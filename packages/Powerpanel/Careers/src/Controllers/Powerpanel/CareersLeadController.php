<?php
namespace Powerpanel\Careers\Controllers\Powerpanel;

use App\CommonModel;
use Powerpanel\Careers\Models\CareerLead;
use Powerpanel\Careers\Models\Careers;
use Powerpanel\Careers\Models\CareerLeadExport;
use App\Helpers\MyLibrary;
use App\Http\Controllers\PowerpanelController;
use Config;
use Excel;
use Request;
use DB;

class CareersLeadController extends PowerpanelController
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
        $iTotalRecords = CommonModel::getRecordCount(false,false,false, 'Powerpanel\Careers\Models\CareerLead');
        $this->breadcrumb['title'] = trans('careers::template.common.manageCareersLeads');
        return view('careers::powerpanel.list', ['iTotalRecords' => $iTotalRecords, 'breadcrumb' => $this->breadcrumb]);
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
        // $filterArr['start'] = !empty(Request::get('rangeFilter')['from']) ? Request::get('rangeFilter')['from'] : '';
        // $filterArr['end'] = !empty(Request::get('rangeFilter')['to']) ? Request::get('rangeFilter')['to'] : '';
        $filterArr['iDisplayLength'] = intval(Request::get('length'));
        $filterArr['iDisplayStart'] = intval(Request::get('start'));
        $filterArr['rangeFilter'] = !empty(Request::input('rangeFilter')) ? Request::input('rangeFilter') : '';

        $sEcho = intval(Request::get('draw'));

        $arrResults = CareerLead::getRecordList($filterArr);
        $iTotalRecords = CommonModel::getRecordCount($filterArr, true,false, 'Powerpanel\Careers\Models\CareerLead');

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
        $update = MyLibrary::deleteMultipleRecords($data,false,false,'Powerpanel\Careers\Models\CareerLead');
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
        return Excel::download(new CareerLeadExport, Config::get('Constant.SITE_NAME') . '-' . trans("careers::template.common.careersLeads") . '-' . date("dmy-h:i") . '.xlsx');

    }

    public function tableData($value)
    {
        $careerDetail = Careers::getRecordById($value->careerId);

        $email = '';
        if (!empty($value->varEmail)) {
            $email .= '<div class="pro-act-btn">';
            $email .= '<a href="javascript:void(0)" class="without_bg_icon" onclick="return hs.htmlExpand(this,{width:300,headingText:\'Email\',wrapperClassName:\'titlebar\',showCredits:false});"><span aria-hidden="true" class="icon-envelope"></span></a>';
            $email .= '<div class="highslide-maincontent">' . nl2br(MyLibrary::getDecryptedString($value->varEmail)) . '</div>';
            $email .= '</div>';
        } else {
            $email .= '-';
        } 

        if (!empty($value->varPhoneNo)) {
            $phoneNo = (MyLibrary::getDecryptedString($value->varPhoneNo));
        } else {
            $phoneNo = '-';
        }

        $doc = $value->resume;
        $Document = '';
        if (!empty($doc) && isset($doc)) {
       
            if (Config::get('Constant.BUCKET_ENABLED')) {
                $Url = Config::get('Constant.CDN_PATH') . "career_documents/" . $doc;
            }else{
                $Url = url('/') . "/cdn/career_documents/" . $doc;
            }
            $Document .= '<tr><td><strong>  File '.':</strong></td><td>'.'<a href="' . $Url . '" title="Download File" download class="without_bg_icon" ><span aria-hidden="true" class="fa fa-download"></span></a>' . '</td></tr>'.'<br>'.'<br>';
        }

        $gender="";
        if($value->gender == "M"){
            $gender = "Male";
        }else{
            $gender = "Female";
        }

        $details = '';
        $details .= '<div class="pro-act-btn"><a href="javascript:void(0)" class="" onclick="return hs.htmlExpand(this,{width:300,headingText:\'Details\',wrapperClassName:\'titlebar\',showCredits:false});"><span aria-hidden="true" class="icon-info"></span></a>';
        $details .= '<div class="highslide-maincontent">';
        $details .= '<ul>';
        $details .= '<li>Address 1: '.$value->varAddress1.'</li>';
        if(!empty($value->varAddress2)){
            $details .= '<li>Address 2: '.$value->varAddress2.'</li>';
        }
        if(!empty($value->varCountry)){
            $details .= '<li>Country: '.$value->varCountry.'</li>';
        }
        if(!empty($value->varState)){
            $details .= '<li>State: '.$value->varState.'</li>';
        }
        if(!empty($value->varCity)){
            $details .= '<li>City: '.$value->varCity.'</li>';
        }
        if(!empty($value->varPostalCode)){
            $details .= '<li>Postal Code: '.$value->varPostalCode.'</li>';
        }
        if(!empty($value->dob)){
            $details .= '<li>Date Of Birth: '.date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ', strtotime($value->dob)).'</li>';
        }
        $details .= '<li>Gender: '.$gender	.'</li>';
        if(!empty($value->varImmigrationStatus)){
        $details .= '<li>Immigration Status: '.$value->varImmigrationStatus.'</li>';
        }
        if(!empty($value->varJobOpening)){
        $details .= '<li>Job Opening: '.$value->varJobOpening.'</li>';
        }
        if(!empty($value->varDescribeExp)){
        $details .= '<li>Describe Experience: '.$value->varDescribeExp.'</li>';
        }
        if(!empty($value->varReasonForChange)){
        $details .= '<li>Reason For Change: '.$value->varReasonForChange.'</li>';
        }
        if(!empty($value->varWhenToStart)){
        $details .= '<li>When To Start: '.$value->varWhenToStart.'</li>';
        }
        $details .= '<ul>';
        $details .= '</div>';
        $details .= '</div>';

        $ApplicationDate = '<span align="left" data-bs-toggle="tooltip" data-bs-placement="bottom" title="'.date(Config::get("Constant.DEFAULT_DATE_FORMAT").' '.Config::get("Constant.DEFAULT_TIME_FORMAT"), strtotime($value->dtCreateDate)).'">'.date(Config::get('Constant.DEFAULT_DATE_FORMAT').' '.Config::get("Constant.DEFAULT_TIME_FORMAT").' ', strtotime($value->dtCreateDate)).'</span>';
      
        $records = array(
            '<input type="checkbox" name="delete[]" class="chkDelete" value="' . $value->id . '">',
            $value->varTitle,
            $value->varLastName,
            $email,
            $careerDetail->varTitle,
            $phoneNo,
            $Document,
            $details,
            $ApplicationDate,
        );

        return $records;
    }
}

