<?php
/**
 * The FrontController class handels email functions
 * configuration  process (ORM code Updates).
 * @package   Netquick powerpanel
 * @license   http://www.opensource.org/licenses/BSD-3-Clause
 * @version   1.1
 * @since     2017-08-17
 * @author    NetQuick
 */
namespace App\Helpers;
use Config;
use Powerpanel\Workflow\Models\WorkflowLog;
use App\Helpers\Email_sender;

class WorkFlowHandler
{

		public static function afterLeadReceived($module,$record,$workflowObj,$workflowLog){
		#After case===============================		
		$created_at = $record->created_at;
		$interval = Self::getTimeDiffFromCurrentTstamp($created_at);
		$elapsedfull = $interval->format('%a days %h hours %i minutes %s seconds');
		$elapsed = $interval->format('%a');		
		if( (int)$elapsed >= (int)$workflowObj->varAfter ){
			if(!empty($workflowLog)){
				if($workflowLog->chrAfterSent == 'N'){
					$data=[];
					$data['chrAfterSent']='Y';
					$where['fkModuleId'] = $module->id;
					$where['fkRecordId'] = $record->id;
					$where['chrAfterSent']='N';
					if(strlen($record->varEmail) > 50){					
						$record->varEmail = MyLibrary::getLaravelDecryptedString($record->varEmail);
					}
					Self::sendMail($record->varEmail,$workflowObj->txtAfter);
					WorkflowLog::updateRecord($data,$where);
					echo 'Sent email to '.$record->varName.'&lt;'.$record->varEmail.'&gt;<br/>Lead Received before: '.$elapsedfull.'<br/>';
				}
			}
		}
		#\.After case===============================				
	}

	public static function afterYesReceived($module,$record,$workflowObj,$workflowLog){
		#After Yes received case===============================
			if(!empty($workflowLog)){

				if(!empty($workflowLog->dtYes) && empty($workflowLog->dtYesSent)){
					$created_at = $workflowLog->dtYes;
					$interval = Self::getTimeDiffFromCurrentTstamp($created_at);
					$elapsedfull = $interval->format('%a days %h hours %i minutes %s seconds');				
					$elapsed = $interval->format('%a');
					if( (int)$elapsed === (int)$workflowObj->varFrequancyPositive ){
							$data=[];
							$data['dtYesSent']=Config::get('Constant.SQLTIMESTAMP');
							$where['fkModuleId'] = $module->id;
							$where['fkRecordId'] = $record->id;
							$where['dtYesSent']=null;
							if(strlen($record->varEmail) > 50){					
								$record->varEmail = MyLibrary::getLaravelDecryptedString($record->varEmail);
							}
							Self::sendMail($record->varEmail,$workflowObj->txtFrequancyPositive);
							WorkflowLog::updateRecord($data,$where);
							echo 'Sent email to '.$record->varName.'&lt;'.$record->varEmail.'&gt;<br/>Lead Received before: '.$elapsedfull.'<br/>';
					}
				}elseif (!empty($workflowLog->dtYesSent)) {
					#Frequantly send emails at every n days of sent last==============					
					$created_at = $workflowLog->dtYesSent;
					$interval = Self::getTimeDiffFromCurrentTstamp($created_at);
					$elapsedfull = $interval->format('%a days %h hours %i minutes %s seconds');					
					$elapsed = $interval->format('%a');
					if( (int)$elapsed === (int)$workflowObj->varFrequancyPositive ){
						$data=[];
						$data['dtYesSent']=Config::get('Constant.SQLTIMESTAMP');
						$where['fkModuleId'] = $module->id;
						$where['fkRecordId'] = $record->id;
						if(strlen($record->varEmail) > 50){					
								$record->varEmail = MyLibrary::getLaravelDecryptedString($record->varEmail);
							}
						Self::sendMail($record->varEmail,$workflowObj->txtFrequancyPositive);
						WorkflowLog::updateRecord($data,$where);
						echo 'Sent email to '.$record->varName.'&lt;'.$record->varEmail.'&gt;<br/>Lead Received before: '.$elapsedfull.'<br/>';
					}
					#\.Frequantly send emails at every n days of sent last============
				}
			}
		#\.After Yes received case===============================				
	}

