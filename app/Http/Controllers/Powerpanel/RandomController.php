<?php

namespace App\Http\Controllers\Powerpanel;

use Auth;
use App\TermsConditions;
use App\TermsConditionsReadLog;
use App\Http\Controllers\PowerpanelController;
use Config;
use Session;
use App\CommonModel;
use App\Helpers\MyLibrary;
use Illuminate\Support\Facades\Request;
use App\Random;
use DB;

class RandomController extends PowerpanelController {

    /**
     * Create a new controller instance.
     * @return void
     */
    public function __construct() {
        parent::__construct();
        if (isset($_COOKIE['locale'])) {
            app()->setLocale($_COOKIE['locale']);
        }
    }

    public function randomverify(Request $request) {
        if (Config::get('Constant.DEFAULT_AUTHENTICATION') == 'Y') {
            if ((Session::get('Authentication_User') == "Y")) {
                if ((Session::get('randomhistory_id') == "")) {
                    return view('powerpanel.random.authentication');
                }
            }
        }
        return redirect('powerpanel/dashboard');
    }

    public function question_verify(Request $request) {
        $userid = auth()->user()->id;
        $SecurityUser = \App\User::getRecordById($userid);
        $chrSecurityQuestions = $SecurityUser['chrSecurityQuestions'];
        $intSearchRank = $SecurityUser['intSearchRank'];
        $intAttempts = $SecurityUser['intAttempts'];
        $Security_history = Session::get('Security_history');
        $MAX_LOGIN_ATTEMPTS = Config::get('Constant.MAX_LOGIN_ATTEMPTS');
        if ($chrSecurityQuestions == "Y" && $Security_history == '') {
            if ($intSearchRank == '1') {
                $High_LoginLog = \App\LoginLog::getSecurity_NewIp_Device_Bro_Count();
                if ($intAttempts >= $MAX_LOGIN_ATTEMPTS - 3 || $High_LoginLog <= 1) {
                    return view('powerpanel.random.securityquestions');
                }
            } elseif ($intSearchRank == '2') {
                $Med_LoginLog = \App\LoginLog::getSecurity_NewIp_Device_Count();
                if ($intAttempts >= $MAX_LOGIN_ATTEMPTS - 2 || $Med_LoginLog <= 1) {
                    return view('powerpanel.random.securityquestions');
                }
            } elseif ($intSearchRank == '3') {
                $Low_LoginLog = \App\LoginLog::getSecurity_NewIp_Count();
                if ($intAttempts >= $MAX_LOGIN_ATTEMPTS - 1 || $Low_LoginLog <= 1) {
                    return view('powerpanel.random.securityquestions');
                }
            } else {
                return redirect('powerpanel/dashboard');
            }
        }
        return redirect('powerpanel/dashboard');
    }

    public function checkrandom(Request $request) {
        $response = array("success" => 1);
        $postArr = Request::input();
        $data['fkIntUserId'] = auth()->user()->id;
        $user_data = Random::randomcheck($data['fkIntUserId'], $postArr['random_code']);
        $checkcount = count($user_data);
        if ($checkcount == 0) {
            $response = array("success" => 0);
        } else {
            $response = array("success" => 1);
            DB::table('powerpanel_random')
                    ->where('fkIntUserId', $data['fkIntUserId'])
                    ->where('intCode', $postArr['random_code'])
                    ->update(['chrExpiry' => 'Y']);
            Session::put('randomhistory_id', $postArr['random_code']);
//            Request::session()->flash('alert-success', 'You are successfully logged in.');
        }
        echo json_encode($response);
        //SitemapGenerator::create(url('/'))->writeToFile(public_path().'/sitemap.xml');
    }

    public function insertRead() {
        $response = '';
        $data = array();
        $data['fkIntUserId'] = auth()->user()->id;
        $data['name'] = auth()->user()->name;
        $data['email'] = auth()->user()->email;
//        MyLibrary::getDecryptedString(auth()->user()->email);
        $data['chrTermsRead'] = 'Y';
        $data['varIpAddress'] = MyLibrary::get_client_ip();
        $read = TermsConditionsReadLog::addRecord($data);
        if (!empty($read)) {
            $response = $read;
        }
        return $response;
    }

}
