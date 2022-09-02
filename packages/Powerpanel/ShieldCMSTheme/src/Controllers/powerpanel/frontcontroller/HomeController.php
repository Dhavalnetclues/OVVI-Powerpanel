<?php

namespace App\Http\Controllers;

use DB;
use Powerpanel\Banner\Models\Banner;
use Powerpanel\News\Models\News;
use Powerpanel\QuickLinks\Models\QuickLinks;
use App\Helpers\static_block;
use App\Helpers\MyLibrary;
use Powerpanel\CmsPage\Models\CmsPage;
use App\Helpers\FrontPageContent_Shield;
use Powerpanel\Publications\Models\Publications;

class HomeController extends FrontController {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $data = array();
        $bannerObj = Banner::getHomeBannerList();
        if (!empty($bannerObj) && count($bannerObj) > 0) {
            $data['bannerData'] = $bannerObj;
        }

        $homePageCmsPageSections = CmsPage::getHomePageDisplaySections();
        if (!empty($homePageCmsPageSections)) {
            $data['homePageCmsPageSections'] = FrontPageContent_Shield::renderBuilder($homePageCmsPageSections->txtDescription);
        }
        
        $quickLinks = QuickLinks::getHomePageList(8);
        if (!empty($quickLinks)) {
            $data['quickLinks'] = array();
            $qlinkcounter = 0;
            foreach ($quickLinks as $link) {
                if ($link->varLinkType == "internal") {
                    if ($link->modules->varModuleName) {
                        $qlink = MyLibrary::getUrlLinkForQlinks($link->modules->varModuleName, $link->fkIntPageId)['uri'];
                        if (!empty($qlink)) {
                            $data['quickLinks'][$qlinkcounter]['link'] = $qlink;
                            $data['quickLinks'][$qlinkcounter]['varTitle'] = $link->varTitle;
                            $data['quickLinks'][$qlinkcounter]['varLinkType'] = $link->varLinkType;
                            $qlinkcounter++;
                        }
                    }
                } else {
                    $data['quickLinks'][$qlinkcounter]['link'] = $link->varExtLink;
                    $data['quickLinks'][$qlinkcounter]['varTitle'] = $link->varTitle;
                    $data['quickLinks'][$qlinkcounter]['varLinkType'] = $link->varLinkType;
                    $qlinkcounter++;
                }
            }
        }

        return view('index', $data);
    }

    public function getPreviousAvailableRecordData($field) {
        $getPreviousAvailableRecordData = self::getPreviousAvailableRecordData_recursive($field);
        return $getPreviousAvailableRecordData;
    }

    public function getPreviousAvailableRecordData_recursive($field, $skip = 1) {
        $response = false;
        $getPreviousAvailableRecordData = InterestRates::getPreviousAvailableRecordData($field, $skip);

        if (!empty($getPreviousAvailableRecordData) && count($getPreviousAvailableRecordData) > 0) {

            $found = 0;
            foreach ($getPreviousAvailableRecordData as $data) {
                if ($data[$field] > 0) {
                    $found = 1;
                    break;
                }
            }
            if ($found == 1) {
                return $data;
            }

            if ($found == 0) {
                $skip = $skip + 10;
                $getPreviousAvailableRecordData = self::getPreviousAvailableRecordData_recursive($field, $skip);
            }
        } else {
            return false;
        }

        return $response;
    }

}
