<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use App\Helpers\MyLibrary;
use App\EmailLog;
use App\EmailType;

class ForgotPasswordController extends Controller
{
		/*
		|--------------------------------------------------------------------------
		| Password Reset Controller
		|--------------------------------------------------------------------------
		|
		| This controller is responsible for handling password reset emails and
		| includes a trait which assists in sending these notifications from
		| your application to your users. Feel free to explore this trait.
		|
		*/

		use SendsPasswordResetEmails;

		/**
		 * Create a new controller instance.
		 *
		 * @return void
		 */
		public function __construct(){
				$this->middleware('guest');
		}


		public function sendResetLinkEmail(Request $request){
			
			$this->validateEmail($request);

			$requestedEmail = $request->only('email');
			
			$request->merge(['email' => MyLibrary::getEncryptedString($requestedEmail['email'])]);

			// We will send the password reset link to this user. Once we have attempted
			// to send the link, we will examine the response then see the message we
			// need to show to the user. Finally, we'll send out a proper response.
			$response = $this->broker()->sendResetLink(
					$request->only('email')
			);

			return $response == Password::RESET_LINK_SENT
									? $this->sendResetLinkResponse($request,$response)
									: $this->sendResetLinkFailedResponse($request, $response);
		}

		protected function sendResetLinkResponse($request,$response){

				$emailTypeId = EmailType::Select(['id'])->checkEmailType('Forgot Password')->first()->id;
				if($emailTypeId > 0){
						// EmailLog::insert([
						// 	'intFkEmailType'=>$emailTypeId,
						// 	'varFrom'=>'netquick@netclues.com',
						// 	'txtTo'=> implode(',', $request->email),
						// 	'txtSubject'=>'Your Password Reset Link',
						// 	'chrIsSent'=>'Y',
						// 	'chrIpAddress'=>MyLibrary::get_client_ip()
						// ]);	
				}
				
				return back()->with('status', trans($response));
		}

}
