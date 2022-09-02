<?php
namespace Powerpanel\Menu\Models;

use Cache;
use Config;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

class Menu extends Model
{
    protected $table = 'menu';
    protected $fillable = [
        'id',
        'intParentMenuId',
        'intItemOrder',
        'intParentItemOrder',
        'chrInMobile',
        'chrInWeb',
        'intAliasId',
        'intPageId',
        'varTitle',
        'txtPageUrl',
        'intPosition',
        'chrActive',
        'chrDisplayFront',
        'chrMegaMenu',
        'chrDelete',
        'chrPublish',
        'created_at',
        'updated_at',
    ];

    public static function getFrontList($check_front = '')
    {
        $response = null;
        $response = Cache::tags(['Menu'])->get('getFrontMenuList');
        if (empty($response)) {
            $menuTypeFields = ['id', 'varTitle', 'chrPublish'];
            $menuFields =
                [
                'id',
                'varTitle',
                'chrPublish',
                'chrDisplayFront',
                'intItemOrder',
                'intParentItemOrder',
                'chrInWeb',
                'chrInMobile',
                'chrActive',
                'intParentMenuId',
                'intPosition',
                'txtPageUrl',
                'chrMegaMenu',
            ];
            $menuObj = Self::getRecords($menuFields, $menuTypeFields)->deleted();
            //$menuObj = Self::getRecords($menuFields, $menuTypeFields)->deleted()->active();
            if (strtolower(Request::segment(1)) != 'powerpanel') {
                $menuObj = $menuObj->publish()->active()->displayFront();
            }
            if ($check_front == 'Y') {
                $menuObj = $menuObj->publish()->active()->displayFront();
            }
            $menuObj = $menuObj->orderBy('intParentItemOrder', 'ASC')
                ->orderBy('intItemOrder', 'ASC')
                ->get();
            if (!empty($menuObj)) {
                $response = array();
                foreach ($menuObj as $data) {
                    $id = $data->intPosition;
                    if (isset($response[$id])) {
                        $response[$id][] = $data;
                    } else {
                        $response[$id] = array($data);
                    }
                }
                asort($response);
            }
            Cache::tags(['Menu'])->forever('getFrontMenuList', $response);
        }
        return $response;
    }

    public static function updateMenuPos($ids, $data)
    {
        Self::whereIn('id', $ids)->update($data);
        Self::whereIn('intParentMenuId', $ids)->update($data);
    }

    public static function updateMenuInDevices($ids, $data)
    {
        Self::whereIn('id', $ids)
            ->update($data);
    }

    public static function inactiveMenusNotInAnyDevice()
    {
        Self::where('chrInMobile', 'N')
            ->where('chrInWeb', 'N')
            ->update(['chrActive' => 'N']);
    }

    public static function javascriptVoidMenus()
    {
        $response = Self::select('intPageId')
            ->where('txtPageUrl', 'javascript:;')
            ->whereRaw('intPageId is not null')
            ->get();
        return $response;
    }

    public static function getHerderMenuItem()
    {
        $response = null;
        $device = Config::get('Constant.DEVICE');
        $isMobile = false;
        if ($device == 'mobile') {
            $isMobile = true;
        }
        $ispc = false;
        if ($device == 'pc') {
            $ispc = true;
        }
        $menuFields = ['id', 'intParentMenuId', 'intPosition', 'intItemOrder', 'intParentItemOrder', 'intAliasId', 'intRecordId', 'intPageId', 'intfkModuleId', 'varTitle', 'txtPageUrl', 'chrDelete', 'chrPublish', 'chrActive'];
        $response = Self::getRecords($menuFields)
        //->where('intParentMenuId', '0')
            ->where('intPosition', '1');
        if ($isMobile == true) {
            $response = $response->where('chrInMobile', 'Y');
        }

        if ($ispc == true) {
            $response = $response->where('chrInWeb', 'Y');
        }
        $response = $response->orderBy('intPosition', 'ASC')
            ->orderBy('intParentItemOrder', 'ASC')
            ->orderBy('intItemOrder', 'ASC')
            ->deleted()
            ->publish()
            ->active()
            ->get();

        return $response;
    }

