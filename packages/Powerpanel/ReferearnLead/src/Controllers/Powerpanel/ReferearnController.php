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
        return Excel::download(new ReferearnLeadExport, Config::get('Constant.SITE_NAME') . '-' . trans("referearn::template.referearnsModule.newslettersLeads") . '-' . date("dmy-h:i") . '.xlsx');
    }

    public function tableData($value)
    {

        // Checkbox
        $checkbox = view('powerpanel.partials.checkbox', ['name'=>'delete[]', 'value'=>$value->id])->render();

        $name = '';
        if (!empty($value->varName)) {
            $name .= $value->varName;
        } else {
            $name .= '-';
        }

        if (!empty($value->varEmail)) {
            $LEmail = MyLibrary::getDecryptedString($value->varEmail);
        } else {
            $LEmail = '-';
        }
        $isSubscribed = (isset($value->chrSubscribed) && !empty($value->chrSubscribed)) ? $value->chrSubscribed : "N";
        $ipAdress = (isset($value->varIpAddress) && !empty($value->varIpAddress)) ? $value->varIpAddress : "-";


        $receivedDate = '<span align="left" data-bs-toggle="tooltip" data-bs-placement="bottom" title="'.date(Config::get("Constant.DEFAULT_DATE_FORMAT").' '.Config::get("Constant.DEFAULT_TIME_FORMAT"), strtotime($value->created_at)).'">'.date(Config::get('Constant.DEFAULT_DATE_FORMAT'), strtotime($value->created_at)).'</span>';

        $records = array(
            $checkbox,
            $LEmail,
            $isSubscribed,
            $ipAdress,
            $receivedDate
        );
        return $records;
    }
}
