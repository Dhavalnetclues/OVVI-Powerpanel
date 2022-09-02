<?php
/**
 * The TermsConditions class handels TermsConditions model queries
 * ORM implemetation.
 * @package   Netquick powerpanel
 * @license   http://www.opensource.org/licenses/BSD-3-Clause
 * @version   1.00
 * @since   	2018-09-12
 * @author    NetQuick
 */
namespace App;
use Illuminate\Database\Eloquent\Model;
use Cache;
class TermsConditions extends Model {
		protected $table = 'terms_and_conditions_history';
		protected $fillable = [
			'id',
			'fkIntUserId',
			'name',
			'email',
			'varIpAddress',
			'chrAccepted',
			'chrDelete',
			'created_at',
			'updated_at'
		];

	/**
   * This method handels insert of event record
   * @return  Object
   * @since   2016-07-14
   * @author  NetQuick
   */
    public static function addRecord($data = false) {
      $response = false;
      $recordId = Self::insertGetId($data);
      if ($recordId > 0) {
          $response = $recordId;
      }        
      return $response;
    }

		public static function getRecord($userId){
			$response = false;
			$response = Self::getPowerPanelRecords(['chrAccepted'])
			->checkAccept()
			->checkUserId($userId)
			->orderBy('updated_at','DESC')
			->first();
			return $response;
		}

		/**
		 * This method handels backend records
		 * @return  Object
		 * @since   2016-07-14
		 * @author  NetQuick
		 */
		public static function getPowerPanelRecords($moduleFields = false)
		{
				$data     = [];
				$response = false;
				$response = self::select($moduleFields);

				if (count($data) > 0) {
						$response = $response->with($data);
				}
				return $response;
		}

	/**
	 * This method handels record id scope
	 * @return  Object
	 * @since   2016-07-24
	 * @author  NetQuick
	 */
	function scopeCheckAccept($query) {
		return $query->where('chrAccepted', 'Y');
	}

	/**
	 * This method handels record id scope
	 * @return  Object
	 * @since   2016-07-24
	 * @author  NetQuick
	 */
	function scopeCheckUserId($query,$id) {
		return $query->where('fkIntUserId', $id);
	}
}
