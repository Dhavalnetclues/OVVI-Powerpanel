<?php

namespace Powerpanel\ShieldCMSTheme\Models;

use Illuminate\Database\Eloquent\Model;

class ErrorLog extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'error_logs';
    protected $fillable = [
        'id',
        'varTitle',
        'txtErrorTemplate',
        'varIpAddress',
        'chrDelete',
        'created_at',
        'updated_at',
    ];

    /**
     * This method handels retrival of backend record list
     * @return  Object
     * @since   2017-10-24
     * @author  NetQuick
     */
    public static function getRecordList($filterArr = false)
    {
        $response = false;
        $moduleFields = ['id',
            'varTitle',
            'txtErrorTemplate',
            'varIpAddress',
            'created_at',
        ];

        $response = Self::select($moduleFields)
            ->filter($filterArr)
            ->get();
        return $response;
    }

    public static function deleteRecordsPermanent($ids = false)
    {
        Self::whereIn('id', $ids)->delete();
    }

    /**
     * This method handels backend records
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

    public function scopeFilter($query, $filterArr = false, $retunTotalRecords = false)
    {
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

}
