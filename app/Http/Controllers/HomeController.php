<?php

namespace App\Http\Controllers;
use Powerpanel\Banner\Models\Banner;

class HomeController extends FrontController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = array();
        $bannerObj = Banner::getHomeBannerList();
        if (!empty($bannerObj) && count($bannerObj) > 0) {
            $data['bannerData'] = $bannerObj;
        }

        return view('index', $data);
    }

}
