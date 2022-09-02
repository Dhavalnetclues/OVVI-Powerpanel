<?php
/**
 * The Banner class handels bannner queries
 * ORM implemetation.
 * @package   Netquick powerpanel
 * @license   http://www.opensource.org/licenses/BSD-3-Clause
 * @version   1.1
 * @since   	2017-07-20
 */
namespace Powerpanel\QuickLinks\Models;
use Illuminate\Database\Eloquent\Model;
use DB;
use App\CommonModel;
use Cache;
class QuickLinks extends Model {
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'quicklinks';
    protected $fillable = [
        'id',
        'fkMainRecord',
        'fkIntPageId',
        'fkModuleId',
        'varSector',
        'varTitle',
        'varExtLink',
        'varLinkType',
        'intDisplayOrder',
        'txtDescription',
        'chrMain',
        'chrAddStar',
        'chrPublish',
        'chrDelete',
        'chrApproved',
        'intApprovedBy',
        'UserID',
        'intSearchRank',
        'dtDateTime',
        'dtEndDateTime',
        'chrRollBack',
        'chrDraft',
        'intSearchRank',
        'chrTrash',
        'FavoriteID',
        'LockUserID', 'chrLock',
        'created_at',
        'updated_at'
    ];
    /**
     * This method handels retrival of home banner record count
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function externalLinksCount() {
        $response = false;
        $moduleFields = ['id'];
        $response = Self::getPowerPanelRecords($moduleFields)
                ->checkExternalLinkType()
                ->deleted()
                ->count();
        return $response;
    }
    /**
     * This method handels retrival of inner banner record count
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function internalLinksCount() {
        $response = false;
        $moduleFields = ['id'];
        $response = Self::getPowerPanelRecords($moduleFields)
                ->checkInternalLinkType()
                ->deleted()
                ->count();
        return $response;
    }
    /**
     * This method handels retrival of record count
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getRecordList($filterArr = false, $isAdmin = false, $userRoleSector = false) {
        $response = false;
        $moduleFields = [
            'id',
            'fkMainRecord',
            'varTitle',
            'varSector',
            'varExtLink',
            'varLinkType',
            'chrPublish',
            'fkIntPageId',
            'fkModuleId',
            'intDisplayOrder',
            'dtDateTime',
            'dtEndDateTime',
            'chrAddStar',
            'chrDraft',
            'intSearchRank',
            'chrTrash',
            'FavoriteID',
            'LockUserID', 
            'chrLock',
            'created_at',
            'updated_at'
        ];
        $pageFields = ['id', 'varTitle'];
        $mdlFields = ['id', 'varTitle'];
        $response = Self::getPowerPanelRecords($moduleFields, $pageFields, $mdlFields)
                ->deleted();
        $response = $response->filter($filterArr)
                ->checkMainRecord('Y')
                ->where('chrTrash', '!=', 'Y');
                if(!$isAdmin){
                    $response = $response->where('varSector', $userRoleSector);
                }
                $response = $response->get();
        return $response;
    }
    /**
     * This method handels retrival of record count
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    
    public static function getQuickLinks($sdate,$edate) {

        $response = false;
       
        $moduleFields = ['id',
            'varTitle','varSector',
            'varExtLink',
            'varLinkType',
            'chrPublish',
            'fkIntPageId',
            'fkModuleId',
            'intDisplayOrder',
            'dtDateTime',
            'dtEndDateTime',
            'chrAddStar',
            'chrDraft',
            'chrTrash',
            'intSearchRank',
            'FavoriteID',
            'LockUserID', 'chrLock',
            'created_at',
            'updated_at'];
        

        if (empty($response)) {

            $response = Self::getFrontRecords($moduleFields, false)->where('chrMain', 'Y');
            if ($sdate != '' && $edate != '') {
                $response = $response->whereRaw('(DATE(dtDateTime)>="' . date('Y-m-d', strtotime(str_replace('/', '-', $sdate))) . '" AND (DATE(dtDateTime)<="' . date('Y-m-d', strtotime(str_replace('/', '-', $edate))) . '") OR ("' . date('Y-m-d', strtotime(str_replace('/', '-', $sdate))) . '" >= dtDateTime and dtEndDateTime is null))');
            } else if ($sdate != '') {
                $response = $response->whereRaw('DATE(dtDateTime)>="' . date('Y-m-d', strtotime(str_replace('/', '-', $sdate))) . '"');
            } else if ($edate != '') {
                $response = $response->whereRaw('DATE(dtDateTime)<="' . date('Y-m-d', strtotime(str_replace('/', '-', $edate))) . '"');
            }
            $response = $response->where('chrTrash', '!=', 'Y')
                                ->deleted()
                                ->publish()
                                
                                ->where('chrDraft', '!=', 'D');
            $response = $response->get();
        }
        
        return $response;
    }
    
    public static function getRecordListFavorite($filterArr = false, $isAdmin = false, $userRoleSector) {
        $userid = auth()->user()->id;
        $response = false;
        $moduleFields = [
            'id',
            'varTitle','varSector',
            'varExtLink',
            'varLinkType',
            'chrPublish',
            'fkIntPageId',
            'fkModuleId',
            'intDisplayOrder',
            'dtDateTime',
            'dtEndDateTime',
            'chrAddStar',
            'chrDraft',
            'chrTrash',
            'intSearchRank',
            'FavoriteID',
            'LockUserID', 'chrLock',
            'created_at',
            'updated_at'
        ];
        $pageFields = ['id', 'varTitle'];
        $mdlFields = ['id', 'varTitle'];
        $response = Self::getPowerPanelRecords($moduleFields, $pageFields, $mdlFields)
                ->deleted();
        $response = $response->filter($filterArr)
                ->whereRaw("find_in_set($userid,FavoriteID)")
                ->checkMainRecord('Y')
                ->where('chrTrash', '!=', 'Y');
                if(!$isAdmin){
                    $response = $response->where('varSector', $userRoleSector);
                }
                $response = $response->get();
        return $response;
    }
    /**
     * This method handels retrival of record count
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getRecordListDraft($filterArr = false, $isAdmin = false, $userRoleSector) {
        $response = false;
        $moduleFields = [
            'id',
            'varTitle','varSector',
            'varExtLink',
            'varLinkType',
            'chrPublish',
            'fkIntPageId',
            'fkModuleId',
            'intDisplayOrder',
            'dtDateTime',
            'dtEndDateTime',
            'chrAddStar',
            'chrDraft',
            'intSearchRank',
            'chrTrash',
            'FavoriteID',
            'LockUserID', 'chrLock',
            'created_at',
            'updated_at'
        ];
        $pageFields = ['id', 'varTitle'];
        $mdlFields = ['id', 'varTitle'];
        $response = Self::getPowerPanelRecords($moduleFields, $pageFields, $mdlFields)
                ->deleted();
        $response = $response->filter($filterArr)
                ->checkMainRecord('Y')
                ->where('chrDraft', 'D')
                ->where('chrTrash', '!=', 'Y');
                if(!$isAdmin){
                    $response = $response->where('varSector', $userRoleSector);
                }
                $response = $response->get();
        return $response;
    }
    /**
     * This method handels retrival of record count
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getRecordListTrash($filterArr = false, $isAdmin = false) {
        $response = false;
        $moduleFields = [
            'id',
            'varTitle','varSector',
            'varExtLink',
            'varLinkType',
            'chrPublish',
            'fkIntPageId',
            'fkModuleId',
            'intDisplayOrder',
            'dtDateTime',
            'dtEndDateTime',
            'chrAddStar',
            'chrDraft',
            'chrTrash',
            'FavoriteID',
            'LockUserID', 'chrLock',
            'intSearchRank',
            'created_at',
            'updated_at'
        ];
        $pageFields = ['id', 'varTitle'];
        $mdlFields = ['id', 'varTitle'];
        $response = Self::getPowerPanelRecords($moduleFields, $pageFields, $mdlFields)
                ->deleted();
        $response = $response->filter($filterArr)
                ->checkMainRecord('Y')
                ->where('chrTrash', 'Y');
                if(!$isAdmin){
                    $response = $response->where('varSector', $userRoleSector);
                }
                $response = $response->get();
        return $response;
    }
    /**
     * This method handels retrival of approval tab List data
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getRecordListApprovalTab($filterArr = false, $isAdmin = false, $userRoleSector) {
        $response = false;
        $moduleFields = [
            'id',
            'fkMainRecord',
            'varTitle',
            'varSector',
            'varExtLink',
            'varLinkType',
            'chrPublish',
            'fkIntPageId',
            'fkModuleId',
            'intDisplayOrder',
            'chrPublish',
            'chrAddStar',
            'chrApproved',
            'intApprovedBy',
            'UserID',
            'chrRollBack',
            'dtDateTime',
            'dtEndDateTime',
            'chrAddStar',
            'chrDraft',
            'chrTrash',
            'FavoriteID',
            'LockUserID', 'chrLock',
            'intSearchRank',
            'created_at',
            'updated_at'
        ];
        $MainIDs = Self::distinct()
                ->select("fkMainRecord")
                ->where('chrTrash', '!=', 'Y')
                ->groupBy('fkMainRecord')
                ->deleted()
                ->get()
                ->toArray();
        $MainIDs = array_column($MainIDs, 'fkMainRecord');
        $response = Self::getPowerPanelRecords($moduleFields)
                ->deleted()
                ->where('chrAddStar', 'Y')
                ->where('chrTrash', '!=', 'Y');
                if(!$isAdmin){
                    $response = $response->where('varSector', $userRoleSector);
                }
                $response = $response->checkStarRecord('Y')
                ->filter($filterArr)
                ->whereIn('id', $MainIDs)
                ->get();
        return $response;
    }
    /**
     * This method handle to child grid.
     * @since   26-Sep-2018
     * @author  NetQuick Team
     */
    public static function getChildGrid($id = false) {
        $response = false;
        if (!empty($id)) {
            $moduleFields = [
                'id',
                'varTitle','varSector',
                'varExtLink',
                'varLinkType',
                'chrPublish',
                'fkIntPageId',
                'fkModuleId',
                'intDisplayOrder',
                'chrPublish',
                'fkMainRecord',
                'chrAddStar',
                'UserID',
                'chrApproved',
                'intApprovedBy',
                'chrPublish',
                'created_at',
                'dtDateTime',
                'dtEndDateTime',
                'chrDraft',
                'chrTrash',
                'FavoriteID',
                'intSearchRank',
                'dtApprovedDateTime',
                'created_at',
                'updated_at'
            ];
            $response = Self::getPowerPanelRecords($moduleFields)
                    ->deleted()
                    ->checkMainRecord('N')
                    ->where('fkMainRecord', $id)
                    ->orderBy('created_at', 'desc')
                    ->get();
        }
        return $response;
    }
    public static function getChildrollbackGrid($request) {
        $id = $request->id;
        $response = false;
        $moduleFields = [
            'id',
            'varTitle','varSector',
            'varExtLink',
            'varLinkType',
            'chrPublish',
            'fkIntPageId',
            'fkModuleId',
            'intDisplayOrder',
            'chrPublish',
            'fkMainRecord',
            'chrAddStar',
            'UserID',
            'chrApproved',
            'intApprovedBy',
            'dtDateTime',
            'dtEndDateTime',
            'chrPublish',
            'chrDraft',
            'chrTrash',
            'FavoriteID',
            'intSearchRank',
            'dtApprovedDateTime',
            'created_at',
            'updated_at'
        ];
        $response = Self::getPowerPanelRecords($moduleFields)
                ->deleted()
                ->where('chrMain', 'N')
                ->where('chrRollBack', 'Y')
                ->where('fkMainRecord', $id)
                ->orderBy('created_at', 'desc')
                ->get();
        return $response;
    }
    /**
     * This method handels retrival of record
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getRecordById($id = false, $ignoreDeleteScope = false) {
        $response = false;
        $moduleFields = [
            'id',
            'varTitle','varSector',
            'fkModuleId',
            'varLinkType',
            'varExtLink',
            'txtDescription',
            'intDisplayOrder',
            'chrPublish',
            'fkIntPageId',
            'fkMainRecord',
            'chrMain',
            'chrAddStar',
            'chrApproved',
            'intApprovedBy',
            'UserID',
            'chrRollBack',
            'dtDateTime',
            'dtEndDateTime',
            'chrDraft',
            'chrTrash',
            'FavoriteID',
            'created_at',
            'intSearchRank',
            'LockUserID', 'chrLock',
            'updated_at'
        ];
        $pageFields = ['id'];
        $response = Self::getPowerPanelRecords($moduleFields, $pageFields);
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
            'fkMainRecord',
            'fkIntPageId',
            'fkModuleId',
            'varTitle','varSector',
            'varExtLink',
            'varLinkType',
            'intDisplayOrder',
            'txtDescription',
            'chrMain',
            'chrAddStar',
            'chrPublish',
            'chrDelete',
            'chrApproved',
            'intApprovedBy',
            'dtDateTime',
            'dtEndDateTime',
            'UserID',
            'chrRollBack',
            'chrDraft',
            'chrTrash',
            'FavoriteID',
            'LockUserID', 'chrLock',
            'created_at',
            'updated_at',
            'intSearchRank'
        ];
        $response = Self::getPowerPanelRecords($moduleFields)
                ->deleted()
                ->checkRecordId($id)
                ->first();
        return $response;
    }
    /**
     * This method handels retrival of record count
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getRecordCount($filterArr = false, $returnCounter = false, $isAdmin = false, $userRoleSector,$flag = 'Y') {
        $response = 0;
        $moduleFields = ['id'];
        $pageQuery = Self::getPowerPanelRecords($moduleFields);
        if ($filterArr != false) {
            $pageQuery = $pageQuery->filter($filterArr, $returnCounter);
        }
        $response = $pageQuery->deleted()
                ->checkMainRecord($flag);
                if(!$isAdmin){
                    $response = $response->where('varSector', $userRoleSector);
                }
                $response = $response->count();
        return $response;
    }
    /**
     * This method handels retrival of record count
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getRecordCountforList($filterArr = false, $returnCounter = false, $isAdmin = false, $userRoleSector) {
        $response = 0;
        $moduleFields = ['id'];
        $response = Self::getPowerPanelRecords($moduleFields);
        if ($filterArr != false) {
            $response = $response->filter($filterArr, $returnCounter);
        }
        $response = $response->deleted()
                ->checkMainRecord('Y');
                if(!$isAdmin){
                    $response = $response->where('varSector', $userRoleSector);
                }
                $response = $response->where('chrTrash', '!=', 'Y')
                ->count();
        return $response;
    }
    /**
     * This method handels retrival of record count
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getRecordCountListApprovalTab($filterArr = false, $isAdmin = false, $userRoleSector) {
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
                ->whereIn('id', $MainIDs);
                if(!$isAdmin){
                    $response = $response->where('varSector', $userRoleSector);
                }
                $response = $response->checkStarRecord('Y')
                 ->where('chrTrash', '!=', 'Y')
                ->count();
        return $response;
    }
    public static function approved_data_Listing($request) {
        $id = $request->id;
        $main_id = $request->main_id;
        // $PUserid = $request->PUserid;
        //Select Child Record Data Start
        $response = false;
        $moduleFields = [
            'id',
            'fkIntPageId',
            'fkModuleId',
            'varTitle','varSector',
            'varExtLink',
            'varLinkType',
            'intDisplayOrder',
            'dtDateTime',
            'dtEndDateTime',
            'chrPublish',
            'intSearchRank',
            'chrDraft',
            'chrTrash',
            'FavoriteID',
            'created_at',
            'updated_at',
        ];
        $response = Self::getPowerPanelRecords($moduleFields)
                ->deleted()
                ->where('chrMain', 'N')
                ->where('id', $id)
                ->orderBy('created_at', 'desc')
                ->first();
        //Select Child Record Data END
        //Update Copy Child Record To Main Record start
        $whereConditions = ['id' => $main_id];
        $updateMainRecord = [
            'varTitle' => $response['varTitle'],
            'varSector' => $response['varSector'],
            'varLinkType' => $response['varLinkType'],
            'fkIntPageId' => $response['fkIntPageId'],
            'fkModuleId' => $response['fkModuleId'],
            'varExtLink' => $response['varExtLink'],
            'dtDateTime' => $response['dtDateTime'],
            'dtEndDateTime' => $response['dtEndDateTime'],
            'chrDraft' => $response['chrDraft'],
            'intSearchRank' => $response['intSearchRank'],
            'FavoriteID' => $response['FavoriteID'],
            'chrPublish' => $response['chrPublish'],
            'intSearchRank' => $response['intSearchRank'],
        ];
        $updateMainRecord['chrAddStar'] = 'N';
        CommonModel::updateRecords($whereConditions, $updateMainRecord,false,'Powerpanel\QuickLinks\Models\QuickLinks');
        //Update Copy Child Record To Main Record end
        $whereConditions_ApproveN = ['fkMainRecord' => $main_id];
        $updateToApproveN = [
            'chrApproved' => 'N',
            'chrLetest' => 'N',
            'intApprovedBy' => '0',
        ];
        CommonModel::updateRecords($whereConditions_ApproveN, $updateToApproveN,false,'Powerpanel\QuickLinks\Models\QuickLinks');
        $whereConditionsApprove = ['id' => $id, 'chrMain' => 'N'];
        $updateToApprove = [
            'chrApproved' => 'Y',
            'chrRollBack' => 'Y',
            'intApprovedBy' => auth()->user()->id,
            'dtApprovedDateTime' => date('Y-m-d H:i:s')
        ];
        CommonModel::updateRecords($whereConditionsApprove, $updateToApprove,false,'Powerpanel\QuickLinks\Models\QuickLinks');
        $msg_show = "Record successfully approved.";
        return $msg_show;
    }
    /**
     * This method handels retrival of record order of AprovalData
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getOrderOfApproval($id) {
        $result = Self::select('intDisplayOrder')
                ->checkRecordId($id)
                ->first();
        return $result;
    }
    /**
     * This method handels record count for new record approvel
     * @return  Object
     * @since   2018-09-26
     * @author  NetQuick
     */
    public static function getNewRecordsCount($isAdmin=false, $userRoleSector) {
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
                ->whereIn('id', $MainIDs);
                if(!$isAdmin){
                    $response = $response->where('varSector', $userRoleSector);
                }
                $response = $response->checkStarRecord('Y')
                 ->where('chrTrash', '!=', 'Y')
                ->count();
        return $response;
    }
    /**
     * This method handels record Latest Record Count
     * @return  Object
     * @since   2018-09-26
     * @author  NetQuick
     */
    public static function getRecordCount_letest($Main_id, $id) {
        $moduleFields = ['chrLetest'];
        $response = Self::getPowerPanelRecords($moduleFields)
                ->deleted()
                ->checkMainRecord('N')
                ->checkLatest('Y')
                ->where('fkMainRecord', $Main_id)
                ->where('id', '!=', $id)
                ->where('chrApproved', 'N')
                ->count();
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
            'intDisplayOrder'
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
     * This method handels retrival of record for notification
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getRecordNotify($id = false) {
        $response = false;
        $moduleFields = ['varTitle','varSector',];
        $response = Self::getPowerPanelRecords($moduleFields)
                ->deleted()
                ->checkRecordId($id)
                ->first();
        return $response;
    }
    #Database Configurations========================================
    /**
     * This method handels retrival of front end records
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    static function getFrontRecords($moduleFields = false, $mdlFields = false) {
        $data = [];
        $response = false;
        $response = self::select($moduleFields);
        if ($mdlFields != false) {
            $data['modules'] = function ($query) use ($mdlFields) {
                $query->select($mdlFields);
            };
        }
        if (count($data) > 0) {
            $response = $response->with($data);
        }
        return $response;
    }
    /**
     * This method handels retrival of backednd records
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    static function getPowerPanelRecords($moduleFields = false, $pageFields = false, $mdlFields = false) {
        $data = [];
        $response = false;
        $response = self::select($moduleFields);

        $data['child'] = function ($query) {
            $query->select(['id','varTitle','fkMainRecord'])
                    ->where('chrDelete', 'N')
                    ->where('dtApprovedDateTime','!=',NULL);
        };

        if ($pageFields != false) {
            $data['pages'] = function ($query) use ($pageFields) {
                $query->select($pageFields);
            };
        }
        if ($mdlFields != false) {
            $data['modules'] = function ($query) use ($mdlFields) {
                $query->select($mdlFields);
            };
        }
        if (count($data) > 0) {
            $response = $response->with($data);
        }
        return $response;
    }

    public function child() {
		return $this->hasMany('Powerpanel\QuickLinks\Models\QuickLinks', 'fkMainRecord', 'id');
	}

    /**
     * This method handels pages relation
     * @return  Object
     * @since   2017-07-20
     */
    public function pages() {
        $response = false;
        $response = $this->belongsTo('Powerpanel\CmsPage\Models\CmsPage', 'fkIntPageId', 'id');
        return $response;
    }
    /**
     * This method handels pages relation
     * @return  Object
     * @since   2017-07-20
     */
    public function modules() {
        $response = false;
        $response = $this->belongsTo('App\Modules', 'fkModuleId', 'id');
        return $response;
    }
    /**
     * This method handels retrival of banners records
     * @return  Object
     * @since   2016-07-20
     */
    static function getRecords() {
        $response = false;
        $response = self::with(['pages']);
        return $response;
    }
    /**
     * This method handels record id scope
     * @return  Object
     * @since   2016-07-24
     */
    function scopeCheckRecordId($query, $id) {
        $response = false;
        $response = $query->where('id', $id);
        return $response;
    }
    function scopeCheckByPageId($query, $id) {
        $response = false;
        $response = $query->where('fkIntPageId', $id);
        return $response;
    }
    /**
     * This method handels order scope
     * @return  Object
     * @since   2016-07-20
     */
    function scopeOrderCheck($query, $order) {
        $response = false;
        $response = $query->where('intDisplayOrder', $order);
        return $response;
    }
    /**
     * This method handels publish scope
     * @return  Object
     * @since   2016-07-20
     */
    function scopePublish($query) {
        $response = false;
        $response = $query->where(['chrPublish' => 'Y']);
        return $response;
    }
    /**
     * This method handels delete scope
     * @return  Object
     * @since   2016-07-20
     */
    function scopeDeleted($query) {
        $response = false;
        $response = $query->where(['chrDelete' => 'N']);
        return $response;
    }
    /**
     * This method handels banner type scope
     * @return  Object
     * @since   2017-08-08
     */
    function scopeLinkType($query, $type = null) {
        $response = false;
        $response = $query->where(['varLinkType' => $type]);
        return $response;
    }
    /**
     * This method checking banner type
     * @return  Object
     * @since   2016-07-20
     */
    function scopeCheckExternalLinkType($query) {
        $response = false;
        $response = $query->where(['varLinkType' => 'external']);
        return $response;
    }
    /**
     * This method checking banner type
     * @return  Object
     * @since   2016-07-14
     */
    function scopeCheckInternalLinkType($query) {
        $response = false;
        $response = $query->where(['varBannerType' => 'internal']);
        return $response;
    }
    /**
     * This method checking default banner
     * @return  Object
     * @since   2016-07-14
     */
    function scopeDisplayOrderBy($query, $orderBy) {
        $response = false;
        $response = $query->orderBy('intDisplayOrder', $orderBy);
        return $response;
    }
    function scopeCheckModuleId($query, $moduleId) {
        $response = false;
        $response = $query->where('fkModuleId', $moduleId);
        return $response;
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
     * This method handels Latest Record scope
     * @return  Object
     * @since   2016-07-24
     * @author  NetQuick
     */
    public function scopeCheckLatest($query, $flag = 'N') {
        $response = false;
        $response = $query->where('chrLetest', "=", $flag);
        return $response;
    }
    /**
     * This method handels filter scope
     * @return  Object
     * @since   2016-07-14
     */
    function scopeFilter($query, $filterArr = false, $retunTotalRecords = false) {
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
        if (!empty($filterArr['sectorFilter']) && $filterArr['sectorFilter'] != ' ') {
            $data = $query->where('varSector', $filterArr['sectorFilter']);
        }
        if (!empty($filterArr['searchFilter']) && $filterArr['searchFilter'] != ' ') {
            $data = $query->where('varTitle', 'like', "%" . $filterArr['searchFilter'] . "%");
        }
        if (!empty($filterArr['linkFilterType']) && $filterArr['linkFilterType'] != ' ') {
            $data = $query->where('varLinkType', $filterArr['linkFilterType']);
        }
        if (!empty($filterArr['pageFilter']) && $filterArr['pageFilter'] != ' ') {
            $data = $query->where('fkIntPageId', '=', $filterArr['pageFilter']);
        }
        if (!empty($query)) {
            $response = $query;
        }
        return $response;
    }
    /**
     * This method handels filter scope
     * @return  Object
     * @since   2016-07-14
     */
    static function add_pages() {
        $response = false;
        $module_code = DB::table('modules')->where('var_module_name', '=', 'cms-page')->first();
        $response = DB::table('cms_pages')
                        ->select('cms_pages.*')
                        ->where('cms_pages.chr_delete', '=', 'N')
                        ->where('cms_pages.chr_publish', '=', 'Y')
                        ->groupBy('cms_pages.id')->get();
        return $response;
    }
    /**
     * This method handels retrival of record for notification
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getHomePageList($limit = 8) {
        $response = false;
        $moduleFields = ['varTitle','varSector', 'varExtLink', 'varLinkType', 'fkIntPageId', 'fkModuleId', 'intDisplayOrder'];
        $mdlFields = ['id', 'varTitle','varSector', 'varModuleName'];
        $response = Self::getFrontRecords($moduleFields, $mdlFields)
                ->deleted()
                ->publish()
                ->dateRange()
                ->checkMainRecord()
                ->orderBy('intDisplayOrder')
                ->take($limit)
                ->get();
        return $response;
    }
    public function scopeDateRange($query) {
        $response = false;
        $response = $query->whereRaw('((dtEndDateTime >= NOW() AND NOW() >= dtDateTime) OR (NOW() >= dtDateTime and dtEndDateTime is null))');
        return $response;
    }
    //Start Draft Count of Records 
    public static function getRecordCountforListDarft($filterArr = false, $returnCounter = false, $isAdmin = false, $ignoreId = array(), $userRoleSector) {
        $response = 0;
        $cmsPageFields = ['id'];
        $userid = auth()->user()->id;
        $response = Self::getPowerPanelRecords($cmsPageFields);
        if ($filterArr != false) {
            $response = $response->filter($filterArr, $returnCounter);
        }
        $response = $response->deleted()
                ->checkMainRecord('Y')
                ->whereNotIn('id', $ignoreId)
                ->where('chrDraft', 'D')
                ->where('chrTrash', '!=', 'Y');
                if(!$isAdmin){
                    $response = $response->where('varSector', $userRoleSector);
                }
                $response = $response->count();
        return $response;
    }
    //End Draft Count of Records 
    //Start Trash Count of Records 
    public static function getRecordCountforListTrash($filterArr = false, $returnCounter = false, $isAdmin = false, $ignoreId = array(),$userRoleSector) {
        $response = 0;
        $cmsPageFields = ['id'];
        $userid = auth()->user()->id;
        $response = Self::getPowerPanelRecords($cmsPageFields);
        if ($filterArr != false) {
            $response = $response->filter($filterArr, $returnCounter);
        }
        $response = $response->deleted()
                ->checkMainRecord('Y')
                ->whereNotIn('id', $ignoreId)
                ->where('chrTrash', 'Y');
                if(!$isAdmin){
                    $response = $response->where('varSector', $userRoleSector);
                }
                $response = $response->count();
        return $response;
    }
    //End Trash Count of Records 
    //Start Favorite Count of Records 
    public static function getRecordCountforListFavorite($filterArr = false, $returnCounter = false, $isAdmin = false, $ignoreId = array(), $userRoleSector) {
        $response = 0;
        $cmsPageFields = ['id'];
        $userid = auth()->user()->id;
        $response = Self::getPowerPanelRecords($cmsPageFields);
        if ($filterArr != false) {
            $response = $response->filter($filterArr, $returnCounter);
        }
        $response = $response->deleted()
                ->checkMainRecord('Y')
                ->where('chrTrash', '!=', 'Y')
                ->whereNotIn('id', $ignoreId)
                ->whereRaw("find_in_set($userid,FavoriteID)");
                if(!$isAdmin){
                    $response = $response->where('varSector', $userRoleSector);
                }
                $response = $response->count();
        return $response;
    }
    //End Favorite Count of Records 

    public static function getPreviousRecordByMainId($id) {
        $response = Self::select('id','fkMainRecord')
                        ->where('fkMainRecord', $id)
                        ->where('chrMain', 'N')
                        ->where('chrApproved', 'N')
                        ->where('chrDelete', 'N')
                        ->where('dtApprovedDateTime','!=',NULL)
                        ->orderBy('dtApprovedDateTime','DESC')
                        ->first();
        return $response;
    }
}
