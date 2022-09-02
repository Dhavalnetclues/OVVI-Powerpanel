<?php

namespace Powerpanel\RoleManager\Models;

use Illuminate\Database\Eloquent\Model;

class Permission_role extends Model
{
  	protected $table = 'role_has_permissions';    
    public $timestamps = false;
    
	  public static function getPermissionRole($id=null){
			$response=false;			
			$moduleFields=['permission_id', 'role_id'];
			$permissionFields=['id','name', 'display_name','intFKModuleCode'];
			$response=Self::getPowerPanelRecords($permissionFields, $moduleFields)
			->checkRoleId($id)
			->get()
			->toArray();
			return $response;
		}

		public static function checkRoleHasPermit($permission_id, $role_id){
			$response=false;			
			$moduleFields=['permission_id', 'role_id'];
			$permissionFields=['id','name', 'display_name'];
			$response=Self::getPowerPanelRecords($permissionFields, $moduleFields)
			->where('permission_id',$permission_id)
			->where('role_id',$role_id)
			->first();
			return $response;
		}

		public static function deletePermissionRole($id=null){
			$response=false;						
			$response=Self::checkRoleId($id)->delete();
			return $response;
		}

		public static function deletePermissionRoles($idArr=null){
			$response=false;						
			$response=Self::whereIn('role_id',$idArr)->delete();
			return $response;
		}

		public static function roleCan($role,$permission_id){
			$response=false;						
			$response=Self::where('role_id',$role)
					->where('permission_id',$permission_id)->first();
			return $response;
		}

   #Database Configurations========================================
    /**
		* This method get records 
		* @return  Object
		* @since   2016-08-16
		* @author  NetQuick
		*/
		static function getPowerPanelRecords($permissionFields=false, $moduleFields=false) {
			$response=false;
			$data=array();
			$response=Self::select($moduleFields);
			if($moduleFields!=false){
				$data['permissionRole'] = function ($query) use ($permissionFields) { $query->select($permissionFields); };
			}
			if(count($data)>0){
				$response = $response->with($data);
			}
			return $response;
		}


    /**
		 * This method handels role-permission relation
		 * @return  Object
		 * @since   2016-08-16
		 * @author  NetQuick
		 */
		public function permissionRole() {
				return $this->belongsTo('App\Permission', 'permission_id', 'id');
		}
		/**
		* This method get records 
		* @return  Object
		* @since   2016-08-16
		* @author  NetQuick
		*/
		static function getRecords() {
			return self::with(['permissionRole']);
		}
		/**
		 * This method handels role id scope
		 * @return  Object
		 * @since   2016-07-24
		 * @author  NetQuick
		 */
		function scopeCheckRoleId($query, $id) {
				return $query->where('role_id', $id);
		}
}
