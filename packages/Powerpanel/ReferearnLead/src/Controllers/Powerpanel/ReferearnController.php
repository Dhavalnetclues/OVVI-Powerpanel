<?php
/**
 * The SubscriptionController class handels subscription functions for front end
 * configuration  process.
 * @package   Netquick powerpanel
 * @license   http://www.opensource.org/licenses/BSD-3-Clause
 * @version   1.00
 * @since     2017-11-10
 * @author    NetQuick
 */
namespace Powerpanel\ReferearnLead\Controllers\Powerpanel;

use App\CommonModel;
use Powerpanel\ReferearnLead\Models\ReferearnLead;
use Powerpanel\ReferearnLead\Models\ReferearnLeadExport;
use App\Helpers\MyLibrary;
use App\Http\Controllers\PowerpanelController;
use Config;
use Excel;
use Request;

class ReferearnController extends PowerpanelController
{
    public function __construct()
    {
        parent::__construct();
        if (isset($_COOKIE['locale'])) {
            app()->setLocale($_COOKIE['locale']);
        }
    }
    /**
     * This method load all subscription view
     * @return  View
     * @since   2016-11-25
     * @author  NetQuick
     */
    public function index()
    {
        $iTotalRecords = CommonModel::getRecordCount(false,false,false, 'Powerpanel\ReferearnLead\Models\ReferearnLead');
        $this->breadcrumb['title'] = trans('referearn::template.referearnsModule.manageReferearnLeads');
        return view('referearn::powerpanel.index', ['iTotalRecords' => $iTotalRecords, 'breadcrumb' => $this->breadcrumb]);
    }
    /**
     * This method loads team table data on view
     * @return  View
     * @since   2016-11-14
     * @author  NetQuick
     */
    public function get_list()
    {
        $filterArr = [];
        $records = [];
        $records["data"] = [];
        $filterArr['orderColumnNo'] = (!empty(Request::get('order')[0]['column']) ? Request::get('order')[0]['column'] : '');
        $filterArr['orderByFieldName'] = (!empty(Request::get('columns')[$filterArr['orderColumnNo']]['name']) ? Request::get('columns')[$filterArr['orderColumnNo']]['name'] : '');
        $filterArr['orderTypeAscOrDesc'] = (!empty(Request::get('order')[0]['dir']) ? Request::get('order')[0]['dir'] : '');
        $filterArr['searchFilter'] = !empty(Request::get('searchValue')) ? Request::get('searchValue') : '';
        $filterArr['emailtypeFilter'] = !empty(Request::get('customActionName')) ? Request::get('customActionName') : '';
        $filterArr['start'] = !empty(Request::get('rangeFilter')['from']) ? Request::get('rangeFilter')['from'] : '';
        $filterArr['end'] = !empty(Request::get('rangeFilter')['to']) ? Request::get('rangeFilter')['to'] : '';
        $filterArr['iDisplayLength'] = intval(Request::get('length'));
        $filterArr['iDisplayStart'] = intval(Request::get('start'));

        $sEcho = intval(Request::get('draw'));
        $arrResults = ReferearnLead::getRecordList($filterArr);
        $iTotalRecords = CommonModel::getRecordCount($filterArr, true,false, 'Powerpanel\ReferearnLead\Models\ReferearnLead');
        $end = $filterArr['iDisplayStart'] + $filterArr['iDisplayLength'];
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;
        if (!empty($arrResults)) {
            foreach ($arrResults as $key => $value) {
                $records["data"][] = $this->tableData($value);
            }
        }
        if (!empty(Request::get("customActionType")) && Request::get("customActionType") == "group_action") {
            $records["customActionStatus"] = "OK"; // pass custom message(useful for getting status of group actions)
        }
        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;
        echo json_encode($records);
    }
    public function DeleteRecord(Request $request)
    {
        $data = Request::all('ids');
        $update = MyLibrary::deleteMultipleRecords($data,false,false,'Powerpanel\ReferearnLead\Models\ReferearnLead');
        echo json_encode($update);
        exit;
    }
    /**
     * This method handels send subscribe email function
     * @return  View
     * @since   2017-11-10
     * @author  NetQuick
     */
    public function send_email()
    {
        $data = ReferearnLead::getRecords()->publish()->deleted();
        if ($data->count() > 0) {
            $data = $data->get()->first()->toArray();
            $id = Crypt::encrypt($data['id']);
            Email_sender::contactUs($data, $id);
            echo 'email sent!';
            //return view('emails.feedback');
        }
    }
    /**
     * This method handels export process of newsletter leads
     * @return  xls file
     * @since   2017-05-12
     * @author  NetQuick
     */
    public function ExportRecord()
    {
        return Excel::download(new ReferearnLeadExport, 'OVVI -' . trans("referearn::template.referearnsModule.referearnLeads") . '-' . date("dmy-h:i") . '.xlsx');
    }

