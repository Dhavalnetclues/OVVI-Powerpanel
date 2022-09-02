<?php

namespace Powerpanel\TicketList\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class SubmitTickets extends Model {

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ticket_master';
    protected $fillable = [
        'id',
        'varTitle',
        'intType',
        'varImage',
        'varCaptcher',
        'chrStatus',
        'txtShortDescription',
        'chrDelete',
        'chrIsPrimary',
        'created_at',
        'updated_at'
    ];

    public static function getCurrentMonthCount() {
        $response = false;
        $response = Self::getRecords()
                ->whereRaw('MONTH(created_at) = MONTH(CURRENT_DATE())')
                ->whereRaw('YEAR(created_at) = YEAR(CURRENT_DATE())')
                ->where('chrPublish', '=', 'Y')
                ->where('chrDelete', '=', 'N')
                ->count();
        return $response;
    }

    public static function getCurrentYearCount() {
        $response = false;
        $response = Self::getRecords()
                ->whereRaw('YEAR(created_at) = YEAR(CURRENT_DATE())')
                ->where('chrPublish', '=', 'Y')
                ->where('chrDelete', '=', 'N')
                ->count();
        return $response;
    }

    /**
     * This method handels retrival of event records
     * @return  Object
     * @since   2017-08-02
     * @author  NetQuick
     */
    static function getRecords() {
        return self::with([]);
    }

    /**
     * This method handels retrival of record count
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getRecordById($id, $moduleFields = false) {
        $response = false;
        $moduleFields = [
        	'id', 
        	'varTitle', 
        	'intType', 
        	'varImage', 
        	'varCaptcher', 
        	'txtShortDescription',
        	'UserID', 
        	'chrDelete', 
        	'created_at', 
        	'updated_at'
        ];
        $response = Self::getPowerPanelRecords($moduleFields)->deleted()->checkRecordId($id)->first();
        return $response;
    }

    public function ticketimage() {
        $response = false;
        $response = $this->hasmany('App\TicketImage', 'fkticketId', 'id');
        return $response;
    }

    /**
     * This method handels backend records
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    static function getPowerPanelRecords($moduleFields = false, $ticketimagefileds = false) {
        $data = [];
        $response = false;
        $response = self::select($moduleFields);
        if ($ticketimagefileds != false) {
            $data['ticketimage'] = function ($query) use ($ticketimagefileds) {
                $query->select($ticketimagefileds)->publish();
            };
        }
        if (count($data) > 0) {
            $response = $response->with($data);
        }
        return $response;
    }

    /**
     * This method handels retrival of backend record list
     * @return  Object
     * @since   2017-10-24
     * @author  NetQuick
     */
    public static function getRecordList($filterArr = false) {
        $ticketimagefileds = ['id', 'fkticketId', 'txtImageName'];
        $response = false;
        $moduleFields = [
            'id',
            'varTitle',
            'intType',
            'varImage',
            'varCaptcher',
            'chrStatus',
            'txtShortDescription',
            'varLink',
            'UserID',
            'HoldMessage',
            'OnGoingMessage',
            'NewImplementationMessage',
            'CompleteMessage',
            'chrDelete',
            'created_at',
            'updated_at'
        ];
        $response = Self::getPowerPanelRecords($moduleFields, $ticketimagefileds)
                ->deleted()
                ->filter($filterArr)
                ->get();
        return $response;
    }

    /**
     * This method handels retrival of backend record list
     * @return  Object
     * @since   2017-10-24
     * @author  NetQuick
     */
    public static function getRecordForDashboardLeadList($limit = 7) {
        $ticketimagefileds = ['id', 'fkticketId', 'txtImageName'];
        $response = false;
        $moduleFields = [
            'id',
            'varTitle',
            'intType',
            'varImage',
            'varCaptcher',
            'txtShortDescription',
            'varLink',
            'chrDelete',
            'created_at',
            'updated_at'
        ];
        $response = Self::getPowerPanelRecords($moduleFields, $ticketimagefileds)
                ->deleted()
                ->publish()
                ->limit($limit)
                ->get();
        return $response;
    }

    public static function getRecordListDashboard($year = false, $timeparam = false, $month = false) {
        $response = false;
        $response = Self::select('id');
        $response = $response->where('chrPublish', '=', 'Y')->where('chrDelete', '=', 'N');
        if ($timeparam != 'month') {
            $response = $response->whereRaw("YEAR(created_at) = " . (int) $year . "")->count();
        } else {
            $response = $response->whereRaw("YEAR(created_at) = " . (int) $year . "")->whereRaw("MONTH(created_at) = " . (int) $month . "")->count();
        }
        return $response;
    }

    /**
     * This method handels retrival of backend record list for Export
     * @return  Object
     * @since   2017-10-24
     * @author  NetQuick
     */
    public static function getListForExport($selectedIds = false) {
        $response = false;
        $moduleFields = [ 
        	'varTitle',
        	'chrStatus', 
        	'intType', 
        	'txtShortDescription', 
        	'varLink', 
        	'created_at',
        	'HoldMessage',
        	'OnGoingMessage',
        	'NewImplementationMessage',
        	'CompleteMessage'
        ];
        $query = Self::getPowerPanelRecords($moduleFields)->deleted();
        if (!empty($selectedIds) && count($selectedIds) > 0) {
            $query->checkMultipleRecordId($selectedIds);
        }
        $response = $query->orderByCreatedAtDesc()->get();
        return $response;
    }

    public static function updateTicketStatus($recordId = false, $status = "P") {
        $response = false;
        $response = Self::where('id', $recordId)
                ->update(['chrStatus' => $status]);
        ;
        return $response;
    }

    /**
     * This method handels record id scope
     * @return  Object
     * @since   2016-07-24
     * @author  NetQuick
     */
    function scopeCheckRecordId($query, $id) {
        return $query->where('id', $id);
    }

    /**
     * This method handels publish scope
     * @return  Object
     * @since   2017-08-02
     * @author  NetQuick
     */
    function scopePublish($query) {
        return $query->where(['chrPublish' => 'Y']);
    }

    /**
     * This method handels delete scope
     * @return  Object
     * @since   2017-08-02
     * @author  NetQuick
     */
    function scopeDeleted($query) {
        return $query->where(['chrDelete' => 'N']);
    }

    /**
     * This method check multiple records id
     * @return  Object
     * @since   2017-08-02
     * @author  NetQuick
     */
    function scopeCheckMultipleRecordId($query, $Ids) {
        return $query->whereIn('id', $Ids);
    }

    /**
     * This method handle order by query
     * @return  Object
     * @since   2017-08-02
     * @author  NetQuick
     */
    function scopeOrderByCreatedAtDesc($query) {
        return $query->orderBy('created_at', 'DESC');
    }

    /**
     * This method handels filter scope
     * @return  Object
     * @since   2017-08-02
     * @author  NetQuick
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
        if (isset($filterArr['searchFilter']) && !empty($filterArr['searchFilter'])) {
            $data = $query->where('varTitle', 'like', '%' . $filterArr['searchFilter'] . '%');
        }
        if (!empty($query)) {
            $response = $query;
        }
        return $response;
    }

}
