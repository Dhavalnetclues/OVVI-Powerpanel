<?php
namespace Powerpanel\Events\Controllers\Powerpanel;

use App\CommonModel;
use Powerpanel\Events\Models\EventLead;
use Powerpanel\Events\Models\Events;
use Powerpanel\Events\Models\EventLeadExport;
use App\Helpers\MyLibrary;
use App\Http\Controllers\PowerpanelController;
use Config;
use Excel;
use Request;

class EventsLeadController extends PowerpanelController
{

    /**
     * Create a new Dashboard controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        if (isset($_COOKIE['locale'])) {
            app()->setLocale($_COOKIE['locale']);
        }
    }

    public function index()
    {
        $iTotalRecords = CommonModel::getRecordCount(false,false,false, 'Powerpanel\Events\Models\EventLead');
        $this->breadcrumb['title'] = trans('events::template.eventsleadModule.manageEventsLeads');
        return view('events::powerpanel.list', ['iTotalRecords' => $iTotalRecords, 'breadcrumb' => $this->breadcrumb]);
    }

    public function get_list()
    {
        $filterArr = [];
        $records = [];
        $records["data"] = [];
        $filterArr['orderColumnNo'] = (!empty(Request::get('order')[0]['column']) ? Request::get('order')[0]['column'] : '');
        $filterArr['orderByFieldName'] = (!empty(Request::get('columns')[$filterArr['orderColumnNo']]['name']) ? Request::get('columns')[$filterArr['orderColumnNo']]['name'] : '');
        $filterArr['orderTypeAscOrDesc'] = (!empty(Request::get('order')[0]['dir']) ? Request::get('order')[0]['dir'] : '');
        $filterArr['searchFilter'] = !empty(Request::get('searchValue')) ? Request::get('searchValue') : '';
        $filterArr['start'] = !empty(Request::get('rangeFilter')['from']) ? Request::get('rangeFilter')['from'] : '';
        $filterArr['end'] = !empty(Request::get('rangeFilter')['to']) ? Request::get('rangeFilter')['to'] : '';
        $filterArr['iDisplayLength'] = intval(Request::get('length'));
        $filterArr['iDisplayStart'] = intval(Request::get('start'));
        $filterArr['rangeFilter'] = !empty(Request::input('rangeFilter')) ? Request::input('rangeFilter') : '';

        $sEcho = intval(Request::get('draw'));

        $arrResults = EventLead::getRecordList($filterArr);
        $iTotalRecords = CommonModel::getRecordCount($filterArr, true,false, 'Powerpanel\Events\Models\EventLead');

        $end = $filterArr['iDisplayStart'] + $filterArr['iDisplayLength'];
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;

        if (!empty($arrResults)) {
            foreach ($arrResults as $key => $value) {
                $records["data"][] = $this->tableData($value);
            }
        }

        if (isset($_REQUEST["customActionType"]) && $_REQUEST["customActionType"] == "group_action") {
            $records["customActionStatus"] = "OK"; // pass custom message(useful for getting status of group actions)
        }

        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;
        echo json_encode($records);
        exit;

    }

    /**
     * This method handels delete leads operation
     * @return  xls file
     * @since   2016-10-18
     * @author  NetQuick
     */
    public function DeleteRecord(Request $request)
    {
        $data = Request::all('ids');
        $update = MyLibrary::deleteMultipleRecords($data,false,false,'Powerpanel\Events\Models\EventLead');
        echo json_encode($update);
        exit;
    }

    /**
     * This method handels export process of Complaint leads
     * @return  xls file
     * @since   2016-10-18
     * @author  NetQuick
     */
    public function ExportRecord()
    {
        return Excel::download(new EventLeadExport, Config::get('Constant.SITE_NAME') . '-' . trans("events::template.complaintleadModule.eventsLeads") . '-' . date("dmy-h:i") . '.xlsx');

    }

