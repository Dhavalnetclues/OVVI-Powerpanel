<?php

namespace Powerpanel\Rfps\Models;

use Illuminate\Database\Eloquent\Model;
use Cache;
use Request;
use App\CommonModel;
use DB;

class Rfps extends Model {

    protected $table = 'rfps';
    protected $fillable = [
        'id',
        'intAliasId',
        'fkIntDocId',
        'dtDateTime',
        'dtEndDateTime','varSector',
        'varTitle',
        'varShortDescription',
        'txtCategories',
        'txtDescription',
        'chrPublish',
        'chrDelete',
        'intSearchRank',
        'varMetaTitle',
        'varMetaDescription',
        'chrPageActive',
        'varPassword',
        'chrDraft',
        'intSearchRank',
        'chrTrash',
        'FavoriteID',
        'LockUserID', 'chrLock',
        'created_at',
        'updated_at'
    ];

    /**
     * This method handels retrival of front blog detail
     * @return  Object
     * @since   2017-10-13
     * @author  NetQuick
     */
    public static function getRecordIdByAliasID($aliasID) {
        $response = false;
        $response = Cache::tags(['Rfps'])->get('getRfpsRecordIdByAliasID_' . $aliasID);
        if (empty($response)) {
            $response = Self::Select('id')->deleted()->publish()->checkAliasId($aliasID)->first();
            Cache::tags(['Rfps'])->forever('getRfpsRecordIdByAliasID_' . $aliasID, $response);
        }
        return $response;
    }

    /**
     * This method handels retrival of front service list
     * @return  Object
     * @since   2017-10-14
     * @author  NetQuick
     */
    public static function getLatestForHome($limit = 1) {
        $response = false;
        $moduleFields = ['id', 'intAliasId', 'varTitle','varSector', 'txtCategories', 'dtDateTime'];
        $aliasFields = ['id', 'varAlias'];
        $response = Cache::tags(['Rfps'])->get('getLatestForHome_' . $limit);
        if (empty($response)) {
            $response = Self::getFrontRecords($moduleFields, $aliasFields)
                    ->deleted()
                    ->publish()
                    ->dateRange()
                    ->where('chrMain', 'Y')
                    ->take($limit)
                    ->orderBy('dtDateTime', 'DESC')
                    ->get();
            Cache::tags(['Rfps'])->forever('getLatestForHome_' . $limit, $response);
        }
        return $response;
    }

    /**
     * This method handels retrival of front latest rfps list
     * @return  Object
     * @since   2017-10-13
     * @author  NetQuick
     */
    public static function getFrontPopularList($id = false) {
        $response = false;
        $moduleFields = ['id', 'varTitle','varSector', 'varShortDescription', 'fkIntDocId', 'intAliasId', 'dtDateTime', 'dtEndDateTime', 'chrPageActive', 'varPassword', 'chrDraft', 'intSearchRank', 'chrTrash', 'FavoriteID', 'created_at', 'updated_at'];
        $aliasFields = ['id', 'varAlias'];
        $response = Cache::tags(['Rfps'])->get('getFrontPopularRfpsList_' . $id);
        if (empty($response)) {
            $response = Self::getFrontRecords($moduleFields, $aliasFields)
                    ->deleted()
                    ->publish()
                    ->latest($id)
                    ->take(3)
                    ->get();
            Cache::tags(['Rfps'])->forever('getFrontPopularRfpsList_' . $id, $response);
        }
        return $response;
    }

    public static function getNewRecordsCount() {
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
                ->whereIn('id', $MainIDs)
                ->checkStarRecord('Y')
                ->where('chrTrash', '!=', 'Y')
                ->count();
        return $response;
    }

    public static function getChildrollbackGrid() {
        $id = $_REQUEST['id'];
        $response = false;
        $cmsPageFields = [
            'id',
            'intAliasId',
            'dtDateTime',
            'dtEndDateTime',
            'varTitle','varSector',
            'txtCategories',
            'varShortDescription',
            'txtDescription',
            'varMetaTitle',
            'varMetaDescription',
            'chrPublish',
            'chrDelete',
            'created_at',
            'UserID',
            'chrApproved',
            'fkMainRecord',
            'intApprovedBy',
            'chrPageActive',
            'varPassword',
            'chrDraft',
            'intSearchRank',
            'chrTrash',
            'FavoriteID',
            'created_at',
            'updated_at'
        ];
        $aliasFields = ['id', 'varAlias'];
        $response = Self::getPowerPanelRecords($cmsPageFields, $aliasFields)
                ->deleted()
                ->where('chrMain', 'N')
                ->where('chrRollBack', 'Y')
                ->where('fkMainRecord', $id)
                ->where('chrIsPreview', 'N')
                ->orderBy('created_at', 'desc')
                ->get();
        return $response;
    }

