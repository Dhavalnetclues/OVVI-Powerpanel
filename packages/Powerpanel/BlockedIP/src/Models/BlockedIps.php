<?php

namespace Powerpanel\BlockedIP\Models;

use Illuminate\Database\Eloquent\Model;
use Config;
use DB;

class BlockedIps extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'blocked_ips';
    protected $fillable = [
        'id',
        'varIpAddress',
        'varEmail',
        'txtBrowserInf',
        'varCountry_name',
        'varCountry_flag',
        'varUrl',
        'varNewUrl',
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
            'varEmail',
            'txtBrowserInf',
            'varCountry_name',
            'varCountry_flag',
            'varUrl',
            'varNewUrl',
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
            'varEmail',
            'txtBrowserInf',
            'varCountry_name',
            'varCountry_flag',
            'varUrl',
            'varNewUrl',
            'created_at',
            'updated_at'
        ];
        $response = Self::select($moduleFields);
        if ($filterArr) {
            $response = $response->filter($filterArr);
        }
        $response = $response
                ->groupBy('varIpAddress')
//                ->havingRaw('COUNT(*) >= 5')
                ->orderBy('created_at', 'DESC')
                ->get();
        return $response;
    }
  public static function getRecordIpList($ipaddress = false) {
      
        $moduleFields = ['id', 'varIpAddress','varUrl','varNewUrl'];
        $response = false;
        $response = Self::Select($moduleFields)
                  ->where('varIpAddress', $ipaddress);
        
        $response = $response->orderBy('created_at')
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
        $ip = self::where('varIpAddress', '=', $ip)->count();
        return $ip;
    }

    public static function getRecordCount($filterArr = false, $returnCounter = false) {
        $response = 0;
        $cmsPageFields = ['id'];
        $pageQuery = Self::getPowerPanelRecords($cmsPageFields);
        if ($filterArr != false) {
            $pageQuery = $pageQuery->filter($filterArr, $returnCounter);
        }
        $response = $pageQuery->count();
        return $response;
    }

    public static function getPowerPanelRecords($moduleFields = false) {
        $data = [];
        $response = false;
        $response = self::select($moduleFields);
        if (count($data) > 0) {
            $response = $response->with($data);
        }
        return $response;
    }

//    static function getRecordCountByIpandTime($ip) {
//        $Time = Config::get('Constant.RETRY_TIME_PERIOD');
//        $current_time = date("Y-m-d H:i:s");
//        $time = strtotime($current_time);
//        $time = $time - ($Time * 60);
//        $minus_time = date("Y-m-d H:i:s", $time);
//        $res = self::where('varIpAddress', $ip)->where('created_at', '<', $minus_time)->delete();
//        $ip = self::where('varIpAddress', '=', $ip)->count();
//        return $ip;
//    }

    public static function deleteRecordsPermanent($data = false) {
        $ips = Self::select('varIpAddress')
                ->whereIn('id', $data)
                ->groupBy('varIpAddress')
                ->get()
                ->pluck('varIpAddress')
                ->toArray();
        Self::whereIn('varIpAddress', $ips)->delete();
    }

    function scopeFilter($query, $filterArr = false, $retunTotalRecords = false) {
        $response = null;
        if ($filterArr['orderByFieldName'] != null && $filterArr['orderTypeAscOrDesc'] != null) {
            $query = $query->orderBy($filterArr['orderByFieldName'], $filterArr['orderTypeAscOrDesc']);
        } else {
            $query = $query->orderBy('created_at', 'DESC');
        }
        if (!$retunTotalRecords) {
            if (!empty($filterArr['iDisplayLength']) && $filterArr['iDisplayLength'] > 0) {
                $data = $query->skip($filterArr['iDisplayStart'])->take($filterArr['iDisplayLength']);
            }
        }
        if (!empty($filterArr['searchFilter']) && $filterArr['searchFilter'] != ' ') {
            $data = $query->where('varIpAddress', 'like', "%" . $filterArr['searchFilter'] . "%")
                    ->orWhere('txtBrowserInf', 'like', "%" . $filterArr['searchFilter'] . "%")
                    ->orWhere('created_at', 'like', "%" . $filterArr['searchFilter'] . "%");
        }
        if (!empty($query)) {
            $response = $query;
        }
        return $response;
    }

}
