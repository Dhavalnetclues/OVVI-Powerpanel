<?php

namespace Powerpanel\PublicationsCategory\Controllers;

use App\Http\Controllers\FrontController;
use App\Http\Traits\slug;
use Powerpanel\PublicationsCategory\Models\PublicationsCategory;
use Powerpanel\Publications\Models\Publications;
use Illuminate\Support\Facades\Input;
use Request;
use App\Helpers\DocumentHelper;
use App\Helpers\MyLibrary;
use App\Helpers\CustomPagination;
use App\Helpers\FrontPageContent_Shield;

class PublicationsCategoryController extends FrontController {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
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
    public function index($alias = false) {
        $args = array();
        if (Request::get('catid') != '') {
            $categoryid = Request::get('catid');
            $categoryid1 = "&catid=" . $categoryid;
            $args['catid'] = $categoryid;
        } else {
            $categoryid = '';
            $categoryid1 = '';
        }
        if (Request::get('name') != '') {
            $name = Request::get('name');
            $name1 = "&name=" . $name;
            $args['name'] = $name;
        } else {
            $name = '';
            $name1 = '';
        }
        if (Request::get('start_date_time') != '') {
            $start_date_time = date("Y-m-d", strtotime(Request::get('start_date_time')));
            $start_date_time1 = "&start_date_time=" . $start_date_time;
            $args['start_date_time'] = $start_date_time;
        } else {
            $start_date_time = '';
            $start_date_time1 = '';
        }
        if (Request::get('end_date_time') != '') {
            $end_date_time = date("Y-m-d", strtotime(Request::get('end_date_time')));
            $end_date_time1 = "&end_date_time=" . $end_date_time;
            $args['end_date_time'] = $end_date_time;
        } else {
            $end_date_time = '';
            $end_date_time1 = '';
        }

        $catid = false;
        $isCategoryList = false;
        $isCategoryTitle = '';
        $isCategoryAlias = '';

        if ($alias) {
            if (is_numeric($alias)) {
                $categoryData = PublicationsCategory::getRecordById($alias);
            } else {
                $cataliasid = slug::resolve_alias($alias);
                $categoryData = PublicationsCategory::getRecordDataByAliasID($cataliasid);
            }
            if (empty($categoryData)) {
                abort(404);
            }
            $catid = $categoryData->id;
            $isCategoryList = true;
            $isCategoryTitle = $categoryData->varTitle;
            $isCategoryAlias = $alias;

            $metaInfo = array('varMetaTitle' => $categoryData->varMetaTitle, 'varMetaKeyword' => $categoryData->varMetaKeyword, 'varMetaDescription' => $categoryData->varMetaDescription);

            if (isset($categoryData->varMetaTitle) && !empty($categoryData->varMetaTitle)) {
                view()->share('META_TITLE', $categoryData->varMetaTitle);
            }

            if (isset($categoryData->varMetaKeyword) && !empty($categoryData->varMetaKeyword)) {
                view()->share('META_KEYWORD', $categoryData->varMetaKeyword);
            }
            if (isset($categoryData->varMetaDescription) && !empty($categoryData->varMetaDescription)) {
                view()->share('META_DESCRIPTION', substr(trim($categoryData->varMetaDescription), 0, 500));
            }
        }

        $data = array();
        $limit = 15;
        $modulePageUrl = Request::segment(1);
        $print = Request::segment(2);

        $modulePageUrl .= "/" . $alias;
        $currentPage = (null !== (Request::get('page'))) ? Request::get('page') : 1;
        $publicationsArr = Publications::getFrontList($currentPage, $limit, $catid, $print, $categoryid, $name, $start_date_time, $end_date_time);

        $publicationsAllCategoriesArr = PublicationsCategory::getAllCategoriesFrontSidebarList();
        if (method_exists($this->Mylibrary, 'buildTree')) {
            $treeArray = MyLibrary::buildTree($publicationsAllCategoriesArr->toArray());

            $CategoryTreeOptionsData = self::singleDropdownCode($treeArray);
        }else{
            $treeArray = '';
            $CategoryTreeOptionsData = '';
        }

        $data['publicationsArr'] = $publicationsArr;
        $data['publicationsAllCategoriesArr'] = $treeArray;
        $data['CategoryTreeOptionsData'] = $CategoryTreeOptionsData;
        $moduelFrontPageUrl = MyLibrary::getFront_Uri('publications-category')['uri'];
        $moduleFrontWithCatUrl = ($alias != false ) ? $moduelFrontPageUrl . '/' . $alias : $moduelFrontPageUrl;
        $pagginateUrl = $moduleFrontWithCatUrl;

        if ($print == 'print') {
            $data['currentPage'] = 1;
            $data['lastPage'] = 1;
            $data['modulePageUrl'] = $moduelFrontPageUrl;
            $data['moduleFrontWithCatUrl'] = $moduleFrontWithCatUrl;
        } else {
            $data['currentPage'] = (int) $publicationsArr->currentPage();
            $data['lastPage'] = (int) $publicationsArr->lastPage();
            $data['modulePageUrl'] = $moduelFrontPageUrl;
            $data['moduleFrontWithCatUrl'] = $moduleFrontWithCatUrl;
        }

        $argstring = '';
        if (!empty($args)) {
            $arg1 = http_build_query($args, '', '&amp;');
            $argstring = '?' . $arg1;
        }

        $data['isCategoryList'] = $isCategoryList;
        $data['isCategoryTitle'] = $isCategoryTitle;
        $data['isCategoryAlias'] = $isCategoryAlias;
        $data['categoryid'] = $catid;
        if (isset($isCategoryTitle) && $isCategoryTitle != '') {
//            $data['currentPageTitle'] = $isCategoryTitle;
            $data['breadcumbcurrentPageTitle'] = $isCategoryTitle;
        }

        if (Request::ajax()) {
            $returnRepsonse = array();
            $returnHtml = view('publications-category::frontview.publications', $data)->render();
            $returnRepsonse = array('html' => $returnHtml, 'lastpage' => $data['lastPage'], 'currentpage' => $data['currentPage']);
            if ($data['lastPage'] > $data['currentPage'] || $data['lastPage'] != 1) {
                $returnRepsonse['paginationHtml'] = '<div class="col-md-12 col-sm-12 col-xs-12">
                    <hr class="pagination_border">
                    <div class="pagination_div text-center ">' . $pagginationLinks . '</div>
                </div>';
            } else {
                $returnRepsonse['paginationHtml'] = '';
            }

            echo json_encode($returnRepsonse);
        } else {
            return view('publications-category::frontview.publications', $data);
        }
    }

