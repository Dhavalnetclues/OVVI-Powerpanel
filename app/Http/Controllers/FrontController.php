<?php

/**
 * The FrontController class handels Preloaded data for front side
 * configuration  process (ORM code Updates).
 * @package   Netquick powerpanel
 * @license   http://www.opensource.org/licenses/BSD-3-Clause
 * @version   1.1
 * @since     2017-08-09
 * @author    NetQuick
 */

namespace App\Http\Controllers;

use App\Alias;
use App\Helpers\Document_hits;
use App\Helpers\Email_sender;
use App\Helpers\FrontPageContent_Shield;
use App\Helpers\MyLibrary;
use App\Helpers\Page_hits;
use App\Helpers\resize_image;
use App\Helpers\time_zone;
use App\Http\Controllers\Controller;
use App\Http\Traits\slug;
use App\LiveUsers;
use App\LoginLog;
use App\Modules;
use App\User;
use Config;
use DB;
use File;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;
use Jenssegers\Agent\Agent;
use Powerpanel\Banner\Models\Banner;
use Powerpanel\CmsPage\Models\CmsPage;
use Powerpanel\Menu\Models\Menu;
use Powerpanel\PopupContent\Models\PopUpContent;
use Validator;
use Cookie;


class FrontController extends Controller
{

    use slug;

    protected $breadcrumb = [];
    protected $sitemap_content;
    public $PAGE_CONTENT;
    private $ip = '';

    public function __construct()
    {

        view()->share('CDN_PATH', Config::get('Constant.CDN_PATH'));
        $device = Config::get('Constant.DEVICE');
        $isMobile = false;
        if ($device == 'mobile') {
            $isMobile = true;
        }
        if (!Request::ajax()) {

            time_zone::time_zone();

            Self::loadAllMenus();
              $this->getPopupContent();
            if (Request::segment(1) != 'download' && Request::segment(1) != 'viewPDF') {
                $this->shareData();
           
            }
        }
    }

    public static function loadAllMenus()
    {

        $allMenu = Menu::getAllMenuItems();
        $pageType = Request::segment(1);

        Self::loadHeaderMenu($allMenu);
        Self::loadSectorMenu($allMenu,$pageType);
        Self::getNavigationMenu($allMenu);
        Self::loadLeftPanelMenus($allMenu);
        Self::loadQuickLinksMenu($allMenu);

        $footerMenu = '';
        if (isset($allMenu[2]) && !empty($allMenu[2])) {
            $footerMenu = Self::getFooterMenuByTypeID($allMenu[2]);
        }
        view()->share('footerMenu', $footerMenu);
    }

    public static function loadHeaderMenu($menuItems)
    {

        $html = '';
        if (empty($menuItems[1][0]['menu_type'])) {
            $html = '';
        } else {
            if (isset($menuItems[1]) && count($menuItems[1]) > 0) {

                $menuArr = Self::buildTree($menuItems[1]);
                $html .= '<ul class="brand-nav brand-navbar" id="headerMenu">';
                foreach ($menuArr as $key => $row) {

                    $activeclass = '';
                    $currenturl = Request::segment(1) . '/' . Request::segment(2);
                    $fullUrl = URL::current();
                    if($fullUrl == url($row['txtPageUrl'])) {
                        $activeclass = "active";
                    }else if (Request::segment(1) == $row['txtPageUrl']) {
                        $activeclass = "active";
                    } else if ($currenturl == $row['txtPageUrl']) {
                        $activeclass = "active";
                    } else {
                        $activeclass = '';
                    }
                    

                    if ($row['txtPageUrl'] == 'javascript:;') {
                        $menuurl = 'javascript:;';
                    } else {
                        $menuurl = url($row['txtPageUrl']);
                    }

                    $html .= '<li class="first ' . $activeclass . '"><a href="' . $menuurl . '" title="' . ucfirst($row['varTitle']) . '">' . ucfirst($row['varTitle']) . '</a>';
                    $html .= Self::getHeaderChildMenuItems($row);
                    $html .= '</li>';
                }
                $html .= '</ul>';
            }
        }
        view()->share('HeadreMenuhtml', $html);
        return $html;
    }

