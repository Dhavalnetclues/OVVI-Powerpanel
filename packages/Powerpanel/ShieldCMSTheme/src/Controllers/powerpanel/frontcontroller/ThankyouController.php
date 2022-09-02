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
        if (Session::get('form_submit')) {
            view()->share('META_TITLE', "Thank You for contacting - Central Bank of Bahamas");
            view()->share('META_KEYWORD', "Thank You");
            view()->share('META_DESCRIPTION', "Thank You for contacting Central Bank of Bahamas get back to you shortly");
            return view('thank-you', ['message' => Session::get('message')]);
        } else {
            return redirect('/');
        }
    }

    public function subscribe_failed(Request $request) {
        if (Session::get('form_submit')) {
            view()->share('META_TITLE', "Thank You for contacting - Central Bank of Bahamas");
            view()->share('META_KEYWORD', "Thank You");
            view()->share('META_DESCRIPTION', "Thank You for contacting Central Bank of Bahamas get back to you shortly");
            return view('failed', ['message' => Session::get('message')]);
        } else {
            return redirect('/');
        }
    }

}
