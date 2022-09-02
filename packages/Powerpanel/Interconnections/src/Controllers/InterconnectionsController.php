<?php
namespace Powerpanel\Interconnections\Controllers;

use App\Helpers\FrontPageContent_Shield;
use App\Http\Controllers\FrontController;
use App\Http\Traits\slug;
use config;
use Powerpanel\CmsPage\Models\CmsPage;
use Powerpanel\Interconnections\Models\Interconnections;
use Request;
use App\Role_user;
use Powerpanel\RoleManager\Models\Role;


class InterconnectionsController extends FrontController
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        Config::set('Constant.MODULE.MODEL_ALIAS', 'Powerpanel\Interconnections\Models\\');
    }

    public function index()
    {
        $data = array();
        $segment1 = Request::segment(1);
        $segment2 = Request::segment(2);

        $sector_slug = '';
        $sector = false;
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

        $data['interconnectionsCategory'] = Interconnections::getInterconnectionsParent();

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

        $data['txtDescription'] = json_encode($pageContent->toArray());

        if (isset($pageContent->varTitle) && !empty($pageContent->varTitle)) {
            view()->share('detailPageTitle', $pageContent->varTitle);
        }
        // End CMS PAGE Front Private, Password Prottected Code

        return view('interconnections::frontview.interconnections', $data);
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
                        if ($value->type == 'interconnections_template') {
                            if (isset($requestArr['month']) && !empty($requestArr['month'])) {
                                $value->val->filter['month'] = $requestArr['month'];
                            }
                            if (isset($requestArr['year']) && !empty($requestArr['year'])) {
                                $value->val->filter['year'] = $requestArr['year'];
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
