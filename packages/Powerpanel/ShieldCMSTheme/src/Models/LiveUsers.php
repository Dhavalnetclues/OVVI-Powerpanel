<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\CommonModel;
use Config;
use DB;
use Carbon\Carbon;

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
        if (!empty($query)) {
            $response = $query;
        }
        return $response;
    }

}
