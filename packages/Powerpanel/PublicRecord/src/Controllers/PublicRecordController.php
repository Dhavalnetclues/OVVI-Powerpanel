<?php

namespace Powerpanel\PublicRecord\Controllers;

use App\Helpers\FrontPageContent_Shield;
use App\Helpers\MyLibrary;
use App\Http\Controllers\FrontController;
use App\Http\Traits\slug;
use App\Role_user;
use Powerpanel\CmsPage\Models\CmsPage;
use Powerpanel\NewsCategory\Models\NewsCategory;
use Powerpanel\PublicRecordCategory\Models\PublicRecordCategory;
use Powerpanel\PublicRecord\Models\PublicRecord;
use Powerpanel\RoleManager\Models\Role;
use Request;

class PublicRecordController extends FrontController
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
     * This method loads PublicRecord list view
     * @return  View
     * @since   2018-08-27
     * @author  NetQuick
     */

    public function index()
    {

        $data = array();

        $segment1 = Request::segment(1);
        $segment2 = Request::segment(2);
        $sector = false;
        $sector_slug = '';
        if (($segment1 == "ict" || $segment1 == "water" || $segment1 == "fuel" || $segment1 == "energy") && (!empty($segment2))) {
            $sector = true;
            $sector_slug = Request::segment(1);
        }

        if ($sector) {
            $pagename = $segment2;
        } else {
            $pagename = $segment1;
        }

        $aliasId = slug::resolve_alias($pagename, $sector_slug);
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
            $data['pageContent'] = $pageContent;
            $content = FrontPageContent_Shield::renderBuilder($pageContent->txtDescription)['response'];
            $data['isContent'] = (isset($content) && !empty($content)) ? true : false;
        } else {
            if (isset($pageContent->txtDescription) && !empty($pageContent->txtDescription)) {
                $data['PageData'] = FrontPageContent_Shield::renderBuilder($pageContent->txtDescription);
            }
            $data['pageContent'] = $pageContent;
        }

        $data['txtDescription'] = (!empty($pageContent) ? json_encode($pageContent->toArray()) : '');

