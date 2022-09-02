<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Video extends Model
{	

	protected $table = 'video';
	protected $fillable = [
			'id',
			'fkIntUserId',
			'varVideoName',
			'varVideoExtension',
			'chrIsUserUploaded',
			'youtubeId',
			'chrIsUserUploaded',
			'varMediaVideoUrlType',
			'varExtVideoUrl',
		  'vimeoId',
			'chrPublish',
			'chrDelete',
			'created_at'
		];

	public function show(){
		return $this->hasOne('App\Show','id','fkIntVideoId');
	}

	public function banners(){
		return $this->hasOne('Powerpanel\Banner\Models\Banner','id','fkIntVideoId');
	}

	/**
	 * This method handels retrival of show records
	 * @return  Object
	 * @since   2016-07-14
	 * @author  NetQuick
	 */
	static function getRecords() 
	{
		return Self::select([
			'id',
		  'fkIntUserId',
		  'varVideoName',
		  'txtVideoOriginalName',
		  'varVideoExtension',
		  'varVideoExtension',
		  'youtubeId',
		  'varMediaVideoUrlType',
		  'varExtVideoUrl',
		  'vimeoId',
		  'chrPublish',
		  'chrDelete'
		])->with([]);
	}
	/**
	 * This method handels record id scope
	 * @return  Object
	 * @since   2016-07-24
	 * @author  NetQuick
	 */
	function scopeCheckRecordId($query, $id) {
		return $query->where('id', $id);
	}

	/**
	 * This method handels publish scope
	 * @return  Object
	 * @since   2016-07-14
	 * @author  NetQuick
	 */
	function scopePublish($query) {
		return $query->where(['chrPublish' => 'Y']);
	}
	/**
	 * This method handels delete scope
	 * @return  Object
	 * @since   2016-07-14
	 * @author  NetQuick
	 */
	function scopeDeleted($query) {
		return $query->where(['chrDelete' => 'N']);
	}	

	/**
	 * This method handels delete scope
	 * @return  Object
	 * @since   2016-07-14
	 * @author  NetQuick
	 */
	function scopeDeletedYes($query) {
		return $query->where(['chrDelete' => 'Y']);
	}	

	 /**
	 * This method handle order by query
	 * @return  Object
	 * @since   2017-08-02
	 * @author  NetQuick
	 */
  function scopeOrderByDesc($query) {
      return $query->orderBy('id','DESC');
  }

  /**
	 * This method handels retrival of page title by page id
	 * @return  Object
	 * @since   2017-10-16
	 * @author  NetQuick
	 */
		public static function getVideoData($id=false){
	 	  $response=false;
			$moduleFields=[
				'id',
			  'varVideoName',
			  'txtVideoOriginalName',
			  'varVideoExtension',
			  'youtubeId',
			  'varMediaVideoUrlType',
			  'varExtVideoUrl',
		  	'vimeoId',
			];
			$response = Self::select($moduleFields)
			->deleted()
			->whereIn('id',$id)
			->get();

			return $response;
		}


	public static function getVideoTitleById($id=false){
		$response=false;
		$moduleFields=['id','varVideoName','varVideoExtension'];
		$response = Self::getPowerPanelRecords($moduleFields)
		->deleted()
		->checkRecordId($id)
		->first();
		return $response;
	}

/**
	 * This method handels retrival of blog records
	 * @return  Object
	 * @since   2016-07-14
	 * @author  NetQuick
	 */
	public static function getPowerPanelRecords($moduleFields = false)
	{
			$data    = [];
			$response = false;
			$response = Self::select($moduleFields);
			if (count($data) > 0) {
					$response = $response->with($data);
			}
			return $response;
	}

	public static function getTrashedVideos()
	{
		$response=false;
		$videoFields =[
			'id', 
		  'varVideoName', 
		  'varVideoExtension',
		  'txtVideoOriginalName',
		  'varMediaVideoUrlType',
		  'varExtVideoUrl',
		  'vimeoId',
		];
		$fetchedVideo  = Self::select($videoFields)
						  		->deletedYes()
						  		->take(15)
						  		->get();

		$response = $fetchedVideo;	
		return $response;
	}

	public static function getAllTrashedVideosIds()
  {
      $response    = false;
      $videoFields =[
      	'id', 
        'varVideoName', 
        'varVideoExtension',
        'txtVideoOriginalName',
        'varMediaVideoUrlType',
        'varExtVideoUrl',
		  	'vimeoId',
      ];
      $fetchedVideo  = Self::select($videoFields)
          ->deletedYes()
          ->get();
      $response = $fetchedVideo;
      return $response;
  }

}