    public static function getAllMenuItems()
    {

        $response = false;

        $menuFields = ['id', 'intParentMenuId', 'varTitle', 'txtPageUrl', 'intPosition'];
        $query = Self::select($menuFields)
            ->orderBy('intPosition', 'ASC')
            ->orderBy('intParentItemOrder', 'ASC')
            ->orderBy('intItemOrder', 'ASC')
            ->deleted()
            ->publish()
            ->active();

        $data = array();

        $data['menuType'] = function ($query) {
            $query->select('id', 'varTitle')->where('chrPublish', 'Y')->deleted();
        };

        $response = $query->with($data)->get();

        $allMenuArr = array();
        if ($response->count() > 0) {
            foreach ($response as $key => $value) {
                //if($value->count() > 0){
                $allMenuArr[$value->intPosition][] = $value->toArray();
                //}
            }
        }
        return $allMenuArr;

    }

    public static function getMenuByTypeId($typeID)
    {

        $response = false;

        $menuFields = ['id', 'intParentMenuId', 'varTitle', 'txtPageUrl'];
        $response = Self::select($menuFields)
            ->where('intPosition', $typeID)
            ->orderBy('intPosition', 'ASC')
            ->orderBy('intParentItemOrder', 'ASC')
            ->orderBy('intItemOrder', 'ASC')
            ->deleted()
            ->publish()
            ->active()
            ->get();

        return $response;
    }
    
    
     public static function getMenuCmsById($typeID)
    {

        $response = false;

        $menuFields = ['id','intPageId', 'intParentMenuId', 'varTitle', 'txtPageUrl'];
        $response = Self::select($menuFields)
            ->where('intPageId', $typeID)
            ->orderBy('intPosition', 'ASC')
            ->orderBy('intParentItemOrder', 'ASC')
            ->orderBy('intItemOrder', 'ASC')
            ->deleted()
            ->publish()
            ->active()
            ->count();

        return $response;
    }

    public function childMenu()
    {
        return $this->hasMany('Powerpanel\Menu\Models\Menu', 'intParentMenuId', 'id');
    }

    public static function getHomeFooterFrontRecords()
    {
        $response = null;
        $device = Config::get('Constant.DEVICE');
        $isMobile = false;
        if ($device == 'mobile') {
            $isMobile = true;
        }
        $ispc = false;
        if ($device == 'pc') {
            $ispc = true;
        }
        $menuFields = ['id', 'intParentMenuId', 'intPosition', 'intItemOrder', 'intParentItemOrder', 'intAliasId', 'intRecordId', 'intPageId', 'intfkModuleId', 'varTitle', 'txtPageUrl', 'chrDelete', 'chrPublish', 'chrActive'];
        $response = Self::getRecords($menuFields)
            ->where('intParentMenuId', '0')
            ->where('intPosition', '2');
        if ($isMobile == true) {
            $response = $response->where('chrInMobile', 'Y');
        }
        if ($ispc == true) {
            $response = $response->where('chrInWeb', 'Y');
        }
        $response = $response->orderBy('intPosition', 'ASC')
            ->orderBy('intParentItemOrder', 'ASC')
            ->orderBy('intItemOrder', 'ASC')
            ->deleted()
            ->publish()
            ->active()
            ->get();
        return $response;
    }

    public static function getMenuByType($typeID)
    {
        $response = null;
        $device = Config::get('Constant.DEVICE');
        $isMobile = false;
        if ($device == 'mobile') {
            $isMobile = true;
        }
        $ispc = false;
        if ($device == 'pc') {
            $ispc = true;
        }

        $menuFields = ['id', 'intParentMenuId', 'intPosition', 'intItemOrder', 'intParentItemOrder', 'intAliasId', 'intRecordId', 'intPageId', 'intfkModuleId', 'varTitle', 'txtPageUrl', 'chrDelete', 'chrPublish', 'chrActive'];

        $response = Self::getRecords($menuFields)
            ->where('intPosition', $typeID);
        if ($isMobile == true) {
            $response = $response->where('chrInMobile', 'Y');
        }

        if ($ispc == true) {
            $response = $response->where('chrInWeb', 'Y');
        }
        $response = $response->orderBy('intPosition', 'ASC')
            ->orderBy('intParentItemOrder', 'ASC')
            ->orderBy('intItemOrder', 'ASC')
            ->deleted()
            ->publish()
            ->active()
            ->get();
        return $response;
    }