    public static function loadSectorMenu($menuItems,$pageType = null)
    {

        $sectorArr = ['energy','fuel','ict','water','spectrum'];
        if($pageType == "" || !in_array($pageType,$sectorArr)) {
            $menuId = "17";
        } else if($pageType == "energy") {
            $menuId = "18";
        } else if($pageType == "water") {
            $menuId = "19";
        } else if($pageType == "fuel") {
            $menuId = "20";
        } else if($pageType == "ict") {
            $menuId = "21";
        } else if($pageType == "spectrum") {
            $menuId = "22";
        } else {
            $menuId ='';
        }

        $menuArr = '';
        $html = '';
        if (empty($menuItems[$menuId][0]['menu_type'])) {
            $html = '';
        } else {
            if (isset($menuItems[$menuId]) && count($menuItems[$menuId]) > 0) {
                if(Request::segment(1) == ''){  //  Home Page
                    $menuArr = Self::buildTree($menuItems[$menuId]);
                    $html .= '<ul class="nav nav-tabs" id="myTab" role="tablist">';
                    $i = 0;
                    foreach ($menuArr as $key => $row) {

                        $active = $i == 0 ? 'true' : 'false';
                        $activeClass = $i == 0 ? 'active' : '';

                        $varTitle = ucfirst(str_replace(' ','_',$row['varTitle']));
                        $url = url($row['txtPageUrl']);

                        if($row['varTitle'] == 'ALL AREAS'){
                            $href = "";
                        }else{
                            $href = "#$varTitle".'_tab';
                        }

                        if (in_array(Request::segment(1),$sectorArr) || $row['varTitle'] == 'ALL AREAS') {
                            $html .= '<li class="nav-item"><a    href="'.$url.'"    id="'.$varTitle.'-tab"   aria-selected="'.$active.'"    role="tab"    class="'.$activeClass.'"    aria-controls="'.$varTitle.'"    title="'.ucfirst($row['varTitle']).'">' . ucfirst($row['varTitle']) . '</a>';
                        } else {
                            $html .= '<li class="nav-item"><a    href="'.$href.'"    id="'.$varTitle.'-tab"    data-toggle="tab"    aria-selected="'.$active.'"    role="tab"    class="'.$activeClass.'"    aria-controls="'.$varTitle.'"    title="'.ucfirst($row['varTitle']).'">' . ucfirst($row['varTitle']) . '</a>';
                        }

                        $html .= '</li>';
                        $i++;
                    }
                    $html .= '</ul>';
                }else{  //  Other Page
                    $menuArr = Self::buildTree($menuItems[$menuId]);
                    $html .= '<ul class="nav nav-tabs" id="myTab" role="tablist">';
                    $i = 0;
                    foreach ($menuArr as $key => $row) {

                        $active = $i == 0 ? 'true' : 'false';
                        $activeClass = $i == 0 ? 'active' : '';

                        $varTitle = ucfirst(str_replace(' ','_',$row['varTitle']));
                        $url = url($row['txtPageUrl']);

                        if($row['varTitle'] == 'ALL AREAS'){
                            $href = "";
                        }else{
                            $href = "#$varTitle".'_tab';
                        }

                        if (in_array(Request::segment(1),$sectorArr) || $row['varTitle'] == 'ALL AREAS') {
                            $html .= '<li class="nav-item"><a    href="'.$url.'"    id="'.$varTitle.'-tab"   aria-selected="'.$active.'"    role="tab"    class="'.$activeClass.'"    aria-controls="'.$varTitle.'"    title="'.ucfirst($row['varTitle']).'">' . ucfirst($row['varTitle']) . '</a>';
                        } else {
                            $html .= '<li class="nav-item"><a    href="'.$href.'"    id="'.$varTitle.'-tab"    data-toggle="tab"    aria-selected="'.$active.'"    role="tab"    class="'.$activeClass.'"    aria-controls="'.$varTitle.'"    title="'.ucfirst($row['varTitle']).'">' . ucfirst($row['varTitle']) . '</a>';
                        }

                        $html .= '</li>';
                        $i++;
                    }
                    $html .= '</ul>';
                }
            }
        }

        $data = array(
            'SectorMenuhtml' => $html,
            'childArr' => $menuArr,
        );
        view()->share('data', $data);
        return $html;
    }

    public static function getHeaderChildMenuItems($menuObj)
    {

        $html = '';
        if (isset($menuObj['children']) && !empty($menuObj['children'])) {
            /* $html .= '<span class="is-open"></span>'; */
            $html .= '<ul class="sub-menu">';
            foreach ($menuObj['children'] as $key => $nav) {

                $activeclass = '';
                $currenturl = Request::segment(1) . '/' . Request::segment(2);
                $fullUrl = URL::current();
                if($fullUrl == url($nav['txtPageUrl'])) {
                    $activeclass = "active";
                }else if (Request::segment(1) == $nav['txtPageUrl']) {
                    $activeclass = "active";
                } else if ($currenturl == $nav['txtPageUrl']) {
                    $activeclass = "active";
                } else {
                    $activeclass = '';
                }

                if ($nav['txtPageUrl'] == 'javascript:;') {
                    $menuurl = 'javascript:;';
                } else {
                    $menuurl = url($nav['txtPageUrl']);
                }

                $html .= '<li class="first ' . $activeclass . '">';
                $html .= '<a href="' . $menuurl . '" title="' . ucfirst($nav['varTitle']) . '">' . ucfirst($nav['varTitle']) . '</a>';
                if (isset($nav['children']) && !empty($nav['children'])) {
                    $html .= Self::getHeaderChildMenuItems($nav);
                }
                $html .= '</li>';
            }
            $html .= '</ul>';
        }
        return $html;
    }

    public static function loadQuickLinksMenu($menuItems)
    {
        $html = '';
        if (empty($menuItems[3][0]['menu_type'])) {
            $html = '';
        } else {
            if (isset($menuItems[3]) && count($menuItems[3]) > 0) {

                $menuArr = Self::buildTree($menuItems[3]);
                $html .= '<ul class="quick-link" id="quickLinksMenu">';
                foreach ($menuArr as $key => $row) {
                    $activeclass = '';
                    $currenturl = Request::segment(1) . '/' . Request::segment(2);
                    $fullUrl = URL::current();
                    if($fullUrl == url($row['txtPageUrl'])) {
                        $activeclass = "active";
                    }else if (Request::segment(1) == $row['txtPageUrl']) {
                        $activeclass = "active";
                    } else if ($currenturl == $row['txtPageUrl']) {
                        $activeclass = "active";
                    } else {
                        $activeclass = '';
                    }

                    if ($row['txtPageUrl'] == 'javascript:;') {
                        $menuurl = 'javascript:;';
                    } else {
                        $menuurl = url($row['txtPageUrl']);
                    }

                    $html .= '<li class="' . $activeclass . '"><a href="' . $menuurl . '" title="' . ucfirst($row['varTitle']) . '">' . ucfirst($row['varTitle']) . '</a>';
                    $html .= Self::getHeaderChildMenuItems($row);
                    $html .= '</li>';
                }
                $html .= '</ul>';
            }
        }
        view()->share('QuickLinksMenu', $html);
        return $html;
    }

