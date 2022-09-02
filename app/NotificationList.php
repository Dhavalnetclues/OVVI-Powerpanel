<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;

class NotificationList extends Model {

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_notifications';
    protected $fillable = ['*'];

    public static function getCurrentMonthCount() {
        $response = false;
        $response = Self::getRecords()
                ->whereRaw('MONTH(created_at) = MONTH(CURRENT_DATE())')
                ->whereRaw('YEAR(created_at) = YEAR(CURRENT_DATE())')
                ->where('chrPublish', '=', 'Y')
                ->where('chrDelete', '=', 'N')
                ->count();
        return $response;
    }

    public static function getCurrentYearCount() {
        $response = false;
        $response = Self::getRecords()
                ->whereRaw('YEAR(created_at) = YEAR(CURRENT_DATE())')
                ->where('chrPublish', '=', 'Y')
                ->where('chrDelete', '=', 'N')
                ->count();
        return $response;
    }

    /**
     * This method handels retrival of event records
     * @return  Object
     * @since   2017-08-02
     * @author  NetQuick
     */
    static function getRecords() {
        return self::with([]);
    }

    /**
     * This method handels retrival of record count
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getRecordById($id, $moduleFields = false) {
        $response = false;
        $moduleFields = ['*'];
        $response = Self::getPowerPanelRecords($moduleFields)->deleted()->checkRecordId($id)->first();
        return $response;
    }

    /**
     * This method handels backend records
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    static function getPowerPanelRecords($moduleFields = false) {
        $data = [];
        $response = false;
        $response = self::select($moduleFields);
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
    /*public static function getRecordList($filterArr = false) {
        $response = false;
        $moduleFields = ['*'];
        $response = Self::getPowerPanelRecords($moduleFields)
                ->deleted()
                ->filter($filterArr)
                ->get();
        return $response;
    }*/

    public static function getNotificationRecordList($filterArr = false,$userIsAdmin = false, $currentUserAccessibleModulesIDs = array(),$retunTotalRecords = false) {
        
        $response = false;
        $moduleFields = [
            'user_notifications.id',
            'user_notifications.fkIntModuleId',
            'user_notifications.fkIntUserId',
            'user_notifications.chrNotificationType',
            'user_notifications.intOnlyForUserId',
            'user_notifications.fkRecordId',
            'user_notifications.txtNotification',
            'user_notifications.created_at',
            'user_notifications.chrPublish',
            'user_notifications.chrDelete',
            'module.varTableName',
            'module.varTitle as ModuleTitle',
            'module.varModelName as ModelName',
            'module.varModuleName as ModuleName',
        ];
        $response = Self::getPowerPanelRecords($moduleFields)
                ->leftJoin('module', 'module.id', '=', 'user_notifications.fkIntModuleId')
                ->where('user_notifications.chrPublish', 'Y')
                ->where('user_notifications.chrDelete', 'N');
                if (!empty($filterArr['orderByFieldName']) && !empty($filterArr['orderTypeAscOrDesc'])) {
                	$response = $response->orderBy('user_notifications.'.$filterArr['orderByFieldName'], $filterArr['orderTypeAscOrDesc']);
                }else{
                	$response = $response->orderBy('user_notifications.created_at', 'DESC')
                	                     ->orderBy('user_notifications.id', 'DESC');	
                }
                
        if (!$userIsAdmin) {
            $response = $response->where(function ($query) use ($currentUserAccessibleModulesIDs) {
                $query->where(function ($query) {
                    $query->where('user_notifications.fkIntModuleId', 21)
                            ->Where('user_notifications.intOnlyForUserId', Auth::id());
                });

                $query->orWhere(function ($query) use ($currentUserAccessibleModulesIDs) {
                    $query->whereIn('user_notifications.fkIntModuleId', $currentUserAccessibleModulesIDs)
                            ->where('user_notifications.chrNotificationType', 'C')
                            ->where('user_notifications.intOnlyForUserId', Auth::id());
                });
                $query->orWhere(function ($query) use ($currentUserAccessibleModulesIDs) {
                    $query->whereIn('user_notifications.fkIntModuleId', $currentUserAccessibleModulesIDs)
                            ->where('user_notifications.chrNotificationType', 'L');
                });

                $query->orWhere('user_notifications.intOnlyForUserId', Auth::id());
            });
        } else {
            $response = $response->where(function ($query) use ($currentUserAccessibleModulesIDs) {
                $query->where(function ($query) use ($currentUserAccessibleModulesIDs) {
                    $query->whereIn('user_notifications.fkIntModuleId', $currentUserAccessibleModulesIDs)
                            ->where('user_notifications.fkIntModuleId', '=', 21)
                            ->where('user_notifications.intOnlyForUserId', Auth::id());
                });

                $query->orWhere(function ($query) use ($currentUserAccessibleModulesIDs) {
                    $query->whereIn('user_notifications.fkIntModuleId', $currentUserAccessibleModulesIDs)
                            ->where('user_notifications.fkIntModuleId', '!=', 21)
                            ->where('user_notifications.fkIntUserId', '!=', Auth::id())
                            ->where('user_notifications.chrNotificationType', "!=", 'C')
                            ->whereNull('intOnlyForUserId');
                });

                $query->orWhere(function ($query) use ($currentUserAccessibleModulesIDs) {
                    $query->whereIn('user_notifications.fkIntModuleId', $currentUserAccessibleModulesIDs)
                            ->where('user_notifications.chrNotificationType', 'C')
                            ->where('user_notifications.intOnlyForUserId', Auth::id());
                });

                $query->orWhere('user_notifications.intOnlyForUserId', Auth::id());
            });
        }

        if (!$retunTotalRecords) {
            if (!empty($filterArr['iDisplayLength']) && $filterArr['iDisplayLength'] > 0) {
                $response = $response->skip($filterArr['iDisplayStart'])->take($filterArr['iDisplayLength']);
            }
        }

        if($retunTotalRecords){
					$response = $response->groupBy('user_notifications.id')->get();
					$response = count($response);
        }else{
        	$response = $response->groupBy('user_notifications.id')->get();
        }
        return $response;
    }

