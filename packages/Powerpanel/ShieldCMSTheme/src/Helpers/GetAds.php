<?php
/**
* This helper returns ads for specific page
* @package   Netquick
* @version   1.00
* @since     2017-04-18
* @author    NetQuick
*/
namespace App\Helpers;
use Illuminate\Support\Facades\Request;
use App\Http\Traits\slug;
use App\Advertise;
use Powerpanel\CmsPage\Models\CmsPage;
class GetAds{
	use slug;
	static function get($position=false,$page=false){ 
	$response=false;
		if(!empty($page) && !empty($position)){				
				$ads=Advertise::getFrontRecordsByPos($page, $position);
			}
		return $response;		
	}
}