<?php
namespace Powerpanel\ContactInfo\Models;

use Illuminate\Database\Eloquent\Model;
use Cache;

class ContactInfo extends Model {

    protected $table = 'contact_info';
    protected $fillable = [
        'id',
        'varTitle',
        'varEmail',
        'varPhoneNo',
        'varFax',
        'txtDescription',
        'intDisplayOrder',
        'fkIntImgId',
        'txtAddress',
        'mailingaddress',
        'varOpeningHours',
        'chrLatitude',
        'chrLongitude',
        'chrIsPrimary',
        'chrPublish',
        'chrDelete',
        'created_at',
        'updated_at',
    ];

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

    /**
     * This method handels retrival of record count
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getContactDetails() {
        $response = false;
        $response = Cache::tags(['ContactInfo'])->get('getFrontContactDetails');
        if (empty($response)) {
            $moduleFields = ['id', 'varTitle', 'varPhoneNo', 'varEmail', 'varFax','txtDescription', 'intDisplayOrder', 'fkIntImgId', 'mailingaddress', 'txtAddress', 'chrIsPrimary', 'chrPublish'];
            $response = Self::select($moduleFields)->deleted()->publish()->get();
            Cache::tags(['ContactInfo'])->forever('getFrontContactDetails', $response);
        }
        return $response;
    }

    public static function getFrontContactDetails($primary = 'N') {
        $response = false;
        if (empty($response)) {
            $moduleFields = ['id', 'varTitle', 'varPhoneNo', 'varEmail', 'varFax','txtDescription', 'intDisplayOrder', 'fkIntImgId', 'mailingaddress', 'txtAddress', 'chrIsPrimary', 'chrPublish'];
            $response = Self::select($moduleFields)->where('chrIsPrimary',$primary)->deleted()->publish()->get();
        }
        return $response;
    }

    /**
     * This method handels retrival of record count based on category
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getCountById($categoryId = null) {
        $response = false;
        $moduleFields = ['id'];
        $response = Self::getPowerPanelRecords($moduleFields)
                ->checkCategoryId($categoryId)
                ->deleted()
                ->count();
        return $response;
    }

    /**
     * This method handels retrival of record count
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getRecordList($filterArr = false) {
        $response = false;
        $moduleFields = [
            'id',
            'chrPublish',
            'intDisplayOrder',
            'fkIntImgId',
            'txtAddress',
            'mailingaddress',
            'chrIsPrimary',
            'varTitle',
            'varEmail',
            'created_at',
        ];
        $response = Self::getPowerPanelRecords($moduleFields)
                ->deleted()
                ->filter($filterArr)
                ->get();
        return $response;
    }

    /**
     * This method handels retrival of record count
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getRecordById($id = false) {
        $response = false;
        $moduleFields = [
            'id',
            'varTitle',
            'varEmail',
            'varPhoneNo',
            'varFax',
            'txtDescription',
            'intDisplayOrder',
            'fkIntImgId',
            'txtAddress',
            'mailingaddress',
            'chrIsPrimary',
            'chrPublish',
            'varLatitude',
            'varLongitude',
            'txtAddress',
        ];
        $response = Self::getPowerPanelRecords($moduleFields)
                ->deleted()
                ->checkRecordId($id)
                ->first();
        return $response;
    }

    /**
     * This method handels retrival of record for notification
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getRecordNotify($id = false) {
        $response = false;
        $imageFields = false;
        $moduleFields = ['varTitle'];
        $response = Self::getPowerPanelRecords($moduleFields)
                ->deleted()
                ->checkRecordId($id)
                ->first();
        return $response;
    }
    
    public static function getContactList()
		{
				$response     = false;
				$moduleFields = ['id', 'varTitle', 'varPhoneNo','varEmail','txtAddress'];
				$response = Self::select($moduleFields)->deleted()->publish()->get();
				return $response;
		}

    #Database Configurations========================================

    /**
     * This method handels retrival of blog records
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public static function getPowerPanelRecords($moduleFields = false) {
        $data = [];
        $response = false;
        $response = self::select($moduleFields);
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
        $moduleFields = ['*'];
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
                    ->first();
        }
        $response = Self::$fetchedOrderObj;
        return $response;
    }

    /**
     * This method handels retrival of event records
     * @return  Object
     * @since   2017-08-02
     * @author  NetQuick
     */
    public static function getRecords() {
        return self::with([]);
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
     * @since   2017-08-02
     * @author  NetQuick
     */
    public function scopePublish($query) {
        return $query->where(['chrPublish' => 'Y']);
    }

    /**
     * This method handels delete scope
     * @return  Object
     * @since   2017-08-02
     * @author  NetQuick
     */
    public function scopeDeleted($query) {
        return $query->where(['chrDelete' => 'N']);
    }

    /**
     * This method handels primary/secodary scope
     * @return  Object
     * @since   2017-08-09
     * @author  NetQuick
     */
    public function scopeContactPrimary($query, $type = 'Y') {
        return $query->where('chrIsPrimary', $type);
    }

    /**
     * This method handels order scope
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public function scopeOrderCheck($query, $order) {
        $response = false;
        $response = $query->where('intDisplayOrder', $order);
        return $response;
    }

    /**
     * This method handels filter scope
     * @return  Object
     * @since   2017-08-02
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
        if (!empty($filterArr['searchFilter']) && $filterArr['searchFilter'] != ' ') {
            $data = $query->whereRaw('varTitle LIKE "%' . $filterArr['searchFilter'] . '%" or varEmail LIKE "%' . $filterArr['searchFilter'] . '%"');
        }
        if (!empty($query)) {
            $response = $query;
        }
        return $response;
    }

    public static function Primary_ContactInfo() {
        $response = false;
        $response = Self::select('*')->where('chrIsPrimary',"=",'Y')->first();
        return $response;
    }

}
