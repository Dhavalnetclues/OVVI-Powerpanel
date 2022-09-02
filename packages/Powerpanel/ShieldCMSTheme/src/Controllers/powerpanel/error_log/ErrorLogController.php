<?php

namespace Powerpanel\ShieldCMSTheme\Controllers\Powerpanel\error_log;

use App\CommonModel;
use App\Http\Controllers\PowerpanelController;
use Config;
use Powerpanel\ShieldCMSTheme\Models\ErrorLog;
use Request;

class ErrorLogController extends PowerpanelController
{

    /**
     * Create a new controller instance.
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        if (isset($_COOKIE['locale'])) {
            app()->setLocale($_COOKIE['locale']);
        }
        $this->CommonModel = new CommonModel();
    }

    /**
     * This method handels load emailLog grid
     * @return  View
     * @since   2017-07-20
     * @author  NetQuick
     */
    public function index()
    {

        $total = CommonModel::getRecordCount(false, false, false, 'Powerpanel\ShieldCMSTheme\Models\ErrorLog');
        $this->breadcrumb['title'] = 'Error Logs';
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
        return view('shiledcmstheme::powerpanel.error_log.error_log', ['iTotalRecords' => $total, 'breadcrumb' => $this->breadcrumb, 'settingarray' => $settingarray]);

    }

    /**
     * This method handels list of emailLog with filters
     * @return  View
     * @since   2017-07-20
     * @author  NetQuick
     */
    public function get_list()
    {
        /* Start code for sorting */
        $filterArr = [];
        $records = array();
        $records["data"] = array();
        $filterArr['orderColumnNo'] = (!empty(Request::get('order')[0]['column']) ? Request::get('order')[0]['column'] : '');
        $filterArr['orderByFieldName'] = (!empty(Request::get('columns')[$filterArr['orderColumnNo']]['name']) ? Request::get('columns')[$filterArr['orderColumnNo']]['name'] : '');
        $filterArr['orderTypeAscOrDesc'] = (!empty(Request::get('order')[0]['dir']) ? Request::get('order')[0]['dir'] : '');
        $filterArr['searchFilter'] = !empty(Request::get('searchValue')) ? Request::get('searchValue') : '';
        $filterArr['emailtypeFilter'] = !empty(Request::get('emailtypeValue')) ? Request::get('emailtypeValue') : '';
        $filterArr['iDisplayLength'] = intval(Request::get('length'));
        $filterArr['iDisplayStart'] = intval(Request::get('start'));
        $sEcho = intval(Request::get('draw'));
        $arrResults = ErrorLog::getRecordList($filterArr);
        $iTotalRecords = CommonModel::getRecordCount($filterArr, true, false, 'Powerpanel\ShieldCMSTheme\Models\ErrorLog');
        $end = $filterArr['iDisplayStart'] + $filterArr['iDisplayLength'];
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;

        if (count($arrResults) > 0 && !empty($arrResults)) {
            foreach ($arrResults as $key => $value) {
                $records["data"][] = $this->tableData($value);
            }
        }
        $records["customActionStatus"] = "OK";
        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;
        echo json_encode($records);
        exit;
    }

    /**
     * This method handels list of emailLog with filters
     * @return  View
     * @since   2017-07-20
     * @author  NetQuick
     */
    public function view($id)
    {
        /* Start code for sorting */
        $errorLogObj = ErrorLog::where('id', $id)->first();
        $content = $errorLogObj->txtErrorTemplate;
        return view('shiledcmstheme::powerpanel.error_log.error_log_view', ['content' => $content]);
    }

    /**
     * This method destroys EmailLog in multiples
     * @return  EmailLog index view
     * @since   2016-10-25
     * @author  NetQuick
     */
    public function DeleteRecord() {
        $data = Request::get('ids');
        $update = ErrorLog::deleteRecordsPermanent($data);
        exit;
    }

    public function tableData($value)
    {
        $details = '';

        $desc = '';
        if (strlen($value->txtErrorTemplate) > 0) {
            $desc .= '<a target="_blank" style="display: inline-block;vertical-align: sub;margin-left: 15px;" href="' . route('error_log_view', ['id' => $value->id]) . '" class="without_bg_icon">View Log Details</a>';
        } else {
            $desc .= '---';
        }

        $checkbox = view('powerpanel.partials.checkbox', ['name'=>'delete', 'value'=>$value->id])->render();

        $records = array(
            $checkbox,
            '<div class="pages_title_div_row"> <span class="title-txt">' . $value->varTitle . '</span></div>',
            $desc,
            date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($value->created_at)),
            $value->varIpAddress,
        );
        return $records;
    }

}
