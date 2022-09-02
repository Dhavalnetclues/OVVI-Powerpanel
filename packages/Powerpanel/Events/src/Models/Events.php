<?php

/**
 * The Events class handels bannner queries
 * ORM implemetation.
 * @package   Netquick powerpanel
 * @license   http://www.opensource.org/licenses/BSD-3-Clause
 * @version   1.1
 * @since       2017-07-20
 * @author    NetQuick
 */

namespace Powerpanel\Events\Models;

use App\CommonModel;
use App\Modules;
use Cache;
use DB;
use Illuminate\Database\Eloquent\Model;

class Events extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'events';
    protected $fillable = [
        'id',
        'fkMainRecord',
        'intFKCategory',
        'varTitle', 'varSector',
        'fkIntImgId',
        'fkIntDocId',
        'txtDescription',
        'varShortDescription',
        'varLocation',
        'varAddress',
        'intSearchRank',
        'chrMain',
        'chrAddStar',
        'dtDateTime',
        'dtEndDateTime',
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
        'intSearchRank',
        'chrTrash',
        'FavoriteID',
        'LockUserID',
        'chrLock',
        'created_at',
        'updated_at',
        'varAdminPhone',
        'varAdminEmail',
    ];

    /**
     * This method handels retrival of managementteams records
     * @return  Object
     * @since   2016-07-20
     * @author  NetQuick
     */
    public static function getRecords()
    {
        $response = false;
        $response = Cache::tags(['Events'])->get('getEventsRecords');
        if (empty($response)) {
            $response = Self::Select(['id', 'intFKCategory', 'varTitle', 'varSector', 'fkIntImgId', 'fkIntDocId', 'varAdminPhone',
                'varAdminEmail', 'txtDescription', 'dtDateTime', 'dtEndDateTime', 'chrPublish', 'chrPageActive',
                'varPassword',
                'chrDraft',
                'intSearchRank',
                'chrTrash',
                'FavoriteID',
                'created_at',
                'updated_at'])
                ->deleted()
                ->publish()
                ->paginate(10);
            Cache::tags(['Events'])->forever('getEventsRecords', $response);
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
        // $response = Cache::tags(['Events'])->get('getEventsRecordIdByAliasID_' . $aliasID);
        if (empty($response)) {
            $moduleFields = [
                'id',
                'intFKCategory',
                'fkIntImgId',
                'fkIntDocId',
                'varTitle',
                'varSector',
                'txtDescription',
                'varShortDescription',
                'varLocation',
                'varAddress',
                'varMetaTitle',
                'varMetaDescription',
                'dtDateTime',
                'dtEndDateTime',
                'chrPageActive',
                'varPassword',
                'chrDraft',
                'intSearchRank',
                'chrTrash',
                'FavoriteID',
                'created_at',
                'varTags',
                'varAdminPhone',
                'varAdminEmail',
                'updated_at',
            ];
            $response = Self::Select($moduleFields)
                ->deleted()
                ->publish()
                ->where('fkMainRecord', 0)
                ->checkAliasId($aliasID)
                ->first();
            // Cache::tags(['Events'])->forever('getEventsRecordIdByAliasID_' . $aliasID, $response);
        }
        return $response;
    }

    public static function getFrontDetailByCategory($categoryId, $id)
    {
        $response = false;
        if (empty($response)) {
            $moduleFields = [
                'id',
                'intFKCategory',
                'intAliasId',
                'fkIntImgId',
                'fkIntDocId',
                'varTitle',
                'varSector',
                'txtDescription',
                'varShortDescription',
                'varLocation',
                'varAddress',
                'varMetaTitle',
                'varMetaDescription',
                'dtDateTime',
                'dtEndDateTime',
                'chrPageActive',
                'varPassword',
                'chrDraft',
                'intSearchRank',
                'chrTrash',
                'FavoriteID',
                'created_at',
                'varTags',
                'varAdminPhone',
                'varAdminEmail',
                'updated_at',
            ];
            $aliasFields = ['id', 'varAlias'];
            $response = Self::getFrontRecords($moduleFields, $aliasFields)
                ->deleted()
                ->publish()
                ->where('chrMain', 'Y')
                ->where('chrIsPreview', 'N')
                ->where('id', '<>', $id)
                ->where('intFKCategory', $categoryId)
                ->get();
        }
        return $response;
    }

    /**
     * This method handels retrival of front blog detail
     * @return  Object
     * @since   2017-10-13
     * @author  NetQuick
     */
    public static function getEventsForCalender()
    {
        $response = false;
        $moduleFields = [
            'id',
            'varTitle', 'varSector',
            'intFKCategory',
            'fkIntImgId',
            'fkIntDocId',
            'intAliasId',
            'txtDescription',
            'varShortDescription',
            'varMetaTitle',
            'varMetaDescription',
            'varAdminPhone',
            'varAdminEmail',
            'dtDateTime',
            'dtEndDateTime',
            'chrPageActive',
            'varPassword',
            'chrDraft',
            'intSearchRank',
            'chrTrash',
            'FavoriteID',
            'created_at',
            'updated_at',
        ];
        $aliasFields = ['id', 'varAlias'];
        $response = Self::getFrontRecords($moduleFields, $aliasFields)
            ->deleted()
            ->publish()
            ->where('chrMain', 'Y')
            ->where('chrIsPreview', 'N')
            ->daterange()
            ->get();
        return $response;
    }

    public static function getMonth()
    {
        $response = false;
        $response = self::select(DB::raw('month(dtDateTime) as month'))
            ->where('chrPublish', '=', 'Y')
            ->where('chrDelete', '=', 'N')
            ->where('chrMain', '=', 'Y')
            ->where('chrIsPreview', '=', 'N')
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();
        return $response;
    }

    public static function getYear()
    {
        $response = false;
        $response = self::select(DB::raw('year(dtDateTime) as year'))
            ->where('chrPublish', '=', 'Y')
            ->where('chrDelete', '=', 'N')
            ->where('chrMain', '=', 'Y')
            ->where('chrIsPreview', '=', 'N')
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->get();
        return $response;
    }

    /**
     * This method handels backend records
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public static function getPowerPanelRecords($moduleFields = false, $aliasFields = false, $eventsCatfileds = false, $moduleCode = false)
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
        if ($eventsCatfileds != false) {
            $data['eventscat'] = function ($query) use ($eventsCatfileds) {
                $query->select($eventsCatfileds)->publish();
            };
        }
        if (count($data) > 0) {
            $response = $response->with($data);
        }
        return $response;
    }

    public function child()
    {
        return $this->hasMany('Powerpanel\Events\Models\Events', 'fkMainRecord', 'id');
    }

    public function eventscat()
    {
        $response = false;
        $response = $this->belongsTo('Powerpanel\EventCategory\Models\EventCategory', 'intFKCategory', 'id');
        return $response;
    }

    public function alias()
    {
        $response = false;
        $response = $this->belongsTo('App\Alias', 'intAliasId', 'id');
        return $response;
    }



    public static function getRecordList($filterArr = false, $isAdmin = false, $ignoreId = array(), $userRoleSector = false)
    {
        $userid = auth()->user()->id;
        $response = false;
        $moduleFields = [
            'id',
            'fkMainRecord',
            'intFKCategory',
            'intAliasId',
            'varTitle', 'varSector',
            'fkIntImgId',
            'fkIntDocId',
            'txtDescription',
            'varShortDescription',
            'varAdminPhone',
            'varAdminEmail',
            'chrPublish',
            'chrMain',
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
            'updated_at',
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
            if (!$isAdmin) {
                $response = $response->where('varSector', $userRoleSector);
            }
            $response = $response->get();
        return $response;
    }

    public static function getRecordListFavorite($filterArr = false, $isAdmin = false, $ignoreId = array(), $userRoleSector)
    {
        $response = false;
        $userid = auth()->user()->id;
        $moduleFields = [
            'id',
            'intFKCategory',
            'intAliasId',
            'varTitle', 'varSector',
            'fkIntImgId',
            'fkIntDocId',
            'txtDescription',
            'varShortDescription',
            'varAdminPhone',
            'varAdminEmail',
            'chrPublish',
            'chrMain',
            'dtDateTime',
            'dtEndDateTime',
            'chrPageActive',
            'varPassword',
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
            if (!$isAdmin) {
                $response = $response->where('varSector', $userRoleSector);
            }
        $response = $response->get();
        return $response;
    }

    public static function getRecordListDraft($filterArr = false, $isAdmin = false, $ignoreId = array(), $userRoleSector)
    {
        $response = false;
        $userid = auth()->user()->id;
        $moduleFields = [
            'id',
            'intFKCategory',
            'intAliasId',
            'varTitle', 'varSector',
            'fkIntImgId',
            'fkIntDocId',
            'txtDescription',
            'varShortDescription',
            'chrPublish',
            'chrMain',
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
            'varAdminPhone',
            'varAdminEmail',
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
            if (!$isAdmin) {
                $response = $response->where('varSector', $userRoleSector);
            }
        $response = $response->get();
        return $response;
    }

    public static function getRecordListTrash($filterArr = false, $isAdmin = false, $ignoreId = array(), $userRoleSector)
    {
        $response = false;
        $userid = auth()->user()->id;
        $moduleFields = [
            'id',
            'intFKCategory',
            'intAliasId',
            'varTitle', 'varSector',
            'fkIntImgId',
            'fkIntDocId',
            'txtDescription',
            'varShortDescription',
            'chrPublish',
            'chrMain',
            'dtDateTime',
            'dtEndDateTime',
            'chrPageActive',
            'varPassword',
            'chrAddStar',
            'chrDraft',
            'intSearchRank',
            'chrTrash',
            'FavoriteID',
            'LockUserID',
            'chrLock',
            'varAdminPhone',
            'varAdminEmail',
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
            })
            ->whereNotIn('id', $ignoreId);
            if(!$isAdmin){
                $response = $response->where('varSector', $userRoleSector);
            };
        $response = $response->get();
        return $response;
    }

    public static function getRecordList_tab1($filterArr = false, $isAdmin = false, $ignoreId = array(), $userRoleSector)
    {
        $response = false;
        $moduleFields = [
            'id',
            'intAliasId',
            'intFKCategory',
            'varTitle', 'varSector',
            'fkIntImgId',
            'fkIntDocId',
            'txtDescription',
            'varShortDescription',
            'varAdminPhone',
            'varAdminEmail',
            'chrPublish',
            'fkMainRecord',
            'chrAddStar',
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
            'updated_at',
        ];
        $eventsCatfileds = ['id', 'varTitle'];
        $MainIDs = Self::distinct()
            ->select("fkMainRecord")
            ->checkMainRecord('N')
            ->where('chrIsPreview', 'N')
            ->groupBy('fkMainRecord')
            ->deleted()
            ->get()
            ->toArray();
        $MainIDs = array_column($MainIDs, 'fkMainRecord');
        $response = Self::getPowerPanelRecords($moduleFields, false, $eventsCatfileds)
            ->deleted()
            ->where('chrAddStar', 'Y')
            ->filter($filterArr)
            ->whereIn('id', $MainIDs)
            ->checkStarRecord('Y')
            ->where('chrTrash', '!=', 'Y')
            ->whereNotIn('id', $ignoreId);
            if (!$isAdmin) {
                $response = $response->where('varSector', $userRoleSector);
            }
            $response = $response->get();
        return $response;
    }





    public static function getRecordCountforList($filterArr = false, $returnCounter = false, $isAdmin = false, $ignoreId = array(), $userRoleSector)
    {
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
            ->whereNotIn('id', $ignoreId);
        if (!$isAdmin) {
            $response = $response->where('varSector', $userRoleSector);
        }
        $response = $response->count();
        return $response;
    }

    public static function getRecordCountListApprovalTab($filterArr = false, $returnCounter = false, $isAdmin = false, $ignoreId = array(), $userRoleSector)
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

        $response = Self::getPowerPanelRecords($moduleFields);
            if ($filterArr != false) {
                $response = $response->filter($filterArr, $returnCounter);
            }
            $response = $response->deleted()
            ->filter($filterArr)
            ->whereIn('id', $MainIDs)
            ->whereNotIn('id', $ignoreId)
            ->checkStarRecord('Y')
            ->where('chrTrash', '!=', 'Y');
            if (!$isAdmin) {
                $response = $response->where('varSector', $userRoleSector);
            }
            $response = $response->count();
        return $response;
    }

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

    public static function getRecordCountforListTrash($filterArr = false, $returnCounter = false, $isAdmin = false, $ignoreId = array(), $userRoleSector)
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
        if (!$isAdmin) {
            $response = $response->where('varSector', $userRoleSector);
        }
        $response = $response->count();

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
        if (!$isAdmin) {
            $response = $response->where('varSector', $userRoleSector);
        }
        $response = $response->count();

        return $response;
    }




    public static function getRecordById($id, $ignoreDeleteScope = false)
    {
        $response = false;
        $moduleFields = [
            'id',
            'intAliasId',
            'intFKCategory',
            'varTitle', 'varSector',
            'fkIntImgId',
            'fkIntDocId',
            'txtDescription',
            'varShortDescription',
            'varAdminPhone',
            'varAdminEmail',
            'varLocation',
            'varAddress',
            'chrAddStar',
            'intSearchRank',
            'dtDateTime',
            'dtEndDateTime',
            'chrPublish',
            'fkMainRecord',
            'varMetaTitle',
            'varMetaDescription',
            'varTags',
            'UserID',
            'chrPageActive',
            'varPassword',
            'chrDraft',
            'intSearchRank',
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
            'intAliasId',
            'intFKCategory',
            'varTitle', 'varSector',
            'fkIntImgId',
            'fkIntDocId',
            'txtDescription',
            'varShortDescription',
            'varAdminPhone',
            'varAdminEmail',
            'varLocation',
            'varAddress',
            'intSearchRank',
            'dtDateTime',
            'dtEndDateTime',
            'chrPublish',
            'fkMainRecord',
            'varMetaTitle',
            'varMetaDescription',
            'UserID',
            'chrPageActive',
            'varPassword',
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
        if (!empty($filterArr['catFilter']) && $filterArr['catFilter'] != ' ') {
            $data = $query->where('intFKCategory', $filterArr['catFilter']);
        }
        if (isset($filterArr['ignore']) && !empty($filterArr['ignore'])) {
            $data = $query->whereNotIn('events.id', $filterArr['ignore']);
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
            ->checkStarRecord('Y')
            ->where('chrTrash', '!=', 'Y');
        if (!$isAdmin) {
            $response = $response->where('varSector', $userRoleSector);
        }
        $response = $response->count();
        return $response;
    }

    public static function getChildGrid()
    {
        $eventsCatfileds = ['id', 'varTitle'];
        $id = $_REQUEST['id'];
        $response = false;
        $moduleFields = [
            'id',
            'intFKCategory',
            'varTitle', 'varSector',
            'txtDescription',
            'varShortDescription',
            'varAdminPhone',
            'varAdminEmail',
            'dtDateTime',
            'dtEndDateTime',
            'chrPublish',
            'fkMainRecord',
            'created_at',
            'chrApproved',
            'updated_at',
            'intApprovedBy',
            'UserID',
            'chrPageActive',
            'varPassword',
            'chrDraft',
            'intSearchRank',
            'chrTrash',
            'dtApprovedDateTime',
            'FavoriteID',
            'created_at',
            'updated_at',
        ];
        $response = Self::getPowerPanelRecords($moduleFields, false, $eventsCatfileds)
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
        $eventsCatfileds = ['id', 'varTitle'];
        $id = $_REQUEST['id'];
        $response = false;
        $moduleFields = [
            'id',
            'intFKCategory',
            'varTitle', 'varSector',
            'txtDescription',
            'varShortDescription',
            'varAdminPhone',
            'varAdminEmail',
            'chrPublish',
            'dtDateTime',
            'dtEndDateTime',
            'fkMainRecord',
            'created_at',
            'chrApproved',
            'updated_at',
            'intApprovedBy',
            'UserID',
            'chrPageActive',
            'varPassword',
            'chrDraft',
            'intSearchRank',
            'chrTrash',
            'FavoriteID',
            'dtApprovedDateTime',
            'created_at',
            'updated_at',
        ];
        $response = Self::getPowerPanelRecords($moduleFields, false, $eventsCatfileds)
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
        $eventsCatfileds = ['id', 'varTitle'];
        $response = false;
        $moduleFields = [
            'id',
            'intAliasId',
            'intFKCategory',
            'varTitle', 'varSector',
            'fkIntImgId',
            'fkIntDocId',
            'txtDescription',
            'varShortDescription',
            'varAdminPhone',
            'varAdminEmail',
            'varLocation',
            'varAddress',
            'intSearchRank',
            'dtDateTime',
            'dtEndDateTime',
            'chrPublish',
            'chrPageActive',
            'varPassword',
            'chrDraft',
            'intSearchRank',
            'chrTrash',
            'FavoriteID',
            'created_at',
            'updated_at',
        ];
        $aliasFields = ['id', 'varAlias'];
        $response = Self::getPowerPanelRecords($moduleFields, false, $eventsCatfileds)
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
            'intFKCategory' => $response['intFKCategory'],
            'varSector' => $response['varSector'],
            'fkIntImgId' => $response['fkIntImgId'],
            'fkIntDocId' => $response['fkIntDocId'],
            'txtDescription' => $response['txtDescription'],
            'chrAddStar' => 'N',
            'intSearchRank' => $response['intSearchRank'],
            'dtDateTime' => $response['dtDateTime'],
            'dtEndDateTime' => $response['dtEndDateTime'],
            'chrDraft' => $response['chrDraft'],
            'intSearchRank' => $response['intSearchRank'],
            'FavoriteID' => $response['FavoriteID'],
            'chrPageActive' => $response['chrPageActive'],
            'chrPublish' => $response['chrPublish'],
        ];
        CommonModel::updateRecords($whereConditions, $updateMainRecord, false, 'Powerpanel\Events\Models\Events');
        //Update Copy Child Record To Main Record end
        $whereConditions_ApproveN = ['fkMainRecord' => $main_id];
        $updateToApproveN = [
            'chrApproved' => 'N',
            'chrLetest' => 'N',
            'intApprovedBy' => '0',
        ];
        CommonModel::updateRecords($whereConditions_ApproveN, $updateToApproveN, false, 'Powerpanel\Events\Models\Events');
        $whereConditionsApprove = ['id' => $id, 'chrMain' => 'N'];
        $updateToApprove = [
            'chrApproved' => 'Y',
            'chrRollBack' => 'Y',
            'intApprovedBy' => auth()->user()->id,
            'dtApprovedDateTime' => date('Y-m-d H:i:s'),
        ];
        CommonModel::updateRecords($whereConditionsApprove, $updateToApprove, false, 'Powerpanel\Events\Models\Events');
        $msg_show = "Record successfully approved.";
        return $msg_show;
    }

    public static function getCountById($categoryId = null)
    {
        $response = false;
        $moduleFields = ['id'];
        $response = Self::getPowerPanelRecords($moduleFields)
            ->checkCategoryId($categoryId)
            ->where('chrMain', 'Y')
            ->where('chrIsPreview', 'N')
            ->deleted()
            ->count();
        return $response;
    }

    public static function getFrontList($filterArr = false, $page = 1, $catid = false, $print = false, $categoryid, $name = "", $start_date_time = "", $end_date_time = "")
    {
        $response = false;
        $moduleFields = ['id',
            'intAliasId',
            'varTitle', 'varSector',
            'intFKCategory',
            'txtDescription',
            'varShortDescription',
            'varAdminPhone',
            'varAdminEmail',
            'fkIntImgId',
            'fkIntDocId',
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
                ->deleted()
                ->publish();
            if ($categoryid != '') {
                $response = $response->whereRaw(DB::raw('intFKCategory="' . $categoryid . '"'));
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
                ->where('chrIsPreview', 'N')
                ->where('chrMain', 'Y');
            if ($catid != false) {
                $response = $response->where('intFKCategory', '=', $catid);
            }
            if ($print == 'print') {
                $response = $response->get();
            } else {
                $response = $response->paginate($page);
            }
        }
        return $response;
    }

    public static function getFrontListPopup($filterArr = false)
    {
        $response = false;
        $moduleFields = [ 'id',
            'intAliasId',
            'varTitle', 'varSector',
            'intFKCategory',
            'txtDescription',
            'varShortDescription',
            'varAdminPhone',
            'varAdminEmail',
            'fkIntImgId',
            'fkIntDocId',
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
    /**
     * This method handels retrival of news records
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public static function getFrontRecords($moduleFields = false, $aliasFields = false, $eventsCatfileds = false)
    {
        $response = false;
        $data = [];
        if ($aliasFields != false) {
            $data = [
                'alias' => function ($query) use ($aliasFields) {
                    $query->select($aliasFields);
                },
            ];
        }
        if ($eventsCatfileds != false) {
            $data['eventscat'] = function ($query) use ($eventsCatfileds) {
                $query->select($eventsCatfileds)->publish();
            };
        }
        $response = self::select($moduleFields)->with($data);
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
        $categoryModuleCode = Modules::getModule('coin-catalogue-category')->id;
        $filter = [];
        $moduleFields = [
            'id',
            'intFKCategory',
            'intAliasId',
        ];
        $catFileds = ['id', 'intAliasId'];
        $aliasFields = ['id', 'varAlias'];
        $response = Self::getSearchRecords($moduleFields, $catFileds, $aliasFields, $moduleCode, $categoryModuleCode)
            ->deleted()
            ->where('id', $id)
            ->first();
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
        $response = Cache::tags(['Events'])->get('getEventsRecordIdByAliasID_' . $aliasID);
        if (empty($response)) {
            $response = Self::Select('id')->deleted()->publish()->checkAliasId($aliasID)->first();
            Cache::tags(['Events'])->forever('getEventsRecordIdByAliasID_' . $aliasID, $response);
        }
        return $response;
    }

    public static function getSearchRecords($moduleFields, $catFileds, $aliasFields, $moduleCode, $categoryModuleCode)
    {
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

    public function cat()
    {
        $response = false;
        $response = $this->belongsTo('Powerpanel\EventCategory\Models\EventCategory', 'intFKCategory', 'id');
        return $response;
    }

    public function scopeCheckCategoryId($query, $id)
    {
        $response = false;
        $response = $query->where('intFKCategory', 'like', '%' . $id . '%')->orWhere('intFKCategory', 'like', '%' . $id . '%');
        return $response;
    }

    /**
     * This method handels Popular Event scope
     * @return  Object
     * @since   2016-08-30
     * @author  NetQuick
     */
    public function scopeLatest($query, $id = false)
    {
        $response = false;
        $response = $query
        /* ->groupBy('id') */
            ->orderBy('dtDateTime', 'desc');
        if ($id > 0) {
            $response = $response->where('id', '!=', $id);
            //->whereRaw('dtDateTime > DATE_SUB(NOW(), INTERVAL 7 DAY)')
            //->whereRaw('dtDateTime <= NOW()');
        }
        return $response;
    }

    /**
     * This method handels retrival of front latest executives list
     * @return  Object
     * @since   2017-10-13
     * @author  NetQuick
     */
    public static function getLatestList($id = false)
    {
        $response = false;
        $moduleFields = [
        		'id',
            'intAliasId',
            'fkIntImgId',
            'varTitle', 
            'varSector',
            'txtDescription',
            'varShortDescription',
            'varAdminPhone',
            'varAdminEmail',
            'varLocation',
            'varAddress',
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
        $response = Self::getFrontRecords($moduleFields, $aliasFields)
            ->where('id', '!=', $id)
						->where('chrTrash', '!=', 'Y')
						->where('chrDraft', '!=', 'D')
						->orderBy('dtDateTime', 'DESC')
						->where('chrIsPreview', 'N')
						->where('chrMain', 'Y')
            ->deleted()
            ->publish()
            ->take(5)
            ->get();
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

    public function scopeDateRange($query)
    {
        $response = false;
        $response = $query->whereRaw('((dtEndDateTime >= NOW() AND NOW() >= dtDateTime) OR (NOW() >= dtDateTime and dtEndDateTime is null))');
        return $response;
    }

    public static function getBuilderRecordList($filterArr = [])
    {
        $response = false;
        $moduleFields = [
            'events.id',
            'events.intAliasId',
            'events.varTitle',
            'events.varSector',
            'events.intFKCategory',
            'events.varLocation',
            'events.varAddress',
            'events.varAdminPhone',
            'events.varAdminEmail',
            DB::raw('IFNULL(COUNT(nq_page_hits.id), 0) AS hits'),
            'events.chrPublish',
            'events.chrDelete',
            'events.dtDateTime',
            'events.dtEndDateTime',
            'events.updated_at',
        ];
        $response = Self::getPowerPanelRecords($moduleFields, false, false, false, false)
            ->filter($filterArr);
        $response = $response->leftJoin('page_hits', 'events.intAliasId', '=', 'page_hits.fkIntAliasId')
            ->where('events.chrPublish', 'Y')
            ->where('events.chrDelete', 'N')
            ->where('events.chrMain', 'Y')
            ->where('events.chrTrash', '!=', 'Y')
            ->where('events.chrDraft', '!=', 'D')
            ->where('events.chrIsPreview', 'N')
            ->groupBy('events.id')
            ->get();
        return $response;
    }

    public static function getBuilderEvents($fields, $recIds)
    {
        $response = false;
        $moduleFields = ['id',
            'intAliasId',
            'varTitle',
            'varSector',
            'intFKCategory',
            'txtDescription',
            'varShortDescription',
            'varAdminPhone',
            'varAdminEmail',
            'varLocation',
            'varAddress',
            'fkIntImgId',
            'fkIntDocId',
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
        array_push($moduleFields, 'fkIntImgId');
        $aliasFields = ['id', 'varAlias'];
        $response = Cache::tags(['Events'])->get('getBuilderEvents_' . implode('-', $recIds));
        if (empty($response)) {
            $response = Self::getFrontRecords($moduleFields, $aliasFields)
                ->whereIn('id', $recIds)
                ->where('chrMain', 'Y')
                ->where('chrIsPreview', 'N')
                ->deleted()
                ->publish()
                ->orderByRaw(DB::raw("FIELD(id, " . implode(',', $recIds) . " )"));
            $response = $response->get();
            Cache::tags(['Events'])->forever('getBuilderEvents_' . implode('-', $recIds), $response);
        }
        return $response;
    }

    public static function getAllEvents($fields, $limit, $eventscat, $today, $dbFilter = false) {

        $response = false;
        $moduleFields = [
            'id',
            'fkMainRecord',
            'intAttendees',
            'intFKCategory',
            'intAliasId',
            'varTitle',
            'varSector',
            'fkIntImgId',
            'fkIntDocId',
            'txtDescription',
            'varShortDescription',
            'varLocation',
            'varAddress',
            'dtDateTime',
            'dtEndDateTime',
            'varMetaTitle',
            'varTags',
            'varMetaDescription',
            'chrMain',
            'intSearchRank',
            'chrIsPreview',
            'chrDraft',
            'chrTrash',
            'varAdminPhone',
            'varAdminEmail',
            'created_at',
            'updated_at',
        ];
        array_push($moduleFields, 'fkIntImgId');
        $aliasFields = ['id', 'varAlias'];
        $categoryModuleFields = ['id', 'varTitle'];
        if (empty($response)) {
            $response = Self::getFrontRecords($moduleFields, $aliasFields, $categoryModuleFields)
                            ->deleted()
                            ->where('chrMain', 'Y');
            if ($eventscat != '') {
                $response = $response->where('intFKCategory', $eventscat);
            }

            if (isset($dbFilter['category']) && !empty($dbFilter['category']) && strtolower($dbFilter['category']) != 'all') {
                $response->where('varSector', '=', strtolower($dbFilter['category']));
            }

            if (isset($dbFilter['eventCategory']) && !empty($dbFilter['eventCategory']) && strtolower($dbFilter['eventCategory']) != 'all') {
                
                $response->where('intFKCategory', $dbFilter['eventCategory']);
            }

            $response = $response->where('chrIsPreview', 'N')
                ->deleted()
                ->publish()
                ->where('chrTrash', '!=', 'Y')
                ->where('chrDraft', '!=', 'D');

            // if ($limit != '') {
            //     $response = $response->limit($limit);
            // }

            if (isset($dbFilter['sortVal']) && !empty($dbFilter['sortVal'])) {
                if ($dbFilter['sortVal'] == 'sortByNew') {
                    $response->orderBy('created_at', 'DESC');
                } elseif ($dbFilter['sortVal'] == 'sortByAsc') {
                    $response->orderBy('varTitle', 'ASC');
                } elseif ($dbFilter['sortVal'] == 'sortByDesc') {
                    $response->orderBy('varTitle', 'DESC');
                } else {
                    $response->orderBy('created_at', 'DESC');
                }
            } else {
                $response->orderBy('created_at', 'DESC');
            }
            
            if (request()->segment(1) != '') {
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

    public static function sitemap(){
        $eventsFields = ['id', 'intAliasId','varTitle','created_at','updated_at'];
        $aliasFields = ['id', 'varAlias', 'intFkModuleCode'];
        $moduleFields = ['id', 'varModuleName', 'varModuleClass'];
        $response = Self::getFrontRecords($eventsFields, $aliasFields)
            ->deleted()
            ->publish()
            ->where('chrIsPreview','=','N')
            ->checkMainRecord('Y')
            ->where('chrIsPreview', 'N')
            ->where('chrTrash', '!=', 'Y')
            ->orderBy('varTitle')
            ->get();
        return $response;
    }
}
