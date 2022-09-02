<?php

namespace Powerpanel\Links\Controllers;

use App\Http\Controllers\FrontController;
use Powerpanel\LinksCategory\Models\LinksCategory;
use Powerpanel\Links\Models\Links;
use Illuminate\Support\Facades\Input;
use App\Helpers\MyLibrary;
use Powerpanel\CmsPage\Models\CmsPage;
use DB;
use App\Http\Traits\slug;
use App\Helpers\FrontPageContent_Shield;
use Request;
use App\Role_user;
use Powerpanel\RoleManager\Models\Role;

class LinksController extends FrontController {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * This method loads UsefulLinks list view
     * @return  View
     * @since   2018-08-27
     * @author  NetQuick
     */
    
    // public function index() {
    //     $data = array();
    //     $pagename = Request::segment(1);
    //     if (is_numeric($pagename) && (int) $pagename > 0) {
    //         $aliasId = $pagename;
    //     } else {
    //         $aliasId = slug::resolve_alias($pagename);
    //     }

    //     if (null !== Request::segment(2) && Request::segment(2) != 'preview') {
    //         if (is_numeric(Request::segment(2))) {
    //             $cmsPageId = Request::segment(2);
    //             $pageContent = CmsPage::getPageByPageId($cmsPageId, false);
    //         } elseif (Request::segment(2) == 'print') {
    //             $pageContent = CmsPage::getPageContentByPageAlias($aliasId);
    //         }
    //     } elseif (is_numeric($aliasId)) {
    //         $pageContent = CmsPage::getPageContentByPageAlias($aliasId);
    //         if (!isset($pageContent->id)) {
    //             $pageContent = CmsPage::getPageByPageId($aliasId, false);
    //         }
    //     }
    //     if (!isset($pageContent->id)) {
    //         abort('404');
    //     }

    //     $CONTENT = ' <h2 class="no_record coming_soon_rcd"> Coming Soon</h2>';
    //     if (!empty($pageContent->txtDescription)) {
    //         $CONTENT = $pageContent->txtDescription;
    //     }

    //     // Start CMS PAGE Front Private, Password Prottected Code 

    //     $pageContentcms = CmsPage::getPageContentByPageAlias($aliasId);
    //     if (isset(auth()->user()->id)) {
    //         $user_id = auth()->user()->id;
    //     } else {
    //         $user_id = '';
    //     }
    //     if (isset($pageContentcms) && $pageContentcms->chrPageActive == 'PR') {
    //         if ($pageContentcms->UserID == $user_id) {
    //             if (isset($pageContent) && $pageContent != '') {
    //                 $data['PageData'] = FrontPageContent_Shield::renderBuilder($pageContent->txtDescription);
    //             }
    //         } else {
    //             return redirect(url('/'));
    //         }
    //     } else if (isset($pageContentcms) && $pageContentcms->chrPageActive == 'PP') {
    //         $data['PassPropage'] = 'PP';
    //         $data['Pageid'] = $pageContentcms->id;
    //     } else {
    //         if (isset($pageContent) && $pageContent != '') {
    //             $data['PageData'] = FrontPageContent_Shield::renderBuilder($pageContent->txtDescription);
    //         } else {
    //             $data['PageData'] = '';
    //         }
    //         $data['pageContent'] = $pageContent;
    //     }
        
    //     echo '<pre>';print_r($data['PageData']);die;

    //     if (isset($pageContent->varTitle) && !empty($pageContent->varTitle)) {
    //         view()->share('detailPageTitle', $pageContent->varTitle);
    //     }
    //     // End CMS PAGE Front Private, Password Prottected Code 
    //     return view('links::frontview.links', $data);
    // }

    public function index() {

        
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
       return view('links::frontview.linkslist',$data);
       
   }

}
