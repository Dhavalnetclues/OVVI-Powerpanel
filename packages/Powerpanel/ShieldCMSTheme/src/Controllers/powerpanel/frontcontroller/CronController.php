<?php
/**
* The MenuController class handels news
* configuration  process.
* @package   Netquick powerpanel
* @license   http://www.opensource.org/licenses/BSD-3-Clause
* @version   1.00
* @since     2017-04-10
* @author    NetQuick
*/
namespace App\Http\Controllers;
use App\Modules;
use Powerpanel\Workflow\Models\Workflow;
use App\User;
use Powerpanel\Workflow\Models\WorkflowLog;
use App\CommonModel;
use Carbon\Carbon;
use App\Helpers\WorkFlowHandler;
use App\Helpers\MyLibrary;
use Illuminate\Routing\UrlGenerator;

class CronController extends FrontController {	
	public $_APP_URL;
	protected $url;
	public function __construct(UrlGenerator $url) {
		$this->url = $url->to('/');
		$this->_APP_URL = env('APP_URL');		
		parent::__construct();
	}

	public function workflow($id){
		$workflowObj = Workflow::getRecordById($id);
		if(!empty($workflowObj)){
			switch ($workflowObj->varActivity) {
				case 'contact-us'://use varModule name of module table
					Self::contactUsWorkflow($workflowObj);
					break;
				// case 'approvals'://currently turned off
				// 	Self::approvalsWorkflow($workflowObj);
				// 	break;
				default:
					echo 'No Workflow selected!';
					break;
			}
		}
	}

	public static function contactUsWorkflow($workflowObj){
		$modelNameSpace = '\\App\\ContactLead';
		$records = $modelNameSpace::getCronRecords();		
		$module = Modules::getModule($workflowObj->varActivity);
		foreach ($records as $record) {			
			$where = [];
			$where['fkModuleId'] = $module->id;
			$where['fkRecordId'] = $record->id;
			$where['charApproval'] = 'N';
			$workflowLog = WorkflowLog::getRecordWhere($where);
			WorkFlowHandler::afterLeadReceived($module,$record,$workflowObj,$workflowLog);			
			WorkFlowHandler::afterYesReceived($module,$record,$workflowObj,$workflowLog);			
			WorkFlowHandler::afterNoReceived($module,$record,$workflowObj,$workflowLog);			
		}
	}

	public static function approvalsWorkflow($workflowObj){		
		$where = [];
		$where['charApproval'] = 'Y';
		$workflowLog = WorkflowLog::getRecordsWhere($where);
		if(!empty($workflowLog)){
			foreach ($workflowLog as $log) {
				$module = Modules::getModuleById($log->fkModuleId);
				$modelNameSpace = '\\App\\'.$module->varModelName;
				$record = CommonModel::getCronRecord($modelNameSpace, $log->fkRecordId,'approvals');
				if(!empty($record)){

					$record->mainRecord = $modelNameSpace::getRecordById($record->fkMainRecord)->varTitle;

					$user = User::getRecordById($record->UserID);
					$userEmail = MyLibrary::getDecryptedString($user->email);
					$record->from = $user->name;
					$record->varEmail = $userEmail;
					
					//WorkFlowHandler::afterLeadReceived($module,$record,$workflowObj,$log);
					WorkFlowHandler::afterYesReceived($module,$record,$workflowObj,$log);

					$record->varEmail = 'ppadmin@netclues.net';
					WorkFlowHandler::afterNoReceived($module,$record,$workflowObj,$log,'approvals');
				}
			}
		}
	}
}