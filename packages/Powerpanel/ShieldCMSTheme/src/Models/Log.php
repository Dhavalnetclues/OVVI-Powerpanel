<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\CommonModel;
use DB;

class Log extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'log';
    protected $fillable = [
        'id',
        'fkIntUserId',
        'fkIntModuleId',
        'intRecordId',
        'txtOldVal',
        'txtNewVal',
        'chrPublish',
        'chrDelete',
        'varIpAddress',
        'varAction',
        'created_at',
        'updated_at'
    ];

    /**
     * This method handels retrival of record count
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    protected static $fetchedID = [];
    protected static $fetchedObj = null;

    public static function getRecordById($id = false) {
        $response = false;
        $moduleFields = [
            'id',
            'fkIntUserId',
            'fkIntModuleId',
            'intRecordId',
            'txtOldVal',
            'txtNewVal',
            'chrPublish',
            'chrDelete',
            'varIpAddress',
            'varAction'
        ];
        if (!in_array($id, Self::$fetchedID)) {
            array_push(Self::$fetchedID, $id);
            Self::$fetchedObj = Self::getPowerPanelRecords($moduleFields)
                    ->checkRecordId($id)
                    ->first();
        }
        $response = Self::$fetchedObj;
        return $response;
    }

    /**
     * This method handels retrival of service records
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    static function getPowerPanelRecords($logFields = false, $moduleFields = false, $userfields = false) {
        $response = false;
        $data = [];
        $response = self::select($logFields);
        if ($moduleFields != false) {
            $data['module'] = function ($query) use ($moduleFields) {
                $query->select($moduleFields);
            };
        }
        if ($moduleFields != false) {
            $data['user'] = function ($query) use ($userfields) {
                $query->select($userfields);
            };
        }
        if (count($data) > 0) {
            $response = $response->with($data);
        }
        return $response;
    }

    /**
     * This method get records 
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    static function getRecords() {
        return self::with(['user', 'module']);
    }

    /**
     * This method handels alias relation
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public function module() {
        return $this->belongsTo('App\Modules', 'fkIntModuleId', 'id');
    }

    /**
     * This method handels alias relation
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public function user() {
        return $this->belongsTo('App\User', 'fkIntUserId', 'id');
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
     * This method handels delete scope
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    function scopeDeleted($query) {
        return $query->where(['log.chrDelete' => 'N']);
    }

    function scopeFilter($query, $filterArr = false, $retunTotalRecords = false) {
        $response = null;
        if ($filterArr['orderByFieldName'] != null && $filterArr['orderTypeAscOrDesc'] != null) {
            $query = $query->orderBy('log.' . $filterArr['orderByFieldName'], $filterArr['orderTypeAscOrDesc']);
        } else {
            $query = $query->orderBy('log.id', 'DESC');
        }
        if (!empty($filterArr['moduleFilter']) && $filterArr['moduleFilter'] != ' ') {
            $data = $query->where('fkIntModuleId', $filterArr['moduleFilter']);
        }
        if (!empty($filterArr['pageFilter']) && $filterArr['pageFilter'] != ' ') {
            $data = $query->where('intRecordId', $filterArr['pageFilter']);
        }

        if (!empty($filterArr['userFilter']) && $filterArr['userFilter'] != ' ') {
            $data = $query->where('fkIntUserId', $filterArr['userFilter']);
        }

        if (!empty($filterArr['customFilterIdentity'])) {
        			$data = $query->where(function ($query) use ($filterArr) {
                    foreach($filterArr['customFilterIdentity'] as $key=> $value){
                    	if($key==0){
                    		$query->where('varAction',$value);	
                    	}else{
                    		$query->orWhere('varAction',$value);	
                    	}
                    }
              });
            //$data = $query->where('varAction', $filterArr['customFilterIdentity']);
        }

        if (isset($filterArr['searchFilter']) && !empty($filterArr['searchFilter']) && $filterArr['searchFilter'] != ' ') {
            $query = $query->leftJoin('users', 'users.id', '=', 'log.fkIntUserId');
            $data = $query->where('users.email', 'like', '%' . $filterArr['searchFilter'] . '%')
                    ->orWhere('users.name', 'like', '%' . $filterArr['searchFilter'] . '%')
                    ->orWhere('log.varIpAddress', 'like', '%' . $filterArr['searchFilter'] . '%')
                    ->orWhere('log.varAction', 'like', '%' . $filterArr['searchFilter'] . '%');
        }

        if (!$retunTotalRecords) {
            if (!empty($filterArr['iDisplayLength']) && $filterArr['iDisplayLength'] > 0) {
                $data = $query->skip($filterArr['iDisplayStart'])->take($filterArr['iDisplayLength']);
            }
        }
        if (!empty($query)) {
            $response = $query;
        }
        return $response;
    }

    public static function deleteRecordsPermanent($data = false) {
        self::whereIn('id', $data)->delete();
    }

    public static function recordLog($data = false) {
        $response = false;
        if ($data != false && !empty($data)) {
            $log = [];
            $log['fkIntUserId'] = $data['userId'];
            $log['fkIntModuleId'] = $data['moduleCode'];
            $log['intRecordId'] = $data['fk_record_id'];
            $log['txtOldVal'] = isset($data['old_val']) ? $data['old_val'] : '-';
            $log['txtNewVal'] = isset($data['new_val']) ? $data['new_val'] : '-';
            $log['varTitle'] = isset($data['varTitle']) ? $data['varTitle'] : null;
            $log['chrPublish'] = $data['chr_publish'];
            $log['chrDelete'] = $data['chr_delete'];
            $log['varAction'] = $data['action'];
            $log['varIpAddress'] = $data['ipAddress'];
            $log['created_at'] = date('Y-m-d H:i:s');
            $log['updated_at'] = date('Y-m-d H:i:s');

            $recordId = self::insertGetId($log);
            if ($recordId > 0) {
                $response = $recordId;
            }
        }
        return $response;
    }

    /**
     * This method handels retrival of backend record list for Export
     * @return  Object
     * @since   2017-10-24
     * @author  NetQuick
     */
    public static function getListForExport($selectedIds = false,$moduleId= false,$recordId=false) {
        $response = false;
        if (!empty($selectedIds)) {
            $selectedIds = explode(',', $selectedIds);
        }
        $logFields = [
        	'id', 
        	'varTitle', 
        	'fkIntUserId', 
        	'fkIntModuleId', 
        	'intRecordId', 
        	'varAction', 
        	'varIpAddress', 
        	'created_at'
        ];
        $userFields = [
        	'id', 
        	'name', 
        	'email'
        ];
        $moduleFields = [
        	'id', 
        	'varTitle', 
        	'varModuleName'
        ];
        $query = Self::getPowerPanelRecords($logFields, $moduleFields, $userFields)->deleted();
        if (!empty($selectedIds) && count($selectedIds) > 0) {
            $query->checkMultipleRecordId($selectedIds);
        }
        if(!empty($moduleId) && $moduleId > 0){
        		$query->where('fkIntModuleId',$moduleId);
        }
        if(!empty($recordId) && $recordId > 0){
        		$query->where('intRecordId',$recordId);
        }
        $response = $query->orderByCreatedAtDesc()->get();
        return $response;
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

}
