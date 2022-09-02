<?php

namespace App;

use Auth;
use DB;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{

    protected $table = 'roles';
    protected $fillable = ['id', 'chrIsAdmin', 'name', 'varSector', 'display_name', 'description', 'chrApprovalRole', 'created_at', 'updated_at', 'chr_delete', 'chr_publish'];

    /**
     * This method handels retrival of record count
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getRecordList($filterArr = false, $userIsAdmin = false, $currentUserRoleData = false)
    {
        $response = false;
        $moduleFields = [
            'id',
            'chrIsAdmin',
            'name',
            'varSector',
            'display_name',
            'description',
            'created_at',
            'updated_at',
        ];
        $response = Self::getPowerPanelRecords($moduleFields)
            ->deleted();
        //if(!Auth::user()->hasRole('netquick_admin')){
        //$response = $response->where('chrIsAdmin','N');
        //}
        $response = $response->filter($filterArr);
        if ($userIsAdmin && $currentUserRoleData->name != "netquick_admin") {
            if ($currentUserRoleData->id != 1 || $currentUserRoleData->id != 2) {
                $response = $response->where('name', "!=", 'netquick_admin');

                $response = $response->where('id', "!=", $currentUserRoleData->id);
            }
        }
        $response = $response->get();
        return $response;
    }

    /**
     * This method handels retrival of record count
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getRecordListCount($filterArr = false, $returnCounter = false, $userIsAdmin = false, $currentUserRoleData = false)
    {
        $response = false;
        $moduleFields = [
            'id',
            'chrIsAdmin',
            'name',
            'varSector',
            'display_name',
            'description',
        ];
        $response = Self::getPowerPanelRecords($moduleFields)
            ->deleted();
        //if(!Auth::user()->hasRole('netquick_admin')){
        //$response = $response->where('chrIsAdmin','N');
        //}
        $response = $response->filter($filterArr, $returnCounter);
        if ($userIsAdmin && $currentUserRoleData->name != "netquick_admin") {
            if ($currentUserRoleData->id != 1 || $currentUserRoleData->id != 2) {
                $response = $response->where('name', "!=", 'netquick_admin');

                $response = $response->where('id', "!=", $currentUserRoleData->id);
            }
        }
        $response = $response->count();
        return $response;
    }

    /**
     * This method handels retrival of record count
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getRecordListing($filterArr = false)
    {
        $response = false;
        $moduleFields = [
            'id',
            'display_name',
            'varSector',
        ];
        $response = Self::getPowerPanelRecords($moduleFields)
            ->deleted()
            ->whereNotIn('id', [1])
            ->orderBy('name', 'asc')
            ->pluck('display_name', 'id');
        return $response;
    }

    /**
     * This method handels retrival of record count
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getPendingRoleWF($filterArr = false)
    {
        $response = false;
        $moduleFields = [
            'id',
            'display_name',
            'varSector',
        ];
        $response = Self::getPowerPanelRecords($moduleFields)
            ->deleted()
            ->checkIsNotApproval()
            ->where('chrIsAdmin', 'N')
            ->whereNotIn('id', $filterArr)
            ->get();
        return $response;
    }

    /**
     * This method handels retrival of record
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getRecordById($id = false)
    {
        $response = false;
        $moduleFields = [
            'id',
            'name',
            'chrIsAdmin',
            'display_name',
            'description',
            'varSector',
        ];
        $pageFields = ['id'];
        $response = Self::getPowerPanelRecords($moduleFields)
            ->deleted()
            ->checkRecordId($id)
            ->first();
        return $response;
    }

    public static function getRecordBySearch($search = false)
    {
        $response = false;
        $moduleFields = [
            'id',
        ];
        $pageFields = ['id'];
        $response = Self::select("id")->where('display_name', 'like', "%" . $search . "%")->pluck('id');
        return $response;
    }

    /**
     * This method handels update record
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function updateRecord($id = false, $data = false)
    {
        $response = false;
        $response = Self::where('id', $id)->update($data);
        return $response;
    }

    public static function deleteRoles($idArr = null)
    {
        $response = false;
        $response = Self::whereIn('id', $idArr)->delete();
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
            'name',
            'chrIsAdmin',
            'display_name',
            'varSector',
            'description'];
        $response = Self::getPowerPanelRecords($moduleFields)->deleted()->checkRecordId($id)->first();
        return $response;
    }

    #Database Configurations========================================
    /**
     * This method handels retrival of front end records
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */

    public static function getFrontRecords($moduleFields = false)
    {
        $response = false;
        $response = self::select($moduleFields);
        return $response;
    }

    /**
     * This method handels retrival of record
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getAdmins($idArr = false)
    {
        $response = false;
        $moduleFields['moduleFields'] = ['id'];
        $moduleFields['roleUserFields'] = ['user_id', 'role_id'];
        $moduleFields['userFields'] = ['id', 'name', 'varSector'];
        $response = Self::getPowerPanelRecords($moduleFields)
            ->deleted()
            ->checkRecordNotIn($idArr)
            ->where('chrIsAdmin', 'Y')
            ->get();
        return $response;
    }

    /**
     * This method handels retrival of record
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getNonAdmins()
    {
        $response = false;
        $moduleFields['moduleFields'] = ['id', 'display_name', 'varSector'];
        $response = Self::getPowerPanelRecords($moduleFields)
            ->deleted()
            ->where('chrIsAdmin', 'N')
            ->get();
        return $response;
    }

    /**
     * This method handels retrival of backednd records
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public static function getPowerPanelRecords($moduleFields = false)
    {
        $data = [];
        $response = false;
        $response = Self::select(isset($moduleFields['moduleFields']) ? $moduleFields['moduleFields'] : $moduleFields);
        if (isset($moduleFields['roleUserFields'])) {
            $data['roleuser'] = function ($query) use ($moduleFields) {
                $roleUsers = array();
                if (isset($moduleFields['userFields'])) {
                    $roleUsers['users'] = function ($query) use ($moduleFields) {
                        $query->select($moduleFields['userFields']);
                    };
                    $query->select($moduleFields['roleUserFields'])->with($roleUsers);
                }
            };
        }
        if (count($data) > 0) {
            $response = $response->with($data);
        }
        return $response->checkIsNotApproval();
    }

    /**
     * This method get records
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public static function getRecords($searchVal = '')
    {
        return self::with([]);
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
     * This method handels record id scope
     * @return  Object
     * @since   2016-07-24
     * @author  NetQuick
     */
    public function scopeCheckRecordNotIn($query, $idArr)
    {
        return $query->whereNotIn('id', $idArr);
    }

    /**
     * This method handels record id scope
     * @return  Object
     * @since   2016-07-24
     * @author  NetQuick
     */
    public function scopeCheckIsNotApproval($query)
    {
        return $query->where('chrApprovalRole', 'N');
    }

    /**
     * This method handels delete scope
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public function scopeDeleted($query)
    {
        return $query->where(['chr_delete' => 'N']);
    }

    public function scopeFilter($query, $filterArr = false, $retunTotalRecords = false)
    {
        $response = null;
        if (!empty($filterArr['orderByFieldName']) && !empty($filterArr['orderTypeAscOrDesc'])) {
            $query = $query->orderBy($filterArr['orderByFieldName'], $filterArr['orderTypeAscOrDesc']);
        } else {
            $query = $query->orderBy('id', 'DESC');
        }
        if (!$retunTotalRecords) {
            if (!empty($filterArr['iDisplayLength']) && $filterArr['iDisplayLength'] > 0) {
                $data = $query->skip($filterArr['iDisplayStart'])->take($filterArr['iDisplayLength']);
            }
        }
        if (!empty($filterArr['searchFilter']) && $filterArr['searchFilter'] != ' ') {
            $data = $query->where('name', 'like', "%" . $filterArr['searchFilter'] . "%");
            $data = $query->orWhere('display_name', 'like', "%" . $filterArr['searchFilter'] . "%");
        }
        if (!empty($query)) {
            $response = $query;
        }
        return $response;
    }

    public function roleuser()
    {
        return $this->hasMany('App\Role_user', 'role_id', 'id');
    }

    public static function GetRoleTitle($id)
    {
        $pagedata = DB::table('role_user')
            ->select('*')
            ->where('user_id', '=', $id)
            ->first();
//        print_r($pagedata);exit;
        if (isset($pagedata->role_id)) {
            $data = DB::table('roles')
                ->select('*')
                ->where('id', '=', $pagedata->role_id)
                ->first();
            return $data->display_name;
        } else {
            return '';
        }

    }

}
