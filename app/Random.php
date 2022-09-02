<?php

/**
 * The TermsConditions class handels TermsConditions model queries
 * ORM implemetation.
 * @package   Netquick powerpanel
 * @license   http://www.opensource.org/licenses/BSD-3-Clause
 * @version   1.00
 * @since   	2018-09-12
 * @author    NetQuick
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cache;

class Random extends Model {

    protected $table = 'powerpanel_random';
    protected $fillable = [
        'id',
        'fkIntUserId',
        'name',
        'email',
        'intCode',
        'varIpAddress',
        'chrExpiry',
        'chrDelete',
        'created_at',
        'updated_at'
    ];

    /**
     * This method handels insert of event record
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public static function addRecord($data = false) {
        $response = false;
        $recordId = Self::insertGetId($data);
        if ($recordId > 0) {
            $response = $recordId;
        }
        return $response;
    }

    public static function getRecord($userId) {
        $response = false;
        $response = Self::getPowerPanelRecords(['chrAccepted'])
                ->checkAccept()
                ->checkUserId($userId)
                ->orderBy('updated_at', 'DESC')
                ->first();
        return $response;
    }

    public static function randomcheck($id = false, $code = false) {
        $moduleFields = ['id', 'fkIntUserId', 'intCode', 'chrDelete', 'chrExpiry'];
        $response = false;
        $response = Self::getRecords($moduleFields)
                ->where('fkIntUserId', $id)
                ->where('intCode', $code)
                ->where('chrExpiry', 'N')
                ->get();
        return $response;
    }

    public static function getRecords($moduleFields = false, $pageFields = false) {
        $data = [];
        $response = false;
        $response = self::select($moduleFields);
        if ($pageFields != false) {
            $data['pages'] = function ($query) use ($pageFields) {
                $query->select($pageFields);
            };
        }
        if (count($data) > 0) {
            $response = $response->with($data);
        }
        return $response;
    }

    /**
     * This method handels backend records
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public static function getPowerPanelRecords($moduleFields = false) {
        $data = [];
        $response = false;
        $response = self::select($moduleFields);

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
    function scopeCheckAccept($query) {
        return $query->where('chrAccepted', 'Y');
    }

    /**
     * This method handels record id scope
     * @return  Object
     * @since   2016-07-24
     * @author  NetQuick
     */
    function scopeCheckUserId($query, $id) {
        return $query->where('fkIntUserId', $id);
    }

}
