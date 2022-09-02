<?php

namespace Powerpanel\EventCategory\Controllers;

use App\Http\Controllers\FrontController;
use Powerpanel\Events\Models\Events;
use App\Document;
use Request;
use Response;
use Calendar;
use App\Http\Traits\slug;
use App\Helpers\MyLibrary;
use App\Helpers\FrontPageContent_Shield;
use Powerpanel\EventCategory\Models\EventCategory;
use App\Helpers\CustomPagination;

class EventCategoryController extends FrontController {

    use slug;

   
    public function __construct() {
        parent::__construct();
    }

    
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
                $categoryData = EventCategory::getRecordById($alias);
            } else {
                $cataliasid = slug::resolve_alias($alias);
                $categoryData = EventCategory::getRecordDataByAliasID($cataliasid);
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

        $eventsArr = Events::getFrontList($currentPage, $limit, $catid, $print, $categoryid, $name, $start_date_time, $end_date_time);
        $eventsAllCategoriesArr = EventCategory::getAllCategoriesFrontSidebarList();
        $data['eventsArr'] = $eventsArr;
        $data['eventsAllCategoriesArr'] = $eventsAllCategoriesArr;
        $moduelFrontPageUrl = MyLibrary::getFront_Uri('events')['uri'];
        $moduleFrontWithCatUrl = ($alias != false ) ? $moduelFrontPageUrl . '/' . $alias : $moduelFrontPageUrl;
        $pagginateUrl = $moduleFrontWithCatUrl;

        if ($print == 'print') {
            $data['currentPage'] = 1;
            $data['lastPage'] = 1;
            $data['modulePageUrl'] = $moduelFrontPageUrl;
            $data['moduleFrontWithCatUrl'] = $moduleFrontWithCatUrl;
        } else {
            $data['currentPage'] = (int) $eventsArr->currentPage();
            $data['lastPage'] = (int) $eventsArr->lastPage();
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
            $returnHtml = view('event-category::frontview.eventscategory', $data)->render();
            $returnRepsonse = array('html' => $returnHtml, 'lastpage' => $data['lastPage'], 'currentpage' => $data['currentPage']);
            echo json_encode($returnRepsonse);
        } else {
            return view('event-category::frontview.eventscategory', $data);
        }
    }

    // public function detail($category, $alias) {
    //     $catid = false;
    //     $cataliasid = slug::resolve_alias($category);
    //     $categoryData = EventCategory::getRecordDataByAliasID($cataliasid);
    //     $isCategoryList = false;
    //     $isCategoryTitle = '';
    //     $isCategoryAlias = '';
    //     if (!empty($categoryData)) {
    //         $catid = $categoryData->id;
    //         $isCategoryList = true;
    //         $isCategoryTitle = $categoryData->varTitle;
    //         $isCategoryAlias = $category;
    //     }
    //     if (is_numeric($alias)) {
    //         $events = Events::getRecordById($alias);
    //     } else {
    //         $id = slug::resolve_alias($alias);
    //         $events = Events::getFrontDetail($id);
    //     }
    //     $recordCategoryId = false;
    //     if (!empty($events)) {
    //         $recordCategoryId = $events->intFKCategory;
    //     }
    //     if (!empty($categoryData) && !empty($events) && $recordCategoryId != $catid) {
    //         abort(404);
    //     }
    //     if (!empty($events)) {
    //         $metaInfo = array('varMetaTitle' => $events->varMetaTitle, 'varMetaKeyword' => $events->varMetaKeyword, 'varMetaDescription' => $events->varMetaDescription);
    //         if (isset($events->varMetaTitle) && !empty($events->varMetaTitle)) {
    //             view()->share('META_TITLE', $events->varMetaTitle);
    //         }
    //         if (isset($events->varMetaKeyword) && !empty($events->varMetaKeyword)) {
    //             view()->share('META_KEYWORD', $events->varMetaKeyword);
    //         }
    //         if (isset($events->varMetaDescription) && !empty($events->varMetaDescription)) {
    //             view()->share('META_DESCRIPTION', substr(trim($events->varMetaDescription), 0, 500));
    //         }
    //         $breadcrumb = [];
    //         $data = [];
    //         $moduelFrontPageUrl = MyLibrary::getFront_Uri('events')['uri'];
    //         $moduleFrontWithCatUrl = ($alias != false ) ? $moduelFrontPageUrl . '/' . $isCategoryAlias : $moduelFrontPageUrl;
    //         $breadcrumb['title'] = (!empty($events->varTitle)) ? ucwords($events->varTitle) : '';
    //         $breadcrumb['url'] = MyLibrary::getFront_Uri('events')['uri'];
    //         $detailPageTitle = $breadcrumb['title'];
    //         $breadcrumb = $breadcrumb;
    //         $data['moduleTitle'] = 'Events';
    //         $eventsAllCategoriesArr = EventCategory::getAllCategoriesFrontSidebarList();
    //         $data['eventsAllCategoriesArr'] = $eventsAllCategoriesArr;
    //         $data['modulePageUrl'] = $moduelFrontPageUrl;
    //         $data['moduleFrontWithCatUrl'] = $moduleFrontWithCatUrl;
    //         $data['isCategoryList'] = $isCategoryList;
    //         $data['isCategoryTitle'] = $isCategoryTitle;
    //         $data['isCategoryAlias'] = $isCategoryAlias;
    //         $data['events'] = $events;
    //         $data['alias'] = $alias;
    //         $data['metaInfo'] = $metaInfo;
    //         $data['breadcrumb'] = $breadcrumb;
    //         $data['detailPageTitle'] = 'Events';
    //         $data['txtDescription'] = FrontPageContent_Shield::renderBuilder($events->txtDescription);
    //         return view('event-category::frontview.events-detail', $data);
    //     } else {
    //         abort(404);
    //     }
    // }

}
