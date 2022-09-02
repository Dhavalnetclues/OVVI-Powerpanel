<?php

namespace Powerpanel\ShieldCMSTheme\Controllers\Powerpanel\general_setting;

use App\CommonModel;
use App\EmailLog;
use App\GeneralSettings;
use App\Helpers\Aws_File_helper;
use App\Helpers\Email_sender;
use App\Helpers\MyLibrary;
use App\Http\Controllers\PowerpanelController;
use App\Modules;
use App\ModuleSettings;
use App\Pagehit;
use App\Timezone;
use App\UserNotification;
use Artisan;
use Auth;
use Cache;
use Carbon\Carbon;
use Config;
use DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Powerpanel\ContactUsLead\Models\ContactLead;
use Powerpanel\NewsletterLead\Models\NewsletterLead;
use Session;
use Validator;

class SettingsController extends PowerpanelController
{

    public function __construct()
    {
        parent::__construct();
        if (isset($_COOKIE['locale'])) {
            app()->setLocale($_COOKIE['locale']);
        }
    }

    public function index()
    {
        /*         * ******* PHP INI FILE CONTENT ********* */
        /* $phpIniPath = public_path() . '/.user.ini';
        $phpIniFileExist = $this->filePathExist($phpIniPath);
        if (!$phpIniFileExist) {
        $phpIniContent = '';
        File::put($phpIniPath, $phpIniContent);
        }
        $phpIniContent = File::get($phpIniPath); */
        /*         * ******* End of PHP INI FILE CONTENT ********* */
        /*         * ******* Robot FILE CONTENT ********* */
        /* $robotFilepath = public_path() . '/robots.txt';
        $robotFileExist = $this->filePathExist($robotFilepath);
        if (!$robotFileExist) {
        $robotFileContent = '';
        File::put($robotFilepath, $robotFileContent);
        }
        $robotFileContent = File::get($robotFilepath); */
        /*         * *******End of Robot FILE CONTENT ********* */
        $BingSiteAuthFilePath = public_path() . '/BingSiteAuth.xml';
        $bingFileName = 'BingSiteAuth.xml';
        $bingFileExist = $this->filePathExist($BingSiteAuthFilePath);
        if (!$bingFileExist) {
            GeneralSettings::checkByFieldName('BING_FILE_PATH')->update(['fieldValue' => null]);
            Config::set('Constant.BING_FILE_PATH', null);
        }
        if (!empty(Session::get('tab'))) {
            $tabSessionValue = Session::get('tab');
        } else {
            $tabSessionValue = '';
        }
        
        $timezone = Timezone::get();
        $this->breadcrumb['title'] = trans('shiledcmstheme::template.header.settings');

        $frontModuleList = Modules::select('id', 'varTitle')
            ->where('chrIsFront', 'Y')
            ->where('chrPublish', 'Y')
            ->where('chrDelete', 'N')
            ->get();

        return view('shiledcmstheme::powerpanel.general_setting.settings', [
            'tab_value' => $tabSessionValue,
            'timezone' => $timezone,
            'breadcrumb' => $this->breadcrumb,
            /* 'phpIniContent' => $phpIniContent, */
            'imageManager' => true,
            /* 'robotFileContent' => $robotFileContent */
            'frontModuleList' => (($frontModuleList->count() > 0) ? $frontModuleList->toArray() : null),
        ]);

    }

    public static function testMail()
    {
        Email_sender::testMail();
        echo '<div class="alert alert-info">Test email has been sent in your login email</div>';
    }