    public static function getChildMenuByType($id, $typeID)
    {

        $response = null;
        $device = Config::get('Constant.DEVICE');
        $isMobile = false;
        if ($device == 'mobile') {
            $isMobile = true;
        }
        $ispc = false;
        if ($device == 'pc') {
            $ispc = true;
        }

        $menuFields = ['id', 'intParentMenuId', 'intPosition', 'intItemOrder', 'intParentItemOrder', 'intAliasId', 'intRecordId', 'intPageId', 'intfkModuleId', 'varTitle', 'txtPageUrl', 'chrDelete', 'chrPublish', 'chrActive'];
        $response = Self::getRecords($menuFields)
            ->where('intParentMenuId', $id)
            ->where('intPosition', $typeID);
        if ($isMobile == true) {
            $response = $response->where('chrInMobile', 'Y');
        }
        if ($ispc == true) {
            $response = $response->where('chrInWeb', 'Y');
        }
        $response = $response->orderBy('intPosition', 'ASC')
            ->orderBy('intParentItemOrder', 'ASC')
            ->orderBy('intItemOrder', 'ASC')
            ->deleted()
            ->publish()
            ->active()
            ->get();

        return $response;
    }

    /**
     * This method handels retrival of record count
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getRecordById($id = false)
    {
        $response = null;
        $response = Self::getMenuItem($id);
        return $response;
    }
    public static function getMenuItem($itemId = false)
    {
        $response = null;
        $menuFields = ['id', 'varTitle', 'txtPageUrl', 'intAliasId', 'intPageId'];
        $response = Self::getRecords($menuFields)
            ->checkRecordId($itemId)
            ->first();
        return $response;
    }
    public static function getMenuItemByMenuType($intPosition = false)
    {
        $response = null;
        $menuFields = ['id', 'varTitle', 'txtPageUrl', 'intAliasId'];
        $response = Self::getRecords($menuFields)
            ->where('intPosition', $intPosition)
            ->first();
        return $response;
    }
    public static function getItemCount($whereArr = false)
    {
        $response = null;
        $menuFields = ['id'];
        $response = Self::getRecords($menuFields)
            ->whereCheck($whereArr)
            ->count();
        return $response;
    }

    public static function checkHasChild($parentId = false)
    {
        $response = null;
        $menuFields = ['id'];
        $response = Self::getRecords($menuFields)
            ->deleted()
            ->publish()
            ->checkParentRecordId($parentId)
            ->first();
        return $response;
    }

    #Config############################################################################

    /**
     * This method handels alias relation
     * @return  Object
     * @since   04-08-2017
     * @author  NetQuick
     */
    public function alias()
    {
        return $this->belongsTo('App\Alias', 'intAliasId', 'id');
    }
    /**
     * This method handels cms page relation
     * @return  Object
     * @since   04-08-2017
     * @author  NetQuick
     */
    public function page()
    {
        return $this->belongsTo('Powerpanel\CmsPage\Models\CmsPage', 'intPageId', 'id');
    }
    /**
     * This method handels menu type relation
     * @return  Object
     * @since   04-08-2017
     * @author  NetQuick
     */
    public function menuType()
    {
        return $this->belongsTo('Powerpanel\Menu\Models\MenuType', 'intPosition', 'id');
    }
    /**
     * This method handels retrival of menu records
     * @return  Object
     * @since   04-08-2017
     * @author  NetQuick
     */
    public static function getRecords($menuFields = false, $menuTypeFields = false, $aliasFields = false, $CmsPageFields = false)
    {
        $data = [];
        $response = null;
        if ($menuFields != false) {
            $response = Self::select($menuFields);
            if ($aliasFields != false) {
                $data['alias'] = function ($query) use ($aliasFields) {$query->select($aliasFields)->checkModuleCode();};
            }
            if ($menuTypeFields != false) {
                $data['menuType'] = function ($query) use ($menuTypeFields) {$query->select($menuTypeFields)->deleted();};
                //$data['menuType'] = function ($query) use ($menuTypeFields) {$query->select($menuTypeFields)->publish();};
            }
            if ($CmsPageFields != false) {
                $data['page'] = function ($query) use ($CmsPageFields) {$query->select($CmsPageFields);};
            }
            if (count($data) > 0) {
                $response = $response->with($data);
            }
        }
        return $response;
    }

