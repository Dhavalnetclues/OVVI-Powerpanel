<?php
namespace Powerpanel\CBDStoreLead\Controllers\Powerpanel;

use App\CommonModel;
use Powerpanel\CBDStoreLead\Models\CBDStoreLead;
use Powerpanel\CBDStoreLead\Models\CBDStoreLeadExport;
use App\Helpers\MyLibrary;
use App\Http\Controllers\PowerpanelController;
use Powerpanel\Services\Models\Services;
use App\Timezone;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Config;
use Excel;
use Request;

class CBDStoreLeadController extends PowerpanelController
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
        $this->MyLibrary = new MyLibrary();
        $this->CommonModel = new CommonModel();
    }

    public function index()
    {
        $iTotalRecords = CommonModel::getRecordCount(false,false,false, 'Powerpanel\CBDStoreLead\Models\CBDStoreLead');
        $this->breadcrumb['title'] = trans('cbdstorelead::template.cbdstoreLeadModule.manageCBDStoreLeads');

        if (method_exists($this->CommonModel, 'GridColumnData')) {
            $settingdata = CommonModel::GridColumnData(Config::get('Constant.MODULE.ID'));
            $settingarray = array();
            foreach ($settingdata as $sdata) {
                $settingarray[$sdata->chrtab][] = $sdata->columnid;
            }
        } else {
            $settingarray = '';
        }
        $settingarray = json_encode($settingarray);

        return view('cbdstorelead::powerpanel.list', ['iTotalRecords' => $iTotalRecords, 'breadcrumb' => $this->breadcrumb, 'settingarray' => $settingarray]);
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
        $sEcho = intval(Request::get('draw'));

        $arrResults = CBDStoreLead::getRecordList($filterArr);
        $iTotalRecords = CommonModel::getRecordCount($filterArr, true,false, 'Powerpanel\CBDStoreLead\Models\CBDStoreLead');
        // print_r($iTotalRecords);die;
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
        // dd($data);        
        $update = MyLibrary::deleteMultipleRecords($data,false,false,'Powerpanel\CBDStoreLead\Models\CBDStoreLead');

        echo json_encode($update);
        exit;
    }

    /**
     * This method handels export process of contact us leads
     * @return  xls file
     * @since   2016-10-18
     * @author  NetQuick
     */
    public function ExportRecord()
    {
        return Excel::download(new CBDStoreLeadExport, 'OVVI -' . trans("cbdstorelead::template.cbdstoreLeadModule.CBDStoreLeads") . '-' . date("dmy-h:i") . '.xlsx');

    }

    public function tableData($value)
    {
 
        // $now = Carbon::now('America/Chicago');
        // dd($now);
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
      
        // $date = '<span align="left" data-bs-toggle="tooltip" data-bs-placement="bottom" title="'.date(Config::get("Constant.DEFAULT_DATE_FORMAT").' '.Config::get("Constant.DEFAULT_TIME_FORMAT"), strtotime($value->dtCreateDate)).'">'.date(Config::get('Constant.DEFAULT_DATE_FORMAT').' '.Config::get("Constant.DEFAULT_TIME_FORMAT").' ', strtotime($value->dtCreateDate)).'</span>';
        $date = '<span align="left" data-bs-toggle="tooltip" data-bs-placement="bottom" title="'.MyLibrary::UTCToTimeZone($value->dtCreateDate, 'UTC', 'America/Chicago').'">'.MyLibrary::UTCToTimeZone($value->dtCreateDate, 'UTC', 'America/Chicago').'</span>';

        $phone = '';
        if (!empty($value->varPhoneNumber)) {
            $phone = MyLibrary::decryptLatest($value->varPhoneNumber);
        } else {
            $phone = '-';
        }
        
        $BusinessName = '';
        if (!empty($value->varBusinessName)) {
            $BusinessName = $value->varBusinessName;
        } else {
            $BusinessName = '-';
        }

        if (!empty($value->varEmailId)) {
            $LEmail = MyLibrary::decryptLatest($value->varEmailId);
        } else {
            $LEmail = '-';
        }

    
        $LTitle = (isset($value->varTitle) && !empty($value->varTitle)) ? $value->varTitle : "-"; 
        $ipAdress = (isset($value->varIpAddress) && !empty($value->varIpAddress)) ? $value->varIpAddress : "-";

        $records = array(
            $checkbox,
            $LTitle,
            $LEmail,
            $phone,
            $BusinessName,
            // $contacting_about,
            $details,
            $ipAdress,
            $date
        );

        return $records;
    }
}
