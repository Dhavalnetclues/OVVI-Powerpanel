<?php

namespace Powerpanel\Organizations\Models;

use DB;
use Illuminate\Database\Eloquent\Model;
use App\CommonModel;
use Cache;

class Organizations extends Model {

    protected $table = 'organizations';
    protected $fillable = [
        'id',
        'varTitle',
        'varDesignation',
        'intParentCategoryId',
        'intDisplayOrder',
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
        'created_at',
        'updated_at',
        'intSearchRank'
    ];

    /**
     * This method handels retrival of front blog detail
     * @return  Object
     * @since   2017-10-13
     * @author  NetQuick
     */
    public static function getRecordIdByAliasID($aliasID) {
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
    public static function getFrontList() {
        $response = false;
        $organizationFields = ['id', 'varTitle'];
        $response = Cache::tags(['Organizations'])->get('getFrontServiceCatList');
        if (empty($response)) {
            $response = Self::getFrontRecords($organizationFields)
                    ->deleted()
                    ->publish()
                    ->get()
                    ->pluck('varTitle', 'id');
            Cache::tags(['Organizations'])->forever('getFrontServiceCatList', $response);
        }
        return $response;
    }

    /**
     * This method handels retrival of organization records
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public static function getFrontRecords($moduleFields = false) {
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
    public function organizations() {
        $response = false;
        $response = $this->hasOne('App\Organizations', 'id', 'intParentCategoryId');
        return $response;
    }

    /**
     * This method handels main category relation
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public function parentCategory() {
        $response = false;
        $response = $this->belongsTo('App\Organizations', 'intParentCategoryId', 'id');
        return $response;
    }

    /**
     * This method handels retrival of records
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public static function getRecords($moduleId = false) {
        $response = false;
        $response = self::with(['parentCategory']);
        return $response;
    }

    public static function UpdateDisplayOrder($order, $parentRecordId = false) {
        $updateSql = "UPDATE nq_organizations SET `intDisplayOrder` = `intDisplayOrder` + 1
				WHERE `intDisplayOrder` >= " . $order . "
				AND chrDelete='N'
				AND intDisplayOrder != 0 AND chrMain='Y'";
        if ((int) $parentRecordId > 0) {
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
    public static function getPowerPanelRecords($moduleFields = false, $parentCategoryFields = false) {
        $data = [];
        $response = false;
        $response = self::select($moduleFields);
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

    /**
     * This method handels retrival of backend record list
     * @return  Object
     * @since   2017-10-24
     * @author  NetQuick
     */
    public static function getRecordList($filterArr = false) {
        $response = false;
        $moduleFields = ['id', 'varTitle', 'intParentCategoryId', 'intDisplayOrder', 'txtShortDescription', 'LockUserID', 'chrLock', 'txtDescription', 'chrPublish'];
        $response = Self::getPowerPanelRecords($moduleFields)
                ->deleted()
                ->filter($filterArr)
                ->get();
        return $response;
    }

    /**
     * This method handels retrival of backend record list
     * @return  Object
     * @since   2017-10-24
     * @author  NetQuick
     */
    public static function getRecordListforOrganizationsGrid($filterArr = false, $isAdmin = false) {
        $response = false;
        $moduleFields = ['id', 'varTitle', 'intParentCategoryId', 'intDisplayOrder', 'txtShortDescription', 'LockUserID', 'chrLock', 'txtDescription', 'chrAddStar', 'chrPublish', 'created_at', 'updated_at'];
        $response = self::select($moduleFields);
        $response = $response->deleted()
                ->dataFilter($filterArr)
                ->checkMainRecord('Y');
        $response = $response->get();
        return $response;
    }

    /**
     * This method handels retrival of backend record list
     * @return  Object
     * @since   2017-10-24
     * @author  NetQuick
     */
    public static function getRecordListforGridbyIds($ids, $filterArr = false) {
        $response = false;
        $response = DB::table('organizations AS Og')
                ->leftjoin('organizations as Og1', 'Og.intParentCategoryId', '=', 'Og1.id')
                ->select('Og.id', 'Og.varTitle', 'Og.intParentCategoryId', 'Og.LockUserID', 'Og.chrLock', 'Og.intDisplayOrder', 'Og.chrAddStar', 'Og.txtShortDescription', 'Og.varDesignation', 'Og.txtDescription', 'Og.chrPublish', 'Og.created_at', 'Og.updated_at')
                ->whereIn('Og.id', $ids);
        if (!empty($filterArr['orderByFieldName']) && !empty($filterArr['orderTypeAscOrDesc'])) {
            $response = $response->orderBy('Og.' . $filterArr['orderByFieldName'], $filterArr['orderTypeAscOrDesc']);
        }
        $response = $response->groupBy('Og.id')
                ->get();
        return $response;
    }

    /**
     * This method handels retrival of backend record list
     * @return  Object
     * @since   2017-10-24
     * @author  NetQuick
     */
    public static function getRecordListforSelectBoxbyIds($ids, $filterArr = false) {
        $response = false;
        $response = DB::table('organizations AS Og')
                ->leftjoin('organizations as Og1', 'Og.intParentCategoryId', '=', 'Og1.id')
                ->select('Og.id', 'Og.varTitle', 'Og.intParentCategoryId', 'Og.intDisplayOrder', 'Og.LockUserID', 'Og.chrLock', 'Og.chrAddStar', 'Og.txtShortDescription', 'Og.txtDescription', 'Og.chrPublish', 'Og.created_at', 'Og.updated_at')
                ->whereIn('Og.id', $ids);
        $response = $response->orderBy('Og.intDisplayOrder', 'ASC');
        $response = $response->groupBy('Og.id')
                ->get();
        return $response;
    }

    /**
     * This method handels retrival of Parent node list
     * @return  Object
     * @since   2017-10-24
     * @author  NetQuick
     */
    public static function getParentNodesIdsByRecordId($recordId = false) {
        $response = array();
        if ($recordId) {
            $tree = DB::select("select GetAncestry_Organization(?) AS tree", [$recordId]);
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
    public static function getRecordsForChart($filterArr = false) {
        $data = [];
        $response = false;
        $moduleFields = [
            'id',
            'varTitle',
            'intParentCategoryId',
            'intDisplayOrder',
            'varDesignation',
            'chrPublish'
        ];
        $parentCategoryFields = [
            'id',
            'varTitle',
            'intParentCategoryId'
        ];
        $response = self::select($moduleFields);
        if ($parentCategoryFields != false) {
            $data['childorganizations'] = function ($query) use ($parentCategoryFields) {
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
    public static function getParentCategoryNameBycatId($ids) {
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
    public static function getRecordById($id, $ignoreDeleteScope = false) {
        $response = false;
        $moduleFields = [
            'id',
            'varTitle',
            'varDesignation',
            'intParentCategoryId',
            'intDisplayOrder',
            'txtShortDescription',
            'txtDescription',
            'fkMainRecord',
            'intApprovedBy',
            'UserID',
            'chrMain',
            'chrAddStar',
            'chrAddStar',
            'chrApproved',
            'chrRollBack',
            'chrPublish',
            'LockUserID', 'chrLock',
            'intSearchRank'
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
    public static function getCategories() {
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
    public static function getRecordsForHierarchy() {
        $response = false;
        $moduleFields = ['id', 'varTitle', 'intParentCategoryId'];
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
    public static function getRecordForLogById($id) {
        $response = false;
        $moduleFields = [
            'id',
            'varTitle',
            'varDesignation',
            'intParentCategoryId',
            'intDisplayOrder',
            'txtShortDescription',
            'txtDescription',
            'fkMainRecord',
            'intApprovedBy',
            'UserID',
            'chrMain',
            'chrAddStar',
            'chrAddStar',
            'chrApproved',
            'chrRollBack',
            'chrPublish',
            'LockUserID', 'chrLock',
            'intSearchRank'
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
    public static function getRecordListApprovalTab($filterArr = false) {
        $response = false;
        $MainIDs = Self::distinct()
                ->select("fkMainRecord")
                ->checkMainRecord('N')
                ->where('fkMainRecord', '!=', '0')
                ->groupBy('fkMainRecord')
                ->get()
                ->toArray();
        $MainIDs = array_column($MainIDs, 'fkMainRecord');
        $moduleFields = [
            'id',
            'varTitle',
            'varDesignation',
            'intParentCategoryId',
            'intDisplayOrder',
            'txtDescription',
            'chrAddStar',
            'fkMainRecord',
            'LockUserID', 'chrLock',
            'chrPublish'
        ];
        $response = Self::getPowerPanelRecords($moduleFields)
                ->deleted()
                ->filter($filterArr)
                ->whereIn('id', $MainIDs)
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

    /**
     * This method handels retrival of child Grid
     * @return  Object
     * @since   2018-09-27
     * @author  NetQuick
     */
    public static function getChildGrid($id) {
        $response = false;
        $moduleFields = [
            'id',
            'varTitle',
            'UserID',
            'chrApproved',
            'fkMainRecord',
            'intApprovedBy',
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
        return $response;
    }

    /**
     * This method handels retrival of child Grid for rollback
     * @return  Object
     * @since   2018-09-27
     * @author  NetQuick
     */
    public static function getChildrollbackGrid($request) {
        $id = $request->id;
        $response = false;
        $moduleFields = [
            'id',
            'varTitle',
            'UserID',
            'chrApproved',
            'fkMainRecord',
            'intApprovedBy',
            'updated_at',
            'created_at'
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
    public static function approved_data_Listing($request) {
        $id = $request->id;
        $main_id = $request->main_id;
        // $PUserid = $request->PUserid;
        //Select Child Record Data Start
        $response = false;
        $moduleFields = [
            'varTitle',
            'varDesignation',
            'intParentCategoryId',
            'intDisplayOrder',
            'txtShortDescription',
            'txtDescription',
            'chrPublish',
            'varMetaTitle',
            'varMetaKeyword',
            'varMetaDescription',
            'intSearchRank'
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
            'intParentCategoryId' => $response['intParentCategoryId'],
            'varDesignation' => $response['varDesignation'],
            //'intDisplayOrder' => $response['intDisplayOrder'],						
            'chrPublish' => $response['chrPublish'],
            'varMetaTitle' => $response['varMetaTitle'],
            'varMetaKeyword' => $response['varMetaKeyword'],
            'varMetaDescription' => $response['varMetaDescription'],
            'intSearchRank' => $response['intSearchRank'],
        ];
        $updateMainRecord['chrAddStar'] = 'N';
        CommonModel::updateRecords($whereConditions, $updateMainRecord, false, 'Powerpanel\Organizations\Models\Organizations');
        //Update Copy Child Record To Main Record end
        $whereConditions_ApproveN = ['fkMainRecord' => $main_id];
        $updateToApproveN = [
            'chrApproved' => 'N',
            'chrLetest' => 'N',
            'intApprovedBy' => '0',
        ];
        CommonModel::updateRecords($whereConditions_ApproveN, $updateToApproveN, false, 'Powerpanel\Organizations\Models\Organizations');
        $whereConditionsApprove = ['id' => $id, 'chrMain' => 'N'];
        $updateToApprove = [
            'chrApproved' => 'Y',
            'chrRollBack' => 'Y',
            'intApprovedBy' => auth()->user()->id
        ];
        CommonModel::updateRecords($whereConditionsApprove, $updateToApprove, false, 'Powerpanel\Organizations\Models\Organizations');
        $msg_show = "Record successfully approved.";
        return $msg_show;
    }

    /**
     * This method handels retrival of new record count
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
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

    /**
     * This method handels retrival of record order of AprovalData
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getOrderOfApproval($id) {
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

    public static function getCatWithParent() {
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
    public static function getCountById($categoryId = null) {
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
    public static function getRecordCounter($parentRecordId = null) {
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
    public static function getRecordCount($filterArr = false, $returnCounter = false) {
        $response = false;
        $moduleFields = ['id'];
        $response = self::getPowerPanelRecords($moduleFields)
                ->checkMainRecord('Y')
                ->deleted();
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
    public static function getRecordCountforList($filterArr = false, $returnCounter = false, $isAdmin = false) {
        $response = false;
        $moduleFields = ['id'];
        $response = self::getPowerPanelRecords($moduleFields)
                ->checkMainRecord('Y')
                ->deleted();
        if ($filterArr != false) {
            $response = $response->filter($filterArr, $returnCounter);
        }
        $response = $response->count();
        return $response;
    }

    public static function getRecordByOrderByParent($order = false, $parentRecordId = false) {
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

    public static function getRecordForReorderByParentId($parentRecordId = false) {
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

    public static function getRecordsForDeleteById($id) {
        $response = false;
        $moduleFields = array(
            'id',
            'varTitle',
            'intParentCategoryId',
            'intDisplayOrder'
        );
        $response = self::getPowerPanelRecords($moduleFields)
                ->checkRecordId($id)
                ->first();
        return $response;
    }

    public static function updateherarachyRecords($when, $ids) {
        $response = false;
        $response = DB::statement("UPDATE nq_organizations SET intDisplayOrder = (CASE id " . $when . " ELSE intDisplayOrder END) WHERE id BETWEEN " . min($ids) . " AND " . max($ids) . " and chrDelete = 'N' and chrMain='Y'");
        return $response;
    }

    public static function updateDisplayOrderByParent($id, $newOrder, $parentRecordId) {
        $response = false;
        $updateQuery = "UPDATE nq_organizations SET intDisplayOrder=intDisplayOrder + 1 WHERE intDisplayOrder >= " . $newOrder . " and chrDelete = 'N' AND id!=$id AND chrDelete = 'N' AND intParentCategoryId=$parentRecordId AND chrMain='Y'";
        $response = DB::statement($updateQuery);
        return $response;
    }

    /**
     * This method handels category id scope
     * @return  Object
     * @since   2018-01-09
     * @author  NetQuick
     */
    public function scopeCheckCategoryId($query, $id) {
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
    public function scopeCheckRecordId($query, $id) {
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
    public function scopeNotIdCheck($query, $id) {
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
    public function scopeOrderCheck($query, $order) {
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
    public function scopePublish($query) {
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
    public function scopeDeleted($query) {
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
     * @author  NetQuick
     */
    public function scopeFilter($query, $filterArr = false, $retunTotalRecords = false) {
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
        if (isset($filterArr['ignore']) && !empty($filterArr['ignore'])) {
            $data = $query->whereNotIn('organizations.id', $filterArr['ignore']);
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
    public function scopeDataFilter($query, $filterArr = false, $retunTotalRecords = false) {
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
    public function scopeCheckParentRecordId($query, $id) {
        $response = false;
        $response = $query->where('intParentCategoryId', $id);
        return $response;
    }

    public function scopeDisplayOrderBy($query, $orderBy) {
        $response = false;
        $response = $query->orderBy('intDisplayOrder', $orderBy);
        return $response;
    }

    public static function getOrganizationNameByOrganizationId($ids) {
        $response = false;
        $parentCategoryFields = ['varTitle'];
        $ids = explode(',', $ids[0]);
        $response = Self::getPowerPanelRecords($parentCategoryFields)->deleted()->whereIn('id', $ids)->get();
        return $response;
    }

// Each category may have one parent
    public function parent() {
    return $this->belongsToOne(static::class, 'intParentCategoryId');





}

// Each category may have multiple children
public function childorganizations() {
    return $this->hasMany(static::class, 'intParentCategoryId');
}

/**
 * This method handels countMainParent scope
 * @return  Object
 * @since   2018-09-07
 * @author  NetQuick
 */
public function scopeCheckMainParentsIds($query, $parentRecordId = false) {
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
public function scopeCheckMainRecord($query, $checkMain = 'Y') {
    $response = false;
    $response = $query->where('chrMain', "=", $checkMain);
    return $response;
}

public static function getBuilderRecordList($filterArr = []) {
    $response = false;
    $moduleFields = [
        'organizations.id',
        'organizations.varTitle',
        'organizations.varDesignation',
        'organizations.chrPublish',
        'organizations.chrDelete',
        'organizations.chrMain',
        'organizations.created_at'
    ];
    $response = Self::getPowerPanelRecords($moduleFields, false, false, false, false)
            ->filter($filterArr);
    $response = $response->where('organizations.chrPublish', 'Y')
            ->where('organizations.chrDelete', 'N')
            ->where('organizations.chrMain', 'Y')
            ->groupBy('organizations.id')
            ->get();
    return $response;
}

public static function getBuilderOrganizations($recIds) {
    $response = false;
    $moduleFields = [
        'id',
        'varTitle',
        'intParentCategoryId',
        'intDisplayOrder',
        'varDesignation',
        'chrPublish'
    ];
    $parentCategoryFields = [
        'id',
        'varTitle',
        'intParentCategoryId'
    ];
    if (empty($response)) {
        $response = Self::getFrontRecords($moduleFields, $parentCategoryFields)
                ->where('chrMain', 'Y');
        if ($recIds != '') {
            $response = $response->where('id', $recIds);
        }
        $response = $response->deleted()
                ->publish()
                ->groupBy('id')
                ->get();
    }
    $data = self::getParentsData($recIds,$response);
    return $data;
}

public static function getParentsData($id,$data) {
    $response = false;
    $moduleFields = [
        'id',
        'varTitle',
        'intParentCategoryId',
        'intDisplayOrder',
        'varDesignation',
        'chrPublish'
    ];
    $parentCategoryFields = [
        'id',
        'varTitle',
        'intParentCategoryId'
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
       }else{
         $orgdata = json_decode(json_encode($data), true);
       }
        $orgdata1 = $orgdata;   
    if (!empty($response) && count($response) > 0) {
        foreach ($response as $orgnization) {
          $orgdata1 =  self::getParentsData($orgnization->id,$orgdata);
        }
    } 
    return $orgdata1;
}

public static function getAllParents() {
    $response = false;
    $moduleFields = [
        'id',
        'varTitle',
        'varDesignation',
        'intParentCategoryId',
        'intDisplayOrder',
        'txtShortDescription',
        'txtDescription',
        'chrPublish',
        'varMetaTitle',
        'varMetaKeyword',
        'varMetaDescription',
        'intSearchRank'
    ];
    $response = Self::getPowerPanelRecords($moduleFields, false)
            ->deleted()
            ->checkMainRecord('Y')
            ->where('chrPublish', 'Y')
            ->where('intParentCategoryId', '0')
            ->get();
    return $response;
}

public static function getPreviousRecordByMainId($id) {
    $response = Self::select('id','fkMainRecord')
                    ->deleted()  
                    ->publish()
                    ->where('fkMainRecord', $id)
                    ->where('chrMain', 'N')
                    ->where('chrApproved', 'N')
                    ->orderBy('dtApprovedDateTime','DESC')
                    ->first();
    return $response;

}

}
