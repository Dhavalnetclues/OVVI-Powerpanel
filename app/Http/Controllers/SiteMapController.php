<?php

namespace App\Http\Controllers;

use Powerpanel\Menu\Models\MenuType;
use Powerpanel\Menu\Models\Menu;
use Spatie\Sitemap\SitemapGenerator;
use Illuminate\Support\Facades\URL;
use Powerpanel\CmsPage\Models\CmsPage;
use Powerpanel\Blogs\Models\Blogs;
use Powerpanel\Service\Models\Service;
use Powerpanel\News\Models\News;
use Powerpanel\Team\Models\Team;
use Illuminate\Support\Facades\Request;
use Config;

class SiteMapController extends FrontController {

    public function __construct() {
        parent::__construct();
    }

    public function index() {

		$pages_data = CmsPage::sitemap();
		$blogs_data = Blogs::sitemap();
		$news_data = News::sitemap();
		$events_data = News::sitemap();
		$services_data = Service::sitemap();
		$team_data = Team::sitemap();

		return view('sitemap',compact('pages_data','blogs_data','news_data','events_data','services_data','team_data'));
	}

    public function sitemapxml(){
		if(realpath(public_path('sitemap.xml'))){
			unlink(public_path('sitemap.xml'));
		}
		$data=[];

		$CmsPage = CmsPage::sitemap();
        $blogsPages = Blogs::sitemap();
		$newsPages = News::sitemap();
		$eventsPages = News::sitemap();
		$servicesPages = Service::sitemap();
		$teamPages = Team::sitemap();

		foreach($CmsPage as $CmsPage_value){
			$date=$CmsPage_value['updated_at'];
			if(empty($CmsPage_value['updated_at']) && $CmsPage_value['updated_at']==''){
				$date=$CmsPage_value['created_at'];
			}
            $cmsPageVal = isset($CmsPage_value['alias']['varAlias']) ? $CmsPage_value['alias']['varAlias'] : '-';
			$data_list=array(
				// 'type'=>'Cms Page',
				// 'id'=>$CmsPage_value['id'],
				// 'name'=>$CmsPage_value['varTitle'],
				'loc'=>url($cmsPageVal),
				'lastmod'=>date("Y-m-d",strtotime($date)),
			);
			array_push($data,$data_list);
		}

        foreach($blogsPages as $BlogsPage_value){
			$date = $BlogsPage_value['updated_at'];
			if(empty($BlogsPage_value['updated_at']) && $BlogsPage_value['updated_at']==''){
				$date = $BlogsPage_value['created_at'];
			}
            $blogsPageVal = isset($BlogsPage_value['alias']['varAlias']) ? $BlogsPage_value['alias']['varAlias'] : '-';
			$data_list=array(
                'loc'=>url('/blogs').'/'.$blogsPageVal,
				'lastmod'=>date("Y-m-d",strtotime($date)),
			);
			array_push($data,$data_list);
		}

        foreach($newsPages as $NewsPage_value){
			$date = $NewsPage_value['updated_at'];
			if(empty($NewsPage_value['updated_at']) && $NewsPage_value['updated_at']==''){
				$date = $NewsPage_value['created_at'];
			}
            $newsPageVal = isset($NewsPage_value['alias']['varAlias']) ? $NewsPage_value['alias']['varAlias'] : '-';
			$data_list=array(
				'loc'=>url($cmsPageVal),
                'loc'=>url('/news').'/'.$newsPageVal,
				'lastmod'=>date("Y-m-d",strtotime($date)),
			);
			array_push($data,$data_list);
		}

        foreach($eventsPages as $EventsPage_value){
			$date = $EventsPage_value['updated_at'];
			if(empty($EventsPage_value['updated_at']) && $EventsPage_value['updated_at']==''){
				$date = $EventsPage_value['created_at'];
			}
            $eventsPageVal = isset($EventsPage_value['alias']['varAlias']) ? $EventsPage_value['alias']['varAlias'] : '-';
			$data_list=array(
                'loc'=>url('/events').'/'.$eventsPageVal,
				'lastmod'=>date("Y-m-d",strtotime($date)),
			);
			array_push($data,$data_list);
		}

        foreach($servicesPages as $servicesPage_value){
			$date = $servicesPage_value['updated_at'];
			if(empty($servicesPage_value['updated_at']) && $servicesPage_value['updated_at']==''){
				$date = $servicesPage_value['created_at'];
			}
            $servicePageVal = isset($servicesPage_value['alias']['varAlias']) ? $servicesPage_value['alias']['varAlias'] : '-';
			$data_list=array(
				'loc'=>url('/service').'/'.$servicePageVal,
				'lastmod'=>date("Y-m-d",strtotime($date)),
			);
			array_push($data,$data_list);
		}

        foreach($teamPages as $teamPage_value){
			$date = $teamPage_value['updated_at'];
			if(empty($teamPage_value['updated_at']) && $teamPage_value['updated_at']==''){
				$date = $teamPage_value['created_at'];
			}
            $teamPageVal = isset($teamPage_value['alias']['varAlias']) ? $teamPage_value['alias']['varAlias'] : '-';
			$data_list=array(
				'loc'=>url('/team').'/'.$teamPageVal,
				'lastmod'=>date("Y-m-d",strtotime($date)),
			);
			array_push($data,$data_list);
		}

		// $file_contents = View::make('sitemap', $data);
		// $response = Response::make($file_contents, 200)->header('Content-Type', 'application/xml');

		$xmlObj = new \SimpleXMLElement("<?xml version='1.0' encoding='UTF-8'?><urlset xmlns='http://www.sitemaps.org/schemas/sitemap/0.9'></urlset>");
		$this->array_to_xml($data, $xmlObj);
		//file_put_contents('sitemap.xml',$xmlObj->saveXML());
		// $xml_version="1.0";
		return response()->view('sitemapxml',compact('data'))->header('Content-Type', 'text/xml');
		// return redirect()->route('sitemap.xml');
		// return response()->view('sitemapxml', compact('data', 'xml_version'))->header('Content-Type', 'text/xml');

		// return Response::download(public_path().'/sitemap.xml');
	}


