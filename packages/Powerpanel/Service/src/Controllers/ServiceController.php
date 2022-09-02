<?php

namespace Powerpanel\Service\Controllers;

use App\Helpers\FrontPageContent_Shield;
use App\Helpers\MyLibrary;
use App\Http\Controllers\FrontController;
use App\Http\Traits\slug;
use App\Role_user;
use Powerpanel\ServiceCategory\Models\ServiceCategory;
use Powerpanel\ContactInfo\Models\ContactInfo;
use Powerpanel\Service\Models\Service;
use Powerpanel\CmsPage\Models\CmsPage;
use Powerpanel\RoleManager\Models\Role;
use Request;

class ServiceController extends FrontController
{
    use slug;

    public function __construct()
    {
    		parent::__construct();
    }


    public function index()
    {

        $data = array();
        $sector = false;
        $sector_slug = '';
        $segment1 = Request::segment(1);
        $segment2 = Request::segment(2);

        if (($segment1 == "ict" || $segment1 == "water" || $segment1 == "fuel" || $segment1 == "energy") && (!empty($segment2))) {
            $sector = true;
            $sector_slug = Request::segment(1);
        }

        if ($sector) {
            $pagename = $segment2;
        } else {
            $pagename = $segment1;
        }

        $aliasId = slug::resolve_alias($pagename, $sector_slug, 3);

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
        return view('services::frontview.service', $data);
    }


    public function detail($alias)
    {

        $catid = false;
        $isCategoryList = false;
        $isCategoryTitle = '';
        $isCategoryAlias = '';

        if (is_numeric($alias)) {
            $service = Service::getRecordById($alias);
        } else {
            $id = slug::resolve_alias($alias);
            $service = Service::getFrontDetail($id);
        }

        $recordCategoryId = false;
        if (!empty($service)) {
            $recordCategoryId = $service->intFKCategory;
        }

        if (!empty($service)) {
            $metaInfo = array('varMetaTitle' => $service->varMetaTitle, 'varMetaKeyword' => $service->varMetaKeyword, 'varMetaDescription' => $service->varMetaDescription);
            if (isset($service->varMetaTitle) && !empty($service->varMetaTitle)) {
                view()->share('META_TITLE', $service->varMetaTitle);
            }
            if (isset($service->varMetaKeyword) && !empty($service->varMetaKeyword)) {
                view()->share('META_KEYWORD', $service->varMetaKeyword);
            }
            if (isset($service->varMetaDescription) && !empty($service->varMetaDescription)) {
                view()->share('META_DESCRIPTION', substr(trim($service->varMetaDescription), 0, 500));
            }
            if (isset($service->fkIntImgId) && !empty($service->fkIntImgId)) {
                $imageLink = \App\Helpers\resize_image::resize($service->fkIntImgId);
                view()->share('ogImage', $imageLink);
            }

            $allServices = Service::getAllServices(false,false,false);
            $primaryContact = ContactInfo::Primary_ContactInfo();

            $breadcrumb = [];
            $data = [];
            $moduelFrontPageUrl = MyLibrary::getFront_Uri('service')['uri'];
            $moduleFrontWithCatUrl = ($alias != false) ? $moduelFrontPageUrl : $moduelFrontPageUrl;

            $breadcrumb['title'] = (!empty($service->varTitle)) ? ucwords($service->varTitle) : '';
            $breadcrumb['url'] = $moduleFrontWithCatUrl;
            $breadcrumb['module'] = 'services';
            $breadcrumb['inner_title'] = '';

            $detailPageTitle = $breadcrumb['title'];
            $data['moduleTitle'] = 'services';
            $data['modulePageUrl'] = $moduelFrontPageUrl;
            $data['allServices'] = $allServices;
            $data['primaryContact'] = $primaryContact;
            $data['moduleFrontWithCatUrl'] = $moduleFrontWithCatUrl;
            $data['isCategoryList'] = $isCategoryList;
            $data['isCategoryTitle'] = $isCategoryTitle;
            $data['isCategoryAlias'] = $isCategoryAlias;
            $data['services'] = $service;
            $data['alias'] = $alias;
            $data['metaInfo'] = $metaInfo;
            $data['breadcrumb'] = $breadcrumb;
            $data['detailPageTitle'] = $detailPageTitle;
            $data['txtDescription'] = FrontPageContent_Shield::renderBuilder($service->txtDescription)['response'];

            return view('services::frontview.services-detail', $data);
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
                        if ($value->type == 'services_template') {

                            if (isset($requestArr['pageNumber']) && !empty($requestArr['pageNumber'])) {
                                $value->val->filter['pageNumber'] = $requestArr['pageNumber'];
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
