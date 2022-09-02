<?php

/**
 * The FormBuilderLead class handels bannner queries
 * ORM implemetation.
 * @package   Netquick powerpanel
 * @license   http://www.opensource.org/licenses/BSD-3-Clause
 * @version   1.1
 * @since       2017-07-20
 * @author    NetQuick
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Cache;
use Carbon\Carbon;
use Config;

class FormBuilderLead extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'formbuilder_lead';
    protected $fillable = [
        'id',
        'fk_formbuilder_id',
        'formdata',
        'varIpAddress',
        'chrDelete',
        'created_at',
    ];

    /**
     * This method handels retrival of videogallery records
     * @return  Object
     * @since   2016-07-20
     * @author  NetQuick
     */
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
        $moduleFields = [
            'id',
            'fk_formbuilder_id',
            'formdata',
            'varIpAddress',
            'chrDelete',
            'created_at',
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
    public static function getRecordList($filterArr = false,$id = false) {
        $response = false;
        $moduleFields = [
            'formbuilder_lead.id',
            'formbuilder_lead.fk_formbuilder_id',
            'formbuilder_lead.formdata',
            'formbuilder_lead.filename',
            'formbuilder_lead.varIpAddress',
            'formbuilder_lead.chrDelete',
            'formbuilder_lead.created_at',
        ];
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
        $moduleFields = [
            'formbuilder_lead.id',
            'formbuilder_lead.fk_formbuilder_id',
            'formbuilder_lead.formdata',
            'formbuilder_lead.filename',
            'formbuilder_lead.varIpAddress',
            'formbuilder_lead.chrDelete',
            'formbuilder_lead.created_at',
        ];
        $response = Self::getPowerPanelRecords($moduleFields)
                ->deleted();
        if (isset($id) && $id != '') {
            $response = $response->where('id', '=', $id);
        }
        $response = $response->filter($filterArr,$returnCounter);
        $response = $response->count();
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
            'fk_formbuilder_id',
            'formdata',
            'varIpAddress',
            'chrDelete',
            'created_at',
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
            'id',
            'fk_formbuilder_id',
            'formdata',
            'varIpAddress',
            'filename',
            'chrDelete',
            'created_at',
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
        return $query->where(['formbuilder_lead.chrPublish' => 'Y']);
    }

    /**
     * This method handels delete scope
     * @return  Object
     * @since   2017-08-02
     * @author  NetQuick
     */
    function scopeDeleted($query) {
        return $query->where(['formbuilder_lead.chrDelete' => 'N']);
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
        $response = '';
        if (!empty($filterArr['orderByFieldName']) && !empty($filterArr['orderTypeAscOrDesc'])) {
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
        if (isset($filterArr['searchFilter']) && !empty($filterArr['searchFilter']) && $filterArr['searchFilter'] != ' ') {
            $query = $query->leftJoin('form_builder', 'form_builder.id', '=', 'formbuilder_lead.fk_formbuilder_id');
            $data = $query->where('form_builder.varName', 'like', '%' . $filterArr['searchFilter'] . '%');
        }

        if (!empty($query)) {
            $response = $query;
        }
        return $response;
    }

    public static function clean($string) {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
        return preg_replace('/[^A-Za-z0-9\-.]/', '', $string); // Removes special chars.
    }

    public static function insertformdata($data, $varIpAddress) {
        $keydata = array_keys($data);
        $attechmentarray = array();
        foreach ($keydata as $edata) {
            $expload = explode("-", $edata);
            if (($expload[0] == "file")) {
                $file = $data[$expload[0] . '-' . $expload[1]];
                $target_dir = Config::get('Constant.LOCAL_CDN_PATH') . "/upload/";
                $timestamp = str_replace([' ', ':'], '-', Carbon::now()->toDateTimeString());
                $file1 = $file->getClientOriginalName();
                $path = pathinfo($file1);
                $filename = $timestamp . '-' . self::clean($path['filename']);
                $path_filename_ext = $target_dir . $filename . "." . $path['extension'];
                $file->move($target_dir, $path_filename_ext);
                array_push($attechmentarray, $filename . "." . $path['extension']);
            }
        }

        $filedata = '';
        if (!empty($attechmentarray)) {
            $filedata = implode(',', $attechmentarray);
        }

        $form_data = [
            'id' => '',
            'fk_formbuilder_id' => $data['fkformbuilderid'],
            'formdata' => json_encode($data),
            'filename' => $filedata,
            'varIpAddress' => $varIpAddress,
            'chrDelete' => 'N',
            'created_at' => date('Y-m-d H:i'),
        ];
        $user = self::insertGetId($form_data);
        return $user;
    }

    public static function GetFormData($id) {
        $pagedata = DB::table('formbuilder_lead')
                ->select('*')
                ->where('id', '=', $id)
                ->first();
        return $pagedata;
    }

}