    public static function buildTree(array $elements, $parentId = 0)
    {

        $branch = array();
        foreach ($elements as $element) {
            if ($element['intParentMenuId'] == $parentId) {
                $children = Self::buildTree($elements, $element['id']);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[] = $element;
            }
        }
        return $branch;
    }

    public static function getNavigationMenu($menuItems)
    {

        $html = '';
        if (empty($menuItems[3][0]['menu_type'])) {
            $html = '';
        } else {

            if (isset($menuItems[3]) && count($menuItems[3]) > 0) {

                $menuArr = Self::buildTree($menuItems[3]);
                $html .= '<ul id="accordionMenu" class="brand-nav brand-navbar navigationMenu">';
                foreach ($menuArr as $navmenu) {

                    $activeclass = '';

                    $segment2 = Request::segment(2);
                    $currenturl = Request::segment(1).(!empty($segment2)?'/'.$segment2:'');
                    $fullUrl = URL::current();
                    if($fullUrl == url($navmenu['txtPageUrl'])) { 
                        $activeclass = "active";
                    }else if (Request::segment(1) == $navmenu['txtPageUrl']) {
                        $activeclass = "active";
                    } else if ($currenturl == $navmenu['txtPageUrl']) {
                        $activeclass = "active";
                    }

                    if ($navmenu['intParentMenuId'] == 0) {

                        if (isset($navmenu['children']) && !empty($navmenu['children'])) {
                            $html .= '<li class="sub-menu1 '.$activeclass.'">';
                        } else {
                            $html .= '<li class="'.$activeclass.'">';
                        }
                        $menuURL = url($navmenu['txtPageUrl']);
                        $currentURL = URL::current();

                        $class = '';
                        if ($menuURL == $currentURL) {
                            $class = 'active';
                        }
                       
                        $html .= '<a href="' . $menuURL . '" title="' . ucfirst($navmenu['varTitle']) . '" data-content="' . ucfirst($navmenu['varTitle']) . '">' . ucfirst($navmenu['varTitle']) . '</a> <span class="collapsed" data-toggle="collapse" data-target="#' . ucfirst($navmenu['varTitle']) . '" aria-expanded="false" aria-controls="' . ucfirst($navmenu['varTitle']) . '"></span>';
                        $html .= Self::getNavigationChildMenu($navmenu);
                        $html .= '</li>';
                    }
                }
            
                $html .= '</ul>';
            }
        }
        view()->share('navigationMenu', $html);
    }

    public static function getNavigationChildMenu($navmenu)
    {

        $html = '';
        if (isset($navmenu['children']) && !empty($navmenu['children'])) {
            $html .= '<ul id="' . ucfirst($navmenu['varTitle']) . '" class="sub-menu collapse" data-parent="#accordionMenu">';
                  
            foreach ($navmenu['children'] as $nav) {

                $activeclass = '';
                $segment2 = Request::segment(2);
                $currenturl = Request::segment(1).(!empty($segment2)?'/'.$segment2:'');
                $fullUrl = Request::fullUrl();

                if($nav['txtPageUrl'] == '/consultations'){
                    $menuURL = url($nav['txtPageUrl']) .'?sector='.strtolower($navmenu['varTitle']);
                }else{
                    $menuURL = url($nav['txtPageUrl']);   
                }

                if($fullUrl == $menuURL) { 
                    $activeclass = "active";
                }else if (Request::segment(1) == $nav['txtPageUrl']) {
                    $activeclass = "active";
                } else if ($currenturl == $nav['txtPageUrl']) {
                    $activeclass = "active";
                }

                $html .= '<li class="'.$activeclass.'">';
                    $html .= '<a href="' . $menuURL .'" title="' . ucfirst($nav['varTitle']) . '">' . ucfirst($nav['varTitle']) . '</a>';
                    if (isset($navmenu['children']) && !empty($navmenu['children'])) {
                        $html .= Self::getNavigationChildMenu($nav);
                    }
                    $html .= '</li>';
                }

            $html .= '</ul>';
        }
        return $html;
    }

    public static function loadLeftPanelMenus($allMenu)
    {

       
        $consumerMenu = '';
        if (isset($allMenu[7]) && !empty($allMenu[7])) {
            $consumerMenu = Self::getMenuByTypeID($allMenu[7]);
        }
        view()->share('consumerMenu', $consumerMenu);
        
         $aboutUsMenu = '';
        if (isset($allMenu[5]) && !empty($allMenu[5])) {
            $aboutUsMenu = Self::getMenuByTypeID($allMenu[5]);
        }
        view()->share('aboutUsMenu', $aboutUsMenu);

        
        $energyMenu = '';
        if (isset($allMenu[8]) && !empty($allMenu[8])) {
            $energyMenu = Self::getMenuByTypeID($allMenu[8]);
        }
        view()->share('energyMenu', $energyMenu);

        $fuelMenu = '';
        if (isset($allMenu[12]) && !empty($allMenu[12])) {
            $fuelMenu = Self::getMenuByTypeID($allMenu[12]);
        }
        view()->share('fuelMenu', $fuelMenu);

        $ictMenu = '';
        if (isset($allMenu[13]) && !empty($allMenu[13])) {
            $ictMenu = Self::getMenuByTypeID($allMenu[13]);
        }
        view()->share('ictMenu', $ictMenu);

        $waterMenu = '';
        if (isset($allMenu[14]) && !empty($allMenu[14])) {
            $waterMenu = Self::getMenuByTypeID($allMenu[14]);
        }
        view()->share('waterMenu', $waterMenu);
    }

