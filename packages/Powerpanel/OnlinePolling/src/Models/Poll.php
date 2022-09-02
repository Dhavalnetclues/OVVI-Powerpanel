<?php

/**
 * The onlinepolling class handels bannner queries
 * ORM implemetation.
 * @package   Netquick powerpanel
 * @license   http://www.opensource.org/licenses/BSD-3-Clause
 * @version   1.1
 * @since       2017-07-20
 * @author    NetQuick
 */

namespace Powerpanel\OnlinePolling\Models;

use App\CommonModel;
use Cache;
use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'polls';
    protected $fillable = [
        'id',
        'varTitle',
        'txtQuestionData',
        'intAudienceLimit',
        'intFKCategory',
        'fkMainRecord',
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
        'dtDateTime',
        'dtEndDateTime',
        'created_at',
        'updated_at'
    ];

    /**
     * This method handels retrival of onlinepollings records
     * @return  Object
     * @since   2016-07-20
     * @author  NetQuick
     */
    public static function getRecords()
    {
        $response = false;
        $response = Cache::tags(['onlinepolling'])->get('getonlinepollingRecords');
        if (empty($response)) {
            $response = Self::Select(['id', 'varTitle', 'intDisplayOrder', 'chrPublish'])
                ->deleted()
                ->publish()
                ->paginate(10);
            Cache::tags(['onlinepolling'])->forever('getonlinepollingRecords', $response);
        }
        return $response;
    }

    /**
     * This method handels backend records
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public static function getPowerPanelRecords($moduleFields = false)
    {
        $data = [];
        $response = false;
        $response = self::select($moduleFields);

        $data['child'] = function ($query) {
            $query->select(['id','varTitle','fkMainRecord'])
                    ->where('chrDelete', 'N')
                    ->where('dtApprovedDateTime','!=',NULL);
        };

        if (count($data) > 0) {
            $response = $response->with($data);
        }
        return $response;
    }

    public function child() {
		return $this->hasMany('Powerpanel\OnlinePolling\Models\Poll', 'fkMainRecord', 'id');
	}
   

    /**
     * This method handels retrival of backend record list
     * @return  Object
     * @since   2017-10-24
     * @author  NetQuick
     */
    public static function getRecordList($filterArr = false, $isAdmin = false)
    {
        $response = false;
        $userid = auth()->user()->id;
        $moduleFields = [
            'id',
            'fkMainRecord',
            'varTitle',
            'intAudienceLimit',
            'dtDateTime',
            'dtEndDateTime',
            'intDisplayOrder',
            'chrMain',
            'chrAddStar',
            'FavoriteID',
            'chrLetest',
            'chrPageActive',
            'chrIsPreview',
            'chrDraft',
            'chrTrash',
            'chrLock',
            'LockUserID',
            'chrRollBack',
            'chrPublish'
        ];

        $query = Self::getPowerPanelRecords($moduleFields)->deleted()->filter($filterArr);
        $query = $query->where(function ($query) use ($userid) {
                                $query->where("UserID", '=', $userid)->where('chrPageActive', '=', 'PR')->orWhere('chrPageActive', '!=', 'PR');
                            });
        $response = $query->checkMainRecord('Y')->where('chrIsPreview', 'N')->where('chrTrash', '!=', 'Y')->where('chrMain', 'Y')->get();

        return $response;
    }

    public static function getRecordList_tab1($filterArr = false)
    {
        $response = false;
        $MainIDs = Self::distinct()
            ->select("fkMainRecord")
            ->checkMainRecord('N')
            ->deleted()
            ->groupBy('fkMainRecord')
            ->get()
            ->toArray();
        $MainIDs = array_column($MainIDs, 'fkMainRecord');
        $moduleFields = [
            'id',
            'fkMainRecord',
            'varTitle',
            'intAudienceLimit',
            'dtDateTime',
            'dtEndDateTime',
            'intDisplayOrder',
            'chrMain',
            'chrAddStar',
            'FavoriteID',
            'chrLetest',
            'chrPageActive',
            'chrIsPreview',
            'chrDraft',
            'chrTrash',
            'chrRollBack',
            'chrPublish'
        ];
        $response = Self::getPowerPanelRecords($moduleFields)
            ->deleted()
            ->filter($filterArr)
            ->whereIn('id', $MainIDs)
            ->where('chrAddStar', 'Y')
            ->get();
        return $response;
    }

    /**
     * This method handels retrival of backend record list
     * @return  Object
     * @since   2017-10-24
     * @author  NetQuick
     */
    public static function getRecordListFavorite($filterArr = false, $isAdmin = false)
    {
        $response = false;
        $userid = auth()->user()->id;
        $moduleFields = [
            'id',
            'varTitle',
            'intAudienceLimit',
            'dtDateTime',
            'dtEndDateTime',
            'intDisplayOrder',
            'chrMain',
            'chrAddStar',
            'FavoriteID',
            'chrLetest',
            'chrPageActive',
            'chrIsPreview',
            'chrDraft',
            'chrTrash',
            'chrRollBack',
            'chrPublish'
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
        $response = $response->get();
        return $response;
    }

    /**
     * This method handels retrival of backend record list
     * @return  Object
     * @since   2017-10-24
     * @author  NetQuick
     */
    public static function getRecordListDraft($filterArr = false, $isAdmin = false)
    {
        $response = false;
        $blogsCatfileds = ['id', 'varTitle'];
        $userid = auth()->user()->id;
        $moduleFields = [
            'id',
            'varTitle',
            'intAudienceLimit',
            'dtDateTime',
            'dtEndDateTime',
            'intDisplayOrder',
            'chrMain',
            'chrAddStar',
            'FavoriteID',
            'chrLetest',
            'chrPageActive',
            'chrIsPreview',
            'chrDraft',
            'chrTrash',
            'chrRollBack',
            'chrPublish'
        ];
        $response = Self::getPowerPanelRecords($moduleFields)
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
     * This method handels retrival of backend record list
     * @return  Object
     * @since   2017-10-24
     * @author  NetQuick
     */
    public static function getRecordListTrash($filterArr = false, $isAdmin = false)
    {
        $response = false;
        $userid = auth()->user()->id;
        $moduleFields = [
            'id',
            'varTitle',
            'intAudienceLimit',
            'dtDateTime',
            'dtEndDateTime',
            'intDisplayOrder',
            'chrMain',
            'chrAddStar',
            'FavoriteID',
            'chrLetest',
            'chrPageActive',
            'chrIsPreview',
            'chrDraft',
            'chrTrash',
            'chrLock',
            'LockUserID',
            'chrRollBack',
            'chrPublish'
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
        $response = $response->get();
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
            'varTitle',
            'txtQuestionData',
            'intAudienceLimit',
            'dtDateTime',
            'dtEndDateTime',
            'intDisplayOrder',
            'chrMain',
            'chrAddStar',
            'fkMainRecord',
            'FavoriteID',
            'chrLetest',
            'chrPageActive',
            'chrIsPreview',
            'chrDraft',
            'chrTrash',
            'chrRollBack',
            'chrPublish'
        ];
        $response = Self::getPowerPanelRecords($moduleFields);
        if (!$ignoreDeleteScope) {
            $response = $response->deleted();
        }
        $response = $response->checkRecordId($id)
            ->first();
        return $response;
    }

    public static function getCountById($categoryId = null)
    {
        $response = false;
        $moduleFields = ['id'];
        $response = Self::getPowerPanelRecords($moduleFields)
            ->where('chrMain', 'Y')
            ->deleted()
            ->count();
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
            'varTitle',
            'intDisplayOrder',
            'chrPublish',
            'intFKCategory',
            'fkMainRecord',
            'UserID',
            'dtDateTime',
            'dtEndDateTime',
        ];
        $response = Self::getPowerPanelRecords($moduleFields)->deleted()->checkRecordId($id)->first();
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
        if ($filterArr['orderByFieldName'] != null && $filterArr['orderTypeAscOrDesc'] != null) {
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
            $data = $query->where('intFKCategory', $filterArr['catFilter']);
        }
        if (!empty($filterArr['searchFilter']) && $filterArr['searchFilter'] != ' ') {
            $data = $query->where('varTitle', 'like', "%" . $filterArr['searchFilter'] . "%");
        }
        if (!empty($query)) {
            $response = $query;
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

    public static function getCatWithParent()
    {
        $response = false;
        $categoryFields = ['id', 'varTitle'];
        $response = Self::getPowerPanelRecords($categoryFields)
            ->deleted()
            ->publish()
            ->where('chrMain', 'Y')->get();
        return $response;
    }

    public static function getRecordCountListApprovalTab($filterArr = false)
    {
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

    public static function getRecordCount($filterArr = false, $returnCounter = false)
    {
        $response = 0;
        $cmsPageFields = ['id'];
        $pageQuery = Self::getPowerPanelRecords($cmsPageFields);
        if ($filterArr != false) {
            $pageQuery = $pageQuery->filter($filterArr, $returnCounter);
        }
        $response = $pageQuery->deleted()->where('chrMain', 'Y')->count();
        return $response;
    }

    public static function getRecordCountforList($filterArr = false, $returnCounter = false, $isAdmin = false)
    {
        $response = 0;
        $cmsPageFields = ['id'];
        $response = Self::getPowerPanelRecords($cmsPageFields);
        if ($filterArr != false) {
            $response = $response->filter($filterArr, $returnCounter);
        }

        $response = $response->deleted()
            ->where('chrMain', 'Y')
            ->count();
        return $response;
    }

    public static function getNewRecordsCount()
    {
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
            ->count();
        return $response;
    }

    public static function getChildGrid()
    {
        $id = $_REQUEST['id'];
        $response = false;
        $moduleFields = ['id', 'varTitle', 'intDisplayOrder', 'intFKCategory', 'chrPublish', 'fkMainRecord', 'created_at', 'chrApproved', 'updated_at', 'intApprovedBy', 'UserID','dtApprovedDateTime'];
        $response = Self::getPowerPanelRecords($moduleFields)->deleted()->where('chrMain', 'N')->where('fkMainRecord', $id)->orderBy('created_at', 'desc')->get();
        return $response;
    }

    public static function getChildrollbackGrid()
    {
        $id = $_REQUEST['id'];
        $response = false;
        $moduleFields = ['id', 'varTitle', 'intDisplayOrder', 'chrPublish', 'intFKCategory', 'fkMainRecord', 'created_at', 'chrApproved', 'updated_at', 'intApprovedBy', 'UserID','dtApprovedDateTime'];
        $response = Self::getPowerPanelRecords($moduleFields)->deleted()->where('chrMain', 'N')->where('chrRollBack', 'Y')->where('fkMainRecord', $id)->orderBy('created_at', 'desc')->get();
        return $response;
    }

    public static function approved_data_Listing($request)
    {
        $id = $request->id;
        $main_id = $request->main_id;
        
        //$PUserid = $request->PUserid;
        //Select Child Record Data Start
        $response = false;
        $moduleFields = [
            'id',
            'varTitle',
            'intDisplayOrder',
            'chrPublish',
            'dtDateTime',
            'dtEndDateTime',
        ];
        $aliasFields = ['id', 'varAlias'];
        $response = Self::getPowerPanelRecords($moduleFields)->deleted()->where('chrMain', 'N')->where('id', $id)->orderBy('created_at', 'desc')->first();
        //Select Child Record Data END
        //Update Copy Child Record To Main Record start
        $whereConditions = ['id' => $main_id];
        $updateMainRecord = [
            'varTitle' => $response['varTitle'],
            'dtDateTime' => $response['dtDateTime'],
            'dtEndDateTime' => $response['dtEndDateTime'],
            'chrAddStar' => 'N',
            'chrPublish' => $response['chrPublish'],
        ];
        CommonModel::updateRecords($whereConditions, $updateMainRecord, false ,'\\Powerpanel\\OnlinePolling\\Models\\Poll');
        //Update Copy Child Record To Main Record end
        $whereConditions_ApproveN = ['fkMainRecord' => $main_id];
        $updateToApproveN = [
            'chrApproved' => 'N',
            'chrLetest' => 'N',
            'intApprovedBy' => '0',
        ];
        CommonModel::updateRecords($whereConditions_ApproveN, $updateToApproveN, false, '\\Powerpanel\\OnlinePolling\\Models\\Poll');
        $whereConditionsApprove = ['id' => $id, 'chrMain' => 'N'];
        $updateToApprove = [
            'chrApproved' => 'Y',
            'chrRollBack' => 'Y',
            'intApprovedBy' => auth()->user()->id,
            'dtApprovedDateTime' => date('Y-m-d H:i:s')
        ];
        CommonModel::updateRecords($whereConditionsApprove, $updateToApprove, false, '\\Powerpanel\\OnlinePolling\\Models\\Poll');
        $msg_show = "Record successfully approved.";
        return $msg_show;
    }

    public static function getRecordforEmailById($id)
    {
        $response = false;
        $moduleFields = ['id',
            'varTitle',
        ];
        $response = Cache::tags(['onlinepolling'])->get('getRecordforEmailById_' . $id);
        if (empty($response)) {
            $response = Self::getFrontRecords($moduleFields)
                ->deleted()
                ->publish()
                ->dateRange()
                ->orderBy('intDisplayOrder')
                ->where('chrMain', 'Y')
                ->CheckRecordId($id)
                ->first();
            Cache::tags(['onlinepolling'])->forever('getRecordforEmailById_' . $id, $response);
        }
        return $response;
    }

    public static function getFrontList($limit = 12)
    {
        $response = false;
        $moduleFields = ['id',
            'varTitle',
        'txtQuestionData',
        'intAudienceLimit',
            'dtDateTime'
        ];
        $response = Cache::tags(['onlinepolling'])->get('onlinepollingFrontList');
        if (empty($response)) {
            $response = Self::getFrontRecords($moduleFields)
                ->deleted()
                ->publish()
                ->dateRange()
                 ->orderBy('intDisplayOrder', 'asc')
                ->where('chrMain', 'Y')
                ->paginate($limit);
            Cache::tags(['onlinepolling'])->forever('onlinepollingFrontList', $response);
        }
        return $response;
    }

    public static function getFrontListForFooter()
    {
        $response = false;
        $moduleFields = [
            'id',
            'varTitle',
        ];
        $response = Cache::tags(['onlinepolling'])->get('onlinepollingFrontListForFooter');
        if (empty($response)) {
            $response = Self::getFrontRecords($moduleFields)
                ->deleted()
                ->publish()
                ->dateRange()
                ->orderBy('intDisplayOrder')
                ->where('chrMain', 'Y')
                ->get();
            Cache::tags(['onlinepolling'])->forever('onlinepollingFrontListForFooter', $response);
        }
        return $response;
    }

    public static function getFrontRecords($moduleFields = false, $aliasFields = false)
    {
        $data = [];
        if ($aliasFields != false) {
            $data = [
                'alias' => function ($query) use ($aliasFields) {
                    $query->select($aliasFields);
                },
            ];
        }
        return self::select($moduleFields)->with($data);
    }

    public function scopeDateRange($query)
    {
        $response = false;
        $response = $query->whereRaw('((dtEndDateTime >= NOW() AND NOW() >= dtDateTime) OR (NOW() >= dtDateTime and dtEndDateTime is null))');
        return $response;
    }

    public static function getOnlinePollingData()
    {
        $response = false;
        $moduleFields = ['id',
            'varTitle',
        ];
        $response = Cache::tags(['onlinepolling'])->get('onlinepollingFrontList');
        if (empty($response)) {
            $response = Self::getFrontRecords($moduleFields)
                ->deleted()
                ->publish()
                ->dateRange()
                ->orderBy('intDisplayOrder')
                ->where('chrMain', 'Y')
                ->get();
            Cache::tags(['onlinepolling'])->forever('onlinepollingFrontList', $response);
        }
        return $response;
    }

    public static function getOnlinePollingCatData($id = '')
    {

        $response = false;
        $moduleFields = ['*'];
        $response = Self::getPowerPanelRecords($moduleFields)->deleted()
            ->deleted()
            ->publish()
            ->where('intFKCategory', $id)
            ->get();
        return $response;
    }

     //Start Draft Count of Records
     public static function getRecordCountforListDarft($filterArr = false, $returnCounter = false, $isAdmin = false, $ignoreId = array())
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
     public static function getRecordCountforListTrash($filterArr = false, $returnCounter = false, $isAdmin = false, $ignoreId = array())
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
             })
             ->count();
         return $response;
     }
 
     //End Trash Count of Records
     //Start Favorite Count of Records
     public static function getRecordCountforListFavorite($filterArr = false, $returnCounter = false, $isAdmin = false, $ignoreId = array())
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
             })
             ->count();
         return $response;
     }

       
    public static function getPreviousRecordByMainId($id) {
        $response = Self::select('id','fkMainRecord')
                        ->where('fkMainRecord', $id)
                        ->where('chrMain', 'N')
                        ->where('chrDelete', 'N')
                        ->where('chrApproved', 'N')
                        ->where('dtApprovedDateTime','!=',NULL)
                        ->orderBy('dtApprovedDateTime','DESC')
                        ->first();
        return $response;

    }

}