    public function tableData($value, $page=false)
    {

        // Checkbox
        $checkbox = view('powerpanel.partials.checkbox', ['name'=>'delete[]', 'value'=>$value->id])->render();

        $name = '';
        $details = '';
        $label = '';
        $FullName = (!empty($value->	varName) ? $value->	varName  : '');
        $Email = (!empty($value->varEmailId) ? MyLibrary::decryptLatest($value->varEmailId)  : '');
        $ReferralFullNameme = (!empty($value->varReferralFullName) ? $value->varReferralFullName  : '');
        $ReferralEmailId = (!empty($value->varReferralEmailId) ? MyLibrary::decryptLatest($value->varReferralEmailId)  : '');
        $ReferralPhoneNumber = (!empty($value->varReferralPhoneNumber) ? MyLibrary::decryptLatest($value->varReferralPhoneNumber)  : '');
        $BusinessType = (!empty($value->varBusinessType) ? $value->varBusinessType  : '');
        $LookingForPOS = (!empty($value->varLookingForPOS) ? $value->varLookingForPOS  : '');
        $ipAdress = (isset($value->varIpAddress) && !empty($value->varIpAddress)) ? $value->varIpAddress : "-";

        if(!empty($ReferralPhoneNumber)){
            $label .= '<b>Referral\'s Phone  :- </b>'.$ReferralPhoneNumber.'<br>';
        }
        if(!empty($BusinessType)){
            $BusinessTypeArr = [
                "Quick_Serve" 			=> "Quick-Serve",
                "Restaurant_/_Bar"		=> "Restaurant / Bar",
                "Retail"				=> "Retail",
                "Bakery"				=> "Bakery",
                "Coffee_Shop" 			=> "Coffee Shop",
                "Concessions_/_Snacks" 	=> "Concessions / Snacks",
                "Food_Truck"			=> "Food Truck",
                "Ice_Cream_/_Yogurt" 	=> "Ice Cream / Yogurt",
                "Juice_Bar"				=> "Juice Bar",
                "Limited-Service_Restaurant" => "Limited-Service Restaurant",
                "Fine_Dining"			=> "Fine Dining",
                "Bar_and_Lounge"		=> "Bar and Lounge",
                "Pizza"					=> "Pizza",
                "Convenience_Store"		=> "Convenience Store",
                "Liquor_Store"			=> "Liquor Store",
                "Grocery_Store"			=> "Grocery Store",
                "Other"					=> "Other"
            ];
            $BusinessTypeList = explode(',',$BusinessType);
            $BusinessTypeListArr = array();
            foreach($BusinessTypeList as $BusinessTypeA){
                $BusinessTypeListArr[] = $BusinessTypeArr[$BusinessTypeA];
            }
            $BusinessTypeDisplay = implode(", ",$BusinessTypeListArr);
            $label .= '<b>Business Type :- </b>'.$BusinessTypeDisplay.'<br>';
        }
        if(!empty($LookingForPOS)){
            $LookingForPOSArr = [
                "Immediately"   => "Immediately",
                "Immediatel"   => "Immediately",
                "1"             => "1 Month",
                "2"             => "2 Months",
                "3-6"             => "3-6 Months",
                "6+"            => "Greater Than 6 Months"
            ];          
            $label .= '<b>POS :- </b>'.$LookingForPOSArr[trim($LookingForPOS)].'<br>';
        }

        $details .= '<div class="pro-act-btn">';
        if($page == 'dashboard') {
            $details .= '<a href="javascript:void(0)" class="" onclick="return hs.htmlExpand(this,{width:300,headingText:\'Contents\',wrapperClassName:\'titlebar\',showCredits:false});"><i class="ri-feedback-line fs-24 body-color"></i></a>';
        } else {
            $details .= '<a href="javascript:void(0)" class="" onclick="return hs.htmlExpand(this,{width:300,headingText:\'Contents\',wrapperClassName:\'titlebar\',showCredits:false});"><i class="ri-mail-open-line fs-16"></i></a>';
        }
        $details .= '<div class="highslide-maincontent">' . nl2br($label) . '</div>';
        $details .= '</div>';

        $Referdetails = '';
        if (!empty($value->varMessage)) {
            $Referdetails .= '<div class="pro-act-btn">';
            $Referdetails .= '<a href="javascript:void(0)" class="without_bg_icon" onclick="return hs.htmlExpand(this,{width:300,headingText:\'Message\',wrapperClassName:\'titlebar\',showCredits:false});"><i aria-hidden="true" class="ri-message-2-line fs-16"></i></a>';
            $Referdetails .= '<div class="highslide-maincontent">' . nl2br($value->varMessage) . '</div>';
            $Referdetails .= '</div>';
        } else {
            $Referdetails .= '-';
        }

        $receivedDate = '<span align="left" data-bs-toggle="tooltip" data-bs-placement="bottom" title="'.date(Config::get("Constant.DEFAULT_DATE_FORMAT").' '.Config::get("Constant.DEFAULT_TIME_FORMAT"), strtotime($value->created_at)).'">'.date(Config::get('Constant.DEFAULT_DATE_FORMAT'), strtotime($value->created_at)).'</span>';

        $records = array(
            $checkbox,
            $FullName,
            $Email,
            $ReferralFullNameme,
            $ReferralEmailId,
            $details,
            $Referdetails,
            $ipAdress,
            $receivedDate
        );
        return $records;
    }
}
