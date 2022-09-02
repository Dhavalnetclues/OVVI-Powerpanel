<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Modules extends Model
{

    protected $table = 'module';
    protected $fillable = [
        'id',
        'intFkGroupCode',
        'varTitle',
        'varModuleName',
        'varTableName',
        'varModelName',
        'varModuleClass',
        'varModuleNameSpace',
        'decVersion',
        'intDisplayOrder',
        'chrIsFront',
        'chrIsPowerpanel',
        'chrPublish',
        'chrDelete',
        'created_at',
        'updated_at',
    ];

    public static function getRecordAvaliModules($ignoreModuleArr = false)
    {
        $response = false;
        $moduleFields = [
            'id',
            'varTitle',
            'varModelName',
            'varModuleName', 'varModuleNameSpace'];

        $query = Self::getRecords($moduleFields)->publish()->where('chrIsFront', 'Y');
        if ($ignoreModuleArr) {
            $query = $query->whereNotIN('varModuleName', $ignoreModuleArr);
        }
        $query = $query->orderBy('varTitle');

        $response = $query->get();
        return $response;
    }

    public static function getAllModuleData($moduleName = false)
    {
        $moduleFields = [
            'id',
            'varTitle',
            'varModuleName',
            'varTableName',
            'varModelName',
            'varModuleClass',
            'varModuleNameSpace',
            'varModuleName',
        ];
        $response = false;
        $response = Self::getRecords($moduleFields, false, false)
            ->deleted()
            ->getModuleId($moduleName)
            ->first();
        return $response;
    }

    public static function getModuleById($id = false)
    {
        $moduleFields = [
            'id',
            'varTitle',
            'varModuleName',
            'varTableName',
            'varModelName',
            'varModuleNameSpace',
            'intFkGroupCode',
        ];
        $response = false;
        $response = Self::getRecords($moduleFields)
            ->deleted()
            ->checkRecordId($id)
            ->first();
        return $response;
    }

    public static function getModuleByWorkflowIds($ids = false)
    {
        $moduleFields = [
            'id',
            'varTitle',
            'varModuleName',
            'varModelName',
            'varModuleNameSpace',
            'intFkGroupCode',
        ];
        $response = false;
        $moduleGroupFields = ['id', 'varTitle'];
        $response = Self::getRecords($moduleFields, $moduleGroupFields)
            ->deleted()
            ->whereIn('id', $ids)
            ->get();
        return $response;
    }

    public static function getModule($moduleName = false)
    {
        $moduleFields = ['id', 'varModuleName', 'varModuleNameSpace'];
        $response = false;
        $response = Self::getRecords($moduleFields)
            ->deleted()
            ->getModuleId($moduleName)
            ->orderBy('varTitle')
            ->first();
        return $response;
    }

    public static function getModuleByModelName($modleName = false)
    {
        $moduleFields = ['id', 'varModuleName', 'varModuleNameSpace', 'varModelName'];
        $response = false;
        $response = Self::getRecords($moduleFields)
            ->deleted()
            ->where('varModelName', $modleName)
            ->orderBy('varTitle')
            ->first();
        return $response;
    }

    public static function getModuleByIdsForMenu($moduleNames = array())
    {
        $moduleFields = [
            'id',
            'varModuleName',
            'varModelName',
            'varModuleNameSpace',
            'varTitle',
        ];
        $response = false;
        $response = Self::getRecords($moduleFields)
            ->deleted()
            ->getModuleByNames($moduleNames)
            ->orderBy('varTitle')
            ->get();
        return $response;
    }

    public static function getAllActiveModules()
    {
        $moduleFields = [
            'id',
            'varModuleName',
            'varModelName',
            'varModuleNameSpace',
            'varTitle',
        ];
        $response = false;
        $response = Self::getRecords($moduleFields)
            ->publish()
            ->deleted()
            ->orderBy('varTitle')
            ->get();

        return $response;
    }

    public static function getModuleIdsByNames($moduleNames = array())
    {
        $moduleFields = [
            'id',
        ];
        $response = false;
        $response = Self::getRecords($moduleFields)
            ->deleted()
            ->getModuleByNames($moduleNames)
            ->orderBy('varTitle')
            ->get()->toArray();
        return $response;
    }

    public static function getModuleDataByNames($moduleNames = array())
    {
        $moduleFields = [
            'id',
            'varModuleName',
            'varModelName',
            'varModuleNameSpace',
            'varTitle',
        ];
        $response = false;
        $response = Self::getRecords($moduleFields)
            ->deleted()
            ->getModuleByNames($moduleNames)
            ->orderBy('varTitle')
            ->get();
        return $response;
    }

    public static function getModuleList($ignoreIds = false)
    {
        $moduleFields = ['id', 'varTitle', 'varModuleNameSpace', 'varModuleName'];
        $response = false;
        $response = Self::getRecords($moduleFields)
            ->publish()
            ->isFront()
            ->deleted();
        if ($ignoreIds) {
            $response = $response->whereNotIn('id', $ignoreIds);
        }
        $response = $response->orderBy('varTitle')
            ->get();
        return $response;
    }

    public static function getModulesBycategory($categoryIds = false, $ignoreIds = false)
    {
        $moduleFields = [
            'id',
            'varTitle',
            'varModuleNameSpace',
            'varModuleName',
        ];
        $response = false;
        $response = Self::getRecords($moduleFields)
            ->publish()
            ->deleted()
            ->where('intFkGroupCode', $categoryIds);

        if ($ignoreIds) {
            $response = $response->whereNotIn('id', $ignoreIds);
        }
        $response = $response->orderBy('varTitle')
            ->get();
        return $response;
    }

    public static function getModuleListForSettings($term = '')
    {
        $moduleFields = ['id', 'varTitle', 'varModuleName'];
        $response = false;
        $response = Self::getRecords($moduleFields)
            ->publish()
            ->deleted()
            ->filter($term)
            ->orderBy('varTitle')
            ->get();
        return $response;
    }

    public static function getFrontModuleList($ignoreIds = false)
    {
        $moduleFields = [
            'id',
            'varTitle',
            'varModelName',
            'varModuleNameSpace',
            'varModuleName'];
        $response = false;
        $response = Self::getRecords($moduleFields)
            ->isFront()
            ->deleted()
            ->publish()
            ->orderBy('varTitle');
        if ($ignoreIds) {
            $response = $response->whereNotIn('id', $ignoreIds);
        }
        $response = $response->get();
        return $response;
    }

    public static function getModuleDataById($moduleId = false)
    {
        $moduleFields = ['id', 'varTitle', 'varModuleName', 'varTableName', 'varModelName', 'varModuleClass', 'varModuleNameSpace'];
        $response = false;
        $response = Self::getPowerPanelRecords($moduleFields)
            ->deleted()
            ->checkRecordId($moduleId)
            ->first();
        return $response;
    }

    public static function getFrontModulesList($ignoreIds = false) {
        $moduleFields = [
            'id',
            'varTitle',
            'varModelName',
             'varModuleNameSpace',
            'varModuleName'];
        $response = false;
        $response = Self::getRecords($moduleFields)
                ->isFront()
                ->deleted()
                ->publish()
                ->orderBy('varTitle');
        $response->where('chrIsFront', '=', 'Y');
        if ($ignoreIds) {
            $response = $response->whereNotIn('id', $ignoreIds);
        }
        $response = $response->get();
        return $response;
    }


    /**
     * This method handels retrival of blog records
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public static function getPowerPanelRecords($moduleFields = false)
    {
        $data = [];
        $response = false;
        $response = self::select($moduleFields);
        if (count($data) > 0) {
            $response = $response->with($data);
        }
        return $response;
    }

    

    #Database Configurations========================================
    /**
     * This method handels retrival of blog records
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */

    public static function getRecords($moduleFields = false, $pageFields = false, $getGroup = true)
    {
        $data = [];
        $response = false;
        $response = self::select($moduleFields);
        if ($pageFields != false) {
            $data['pages'] = function ($query) use ($pageFields) {
                $query->select($pageFields);
            };
        }
        if ($getGroup) {
            $data['group'] = function ($query) {
                $query->select(['id', 'varTitle']);
            };
        }
        if (count($data) > 0) {
            $response = $response->with($data);
        }
        return $response;
    }

    /**
     * This method handels record id scope
     * @return  Object
     * @since   2016-07-24
     * @author  NetQuick
     */
    public function scopeCheckRecordId($query, $id)
    {
        return $query->where('id', $id);
    }

    /**
     * This method handels record id scope
     * @return  Object
     * @since   2016-07-24
     * @author  NetQuick
     */
    public function scopeCheckRecordIdIn($query, $ids)
    {
        return $query->whereIn('id', $ids);
    }

    /**
     * This method handels publish scope
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public function scopePublish($query)
    {
        return $query->where(['chrPublish' => 'Y']);
    }

    /**
     * This method handels front scope
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public function scopeIsFront($query)
    {
        return $query->where(['chrIsFront' => 'Y']);
    }

    /**
     * This method handels publish scope
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public function scopeGetModuleId($query, $moduleName)
    {
        return $query->where('varModuleName', $moduleName);
    }

    /**
     * This method handels publish scope
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public function scopeGetModuleByNames($query, $moduleNames)
    {
        return $query->whereIn('varModuleName', $moduleNames);
    }

    /**
     * This method handels filter scope
     * @return  Object
     * @since   2018-01-04
     * @author  NetQuick
     */
    public function scopeFilter($query, $term = '')
    {
        $response = false;

        $query = $query->whereNotIn('varModuleName', [
            "settings",
            "home",
            "menu-type",
            "recent-updates",
            "sitemap",
            "contact-us",
            "notificationlist",
            "email-log",
            "newsletter-lead",
            "log",
            "login-history",
            "faq",
            "plugins",
            "users",
            "roles",
        ]);

        if ($term != '') {
            $query = $query->where('varModuleName', 'like', "%" . $term . "%")
                ->orWhere('varTitle', 'like', "%" . $term . "%");
        }

        if (!empty($query)) {
            $response = $query;
        }
        return $response;
    }

    /**
     * This method handels delete scope
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public function scopeDeleted($query)
    {
        return $query->where(['chrDelete' => 'N']);
    }

    public function pages()
    {
        return $this->hasOne('Powerpanel\CmsPage\Models\CmsPage', 'intFKModuleCode', 'id');
    }

    public function log()
    {
        return $this->hasOne('App\Log', 'id', 'fkIntModuleId');
    }

    public function emailLog()
    {
        return $this->hasOne('App\EmailLog', 'id', 'fkIntModuleId');
    }

    public function banner()
    {
        return $this->hasOne('Powerpanel\Banner\Models\Banner', 'id', 'fkModuleId');
    }

    public function group()
    {
        $response = false;
        $response = $this->hasOne('App\ModuleGroup', 'id', 'intFkGroupCode');
        return $response;
    }

}
