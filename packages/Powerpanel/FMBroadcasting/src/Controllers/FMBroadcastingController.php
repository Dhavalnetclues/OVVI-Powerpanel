<?php

namespace Powerpanel\FMBroadcasting\Controllers;

use App\Helpers\FrontPageContent_Shield;
use App\Http\Controllers\FrontController;
use App\Http\Traits\slug;
use App\Role_user;
use Powerpanel\CmsPage\Models\CmsPage;
use Powerpanel\FMBroadcasting\Models\FMBroadcasting;
use Powerpanel\RoleManager\Models\Role;
use Request;

class FMBroadcastingController extends FrontController
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
     * This method loads FMBroadcasting list view
     * @return  View
     * @since   2018-08-27
     * @author  NetQuick
     */

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
            $content = FrontPageContent_Shield::renderBuilder($pageContent->txtDescription)['response'];
            $data['isContent'] = (isset($content) && !empty($content)) ? true : false;
        } else {
            if (isset($pageContent->txtDescription) && !empty($pageContent->txtDescription)) {
                $data['txtDescription'] = json_encode($pageContent->toArray());
                $data['PageData'] = FrontPageContent_Shield::renderBuilder($pageContent->txtDescription);
            }
            $data['pageContent'] = $pageContent;
        }

        if (isset($pageContent->varTitle) && !empty($pageContent->varTitle)) {
            view()->share('detailPageTitle', $pageContent->varTitle);
        }
        // End CMS PAGE Front Private, Password Prottected Code
        return view('fmbroadcasting::frontview.fmbroadcasting', $data);

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
                        if ($value->type == 'fmbroadcasting_template') {
                            
                            
                            if (isset($requestArr['pageNumber']) && !empty($requestArr['pageNumber'])) {
                                $value->val->filter['pageNumber'] = $requestArr['pageNumber'];
                            }
                            if (isset($requestArr['limits']) && !empty($requestArr['limits'])) {
                                $value->val->filter['limits'] = $requestArr['limits'];
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
