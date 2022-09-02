<?php

namespace App\Http\Controllers;

use Powerpanel\Menu\Models\MenuType;
use Powerpanel\Menu\Models\Menu;

use Spatie\Sitemap\SitemapGenerator;
use Config;

class SiteMapController extends FrontController {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $menu_array = $this->buildMenu();
        $siteMap = $this->make_menu(0, "", $menu_array);
        return view('sitemap', compact('siteMap'));
    }

    /**
     * This method handels loading process of generating array from menu data
     * @return  Menu array
     * @since   04-08-2017
     * @author  NetQuick
     */
    public function buildMenu($position = null) {
        if ($position == null) {
            $position = 1;
        }
        $response = false;
        $menu_array = array();
        $result = $this->sitemap_content;
        if (!empty($result[$position])) {
            
            foreach ($result[$position] as $menuItem) {
            
            
                $menu_array['items'][$menuItem->id] = array(
                    'id' => $menuItem->id,
                    'pid' => $menuItem->intParentMenuId,
                    'title' => $menuItem->varTitle,
                    'url' => $menuItem->txtPageUrl,
                    'active' => $menuItem->chrActive,
                    'position' => $menuItem->intPosition,
                    'mega_menu' => $menuItem->chrMegaMenu,
                    'chrInMobile' => $menuItem->chrInMobile,
                    'chrInWeb' => $menuItem->chrInWeb,
                    'chr_publish' => $menuItem->chrPublish
                );
                $menu_array['parents'][$menuItem->intParentMenuId][] = $menuItem->id;
            }
        }
        $response = $menu_array;
        return $response;
    }

    public function make_menu($parentId = false, $parentUrl = false, $menu_array = false) {
        $parent_order = 1;
        $response = false;
        $active = false;
        $html = '';

        if (isset($menu_array['parents'][$parentId])) {
            $child_order = 1;
            $html = '';
            foreach ($menu_array['parents'][$parentId] as $itemId) {
                if (strtolower($menu_array['items'][$itemId]['url']) != 'sitemap') {
                    $child = array_column($menu_array['items'], 'pid');
                    $hasChild = (in_array($itemId, $child)) ? true : false;
                    $active = $menu_array['items'][$itemId]['active'];
                    $chr_publish = $menu_array['items'][$itemId]['chr_publish'];
                    $cur_url = $menu_array['items'][$itemId]['url'];
                    $html .= '<li>';
                    $html .= '<a href="' . $cur_url . '" title="' . $menu_array['items'][$itemId]['title'] . '" >';

                    if ($menu_array['items'][$itemId]['pid'] < 1) {
                        if ($menu_array['items'][$itemId]['id'] == 1) {
                            $Icon_class = "fa fa-info-circle";
                        } elseif ($menu_array['items'][$itemId]['id'] == 19) {
                            $Icon_class = "fa fa-ioxhost";
                        } elseif ($menu_array['items'][$itemId]['id'] == 53) {
                            $Icon_class = "fa fa-usd";
                        } elseif ($menu_array['items'][$itemId]['id'] == 57) {
                            $Icon_class = "fa fa-users";
                        } elseif ($menu_array['items'][$itemId]['id'] == 73) {
                            $Icon_class = "fa fa-address-book";
                        } else {
                            $Icon_class = "";
                        }
                        $html .='<span class="sitemap__icon"><i class="' . $Icon_class . '"></i></span><span class="sitemap__main-menu">';
                        $html .= $menu_array['items'][$itemId]['title'] . '</span>';
                    } else {
                        $html .= $menu_array['items'][$itemId]['title'];
                    }
                    $html .= '</a>';
                    if ($hasChild) {
                        $html .= '<ul>';
                    }
                    $html .= Self::make_menu($itemId, $cur_url, $menu_array);
                    $html .= '</li>';
                    if ($hasChild) {
                        $html .= '</ul>';
                    }
                    $parent_order++;
                    $child_order++;
                }
            }
            $html .= '';
        }
        $response = $html;
        return $response;
    }

    public function generateSitemap() {
        $generatedSitemap = SitemapGenerator::create(url('/'))->writeToFile(Config::get('Constant.LOCAL_CDN_PATH') . '/sitemap.xml');
        if ($generatedSitemap) {
            return redirect(url('/sitemap.xml'));
        }
    }

}
