<?php

namespace Powerpanel\Interconnections\Models;

use App\CommonModel;
use Cache;
use Request;
use DB;
use Illuminate\Database\Eloquent\Model;

class Interconnections extends Model
{

    protected $table = 'interconnections';
    protected $fillable = [
        'id',
        'varSector',
        'varTitle',
        'intParentCategoryId',
        'intDisplayOrder',
        'txtShortDescription',
        'dtDateTime',
        'fkIntDocId',
        'fkMainRecord',
        'intApprovedBy',
        'UserID',
        'chrMain',
        'chrAddStar',
        'chrApproved',
        'chrRollBack',
        'chrLetest',
        'chrPublish',
        'chrDelete',
        'LockUserID',
        'chrLock',
        'chrTrash',
        'FavoriteID',
        'chrDraft',
        'chrPublish',
        'created_at',
        'updated_at',
        'intSearchRank',
    ];

    /**
     * This method handels retrival of front blog detail
     * @return  Object
     * @since   2017-10-13
     * @author  NetQuick
     */
    public static function getRecordIdByAliasID($aliasID)
    {
        $response = false;
        $response = Cache::tags(['Organizations'])->get('getServiceCatRecordIdByAliasID' . $aliasID);
        if (empty($response)) {
            $response = Self::Select('id')->deleted()->publish()->checkAliasId($aliasID)->first();
            Cache::tags(['Organizations'])->forever('getServiceCatRecordIdByAliasID' . $aliasID, $response);
        }
        return $response;
    }

    /**
     * This method handels retrival of front latest organization list
     * @return  Object
     * @since   2017-10-13
     * @author  NetQuick
     */
    public static function getFrontList()
    {
        $response = false;
        $organizationFields = [
            'id',
            'varSector',
            'varTitle',
            'dtDateTime',
            'intParentCategoryId',
            'intDisplayOrder',
            'txtShortDescription',
            'fkIntDocId',
            'fkMainRecord',
            'intApprovedBy',
            'UserID',
            'chrMain',
            'chrAddStar',
            'chrApproved',
            'chrRollBack',
            'chrLetest',
            'chrPublish',
            'chrDelete',
            'LockUserID',
            'chrLock',
            'chrTrash',
            'FavoriteID',
            'chrDraft',
            'chrPublish',
            'created_at',
            'updated_at',
            'intSearchRank',
        ];
        $response = Cache::tags(['Interconnections'])->get('getFrontServiceCatList');
        if (empty($response)) {
            $response = Self::getFrontRecords($organizationFields)
                ->deleted()
                ->publish()
                ->where('chrTrash', '!=', 'Y')
                ->where('chrDraft', '!=', 'D')
                ->get()
                ->pluck('varTitle', 'id');
            Cache::tags(['Interconnections'])->forever('getFrontServiceCatList', $response);
        }
        return $response;
    }

    /**
     * This method handels retrival of organization records
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public static function getFrontRecords($moduleFields = false)
    {
        $response = false;
        $response = self::select($moduleFields);
        return $response;
    }

    /**
     * This method handels organization sub-category relation
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public function organizations()
    {
        $response = false;
        $response = $this->hasOne('Powerpanel\Interconnections\Models\Interconnections', 'id', 'intParentCategoryId');
        return $response;
    }

    /**
     * This method handels main category relation
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public function parentCategory()
    {
        $response = false;
        $response = $this->belongsTo('Powerpanel\Interconnections\Models\Interconnections', 'intParentCategoryId', 'id');
        return $response;
    }

    /**
     * This method handels retrival of records
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public static function getRecords($moduleId = false)
    {
        $response = false;
        $response = self::with(['parentCategory']);
        return $response;
    }

    public static function UpdateDisplayOrder($order, $parentRecordId = false)
    {
        $updateSql = "UPDATE nq_interconnections SET `intDisplayOrder` = `intDisplayOrder` + 1
				WHERE `intDisplayOrder` >= " . $order . "
				AND chrDelete='N'
				AND intDisplayOrder != 0 AND chrMain='Y'";
        if ((int)$parentRecordId > 0) {
            $updateSql .= " AND intParentCategoryId=$parentRecordId";
        } else {
            $updateSql .= " AND intParentCategoryId = 0";
        }
        $updateSql .= " ORDER BY `intDisplayOrder` ASC";
        DB::update(DB::raw($updateSql));
    }

    /**
     * This method handels backend records
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public static function getPowerPanelRecords($moduleFields = false, $parentCategoryFields = false)
    {
        $data = [];
        $response = false;
        $response = self::select($moduleFields);

        $data['child'] = function ($query) {
            $query->select(['id','varTitle','fkMainRecord'])
                    ->where('chrDelete', 'N')
                    ->where('dtApprovedDateTime','!=',NULL);
        };

        if ($parentCategoryFields != false) {
            $data['parentCategory'] = function ($query) use ($parentCategoryFields) {
                $query->select($parentCategoryFields);
            };
        }
        if (count($data) > 0) {
            $response = $response->with($data);
        }
        return $response;
    }

    public function child() {
		return $this->hasMany('Powerpanel\Interconnections\Models\Interconnections', 'fkMainRecord', 'id');
	}

    /**
     * This method handels retrival of backend record list
     * @return  Object
     * @since   2017-10-24
     * @author  NetQuick
     */
    public static function getRecordList($filterArr = false)
    {
        $userid = auth()->user()->id;
        $response = false;
        $moduleFields = [
            'id',
            'varSector',
            'varTitle',
            'dtDateTime',
            'txtShortDescription',
            'intParentCategoryId',
            'intDisplayOrder',
            'fkIntDocId',
            'fkMainRecord',
            'intApprovedBy',
            'UserID',
            'chrMain',
            'chrAddStar',
            'chrApproved',
            'chrRollBack',
            'chrLetest',
            'chrPublish',
            'chrDelete',
            'LockUserID',
            'chrLock',
            'chrTrash',
            'FavoriteID',
            'chrDraft',
            'chrPublish',
            'created_at',
            'updated_at',
            'intSearchRank',
        ];
        $response = Self::getPowerPanelRecords($moduleFields)
            ->deleted()
            ->filter($filterArr)
            ->where(function ($query) use ($userid) {
                $query->where("UserID", '=', $userid)->where('chrPageActive', '=', 'PR')
                    ->orWhere('chrPageActive', '!=', 'PR');
            })
            ->checkMainRecord('Y')
            ->where('chrIsPreview', 'N')
            ->where('chrTrash', '!=', 'Y')
            ->get();
        return $response;
    }

