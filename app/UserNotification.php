<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;
use Carbon\Carbon;
use App\Helpers\MyLibrary;

class UserNotification extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'user_notifications';
    protected $fillable = [
        'id',
        'fkIntModuleId',
        'fkIntUserId',
        'fkIntRecordCode',
        'txtNotification',
        'chrPublish',
        'chrDelete',
        'varIpAddress',
        'created_at',
        'updated_at'
    ];

    /**
     * This method handels insert of event record
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public static function addRecord($data = false) {
        $response = false;
        $recordId = Self::insertGetId($data);
        if ($recordId > 0) {
            $response = $recordId;
        }
        return $response;
    }

    public static function getRecordListToday($userIsAdmin = false, $currentUserAccessibleModulesIDs = false,$recordCount = false) {
        // $Today = date("Y-m-d");
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
                // ->whereDate('user_notifications.created_at', $Today)
                ->where('user_notifications.chrPublish', 'Y')
                ->where('user_notifications.chrDelete', 'N')
                ->limit(10)
                ->orderBy('user_notifications.created_at', 'DESC')
                ->orderBy('user_notifications.id', 'DESC');
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
                            ->where('user_notifications.fkIntModuleId', 21)
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

        if($recordCount==true){
        	$response = $response->groupBy('user_notifications.id')->get()->count();
        }else{
        	$response = $response->groupBy('user_notifications.id')->get();
        }
        
        return $response;
    }

    public static function getRecordListLastsevenDays($userIsAdmin = false, $currentUserAccessibleModulesIDs = false,$recordCount = false) {
        $Today = date('Y-m-d', strtotime("-1 days"));
        $latseven = date('Y-m-d', strtotime("-2 days"));
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
                ->whereDate('user_notifications.created_at', "<=", $Today)
                ->whereDate('user_notifications.created_at', ">=", $latseven)
                ->where('user_notifications.chrPublish', 'Y')
                ->where('user_notifications.chrDelete', 'N')
                ->orderBy('user_notifications.created_at', 'DESC')
                ->orderBy('user_notifications.id', 'DESC');
        if (!$userIsAdmin) {
            $response = $response->where(function ($query) use ($currentUserAccessibleModulesIDs) {
                $query->where(function ($query) {
                    $query->where('user_notifications.fkIntModuleId', '=', 21)
                            ->where('user_notifications.intOnlyForUserId', Auth::id());
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
        if($recordCount==true){
        	$response = $response->groupBy('user_notifications.id')->get()->count();
        }else{
        	$response = $response->groupBy('user_notifications.id')->get();	
        }
        
        return $response;
    }

    public static function getReadRecordList($id = false) {
        $user = DB::table('user_read_notifications')->select('id')
                ->where('fkIntNotifications', $id)
                ->where('fkIntUserId', Auth::id())
                ->where('chrPublish', 'Y')
                ->where('chrDelete', 'N')
                ->first();
        return empty($user);
    }

    public static function getAllRecordList($userIsAdmin = false, $currentUserAccessibleModulesIDs = false) {
        $Today = date("Y-m-d");
        $latseven = date('Y-m-d', strtotime("-2 days"));
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
                ->whereDate('user_notifications.created_at', "<=", $Today)
                ->whereDate('user_notifications.created_at', ">=", $latseven)
                ->where('user_notifications.chrPublish', 'Y')
                ->where('user_notifications.chrDelete', 'N')
                ->orderBy('user_notifications.created_at', 'DESC')
                ->orderBy('user_notifications.id', 'DESC');
        if (!$userIsAdmin) {
            $response = $response->where(function ($query) use ($currentUserAccessibleModulesIDs) {
                $query->where(function ($query) {
                    $query->where('user_notifications.fkIntModuleId', '=', 21)
                            ->where('user_notifications.intOnlyForUserId', Auth::id());
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

        $response = $response->groupBy('user_notifications.id')->get();
        return $response;
    }

    public static function getReadCount($ids=false) {
        $Today = date("Y-m-d");
        $latseven = date('Y-m-d', strtotime("-2 days"));
        $user = DB::table('user_read_notifications')
        				->select('id')
        				->rightJoin('user_notifications', 'user_notifications.id', '=', 'user_read_notifications.fkIntNotifications')
//                ->where('user_read_notifications.fkIntUserId', Auth::id())
//                ->whereBetween('user_read_notifications.created_at', [$latseven, $Today])
                ->where('user_read_notifications.chrPublish', 'Y')
                ->where('user_read_notifications.chrDelete', 'N')
                ->whereIn('user_notifications.id',$ids)
                ->count();
        return $user;
    }

    public static function checkUserAlreadyread($notificationId, $userId) {
        $user = DB::table('user_read_notifications')->select('id')
                ->where('fkIntUserId', $userId)
                ->where('fkIntNotifications', $notificationId)
                ->where('chrPublish', 'Y')
                ->where('chrDelete', 'N')
                ->count();
        return $user;
    }

    public static function insertReadNotificationByUser($data) {
        $response = false;
        $recordData = [
            'fkIntNotifications' => $data['id'],
            'fkIntModuleId' => $data['ModuleId'],
            'fkIntUserId' => Auth::id(),
            'fkRecordId' => $data['RecordId'],
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'varIpAddress' => MyLibrary::get_client_ip(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ];

        $recordId = DB::table('user_read_notifications')->insertGetId($recordData);
        if ($recordId > 0) {
            $response = $recordId;
        }
        return $response;
    }

    public static function insertMarkAllReadNotificationByUser($data) {
        $response = false;
        $recordData = [
            'fkIntNotifications' => $data->id,
            'fkIntModuleId' => $data->fkIntModuleId,
            'fkIntUserId' => Auth::id(),
            'fkRecordId' => $data->fkRecordId,
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'varIpAddress' => MyLibrary::get_client_ip(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ];

        $recordId = DB::table('user_read_notifications')->insertGetId($recordData);
        if ($recordId > 0) {
            $response = $recordId;
        }
        return $response;
    }

    public static function getPowerPanelRecords($moduleFields = false) {
        $data = [];
        $response = false;
        $response = self::select($moduleFields);
        if (count($data) > 0) {
            $response = $response->with($data);
        }
        return $response;
    }

    public static function deleteNotificationByRecordID($recordId = false, $moduleId = false, $type = false) {
        $response = false;
        $NotificationIDs = Self::select('id')
                ->where('fkRecordId', $recordId);
        if (is_array($recordId)) {
            $NotificationIDs = $NotificationIDs->whereIn('fkRecordId', $recordId);
        } else {
            $NotificationIDs = $NotificationIDs->where('fkRecordId', $recordId);
        }
        $NotificationIDs = $NotificationIDs->where('fkIntModuleId', $moduleId)
                ->deleted()
                ->get()
                ->toArray();
        if (!empty($NotificationIDs)) {
            $NotificationIDs = array_column($NotificationIDs, 'id');
            Self::whereIn('id', $NotificationIDs)
                    ->delete();

            DB::table('user_read_notifications')
                    ->whereIn('fkIntNotifications', $NotificationIDs)
                    ->delete();
        }
        return $response;
    }

    public static function deleteReadNotificationByIDs($NotificationIDs = false) {
        $response = false;
        if (!empty($NotificationIDs)) {
            DB::table('user_read_notifications')
                    ->whereIn('fkIntNotifications', $NotificationIDs)
                    ->update(['chrPublish' => 'N', 'chrDelete' => 'Y']);
        }
        return $response;
    }

    public function scopeDeleted($query) {
        return $query->where(['chrDelete' => 'N']);
    }

}
