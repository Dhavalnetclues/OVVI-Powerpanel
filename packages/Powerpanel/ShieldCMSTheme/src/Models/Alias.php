<?php
namespace App;

use Config;
use Cviebrock\EloquentSluggable\Sluggable;
use DB;
use Illuminate\Database\Eloquent\Model;

class Alias extends Model
{
    use Sluggable;
    protected $table = 'alias';
    protected $fillable = ['varAlias', 'intFkModuleCode'];
    protected static $fetchedID = [];
    protected static $fetchedAlias = null;

    public function sluggable()
    {
        return [
            'varAlias' => [
                'source' => 'alias',
            ],
        ];
    }

    public static function getAlias($slug = false)
    {
        $response = false;
        if (!empty($slug)) {
            $response = Self::select(['id', 'intFkModuleCode', 'varAlias'])
                ->where('varAlias', $slug)
                ->get()
                ->first();
        }
        return $response;
    }
    public static function getAliasbyID($id = false)
    {
        $response = false;
        if (!empty($id)) {
            $response = Self::select(['id', 'intFkModuleCode', 'varAlias'])
                ->where('id', $id)
                ->where('chrIsPreview', "N")
                ->first();
        }
        return $response;
    }
    public static function get_module_by_alias($slug = false)
    {
        $response = false;
        $response = DB::table('alias')
            ->where('varAlias', '=', $slug)
            ->leftJoin('cms_pages', 'cms_pages.module_code', '=', 'alias.intFkModuleCode')
            ->first();
        return $response;
    }
    public static function addAlias($alias = false, $moduleCode = false, $isPreview = 'N')
    {
        $response = false;
        $exists = Self::getAlias($alias);
        if (empty($exists)) {
            $data = [
                'varAlias' => $alias,
                'intFkModuleCode' => $moduleCode,
                'chrIsPreview' => $isPreview];
            $response = Self::insertGetId($data);
        } else {
            $response = $exists->id;
        }
        return $response;
    }
    public static function getModuleByAliasId($aliasId)
    {
        $response = false;
        $response = Self::select(['intFkModuleCode'])
            ->where('id', $aliasId)
            ->get()
            ->first();
        return $response;
    }

    /*  new added code **/
    public static function deleteAlias($id, $moduleCode = false)
    {
        if (empty($moduleCode) && $moduleCode == '') {
            $moduleCode = Config::get('Constant.MODULE.ID');
        }
        $update = Alias::where('id', $id)->where('intFkModuleCode', $moduleCode)
        //->update(['chrDelete' => 'Y']);
            ->delete();
        return $update;
    }
    /*  end code here **/

    public function module()
    {
        return $this->belongsTo('App\Modules', 'intFkModuleCode', 'id');
    }
    /**
     * This method handels retrival of records
     * @return  Object
     * @since   04-08-2017
     * @author  NetQuick
     */
    public static function getRecords()
    {
        return self::with(['cmsPage', 'module']);
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
    public function scopeCheckByAlias($query, $alias)
    {
        return $query->where('varAlias', $alias);
    }
    public function events()
    {
        return $this->hasOne('App\Blogs', 'id', 'intAliasId');
    }
    public function blogs()
    {
        return $this->hasOne('App\Blogs', 'id', 'intAliasId');
    }
    public function team()
    {
        return $this->hasOne('App\team', 'id', 'intAliasId');
    }
    public function client()
    {
        return $this->hasOne('App\Client', 'id', 'intAliasId');
    }
    public function blogCategory()
    {
        return $this->hasOne('App\BlogCategory', 'id', 'intAliasId');
    }
    public function servieCategory()
    {
        return $this->hasOne('Powerpanel\ServicesCategory\Models\ServiceCategory', 'id', 'intAliasId');
    }
    public function newsCategory()
    {
        return $this->hasOne('App\NewsCategory', 'id', 'intAliasId');
    }
    public function eventCategory()
    {
        return $this->hasOne('App\EventCategory', 'id', 'intAliasId');
    }
    public function sponsorCategory()
    {
        return $this->hasOne('Powerpanel\SponsorCategory\Models\SponsorCategory', 'id', 'intAliasId');
    }
    public function productCategory()
    {
        return $this->hasOne('App\ProductCategory', 'id', 'intAliasId');
    }
    public function advertiseSlots()
    {
        return $this->hasOne('App\AdvertiseSlots', 'id', 'intAliasId');
    }
    public function cmspage()
    {
        return $this->hasOne('Powerpanel\CmsPage\Models\CmsPage', 'id', 'intAliasId');
    }
    public function staticBlock()
    {
        return $this->hasOne('App\StaticBlocks', 'id', 'intAliasId');
    }
    public function menu()
    {
        return $this->hasOne('Powerpanel\Menu\Models\Menu', 'id', 'intAliasId');
    }
    public function menuType()
    {
        return $this->hasOne('Powerpanel\Menu\Models\MenuType', 'id', 'intAliasId');
    }
    /**
     * This method handels module code check for alias
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public function scopeCheckModuleCode($query, $moduleId = false)
    {
        if ($moduleId == false) {
            $moduleId = Config::get('Constant.MODULE.ID');
        }
        return $query->where('alias.intFkModuleCode', $moduleId);
    }
    public static function updateAlias($oldAlias, $newAlias)
    {
        Alias::where('varAlias', $oldAlias)->update(['varAlias' => $newAlias]);
    }
    public static function updatePreviewAlias($varAlias, $isPreview)
    {
        Alias::where('varAlias', $varAlias)->update(['chrIsPreview' => $isPreview]);
    }
}
