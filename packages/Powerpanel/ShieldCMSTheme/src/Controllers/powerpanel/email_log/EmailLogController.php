<?php

namespace Powerpanel\ShieldCMSTheme\Controllers\Powerpanel\email_log;

use Request;
use App\EmailType;
use Powerpanel\ShieldCMSTheme\Models\EmailLog;
use App\Http\Controllers\PowerpanelController;
use App\Helpers\MyLibrary;
use Config;
use App\CommonModel;

class EmailLogController extends PowerpanelController {

    /**
     * Create a new controller instance.
     * @return void
     */
    public function __construct() {
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
    public function index() {

        $total = CommonModel::getRecordCount(false,false,false, 'Powerpanel\ShieldCMSTheme\Models\EmailLog');
        $emailTypes = $total > 0 ? EmailType::getEmailTypes() : null;
        $this->breadcrumb['title'] = trans('shiledcmstheme::template.emailLogModule.manage');

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

        return view('shiledcmstheme::powerpanel.email_log.email_log', ['emailTypes' => $emailTypes, 'iTotalRecords' => $total, 'breadcrumb' => $this->breadcrumb, 'settingarray' => $settingarray]);
    }

    /**
     * This method handels list of emailLog with filters
     * @return  View
     * @since   2017-07-20
     * @author  NetQuick
     */
    public function get_list() {
        /* Start code for sorting */
        $filterArr = [];
        $records = array();
        $records["data"] = array();
        $filterArr['orderColumnNo'] = (!empty(Request::get('order') [0]['column']) ? Request::get('order') [0]['column'] : '');
        $filterArr['orderByFieldName'] = (!empty(Request::get('columns') [$filterArr['orderColumnNo']]['name']) ? Request::get('columns') [$filterArr['orderColumnNo']]['name'] : '');
        $filterArr['orderTypeAscOrDesc'] = (!empty(Request::get('order') [0]['dir']) ? Request::get('order') [0]['dir'] : '');
        $filterArr['searchFilter'] = !empty(Request::get('searchValue')) ? Request::get('searchValue') : '';
        $filterArr['emailtypeFilter'] = !empty(Request::get('emailtypeValue')) ? Request::get('emailtypeValue') : '';
        $filterArr['iDisplayLength'] = intval(Request::get('length'));
        $filterArr['iDisplayStart'] = intval(Request::get('start'));
        $sEcho = intval(Request::get('draw'));
        $arrResults = EmailLog::getRecordList($filterArr);
        $iTotalRecords = CommonModel::getRecordCount($filterArr, true,false, 'Powerpanel\ShieldCMSTheme\Models\EmailLog');
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
     * This method destroys EmailLog in multiples
     * @return  EmailLog index view
     * @since   2016-10-25
     * @author  NetQuick
     */
    public function DeleteRecord() {
        $data = Request::get('ids');
        $update = EmailLog::deleteRecordsPermanent($data);
        exit;
    }

    public function tableData($value) {

        if ($value->emailType->varEmailType == 'Project Approved') {
            $to = '<label title="' . str_replace("</br>", "\n", $value->txtTo) . '">' . trans('shiledcmstheme::template.emailLogModule.subscriberGroup') . '</label>';
        } else {
            $to = $value->txtTo;
        }

        $checkbox = view('powerpanel.partials.checkbox', ['name'=>'delete', 'value'=>$value->id])->render();

        $date = '<span align="left" data-bs-toggle="tooltip" data-bs-placement="bottom" title="'.date(Config::get("Constant.DEFAULT_DATE_FORMAT").' '.Config::get("Constant.DEFAULT_TIME_FORMAT"), strtotime($value->created_at)).'">'.date(Config::get('Constant.DEFAULT_DATE_FORMAT'), strtotime($value->created_at)).'</span>';

        $records = array(
            $checkbox,
            $value->emailType->varEmailType,
            Mylibrary::getLaravelDecryptedString($value->varFrom),
            Mylibrary::getLaravelDecryptedString($to),
            strtoupper($value->chrIsSent),
            $value->chrAttachment,
            $date
        );
        return $records;
    }

    public function ajax() {
        $emaillogpage_id = Request::get('emaillogpage_id');
        if ($emaillogpage_id > 0) {
            $emailpageID = $emaillogpage_id;
            $emailLogPageRecord = EmailLog::getRecordById($emailpageID);
            $emailArr = [];
            if (!empty($emailLogPageRecord) && count($emailLogPageRecord) > 0) {
                $emailArr['txt_subject'] = $emailLogPageRecord->txtSubject;
                $emailArr['txt_to'] = $emailLogPageRecord->txtTo;
                $emailArr['date'] = date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($emailLogPageRecord->created_at));
            }
            echo json_encode($emailArr);
        }
    }

}
