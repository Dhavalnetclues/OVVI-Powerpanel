<?php

namespace Powerpanel\News\Controllers;

use App\Helpers\FrontPageContent_Shield;
use App\Helpers\MyLibrary;
use App\Http\Controllers\FrontController;
use App\Http\Traits\slug;
use Powerpanel\CmsPage\Models\CmsPage;
use Powerpanel\NewsCategory\Models\NewsCategory;
use Powerpanel\News\Models\News;
// use Request;
use Illuminate\Http\Request;
use App\Role_user;
use Powerpanel\RoleManager\Models\Role;

class NewsController extends FrontController
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
        $sector = false;
        $sector_slug = '';
        $segment1 = $request->segment(1);
        $segment2 = $request->segment(2);
        $letestNewsForSideLeftPanel = News::getLetestRecordOfNews();

        if (($segment1 == "ict" || $segment1 == "water" || $segment1 == "fuel" || $segment1 == "energy") && (!empty($segment2))) {
            $sector = true;
            $sector_slug = $request->segment(1);
        }

        if ($sector) {
            $pagename = $segment2;
        } else {
            $pagename = $segment1;
        }

        $aliasId = slug::resolve_alias($pagename, $sector_slug, 3);

        if ($request->segment(3) == 'preview') {
            $cmsPageId = $request->segment(2);
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
        // dd( $this->PAGE_CONTENT);
        $data['PageData'] = '';
        if (isset($pageContent) && $pageContent->chrPageActive == 'PR') {
            if ($pageContent->UserID == $user_id || $role == 'netquick_admin') {
                if (isset($pageContent->txtDescription) && !empty($pageContent->txtDescription)) {
                    $data['PageData'] = $this->PAGE_CONTENT;
                }
            } else {
                return redirect(url('/'));
            }
        } else if (isset($pageContent) && $pageContent->chrPageActive == 'PP') {
            $data['PassPropage'] = 'PP';
            $data['tablename'] = 'cms_page';
            $data['Pageid'] = $pageContent->id;
            $content = $this->PAGE_CONTENT;
            $data['isContent'] = (isset($content) && !empty($content)) ? true:false;
        } else {
            if (isset($pageContent->txtDescription) && !empty($pageContent->txtDescription)) {
                $data['PageData'] = $this->PAGE_CONTENT;
            }
            $data['pageContent'] = $pageContent;
        }

        $data['letestNews'] = $letestNewsForSideLeftPanel;
        if (isset($pageContent->varTitle) && !empty($pageContent->varTitle)) {
            view()->share('detailPageTitle', $pageContent->varTitle);
        }
        // End CMS PAGE Front Private, Password Prottected Code
        return view('news::frontview.news', $data);

    }

    public function detail($alias)
    {
        $catid = false;
        $isCategoryList = false;
        $isCategoryTitle = '';
        $isCategoryAlias = '';

        if (is_numeric($alias)) {
            $news = News::getRecordById($alias);
        } else {
            $id = slug::resolve_alias($alias);
            $news = News::getFrontDetail($id);
        }
        $latestNews = News::getFrontLatestNews();

        $recordCategoryId = false;
        if (!empty($news)) {
            $recordCategoryId = $news->intFKCategory;
        }

        if (!empty($news)) {
            $metaInfo = array('varMetaTitle' => $news->varMetaTitle, 'varMetaKeyword' => $news->varMetaKeyword, 'varMetaDescription' => $news->varMetaDescription);
            if (isset($news->varMetaTitle) && !empty($news->varMetaTitle)) {
                view()->share('META_TITLE', $news->varMetaTitle);
            }
            if (isset($news->varMetaKeyword) && !empty($news->varMetaKeyword)) {
                view()->share('META_KEYWORD', $news->varMetaKeyword);
            }
            if (isset($news->varMetaDescription) && !empty($news->varMetaDescription)) {
                view()->share('META_DESCRIPTION', substr(trim($news->varMetaDescription), 0, 500));
            }
            if (isset($news->fkIntImgId) && !empty($news->fkIntImgId)) {
                $imageLink = \App\Helpers\resize_image::resize($news->fkIntImgId);
                view()->share('ogImage', $imageLink);
            }
            $breadcrumb = [];
            $data = [];
            $moduelFrontPageUrl = MyLibrary::getFront_Uri('news')['uri'];
            $moduleFrontWithCatUrl = ($alias != false) ? $moduelFrontPageUrl : $moduelFrontPageUrl;

            $breadcrumb['title'] = (!empty($news->varTitle)) ? ucwords($news->varTitle) : '';
            $breadcrumb['url'] = $moduleFrontWithCatUrl;
            $breadcrumb['module'] = 'News';
            $breadcrumb['inner_title'] = '';

            $detailPageTitle = $breadcrumb['title'];
            $data['moduleTitle'] = 'News';
            $newsAllCategoriesArr = NewsCategory::getAllCategoriesFrontSidebarList();
            $data['newsAllCategoriesArr'] = $newsAllCategoriesArr;
            $data['modulePageUrl'] = $moduelFrontPageUrl;
            $data['moduleFrontWithCatUrl'] = $moduleFrontWithCatUrl;
            $data['isCategoryList'] = $isCategoryList;
            $data['isCategoryTitle'] = $isCategoryTitle;
            $data['isCategoryAlias'] = $isCategoryAlias;
            $data['news'] = $news;
            $data['tags'] = (isset($news->varTags) && !empty($news->varTags)) ? explode(",", $news->varTags) : '';
            $data['alias'] = $alias;
            $data['metaInfo'] = $metaInfo;
            $data['breadcrumb'] = $breadcrumb;
            $data['detailPageTitle'] = $detailPageTitle;
            $data['latestNews'] = $latestNews;
            $data['txtDescription'] = FrontPageContent_Shield::renderBuilder($news->txtDescription)['response'];
            return view('news::frontview.news-detail', $data);
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

            // $pageContentcms = CmsPage::getPageContentByPageAlias($aliasId);
            if (isset($pageContent->txtDescription) && !empty($pageContent->txtDescription)) {
                $txtDesc = json_decode($pageContent->txtDescription);

                foreach ($txtDesc as $key => $value) {
                    if ($value->type == 'news_template') {
                        if (isset($requestArr['category']) && !empty($requestArr['category'])) {
                            $value->val->filter['category'] = $requestArr['category'];
                        }
                        if (isset($requestArr['year']) && !empty($requestArr['year'])) {
                            $value->val->filter['year'] = $requestArr['year'];
                        }
                         if (isset($requestArr['limits']) && !empty($requestArr['limits'])) {
                                $value->val->filter['limits'] = $requestArr['limits'];
                            }
                        if (isset($requestArr['pageNumber']) && !empty($requestArr['pageNumber'])) {
                            $value->val->filter['pageNumber'] = $requestArr['pageNumber'];
                        }
                        if (isset($requestArr['sortVal']) && !empty($requestArr['sortVal'])) {
                            $value->val->filter['sortVal'] = $requestArr['sortVal'];
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
