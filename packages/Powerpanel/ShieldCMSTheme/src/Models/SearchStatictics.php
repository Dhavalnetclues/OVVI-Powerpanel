<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class SearchStatictics extends Model {

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'globalsearches';
    protected $fillable = [
        'id',
        'varTitle',
        'chrDelete',
        'chrPublish',
        'created_at',
        'updated_at'
    ];

    /**
     * This method handels retrival of event records
     * @return  Object
     * @since   2017-08-02
     * @author  NetQuick
     */
    static function getRecords() {
        return self::with([]);
    }

    /**
     * This method handels retrival of record count
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getRecordById($id, $moduleFields = false) {
        $response = false;
        $moduleFields = ['id',
            'varTitle',
            'chrDelete',
            'chrPublish',
            'created_at',
            'updated_at'];
        $response = Self::getPowerPanelRecords($moduleFields)->deleted()->checkRecordId($id)->first();
        return $response;
    }

    /**
     * This method handels backend records
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    static function getPowerPanelRecords($moduleFields = false) {
        $data = [];
        $response = false;
        $response = self::select($moduleFields);
        if (count($data) > 0) {
            $response = $response->with($data);
        }
        return $response;
    }

    /**
     * This method handels retrival of backend record list
     * @return  Object
     * @since   2017-10-24
     * @author  NetQuick
     */
    public static function getRecordList($filterArr = false) {
        $response = false;
        DB::enableQueryLog();
        $moduleFields = [
        	'globalsearches_rel.fkSearchRecordId as relID', 
        	'globalsearches_rel.created_at as createDate', 
        	DB::raw('count(fkSearchRecordId) as counter'),
        	DB::raw('YEAR(nq_globalsearches.created_at) as onlyyear'),
        	DB::raw('MONTH(nq_globalsearches.created_at) as onlymonth'), 
        	'globalsearches.id', 'globalsearches.varTitle', 
        	'globalsearches.chrDelete', 
        	'globalsearches.chrPublish', 
        	'globalsearches.created_at', 
        	'globalsearches.updated_at'
        ];
        $response = Self::getPowerPanelRecords($moduleFields)
                ->leftJoin('globalsearches_rel', 'globalsearches_rel.fkSearchRecordId', '=', 'globalsearches.id')
                ->where('globalsearches.chrDelete', 'N')
                ->where('globalsearches.chrPublish', 'Y')
                ->where('globalsearches_rel.chrDelete', 'N')
                ->where('globalsearches_rel.chrPublish', 'Y')
                ->groupBy('onlyyear')
                ->groupBy('onlymonth')
                ->groupBy('globalsearches.varTitle');

        if (!empty($filterArr['yearFilter']) && $filterArr['yearFilter'] != '') {
            $response = $response->whereRaw('YEAR(nq_globalsearches.created_at) = ' . $filterArr['yearFilter']);
        }
        if (!empty($filterArr['monthFilter']) && $filterArr['monthFilter'] != '') {
            $response = $response->whereRaw('MONTH(nq_globalsearches.created_at) = ' . $filterArr['monthFilter']);
        }
        $response = $response->filter($filterArr)->get();
        return $response;
    }

    public static function getRecordCount($filterArr = false) {
          $response = false;
        DB::enableQueryLog();
        $moduleFields = [
            'globalsearches_rel.fkSearchRecordId as relID',
            'globalsearches_rel.created_at as createDate',
            DB::raw('count(fkSearchRecordId) as counter'),
            DB::raw('YEAR(nq_globalsearches.created_at) as onlyyear'),
            DB::raw('MONTH(nq_globalsearches.created_at) as onlymonth'),
            'globalsearches.id', 'globalsearches.varTitle',
            'globalsearches.chrDelete',
            'globalsearches.chrPublish',
            'globalsearches.created_at',
            'globalsearches.updated_at'
        ];
        $response = Self::getPowerPanelRecords($moduleFields)
                ->leftJoin('globalsearches_rel', 'globalsearches_rel.fkSearchRecordId', '=', 'globalsearches.id')
                ->where('globalsearches.chrDelete', 'N')
                ->where('globalsearches.chrPublish', 'Y')
                ->where('globalsearches_rel.chrDelete', 'N')
                ->where('globalsearches_rel.chrPublish', 'Y')
                ->groupBy('onlyyear')
                ->groupBy('onlymonth')
                ->groupBy('globalsearches.varTitle');

        if (!empty($filterArr['yearFilter']) && $filterArr['yearFilter'] != '') {
            $response = $response->whereRaw('YEAR(nq_globalsearches.created_at) = ' . $filterArr['yearFilter']);
        }
        if (!empty($filterArr['monthFilter']) && $filterArr['monthFilter'] != '') {
            $response = $response->whereRaw('MONTH(nq_globalsearches.created_at) = ' . $filterArr['monthFilter']);
        }
        $response = $response->filter($filterArr)->get();
        return $response;
    }

    public static function deleteGlobalsearches_rel($ids) {
        Self::whereIn('fkSearchRecordId', $ids)
                ->update(['chrPublish' => 'N', 'chrDelete' => 'Y']);
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
     * @since   2017-08-02
     * @author  NetQuick
     */
    function scopePublish($query) {
        return $query->where(['chrPublish' => 'Y']);
    }

    /**
     * This method handels delete scope
     * @return  Object
     * @since   2017-08-02
     * @author  NetQuick
     */
    function scopeDeleted($query) {
        return $query->where(['chrDelete' => 'N']);
    }

    /**
     * This method check multiple records id
     * @return  Object
     * @since   2017-08-02
     * @author  NetQuick
     */
    function scopeCheckMultipleRecordId($query, $Ids) {
        return $query->whereIn('id', $Ids);
    }

    /**
     * This method handle order by query
     * @return  Object
     * @since   2017-08-02
     * @author  NetQuick
     */
    function scopeOrderByCreatedAtDesc($query) {
        return $query->orderBy('created_at', 'DESC');
    }

    /**
     * This method handels filter scope
     * @return  Object
     * @since   2017-08-02
     * @author  NetQuick
     */
    function scopeFilter($query, $filterArr = false, $retunTotalRecords = false) {
        $response = null;
        if ($filterArr['orderByFieldName'] != null && $filterArr['orderTypeAscOrDesc'] != null) {
            $query = $query->orderBy($filterArr['orderByFieldName'], $filterArr['orderTypeAscOrDesc']);
        } else {
            $query = $query->orderBy('varTitle', 'ASC');
        }

        if (!$retunTotalRecords) {
            if (!empty($filterArr['iDisplayLength']) && $filterArr['iDisplayLength'] > 0) {
                $data = $query->skip($filterArr['iDisplayStart'])->take($filterArr['iDisplayLength']);
            }
        }
        if (!empty($filterArr['statusFilter']) && $filterArr['statusFilter'] != ' ') {
            $data = $query->where('chrPublish', $filterArr['statusFilter']);
        }
        if (isset($filterArr['searchFilter']) && !empty($filterArr['searchFilter'])) {
            $data = $query->where('varTitle', 'like', '%' . $filterArr['searchFilter'] . '%');
        }

        if (!empty($query)) {
            $response = $query;
        }
        return $response;
    }

}
