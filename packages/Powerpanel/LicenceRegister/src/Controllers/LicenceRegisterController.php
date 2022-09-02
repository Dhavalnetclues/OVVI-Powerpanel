<?php
namespace Powerpanel\LicenceRegister\Controllers;

use App\Helpers\FrontPageContent_Shield;
use App\Helpers\MyLibrary;
use App\Http\Controllers\FrontController;
use App\Http\Traits\slug;
use Illuminate\Support\Facades\Request;
use Powerpanel\CmsPage\Models\CmsPage;
use Powerpanel\LicenceRegister\Models\LicenceRegister;
use Powerpanel\Service\Models\Service;
use App\Role_user;
use Powerpanel\RoleManager\Models\Role;

class LicenceRegisterController extends FrontController
{
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
     * This method loads licence-register list view
     * @return  View
     * @since   2020-01-17
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

        $aliasId = slug::resolve_alias($pagename,$sector_slug);
        if (Request::segment(3) == 'preview') {
            $cmsPageId = Request::segment(2);
            $pageContent = CmsPage::getPageByPageId($cmsPageId, false);
        }else{
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

        $selectstatus = array('Issued', 'Reissued', 'Renewed', 'Pending', 'Revoked', 'Surrendered', 'Expired', 'Consolidated', 'Suspended');
        $selectservices = Service::getServicesForRegisterOfApplications();

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
            $data['isContent'] = (isset($content) && !empty($content)) ? true:false;
        } else {
            if (isset($pageContent->txtDescription) && !empty($pageContent->txtDescription)) {
                $data['PageData'] = FrontPageContent_Shield::renderBuilder($pageContent->txtDescription);
            }
            $data['pageContent'] = $pageContent;
        }

        $data['allStatus'] = $selectstatus;

        $data['allServices'] = $selectservices;

        $data['txtDescription'] = json_encode($pageContent->toArray());

        if (isset($pageContent->varTitle) && !empty($pageContent->varTitle)) {
            view()->share('detailPageTitle', $pageContent->varTitle);
        }
        // End CMS PAGE Front Private, Password Prottected Code
        return view('licence-register::frontview.licence-register', $data);
    }

    /**
     * This method loads licence-register detail view
     * @param   Alias of record
     * @return  View
     * @since   2020-01-17
     * @author  NetQuick
     */
    public function detail($alias) {
        
        $catid = false;
        $isCategoryList = false;
        $isCategoryTitle = '';
        $isCategoryAlias = '';

        if (is_numeric($alias)) {
            $licenceregister = LicenceRegister::getRecordById($alias);
        } else {
            $id = slug::resolve_alias($alias);
            $licenceregister = LicenceRegister::getFrontRecordDetail($id);
        }
        $recordCategoryId = false;
        if (!empty($licenceregister)) {
            $recordCategoryId = $licenceregister->intFKCategory;
        }

        if (!empty($licenceregister)) {
            $metaInfo = array('varMetaTitle' => $licenceregister->varMetaTitle, 'varMetaKeyword' => $licenceregister->varMetaKeyword, 'varMetaDescription' => $licenceregister->varMetaDescription);
            if (isset($licenceregister->varMetaTitle) && !empty($licenceregister->varMetaTitle)) {
                view()->share('META_TITLE', $licenceregister->varMetaTitle);
            }
            if (isset($licenceregister->varMetaKeyword) && !empty($licenceregister->varMetaKeyword)) {
                view()->share('META_KEYWORD', $licenceregister->varMetaKeyword);
            }
            if (isset($licenceregister->varMetaDescription) && !empty($licenceregister->varMetaDescription)) {
                view()->share('META_DESCRIPTION', substr(trim($licenceregister->varMetaDescription), 0, 500));
            }
            if (isset($licenceregister->fkIntImgId) && !empty($licenceregister->fkIntImgId)) {
                $imageLink = \App\Helpers\resize_image::resize($licenceregister->fkIntImgId);
                view()->share('ogImage', $imageLink);
            }
            $breadcrumb = [];
            $data = [];
            $moduelFrontPageUrl = MyLibrary::getFront_Uri('licence-register')['uri'];
            $moduleFrontWithCatUrl = ($alias != false) ? $moduelFrontPageUrl : $moduelFrontPageUrl;
            $breadcrumb['title'] = (!empty($licenceregister->varTitle)) ? ucwords($licenceregister->varTitle) : '';
            $breadcrumb['url'] = MyLibrary::getFront_Uri('licence-register',false,true)['uri'];
            $detailPageTitle = $breadcrumb['title'];
            $breadcrumb = $breadcrumb;
            $data['moduleTitle'] = 'licence-register';
            $data['modulePageUrl'] = $moduelFrontPageUrl;
            $data['moduleFrontWithCatUrl'] = $moduleFrontWithCatUrl;
            $data['isCategoryList'] = $isCategoryList;
            $data['isCategoryTitle'] = $isCategoryTitle;
            $data['isCategoryAlias'] = $isCategoryAlias;
            $data['licenceRegister'] = $licenceregister;
            $data['alias'] = $alias;
            $data['metaInfo'] = $metaInfo;
            $data['breadcrumb'] = $breadcrumb;
            $data['detailPageTitle'] = 'licence-register';
            $data['txtDescription'] = FrontPageContent_Shield::renderBuilder($licenceregister->txtDescription);

            return view('licence-register::frontview.licence-register-detail', $data);
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
                        if ($value->type == 'licenceregister_template') {
                            if (isset($requestArr['serviceValue']) && !empty($requestArr['serviceValue'])) {
                                $value->val->filter['serviceValue'] = $requestArr['serviceValue'];
                            }
                            if (isset($requestArr['pageNumber']) && !empty($requestArr['pageNumber'])) {
                                $value->val->filter['pageNumber'] = $requestArr['pageNumber'];
                            }
                            if (isset($requestArr['statusValue']) && !empty($requestArr['statusValue'])) {
                                $value->val->filter['statusValue'] = $requestArr['statusValue'];
                            }
                            if(isset($requestArr['sectorName']) && !empty($requestArr['sectorName'])){
                                $value->val->filter['sectorName'] = $requestArr['sectorName'];
                            }
                            if (isset($requestArr['searchValue']) && !empty($requestArr['searchValue'])) {
                                $value->val->filter['searchValue'] = $requestArr['searchValue'];
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
