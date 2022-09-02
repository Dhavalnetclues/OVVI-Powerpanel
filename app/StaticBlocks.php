<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class StaticBlocks extends Model
{

		protected $table    = 'static_block';
		protected $fillable = [
				'varTitle',
				'intAliasId',
				'intChildMenu',
				'fkIntImgId',
				'fkIntVideoId',
				'varExternalLink',
				'txtDescription',
				'chrDelete',
				'chrPublish',
				'created_at',
				'updated_at',
		];
		/**
		 * This method handels retrival of show records
		 * @return  Object
		 * @since   2016-07-14
		 * @author  NetQuick
		 */
		public static function getRecords()
		{
				return self::with(['alias' => function ($query) {
						$query->checkModuleCode();
				}]);
		}

		public static function getStaticBlockList($id)
		{
				$response          = false;
				$staticBlockFields = ['id','varTitle'];

				$response = Self::select($staticBlockFields)
											->deleted()
											->publish()
											->where('intAliasId',$id)
											->first();

				return $response;
		}

		public static function getStaticBlockId($id,$onlyParent= false,$withParent = false)
		{

				$response          = false;
				$staticBlockFields = ['id','varTitle','fkIntImgId','varExternalLink','txtDescription'];
				if (empty($response)) 
				{
						$response = Self::select($staticBlockFields)
												->deleted()
												->publish();
						if($onlyParent){
							$response =	$response->where("id", $id);
						}else{
							$response =	$response->where("intChildMenu", $id);
						}

						if($withParent){
							$response = $response->orWhere("id", $id);
						}
						$response = $response->get();
				}

				return $response;
		}

		public static function getParentStaticBlockId($id)
		{
				$response          = false;
				$staticBlockFields = ['id','varTitle','fkIntImgId','varExternalLink','txtDescription'];
				if (empty($response)) 
				{
						$response = Self::select($staticBlockFields)
												->deleted()
												->publish()
												->where("id", $id)
												->get();
				}
				return $response;
		}
		#welcome section code here
		public static function getStaticBlockIdRecord($id)
		 {
				$response          = false;
				$staticBlockFields = ['id','varTitle','fkIntImgId','varExternalLink','txtDescription'];
				if (empty($response)) 
				{
						$response = Self::select($staticBlockFields)
																->deleted()
																->publish()
																->where("id", $id)
																->get();
				}
				return $response;
		}

		public static function getFrontRecords($staticBlockFields = false,$aliasFields = false)
		{
				$response = false;
				$data     = [
						'alias' => function ($query) use ($aliasFields) 
						{
								$query->select($aliasFields)->checkRecordId();
						},
				];
				$response = self::select($staticBlockFields)->with($data);
				return $response;
		}
		/**
		 * This method handels backend records
		 * @return  Object
		 * @since   2016-07-14
		 * @author  NetQuick
		 */
		public static function getPowerPanelRecords($moduleFields = false, $aliasFields = false)
		{
				$data     = [];
				$response = false;
				$response = self::select($moduleFields);
				if ($aliasFields != false) {
						$data['alias'] = function ($query) use ($aliasFields) {
								$query->select($aliasFields)->checkModuleCode();
						};
				}
				if (count($data) > 0) {
						$response = $response->with($data);
				}
				return $response;
		}

		public static function get_description_by_alias($aliasId = false)
		{
				$response           = false;
				$statickBlockFields = ['id', 'intAliasId', 'txtDescription', 'intChildMenu', 'fkIntImgId', 'fkIntVideoId', 'varExternalLink'];

				$response = Self::Select($statickBlockFields)
						->where('intAliasId', $aliasId)
						->deleted()
						->publish()
						->first();
				return $response;
		}

		/**
		 * This method handels retrival of backend record list
		 * @return  Object
		 * @since   2017-10-24
		 * @author  NetQuick
		 */
		public static function getRecordList($filterArr = false)
		{

				$response     = false;
				$moduleFields = ['id', 'varTitle', 'intAliasId', 'txtDescription', 'created_at', 'chrPublish', 'intChildMenu'];
				$aliasFields  = ['id', 'varAlias'];
				$response     = Self::getPowerPanelRecords($moduleFields, $aliasFields)
				->deleted()
				->filter($filterArr);
				if(empty($filterArr['searchFilter'])){
					$response = $response->where('intChildMenu', '=', null);
				}
				$response = $response->get();
				return $response;
		}
		public static function getRecordChildListGrid()
		{
				$response     = false;
				$moduleFields = ['intChildMenu'];
				$response     = Self::getPowerPanelRecords($moduleFields)
						->deleted()
						->where('intChildMenu', '!=', null)
						->get();
				return $response;
		}

		public static function getRecordChildList($id = false)
		{
				$response     = false;
				$moduleFields = ['id', 'varTitle', 'intChildMenu', 'txtDescription', 'created_at', 'chrPublish'];
				$response     = Self::getPowerPanelRecords($moduleFields)
						->deleted()
						->where('intChildMenu', '=', $id)
						->get();
				return $response;
		}
		public static function getRecordListDropdown()
		{
				$response     = false;
				$moduleFields = ['id', 'varTitle'];
				$response     = Self::getPowerPanelRecords($moduleFields)
						->where('intChildMenu', '=', null)
						->deleted()
				//->publish()
						->get();
				return $response;
		}

		/**
		 * This method handels retrival of record by id
		 * @return  Object
		 * @since   2017-10-16
		 * @author  NetQuick
		 */
		public static function getRecordById($id)
		{
				$response     = false;
				$moduleFields = ['id', 'varTitle', 'intAliasId', 'txtDescription', 'intChildMenu', 'fkIntImgId', 'fkIntVideoId', 'varExternalLink', 'created_at', 'chrPublish'];
				$aliasFields  = ['id', 'varAlias'];
				$response     = Self::getPowerPanelRecords($moduleFields, $aliasFields)->deleted()->checkRecordId($id)->first();
				return $response;
		}

		/**
		 * This method handels retrival of record by id
		 * @return  Object
		 * @since   2017-10-16
		 * @author  NetQuick
		 */
		public static function getWelcomeSection($alias = false)
		{
				$response      = false;
				$moduleFields  = ['id', 'varTitle', 'txtDescription', 'created_at', 'chrPublish'];
				$aliasFields   = ['id', 'varAlias'];
				$query         = self::select($moduleFields);
				$data['alias'] = function ($query) use ($aliasFields, $alias) {
						$query->select($aliasFields)->where('varAlias', '=', $alias);
				};
				$response = $query->with($data)->deleted()->first();
				return $response;
		}

		/**
		 * This method handels retrival of record count
		 * @return  Object
		 * @since   2017-10-16
		 * @author  NetQuick
		 */
		public static function getRecordCount($filterArr = false, $returnCounter = false) {
				$response = 0;
				$moduleFields = ['id'];
				$response = Self::getPowerPanelRecords($moduleFields);
				if ($filterArr != false) {
						$response = $response->filter($filterArr, $returnCounter);
				}

				$response = $response->where('intChildMenu', '=', null)
														 ->deleted()			
														 ->count();
				return $response;
		}

		/**
		 * This method handels retrival of record count
		 * @return  Object
		 * @since   2017-10-16
		 * @author  NetQuick
		 */
		public static function getRecordCountforList($filterArr = false, $returnCounter = false) {
				$response = 0;
				$moduleFields = ['id'];
				$response = Self::getPowerPanelRecords($moduleFields);
				if ($filterArr != false) {
						$response = $response->filter($filterArr, $returnCounter);
				}

				if(empty($filterArr['searchFilter'])){
					$response = $response->where('intChildMenu', '=', null);
				}

				$response = $response->deleted()			
										->count();
				return $response;
		}

		/**
		 * This method handels alias id scope
		 * @return  Object
		 * @since   2016-07-24
		 * @author  NetQuick
		 */
		public function scopeCheckAliasId($query, $id)
		{
				return $query->where('intAliasId', $id);
		}
		/**
		 * This method handels record id scope
		 * @return  Object
		 * @since   2016-07-24
		 * @author  NetQuick
		 */
		public function scopeCheckRecordId($query, $id)
		{
				return $query->where('id', $id);
		}

		/**
		 * This method handels publish scope
		 * @return  Object
		 * @since   2016-07-14
		 * @author  NetQuick
		 */
		public function scopePublish($query)
		{
				return $query->where(['chrPublish' => 'Y']);
		}
		/**
		 * This method handels delete scope
		 * @return  Object
		 * @since   2016-07-14
		 * @author  NetQuick
		 */
		public function scopeDeleted($query)
		{
				return $query->where(['chrDelete' => 'N']);
		}

		/**
		 * This method handels alias relation
		 * @return  Object
		 * @since   2016-07-14
		 * @author  NetQuick
		 */
		public function alias()
		{
				return $this->belongsTo('App\Alias', 'intAliasId');
		}

		/**
		 * This method handels image relation
		 * @return  Object
		 * @since   2016-07-14
		 * @author  NetQuick
		 */
		public function image()
		{
				$response = false;
				$response = $this->belongsTo('App\Image', 'fkIntImgId', 'id');
				return $response;
		}
		/**
		 * This method handels video relation
		 * @return  Object
		 * @since   2016-07-14
		 * @author  NetQuick
		 */
		public function video()
		{
				return $this->belongsTo('App\Video', 'fkIntVideoId', 'id');
		}

		/**
		 * This method handels filter scope
		 * @return  Object
		 * @since   2016-07-14
		 * @author  NetQuick
		 */
		public function scopeFilter($query, $filterArr = false, $retunTotalRecords = false)
		{
				$response = null;
				if ($filterArr['orderByFieldName'] != null && $filterArr['orderTypeAscOrDesc'] != null) {
						$query = $query->orderBy($filterArr['orderByFieldName'], $filterArr['orderTypeAscOrDesc']);
				} else {
						$query = $query->orderBy('varTitle', 'ASC');
				}

				if (!$retunTotalRecords) {
						if (!empty($filterArr['iDisplayLength']) && $filterArr['iDisplayLength'] > 0) {
								$data = $query->skip($filterArr['iDisplayStart'])->take($filterArr['iDisplayLength']);
						}
				}

				if (!empty($filterArr['statusFilter']) && $filterArr['statusFilter'] != ' ') {
						$data = $query->where('chrPublish', $filterArr['statusFilter']);
				}

				if (!empty($filterArr['searchFilter']) && $filterArr['searchFilter'] != ' ') {
						$data = $query->where('varTitle', 'like', "%" . $filterArr['searchFilter'] . "%");
				}

				if (!empty($query)) {
						$response = $query;
				}
				return $response;
		}

		/**
		 * This method handels retrival of record by id for Log Manage
		 * @return  Object
		 * @since   2017-10-16
		 * @author  NetQuick
		 */
		public static function getRecordForLogById($id)
		{
				$response     = false;
				$moduleFields = ['id', 'varTitle', 'intAliasId', 'txtDescription', 'created_at', 'chrPublish'];
				$response     = Self::getPowerPanelRecords($moduleFields)->deleted()->checkRecordId($id)->first();
				return $response;
		}

}
