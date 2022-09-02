<?php

namespace Powerpanel\PopupContent\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class PopUpContent extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'pop_up_content';
    protected $fillable = [
        'id',
        'varTitle',
        'chrDisplay',
        'fkIntImgId',
        'dtStartDateTime',
        'dtEndDateTime',
        'fkIntPageId',
        'fkModuleId',
        'chrPublish',
        'chrDelete'
    ];
    
    

    /**
     * This method handels retrival of faqs records
     * @return  Object
     * @since   2016-07-20
     * @author  NetQuick
     */
    static function getRecords() {
        return self::with([]);
    }

    /**
     * This method handels record id scope
     * @return  Object
     * @since   2016-07-24
     * @author  NetQuick
     */
    function scopeCheckRecordId($query, $id) {
        return $query->where('id', $id);
    }

    /**
     * This method handels publish scope
     * @return  Object
     * @since   2016-07-20
     * @author  NetQuick
     */
    function scopePublish($query) {
        return $query->where(['chrPublish' => 'Y']);
    }

    /**
     * This method handels delete scope
     * @return  Object
     * @since   2016-07-20
     * @author  NetQuick
     */
    function scopeDeleted($query) {
        return $query->where(['chrDelete' => 'N']);
    }

    /**
     * This method handels filter scope
     * @return  Object
     * @since   2016-08-08
     * @author  NetQuick
     */
    

    /**
     * This method handels retrival of record by id
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getPopupContent() {
        $response = false;
        $moduleFields = ['id', 'fkIntPageId','fkModuleId','varTitle', 'chrDisplay', 'fkIntImgId', 'dtStartDateTime', 'dtEndDateTime', 'chrPublish'];
        $response = Self::select($moduleFields)
                        ->publish()
                        ->deleted()
                        ->get();
        return $response;
    }
    public static function checkPopupContent($pageid,$fkmodulecode) {
        $response = false;
        $moduleFields = ['id', 'fkIntPageId','fkModuleId','varTitle', 'chrDisplay', 'fkIntImgId', 'dtStartDateTime', 'dtEndDateTime', 'chrPublish'];
        $response = Self::select($moduleFields)
                        ->publish()
                        ->where('fkIntPageId','=',$pageid)
                        ->where('fkModuleId','=',$fkmodulecode)
                        ->deleted()
                        ->first();
        return $response;
    }
    
    public static function getRecordCheck($filterArr = false) {
        $response = false;
        $moduleFields = ['id','fkIntPageId','fkModuleId', 'varTitle', 'chrDisplay', 'fkIntImgId', 'dtStartDateTime', 'dtEndDateTime', 'chrPublish'];
       
        $response = Self::getPowerPanelRecords($moduleFields)
                ->deleted()
                 ->where('chrDisplay','=','Y')
                ->get();
        return $response;
    }

    /**
     * This method handels retrival of record by id
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getRecordById($id) {
        $response = false;
        $moduleFields = ['id','fkIntPageId','fkModuleId', 'varTitle', 'chrDisplay', 'fkIntImgId', 'dtStartDateTime', 'dtEndDateTime', 'chrPublish'];
        $response = Self::select($moduleFields)->deleted()->checkRecordId($id)->first();
        return $response;
    }

    public static function getRecordCount() {
        $response = false;
        $moduleFields = ['id','fkIntPageId','fkModuleId', 'varTitle',  'chrDisplay','fkIntImgId', 'dtStartDateTime', 'dtEndDateTime', 'chrPublish'];
        $response = Self::select($moduleFields)->deleted()->count();
        return $response;
    }






    public static function getRecordList($filterArr = false) {
        $response = false;
        $moduleFields = ['id','fkIntPageId','fkModuleId', 'varTitle', 'chrDisplay', 'fkIntImgId', 'dtStartDateTime', 'dtEndDateTime', 'chrPublish'];

        $response = Self::getPowerPanelRecords($moduleFields)
                ->deleted()
                 ->filter($filterArr)
                ->get();
        return $response;
    }






    public static function getPowerPanelRecords($moduleFields = false, $aliasFields = false, $videoFields = false, $imageFields = false, $categoryFields = false) {
        $data = [];
        $response = false;
        $response = self::select($moduleFields);
        if ($imageFields != false) {
            $data['image'] = function ($query) use ($imageFields) {
                $query->select($imageFields);
            };
        }
        if ($aliasFields != false) {
            $data['alias'] = function ($query) use ($aliasFields) {
                $query->select($aliasFields)->checkModuleCode();
            };
        }
        if ($videoFields != false) {
            $data['video'] = function ($query) use ($videoFields) {
                $query->select($videoFields)->publish();
            };
        }
        if ($categoryFields != false) {
            $data['serviceCategory'] = function ($query) use ($categoryFields) {
                $query->select($categoryFields);
            };
        }
        if (count($data) > 0) {
            $response = $response->with($data);
        }
        return $response;
    }

    public static function getRecordForLogById($id) {
        $response = false;
        $moduleFields = ['id','fkIntPageId','fkModuleId', 'chrDisplay', 'varTitle', 'fkIntImgId', 'dtStartDateTime', 'dtEndDateTime', 'chrPublish'];
        $response = Self::select($moduleFields)->deleted()->checkRecordId($id)->first();
        return $response;
    }
    
    
    public function scopeFilter($query, $filterArr = false, $retunTotalRecords = false) {

        $response = false;
        if (!empty($filterArr['orderByFieldName']) && !empty($filterArr['orderTypeAscOrDesc'])) {
            $query = $query->orderBy($filterArr['orderByFieldName'], $filterArr['orderTypeAscOrDesc']);
        }

        if (!$retunTotalRecords) {
            if (!empty($filterArr['iDisplayLength']) && $filterArr['iDisplayLength'] > 0) {
                $data = $query->skip($filterArr['iDisplayStart'])->take($filterArr['iDisplayLength']);
            }
        }
        if (!empty($filterArr['statusFilter']) && $filterArr['statusFilter'] != ' ') {
            $data = $query->where('chrPublish', $filterArr['statusFilter']);
        }
        if (!empty($filterArr['catFilter']) && $filterArr['catFilter'] != ' ') {
            $data = $query->where('txtCategories', 'like', '%' . '"' . $filterArr['catFilter'] . '"' . '%');
        }
        if (!empty($filterArr['searchFilter']) && $filterArr['searchFilter'] != ' ') {
            $data = $query->where('varTitle', 'like', "%" . $filterArr['searchFilter'] . "%");
        }

        if (isset($filterArr['ignore']) && !empty($filterArr['ignore'])) {
            $data = $query->whereNotIn('id', $filterArr['ignore']);
        }

        


        if (!empty($query)) {
            $response = $query;
        }
        return $response;
    
    
        
        }
//    function scopeFilter($query) {
//        return $query->whereRaw('NOW() BETWEEN dtStartDateTime AND dtEndDateTime');
//    }
    /**
     * This method handels front search scope
     * @return  Object
     * @since   2016-08-09
     * @author  NetQuick
     */
    public function scopeFrontSearch($query, $term = '') {
        $response = false;
        $response = $query->where(['varTitle', 'like', '%' . $term . '%']);
        return $response;
    }

}
