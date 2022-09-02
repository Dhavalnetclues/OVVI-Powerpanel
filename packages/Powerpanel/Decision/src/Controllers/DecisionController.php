<?php

namespace Powerpanel\Decision\Controllers;

use App\Helpers\FrontPageContent_Shield;
use App\Helpers\MyLibrary;
use App\Helpers\ParentRecordHierarchy_builder;
use App\Http\Controllers\FrontController;
use App\Http\Traits\slug;
use App\Modules;
use App\Role_user;
use Powerpanel\CmsPage\Models\CmsPage;
use Powerpanel\DecisionCategory\Models\DecisionCategory;
use Powerpanel\Decision\Models\Decision;
use Powerpanel\RoleManager\Models\Role;
use Request;

class DecisionController extends FrontController
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->Mylibrary = new Mylibrary();
    }

    /**
     * This method loads services detail view
     * @param   Alias of record
     * @return  View
     * @since   2018-09-19
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
            $content = FrontPageContent_Shield::renderBuilder($pageContent->txtDescription)['response'];
            $data['pageContent'] = $pageContent;
            $data['isContent'] = (isset($content) && !empty($content)) ? true : false;
        } else {
            if (isset($pageContent->txtDescription) && !empty($pageContent->txtDescription)) {
                $data['PageData'] = FrontPageContent_Shield::renderBuilder($pageContent->txtDescription);
            }
            $data['pageContent'] = $pageContent;
        }

        if (isset($sector_slug)) {
            $sector_slug = $sector_slug;
        } else {

            $sector_slug = 'ofreg';
        }
//        $publicRecordCategory = DecisionCategory::getCategoriesListSectorwise($sector_slug);

        $pageData = Modules::getAllModuleData('decision-category');
        if (isset($pageData->varModuleNameSpace) && $pageData->varModuleNameSpace != '') {
            $MODEL = $pageData->varModuleNameSpace . 'Models\\' . $pageData->varModelName;
        } else {
            $MODEL = '\\App\\' . $pageData->varModelName;
        }
        $categories = ParentRecordHierarchy_builder::Parentrecordhierarchy_frontArr($MODEL, $sector_slug, false);

        $data['decisionCategories'] = $categories;
        if (isset($pageContent->varTitle) && !empty($pageContent->varTitle)) {
            view()->share('detailPageTitle', $pageContent->varTitle);
        }
        // End CMS PAGE Front Private, Password Prottected Code

        return view('decision::frontview.decision', $data);

    }

    public static function singleDropdownCode($dataArray = array(), $type = "data")
    {
        if ($type == "html") {
            return self::singleDropdwonTreeHtml($dataArray);
        } else {
            return self::singleDropdwonTreeData($dataArray);
        }
    }

    public static function singleDropdwonTreeHtml($dataArray = array(), $sub_mark = '')
    {
        $returnOptions = '';
        if (!empty($dataArray)) {
            foreach ($dataArray as $key => $value) {
                $returnOptions .= '<option value="' . $value['id'] . '">' . $sub_mark . $value['varTitle'] . '</option>';
                if (isset($value['children'])) {
                    $returnOptions .= self::singleDropdwonTreeHtml($value['children'], $sub_mark . '--');
                }
            }
        }
        return $returnOptions;
    }

    public static function singleDropdwonTreeData($dataArray = array(), $sub_mark = '', $returnArray = array())
    {

        if (!empty($dataArray)) {
            foreach ($dataArray as $key => $value) {
                $dataNewArray = $value;
                /* $dataNewArray['id'] = $value['id'];
                $dataNewArray['varTitle'] = $value['varTitle']; */
                $dataNewArray['submark'] = $sub_mark;
                $returnArray[] = $dataNewArray;
                if (isset($value['children'])) {
                    $returnArray = self::singleDropdwonTreeData($value['children'], $sub_mark . '|_', $returnArray);
                }
            }
        }
        return $returnArray;
    }

    public function detail($alias)
    {
        $catid = false;
        $isCategoryList = false;
        $isCategoryTitle = '';
        $isCategoryAlias = '';

        if (is_numeric($alias)) {
            $decisions = Decision::getRecordById($alias);

        } else {
            $id = slug::resolve_alias($alias);
            $decisions = Decision::getFrontDetail($id);
        }
        $recordCategoryId = false;
        if (!empty($decisions)) {
            $recordCategoryId = $decisions->intFKCategory;
        }

        if (!empty($decisions)) {
            $metaInfo = array('varMetaTitle' => $decisions->varMetaTitle, 'varMetaKeyword' => $decisions->varMetaKeyword, 'varMetaDescription' => $decisions->varMetaDescription);
            if (isset($decisions->varMetaTitle) && !empty($decisions->varMetaTitle)) {
                view()->share('META_TITLE', $decisions->varMetaTitle);
            }
            if (isset($decisions->varMetaKeyword) && !empty($decisions->varMetaKeyword)) {
                view()->share('META_KEYWORD', $decisions->varMetaKeyword);
            }
            if (isset($decisions->varMetaDescription) && !empty($decisions->varMetaDescription)) {
                view()->share('META_DESCRIPTION', substr(trim($decisions->varMetaDescription), 0, 500));
            }
            if (isset($decisions->fkIntImgId) && !empty($decisions->fkIntImgId)) {
                $imageLink = \App\Helpers\resize_image::resize($decisions->fkIntImgId);
                view()->share('ogImage', $imageLink);
            }
            $breadcrumb = [];
            $data = [];
            $moduelFrontPageUrl = MyLibrary::getFront_Uri('decision')['uri'];
            $moduleFrontWithCatUrl = ($alias != false) ? $moduelFrontPageUrl : $moduelFrontPageUrl;
            $breadcrumb['title'] = (!empty($decisions->varTitle)) ? ucwords($decisions->varTitle) : '';
            $breadcrumb['url'] = MyLibrary::getFront_Uri('decision')['uri'];
            $detailPageTitle = $breadcrumb['title'];
            $breadcrumb = $breadcrumb;
            $data['moduleTitle'] = 'Decisions';

            $decisionAllCategoriesArr = DecisionCategory::getAllCategoriesFrontSidebarList();
            $data['decisionAllCategoriesArr'] = $decisionAllCategoriesArr;
            $data['modulePageUrl'] = $moduelFrontPageUrl;
            $data['moduleFrontWithCatUrl'] = $moduleFrontWithCatUrl;
            $data['isCategoryList'] = $isCategoryList;
            $data['isCategoryTitle'] = $isCategoryTitle;
            $data['isCategoryAlias'] = $isCategoryAlias;
            $data['decisions'] = $decisions;
            $data['alias'] = $alias;
            $data['metaInfo'] = $metaInfo;
            $data['breadcrumb'] = $breadcrumb;
            $data['detailPageTitle'] = 'Publications';
            $data['txtDescription'] = FrontPageContent_Shield::renderBuilder($decisions->txtDescription);

            return view('decision::frontview.decision-detail', $data);
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
                        if ($value->type == 'decision_template') {

                            if (isset($requestArr['pageNumber']) && !empty($requestArr['pageNumber'])) {
                                $value->val->filter['pageNumber'] = $requestArr['pageNumber'];
                            }
                            if (isset($requestArr['year']) && !empty($requestArr['year'])) {
                                $value->val->filter['year'] = $requestArr['year'];
                            }
                             if (isset($requestArr['limits']) && !empty($requestArr['limits'])) {
                                $value->val->filter['limits'] = $requestArr['limits'];
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
