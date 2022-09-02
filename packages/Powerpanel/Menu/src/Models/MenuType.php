<?php

namespace Powerpanel\Menu\Models;
use Illuminate\Database\Eloquent\Model;
use DB;

class MenuType extends Model
{
	protected $table = 'menu_type';
	protected $fillable = [
		'varTitle',
		'intAliasId',
		'created_at',
		'updated_at',
		'chrPublish',
		'chrDelete'
		];


	static function getRecordById($position=false){
		$response=false;
		$menuTypeFields=['id','varTitle','chrPublish','intAliasId'];
		
		if($position!=false){
			$response = Self::getPowerPanelRecords($menuTypeFields)->checkRecordId($position)->first();
		}
		return $response;
	}

	/**
	 * This method handels retrival of blog records
	 * @return  Object
	 * @since   2016-07-24
	 * @author  NetQuick
	 */
	static function getList() {
		$response=false;
		$menuTypeFields=['id','varTitle','chrPublish'];
		$response =	Self::getPowerPanelRecords($menuTypeFields) 
										->deleted()
										->get();
		return $response;
	}

	/**
	 * This method handels retrival of news records
	 * @return  Object
	 * @since   2016-07-14
	 * @author  NetQuick
	 */		
		static function getPowerPanelRecords($menuTypeFields=false,$aliasFields=false,$moduleId=false) 
		{			
			$data=[];
			$response=false;
			$response=self::select($menuTypeFields);
			if($aliasFields!=false){
				$data['alias'] = function ($query) use ($aliasFields, $moduleId) { 
					$query->select($aliasFields)->checkModuleCode($moduleId); 
				};
			}
			if(count($data)>0){
				$response = $response->with($data);
			}			
			return $response;
		}



	/**
	* This method handels alias relation
	* @return  Object
	* @since   2016-07-14
	* @author  NetQuick
	*/
	public function alias() {
		return $this->belongsTo('App\Alias', 'intAliasId', 'id');
	}	

	/**
	 * This method handels record id scope
	 * @return  Object
	 * @since   04-08-2017
	 * @author  NetQuick
	 */
	function scopeCheckRecordId($query, $id) {
		return $query->where('id', $id);
	}
	
	/**
	* This method handels publish scope
	* @return  Object
	* @since   04-08-2017
	* @author  NetQuick
	*/
	function scopePublish($query) {
		return $query->where(['chrPublish' => 'Y']);
	}
	/**
	* This method handels delete scope
	* @return  Object
	* @since   04-08-2017
	* @author  NetQuick
	*/
	function scopeDeleted($query) {
		return $query->where(['chrDelete' => 'N']);
	}
	
	public function menu(){
		return $this->hasOne('Powerpanel\Menu\Models\Menu','id','intPosition');
	}
}
