<?php

namespace App;

use Cache;
use Illuminate\Database\Eloquent\Model;
use DB;

class ImgModuleRel extends Model
{
		protected $table    = 'image_module_rel';
		protected $fillable = [
			'id',
			'varTmpId',
			'intFkImgId',
			'intFkModuleCode',
			'intRecordId',
			'created_at',
			'updated_at'
		];
		protected static $fetchedID  = [];
		protected static $fetchedImg = null;

		public static function getRecord($idArr = null){
			$response = false;
			if(!empty($idArr)){
				$response = ImgModuleRel::select('intFkImgId')
				->whereIn('intFkImgId', $idArr)
				->get();
			}
			return $response;			
		}

		public static function getRecordCheckImageUsed($idArr = null){
			$response = false;
			$usedimages  = array();
			$usedimagesData  = array();
			if(!empty($idArr)){
				$imagelrelationfields = ['id','intFkImgId','intFkModuleCode','intRecordId'];
				$mdlFields = ['id', 'varTitle','varTableName','varModelName','varModuleClass'];
				$response = self::getRecordsforCheckImageUsed($imagelrelationfields,$mdlFields)
				->whereIn('intFkImgId', $idArr)
				->orderBy('intFkModuleCode')
				->get();
				if(!empty($response)){
					foreach($response as $reslt){
						if(!in_array($reslt->intFkImgId, $usedimages)){
							$tableName = $reslt->modules->varTableName;
							if (\Schema::hasColumn($tableName, 'fkMainRecord')) {
								$recordData = DB::table($tableName)->select('*')
															->where('chrDelete','N')
															->whereRaw("(`id`=".$reslt->intRecordId." OR `fkMainRecord`=".$reslt->intRecordId.")")
															->where('fkIntImgId',$reslt->intFkImgId)
															->get();
								if(!empty($recordData)){
									foreach($recordData as $allrc){
										if(!in_array($reslt->intFkImgId, $usedimages)){
											if($allrc->fkMainRecord != 0){
												$parentRecordData = DB::table($tableName)->select('*')->where('chrDelete','N')->where('id',$allrc->fkMainRecord)->first();
												if(!empty($parentRecordData)){
													if($reslt->intFkImgId == $allrc->fkIntImgId){
														$usedimages[] = $reslt->intFkImgId;
														$usedimagesData[] = $reslt;	
													}
												}
											}else{
												$parentRecordData = DB::table($tableName)->select('*')->where('chrDelete','N')->where('id',$allrc->id)->where('fkIntImgId',$reslt->intFkImgId)->first();
												if(!empty($parentRecordData)){
													$usedimages[] = $reslt->intFkImgId;
													$usedimagesData[] = $reslt;
												}
											}
										}
									}	
								}
								
							}else{
								$recordData = DB::table($tableName)->select('*')
															->where('chrDelete','N')
															->whereRaw("(`id`=".$reslt->intRecordId.")")
															->where('fkIntImgId',$reslt->intFkImgId)
															->first();
									if(!empty($recordData)){
										$usedimages[] = $reslt->intFkImgId;
										$usedimagesData[] = $reslt;
									}
							}
						}
					}
				}
			}
			$response = $usedimagesData;
			return $response;			
		}

		public static function getRecordsforCheckImageUsed($imageRelfields = false,$moduleFileds = false){
			$data = [];
			$response = false;
			$response = self::select($imageRelfields);
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

		public static function addRecord($data = false){
				$response = false;
				if ($data != false && !empty($data)) {
						$recordId = ImgModuleRel::insertGetId($data);
						if ($recordId > 0) {
								$response = $recordId;
						}
				}
				return $response;
		}

		public static function deleteRecord($whereConditions = null){
				$response = false;
				if (!empty($whereConditions)) {
						$response = ImgModuleRel::where($whereConditions)->delete();
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
