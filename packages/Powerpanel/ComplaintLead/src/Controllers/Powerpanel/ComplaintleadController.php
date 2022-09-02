<?php
namespace Powerpanel\ComplaintLead\Controllers\Powerpanel;

use App\CommonModel;
use Powerpanel\ComplaintLead\Models\ComplaintLead;
use Powerpanel\Companies\Models\Companies;
use Powerpanel\ComplaintLead\Models\ComplaintLeadExport;
use App\Helpers\MyLibrary;
use App\Http\Controllers\PowerpanelController;
use Powerpanel\Services\Models\Services;
use Config;
use Excel;
use Request;

class ComplaintLeadController extends PowerpanelController
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
        $iTotalRecords = CommonModel::getRecordCount(false,false,false, 'Powerpanel\ComplaintLead\Models\ComplaintLead');
        $this->breadcrumb['title'] = trans('complaintlead::template.complaintleadModule.manageComplaintLeads');
        return view('complaintlead::powerpanel.list', ['iTotalRecords' => $iTotalRecords, 'breadcrumb' => $this->breadcrumb]);
    }

    public function get_list()
    {
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

        $arrResults = ComplaintLead::getRecordList($filterArr);
        $iTotalRecords = CommonModel::getRecordCount($filterArr, true,false, 'Powerpanel\ComplaintLead\Models\ComplaintLead');

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
        $update = MyLibrary::deleteMultipleRecords($data,false,false,'Powerpanel\ComplaintLead\Models\ComplaintLead');
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
        return Excel::download(new ComplaintLeadExport, Config::get('Constant.SITE_NAME') . '-' . trans("complaintlead::template.complaintleadModule.complaintLeads") . '-' . date("dmy-h:i") . '.xlsx');

    }

    public function tableData($value)
    {
       
        
        $phoneNo = '';
       
        
        
       $companyname = Companies::getRecordById($value->fkIntCompanyId);
      
        
        $Company_response = '';
        if (!empty($value->company_response)) {
            $Company_response .= '<div class="pro-act-btn">';
            $Company_response .= '<a href="javascript:void(0)" class="without_bg_icon" onclick="return hs.htmlExpand(this,{width:300,headingText:\'Company Response\',wrapperClassName:\'titlebar\',showCredits:false});"><span aria-hidden="true" class="icon-envelope"></span></a>';
            $Company_response .= '<div class="highslide-maincontent">' . nl2br($value->company_response) . '</div>';
            $Company_response .= '</div>';
        } else {
            $Company_response .= '-';
        } 

        $email = '';
        if (!empty($value->varEmail)) {
            $email .= '<div class="pro-act-btn">';
            $email .= '<a href="javascript:void(0)" class="without_bg_icon" onclick="return hs.htmlExpand(this,{width:300,headingText:\'Email\',wrapperClassName:\'titlebar\',showCredits:false});"><span aria-hidden="true" class="icon-envelope"></span></a>';
            $email .= '<div class="highslide-maincontent">' . nl2br(MyLibrary::getDecryptedString($value->varEmail)) . '</div>';
            $email .= '</div>';
        } else {
            $email .= '-';
        } 
        
      
        
        $doc = $value->varFile;
        $otherinfo = '';
        if (!empty($doc) && isset($doc)) {
            $docx = rtrim($doc, ",");
            $docName = explode(',', $docx);
    
            $otherinfo .= '<div class="pro-act-btn">';
            $otherinfo .= '<a href="javascript:void(0)" class="without_bg_icon" onclick="return hs.htmlExpand(this,{width:300,headingText:\'DOCUMENTS\',wrapperClassName:\'titlebar\',showCredits:false});"><span aria-hidden="true" class="fa fa-file"></span></a>';
            $otherinfo .= '<div class="highslide-maincontent">';
            $count= count($docName);
            $i=1;
            foreach($docName as $docs){
                if($i<=$count)
                
                {
                    if (Config::get('Constant.BUCKET_ENABLED')) {
                        $Url = Config::get('Constant.CDN_PATH') . "complaint_documents/" . $docs;
                    } else {
                        $Url = url('/') . "/cdn/complaint_documents/" . $docs;
                    }
                    $otherinfo .= '<tr><td><strong>  File '.$i.':</strong></td><td>'.'<a href="' . $Url. '" title="Download File" download class="without_bg_icon" ><span aria-hidden="true" class="fa fa-download"></span></a>' . '</td></tr>'.'<br>'.'<br>';
                }
                $i++;
        } 
        
            $otherinfo .='</div>';
            $otherinfo .='</div>';
        }
        else {
            $otherinfo = '-';
        }
        
        if (!empty($value->varPhoneNo)) {
            $phoneNo = (MyLibrary::getDecryptedString($value->varPhoneNo));
        } else {
            $phoneNo = '-';
        }
        // dd($value);
        $records = array(
            '<input type="checkbox" name="delete[]" class="form-check-input chkDelete" value="' . $value->id . '">',
            $value->varTitle,
            $email,
            $value->varService ? $value->varService : "-" ,
           $companyname->varTitle ,
            date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ', strtotime($value->complaint_date)),
         
            $phoneNo,
            
            $Company_response,
            $otherinfo ,
            date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($value->created_at)),
        );

        return $records;
    }
}

