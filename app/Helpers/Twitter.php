<?php

namespace App\Helpers;

use Abraham\TwitterOAuth\TwitterOAuth;
use App\Image;
use Config;

Class Twitter{
		public static function generateAccessToken($code=false){
			
		}

		public static function shareStory($formPost=false){
			$twSetting = array();	
			$twSetting['api_key'] = Config::get('Constant.SOCIAL_SHARE_TWITTER_API_KEY');
			$twSetting['secret_key']=Config::get('Constant.SOCIAL_SHARE_TWITTER_SECRET_KEY');
			$twSetting['access_token']=Config::get('Constant.SOCIAL_SHARE_TWITTER_ACCESS_TOKEN');
			$twSetting['access_secret_key']=Config::get('Constant.SOCIAL_SHARE_TWITTER_ACCESS_SECRET_KEY');

			$connection = new TwitterOAuth($twSetting['api_key'], $twSetting['secret_key'], $twSetting['access_token'], $twSetting['access_secret_key']);
			$parameters = ['status' => $formPost['txtDescription']];

			if(!empty($formPost['socialImage']) && isset($formPost['socialImage'])) {
				$img=Image::getImg($formPost['socialImage']);
				$media1 = $connection->upload('media/upload', ['media' => public_path('assets/images/'.$img->txtImageName.'.'.$img->varImageExtension)]);
				$parameters['media_ids'] = $media1->media_id_string;
			}
			
			$messageTxt = substr($parameters['status'], 0, 140);
			$Response = $connection->post('statuses/update', $parameters);
			
			echo json_encode($Response);
			exit;
		}

}