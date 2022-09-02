<?php
/**
 * The PhotoGallery class handels bannner queries
 * ORM implemetation.
 * @package   Netquick powerpanel
 * @license   http://www.opensource.org/licenses/BSD-3-Clause
 * @version   1.1
 * @since       2017-07-20
 * @author    NetQuick
 */
namespace Powerpanel\PhotoGallery\Models;
use Illuminate\Database\Eloquent\Model;
use Cache;
use App\CommonModel;
use DB;

class PhotoGallery extends Model {
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'photo_gallery';
    protected $fillable = [
        'id',
        'fkMainRecord',
        'varTitle',
        'fkIntImgId',
        'intPhotoAlbumId',
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
        'dtDateTime',
        'dtEndDateTime',
        'intSearchRank',
        'chrDraft',
        'chrTrash',
        'FavoriteID',
        'LockUserID','chrLock',
        'created_at',
        'updated_at'
    ];
    /**
     * This method handels retrival of photogallery records
     * @return  Object
     * @since   2016-07-20
     * @author  NetQuick
     */
    public static function getRecords() {
        $response = false;
        $response = Cache::tags(['PhotoGallery'])->get('getPhotoGalleryRecords');
        if (empty($response)) {
            $response = Self::Select(['id', 'varTitle', 'fkIntImgId', 'intPhotoAlbumId', 'intDisplayOrder', 'chrPublish'])
                    ->deleted()
                    ->publish()
                    ->paginate(10);
            Cache::tags(['PhotoGallery'])->forever('getPhotoGalleryRecords', $response);
        }
        return $response;
    }
    /**
     * This method handels backend records
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public static function getPowerPanelRecords($moduleFields = false, $photoAlbumFields = false) {
        $data = [];
        $response = false;

        $data['child'] = function ($query) {
            $query->select(['id','varTitle','fkMainRecord'])
                    ->where('chrDelete','N')
                    ->where('dtApprovedDateTime','!=',NULL);
        };

        $response = self::select($moduleFields);
        if ($photoAlbumFields != false) {
            $data['photoAlbum'] = function ($query) use ($photoAlbumFields) {
                $query->select($photoAlbumFields);
            };
        }
        if (count($data) > 0) {
            $response = $response->with($data);
        }
        return $response;
    }


    public function child() {
		return $this->hasMany('Powerpanel\PhotoGallery\Models\PhotoGallery', 'fkMainRecord', 'id');
	}


    public static function getRecordList($filterArr = false, $isAdmin = false, $ignoreId = array(), $userRoleSector = false) {
        $response = false;
        $teamcatfileds = ['id', 'varTitle'];
        $moduleFields = [
            'id',
            'varTitle',
            'fkIntImgId',
            'intPhotoAlbumId',
            'intDisplayOrder',
            'chrPublish',
            'chrMain',
            'dtDateTime',
            'dtEndDateTime',
            'intSearchRank',
            'chrAddStar',
            'chrDraft',
            'chrTrash',
            'FavoriteID',
            'LockUserID','chrLock',
            'created_at',
            'updated_at'
        ];
        $response = Self::getPowerPanelRecords($moduleFields, $teamcatfileds)
                ->deleted()
                ->whereNotIn('id', $ignoreId);
        $response = $response->filter($filterArr)
                ->where('chrTrash', '!=', 'Y')
                ->checkMainRecord('Y')
                ->get();
        return $response;
    }

    public static function getRecordList_tab1($filterArr = false, $isAdmin = false, $ignoreId = array(), $userRoleSector) {
        $response = false;
        $moduleFields = [
            'id',
            'varTitle',
            'fkIntImgId',
            'intPhotoAlbumId',
            'intDisplayOrder',
            'chrPublish',
            'fkMainRecord',
            'chrAddStar',
            'dtDateTime',
            'dtEndDateTime',
            'intSearchRank',
            'chrDraft',
            'chrTrash',
            'FavoriteID',
            'LockUserID','chrLock',
            'created_at',
            'updated_at'
        ];
        $MainIDs = Self::distinct()
                ->select("fkMainRecord")
                ->checkMainRecord('N')
                ->groupBy('fkMainRecord')
                ->deleted()
                ->get()
                ->toArray();
        $MainIDs = array_column($MainIDs, 'fkMainRecord');
        $photoAlbumFields = ['id', 'varTitle'];
        $response = Self::getPowerPanelRecords($moduleFields, $photoAlbumFields)
                ->deleted()
                ->whereNotIn('id', $ignoreId);
                $response = $response->where('chrAddStar', 'Y')
                ->filter($filterArr)
                ->whereIn('id', $MainIDs)
                ->where('chrTrash', '!=', 'Y')
                ->checkStarRecord('Y')
                ->get();
        return $response;
    }

    public static function getRecordListFavorite($filterArr = false, $isAdmin = false, $ignoreId = array(), $userRoleSector) {
        $response = false;
        $teamcatfileds = ['id', 'varTitle'];
         $userid = auth()->user()->id;
        $moduleFields = [
            'id',
            'varTitle',
            'fkIntImgId',
            'intPhotoAlbumId',
            'intDisplayOrder',
            'chrPublish',
            'chrMain',
            'dtDateTime',
            'dtEndDateTime',
            'intSearchRank',
            'chrAddStar',
            'chrDraft',
            'chrTrash',
            'FavoriteID',
            'LockUserID','chrLock',
            'created_at',
            'updated_at'
        ];
        $response = Self::getPowerPanelRecords($moduleFields, $teamcatfileds)
                ->deleted()
                ->whereNotIn('id', $ignoreId);
        $response = $response->filter($filterArr)
                ->checkMainRecord('Y')
                ->where('chrTrash', '!=', 'Y')
                ->whereRaw("find_in_set($userid,FavoriteID)")
                ->get();
        return $response;
    }

    public static function getRecordListDraft($filterArr = false, $isAdmin = false, $ignoreId = array(), $userRoleSector) {
        $response = false;
        $teamcatfileds = ['id', 'varTitle'];
        $moduleFields = [
            'id',
            'varTitle',
            'fkIntImgId',
            'intPhotoAlbumId',
            'intDisplayOrder',
            'chrPublish',
            'chrMain',
            'dtDateTime',
            'dtEndDateTime',
            'intSearchRank',
            'chrAddStar',
            'chrDraft',
            'chrTrash',
            'FavoriteID',
            'LockUserID','chrLock',
            'created_at',
            'updated_at'
        ];
        $response = Self::getPowerPanelRecords($moduleFields, $teamcatfileds)
                ->deleted()
                ->whereNotIn('id', $ignoreId);
        $response = $response->filter($filterArr)
                ->checkMainRecord('Y')
                ->where('chrDraft', 'D')
                ->where('chrTrash', '!=', 'Y')
                ->get();
        return $response;
    }

    public static function getRecordListTrash($filterArr = false, $isAdmin = false, $ignoreId = array(), $userRoleSector) {
        $response = false;
        $teamcatfileds = ['id', 'varTitle'];
        $moduleFields = [
            'id',
            'varTitle',
            'fkIntImgId',
            'intPhotoAlbumId',
            'intDisplayOrder',
            'chrPublish',
            'chrMain',
            'dtDateTime',
            'dtEndDateTime',
            'intSearchRank',
            'chrAddStar',
            'chrDraft',
            'chrTrash',
            'FavoriteID',
            'LockUserID','chrLock',
            'created_at',
            'updated_at'
        ];
        $response = Self::getPowerPanelRecords($moduleFields, $teamcatfileds)
                ->deleted()
                ->whereNotIn('id', $ignoreId);
        $response = $response->filter($filterArr)
                ->checkMainRecord('Y')
                ->where('chrTrash', 'Y')
                ->get();
        return $response;
    }


    public static function getRecordCountforList($filterArr = false, $returnCounter = false, $isAdmin = false, $ignoreId = array(), $userRoleSector) {
        $response = 0;
        $cmsPageFields = ['id'];
        $response = Self::getPowerPanelRecords($cmsPageFields);
        if ($filterArr != false) {
            $response = $response->filter($filterArr, $returnCounter);
        }
        // if(!$isAdmin){
        //     $response = $response->where('varSector', $userRoleSector);
        // }
        $response = $response->deleted()
                ->where('chrMain', 'Y')
                ->whereNotIn('id', $ignoreId)
                ->where('chrTrash', '!=', 'Y')
                ->count();
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
                ->deleted()
                ->whereNotIn('id', $ignoreId);
                if ($filterArr != false) {
                    $response = $response->filter($filterArr, $returnCounter);
                }
                // if(!$isAdmin){
                //     $response = $response->where('varSector', $userRoleSector);
                // }
                $response = $response->whereIn('id', $MainIDs)
                ->checkStarRecord('Y')
                ->where('chrTrash', '!=', 'Y')
                ->count();
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
                ->whereNotIn('id', $ignoreId);
                // if(!$isAdmin){
                //     $response = $response->where('varSector', $userRoleSector);
                // }
                $response = $response->where('chrDraft', 'D')
                ->where('chrTrash', '!=', 'Y')
                ->count();
        return $response;
    }

    public static function getRecordCountforListTrash($filterArr = false, $returnCounter = false, $isAdmin = false, $ignoreId = array(), $userRoleSector) {
        $response = 0;
        $cmsPageFields = ['id'];
        $userid = auth()->user()->id;
        $response = Self::getPowerPanelRecords($cmsPageFields);
        if ($filterArr != false) {
            $response = $response->filter($filterArr, $returnCounter);
        }
        $response = $response->deleted()
        ->whereNotIn('id', $ignoreId);
                // if(!$isAdmin){
                //     $response = $response->where('varSector', $userRoleSector);
                // }
                $response = $response->checkMainRecord('Y')
                ->whereNotIn('id', $ignoreId)
                ->where('chrTrash', 'Y')
                ->count();
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
                ->whereNotIn('id', $ignoreId);
                // if(!$isAdmin){
                //     $response = $response->where('varSector', $userRoleSector);
                // }
                $response = $response->where('chrTrash', '!=', 'Y')
                ->whereNotIn('id', $ignoreId)
                ->whereRaw("find_in_set($userid,FavoriteID)")
                ->count();
        return $response;
    }



    public static function getRecordById($id, $ignoreDeleteScope = false) {
        $response = false;
        $moduleFields = [
            'id',
            'varTitle',
            'fkIntImgId',
            'intPhotoAlbumId',
            'intDisplayOrder',
            'chrPublish',
            'fkMainRecord',
            'UserID',
            'dtDateTime',
            'dtEndDateTime',
            'intSearchRank',
            'chrDraft',
            'chrTrash',
            'FavoriteID',
            'LockUserID','chrLock',
            'created_at',
            'updated_at'
        ];
        $response = Self::getPowerPanelRecords($moduleFields);
        if (!$ignoreDeleteScope) {
            $response = $response->deleted();
        }
        $response = $response->checkRecordId($id)->first();
        return $response;
    }
    /**
     * This method handels retrival of front Product list
     * @return  Object
     * @since   2017-10-14
     * @author  NetQuick
     */
    public static function getFrontList($page = false, $limit = 10) {
        $response = false;
        $moduleFields = [
            'id',
            'varTitle',
            'fkIntImgId',
            'txtDescription'
        ];
        $response = Cache::tags(['PhotoGallery'])->get('getFrontPhotoGalleryList_' . $page);
        if (empty($response)) {
            $response = Self::getFrontRecords($moduleFields)
                    ->deleted()
                    ->publish()
                    ->where('chrMain', 'Y')
                    ->orderBy('intDisplayOrder', 'asc')
                    ->paginate($limit);
            Cache::tags(['PhotoGallery'])->forever('getFrontPhotoGalleryList_' . $page, $response);
        }
        return $response;
    }
    public static function getFrontRecords($moduleFields = false, $aliasFields = false) {
        $response = false;
        $data = [];
        $response = self::select($moduleFields)->with($data);
        return $response;
    }
    /**
     * This method handels retrival of front Product list
     * @return  Object
     * @since   2017-10-14
     * @author  NetQuick
     */
    public static function getFrontListByalbumId($albumId = false, $page = false, $limit = 10) {
        $response = false;
        $moduleFields = [
            'id',
            'varTitle',
            'fkIntImgId',
            'txtDescription'
        ];
        $response = Cache::tags(['PhotoGallery'])->get('getFrontPhotoGalleryList_' . $page . '_' . $albumId);
        if (empty($response)) {
            $response = Self::select($moduleFields)
                    ->deleted()
                    ->publish()
                    ->where('chrMain', 'Y')
                    ->where('chrTrash', '!=', 'Y')
                ->where('chrDraft', '!=', 'D')
                    ->where('intPhotoAlbumId', $albumId)
                    ->orderBy('intDisplayOrder', 'asc')
                    ->paginate($limit);
            Cache::tags(['PhotoGallery'])->forever('getFrontPhotoGalleryList_' . $page . '_' . $albumId, $response);
        }
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
            'varTitle',
            'fkIntImgId',
            'intPhotoAlbumId',
            'intDisplayOrder',
            'chrPublish',
            'fkMainRecord',
            'UserID',
            'dtDateTime',
            'dtEndDateTime',
            'intSearchRank',
            'chrDraft',
            'chrTrash',
            'FavoriteID',
            'LockUserID','chrLock',
            'created_at',
            'updated_at'
        ];
        $response = Self::getPowerPanelRecords($moduleFields)->deleted()->checkRecordId($id)->first();
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
     * This method handels image relation
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public function photoAlbum() {
        return $this->belongsTo('Powerpanel\PhotoAlbum\Models\PhotoAlbum', 'intPhotoAlbumId', 'id');
    }
    /**
     * This method handels filter scope
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public function scopeFilter($query, $filterArr = false, $retunTotalRecords = false) {
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
        if (!empty($filterArr['albumFilter']) && $filterArr['albumFilter'] != ' ') {
            $data = $query->where('intPhotoAlbumId', $filterArr['albumFilter']);
        }
        if (!empty($filterArr['searchFilter']) && $filterArr['searchFilter'] != ' ') {
            $data = $query->where('varTitle', 'like', "%" . $filterArr['searchFilter'] . "%");
        }
        if (!empty($query)) {
            $response = $query;
        }
        return $response;
    }
    public static function getRecordCount($filterArr = false, $returnCounter = false) {
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

    public static function getRecordCountForDorder($filterArr = false, $returnCounter = false) {
        $response = 0;
        $cmsPageFields = ['id'];
        $pageQuery = Self::getPowerPanelRecords($cmsPageFields);
        if ($filterArr != false) {
            $pageQuery = $pageQuery->filter($filterArr, $returnCounter);
        }
        $response = $pageQuery
                ->deleted()
                ->where('chrMain', 'Y')
                ->where('chrTrash','N')
                ->count();
        return $response;
    }

    public static function getPhotoCountByAlbumId($albumId = 0) {
        $response = 0;
        $moduleFields = ['id'];
        $response = Self::getPowerPanelRecords($moduleFields);
        $response = $response
                ->deleted()
                ->where('chrMain', 'Y')
                ->where('intPhotoAlbumId', $albumId)
                ->count();
        return $response;
    }


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
    }
    public static function getChildGrid() {
        $id = $_REQUEST['id'];
        $response = false;
        $moduleFields = ['id', 'varTitle', 'intPhotoAlbumId', 'intDisplayOrder', 'chrPublish', 'fkMainRecord', 'created_at', 'chrApproved', 'updated_at', 'intApprovedBy', 'UserID', 'intSearchRank',
            'chrDraft',
            'chrTrash',
            'FavoriteID',
            'created_at',
            'updated_at'];
        $response = Self::getPowerPanelRecords($moduleFields)->deleted()->where('chrMain', 'N')->where('fkMainRecord', $id)->orderBy('created_at', 'desc')->get();
        return $response;
    }
    public static function getChildrollbackGrid() {
        $id = $_REQUEST['id'];
        $response = false;
        $moduleFields = ['id', 'varTitle', 'intPhotoAlbumId', 'intDisplayOrder', 'chrPublish', 'fkMainRecord', 'created_at', 'chrApproved', 'updated_at', 'intApprovedBy', 'UserID'];
        $response = Self::getPowerPanelRecords($moduleFields)->deleted()->where('chrMain', 'N')->where('chrRollBack', 'Y')->where('fkMainRecord', $id)->orderBy('created_at', 'desc')->get();
        return $response;
    }
    public static function approved_data_Listing($request) {
        $id = $request->id;
        $main_id = $request->main_id;
        $PUserid = $request->PUserid;
        //Select Child Record Data Start
        $response = false;
        $moduleFields = [
            'id',
            'varTitle',
            'fkIntImgId',
            'intPhotoAlbumId',
            'intDisplayOrder',
            'chrPublish',
            'dtDateTime',
            'dtEndDateTime',
            'intSearchRank',
            'chrDraft',
            'chrTrash',
            'FavoriteID',
            'created_at',
            'updated_at'
        ];
        $aliasFields = ['id', 'varAlias'];
        $response = Self::getPowerPanelRecords($moduleFields)->deleted()->where('chrMain', 'N')->where('id', $id)->orderBy('created_at', 'desc')->first();
        //Select Child Record Data END
        //Update Copy Child Record To Main Record start
        $whereConditions = ['id' => $main_id];
        $updateMainRecord = [
            'varTitle' => $response['varTitle'],
            'dtDateTime' => $response['dtDateTime'],
            'dtEndDateTime' => $response['dtEndDateTime'],
            'intPhotoAlbumId' => $response['intPhotoAlbumId'],
            'fkIntImgId' => $response['fkIntImgId'],
            'chrAddStar' => 'N',
            'chrDraft' => $response['chrDraft'],
            'intSearchRank' => $response['intSearchRank'],
            'FavoriteID' => $response['FavoriteID'],
            'chrPublish' => $response['chrPublish'],
        ];
        CommonModel::updateRecords($whereConditions, $updateMainRecord,false,'Powerpanel\PhotoGallery\Models\PhotoGallery');
        //Update Copy Child Record To Main Record end
        $whereConditions_ApproveN = ['fkMainRecord' => $main_id];
        $updateToApproveN = [
            'chrApproved' => 'N',
            'chrLetest' => 'N',
            'intApprovedBy' => '0',
        ];
        CommonModel::updateRecords($whereConditions_ApproveN, $updateToApproveN,false,'Powerpanel\PhotoGallery\Models\PhotoGallery');
        $whereConditionsApprove = ['id' => $id, 'chrMain' => 'N'];
        $updateToApprove = [
            'chrApproved' => 'Y',
            'chrRollBack' => 'Y',
            'intApprovedBy' => auth()->user()->id
        ];
        CommonModel::updateRecords($whereConditionsApprove, $updateToApprove,false,'Powerpanel\PhotoGallery\Models\PhotoGallery');
        $msg_show = "Record successfully approved.";
        return $msg_show;
    }
    public static function getCountById($categoryId = null) {
        $response = false;
        $moduleFields = ['id'];
        $response = Self::getPowerPanelRecords($moduleFields)
                ->deleted()
                ->where('chrMain', 'Y')
                ->count();
        return $response;
    }
    public function scopeDateRange($query) {
        $response = false;
        $response = $query->whereRaw('((dtEndDateTime >= NOW() AND NOW() >= dtDateTime) OR (NOW() >= dtDateTime and dtEndDateTime is null))');
        return $response;
    }


}
