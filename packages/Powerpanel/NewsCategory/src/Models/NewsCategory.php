<?php

namespace Powerpanel\NewsCategory\Models;

use DB;
use Illuminate\Database\Eloquent\Model;
use Cache;
use App\CommonModel;

class NewsCategory extends Model {

    protected $table = 'news_category';
    protected $fillable = [
        'id',
        'varTitle','varSector',
        'intAliasId',
        'chrLetest',
        'intSearchRank',
        'intParentCategoryId',
        'intDisplayOrder',
        'varMetaTitle',
        'varMetaDescription',
        'chrPublish',
        'chrDelete',
        'created_at',
        'updated_at',
        'dtDateTime',
        'dtEndDateTime',
        'chrPageActive',
        'varPassword',
        'chrDraft',
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
        $response = Cache::tags(['NewsCategory'])->get('getPublicationsCatRecordIdByAliasID' . $aliasID);
        if (empty($response)) {
            $response = Self::Select('id', 'varTitle','varSector', 'intAliasId')
                    ->deleted()
                    ->publish()
                    ->checkAliasId($aliasID)
                    ->first();
            Cache::tags(['NewsCategory'])->forever('getPublicationsCatRecordIdByAliasID' . $aliasID, $response);
        }
        return $response;
    }

    /**
     * This method handels retrival of front blog detail
     * @return  Object
     * @since   2017-10-13
     * @author  NetQuick
     */
    public static function getRecordDataByAliasID($aliasID) {
        $response = false;
        $response = Cache::tags(['NewsCategory'])->get('getPublicationsCatRecordDataByAliasID' . $aliasID);
        if (empty($response)) {
            $response = Self::Select('id', 'varTitle','varSector', 'intAliasId', 'varMetaTitle', 'varMetaDescription', 'chrPageActive', 'varPassword','UserID', 'chrDraft', 'intSearchRank', 'chrTrash', 'FavoriteID', 'created_at', 'updated_at')
                    ->deleted()
                    ->publish()
                    ->dateRange()
                    ->where('chrMain', 'Y')
                    ->where('chrTrash', '!=', 'Y')
                    ->where('chrDraft', '!=', 'D')
                    ->checkAliasId($aliasID)
                    ->first();
            Cache::tags(['NewsCategory'])->forever('getPublicationsCatRecordDataByAliasID' . $aliasID, $response);
        }
        return $response;
    }

    /**
     * This method handels retrival of front latest newsCategory list
     * @return  Object
     * @since   2017-10-13
     * @author  NetQuick
     */
    public static function getFrontList() {
        $response = false;
        $newsCategoryFields = ['id', 'varTitle'];
        $response = Cache::tags(['NewsCategory'])->get('getFrontServiceCatList');
        if (empty($response)) {
            $response = Self::getFrontRecords($newsCategoryFields)
                    ->deleted()
                    ->publish()
                    ->get()
                    ->pluck('varTitle','varSector', 'id');
            Cache::tags(['NewsCategory'])->forever('getFrontServiceCatList', $response);
        }
        return $response;
    }

    /**
     * This method handels retrival of newsCategory records
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public static function getFrontRecords($moduleFields = false, $aliasFields = false) {
        $response = false;
        $data = [];
        $response = self::select($moduleFields);
        if ($aliasFields != false) {
            $data = [
                'alias' => function ($query) use ($aliasFields) {
                    $query->select($aliasFields);
                },
            ];
        }
        if (count($data) > 0) {
            $response = $response->with($data);
        }
        return $response;
    }

    /**
     * This method handels newsCategory sub-category relation
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public function news_category() {
        $response = false;
        $response = $this->hasOne('App\NewsCategory', 'id', 'intParentCategoryId');
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
        $response = $this->belongsTo('App\NewsCategory', 'intParentCategoryId', 'id');
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
    public static function getRecordsForMenu($moduleCode = false) {
        $response = false;
        $moduleFields = [
            'id',
            'varTitle','varSector',
            'intAliasId',
            'chrLetest',
            'intParentCategoryId',
            'intDisplayOrder',
            'varMetaTitle',
            'varMetaDescription',
            'chrPublish',
            'chrDelete',
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
        $ignoreIds = [];
        $response = Self::getPowerPanelRecords($moduleFields, null, $aliasFields, $moduleCode)
                ->dateRange()
                ->where('chrMain', 'Y')
                ->where('chrIsPreview', 'N')
                ->deleted()
                ->publish()
                ->whereNotIn('id', $ignoreIds)
                ->get();
        return $response;
    }

    /**
     * This method handels backend records
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public static function getPowerPanelRecords($moduleFields = false, $parentCategoryFields = false, $aliasFields = false, $moduleCode = false) {
        $data = [];
        $response = false;

        $data['child'] = function ($query) {
            $query->select(['id','varTitle','fkMainRecord'])
                    ->where('chrDelete','N')
                    ->where('dtApprovedDateTime','!=',NULL);
        };

        $response = self::select($moduleFields);
        if ($aliasFields != false) {
            $data['alias'] = function ($query) use ($aliasFields, $moduleCode) {
                $query->select($aliasFields)->checkModuleCode($moduleCode);
            };
        }
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
		return $this->hasMany('Powerpanel\NewsCategory\Models\NewsCategory', 'fkMainRecord', 'id');
	}

    /**
     * This method handels retrival of backend record list
     * @return  Object
     * @since   2017-10-24
     * @author  NetQuick
     */
    public static function getRecordList($filterArr = false) {
        $response = false;
        $moduleFields = ['id', 'varTitle','varSector', 'intAliasId', 'intParentCategoryId', 'intDisplayOrder', 'chrPublish', 'chrPageActive', 'varPassword', 'chrDraft', 'LockUserID', 'chrLock', 'intSearchRank', 'chrTrash', 'FavoriteID', 'created_at', 'updated_at'];
        $aliasFields = ['id', 'varAlias'];
        $response = Self::getPowerPanelRecords($moduleFields, false, $aliasFields)
                ->deleted()
                ->filter($filterArr)
                ->checkMainRecord('Y')
                ->where('chrIsPreview', 'N')
                ->where('chrTrash', '!=', 'Y')
                ->get();
        return $response;
    }







