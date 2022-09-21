<?php

/**
 * The OrderLead class handels bannner queries
 * ORM implemetation.
 * @package   Netquick powerpanel
 * @license   http://www.opensource.org/licenses/BSD-3-Clause
 * @version   1.1
 * @since       2017-07-20
 * @author    NetQuick
 */

namespace Powerpanel\OrderLead\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Cache;
use Carbon\Carbon;
use Config;
use App\Helpers\Aws_File_helper;
use App\Helpers\MyLibrary;

class OrderLead extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $BUCKET_ENABLED;

    protected $table = 'OrderLeads';
    protected $fillable = [
        'id',
        'varTitle',
        'varOnBusinessType',
        'chrOnPOSBundle',
        'chrOnPOSColor',
        'varOnSoftwareServiceFees',
        'varOnPeripherals',
        'varOnMenuProgramming',
        'varOnAdditionalModules',
        'varOnStreetAddress',
        'varOnCountry',
        'varName',
        'varOnState',
        'varOnCity',
        'varOnZipCode',
        'varOnPhoneNumber',
        'varOnEmailId',
        'varOnAdditionalEquipment',
        'fk_formbuilder_id',
        'varOnFullName',
        'varIpAddress',
        'chrDelete',
        'created_at',
    ];

    public function __construct()
    {
        parent::__construct();
        $this->BUCKET_ENABLED = Config::get('Constant.BUCKET_ENABLED');
    }

    /**
     * This method handels retrival of videogallery records
     * @return  Object
     * @since   2016-07-20
     * @author  NetQuick
     */
    public static function getCurrentMonthCount() {
        $response = false;
        $response = Self::getRecords()
                ->leftJoin('Countries', 'Countries.id', '=', 'OrderLeads.varOnCountry')
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
                ->leftJoin('Countries', 'Countries.id', '=', 'OrderLeads.varOnCountry')
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
            'varTitle',
            'varOnBusinessType',
            'chrOnPOSBundle',
            'chrOnPOSColor',
            'varOnSoftwareServiceFees',
            'varOnPeripherals',
            'varOnMenuProgramming',
            'varOnAdditionalModules',
            'varOnStreetAddress',
            'varOnCountry',
            'Countries.varName',
            'varOnState',
            'varOnCity',
            'varOnZipCode',
            'varOnPhoneNumber',
            'varOnEmailId',
            'varOnAdditionalEquipment',
            'fk_formbuilder_id',
            'varOnFullName',
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
            'OrderLeads.id',
            'varTitle',
            'varOnBusinessType',
            'chrOnPOSBundle',
            'chrOnPOSColor',
            'varOnSoftwareServiceFees',
            'varOnPeripherals',
            'varOnMenuProgramming',
            'varOnAdditionalModules',
            'varOnStreetAddress',
            'varOnCountry',
            'Countries.varName AS country',
            'Cities.varName AS city',
            'States.varName AS state',
            'varOnState',
            'varOnCity',
            'varOnZipCode',
            'varOnPhoneNumber',
            'varOnEmailId',
            'varOnAdditionalEquipment',
            'fk_formbuilder_id',
            'varOnFullName',
            'varIpAddress',
            'chrDelete',
            'created_at',
        ];
        $response = Self::getPowerPanelRecords($moduleFields)
                    ->leftJoin('Countries', 'Countries.id', '=', 'OrderLeads.varOnCountry')
                    ->leftJoin('Cities', 'Cities.id', '=', 'OrderLeads.varOnCity')
                    ->leftJoin('States', 'States.id', '=', 'OrderLeads.varOnState')
                    ->deleted();
        if (isset($id) && $id != '') {
            $response = $response->where('OrderLeads.id', '=', $id);
        }
        $response = $response->filter($filterArr)
                ->get();
        return $response;
    }

    public static function getRecordCount($filterArr = false, $returnCounter = false, $modelNameSpace = false, $checkMain = false, $id = false) {
        $response = false;
        $moduleFields = [
            'id',
            'varTitle',
            'varOnBusinessType',
            'chrOnPOSBundle',
            'chrOnPOSColor',
            'varOnSoftwareServiceFees',
            'varOnPeripherals',
            'varOnMenuProgramming',
            'varOnAdditionalModules',
            'varOnStreetAddress',
            'varOnCountry',
            'varOnState',
            'varOnCity',
            'varOnZipCode',
            'varOnPhoneNumber',
            'varOnEmailId',
            'varOnAdditionalEquipment',
            'fk_formbuilder_id',
            'varOnFullName',
            'varIpAddress',
            'chrDelete',
            'created_at',
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
            'varTitle',
            'varOnBusinessType',
            'chrOnPOSBundle',
            'chrOnPOSColor',
            'varOnSoftwareServiceFees',
            'varOnPeripherals',
            'varOnMenuProgramming',
            'varOnAdditionalModules',
            'varOnStreetAddress',
            'varOnCountry',
            'varOnState',
            'varOnCity',
            'varOnZipCode',
            'varOnPhoneNumber',
            'varOnEmailId',
            'varOnAdditionalEquipment',
            'fk_formbuilder_id',
            'varOnFullName',
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
            'OrderLeads.id',
            'varTitle',
            'varOnBusinessType',
            'chrOnPOSBundle',
            'chrOnPOSColor',
            'varOnSoftwareServiceFees',
            'varOnPeripherals',
            'varOnMenuProgramming',
            'varOnAdditionalModules',
            'varOnStreetAddress',
            'varOnCountry',
            'varOnState',
            'varOnCity',
            'Countries.varName AS country',
            'Cities.varName AS city',
            'States.varName AS state',
            'varOnZipCode',
            'varOnPhoneNumber',
            'varOnEmailId',
            'varOnAdditionalEquipment',
            'fk_formbuilder_id',
            'varOnFullName',
            'varIpAddress',
            'chrDelete',
            'created_at',
        ];
        $query = Self::getPowerPanelRecords($moduleFields)
                ->leftJoin('Countries', 'Countries.id', '=', 'OrderLeads.varOnCountry')
                ->leftJoin('Cities', 'Cities.id', '=', 'OrderLeads.varOnCity')
                ->leftJoin('States', 'States.id', '=', 'OrderLeads.varOnState')
                ->deleted();
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
        return $query->where(['OrderLeads.chrPublish' => 'Y']);
    }

    /**
     * This method handels delete scope
     * @return  Object
     * @since   2017-08-02
     * @author  NetQuick
     */
    function scopeDeleted($query) {
        return $query->where(['OrderLeads.chrDelete' => 'N']);
    }

    /**
     * This method check multiple records id
     * @return  Object
     * @since   2017-08-02
     * @author  NetQuick
     */
    function scopeCheckMultipleRecordId($query, $Ids) {
        return $query->whereIn('OrderLeads.id', $Ids);
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
            $query = $query->orderBy('OrderLeads.'.$filterArr['orderByFieldName'], $filterArr['orderTypeAscOrDesc']);
        } else {
            $query = $query->orderBy('id', 'desc');
        }
        if (!$retunTotalRecords) {
            if (!empty($filterArr['iDisplayLength']) && $filterArr['iDisplayLength'] > 0) {
                $data = $query->skip($filterArr['iDisplayStart'])->take($filterArr['iDisplayLength']);
            }
        }
        if (!empty($filterArr['statusFilter']) && $filterArr['statusFilter'] != ' ') {
            $data = $query->where('OrderLeads.chrPublish', $filterArr['statusFilter']);
        }
        if (isset($filterArr['searchFilter']) && !empty($filterArr['searchFilter']) && $filterArr['searchFilter'] != ' ') {
            $data = $query->where('OrderLeads.varTitle', 'like', '%' . $filterArr['searchFilter'] . '%')->orwhere('OrderLeads.varOnFullName', 'like', '%' . $filterArr['searchFilter'] . '%')->orwhere('varOnEmailId', 'like','%'. MyLibrary::encryptLatest($filterArr['searchFilter']).'%');
        }

        if (!empty($filterArr['start']) && $filterArr['start'] != ' ') {
                $data = $query->whereRaw('DATE(`nq_OrderLeads`.`created_at`) >= DATE("' . date('Y-m-d', strtotime(str_replace('/', '-', $filterArr['start']))) . '")');
        }
        if (!empty($filterArr['start']) && $filterArr['start'] != '' &&  empty($filterArr['end']) && $filterArr['end'] == '') {
                $data = $query->whereRaw('DATE(`nq_OrderLeads`.`created_at`) >= DATE("' . date('Y-m-d', strtotime(str_replace('/', '-', $filterArr['start']))) . '")');
        }

        if (!empty($filterArr['end']) && $filterArr['end'] != ' ') {
                $data = $query->whereRaw('DATE(`nq_OrderLeads`.`created_at`) <= DATE("' . date('Y-m-d', strtotime(str_replace('/', '-', $filterArr['end']))) . '") AND `nq_OrderLeads`.`created_at` IS NOT null');
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
        $saved_name = "";
        $saved_file_name = "";

        foreach ($keydata as $edata) {
            $expload = explode("-", $edata);
            if (($expload[0] == "file")) {

                $files = $data[$expload[0] . '-' . $expload[1]];
                $error = $files->getError();
                $original_name = $files->getClientOriginalName();
                $sourceFilePath = $files->getPathName();
                $pathval = pathinfo($original_name);

                $saved_name = "file" . "_" . time() . "_" .  self::clean($pathval['filename']). "." . $pathval['extension'];
                $saved_file_name .= "file" . "_" . time() . "_" . self::clean($pathval['filename']). "." . $pathval['extension'].',';

                $_this = new self;
                if ($error == UPLOAD_ERR_OK) {
                    $destinationPath = 'cdn\foi_documents';
                    if ($_this->BUCKET_ENABLED) {
                        Aws_File_helper::putObject($sourceFilePath, 'foi_documents/', $saved_name);
                    } else {
                        $files->move($destinationPath, $saved_name);
                        $data['attachement'] = url('/') . "cdn\foi_documents" . $saved_name;
                    }
                }
                array_push($attechmentarray, $saved_name);


                // $file = $data[$expload[0] . '-' . $expload[1]];
                // $target_dir = Config::get('Constant.LOCAL_CDN_PATH') . "/upload/";
                // $timestamp = str_replace([' ', ':'], '-', Carbon::now()->toDateTimeString());
                // $file1 = $file->getClientOriginalName();
                // $path = pathinfo($file1);
                // $filename = $timestamp . '-' . self::clean($path['filename']);
                // $path_filename_ext = $target_dir . $filename . "." . $path['extension'];
                // $file->move($target_dir, $path_filename_ext);
                // array_push($attechmentarray, $filename . "." . $path['extension']);
            }
        }

        $filedata = '';
        if (!empty($attechmentarray)) {
            $filedata = implode(',', $attechmentarray);
        }

        $form_data = [
            'id' => '',
            'fk_formbuilder_id' => $data['fkformbuilderid'],
            'varOnFullName' => $data['varOnFullName'],
            'filename' => $filedata,
            'varIpAddress' => $varIpAddress,
            'chrDelete' => 'N',
            'created_at' => date('Y-m-d H:i'),
        ];
        $user = self::insertGetId($form_data);
        return $user;
    }

    public static function GetFormData($id) {
        $pagedata = DB::table('OrderLeads')
                ->select('*')
                ->where('id', '=', $id)
                ->first();
        return $pagedata;
    }

    public static function getDashboardReport($year = false) {
        $response = false;
        $response = Self::select('*')->where('chrDelete', 'N');
        if ($year != '') {
            $response = $response->whereRaw("YEAR(created_at) >= " . (int) $year . "");
        }
        $response = $response->count();
        return $response;
    }

}
