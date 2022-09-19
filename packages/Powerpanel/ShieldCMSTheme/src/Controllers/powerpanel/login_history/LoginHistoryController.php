<?php
namespace Powerpanel\ShieldCMSTheme\Controllers\Powerpanel\login_history;

use App\Http\Controllers\PowerpanelController;
use Illuminate\Support\Facades\Redirect;
use Request;
use Excel;
use App\CommonModel;
use App\Http\Traits\slug;
use App\LoginLog;
use App\Helpers\MyLibrary;
use Session;
use Config;
use Auth;


class LoginHistoryController extends PowerpanelController {

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
        $iTotalRecords = LoginLog::getRecords()->deleted()->count();
        $this->breadcrumb['title'] = trans('Login History');


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
        return view('shiledcmstheme::powerpanel.login_history.list', ['iTotalRecords' => $iTotalRecords, 'breadcrumb' => $this->breadcrumb, 'settingarray' => $settingarray]);
        

    }

    /**
     * This method destroys Log in multiples
     * @return  Log index view
     * @since   2016-10-25
     * @author  NetQuick
     */
    public function DeleteRecord() {
        $data = Request::get('ids');
        $update = LoginLog::deleteRecordsPermanent($data);
        exit;
    }

    public function get_list() {
        $filterArr = [];
        $records = [];
        $records["data"] = [];
        $filterArr['orderColumnNo'] = (!empty(Request::get('order') [0]['column']) ? Request::get('order') [0]['column'] : '');
        $filterArr['orderByFieldName'] = (!empty(Request::get('columns') [$filterArr['orderColumnNo']]['name']) ? Request::get('columns') [$filterArr['orderColumnNo']]['name'] : '');
        $filterArr['orderTypeAscOrDesc'] = (!empty(Request::get('order') [0]['dir']) ? Request::get('order') [0]['dir'] : '');
        $filterArr['searchFilter'] = !empty(Request::get('searchValue')) ? Request::get('searchValue') : '';
        $filterArr['iDisplayLength'] = intval(Request::get('length'));
        $filterArr['iDisplayStart'] = intval(Request::get('start'));
        $sEcho = intval(Request::get('draw'));

        $userid = auth()->user()->id;
        if ($userid == 1) {
            $arrResults = LoginLog::getRecords()->deleted()->filter($filterArr)->get();
            $iTotalRecords = LoginLog::getRecords()->deleted()->filter($filterArr, true)->count();
        }else{
             $arrResults = LoginLog::getRecords()->where('fkIntUserId','!=','1')->deleted()->filter($filterArr)->get();
            $iTotalRecords = LoginLog::getRecords()->where('fkIntUserId','!=','1')->deleted()->filter($filterArr, true)->count();
        }


        $end = $filterArr['iDisplayStart'] + $filterArr['iDisplayLength'];
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;

        if (!empty($arrResults)) {
            foreach ($arrResults as $key => $value) {
                if (isset($value->user)) {
                    $records["data"][] = $this->tableData($value);
                }
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

    public function tableData($value) {
        $details = '';
        $phoneNo = '';
        $records = [];

        if (!empty($value->txtUserMessage)) {
            $details .= '<div class="pro-act-btn">';
            $details .= '<a href="javascript:void(0)" class="without_bg_icon" onclick="return hs.htmlExpand(this,{width:300,headingText:\'Message\',wrapperClassName:\'titlebar\',showCredits:false});"><span aria-hidden="true" class="icon-envelope"></span></a>';
            $details .= '<div class="highslide-maincontent">' . nl2br($value->txtUserMessage) . '</div>';
            $details .= '</div>';
        } else {
            $details .= '-';
        }
        if (null !== Session::get('loghistory_id') && (Session::get('loghistory_id') != "") && Session::get('loghistory_id') == $value->id) {
            $user_logouttime = "You are currently logged In";
            $user_log_delete_checkbox = '';
        } else {
            if (strtotime($value->updated_at) > strtotime($value->created_at)) {
                $user_logouttime = date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($value->updated_at->setTimezone(Config::get('Constant.DEFAULT_TIME_ZONE'))));
                $user_log_delete_checkbox = view('powerpanel.partials.checkbox', ['name'=>'delete', 'value'=>$value->id])->render();
            } else {
                $user_logouttime = "N/A";
                $user_log_delete_checkbox = view('powerpanel.partials.checkbox', ['name'=>'delete', 'value'=>$value->id])->render();
            }
        }


        $date = '<span align="left" data-bs-toggle="tooltip" data-bs-placement="bottom" title="'.date(Config::get("Constant.DEFAULT_DATE_FORMAT").' '.Config::get("Constant.DEFAULT_TIME_FORMAT"), strtotime($value->created_at->setTimezone(Config::get('Constant.DEFAULT_TIME_ZONE')))).'">'.date(Config::get('Constant.DEFAULT_DATE_FORMAT'), strtotime($value->created_at)).'</span>';

        $records = array(
            $user_log_delete_checkbox,
            '<img class="country-icon" src="' . $value->varCountry_flag . '" alt="' . $value->varCountry_name . '" title="' . $value->varCountry_name . '">',
            $value->varCountry_name,
            $value->user->name,
            MyLibrary::getDecryptedString($value->user->email),
            str_replace("::ffff:","",$value->varIpAddress),
            $date,
            $user_logouttime
        );
        return $records;
    }

}
