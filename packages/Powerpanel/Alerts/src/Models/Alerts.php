<?php

/**
 * The Banner class handels bannner queries
 * ORM implemetation.
 * @package   Netquick powerpanel
 * @license   http://www.opensource.org/licenses/BSD-3-Clause
 * @version   1.1
 * @since   	2017-07-20
 */

namespace Powerpanel\Alerts\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\CommonModel;
use App\Helpers\MyLibrary;
use Powerpanel\CmsPage\Models\CmsPage;
use Cache;

class Alerts extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'alerts';
    protected $fillable = [
        'id',
        'fkMainRecord',
        'fkIntPageId',
        'fkModuleId',
        'varTitle',
        'varSector',
        'varExtLink',
        'varLinkType',
        'intDisplayOrder',
        'txtDescription',
        'varShortDescription',
        'fkIntDocId',
        'chrMain',
        'chrAddStar',
        'chrPublish',
        'chrDelete',
        'chrApproved',
        'intApprovedBy',
        'UserID',
        'intSearchRank',
        'intAlertType',
        'FavoriteID',
        'dtDateTime',
        'dtEndDateTime',
        'chrDraft',
        'chrTrash',
        'chrArchive',
        'chrRollBack',
        'LockUserID', 'chrLock',
        'created_at',
        'updated_at'
    ];

    /**
     * This method handels retrival of home banner record count
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function externalLinksCount() {
        $response = false;
        $moduleFields = ['id'];
        $response = Self::getPowerPanelRecords($moduleFields)
                ->checkExternalLinkType()
                ->deleted()
                ->count();
        return $response;
    }

    /**
     * This method handels retrival of inner banner record count
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function internalLinksCount() {
        $response = false;
        $moduleFields = ['id'];
        $response = Self::getPowerPanelRecords($moduleFields)
                ->checkInternalLinkType()
                ->deleted()
                ->count();
        return $response;
    }






    public static function getRecordList($filterArr = false, $isAdmin = false, $ignoreId = array(), $userRoleSector = false) {
        $response = false;
        $moduleFields = [
            'id',
            'varTitle','varSector',
            'varExtLink',
            'varLinkType',
            'chrPublish',
            'fkIntPageId',
            'intSearchRank',
            'intAlertType',
            'varShortDescription',
            'fkIntDocId',
            'FavoriteID',
            'fkModuleId',
            'chrAddStar',
            'chrDraft',
            'chrArchive',
            'intDisplayOrder',
            'LockUserID', 'chrLock',
            'dtDateTime',
            'dtEndDateTime'
        ];
        $pageFields = ['id', 'varTitle'];
        $mdlFields = ['id', 'varTitle'];
        $response = Self::getPowerPanelRecords($moduleFields, $pageFields, $mdlFields)
                ->deleted()
                ->filter($filterArr)
                ->checkMainRecord('Y')
                ->where('chrTrash', '!=', 'Y')
                ->where(function ($query) {
                    $query->where(function ($query) {
                        $query->where("chrArchive", '!=', 'Y')
                        ->whereRaw('(dtEndDateTime >= NOW() OR dtEndDateTime is null)');
                    });
                })
                ->whereNotIn('id', $ignoreId);
                if(!$isAdmin){
                    $response = $response->where('varSector', $userRoleSector);
                };
        $response = $response->get();
        return $response;
    }

    public static function getRecordListApprovalTab($filterArr = false, $isAdmin = false, $ignoreId = array(), $userRoleSector) {
        $response = false;
        $MainIDs = Self::distinct()
                ->select("fkMainRecord")
                ->checkMainRecord('N')
                ->groupBy('fkMainRecord')
                ->deleted()
                ->get()
                ->toArray();
        $MainIDs = array_column($MainIDs, 'fkMainRecord');
        $moduleFields = [
            'id',
            'varTitle','varSector',
            'varExtLink',
            'varLinkType',
            'chrPublish',
            'fkIntPageId',
            'fkModuleId',
            'intDisplayOrder',
            'intSearchRank',
            'varShortDescription',
            'intAlertType',
            'FavoriteID',
            'fkIntDocId',
            'chrPublish',
            'chrAddStar',
            'chrApproved',
            'intApprovedBy',
            'UserID',
            'chrRollBack',
            'chrAddStar',
            'chrDraft',
            'chrArchive',
            'dtDateTime',
            'dtEndDateTime',
            'LockUserID', 'chrLock',
            'created_at',
            'updated_at'
        ];
        $response = Self::getPowerPanelRecords($moduleFields)
                ->deleted()
                ->filter($filterArr)
                ->whereIn('id', $MainIDs)
                ->checkStarRecord('Y')
                ->where(function ($query) {
                    $query->where(function ($query) {
                        $query->where("chrArchive", '!=', 'Y')
                        ->whereRaw('(dtEndDateTime >= NOW() OR dtEndDateTime is null)');
                    });
                })
                ->whereNotIn('id', $ignoreId);
                if(!$isAdmin){
                    $response = $response->where('varSector', $userRoleSector);
                };
                $response = $response->where('chrTrash', '!=', 'Y')
                ->get();
        return $response;
    }

    public static function getRecordListFavorite($filterArr = false, $isAdmin = false, $ignoreId = array(), $userRoleSector) {
        $response = false;
        $userid = auth()->user()->id;
        $moduleFields = [
            'id',
            'varTitle','varSector',
            'varExtLink',
            'varLinkType',
            'chrPublish',
            'fkIntPageId',
            'intSearchRank',
            'intAlertType',
            'FavoriteID',
            'fkModuleId',
            'fkIntDocId',
            'chrAddStar',
            'chrDraft',
            'chrArchive',
            'intDisplayOrder',
            'LockUserID', 'chrLock',
            'dtDateTime',
            'dtEndDateTime'
        ];
        $response = Self::getPowerPanelRecords($moduleFields)
                ->deleted()
                ->filter($filterArr)
                ->checkMainRecord('Y')
                ->where('chrTrash', '!=', 'Y')
                ->whereRaw("find_in_set($userid,FavoriteID)")
                ->where(function ($query) {
                    $query->where(function ($query) {
                        $query->where("chrArchive", '!=', 'Y')
                        ->whereRaw('(dtEndDateTime >= NOW() OR dtEndDateTime is null)');
                    });
                })
                ->where(function ($query) use ($userid) {
                    $query->where("UserID", '=', $userid)->where('chrPageActive', '=', 'PR')
                    ->orWhere('chrPageActive', '!=', 'PR');
                })
                ->whereNotIn('id', $ignoreId);
                if(!$isAdmin){
                    $response = $response->where('varSector', $userRoleSector);
                };
                $response = $response->get();
        return $response;
    }

    public static function getRecordListDraft($filterArr = false, $isAdmin = false, $ignoreId = array(), $userRoleSector) {
        $response = false;
        $moduleFields = [
            'id',
            'varTitle','varSector',
            'varExtLink',
            'varLinkType',
            'chrPublish',
            'fkIntPageId',
            'intSearchRank',
            'intAlertType',
            'FavoriteID',
            'fkModuleId',
            'chrAddStar',
            'fkIntDocId',
            'chrDraft',
            'chrTrash',
            'chrArchive',
            'intDisplayOrder',
            'LockUserID', 'chrLock',
            'dtDateTime',
            'dtEndDateTime'
        ];
        $response = Self::getPowerPanelRecords($moduleFields)
                ->deleted()
                ->filter($filterArr)
                ->checkMainRecord('Y')
                ->where('chrDraft', '=', 'D')
                ->where(function ($query) {
                    $query->where(function ($query) {
                        $query->where("chrArchive", '!=', 'Y')
                        ->whereRaw('(dtEndDateTime >= NOW() OR dtEndDateTime is null)');
                    });
                })
                ->whereNotIn('id', $ignoreId);
                if(!$isAdmin){
                    $response = $response->where('varSector', $userRoleSector);
                };
                $response = $response->where('chrTrash', '!=', 'Y');
        $response = $response->get();
        return $response;
    }

    public static function getRecordListTrash($filterArr = false, $isAdmin = false, $ignoreId = array(), $userRoleSector) {
        $response = false;
        $moduleFields = [
            'id',
            'varTitle','varSector',
            'varExtLink',
            'varLinkType',
            'chrPublish',
            'fkIntPageId',
            'intSearchRank',
            'intAlertType',
            'FavoriteID',
            'fkModuleId',
            'chrAddStar',
            'chrDraft',
            'fkIntDocId',
            'chrArchive',
            'intDisplayOrder',
            'LockUserID',
            'chrLock',
            'dtDateTime',
            'dtEndDateTime'
        ];
        $userid = auth()->user()->id;
        $response = Self::getPowerPanelRecords($moduleFields)
                ->deleted()
                ->filter($filterArr)
                ->checkMainRecord('Y')
                ->where('chrTrash', 'Y')
                ->where(function ($query) use ($userid) {
                    $query->where("UserID", '=', $userid)->where('chrPageActive', '=', 'PR')
                    ->orWhere('chrPageActive', '!=', 'PR');
                })
                ->whereNotIn('id', $ignoreId);
                if(!$isAdmin){
                    $response = $response->where('varSector', $userRoleSector);
                };
                $response = $response->get();
        return $response;
    }




    public static function getRecordCountforList($filterArr = false, $returnCounter = false, $isAdmin = false, $ignoreId = array(), $userRoleSector) {
        $response = 0;
        $moduleFields = ['id'];
        $pageQuery = Self::getPowerPanelRecords($moduleFields);
        if ($filterArr != false) {
            $pageQuery = $pageQuery->filter($filterArr, $returnCounter);
        }
        $response = $pageQuery->deleted()
                ->checkMainRecord('Y')
                ->where(function ($query) {
                    $query->where(function ($query) {
                        $query->where("chrArchive", '!=', 'Y')
                        ->whereRaw('(dtEndDateTime >= NOW() OR dtEndDateTime is null)');
                    });
                })
                ->where('chrTrash', '!=', 'Y')
                ->whereNotIn('id', $ignoreId);
                if(!$isAdmin){
                    $response = $response->where('varSector', $userRoleSector);
                }
        $response = $response->count();
        return $response;
    }

    public static function getRecordCountListApprovalTab($filterArr = false, $returnCounter = false, $isAdmin = false, $ignoreId = array(), $userRoleSector) {
        $response = false;
        $MainIDs = Self::distinct()
                ->select("fkMainRecord")
                ->where('fkMainRecord', '!=', '0')
                ->groupBy('fkMainRecord')
                ->get()
                ->toArray();
        $MainIDs = array_column($MainIDs, 'fkMainRecord');
        $moduleFields = ['id'];
        $response = Self::getPowerPanelRecords($moduleFields)
                ->deleted();
                if ($filterArr != false) {
                    $response = $response->filter($filterArr, $returnCounter);
                }
                $response = $response->whereIn('id', $MainIDs)
                ->checkStarRecord('Y')
                ->where(function ($query) {
                    $query->where(function ($query) {
                        $query->where("chrArchive", '!=', 'Y')
                        ->whereRaw('(dtEndDateTime >= NOW() OR dtEndDateTime is null)');
                    });
                })
                ->where('chrTrash', '!=', 'Y')
                ->whereNotIn('id', $ignoreId);
                if(!$isAdmin){
                    $response = $response->where('varSector', $userRoleSector);
                }
                $response = $response->count();
        return $response;
    }

    public static function getRecordCountforListFavorite($filterArr = false, $returnCounter = false, $isAdmin = false, $ignoreId = array(), $userRoleSector) {
        $response = 0;
        $cmsPageFields = ['id'];
        $userid = auth()->user()->id;
        $response = Self::getPowerPanelRecords($cmsPageFields);
        if ($filterArr != false) {
            $response = $response->filter($filterArr, $returnCounter);
        }
        $response = $response->deleted()
                ->checkMainRecord('Y')
                ->where('chrTrash', '!=', 'Y')
                ->whereRaw("find_in_set($userid,FavoriteID)")
                ->where(function ($query) {
                    $query->where(function ($query) {
                        $query->where("chrArchive", '!=', 'Y')
                        ->whereRaw('(dtEndDateTime >= NOW() OR dtEndDateTime is null)');
                    });
                })
                ->where(function ($query) use ($userid) {
                    $query->where("UserID", '=', $userid)->where('chrPageActive', '=', 'PR')
                    ->orWhere('chrPageActive', '!=', 'PR');
                })
                ->whereNotIn('id', $ignoreId);
                if(!$isAdmin){
                    $response = $response->where('varSector', $userRoleSector);
                }
                $response = $response->count();
        return $response;
    }

    public static function getRecordCountforListDarft($filterArr = false, $returnCounter = false, $isAdmin = false, $ignoreId = array(), $userRoleSector) {
        $response = 0;
        $cmsPageFields = ['id'];
        $response = Self::getPowerPanelRecords($cmsPageFields);
        if ($filterArr != false) {
            $response = $response->filter($filterArr, $returnCounter);
        }
        $response = $response->deleted()
                ->checkMainRecord('Y')
                ->where('chrDraft', '=', 'D')
                ->where(function ($query) {
                    $query->where(function ($query) {
                        $query->where("chrArchive", '!=', 'Y')
                        ->whereRaw('(dtEndDateTime >= NOW() OR dtEndDateTime is null)');
                    });
                })
                ->where('chrTrash', '!=', 'Y')
                ->whereNotIn('id', $ignoreId);
                if(!$isAdmin){
                    $response = $response->where('varSector', $userRoleSector);
                }
                $response = $response->count();
        return $response;
    }

    public static function getRecordCountforListTrash($filterArr = false, $returnCounter = false, $isAdmin = false, $ignoreId = array(), $userRoleSector) {
        $response = 0;
        $cmsPageFields = ['id'];
        $response = Self::getPowerPanelRecords($cmsPageFields);
        if ($filterArr != false) {
            $response = $response->filter($filterArr, $returnCounter);
        }
        $response = $response->deleted()
                ->checkMainRecord('Y')
                ->where('chrTrash', 'Y')
                ->whereNotIn('id', $ignoreId);
                if(!$isAdmin){
                    $response = $response->where('varSector', $userRoleSector);
                }
                $response = $response->count();
        return $response;
    }





    public static function getChildGrid($id = false) {
        $response = false;
        if (!empty($id)) {
            $moduleFields = [
                'id',
                'varTitle','varSector',
                'varExtLink',
                'varLinkType',
                'chrPublish',
                'fkIntPageId',
                'fkModuleId',
                'intDisplayOrder',
                'chrPublish',
                'fkMainRecord',
                'intSearchRank',
                'intAlertType',
                'varShortDescription',
                'FavoriteID',
                'chrAddStar',
                'fkIntDocId',
                'UserID',
                'chrApproved',
                'intApprovedBy',
                'chrPublish',
                'dtApprovedDateTime',
                'created_at',
                'dtDateTime',
                'dtEndDateTime',
                'updated_at',
            ];
            $response = Self::getPowerPanelRecords($moduleFields)
                    ->deleted()
                    ->checkMainRecord('N')
                    ->where('fkMainRecord', $id)
                    ->orderBy('created_at', 'desc')
                    ->get();
        }
        return $response;
    }

    public static function getChildrollbackGrid($request) {
        $id = $request->id;
        $response = false;
        $moduleFields = [
            'id',
            'varTitle','varSector',
            'varExtLink',
            'varLinkType',
            'chrPublish',
            'fkIntPageId',
            'fkModuleId',
            'intDisplayOrder',
            'chrPublish',
            'fkMainRecord',
            'intSearchRank',
            'intAlertType',
            'FavoriteID',
            'fkIntDocId',
            'chrAddStar',
            'UserID',
            'chrApproved',
            'intApprovedBy',
            'dtDateTime',
            'dtEndDateTime',
            'chrPublish',
            'created_at',
            'updated_at'
        ];
        $response = Self::getPowerPanelRecords($moduleFields)
                ->deleted()
                ->where('chrMain', 'N')
                ->where('chrRollBack', 'Y')
                ->where('fkMainRecord', $id)
                ->orderBy('created_at', 'desc')
                ->get();
        return $response;
    }

    /**
     * This method handels retrival of record
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getRecordById($id = false, $ignoreDeleteScope = false) {
        $response = false;
        $moduleFields = [
            'id',
            'varTitle','varSector',
            'fkModuleId',
            'varLinkType',
            'varExtLink',
            'txtDescription',
            'intDisplayOrder',
            'chrPublish',
            'fkIntPageId',
            'fkMainRecord',
            'chrMain',
            'varShortDescription',
            'chrAddStar',
            'chrApproved',
            'intApprovedBy',
            'UserID',
            'chrRollBack',
            'fkIntDocId',
            'dtDateTime',
            'dtEndDateTime',
            'created_at',
            'updated_at',
            'intSearchRank',
            'intAlertType',
            'FavoriteID',
            'LockUserID',
            'chrLock',
            'chrDraft',
            'chrArchive',
            'intSearchRank'
        ];
        $pageFields = ['id'];
        $response = Self::getPowerPanelRecords($moduleFields, $pageFields);
        if (!$ignoreDeleteScope) {
            $response = $response->deleted();
        }
        $response = $response->checkRecordId($id)
                ->first();
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
        $moduleFields = [
            'id',
            'fkMainRecord',
            'fkIntPageId',
            'fkModuleId',
            'varTitle','varSector',
            'varExtLink',
            'varLinkType',
            'intDisplayOrder',
            'txtDescription',
            'chrMain',
            'chrAddStar',
            'chrPublish',
            'varShortDescription',
            'chrDelete',
            'chrApproved',
            'intApprovedBy',
            'dtDateTime',
            'dtEndDateTime',
            'UserID',
            'chrRollBack',
            'fkIntDocId',
            'created_at',
            'updated_at',
            'intAlertType',
            'FavoriteID',
            'LockUserID',
            'chrLock',
            'intSearchRank'
        ];
        $response = Self::getPowerPanelRecords($moduleFields)
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
    public static function getRecordCount($filterArr = false, $returnCounter = false, $isAdmin = false, $userRoleSector) {
        $response = 0;
        $cmsPageFields = ['id'];
        $pageQuery = Self::getPowerPanelRecords($cmsPageFields);
        if ($filterArr != false) {
            $pageQuery = $pageQuery->filter($filterArr, $returnCounter);
        }
        $response = $pageQuery
                ->deleted()
                ->where('chrMain', 'Y');
                // ->where('chrIsPreview', 'N');
                if(!$isAdmin){
                    $response = $response->where('varSector', $userRoleSector);
                }
                $response = $response->count();
        return $response;
    }

    public static function getRecordCountForDorder($filterArr = false, $returnCounter = false, $isAdmin = false, $userRoleSector) {
        $response = 0;
        $cmsPageFields = ['id'];
        $pageQuery = Self::getPowerPanelRecords($cmsPageFields);
        if ($filterArr != false) {
            $pageQuery = $pageQuery->filter($filterArr, $returnCounter);
        }
        $response = $pageQuery
                ->deleted()
                ->where('chrMain', 'Y')
                ->where('chrTrash','N');
                // ->where('chrIsPreview', 'N');
                if(!$isAdmin){
                    $response = $response->where('varSector', $userRoleSector);
                }
                $response = $response->count();
        return $response;
    }

    public static function approved_data_Listing($request) {
        $id = $request->id;
        $main_id = $request->main_id;
        // $PUserid = $request->PUserid;
        //Select Child Record Data Start
        $response = false;
        $moduleFields = [
            'id',
            'fkIntPageId',
            'fkModuleId',
            'varTitle','varSector',
            'varExtLink',
            'varLinkType',
            'intDisplayOrder',
            'dtDateTime',
            'dtEndDateTime',
            'fkIntDocId',
            'chrPublish',
            'intAlertType',
            'intSearchRank',
            'chrDraft',
            'chrArchive',
            'chrTrash',
            'FavoriteID',
            'created_at',
            'updated_at'
        ];
        $response = Self::getPowerPanelRecords($moduleFields)
                ->deleted()
                ->where('chrMain', 'N')
                ->where('id', $id)
                ->orderBy('created_at', 'desc')
                ->first();
        //Select Child Record Data END
        //Update Copy Child Record To Main Record start
        $whereConditions = ['id' => $main_id];
        $updateMainRecord = [
            'varTitle' => $response['varTitle'],
            'varSector' => $response['varSector'],
            'varLinkType' => $response['varLinkType'],
            'fkIntPageId' => $response['fkIntPageId'],
            'fkModuleId' => $response['fkModuleId'],
            'varExtLink' => $response['varExtLink'],
            'fkIntDocId' => $response['fkIntDocId'],
            'dtDateTime' => $response['dtDateTime'],
            'dtEndDateTime' => $response['dtEndDateTime'],
            'chrPublish' => $response['chrPublish'],
            'intSearchRank' => $response['intSearchRank'],
            'intAlertType' => $response['intAlertType'],
            'FavoriteID' => $response['FavoriteID'],
            'chrDraft' => $response['chrDraft'],
            'chrArchive' => $response['chrArchive'],
            'FavoriteID' => $response['FavoriteID'],
        ];
        $updateMainRecord['chrAddStar'] = 'N';
        CommonModel::updateRecords($whereConditions, $updateMainRecord, false, 'Powerpanel\Alerts\Models\Alerts');
        //Update Copy Child Record To Main Record end
        $whereConditions_ApproveN = ['fkMainRecord' => $main_id];
        $updateToApproveN = [
            'chrApproved' => 'N',
            'chrLetest' => 'N',
            'intApprovedBy' => '0',
        ];
        CommonModel::updateRecords($whereConditions_ApproveN, $updateToApproveN, false, 'Powerpanel\Alerts\Models\Alerts');
        $whereConditionsApprove = ['id' => $id, 'chrMain' => 'N'];
        $updateToApprove = [
            'chrApproved' => 'Y',
            'chrRollBack' => 'Y',
            'intApprovedBy' => auth()->user()->id,
            'dtApprovedDateTime' => date('Y-m-d H:i:s')
        ];
        CommonModel::updateRecords($whereConditionsApprove, $updateToApprove, false, 'Powerpanel\Alerts\Models\Alerts');
        $msg_show = "Record successfully approved.";
        return $msg_show;
    }

    /**
     * This method handels retrival of record order of AprovalData
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getOrderOfApproval($id) {
        $result = Self::select('intDisplayOrder')
                ->checkRecordId($id)
                ->first();
        return $result;
    }

    /**
     * This method handels record count for new record approvel
     * @return  Object
     * @since   2018-09-26
     * @author  NetQuick
     */
    public static function getNewRecordsCount() {
        $response = false;
        $MainIDs = Self::distinct()
                ->select("fkMainRecord")
                ->where('fkMainRecord', '!=', '0')
                ->groupBy('fkMainRecord')
                ->get()
                ->toArray();
        $MainIDs = array_column($MainIDs, 'fkMainRecord');
        $moduleFields = ['id'];
        $response = Self::getPowerPanelRecords($moduleFields)
                ->deleted()
                ->whereIn('id', $MainIDs)
                ->checkStarRecord('Y')
                ->where('chrTrash', '!=', 'Y')
                ->count();
        return $response;
        return $NewRecordsCount;
    }

    /**
     * This method handels record Latest Record Count
     * @return  Object
     * @since   2018-09-26
     * @author  NetQuick
     */
    public static function getRecordCount_letest($Main_id, $id) {
        $moduleFields = ['chrLetest'];
        $response = Self::getPowerPanelRecords($moduleFields)
                ->deleted()
                ->checkMainRecord('N')
                ->checkLatest('Y')
                ->where('fkMainRecord', $Main_id)
                ->where('id', '!=', $id)
                ->where('chrApproved', 'N')
                ->count();
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
            'intDisplayOrder'
        ];
        if (!in_array($order, Self::$fetchedOrder)) {
            array_push(Self::$fetchedOrder, $order);
            Self::$fetchedOrderObj = Self::getPowerPanelRecords($moduleFields)
                    ->deleted()
                    ->orderCheck($order)
                    ->checkMainRecord('Y')
                    ->first();
        }
        $response = Self::$fetchedOrderObj;
        return $response;
    }

    public function ReorderAllrecords(){
    	
    	$tablename = app(self::Class)->getTable();
    	$records = self::orderBy('intDisplayOrder', 'asc')
    									->where("chrMain",'Y')
											->where("chrDelete","N")
											->where("chrTrash","N")
											->orderBy('intDisplayOrder', 'asc')
											->get();

			$ids = array();
      $update_syntax = "";

      if (!empty($records)) {
          $i = 0;
          foreach ($records as $rec) {
              $i++;
              $ids[$rec->id] = $rec->id;
              $update_syntax .= " WHEN " . $rec->id . " THEN $i ";
          }
          if (!empty($ids)) {
              $when = $update_syntax;
              DB::statement("UPDATE "."nq_".$tablename." SET intDisplayOrder = (CASE id " . $when . " ELSE intDisplayOrder END) WHERE id BETWEEN " . min($ids) . " AND " . max($ids) . " and chrDelete = 'N' and chrMain='Y'");
          }
      }
    }

    /**
     * This method handels retrival of record for notification
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getRecordNotify($id = false) {
        $response = false;
        $moduleFields = ['varTitle'];
        $response = Self::getPowerPanelRecords($moduleFields)
                ->deleted()
                ->checkRecordId($id)
                ->first();
        return $response;
    }

    #Database Configurations========================================
    /**
     * This method handels retrival of front end records
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */

    static function getFrontRecords($moduleFields = false, $mdlFields = false) {
        $data = [];
        $response = false;
        $response = self::select($moduleFields);
        if ($mdlFields != false) {
            $data['modules'] = function ($query) use ($mdlFields) {
                $query->select($mdlFields);
            };
        }
        if (count($data) > 0) {
            $response = $response->with($data);
        }
        return $response;
    }

    /**
     * This method handels retrival of backednd records
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    static function getPowerPanelRecords($moduleFields = false, $pageFields = false, $mdlFields = false) {
        $data = [];
        $response = false;

        $data['child'] = function ($query) {
            $query->select(['id','varTitle','fkMainRecord'])
                    ->where('chrDelete','N')
                    ->where('dtApprovedDateTime','!=',NULL);
        };

        $response = self::select($moduleFields);
        if ($pageFields != false) {
            $data['pages'] = function ($query) use ($pageFields) {
                $query->select($pageFields);
            };
        }
        if ($mdlFields != false) {
            $data['modules'] = function ($query) use ($mdlFields) {
                $query->select($mdlFields);
            };
        }
        if (count($data) > 0) {
            $response = $response->with($data);
        }
        return $response;
    }

    public function child() {
		return $this->hasMany('Powerpanel\Alerts\Models\Alerts', 'fkMainRecord', 'id');
	}

    /**
     * This method handels pages relation
     * @return  Object
     * @since   2017-07-20
     */
    public function pages() {
        $response = false;
        $response = $this->belongsTo('Powerpanel\CmsPage\Models\CmsPage', 'fkIntPageId', 'id');
        return $response;
    }

    /**
     * This method handels pages relation
     * @return  Object
     * @since   2017-07-20
     */
    public function modules() {
        $response = false;
        $response = $this->belongsTo('App\Modules', 'fkModuleId', 'id');
        return $response;
    }

    /**
     * This method handels retrival of banners records
     * @return  Object
     * @since   2016-07-20
     */
    static function getRecords() {
        $response = false;
        $response = self::with(['pages']);
        return $response;
    }

    /**
     * This method handels record id scope
     * @return  Object
     * @since   2016-07-24
     */
    function scopeCheckRecordId($query, $id) {
        $response = false;
        $response = $query->where('id', $id);
        return $response;
    }

    function scopeCheckByPageId($query, $id) {
        $response = false;
        $response = $query->where('fkIntPageId', $id);
        return $response;
    }

    /**
     * This method handels order scope
     * @return  Object
     * @since   2016-07-20
     */
    function scopeOrderCheck($query, $order) {
        $response = false;
        $response = $query->where('intDisplayOrder', $order);
        return $response;
    }

    /**
     * This method handels publish scope
     * @return  Object
     * @since   2016-07-20
     */
    function scopePublish($query) {
        $response = false;
        $response = $query->where(['chrPublish' => 'Y']);
        return $response;
    }

    /**
     * This method handels delete scope
     * @return  Object
     * @since   2016-07-20
     */
    function scopeDeleted($query) {
        $response = false;
        $response = $query->where(['chrDelete' => 'N']);
        return $response;
    }

    /**
     * This method handels banner type scope
     * @return  Object
     * @since   2017-08-08
     */
    function scopeLinkType($query, $type = null) {
        $response = false;
        $response = $query->where(['varLinkType' => $type]);
        return $response;
    }

    /**
     * This method checking banner type
     * @return  Object
     * @since   2016-07-20
     */
    function scopeCheckExternalLinkType($query) {
        $response = false;
        $response = $query->where(['varLinkType' => 'external']);
        return $response;
    }

    /**
     * This method checking banner type
     * @return  Object
     * @since   2016-07-14
     */
    function scopeCheckInternalLinkType($query) {
        $response = false;
        $response = $query->where(['varBannerType' => 'internal']);
        return $response;
    }

    /**
     * This method checking default banner
     * @return  Object
     * @since   2016-07-14
     */
    function scopeDisplayOrderBy($query, $orderBy) {
        $response = false;
        $response = $query->orderBy('intDisplayOrder', $orderBy);
        return $response;
    }

    function scopeCheckModuleId($query, $moduleId) {
        $response = false;
        $response = $query->where('fkModuleId', $moduleId);
        return $response;
    }

    /**
     * This method handels Main Record scope
     * @return  Object
     * @since   2016-07-24
     * @author  NetQuick
     */
    public function scopeCheckMainRecord($query, $checkMain = 'Y') {
        $response = false;
        $response = $query->where('chrMain', "=", $checkMain);
        return $response;
    }

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

    /**
     * This method handels Latest Record scope
     * @return  Object
     * @since   2016-07-24
     * @author  NetQuick
     */
    public function scopeCheckLatest($query, $flag = 'N') {
        $response = false;
        $response = $query->where('chrLetest', "=", $flag);
        return $response;
    }

    /**
     * This method handels filter scope
     * @return  Object
     * @since   2016-07-14
     */
    function scopeFilter($query, $filterArr = false, $retunTotalRecords = false) {
        $response = null;
        if (!empty($filterArr['orderByFieldName']) && !empty($filterArr['orderTypeAscOrDesc'])) {
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
        if (!empty($filterArr['searchFilter']) && $filterArr['searchFilter'] != ' ') {
            $data = $query->where('varTitle', 'like', "%" . $filterArr['searchFilter'] . "%");
        }
        if (!empty($filterArr['linkFilterType']) && $filterArr['linkFilterType'] != ' ') {
            $data = $query->where('varLinkType', $filterArr['linkFilterType']);
        }
        if (!empty($filterArr['catFilter']) && $filterArr['catFilter'] != ' ') {
            $data = $query->where('intAlertType', '=', $filterArr['catFilter']);
        }
        if (!empty($filterArr['sectorFilter']) && $filterArr['sectorFilter'] != ' ') {
            $data = $query->where('varSector', $filterArr['sectorFilter']);
        }
        if (!empty($filterArr['pageFilter']) && $filterArr['pageFilter'] != ' ') {
            $data = $query->where('fkIntPageId', '=', $filterArr['pageFilter']);
        }
        if (isset($filterArr['ignore']) && !empty($filterArr['ignore'])) {
            $data = $query->whereNotIn('alerts.id', $filterArr['ignore']);
        }
        if (!empty($filterArr['rangeFilter']['from']) && $filterArr['rangeFilter']['to']) {
            $data = $query->whereRaw('DATE(dtDateTime) BETWEEN "' . date('Y-m-d', strtotime(str_replace('/', '-', $filterArr['rangeFilter']['from']))) . '" AND "' . date('Y-m-d', strtotime(str_replace('/', '-', $filterArr['rangeFilter']['to']))) . '"');
        }
        if (!empty($query)) {
            $response = $query;
        }
        return $response;
    }

    /**
     * This method handels filter scope
     * @return  Object
     * @since   2016-07-14
     */
    static function add_pages() {
        $response = false;
        $module_code = DB::table('modules')->where('var_module_name', '=', 'cms-page')->first();
        $response = DB::table('cms_pages')
                        ->select('cms_pages.*')
                        ->where('cms_pages.chr_delete', '=', 'N')
                        ->where('cms_pages.chr_publish', '=', 'Y')
                        ->groupBy('cms_pages.id')->get();
        return $response;
    }

    /**
     * This method handels retrival of record for notification
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getHomePageList($limit = 8) {
        $response = false;
        $moduleFields = ['varTitle','varSector', 'varExtLink', 'varLinkType', 'fkIntPageId', 'fkModuleId', 'intDisplayOrder'];
        $mdlFields = ['id', 'varTitle', 'varSector','varModuleName'];
        $response = Self::getFrontRecords($moduleFields, $mdlFields)
                ->deleted()
                ->publish()
                ->dateRange()
                ->checkMainRecord()
                ->take($limit)
                ->get();
        return $response;
    }

    /**
     * This method handels retrival of record for notification
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getAlertsForHeader() {
        $response = false;
        $moduleFields = ['id', 'varTitle','varSector', 'varExtLink', 'fkIntDocId','varLinkType', 'fkIntPageId', 'fkModuleId', 'intDisplayOrder', 'intAlertType', 'intSearchRank', 'FavoriteID'];
        $mdlFields = ['id', 'varTitle', 'varModuleName', 'varTableName', 'varModelName', 'varModuleClass'];
        $response = Self::getFrontRecords($moduleFields, $mdlFields)
                ->deleted()
                ->publish()
                ->dateRange()
                ->where('chrMain', 'Y')
                ->orderBy('intAlertType')
                ->orderBy('intDisplayOrder')
                ->get();
        return $response;
    }

    public static function getAlertsForListing() {
        $response = false;
        $moduleFields = ['id', 'varTitle','varSector', 'varExtLink', 'fkIntDocId','varLinkType','varShortDescription', 'fkIntPageId', 'fkModuleId', 'intDisplayOrder', 'intAlertType', 'intSearchRank', 'FavoriteID'];
        $mdlFields = ['id', 'varTitle', 'varModuleName', 'varTableName', 'varModelName', 'varModuleClass'];
        $response = Self::getFrontRecords($moduleFields, $mdlFields)
                ->deleted()
                ->publish()
                ->dateRange()
                ->where('chrMain', 'Y')
                ->where('chrTrash', '!=', 'Y')
                ->where('chrDraft', '!=', 'D')
                ->orderBy('intAlertType')
                ->orderBy('intDisplayOrder')
                ->get();
        return $response;
    }

    public function scopeDateRange($query) {
        $response = false;
        $response = $query->whereRaw('((dtEndDateTime >= NOW() AND NOW() >= dtDateTime) OR (NOW() >= dtDateTime and dtEndDateTime is null))');
        return $response;
    }

    public static function getBuilderRecordList($filterArr = []) {
        $response = false;
        $moduleFields = [
            'alerts.id',
            'alerts.varTitle',
            'alerts.varSector',
            'alerts.intAlertType',
            'alerts.chrPublish',
            'alerts.chrDelete',
            'alerts.chrMain',
            'alerts.fkIntDocId',
            'alerts.chrDraft',
            'alerts.chrTrash',
            'alerts.intDisplayOrder',
            'alerts.dtDateTime',
            'alerts.dtEndDateTime',
            'alerts.updated_at'
        ];
        $response = Self::getPowerPanelRecords($moduleFields, false, false, false, false)
                ->filter($filterArr);
        $response = $response->where('alerts.chrPublish', 'Y')
                ->where('alerts.chrDelete', 'N')
                ->where('alerts.chrMain', 'Y')
                ->where('alerts.chrDraft', '!=', 'D')
                ->where('alerts.chrTrash', '!=', 'Y')
                ->groupBy('alerts.id')
                ->get();
        return $response;
    }

    public static function getBuilderAlerts($recIds) {
        $response = false;
        $moduleFields = ['id', 'varTitle', 'varSector','varExtLink','fkIntDocId', 'varLinkType', 'varShortDescription','fkIntPageId', 'fkModuleId', 'intDisplayOrder', 'intAlertType', 'intSearchRank', 'FavoriteID'];
        $mdlFields = ['id', 'varTitle', 'varModuleName', 'varTableName', 'varModelName', 'varModuleClass'];
        if (empty($response)) {
            $response = Self::getFrontRecords($moduleFields, $mdlFields)
                    ->whereIn('id', $recIds)
                    ->where('chrMain', 'Y')
                    ->where('chrDraft', '!=', 'D')
                    ->where('chrTrash', '!=', 'Y')
                    ->deleted()
                    ->publish()
                    ->orderByRaw(DB::raw("FIELD(id, " . implode(',', $recIds) . " )"))
                    ->groupBy('id')
                    ->groupBy('intAlertType')
                    ->get();
        }
        return $response;
    }

    public static function getInternalLinkHtml($value) {
        $linkUrl = url('/');
        $moduleCode = $value->modules->id;
        $moduleListforFindpageArray = [
            'publications-category' => 'publications',
            'publications' => 'publications',
            'events-category' => 'events',
            'events' => 'events',
            'news-category' => 'news-category',
            'news' => 'news-category',
            'faq-category' => 'faq-category',
            'blogs' => 'blogs',
            'blog-category' => 'blog-category'
        ];

        $categoryFieldsets = [
            'publications' => 'publications-category',
            'events' => 'event-category',
            'news' => 'news-category',
            'faq' => 'faq-category',
            'blogs' => 'blogs',
            'blog-category' => 'blog-category'
        ];
        if ($moduleCode != 4) {
            $catAlias = false;
            $catfieldName = '';
            if (array_key_exists($value->modules->varModuleName, $moduleListforFindpageArray)) {
                $moduleData = DB::table('module')->select('id')->where('varModuleName', $moduleListforFindpageArray[$value->modules->varModuleName])->first();
                $moduleCode = $moduleData->id;
            }
            $pageAlias = CmsPage::select('alias.varAlias')
                    ->leftJoin('alias', 'alias.id', '=', 'cms_page.intAliasId')
                    ->where('cms_page.intFKModuleCode', $moduleCode)
                    ->where('cms_page.chrPublish', 'Y')
                    ->where('cms_page.chrDelete', 'N')
                    ->first();
            if (isset($pageAlias->varAlias)) {
                $value->pageAlias = $pageAlias->varAlias;
            }

            if (\Schema::hasColumn($value->modules->varTableName, 'intAliasId')) {

                $modulefields = ['varTitle', 'intAliasId', 'alias.varAlias as recordalias'];
                if (\Schema::hasColumn($value->modules->varTableName, 'txtCategories')) {
                    $catAlias = true;
                    $catfieldName = 'txtCategories';
                    array_push($modulefields, $value->modules->varTableName . '.txtCategories');
                }
                if (\Schema::hasColumn($value->modules->varTableName, 'intFKCategory')) {
                    $catAlias = true;
                    $catfieldName = 'intFKCategory';
                    array_push($modulefields, $value->modules->varTableName . '.intFKCategory');
                }
                $recordData = DB::table($value->modules->varTableName)
                        ->select($modulefields)
                        ->join('alias', 'alias.id', '=', $value->modules->varTableName . '.intAliasId')
                        ->where($value->modules->varTableName . '.id', $value->fkIntPageId);
                if (\Schema::hasColumn($value->modules->varTableName, 'chrMain')) {
                    $recordData = $recordData->where($value->modules->varTableName . '.chrMain', 'Y');
                }
                if (\Schema::hasColumn($value->modules->varTableName, 'chrIsPreview')) {
                    $recordData = $recordData->where($value->modules->varTableName . '.chrIsPreview', 'N');
                }
                $recordData = $recordData->first();

                if ($catAlias) {
                    $categoryRecordAlias = '';
                    if ($catfieldName == 'txtCategories') {
                        if (isset($categoryFieldsets[$value->modules->varModuleName])) {
                            if (isset($recordData->txtCategories)) {
                                $categoryRecordAlias = Mylibrary::getRecordAliasByModuleNameRecordId($categoryFieldsets[$value->modules->varModuleName], $recordData->txtCategories);
                            }
                        }
                    } else {
                        if (isset($categoryFieldsets[$value->modules->varModuleName])) {
                            if (isset($recordData->intFKCategory)) {
                                $categoryRecordAlias = Mylibrary::getRecordAliasByModuleNameRecordId($categoryFieldsets[$value->modules->varModuleName], $recordData->intFKCategory);
                            }
                        }
                    }
                    if ($categoryRecordAlias != "") {
                        if (isset($pageAlias->varAlias)) {
                            if (isset($recordData->recordalias)) {
                                $linkUrl = url('/') . '/' . $pageAlias->varAlias . '/' . $categoryRecordAlias . '/' . $recordData->recordalias;
                            }
                        }
                    } else {
                        if (isset($pageAlias->varAlias)) {
                            if (isset($recordData->recordalias)) {
                                $linkUrl = url('/') . '/' . $pageAlias->varAlias . '/' . $recordData->recordalias;
                            }
                        }
                    }
                } else {
                    if (isset($recordData->recordalias)) {
                        if (isset($pageAlias->varAlias)) {
                            $linkUrl = url('/') . '/' . $pageAlias->varAlias . '/' . $recordData->recordalias;
                        }
                    } else {
                        if (isset($pageAlias->varAlias)) {
                            $linkUrl = url('/') . '/' . $pageAlias->varAlias;
                        }
                    }
                }
            }
        } else {
            $pageAlias = CmsPage::select('alias.varAlias')
                    ->leftJoin('alias', 'alias.id', '=', 'cms_page.intAliasId')
                    ->where('cms_page.id', $value->fkIntPageId)
                    ->where('cms_page.chrPublish', 'Y')
                    ->where('cms_page.chrDelete', 'N')
                    ->first();
            if (isset($pageAlias->varAlias)) {
                $value->pageAlias = $pageAlias->varAlias;
                $linkUrl = url('/' . $pageAlias->varAlias);
            }
        }

        return $linkUrl;
    }

    public static function getAllAlerts($limit, $alerttype, $sdate, $edate) {

        $response = false;
        $moduleFields = ['id', 'varTitle', 'varSector','varExtLink', 'varLinkType', 'varShortDescription','fkIntPageId', 'fkModuleId', 'intDisplayOrder', 'intAlertType', 'intSearchRank', 'FavoriteID', 'dtEndDateTime', 'dtDateTime'];
        $mdlFields = ['id', 'varTitle', 'varModuleName', 'varTableName', 'varModelName', 'varModuleClass'];
        if (empty($response)) {
            $response = Self::getFrontRecords($moduleFields, $mdlFields)
                    ->where('chrMain', 'Y');
            if ($alerttype != '') {
                $response = $response->where('intAlertType', $alerttype);
            }

            if ($sdate != '' && $edate != '') {
                $response = $response->whereRaw('(DATE(dtDateTime)>="' . date('Y-m-d', strtotime(str_replace('/', '-', $sdate))) . '" AND (DATE(dtDateTime)<="' . date('Y-m-d', strtotime(str_replace('/', '-', $edate))) . '") OR ("' . date('Y-m-d', strtotime(str_replace('/', '-', $sdate))) . '" >= dtDateTime and dtEndDateTime is null))');
            } else if ($sdate != '') {
                $response = $response->whereRaw('DATE(dtDateTime)>="' . date('Y-m-d', strtotime(str_replace('/', '-', $sdate))) . '"');
            } else if ($edate != '') {
                $response = $response->whereRaw('DATE(dtDateTime)<="' . date('Y-m-d', strtotime(str_replace('/', '-', $edate))) . '"');
            }

            $response = $response->deleted()
                    ->where('chrDraft', '!=', 'D')
                    ->where('chrTrash', '!=', 'Y')
                    ->publish()
                    ->orderBy('dtDateTime', 'desc');
            if ($limit != '') {
                $response = $response->limit($limit);
            }
            $response = $response->get();
        }
        return $response;
    }

    public static function getPreviousRecordByMainId($id) {
        $response = Self::select('id','fkMainRecord')
                        ->deleted()  
                        ->publish()
                        ->where('fkMainRecord', $id)
                        ->where('chrMain', 'N')
                        ->where('chrApproved', 'N')
                        ->orderBy('dtApprovedDateTime','DESC')
                        ->first();
        return $response;

    }

}
