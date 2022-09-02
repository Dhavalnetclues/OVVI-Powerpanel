<?php
namespace App\Helpers;
use App\VideoModuleRel;
use Validator;
use Config;

class AddVideoModelRel {
	static function sync($VideoArr, $recordId){

		$where=[];
		$where['intFkModuleCode']=Config::get('Constant.MODULE.ID');
		$where['intRecordId']=$recordId;
		VideoModuleRel::deleteRecord($where);

		foreach ($VideoArr as $videoID) {
		if(!empty($videoID)){							
			 $videoRel=[];
			 $videoRel['intFkModuleCode']=Config::get('Constant.MODULE.ID');
			 $videoRel['intRecordId']=$recordId;
			 $videoRel['intFkVideoId']= $videoID;
		 	 VideoModuleRel::addRecord($videoRel);
			}
		}		
	}
}