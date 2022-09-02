<?php
namespace App\Helpers;
require_once base_path("vendor/autoload.php"); //include library
require_once base_path('vendor/google/apiclient/src/Google/Client.php');
require_once base_path('vendor/google/apiclient-services/src/Google/Service/PlusDomains.php');
require_once base_path('vendor/google/apiclient-services/src/Google/Service/AndroidPublisher/Testers.php');



use Google_Client;
use Google_Service_PlusDomains;
use Google_Service_AndroidPublisher_Testers;
use GuzzleHttp;
use Config;
Class Google{
		public static function generateAccessToken($code=false){
			$response=false;			
			if($code){
				$client = new Google_Client();
				$client->setRedirectUri('postmessage');						
				$client->setAuthConfig(base_path("app/Services/client_id.json"));
				$client->addScope([
						"https://www.googleapis.com/auth/plus.me",						
						"https://www.googleapis.com/auth/plus.stream.write",
						"https://www.googleapis.com/auth/plus.circles.read",
						"https://www.googleapis.com/auth/plus.circles.write"
				]);
				$client->authenticate($code);
				$response = $client->getAccessToken();
			}
			// else{
			// 	$auth_url = $client->createAuthUrl(); // create authentication URL							
			// 	$response = json_encode(['login'=>'failed','GpUrl'=>$auth_url]);
			// }
			return $response;
		}

		public static function shareStory($code, $content=false){
			
				//create Google Client Object and set its configurations
				$client = new Google_Client();
				$client->setRedirectUri('postmessage');						
				$client->setAuthConfig(base_path("app/Services/client_id.json"));
				$client->addScope([
						"https://www.googleapis.com/auth/plus.me",						
						"https://www.googleapis.com/auth/plus.stream.write",
						"https://www.googleapis.com/auth/plus.circles.read",
						"https://www.googleapis.com/auth/plus.circles.write",
						"https://www.googleapis.com/auth/plus.media.upload"
				]);				 		
				$token=Config::get('Constant.SOCIAL_SHARE_GOOGLE_PLUS_ACCESS_TOKEN');
				if(isset($token)){
					$client->setAccessToken($token);
					$message = isset($content['status'])?$content['status']:"NQ Post using Google API PHP Client Library!";
					$url = isset($content['url'])?$content['url']:'';
					$activity = [
		        'object' => [
		            'originalContent' => $message,
		            'attachments' => [ 
		            	[
		                'url' => $url,
		                'objectType' => 'article',
		            	] 
		          	]
		        	],
		        'access' => [
		           'items' => [ 
		            	[
		                'type' => 'domain'
		            	] 
		          	],
		           'domainRestricted' => true
		        ]
		    	];
						
						$options    = array(
								"headers" => array(
										'content-type' => 'application/json; charset=UTF-8',
										'Authorization' => 'bearer '.$token
								),
								"body" => json_encode($activity)
						);

						$circleOptions = array(
								"headers" => array(
										'content-type' => 'application/json; charset=UTF-8',
										'Authorization' => 'bearer '.$token
								)
						);
						
						#get Circles list of current user
						// $httpClient = $client->authorize();						
						// $getCircles =  $httpClient->get("https://www.googleapis.com/plusDomains/v1/people/me/circles", $circleOptions);
						// $circles =  json_decode($getCircles->getBody()->getContents());
						// var_dump($circles);exit;

						

						$httpClient = $client->authorize();
						$request = $httpClient->request("POST", "https://www.googleapis.com/plusDomains/v1/people/me/activities", $options);
						//$request = $httpClient->request("POST", "https://www.googleapis.com/plusDomains/v1/circles/302b60070cff7f51", $options);
						$response = $request->getBody(); 
						$response = json_decode($response->getContents());
						if(isset($response->error->code)){
							if($response->error->code==401){
								$auth_url = $client->createAuthUrl(); // create authentication URL							
								echo json_encode(['login'=>'failed','GpUrl'=>$auth_url]);
							} else {								
								echo json_encode($response);
							}
						}else{							
							echo json_encode($response);
						}						
				} else {
					$auth_url = $client->createAuthUrl(); // create authentication URL							
					echo json_encode(['login'=>'failed','GpUrl'=>$auth_url]);
				}
			}

}