    /**
     * This method handels retrival of record count based on category
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getCountById($categoryId = null) {
        $response = false;
        $moduleFields = ['id'];
        $response = Self::getPowerPanelRecords($moduleFields)
                ->checkCategoryId($categoryId)
                ->deleted()
                ->where('chrMain', 'Y')
                ->where('chrIsPreview', 'N')
                ->count();
        return $response;
    }

    /**
     * This method handels retrival of record count
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getRecordList($filterArr = false, $isAdmin = false) {
        $userid = auth()->user()->id;
        $response = false;
        $moduleFields = [
            'id',
            'intAliasId',
            'fkIntDocId',
            'chrPublish',
            'varTitle','varSector',
            'dtDateTime',
            'dtEndDateTime',
            'txtCategories',
            'txtDescription',
            'varShortDescription',
            'chrPageActive',
            'varPassword',
            'chrAddStar',
            'chrDraft',
            'intSearchRank',
            'chrTrash',
            'FavoriteID',
            'LockUserID', 'chrLock',
            'created_at',
            'updated_at'
        ];
        $aliasFields = ['id', 'varAlias'];
        $response = Self::getPowerPanelRecords($moduleFields, $aliasFields)
                ->deleted()
                ->filter($filterArr)
                ->where(function ($query) use ($userid) {
                    $query->where("UserID", '=', $userid)->where('chrPageActive', '=', 'PR')
                    ->orWhere('chrPageActive', '!=', 'PR');
                })
                ->checkMainRecord('Y')
                ->where('chrIsPreview', 'N')
                ->where('chrTrash', '!=', 'Y');
        $response = $response->get();
        return $response;
    }

    /**
     * This method handels retrival of record count
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getRecordListFavorite($filterArr = false, $isAdmin = false) {
        $userid = auth()->user()->id;
        $response = false;
        $moduleFields = [
            'id',
            'intAliasId',
            'fkIntDocId',
            'chrPublish',
            'varTitle','varSector',
            'dtDateTime',
            'dtEndDateTime',
            'txtCategories',
            'txtDescription',
            'varShortDescription',
            'chrPageActive',
            'varPassword',
            'chrAddStar',
            'chrDraft',
            'intSearchRank',
            'chrTrash',
            'FavoriteID',
            'LockUserID', 'chrLock',
            'created_at',
            'updated_at'
        ];
        $aliasFields = ['id', 'varAlias'];
        $response = Self::getPowerPanelRecords($moduleFields, $aliasFields)
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
        $response = $response->get();
        return $response;
    }

    /**
     * This method handels retrival of record count
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getRecordListDraft($filterArr = false, $isAdmin = false) {
        $userid = auth()->user()->id;
        $response = false;
        $moduleFields = [
            'id',
            'intAliasId',
            'fkIntDocId',
            'chrPublish','varSector',
            'varTitle',
            'dtDateTime',
            'dtEndDateTime',
            'txtCategories',
            'txtDescription',
            'varShortDescription',
            'chrPageActive',
            'varPassword',
            'chrAddStar',
            'chrDraft',
            'intSearchRank',
            'chrTrash',
            'FavoriteID',
            'LockUserID', 'chrLock',
            'created_at',
            'updated_at'
        ];
        $aliasFields = ['id', 'varAlias'];
        $response = Self::getPowerPanelRecords($moduleFields, $aliasFields)
                ->deleted()
                ->filter($filterArr)
                ->checkMainRecord('Y')
                ->where('chrIsPreview', 'N')
                ->where('chrDraft', 'D')
                ->where('chrTrash', '!=', 'Y')
                ->where(function ($query) use ($userid) {
            $query->where("UserID", '=', $userid)->where('chrPageActive', '=', 'PR')
            ->orWhere('chrPageActive', '!=', 'PR');
        });
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
        $userid = auth()->user()->id;
        $response = false;
        $moduleFields = [
            'id',
            'intAliasId',
            'fkIntDocId',
            'chrPublish',
            'varTitle','varSector',
            'dtDateTime',
            'dtEndDateTime',
            'txtCategories',
            'txtDescription',
            'varShortDescription',
            'chrPageActive',
            'varPassword',
            'chrAddStar',
            'chrDraft',
            'intSearchRank',
            'chrTrash',
            'FavoriteID',
            'LockUserID', 'chrLock',
            'created_at',
            'updated_at'
        ];
        $aliasFields = ['id', 'varAlias'];
        $response = Self::getPowerPanelRecords($moduleFields, $aliasFields)
                ->deleted()
                ->filter($filterArr)
                ->checkMainRecord('Y')
                ->where('chrIsPreview', 'N')
                ->where('chrTrash', 'Y')
                ->where(function ($query) use ($userid) {
            $query->where("UserID", '=', $userid)->where('chrPageActive', '=', 'PR')
            ->orWhere('chrPageActive', '!=', 'PR');
        });
        $response = $response->get();
        return $response;
    }

    public static function getRecordList_tab1($filterArr = false) {
        $response = false;
        $cmsPageFields = ['id', 'intAliasId', 'varTitle','varSector', 'dtDateTime', 'dtEndDateTime', 'txtDescription', 'varShortDescription', 'varMetaTitle', 'varMetaDescription', 'chrPublish', 'LockUserID', 'chrLock', 'chrDelete', 'chrAddStar', 'txtCategories', 'chrPageActive', 'varPassword', 'chrDraft', 'intSearchRank', 'chrTrash', 'FavoriteID', 'created_at', 'updated_at'];
        $aliasFields = ['id', 'varAlias'];
        $MainIDs = Self::distinct()
                ->select("fkMainRecord")
                ->where('chrMain', 'N')
                ->where('chrIsPreview', 'N')
                ->groupBy('fkMainRecord')
                ->deleted()
                ->get()
                ->toArray();
        $MainIDs = array_column($MainIDs, 'fkMainRecord');
        $response = Self::getPowerPanelRecords($cmsPageFields, $aliasFields)
                ->deleted()
                ->where('chrAddStar', 'Y')
                ->where('chrTrash', '!=', 'Y')
                ->filter($filterArr)
                ->whereIn('id', $MainIDs)
                ->get();
        return $response;
    }

    public static function getRecordCount($filterArr = false, $returnCounter = false) {
        $response = 0;
        $cmsPageFields = ['id'];
        $pageQuery = Self::getPowerPanelRecords($cmsPageFields);
        if ($filterArr != false) {
            $pageQuery = $pageQuery->filter($filterArr, $returnCounter);
        }
        $response = $pageQuery->deleted()
                ->where('chrMain', 'Y')
                ->where('chrIsPreview', 'N')
                ->count();
        return $response;
    }

    public static function getRecordCountforList($filterArr = false, $returnCounter = false, $isAdmin = false) {
        $response = 0;
        $cmsPageFields = ['id'];
        $userid = auth()->user()->id;
        $response = Self::getPowerPanelRecords($cmsPageFields);
        if ($filterArr != false) {
            $response = $response->filter($filterArr, $returnCounter);
        }
        $response = $response->deleted()
                ->where(function ($query) use ($userid) {
                    $query->where("UserID", '=', $userid)->where('chrPageActive', '=', 'PR')
                    ->orWhere('chrPageActive', '!=', 'PR');
                })
                ->checkMainRecord('Y')
                ->where('chrIsPreview', 'N')
                ->where('chrTrash', '!=', 'Y')
                ->count();
        return $response;
    }

    public static function getRecordCountListApprovalTab($filterArr = false) {
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
                ->whereIn('id', $MainIDs)
                ->checkStarRecord('Y')
                ->where('chrTrash', '!=', 'Y')
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
                ->where('chrIsPreview', 'N')
                ->count();
        return $response;
    }

    /**
     * This method handels retrival of record count
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getRecordById($id = false, $ignoreDeleteScope = false) {
        $response = false;
        $moduleFields = [
            'id',
            'intAliasId',
            'varTitle','varSector',
            'txtCategories',
            'fkIntDocId',
            'dtDateTime',
            'dtEndDateTime',
            'txtCategories',
            'txtDescription',
            'varShortDescription',
            'intSearchRank',
            'varMetaTitle',
            'varMetaDescription',
            'fkMainRecord',
            'chrPublish',
            'UserID',
            'varPassword',
            'chrDraft',
            'intSearchRank',
            'varTags',
            'chrPageActive',
            'chrTrash',
            'FavoriteID',
            'LockUserID', 'chrLock',
            'created_at',
            'updated_at'
        ];
        $aliasFields = ['id', 'varAlias'];
        $response = Self::getPowerPanelRecords($moduleFields, $aliasFields);
        if (!$ignoreDeleteScope) {
            $response = $response->deleted();
        }
        $response = $response->checkRecordId($id)
                ->first();
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

    #Database Configurations========================================
    /**
     * This method handels retrival of rfps records old version *=Delete it afterwards=*
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */

