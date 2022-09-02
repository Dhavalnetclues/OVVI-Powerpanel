<?php

/**
 * The BoardOfDirectors class handels bannner queries
 * ORM implemetation.
 * @package   Netquick powerpanel
 * @license   http://www.opensource.org/licenses/BSD-3-Clause
 * @version   1.1
 * @since       2017-07-20
 * @author    NetQuick
 */

namespace Powerpanel\BoardOfDirectors\Models;

use App\CommonModel;
use Cache;
use DB;
use Illuminate\Database\Eloquent\Model;
use Request;

class BoardOfDirectors extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'board_of_directors';
    protected $fillable = [
        'id',
        'fkMainRecord',
        'varTitle',
        'varTagLine',
        'varTags',
        'varDepartment',
        'fkIntImgId',
        'fkIntDocId',
        'varShortDescription',
        'txtDescription',
        'varEmail',
        'varPhoneNo',
        'varSector',
        'intSearchRank',
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
        'varMetaTitle',
        'varMetaDescription',
        'chrPageActive',
        'varPassword',
        'chrDraft',
        'chrTrash',
        'FavoriteID',
        'LockUserID', 'chrLock',
        'created_at',
        'updated_at',
    ];
    public static function getBuilderBoard($recIds)
    {
        $response = false;
        $moduleFields = [
            'id',
            'varTitle',
            'intAliasId',
            'varTagLine',
            'varDepartment',
            'fkIntImgId',
            'fkIntDocId',
            'intDisplayOrder',
            'txtDescription',
            'varEmail',
            'varPhoneNo',
            'varShortDescription',
            'chrDelete',
            'chrPublish',
            'created_at',
            'updated_at',
        ];
        $aliasFields = ['id', 'varAlias'];
        $response = Cache::tags(['BoardOfDirectors'])->get('getBuilderBoard_' . implode('-', $recIds));
        $query = Self::getFrontRecords($moduleFields, $aliasFields)
            ->whereIn('id', $recIds)
            ->deleted()
            ->publish();
//           if(Request::segment(1) != ''){
        //            $response = $query->paginate(4);
        //            }else{
        //             $response = $query->get();
        //            }
        $response = $query->get();
        return $response;
    }
    public static function getTemplateTeamList($dbFilter = false, $limit)
    {
        $response = false;
        $moduleFields = [
            'id',
            'intAliasId',
            'fkMainRecord',
            'varTitle',
            'varTagLine',
            'fkIntImgId',
            'fkIntDocId',
            'varShortDescription',
            'varDepartment',
            'txtDescription',
            'varEmail',
            'varPhoneNo',
            'varSector',
            'intSearchRank',
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
            'varMetaTitle',
            'varMetaDescription',
            'created_at',
            'updated_at',
        ];
        $aliasFields = ['id', 'varAlias'];

        $query = Self::getFrontRecords($moduleFields, $aliasFields)
            ->deleted()
            ->publish()
            ->where('chrMain', 'Y')
            ->where('chrIsPreview', 'N')
            ->where('chrTrash', '!=', 'Y')
            ->where('chrDraft', '!=', 'D')
            ->where('chrIsPreview', 'N')
            ->orderBy('intDisplayOrder', 'asc');
        if (Request::segment(1) != '') {
            $pageNumber = 1;
            if (isset($dbFilter['pageNumber']) && !empty($dbFilter['pageNumber'])) {
                $pageNumber = $dbFilter['pageNumber'];
            }
            $response = $query->paginate($limit, ['*'], 'page', $pageNumber);
        } else {
            $response = $query->get();
        }
        return $response;
    }

    /**
     * This method handels retrival of managementteams records
     * @return  Object
     * @since   2016-07-20
     * @author  NetQuick
     */
    public static function getRecords()
    {
        $response = false;
        $response = Cache::tags(['BoardOfDirectors'])->get('getCareersRecords');
        if (empty($response)) {
            $response = Self::Select(['id', 'intAliasId', 'varTitle', 'varTags', 'varShortDescription', 'varTagLine', 'varDepartment', 'varEmail', 'varPhoneNo', 'varSector', 'intSearchRank', 'txtDescription', 'fkIntImgId', 'fkIntDocId', 'chrMain', 'chrAddStar', 'intDisplayOrder', 'chrPublish', 'chrPageActive', 'varPassword', 'chrDraft', 'chrTrash', 'FavoriteID', 'created_at', 'updated_at'])
                ->deleted()
                ->publish()
                ->get();
            Cache::tags(['BoardOfDirectors'])->forever('getCareersRecords', $response);
        }
        return $response;
    }

    /**
     * This method handels retrival of front blog detail
     * @return  Object
     * @since   2017-10-13
     * @author  NetQuick
     */
    public static function getFrontDetail($aliasID)
    {
        $response = false;
        $response = Cache::tags(['BoardOfDirectors'])->get('getCareersRecordIdByAliasID_' . $aliasID);
        if (empty($response)) {
            $moduleFields = [
                'id',
                'fkMainRecord',
                'varTitle',
                'varTagLine',
                'varDepartment',
                'fkIntImgId',
                'fkIntDocId',
                'varShortDescription',
                'txtDescription',
                'varEmail',
                'varPhoneNo',
                'varSector',
                'varTags',
                'intSearchRank',
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
                'varMetaTitle',
                'varMetaDescription',
                'chrPageActive',
                'varPassword',
                'chrDraft',
                'chrTrash',
                'FavoriteID',
                'LockUserID', 'chrLock',
                'created_at',
                'updated_at',
            ];
            $response = Self::Select($moduleFields)
                ->deleted()
                ->publish()
                ->where('fkMainRecord', 0)
                ->checkAliasId($aliasID)
                ->first();
            Cache::tags(['BoardOfDirectors'])->forever('getCareersRecordIdByAliasID_' . $aliasID, $response);
        }
        return $response;
    }

    public static function getFrontList()
    {
        $response = false;
        if (empty($response)) {
            $moduleFields = [
                'id',
                'fkMainRecord',
                'varTitle',
                'intAliasId',
                'varTagLine',
                'varDepartment',
                'fkIntImgId',
                'fkIntDocId',
                'varShortDescription',
                'txtDescription',
                'varEmail',
                'varPhoneNo',
                'varSector',
                'varTags',
                'intSearchRank',
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
                'varMetaTitle',
                'varMetaDescription',
                'chrPageActive',
                'varPassword',
                'chrDraft',
                'chrTrash',
                'FavoriteID',
                'LockUserID', 'chrLock',
                'created_at',
                'updated_at',
            ];
            $aliasFields = ['id', 'varAlias'];
            $response = Self::getFrontRecords($moduleFields, $aliasFields)
                ->deleted()
                ->publish()
                ->where('chrMain', 'Y')
                ->where('chrIsPreview', 'N')
                ->where('chrTrash', '!=', 'Y')
                ->where('chrDraft', '!=', 'D')
                ->orderBy('intDisplayOrder', 'asc')
                ->get();
        }
        return $response;
    }

    public static function getFrontListPopup($filterArr = false)
    {
        $response = false;
        $moduleFields = ['id',
            'fkMainRecord',
            'varTitle',
            'intAliasId',
            'varTagLine',
            'varDepartment',
            'fkIntImgId',
            'fkIntDocId',
            'varShortDescription',
            'txtDescription',
            'varEmail',
            'varPhoneNo',
            'varSector',
            'intSearchRank',
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
            'varMetaTitle',
            'varMetaDescription',
            'chrPageActive',
            'varPassword',
            'chrDraft',
            'chrTrash',
            'FavoriteID',
            'LockUserID', 'chrLock',
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

            Cache::tags(['Blogs'])->forever('getFrontBlogsList_', $response);
        }
        return $response;
    }
    public static function getFrontRecords($moduleFields = false, $aliasFields = false)
    {
        $response = false;
        $data = [];
        if ($aliasFields != false) {
            $data['alias'] = function ($query) use ($aliasFields) {
                $query->select($aliasFields);
            };
        }
        $response = self::select($moduleFields)->with($data);
        return $response;
    }

    /**
     * This method handels backend records
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public static function getPowerPanelRecords($moduleFields = false, $aliasFields = false, $moduleCode = false)
    {
        $data = [];
        $response = false;
        $response = self::select($moduleFields);

        $data['child'] = function ($query) {
            $query->select(['id', 'varTitle', 'fkMainRecord'])
                ->where('chrDelete', 'N')
                ->where('dtApprovedDateTime', '!=', null);
        };

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

    public function child()
    {
        return $this->hasMany('Powerpanel\BoardOfDirectors\Models\BoardOfDirectors', 'fkMainRecord', 'id');
    }

    public function alias()
    {
        $response = false;
        $response = $this->belongsTo('App\Alias', 'intAliasId', 'id');
        return $response;
    }

    /**
     * This method handels retrival of backend record list
     * @return  Object
     * @since   2017-10-24
     * @author  NetQuick
     */
    public static function getRecordList($filterArr = false, $isAdmin = false, $userRoleSector = false)
    {
        $userid = auth()->user()->id;
        $response = false;
        $moduleFields = [
            'id',
            'fkMainRecord',
            'intAliasId',
            'varTitle',
            'varTagLine',
            'varDepartment',
            'fkIntImgId',
            'fkIntDocId',
            'varShortDescription',
            'txtDescription',
            'varEmail',
            'varPhoneNo',
            'varSector',
            'intSearchRank',
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
            'varMetaTitle',
            'varMetaDescription',
            'chrPageActive',
            'varPassword',
            'chrDraft',
            'chrTrash',
            'FavoriteID',
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
            });
        if (!$isAdmin) {
            $response = $response->where('varSector', $userRoleSector);
        }
        $response = $response->checkMainRecord('Y')
            ->where('chrIsPreview', 'N')

            ->where('chrTrash', '!=', 'Y');
        $response = $response->get();
        return $response;
    }

    /**
     * This method handels retrival of backend record list
     * @return  Object
     * @since   2017-10-24
     * @author  NetQuick
     */
    public static function getRecordListFavorite($filterArr = false, $isAdmin = false, $userRoleSector)
    {
        $userid = auth()->user()->id;
        $response = false;
        $moduleFields = [
            'id',
            'fkMainRecord',
            'varTitle',
            'varTagLine',
            'varDepartment',
            'fkIntImgId',
            'fkIntDocId',
            'varShortDescription',
            'txtDescription',
            'varEmail',
            'varPhoneNo',
            'varSector',

            'intSearchRank',
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
            'varMetaTitle',
            'varMetaDescription',
            'chrPageActive',
            'varPassword',
            'chrDraft',
            'chrTrash',
            'FavoriteID',
            'LockUserID', 'chrLock',
            'created_at',
            'updated_at',
        ];
        $aliasFields = ['id', 'varAlias'];
        $response = Self::getPowerPanelRecords($moduleFields, $aliasFields)
            ->deleted()
            ->filter($filterArr)
            ->checkMainRecord('Y')
            ->where('chrTrash', '!=', 'Y')
            ->where('chrIsPreview', 'N')
            ->whereRaw("find_in_set($userid,FavoriteID)")
            ->where(function ($query) use ($userid) {
                $query->where("UserID", '=', $userid)->where('chrPageActive', '=', 'PR')
                    ->orWhere('chrPageActive', '!=', 'PR');
            });
        if (!$isAdmin) {
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
    public static function getRecordListTrash($filterArr = false, $isAdmin = false, $userRoleSector)
    {
        $userid = auth()->user()->id;
        $response = false;
        $moduleFields = [
            'id',
            'fkMainRecord',
            'varTitle',
            'varTagLine',
            'varDepartment',
            'fkIntImgId',
            'fkIntDocId',
            'varShortDescription',
            'txtDescription',
            'varEmail',
            'varPhoneNo',
            'varSector',

            'intSearchRank',
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
            'varMetaTitle',
            'varMetaDescription',
            'chrPageActive',
            'varPassword',
            'chrDraft',
            'chrTrash',
            'FavoriteID',
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
            });
        if (!$isAdmin) {
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
    public static function getRecordListDraft($filterArr = false, $isAdmin = false, $userRoleSector)
    {
        $userid = auth()->user()->id;
        $response = false;
        $moduleFields = [
            'id',
            'fkMainRecord',
            'varTitle',
            'varTagLine',
            'varDepartment',
            'fkIntImgId',
            'fkIntDocId',
            'varShortDescription',
            'txtDescription',
            'varEmail',
            'varPhoneNo',
            'varSector',

            'intSearchRank',
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
            'varMetaTitle',
            'varMetaDescription',
            'chrPageActive',
            'varPassword',
            'chrDraft',
            'chrTrash',
            'FavoriteID',
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
            });
        if (!$isAdmin) {
            $response = $response->where('varSector', $userRoleSector);
        }
        $response = $response->get();
        return $response;
    }

    public static function getRecordList_tab1($filterArr = false, $isAdmin = false, $userRoleSector)
    {
        $response = false;
        $moduleFields = [
            'id',
            'fkMainRecord',
            'varTitle',
            'varTagLine',
            'varDepartment',
            'fkIntImgId',
            'fkIntDocId',
            'varShortDescription',
            'txtDescription',
            'varEmail',
            'varPhoneNo',
            'varSector',
            'intSearchRank',
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
            'varMetaTitle',
            'varMetaDescription',
            'chrPageActive',
            'varPassword',
            'chrDraft',
            'chrTrash',
            'FavoriteID',
            'LockUserID', 'chrLock',
            'created_at',
            'updated_at',
        ];
        $userid = auth()->user()->id;
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
            ->filter($filterArr)
            ->whereIn('id', $MainIDs)
            ->where('chrTrash', '!=', 'Y');
        if (!$isAdmin) {
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
    public static function getRecordById($id, $ignoreDeleteScope = false)
    {
        $response = false;
        $moduleFields = [
            'id',
            'intAliasId',
            'fkMainRecord',
            'varTitle',
            'varTagLine',
            'varDepartment',
            'fkIntImgId',
            'fkIntDocId',
            'varShortDescription',
            'txtDescription',
            'varEmail',
            'varPhoneNo',
            'varSector',
            'varTags',
            'intSearchRank',
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
            'varMetaTitle',
            'varMetaDescription',
            'chrPageActive',
            'varPassword',
            'chrDraft',
            'chrTrash',
            'FavoriteID',
            'LockUserID', 'chrLock',
            'created_at',
            'updated_at',
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
            'fkMainRecord',
            'varTitle',
            'varTagLine',
            'varDepartment',
            'fkIntImgId',
            'fkIntDocId',
            'varShortDescription',
            'txtDescription',
            'varEmail',
            'varPhoneNo',
            'varSector',
            'varTags',
            'intSearchRank',
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
            'varMetaTitle',
            'varMetaDescription',
            'chrPageActive',
            'varPassword',
            'chrDraft',
            'chrTrash',
            'FavoriteID',
            'LockUserID', 'chrLock',
            'created_at',
            'updated_at',
        ];
        $response = Self::getPowerPanelRecords($moduleFields)
            ->deleted()
            ->checkRecordId($id)
            ->first();
        return $response;
    }

    public static function getRecordCount_letest($Main_id, $id)
    {
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
    protected static $fetchedOrder = [];
    protected static $fetchedOrderObj = null;

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
                ->checkMainRecord('Y')
                ->first();
        }
        $response = Self::$fetchedOrderObj;
        return $response;
    }

    /**
     * This method handels retrival of front blog detail
     * @return  Object
     * @since   2017-10-13
     * @author  NetQuick
     */
    public static function getRecordIdByAliasID($aliasID)
    {
        $response = false;
        $response = Cache::tags(['BoardOfDirectors'])->get('getCareersRecordIdByAliasID_' . $aliasID);
        if (empty($response)) {
            $response = Self::Select(['id', 'varTitle', 'varShortDescription', 'varTagLine', 'varDepartment', 'varEmail', 'varPhoneNo', 'varSector', 'intSearchRank', 'txtDescription', 'fkIntImgId',
                'fkIntDocId', 'chrMain', 'chrAddStar', 'intDisplayOrder', 'chrPublish', 'chrPageActive', 'varPassword', 'chrDraft', 'chrTrash', 'FavoriteID', 'created_at', 'updated_at'])
                ->deleted()->publish()->checkAliasId($aliasID)->first();
            Cache::tags(['BoardOfDirectors'])->forever('getCareersRecordIdByAliasID_' . $aliasID, $response);
        }
        return $response;
    }

    public static function getOrderOfApproval($id)
    {
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
    public function scopeCheckRecordId($query, $id)
    {
        return $query->where('id', $id);
    }

    /**
     * This method handels order scope
     * @return  Object
     * @since   2016-07-20
     * @author  NetQuick
     */
    public function scopeOrderCheck($query, $order)
    {
        return $query->where('intDisplayOrder', $order);
    }

    /**
     * This method handels publish scope
     * @return  Object
     * @since   2016-07-20
     * @author  NetQuick
     */
    public function scopePublish($query)
    {
        return $query->where(['chrPublish' => 'Y']);
    }

    /**
     * This method handels delete scope
     * @return  Object
     * @since   2016-07-20
     * @author  NetQuick
     */
    public function scopeDeleted($query)
    {
        return $query->where(['chrDelete' => 'N']);
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
     * This method handels filter scope
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public function scopeFilter($query, $filterArr = false, $retunTotalRecords = false)
    {
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
        if (isset($filterArr['ignore']) && !empty($filterArr['ignore'])) {
            $data = $query->whereNotIn('board_of_directors.id', $filterArr['ignore']);
        }
        if (!empty($filterArr['customFilterIdentity']) && $filterArr['customFilterIdentity'] != ' ') {
            $data = $query->where('chrPageActive', $filterArr['customFilterIdentity']);
        }
        if (!empty($filterArr['searchFilter']) && $filterArr['searchFilter'] != ' ') {
            $data = $query->where('varTitle', 'like', "%" . $filterArr['searchFilter'] . "%");
        }
        if (!empty($query)) {
            $response = $query;
        }
        return $response;
    }

    public static function getRecordCount($filterArr = false, $returnCounter = false, $isAdmin = false, $userRoleSector)
    {
        $response = 0;
        $cmsPageFields = ['id'];
        $pageQuery = Self::getPowerPanelRecords($cmsPageFields);
        if ($filterArr != false) {
            $pageQuery = $pageQuery->filter($filterArr, $returnCounter);
        }
        $response = $pageQuery
            ->deleted()
            ->where('chrMain', 'Y')
            ->where('chrIsPreview', 'N');
        if (!$isAdmin) {
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
    public static function getRecordCountforList($filterArr = false, $returnCounter = false, $isAdmin = false, $userRoleSector)
    {
        $response = 0;
        $cmsPageFields = ['id'];
        $response = Self::getPowerPanelRecords($cmsPageFields);
        if ($filterArr != false) {
            $response = $response->filter($filterArr, $returnCounter);
        }

        if (!$isAdmin) {
            $response = $response->where('varSector', $userRoleSector);
        }
        $userid = auth()->user()->id;
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

    public static function getRecordCountListApprovalTab($filterArr = false)
    {
        $response = false;
        $userid = auth()->user()->id;
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
            ->where('chrTrash', '!=', 'Y')
            ->checkStarRecord('Y')
            ->count();
        return $response;
    }

    public static function getNewRecordsCount($isAdmin = false, $userRoleSector)
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
            ->whereIn('id', $MainIDs)
            ->where('chrTrash', '!=', 'Y');
        if (!$isAdmin) {
            $response = $response->where('varSector', $userRoleSector);
        }
        $response = $response->checkStarRecord('Y')
            ->count();
        return $response;
    }

    public static function getChildGrid()
    {
        $catfields = ['id', 'varTitle'];
        $id = $_REQUEST['id'];
        $response = false;
        $moduleFields = ['id', 'varTitle', 'fkMainRecord', 'UserID', 'intAliasId', 'varShortDescription', 'varTagLine', 'varDepartment', 'varEmail', 'varPhoneNo', 'varSector', 'intSearchRank', 'txtDescription', 'fkIntImgId',
            'fkIntDocId', 'chrApproved', 'intApprovedBy', 'chrMain', 'chrAddStar', 'intDisplayOrder', 'chrPublish', 'dtApprovedDateTime', 'chrPageActive', 'varPassword', 'chrDraft', 'chrTrash', 'FavoriteID', 'created_at', 'updated_at'];
        $response = Self::getPowerPanelRecords($moduleFields, false, $catfields)
            ->deleted()
            ->where('chrMain', 'N')
            ->where('fkMainRecord', $id)
            ->where('chrIsPreview', 'N')
            ->orderBy('created_at', 'desc')
            ->get();
        return $response;
    }

    public static function getChildrollbackGrid()
    {
        $id = $_REQUEST['id'];
        $response = false;
        $moduleFields = ['id', 'varTitle', 'varShortDescription', 'varTagLine', 'varTags', 'varDepartment', 'varEmail', 'varPhoneNo', 'varSector', 'intSearchRank', 'txtDescription', 'fkIntImgId',
            'fkIntDocId', 'chrMain', 'chrAddStar', 'intDisplayOrder', 'chrPublish', 'chrPageActive', 'varPassword', 'chrDraft', 'chrTrash', 'FavoriteID', 'created_at', 'updated_at'];
        $response = Self::getPowerPanelRecords($moduleFields, false)
            ->deleted()
            ->where('chrMain', 'N')
            ->where('chrRollBack', 'Y')
            ->where('fkMainRecord', $id)
            ->where('chrIsPreview', 'N')
            ->orderBy('created_at', 'desc')
            ->get();
        return $response;
    }

    public static function approved_data_Listing($request)
    {
        $id = $request->id;
        $main_id = $request->main_id;
        // $PUserid = $request->PUserid;
        //Select Child Record Data Start
        $response = false;
        $moduleFields = [
            'id',
            'fkMainRecord',
            'varTitle',
            'varTagLine',
            'varDepartment',
            'fkIntImgId',
            'fkIntDocId',
            'varShortDescription',
            'txtDescription',
            'varEmail',
            'varPhoneNo',
            'varSector',
            'varTags',
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
            'varMetaTitle',
            'varMetaDescription',
            'chrPageActive',
            'varPassword',
            'chrDraft',
            'chrTrash',
            'FavoriteID',
            'LockUserID', 'chrLock',
            'created_at',
            'updated_at',
        ];
        $aliasFields = ['id', 'varAlias'];
        $response = Self::getPowerPanelRecords($moduleFields, false)
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
            'varShortDescription' => $response['varShortDescription'],
            'varTagLine' => $response['varTagLine'],
            'varDepartment' => $response['varDepartment'],
            'varEmail' => $response['varEmail'],
            'varPhoneNo' => $response['varPhoneNo'],
            'varSector' => $response['varSector'],
            'intDisplayOrder' => $response['intDisplayOrder'],
            'txtDescription' => $response['txtDescription'],
            'fkIntImgId' => $response['fkIntImgId'],
            'chrAddStar' => 'N',
            'intSearchRank' => $response['intSearchRank'],
            'chrDraft' => $response['chrDraft'],
            'FavoriteID' => $response['FavoriteID'],
            'chrPageActive' => $response['chrPageActive'],
            'chrPublish' => $response['chrPublish'],
        ];
        CommonModel::updateRecords($whereConditions, $updateMainRecord, false, 'Powerpanel\BoardOfDirectors\Models\BoardOfDirectors');
        //Update Copy Child Record To Main Record end
        $whereConditions_ApproveN = ['fkMainRecord' => $main_id];
        $updateToApproveN = [
            'chrApproved' => 'N',
            'chrLetest' => 'N',
            'intApprovedBy' => '0',
        ];
        CommonModel::updateRecords($whereConditions_ApproveN, $updateToApproveN, false, 'Powerpanel\BoardOfDirectors\Models\BoardOfDirectors');
        $whereConditionsApprove = ['id' => $id, 'chrMain' => 'N'];
        $updateToApprove = [
            'chrApproved' => 'Y',
            'chrRollBack' => 'Y',
            'intApprovedBy' => auth()->user()->id,
            'dtApprovedDateTime' => date('Y-m-d H:i:s'),
        ];
        CommonModel::updateRecords($whereConditionsApprove, $updateToApprove, false, 'Powerpanel\BoardOfDirectors\Models\BoardOfDirectors');
        $msg_show = "Record successfully approved.";
        return $msg_show;
    }

    public static function getCountById($categoryId = null)
    {
        $response = false;
        $moduleFields = ['id'];
        $response = Self::getPowerPanelRecords($moduleFields)
            ->where('chrMain', 'Y')
            ->where('chrIsPreview', 'N')
            ->deleted()
            ->count();
        return $response;
    }

    /**
     * This method handels retrival of backend record list
     * @return  Object
     * @since   2017-10-24
     * @author  NetQuick
     */
    public static function getFrontSearchRecordById($id, $moduleCode)
    {
        $filter = [];
        $moduleFields = [
            'id',
            'intAliasId',
        ];
        $aliasFields = ['id', 'varAlias'];
        $response = Self::getSearchRecords($moduleFields, $aliasFields, $moduleCode)
            ->deleted()
            ->where('id', $id)
            ->first();
        return $response;
    }

    public static function getSearchRecords($moduleFields, $aliasFields, $moduleCode)
    {
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
     * This method handels alias id scope
     * @return  Object
     * @since   2016-07-24
     * @author  NetQuick
     */
    public function scopeCheckAliasId($query, $id)
    {
        return $query->where('intAliasId', $id);
    }

    /**
     * This method handels retrival of record count
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getRecordsForMenu($moduleCode = false)
    {
        $response = false;
        $moduleFields = [
            'id',
            'fkMainRecord',
            'varTitle',
            'varTagLine',
            'varDepartment',
            'fkIntImgId',
            'fkIntDocId',
            'varShortDescription',
            'txtDescription',
            'varEmail',
            'varPhoneNo',
            'varSector',
            'intSearchRank',
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
            'varMetaTitle',
            'varMetaDescription',
            'chrPageActive',
            'varPassword',
            'chrDraft',
            'chrTrash',
            'FavoriteID',
            'LockUserID', 'chrLock',
            'created_at',
            'updated_at',
        ];
        $aliasFields = ['id', 'varAlias'];
        $response = Self::getPowerPanelRecords($moduleFields, $aliasFields, $moduleCode)
            ->dateRange()
            ->where('chrMain', 'Y')
            ->where('chrIsPreview', 'N')
            ->deleted()
            ->publish()
            ->get();
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
        if (!$isAdmin) {
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
    public static function getRecordCountforListTrash($filterArr = false, $returnCounter = false, $isAdmin = false, $ignoreId = array(), $userRoleSector)
    {
        $response = 0;
        $cmsPageFields = ['id'];
        $userid = auth()->user()->id;
        $response = Self::getPowerPanelRecords($cmsPageFields);
        if ($filterArr != false) {
            $response = $response->filter($filterArr, $returnCounter);
        }
        if (!$isAdmin) {
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
    public static function getRecordCountforListFavorite($filterArr = false, $returnCounter = false, $isAdmin = false, $ignoreId = array(), $userRoleSector)
    {
        $response = 0;
        $cmsPageFields = ['id'];
        $userid = auth()->user()->id;
        $response = Self::getPowerPanelRecords($cmsPageFields);
        if ($filterArr != false) {
            $response = $response->filter($filterArr, $returnCounter);
        }
        if (!$isAdmin) {
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

    public static function getPreviousRecordByMainId($id)
    {
        $response = Self::select('id', 'fkMainRecord')
            ->where('fkMainRecord', $id)
            ->where('chrMain', 'N')
            ->where('chrApproved', 'N')
            ->where('chrDelete', 'N')
            ->where('dtApprovedDateTime', '!=', null)
            ->orderBy('dtApprovedDateTime', 'DESC')
            ->first();
        return $response;
    }

    public static function getBuilderRecordList($filterArr = [])
    {
        $response = false;
        $moduleFields = [
            'board_of_directors.id',
            'board_of_directors.intAliasId',
            'board_of_directors.varTitle',
            'board_of_directors.varShortDescription',
            'board_of_directors.varSector',
            'board_of_directors.varTagLine',
            'board_of_directors.varEmail',
            DB::raw('IFNULL(COUNT(nq_page_hits.id), 0) AS hits'),
            'board_of_directors.chrPublish',
            'board_of_directors.chrDelete',
            'board_of_directors.updated_at',
        ];
        $response = Self::getPowerPanelRecords($moduleFields, false, false, false, false)
            ->filter($filterArr);
        $response = $response->leftJoin('page_hits', 'board_of_directors.intAliasId', '=', 'page_hits.fkIntAliasId')
            ->where('board_of_directors.chrPublish', 'Y')
            ->where('board_of_directors.chrDelete', 'N')
            ->where('board_of_directors.chrMain', 'Y')
            ->where('board_of_directors.chrTrash', '!=', 'Y')
            ->where('board_of_directors.chrDraft', '!=', 'D')
            ->where('board_of_directors.chrIsPreview', 'N')
            ->groupBy('board_of_directors.id')
            ->get();
        return $response;
    }

}
