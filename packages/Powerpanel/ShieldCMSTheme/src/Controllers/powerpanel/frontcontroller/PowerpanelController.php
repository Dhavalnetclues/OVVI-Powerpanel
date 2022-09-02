<?php

namespace App\Http\Controllers;

use App\Alias;
use App\Audio;
use App\CommonModel;
use App\Document;
use App\Helpers\Aws_File_helper;
use App\Helpers\MyLibrary;
use App\Helpers\PageHitsReport;
use App\Helpers\resize_image;
use App\Helpers\time_zone;
use App\Http\Controllers\Controller;
use App\Http\Traits\slug;
use App\Image;
use App\Log;
use App\LoginLog;
use App\Modules;
use App\Permission;
use App\TermsConditions;
use App\User;
use App\UserNotification;
use Artisan;
use Auth;
use Carbon\Carbon;
use Config;
use DB;
use File;
use Illuminate\Support\Facades\Input;
use Jenssegers\Agent\Agent;
use Request;
use Session;
use Zip;

class PowerpanelController extends Controller
{

    use slug;

    protected $breadcrumb = [];
    protected $commonVariableArr = [];
    protected $module_code;
    protected $currentUserRoleData;
    protected $BUCKET_ENABLED;
    protected $S3_MEDIA_BUCKET_PATH;
    protected $S3_MEDIA_BUCKET_DOCUMENT_PATH;
    protected $S3_MEDIA_BUCKET_GENERAL_PATH;
    protected $S3_MEDIA_BUCKET_XML_PATH;
    protected $S3_MEDIA_BUCKET_VIDEO_PATH;
    protected $currentUserAccessibleModules;

