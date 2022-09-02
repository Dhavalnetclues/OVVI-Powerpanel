<?php

/**
 * The ModuleGroup class handels DashboardOrder model queries
 * ORM implemetation.
 * @package   Netquick powerpanel 
 * @version   1.00
 * @since   	25-Sep-2018
 * @author    Rbhuva
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cache;
use DB;
use App\CommonModel;

class DashboardOrder extends Model {

    protected $table = 'dashboardorder';
    protected $fillable = [
        'id',
        'varTitle',
        'intDisplayOrder',
        'created_at',
        'updated_at',
    ];

    public static function UpdateDisplayOrder($AllOrder, $UserId) {
        $Record = Self::Select('*')->where('UserID', $UserId)->first();
        if (empty($Record)) {
            $New_OrderData['intDisplayOrder'] = $AllOrder;
            $New_OrderData['UserID'] = $UserId;
            Self::insertGetId($New_OrderData);
        } else {
            $whereConditions = ['UserID' => $UserId];
            $update = [
                'intDisplayOrder' => $AllOrder,
            ];

            CommonModel::updateRecords($whereConditions, $update, FALSE, 'App\DashboardOrder');
        }
    }

    public static function getDashboardOrder($id, $cmsPageFields = false) {
        $response = false;
        $cmsPageFields = ['id', 'intDisplayOrder'];
        $response = Self::getPowerPanelRecords($cmsPageFields)->where('UserID', $id)->first();
        if (empty($response)) {
            $New_OrderData['intDisplayOrder'] = '[1,2,4,7,12]';
            $New_OrderData['UserID'] = auth()->user()->id;
            Self::insertGetId($New_OrderData);
        } else {
            $response->intDisplayOrder;
            $response = explode('[', $response->intDisplayOrder)[1];
            $response = explode(']', $response)[0];
        }

        return $response;
    }

    public static function dashboardWidgetSettings($id, $cmsPageFields = false) {
        $response = false;
        $cmsPageFields = ['txtWidgetSetting'];
        $response = Self::getPowerPanelRecords($cmsPageFields)
                ->where('UserID', $id)
                ->first();
        return $response;
    }

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

}