    public static function getMenuByTypeID($menuItems)
    {
           
        $html = '';
        if (count($menuItems) > 0) {
            $menuArr = Self::buildTree($menuItems);
            $html .= '<ul class="nqul d-flex flex-wrap n-fs-16 n-ff-2 n-fw-600 n-fc-black-500" id="accordianMenu">';
            foreach ($menuArr as $navmenu) {
                if (empty($navmenu['menu_type'])) {

                } else {
                    if ($navmenu['intParentMenuId'] == 0) {
                        $html .= '<li>';

                        $menuURL = url($navmenu['txtPageUrl']);
                        $currentURL = URL::current();

                        $class = '';
                        if ($menuURL == $currentURL) {
                            $class = 'active';
                        }
                       
                    if($navmenu['txtPageUrl'] == '/complaints' && Request::segment(1) == 'on-line-complaint-form' ){
                      $class = 'active';
                        
                    }

                        $html .= '<a class="' . $class . '"  href="' . $menuURL . '" title="' . ucfirst($navmenu['varTitle']) . '" data-content="' . ucfirst($navmenu['varTitle']) . '">' . ucfirst($navmenu['varTitle']) . '</a>';
                        $html .= Self::getChildMenuItem($navmenu);
                        $html .= '</li>';
                    }
                }
            }
            $html .= '</ul>';
        }
        return $html;
    }

    public static function getChildMenuItem($navmenu)
    {
       
        $html = '';
        if (isset($navmenu['children']) && !empty($navmenu['children'])) {

            $html .= '<span class="collapsed" data-toggle="collapse" data-target="#' . str_slug($navmenu['varTitle']) . '" aria-controls="' . str_slug($navmenu['varTitle']) . '"></span>';
            if ($navmenu['intParentMenuId'] == 0) {
                $dataParent = '#accordianMenu';
            } else {
                $dataParent = '#' . str_slug($navmenu['varTitle']);
            }

            $html .= '<ul id="' . str_slug($navmenu['varTitle']) . '" class="collapse" data-parent="' . $dataParent . '">';
            foreach ($navmenu['children'] as $nav) {
                $html .= '<li>';

                $menuURL = url($nav['txtPageUrl']);
                $currentURL = URL::current();

                $class = '';
                if ($menuURL == $currentURL) {
                    $class = 'active';
                }

                $html .= '<a class="' . $class . '" href="' . url($nav['txtPageUrl']) . '" title="' . ucfirst($nav['varTitle']) . '">' . ucfirst($nav['varTitle']) . '</a>';
                if (isset($nav['children']) && !empty($nav['children'])) {
                    $html .= Self::getChildMenuItem($nav);
                }
                $html .= '</li>';
            }
            $html .= '</ul>';
        }
        return $html;
    }

    public static function getFooterMenuByTypeID($menuItems)
    {
        $html = '';
        
            //$menuObj = Menu::getMenuByTypeId($typeID);
            if (count($menuItems) > 0) {
                $html .= '<ul class="nqul default-nav">';
                foreach ($menuItems as $navmenu) {

                    if ($navmenu['intParentMenuId'] == 0) {
                        $html .= '<li>';

                        $menuURL = url($navmenu['txtPageUrl']);
                        $currentURL = URL::current();

                        $class = '';
                        if ($menuURL == $currentURL) {
                            $class = 'active';
                        }
                        if(!empty($navmenu['menu_type'])){
                        $html .= '<a class="' . $class . '"  href="' . $menuURL . '" title="' . ucfirst($navmenu['varTitle']) . '" data-content="' . ucfirst($navmenu['varTitle']) . '">' . ucfirst($navmenu['varTitle']) . '</a>';
                        }
                        $html .= '</li>';
                    }
                }
                $html .= '</ul>';
            }
        
        return $html;
    }

    public function getPopupContent() {

        if (Request::segment(1) != 'thankyou' && Request::segment(1) != 'viewPDF') {
            $sector = false;
            $sector_slug = '';
            $AliasId = '';
            $pageid = '';
            $segment1 = Request::segment(1);
            $segment2 = Request::segment(2);

            if ($segment1 != '' && $segment2 == '') {
                $AliasId = slug::resolve_alias_for_routes(Request::segment(1));
            } elseif (($segment1 == "ict" || $segment1 == "water" || $segment1 == "fuel" || $segment1 == "energy") && (!empty($segment2))) {
                $sector = true;
                $sector_slug = Request::segment(1);
                if ($sector) {
                    $pagename = $segment2;
                } else {
                    $pagename = $segment1;
                }
                $AliasId = slug::resolve_alias_for_routes($pagename, $sector_slug);
            } elseif (($segment1 != "ict" || $segment1 != "water" || $segment1 != "fuel" || $segment1 != "energy") && (!empty($segment2))) {
                $objResult = Alias::getAlias($segment2);
                if (!empty($objResult)) {
                    $AliasId = $objResult->id;
                }
            } elseif (empty($segment1)) {
                $segment1 = 'home';
                $AliasId = slug::resolve_alias_for_routes($segment1);
            }



            $aliasrecord = Alias::getAliasbyID($AliasId);

            // we will get fkmodulecode here
            if (isset($aliasrecord) && !empty($aliasrecord)) {
                $module = Modules::getModuleById($aliasrecord->intFkModuleCode);
                if (isset($module->id) && !empty($module->id)) {
                    if ($module->varModuleNameSpace != '') {
                        $model = $module->varModuleNameSpace . 'Models\\' . $module->varModelName;
                    }
                    $modelrecord = $model::getFrontListPopup();
                    foreach ($modelrecord as $mrecord) {
                        if ($mrecord->intAliasId == $AliasId) {
                            $pageid = $mrecord->id;
                        }
                    }
                }
            }
            if (isset($pageid) && !empty($pageid)) {
                $popupObj = PopUpContent::checkPopupContent($pageid, $aliasrecord->intFkModuleCode);
                if (!empty($popupObj) && isset($popupObj)) {
                    view()->share('popupObj', $popupObj);
                } else {
                    $checkall = PopUpContent::getPopupContent();
                    $popupObj = array();
                    foreach ($checkall as $checkrec) {
                        if ($checkrec->chrDisplay == 'Y') {
                            $popupObj = $checkrec;
                        }
                    }
                    view()->share('popupObj', $popupObj);
                }
            }
        }
    }

