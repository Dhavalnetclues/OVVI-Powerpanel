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
use App\Pagehit;
use Carbon\Carbon;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;
use Jenssegers\Agent\Agent;
use App\Helpers\MyLibrary;

class Page_hits extends Controller {

    public static function insertHits($page) {
        $aliasID = null;
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
        if (isset($page->intAliasId)) {
            $aliasID = $page->intAliasId;
        }
        if (isset($page->id)) {
            $RecordID = $page->id;
        }

        $aliasObj = Alias::getAliasbyID($aliasID);
        if (!empty($aliasObj) && isset($aliasObj->intFkModuleCode)) {
            $ModuleID = $aliasObj->intFkModuleCode;
        }
        $session_id = Session::getId();
        // echo $session_id;
        // exit();
        //if (!empty($sever_info) && !empty($device) && !empty($ip_address) && !empty($aliasID)) {
        if (!empty($sever_info) && !empty($device) && !empty($ip_address) && !empty($aliasID)) {
            $response = Pagehit::select('id')->where(['fkIntAliasId' => $aliasID, 'varSessionId' => $session_id, 'isWeb' => $device])->first();
            if (!isset($response->id)) {
                Pagehit::insert([
                    'fkIntAliasId' => $aliasID,
                    'intFKRecordCode' => $RecordID,
                    'intFKModuleCode' => $ModuleID,
                    'varBrowserInfo' => $sever_info,
                    'isWeb' => $device,
                    'varIpAddress' => $ip_address,
                    'varSessionId' => $session_id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }
    }

    public static function insertDetailPageHits($segmentTwo) {
        $aliasID = null;
        $sever_info = Request::server('HTTP_USER_AGENT');
        $ip_address = MyLibrary::get_client_ip();

        $device = '';
        $session_id = Session::getId();
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
        if (!empty($segmentTwo)) {
            $aliasObj = Alias::getAlias($segmentTwo);
            if (!empty($aliasObj) && isset($aliasObj->id)) {
                $aliasID = $aliasObj->id;
            }
            if (!empty($aliasObj) && isset($aliasObj->intFkModuleCode)) {
                $ModuleID = $aliasObj->intFkModuleCode;
            }
        }
        if (!empty($sever_info) && !empty($device) && !empty($ip_address) && !empty($aliasID)) {
            $isExist = Pagehit::select('id')->where(['fkIntAliasId' => $aliasID, 'varSessionId' => $session_id, 'isWeb' => $device])->first();
            if (!isset($isExist->id)) {
                Pagehit::insert([
                    'fkIntAliasId' => $aliasID,
//                    'intFKRecordCode' => $RecordID,
                    'intFKModuleCode' => $ModuleID,
                    'varBrowserInfo' => $sever_info,
                    'isWeb' => $device,
                    'varIpAddress' => $ip_address,
                    'varSessionId' => $session_id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }
    }

}
