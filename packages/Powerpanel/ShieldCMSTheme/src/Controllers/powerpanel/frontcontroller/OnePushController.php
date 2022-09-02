<?php

namespace App\Http\Controllers;

use App\Http\Controllers\PowerpanelController;
use Request;
use App\GeneralSettings;
use App\Alias;
use App\Image;
use Abraham\TwitterOAuth\TwitterOAuth;
use DB;
use File;
use App\Http\Traits\slug;
use Config;
use Session;
use App\Helpers\MyLibrary;
use App\Helpers\Google;
use App\Helpers\Instagram_share;
use App\Helpers\LinkedInHelper;
use App\Helpers\Facebook;
use App\Helpers\Twitter;
use App\Helpers\resize_image;
use Cache;

class OnePushController extends PowerpanelController {

    public function ShareonSocialMedia(Request $request) {
        if (null !== (Request::get('socialmedia')) && !empty(Request::get('socialmedia'))) {
            $formPost = Request::all();
            Session::put('socialShare', $formPost);
        } else {
            $formPost = Session::get('socialShare');
            if(empty($formPost)){
                return false;
            }
        }
        if (in_array('facebook', $formPost['socialmedia'])) {
            $this->fbShare($formPost);
        }
        if (in_array('twitter', $formPost['socialmedia'])) {
            $this->twitterShare($formPost);
        }
        if (in_array('googleplus', $formPost['socialmedia'])) {
            $this->gPshare($formPost);
        }
        if (in_array('linkedin', $formPost['socialmedia'])) {
            $this->linkedinShare($formPost);
        }
        if (in_array('instagram', $formPost['socialmedia'])) {
            Instagram_share::share($formPost);
        }
    }

    public function twitterShare($formPost) {
        Twitter::shareStory($formPost);
    }

    public function fbShare($formPost) {
        Facebook::shareStory($formPost);
    }

    public function gPshare($formPost) {
        $code = Config::get('Constant.SOCIAL_SHARE_GOOGLE_PLUS_ACCESS_TOKEN');
        $content = [
            'status' => $formPost['varTitle'] . ': ' . $formPost['txtDescription'],
            'url' => $formPost['frontLink']
        ];
        if (!empty($formPost['frontImg']) && isset($formPost['frontImg'])) {
            $img = Image::getImg($formPost['frontImg']);
            $content['media'] = Config::get('Constant.CDN_PATH') . 'assets/images/' . $img->txtImageName . '.' . $img->varImageExtension;
        }
        Google::shareStory($code, $content);
    }

    public function linkedinShare($formPost) {
        $content = [
            'status' => $formPost['varTitle'] . ': ' . $formPost['txtDescription'],
            'url' => $formPost['frontLink']
        ];
        if (!empty($formPost['frontImg']) && isset($formPost['frontImg'])) {
            $content['media'] = resize_image::resize($formPost['frontImg']);
        }
        LinkedInHelper::share($content);
    }

    public function gPlusCallBack(Request $request) {
        if (Request::get('code')) {
            $formPost = $request->session()->get('socialShare');
            $token = Google::generateAccessToken(Request::get('code'))['access_token'];
            GeneralSettings::checkByFieldName('SOCIAL_SHARE_GOOGLE_PLUS_GETCODE')->update(['fieldValue' => Request::get('code')]);
            GeneralSettings::checkByFieldName('SOCIAL_SHARE_GOOGLE_PLUS_ACCESS_TOKEN')->update(['fieldValue' => $token]);
            Config::set('Constant.SOCIAL_SHARE_GOOGLE_PLUS_ACCESS_TOKEN', $token);
            Config::set('Constant.SOCIAL_SHARE_GOOGLE_PLUS_GETCODE', Request::get('code'));
            Cache::tags('genralSettings')->flush();
            $this->gPshare($formPost);
            echo "<script>window.close();</script>";
        }
    }

    public function LinkedInCallBack(Request $request) {
        if (Request::get('code')) {
            $formPost = $request->session()->get('socialShare');
            $token = LinkedInHelper::generateAccessToken(Request::get('code'));
            GeneralSettings::checkByFieldName('SOCIAL_SHARE_LINKEDIN_GETCODE')->update(['fieldValue' => Request::get('code')]);
            GeneralSettings::checkByFieldName('SOCIAL_SHARE_LINKEDIN_ACCESS_TOKEN')->update(['fieldValue' => $token]);
            Config::set('Constant.SOCIAL_SHARE_LINKEDIN_ACCESS_TOKEN', $token);
            Config::set('Constant.SOCIAL_SHARE_LINKEDIN_GETCODE', Request::get('code'));
            Cache::tags('genralSettings')->flush();
            $this->linkedinShare($formPost);
            echo "<script>window.close();</script>";
        }
    }

    public function getRecord() {

        $id = (int) Request::get('alias');
        $modal = Request::get('modal');
        $modelNameSpace = Request::get('namespace');
        if(!empty($modelNameSpace) && $modelNameSpace !== null) {
            $modelNameSpace = $modelNameSpace . 'Models\\' . $modal;
        } else {
            $modelNameSpace = '\\App\\' . $modal;
        }
        $moduleHasImage = Request::get('modulehasimage');
        $moduleImageFieldName = Request::get('moduleimagefiledname');
        $moduleFieldsForShare = array();
        $moduleFieldsForShare = ['varTitle', 'txtDescription', 'varMetaTitle', 'varMetaDescription'];
        if ($moduleHasImage == "yes") {
            if ($moduleImageFieldName != "") {
                array_push($moduleFieldsForShare, $moduleImageFieldName);
            } else {
                array_push($moduleFieldsForShare, 'fkIntImgId');
            }
        }
        $record = $modelNameSpace::select($moduleFieldsForShare)
                ->where('id', $id)
                ->get();
        if ($moduleHasImage == "yes") {
            $record[0]->imgsrc = resize_image::resize($record[0]->$moduleImageFieldName, 200, 200);
            $record[0]->modulefieldImgId = $record[0]->$moduleImageFieldName;
        }
        echo json_encode($record);
    }

}
