<?php

namespace Powerpanel\GetdemoLead\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Helpers\MyLibrary;

class GetdemoLead extends Model {

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'getdemo_leads';
    protected $fillable = [
        'id',
        'varTitle',
        'varEmail',
        'varPhoneNo',
        'varBusinessName',
        'txtUserMessage',
        'chrDelete',
        'varIpAddress',
        'varPageName',
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
        $moduleFields = ['id', 'varTitle', 'varEmail', 'varPhoneNo', 'txtUserMessage', 'varBusinessName', 'chrDelete', 'varIpAddress', 'created_at', 'updated_at','varPageName'];
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
    public static function getRecordList($filterArr = false, $id = false) {
        $response = false;
        $moduleFields = ['id', 'varTitle', 'varEmail', 'varPhoneNo', 'varBusinessName', 'txtUserMessage', 'varIpAddress', 'created_at', 'chrPublish','varPageName'];
        $response = Self::getPowerPanelRecords($moduleFields)
                ->deleted();
        if (isset($id) && $id != '') {
            $response = $response->where('id', '=', $id);
        }
        $response = $response->filter($filterArr)
                ->get();
        return $response;
    }

    public static function getRecordCount($filterArr = false, $returnCounter = false, $modelNameSpace = false, $checkMain = false, $id = false) {
        $response = false;
        $moduleFields = ['id', 'varTitle', 'varEmail', 'varPhoneNo', 'varBusinessName', 'txtUserMessage', 'varIpAddress', 'created_at', 'chrPublish','varPageName'];
        $response = Self::getPowerPanelRecords($moduleFields)
                ->deleted();
        if (isset($id) && $id != '') {
            $response = $response->where('id', '=', $id);
        }
        $response = $response->filter($filterArr, $returnCounter);
        $response = $response->count();
        return $response;
    }

    /**
     * This method handels retrival of backend record list
     * @return  Object
     * @since   2017-10-24
     * @author  NetQuick
     */
    public static function getRecordForDashboardLeadList($limit = 5) {
        $response = false;
        $moduleFields = [
            'id',
            'varTitle',
            'varEmail',
            'varPhoneNo',
            'varBusinessName',
            'txtUserMessage',
            'varIpAddress',
            'created_at',
            'chrPublish',
            'varPageName',
        ];
        $response = Self::getPowerPanelRecords($moduleFields)
                ->deleted()
                ->orderBy('id', 'DESC')
                ->limit($limit)
                ->get();
        return $response;
    }

    public static function getRecordListDashboard($year = false, $timeparam = false, $month = false) {
        $response = false;
        $response = Self::where('chrPublish', '=', 'Y')->where('chrDelete', '=', 'N');
        if ($year != '') {
            $response = $response->whereRaw("YEAR(created_at) >= " . (int) $year . "");
        }
        $response = $response->count();
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
        $moduleFields = ['id', 'varTitle', 'varEmail', 'varPhoneNo', 'varBusinessName', 'txtUserMessage', 'created_at','varPageName'];
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
        // \DB::enableQueryLog(); // Enable query log
        $response = false;
        $moduleFields = [
            'varTitle',
            'varEmail',
            'varPhoneNo',
            'varBusinessName',
            'txtUserMessage',
            'varIpAddress',
            'varPageName',
            'created_at'
        ];
        $query = Self::getPowerPanelRecords($moduleFields)
                ->deleted();

        if(isset($selectedIds["searchFilter"]) && !empty($selectedIds["searchFilter"])){
            $query->SearchByName($selectedIds["searchFilter"]);
        }
        if(isset($selectedIds["start"]) && !empty($selectedIds["start"]) && isset($selectedIds["end"]) && !empty($selectedIds["end"])){
            $query->SearchByDateRange($selectedIds["start"],$selectedIds["end"]);
        }else if(isset($selectedIds["start"]) && !empty($selectedIds["start"]) && $selectedIds["end"] == ""){
            $query->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '>=', $selectedIds["start"]);
        }else if(isset($selectedIds["end"]) && !empty($selectedIds["end"]) && $selectedIds["start"] == ""){
            $query->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '<=', $selectedIds["end"]);
        }

        if(isset($selectedIds["checkedIds"]) &&  !empty($selectedIds["checkedIds"]) && count($selectedIds["checkedIds"]) > 0){
            $query->checkMultipleRecordId($selectedIds["checkedIds"]);
        }
        $response = $query->orderByCreatedAtDesc()
                ->get();
                // dd(\DB::getQueryLog()); // Show results of log
        return $response;
    }

    /**
     * This method handels search by date range scope
     * @return  Object
     * @since   2016-07-24
     * @author  NetQuick
     */
    function scopeSearchByDateRange($query, $startDate, $endDate) {
            return $query->whereBetween(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), [$startDate,$endDate]);
    }

    /**
     * This method handels search by title scope
     * @return  Object
     * @since   2016-07-24
     * @author  NetQuick
     */
    function scopeSearchByName($query, $title) {
        return $query->where('varTitle', 'like', '%' .$title . '%');
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
        if (!empty($filterArr['orderByFieldName']) && !empty($filterArr['orderTypeAscOrDesc'])) {
            $query = $query->orderBy($filterArr['orderByFieldName'], $filterArr['orderTypeAscOrDesc']);
        } else {
            $query = $query->orderBy('id', 'DESC');
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
            $data = $query->where('varTitle', 'like', '%' . $filterArr['searchFilter'] . '%')->orwhere('varBusinessName', 'like', '%' . $filterArr['searchFilter'] . '%')->orwhere('varEmail', 'like','%'. MyLibrary::encryptLatest($filterArr['searchFilter']).'%');
        }
        if (!empty($filterArr['start']) && $filterArr['start'] != ' ') {
            $data = $query->whereRaw('DATE(created_at) >= DATE("' . date('Y-m-d', strtotime(str_replace('/', '-', $filterArr['start']))) . '")');
        }
        if (!empty($filterArr['start']) && $filterArr['start'] != '' && empty($filterArr['end']) && $filterArr['end'] == '') {
            $data = $query->whereRaw('DATE(created_at) >= DATE("' . date('Y-m-d', strtotime(str_replace('/', '-', $filterArr['start']))) . '")');
        }

        if (!empty($filterArr['end']) && $filterArr['end'] != ' ') {
            $data = $query->whereRaw('DATE(created_at) <= DATE("' . date('Y-m-d', strtotime(str_replace('/', '-', $filterArr['end']))) . '") AND created_at IS NOT null');
        }
        if (!empty($query)) {
            $response = $query;
        }
        return $response;
    }

}
