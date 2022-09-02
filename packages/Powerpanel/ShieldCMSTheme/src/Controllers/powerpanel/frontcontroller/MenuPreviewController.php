<?php 
namespace App\Http\Controllers;

use Request;
use Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use Powerpanel\Menu\Models\Menu;
use Powerpanel\Menu\Models\MenuType;
use App\Helpers\Menu_builder;

class MenuPreviewController extends Controller {
		
	public function index(Request $request)
	{
		Menu_builder::load_menu(1,'headerMenu');
		Menu_builder::load_menu(2,'footerMenu');
		return view('powerpanel.menu.menuPreview');
	}
}