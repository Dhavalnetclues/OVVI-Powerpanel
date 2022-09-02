<?php
namespace App\Helpers;

use App\Image;
use Config;
use Facebook\FileUpload\FacebookFile;
use Facebook\FacebookRequest;
use Facebook\GraphObject;
use Facebook\FacebookRequestException;

Class Facebook{
		public static function generateAccessToken($code=false){
			
		}

		public static function shareStory($formPost=false){

			$status = 0;
			$fbSetting = array();
			$fbSetting['page_id'] = Config::get('Constant.SOCIAL_SHARE_FB_ID');
			$fbSetting['app_id']=Config::get('Constant.SOCIAL_SHARE_FB_API_KEY');
			$fbSetting['app_secret']=Config::get('Constant.SOCIAL_SHARE_FB_SECRET_KEY');
			$fbSetting['accessToken']=Config::get('Constant.SOCIAL_SHARE_FB_ACCESS_TOKEN');

			$accessToken = $fbSetting['accessToken'];

			$fb = new \Facebook\Facebook([
				'app_id' => $fbSetting['app_id'],
				'app_secret' => $fbSetting['app_secret'],
				'default_graph_version' => 'v2.11'
			]);

			$linkData = [
				'message' => $formPost['txtDescription'],
			];

			$img = array();

			$helper = $fb->getRedirectLoginHelper();

			$fbSetting['accessToken'] = $helper->getAccessToken();  

			if( !empty($formPost['socialImage']) && $formPost['socialImage'] !== null) {
				$img=Image::getImg($formPost['socialImage']);
			}
			
			if(!empty($img)){
				$linkData['source'] = $fb->fileToUpload(Config::get('Constant.CDN_PATH').'assets/images/'.$img->txtImageName.'.'.$img->varImageExtension);
			}
			
			try {
				$response = $fb->post('/'.$fbSetting['page_id'].'/feed', $linkData, $accessToken);
			
				echo '<pre>';print_r($response);die;
			} catch(Facebook\Exceptions\FacebookResponseException $e) {
				echo 'Graph returned an error: ' . $e->getMessage();
				exit;
			} catch(Facebook\Exceptions\FacebookSDKException $e) {
				echo 'Facebook SDK returned an error: ' . $e->getMessage();
				exit;
			}
			$graphNode = $response->getGraphNode();
			echo 'Posted with id: ' . $graphNode['id'];
			echo $status;
		}

}