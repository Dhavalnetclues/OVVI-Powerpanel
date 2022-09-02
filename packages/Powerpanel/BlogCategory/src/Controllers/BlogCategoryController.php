<?php

namespace Powerpanel\BlogCategory\Controllers;

use App\Http\Controllers\FrontController;
use Powerpanel\Blogs\Models\Blogs;
use App\Document;
use Request;
use Response;
use Calendar;
use App\Http\Traits\slug;
use App\Helpers\MyLibrary;
use App\Helpers\FrontPageContent_Shield;
use Powerpanel\BlogCategory\Models\BlogCategory;
use App\Helpers\CustomPagination;

class BlogCategoryController extends FrontController {

    use slug;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
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
                $categoryData = BlogCategory::getRecordById($alias);
            } else {
                $cataliasid = slug::resolve_alias($alias);
                $categoryData = BlogCategory::getRecordDataByAliasID($cataliasid);
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

        $blogsArr = Blogs::getFrontList($currentPage, $limit, $catid, $print, $categoryid, $name, $start_date_time, $end_date_time);
        $blogsAllCategoriesArr = BlogCategory::getAllCategoriesFrontSidebarList();
        $data['blogsArr'] = $blogsArr;
        $data['blogsAllCategoriesArr'] = $blogsAllCategoriesArr;
        $moduelFrontPageUrl = MyLibrary::getFront_Uri('blogs')['uri'];
        $moduleFrontWithCatUrl = ($alias != false ) ? $moduelFrontPageUrl . '/' . $alias : $moduelFrontPageUrl;
        $pagginateUrl = $moduleFrontWithCatUrl;

        if ($print == 'print') {
            $data['currentPage'] = 1;
            $data['lastPage'] = 1;
            $data['modulePageUrl'] = $moduelFrontPageUrl;
            $data['moduleFrontWithCatUrl'] = $moduleFrontWithCatUrl;
        } else {
            $data['currentPage'] = (int) $blogsArr->currentPage();
            $data['lastPage'] = (int) $blogsArr->lastPage();
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
         $data['DataDescription'] = FrontPageContent_Shield::renderBuilder($categoryData->txtDescription);
        }else{
           $data['DataDescription'] = '';  
        }
        if (Request::ajax()) {
            $returnRepsonse = array();
            $returnHtml = view('blogcategory::frontview.blogscategory', $data)->render();
            $returnRepsonse = array('html' => $returnHtml, 'lastpage' => $data['lastPage'], 'currentpage' => $data['currentPage']);
            echo json_encode($returnRepsonse);
        } else {
            return view('blogcategory::frontview.blogscategory', $data);
        }
    }


}
