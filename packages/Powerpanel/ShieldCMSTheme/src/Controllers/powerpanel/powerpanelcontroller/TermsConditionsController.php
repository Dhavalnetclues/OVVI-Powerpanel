<?php
namespace App\Http\Controllers\Powerpanel;
use Input;
use Auth;
use App\TermsConditions;
use App\TermsConditionsReadLog;
use App\Http\Controllers\PowerpanelController;
use Config;
use App\CommonModel;
use App\Helpers\MyLibrary;
class TermsConditionsController extends PowerpanelController {
	/**
	* Create a new controller instance.
	* @return void
	*/
	public function __construct() {
		parent::__construct();
		if(isset($_COOKIE['locale'])){
			app()->setLocale($_COOKIE['locale']);
		}
	}

	public function insertAccept(){
		$response = false;
		$data = array();		
		$data['fkIntUserId'] = auth()->user()->id;
		$data['name'] = auth()->user()->name;
		$data['email'] = auth()->user()->email;//MyLibrary::getDecryptedString(auth()->user()->email);
		$data['chrAccepted'] = 'Y';
		$data['varIpAddress'] = MyLibrary::get_client_ip();
		$data['created_at'] = date('Y-m-d H:i:s');
		$data['updated_at'] = date('Y-m-d H:i:s');		
		$read = TermsConditions::addRecord($data);
		if(!empty($read)){
			$response = $read;
		}
		return $response;
	}	

	public function checkAccepted(){
		$response = 'N';
		$userId = auth()->user()->id;
		$accepted = TermsConditions::getRecord($userId);
		if(!empty($accepted)){
			$response = $accepted->chrAccepted;
		}
		return $response;
	}

	public function insertRead()
	{
		$response = false;
		$data = array();
		$data['fkIntUserId'] = auth()->user()->id;
		$data['name'] = auth()->user()->name;
		$data['email'] = auth()->user()->email;//MyLibrary::getDecryptedString(auth()->user()->email);
		$data['chrTermsRead'] = 'Y';
		$data['varIpAddress'] = MyLibrary::get_client_ip();
		$read = TermsConditionsReadLog::addRecord($data);
		if(!empty($read)){
			$response = $read;
		}
		return $response;
	}
}