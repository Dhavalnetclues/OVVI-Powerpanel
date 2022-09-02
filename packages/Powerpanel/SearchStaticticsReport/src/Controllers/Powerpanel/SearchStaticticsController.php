<?php

namespace Powerpanel\SearchStaticticsReport\Controllers\Powerpanel;

use App\Http\Controllers\PowerpanelController;
use Illuminate\Support\Facades\Redirect;
use Request;
use Excel;
use Powerpanel\SearchStaticticsReport\Models\SearchStatictics;
use App\CommonModel;
use Powerpanel\SearchStaticticsReport\Models\GlobalSearchRel;
use App\Helpers\MyLibrary;
use Config;

class SearchStaticticsController extends PowerpanelController {

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
        $this->CommonModel = new CommonModel();
    }

    public function index() {
        $iTotalRecords = CommonModel::getRecordCount(false,false,false,'Powerpanel\SearchStaticticsReport\Models\SearchStatictics');
        $this->breadcrumb['title'] = "Search Statistics";

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

        return view('searchstatictics::powerpanel.list', ['iTotalRecords' => $iTotalRecords, 'breadcrumb' => $this->breadcrumb, 'settingarray' => $settingarray]);
    }

    public function get_list() {
        $filterArr = [];
        $records = [];
        $records["data"] = [];
        $filterArr['orderColumnNo'] = (!empty(Request::get('order') [0]['column']) ? Request::get('order') [0]['column'] : '');
        $filterArr['orderByFieldName'] = (!empty(Request::get('columns') [$filterArr['orderColumnNo']]['name']) ? Request::get('columns') [$filterArr['orderColumnNo']]['name'] : '');
        $filterArr['orderTypeAscOrDesc'] = (!empty(Request::get('order') [0]['dir']) ? Request::get('order') [0]['dir'] : '');
        $filterArr['searchFilter'] = !empty(Request::get('searchValue')) ? Request::get('searchValue') : '';
        $filterArr['yearFilter'] = !empty(Request::get('yearValue')) ? Request::get('yearValue') : '';
        $filterArr['monthFilter'] = !empty(Request::get('monthValue')) ? Request::get('monthValue') : '';
        $filterArr['iDisplayLength'] = intval(Request::get('length'));
        $filterArr['iDisplayStart'] = intval(Request::get('start'));
        $sEcho = intval(Request::get('draw'));

        $arrResults = SearchStatictics::getRecordList($filterArr);
        $iTotalRecords = SearchStatictics::getRecordCount($filterArr, true,false,'Powerpanel\SearchStaticticsReport\Models\SearchStatictics');
        $iTotalRecords = count($iTotalRecords);
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
        GlobalSearchRel::deleteGlobalsearches_rel($data['ids']);
        $update = MyLibrary::deleteMultipleRecords($data,false,false,'Powerpanel\SearchStaticticsReport\Models\SearchStatictics');
        echo json_encode($update);
        exit;
    }

    public function tableData($value) {

        // Checkbox
        $checkbox = view('powerpanel.partials.checkbox', ['name'=>'delete', 'value'=>$value->id])->render();

        $records = array(
            $checkbox,
            $value->varTitle,
            $value->counter,
            date('M', strtotime($value->createDate)),
            date('Y', strtotime($value->createDate)),
        );

        return $records;
    }

}
