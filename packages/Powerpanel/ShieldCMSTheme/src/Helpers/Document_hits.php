<?php

/**
 * This helper generates email sender
 * @package   Netquick
 * @version   1.00
 * @since     2016-11-14
 */

namespace App\Helpers;

use App\Alias;
use Config;
use Session;
use App\Http\Controllers\Controller;
use App\Document;
use Carbon\Carbon;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;
use Jenssegers\Agent\Agent;
use App\Helpers\MyLibrary;
use App\CommonModel;

class Document_hits extends Controller {

    public static function insertHits($docId, $counterType) {
        $sever_info = Request::server('HTTP_USER_AGENT');
        $ip_address = MyLibrary::get_client_ip();

        $device = '';
        if (Config::get('Constant.DEVICE') == 'iPad') {
            $device = 'Y';
        } elseif (Config::get('Constant.DEVICE') == 'mobile') {
            $deviceId = explode(',', $ip_address);
            if (isset($deviceId[0])) {
                $ip_address = $deviceId[0];
            }
            $device = 'N';
        } else {
            $device = 'Y';
        }

        $session_id = Session::getId();

        if (!empty($sever_info) && !empty($device) && !empty($ip_address) && !empty($docId) && !empty($counterType)) {
            $response = Document::getDocDataForHitsById($docId);
            if (isset($response->id)) {
                $docDataArray = [];
                $whereConditions = ['id' => $docId];

                if ($counterType == "download") {
                    if ($device == "N") {
                        $docDataArray['intMobileDownloadCount'] = (int) $response->intMobileDownloadCount + 1;
                    } else {
                        $docDataArray['intDesktopDownloadCount'] = (int) $response->intDesktopDownloadCount + 1;
                    }
                } else if ($counterType == "view") {
                    if ($device == "N") {
                        $docDataArray['intMobileViewCount'] = (int) $response->intMobileViewCount + 1;
                    } else {
                        $docDataArray['intDesktopViewCount'] = (int) $response->intDesktopViewCount + 1;
                    }
                }

                if (!empty($docDataArray)) {
                    CommonModel::updateRecords($whereConditions, $docDataArray, false, "\app\Document");
                }
            }
        }
    }

}