    public static function getRecords() {
        $response = false;
        $data = ['image', 'alias'];
        if (count($data) > 0) {
            $response = Self::with($data);
        }
        return $response;
    }

    /**
     * This method handels retrival of rfps records
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public static function getFrontRecords($rfpsFields = false, $aliasFields = false) {
        $data = [
            'alias' => function ($query) use ($aliasFields) {
                $query->select($aliasFields);
            },
        ];
        return self::select($rfpsFields)->with($data);
    }

    /**
     * This method handels retrival of rfps records
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public static function getPowerPanelRecords($moduleFields = false, $aliasFields = false, $imageFields = false, $categoryFields = false) {
        $data = [];
        $response = false;
        $response = self::select($moduleFields);
        if ($imageFields != false) {
            $data['image'] = function ($query) use ($imageFields) {
                $query->select($imageFields);
            };
        }
        if ($aliasFields != false) {
            $data['alias'] = function ($query) use ($aliasFields) {
                $query->select($aliasFields)->checkModuleCode();
            };
        }
        if ($categoryFields != false) {
            $data['rfpsCategory'] = function ($query) use ($categoryFields) {
                $query->select($categoryFields);
            };
        }
        if (count($data) > 0) {
            $response = $response->with($data);
        }
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
        $moduleFields = ['id',
            'varTitle','varSector',
            'fkIntDocId',
            'dtDateTime',
            'dtEndDateTime',
            'txtCategories',
            'txtDescription',
            'varShortDescription',
            'intSearchRank',
            'varMetaTitle',
            'varMetaDescription',
            'varMetaDescription',
            'fkMainRecord',
            'chrPublish',
            'UserID',
            'chrPageActive',
            'varPassword',
            'chrDraft',
            'intSearchRank',
            'chrTrash',
            'FavoriteID',
            'LockUserID', 'chrLock',
            'created_at',
            'updated_at'
        ];
        $response = Self::getPowerPanelRecords($moduleFields)->deleted()->checkRecordId($id)->first();
        return $response;
    }

    /**
     * This method handels retrival of front latest Show list
     * @return  Object
     * @since   2017-10-13
     * @author  NetQuick
     */
    public static function getFeaturedList($limit = 10) {
        $response = false;
        $response = Cache::tags(['Rfps'])->get('getRfpsFeaturedList');
        if (empty($response)) {
            $moduleFields = ['varTitle','varSector', 'fkIntDocId', 'varShortDescription', 'txtDescription', 'intAliasId', 'dtDateTime', 'dtEndDateTime', 'chrPageActive', 'varPassword', 'chrDraft', 'intSearchRank', 'chrTrash', 'FavoriteID', 'created_at', 'updated_at'];
            $aliasFields = ['id', 'varAlias'];
            $response = Self::getFrontRecords($moduleFields, $aliasFields)
                    ->deleted()
                    ->publish()
                    ->take($limit)
                    ->get();
            Cache::tags(['Rfps'])->forever('getRfpsFeaturedList', $response);
        }
        return $response;
    }

