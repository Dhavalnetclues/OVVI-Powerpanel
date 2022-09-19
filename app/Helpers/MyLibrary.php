<?php

namespace App\Helpers;

use App\Alias;
use App\CommonModel;
use App\Helpers\Aws_File_helper;
use App\Http\Traits\slug;
use App\Log;
use App\Modules;
use App\ModuleSettings;
use App\RecentUpdates;
use App\User;
use Auth;
use Carbon\Carbon;
use Config;
use DB;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
use Jenssegers\Agent\Agent;
use Powerpanel\CmsPage\Models\CmsPage;
use Powerpanel\ContactInfo\Models\ContactInfo;
use Powerpanel\Menu\Models\Menu;
use Request;

class MyLibrary
{

    private static $pageModuleData;

    public static function getModelNameSpace($nameSpace = 'App\\')
    {
        $modelName = Config::get('Constant.MODULE.MODEL_NAME');
        $modelNameSpace = $nameSpace . $modelName;
        return $modelNameSpace;
    }

    public static function withoutAliasMouduleIds()
    {
        $respone = false;
        /*
        With out module names List : Submit ticket - 21, Quick links - 30,workflow - 31,usefull links- 32
         */
        $withoutAliasModuleIds = [];
        $response = $withoutAliasModuleIds;
        return $response;
    }

    public static function getModuleNamesWithoutAlias()
    {
        $response = false;
        $response = [
            'submit-tickets',
            'quick-links',
            'searchentity',
            'useful-links',
            'workflow',
            'department',
            'feedback-leads',
            'organizations',
            'alerts',
            'video-gallery',
            'photo-gallery',
        ];
        return $response;
    }

    public static function getModuleNamesNotDisplayInfront()
    {
        $response = false;
        $response = [
            'publications-category',
        ];
        return $response;
    }

    public static function setFrontRoutes($segment = false, $preview = false, $sector_slug = false) {

        $aliasId = slug::resolve_alias_for_routes($segment, $sector_slug);
        $response = CmsPage::getPageWithAlias($aliasId, $preview);
        return $response;

    }

    public static function setConstants($segmentsArr = false, $preview = false) {
        

        Self::$pageModuleData = Modules::getAllModuleData('pages');
        Config::set('Constant.APP_KEY', env('APP_KEY'));
        if (empty($segmentsArr)) {
            $module = 'home';
        } elseif ($segmentsArr[0] != 'powerpanel') {
            if (($segmentsArr[0] == "ict" || $segmentsArr[0] == "water" || $segmentsArr[0] == "fuel" || $segmentsArr[0] == "energy") && (isset($segmentsArr[1]) && !empty($segmentsArr[1]))) {
                $module = $segmentsArr[1];
            } else {
                $module = $segmentsArr[0];
            }
        } elseif (isset($segmentsArr[1])) {
            $module = $segmentsArr[1];
        }

        $controller_name_space = 'App\Http\Controllers\\';
        $name_space = 'App\\';
        $response = false;
        if (!empty($segmentsArr) && isset($module)) {
            $objModules = array();
            if ($segmentsArr[0] != 'powerpanel') {
                $aliasId = slug::resolve_alias_for_routes($module);
                $response = CmsPage::getPageWithAlias($aliasId, $preview);
                if (isset($response->modules->varModuleName)) {
                    $module = $response->modules->varModuleName;
                    $objModules = Modules::getAllModuleData($module);
                }
            } else {
                $objModules = Modules::getAllModuleData($module);
            }

            if (empty($objModules) && $segmentsArr[0] != 'powerpanel') {
                $aliasId = slug::resolve_alias_for_routes($module);
                $response = CmsPage::getPageWithAlias($aliasId, $preview);

                if (isset($response->modules->varModuleName)) {
                    $module = $response->modules->varModuleName;
                    $objModules = Modules::getAllModuleData($module);
                }
            }

            if (!empty($objModules)) {
                $settings = ModuleSettings::getSettings($objModules->id);
                if (!empty($objModules->varModuleNameSpace)) {
                    $controller_name_space = $objModules->varModuleNameSpace . 'Controllers\\';
                    $name_space = $objModules->varModuleNameSpace;
                }
                Config::set('Constant.MODULE.ID', $objModules->id);
                Config::set('Constant.MODULE.TITLE', $objModules->varTitle);
                Config::set('Constant.MODULE.NAME', $objModules->varModuleName);
                Config::set('Constant.MODULE.TABLE_NAME', $objModules->varTableName);
                Config::set('Constant.MODULE.MODEL_NAME', $objModules->varModelName);
                Config::set('Constant.MODULE.CONTROLLER', $objModules->varModuleClass);
                $settings = isset($settings->txtSettings) ? $settings->txtSettings : json_encode(null);
                Config::set('Constant.MODULE.SETTINGS', $settings);

                $endSegment = collect($segmentsArr)->last();
                $action = '';
                $notificationAction = '';
                if ($endSegment == $objModules->varModuleName) {
                    $action = 'list';
                    $notificationAction = 'listed';
                } else if ($endSegment == 'add' || $endSegment == 'create') {
                    $action = 'add';
                    $notificationAction = 'added';
                } else if ($endSegment == 'edit' || $endSegment == 'update_status' || $endSegment == 'update') {
                    Config::set('Constant.RECORD.ALIAS', $segmentsArr[2]);
                    $action = 'edit';
                    $notificationAction = 'updated';
                } else if ($endSegment == "destroy" || $endSegment == "DeleteRecord") {
                    $action = 'delete';
                    $notificationAction = 'deleted';
                }
                Config::set('Constant.MODULE.ACTION', $action);
                Config::set('Constant.NOTIFICATION.ACTION', $notificationAction);
                $response = true;
            }
        }

        Config::set('Constant.MODULE.NAME_SPACE', $name_space);
        Config::set('Constant.MODULE.CONTROLLER_NAME_SPACE', $controller_name_space);
        return $response;
    }

    public static function getUserLogoByUserID($userId = false)
    {
        $response = false;
        $userData = User::getRecordById($userId);
        if (isset($userData) && !empty($userData->fkIntImgId)) {
            $notificationIcon = resize_image::resize($userData->fkIntImgId);
            $response = $notificationIcon;
        }
        return $response;
    }

    public static function logData($id = false, $moduleId = false, $addlog = false, $userId = false)
    {
        if ($moduleId == false) {
            $moduleId = Config::get('Constant.MODULE.ID');
        }
        if ($addlog == false) {
            $action = Config::get('Constant.MODULE.ACTION');
        } else {
            $action = $addlog;
        }
        $response = array();
        if ($userId) {
            $user = $userId;
        } else {
            $user = Auth::id();
        }
        if (!empty($id)) {
            $logArr = array();
            $logArr['fk_record_id'] = $id;
            $logArr['userId'] = $user;
            $logArr['moduleCode'] = $moduleId;
            $logArr['ipAddress'] = self::get_client_ip();
            $logArr['chr_publish'] = 'Y';
            $logArr['chr_delete'] = 'N';
            $logArr['action'] = $action;
            $response = $logArr;
        }
        return $response;
    }

