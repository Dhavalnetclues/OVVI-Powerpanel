<?php
namespace App\Http\Controllers;
use Request;
use Input;
use App\Alias;
use Powerpanel\Menu\Models\Menu;
use App\Http\Traits\slug;
use App\Modules;
use Powerpanel\CmsPage\Models\CmsPage;
use DB;
use App\Image;
use Powerpanel\Banner\Models\Banner;
use App\GeneralSettings;
use App\team;
use App\NewsCategory;


class InstallerController extends FrontController
{
		use slug;	
		/**
		 * Create a new controller instance.
		 *
		 * @return void
		 */
		public function __construct()
		{
				 parent::__construct();
				 
				 //$this->update_banner_image();
				 //$this->update_front_logo_id();
				 //$this->add_team_alias();
				 //$this->add_news_category_alias();
		}

		/**
		 * Show the application dashboard.
		 *
		 * @return \Illuminate\Http\Response
		 */
		public function index()
		{   
						abort(404);
		}

	public function add_cms_page_alias()
	{   
				$response = 'false';
				$module_slug_arr = Input::all();
				$CmsPages  = CmsPage::getRecords()->deleted()->get();

				$getPage = CmsPage::where('varTitle','=','Home')->first();
				$cms_module_code =	Modules::getRecords()->deleted()->getModuleId('pages')->first();

				$home_alias = new Alias;
				$home_alias['intFkModuleCode'] = $cms_module_code->id;
				$home_alias['varAlias']  = 'home';
				$home_alias['varIpAddress'] = Request::ip();	
				$home_alias->save();

				foreach ($CmsPages as $key => $page) 
				{		
					$slug = slug::create_slug($page->var_title);
					if(array_key_exists($slug, $module_slug_arr))
					{
							$alias = new Alias;
							$alias['intFkModuleCode'] = $cms_module_code->int_code;
							$alias['varAlias']  = $slug;
							$alias['varIpAddress'] = Request::ip();
							if($alias->save())
							{
								$response = 'true';
							}			
							$module_code =	DB::table('modules')->where('var_module_name','=',$slug)->first();		
							
							CmsPage::where('module_slug', $slug)
		          				->update(['module_code' => $module_code->int_code]);

					}else{


							$alias = new Alias;
							$alias['fk_module_code'] = $cms_module_code->int_code;
							$alias['fk_record_code'] = $page->id;
							$alias['alias']  = $slug;
							$alias['ip_address'] = Request::ip();
							if($alias->save())
							{
								$response = 'true';
							}
							
							CmsPage::where('module_slug', $slug)
		          				->update(['module_code' => $cms_module_code->int_code]);

					}
				}
				return $response;
		}

		public function update_banner_image()
		{	

				$images	 = Image::all();
				$banner = Banner::all();

				$bannerArr = array();
				foreach ($banner as $key => $b_img) 
				{
						$bannerArr[$b_img['image']] = $b_img['id']; 
				}

				foreach ($images as $key => $value) 
				{
				    $image_name = $value->txt_image_alt_tag.'.'.$value->var_image_extension;
						if(array_key_exists($image_name, $bannerArr))
						{
								Banner::where('image', $image_name)->update(['image' => $value->id]);
						}
				}

		}

		public function update_front_logo_id()
		{	
			
			$images	 = Image::all();
			$settings = GeneralSettings::all();
			foreach ($images as $key => $value) 
			{
			    $image_name = $value->txt_image_alt_tag.'.'.$value->var_image_extension;
					if($image_name == "logo.png")
					{
							GeneralSettings::where('field_name', 'FRONT_LOGO_ID')->update(['field_value' => $value->id]);
					}
			}
		}


		public function add_team_alias()
		{
			
			$objTeam = team::all();

			foreach ($objTeam as $key => $team) 
			{		
						$slug = slug::create_slug($team->name);
						$team_module_code =	DB::table('modules')->where('var_module_name','=','team')->first();
						$alias = new Alias;
						$alias['fk_module_code'] = $team_module_code->int_code;
						$alias['fk_record_code'] = $team->id;
						$alias['alias']  = $slug;
						$alias['ip_address'] = Request::ip();
						$alias->save();
			}

		}

		public function add_news_category_alias()
		{

			$objNewsCategory = NewsCategory::all();
			foreach ($objNewsCategory as $key => $value) 
			{
						$slug = slug::create_slug($value->name);
						$news_category_module_code =	DB::table('modules')->where('var_module_name','=','news-category')->first();
						$alias = new Alias;
						$alias['fk_module_code'] = $news_category_module_code->int_code;
						$alias['fk_record_code'] = $value->id;
						$alias['alias']  = $slug;
						$alias['ip_address'] = Request::ip();
						$alias->save();
			}

		}

}