    public function setInnerBanner($pageObj = false)
    {
        $innerBannerArr = [];
        $innerBannerArr['currentPageTitle'] = (isset($pageObj->varTitle) ? $pageObj->varTitle : Request::segment(1));
        $defaultBanner = Banner::getDefaultBannerList();
        $innerBanner = $defaultBanner;
        if (isset($pageObj->id)) {

            $segment1 = Request::segment(1);
            $segment2 = Request::segment(2);

            if (($segment1 == "ict" || $segment1 == "water" || $segment1 == "fuel" || $segment1 == "energy") && (!empty($segment1) && !empty($segment2))) {
                if (null !== Request::segment(2)) {
                    $AliasId = slug::resolve_alias_for_routes(Request::segment(2), $segment1);
                    $moduleID = Alias::getModuleByAliasId($AliasId);

                    if (isset($pageObj->id)) {
                        $innerBanner = Banner::getInnerBannerListingPage($pageObj->id, $moduleID->intFkModuleCode);
                        if (count($innerBanner) < 1) {
                            $innerBanner = $defaultBanner;
                        }
                    }

                    $CmsPageId = CmsPage::getPageWithModuleId();
                    if (!empty($CmsPageId)) {
                        $innerBanner = Banner::getInnerBannerList($pageObj->id, $CmsPageId->intFKModuleCode);
                        if (count($innerBanner) < 1) {
                            $innerBanner = $defaultBanner;
                        }
                    }
                }
            } else {

                if (null !== $segment1 && null == Request::segment(2)) {

                    $AliasId = slug::resolve_alias_for_routes(Request::segment(1));
                    $moduleID = Alias::getModuleByAliasId($AliasId);

                    if (isset($pageObj->id)) {
                        $innerBanner = Banner::getInnerBannerListingPage($pageObj->id, $moduleID->intFkModuleCode);

                        if (count($innerBanner) < 1) {
                            $innerBanner = $defaultBanner;
                        }
                    }

                    $CmsPageId = CmsPage::getPageWithModuleId();
                    if (!empty($CmsPageId)) {
                        $innerBanner = Banner::getInnerBannerList($pageObj->id, $CmsPageId->intFKModuleCode);
                        if (count($innerBanner) < 1) {
                            $innerBanner = $defaultBanner;
                        }
                    }
                } elseif ($segment1 !== null && $segment2 !== null && Request::segment(3) !== 'preview' && $segment2 != 'preview') {

                    $AliasId = slug::resolve_alias(Request::segment(2));
                    $moduleID = Alias::getModuleByAliasId($AliasId);
                    $module = Modules::getModuleById($moduleID->intFkModuleCode);

                    if ($module->varModuleNameSpace != '') {
                        $model = $module->varModuleNameSpace . 'Models\\' . $module->varModelName;
                    } else {
                        $model = '\\App\\' . $module->varModelName;
                    }
                    $modulerecord = $model::getRecords();

                    foreach ($modulerecord as $modulerec) {
                        if ($modulerec->intAliasId == $AliasId) {
                            if (isset($modulerec->id)) {
                                $innerBanner = Banner::getInnerBannerListingPage($modulerec->id, $moduleID->intFkModuleCode);
                                if(!empty($innerBanner)){
                                    break;
                                }
                            }
                        }
                    }
                 
            
                    if (count($innerBanner) < 1) {
                        $AliasId = slug::resolve_alias_for_routes(Request::segment(1));
                        $moduleID = Alias::getModuleByAliasId($AliasId);
                        if (isset($pageObj->id)) {
                            $innerBanner = Banner::getInnerBannerListingPage($pageObj->id, $moduleID->intFkModuleCode);
                            if (count($innerBanner) < 1) {
                                $innerBanner = $defaultBanner;
                            }
                        }
                    }
                }
            }
        } else {

            $innerBanner = $defaultBanner;
        }

        $innerBannerArr['inner_banner_data'] = $innerBanner;
        return $innerBannerArr;
    }

