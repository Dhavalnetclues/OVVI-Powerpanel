<?php

/**
 * The Blogs class handels bannner queries
 * ORM implemetation.
 * @package   Netquick powerpanel
 * @license   http://www.opensource.org/licenses/BSD-3-Clause
 * @version   1.1
 * @since       2017-07-20
 * @author    NetQuick
 */

namespace Powerpanel\NumberAllocation\Models;

use App\CommonModel;
use App\Modules;
use Cache;
use DB;
use Illuminate\Database\Eloquent\Model;
use Request;

class NumberAllocation extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'number_allocation';
    protected $fillable = [
        'id',
        'fkMainRecord',
        'varSector',
        'nxx',
        'intFKCategory',
        'service',
        'note',
        'chrMain',
        'chrAddStar',
        'chrDraft',
        'chrPublish',
        'chrDelete',
        'chrTrash',
        'FavoriteID',
        'intSearchRank',
        'chrApproved',
        'intApprovedBy',
        'chrRollBack',
        'intDisplayOrder',
        'chrPageActive',
        'varPassword',
        'UserID',
        'chrIsPreview',
        'dtApprovedDateTime',
        'LockUserID',
        'chrLock',
        'created_at',
        'updated_at',
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
        $response = Cache::tags(['NumberAllocation'])->get('getNumberAllocationRecords');
        if (empty($response)) {
            $response = Self::Select([
            'id',
            'fkMainRecord',
            'varSector',
            'nxx',
            'intFKCategory',
            'service',
            'note',
            'chrPublish'])
                ->deleted()
                ->publish()
                ->paginate(10);
            Cache::tags(['NumberAllocation'])->forever('getNumberAllocationRecords', $response);
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
//        $response = Cache::tags(['Blogs'])->get('getBlogsRecordIdByAliasID_' . $aliasID);
        if (empty($response)) {
            $moduleFields = [
                'id',
                'varSector',
                'nxx',
                'intFKCategory',
                'service',
                'note',
                'chrAddStar',
                'chrDraft',
                'chrPublish',
                'chrTrash',
                'FavoriteID',
                'intSearchRank',
                'intDisplayOrder',
                'chrPageActive',
                'varPassword',
                'chrLock',
                'created_at',
                'updated_at',
            ];
            $response = Self::Select($moduleFields)
                ->deleted()
                ->publish()
                ->where('fkMainRecord', 0)
                ->checkAliasId($aliasID)
                ->first();
//            Cache::tags(['Blogs'])->forever('getBlogsRecordIdByAliasID_' . $aliasID, $response);
        }
        return $response;
    }

    /**
     * This method handels retrival of front blog detail
     * @return  Object
     * @since   2017-10-13
     * @author  NetQuick
     */
    public static function getNumberAllocationForCalender()
    {
        $response = false;
        $moduleFields = [
            'id',
            'varSector',
            'nxx',
            'intFKCategory',
            'service',
            'note',
            'chrAddStar',
            'chrDraft',
            'chrPublish',
            'chrTrash',
            'FavoriteID',
            'intSearchRank',
            'intDisplayOrder',
            'chrPageActive',
            'varPassword',
            'chrLock',
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
    public static function getPowerPanelRecords($moduleFields = false, $aliasFields = false, $numberAllocationCatfileds = false, $moduleCode = false)
    {
        $data = [];
        $response = false;
        $response = self::select($moduleFields);

        $data['child'] = function ($query) {
            $query->select(['id','nxx','fkMainRecord'])
                    ->where('chrDelete', 'N')
                    ->where('dtApprovedDateTime','!=',NULL);
        };

        if ($aliasFields != false) {
            $data['alias'] = function ($query) use ($aliasFields, $moduleCode) {
                $query->select($aliasFields)->checkModuleCode($moduleCode);
            };
        }
        // blogsCatfileds
        if ($numberAllocationCatfileds != false) {
            $data['blogsCat'] = function ($query) use ($numberAllocationCatfileds) {
                $query->select($numberAllocationCatfileds)->publish();
            };
        }
        if (count($data) > 0) {
            $response = $response->with($data);
        }
        return $response;
    }

    public function child() {
		return $this->hasMany('Powerpanel\NumberAllocation\Models\NumberAllocation', 'fkMainRecord', 'id');
	}

    public static function getAllNumberAllocations($limit = false, $dbFilter = false)
    {
        $response = false;
        if (empty($response)) {
            $moduleFields = [
                'id',
                'varSector',
                'nxx',
                'intFKCategory',
                'service',
                'note',
                'intSearchRank',
                'intDisplayOrder',
            ];
            $categoryField = ['id','varTitle'];
            $response = Self::getFrontRecords($moduleFields, false, $categoryField)
                    ->deleted()
                    ->publish()
                    ->where('chrMain', 'Y')
                    ->where('chrIsPreview', 'N')
                    ->where('chrTrash', '!=', 'Y')
                    ->where('chrDraft', '!=', 'D');                    

            if(isset($dbFilter['category']) && !empty($dbFilter['category']) && strtolower($dbFilter['category']) != 'all') {
                $response = $response->where('intFKCategory', $dbFilter['category']);
            }

            $response = $response->orderBy('intDisplayOrder', 'asc');

            // if (Request::segment(1) != '') {
            //     $pageNumber = 1;
            //     if(isset($dbFilter['pageNumber']) && !empty($dbFilter['pageNumber'])) {
            //         $pageNumber = $dbFilter['pageNumber'];
            //     }
            //     $response = $response->paginate(2, ['*'], 'page', $pageNumber);
            // } else {
            //     $response = $response->get();
            // }

            $response = $response->get();
        }
        return $response;
    }

    public function blogscat()
    {
        $response = false;
        $response = $this->belongsTo('Powerpanel\NumberAllocation\Models\CompanyCategory', 'intFKCategory', 'id');
        return $response;
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
        $blogsCatfileds = ['id', 'varTitle'];
        $moduleFields = [
            'id',
            'fkMainRecord',
            'varSector',
            'nxx',
            'intFKCategory',
            'service',
            'note',
            'chrMain',
            'chrAddStar',
            'chrDraft',
            'chrPublish',
            'chrDelete',
            'chrTrash',
            'FavoriteID',
            'intSearchRank',
            'intDisplayOrder',
            'chrPageActive',
            'varPassword',
            'chrIsPreview',
            'dtApprovedDateTime',
            'LockUserID',
            'chrLock',
            'created_at',
            'updated_at'
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
    public static function getRecordListFavorite($filterArr = false, $isAdmin = false, $userRoleSector)
    {
        $userid = auth()->user()->id;
        $response = false;
        $blogsCatfileds = ['id', 'varTitle'];
        $moduleFields = [
            'id',
            'fkMainRecord',
            'varSector',
            'nxx',
            'intFKCategory',
            'service',
            'note',
            'chrMain',
            'chrAddStar',
            'chrDraft',
            'chrPublish',
            'chrDelete',
            'chrTrash',
            'FavoriteID',
            'intSearchRank',
            'intDisplayOrder',
            'chrPageActive',
            'varPassword',
            'chrIsPreview',
            'dtApprovedDateTime',
            'LockUserID',
            'chrLock',
            'created_at',
            'updated_at'
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
    public static function getRecordListDraft($filterArr = false, $isAdmin = false, $userRoleSector)
    {
        $userid = auth()->user()->id;
        $response = false;
        $blogsCatfileds = ['id', 'varTitle'];
        $moduleFields = [
            'id',
            'fkMainRecord',
            'varSector',
            'nxx',
            'intFKCategory',
            'service',
            'note',
            'chrMain',
            'chrAddStar',
            'chrDraft',
            'chrPublish',
            'chrDelete',
            'chrTrash',
            'FavoriteID',
            'intSearchRank',
            'intDisplayOrder',
            'chrPageActive',
            'varPassword',
            'chrIsPreview',
            'dtApprovedDateTime',
            'LockUserID',
            'chrLock',
            'created_at',
            'updated_at'
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

    /**
     * This method handels retrival of backend record list
     * @return  Object
     * @since   2017-10-24
     * @author  NetQuick
     */
    public static function getRecordListTrash($filterArr = false, $isAdmin = false)
    {
        $userid = auth()->user()->id;
        $response = false;
        $blogsCatfileds = ['id', 'varTitle'];
        $moduleFields = [
            'id',
            'fkMainRecord',
            'varSector',
            'nxx',
            'intFKCategory',
            'service',
            'note',
            'chrMain',
            'chrAddStar',
            'chrDraft',
            'chrPublish',
            'chrDelete',
            'chrTrash',
            'FavoriteID',
            'intSearchRank',
            'intDisplayOrder',
            'chrPageActive',
            'varPassword',
            'chrIsPreview',
            'dtApprovedDateTime',
            'LockUserID',
            'chrLock',
            'created_at',
            'updated_at'
        ];
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

    public static function getRecordList_tab1($filterArr = false, $isAdmin = false, $userRoleSector)
    {
        $response = false;
        $moduleFields = [
            'id',
            'fkMainRecord',
            'varSector',
            'nxx',
            'intFKCategory',
            'service',
            'note',
            'chrMain',
            'chrAddStar',
            'chrDraft',
            'chrPublish',
            'chrDelete',
            'chrTrash',
            'FavoriteID',
            'intSearchRank',
            'intDisplayOrder',
            'chrPageActive',
            'varPassword',
            'chrIsPreview',
            'dtApprovedDateTime',
            'LockUserID',
            'chrLock',
            'created_at',
            'updated_at'
        ];
        $blogsCatfileds = ['id', 'varTitle'];
        $MainIDs = Self::distinct()
            ->select("fkMainRecord")
            ->checkMainRecord('N')
            ->where('chrIsPreview', 'N')
            ->groupBy('fkMainRecord')
            ->deleted()
            ->get()
            ->toArray();
        $MainIDs = array_column($MainIDs, 'fkMainRecord');
        $response = Self::getPowerPanelRecords($moduleFields, false, $blogsCatfileds)
            ->deleted()
            ->where('chrAddStar', 'Y')
            ->where('chrTrash', '!=', 'Y');
            if(!$isAdmin){
                $response = $response->where('varSector', $userRoleSector);
            }
            $response = $response->checkStarRecord('Y')
            ->filter($filterArr)
            ->whereIn('id', $MainIDs)
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
            'fkMainRecord',
            'varSector',
            'nxx',
            'intFKCategory',
            'service',
            'note',
            'chrMain',
            'chrAddStar',
            'chrDraft',
            'chrPublish',
            'chrDelete',
            'chrTrash',
            'FavoriteID',
            'intSearchRank',
            'intDisplayOrder',
            'chrPageActive',
            'varPassword',
            'chrIsPreview',
            'dtApprovedDateTime',
            'UserID',
            'LockUserID',
            'chrLock',
            'created_at',
            'updated_at'
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
            'varSector',
            'nxx',
            'intFKCategory',
            'service',
            'note',
            'chrMain',
            'chrAddStar',
            'chrDraft',
            'chrPublish',
            'chrDelete',
            'chrTrash',
            'FavoriteID',
            'intSearchRank',
            'intDisplayOrder',
            'chrPageActive',
            'varPassword',
            'chrIsPreview',
            'dtApprovedDateTime',
            'UserID',
            'LockUserID',
            'chrLock',
            'created_at',
            'updated_at'
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
            $query = $query->orderBy('intDisplayOrder', 'ASC');
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
            $data = $query->whereNotIn('number_allocation.id', $filterArr['ignore']);
        }
        // echo '<pre>';print_r($filterArr['catFilter']);die;
        if (!empty($filterArr['catFilter']) && $filterArr['catFilter'] != ' ') {
            $data = $query->where('intFKCategory', $filterArr['catFilter']);
        }
        if (!empty($filterArr['customFilterIdentity']) && $filterArr['customFilterIdentity'] != ' ') {
            $data = $query->where('chrPageActive', $filterArr['customFilterIdentity']);
        }
        if (!empty($filterArr['searchFilter']) && $filterArr['searchFilter'] != ' ') {
            $data = $query->where('nxx', 'like', "%" . $filterArr['searchFilter'] . "%");
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
            ->where('chrMain', 'Y');
            if(!$isAdmin){
                $response = $response->where('varSector', $userRoleSector);
            }
            $response = $response->where('chrIsPreview', 'N')
            ->count();
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
            ->checkMainRecord('Y');
            if(!$isAdmin){
                $response = $response->where('varSector', $userRoleSector);
            }
            $response = $response->where('chrIsPreview', 'N')
            ->where('chrTrash', '!=', 'Y')
            ->count();
        return $response;
    }

    public static function getRecordCountListApprovalTab($filterArr = false, $returnCounter = false, $isAdmin = false, $userRoleSector)
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
            ->whereIn('id', $MainIDs)
            ->checkStarRecord('Y');
            if(!$isAdmin){
                $response = $response->where('varSector', $userRoleSector);
            }
            $response = $response->where('chrTrash', '!=', 'Y')
            ->count();
        return $response;
    }

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

    public static function getChildGrid()
    {
        $blogsCatfileds = ['id', 'varTitle'];
        $id = $_REQUEST['id'];
        $response = false;
        $moduleFields = [
            'id',
            'fkMainRecord',
            'varSector',
            'nxx',
            'intFKCategory',
            'service',
            'note',
            'chrMain',
            'chrAddStar',
            'chrDraft',
            'chrPublish',
            'chrDelete',
            'chrTrash',
            'FavoriteID',
            'chrApproved',
            'intSearchRank',
            'intDisplayOrder',
            'chrPageActive',
            'varPassword',
            'chrIsPreview',
            'dtApprovedDateTime',
            'UserID',
            'LockUserID',
            'chrLock',
            'created_at',
            'updated_at'
        ];
        $response = Self::getPowerPanelRecords($moduleFields, false, $blogsCatfileds)
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
        $blogsCatfileds = ['id', 'varTitle'];
        $id = $_REQUEST['id'];
        $response = false;
        $moduleFields = [
            'id',
            'fkMainRecord',
            'varSector',
            'nxx',
            'intFKCategory',
            'service',
            'note',
            'chrMain',
            'chrAddStar',
            'chrDraft',
            'chrPublish',
            'chrDelete',
            'chrTrash',
            'FavoriteID',
            'chrApproved',
            'intSearchRank',
            'intDisplayOrder',
            'chrPageActive',
            'varPassword',
            'chrIsPreview',
            'dtApprovedDateTime',
            'UserID',
            'LockUserID',
            'chrLock',
            'created_at',
            'updated_at'
        ];
        $response = Self::getPowerPanelRecords($moduleFields, false, $blogsCatfileds)
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

        //$PUserid = $request->PUserid;
        //Select Child Record Data Start
        $blogsCatfileds = ['id', 'varTitle'];
        $response = false;
        $moduleFields = [
            'id',
            'fkMainRecord',
            'varSector',
            'nxx',
            'intFKCategory',
            'service',
            'note',
            'chrMain',
            'chrAddStar',
            'chrDraft',
            'chrPublish',
            'chrDelete',
            'chrTrash',
            'FavoriteID',
            'chrApproved',
            'intSearchRank',
            'intDisplayOrder',
            'chrPageActive',
            'varPassword',
            'chrIsPreview',
            'dtApprovedDateTime',
            'UserID',
            'LockUserID',
            'chrLock',
            'created_at',
            'updated_at'
        ];
        $response = Self::getPowerPanelRecords($moduleFields, false, $blogsCatfileds)
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
            'nxx' => $response['nxx'],
            'intFKCategory' => $response['intFKCategory'],
            'varSector' => $response['varSector'],
            'service' => $response['service'],
            'note' => $response['note'],
            'chrAddStar' => 'N',
            'intSearchRank' => $response['intSearchRank'],
            'chrDraft' => $response['chrDraft'],
            'intSearchRank' => $response['intSearchRank'],
            'FavoriteID' => $response['FavoriteID'],
            'chrPageActive' => $response['chrPageActive'],
            'chrPublish' => $response['chrPublish'],
        ];
        CommonModel::updateRecords($whereConditions, $updateMainRecord, false, 'Powerpanel\NumberAllocation\Models\NumberAllocation');
        //Update Copy Child Record To Main Record end
        $whereConditions_ApproveN = ['fkMainRecord' => $main_id];
        $updateToApproveN = [
            'chrApproved' => 'N',
            'chrLetest' => 'N',
            'intApprovedBy' => '0',
        ];
        CommonModel::updateRecords($whereConditions_ApproveN, $updateToApproveN, false, 'Powerpanel\NumberAllocation\Models\NumberAllocation');
        $whereConditionsApprove = ['id' => $id, 'chrMain' => 'N'];
        $updateToApprove = [
            'chrApproved' => 'Y',
            'chrRollBack' => 'Y',
            'intApprovedBy' => auth()->user()->id,
            'dtApprovedDateTime' => date('Y-m-d H:i:s')
        ];
        CommonModel::updateRecords($whereConditionsApprove, $updateToApprove, false, 'Powerpanel\NumberAllocation\Models\NumberAllocation');
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
            'varTitle',
            'varSector',
            'intFkCategory',
            'txtDescription',
            'varShortDescription',
            'fkIntImgId',
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
        $response = Cache::tags(['Blogs'])->get('getFrontBlogsList_' . $page);
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
                ->where('chrIsPreview', 'N')
                ->where('chrMain', 'Y');
            if ($catid != false) {
                $response = $response->where('intFkCategory', '=', $catid);
            }
            if ($print == 'print') {
                $response = $response->get();
            } else {
                $response = $response->paginate($page);
            }
            Cache::tags(['Blogs'])->forever('getFrontBlogsList_' . $page, $response);
        }
        return $response;
    }

    /**
     * This method handels retrival of news records
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public static function getFrontRecords($moduleFields = false, $aliasFields = false, $numberAllocationCatfileds= false)
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

        if ($numberAllocationCatfileds != false) {
            $data['blogsCat'] = function ($query) use ($numberAllocationCatfileds) {
                $query->select($numberAllocationCatfileds);
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
        $response = Cache::tags(['Blogs'])->get('getBlogsRecordIdByAliasID_' . $aliasID);
        if (empty($response)) {
            $response = Self::Select('id')->deleted()->publish()->checkAliasId($aliasID)->first();
            Cache::tags(['Blogs'])->forever('getBlogsRecordIdByAliasID_' . $aliasID, $response);
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
        $response = $this->belongsTo('Powerpanel\NumberAllocation\Models\CompanyCategory', 'intFKCategory', 'id');
        return $response;
    }

    public function scopeCheckCategoryId($query, $id)
    {
        $response = false;
        $response = $query->where('intFKCategory', 'like', '%' . $id . '%')->orWhere('intFKCategory', 'like', '%' . $id . '%');
        return $response;
    }

    /**
     * This method handels Popular Blog scope
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
        $moduleFields = ['id',
            'intAliasId',
            'varTitle','varSector',
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
        $response = Cache::tags(['Executives'])->get('getFrontLatestBlogsList_' . $id);
        if (empty($response)) {
            $response = Self::getFrontRecords($moduleFields, $aliasFields)
                ->deleted()
                ->publish()
                ->latest($id)
                ->take(5)
                ->get();
            Cache::tags(['Executives'])->forever('getFrontLatestBlogsList_' . $id, $response);
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
            'blogs.id',
            'blogs.intAliasId',
            'blogs.varTitle','blogs.varSector',
            'blogs.intFKCategory',
            DB::raw('IFNULL(COUNT(nq_page_hits.id), 0) AS hits'),
            'blogs.chrPublish',
            'blogs.chrDelete',
            'blogs.dtDateTime',
            'blogs.dtEndDateTime',
            'blogs.updated_at',
        ];
        $response = Self::getPowerPanelRecords($moduleFields, false, false, false, false)
            ->filter($filterArr);
        $response = $response->leftJoin('page_hits', 'blogs.intAliasId', '=', 'page_hits.fkIntAliasId')
            ->where('blogs.chrPublish', 'Y')
            ->where('blogs.chrDelete', 'N')
            ->where('blogs.chrMain', 'Y')
            ->where('blogs.chrTrash', '!=', 'Y')
            ->where('blogs.chrDraft', '!=', 'D')
            ->where('blogs.chrIsPreview', 'N')
            ->groupBy('blogs.id')
            ->get();
        return $response;
    }

    public static function getBuilderBlog($fields, $recIds)
    {
        $response = false;
        $moduleFields = ['id',
            'intAliasId',
            'varTitle','varSector',
            'intFkCategory',
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
        array_push($moduleFields, 'fkIntImgId');
        $aliasFields = ['id', 'varAlias'];
        $response = Cache::tags(['Blogs'])->get('getBuilderBlog_' . implode('-', $recIds));
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
            Cache::tags(['Blogs'])->forever('getBuilderBlog_' . implode('-', $recIds), $response);
        }
        return $response;
    }

    public static function getAllBlogs($fields, $limit, $sdate, $edate, $blogscat)
    {
        $response = false;
        $moduleFields = ['id',
            'intAliasId',
            'varTitle','varSector',
            'intFkCategory',
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
        array_push($moduleFields, 'fkIntImgId');
        $aliasFields = ['id', 'varAlias'];
        if (empty($response)) {
            $response = Self::getFrontRecords($moduleFields, $aliasFields)
                ->where('chrMain', 'Y');
            if ($blogscat != '') {
                $response = $response->where('intFkCategory', $blogscat);
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
                $response = $response->paginate(6);
            } else {
                $response = $response->get();
            }

        }
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

    //End Draft Count of Records
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
