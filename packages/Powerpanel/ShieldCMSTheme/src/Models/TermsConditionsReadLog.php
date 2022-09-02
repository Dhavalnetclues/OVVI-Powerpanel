<?php
/**
 * The TermsConditionsReadLog class handels TermsConditionsReadLog model queries
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
use DB;
class TermsConditionsReadLog extends Model {
		protected $table = 'terms_and_conditions_read_history';
		protected $fillable = [
			'id',
			'fkIntUserId',
			'name',
			'email',
			'varIpAddress',
			'chrTermsRead',
			'chrDelete',
			'created_at'
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
}
