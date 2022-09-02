<?php
namespace Powerpanel\NotificationList\Controllers\Powerpanel;

use App\Http\Controllers\PowerpanelController;
use Illuminate\Support\Facades\Redirect;
use Request;
use Excel;
use Session;
use Auth;
use App\Department;
use App\Modules;
use Powerpanel\CmsPage\Models\CmsPage;
use Powerpanel\NotificationList\Models\NotificationList;
use App\CommonModel;
use App\Helpers\MyLibrary;
use Config;
use App\UserNotification;
use App\Helpers\Email_sender;
use Illuminate\Support\Facades\Validator;

class NotificationListController extends PowerpanelController {

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
    }

    public function index() {
    	$currentUserAccessibleModulesIDs = array();
        if (!empty($this->currentUserAccessibleModules)) {
            foreach ($this->currentUserAccessibleModules as $moduledata) {
                array_push($currentUserAccessibleModulesIDs, $moduledata['id']);
            }
        }

        $userIsAdmin = false;
        if (null !== Session::get('USERROLEDATA') && null != Auth::check()) {
            $this->currentUserRoleData = Session::get('USERROLEDATA');
            if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                $userIsAdmin = true;
            }
        }
        $iTotalRecords = NotificationList::getNotificationRecordList(false,$userIsAdmin,$currentUserAccessibleModulesIDs,true);
        $this->breadcrumb['title'] = trans('notificationlist::template.notificationlistModule.managenotificationlist');
        return view('notificationlist::powerpanel.list', ['iTotalRecords' => $iTotalRecords, 'breadcrumb' => $this->breadcrumb]);
    }

    public function get_list() {
    	$currentUserAccessibleModulesIDs = array();
        if (!empty($this->currentUserAccessibleModules)) {
            foreach ($this->currentUserAccessibleModules as $moduledata) {
                array_push($currentUserAccessibleModulesIDs, $moduledata['id']);
            }
        }

        $userIsAdmin = false;
        if (null !== Session::get('USERROLEDATA') && null != Auth::check()) {
            $this->currentUserRoleData = Session::get('USERROLEDATA');
            if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                $userIsAdmin = true;
            }
        }

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
        $arrResults = NotificationList::getNotificationRecordList($filterArr,$userIsAdmin,$currentUserAccessibleModulesIDs);
        $iTotalRecords = NotificationList::getNotificationRecordList($filterArr,$userIsAdmin,$currentUserAccessibleModulesIDs,true);
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
    		$value = Request::input('value');
        $data = Request::all('ids');
        $update = MyLibrary::deleteMultipleRecords($data,false,$value);
        UserNotification::deleteReadNotificationByIDs($data['ids']);
        echo json_encode($update);
        exit;
    }

    /**
     * This method handels export process of notificationlist us leads
     * @return  xls file
     * @since   2016-10-18
     * @author  NetQuick
     */
    public function ExportRecord() {
        if (Request::get('export_type') == 'selected_records') {
            $selectedIds = array();
            if (null !== Request::get('delete')) {
                $selectedIds = Request::get('delete');
            }

            $arrResults = NotificationList::getListForExport($selectedIds);
        } else {

            $arrResults = NotificationList::getListForExport();
        }

        if (count($arrResults) > 0) {
            foreach ($arrResults as $key => $value) {

                $moduledata = Modules::getModuleById($value->fkIntModuleId);
                $model = '\\App\\' . $moduledata->varModelName;
                $moduleRec = $model::getRecordById($value->fkRecordId);

                if (isset($moduleRec->varTitle) && !empty($moduleRec->varTitle)) {
                    $moduleRecord = $moduleRec->varTitle;
                } else {
                    $moduleRecord = '-';
                }
                if (isset($moduledata->varTitle) && !empty($moduledata->varTitle)) {
                    $moduleTitle = $moduledata->varTitle;
                } else {
                    $moduleTitle = '-';
                }
                $txtNotification = '-';
                if (!empty($value->txtNotification)) {
                    $txtNotification = $value->txtNotification;
                }

                $data[] = [
                    $moduleRecord,
                    $moduleTitle,
                    $txtNotification,
                    $value->varIpAddress,
                    date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($value->created_at))
                ];
            }

            $this->createNotificationListExcel($data);
        }
    }

    /**
     * This method create notificationlist lead excel sheet
     * @return  xls file
     * @since   2016-10-18
     * @author  NetQuick
     */
    public function createNotificationListExcel($data) {

        Excel::create(Config::get('Constant.SITE_NAME') . '-' . trans("notificationlist::template.notificationlistModule.notificationlist") . '-' . date("dmy-h:i"), function($excel) use($data) {
            $excel->sheet(date('M-d-Y'), function($sheet) use($data) {
                $sheet->setAutoSize(true);
                $sheet->fromArray($data);
                $sheet->row(1, array(
                    trans('Record Name'),
                    trans('Module Name'),
                    trans('Message'),
                    trans('notificationlist::template.notificationlistModule.ipAddress'),
                    trans('notificationlist::template.notificationlistModule.receivedDateTime')
                ));

                $sheet->prependRow(array(
                    Config::get('Constant.SITE_NAME') . ' ' . trans("notificationlist::template.notificationlistModule.notificationlist")
                ));

                $sheet->mergeCells('A1:F1');
                $sheet->row(1, function($row) {
                    $row->setAlignment('center');
                    $row->setFontWeight('bold');
                    $row->setFontSize(12);
                });
                $sheet->row(2, function($row) {
                    $row->setAlignment('center');
                    $row->setFontWeight('bold');
                    $row->setFontSize(12);
                });
            });
        })->download('xls');
    }

    public function tableData($value) {

        $moduledata = Modules::getModuleById($value->fkIntModuleId);
        if (isset($moduledata->varModuleNameSpace) && $moduledata->varModuleNameSpace != '') {
            $model = $moduledata->varModuleNameSpace . 'Models\\' . $moduledata->varModelName;
        } else {
            $model = '\\App\\' . $moduledata->varModelName;
        }

        $moduleRec = $model::getRecordById($value->fkRecordId);

        if (isset($moduleRec->varTitle) && !empty($moduleRec->varTitle)) {
            $moduleRecord = $moduleRec->varTitle;
        } else {
            $moduleRecord = 'N/A';
        }

        $details = '';
        if (!empty($value->txtNotification)) {
            $details .= '<div class="pro-act-btn">';
            $details .= '<a href="javascript:void(0)" class="" onclick="return hs.htmlExpand(this,{width:300,headingText:\'Message\',wrapperClassName:\'titlebar\',showCredits:false});"><span aria-hidden="true" class="icon-envelope"></span></a>';
            $details .= '<div class="highslide-maincontent">' . nl2br($value->txtNotification) . '</div>';
            $details .= '</div>';
        } else {
            $details .= '-';
        }

        // Checkbox
        $checkbox = view('powerpanel.partials.checkbox', ['name'=>'delete', 'value'=>$value->id])->render();


        // date
        $date = $value->created_at;
        $date = '<span align="left" data-bs-toggle="tooltip" data-bs-placement="bottom" title="'.date(Config::get("Constant.DEFAULT_DATE_FORMAT").' '.Config::get("Constant.DEFAULT_TIME_FORMAT"), strtotime($date)).'">'.date(Config::get('Constant.DEFAULT_DATE_FORMAT'), strtotime($date)).'</span>';

        $records = array(
            $checkbox,
            '<div class="pages_title_div_row">'. $moduleRecord.'</div>',
            $moduledata->varTitle,
            $details,
            $date
        );

        return $records;
    }
}
