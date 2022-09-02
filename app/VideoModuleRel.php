<?php

namespace App;

use Cache;
use Illuminate\Database\Eloquent\Model;

class VideoModuleRel extends Model
{
		protected $table    = 'video_module_rel';
		protected $fillable = [
			'id',
			'varTmpId',
			'intFkVideoId',
			'intFkModuleCode',
			'intRecordId',
			'created_at',
			'updated_at'
		];
		protected static $fetchedID  = [];
		protected static $fetchedVideo = null;

		public static function getRecord($idArr = null){
			$response = false;
			if(!empty($idArr)){
				$response = VideoModuleRel::select('intFkVideoId')
				->whereIn('intFkVideoId', $idArr)
				->get();
			}
			return $response;			
		}

		public static function addRecord($data = false){
        $response = false;
        if ($data != false && !empty($data)) {
            $recordId = VideoModuleRel::insertGetId($data);
            if ($recordId > 0) {
                $response = $recordId;
            }
        }
        return $response;
    }

    public static function deleteRecord($whereConditions = null){
    		$response = false;
        if (!empty($whereConditions)) {
            $response = VideoModuleRel::where($whereConditions)->delete();
        }
        return $response;	
    }
}