    public static function notificationData($id = false, $data = false, $moduleId = false)
    {
        if ($moduleId == false) {
            $moduleId = Config::get('Constant.MODULE.ID');
        }
        $response = array();
        if (!empty($id) && count($data) > 0 && !empty($data)) {
            $verb = '';
            if (Config::get('Constant.MODULE.ACTION') == "delete") {
                $verb = 'from';
            } else if (Config::get('Constant.MODULE.ACTION') == "add") {
                $verb = 'to';
            } else if (Config::get('Constant.MODULE.ACTION') == "edit") {
                $verb = 'at';
            }
            if (isset($data->varTitle)) {
                $title = $data->varTitle;
            } else {
                $title = $data->name;
            }
            $notification = "%s " . Config::get('Constant.MODULE.ACTION') . ' ' . $title . ' ' . $verb . ' ' . Config::get('Constant.MODULE.NAME') . '.';
            $recentNotification = '%s ' . Config::get('Constant.NOTIFICATION.ACTION') . ' ' . $title . ' ' . $verb . ' ' . Config::get('Constant.MODULE.NAME') . '.';
            $notificationArr['fkIntRecordCode'] = $id;
            $notificationArr['fkIntUserId'] = Auth::id();
            $notificationArr['varIpAddress'] = self::get_client_ip();
            $notificationArr['fkIntModuleId'] = $moduleId;
            $notificationArr['txtNotification'] = $notification;
            $notificationArr['txtRecentNotification'] = $recentNotification;
            $notificationArr['chrRecordDelete'] = $verb == 'from' ? 'Y' : 'N';

            $response = $notificationArr;
        }
        return $response;
    }

    public static function userNotificationData($moduleId = false)
    {
        if ($moduleId == false) {
            $moduleId = Config::get('Constant.MODULE.ID');
        }
        $response = array();

        $notificationArr['fkIntUserId'] = Auth::id();
        $notificationArr['varIpAddress'] = self::get_client_ip();
        $notificationArr['created_at'] = Carbon::now();
        $notificationArr['updated_at'] = Carbon::now();
        $notificationArr['fkIntModuleId'] = $moduleId;
        $response = $notificationArr;

        return $response;
    }

    /* public static function deleteMultipleRecords($data = false, $modifiedModuleFields = array(), $value = false) {
    $response = false;
    $responseAr = [];
    if (!empty($data)) {
    $modelNameSpace = self::getModelNameSpace();
    if ($value == 'P') {
    $updateFields = ['chrTrash' => 'Y'];
    $deletedata = 'Primary Move To Trash';
    } elseif ($value == 'F') {
    $updateFields = ['chrTrash' => 'Y'];
    $deletedata = 'Favorite Move To Trash';
    } elseif ($value == 'A') {
    $updateFields = ['chrDelete' => 'Y', 'chrPublish' => 'N'];
    $deletedata = 'Delete  Approved Record';
    } elseif ($value == 'D') {
    $updateFields = ['chrDelete' => 'Y', 'chrPublish' => 'N', 'chrDraft' => 'N'];
    $deletedata = Config::get('Constant.DELETE_DRAFT_RECORD');
    } elseif ($value == 'T') {
    $updateFields = ['chrDelete' => 'Y', 'chrPublish' => 'N', 'chrTrash' => 'N'];
    $deletedata = Config::get('Constant.DELETE_TRASH_RECORD');
    } else {
    $updateFields = ['chrDelete' => 'Y', 'chrPublish' => 'N'];
    $deletedata = Config::get('Constant.DELETE_RECORD');
    }
    $modelName = Config::get('Constant.MODULE.MODEL_NAME');
    if ($modelName == "NewsletterLead") {
    $updateFields['chrSubscribed'] = 'N';
    }

    $whereINConditions = $data['ids'];
    $update = CommonModel::updateMultipleRecords($whereINConditions, $updateFields);
    $displayOrderFiledsIDs = [];
    foreach ($data['ids'] as $key => $id) {
    if ($update) {
    $objModule = CommonModel::getRecordsForDeleteById($id, false, false, $modifiedModuleFields);
    if (isset($objModule->intDisplayOrder)) {
    $displayOrderFiledsIDs [] = $objModule->id;
    /* if(isset($modifiedModuleFields) && !empty($modifiedModuleFields)){
    CommonModel::updateModifiedModuleOrder($objModule,$modifiedModuleFields);
    }else{
    CommonModel::updateOrder($objModule);
    } */
    /* $updateFields['intDisplayOrder'] = 1;
    }

    if (!empty($id)) {
    $logArr = self::logData($id, false, $deletedata);
    $title = '-';
    if (isset($objModule->varTitle)) {
    $title = $objModule->varTitle;
    } else if (isset($objModule->varName)) {
    $title = $objModule->varName;
    } else if (isset($objModule->name)) {
    $title = $objModule->name;
    }
    $logArr['varTitle'] = $title;
    Log::recordLog($logArr);
    array_push($responseAr, $objModule->id);
    $updateRecentUpdatesFilelds = ['chrRecordDelete' => 'Y'];
    if (Auth::user()->can('recent-updates-list')) {
    $notificationUpdate = RecentUpdates::updateRecords($id, $updateRecentUpdatesFilelds);
    if ($notificationUpdate) {
    $notificationArr = self::notificationData($id, $objModule);
    RecentUpdates::setNotification($notificationArr);
    }
    }
    }
    }
    }

    if (!empty($displayOrderFiledsIDs)) {
    if (isset($modifiedModuleFields) && !empty($modifiedModuleFields)) {
    CommonModel::setDisplayOrderSequence($modifiedModuleFields);
    } else {
    CommonModel::setDisplayOrderSequence();
    }
    /* $minOrderResult = CommonModel::getMinDisplayOrdersforDelete($displayOrderFiledsIDs,$modifiedModuleFields);
    $result = CommonModel::getDisplayOrdersforDelete($displayOrderFiledsIDs,$modifiedModuleFields);
    if(!empty($minOrderResult) && !empty($result)){
    foreach($result as $resultDetail){
    if(isset($modifiedModuleFields) && !empty($modifiedModuleFields)){
    CommonModel::tempupdateModifiedModuleOrder($resultDetail,$minOrderResult->minorder,$modifiedModuleFields);
    }else{
    CommonModel::tempUpdateOrder($resultDetail,$minOrderResult->minorder);
    }
    }
    } */
    /* }

    $response = $responseAr;
    }
    return $response;
    } */

