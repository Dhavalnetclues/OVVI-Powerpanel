<?php
namespace App\Http\Controllers\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\PowerpanelController;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Foundation\Auth\ResetsPasswords;
use App\Helpers\MyLibrary;
use App\EmailLog;
use Illuminate\Support\Facades\Password;
use Auth;

class ResetPasswordController extends PowerpanelController
{
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
		* Where to redirect users after resetting their password.
		*
		* @var string
		*/
		protected $redirectTo = 'powerpanel/dashboard';
		/**
		* Create a new controller instance.
		*
		* @return void
		*/
		public function __construct()
		{
						//$this->middleware('guest');
		}
		/**
		* Get the password reset validation rules.
		*
		* @return array
		*/
		protected function rules()
		{
				return [
						'token' => 'required|handle_xss',
						'email' => 'required|email|handle_xss',
						'password' => 'required|confirmed|min:6|max:20|check_passwordrules|handle_xss',
						'password_confirmation' => 'required|min:6|max:20|check_passwordrules|handle_xss',
				];
		}
		public function sendResetLinkAjax(Request $request, PasswordBroker $passwords)
		{
			$response = false;
			if( $request->ajax() )
			{

					$this->validate($request, ['email' => 'required|email']);

					$requestedEmail = $request->only('email');
					$request->merge(['email' => MyLibrary::getEncryptedString($requestedEmail['email'])]);

					$response = $this->broker()->sendResetLink(
							$request->only('email')
					);

					switch ($response)
					{
						case PasswordBroker::RESET_LINK_SENT:
						$response = array(
										'error'=>'false',
										'msg'=>'A password link has been sent to your email address'
										);
							return json_encode($response);
						case PasswordBroker::INVALID_USER:
						$response = array(
												'error'=>'true',
												'msg'=>"We can't find a user with that email address"
											);
						return json_encode($response);
					}
			}

			return $response;
		}

		/**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function reset(Request $request)
    {

        $this->validate($request, $this->rules(), $this->validationErrorMessages());

        $requestedEmail = $request->only('email');
				$request->merge(['email' => MyLibrary::getEncryptedString($requestedEmail['email'])]);

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $response = $this->broker()->reset(
            $this->credentials($request), function ($user, $password) {
                $this->resetPassword($user, $password);
            }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        return $response == Password::PASSWORD_RESET
                    ? $this->sendResetResponse($response)
                    : $this->sendResetFailedResponse($request, $response);
    }

    public function sendResetResponse($response){
    	Auth::logout();
			return redirect('powerpanel/login')->with('message','Your password has been successfully reset');
    }
}