    public function shareData()
    {
        $shareData = [];
        $pageCms = null;
        $viewingPreview = false;
        $segmentsArr = Request::segments();

        if (!empty($segmentsArr) && in_array('preview', $segmentsArr)) {
            $viewingPreview = true;
        }

        $segment1 = Request::segment(1);
        $segment2 = Request::segment(2);

        $sector = '';
        if (($segment1 == "ict" || $segment1 == "water" || $segment1 == "fuel" || $segment1 == "energy") && (!empty($segment2))) {
            $pageName = $segment2;
            $sector = $segment1;
        } else {
            $pageName = $segment1;
        }
        $cmsPageId = slug::resolve_alias_for_routes(!empty($pageName) ? $pageName : 'home', $sector);

        if (null !== Request::segment(3)) {
            if (is_numeric(Request::segment(3))) {
                if (null !== Request::segment(3) && Request::segment(3) == 'preview') {
                    $cmsPageId = slug::resolve_alias_for_routes(Request::segment(2));
                    $pageCms = CmsPage::getPageByPageId($cmsPageId, true);
                } else {
                    $cmsPageId = Request::segment(2);
                    $pageCms = CmsPage::getPageByPageId($cmsPageId, false);
                }
            } else {
                if (null !== Request::segment(3) && Request::segment(3) == 'preview') {
                    if (null !== Request::segment(4) && Request::segment(4) == 'detail') {
                        $cmsPageId = slug::resolve_alias_for_routes(Request::segment(2), $sector);
                        $pageCms;
                    } else {
                        $cmsPageId = slug::resolve_alias_for_routes(Request::segment(2), $sector);
                        $pageCms = CmsPage::getPageByPageId($cmsPageId, false);

                    }
                } else {
                    $cmsPageId = slug::resolve_alias_for_routes(Request::segment(2), $sector);
                    $pageCms = CmsPage::getPageByPageId($cmsPageId, false);
                }
            }
        } else if (is_numeric($cmsPageId) && Request::segment(2) !== 'preview') {
            $pageCms = CmsPage::getPageByPageId($cmsPageId);
        } elseif (null !== Request::segment(1) && null !== Request::segment(2) && Request::segment(2) == 'preview') {
            $cmsPageId = slug::resolve_alias_for_routes(Request::segment(1), $sector);
            $pageCms = CmsPage::getPageByPageId($cmsPageId, false);
        }

        if (!Request::ajax()) {

            if (isset($pageCms->varTitle) && strtolower($pageCms->varTitle) != 'home') {

                $shareData = $this->setInnerBanner($pageCms);
            } else {
                $shareData = $this->setInnerBanner();
            }

            if (File::exists(base_path() . '/packages/Powerpanel/ContactInfo/src/Models/ContactInfo.php')) {
                $contacts = \Powerpanel\ContactInfo\Models\ContactInfo::getContactDetails();

                foreach ($contacts as $contact) {
                    if (isset($contact->chrIsPrimary) && $contact->chrIsPrimary == 'Y') {
                        $objContactInfo = $contact;
                    }
                    if (isset($contact->chrIsPrimary) && $contact->chrIsPrimary == 'N') {
                        $secondaryaddress = $contact;
                    }
                }
                $shareData['objContactInfo'] = (!empty($objContactInfo)) ? $objContactInfo : '';
                $shareData['secondaryaddress'] = (!empty($secondaryaddress)) ? $secondaryaddress : '';
            }
            Self::addLiveUsers();
        }

        if (Request::segment(2) != '') {
            $url = Request::segment(1) . '/' . Request::segment(2);
        } else {
            $url = Request::segment(1);
        }

        $this->PAGE_CONTENT = isset($pageCms->txtDescription) ? FrontPageContent_Shield::renderBuilder($pageCms->txtDescription) : Config::get('Constant.PAGE_CONTENT');

        //$Breadcumbmid = Menu::GetBreadumbid($url);
        //      $shareData['currentPageTitle'] = isset($Breadcumbid->varTitle) ? $Breadcumbid->varTitle : ucfirst(Request::segment(1));
        $shareData['PAGE_ID'] = isset($pageCms->id) ? $pageCms->id : ucfirst(Request::segment(1));
        $shareData['META_TITLE'] = isset($pageCms->varMetaTitle) ? $pageCms->varMetaTitle : ucfirst(Request::segment(1));
        $shareData['META_KEYWORD'] = isset($pageCms->varMetaKeyword) ? $pageCms->varMetaKeyword : Config::get('Constant.META_KEYWORD');
        $shareData['META_DESCRIPTION'] = isset($pageCms->varMetaDescription) ? substr(trim($pageCms->varMetaDescription), 0, 200) : Config::get('Constant.DEFAULT_META_DESCRIPTION');
        $shareData['PAGE_CONTENT'] = $this->PAGE_CONTENT;
        $shareData['PAGE_CONTENT_BOTTOM'] = isset($pageCms->txtDescription_bottom) ? $pageCms->txtDescription_bottom : Config::get('Constant.PAGE_CONTENT_BOTTOM');
        $shareData['APP_URL'] = Config::get('Constant.ENV_APP_URL');
        $shareData['SHARE_IMG'] = Config::get('Constant.FRONT_LOGO_ID');
        $shareData['VIEWING_PREVIEW'] = $viewingPreview;
        //$shareData['menuLinks'] = $data['menuLinks'];

        view()->share($shareData);
    }

