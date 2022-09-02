<?php

namespace App;

use Cache;
use Illuminate\Database\Eloquent\Model;
use DB;

class AudioModuleRel extends Model
{
		protected $table    = 'audio_module_rel';
		protected $fillable = [
			'id',
			'varTmpId',
			'intFkAudioId',
			'intFkModuleCode',
			'intRecordId',
			'created_at',
			'updated_at'
		];

		public static function getRecord($idArr = null){
			$response = false;
			if(!empty($idArr)){
				$response = AudioModuleRel::select('intFkAudioId')
				->whereIn('intFkAudioId', $idArr)
				->get();
			}
			return $response;			
		}

		public static function addRecord($data = false){
				$response = false;
				if ($data != false && !empty($data)) {
						$recordId = AudioModuleRel::insertGetId($data);
						if ($recordId > 0) {
								$response = $recordId;
						}
				}
				return $response;
		}

		public static function getRecordCheckAudioUsed($idArr = null){
			$response = false;
			$usedAudios  = array();
			$usedAudioData  = array();
			if(!empty($idArr)){
                            
				$audiorelationfields = ['id','intFkAudioId','intFkModuleCode','intRecordId'];
				$mdlFields = ['id', 'varTitle','varTableName','varModelName','varModuleClass'];
				$response = self::getRecordsforCheckAudioUsed($audiorelationfields,$mdlFields)
				->whereIn('intFkAudioId', $idArr)
				->orderBy('intFkModuleCode')
				->get();
				if(!empty($response)){
					foreach($response as $reslt){
						if(!in_array($reslt->intFkAudioId, $usedAudio)){
							$tableName = $reslt->modules->varTableName;
							if (\Schema::hasColumn($tableName, 'fkMainRecord')) {
								$recordData = DB::table($tableName)->select('*')
															->where('chrDelete','N')
															->whereRaw("(`id`=".$reslt->intRecordId." OR `fkMainRecord`=".$reslt->intRecordId.")")
															->where('fkIntAudioId',$reslt->intFkAudioId)
															->get();
								if(!empty($recordData)){
									foreach($recordData as $allrc){
										if(!in_array($reslt->intFkAudioId, $usedAudio)){
											if($allrc->fkMainRecord != 0){
												$parentRecordData = DB::table($tableName)->select('*')->where('chrDelete','N')->where('id',$allrc->fkMainRecord)->first();
												if(!empty($parentRecordData)){
													if($reslt->intFkAudioId == $allrc->fkIntAudioId){
														$usedAudio[] = $reslt->intFkAudioId;
														$usedAudioData[] = $reslt;	
													}
												}
											}else{
												$parentRecordData = DB::table($tableName)->select('*')->where('chrDelete','N')->where('id',$allrc->id)->where('fkIntAudioId',$reslt->intFkAudioId)->first();
												if(!empty($parentRecordData)){
													$usedAudio[] = $reslt->intFkAudioId;
													$usedAudioData[] = $reslt;
												}
											}
										}
									}	
								}
								
							}else{
								$recordData = DB::table($tableName)->select('*')
															->where('chrDelete','N')
															->whereRaw("(`id`=".$reslt->intRecordId.")")
															->where('fkIntAudioId',$reslt->intFkAudioId)
															->first();
									if(!empty($recordData)){
										$usedAudio[] = $reslt->intFkAudioId;
										$usedAudioData[] = $reslt;
									}
							}
						}
					}
				}
			}
			$response = $usedAudioData;
			return $response;			
		}

		public static function getRecordsforCheckAudioUsed($audioRelfields = false,$moduleFileds = false){
			$data = [];
			$response = false;
			$response = self::select($audioRelfields);
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
						$response = AudioModuleRel::where($whereConditions)->delete();
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
