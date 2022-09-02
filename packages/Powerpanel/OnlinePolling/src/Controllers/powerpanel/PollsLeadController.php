<?php

namespace Powerpanel\OnlinePolling\Controllers\Powerpanel;

use App\CommonModel;
use Powerpanel\OnlinePolling\Models\PollLead;
use Powerpanel\Companies\Models\Companies;
use Powerpanel\OnlinePolling\Models\PollLeadExport;
use App\Helpers\MyLibrary;
use App\Http\Controllers\PowerpanelController;
use Powerpanel\Services\Models\Services;
use Config;
use Excel;
use Request;

class PollsLeadController extends PowerpanelController {

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

        $iTotalRecords = CommonModel::getRecordCount(false, false, false, 'Powerpanel\OnlinePolling\Models\PollLead');
        $this->breadcrumb['title'] = trans('polls::template.onlinepollingleadModule.manageOnlineLeads');
        return view('polls::powerpanel.list', ['iTotalRecords' => $iTotalRecords, 'breadcrumb' => $this->breadcrumb]);
    }

    public function get_list() {
        $filterArr = [];
        $records = [];
        $records["data"] = [];
        $filterArr['cmpId'] = !empty(Request::get('cmpId')) ? Request::get('cmpId') : '';
//     echo '<pre>';print_r($filterArr['cmpId']);exit;
        $filterArr['orderColumnNo'] = (!empty(Request::get('order')[0]['column']) ? Request::get('order')[0]['column'] : '');
        $filterArr['orderByFieldName'] = (!empty(Request::get('columns')[$filterArr['orderColumnNo']]['name']) ? Request::get('columns')[$filterArr['orderColumnNo']]['name'] : '');
        $filterArr['orderTypeAscOrDesc'] = (!empty(Request::get('order')[0]['dir']) ? Request::get('order')[0]['dir'] : '');
        $filterArr['searchFilter'] = !empty(Request::get('searchValue')) ? Request::get('searchValue') : '';
        // $filterArr['start'] = !empty(Request::get('rangeFilter')['from']) ? Request::get('rangeFilter')['from'] : '';
        // $filterArr['end'] = !empty(Request::get('rangeFilter')['to']) ? Request::get('rangeFilter')['to'] : '';
        $filterArr['iDisplayLength'] = intval(Request::get('length'));
        $filterArr['iDisplayStart'] = intval(Request::get('start'));
        $filterArr['rangeFilter'] = !empty(Request::input('rangeFilter')) ? Request::input('rangeFilter') : '';

        $sEcho = intval(Request::get('draw'));

        $arrResults = PollLead::getRecordList($filterArr);
        $iTotalRecords = CommonModel::getRecordCount($filterArr, true, false, 'Powerpanel\OnlinePolling\Models\PollLead');

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
        $update = MyLibrary::deleteMultipleRecords($data, false, false, 'Powerpanel\OnlinePolling\Models\PollLead');
        echo json_encode($update);
        exit;
    }

    /**
     * This method handels export process of Complaint leads
     * @return  xls file
     * @since   2016-10-18
     * @author  NetQuick
     */
    public function ExportRecord() {
        return Excel::download(new PollLeadExport, Config::get('Constant.SITE_NAME') . '-' . trans("polls::template.onlinepollingleadModule.OnlineLeads") . '-' . date("dmy-h:i") . '.xlsx');
    }

    public function tableData($value) {

        $details = '';
        $phoneNo = '';
        if (!empty($value->txtMessage)) {
            $details .= '<div class="pro-act-btn">';
            $details .= '<a href="javascript:void(0)" class="without_bg_icon" onclick="return hs.htmlExpand(this,{width:300,headingText:\'Message\',wrapperClassName:\'titlebar\',showCredits:false});"><span aria-hidden="true" class="icon-envelope"></span></a>';
            $details .= '<div class="highslide-maincontent">' . nl2br($value->txtMessage) . '</div>';
            $details .= '</div>';
        } else {
            $details .= '-';
        }




        $question = json_decode($value->txtQuestionData);

        $otherinfo = '';
        if (!empty($question) && isset($question)) {


            $otherinfo .= '<div class="pro-act-btn">';
            $otherinfo .= '<a href="javascript:void(0)" class="without_bg_icon" onclick="return hs.htmlExpand(this,{width:300,headingText:\'Form Details\',wrapperClassName:\'titlebar\',showCredits:false});"><span aria-hidden="true" class="fa fa-file"></span></a>';
            $otherinfo .= '<div class="highslide-maincontent">';

            foreach ($question as $que) {
                $otherinfo .= '<tr><td><strong> ' . $que->question . ' : </strong></td>';


                $ans = $que->answere;

                if (is_array($ans)) {
                    foreach ($ans as $answere) {
                        $otherinfo .= '<td>' . $answere . ' </td>' ;
                    }
                        $otherinfo .= '</td></tr>' . '<br>'.'<br>';
                }
                else{
                    $otherinfo .= '<td>' . $ans . ' </td>' . '</td></tr>' . '<br>' . '<br>';
                }
            }

            $otherinfo .= '</div>';
            $otherinfo .= '</div>';
        } else {
            $otherinfo = '-';
        }


        // dd($value);
        $records = array(
            '<input type="checkbox" name="delete[]" class="chkDelete form-check-input" value="' . $value->id . '">',
            $value->varTitle,
            $otherinfo,
            $details,
            date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($value->created_at)),
        );

        return $records;
    }

}