    /**
     * This method handels alias relation
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public function alias() {
        return $this->belongsTo('App\Alias', 'intAliasId', 'id');
    }

    /**
     * This method handels image relation
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public function image() {
        return $this->belongsTo('App\Image', 'fkIntDocId', 'id');
    }

    /**
     * This method handels video relation
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */

    /**
     * This method handels rfps category relation
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public function rfpsCategory() {
        return $this->belongsTo('Powerpanel\RfpsCategory\Models\RfpsCategory', 'intCategoryId', 'id');
    }

    /**
     * This method handels alias id scope
     * @return  Object
     * @since   2016-07-24
     * @author  NetQuick
     */
    public function scopeCheckAliasId($query, $id) {
        return $query->where('intAliasId', $id);
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
     * This method handels category id scope
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public function scopeCheckCategoryId($query, $id) {
        $response = false;
        $response = $query->where('txtCategories', '=', $id);
        return $response;
        //return $query->where('txtCategories', 'like', '%' . '"' . $id . '"' . '%');
    }

    /**
     * This method handels order scope
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */

    /**
     * This method handels publish scope
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public function scopePublish($query) {
        return $query->where(['chrPublish' => 'Y']);
    }

    /**
     * This method handels delete scope
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public function scopeDeleted($query) {
        return $query->where(['chrDelete' => 'N']);
    }

    /**
     * This method handels Popular Event scope
     * @return  Object
     * @since   2016-08-30
     * @author  NetQuick
     */
    public function scopeLatest($query, $id = false) {
        $response = false;
        $response = $query->orderBy('dtDateTime', 'desc');
        if ($id > 0) {
            $response = $response->where('id', '!=', $id);
        }
        return $response;
    }

    /**
     * This method handels front filter scope
     * @return  Object
     * @since   2016-08-30
     * @author  NetQuick
     */
    public function scopeFrontFilter($query, $id = false, $range = false) {
        echo $range;
        if ($range != false) {
            if ($range[0] != false && $range[1] != false) {
                $query->whereBetween('dtDateTime', $range);
            }
        }
        if ($id != false) {
            return $query->where('txtCategories', 'like', '%' . '"' . $id . '"' . '%');
        }
        return $query;
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
        if (!empty($filterArr['catFilter']) && $filterArr['catFilter'] != ' ') {
            $data = $query->where('txtCategories', '=', $filterArr['catFilter']);
        }
        if (!empty($filterArr['customFilterIdentity']) && $filterArr['customFilterIdentity'] != ' ') {
            $data = $query->where('chrPageActive', $filterArr['customFilterIdentity']);
        }
        if (!empty($filterArr['searchFilter']) && $filterArr['searchFilter'] != ' ') {
            $data = $query->where('varTitle', 'like', "%" . $filterArr['searchFilter'] . "%");
        }
        if (isset($filterArr['ignore']) && !empty($filterArr['ignore'])) {
            $data = $query->whereNotIn('rfps.id', $filterArr['ignore']);
        }
        if (!empty($filterArr['rangeFilter']['from']) && $filterArr['rangeFilter']['to']) {
            $data = $query->whereRaw('DATE(dtDateTime) BETWEEN "' . date('Y-m-d', strtotime(str_replace('/', '-', $filterArr['rangeFilter']['from']))) . '" AND "' . date('Y-m-d', strtotime(str_replace('/', '-', $filterArr['rangeFilter']['to']))) . '"');
        }
        if (!empty($query)) {
            $response = $query;
        }
        return $response;
    }

    public static function getChildGrid() {
        $id = $_REQUEST['id'];
        $response = false;
        $cmsPageFields = [
            'id',
            'intAliasId',
            'dtDateTime',
            'txtCategories',
            'varTitle','varSector',
            'txtDescription',
            'varShortDescription',
            'varMetaTitle',
            'varMetaDescription',
            'chrPublish',
            'chrDelete',
            'created_at',
            'UserID',
            'chrApproved',
            'fkMainRecord',
            'intApprovedBy',
            'chrPageActive',
            'varPassword',
            'chrDraft',
            'intSearchRank',
            'chrTrash',
            'FavoriteID',
            'dtApprovedDateTime',
            'created_at',
            'updated_at'
        ];
        $aliasFields = ['id', 'varAlias'];
        $response = Self::getPowerPanelRecords($cmsPageFields, $aliasFields)
                ->deleted()
                ->where('chrMain', 'N')
                ->where('fkMainRecord', $id)
                ->where('chrIsPreview', 'N')
                ->orderBy('created_at', 'desc')
                ->get();
        return $response;
    }

    public static function approved_data_Listing($request) {
        $id = $request->id;
        $main_id = $request->main_id;
        // $PUserid = $request->PUserid;
        //Select Child Record Data Start
        $response = false;
        $cmsPageFields = [
            'id',
            'intSearchRank',
            'intAliasId',
            'dtDateTime',
            'fkIntDocId',
            'dtEndDateTime',
            'varTitle','varSector',
            'varShortDescription',
            'txtDescription',
            'varMetaTitle',
            'varMetaDescription',
            'chrPublish',
            'chrDelete',
            'UserID',
            'chrApproved',
            'fkMainRecord',
            'txtCategories',
            'chrPageActive',
            'varPassword',
            'chrDraft',
            'intSearchRank',
            'chrTrash',
            'FavoriteID',
            'created_at',
            'updated_at'
        ];
        $aliasFields = ['id', 'varAlias'];
        $response = Self::getPowerPanelRecords($cmsPageFields, $aliasFields)
                ->deleted()
                ->where('chrMain', 'N')
                ->where('chrIsPreview', 'N')
                ->where('id', $id)
                ->orderBy('created_at', 'desc')
                ->first();
        //Select Child Record Data END
        //Update Copy Child Record To Main Record start
        $whereConditions = ['id' => $main_id];
        $updateMainRecord = [
            'varTitle' => $response['varTitle'],
            'dtDateTime' => $response['dtDateTime'],
            'varSector' => $response['varSector'],
            'dtEndDateTime' => $response['dtEndDateTime'],
            'txtDescription' => $response['txtDescription'],
            'varShortDescription' => $response['varShortDescription'],
            'fkIntDocId' => $response['fkIntDocId'],
            'varMetaTitle' => $response['varMetaTitle'],
            'chrAddStar' => 'N',
            'varMetaDescription' => $response['varMetaDescription'],
            'chrDraft' => $response['chrDraft'],
            'intSearchRank' => $response['intSearchRank'],
            'FavoriteID' => $response['FavoriteID'],
            'chrPageActive' => $response['chrPageActive'],
            'chrPublish' => $response['chrPublish'],
            'txtCategories' => $response['txtCategories'],
        ];
        CommonModel::updateRecords($whereConditions, $updateMainRecord, false, 'Powerpanel\Rfps\Models\Rfps');
        //Update Copy Child Record To Main Record end
        $whereConditions_ApproveN = ['fkMainRecord' => $main_id];
        $updateToApproveN = [
            'chrApproved' => 'N',
            'chrLetest' => 'N',
            'intApprovedBy' => '0',
        ];
        CommonModel::updateRecords($whereConditions_ApproveN, $updateToApproveN, false, 'Powerpanel\Rfps\Models\Rfps');
        $whereConditionsApprove = ['id' => $id, 'chrMain' => 'N'];
        $updateToApprove = [
            'chrApproved' => 'Y',
            'chrRollBack' => 'Y',
            'intApprovedBy' => auth()->user()->id,
            'dtApprovedDateTime' => date('Y-m-d H:i:s')
        ];
        CommonModel::updateRecords($whereConditionsApprove, $updateToApprove, false, 'Powerpanel\Rfps\Models\Rfps');
        $msg_show = "Record successfully approved.";
        return $msg_show;
    }

    public static function getFrontList($filterArr = false, $page = 1, $catid = false, $print = false, $categoryid = false, $name = false, $start_date_time = false, $end_date_time = false) {
        $response = false;
        $moduleFields = ['id',
            'intAliasId',
            'varTitle','varSector',
            'txtCategories',
            'fkIntDocId',
            'txtDescription',
            'varShortDescription',
            'dtDateTime',
            'dtEndDateTime',
            'varMetaTitle',
            'varMetaDescription',
            'chrPublish',
            'chrPageActive',
            'varPassword',
            'chrDraft',
            'intSearchRank',
            'chrTrash',
            'FavoriteID',
            'created_at',
            'updated_at'
        ];
        $aliasFields = ['id', 'varAlias'];
        $response = Self::getFrontRecords($moduleFields, $aliasFields)
                ->deleted()
                ->publish();
        if ($categoryid != '') {
            $response = $response->whereRaw(DB::raw('txtCategories="' . $categoryid . '"'));
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
        $response = $response->orderBy('dtDateTime', 'DESC')
                ->where('chrTrash', '!=', 'Y')
                ->where('chrDraft', '!=', 'D')
                ->where('chrIsPreview', 'N')
                ->where('chrMain', 'Y');
        if ($catid != false) {
            $response = $response->where('txtCategories', '=', $catid);
        }
        if ($print == 'print') {
            $response = $response->get();
        } else {
            $response = $response->paginate($page);
        }
        return $response;
    }

    public static function getMonth() {
        $response = false;
        $response = self::select(DB::raw('month(dtDateTime) as month'))
                ->where('chrPublish', '=', 'Y')
                ->where('chrDelete', '=', 'N')
                ->where('chrMain', '=', 'Y')
                ->groupBy('month')
                ->orderBy('month', 'asc')
                ->get();
        return $response;
    }

    public static function getYear() {
        $response = false;
        $response = self::select(DB::raw('year(dtDateTime) as year'))
                ->where('chrPublish', '=', 'Y')
                ->where('chrDelete', '=', 'N')
                ->where('chrMain', '=', 'Y')
                ->groupBy('year')
                ->orderBy('year', 'desc')
                ->get();
        return $response;
    }

    public function scopeDateRange($query) {
        $response = false;
        $response = $query->whereRaw('((dtEndDateTime >= NOW() AND NOW() >= dtDateTime) OR (NOW() >= dtDateTime and dtEndDateTime is null))');
        return $response;
    }

    /**
     * This method handels retrival of front executives detail
     * @return  Object
     * @since   2017-10-13
     * @author  NetQuick
     */
    public static function getFrontDetail($id) {
        $response = false;
        if (empty($response)) {
            $moduleFields = ['id',
                'intAliasId',
                'varTitle','varSector',
                'fkIntDocId',
                'txtCategories',
                'txtDescription',
                'varShortDescription',
                'dtDateTime',
                'dtEndDateTime',
                'varMetaTitle',
                'varMetaDescription',
                'chrPublish',
                'chrPageActive',
                'varPassword',
                'chrDraft',
                'intSearchRank',
                'chrTrash',
                'FavoriteID',
                'created_at',
                'updated_at'];
            $aliasFields = ['id', 'varAlias'];
            // $response = Cache::tags(['Rfps'])->get('getFrontRfpsDetail_' . $id);
                $response = Self::getFrontRecords($moduleFields, $aliasFields)
                        ->deleted()
                        ->publish()
                        ->where('fkMainRecord', 0)
                        ->checkAliasId($id)
                        ->first();
            // Cache::tags(['Rfps'])->forever('getFrontRfpsDetail_' . $id, $response);
        }
        return $response;
    }

    /**
     * This method handels retrival of front latest executives list
     * @return  Object
     * @since   2017-10-13
     * @author  NetQuick
     */
    public static function getLatestList($id = false) {
        $response = false;
        $moduleFields = ['id',
            'intAliasId',
            'varTitle','varSector',
            'fkIntDocId',
            'txtDescription',
            'varShortDescription',
            'dtDateTime',
            'dtEndDateTime',
            'varMetaTitle',
            'varMetaDescription',
            'chrPublish',
            'chrPageActive',
            'varPassword',
            'chrDraft',
            'intSearchRank',
            'chrTrash',
            'FavoriteID',
            'created_at',
            'updated_at'];
        $aliasFields = ['id', 'varAlias'];
        $response = Cache::tags(['Executives'])->get('getFrontLatestExecutivesList_' . $id);
        if (empty($response)) {
            $response = Self::getFrontRecords($moduleFields, $aliasFields)
                    ->deleted()
                    ->publish()
                    ->latest($id)
                    ->take(5)
                    ->get();
            Cache::tags(['Executives'])->forever('getFrontLatestExecutivesList_' . $id, $response);
        }
        return $response;
    }

    public static function getBuilderRecordList($filterArr = []) {
        $response = false;
        $moduleFields = [
            'rfps.id',
            'rfps.intAliasId',
            'rfps.varTitle','varSector',
            'rfps.txtCategories',
            DB::raw('IFNULL(COUNT(nq_page_hits.id), 0) AS hits'),
            'rfps.chrPublish',
            'rfps.chrDelete',
            'rfps.dtDateTime',
            'rfps.dtEndDateTime',
            'rfps.updated_at'
        ];
        $response = Self::getPowerPanelRecords($moduleFields, false, false, false, false)
                ->filter($filterArr);
        $response = $response->leftJoin('page_hits', 'rfps.intAliasId', '=', 'page_hits.fkIntAliasId')
                ->where('rfps.chrPublish', 'Y')
                ->where('rfps.chrDelete', 'N')
                ->where('rfps.chrMain', 'Y')
                ->where('rfps.chrTrash', '!=', 'Y')
                ->where('rfps.chrDraft', '!=', 'D')
                ->where('rfps.chrIsPreview', 'N')
                ->groupBy('rfps.id')
                ->get();
        return $response;
    }

    public static function getBuilderRfps($fields, $recIds) {
        $response = false;
        $moduleFields = ['id',
            'intAliasId',
            'varTitle','varSector',
            'txtCategories',
            'fkIntDocId',
            'txtDescription',
            'varShortDescription',
            'dtDateTime',
            'dtEndDateTime',
            'varMetaTitle',
            'varMetaDescription',
            'chrPublish',
            'chrPageActive',
            'varPassword',
            'chrDraft',
            'intSearchRank',
            'chrTrash',
            'FavoriteID',
            'created_at',
            'updated_at'];
        $aliasFields = ['id', 'varAlias'];
        if (empty($response)) {
            $response = Self::getFrontRecords($moduleFields, $aliasFields)
                    ->whereIn('id', $recIds)
                    ->where('chrMain', 'Y')
                    ->where('chrIsPreview', 'N')
                    ->where('chrTrash', '!=', 'Y')
                    ->where('chrDraft', '!=', 'D')
                    ->deleted()
                    ->publish()
                    ->orderByRaw(DB::raw("FIELD(id, " . implode(',', $recIds) . " )"));
            $response = $response->get();
        }
        return $response;
    }

    public static function getAllRfps($fields, $limit, $sdate, $edate, $rfpscat) {
        $response = false;
        $moduleFields = ['id',
            'intAliasId',
            'varTitle','varSector',
            'txtCategories',
            'fkIntDocId',
            'txtDescription',
            'varShortDescription',
            'dtDateTime',
            'dtEndDateTime',
            'varMetaTitle',
            'varMetaDescription',
            'chrPublish',
            'chrPageActive',
            'varPassword',
            'chrDraft',
            'intSearchRank',
            'chrTrash',
            'FavoriteID',
            'created_at',
            'updated_at'];
        $aliasFields = ['id', 'varAlias'];
        if (empty($response)) {
            $response = Self::getFrontRecords($moduleFields, $aliasFields)
                    ->where('chrMain', 'Y');
            if ($rfpscat != '') {
                $response = $response->where('txtCategories', $rfpscat);
            }

            if ($sdate != '' && $edate != '') {
                $response = $response->whereRaw('(DATE(dtDateTime)>="' . date('Y-m-d', strtotime(str_replace('/', '-', $sdate))) . '" AND (DATE(dtDateTime)<="' . date('Y-m-d', strtotime(str_replace('/', '-', $edate))) . '") OR ("' . date('Y-m-d', strtotime(str_replace('/', '-', $sdate))) . '" >= dtDateTime and dtEndDateTime is null))');
            } else if ($sdate != '') {
                $response = $response->whereRaw('DATE(dtDateTime)>="' . date('Y-m-d', strtotime(str_replace('/', '-', $sdate))) . '"');
            } else if ($edate != '') {
                $response = $response->whereRaw('DATE(dtDateTime)<="' . date('Y-m-d', strtotime(str_replace('/', '-', $edate))) . '"');
            }

            $response = $response->where('chrIsPreview', 'N')
                    ->deleted()
                    ->publish()
                    ->where('chrTrash', '!=', 'Y')
                    ->where('chrDraft', '!=', 'D')
                    ->orderBy('dtDateTime', 'desc');
            if ($limit != '') {
                $response = $response->limit($limit);
            }
            if (Request::segment(1) != '') {
                $response = $response->paginate(2);
            } else {
                $response = $response->get();
            }
        }
        return $response;
    }

    //Start Draft Count of Records 
    public static function getRecordCountforListDarft($filterArr = false, $returnCounter = false, $isAdmin = false, $ignoreId = array()) {
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
                ->where('chrTrash', '!=', 'Y')
                ->where(function ($query) use ($userid) {
                    $query->where("UserID", '=', $userid)->where('chrPageActive', '=', 'PR')
                    ->orWhere('chrPageActive', '!=', 'PR');
                })
                ->count();
        return $response;
    }

    //End Draft Count of Records 
    //Start Trash Count of Records 
    public static function getRecordCountforListTrash($filterArr = false, $returnCounter = false, $isAdmin = false, $ignoreId = array()) {
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
                })
                ->count();
        return $response;
    }

    //End Trash Count of Records 
    //Start Favorite Count of Records 
    public static function getRecordCountforListFavorite($filterArr = false, $returnCounter = false, $isAdmin = false, $ignoreId = array()) {
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
                })
                ->count();
        return $response;
    }

    //End Favorite Count of Records 
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

    //End Favorite Count of Records
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
