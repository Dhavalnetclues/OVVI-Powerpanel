<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Powerpanel\ShieldCMSTheme\Controllers\Powerpanel\dashboard\DashboardController;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Contracts\Auth\Guard;
use App\Http\Controllers\PowerpanelController;
use App\BlockedIps;
use App\User;
use App\LoginLog;
use Config;
use Validator;
use Auth;
use Session;
use Hash;
use Cookie;
use App\Random;
use App\Helpers\Email_sender;
use Spatie\Sitemap\SitemapGenerator;
use Spatie\Crawler\Url;
use App\Helpers\MyLibrary;
use App\Helpers\time_zone;
use Jenssegers\Agent\Agent;
use DB;
use App\Helpers\resize_image;

class LoginController extends PowerpanelController {
    /*
      |--------------------------------------------------------------------------
      | Login Controller
      |--------------------------------------------------------------------------
      |
      | This controller handles authenticating users for the application and
      | redirecting them to your home screen. The controller uses a trait
      | to conveniently provide its functionality to your applications.
      |
     */

use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/powerpanel/dashboard';
    protected $redirectAfterLogout = '/powerpanel';
    protected $guard = 'web';
    private $ip = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->ip = MyLibrary::get_client_ip();
        $ipCount = BlockedIps::getRecordCountByIp($this->ip);
        if ($ipCount >= 5) {
            $message = 'This IP has been blocked due to too many login attempts!<br> Please Contact administrator for further assistance.';
            echo view('errors.attempts', compact('message'))->render();
            exit();
        }
        if (isset($_COOKIE['locale'])) {
            app()->setLocale($_COOKIE['locale']);
        }
        $this->middleware('guest')->except('logout');
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data) {
        return User::create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => bcrypt($data['password']),
        ]);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data, Request $request) {
        $rules = [
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required',
        ];
        return Validator::make($data, $rules);
    }

    public function login(Request $request, Guard $auth) {
        time_zone::time_zone();
        $messsages = array(
            'email.required' => 'Email is required.',
            'email.email' => 'Email is not valid.',
            'email.handle_xss' => 'Please enter valid input.',
            'password.required' => 'Password is required.'
        );
        $rules = [
            'email' => 'required|email',
            'email.exists' => 'Email not registered',
            'password' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules, $messsages);
        if ($validator->passes()) {
            $email = MyLibrary::getEncryptedString($request->email);
            $exitsUserEmail = User::where('email', '=', $email)->first();
            $MAX_LOGIN_ATTEMPTS = Config::get('Constant.MAX_LOGIN_ATTEMPTS');
            if (null !== $exitsUserEmail && $exitsUserEmail->intAttempts >= $MAX_LOGIN_ATTEMPTS) {
                $LOCKOUT_TIME = Config::get('Constant.LOCKOUT_TIME');
                $Last_Attempts_Time = $exitsUserEmail->Last_Attempts_Time;
                $time = strtotime($Last_Attempts_Time);
                $plus_time = $time + ($LOCKOUT_TIME * 60);
                $current_time = strtotime(date("Y-m-d H:i:s"));
                $minutes = round(abs($plus_time - $current_time) / 60, 2);
                $minutes = mb_substr($minutes, 0, 2);
                $minutes = str_replace(".", "", $minutes);
                $seconds = abs($plus_time - $current_time) % 60;
                if ($current_time > $plus_time) {
                    return redirect('powerpanel/login')->with('message', 'Oh! Sorry, you can not log in now. Try logging in after a few times.');
                } else {
                    $Diff_Time = $minutes . ':' . $seconds;
                    return redirect('powerpanel/login')->with('message', 'Oh! Sorry, you can not log in now. Try logging in after ' . $Diff_Time . ' minutes.');
                }
            }
            $remember = isset($request->remember) ? true : false;
            if (Auth::guard($this->guard)->attempt(['email' => MyLibrary::getEncryptedString($request->email), 'password' => $request->password, 'chrPublish' => 'Y', 'chrDelete' => 'N'], $remember)) {
                /* code for set cookie for remmeber login */
                if ($remember == 1) {
                    Cookie::queue('cookie_login_email', $request->email);
                    Cookie::queue('cookie_login_password', $request->password);
                    Cookie::queue('remember', $request->remember);
                } else {
                    Cookie::queue(Cookie::forget('cookie_login_email', ''));
                    Cookie::queue(Cookie::forget('cookie_login_password', ''));
                    Cookie::queue(Cookie::forget('remember', ''));
                }
                $userid = auth()->user()->id;
                $SecurityUser = \App\User::getRecordById($userid);
                $chrSecurityQuestions = $SecurityUser['chrSecurityQuestions'];
                if ($chrSecurityQuestions != 'Y') {
                    if ($exitsUserEmail->intAttempts > 0) {
                        User::updateUserRecordByEmail($email, ['intAttempts' => 0, 'First_Attempts_Time' => null, 'Last_Attempts_Time' => null]);
                    }
                } else {
                    $intSearchRank = $SecurityUser['intSearchRank'];
                    $intAttempts = $SecurityUser['intAttempts'];
                    $MAX_LOGIN_ATTEMPTS = Config::get('Constant.MAX_LOGIN_ATTEMPTS');
                    if ($intSearchRank == '1') {
                        if ($intAttempts < $MAX_LOGIN_ATTEMPTS - 3) {
                            User::updateUserRecordByEmail($email, ['intAttempts' => 0, 'First_Attempts_Time' => null, 'Last_Attempts_Time' => null]);
                        }
                    } elseif ($intSearchRank == '2') {
                        if ($intAttempts < $MAX_LOGIN_ATTEMPTS - 2) {
                            User::updateUserRecordByEmail($email, ['intAttempts' => 0, 'First_Attempts_Time' => null, 'Last_Attempts_Time' => null]);
                        }
                    } elseif ($intSearchRank == '3') {
                        if ($intAttempts < $MAX_LOGIN_ATTEMPTS - 1) {
                            User::updateUserRecordByEmail($email, ['intAttempts' => 0, 'First_Attempts_Time' => null, 'Last_Attempts_Time' => null]);
                        }
                    } else {
                        if ($exitsUserEmail->intAttempts > 0) {
                            User::updateUserRecordByEmail($email, ['intAttempts' => 0, 'First_Attempts_Time' => null, 'Last_Attempts_Time' => null]);
                        }
                    }
                }

//----------------New device signed Start----------------
                $user = Auth::user();
                $agent = new Agent;
                if ($agent->isMobile()) {
                    $device = $agent->device();
                } else {
                    $device = 'Desktop';
                }
                $browser = $agent->browser();
                $version = $agent->version($browser);
                $platform = $agent->platform();
                $record_count = LoginLog::getRecordCount($user['id'], $this->ip, $browser, $platform, $device);
                $location = MyLibrary::get_geolocation($this->ip);
                $decodedLocation = json_decode($location, true);
                $log = new LoginLog;
                $log['fkIntUserId'] = $user['id'];
                $log['varIpAddress'] = $this->ip;
                $log['varCity'] = !empty($decodedLocation['city']) ? $decodedLocation['city'] : null;
                $log['varState_prov'] = !empty($decodedLocation['state_prov']) ? $decodedLocation['state_prov'] : null;
                $log['varCountry_name'] = !empty($decodedLocation['country_name']) ? $decodedLocation['country_name'] : null;
                $log['varCountry_flag'] = !empty($decodedLocation['country_flag']) ? $decodedLocation['country_flag'] : null;
                $log['varBrowser_Name'] = $browser;
                $log['varBrowser_Version'] = $version;
                $log['varBrowser_Platform'] = $platform;
                $log['varDevice'] = $device;
                $log->save();
//                --

                if ($record_count == 0) {
                    if ($user['fkIntImgId'] != '') {
                        $user_img = $user['fkIntImgId'];
                        $logo_url = resize_image::resize($user_img);
                    } else {
                        $logo_url = Config::get('Constant.CDN_PATH') .'/assets/images/man.png';
                    }
                    $msg = $browser . ' ' . $platform . ' ' . $device;
                    $email = MyLibrary::getDecryptedString($user['email']);
                    $personalemail = MyLibrary::getDecryptedString($user['personalId']);
                    Email_sender::Security_alert($email, $personalemail, $user['name'], $logo_url, $msg, $log->id);
                }
//----------------New device signed End----------------
//----------------Two Factor Authentication Start----------------
                if (Config::get('Constant.DEFAULT_AUTHENTICATION') == 'Y') {
                    $userEmailID = $auth->user()['email'];
                    $user_data = User::getRecordByEmailID($userEmailID);
                    if ($user_data['chrAuthentication'] == 'Y') {
                        Session::put('Authentication_User', $user_data['chrAuthentication']);
                        DB::table('powerpanel_random')
                                ->where('fkIntUserId', auth()->user()->id)
                                ->update(['chrExpiry' => 'Y']);

                        $rand1 = (mt_rand(10, 62));
                        $time = substr(time(), -2);
                        $rand2 = (mt_rand(63, 99));
                        $random = $rand1 . $time . $rand2;

                        $data = array();
                        $data['fkIntUserId'] = auth()->user()->id;
                        $data['name'] = auth()->user()->name;
                        $data['email'] = auth()->user()->email;
                        $data['intCode'] = $random;
                        $data['chrExpiry'] = 'N';
                        $data['chrDelete'] = 'N';
                        $data['varIpAddress'] = MyLibrary::get_client_ip();
                        $data['created_at'] = date('Y-m-d H:i:s');
                        $data['updated_at'] = date('Y-m-d H:i:s');
                        $personalemail = MyLibrary::getDecryptedString($user_data->personalId);
                        Random::addRecord($data);
                        Email_sender::Random($data, $personalemail);
                    } else {
                        Session::put('Authentication_User', $user_data['chrAuthentication']);
                    }
                }
//----------------Two Factor Authentication End----------------
                $userIsAdmin = false;
                $currentUserRoleData = Mylibrary::getCurrentUserRoleDatils();
                if (!empty($currentUserRoleData)) {
                    if ($currentUserRoleData->chrIsAdmin == 'Y') {
                        $userIsAdmin = true;
                    }
                }
                
                if (!$userIsAdmin) {
                    $wf = DashboardController::workflowFunctions();
                    $pendingWf = $wf['pendingRoleWF'];

                    // $pendingActionCount = [];
                    // foreach ($pendingWf as $pkey => $pvalue) {
                    //     if ($pkey == $currentUserRoleData->display_name) {
                    //         foreach ($pvalue['category'] as $wkey => $wvalue) {
                    //             $pendingActionCount[] = count($wvalue);
                    //         }
                    //     }
                    // }
                    
                    if (array_key_exists($currentUserRoleData->display_name, $pendingWf)) {
                        Auth::logout();
                        return redirect('powerpanel/login')->with('message', 'You are not allowed to login as workflow is not designed for your account. Please contact to the website administrator for more information.');
                    }
                }

                Session::put('USERROLEDATA', $currentUserRoleData);
                Session::put('loghistory_id', $log->id);
                return $this->sendLoginResponse($request);
            } else {
                $exitsUserPassword = User::where('password', '=', $request->password)->where('email', '=', $email)->first();
                if (empty($exitsUserEmail)) {
                    BlockedIps::addRecord([
                        'varIpAddress' => $this->ip,
                        'varEmail' => $request->email,
                        'created_at' => date('Y-m-d H:i:s'),
                        'txtBrowserInf' => $request->header('User-Agent')
                    ]);
                    return redirect('powerpanel/login')->withErrors($validator)->withInput()->withErrors(['email' => "The email address that you've entered doesn't match any records."]);
                } else if ($exitsUserEmail->chrDelete == 'Y' || $exitsUserEmail->chrPublish == "N") {
                    $current_time = strtotime(date("Y-m-d H:i:s"));
                    $LOCKOUT_TIME = Config::get('Constant.LOCKOUT_TIME');
                    $Time = Config::get('Constant.RETRY_TIME_PERIOD');
                    $First_Attempts_Time = $exitsUserEmail->First_Attempts_Time;
                    $time = strtotime($First_Attempts_Time);
                    $plus_time = $time + ($Time * 60);
                    if ($exitsUserEmail->intAttempts > 0 && $current_time <= $plus_time) {
                        $attempt = $exitsUserEmail->intAttempts + 1;
                        User::updateUserRecordByEmail($email, ['intAttempts' => $attempt]);
                    } else {
                        $attempt = $exitsUserEmail->intAttempts;
                        User::updateUserRecordByEmail($email, ['intAttempts' => 1, 'First_Attempts_Time' => date('Y-m-d H:i:s')]);
                    }
                    if ($attempt >= $MAX_LOGIN_ATTEMPTS) {
                        User::updateUserRecordByEmail($email, ['Last_Attempts_Time' => date('Y-m-d H:i:s')]);
                        return redirect('powerpanel/login')->with('message', 'Sorry! You have exceeded the maximum number of login attempts. Please try again after ' . $LOCKOUT_TIME . ' minutes.');
                    } else {
                        return redirect('powerpanel/login')->withErrors($validator)->withInput()->withErrors(['email' => "The email address that you've entered currently not active."]);
                    }
                } else if (empty($exitsUserPassword)) {
                    $current_time = strtotime(date("Y-m-d H:i:s"));
                    $LOCKOUT_TIME = Config::get('Constant.LOCKOUT_TIME');
                    $Time = Config::get('Constant.RETRY_TIME_PERIOD');
                    $First_Attempts_Time = $exitsUserEmail->First_Attempts_Time;
                    $time = strtotime($First_Attempts_Time);
                    $plus_time = $time + ($Time * 60);
                    if ($exitsUserEmail->intAttempts > 0 && $current_time <= $plus_time) {
                        $attempt = $exitsUserEmail->intAttempts + 1;
                        User::updateUserRecordByEmail($email, ['intAttempts' => $attempt]);
                    } else {
                        $attempt = $exitsUserEmail->intAttempts;
                        User::updateUserRecordByEmail($email, ['intAttempts' => 1, 'First_Attempts_Time' => date('Y-m-d H:i:s')]);
                    }
                    if ($attempt >= $MAX_LOGIN_ATTEMPTS) {
                        User::updateUserRecordByEmail($email, ['Last_Attempts_Time' => date('Y-m-d H:i:s')]);
                        return redirect('powerpanel/login')->with('message', 'Sorry! You have exceeded the maximum number of login attempts. Please try again after ' . $LOCKOUT_TIME . ' minutes.');
                    } else {
                        return redirect('powerpanel/login')->withErrors($validator)->withInput()->withErrors(['password' => "The password that you've entered is incorrect."]);
                    }
                }
            }
            return redirect()->intended($this->redirectPath());
        } else {
            return redirect('powerpanel/login')->withErrors($validator)->withInput();
        }
    }

    public static function logout(Request $request, Guard $auth) {
        if (isset($auth->user()['id'])) {
            $id = $auth->user()['id'];
            DB::table('powerpanel_random')
                    ->where('fkIntUserId', $id)
                    ->update(['chrExpiry' => 'Y']);
        }
        Auth::logout();
        if (null !== Session::get('loghistory_id') && (Session::get('loghistory_id') != "")) {
            time_zone::time_zone();
            $logid = Session::get('loghistory_id');
            $log = new LoginLog;
            $log->where('id', $logid)->update(['chrIsLoggedOut' => 'Y', 'updated_at' => date('Y-m-d H:i:s')]);
            Session::forget('loghistory_id');
            //Session::save();
        }

        Session::forget('USERROLEDATA');
        Session::flush();
        return redirect('powerpanel/login')->with('message', 'You are successfully logged out. Thank you and have a great day.');
    }

}
