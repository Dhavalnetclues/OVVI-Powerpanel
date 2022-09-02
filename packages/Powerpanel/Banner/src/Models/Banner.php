<?php

/**
 * The Banner class handels bannner queries
 * ORM implemetation.
 * @package   Netquick powerpanel
 * @license   http://www.opensource.org/licenses/BSD-3-Clause
 * @version   1.1
 * @since       2017-07-20
 */

namespace Powerpanel\Banner\Models;

use App\CommonModel;
use Cache;
use DB;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'banner';
    protected $fillable = [
        'varTitle',
        'varLink',
        'varShortDescription',
        'fkIntImgId',
        'fkIntInnerImgId',
        'fkIntIconId',
        'chrDisplayVideo',
        'chrDisplayLink',
        'varVideoLink',
        'fkIntVideoId',
        'varBannerType',
        'fkModuleId',
        'fkIntPageId',
        'txtDescription',
        'intDisplayOrder',
        'chrPublish',
        'chrDelete',
        'chrDefaultBanner',
        'chrDraft',
        'chrArchive',
        'chrTrash',
        'FavoriteID',
        'LockUserID',
        'chrLock',
        'created_at',
        'updated_at',
    ];

    /**
     * This method handels retrival of front banner list
     * @return  Object
     * @since   2017-10-14
     * @author  NetQuick
     */
    public static function getHomeBannerList()
    {
        $response = false;
        $response = Cache::tags(['Banner'])->get('getHomeBannerList');

        if (empty($response)) {

            $moduleFields = ['id','varTitle', 'fkIntImgId', 'fkIntIconId','varShortDescription','varLink','chrDisplayLink','chrDisplayVideo','varVideoLink'];
            $imageField = ['id','txtImageName','txtImgOriginalName','varImageExtension','fk_folder'];
            $folderFields = ['id','foldername'];
            $iconField = ['id','txtImageName','txtImgOriginalName','varImageExtension','fk_folder'];
            $response = Self::getFrontRecords($moduleFields, $imageField, $folderFields, $iconField)
                            ->bannerType('home_banner')
                            ->displayOrderBy('ASC')
                            ->dateRange()
                            ->deleted()
                            ->where('chrMain', 'Y')
                            ->where('chrTrash', '!=', 'Y')
                            ->publish()
                            ->get();

            Cache::tags(['Banner'])->forever('getHomeBannerList', $response);
        }
        return $response;
    }

    /**
     * This method handels retrival of front banner list
     * @return  Object
     * @since   2017-10-14
     * @author  NetQuick
     */
    public static function getDefaultBannerList()
    {
        $response = false;
        $response = Cache::tags(['Banner'])->get('getDefaultBannerList');
        if (empty($response)) {
            $moduleFields = ['fkIntImgId', 'fkIntInnerImgId', 'varLink', 'varShortDescription', 'chrDisplayLink', 'chrDisplayVideo', 'varVideoLink', 'varTitle', 'txtDescription', 'chrDraft', 'chrArchive', 'chrTrash', 'chrPublish', 'FavoriteID', 'created_at', 'updated_at'];
            $response = Self::getFrontRecords($moduleFields)
                ->checkDefaultBanner()
                ->displayOrderBy('ASC')
                ->deleted()
                ->dateRange()
                ->where('chrMain', 'Y')
                ->where('chrTrash', '!=', 'Y')
                ->publish()
                ->get();
            Cache::tags(['Banner'])->forever('getDefaultBannerList', $response);
        }
        return $response;
    }

    /**
     * This method handels retrival of front banner list
     * @return  Object
     * @since   2017-10-14
     * @author  NetQuick
     */
    public static function getInnerBannerList($pageId = false, $moduleId = false)
    {
        $response = false;
        $moduleFields = ['varTitle', 'fkIntImgId',  'fkIntInnerImgId','varLink', 'varShortDescription', 'chrDisplayLink', 'varVideoLink', 'chrDisplayVideo', 'txtDescription', 'chrDraft',  'chrPublish','chrArchive', 'chrTrash', 'FavoriteID', 'created_at', 'updated_at'];
        $response = Self::getFrontRecords($moduleFields)
            ->deleted()
            ->dateRange()
            ->publish()
            ->bannerType('inner_banner');
        if ($pageId) {
            $response = $response->checkByPageId($pageId);
        }
        if ($moduleId) {
            $response = $response->checkModuleId($moduleId);
        }
        $response = $response->get();
        return $response;
    }

    public static function getInnerBannerListingPage($pageId = false, $moduleId = false)
    {
        $response = false;
        $moduleFields = ['varTitle', 'fkIntImgId', 'fkIntInnerImgId', 'varLink','fkIntPageId', 'varShortDescription', 'fkModuleId','varVideoLink', 'chrPublish','chrDisplayLink', 'chrDisplayVideo', 'txtDescription', 'chrDraft', 'chrArchive', 'chrTrash', 'FavoriteID', 'created_at', 'updated_at'];
        $response = Self::getFrontRecords($moduleFields)
            ->deleted()
            ->dateRange()
            ->publish()
            ->bannerType('inner_banner');
        
        if ($pageId) {
            $response = $response->checkByPageId($pageId);
        }
        if ($moduleId) {
            $response = $response->checkModuleId($moduleId);
        }
//        echo '<Pre>';print_r($response);exit;
        if (!empty($response)) {
            $response = $response->get();
        }
            
        return $response;
    }

    /**
     * This method handels retrival of home banner record count
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function homeBannerCount()
    {
        $response = false;
        $moduleFields = ['id'];
        $response = Self::getPowerPanelRecords($moduleFields)
            ->checkHomeBannerType()
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
    public static function innerBannerCount()
    {
        $response = false;
        $moduleFields = ['id'];
        $response = Self::getPowerPanelRecords($moduleFields)
            ->CheckInnerBannerType()
            ->deleted()
            ->count();
        return $response;
    }







    public static function getRecordList($filterArr = false, $isAdmin = false, $ignoreId = array(), $userRoleSector = false)
    {
        $response = false;
        $imageFields = false;
        $videoFields = false;
        $moduleFields = [
            'id',
            'varBannerType',
            'chrDefaultBanner',
            'varLink',
            'varShortDescription',
            'chrAddStar',
            'varRotateTime',
            'chrDisplayVideo',
            'chrDisplayLink',
            'varVideoLink',
            'fkMainRecord',
            'chrPublish',
            'chrDefaultBanner',
            'fkIntPageId',
            'fkIntImgId',
             'fkIntInnerImgId',
            'fkModuleId',
            'varTitle',
            'varBannerType',
            'intDisplayOrder',
            'dtDateTime',
            'dtEndDateTime',
            'chrDraft',
            'chrArchive',
            'chrTrash',
            'chrAddStar',
            'FavoriteID',
            'LockUserID', 'chrLock',
            'created_at',
            'updated_at',
        ];
        $pageFields = ['id', 'varTitle'];
        $mdlFields = ['id', 'varTitle'];
        $response = $response = Self::getPowerPanelRecords($moduleFields, $pageFields, $imageFields, $videoFields, $mdlFields)
            ->deleted()
            ->where('chrIsPreview', 'N')
            ->where('chrTrash', '!=', 'Y')
            ->where(function ($query) {
                $query->where(function ($query) {
                    $query->where("chrArchive", '!=', 'Y')
                        ->whereRaw('(dtEndDateTime >= NOW() OR dtEndDateTime is null)');
                });
            })
            ->checkMainRecord('Y');
        $response = $response->filter($filterArr)
        ->whereNotIn('id', $ignoreId);
        // if(!$isAdmin){
        //     $response = $response->where('varSector', $userRoleSector);
        // }
        $response = $response->get();
        return $response;
    }

    public static function getRecordListDraft($filterArr = false, $isAdmin = false, $ignoreId = array(), $userRoleSector)
    {
        $response = false;
        $imageFields = false;
        $videoFields = false;
        $moduleFields = [
            'id',
            'varBannerType',
            'chrDefaultBanner',
            'varLink',
            'varShortDescription',
            'chrAddStar',
            'varRotateTime',
            'chrDisplayVideo',
            'chrDisplayLink',
            'varVideoLink',
            'fkMainRecord',
            'chrPublish',
            'chrDefaultBanner',
            'fkIntPageId',
            'fkIntImgId',
             'fkIntInnerImgId',
            'fkModuleId',
            'varTitle',
            'varBannerType',
            'intDisplayOrder',
            'dtDateTime',
            'dtEndDateTime',
            'chrAddStar',
            'chrDraft',
            'chrArchive',
            'chrTrash',
            'FavoriteID',
            'LockUserID', 'chrLock',
            'created_at',
            'updated_at',
        ];
        $pageFields = ['id', 'varTitle'];
        $mdlFields = ['id', 'varTitle'];
        $response = $response = Self::getPowerPanelRecords($moduleFields, $pageFields, $imageFields, $videoFields, $mdlFields)
            ->deleted()
            ->filter($filterArr)
            ->checkMainRecord('Y')
            ->where('chrIsPreview', 'N')
            ->where('chrDraft', 'D')
            ->where(function ($query) {
                $query->where(function ($query) {
                    $query->where("chrArchive", '!=', 'Y')
                        ->whereRaw('(dtEndDateTime >= NOW() OR dtEndDateTime is null)');
                });
            })
            ->where('chrTrash', '!=', 'Y')
            ->whereNotIn('id', $ignoreId);
            // if(!$isAdmin){
            //     $response = $response->where('varSector', $userRoleSector);
            // }
            $response = $response->get();
        return $response;
    }

    public static function getRecordListTrash($filterArr = false, $isAdmin = false, $ignoreId = array(), $userRoleSector)
    {
        $response = false;
        $imageFields = false;
        $videoFields = false;
        $moduleFields = [
            'id',
            'varBannerType',
            'chrDefaultBanner',
            'varLink',
            'varShortDescription',
            'chrAddStar',
            'varRotateTime',
            'chrDisplayVideo',
            'chrDisplayLink',
            'varVideoLink',
            'fkMainRecord',
            'chrPublish',
            'chrDefaultBanner',
            'fkIntPageId',
            'fkIntImgId',
             'fkIntInnerImgId',
            'fkModuleId',
            'varTitle',
            'varBannerType',
            'intDisplayOrder',
            'dtDateTime',
            'dtEndDateTime',
            'chrDraft',
            'chrArchive',
            'chrTrash',
            'chrAddStar',
            'FavoriteID',
            'LockUserID', 'chrLock',
            'created_at',
            'updated_at',
        ];
        $pageFields = ['id', 'varTitle'];
        $mdlFields = ['id', 'varTitle'];
        $response = $response = Self::getPowerPanelRecords($moduleFields, $pageFields, $imageFields, $videoFields, $mdlFields)
            ->deleted()
            ->filter($filterArr)
            ->checkMainRecord('Y')
            ->where('chrIsPreview', 'N')
            ->where('chrTrash', 'Y')
            ->whereNotIn('id', $ignoreId);
            // if(!$isAdmin){
            //     $response = $response->where('varSector', $userRoleSector);
            // }
            $response = $response->get();
        return $response;
    }

    public static function getRecordListFavorite($filterArr = false, $isAdmin = false, $ignoreId = array(), $userRoleSector)
    {
        $response = false;
        $imageFields = false;
        $videoFields = false;
        $moduleFields = [
            'id',
            'varBannerType',
            'chrDefaultBanner',
            'varLink',
            'varShortDescription',
            'chrAddStar',
            'varRotateTime',
            'chrDisplayVideo',
            'chrDisplayLink',
            'varVideoLink',
            'fkMainRecord',
            'chrPublish',
            'chrDefaultBanner',
            'fkIntPageId',
            'fkIntImgId',
             'fkIntInnerImgId',
            'fkModuleId',
            'varTitle',
            'varBannerType',
            'intDisplayOrder',
            'dtDateTime',
            'dtEndDateTime',
            'chrDraft',
            'chrArchive',
            'chrTrash',
            'chrAddStar',
            'FavoriteID',
            'LockUserID', 'chrLock',
            'created_at',
            'updated_at',
        ];
        $pageFields = ['id', 'varTitle'];
        $mdlFields = ['id', 'varTitle'];
        $userid = auth()->user()->id;
        $response = $response = Self::getPowerPanelRecords($moduleFields, $pageFields, $imageFields, $videoFields, $mdlFields)
            ->deleted()
            ->filter($filterArr)
            ->checkMainRecord('Y')
            ->where('chrIsPreview', 'N')
            ->where('chrTrash', '!=', 'Y')
            ->where(function ($query) {
                $query->where(function ($query) {
                    $query->where("chrArchive", '!=', 'Y')
                        ->whereRaw('(dtEndDateTime >= NOW() OR dtEndDateTime is null)');
                });
            })
            ->whereRaw("find_in_set($userid,FavoriteID)")
            ->whereNotIn('id', $ignoreId);
            // if(!$isAdmin){
            //     $response = $response->where('varSector', $userRoleSector);
            // }
        $response = $response->get();
        return $response;
    }

    public static function getRecordList_tab1($filterArr = false, $isAdmin = false, $ignoreId = array(), $userRoleSector)
    {
        $response = false;
        $videoFields = false;
        $imageFields = false;
        $moduleFields = [
            'id',
            'varBannerType',
            'fkMainRecord',
            'varRotateTime',
            'chrAddStar',
            'chrDefaultBanner',
            'varLink',
            'varShortDescription',
            'chrPublish',
            'chrDefaultBanner',
            'chrDisplayVideo',
            'chrDisplayLink',
            'varVideoLink',
            'fkIntPageId',
            'fkIntImgId',
             'fkIntInnerImgId',
            'fkModuleId',
            'varTitle',
            'varBannerType',
            'intDisplayOrder',
            'dtDateTime',
            'dtEndDateTime',
            'chrDraft',
            'chrArchive',
            'chrTrash',
            'chrAddStar',
            'FavoriteID',
            'LockUserID', 'chrLock',
            'created_at',
            'updated_at',
        ];
        $pageFields = ['id', 'varTitle'];
        $mdlFields = ['id', 'varTitle'];
        $MainID = Self::distinct()
            ->select("fkMainRecord")
            ->where('fkMainRecord', '!=', '0')
            ->deleted()
            ->groupBy('fkMainRecord')
            ->get();
        $MainRecordId = array();
        foreach ($MainID as $ids) {
            $MainRecordId[] = $ids->fkMainRecord;
        }
        $response = Self::getPowerPanelRecords($moduleFields, $pageFields, $imageFields, $videoFields, $mdlFields)
            ->deleted()
            ->where('chrAddStar', 'Y')
            ->filter($filterArr)
            ->whereIn('id', $MainRecordId)
            ->where('chrTrash', '!=', 'Y')
            ->where(function ($query) {
                $query->where(function ($query) {
                    $query->where("chrArchive", '!=', 'Y')
                        ->whereRaw('(dtEndDateTime >= NOW() OR dtEndDateTime is null)');
                });
            })
            ->whereNotIn('id', $ignoreId);
            // if(!$isAdmin){
            //     $response = $response->where('varSector', $userRoleSector);
            // }
            $response = $response->get();
        return $response;
    }




    public static function getRecordCountforList($filterArr = false, $returnCounter = false, $isAdmin = false, $ignoreId = array(), $userRoleSector)
    {
        $response = 0;
        $cmsPageFields = ['id'];
        $response = Self::getPowerPanelRecords($cmsPageFields);
        if ($filterArr != false) {
            $response = $response->filter($filterArr, $returnCounter);
        }
        $response = $response->deleted()
            ->checkMainRecord('Y')
            ->where('chrIsPreview', 'N')
            ->where('chrTrash', '!=', 'Y')
            ->where(function ($query) {
                $query->where(function ($query) {
                    $query->where("chrArchive", '!=', 'Y')
                        ->whereRaw('(dtEndDateTime >= NOW() OR dtEndDateTime is null)');
                });
            })
            ->whereNotIn('id', $ignoreId);
            // if(!$isAdmin){
            //     $response = $response->where('varSector', $userRoleSector);
            // }
            $response = $response->count();
        return $response;
    }

    public static function getRecordCountListApprovalTab($filterArr = false, $returnCounter = false, $isAdmin = false, $ignoreId = array(), $userRoleSector)
    {
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
            // if(!$isAdmin){
            //     $response = $response->where('varSector', $userRoleSector);
            // }
            $response = $response->count();
        return $response;
    }

    public static function getRecordCountforListDarft($filterArr = false, $returnCounter = false, $isAdmin = false, $ignoreId = array(), $userRoleSector)
    {
        $response = 0;
        $cmsPageFields = ['id'];
        $userid = auth()->user()->id;
        $response = Self::getPowerPanelRecords($cmsPageFields);
        if ($filterArr != false) {
            $response = $response->filter($filterArr, $returnCounter);
        }
        $response = $response->deleted()
            ->checkMainRecord('Y')
            ->where('chrIsPreview', 'N')
            ->where('chrDraft', 'D')
            ->where('chrTrash', '!=', 'Y')
            ->where(function ($query) {
                $query->where(function ($query) {
                    $query->where("chrArchive", '!=', 'Y')
                        ->whereRaw('(dtEndDateTime >= NOW() OR dtEndDateTime is null)');
                });
            })
            ->whereNotIn('id', $ignoreId);
            // if(!$isAdmin){
            //     $response = $response->where('varSector', $userRoleSector);
            // }
            $response = $response->count();
        return $response;
    }

    public static function getRecordCountforListTrash($filterArr = false, $returnCounter = false, $isAdmin = false, $ignoreId = array(), $userRoleSector)
    {
        $response = 0;
        $cmsPageFields = ['id'];
        $userid = auth()->user()->id;
        $response = Self::getPowerPanelRecords($cmsPageFields);
        if ($filterArr != false) {
            $response = $response->filter($filterArr, $returnCounter);
        }
        $response = $response->deleted()
            ->checkMainRecord('Y')
            ->where('chrIsPreview', 'N')
            ->where('chrTrash', 'Y')
            ->whereNotIn('id', $ignoreId);
            // if(!$isAdmin){
            //     $response = $response->where('varSector', $userRoleSector);
            // }
            $response = $response->count();
        return $response;
    }

    public static function getRecordCountforListFavorite($filterArr = false, $returnCounter = false, $isAdmin = false, $ignoreId = array(), $userRoleSector)
    {
        $response = 0;
        $cmsPageFields = ['id'];
        $userid = auth()->user()->id;
        $response = Self::getPowerPanelRecords($cmsPageFields);
        if ($filterArr != false) {
            $response = $response->filter($filterArr, $returnCounter);
        }
        $response = $response->deleted()
            ->checkMainRecord('Y')
            ->where('chrIsPreview', 'N')
            ->where('chrTrash', '!=', 'Y')
            ->whereRaw("find_in_set($userid,FavoriteID)")
            ->where(function ($query) {
                $query->where(function ($query) {
                    $query->where("chrArchive", '!=', 'Y')
                        ->whereRaw('(dtEndDateTime >= NOW() OR dtEndDateTime is null)');
                });
            })
            ->whereNotIn('id', $ignoreId);
            // if(!$isAdmin){
            //     $response = $response->where('varSector', $userRoleSector);
            // }
            $response = $response->count();
        return $response;
    }




    public static function getChildGrid()
    {
        $id = $_REQUEST['id'];
        $response = false;
        $moduleFields = [
            'id',
            'varBannerType',
            'fkMainRecord',
            'chrAddStar',
            'chrDefaultBanner',
            'varLink',
            'chrPublish',
            'chrDefaultBanner',
            'varShortDescription',
            'chrDisplayVideo',
            'chrDisplayLink',
            'varVideoLink',
            'fkIntPageId',
            'fkIntImgId',
             'fkIntInnerImgId',
            'fkModuleId',
            'varTitle',
            'varBannerType',
            'intDisplayOrder',
            'created_at',
            'UserID',
            'chrApproved',
            'updated_at',
            'intApprovedBy',
            'chrDraft',
            'chrArchive',
            'chrTrash',
            'FavoriteID',
            'dtApprovedDateTime',
            'created_at',
            'updated_at',
        ];
        $response = Self::getPowerPanelRecords($moduleFields)->deleted()->where('chrMain', 'N')->where('fkMainRecord', $id)->orderBy('created_at', 'desc')->get();
//        print_r($response);exit;
        return $response;
    }

    public static function approved_data_Listing($request)
    {
        $id = $request->id;
        $main_id = $request->main_id;
        // $PUserid = $request->PUserid;
        //Select Child Record Data Start
        $response = false;
        $moduleFields = [
            'id',
            'varBannerType',
            'fkIntVideoId',
            'varLink',
            'txtDescription',
            'varBannerVersion',
            'fkMainRecord',
            'varShortDescription',
            'chrAddStar',
            'chrDefaultBanner',
            'chrDisplayVideo',
            'chrDisplayLink',
            'varVideoLink',
            'chrPublish',
            'chrDefaultBanner',
            'fkIntPageId',
            'fkIntImgId',
             'fkIntInnerImgId',
            'fkModuleId',
            'varTitle',
            'varBannerType',
            'intDisplayOrder',
            'created_at',
            'UserID',
            'chrApproved',
            'updated_at',
            'intApprovedBy',
            'dtDateTime',
            'dtEndDateTime',
            'chrDraft',
            'chrArchive',
            'chrTrash',
            'FavoriteID',
            'created_at',
            'updated_at',
        ];
        $response = Self::getPowerPanelRecords($moduleFields)->deleted()->where('chrMain', 'N')->where('id', $id)->orderBy('created_at', 'desc')->first();
        //Select Child Record Data END
        //Update Copy Child Record To Main Record start
        $whereConditions = ['id' => $main_id];
        $updateMainRecord = [
            'varTitle' => $response['varTitle'],
            'fkIntImgId' => $response['fkIntImgId'],
            'fkIntInnerImgId' => $response['fkIntInnerImgId'],
            'varLink' => $response['varLink'],
            'chrDisplayVideo' => $response['chrDisplayVideo'],
            'chrDisplayLink' => $response['chrDisplayLink'],
            'varShortDescription' => $response['varShortDescription'],
            'varVideoLink' => $response['varVideoLink'],
            'fkIntVideoId' => $response['fkIntVideoId'],
            'fkIntPageId' => $response['fkIntPageId'],
            'fkModuleId' => $response['fkModuleId'],
            'varBannerType' => $response['varBannerType'],
            'varBannerVersion' => $response['varBannerVersion'],
            'chrDefaultBanner' => $response['chrDefaultBanner'],
            'txtDescription' => $response['txtDescription'],
            'dtDateTime' => $response['dtDateTime'],
            'dtEndDateTime' => $response['dtEndDateTime'],
            'chrDraft' => $response['chrDraft'],
            'chrArchive' => $response['chrArchive'],
            'chrAddStar' => 'N',
            'chrPublish' => $response['chrPublish'],
        ];
        CommonModel::updateRecords($whereConditions, $updateMainRecord, false, 'Powerpanel\Banner\Models\Banner');
        //Update Copy Child Record To Main Record end
        $whereConditions_ApproveN = ['fkMainRecord' => $main_id];
        $updateToApproveN = [
            'chrApproved' => 'N',
            'chrLetest' => 'N',
            'intApprovedBy' => '0',
        ];
        CommonModel::updateRecords($whereConditions_ApproveN, $updateToApproveN, false, 'Powerpanel\Banner\Models\Banner');
        $whereConditionsApprove = ['id' => $id, 'chrMain' => 'N'];
        $updateToApprove = [
            'chrApproved' => 'Y',
            'chrRollBack' => 'Y',
            'intApprovedBy' => auth()->user()->id,
        ];
        CommonModel::updateRecords($whereConditionsApprove, $updateToApprove, false, 'Powerpanel\Banner\Models\Banner');
        $msg_show = "Record successfully approved.";
        return $msg_show;
    }

    public static function getOrderOfApproval($id)
    {
        $result = Self::select('intDisplayOrder')
            ->checkRecordId($id)
            ->first();
        return $result;
    }

    public static function getRecordCount($filterArr = false, $returnCounter = false)
    {
        $response = 0;
        $cmsPageFields = ['id'];
        $pageQuery = Self::getPowerPanelRecords($cmsPageFields);
        if ($filterArr != false) {
            $pageQuery = $pageQuery->filter($filterArr, $returnCounter);
        }
        $response = $pageQuery->deleted()
            ->where('chrMain', 'Y')
            ->count();
        return $response;
    }

    public static function getRecordCount_letest($Main_id, $id)
    {
        $moduleFields = ['chrLetest'];
        $response = Self::getPowerPanelRecords($moduleFields)
            ->deleted()
            ->where('chrMain', 'N')
            ->where('fkMainRecord', $Main_id)
            ->where('chrLetest', 'Y')
            ->where('id', '!=', $id)
            ->where('chrApproved', 'N')
            ->where('chrDraft', '!=', 'D')
            ->where('chrTrash', '!=', 'Y')
            ->where('chrIsPreview', 'N')
            ->count();
        return $response;
    }

    /**
     * This method handels retrival of record
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getRecordById($id = false, $ignoreDeleteScope = false)
    {
        $response = false;
        $imageFields = false;
        $moduleFields = [
            'id',
            'varTitle',
            'varRotateTime',
            'varRotateTime',
            'varLink',
            'fkMainRecord',
            'varBannerVersion',
            'varShortDescription',
            'fkModuleId',
            'fkIntImgId',
            'fkIntInnerImgId',
            'fkIntIconId',
            'chrDisplayVideo',
            'chrDisplayLink',
            'varVideoLink',
            'varBannerType',
            'chrAddStar',
            'fkIntVideoId',
            'txtDescription',
            'intDisplayOrder',
            'chrPublish',
            'chrDefaultBanner',
            'fkIntPageId',
            'UserID',
            'dtDateTime',
            'dtEndDateTime',
            'chrDraft',
            'chrArchive',
            'chrTrash',
            'FavoriteID',
            'LockUserID', 'chrLock',
            'created_at',
            'updated_at',
        ];
        $videoFields = [
            'id',
            'youtubeId',
            'varVideoName',
            'varVideoExtension',
        ];
        $pageFields = ['id'];
        $response = Self::getPowerPanelRecords($moduleFields, $pageFields, $imageFields, $videoFields);
        if (!$ignoreDeleteScope) {
            $response = $response->deleted();
        }
        $response = $response->checkRecordId($id)
            ->first();
        return $response;
    }

    public static function getChildrollbackGrid()
    {
        $id = $_REQUEST['id'];
        $response = false;
        $moduleFields = ['id', 'varBannerType', 'varLink', 'chrDisplayLink', 'chrDisplayVideo', 'varShortDescription', 'varVideoLink', 'fkIntVideoId', 'txtDescription', 'varBannerVersion', 'fkMainRecord', 'chrAddStar', 'chrDefaultBanner', 'chrPublish', 'chrDefaultBanner', 'fkIntPageId', 'fkIntImgId', 'fkModuleId', 'varTitle', 'fkIntInnerImgId', 'varBannerType', 'intDisplayOrder', 'created_at', 'UserID', 'chrApproved', 'updated_at', 'intApprovedBy', 'chrDraft', 'chrArchive', 'chrTrash', 'FavoriteID', 'created_at', 'updated_at'];
        $response = Self::getPowerPanelRecords($moduleFields)->deleted()->where('chrMain', 'N')->where('chrRollBack', 'Y')->where('fkMainRecord', $id)->orderBy('created_at', 'desc')->get();
        return $response;
    }

    public static function getNewRecordsCount()
    {
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
            ->where(function ($query) {
                $query->where(function ($query) {
                    $query->where("chrArchive", '!=', 'Y')
                        ->whereRaw('(dtEndDateTime >= NOW() OR dtEndDateTime is null)');
                });
            })
            ->count();
        return $response;
    }

    /**
     * This method handels retrival of record by id for Log Manage
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getRecordForLogById($id)
    {
        $response = false;
        $moduleFields = [
            'id',
            'varTitle',
            'varLink',
            'fkMainRecord',
            'varBannerVersion',
            'varShortDescription',
            'chrDisplayVideo',
            'chrDisplayLink',
            'varVideoLink',
            'fkModuleId',
            'fkIntImgId',
            'fkIntInnerImgId',
            'varBannerType',
            'fkIntVideoId',
            'txtDescription',
            'intDisplayOrder',
            'chrPublish',
            'chrDefaultBanner',
            'fkIntPageId',
            'UserID',
            'dtDateTime',
            'dtEndDateTime',
            'chrDraft',
            'chrArchive',
            'chrTrash',
            'FavoriteID',
            'LockUserID', 'chrLock',
            'created_at',
            'updated_at',
        ];
        $response = Self::getPowerPanelRecords($moduleFields)->deleted()->checkRecordId($id)->first();
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

    public static function getRecordByOrder($order = false)
    {
        $response = false;
        $moduleFields = [
            'id',
            'intDisplayOrder',
        ];
        if (!in_array($order, Self::$fetchedOrder)) {
            array_push(Self::$fetchedOrder, $order);
            Self::$fetchedOrderObj = Self::getPowerPanelRecords($moduleFields)
                ->deleted()
                ->where('chrMain', 'Y')
                ->where('chrIsPreview', 'N')
                ->orderCheck($order)
                ->first();
        }
        $response = Self::$fetchedOrderObj;
        return $response;
    }

    public function ReorderAllrecords(){
    	
    	$tablename = app(self::Class)->getTable();
    	$records = self::orderBy('intDisplayOrder', 'asc')
    									->where("chrMain",'Y')
    									->where("chrIsPreview",'N')
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
              DB::statement("UPDATE "."nq_".$tablename." SET intDisplayOrder = (CASE id " . $when . " ELSE intDisplayOrder END) WHERE id BETWEEN " . min($ids) . " AND " . max($ids) . " and chrDelete = 'N' and chrMain='Y' and chrIsPreview='N'");
          }
      }
    }

    /**
     * This method handels retrival of record for notification
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getRecordNotify($id = false)
    {
        $response = false;
        $imageFields = false;
        $moduleFields = ['varTitle'];
        $response = Self::getPowerPanelRecords($moduleFields)
            ->deleted()
            ->checkRecordId($id)
            ->first();
        return $response;
    }

    /**
     * This method handels set/unset of default banner
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function setDefault($id = false, $flagArr = false)
    {
        $response = false;
        $response = Self::where('id', $id)->update($flagArr);
        return $response;
    }

    #Database Configurations========================================
    /**
     * This method handels retrival of front end records
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */

    public static function getFrontRecords($moduleFields = false, $imageFields = false, $folderFields = false, $iconFields = false)
    {
        $response = false;

        $query = Self::select($moduleFields);

        $data = array();
        
        if($imageFields){   
            $data['image'] = function ($query) use ($imageFields, $folderFields) {
                $folderJoin = array();
                if($folderFields) {   
                    $folderJoin['folder'] = function ($query) use ($folderFields) {
                        $query = $query->select($folderFields);
                    }; 
                }
                $query = $query->select($imageFields)->with($folderJoin);
            }; 
        }

        if($iconFields){   
            $data['icon'] = function ($query) use ($iconFields, $folderFields) {
                $folderJoin = array();
                if($folderFields) {
                    $folderJoin['folder'] = function ($query) use ($folderFields) {
                        $query = $query->select($folderFields);
                    }; 
                }
                $query = $query->select($iconFields)->with($folderJoin);
            }; 
        }
        
        $response = $query->with($data);

        return $response;
    }

    /**
     * This method handels retrival of backednd records
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public static function getPowerPanelRecords($moduleFields = false, $pageFields = false, $imageFields = false, $videoFields = false, $mdlFields = false)
    {
        $data = [];
        $response = false;
        $response = self::select($moduleFields);

        $data['child'] = function ($query) {
            $query->select(['id','varTitle','fkMainRecord'])
                    ->where('chrDelete','N')
                    ->where('dtApprovedDateTime','!=',NULL);
        };

        if ($imageFields != false) {
            $data['image'] = function ($query) use ($imageFields) {
                $query->select($imageFields);
            };
        }
        if ($videoFields != false) {
            $data['video'] = function ($query) use ($videoFields) {
                $query->select($videoFields)->publish();
            };
        }
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
		return $this->hasMany('Powerpanel\Banner\Models\Banner', 'fkMainRecord', 'id');
	}

    /**
     * This method handels image relation
     * @return  Object
     * @since   2017-07-20
     */
    public function image()
    {
        $response = false;
        $response = $this->belongsTo('App\Image', 'fkIntImgId', 'id');
        return $response;
    }

    public function icon()
    {
        $response = false;
        $response = $this->belongsTo('App\Image', 'fkIntIconId', 'id');
        return $response;
    }
    
    /**
     * This method handels video relation
     * @return  Object
     * @since   2017-10-04
     */
    public function video()
    {
        $response = false;
        $response = $this->belongsTo('App\Video', 'fkIntVideoId', 'id');
        return $response;
    }

    /**
     * This method handels pages relation
     * @return  Object
     * @since   2017-07-20
     */
    public function pages()
    {
        $response = false;
        $response = $this->belongsTo('Powerpanel\CmsPage\Models\CmsPage', 'fkIntPageId', 'id');
        return $response;
    }

    /**
     * This method handels pages relation
     * @return  Object
     * @since   2017-07-20
     */
    public function modules()
    {
        $response = false;
        $response = $this->belongsTo('App\Modules', 'fkModuleId', 'id');
        return $response;
    }

    /**
     * This method handels retrival of banners records
     * @return  Object
     * @since   2016-07-20
     */
    public static function getRecords()
    {
        $response = false;
        $response = self::with(['image', 'pages']);
        return $response;
    }

    /**
     * This method handels record id scope
     * @return  Object
     * @since   2016-07-24
     */
    public function scopeCheckRecordId($query, $id)
    {
        $response = false;
        $response = $query->where('id', $id);
        return $response;
    }

    public function scopeCheckByPageId($query, $id)
    {
        $response = false;
        $response = $query->where('fkIntPageId', $id);
        return $response;
    }

    /**
     * This method handels order scope
     * @return  Object
     * @since   2016-07-20
     */
    public function scopeOrderCheck($query, $order)
    {
        $response = false;
        $response = $query->where('intDisplayOrder', $order);
        return $response;
    }

    /**
     * This method handels publish scope
     * @return  Object
     * @since   2016-07-20
     */
    public function scopePublish($query)
    {
        $response = false;
        $response = $query->where(['chrPublish' => 'Y']);
        return $response;
    }

    /**
     * This method handels delete scope
     * @return  Object
     * @since   2016-07-20
     */
    public function scopeDeleted($query)
    {
        $response = false;
        $response = $query->where(['chrDelete' => 'N']);
        return $response;
    }

    /**
     * This method handels banner type scope
     * @return  Object
     * @since   2017-08-08
     */
    public function scopeBannerType($query, $type = null)
    {
        $response = false;
        $response = $query->where(['varBannerType' => $type]);
        return $response;
    }

    /**
     * This method checking banner type
     * @return  Object
     * @since   2016-07-20
     */
    public function scopeCheckHomeBannerType($query)
    {
        $response = false;
        $response = $query->where(['varBannerType' => 'home_banner']);
        return $response;
    }

    /**
     * This method checking banner type
     * @return  Object
     * @since   2016-07-14
     */
    public function scopeCheckInnerBannerType($query)
    {
        $response = false;
        $response = $query->where(['varBannerType' => 'inner_banner']);
        return $response;
    }

    /**
     * This method checking default banner
     * @return  Object
     * @since   2016-07-14
     */
    public function scopeCheckDefaultBanner($query)
    {
        $response = false;
        $response = $query->where(['chrDefaultBanner' => 'Y']);
        return $response;
    }

    /**
     * This method checking default banner
     * @return  Object
     * @since   2016-07-14
     */
    public function scopeDisplayOrderBy($query, $orderBy)
    {
        $response = false;
        $response = $query->orderBy('intDisplayOrder', $orderBy);
        return $response;
    }

    public function scopeCheckModuleId($query, $moduleId)
    {
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
    public function scopeCheckStarRecord($query, $flag = 'Y')
    {
        $response = false;
        $response = $query->where('chrAddStar', "=", $flag);
        return $response;
    }

    public function scopeDateRange($query)
    {
        $response = false;
        $response = $query->whereRaw('((dtEndDateTime >= NOW() AND NOW() >= dtDateTime) OR (NOW() >= dtDateTime and dtEndDateTime is null))');
        return $response;
    }

    /**
     * This method handels filter scope
     * @return  Object
     * @since   2016-07-14
     */
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
        if (!empty($filterArr['statusFilter']) && $filterArr['statusFilter'] != ' ') {
            $data = $query->where('chrPublish', $filterArr['statusFilter']);
        }
        if (!empty($filterArr['searchFilter']) && $filterArr['searchFilter'] != ' ') {
            $data = $query->where('varTitle', 'like', "%" . $filterArr['searchFilter'] . "%");
        }
        if (!empty($filterArr['bannerFilter']) && $filterArr['bannerFilter'] != ' ') {
            $data = $query->where('varBannerType', '=', $filterArr['bannerFilter']);
            if ($filterArr['bannerFilter'] == 'img_banner' || $filterArr['bannerFilter'] == 'vid_banner') {
                $data = $data->orWhere('varBannerVersion', '=', $filterArr['bannerFilter']);
            }
        }
        if (!empty($filterArr['bannerFilterType']) && $filterArr['bannerFilterType'] != ' ') {
            $data = $query->where('varBannerType', $filterArr['bannerFilterType']);
        }
        if (!empty($filterArr['pageFilter']) && $filterArr['pageFilter'] != ' ') {
            $data = $query->where('fkIntPageId', '=', $filterArr['pageFilter']);
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
    public static function add_pages()
    {
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
     * This method handels Main Record scope
     * @return  Object
     * @since   2016-07-24
     * @author  NetQuick
     */
    public function scopeCheckMainRecord($query, $checkMain = 'Y')
    {
        $response = false;
        $response = $query->where('chrMain', "=", $checkMain);
        return $response;
    }

    public static function getPreviousRecordByMainId($id)
    {
        $response = Self::select('id', 'fkMainRecord')
                        ->where('fkMainRecord', $id)
                        ->where('chrMain', 'N')
                        ->where('chrApproved', 'N')
                        ->where('chrDelete','N')
                        ->where('dtApprovedDateTime','!=',NULL)
                        ->orderBy('dtApprovedDateTime', 'DESC')
                        ->first();

        return $response;

    }

}