	public static function afterNoReceived($module,$record,$workflowObj,$workflowLog,$type=false){
		#After No received case===============================		
			$contentText = view('powerpanel.workflow.partials.approvals-reminder', ['module'=>$module,'record'=>$record])->render();
			$workflowObj->txtFrequancyNegative = $contentText;
			if(!empty($workflowLog) && $type!='approvals'){
				if(!empty($workflowLog->dtNo) && empty($workflowLog->dtNoSent)){
					$created_at = $record->created_at;
					$interval = Self::getTimeDiffFromCurrentTstamp($created_at);
					$elapsedfull = $interval->format('%a days %h hours %i minutes %s seconds');
					$elapsed = $interval->format('%a');										
					if( (int)$elapsed === (int)$workflowObj->varFrequancyNegative ){
							$data=[];
							$data['dtNoSent']=Config::get('Constant.SQLTIMESTAMP');
							$where['fkModuleId'] = $module->id;
							$where['fkRecordId'] = $record->id;
							$where['dtNoSent']=null;
							if(strlen($record->varEmail) > 50){					
								$record->varEmail = MyLibrary::getLaravelDecryptedString($record->varEmail);
							}
							Self::sendMail($record->varEmail,$workflowObj->txtFrequancyNegative);
							WorkflowLog::updateRecord($data,$where);
							echo 'Sent email to '.$record->varName.'&lt;'.$record->varEmail.'&gt;<br/>Lead Received before: '.$elapsedfull.'<br/>';
					}
				}elseif (!empty($workflowLog->dtNoSent)) {
					#Frequantly send emails at every n days of sent last==============
					$created_at = $workflowLog->dtNoSent;
					$interval = Self::getTimeDiffFromCurrentTstamp($created_at);
					$elapsedfull = $interval->format('%a days %h hours %i minutes %s seconds');					
					$elapsed = $interval->format('%a');
					if( (int)$elapsed === (int)$workflowObj->varFrequancyNegative ){
						$data=[];
						$data['dtNoSent']=Config::get('Constant.SQLTIMESTAMP');
						$where['fkModuleId'] = $module->id;
						$where['fkRecordId'] = $record->id;		
						if(strlen($record->varEmail) > 50){					
								$record->varEmail = MyLibrary::getLaravelDecryptedString($record->varEmail);
							}				
						Self::sendMail($record->varEmail,$workflowObj->txtFrequancyNegative);
						WorkflowLog::updateRecord($data,$where);
						echo 'Sent email to '.$record->varName.'&lt;'.$record->varEmail.'&gt;<br/>Lead Received before: '.$elapsedfull.'<br/>';
					}
					#\.Frequantly send emails at every n days of sent last============
				}
			}elseif ($type=='approvals') {
				Self::noApproval($module,$record,$workflowObj,$workflowLog,$type);
			}
		#\.After No received case===============================				
	}

	public static function noApproval($module,$record,$workflowObj,$workflowLog,$type=false){
		if(empty($workflowLog->dtNoSent)){
					$created_at = $record->created_at;
					$interval = Self::getTimeDiffFromCurrentTstamp($created_at);
					$elapsedfull = $interval->format('%a days %h hours %i minutes %s seconds');
					$elapsed = $interval->format('%a');
					
					$data=[];
					$data['dtNoSent']=Config::get('Constant.SQLTIMESTAMP');
					$where['fkModuleId'] = $module->id;
					$where['fkRecordId'] = $record->id;
					$where['dtNoSent']=null;
					if(strlen($record->varEmail) > 50){					
						$record->varEmail = MyLibrary::getLaravelDecryptedString($record->varEmail);
					}
					Self::sendMail($record->varEmail,$workflowObj->txtFrequancyNegative);
					WorkflowLog::updateRecord($data,$where);
					echo 'Sent email to '.$record->varName.'&lt;'.$record->varEmail.'&gt;<br/>Lead Received before: '.$elapsedfull.'<br/>';
					
				}elseif (!empty($workflowLog->dtNoSent)) {					
					#Frequantly send emails at every n days of sent last==============
					$created_at = $workflowLog->dtNoSent;
					$interval = Self::getTimeDiffFromCurrentTstamp($created_at);
					$elapsedfull = $interval->format('%a days %h hours %i minutes %s seconds');					
					$elapsed = $interval->format('%a');
					if( (int)$elapsed === (int)$workflowObj->varFrequancyNegative ){
						$data=[];
						$data['dtNoSent']=Config::get('Constant.SQLTIMESTAMP');
						$where['fkModuleId'] = $module->id;
						$where['fkRecordId'] = $record->id;		
						if(strlen($record->varEmail) > 50){					
								$record->varEmail = MyLibrary::getLaravelDecryptedString($record->varEmail);
							}				
						Self::sendMail($record->varEmail,$workflowObj->txtFrequancyNegative);
						WorkflowLog::updateRecord($data,$where);
						echo 'Sent email to '.$record->varName.'&lt;'.$record->varEmail.'&gt;<br/>Lead Received before: '.$elapsedfull.'<br/>';
					}
					#\.Frequantly send emails at every n days of sent last============
				}
	}


	public static function getTimeDiffFromCurrentTstamp($date){		
		$now = Config::get('Constant.SQLTIMESTAMP');
		$datetime1 = new \DateTime($now);
		$datetime2 = new \DateTime($date);
		return $datetime1->diff($datetime2);
	}

	public static function sendMail($email,$txtContent){		
		Email_sender::cronMail($email,$txtContent);
	}
}
