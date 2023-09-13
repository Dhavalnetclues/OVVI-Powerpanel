<?php

namespace Powerpanel\ResellerLead\Controllers\Powerpanel;

use App\Http\Controllers\PowerpanelController;
use Illuminate\Support\Facades\Redirect;
use Request;
use Excel;
use Powerpanel\ResellerLead\Models\ResellerLead;
use App\CommonModel;
use App\Helpers\MyLibrary;
use Powerpanel\ResellerLead\Models\ResellerLeadExport;
use Config;

class ResellerleadController extends PowerpanelController {

    /**
     * Create a new Dashboard controller instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
        
        if (isset($_COOKIE['locale'])) {
            app()->setLocale($_COOKIE['locale']);
        }
    }

    public function index() {
        $iTotalRecords = CommonModel::getRecordCount(false,false,false,'Powerpanel\ResellerLead\Models\ResellerLead');
        $this->breadcrumb['title'] = trans('resellerlead::template.resellerleadModule.manageresellerlead');
        return view('resellerlead::powerpanel.list', ['iTotalRecords' => $iTotalRecords, 'breadcrumb' => $this->breadcrumb]);
    }

    public function get_list() {
        $filterArr = [];
        $records = [];
        $records["data"] = [];
        $filterArr['orderColumnNo'] = (!empty(Request::get('order') [0]['column']) ? Request::get('order') [0]['column'] : '');
        $filterArr['orderByFieldName'] = (!empty(Request::get('columns') [$filterArr['orderColumnNo']]['name']) ? Request::get('columns') [$filterArr['orderColumnNo']]['name'] : '');
        $filterArr['orderTypeAscOrDesc'] = (!empty(Request::get('order') [0]['dir']) ? Request::get('order') [0]['dir'] : '');
        $filterArr['searchFilter'] = !empty(Request::get('searchValue')) ? Request::get('searchValue') : '';
         $filterArr['start'] = !empty(Request::get('rangeFilter')['from']) ? Request::get('rangeFilter')['from'] : '';
        $filterArr['end'] = !empty(Request::get('rangeFilter')['to']) ? Request::get('rangeFilter')['to'] : '';
        $filterArr['iDisplayLength'] = intval(Request::get('length'));
        $filterArr['iDisplayStart'] = intval(Request::get('start'));
        $sEcho = intval(Request::get('draw'));

        if(isset($_REQUEST['id'])){
            $id = $_REQUEST['id'];
        }else{
            $id = '';
        }

        $arrResults = ResellerLead::getRecordList($filterArr,$id);
        // print_r($arrResults);die;
        $iTotalRecords = ResellerLead::getRecordCount($filterArr, true,'','',$id);
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
    public function DeleteRecord(Request $request) {
        $data = Request::all('ids');
        $update = MyLibrary::deleteMultipleRecords($data,false,false,'Powerpanel\ResellerLead\Models\ResellerLead');
        echo json_encode($update);
        exit;
    }

    /**
     * This method handels export process of contact us leads
     * @return  xls file
     * @since   2016-10-18
     * @author  NetQuick
     */
    public function ExportRecord() {
        return Excel::download(new ResellerLeadExport, 'OVVI -' . trans("resellerlead::template.resellerleadModule.resellerLeads") . '-' . date("dmy-h:i") . '.xlsx');
    }

    public function tableData($value, $page=false) {
        $details = '-';
        $phoneNo = '-';
        // Checkbox
        $checkbox = view('powerpanel.partials.checkbox', ['name'=>'delete[]', 'value'=>$value->id])->render();
        $details = '';
        if (!empty($value->varMessage)) {
            $details .= '<div class="pro-act-btn">';
            $details .= '<a href="javascript:void(0)" class="without_bg_icon" onclick="return hs.htmlExpand(this,{width:300,headingText:\'Message\',wrapperClassName:\'titlebar\',showCredits:false});"><i aria-hidden="true" class="ri-message-2-line fs-16"></i></a>';
            $details .= '<div class="highslide-maincontent">' . nl2br($value->varMessage) . '</div>';
            $details .= '</div>';
        } else {
            $details .= '-';
        }
        
        $Reseller_details = $label = '';
        $StreetAddress = (!empty($value->varAddress) ? $value->varAddress  : '');
        $Country = (!empty($value->country) ? $value->country  : '');
        $State = (!empty($value->state) ? $value->state  : '');
        $City = (!empty($value->city) ? $value->city  : '');
        $BestTimeToCall = (!empty($value->varBestTimeToCall) ? $value->varBestTimeToCall  : '');
        if(!empty($StreetAddress)){
            $label .= '<b>Address :- </b>'.$StreetAddress.'<br>';
        }
        if(!empty($Country)){
            $label .= '<b>Country :- </b>'.$Country.'<br>';
        }
        if(!empty($State)){
            $label .= '<b>State :- </b>'.$State.'<br>';
        }
        if(!empty($City)){
            $label .= '<b>City :- </b>'.$City.'<br>';
        }
        if(!empty($BestTimeToCall)){
            $label .= '<b>Best Time To Call :- </b>'.$BestTimeToCall.'<br>';
        }
        $Reseller_details .= '<div class="pro-act-btn">';
        if($page == 'dashboard') {
            $Reseller_details .= '<a href="javascript:void(0)" class="" onclick="return hs.htmlExpand(this,{width:300,headingText:\'Contents\',wrapperClassName:\'titlebar\',showCredits:false});"><i class="ri-feedback-line fs-24 body-color"></i></a>';
        } else {
            $Reseller_details .= '<a href="javascript:void(0)" class="" onclick="return hs.htmlExpand(this,{width:300,headingText:\'Contents\',wrapperClassName:\'titlebar\',showCredits:false});"><i class="ri-mail-open-line fs-16"></i></a>';
        }
        $Reseller_details .= '<div class="highslide-maincontent">' . nl2br($label) . '</div>';
        $Reseller_details .= '</div>';
            
        if (!empty($value->varCompaney) ) {
            $Companey = $value->varCompaney;
        } else {    
            $Companey = '-';
        }
        if (!empty($value->varPhoneNumber)) {
            $phoneNo = MyLibrary::decryptLatest($value->varPhoneNumber);
        } else {
            $phoneNo = '-';
        }

		$ipAdress = (isset($value->varIpAddress) && !empty($value->varIpAddress)) ? $value->varIpAddress : "-";
        $receivedDate = '<span align="left" data-bs-toggle="tooltip" data-bs-placement="bottom" title="'.MyLibrary::UTCToTimeZone($value->created_at, 'UTC', 'America/Chicago').'">'.MyLibrary::UTCToTimeZone($value->created_at, 'UTC', 'America/Chicago').'</span>';

        $records = array(
            $checkbox,
            $value->varTitle,
            $phoneNo,
            MyLibrary::decryptLatest($value->varEmailId),
            // $Satisfied,
            // $Visitfor,
            $Companey,
            $Reseller_details,
            $details,
            $ipAdress,
            $receivedDate
        );

        return $records;
    }

}
