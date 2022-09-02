<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;

class Document extends Model {

    protected $table = 'documents';
    protected $fillable = [
        'id',
        'fkIntUserId',
        'txtDocumentName',
        'txtSrcDocumentName',
        'varDocumentExtension',
        'chrIsUserUploaded',
        'varfolder', 'fk_folder',
        'chrPublish',
        'chrDelete',
    ];

    public static function getDocuments($limit, $page, $position = 0, $filter = false) {
        $response = false;
        $documentFields = ['id', 'txtDocumentName', 'txtSrcDocumentName', 'varDocumentExtension', 'intMobileViewCount', 'intDesktopViewCount', 'intMobileDownloadCount', 'intDesktopDownloadCount', 'chrPublish', 'varfolder', 'fk_folder'];
        $response = Self::select($documentFields)
                ->publish()
                ->deleted()
                ->skip($position);
        if (isset($filter['docName'])) {
            $response = $response->searchByName($filter['docName']);
        }
        $response = $response->take($limit, $page)
                ->orderBy('id', 'DESC')
                ->get();
        return $response;
    }

    public static function getDocumentCounters($year = 4, $timeparam = 'year') {
        $response = false;
        $documentFields = [
            DB::raw('SUM( intMobileViewCount ) AS mobileViewCount'),
            DB::raw('SUM( intMobileDownloadCount ) AS mobileDownloadCount'),
            DB::raw('SUM( intDesktopViewCount ) AS desktopViewCount'),
            DB::raw('SUM( intDesktopDownloadCount ) AS desktopDownloadCount')
        ];

        if ($timeparam == 'year') {
            $documentFields[] = DB::raw('YEAR(created_at) as Year');
            $response = Self::select($documentFields)
                            ->publish()
                            ->deleted()
                            ->whereRaw("YEAR(created_at) > YEAR( (DATE_SUB( CURDATE() , INTERVAL " . (int) $year . " YEAR ) ) )")
                            ->orderBy('created_at', 'DESC')
                            ->groupBy(DB::raw('YEAR(created_at)'))->get();
        } elseif ($timeparam == 'month') {
            $documentFields[] = DB::raw('MONTHNAME(created_at) as Year');
            $response = Self::select($documentFields)
                            ->publish()
                            ->deleted()
                            ->whereRaw("MONTH(created_at) > MONTH( (DATE_SUB( CURDATE() , INTERVAL " . (int) $year . " MONTH ) ) )")
                            ->orderBy('created_at', 'DESC')
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
        $documentFields = ['id', 'txtDocumentName', 'txtSrcDocumentName', 'varDocumentExtension', 'chrIsUserUploaded', 'chrPublish', 'varfolder', 'fk_folder'];
        $response = Self::select($documentFields)->checkRecordId($id)->publish()->deleted()->first();
        return $response;
    }

    public static function getRecentUploadedImages() {
        $response = false;
        $documentFields = ['id', 'txtDocumentName', 'txtSrcDocumentName', 'varDocumentExtension', 'varfolder', 'fk_folder'];
        $response = Self::select($documentFields)
                ->publish()
                ->deleted()
                ->orderBy('id', 'DESC')
                ->take(10)
                ->get();

        return $response;
    }

    public static function getTrashedDocuments() {
        $response = false;
        $documentFields = ['id', 'txtDocumentName', 'txtSrcDocumentName', 'varDocumentExtension', 'varfolder', 'fk_folder'];
        $fetchedDoc = Self::select($documentFields)
                ->deletedYes()
                ->orderBy('updated_at', 'desc')
                ->take(15)
                ->get();

        $response = $fetchedDoc;
        return $response;
    }

    public static function getAllTrashedDocumentsIds() {
        $response = false;
        $documentFields = ['id', 'txtDocumentName', 'txtSrcDocumentName', 'varDocumentExtension', 'varfolder', 'fk_folder'];
        $fetchedDocs = Self::select($documentFields)
                ->deletedYes()
                ->get();

        $response = $fetchedDocs;
        return $response;
    }

    public static function getRecordCount($filter = false) {
        $response = false;
        $moduleFields = ['id'];
        $moduleRecords = Self::select($moduleFields);
        if (isset($filter['docName'])) {
            $moduleRecords = $moduleRecords->searchByName($filter['docName']);
        }
        $response = $moduleRecords->deleted()->count();
        return $response;
    }

    public static function getFolderRecordCount($filter = false) {
        $response = false;
            $moduleFields = ['id'];
            $moduleRecords = Self::select($moduleFields);
            if (isset($filter['docName'])) {
                $moduleRecords = $moduleRecords->searchByName($filter['docName']);
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
    public function scopeSearchByName($query, $docName = false) {
        if (!empty($docName) && $docName != false) {
            return $query->where('txtDocumentName', 'like', '' . $docName . '%');
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
        $moduleFields = ['id', 'fkIntUserId', 'txtDocumentName', 'txtSrcDocumentName', 'varDocumentExtension', 'chrIsUserUploaded'];
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
            'varfolder', 'fk_folder',
            'txtDocumentName',
            'txtSrcDocumentName',
            'varDocumentExtension',
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

    public static function getFolderImage($id) {
        $folderdata = DB::table('documents')
                ->select('*')
                ->where('id', '=', $id)
                ->where('chrPublish', '=', 'Y')
                ->where('chrDelete', '=', 'N')
                ->first();
        return $folderdata;
    }

    public static function getFolderName($id) {
        $folderdata = DB::table('folder')
                ->select('*')
                ->where('id', '=', $id)
                ->first();
        return $folderdata;
    }

    public static function getFolderType($id) {
        $folderdata = DB::table('folder')
                ->select('*')
                ->where('type', '=', $id)
                ->get();
        return $folderdata;
    }

}
