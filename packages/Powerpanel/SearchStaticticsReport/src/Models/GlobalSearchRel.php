<?php

namespace Powerpanel\SearchStaticticsReport\Models;

use Illuminate\Database\Eloquent\Model;

class GlobalSearchRel extends Model {

    protected $table = 'globalsearches_rel';
    protected $fillable = [
        'id',
        'fkSearchRecordId',
        'varBrowserInfo',
        'isWeb',
        'varSessionId',
        'varIpAddress',
        'chrPublish',
        'chrDelete',
        'created_at',
        'updated_at'
    ];

    /**
     * This method handels publish scope
     * @return  Object
     * @since   2018-09-18
     * @author  NetQuick
     */
    function scopePublish($query) {
        $response = false;
        $response = $query->where(['chrPublish' => 'Y']);
        return $response;
    }

    /**
     * This method handels delete scope
     * @return  Object
     * @since   2018-09-18
     * @author  NetQuick
     */
    function scopeDeleted($query) {
        $response = false;
        $response = $query->where(['chrDelete' => 'N']);
        return $response;
    }

    public static function deleteGlobalsearches_rel($ids) {
        Self::whereIn('fkSearchRecordId', $ids)
                ->update(['chrPublish' => 'N', 'chrDelete' => 'Y']);
    }

    public static function Globalsearches_rel_date($id) {
        $response = false;

        $moduleFields = ['created_at'];
        $response = Self::getPowerPanelRecords($moduleFields)
                ->where('chrDelete', 'N')
                ->where('chrPublish', 'Y')
                ->where('fkSearchRecordId', $id)
                ->orderBy('created_at', 'desc')
                ->first();
        return $response->created_at;
    }

    static function getPowerPanelRecords($moduleFields = false) {
        $data = [];
        $response = false;
        $response = self::select($moduleFields);
        if (count($data) > 0) {
            $response = $response->with($data);
        }
        return $response;
    }

}