    public static function deleteMultipleRecords($data = false, $modifiedModuleFields = array(), $value = false, $modelNameSpace = 'App\\')
    {
        $response = false;
        $responseAr = [];
        if (!empty($data)) {
            if ($modelNameSpace == 'App\\') {
                $modelNameSpace = self::getModelNameSpace($modelNameSpace);
            }
            $modelName = Config::get('Constant.MODULE.MODEL_NAME');
            $ignoremodelnames = array('Department');
            if (isset($modifiedModuleFields) && !empty($modifiedModuleFields) && Config::get('Constant.DEFAULT_TRASH') == 'Y' && !in_array($modelName, $ignoremodelnames)) {
                foreach ($data['ids'] as $key => $id) {

                    $modulerecordData = $modelNameSpace::getRecordForLogById($id);
                    if ($value == 'P') {
                        if ($modulerecordData->fkMainRecord == "0") {
                            $updateFields = ['chrTrash' => 'Y'];
                            $deletedata = Config::get('Constant.PRIMARY_MOVE_TO_TRASH');
                        } else {
                            $updateFields = ['chrDelete' => 'Y', 'chrPublish' => 'N'];
                            $deletedata = Config::get('Constant.DELETE_APPROVED');
                        }
                    } elseif ($value == 'F') {
                        if ($modulerecordData->fkMainRecord == "0") {
                            $updateFields = ['chrTrash' => 'Y'];
                            $deletedata = Config::get('Constant.FAVORITE_RECORD_MOVE_TO_TRASH');
                        } else {
                            $updateFields = ['chrDelete' => 'Y', 'chrPublish' => 'N'];
                            $deletedata = Config::get('Constant.DELETE_APPROVED');
                        }
                    } elseif ($value == 'A') {
                        if ($modulerecordData->fkMainRecord == "0") {
                            $updateFields = ['chrTrash' => 'Y'];
                            $deletedata = Config::get('Constant.APPROVE_RECORD_MOVE_TO_TRASH');
                        } else {
                            $updateFields = ['chrDelete' => 'Y', 'chrPublish' => 'N'];
                            $deletedata = Config::get('Constant.DELETE_APPROVED');
                        }
                    } elseif ($value == 'D') {
                        $updateFields = ['chrTrash' => 'Y'];
                        $deletedata = Config::get('Constant.DRAFT_RECORD_MOVE_TO_TRASH');
                    } elseif ($value == 'R') {
                        $updateFields = ['chrTrash' => 'Y'];
                        $deletedata = Config::get('Constant.ARCHIVE_RECORD_MOVE_TO_TRASH');
                    } elseif ($value == 'T') {
                        if ($modelName != 'FormBuilder' || $modelName != 'PageTemplate') {
                            $updateFields = ['chrDelete' => 'Y', 'chrPublish' => 'N', 'chrTrash' => 'N'];
                        } else {
                            $updateFields = ['chrDelete' => 'Y', 'chrPublish' => 'N'];
                        }
                        $deletedata = Config::get('Constant.DELETE_TRASH_RECORD');
                    } else {
                        $updateFields = ['chrDelete' => 'Y', 'chrPublish' => 'N'];
                        $deletedata = Config::get('Constant.DELETE_RECORD');
                    }

                    $whereINConditions = [$id];
                    $update = CommonModel::updateMultipleRecords($whereINConditions, $updateFields, false, $modelNameSpace);
                }
            } else {
                if (isset($value) && $value == "notification") {
                    $updateFields = ['chrDelete' => 'Y', 'chrPublish' => 'N'];
                    $deletedata = Config::get('Constant.NOTIFICATION_RECORD_DELETE');
                }else if($value == false){
                    $updateFields = ['chrDelete' => 'Y'];
                    $deletedata = Config::get('Constant.DELETE_RECORD');
                } else {
                    $updateFields = ['chrDelete' => 'Y', 'chrPublish' => 'N'];
                    $deletedata = Config::get('Constant.DELETE_RECORD');
                }


                $whereINConditions = $data['ids'];
                $update = CommonModel::updateMultipleRecords($whereINConditions, $updateFields, false, $modelNameSpace);
            }

            $displayOrderFiledsIDs = [];
            foreach ($data['ids'] as $key => $id) {
                if ($update) {

                    $objModule = CommonModel::getRecordsForDeleteById($id, false, $modelNameSpace, $modifiedModuleFields);
                    if (isset($objModule->intDisplayOrder)) {
                        $displayOrderFiledsIDs[] = $objModule->id;
                        /* if(isset($modifiedModuleFields) && !empty($modifiedModuleFields)){
                        CommonModel::updateModifiedModuleOrder($objModule,$modifiedModuleFields);
                        }else{
                        CommonModel::updateOrder($objModule);
                        } */
                        $updateFields['intDisplayOrder'] = 1;
                    }

                    if (!empty($id)) {
                        $logArr = self::logData($id, false, $deletedata);
                        $title = '-';
                        if ($modelName == 'Workflow') {
                            $title = $modelName;
                        } else if (isset($objModule->varTitle)) {
                            $title = $objModule->varTitle;
                        } else if (isset($objModule->varName)) {
                            $title = $objModule->varName;
                        } else if (isset($objModule->name)) {
                            $title = $objModule->name;
                        } else if (isset($objModule->txtNotification)) {
                            $title = $objModule->txtNotification;
                        } else if (isset($objModule->varTemplateName)) {
                            $title = $objModule->varTemplateName;
                        }
                        $logArr['varTitle'] = $title;
                        // Log::recordLog($logArr);
                        array_push($responseAr, $objModule->id);
                        $updateRecentUpdatesFilelds = ['chrRecordDelete' => 'Y'];
                        if (Auth::user()->can('recent-updates-list')) {
                            $notificationUpdate = RecentUpdates::updateRecords($id, $updateRecentUpdatesFilelds);
                            if ($notificationUpdate) {
                                $notificationArr = self::notificationData($id, $objModule);
                                RecentUpdates::setNotification($notificationArr);
                            }
                        }
                    }
                }
            }

            if (!empty($displayOrderFiledsIDs)) {
                if (isset($modifiedModuleFields) && !empty($modifiedModuleFields)) {
                    CommonModel::setDisplayOrderSequence($modifiedModuleFields);
                } else {
                    CommonModel::setDisplayOrderSequence();
                }
                /* $minOrderResult = CommonModel::getMinDisplayOrdersforDelete($displayOrderFiledsIDs,$modifiedModuleFields);
            $result = CommonModel::getDisplayOrdersforDelete($displayOrderFiledsIDs,$modifiedModuleFields);
            if(!empty($minOrderResult) && !empty($result)){
            foreach($result as $resultDetail){
            if(isset($modifiedModuleFields) && !empty($modifiedModuleFields)){
            CommonModel::tempupdateModifiedModuleOrder($resultDetail,$minOrderResult->minorder,$modifiedModuleFields);
            }else{
            CommonModel::tempUpdateOrder($resultDetail,$minOrderResult->minorder);
            }
            }
            } */
            }

            $response = $responseAr;
        }
        return $response;
    }

    public static function setPublishUnpublish($alias = false, $request, $modelNameSpace = false)
    {
        $value = Request::get('val');
        $response = false;
        if (!empty($alias) && !empty($value)) {
            if ($modelNameSpace == false) {
                $modelNameSpace = self::getModelNameSpace();
            }
            if (is_numeric($alias)) {
                $id = $alias;
                $whereConditions = ['id' => $id];
                //$objModule = $modelNameSpace::getRecordById($id);
            }

            $logArr = self::logData($id);
            
            if ($modelNameSpace != "\App\User" && $modelNameSpace != "App\User") {
                $newCmsPageObj = $modelNameSpace::getRecordForLogById($id);
                $logArr['varTitle'] = stripslashes($newCmsPageObj->varTitle);
            }

            if (!empty($value) && ($value == 'Unpublish')) {
                $updateField = ['chrPublish' => 'N'];
                $update = CommonModel::updateRecords($whereConditions, $updateField, false, $modelNameSpace);
                if ($update) {
                    $logArr['action'] = 'Unpublish';
                    Log::recordLog($logArr);
                    $response = $update;
                }
            }
            if (!empty($value) && ($value == 'Publish')) {
                $updateField = ['chrPublish' => 'Y'];
                $update = CommonModel::updateRecords($whereConditions, $updateField, false, $modelNameSpace);
                if ($update) {
                    $logArr['action'] = 'publish';
                    Log::recordLog($logArr);
                    $response = $update;
                }
            }
        }
        return $response;
    }

