<?php

namespace Powerpanel\OrderLead\Controllers\Powerpanel;

use App\Http\Controllers\PowerpanelController;
use Illuminate\Support\Facades\Redirect;
use Request;
use Excel;
use App\Department;
use Powerpanel\OrderLead\Models\OrderLead;
use Powerpanel\OrderLead\Models\OrderLeadExport;
use App\CommonModel;
use App\Helpers\MyLibrary;
use Config;
use App\UserNotification;
use App\Helpers\Email_sender;
use Illuminate\Support\Facades\Validator;

class OrderLeadController extends PowerpanelController {


    public function __construct() {
        parent::__construct();
        if (isset($_COOKIE['locale'])) {
            app()->setLocale($_COOKIE['locale']);
        }
    }


    public function index() {
        $iTotalRecords = OrderLead::getRecordCount(false,true,"Powerpanel\OrderLead\Models\OrderLead");
        $this->breadcrumb['title'] = trans('orderlead::template.orderleadModule.manageorderLeads');
        return view('orderlead::powerpanel.list', ['iTotalRecords' => $iTotalRecords, 'breadcrumb' => $this->breadcrumb]);
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

        if (isset($_REQUEST['id'])) {
            $id = $_REQUEST['id'];
        } else {
            $id = '';
        }

        $arrResults = OrderLead::getRecordList($filterArr, $id);
        $iTotalRecords = OrderLead::getRecordCount($filterArr, true, '', '', $id);
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


    public function DeleteRecord(Request $request) {
        $data = Request::all('ids');
        $update = MyLibrary::deleteMultipleRecords($data,false,false,"Powerpanel\OrderLead\Models\OrderLead");
        UserNotification::deleteNotificationByRecordID($data['ids'], Config::get('Constant.MODULE.ID'));
        echo json_encode($update);
        exit;
    }



    public function ExportRecord() {
        return Excel::download(new OrdereadExport, Config::get('Constant.SITE_NAME') . '-' . trans("orderlead::template.orderleadModule.orderleads") . '-' . date("dmy-h:i") . '.xlsx');
    }



    public static function tableData($value, $page=false) {

        // Checkbox
        $checkboxFirstTD = view('powerpanel.partials.checkbox', ['name'=>'delete[]', 'value'=>$value->id])->render();

        // $customeformdata = \App\CommonModel::getFormBuilderData($value->fk_formbuilder_id);
        $details = '';
        $label = '';
        // $requestkey_array = [];
        // $json_customeformdata = (isset($customeformdata->varFormDescription)) ? (json_decode($customeformdata->varFormDescription)) : null;
        // $json_data = (json_decode($value->formdata));
        // $json_Array = (array) $json_data;

        // foreach ($json_data as $key => $va) {
        //     $requestkey_array[] = $key;
        // }

        // $requestKeys = $requestkey_array;
        $inputsOfEmailArray = array();
        $valueindex = 0;
        $checkbox = '';
        $user_email = '';
        $Business = (!empty($value->varTitle) ? $value->varTitle  : ' - ');
        $FullName = (!empty($value->varOnFullName) ? $value->varOnFullName  : ' - ');

        $receive_date = '<span align="left" data-bs-toggle="tooltip" data-bs-placement="bottom" title="'.date(Config::get("Constant.DEFAULT_DATE_FORMAT").' '.Config::get("Constant.DEFAULT_TIME_FORMAT"), strtotime($value->created_at)).'">'.date(Config::get('Constant.DEFAULT_DATE_FORMAT'), strtotime($value->created_at)).'</span>';
        
        $label = 'Test';
        $details .= '<div class="pro-act-btn">';
        if($page == 'dashboard') {
            $details .= '<a href="javascript:void(0)" class="" onclick="return hs.htmlExpand(this,{width:300,headingText:\'Contents\',wrapperClassName:\'titlebar\',showCredits:false});"><i class="ri-feedback-line fs-24 body-color"></i></a>';
        } else {
            $details .= '<a href="javascript:void(0)" class="" onclick="return hs.htmlExpand(this,{width:300,headingText:\'Contents\',wrapperClassName:\'titlebar\',showCredits:false});"><i class="ri-mail-open-line fs-16"></i></a>';
        }
        $details .= '<div class="highslide-maincontent">' . nl2br($label) . '</div>';
        $details .= '</div>';
    
        $records = array(
            $checkboxFirstTD,
            $Business,
            $FullName,
            $details,
            $value->varIpAddress,
            $receive_date
        );

        return $records;
    }

}
