<?php

namespace Powerpanel\Companies\Controllers;

use App\Helpers\FrontPageContent_Shield;
use App\Helpers\MyLibrary;
use App\Http\Controllers\FrontController;
use App\Http\Traits\slug;
use App\Role_user;
use Powerpanel\CmsPage\Models\CmsPage;
use Powerpanel\Companies\Models\Companies;
use Powerpanel\CompanyCategory\Models\CompanyCategory;
use Powerpanel\RoleManager\Models\Role;
use Request;

class CompaniesController extends FrontController
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

    public function index()
    {

        $data = array();
        $pagename = Request::segment(1);

        $aliasId = slug::resolve_alias($pagename);
        if (Request::segment(3) == 'preview') {
            $pageId = Request::segment(2);
            $pageContent = CmsPage::getPageByPageId($pageId, false);
        } else {
            $pageContent = CmsPage::getPageContentByPageAlias($aliasId);
        }

        if (!isset($pageContent->id)) {
            abort('404');
        }

        $CONTENT = ' <h2 class="no_record coming_soon_rcd"> Coming Soon</h2>';
        if (!empty($pageContent->txtDescription)) {
            $CONTENT = $pageContent->txtDescription;
        }

        // Start CMS PAGE Front Private, Password Prottected Code

        $pageContentcms = CmsPage::getPageContentByPageAlias($aliasId);
        if (isset(auth()->user()->id)) {
            $user_id = auth()->user()->id;
            $role = Role::getRecordById(Role_user::getRecordBYModelId($user_id)->role_id)->name;
        } else {
            $user_id = '';
            $role = '';
        }

        $data['PageData'] = '';
        if (isset($pageContentcms) && $pageContentcms->chrPageActive == 'PR') {
            if ($pageContentcms->UserID == $user_id || $role == 'netquick_admin') {
                if (isset($pageContent->txtDescription) && !empty($pageContent->txtDescription)) {
                    $data['PageData'] = FrontPageContent_Shield::renderBuilder($pageContent->txtDescription);
                }
            } else {
                return redirect(url('/'));
            }
        } else if (isset($pageContentcms) && $pageContentcms->chrPageActive == 'PP') {
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
        return view('companies::frontview.companies', $data);

    }

    public function detail($alias)
    {
        $catid = false;
        $isCategoryList = false;
        $isCategoryTitle = '';
        $isCategoryAlias = '';

        if (is_numeric($alias)) {
            $companies = Companies::getRecordById($alias);
        } else {
            $id = slug::resolve_alias($alias);
            $companies = Companies::getFrontDetail($id);
        }
        $recordCategoryId = false;
        if (!empty($companies)) {
            $recordCategoryId = $companies->intFKCategory;
        }

        if (!empty($companies)) {
            $metaInfo = array('varMetaTitle' => $companies->varMetaTitle, 'varMetaKeyword' => $companies->varMetaKeyword, 'varMetaDescription' => $companies->varMetaDescription);
            if (isset($companies->varMetaTitle) && !empty($companies->varMetaTitle)) {
                view()->share('META_TITLE', $companies->varMetaTitle);
            }
            if (isset($companies->varMetaKeyword) && !empty($companies->varMetaKeyword)) {
                view()->share('META_KEYWORD', $companies->varMetaKeyword);
            }
            if (isset($companies->varMetaDescription) && !empty($companies->varMetaDescription)) {
                view()->share('META_DESCRIPTION', substr(trim($companies->varMetaDescription), 0, 500));
            }
            if (isset($companies->fkIntImgId) && !empty($companies->fkIntImgId)) {
                $imageLink = \App\Helpers\resize_image::resize($companies->fkIntImgId);
                view()->share('ogImage', $imageLink);
            }
            $breadcrumb = [];
            $data = [];
            $moduelFrontPageUrl = MyLibrary::getFront_Uri('companies')['uri'];
            $moduleFrontWithCatUrl = ($alias != false) ? $moduelFrontPageUrl : $moduelFrontPageUrl;
            $breadcrumb['title'] = (!empty($companies->varTitle)) ? ucwords($companies->varTitle) : '';
            $breadcrumb['url'] = MyLibrary::getFront_Uri('companies')['uri'];
            $detailPageTitle = $breadcrumb['title'];
            $breadcrumb = $breadcrumb;
            $data['moduleTitle'] = 'Companies';
            $companiesAllCategoriesArr = CompanyCategory::getAllCategoriesFrontSidebarList();
            $data['companiesAllCategoriesArr'] = $companiesAllCategoriesArr;
            $data['modulePageUrl'] = $moduelFrontPageUrl;
            $data['moduleFrontWithCatUrl'] = $moduleFrontWithCatUrl;
            $data['isCategoryList'] = $isCategoryList;
            $data['isCategoryTitle'] = $isCategoryTitle;
            $data['isCategoryAlias'] = $isCategoryAlias;
            $data['companies'] = $companies;
            $data['alias'] = $alias;
            $data['metaInfo'] = $metaInfo;
            $data['breadcrumb'] = $breadcrumb;
            $data['detailPageTitle'] = 'Companies';
            $data['txtDescription'] = FrontPageContent_Shield::renderBuilder($companies->txtDescription);

            return view('companies::frontview.companies-detail', $data);
        } else {
            abort(404);
        }
    }

}
