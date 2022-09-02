<?php
namespace App\Helpers;
use App\DocumentModuleRel;
use Validator;
use Config;

class AddDocumentModelRel {
	static function sync($documentArr, $recordId,$approval = false){

		$where=[];
		$where['intFkModuleCode']=Config::get('Constant.MODULE.ID');
		if($approval){
			$where['intRecordId']=$approval;
		}else{
			$where['intRecordId']=$recordId;	
		}
		DocumentModuleRel::deleteRecord($where);

		foreach ($documentArr as $documentID) {
			if(!empty($documentID)){
				$documentRel=[];
				$documentRel['intFkModuleCode']=Config::get('Constant.MODULE.ID');
				$documentRel['intFkDocumentId']=$documentID;
				if($approval){
					$documentRel['intRecordId']=$approval;
				}else{
					$documentRel['intRecordId']=$recordId;
				}
				DocumentModuleRel::addRecord($documentRel);
			}			
		}		
	}
}