<?php
namespace Powerpanel\Events\Models;
use Illuminate\Database\Eloquent\Model;
use DB;
class EventLead extends Model {
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	 protected $table = 'event_lead';
	 protected $fillable = [
		'id',
		'eventId',
		'startDate',
		'endDate',
		'startTime',
		'endTime',
		'noOfAttendee',
		'attendeeDetail',
		'message',
		'chrPublish',
		'chrDelete',
		'varIpAddress',
		'created_at',
		'updated_at'
	];

	public static function getEventAttendeeCount($eventId, $startDate, $endDate, $startTime, $endTime ) {
		$response = false;
        $moduleFields = [
			'id',
			'eventId',
			'startDate',
			'endDate',
			'startTime',
			'endTime',
			'noOfAttendee',
			'attendeeDetail',
			'message',
			'chrPublish',
			'chrDelete',
			'varIpAddress',
			'created_at',
			'updated_at'
		];

		if (empty($response)) {
			$response = Self::getFrontRecords($moduleFields)
								->where('eventId',$eventId)
								->where('startDate',$startDate)
								->where('endDate',$endDate)
								->where('startTime',$startTime)
								->where('endTime',$endTime)->get();

			$response = $response->sum('noOfAttendee');
		}
		return $response;
	}

	public static function getFrontRecords($moduleFields = false, $aliasFields = false) {
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


	public static function getCurrentMonthCount(){
		$response = false;
		$response = Self::getRecords()
		->whereRaw('MONTH(created_at) = MONTH(CURRENT_DATE())') 
		->whereRaw('YEAR(created_at) = YEAR(CURRENT_DATE())')
		->where('chrPublish','=','Y')
		->where('chrDelete','=','N')		
		->count();
		return $response;
	}

	public static function getCurrentYearCount(){
		$response = false;
		$response = Self::getRecords()		
		->whereRaw('YEAR(created_at) = YEAR(CURRENT_DATE())')
		->where('chrPublish','=','Y')
		->where('chrDelete','=','N')		
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
		public static function getRecordById($id,$moduleFields = false)
		{
				$response      = false;
        $moduleFields = ['id','varName','varEmail','varPhoneNo','txtUserMessage','chrDelete','varIpAddress','created_at','updated_at'];       
        $response = Self::getPowerPanelRecords($moduleFields)->deleted()->checkRecordId($id)->first();
        return $response;
		}

	/**
	 * This method handels backend records
	 * @return  Object
	 * @since   2016-07-14
	 * @author  NetQuick
	 */
	static function getPowerPanelRecords($moduleFields=false ) 
	{     
		$data=[];
		$response = false;
		$response=self::select($moduleFields);
		if(count($data)>0){
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
	public static function getRecordList($filterArr=false){
		$response = false;
		$moduleFields=[ 
			'id',
			'eventId',
			'startDate',
			'endDate',
			'startTime',
			'endTime',
			'noOfAttendee',
			'attendeeDetail',
			'message',
			'chrPublish',
			'chrDelete',
			'varIpAddress',
			'created_at',
			'updated_at'
		];
		$response = Self::getPowerPanelRecords($moduleFields)
		->deleted()		
		->filter($filterArr)
		->get(); 
		return $response;
	}

	/**
	 * This method handels retrival of backend record list for Export
	 * @return  Object
	 * @since   2017-10-24
	 * @author  NetQuick
	 */
	public static function getListForExport($selectedIds=false){
		$response = false;
		$moduleFields=[ 
			'id',
			'eventId',
			'startDate',
			'endDate',
			'startTime',
			'endTime',
			'noOfAttendee',
			'attendeeDetail',
			'message',
			'chrPublish',
			'chrDelete',
			'varIpAddress',
			'created_at',
			'updated_at'
		];
		$query = Self::getPowerPanelRecords($moduleFields)->deleted();
		if(!empty($selectedIds) && count($selectedIds) > 0){
			$query->checkMultipleRecordId($selectedIds);
		}
		$response = $query->orderByCreatedAtDesc()->get();
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
	function scopeCheckMultipleRecordId($query,$Ids) 
	{
			return $query->whereIn('id',$Ids);
	}

	 /**
	 * This method handle order by query
	 * @return  Object
	 * @since   2017-08-02
	 * @author  NetQuick
	 */
	function scopeOrderByCreatedAtDesc($query) {
			return $query->orderBy('created_at','DESC');
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

    public static function getDashboardReport($year = false) {
        $response = false;
        $response = Self::where('chrPublish', '=', 'Y')->where('chrDelete', '=', 'N');
        if ($year != '') {
            $response = $response->whereRaw("YEAR(created_at) >= " . (int) $year . "");
        }
        $response = $response->count();
        return $response;
    }

	/**
	 * This method handels filter scope
	 * @return  Object
	 * @since   2017-08-02
	 * @author  NetQuick
	 */
	function scopeFilter($query, $filterArr = false ,$retunTotalRecords = false) 
	{
				$response = null;
				if (!empty($filterArr['orderByFieldName'])  && !empty($filterArr['orderTypeAscOrDesc'])) {
						$query = $query->orderBy($filterArr['orderByFieldName'], $filterArr['orderTypeAscOrDesc']);
				} else {
						$query = $query->orderBy('id', 'DESC');
				}

				if (!$retunTotalRecords) {
						if (!empty($filterArr['iDisplayLength']) && $filterArr['iDisplayLength'] > 0) {
								$data = $query->skip($filterArr['iDisplayStart'])->take($filterArr['iDisplayLength']);
						}
				}
				if (!empty($filterArr['statusFilter']) && $filterArr['statusFilter'] != ' ') {
						$data = $query->where('chrPublish' ,$filterArr['statusFilter']);
				}
				if(isset($filterArr['searchFilter']) && !empty($filterArr['searchFilter']))
				{
						$data = $query->where('varName','like','%'.$filterArr['searchFilter'].'%')->orwhere('varEmail','like','%'.$filterArr['searchFilter'].'%');
				}

				if (!empty($filterArr['start']) && $filterArr['start'] != ' ') {
						$data = $query->whereRaw('DATE(created_at) >= DATE("' . date('Y-m-d', strtotime(str_replace('/', '-', $filterArr['start']))) . '")');
				}

				if (!empty($filterArr['start']) && $filterArr['start'] != '' &&  empty($filterArr['end']) && $filterArr['end'] == '') {
						$data = $query->whereRaw('DATE(created_at) >= DATE("' . date('Y-m-d', strtotime(str_replace('/', '-', $filterArr['start']))) . '")');
				}

				if (!empty($filterArr['end']) && $filterArr['end'] != ' ') {
						$data = $query->whereRaw('DATE(created_at) <= DATE("' . date('Y-m-d', strtotime(str_replace('/', '-', $filterArr['end']))) . '") AND created_at IS NOT null');
				}

				if (!empty($query)) {
						$response = $query;
				}
				return $response;
	}

}