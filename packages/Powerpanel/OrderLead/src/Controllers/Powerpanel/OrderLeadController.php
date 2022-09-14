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
        // print_r($arrResults);die;
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
        return Excel::download(new OrderLeadExport, 'OVVI -' . trans("orderlead::template.orderleadModule.orderleads") . '-' . date("dmy-h:i") . '.xlsx');
    }

    public static function tableData($value, $page=false) {
        // print_r($value);die;
        // Checkbox
        $checkboxFirstTD = view('powerpanel.partials.checkbox', ['name'=>'delete[]', 'value'=>$value->id])->render();
        $details = '';
        $label = '';
        $Business = (!empty($value->varTitle) ? $value->varTitle  : '');
        $FullName = (!empty($value->varOnFullName) ? $value->varOnFullName  : '');
        $Email = (!empty($value->varOnEmailId) ? MyLibrary::decryptLatest($value->varOnEmailId)  : '');
        $BusinessType = (!empty($value->varOnBusinessType) ? $value->varOnBusinessType  : '');
        $POSBundle = (!empty($value->chrOnPOSBundle) ? $value->chrOnPOSBundle  : '');
        $POSColor = (!empty($value->chrOnPOSColor) ? $value->chrOnPOSColor  : '');
        $SoftwareServiceFees = (!empty($value->varOnSoftwareServiceFees) ? $value->varOnSoftwareServiceFees  : '');
        $Peripherals = (!empty($value->varOnPeripherals) ? $value->varOnPeripherals  : '');
        $MenuProgramming = (!empty($value->varOnMenuProgramming) ? $value->varOnMenuProgramming  : '');
        $AdditionalModules = (!empty($value->varOnAdditionalModules) ? $value->varOnAdditionalModules  : '');
        $StreetAddress = (!empty($value->varOnStreetAddress) ? $value->varOnStreetAddress  : '');
                       
        $receive_date = '<span align="left" data-bs-toggle="tooltip" data-bs-placement="bottom" title="'.date(Config::get("Constant.DEFAULT_DATE_FORMAT").' '.Config::get("Constant.DEFAULT_TIME_FORMAT"), strtotime($value->created_at)).'">'.date(Config::get('Constant.DEFAULT_DATE_FORMAT'), strtotime($value->created_at)).'</span>';
        if(!empty($BusinessType)){
            $BusinessTypeArr = [
                "Fine_Dining_Restaurant" 	=> "Fine Dining Restaurant",
                "Quick_Service"          	=> "Quick Service",
                "Full_Service_Casual"    	=> "Full Service Casual",
                "Pizza"					 	=> "Pizza",
                "Bar_and_Lounge"		 	=> "Bar and Lounge",
                "Frozen_Yogurt/Ice_Cream" 	=> "Frozen Yogurt/Ice Cream",
                "Food_Truck"				=> "Food Truck",
                "Cafes/Bistros"				=> "Cafes/Bistros",
                "Convenience_Store"			=> "Convenience Store",
                "Liquor_Store"				=> "Liquor Store",
                "Bakery"					=> "Bakery",
                "Grocery_Store"				=> "Grocery Store",
                "Clothing_Store"			=> "Clothing Store",
                "Other"						=> "Other"
            ];
            $BusinesTypeList = explode(',',$BusinessType);
            $BusinesTypeListArr = array();
            foreach($BusinesTypeList as $BusinessTypes){
                $BusinesTypeListArr[] = $BusinessTypeArr[$BusinessTypes];
            }
            $BusinesTypeListDisplay = implode(", ",$BusinesTypeListArr);
            $label .= '<b>Business Type :- </b>'.$BusinesTypeListDisplay.'<br>';
        }
        if(!empty($POSBundle)){
            $label .= '<b>No Of POS Bundle :- </b>'.$POSBundle.'<br>';
        }
        if(!empty($POSColor)){
            $label .= '<b>POS Color :- </b>'.(($POSColor == "B") ? "Black" : "White").'<br>';
        }
        if(!empty($SoftwareServiceFees)){
            $SoftwareServiceFeesArr = ['', '$79.00 / Month (Paid Monthly)','$69.00 / Month (Paid Annually)'];
            $label .= '<b>Software & Service Fees :- </b>'.$SoftwareServiceFeesArr[$SoftwareServiceFees].'<br>';
        }
        if(!empty($Peripherals)){
            $PeripheralsArr = [
                "EMV - Credit Card Terminal",
                "Kitchen Printer",
                "Bar Code Scanner",
                "Weighing Scale",
                "Customer Display – 2 Line – Only Available in Black Color",
                "Customer Display – 10 Inch – Only Available in Black Color"
            ];
            $PeripheralsList = explode(',',$Peripherals);
            $PeripheralsListArr = array();
            foreach($PeripheralsList as $Peripheral){
                $PeripheralsListArr[] = $PeripheralsArr[$Peripheral];
            }
            $PeripheralsListDisplay = implode(", ",$PeripheralsListArr);
            $label .= '<b>Pheripherals :- </b>'.$PeripheralsListDisplay.'<br>';
        }
        if(!empty($MenuProgramming)){
            $MenuProgrammingArr = ['', '1 – 250 items','250 – 500 items','500 + items'];
            $label .= '<b>Menu Programming :- </b>'.$MenuProgrammingArr[$MenuProgramming].'<br>';
        }
        // echo $result;die;
        if(!empty($AdditionalModules)){
            $AdditionalModulesArr = [
                "Online Ordering - $39/month",
                "Gift Cards Processing - $15/month",
                "50+ - 3rd Party App Integration – $100/month (Unlimited Apps)",
                "Direct Accounting Integration – QuickBooks, Sage and 10 more - $25/month",
                "Direct Payroll Integration – ADT, Gusto and 7 more - $25/month",
                "Website Development and Social Media Platforms – TBD"
            ];
            $AdditionalModulesList = explode(',',$AdditionalModules);
            $AdditionalModulesDisplayArr = array();
            foreach($AdditionalModulesList as $AdditionalModule){
                $AdditionalModulesDisplayArr[] = $AdditionalModulesArr[$AdditionalModule];
            }
            $AdditionalModuleDisplay = implode(", ",$AdditionalModulesDisplayArr);
            // print_r($AdditionalModuleDisplay);die;
            $label .= '<b>Additional Modules :- </b>'.$AdditionalModuleDisplay.'<br>';
        }
        $details .= '<div class="pro-act-btn">';
        if($page == 'dashboard') {
            $details .= '<a href="javascript:void(0)" class="" onclick="return hs.htmlExpand(this,{width:300,headingText:\'Contents\',wrapperClassName:\'titlebar\',showCredits:false});"><i class="ri-feedback-line fs-24 body-color"></i></a>';
        } else {
            $details .= '<a href="javascript:void(0)" class="" onclick="return hs.htmlExpand(this,{width:300,headingText:\'Contents\',wrapperClassName:\'titlebar\',showCredits:false});"><i class="ri-mail-open-line fs-16"></i></a>';
        }
        $details .= '<div class="highslide-maincontent">' . nl2br($label) . '</div>';
        $details .= '</div>';

        $Businessdetails = '';
        $Businesslabel = '';
        $Country = (!empty($value->country) ? $value->country  : '');
        $State = (!empty($value->state) ? $value->state  : '');
        $City = (!empty($value->city) ? $value->city  : '');
        $ZipCode = (!empty($value->varOnZipCode) ? MyLibrary::decryptLatest($value->varOnZipCode)  : '');
        $PhoneNumber = (!empty($value->varOnPhoneNumber) ? MyLibrary::decryptLatest($value->varOnPhoneNumber)  : '');
        $AdditionalEquipment = (!empty($value->varOnAdditionalEquipment) ? $value->varOnAdditionalEquipment  : '');
        if(!empty($StreetAddress)){
            $Businesslabel .= '<b>Street Address :- </b>'.$StreetAddress.'<br>';
        }
        if(!empty($Country)){
            $Businesslabel .= '<b>Country :- </b>'.$Country.'<br>';
        }
        if(!empty($State)){
            $Businesslabel .= '<b>State :- </b>'.$State.'<br>';
        }
        if(!empty($City)){
            $Businesslabel .= '<b>City :- </b>'.$City.'<br>';
        }
        if(!empty($ZipCode)){
            $Businesslabel .= '<b>ZipCode :- </b>'.$ZipCode.'<br>';
        }
        if(!empty($PhoneNumber)){
            $Businesslabel .= '<b>PhoneNumber :- </b>'.$PhoneNumber.'<br>';
        }
        if(!empty($AdditionalEquipment)){
            $Businesslabel .= '<b>AdditionalEquipment :- </b>'.$AdditionalEquipment.'<br>';
        }
        $Businessdetails .= '<div class="pro-act-btn">';
        if($page == 'dashboard') {
            $Businessdetails .= '<a href="javascript:void(0)" class="" onclick="return hs.htmlExpand(this,{width:300,headingText:\'Contents\',wrapperClassName:\'titlebar\',showCredits:false});"><i class="ri-feedback-line fs-24 body-color"></i></a>';
        } else {
            $Businessdetails .= '<a href="javascript:void(0)" class="" onclick="return hs.htmlExpand(this,{width:300,headingText:\'Contents\',wrapperClassName:\'titlebar\',showCredits:false});"><i class="ri-mail-open-line fs-16"></i></a>';
        }
        $Businessdetails .= '<div class="highslide-maincontent">' . nl2br($Businesslabel) . '</div>';
        $Businessdetails .= '</div>';
    
        $records = array(
            $checkboxFirstTD,
            $Business,
            $FullName,
            $Email,
            $details,
            $Businessdetails,
            $value->varIpAddress,
            $receive_date
        );

        return $records;
    }

}
