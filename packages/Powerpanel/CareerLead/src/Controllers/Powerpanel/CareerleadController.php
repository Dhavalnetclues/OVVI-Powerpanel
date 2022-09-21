<?php
namespace Powerpanel\CareerLead\Controllers\Powerpanel;

use App\CommonModel;
use Powerpanel\CareerLead\Models\CareerLead;
use Powerpanel\Companies\Models\Companies;
use Powerpanel\CareerLead\Models\CareerLeadExport;
use App\Helpers\MyLibrary;
use App\Http\Controllers\PowerpanelController;
use Powerpanel\Services\Models\Services;
use Config;
use Excel;
use Request;

class CareerLeadController extends PowerpanelController
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
        $iTotalRecords = CommonModel::getRecordCount(false,false,false, 'Powerpanel\CareerLead\Models\CareerLead');
        $this->breadcrumb['title'] = trans('careerlead::template.careerleadModule.manageCareerLeads');
        return view('careerlead::powerpanel.list', ['iTotalRecords' => $iTotalRecords, 'breadcrumb' => $this->breadcrumb]);
    }

    public function get_list()
    {
        $filterArr = [];
        $records = [];
        $records["data"] = [];
        $filterArr['cmpId'] = !empty(Request::get('cmpId')) ? Request::get('cmpId') : '';
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
        
        $arrResults = CareerLead::getRecordList($filterArr);
            // echo '<pre>';print_r($arrResults);exit;

        $iTotalRecords = CommonModel::getRecordCount($filterArr, true,false, 'Powerpanel\CareerLead\Models\CareerLead');

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
        $update = MyLibrary::deleteMultipleRecords($data,false,false,'Powerpanel\CareerLead\Models\CareerLead');
        echo json_encode($update);
        exit;
    }

    /**
     * This method handels export process of Career leads
     * @return  xls file
     * @since   2016-10-18
     * @author  NetQuick
     */
    public function ExportRecord()
    {
        return Excel::download(new CareerLeadExport, 'OVVI - ' . trans("careerlead::template.careerleadModule.careerLeads") . '-' . date("dmy-h:i") . '.xlsx');

    }

    public function tableData($value){
        $checkbox = view('powerpanel.partials.checkbox', ['name'=>'delete[]', 'value'=>$value->id])->render();  
        $phoneNo = '';          
        $email = '';
        if (!empty($value->varEmail)) {
            $email = MyLibrary::decryptLatest($value->varEmail);
        } else {
            $email = '-';
        } 
        
        $doc = $value->varFile;
        $otherinfo = '';
        if (!empty($doc) && isset($doc)) {
            $docx = rtrim($doc, ",");
            $docName = explode(',', $docx);
            
            // $otherinfo .= '<div class="pro-act-btn">';
            // $otherinfo .= '<a href="javascript:void(0)" class="without_bg_icon" onclick="return hs.htmlExpand(this,{width:300,headingText:\'DOCUMENTS\',wrapperClassName:\'titlebar\',showCredits:false});"><span aria-hidden="true" class="fa fa-file"></span></a>';
            // $otherinfo .= '<div class="highslide-maincontent">';
            $count= count($docName);
            $i=1;
            $Url = "https://www.ovvihq.com/assets/career/" . $doc;
            $otherinfo .= '<p>'.'<a href="' . $Url. '" target="_blank" title="Download File" download class="without_bg_icon" ><span aria-hidden="true" class="fa fa-download">Download</span></a>' . '</p>';
          
            // $otherinfo .='</div>';
            // $otherinfo .='</div>';
        }else {
            $otherinfo = '-';
        }
        $details = '';
        if (!empty($value->varMessage)) {
            $details .= '<div class="pro-act-btn">';
            $details .= '<a href="javascript:void(0)" class="without_bg_icon" onclick="return hs.htmlExpand(this,{width:300,headingText:\'Message\',wrapperClassName:\'titlebar\',showCredits:false});"><i aria-hidden="true" class="ri-message-2-line fs-16"></i></a>';
            $details .= '<div class="highslide-maincontent">' . nl2br($value->varMessage) . '</div>';
            $details .= '</div>';
        } else {
            $details .= '-';
        }

        
        if (!empty($value->varPhoneNo)) {   
            $phoneNo = (MyLibrary::decryptLatest($value->varPhoneNo));
        } else {
            $phoneNo = '-';
        }
        // dd($value);
        $records = array(
            $checkbox,
            $value->varTitle,
            $email,
            $phoneNo,            
            $details,         
            $otherinfo ,
            $value->varPageName,
            $value->varIpAddress,
            date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($value->created_at)),
        );

        return $records;
    }
}

