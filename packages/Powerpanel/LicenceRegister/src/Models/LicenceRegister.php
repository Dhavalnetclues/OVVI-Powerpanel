<?php

/**
 * The LicenceRegister class handels bannner queries
 * ORM implemetation.
 * @package   Netquick powerpanel
 * @license   http://www.opensource.org/licenses/BSD-3-Clause
 * @version   1.1
 * @since       2017-07-20
 * @author    NetQuick
 */

namespace Powerpanel\LicenceRegister\Models;

use App\Modules;
use App\CommonModel;
use Illuminate\Database\Eloquent\Model;
use Cache;
use DB;
use Powerpanel\Service\Models\Service;
use Request;

class LicenceRegister extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'licence_register';
    protected $fillable = [
        'id',
        'fkMainRecord',
        'varTitle',
        'varCompanyId',
        'varContactPerson','fkIntDocId',
        'varEmail',
        'varWeblink1','varWeblink2','varWeblink3','varContactAddress','varStatus','varService', 
        'varSector','dtDateTime','varIssuenote','chrRenewal','dtRenewaldate','varRenewalNote',
        'intSearchRank',
        'chrMain',
        'chrAddStar',
        'intDisplayOrder',
        'chrPublish',
        'chrDelete',
        'chrApproved',
        'intApprovedBy',
        'chrRollBack',
        'UserID',
        'chrAddStar',
        'varMetaTitle',
        'varMetaDescription',
        'chrPageActive',
        'varPassword',
        'chrDraft',
        'chrTrash',
        'FavoriteID',
        'LockUserID', 'chrLock',
        'created_at',
        'updated_at'
    ];

    /**
     * This method handels retrival of managementteams records
     * @return  Object
     * @since   2016-07-20
     * @author  NetQuick
     */
    public static function getRecords() {
        $response = false;
        $response = Cache::tags(['LicenceRegister'])->get('getLicenceRegisterRecords');
        if (empty($response)) {
            $response = Self::Select(['id', 'intAliasId','varTitle', 'varCompanyId', 'varEmail', 'varWeblink1','varWeblink2','varWeblink3','varContactAddress','varStatus','varService', 'varSector','dtDateTime','varIssuenote','chrRenewal','dtRenewaldate','varRenewalNote',  'intSearchRank',  'varContactPerson','fkIntDocId', 'chrMain', 'chrAddStar', 'intDisplayOrder', 'chrPublish', 'chrPageActive', 'varPassword', 'chrDraft', 'chrTrash', 'FavoriteID', 'created_at', 'updated_at'])
                    ->deleted()
                    ->publish()
                    ->get();
            Cache::tags(['LicenceRegister'])->forever('getLicenceRegisterRecords', $response);
        }
        return $response;
    }

    /**
     * This method handels retrival of front blog detail
     * @return  Object
     * @since   2017-10-13
     * @author  NetQuick
     */
    public static function getFrontDetail($aliasID) {
        $response = false;
        $response = Cache::tags(['LicenceRegister'])->get('getLicenceRegisterRecordIdByAliasID_' . $aliasID);
        if (empty($response)) {
            $moduleFields = [
                'id',
                'fkMainRecord',
                'varTitle',
                'varCompanyId',
                'varContactPerson','fkIntDocId',
                
                'varEmail',
                'varWeblink1','varWeblink2','varWeblink3','varContactAddress','varStatus','varService',
                'varSector','dtDateTime','varIssuenote','chrRenewal','dtRenewaldate','varRenewalNote',
                
                 'intSearchRank',
                'chrMain',
                'chrAddStar',
                'intDisplayOrder',
                'chrPublish',
                'chrDelete',
                'chrApproved',
                'intApprovedBy',
                'chrRollBack',
                'UserID',
                'chrAddStar',
                'varMetaTitle',
                'varMetaDescription',
                'chrPageActive',
                'varPassword',
                'chrDraft',
                'chrTrash',
                'FavoriteID',
                'LockUserID', 'chrLock',
                'created_at',
                'updated_at'
            ];
            $response = Self::Select($moduleFields)
                    ->deleted()
                    ->publish()
                    ->where('fkMainRecord', 0)
                    ->checkAliasId($aliasID)
                    ->first();
            Cache::tags(['LicenceRegister'])->forever('getLicenceRegisterRecordIdByAliasID_' . $aliasID, $response);
        }
        return $response;
    }

    public static function getFrontList() {
        $response = false;
        if (empty($response)) {
            $moduleFields = [
                'id',
                'fkMainRecord',
                'varTitle',
                'varCompanyId',
                'varContactPerson','fkIntDocId',
                'dtDateTime',
                'varEmail',
                'varWeblink1','varWeblink2','varWeblink3','varContactAddress','varStatus','varService',
                'varSector','varIssuenote','chrRenewal','dtRenewaldate','varRenewalNote',
                'intSearchRank',
                'chrMain',
                'chrAddStar',
                'intDisplayOrder',
                'chrPublish',
                'chrDelete',
                'chrApproved',
                'intApprovedBy',
                'chrRollBack',
                'UserID',
                'chrAddStar',
                'varMetaTitle',
                'varMetaDescription',
                'chrPageActive',
                'varPassword',
                'chrDraft',
                'chrTrash',
                'FavoriteID',
                'LockUserID', 'chrLock',
                'created_at',
                'updated_at'
            ];
            $aliasFields = ['id', 'varAlias'];
            $response = Self::getFrontRecords($moduleFields, $aliasFields)
                    ->deleted()
                    ->publish()
                    ->where('chrMain', 'Y')
                    ->where('chrIsPreview', 'N')
                    ->where('chrTrash', '!=', 'Y')
                    ->where('chrDraft', '!=', 'D')
                    ->orderBy('intDisplayOrder', 'asc')
                    ->get();
        }
        return $response;
    }

    public static function getFrontListPopup($filterArr = false)
    {
        $response = false;
        $moduleFields = [  'id',
                'fkMainRecord',
                'varTitle',
                'intAliasId',
                'varCompanyId',
                'varContactPerson','fkIntDocId',
                'dtDateTime',
                'varEmail',
                'varWeblink1','varWeblink2','varWeblink3','varContactAddress','varStatus','varService',
                'varSector','varIssuenote','chrRenewal','dtRenewaldate','varRenewalNote',
                'intSearchRank',
                'chrMain',
                'chrAddStar',
                'intDisplayOrder',
                'chrPublish',
                'chrDelete',
                'chrApproved',
                'intApprovedBy',
                'chrRollBack',
                'UserID',
                'chrAddStar',
                'varMetaTitle',
                'varMetaDescription',
                'chrPageActive',
                'varPassword',
                'chrDraft',
                'chrTrash',
                'FavoriteID',
                'LockUserID', 'chrLock',
                'created_at',
                'updated_at'];
        $aliasFields = ['id', 'varAlias'];
        $response = Cache::tags(['Blogs'])->get('getFrontBlogsList_');
        if (empty($response)) {
            $response = Self::getFrontRecords($moduleFields, $aliasFields)
                ->deleted()
                ->publish();
          
          
            $response = $response->where('chrTrash', '!=', 'Y')
                ->where('chrDraft', '!=', 'D')
                ->where('chrIsPreview', 'N')
                ->where('chrMain', 'Y')
                ->get();
            
            Cache::tags(['Blogs'])->forever('getFrontBlogsList_' , $response);
        }
        return $response;
    }
    public static function getFrontRecords($moduleFields = false, $aliasFields = false) {
        $response = false;
        $data = [];
        if ($aliasFields != false) {
            $data['alias'] = function ($query) use ($aliasFields) {
                $query->select($aliasFields);
            };
        }
        $response = self::select($moduleFields)->with($data);
        return $response;
    }

    /**
     * This method handels backend records
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public static function getPowerPanelRecords($moduleFields = false, $aliasFields = false, $moduleCode = false) {
        $data = [];
        $response = false;
        $response = self::select($moduleFields);

        $data['child'] = function ($query) {
            $query->select(['id','varTitle','fkMainRecord'])
                    ->where('chrDelete', 'N')
                    ->where('dtApprovedDateTime','!=',NULL);
        };

        if ($aliasFields != false) {
            $data['alias'] = function ($query) use ($aliasFields, $moduleCode) {
                $query->select($aliasFields)->checkModuleCode($moduleCode);
            };
        }
        
        if (count($data) > 0) {
            $response = $response->with($data);
        }
        return $response;
    }

    public function child() {
		return $this->hasMany('Powerpanel\LicenceRegister\Models\LicenceRegister', 'fkMainRecord', 'id');
	}
        
        
        public static function getBuilderBoard($recIds) {
        $response = false;
        $moduleFields = [
         'id',
                'fkMainRecord',
                'varTitle',
                'varCompanyId',
                'varContactPerson',
                'fkIntDocId',
                'varEmail',
                'intAliasId',
                'varWeblink1',
                'varWeblink2',
                'varWeblink3',
                'varContactAddress',
                'varStatus',
                'varService', 
                'varSector',
                'dtDateTime',
                'varIssuenote',
                'chrRenewal',
                'dtRenewaldate',
                'varRenewalNote',
                'intSearchRank',
                'chrMain',
                'chrAddStar',
                'intDisplayOrder',
                'chrPublish',
                'chrDelete',
                'chrApproved',
                'intApprovedBy',
                'chrRollBack',
                'UserID',
                'chrAddStar',
                'varMetaTitle',
                'varMetaDescription',
                'chrPageActive',
        ];
        $aliasFields = ['id', 'varAlias'];
 $response = Cache::tags(['LicenceRegister'])->get('getBuilderBoard_' . implode('-', $recIds));
        $query = Self::getFrontRecords($moduleFields, $aliasFields)
                   ->whereIn('id', $recIds)
                ->deleted()
                ->publish();
//           if(Request::segment(1) != ''){
//            $response = $query->paginate(4);
//            }else{
//             $response = $query->get();   
//            }
             $response = $query->get();  
        return $response;
    }

    public static function getAllLicenseRegister($fields = false, $limit, $dbFilter = false,$sectorfield)
    {
        $response = false;
        if (empty($response)) {
            $moduleFields = [
                'id',
                'fkMainRecord',
                'varTitle',
                'varCompanyId',
                'varContactPerson',
                'fkIntDocId',
                'varEmail',
                'intAliasId',
                'varWeblink1',
                'varWeblink2',
                'varWeblink3',
                'varContactAddress',
                'varStatus',
                'varService', 
                'varSector',
                'dtDateTime',
                'varIssuenote',
                'chrRenewal',
                'dtRenewaldate',
                'varRenewalNote',
                'intSearchRank',
                'chrMain',
                'chrAddStar',
                'intDisplayOrder',
                'chrPublish',
                'chrDelete',
                'chrApproved',
                'intApprovedBy',
                'chrRollBack',
                'UserID',
                'chrAddStar',
                'varMetaTitle',
                'varMetaDescription',
                'chrPageActive',
            ];
            $aliasFields = ['id', 'varAlias'];
            $response = Self::getFrontRecords($moduleFields, $aliasFields)
                    ->deleted()
                    ->publish()
                    ->where('chrMain', 'Y')
                    ->where('chrIsPreview', 'N')
                    ->where('chrTrash', '!=', 'Y')
                    ->where('chrDraft', '!=', 'D');                    

            if(isset($dbFilter['statusValue']) && !empty($dbFilter['statusValue']) && strtolower($dbFilter['statusValue']) != 'all') {
                $response = $response->where('varStatus', $dbFilter['statusValue']);
            }
            
            //    if (isset($sectorfield) && !empty($sectorfield)) {
            //      $response = $response->where('varSector', '=', $sectorfield);
            // }
            if(isset($dbFilter['serviceValue']) && !empty($dbFilter['serviceValue']) && strtolower($dbFilter['serviceValue']) != 'all') {
                $response = $response->whereRaw("find_in_set('".$dbFilter['serviceValue']."',varService)");
            }

            if(isset($dbFilter['sectorName']) && !empty($dbFilter['sectorName']) ) {
                $response = $response->where('varSector', '=', $dbFilter['sectorName']);
            }else{
                $sector_name = Request::segment(1);
                $response = $response->where('varSector', '=', $sectorfield);
            }

            if(isset($dbFilter['searchValue']) && !empty($dbFilter['searchValue'])) {
                $response = $response->where('varTitle', 'like', '%' . $dbFilter['searchValue'] . '%');
            }

            $response = $response->orderBy('intDisplayOrder', 'asc');

            if (Request::segment(1) != '') {
                $pageNumber = 1;
                if(isset($dbFilter['pageNumber']) && !empty($dbFilter['pageNumber'])) {
                    $pageNumber = $dbFilter['pageNumber'];
                }
                $response = $response->paginate($limit, ['*'], 'page', $pageNumber);
            } else {
                $response = $response->get();
            }
        }
        return $response;
    }

    public static function getFrontRecordDetail($aliasID) {
        $response = false;
        if (empty($response)) {
            $moduleFields = [
                'id',
                'fkMainRecord',
                'varTitle',
                'varCompanyId',
                'varContactPerson',
                'fkIntDocId',
                'varEmail',
                'intAliasId',
                'varWeblink1',
                'varWeblink2',
                'varWeblink3',
                'varContactAddress',
                'varStatus',
                'varService', 
                'varSector',
                'dtDateTime',
                'varIssuenote',
                'chrRenewal',
                'dtRenewaldate',
                'varRenewalNote',
                'intSearchRank',
                'chrMain',
                'chrAddStar',
                'intDisplayOrder',
                'chrPublish',
                'chrDelete',
                'chrApproved',
                'intApprovedBy',
                'chrRollBack',
                'UserID',
                'chrAddStar',
                'varMetaTitle',
                'varMetaDescription',
                'chrPageActive',
            ];
            $response = Self::Select($moduleFields)
                    ->deleted()
                    ->publish()
                    ->where('fkMainRecord', 0)
                    ->checkAliasId($aliasID)
                    ->first();        }
            $services = Service::getServicesForRegisterApplications($response->varService);
            $response->services = $services;
        return $response;
    }

    public function alias() {
        $response = false;
        $response = $this->belongsTo('App\Alias', 'intAliasId', 'id');
        return $response;
    }

    /**
     * This method handels retrival of backend record list
     * @return  Object
     * @since   2017-10-24
     * @author  NetQuick
     */
    public static function getRecordList($filterArr = false, $isAdmin = false, $userRoleSector = false) {
        $userid = auth()->user()->id;
        $response = false;
        $moduleFields = [
            'id',
            'fkMainRecord',
            'varTitle',
            'intAliasId',
            'varCompanyId',
            'varContactPerson','fkIntDocId',
            'varEmail',
            'varWeblink1','varWeblink2','varWeblink3','varContactAddress','varStatus','varService',
            'varSector','dtDateTime','varIssuenote','chrRenewal','dtRenewaldate','varRenewalNote',
            'intSearchRank',
            'chrMain',
            'chrAddStar',
            'intDisplayOrder',
            'chrPublish',
            'chrDelete',
            'chrApproved',
            'intApprovedBy',
            'chrRollBack',
            'UserID',
            'chrAddStar',
            'varMetaTitle',
            'varMetaDescription',
            'chrPageActive',
            'varPassword',
            'chrDraft',
            'chrTrash',
            'FavoriteID',
            'LockUserID', 'chrLock',
            'created_at',
            'updated_at'
        ];
        $aliasFields = ['id', 'varAlias'];
        $response = Self::getPowerPanelRecords($moduleFields, $aliasFields)
                ->deleted()
                ->filter($filterArr)
                ->where(function ($query) use ($userid) {
                    $query->where("UserID", '=', $userid)->where('chrPageActive', '=', 'PR')
                    ->orWhere('chrPageActive', '!=', 'PR');
                });
                if(!$isAdmin){
                    $response = $response->where('varSector', $userRoleSector);
                }
                $response = $response->checkMainRecord('Y')
                ->where('chrIsPreview', 'N')
                        ->where('chrPublish', '=', 'Y')
                ->where('chrTrash', '!=', 'Y');
        $response = $response->get();
        return $response;
    }
    public static function getRecordForService($sector = false) {
        
        $response = false;
        $moduleFields = [
            'id',
            'fkMainRecord',
            'varTitle',
            'intAliasId',
            'varCompanyId',
            'varContactPerson','fkIntDocId',
            'varEmail',
            'varWeblink1','varWeblink2','varWeblink3','varContactAddress','varStatus','varService',
            'varSector','dtDateTime','varIssuenote','chrRenewal','dtRenewaldate','varRenewalNote',
            'intSearchRank',
            'chrMain',
            'chrAddStar',
            'intDisplayOrder',
            'chrPublish',
            'chrDelete',
            'chrApproved',
            'intApprovedBy',
            'chrRollBack',
            'UserID',
            'chrAddStar',
            'varMetaTitle',
            'varMetaDescription',
            'chrPageActive',
            'varPassword',
            'chrDraft',
            'chrTrash',
            'FavoriteID',
            'LockUserID', 'chrLock',
            'created_at',
            'updated_at'
        ];
        $aliasFields = ['id', 'varAlias'];
        $response = Self::getPowerPanelRecords($moduleFields, $aliasFields)
                ->deleted()
                ->where('varSector', $sector)
                ->where('chrIsPreview', 'N')
                ->where('chrPublish', '=', 'Y')
                ->where('chrTrash', '!=', 'Y');
        $response = $response->get();
        return $response;
    }

    /**
     * This method handels retrival of backend record list
     * @return  Object
     * @since   2017-10-24
     * @author  NetQuick
     */
    public static function getRecordListFavorite($filterArr = false, $isAdmin = false, $userRoleSector) {
        $userid = auth()->user()->id;
        $response = false;
        $moduleFields = [
            'id',
            'fkMainRecord',
            'varTitle',
            'varCompanyId',
            'varContactPerson','fkIntDocId',
            
            'varEmail',
            'varWeblink1','varWeblink2','varWeblink3','varContactAddress','varStatus','varService',
            'varSector','dtDateTime','varIssuenote','chrRenewal','dtRenewaldate','varRenewalNote',
            
            'intSearchRank',
            'chrMain',
            'chrAddStar',
            'intDisplayOrder',
            'chrPublish',
            'chrDelete',
            'chrApproved',
            'intApprovedBy',
            'chrRollBack',
            'UserID',
            'chrAddStar',
            'varMetaTitle',
            'varMetaDescription',
            'chrPageActive',
            'varPassword',
            'chrDraft',
            'chrTrash',
            'FavoriteID',
            'LockUserID', 'chrLock',
            'created_at',
            'updated_at'
        ];
        $aliasFields = ['id', 'varAlias'];
        $response = Self::getPowerPanelRecords($moduleFields, $aliasFields)
                ->deleted()
                ->filter($filterArr)
                ->checkMainRecord('Y')
                ->where('chrTrash', '!=', 'Y')
                ->where('chrIsPreview', 'N')
                ->whereRaw("find_in_set($userid,FavoriteID)")
                ->where(function ($query) use ($userid) {
            $query->where("UserID", '=', $userid)->where('chrPageActive', '=', 'PR')
            ->orWhere('chrPageActive', '!=', 'PR');
        });
        if(!$isAdmin){
            $response = $response->where('varSector', $userRoleSector);
        }
        $response = $response->get();
        return $response;
    }

    /**
     * This method handels retrival of backend record list
     * @return  Object
     * @since   2017-10-24
     * @author  NetQuick
     */
    public static function getRecordListTrash($filterArr = false, $isAdmin = false, $userRoleSector) {
        $userid = auth()->user()->id;
        $response = false;
        $moduleFields = [
            'id',
            'fkMainRecord',
            'varTitle',
            'varCompanyId',
            'varContactPerson','fkIntDocId',
            
            'varEmail',
            'varWeblink1','varWeblink2','varWeblink3','varContactAddress','varStatus','varService',
            'varSector','dtDateTime','varIssuenote','chrRenewal','dtRenewaldate','varRenewalNote',
            
            'intSearchRank',
            'chrMain',
            'chrAddStar',
            'intDisplayOrder',
            'chrPublish',
            'chrDelete',
            'chrApproved',
            'intApprovedBy',
            'chrRollBack',
            'UserID',
            'chrAddStar',
            'varMetaTitle',
            'varMetaDescription',
            'chrPageActive',
            'varPassword',
            'chrDraft',
            'chrTrash',
            'FavoriteID',
            'LockUserID', 'chrLock',
            'created_at',
            'updated_at'
        ];
        $aliasFields = ['id', 'varAlias'];
        $response = Self::getPowerPanelRecords($moduleFields, $aliasFields)
                ->deleted()
                ->filter($filterArr)
                ->checkMainRecord('Y')
                ->where('chrIsPreview', 'N')
                ->where('chrTrash', 'Y')
                ->where(function ($query) use ($userid) {
            $query->where("UserID", '=', $userid)->where('chrPageActive', '=', 'PR')
            ->orWhere('chrPageActive', '!=', 'PR');
        });
        if(!$isAdmin){
            $response = $response->where('varSector', $userRoleSector);
        }
        $response = $response->get();
        return $response;
    }

    /**
     * This method handels retrival of backend record list
     * @return  Object
     * @since   2017-10-24
     * @author  NetQuick
     */
    public static function getRecordListDraft($filterArr = false, $isAdmin = false, $userRoleSector) {
        $userid = auth()->user()->id;
        $response = false;
        $moduleFields = [
            'id',
            'fkMainRecord',
            'varTitle',
            'varCompanyId',
            'varContactPerson','fkIntDocId',
            
            'varEmail',
            'varWeblink1','varWeblink2','varWeblink3','varContactAddress','varStatus','varService',
            'varSector','dtDateTime','varIssuenote','chrRenewal','dtRenewaldate','varRenewalNote',
            
            'intSearchRank',
            'chrMain',
            'chrAddStar',
            'intDisplayOrder',
            'chrPublish',
            'chrDelete',
            'chrApproved',
            'intApprovedBy',
            'chrRollBack',
            'UserID',
            'chrAddStar',
            'varMetaTitle',
            'varMetaDescription',
            'chrPageActive',
            'varPassword',
            'chrDraft',
            'chrTrash',
            'FavoriteID',
            'LockUserID', 'chrLock',
            'created_at',
            'updated_at'
        ];
        $aliasFields = ['id', 'varAlias'];
        $response = Self::getPowerPanelRecords($moduleFields, $aliasFields)
                ->deleted()
                ->filter($filterArr)
                ->checkMainRecord('Y')
                ->where('chrIsPreview', 'N')
                ->where('chrDraft', 'D')
                ->where('chrTrash', '!=', 'Y')
                ->where(function ($query) use ($userid) {
            $query->where("UserID", '=', $userid)->where('chrPageActive', '=', 'PR')
            ->orWhere('chrPageActive', '!=', 'PR');
        });
        if(!$isAdmin){
            $response = $response->where('varSector', $userRoleSector);
        }
        $response = $response->get();
        return $response;
    }

    public static function getRecordList_tab1($filterArr = false, $isAdmin = false, $userRoleSector) {
        $response = false;
        $moduleFields = [
            'id',
            'fkMainRecord',
            'varTitle',
            'varCompanyId',
            'varContactPerson','fkIntDocId',
            
            'varEmail',
            'varWeblink1','varWeblink2','varWeblink3','varContactAddress','varStatus','varService',
            'varSector','dtDateTime','varIssuenote','chrRenewal','dtRenewaldate','varRenewalNote',
            
            'intSearchRank',
            'chrMain',
            'chrAddStar',
            'intDisplayOrder',
            'chrPublish',
            'chrDelete',
            'chrApproved',
            'intApprovedBy',
            'chrRollBack',
            'UserID',
            'chrAddStar',
            'varMetaTitle',
            'varMetaDescription',
            'chrPageActive',
            'varPassword',
            'chrDraft',
            'chrTrash',
            'FavoriteID',
            'LockUserID', 'chrLock',
            'created_at',
            'updated_at'
        ];
        $userid = auth()->user()->id;
        $MainIDs = Self::distinct()
                ->select("fkMainRecord")
                ->checkMainRecord('N')
                ->where('chrIsPreview', 'N')
                ->groupBy('fkMainRecord')
                ->deleted()
                ->get()
                ->toArray();
        $MainIDs = array_column($MainIDs, 'fkMainRecord');
        $response = Self::getPowerPanelRecords($moduleFields, false)
                ->deleted()
                ->filter($filterArr)
                ->whereIn('id', $MainIDs)
                ->where('chrTrash', '!=', 'Y');
                if(!$isAdmin){
                    $response = $response->where('varSector', $userRoleSector);
                }
                $response = $response->checkStarRecord('Y')
                ->get();
        return $response;
    }

    /**
     * This method handels retrival of record by id
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getRecordById($id, $ignoreDeleteScope = false) {
        $response = false;
        $moduleFields = [
            'id',
            'intAliasId',
            'fkMainRecord',
            'varTitle',
            'varCompanyId',
            'varContactPerson','fkIntDocId',
            'varEmail',
            'varWeblink1','varWeblink2','varWeblink3','varContactAddress','varStatus','varService',
            'varSector','dtDateTime','varIssuenote','chrRenewal','dtRenewaldate','varRenewalNote',
            'intSearchRank',
            'chrMain',
            'intDisplayOrder',
            'chrPublish',
            'chrDelete',
            'chrApproved',
            'intApprovedBy',
            'chrRollBack',
            'UserID',
            'chrAddStar',
            'varMetaTitle',
            'varMetaDescription',
            'chrPageActive',
            'varPassword',
            'chrDraft',
            'chrTrash',
            'FavoriteID',
            'LockUserID', 'chrLock',
            'created_at',
            'updated_at'
        ];
        $aliasFields = ['id', 'varAlias'];
        $response = Self::getPowerPanelRecords($moduleFields, $aliasFields);
        if (!$ignoreDeleteScope) {
            $response = $response->deleted();
        }
        $response = $response->checkRecordId($id)
                ->first();
             $services = Service::getServicesForRegisterApplications($response->varService);
            $response->services = $services;
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
            'varTitle',
            'varCompanyId',
            'varContactPerson','fkIntDocId',
            
            'varEmail',
            'varWeblink1','varWeblink2','varWeblink3','varContactAddress','varStatus','varService',
            'varSector','dtDateTime','varIssuenote','chrRenewal','dtRenewaldate','varRenewalNote',
            
            'intSearchRank',
            'chrMain',
            'chrAddStar',
            'intDisplayOrder',
            'chrPublish',
            'chrDelete',
            'chrApproved',
            'intApprovedBy',
            'chrRollBack',
            'UserID',
            'chrAddStar',
            'varMetaTitle',
            'varMetaDescription',
            'chrPageActive',
            'varPassword',
            'chrDraft',
            'chrTrash',
            'FavoriteID',
            'LockUserID', 'chrLock',
            'created_at',
            'updated_at'
        ];
        $response = Self::getPowerPanelRecords($moduleFields)
                ->deleted()
                ->checkRecordId($id)
                ->first();
        return $response;
    }

    public static function getRecordCount_letest($Main_id, $id) {
        $moduleFields = ['chrLetest'];
        $response = Self::getPowerPanelRecords($moduleFields)
                ->deleted()
                ->where('chrMain', 'N')
                ->where('fkMainRecord', $Main_id)
                ->where('chrLetest', 'Y')
                ->where('id', '!=', $id)
                ->where('chrApproved', 'N')
                ->where('chrIsPreview', 'N')
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
            'intDisplayOrder',
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

    /**
     * This method handels retrival of front blog detail
     * @return  Object
     * @since   2017-10-13
     * @author  NetQuick
     */
    public static function getRecordIdByAliasID($aliasID) {
        $response = false;
        $response = Cache::tags(['LicenceRegister'])->get('getLicenceRegisterRecordIdByAliasID_' . $aliasID);
        if (empty($response)) {
            $response = Self::Select(['id', 'varTitle', 'varCompanyId', 'varEmail', 'varWeblink1','varWeblink2','varWeblink3','varContactAddress','varStatus','varService', 'varSector','dtDateTime','varIssuenote','chrRenewal','dtRenewaldate','varRenewalNote',  'intSearchRank', 'varContactPerson','fkIntDocId', 'chrMain', 'chrAddStar', 'intDisplayOrder', 'chrPublish', 'chrPageActive', 'varPassword', 'chrDraft', 'chrTrash', 'FavoriteID', 'created_at', 'updated_at'])
                            ->deleted()->publish()->checkAliasId($aliasID)->first();
            Cache::tags(['LicenceRegister'])->forever('getLicenceRegisterRecordIdByAliasID_' . $aliasID, $response);
        }
        return $response;
    }

    public static function getOrderOfApproval($id) {
        $result = Self::select('intDisplayOrder')
                ->checkRecordId($id)
                ->first();
        return $result;
    }

    /**
     * This method handels record id scope
     * @return  Object
     * @since   2016-07-24
     * @author  NetQuick
     */
    public function scopeCheckRecordId($query, $id) {
        return $query->where('id', $id);
    }

    /**
     * This method handels order scope
     * @return  Object
     * @since   2016-07-20
     * @author  NetQuick
     */
    public function scopeOrderCheck($query, $order) {
        return $query->where('intDisplayOrder', $order);
    }

    /**
     * This method handels publish scope
     * @return  Object
     * @since   2016-07-20
     * @author  NetQuick
     */
    public function scopePublish($query) {
        return $query->where(['chrPublish' => 'Y']);
    }

    /**
     * This method handels delete scope
     * @return  Object
     * @since   2016-07-20
     * @author  NetQuick
     */
    public function scopeDeleted($query) {
        return $query->where(['chrDelete' => 'N']);
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
     * This method handels filter scope
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public function scopeFilter($query, $filterArr = false, $retunTotalRecords = false) {
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
        if (!empty($filterArr['sectorFilter']) && $filterArr['sectorFilter'] != ' ') {
            $data = $query->where('varSector', $filterArr['sectorFilter']);
        }
        if (isset($filterArr['ignore']) && !empty($filterArr['ignore'])) {
            $data = $query->whereNotIn('licence_register.id', $filterArr['ignore']);
        }
        if (!empty($filterArr['customFilterIdentity']) && $filterArr['customFilterIdentity'] != ' ') {
            $data = $query->where('chrPageActive', $filterArr['customFilterIdentity']);
        }
        if (!empty($filterArr['searchFilter']) && $filterArr['searchFilter'] != ' ') {
            $data = $query->where('varTitle', 'like', "%" . $filterArr['searchFilter'] . "%");
        }
        if (!empty($query)) {
            $response = $query;
        }
        return $response;
    }

    public static function getRecordCount($filterArr = false, $returnCounter = false, $isAdmin = false, $userRoleSector) {
        $response = 0;
        $cmsPageFields = ['id'];
        $pageQuery = Self::getPowerPanelRecords($cmsPageFields);
        if ($filterArr != false) {
            $pageQuery = $pageQuery->filter($filterArr, $returnCounter);
        }
        $response = $pageQuery
                ->deleted()
                ->where('chrMain', 'Y')
                ->where('chrIsPreview', 'N');
                if(!$isAdmin){
                    $response = $response->where('varSector', $userRoleSector);
                }
                $response = $response->count();
        return $response;
    }

    /**
     * This method handels retrival of record count
     * @return  Object
     * @since   2018-10-08
     * @author  NetQuick
     */
    public static function getRecordCountforList($filterArr = false, $returnCounter = false, $isAdmin = false, $userRoleSector) {
        $response = 0;
        $cmsPageFields = ['id'];
        $response = Self::getPowerPanelRecords($cmsPageFields);
        if ($filterArr != false) {
            $response = $response->filter($filterArr, $returnCounter);
        }
        if(!$isAdmin){
            $response = $response->where('varSector', $userRoleSector);
        }
        $userid = auth()->user()->id;
        $response = $response->deleted()
                ->where(function ($query) use ($userid) {
                    $query->where("UserID", '=', $userid)->where('chrPageActive', '=', 'PR')
                    ->orWhere('chrPageActive', '!=', 'PR');
                })
                ->checkMainRecord('Y')
                ->where('chrIsPreview', 'N')
                ->where('chrTrash', '!=', 'Y')
                ->count();
        return $response;
    }

    public static function getRecordCountListApprovalTab($filterArr = false, $isAdmin = false, $userRoleSector) {
        $response = false;
        $userid = auth()->user()->id;
        $MainIDs = Self::distinct()
                ->select("fkMainRecord")
                ->where('fkMainRecord', '!=', '0')
                ->where('chrIsPreview', 'N')
                ->groupBy('fkMainRecord')
                ->get()
                ->toArray();
        $MainIDs = array_column($MainIDs, 'fkMainRecord');
        $moduleFields = ['id'];
        $response = Self::getPowerPanelRecords($moduleFields)
                ->deleted()
                ->filter($filterArr)
                ->whereIn('id', $MainIDs);
                if(!$isAdmin){
                    $response = $response->where('varSector', $userRoleSector);
                }
                $response = $response->checkStarRecord('Y')
                ->where('chrTrash', '!=', 'Y')
                ->count();
        return $response;
    }

    public static function getNewRecordsCount($isAdmin = false, $userRoleSector) {
        $response = false;
        $MainIDs = Self::distinct()
                ->select("fkMainRecord")
                ->where('fkMainRecord', '!=', '0')
                ->where('chrIsPreview', 'N')
                ->groupBy('fkMainRecord')
                ->get()
                ->toArray();
        $MainIDs = array_column($MainIDs, 'fkMainRecord');
        $moduleFields = ['id'];
        $response = Self::getPowerPanelRecords($moduleFields)
                ->deleted()
                ->whereIn('id', $MainIDs)
                ->where('chrTrash', '!=', 'Y');
                if(!$isAdmin){
                    $response = $response->where('varSector', $userRoleSector);
                }
                $response = $response->checkStarRecord('Y')
                ->count();
        return $response;
    }

    public static function getChildGrid() {
        $catfields = ['id', 'varTitle'];
        $id = $_REQUEST['id'];
        $response = false;
        $moduleFields = ['id','fkMainRecord', 'varTitle','UserID','intApprovedBy','chrApproved', 'varCompanyId', 'varEmail', 'varWeblink1','varWeblink2','varWeblink3','varContactAddress','varStatus','varService', 'varSector','dtDateTime','varIssuenote','chrRenewal','dtRenewaldate','varRenewalNote',  'intSearchRank', 'varContactPerson','fkIntDocId', 'chrMain', 'chrAddStar', 'intDisplayOrder', 'chrPublish', 'chrPageActive', 'varPassword', 'chrDraft','dtApprovedDateTime', 'chrTrash', 'FavoriteID', 'created_at', 'updated_at'];
        $response = Self::getPowerPanelRecords($moduleFields, false, $catfields)
                ->deleted()
                ->where('chrMain', 'N')
                ->where('fkMainRecord', $id)
                ->where('chrIsPreview', 'N')
                ->orderBy('created_at', 'desc')
                ->get();
        return $response;
    }

    public static function getChildrollbackGrid() {
        $id = $_REQUEST['id'];
        $response = false;
        $moduleFields = ['id', 'varTitle', 'varCompanyId', 'varEmail', 'varWeblink1','varWeblink2','varWeblink3','varContactAddress','varStatus','varService', 'varSector','dtDateTime','varIssuenote','chrRenewal','dtRenewaldate','varRenewalNote',  'intSearchRank', 'varContactPerson','fkIntDocId', 'chrMain', 'chrAddStar', 'intDisplayOrder', 'chrPublish', 'chrPageActive', 'varPassword', 'chrDraft', 'chrTrash', 'FavoriteID', 'created_at', 'updated_at'];
        $response = Self::getPowerPanelRecords($moduleFields, false)
                ->deleted()
                ->where('chrMain', 'N')
                ->where('chrRollBack', 'Y')
                ->where('fkMainRecord', $id)
                ->where('chrIsPreview', 'N')
                ->orderBy('created_at', 'desc')
                ->get();
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
            'fkMainRecord',
            'varTitle',
            'varCompanyId',
            'varContactPerson','fkIntDocId',
            
            'varEmail',
            'varWeblink1','varWeblink2','varWeblink3','varContactAddress','varStatus','varService',
            'varSector','dtDateTime','varIssuenote','chrRenewal','dtRenewaldate','varRenewalNote',
            
            'chrMain',
            'chrAddStar',
            'intDisplayOrder',
            'chrPublish',
            'chrDelete',
            'chrApproved',
            'intApprovedBy',
            'chrRollBack',
            'UserID',
            'chrAddStar',
            'varMetaTitle',
            'varMetaDescription',
            'chrPageActive',
            'varPassword',
            'chrDraft',
            'chrTrash',
            'FavoriteID',
            'LockUserID', 'chrLock',
            'created_at',
            'updated_at'
        ];
        $aliasFields = ['id', 'varAlias'];
        $response = Self::getPowerPanelRecords($moduleFields, false)
                ->deleted()
                ->where('chrMain', 'N')
                ->where('chrIsPreview', 'N')
                ->where('id', $id)
                ->orderBy('created_at', 'desc')
                ->first();
        //Select Child Record Data END
        //Update Copy Child Record To Main Record start
        $whereConditions = ['id' => $main_id];
        $updateMainRecord = [
            'varTitle' => $response['varTitle'],
            'varCompanyId' => $response['varCompanyId'],
            'fkIntDocId' => $response['fkIntDocId'],
            'varContactPerson' => $response['varContactPerson'],
            'varWeblink1' => $response['varWeblink1'],
            'varWeblink2' => $response['varWeblink2'],
            'varWeblink3' => $response['varWeblink3'],
            'varContactAddress' => $response['varContactAddress'],
            'varStatus' => $response['varStatus'],
            'varService' => $response['varService'],
            'varEmail' => $response['varEmail'],
            'varSector' => $response['varSector'],
            'dtDateTime' => $response['dtDateTime'],
            'varIssuenote' => $response['varIssuenote'],
            'chrRenewal' => $response['chrRenewal'],
            'dtRenewaldate' => $response['dtRenewaldate'],
            'varRenewalNote' => $response['varRenewalNote'],
            'chrAddStar' => 'N',
            'intSearchRank' => $response['intSearchRank'],
            'chrDraft' => $response['chrDraft'],
            'FavoriteID' => $response['FavoriteID'],
            'chrPageActive' => $response['chrPageActive'],
            'chrPublish' => $response['chrPublish'],
        ];
        CommonModel::updateRecords($whereConditions, $updateMainRecord, false, 'Powerpanel\LicenceRegister\Models\LicenceRegister');
        //Update Copy Child Record To Main Record end
        $whereConditions_ApproveN = ['fkMainRecord' => $main_id];
        $updateToApproveN = [
            'chrApproved' => 'N',
            'chrLetest' => 'N',
            'intApprovedBy' => '0',
        ];
        CommonModel::updateRecords($whereConditions_ApproveN, $updateToApproveN, false, 'Powerpanel\LicenceRegister\Models\LicenceRegister');
        $whereConditionsApprove = ['id' => $id, 'chrMain' => 'N'];
        $updateToApprove = [
            'chrApproved' => 'Y',
            'chrRollBack' => 'Y',
            'intApprovedBy' => auth()->user()->id,
            'dtApprovedDateTime' => date('Y-m-d H:i:s')
        ];
        CommonModel::updateRecords($whereConditionsApprove, $updateToApprove, false, 'Powerpanel\LicenceRegister\Models\LicenceRegister');
        $msg_show = "Record successfully approved.";
        return $msg_show;
    }

    public static function getCountById($categoryId = null) {
        $response = false;
        $moduleFields = ['id'];
        $response = Self::getPowerPanelRecords($moduleFields)
                ->where('chrMain', 'Y')
                ->where('chrIsPreview', 'N')
                ->deleted()
                ->count();
        return $response;
    }

    /**
     * This method handels retrival of backend record list
     * @return  Object
     * @since   2017-10-24
     * @author  NetQuick
     */
    public static function getFrontSearchRecordById($id, $moduleCode) {
        $filter = [];
        $moduleFields = [
            'id',
            'intAliasId'
        ];
        $aliasFields = ['id', 'varAlias'];
        $response = Self::getSearchRecords($moduleFields, $aliasFields, $moduleCode)
                ->deleted()
                ->where('id', $id)
                ->first();
        return $response;
    }

    public static function getSearchRecords($moduleFields, $aliasFields, $moduleCode) {
        $data = [];
        $response = false;
        $response = self::select($moduleFields);
        if ($aliasFields != false) {
            $data['alias'] = function ($query) use ($aliasFields, $moduleCode) {
                $query->select($aliasFields)->checkModuleCode($moduleCode);
            };
        }
        if (count($data) > 0) {
            $response = $response->with($data);
        }
        return $response;
    }

    /**
     * This method handels alias id scope
     * @return  Object
     * @since   2016-07-24
     * @author  NetQuick
     */
    public function scopeCheckAliasId($query, $id) {
        return $query->where('intAliasId', $id);
    }

    /**
     * This method handels retrival of record count
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getRecordsForMenu($moduleCode = false) {
        $response = false;
        $moduleFields = [
            'id',
            'fkMainRecord',
            'varTitle',
            'varCompanyId',
            'varContactPerson','fkIntDocId',
            
            'varEmail',
            'varWeblink1','varWeblink2','varWeblink3','varContactAddress','varStatus','varService',
            'varSector','dtDateTime','varIssuenote','chrRenewal','dtRenewaldate','varRenewalNote',
            
            'intSearchRank',
            'chrMain',
            'chrAddStar',
            'intDisplayOrder',
            'chrPublish',
            'chrDelete',
            'chrApproved',
            'intApprovedBy',
            'chrRollBack',
            'UserID',
            'chrAddStar',
            'varMetaTitle',
            'varMetaDescription',
            'chrPageActive',
            'varPassword',
            'chrDraft',
            'chrTrash',
            'FavoriteID',
            'LockUserID', 'chrLock',
            'created_at',
            'updated_at'
        ];
        $aliasFields = ['id', 'varAlias'];
        $response = Self::getPowerPanelRecords($moduleFields, $aliasFields, $moduleCode)
                ->dateRange()
                ->where('chrMain', 'Y')
                ->where('chrIsPreview', 'N')
                ->deleted()
                ->publish()
                ->get();
        return $response;
    }

    //Start Draft Count of Records 
    public static function getRecordCountforListDarft($filterArr = false, $returnCounter = false, $isAdmin = false, $ignoreId = array(), $userRoleSector) {
        $response = 0;
        $cmsPageFields = ['id'];
        $userid = auth()->user()->id;
        $response = Self::getPowerPanelRecords($cmsPageFields);
        if ($filterArr != false) {
            $response = $response->filter($filterArr, $returnCounter);
        }
        if(!$isAdmin){
            $response = $response->where('varSector', $userRoleSector);
        }
        $response = $response->deleted()
                ->checkMainRecord('Y')
                ->where('chrIsPreview', 'N')
                ->whereNotIn('id', $ignoreId)
                ->where('chrDraft', 'D')
                ->where('chrTrash', '!=', 'Y')
                ->where(function ($query) use ($userid) {
                    $query->where("UserID", '=', $userid)->where('chrPageActive', '=', 'PR')
                    ->orWhere('chrPageActive', '!=', 'PR');
                })
                ->count();
        return $response;
    }

    //End Draft Count of Records 
    //Start Trash Count of Records 
    public static function getRecordCountforListTrash($filterArr = false, $returnCounter = false, $isAdmin = false, $ignoreId = array(), $userRoleSector) {
        $response = 0;
        $cmsPageFields = ['id'];
        $userid = auth()->user()->id;
        $response = Self::getPowerPanelRecords($cmsPageFields);
        if ($filterArr != false) {
            $response = $response->filter($filterArr, $returnCounter);
        }
        if(!$isAdmin){
            $response = $response->where('varSector', $userRoleSector);
        }
        $response = $response->deleted()
                ->checkMainRecord('Y')
                ->where('chrIsPreview', 'N')
                ->whereNotIn('id', $ignoreId)
                ->where('chrTrash', 'Y')
                ->where(function ($query) use ($userid) {
                    $query->where("UserID", '=', $userid)->where('chrPageActive', '=', 'PR')
                    ->orWhere('chrPageActive', '!=', 'PR');
                })
                ->count();
        return $response;
    }

    //End Trash Count of Records 
    //Start Favorite Count of Records 
    public static function getRecordCountforListFavorite($filterArr = false, $returnCounter = false, $isAdmin = false, $ignoreId = array(), $userRoleSector) {
        $response = 0;
        $cmsPageFields = ['id'];
        $userid = auth()->user()->id;
        $response = Self::getPowerPanelRecords($cmsPageFields);
        if ($filterArr != false) {
            $response = $response->filter($filterArr, $returnCounter);
        }
        if(!$isAdmin){
            $response = $response->where('varSector', $userRoleSector);
        }
        $response = $response->deleted()
                ->checkMainRecord('Y')
                ->where('chrIsPreview', 'N')
                ->where('chrTrash', '!=', 'Y')
                ->whereNotIn('id', $ignoreId)
                ->whereRaw("find_in_set($userid,FavoriteID)")
                ->where(function ($query) use ($userid) {
                    $query->where("UserID", '=', $userid)->where('chrPageActive', '=', 'PR')
                    ->orWhere('chrPageActive', '!=', 'PR');
                })
                ->count();
        return $response;
    }

    public static function getPreviousRecordByMainId($id) {
        $response = Self::select('id', 'fkMainRecord')
                ->where('fkMainRecord', $id)
                ->where('chrMain', 'N')
                ->where('chrApproved', 'N')
                ->where('chrDelete', 'N')
                ->where('dtApprovedDateTime','!=',NULL)
                ->orderBy('dtApprovedDateTime', 'DESC')
                ->first();
        return $response;
    }

    public static function getBuilderRecordList($filterArr = [])
    {
        $response = false;
        $moduleFields = [
            'licence_register.id',
            'licence_register.intAliasId',
            'licence_register.varTitle',
            'licence_register.varSector',
            'licence_register.varStatus',
            'licence_register.varEmail',
            DB::raw('IFNULL(COUNT(nq_page_hits.id), 0) AS hits'),
            'licence_register.chrPublish',
            'licence_register.chrDelete',
            'licence_register.updated_at',
        ];
        $response = Self::getPowerPanelRecords($moduleFields, false, false, false, false)
            ->filter($filterArr);
        $response = $response->leftJoin('page_hits', 'licence_register.intAliasId', '=', 'page_hits.fkIntAliasId')
            ->where('licence_register.chrPublish', 'Y')
            ->where('licence_register.chrDelete', 'N')
            ->where('licence_register.chrMain', 'Y')
            ->where('licence_register.chrTrash', '!=', 'Y')
            ->where('licence_register.chrDraft', '!=', 'D')
            ->where('licence_register.chrIsPreview', 'N')
            ->groupBy('licence_register.id')
            ->get();
        return $response;
    }
    
    
}
