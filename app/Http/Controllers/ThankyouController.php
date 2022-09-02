<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redirect;
use Request;
use Session;
use Config;

class ThankyouController extends FrontController {

    public function __construct() {
        parent::__construct();
    }

    public function index(Request $request) {
        
        // if (Session::get('form_submit')) {
            
            $data = Request::all();
            $message = 'Thank You for interest we will get back to you shortly';
            if(isset($data['message']) && !empty($data['message'])) {
                $message = $data['message'];
            }else if(!empty(Session::get('message'))){
                $message = Session::get('message');
            }

            view()->share('META_TITLE', $message);
            view()->share('META_DESCRIPTION', $message);

            return view('thank-you', ['message' => $message]);

        // } else {
        //     return redirect('/');
        // }
    }
    
    public function success(Request $request) {
        
        if (Session::get('form_submit')) {
             view()->share('META_TITLE', 'Thank You');
            view()->share('META_KEYWORD', 'Thank You');
            view()->share('META_DESCRIPTION', 'Thank You');
            return view('subscribed', ['message' => Session::get('message')]);
        } else {
            return redirect('/');
        }
    }
    
    public function unsubscribed(Request $request) {
      
        if (Session::get('form_submit')) {
            view()->share('META_TITLE', 'Thank You');
            view()->share('META_KEYWORD', 'Thank You');
            view()->share('META_DESCRIPTION','Thank You');
            return view('unsubscribed', ['message' => Session::get('message')]);
        } else {
            return redirect('/');
        }
    }

 
       public function subscribe_failed(Request $request) {
        if (Session::get('form_submit')) {
            view()->share('Access Denied');
            view()->share('Access Denied');
            view()->share('Access Denied');
            return view('access_denied', ['message' => Session::get('message')]);
        } else {
            return redirect('/');
        }
    }

}
