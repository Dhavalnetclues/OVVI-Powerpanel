<?php

namespace App\Http\Controllers;

use App\Helpers\Menu_builder;
use App\Http\Controllers\Controller;
use Request;

class MenuPreviewController extends Controller
{

    public function index(Request $request)
    {

        Menu_builder::load_menu(1, 'headerMenu');

        Menu_builder::load_menu(2, 'footerMenu');

        return view('menu::powerpanel.menuPreview');

    }

}