    /**
     * This method generates events seo content
     * @return  Meta values
     * @since   2016-10-25
     * @author  NetQuick
     */
    public static function generateSeocontent($title = false, $description = false, $fromajax = false)
    {
        $response = '';
        if (strlen($description) > 0) {
            if ($fromajax) {
                $description = html_entity_decode(strip_tags($description));
            } else {
                $description = strip_tags($description);
            }
        }

        $meta_title = $title;
        $meta_keyword = $title;
        $meta_description = substr($description, 0, 160);
        $seo_data = $meta_title . '*****' . $meta_keyword . '*****' . $meta_description;
        $response = $seo_data;
        return $response;
    }

    /**
     * This method handels swapping of available order record while editing
     * @param      order
     * @return  order
     * @since   2016-12-23
     * @author  NetQuick
     */
    public static function swapOrderEdit($order = null, $id = null, $isCustomize = false, $condtionFileds = array(), $modelNameSpace = 'App\\')
    {
        if ($modelNameSpace == 'App\\') {
            $modelNameSpace = self::getModelNameSpace($modelNameSpace);
        }
        $existingRecord = CommonModel::getRecordByOrder($modelNameSpace, $order);
        $recEx = CommonModel::getRecordByOrder($modelNameSpace, $order);

        if ($isCustomize) {
            $totalRecordCount = $modelNameSpace::getRecordCount(false, false, false, false);
        } else {
      
            $totalRecordCount = CommonModel::getRecordCount(false, false, false, $modelNameSpace);
        }

        $currentRecord = $modelNameSpace::getRecordById($id);

      
       
        if ($currentRecord->intDisplayOrder != $order) {
            if (!empty($existingRecord)) {
  
                $whereConditions = ['id' => $currentRecord->id];
                CommonModel::updateRecords($whereConditions, ['intDisplayOrder' => $order], false, $modelNameSpace);

                CommonModel::updateOrderAfterEdit($currentRecord->intDisplayOrder, $existingRecord->intDisplayOrder, $currentRecord->id, $isCustomize, $condtionFileds);
            } else {
                
                if ($totalRecordCount > 0) {
                    if ($order > $totalRecordCount) {
                        /* code for find last record orderID */
                        $lastrecordDetail = CommonModel::getRecordByOrder($modelNameSpace, $totalRecordCount);
                        if (!empty($lastrecordDetail)) {

                            $whereConditions = ['id' => $currentRecord->id];
                            CommonModel::updateRecords($whereConditions, ['intDisplayOrder' => $totalRecordCount], false, $modelNameSpace);

                            CommonModel::updateOrderAfterEdit($currentRecord->intDisplayOrder, $lastrecordDetail->intDisplayOrder, $currentRecord->id, $isCustomize, $condtionFileds);
                        }
                    }
                }
            }
        }
    }

    /* public static function swapOrderEdit($order = null, $id = null) {
    $modelNameSpace = self::getModelNameSpace();
    $recEx = CommonModel::getRecordByOrder($modelNameSpace, $order);

    if (count($recEx) > 0) {
    $recCur = $modelNameSpace::getRecordById($id);
    if ($recCur->intDisplayOrder != $recEx->intDisplayOrder) {
    $whereConditionsForEx = ['id' => $recEx['id']];
    CommonModel::updateRecords($whereConditionsForEx, ['intDisplayOrder' => $recCur->intDisplayOrder]);
    $whereConditionsForCur = ['id' => $recCur['id']];
    CommonModel::updateRecords($whereConditionsForCur, ['intDisplayOrder' => $recEx->intDisplayOrder]);
    }
    }
    } */

    /**
     * This method handels swapping of available order record while editing
     * @param      order
     * @return  order
     * @since   2016-12-23
     * @author  NetQuick
     */
    public static function swapOrder($order = null, $exOrder = null, $modelNameSpace = 'App\\')
    {
        if ($modelNameSpace == 'App\\') {
            $modelNameSpace = self::getModelNameSpace($modelNameSpace);
        }
        $recEx = CommonModel::getRecordByOrder($modelNameSpace, $exOrder);
        if (!empty($recEx)) {
            $recCur = CommonModel::getRecordByOrder($modelNameSpace, $order);
            if ($recCur->intDisplayOrder != $recEx->intDisplayOrder) {
                $whereConditionsForEx = ['id' => $recEx['id']];
                CommonModel::updateRecords($whereConditionsForEx, ['intDisplayOrder' => $recCur->intDisplayOrder], false, $modelNameSpace);
                $whereConditionsForCur = ['id' => $recCur['id']];
                CommonModel::updateRecords($whereConditionsForCur, ['intDisplayOrder' => $recEx->intDisplayOrder], false, $modelNameSpace);
            }
        }
    }

    /**
     * This method handels swapping of available order record while adding
     * @param      order
     * @return  order
     * @since   2016-10-21
     * @author  NetQuick
     */
    public static function swapOrderAdd($order = null, $isCustomize = false, $condtionFileds = array(), $modelNameSpace = 'App\\')
    {
        $response = false;
        if ($modelNameSpace == 'App\\') {
            $modelNameSpace = self::getModelNameSpace($modelNameSpace);
        }
        $rec = CommonModel::getRecordByOrder($modelNameSpace, $order);
        if ($isCustomize) {
            $total = $modelNameSpace::getRecordCount(false, false, false, $modelNameSpace);
        } else {
            $total = CommonModel::getRecordCount(false, false, false, $modelNameSpace);
        }

        if (!empty($rec)) {
            $whereConditions = ['intDisplayOrder' => $order];
            if ($isCustomize) {
                if (!empty($condtionFileds)) {
                    if (in_array('chrMain', $condtionFileds)) {
                        $whereConditions['chrMain'] = 'Y';
                        $whereConditions['fkMainRecord'] = '0';
                    }

                    if (in_array('chrIsPreview', $condtionFileds)) {
                        $whereConditions['chrIsPreview'] = 'N';
                    }
                }
            }
            CommonModel::updateRecords($whereConditions, ['intDisplayOrder' => $order + 1], false, $modelNameSpace);

            $updatedOrder = $order + 1;
            CommonModel::updateOrderAfterAdd($updatedOrder, $rec->id, $isCustomize, $condtionFileds);

            $response = $order;
        } else {
            $response = $total + 1;
        }

        return $response;
    }

    // /**
    //  * This method reorders events
    //  * @return  events index view data
    //  * @since   2016-10-11
    //  * @author  NetQuick
    //  */
    // static function reorder($data) {
    //         if (isset($data['order'])) {
    //                 $data = array_filter($data['order'], function ($value) {
    //                         return $value !== '';
    //                 });
    //                 foreach ($data as $key => $value) {
    //                         if ((int)$key != 0) {
    //                                 $whereConditions = ['id'=> $key];
    //                                 CommonModel::updateRecords($whereConditions, ['intDisplayOrder' => $value]);
    //                         }
    //                 }
    //         }
    // }
    public static function insertAlias($alias = false, $moduleCode = false, $isPreview = 'N', $sector = false)
    {
        $response = false;
        if (is_array($alias)) {
            $alias_1 = $alias[0];
        } else {
            $alias_1 = $alias;
        }

        $moduleCode = ($moduleCode == false) ? Config::get('Constant.MODULE.ID') : $moduleCode;
        $response = Alias::addAlias($alias_1, $moduleCode, $isPreview, $sector);
        return $response;
    }

