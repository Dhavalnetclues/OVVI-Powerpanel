<?php

/**
 * The Product class handels product model queries
 * ORM implemetation.
 * @package   Netquick powerpanel
 * @license   http://www.opensource.org/licenses/BSD-3-Clause
 * @version   1.00
 * @since        2016-07-14
 * @author    NetQuick
 */

namespace Powerpanel\Publications\Models;

use App\Modules;
use Powerpanel\PublicationsCategory\Models\PublicationsCategory;
use App\CommonModel;
use Cache;
use Request;
use DB;
use Illuminate\Database\Eloquent\Model;

class Publications extends Model {

    protected $table = 'publications';
    protected $fillable = [
        'id',
        'varTitle', 'varSector',
        'txtDescription',
        'varShortDescription',
        'intAliasId',
        'dtDateTime',
        'dtEndDateTime',
        'chrLetest',
        'fkIntDocId',
        'PublicationDate',
        'txtCategories',
        'fkMainRecord',
        'intApprovedBy',
        'UserID',
        'chrMain',
        'chrAddStar',
        'chrApproved',
        'chrRollBack',
        'chrPublish',
        'chrDelete',
        'intSearchRank',
        'created_at',
        'updated_at',
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
        $response = Self::Select('id')->deleted()->publish()->checkAliasId($aliasID)->first();
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

    public static function getNewRecordsCount($isAdmin=false, $userRoleSector) {
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
                ->where('chrIsPreview', 'N')
                ->whereIn('id', $MainIDs);
                if(!$isAdmin){
                    $response = $response->where('varSector', $userRoleSector);
                }
                $response = $response->starRecord('Y')
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
                ->where('chrIsPreview', 'N')
                ->where('chrLetest', 'Y')
                ->where('id', '!=', $id)
                ->where('chrApproved', 'N')
                ->count();
        return $response;
    }

    public static function getBuilderPublication($recIds) {
        $response = false;
        $moduleFields = [
            'id',
            'varTitle', 'varSector',
            'txtDescription',
            'varShortDescription',
            'intAliasId',
            'dtDateTime',
            'dtEndDateTime',
            'chrLetest',
            'fkIntDocId',
            'PublicationDate',
            'txtCategories',
            'fkMainRecord',
            'intApprovedBy',
            'UserID',
            'chrMain',
            'chrAddStar',
            'chrApproved',
            'chrRollBack',
            'chrPublish',
            'chrDelete',
            'intSearchRank',
            'created_at',
            'updated_at',
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
        $aliasFields = ['id', 'varAlias'];
        $response = Cache::tags(['Publications'])->get('getBuilderPublication_' . implode('-', $recIds));
        $query = Self::getFrontRecords($moduleFields, $aliasFields)
                ->whereIn('id', $recIds)
                ->deleted()
                ->publish();
        if (Request::segment(1) != '') {
            $response = $query->paginate(6);
        } else {
            $response = $query->get();
        }
        return $response;
    }


    public static function getRecordList($filterArr = false, $isAdmin = false, $ignoreId = array(), $userRoleSector = false) {
        $userid = auth()->user()->id;
        $response = false;
        $moduleFields = [
            'id',
            'fkMainRecord',
            'chrPublish',
            'fkIntDocId',
            'PublicationDate',
            'chrAddStar',
            'txtCategories',
            'txtDescription',
            'varShortDescription',
            'varTitle', 'varSector',
            'intAliasId',
            'dtDateTime',
            'dtEndDateTime',
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
                ->where('chrTrash', '!=', 'Y')
                ->whereNotIn('id', $ignoreId);
                if(!$isAdmin){
                    $response = $response->where('varSector', $userRoleSector);
                }
        $response = $response->get();
        return $response;
    }

    public static function getRecordListApprovalTab($filterArr = false, $isAdmin = false, $ignoreId = array(), $userRoleSector) {
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
            'chrPublish',
            'fkIntDocId',
            'PublicationDate',
            'chrAddStar',
            'fkMainRecord',
            'txtCategories',
            'varShortDescription',
            'varTitle', 'varSector',
            'txtDescription',
            'intAliasId',
            'dtDateTime',
            'dtEndDateTime',
            'created_at',
            'updated_at',
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
        $response = Self::getPowerPanelRecords($moduleFields)
                ->deleted()
                ->where('chrIsPreview', 'N')
                ->where('chrTrash', '!=', 'Y')
                ->whereNotIn('id', $ignoreId);
                if ($isAdmin) {
                    $response = $response->starRecord('Y');
                }
                if(!$isAdmin){
                    $response = $response->where('varSector', $userRoleSector);
                }
                $response = $response->filter($filterArr)
                ->whereIn('id', $MainIDs)
                ->get();
        return $response;
    }

    public static function getRecordListFavorite($filterArr = false, $isAdmin = false, $ignoreId = array(), $userRoleSector) {
        $userid = auth()->user()->id;
        $response = false;
        $moduleFields = [
            'id',
            'chrPublish',
            'fkIntDocId',
            'PublicationDate',
            'chrAddStar',
            'txtCategories',
            'txtDescription',
            'varShortDescription',
            'varTitle', 'varSector',
            'intAliasId',
            'dtDateTime',
            'dtEndDateTime',
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
        })
        ->whereNotIn('id', $ignoreId);
        if(!$isAdmin){
            $response = $response->where('varSector', $userRoleSector);
        }
        $response = $response->get();
        return $response;
    }

    public static function getRecordListDraft($filterArr = false, $isAdmin = false, $ignoreId = array(), $userRoleSector) {
        $userid = auth()->user()->id;
        $response = false;
        $moduleFields = [
            'id',
            'chrPublish',
            'fkIntDocId',
            'PublicationDate',
            'chrAddStar',
            'txtCategories',
            'txtDescription',
            'varShortDescription',
            'varTitle', 'varSector',
            'intAliasId',
            'dtDateTime',
            'dtEndDateTime',
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
        $aliasFields = ['id', 'varAlias'];
        $response = Self::getPowerPanelRecords($moduleFields, $aliasFields)
                ->deleted()
                ->filter($filterArr)
                ->checkMainRecord('Y')
                ->where('chrIsPreview', 'N')
                ->where('chrDraft', 'D')
                ->where('chrTrash', '!=', 'Y')
                ->whereNotIn('id', $ignoreId);
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

    public static function getRecordListTrash($filterArr = false, $isAdmin = false, $ignoreId = array(), $userRoleSector) {
        $userid = auth()->user()->id;
        $response = false;
        $moduleFields = [
            'id',
            'chrPublish',
            'fkIntDocId',
            'PublicationDate',
            'chrAddStar',
            'txtCategories',
            'txtDescription',
            'varShortDescription',
            'varTitle', 'varSector',
            'intAliasId',
            'dtDateTime',
            'dtEndDateTime',
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
        })
        ->whereNotIn('id', $ignoreId);
        if(!$isAdmin){
            $response = $response->where('varSector', $userRoleSector);
        }
        $response = $response->get();
        return $response;
    }


    public static function getRecordCount($filterArr = false, $returnCounter = false, $isAdmin = false, $ignoreId = array(), $userRoleSector) {
        $response = 0;
        $moduleFields = ['id'];
        $userid = auth()->user()->id;
        $response = Self::getPowerPanelRecords($moduleFields);
        if ($filterArr != false) {
            $response = $response->filter($filterArr, $returnCounter);
        }
        $response = $response->deleted()
                ->checkMainRecord('Y')
                ->whereNotIn('id', $ignoreId);
                if(!$isAdmin){
                    $response = $response->where('varSector', $userRoleSector);
                }
                $response = $response->where('chrIsPreview', 'N')
                ->where('chrTrash', '!=', 'Y')
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
        $response = Self::getPowerPanelRecords($moduleFields);
            if ($filterArr != false) {
                $response = $response->filter($filterArr, $returnCounter);
            }
            $response = $response->deleted()
            ->filter($filterArr)
            ->whereIn('id', $MainIDs)
            ->checkStarRecord('Y')
            ->where('chrTrash', '!=', 'Y')
            ->whereNotIn('id', $ignoreId);
            if(!$isAdmin){
                $response = $response->where('varSector', $userRoleSector);
            }
            $response = $response->count();
        return $response;
    }

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
            'UserID',
            'varTitle', 'varSector',
            'txtCategories',
            'txtDescription',
            'varShortDescription',
            'intAliasId',
            'fkIntDocId',
            'PublicationDate',
            'chrAddStar',
            'dtDateTime',
            'dtEndDateTime',
            'fkMainRecord',
            'txtCategories',
            'varMetaTitle',
            'varMetaDescription',
            'varTags',
            'intSearchRank',
            'chrPublish',
            'chrPageActive',
            'varPassword',
            'chrDraft',
            'intSearchRank',
            'chrTrash',
            'FavoriteID',
            'LockUserID', 'chrLock',
            'created_at',
            'updated_at'];
        $response = Self::getPowerPanelRecords($moduleFields);
        if (!$ignoreDeleteScope) {
            $response = $response->deleted();
        }
        $response = $response->checkRecordId($id)
                ->first();
        return $response;
    }

    /**
     * This method handels retrival of front Product list
     * @return  Object
     * @since   2017-10-14
     * @author  NetQuick
     */
    public static function getFrontList($page = false, $limit = false, $catid = false, $print = false, $categoryid, $name = "", $start_date_time = "", $end_date_time = "") {
        $response = false;
        $moduleFields = [
            'id',
            'varTitle', 'varSector',
            'txtDescription',
            'varShortDescription',
            'intAliasId',
            'dtDateTime',
            'fkIntDocId',
            'PublicationDate',
            'txtCategories',
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
        $response = Cache::tags(['Publications'])->get('getFrontPublicationsList_' . $page);
        if (empty($response)) {
            $response = Self::getFrontRecords($moduleFields, $aliasFields)
                    ->deleted()
                    ->where('chrMain', 'Y')
                    ->where('chrTrash', '!=', 'Y')
                    ->where('chrDraft', '!=', 'D')
                    ->where('chrIsPreview', 'N')
                    ->orderBy('dtDateTime', 'desc')
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
            if ($catid != false) {
                $response = $response->where('txtCategories', '=', $catid);
            }
            if ($print == 'print') {
                $response = $response->get();
            } else {
                $response = $response->paginate($limit);
            }
            Cache::tags(['Publications'])->forever('getFrontPublicationsList_' . $page, $response);
        }
        return $response;
    }

    /**
     * This method handels retrival of front Sponsor list
     * @return  Object
     * @since   2017-10-13
     * @author  NetQuick
     */
    public static function getFeaturedList($limit = 5) {
        $response = false;
        $response = Cache::tags(['Publications'])->get('getFrontList');
        if (empty($response)) {
            $moduleFields = [
                'id',
                'varTitle', 'varSector',
                'dtDateTime',
                'txtDescription',
                'varShortDescription',
                'intAliasId',
                'fkIntDocId',
                'PublicationDate',
                'txtCategories',
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
            $response = Self::getFrontRecords($moduleFields)
                    ->deleted()
                    ->publish()
                    ->featured('Y')
                    ->displayOrderBy('DESC')
                    ->take($limit)
                    ->get();
            Cache::tags(['Publications'])->forever('getFrontList', $response);
        }
        return $response;
    }

    /**
     * This method handels retrival of front Product detail
     * @return  Object
     * @since   2017-10-13
     * @author  NetQuick
     */
    public static function getFrontDetail($id) {
        $response = false;
        $moduleFields = [
            'id',
            'varTitle', 'varSector',
            'intAliasId',
            'txtDescription',
            'varShortDescription',
            'fkIntDocId',
            'PublicationDate',
            'dtDateTime',
            'dtDateTime',
            'txtCategories',
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
        $response = Cache::tags(['Publications'])->get('getFrontProductDetail_' . $id);
        if (empty($response)) {
            $response = Self::getFrontRecords($moduleFields)
                    ->deleted()
                    ->where('chrMain', 'Y')
                    ->where('chrIsPreview', 'N')
                    ->dateRange()
                    ->checkAliasId($id)
                    ->first();
            Cache::tags(['Publications'])->forever('getFrontProductDetail_' . $id, $response);
        }
        return $response;
    }

    /**
     * This method handels retrival of front service list
     * @return  Object
     * @since   2017-10-14
     * @author  NetQuick
     */
    public static function getListByCategory($categoryId, $paginate = 6, $page = false, $monthid = false, $yearid = false, $print = false) {
        $response = false;
        $moduleFields = [
            'id',
            'varTitle', 'varSector',
            'txtDescription',
            'varShortDescription',
            'intAliasId',
            'dtDateTime',
            'dtEndDateTime',
            'fkIntDocId',
            'PublicationDate',
            'txtCategories',
            'varMetaTitle',
            'varMetaDescription',
            'intDisplayOrder',
            'fkMainRecord',
            'chrMain',
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
        $response = Cache::tags(['Publications'])->get('getFrontPublicationsList_' . $page);
        if (empty($response)) {
            $response = Self::getFrontRecords($moduleFields, $aliasFields)
                    ->deleted()
                    ->checkCategoryId($categoryId)
                    ->dateRange()
                    ->publish()
                    ->where('chrMain', 'Y')
                    ->where('chrIsPreview', 'N')
                    ->orderBy('dtDateTime', 'desc');
            if ($monthid != '') {
                $response = $response->whereRaw(DB::raw('month(dtDateTime)=' . $monthid));
            }
            if ($yearid != '') {
                $response = $response->whereRaw(DB::raw('year(dtDateTime)=' . $yearid));
            }
            if ($print == 'print') {
                $response = $response->get();
            } else {
                $response = $response->paginate($paginate);
            }
            Cache::tags(['Publications'])->forever('getFrontPublicationsList_' . $page, $response);
        }
        return $response;
    }

    /**
     * This method handels retrival of front service list
     * @return  Object
     * @since   2017-10-14
     * @author  NetQuick
     */
    public static function getFrontListForHomepage($limit = 5) {
        $response = false;
        $moduleFields = [
            'id',
            'varTitle', 
            'varSector',
            'intAliasId',
            'txtDescription',
            'varShortDescription',
            'dtDateTime',
            'dtEndDateTime',
            'fkIntDocId',
            'PublicationDate',
            'txtCategories',
            'varMetaTitle',
            'varMetaDescription',
            'intDisplayOrder',
            'fkMainRecord',
            'chrMain',
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
                ->dateRange()
                ->publish()
                ->where('chrMain', 'Y')
                ->where('chrIsPreview', 'N')
                ->orderBy('dtDateTime', 'desc')
                ->take($limit)
                ->get();
        return $response;
    }

    /**
     * This method handels retrival of front service list
     * @return  Object
     * @since   2017-10-14
     * @author  NetQuick
     */
    public static function getFrontRecordCountForHomepage() {
        $response = false;
        $moduleFields = [
            'id'
        ];
        $response = Self::getFrontRecords($moduleFields)
                ->deleted()
                ->dateRange()
                ->publish()
                ->where('chrMain', 'Y')
                ->where('chrIsPreview', 'N')
                ->orderBy('dtDateTime', 'desc')
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
                    ->mainRecord(true)
                    ->first();
        }
        $response = Self::$fetchedOrderObj;
        return $response;
    }

    #Database Configurations========================================
    /**
     * This method handels retrival of product records
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */

    public static function getFrontRecords($moduleFields = false, $aliasFields = false) {
        $response = false;
        $data = array();
        if ($aliasFields != false) {
            $data = [
                'alias' => function ($query) use ($aliasFields) {
                    $query->select($aliasFields);
                },
            ];
        }
        $response = self::select($moduleFields)->with($data);
        return $response;
    }

    /**
     * This method handels retrival of product records
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public static function getPowerPanelRecords($moduleFields = false, $aliasFields = false, $videoFields = false, $categoryFields = false) {
        $data = [];
        $response = false;
        $response = self::select($moduleFields);

        $data['child'] = function ($query) {
            $query->select(['id','varTitle','fkMainRecord'])
                    ->where('chrDelete', 'N')
                    ->where('dtApprovedDateTime','!=',NULL);
        };

        if ($categoryFields != false) {
            $data['productCategory'] = function ($query) use ($categoryFields) {
                $query->select($categoryFields);
            };
        }
        if (count($data) > 0) {
            $response = $response->with($data);
        }
        return $response;
    }

    public function child() {
		return $this->hasMany('Powerpanel\Publications\Models\Publications', 'fkMainRecord', 'id');
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
            'UserID',
            'intSearchRank',
            'fkMainRecord',
            'dtDateTime',
            'dtEndDateTime',
            'PublicationDate',
            'varTitle', 
            'varSector',
            'txtDescription',
            'varShortDescription',
            'intAliasId',
            'txtCategories',
            'varMetaTitle',
            'fkIntDocId',
            'varMetaDescription',
            'chrPublish',
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
     * This method handels retrival of product records
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public static function getChildGrid($id) {
        $response = false;
        $moduleFields = [
            'id',
            'varTitle', 'varSector',
            'UserID',
            'txtCategories',
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
            'dtApprovedDateTime',
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
            'varTitle', 'varSector',
            'UserID',
            'txtCategories',
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
            'dtApprovedDateTime',
            'updated_at'
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
            'id',
            'varTitle', 'varSector',
            'txtDescription',
            'varShortDescription',
            'dtDateTime',
            'dtEndDateTime',
            'fkIntDocId',
            'PublicationDate',
            'intSearchRank',
            'txtCategories',
            'varMetaTitle',
            'varMetaDescription',
            'chrPageActive',
            'varPassword',
            'chrPublish',
            'chrDelete',
            'chrDraft',
            'intSearchRank',
            'chrTrash',
            'FavoriteID',
            'created_at',
            'updated_at'
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
            'varSector' => $response['varSector'],
            'txtDescription' => $response['txtDescription'],
            'dtDateTime' => $response['dtDateTime'],
            'dtEndDateTime' => $response['dtEndDateTime'],
            'fkIntDocId' => $response['fkIntDocId'],
            'PublicationDate' => $response['PublicationDate'],
            'txtCategories' => $response['txtCategories'],
            'varMetaTitle' => $response['varMetaTitle'],
            'varMetaDescription' => $response['varMetaDescription'],
            'chrDraft' => $response['chrDraft'],
            'intSearchRank' => $response['intSearchRank'],
            'FavoriteID' => $response['FavoriteID'],
            'chrPageActive' => $response['chrPageActive'],
            'chrPublish' => $response['chrPublish'],
            'updated_at' => date('Y-m-d H:i:s')
        ];
        $updateMainRecord['chrAddStar'] = 'N';
        CommonModel::updateRecords($whereConditions, $updateMainRecord, false, 'Powerpanel\Publications\Models\Publications');
        //Update Copy Child Record To Main Record end
        $whereConditions_ApproveN = ['fkMainRecord' => $main_id];
        $updateToApproveN = [
            'chrApproved' => 'N',
            'chrLetest' => 'N',
            'intApprovedBy' => '0',
            'updated_at' => date('Y-m-d H:i:s')
        ];
        CommonModel::updateRecords($whereConditions_ApproveN, $updateToApproveN, false, 'Powerpanel\Publications\Models\Publications');
        $whereConditionsApprove = ['id' => $id, 'chrMain' => 'N'];
        $updateToApprove = [
            'chrApproved' => 'Y',
            'chrRollBack' => 'Y',
            'intApprovedBy' => auth()->user()->id,
            'updated_at' => date('Y-m-d H:i:s'),
            'dtApprovedDateTime' => date('Y-m-d H:i:s')
        ];
        CommonModel::updateRecords($whereConditionsApprove, $updateToApprove, false, 'Powerpanel\Publications\Models\Publications');
        $msg_show = "Record successfully approved.";
        return $msg_show;
    }

    /**
     * This method handels retrival of backend record list
     * @return  Object
     * @since   2017-10-24
     * @author  NetQuick
     */
    public static function getFrontSearchRecordById($id, $moduleCode) {
        $categoryModuleCode = Modules::getModule('publications-category')->id;
        $filter = [];
        $moduleFields = [
            'txtCategories'
        ];
        $catFileds = ['id', 'intAliasId'];
        $aliasFields = ['id', 'varAlias'];
        $response = Self::getSearchRecords($moduleFields, false, false, $moduleCode, false)
                ->deleted()
                ->where('id', $id)
                ->first();
        if (!empty($response)) {
            $cats = $response->txtCategories;
            if (!empty($cats)) {
                $response = PublicationsCategory::getCategoryForSearch([$cats], $categoryModuleCode);
            }
        }
        return $response;
    }

    public static function getSearchRecords($moduleFields = false, $catFileds = false, $aliasFields = false, $moduleCode = false, $categoryModuleCode = false) {
        $data = [];
        $response = false;
        $response = self::select($moduleFields);
        if ($aliasFields != false) {
            $data['alias'] = function ($query) use ($aliasFields, $moduleCode) {
                $query->select($aliasFields)->checkModuleCode($moduleCode);
            };
        }
        if ($catFileds != false) {
            $data['cat'] = function ($query) use ($catFileds, $aliasFields, $categoryModuleCode) {
                $joinArr = [];
                $joinArr['alias'] = function ($query) use ($aliasFields, $categoryModuleCode) {
                    $query->select($aliasFields)->checkModuleCode($categoryModuleCode);
                };
                $query->select($catFileds)->with($joinArr);
            };
        }
        if (count($data) > 0) {
            $response = $response->with($data);
        }
        return $response;
    }

    public function cat() {
        $response = false;
        $response = $this->belongsTo('App\PublicationsCategory', 'intFKCategory', 'id');
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
        /* $response = $query->where('txtCategories', 'like', '%' . serialize((string) $id) . '%')->orWhere('txtCategories', 'like', '%' . serialize($id) . '%'); */
        return $response;
        //return $query->where('txtCategories', 'like', '%' . '"' . $id . '"' . '%');
    }

    /**
     * This method handels order scope
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public function scopeOrderCheck($query, $order) {
        return $query->where('intDisplayOrder', $order);
    }

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
     * This method handels Popular Product scope
     * @return  Object
     * @since   2016-08-30
     * @author  NetQuick
     */
    public function scopeLatest($query, $id = false) {
        return $query
                        //->whereRaw('created_at > DATE_SUB(NOW(), INTERVAL 7 DAY)')
                        //->whereRaw('created_at <= NOW()')
                        ->groupBy('id')
                        ->orderBy('created_at', 'desc');
    }

    /**
     * This method handels featured scope
     * @return  Object
     * @since   2016-08-08
     * @author  NetQuick
     */
    public function scopeFeatured($query, $flag = null) {
        $response = false;
        $response = $query->where(['varFeaturedProduct' => $flag]);
        return $response;
    }

    /**
     * This method handels orderBy scope
     * @return  Object
     * @since   2016-08-08
     * @author  NetQuick
     */
    public function scopeDisplayOrderBy($query, $orderBy) {
        $response = false;
        $response = $query->orderBy('intDisplayOrder', $orderBy);
        return $response;
    }

    /**
     * This method handels delete scope
     * @return  Object
     * @since   2016-07-20
     * @author  NetQuick
     */
    public function scopeStarRecord($query, $flag) {
        $response = false;
        $response = $query->where('chrAddStar', '=', $flag);
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
            //$data = $query->where('txtCategories', 'like', '%' . '"' . $filterArr['catFilter'] . '"' . '%');
            $data = $query->where('txtCategories', '=', $filterArr['catFilter']);
        }
        if (isset($filterArr['ignore']) && !empty($filterArr['ignore'])) {
            $data = $query->whereNotIn('publications.id', $filterArr['ignore']);
        }
        if (!empty($filterArr['searchFilter']) && $filterArr['searchFilter'] != ' ') {
            $data = $query->where('varTitle', 'like', "%" . $filterArr['searchFilter'] . "%");
        }
        if (!empty($filterArr['customFilterIdentity']) && $filterArr['customFilterIdentity'] != ' ') {
            $data = $query->where('chrPageActive', $filterArr['customFilterIdentity']);
        }
        if (!empty($filterArr['start'])) {
            $data = $query->whereRaw('DATE(dtDateTime) >= DATE("' . date('Y-m-d', strtotime(str_replace('/', '-', $filterArr['start']))) . '")');
        }
        if (!empty($filterArr['end'])) {
            $data = $query->whereRaw('DATE(dtDateTime) <= DATE("' . date('Y-m-d', strtotime(str_replace('/', '-', $filterArr['end']))) . '") AND dtDateTime IS NOT null');
        }
        if (!empty($query)) {
            $response = $query;
        }
        return $response;
    }

    public function scopeDateRange($query) {
        $response = false;
        $response = $query->whereRaw('((dtEndDateTime >= NOW() AND NOW() >= dtDateTime) OR (NOW() >= dtDateTime and dtEndDateTime is null))');
        return $response;
    }

    public static function getBuilderRecordList($filterArr = []) {
        $response = false;
        $moduleFields = [
            'publications.id',
            'publications.intAliasId',
            'publications.varTitle',
            'publications.varSector',
            'publications.txtCategories',
            DB::raw('IFNULL(COUNT(nq_page_hits.id), 0) AS hits'),
            'publications.chrPublish',
            'publications.chrDelete',
            'publications.dtDateTime',
            'publications.dtEndDateTime',
            'publications.updated_at'
        ];
        $response = Self::getPowerPanelRecords($moduleFields, false, false, false, false)
                ->filter($filterArr);
        $response = $response->leftJoin('page_hits', 'publications.intAliasId', '=', 'page_hits.fkIntAliasId')
                ->where('publications.chrPublish', 'Y')
                ->where('publications.chrDelete', 'N')
                ->where('publications.chrMain', 'Y')
                ->where('publications.chrTrash', '!=', 'Y')
                ->where('publications.chrDraft', '!=', 'D')
                ->where('publications.chrIsPreview', 'N')
                ->groupBy('publications.id')
                ->get();
        return $response;
    }

//    public static function getBuilderPublication($fields, $recIds) {
//        $response = false;
//         $moduleFields = [
//            'id',
//             'intAliasId',
//            'varTitle','varSector',
//            'txtDescription',
//            'varShortDescription',
//            'dtDateTime',
//            'dtEndDateTime',
//            'fkIntDocId',
//            'PublicationDate',
//            'intSearchRank',
//            'txtCategories',
//            'varMetaTitle',
//            'varMetaDescription',
//            'chrPageActive',
//            'varPassword',
//            'chrPublish',
//            'chrDelete',
//            'chrDraft',
//            'intSearchRank',
//            'chrTrash',
//            'FavoriteID',
//            'created_at',
//            'updated_at'
//        ];
//        $aliasFields = ['id', 'varAlias'];
//        if (empty($response)) {
//            $response = Self::getFrontRecords($moduleFields, $aliasFields)
//                    ->whereIn('id', $recIds)
//                    ->where('chrMain', 'Y')
//                    ->where('chrIsPreview', 'N')
//                    ->where('chrTrash', '!=', 'Y')
//                    ->where('chrDraft', '!=', 'D')
//                    ->deleted()
//                    ->publish()
//                    ->orderByRaw(DB::raw("FIELD(id, " . implode(',', $recIds) . " )"))
//                    ->get();
//        }
//        return $response;
//    }

    public static function getAllPublication($limit, $publicationscat, $dbFilter = false, $sector_slug) {

        $response = false;
        $moduleFields = [
            'id',
            'intAliasId',
            'varTitle', 'varSector',
            'txtDescription',
            'varShortDescription',
            'dtDateTime',
            'dtEndDateTime',
            'fkIntDocId',
            'PublicationDate',
            'intSearchRank',
            'txtCategories',
            'varMetaTitle',
            'varMetaDescription',
            'chrPageActive',
            'varPassword',
            'chrPublish',
            'chrDelete',
            'chrDraft',
            'intSearchRank',
            'chrTrash',
            'FavoriteID',
            'created_at',
            'updated_at'
        ];
        $aliasFields = ['id', 'varAlias'];
        if (empty($response)) {
            $response = Self::getFrontRecords($moduleFields, $aliasFields)
                    ->where('chrMain', 'Y')
                    ->where('chrIsPreview', 'N');
            
            if (empty($dbFilter['category'])) {
            
                if ($publicationscat != '') {
                    $user = DB::table('publications_category')->where('intParentCategoryId', $publicationscat)->where('chrDraft', '!=', 'D')->where('chrTrash', '!=', 'Y')->where('chrDelete', 'N')->where('chrPublish', 'Y')->get();
                    if ($user->count() > 0) {
                        $childid = array();
                        foreach ($user as $parent_data) {
                            array_push($childid, $parent_data->id);
                        }

                        $response = $response->whereIn('txtCategories', $childid);
                    } else {
                       
                        $response = $response->where('txtCategories', $publicationscat);
                    }
                }
            } else{
                if(!empty($dbFilter['category'])){
                $recordwithoutParent = DB::table('publications_category')->where('id', $dbFilter['category'])->where('intParentCategoryId', '=', '0')->get();
                if($recordwithoutParent->count() > 0){
                    $response = $response->where('txtCategories', strtolower($dbFilter['category']));
                }
                else{
                $user = DB::table('publications_category')->where('intParentCategoryId', $dbFilter['category'])->where('chrDraft', '!=', 'D')->where('chrTrash', '!=', 'Y')->where('chrDelete', 'N')->where('chrPublish', 'Y')->get();
                if ($user->count() > 0) {
                
                    $childid = array();
                    foreach ($user as $parent_data) {
                        array_push($childid, $parent_data->id);
                    }
                   
                    

                    $response = $response->whereIn('txtCategories', $childid);
                } else {
                    
                    $response = $response->where('txtCategories', strtolower($dbFilter['category']));
                }
                }
            }
            }
          
//            if ($publicationscat != '') {
//    
//                        $response = $response->where('txtCategories', $publicationscat);
//                    
//                }
            
            $response = $response->where('chrIsPreview', 'N')
                    ->deleted()
                    ->publish()
                    ->where('chrTrash', '!=', 'Y')
                    ->where('chrDraft', '!=', 'D')
                    
                    ->orderBy('PublicationDate', 'desc');
            
            if (isset($sector_slug) && !empty($sector_slug)) {
                 $response = $response->where('varSector', '=', $sector_slug);
            }
            
            // if (isset($dbFilter['year']) && !empty($dbFilter['year'])) {
            //     $years = $dbFilter['year'];
            //     $response->where(function($response) use($years) {
            //         foreach ($years as $year) {
            //             $response->whereYear('PublicationDate', '=', $year, 'or');
            //         }
            //     });
            // } else {
            //     $response->whereYear('PublicationDate', '=', date('Y'));
            // }

//            if (isset($dbFilter['category']) && !empty($dbFilter['category']) && strtolower($dbFilter['category']) != 'all') {
//                $response->where('txtCategories', '=', strtolower($dbFilter['category']));
//            }

//            if ($limit != '') {
//
//                $response = $response->limit($limit);
//            }
            if (Request::segment(1) != '') {
                $pageNumber = 1;
                if (isset($dbFilter['pageNumber']) && !empty($dbFilter['pageNumber'])) {
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


    public static function getPreviousRecordByMainId($id) {
        $response = Self::select('id', 'fkMainRecord')
                ->where('fkMainRecord', $id)
                ->where('chrMain', 'N')
                ->where('chrDelete', 'N')
                ->where('chrApproved', 'N')
                ->where('dtApprovedDateTime','!=',NULL)
                ->orderBy('dtApprovedDateTime', 'DESC')
                ->first();
        return $response;
    }

}
