<?php

namespace Powerpanel\CmsPage\Models;

use Illuminate\Database\Eloquent\Model;
use Cache;
use App\CommonModel;
use Config;
use DB;

class CmsPage extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'cms_page';
    protected $fillable = [
        'id',
        'intAliasId',
        'intFKModuleCode',
        'varTitle','varSector',
        'txtDescription',
        'dtDateTime',
        'dtEndDateTime',
        'varMetaTitle',
        'varMetaDescription',
        'intSearchRank',
        'chrDisplay',
        'chrPublish',
        'chrDelete',
        'chrPageActive',
        'varPassword',
        'chrDraft',
        'chrTrash',
        'FavoriteID',
        'LockUserID',
        'chrLock',
        'chrArchive'
    ];

    public static function getRecordByModuleId($id, $moduleId) {
        $cmspageFields = [
            'id',
            'intAliasId',
            'varSector',
            'intFKModuleCode'
        ];
        $aliasFields = ['id', 'varAlias'];
        $moduleFields = ['id'];
        $response = Self::getPowerPanelRecords($cmspageFields, $aliasFields, false, $moduleId)
                ->deleted()
                ->publish()
                ->where('chrMain', 'Y')
                ->where('intFKModuleCode', $id)
                ->first();
        return $response;
    }

    public static function getRecordByModuleIdForQlink($id, $moduleId, $pageId = false) {
        $cmspageFields = [
            'id',
            'intAliasId',
            'intFKModuleCode'
        ];
        $aliasFields = ['id', 'varAlias'];
        $moduleFields = ['id'];
        $response = Self::getPowerPanelRecords($cmspageFields, $aliasFields, false, $moduleId)
                ->deleted()
                ->publish()
                ->where('chrMain', 'Y')
                ->where('intFKModuleCode', $id);
        if ($pageId) {
            $response = $response->where('id', $pageId);
        }
        $response = $response->first();
        return $response;
    }

    
    public static function getPopupPage() {
        $response = false;
        //$response = Cache::tags(['ContactInfo'])->get('getFrontContactDetails');
        if (empty($response)) {
            $moduleFields = ['id', 'varTitle','varSector', 'intAliasId', 'intFKModuleCode', 'txtDescription'];
            $response = Self::select($moduleFields)->deleted()->publish()->get();
            //$response = Self::select($moduleFields)->deleted()->publish()->get();
            //Cache::tags(['ContactInfo'])->forever('getFrontContactDetails', $response);
        }
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
        $response = Self::Select('id')->deleted()->publish()->checkAliasId($aliasID)->first();
        return $response;
    }

    public static function getFrontRecordById($id) {
        $response = false;
        $noticesFields = [
            'id',
            'varTitle','varSector',
            'txtDescription',
        ];
        $response = Cache::tags(['Home'])->get('getFrontRecordById_' . $id);
        if (empty($response)) {
            $response = Self::getFrontPageRecord($noticesFields)
                    ->deleted()
                    ->publish()
                    ->where('chrMain', 'Y')
                    ->where('id', $id)
                    ->dateRange()
                    ->orderBy('dtDateTime', 'desc')
                    ->first();
            Cache::tags(['Home'])->forever('getFrontRecordById_' . $id, $response);
        }
        return $response;
    }

    /**
     * This method handels retrival of blog records
     * @return  Object
     * @since   2016-07-24
     * @author  NetQuick
     */
    public static function getPageWithAlias($aliasId = false, $preview = false) {
        $response = false;
        $cmsPageFields = ['id','varTitle','varSector','intAliasId','intFKModuleCode'];
        $aliasFields = ['id', 'varAlias', 'intFkModuleCode'];
        $moduleFields = ['id', 'varModuleName', 'varModuleClass'];


        if ($preview) {
            $response = Self::getFrontPageRecord($cmsPageFields, $aliasFields, $moduleFields)
                            ->deleted()
                            ->publish()
                            ->checkAliasId($aliasId)
                            ->orderBy('id', 'desc')
                            ->first();

        } else {
            $response = Self::getFrontPageRecord($cmsPageFields, $aliasFields, $moduleFields)
                            ->deleted()
                            ->publish()
                            ->dateRange()
                            ->where('chrMain','Y')
                            ->checkIsNotPreview()
                            ->checkAliasId($aliasId)
                            ->first();
        }
        return $response;
    }

    /**
     * This method handels retrival of blog records
     * @return  Object
     * @since   2016-07-24
     * @author  NetQuick
     */
    public static function getHomePageDisplaySections() {
        $response = false;
        $cmsPageFields = ['id', 'varTitle','varSector', 'intAliasId', 'intFKModuleCode', 'txtDescription'];
        $aliasFields = ['id', 'varAlias', 'intFkModuleCode'];
        $moduleFields = ['id', 'varModuleName', 'varModuleClass'];
        $response = Self::getFrontPageRecord($cmsPageFields, $aliasFields, $moduleFields)
                ->deleted()
                ->where('chrMain', 'Y')
                ->where('chrIsPreview', 'N')
                ->where('id', '1')
                ->dateRange()
                ->publish()
                ->first();
        return $response;
    }

    /**
     * This method handels retrival of blog records
     * @return  Object
     * @since   2016-07-24
     * @author  NetQuick
     */
    public static function getPageContentByPageAlias($cmsPageId) {
        $response = false;
        $response = Self::select(['txtDescription', 'varTitle','varSector', 'id', 'chrPageActive', 'varPassword', 'UserID'])
                ->deleted()
                ->where('fkMainRecord', 0)
                ->checkAliasId($cmsPageId)
                ->first();
        return $response;
    }

    /**
     * This method handels retrival of blog records
     * @return  Object
     * @since   2016-07-24
     * @author  NetQuick
     */
    public static function getPageByPageId($cmsPageId, $checkAlias = true) {
        $response = false;
        $response = Self::select(['id', 'intAliasId', 'intFKModuleCode', 'varTitle', 'varSector','txtDescription', 'chrPageActive', 'varPassword', 'dtDateTime', 'dtEndDateTime', 'varMetaTitle', 'varMetaDescription']);
        if ($checkAlias) {
            $response->checkAliasId($cmsPageId);
        } else {
            $response->checkRecordId($cmsPageId);
        }
        $response = $response->first();
        return $response;
    }

    public static function getPriviewPageByPageId($cmsPageId, $checkAlias = true) {
        $response = false;
        $response = Self::select(['id', 'intAliasId', 'intFKModuleCode', 'varTitle', 'varSector','txtDescription', 'dtDateTime', 'dtEndDateTime', 'varMetaTitle', 'varMetaDescription']);
        $response->checkRecordId($cmsPageId);
        $response = $response->first();
        return $response;
    }

    /**
     * This method handels retrival of blog records
     * @return  Object
     * @since   2016-07-24
     * @author  NetQuick
     */
    public static function getFrontPageRecord($cmsPageFields = false, $aliasFields = false, $moduleFields = false) {
        $data = [];
        $pageObj = Self::select($cmsPageFields);
        if ($aliasFields != false) {
            $data['alias'] = function ($query) use ($aliasFields) {
                $query->select($aliasFields);
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

    /**
     * This method handels retrival of blog records
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public static function getPowerPanelRecords($cmsPageFields = false, $aliasFields = false, $moduleFields = false, $moduleCode = false) {
        $data = [];
        $pageObj = Self::select($cmsPageFields);

        $data['child'] = function ($query) {
            $query->select(['id','varTitle','fkMainRecord'])
                    ->where('chrDelete','N')
                    ->where('dtApprovedDateTime','!=',NULL);
        };

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

    public function child() {
		return $this->hasMany('Powerpanel\CmsPage\Models\CmsPage', 'fkMainRecord', 'id');
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
        $response = Self::getPowerPanelRecords($cmsPageFields);
        if ($filterArr != false) {
            $response = $response->filter($filterArr, $returnCounter);
        }
        $response = $response
                ->deleted()
                ->checkMainRecord('Y');
                if(!$isAdmin){
                    $response = $response->where('varSector', $userRoleSector);
                }
                $response = $response->where('chrIsPreview', 'N')
                ->count();
        return $response;
    }


    public static function getRecordCountforList($filterArr = false, $returnCounter = false, $isAdmin = false, $ignoreId = array(), $userRoleSector) {
        $response = 0;
        $cmsPageFields = ['id'];
        $response = Self::getPowerPanelRecords($cmsPageFields);
        if ($filterArr != false) {
            $response = $response->filter($filterArr, $returnCounter);
        }
        $userid = auth()->user()->id;
        $response = $response->deleted()
                ->where(function ($query) use ($userid) {
                    $query->where("UserID", '=', $userid)->where('chrPageActive', '=', 'PR')
                    ->orWhere('chrPageActive', '!=', 'PR');
                })
                ->checkMainRecord('Y')
                ->whereNotIn('id', $ignoreId);
                if(!$isAdmin){
                    $response = $response->where('varSector', $userRoleSector);
                }
                $response = $response->where('chrIsPreview', 'N')
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


    public static function getRecordCountforListDarft($filterArr = false, $returnCounter = false, $isAdmin = false, $ignoreId = array(), $userRoleSector) {
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
                if(!$isAdmin){
                    $response = $response->where('varSector', $userRoleSector);
                }
                $response = $response->where(function ($query) use ($userid) {
                    $query->where("UserID", '=', $userid)->where('chrPageActive', '=', 'PR')
                    ->orWhere('chrPageActive', '!=', 'PR');
                })
                ->where(function ($query) {
                	$query->where(function ($query) {
                    $query->where("chrArchive", '!=', 'Y')
                    ->whereRaw('(dtEndDateTime >= NOW() OR dtEndDateTime is null)');
                  });
                })
                ->count();
        return $response;
    }


    public static function getRecordCountforListTrash($filterArr = false, $returnCounter = false, $isAdmin = false, $ignoreId = array(),$userRoleSector) {
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
                ->where('chrIsPreview', 'N')
                ->where('chrTrash', '!=', 'Y')
                ->whereNotIn('id', $ignoreId)
                ->whereRaw("find_in_set($userid,FavoriteID)")
                ->where(function ($query) use ($userid) {
                    $query->where("UserID", '=', $userid)->where('chrPageActive', '=', 'PR')
                    ->orWhere('chrPageActive', '!=', 'PR');
                })
                ->where(function ($query) {
                	$query->where(function ($query) {
                    $query->where("chrArchive", '!=', 'Y')
                    ->whereRaw('(dtEndDateTime >= NOW() OR dtEndDateTime is null)');
                  });
                });
                if(!$isAdmin){
                    $response = $response->where('varSector', $userRoleSector);
                }
                $response = $response->count();
        return $response;
    }


    public static function getRecordCount_tab1($filterArr = false, $returnCounter = false) {
        $response = 0;
        $cmsPageFields = ['id'];
        $response = Self::getPowerPanelRecords($cmsPageFields);
        if ($filterArr != false) {
            $response = $response->filter($filterArr, $returnCounter);
        }
        $response = $response
                ->deleted()
                ->checkMainRecord('Y')
                ->count();
        return $response;
    }



    public static function getRecordList($filterArr = false, $isAdmin = false, $ignoreId = array(), $userRoleSector = false) {
        $userid = auth()->user()->id;
        $response = false;
        $cmsPageFields = ['id','fkMainRecord','intAliasId', 'intFKModuleCode', 'varTitle','varSector', 'txtDescription', 'dtDateTime', 'dtEndDateTime', 'varMetaTitle', 'varMetaDescription', 'chrArchive', 'UserID', 'chrPublish', 'chrDelete', 'chrAddStar', 'intSearchRank', 'FavoriteID', 'chrDraft', 'chrPageActive', 'LockUserID', 'chrLock', 'updated_at', 'created_at'];
        $aliasFields = ['id', 'varAlias'];
        $response = Self::getPowerPanelRecords($cmsPageFields, $aliasFields)
                ->deleted()
                ->filter($filterArr)
                ->where(function ($query) use ($userid) {
                    $query->where("UserID", '=', $userid)->where('chrPageActive', '=', 'PR')
                    ->orWhere('chrPageActive', '!=', 'PR');
                })
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
                if(!$isAdmin){
                    $response = $response->where('varSector', $userRoleSector);
                }
        $response = $response->get();
        return $response;
    }
    
    
    
     public static function getRecordListforinternaldropdown($filterArr = false, $isAdmin = false, $userRoleSector) {
        $userid = auth()->user()->id;
        $response = false;
        $cmsPageFields = ['id', 'intAliasId', 'intFKModuleCode', 'varTitle','varSector', 'txtDescription', 'dtDateTime', 'dtEndDateTime', 'varMetaTitle', 'varMetaDescription', 'chrArchive', 'UserID', 'chrPublish', 'chrDelete', 'chrAddStar', 'intSearchRank', 'FavoriteID', 'chrDraft', 'chrPageActive', 'LockUserID', 'chrLock', 'updated_at', 'created_at'];
        $aliasFields = ['id', 'varAlias'];
        $response = Self::getPowerPanelRecords($cmsPageFields, $aliasFields)
                ->deleted()
                ->filter($filterArr)
                ->where(function ($query) use ($userid) {
                    $query->where("UserID", '=', $userid)->where('chrPageActive', '=', 'PR')
                    ->orWhere('chrPageActive', '!=', 'PR');
                })
                ->checkMainRecord('Y')
                ->where('chrIsPreview', 'N')
                ->where('chrTrash', '!=', 'Y')
                ->where(function ($query) {
                	$query->where(function ($query) {
                    $query->where("chrArchive", '!=', 'Y')
                    ->whereRaw('(dtEndDateTime >= NOW() OR dtEndDateTime is null)');
                  });
                });
                
                if(!$isAdmin){
                    $response = $response->where('varSector', $userRoleSector);
                }
        $response = $response->get();
        return $response;
    }

    public static function getRecordListDraft($filterArr = false, $isAdmin = false, $ignoreId = array(), $userRoleSector) {
        $response = false;
        $userid = auth()->user()->id;
        $cmsPageFields = [
            'id',
            'intAliasId',
            'intFKModuleCode',
            'varTitle','varSector',
            'txtDescription',
            'dtDateTime',
            'dtEndDateTime',
            'varMetaTitle',
            'varMetaDescription',
            'chrPublish',
            'chrDelete',
            'intSearchRank',
            'FavoriteID',
            'chrAddStar',
            'chrDraft',
            'chrArchive',
            'updated_at',
            'chrPageActive',
            'chrLock',
            'LockUserID',
            'created_at'
        ];
        $aliasFields = ['id', 'varAlias'];
        $response = Self::getPowerPanelRecords($cmsPageFields, $aliasFields)
                ->deleted()
                ->filter($filterArr)
                ->checkMainRecord('Y')
                ->where('chrIsPreview', 'N')
                ->where('chrDraft', 'D')
                ->where('chrTrash', '!=', 'Y');
                if(!$isAdmin){
                    $response = $response->where('varSector', $userRoleSector);
                }
                $response = $response->where(function ($query) use ($userid) {
                    $query->where("UserID", '=', $userid)->where('chrPageActive', '=', 'PR')
                    ->orWhere('chrPageActive', '!=', 'PR');
                })
                ->where(function ($query) {
                	$query->where(function ($query) {
                    $query->where("chrArchive", '!=', 'Y')
                    ->whereRaw('(dtEndDateTime >= NOW() OR dtEndDateTime is null)');
                  });
                })
                ->whereNotIn('id', $ignoreId);
        $response = $response->get();
        return $response;
    }

    public static function getRecordListTrash($filterArr = false, $isAdmin = false, $ignoreId = array()) {
        $response = false;
        $userid = auth()->user()->id;
        $cmsPageFields = [
            'id',
            'intAliasId',
            'intFKModuleCode',
            'varTitle','varSector',
            'txtDescription',
            'dtDateTime',
            'dtEndDateTime',
            'varMetaTitle',
            'varMetaDescription',
            'chrPublish',
            'chrDelete',
            'intSearchRank',
            'FavoriteID',
            'chrAddStar',
            'chrDraft',
            'chrArchive',
            'updated_at',
            'chrPageActive',
            'chrLock',
            'LockUserID',
            'created_at'
        ];
        $aliasFields = ['id', 'varAlias'];
        $response = Self::getPowerPanelRecords($cmsPageFields, $aliasFields)
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
                if(!$isAdmin){
                    $response = $response->where('varSector', $userRoleSector);
                }
        $response = $response->get();
        return $response;
    }

    public static function getRecordListFavorite($filterArr = false, $isAdmin = false, $ignoreId = array(), $userRoleSector) {
        $response = false;
        $userid = auth()->user()->id;
        $cmsPageFields = [
            'id',
            'intAliasId',
            'intFKModuleCode',
            'varTitle','varSector',
            'txtDescription',
            'dtDateTime',
            'dtEndDateTime',
            'varMetaTitle',
            'varMetaDescription',
            'chrPublish',
            'chrDelete',
            'intSearchRank',
            'FavoriteID',
            'chrAddStar',
            'chrDraft',
            'chrArchive',
            'updated_at',
            'chrPageActive',
            'chrLock',
            'LockUserID',
            'created_at'
        ];
        $aliasFields = ['id', 'varAlias'];
        $response = Self::getPowerPanelRecords($cmsPageFields, $aliasFields)
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
                ->where(function ($query) {
                	$query->where(function ($query) {
                    $query->where("chrArchive", '!=', 'Y')
                    ->whereRaw('(dtEndDateTime >= NOW() OR dtEndDateTime is null)');
                  });
                })
                ->whereNotIn('id', $ignoreId);
                if(!$isAdmin){
                    $response = $response->where('varSector', $userRoleSector);
                }
        $response = $response->get();
        return $response;
    }
    
    public static function getRecordListArchive($filterArr = false, $isAdmin = false, $ignoreId = array(), $userRoleSector) {
        $response = false;
        $userid = auth()->user()->id;
        $cmsPageFields = [
            'id',
            'intAliasId',
            'intFKModuleCode',
            'varTitle','varSector',
            'txtDescription',
            'dtDateTime',
            'dtEndDateTime',
            'varMetaTitle',
            'varMetaDescription',
            'chrPublish',
            'chrDelete',
            'intSearchRank',
            'FavoriteID',
            'chrAddStar',
            'chrDraft',
            'updated_at',
            'chrPageActive',
            'chrLock',
            'chrArchive',
            'LockUserID',
            'created_at'
        ];
        $aliasFields = ['id', 'varAlias'];
        $response = Self::getPowerPanelRecords($cmsPageFields, $aliasFields)
                ->deleted()
                ->filter($filterArr)
                ->checkMainRecord('Y')
                ->where('chrIsPreview', 'N')
                ->where('chrTrash', '!=', 'Y')
                ->where(function ($query) {
                	$query->where(function ($query) {
                    $query->where("chrArchive", '!=', 'Y')
                    ->whereRaw('(dtEndDateTime <= NOW())');
                  })
                  ->orWhere(function ($query) {
                    $query->where("chrArchive", '=', 'Y')
                    ->whereRaw('(dtEndDateTime <= NOW())');
                  })
                  ->orWhere('chrArchive', '=', 'Y');
                })
                ->whereNotIn('id', $ignoreId);
                if(!$isAdmin){
                    $response = $response->where('varSector', $userRoleSector);
                }
        $response = $response->get();
        return $response;
    }

    public static function getRecordList_tab1($filterArr = false, $isAdmin = false, $userRoleSector) {
        $response = false;
        $MainIDs = Self::distinct()
                ->select("fkMainRecord")
                ->checkMainRecord('N')
                ->where('chrIsPreview', 'N')
                ->groupBy('fkMainRecord')
                ->deleted()
                ->get()
                ->toArray();
        $MainIDs = array_column($MainIDs, 'fkMainRecord');
        $userid = auth()->user()->id;
        $cmsPageFields = [
            'id',
            'fkMainRecord',
            'intAliasId',
            'intFKModuleCode',
            'varTitle','varSector',
            'txtDescription',
            'dtDateTime',
            'dtEndDateTime',
            'varMetaTitle',
            'varMetaDescription',
            'chrPublish',
            'chrDelete',
            'intSearchRank',
            'chrAddStar',
            'chrDraft',
            'chrArchive',
            'updated_at',
            'chrPageActive',
            'created_at',
            'chrLock',
            'LockUserID',
            'FavoriteID'
        ];
        $aliasFields = ['id', 'varAlias'];
        $response = Self::getPowerPanelRecords($cmsPageFields, $aliasFields)
                ->deleted()
                ->filter($filterArr)
                ->whereIn('id', $MainIDs)
                ->where('chrTrash', '!=', 'Y');
                if(!$isAdmin){
                    $response = $response->where('varSector', $userRoleSector);
                }
                $response = $response->checkStarRecord('Y')
                ->where(function ($query) {
                	$query->where(function ($query) {
                    $query->where("chrArchive", '!=', 'Y')
                    ->whereRaw('(dtEndDateTime >= NOW() OR dtEndDateTime is null)');
                  });
                })
                ->get();
        return $response;
    }

    /**
     * This method handels retrival of record count
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getRecordsForMenu($moduleCode = false) {
        $response = false;
        $userid = auth()->user()->id;
        $cmsPageFields = ['id', 'intAliasId', 'intFKModuleCode', 'varTitle','varSector', 'txtDescription', 'dtDateTime', 'dtEndDateTime','chrArchive', 'varMetaTitle', 'varMetaDescription', 'chrPublish', 'chrDelete'];
        $aliasFields = ['id', 'varAlias'];
        $moduleFields = ['id', 'varModuleName'];
        $ignoreId = [0];
        $response = Self::getPowerPanelRecords($cmsPageFields, $aliasFields, $moduleFields, $moduleCode)
                ->checkMainRecord('Y')
                ->where('chrIsPreview', 'N')
                ->where('chrDraft', '!=', 'D')
                ->where('chrArchive', '!=', 'Y')
                ->where('chrTrash', '!=', 'Y')
                ->where(function ($query) use ($userid) {
                    $query->where("UserID", '=', $userid)->where('chrPageActive', '=', 'PR')
                    ->orWhere('chrPageActive', '!=', 'PR');
                })
                ->whereNotIn('id', $ignoreId)
                ->orderBy('updated_at', 'desc')
                ->deleted()
                ->publish()
                ->get();
        return $response;
    }

    public static function getNewRecordsCount($isAdmin=false, $userRoleSector) {
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
                ->whereIn('id', $MainIDs);
                if(!$isAdmin){
                    $response = $response->where('varSector', $userRoleSector);
                }
                $response = $response->where('chrTrash', '!=', 'Y')
                ->checkStarRecord('Y')
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
     * This method handels retrival of record count
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getPagesWithModule($moduleCode = false) {
        $response = false;
        $cmsPageFields = ['id', 'intAliasId', 'intFKModuleCode', 'varTitle','varSector', 'txtDescription', 'dtDateTime', 'dtEndDateTime', 'varMetaTitle', 'varMetaDescription', 'chrPublish', 'chrDelete'];
        $moduleFields = ['id', 'varModuleName'];
        $response = Self::getPowerPanelRecords($cmsPageFields, false, $moduleFields, $moduleCode)
                ->deleted()
                ->publish()
                ->where('chrIsPreview', 'N')
                ->checkMainRecord('Y')
                ->orderBy('varTitle')
                ->get();
        return $response;
    }

    /**
     * This method handels retrival of record count
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getPagesWithModuleForLinks($ignoreModuleIds = array(), $moduleCode = false) {
        $response = false;
        $cmsPageFields = ['id', 'intAliasId', 'intFKModuleCode', 'varTitle','varSector', 'txtDescription', 'dtDateTime', 'dtEndDateTime', 'varMetaTitle', 'varMetaDescription', 'chrPublish', 'chrDelete'];
        $moduleFields = ['id', 'varModuleName'];
        $response = Self::getPowerPanelRecords($cmsPageFields, false, $moduleFields, $moduleCode)
                ->deleted()
                ->publish()
                ->where('chrIsPreview', 'N')
                ->checkMainRecord('Y')
                ->where('chrTrash', '!=', 'Y')
                ->orderBy('varTitle');
        if (!empty($ignoreModuleIds)) {
            $response = $response->whereNotIn('intFKModuleCode', $ignoreModuleIds);
        }
        $response = $response->get();
        return $response;
    }

    public static function getPageWithModuleId($moduleCode = false) {
        $response = false;
        $cmsPageFields = ['id', 'intAliasId', 'intFKModuleCode', 'varTitle','varSector'];
        $moduleFields = ['id', 'varModuleName'];
        $response = Self::getPowerPanelRecords($cmsPageFields, false, $moduleFields, $moduleCode)
                ->deleted()
                ->publish()
                ->Pages()
                ->first();
        return $response;
    }

    /**
     * This method handels retrival of record count
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getRecordById($id, $ignoreDeleteScope = false) {
        $response = false;
        $cmsPageFields = [
            'id',
            'intSearchRank',
            'FavoriteID',
            'chrPageActive',
            'varPassword',
            'intAliasId',
            'intFKModuleCode',
            'varTitle','varSector',
            'txtDescription',
            'dtDateTime',
            'dtEndDateTime',
            'varMetaTitle',
            'varMetaDescription',
            'chrAddStar',
            'varTags',
            'chrPublish',
            'chrDelete',
            'fkMainRecord',
            'UserID',
            'chrDraft',
             'chrArchive',
            'chrTrash',
            'chrMain',
            'LockUserID',
            'chrLock'
        ];
        $aliasFields = ['id', 'varAlias'];
        $moduleFields = ['id', 'varModuleName'];
//        echo $id;exit;
        $response = Self::getPowerPanelRecords($cmsPageFields, $aliasFields);
        if (!$ignoreDeleteScope) {
            $response = $response->deleted();
        }
        $response = $response->checkRecordId($id)->first();
        return $response;
    }

    /**
     * This method handels retrival of record for delete
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getRecordsforDeleteById($id) {
        $response = false;
        $moduleFields = ['id', 'varTitle','varSector'];
        $response = Self::getPowerPanelRecords($moduleFields)->checkRecordId($id)->first();
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
        $cmspageFields = [
            'id',
            'intSearchRank',
            'FavoriteID',
            'chrPageActive',
            'varPassword',
            'intAliasId',
            'intFKModuleCode',
            'varTitle','varSector',
            'txtDescription',
            'dtDateTime',
            'dtEndDateTime',
            'varMetaTitle',
            'varMetaDescription',
            'chrPublish',
            'chrDelete',
            'fkMainRecord',
            'UserID',
            'chrDraft',
             'chrArchive',
            'chrTrash',
            'FavoriteID',
            'LockUserID',
            'chrLock'
        ];
        $aliasFields = ['id', 'varAlias'];
        $moduleFields = ['id', 'varModuleName'];
        $response = Self::getPowerPanelRecords($cmspageFields, $aliasFields, $moduleFields)->deleted()->checkRecordId($id)->first();
        return $response;
    }

    /**
     * This method handels retrival of record by id for Log Manage
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getHomePageShortDescriptionById($id) {
        $response = false;
        $cmspageFields = ['id', 'intSearchRank', 'FavoriteID', 'chrPageActive', 'varPassword', 'intAliasId', 'intFKModuleCode', 'varTitle','varSector', 'txtDescription', 'dtDateTime', 'dtEndDateTime', 'varMetaTitle', 'varMetaDescription', 'chrPublish', 'chrDelete', 'fkMainRecord', 'UserID'];
        $response = Self::getPowerPanelRecords($cmspageFields)->deleted()->checkRecordId($id)->publish()->first();
        return $response;
    }

    /**
     * This method handels retrival of record count
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getRecordCountListApprovalTab($filterArr = false, $returnCounter = false, $isAdmin = false, $ignoreId = array(), $userRoleSector) {
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
                ->deleted();
                if ($filterArr != false) {
                    $response = $response->filter($filterArr, $returnCounter);
                }
                $response = $response->whereIn('id', $MainIDs)
                ->whereNotIn('id', $ignoreId);
                if(!$isAdmin){
                    $response = $response->where('varSector', $userRoleSector);
                }
                $response = $response->where('chrTrash', '!=', 'Y')
                ->checkStarRecord('Y')
                ->where(function ($query) {
                	$query->where(function ($query) {
                    $query->where("chrArchive", '!=', 'Y')
                    ->whereRaw('(dtEndDateTime >= NOW() OR dtEndDateTime is null)');
                  });
                })
                ->count();
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
                ->where('chrDraft', '!=', 'D')
                ->where('chrTrash', '!=', 'Y')
                ->where('chrIsPreview', 'N')
                ->count();
        return $response;
    }

    public static function getRecordForMenuAddByModuleId($id, $moduleId = false) {
        $cmspageFields = [
            'id',
            'fkMainRecord',
            'intAliasId',
            'intFKModuleCode',
            'varTitle','varSector',
            'txtDescription',
            'dtDateTime',
            'dtEndDateTime',
            'chrMain',
            'chrPublish',
            'chrDelete',
        ];
        $aliasFields = ['id', 'varAlias'];
        $moduleFields = ['id', 'varModuleName'];
        $response = Self::getPowerPanelRecords($cmspageFields, $aliasFields, false, $moduleId)
                ->deleted()
                ->where('chrMain', 'Y')
                ->where('chrLetest', 'N')
                ->where('chrApproved', 'N')
                ->where('intFKModuleCode', $id)
                ->first();
        return $response;
    }

    public static function getRecordForPowerpanelShareByModuleId($id, $moduleId = false) {
        $cmspageFields = [
            'id',
            'fkMainRecord',
            'intAliasId',
            'intFKModuleCode',
            'varTitle','varSector',
            'txtDescription',
            'dtDateTime',
            'dtEndDateTime',
            'chrMain',
            'chrPublish',
            'chrDelete',
        ];
        $aliasFields = ['id', 'varAlias'];
        $moduleFields = ['id', 'varModuleName'];
        $response = Self::getPowerPanelRecords($cmspageFields, $aliasFields, false, $moduleId)
                ->deleted()
                ->where('chrMain', 'Y')
                ->where('chrLetest', 'N')
                ->where('chrApproved', 'N')
                ->where('intFKModuleCode', $id)
                ->first();
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
                    ->where('chrIsPreview', 'N')
                    ->first();
        }
        $response = Self::$fetchedOrderObj;
        return $response;
    }

    /**
     * This method handels retrival of record with id and title
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getPagesIdTitle() {
        $response = false;
        $cmsPageFields = ['id', 'varTitle'];
        $response = Self::getPowerPanelRecords($cmsPageFields)
                ->deleted()
                ->get();
        return $response;
    }

    /**
     * This method handels retrival of record with id and title
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getHomePage() {
        $response = false;
        $cmsPageFields = ['id', 'varTitle', 'intFKModuleCode'];
        $moduleFields = ['id'];
        $response = Self::getPowerPanelRecords($cmsPageFields, false, $moduleFields)
                ->getHomePage()
                ->first();
        return $response;
    }
    
    public static function getFrontList($filterArr = false)
    {
        $response = false;
        $moduleFields = [ 'id',
        'intAliasId',
        'intFKModuleCode',
        'varTitle','varSector',
        'txtDescription',
        'dtDateTime',
        'dtEndDateTime',
        'varMetaTitle',
        'varMetaDescription',
        'intSearchRank',
        'chrPublish',
        'chrDelete',
        'chrPageActive',
        'varPassword',
        'chrDraft',
        'chrTrash',
        'FavoriteID',
        'LockUserID',
        'chrLock',
        'chrArchive'];
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
    
    
    public static function getFrontListPopup($filterArr = false)
    {
        $response = false;
        $moduleFields = [ 'id',
        'intAliasId',
        'intFKModuleCode',
        'varTitle','varSector',
        'txtDescription',
        'dtDateTime',
        'dtEndDateTime',
        'varMetaTitle',
        'varMetaDescription',
        'intSearchRank',
        'chrPublish',
        'chrDelete',
        'chrPageActive',
        'varPassword',
        'chrDraft',
        'chrTrash',
        'FavoriteID',
        'LockUserID',
        'chrLock',
        'chrArchive'];
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
     * This method handels retrival of page title by page id
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getPageTitleById($id = false) {
        $response = false;
        $cmsPageFields = ['varTitle', 'intFKModuleCode'];
        $response = Self::getPowerPanelRecords($cmsPageFields)
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
    public static function getOptionPageList($filterArr = false) {
        $response = false;
        $cmsPageFields = ['id', 'varTitle'];
        $response = Self::getPowerPanelRecords($cmsPageFields)->pluck('varTitle', 'id');
        return $response;
    }

    /**
     * This method handels alias relation
     * @return  Object
     * @since   2016-07-24
     * @author  NetQuick
     */
    public function alias() {
        return $this->belongsTo('App\Alias', 'intAliasId', 'id');
    }

    /**
     * This method handels module relation
     * @return  Object
     * @since   2016-07-24
     * @author  NetQuick
     */
    public function modules() {
        return $this->belongsTo('App\Modules', 'intFKModuleCode', 'id');
    }

    /**
     * This method handels module relation
     * @return  Object
     * @since   2016-07-24
     * @author  NetQuick
     */
    // public function pagehits()
    // {
    //    return $this->belongsTo('App\Pagehit', 'intAliasId', 'fkIntAliasId');
    // }
    /**
     * This method handels retrival of blog records
     * @return  Object
     * @since   2016-07-24
     * @author  NetQuick
     */
    public static function getRecords($moduleId = false) {
        return self::with(['alias' => function ($query) use ($moduleId) {
                        $query->checkModuleCode($moduleId);
                    }, 'modules']);
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
     * This method handels alias id scope
     * @return  Object
     * @since   2016-07-24
     * @author  NetQuick
     */
    public function scopeCheckModuleId($query, $id) {
        return $query->where('intFKModuleCode', $id);
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
     * @since   2016-07-24
     * @author  NetQuick
     */
    public function scopeOrderCheck($query, $order) {
        return $query->where('intDisplayOrder', $order);
    }

    /**
     * This method handels home page scope
     * @return  Object
     * @since   2016-07-24
     * @author  NetQuick
     */
    public function scopeGetHomePage($query) {
        return $query->where('varTitle', 'Home');
    }

    /**
     * This method handels publish scope
     * @return  Object
     * @since   2016-07-24
     * @author  NetQuick
     */
    public function scopePublish($query) {
        return $query->where(['chrPublish' => 'Y']);
    }

    public function scopePages($query) {
        return $query->where('varTitle', '=', 'Pages');
    }

    /**
     * This method handels delete scope
     * @return  Object
     * @since   2016-07-24
     * @author  NetQuick
     */
    public function scopeDeleted($query) {
        return $query->where(['chrDelete' => 'N']);
    }

    public function scopeCheckIsNotPreview($query) {
        return $query->where('chrIsPreview', 'N');
    }

    public function scopeCheckIsPreview($query) {
        return $query->where('chrIsPreview', 'Y');
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

    static function GetFrontdetaiBreadumb($id) {
        $response = false;
        $cmsPageFields = ['varTitle', 'intFKModuleCode'];
        $response = Self::getPowerPanelRecords($cmsPageFields)
                ->deleted()
                ->checkAliasName($id)
                ->first();
        return $response;
    }

    function scopeCheckAliasName($query, $id) {
        return $query->where('intAliasId', $id);
    }

    /**
     * This method handels filter scope
     * @return  Object
     * @since   2016-07-24
     * @author  NetQuick
     */
    public function scopeFilter($query, $filterArr = false, $retunTotalRecords = false) {
        $response = null;
        if (!empty($filterArr['orderByFieldName']) && !empty($filterArr['orderTypeAscOrDesc'])) {
            $query = $query->orderBy($filterArr['orderByFieldName'], $filterArr['orderTypeAscOrDesc']);
        } else {
            $query = $query->orderBy('updated_at', 'ASC');
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

        if (!empty($filterArr['customFilterIdentity']) && $filterArr['customFilterIdentity'] != ' ') {
            $data = $query->where('chrPageActive', $filterArr['customFilterIdentity']);
        }

        if (!empty($filterArr['searchFilter']) && $filterArr['searchFilter'] != ' ') {
            $data = $query->where('varTitle', 'like', "%" . $filterArr['searchFilter'] . "%");
        }
        if (!empty($filterArr['rangeFilter']) && $filterArr['rangeFilter'] != ' ') {
            $data = $query->whereRaw('DATE(dtStartDateTime) >= DATE("' . date('Y-m-d', strtotime($filterArr['rangeFilter']['from'])) . '")  AND DATE(dtEndDateTime) <= DATE("' . date('Y-m-d', strtotime($filterArr['rangeFilter']['to'])) . '")');
        }
        if (!empty($query)) {
            $response = $query->checkIsNotPreview();
        }
        return $response;
    }

    /**
     * This method handels front search scope
     * @return  Object
     * @since   2016-08-09
     * @author  NetQuick
     */
    public function scopeFrontSearch($query, $term = '') {
        return $query->where(['varTitle', 'like', '%' . $term . '%']);
    }

    public function banners() {
        return $this->hasMany('Powerpanel\Banner\Models\Banner', 'image', 'id');
    }

    public function menu() {
        return $this->hasOne('Powerpanel\Menu\Models\Menu', 'id', 'intPageId');
    }

    /**
     * This method handle to child grid.
     * @author  Snehal
     */
    public static function getChildGrid() {
        $id = $_REQUEST['id'];
        $response = false;
        $cmsPageFields = ['id', 'intAliasId', 'intFKModuleCode', 'varTitle','varSector', 'txtDescription', 'dtDateTime', 'dtEndDateTime', 'varMetaTitle', 'varMetaDescription', 'chrPublish', 'chrDelete', 'created_at', 'UserID', 'chrApproved', 'fkMainRecord', 'dtApprovedDateTime', 'intApprovedBy', 'updated_at'];
        $aliasFields = ['id', 'varAlias'];
        $response = Self::getPowerPanelRecords($cmsPageFields, $aliasFields)
                ->deleted()
                ->where('chrMain', 'N')
                ->where('chrIsPreview', 'N')
                ->where('fkMainRecord', $id)
                ->orderBy('created_at', 'desc')
                ->get();
        return $response;
    }

    public static function getChildrollbackGrid() {
        $id = $_REQUEST['id'];
        $response = false;
        $cmsPageFields = ['id', 'intAliasId', 'intFKModuleCode', 'varTitle','varSector', 'txtDescription', 'dtDateTime', 'dtEndDateTime', 'varMetaTitle', 'varMetaDescription', 'chrPublish', 'chrDelete', 'created_at', 'UserID', 'chrApproved', 'fkMainRecord', 'intApprovedBy', 'dtApprovedDateTime', 'updated_at', 'created_at'];
        $aliasFields = ['id', 'varAlias'];
        $response = Self::getPowerPanelRecords($cmsPageFields, $aliasFields)
                ->deleted()
                ->where('chrMain', 'N')
                ->where('chrIsPreview', 'N')
                ->where('chrRollBack', 'Y')
                ->where('fkMainRecord', $id)
                ->orderBy('created_at', 'desc')
                ->get();
        return $response;
    }

    /**
     * This method handle to Approve record updated by user.
     * @author  Snehal
     */
    public static function approved_data_Listing($request) {
        $id = $request->id;
        $main_id = $request->main_id;
        // $PUserid = $request->PUserid;
        //Select Child Record Data Start
        $response = false;
        $cmsPageFields = ['id', 'intSearchRank', 'FavoriteID', 'chrPageActive', 'varPassword', 'intAliasId', 'intFKModuleCode', 'varTitle', 'varSector','chrArchive', 'txtDescription', 'dtDateTime', 'dtEndDateTime', 'varMetaTitle', 'varMetaDescription', 'chrPublish', 'chrDelete', 'created_at', 'UserID', 'chrApproved', 'fkMainRecord', 'chrDraft'];
        $aliasFields = ['id', 'varAlias'];
        $response = Self::getPowerPanelRecords($cmsPageFields, $aliasFields)
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
            'intFKModuleCode' => $response['intFKModuleCode'],
            'txtDescription' => $response['txtDescription'],
            'dtDateTime' => $response['dtDateTime'],
            'dtEndDateTime' => $response['dtEndDateTime'],
            'varMetaTitle' => $response['varMetaTitle'],
            'chrAddStar' => 'N',
            'chrDraft' => $response['chrDraft'],
            'chrArchive' => $response['chrArchive'],
            'intSearchRank' => $response['intSearchRank'],
            'FavoriteID' => $response['FavoriteID'],
            'chrPageActive' => $response['chrPageActive'],
            'varMetaDescription' => $response['varMetaDescription'],
            'chrPublish' => $response['chrPublish'],
        ];
        CommonModel::updateRecords($whereConditions, $updateMainRecord,false, 'Powerpanel\CmsPage\Models\CmsPage');
        //Update Copy Child Record To Main Record end
        $whereConditions_ApproveN = ['fkMainRecord' => $main_id];
        $updateToApproveN = [
            'chrApproved' => 'N',
            'intApprovedBy' => '0',
        ];
        CommonModel::updateRecords($whereConditions_ApproveN, $updateToApproveN,false, 'Powerpanel\CmsPage\Models\CmsPage');
        $whereConditionsApprove = ['id' => $id, 'chrMain' => 'N'];
        $updateToApprove = [
            'chrApproved' => 'Y',
            'chrRollBack' => 'Y',
            'intApprovedBy' => auth()->user()->id,
            'dtApprovedDateTime' => date('Y-m-d H:i:s')
        ];
        CommonModel::updateRecords($whereConditionsApprove, $updateToApprove,false, 'Powerpanel\CmsPage\Models\CmsPage');
        $msg_show = "Record successfully approved.";
        return $msg_show;
    }

    public function scopeDateRange($query) {
        $response = false;
        $response = $query->whereRaw('((dtEndDateTime >= NOW() AND NOW() >= dtDateTime) OR (NOW() >= dtDateTime and dtEndDateTime is null))');
        return $response;
    }
    public function scopeEndDateRange($query) {
        $response = false;
        $response = $query->whereRaw('((dtEndDateTime >= NOW()) OR (dtEndDateTime is null))');
        return $response;
    }

    public static function getPreviousRecordByMainId($id) {
        $response = Self::select('id','fkMainRecord')
                        ->where('fkMainRecord', $id)
                        ->where('chrMain', 'N')
                        ->where('chrApproved', 'N')
                        ->where('chrDelete','N')
                        ->where('dtApprovedDateTime','!=',NULL)
                        ->orderBy('dtApprovedDateTime','DESC')
                        ->first();
        return $response;
    }

    public static function sitemap(){
        $cmsPageFields = ['id', 'intAliasId', 'intFKModuleCode','varTitle','created_at','updated_at'];
        $aliasFields = ['id', 'varAlias', 'intFkModuleCode'];
        $moduleFields = ['id', 'varModuleName', 'varModuleClass'];
        $response = Self::getFrontPageRecord($cmsPageFields, $aliasFields, $moduleFields)
            ->deleted()
            ->publish()
            ->where('chrIsPreview','=','N')
            ->orderBy('varTitle')
            ->get();
        return $response;
    }

}