    public static function get_client_ip(){        
        $ipaddress = '';
        $ipaddress = $_SERVER['REMOTE_ADDR'];
        if (isset($_SERVER['HTTP_CLIENT_IP'])){
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        }else if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }else if(isset($_SERVER['HTTP_X_FORWARDED'])){
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        }else if(isset($_SERVER['HTTP_FORWARDED_FOR'])){
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        }else if(isset($_SERVER['HTTP_FORWARDED'])){
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        }else if(isset($_SERVER['REMOTE_ADDR'])){
        }else{
            $ipaddress = 'UNKNOWN';
        }
        // if (getenv('HTTP_CLIENT_IP')) {
        //     $ipaddress = getenv('HTTP_CLIENT_IP');
        // } else if (getenv('HTTP_X_FORWARDED_FOR')) {
        //     $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        // } else if (getenv('HTTP_X_FORWARDED')) {
        //     $ipaddress = getenv('HTTP_X_FORWARDED');
        // } else if (getenv('HTTP_FORWARDED_FOR')) {
        //     $ipaddress = getenv('HTTP_FORWARDED_FOR');
        // } else if (getenv('HTTP_FORWARDED')) {
        //     $ipaddress = getenv('HTTP_FORWARDED');
        // } else if (getenv('REMOTE_ADDR')) {
        //     $ipaddress = getenv('REMOTE_ADDR');
        // } else {
        //     $ipaddress = 'UNKNOWN';
        // }     

        // if (env('APP_ENV') == "local") {
        //     $ipaddress = getHostByName(getHostName()); //remove this while uploading on server
        // }       

