<?php
namespace App\Helpers;
use App\ImgModuleRel;
use Validator;
use Config;

class AddImageModelRel {
	static function sync($imgArr, $recordId,$approval = false,$imgControlName = "default"){
		$imgControlName = (isset($imgControlName) && $imgControlName !="") ? $imgControlName : "default";
		$where=[];
		$where['intFkModuleCode']=Config::get('Constant.MODULE.ID');
		if($approval){
			$where['intRecordId']=$approval;
		}else{
			$where['intRecordId']=$recordId;	
		}
		$where['varImgControlName'] = $imgControlName;
		ImgModuleRel::deleteRecord($where);

		foreach ($imgArr as $imageID) {
			if(!empty($imageID)){
				$imageRel=[];
				$imageRel['intFkModuleCode']=Config::get('Constant.MODULE.ID');
				$imageRel['intFkImgId']= $imageID;
				if($approval){
					$imageRel['intRecordId']=$approval;
				}else{
					$imageRel['intRecordId']=$recordId;
				}
				$imageRel['varImgControlName'] = $imgControlName;
				ImgModuleRel::addRecord($imageRel);
			}
		}		
	}
}