    public static function update_settings()
    {
        $data = Request::all();
        $BingSiteAuthFilePath = public_path() . '/BingSiteAuth.xml';
        $bingFileName = 'BingSiteAuth.xml';
        $BingfileError = false;
        if (Request::file('xml_file')) {
            $file = Request::file('xml_file');
            $pathinfo = pathinfo($file->getClientOriginalName());
            $uploadedFileExtention = $pathinfo['extension'];
            if ($uploadedFileExtention != 'xml') {
                $BingfileError = true;
            }
            if ($BingfileError == false) {
                if (self::filePathExist($BingSiteAuthFilePath)) {
                    unlink($BingSiteAuthFilePath);
                }
                $file->move(public_path(), $bingFileName);
            }
        }
        //$phpIniPath = public_path() . '/.user.ini';
        //$robotFilepath = public_path() . '/robots.txt';
        Session::forget('tab');
        Session::put('tab', Request::get('tab'));
        $tab_val = Request::get('tab');

        switch ($tab_val) {
            case 'general_settings':
                $message = array(
                    'front_logo_id.required' => 'The front logo field is required.',
                );
                $rules = array(
                    'site_name' => 'required|max:160',
                    'front_logo_id' => 'required',
                );
                break;
            case 'smtp_settings':
                $rules = array(
                    'mailer' => 'required',
                    'smtp_server' => 'required',
                    'smtp_username' => 'required',
                    'smtp_password' => 'required',
                    'smtp_port' => 'required',
                    'smtp_sender_name' => 'required',
                    'smtp_sender_id' => 'required|email',
                    /* 'mail_content' => 'required' */
                );
                break;
            case 'seo_settings':
                $rules = array(
                    'meta_title' => 'required',
                    'meta_description' => 'required',
                );
                $message = array();
                //if ($BingfileError) {
                //$rules['xml_file'] = 'required';
                $message = array(
                    'xml_file.required' => 'Please upload only xml file',
                );
                //}
                break;
            case 'social_settings':
                $rules = array(
                    'google_link' => 'url',
                    'tumblr_link' => 'url',
                    'pinterest_link' => 'url',
                    'flickr_link' => 'url',
                    'dribbble_link' => 'url',
                    'rss_feed_link' => 'url',
                );
                $message = array(
                    'google_link.url' => 'Enter valid Url',
                    'tumblr_link.url' => 'Enter valid Url',
                    'pinterest_link.url' => 'Enter valid Url',
                    'flickr_link.url' => 'Enter valid Url',
                    'dribbble_link.url' => 'Enter valid Url',
                    'rss_feed_link.url' => 'Enter valid Url',
                );
                break;
            case 'social_share_settings':
                $rules = array(
                    'fb_id' => 'required',
                    'fb_api' => 'required',
                    'fb_secret_key' => 'required',
                    'fb_access_token' => 'required',
                    'twitter_api' => 'required',
                    'twitter_secret_key' => 'required',
                    'twitter_access_token' => 'required',
                    'twitter_access_token_key' => 'required',
                    'linkedin_api' => 'required',
                    'linkedin_secret_key' => 'required',
                    'linkedin_access_token' => 'required',
                    'linkedin_access_token_key' => 'required',
                );
                break;
            case 'other_settings':
                $rules = array(
                    'google_capcha_key' => 'required',
                    'google_map_key' => 'required',
                    /* 'php_ini_content' => 'handle_xss', */
                );
                $message = array(
                    'php_ini_content.handle_xss' => 'Enter valid input.',
                );
                break;

            case 'security_settings':
                $rules = array(
                    'max_login_attempts' => 'required',
                    'retry_time_period' => 'required',
                    'lockout_time' => 'required',
                );
                $message = array(
                );
                break;
            case 'cron_settings':
                $rules = array(
                    'log_remove_time' => 'required',
                );
                $message = array(
                );
                break;
            case 'magic_settings':
                $rules = array(
                    'Magic_Send_Email' => 'required',
                    'publish_content_module' => 'required',
                    'Magic_Receive_Email' => 'required',
                    'Magic_Receive_Password' => 'required',
                );
                $message = array(
                    'Magic_Send_Email.required' => 'Website email is required',
                    'Magic_Receive_Password.required' => 'Website email is required',
                    'Magic_Receive_Email.required' => 'Assigned email is required',
                    'publish_content_module.required' => 'Please select the module',
                );
                break;
            case 'maintenancenew_settings':
                $rules = array(
                    'paymenttype' => 'required',
                    'Maintenancenew_Hour' => 'required',
                    'Maintenancenew_Rep_Send_Email' => 'required',
                );
                $message = array(
                );
                break;
            case 'features_settings':
                $rules = array(
                );
                $message = array(
                );
                break;

            case 'maintenance':
                $message = array(
                    'reset.required' => 'Please select an option to reset.',
                );
                $rules = array(
                    'reset' => 'required',
                );
                break;
        }
        if (($tab_val == 'other_settings') || ($tab_val == 'security_settings') || ($tab_val == 'cron_settings') || ($tab_val == 'features_settings') || ($tab_val == 'general_settings') || ($tab_val == 'maintenance') || ($tab_val == 'social_settings') || ($tab_val == 'seo_settings') || ($tab_val == 'maintenancenew_settings') || ($tab_val == 'magic_settings')) {
            $validator = Validator::make($data, $rules, $message);
        } else {
            $validator = Validator::make($data, $rules);
        }
        if ($validator->passes()) {
            if (Request::get('chrDepartmentEmail') == 'on') {
                $DepartmentEmail = 'Y';
            } else {
                $DepartmentEmail = 'N';
            }

            if (Request::get('chrUseSMTP') == 'on') {
                $useSMTP = 'Y';
            } else {
                $useSMTP = 'N';
            }
            switch ($tab_val) {
                case 'general_settings':
                    $arrGeneralSettings = array(
                        'SITE_NAME' => trim(Request::get('site_name')),
                        'FRONT_LOGO_ID' => Request::get('front_logo_id'),
                        'DEFAULT_TIME_ZONE' => Request::get('timezone'),
                        'DEFAULT_NEWSLETTER_EMAIL' => MyLibrary::getLaravelEncryptedString(Request::get('default_newsletter_email')),
                        'DEFAULT_EVENT_EMAIL' => MyLibrary::getLaravelEncryptedString(Request::get('default_event_email')),
                        'COMPLAINT_ADMIN_EMAIL' => MyLibrary::getLaravelEncryptedString(Request::get('default_complaint_email')),
                        'HR_EMAIL' => MyLibrary::getLaravelEncryptedString(Request::get('hr_email')),
                        'ONLINE_PAYMENT_EMAIL' => MyLibrary::getLaravelEncryptedString(Request::get('online_payment_email')),
                        'DEFAULT_REPLYTO_EMAIL' => MyLibrary::getLaravelEncryptedString(trim(Request::get('default_replyto_email'))),
                        'DEFAULT_NOTIFCATION_DEPARTMENT_EMAIL' => $DepartmentEmail,
                        'DEFAULT_CONTACTUS_EMAIL' => MyLibrary::getLaravelEncryptedString(trim(Request::get('default_contactus_email'))),
                        'SUBMIT_TICKET' => MyLibrary::getLaravelEncryptedString(trim(Request::get('default_submit_ticket_email'))),
                        'DEFAULT_FEEDBACK_EMAIL' => MyLibrary::getLaravelEncryptedString(trim(Request::get('default_feedback_email'))),
                    );
                    break;
                case 'smtp_settings':
                    $arrGeneralSettings = array(
                        'MAILER' => Request::get('mailer'),
                        'SMTP_SERVER' => trim(Request::get('smtp_server')),
                        'SMTP_USERNAME' => trim(Request::get('smtp_username')),
                        'SMTP_PASSWORD' => Request::get('smtp_password'),
                        'SMTP_ENCRYPTION' => Request::get('smtp_encryption'),
                        'SMTP_AUTHENTICATION' => Request::get('smtp_authenticattion'),
                        'SMTP_PORT' => trim(Request::get('smtp_port')),
                        'SMTP_SENDER_NAME' => trim(Request::get('smtp_sender_name')),
                        'DEFAULT_EMAIL' => MyLibrary::getLaravelEncryptedString(trim(Request::get('smtp_sender_id'))),
                        'USE_SMTP_SETTING' => $useSMTP,
                        /* 'DEFAULT_SIGNATURE_CONTENT' => Request::get('mail_content') */
                    );
                    break;
                case 'seo_settings':
                    $arrGeneralSettings = array(
                        'GOOGLE_ANALYTIC_CODE' => Request::get('google_analytic_code'),
                        'GOOGLE_TAG_MANAGER_FOR_BODY' => Request::get('google_tag_manager_for_body'),
                        'DEFAULT_META_TITLE' => trim(Request::get('meta_title')),
                        'DEFAULT_META_DESCRIPTION' => Request::get('meta_description'),
                        'META_TAG' => trim(Request::get('meta_tag')),
                        /* 'ROBOT_TXT_CONTENT' => Request::get('robotfile_content'), */
                        'BING_FILE_PATH' => $bingFileName,
                    );
                    break;
                case 'social_settings':
                    $arrGeneralSettings = array(
                        'SOCIAL_FB_LINK' => trim(Request::get('fb_link')),
                        'SOCIAL_TWITTER_LINK' => trim(Request::get('twitter_link')),
                        'SOCIAL_YOUTUBE_LINK' => trim(Request::get('youtube_link')),
                        'Google_Plus_Link' => trim(Request::get('google_link')),
                        'SOCIAL_INSTAGRAM_LINK' => trim(Request::get('instagram_link')),
                        'SOCIAL_TUMBLR_LINK' => trim(Request::get('tumblr_link')),
                        'SOCIAL_PINTEREST_LINK' => trim(Request::get('pinterest_link')),
                        'SOCIAL_FLICKR_LINK' => trim(Request::get('flickr_link')),
                        'SOCIAL_DRIBBBLE_LINK' => trim(Request::get('dribbble_link')),
                        'SOCIAL_RSS_FEED_LINK' => trim(Request::get('rss_feed_link')),
                        'SOCIAL_TRIP_ADVISOR_LINK' => trim(Request::get('trip_advisor_link')),
                    );
                    break;
                case 'social_share_settings':
                    $arrGeneralSettings = array(
                        'SOCIAL_SHARE_FB_ID' => Request::get('fb_id'),
                        'SOCIAL_SHARE_FB_API_KEY' => trim(Request::get('fb_api')),
                        'SOCIAL_SHARE_FB_SECRET_KEY' => trim(Request::get('fb_secret_key')),
                        'SOCIAL_SHARE_FB_ACCESS_TOKEN' => trim(Request::get('fb_access_token')),
                        'SOCIAL_SHARE_TWITTER_API_KEY' => trim(Request::get('twitter_api')),
                        'SOCIAL_SHARE_TWITTER_SECRET_KEY' => trim(Request::get('twitter_secret_key')),
                        'SOCIAL_SHARE_TWITTER_ACCESS_TOKEN' => trim(Request::get('twitter_access_token')),
                        'SOCIAL_SHARE_TWITTER_ACCESS_SECRET_KEY' => trim(Request::get('twitter_access_token_key')),
                        'SOCIAL_SHARE_LINKEDIN_API_KEY' => trim(Request::get('linkedin_api')),
                        'SOCIAL_SHARE_LINKEDIN_SECRET_KEY' => trim(Request::get('linkedin_secret_key')),
                        'SOCIAL_SHARE_LINKEDIN_ACCESS_TOKEN' => trim(Request::get('linkedin_access_token')),
                        'SOCIAL_SHARE_LINKEDIN_ACCESS_SECRET_KEY' => trim(Request::get('linkedin_access_token_key')),
                    );
                    break;
                case 'other_settings':
                    $available_social_links_for_team = array();
                    $available_social_links_for_team_data = Request::get('available_social_links_for_team');
                    $i = 0;
                    if (is_array($available_social_links_for_team_data)) {
                        foreach ($available_social_links_for_team_data as $key => $value) {
                            if ($value['title'] != "") {
                                $value['key'] = 'social_link_' . $i;
                                $available_social_links_for_team[$i] = $value;
                                $i++;
                            }
                        }
                    }
                    $available_social_links_for_team = serialize($available_social_links_for_team);
                    $arrGeneralSettings = array(
                        'DEFAULT_PAGE_SIZE' => 9,
                        'DEFAULT_DATE_FORMAT' => Request::get('default_date_format'),
                        'DEFAULT_TIME_FORMAT' => Request::get('time_format'),
                        'GOOGLE_MAP_KEY' => trim(Request::get('google_map_key')),
                        'GOOGLE_CAPCHA_KEY' => trim(Request::get('google_capcha_key')),
                        'GOOGLE_CAPCHA_SECRET' => trim(Request::get('google_capcha_secret')),
                        'DEFAULT_Authentication_TIME' => trim(Request::get('Authentication_Time')),
                        'BAD_WORDS' => Request::get('bad_words'),
                        /* 'PHP_INI_CONTENT' => Request::get('php_ini_content'), */
                        'AVAILABLE_SOCIAL_LINKS_FOR_TEAM_MEMBER' => $available_social_links_for_team,
                    );
                    break;
                case 'security_settings':
                    $arrGeneralSettings = array(
                        'MAX_LOGIN_ATTEMPTS' => Request::get('max_login_attempts'),
                        'RETRY_TIME_PERIOD' => Request::get('retry_time_period'),
                        'LOCKOUT_TIME' => trim(Request::get('lockout_time')),
                        'IP_SETTING' => trim(Request::get('ip_setting')),
                    );
                    break;
                case 'cron_settings':
                    $arrGeneralSettings = array(
                        'LOG_REMOVE_TIME' => Request::get('log_remove_time'),
                    );
                    break;
                case 'magic_settings':
                    $arrGeneralSettings = array(
                        'Magic_Send_Email' => Request::get('Magic_Send_Email'),
                        //'Magic_Auth_Password' => Request::get('Magic_Auth_Password'),
                        'Magic_Receive_Email' => Request::get('Magic_Receive_Email'),
                        'Magic_Receive_Password' => Request::get('Magic_Receive_Password'),
                        'PUBLISH_CONTENT_MODULE' => Request::get('publish_content_module'),
                    );
                    break;
                case 'maintenancenew_settings':
                    $arrGeneralSettings = array(
                        'paymenttype' => Request::get('paymenttype'),
                        'Maintenancenew_Hour' => Request::get('Maintenancenew_Hour'),
                        'extebdmonth' => Request::get('extebdmonth') == 'Y' ? 'Y' : 'N',
                        'Maintenancenew_Rep_Send_Email ' => Request::get('Maintenancenew_Rep_Send_Email'),
                    );
                    break;
                case 'maintenancenew_settings':
                    $arrGeneralSettings = array(
                        'paymenttype' => Request::get('Magic_Send_Email'),
                        'Maintenancenew_Hour' => Request::get('Magic_Auth_Password'),
                        'Maintenancenew_Rep_Send_Email' => Request::get('Magic_Receive_Email'),
                    );
                    break;
                case 'features_settings':
                    $arrGeneralSettings = array(
                        'DEFAULT_DRAFT' => Request::get('chrDraft') == 'on' ? 'Y' : 'N',
                        'DEFAULT_TRASH' => Request::get('chrTrash') == 'on' ? 'Y' : 'N',
                        'DEFAULT_QUICK' => Request::get('chrQuick') == 'on' ? 'Y' : 'N',
                        'DEFAULT_DUPLICATE' => Request::get('chrDuplicate') == 'on' ? 'Y' : 'N',
                        'DEFAULT_VISIBILITY' => Request::get('chrVisibility') == 'on' ? 'Y' : 'N',
                        'DEFAULT_VISUAL' => Request::get('chrVisual') == 'on' ? 'Y' : 'N',
                        'DEFAULT_FAVORITE' => Request::get('chrFavorite') == 'on' ? 'Y' : 'N',
                        'DEFAULT_ARCHIVE' => Request::get('chrArchive') == 'on' ? 'Y' : 'N',
                        'DEFAULT_FORMBUILDER' => Request::get('chrFormbuilder') == 'on' ? 'Y' : 'N',
                        'DEFAULT_PAGETEMPLATE' => Request::get('chrPageTemplate') == 'on' ? 'Y' : 'N',
                        'DEFAULT_SPELLCHCEK' => Request::get('chrSpellChcek') == 'on' ? 'Y' : 'N',
                        'DEFAULT_MESSAGINGSYSTEM' => Request::get('chrMessagingSystem') == 'on' ? 'Y' : 'N',
                        'DEFAULT_CONTENTLOCK' => Request::get('chrContentLock') == 'on' ? 'Y' : 'N',
                        'DEFAULT_AUDIO' => Request::get('chrAudio') == 'on' ? 'Y' : 'N',
                        'DEFAULT_AUTHENTICATION' => Request::get('chrAuthentication') == 'on' ? 'Y' : 'N',
                        'DEFAULT_FEEDBACKFORM' => Request::get('chrFrontFeedbackForm') == 'on' ? 'Y' : 'N',
                        'DEFAULT_ONLINEPOLLINGFORM' => Request::get('chrOnlinePollingForm') == 'on' ? 'Y' : 'N',
                        'DEFAULT_SHARINGOPTION' => Request::get('chrSharingOption') == 'on' ? 'Y' : 'N',
                        'DEFAULT_EMAILTOFRIENDOPTION' => Request::get('chrEmailtofriendOption') == 'on' ? 'Y' : 'N',
                    );
                    break;
                case 'maintenance':
                    foreach (Request::get('reset') as $key => $value) {
                        switch ($value) {
                            case "moblihits":
                                Pagehit::where('isWeb', '=', 'N')->delete();
                                break;
                            case "webhits":
                                Pagehit::where('isWeb', '=', 'Y')->delete();
                                Cache::forget('checkPageHits');
                                Cache::forget('checkInnerPageHits');
                                break;
                            case "contactleads":
                                ContactLead::truncate();
                                break;
                            case "newsletterleads":
                                NewsletterLead::truncate();
                                break;
                            case "emaillog":
                                EmailLog::truncate();
                                break;
                            case 'viewcache':
                                Self::clearViewCache();
                                break;
                            case "flushAllCache":
                                Cache::flush();
                                break;
                        }
                    }
                    break;
            }
            if ($tab_val != 'maintenance') {
                foreach ($arrGeneralSettings as $key => $value) {
                    if ($key != 'PHP_INI_CONTENT' || $key != 'ROBOT_TXT_CONTENT') {
                        GeneralSettings::checkByFieldName($key)->update(['fieldValue' => $value]);
                    }
                    /* if ($key == 'PHP_INI_CONTENT') {
                    $fileexist = self::filePathExist($phpIniPath);
                    if ($fileexist) {
                    $phpIniContent = $value;
                    File::put($phpIniPath, $phpIniContent);
                    }
                    } */
                    /* if ($key == 'ROBOT_TXT_CONTENT') {
                $fileexist = self::filePathExist($robotFilepath);
                if ($fileexist) {
                $robotFileContent = $value;
                File::put($robotFilepath, $robotFileContent);
                }
                } */
                }
            }
            self::flushCache();
        } else {
            return Redirect::route('powerpanel/settings')->withErrors($validator)->withInput();
        }
        if ($tab_val == 'maintenance') {
            return Redirect::route('powerpanel/settings')->with('message', 'The data has been successfully reset.');
        } else {
            return Redirect::route('powerpanel/settings')->with('message', 'The record has been successfully edited and saved.  ');
        }
    }

