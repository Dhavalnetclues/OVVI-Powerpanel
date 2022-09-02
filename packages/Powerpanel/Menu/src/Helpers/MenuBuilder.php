<?php

/**

 * The MenuBuilder class generates dynamic menu

 * configuration  process (ORM code Updates).

 * @package   Netquick powerpanel

 * @license   http://www.opensource.org/licenses/BSD-3-Clause

 * @version   1.1

 * @since     2017-08-09

 * @author    NetQuick

 */

namespace App\Helpers;

use Config;
use Lavary\Menu\Menu;

class MenuBuilder
{

    public static $arrMenu = false;

    /**

     * This method loads dynamic menu

     * @return  Menu array

     * @since   2017-08-09

     * @author  NetQuick

     */

    public static function loadMenu($menu_content = null, $name = null)
    {

        $isMobile = Config::get('Constant.DEVICE');

        $menu = new Menu;

        if ($menu_content != null) {

            $menu->make($name, function ($menu) use ($menu_content, $name, $isMobile) {

                $arrParents = array();

                foreach ($menu_content as $element) {

                    if ((int) $element->intParentMenuId > 0) {

                        $arrParents[] = (int) $element->intParentMenuId;

                    }

                }

                $arrData = self::getMenuArray($menu_content, 0, $menu, $name, $arrParents, $isMobile);

            });

        }

    }

    /**

     * This method generates menu data for dynamic menu

     * @return  Menu array

     * @since   2017-08-09

     * @author  NetQuick

     */

    public static function getMenuArray($elements = false, $parentId = 0, $menu = false, $name = false, $arrParents = array(), $isMobile = false)
    {

        $parentCheck = $arrParents;

        $parentMegaMenu = [];

        $branch = array();

        foreach ($elements as $element) {

            if (($isMobile == 'mobile' && $element->chrInMobile == 'Y') || ($isMobile != 'mobile' && $element->chrInWeb == 'Y')) {

                if ($element->intParentMenuId && $element->intParentMenuId > 0) {

                    if ($element->chrMegaMenu == 'Y') {

                        array_push($parentMegaMenu, $element->intParentMenuId);

                    }

                }

                if ($element->intParentMenuId == $parentId) {

                    $attr = array();

                    if (in_array($element->id, $parentCheck)) {

                        //$attr['class'] = 'dropdown';

                        if ($element->chrMegaMenu == 'Y') {

                            $attr['class'] .= ' multi-level';

                        }

                    }

                    if (in_array($element->intParentMenuId, $parentMegaMenu)) {

                        $inMegamenu = 'Y';

                    } else {

                        $inMegamenu = 'N';

                    }

                    $url = $element->txtPageUrl;

                    $title = $element->varTitle;

                    if (strtolower($title) == 'home' && $element->intPosition == 1) {

                        $title = 'Home';

                    }

                    $subMenu = $menu->add($title, $attr)->active($element->txtPageUrl . '/*');

                    $arrAttr = array();

                    if (in_array($element->id, $parentCheck)) {

                        //$arrAttr['class'] = 'dropdown-toggle link';

                    } else {

                        //$arrAttr['class'] = 'link';

                    }

                    $arrAttr['title'] = $element->varTitle;

                    $arrAttr['data-chrMegaMenu'] = $element->chrMegaMenu;

                    $arrAttr['data-inMegamenu'] = $inMegamenu;

                    if (filter_var($url, FILTER_VALIDATE_URL)) {

                        $arrAttr['href'] = $url;

                    } else {

                        $arrAttr['href'] = url($url);

                    }

                    $subMenu->link->attr($arrAttr);

                    $children = self::getMenuArray($elements, $element->id, $subMenu, false, $arrParents, $isMobile);

                    if ($children) {

                        $element->children = $children;

                    }

                    $branch[] = $element;

                }

            }

        }

        return $branch;

    }

}
