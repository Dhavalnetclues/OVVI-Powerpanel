<?php

namespace Powerpanel\GetdemoLead\Controllers\Powerpanel;

use App\Http\Controllers\PowerpanelController;
use Illuminate\Support\Facades\Redirect;
use Request;
use Excel;
use Powerpanel\GetdemoLead\Models\GetdemoLead;
use App\CommonModel;
use App\Helpers\MyLibrary;
use Powerpanel\GetdemoLead\Models\GetdemoExport;
use Config;

class GetdemoleadController extends PowerpanelController {

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
        $iTotalRecords = CommonModel::getRecordCount(false,false,false,'Powerpanel\GetdemoLead\Models\GetdemoLead');
        $this->breadcrumb['title'] = trans('getdemolead::template.getdemoleadModule.managegetdemolead');
        return view('getdemolead::powerpanel.list', ['iTotalRecords' => $iTotalRecords, 'breadcrumb' => $this->breadcrumb]);
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

        $arrResults = GetdemoLead::getRecordList($filterArr,$id);
        $iTotalRecords = GetdemoLead::getRecordCount($filterArr, true,'','',$id);
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
        $update = MyLibrary::deleteMultipleRecords($data,false,false,'Powerpanel\GetdemoLead\Models\getdemoLead');
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
        return Excel::download(new GetdemoLeadExport, Config::get('Constant.SITE_NAME') . '-' . trans("getdemo::template.getdemoModule.getdemoLeads") . '-' . date("dmy-h:i") . '.xlsx');
    }

    public function tableData($value) {
        $details = '-';
        $phoneNo = '-';
        $Visitfor = '-';
        $Satisfied = '-';

        // Checkbox
        $checkbox = view('powerpanel.partials.checkbox', ['name'=>'delete[]', 'value'=>$value->id])->render();

        $category = "";
        if (!empty($value->txtUserMessage)) {
            $details .= '<div class="pro-act-btn">';
            $details .= '<a href="javascript:void(0)" class="" onclick="return hs.htmlExpand(this,{width:300,headingText:\'Message\',wrapperClassName:\'titlebar\',showCredits:false});"><span aria-hidden="true" class="icon-envelope"></span></a>';
            $details .= '<div class="highslide-maincontent">' . nl2br($value->txtUserMessage) . '</div>';
            $details .= '</div>';
        } else {
            $details .= '-';
        }
        if (!empty($value->varVisitfor)) {
            $Visitfor .= '<div class="pro-act-btn">';
            $Visitfor .= '<a href="javascript:void(0)" class="" onclick="return hs.htmlExpand(this,{width:300,headingText:\'What was the reason for visit?\',wrapperClassName:\'titlebar\',showCredits:false});"><span aria-hidden="true" class="icon-envelope"></span></a>';
            $Visitfor .= '<div class="highslide-maincontent">' . nl2br($value->varVisitfor) . '</div>';
            $Visitfor .= '</div>';
        } else {
            $Visitfor .= '-';
        }



        if ($value->chrSatisfied != 'N') {
            if ($value->chrSatisfied == 'H') {
                $Satisfied = "Horrible";
            } elseif ($value->chrSatisfied == 'B') {
                $Satisfied = "Bad";
            } elseif ($value->chrSatisfied == 'J') {
                $Satisfied = "Just OK";
            } elseif ($value->chrSatisfied == 'G') {
                $Satisfied = "Good";
            } elseif ($value->chrSatisfied == 'S') {
                $Satisfied = "Super!";
            } else {
                $Satisfied = '-';
            }
        } else {
            $Satisfied = '-';
        }
        if ($value->chrCategory != '0') {
            if ($value->chrCategory == '1') {
                $category = "Suggestions";
            } elseif ($value->chrCategory == '2') {
                $category = "Issues/Bugs";
            } elseif ($value->chrCategory == '3') {
                $category = "Others";
            } else {
                $category = '-';
            }
        } else {
            $category = '-';
        }
        if (!empty($value->varPhoneNo)) {
            $phoneNo = MyLibrary::getDecryptedString($value->varPhoneNo);
        } else {
            $phoneNo = '-';
        }

				$ipAdress = (isset($value->varIpAddress) && !empty($value->varIpAddress)) ? $value->varIpAddress : "-";
        $receivedDate = '<span align="left" data-bs-toggle="tooltip" data-bs-placement="bottom" title="'.date(Config::get("Constant.DEFAULT_DATE_FORMAT").' '.Config::get("Constant.DEFAULT_TIME_FORMAT"), strtotime($value->created_at)).'">'.date(Config::get('Constant.DEFAULT_DATE_FORMAT'), strtotime($value->created_at)).'</span>';

        $records = array(
            $checkbox,
            $value->varName,
            MyLibrary::getDecryptedString($value->varEmail),
            $phoneNo,
            $Satisfied,
            $Visitfor,
            $category,
            $details,
            $ipAdress,
            $receivedDate
        );

        return $records;
    }

}
