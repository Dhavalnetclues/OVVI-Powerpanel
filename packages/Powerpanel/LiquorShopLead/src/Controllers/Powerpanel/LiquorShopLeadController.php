<?php
namespace Powerpanel\LiquorShopLead\Controllers\Powerpanel;

use App\CommonModel;
use Powerpanel\LiquorShopLead\Models\LiquorShopLead;
use Powerpanel\LiquorShopLead\Models\LiquorShopLeadExport;
use App\Helpers\MyLibrary;
use App\Http\Controllers\PowerpanelController;
use Powerpanel\Services\Models\Services;
use Config;
use Excel;
use Request;

class LiquorShopLeadController extends PowerpanelController
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
        $iTotalRecords = CommonModel::getRecordCount(false,false,false, 'Powerpanel\LiquorShopLead\Models\LiquorShopLead');
        $this->breadcrumb['title'] = trans('liquorshoplead::template.liquorShopleadModule.manageLiquorShopLeads');

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

        return view('liquorshoplead::powerpanel.list', ['iTotalRecords' => $iTotalRecords, 'breadcrumb' => $this->breadcrumb, 'settingarray' => $settingarray]);
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
        $arrResults = LiquorShopLead::getRecordList($filterArr);
        $iTotalRecords = CommonModel::getRecordCount($filterArr, true,false, 'Powerpanel\LiquorShopLead\Models\LiquorShopLead');
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
        // print_r($data);die;
        $update = MyLibrary::deleteMultipleRecords($data,false,false,'Powerpanel\LiquorShopLead\Models\LiquorShopLead');
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
        return Excel::download(new LiquorShopLeadExport, 'OVVI -' . trans("liquorshoplead::template.liquorShopleadModule.LiquorShopLead") . '-' . date("dmy-h:i") . '.xlsx');

    }

    public function tableData($value)
    {
        // Checkbox
        $checkbox = view('powerpanel.partials.checkbox', ['name'=>'delete[]', 'value'=>$value->id])->render();
        
        $date = '<span align="left" data-bs-toggle="tooltip" data-bs-placement="bottom" title="'.date(Config::get("Constant.DEFAULT_DATE_FORMAT").' '.Config::get("Constant.DEFAULT_TIME_FORMAT"), strtotime($value->created_at)).'">'.date(Config::get('Constant.DEFAULT_DATE_FORMAT').' '.Config::get("Constant.DEFAULT_TIME_FORMAT").' ', strtotime($value->created_at)).'</span>';

        if (!empty($value->varOnEmailAddress)) {
            $varOnEmailAddress = MyLibrary::decryptLatest($value->varOnEmailAddress);
        } else {
            $varOnEmailAddress = '-';
        }
        $varRequestNumber = (isset($value->varRequestNumber) && !empty($value->varRequestNumber)) ? $value->varRequestNumber : "-"; 
        $interestedComponent = (isset($value->chrChooseComponent) && !empty($value->chrChooseComponent) && $value->chrChooseComponent==1) ? "Complete POS System" : "Software Only";
        $totalPOS = (isset($value->chrHowManyPOS) && !empty($value->chrHowManyPOS) && $value->chrHowManyPOS==1) ? "One POS" : "Two or more POS Systems";
        $zipCode = (isset($value->varOnZipCode) && !empty($value->varOnZipCode)) ?  MyLibrary::decryptLatest($value->varOnZipCode) : "";
        $varOnFirstName = (isset($value->varOnFirstName) && !empty($value->varOnFirstName)) ?  $value->varOnFirstName : "";
        $varOnLastName = (isset($value->varOnLastName) && !empty($value->varOnLastName)) ?  $value->varOnLastName : "";
        $varOnCompanyName = (isset($value->varOnCompanyName) && !empty($value->varOnCompanyName)) ?  $value->varOnCompanyName : "";
        $varOnPhoneNumber = (isset($value->varOnPhoneNumber) && !empty($value->varOnPhoneNumber)) ?  MyLibrary::decryptLatest($value->varOnPhoneNumber) : "";
        $ipAdress = (isset($value->varIpAddress) && !empty($value->varIpAddress)) ? $value->varIpAddress : "-";

        $records = array(
            $checkbox,
            $varRequestNumber,
            $interestedComponent,
            $totalPOS,
            $zipCode,
            $varOnEmailAddress,
            $varOnFirstName,
            $varOnLastName,
            $varOnCompanyName,
            $varOnPhoneNumber,
            $ipAdress,
            $date
        );

        return $records;
    }
}
