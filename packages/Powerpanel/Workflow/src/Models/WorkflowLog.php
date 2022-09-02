<?php
/**
 * The Workflow class handels Workflow model queries
 * ORM implemetation.
 * @package   Netquick powerpanel
 * @license   http://www.opensource.org/licenses/BSD-3-Clause
 * @version   1.00
 * @since       2018-09-12
 * @author    NetQuick
 */
namespace Powerpanel\Workflow\Models;

use Illuminate\Database\Eloquent\Model;

class WorkflowLog extends Model
{
    protected $table = 'workflow_log';
    protected $fillable = [
        'fkModuleId',
        'fkRecordId',
        'dtYes',
        'dtNo',
        'chrAfterSent',
        'dtYesSent',
        'dtNoSent',
        'chrPublish',
        'chrDelete',
        'created_at',
        'updated_at',
    ];

    /**
     * This method handels retrival of backend record list
     * @return  Object
     * @since   2017-10-24
     * @author  NetQuick
     */
    public static function getRecordList($filterArr = false)
    {
        $response = false;
        $moduleFields = [
            'id',
            'varTitle',
            'varActivity',
            'varAction',
            'chrPublish',
            'chrDelete',
        ];
        $response = Self::getPowerPanelRecords($moduleFields)
            ->deleted()
            ->filter($filterArr)
            ->get();
        return $response;
    }

    /**
     * This method handels retrival of backend record list
     * @return  Object
     * @since   2017-10-24
     * @author  NetQuick
     */
    public static function getApprovalListDashbord()
    {
        $response = false;
        $moduleFields = [
            'id',
            'fkModuleId',
            'fkRecordId',
            'created_at',
        ];

        
        $fields = [
            'id',
            'varModuleName',
            'varTitle',
            'varModuleNameSpace',
        ];

        $response = Self::getPowerPanelRecords($moduleFields, $fields)
            ->deleted()
            ->where('dtYes', '=', null)
            ->where('dtNo', '=', null)
            ->where('charApproval', 'Y')
            ->whereNull('dtYes')
            ->whereNull('dtNo')
            ->groupBy('fkModuleId')
            ->orderBy('created_at', 'desc')
            ->get();
        return $response;
    }

    /**
     * This method handels retrival of record by id
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getRecordById($id)
    {
        $response = false;
        $moduleFields = [
            'id',
            'varTitle',
            'varActivity',
            'varAction',
            'varFrequancyNegative',
            'varFrequancyPositive',
            'varAfter',
            'chrPublish',
        ];
        $response = Self::getPowerPanelRecords($moduleFields)
            ->deleted()
            ->checkRecordId($id)
            ->first();
        return $response;
    }

    /**
     * This method handels retrival of record by id
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getRecordWhere($whereCondArr)
    {
        $response = false;
        $moduleFields = [
            'id',
            'fkModuleId',
            'fkRecordId',
            'dtYes',
            'dtNo',
            'chrAfterSent',
            'dtYesSent',
            'dtNoSent',
            'chrPublish',
        ];
        $response = Self::getPowerPanelRecords($moduleFields)
            ->where($whereCondArr)
            ->first();
        return $response;
    }

    /**
     * This method handels retrival of record by id
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getRecordsWhere($whereCondArr)
    {
        $response = false;
        $moduleFields = [
            'id',
            'fkModuleId',
            'fkRecordId',
            'dtYes',
            'dtNo',
            'chrAfterSent',
            'dtYesSent',
            'dtNoSent',
            'chrPublish',
        ];
        $response = Self::getPowerPanelRecords($moduleFields)
            ->where($whereCondArr)
            ->get();
        return $response;
    }

    /**
     * This method handels backend records
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public static function getPowerPanelRecords($moduleFields = false, $fields = false)
    {
        $data = [];
        $response = false;
        $response = self::select($moduleFields)->with(['module']);
        
        if ($fields != false) {
            $data['module'] = function ($query) use ($fields) {
                $query->select($fields);
            };
        }

        if (count($data) > 0) {
            $response = $response->with($data);
        }
        return $response;
    }

    /**
     * This method handels insert of event record
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public static function addRecord($data = false)
    {
        $response = false;
        $recordId = Self::insertGetId($data);
        if ($recordId > 0) {
            $response = $recordId;
        }
        return $response;
    }

    /**
     * This method handels update of log record
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public static function updateRecord($data = false, $whereCondArr = false)
    {
        $response = false;
        if ($data && $whereCondArr) {
            $checkNullArray = [];
            foreach ($whereCondArr as $key => $value) {
                if ($value == 'null') {
                    array_push($checkNullArray, $key);
                    unset($whereCondArr[$key]);
                }
            }
            $update = Self::where($whereCondArr);
            if (!empty($checkNullArray)) {
                foreach ($checkNullArray as $nullvalue) {
                    $update = $update->whereNull($nullvalue);
                }
            }

            $update = $update->update($data);

            if ($update) {
                $response = $update;
            }
        }
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
        $response = false;
        $response = $query->where('fkRecordId', $id);
        return $response;
    }

    /**
     * This method handels record id scope
     * @return  Object
     * @since   2016-07-24
     * @author  NetQuick
     */
    public function scopeCheckModuleId($query, $id)
    {
        $response = false;
        $response = $query->where('fkModuleId', $id);
        return $response;
    }

    /**
     * This method handels publish scope
     * @return  Object
     * @since   2016-07-20
     * @author  NetQuick
     */
    public function scopePublish($query)
    {
        $response = false;
        $response = $query->where(['chrPublish' => 'Y']);
        return $response;
    }

    /**
     * This method handels delete scope
     * @return  Object
     * @since   2016-07-20
     * @author  NetQuick
     */
    public function scopeDeleted($query)
    {
        $response = false;
        $response = $query->where(['chrDelete' => 'N']);
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
        if (!empty($filterArr['searchFilter']) && $filterArr['searchFilter'] != ' ') {
            $data = $query->where('varTitle', 'like', "%" . $filterArr['searchFilter'] . "%");
        }
        if (!empty($filterArr['statusFilter']) && $filterArr['statusFilter'] != ' ') {
            $data = $query->where('chrPublish', $filterArr['statusFilter']);
        }
        // if (!empty($filterArr['rangeFilter']['from']) && $filterArr['rangeFilter']['to']) {
        //         $data = $query->whereRaw('DATE(dtStartDateTime) BETWEEN "' . $filterArr['rangeFilter']['from'] . '" AND "' . $filterArr['rangeFilter']['to'] . '"');
        // }
        if (!empty($query)) {
            $response = $query;
        }
        return $response;
    }

    public function module()
    {
        return $this->hasOne('App\Modules', 'id', 'fkModuleId');
    }
}
