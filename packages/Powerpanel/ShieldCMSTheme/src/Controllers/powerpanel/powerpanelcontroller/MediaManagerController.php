<?php

namespace App\Http\Controllers\Powerpanel;

use App\Http\Controllers\PowerpanelController;
use Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Contracts\Auth\Guard;
use Validator;
use App\Image;
use App\RecentUpdates;
use App\Searchentity;
use Hash;
use Illuminate\Routing\UrlGenerator;
use DB;
use Auth;
use File;
use App\Modules;
use App\Helpers\resize_image;
use App\User;
use App\Helpers\MyLibrary;

class MediaManagerController extends PowerpanelController {

    public function __construct(UrlGenerator $url) {
        parent::__construct();
        $this->url = $url;
        if (isset($_COOKIE['locale'])) {
            app()->setLocale($_COOKIE['locale']);
        }
    }

    public function index(Guard $auth) {

        $this->breadcrumb['title'] = "Media Manager";
        return view('powerpanel.mediamanagersidebar.list', ['breadcrumb' => $this->breadcrumb, 'videoManager' => true]);
    }

}
