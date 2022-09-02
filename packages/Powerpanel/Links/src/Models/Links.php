<?php
/**
 * The Links class handels bannner queries
 * ORM implemetation.
 * @package   Netquick powerpanel
 * @license   http://www.opensource.org/licenses/BSD-3-Clause
 * @version   1.1
 * @since       2017-07-20
 * @author    NetQuick
 */
namespace Powerpanel\Links\Models;
use Illuminate\Database\Eloquent\Model;
use Cache;
use DB;
use App\CommonModel;
class Links extends Model {
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'links';
    protected $fillable = [
        'id',
        'fkMainRecord',
        'intFKCategory',
        'varTitle',
        'varSector',
        'varExtLink','fkModuleId','fkIntPageId','varLinkType',
        'chrMain',
        'chrAddStar',
        'intDisplayOrder',
        'chrPublish',
        'chrDelete',
        'chrApproved',
        'intApprovedBy',
        'chrRollBack',
        'UserID',
        'chrAddStar',
        'dtDateTime',
        'dtEndDateTime',
        'intSearchRank',
        'chrDraft',
        'chrTrash',
        'FavoriteID',
        'LockUserID','chrLock',
        'created_at',
        'updated_at'
    ];
    /**
     * This method handels retrival of links records
     * @return  Object
     * @since   2016-07-20
     * @author  NetQuick
     */
    public static function getRecords() {
        $response = false;
        $response = Cache::tags(['Links'])->get('getLinksRecords');
        if (empty($response)) {
            $response = Self::Select(['id', 'intFKCategory', 'varTitle','varSector', 'varExtLink','fkModuleId','fkIntPageId','varLinkType', 'intDisplayOrder', 'chrPublish'])
                    ->deleted()
                    ->publish()
                    ->paginate(10);
            Cache::tags(['Links'])->forever('getLinksRecords', $response);
        }
        return $response;
    }
    /**
     * This method handels backend records
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public static function getPowerPanelRecords($moduleFields = false, $teamcatfileds = false) {
        $data = [];
        $response = false;
        $response = self::select($moduleFields);

        $data['child'] = function ($query) {
            $query->select(['id','varTitle','fkMainRecord'])
                    ->where('chrDelete', 'N')
                    ->where('dtApprovedDateTime','!=',NULL);
        };

        if ($teamcatfileds != false) {
            $data['teamcat'] = function ($query) use ($teamcatfileds) {
                $query->select($teamcatfileds)->publish();
            };
        }
        if (count($data) > 0) {
            $response = $response->with($data);
        }
        return $response;
    }

    public function child() {
		return $this->hasMany('Powerpanel\Links\Models\Links', 'fkMainRecord', 'id');
	}

    public function teamcat() {
        $response = false;
        $response = $this->belongsTo('Powerpanel\LinksCategory\Models\LinksCategory', 'intFKCategory', 'id');
        return $response;
    }
    /**
     * This method handels retrival of backend record list
     * @return  Object
     * @since   2017-10-24
     * @author  NetQuick
     */
    public static function getRecordList($filterArr = false, $isAdmin = false, $userRoleSector = false) {
        $teamcatfileds = ['id', 'varTitle'];
        $response = false;
        $moduleFields = [
            'id',
            'fkMainRecord',
            'intFKCategory',
            'varTitle',
            'varSector',
            'varExtLink',
            'fkModuleId',
            'fkIntPageId',
            'varLinkType',
            'intDisplayOrder',
            'chrPublish',
            'chrMain',
            'dtDateTime',
            'dtEndDateTime',
            'chrAddStar',
            'chrDraft',
            'chrTrash',
            'intSearchRank',
            'FavoriteID',
            'LockUserID','chrLock',
            'created_at',
            'updated_at'
        ];
        $response = Self::getPowerPanelRecords($moduleFields, $teamcatfileds)
                ->deleted();
        $response = $response->filter($filterArr)
                ->where('chrTrash', '!=', 'Y')
                ->checkMainRecord('Y');
                if(!$isAdmin){
                    $response = $response->where('varSector', $userRoleSector);
                }
                $response = $response->get();
        return $response;
    }
    /**
     * This method handels retrival of backend record list
     * @return  Object
     * @since   2017-10-24
     * @author  NetQuick
     */
    public static function getRecordListFavorite($filterArr = false, $isAdmin = false, $userRoleSector) {
        $teamcatfileds = ['id', 'varTitle'];
        $userid = auth()->user()->id;
        $response = false;
        $moduleFields = [
            'id',
            'intFKCategory',
            'varTitle',
            'varSector',
            'varExtLink','fkModuleId','fkIntPageId','varLinkType',
            'intDisplayOrder',
            'chrPublish',
            'chrMain',
            'dtDateTime',
            'dtEndDateTime',
            'chrAddStar',
            'chrDraft',
            'chrTrash',
            'FavoriteID',
            'intSearchRank',
            'LockUserID','chrLock',
            'created_at',
            'updated_at'
        ];
        $response = Self::getPowerPanelRecords($moduleFields, $teamcatfileds)
                ->deleted();
        $response = $response->filter($filterArr)
                ->checkMainRecord('Y')
                ->where('chrTrash', '!=', 'Y')
                ->whereRaw("find_in_set($userid,FavoriteID)");
                if(!$isAdmin){
                    $response = $response->where('varSector', $userRoleSector);
                }
                $response = $response->get();
        return $response;
    }
    /**
     * This method handels retrival of backend record list
     * @return  Object
     * @since   2017-10-24
     * @author  NetQuick
     */
    public static function getRecordListDraft($filterArr = false, $isAdmin = false, $userRoleSector) {
        $teamcatfileds = ['id', 'varTitle'];
        $response = false;
        $moduleFields = [
            'id',
            'intFKCategory',
            'varTitle',
            'varSector',
            'varExtLink','fkModuleId','fkIntPageId','varLinkType',
            'intDisplayOrder',
            'chrPublish',
            'chrMain',
            'dtDateTime',
            'dtEndDateTime',
            'chrAddStar',
            'chrDraft',
            'chrTrash',
            'FavoriteID',
            'intSearchRank',
            'LockUserID','chrLock',
            'created_at',
            'updated_at'
        ];
        $response = Self::getPowerPanelRecords($moduleFields, $teamcatfileds)
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
     * This method handels retrival of backend record list
     * @return  Object
     * @since   2017-10-24
     * @author  NetQuick
     */
    public static function getRecordListTrash($filterArr = false, $isAdmin = false) {
        $teamcatfileds = ['id', 'varTitle'];
        $response = false;
        $moduleFields = [
            'id',
            'intFKCategory',
            'varTitle',
            'varSector',
            'varExtLink','fkModuleId','fkIntPageId','varLinkType',
            'intDisplayOrder',
            'chrPublish',
            'chrMain',
            'dtDateTime',
            'dtEndDateTime',
            'chrAddStar',
            'chrDraft',
            'chrTrash',
            'FavoriteID',
            'intSearchRank',
            'LockUserID','chrLock',
            'created_at',
            'updated_at'
        ];
        $response = Self::getPowerPanelRecords($moduleFields, $teamcatfileds)
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
    public static function getRecordList_tab1($filterArr = false, $isAdmin = false, $userRoleSector) {
        $response = false;
        $moduleFields = [
            'id',
            'intFKCategory',
            'varTitle',
            'varSector',
            'varExtLink',
            'fkModuleId',
            'fkIntPageId',
            'varLinkType',
            'intDisplayOrder',
            'chrPublish',
            'fkMainRecord',
            'chrAddStar',
            'dtDateTime',
            'dtEndDateTime',
            'chrDraft',
            'chrTrash',
            'FavoriteID',
            'intSearchRank',
            'LockUserID',
            'chrLock',
            'created_at',
            'updated_at'
        ];
        $teamcatfileds = ['id', 'varTitle'];
        $MainIDs = Self::distinct()
                ->select("fkMainRecord")
                ->checkMainRecord('N')
                ->groupBy('fkMainRecord')
                ->deleted()
                ->get()
                ->toArray();
        $MainIDs = array_column($MainIDs, 'fkMainRecord');
        $response = Self::getPowerPanelRecords($moduleFields, $teamcatfileds)
                ->deleted()
                ->where('chrAddStar', 'Y')
                ->filter($filterArr)
                ->whereIn('id', $MainIDs)
                ->where('chrTrash', '!=', 'Y');
                if(!$isAdmin){
                    $response = $response->where('varSector', $userRoleSector);
                }
                $response = $response->checkStarRecord('Y')
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
        $moduleFields = ['id',
            'intFKCategory',
            'varTitle',
            'varSector',
            'intSearchRank',
            'varExtLink','fkModuleId','fkIntPageId','varLinkType',
            'intDisplayOrder',
            'chrPublish',
            'fkMainRecord',
            'chrAddStar',
            'UserID',
            'dtDateTime',
            'dtEndDateTime',
            'chrDraft',
            'chrTrash',
            'FavoriteID',
            'intSearchRank',
            'LockUserID','chrLock',
            'created_at',
            'updated_at'
        ];
        $response = Self::getPowerPanelRecords($moduleFields);
        if (!$ignoreDeleteScope) {
            $response = $response->deleted();
        }
        $response = $response->checkRecordId($id)->first();
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
            'intFKCategory',
            'varTitle',
            'varSector',
            'intSearchRank',
            'varExtLink','fkModuleId','fkIntPageId','varLinkType',
            'intDisplayOrder',
            'chrPublish',
            'fkMainRecord',
            'UserID',
            'dtDateTime',
            'dtEndDateTime',
            'chrDraft',
            'chrTrash',
            'FavoriteID',
            'intSearchRank',
            'LockUserID','chrLock',
            'created_at',
            'updated_at'
        ];
        $response = Self::getPowerPanelRecords($moduleFields)->deleted()->checkRecordId($id)->first();
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
    public static function getOrderOfApproval($id) {
        $result = Self::select('intDisplayOrder')
                ->checkRecordId($id)
                ->first();
        return $result;
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
        if (!empty($filterArr['orderByFieldName']) && !empty($filterArr['orderTypeAscOrDesc'])) {
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
        if (!empty($filterArr['catFilter']) && $filterArr['catFilter'] != ' ') {
            $data = $query->where('intFKCategory', $filterArr['catFilter']);
        }
        if (!empty($filterArr['searchFilter']) && $filterArr['searchFilter'] != ' ') {
            $data = $query->where('varTitle', 'like', "%" . $filterArr['searchFilter'] . "%");
        }
        if (isset($filterArr['ignore']) && !empty($filterArr['ignore'])) {
            $data = $query->whereNotIn('id', $filterArr['ignore']);
        }
        if (!empty($filterArr['rangeFilter']['from']) && $filterArr['rangeFilter']['to']) {
            $data = $query->whereRaw('DATE(dtDateTime) BETWEEN "' . date('Y-m-d', strtotime(str_replace('/', '-', $filterArr['rangeFilter']['from']))) . '" AND "' . date('Y-m-d', strtotime(str_replace('/', '-', $filterArr['rangeFilter']['to']))) . '"');
        }
        if (!empty($query)) {
            $response = $query;
        }
        return $response;
    }
    public static function getRecordCount($filterArr = false, $returnCounter = false, $isAdmin = false, $userRoleSector) {
        $response = 0;
        $cmsPageFields = ['id'];
        $pageQuery = Self::getPowerPanelRecords($cmsPageFields);
        if ($filterArr != false) {
            $pageQuery = $pageQuery->filter($filterArr, $returnCounter);
        }
        $response = $pageQuery
                ->deleted()
                ->where('chrMain', 'Y');
                if(!$isAdmin){
                    $response = $response->where('varSector', $userRoleSector);
                }
                $response = $response->count();
        return $response;
    }
    /**
     * This method handels retrival of record count
     * @return  Object
     * @since   2018-10-08
     * @author  NetQuick
     */
    public static function getRecordCountforList($filterArr = false, $returnCounter = false, $isAdmin = false, $userRoleSector) {
        $response = 0;
        $cmsPageFields = ['id'];
        $response = Self::getPowerPanelRecords($cmsPageFields);
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
    public static function getChildGrid() {
        $teamcatfileds = ['id', 'varTitle'];
        $id = $_REQUEST['id'];
        $response = false;
        $moduleFields = ['id', 'intFKCategory', 'varTitle','varSector', 'varExtLink','fkModuleId','fkIntPageId','varLinkType', 'intDisplayOrder', 'chrPublish', 'fkMainRecord', 'created_at', 'chrApproved', 'updated_at', 'intSearchRank', 'intApprovedBy', 'UserID', 'chrDraft', 'chrTrash', 'FavoriteID', 'dtApprovedDateTime', 'created_at', 'updated_at'];
        $response = Self::getPowerPanelRecords($moduleFields, $teamcatfileds)->deleted()->where('chrMain', 'N')->where('fkMainRecord', $id)->orderBy('created_at', 'desc')->get();
        return $response;
    }
    public static function getChildrollbackGrid() {
        $teamcatfileds = ['id', 'varTitle'];
        $id = $_REQUEST['id'];
        $response = false;
        $moduleFields = ['id', 'intFKCategory', 'varTitle','varSector', 'varExtLink','fkModuleId','fkIntPageId','varLinkType', 'intDisplayOrder', 'chrPublish', 'fkMainRecord', 'created_at', 'chrApproved', 'updated_at', 'intSearchRank', 'intApprovedBy', 'UserID', 'chrDraft', 'chrTrash', 'FavoriteID', 'dtApprovedDateTime', 'created_at', 'updated_at'];
        $response = Self::getPowerPanelRecords($moduleFields, $teamcatfileds)->deleted()->where('chrMain', 'N')->where('chrRollBack', 'Y')->where('fkMainRecord', $id)->orderBy('created_at', 'desc')->get();
        return $response;
    }
    public static function approved_data_Listing($request) {
         $id = $request->id;
        $main_id = $request->main_id;
        // $PUserid = $request->PUserid;
        //Select Child Record Data Start
        $teamcatfileds = ['id', 'varTitle'];
        $response = false;
        $moduleFields = [
            'id',
            'intFKCategory',
            'varTitle',
            'varSector',
            'intSearchRank',
            'varExtLink','fkModuleId','fkIntPageId','varLinkType',
            'intDisplayOrder',
            'chrPublish',
            'dtDateTime',
            'dtEndDateTime',
            'chrDraft',
            'chrTrash',
            'FavoriteID',
            'created_at',
            'updated_at'
        ];
        $aliasFields = ['id', 'varAlias'];
        $response = Self::getPowerPanelRecords($moduleFields, $teamcatfileds)->deleted()->where('chrMain', 'N')->where('id', $id)->orderBy('created_at', 'desc')->first();
        //Select Child Record Data END
        //Update Copy Child Record To Main Record start
        $whereConditions = ['id' => $main_id];
        $updateMainRecord = [
            'varTitle' => $response['varTitle'],
            'intFKCategory' => $response['intFKCategory'],
            'varSector' => $response['varSector'],
            'intSearchRank' => $response['intSearchRank'],
            'dtDateTime' => $response['dtDateTime'],
            'dtEndDateTime' => $response['dtEndDateTime'],
            'varExtLink' => $response['varExtLink'],
            'fkIntPageId' => $response['fkIntPageId'],
            'fkModuleId' => $response['fkModuleId'],
            'varLinkType' => $response['varLinkType'],
            'chrAddStar' => 'N',
            'chrDraft' => $response['chrDraft'],
            'intSearchRank' => $response['intSearchRank'],
            'FavoriteID' => $response['FavoriteID'],
            'chrPublish' => $response['chrPublish'],
        ];
        CommonModel::updateRecords($whereConditions, $updateMainRecord,false,'Powerpanel\Links\Models\Links');
        //Update Copy Child Record To Main Record end
        $whereConditions_ApproveN = ['fkMainRecord' => $main_id];
        $updateToApproveN = [
            'chrApproved' => 'N',
            'chrLetest' => 'N',
            'intApprovedBy' => '0',
        ];
        CommonModel::updateRecords($whereConditions_ApproveN, $updateToApproveN,false,'Powerpanel\Links\Models\Links');
        $whereConditionsApprove = ['id' => $id, 'chrMain' => 'N'];
        $updateToApprove = [
            'chrApproved' => 'Y',
            'chrRollBack' => 'Y',
            'intApprovedBy' => auth()->user()->id,
            'dtApprovedDateTime' => date('Y-m-d H:i:s')
        ];
        CommonModel::updateRecords($whereConditionsApprove, $updateToApprove,false,'Powerpanel\Links\Models\Links');
        $msg_show = "Record successfully approved.";
        return $msg_show;
    }
    public static function getCountById($categoryId = null) {
        $response = false;
        $moduleFields = ['id'];
        $response = Self::getPowerPanelRecords($moduleFields)
                ->checkCategoryId($categoryId)
                ->deleted()
                ->where('chrMain', 'Y')
                ->count();
        return $response;
    }
    public function scopeCheckCategoryId($query, $id) {
        $response = false;
        $response = $query->where('intFKCategory', '=', $id);
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
    
    public static function getBuilderRecordList($filterArr = []) {
        $response = false;
         $moduleFields = [
            'id',
            'intFKCategory',
            'varTitle',
            'varSector',
            'intSearchRank',
            'varExtLink','fkModuleId','fkIntPageId','varLinkType',
            'intDisplayOrder',
            'chrPublish',
            'dtDateTime',
            'dtEndDateTime',
            'chrDraft',
            'chrTrash',
            'FavoriteID',
            'created_at',
            'updated_at'
        ];
        $response = Self::getPowerPanelRecords($moduleFields, false, false, false, false)
                ->filter($filterArr);
        $response = $response->where('chrPublish', 'Y')
                ->where('chrDelete', 'N')
                ->where('chrMain', 'Y')
                ->where('chrTrash', '!=', 'Y')
                ->where('chrDraft', '!=', 'D')
                ->groupBy('id')
                ->get();
        return $response;
    }

    public static function getFrontList($filterArr = false, $page = 1, $catid = false, $print = false, $categoryid, $name = "", $start_date_time = "", $end_date_time = "")
    {
        $response = false;
        $moduleFields = [
            'id',
            'fkMainRecord',
            'intFKCategory',
            'varTitle','varSector',
            'varExtLink','fkModuleId','fkIntPageId','varLinkType',
            'chrMain',
            'chrAddStar',
            'intDisplayOrder',
            'chrPublish',
            'chrDelete',
            'chrApproved',
            'intApprovedBy',
            'chrRollBack',
            'UserID',
            'chrAddStar',
            'dtDateTime',
            'dtEndDateTime',
            'intSearchRank',
            'chrDraft',
            'chrTrash',
            'FavoriteID',
            'LockUserID','chrLock',
            'created_at',
            'updated_at'
        ];
        $aliasFields = ['id'];
        if (empty($response)) {
            $response = Self::getFrontRecords($moduleFields, $aliasFields)
                ->deleted()
                ->publish();
            if ($categoryid != '') {
                $response = $response->whereRaw(DB::raw('intFkCategory="' . $categoryid . '"'));
            }
            if ($name != '') {
                $response = $response->where('varTitle', 'like', '%' . '' . $name . '' . '%');
            }
            if ($start_date_time != '' && $end_date_time != '') {
                $response = $response->whereRaw('(dtDateTime>="' . $start_date_time . '" AND (dtDateTime<="' . $end_date_time . '"))');
            } else if ($start_date_time != '') {
                $response = $response->whereRaw('dtDateTime>="' . $start_date_time . '"')->dateRange();
            } else if ($end_date_time != '') {
                $response = $response->whereRaw('dtDateTime<="' . $end_date_time . '"')->dateRange();
            } else {
                $response = $response->dateRange();
            }
            $response = $response->where('chrTrash', '!=', 'Y')
                ->where('chrDraft', '!=', 'D')
                ->orderBy('dtDateTime', 'DESC')
                ->where('chrMain', 'Y');
            if ($catid != false) {
                $response = $response->where('intFkCategory', '=', $catid);
            }
            if ($print == 'print') {
                $response = $response->get();
            } else {
                $response = $response->paginate($page);
            }
        }
        return $response;
    }
    
      public function modules() {
        $response = false;
        $response = $this->belongsTo('App\Modules', 'fkModuleId', 'id');
        return $response;
    }
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
    
     public static function getBuilderLinks($recIds) {
          $response = false;
       
        $linkModuleItemFields = [
            'id',
            'varTitle','varSector',
            'varExtLink','fkModuleId','fkIntPageId','varLinkType',
            'intFKCategory',
            'fkMainRecord',
            'chrDraft',
            'chrTrash',
            'FavoriteID',
            'intSearchRank',
            'created_at',
            'updated_at'
        ];
         $mdlFields = ['id', 'varTitle','varSector', 'varModuleName', 'varTableName', 'varModelName', 'varModuleClass'];
        if (empty($response)) {
            $response = Self::getFrontRecords($linkModuleItemFields, $mdlFields)
                    ->whereIn('id', $recIds)
                    ->deleted()
                    ->publish()
                    ->where('chrMain', 'Y')
                    ->where('chrTrash', '!=', 'Y')
                    ->where('chrDraft', '!=', 'D')
                    ->orderByRaw(DB::raw("FIELD(id, " . implode(',', $recIds) . " )"))
                    ->get();
        }
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