    public static function addLiveUsers()
    {

        $sever_info = Request::server('HTTP_USER_AGENT');
        $ip_address = MyLibrary::get_client_ip();
        $ipCount = LiveUsers::getRecordCountByIp_insert($ip_address);
        if ($ipCount != 0) {
            LiveUsers::updateRecordByIp($ip_address, [
                'updated_at' => date('Y-m-d H:i:s'),
                'txtBrowserInf' => $sever_info,
            ]);
        } else {
            $location = MyLibrary::get_geolocation($ip_address);
            $decodedLocation = json_decode($location, true);
            if (isset($ip_address)) {

                LiveUsers::addRecord([
                    'varIpAddress' => !empty($ip_address) ? $ip_address : null,
                    'varContinent_code' => !empty($decodedLocation['continent_code']) ? $decodedLocation['continent_code'] : null,
                    'varContinent_name' => !empty($decodedLocation['continent_name']) ? $decodedLocation['continent_name'] : null,
                    'varCountry_code2' => !empty($decodedLocation['country_code2']) ? $decodedLocation['country_code2'] : null,
                    'varCountry_code3' => !empty($decodedLocation['country_code3']) ? $decodedLocation['country_code3'] : null,
                    'varCountry_name' => !empty($decodedLocation['country_name']) ? $decodedLocation['country_name'] : null,
                    'varCountry_capital' => !empty($decodedLocation['country_capital']) ? $decodedLocation['country_capital'] : null,
                    'varState_prov' => !empty($decodedLocation['state_prov']) ? $decodedLocation['state_prov'] : null,
                    'varDistrict' => !empty($decodedLocation['district']) ? $decodedLocation['district'] : null,
                    'varCity' => !empty($decodedLocation['city']) ? $decodedLocation['city'] : null,
                    'varZipcode' => !empty($decodedLocation['zipcode']) ? $decodedLocation['zipcode'] : null,
                    'varLatitude' => !empty($decodedLocation['latitude']) ? $decodedLocation['latitude'] : null,
                    'varLongitude' => !empty($decodedLocation['longitude']) ? $decodedLocation['longitude'] : null,
                    'varIs_eu' => !empty($decodedLocation['is_eu']) ? $decodedLocation['is_eu'] : null,
                    'varCalling_code' => !empty($decodedLocation['calling_code']) ? $decodedLocation['calling_code'] : null,
                    'varCountry_tld' => !empty($decodedLocation['country_tld']) ? $decodedLocation['country_tld'] : null,
                    'varLanguages' => !empty($decodedLocation['languages']) ? $decodedLocation['languages'] : null,
                    'varCountry_flag' => !empty($decodedLocation['country_flag']) ? $decodedLocation['country_flag'] : null,
                    'varGeoname_id' => !empty($decodedLocation['geoname_id']) ? $decodedLocation['geoname_id'] : null,
                    'varIsp' => !empty($decodedLocation['isp']) ? $decodedLocation['isp'] : null,
                    'varConnection_type' => !empty($decodedLocation['connection_type']) ? $decodedLocation['connection_type'] : null,
                    'varOrganization' => !empty($decodedLocation['organization']) ? $decodedLocation['organization'] : null,
                    'varCurrencyCode' => !empty($decodedLocation['currency']['code']) ? $decodedLocation['currency']['code'] : null,
                    'varCurrencyName' => !empty($decodedLocation['currency']['name']) ? $decodedLocation['currency']['name'] : null,
                    'varCurrencySymbol' => !empty($decodedLocation['currency']['symbol']) ? $decodedLocation['currency']['symbol'] : null,
                    'varTime_zoneName' => !empty($decodedLocation['time_zone']['name']) ? $decodedLocation['time_zone']['name'] : null,
                    'varTime_zoneOffset' => !empty($decodedLocation['time_zone']['offset']) ? $decodedLocation['time_zone']['offset'] : null,
                    'varTime_zoneCurrent_time' => !empty($decodedLocation['time_zone']['current_time']) ? $decodedLocation['time_zone']['current_time'] : null,
                    'varTime_zoneCurrent_time_unix' => !empty($decodedLocation['time_zone']['current_time_unix']) ? $decodedLocation['time_zone']['current_time_unix'] : null,
                    'varTime_zoneIs_dst' => !empty($decodedLocation['time_zone']['is_dst']) ? $decodedLocation['time_zone']['is_dst'] : null,
                    'varTime_zoneDst_savings' => !empty($decodedLocation['time_zone']['dst_savings']) ? $decodedLocation['time_zone']['dst_savings'] : null,
                    'txtBrowserInf' => $_SERVER['HTTP_USER_AGENT'],
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }
    }

    public function setdocumentCounter()
    {

        $docId = Request::get('docId');
        $counterType = Request::get('counterType');

        if (!empty($docId) && !empty($counterType)) {
            Document_hits::insertHits($docId, $counterType);
        }
    }

    public function download($filename)
    {
        $AWSContants = MyLibrary::getAWSconstants();
        $_APP_URL = $AWSContants['CDN_PATH'];
        $saveAsLocalPath = public_path('/documents/' . $filename);
        $file_path = $AWSContants['S3_MEDIA_BUCKET_DOCUMENT_PATH'] . '/' . $filename;
        $fileExists = Mylibrary::filePathExist($file_path);
        Aws_File_helper::getObjectWithSaveAs($file_path, $saveAsLocalPath);
        return response()->download($saveAsLocalPath, $filename);
    }

    public function check_activity()
    {
        $log_id = base64_decode($_REQUEST['rfn']);
        $arrResults = LoginLog::getRecordbyId($log_id);
        $id = $arrResults['id'];
        $fkIntUserId = $arrResults['fkIntUserId'];
        $varIpAddress = $arrResults['varIpAddress'];
        $varBrowser_Name = $arrResults['varBrowser_Name'];
        $varDevice = $arrResults['varDevice'];
        $dat_time = date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($arrResults['created_at']->setTimezone(Config::get('Constant.DEFAULT_TIME_ZONE'))));

        $User_Results = User::getRecordByIdWithoutRole($arrResults['fkIntUserId']);
        if ($User_Results['fkIntImgId'] != '') {
            $user_img = $User_Results['fkIntImgId'];
            $logo_url = resize_image::resize($user_img);
        } else {
            $logo_url = Config::get('Constant.CDN_PATH') . '/assets/images/man.png';
        }
        $email = MyLibrary::getDecryptedString($User_Results['email']);
        echo view('errors.check_activity', compact('varBrowser_Name', 'logo_url', 'email', 'varDevice', 'dat_time', 'varIpAddress', 'id'))->render();
        exit();
    }

    public function check_activity_no_secure()
    {
        $record = Request::all();
        DB::table('login_history')
            ->where('id', $record['id'])
            ->update(['chrActive' => 'N']);
    }

    public function UpdateNotificationToken()
    {
        $agent = new Agent();
        $mybrowser = $agent->browser();

        $notificationdata = DB::table('notificationtoken')
            ->select('*')
            ->where('browser', '=', $mybrowser)
            ->get();

        $record = Request::all();
        if (count($notificationdata) > 0) {
            foreach ($notificationdata as $ddata) {
                if ($ddata->browser == $mybrowser) {
                    DB::table('notificationtoken')
                        ->where('browser', '=', $mybrowser)
                        ->update(['browser' => $mybrowser, 'notificationtoken' => $record['token'], 'notificationmsg' => $record['message'], 'notificationerr' => $record['error']]);
                } else {
                    $insertqueryArray = array();
                    $insertqueryArray['browser'] = $mybrowser;
                    $insertqueryArray['notificationtoken'] = $record['token'];
                    $insertqueryArray['notificationmsg'] = $record['message'];
                    $insertqueryArray['notificationerr'] = $record['error'];
                    DB::table('notificationtoken')->insertGetId($insertqueryArray);
                }
            }
        } else {
            $insertqueryArray = array();
            $insertqueryArray['browser'] = $mybrowser;
            $insertqueryArray['notificationtoken'] = $record['token'];
            $insertqueryArray['notificationmsg'] = $record['message'];
            $insertqueryArray['notificationerr'] = $record['error'];
            DB::table('notificationtoken')->insertGetId($insertqueryArray);
        }
    }

    public function PagePassURLListing()
    {
        $record = Request::input();
        $pagedata = DB::table($record['tablename'])
            ->select('*')
            ->where('id', '=', $record['id'])
            ->first();
        if ($pagedata->varPassword == $record['passwordprotect']) {
            $html = FrontPageContent_Shield::renderBuilder($pagedata->txtDescription);
            echo json_encode($html['response']);
        } else {
            $response = array("error" => 1, 'validatorErrors' => 'Password Does Not Match');
            echo json_encode($response);
        }
    }

    public function emailToFriend(Request $request)
    {
        $data = Request::all();

        $messsages = array(
            'name.required' => 'Name is required',
            'name.handle_xss' => 'Please enter valid input',
            'name.no_url' => 'URL is not allowed',
            'email.required' => 'Email is required',
            'friendName.required' => "Friend's Name is required",
            'friendName.handle_xss' => "Please enter valid input",
            'friendName.no_url' => "URL is not allowed",
            'friendEmail.required' => "Friend's Email is required",
            'message.handle_xss' => 'Please Enter Valid Input',
            'message.no_url' => 'URL is not allowed',
             'g-recaptcha-response.required' => 'Captcha is required'
            
        );

        $rules = array(
            'name' => 'required|handle_xss|no_url',
            'email' => 'required|email|regex:[[_A-Za-z0-9-]+(\.[_A-Za-z0-9-]+)*@[A-Za-z0-9-]+(\.[A-Za-z0-9-]+)*(\.[A-Za-z]{2,4})]',
            'friendName' => 'required|handle_xss|no_url',
            'friendEmail' => 'required|email|regex:[[_A-Za-z0-9-]+(\.[_A-Za-z0-9-]+)*@[A-Za-z0-9-]+(\.[A-Za-z0-9-]+)*(\.[A-Za-z]{2,4})]',
            'message' => 'handle_xss|no_url',
             'g-recaptcha-response' => 'required'
            
        );

        $validator = Validator::make($data, $rules, $messsages);

        if ($validator->passes()) {

            Email_sender::EmailtoFriend($data);
            if (Request::ajax()) {
                return json_encode(['success' => 'We have received your request. We will get back to you shortly.']);
            } else {
                return redirect()->route('thankyou')->with(['form_submit' => true, 'message' => 'We have received your request. We will get back to you shortly.']);
            }
        } else {
                return redirect()->back()->withErrors($validator)->withInput();
            }
        
    }

    public function insertHits()
    {

        $response = false;
        $segmentsArr = Request::get('segments');
        $sector = '';

        if (!empty($segmentsArr)) {
            $segment1 = $segmentsArr[0];
            $segment2 = (isset($segmentsArr[1]) ? $segmentsArr[1] : '');
            if (($segment1 == "ict" || $segment1 == "water" || $segment1 == "fuel" || $segment1 == "energy") && (!empty($segment2))) {
                $pageName = $segment2;
                $sector = $segment1;
            } else {
                $pageName = $segment1;
            }
        } else {
            $pageName = 'home';
        }

        $cmsPageId = slug::resolve_alias_for_routes($pageName, $sector);
        $pageCms = CmsPage::getPageByPageId($cmsPageId);

        if (isset($segmentsArr[0]) && $segmentsArr[0] != end($segmentsArr)) {
            Page_hits::insertDetailPageHits(end($segmentsArr));
            $response = true;
        } else {
            Page_hits::insertHits($pageCms);
            $response = true;
        }
        return $response;
    }

    public function cookiesPopupStore() {
        Cookie::queue('cookiesPopupStore', 'cookiesPopupStore', 525600);
        return 'CookiesSetprivacy';
    }
    
}
