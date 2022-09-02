<?php
/**
* This helper Share post on Instagram
* @package   Netquick
* @version   1.00
* @since     2017-09-18
* @author    Netquick
*/

namespace App\Helpers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\GeneralSettings;
use Request;
use Session;
use Config;

require __DIR__.'/../../vendor/autoload.php';

class Instagram_share{

	public static function share($fieldArr=null) {
	
		////// CONFIG ///////
		$username = 'tomsmith.net1';
		$password = 'netclues@123';
		$debug = true;
		$truncatedDebug = false;
	
		/////// MEDIA ////////
		$photoFilename = Config::get('Constant.LOCAL_CDN_PATH').'/caches/50x50/2017-07-25-22-04-30-a-red-tick-on-a-white-background.jpg';
		
		$ig = new \InstagramAPI\Instagram($debug, $truncatedDebug);
		try {
			$ig->login($username, $password);
		} catch (\Exception $e) {
			echo 'Something went wrong: '.$e->getMessage()."\n";			
			exit(0);
		}
		// NOTE: This code will make the hashtag area 'clickable', but YOU need to
		// manually draw the hashtag or a sticker-image on top of your image yourself
		// before uploading, if you want the tag to actually be visible on-screen!
		$metadata = [
			'hashtags' => [
				// Note that you can add more than one hashtag in this array.
				[
					'tag_name'         => 'test', // Hashtag WITHOUT the '#'! NOTE: This hashtag MUST appear in the caption.
					'x'                => 0.5, // Range: 0.0 - 1.0. Note that x = 0.5 and y = 0.5 is center of screen.
					'y'                => 0.5, // Also note that X/Y is setting the position of the CENTER of the clickable area.
					'width'            => 0.24305555, // Clickable area size, as percentage of image size: 0.0 - 1.0
					'height'           => 0.07347973, // ...
					'rotation'         => 0.0,
					'is_sticker'       => false, // Don't change this value.
					'use_custom_title' => false, // Don't change this value.
				],
				// ...
			],
			'caption' => '#NetQuick API!',
		];
		try {
			$ig->story->uploadPhoto($photoFilename, $metadata);
			// NOTE: Providing metadata for story uploads is OPTIONAL. If you just want
			// to upload the story without hashtags, just do the following instead:
			// $ig->story->uploadPhoto($photoFilename);
		} catch (\Exception $e) {
			echo 'Something went wrong: '.$e->getMessage()."\n";
		}
	
	
	
	}
	
}