        return $ipaddress;
    }

    public static function get_geolocation($ip, $lang = "en", $fields = "*", $excludes = "")
    {
        $apiKey = "88c95abf0bc640c9bd0358ff07527692";
        $url = "https://api.ipgeolocation.io/ipgeo?apiKey=" . $apiKey . "&ip=" . $ip . "&lang=" . $lang . "&fields=" . $fields . "&excludes=" . $excludes;
        $cURL = curl_init();
        curl_setopt($cURL, CURLOPT_URL, $url);
        curl_setopt($cURL, CURLOPT_HTTPGET, true);
        curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
        if(env('APP_ENV') == 'local'){
            curl_setopt($cURL, CURLOPT_SSL_VERIFYPEER, false);
        }
        curl_setopt($cURL, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Accept: application/json',
        ));
        return curl_exec($cURL);
    }

    public static function updateAliasForPageInMenu($newAlias, $aliasPosition = "before", $moduleCode = false)
    {
        $tablename = 'nq_menu';

        $moduleCode = $moduleCode;
        $menuLinks = Menu::getMenuRecordsByModuleId($moduleCode);
        if (!empty($menuLinks)) {
            $menulinks = $menuLinks->toArray();
            if (!empty($menulinks)) {
                $update_syntax = "";
                $ids = array();
                foreach ($menulinks as $value) {
                    $ids[] = $value['id'];
                    $pageurl = trim($value['txtPageUrl'], '/');
                    $pageurlData = explode('/', $pageurl);
                    if (count($pageurlData) > 1) {
                        if ($aliasPosition == "before") {
                            $menukeys = array_keys($pageurlData);
                            $firstkey = current($menukeys);
                            $pageurlData[$firstkey] = $newAlias;
                            //$newpageUrl = $pageurlData[0]."/".$newAlias;
                            $newpageUrl = implode('/', $pageurlData);
                            $update_syntax .= " WHEN " . "'" . $pageurl . "'" . " THEN " . "'" . $newpageUrl . "' ";
                        }
                    }
                }

                if (!empty($update_syntax)) {
                    $updateSqlQuery = "UPDATE " . $tablename . " SET txtPageUrl = (CASE txtPageUrl " . $update_syntax . " ELSE txtPageUrl END) WHERE id BETWEEN " . min($ids) . " AND " . max($ids) . " and chrDelete = 'N' AND intfkModuleId=" . $moduleCode . "";
                    DB::update(DB::raw($updateSqlQuery));
                }
            }
        }
    }

    public static function updateAliasInMenu($newAlias, $recordId, $aliasPosition = "after", $moduleCode = false)
    {
        $tablename = 'nq_menu';

        $moduleCode = ($moduleCode == false) ? Config::get('Constant.MODULE.ID') : $moduleCode;
        $menuLinks = Menu::getMenuRecordsByModuleId($moduleCode, $recordId);
        if (!empty($menuLinks)) {
            $menulinks = $menuLinks->toArray();
            if (!empty($menulinks)) {
                $update_syntax = "";
                $ids = array();
                foreach ($menulinks as $value) {
                    $ids[] = $value['id'];
                    $pageurl = trim($value['txtPageUrl'], '/');
                    $pageurlData = explode('/', $pageurl);
                    if (count($pageurlData) > 1) {
                        if ($aliasPosition == "after") {
                            $endkey = array_keys($pageurlData);
                            $endkey = end($endkey);
                            $pageurlData[$endkey] = $newAlias;
                            //$newpageUrl = $pageurlData[0]."/".$newAlias;
                            $newpageUrl = implode('/', $pageurlData);
                        } else {
                            $newpageUrl = $newAlias . "/" . $pageurlData[1];
                        }
                        $update_syntax .= " WHEN " . "'" . $pageurl . "'" . " THEN " . "'" . $newpageUrl . "' ";
                    }
                }

                if (!empty($update_syntax)) {
                    $updateSqlQuery = "UPDATE " . $tablename . " SET txtPageUrl = (CASE txtPageUrl " . $update_syntax . " ELSE txtPageUrl END) WHERE id BETWEEN " . min($ids) . " AND " . max($ids) . " and chrDelete = 'N' AND intfkModuleId=" . $moduleCode . " AND intRecordId=" . $recordId . "";
                    DB::update(DB::raw($updateSqlQuery));
                }
            }
        }
    }

    /**
     * This method handels Encrypted code with AES Algorythm Custom Method
     * @return  Object
     * @since   2018-07-25
     * @author  NetQuick
     */
    public static function getEncryptedString($plaintext, $getAppKeyFromEnv = false)
    {
        $encryptedSring = '';
        if (!empty($plaintext)) {

            $envKey = Config::get('Constant.ENV_APP_KEY');

            if ($getAppKeyFromEnv) {
                $envKey = env('APP_KEY');
            }
            $method = 'aes-256-cbc';

            // Must be exact 32 chars (256 bit)
            $secureEnvKey = substr(hash('sha256', $envKey, true), 0, 32);

            // IV must be exact 16 chars (128 bit)
            $iv = chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0);

            $encryptedSring = base64_encode(openssl_encrypt($plaintext, $method, $secureEnvKey, OPENSSL_RAW_DATA, $iv));
        }
        return $encryptedSring;
    }

    /**
     * This method handels Decrypted code with AES Algorythm Custom Method
     * @return  Object
     * @since   2018-07-25
     * @author  NetQuick
     */
    public static function getDecryptedString($encrypted, $getAppKeyFromEnv = false)
    {
        $decryptedSring = '';
        if (!empty($encrypted)) {

            $envKey = Config::get('Constant.ENV_APP_KEY');
            if ($getAppKeyFromEnv) {
                $envKey = env('APP_KEY');
            }
            $method = 'aes-256-cbc';

            // Must be exact 32 chars (256 bit)
            $secureEnvKey = substr(hash('sha256', $envKey, true), 0, 32);

            // IV must be exact 16 chars (128 bit)
            $iv = chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0);

            $decryptedSring = openssl_decrypt(base64_decode($encrypted), $method, $secureEnvKey, OPENSSL_RAW_DATA, $iv);
        }
        return $decryptedSring;
    }

    public static function getLaravelEncryptedString($plaintext)
    {
        $encryptedSring = '';
        if (!empty($plaintext)) {
            $encryptedSring = Crypt::encrypt($plaintext);
        }
        return $encryptedSring;
    }

    public static function getLaravelDecryptedString($encrypted)
    {
        $decryptedSring = '';
        if (!empty($encrypted)) {
            try {
                $decryptedSring = Crypt::decrypt($encrypted);
            } catch (DecryptException $e) {
                $decryptedSring = '';
            }
        }
        return $decryptedSring;
    }

    public static function getCurrentUserRoleDatils()
    {
        $response = false;
        $currentUserRoleData = auth()->user()->roles->first();
        if (!empty($currentUserRoleData)) {
            $response = $currentUserRoleData;
        }
        return $response;
    }

    public static function format_size($size)
    {
        $sizes = array(" Bytes", " KB", " MB", " GB", " TB", " PB", " EB", " ZB", " YB");
        if ($size == 0) {
            return ('n/a');
        } else {
            return (round($size / pow(1024, ($i = floor(log($size, 1024)))), 1) . $sizes[$i]);
        }
    }

    public static function getFrontUri($moduleName, $recordId = false)
    {

        $response = ['uri' => '', 'moduleAlias' => ''];
        $moduleData = Modules::getAllModuleData($moduleName);
        if (!empty($moduleData)) {
            $pageData = Self::$pageModuleData;
            $page = CmsPage::getRecordByModuleId($moduleData->id, $pageData->id);
            $recordAlias = false;

            if ($recordId && !empty($page)) {
                $MODEL = $moduleData->varModelName;
//                $MODEL = '\\App\\' . $MODEL;
                if (Config::get('Constant.MODULE.NAME_SPACE') != '') {
                    $MODEL = Config::get('Constant.MODULE.NAME_SPACE') . 'Models\\' . Config::get('Constant.MODULE.MODEL_NAME');
                } else {
                    $MODEL = '\\App\\' . Config::get('Constant.MODULE.MODEL_NAME');
                }
                $recordData = CommonModel::getPowerPanelRecordById($recordId, $moduleData->id, $MODEL);
                if (isset($recordData->alias->varAlias)) {
                    $recordAlias = $recordData->alias->varAlias;
                }
            }

            if ($moduleName != 'pages') {
                if (isset($page->alias->varAlias) && !empty($page)) {
                    $response['moduleAlias'] = $page->alias->varAlias;
                    $link = url('/' . $page->alias->varAlias);
                    if ($recordAlias) {
                        $link .= '/' . $recordAlias;
                    }
                    $response['uri'] = $link;
                }
            } else {
                $response['uri'] = url('/' . $recordAlias);
            }
        }

        return $response;
    }

    public static function getFront_Uri($moduleName, $recordId = false, $isdetail = false)
    {
        $response = [];
        if ($moduleName != 'pages') {
            $moduleData = Modules::getAllModuleData($moduleName);
            if (!empty($moduleData)) {
                $pageData = Self::$pageModuleData;

                $page = CmsPage::getRecordByModuleId($moduleData->id, $pageData->id);
                $recordAlias = false;

                //$txt = file_get_contents('file.txt');
                // file_put_contents('file.txt', $txt.$moduleName.', '.(int)$pageData->id."\n");

                if ($recordId && !empty($page)) {
                    $MODEL = $moduleData->varModelName;
//                    $MODEL = '\\App\\' . $MODEL;
                    if (Config::get('Constant.MODULE.NAME_SPACE') != '') {
                        $MODEL = Config::get('Constant.MODULE.NAME_SPACE') . 'Models\\' . Config::get('Constant.MODULE.MODEL_NAME');
                    } else {
                        $MODEL = '\\App\\' . Config::get('Constant.MODULE.MODEL_NAME');
                    }
                    $recordData = CommonModel::getFrontRecordById($recordId, $moduleData->id, $MODEL);
                    if (isset($recordData->alias->varAlias)) {

                        $recordAlias = $recordData->alias->varAlias;
                    }
                }

                if (isset($page->alias->varAlias) && !empty($page)) {
                    if ($isdetail) {
                        if ($page->varSector == 'ict' || $page->varSector == 'water' || $page->varSector == 'fuel' || $page->varSector == 'energy') {
                            $slug = '/' . $page->varSector;
                        } else {
                            $slug = '';
                        }
                    } else {
                        $slug = '';
                    }
                    $response['moduleAlias'] = $page->alias->varAlias;
                    $link = url($slug . '/' . $page->alias->varAlias);
                    if ($recordAlias) {
                        $link .= '/' . $recordAlias;
                    }

                    $response['uri'] = $link;

                }
            }
        } else {
            $response['uri'] = url('/');
            if ((int) $recordId > 0) {
                $pageModuleData = Self::$pageModuleData;
                $MODEL = $pageModuleData->varModelName;
                $MODEL = '\\App\\' . $MODEL;
                $recordData = CommonModel::getFrontRecordById($recordId, $pageModuleData->id, $MODEL);
                if (isset($recordData->alias->varAlias)) {
                    $response['uri'] = url('/' . $recordData->alias->varAlias);
                }
            }

            // $txt = file_get_contents('file.txt');
            // file_put_contents('file.txt', $txt.$moduleName.', '.(int)$recordId."\n");
        }
        return $response;
    }

    public static function getRecordAliasByModuleNameRecordId($moduleName, $recordId = false)
    {
        $response = false;
        if ($moduleName != 'pages') {
            $moduleData = Modules::getAllModuleData($moduleName);
            if (!empty($moduleData)) {
                $pageData = Self::$pageModuleData;
                $recordAlias = false;
                if ($recordId) {
                    if (isset($moduleData->varModuleNameSpace) && $moduleData->varModuleNameSpace != '') {
                        $MODEL = $moduleData->varModuleNameSpace . 'Models\\' . $moduleData->varModelName;
                    } else {
                        $MODEL = '\\App\\' . $moduleData->varModelName;
                    }
                    $recordData = CommonModel::getFrontRecordById($recordId, $moduleData->id, $MODEL);
                    if (isset($recordData->alias->varAlias)) {
                        $response = $recordData->alias->varAlias;
                    }
                }
            }
        } else {
            if ((int) $recordId > 0) {
                $pageModuleData = Self::$pageModuleData;
                if (isset($pageModuleData->varModuleNameSpace) && $pageModuleData->varModuleNameSpace != '') {
                    $MODEL = $pageModuleData->varModuleNameSpace . 'Models\\' . $pageModuleData->varModelName;
                } else {
                    $MODEL = '\\App\\' . $pageModuleData->varModelName;
                }
                $recordData = CommonModel::getFrontRecordById($recordId, $pageModuleData->id, $MODEL);
                if (isset($recordData->alias->varAlias)) {
                    $response = $recordData->alias->varAlias;
                }
            }
        }
        return $response;
    }

    public static function getUrlLinkForQlinks($moduleName, $recordId = false)
    {
        $response = [];

        if ($moduleName != 'pages') {
            $response['uri'] = "";
            $moduleData = Modules::getAllModuleData($moduleName);
            if (!empty($moduleData)) {
                $pageData = Self::$pageModuleData;
                if ($moduleName != 'pages') {
                    $page = CmsPage::getRecordByModuleIdForQlink($moduleData->id, $pageData->id);
                } else {
                    $page = CmsPage::getRecordByModuleIdForQlink($moduleData->id, $pageData->id, $recordId);
                }

                $recordAlias = false;

                if ($recordId && !empty($page)) {
                    $MODEL = $moduleData->varModelName;
                    if (isset($moduleData->varModuleNameSpace) && $moduleData->varModuleNameSpace != '') {
                        $MODEL = $moduleData->varModuleNameSpace . 'Models\\' . $MODEL;
                    } else {
                        $MODEL = '\\App\\' . $MODEL;
                    }
                    $recordData = CommonModel::getFrontRecordByIdForQlink($recordId, $moduleData->id, $MODEL);
                    if (isset($recordData->alias->varAlias)) {
                        $recordAlias = $recordData->alias->varAlias;
                    }
                }

                if (isset($page->alias->varAlias) && !empty($page)) {
                    $response['moduleAlias'] = $page->alias->varAlias;
                    $link = url('/' . $page->alias->varAlias);
                    if ($recordAlias) {
                        $link .= '/' . $recordAlias;
                    }
                    if (!empty($page)) {
                        $response['uri'] = $link;
                    }
                }
            }
        } else {
            $response['uri'] = "";
            if ((int) $recordId > 0) {
                $pageModuleData = Self::$pageModuleData;
                $MODEL = $pageModuleData->varModelName;
                if (isset($pageModuleData->varModuleNameSpace) && $pageModuleData->varModuleNameSpace != '') {
                    $MODEL = $pageModuleData->varModuleNameSpace . 'Models\\' . $MODEL;
                } else {
                    $MODEL = '\\App\\' . $MODEL;
                }
                $recordData = CommonModel::getFrontRecordByIdForQlink($recordId, $pageModuleData->id, $MODEL);
                if (isset($recordData->alias->varAlias)) {
                    $response['uri'] = url('/' . $recordData->alias->varAlias);
                }
            }
        }

        return $response;
    }

    public static function getModulePageAliasByModuleName($moduleName)
    {
        $response = false;
        $moduleData = Modules::getAllModuleData($moduleName);
        if (!empty($moduleData)) {
            $pageData = Self::$pageModuleData;
            $page = CmsPage::getRecordByModuleId($moduleData->id, $pageData->id);
            $recordAlias = false;

            if ($moduleName != 'pages') {
                if (isset($page->alias->varAlias) && !empty($page)) {
                    $response = $page->alias->varAlias;
                }
            }
        }

        return $response;
    }

    public static function filePathExist($filepath = false)
    {
        $response = false;
        if (Config::get('Constant.BUCKET_ENABLED')) {
            if (Aws_File_helper::checkObjectExists($filepath)) {
                $response = true;
            }
        } else {
            if (file_exists($filepath)) {
                $response = true;
            }
        }

        return $response;
    }

    public static function getAWSconstants()
    {
        $response = array();
        $response['BUCKET_ENABLED'] = false;
        $response['CDN_PATH'] = Config::get('Constant.CDN_PATH');
        if (Config::get('Constant.BUCKET_ENABLED')) {
            $response['BUCKET_ENABLED'] = Config::get('Constant.BUCKET_ENABLED');
            $response['S3_MEDIA_BUCKET_PATH'] = Config::get('Constant.S3_MEDIA_BUCKET_PATH');
            $response['S3_MEDIA_BUCKET_DOCUMENT_PATH'] = Config::get('Constant.S3_MEDIA_BUCKET_DOCUMENT_PATH');
            $response['S3_MEDIA_BUCKET_GENERAL_PATH'] = Config::get('Constant.S3_MEDIA_BUCKET_GENERAL_PATH');
            $response['S3_MEDIA_BUCKET_VIDEO_PATH'] = Config::get('Constant.S3_MEDIA_BUCKET_VIDEO_PATH');
        }
        return $response;
    }

    public static function getPercentageByInerestRatemonth($currentMonthVal, $prevMonthVal, $identity = "default")
    {
        $retunArray = array('percentage' => 0, 'perstatus' => 'fa fa-level-up', 'percolor' => 'green');

        if ($identity == "fltDiscount_rate" || $identity == "fltThree_month_deposits" || $identity == "fltRes_mortgages" || $identity == "fltConsumer_loans" || $identity == "fltPrime_rate" || $identity == "fltTreasury_bill_rate") {
            $lq = number_format((($currentMonthVal - $prevMonthVal) * 100), 1);
            if ($lq == 0) {
                $status = '';
            } else {
                $status = ($lq < 0) ? "fa fa-level-down" : "fa fa-level-up";
            }
            $color = ($lq < 0) ? "red" : "green";
        } else {
            $identity = "default";
        }

        if ($identity == "default") {
            if ($prevMonthVal > 0) {
                $lq = number_format((($currentMonthVal / $prevMonthVal * 100) - 100), 1);
            } else {
                $lq = number_format(0, 1);
            }
            if ($lq == 0) {
                $status = '';
            } else {
                $status = ($lq < 0) ? "fa fa-level-down" : "fa fa-level-up";
            }
            $color = ($lq < 0) ? "red" : "green";
        }

        $retunArray = array('percentage' => $lq, 'perstatus' => $status, 'percolor' => $color);
        return $retunArray;
    }

    public static function buildTree(array $elements, $parentId = 0)
    {
        $branch = array();

        if (!empty($elements)) {
            foreach ($elements as $element) {
                if ($element['intParentCategoryId'] == $parentId) {
                    $children = self::buildTree($elements, $element['id']);
                    if ($children) {
                        $element['children'] = $children;
                    }
                    $branch[] = $element;
                }
            }
        }

        return $branch;
    }

    public static function getParentsNames($parentId = 0, $modelname, $branch = array())
    {
        $moduleFileds = ['id', 'varTitle', 'intParentCategoryId'];
        $getRecords = $modelname::select($moduleFileds)
            ->where('chrMain', 'Y')
            ->where('chrIsPreview', 'N')
            ->where('chrMain', 'Y')
            ->where('chrDelete', 'N')
            ->where('chrPublish', 'Y')
            ->where('id', $parentId)
            ->first();
        if (!empty($getRecords) && count($getRecords) > 0) {
            array_push($branch, $getRecords);
            if ($getRecords['intParentCategoryId'] != 0) {
                return self::getParentsNames($getRecords['intParentCategoryId'], $modelname, $branch);
            }
        }

        return $branch;
    }

    public static function getModuleWiseRecordData($modelName = false, $tableName = false, $recordId = false)
    {
        $response = false;
        $recordFileds = [];
        if ($tableName == "contact_lead") {
            $titleField = ['varName as Title', 'varEmail'];
        }elseif($tableName == "number_allocation"){
            $titleField = ['nxx as Title'];
        }else {
            $titleField = ['varTitle as Title'];
        }
        $recordFileds = $titleField;

        $response = DB::table($tableName)
        //if($tableName == "contact_lead")
            ->select($recordFileds)
            ->where('chrDelete', 'N')
            ->where('id', $recordId)
            ->first();
        return $response;
    }

    public static function GetHideCookies($module = '', $gridhead = '', $tabidentity = '', $tabindex = '')
    {
        $cookiename = array();
        $titleclass = 'checked="checked"';
        if ($module != "") {
            array_push($cookiename, $module);
        }
        if ($gridhead != "") {
            array_push($cookiename, $gridhead);
        }
        if ($tabidentity != "") {
            array_push($cookiename, $tabidentity);
        }
        if ($tabindex != "") {
            array_push($cookiename, $tabindex);
        }
        if (!empty($cookiename)) {
            $cookiename = implode('_', $cookiename);
            if (isset($_COOKIE[$cookiename])) {
                if (isset($_COOKIE[$cookiename]) && $_COOKIE[$cookiename] == 'Y') {
                    $titleclass = 'checked="checked"';
                } else {
                    $titleclass = '';
                }
            } else {
                $titleclass = 'checked="checked"';
            }
        }
        return $titleclass;
    }

    public static function count_days($date)
    {
        $datestring = date("Y-m-d", strtotime($date));
        $currentdate = date('Y-m-d');
        $date1 = date_create($datestring);
        $date2 = date_create($currentdate);
        $diff = date_diff($date1, $date2);
        $days = $diff->format("%a");
        return $days;
    }

    public static function GetTemplateData()
    {
        $userIsAdmin = false;
        $currentUserRoleData = auth()->user()->roles->first();
        if (!empty($currentUserRoleData)) {
            $udata = $currentUserRoleData;
        }
        if ($udata->chrIsAdmin == 'Y') {
            $userIsAdmin = true;
        }
        $userid = auth()->user()->id;
        $pagedata = DB::table('visultemplate')
            ->select('*');
        if (!$userIsAdmin) {
            $pagedata = $pagedata->where(function ($query) use ($userid) {
                $query->where("UserID", '=', $userid)->where('chrDisplayStatus', '=', 'PR')
                    ->orWhere('chrDisplayStatus', '=', 'PU');
            });
        }
        $pagedata = $pagedata->where('chrDelete', '=', 'N')
            ->where('chrPublish', '=', 'Y')
            ->where('txtDesc', '!=', '')
            ->where('chrIsPreview', 'N')
            ->orderBy('created_at', 'desc')
            ->get();
        return $pagedata;
    }

    public static function GetFormBuilderData()
    {
        $userIsAdmin = false;
        $currentUserRoleData = auth()->user()->roles->first();
        if (!empty($currentUserRoleData)) {
            $udata = $currentUserRoleData;
        }
        if ($udata->chrIsAdmin == 'Y') {
            $userIsAdmin = true;
        }
        $pagedata = DB::table('form_builder')
            ->select('*');
        if (!$userIsAdmin) {
            $pagedata = $pagedata->where('UserID', auth()->user()->id);
        }
        $pagedata = $pagedata->where('chrDelete', '=', 'N')
            ->where('chrPublish', '=', 'Y')
            ->orderBy('created_at', 'desc')
            ->get();
        return $pagedata;
    }

    public static function obfuscate_email($email)
    {
        $em = explode("@", $email);
        $name = implode('@', array_slice($em, 0, count($em) - 1));
        $len = floor(strlen($name) / 2);
        return substr($name, 0, $len) . str_repeat('*', $len) . "@" . end($em);
    }

    public static function check_doc_mime($tmpname)
    {
        // MIME types: http://filext.com/faq/office_mime_types.php
        
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mtype = finfo_file($finfo, $tmpname);
        finfo_close($finfo);

        //$mtype =  mime_content_type($tmpname);
        $validmimeTypeArray = [
            "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
            "application/vnd.ms-excel",
            "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
            "application/vnd.ms-powerpoint",
            "application/vnd.openxmlformats-officedocument.presentationml.presentation",
            "application/excel",
            "application/x-excel",
            "application/x-msexcel",
            "application/pdf",
            "application/msword",
            "application/vnd.openxmlformats-officedocument.wordprocessingml.template",
            "application/vnd.ms-word.document.macroEnabled.12",
            "application/vnd.ms-word.template.macroEnabled.12",
            "application/vnd.openxmlformats-officedocument.spreadsheetml.template",
            "application/vnd.ms-excel.sheet.macroEnabled.12",
            "application/vnd.ms-excel.template.macroEnabled.12",
            "application/vnd.ms-excel.addin.macroEnabled.12",
            "application/vnd.ms-excel.sheet.binary.macroEnabled.12",
            "application/vnd.openxmlformats-officedocument.presentationml.template",
            "application/vnd.openxmlformats-officedocument.presentationml.slideshow",
            "application/vnd.ms-powerpoint.addin.macroEnabled.12",
            "application/vnd.ms-powerpoint.presentation.macroEnabled.12",
            "application/vnd.ms-powerpoint.template.macroEnabled.12",
            "application/vnd.ms-powerpoint.slideshow.macroEnabled.12",
            "application/doc",
            "application/ms-doc",
            "application/mspowerpoint",
            "application/powerpoint",
            "application/x-mspowerpoint",
            "text/plain",
            "application/x-compressed",
            "application/x-zip-compressed",
            "application/zip",
            "multipart/x-zip",
            "application/vnd.ms-office",
        ];

        if (in_array($mtype, $validmimeTypeArray)) {
            return true;
        } else {
            return false;
        }
    }

    public static function getLabelforformbuilder($inputvalue, $inputBuilderArray)
    {
        $response = "";
        foreach ($inputBuilderArray as $buildValue) {
            $inpuval = explode('*', $inputvalue);
            if (isset($inpuval[1])) {
                if ($buildValue->label == $inpuval[1]) {
                    if ($buildValue->value == $inpuval[0]) {
                        $response = $buildValue->label;
                        break;
                    }
                }
            }
        }

        return $response;
    }

    public static function getEmailCountry($id)
    {
        $data = DB::table('country')
            ->select('*')
            ->where('id', $id)
            ->get();
        return $data;
    }

    public static function getEmailState($id)
    {
        $data = DB::table('state')
            ->select('*')
            ->where('id', $id)
            ->get();
        return $data;
    }

    public static function SendNotificationData($title, $body)
    {
        $agent = new Agent();
        $mybrowser = $agent->browser();
        $data = DB::table('notificationtoken')
            ->select('*')
            ->get();
        foreach ($data as $ndata) {
            if ($ndata->browser != $mybrowser) {
                $tokens = $ndata->notificationtoken;
                $url = 'https://fcm.googleapis.com/fcm/send';
                $headers = array(
                    'Content-Type:application/json',
                    'Authorization:key=' . Config::get('Constant.Authorization_Key'),
                );

                $key = 'registration_ids';
                $to = [$tokens];

                $payload = [
                    "notification" => [
                        "title" => $title,
                        "body" => $body,
                    ],
                    $key => $to,
                ];

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
                $result = curl_exec($ch);
                curl_close($ch);
                return $result;
            }
        }
    }

    public static function GetFolderID($id)
    {
        $folderdata = DB::table('image')
            ->select('*')
            ->where('id', '=', $id)
            ->first();
        return $folderdata;
    }

    public static function GetDocumentFolderID($id)
    {
        $folderdata = DB::table('documents')
            ->select('*')
            ->where('id', '=', $id)
            ->first();
        return $folderdata;
    }

    public static function DateTimeFormat()
    {
        $dt_time = Config::get('Constant.DEFAULT_DATE_FORMAT');
        $dateReplace = str_replace('d', 'dd', $dt_time);
        $yearReplace = str_replace('Y', 'yyyy', $dateReplace);
        return $yearReplace;
    }

    function decryptLatest($string) {
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $secret_key = 'OVVIHQ1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $secret_iv = 'OVVIHQ1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $key = hash('sha256', $secret_key); // hash
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        $output = openssl_decrypt(($string), $encrypt_method, $key, 0, $iv);
        return $output;
    }

}
