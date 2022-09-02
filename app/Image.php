<?php

namespace App;

use Cache;
use DB;
use Illuminate\Database\Eloquent\Model;

class Image extends Model {

    protected $table = 'image';
    protected $fillable = [
        'fk_folder',
        'fkIntUserId',
        'txtImageName',
        'txtImgOriginalName',
        'varImageExtension',
        'chrIsUserUploaded',
        'chrPublish',
        'chrDelete',
    ];
    protected static $fetchedID = [];
    protected static $fetchedImg = null;

    // public static function getImg($imgId)
    // {
    //     $response=false;
    //     if($imgId != null){
    //         $imageFields=['id', 'txtImageName', 'varImageExtension'];
    //         $response = Self::getRecords($imageFields)
    //         ->checkRecordId($imgId)
    //         ->first();
    //     }
    //     return $response;
    // }
    #Old cached fucntion
    // public static function getImg($imgId)
    // {
    //     $response=false;
    //     if($imgId != null)
    //     {
    //         if(!in_array($imgId, Self::$fetchedID)){
    //             array_push(Self::$fetchedID, $imgId);
    //             $imageFields=['id', 'txtImageName', 'varImageExtension'];
    //             Self::$fetchedImg = Self::getRecords($imageFields)
    //             ->checkRecordId($imgId)
    //             ->first();
    //         }
    //     }
    //     $response=Self::$fetchedImg;
    //     return $response;
    // }

    public static function getImg($imgId) {
        $response = false;
        if ($imgId != null) {
            $imageFields = ['id', 'txtImageName', 'txtImgOriginalName', 'varImageExtension'];
            $response = Cache::rememberForever('img-' . $imgId, function () use ($imgId, $imageFields) {
                        return Self::getRecords($imageFields)
                                        ->checkRecordId($imgId)
                                        ->first();
                    });
        }
        return $response;
    }

    public static function getImages($limit, $page, $position = 0, $filter = false) {
        $response = false;
        $imageFields = ['id', 'txtImageName', 'varImageExtension', 'txtImgOriginalName', 'varfolder', 'fk_folder'];

        $fetchedImg = Self::select($imageFields)
                ->publish()
                ->deleted()
                ->skip($position)
                ->take($limit, $page);
        if (isset($filter['imageName']) && !empty($filter['imageName'])) {
            $fetchedImg = $fetchedImg->searchByName($filter['imageName']);
        }
        $fetchedImg = $fetchedImg->orderBy('id', 'DESC')
                ->get();

        $response = $fetchedImg;
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
        $ImageFields = ['id', 'varTitle', 'txtImageName', 'varImageExtension', 'txtImgOriginalName', 'varAltText', 'txtCaption', 'chrIsUserUploaded', 'chrPublish', 'created_at','fk_folder','varfolder'];
        $response = Self::select($ImageFields)
                ->checkRecordId($id)
                ->publish()
                ->deleted()
                ->first();

        return $response;
    }

    public static function getRecentUploadedImages() {
        $response = false;
        $imageFields = ['id', 'txtImageName', 'varImageExtension', 'txtImgOriginalName','fk_folder','varfolder'];
        $fetchedImg = Self::select($imageFields)
                ->publish()
                ->deleted()
                ->orderBy('id', 'DESC')
                ->take(10)
                ->get();

        $response = $fetchedImg;
        return $response;
    }

    public static function getTrashedImages() {
        $response = false;
        $imageFields = ['id', 'txtImageName', 'varImageExtension', 'txtImgOriginalName','fk_folder','varfolder'];
        $fetchedImg = Self::select($imageFields)
                ->deletedYes()
                ->take(15)
                ->get();

        $response = $fetchedImg;
        return $response;
    }

    public static function getAllTrashedImagesIds() {
        $response = false;
        $imageFields = ['id', 'txtImageName', 'varImageExtension','fk_folder','varfolder'];
        $fetchedImg = Self::select($imageFields)
                ->deletedYes()
                ->get();
        $response = $fetchedImg;
        return $response;
    }

    public static function getRecordCount($filter = false) {
        $response = false;
//        if ($filter['imageName'] != 0) {
            $moduleFields = ['id'];
            $moduleRecords = Self::select($moduleFields);
            if (isset($filter['imageName']) && $filter['imageName'] != "") {
                $moduleRecords = $moduleRecords->searchByName($filter['imageName']);
            }
            $response = $moduleRecords->deleted()->count();
//        }
        return $response;
    }
    public static function getFolderRecordCount($filter = false) {
        $response = false;
        if ($filter['imageName'] != 0) {
            $moduleFields = ['id'];
            $moduleRecords = Self::select($moduleFields);
            if (isset($filter['imageName']) && $filter['imageName'] != "") {
                $moduleRecords = $moduleRecords->searchByName($filter['imageName']);
            }
            $response = $moduleRecords->deleted()->count();
        }
        return $response;
    }

    /**
     * This method handels retrival of show records
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public static function getRecords($imageFields) {
        return Self::select($imageFields);
    }

    public function events() {
        return $this->hasOne('App\Events', 'id', 'fkIntImgId');
    }

    public function blogs() {
        return $this->hasOne('App\Blogs', 'id', 'fkIntImgId');
    }

    public function advertise() {
        return $this->hasOne('App\Advertise', 'id', 'intImgId');
    }

    public function banners() {
        return $this->hasOne('Powerpanel\Banner\Models\Banner', 'id', 'intImgId');
    }

    public function show() {
        return $this->hasOne('App\Show', 'id', 'intImgId');
    }

    public function team() {
        return $this->hasOne('App\Team', 'id', 'fkIntImgId');
    }

    public function client() {
        return $this->hasOne('App\Client', 'id', 'fkIntImgId');
    }

    public function sponsor() {
        return $this->hasOne('App\Sponsor', 'id', 'fkIntImgId');
    }


    public function folder() {
        return $this->hasOne('App\Folder', 'id', 'fk_folder');
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
     * This method handels search by image name query
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public function scopeSearchByName($query, $imageName = false) {
        if (!empty($imageName) && $imageName != false) {
            return $query->where('txtImgOriginalName', 'like', '%' . $imageName . '%');
        } else {
            return false;
        }
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
    
    
    public static function getFolderImage($id){
    $folderdata = DB::table('image')
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
