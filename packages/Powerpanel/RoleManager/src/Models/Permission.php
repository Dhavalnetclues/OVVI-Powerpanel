<?php
namespace Powerpanel\RoleManager\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{

    public static function getPermissions($grpId = false)
    {
        $response = false;
        $permissionFields = ['id', 'name', 'display_name', 'description', 'intFKModuleCode'];
        $moduleFields = ['id', 'intFkGroupCode', 'varTitle'];
        $moduleGroupFields = ['id', 'varTitle', 'intDisplayOrder'];

        $fieldsArr = [
            'permissionFields' => $permissionFields,
            'moduleFields' => $moduleFields,
            'moduleGroupFields' => $moduleGroupFields,
        ];

        $response = Self::getPowerPanelRecords($fieldsArr, $grpId)
            ->orderby('display_name')
            ->get()
            ->toArray();
        return $response;
    }

    public static function getPermitByName($name)
    {
        $response = false;
        $response = Self::select('id')->where('name', $name)->first();
        return $response;
    }

    /**
     * This method get records
     * @return  Object
     * @since   2016-08-16
     * @author  NetQuick
     */
    public static function getPowerPanelRecords($fieldsArr = false, $grpId = false)
    {
        $response = false;
        $response = Self::select($fieldsArr['permissionFields']);
        if ($fieldsArr['moduleFields'] != false) {
            $data['modules'] = function ($query) use ($fieldsArr, $grpId) {
                $moduleGoupArr = array();
                if (isset($fieldsArr['moduleGroupFields'])) {
                    $moduleGoupArr['group'] = function ($query) use ($fieldsArr, $grpId) {
                        if ($grpId) {
                            $query->select($fieldsArr['moduleGroupFields'])->where('id', $grpId);
                        } else {
                            $query->select($fieldsArr['moduleGroupFields']);
                        }
                    };

                    if ($grpId) {
                        $query->select($fieldsArr['moduleFields'])->with($moduleGoupArr)
                            ->where('intFkGroupCode', $grpId)
                            ->where('chrPublish', 'Y')
                            ->where('chrDelete', 'N');
                    } else {
                        $query->select($fieldsArr['moduleFields'])->with($moduleGoupArr)
                            ->where('chrPublish', 'Y')
                            ->where('chrDelete', 'N');
                    }
                }
            };
        }
        if (count($data) > 0) {
            $response = $response->with($data);
        }
        return $response;
    }

    /**
     * This method handels module relation
     * @return  Object
     * @since   2016-07-24
     * @author  NetQuick
     */
    public function modules()
    {
        return $this->belongsTo('App\Modules', 'intFKModuleCode', 'id');
    }

    public function permissionRole()
    {
        return $this->hasOne('App\Permission_role', 'id', 'permission_id');
    }
}
