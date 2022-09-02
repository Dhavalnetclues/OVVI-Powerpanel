<?php

/**
 * The maintenance class handels bannner queries
 * ORM implemetation.
 * @package   Netquick powerpanel
 * @license   http://www.opensource.org/licenses/BSD-3-Clause
 * @version   1.1
 * @since       2017-07-20
 * @author    NetQuick
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cache;
use Carbon\Carbon;

class Maintenance extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'maintenance';
    protected $fillable = ['*'];

    /**
     * This method handels retrival of maintenances records
     * @return  Object
     * @since   2016-07-20
     * @author  NetQuick
     */
    public static function getRecordListing($filterArr = false) {
        $response = false;
        $moduleFields = [
            'id',
            'varTitle'
        ];
        $response = Self::getPowerPanelRecords($moduleFields)
                ->deleted()
                ->orderBy('varTitle', 'asc')
                ->pluck('varTitle', 'id');
        return $response;
    }

    public static function getRecordListingforcms($filterArr = false) {
        $moduleFields = ['id', 'varTitle'];
        $response = false;
        $response = Self::Select($moduleFields)
                ->where('chrMain', 'Y')
                ->publish()
                ->deleted();

        $response = $response->orderBy('varTitle')
                ->get();
        return $response;
    }

    public static function getRecords() {
        $response = false;
        $response = Cache::maintenances(['maintenance'])->get('getmaintenanceRecords');
        if (empty($response)) {
            $response = Self::Select(['id', 'varTitle', 'intDisplayOrder', 'chrPublish'])
                    ->deleted()
                    ->publish()
                    ->paginate(10);
            Cache::maintenances(['maintenance'])->forever('getmaintenanceRecords', $response);
        }
        return $response;
    }

    /**
     * This method handels backend records
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public static function getPowerPanelRecords($moduleFields = false) {
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
    public static function getRecordList($filterArr = false, $isAdmin = false) {
        $response = false;
        $moduleFields = ['*'];
        $response = Self::getPowerPanelRecords($moduleFields)
                ->deleted();
        if ($isAdmin) {
            $response = $response->where('chrAddStar', 'N');
        }
        $response = $response->filter($filterArr)
                        ->where('chrMain', 'Y')->get();
        return $response;
    }

    public static function getRecordList_tab1($filterArr = false) {
        $response = false;
        $MainIDs = Self::distinct()
                ->select("fkMainRecord")
                ->checkMainRecord('N')
                ->deleted()
                ->groupBy('fkMainRecord')
                ->get()
                ->toArray();
        $MainIDs = array_column($MainIDs, 'fkMainRecord');
        $moduleFields = ['*'];
        $response = Self::getPowerPanelRecords($moduleFields)
                ->deleted()
                ->filter($filterArr)
                ->whereIn('id', $MainIDs)
                ->where('chrAddStar', 'Y')
                ->get();
        return $response;
    }

    /**
     * This method handels retrival of record by id
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getRecordById($id, $ignoreDeleteScope = false) {
        $response = false;
        $moduleFields = ['*'];
        $response = Self::getPowerPanelRecords($moduleFields);
        if (!$ignoreDeleteScope) {
            $response = $response->deleted();
        }
        $response = $response->checkRecordId($id)
                ->first();
        return $response;
    }

    /**
     * This method handels retrival of record by id for Log Manage
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getRecordForLogById($id) {
        $response = false;
        $moduleFields = ['*'];
        $response = Self::getPowerPanelRecords($moduleFields)->deleted()->checkRecordId($id)->first();
        return $response;
    }

    /**
     * This method handels retrival of record count
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    protected static $fetchedOrder = [];
    protected static $fetchedOrderObj = null;

    public static function getRecordByOrder($order = false) {
        $response = false;
        $moduleFields = [
            'id',
            'intDisplayOrder',
        ];
        if (!in_array($order, Self::$fetchedOrder)) {
            array_push(Self::$fetchedOrder, $order);
            Self::$fetchedOrderObj = Self::getPowerPanelRecords($moduleFields)
                    ->deleted()
                    ->orderCheck($order)
                    ->checkMainRecord('Y')
                    ->first();
        }
        $response = Self::$fetchedOrderObj;
        return $response;
    }

    /**
     * This method handels record id scope
     * @return  Object
     * @since   2016-07-24
     * @author  NetQuick
     */
    public function scopeCheckRecordId($query, $id) {
        return $query->where('id', $id);
    }

    /**
     * This method handels order scope
     * @return  Object
     * @since   2016-07-20
     * @author  NetQuick
     */
    public function scopeOrderCheck($query, $order) {
        return $query->where('intDisplayOrder', $order);
    }

    /**
     * This method handels publish scope
     * @return  Object
     * @since   2016-07-20
     * @author  NetQuick
     */
    public function scopePublish($query) {
        return $query->where(['chrPublish' => 'Y']);
    }

    /**
     * This method handels delete scope
     * @return  Object
     * @since   2016-07-20
     * @author  NetQuick
     */
    public function scopeDeleted($query) {
        return $query->where(['chrDelete' => 'N']);
    }

    /**
     * This method handels Main Record scope
     * @return  Object
     * @since   2016-07-24
     * @author  NetQuick
     */
    public function scopeCheckMainRecord($query, $checkMain = 'Y') {
        $response = false;
        $response = $query->where('chrMain', "=", $checkMain);
        return $response;
    }

    /**
     * This method handels Main Record scope
     * @return  Object
     * @since   2016-07-24
     * @author  NetQuick
     */
    public function scopeCheckStarRecord($query, $flag = 'Y') {
        $response = false;
        $response = $query->where('chrAddStar', "=", $flag);
        return $response;
    }

    /**
     * This method handels filter scope
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public function scopeFilter($query, $filterArr = false, $retunTotalRecords = false) {
        $response = null;
        if ($filterArr['orderByFieldName'] != null && $filterArr['orderTypeAscOrDesc'] != null) {
            $query = $query->orderBy($filterArr['orderByFieldName'], $filterArr['orderTypeAscOrDesc']);
        } else {
            $query = $query->orderBy('varTitle', 'ASC');
        }
        if (!$retunTotalRecords) {
            if (!empty($filterArr['iDisplayLength']) && $filterArr['iDisplayLength'] > 0) {
                $data = $query->skip($filterArr['iDisplayStart'])->take($filterArr['iDisplayLength']);
            }
        }
        if (!empty($filterArr['statusFilter']) && $filterArr['statusFilter'] != ' ') {
            $data = $query->where('chrPublish', $filterArr['statusFilter']);
        }
        if (!empty($filterArr['searchFilter']) && $filterArr['searchFilter'] != ' ') {
            $data = $query->where('varTitle', 'like', "%" . $filterArr['searchFilter'] . "%");
        }
        if (!empty($query)) {
            $response = $query;
        }
        return $response;
    }

    public static function getOrderOfApproval($id) {
        $result = Self::select('intDisplayOrder')
                ->checkRecordId($id)
                ->first();
        return $result;
    }

    public static function getCatWithParent() {
        $response = false;
        $categoryFields = ['id', 'varTitle'];
        $response = Self::getPowerPanelRecords($categoryFields)
                        ->deleted()
                        ->publish()
                        ->where('chrMain', 'Y')->get();
        return $response;
    }

    public static function getRecordCountListApprovalTab($filterArr = false) {
        $response = false;
        $MainIDs = Self::distinct()
                ->select("fkMainRecord")
                ->where('fkMainRecord', '!=', '0')
                ->groupBy('fkMainRecord')
                ->get()
                ->toArray();
        $MainIDs = array_column($MainIDs, 'fkMainRecord');
        $moduleFields = ['id'];
        $response = Self::getPowerPanelRecords($moduleFields)
                ->deleted()
                ->filter($filterArr)
                ->whereIn('id', $MainIDs)
                ->checkStarRecord('Y')
                ->count();
        return $response;
    }

    public static function getRecordCount($filterArr = false, $returnCounter = false) {
        $response = 0;
        $cmsPageFields = ['id'];
        $pageQuery = Self::getPowerPanelRecords($cmsPageFields);
        if ($filterArr != false) {
            $pageQuery = $pageQuery->filter($filterArr, $returnCounter);
        }
        $response = $pageQuery->deleted()->where('chrMain', 'Y')->count();
        return $response;
    }

    public static function getRecordCountforList($filterArr = false, $returnCounter = false, $isAdmin = false) {
        $response = 0;
        $cmsPageFields = ['id'];
        $response = Self::getPowerPanelRecords($cmsPageFields);
        if ($filterArr != false) {
            $response = $response->filter($filterArr, $returnCounter);
        }
        if ($isAdmin) {
            $response = $response->checkStarRecord('N');
        }
        $response = $response->deleted()
                ->where('chrMain', 'Y')
                ->count();
        return $response;
    }

    public static function getNewRecordsCount() {
        $NewRecordsCount = Self::select('*')->where('chrAddStar', 'Y')->where('chrDelete', 'N')->count();
        return $NewRecordsCount;
    }

    public static function getRecordCount_letest($Main_id, $id) {
        $moduleFields = ['chrLetest'];
        $response = Self::getPowerPanelRecords($moduleFields)
                ->deleted()
                ->where('chrMain', 'N')
                ->where('fkMainRecord', $Main_id)
                ->where('chrLetest', 'Y')
                ->where('id', '!=', $id)
                ->where('chrApproved', 'N')
                ->count();
        return $response;
    }

    public static function getChildGrid() {
        $id = $_REQUEST['id'];
        $response = false;
        $moduleFields = ['*'];
        $response = Self::getPowerPanelRecords($moduleFields)->deleted()->where('chrMain', 'N')->where('fkMainRecord', $id)->orderBy('created_at', 'desc')->get();
        return $response;
    }

    public static function getChildrollbackGrid() {
        $id = $_REQUEST['id'];
        $response = false;
        $moduleFields = ['*'];
        $response = Self::getPowerPanelRecords($moduleFields)->deleted()->where('chrMain', 'N')->where('chrRollBack', 'Y')->where('fkMainRecord', $id)->orderBy('created_at', 'desc')->get();
        return $response;
    }

    public static function TotalHourInMonth() {
        $response = false;
        $TotalHr = '';
        $moduleFields = ['*'];
        $sum = strtotime('00:00');
        $totaltime = 0;
        $response = Self::getPowerPanelRecords($moduleFields)
                ->deleted()
                ->whereMonth('dtDateTime', Carbon::now()->month)
                ->get();
        if (!empty($response)) {
            foreach ($response as $key => $value) {
                $timeinsec = strtotime($value['totalhour']) - $sum;
                $totaltime = $totaltime + $timeinsec;
            }
        }
        $h = str_pad(intval($totaltime / 3600), 2, '0', STR_PAD_LEFT);
        $totaltime = str_pad($totaltime - ($h * 3600), 2, '0', STR_PAD_LEFT);
        $m = str_pad(intval($totaltime / 60), 2, '0', STR_PAD_LEFT);
        return "$h:$m";
    }

    public static function GetallMonthData($settings) {
        $moduleFields = ['*'];
        $userPerMonth = array();
        for ($i = 1; $i <= 12; $i++) {
            $userPerMonth[$i] = self::getPowerPanelRecords($moduleFields)->whereMonth('dtDateTime', $i)->get();
        }
        $sum = strtotime('00:00');
        $time = array();
        $year = 1;
        foreach ($userPerMonth as $key => $value) {
            $totaltime = 0;
            for ($j = 0; $j <= count($value); $j++) {
                if (isset($value[$j]['totalhour']) && !empty($value[$j]['totalhour'])) {
                    $timeinsec = strtotime($value[$j]['totalhour']) - $sum;
                    $totaltime = $totaltime + $timeinsec;
                }
            }
            $monthNum = $year;
            $monthName = date('F', mktime(0, 0, 0, $monthNum, 10));
            if ($totaltime != 0) {
                $h = str_pad(intval($totaltime / 3600), 2, '0', STR_PAD_LEFT);
                $totaltime = str_pad($totaltime - ($h * 3600), 2, '0', STR_PAD_LEFT);
                $m = str_pad(intval($totaltime / 60), 2, '0', STR_PAD_LEFT);
                $time[$year][$monthName] = "$h:$m";
                $totalhourwork = "$h:$m";
                $time[$year][$year] = $totalhourwork;
                $time[$year]['monthnumber'] = $year;
                $time[$year]['paymenttype'] = (isset($settings['paymenttype']) && $settings['paymenttype'] != "") ? $settings['paymenttype'] : 'Not Defiend';
                $time[$year]['Maintenancenew_Hour'] = (isset($settings['Maintenancenew_Hour']) && $settings['Maintenancenew_Hour'] != "") ? $settings['Maintenancenew_Hour'] : 'Not Defiend';
//               ===========================
                if (isset($settings['Maintenancenew_Hour']) && $settings['paymenttype'] == "Y") {
                    $UnusedHr01 = strtotime($settings['Maintenancenew_Hour']) - strtotime('00:00');
                    $h01 = str_pad(intval($UnusedHr01 / 3600), 2, '0', STR_PAD_LEFT);
                    $hd01 = str_pad(intval(($UnusedHr01 / 3600) / 12), 2, '0', STR_PAD_LEFT);
                    $totaltime01 = str_pad($UnusedHr01 - ($h01 * 3600), 2, '0', STR_PAD_LEFT);
                    $m01 = str_pad(intval(($totaltime01 / 60) / 12), 2, '0', STR_PAD_LEFT);
                    $PerMonth = "$hd01:$m01";
                } elseif (isset($settings['Maintenancenew_Hour']) && $settings['paymenttype'] == "M") {
                    $PerMonth = $settings['Maintenancenew_Hour'];
                } else {
                    $PerMonth = "00:00";
                }
//                =========Remaining_Hour==============
                if (isset($settings['Maintenancenew_Hour']) && strtotime($PerMonth) > strtotime($totalhourwork)) {
                    $R_UnusedHr = strtotime($PerMonth) - strtotime($totalhourwork);
                    $R_h = str_pad(intval($R_UnusedHr / 3600), 2, '0', STR_PAD_LEFT);
                    $R_totaltime = str_pad($R_UnusedHr - ($R_h * 3600), 2, '0', STR_PAD_LEFT);
                    $R_m = str_pad(intval($R_totaltime / 60), 2, '0', STR_PAD_LEFT);
                    $Remaining_Hour = "$R_h:$R_m";
                } else {
                    $Remaining_Hour = "00:00";
                }
//                  ********************Extra Hour****************
                if (isset($settings['Maintenancenew_Hour']) && strtotime($PerMonth) < strtotime($totalhourwork)) {
                    $ExtraHr = strtotime($totalhourwork) - strtotime($PerMonth);
                    $E_h = str_pad(intval($ExtraHr / 3600), 2, '0', STR_PAD_LEFT);
                    $E_totaltime = str_pad($ExtraHr - ($E_h * 3600), 2, '0', STR_PAD_LEFT);
                    $E_m = str_pad(intval($E_totaltime / 60), 2, '0', STR_PAD_LEFT);
                    $ExtraHr = "$E_h:$E_m";
                } else {
                    $ExtraHr = "00:00";
                }
                $time[$year]['PerMonth_Hour'] = (isset($settings['Maintenancenew_Hour']) && $settings['Maintenancenew_Hour'] != "") ? $PerMonth : 'Not Defiend';
                $time[$year]['Used_Hour'] = $totalhourwork;
                $time[$year]['Remaining_Hour'] = $Remaining_Hour;
                $time[$year]['Extra_Hour'] = $ExtraHr;
            } else {
                $totalhourwork = '00:00';
                  $time[$year]['monthnumber'] = $year;
                $time[$year][$monthName] = "00:00";
                $time[$year][$year] = $totalhourwork;
                $time[$year]['paymenttype'] = (isset($settings['paymenttype']) && $settings['paymenttype'] != "") ? $settings['paymenttype'] : 'Not Defiend';
                $time[$year]['Maintenancenew_Hour'] = (isset($settings['Maintenancenew_Hour']) && $settings['Maintenancenew_Hour'] != "") ? $settings['Maintenancenew_Hour'] : 'Not Defiend';
//                 ===========================
                if (isset($settings['Maintenancenew_Hour']) && $settings['paymenttype'] == "Y") {
                    $UnusedHr01 = strtotime($settings['Maintenancenew_Hour']) - strtotime('00:00');
                    $h01 = str_pad(intval($UnusedHr01 / 3600), 2, '0', STR_PAD_LEFT);
                    $hd01 = str_pad(intval(($UnusedHr01 / 3600) / 12), 2, '0', STR_PAD_LEFT);
                    $totaltime01 = str_pad($UnusedHr01 - ($h01 * 3600), 2, '0', STR_PAD_LEFT);
                    $m01 = str_pad(intval(($totaltime01 / 60) / 12), 2, '0', STR_PAD_LEFT);
                    $PerMonth = "$hd01:$m01";
                } elseif (isset($settings['Maintenancenew_Hour']) && $settings['paymenttype'] == "M") {
                    $PerMonth = $settings['Maintenancenew_Hour'];
                } else {
                    $PerMonth = "00:00";
                }
//                  =========Remaining_Hour==============
                if (isset($settings['Maintenancenew_Hour']) && strtotime($PerMonth) > strtotime($totalhourwork)) {
                    $R_UnusedHr = strtotime($PerMonth) - strtotime($totalhourwork);
                    $R_h = str_pad(intval($R_UnusedHr / 3600), 2, '0', STR_PAD_LEFT);
                    $R_totaltime = str_pad($R_UnusedHr - ($R_h * 3600), 2, '0', STR_PAD_LEFT);
                    $R_m = str_pad(intval($R_totaltime / 60), 2, '0', STR_PAD_LEFT);
                    $Remaining_Hour = "$R_h:$R_m";
                } else {
                    $Remaining_Hour = "00:00";
                }

//                ********************Extra Hour****************
                if (isset($settings['Maintenancenew_Hour']) && strtotime($PerMonth) < strtotime($totalhourwork)) {
                    $ExtraHr = strtotime($totalhourwork) - strtotime($PerMonth);
                    $E_h = str_pad(intval($ExtraHr / 3600), 2, '0', STR_PAD_LEFT);
                    $E_totaltime = str_pad($ExtraHr - ($E_h * 3600), 2, '0', STR_PAD_LEFT);
                    $E_m = str_pad(intval($E_totaltime / 60), 2, '0', STR_PAD_LEFT);
                    $ExtraHr = "$E_h:$E_m";
                } else {
                    $ExtraHr = "00:00";
                }

                $time[$year]['PerMonth_Hour'] = (isset($settings['Maintenancenew_Hour']) && $settings['Maintenancenew_Hour'] != "") ? $PerMonth : 'Not Defiend';
                $time[$year]['Used_Hour'] = $totalhourwork;
                $time[$year]['Remaining_Hour'] = $Remaining_Hour;
                $time[$year]['Extra_Hour'] = $ExtraHr;
            }
            $year++;
        }
//        echo "<pre/>";
//        print_r($time);
//        exit;
        return $time;
    }

    public static function approved_data_Listing($request) {
        $id = $request->id;
        $main_id = $request->main_id;
        $PUserid = $request->PUserid;
        //Select Child Record Data Start
        $response = false;
        $moduleFields = [
            'id',
            'varTitle',
            'intDisplayOrder',
            'chrPublish'
        ];
        $aliasFields = ['id', 'varAlias'];
        $response = Self::getPowerPanelRecords($moduleFields)->deleted()->where('chrMain', 'N')->where('id', $id)->orderBy('created_at', 'desc')->first();
        //Select Child Record Data END
        //Update Copy Child Record To Main Record start
        $whereConditions = ['id' => $main_id];
        $updateMainRecord = [
            'varTitle' => $response['varTitle'],
            'chrAddStar' => 'N',
            'chrPublish' => $response['chrPublish'],
        ];
        CommonModel::updateRecords($whereConditions, $updateMainRecord);
        //Update Copy Child Record To Main Record end
        $whereConditions_ApproveN = ['fkMainRecord' => $main_id];
        $updateToApproveN = [
            'chrApproved' => 'N',
            'chrLetest' => 'N',
            'intApprovedBy' => '0',
        ];
        CommonModel::updateRecords($whereConditions_ApproveN, $updateToApproveN);
        $whereConditionsApprove = ['id' => $id, 'chrMain' => 'N'];
        $updateToApprove = [
            'chrApproved' => 'Y',
            'chrRollBack' => 'Y',
            'intApprovedBy' => auth()->user()->id
        ];
        CommonModel::updateRecords($whereConditionsApprove, $updateToApprove);
        $msg_show = "Record successfully approved.";
        return $msg_show;
    }

    public static function getRecordforEmailById($id) {
        $response = false;
        $moduleFields = ['id',
            'varTitle',
        ];
        $response = Cache::maintenances(['maintenance'])->get('getRecordforEmailById_' . $id);
        if (empty($response)) {
            $response = Self::getFrontRecords($moduleFields)
                    ->deleted()
                    ->publish()
                    ->dateRange()
                    ->orderBy('intDisplayOrder')
                    ->where('chrMain', 'Y')
                    ->CheckRecordId($id)
                    ->first();
            Cache::maintenances(['maintenance'])->forever('getRecordforEmailById_' . $id, $response);
        }
        return $response;
    }

    public static function getFrontList() {
        $response = false;
        $moduleFields = ['id',
            'varTitle',
        ];
        $response = Cache::maintenances(['maintenance'])->get('maintenanceFrontList');
        if (empty($response)) {
            $response = Self::getFrontRecords($moduleFields)
                    ->deleted()
                    ->publish()
                    ->dateRange()
                    ->orderBy('intDisplayOrder')
                    ->where('chrMain', 'Y')
                    ->get();
            Cache::maintenances(['maintenance'])->forever('maintenanceFrontList', $response);
        }
        return $response;
    }

    public static function getFrontListForFooter() {
        $response = false;
        $moduleFields = [
            'id',
            'varTitle',
        ];
        $response = Cache::maintenances(['maintenance'])->get('maintenanceFrontListForFooter');
        if (empty($response)) {
            $response = Self::getFrontRecords($moduleFields)
                    ->deleted()
                    ->publish()
                    ->dateRange()
                    ->orderBy('intDisplayOrder')
                    ->where('chrMain', 'Y')
                    ->get();
            Cache::maintenances(['maintenance'])->forever('maintenanceFrontListForFooter', $response);
        }
        return $response;
    }

    public static function getFrontRecords($moduleFields = false, $aliasFields = false) {
        $data = [];
        if ($aliasFields != false) {
            $data = [
                'alias' => function ($query) use ($aliasFields) {
                    $query->select($aliasFields);
                },
            ];
        }
        return self::select($moduleFields)->with($data);
    }

    public function scopeDateRange($query) {
        $response = false;
        $response = $query->whereRaw('((dtEndDateTime >= NOW() AND NOW() >= dtDateTime) OR (NOW() >= dtDateTime and dtEndDateTime is null))');
        return $response;
    }

}