	function array_to_xml($data, $xmlObj) {
		foreach($data as $key => $value) {
			if(is_array($value)) {
				$subnode = $xmlObj->addChild("url");
				$this->array_to_xml($value, $subnode);
			}else {
				$xmlObj->addChild("$key",htmlspecialchars($value));
				}
		}
		return $xmlObj;
	}

	function slugify($str) {
		$search = array('Ș', 'Ț', 'ş', 'ţ', 'Ş', 'Ţ', 'ș', 'ț', 'î', 'â', 'ă', 'Î', 'Â', 'Ă', 'ë', 'Ë');
		$replace = array('s', 't', 's', 't', 's', 't', 's', 't', 'i', 'a', 'a', 'i', 'a', 'a', 'e', 'E');
		$str = str_ireplace($search, $replace, strtolower(trim($str)));
		$str = preg_replace('/[^\w\d\-\ ]/', '', $str);
		$str = str_replace(' ', '-', $str);
		return preg_replace('/\-{2,}/', '-', $str);
	}

    // public function index() {
    //     $allMenu = Menu::getAllMenuItems();

    //     $siteMap = '';
    //     if(isset($allMenu[1]) && !empty($allMenu[1])){
    //         $siteMap = Self::getMenuByTypeID($allMenu[1]);
    //     }

    //     $footerMenu  = '';
    //     if(isset($allMenu[2]) && !empty($allMenu[2])){
    //         $footerMenu .= '<ul class="nqul default-nav">';
    //         $footerMenu .= Self::getMenuByTypeID($allMenu[2]);
    //         $footerMenu .= '</ul>';
    //     }

    //     return view('sitemap', compact('siteMap', 'footerMenu'));
    // }

    // public static function getMenuByTypeID($menuItems) {

    //     $html = '';
    //     $menuArr = Self::buildTree($menuItems);

    //     foreach ($menuArr as $navmenu) {
    //         // dd($navmenu);

    //         if ($navmenu['intParentMenuId'] == 0) {
    //             $html .= '<li>';

    //             $menuURL = url($navmenu['txtPageUrl']);
    //             $currentURL = URL::current();

    //             $class = '';
    //             if ($menuURL == $currentURL) {
    //                 $class = 'active';
    //             }

    //             $html .= '<a  href="' . $menuURL . '" title="' . ucfirst($navmenu['varTitle']) . '" >' . ucfirst($navmenu['varTitle']) . '</a>';

    //                 // $moduleRecords = Blogs::
    //                 // $html .= '<ul>';
    //                 // $html .= '<li>ASD</li>';
    //                 // $html .= '</ul>';

    //             $html .= Self::getChildMenuItem($navmenu);
    //             $html .= '</li>';
    //         }
    //     }
    //     return $html;
    // }

    // public static function getChildMenuItem($navmenu) {

    //     $html = '';
    //     if (isset($navmenu['children']) && count($navmenu['children']) > 0) {
    //         $html .= '<ul>';
    //         foreach ($navmenu['children'] as $nav) {
    //             $html .= '<li>';

    //             $menuURL = url($nav['txtPageUrl']);
    //             $currentURL = URL::current();

    //             $class = '';
    //             if ($menuURL == $currentURL) {
    //                 $class = 'active';
    //             }

    //             $html .= '<a href="' . url($nav['txtPageUrl']) . '" title="' . ucfirst($nav['varTitle']) . '">' . ucfirst($nav['varTitle']) . '</a>';
    //             if (isset($nav['children']) && count($nav['children']) > 0) {
    //                 $html .= Self::getChildMenuItem($nav);
    //             }
    //             $html .= '</li>';
    //         }
    //         $html .= '</ul>';
    //     }
    //     return $html;
    // }

    // public function generateSitemap() {
    //     $generatedSitemap = SitemapGenerator::create(url('/'))->writeToFile(Config::get('Constant.LOCAL_CDN_PATH') . '/sitemap.xml');
    //     if ($generatedSitemap) {
    //         return redirect(url('/sitemap.xml'));
    //     }
    // }

    // public static function buildTree(array $elements, $parentId = 0) {

    //     $branch = array();
    //     foreach ($elements as $element) {
    //         if ($element['intParentMenuId'] == $parentId) {
    //             $children = Self::buildTree($elements, $element['id']);
    //             if ($children) {
    //                 $element['children'] = $children;
    //             }
    //             $branch[] = $element;
    //         }
    //     }
    //     return $branch;
    // }

}
