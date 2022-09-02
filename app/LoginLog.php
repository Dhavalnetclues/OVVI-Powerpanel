<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\CommonModel;
use DB;
use App\Helpers\MyLibrary;
use Session;
use Jenssegers\Agent\Agent;

class LoginLog extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'login_history';
    protected $fillable = [
        'id',
        'fkIntUserId',
        'varIpAddress',
        'varBrowser_Name',
        'varBrowser_Version',
        'varBrowser_Platform',
        'varDevice',
        'chrActive',
        'created_at',
        'updated_at'
    ];

    /**
     * This method handels delete scope
     * @return  Object
     * @since   2017-10-14
     * @author  NetQuick
     */
    function scopeDeleted($query) {
        return $query->where(['login_history.chrDelete' => 'N']);
    }

    /**
     * This method handels retrival of event records
     * @return  Object
     * @since   2017-10-14
     * @author  NetQuick
     */
    static function getRecords() {

        /* return self::with([
          'user' => function ($query) use ($searchVal) {
          $query->where('users.email','like','%'.$searchVal.'%')
          ->orWhere('users.name','like','%'.$searchVal.'%');
          }]); */
        $moduleFields = [
            'login_history.id as id',
            'login_history.fkIntUserId as fkIntUserId',
            'login_history.varIpAddress as varIpAddress',
            'login_history.varCity as varCity',
            'login_history.varState_prov as varState_prov',
            'login_history.varCountry_flag as varCountry_flag',
            'login_history.varCountry_name as varCountry_name',
            'login_history.varBrowser_Name as varBrowser_Name',
            'login_history.varBrowser_Version as varBrowser_Version',
            'login_history.varBrowser_Platform as varBrowser_Platform',
            'login_history.varDevice as varDevice',
            'login_history.chrActive as chrActive',
            'login_history.created_at as created_at',
            'login_history.updated_at as updated_at'
        ];
        return self::select($moduleFields);
    }

    // public static function getRecordsUser() {
    //   return  Self::getRecords()
    // 		    	->where('fkIntUserId','=','4')
    // 			    ->deleted()
    // 			    ->publish()
    // 			    ->get();
    // }


    public static function deleteRecordsPermanent($data = false) {
        self::whereIn('id', $data)->delete();
    }

    /**
     * This method handels alias relation
     * @return  Object
     * @since   2016-10-14
     * @author  NetQuick
     */
    public function user() {
        return $this->belongsTo('App\User', 'fkIntUserId', 'id');
    }

    // public function getPermissions(){
    // 	self::where('id',$data)->delete();
    // }

    /**
     * This method handels filter scope
     * @return  Object
     * @since   2017-08-02
     * @author  NetQuick
     */
    function scopeFilter($query, $filterArr = false, $retunTotalRecords = false) {
        $response = null;
        if (!empty($filterArr['orderByFieldName']) && !empty($filterArr['orderTypeAscOrDesc'])) {
            $query = $query->orderBy('login_history.' . $filterArr['orderByFieldName'], $filterArr['orderTypeAscOrDesc']);
        } else {
            $query = $query->orderBy('login_history.id', 'DESC');
        }


        if (isset($filterArr['searchFilter']) && !empty($filterArr['searchFilter']) && $filterArr['searchFilter'] != ' ') {
            $query = $query->leftJoin('users', 'users.id', '=', 'login_history.fkIntUserId');
            /* $data = $query->where('users.email', 'like', '%' . $filterArr['searchFilter'] . '%')
              ->orWhere('users.name', 'like', '%' . $filterArr['searchFilter'] . '%'); */
            $data = $query->where('users.name', 'like', '%' . $filterArr['searchFilter'] . '%');
        }

        if (!$retunTotalRecords) {
            if (!empty($filterArr['iDisplayLength']) && $filterArr['iDisplayLength'] > 0) {
                $data = $query->skip($filterArr['iDisplayStart'])->take($filterArr['iDisplayLength']);
            }
        }
        if (!empty($filterArr['statusFilter']) && $filterArr['statusFilter'] != ' ') {
            $data = $query->where('varIpAddress', $filterArr['statusFilter']);
        }

        if (!empty($query)) {
            $response = $query;
        }

        return $response;
    }

    public static function getRecordCount($user = false, $ip = false, $browser = false, $platform = false, $device = false) {
        $user = DB::table('login_history')->select('id')
                ->where('fkIntUserId', $user)
                ->where('varIpAddress', $ip)
                ->where('varBrowser_Name', $browser)
                ->where('varBrowser_Platform', $platform)
                ->where('varDevice', $device)
                ->where('chrDelete', 'N')
                ->where('chrActive', 'Y')
                ->count();
        return $user;
    }

    public static function getRecordbyId($log_id = false) {
        return Self::getRecords()
                        ->where('id', '=', $log_id)
                        ->deleted()
                        ->first();
    }

    public static function getRecent_securityRecordbyId($userid = false) {
        return Self::getRecords()
                        ->where('fkIntUserId', '=', $userid)
                        ->orderBy('id', 'DESC')
                        ->deleted()
                        ->first();
    }

    public static function getcurrently_DevicesUser($userid = false) {
        $date = date("Y-m-d");
        return Self::getRecords()
                        ->where('fkIntUserId', '=', $userid)
                        ->whereRaw('created_at = updated_at')
                        ->whereDate('created_at', '=', $date)
                        ->orderBy('id', 'DESC')
                        ->take(5)
                        ->deleted()
                        ->get();
    }

    public static function getprevious_DevicesUser($userid = false) {
        $date = date("Y-m-d");
        return Self::getRecords()
                        ->where('fkIntUserId', '=', $userid)
//                        ->whereRaw('created_at != updated_at')
                        ->whereDate('created_at', '!=', $date)
                        ->orderBy('id', 'DESC')
                        ->take(5)
                        ->deleted()
                        ->get();
    }

    public static function getSecurity_NewIp_Device_Bro_Count() {
        $agent = new Agent;
        if ($agent->isMobile()) {
            $device = $agent->device();
        } else {
            $device = 'Desktop';
        }
        $browser = $agent->browser();
        $user = auth()->user()->id;
        $ip = MyLibrary::get_client_ip();
        $user = DB::table('login_history')->select('id')
                ->where('fkIntUserId', $user)
                ->where('varIpAddress', $ip)
                ->where('varDevice', $device)
                ->where('varBrowser_Name', $browser)
                ->where('chrDelete', 'N')
                ->where('chrActive', 'Y')
                ->count();
        return $user;
    }

    public static function getSecurity_NewIp_Device_Count() {
        $agent = new Agent;
        if ($agent->isMobile()) {
            $device = $agent->device();
        } else {
            $device = 'Desktop';
        }
        $user = auth()->user()->id;
        $ip = MyLibrary::get_client_ip();
        $user = DB::table('login_history')->select('id')
                ->where('fkIntUserId', $user)
                ->where('varIpAddress', $ip)
                ->where('varDevice', $device)
                ->where('chrDelete', 'N')
                ->where('chrActive', 'Y')
                ->count();
        return $user;
    }

    public static function getSecurity_NewIp_Count() {
        $user = auth()->user()->id;
        $ip = MyLibrary::get_client_ip();
        $user = DB::table('login_history')->select('id')
                ->where('fkIntUserId', $user)
                ->where('varIpAddress', $ip)
                ->where('chrDelete', 'N')
                ->where('chrActive', 'Y')
                ->count();
        return $user;
    }

    public static function getLoginHistryData($id) {
        $response = false;
        $userid = auth()->user()->id;
        $moduleFields = [
            'id',
            'fkIntUserId',
            'varIpAddress',
            'varBrowser_Name',
            'varBrowser_Version',
            'varBrowser_Platform',
            'varDevice',
            'chrActive',
            'chrIsLoggedOut',
            'created_at',
            'updated_at'
        ];
        $response = Self::select($moduleFields)
                ->deleted()
                ->where('fkIntUserId', '=', $id)
                ->where('chrIsLoggedOut', '=', 'N')
                ->orderBy('id', 'desc')
                ->first();
        return $response;
    }

}
