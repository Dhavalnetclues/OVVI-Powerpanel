<?php

namespace Powerpanel\FaqCategory\Controllers;

use App\Http\Controllers\FrontController;
use Powerpanel\Faq\Models\Faq;
use App\Document;
use Request;
use Response;
use DB;
use Powerpanel\CmsPage\Models\CmsPage;
use App\Helpers\FrontPageContent_Shield;
use App\Http\Traits\slug;
use App\Helpers\MyLibrary;
use Powerpanel\FaqCategory\Models\FaqCategory;
use App\Helpers\CustomPagination;

class FaqCategoryController extends FrontController {

    use slug;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * This method loads Faq list view
     * @return  View
     * @since   2018-08-27
     * @author  NetQuick
     */
    public function index($alias = false) 
    {
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
                $categoryData = FaqCategory::getRecordById($alias);
            } else {
                $cataliasid = slug::resolve_alias($alias);
                $categoryData = FaqCategory::getRecordDataByAliasID($cataliasid);
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

        $faqArr = Faq::getFront_List($currentPage, $limit, $catid, $print, $categoryid, $name, $start_date_time, $end_date_time);
        $faqAllCategoriesArr = FaqCategory::getAllCategoriesFrontSidebarList();
        $data['faqArr'] = $faqArr;
        $data['faqAllCategoriesArr'] = $faqAllCategoriesArr;
        $moduelFrontPageUrl = MyLibrary::getFront_Uri('faq')['uri'];
        $moduleFrontWithCatUrl = ($alias != false ) ? $moduelFrontPageUrl . '/' . $alias : $moduelFrontPageUrl;
        $pagginateUrl = $moduleFrontWithCatUrl;

        if ($print == 'print') {
            $data['currentPage'] = 1;
            $data['lastPage'] = 1;
            $data['modulePageUrl'] = $moduelFrontPageUrl;
            $data['moduleFrontWithCatUrl'] = $moduleFrontWithCatUrl;
        } else {
            $data['currentPage'] = (int) $faqArr->currentPage();
            $data['lastPage'] = (int) $faqArr->lastPage();
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
            $data['breadcumbcurrentPageTitle'] = $isCategoryTitle;
        }
        if(isset($categoryData->txtDescription)){
         $data['txtDescription'] = FrontPageContent_Shield::renderBuilder($categoryData->txtDescription);
        }else{
           $data['txtDescription'] = '';  
        }

        if (Request::ajax()) {
            $returnRepsonse = array();
            $returnHtml = view('faq-category::frontview.faqcategorylist', $data)->render();
            $returnRepsonse = array('html' => $returnHtml, 'lastpage' => $data['lastPage'], 'currentpage' => $data['currentPage']);
            echo json_encode($returnRepsonse);
        } else {
            return view('faq-category::frontview.faqcategorylist', $data);
        }
    }

}
