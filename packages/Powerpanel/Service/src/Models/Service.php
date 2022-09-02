<?php

/**
 * The Service class handels bannner queries
 * ORM implemetation.
 * @package   Netquick powerpanel
 * @license   http://www.opensource.org/licenses/BSD-3-Clause
 * @version   1.1
 * @since       2017-07-20
 * @author    NetQuick
 */

namespace Powerpanel\Service\Models;

use App\CommonModel;
use App\Modules;
use Cache;
use DB;
use Illuminate\Database\Eloquent\Model;
use Powerpanel\ServiceCategory\Models\ServiceCategory;
use Request;

class Service extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'service';
    protected $fillable = [
        'id',
        'fkMainRecord',
        'intFKCategory',
        'varTitle',
        'txtDescription',
        'varSector',
        'serviceCode',
        'applicationFee',
        'fkIntImgId',
        'noteTitle',
        'noteLink',
        'varRegisterID',
        'varLicenceRegisterID',
        'chrServiceFees',
        'chrMain',
        'chrAddStar',
        ' intDisplayOrder',
        'chrPublish',
        'chrDelete',
        'chrApproved',
        'intApprovedBy',
        'chrRollBack',
        'UserID',
        'chrAddStar',
        'varShortDescription',
        'chrPageActive',
        'varPassword',
        'chrDraft',
        'chrTrash',
        'FavoriteID',
        'LockUserID', 'chrLock',
        'created_at',
        'updated_at',
    ];

    /**
     * This method handels retrival of managementteams records
     * @return  Object
     * @since   2016-07-20
     * @author  NetQuick
     */
    public static function getRecords()
    {
        $response = false;
        $response = Cache::tags(['Service'])->get('getServicesRecords');
        if (empty($response)) {
            $response = Self::Select(['id', 'intFKCategory', 'varTitle','varShortDescription', 'varRegisterID', 'fkIntImgId','varLicenceRegisterID', 'varSector', 'txtDescription', 'chrPublish'])
                ->deleted()
                ->publish()
                ->paginate(10);
            Cache::tags(['Service'])->forever('getServicesRecords', $response);
        }
        return $response;
    }

    public static function getFrontListPopup($filterArr = false)
    {
        $response = false;
        $moduleFields = ['id',
        'fkMainRecord',
        'intFKCategory',
        'varTitle',
        'txtDescription',
        'varSector',
        'serviceCode',
        'applicationFee',
        'fkIntImgId',
        'noteTitle',
        'noteLink',
        'varRegisterID',
        'varLicenceRegisterID',
        'chrServiceFees',
        'chrMain',
        'chrAddStar',
        'chrPublish',
        'chrDelete',
        'chrApproved',
        'intApprovedBy',
        'chrRollBack',
        'UserID',
        'chrAddStar',
        'varShortDescription',
        'chrPageActive',
        'varPassword',
        'chrDraft',
        'chrTrash',
        'FavoriteID',
        'LockUserID', 'chrLock',
        'created_at',
        'updated_at'];
        $aliasFields = ['id', 'varAlias'];
        
            $response = Self::getFrontRecords($moduleFields, $aliasFields)
                ->deleted()
                ->publish();
          
          
            $response = $response->where('chrTrash', '!=', 'Y')
                ->where('chrDraft', '!=', 'D')
                ->where('chrIsPreview', 'N')
                ->where('chrMain', 'Y')
                ->get();
            
            
        return $response;
    }

    /**
     * This method handels retrival of front service detail
     * @return  Object
     * @since   2017-10-13
     * @author  NetQuick
     */
    public static function getFrontDetail($aliasID)
    {
        $response = false;
//        $response = Cache::tags(['Service'])->get('getServicesRecordIdByAliasID_' . $aliasID);
        if (empty($response)) {
            $moduleFields = [
                'id',
                'intFKCategory',
                'fkIntImgId',
                'varTitle',
                'varSector',
                'serviceCode',
                 'varRegisterID',
                 'varLicenceRegisterID',
                'applicationFee',
                'noteTitle',
                'noteLink',
                'chrServiceFees',
                'txtDescription',

                'chrPageActive',
                'varPassword',
                'chrDraft',
                'varShortDescription',
                'chrTrash',
                'FavoriteID',
                'created_at',
                'updated_at',
            ];
            $response = Self::Select($moduleFields)
                ->deleted()
                ->publish()
                ->where('fkMainRecord', 0)
                ->checkAliasId($aliasID)
                ->first();
//            Cache::tags(['Service'])->forever('getServicesRecordIdByAliasID_' . $aliasID, $response);
        }
        return $response;
    }

    /**
     * This method handels retrival of front service detail
     * @return  Object
     * @since   2017-10-13
     * @author  NetQuick
     */
    public static function getServicesForCalender()
    {
        $response = false;
        $moduleFields = [
            'id',
            'varTitle',
            'varSector',
            'intFKCategory',
            'fkIntImgId',
            'txtDescription',
            'varSector',
            'serviceCode',
             'varRegisterID',
             'varLicenceRegisterID',
            'applicationFee',
            'noteTitle',
            'noteLink',
            'chrServiceFees',
            'varShortDescription',
            'chrPageActive',
            'varPassword',
            'chrDraft',

            'chrTrash',
            'FavoriteID',
            'created_at',
            'updated_at',
        ];
        $aliasFields = ['id', 'varAlias'];
        $response = Self::getFrontRecords($moduleFields, $aliasFields)
            ->deleted()
            ->publish()
            ->where('chrMain', 'Y')
            ->where('chrIsPreview', 'N')
            ->daterange()
            ->get();
        return $response;
    }

    public static function getMonth()
    {
        $response = false;
        $response = self::select(DB::raw('month(dtDateTime) as month'))
            ->where('chrPublish', '=', 'Y')
            ->where('chrDelete', '=', 'N')
            ->where('chrMain', '=', 'Y')
            ->where('chrIsPreview', '=', 'N')
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();
        return $response;
    }

    public static function getYear()
    {
        $response = false;
        $response = self::select(DB::raw('year(dtDateTime) as year'))
            ->where('chrPublish', '=', 'Y')
            ->where('chrDelete', '=', 'N')
            ->where('chrMain', '=', 'Y')
            ->where('chrIsPreview', '=', 'N')
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->get();
        return $response;
    }

    /**
     * This method handels backend records
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public static function getPowerPanelRecords($moduleFields = false, $aliasFields = false, $servicesCatfileds = false, $moduleCode = false)
    {
        $data = [];
        $response = false;
        $response = self::select($moduleFields);

        $data['child'] = function ($query) {
            $query->select(['id', 'varTitle', 'fkMainRecord'])
                ->where('chrDelete', 'N')
                ->where('dtApprovedDateTime', '!=', null);
        };

        if ($aliasFields != false) {
            $data['alias'] = function ($query) use ($aliasFields, $moduleCode) {
                $query->select($aliasFields)->checkModuleCode($moduleCode);
            };
        }
        if ($servicesCatfileds != false) {
            $data['servicecat'] = function ($query) use ($servicesCatfileds) {
                $query->select($servicesCatfileds)->publish();
            };
        }
        if (count($data) > 0) {
            $response = $response->with($data);
        }
        return $response;
    }

    public function child()
    {
        return $this->hasMany('Powerpanel\Service\Models\Service', 'fkMainRecord', 'id');
    }

    public function servicecat()
    {
        $response = false;
        $response = $this->belongsTo('Powerpanel\ServiceCategory\Models\ServiceCategory', 'intFKCategory', 'id');
        return $response;
    }

    public function alias()
    {
        $response = false;
        $response = $this->belongsTo('App\Alias', 'intAliasId', 'id');
        return $response;
    }




    public static function getRecordList($filterArr = false, $isAdmin = false,$ignoreId = array(), $userRoleSector = false)
    {
        $userid = auth()->user()->id;
        $response = false;
        $moduleFields = [
            'id',
            'fkMainRecord',
            'intFKCategory',
            'intAliasId',
            'varTitle',
            'txtDescription',
            'varSector',
            'serviceCode',
            'applicationFee',
            'noteTitle',
             'varRegisterID',
             'varLicenceRegisterID',
            'noteLink',
            'chrServiceFees',
            'chrPublish',
            'chrMain',
            'intDisplayOrder',
            'chrPageActive',
            'varPassword',
            'fkIntImgId',
            'chrAddStar',
            'chrDraft',
            'varShortDescription',
            'chrTrash',
            'FavoriteID',
            'LockUserID', 'chrLock',
            'created_at',
            'updated_at',
        ];
        $aliasFields = ['id', 'varAlias'];
        $response = Self::getPowerPanelRecords($moduleFields, $aliasFields)
            ->deleted()
            ->filter($filterArr)
            ->where(function ($query) use ($userid) {
                $query->where("UserID", '=', $userid)->where('chrPageActive', '=', 'PR')
                    ->orWhere('chrPageActive', '!=', 'PR');
            })
            ->checkMainRecord('Y')
            ->where('chrIsPreview', 'N')
            ->where('chrTrash', '!=', 'Y')
            ->whereNotIn('id', $ignoreId);
            if (!$isAdmin) {
                $response = $response->where('varSector', $userRoleSector);
            }
        $response = $response->get();
        return $response;
    }

    public static function getRecordList_tab1($filterArr = false, $isAdmin = false,$ignoreId = array(), $userRoleSector)
    {
        $response = false;
        $moduleFields = [
            'id',
            'intFKCategory',
            'varTitle',
            'txtDescription',
            'intAliasId',
            'fkIntImgId',
            'varSector',
            'serviceCode',
            'applicationFee',
            'noteTitle',
            'noteLink',
            'chrServiceFees',
            'chrPublish',
            'fkMainRecord',
            'chrAddStar',
            'varRegisterID',
            'varLicenceRegisterID',
            'varShortDescription',
            'intDisplayOrder',
            'chrPageActive',
            'varPassword',
            'chrDraft',
            'chrTrash',
            'FavoriteID',
            'LockUserID', 'chrLock',
            'created_at',
            'updated_at',
        ];
        $servicesCatfileds = ['id', 'varTitle'];
        $MainIDs = Self::distinct()
            ->select("fkMainRecord")
            ->checkMainRecord('N')
            ->where('chrIsPreview', 'N')
            ->groupBy('fkMainRecord')
            ->deleted()
            ->get()
            ->toArray();
        $MainIDs = array_column($MainIDs, 'fkMainRecord');
        $response = Self::getPowerPanelRecords($moduleFields, false, $servicesCatfileds)
            ->deleted()
            ->where('chrAddStar', 'Y')
            ->where('chrTrash', '!=', 'Y')
            ->whereNotIn('id', $ignoreId);
            if (!$isAdmin) {
                $response = $response->where('varSector', $userRoleSector);
            }
        $response = $response->filter($filterArr)
            ->whereIn('id', $MainIDs)
            ->checkStarRecord('Y')
            ->get();
        return $response;
    }

    public static function getRecordListFavorite($filterArr = false, $isAdmin = false,$ignoreId = array(), $userRoleSector)
    {
        $userid = auth()->user()->id;
        $response = false;
        $moduleFields = [
            'id',
            'intFKCategory',
            'varTitle',
            'txtDescription',
            'varSector',
            'serviceCode',
            'intAliasId',
            'applicationFee',
            'noteTitle',
            'noteLink',
            'varRegisterID',
            'fkIntImgId',
            'varLicenceRegisterID',
            'chrServiceFees',
            'chrPublish',
            'chrMain',
            'intDisplayOrder',
            'chrPageActive',
            'varShortDescription',
            'varPassword',
            'chrAddStar',
            'chrDraft',
            'chrTrash',
            'FavoriteID',
            'LockUserID', 'chrLock',
            'created_at',
            'updated_at',
        ];
        $aliasFields = ['id', 'varAlias'];
        $response = Self::getPowerPanelRecords($moduleFields, $aliasFields)
            ->deleted()
            ->filter($filterArr)
            ->checkMainRecord('Y')
            ->where('chrIsPreview', 'N')
            ->where('chrTrash', '!=', 'Y')
            ->whereRaw("find_in_set($userid,FavoriteID)")
            ->where(function ($query) use ($userid) {
                $query->where("UserID", '=', $userid)->where('chrPageActive', '=', 'PR')
                    ->orWhere('chrPageActive', '!=', 'PR');
            })
            ->whereNotIn('id', $ignoreId);
            if (!$isAdmin) {
                $response = $response->where('varSector', $userRoleSector);
            }
        $response = $response->get();
        return $response;
    }

    public static function getRecordListDraft($filterArr = false, $isAdmin = false,$ignoreId = array(), $userRoleSector)
    {
        $userid = auth()->user()->id;
        $response = false;
        $moduleFields = [
            'id',
            'intFKCategory',
            'varTitle',
            'fkIntImgId',
            'txtDescription',
            'varSector',
            'serviceCode',
            'applicationFee',
            'noteTitle',
            'noteLink',
            'chrServiceFees',
            'varRegisterID',
            'varLicenceRegisterID',
            'chrPublish',
            'chrMain',
            'intDisplayOrder',
            'varShortDescription',
            'chrPageActive',
            'varPassword',
            'chrAddStar',
            'chrDraft',
            'chrTrash',
            'FavoriteID',
            'LockUserID', 'chrLock',
            'created_at',
            'updated_at',
        ];
        $aliasFields = ['id', 'varAlias'];
        $response = Self::getPowerPanelRecords($moduleFields, $aliasFields)
            ->deleted()
            ->filter($filterArr)
            ->checkMainRecord('Y')
            ->where('chrIsPreview', 'N')
            ->where('chrDraft', 'D')
            ->where('chrTrash', '!=', 'Y')
            ->whereNotIn('id', $ignoreId);
            if (!$isAdmin) {
                $response = $response->where('varSector', $userRoleSector);
            }
            $response = $response->where(function ($query) use ($userid) {
                $query->where("UserID", '=', $userid)->where('chrPageActive', '=', 'PR')
                    ->orWhere('chrPageActive', '!=', 'PR');
            });
        $response = $response->get();
        return $response;
    }

    public static function getRecordListTrash($filterArr = false, $isAdmin = false,$ignoreId = array(), $userRoleSector)
    {
        $userid = auth()->user()->id;
        $response = false;
        $moduleFields = [
            'id',
            'intFKCategory',
            'varTitle',
            'txtDescription',
            'varSector',
            'fkIntImgId',
            'serviceCode',
            'applicationFee',
            'noteTitle',
            'noteLink',
            'varRegisterID',
            'varLicenceRegisterID',
            'chrServiceFees',
            'chrPublish',
            'varShortDescription',
            'chrMain',
            'intDisplayOrder',
            'chrPageActive',
            'varPassword',
            'chrAddStar',
            'chrDraft',
            'chrTrash',
            'FavoriteID',
            'LockUserID', 'chrLock',
            'created_at',
            'updated_at',
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
            })
            ->whereNotIn('id', $ignoreId);
            if (!$isAdmin) {
                $response = $response->where('varSector', $userRoleSector);
            }
        $response = $response->get();
        return $response;
    }




    public static function getRecordCountforList($filterArr = false, $returnCounter = false, $isAdmin = false, $ignoreId = array(), $userRoleSector)
    {
        $response = 0;
        $cmsPageFields = ['id'];
        $userid = auth()->user()->id;
        $response = Self::getPowerPanelRecords($cmsPageFields);
        if ($filterArr != false) {
            $response = $response->filter($filterArr, $returnCounter);
        }
        $response = $response->deleted()
            ->where(function ($query) use ($userid) {
                $query->where("UserID", '=', $userid)->where('chrPageActive', '=', 'PR')
                    ->orWhere('chrPageActive', '!=', 'PR');
            })
            ->checkMainRecord('Y')
            ->whereNotIn('id', $ignoreId);
            if (!$isAdmin) {
                $response = $response->where('varSector', $userRoleSector);
            }
        $response = $response->where('chrIsPreview', 'N')
            ->where('chrTrash', '!=', 'Y')
            ->count();
        return $response;
    }

    public static function getRecordCountListApprovalTab($filterArr = false, $returnCounter = false, $isAdmin = false, $ignoreId = array(), $userRoleSector)
    {
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
        $response = Self::getPowerPanelRecords($moduleFields);
            if ($filterArr != false) {
                $response = $response->filter($filterArr, $returnCounter);
            }
            $response = $response->deleted()
            ->filter($filterArr)
            ->whereIn('id', $MainIDs)
            ->whereNotIn('id', $ignoreId);
            if (!$isAdmin) {
                $response = $response->where('varSector', $userRoleSector);
            }
        $response = $response->checkStarRecord('Y')
            ->where('chrTrash', '!=', 'Y')
            ->count();
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
            ->whereNotIn('id', $ignoreId)
            ->where('chrDraft', 'D')
            ->where('chrTrash', '!=', 'Y');
        if (!$isAdmin) {
            $response = $response->where('varSector', $userRoleSector);
        }
        $response = $response->where(function ($query) use ($userid) {
            $query->where("UserID", '=', $userid)->where('chrPageActive', '=', 'PR')
                ->orWhere('chrPageActive', '!=', 'PR');
        })
            ->count();
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
            ->whereNotIn('id', $ignoreId)
            ->where('chrTrash', 'Y')
            ->where(function ($query) use ($userid) {
                $query->where("UserID", '=', $userid)->where('chrPageActive', '=', 'PR')
                    ->orWhere('chrPageActive', '!=', 'PR');
            });
        if (!$isAdmin) {
            $response = $response->where('varSector', $userRoleSector);
        }
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
            ->whereNotIn('id', $ignoreId)
            ->whereRaw("find_in_set($userid,FavoriteID)")
            ->where(function ($query) use ($userid) {
                $query->where("UserID", '=', $userid)->where('chrPageActive', '=', 'PR')
                    ->orWhere('chrPageActive', '!=', 'PR');
            });
        if (!$isAdmin) {
            $response = $response->where('varSector', $userRoleSector);
        }
        $response = $response->count();
        return $response;
    }



    public static function getRecordById($id, $ignoreDeleteScope = false)
    {
        $response = false;
        $moduleFields = [
            'id',
            'intFKCategory',
            'varTitle',
            'intDisplayOrder',
            'txtDescription',
            'varSector',
            'intAliasId',
            'fkIntImgId',
            'serviceCode',
            'chrAddStar',
            'applicationFee',
            'noteTitle',
            'noteLink',
             'varRegisterID',
             'varLicenceRegisterID',
            'chrServiceFees',
            'chrPublish',
            'fkMainRecord',
            'intDisplayOrder',
            'UserID',
            'chrPageActive',
            'varPassword',
            'chrDraft',
            'chrTrash',
            'FavoriteID',
            'LockUserID',
            'varShortDescription',
            'chrLock',
            'created_at',
            'updated_at',
        ];
        $aliasFields = ['id', 'varAlias'];
        $response = Self::getPowerPanelRecords($moduleFields, $aliasFields);
        if (!$ignoreDeleteScope) {
            $response = $response->deleted();
        }
        $response = $response->checkRecordId($id)
            ->first();
        return $response;
    }

    public static function getServicesForRegisterOfApplications()
    {
        $response = false;
        $moduleFields = [
            'id',
            'intFKCategory',
            'varTitle',
            'intDisplayOrder',
            'txtDescription',
            'varSector',
            'serviceCode',
            'applicationFee',
            'varShortDescription',
            'noteTitle',
            'noteLink',
            'chrServiceFees',
            'fkIntImgId',
            'chrPublish',
             'varRegisterID',
             'varLicenceRegisterID',
            'fkMainRecord',
            'intDisplayOrder',
            'chrPageActive',
            'varPassword',
            'chrDraft',
            'chrTrash',
            'FavoriteID',
            'LockUserID',
            'chrLock',
            'created_at',
            'updated_at',
        ];
        $response = Self::getPowerPanelRecords($moduleFields);
        $response = $response
            ->where('chrTrash', '!=', 'Y')
            ->publish()
            ->deleted()
            ->get();
        return $response;
    }

    public static function getServicesForRegisterApplications($ids)
    {
        $response = false;
        $categoryArray = array();
        $moduleFields = [
            'id',
            'varTitle',
            'intFkCategory',
            'txtDescription',
            'fkIntImgId',
            'varSector',
            'serviceCode',
            'applicationFee',
            'noteTitle',
            'noteLink',
             'varRegisterID',
             'varShortDescription',
             'varLicenceRegisterID',
            'chrServiceFees',
            'chrPublish',
            'chrPageActive',
            'varPassword',
            'chrDraft',
            'chrTrash',
            'FavoriteID',
            'created_at',
            'updated_at'];
        $aliasFields = ['id', 'varAlias'];
        if (empty($response)) {
            $response = Self::getFrontRecords($moduleFields, $aliasFields)
                ->whereIn('id', explode(',', $ids))
                ->where('chrMain', 'Y')
                ->where('chrIsPreview', 'N')
                ->where('chrTrash', '!=', 'Y')
                ->where('chrDraft', '!=', 'D')
                ->deleted()
                ->publish();
            $response = $response->get();
            foreach ($response as $value) {
                $servicecategory = ServiceCategory::getRecordById($value->intFkCategory);
                $categoryArray[$servicecategory->id]['categoryName'] = $servicecategory->varTitle;
                $categoryArray[$servicecategory->id]['categorycode'] = str_replace(' ', '', $servicecategory->varTitle);
                $categoryArray[$servicecategory->id]['services'][] = $value;
            }
        }
        return $categoryArray;
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
            'intAliasId',
            'intFKCategory',
            'varTitle',
            'fkIntImgId',
            'txtDescription',
            'varSector',
            'serviceCode',
            'varShortDescription',
            'applicationFee',
            'noteTitle',
            'noteLink',
            'chrServiceFees',
             'varRegisterID',
             'varLicenceRegisterID',
            'chrPublish',
            'fkMainRecord',

            'intDisplayOrder',
            'UserID',
            'chrPageActive',
            'varPassword',
            'chrDraft',

            'chrTrash',
            'FavoriteID',
            'LockUserID', 'chrLock',
            'created_at',
            'updated_at',
        ];
        $response = Self::getPowerPanelRecords($moduleFields)
            ->deleted()
            ->checkRecordId($id)
            ->first();
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

    public static function getOrderOfApproval($id)
    {
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
    public function scopeCheckRecordId($query, $id)
    {
        return $query->where('id', $id);
    }

    /**
     * This method handels order scope
     * @return  Object
     * @since   2016-07-20
     * @author  NetQuick
     */
    public function scopeOrderCheck($query, $order)
    {
        return $query->where('intDisplayOrder', $order);
    }

    /**
     * This method handels publish scope
     * @return  Object
     * @since   2016-07-20
     * @author  NetQuick
     */
    public function scopePublish($query)
    {
        return $query->where(['chrPublish' => 'Y']);
    }

    /**
     * This method handels delete scope
     * @return  Object
     * @since   2016-07-20
     * @author  NetQuick
     */
    public function scopeDeleted($query)
    {
        return $query->where(['chrDelete' => 'N']);
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

    /**
     * This method handels filter scope
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public function scopeFilter($query, $filterArr = false, $retunTotalRecords = false)
    {
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
            $data = $query->whereNotIn('services.id', $filterArr['ignore']);
        }
        if (!empty($filterArr['catFilter']) && $filterArr['catFilter'] != ' ') {
            $data = $query->where('intFKCategory', $filterArr['catFilter']);
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

    public static function getRecordCount($filterArr = false, $returnCounter = false, $isAdmin = false, $userRoleSector)
    {
        $response = 0;
        $cmsPageFields = ['id'];
        $pageQuery = Self::getPowerPanelRecords($cmsPageFields);
        if ($filterArr != false) {
            $pageQuery = $pageQuery->filter($filterArr, $returnCounter);
        }
        $response = $pageQuery
            ->deleted()
            ->where('chrMain', 'Y');
        if (!$isAdmin) {
            $response = $response->where('varSector', $userRoleSector);
        }
        $response = $response->where('chrIsPreview', 'N')
            ->count();
        return $response;
    }

    public static function getRecordCountForDorder($filterArr = false, $returnCounter = false, $isAdmin = false, $userRoleSector)
    {
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
        if (!$isAdmin) {
            $response = $response->where('varSector', $userRoleSector);
        }
        $response = $response->where('chrIsPreview', 'N')
            ->count();
        return $response;
    }

    public static function getRecordCountOrder($filterArr = false, $returnCounter = false)
    {
        $response = 0;
        $cmsPageFields = ['id'];
        $pageQuery = Self::getPowerPanelRecords($cmsPageFields);
        if ($filterArr != false) {
            $pageQuery = $pageQuery->filter($filterArr, $returnCounter);
        }
        $response = $pageQuery
            ->deleted()
            ->where('chrMain', 'Y')
            ->count();
        return $response;
    }

    public static function getNewRecordsCount($isAdmin = false, $userRoleSector)
    {
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
            ->whereIn('id', $MainIDs);
        if (!$isAdmin) {
            $response = $response->where('varSector', $userRoleSector);
        }
        $response = $response->checkStarRecord('Y')
            ->where('chrTrash', '!=', 'Y')
            ->count();
        return $response;
    }

    public static function getChildGrid()
    {
        $servicesCatfileds = ['id', 'varTitle'];
        $id = $_REQUEST['id'];
        $response = false;
        $moduleFields = [
            'id',
            'intFKCategory',
            'varTitle',
            'txtDescription',
            'varSector',
            'serviceCode',
            'varShortDescription',
            'applicationFee',
            'noteTitle',
            'noteLink',
            'chrServiceFees',
             'varRegisterID',
             'varLicenceRegisterID',
            'intDisplayOrder',
            'chrPublish',
            'fkMainRecord',
            'created_at',
            'chrApproved',
            'updated_at',
            'intApprovedBy',
            'UserID',
            'chrPageActive',
            'varPassword',
            'chrDraft',
            'fkIntImgId',
            'chrTrash',
            'FavoriteID',
            'dtApprovedDateTime',
            'created_at',
            'updated_at',
        ];
        $response = Self::getPowerPanelRecords($moduleFields, false, $servicesCatfileds)
            ->deleted()
            ->where('chrMain', 'N')
            ->where('fkMainRecord', $id)
            ->where('chrIsPreview', 'N')
            ->orderBy('created_at', 'desc')
            ->get();
        return $response;
    }

    public static function getChildrollbackGrid()
    {
        $servicesCatfileds = ['id', 'varTitle'];
        $id = $_REQUEST['id'];
        $response = false;
        $moduleFields = [
            'id',
            'intFKCategory',
            'varTitle',
            'txtDescription',
            'varSector',
            'serviceCode',
            'varShortDescription',
            'applicationFee',
            'noteTitle',
            'noteLink',
            'chrServiceFees',
            'chrPublish',
            'intDisplayOrder',
             'varRegisterID',
             'varLicenceRegisterID',
            'fkMainRecord',
            'created_at',
            'chrApproved',
            'updated_at',
            'intApprovedBy',
            'UserID',
            'chrPageActive',
            'varPassword',
            'chrDraft',
            'fkIntImgId',
            'chrTrash',
            'FavoriteID',
            'created_at',
            'updated_at',
        ];
        $response = Self::getPowerPanelRecords($moduleFields, false, $servicesCatfileds)
            ->deleted()
            ->where('chrMain', 'N')
            ->where('chrRollBack', 'Y')
            ->where('fkMainRecord', $id)
            ->where('chrIsPreview', 'N')
            ->orderBy('created_at', 'desc')
            ->get();
        return $response;
    }

    public static function approved_data_Listing($request)
    {
        $id = $request->id;
        $main_id = $request->main_id;
        //Select Child Record Data Start
        $servicesCatfileds = ['id', 'varTitle'];
        $response = false;
        $moduleFields = [
            'id',
             'fkMainRecord',
            'intFKCategory',
            'varTitle',
            'txtDescription',
            'varSector',
            'serviceCode',
            'applicationFee',
            'noteTitle',
            'noteLink',
            'intAliasId',
             'varRegisterID',
             'varShortDescription',
             'varLicenceRegisterID',
            'chrServiceFees',
            'intDisplayOrder',
            'chrPublish',
            'chrPageActive',
            'varPassword',
            'chrDraft',
            'intDisplayOrder',
            'fkIntImgId',
            'chrTrash',
            'FavoriteID',
            'created_at',
            'updated_at',
        ];
        $aliasFields = ['id', 'varAlias'];
        $response = Self::getPowerPanelRecords($moduleFields, false, $servicesCatfileds)
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
            'varSector' => $response['varSector'],
            'serviceCode' => $response['serviceCode'],
            'applicationFee' => $response['applicationFee'],
            'noteTitle' => $response['noteTitle'],
            'noteLink' => $response['noteLink'],
            'varRegisterID' => $response['varRegisterID'],
            'varLicenceRegisterID' => $response['varLicenceRegisterID'],
            'fkIntImgId' => $response['fkIntImgId'],
            'chrServiceFees' => $response['chrServiceFees'],
            'intFKCategory' => $response['intFKCategory'],
            'varSector' => $response['varSector'],
            'txtDescription' => $response['txtDescription'],
            'chrAddStar' => 'N',
            'chrDraft' => $response['chrDraft'],
            'intDisplayOrder' => $response['intDisplayOrder'],
            'FavoriteID' => $response['FavoriteID'],
            'chrPageActive' => $response['chrPageActive'],
            'chrPublish' => $response['chrPublish'],
        ];
        CommonModel::updateRecords($whereConditions, $updateMainRecord, false, 'Powerpanel\Service\Models\Service');
        //Update Copy Child Record To Main Record end

        $whereConditions_ApproveN = ['fkMainRecord' => $main_id];
        $updateToApproveN = [
            'chrApproved' => 'N',
            'chrLetest' => 'N',
            'intApprovedBy' => '0',
        ];
        CommonModel::updateRecords($whereConditions_ApproveN, $updateToApproveN, false, 'Powerpanel\Service\Models\Service');

        $whereConditionsApprove = ['id' => $id, 'chrMain' => 'N'];
        $updateToApprove = [
            'chrApproved' => 'Y',
            'chrRollBack' => 'Y',
            'intApprovedBy' => auth()->user()->id,
            'dtApprovedDateTime' => date('Y-m-d H:i:s'),
        ];
        CommonModel::updateRecords($whereConditionsApprove, $updateToApprove, false, 'Powerpanel\Service\Models\Service');
        $msg_show = "Record successfully approved.";
        return $msg_show;
    }

    public static function getCountById($categoryId = null)
    {
        $response = false;
        $moduleFields = ['id'];
        $response = Self::getPowerPanelRecords($moduleFields)
            ->checkCategoryId($categoryId)
            ->where('chrMain', 'Y')
            ->where('chrIsPreview', 'N')
            ->deleted()
            ->count();
        return $response;
    }

    public static function getFrontList($filterArr = false, $page = 1, $catid = false, $print = false, $categoryid, $name = "", $start_date_time = "", $end_date_time = "")
    {
        $response = false;
        $moduleFields = ['id',
            'fkIntImgId',
            'varTitle',
            'intAliasId',
            'intFkCategory',
            'txtDescription',
            'varSector',
            'serviceCode',
            'varShortDescription',
            'applicationFee',
            'noteTitle',
            'noteLink',
            'chrServiceFees',
             'varRegisterID',
            'varLicenceRegisterID',
            'chrPublish',
            'chrPageActive',
            'varPassword',
            'chrDraft',
            'intDisplayOrder',
            'chrTrash',
            'FavoriteID',
            'created_at',
            'updated_at'];
        $aliasFields = ['id', 'varAlias'];
        $response = Cache::tags(['Services'])->get('getFrontServicesList_' . $page);
        if (empty($response)) {
            $response = Self::getFrontRecords($moduleFields, $aliasFields)
                ->deleted()
                ->publish();
            if ($categoryid != '') {
                $response = $response->whereRaw(DB::raw('intFkCategory="' . $categoryid . '"'));
            }
            if ($name != '') {
                $response = $response->where('varTitle', 'like', '%' . '' . $name . '' . '%');
            }
            if ($start_date_time != '' && $end_date_time != '') {
                $response = $response->whereRaw('(dtDateTime>="' . $start_date_time . '" AND (dtDateTime<="' . $end_date_time . '"))');
            } else if ($start_date_time != '') {
                $response = $response->whereRaw('dtDateTime>="' . $start_date_time . '"')->dateRange();
            } else if ($end_date_time != '') {
                $response = $response->whereRaw('dtDateTime<="' . $end_date_time . '"')->dateRange();
            } else {
                $response = $response->dateRange();
            }
            $response = $response->where('chrTrash', '!=', 'Y')
                ->where('chrDraft', '!=', 'D')
                ->orderBy('DESC')
                ->where('chrIsPreview', 'N')
                ->where('chrMain', 'Y');
            if ($catid != false) {
                $response = $response->where('intFkCategory', '=', $catid);
            }
            if ($print == 'print') {
                $response = $response->get();
            } else {
                $response = $response->paginate($page);
            }
            Cache::tags(['Services'])->forever('getFrontServicesList_' . $page, $response);
        }
        return $response;
    }

    /**
     * This method handels retrival of news records
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public static function getFrontRecords($moduleFields = false, $aliasFields = false)
    {
        $response = false;
        $data = [];
        if ($aliasFields != false) {
            $data = [
                'alias' => function ($query) use ($aliasFields) {
                    $query->select($aliasFields);
                },
            ];
        }
        $response = self::select($moduleFields)->with($data);
        return $response;
    }

    /**
     * This method handels retrival of backend record list
     * @return  Object
     * @since   2017-10-24
     * @author  NetQuick
     */
    public static function getFrontSearchRecordById($id, $moduleCode)
    {
        $categoryModuleCode = Modules::getModule('coin-catalogue-category')->id;
        $filter = [];
        $moduleFields = [
            'id',
            'intFKCategory',

        ];
        $catFileds = ['id', 'intAliasId'];
        $aliasFields = ['id', 'varAlias'];
        $response = Self::getSearchRecords($moduleFields, $catFileds, $aliasFields, $moduleCode, $categoryModuleCode)
            ->deleted()
            ->where('id', $id)
            ->first();
        return $response;
    }

    /**
     * This method handels retrival of front service detail
     * @return  Object
     * @since   2017-10-13
     * @author  NetQuick
     */
    public static function getRecordIdByAliasID($aliasID)
    {
        $response = false;
        $response = Cache::tags(['Services'])->get('getServicesRecordIdByAliasID_' . $aliasID);
        if (empty($response)) {
            $response = Self::Select('id')->deleted()->publish()->checkAliasId($aliasID)->first();
            Cache::tags(['Services'])->forever('getServicesRecordIdByAliasID_' . $aliasID, $response);
        }
        return $response;
    }

    public static function getSearchRecords($moduleFields, $catFileds, $aliasFields, $moduleCode, $categoryModuleCode)
    {
        $data = [];
        $response = false;
        $response = self::select($moduleFields);
        if ($aliasFields != false) {
            $data['alias'] = function ($query) use ($aliasFields, $moduleCode) {
                $query->select($aliasFields)->checkModuleCode($moduleCode);
            };
        }
        if ($catFileds != false) {
            $data['cat'] = function ($query) use ($catFileds, $aliasFields, $categoryModuleCode) {
                $joinArr = [];
                $joinArr['alias'] = function ($query) use ($aliasFields, $categoryModuleCode) {
                    $query->select($aliasFields)->checkModuleCode($categoryModuleCode);
                };
                $query->select($catFileds)->with($joinArr);
            };
        }
        if (count($data) > 0) {
            $response = $response->with($data);
        }
        return $response;
    }

    public function cat()
    {
        $response = false;
        $response = $this->belongsTo('Powerpanel\ServiceCategory\Models\ServiceCategory', 'intFKCategory', 'id');
        return $response;
    }

    public function scopeCheckCategoryId($query, $id)
    {
        $response = false;
        $response = $query->where('intFKCategory', 'like', '%' . $id . '%')->orWhere('intFKCategory', 'like', '%' . $id . '%');
        return $response;
    }

    /**
     * This method handels Popular Service scope
     * @return  Object
     * @since   2016-08-30
     * @author  NetQuick
     */
    public function scopeLatest($query, $id = false)
    {
        $response = false;
        $response = $query
        /* ->groupBy('id') */
            ->orderBy('desc');
        if ($id > 0) {
            $response = $response->where('id', '!=', $id);
            //->whereRaw('dtDateTime > DATE_SUB(NOW(), INTERVAL 7 DAY)')
            //->whereRaw('dtDateTime <= NOW()');
        }
        return $response;
    }

    /**
     * This method handels retrival of front latest executives list
     * @return  Object
     * @since   2017-10-13
     * @author  NetQuick
     */
    public static function getLatestList($id = false)
    {
        $response = false;
        $moduleFields = ['id',

            'varTitle',
            'txtDescription',
            'varSector',
            'serviceCode',
             'varRegisterID',
            'varLicenceRegisterID',
            'applicationFee',
            'noteTitle',
            'noteLink',
            'chrServiceFees',
            'varShortDescription',
            'intDisplayOrder',
            'intAliasId',
            'chrPublish',
            'chrPageActive',
            'varPassword',
            'chrDraft',
            'fkIntImgId',
            'chrTrash',
            'FavoriteID',
            'created_at',
            'updated_at'];
        $aliasFields = ['id', 'varAlias'];
        $response = Cache::tags(['Executives'])->get('getFrontLatestServicesList_' . $id);
        if (empty($response)) {
            $response = Self::getFrontRecords($moduleFields, $aliasFields)
                ->deleted()
                ->publish()
                ->latest($id)
                ->take(5)
                ->get();
            Cache::tags(['Executives'])->forever('getFrontLatestServicesList_' . $id, $response);
        }
        return $response;
    }

     /**
     * This method handels retrival of front latest services for builder section list
     * @return  Object
     * @since   2022-07-12
     * @author  NetQuick
     */
    public static function getTemplateServiceList($id = false, $title, $config, $layout, $filter, $class, $desc, $limit)
    {
        $response = false;
        $moduleFields = [
            'id',
            'varTitle',
            'txtDescription',
            'varSector',
            'serviceCode',
            'varRegisterID',
            'varLicenceRegisterID',
            'applicationFee',
            'noteTitle',
            'noteLink',
            'chrServiceFees',
            'fkIntImgId',
            'intDisplayOrder',
            'varShortDescription',
            'chrPublish',
            'chrPageActive',
            'varPassword',
            'chrDraft',
            'chrTrash',
            'FavoriteID',
            'created_at',
            'updated_at'];
        $aliasFields = ['id', 'varAlias'];
        $response = Self::getFrontRecords($moduleFields, $aliasFields)
            ->deleted()
            ->publish()
            //->latest($id)
            ->limit($limit)
            ->get();
        return $response;
    }

    /**
     * This method handels alias id scope
     * @return  Object
     * @since   2016-07-24
     * @author  NetQuick
     */
    public function scopeCheckAliasId($query, $id)
    {
        return $query->where('intAliasId', $id);
    }

    public function scopeDateRange($query)
    {
        $response = false;
        $response = $query->whereRaw('((dtEndDateTime >= NOW() AND NOW() >= dtDateTime) OR (NOW() >= dtDateTime and dtEndDateTime is null))');
        return $response;
    }

    public static function getBuilderRecordList($filterArr = [])
    {
        $response = false;
        $moduleFields = [
            'service.id',
            'service.intAliasId',
            'service.varTitle', 'service.varSector',
            'service.intFKCategory',
            'service.serviceCode',
            'service.applicationFee',
            'service.fkIntImgId',
            'service.chrPublish',
            'service.chrDelete',
            'service.updated_at',
        ];
        $response = Self::getPowerPanelRecords($moduleFields, false, false, false, false)
            ->filter($filterArr);
            $response = $response->leftJoin('page_hits', 'services.intAliasId', '=', 'page_hits.fkIntAliasId')
            ->where('service.chrDelete', 'N')
            ->where('service.chrMain', 'Y')
            ->where('service.chrTrash', '!=', 'Y')
            ->where('service.chrDraft', '!=', 'D')
            ->where('service.chrIsPreview', 'N')
            ->groupBy('service.id')
            ->get();
        return $response;
    }

    public static function getBuilderServices($fields, $recIds)
    {
        $response = false;
        $moduleFields = [
            'id',
            'intAliasId',
            'varTitle',
            'intFkCategory',
            'txtDescription',
            'varSector',
            'serviceCode',
            'varRegisterID',
            'varLicenceRegisterID',
            'applicationFee',
            'noteTitle',
            'noteLink',
            'chrServiceFees',
            'varShortDescription',
            'chrPublish',
            'chrPageActive',
            'varPassword',
            'chrDraft',
            'fkIntImgId',
            'chrTrash',
            'FavoriteID',
            'created_at',
            'updated_at'];
        array_push($moduleFields, 'fkIntImgId');
        $aliasFields = ['id', 'varAlias'];
        $response = Cache::tags(['Services'])->get('getBuilderService_' . implode('-', $recIds));
        if (empty($response)) {
            $response = Self::getFrontRecords($moduleFields, $aliasFields)
                ->whereIn('id', $recIds)
                ->where('chrMain', 'Y')
                ->where('chrIsPreview', 'N')
                ->where('chrTrash', '!=', 'Y')
                ->where('chrDraft', '!=', 'D')
                ->deleted()
                ->publish()
                ->orderByRaw(DB::raw("FIELD(id, " . implode(',', $recIds) . " )"));
            $response = $response->get();
            Cache::tags(['Services'])->forever('getBuilderService_' . implode('-', $recIds), $response);
        }
        return $response;
    }

    public static function getAllServices($fields, $limit, $servicescat)
    {
        $response = false;
        $moduleFields = [
            'id',
            'intAliasId',
            'varTitle',
            'intFkCategory',
            'txtDescription',
            'varShortDescription',
            'fkIntImgId',
            'varSector',
            'serviceCode',
            'varRegisterID',
            'varLicenceRegisterID',
            'applicationFee',
            'noteTitle',
            'noteLink',
            'chrServiceFees',
            'chrPublish',
            'chrPageActive',
            'varPassword',
            'chrDraft',
            'varShortDescription',
            'chrTrash',
            'FavoriteID',
            'created_at',
            'updated_at'
        ];

        array_push($moduleFields, 'fkIntImgId');
        $aliasFields = ['id', 'varAlias'];

        if (empty($response)) {
            $response = Self::getFrontRecords($moduleFields, $aliasFields)->where('chrMain', 'Y');
            if ($servicescat != '') {
                $response = $response->where('intFkCategory', $servicescat);
            }

            // if ($sdate != '' && $edate != '') {
            //     $response = $response->whereRaw('(DATE(dtDateTime)>="' . date('Y-m-d', strtotime(str_replace('/', '-', $sdate))) . '" AND (DATE(dtDateTime)<="' . date('Y-m-d', strtotime(str_replace('/', '-', $edate))) . '") OR ("' . date('Y-m-d', strtotime(str_replace('/', '-', $sdate))) . '" >= dtDateTime and dtEndDateTime is null))');
            // } else if ($sdate != '') {
            //     $response = $response->whereRaw('DATE(dtDateTime)>="' . date('Y-m-d', strtotime(str_replace('/', '-', $sdate))) . '"');
            // } else if ($edate != '') {
            //     $response = $response->whereRaw('DATE(dtDateTime)<="' . date('Y-m-d', strtotime(str_replace('/', '-', $edate))) . '"');
            // }

            $response = $response->where('chrIsPreview', 'N')
                ->deleted()
                ->publish()
                ->where('chrTrash', '!=', 'Y')
                ->where('chrDraft', '!=', 'D');
                // ->orderBy('dtDateTime', 'desc');
            if ($limit != '') {
                $response = $response->limit($limit);
            }
            if (Request::segment(1) != '') {
                $response = $response->paginate(6);
            } else {
                $response = $response->get();
            }

        }
        return $response;
    }

    public static function getPreviousRecordByMainId($id)
    {
        $response = Self::select('id', 'fkMainRecord')
            ->where('fkMainRecord', $id)
            ->where('chrMain', 'N')
            ->where('chrApproved', 'N')
            ->where('chrDelete', 'N')
            ->where('dtApprovedDateTime', '!=', null)
            ->orderBy('dtApprovedDateTime', 'DESC')
            ->first();
        return $response;

    }

    public static function sitemap(){
        $eventsFields = ['id', 'intAliasId','varTitle','created_at','updated_at'];
        $aliasFields = ['id', 'varAlias', 'intFkModuleCode'];
        $moduleFields = ['id', 'varModuleName', 'varModuleClass'];
        $response = Self::getFrontRecords($eventsFields, $aliasFields, false)
            ->deleted()
            ->publish()
            ->where('chrIsPreview','=','N')
            ->checkMainRecord('Y')
            ->where('chrIsPreview', 'N')
            ->where('chrTrash', '!=', 'Y')
            ->orderBy('varTitle')
            ->get();
        return $response;
    }

}
