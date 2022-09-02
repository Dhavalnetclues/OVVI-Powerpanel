<?php
use Illuminate\Database\Seeder;
class PermissionTableSeeder extends Seeder {
	/**
	* Run the database seeds.
	*
	* @return void
	*/
	public function run() {
		$modules = DB::table('module')->select('id','varModuleName','varTitle','varPermissions')
						->orderBy('varTitle')
						->groupBy('varModuleName')
						->groupBy('varTitle')
						->groupBy('varPermissions')
						->groupBy('id')
						->get();
		$permission = [];
			foreach ($modules as $module) {
				$permissions = [];				
				foreach (explode(',', $module->varPermissions) as $permissionName) {

					$permissionName=trim($permissionName);
					$Icon = $permissionName;
					if($permissionName=='list'){
						$Icon = 'per_list';	
					}elseif ($permissionName == 'create') {
						$Icon = 'per_add';							
					}elseif ($permissionName == 'edit') {
						$Icon = 'per_edit';							
					}elseif ($permissionName == 'delete') {
						$Icon = 'per_delete';							
					}elseif ($permissionName == 'publish') {
						$Icon = 'per_publish';
					}elseif ($permissionName == 'reviewchanges') {
						$Icon = 'per_reviewchanges';
					}elseif($permissionName == 'change-password'){
						$Icon = 'change-password';
					}elseif($permissionName == 'general-setting-management'){
						$Icon =	'setting_link';
					}elseif($permissionName == 'smtp-mail-setting'){
						$Icon =	'mail_setting';
					}elseif($permissionName == 'seo-setting'){
						$Icon =	'seo_setting';
					}elseif($permissionName == 'social-setting'){
						$Icon =	'social_setting';
					}elseif($permissionName == 'social-media-share-setting'){
						$Icon =	'share_setting';
					}elseif($permissionName == 'other-setting'){
						$Icon =	'other_setting';
					}elseif($permissionName == 'maintenance-setting'){
						$Icon =	'setting';
					}elseif($permissionName == 'module-setting'){
						$Icon =	'module_setting';
					}elseif($permissionName == 'recent-activities'){
						$Icon =	'recent_activities';
					}					

					array_push($permissions, [
						'name' => $module->varModuleName.'-'.$permissionName,
						'display_name' => $Icon,
						'description' => ucwords($permissionName).' Permission',
						'intFKModuleCode'=> $module->id
					]);
				}
				array_push($permission, $permissions);
		}
		
		//$modules = DB::table('permissions')->truncate();		
		foreach ($permission as $key => $value) {
			DB::table('permissions')->insert($value);
		}
	}
}