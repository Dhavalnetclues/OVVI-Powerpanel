<?php
namespace App\Helpers;
use Config;

Class LinkedInHelper {

	public static function generateAccessToken($code=false){
		$response=false;
		if($code && $code!=""){
				$linkedin_api_key = Config::get('Constant.SOCIAL_SHARE_LINKEDIN_API_KEY');
				$linkedin_secret_key = Config::get('Constant.SOCIAL_SHARE_LINKEDIN_SECRET_KEY');
				$linkedin_redirect_url = url("powerpanel/share/LinkedInCallBack");
				$params = array(
					'grant_type' => 'authorization_code', 
					'client_id' => $linkedin_api_key, 
					'client_secret' => $linkedin_secret_key, 
					'code' => $code, 
					'redirect_uri' => $linkedin_redirect_url, 
				); 
				// Authentication request 
				$url = 'https://www.linkedin.com/uas/oauth2/accessToken?' . http_build_query($params);
				// Tell streams to make a POST request 
				$context = stream_context_create( 
					array('http' =>  
						 array('method' => 'POST') 
					) 
				);
				// Retrieve access token information 
				$response = file_get_contents($url, false, $context); 				 
				$token = json_decode($response);

				if(isset($token->access_token) && $token->access_token !=""){
					$response = $token->access_token;
				}
			}
			return $response;
		}


	public static function share($content=false){
		$linkedin_api_key = Config::get('Constant.SOCIAL_SHARE_LINKEDIN_API_KEY');
		$linkedin_secret_key = Config::get('Constant.SOCIAL_SHARE_LINKEDIN_SECRET_KEY');
		$linkdinAccessToken = Config::get('Constant.SOCIAL_SHARE_LINKEDIN_ACCESS_TOKEN');

		$linkedIn = new \Happyr\LinkedIn\LinkedIn($linkedin_api_key, $linkedin_secret_key);		
		$linkedIn->setAccessToken($linkdinAccessToken);	
		
		$linkdinComments = isset($content['status'])?$content['status']:'Token Regenerated';
		
		$options = [ 
			'json'=>[
				'comment' => $linkdinComments,
				'visibility' => [
				 'code' => 'anyone'
				], 'content' => [
				 'submitted-url' => isset($content['url'])?$content['url']:url('/'),
				 'submitted-image-url' => isset($content['media'])?$content['media']:'',
				]
		 ]
	 ];

	 $result = $linkedIn->post('v1/people/~/shares', $options);

		if(isset($result['updateKey']) && $result['updateKey']!=''){
				echo json_encode($result);
		}else{
			$linkedin_redirect_url = url("powerpanel/share/LinkedInCallBack");
				$params = array(
					'response_type' => 'code', 
					'client_id' => $linkedin_api_key, 
					'scope' => 'r_basicprofile r_emailaddress w_share', 
					'state' => uniqid('', true), // unique long string 
					'redirect_uri' => $linkedin_redirect_url, 
				);
				// Authentication request 
				$linkdinAuthRrl = 'https://www.linkedin.com/uas/oauth2/authorization?' . http_build_query($params); 
				echo json_encode(['login'=>'failed','linkedInUrl'=>$linkdinAuthRrl]);
		}
	
	}


	
}