<?php

namespace Powerpanel\BoardOfDirectors\Controllers;

use App\Helpers\FrontPageContent_Shield;
use App\Helpers\MyLibrary;
use App\Http\Controllers\FrontController;
use App\Http\Traits\slug;
use Illuminate\Support\Facades\Request;
use Powerpanel\BoardOfDirectors\Models\BoardOfDirectors;
use Powerpanel\CmsPage\Models\CmsPage;
use App\Role_user;
use Powerpanel\RoleManager\Models\Role;

class BoardOfDirectorsController extends FrontController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data = array();
//        $pagename = Request::segment(1);
//        if (is_numeric($pagename) && (int) $pagename > 0) {
//            $aliasId = $pagename;
//        } else {
//            $aliasId = slug::resolve_alias($pagename);
//        }
        
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
            $pageId = Request::segment(2);
            $ = CmsPage::getPageByPageId($pageId, false);
        }else{
            $ = CmsPage::getByPageAlias($aliasId);
        }

        // if (null !== Request::segment(2) && Request::segment(2) != 'preview') {
        //     if (is_numeric(Request::segment(2))) {
        //         $cmsPageId = Request::segment(2);
        //         $ = CmsPage::getPageByPageId($cmsPageId, false);
        //     } elseif (Request::segment(2) == 'print') {
        //         $ = CmsPage::getByPageAlias($aliasId);
        //     }
        // } elseif (is_numeric($aliasId)) {
        //     $ = CmsPage::getByPageAlias($aliasId);
        //     if (!isset($->id)) {
        //         $ = CmsPage::getPageByPageId($aliasId, false);
        //     }
        // }

        if (isset(auth()->user()->id)) {
            $user_id = auth()->user()->id;
            $role = Role::getRecordById(Role_user::getRecordBYModelId($user_id)->role_id)->name;
        } else {
            $user_id = '';
            $role = '';
        }

        $data['PageData'] = '';
        if (isset($) && $->chrPageActive == 'PR') {
            if ($->UserID == $user_id || $role == 'netquick_admin') {
                if (isset($->txtDescription) && !empty($->txtDescription)) {
                    $data['PageData'] = FrontPageContent_Shield::renderBuilder($->txtDescription);
                }
            } else {
                return redirect(url('/'));
            }
        } else if (isset($) && $->chrPageActive == 'PP') {
            $data['PassPropage'] = 'PP';
            $data['tablename'] = 'cms_page';
            $data['Pageid'] = $->id;
            $content = FrontPageContent_Shield::renderBuilder($->txtDescription)['response'];
            $data['isContent'] = (isset($content) && !empty($content)) ? true:false;
        } else {

            if (isset($->txtDescription) && !empty($->txtDescription)) {
                $data['PageData'] = FrontPageContent_Shield::renderBuilder($->txtDescription);
            }
            $data[''] = $;
        }

        if (isset($->varTitle) && !empty($->varTitle)) {
            view()->share('detailPageTitle', $->varTitle);
        }
        return view('boardofdirectors::frontview.board_of_director', $data);
    }

    public function detail($alias)
    {
        $catid = false;
        $isCategoryList = false;
        $isCategoryTitle = '';
        $isCategoryAlias = '';

        if (is_numeric($alias)) {
            $boardofdirectors = BoardOfDirectors::getRecordById($alias);
        } else {
            $id = slug::resolve_alias($alias);
            $boardofdirectors = BoardOfDirectors::getFrontDetail($id);
        }
        $recordCategoryId = false;
        if (!empty($boardofdirectors)) {
            $recordCategoryId = $boardofdirectors->intFKCategory;
        }

        if (!empty($boardofdirectors)) {
            $metaInfo = array('varMetaTitle' => $boardofdirectors->varMetaTitle, 'varMetaKeyword' => $boardofdirectors->varMetaKeyword, 'varMetaDescription' => $boardofdirectors->varMetaDescription);
            if (isset($boardofdirectors->varMetaTitle) && !empty($boardofdirectors->varMetaTitle)) {
                view()->share('META_TITLE', $boardofdirectors->varMetaTitle);
            }
            if (isset($boardofdirectors->varMetaKeyword) && !empty($boardofdirectors->varMetaKeyword)) {
                view()->share('META_KEYWORD', $boardofdirectors->varMetaKeyword);
            }
            if (isset($boardofdirectors->varMetaDescription) && !empty($boardofdirectors->varMetaDescription)) {
                view()->share('META_DESCRIPTION', substr(trim($boardofdirectors->varMetaDescription), 0, 500));
            }
            if (isset($boardofdirectors->fkIntImgId) && !empty($boardofdirectors->fkIntImgId)) {
                $imageLink = \App\Helpers\resize_image::resize($boardofdirectors->fkIntImgId);
                view()->share('ogImage', $imageLink);
            }
            $breadcrumb = [];
            $data = [];
            $moduelFrontPageUrl = MyLibrary::getFront_Uri('boardofdirectors')['uri'];
            $moduleFrontWithCatUrl = ($alias != false) ? $moduelFrontPageUrl : $moduelFrontPageUrl;
            $breadcrumb['title'] = (!empty($boardofdirectors->varTitle)) ? ucwords($boardofdirectors->varTitle) : '';
            $breadcrumb['url'] = MyLibrary::getFront_Uri('boardofdirectors')['uri'];
            $detailPageTitle = $breadcrumb['title'];
            $breadcrumb = $breadcrumb;
            $data['moduleTitle'] = 'boardofdirectors';
            $data['modulePageUrl'] = $moduelFrontPageUrl;
            $data['moduleFrontWithCatUrl'] = $moduleFrontWithCatUrl;
            $data['isCategoryList'] = $isCategoryList;
            $data['isCategoryTitle'] = $isCategoryTitle;
            $data['isCategoryAlias'] = $isCategoryAlias;
            $data['boardofdirectors'] = $boardofdirectors;
            $data['alias'] = $alias;
            $data['metaInfo'] = $metaInfo;
            $data['breadcrumb'] = $breadcrumb;
            $data['detailPageTitle'] = 'boardofdirectors';

            $data['txtDescription'] = FrontPageContent_Shield::renderBuilder($boardofdirectors->txtDescription)['response'];

            return view('boardofdirectors::frontview.board_of_director_detail', $data);
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
                $ = CmsPage::getByPageAlias($aliasId);

                if (!isset($->id)) {
                    $ = CmsPage::getPageByPageId($aliasId, false);
                }
            }

            $cms = CmsPage::getByPageAlias($aliasId);

            $data['PageData'] = '';
            if (isset($cms) && $cms->chrPageActive == 'PR') {
                if ($cms->UserID == $user_id) {
                    if (isset($->txtDescription) && !empty($->txtDescription)) {
                        $data['PageData'] = FrontPageContent_Shield::renderBuilder($->txtDescription);
                    }
                } else {
                    return redirect(url('/'));
                }
            } else if (isset($cms) && $cms->chrPageActive == 'PP') {
                $data['PassPropage'] = 'PP';
                $data['Pageid'] = $cms->id;
            } else {
                if (isset($->txtDescription) && !empty($->txtDescription)) {
                    $txtDesc = json_decode($->txtDescription);

                    foreach ($txtDesc as $key => $value) {
                        if ($value->type == 'boardofdirectors_template') {

                            if (isset($requestArr['pageNumber']) && !empty($requestArr['pageNumber'])) {
                                $value->val->filter['pageNumber'] = $requestArr['pageNumber'];
                            }
                        }
                    }

                    $->txtDescription = json_encode($txtDesc);
                    $response = FrontPageContent_Shield::renderBuilder($->txtDescription);

                    return $response;
                }
            }
        }
    }

    public function PagePassURLListing()
    {
        $record = Request::input();
        $pagedata = DB::table($record['tablename'])
            ->select('*')
            ->where('id', '=', $record['id'])
            ->first();
        if ($pagedata->varPassword == $record['passwordprotect']) {
            $html = FrontPageContent_Shield::renderBuilder($pagedata->txtDescription);
            echo json_encode($html['response']);
        } else {
            $response = array("error" => 1, 'validatorErrors' => 'Password Does Not Match');
            echo json_encode($response);
        }
    }

}
