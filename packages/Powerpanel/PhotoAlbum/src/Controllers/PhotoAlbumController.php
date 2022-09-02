<?php

namespace Powerpanel\PhotoAlbum\Controllers;

use App\Http\Controllers\FrontController;
use Powerpanel\PhotoAlbum\Models\PhotoAlbum;
use App\Document;
use Request;
use Response;
use App\Http\Traits\slug;
use Powerpanel\CmsPage\Models\CmsPage;
use App\Helpers\MyLibrary;
use App\Helpers\CustomPagination;
use App\Helpers\FrontPageContent_Shield;
use Powerpanel\PhotoGallery\Models\PhotoGallery;
use App\Role_user;
use Powerpanel\RoleManager\Models\Role;

class PhotoAlbumController extends FrontController {

    use slug;

    public function __construct() {
        parent::__construct();
    }

    public function index($alias = false) {
        
        $data = array();
        $pagename = Request::segment(1);
        
        $aliasId = slug::resolve_alias($pagename);
        if (Request::segment(3) == 'preview') {
            $cmsPageId = Request::segment(2);
            $pageContent = CmsPage::getPageByPageId($cmsPageId, false);
        }else{
            $pageContent = CmsPage::getPageContentByPageAlias($aliasId);
        }

        if (!isset($pageContent->id)) {
            abort('404');
        }

        $CONTENT = ' <h2 class="no_record coming_soon_rcd"> Coming Soon</h2>';
        if (!empty($pageContent->txtDescription)) {
            $CONTENT = $pageContent->txtDescription;
        }

        // Start CMS PAGE Front Private, Password Prottected Code 

        $pageContentcms = CmsPage::getPageContentByPageAlias($aliasId);
        if (isset(auth()->user()->id)) {
            $user_id = auth()->user()->id;
            $role = Role::getRecordById(Role_user::getRecordBYModelId($user_id)->role_id)->name;
        } else {
            $user_id = '';
            $role = '';
        }

        $data['PageData'] = '';
        if (isset($pageContentcms) && $pageContentcms->chrPageActive == 'PR') {
            if ($pageContentcms->UserID == $user_id || $role == 'netquick_admin') {
                if (isset($pageContent->txtDescription) && !empty($pageContent->txtDescription)) {
                    $data['PageData'] = FrontPageContent_Shield::renderBuilder($pageContent->txtDescription);
                }
            } else {
                return redirect(url('/'));
            }
        } else if (isset($pageContentcms) && $pageContentcms->chrPageActive == 'PP') {
            $data['PassPropage'] = 'PP';
            $data['tablename'] = 'cms_page';
            $data['Pageid'] = $pageContent->id;
            $content = FrontPageContent_Shield::renderBuilder($pageContent->txtDescription)['response'];
            $data['isContent'] = (isset($content) && !empty($content)) ? true:false;
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
        return view('photo-album::frontview.photo-album',$data);
    }

    public function detail($alias) {
        $isCategoryList = false;
        $isCategoryTitle = '';
        $isCategoryAlias = '';
        if (is_numeric($alias)) {
            $photoAlbumId = $alias;
            $photoAlbum = PhotoAlbum::getRecordById($alias);
        } else {
            $id = slug::resolve_alias($alias);
            $photoAlbum = PhotoAlbum::getFrontDetail($id);
            $photoAlbumId = $photoAlbum->id;
        }

        $recordCategoryId = false;
        if (!empty($photoAlbum)) {
            $recordCategoryId = $photoAlbum->txtCategories;
        }

        if (!empty($photoAlbum)) {
            $metaInfo = array('varMetaTitle' => $photoAlbum->varMetaTitle, 'varMetaKeyword' => $photoAlbum->varMetaKeyword, 'varMetaDescription' => $photoAlbum->varMetaDescription);

            if (isset($photoAlbum->varMetaTitle) && !empty($photoAlbum->varMetaTitle)) {
                view()->share('META_TITLE', $photoAlbum->varMetaTitle);
            }

            if (isset($photoAlbum->varMetaKeyword) && !empty($photoAlbum->varMetaKeyword)) {
                view()->share('META_KEYWORD', $photoAlbum->varMetaKeyword);
            }
            if (isset($photoAlbum->varMetaDescription) && !empty($photoAlbum->varMetaDescription)) {
                view()->share('META_DESCRIPTION', substr(trim($photoAlbum->varMetaDescription), 0, 500));
            }
            if (isset($photoAlbum->fkIntImgId) && !empty($photoAlbum->fkIntImgId)) {
                $imageLink = \App\Helpers\resize_image::resize($photoAlbum->fkIntImgId);
                view()->share('ogImage', $imageLink);
            }

            $breadcrumb = [];
            $data = [];

            $moduelFrontPageUrl = MyLibrary::getFront_Uri('photo-album')['uri'];
            $moduleFrontWithCatUrl = ($alias != false ) ? $moduelFrontPageUrl . '/' . $isCategoryAlias : $moduelFrontPageUrl;

            $breadcrumb['title'] = (!empty($photoAlbum->varTitle)) ? ucwords($photoAlbum->varTitle) : '';
            $breadcrumb['url'] = MyLibrary::getFront_Uri('photo-album')['uri'];
            $detailPageTitle = $breadcrumb['title'];
            $breadcrumb = $breadcrumb;

            $data['moduleTitle'] = 'Photo Albums';
            $data['currentPageTitle'] = 'Photo Albums';
            $photoAlbumAllCategoriesArr = array(); 
            $data['photoAlbumAllCategoriesArr'] = $photoAlbumAllCategoriesArr;

            $data['modulePageUrl'] = $moduelFrontPageUrl;
            $data['moduleFrontWithCatUrl'] = $moduleFrontWithCatUrl;

            $data['isCategoryList'] = $isCategoryList;
            $data['isCategoryTitle'] = $isCategoryTitle;
            $data['isCategoryAlias'] = $isCategoryAlias;

            $data['photoAlbum'] = $photoAlbum;
            $data['alias'] = $alias;
            $data['metaInfo'] = $metaInfo;
            $data['txtDescription'] = (!empty($photoAlbum->txtDescription)) ? FrontPageContent_Shield::renderBuilder($photoAlbum->txtDescription) : '';
            $data['breadcrumb'] = $breadcrumb;

            $limit = 15;
            $currentPage = (null !== (Request::get('page'))) ? Request::get('page') : 1;

            $photoGalleryArr = PhotoGallery::getFrontListByalbumId($photoAlbumId, $currentPage, $limit);

            $data['photoGalleryArr'] = $photoGalleryArr;

            return view('photo-album::frontview.photo-album-detail', $data);
        } else {
            abort(404);
        }
    }

}
