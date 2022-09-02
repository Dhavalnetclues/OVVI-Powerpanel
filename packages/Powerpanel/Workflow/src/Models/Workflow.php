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

class Workflow extends Model
{

    protected $table = 'workflow';
    protected $fillable = [
        'id',
        'varTitle',
        'varActivity',
        'varAction',
        'varFrequancyNegative',
        'varFrequancyPositive',
        'varAfter',
        'varUserId',
        'varUserRoles',
        'intModuleId',
        'intCategoryId',
        'charNeedApproval',
        'chrNeedAddPermission',
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
            'varType',
            'varTitle',
            'varActivity',
            'varAction',
            'varUserRoles',
            'intModuleId',
            'charNeedApproval',
            'chrNeedAddPermission',
            'created_at',
            'chrPublish',
            'chrDelete',
        ];
        $response = Self::getPowerPanelRecords($moduleFields)
            ->deleted()
            ->filter($filterArr)
            ->groupBy('varUserRoles')
            ->get();
        return $response;
    }

    public static function getChildGrid($filterArr = false)
    {
        $id = $_REQUEST['id'];
        $role_id = Self::getRecordById($id)->varUserRoles;
        $response = false;
        $moduleFields = [
            'id',
            'varType',
            'varTitle',
            'varActivity',
            'varAction',
            'varUserRoles',
            'intModuleId',
            'charNeedApproval',
            'chrNeedAddPermission',
            'created_at',
            'chrPublish',
            'chrDelete',
        ];
        $response = Self::getPowerPanelRecords($moduleFields)
            ->deleted()
            ->filter($filterArr)
            ->where('varUserRoles', $role_id)
//                ->whereNotIn('id', [$id])
            ->get();
        return $response;
    }

    public static function getRecordCount($filterArr = false, $returnCounter = false, $modelNameSpace = false, $checkMain = false, $id = false) {
        $response = false;
        $moduleFields = [
            'id',
            'varType',
            'varTitle',
            'varActivity',
            'varAction',
            'varUserRoles',
            'intModuleId',
            'charNeedApproval',
            'chrNeedAddPermission',
            'created_at',
            'chrPublish',
            'chrDelete',
        ];
        $response = Self::getPowerPanelRecords($moduleFields)
                ->deleted();
                if ($filterArr != false) {
                    $response = $response->filter($filterArr, $returnCounter);
                }
                $response = $response->count();
        return $response;
    }

    /**
     * This method handels retrival of record by id
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getPendingWorkFlows($whereArray)
    {
        $response = false;
        $moduleFields = [
            'id',
            'varUserId',
            'charNeedApproval',
            'chrNeedAddPermission',
        ];
        $response = Self::getPowerPanelRecords($moduleFields)
            ->where($whereArray)
            ->deleted()
            ->publish()
            ->first();
        return $response;
    }

    public static function checkExists($catId, $roleId, $mid = false)
    {
        $response = null;
        $exists = Workflow::select('id')
            ->where([
                //'intCategoryId' => $catId,
                'varUserRoles' => $roleId,
                'intModuleId' => $mid,
            ])
            ->where('chrDelete', 'N')
            ->where('chrPublish', 'Y')
            ->first();
        if (!empty($exists)) {
            $response = $exists;
        }
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
            'varType',
            'varTitle',
            'varActivity',
            'varAction',
            'varFrequancyNegative',
            'varFrequancyPositive',
            'txtFrequancyPositive',
            'txtFrequancyNegative',
            'txtAfter',
            'varAfter',
            'varUserId',
            'varUserRoles',
            'intModuleId',
            'intCategoryId',
            'charNeedApproval',
            'chrNeedAddPermission',
            'chrPublish',
        ];
        $response = Self::getPowerPanelRecords($moduleFields)
            ->deleted()
            ->publish()
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
    public static function getRecordByCategoryId($id, $userRoleId = false, $moduleId = false)
    {
        $response = false;
        $moduleFields = [
            'id',
            'chrNeedAddPermission',
            'charNeedApproval',
            'varUserId',
        ];
        $response = Self::getPowerPanelRecords($moduleFields)
            ->deleted()
            ->publish();
            //->where('intCategoryId', $id);
        if (!empty($moduleId)) {
            $response = $response->where('intModuleId', $moduleId);
        }
        $response = $response->where('varUserRoles', $userRoleId)
            ->orderBy('created_at', 'desc')
            ->first();
        return $response;
    }

    /**
     * This method handels retrival of record by id
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getApprovalWorkFlows($checkFromRoles, $in = false)
    {
        $response = false;
        $moduleFields = [
            'intCategoryId',
            'varUserRoles',
        ];
        $response = Self::getPowerPanelRecords($moduleFields)
            ->where('varType', 'approvals');
        if ($in) {
            $response = $response->whereIn('varUserRoles', $checkFromRoles);
        } else {
            $response = $response->whereNotIn('varUserRoles', $checkFromRoles);
        }
        $response = $response->deleted()
            ->publish()
            ->get();
        return $response;
    }

    /**
     * This method handels retrival of record by id
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getApprovalWorkFlowsDD($checkFromRoles, $catId)
    {
        $response = false;
        $moduleFields = [
            'intCategoryId',
            'varUserRoles',
        ];
        $response = Self::getPowerPanelRecords($moduleFields)
            ->where('varUserRoles', $checkFromRoles)
            ->where('intCategoryId', $catId)
            ->where('varType', 'approvals')
            ->deleted()
            ->publish()
            ->get();
        return $response;
    }

    /**
     * This method handels retrival of record by id
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getApprovalWorkFlowsDashboard()
    {
        $response = false;
        $moduleFields = [
            'id',
            'varTitle',
            'intCategoryId',
            'varUserRoles',
            'varUserId',
            'varActivity',
            'intModuleId',
            'charNeedApproval',
            'chrNeedAddPermission',
        ];
        $roleFields = ['id', 'display_name'];
        $response = Self::getPowerPanelRecords($moduleFields, $roleFields)
            ->where('varType', 'approvals')
            ->deleted()
            ->publish()
            ->orderBy('created_at', 'desc')
            ->get();
        return $response;
    }

    public static function deleteWorkflowForRoles($ids)
    {
        Self::whereIn('varUserRoles', $ids)->update(['chrPublish' => 'N', 'chrDelete' => 'Y']);
    }

    /**
     * This method handels backend records
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public static function getPowerPanelRecords($moduleFields = false, $roleFields = false)
    {
        $data = [];
        $response = false;
        $response = self::select($moduleFields);
        if ($roleFields != false) {
            $data['roles'] = function ($query) use ($roleFields) {
                $query->select($roleFields)->deleted()->where('chrApprovalRole', 'N');
            };
        }
        if (count($data) > 0) {
            $response = $response->with($data);
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
        $response = $query->where('id', $id);
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
            $rolid = Role::getRecordBySearch($filterArr['searchFilter']);
//           echo "<pre/>";print_r($rolid);exit;
            $data = $query->whereIn('varUserRoles', $rolid);
        }
        if (!empty($filterArr['statusFilter']) && $filterArr['statusFilter'] != ' ') {
            $data = $query->where('chrPublish', $filterArr['statusFilter']);
        }
        if (!empty($filterArr['roleFilter']) && $filterArr['roleFilter'] != ' ') {
            $data = $query->where('varUserRoles', $filterArr['roleFilter']);
        }
        if (!empty($filterArr['categorieFilter']) && $filterArr['categorieFilter'] != ' ') {
            $data = $query->where('intCategoryId', $filterArr['categorieFilter']);
        }
        // if (!empty($filterArr['rangeFilter']['from']) && $filterArr['rangeFilter']['to']) {
        //         $data = $query->whereRaw('DATE(dtStartDateTime) BETWEEN "' . $filterArr['rangeFilter']['from'] . '" AND "' . $filterArr['rangeFilter']['to'] . '"');
        // }
        if (!empty($query)) {
            $response = $query;
        }
        return $response;
    }

    public function roles()
    {
        return $this->belongsTo('App\Role', 'varUserRoles', 'id');
    }

}
