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

namespace Powerpanel\PhotoAlbum\Models;

use App\Modules;
use Cache;
use App\CommonModel;
use DB;
use Request;
use Illuminate\Database\Eloquent\Model;

class PhotoAlbum extends Model {

    protected $table = 'photo_album';
    protected $fillable = [
        'id',
        'varTitle','varSector',
        'txtDescription',
        'varShortDescription',
        'intAliasId',
        'dtDateTime',
        'dtEndDateTime',
        'chrLetest',
        'fkIntImgId',
        'fkMainRecord',
        'intApprovedBy',
        'UserID',
        'chrMain',
        'chrAddStar',
        'chrApproved',
        'chrRollBack',
        'chrPublish',
        'chrDelete',
        'chrPageActive',
        'varPassword',
        'chrDraft',
        'chrTrash',
        'FavoriteID',
        'LockUserID', 'chrLock',
        'intSearchRank',
        'created_at',
        'updated_at',
        'varMetaTitle',
        'varMetaDescription',
        'intDisplayOrder',
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

    /**
     * This method handels retrival of record count
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getRecordCount($filterArr = false, $returnCounter = false, $isAdmin = false) {
        $response = 0;
        $moduleFields = ['id'];
        $response = Self::getPowerPanelRecords($moduleFields);
        if ($filterArr != false) {
            $response = $response->filter($filterArr, $returnCounter);
        }
        if ($isAdmin) {
            $response = $response->starRecord('N');
        }
        $response = $response->deleted()
                ->where('chrMain', 'Y')
                ->where('chrIsPreview', 'N')
                ->count();
        return $response;
    }

    public static function getRecordCountForDorder($filterArr = false, $returnCounter = false, $isAdmin = false) {
        $response = 0;
        $moduleFields = ['id'];
        $response = Self::getPowerPanelRecords($moduleFields);
        if ($filterArr != false) {
            $response = $response->filter($filterArr, $returnCounter);
        }
        if ($isAdmin) {
            $response = $response->starRecord('N');
        }
        $response = $response->deleted()
                ->where('chrMain', 'Y')
                ->where('chrTrash','N')
                ->where('chrIsPreview', 'N')
                ->count();
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
                ->where('chrIsPreview', 'N')
                ->where('chrTrash', '!=', 'Y')
                ->whereIn('id', $MainIDs)
                ->starRecord('Y')
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





    public static function getRecordList($filterArr = false, $isAdmin = false, $ignoreId = array(), $userRoleSector = false) {
        $response = false;
        $userid = auth()->user()->id;
        $moduleFields = [
            'id',
            'chrPublish',
            'fkIntImgId',
            'chrAddStar',
            'txtDescription',
            'varShortDescription',
            'varMetaTitle',
            'varMetaDescription',
            'varTitle','varSector',
            'intAliasId',
            'chrPageActive',
            'intSearchRank',
            'varPassword',
            'chrDraft',
            'chrTrash',
            'FavoriteID',
            'dtDateTime',
            'dtEndDateTime',
            'intDisplayOrder',
            'LockUserID', 'chrLock',
            'created_at',
            'updated_at',
        ];
        $aliasFields = ['id', 'varAlias'];
        $response = Self::getPowerPanelRecords($moduleFields, $aliasFields)
                ->deleted()
                ->filter($filterArr)
                ->where(function ($query) use ($userid) {
                    $query->where("UserID", '=', $userid)->where('chrPageActive', '=', 'PR')
                    ->orWhere('chrPageActive', '!=', 'PR');
                })->whereNotIn('id', $ignoreId);
                if(!$isAdmin){
                    $response = $response->where('varSector', $userRoleSector);
                };
                $response = $response->checkMainRecord('Y')
                ->where('chrIsPreview', 'N')
                ->where('chrTrash', '!=', 'Y');
        $response = $response->get();
        return $response;
    }

    public static function getRecordListDraft($filterArr = false, $isAdmin = false, $ignoreId = array(), $userRoleSector) {
        $response = false;
        $blogsCatfileds = ['id', 'varTitle'];
        $userid = auth()->user()->id;
        $moduleFields = [
            'id',
            'chrPublish',
            'fkIntImgId',
            'chrAddStar',
            'txtDescription',
            'varShortDescription',
            'varMetaTitle',
            'varMetaDescription',
            'varTitle','varSector',
            'intAliasId',
            'chrPageActive',
            'intSearchRank',
            'varPassword',
            'chrDraft',
            'chrTrash',
            'FavoriteID',
            'dtDateTime',
            'dtEndDateTime',
            'intDisplayOrder',
            'LockUserID', 'chrLock',
            'created_at',
            'updated_at',
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
        })
        ->whereNotIn('id', $ignoreId);
        if(!$isAdmin){
            $response = $response->where('varSector', $userRoleSector);
        }
        $response = $response->get();
        return $response;
    }

    public static function getRecordListTrash($filterArr = false, $isAdmin = false, $ignoreId = array(), $userRoleSector) {
        $response = false;
        $blogsCatfileds = ['id', 'varTitle'];
        $userid = auth()->user()->id;
        $moduleFields = [
            'id',
            'chrPublish',
            'fkIntImgId',
            'chrAddStar',
            'txtDescription',
            'varShortDescription',
            'varMetaTitle',
            'varMetaDescription',
            'varTitle','varSector',
            'intAliasId',
            'chrPageActive',
            'intSearchRank',
            'varPassword',
            'chrDraft',
            'chrTrash',
            'FavoriteID',
            'dtDateTime',
            'dtEndDateTime',
            'intDisplayOrder',
            'LockUserID', 'chrLock',
            'created_at',
            'updated_at',
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
        })->whereNotIn('id', $ignoreId);
        if(!$isAdmin){
            $response = $response->where('varSector', $userRoleSector);
        }
        $response = $response->get();
        return $response;
    }

    public static function getRecordListFavorite($filterArr = false, $isAdmin = false, $ignoreId = array(), $userRoleSector) {
        $response = false;
        $blogsCatfileds = ['id', 'varTitle'];
        $userid = auth()->user()->id;
        $moduleFields = [
            'id',
            'chrPublish',
            'fkIntImgId',
            'chrAddStar',
            'txtDescription',
            'varShortDescription',
            'varMetaTitle',
            'varMetaDescription',
            'varTitle','varSector',
            'intAliasId',
            'chrPageActive',
            'intSearchRank',
            'varPassword',
            'chrDraft',
            'chrTrash',
            'FavoriteID',
            'dtDateTime',
            'dtEndDateTime',
            'intDisplayOrder',
            'LockUserID', 'chrLock',
            'created_at',
            'updated_at',
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
        })->whereNotIn('id', $ignoreId);
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
            'fkIntImgId',
            'chrAddStar',
            'fkMainRecord',
            'txtDescription',
            'varShortDescription',
            'varMetaTitle',
            'varMetaDescription',
            'varTitle','varSector',
            'intAliasId',
            'dtDateTime',
            'dtEndDateTime',
            'chrPageActive',
            'varPassword',
            'chrDraft',
            'chrTrash',
            'FavoriteID',
            'intSearchRank',
            'LockUserID', 'chrLock',
            'created_at',
            'updated_at',
            'intDisplayOrder',
        ];
        $response = Self::getPowerPanelRecords($moduleFields)
                ->deleted()
                ->filter($filterArr)
                ->whereIn('id', $MainIDs)
                ->where('chrTrash', '!=', 'Y')
                ->where('chrIsPreview', 'N')
                ->whereNotIn('id', $ignoreId);
        if ($isAdmin) {
            $response = $response->starRecord('Y');
        }
        if(!$isAdmin){
            $response = $response->where('varSector', $userRoleSector);
        }
        $response = $response->get();
        return $response;
    }


    public static function getRecordCountforList($filterArr = false, $returnCounter = false, $isAdmin = false, $ignoreId = array(), $userRoleSector) {
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
                })->whereNotIn('id', $ignoreId);
                if(!$isAdmin){
                    $response = $response->where('varSector', $userRoleSector);
                }
                $response = $response->checkMainRecord('Y')
                ->where('chrIsPreview', 'N')
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
        $response = Self::getPowerPanelRecords($moduleFields)
                ->deleted()
                ->where('chrIsPreview', 'N')
                ->where('chrTrash', '!=', 'Y');
                if ($filterArr != false) {
                    $response = $response->filter($filterArr, $returnCounter);
                }
                $response = $response->whereIn('id', $MainIDs)
                ->whereNotIn('id', $ignoreId);
                if(!$isAdmin){
                    $response = $response->where('varSector', $userRoleSector);
                }
                if ($isAdmin) {
                    $response = $response->starRecord('Y');
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
                ->whereNotIn('id', $ignoreId);
                if(!$isAdmin){
                    $response = $response->where('varSector', $userRoleSector);
                }
                $response = $response->where('chrTrash', '!=', 'Y')
                ->whereRaw("find_in_set($userid,FavoriteID)")
                ->where(function ($query) use ($userid) {
                    $query->where("UserID", '=', $userid)->where('chrPageActive', '=', 'PR')
                    ->orWhere('chrPageActive', '!=', 'PR');
                })
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
        $response = $response->deleted()
                ->checkMainRecord('Y')
                ->where('chrIsPreview', 'N')
                ->whereNotIn('id', $ignoreId)
                ->where('chrDraft', 'D')
                ->where('chrTrash', '!=', 'Y')
                ->where(function ($query) use ($userid) {
                    $query->where("UserID", '=', $userid)->where('chrPageActive', '=', 'PR')
                    ->orWhere('chrPageActive', '!=', 'PR');
                })->whereNotIn('id', $ignoreId);
                if(!$isAdmin){
                    $response = $response->where('varSector', $userRoleSector);
                }
                $response = $response->count();
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
                })->whereNotIn('id', $ignoreId);
                if(!$isAdmin){
                    $response = $response->where('varSector', $userRoleSector);
                }
                $response = $response->count();
        return $response;
    }






    public static function getPhotoAlbumDropdwonList($ignoreIds = false) {
        $response = false;
        $moduleFields = [
            'id',
            'varTitle','varSector',
            'chrPageActive',
            'UserID',
            'intAliasId',
            'chrDraft'
        ];
        $aliasFields = ['id', 'varAlias'];
        $response = Self::getPowerPanelRecords($moduleFields, $aliasFields)
                ->deleted()
                ->publish()
                ->where('chrMain', 'Y')
                ->where('chrIsPreview', 'N')
                ->where('chrDraft', '!=', 'D')
                ->where('chrTrash', '!=', 'Y')
                ->orderBy('intDisplayOrder');
        if ($ignoreIds) {
            $response = $response->whereNotIn('id', $ignoreIds);
        }
        $response = $response->get();
        return $response;
    }

    public static function getPhotoAlbumDropdwonFilterList($ignoreIds = false) {
        $response = false;
        $moduleFields = [
            'id',
            'varTitle','varSector',
            'intAliasId'
        ];
        $aliasFields = ['id', 'varAlias'];
        $response = Self::getPowerPanelRecords($moduleFields, $aliasFields)
                ->deleted()
                ->publish()
                ->where('chrMain', 'Y')
                ->where('chrIsPreview', 'N')
                ->where('chrDraft', '!=', 'D')
                ->where('chrTrash', '!=', 'Y')
                ->orderBy('intDisplayOrder');
        if ($ignoreIds) {
            $response = $response->whereNotIn('id', $ignoreIds);
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
    public static function getRecordById($id = false, $ignoreDeleteScope = false) {
        $response = false;
        $moduleFields = [
            'id',
            'UserID',
            'varTitle','varSector',
            'intAliasId',
            'fkIntImgId',
            'dtDateTime',
            'dtEndDateTime',
            'fkMainRecord',
            'txtDescription',
            'varShortDescription',
            'varMetaTitle',
            'chrPageActive',
            'varPassword',
            'chrDraft',
            'chrTrash',
            'FavoriteID',
            'LockUserID', 'chrLock',
            'varMetaDescription',
            'varTags',
            'intSearchRank',
            'chrPublish',
            'intDisplayOrder',
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
     * This method handels retrival of front Product list
     * @return  Object
     * @since   2017-10-14
     * @author  NetQuick
     */
    public static function getFrontList() {
        $response = false;
        $moduleFields = [
            'id',
            'varTitle','varSector',
            'intAliasId',
            'fkIntImgId',
            'txtDescription',
            'varShortDescription',
            'varMetaTitle',
            'chrPageActive',
            'varPassword',
            'chrDraft',
            'chrTrash',
            'intSearchRank',
            'FavoriteID',
            'varMetaDescription',
        ];
        $aliasFields = ['id', 'varAlias'];
        $imageFields = ['id'];
        $photoGalleryFields = ['varTitle','varSector', 'fkIntImgId', 'intPhotoAlbumId'];
        $response = Self::getFrontRecords($moduleFields, $aliasFields, $imageFields, $photoGalleryFields)
                ->deleted()
                ->publish()
                ->where('chrMain', 'Y')
                ->where('chrIsPreview', 'N')
                ->where('chrTrash', '!=', 'Y')
                ->where('chrDraft', '!=', 'D')
                ->orderBy('intDisplayOrder', 'asc')
                ->get();
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
        $response = Cache::tags(['PhotoAlbum'])->get('getFrontList');
        if (empty($response)) {
            $moduleFields = [
                'id',
                'varTitle','varSector',
                'intAliasId',
                'fkIntImgId',
                'txtDescription',
                'varShortDescription',
                'varMetaTitle',
                'chrPageActive',
                'varPassword',
                'chrDraft',
                'intSearchRank',
                'chrTrash',
                'FavoriteID',
                'varMetaDescription',
            ];
            $response = Self::getFrontRecords($moduleFields)
                    ->deleted()
                    ->publish()
                    ->featured('Y')
                    ->displayOrderBy('DESC')
                    ->take($limit)
                    ->get();
            Cache::tags(['PhotoAlbum'])->forever('getFrontList', $response);
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
            'varTitle','varSector',
            'intAliasId',
            'fkIntImgId',
            'txtDescription',
            'varShortDescription',
            'varMetaTitle',
            'chrPageActive',
            'varPassword',
            'chrDraft',
            'chrTrash',
            'intSearchRank',
            'FavoriteID',
            'varMetaDescription',
        ];
        $response = Cache::tags(['PhotoAlbum'])->get('getFrontProductDetail_' . $id);
        if (empty($response)) {
            $response = Self::getFrontRecords($moduleFields)
                    ->deleted()
                    ->where('chrMain', 'Y')
                    ->where('chrIsPreview', 'N')
                    ->checkAliasId($id)
                    ->first();
            Cache::tags(['PhotoAlbum'])->forever('getFrontProductDetail_' . $id, $response);
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
            'varTitle','varSector',
            'intAliasId',
            'dtDateTime',
            'dtEndDateTime',
            'fkIntImgId',
            'txtDescription',
            'varShortDescription',
            'varMetaTitle',
            'chrPageActive',
            'varPassword',
            'chrDraft',
            'chrTrash',
            'FavoriteID',
            'intSearchRank',
            'varMetaDescription',
            'intDisplayOrder',
            'fkMainRecord',
            'chrMain',
            'created_at',
            'updated_at'
        ];
        $aliasFields = ['id', 'varAlias'];
        $response = Cache::tags(['PhotoAlbum'])->get('getFrontPhotoAlbumList_' . $page);
        if (empty($response)) {
            $response = Self::getFrontRecords($moduleFields, $aliasFields)
                    ->deleted()
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
            Cache::tags(['PhotoAlbum'])->forever('getFrontPhotoAlbumList_' . $page, $response);
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
            'varTitle','varSector',
            'intAliasId',
            'dtDateTime',
            'dtEndDateTime',
            'fkIntImgId',
            'txtDescription',
            'varShortDescription',
            'varMetaTitle',
            'chrPageActive',
            'varPassword',
            'chrDraft',
            'chrTrash',
            'FavoriteID',
            'intSearchRank',
            'varMetaDescription',
            'intDisplayOrder',
            'fkMainRecord',
            'chrMain',
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
                    ->where('chrIsPreview', 'N')
                    ->first();
        }
        $response = Self::$fetchedOrderObj;
        return $response;
    }

    public function ReorderAllrecords(){
    	
    	$tablename = app(self::Class)->getTable();
    	$records = self::orderBy('intDisplayOrder', 'asc')
    									->where("chrMain",'Y')
    									->where("chrIsPreview",'N')
											->where("chrDelete","N")
											->where("chrTrash","N")
											->orderBy('intDisplayOrder', 'asc')
											->get();

			$ids = array();
      $update_syntax = "";

      if (!empty($records)) {
          $i = 0;
          foreach ($records as $rec) {
              $i++;
              $ids[$rec->id] = $rec->id;
              $update_syntax .= " WHEN " . $rec->id . " THEN $i ";
          }
          if (!empty($ids)) {
              $when = $update_syntax;
              DB::statement("UPDATE "."nq_".$tablename." SET intDisplayOrder = (CASE id " . $when . " ELSE intDisplayOrder END) WHERE id BETWEEN " . min($ids) . " AND " . max($ids) . " and chrDelete = 'N' and chrMain='Y' and chrIsPreview='N'");
          }
      }
    }

    public static function getOrderOfApproval($id) {
        $result = Self::select('intDisplayOrder')
                ->checkRecordId($id)
                ->first();
        return $result;
    }

    #Database Configurations========================================
    /**
     * This method handels retrival of product records
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */

    public static function getFrontRecords($moduleFields = false, $aliasFields = false, $imageFields = false, $photoGalleryFields = false) {
        $response = false;
        $data = [];
        if ($aliasFields != false) {
            $data['alias'] = function ($query) use ($aliasFields) {
                $query->select($aliasFields);
            };
        }
        if ($photoGalleryFields != false) {
            $data['photoGallery'] = function ($query) use ($photoGalleryFields) {
                $query->select($photoGalleryFields)
                        ->publish()
                        ->where('chrMain', 'Y')
                        ->deleted();
            };
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
    public static function getPowerPanelRecords($moduleFields = false, $aliasFields = false, $imageFields = false) {
        $data = [];
        $response = false;

        $data['child'] = function ($query) {
            $query->select(['id','varTitle','fkMainRecord'])
                    ->where('chrDelete','N')
                    ->where('dtApprovedDateTime','!=',NULL);
        };

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
        if (count($data) > 0) {
            $response = $response->with($data);
        }
        return $response;
    }

    public function child() {
		return $this->hasMany('Powerpanel\PhotoAlbum\Models\PhotoAlbum', 'fkMainRecord', 'id');
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
            'varTitle','varSector',
            'intAliasId',
            'fkIntImgId',
            'txtDescription',
            'varShortDescription',
            'varMetaTitle',
            'chrPageActive',
            'varPassword',
            'chrDraft',
            'chrTrash',
            'FavoriteID',
            'LockUserID', 'chrLock',
            'varMetaDescription',
            'chrPublish',
            'intDisplayOrder'
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
    public static function getRecords() {
        return self::with(['image']);
    }

    public static function getChildGrid($id) {
        $response = false;
        $moduleFields = [
            'id',
            'UserID',
            'varTitle','varSector',
            'chrApproved',
            'fkMainRecord',
            'intApprovedBy',
            'created_at',
            'updated_at',
            'chrPageActive',
            'varPassword',
            'chrDraft',
            'chrTrash',
            'FavoriteID',
            'dtApprovedDateTime',
            'intSearchRank',
            'intDisplayOrder'
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
            'UserID',
            'varTitle','varSector',
            'chrApproved',
            'fkMainRecord',
            'intApprovedBy',
            'updated_at',
            'created_at',
            'chrPageActive',
            'varPassword',
            'chrDraft',
            'chrTrash',
            'FavoriteID',
            'intSearchRank',
            'intDisplayOrder'
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
            'varTitle','varSector',
            'dtDateTime',
            'dtEndDateTime',
            'fkIntImgId',
            'intSearchRank',
            'txtDescription',
            'chrPageActive',
            'varPassword',
            'chrDraft',
            'chrTrash',
            'FavoriteID',
            'varShortDescription',
            'varMetaTitle',
            'varMetaDescription',
            'intDisplayOrder',
            'chrPublish'
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
            'dtDateTime' => $response['dtDateTime'],
            'dtEndDateTime' => $response['dtEndDateTime'],
            'fkIntImgId' => $response['fkIntImgId'],
            'intSearchRank' => $response->intSearchRank,
            'txtDescription' => $response['txtDescription'],
            'varShortDescription' => $response['varShortDescription'],
            'varMetaTitle' => $response['varMetaTitle'],
            'varMetaDescription' => $response['varMetaDescription'],
            'intSearchRank' => $response['intSearchRank'],
            'dtDateTime' => $response['dtDateTime'],
            'dtEndDateTime' => $response['dtEndDateTime'],
            'chrDraft' => $response['chrDraft'],
            'intSearchRank' => $response['intSearchRank'],
            'FavoriteID' => $response['FavoriteID'],
            'chrPageActive' => $response['chrPageActive'],
            'chrPublish' => $response['chrPublish'],
            'updated_at' => date('Y-m-d H:i:s')
        ];
        $updateMainRecord['chrAddStar'] = 'N';
        CommonModel::updateRecords($whereConditions, $updateMainRecord, false, 'Powerpanel\PhotoAlbum\Models\PhotoAlbum');
        //Update Copy Child Record To Main Record end
        $whereConditions_ApproveN = ['fkMainRecord' => $main_id];
        $updateToApproveN = [
            'chrApproved' => 'N',
            'chrLetest' => 'N',
            'intApprovedBy' => '0',
            'updated_at' => date('Y-m-d H:i:s')
        ];
        CommonModel::updateRecords($whereConditions_ApproveN, $updateToApproveN, false, 'Powerpanel\PhotoAlbum\Models\PhotoAlbum');
        $whereConditionsApprove = ['id' => $id, 'chrMain' => 'N'];
        $updateToApprove = [
            'chrApproved' => 'Y',
            'chrRollBack' => 'Y',
            'intApprovedBy' => auth()->user()->id,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        CommonModel::updateRecords($whereConditionsApprove, $updateToApprove, false, 'Powerpanel\PhotoAlbum\Models\PhotoAlbum');
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
        $filter = [];
        $moduleFields = [
            'id'
        ];
        $catFileds = ['id', 'intAliasId'];
        $aliasFields = ['id', 'varAlias'];
        $response = Self::getSearchRecords($moduleFields, false, false, $moduleCode, false)
                ->deleted()
                ->where('id', $id)
                ->first();
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
        if (count($data) > 0) {
            $response = $response->with($data);
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
        return $this->belongsTo('App\Image', 'fkIntImgId', 'id');
    }

    /**
     * This method handels image relation
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public function photoGallery() {
        return $this->hasMany('Powerpanel\PhotoGallery\Models\PhotoGallery', 'intPhotoAlbumId', 'id');
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
        if (!empty($filterArr['searchFilter']) && $filterArr['searchFilter'] != ' ') {
            $data = $query->where('varTitle', 'like', "%" . $filterArr['searchFilter'] . "%");
        }
        if (!empty($filterArr['customFilterIdentity']) && $filterArr['customFilterIdentity'] != ' ') {
            $data = $query->where('chrPageActive', $filterArr['customFilterIdentity']);
        }
        if (isset($filterArr['ignore']) && !empty($filterArr['ignore'])) {
            $data = $query->whereNotIn('photo_album.id', $filterArr['ignore']);
        }
        if (!empty($filterArr['start'])) {
            $data = $query->whereRaw('DATE(dtDateTime) >= DATE("' . date('Y-m-d', strtotime(str_replace('/', '-', $filterArr['start']))) . '")');
        }
        if (!empty($filterArr['end'])) {
            $data = $query->whereRaw('DATE(dtEndDateTime) <= DATE("' . date('Y-m-d', strtotime(str_replace('/', '-', $filterArr['end']))) . '") AND dtDateTime IS NOT null');
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
            'photo_album.id',
            'photo_album.intAliasId',
            'photo_album.fkIntImgId',
            'photo_album.varTitle',
            'photo_album.varSector',
            DB::raw('IFNULL(COUNT(nq_page_hits.id), 0) AS hits'),
            'photo_album.chrPublish',
            'photo_album.chrDelete',
            'photo_album.dtDateTime',
            'photo_album.dtEndDateTime',
            'photo_album.updated_at'
        ];
        $response = Self::getPowerPanelRecords($moduleFields, false, false, false, false)
                ->filter($filterArr);
        $response = $response->leftJoin('page_hits', 'photo_album.intAliasId', '=', 'page_hits.fkIntAliasId')
                ->where('photo_album.chrPublish', 'Y')
                ->where('photo_album.chrDelete', 'N')
                ->where('photo_album.chrMain', 'Y')
                ->where('photo_album.chrTrash', '!=', 'Y')
                ->where('photo_album.chrDraft', '!=', 'D')
                ->where('photo_album.chrIsPreview', '!=', 'Y')
                ->groupBy('photo_album.id')
                ->get();
        return $response;
    }

    public static function getBuilderPhotoAlbum($fields, $recIds) {
        $response = false;
       $moduleFields = [
            'id',
        'varTitle','varSector',
        'txtDescription',
        'varShortDescription',
        'intAliasId',
        'dtDateTime',
        'dtEndDateTime',
        'chrLetest',
        'fkIntImgId',
        'fkMainRecord',
        'intApprovedBy',
        'UserID',
        'chrMain',
        'chrAddStar',
        'chrApproved',
        'chrRollBack',
        'chrPublish',
        'chrDelete',
        'chrPageActive',
        'varPassword',
        'chrDraft',
        'chrTrash',
        'FavoriteID',
        'LockUserID', 'chrLock',
        'intSearchRank',
        'created_at',
        'updated_at',
        'varMetaTitle',
        'varMetaDescription',
        'intDisplayOrder',
        ];
        array_push($moduleFields, 'fkIntImgId');
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
                    ->orderByRaw(DB::raw("FIELD(id, " . implode(',', $recIds) . " )"))
                    ->get();
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

    public static function getAllPhotoAlbum($fields, $limit, $sdate, $edate) {
        $response = false;
        $moduleFields = [ 'id',
            'intAliasId',
            'varTitle',
            'varSector',
            'dtDateTime',
            'dtEndDateTime',
            'fkIntImgId',
            'intSearchRank',
            'txtDescription',
            'chrPageActive',
            'varPassword',
            'chrDraft',
            'chrTrash',
            'FavoriteID',
            'varShortDescription',
            'varMetaTitle',
            'varMetaDescription',
            'intDisplayOrder',
            'chrPublish'];
        $aliasFields = ['id', 'varAlias'];
        if (empty($response)) {
            $response = Self::getFrontRecords($moduleFields, $aliasFields)
                    ->where('chrMain', 'Y');
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
             if(Request::segment(1) != ''){
                        $response = $response->paginate(2);
                        }else{
                         $response = $response->get();   
                        }
        }
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
