<?php

namespace Powerpanel\NewsCategory\Controllers;

use App\Helpers\FrontPageContent_Shield;
use App\Helpers\MyLibrary;
use App\Http\Controllers\FrontController;
use App\Http\Traits\slug;
use Powerpanel\NewsCategory\Models\NewsCategory;
use Powerpanel\News\Models\News;
use Request;

class NewsCategoryController extends FrontController
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
    public function index($alias = false)
    {
        $args = array();
        $data = array();
        $data['isFromHomeSearch'] = false;

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

        if (Request::get('searchTerm')) {
            $name = Request::get('searchTerm');
            $name1 = "&name=" . $name;
            $args['searchTerm'] = $name;
            $data['isFromHomeSearch'] = 'true';
            $data['searchTerm'] = Request::get('searchTerm');
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
                $categoryData = NewsCategory::getRecordById($alias);
            } else {
                $cataliasid = slug::resolve_alias($alias);
                $categoryData = NewsCategory::getRecordDataByAliasID($cataliasid);
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

        $limit = 15;
        $modulePageUrl = Request::segment(1);
        $print = Request::segment(2);

        $modulePageUrl .= "/" . $alias;
        $currentPage = (null !== (Request::get('page'))) ? Request::get('page') : 1;

        $newsArr = News::getFrontList($currentPage, $limit, $catid, $print, $categoryid, $name, $start_date_time, $end_date_time);

        $newsAllCategoriesArr = NewsCategory::getAllCategoriesFrontSidebarList();
        $data['newsArr'] = $newsArr;
        $data['newsAllCategoriesArr'] = $newsAllCategoriesArr;
        $moduelFrontPageUrl = MyLibrary::getFront_Uri('news')['uri'];
        $moduleFrontWithCatUrl = ($alias != false) ? $moduelFrontPageUrl . '/' . $alias : $moduelFrontPageUrl;
        $pagginateUrl = $moduleFrontWithCatUrl;

        if ($print == 'print') {
            $data['currentPage'] = 1;
            $data['lastPage'] = 1;
            $data['modulePageUrl'] = $moduelFrontPageUrl;
            $data['moduleFrontWithCatUrl'] = $moduleFrontWithCatUrl;
        } else {
            $data['currentPage'] = (int)$newsArr->currentPage();
            $data['lastPage'] = (int)$newsArr->lastPage();
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
        if (isset($isCategoryTitle) && $isCategoryTitle != '') {
            $data['breadcumbcurrentPageTitle'] = $isCategoryTitle;
        }
        $data['categoryid'] = $catid;
        $data['isCategoryAlias'] = $isCategoryAlias;
        if (Request::ajax()) {
            $returnRepsonse = array();
            $returnHtml = view('news-category::frontview.newscategory', $data)->render();
            $returnRepsonse = array('html' => $returnHtml, 'lastpage' => $data['lastPage'], 'currentpage' => $data['currentPage']);

            echo json_encode($returnRepsonse);
        } else {
            return view('news-category::frontview.newscategory', $data);
        }
    }

    /**
     * This method loads News detail view
     * @param   Alias of record
     * @return  View
     * @since   2018-08-27
     * @author  NetQuick
     */
    // public function detail($category, $alias)
    // {
    //     $catid = false;
    //     $cataliasid = slug::resolve_alias($category);
    //     $categoryData = NewsCategory::getRecordDataByAliasID($cataliasid);

    //     $isCategoryList = false;
    //     $isCategoryTitle = '';
    //     $catid = '';
    //     $isCategoryAlias = '';

    //     if (!empty($categoryData)) {
    //         $catid = $categoryData->id;
    //         $isCategoryList = true;
    //         $isCategoryTitle = $categoryData->varTitle;
    //         $isCategoryAlias = $category;
    //     }

    //     if (is_numeric($alias)) {
    //         $news = News::getRecordById($alias);
    //     } else {
    //         $id = slug::resolve_alias($alias);
    //         $news = News::getFrontDetail($id);
    //     }

    //     $recordCategoryId = false;
    //     if (!empty($news)) {
    //         $recordCategoryId = $news->txtCategories;
    //     }

    //     if ((!empty($categoryData) && !empty($news)) && $recordCategoryId != $catid) {
    //         abort(404);
    //     }

    //     if (!empty($news)) {
    //         $metaInfo = array('varMetaTitle' => $news->varMetaTitle, 'varMetaKeyword' => $news->varMetaKeyword, 'varMetaDescription' => $news->varMetaDescription);

    //         if (isset($news->varMetaTitle) && !empty($news->varMetaTitle)) {
    //             view()->share('META_TITLE', $news->varMetaTitle);
    //         }

    //         if (isset($news->varMetaKeyword) && !empty($news->varMetaKeyword)) {
    //             view()->share('META_KEYWORD', $news->varMetaKeyword);
    //         }
    //         if (isset($news->varMetaDescription) && !empty($news->varMetaDescription)) {
    //             view()->share('META_DESCRIPTION', substr(trim($news->varMetaDescription), 0, 500));
    //         }

    //         $breadcrumb = [];
    //         $data = [];

    //         $moduelFrontPageUrl = MyLibrary::getFront_Uri('news-category')['uri'];
    //         $moduleFrontWithCatUrl = ($alias != false) ? $moduelFrontPageUrl . '/' . $isCategoryAlias : $moduelFrontPageUrl;

    //         $breadcrumb['title'] = (!empty($news->varTitle)) ? ucwords($news->varTitle) : '';
    //         $breadcrumb['url'] = MyLibrary::getFront_Uri('news-category')['uri'];
    //         $detailPageTitle = $breadcrumb['title'];
    //         $breadcrumb = $breadcrumb;

    //         $data['detailPageTitle'] = 'News';
    //         $data['moduleTitle'] = 'News';
    //         $newsAllCategoriesArr = NewsCategory::getAllCategoriesFrontSidebarList();
    //         $data['newsAllCategoriesArr'] = $newsAllCategoriesArr;

    //         $data['modulePageUrl'] = $moduelFrontPageUrl;
    //         $data['moduleFrontWithCatUrl'] = $moduleFrontWithCatUrl;

    //         $data['isCategoryList'] = $isCategoryList;
    //         $data['isCategoryTitle'] = $isCategoryTitle;
    //         $data['categoryid1'] = $catid;
    //         $data['isCategoryAlias'] = $isCategoryAlias;

    //         $data['news'] = $news;
    //         $data['alias'] = $alias;
    //         $data['metaInfo'] = $metaInfo;
    //         $data['breadcrumb'] = $breadcrumb;
    //         $data['txtDescription'] = FrontPageContent_Shield::renderBuilder($news->txtDescription);
    //         return view('news-category::frontview.news-detail', $data);
    //     } else {
    //         abort(404);
    //     }
    // }

}
