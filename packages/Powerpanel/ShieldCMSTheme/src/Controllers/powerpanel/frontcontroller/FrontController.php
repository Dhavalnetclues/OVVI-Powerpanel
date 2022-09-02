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
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;
use Jenssegers\Agent\Agent;
use Powerpanel\Banner\Models\Banner;
use Powerpanel\CmsPage\Models\CmsPage;
use Powerpanel\Menu\Models\Menu;
use Powerpanel\PopupContent\Models\PopUpContent;
use Response;
use Validator;

class FrontController extends Controller
{

    use slug;

    protected $breadcrumb = [];
    protected $sitemap_content;
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

            Self::loadHeaderMenu();
            $this->getNavigationMenu();
            $this->loadLeftPanelMenus();
            $this->getFooterMenu();
            $this->getPopupContent();

            if (Request::segment(1) != 'download' && Request::segment(1) != 'viewPDF') {
                $this->shareData();
            }
        }
    }

    public static function loadHeaderMenu() {
        
        $html = '';

        $headerMenu = Menu::getMenuByTypeId(1);

        $html = '';
        if ($headerMenu->count() > 0) {
            $html .= '<ul class="brand-nav brand-navbar" id="headerMenu">';
            foreach ($headerMenu as $key => $row) {

                $activeclass = '';
                $currenturl = Request::segment(1) . '/' . Request::segment(2);
                if (Request::segment(1) != '') {
                    if (Request::segment(1) == $row->txtPageUrl) {
                        $activeclass = "active";
                    } else if ($currenturl == $row->txtPageUrl) {
                        $activeclass = "active";
                    } else {
                        $activeclass = '';
                    }
                }
                if ($row->txtPageUrl == 'javascript:;') {
                    $menuurl = 'javascript:;';
                } else {
                    $menuurl = url($row->txtPageUrl);
                }

                $html .= '<li class="first ' . $activeclass . '"><a href="' . $menuurl . '" title="' . ucfirst($row->varTitle) . '">' . ucfirst($row->varTitle) . '</a>';
                $html .= Self::getHeaderChildMenuItems($row);
                $html .= '</li>';
            }
            $html .= '</ul>';
        }

        view()->share('HeadreMenuhtml', $html);
        return $html;
    }

    public static function getHeaderChildMenuItems($menuObj) {

        $html = '';
        if (isset($menuObj->childMenu) && count($menuObj->childMenu) > 0) {
            $html .= '<span class="is-open"></span>';
            $html .= '<ul class="sub-menu">';
            foreach ($menuObj->childMenu as $key => $nav) {

                $activeclass = '';
                $currenturl = Request::segment(1) . '/' . Request::segment(2);
                if (Request::segment(1) != '') {
                    if (Request::segment(1) == $nav->txtPageUrl) {
                        $activeclass = "active";
                    } else if ($currenturl == $nav->txtPageUrl) {
                        $activeclass = "active";
                    } else {
                        $activeclass = '';
                    }
                }

                if ($nav->txtPageUrl == 'javascript:;') {
                    $menuurl = 'javascript:;';
                } else {
                    $menuurl = url($nav->txtPageUrl);
                }

                $html .= '<li class="first ' . $activeclass . '">';
                $html .= '<a href="' . $menuurl . '" title="' . ucfirst($nav->varTitle) . '">' . ucfirst($nav->varTitle) . '</a>';
                if (isset($nav->childMenu) && count($nav->childMenu) > 0) {
                    $html .= Self::getHeaderChildMenuItems($nav);
                }
                $html .= '</li>';
            }
            $html .= '</ul>';
        }
        return $html;
    }

    public function getNavigationMenu()
    {
        $navigationMenu = $this->getNavigationMenuByTypeID(3);
        view()->share('navigationMenu', $navigationMenu);
    }

    public function getNavigationMenuByTypeID($typeID)
    {

        $menuObj = Menu::getMenuByTypeId($typeID);

        $html = '';
        $html .= '<ul id="accordionMenu" class="brand-nav brand-navbar">';
        foreach ($menuObj as $navmenu) {

            if ($navmenu->intParentMenuId == 0) {
                //$childmenu = Self::getNavigationChildMenu($navmenu);
                if (isset($navmenu->childMenu) && count($navmenu->childMenu) > 0) {
                    $html .= '<li class="sub-menu1">';
                } else {
                    $html .= '<li>';
                }
                $menuURL = url($navmenu->txtPageUrl);
                $currentURL = URL::current();

                $class = '';
                if ($menuURL == $currentURL) {
                    $class = 'active';
                }
                $html .= '<a href="' . url($navmenu->txtPageUrl) . '" title="' . ucfirst($navmenu->varTitle) . '" data-content="' . ucfirst($navmenu->varTitle) . '">' . ucfirst($navmenu->varTitle) . '</a> <span class="collapsed" data-toggle="collapse" data-target="#' . ucfirst($navmenu->varTitle) . '" aria-expanded="false" aria-controls="' . ucfirst($navmenu->varTitle) . '"></span>';
                $html .= Self::getNavigationChildMenu($navmenu);
                $html .= '</li>';
            }
        }
        $html .= '</ul>';
        return $html;
    }

    public static function getNavigationChildMenu($navmenu)
    {

        $html = '';
        if (isset($navmenu->childMenu) && count($navmenu->childMenu) > 0) {
            $html .= '<ul id="' . ucfirst($navmenu->varTitle) . '" class="sub-menu collapse" data-parent="#accordionMenu">';
            foreach ($navmenu->childMenu as $nav) {
                $html .= '<li>';
                $menuURL = url($nav->txtPageUrl);
                $html .= '<a href="' . url($nav->txtPageUrl) . '" title="' . ucfirst($nav->varTitle) . '">' . ucfirst($nav->varTitle) . '</a>';
                if (isset($nav->childMenu) && count($nav->childMenu) > 0) {
                    $html .= Self::getNavigationChildMenu($nav);
                }
                $html .= '</li>';
            }
            $html .= '</ul>';
        }
        return $html;
    }

    public function loadLeftPanelMenus()
    {

        $aboutUsMenu = $this->getMenuByTypeID(5);
        view()->share('aboutUsMenu', $aboutUsMenu);

        $consumerMenu = $this->getMenuByTypeID(7);
        view()->share('consumerMenu', $consumerMenu);

        $energyMenu = $this->getMenuByTypeID(8);
        view()->share('energyMenu', $energyMenu);

        $fuelMenu = $this->getMenuByTypeID(12);
        view()->share('fuelMenu', $fuelMenu);

        $ictMenu = $this->getMenuByTypeID(13);
        view()->share('ictMenu', $ictMenu);

        $waterMenu = $this->getMenuByTypeID(14);
        view()->share('waterMenu', $waterMenu);
    }

    public function getMenuByTypeID($typeID) {

        $html = '';
        $menuObj = Menu::getMenuByTypeId($typeID);
        if ($menuObj->count() > 0) {
            $html .= '<ul class="nqul d-flex flex-wrap n-fs-16 n-ff-2 n-fw-600 n-fc-black-500" id="accordianMenu">';
            foreach ($menuObj as $navmenu) {

                if ($navmenu->intParentMenuId == 0) {
                    $html .= '<li>';

                    $menuURL = url($navmenu->txtPageUrl);
                    $currentURL = URL::current();

                    $class = '';
                    if ($menuURL == $currentURL) {
                        $class = 'active';
                    }

                    $html .= '<a class="' . $class . '"  href="' . $menuURL . '" title="' . ucfirst($navmenu->varTitle) . '" data-content="' . ucfirst($navmenu->varTitle) . '">' . ucfirst($navmenu->varTitle) . '</a>';
                    $html .= Self::getChildMenuItem($navmenu);

                    $html .= '</li>';
                }
            }
            $html .= '</ul>';
        }
        return $html;

    }

    public static function getChildMenuItem($navmenu) {

        $html = '';
        if (isset($navmenu->childMenu) && count($navmenu->childMenu) > 0) {

            $html .= '<span class="collapsed" data-toggle="collapse" data-target="#' . str_slug($navmenu->varTitle) . '" aria-controls="' . str_slug($navmenu->varTitle) . '"></span>';
            if ($navmenu->intParentMenuId == 0) {
                $dataParent = '#accordianMenu';
            } else {
                $dataParent = '#' . str_slug($navmenu->varTitle);
            }

            $html .= '<ul id="' . str_slug($navmenu->varTitle) . '" class="collapse" data-parent="' . $dataParent . '">';
            foreach ($navmenu->childMenu as $nav) {
                $html .= '<li>';

                $menuURL = url($nav->txtPageUrl);
                $currentURL = URL::current();

                $class = '';
                if ($menuURL == $currentURL) {
                    $class = 'active';
                }

                $html .= '<a class="' . $class . '" href="' . url($nav->txtPageUrl) . '" title="' . ucfirst($nav->varTitle) . '">' . ucfirst($nav->varTitle) . '</a>';
                if (isset($nav->childMenu) && count($nav->childMenu) > 0) {
                    $html .= Self::getChildMenuItem($nav);
                }
                $html .= '</li>';
            }
            $html .= '</ul>';
        }
        return $html;
    }

    public function getFooterMenu() {
        $footerMenu = $this->getFooterMenuByTypeID(2);
        view()->share('footerMenu', $footerMenu);
    }

    public function getFooterMenuByTypeID($typeID) {
        $html = '';
        $menuObj = Menu::getMenuByTypeId($typeID);
        if(count($menuObj) > 0) {
            $html .= '<ul class="nqul default-nav">';
            foreach ($menuObj as $navmenu) {

                if ($navmenu->intParentMenuId == 0) {
                    $html .= '<li>';

                    $menuURL = url($navmenu->txtPageUrl);
                    $currentURL = URL::current();

                    $class = '';
                    if ($menuURL == $currentURL) {
                        $class = 'active';
                    }

                    $html .= '<a class=" ' . $class . '"  href="' . $menuURL . '" title="' . ucfirst($navmenu->varTitle) . '" data-content="' . ucfirst($navmenu->varTitle) . '">' . ucfirst($navmenu->varTitle) . '</a>';

                    $html .= '</li>';
                }
            }
            $html .= '</ul>';
        }
        return $html;
    }

    public function getPopupContent() {
        $popupObj = PopUpContent::getPopupContent();
        view()->share('popupObj', $popupObj);
        return $popupObj;
    }

    public function setInnerBanner($pageObj = false) {
        
        $innerBannerArr = [];
        $innerBannerArr['currentPageTitle'] = (isset($pageObj->varTitle) ? $pageObj->varTitle : Request::segment(1));

        $defaultBanner = Banner::getDefaultBannerList();
        $innerBanner = $defaultBanner;
        if (isset($pageObj->id)) {

            $segment1 = Request::segment(1);
            $segment2 = Request::segment(2);
            if (($segment1 == "ict" || $segment1 == "water" || $segment1 == "fuel" || $segment1 == "energy") && (!empty($segment1))) {
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
                } elseif ($segment1 !== null && $segment2 !== null) {
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

//           if (($segment1 == "ict" || $segment1 == "water" || $segment1 == "fuel" || $segment1 == "energy") && (!empty($segment1))) {
            //
            //                if (null !== Request::segment(3) && Request::segment(4) !== 'preview') {
            //                    $id = slug::resolve_alias_for_routes(Request::segment(4), $segment1);
            //                    if (Config::get('Constant.MODULE.NAME_SPACE') != '') {
            //                        $MODEL = Config::get('Constant.MODULE.NAME_SPACE') . 'Models\\' . Config::get('Constant.MODULE.MODEL_NAME');
            //                    } else {
            //                        $MODEL = '\\App\\' . Config::get('Constant.MODULE.MODEL_NAME');
            //                    }
            //                    if (is_numeric($id)) {
            //                        $recordID = $MODEL::getRecordIdByAliasID($id);
            //                        if (isset($recordID->id)) {
            //                            $recordID = (string) $recordID->id;
            //                            $innerBanner = Banner::getInnerBannerList($recordID, Config::get('Constant.MODULE.ID'));
            //                            if (count($innerBanner) < 1) {
            //                                $innerBanner = $defaultBanner;
            //                            }
            //                        }
            //                    }
            //                }
            //            } else {
            //
            //                if (null !== Request::segment(3) && Request::segment(4) !== 'preview') {
            //                    $id = slug::resolve_alias_for_routes(Request::segment(3));
            //                    if (Config::get('Constant.MODULE.NAME_SPACE') != '') {
            //                        $MODEL = Config::get('Constant.MODULE.NAME_SPACE') . 'Models\\' . Config::get('Constant.MODULE.MODEL_NAME');
            //                    } else {
            //                        $MODEL = '\\App\\' . Config::get('Constant.MODULE.MODEL_NAME');
            //                    }
            //                    if (is_numeric($id)) {
            //                        $recordID = $MODEL::getRecordIdByAliasID($id);
            //                        if (isset($recordID->id)) {
            //                            $recordID = (string) $recordID->id;
            //                            $innerBanner = Banner::getInnerBannerList($recordID, Config::get('Constant.MODULE.ID'));
            //                            if (count($innerBanner) < 1) {
            //                                $innerBanner = $defaultBanner;
            //                            }
            //                        }
            //                    }
            //                }
            //            }

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
                $cmsPageId = slug::resolve_alias_for_routes(Request::segment(2), $sector);
                $pageCms = CmsPage::getPageByPageId($cmsPageId, true);
            }
        } else if (is_numeric($cmsPageId)) {
            $pageCms = CmsPage::getPageByPageId($cmsPageId);
        }

        if (!Request::ajax()) {

            if (isset($pageCms->varTitle) && strtolower($pageCms->varTitle) != 'home') {

                $shareData = $this->setInnerBanner($pageCms);
            } else {
                $shareData = $this->setInnerBanner();
            }

            if (!in_array(Request::segment(1), ['login', 'logout']) && !$viewingPreview) {
                if (isset($segmentsArr[0]) && $segmentsArr[0] != end($segmentsArr)) {
                    Page_hits::insertDetailPageHits(end($segmentsArr));
                } else {
                    Page_hits::insertHits($pageCms);
                }
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

        $menuLinks = Menu::getHomeFooterFrontRecords();

        $data['menuLinks'] = array();
        if (!empty($menuLinks)) {
            $qlinkcounter1 = 0;
            foreach ($menuLinks as $link1) {
                $data['menuLinks'][$qlinkcounter1]['txtPageUrl'] = $link1->txtPageUrl;
                $data['menuLinks'][$qlinkcounter1]['varTitle'] = $link1->varTitle;
                $qlinkcounter1++;
            }
        }

        if (Request::segment(2) != '') {
            $url = Request::segment(1) . '/' . Request::segment(2);
        } else {
            $url = Request::segment(1);
        }

        $Breadcumbmid = Menu::GetBreadumbid($url);
//      $shareData['currentPageTitle'] = isset($Breadcumbid->varTitle) ? $Breadcumbid->varTitle : ucfirst(Request::segment(1));
        $shareData['PAGE_ID'] = isset($pageCms->id) ? $pageCms->id : ucfirst(Request::segment(1));
        $shareData['META_TITLE'] = isset($pageCms->varMetaTitle) ? $pageCms->varMetaTitle : ucfirst(Request::segment(1));
        $shareData['META_KEYWORD'] = isset($pageCms->varMetaKeyword) ? $pageCms->varMetaKeyword : Config::get('Constant.META_KEYWORD');
        $shareData['META_DESCRIPTION'] = isset($pageCms->varMetaDescription) ? substr(trim($pageCms->varMetaDescription), 0, 200) : Config::get('Constant.DEFAULT_META_DESCRIPTION');
        $shareData['PAGE_CONTENT'] = isset($pageCms->txtDescription) ? FrontPageContent_Shield::renderBuilder($pageCms->txtDescription) : Config::get('Constant.PAGE_CONTENT');
        $shareData['PAGE_CONTENT_BOTTOM'] = isset($pageCms->txtDescription_bottom) ? $pageCms->txtDescription_bottom : Config::get('Constant.PAGE_CONTENT_BOTTOM');
        $shareData['APP_URL'] = Config::get('Constant.ENV_APP_URL');
        $shareData['SHARE_IMG'] = Config::get('Constant.FRONT_LOGO_ID');
        $shareData['VIEWING_PREVIEW'] = $viewingPreview;
        $shareData['menuLinks'] = $data['menuLinks'];

        view()->share($shareData);
    }

    public static function addLiveUsers() {

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

    public function setdocumentCounter() {
        
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
            'name.required' => 'First Name is required',
            'email.required' => 'Email is required',
            'friendName.required' => "Friend's Name is required",
            'friendEmail.required' => "Friend's Email is required",
            'email.email' => 'Please enter valid email',
            'friendEmail.email' => 'Please enter valid email',
            'g-recaptcha-response.required' => 'Captcha is required',
        );

        $rules = array(
            'name' => 'required',
            'email' => 'required|email',
            'friendName' => 'required',
            'friendEmail' => 'required|email',
            'g-recaptcha-response' => 'required',
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

            //return contact form with errors
            if (!empty($data['back_url'])) {
                return redirect($data['back_url'] . '#emailToFriend_form')->withErrors($validator)->withInput();
            } else {
                return Redirect::route('/')->withErrors($validator)->withInput();
            }

        }

    }

}
