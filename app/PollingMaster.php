<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class PollingMaster extends Model {

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'polling_master_lead';
    protected $fillable = [
        'id',
        'fkIntPollingId',
        'FkCookies',
        'varAns',
        'fkIntPollingCatId',
        'created_at',
        'updated_at'
    ];

    public static function getCurrentMonthCount() {
        $response = false;
        $response = Self::getRecords()
                ->whereRaw('MONTH(created_at) = MONTH(CURRENT_DATE())')
                ->whereRaw('YEAR(created_at) = YEAR(CURRENT_DATE())')
                ->where('chrPublish', '=', 'Y')
                ->where('chrDelete', '=', 'N')
                ->count();
        return $response;
    }

    public static function CountYes($id = '') {
        $response = false;
        $response = Self::getRecords()
                ->where('fkIntPollingId', '=', $id)
                ->where('varAns', '=', 'Y')
                ->where('chrPublish', '=', 'Y')
                ->where('chrDelete', '=', 'N')
                ->count();
        return $response;
    }

    public static function CountNo($id = '') {
        $response = false;
        $response = Self::getRecords()
                ->where('fkIntPollingId', '=', $id)
                ->where('varAns', '=', 'N')
                ->where('chrPublish', '=', 'Y')
                ->where('chrDelete', '=', 'N')
                ->count();
        return $response;
    }

    public static function getPollingCookiesData($id = '') {
        $response = false;
        $response = Self::getRecords()
                ->where('FkCookies', '=', $id)
                ->where('chrPublish', '=', 'Y')
                ->where('chrDelete', '=', 'N')
                ->count();
        return $response;
    }

    public static function getCurrentYearCount() {
        $response = false;
        $response = Self::getRecords()
                ->whereRaw('YEAR(created_at) = YEAR(CURRENT_DATE())')
                ->where('chrPublish', '=', 'Y')
                ->where('chrDelete', '=', 'N')
                ->count();
        return $response;
    }

    /**
     * This method handels retrival of event records
     * @return  Object
     * @since   2017-08-02
     * @author  NetQuick
     */
    static function getRecords() {
        return self::with([]);
    }

    /**
     * This method handels retrival of record count
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getRecordById($id, $moduleFields = false) {
        $response = false;
        $moduleFields = [
            'id',
            'fkIntPollingId',
            'FkCookies',
            'fkIntPollingCatId',
            'varAns',
            'created_at',
            'updated_at'
        ];
        $response = Self::getPowerPanelRecords($moduleFields)->deleted()->checkRecordId($id)->first();
        return $response;
    }

    /**
     * This method handels backend records
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    static function getPowerPanelRecords($moduleFields = false) {
        $data = [];
        $response = false;
        $response = self::select($moduleFields);
        if (count($data) > 0) {
            $response = $response->with($data);
        }
        return $response;
    }

    /**
     * This method handels retrival of backend record list
     * @return  Object
     * @since   2017-10-24
     * @author  NetQuick
     */
    public static function getRecordList($filterArr = false) {
        $response = false;
        $moduleFields = [
            'id',
            'fkIntPollingId',
            'fkIntPollingCatId',
            'FkCookies',
            'varAns',
            'created_at',
            'chrPublish'
        ];
        $response = Self::getPowerPanelRecords($moduleFields)
                ->deleted()
                ->filter($filterArr)
                ->get();
        return $response;
    }

    public static function getRecordListDashboard($year = false, $timeparam = false, $month = false) {
        $response = false;
        $response = Self::select('id');
        $response = $response->where('chrPublish', '=', 'Y')->where('chrDelete', '=', 'N');
        if ($timeparam != 'month') {
            $response = $response->whereRaw("YEAR(created_at) = " . (int) $year . "")->count();
        } else {
            $response = $response->whereRaw("YEAR(created_at) = " . (int) $year . "")->whereRaw("MONTH(created_at) = " . (int) $month . "")->count();
        }
        return $response;
    }

    /**
     * This method handels retrival of backend record list
     * @return  Object
     * @since   2017-10-24
     * @author  NetQuick
     */
    public static function getCronRecords() {
        $response = false;
        $moduleFields = [
            'id',
            'fkIntPollingId',
            'fkIntPollingCatId',
            'FkCookies',
            'varAns',
            'created_at'
        ];
        $response = Self::getPowerPanelRecords($moduleFields)
                ->deleted()
                ->publish()
                ->get();
        return $response;
    }

    /**
     * This method handels retrival of backend record list for Export
     * @return  Object
     * @since   2017-10-24
     * @author  NetQuick
     */
    public static function getListForExport($selectedIds = false) {
        $response = false;
        $moduleFields = [
            'fkIntPollingId',
            'fkIntPollingCatId',
            'FkCookies',
            'varAns',
            'varIpAddress',
            'created_at'
        ];
        $query = Self::getPowerPanelRecords($moduleFields)->deleted();
        if (!empty($selectedIds) && count($selectedIds) > 0) {
            $query->checkMultipleRecordId($selectedIds);
        }
        $response = $query->orderByCreatedAtDesc()->get();
        return $response;
    }

    /**
     * This method handels record id scope
     * @return  Object
     * @since   2016-07-24
     * @author  NetQuick
     */
    function scopeCheckRecordId($query, $id) {
        return $query->where('id', $id);
    }

    /**
     * This method handels publish scope
     * @return  Object
     * @since   2017-08-02
     * @author  NetQuick
     */
    function scopePublish($query) {
        return $query->where(['chrPublish' => 'Y']);
    }

    /**
     * This method handels delete scope
     * @return  Object
     * @since   2017-08-02
     * @author  NetQuick
     */
    function scopeDeleted($query) {
        return $query->where(['chrDelete' => 'N']);
    }

    /**
     * This method check multiple records id
     * @return  Object
     * @since   2017-08-02
     * @author  NetQuick
     */
    function scopeCheckMultipleRecordId($query, $Ids) {
        return $query->whereIn('id', $Ids);
    }

    /**
     * This method handle order by query
     * @return  Object
     * @since   2017-08-02
     * @author  NetQuick
     */
    function scopeOrderByCreatedAtDesc($query) {
        return $query->orderBy('created_at', 'DESC');
    }

    /**
     * This method handels filter scope
     * @return  Object
     * @since   2017-08-02
     * @author  NetQuick
     */
    function scopeFilter($query, $filterArr = false, $retunTotalRecords = false) {
        $response = null;
        if ($filterArr['orderByFieldName'] != null && $filterArr['orderTypeAscOrDesc'] != null) {
            $query = $query->orderBy($filterArr['orderByFieldName'], $filterArr['orderTypeAscOrDesc']);
        } else {
            $query = $query->orderBy('created_at', 'desc');
        }
        if (!$retunTotalRecords) {
            if (!empty($filterArr['iDisplayLength']) && $filterArr['iDisplayLength'] > 0) {
                $data = $query->skip($filterArr['iDisplayStart'])->take($filterArr['iDisplayLength']);
            }
        }
        if (!empty($filterArr['statusFilter']) && $filterArr['statusFilter'] != ' ') {
            $data = $query->where('chrPublish', $filterArr['statusFilter']);
        }
        if (isset($filterArr['searchFilter']) && !empty($filterArr['searchFilter'])) {
            $data = $query->where('varName', 'like', '%' . $filterArr['searchFilter'] . '%')->orwhere('varEmail', 'like', '%' . $filterArr['searchFilter'] . '%');
        }
        if (!empty($query)) {
            $response = $query;
        }
        return $response;
    }

    public static function getOnlinePollingCatDataYes($Qid = '', $catId) {
        $response = false;
        $moduleFields = ['*'];
        $response = Self::getPowerPanelRecords($moduleFields)->deleted()
                ->deleted()
                ->publish()
                ->where('fkIntPollingCatId', $catId)
                ->where('fkIntPollingId', $Qid)
                ->where('varAns', 'Y')
                ->get();
        return $response;
    }

    public static function getOnlinePollingCatDataNo($Qid = '', $catId) {
        $response = false;
        $moduleFields = ['*'];
        $response = Self::getPowerPanelRecords($moduleFields)->deleted()
                ->deleted()
                ->publish()
                  ->where('fkIntPollingCatId', $catId)
                ->where('fkIntPollingId', $Qid)
                ->where('varAns', 'N')
                ->get();
        return $response;
    }

}