    public static function getRecordList_tab1($filterArr = false)
    {
        $response = false;
        $moduleFields = [
            'id',
            'varSector',
            'varTitle',
            'dtDateTime',
            'txtShortDescription',
            'intParentCategoryId',
            'intDisplayOrder',
            'fkIntDocId',
            'fkMainRecord',
            'intApprovedBy',
            'UserID',
            'chrMain',
            'chrAddStar',
            'chrApproved',
            'chrRollBack',
            'chrLetest',
            'chrPublish',
            'chrDelete',
            'LockUserID',
            'chrLock',
            'chrTrash',
            'FavoriteID',
            'chrDraft',
            'chrPublish',
            'created_at',
            'updated_at',
            'intSearchRank',
        ];
        $MainIDs = Self::distinct()
            ->select("fkMainRecord")
            ->checkMainRecord('N')
            ->where('chrIsPreview', 'N')
            ->groupBy('fkMainRecord')
            ->deleted()
            ->get()
            ->toArray();
        $MainIDs = array_column($MainIDs, 'fkMainRecord');
        $response = Self::getPowerPanelRecords($moduleFields, false)
            ->deleted()
            ->where('chrAddStar', 'Y')
            ->filter($filterArr)
            ->whereIn('id', $MainIDs)
            ->where('chrTrash', '!=', 'Y')
            ->checkStarRecord('Y')
            ->get();
        return $response;
    }

