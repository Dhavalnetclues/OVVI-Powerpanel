<?php
/**
* This helper give description for static block section by alias.
* @package   Netquick
* @version   1.00
* @since     2016-12-07
* @author    Vishal Agrawal
*/
namespace App\Helpers;
use App\StaticBlocks;
use App\Http\Traits\slug;

class static_block {
	use slug;

  static function static_block($alias,$onlyParentRecords = false,$childWithParent = false) {
		if (!empty($alias)) 
		{
			$alisasID = slug::resolve_alias_for_routes($alias);
        if (!empty($alisasID)) {
            
            $staticBlockObj = StaticBlocks::getStaticBlockList($alisasID);
            if(!empty($staticBlockObj)) {
              $staticData = StaticBlocks::getStaticBlockId($staticBlockObj->id,$onlyParentRecords,$childWithParent);
              return $staticData;
           }
        }
		}
	}
}