    public static function getRecordListforNewsCategoryGrid($filterArr = false, $isAdmin = false, $ignoreId = array(), $userRoleSector) {
        $userid = auth()->user()->id;
        $response = false;
        $moduleFields = [
            'id',
            'varTitle','varSector',
            'intParentCategoryId',
            'intDisplayOrder',
            'chrAddStar',
            'chrPublish',
            'dtDateTime',
            'dtEndDateTime',
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
        $response = self::select($moduleFields);
        $response = $response->deleted()
                ->filter($filterArr)
                ->where(function ($query) use ($userid) {
                    $query->where("UserID", '=', $userid)->where('chrPageActive', '=', 'PR')
                    ->orWhere('chrPageActive', '!=', 'PR');
                })
                ->checkMainRecord('Y')
                ->where('chrIsPreview', 'N')
                ->where('chrTrash', '!=', 'Y')
                ->whereNotIn('id', $ignoreId);
                if(!$isAdmin){
                    $response = $response->where('varSector', $userRoleSector);
                };
        $response = $response->get();
        return $response;
    }

    public static function getDraftRecordListforNewsCategoryGrid($filterArr = false, $isAdmin = false, $ignoreId = array(), $userRoleSector) {
        $userid = auth()->user()->id;
        $response = false;
        $moduleFields = [
            'id',
            'varTitle','varSector', 
            'intParentCategoryId',
            'intDisplayOrder',
            'chrAddStar',
            'chrPublish',
            'dtDateTime',
            'dtEndDateTime',
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
        $response = self::select($moduleFields);
        $response = $response->deleted()
                ->dataFilter($filterArr)
                ->where(function ($query) use ($userid) {
                    $query->where("UserID", '=', $userid)->where('chrPageActive', '=', 'PR')
                    ->orWhere('chrPageActive', '!=', 'PR');
                })
                ->checkMainRecord('Y')
                ->where('chrIsPreview', 'N')
                ->where('chrDraft', 'D')
                ->where('chrTrash', '!=', 'Y')
                ->whereNotIn('id', $ignoreId);
                if(!$isAdmin){
                    $response = $response->where('varSector', $userRoleSector);
                };
        $response = $response->get();
        return $response;
    }

    public static function getFavoriteRecordListforNewsCategoryGrid($filterArr = false, $isAdmin = false, $ignoreId = array(), $userRoleSector) {
        $userid = auth()->user()->id;
        $response = false;
        $moduleFields = [
            'id',
            'varTitle','varSector',
            'intParentCategoryId',
            'intDisplayOrder',
            'chrAddStar',
            'chrPublish',
            'dtDateTime',
            'dtEndDateTime',
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
        $response = self::select($moduleFields);
        $response = $response->deleted()
                ->dataFilter($filterArr)
                ->where(function ($query) use ($userid) {
                    $query->where("UserID", '=', $userid)->where('chrPageActive', '=', 'PR')
                    ->orWhere('chrPageActive', '!=', 'PR');
                })
                ->checkMainRecord('Y')
                ->where('chrTrash', '!=', 'Y')
                ->whereRaw("find_in_set($userid,FavoriteID)")
                ->whereNotIn('id', $ignoreId);
                if(!$isAdmin){
                    $response = $response->where('varSector', $userRoleSector);
                };
        $response = $response->get();
        return $response;
    }

    public static function getTrashRecordListforNewsCategoryGrid($filterArr = false, $isAdmin = false, $ignoreId = array(), $userRoleSector) {
        $userid = auth()->user()->id;
        $response = false;
        $moduleFields = [
            'id',
            'varTitle','varSector',
            'intParentCategoryId',
            'intDisplayOrder',
            'chrAddStar',
            'chrPublish',
            'dtDateTime',
            'dtEndDateTime',
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
        $response = self::select($moduleFields);
        $response = $response->deleted()
                ->dataFilter($filterArr)
                ->where(function ($query) use ($userid) {
                    $query->where("UserID", '=', $userid)->where('chrPageActive', '=', 'PR')
                    ->orWhere('chrPageActive', '!=', 'PR');
                })
                ->checkMainRecord('Y')
                ->where('chrIsPreview', 'N')
                ->where('chrTrash', 'Y')
                ->whereNotIn('id', $ignoreId);
                if(!$isAdmin){
                    $response = $response->where('varSector', $userRoleSector);
                };
        $response = $response->get();
        return $response;
    }

    public static function getRecordListApprovalTab($filterArr = false, $isAdmin = false, $ignoreId = array(), $userRoleSector ) {
        $response = false;
        $MainIDs = Self::distinct()
                ->select("fkMainRecord")
                ->where('fkMainRecord', '!=', '0')
                ->where('chrIsPreview', 'N')
                ->groupBy('fkMainRecord')
                ->get()
                ->toArray();
        $MainIDs = array_column($MainIDs, 'fkMainRecord');
        $moduleFields = [
            'id',
            'varTitle','varSector',
            'intParentCategoryId',
            'intDisplayOrder',
            'chrAddStar',
            'fkMainRecord',
            'chrPublish',
            'dtDateTime',
            'dtEndDateTime',
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
        $response = Self::getPowerPanelRecords($moduleFields)
                ->deleted()
                ->filter($filterArr)
                ->whereIn('id', $MainIDs)
                ->checkStarRecord('Y')
                ->where('chrTrash', '!=', 'Y')
                ->whereNotIn('id', $ignoreId);
                if(!$isAdmin){
                    $response = $response->where('varSector', $userRoleSector);
                };
        $response = $response->get();
        return $response;
    }



    public static function getRecordCountforList($filterArr = false, $returnCounter = false, $isAdmin = false, $ignoreId = array(), $userRoleSector) {
        $response = false;
        $moduleFields = ['id'];
        $response = self::getPowerPanelRecords($moduleFields)->deleted();

        if ($filterArr != false) {
            $response = $response->filter($filterArr, $returnCounter);
        }
        $userid = auth()->user()->id;
        $response = $response
                ->where('chrMain', 'Y')
                ->where('chrIsPreview', 'N')
                ->where('chrTrash', '!=', 'Y')
                ->whereNotIn('id', $ignoreId);
                if(!$isAdmin){
                    $response = $response->where('varSector', $userRoleSector);
                }
        $response = $response->where(function ($query) use ($userid) {
                    $query->where("UserID", '=', $userid)
                    ->where('chrPageActive', '=', 'PR')
                    ->orWhere('chrPageActive', '!=', 'PR');
                })
                ->count();
        return $response;
    }

    public static function getRecordCountListApprovalTab($filterArr = false, $returnCounter = false, $isAdmin = false, $ignoreId = array(), $userRoleSector) {
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
                ->deleted();
                if ($filterArr != false) {
                    $response = $response->filter($filterArr, $returnCounter);
                }
                $response = $response->filter($filterArr)
                ->whereNotIn('id', $ignoreId)
                ->whereIn('id', $MainIDs)
                ->where('chrTrash', '!=', 'Y');
        if(!$isAdmin){
            $response = $response->where('varSector', $userRoleSector);
        }
        $response = $response->checkStarRecord('Y')
                ->count();
        return $response;
    }

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

    public static function getRecordCountforListTrash($filterArr = false, $returnCounter = false, $isAdmin = false, $ignoreId = array(), $userRoleSector) {
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
                ->where('chrTrash', 'Y');
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

    public static function getRecordCountforListFavorite($filterArr = false, $returnCounter = false, $isAdmin = false, $ignoreId = array(), $userRoleSector) {
        $response = 0;
        $cmsPageFields = ['id'];
        $userid = auth()->user()->id;
        $response = Self::getPowerPanelRecords($cmsPageFields);
        if ($filterArr != false) {
            $response = $response->dataFilter($filterArr);
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








    /**
     * This method handels retrival of backend record list
     * @return  Object
     * @since   2017-10-24
     * @author  NetQuick
     */
    public static function getRecordListforGridbyIds($ids, $filterArr = false) {
        $userid = auth()->user()->id;
        $response = false;
        $response = DB::table('news_category AS Og')
                ->leftjoin('news_category as Og1', 'Og.intParentCategoryId', '=', 'Og1.id')
                ->select('Og.id', 'Og.intAliasId', 'Og.varTitle','Og.varSector',  'Og.intParentCategoryId', 'Og.intDisplayOrder', 'Og.chrPublish', 'Og.LockUserID', 'Og.chrLock', 'Og.chrMain', 'Og.chrAddStar', 'Og.dtDateTime', 'Og.dtEndDateTime', 'Og.chrPageActive', 'Og.varPassword', 'Og.chrDraft', 'Og.intSearchRank', 'Og.chrTrash', 'Og.FavoriteID', 'Og.created_at', 'Og.updated_at')
                ->whereIn('Og.id', $ids)
                ->where(function ($query) use ($userid) {
                    $query->where("Og.UserID", '=', $userid)->where('Og.chrPageActive', '=', 'PR')
                    ->orWhere('Og.chrPageActive', '!=', 'PR');
                })
                ->where('Og.chrMain', 'Y')
                ->where('Og.chrIsPreview', 'N')
                ->where('Og.chrTrash', '!=', 'Y');
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
    public static function getDraftRecordListforGridbyIds($ids, $filterArr = false) {
        $userid = auth()->user()->id;
        $response = false;
        $response = DB::table('news_category AS Og')
                ->leftjoin('news_category as Og1', 'Og.intParentCategoryId', '=', 'Og1.id')
                ->select('Og.id', 'Og.intAliasId', 'Og.varTitle','Og.varSector',  'Og.intParentCategoryId', 'Og.intDisplayOrder', 'Og.chrPublish', 'Og.LockUserID', 'Og.chrLock', 'Og.chrMain', 'Og.chrAddStar', 'Og.dtDateTime', 'Og.dtEndDateTime', 'Og.chrPageActive', 'Og.varPassword', 'Og.chrDraft', 'Og.intSearchRank', 'Og.chrTrash', 'Og.FavoriteID', 'Og.created_at', 'Og.updated_at')
                ->whereIn('Og.id', $ids)
                ->where(function ($query) use ($userid) {
                    $query->where("Og.UserID", '=', $userid)->where('Og.chrPageActive', '=', 'PR')
                    ->orWhere('Og.chrPageActive', '!=', 'PR');
                })
                ->where('Og.chrMain', 'Y')
                ->where('Og.chrIsPreview', 'N')
                ->where('Og.chrDraft', 'D')
                ->where('Og.chrTrash', '!=', 'Y');
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
    public static function getTrashRecordListforGridbyIds($ids, $filterArr = false) {
        $userid = auth()->user()->id;
        $response = false;
        $response = DB::table('news_category AS Og')
                ->leftjoin('news_category as Og1', 'Og.intParentCategoryId', '=', 'Og1.id')
                ->select('Og.id', 'Og.intAliasId', 'Og.varTitle','Og.varSector',  'Og.intParentCategoryId', 'Og.intDisplayOrder', 'Og.chrPublish', 'Og.LockUserID', 'Og.chrLock', 'Og.chrMain', 'Og.chrAddStar', 'Og.dtDateTime', 'Og.dtEndDateTime', 'Og.chrPageActive', 'Og.varPassword', 'Og.chrDraft', 'Og.intSearchRank', 'Og.chrTrash', 'Og.FavoriteID', 'Og.created_at', 'Og.updated_at')
                ->whereIn('Og.id', $ids)
                ->where(function ($query) use ($userid) {
                    $query->where("Og.UserID", '=', $userid)->where('Og.chrPageActive', '=', 'PR')
                    ->orWhere('Og.chrPageActive', '!=', 'PR');
                })
                ->where('Og.chrMain', 'Y')
                ->where('Og.chrIsPreview', 'N')
                ->where('Og.chrTrash', 'Y');
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
        $response = DB::table('news_category AS Og')
                ->leftjoin('news_category as Og1', 'Og.intParentCategoryId', '=', 'Og1.id')
                ->select('Og.id', 'Og.varTitle', 'Og.varSector', 'Og.intParentCategoryId', 'Og.intDisplayOrder', 'Og.chrPublish', 'Og.intAliasId', 'Og.chrPageActive', 'Og.LockUserID', 'Og.chrLock', 'Og.varPassword', 'Og.chrDraft', 'Og.intSearchRank', 'Og.chrTrash', 'Og.FavoriteID', 'Og.created_at', 'Og.updated_at')
                ->whereIn('Og.id', $ids)
                ->where('Og.chrTrash', '!=', 'Y');
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
            $tree = DB::select("select GetAncestry_NewsCategory(?) AS tree", [$recordId]);
            if (isset($tree[0]) && !empty($tree[0])) {
                $response = explode(',', $tree[0]->tree);
            }
        }
        return $response;
    }

    public static function getCategoryData($id) {
        $response = null;
        $menuFields = ['id', 'varTitle','varSector'];
        $response = Self::getRecords($menuFields)
                ->checkPageId($id)
                ->deleted()
                ->first();
        return $response['varTitle'];
    }

    function scopeCheckPageId($query, $id) {
        return $query->where('id', $id);
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
        $moduleFields = ['id', 'varTitle','varSector',  'intParentCategoryId', 'intDisplayOrder', 'chrPublish'];
        $parentCategoryFields = ['id', 'varTitle', 'intParentCategoryId'];
        $response = self::select($moduleFields);
        if ($parentCategoryFields != false) {
            $data['childnews_category'] = function ($query) use ($parentCategoryFields) {
                $query->select($parentCategoryFields);
            };
        }
        if (count($data) > 0) {
            $response = $response->with($data);
        }
        $response = $response->deleted()
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
        $categoryFields = ['varTitle', 'intAliasId'];
        $aliasFields = ['id', 'varAlias'];
        $response = Self::getPowerPanelRecords($categoryFields, false, $aliasFields)->deleted()->whereIn('id', $ids)->get();
        return $response;
    }

    public static function getOrderOfApproval($id) {
        $NewRecordsCount = Self::select('intDisplayOrder')
                ->checkRecordId($id)
                ->first();
        return $NewRecordsCount;
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
            'intAliasId',
            'fkMainRecord',
            'varTitle','varSector', 
            'intParentCategoryId',
            'intDisplayOrder',
            'chrPublish',
            'fkMainRecord',
            'intApprovedBy',
            'UserID',
            'chrMain',
            'chrAddStar',
            'chrApproved',
            'chrRollBack',
            'intSearchRank',
            'dtDateTime',
            'dtEndDateTime',
            'varMetaTitle',
            'varMetaDescription',
            'varTags',
            'chrPageActive',
            'varPassword',
            'chrDraft',
            'chrTrash',
            'FavoriteID',
            'LockUserID', 'chrLock',
            'created_at',
            'updated_at'
        ];
        $aliasFields = ['id', 'varAlias'];
        $response = Self::getPowerPanelRecords($moduleFields, false, $aliasFields);
        if (!$ignoreDeleteScope) {
            $response = $response->deleted();
        }
        $response = $response->checkRecordId($id)->first();
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
        $moduleFields = ['id', 'varTitle','varSector',  'intParentCategoryId'];
        $response = Self::select($moduleFields)
                ->checkMainRecord('Y')
                ->where('chrIsPreview', 'N')
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
        $moduleFields = ['id', 'varTitle','varSector',  'intParentCategoryId'];
        $response = Self::select($moduleFields)
                ->mainRecord('Y')
                ->where('chrPublish', 'Y')
                ->where('chrIsPreview', 'N')
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
            'intSearchRank',
            'UserID',
            'intAliasId',
            'fkMainRecord',
            'varTitle','varSector', 
            'intParentCategoryId',
            'intDisplayOrder',
            'chrPublish',
            'dtDateTime',
            'dtEndDateTime',
            'chrPageActive',
            'varPassword',
            'chrDraft',
            'varMetaDescription',
            'varMetaTitle',
            'chrTrash',
            'FavoriteID',
            'LockUserID', 'chrLock',
            'created_at',
            'updated_at'
        ];
        $aliasFields = ['id', 'varAlias'];
        $response = Self::getPowerPanelRecords($moduleFields, false, $aliasFields)->deleted()->checkRecordId($id)->first();
        return $response;
    }

    /**
     * This method handels retrival of front latest service list
     * @return  Object
     * @since   2017-10-13
     * @author  NetQuick
     */
    public static function getCategoryList($paginate = 6, $page) {
        $response = false;
        $moduleFields = ['id', 'intAliasId', 'varTitle','varSector',  'intParentCategoryId', 'intDisplayOrder', 'chrPageActive', 'varPassword', 'chrDraft', 'intSearchRank', 'chrTrash', 'FavoriteID', 'created_at', 'updated_at'];
        $aliasFields = ['id', 'varAlias'];
        $response = Cache::tags(['NewsCategory'])->get('getCategoryList_' . $page);
        if (empty($response)) {
            $response = Self::getFrontRecords($moduleFields, $aliasFields)
                    ->deleted()
                    ->publish()
                    ->where('intParentCategoryId', 0)
                    ->orderBy('intDisplayOrder', 'ASC')
                    ->checkMainRecord('Y')
                    ->paginate($paginate);
            Cache::tags(['NewsCategory'])->forever('getCategoryList_' . $page, $response);
        }
        return $response;
    }

    /**
     * This method handels retrival of front latest service list
     * @return  Object
     * @since   2017-10-13
     * @author  NetQuick
     */
    public static function getAllCategoriesFrontSidebarList() {
        $response = false;
        $moduleFields = ['id', 'intAliasId', 'varTitle', 'varSector', 'intParentCategoryId', 'intDisplayOrder', 'chrPageActive', 'varPassword', 'chrDraft', 'intSearchRank', 'chrTrash', 'FavoriteID', 'created_at', 'updated_at'];
        $aliasFields = ['id', 'varAlias'];
        $response = Cache::tags(['NewsCategory'])->get('getAllCategoriesFrontSidebarList');
        if (empty($response)) {
            $response = Self::getFrontRecords($moduleFields, $aliasFields)
                    ->deleted()
                    ->publish()
                    ->checkMainRecord('Y')
                    ->dateRange()
                    ->where('chrTrash', '!=', 'Y')
                    ->where('chrDraft', '!=', 'D')
                     ->where('chrIsPreview', 'N')
                    ->where('chrTrash', 'N')
                    ->orderBy('intDisplayOrder', 'ASC')
                    ->get();
            Cache::tags(['NewsCategory'])->forever('getAllCategoriesFrontSidebarList', $response);
        }
        return $response;
    }

    /**
     * This method handels retrival of front service detail
     * @return  Object
     * @since   2017-10-13
     * @author  NetQuick
     */
    public static function getFrontDetail($id) {
        $response = false;
        $moduleFields = [
            'id',
            'intAliasId',
            'varTitle','varSector', 
            'dtDateTime',
            'dtEndDateTime',
            'chrMain',
            'varMetaTitle',
            'varMetaDescription',
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
        $response = Cache::tags(['NewsCategory'])->get('getNewsCategoryFrontDetail_' . $id);
        if (empty($response)) {
            $response = Self::getFrontRecords($moduleFields, $aliasFields)
                    ->deleted()
                    ->dateRange()
                    ->publish()
                    ->where('fkMainRecord', 0)
                    ->where('chrMain', 'Y')
                    ->checkAliasId($id)
                    ->first();
            Cache::tags(['NewsCategory'])->forever('getNewsCategoryFrontDetail_' . $id, $response);
        }
        return $response;
    }

    /**
     * This method handels retrival of front latest service list
     * @return  Object
     * @since   2017-10-13
     * @author  NetQuick
     */
    public static function getSubCategoryList($categoryId, $paginate = 6, $page) {
        $response = false;
        $moduleFields = [
            'id',
            'intAliasId',
            'varTitle','varSector', 
            'intParentCategoryId',
            'intDisplayOrder',
            'varMetaTitle',
            'varMetaDescription',
            'chrPageActive', 'varPassword', 'chrDraft', 'intSearchRank', 'chrTrash', 'FavoriteID', 'created_at', 'updated_at'
        ];
        $aliasFields = ['id', 'varAlias'];
        $response = Cache::tags(['NewsCategory'])->get('getCategoryList_' . $page);
        if (empty($response)) {
            $response = Self::getFrontRecords($moduleFields, $aliasFields)
                    ->deleted()
                    ->publish()
                    ->where('intParentCategoryId', $categoryId)
                    ->orderBy('intDisplayOrder', 'ASC')
                    ->paginate($paginate, ['*'], 'categoryPage');
            Cache::tags(['NewsCategory'])->forever('getCategoryList_' . $page, $response);
        }
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
            'intAliasId'
        ];
        $aliasFields = ['id', 'varAlias'];
        if (!in_array($order, Self::$fetchedOrder)) {
            array_push(Self::$fetchedOrder, $order);
            Self::$fetchedOrderObj = Self::getPowerPanelRecords($moduleFields, false, $aliasFields)
                    ->deleted()
                    ->orderCheck($order)
                    ->where('chrIsPreview', 'N')
                    ->first();
        }
        $response = Self::$fetchedOrderObj;
        return $response;
    }

    public static function getCatWithParent() {
        $response = false;
        $categoryFields = ['id', 'intAliasId', 'intParentCategoryId', 'varTitle','varSector',  'intDisplayOrder', 'chrPageActive', 'varPassword', 'chrDraft', 'intSearchRank', 'chrTrash', 'FavoriteID', 'created_at', 'updated_at'];
        $parentCategoryFields = ['id', 'intAliasId', 'varTitle'];
        $aliasFields = ['id', 'varAlias'];
        $response = Self::getPowerPanelRecords($categoryFields, $parentCategoryFields, $aliasFields)
                ->deleted()
                ->publish()
                ->mainRecord('Y')
                ->where('chrIsPreview', 'N')
                ->where('chrTrash', '!=', 'Y')
                ->orderBy('intDisplayOrder', 'asc')
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
                ->deleted()
                ->mainRecord('Y')
                ->where('chrIsPreview', 'N')
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
                ->mainRecord('Y')
                ->where('chrIsPreview', 'N')
                ->count();
        return $response;
    }

    public static function UpdateDisplayOrder($order, $parentRecordId = false) {
        $updateSql = "UPDATE nq_news_category SET `intDisplayOrder` = `intDisplayOrder` + 1
				WHERE `intDisplayOrder` >= " . $order . "
				AND chrDelete='N'
				AND intDisplayOrder != 0 AND chrMain='Y' AND chrIsPreview='N'";
        if ((int) $parentRecordId > 0) {
            $updateSql .= " AND intParentCategoryId=$parentRecordId";
        } else {
            $updateSql .= " AND intParentCategoryId = 0";
        }
        $updateSql .= " ORDER BY `intDisplayOrder` ASC";
        DB::update(DB::raw($updateSql));
    }

    /**
     * This method handels retrival of record count based on category
     * @return  Object
     * @since   2018-01-09
     * @author  NetQuick
     */
    public static function getRecordCount($filterArr = false, $returnCounter = false, $isAdmin, $userRoleSector) {
        $response = false;
        $moduleFields = ['id'];
        $response = self::getPowerPanelRecords($moduleFields)->deleted();
        if ($filterArr != false) {
            $response = $response->filter($filterArr, $returnCounter);
        }
        if(!$isAdmin){
            $response = $response->where('varSector', $userRoleSector);
        }
        $response = $response->where('chrMain', 'Y')
                ->where('chrIsPreview', 'N')
                ->count();
        return $response;
    }

    public static function getNewRecordsCount($isAdmin, $userRoleSector) {
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

    public static function getRecordByOrderByParent($order = false, $parentRecordId = false) {
        $response = false;
        $moduleFields = [
            'id', 'intAliasId',
            'intDisplayOrder',
            'intParentCategoryId',
        ];
        $aliasFields = ['id', 'varAlias'];
        $response = Self::getPowerPanelRecords($moduleFields, false, $aliasFields)
                ->deleted()
                ->orderCheck($order)
                ->checkMainParentsIds($parentRecordId)
                ->mainRecord('Y')
                ->first();
        return $response;
    }

    public static function getRecordForReorderByParentId($parentRecordId = false) {
        $response = false;
        $moduleFields = [
            'id',
            'intDisplayOrder',
            'intParentCategoryId',
            'intAliasId',
        ];
        $aliasFields = ['id', 'varAlias'];
        $response = Self::getPowerPanelRecords($moduleFields, false, $aliasFields)
                ->deleted()
                ->checkMainParentsIds($parentRecordId)
                ->orderBy('intDisplayOrder')
                ->mainRecord('Y')
                ->where('chrTrash', 'N')
                ->where('chrIsPreview', 'N')
                ->get();
        return $response;
    }

    public static function getRecordsForDeleteById($id) {
        $response = false;
        $moduleFields = array('id', 'intParentCategoryId', 'intDisplayOrder');
        $response = self::getPowerPanelRecords($moduleFields)->checkRecordId($id)->first();
        return $response;
    }

    public static function updateherarachyRecords($when, $ids) {
        $response = false;
        $response = DB::statement("UPDATE nq_news_category SET intDisplayOrder = (CASE id " . $when . " ELSE intDisplayOrder END) WHERE id BETWEEN " . min($ids) . " AND " . max($ids) . " and chrDelete = 'N' and chrMain='Y'");
        return $response;
    }

    public static function updateDisplayOrderByParent($id, $newOrder, $parentRecordId) {
        $response = false;
        $updateQuery = "UPDATE nq_news_category SET intDisplayOrder=intDisplayOrder + 1 WHERE intDisplayOrder >= " . $newOrder . " and chrDelete = 'N' AND id!=$id AND chrDelete = 'N' AND intParentCategoryId=$parentRecordId";
        $response = DB::statement($updateQuery);
        return $response;
    }

    public static function getChildGrid() {
        $response = false;
        $id = $_REQUEST['id'];
        $moduleFields = [
            'id',
            'UserID',
            'varTitle','varSector',
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
        $response = Self::getPowerPanelRecords($moduleFields)
                ->deleted()
                ->where('chrMain', 'N')
                ->where('chrIsPreview', 'N')
                ->where('fkMainRecord', $id)
                ->orderBy('created_at', 'desc')
                ->get();
        return $response;
    }

    public static function getChildrollbackGrid($request) {
        $id = $request->id;
        $response = false;
        $moduleFields = [
            'id',
            'varTitle','varSector', 
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
            'updated_at',
            'created_at'
        ];
        $response = Self::getPowerPanelRecords($moduleFields)
                ->deleted()
                ->where('chrMain', 'N')
                ->where('chrIsPreview', 'N')
                ->where('chrRollBack', 'Y')
                ->where('fkMainRecord', $id)
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
        $moduleFields = [
            'varTitle','varSector', 
            'intParentCategoryId',
            'intDisplayOrder',
            'chrPublish',
            'dtDateTime',
            'dtEndDateTime',
            'intSearchRank',
            'chrPageActive',
            'varPassword',
            'chrDraft',
            'chrTrash',
            'FavoriteID',
            'varMetaTitle',
            'varMetaDescription',
            'updated_at',
            'created_at'
        ];
        $response = Self::getPowerPanelRecords($moduleFields)
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
            'intParentCategoryId' => $response['intParentCategoryId'],
            'varSector' => $response['varSector'],
            'chrPublish' => $response['chrPublish'],
            'intSearchRank' => $response->intSearchRank,
            'dtDateTime' => $response['dtDateTime'],
            'dtEndDateTime' => $response['dtEndDateTime'],
            'varMetaTitle' => $response['varMetaTitle'],
            'varMetaDescription' => $response['varMetaDescription'],
            'chrDraft' => $response['chrDraft'],
            'FavoriteID' => $response['FavoriteID'],
            'chrPageActive' => $response['chrPageActive'],
            'chrPublish' => $response['chrPublish']
        ];
        $updateMainRecord['chrAddStar'] = 'N';
        CommonModel::updateRecords($whereConditions, $updateMainRecord, false, 'Powerpanel\NewsCategory\Models\NewsCategory');
        //Update Copy Child Record To Main Record end
        $whereConditions_ApproveN = ['fkMainRecord' => $main_id];
        $updateToApproveN = [
            'chrApproved' => 'N',
            'chrLetest' => 'N',
            'intApprovedBy' => '0',
        ];
        CommonModel::updateRecords($whereConditions_ApproveN, $updateToApproveN, false, 'Powerpanel\NewsCategory\Models\NewsCategory');
        $whereConditionsApprove = ['id' => $id, 'chrMain' => 'N'];
        $updateToApprove = [
            'chrApproved' => 'Y',
            'chrRollBack' => 'Y',
            'intApprovedBy' => auth()->user()->id,
            'dtApprovedDateTime' => date('Y-m-d H:i:s')
        ];
        CommonModel::updateRecords($whereConditionsApprove, $updateToApprove, false, 'Powerpanel\NewsCategory\Models\NewsCategory');
        $msg_show = "Record successfully approved.";
        return $msg_show;
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
     * This method handels delete scope
     * @return  Object
     * @since   2016-07-20
     * @author  NetQuick
     */
    public function scopeMainRecord($query, $checkMain) {
        $response = false;
        if ($checkMain) {
            $response = $query->where('chrMain', '=', 'Y');
        } else {
            $response = $query->where('chrMain', '=', 'N');
        }
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
     * This method handels alias id scope
     * @return  Object
     * @since   2016-07-24
     * @author  NetQuick
     */
    public function scopeCheckAliasId($query, $id) {
        $response = false;
        $response = $query->where('intAliasId', $id);
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
        if (!empty($filterArr['customFilterIdentity']) && $filterArr['customFilterIdentity'] != ' ') {
            $data = $query->where('chrPageActive', $filterArr['customFilterIdentity']);
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
        if (!empty($filterArr['sectorFilter']) && $filterArr['sectorFilter'] != ' ') {
            $data = $query->where('varSector', $filterArr['sectorFilter']);
        }
        if (!empty($filterArr['customFilterIdentity']) && $filterArr['customFilterIdentity'] != ' ') {
            $data = $query->where('chrPageActive', $filterArr['customFilterIdentity']);
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

    public static function getNewsCategoryNameByNewsCategoryId($ids) {
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
public function childnews_category() {
    return $this->hasMany(static::class, 'intParentCategoryId');
}

public function alias() {
    $response = false;
    $response = $this->belongsTo('App\Alias', 'intAliasId', 'id');
    return $response;
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

public function scopeDateRange($query) {
    $response = false;
    $response = $query->whereRaw('((dtEndDateTime >= NOW() AND NOW() >= dtDateTime) OR (NOW() >= dtDateTime and dtEndDateTime is null))');
    return $response;
}

public static function getRecordByIds($ids) {
    $response = false;
    $categoryFields = ['id', 'varTitle'];
    $response = Self::getFrontRecords($categoryFields)
            ->publish()
            ->deleted()
            ->where('chrTrash', '!=', 'Y')
                ->where('chrDraft', '!=', 'D')
                ->where('chrIsPreview', 'N')
            ->checkRecordIds($ids)
            ->get();
    return $response;
}

public function scopeCheckRecordIds($query, $ids) {
    $response = false;
    $response = $query->whereIn('id', $ids);
    return $response;
}

public static function getCatData($id) {
    $response = false;
    $categoryFields = ['id', 'varTitle', 'intDisplayOrder'];
    $response = Self::getPowerPanelRecords($categoryFields)
                    ->deleted()
                    ->publish()
                    ->where('id', $id)
                    ->orderBy('intDisplayOrder', 'asc')->first();
    return $response;
}

 public static function getAllCategory() {
        $response = false;
        $moduleFields = [
            'id',
            'varTitle','varSector', 
            'intParentCategoryId',
            'intDisplayOrder',
            'chrPublish',
            'dtDateTime',
            'dtEndDateTime',
            'intSearchRank',
            'chrPageActive',
            'varPassword',
            'chrDraft',
            'chrTrash',
            'FavoriteID',
            'varMetaTitle',
            'varMetaDescription',
            'updated_at',
            'created_at'
        ];
        $response = Self::getPowerPanelRecords($moduleFields, false)
                ->deleted()
                 ->publish()
                ->checkMainRecord('Y')
                ->where('chrPublish', 'Y')
                 ->where('chrIsPreview', 'N')
                ->where('chrTrash', '!=', 'Y')
                ->where('chrDraft', '!=', 'D')
                 ->groupBy('id')
                ->get();
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