    public function __construct()
    {
        time_zone::time_zone();
        $this->commonVariableArr['userId'] = Auth::id();
        $this->commonVariableArr['ipAddress'] = MyLibrary::get_client_ip();
        $this->middleware(function ($request, $next) {
//            ======
            $log_id = Session::get('loghistory_id');
            $arrResults = LoginLog::getRecordbyId($log_id);
            if (isset($arrResults['chrActive']) && $arrResults['chrActive'] != 'Y') {
                Auth::logout();
                return redirect('powerpanel/login')->with('message', "You're not authorized to access PowerPanel.");
            }
//            ======
            $this->logo();
            $userIsAdmin = false;
            $superAdmin = false;
            if (null !== Session::get('USERROLEDATA') && null != Auth::check()) {
                $this->currentUserRoleData = Session::get('USERROLEDATA');
                if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                    $userIsAdmin = true;
                }
                if ($this->currentUserRoleData->name == "netquick_admin") {
                    $superAdmin = true;
                }
            }
            if (!$superAdmin) {
                $termsAccepted = 'N';
                $userId = Auth::id();
                $accepted = TermsConditions::getRecord($userId);
                if (!empty($accepted)) {
                    $termsAccepted = $accepted->chrAccepted;
                }
            } else {
                $termsAccepted = 'Y';
            }
            if (empty($this->currentUserAccessibleModules)) {
                $currentUserAccessibleModules = array();
                $activeModules = Modules::getAllActiveModules();
                if (!empty($activeModules)) {
                    $i = 0;
                    foreach ($activeModules as $moduleData) {
                        $permissioname = $moduleData->varModuleName . '-list';
                        if (Auth::user()->can($permissioname)) {
                            $currentUserAccessibleModules[$i]['id'] = $moduleData->id;
                            $currentUserAccessibleModules[$i]['varModuleName'] = $moduleData->varModuleName;
                            $i++;
                        }
                    }
                }
                $this->currentUserAccessibleModules = $currentUserAccessibleModules;
            }
            view()->share('currentUserAccessibleModules', $this->currentUserAccessibleModules);
            view()->share('termsAccepted', $termsAccepted);
            view()->share('userIsAdmin', $userIsAdmin);
            return $next($request);
        });
        $this->BUCKET_ENABLED = Config::get('Constant.BUCKET_ENABLED');
        $this->S3_MEDIA_BUCKET_PATH = Config::get('Constant.S3_MEDIA_BUCKET_PATH');
        $this->S3_MEDIA_BUCKET_DOCUMENT_PATH = Config::get('Constant.S3_MEDIA_BUCKET_DOCUMENT_PATH');
        $this->S3_MEDIA_BUCKET_GENERAL_PATH = Config::get('Constant.S3_MEDIA_BUCKET_GENERAL_PATH');
        $this->S3_MEDIA_BUCKET_XML_PATH = Config::get('Constant.S3_MEDIA_BUCKET_XML_PATH');
        $this->S3_MEDIA_BUCKET_VIDEO_PATH = Config::get('Constant.S3_MEDIA_BUCKET_VIDEO_PATH');
        view()->share('allLocale', $this->getLang());
        view()->share('CDN_PATH', Config::get('Constant.CDN_PATH'));
        view()->share('BUCKET_ENABLED', Config::get('Constant.BUCKET_ENABLED'));
    }

    public function logo()
    {

        $user_data = Auth::user();
        $sessionEmailID = '';
        if (!empty($user_data->email)) {
            $sessionEmailID = $user_data->email;
        }
        if (!empty($sessionEmailID)) {
            if (!empty($user_data->fkIntImgId)) {
                $logo_url = resize_image::resize($user_data->fkIntImgId);
                view()->share('User_logo_url', $logo_url);
            } else {
                $logo_url = Config::get('Constant.CDN_PATH') . 'assets/images/default.png';
                view()->share('User_logo_url', $logo_url);
            }
        }
    }

    public function getLang()
    {
        $langArray = array();
        $directory = base_path('resources/lang');
        $dir = File::directories($directory);
        foreach ($dir as $directory) {
            array_push($langArray, str_replace('//', '', explode('/lang', $directory)[1]));
        }
        return $langArray;
    }

    /**
     * This method generates alias
     * @return  JSON formata data of alias
     * @since   2017-10-30
     * @author  NetQuick
     */
    public function aliasGenerate()
    {
        $alias = Request::input('alias');
        $sector = Request::input('sector');

        $unique = true;
        if (!empty($sector)) {
            $unique = false;
        }
        $slug = slug::create_slug($alias, $unique);
        $response = array('alias' => $slug);
        echo json_encode($response);
    }

    /**
     * This method generates seo content
     * @return  Meta values
     * @since   2017-10-30
     * @author  NetQuick
     */
    public function generateSeoContent()
    {
        $fromajax = true;
        $title = Request::input('title');
        $title = ($title == "") ? Request::input('name') : $title;
        //$description = Request::input('description');
        $desc = Request::input('description');
        $description = trim(preg_replace('/ +/', ' ', preg_replace('/[^A-Za-z0-9 &]/', '', urldecode(html_entity_decode(strip_tags($desc))))));
        $seoData = MyLibrary::generateSeocontent($title, $description, $fromajax);
        return $seoData;
    }

    public function install($file)
    {
        $zip = Zip::open(base_path('storage/' . $file . '.zip'));
        $zip->extract(base_path('storage/' . $file . '/'));

        File::copyDirectory(base_path('storage/' . $file . '/app'), base_path('app/'));
        File::copyDirectory(base_path('storage/' . $file . '/database'), base_path('database/'));
        File::copyDirectory(base_path('storage/' . $file . '/public_html'), base_path('public_html/'));
        File::copyDirectory(base_path('storage/' . $file . '/resources'), base_path('resources/'));
        File::deleteDirectory(base_path('storage/' . $file));

        ob_clean();
        ob_end_flush();
        ini_set("output_buffering", "0");
        ob_implicit_flush(true);
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');

        $command = "D: && cd " . base_path('/') . " && composer dump-autoload";
        $proc = popen($command, 'r');
        while (!feof($proc)) {
            $this->echoEvent(fread($proc, 4096));
        }
        $this->echoEvent("finish");

        Artisan::call('cache:clear');
        Artisan::call('migrate');
        Artisan::call('db:seed', ['--class' => 'ModuleEntryTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'PermissionTableSeeder']);
    }

    public function echoEvent($datatext)
    {
        echo "data: " . implode("\ndata: ", explode("\n", $datatext)) . "\n\n";
    }

    public static function GetVersion()
    {
        $agent = new Agent();
// Chrome, IE, Safari, Firefox, ...
        $browser = $agent->browser();
        $version = $agent->version($browser);
        return $version;
    }

    public static function Getbrowser()
    {
        $agent = new Agent();
// Chrome, IE, Safari, Firefox, ...
        $browser = $agent->browser();
        return $browser;
    }

    /**
     * This method Quick Edit of page.
     * @author  Snehal
     */
    public static function Quickedit_Listing()
    {
        $record = Request::input();
        $modiledata = Modules::getModuleById($record['moduleid']);
        $start_date_time = !empty($record['start_date_time']) ? date('Y-m-d H:i:s', strtotime($record['start_date_time'])) : date('Y-m-d H:i:s');
        $end_date_time = !empty($record['end_date_time']) ? date('Y-m-d H:i:s', strtotime($record['end_date_time'])) : null;
        DB::table($modiledata['varTableName'])
            ->where('id', $record['id'])
            ->update(['varTitle' => $record['name'], 'intSearchRank' => $record['search_rank'], 'dtDateTime' => $start_date_time, 'dtEndDateTime' => $end_date_time]);
        if ($record['moduleid'] == 4) {
            DB::table('menu')
                ->where('intPageId', $record['id'])
                ->update(['varTitle' => $record['name']]);
        }
        if ($record['value'] == 'P') {
            $trashdata = Config::get('Constant.QUICK_EDIT_TO_PRIMARY_RECORD');
        } elseif ($record['value'] == 'A') {
            $trashdata = Config::get('Constant.QUICK_EDIT_TO_APPROVE_RECORD');
        } elseif ($record['value'] == 'D') {
            $trashdata = Config::get('Constant.QUICK_EDIT_TO_DRAFT_RECORD');
        } else {
            $trashdata = Config::get('Constant.QUICK_EDIT_TO_FAVORITE_RECORD');
        }
        $logArr = MyLibrary::logData($record['id'], $record['moduleid'], $trashdata);
        $logArr['varTitle'] = stripslashes($record['name']);
        Log::recordLog($logArr);
    }

    /**
     * This method Copy of Page.
     * @author  Snehal
     */
    public function Copy_Listing()
    {
        $record = Request::input();
        $modiledata = Modules::getModuleById($record['moduleid']);
        $pagedata = DB::table($modiledata['varTableName'])
            ->select('*')
            ->where('id', '=', $record['id'])
            ->first();
        if (\Schema::hasColumn($modiledata['varTableName'], 'intDisplayOrder')) {
            $displayorderdata = DB::table($modiledata['varTableName'])
                ->select('*')
                ->where('chrDelete', 'N')
                ->orderBy('intDisplayOrder', 'desc')
                ->first();
        }
        foreach ($pagedata as $key => $item) {
            $DataArr[$key] = $item;
        }
        if (!empty($pagedata->intAliasId)) {
            $aliasdata = DB::table('alias')
                ->where('id', $pagedata->intAliasId)
                ->where('chrIsPreview', "N")
                ->first();
            $genratedAlias = slug::create_slug($pagedata->varTitle);
            $newGenratedAlias = $genratedAlias[0];
            $data = [
                'intFkModuleCode' => $aliasdata->intFkModuleCode,
                'varAlias' => $newGenratedAlias,
                'chrIsPreview' => $aliasdata->chrIsPreview];
            $aliasid = Alias::insertGetId($data);
            $DataArr['intAliasId'] = $aliasid;
        }
        unset($DataArr['id']);
        unset($DataArr['FavoriteID']);
        if (isset($pagedata->varTitle) && !empty($pagedata->varTitle)) {
            $DataArr['varTitle'] = $pagedata->varTitle . ' - Copy';
        } else {
            if (isset($pagedata->nxx) && !empty($pagedata->nxx)) {
                $DataArr['nxx'] = $pagedata->nxx . ' - Copy';
            }
        }

        if (isset($displayorderdata->intDisplayOrder)) {
            $DataArr['intDisplayOrder'] = $displayorderdata->intDisplayOrder + 1;
        }
        if(isset($pagedata->chrPublish)) {
            $DataArr['chrPublish'] = 'N';
        }
        $DataArr['chrAddStar'] = 'N';
        $DataArr['created_at'] = Carbon::now();
        $DataArr['updated_at'] = Carbon::now();
        $MODULE_NAME = 'App\\';
        if ($modiledata['varModuleNameSpace'] !== '' && !empty($modiledata) && $modiledata != 'null' && $modiledata != null) {
            $MODULE_NAME = $modiledata['varModuleNameSpace'] . 'Models\\';
        }
        $id = CommonModel::addRecord($DataArr, $MODULE_NAME . $modiledata['varModelName']);
        $trashdata = Config::get('Constant.PRIMARY_RECORD_COPY');
        $logArr = MyLibrary::logData($record['id'], $record['moduleid'], $trashdata);
        if (isset($pagedata->varTitle) && !empty($pagedata->varTitle)) {
            $logArr['varTitle'] = stripslashes($pagedata->varTitle);
        } else {
            if (isset($pagedata->nxx) && !empty($pagedata->nxx)) {
                $logArr['varTitle'] = stripslashes($pagedata->nxx);
            }
        }
        Log::recordLog($logArr);
    }

    /**
     * This method Favorite of Page.
     * @author  Snehal
     */
    public function Favorite_Listing()
    {
        $record = Request::input();
        $modiledata = Modules::getModuleById($record['moduleid']);
        if ($record['flag'] == 'Y') {
            if ($record['tab'] == 'P') {
                $favoritedata = Config::get('Constant.PRIMARY_RECORD_MOVE_TO_FAVORITE');
            } elseif ($record['tab'] == 'A') {
                $favoritedata = Config::get('Constant.APPROVE_RECORD_MOVE_TO_FAVORITE');
            } elseif ($record['tab'] == 'D') {
                $favoritedata = Config::get('Constant.DRAFT_RECORD_MOVE_TO_FAVORITE');
            } elseif ($record['tab'] == 'T') {
                $favoritedata = Config::get('Constant.TRASH_RECORD_MOVE_TO_FAVORITE');
            } else {
                $favoritedata = Config::get('Constant.PRIMARY_RECORD_MOVE_TO_FAVORITE');
            }
        } else if ($record['flag'] == 'N') {
            if ($record['tab'] == 'P') {
                $favoritedata = Config::get('Constant.FAVORITE_RECORD_MOVE_TO_PRIMARY');
            } elseif ($record['tab'] == 'A') {
                $favoritedata = Config::get('Constant.FAVORITE_RECORD_MOVE_TO_APPROVE');
            } elseif ($record['tab'] == 'D') {
                $favoritedata = Config::get('Constant.FAVORITE_RECORD_MOVE_TO_DRAFT');
            } elseif ($record['tab'] == 'T') {
                $favoritedata = Config::get('Constant.FAVORITE_RECORD_MOVE_TO_TRASH');
            } else {
                $favoritedata = Config::get('Constant.FAVORITE_RECORD_MOVE_TO_PRIMARY');
            }
        }
        if (isset($modiledata['varModuleNameSpace']) && $modiledata['varModuleNameSpace'] != '') {
            $modelNameSpace = $modiledata['varModuleNameSpace'] . 'Models\\' . $modiledata['varModelName'];
        } else {
            $modelNameSpace = '\\App\\' . $modiledata['varModelName'];
        }
//        $modelNameSpace = '\\App\\' . $modiledata['varModelName'];
        $newCmsPageObj = $modelNameSpace::getRecordForLogById($record['id']);
        if ($record['flag'] == 'Y') {
            if (!empty($newCmsPageObj->FavoriteID)) {
                $Favorite_array = explode(",", $newCmsPageObj->FavoriteID);
                array_unshift($Favorite_array, auth()->user()->id);
                $FavoriteID = implode(",", $Favorite_array);
            } else {
                $FavoriteID = auth()->user()->id;
            }
        } else if ($record['flag'] == 'N') {
            $Favorite_array = explode(",", $newCmsPageObj->FavoriteID);
            $remove = array(auth()->user()->id);
            $new_array = array_diff($Favorite_array, $remove);
            $FavoriteID = implode(",", $new_array);
        }
        $FavoriteID = !empty($FavoriteID) ? $FavoriteID : null;
        $logArr = MyLibrary::logData($record['id'], $record['moduleid'], $favoritedata);
        $logArr['varTitle'] = stripslashes($newCmsPageObj->varTitle);
        Log::recordLog($logArr);
        DB::table($modiledata['varTableName'])
            ->where('id', $record['id'])
            ->update(['FavoriteID' => $FavoriteID]);
    }

    /**
     * This method Archive of Page.
     * @author  Snehal
     */
    public function Archive_Listing()
    {
        $record = Request::input();
        $modiledata = Modules::getModuleById($record['moduleid']);

        $archivedata = Config::get('Constant.PRIMARY_RECORD_MOVE_TO_ARCHIVE');
        if (isset($modiledata['varModuleNameSpace']) && $modiledata['varModuleNameSpace'] != '') {
            $modelNameSpace = $modiledata['varModuleNameSpace'] . 'Models\\' . $modiledata['varModelName'];
        } else {
            $modelNameSpace = '\\App\\' . $modiledata['varModelName'];
        }
//        $modelNameSpace = '\\App\\' . $modiledata['varModelName'];
        $newCmsPageObj = $modelNameSpace::getRecordForLogById($record['id']);

        $logArr = MyLibrary::logData($record['id'], $record['moduleid'], $archivedata);
        $logArr['varTitle'] = stripslashes($newCmsPageObj->varTitle);
        Log::recordLog($logArr);
        DB::table($modiledata['varTableName'])
            ->where('id', $record['id'])
            ->update(['chrArchive' => 'Y', 'dtEndDateTime' => date('Y-m-d H:i:s')]);
    }

    public function TrashData_Listing()
    {
        $record = Request::input();
        $modiledata = Modules::getModuleById($record['moduleid']);
        $trashdata = Config::get('Constant.PRIMARY_MOVE_TO_TRASH');
        if (isset($modiledata['varModuleNameSpace']) && $modiledata['varModuleNameSpace'] != '') {
            $modelNameSpace = $modiledata['varModuleNameSpace'] . 'Models\\' . $modiledata['varModelName'];
        } else {
            $modelNameSpace = '\\App\\' . $modiledata['varModelName'];
        }
//        $modelNameSpace = '\\App\\' . $modiledata['varModelName'];
        $newCmsPageObj = $modelNameSpace::getRecordForLogById($record['id']);
        $logArr = MyLibrary::logData($record['id'], $record['moduleid'], $trashdata);
        $logArr['varTitle'] = stripslashes($newCmsPageObj->varTitle);
        Log::recordLog($logArr);
        DB::table($modiledata['varTableName'])
            ->where('id', $record['id'])
            ->update(['chrTrash' => 'Y']);
        if(method_exists($modelNameSpace, 'ReorderAllrecords')){
            $modelNameSpace::ReorderAllrecords();
        }
    }

    public function RestoreData_Listing()
    {
        $record = Request::input();
        $modiledata = Modules::getModuleById($record['moduleid']);
        if ($record['tabdata'] == 'T') {
            $restoredata = Config::get('Constant.TRASH_RECORD_RESTORE');
        } else {
            $restoredata = Config::get('Constant.RECORD_RESTORE');
        }
        if (isset($modiledata['varModuleNameSpace']) && $modiledata['varModuleNameSpace'] != '') {
            $modelNameSpace = $modiledata['varModuleNameSpace'] . 'Models\\' . $modiledata['varModelName'];
        } else {
            $modelNameSpace = '\\App\\' . $modiledata['varModelName'];
        }
//        $modelNameSpace = '\\App\\' . $modiledata['varModelName'];
        $newCmsPageObj = $modelNameSpace::getRecordForLogById($record['id']);
        $logArr = MyLibrary::logData($record['id'], $record['moduleid'], $restoredata);
        $logArr['varTitle'] = stripslashes($newCmsPageObj->varTitle);
        Log::recordLog($logArr);
        DB::table($modiledata['varTableName'])
            ->where('id', $record['id'])
            ->update(['chrTrash' => 'N']);
    }

    public function UnArchiveData_Listing()
    {
        $record = Request::input();
        $modiledata = Modules::getModuleById($record['moduleid']);

        $restoredata = Config::get('Constant.RECORD_UNARCHIVE');
        if (isset($modiledata['varModuleNameSpace']) && $modiledata['varModuleNameSpace'] != '') {
            $modelNameSpace = $modiledata['varModuleNameSpace'] . 'Models\\' . $modiledata['varModelName'];
        } else {
            $modelNameSpace = '\\App\\' . $modiledata['varModelName'];
        }
//        $modelNameSpace = '\\App\\' . $modiledata['varModelName'];
        $newCmsPageObj = $modelNameSpace::getRecordForLogById($record['id']);
        $logArr = MyLibrary::logData($record['id'], $record['moduleid'], $restoredata);
        $logArr['varTitle'] = stripslashes($newCmsPageObj->varTitle);
        Log::recordLog($logArr);
        DB::table($modiledata['varTableName'])
            ->where('id', $record['id'])
            ->update(['chrArchive' => 'N', 'dtEndDateTime' => null]);
    }

    public function RemoveDarftData()
    {
        $record = Request::input();
        $modiledata = Modules::getModuleById($record['moduleid']);
        DB::table($modiledata['varTableName'])
            ->where('id', $record['id'])
            ->update(['chrPublish' => 'Y', 'chrDraft' => 'N']);
    }

    public function Notification_View()
    {
        $currentUserAccessibleModulesIDs = array();
        if (!empty($this->currentUserAccessibleModules)) {
            foreach ($this->currentUserAccessibleModules as $moduledata) {
                array_push($currentUserAccessibleModulesIDs, $moduledata['id']);
            }
        }
        $userIsAdmin = false;
        if (null !== Session::get('USERROLEDATA') && null != Auth::check()) {
            $this->currentUserRoleData = Session::get('USERROLEDATA');
            if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                $userIsAdmin = true;
            }
        }
        $Today = UserNotification::getRecordListToday($userIsAdmin, $currentUserAccessibleModulesIDs);
        $todayIds = array_column($Today->toArray(), 'id');
        $sevenDays = UserNotification::getRecordListLastsevenDays($userIsAdmin, $currentUserAccessibleModulesIDs);
        $sevenIds = array_column($sevenDays->toArray(), 'id');
        $notificationIds = array_merge($todayIds, $sevenIds);
        $notificationIds = array_unique($notificationIds);
        $ReadTotal = UserNotification::getReadCount($notificationIds);
        $Total = count($Today) + count($sevenDays) - $ReadTotal;
        $data = array();
        $data['Today'] = $Today;
        $data['sevenDays'] = $sevenDays;
        $data['ReadTotal'] = $ReadTotal;
        $data['Total'] = $Total;
        $data['currentUserAccessibleModulesIDs'] = $currentUserAccessibleModulesIDs;
        return view('notification-header', $data)->render();
    }

    public function header_notification_count()
    {
        $currentUserAccessibleModulesIDs = array();
        if (!empty($this->currentUserAccessibleModules)) {
            foreach ($this->currentUserAccessibleModules as $moduledata) {
                array_push($currentUserAccessibleModulesIDs, $moduledata['id']);
            }
        }
        $userIsAdmin = false;
        if (null !== Session::get('USERROLEDATA') && null != Auth::check()) {
            $this->currentUserRoleData = Session::get('USERROLEDATA');
            if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                $userIsAdmin = true;
            }
        }
        $Today = UserNotification::getRecordListToday($userIsAdmin, $currentUserAccessibleModulesIDs);
        $todayIds = array_column($Today->toArray(), 'id');
        $sevenDays = UserNotification::getRecordListLastsevenDays($userIsAdmin, $currentUserAccessibleModulesIDs);
        $sevenIds = array_column($sevenDays->toArray(), 'id');
        $notificationIds = array_merge($todayIds, $sevenIds);
        $notificationIds = array_unique($notificationIds);
        $ReadTotal = UserNotification::getReadCount($notificationIds);
        $Total = count($Today) + count($sevenDays) - $ReadTotal;
        return $Total;
    }

    public function Hits_Listing()
    {
        $record = Request::input();
        $webHits = PageHitsReport::WebHits($record['id'], $record['name']);
        return $webHits;
    }

    public function unlock_pagedata()
    {
        $record = Request::input();
        $modiledata = Modules::getModuleById($record['mid']);

        $pagedata = DB::table($modiledata['varTableName'])
            ->select('*')
            ->where('id', '=', $record['id'])
            ->first();

        if (isset($pagedata->chrLock) && $pagedata->chrLock == 'N') {
            return '1';
        } else {
            DB::table($modiledata['varTableName'])
                ->where('id', $record['id'])
                ->update(['LockUserID' => '', 'chrLock' => 'N']);

            $lockdata = Config::get('Constant.UNLOCKED_RECORD');
            $logArr = MyLibrary::logData($record['id'], $record['mid'], $lockdata);
            if (isset($pagedata->varTitle) && !empty($pagedata->varTitle)) {
                $logArr['varTitle'] = stripslashes($pagedata->varTitle);
            } else {
                if (isset($pagedata->nxx) && !empty($pagedata->nxx)) {
                    $logArr['varTitle'] = stripslashes($pagedata->nxx);
                }
            }
            Log::recordLog($logArr);
            return '0';
        }
    }

    public function lock_pagedata()
    {
        $record = Request::input();
        $modiledata = Modules::getModuleById($record['mid']);
        $pagedata = DB::table($modiledata['varTableName'])
            ->select('*')
            ->where('id', '=', $record['id'])
            ->first();

        if (isset($pagedata->chrLock) && $pagedata->chrLock == 'Y') {
            return '1';
        } else {
            DB::table($modiledata['varTableName'])
                ->where('id', $record['id'])
                ->update(['LockUserID' => $record['uid'], 'chrLock' => 'Y']);
            $lockdata = Config::get('Constant.LOCKED_RECORD');
            $logArr = MyLibrary::logData($record['id'], $record['mid'], $lockdata);
            if (isset($pagedata->varTitle) && !empty($pagedata->varTitle)) {
                $logArr['varTitle'] = stripslashes($pagedata->varTitle);
            } else {
                if (isset($pagedata->nxx) && !empty($pagedata->nxx)) {
                    $logArr['varTitle'] = stripslashes($pagedata->nxx);
                }
            }

            Log::recordLog($logArr);
            return '0';
        }
    }

    public function Save_Data()
    {
        $record = Request::input();
        $modiledata = Modules::getModuleById($record['mid']);

        $genratedAlias = slug::create_slug($record['name']);
        $newGenratedAlias = $genratedAlias[0];
        $data = [
            'intFkModuleCode' => $record['mid'],
            'varAlias' => $newGenratedAlias,
            'chrIsPreview' => 'N'];
        $aliasid = Alias::insertGetId($data);
        $insertqueryArray = array();
        if (\Schema::hasColumn($modiledata['varTableName'], 'varTitle')) {
            $insertqueryArray['varTitle'] = $record['name'];
        }
        if (\Schema::hasColumn($modiledata['varTableName'], 'intAliasId')) {
            $insertqueryArray['intAliasId'] = $aliasid;
        }
        if (\Schema::hasColumn($modiledata['varTableName'], 'intFKModuleCode')) {
            $insertqueryArray['intFKModuleCode'] = $record['mid'];
        }
        if (\Schema::hasColumn($modiledata['varTableName'], 'chrMain')) {
            $insertqueryArray['chrMain'] = 'Y';
        }
        if (\Schema::hasColumn($modiledata['varTableName'], 'chrDraft')) {
            $insertqueryArray['chrDraft'] = 'D';
        }
        if (\Schema::hasColumn($modiledata['varTableName'], 'chrPublish')) {
            $insertqueryArray['chrPublish'] = 'N';
        }
        if (\Schema::hasColumn($modiledata['varTableName'], 'intDisplayOrder')) {
            $displayorderdata = DB::table($modiledata['varTableName'])
                ->select('*')
                ->where('chrDelete', 'N')
                ->orderBy('intDisplayOrder', 'desc')
                ->first();
            if (isset($displayorderdata->intDisplayOrder)) {
                $insertqueryArray['intDisplayOrder'] = $displayorderdata->intDisplayOrder + 1;
            }
        }
        if (!empty($insertqueryArray)) {
            $data = DB::table($modiledata['varTableName'])->insertGetId($insertqueryArray);
        }

//        if (\Schema::hasColumn($modiledata['varTableName'], 'varTitle')) {
        //            $data = DB::table($modiledata['varTableName'])->insertGetId(
        //                    ['varTitle' => $record['name'], 'intAliasId' => $aliasid, 'intFKModuleCode' => $record['mid'], 'chrMain' => 'Y', 'chrDraft' => 'D']
        //            );
        //        }

        $darftdata = Config::get('Constant.AUTO_SAVED_DARFT');
        $logArr = MyLibrary::logData($data, $record['mid'], $darftdata);
        $logArr['varTitle'] = $record['name'];
        Log::recordLog($logArr);
    }

    public function folderdata()
    {

        $record = Request::input();
        if ($record['type'] == 1) {
            $foldername = Image::getFolderType(1);
        } else if ($record['type'] == 2) {
            $foldername = Document::getFolderType(2);
        } else if ($record['type'] == 3) {
            $foldername = Audio::getFolderType(3);
        }

        if (count($foldername) > 0) {
            foreach ($foldername as $name) {
                if ($record['name'] != $name->foldername) {
                    $data = DB::table('folder')->insertGetId(
                        ['type' => $record['type'], 'foldername' => $record['name'], 'date' => date('Y-m-d H:i:s')]
                    );
                    if ($record['type'] == 1) {
                        if ($this->BUCKET_ENABLED) {
                            $path = $this->S3_MEDIA_BUCKET_PATH . '/assets/images/' . $record['name'];
                        } else {
                            $path = public_path('assets/images/' . $record['name']);
                        }

                    } else if ($record['type'] == 2) {

                        if ($this->BUCKET_ENABLED) {
                            $path = $this->S3_MEDIA_BUCKET_PATH . '/documents/' . $record['name'];
                        } else {
                            $path = public_path('documents/' . $record['name']);
                        }
                    } else if ($record['type'] == 3) {
                        if ($this->BUCKET_ENABLED) {
                            $path = $this->S3_MEDIA_BUCKET_PATH . '/audios/' . $record['name'];
                        } else {
                            $path = public_path('audios/' . $record['name']);
                        }
                    }
                    if (!File::isDirectory($path)) {
                        File::makeDirectory($path, 0777, true, true);
                    }
                    $html = '';
                    if ($record['type'] == 1) {
                        $html .= '<select class="form-control folderslection" onchange="Imagefolderselect(this.value)">';
                    } else if ($record['type'] == 2) {
                        $html .= '<select class="form-control folderslection" onchange="Documentfolderselect(this.value)">';
                    } else if ($record['type'] == 3) {
                        $html .= '<select class="form-control folderslection" onchange="Audiofolderselect(this.value)">';
                    }
                    if ($record['type'] == 1) {
                        $folderdata = Image::getFolderType(1);
                    } else if ($record['type'] == 2) {
                        $folderdata = Document::getFolderType(2);
                    } else if ($record['type'] == 3) {
                        $folderdata = Audio::getFolderType(3);
                    }
                    $html .= '<option value="0">All</option>';
                    foreach ($folderdata as $fdata) {
                        $html .= '<option value="' . $fdata->id . '">' . $fdata->foldername . '</option>';
                    }
                    $html .= '</select>';
                    return $html . '@@@' . $record['type'];
                } else {
                    return 0;
                }
            }
        } else {
            $data = DB::table('folder')->insertGetId(
                ['type' => $record['type'], 'foldername' => $record['name'], 'date' => date('Y-m-d H:i:s')]
            );
            if ($record['type'] == 1) {
                if ($this->BUCKET_ENABLED) {
                    $path = $this->S3_MEDIA_BUCKET_PATH . '/assets/images/' . $record['name'];
                } else {
                    $path = public_path('assets/images/' . $record['name']);
                }
            } else if ($record['type'] == 2) {
                if ($this->BUCKET_ENABLED) {
                    $path = $this->S3_MEDIA_BUCKET_PATH . '/documents/' . $record['name'];
                } else {
                    $path = public_path('documents/' . $record['name']);
                }
            } else if ($record['type'] == 3) {
                if ($this->BUCKET_ENABLED) {
                    $path = $this->S3_MEDIA_BUCKET_PATH . '/audios/' . $record['name'];
                } else {
                    $path = public_path('audios/' . $record['name']);
                }
            }
            if (!File::isDirectory($path)) {
                File::makeDirectory($path, 0777, true, true);
            }
            $html = '';
            if ($record['type'] == 1) {
                $html .= '<select class="form-control folderslection" onchange="Imagefolderselect(this.value)">';
            } else if ($record['type'] == 2) {
                $html .= '<select class="form-control folderslection" onchange="Documentfolderselect(this.value)">';
            } else if ($record['type'] == 3) {
                $html .= '<select class="form-control folderslection" onchange="Audiofolderselect(this.value)">';
            }
            if ($record['type'] == 1) {
                $folderdata = Image::getFolderType(1);
            } else if ($record['type'] == 2) {
                $folderdata = Document::getFolderType(2);
            } else if ($record['type'] == 3) {
                $folderdata = Audio::getFolderType(3);
            }
            $html .= '<option value="0">All</option>';
            foreach ($folderdata as $fdata) {
                $html .= '<option value="' . $fdata->id . '">' . $fdata->foldername . '</option>';
            }
            $html .= '</select>';
            return $html . '@@@' . $record['type'];
        }
    }

    public function GetFolderImages()
    {
        $html = '';
        $folder_data = DB::table('image')
            ->select('*')
            ->where('fk_folder', '=', $_REQUEST['fid'])
            ->where('chrPublish', '=', 'Y')
            ->where('chrDelete', '=', 'N')
            ->get();

        $i = 0;
        foreach ($folder_data as $fdata) {
            $folderdata = Image::getFolderName($fdata->fk_folder);

            if ($_REQUEST['imgIDs'] == $fdata->id) {
                $class = 'img-box-active';
                $iconclass = 'icon-check icons';
            } else {
                $class = '';
                $iconclass = '';
            }
            $img_path = Config::get('Constant.CDN_PATH') . '/assets/images/' . $folderdata->foldername . '/' . $fdata->txtImageName . '.' . $fdata->varImageExtension;
            $html .= "<div class=\"img-box contains_thumb " . $class . "\" id='media_" . $fdata->id . "' data-folder='" . $fdata->fk_folder . "' data-order='" . $i . "'>
                     <div class='thumbnail_container'>
                        <div class='thumbnail' id='media_image_" . $fdata->id . "' data-image_big_source='" . $img_path . "' data-image_title='" . $fdata->txtImgOriginalName . "'>
                           <a title='" . $fdata->txtImgOriginalName . "' href=\"javascript:void(0);\" onclick=\"MediaManager.selectImage('" . $fdata->id . "')\">
                           <img alt='" . $fdata->txtImgOriginalName . "' src='" . $img_path . "'>
                           <span class=\"icon-check\" aria-hidden=\"true\"></span>
                           </a>
                        </div>
                     </div>
                     <a class=\"right_check text_doc\" href=\"javascript:void(0)\"><i class=\"" . $iconclass . "\"></i></a>
                     <div class=\"img-btns\"><a href=\"javascript:;\" title=\"Image Detail\" onclick=\"MediaManager.getImageDetails('" . $fdata->id . "','" . $fdata->fk_folder . "');\"><span class=\"icon icon-info\"></span></a><a href=\"javascript:;\" title=\"Crop Image\" onclick=\"MediaManager.cropImage('" . $fdata->id . "','" . $fdata->fk_folder . "');\"><span class=\"icon-crop\"></span></a></div>
                  </div>";
            $i++;
        }

        echo $html;
        exit;
    }

    public function GetFolderAudio()
    {
        $html = '';
        $folder_data = DB::table('audios')
            ->select('*')
            ->where('fk_folder', '=', $_REQUEST['fid'])
            ->where('chrPublish', '=', 'Y')
            ->where('chrDelete', '=', 'N')
            ->get();

        $i = 0;
        foreach ($folder_data as $fdata) {
            $folderdata = Audio::getFolderName($fdata->fk_folder);

            if ($_REQUEST['imgIDs'] == $fdata->id) {
                $class = 'img-box-active';
                $iconclass = 'icon-check icons';
            } else {
                $class = '';
                $iconclass = '';
            }
            $audioUrl = Config::get('Constant.CDN_PATH') . '/audios/' . $folderdata->foldername . '/' . $fdata->txtSrcAudioName . '.' . $fdata->varAudioExtension;
            $html .= "<div data-audioext=\"" . $fdata->varAudioExtension . "\" data-audionm=\"" . $fdata->txtAudioName . "\" data-audiourl=\"" . $audioUrl . "\" class=\"img-box contains_thumb " . $class . "\" id=\"audio_" . $fdata->id . "\" style='margin-bottom: 60px;'>
    <div class=\"thumbnail_container\">
      <div class=\"thumbnail\">
         <a title=\"" . $fdata->txtAudioName . "\" href=\"javascript:void(0);\" onclick=\"MediaManager.selectAudio('" . $fdata->id . "')\">
            <audio id=\"myAudio_" . $fdata->id . "\">
               <source src=\"" . $audioUrl . "\" type=\"audio/mpeg\">
            </audio>
            <input type=\"hidden\" id=\"audioid\" value=\"" . $fdata->id . "\">";
            if (strtolower($fdata->varAudioExtension) == "mp3") {
                $html .= "<img alt='" . $fdata->txtAudioName . "' src='" . Config::get('Constant.CDN_PATH') . "assets/images/audios_logo/mp3.png'>";
            } elseif (strtolower($fdata->varAudioExtension) == "wav") {
                $html .= "<img alt='" . $fdata->txtAudioName . "' src='" . Config::get('Constant.CDN_PATH') . "assets/images/audios_logo/wav.png'>";
            } elseif (strtolower($fdata->varAudioExtension) == "mp4") {
                $html .= "<img alt='" . $fdata->txtAudioName . "' src='" . Config::get('Constant.CDN_PATH') . "assets/images/audios_logo/mp4.png'>";
            } else {
                $html .= "<img alt='" . $fdata->txtAudioName . "' src='" . Config::get('Constant.CDN_PATH') . "assets/images/audios_logo/audio_icon.png'>";
            }

            $html .= "<span class='icon-check' aria-hidden='true'></span></a>
                <div class=\"mp3_overlay\">
                   <span>
                      <a onclick=\"Playaudio('" . $fdata->id . "')\">
                         <div id=\"audiohtml_" . $fdata->id . "\"><i class=\"fa fa-play\" title=\"Play\"></i></div>
                      </a>
                   </span>
                </div>
             </div>
          </div>
          <div class=\"title-change\">
             <input class=\"form-control\" type=\"text\" name=\"audioname" . $fdata->id . "\" id=\"audioname_" . $fdata->id . "\" value=\"" . $fdata->txtAudioName . "\"><a onclick=\"MediaManager.GetUpdateAudioName('" . $fdata->id . "')\" href=\"javascript:void(0);\" class=\"btn\"><i class=\"ri-pencil-line\"></i></a>
          </div>
          <a class=\"right_check\" href=\"javascript:void(0)\"><i class=\"" . $iconclass . "\"></i></a></div>";
            $i++;
        }

        echo $html;
        exit;
    }

    public function GetFolderDocument()
    {
        $html = '';
        $folder_data = DB::table('documents')
            ->select('*')
            ->where('fk_folder', '=', $_REQUEST['fid'])
            ->where('chrPublish', '=', 'Y')
            ->where('chrDelete', '=', 'N')
            ->get();

        $i = 0;
        foreach ($folder_data as $fdata) {
            $folderdata = Document::getFolderName($fdata->fk_folder);
            $expload = explode(",", $_REQUEST['imgIDs']);
            if (in_array($fdata->id, $expload)) {
                $class = 'img-box-active';
                $iconclass = 'icon-check icons';
            } else {
                $class = '';
                $iconclass = '';
            }

            $docUrl = Config::get('Constant.CDN_PATH') . '/documents/' . $folderdata->foldername . '/' . $fdata->txtSrcDocumentName . '.' . $fdata->varDocumentExtension;
            $html .= "<div data-docext=\"" . $fdata->varDocumentExtension . "\" data-docnm=\"" . $fdata->txtDocumentName . "\" data-folder='" . $fdata->fk_folder . "' data-docurl=\"" . $docUrl . "\" class=\"img-box contains_thumb " . $class . "\" id=\"document_" . $fdata->id . "\">
                <div class=\"thumbnail_container\">
                   <div class=\"thumbnail\">
                      <a title=\"" . $fdata->txtDocumentName . "\" href=\"javascript:void(0);\" onclick=\"MediaManager.selectDocument('" . $fdata->id . "')\">";
            if (strtolower($fdata->varDocumentExtension) == "pdf") {
                $html .= "<img alt='" . $fdata->txtDocumentName . "' src='" . Config::get('Constant.CDN_PATH') . "assets/images/documents_logo/pdf.png'><span class=\"mob_doc\"><i data-bs-toggle='tooltip' data-bs-placement='top' class='ri-cellphone-line' title='View: " . $fdata->intMobileViewCount . "\nDownload: " . $fdata->intMobileDownloadCount . "'></i></span><span class=\"mob_desk\"><i data-bs-toggle='tooltip' data-bs-placement='top' class='ri-computer-line' title='View: " . $fdata->intDesktopViewCount . "\nDownload: " . $fdata->intDesktopDownloadCount . "'></i></span><span class=\"mob_copy\"><i class='ri-clipboard-line doc-copy' data-docurl=\"" . $docUrl . "\" data-bs-toggle='tooltip' data-bs-placement='top' title='Copy Link'></i></span>";
            } elseif (strtolower($fdata->varDocumentExtension) == "xls" || $fdata->varDocumentExtension == "xlsx" || $fdata->varDocumentExtension == "xlsm") {
                $html .= "<img alt='" . $fdata->txtDocumentName . "' src='" . Config::get('Constant.CDN_PATH') . "assets/images/documents_logo/xls.png'><span class=\"mob_doc\"><i data-bs-toggle='tooltip' data-bs-placement='top' class='ri-cellphone-line' title='View: " . $fdata->intMobileViewCount . "\nDownload: " . $fdata->intMobileDownloadCount . "'></i></span><span class=\"mob_desk\"><i data-bs-toggle='tooltip' data-bs-placement='top' class='ri-computer-line' title='View: " . $fdata->intDesktopViewCount . "\nDownload: " . $fdata->intDesktopDownloadCount . "'></i></span><span class=\"mob_copy\"><i class='ri-clipboard-line doc-copy' data-docurl=\"" . $docUrl . "\" data-bs-toggle='tooltip' data-bs-placement='top' title='Copy Link'></i></span>";
            } elseif (strtolower($fdata->varDocumentExtension) == "docx" || strtolower($fdata->varDocumentExtension) == "doc") {
                $html .= "<img alt='" . $fdata->txtDocumentName . "' src='" . Config::get('Constant.CDN_PATH') . "assets/images/documents_logo/doc.png'><span class=\"mob_doc\"><i data-bs-toggle='tooltip' data-bs-placement='top' class='ri-cellphone-line' title='View: " . $fdata->intMobileViewCount . "\nDownload: " . $fdata->intMobileDownloadCount . "'></i></span><span class=\"mob_desk\"><i data-bs-toggle='tooltip' data-bs-placement='top' class='ri-computer-line' title='View: " . $fdata->intDesktopViewCount . "\nDownload: " . $fdata->intDesktopDownloadCount . "'></i></span><span class=\"mob_copy\"><i class='ri-clipboard-line doc-copy' data-docurl=\"" . $docUrl . "\" data-bs-toggle='tooltip' data-bs-placement='top' title='Copy Link'></i></span>";
            } elseif (strtolower($fdata->varDocumentExtension) == "ppt") {
                $html .= "<img alt='" . $fdata->txtDocumentName . "' src='" . Config::get('Constant.CDN_PATH') . "assets/images/documents_logo/ppt.png'><span class=\"mob_doc\"><i data-bs-toggle='tooltip' data-bs-placement='top' class='ri-cellphone-line' title='View: " . $fdata->intMobileViewCount . "\nDownload: " . $fdata->intMobileDownloadCount . "'></i></span><span class=\"mob_desk\"><i data-bs-toggle='tooltip' data-bs-placement='top' class='ri-computer-line' title='View: " . $fdata->intDesktopViewCount . "\nDownload: " . $fdata->intDesktopDownloadCount . "'></i></span><span class=\"mob_copy\"><i class='ri-clipboard-line doc-copy' data-docurl=\"" . $docUrl . "\" data-bs-toggle='tooltip' data-bs-placement='top' title='Copy Link'></i></span>";
            } elseif (strtolower($fdata->varDocumentExtension) == "txt") {
                $html .= "<img alt='" . $fdata->txtDocumentName . "' src='" . Config::get('Constant.CDN_PATH') . "assets/images/documents_logo/txt.png'><span class=\"mob_doc\"><i data-bs-toggle='tooltip' data-bs-placement='top' class='ri-cellphone-line' title='View: " . $fdata->intMobileViewCount . "\nDownload: " . $fdata->intMobileDownloadCount . "'></i></span><span class=\"mob_desk\"><i data-bs-toggle='tooltip' data-bs-placement='top' class='ri-computer-line' title='View: " . $fdata->intDesktopViewCount . "\nDownload: " . $fdata->intDesktopDownloadCount . "'></i></span><span class=\"mob_copy\"><i class='ri-clipboard-line doc-copy' data-docurl=\"" . $docUrl . "\" data-bs-toggle='tooltip' data-bs-placement='top' title='Copy Link'></i></span>";
            } else {
                $html .= "<img alt='" . $fdata->txtDocumentName . "' src='" . Config::get('Constant.CDN_PATH') . "assets/images/documents_logo/document_icon.png'><span class=\"mob_doc\"><i data-bs-toggle='tooltip' data-bs-placement='top' class='ri-cellphone-line' title='View: " . $fdata->intMobileViewCount . "\nDownload: " . $fdata->intMobileDownloadCount . "'></i></span><span class=\"mob_desk\"><i data-bs-toggle='tooltip' data-bs-placement='top' class='ri-computer-line' title='View: " . $fdata->intDesktopViewCount . "\nDownload: " . $fdata->intDesktopDownloadCount . "'></i></span><span class=\"mob_copy\"><i class='ri-clipboard-line doc-copy' data-docurl=\"" . $docUrl . "\" data-bs-toggle='tooltip' data-bs-placement='top' title='Copy Link'></i></span>";
            }
            $html .= "<span class=\"icon-check\" aria-hidden=\"true\"></span>
                      </a>
                   </div>
                </div>
                <div class=\"title-change\">
                   <input class=\"form-control\" type=\"text\" name=\"documentname" . $fdata->id . "\" id=\"documentname_" . $fdata->id . "\" value=\"" . $fdata->txtDocumentName . "\"><a onclick=\"MediaManager.GetUpdateDocumentName('" . $fdata->id . "')\" href=\"javascript:void(0);\" class=\"btn\"><i class=\"ri-pencil-line\"></i></a>
                </div>
                <a class=\"right_check\" href=\"javascript:void(0)\"><i class=\"" . $iconclass . "\"></i></a>
             </div>";
            $i++;
        }
        echo $html;
        exit;
    }

    public function assignAllPermissonToSuperAdmin()
    {

        $permissionObj = Permission::get();
        if ($permissionObj->count() > 0) {
            DB::table('role_has_permissions')->where('role_id', 1)->delete();
            foreach ($permissionObj as $key => $value) {
                $fields = array();
                $fields['permission_id'] = $value->id;
                $fields['role_id'] = 1;
                DB::table('role_has_permissions')->insertGetId($fields);
            }
        }
    }

    public function uploadImage()
    {

        $response = array();

        $file = Request::file('upload');
        $timestamp = date('YmdHis');
        $pathinfo = pathinfo($file->getClientOriginalName());
        $extension = $pathinfo['extension'];
        $sourceFilePath = $file->getPathName();
        $name = self::clean($pathinfo['filename']) . '-' . $timestamp;
        $storeFileName = $name . '.' . $pathinfo['extension'];

        if ($this->BUCKET_ENABLED) {
            Aws_File_helper::putObject($sourceFilePath, $this->S3_MEDIA_BUCKET_PATH . '/', $storeFileName);
            $filePath = $AWSContants['S3_MEDIA_BUCKET_PATH'] . '/' . $name . '.' . $extension;
            if (Mylibrary::filePathExist($filePath)) {
                $response['url'] = $_APP_URL . $AWSContants['S3_MEDIA_BUCKET_PATH'] . '/' . $name . '.' . $extension;
            }
        } else {
            $file->move(Config::get('Constant.LOCAL_CDN_PATH') . '/assets/images/ckeditor/', $storeFileName);
            $response['url'] = env('CDN_URL') . '/assets/images/ckeditor/' . $name . '.' . $extension;
        }
        return json_encode($response);
    }

    public static function clean($string)
    {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
        return preg_replace('/[^A-Za-z0-9\-.]/', '', $string); // Removes special chars.
    }

}
