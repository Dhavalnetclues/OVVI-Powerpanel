<?php
/**
* DateFormater helper class converts date format visa-versa js to sql
* @package   Netquick
* @version   1.00
* @since     2016-12-29
* @author    Vishal Agrawal
*/
namespace App\Helpers;
class DateFormater {
  static function fixDateFormat($format) {
  	$mySqlFormat = '';
		if (!empty($format)) {
			if ($format=='yyyy/mm/dd') {
				$mySqlFormat .= 'Y/m/d';
			} elseif ($format=='mm/dd/yyyy') {
				$mySqlFormat .= 'm/d/Y';
			} elseif ($format=='yyyy-mm-dd') {
				$mySqlFormat .= 'Y-m-d';
			} elseif ($format=='dd-mm-yyyy') {
				$mySqlFormat .= 'd-m-Y';
			} 
		}
		return $mySqlFormat;
	}
}