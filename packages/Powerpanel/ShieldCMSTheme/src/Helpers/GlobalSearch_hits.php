<?php
/**
* This helper generates email sender
* @package   Netquick
* @version   1.00
* @since     2016-11-14
*/
namespace App\Helpers;
use Session;
use App\Http\Controllers\Controller;
use App\GlobalSearch;
use App\GlobalSearchRel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;
use Jenssegers\Agent\Agent;
use App\Helpers\MyLibrary;
class GlobalSearch_hits extends Controller
{
		public static function insertSearchHits($searchString=false)
		{
				$sever_info = Request::server('HTTP_USER_AGENT');
				$ip_address = MyLibrary::get_client_ip();
				$agent  = new Agent;
				$device = '';		if ($agent->is('iPad')) {
						$device = 'Y';
				} elseif ($agent->isMobile()) {
						$deviceId = explode(',', $ip_address);
						if (isset($deviceId[0])) {
								$ip_address = $deviceId[0];
						}
						$device = 'N';
				} else {
						$device = 'Y';
				}
				
				$session_id = Session::getId();

				$currentYear = date('Y');
				$currentMonth = date('m');
				
				if (!empty($sever_info) && !empty($device) && !empty($ip_address) && !empty($searchString)) {
							$searchString = str_replace( array( '`', '~', '@' , '#', '^', '>','\\','/','&' ), ' ', $searchString);
							$searchString = preg_replace('/\s+/', ' ', $searchString);
							$response = GlobalSearch::select('id')
													->where(['varTitle'=>$searchString])
													->whereRaw("MONTH(created_at) =".$currentMonth." AND YEAR(created_at) =".$currentYear." ")
													->first();
							if (isset($response->id)) {
									$searchRelData = GlobalSearchRel::select('id')->where([ 'varSessionId' => $session_id, 'isWeb' => $device])->first();
										if (!isset($searchRelData->id)) {
												GlobalSearchRel::insert([
																'fkSearchRecordId'   => $response->id,
																'varBrowserInfo' => $sever_info,
																'isWeb'          => $device,
																'varIpAddress'   => $ip_address,
																'varSessionId'   => $session_id,
																'created_at'     => Carbon::now(),
																'updated_at'     => Carbon::now(),
												]);
										}
							}else{
								//code for new searchstring data insert
								$searchString = str_replace( array( '`', '~', '@' , '#', '^', '>','\\','/','&' ), ' ', $searchString);
								$searchString = preg_replace('/\s+/', ' ', $searchString);
								$searchRecordId = GlobalSearch::insertGetId([
												'varTitle'   => $searchString,
												'created_at'     => Carbon::now(),
												'updated_at'     => Carbon::now(),
								]);

								if($searchRecordId > 0){
									GlobalSearchRel::insert([
																'fkSearchRecordId'   => $searchRecordId,
																'varBrowserInfo' => $sever_info,
																'isWeb'          => $device,
																'varIpAddress'   => $ip_address,
																'varSessionId'   => $session_id,
																'created_at'     => Carbon::now(),
																'updated_at'     => Carbon::now(),
												]);
								}
							}
							
						}
				}
		
}