    public function detail($category, $alias) {

        $catid = false;
        $cataliasid = slug::resolve_alias($category);

        $categoryData = PublicationsCategory::getRecordDataByAliasID($cataliasid);

        $isCategoryList = false;
        $isCategoryTitle = '';
        $isCategoryAlias = '';

        if (!empty($categoryData)) {
            $catid = $categoryData->id;
            $isCategoryList = true;
            $isCategoryTitle = $categoryData->varTitle;
            $isCategoryAlias = $category;
        }

        if (is_numeric($alias)) {
            $publications = Publications::getRecordById($alias);
        } else {
            $id = slug::resolve_alias($alias);
            $publications = Publications::getFrontDetail($id);
        }

        $recordCategoryId = false;
        if (!empty($publications)) {
            $recordCategoryId = $publications->txtCategories;
        }

        if ((!empty($categoryData) && !empty($publications)) && $recordCategoryId != $catid) {
            abort(404);
        }

        if (!empty($publications)) {
            $metaInfo = array('varMetaTitle' => $publications->varMetaTitle, 'varMetaKeyword' => $publications->varMetaKeyword, 'varMetaDescription' => $publications->varMetaDescription);

            if (isset($publications->varMetaTitle) && !empty($publications->varMetaTitle)) {
                view()->share('META_TITLE', $publications->varMetaTitle);
            }

            if (isset($publications->varMetaKeyword) && !empty($publications->varMetaKeyword)) {
                view()->share('META_KEYWORD', $publications->varMetaKeyword);
            }
            if (isset($publications->varMetaDescription) && !empty($publications->varMetaDescription)) {
                view()->share('META_DESCRIPTION', substr(trim($publications->varMetaDescription), 0, 500));
            }
            if (isset($publications->fkIntImgId) && !empty($publications->fkIntImgId)) {
                $imageLink = \App\Helpers\resize_image::resize($publications->fkIntImgId);
                view()->share('ogImage', $imageLink);
            }

            $breadcrumb = [];
            $data = [];

            $moduelFrontPageUrl = MyLibrary::getFront_Uri('publications-category')['uri'];
            $moduleFrontWithCatUrl = ($alias != false ) ? $moduelFrontPageUrl . '/' . $isCategoryAlias : $moduelFrontPageUrl;

            $breadcrumb['title'] = (!empty($publications->varTitle)) ? ucwords($publications->varTitle) : '';
            $breadcrumb['url'] = MyLibrary::getFront_Uri('publications-category')['uri'];
            $detailPageTitle = $breadcrumb['title'];
            $breadcrumb = $breadcrumb;

//            $data['detailPageTitle'] = $detailPageTitle;
            $data['detailPageTitle'] = 'Publications';
            $data['moduleTitle'] = 'Publications';
            //$data['currentPageTitle'] = $publications->varTitle;
            $publicationsAllCategoriesArr = PublicationsCategory::getAllCategoriesFrontSidebarList();
            $data['publicationsAllCategoriesArr'] = $publicationsAllCategoriesArr;

            $data['modulePageUrl'] = $moduelFrontPageUrl;
            $data['moduleFrontWithCatUrl'] = $moduleFrontWithCatUrl;

            $data['isCategoryList'] = $isCategoryList;
            $data['isCategoryTitle'] = $isCategoryTitle;
            $data['isCategoryAlias'] = $isCategoryAlias;

            $data['publications'] = $publications;
            $data['alias'] = $alias;
            $data['metaInfo'] = $metaInfo;
            $data['breadcrumb'] = $breadcrumb;
            $data['txtDescription'] = FrontPageContent_Shield::renderBuilder($publications->txtDescription);
            return view('publications-category::frontview.publications-detail', $data);
        } else {
            abort(404);
        }
    }

    public static function singleDropdownCode($dataArray = array(), $type = "data") {
        if ($type == "html") {
            return self::singleDropdwonTreeHtml($dataArray);
        } else {
            return self::singleDropdwonTreeData($dataArray);
        }
    }

    public static function singleDropdwonTreeHtml($dataArray = array(), $sub_mark = '') {
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

    public static function singleDropdwonTreeData($dataArray = array(), $sub_mark = '', $returnArray = array()) {

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

}
