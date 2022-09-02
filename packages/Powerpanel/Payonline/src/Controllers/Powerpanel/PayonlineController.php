<?php
namespace Powerpanel\Payonline\Controllers\Powerpanel;

use App\CommonModel;
use App\Helpers\MyLibrary;
use App\Http\Controllers\PowerpanelController;
use Config;
use Excel;
use Powerpanel\Payonline\Models\Payonline;
use Powerpanel\Payonline\Models\PayonlineExport;
use Request;

class PayonlineController extends PowerpanelController
{

    protected $paymentTypeList = array();
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
        $this->paymentTypeList[5] = 'Type Approval';
        $this->paymentTypeList[1] = 'Ship or Aircraft Radio License';
        $this->paymentTypeList[2] = 'Other Radio License';
        $this->paymentTypeList[3] = 'Fuel Operating or Import Permit';
        $this->paymentTypeList[4] = 'Other';
    }

    public function index()
    {
        $iTotalRecords = CommonModel::getRecordCount(false, false, false, 'Powerpanel\Payonline\Models\Payonline');
        $this->breadcrumb['title'] = trans('payonline::template.payonlineModule.manageLeads');
        return view('payonline::powerpanel.list', ['iTotalRecords' => $iTotalRecords, 'breadcrumb' => $this->breadcrumb]);
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

        $arrResults = Payonline::getRecordList($filterArr);
        $iTotalRecords = CommonModel::getRecordCount($filterArr, true, false, 'Powerpanel\Payonline\Models\Payonline');

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
        $update = MyLibrary::deleteMultipleRecords($data, false, false, 'Powerpanel\Payonline\Models\Payonline');
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
        return Excel::download(new PayonlineExport, Config::get('Constant.SITE_NAME') . '- Payment leads -' . date("dmyhis") . '.xlsx');
    }

    public function tableData($value)
    {

        $amount = $value->currency . ' $' . $value->amount;

        $details = '';
        $details .= '<div class="pro-act-btn"><a href="javascript:void(0)" class="" onclick="return hs.htmlExpand(this,{width:300,headingText:\'Details\',wrapperClassName:\'titlebar\',showCredits:false});"><span aria-hidden="true" class="icon-info"></span></a>';
        $details .= '<div class="highslide-maincontent">';
        $details .= '<ul>';

        if(!empty($value->companyName)){
            $details .= '<li>Company Name: '.$value->companyName.'</li>';
        }

        $details .= '<li>Payment for : '.$this->paymentTypeList[$value->paymentFor].'</li>';
        $details .= '<li>Payment Details : '.$value->description.'</li>';
        // $details .= '<li>Invoice No: '.$value->invoiceNo.'</li>';
        $details .= '<li>Amount: '.$amount.'</li>';
        $details .= '<li>Card Type: '.$value->cardType.' Card</li>';

        if(!empty($value->phone)) {
            $details .= '<li>Phone No: '.MyLibrary::getDecryptedString($value->phone).'</li>';
        }
        
        if(!empty($value->note)){
            $details .= '<li>Special Instructions: '.$value->note.'</li>';
        }
        
        $details .= '<ul>';
        $details .= '</div>';
        $details .= '</div>';

        $records = array(
            '<input type="checkbox" name="delete[]" class="form-check-input chkDelete" value="' . $value->id . '">',
            $value->name,
            MyLibrary::getDecryptedString($value->email),
            $value->txnId,
            $value->txnStatus,
            $details,
            date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($value->payment_date)),
        );

        return $records;
    }
}
