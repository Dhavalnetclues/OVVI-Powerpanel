<?php

namespace App;

use App\Helpers\MyLibrary;
use Config;
use DB;
use App\Modules;
use Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CommonModel extends Model {

    /**
     * This method handels insert of event record
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public static function addRecord($data = false, $modelNameSpace = false) {
        $response = false;
        if ($data != false && !empty($data)) {
            if ($modelNameSpace == false) {
                $modelNameSpace = MyLibrary::getModelNameSpace();
            }
            
            $recordId = $modelNameSpace::insertGetId($data);
            if ($recordId > 0) {
                $response = $recordId;
            }
        }
        return $response;
    }

    public static function getFrontRecordById($id, $moduleCode = false, $modelNameSpace = false) {
        $response = false;
        $moduleFields = ['id', 'intAliasId'];
        $aliasFields = ['id', 'varAlias'];

        $response = Self::getPowerPanelRecords($moduleFields, $aliasFields, $moduleCode, $modelNameSpace);
        $response = $response->checkRecordId($id)
                ->where('chrIsPreview', 'N')
                ->deleted()
                ->publish()
                ->where('chrMain', 'Y')
                ->first();
        return $response;
    }

    public static function addTemplateRecord($data) {
        $user = DB::table('visultemplate')->insertGetId($data);
        return $user;
    }

    public static function getFrontRecordByIdForQlink($id, $moduleCode = false, $modelNameSpace = false) {
        $response = false;
        $moduleFields = ['id', 'intAliasId'];
        $aliasFields = ['id', 'varAlias'];

        $response = Self::getPowerPanelRecords($moduleFields, $aliasFields, $moduleCode, $modelNameSpace);
        $response = $response->checkRecordId($id)
                ->where('chrIsPreview', 'N')
                ->deleted()
                ->publish()
                ->where('chrMain', 'Y')
                ->first();
        return $response;
    }

    public static function getPowerPanelRecordById($id, $moduleCode = false, $modelNameSpace = false) {
        $response = false;
        $moduleFields = ['id', 'intAliasId'];
        $aliasFields = ['id', 'varAlias'];
        $response = Self::getPowerPanelRecords($moduleFields, $aliasFields, $moduleCode, $modelNameSpace);
        $response = $response->checkRecordId($id)->first();
        return $response;
    }

    public static function getPowerPanelRecords($moduleFields = false, $aliasFields = false, $moduleCode = false, $modelNameSpace = false) {
        $data = [];
        $pageObj = $modelNameSpace::select($moduleFields);
        if ($aliasFields != false) {
            $data['alias'] = function ($query) use ($aliasFields, $moduleCode) {
                $query->select($aliasFields)->checkModuleCode($moduleCode);
            };
        }

        if (count($data) > 0) {
            $pageObj = $pageObj->with($data);
        }
        return $pageObj;
    }

    public function scopeCheckRecordId($query, $id) {
        return $query->where('id', $id);
    }

    public function scopeDeleted($query) {
        return $query->where(['chrDelete' => 'N']);
    }

    public function scopePublish($query) {
        return $query->where(['chrPublish' => 'Y']);
    }

    public function alias() {
        return $this->belongsTo('App\Alias', 'intAliasId', 'id');
    }

    public static function updateRecords($whereCondArr = false, $data = false, $orWhereCondArr = false, $modelNameSpace = false) {
        $response = false;
        if (!empty($whereCondArr) && !empty($data)) {
            if ($modelNameSpace == false) {
                $modelNameSpace = MyLibrary::getModelNameSpace();
            }
            $update = $modelNameSpace::where($whereCondArr);
            if ($orWhereCondArr != false) {
                $update = $update->orWhere($orWhereCondArr);
            }
            $update->update($data);
            $response = $update;
        }
        return $response;
    }

    public static function updateMultipleRecords($whereCondArr = false, $data = false, $orWhereCondArr = false, $modelNameSpace = false) {
        $response = false;
        if (!empty($whereCondArr) && !empty($data)) {
            if ($modelNameSpace == false) {
                $modelNameSpace = MyLibrary::getModelNameSpace();
            }
            $update = $modelNameSpace::whereIn('id', $whereCondArr);
            if ($orWhereCondArr != false) {
                $update = $update->orWhere($orWhereCondArr);
            }
            $update->update($data);
            $response = $update;
        }
        return $response;
    }

    public static function getRecordByOrder($modelNameSpace = false, $order = false) {

        $response = false;
        $response = $modelNameSpace::getRecordByOrder($order);
        return $response;
    }

    public static function tempUpdateOrder($objects, $minOrder) {
        DB::update(DB::raw("UPDATE
				nq_" . Config::get('Constant.MODULE.TABLE_NAME') . " SET `intDisplayOrder` = `intDisplayOrder` - 1
				WHERE `intDisplayOrder` > " . $minOrder . "
				AND chrDelete='N'
				AND intDisplayOrder != 0 "));
    }

    public static function updateOrder($objects) {
        DB::update(DB::raw("UPDATE
				nq_" . Config::get('Constant.MODULE.TABLE_NAME') . " SET `intDisplayOrder` = `intDisplayOrder` - 1
				WHERE `intDisplayOrder` > " . $objects->intDisplayOrder . "
				AND chrDelete='N'
				AND intDisplayOrder != 0 "));
    }

    public static function updateOrderAfterAdd($addedOrder, $existingRecId, $isCustomize = false, $condtionFileds = array()) {
        $updateSqlQuery = "UPDATE
				nq_" . Config::get('Constant.MODULE.TABLE_NAME') . " SET `intDisplayOrder` = `intDisplayOrder` + 1
				WHERE `intDisplayOrder` >= " . $addedOrder . "
				AND `chrDelete` ='N'
				AND `intDisplayOrder` != 0
				AND `intDisplayOrder` != 1
				AND `id` != " . $existingRecId . "";

        if ($isCustomize) {
            if (!empty($condtionFileds)) {
                if (in_array('chrMain', $condtionFileds)) {
                    $updateSqlQuery .= " AND chrMain='Y' AND fkMainRecord = 0";
                }

                if (in_array('chrIsPreview', $condtionFileds)) {
                    $updateSqlQuery .= " AND chrIsPreview='N'";
                }
            }
        }

        DB::update(DB::raw($updateSqlQuery));
    }

    public static function updateOrderAfterEdit($currentOrder, $updatedOrder, $currentRecordId, $isCustomize = false, $condtionFileds = array()) {
        if ($currentOrder > $updatedOrder) {

            $updateSqlQuery = "UPDATE
				nq_" . Config::get('Constant.MODULE.TABLE_NAME') . " SET `intDisplayOrder` = `intDisplayOrder` + 1
				WHERE `intDisplayOrder` <" . $currentOrder . "
				AND `intDisplayOrder` >=" . $updatedOrder . "
				AND `chrDelete` ='N'
				AND `intDisplayOrder` != 0
				AND `id` !=" . $currentRecordId . "";

            if ($isCustomize) {
                if (!empty($condtionFileds)) {
                    if (in_array('chrMain', $condtionFileds)) {
                        $updateSqlQuery .= " AND chrMain='Y' AND fkMainRecord = 0";
                    }

                    if (in_array('chrIsPreview', $condtionFileds)) {
                        $updateSqlQuery .= " AND chrIsPreview='N'";
                    }
                }
            }

            DB::update(DB::raw($updateSqlQuery));
        } else {

            $updateSqlQuery = "UPDATE
				nq_" . Config::get('Constant.MODULE.TABLE_NAME') . " SET `intDisplayOrder` = `intDisplayOrder` - 1
				WHERE `intDisplayOrder` >" . $currentOrder . "
				AND `intDisplayOrder` <=" . $updatedOrder . "
				AND `chrDelete` ='N'
				AND `intDisplayOrder` != 0
				AND `intDisplayOrder` != 1
				AND `id` !=" . $currentRecordId . "";

            if (!empty($condtionFileds)) {
                if (in_array('chrMain', $condtionFileds)) {
                    $updateSqlQuery .= " AND chrMain='Y' AND fkMainRecord = 0";
                }

                if (in_array('chrIsPreview', $condtionFileds)) {
                    $updateSqlQuery .= " AND chrIsPreview='N'";
                }
            }

            DB::update(DB::raw($updateSqlQuery));
        }
    }

    /* code for modified Table for new functionality */

    public static function updateModifiedModuleOrder($objects, $condtionFileds = array()) {

        $updateSqlQuery = "UPDATE
				nq_" . Config::get('Constant.MODULE.TABLE_NAME') . " SET `intDisplayOrder` = `intDisplayOrder` - 1
				WHERE `intDisplayOrder` > " . $objects->intDisplayOrder . "
				AND chrDelete='N'
				AND intDisplayOrder != 0 ";
        if (!empty($condtionFileds)) {
            if (in_array('chrMain', $condtionFileds)) {
                $updateSqlQuery .= " AND chrMain='Y' AND fkMainRecord = 0";
            }

            if (in_array('chrIsPreview', $condtionFileds)) {
                $updateSqlQuery .= " AND chrIsPreview='N'";
            }
        }

        if (isset($objects->fkMainRecord) && $objects->fkMainRecord == '0') {
            DB::update(DB::raw($updateSqlQuery));
        }
    }

    /* end of code for modified Table for new functionality */

    /**
     * This method handels reorder tour wise record
     * @return  Object
     * @since   2018-07-09
     * @author  K
     */
    public static function updatePhotoGalleryOrder($objects, $albumId) {
        DB::update(DB::raw("UPDATE
					nq_" . Config::get('Constant.MODULE.TABLE_NAME') . " SET `intDisplayOrder` = `intDisplayOrder` - 1
									WHERE `intDisplayOrder` > " . $objects->intDisplayOrder . "
									AND fkIntAlbumId=" . $albumId . "
					AND chrDelete='N' 
					AND intDisplayOrder != 0 "));
    }

    /**
     * This method handels retrival of record count
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getRecordCount($filterArr = false, $returnCounter = false, $modelNameSpace = false, $nameSpace = 'App\\') {
        $response = false;
        $moduleFields = ['id'];
        if ($nameSpace == 'App\\') {
            $modelNameSpace = MyLibrary::getModelNameSpace($nameSpace);
        } else {
            $modelNameSpace = $nameSpace;
        }
        $response = $modelNameSpace::getPowerPanelRecords($moduleFields)->deleted();

        if ($filterArr != false) {
            $response = $response->filter($filterArr, $returnCounter);
        }

        $response = $response->count();
        return $response;
    }

    public static function getUserName($userID = false) {

        $user = DB::table('users')->select('name')->where('id', $userID)->first();
        if (!empty($user)) {
            return $user->name;
        } else {
            return '-';
        }
    }

    /**
     * This method handels retrival of record for delete
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getRecordsForDeleteById($id, $modelName = false, $modelNameSpace = false, $modifiedModuleFields = false) {
        $response = false;
        $moduleFields = array('id');
        if ($modelName == false) {
            $modelName = Config::get('Constant.MODULE.MODEL_NAME');
        }
        $titleField = 'varTitle';
        $displayOrderField = true;
        $nameFieldModules = array('User','Payonline');
        $varNameFieldModules = array('ContactLead', 'NewsletterLead', 'RestaurantReservations', 'AppointmentLead', 'FeedbackLead', 'FormBuilder', 'PageTemplate');
        if (in_array($modelName, $nameFieldModules)) {
            $titleField = 'name';
        } else if (in_array($modelName, $varNameFieldModules)) {
            $titleField = 'varName';
        } else if ($modelName == 'NumberAllocation'){
            $titleField = 'nxx';
        } else if ($modelName == 'EventsLead'){
            $titleField = 'eventId';
        } else if ($modelName == 'varTitle'){
            $titleField = 'varTitle';
        } else {
            $titleField = 'varTitle';
        }

        if ($modelName == "NotificationList") {
            $titleField = 'txtNotification';
        }

        if ($modelName == "PageTemplates") {
              
            $titleField = 'varTemplateName';
        }

        if (!empty($titleField)) {
            array_push($moduleFields, $titleField);
        }

        $avoidDisplayOrderFieldModules = array('CmsPage', 'StaticBlocks', 'ContactInfo', 'ContactLead', 'NewsletterLead', 'EventLead', 'Publications', 'Workflow', 'News', 'FeedbackLead', 'HitsReport', 'SearchStatictics', 'RFP', 'NotificationList', 'FormBuilder', 'PageTemplates');

        if (in_array($modelName, $avoidDisplayOrderFieldModules)) {
            $displayOrderField = false;
        }
        if ($displayOrderField) {
             if (Schema::hasColumn(Config::get('Constant.MODULE.TABLE_NAME'), 'intDisplayOrder')) {
            array_push($moduleFields, 'intDisplayOrder');
             }
        }

        if (!empty($modifiedModuleFields)) {
            if (in_array('chrMain', $modifiedModuleFields)) {
                array_push($moduleFields, 'chrMain', 'fkMainRecord');
            }

            if (in_array('chrIsPreview', $modifiedModuleFields)) {
                array_push($moduleFields, 'chrIsPreview');
            }
        }

        if ($modelNameSpace == false) {
            $modelNameSpace = MyLibrary::getModelNameSpace();
        }

        $response = $modelNameSpace::getPowerPanelRecords($moduleFields)->checkRecordId($id)->first();
        return $response;
    }

    public static function getMinDisplayOrdersforDelete($ids, $modifiedModuleFields = false) {
        $tablename = Config::get('Constant.MODULE.TABLE_NAME');
        $modulefields = ['id', 'intDisplayOrder'];
        $modulefields = array_merge($modulefields, $modifiedModuleFields);
        $repsonse = DB::table($tablename)
                ->selectRaw('min(`intDisplayOrder`) as minorder')
                ->whereIn('id', $ids);
        if (in_array('chrMain', $modifiedModuleFields)) {
            $repsonse = $repsonse->where('chrMain', 'Y')->where('fkMainRecord', '0');
        }
        if (in_array('chrIsPreview', $modifiedModuleFields)) {
            $repsonse = $repsonse->where('chrIsPreview', 'N');
        }
        $repsonse = $repsonse->first();
        return $repsonse;
    }

    public static function setDisplayOrderSequence($modifiedModuleFields = array(), $tableName = false) {
        $repsonse = false;
        $tablename = 'nq_' . Config::get('Constant.MODULE.TABLE_NAME');
        if ($tableName) {
            $tablename = $tableName;
        }

        $selectQuery = "SELECT id FROM " . $tablename . " WHERE chrDelete = 'N' ";

        if (in_array('chrMain', $modifiedModuleFields)) {
            $selectQuery .= " AND chrMain='Y' AND fkMainRecord='0'";
        }
        if (in_array('chrIsPreview', $modifiedModuleFields)) {
            $selectQuery .= " AND chrIsPreview='N'";
        }

        $selectQuery .= " ORDER BY intDisplayOrder asc";

        $results = DB::select($selectQuery);
        $total = count($results);
        $i = 0;
        $update_syntax = "";
        if ($total > 0) {
            foreach ($results as $row) {
                $i++;
                $ids[$i] = $row->id;
                $update_syntax .= " WHEN " . $row->id . " THEN $i ";
            }
        }
        if ($total > 0) {
            $updateSqlQuery = "UPDATE " . $tablename . " SET intDisplayOrder = (CASE id " . $update_syntax . " ELSE intDisplayOrder END) WHERE id BETWEEN " . min($ids) . " AND " . max($ids) . " and chrDelete = 'N' ";

            if (in_array('chrMain', $modifiedModuleFields)) {
                $updateSqlQuery .= " AND chrMain='Y' AND fkMainRecord='0'";
            }
            if (in_array('chrIsPreview', $modifiedModuleFields)) {
                $updateSqlQuery .= " AND chrIsPreview='N'";
            }

            DB::update(DB::raw($updateSqlQuery));
        }
    }

    public static function tempupdateModifiedModuleOrder($objects, $minorder, $condtionFileds = array()) {

        $updateSqlQuery = "UPDATE
				nq_" . Config::get('Constant.MODULE.TABLE_NAME') . " SET `intDisplayOrder` = `intDisplayOrder` - 1
				WHERE `intDisplayOrder` > " . $minorder . "
				AND chrDelete='N'
				AND intDisplayOrder != 0 ";
        if (!empty($condtionFileds)) {
            if (in_array('chrMain', $condtionFileds)) {
                $updateSqlQuery .= " AND chrMain='Y' AND fkMainRecord = 0";
            }

            if (in_array('chrIsPreview', $condtionFileds)) {
                $updateSqlQuery .= " AND chrIsPreview='N'";
            }
        }
        if (isset($objects->fkMainRecord) && $objects->fkMainRecord == '0') {
            DB::update(DB::raw($updateSqlQuery));
        }
    }

    public static function getDisplayOrdersforDelete($ids, $modifiedModuleFields = false) {
        $tablename = Config::get('Constant.MODULE.TABLE_NAME');
        $modulefields = ['id', 'intDisplayOrder'];
        if (in_array('chrMain', $modifiedModuleFields)) {
            array_push($modulefields, 'chrMain', 'fkMainRecord');
        }
        if (in_array('chrIsPreview', $modifiedModuleFields)) {
            array_push($modulefields, 'chrIsPreview');
        }

        $modulefields = array_merge($modulefields, $modifiedModuleFields);
        $repsonse = DB::table($tablename)->
                select($modulefields)
                ->whereIn('id', $ids)
                ->orderBy('intDisplayOrder', 'desc')
                ->get();
        return $repsonse;
    }

    /**
     * This method handels retrival of latest change requests
     * @return  Integer
     * @since   2018-10-04
     * @author  NetQuick
     */
    public static function getLatestsProposalsCounts($mainId) {
        $response = 0;
        $modelNameSpace = MyLibrary::getModelNameSpace();
        $lastApproved = $modelNameSpace::select('updated_at')
                ->where('chrApproved', 'Y')
                ->where('fkMainRecord', $mainId)
                ->first();
        if (!empty($lastApproved)) {
            $response = $modelNameSpace::select('id')
                    ->where('created_at', '>', $lastApproved->updated_at)
                    ->where('fkMainRecord', $mainId)
                    ->where('chrDelete', 'N')
                    ->count();
        }
        return $response;
    }

    /**
     * This method handels retrival of backend record list
     * @return  Object
     * @since   2017-10-24
     * @author  NetQuick
     */
    public static function getCronRecord($model, $id, $type = false) {
        $response = false;
        $moduleFields = [
            'id',
            'UserID',
            'created_at'
        ];
        if(Str::contains($model, 'NumberAllocation')) {
            array_push($moduleFields, 'nxx');
        } else {
            array_push($moduleFields, 'varTitle');
        }
        if ($type == 'approvals') {
            $moduleFields[] = 'fkMainRecord';
        }
        $response = $model::select($moduleFields)
                ->where('fkMainRecord', $id)
                // ->where('chrPublish','Y')
                // ->where('chrDelete','N')
                /* ->where('id',$id) */
                ->first();
        return $response;
    }

    public static function getTotalRecordCount($modelNameSpace = false, $chekPreview = false, $filter = false) {
        $response = false;
        $moduleFields = ['id'];

        if ($modelNameSpace == false) {
            $modelNameSpace = MyLibrary::getModelNameSpace();
        }

        $response = $modelNameSpace::getPowerPanelRecords($moduleFields)->deleted();

//				if ($chekPreview) {
//					$response = $response->checkIsNotPreview();
//				}

        $response = $response->filter($filter)->count();
        return $response;
    }

    public static function getFormBuilderData($id) {
        $pagedata = DB::table('form_builder')
                ->select('*')
                ->where('id', '=', $id)
                ->where('chrPublish', 'Y')
                ->where('chrDelete', 'N')
                ->first();
        return $pagedata;
    }

    public static function GridColumnData($moduleid) {
        $pagedata = DB::table('gridsetting')
                ->select('*')
                ->where('moduleid', '=', $moduleid)
                ->where('UserID', '=', auth()->user()->id)
                ->groupBy('chrtab')
                ->groupBy('columnid')
                ->get();
        return $pagedata;
    }

}
