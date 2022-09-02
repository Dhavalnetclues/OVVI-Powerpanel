<?php

namespace Powerpanel\Rfps\Controllers;

use App\Helpers\FrontPageContent_Shield;
use App\Helpers\MyLibrary;
use App\Http\Controllers\FrontController;
use App\Http\Traits\slug;
use App\Role_user;
use Powerpanel\CmsPage\Models\CmsPage;
use Powerpanel\RfpsCategory\Models\RfpsCategory;
use Powerpanel\Rfps\Models\Rfps;
use Powerpanel\RoleManager\Models\Role;
use Request;

class RfpsController extends FrontController
{

    use slug;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * This method loads Rfps list view
     * @return  View
     * @since   2018-08-27
     * @author  NetQuick
     */

    public function index()
    {

        $data = array();
        $pagename = Request::segment(1);

        
        $aliasId = slug::resolve_alias($pagename);
        if (Request::segment(3) == 'preview') {
            $cmsPageId = Request::segment(2);
            $pageContent = CmsPage::getPageByPageId($cmsPageId, false);
        } else {
            $pageContent = CmsPage::getPageContentByPageAlias($aliasId);
        }
        // Start CMS PAGE Front Private, Password Prottected Code

        
        if (isset(auth()->user()->id)) {
            $user_id = auth()->user()->id;
            $role = Role::getRecordById(Role_user::getRecordBYModelId($user_id)->role_id)->name;
        } else {
            $user_id = '';
            $role = '';
        }

        $data['PageData'] = '';
        if (isset($pageContent) && $pageContent->chrPageActive == 'PR') {
            if ($pageContent->UserID == $user_id || $role == 'netquick_admin') {
                if (isset($pageContent->txtDescription) && !empty($pageContent->txtDescription)) {
                    $data['PageData'] = FrontPageContent_Shield::renderBuilder($pageContent->txtDescription);
                }
            } else {
                return redirect(url('/'));
            }
        } else if (isset($pageContent) && $pageContent->chrPageActive == 'PP') {
            $data['PassPropage'] = 'PP';
            $data['tablename'] = 'cms_page';
            $data['Pageid'] = $pageContent->id;
            $content = FrontPageContent_Shield::renderBuilder($pageContent->txtDescription)['response'];
            $data['isContent'] = (isset($content) && !empty($content)) ? true : false;
        } else {
            if (isset($pageContent->txtDescription) && !empty($pageContent->txtDescription)) {
                $data['PageData'] = FrontPageContent_Shield::renderBuilder($pageContent->txtDescription);
            }
            $data['pageContent'] = $pageContent;
        }

        if (isset($pageContent->varTitle) && !empty($pageContent->varTitle)) {
            view()->share('detailPageTitle', $pageContent->varTitle);
        }
        // End CMS PAGE Front Private, Password Prottected Code
        return view('rfps::frontview.rfps', $data);

    }

    public function detail($alias)
    {
        $catid = false;
        $isCategoryList = false;
        $isCategoryTitle = '';
        $isCategoryAlias = '';

        if (is_numeric($alias)) {
            $rfps = Rfps::getRecordById($alias);
        } else {
            $id = slug::resolve_alias($alias);
            $rfps = Rfps::getFrontDetail($id);
        }
        // echo '<pre>';
        // print_r(FrontPageContent_Shield::renderBuilder($rfps->txtDescription));die;
        $recordCategoryId = false;
        if (!empty($rfps)) {
            $recordCategoryId = $rfps->intFKCategory;
        }

        if (!empty($rfps)) {
            $metaInfo = array('varMetaTitle' => $rfps->varMetaTitle, 'varMetaKeyword' => $rfps->varMetaKeyword, 'varMetaDescription' => $rfps->varMetaDescription);
            if (isset($rfps->varMetaTitle) && !empty($rfps->varMetaTitle)) {
                view()->share('META_TITLE', $rfps->varMetaTitle);
            }
            if (isset($rfps->varMetaKeyword) && !empty($rfps->varMetaKeyword)) {
                view()->share('META_KEYWORD', $rfps->varMetaKeyword);
            }
            if (isset($rfps->varMetaDescription) && !empty($rfps->varMetaDescription)) {
                view()->share('META_DESCRIPTION', substr(trim($rfps->varMetaDescription), 0, 500));
            }
            if (isset($rfps->fkIntImgId) && !empty($rfps->fkIntImgId)) {
                $imageLink = \App\Helpers\resize_image::resize($rfps->fkIntImgId);
                view()->share('ogImage', $imageLink);
            }
            $breadcrumb = [];
            $data = [];
            $moduelFrontPageUrl = MyLibrary::getFront_Uri('rfps')['uri'];
            $moduleFrontWithCatUrl = ($alias != false) ? $moduelFrontPageUrl : $moduelFrontPageUrl;
            $breadcrumb['title'] = (!empty($rfps->varTitle)) ? ucwords($rfps->varTitle) : '';
            $breadcrumb['url'] = MyLibrary::getFront_Uri('rfps')['uri'];
            $detailPageTitle = $breadcrumb['title'];
            $breadcrumb = $breadcrumb;
            $data['moduleTitle'] = 'Rfps';
            $rfpsAllCategoriesArr = RfpsCategory::getAllCategoriesFrontSidebarList();
            $data['rfpsAllCategoriesArr'] = $rfpsAllCategoriesArr;
            $data['modulePageUrl'] = $moduelFrontPageUrl;
            $data['moduleFrontWithCatUrl'] = $moduleFrontWithCatUrl;
            $data['isCategoryList'] = $isCategoryList;
            $data['isCategoryTitle'] = $isCategoryTitle;
            $data['isCategoryAlias'] = $isCategoryAlias;
            $data['rfps'] = $rfps;
            $data['alias'] = $alias;
            $data['metaInfo'] = $metaInfo;
            $data['breadcrumb'] = $breadcrumb;
            $data['detailPageTitle'] = 'Rfps';
            $data['txtDescription'] = FrontPageContent_Shield::renderBuilder($rfps->txtDescription)['response'];

            return view('rfps::frontview.rfps-detail', $data);
        } else {
            abort(404);
        }
    }

}
