<?php

namespace Powerpanel\ShieldCMSTheme\Controllers\Powerpanel\profile;

use App\Http\Controllers\PowerpanelController;
use Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Contracts\Auth\Guard;
use Validator;
use App\Image;
use App\RecentUpdates;
use App\User;
use Hash;
use Illuminate\Routing\UrlGenerator;
use DB;
use Auth;
use App\Modules;
use App\Helpers\resize_image;
use App\Helpers\MyLibrary;
use Config;

class ProfileController extends PowerpanelController {

    public function __construct(UrlGenerator $url) {
        parent::__construct();
        $this->url = $url;
         $this->MyLibrary = new MyLibrary();
        if (isset($_COOKIE['locale'])) {
            app()->setLocale($_COOKIE['locale']);
        }
    }

    public function index(Guard $auth) {

        $userEmailID = $auth->user()['email'];
        $user_data = User::getRecordByEmailID($userEmailID);
        $user_data->email = MyLibrary::getDecryptedString($user_data->email);
        $user_data->personalId = MyLibrary::getDecryptedString($user_data->personalId);
        $logo = Image::getImg($user_data->fkIntImgId);
        $MyLibrary = $this->MyLibrary;
        if (!empty($logo)) {
            $logo_url = resize_image::resize($logo->id);
        } else {
            if ($this->BUCKET_ENABLED) {
                $logo_url = Config::get('Constant.CDN_PATH') . 'resources/images/upload_file.gif';
            } else {
                $logo_url = $this->url->to('resources/images/upload_file.gif');
            }
        }

        $this->breadcrumb['title'] =  trans('shiledcmstheme::template.header.myProfile');
        return view('shiledcmstheme::powerpanel.profile.change_profile', ['user_data' => $user_data,'MyLibrary' => $MyLibrary ,'user_photo' => $logo_url, 'breadcrumb' => $this->breadcrumb, 'imageManager' => true]);
    }

    static function changeprofile(Request $request, Guard $auth) {
        $requestArr = Request::all();
    	$request = (object) $requestArr;
        $data = Request::all();
        $data['email'] = MyLibrary::getEncryptedString($data['email']);
        $rules = array(
            'name' => 'required|max:150',
            'email' => 'required|max:100|unique:users,email,' . $auth->user()['id'],
            'personalId' => 'required|email|max:100'
        );
        $validator = Validator::make($data, $rules);
        $userEmailID = $auth->user()['email'];
        if ($validator->passes()) {
            $data = [
                'name' => $request->name,
                'email' => MyLibrary::getEncryptedString($request->email),
                'personalId' => MyLibrary::getEncryptedString($request->personalId),
                'fkIntImgId' => (!empty($request->img_id) ? $request->img_id : null),
            ];

            $user = User::updateUserRecordByEmail($userEmailID, $data);
            return Redirect::route('powerpanel/changeprofile')->with('message', 'The record has been successfully edited and saved.');
        } else {
            return Redirect::route('powerpanel/changeprofile')->withErrors($validator)->withInput();
        }
    }

    public function changepassword() {
        $this->breadcrumb['title'] =  trans('shiledcmstheme::template.header.changePassword');
        return view('shiledcmstheme::powerpanel.profile.change_password', ['breadcrumb' => $this->breadcrumb]);
    }

    public function handle_changepassword(Request $request, Guard $auth) {
    	$requestArr = Request::all(); 
    	$request = (object) $requestArr;
        $data = Request::all();
        $moduleCode = Modules::getModule('users');
        $rules = array(
            'old_password' => 'required|max:20',
            'new_password' => 'required|max:20|min:6|check_passwordrules',
            'confirm_password' => 'required|same:new_password|max:20|min:6|check_passwordrules',
        );
        $validation_messages = array(
            'confirm_password.same' => 'Confirm Password and New Password must match.',
        );
        $validator = Validator::make($data, $rules, $validation_messages);
        $userEmailID = $auth->user()['email'];
        $user_data = User::getRecordByEmailID($userEmailID);
        if ($validator->passes()) {
            if (Hash::check($request->old_password, $user_data->password)) {
                if ($request->old_password != $request->new_password) {
                    $data = ['password' => bcrypt($request->new_password), 'pass_change_dt' => date('Y-m-d')];
                    $user = User::updateUserRecordByEmail($userEmailID, $data);
                    if ($user) {
                        $userObj = User::getRecordByIdWithoutRole($user_data->id);
                        if (Auth::user()->can('recent-updates-list')) {
                            $notificationArr = MyLibrary::notificationData($user_data->id, $userObj, $moduleCode->id);
                            RecentUpdates::setNotification($notificationArr);
                        }
                    }
                } else {
                    return Redirect::route('powerpanel/changepassword')->with('error', 'Password is already exists please choose another password.');
                }
            } else {
                return Redirect::route('powerpanel/changepassword')->with('error', 'Old Password is not valid please enter valid password.');
            }
            return Redirect::route('powerpanel/changepassword')->with('message', 'The record has been successfully edited and saved.');
        } else {
            return Redirect::route('powerpanel/changepassword')->withErrors($validator)->withInput();
        }
    }

}
