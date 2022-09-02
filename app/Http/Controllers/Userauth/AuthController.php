<?php

namespace App\Http\Controllers\Userauth;

use App\Http\Controllers\FrontController;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Front_user;
use Validator;
use Illuminate\Contracts\Auth\Guard;
use Auth;
use Session;

class AuthController extends FrontController 
{
	/*
	|--------------------------------------------------------------------------
	| Registration & Login Controller
	|--------------------------------------------------------------------------
	|
	| This controller handles the registration of new users, as well as the
	| authentication of existing users. By default, this controller uses
	| a simple trait to add these behaviors. Why don't you explore it?
	|
	*/
	use AuthenticatesUsers;
	/**
	* Where to redirect users after login / registration.
	*
	* @var string
	*/
	protected $redirectTo = '/';
	protected $redirectAfterLogout = '/login';
	protected $guard = 'front-user';
	/**
	* Create a new authentication controller instance.
	*
	* @return void
	*/
	public function __construct() {
			
		parent::__construct();
		if(isset($_COOKIE['locale'])){
			app()->setLocale($_COOKIE['locale']);
		}
		$this->middleware('guest')->except('logout');
	}
	/**s
	* Get a validator for an incoming registration request.
	*
	* @param  array  $data
	* @return \Illuminate\Contracts\Validation\Validator
	*/
	protected function validator(array $data) 
	{

			return Validator::make($data, [
	        'name' => 'required|max:255',
	        'email' => 'required|email|max:255|unique:front_users',
	        'password' => 'required|min:6|confirmed',
	    ]);
	}

	/**
	* Create a new user instance after a valid registration.
	*
	* @param  array  $data
	* @return User
	*/
	protected function create(array $data) 
	{
		return Front_user::create([
			'name' => $data['name'],
			'email' => $data['email'],
			'password' => bcrypt($data['password']),
		]);
		
	}

	public function showLoginForm()
	{	
	 	if(view()->exists('auth.authenticate')){
        return view('auth.authenticate');
    }	
		return view('user.auth.login');	
	}

	public function showRegistrationForm()
	{
		return view('user.auth.register');
	}

	
	
}
