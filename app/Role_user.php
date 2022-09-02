<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role_user extends Model
{
    protected $table = 'model_has_roles';
    protected $fillable = [
        'model_id',
        'model_type',
        'role_id',
    ];

    public $timestamps = false;

    public static function deleteUserRole($id = null)
    {
        $response = false;
        $response = Self::checkUserId($id)->delete();
        return $response;
    }

    public static function deleteUserRoles($idArr = null)
    {
        $response = false;
        $response = Self::whereIn('role_id', $idArr)->delete();
        return $response;
    }

    public static function getRecordBYModelId($id)
    {
        $response = false;
        $response = Self::where('model_id', $id)->first();

        return $response;
    }

    public static function getCountById($id = null)
    {
        $response = false;
        $response = Self::where('role_id', $id)
            ->leftJoin('users', 'model_has_roles.model_id', '=', 'users.id')
            ->where('users.chrDelete', 'N')
            ->where('users.chrPublish', 'Y')
            ->count();

        return $response;
    }

    public static function getUserRoleByUserId($id = null)
    {
        $response = false;
        $response = Self::checkUserId($id)->first();
        return $response;
    }

    /**
     * This method handels role id scope
     * @return  Object
     * @since   2016-07-24
     * @author  NetQuick
     */
    public function scopeCheckUserId($query, $id)
    {
        return $query->where('model_id', $id);
    }

    /**
     * This method handels team relation
     * @return  Object
     * @since   2017-08-01
     * @author  NetQuick
     */
    public function roles()
    {
        return $this->hasMany('App\Role', 'id', 'role_id');
    }

    public function users()
    {
        return $this->belongsTo('App\User', 'model_id', 'id');
    }

}
