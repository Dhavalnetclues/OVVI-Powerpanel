<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cache;
use Config;
use DB;

class PageTemplate extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'visultemplate';
    protected $fillable = [
        'id',
        'intAliasId',
        'UserID',
        'varTemplateName',
        'txtDesc',
        'chrPublish',
        'chrDelete',
        'chrDisplayStatus',
        'created_at',
        'updated_at',
    ];

    public static function getRecordByModuleId($id, $moduleId) {
        $cmspageFields = [
            'id',
            'intAliasId',
            'intFKModuleCode'
        ];
        $aliasFields = ['id', 'varAlias'];
        $moduleFields = ['id'];
        $response = Self::getPowerPanelRecords($cmspageFields, $aliasFields, false, $moduleId)
                ->deleted()
                ->publish()
                ->where('intFKModuleCode', $id)
                ->first();
        return $response;
    }

    public static function getRecordByModuleIdForQlink($id, $moduleId, $pageId = false) {
        $cmspageFields = [
            'id',
            'intAliasId',
            'intFKModuleCode'
        ];
        $aliasFields = ['id', 'varAlias'];
        $moduleFields = ['id'];
        $response = Self::getPowerPanelRecords($cmspageFields, $aliasFields, false, $moduleId)
                ->deleted()
                ->publish()
//                ->where('chrMain', 'Y')
                ->where('intFKModuleCode', $id);
        if ($pageId) {
            $response = $response->where('id', $pageId);
        }
        $response = $response->first();
        return $response;
    }

    /**
     * This method handels retrival of front blog detail
     * @return  Object
     * @since   2017-10-13
     * @author  NetQuick
     */
    public static function getRecordIdByAliasID($aliasID) {
        $response = false;
        $response = Self::Select('id')->deleted()->publish()->checkAliasId($aliasID)->first();
        return $response;
    }

    public static function getFrontRecordById($id) {
        $response = false;
        $noticesFields = [
            'id',
            'varTemplateName',
            'txtDesc',
        ];
        $response = Cache::tags(['Home'])->get('getFrontRecordById_' . $id);
        if (empty($response)) {
            $response = Self::getFrontPageRecord($noticesFields)
                    ->deleted()
                    ->publish()
//                    ->where('chrMain', 'Y')
                    ->where('id', $id)
                    ->dateRange()
                    ->first();
            Cache::tags(['Home'])->forever('getFrontRecordById_' . $id, $response);
        }
        return $response;
    }

    /**
     * This method handels retrival of blog records
     * @return  Object
     * @since   2016-07-24
     * @author  NetQuick
     */
    public static function getPageWithAlias($aliasId = false, $preview = false) {
        $response = false;
        $cmsPageFields = ['id', 'intAliasId', 'intFKModuleCode'];
        $aliasFields = ['id', 'varAlias', 'intFkModuleCode'];
        $moduleFields = ['id', 'varModuleName', 'varModuleClass'];
        $response = Self::getFrontPageRecord($cmsPageFields, $aliasFields, $moduleFields)
                ->deleted()
                ->dateRange()
                ->checkAliasId($aliasId);
        if ($preview) {
            $response = $response->orderBy('id', 'desc');
        } else {
            $response = $response->checkIsNotPreview()->publish();
        }
        $response = $response->first();
        return $response;
    }

    /**
     * This method handels retrival of blog records
     * @return  Object
     * @since   2016-07-24
     * @author  NetQuick
     */
    public static function getHomePageDisplaySections() {
        $response = false;
        $cmsPageFields = ['id', 'varTemplateName', 'intAliasId', 'intFKModuleCode', 'txtDesc'];
        $aliasFields = ['id', 'varAlias', 'intFkModuleCode'];
        $moduleFields = ['id', 'varModuleName', 'varModuleClass'];
        $response = Self::getFrontPageRecord($cmsPageFields, $aliasFields, $moduleFields)
                ->deleted()
//                ->where('chrMain', 'Y')
//                ->where('chrIsPreview', 'N')
                ->where('id', '1')
                ->dateRange()
                ->publish()
                ->first();
        return $response;
    }

    /**
     * This method handels retrival of blog records
     * @return  Object
     * @since   2016-07-24
     * @author  NetQuick
     */
    public static function getPageContentByPageAlias($cmsPageId) {
        $response = false;
        $response = Self::select(['txtDesc', 'varTemplateName', 'id', 'varPassword', 'UserID'])
                ->deleted()
                ->where(0)
                ->checkAliasId($cmsPageId)
                ->first();
        return $response;
    }

    /**
     * This method handels retrival of blog records
     * @return  Object
     * @since   2016-07-24
     * @author  NetQuick
     */
    public static function getPageByPageId($cmsPageId, $checkAlias = true) {
        $response = false;

        $response = Self::select([
                    'id',
                    'intAliasId',
                    'UserID',
                    'varTemplateName',
                    'txtDesc',
                    'chrPublish',
                    'chrDelete',
                    'chrDisplayStatus',
                    'created_at',
                    'updated_at',
        ]);
        if ($checkAlias) {
            $response->checkAliasId($cmsPageId);
        } else {
            $response->checkRecordId($cmsPageId);
        }
        $response = $response->first();
        return $response;
    }

    public static function getPriviewPageByPageId($cmsPageId, $checkAlias = true) {
        $response = false;
        $response = Self::select([
                    'id',
                    'intAliasId',
                    'UserID',
                    'varTemplateName',
                    'txtDesc',
                    'chrPublish',
                    'chrDelete',
                    'chrDisplayStatus',
                    'created_at',
                    'updated_at',
        ]);
        $response->checkRecordId($cmsPageId);
        $response = $response->first();
        return $response;
    }

    /**
     * This method handels retrival of blog records
     * @return  Object
     * @since   2016-07-24
     * @author  NetQuick
     */
    public static function getFrontPageRecord($cmsPageFields = false, $aliasFields = false, $moduleFields = false) {
        $data = [];
        $pageObj = Self::select($cmsPageFields);
        if ($aliasFields != false) {
            $data['alias'] = function ($query) use ($aliasFields) {
                $query->select($aliasFields);
            };
        }
        if ($moduleFields != false) {
            $data['modules'] = function ($query) use ($moduleFields) {
                $query->select($moduleFields);
            };
        }
        if (count($data) > 0) {
            $pageObj = $pageObj->with($data);
        }
        return $pageObj;
    }

    /**
     * This method handels retrival of blog records
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public static function getPowerPanelRecords($cmsPageFields = false, $aliasFields = false, $moduleFields = false, $moduleCode = false) {
        $data = [];
        $pageObj = Self::select($cmsPageFields);
        if ($aliasFields != false) {
            $data['alias'] = function ($query) use ($aliasFields, $moduleCode) {
                $query->select($aliasFields)->checkModuleCode($moduleCode);
            };
        }
        if ($moduleFields != false) {
            $data['modules'] = function ($query) use ($moduleFields) {
                $query->select($moduleFields);
            };
        }
        if (count($data) > 0) {
            $pageObj = $pageObj->with($data);
        }
        return $pageObj;
    }

    /**
     * This method handels retrival of record count
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getRecordCount($filterArr = false, $returnCounter = false) {
        $response = 0;
        $cmsPageFields = ['id'];
        $response = Self::getPowerPanelRecords($cmsPageFields);
        if ($filterArr != false) {
            $response = $response->filter($filterArr, $returnCounter);
        }
        $response = $response
                ->deleted()
                ->count();
        return $response;
    }

    /**
     * This method handels retrival of record count
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getRecordCountforList($filterArr = false, $returnCounter = false, $isAdmin = false, $ignoreId = array()) {
        $response = 0;
        $cmsPageFields = ['id'];
        $response = Self::getPowerPanelRecords($cmsPageFields);
        if ($filterArr != false) {
            $response = $response->filter($filterArr, $returnCounter);
        }
        $userid = auth()->user()->id;
        $response = $response->deleted()
                ->where('chrIsPreview', 'N');
         if (!$isAdmin) {
                 $response = $response->where(function ($query) use ($userid) {
                        $query->where("UserID", '=', $userid)->where('chrDisplayStatus', '=', 'PR')
                        ->orWhere('chrDisplayStatus', '=', 'PU');
                    });
            }
        $response = $response->whereNotIn('id', $ignoreId)->count();
        return $response;
    }

    public static function getRecordCount_tab1($filterArr = false, $returnCounter = false) {
        $response = 0;
        $cmsPageFields = ['id'];
        $response = Self::getPowerPanelRecords($cmsPageFields);
        if ($filterArr != false) {
            $response = $response->filter($filterArr, $returnCounter);
        }
        $response = $response
                ->deleted()
//                ->checkMainRecord('Y')
                ->count();
        return $response;
    }

    /**
     * This method handels retrival of record count
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getRecordList($filterArr = false, $isAdmin = false, $ignoreId = array()) {
        $userid = auth()->user()->id;
        $response = false;
        $cmsPageFields = [
            'id',
            'intAliasId',
            'UserID',
            'varTemplateName',
            'txtDesc',
            'chrPublish',
            'chrDelete',
            'chrDisplayStatus',
            'chrIsPreview',
            'created_at',
            'updated_at',
        ];
        $aliasFields = ['id', 'varAlias'];
        $response = Self::getPowerPanelRecords($cmsPageFields, $aliasFields)
                ->deleted()
                ->filter($filterArr)
                ->where('chrIsPreview', 'N');
            if (!$isAdmin) {
                 $response = $response->where(function ($query) use ($userid) {
                        $query->where("UserID", '=', $userid)->where('chrDisplayStatus', '=', 'PR')
                        ->orWhere('chrDisplayStatus', '=', 'PU');
                    });
            }
        $response = $response->whereNotIn('id', $ignoreId);
        //->orderBy('created_at', 'desc');
        $response = $response->get();
        return $response;
    }

    /**
     * This method handels retrival of record count
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getRecordsForMenu($moduleCode = false) {
        $response = false;
        $userid = auth()->user()->id;
        $cmsPageFields = [
            'id',
            'intAliasId',
            'UserID',
            'varTemplateName',
            'txtDesc',
            'chrPublish',
            'chrDelete',
            'chrDisplayStatus',
            'created_at',
            'updated_at',
        ];
        $aliasFields = ['id', 'varAlias'];
        $moduleFields = ['id', 'varModuleName'];
        $ignoreId = [0];
        $response = Self::getPowerPanelRecords($cmsPageFields, $aliasFields, $moduleFields, $moduleCode)
                ->where(function ($query) use ($userid) {
                    $query->where("UserID", '=', $userid);
                })
                ->whereNotIn('id', $ignoreId)
                ->orderBy('updated_at', 'desc')
                ->deleted()
                ->publish()
                ->get();
        return $response;
    }

    /**
     * This method handels retrival of record count
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getPagesWithModule($moduleCode = false) {
        $response = false;
        $cmsPageFields = [
            'id',
            'intAliasId',
            'UserID',
            'varTemplateName',
            'txtDesc',
            'chrPublish',
            'chrDelete',
            'chrDisplayStatus',
            'created_at',
            'updated_at',
        ];
        $moduleFields = ['id', 'varModuleName'];
        $response = Self::getPowerPanelRecords($cmsPageFields, false, $moduleFields, $moduleCode)
                ->deleted()
                ->publish()
                ->orderBy('varTemplateName')
                ->get();
        return $response;
    }

    /**
     * This method handels retrival of record count
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getPagesWithModuleForLinks($ignoreModuleIds = array(), $moduleCode = false) {
        $response = false;
        $cmsPageFields = [
            'id',
            'intAliasId',
            'UserID',
            'varTemplateName',
            'txtDesc',
            'chrPublish',
            'chrDelete',
            'chrDisplayStatus',
            'created_at',
            'updated_at',
        ];
        $moduleFields = ['id', 'varModuleName'];
        $response = Self::getPowerPanelRecords($cmsPageFields, false, $moduleFields, $moduleCode)
                ->deleted()
                ->publish()
                ->where('chrIsPreview', 'N')
                ->orderBy('varTemplateName');
        if (!empty($ignoreModuleIds)) {
            $response = $response->whereNotIn('intFKModuleCode', $ignoreModuleIds);
        }
        $response = $response->get();
        return $response;
    }

    public static function getPageWithModuleId($moduleCode = false) {
        $response = false;
        $cmsPageFields = ['id', 'intAliasId', 'intFKModuleCode', 'varTemplateName'];
        $moduleFields = ['id', 'varModuleName'];
        $response = Self::getPowerPanelRecords($cmsPageFields, false, $moduleFields, $moduleCode)
                ->deleted()
                ->publish()
                ->Pages()
                ->first();
        return $response;
    }

    /**
     * This method handels retrival of record count
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getRecordById($id, $ignoreDeleteScope = false) {
        $response = false;
        $cmsPageFields = [
            'id',
            'intAliasId',
            'UserID',
            'varTemplateName',
            'txtDesc',
            'chrPublish',
            'chrDelete',
            'chrDisplayStatus',
            'created_at',
            'updated_at',
        ];
        $aliasFields = ['id', 'varAlias'];
        $moduleFields = ['id', 'varModuleName'];
//        echo $id;exit;
        $response = Self::getPowerPanelRecords($cmsPageFields, $aliasFields);
        if (!$ignoreDeleteScope) {
            $response = $response->deleted();
        }
        $response = $response->checkRecordId($id)->first();
        return $response;
    }

    /**
     * This method handels retrival of record for delete
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getRecordsforDeleteById($id) {
        $response = false;
        $moduleFields = ['id', 'varTemplateName'];
        $response = Self::getPowerPanelRecords($moduleFields)->checkRecordId($id)->first();
        return $response;
    }

    /**
     * This method handels retrival of record by id for Log Manage
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getRecordForLogById($id) {
        $response = false;
        $cmspageFields = [
            'id',
            'intAliasId',
            'UserID',
            'varTemplateName',
            'txtDesc',
            'chrPublish',
            'chrDelete',
            'chrDisplayStatus',
            'created_at',
            'updated_at',
        ];
        $aliasFields = ['id', 'varAlias'];
        $moduleFields = ['id', 'varModuleName'];
        $response = Self::getPowerPanelRecords($cmspageFields, $aliasFields, $moduleFields)->deleted()->checkRecordId($id)->first();
        return $response;
    }

    /**
     * This method handels retrival of record by id for Log Manage
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getHomePageShortDescriptionById($id) {
        $response = false;
        $cmspageFields = ['id', 'intSearchRank', 'FavoriteID', 'varPassword', 'intAliasId', 'intFKModuleCode', 'varTemplateName', 'txtDesc', 'chrPublish', 'chrDelete', 'UserID'];
        $response = Self::getPowerPanelRecords($cmspageFields)->deleted()->checkRecordId($id)->publish()->first();
        return $response;
    }

    /**
     * This method handels retrival of record count
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getRecordCount_letest($Main_id, $id) {
        $moduleFields = ['chrLetest'];
        $response = Self::getPowerPanelRecords($moduleFields)
                ->deleted()
                ->where($Main_id)
                ->where('chrLetest', 'Y')
                ->where('id', '!=', $id)
                ->where('chrApproved', 'N')
                ->count();
        return $response;
    }

    public static function getRecordForMenuAddByModuleId($id, $moduleId = false) {
        $cmspageFields = [
            'id',
            'intAliasId',
            'UserID',
            'varTemplateName',
            'txtDesc',
            'chrPublish',
            'chrDelete',
            'created_at',
            'updated_at',
        ];
        $aliasFields = ['id', 'varAlias'];
        $moduleFields = ['id', 'varModuleName'];
        $response = Self::getPowerPanelRecords($cmspageFields, $aliasFields, false, $moduleId)
                ->deleted()
                ->where('chrLetest', 'N')
                ->where('chrApproved', 'N')
                ->where('intFKModuleCode', $id)
                ->first();
        return $response;
    }

    public static function getRecordForPowerpanelShareByModuleId($id, $moduleId = false) {
        $cmspageFields = [
            'id',
            'intAliasId',
            'UserID',
            'varTemplateName',
            'txtDesc',
            'chrPublish',
            'chrDelete',
            'created_at',
            'updated_at',
        ];
        $aliasFields = ['id', 'varAlias'];
        $moduleFields = ['id', 'varModuleName'];
        $response = Self::getPowerPanelRecords($cmspageFields, $aliasFields, false, $moduleId)
                ->deleted()
                ->where('chrLetest', 'N')
                ->where('chrApproved', 'N')
                ->where('intFKModuleCode', $id)
                ->first();
        return $response;
    }

    /**
     * This method handels retrival of record count
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    protected static $fetchedOrder = [];
    protected static $fetchedOrderObj = null;

    public static function getRecordByOrder($order = false) {
        $response = false;
        $moduleFields = [
            'id',
            'intDisplayOrder',
        ];
        if (!in_array($order, Self::$fetchedOrder)) {
            array_push(Self::$fetchedOrder, $order);
            Self::$fetchedOrderObj = Self::getPowerPanelRecords($moduleFields)
                    ->deleted()
                    ->orderCheck($order)
                    ->first();
        }
        $response = Self::$fetchedOrderObj;
        return $response;
    }

    /**
     * This method handels retrival of record with id and title
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getPagesIdTitle() {
        $response = false;
        $cmsPageFields = ['id', 'varTemplateName'];
        $response = Self::getPowerPanelRecords($cmsPageFields)
                ->deleted()
                ->get();
        return $response;
    }

    /**
     * This method handels retrival of record with id and title
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getHomePage() {
        $response = false;
        $cmsPageFields = ['id', 'varTemplateName', 'intFKModuleCode'];
        $moduleFields = ['id'];
        $response = Self::getPowerPanelRecords($cmsPageFields, false, $moduleFields)
                ->getHomePage()
                ->first();
        return $response;
    }

    /**
     * This method handels retrival of page title by page id
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getPageTitleById($id = false) {
        $response = false;
        $cmsPageFields = ['varTemplateName', 'intFKModuleCode'];
        $response = Self::getPowerPanelRecords($cmsPageFields)
                ->deleted()
                ->checkRecordId($id)
                ->first();
        return $response;
    }

    /**
     * This method handels retrival of record count
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getOptionPageList($filterArr = false) {
        $response = false;
        $cmsPageFields = ['id', 'varTemplateName'];
        $response = Self::getPowerPanelRecords($cmsPageFields)->pluck('varTemplateName', 'id');
        return $response;
    }

    /**
     * This method handels alias relation
     * @return  Object
     * @since   2016-07-24
     * @author  NetQuick
     */
    public function alias() {
        return $this->belongsTo('App\Alias', 'intAliasId', 'id');
    }

    /**
     * This method handels module relation
     * @return  Object
     * @since   2016-07-24
     * @author  NetQuick
     */
    public function modules() {
        return $this->belongsTo('App\Modules', 'intFKModuleCode', 'id');
    }

    /**
     * This method handels module relation
     * @return  Object
     * @since   2016-07-24
     * @author  NetQuick
     */

    /**
     * This method handels retrival of blog records
     * @return  Object
     * @since   2016-07-24
     * @author  NetQuick
     */
    public static function getRecords($moduleId = false) {
        return self::with(['alias' => function ($query) use ($moduleId) {
                        $query->checkModuleCode($moduleId);
                    }, 'modules']);
    }

    /**
     * This method handels alias id scope
     * @return  Object
     * @since   2016-07-24
     * @author  NetQuick
     */
    public function scopeCheckAliasId($query, $id) {
        return $query->where('intAliasId', $id);
    }

    /**
     * This method handels alias id scope
     * @return  Object
     * @since   2016-07-24
     * @author  NetQuick
     */
    public function scopeCheckModuleId($query, $id) {
        return $query->where('intFKModuleCode', $id);
    }

    /**
     * This method handels record id scope
     * @return  Object
     * @since   2016-07-24
     * @author  NetQuick
     */
    public function scopeCheckRecordId($query, $id) {
        return $query->where('id', $id);
    }

    /**
     * This method handels order scope
     * @return  Object
     * @since   2016-07-24
     * @author  NetQuick
     */
    public function scopeOrderCheck($query, $order) {
        return $query->where('intDisplayOrder', $order);
    }

    /**
     * This method handels home page scope
     * @return  Object
     * @since   2016-07-24
     * @author  NetQuick
     */
    public function scopeGetHomePage($query) {
        return $query->where('varTemplateName', 'Home');
    }

    /**
     * This method handels publish scope
     * @return  Object
     * @since   2016-07-24
     * @author  NetQuick
     */
    public function scopePublish($query) {
        return $query->where(['chrPublish' => 'Y']);
    }

    public function scopePages($query) {
        return $query->where('varTemplateName', '=', 'Pages');
    }

    /**
     * This method handels delete scope
     * @return  Object
     * @since   2016-07-24
     * @author  NetQuick
     */
    public function scopeDeleted($query) {
        return $query->where(['chrDelete' => 'N']);
    }

    /**
     * This method handels Main Record scope
     * @return  Object
     * @since   2016-07-24
     * @author  NetQuick
     */

    /**
     * This method handels Main Record scope
     * @return  Object
     * @since   2016-07-24
     * @author  NetQuick
     */
    public function scopeCheckStarRecord($query, $flag = 'Y') {
        $response = false;
        $response = $query->where('chrAddStar', "=", $flag);
        return $response;
    }

    static function GetFrontdetaiBreadumb($id) {
        $response = false;
        $cmsPageFields = ['varTemplateName', 'intFKModuleCode'];
        $response = Self::getPowerPanelRecords($cmsPageFields)
                ->deleted()
                ->checkAliasName($id)
                ->first();
        return $response;
    }

    function scopeCheckAliasName($query, $id) {
        return $query->where('intAliasId', $id);
    }

    /**
     * This method handels filter scope
     * @return  Object
     * @since   2016-07-24
     * @author  NetQuick
     */
    public function scopeFilter($query, $filterArr = false, $retunTotalRecords = false) {
        $response = null;
        if (!empty($filterArr['orderByFieldName']) && !empty($filterArr['orderTypeAscOrDesc'])) {
            $query = $query->orderBy($filterArr['orderByFieldName'], $filterArr['orderTypeAscOrDesc']);
        } else {
            $query = $query->orderBy('updated_at', 'ASC');
        }
        if (!$retunTotalRecords) {
            if (!empty($filterArr['iDisplayLength']) && $filterArr['iDisplayLength'] > 0) {
                $data = $query->skip($filterArr['iDisplayStart'])->take($filterArr['iDisplayLength']);
            }
        }
        if (!empty($filterArr['statusFilter']) && $filterArr['statusFilter'] != ' ') {
            $data = $query->where('chrPublish', $filterArr['statusFilter']);
        }
        if (!empty($filterArr['searchFilter']) && $filterArr['searchFilter'] != ' ') {
            $data = $query->where('varTemplateName', 'like', "%" . $filterArr['searchFilter'] . "%");
        }
        if (!empty($filterArr['rangeFilter']) && $filterArr['rangeFilter'] != ' ') {
            $data = $query->whereRaw('DATE(dtStartDateTime) >= DATE("' . date('Y-m-d', strtotime($filterArr['rangeFilter']['from'])) . '")  AND DATE(dtEndDateTime) <= DATE("' . date('Y-m-d', strtotime($filterArr['rangeFilter']['to'])) . '")');
        }

        return $response;
    }

    /**
     * This method handels front search scope
     * @return  Object
     * @since   2016-08-09
     * @author  NetQuick
     */
    public function scopeFrontSearch($query, $term = '') {
        return $query->where(['varTemplateName', 'like', '%' . $term . '%']);
    }

    public function banners() {
        return $this->hasMany('Powerpanel\Banner\Models\Banner', 'image', 'id');
    }

    public function menu() {
        return $this->hasOne('Powerpanel\Menu\Models\Menu', 'id', 'intPageId');
    }

    /**
     * This method handle to child grid.
     * @author  Snehal
     */
    /**
     * This method handle to Approve record updated by user.
     * @author  Snehal
     */
}