    public static function getRecordListFavorite($filterArr = false, $isAdmin = false, $userRoleSector)
    {
        $userid = auth()->user()->id;
        $response = false;
        $moduleFields = [
            'id',
            'varSector',
            'varTitle',
            'dtDateTime',
            'txtShortDescription',
            'intParentCategoryId',
            'intDisplayOrder',
            'fkIntDocId',
            'fkMainRecord',
            'intApprovedBy',
            'UserID',
            'chrMain',
            'chrAddStar',
            'chrApproved',
            'chrRollBack',
            'chrLetest',
            'chrPublish',
            'chrDelete',
            'LockUserID',
            'chrLock',
            'chrTrash',
            'FavoriteID',
            'chrDraft',
            'chrPublish',
            'created_at',
            'updated_at',
            'intSearchRank',
        ];
        $response = Self::getPowerPanelRecords($moduleFields)
            ->deleted()
            ->filter($filterArr)
            ->checkMainRecord('Y')
            ->where('chrIsPreview', 'N')
            ->where('chrTrash', '!=', 'Y')
            ->whereRaw("find_in_set($userid,FavoriteID)")
            ->where(function ($query) use ($userid) {
                $query->where("UserID", '=', $userid)->where('chrPageActive', '=', 'PR')
                    ->orWhere('chrPageActive', '!=', 'PR');
            });
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
    public static function getRecordListforInterconnectionsGrid($filterArr = false, $isAdmin = false, $userRoleSector)
    {
        $response = false;

        $moduleFields = [
            'id',
            'varSector',
            'varTitle',
            'dtDateTime',
            'txtShortDescription',
            'intParentCategoryId',
            'intDisplayOrder',
            'fkIntDocId',
            'fkMainRecord',
            'intApprovedBy',
            'UserID',
            'chrMain',
            'chrAddStar',
            'chrApproved',
            'chrRollBack',
            'chrLetest',
            'chrPublish',
            'chrDelete',
            'LockUserID',
            'chrLock',
            'chrTrash',
            'FavoriteID',
            'chrDraft',
            'chrPublish',
            'created_at',
            'updated_at',
            'intSearchRank',];
        $response = self::select($moduleFields);
        $response = $response->deleted()
            ->dataFilter($filterArr)
            ->checkMainRecord('Y')
            ->where('chrIsPreview', 'N')
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
    public static function getRecordListforGridbyIds($ids, $filterArr = false)
    {
        $response = false;
        $response = DB::table('interconnections AS Int')
            ->leftjoin('interconnections as Int1', 'Int.intParentCategoryId', '=', 'Int1.id')
            ->select('Int.id', 'Int.varTitle','Int.varSector', 'Int.dtDateTime', 'Int.intParentCategoryId', 'Int.LockUserID', 'Int.chrLock', 'Int.intDisplayOrder', 'Int.chrAddStar', 'Int.txtShortDescription', 'Int.FavoriteID', 'Int.chrTrash', 'Int.chrIsPreview', 'Int.chrDraft', 'Int.intSearchRank', 'Int.chrPublish', 'Int.created_at', 'Int.updated_at')
            ->whereIn('Int.id', $ids);
        if (!empty($filterArr['orderByFieldName']) && !empty($filterArr['orderTypeAscOrDesc'])) {
            $response = $response->orderBy('Int.' . $filterArr['orderByFieldName'], $filterArr['orderTypeAscOrDesc']);
        }
        $response = $response->groupBy('Int.id')
            ->get();
        return $response;
    }

    /**
     * This method handels retrival of backend record list
     * @return  Object
     * @since   2017-10-24
     * @author  NetQuick
     */
    public static function getRecordListforSelectBoxbyIds($ids, $filterArr = false)
    {
        $response = false;
        $response = DB::table('interconnections AS Int')
            ->leftjoin('interconnections as Int1', 'Int.intParentCategoryId', '=', 'Int1.id')
            ->select('Int.id', 'Int.varTitle','Int.varSector','Int.dtDateTime', 'Int.intParentCategoryId', 'Int.intDisplayOrder', 'Int.LockUserID', 'Int.chrLock', 'Int.chrAddStar', 'Int.txtShortDescription', 'Int.chrPublish', 'Int.created_at', 'Int.updated_at')
            ->whereIn('Int.id', $ids);
        $response = $response->orderBy('Int.intDisplayOrder', 'ASC');
        $response = $response->groupBy('Int.id')
            ->get();
        return $response;
    }

    /**
     * This method handels retrival of Parent node list
     * @return  Object
     * @since   2017-10-24
     * @author  NetQuick
     */
    public static function getParentNodesIdsByRecordId($recordId = false)
    {
        $response = array();
        if ($recordId) {
            $tree = DB::select("select GetAncestry_interconnections(?) AS tree", [$recordId]);
            if (isset($tree[0]) && !empty($tree[0])) {
                $response = explode(',', $tree[0]->tree);
            }
        }
        return $response;
    }

    /**
     * This method handels retrival of backend record list
     * @return  Object
     * @since   2017-10-24
     * @author  NetQuick
     */
    public static function getRecordsForChart($filterArr = false)
    {
        $data = [];
        $response = false;
        $moduleFields = [
            'id',
            'varSector',
            'varTitle',
            'dtDateTime',
            'intParentCategoryId',
            'intDisplayOrder',
            'chrPublish',
        ];
        $parentCategoryFields = [
            'id',
            'varSector',
            'varTitle',
            'dtDateTime',
            'intParentCategoryId',
        ];
        $response = self::select($moduleFields);
        if ($parentCategoryFields != false) {
            $data['childInterconnections'] = function ($query) use ($parentCategoryFields) {
                $query->select($parentCategoryFields)
                    ->deleted()
                    ->publish()
                    ->checkMainRecord('Y');
            };
        }
        if (count($data) > 0) {
            $response = $response->with($data);
        }
        $response = $response
            ->deleted()
            ->publish()
            ->checkMainRecord('Y')
            ->orderBy('intDisplayOrder')
            ->get();
        return $response;
    }

    /**
     * This method handels retrival of Parent Category record by id
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getParentCategoryNameBycatId($ids)
    {
        $response = false;
        $categoryFields = ['varTitle'];
        $response = Self::getPowerPanelRecords($categoryFields)->deleted()->whereIn('id', $ids)->get();
        return $response;
    }

    /**
     * This method handels retrival of record by id
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getRecordById($id, $ignoreDeleteScope = false)
    {
        $response = false;
        $moduleFields = [
            'id',
            'varSector',
            'varTitle',
            'dtDateTime',
            'txtShortDescription',
            'intParentCategoryId',
            'intDisplayOrder',
            'fkIntDocId',
            'fkMainRecord',
            'intApprovedBy',
            'UserID',
            'chrMain',
            'chrAddStar',
            'chrApproved',
            'chrRollBack',
            'chrLetest',
            'chrPublish',
            'chrDelete',
            'LockUserID',
            'chrLock',
            'chrTrash',
            'FavoriteID',
            'chrDraft',
            'chrPublish',
            'created_at',
            'updated_at',
            'intSearchRank',
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
     * This method handels retrival of category Record
     * @return  Object
     * @since   2017-10-24
     * @author  NetQuick
     */
    public static function getCategories()
    {
        $response = false;
        $moduleFields = ['id', 'varTitle', 'intParentCategoryId'];
        $response = Self::select($moduleFields)
            ->deleted()
            ->publish();
        return $response;
    }

    /**
     * This method handels retrival of category Record
     * @return  Object
     * @since   2017-10-24
     * @author  NetQuick
     */
    public static function getRecordsForHierarchy()
    {
        $response = false;
        $moduleFields = ['id', 'varTitle','varSector', 'intParentCategoryId'];
        $response = Self::select($moduleFields)
            ->checkMainRecord('Y')
            ->deleted();
        return $response;
    }

    /**
     * This method handels retrival of record by id for Log Manage
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getRecordForLogById($id)
    {
        $response = false;
        $moduleFields = [
            'id',
            'varSector',
            'varTitle',
            'dtDateTime',
            'txtShortDescription',
            'intParentCategoryId',
            'intDisplayOrder',
            'fkIntDocId',
            'fkMainRecord',
            'intApprovedBy',
            'UserID',
            'chrMain',
            'chrAddStar',
            'chrApproved',
            'chrRollBack',
            'chrLetest',
            'chrPublish',
            'chrDelete',
            'LockUserID',
            'chrLock',
            'chrTrash',
            'FavoriteID',
            'chrDraft',
            'chrPublish',
            'created_at',
            'updated_at',
            'intSearchRank',
        ];
        $response = Self::getPowerPanelRecords($moduleFields)->deleted()->checkRecordId($id)->first();
        return $response;
    }

    /**
     * This method handels retrival of record for approval grid
     * @return  Object
     * @since   2018-09-27
     * @author  NetQuick Team
     */
    public static function getRecordListApprovalTab($filterArr = false, $isAdmin = false, $userRoleSector)
    {
        $response = false;
        $MainIDs = Self::distinct()
            ->select("fkMainRecord")
            ->checkMainRecord('N')
            ->where('chrMain', 'N')
            ->where('chrIsPreview', 'N')
            ->where('fkMainRecord', '!=', '0')
            ->groupBy('fkMainRecord')
            ->get()
            ->toArray();
        $MainIDs = array_column($MainIDs, 'fkMainRecord');
        $moduleFields = [
            'id',
            'varSector',
            'varTitle',
            'dtDateTime',
            'txtShortDescription',
            'intParentCategoryId',
            'intDisplayOrder',
            'fkIntDocId',
            'fkMainRecord',
            'intApprovedBy',
            'UserID',
            'chrMain',
            'chrAddStar',
            'chrApproved',
            'chrRollBack',
            'chrLetest',
            'chrPublish',
            'chrDelete',
            'LockUserID',
            'chrLock',
            'chrTrash',
            'FavoriteID',
            'chrDraft',
            'chrPublish',
            'created_at',
            'updated_at',
            'intSearchRank',
        ];
        $response = Self::getPowerPanelRecords($moduleFields)
            ->deleted()
            ->filter($filterArr)
            ->where('chrAddStar', 'Y')
            ->where('chrTrash', '!=', 'Y');
            if(!$isAdmin){
                $response = $response->where('varSector', $userRoleSector);
            }
            $response = $response->whereIn('id', $MainIDs)
            ->checkStarRecord('Y')
            ->get();
        return $response;
    }

    /**
     * This method handels retrival of record count
     * @return  Object
     * @since   2018-09-27
     * @author  NetQuick
     */
    public static function getRecordCountListApprovalTab($filterArr = false, $isAdmin = false, $userRoleSector)
    {
        $response = false;
        $MainIDs = Self::distinct()
            ->select("fkMainRecord")
            ->where('fkMainRecord', '!=', '0')
            ->where('chrIsPreview', 'N')
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

    /**
     * This method handels retrival of child Grid
     * @return  Object
     * @since   2018-09-27
     * @author  NetQuick
     */
    public static function getChildGrid($id)
    {
        $response = false;
        $moduleFields = [
            'id',
            'varSector',
            'varTitle',
            'dtDateTime',
            'txtShortDescription',
            'UserID',
            'chrApproved',
            'fkMainRecord',
            'intApprovedBy',
            'dtApprovedDateTime',
            'created_at',
            'updated_at',
        ];
        $response = Self::getPowerPanelRecords($moduleFields)
            ->deleted()
            ->checkMainRecord('N')
            ->where('fkMainRecord', $id)
            ->orderBy('created_at', 'desc')
            ->get();
        return $response;
    }

    /**
     * This method handels retrival of child Grid for rollback
     * @return  Object
     * @since   2018-09-27
     * @author  NetQuick
     */
    public static function getChildrollbackGrid($request)
    {
        $id = $request->id;
        $response = false;
        $moduleFields = [
            'id',
            'varSector',
            'varTitle',
            'dtDateTime',
            'txtShortDescription',
            'UserID',
            'chrApproved',
            'fkMainRecord',
            'intApprovedBy',
            'updated_at',
            'created_at',
        ];
        $response = Self::getPowerPanelRecords($moduleFields)
            ->deleted()
            ->checkMainRecord('N')
            ->where('chrRollBack', 'Y')
            ->where('fkMainRecord', $id)
            ->orderBy('created_at', 'desc')
            ->get();
        return $response;
    }

    /**
     * This method handels update record for ajax approved
     * @return  Object
     * @since   2018-09-27
     * @author  NetQuick
     */
    public static function approved_data_Listing($request)
    {
        $id = $request->id;
        $main_id = $request->main_id;
        // $PUserid = $request->PUserid;
        //Select Child Record Data Start
        $response = false;
        $moduleFields = [
            'varTitle',
            'varSector',
            'dtDateTime',
            'intParentCategoryId',
            'intDisplayOrder',
            'txtShortDescription',
            'chrPublish',
            'intSearchRank',
        ];
        $response = Self::getPowerPanelRecords($moduleFields)
            ->deleted()
            ->checkMainRecord('N')
            ->where('id', $id)
            ->orderBy('created_at', 'desc')
            ->first();
        //Select Child Record Data END
        //Update Copy Child Record To Main Record start
        $whereConditions = ['id' => $main_id];
        $updateMainRecord = [
            'varTitle' => $response['varTitle'],
            'txtShortDescription' => $response['txtShortDescription'],
            'dtDateTime' => $response['dtDateTime'],
            'intParentCategoryId' => $response['intParentCategoryId'],
            'intDisplayOrder' => $response['intDisplayOrder'],
            'chrPublish' => $response['chrPublish'],
            'intSearchRank' => $response['intSearchRank'],
        ];
        $updateMainRecord['chrAddStar'] = 'N';
        CommonModel::updateRecords($whereConditions, $updateMainRecord, false, 'Powerpanel\Interconnections\Models\Interconnections');
        //Update Copy Child Record To Main Record end
        $whereConditions_ApproveN = ['fkMainRecord' => $main_id];
        $updateToApproveN = [
            'chrApproved' => 'N',
            'chrLetest' => 'N',
            'intApprovedBy' => '0',
        ];
        CommonModel::updateRecords($whereConditions_ApproveN, $updateToApproveN, false, 'Powerpanel\Interconnections\Models\Interconnections');
        $whereConditionsApprove = ['id' => $id, 'chrMain' => 'N'];
        $updateToApprove = [
            'chrApproved' => 'Y',
            'chrRollBack' => 'Y',
            'intApprovedBy' => auth()->user()->id,
            'dtApprovedDateTime' => date('Y-m-d H:i:s')
        ];
        CommonModel::updateRecords($whereConditionsApprove, $updateToApprove, false, 'Powerpanel\Interconnections\Models\Interconnections');
        $msg_show = "Record successfully approved.";
        return $msg_show;
    }

    /**
     * This method handels retrival of new record count
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getNewRecordsCount($isAdmin=false, $userRoleSector)
    {
        $response = false;
        $MainIDs = Self::distinct()
            ->select("fkMainRecord")
            ->where('fkMainRecord', '!=', '0')
            ->where('chrIsPreview', 'N')
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

    //Start Trash Count of Records
    public static function getRecordCountforListTrash($filterArr = false, $returnCounter = false, $isAdmin = false, $ignoreId = array(),$userRoleSector)
    {
        $response = 0;
        $cmsPageFields = ['id'];
        $userid = auth()->user()->id;
        $response = Self::getPowerPanelRecords($cmsPageFields);
        if ($filterArr != false) {
            $response = $response->filter($filterArr, $returnCounter);
        }

        $response = $response->deleted()
            ->checkMainRecord('Y')
            ->where('chrIsPreview', 'N')
            ->whereNotIn('id', $ignoreId)
            ->where('chrTrash', 'Y')
            ->where(function ($query) use ($userid) {
                $query->where("UserID", '=', $userid)->where('chrPageActive', '=', 'PR')
                    ->orWhere('chrPageActive', '!=', 'PR');
            });
            if(!$isAdmin){
                $response = $response->where('varSector', $userRoleSector);
            }
            $response = $response->count();
        return $response;
    }

    //Start Draft Count of Records
    public static function getRecordCountforListDarft($filterArr = false, $returnCounter = false, $isAdmin = false, $ignoreId = array(), $userRoleSector)
    {
        $response = 0;
        $cmsPageFields = ['id'];
        $userid = auth()->user()->id;
        $response = Self::getPowerPanelRecords($cmsPageFields);
        if ($filterArr != false) {
            $response = $response->filter($filterArr, $returnCounter);
        }
        $response = $response->deleted()
            ->checkMainRecord('Y')
            ->where('chrIsPreview', 'N')
            ->whereNotIn('id', $ignoreId)
            ->where('chrDraft', 'D')
            ->where('chrTrash', '!=', 'Y');
            if(!$isAdmin){
                $response = $response->where('varSector', $userRoleSector);
            }
            $response = $response->where(function ($query) use ($userid) {
                $query->where("UserID", '=', $userid)->where('chrPageActive', '=', 'PR')
                    ->orWhere('chrPageActive', '!=', 'PR');
            })
            ->count();
        return $response;
    }

    public static function getRecordListDraft($filterArr = false, $isAdmin = false, $userRoleSector)
    {
        $userid = auth()->user()->id;
        $response = false;
        $moduleFields = [
            'id',
            'varSector',
            'varTitle',
            'dtDateTime',
            'chrPublish',
            'chrMain',
            'intParentCategoryId',
            'intDisplayOrder',
            'txtShortDescription',
            'chrPageActive',
            'chrAddStar',
            'chrDraft',
            'intSearchRank',
            'chrTrash',
            'FavoriteID',
            'LockUserID',
            'chrLock',
            'created_at',
            'updated_at',
        ];
        $response = Self::getPowerPanelRecords($moduleFields)
            ->deleted()
            ->filter($filterArr)
            ->checkMainRecord('Y')
            ->where('chrIsPreview', 'N')
            ->where('chrDraft', 'D')
            ->where('chrTrash', '!=', 'Y');
            if(!$isAdmin){
                $response = $response->where('varSector', $userRoleSector);
            }
            $response = $response->where(function ($query) use ($userid) {
                $query->where("UserID", '=', $userid)->where('chrPageActive', '=', 'PR')
                    ->orWhere('chrPageActive', '!=', 'PR');
            });
        $response = $response->get();
        return $response;
    }

    public static function getParentRecordList($filterArr = false, $isAdmin = false)
    {
        $response = false;
        $moduleFields = [
            'id',
            'varSector',
            'varTitle',
            'intParentCategoryId',
        ];
        $response = Self::getPowerPanelRecords($moduleFields)
            ->deleted()
            ->filter($filterArr)
            ->where('intParentCategoryId', '=', '0');
        $response = $response->get();
        return $response;
    }
    public static function getParentRecordListGrid($filterArr = false, $sectorname = false)
    {
        $response = false;
        $moduleFields = [
            'id',
            'varSector',
            'varTitle',
            'intParentCategoryId',
        ];
        $response = Self::getPowerPanelRecords($moduleFields)
            ->deleted()
            ->filter($filterArr)
            ->where('intParentCategoryId', '=', '0')
            ->where('varSector',$sectorname);
        $response = $response->get();
        return $response;
    }

    public static function getRecordListTrash($filterArr = false, $isAdmin = false)
    {
        $response = false;
        $blogsCatfileds = ['id', 'varTitle'];
        $userid = auth()->user()->id;
        $moduleFields = [
            'id',
            'varSector',
            'varTitle',
            'dtDateTime',
            'intParentCategoryId',
            'intDisplayOrder',
            'fkIntDocId',
            'fkMainRecord',
	        'txtShortDescription',
            'intApprovedBy',
            'UserID',
            'chrMain',
            'chrAddStar',
            'chrApproved',
            'chrRollBack',
            'chrLetest',
            'chrPublish',
            'chrDelete',
            'LockUserID',
            'chrLock',
            'chrTrash',
            'FavoriteID',
            'chrDraft',
            'chrPublish',
            'created_at',
            'updated_at',
            'intSearchRank',
        ];
        // $aliasFields = ['id'];
        $response = Self::getPowerPanelRecords($moduleFields)
            ->deleted()
            ->filter($filterArr)
            ->checkMainRecord('Y')
            ->where('chrIsPreview', 'N')
            ->where('chrTrash', 'Y')
            ->where(function ($query) use ($userid) {
                $query->where("UserID", '=', $userid)->where('chrPageActive', '=', 'PR')
                    ->orWhere('chrPageActive', '!=', 'PR');
            });
            if(!$isAdmin){
                $response = $response->where('varSector', $userRoleSector);
            }
        $response = $response->get();

        return $response;
    }

    public static function getRecordCountforListFavorite($filterArr = false, $returnCounter = false, $isAdmin = false, $ignoreId = array(), $userRoleSector)
    {
        $response = 0;
        $cmsPageFields = ['id'];
        $userid = auth()->user()->id;
        $response = Self::getPowerPanelRecords($cmsPageFields);
        if ($filterArr != false) {
            $response = $response->filter($filterArr, $returnCounter);
        }
        $response = $response->deleted()
            ->checkMainRecord('Y')
            ->where('chrIsPreview', 'N')
            ->where('chrTrash', '!=', 'Y')
            ->whereNotIn('id', $ignoreId)
            ->whereRaw("find_in_set($userid,FavoriteID)")
            ->where(function ($query) use ($userid) {
                $query->where("UserID", '=', $userid)->where('chrPageActive', '=', 'PR')
                    ->orWhere('chrPageActive', '!=', 'PR');
            });
            if(!$isAdmin){
                $response = $response->where('varSector', $userRoleSector);
            }
            $response = $response->count();
        return $response;
    }

    /**
     * This method handels retrival of record order of AprovalData
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getOrderOfApproval($id)
    {
        $NewRecordsCount = Self::select('intDisplayOrder')
            ->checkRecordId($id)
            ->first();
        return $NewRecordsCount;
    }

    /**
     * This method handels record Latest Record Count
     * @return  Object
     * @since   2018-09-26
     * @author  NetQuick
     */
    public static function getRecordCount_letest($Main_id, $id)
    {
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
    protected static $_fetchedOrder = [];
    protected static $_fetchedOrderObj = null;

    public static function getRecordByOrder($order = false)
    {
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
                ->first();
        }
        $response = Self::$fetchedOrderObj;
        return $response;
    }

    public static function getCatWithParent()
    {
        $response = false;
        $categoryFields = ['id', 'intParentCategoryId', 'varTitle'];
        $parentCategoryFields = ['id', 'varTitle'];
        $response = Self::getPowerPanelRecords($categoryFields, $parentCategoryFields)
            ->deleted()
            ->publish()
            ->get();
        return $response;
    }

    /**
     * This method handels retrival of record count based on category
     * @return  Object
     * @since   2018-01-09
     * @author  NetQuick
     */
    public static function getCountById($categoryId = null)
    {
        $response = false;
        $moduleFields = ['id'];
        $response = Self::getPowerPanelRecords($moduleFields)
            ->checkCategoryId($categoryId)
            ->where('chrMain', "=", "Y")
            ->deleted()
            ->count();
        return $response;
    }

    /**
     * This method handels retrival of record count based on category
     * @return  Object
     * @since   2018-01-09
     * @author  NetQuick
     */
    public static function getRecordCounter($parentRecordId = null)
    {
        $response = false;
        $moduleFields = ['id'];
        $response = Self::getPowerPanelRecords($moduleFields)
            ->deleted()
            ->checkMainParentsIds($parentRecordId)
            ->checkMainRecord('Y')
            ->count();
        return $response;
    }

    /**
     * This method handels retrival of record count based on category
     * @return  Object
     * @since   2018-01-09
     * @author  NetQuick
     */
    public static function getRecordCount($filterArr = false, $returnCounter = false, $isAdmin = false, $userRoleSector)
    {
        $response = false;
        $moduleFields = ['id'];
        $response = self::getPowerPanelRecords($moduleFields)->deleted()
            ->checkMainRecord('Y');
            if(!$isAdmin){
                $response = $response->where('varSector', $userRoleSector);
            }
            if ($filterArr != false) {
                $response = $response->filter($filterArr, $returnCounter);
            }
            $response = $response->count();
        return $response;
    }

    /**
     * This method handels retrival of record count based on category
     * @return  Object
     * @since   2018-01-09
     * @author  NetQuick
     */
    public static function getRecordCountforList($filterArr = false, $returnCounter = false, $isAdmin = false, $userRoleSector)
    {
        $response = 0;
        $response = false;
        $moduleFields = ['id'];
        $userid = auth()->user()->id;
        $response = self::getPowerPanelRecords($moduleFields)
            ->checkMainRecord('Y')
            ->deleted()
            ->where(function ($query) use ($userid) {
                $query->where("UserID", '=', $userid)->where('chrPageActive', '=', 'PR')
                    ->orWhere('chrPageActive', '!=', 'PR');
            })
            ->checkMainRecord('Y');
            if(!$isAdmin){
                $response = $response->where('varSector', $userRoleSector);
            }
            $response = $response->where('chrIsPreview', 'N')
            ->where('chrTrash', '!=', 'Y');
        if ($filterArr != false) {
            $response = $response->filter($filterArr, $returnCounter);
        }
        $response = $response->count();
        return $response;
    }

    public static function getRecordByOrderByParent($order = false, $parentRecordId = false)
    {
        $response = false;
        $moduleFields = [
            'id',
            'intDisplayOrder',
            'intParentCategoryId',
        ];
        $response = Self::getPowerPanelRecords($moduleFields)
            ->deleted()
            ->orderCheck($order)
            ->checkMainParentsIds($parentRecordId)
            ->checkMainRecord('Y')
            ->first();
        return $response;
    }

    public static function getRecordForReorderByParentId($parentRecordId = false)
    {
        $response = false;
        $moduleFields = [
            'id',
            'intDisplayOrder',
            'intParentCategoryId',
        ];
        $response = Self::getPowerPanelRecords($moduleFields)
            ->deleted()
            ->checkMainParentsIds($parentRecordId)
            ->checkMainRecord('Y')
            ->orderBy('intDisplayOrder', 'asc')
            ->get();
        return $response;
    }

    public static function getRecordsForDeleteById($id)
    {
        $response = false;
        $moduleFields = array(
            'id',
            'varTitle',
            'dtDateTime',
            'varSector',
            'txtShortDescription',
            'intParentCategoryId',
            'intDisplayOrder',
        );
        $response = self::getPowerPanelRecords($moduleFields)
            ->checkRecordId($id)
            ->first();
        return $response;
    }

    public static function updateherarachyRecords($when, $ids)
    {
        $response = false;
        $response = DB::statement("UPDATE nq_interconnections SET intDisplayOrder = (CASE id " . $when . " ELSE intDisplayOrder END) WHERE id BETWEEN " . min($ids) . " AND " . max($ids) . " and chrDelete = 'N' and chrMain='Y'");
        return $response;
    }

    public static function updateDisplayOrderByParent($id, $newOrder, $parentRecordId)
    {
        $response = false;
        $updateQuery = "UPDATE nq_interconnections SET intDisplayOrder=intDisplayOrder + 1 WHERE intDisplayOrder >= " . $newOrder . " and chrDelete = 'N' AND id!=$id AND chrDelete = 'N' AND intParentCategoryId=$parentRecordId AND chrMain='Y'";
        $response = DB::statement($updateQuery);
        return $response;
    }

    /**
     * This method handels category id scope
     * @return  Object
     * @since   2018-01-09
     * @author  NetQuick
     */
    public function scopeCheckCategoryId($query, $id)
    {
        $response = false;
        $response = $query->where('intParentCategoryId', $id);
        return $response;
    }

    /**
     * This method handels record id scope
     * @return  Object
     * @since   2016-07-24
     * @author  NetQuick
     */
    public function scopeCheckRecordId($query, $id)
    {
        $response = false;
        $response = $query->where('id', $id);
        return $response;
    }

    /**
     * This method handels current id scope
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public function scopeNotIdCheck($query, $id)
    {
        $response = false;
        $response = $query->where('id', '!=', $id);
        return $response;
    }

    /**
     * This method handels order scope
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public function scopeOrderCheck($query, $order)
    {
        $response = false;
        $response = $query->where('intDisplayOrder', $order);
        return $response;
    }

    /**
     * This method handels publish scope
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public function scopePublish($query)
    {
        $response = false;
        $response = $query->where(['chrPublish' => 'Y']);
        return $response;
    }

    /**
     * This method handels delete scope
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public function scopeDeleted($query)
    {
        $response = false;
        $response = $query->where(['chrDelete' => 'N']);
        return $response;
    }

    /**
     * This method handels Main Record scope
     * @return  Object
     * @since   2016-07-24
     * @author  NetQuick
     */
    public function scopeCheckStarRecord($query, $flag = 'Y')
    {
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
    public function scopeCheckLatest($query, $flag = 'N')
    {
        $response = false;
        $response = $query->where('chrLetest', "=", $flag);
        return $response;
    }

    /**
     * This method handels filter scope
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public function scopeFilter($query, $filterArr = false, $retunTotalRecords = false)
    {
        $response = null;
        if (!empty($filterArr['orderByFieldName']) && !empty($filterArr['orderTypeAscOrDesc'])) {
            $data = $query->orderBy($filterArr['orderByFieldName'], $filterArr['orderTypeAscOrDesc']);
        } else {
            $data = $query->orderBy('id', 'ASC');
        }
        if (!$retunTotalRecords) {
            if (!empty($filterArr['limit']) && $filterArr['limit'] > 0) {
                $data = $query->skip($filterArr['start'])->take($filterArr['limit']);
            }
        }
        if (!empty($filterArr['statusFilter']) && $filterArr['statusFilter'] != ' ') {
            $data = $query->where('chrPublish', $filterArr['statusFilter']);
        }
        if (!empty($filterArr['sectorFilter']) && $filterArr['sectorFilter'] != ' ') {
            $data = $query->where('varSector', $filterArr['sectorFilter']);
        }
        if (!empty($filterArr['categoryfilter']) && $filterArr['categoryfilter'] != ' ') {
            $data = $query->where('intParentCategoryId','=' ,$filterArr['categoryfilter']);
        }
        if (isset($filterArr['ignore']) && !empty($filterArr['ignore'])) {
            $data = $query->whereNotIn('interconnections.id', $filterArr['ignore']);
        }
        if (!empty($filterArr['searchFilter'])) {
            $data = $query->whereRaw("( varTitle LIKE '%" . $filterArr['searchFilter'] . "%')");
        } else {
            $data = $query->orderBy('id', 'ASC');
        }
        if (!empty($data)) {
            $response = $data;
        }
        return $response;
    }

    /**
     * This method handels filter scope
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public function scopeDataFilter($query, $filterArr = false, $retunTotalRecords = false)
    {
        $response = null;
        if (!empty($filterArr['orderByFieldName']) && !empty($filterArr['orderTypeAscOrDesc'])) {
            $data = $query->orderBy($filterArr['orderByFieldName'], $filterArr['orderTypeAscOrDesc']);
        } else {
            $data = $query->orderBy('id', 'ASC');
        }
        if (!$retunTotalRecords) {
            if (!empty($filterArr['limit']) && $filterArr['limit'] > 0) {
                $data = $query->skip($filterArr['start'])->take($filterArr['limit']);
            }
        }
        if (!empty($filterArr['statusFilter']) && $filterArr['statusFilter'] != ' ') {
            $data = $query->where('chrPublish', $filterArr['statusFilter']);
        }
        if (!empty($filterArr['sectorFilter']) && $filterArr['sectorFilter'] != ' ') {
            $data = $query->where('varSector', $filterArr['sectorFilter']);
        }
        if (!empty($filterArr['categoryfilter']) && $filterArr['categoryfilter'] != ' ') {
            $data = $query->where('intParentCategoryId','=' ,$filterArr['categoryfilter']);
        }
        if (!empty($filterArr['searchFilter'])) {
            $data = $query->whereRaw("( varTitle LIKE '%" . $filterArr['searchFilter'] . "%')");
        } else {
            $data = $query->orderBy('id', 'ASC');
        }
        if (!empty($data)) {
            $response = $data;
        }
        return $response;
    }

    /**
     * This method handels parent record id scope
     * @return  Object
     * @since   04-08-2017
     * @author  NetQuick
     */
    public function scopeCheckParentRecordId($query, $id)
    {
        $response = false;
        $response = $query->where('intParentCategoryId', $id);
        return $response;
    }

    public function scopeDisplayOrderBy($query, $orderBy)
    {
        $response = false;
        $response = $query->orderBy('intDisplayOrder', $orderBy);
        return $response;
    }

    public static function getOrganizationNameByOrganizationId($ids)
    {
        $response = false;
        $parentCategoryFields = ['varTitle'];
        $ids = explode(',', $ids[0]);
        $response = Self::getPowerPanelRecords($parentCategoryFields)->deleted()->whereIn('id', $ids)->get();
        return $response;
    }

// Each category may have one parent
    public function parent()
    {
        return $this->belongsToOne(static::class, 'intParentCategoryId');

    }

// Each category may have multiple children
    public function childInterconnections()
    {
        return $this->hasMany(static::class, 'intParentCategoryId');
    }

/**
 * This method handels countMainParent scope
 * @return  Object
 * @since   2018-09-07
 * @author  NetQuick
 */
    public function scopeCheckMainParentsIds($query, $parentRecordId = false)
    {
        $response = false;
        if ($parentRecordId > 0) {
            $response = $query->where(['intParentCategoryId' => $parentRecordId]);
        } else {
            $response = $query->where(['intParentCategoryId' => '0']);
        }
        return $response;
    }

/**
 * This method handels Main Record scope
 * @return  Object
 * @since   2016-07-24
 * @author  NetQuick
 */
    public function scopeCheckMainRecord($query, $checkMain = 'Y')
    {
        $response = false;
        $response = $query->where('chrMain', "=", $checkMain);
        return $response;
    }

    public static function getBuilderRecordList($filterArr = [])
    {
        $response = false;
        $moduleFields = [
            'interconnections.id',
            'interconnections.varTitle',
            'interconnections.dtDateTime',
            'interconnections.txtShortDescription',
            'interconnections.chrPublish',
            'interconnections.chrDelete',
            'interconnections.chrMain',
            'interconnections.created_at',
        ];
        $response = Self::getPowerPanelRecords($moduleFields, false, false, false, false)
            ->filter($filterArr);
        $response = $response->where('interconnections.chrPublish', 'Y')
            ->where('interconnections.chrDelete', 'N')
            ->where('interconnections.chrMain', 'Y')
            ->groupBy('interconnections.id')
            ->get();
        return $response;
    }

    public static function getBuilderInterconnections($recIds, $dbFilter=false,$sector_slug)
    {
        $response = false;
        $moduleFields = [
            'id',
            'varTitle',
            'dtDateTime',
            'varSector',
            'fkIntDocId',
            'txtShortDescription',
            'intParentCategoryId',
            'intDisplayOrder',
            'chrPublish',
        ];
        $parentCategoryFields = [
            'id',
            'varTitle',
            'intParentCategoryId',
        ];
        if (empty($response)) {
            $response = Self::getFrontRecords($moduleFields, $parentCategoryFields)
                ->where('chrMain', 'Y')
                ->where('intParentCategoryId','<>','0');
            
            if (isset($dbFilter['category']) && !empty($dbFilter['category']) && strtolower($dbFilter['category']) != 'all') {
                $response = $response->where('intParentCategoryId', $dbFilter['category']);
            }

            if(isset($dbFilter['month']) && !empty($dbFilter['month'])) {
                $response->whereMonth('dtDateTime', $dbFilter['month']);                
            }
            
             if (isset($sector_slug) && !empty($sector_slug)) {
                 $response = $response->where('varSector', '=', $sector_slug);
            }

            if(isset($dbFilter['year']) && !empty($dbFilter['year'])) {
                $years = $dbFilter['year'];
                $response->where(function($response) use($years) {
                    foreach ($years as $year) {
                        $response->whereYear('dtDateTime', '=', $year, 'or');
                    }
                });
                
            }
            $response = $response->deleted()
                ->publish()
                ->groupBy('id');

            $response->orderBy('dtDateTime', 'DESC');

            if (Request::segment(1) != '') {
                $pageNumber = 1;
                if(isset($dbFilter['pageNumber']) && !empty($dbFilter['pageNumber'])) {
                    $pageNumber = $dbFilter['pageNumber'];
                }
                $response = $response->paginate(12, ['*'], 'page', $pageNumber);
            } else {
                $response = $response->get();
            }
        }
        // $data = self::getParentsData($recIds, $response);
        return $response;
    }

    public static function getInterconnectionsParent() {
        $moduleFields = [
            'id',
            'varTitle',
            'dtDateTime',
            'varSector',
            'fkIntDocId',
            'txtShortDescription',
            'intParentCategoryId',
            'intDisplayOrder',
            'chrPublish',
        ];

        $parentCategoryFields = [
            'id',
            'varTitle',
            'intParentCategoryId',
        ]; 

        $response = Self::getFrontRecords($moduleFields, $parentCategoryFields)
                ->where('chrMain', 'Y')
                ->where('intParentCategoryId','=','0')
                ->orderBy('intDisplayOrder')
                ->where('chrTrash', '!=', 'Y')
            ->where('chrDraft', '!=', 'D')
            ->deleted()
                ->publish();

        $response->orderBy('dtDateTime', 'DESC');

        $response = $response->get();

        return $response;

    }

    public static function getParentsData($id, $data)
    {
        $response = false;
        $moduleFields = [
            'id',
            'varTitle',
            'dtDateTime',
            'varSector',
            'txtShortDescription',
            'intParentCategoryId',
            'intDisplayOrder',
            'chrPublish',
        ];
        $parentCategoryFields = [
            'id',
            'varTitle',
            'intParentCategoryId',
        ];
        $response = Self::getFrontRecords($moduleFields, $parentCategoryFields)
            ->where('chrMain', 'Y');
        $response = $response->where('intParentCategoryId', $id);
        $response = $response->deleted()
            ->publish()
            ->groupBy('id')
            ->get();
        if (!empty($response) && count($response) > 0) {
            $orgdata = array_merge(json_decode(json_encode($data), true), json_decode(json_encode($response), true));
        } else {
            $orgdata = json_decode(json_encode($data), true);
        }
        $orgdata1 = $orgdata;
        if (!empty($response) && count($response) > 0) {
            foreach ($response as $orgnization) {
                $orgdata1 = self::getParentsData($orgnization->id, $orgdata);
            }
        }
        return $orgdata1;
    }

    public static function getAllParents()
    {
        $response = false;
        $moduleFields = [
            'id',
            'varTitle',
            'dtDateTime',
            'varSector',
            'intParentCategoryId',
            'intDisplayOrder',
            'txtShortDescription',
            'chrPublish',
            'intSearchRank',
        ];
        $response = Self::getPowerPanelRecords($moduleFields, false)
            ->deleted()
            ->checkMainRecord('Y')
            ->where('chrPublish', 'Y')
            ->where('chrTrash', '!=', 'Y')
            ->where('chrDraft', '!=', 'D')
            ->where('intParentCategoryId', '0')
            ->get();
        return $response;
    }

    public static function getPreviousRecordByMainId($id)
    {
        $response = Self::select('id', 'fkMainRecord')
                        ->where('fkMainRecord', $id)    
                        ->where('chrMain', 'N')
                        ->where('chrApproved', 'N')
                        ->where('chrDelete', 'N')
                        ->where('dtApprovedDateTime','!=', NULL)
                        ->orderBy('dtApprovedDateTime', 'DESC')
                        ->first();
                        
        return $response;

    }

}
