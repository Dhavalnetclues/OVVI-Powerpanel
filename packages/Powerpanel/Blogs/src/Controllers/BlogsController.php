<?php

namespace Powerpanel\Blogs\Controllers;

use App\Helpers\FrontPageContent_Shield;
use App\Helpers\MyLibrary;
use App\Http\Controllers\FrontController;
use App\Http\Traits\slug;
use App\Role_user;
use Powerpanel\BlogCategory\Models\BlogCategory;
use Powerpanel\Blogs\Models\Blogs;
use Powerpanel\CmsPage\Models\CmsPage;
use Powerpanel\RoleManager\Models\Role;
use Request;

class BlogsController extends FrontController
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

    public function index()
    {
        $data = array();
        $pagename = Request::segment(1);

        $aliasId = slug::resolve_alias($pagename);
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
                $data['PageData'] = FrontPageContent_Shield::renderBuilder($pageContent->txtDescription);
            }
            $data['pageContent'] = $pageContent;
        }

        if (isset($pageContent->varTitle) && !empty($pageContent->varTitle)) {
            view()->share('detailPageTitle', $pageContent->varTitle);
        }
        // End CMS PAGE Front Private, Password Prottected Code
        return view('blogs::frontview.blogs', $data);

    }

    public function detail($alias)
    {
        $catid = false;
        $isCategoryList = false;
        $isCategoryTitle = '';
        $isCategoryAlias = '';

        if (is_numeric($alias)) {
            $blogs = Blogs::getRecordById($alias);
        } else {
            $id = slug::resolve_alias($alias);
            $blogs = Blogs::getFrontDetail($id);
        }
        $recordCategoryId = false;
        if (!empty($blogs)) {
            $recordCategoryId = $blogs->intFKCategory;
        }

        if (!empty($blogs)) {
            $metaInfo = array('varMetaTitle' => $blogs->varMetaTitle, 'varMetaKeyword' => $blogs->varMetaKeyword, 'varMetaDescription' => $blogs->varMetaDescription);
            if (isset($blogs->varMetaTitle) && !empty($blogs->varMetaTitle)) {
                view()->share('META_TITLE', $blogs->varMetaTitle);
            }
            if (isset($blogs->varMetaKeyword) && !empty($blogs->varMetaKeyword)) {
                view()->share('META_KEYWORD', $blogs->varMetaKeyword);
            }
            if (isset($blogs->varMetaDescription) && !empty($blogs->varMetaDescription)) {
                view()->share('META_DESCRIPTION', substr(trim($blogs->varMetaDescription), 0, 500));
            }
            if (isset($blogs->fkIntImgId) && !empty($blogs->fkIntImgId)) {
                $imageLink = \App\Helpers\resize_image::resize($blogs->fkIntImgId);
                view()->share('ogImage', $imageLink);
            }
            $breadcrumb = [];
            $data = [];
            $moduelFrontPageUrl = MyLibrary::getFront_Uri('blogs')['uri'];
            $moduleFrontWithCatUrl = ($alias != false) ? $moduelFrontPageUrl : $moduelFrontPageUrl;
            
            $breadcrumb['title'] = (!empty($blogs->varTitle)) ? ucwords($blogs->varTitle) : '';
            $breadcrumb['url'] = $moduelFrontPageUrl;
            $breadcrumb['module'] = 'Blogs';
            $breadcrumb['inner_title'] = '';
            
            $detailPageTitle = $breadcrumb['title'];
            $data['moduleTitle'] = 'Blogs';
            $blogsAllCategoriesArr = BlogCategory::getAllCategoriesFrontSidebarList();
            $data['blogsAllCategoriesArr'] = $blogsAllCategoriesArr;
            $data['modulePageUrl'] = $moduelFrontPageUrl;
            $data['moduleFrontWithCatUrl'] = $moduleFrontWithCatUrl;
            $data['isCategoryList'] = $isCategoryList;
            $data['isCategoryTitle'] = $isCategoryTitle;
            $data['isCategoryAlias'] = $isCategoryAlias;
            $data['blogs'] = $blogs;
            $data['alias'] = $alias;
            $data['metaInfo'] = $metaInfo;
            $data['breadcrumb'] = $breadcrumb;
            $data['detailPageTitle'] = $detailPageTitle;
            $data['txtDescription'] = FrontPageContent_Shield::renderBuilder($blogs->txtDescription);

            return view('blogs::frontview.blogs-detail', $data);
        } else {
            abort(404);
        }
    }

}
