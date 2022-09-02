<?php
namespace App\Http\Controllers\Userauth;
//use App\Http\Controllers\PowerpanelController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Contracts\Auth\PasswordBroker;

class PasswordController extends Controller
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
    * Create a new password controller instance.
    *
    * @return void
    */
    protected $redirectTo = '/login';
    protected $guard = 'front-user';
    protected $broker = 'front-users';

    public function __construct()
    {
       // parent::__construct();
      
       $this->middleware('guest:front-user');

        if(isset($_COOKIE['locale'])){
            app()->setLocale($_COOKIE['locale']);
        }
    }

    public function getEmail()
    {
        return $this->showLinkRequestForm();
    }

    public function showLinkRequestForm()
    {
        if (property_exists($this, 'linkRequestView')) {
            return view($this->linkRequestView);
        }

        if (view()->exists('user.auth.passwords.email')) {
            return view('user.auth.passwords.email');
        }

        return view('user.auth.password');
    }

    public function showResetForm(Request $request, $token = null)
    {

        if (is_null($token)) {
            return $this->getEmail();
        }
        $email = $request->input('email');

        if (property_exists($this, 'resetView')) {
            return view($this->resetView)->with(compact('token', 'email'));
        }

        if (view()->exists('user.auth.passwords.reset')) {
            return view('user.auth.passwords.reset')->with(compact('token', 'email'));
        }

        return view('user.passwords.auth.reset')->with(compact('token', 'email'));
    }
}