//        echo '<pre>';print_r( $data['PageData']);exit;
        $publicRecordCategory = PublicRecordCategory::getCategoriesList();
        $data['publicRecordCategories'] = $publicRecordCategory;

        if (isset($pageContent->varTitle) && !empty($pageContent->varTitle)) {
            view()->share('detailPageTitle', $pageContent->varTitle);
        }
        // End CMS PAGE Front Private, Password Prottected Code
        return view('public-record::frontview.public-record', $data);

    }

    public function detail($alias)
    {
        $catid = false;
        $isCategoryList = false;
        $isCategoryTitle = '';
        $isCategoryAlias = '';

        if (is_numeric($alias)) {
            $publicrecord = PublicRecord::getRecordById($alias);
        } else {
            $id = slug::resolve_alias($alias);
            $publicrecord = PublicRecord::getFrontDetail($id);
        }
        // echo '<pre>';
        // print_r(FrontPageContent_Shield::renderBuilder($publicrecord->txtDescription));die;
        $recordCategoryId = false;
        if (!empty($publicrecord)) {
            $recordCategoryId = $publicrecord->intFKCategory;
        }

        if (!empty($publicrecord)) {
            $metaInfo = array('varMetaTitle' => $publicrecord->varMetaTitle, 'varMetaKeyword' => $publicrecord->varMetaKeyword, 'varMetaDescription' => $publicrecord->varMetaDescription);
            if (isset($publicrecord->varMetaTitle) && !empty($publicrecord->varMetaTitle)) {
                view()->share('META_TITLE', $publicrecord->varMetaTitle);
            }
            if (isset($publicrecord->varMetaKeyword) && !empty($publicrecord->varMetaKeyword)) {
                view()->share('META_KEYWORD', $publicrecord->varMetaKeyword);
            }
            if (isset($publicrecord->varMetaDescription) && !empty($publicrecord->varMetaDescription)) {
                view()->share('META_DESCRIPTION', substr(trim($publicrecord->varMetaDescription), 0, 500));
            }
            if (isset($publicrecord->fkIntImgId) && !empty($publicrecord->fkIntImgId)) {
                $imageLink = \App\Helpers\resize_image::resize($publicrecord->fkIntImgId);
                view()->share('ogImage', $imageLink);
            }
            $breadcrumb = [];
            $data = [];
            $moduelFrontPageUrl = MyLibrary::getFront_Uri('public-record')['uri'];
            $moduleFrontWithCatUrl = ($alias != false) ? $moduelFrontPageUrl : $moduelFrontPageUrl;
            $breadcrumb['title'] = (!empty($publicrecord->varTitle)) ? ucwords($publicrecord->varTitle) : '';
            $breadcrumb['url'] = MyLibrary::getFront_Uri('public-record')['uri'];
            $detailPageTitle = $breadcrumb['title'];
            $breadcrumb = $breadcrumb;
            $data['moduleTitle'] = 'PublicRecord';
            $newsAllCategoriesArr = NewsCategory::getAllCategoriesFrontSidebarList();
            $data['newsAllCategoriesArr'] = $newsAllCategoriesArr;
            $data['modulePageUrl'] = $moduelFrontPageUrl;
            $data['moduleFrontWithCatUrl'] = $moduleFrontWithCatUrl;
            $data['isCategoryList'] = $isCategoryList;
            $data['isCategoryTitle'] = $isCategoryTitle;
            $data['isCategoryAlias'] = $isCategoryAlias;
            $data['public-record'] = $publicrecord;
            $data['alias'] = $alias;
            $data['metaInfo'] = $metaInfo;
            $data['breadcrumb'] = $breadcrumb;
            $data['detailPageTitle'] = 'PublicRecord';
            $data['txtDescription'] = FrontPageContent_Shield::renderBuilder($publicrecord->txtDescription)['response'];

            return view('public-record::frontview.public-record-detail', $data);
        } else {
            abort(404);
        }
    }

    public function fetchData(Request $request)
    {
        $requestArr = Request::all();
        if (isset($requestArr['pageName']) && !empty($requestArr['pageName'])) {
            if (is_numeric($requestArr['pageName']) && (int) $requestArr['pageName'] > 0) {
                $aliasId = $requestArr['pageName'];
            } else {
                $aliasId = slug::resolve_alias($requestArr['pageName']);
            }

            if (is_numeric($aliasId)) {
                $pageContent = CmsPage::getPageContentByPageAlias($aliasId);
                if (!isset($pageContent->id)) {
                    $pageContent = CmsPage::getPageByPageId($aliasId, false);
                }
            }

            $pageContentcms = CmsPage::getPageContentByPageAlias($aliasId);

            $data['PageData'] = '';
            if (isset($pageContentcms) && $pageContentcms->chrPageActive == 'PR') {
                if ($pageContentcms->UserID == $user_id) {
                    if (isset($pageContent->txtDescription) && !empty($pageContent->txtDescription)) {
                        $data['PageData'] = FrontPageContent_Shield::renderBuilder($pageContent->txtDescription);
                    }
                } else {
                    return redirect(url('/'));
                }
            } else if (isset($pageContentcms) && $pageContentcms->chrPageActive == 'PP') {
                $data['PassPropage'] = 'PP';
                $data['Pageid'] = $pageContentcms->id;
            } else {
                if (isset($pageContent->txtDescription) && !empty($pageContent->txtDescription)) {
                    $txtDesc = json_decode($pageContent->txtDescription);

                    foreach ($txtDesc as $key => $value) {
                        if ($value->type == 'publicRecord_template') {
                            if (isset($requestArr['month']) && !empty($requestArr['month'])) {
                                $value->val->filter['month'] = $requestArr['month'];
                            }
                            if (isset($requestArr['year']) && !empty($requestArr['year'])) {
                                $value->val->filter['year'] = $requestArr['year'];
                            }
                             if (isset($requestArr['limits']) && !empty($requestArr['limits'])) {
                                $value->val->filter['limits'] = $requestArr['limits'];
                            }
                            if (isset($requestArr['pageNumber']) && !empty($requestArr['pageNumber'])) {
                                $value->val->filter['pageNumber'] = $requestArr['pageNumber'];
                            }
                            if (isset($requestArr['category']) && !empty($requestArr['category'])) {
                                $value->val->filter['category'] = $requestArr['category'];
                            }
                        }
                    }
                    $pageContent->txtDescription = json_encode($txtDesc);

                    $response = FrontPageContent_Shield::renderBuilder($pageContent->txtDescription);
                    return $response;
                }

            }

        }
    }

}
