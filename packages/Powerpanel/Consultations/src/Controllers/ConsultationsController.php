<?php

namespace Powerpanel\Consultations\Controllers;

use App\Helpers\FrontPageContent_Shield;
use App\Helpers\MyLibrary;
use App\Http\Controllers\FrontController;
use App\Http\Traits\slug;
use App\Role_user;
use Powerpanel\CmsPage\Models\CmsPage;
use Powerpanel\Consultations\Models\Consultations;
use Powerpanel\RoleManager\Models\Role;
// use Request;
use Illuminate\Http\Request;

class ConsultationsController extends FrontController
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
     * This method loads News list view
     * @return  View
     * @since   2018-08-27
     * @author  NetQuick
     */

    public function index(Request $request)
    {
        $data = array();
       $data = array();

        $segment1 = $request->segment(1);
        $segment2 = $request->segment(2);

        $sector = false;
        $sector_slug = '';
        if (($segment1 == "ict" || $segment1 == "water" || $segment1 == "fuel" || $segment1 == "energy") && (!empty($segment2))) {
            $sector = true;
            $sector_slug = $request->segment(1);
        }

        if ($sector) {
            $pagename = $segment2;
        } else {
            $pagename = $segment1;
        }

        $aliasId = slug::resolve_alias($pagename,$sector_slug);
        if ($request->segment(3) == 'preview') {
            $cmsPageId = $request->segment(2);
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

        $data['txtDescription'] = json_encode($pageContent->toArray());

        if (isset($pageContent->varTitle) && !empty($pageContent->varTitle)) {
            view()->share('detailPageTitle', $pageContent->varTitle);
        }
        // End CMS PAGE Front Private, Password Prottected Code
        return view('consultations::frontview.consultations', $data);
    }

    public function detail($alias)
    {
        $catid = false;
        $isCategoryList = false;
        $isCategoryTitle = '';
        $isCategoryAlias = '';

        if (is_numeric($alias)) {
            $consultations = Consultations::getRecordById($alias);
        } else {
            $id = slug::resolve_alias($alias);
            $consultations = Consultations::getFrontDetail($id);
        }

        $latestConsultations = Consultations::getFrontLatestConsultations();

        // $recordCategoryId = false;
        // if (!empty($consultations)) {
        //     $recordCategoryId = $consultations->intFKCategory;
        // }
        if (!empty($consultations)) {
            $metaInfo = array('varMetaTitle' => $consultations->varMetaTitle, 'varMetaKeyword' => $consultations->varMetaKeyword, 'varMetaDescription' => $consultations->varMetaDescription);
            if (isset($consultations->varMetaTitle) && !empty($consultations->varMetaTitle)) {
                view()->share('META_TITLE', $consultations->varMetaTitle);
            }
            if (isset($consultations->varMetaKeyword) && !empty($consultations->varMetaKeyword)) {
                view()->share('META_KEYWORD', $consultations->varMetaKeyword);
            }
            if (isset($consultations->varMetaDescription) && !empty($consultations->varMetaDescription)) {
                view()->share('META_DESCRIPTION', substr(trim($consultations->varMetaDescription), 0, 500));
            }
            $breadcrumb = [];
            $data = [];
            $moduelFrontPageUrl = MyLibrary::getFront_Uri('consultations')['uri'];
            $moduleFrontWithCatUrl = ($alias != false) ? $moduelFrontPageUrl : $moduelFrontPageUrl;
            $breadcrumb['title'] = (!empty($consultations->varTitle)) ? ucwords($consultations->varTitle) : '';
            $breadcrumb['url'] = MyLibrary::getFront_Uri('consultations')['uri'];
            $detailPageTitle = $breadcrumb['title'];
            $breadcrumb = $breadcrumb;
            $data['moduleTitle'] = 'consultations';
            $data['modulePageUrl'] = $moduelFrontPageUrl;
            $data['moduleFrontWithCatUrl'] = $moduleFrontWithCatUrl;
            $data['isCategoryList'] = $isCategoryList;
            $data['isCategoryTitle'] = $isCategoryTitle;
            $data['isCategoryAlias'] = $isCategoryAlias;
            $data['tags'] = (isset($consultations->varTags) && !empty($consultations->varTags)) ? explode(",", $consultations->varTags) : '';
            $data['consultations'] = $consultations;
            $data['alias'] = $alias;
            $data['metaInfo'] = $metaInfo;
            $data['latestConsultations'] = $latestConsultations;
            $data['breadcrumb'] = $breadcrumb;
            $data['detailPageTitle'] = 'consultations';
            $data['txtDescription'] = FrontPageContent_Shield::renderBuilder($consultations->txtDescription)['response'];
            return view('consultations::frontview.consultations-detail', $data);
        } else {
            abort(404);
        }
    }

    public function fetchData(Request $request)
    {
        $requestArr = $request->all();

        if($request->ajax()){
            $searchText = $request->input('search_action');
        }

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
                        if ($value->type == 'consultations_template') {
                            if (isset($requestArr['category']) && !empty($requestArr['category'])) {
                                $value->val->filter['category'] = $requestArr['category'];
                            }
                            if (isset($requestArr['year']) && !empty($requestArr['year'])) {
                                $value->val->filter['year'] = $requestArr['year'];
                            }
                            if (isset($requestArr['pageNumber']) && !empty($requestArr['pageNumber'])) {
                                $value->val->filter['pageNumber'] = $requestArr['pageNumber'];
                            }
                            if (isset($requestArr['limits']) && !empty($requestArr['limits'])) {
                                $value->val->filter['limits'] = $requestArr['limits'];
                            }
                            if (isset($requestArr['consultationType']) && !empty($requestArr['consultationType'])) {
                                $value->val->filter['consultationType'] = $requestArr['consultationType'];
                            }
                            if (isset($searchText) && !empty($searchText)) {
                                $value->val->filter['search_action'] = $searchText;
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