    public function tableData($value)
    {
        // Checkbox
        $checkbox = view('powerpanel.partials.checkbox', ['name'=>'delete[]', 'value'=>$value->id])->render();

       $eventDetail = Events::getRecordById($value->eventId);

        if(isset($eventDetail) && !empty($eventDetail)) {
            $eventName = $eventDetail->varTitle;
        } else {
            $eventName = '-';
        }

        $attendeeDetails = json_decode($value->attendeeDetail);
        if(isset($attendeeDetails) && !empty($attendeeDetails)) {
            $attendeeName = $attendeeDetails[0]->full_name;
            $attendeeEmail = $attendeeDetails[0]->email;
        } else {
            $attendeeName = '-';
            $attendeeEmail = '-';
        }

        $noOfAttendee = (isset($value->noOfAttendee) && !empty($value->noOfAttendee)) ? $value->noOfAttendee : "-"; 

        // Date
        $startDate = '<span align="left" data-bs-toggle="tooltip" data-bs-placement="bottom" title="'.date(Config::get("Constant.DEFAULT_DATE_FORMAT").' '.Config::get("Constant.DEFAULT_TIME_FORMAT"), strtotime($value->startDate)).'">'.date(Config::get('Constant.DEFAULT_DATE_FORMAT'), strtotime($value->startDate)).'</span>';
        if(!empty($value->endDate)){
            $endDate = '<span align="left" data-bs-toggle="tooltip" data-bs-placement="bottom" title="'.date(Config::get("Constant.DEFAULT_DATE_FORMAT").' '.Config::get("Constant.DEFAULT_TIME_FORMAT"), strtotime($value->endDate)).'">'.date(Config::get('Constant.DEFAULT_DATE_FORMAT'), strtotime($value->endDate)).'</span>';
        } else {
            $endDate = 'No Expiry';
        }

        $receivedDate = '<span align="left" data-bs-toggle="tooltip" data-bs-placement="bottom" title="'.date(Config::get("Constant.DEFAULT_DATE_FORMAT").' '.Config::get("Constant.DEFAULT_TIME_FORMAT"), strtotime($value->created_at)).'">'.date(Config::get('Constant.DEFAULT_DATE_FORMAT'), strtotime($value->created_at)).'</span>';

        $attendee_Details = '-';
        // $attendee_Details .= '<div class="pro-act-btn"><a href="javascript:void(0)" class="" onclick="return hs.htmlExpand(this,{width:300,headingText:\'Attendee(s) Details\',wrapperClassName:\'titlebar\',showCredits:false});"><span aria-hidden="true" class="icon-info"></span></a>';
        // $attendee_Details .= '<div class="highslide-maincontent">';
        // $attendee_Details .= '<ul>';
        //     foreach($attendeeDetails as $key => $v){
        //         $attendee_Details .= '<li>Full Name :'.' '.$v->full_name . '</li>';
        //         $attendee_Details .= '<li>Email :'.' '.$v->email . '</li>';
        //         $attendee_Details .= '<li>Phone No. :'.' '.$v->phone . '</li><br>';
        //     }
        // $attendee_Details .= '<ul>';
        // $attendee_Details .= '</div>';
        // $attendee_Details .= '</div>';

        $message = '';
        $message .= '<a href="javascript:void(0)" class="" onclick="return hs.htmlExpand(this,{width:300,headingText:\'Contents\',wrapperClassName:\'titlebar\',showCredits:false});"><i class="ri-mail-open-line fs-16"></i></a>';
        $message .= '<div class="highslide-maincontent">';
        $message .= '<ul>';
            $message .= '<li>'. $value->message . '</li>';
        $message .= '<ul>';
        $message .= '</div>';
        $message .= '</div>';

        $records = array(
            $checkbox,
            $eventName,
            $attendeeName,
            $attendeeEmail,
            $startDate,
            $endDate,
            $noOfAttendee,
            $attendee_Details,
            $message,
            $receivedDate
        );

        return $records;
    }
}

