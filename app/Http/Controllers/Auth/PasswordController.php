<?php
namespace App\Http\Controllers\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\PowerpanelController;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Support\Facades\Password;
use App\EmailLog;
use App\EmailType;
use App\GeneralSettings;
use App\User;
use App\Helpers\MyLibrary;
use Request as CustomRequest;
class PasswordController extends PowerpanelController {
		/*
		|--------------------------------------------------------------------------
		| Password Reset Controller
		|--------------------------------------------------------------------------
		|
		| This controller is responsible for handling password reset requests
		| and uses a simple trait to include this behavior. You're free to
		| explore this trait and override any methods you wish to tweak.
		|
		*/
		use ResetsPasswords;
		/**
		* Create a new password controller instance.
		*
		* @return void
		*/
		protected $redirectTo = '/powerpanel/dashboard';
		public function __construct() {
				parent::__construct();
				//$this->middleware('guest:user');
				if(isset($_COOKIE['locale'])){
						app()->setLocale($_COOKIE['locale']);
				}
		}

		protected function getResetValidationRules()
		{
				return [
						'token' => 'required',
						'email' => 'required|email',
						'password' => 'required|confirmed|min:6|max:20|check_passwordrules',
						'password_confirmation' => 'required|confirmed|min:6|max:20|check_passwordrules',
				];
		}

		public function sendResetLinkAjax(Request $request, PasswordBroker $passwords)
		{
			
				if( $request->ajax() )
				{
					
						$this->validate($request, ['email' => 'required|email']);
						
						$response = $passwords->sendResetLink($request->only('email'), function($m){
								$m->subject('Your Password Reset Link');
						});

						switch ($response)
						{
								case PasswordBroker::RESET_LINK_SENT:
									 return[
											 'error'=>'false',
											 'msg'=>'A password link has been sent to your email address'
									 ];

								case PasswordBroker::INVALID_USER:
									 return[
											 'error'=>'true',
											 'msg'=>"We can't find a user with that email address"
									 ];
						}
				}
				return false;
		}

		/**
		 * Send a reset link to the given user.
		 *
		 * @param  \Illuminate\Http\Request  $request
		 * @return \Illuminate\Http\Response
		 */
		public function sendResetLinkEmail(Request $request,PasswordBroker $passwords)
		{
				$anotherEmailForSendResetLink = false;
				$em=[];
				$requestedEmail = $request->only('email');
				$requestedEmail = MyLibrary::getDecryptedString($requestedEmail['email']);
				$em['email'] = $requestedEmail;
				$request = $em;

				$this->validateSendResetLinkEmail($request);

        $broker = $this->getBroker();

        $response = Password::broker($broker)->sendResetLink(
            $this->getSendResetLinkEmailCredentials($request),
            $this->resetEmailBuilder()
        );
				$userData = User::getRecordByEmailID($requestedEmail);
				
				$userId = 1;
				if(!empty($userData)){
					$userId = $userData->id;
					$settings = Self::getSettings();
					$settings["subject"]   = "Password Reset Link - " . $settings['SITE_NAME'];
					$settings['emailType'] = EmailType::getRecords()->checkEmailType('Forgot Password')->first()->id;
					$settings['from']      = $settings['DEFAULT_EMAIL'];
					$settings['to']        = $requestedEmail;
					$settings['sender']    = $settings['SMTP_SENDER_NAME'];
					$settings['receiver']  = $settings['SMTP_SENDER_NAME'];
					$settings['txtBody']   = '';
					$settings['userId']   = $userId;
					$logId                 = Self::recodLog($settings);
				}
				
				switch ($response) {
						case Password::RESET_LINK_SENT:
								$mailSuccessResponse = $this->getSendResetLinkEmailSuccessResponse($response);
								if (isset($logId) && $logId > 0) {
										EmailLog::updateEmailLog(['id' => $logId], ['chrIsSent' => 'Y']);
								}
								return $mailSuccessResponse;
						case Password::INVALID_USER:
						default:
								return $this->getSendResetLinkEmailFailureResponse($response);
				}
		}

		/**
		 * This method loads general settings
		 * @return  Settings array
		 * @since   2017-08-17
		 * @author  NetQuick
		 */
		public static function getSettings()
		{
				$settings        = [];
				$generalSettings = GeneralSettings::getSettings();
				if (!empty($generalSettings)) {
						foreach ($generalSettings as $key => $value) {
								$settings[$value['fieldName']] = $value['fieldValue'];
						}
				}
				return $settings;
		}

		/**
		 * This method stores email log data in database
		 * @return  Flag
		 * @since   2017-08-17
		 * @author  NetQuick
		 */
		public static function recodLog($mailData = null)
		{
				if ($mailData != null) {
						$logData                    = [];
						$logData['intFkUserId']     = isset($mailData['userId']) ? $mailData['userId'] : 1;
						$logData['intFkEmailType']  = $mailData['emailType'];
						$logData['chrReceiverType'] = isset($mailData['chrReceiverType']) ? $mailData['chrReceiverType'] : '-';
						$logData['intFkModuleId']   = isset($mailData['intFkModuleId']) ? $mailData['intFkModuleId'] : 1;
						$logData['intFkRecordId']   = isset($mailData['chrReceiverType']) ? $mailData['chrReceiverType'] : '-';
						$logData['varFrom']         = $mailData['from'];
						$logData['txtTo']           = $mailData['to'];
						$logData['txtCc']           = isset($mailData['cc']) ? $mailData['cc'] : '-';
						$logData['txtBcc']          = isset($mailData['bcc']) ? $mailData['bcc'] : '-';
						$logData['txtSubject']      = $mailData['subject'];
						$logData['chrAttachment']   = isset($mailData['attachment']) ? $mailData['attachment'] : '-';
						$logData['chrIsSent']       = 'N';
						$logData['chrPublish']      = 'Y';
						$logData['chrDelete']       = 'N';
						$logData['chrIpAddress']    = MyLibrary::get_client_ip();
						$logData['varBrowserInfo']  = CustomRequest::header('User-Agent');
						$logData['created_at']      = date('Y-m-d H:i:s');
						$recordId                   = EmailLog::logEmail($logData);
						return $recordId;
				}
		}
}