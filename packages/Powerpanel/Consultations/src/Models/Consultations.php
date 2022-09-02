<?php

namespace Powerpanel\Consultations\Models;

use Illuminate\Database\Eloquent\Model;
use Cache;
use Request;
use App\CommonModel;
use DB;

class Consultations extends Model {

    protected $table = 'consultations';
    protected $fillable = [
        'id',
        'intAliasId',
        'dtDateTime',
        'dtEndDateTime',
        'varSector',
        'fkIntDocId',
        'varTitle',
        'varShortDescription',
        'txtCategories',
        'txtDescription',
        'chrPublish',
        'chrDelete',
        'intSearchRank',
        'varMetaTitle',
        'varTags',
        'varMetaDescription',
        'chrPageActive',
        'varPassword',
        'chrDraft',
        'intSearchRank',
        'chrTrash',
        'FavoriteID',
        'intDisplayOrder',
        'LockUserID', 
        'chrLock',
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
        $response = Cache::tags(['Consultations'])->get('getConsultationsRecordIdByAliasID_' . $aliasID);
        if (empty($response)) {
            $response = Self::Select('id')->deleted()->publish()->checkAliasId($aliasID)->first();
            Cache::tags(['Consultations'])->forever('getConsultationsRecordIdByAliasID_' . $aliasID, $response);
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
        $moduleFields = ['id', 'intAliasId', 'varTitle','fkIntDocId','varSector', 'txtCategories', 'intDisplayOrder', 'dtDateTime'];
        $aliasFields = ['id', 'varAlias'];
        $response = Cache::tags(['Consultations'])->get('getLatestForHome_' . $limit);
        if (empty($response)) {
            $response = Self::getFrontRecords($moduleFields, $aliasFields)
                    ->deleted()
                    ->publish()
                    ->dateRange()
                    ->where('chrMain', 'Y')
                    ->take($limit)
                    ->orderBy('dtDateTime', 'DESC')
                    ->get();
            Cache::tags(['Consultations'])->forever('getLatestForHome_' . $limit, $response);
        }
        return $response;
    }

    /**
     * This method handels retrival of front latest Consultations list
     * @return  Object
     * @since   2017-10-13
     * @author  NetQuick
     */
    public static function getFrontPopularList($id = false) {
        $response = false;
        $moduleFields = ['id', 'varTitle','fkIntDocId','varSector', 'varShortDescription', 'intAliasId', 'dtDateTime', 'dtEndDateTime', 'chrPageActive', 'varPassword', 'chrDraft', 'intSearchRank', 'intDisplayOrder', 'chrTrash', 'FavoriteID', 'created_at', 'updated_at'];
        $aliasFields = ['id', 'varAlias'];
        $response = Cache::tags(['Consultations'])->get('getFrontPopularConsultationsList_' . $id);
        if (empty($response)) {
            $response = Self::getFrontRecords($moduleFields, $aliasFields)
                    ->deleted()
                    ->publish()
                    ->latest($id)
                    ->take(3)
                    ->get();
            Cache::tags(['Consultations'])->forever('getFrontPopularConsultationsList_' . $id, $response);
        }
        return $response;
    }

    public static function getNewRecordsCount($isAdmin = false, $userRoleSector) {
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
                ->where('chrTrash', '!=', 'Y');
                if(!$isAdmin){
                    $response = $response->where('varSector', $userRoleSector);
                }
                $response = $response->checkStarRecord('Y')
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
            'varTitle',
            'fkIntDocId',
            'varSector',
            'txtCategories',
            'varShortDescription',
            'txtDescription',
            'varMetaTitle',
            'varTags',
            'varMetaDescription',
            'chrPublish',
            'chrDelete',
            'created_at',
            'UserID',
            'intDisplayOrder',
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
    public static function getRecordList($filterArr = false, $isAdmin = false, $userRoleSector = false) {
        $userid = auth()->user()->id;
        $response = false;
        $moduleFields = [
            'id',
            'fkMainRecord',
            'intAliasId',
            'chrPublish',
            'varTitle',
            'fkIntDocId',
            'varSector',
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
            'intDisplayOrder',
            'chrTrash',
            'FavoriteID',
            'LockUserID', 
            'chrLock',
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
                });
                if(!$isAdmin){
                    $response = $response->where('varSector', $userRoleSector);
                }
                $response = $response->checkMainRecord('Y')
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
    public static function getRecordListFavorite($filterArr = false, $isAdmin = false, $userRoleSector) {
        $userid = auth()->user()->id;
        $response = false;
        $moduleFields = [
            'id',
            'intAliasId',
            'chrPublish',
            'varTitle',
            'fkIntDocId',
            'varSector',
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
            'intDisplayOrder',
            'FavoriteID',
            'LockUserID',
            'chrLock',
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
        $userid = auth()->user()->id;
        $response = false;
        $moduleFields = [
            'id',
            'intAliasId',
            'chrPublish',
            'varSector',
            'varTitle',
            'fkIntDocId',
            'dtDateTime',
            'dtEndDateTime',
            'txtCategories',
            'txtDescription',
            'varShortDescription',
            'chrPageActive',
            'varPassword',
            'chrAddStar',
            'chrDraft',
            'intDisplayOrder',
            'intSearchRank',
            'chrTrash',
            'FavoriteID',
            'LockUserID',
            'chrLock',
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
    public static function getRecordListTrash($filterArr = false, $isAdmin = false, $userRoleSector) {
        $userid = auth()->user()->id;
        $response = false;
        $moduleFields = [
            'id',
            'intAliasId',
            'chrPublish',
            'varTitle',
            'fkIntDocId',
            'varSector',
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
            'intDisplayOrder',
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
        if(!$isAdmin){
            $response = $response->where('varSector', $userRoleSector);
        }
        $response = $response->get();
        return $response;
    }

    public static function getRecordList_tab1($filterArr = false, $isAdmin = false, $userRoleSector) {
        $response = false;
        $cmsPageFields = ['id', 'fkMainRecord' ,'intAliasId', 'varTitle','fkIntDocId','varSector', 'dtDateTime', 'dtEndDateTime', 'txtDescription', 'varShortDescription', 'varMetaTitle', 'varTags','intDisplayOrder', 'varMetaDescription', 'chrPublish', 'LockUserID', 'chrLock', 'chrDelete', 'chrAddStar', 'txtCategories', 'chrPageActive', 'varPassword', 'chrDraft', 'intSearchRank', 'chrTrash', 'FavoriteID', 'created_at', 'updated_at'];
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
                ->filter($filterArr)
                ->whereIn('id', $MainIDs)
                ->where('chrTrash', '!=', 'Y');
                if(!$isAdmin){
                    $response = $response->where('varSector', $userRoleSector);
                }
                $response = $response->where('chrAddStar', 'Y')
                ->get();
        return $response;
    }

    public static function getRecordCount($filterArr = false, $returnCounter = false, $isAdmin = false, $userRoleSector) {
        $response = 0;
        $cmsPageFields = ['id'];
        $pageQuery = Self::getPowerPanelRecords($cmsPageFields);
        if ($filterArr != false) {
            $pageQuery = $pageQuery->filter($filterArr, $returnCounter);
        }
        $response = $pageQuery->deleted()
                ->where('chrMain', 'Y')
                ->where('chrIsPreview', 'N');
                if(!$isAdmin){
                    $response = $response->where('varSector', $userRoleSector);
                }
                $response = $response->count();
        return $response;
    }

    public static function getRecordCountforList($filterArr = false, $returnCounter = false, $isAdmin = false, $userRoleSector) {
        $response = 0;
        $cmsPageFields = ['id'];
        $userid = auth()->user()->id;
        $response = Self::getPowerPanelRecords($cmsPageFields);
        if ($filterArr != false) {
            $response = $response->filter($filterArr, $returnCounter);
        }
        if(!$isAdmin){
            $response = $response->where('varSector', $userRoleSector);
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

    public static function getRecordCountListApprovalTab($filterArr = false, $isAdmin = false, $userRoleSector) {
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
            'varTitle',
            'fkIntDocId',
            'varSector',
            'txtCategories',
            'dtDateTime',
            'dtEndDateTime',
            'txtDescription',
            'varShortDescription',
            'intSearchRank',
            'varMetaTitle',
            'varTags',
            'varMetaDescription',
            'fkMainRecord',
            'chrPublish',
            'UserID',
            'chrAddStar',
            'varPassword',
            'chrDraft',
            'intSearchRank',
            'intDisplayOrder',
            'chrPageActive',
            'chrTrash',
            'FavoriteID',
            'LockUserID',
            'chrLock',
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

    public function scopeOrderCheck($query, $order)
    {
        return $query->where('intDisplayOrder', $order);
    }

    #Database Configurations========================================
    /**
     * This method handels retrival of Consultations records old version *=Delete it afterwards=*
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
     * This method handels retrival of Consultations records
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public static function getFrontRecords($consultationsFields = false, $aliasFields = false) {
        $data = [
            'alias' => function ($query) use ($aliasFields) {
                $query->select($aliasFields);
            },
        ];
        return self::select($consultationsFields)->with($data);
    }

    /**
     * This method handels retrival of Consultations records
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public static function getPowerPanelRecords($moduleFields = false, $aliasFields = false, $imageFields = false, $categoryFields = false) {
        $data = [];
        $response = false;
        $response = self::select($moduleFields);

        $data['child'] = function ($query) {
            $query->select(['id','varTitle','fkMainRecord'])
                    ->where('chrDelete', 'N')
                    ->where('dtApprovedDateTime','!=',NULL);
        };

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
        // if ($categoryFields != false) {
        //     $data['newsCategory'] = function ($query) use ($categoryFields) {
        //         $query->select($categoryFields);
        //     };
        // }
        if (count($data) > 0) {
            $response = $response->with($data);
        }
        return $response;
    }

    public function child() {
		return $this->hasMany('Powerpanel\Consultations\Models\Consultations', 'fkMainRecord', 'id');
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
            'varTitle',
            'varSector',
            'dtDateTime',
            'fkIntDocId',
            'dtEndDateTime',
            'txtCategories',
            'txtDescription',
            'varShortDescription',
            'intSearchRank',
            'varMetaTitle',
            'varTags',
            'varMetaDescription',
            'varMetaDescription',
            'fkMainRecord',
            'chrPublish',
            'UserID',
            'chrPageActive',
            'varPassword',
            'chrDraft',
            'intSearchRank',
            'intDisplayOrder',
            'chrTrash',
            'FavoriteID',
            'LockUserID', 
            'chrLock',
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
        $response = Cache::tags(['Consultations'])->get('getConsultationsFeaturedList');
        if (empty($response)) {
            $moduleFields = ['varTitle','fkIntDocId','varSector', 'varShortDescription', 'intDisplayOrder', 'txtDescription', 'intAliasId', 'dtDateTime', 'dtEndDateTime', 'chrPageActive', 'varPassword', 'chrDraft', 'intSearchRank', 'chrTrash', 'FavoriteID', 'created_at', 'updated_at'];
            $aliasFields = ['id', 'varAlias'];
            $response = Self::getFrontRecords($moduleFields, $aliasFields)
                    ->deleted()
                    ->publish()
                    ->take($limit)
                    ->get();
            Cache::tags(['Consultations'])->forever('getConsultationsFeaturedList', $response);
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
     * This method handels Consultations category relation
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    // public function newsCategory() {
    //     return $this->belongsTo('Powerpanel\NewsCategory\Models\NewsCategory', 'intCategoryId', 'id');
    // }

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
        if (!empty($filterArr['sectorFilter']) && $filterArr['sectorFilter'] != ' ') {
            $data = $query->where('varSector', $filterArr['sectorFilter']);
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
            $data = $query->whereNotIn('consultations.id', $filterArr['ignore']);
        }
        // if (!empty($filterArr['rangeFilter']['from']) && $filterArr['rangeFilter']['to']) {
        //     $data = $query->whereRaw('DATE(dtDateTime) BETWEEN "' . date('Y-m-d', strtotime(str_replace('/', '-', $filterArr['rangeFilter']['from']))) . '" AND "' . date('Y-m-d', strtotime(str_replace('/', '-', $filterArr['rangeFilter']['to']))) . '"');
        // }
        if (!empty($filterArr['start']) && $filterArr['start'] != ' ') {
            $data = $query->whereRaw('DATE(dtDateTime) >= DATE("' . date('Y-m-d', strtotime(str_replace('/', '-', $filterArr['start']))) . '")');
        }
        if (!empty($filterArr['start']) && $filterArr['start'] != '' &&  empty($filterArr['end']) && $filterArr['end'] == '') {
                $data = $query->whereRaw('DATE(dtDateTime) = DATE("' . date('Y-m-d', strtotime(str_replace('/', '-', $filterArr['start']))) . '")');
        }
        if (!empty($filterArr['end']) && $filterArr['end'] != ' ') {
                $data = $query->whereRaw('DATE(dtDateTime) <= DATE("' . date('Y-m-d', strtotime(str_replace('/', '-', $filterArr['end']))) . '") AND dtDateTime IS NOT null');
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
            'varTitle',
            'fkIntDocId',
            'varSector',
            'txtDescription',
            'varShortDescription',
            'varMetaTitle',
            'varTags',
            'varMetaDescription',
            'chrPublish',
            'chrDelete',
            'created_at',
            'intDisplayOrder',
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
            'dtEndDateTime',
            'varTitle',
            'fkIntDocId',
            'varSector',
            'varShortDescription',
            'txtDescription',
            'varMetaTitle',
            'varTags',
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
            'intDisplayOrder',
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
            'fkIntDocId' => $response['fkIntDocId'],
            'dtDateTime' => $response['dtDateTime'],
            'varSector' => $response['varSector'],
            'dtEndDateTime' => $response['dtEndDateTime'],
            'txtDescription' => $response['txtDescription'],
            'varShortDescription' => $response['varShortDescription'],
            'varMetaTitle' => $response['varMetaTitle'],
            'chrAddStar' => 'N',
            'varMetaDescription' => $response['varMetaDescription'],
            'chrDraft' => $response['chrDraft'],
            'intSearchRank' => $response['intSearchRank'],
            'FavoriteID' => $response['FavoriteID'],
            'chrPageActive' => $response['chrPageActive'],
            'chrPublish' => $response['chrPublish'],
            'txtCategories' => $response['txtCategories'],
            'intDisplayOrder' => $response['intDisplayOrder'],
        ];
        CommonModel::updateRecords($whereConditions, $updateMainRecord, false, 'Powerpanel\Consultations\Models\Consultations');
        //Update Copy Child Record To Main Record end
        $whereConditions_ApproveN = ['fkMainRecord' => $main_id];
        $updateToApproveN = [
            'chrApproved' => 'N',
            'chrLetest' => 'N',
            'intApprovedBy' => '0',
        ];
        CommonModel::updateRecords($whereConditions_ApproveN, $updateToApproveN, false, 'Powerpanel\Consultations\Models\Consultations');
        $whereConditionsApprove = ['id' => $id, 'chrMain' => 'N'];
        $updateToApprove = [
            'chrApproved' => 'Y',
            'chrRollBack' => 'Y',
            'intApprovedBy' => auth()->user()->id,
            'dtApprovedDateTime' => date('Y-m-d H:i:s')
        ];
        CommonModel::updateRecords($whereConditionsApprove, $updateToApprove, false, 'Powerpanel\Consultations\Models\Consultations');
        $msg_show = "Record successfully approved.";
        return $msg_show;
    }

    public static function getFrontList($filterArr = false, $page = 1, $catid = false, $print = false, $categoryid = false, $name = false, $start_date_time = false, $end_date_time = false) {
        $response = false;
        $moduleFields = ['id',
            'intAliasId',
            'varTitle',
            'fkIntDocId',
            'varSector',
            'txtCategories',
            'txtDescription',
            'varShortDescription',
            'dtDateTime',
            'dtEndDateTime',
            'varMetaTitle',
            'varTags',
            'varMetaDescription',
            'chrPublish',
            'chrPageActive',
            'varPassword',
            'chrDraft',
            'intSearchRank',
            'intDisplayOrder',
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
public static function getFrontListPopup($filterArr = false)
    {
        $response = false;
        $moduleFields = [ 'id',
            'intAliasId',
            'varTitle',
            'fkIntDocId',
            'varSector',
            'txtCategories',
            'txtDescription',
            'varShortDescription',
            'dtDateTime',
            'dtEndDateTime',
            'varMetaTitle',
            'varTags',
            'varMetaDescription',
            'chrPublish',
            'chrPageActive',
            'varPassword',
            'chrDraft',
            'intSearchRank',
            'intDisplayOrder',
            'chrTrash',
            'FavoriteID',
            'created_at',
            'updated_at'];
        $aliasFields = ['id', 'varAlias'];
        $response = Cache::tags(['Blogs'])->get('getFrontBlogsList_');
        if (empty($response)) {
            $response = Self::getFrontRecords($moduleFields, $aliasFields)
                ->deleted()
                ->publish();
          
          
            $response = $response->where('chrTrash', '!=', 'Y')
                ->where('chrDraft', '!=', 'D')
                ->where('chrIsPreview', 'N')
                ->where('chrMain', 'Y')
                ->get();
            
            Cache::tags(['Blogs'])->forever('getFrontBlogsList_' , $response);
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
                'varTitle',
                'fkIntDocId',
                'varSector',
                'txtCategories',
                'txtDescription',
                'varShortDescription',
                'dtDateTime',
                'dtEndDateTime',
                'varMetaTitle',
                'varTags',
                'varMetaDescription',
                'chrPublish',
                'varTags',
                'chrPageActive',
                'varPassword',
                'chrDraft',
                'intSearchRank',
                'intDisplayOrder',
                'chrTrash',
                'FavoriteID',
                'created_at',
                'updated_at'];
            $aliasFields = ['id', 'varAlias'];
            // $response = Cache::tags(['Consultations'])->get('getFrontConsultationsDetail_' . $id);
                $response = Self::getFrontRecords($moduleFields, $aliasFields)
                        ->deleted()
                        ->publish()
                        ->where('fkMainRecord', 0)
                        ->checkAliasId($id)
                        ->first();
            // Cache::tags(['Consultations'])->forever('getFrontConsultationsDetail_' . $id, $response);
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
            'varTitle',
            'fkIntDocId',
            'varSector',
            'txtDescription',
            'varShortDescription',
            'dtDateTime',
            'dtEndDateTime',
            'varMetaTitle',
            'varTags',
            'varMetaDescription',
            'chrPublish',
            'chrPageActive',
            'varPassword',
            'chrDraft',
            'intSearchRank',
            'intDisplayOrder',
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
            'consultations.id',
            'consultations.intAliasId',
            'consultations.varTitle','varSector',
            'consultations.txtCategories',
            DB::raw('IFNULL(COUNT(nq_page_hits.id), 0) AS hits'),
            'consultations.chrPublish',
            'consultations.chrDelete',
            'consultations.dtDateTime',
            'consultations.dtEndDateTime',
            'consultations.updated_at'
        ];
        $response = Self::getPowerPanelRecords($moduleFields, false, false, false, false)
                ->filter($filterArr);
        $response = $response->leftJoin('page_hits', 'consultations.intAliasId', '=', 'page_hits.fkIntAliasId')
                ->where('consultations.chrPublish', 'Y')
                ->where('consultations.chrDelete', 'N')
                ->where('consultations.chrMain', 'Y')
                ->where('consultations.chrTrash', '!=', 'Y')
                ->where('consultations.chrDraft', '!=', 'D')
                ->where('consultations.chrIsPreview', 'N')
                ->groupBy('consultations.id')
                ->get();
        return $response;
    }

    public static function getBuilderNews($fields, $recIds) {
        $response = false;
        $moduleFields = ['id',
            'intAliasId',
            'varTitle',
            'varSector',
            'fkIntDocId',
            'txtCategories',
            'txtDescription',
            'varShortDescription',
            'dtDateTime',
            'dtEndDateTime',
            'varMetaTitle',
            'varTags',
            'varMetaDescription',
            'chrPublish',
            'chrPageActive',
            'varPassword',
            'chrDraft',
            'intSearchRank',
            'intDisplayOrder',
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

    public static function getFrontLatestConsultations() {

        $response = false;

        $moduleFields = [
            'id',
            'intAliasId',
            'varTitle',
            'fkIntDocId',
            'varSector',
            'txtCategories',
            'dtDateTime',
            'dtEndDateTime',
            'txtDescription',
            'varShortDescription',
            'intSearchRank',
            'varMetaTitle',
            'varTags',
            'varMetaDescription',
            'fkMainRecord',
            'chrPublish',
            'UserID',
            'varPassword',
            'chrDraft',
            'intSearchRank',
            'intDisplayOrder',
            'chrPageActive',
            'chrTrash',
            'FavoriteID',
            'LockUserID',
            'chrLock',
            'created_at',
            'updated_at'
        ];
        $aliasFields = ['id', 'varAlias'];

        if (empty($response)) {

            $response = Self::getFrontRecords($moduleFields, $aliasFields)->where('chrMain', 'Y');
            $response = $response
                                ->where('chrIsPreview', 'N')
                                ->deleted()
                                ->publish()
                                ->where('chrTrash', '!=', 'Y')
                                ->where('chrDraft', '!=', 'D')
                                ->orderBy('dtDateTime', 'desc');
            $response = $response->take(2)->get();
        }
        
        return $response;
    }

    public static function getAllConsultations($fields, $limit, $sdate, $edate, $newscat, $dbFilter=false,$sector_consultation) {
        $response = false;
        $moduleFields = [
            'id',
            'intAliasId',
            'varTitle',
            'fkIntDocId',
            'varSector',
            'txtCategories',
            'dtDateTime',
            'dtEndDateTime',
            'txtDescription',
            'varShortDescription',
            'intSearchRank',
            'varMetaTitle',
            'varTags',
            'varMetaDescription',
            'fkMainRecord',
            'chrPublish',
            'UserID',
            'varPassword',
            'chrDraft',
            'intSearchRank',
            'intDisplayOrder',
            'chrPageActive',
            'chrTrash',
            'FavoriteID',
            'LockUserID',
            'chrLock',
            'created_at',
            'updated_at'
        ];
        $aliasFields = ['id', 'varAlias'];
        if (empty($response)) {
            $response = Self::getFrontRecords($moduleFields, $aliasFields)
                    ->where('chrMain', 'Y');
                    //->orderBy('dtDateTime', 'desc');
            if ($newscat != '') {
                $response = $response->where('txtCategories', $newscat);
            }

            if ($sdate != '' && $edate != '') {
                $response = $response->whereRaw('(DATE(dtDateTime)>="' . date('Y-m-d', strtotime(str_replace('/', '-', $sdate))) . '" AND (DATE(dtDateTime)<="' . date('Y-m-d', strtotime(str_replace('/', '-', $edate))) . '") OR ("' . date('Y-m-d', strtotime(str_replace('/', '-', $sdate))) . '" >= dtDateTime and dtEndDateTime is null))');
            } else if ($sdate != '') {
                $response = $response->whereRaw('DATE(dtDateTime)>="' . date('Y-m-d', strtotime(str_replace('/', '-', $sdate))) . '"');
            } else if ($edate != '') {
                $response = $response->whereRaw('DATE(dtDateTime)<="' . date('Y-m-d', strtotime(str_replace('/', '-', $edate))) . '"');
            }

            if(isset($dbFilter['year']) && !empty($dbFilter['year'])) {
                $years = $dbFilter['year'];
                $response->where(function($response) use($years) {
                    foreach ($years as $year) {
                        $response->whereYear('dtDateTime', '=', $year, 'or');
                    }
                });
            } else {
                // $response->whereYear('dtDateTime', '=', date('Y'));
                $response->whereYear('dtDateTime', '>=','2021');
            }

            if(isset($sector_consultation) && !empty($sector_consultation)) {
                $response->where('varSector', '=', strtolower($sector_consultation));
            }
            
            if(isset($dbFilter['category']) && !empty($dbFilter['category']) && strtolower($dbFilter['category']) != 'all') {
                $response->where('varSector', '=', strtolower($dbFilter['category']));
            }

            if(isset($dbFilter['consultationType']) && !empty($dbFilter['consultationType']) && strtolower($dbFilter['consultationType']) != 'all') {
                $response->where('txtCategories', '=', strtolower($dbFilter['consultationType']));
            }

            $response = $response->where('chrIsPreview', 'N')
                    ->deleted()
                    ->publish()
                    ->where('chrTrash', '!=', 'Y')
                    ->where('chrDraft', '!=', 'D');
                    
            if(isset($dbFilter['sortVal']) && !empty($dbFilter['sortVal'])) {
                if($dbFilter['sortVal'] == 'sortByNew') {
                    $response->orderBy('dtDateTime', 'DESC');
                } 
                elseif ($dbFilter['sortVal'] == 'sortByAsc') {
                    $response->orderBy('varTitle', 'ASC');
                } 
                elseif ($dbFilter['sortVal'] == 'sortByDesc') {
                    $response->orderBy('varTitle', 'DESC');
                }
                else {
                    $response->orderBy('dtDateTime', 'DESC');
                }
            } else {
                $response->orderBy('dtDateTime', 'DESC')->orderBy('created_at', 'DESC');
            }

            if(isset($dbFilter['search_action']) && !empty($dbFilter['search_action'])) {
                $response->where('varTitle', 'LIKE', "%{$dbFilter['search_action']}%");
            }

//            if ($limit != '') {
//                $response = $response->limit($limit);
//            }
            if (Request::segment(1) != '') {
                $pageNumber = 1;
                if(isset($dbFilter['pageNumber']) && !empty($dbFilter['pageNumber'])) {
                    $pageNumber = $dbFilter['pageNumber'];
                }
                 if (isset($dbFilter['limits']) && !empty($dbFilter['limits'])) {
                    $limit = $dbFilter['limits'];
                }
                $response = $response->paginate($limit, ['*'], 'page', $pageNumber);
            } else {
                $response = $response->get();
            }
        }
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
        if(!$isAdmin){
            $response = $response->where('varSector', $userRoleSector);
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
    public static function getRecordCountforListTrash($filterArr = false, $returnCounter = false, $isAdmin = false, $ignoreId = array(), $userRoleSector) {
        $response = 0;
        $cmsPageFields = ['id'];
        $userid = auth()->user()->id;
        $response = Self::getPowerPanelRecords($cmsPageFields);
        if ($filterArr != false) {
            $response = $response->filter($filterArr, $returnCounter);
        }
        if(!$isAdmin){
            $response = $response->where('varSector', $userRoleSector);
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
    public static function getRecordCountforListFavorite($filterArr = false, $returnCounter = false, $isAdmin = false, $ignoreId = array(), $userRoleSector) {
        $response = 0;
        $cmsPageFields = ['id'];
        $userid = auth()->user()->id;
        $response = Self::getPowerPanelRecords($cmsPageFields);
        if ($filterArr != false) {
            $response = $response->filter($filterArr, $returnCounter);
        }
        if(!$isAdmin){
            $response = $response->where('varSector', $userRoleSector);
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
