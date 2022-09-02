<?php

namespace Powerpanel\ShieldCMSTheme\Models;

use Illuminate\Database\Eloquent\Model;
use App\EmailType;
use DB;

class EmailLog extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'email_log';
    protected $fillable = [
        'id',
        'intFkUserId',
        'intFkEmailType',
        'chrReceiverType',
        'intFkModuleId',
        'intFkRecordId',
        'varFrom',
        'txtTo',
        'txtCc',
        'txtBcc',
        'txtSubject',
        'chrAttachment',
        'chrIsSent',
        'chrPublish',
        'chrDelete',
        'chrIpAddress',
        'varBrowserInfo',
        'created_at',
        'updated_at'
    ];

    /**
     * This method get records 
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    static function getRecords($searchVal = '') {
        return self::with(['emailType']);
    }

    /**
     * This method handels email type relation
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public function emailType() {
        return $this->belongsTo('App\EmailType', 'intFkEmailType', 'id');
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
        return $query->where(['chrDelete' => 'N']);
    }

    /**
     * This method inserts email log data
     * @return  id
     * @since   2016-07-14
     * @author  NetQuick
     */
    static function logEmail($data) {
        return EmailLog::insertGetId($data);
    }

    /**
     * This method updates email log data
     * @return  id
     * @since   2016-07-14
     * @author  NetQuick
     */
    static function updateEmailLog($where, $data) {
        EmailLog::where($where)->update($data);
    }

    function scopeFilter($query, $filterArr = false, $retunTotalRecords = false) {
        $response = null;
        if ($filterArr['orderByFieldName'] != null && $filterArr['orderTypeAscOrDesc'] != null) {
            $query = $query->orderBy($filterArr['orderByFieldName'], $filterArr['orderTypeAscOrDesc']);
        } else {
            $query = $query->orderBy('varFrom', 'ASC');
        }
        if (!$retunTotalRecords) {
            if (!empty($filterArr['iDisplayLength']) && $filterArr['iDisplayLength'] > 0) {
                $data = $query->skip($filterArr['iDisplayStart'])->take($filterArr['iDisplayLength']);
            }
        }
        if (!empty($filterArr['searchFilter']) && $filterArr['searchFilter'] != ' ') {

            $emailid = EmailType::getRecordBySearch($filterArr['searchFilter']);
            $data = $query->whereIn('intFkEmailType', $emailid);
        }
        if (!empty($filterArr['emailtypeFilter']) && $filterArr['emailtypeFilter'] != ' ') {
            $data = $query->where('intFkEmailType', $filterArr['emailtypeFilter']);
        }
        if (!empty($query)) {
            $response = $query;
        }
        return $response;
    }

    public static function deleteRecordsPermanent($data = false) {
        self::whereIn('id', $data)->delete();
    }

    /**
     * This method handels backend records
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    static function getPowerPanelRecords($moduleFields = false, $emailTypeFields = false) {
        $data = [];
        $response = false;
        $response = self::select($moduleFields);

        if ($emailTypeFields != false) {
            $data['emailType'] = function ($query) use ($emailTypeFields) {
                $query->select($emailTypeFields);
            };
        }

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
    public static function getRecordList($filterArr = false) {
        $response = false;
        $moduleFields = ['id', 'intFkEmailType', 'varFrom', 'txtTo', 'chrIsSent', 'chrAttachment', 'created_at', 'chrPublish'];
        $emailTypeFields = ['id', 'varEmailType'];
        $response = Self::getPowerPanelRecords($moduleFields, $emailTypeFields)
                ->deleted()
                ->filter($filterArr)
                ->get();
        return $response;
    }

    /**
     * This method handels retrival of record by id
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getRecordById($id) {
        $response = false;
        $moduleFields = ['id', 'intFkUserId', 'intFkEmailType', 'chrReceiverType', 'intFkModuleId', 'intFkRecordId', 'varFrom', 'txtTo', 'txtCc', 'txtBcc', 'txtSubject', 'chrAttachment', 'chrIsSent', 'chrPublish', 'created_at'];
        $response = Self::getPowerPanelRecords($moduleFields)->deleted()->checkRecordId($id)->first();
        return $response;
    }

    public static function getRecordByModule($rec_id = false, $mod_id = false) {
        $user = DB::table('email_log')->select('txtBody')
                ->where('intFkModuleId', $mod_id)
                ->where('intFkRecordId', $rec_id)
                ->where('chrPublish', 'Y')
                ->where('chrDelete', 'N')
                ->first();
        return $user;
    }

//    public static function getRecordByModule($rec_id, $mod_id) {
//        $response = false;
//        $moduleFields = ['intFkModuleId', 'intFkRecordId', 'txtBody'];
//        $response = Self::getPowerPanelRecords($moduleFields)
//                ->deleted()
//                ->where('intFkRecordId', $rec_id)
//                ->first();
//        echo '<pre>';
//        print_r($response);
//        exit;
//        return $response;
//    }
}
