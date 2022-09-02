<?php

namespace App;

use Cache;
use Illuminate\Database\Eloquent\Model;
use DB;

class DocumentModuleRel extends Model
{
		protected $table    = 'document_module_rel';
		protected $fillable = [
			'id',
			'varTmpId',
			'intFkDocumentId',
			'intFkModuleCode',
			'intRecordId',
			'created_at',
			'updated_at'
		];

		public static function getRecord($idArr = null){
			$response = false;
			if(!empty($idArr)){
				$response = DocumentModuleRel::select('intFkDocumentId')
				->whereIn('intFkDocumentId', $idArr)
				->get();
			}
			return $response;			
		}

		public static function addRecord($data = false){
				$response = false;
				if ($data != false && !empty($data)) {
						$recordId = DocumentModuleRel::insertGetId($data);
						if ($recordId > 0) {
								$response = $recordId;
						}
				}
				return $response;
		}

		public static function getRecordCheckDocUsed($idArr = null){
			$response = false;
			$usedDocs  = array();
			$usedDocsData  = array();
			if(!empty($idArr)){
				$docrelationfields = ['id','intFkDocumentId','intFkModuleCode','intRecordId'];
				$mdlFields = ['id', 'varTitle','varTableName','varModelName','varModuleClass'];
				$response = self::getRecordsforCheckDocUsed($docrelationfields,$mdlFields)
				->whereIn('intFkDocumentId', $idArr)
				->orderBy('intFkModuleCode')
				->get();
				if(!empty($response)){
					foreach($response as $reslt){
						if(!in_array($reslt->intFkDocumentId, $usedDocs)){
							$tableName = $reslt->modules->varTableName;
							if (\Schema::hasColumn($tableName, 'fkMainRecord')) {
								$recordData = DB::table($tableName)->select('*')
															->where('chrDelete','N')
															->whereRaw("(`id`=".$reslt->intRecordId." OR `fkMainRecord`=".$reslt->intRecordId.")")
															->where('fkIntDocId',$reslt->intFkDocumentId)
															->get();
								if(!empty($recordData)){
									foreach($recordData as $allrc){
										if(!in_array($reslt->intFkDocumentId, $usedDocs)){
											if($allrc->fkMainRecord != 0){
												$parentRecordData = DB::table($tableName)->select('*')->where('chrDelete','N')->where('id',$allrc->fkMainRecord)->first();
												if(!empty($parentRecordData)){
													if($reslt->intFkDocumentId == $allrc->fkIntDocId){
														$usedDocs[] = $reslt->intFkDocumentId;
														$usedDocsData[] = $reslt;	
													}
												}
											}else{
												$parentRecordData = DB::table($tableName)->select('*')->where('chrDelete','N')->where('id',$allrc->id)->where('fkIntDocId',$reslt->intFkDocumentId)->first();
												if(!empty($parentRecordData)){
													$usedDocs[] = $reslt->intFkDocumentId;
													$usedDocsData[] = $reslt;
												}
											}
										}
									}	
								}
								
							}else{
								$recordData = DB::table($tableName)->select('*')
															->where('chrDelete','N')
															->whereRaw("(`id`=".$reslt->intRecordId.")")
															->where('fkIntDocId',$reslt->intFkDocumentId)
															->first();
									if(!empty($recordData)){
										$usedDocs[] = $reslt->intFkDocumentId;
										$usedDocsData[] = $reslt;
									}
							}
						}
					}
				}
			}
			$response = $usedDocsData;
			return $response;			
		}

		public static function getRecordsforCheckDocUsed($docRelfields = false,$moduleFileds = false){
			$data = [];
			$response = false;
			$response = self::select($docRelfields);
			if ($moduleFileds != false) {
					$data['modules'] = function ($query) use ($moduleFileds) {
							$query->select($moduleFileds);
					};
			}
			if (count($data) > 0) {
					$response = $response->with($data);
			}
			return $response;
		}

		public static function deleteRecord($whereConditions = null){
				$response = false;
				if (!empty($whereConditions)) {
						$response = DocumentModuleRel::where($whereConditions)->delete();
				}
				return $response;	
		}

		/**
		 * This method handels pages relation
		 * @return  Object
		 * @since   2017-07-20
		 */
		public function modules() {
				$response = false;
				$response = $this->belongsTo('App\Modules', 'intFkModuleCode', 'id');
				return $response;
		}
}