    public static function getRecordListDashboard($year = false, $timeparam = false, $month = false) {
        $response = false;
        $response = Self::select('id');
        $response = $response->where('chrPublish', '=', 'Y')->where('chrDelete', '=', 'N');
        if ($timeparam != 'month') {
            $response = $response->whereRaw("YEAR(created_at) = " . (int) $year . "")->count();
        } else {
            $response = $response->whereRaw("YEAR(created_at) = " . (int) $year . "")->whereRaw("MONTH(created_at) = " . (int) $month . "")->count();
        }
        return $response;
    }

    /**
     * This method handels retrival of backend record list
     * @return  Object
     * @since   2017-10-24
     * @author  NetQuick
     */
    public static function getCronRecords() {
        $response = false;
        $moduleFields = ['*'];
        $response = Self::getPowerPanelRecords($moduleFields)
                ->deleted()
                ->publish()
                ->get();
        return $response;
    }

    /**
     * This method handels retrival of backend record list for Export
     * @return  Object
     * @since   2017-10-24
     * @author  NetQuick
     */
    public static function getListForExport($selectedIds = false) {
        $response = false;
        $moduleFields = ['*'];
        $query = Self::getPowerPanelRecords($moduleFields)->deleted();
        if (!empty($selectedIds) && count($selectedIds) > 0) {
            $query->checkMultipleRecordId($selectedIds);
        }
        $response = $query->orderByCreatedAtDesc()->get();
        return $response;
    }

    /**
     * This method handels record id scope
     * @return  Object
     * @since   2016-07-24
     * @author  NetQuick
     */
    function scopeCheckRecordId($query, $id) {
        return $query->where('id', $id);
    }

    /**
     * This method handels publish scope
     * @return  Object
     * @since   2017-08-02
     * @author  NetQuick
     */
    function scopePublish($query) {
        return $query->where(['chrPublish' => 'Y']);
    }

    /**
     * This method handels delete scope
     * @return  Object
     * @since   2017-08-02
     * @author  NetQuick
     */
    function scopeDeleted($query) {
        return $query->where(['chrDelete' => 'N']);
    }

    /**
     * This method check multiple records id
     * @return  Object
     * @since   2017-08-02
     * @author  NetQuick
     */
    function scopeCheckMultipleRecordId($query, $Ids) {
        return $query->whereIn('id', $Ids);
    }

    /**
     * This method handle order by query
     * @return  Object
     * @since   2017-08-02
     * @author  NetQuick
     */
    function scopeOrderByCreatedAtDesc($query) {
        return $query->orderBy('created_at', 'DESC');
    }

    /**
     * This method handels filter scope
     * @return  Object
     * @since   2017-08-02
     * @author  NetQuick
     */
    function scopeFilter($query, $filterArr = false, $retunTotalRecords = false) {
        $response = '';
        if (!empty($filterArr['orderByFieldName']) && !empty($filterArr['orderTypeAscOrDesc'])) {
            $query = $query->orderBy($filterArr['orderByFieldName'], 'desc');
        } else {
            $query = $query->orderBy('created_at', 'desc');
        }
        if (!$retunTotalRecords) {
            if (!empty($filterArr['iDisplayLength']) && $filterArr['iDisplayLength'] > 0) {
                $data = $query->skip($filterArr['iDisplayStart'])->take($filterArr['iDisplayLength']);
            }
        }
        if (!empty($filterArr['statusFilter']) && $filterArr['statusFilter'] != ' ') {
            $data = $query->where('chrPublish', $filterArr['statusFilter']);
        }
        if (isset($filterArr['searchFilter']) && !empty($filterArr['searchFilter'])) {
            $data = $query->where('varName', 'like', '%' . $filterArr['searchFilter'] . '%')->orwhere('varEmail', 'like', '%' . $filterArr['searchFilter'] . '%');
        }
        if (!empty($query)) {
            $response = $query;
        }
        return $response;
    }

}