    /**
     * This method handels record id scope
     * @return  Object
     * @since   04-08-2017
     * @author  NetQuick
     */
    public function scopeCheckRecordId($query, $id)
    {
        return $query->where('id', $id);
    }

    /**
     * This method handels parent record id scope
     * @return  Object
     * @since   04-08-2017
     * @author  NetQuick
     */
    public function scopeCheckParentRecordId($query, $id)
    {
        return $query->where('intParentMenuId', $id);
    }

    /**
     * This method handels alias id scope
     * @return  Object
     * @since   04-08-2017
     * @author  NetQuick
     */
    public function scopeCheckAliasId($query, $id)
    {
        return $query->where('intAliasId', $id);
    }
    /**
     * This method handels item order scope
     * @return  Object
     * @since   04-08-2017
     * @author  NetQuick
     */
    public function scopeItemOrderCheck($query, $order)
    {
        return $query->where('intItemOrder', $order);
    }
    /**
     * This method handels parent item order scope
     * @return  Object
     * @since   04-08-2017
     * @author  NetQuick
     */
    public function scopeItemParentItemOrderCheck($query, $order)
    {
        return $query->where('intParentItemOrder', $order);
    }
    /**
     * This method handels scope for various where get calls
     * @return  Object
     * @since   04-08-2017
     * @author  NetQuick
     */
    public function scopeWhereCheck($query, $whereArray)
    {
        return $query->where($whereArray);
    }
    /**
     * This method handels publish scope
     * @return  Object
     * @since   04-08-2017
     * @author  NetQuick
     */
    public function scopePublish($query)
    {
        return $query->where(['chrPublish' => 'Y']);
    }
    public function scopeDisplayFront($query)
    {
        return $query->where(['chrDisplayFront' => 'Y']);
    }
    /**
     * This method handels active scope
     * @return  Object
     * @since   04-08-2017
     * @author  NetQuick
     */
    public function scopeActive($query)
    {
        return $query->where(['chrActive' => 'Y']);
    }
    /**
     * This method handels delete scope
     * @return  Object
     * @since   04-08-2017
     * @author  NetQuick
     */
    public function scopeDeleted($query)
    {
        return $query->where(['chrDelete' => 'N']);
    }
    /**
     * This method handels menu type check scope
     * @return  Object
     * @since   11-10-2017
     * @author  NetQuick
     */
    public function scopeCheckMenuType($query, $position)
    {
        return $query->where(['intPosition' => $position])
            ->orderBy('intParentItemOrder', 'ASC')
            ->orderBy('intItemOrder', 'ASC');
    }

    #End Config##################################################################

    /**
     * This method loads menu data for powerpanel side
     * @return  Object
     * @since   04-08-2017
     * @author  NetQuick
     */
    public static function getMenuObject($position)
    {
        $response = false;
        $response = DB::select(DB::raw('SELECT *
		FROM `nq_menu`
		WHERE intPosition = ' . $position . '
		AND	chrDelete="N" ORDER BY `intParentItemOrder` ASC, `intItemOrder` ASC')
        );
        return $response;
    }
    /**
     * This method loads menu data for front side
     * @return  Object
     * @since   09-08-2017
     * @author  NetQuick
     */
    public static function getMenuObjectFront($position)
    {
        $response = false;
        $response = DB::select(DB::raw('SELECT *
		FROM `nq_menu`
		WHERE intPosition = ' . $position . '
		AND `chrDelete` = "N"
		AND `chrPublish` = "Y"
		AND `chrActive` = "Y"
		ORDER BY `intParentItemOrder` ASC, `intItemOrder` ASC')
        );
        return $response;
    }

    public static function GetBreadumbid($id)
    {
        $response = null;
        $menuFields = ['id', 'intParentMenuId', 'varTitle', 'txtPageUrl', 'intfkModuleId'];
        $response = Self::getRecords($menuFields)
            ->checkPageId($id);
        $response = $response->deleted()
            ->orderBy('intPageId', 'ASC')
            ->first();
        return $response;
    }

    public function scopeCheckPageId($query, $id)
    {
        return $query->where('txtPageUrl', $id);
    }

}
