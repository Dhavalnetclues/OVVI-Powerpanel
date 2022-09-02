<?php
/**
 * The onlinepollingcategory class handels bannner queries
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
class OnlinePollingCategory extends Model {
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'onlinepollingcategory';
    protected $fillable = [
        'id',
        'intDisplayOrder',
        'chrPublish',
        'chrDelete',
        'chrApproved',
        'intApprovedBy',
        'chrRollBack',
        'UserID',
        'chrAddStar'
    ];
    /**
     * This method handels retrival of onlinepollingcategorys records
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
    public static function getRecordcatListing($filterArr = false) {
        $response = false;
        $moduleFields = [
            'id',
            'varTitle'
        ];
        $response = Self::getPowerPanelRecords($moduleFields)
                ->deleted()
                ->publish()
                ->orderBy('varTitle', 'asc')
                 ->get();
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
        $response = Cache::tags(['onlinepollingcategory'])->get('getonlinepollingcategoryRecords');
        if (empty($response)) {
            $response = Self::Select(['id', 'varTitle', 'intDisplayOrder', 'chrPublish'])
                    ->deleted()
                    ->publish()
                    ->paginate(10);
            Cache::tags(['onlinepollingcategory'])->forever('getonlinepollingcategoryRecords', $response);
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
        $moduleFields = [
            'id',
            'varTitle',
            'intDisplayOrder',
            'chrAddStar',
            'chrPublish',
            'chrMain'
        ];
        $response = Self::getPowerPanelRecords($moduleFields)
                ->deleted();
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
        $moduleFields = [
            'id',
            'varTitle',
            'intDisplayOrder',
            'chrPublish',
            'fkMainRecord',
            'chrAddStar'
        ];
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
        $moduleFields = [
            'id',
            'varTitle',
            'intDisplayOrder',
            'chrPublish',
            'fkMainRecord',
            'UserID'
        ];
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
        $moduleFields = [
            'id',
            'varTitle',
            'intDisplayOrder',
            'chrPublish',
            'fkMainRecord',
            'UserID'
        ];
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
        $response = $response->deleted()
                ->where('chrMain', 'Y')
                ->count();
        return $response;
    }
    public static function getNewRecordsCount() {
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
                ->whereIn('id', $MainIDs)
                ->checkStarRecord('Y')
                ->count();
        return $response;
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
        $moduleFields = ['id', 'varTitle', 'intDisplayOrder', 'chrPublish', 'fkMainRecord', 'created_at', 'chrApproved', 'updated_at', 'intApprovedBy', 'UserID'];
        $response = Self::getPowerPanelRecords($moduleFields)->deleted()->where('chrMain', 'N')->where('fkMainRecord', $id)->orderBy('created_at', 'desc')->get();
        return $response;
    }
    public static function getChildrollbackGrid() {
        $id = $_REQUEST['id'];
        $response = false;
        $moduleFields = ['id', 'varTitle', 'intDisplayOrder', 'chrPublish', 'fkMainRecord', 'created_at', 'chrApproved', 'updated_at', 'intApprovedBy', 'UserID'];
        $response = Self::getPowerPanelRecords($moduleFields)->deleted()->where('chrMain', 'N')->where('chrRollBack', 'Y')->where('fkMainRecord', $id)->orderBy('created_at', 'desc')->get();
        return $response;
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
        $response = Cache::tags(['onlinepollingcategory'])->get('getRecordforEmailById_' . $id);
        if (empty($response)) {
            $response = Self::getFrontRecords($moduleFields)
                    ->deleted()
                    ->publish()
                    ->dateRange()
                    ->orderBy('intDisplayOrder')
                    ->where('chrMain', 'Y')
                    ->CheckRecordId($id)
                    ->first();
            Cache::tags(['onlinepollingcategory'])->forever('getRecordforEmailById_' . $id, $response);
        }
        return $response;
    }
    public static function getFrontList() {
        $response = false;
        $moduleFields = ['id',
            'varTitle',
        ];
        $response = Cache::tags(['onlinepollingcategory'])->get('onlinepollingcategoryFrontList');
        if (empty($response)) {
            $response = Self::getFrontRecords($moduleFields)
                    ->deleted()
                    ->publish()
                    ->dateRange()
                    ->orderBy('intDisplayOrder')
                    ->where('chrMain', 'Y')
                    ->get();
            Cache::tags(['onlinepollingcategory'])->forever('onlinepollingcategoryFrontList', $response);
        }
        return $response;
    }
    public static function getFrontListForFooter() {
        $response = false;
        $moduleFields = [
            'id',
            'varTitle',
        ];
        $response = Cache::tags(['onlinepollingcategory'])->get('onlinepollingcategoryFrontListForFooter');
        if (empty($response)) {
            $response = Self::getFrontRecords($moduleFields)
                    ->deleted()
                    ->publish()
                    ->dateRange()
                    ->orderBy('intDisplayOrder')
                    ->where('chrMain', 'Y')
                    ->get();
            Cache::tags(['onlinepollingcategory'])->forever('onlinepollingcategoryFrontListForFooter', $response);
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
