<?php
/**
 * The ModuleGroup class handels ModuleGroup model queries
 * ORM implemetation.
 * @package   Netquick powerpanel
 * @license   http://www.opensource.org/licenses/BSD-3-Clause
 * @version   1.00
 * @since   	2018-09-12
 * @author    NetQuick
 */
namespace App;
use Illuminate\Database\Eloquent\Model;
use Cache;
use DB;
class ModuleGroup extends Model {
		protected $table = 'module_group';
		protected $fillable = [
			'id',
			'varTitle',
			'chrPublish',
			'chrDelete'
		];

		public static function getGroupById($id){
			$response = false;
			$data=Self::select('id', 'varTitle')
			->where('id',$id)
			->first();
			if(!empty($data)){
				$response=$data;
			}
			return $response;
		}
}