    public static function flushCache()
    {
    		$arrSettings = GeneralSettings::getSettings();
    		file_put_contents(storage_path('app/public/general_settings.json'), json_encode($arrSettings));
        Cache::tags('genralSettings')->flush();
    }

    public function getDBbackUp()
    {
        $message = trans('shiledcmstheme::template.common.oppsSomethingWrong');
        Artisan::call('backup:run');
        Session::put('tab', 'maintenance');
        $filename = base_path('storage/laravel-backups/temp/' . env('DB_DATABASE') . '.sql');
        $bytes = File::size($filename);
        if ($bytes > 0 && self::filePathExist($filename)) {
            $message = 'Database has been backed up!';
            GeneralSettings::deleteLogs();
        }
        return Redirect::route('powerpanel/settings')->with('message', $message);
    }

    public static function clearViewCache()
    {
        ob_clean();
        ob_end_flush();
        ini_set("output_buffering", "0");
        ob_implicit_flush(true);
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
        $command = "D: && cd " . base_path();
        $proc = popen($command, 'r');
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
    }

    public static function filePathExist($filepath = false)
    {
        $response = false;
        if (file_exists($filepath)) {
            $response = true;
        }
        return $response;
    }

    public static function clean($string)
    {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
        return preg_replace('/[^A-Za-z0-9\-.]/', '', $string); // Removes special chars.
    }

