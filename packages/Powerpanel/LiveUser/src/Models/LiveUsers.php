<?php

namespace Powerpanel\LiveUser\Models;

use Illuminate\Database\Eloquent\Model;
use App\CommonModel;
use Config;
use DB;
use Carbon\Carbon;
use App\Helpers\MyLibrary;

class LiveUsers extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'live_user';
    protected $fillable = [
        'id',
        'varIpAddress',
        'varContinent_code',
        'varContinent_name',
        'varCountry_code2',
        'varCountry_code3',
        'varCountry_name',
        'varCountry_capital',
        'varState_prov',
        'varDistrict',
        'varCity',
        'varZipcode',
        'varLatitude',
        'varLongitude',
        'varIs_eu',
        'varCalling_code',
        'varCountry_tld',
        'varLanguages',
        'varCountry_flag',
        'varGeoname_id',
        'varIsp',
        'varConnection_type',
        'varOrganization',
        'varCurrencyCode',
        'varCurrencyName',
        'varCurrencySymbol',
        'varTime_zoneName',
        'varTime_zoneOffset',
        'varTime_zoneCurrent_time',
        'varTime_zoneCurrent_time_unix',
        'varTime_zoneIs_dst',
        'varTime_zoneDst_savings',
        'txtBrowserInf',
        'ChrBlock',
        'created_at',
        'updated_at'
    ];

    /**
     * This method handels retrival of event records
     * @return  Object
     * @since   2017-10-14
     * @author  NetQuick
     */
    static function getRecords() {
        $moduleFields = [
            'id',
            'varIpAddress',
            'varContinent_code',
            'varContinent_name',
            'varCountry_code2',
            'varCountry_code3',
            'varCountry_name',
            'varCountry_capital',
            'varState_prov',
            'varDistrict',
            'varCity',
            'varZipcode',
            'varLatitude',
            'varLongitude',
            'varIs_eu',
            'varCalling_code',
            'varCountry_tld',
            'varLanguages',
            'varCountry_flag',
            'varGeoname_id',
            'varIsp',
            'varConnection_type',
            'varOrganization',
            'varCurrencyCode',
            'varCurrencyName',
            'varCurrencySymbol',
            'varTime_zoneName',
            'varTime_zoneOffset',
            'varTime_zoneCurrent_time',
            'varTime_zoneCurrent_time_unix',
            'varTime_zoneIs_dst',
            'varTime_zoneDst_savings',
            'txtBrowserInf',
            'ChrBlock',
            'created_at',
            'updated_at'
        ];
        return self::select($moduleFields)->get();
    }

    static function getRecordList($filterArr = false) {
        $response = false;
        $moduleFields = [
            'id',
            'varIpAddress',
            'varContinent_code',
            'varContinent_name',
            'varCountry_code2',
            'varCountry_code3',
            'varCountry_name',
            'varCountry_capital',
            'varState_prov',
            'varDistrict',
            'varCity',
            'varZipcode',
            'varLatitude',
            'varLongitude',
            'varIs_eu',
            'varCalling_code',
            'varCountry_tld',
            'varLanguages',
            'varCountry_flag',
            'varGeoname_id',
            'varIsp',
            'varConnection_type',
            'varOrganization',
            'varCurrencyCode',
            'varCurrencyName',
            'varCurrencySymbol',
            'varTime_zoneName',
            'varTime_zoneOffset',
            'varTime_zoneCurrent_time',
            'varTime_zoneCurrent_time_unix',
            'varTime_zoneIs_dst',
            'varTime_zoneDst_savings',
            'txtBrowserInf',
            'ChrBlock',
            'created_at',
            'updated_at'
        ];
        $response = Self::select($moduleFields);
        if ($filterArr) {
            $response = $response->filter($filterArr);
        }
        $response = $response
                //->groupBy('varIpAddress')
                ->get();
        return $response;
    }

    static function getRecordWithCountry() {
        $moduleFields = [
            'id',
            'varCountry_name'
        ];
        $response = false;
        $response = Self::select($moduleFields)
                ->groupBy('varCountry_name')
                ->orderBy('updated_at', 'DESC')
                ->get();
        return $response;
    }
    static function addRecord($data) {
        $user = self::insertGetId($data);
        return $user;
    }

    static function updateRecordByIp($ip, $data) {
        $user = self::where('varIpAddress', '=', $ip)->update($data);
        return $user;
    }

    static function getRecordCountByIp($ip) {
        $ip = self::where('varIpAddress', '=', $ip)->where('ChrBlock', '=', 'Y')->count();
        return $ip;
    }

    static function getRecordCountByIp_insert($ip) {
        $ip = self::where('varIpAddress', '=', $ip)->count();
        return $ip;
    }

    public static function deleteRecordsPermanent($data = false) {
        $ips = Self::select('varIpAddress')
                ->whereIn('id', $data)
                ->groupBy('varIpAddress')
                ->get()
                ->pluck('varIpAddress')
                ->toArray();
        Self::whereIn('varIpAddress', $ips)->delete();
    }

    public static function blockRecordsPermanent($data = false) {
        Self::whereIn('id', $data)->update(['ChrBlock' => 'Y']);
    }

    function scopeOrderByCreatedAtDesc($query) {
        return $query->orderBy('created_at','DESC');
    }

    static function getPowerPanelRecords( $moduleFields=false ) 
    {     
        $data=[];
        $response = false;
        $response=self::select($moduleFields);
        if(count($data)>0){
            $response = $response->with($data);
        }             
        return $response;
    }

    public static function getListForExport($selectedIds=false, $county = false, $startDate=false, $endDate=false){
        $response = false;
        $moduleFields=[ 'varIpAddress', 'varContinent_code', 'varContinent_name', 'varCountry_code2', 'varCountry_code3', 'varCountry_name','varCountry_capital', 'varState_prov', 'varDistrict', 'varCity', 'varZipcode', 'varLatitude', 'varLongitude', 'varIs_eu', 'varCalling_code',
        'varCountry_tld', 'varLanguages', 'varCountry_flag', 'varGeoname_id', 'varIsp', 'varConnection_type', 'varOrganization', 'varCurrencyCode',
        'varCurrencyName', 'varCurrencySymbol', 'varTime_zoneName', 'varTime_zoneOffset', 'varTime_zoneCurrent_time','varTime_zoneCurrent_time_unix', 'varTime_zoneIs_dst', 'varTime_zoneDst_savings', 'txtBrowserInf', 'ChrBlock', 'created_at',];
        $query = Self::getPowerPanelRecords($moduleFields);
        if(!empty($selectedIds) && count($selectedIds) > 0){
          $query->checkMultipleRecordId($selectedIds);
        }
        if(!empty($county)) {
            $query->checkCountry($county);
        }
        if(!empty($startDate) || !empty($endDate)) {
            $query->checkDateRange($startDate, $endDate);
        } 
        // else if (!empty($startDate)) {
        //     $query->checkStartDate($startDate);
        // } else if (!empty($endDate)) {
        //     $query->checkEndDate($endDate);
        // }
        $response = $query->orderByCreatedAtDesc()->get();
        return $response;
      }

      function scopeCheckMultipleRecordId($query,$Ids) 
      {
        return $query->whereIn('id',$Ids);
      }

    //   function scopeCheckDateRange($query, $startDate, $endDate) 
    //   {
    //     if (!empty($startDate) && $startDate != ' ') {
    //         $query->whereRaw('DATE(updated_at) >= DATE("' . date('Y-m-d', strtotime(str_replace('/', '-', $startDate))) . '")');
    //     }

    //     if (!empty($startDate) && $startDate != '' &&  empty($endDate) && $endDate == '') {
    //         $query->whereRaw('DATE(updated_at) = DATE("' . date('Y-m-d', strtotime(str_replace('/', '-', $startDate))) . '")');
    //     }

    //     if (!empty($endDate) && $endDate != ' ') {
    //         $query->whereRaw('DATE(updated_at) <= DATE("' . date('Y-m-d', strtotime(str_replace('/', '-', $endDate))) . '") AND updated_at IS NOT null');
    //     }

    //     return $query;
    //   }

    //   function scopeCheckStartDate($query,$startDate) 
    //   {
    //     return $query->whereDate('created_at', '>=', date('Y-m-d', strtotime(str_replace('/', '-', $startDate))));
    //   }
    //   function scopeCheckEndDate($query,$endDate) 
    //   {
    //     return $query->whereDate('created_at', '<=', date('Y-m-d', strtotime(str_replace('/', '-', $endDate))));
    //   }

      function scopeCheckCountry($query,$county) 
      {
        return $query->where('varCountry_name',$county);
      }

    function scopeFilter($query, $filterArr = false, $retunTotalRecords = false) {
        $response = null;
        if ($filterArr['orderByFieldName'] != null && $filterArr['orderTypeAscOrDesc'] != null) {
            $query = $query->orderBy($filterArr['orderByFieldName'], $filterArr['orderTypeAscOrDesc']);
        } else {
            $query = $query->orderBy('updated_at', 'DESC');
        }
        if (!$retunTotalRecords) {
            if (!empty($filterArr['iDisplayLength']) && $filterArr['iDisplayLength'] > 0) {
                $data = $query->skip($filterArr['iDisplayStart'])->take($filterArr['iDisplayLength']);
            }
        }
        if (!empty($filterArr['searchFilter']) && $filterArr['searchFilter'] != ' ') {
            $data = $query->where('varIpAddress', 'like', "%" . $filterArr['searchFilter'] . "%")
                    ->orWhere('txtBrowserInf', 'like', "%" . $filterArr['searchFilter'] . "%")
                    ->orWhere('updated_at', 'like', "%" . $filterArr['searchFilter'] . "%");
        }
        if (!empty($filterArr['CounFilter']) && $filterArr['CounFilter'] != '') {
            $data = $query->where('varCountry_name', '=', $filterArr['CounFilter']);
        }
        if (!empty($filterArr['Before15Day']) && $filterArr['Before15Day'] != '' && $filterArr['Before15Day'] != 'N') {
            $data = $query->where('updated_at','>=',Carbon::now()->subdays(10));
//            $data = $query->whereDate('updated_at', Carbon::now()->subDays(15));
        }

        if (!empty($filterArr['start']) && $filterArr['start'] != ' ') {
            $data = $query->whereRaw('DATE(updated_at) >= DATE("' . date('Y-m-d', strtotime(str_replace('/', '-', $filterArr['start']))) . '")');
        }

        if (!empty($filterArr['start']) && $filterArr['start'] != '' &&  empty($filterArr['end']) && $filterArr['end'] == '') {
                $data = $query->whereRaw('DATE(updated_at) = DATE("' . date('Y-m-d', strtotime(str_replace('/', '-', $filterArr['start']))) . '")');
        }

        if (!empty($filterArr['end']) && $filterArr['end'] != ' ') {
                $data = $query->whereRaw('DATE(updated_at) <= DATE("' . date('Y-m-d', strtotime(str_replace('/', '-', $filterArr['end']))) . '") AND updated_at IS NOT null');
        }
    
        if (!empty($query)) {
            $response = $query;
        }
        return $response;
    }

}
