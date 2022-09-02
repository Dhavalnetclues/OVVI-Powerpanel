<?php
/**
* DateFormater helper class converts date format visa-versa js to sql
* @package   Netquick
* @version   1.00
* @since     2016-12-29
* @author    Vishal Agrawal
*/
namespace App\Helpers;
use DB;

class RecordUploadDate {
  static function getDateByRecordIdAndTableName($recordId=false,$tableName = false) {
      $date = "";
      if($recordId > 0){
         $response = DB::table($tableName)->select('dtDateTime')->where('id',$recordId)->first();
         if(!empty($response)){
             $date = $response->dtDateTime;
         }
      }
      return $date;
  }
}