    public static function saveModuleSettings()
    {
        $data = Request::all();
        $id = $data['moduleId'];
        unset($data['_token']);
        $settings = json_encode($data);
        $exists = ModuleSettings::getSettings($id);
        $settings = ['intModuleId' => $id, 'txtSettings' => $settings, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')];
        if (empty($exists)) {
            CommonModel::addRecord($settings, '\\App\\ModuleSettings');
        } else {
            $whereCondArr = ['intModuleId' => $id];
            CommonModel::updateRecords($whereCondArr, $settings, false, '\\App\\ModuleSettings');
        }
        echo '<div class="alert alert-success">' . ucwords($data['moduleName']) . ' Settings saved successfully</div>';
    }

    public function getModuleSettings()
    {
        $response = false;
        $data = Request::all();
        $id = $data['moduleId'];
        Session::put('moduleSettting', $id);
        $response = ModuleSettings::getSettings($id);
        $response = isset($response->txtSettings) ? $response->txtSettings : null;
        if (!empty($response)) {
            $response = $response;
        }
        return $response;
    }

    public function getModulesAjax()
    {
        $term = Request::get('term');
        Session::put('tab', 'module');
        $modules = Modules::getModuleListForSettings($term);
        if (null == Session::get('moduleSettting')) {
            Session::put('moduleSettting', $modules[0]->id);
        }
        return view('powerpanel.partials.modulesettingtabs', ['modules' => $modules])->render();
    }

    public function insertTicket(Request $request)
    {
        $data = Request::all();
        $rules = array(
            'Name' => 'required|handle_xss|no_url|bad_words|valid_input',
            'varType' => 'required|handle_xss|no_url|bad_words|valid_input',
            'varMessage' => 'required|handle_xss|no_url|bad_words',
            'Link' => 'required|handle_xss|valid_input',
        );

        $message = array(
            'Name.required' => 'Name field is required.',
            'varType.required' => 'Type field is required.',
            'varMessage.required' => 'Message field is required.',
            'Link.required' => 'Link field is required.',
        );

        $validator = Validator::make($data, $rules, $message);

        if ($validator->passes()) {
            $varTitle = $data['Name'];
            $intType = $data['varType'];
            $txtShortDescription = $data['varMessage'];
            $Link = $data['Link'];
            $chrPublish = 'Y';
            $chrDelete = 'N';
            $chrSubmitFlag = 'Y';

            if ($data['img_val1'] != '') {

                $data1 = $data['img_val1'];
                $data1 = str_replace('data:image/png;base64,', '', $data1);
                $data12 = base64_decode($data1);
                $timestamp = str_replace([' ', ':'], '-', Carbon::now()->toDateTimeString());
                $imagename = $timestamp . "_" . self::clean($varTitle);
                $file2 = Config::get('Constant.LOCAL_CDN_PATH') . '/awsresizefiles/'.$imagename.'.png';
                if ($data['img_val1'] != '') {
                    $varCaptcher = $imagename . '.png';
                    if ($this->BUCKET_ENABLED) {
                        file_put_contents($file2, $data12);
                        $sourceFilePath = $file2;
                        Aws_File_helper::putObject($sourceFilePath, $this->S3_MEDIA_BUCKET_PATH . '/ticket_images/', $varCaptcher);
                        unlink($file2);
                    } else {
                        file_put_contents($file2, $data12);
                    }
                } else {
                    $varCaptcher = '';
                }
            } else {
                $varCaptcher = '';
            }

            DB::table('ticket_master')->insertGetId(
                array(
                    'varTitle' => $varTitle,
                    'intType' => $intType,
                    'varCaptcher' => $varCaptcher,
                    'txtShortDescription' => $txtShortDescription,
                    'varLink' => $Link,
                    'chrPublish' => $chrPublish,
                    'chrDelete' => $chrDelete,
                    'chrSubmitFlag' => $chrSubmitFlag,
                    'UserID' => Auth::user()->id,
                )
            );
            $id = DB::getPdo()->lastInsertId();
            if (Request::hasfile('file-1')) {
                foreach (Request::file('file-1') as $file) {
                    //$name=$file->getClientOriginalName();
                    $timestamp = str_replace([' ', ':'], '-', Carbon::now()->toDateTimeString());
                    $pathinfo = pathinfo($file->getClientOriginalName());
                    $filename = $timestamp . '-' . self::clean($pathinfo['filename']);
                    $name = $filename . "." . $pathinfo['extension'];
                    if (exif_imagetype($file->getPathName())) {
                        if ($this->BUCKET_ENABLED) {
                            $sourceFilePath = $file->getPathName();
                            Aws_File_helper::putObject($sourceFilePath, $this->S3_MEDIA_BUCKET_PATH . '/ticket_images/', $name);
                        } else {
                            $file->move(Config::get('Constant.LOCAL_CDN_PATH') . '/assets/images/ticket_images/', $name);
                        }
                        $data[] = $name;
                        DB::table('ticket_image')->insertGetId(
                            array('fkticketId' => $id, 'txtImageName' => $name, 'chrPublish' => $chrPublish, 'chrDelete' => $chrDelete)
                        );
                    }
                }
            }
            if (!empty($id)) {
                //Email_sender::submitTicket($data, $id);
                /* code for sending notification to super admin */
                $submitTicketModuleData = Modules::getModule('submit-tickets');
                $submitTicketModuleId = $submitTicketModuleData->id;
                $userNotificationArr = MyLibrary::userNotificationData($submitTicketModuleId);
                $userNotificationArr['fkRecordId'] = $id;
                $userNotificationArr['txtNotification'] = 'New ticket has been generated by ' . ucfirst(auth()->user()->name) . ' (Support)';
                $userNotificationArr['fkIntUserId'] = auth()->user()->id;
                $userNotificationArr['chrNotificationType'] = 'T';
                $userNotificationArr['intOnlyForUserId'] = 1;
                UserNotification::addRecord($userNotificationArr);
            }
            if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                return redirect(url('powerpanel/submit-tickets/'))->with('message', 'Thank you. Your ticket has been submitted and its under development team review.');
            } else {
                return redirect(url('powerpanel/'))->with('message', 'Thank you. Your ticket has been submitted and its under development team review.');
            }
        } else {
            return Redirect::back()->withErrors($validator)->withInput();
        }
    }

}
