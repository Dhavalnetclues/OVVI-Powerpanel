<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;

class Audio extends Model {

    protected $table = 'audios';
    protected $fillable = [
        'id',
        'fkIntUserId',
        'fk_folder','varfolder',
        'txtAudioName',
        'txtSrcAudioName',
        'varAudioExtension',
        'chrIsUserUploaded',
        'chrPublish',
        'chrDelete',
    ];

    public static function getAudios($limit, $page, $position = 0, $filter = false) {
        $response = false;
        $audioFields = ['id', 'txtAudioName','fk_folder','varfolder', 'txtSrcAudioName', 'varAudioExtension', 'intMobileViewCount', 'intDesktopViewCount', 'intMobileDownloadCount', 'intDesktopDownloadCount', 'chrPublish'];
        $response = Self::select($audioFields)
                ->publish()
                ->deleted()
                ->skip($position);
        if (isset($filter['audioName'])) {
            $response = $response->searchByName($filter['audioName']);
        }
        $response = $response->take($limit, $page)
                ->orderBy('id', 'DESC')
                ->get();
        return $response;
    }

    public static function getAudioCounters($year = 4, $timeparam = 'year') {
        $response = false;
        $audioFields = [
            DB::raw('SUM( intMobileViewCount ) AS mobileViewCount'),
            DB::raw('SUM( intMobileDownloadCount ) AS mobileDownloadCount'),
            DB::raw('SUM( intDesktopViewCount ) AS desktopViewCount'),
            DB::raw('SUM( intDesktopDownloadCount ) AS desktopDownloadCount')
        ];

        if ($timeparam == 'year') {
            $audioFields[] = DB::raw('YEAR(created_at) as Year');
            $response = Self::select($audioFields)
                            ->publish()
                            ->deleted()
                            ->whereRaw("YEAR(created_at) > YEAR( (DATE_SUB( CURDATE() , INTERVAL " . (int) $year . " YEAR ) ) )")
                            ->orderBy('created_at','DESC')
                            ->groupBy(DB::raw('YEAR(created_at)'))->get();
        } elseif ($timeparam == 'month') {
            $audioFields[] = DB::raw('MONTHNAME(created_at) as Year');
            $response = Self::select($audioFields)
                            ->publish()
                            ->deleted()
                            ->whereRaw("MONTH(created_at) > MONTH( (DATE_SUB( CURDATE() , INTERVAL " . (int) $year . " MONTH ) ) )")
                            ->orderBy('created_at','DESC')
                            ->groupBy(DB::raw('MONTH(created_at)'))->get();
        }
        return $response;
    }

    /**
     * This method handels retrival of record count
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getRecordById($id) {
        $response = false;
        $audioFields = ['id','fk_folder','varfolder', 'txtAudioName', 'txtSrcAudioName', 'varAudioExtension', 'chrIsUserUploaded', 'chrPublish'];
        $response = Self::select($audioFields)->checkRecordId($id)->publish()->deleted()->first();
        return $response;
    }

    public static function getRecentUploadedImages() {
        $response = false;
        $audioFields = ['id','fk_folder','varfolder', 'txtAudioName', 'txtSrcAudioName', 'varAudioExtension'];
        $response = Self::select($audioFields)
                ->publish()
                ->deleted()
                ->orderBy('id', 'DESC')
                ->take(10)
                ->get();

        return $response;
    }

    public static function getTrashedAudios() {
        $response = false;
        $audioFields = ['id','fk_folder','varfolder', 'txtAudioName', 'txtSrcAudioName', 'varAudioExtension'];
        $fetchedDoc = Self::select($audioFields)
                ->deletedYes()
                ->orderBy('updated_at','desc')
                ->take(15)
                ->get();

        $response = $fetchedDoc;
        return $response;
    }

    public static function getAllTrashedAudiosIds() {
        $response = false;
        $audioFields = ['id','fk_folder','varfolder', 'txtAudioName', 'txtSrcAudioName', 'varAudioExtension'];
        $fetchedDocs = Self::select($audioFields)
                ->deletedYes()
                ->get();

        $response = $fetchedDocs;
        return $response;
    }

    public static function getRecordCount($filter = false) {
        $response = false;
        $moduleFields = ['id'];
        $moduleRecords = Self::select($moduleFields);
        if (isset($filter['audioName'])) {
            $moduleRecords = $moduleRecords->searchByName($filter['audioName']);
        }
        $response = $moduleRecords->deleted()->count();
        return $response;
    }

    /**
     * This method handels search by image name query
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public function scopeSearchByName($query, $audioName = false) {
        if (!empty($audioName) && $audioName != false) {
            return $query->where('txtAudioName', 'like', '' . $audioName . '%');
        } else {
            return false;
        }
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
     * This method handels publish scope
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public function scopePublish($query) {
        return $query->where('chrPublish', 'Y');
    }

    /**
     * This method handels delete scope
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public function scopeDeleted($query) {
        return $query->where('chrDelete', 'N');
    }

    /**
     * This method handels delete scope
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public function scopeDeletedYes($query) {
        return $query->where(['chrDelete' => 'Y']);
    }

    /**
     * This method handle order by query
     * @return  Object
     * @since   2017-08-02
     * @author  NetQuick
     */
    public function scopeOrderByDesc($query) {
        return $query->orderBy('id', 'DESC');
    }

    /**
     * This method handels retrival of page title by page id
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getDocDataByIds($ids = false) {

        $response = false;
        $moduleFields = ['id', 'fkIntUserId', 'txtAudioName', 'txtSrcAudioName', 'varAudioExtension', 'chrIsUserUploaded'];
        $response = Self::select($moduleFields)
                ->deleted()
                ->whereIn('id', $ids)
                ->get();

        return $response;
    }

    /**
     * This method handels retrival of page title by page id
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getDocDataForHitsById($id = false) {

        $response = false;
        $moduleFields = [
            'id',
            'fkIntUserId',
            'txtAudioName',
            'txtSrcAudioName',
            'varAudioExtension',
            'chrIsUserUploaded',
            'intMobileViewCount',
            'intDesktopViewCount',
            'intMobileDownloadCount',
            'intDesktopDownloadCount'
        ];
        $response = Self::select($moduleFields)
                ->deleted()
                ->where('id', $id)
                ->first();

        return $response;
    }
    
    
    public static function getFolderImage($id){
    $folderdata = DB::table('audios')
                ->select('*')
                ->where('id', '=', $id)
             ->where('chrPublish','=','Y')
                ->where('chrDelete','=','N')
                ->first();
    return $folderdata;
    }
    
    public static function getFolderName($id){
    $folderdata = DB::table('folder')
                ->select('*')
                ->where('id', '=', $id)
                ->first();
    return $folderdata;
    }
    
    public static function getFolderType($id){
    $folderdata = DB::table('folder')
                ->select('*')
                ->where('type', '=', $id)
                ->get();
    return $folderdata;
    }

}
