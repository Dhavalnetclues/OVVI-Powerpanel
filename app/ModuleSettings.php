<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Cache;
use DB;

class ModuleSettings extends Model
{
		/**
		 * The attributes that are mass assignable.
		 *
		 * @var array
		 */
		protected $table    = 'module_setting';
		protected $fillable = [
			'id',
			'intModuleId',			
			'txtSettings'
		];

		/**
		 * This method handels retrival of records
		 * @return  Object
		 * @since   2018-01-03
		 * @author  NetQuick
		 */
		public static function getSettings($id=false){
			$response = false;
			if($id){
				$filedArr = [ 'id', 'intModuleId', 'txtSettings' ];
				$response = Self::select($filedArr)->checkModuleId($id)->first();
			}
			return $response;
		}		

		/**
		* This method handels record id scope
		* @return  Object
		* @since   2016-07-24
		* @author  NetQuick
		*/
		function scopeCheckModuleId($query, $id) {
			return $query->where('intModuleId', $id);
		}
}
