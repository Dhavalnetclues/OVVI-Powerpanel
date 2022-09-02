<?php

namespace Powerpanel\Organizations\Controllers\Powerpanel;

use App\Http\Controllers\PowerpanelController;
use Carbon\Carbon;
use Powerpanel\Organizations\Models\Organizations;
use Powerpanel\Workflow\Models\WorkflowLog;
use Powerpanel\Workflow\Models\Workflow;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Validator;
use DB;
use File;
use App\Log;
use App\User;
use App\RecentUpdates;
use App\CommonModel;
use App\Helpers\MyLibrary;
use App\Helpers\Category_builder;
use App\Helpers\ParentRecordHierarchy_builder;
use App\Helpers\AddCategoryAjax;
use Auth;
use Cache;
use Config;
use Powerpanel\Workflow\Models\Comments;
use App\Modules;
use Powerpanel\RoleManager\Models\Role_user;
use App\UserNotification;

class OrganizationsController extends PowerpanelController {

    public function __construct() {
        parent::__construct();
        if (isset($_COOKIE['locale'])) {
            app()->setLocale($_COOKIE['locale']);
        }
        $this->MyLibrary = new MyLibrary();
        $this->CommonModel = new CommonModel();
    }

    /**
     * This method handels load process of Organization
     * @return  View
     * @since   2017-11-10
     * @author  NetQuick
     */
    public function index() {
        $userIsAdmin = false;
        if (isset($this->currentUserRoleData) && !empty($this->currentUserRoleData)) {
            if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                $userIsAdmin = true;
            }
        }else{
            $userIsAdmin = true;
        }
        $iTotalRecords = Organizations::getRecordCount();
        $NewRecordsCount = Organizations::getNewRecordsCount();
        $this->breadcrumb['title'] = trans('organizations::template.organizationsModule.manageOrganizations');
        $breadcrumb = $this->breadcrumb;
        /* code for getting chart for parent categories */
        $organizationData = Organizations::getRecordsForChart();
        $orgdata = array();
        if (!empty($organizationData) && count($organizationData) > 0) {
            foreach ($organizationData as $orgnization) {
                $ogData = array();
                $tempData = array();
                $tempData['v'] = (String) $orgnization->id;
                $tempData['f'] = $orgnization->varTitle;
                $ogData[] = $tempData;
                if ($orgnization->intParentCategoryId > 0) {
                    array_push($ogData, (String) $orgnization->intParentCategoryId);
                } else {
                    array_push($ogData, null);
                }
                array_push($ogData, $orgnization->varTitle);
                $orgdata[] = $ogData;
            }
        }
        $orgdata = json_encode($orgdata);
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
        return view('organizations::powerpanel.index', compact('NewRecordsCount', 'iTotalRecords', 'breadcrumb', 'userIsAdmin', 'orgdata', 'settingarray'));
    }

    /**
     * This method stores Organization modifications
     * @return  View
     * @since   2017-11-10
     * @author  NetQuick
     */
    public function handlePost(Request $request) {
        $userIsAdmin = false;
        if (!empty($this->currentUserRoleData)) {
            if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                $userIsAdmin = true;
            }
        }
        $data = Request::input();
        $rules = array(
            'title' => 'required|max:160|handle_xss|no_url',
            'display_order' => 'required|greater_than_zero|handle_xss|no_url',
            'Designation' => 'handle_xss|no_url'
        );
        $messsages = array(
            'title.required' => 'Title field is required.',
            'display_order.required' => trans('organizations::template.organizationsModule.displayOrder'),
            'display_order.greater_than_zero' => trans('organizations::template.organizationsModule.displayGreaterThan')
        );
        $validator = Validator::make($data, $rules, $messsages);
        if ($validator->passes()) {
            $OrganizationArr = [];
            $module = Modules::getModuleById(Config::get('Constant.MODULE.ID'));
            $id = Request::segment(3);
            $actionMessage = trans('organizations::template.common.oppsSomethingWrong');
            if (is_numeric($id)) { #Edit post Handler=======
                $Organization = Organizations::getRecordForLogById($id);
                if ($Organization->chrLock == 'Y' && auth()->user()->id != $Organization->LockUserID) {
                    if ($this->currentUserRoleData->chrIsAdmin != 'Y') {
                        $lockedUserData = User::getRecordById($Organization->LockUserID, true);
                        $lockedUserName = 'someone';
                        if (!empty($lockedUserData)) {
                            $lockedUserName = $lockedUserData->name;
                        }
                        $actionMessage = "This record has been locked by " . $lockedUserName . ".";
                        return redirect()->route('powerpanel.organizations.index')->with('message', $actionMessage);
                    }
                }
                if(Config::get('Constant.CHRSearchRank') == 'Y'){
                    $serachrank = $data['search_rank'];
                }
                $updateOrganizationFields = [
                    'varTitle' => stripslashes(trim($data['title'])),
                    'intParentCategoryId' => $data['parent_category_id'],
                    'chrPublish' => isset($data['chrMenuDisplay']) ? $data['chrMenuDisplay'] : 'Y',
                    'varDesignation' => $data['Designation'],
                    'intSearchRank' => $serachrank,
                ];
                $whereConditions = ['id' => $Organization->id];
                 if (File::exists(app_path() . '/Workflow.php') != null || File::exists(base_path() . '/packages/Powerpanel/Workflow/src/Models/Workflow.php') != null) {
                    if (!$userIsAdmin) {
                        $userRole = $this->currentUserRoleData->id;
                    } else {
                        $userRoleData = Role_user::getUserRoleByUserId($Organization->UserID);
                        if (isset($userRoleData->role_id)) {
                            $userRole = $userRoleData->role_id;
                        } else {
                            $userRole = $this->currentUserRoleData->id;
                        }
                    }
                    $workFlowByCat = Workflow::getRecordByCategoryId($module->intFkGroupCode, $userRole, Config::get('Constant.MODULE.ID'));
                    if (empty($workFlowByCat->varUserId) || $userIsAdmin || $workFlowByCat->charNeedApproval == 'N') {

                        if ((int) $Organization->fkMainRecord === 0 || empty($workFlowByCat->varUserId)) {
                            $update = CommonModel::updateRecords($whereConditions, $updateOrganizationFields, false, 'Powerpanel\Organizations\Models\Organizations');
                            if ($update) {
                                if (!empty($id)) {
                                    $addlog = '';
                                    self::newSwapOrderEdit($data['display_order'], $Organization);
                                    $logArr = MyLibrary::logData($Organization->id, false, $addlog);
                                    if (Auth::user()->can('log-advanced')) {
                                        $newOrganizationObj = Organizations::getRecordForLogById($Organization->id);
                                        $oldRec = $this->recordHistory($Organization);
                                        $newRec = $this->newrecordHistory($Organization, $newOrganizationObj);
                                        $logArr['old_val'] = $oldRec;
                                        $logArr['new_val'] = $newRec;
                                    }
                                    $logArr['varTitle'] = trim($data['title']);
                                    Log::recordLog($logArr);
                                    if (Auth::user()->can('recent-updates-list')) {
                                        if (!isset($newOrganizationObj)) {
                                            $newOrganizationObj = Organizations::getRecordForLogById($Organization->id);
                                        }
                                        $notificationArr = MyLibrary::notificationData($Organization->id, $newOrganizationObj);
                                        RecentUpdates::setNotification($notificationArr);
                                    }
                                    if (isset($data['saveandexit']) && $data['saveandexit'] == 'approvesaveandexit') {
                                        $actionMessage = trans('organizations::template.common.recordApprovalMessage');
                                    } else {
                                        $actionMessage = trans('organizations::template.organizationsModule.successMessage');
                                    }
                                }
                            }
                        } else {
                            $updateModuleFields = $updateOrganizationFields;
                            $this->insertApprovedRecord($updateModuleFields, $data, $id);
                            if (isset($data['saveandexit']) && $data['saveandexit'] == 'approvesaveandexit') {
                                $actionMessage = trans('organizations::template.common.recordApprovalMessage');
                            } else {
                                $actionMessage = trans('organizations::template.organizationsModule.successMessage');
                            }
                        }
                    } else {
                        if ($workFlowByCat->charNeedApproval == 'Y') {
                            $this->insertApprovalRecord($Organization, $data);
                            if (isset($data['saveandexit']) && $data['saveandexit'] == 'approvesaveandexit') {
                                $actionMessage = trans('organizations::template.common.recordApprovalMessage');
                            } else {
                                $actionMessage = trans('organizations::template.organizationsModule.successMessage');
                            }
                        }
                    }
                } else {
                    $update = CommonModel::updateRecords($whereConditions, $updateOrganizationFields, false, 'Powerpanel\Organizations\Models\Organizations');
                    $actionMessage = trans('organizations::template.organizationsModule.successMessage');
                }
            } else { #Add post Handler=======
                 if (File::exists(app_path() . '/Workflow.php') != null || File::exists(base_path() . '/packages/Powerpanel/Workflow/src/Models/Workflow.php') != null) {
                    $workFlowByCat = Workflow::getRecordByCategoryId($module->intFkGroupCode, $this->currentUserRoleData->id, Config::get('Constant.MODULE.ID'));
                }
                if (!empty($workFlowByCat->varUserId) && $workFlowByCat->chrNeedAddPermission == 'Y' && !$userIsAdmin) {
                    $data['chrMenuDisplay'] = 'N';
                    $Organization = $this->insertNewRecord($data, $OrganizationArr);
                    $data['chrMenuDisplay'] = 'Y';
                    $this->insertApprovalRecord($Organization, $data);
                } else {
                    $Organization = $this->insertNewRecord($data, $OrganizationArr);
                }
                if (isset($data['saveandexit']) && $data['saveandexit'] == 'approvesaveandexit') {
                    $actionMessage = trans('organizations::template.common.recordApprovalMessage');
                } else {
                    $actionMessage = trans('organizations::template.organizationsModule.addedMessage');
                }
                $id = $Organization->id;
            }
            $this->flushCache();
            if ((!empty($data['saveandexit']) && $data['saveandexit'] == 'saveandexit') || !$userIsAdmin) {
                return redirect()->route('powerpanel.organizations.index')->with('message', $actionMessage);
            } else {
                return redirect()->route('powerpanel.organizations.edit', $id)->with('message', $actionMessage);
            }
        } else {
            return Redirect::back()->withErrors($validator)->withInput();
        }
    }

    public function insertApprovedRecord($updateModuleFields, $postArr, $id) {
        $Organization = Organizations::getRecordById($postArr['fkMainRecord']);
        $whereConditions = ['id' => $postArr['fkMainRecord']];
        $updateModuleFields['chrAddStar'] = 'N';
        $update = CommonModel::updateRecords($whereConditions, $updateModuleFields, false, 'Powerpanel\Organizations\Models\Organizations');
        self::newSwapOrderEdit($postArr['display_order'], $Organization);
        $whereConditions_ApproveN = ['fkMainRecord' => $postArr['fkMainRecord']];
        $updateToApproveN = [
            'chrApproved' => 'N',
            'chrLetest' => 'N',
            'intApprovedBy' => '0',
        ];
        $update = CommonModel::updateRecords($whereConditions_ApproveN, $updateToApproveN, false, 'Powerpanel\Organizations\Models\Organizations');
        $whereConditionsApprove = ['id' => $id, 'chrMain' => 'N'];
        $updateToApprove = [
            'chrApproved' => 'Y',
            'chrRollBack' => 'Y',
            'intApprovedBy' => auth()->user()->id
        ];
        $update = CommonModel::updateRecords($whereConditionsApprove, $updateToApprove, false, 'Powerpanel\Organizations\Models\Organizations');
        if ($postArr['chrMenuDisplay'] == 'D') {
            $addlog = Config::get('Constant.DRAFT_RECORD_APPROVED');
        } else {
            $addlog = Config::get('Constant.RECORD_APPROVED');
        }
        $newBannerObj = Organizations::getRecordForLogById($id);
        $logArr = MyLibrary::logData($id, false, $addlog);
        $logArr['varTitle'] = stripslashes($newBannerObj->varTitle);
        Log::recordLog($logArr);
        if (method_exists($this->MyLibrary, 'userNotificationData')) {
        /* notification for user to record approved */
        $userNotificationArr = MyLibrary::userNotificationData(Config::get('Constant.MODULE.ID'));
        $userNotificationArr['fkRecordId'] = $id;
        $userNotificationArr['txtNotification'] = 'Your request has been approved by ' . ucfirst(auth()->user()->name) . ' (' . ucfirst(Config::get('Constant.MODULE.NAME')) . ')';
        $userNotificationArr['fkIntUserId'] = Auth::user()->id;
        $userNotificationArr['chrNotificationType'] = 'A';
        $userNotificationArr['intOnlyForUserId'] = $newBannerObj->UserID;
        UserNotification::addRecord($userNotificationArr);
        /* notification for user to record approved */
        }
        if ($update) {
            if ($id > 0 && !empty($id)) {
                $where = [];
                $flowData = [];
                $flowData['dtYes'] = Config::get('Constant.SQLTIMESTAMP');
                $where['fkModuleId'] = Config::get('Constant.MODULE.ID');
                $where['fkRecordId'] = (isset($postArr['fkMainRecord']) && (int) $postArr['fkMainRecord'] != 0) ? $postArr['fkMainRecord'] : $id;
                $where['dtYes'] = 'null';
                WorkflowLog::updateRecord($flowData, $where);
                self::flushCache();
                $actionMessage = trans('organizations::template.organizationsModule.successMessage');
            }
        }
    }

    public function insertApprovalRecord($Organization, $postArr) {
        $OrganizationArr = [];
        $OrganizationArr['UserID'] = auth()->user()->id;
        $OrganizationArr['chrMain'] = 'N';
        $OrganizationArr['chrLetest'] = 'Y';
        $OrganizationArr['fkMainRecord'] = $Organization->id;
        $OrganizationArr['varTitle'] = stripslashes(trim($postArr['title']));
        $OrganizationArr['intDisplayOrder'] = $postArr['display_order'];
        $OrganizationArr['intParentCategoryId'] = $postArr['parent_category_id'];
        $OrganizationArr['varDesignation'] = stripslashes(trim($postArr['Designation']));
        if(Config::get('Constant.CHRSearchRank') == 'Y'){
        $OrganizationArr['intSearchRank'] = $postArr['search_rank'];
        }
        $OrganizationArr['chrPublish'] = isset($postArr['chrMenuDisplay']) ? $postArr['chrMenuDisplay'] : 'Y';
        $OrganizationArr['created_at'] = Carbon::now();
        $addlog = Config::get('Constant.SENT_FOR_APPROVAL');
        $OrganizationID = CommonModel::addRecord($OrganizationArr, 'Powerpanel\Organizations\Models\Organizations');
        if (!empty($OrganizationID)) {
            $id = $OrganizationID;
            WorkflowLog::addRecord([
                'fkModuleId' => Config::get('Constant.MODULE.ID'),
                'fkRecordId' => $Organization->id,
                'charApproval' => 'Y'
            ]);
            if (method_exists($this->MyLibrary, 'userNotificationData')) {
            $userNotificationArr = MyLibrary::userNotificationData(Config::get('Constant.MODULE.ID'));
            $userNotificationArr['fkRecordId'] = $Organization->id;
            $userNotificationArr['txtNotification'] = 'New approval request from ' . ucfirst(auth()->user()->name) . ' (' . ucfirst(Config::get('Constant.MODULE.NAME')) . ')';
            $userNotificationArr['fkIntUserId'] = Auth::user()->id;
            $userNotificationArr['chrNotificationType'] = 'A';
            UserNotification::addRecord($userNotificationArr);
            }
            $newOrganizationObj = Organizations::getRecordForLogById($id);
            $logArr = MyLibrary::logData($id, false, $addlog);
            $logArr['varTitle'] = $newOrganizationObj->varTitle;
            Log::recordLog($logArr);
            if (Auth::user()->can('recent-updates-list')) {
                $notificationArr = MyLibrary::notificationData($id, $newOrganizationObj);
                RecentUpdates::setNotification($notificationArr);
            }
            $actionMessage = trans('organizations::template.organizationsModule.addedMessage');
        }
        $whereConditionsAddstar = ['id' => $Organization->id];
        $updateAddStar = [
            'chrAddStar' => 'Y',
        ];
        CommonModel::updateRecords($whereConditionsAddstar, $updateAddStar, false, 'Powerpanel\Organizations\Models\Organizations');
    }

    public function insertNewRecord($postArr, $OrganizationArr) {
        $response = false;
        $OrganizationArr['varTitle'] = stripslashes(trim($postArr['title']));
        $OrganizationArr['intDisplayOrder'] = self::newDisplayOrderAdd($postArr['display_order'], $postArr['parent_category_id']);
        $OrganizationArr['intParentCategoryId'] = $postArr['parent_category_id'];
        $OrganizationArr['varDesignation'] = $postArr['Designation'];
        $OrganizationArr['chrPublish'] = $postArr['chrMenuDisplay'];
        if(Config::get('Constant.CHRSearchRank') == 'Y'){
        $OrganizationArr['intSearchRank'] = $postArr['search_rank'];
        }
        $OrganizationArr['UserID'] = auth()->user()->id;
        $OrganizationArr['chrMain'] = 'Y';
        $OrganizationArr['created_at'] = Carbon::now();
        $OrganizationID = CommonModel::addRecord($OrganizationArr, 'Powerpanel\Organizations\Models\Organizations');
        if (!empty($OrganizationID)) {
            $id = $OrganizationID;
            self::newReOrderDisplayOrder($postArr['parent_category_id']);
            $newOrganizationObj = Organizations::getRecordForLogById($id);
            $logArr = MyLibrary::logData($id);
            $logArr['varTitle'] = $newOrganizationObj->varTitle;
            Log::recordLog($logArr);
            if (Auth::user()->can('recent-updates-list')) {
                $notificationArr = MyLibrary::notificationData($id, $newOrganizationObj);
                RecentUpdates::setNotification($notificationArr);
            }
            $response = $newOrganizationObj;
            $actionMessage = trans('organizations::template.organizationsModule.addedMessage');
        }
        return $response;
    }

    /**
     * This method loads Organization table data on view
     * @return  View
     * @since   2017-11-10
     * @author  NetQuick
     */
    public function get_list() {
        $filterArr = [];
        $records = [];
        $records["data"] = [];
        $filterArr['orderColumnNo'] = (!empty(Request::input('order') [0]['column']) ? Request::input('order') [0]['column'] : '');
        $filterArr['orderByFieldName'] = (!empty(Request::input('columns') [$filterArr['orderColumnNo']]['name']) ? Request::input('columns') [$filterArr['orderColumnNo']]['name'] : '');
        $filterArr['orderTypeAscOrDesc'] = (!empty(Request::input('order') [0]['dir']) ? Request::input('order') [0]['dir'] : '');
        $filterArr['statusFilter'] = !empty(Request::input('statusValue')) ? Request::input('statusValue') : '';
        $filterArr['searchFilter'] = !empty(Request::input('searchValue')) ? Request::input('searchValue') : '';
        $filterArr['OrganizationFilter'] = !empty(Request::input('OrganizationFilter')) ? Request::input('OrganizationFilter') : '';
        $filterArr['personalityFilter'] = !empty(Request::input('personalityFilter')) ? Request::input('personalityFilter') : '';
        $filterArr['paymentFilter'] = !empty(Request::input('paymentFilter')) ? Request::input('paymentFilter') : '';
        $filterArr['rangeFilter'] = !empty(Request::input('rangeFilter')) ? Request::input('rangeFilter') : '';
        $filterArr['iDisplayLength'] = intval(Request::input('length'));
        $filterArr['iDisplayStart'] = intval(Request::input('start'));
        $sEcho = intval(Request::input('draw'));
        $isAdmin = false;
        if (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y') {
            $isAdmin = true;
        }
        $arrResults = Organizations::getRecordListforOrganizationsGrid($filterArr, $isAdmin);
        $arrResults = $this->restructureData($arrResults, $filterArr);
        $iTotalRecords = Organizations::getRecordCountforList($filterArr, true, $isAdmin);
        $end = $filterArr['iDisplayStart'] + $filterArr['iDisplayLength'];
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;
        if (!empty($arrResults)) {
            foreach ($arrResults as $key => $value) {
                $records["data"][] = $this->tableData($value);
            }
        }
        $NewRecordsCount = Organizations::getNewRecordsCount();
        $records["newRecordCount"] = $NewRecordsCount;
        $records["customActionStatus"] = "OK";
        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;
        return json_encode($records);
    }

    /**
     * This method loads Organization table data on view
     * @return  View
     * @since   2017-11-10
     * @author  NetQuick
     */
    public function get_list_New() {
        $filterArr = [];
        $records = [];
        $records["data"] = [];
        $filterArr['orderColumnNo'] = (!empty(Request::input('order') [0]['column']) ? Request::input('order') [0]['column'] : '');
        $filterArr['orderByFieldName'] = (!empty(Request::input('columns') [$filterArr['orderColumnNo']]['name']) ? Request::input('columns') [$filterArr['orderColumnNo']]['name'] : '');
        $filterArr['orderTypeAscOrDesc'] = (!empty(Request::input('order') [0]['dir']) ? Request::input('order') [0]['dir'] : '');
        $filterArr['statusFilter'] = !empty(Request::input('statusValue')) ? Request::input('statusValue') : '';
        $filterArr['searchFilter'] = !empty(Request::input('searchValue')) ? Request::input('searchValue') : '';
        $filterArr['OrganizationFilter'] = !empty(Request::input('OrganizationFilter')) ? Request::input('OrganizationFilter') : '';
        $filterArr['personalityFilter'] = !empty(Request::input('personalityFilter')) ? Request::input('personalityFilter') : '';
        $filterArr['paymentFilter'] = !empty(Request::input('paymentFilter')) ? Request::input('paymentFilter') : '';
        $filterArr['rangeFilter'] = !empty(Request::input('rangeFilter')) ? Request::input('rangeFilter') : '';
        $filterArr['iDisplayLength'] = intval(Request::input('length'));
        $filterArr['iDisplayStart'] = intval(Request::input('start'));
        $sEcho = intval(Request::input('draw'));
        $arrResults = Organizations::getRecordListApprovalTab($filterArr);
        $iTotalRecords = Organizations::getRecordCountListApprovalTab($filterArr, true);
        $end = $filterArr['iDisplayStart'] + $filterArr['iDisplayLength'];
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;
        if (!empty($arrResults)) {
            foreach ($arrResults as $key => $value) {
                $records["data"][] = $this->tableDataWaitingToApprovedData($value);
            }
        }
        $NewRecordsCount = Organizations::getNewRecordsCount();
        $records["newRecordCount"] = $NewRecordsCount;
        $records["customActionStatus"] = "OK";
        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;
        return json_encode($records);
    }

    /**
     * This method delete multiples Organization
     * @return  true/false
     * @since   2017-07-15
     * @author  NetQuick
     */
    /* public function DeleteRecord(Request $request) {
      $data = $request->all('ids');
      $update = MyLibrary::deleteMultipleRecords($data);
      $this->flushCache();
      echo json_encode($update);
      exit;
      } */
    public function DeleteRecord(Request $request) {
        /* new code for delete and reorder functionality */
        $data = Request::all('ids');
        $update = self::deleteMultipleRecords($data);
        if (File::exists(app_path() . '/Comments.php') != null || File::exists(base_path() . '/packages/Powerpanel/Workflow/src/Models/Comments.php') != null) {
            Comments::deleteComments($data['ids'], Config::get('Constant.MODULE.MODEL_NAME'));
        }
        foreach ($update as $ids) {
            $ignoreDeleteScope = true;
            $Deleted_Record = Organizations::getRecordById($ids, $ignoreDeleteScope);
            $Cnt_Letest = Organizations::getRecordCount_letest($Deleted_Record['fkMainRecord'], $Deleted_Record['id']);
            if ($Cnt_Letest <= 0) {
                $updateLetest = [
                    'chrAddStar' => 'N',
                ];
                $whereConditionsApprove = ['id' => $Deleted_Record['fkMainRecord']];
                CommonModel::updateRecords($whereConditionsApprove, $updateLetest, false, 'Powerpanel\Organizations\Models\Organizations');
                 if (File::exists(app_path() . '/Workflow.php') != null || File::exists(base_path() . '/packages/Powerpanel/Workflow/src/Models/Workflow.php') != null) {
                    $where = [];
                    $flowData = [];
                    $flowData['dtNo'] = Config::get('Constant.SQLTIMESTAMP');
                    $where['fkModuleId'] = Config::get('Constant.MODULE.ID');
                    $where['fkRecordId'] = $Deleted_Record['fkMainRecord'];
                    $where['dtNo'] = 'null';
                    WorkflowLog::updateRecord($flowData, $where);
                }
            }
        }
        $this->flushCache();
        echo json_encode($update);
        exit;
    }

    public function deleteMultipleRecords($data) {
        $response = false;
        $responseAr = [];
        if (!empty($data)) {
            $updateFields = ['chrDelete' => 'Y', 'chrPublish' => 'N'];
            $whereINConditions = $data['ids'];
            $update = CommonModel::updateMultipleRecords($whereINConditions, $updateFields, false, 'Powerpanel\Organizations\Models\Organizations');
            foreach ($data['ids'] as $key => $id) {
                if ($update) {
                    $objModule = Organizations::getRecordsForDeleteById($id);
                    if (isset($objModule->intDisplayOrder)) {
                        self::newReOrderDisplayOrder($objModule->intParentCategoryId);
                    }
                    if (!empty($id)) {
                        $logArr = MyLibrary::logData($id);
                        $title = '-';
                        if (isset($objModule->varTitle)) {
                            $title = $objModule->varTitle;
                        } else if (isset($objModule->varName)) {
                            $title = $objModule->varName;
                        } else if (isset($objModule->name)) {
                            $title = $objModule->name;
                        }
                        $logArr['varTitle'] = stripslashes($title);
                        Log::recordLog($logArr);
                        array_push($responseAr, $objModule->id);
                        $updateRecentUpdatesFilelds = ['chrRecordDelete' => 'Y'];
                        if (Auth::user()->can('recent-updates-list')) {
                            $notificationUpdate = RecentUpdates::updateRecords($id, $updateRecentUpdatesFilelds);
                            if ($notificationUpdate) {
                                $notificationArr = MyLibrary::notificationData($id, $objModule);
                                RecentUpdates::setNotification($notificationArr);
                            }
                        }
                    }
                }
            }
            $response = $responseAr;
        }
        return $response;
    }

    /**
     * This method reorders banner position
     * @return  Banner index view data
     * @since   2016-10-26
     * @author  NetQuick
     */
    /* public function reorder() {
      $order=Request::input('order');
      $exOrder=Request::input('exOrder');
      MyLibrary::swapOrder($order, $exOrder);
      $this->flushCache();
      } */

    /**
     * This method reorders banner position
     * @return  Banner index view data
     * @since   2016-10-26
     * @author  NetQuick
     */
    public function reorder() {
        $order = Request::input('order');
        $exOrder = Request::input('exOrder');
        $parentRecordId = Request::input('parentRecordId');
        $recordID = '';
        self::swapOrder($order, $exOrder, $parentRecordId);
        $this->flushCache();
    }

    /**
     * This method handels swapping of available order record while editing
     * @param      order
     * @return  order
     * @since   2016-12-23
     * @author  NetQuick
     */
    public static function swapOrder($order = null, $exOrder = null, $parentRecordId = false, $recordID = false) {
        $recEx = Organizations::getRecordByOrderByParent($exOrder, $parentRecordId);
        if (!empty($recEx)) {
            $recCur = Organizations::getRecordByOrderByParent($order, $parentRecordId);
            if ($recCur->intDisplayOrder != $recEx->intDisplayOrder) {
                $whereConditionsForEx = ['id' => $recEx['id']];
                CommonModel::updateRecords($whereConditionsForEx, ['intDisplayOrder' => $recCur->intDisplayOrder], false, 'Powerpanel\Organizations\Models\Organizations');
                $whereConditionsForCur = ['id' => $recCur['id']];
                CommonModel::updateRecords($whereConditionsForCur, ['intDisplayOrder' => $recEx->intDisplayOrder], false, 'Powerpanel\Organizations\Models\Organizations');
            }
        }
        self::newReOrderDisplayOrder($parentRecordId);
    }

    /**
     * This method destroys Banner in multiples
     * @return  Banner index view
     * @since   2016-10-25
     * @author  NetQuick
     */
    public function publish(Request $request) {
        $requestArr = Request::all();
//        $request = (object) $requestArr;
        $val = Request::get('val');
        $alias = Request::input('alias');
        $update = MyLibrary::setPublishUnpublish($alias, $val, 'Powerpanel\Organizations\Models\Organizations');
        $this->flushCache();
        echo json_encode($update);
        exit;
    }

    /**
     * This method handels logs History records
     * @param   $data
     * @return  HTML
     * @since   2017-07-21
     * @author  NetQuick
     */
    public function recordHistory($data = false) {
        $returnHtml = '';
        $returnHtml .= '<table class="new_table_desing table table-striped table-bordered table-hover">
                            <thead>
                                    <tr>
                                        <th align="center">' . trans("organizations::template.common.title") . '</th>
                                        <th align="center">' . trans("organizations::template.common.parentCategory") . '</th>
                                        <th align="center">Designation</th>
                                        <th align="center">' . trans("organizations::template.common.displayorder") . '</th>
                                        <th align="center">' . trans("organizations::template.common.publish") . '</th>
                                    </tr>
                            </thead>
                            <tbody>
                                    <tr>
                                        <td align="center">' . stripslashes($data->varTitle) . '</td>';
        if ($data->intParentCategoryId > 0) {
            $catIDS[] = $data->intParentCategoryId;
            $parentCateName = Organizations::getParentCategoryNameBycatId($catIDS);
            $parentCateName = $parentCateName[0]->varTitle;
            $returnHtml .= '<td align="center">' . $parentCateName . '</td>';
        } else {
            $returnHtml .= '<td align="center">-</td>';
        }
        $returnHtml .= '<td align="center">' . stripslashes($data->varDesignation) . '</td>
                                        <td align="center">' . ($data->intDisplayOrder) . '</td>
                                        <td align="center">' . $data->chrPublish . '</td>
                                    </tr>
                            </tbody>
                        </table>';
        return $returnHtml;
    }

    public function newrecordHistory($data = false, $newdata = false) {
        if ($data->varTitle != $newdata->varTitle) {
            $titlecolor = 'style="background-color:#f5efb7"';
        } else {
            $titlecolor = '';
        }
        if ($data->intParentCategoryId != $newdata->intParentCategoryId) {
            $ParentCategoryIdcolor = 'style="background-color:#f5efb7"';
        } else {
            $ParentCategoryIdcolor = '';
        }
        if ($data->varDesignation != $newdata->varDesignation) {
            $Designationcolor = 'style="background-color:#f5efb7"';
        } else {
            $Designationcolor = '';
        }
        if ($data->intDisplayOrder != $newdata->intDisplayOrder) {
            $DisplayOrdercolor = 'style="background-color:#f5efb7"';
        } else {
            $DisplayOrdercolor = '';
        }
        if ($data->chrPublish != $newdata->chrPublish) {
            $Publishcolor = 'style="background-color:#f5efb7"';
        } else {
            $Publishcolor = '';
        }
        $returnHtml = '';
        $returnHtml .= '<table class="new_table_desing table table-striped table-bordered table-hover">
                            <thead>
                                    <tr>
                                        <th align="center">' . trans("organizations::template.common.title") . '</th>
                                        <th align="center">' . trans("organizations::template.common.parentCategory") . '</th>
                                        <th align="center">Designation</th>
                                        <th align="center">' . trans("organizations::template.common.displayorder") . '</th>
                                        <th align="center">' . trans("organizations::template.common.publish") . '</th>
                                    </tr>
                            </thead>
                            <tbody>
                                    <tr>
                                        <td align="center" ' . $titlecolor . '>' . stripslashes($newdata->varTitle) . '</td>';
        if ($newdata->intParentCategoryId > 0) {
            $catIDS[] = $newdata->intParentCategoryId;
            $parentCateName = Organizations::getParentCategoryNameBycatId($catIDS);
            $parentCateName = $parentCateName[0]->varTitle;
            $returnHtml .= '<td align="center" ' . $ParentCategoryIdcolor . '>' . $parentCateName . '</td>';
        } else {
            $returnHtml .= '<td align="center">-</td>';
        }
        $returnHtml .= '<td align="center" ' . $Designationcolor . '>' . stripslashes($newdata->varDesignation) . '</td>
                                        <td align="center" ' . $DisplayOrdercolor . '>' . ($newdata->intDisplayOrder) . '</td>
                                        <td align="center" ' . $Publishcolor . '>' . $newdata->chrPublish . '</td>
                                    </tr>
                            </tbody>
                        </table>';
        return $returnHtml;
    }

    public function tableData($value = false) {
        $isParent = Organizations::getCountById($value->id);
        $details = '';
        $parent_category_name = ' ';
        $publish_action = '';
        $titleData_delete = "";
        $titleData_publish = "";
        $details = '';
        if ($isParent > 0) {
            $titleData_delete = 'This organization is selected as parent organization in other record so it can&#39;t be deleted.';
            $titleData_publish = 'This organization is selected as parent organization in other record so it can&#39;t be published/unpublished.';
        }
        $checkbox = '<a href="javascript:;" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-toggle="tooltip" data-bs-content="' . $titleData_delete . '" title="' . $titleData_delete . '"><i style="color:red" class="ri-alert-fill"></i></a>';
        $checkbox_publish = '<a href="javascript:;" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-toggle="tooltip" data-bs-content="' . $titleData_publish . '" title="' . $titleData_publish . '"><i style="color:red" class="ri-alert-fill"></i></a>';
        if (Auth::user()->can('organizations-edit')) {
            $details .= '<a class="" title="' . trans("organizations::template.common.edit") . '" href="' . route('powerpanel.organizations.edit', array('alias' => $value->id)) . '"><i class="ri-pencil-line"></i></a>';
        }
        if ((Auth::user()->can('organizations-delete') || (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y')) && $isParent == 0) {
            $details .= '<a class=" delete" title="' . trans("organizations::template.common.delete") . '" data-controller="organizations" data-alias = "' . $value->id . '"><i class="ri-delete-bin-line"></i></a>';
        }
        if (Auth::user()->can('organizations-publish')) {
            if ($isParent == 0) {
                if ($value->chrPublish == 'Y') {
                    //Bootstrap Switch
                    $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/organizations', 'data_alias'=>$value->id, 'title'=>trans("organizations::template.common.publishedRecord"), 'data_value'=>'Unpublish'])->render();
                } else {
                    //Bootstrap Switch
                    $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/organizations', 'data_alias'=>$value->id, 'title'=>trans("organizations::template.common.unpublishedRecord"), 'data_value'=>'Publish', 'checked'=>'checked'])->render();
                }
            } else {
                $publish_action = $checkbox_publish;
            }
        }
        $parentCategoryTitle = '-';
        if (!empty($value->intParentCategoryId) && $value->intParentCategoryId > 0) {
            $catIDS[] = $value->intParentCategoryId;
            $parentCategoryName = Organizations::getParentCategoryNameBycatId($catIDS);
            $parentCategoryTitle = $parentCategoryName[0]->varTitle;
        }
        if (Auth::user()->can('organizations-reviewchanges') && (File::exists(app_path() . '/Workflow.php') != null || File::exists(base_path() . '/packages/Powerpanel/Workflow/src/Models/Workflow.php') != null)) {
            $update = "<a class=\"icon_title1\" style=\"margin-right: 5px;\" onclick=\"javascript:expandcollapsepanel(this ,'primary-tasklisting" . $value->id . "', 'primary-mainsingnimg" . $value->id . "'," . $value->id . "," . "'primary-'" . ")\"><i id=\"primary-mainsingnimg" . $value->id . "\" class=\"ri-add-box-line\"></i></a>";
            $rollback = "<a class=\"icon_title2\" style=\"margin-right: 5px;\" onclick=\"javascript:expandcollapsepanel_rolback(this ,'primary-tasklisting_rollback" . $value->id . "', 'primary-mainsingnimg_rollback" . $value->id . "'," . $value->id . "," . "'primary-'" . ")\"><i id=\"primary-mainsingnimg_rollback" . $value->id . "\" class=\"ri-history-line\"></i></a>";
        } else {
            $update = '';
            $rollback = '';
        }
        if ($value->chrLock != 'Y') {
            $title = '<div class="quick_edit"><a class="" title="Edit" href="' . route('powerpanel.organizations.edit', array('alias' => $value->id)) . '">' . $value->treename . '</a></div>';
        } else {
            if (auth()->user()->id != $value->LockUserID) {
                if (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y') {
                    $title = '<div class="quick_edit"><a class="" title="Edit" href="' . route('powerpanel.organizations.edit', array('alias' => $value->id)) . '">' . $value->treename . '</a></div>';
                } else {
                    $title = '<div class="quick_edit">' . $value->treename . '</div>';
                }
            } else {
                $title = '<div class="quick_edit"><a class="" title="Edit" href="' . route('powerpanel.organizations.edit', array('alias' => $value->id)) . '">' . $value->treename . '</a></div>';
            }
        }
        if ($value->varDesignation != '') {
            $designation = $value->varDesignation;
        } else {
            $designation = '-';
        }
        $logurl = url('powerpanel/log?id=' . $value->id . '&mid=' . Config::get('Constant.MODULE.ID'));
        $log = '';
        if ($value->chrLock != 'Y') {
            if (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y') {
                $log .= $details;
                if (Auth::user()->can('log-list')) {
                    $log .= "<a title=\"Log History\" href=\"$logurl\"><i class=\"ri-time-line\"></i></a>";
                }
            } else {
                if ($details == "") {
                    $details = "---";
                } else {
                    $details = $details;
                }
                $log .= $details;
                if (Auth::user()->can('log-list')) {
                    $log .= "<a title=\"Log History\" href=\"$logurl\"><i class=\"ri-time-line\"></i></a>";
                }
            }
        } else {
            if (auth()->user()->id != $value->LockUserID) {
                $lockedUserData = User::getRecordById($value->LockUserID, true);
                $lockedUserName = 'someone';
                if (!empty($lockedUserData)) {
                    $lockedUserName = $lockedUserData->name;
                }
                if (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y') {
                    $log .= '<a class="star_lock" onclick="GetUnLockData(' . $value->id . ',' . auth()->user()->id . ',' . Config::get('Constant.MODULE.ID') . ',1)" title="This record has been locked by ' . $lockedUserName . ', Click here to unlock."><i class="ri-lock-2-line"></i></a>';
                } else {
                    $log .= '<a class="star_lock" title="This record has been locked by ' . $lockedUserName . '."><i class="ri-lock-2-line"></i></a>';
                }
            } else {
                $log .= '<a class="star_lock" onclick="GetUnLockData(' . $value->id . ',' . auth()->user()->id . ',' . Config::get('Constant.MODULE.ID') . ',1)" title="Click here to unlock."><i class="ri-lock-2-line"></i></a>';
            }
        }
        if ($publish_action == "") {
            $publish_action = "---";
        } else {
            if ($value->chrLock != 'Y') {
                $publish_action = $publish_action;
            } else {
                if (isset($this->currentUserRoleData->chrIsAdmin) && (auth()->user()->id == $value->LockUserID) || $this->currentUserRoleData->chrIsAdmin == 'Y') {
                    $publish_action = $publish_action;
                } else {
                    $publish_action = "---";
                }
            }
        }
        $statusdata = '';
        if (method_exists($this->MyLibrary, 'count_days')) {
            $days = MyLibrary::count_days($value->created_at);
            $days_modified = MyLibrary::count_days($value->updated_at);
        } else {
            $days = '';
            $days_modified = '';
        }
        if ($days_modified < Config::get('Constant.DEFAULT_DAYS') && $days < Config::get('Constant.DEFAULT_DAYS')) {
            if ($days < Config::get('Constant.DEFAULT_DAYS')) {
                $statusdata = '<img border="0" title="There was new action on this menu." alt="New" src="' . Config::get('Constant.CDN_PATH') . 'assets/images/new.png' . '">';
            }
        } else {
            if ($days_modified < Config::get('Constant.DEFAULT_DAYS')) {
                $statusdata = '<img border="0" title="There was edit/update action on this menu." alt="Updated" src="' . Config::get('Constant.CDN_PATH') . 'assets/images/updated.png' . '">';
            }
            if ($days < Config::get('Constant.DEFAULT_DAYS')) {
                $statusdata = '<img border="0" title="There was new action on this menu." alt="New" src="' . Config::get('Constant.CDN_PATH') . 'assets/images/new.png' . '">';
            }
        }
        $status = '';

        if ($value->chrAddStar == 'Y') {
            $status .= Config::get('Constant.APPROVAL_LIST') . ' ';
        }
        $log .= "<a title='Rollback to previous version'  onclick=\"rollbackToPreviousVersion('" . $value->id . "');\"  class=\"log-grid\"><i class=\"ri-history-line\"></i></a>";

        $records = array(
            ($isParent == 0) ? '<input type="checkbox" name="delete" class="chkDelete form-check-input" value="' . $value->id . '">' : $checkbox,
            $value->id,
            '<div class="pages_title_div_row">' . $update . $rollback . $title . ' ' . $status . $statusdata . '</div>',
            $designation,
            $parentCategoryTitle,
            '<a href="javascript:;" data-parentRecordId="' . $value->intParentCategoryId . '" data-order="' . $value->intDisplayOrder . '" class="moveUp"><i class="ri-arrow-up-line" aria-hidden="true"></i></a>
				' . $value->DisplayOrder .
            ' <a href="javascript:;" data-parentRecordId="' . $value->intParentCategoryId . '" data-order="' . $value->intDisplayOrder . '" class="moveDwn"><i class="ri-arrow-down-line" aria-hidden="true"></i></a>',
            $publish_action,
            $log,
            $value->intDisplayOrder,
        );
        return $records;
    }

    public function tableDataWaitingToApprovedData($value = false) {
        $isParent = Organizations::getCountById($value->id);
        $details = '';
        $parent_category_name = ' ';
        $publish_action = '';
        $titleData_delete = "";
        $titleData_publish = "";
        $details = '';
        if ($isParent > 0) {
            $titleData_delete = 'This organization is selected as parent organization in other record so it can&#39;t be deleted.';
            $titleData_publish = 'This organization is selected as parent organization in other record so it can&#39;t be published/unpublished.';
        }
        $checkbox = '<a href="javascript:;" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-toggle="tooltip" data-bs-content="' . $titleData_delete . '" title="' . $titleData_delete . '"><i style="color:red" class="ri-alert-fill"></i></a>';
        $checkbox_publish = '<a href="javascript:;" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-toggle="tooltip" data-bs-content="' . $titleData_publish . '" title="' . $titleData_publish . '"><i style="color:red" class="ri-alert-fill"></i></a>';
        if (Auth::user()->can('organizations-edit')) {
            $details .= '<a class="" title="' . trans("organizations::template.common.edit") . '" href="' . route('powerpanel.organizations.edit', array('alias' => $value->id)) . '"><i class="ri-pencil-line"></i></a>';
        }
        if (Auth::user()->can('organizations-delete') || (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y') && $isParent == 0) {
            $details .= '<a class=" delete" title="' . trans("organizations::template.common.delete") . '" data-controller="organizations" data-alias = "' . $value->id . '"><i class="ri-delete-bin-line"></i></a>';
        }
        $parentCategoryTitle = '-';
        if (!empty($value->intParentCategoryId) && $value->intParentCategoryId > 0) {
            $catIDS[] = $value->intParentCategoryId;
            $parentCategoryName = Organizations::getParentCategoryNameBycatId($catIDS);
            $parentCategoryTitle = $parentCategoryName[0]->varTitle;
        }
        if (Auth::user()->can('organizations-reviewchanges')) {
            $update = "<a class=\"icon_title1\" style=\"margin-right: 5px;\" onclick=\"javascript:expandcollapsepanel(this ,'waiting-tasklisting" . $value->id . "', 'waiting-mainsingnimg" . $value->id . "'," . $value->id . "," . "'waiting-'" . ")\"><i id=\"waiting-mainsingnimg" . $value->id . "\" class=\"ri-add-box-line\"></i></a>";
            $rollback = "<a class=\"icon_title2\" style=\"margin-right: 5px;\" onclick=\"javascript:expandcollapsepanel_rolback(this ,'waiting-tasklisting_rollback" . $value->id . "', 'waiting-mainsingnimg_rollback" . $value->id . "'," . $value->id . "," . "'waiting-'" . ")\"><i id=\"waiting-mainsingnimg_rollback" . $value->id . "\" class=\"ri-history-line\"></i></a>";
        } else {
            $update = '';
            $rollback = '';
        }
        if (Auth::user()->can('organizations-reviewchanges') && $value->chrAddStar == 'Y') {
            $star = 'addhiglight';
        } else {
            $star = '';
        }
        if (Auth::user()->can('organizations-edit')) {
            if ($value->chrLock != 'Y') {
                $title = '<div class="quick_edit"><a class="' . $star . '" title="' . trans("organizations::template.common.edit") . '" href="' . route('powerpanel.organizations.edit', array('alias' => $value->id)) . '">' . $value->varTitle . '</a></div>';
            } else {
                if (auth()->user()->id != $value->LockUserID) {
                    if (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y') {
                        $title = '<div class="quick_edit"><a class="' . $star . '" title="' . trans("organizations::template.common.edit") . '" href="' . route('powerpanel.organizations.edit', array('alias' => $value->id)) . '">' . $value->varTitle . '</a></div>';
                    } else {
                        $title = '<div class="quick_edit">' . $value->varTitle . '</div>';
                    }
                } else {
                    $title = '<div class="quick_edit"><a class="' . $star . '" title="' . trans("organizations::template.common.edit") . '" href="' . route('powerpanel.organizations.edit', array('alias' => $value->id)) . '">' . $value->varTitle . '</a></div>';
                }
            }
        } else {
            $title = stripslashes($value->varTitle);
        }


        if ($value->varDesignation != '') {
            $designation = $value->varDesignation;
        } else {
            $designation = '-';
        }
        $logurl = url('powerpanel/log?id=' . $value->id . '&mid=' . Config::get('Constant.MODULE.ID'));
        $log = '';
        if ($value->chrLock != 'Y') {
            if (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y') {
                $log .= $details;
                if (Auth::user()->can('log-list')) {
                    $log .= "<a title=\"Log History\" href=\"$logurl\"><i class=\"ri-time-line\"></i></a>";
                }
            } else {
                if ($details == "") {
                    $details = "---";
                } else {
                    $details = $details;
                }
                $log .= $details;
                if (Auth::user()->can('log-list')) {
                    $log .= "<a title=\"Log History\" href=\"$logurl\"><i class=\"ri-time-line\"></i></a>";
                }
            }
        } else {
            if (auth()->user()->id != $value->LockUserID) {
                $lockedUserData = User::getRecordById($value->LockUserID, true);
                $lockedUserName = 'someone';
                if (!empty($lockedUserData)) {
                    $lockedUserName = $lockedUserData->name;
                }
                if (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y') {
                    $log .= '<a class="star_lock" onclick="GetUnLockData(' . $value->id . ',' . auth()->user()->id . ',' . Config::get('Constant.MODULE.ID') . ',1)" title="This record has been locked by ' . $lockedUserName . ', Click here to unlock."><i class="ri-lock-2-line"></i></a>';
                } else {
                    $log .= '<a class="star_lock" title="This record has been locked by ' . $lockedUserName . '."><i class="ri-lock-2-line"></i></a>';
                }
            } else {
                $log .= '<a class="star_lock" onclick="GetUnLockData(' . $value->id . ',' . auth()->user()->id . ',' . Config::get('Constant.MODULE.ID') . ',1)" title="Click here to unlock."><i class="ri-lock-2-line"></i></a>';
            }
        }
        $statusdata = '';
        if (method_exists($this->MyLibrary, 'count_days')) {
            $days = MyLibrary::count_days($value->created_at);
            $days_modified = MyLibrary::count_days($value->updated_at);
        } else {
            $days = '';
            $days_modified = '';
        }
        if ($days_modified < Config::get('Constant.DEFAULT_DAYS') && $days < Config::get('Constant.DEFAULT_DAYS')) {
            if ($days < Config::get('Constant.DEFAULT_DAYS')) {
                $statusdata = '<img border="0" title="There was new action on this menu." alt="New" src="' . Config::get('Constant.CDN_PATH') . 'assets/images/new.png' . '">';
            }
        } else {
            if ($days_modified < Config::get('Constant.DEFAULT_DAYS')) {
                $statusdata = '<img border="0" title="There was edit/update action on this menu." alt="Updated" src="' . Config::get('Constant.CDN_PATH') . 'assets/images/updated.png' . '">';
            }
            if ($days < Config::get('Constant.DEFAULT_DAYS')) {
                $statusdata = '<img border="0" title="There was new action on this menu." alt="New" src="' . Config::get('Constant.CDN_PATH') . 'assets/images/new.png' . '">';
            }
        }

        $log .= "<a title='Rollback to previous version'  onclick=\"rollbackToPreviousVersion('" . $value->id . "');\"  class=\"log-grid\"><i class=\"ri-history-line\"></i></a>";
        $records = array(
            $value->id,
            '<div class="pages_title_div_row">' . $update . $rollback . $title . $statusdata . '</div>',
            $designation,
            $parentCategoryTitle,
            $log,
            $value->intDisplayOrder
        );
        return $records;
    }

    /**
     * This method loads Organization edit view
     * @param   Alias of record
     * @return  View
     * @since   2017-11-10
     * @author  NetQuick
     */
    public function edit($alias = false) {
        $isParent = 0;
        $userIsAdmin = false;
       if (isset($this->currentUserRoleData) && !empty($this->currentUserRoleData)) {
            if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                $userIsAdmin = true;
            }
        } else{
            $userIsAdmin = true;
        }
        if (!is_numeric($alias)) {
            $categories = ParentRecordHierarchy_builder::Parentrecordhierarchy();
            $total = Organizations::getRecordCounter();
            $total = $total + 1;
            $this->breadcrumb['title'] = trans('organizations::template.organizationsModule.addOrganization');
            $this->breadcrumb['module'] = trans('organizations::template.organizationsModule.manageOrganizations');
            $this->breadcrumb['url'] = 'powerpanel/organizations';
            $this->breadcrumb['inner_title'] = trans('organizations::template.organizationsModule.addOrganization');
            $breadcrumb = $this->breadcrumb;
            $data = compact('total', 'breadcrumb', 'categories', 'isParent', 'userIsAdmin');
        } else {
            $id = $alias;
            $organization = Organizations::getRecordById($id);
            if (empty($organization)) {
                return redirect()->route('powerpanel.organizations.add');
            }
            $isParent = Organizations::getCountById($organization->id);
            $categories = ParentRecordHierarchy_builder::Parentrecordhierarchy($organization->intParentCategoryId, $organization->id, 'Powerpanel\Organizations\Models\Organizations');
            $metaInfo = array('varMetaTitle' => $organization->varMetaTitle, 'varMetaKeyword' => $organization->varMetaKeyword, 'varMetaDescription' => $organization->varMetaDescription);
            $this->breadcrumb['title'] = trans('organizations::template.common.edit') . ' - ' . $organization->varTitle;
            $this->breadcrumb['module'] = trans('organizations::template.organizationsModule.manageOrganizations');
            $this->breadcrumb['url'] = 'powerpanel/organizations';
            $this->breadcrumb['inner_title'] = trans('organizations::template.common.edit') . ' - ' . $organization->varTitle;
            $breadcrumb = $this->breadcrumb;
            if ((int) $organization->fkMainRecord !== 0) {
                $organizationHighLight = Organizations::getRecordById($organization->fkMainRecord);
                $metaInfo_highLight['varMetaTitle'] = $organizationHighLight['varMetaTitle'];
                $metaInfo_highLight['varMetaKeyword'] = $organizationHighLight['varMetaKeyword'];
                $metaInfo_highLight['varMetaDescription'] = $organizationHighLight['varMetaDescription'];
            } else {
                $organizationHighLight = "";
                $metaInfo_highLight['varMetaTitle'] = "";
                $metaInfo_highLight['varMetaKeyword'] = "";
                $metaInfo_highLight['varMetaDescription'] = "";
            }
            $data = compact('organizationHighLight', 'metaInfo_highLight', 'categories', 'isParent', 'organization', 'metaInfo', 'breadcrumb', 'userIsAdmin');
        }
        //Start Button Name Change For User Side
        if (isset($this->currentUserRoleData->chrIsAdmin)) {
            if ($this->currentUserRoleData->chrIsAdmin != 'Y') {
                $module = Modules::getModuleById(Config::get('Constant.MODULE.ID'));
                if (!$userIsAdmin) {
                    $userRole = $this->currentUserRoleData->id;
                } else {
                    $userRoleData = Role_user::getUserRoleByUserId(auth()->user()->id);
                    $userRole = $userRoleData->role_id;
                }
                $workFlowByCat = Workflow::getRecordByCategoryId($module->intFkGroupCode, $userRole, Config::get('Constant.MODULE.ID'));
                if (!empty($workFlowByCat)) {
                    $data['chrNeedAddPermission'] = $workFlowByCat->chrNeedAddPermission;
                    $data['charNeedApproval'] = $workFlowByCat->charNeedApproval;
                } else {
                    $data['chrNeedAddPermission'] = 'N';
                    $data['charNeedApproval'] = 'N';
                }
            }
        }else {
            $data['chrNeedAddPermission'] = 'N';
            $data['charNeedApproval'] = 'N';
        }
        //End Button Name Change For User Side
        return view('organizations::powerpanel.actions', $data);
    }

    /**
     * This method handels loading process of generating html menu from array data
     * @return  Html menu
     * @param  parentId, parentUrl, menu_array
     * @since   04-08-2017
     * @author  NetQuick
     */
    public function getChildren($CatId = false) {
        $serCats = Organizations::where('intParentCategoryId', $CatId)->get();
        $response = false;
        $html = '';
        foreach ($serCats as $serCat) {
            if (isset($serCat->intParentCategoryId)) {
                $html = '<ul class="dd-list menu_list_set">';
                $html .= '<li class="dd-item">';
                $html .= $serCat->varTitle;
                $html .= $this->getChildren($serCat->id);
                $html .= '</li>';
                $html .= '</ul>';
            }
        }
        $response = $html;
        return $response;
    }

    public function addCatAjax() {
        $data = Request::input();
        return AddCategoryAjax::Add($data, 'Organization');
    }

    public static function flushCache() {
        Cache::tags('Organizations')->flush();
    }

    public function restructureData($elements, $filterArr) {
        $stringIds = array();
        $children = array();
        $onlyParentIds = array();
        $minIds = array();
        foreach ($elements as $element) {
            $stringIds[] = $element->id;
            $stringIds[] = $element->intParentCategoryId;
            $onlyParentIds[] = $element->intParentCategoryId;
        }
        if (!empty($filterArr['searchFilter']) || !empty($filterArr['statusFilter'])) {
            array_push($stringIds, '0');
//            foreach ($onlyParentIds as $Id) {
//                $parentNodes = Organizations::getParentNodesIdsByRecordId($Id);
//                if (!empty($parentNodes)) {
//                    $stringIds = array_merge($stringIds, $parentNodes);
//                }
//            }
        }
        $stringIds = array_unique($stringIds);
        $fetchData = Organizations::getRecordListforGridbyIds($stringIds, $filterArr);
        $children = array();
        if (!empty($fetchData)) {
            foreach ($fetchData as $p) {
                $pt = $p->intParentCategoryId;
                $list = isset($children[$pt]) ? $children[$pt] : array();
                array_push($list, $p);
                $children[$pt] = $list;
            }
        }
        $list = array();
        $list = $this->treerecurse(0, '&nbsp;&nbsp;&nbsp;', array(), $children, 10, 0);
        $list = array_slice($list, $filterArr['iDisplayStart'], $filterArr['iDisplayLength']);
        return $list;
    }

    public function treerecurse($id, $indent, $list = Array(), $children = Array(), $maxlevel = '10', $level = 0, $Type = 1, $Order = '') {
        $c = "";
        if (isset($children[$id]) && !empty($children[$id]) && $level <= $maxlevel) {
            foreach ($children[$id] as $c) {
                $id = $c->id;
                if ($Type) {
                    $pre = '<sup>|_</sup>&nbsp;';
                    $spacer = '.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                    $parent_order = $Order;
                } else {
                    $pre = '|_ ';
                    $spacer = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                }
                if ($c->intParentCategoryId == 0) {
                    $txt = $c->varTitle;
                    $Orderparent = $c->intDisplayOrder;
                } else {
                    $txt = $pre . $c->varTitle;
                    $Orderparent = " . " . $c->intDisplayOrder;
                }
                $pt = $c->intParentCategoryId;
                $list[$id] = $c;
                $list[$id]->treename = "$indent$txt";
                $list[$id]->children = (isset($children[$id])) ? count($children[$id]) : 0;
                $list[$id]->DisplayOrder = $Order . $Orderparent;
                $list = $this->treerecurse($id, $indent . $spacer, $list, $children, $maxlevel, $level + 1, $Type, $parent_order . $Orderparent);
            }
        }
        return $list;
    }

    /**
     * This method handels swapping of available order record while adding new function
     * @param      order
     * @return  order
     * @since   2016-10-21
     * @author  NetQuick
     */
    public static function newDisplayOrderAdd($order = null, $parentRecordId = false) {
        $response = false;
        $TotalRec = Organizations::getRecordCounter($parentRecordId);
        if ($parentRecordId > 0) {
            if ($TotalRec >= $order) {
                Organizations::UpdateDisplayOrder($order, $parentRecordId);
                $order = $order;
            } else {
                $order = $TotalRec + 1;
            }
        } else {
            if ($TotalRec >= $order) {
                Organizations::UpdateDisplayOrder($order);
                $order = $order;
            } else {
                $order = $TotalRec + 1;
            }
        }
        $response = (int) $order;
        return $response;
    }

    /**
     * This method handels swapping of available order record while editing
     * @param      order
     * @return  order
     * @since   2016-12-23
     * @author  NetQuick
     */
    public static function newSwapOrderEdit($order = null, $oldData = null) {
        $id = $oldData->id;
        $recCur = Organizations::getRecordById($id);
        if (!empty($recCur)) {
            $parentRecordId = $recCur->intParentCategoryId;
            $TotalRec = Organizations::getRecordCounter($parentRecordId);
            if ($parentRecordId > 0) {
                if ($TotalRec > $order) {
                    Organizations::UpdateDisplayOrder($order, $parentRecordId);
                    $whereConditionsForCur = ['id' => $recCur['id']];
                    CommonModel::updateRecords($whereConditionsForCur, ['intDisplayOrder' => $order], false, 'Powerpanel\Organizations\Models\Organizations');
                } else {
                    $whereConditionsForCur = ['id' => $recCur['id']];
                    CommonModel::updateRecords($whereConditionsForCur, ['intDisplayOrder' => $TotalRec + 1], false, 'Powerpanel\Organizations\Models\Organizations');
                }
            } else {
                if ($TotalRec > $order) {
                    Organizations::UpdateDisplayOrder($order);
                    $whereConditionsForCur = ['id' => $recCur['id']];
                    CommonModel::updateRecords($whereConditionsForCur, ['intDisplayOrder' => $order], false, 'Powerpanel\Organizations\Models\Organizations');
                } else {
                    $whereConditionsForCur = ['id' => $recCur['id']];
                    CommonModel::updateRecords($whereConditionsForCur, ['intDisplayOrder' => $TotalRec + 1], false, 'Powerpanel\Organizations\Models\Organizations');
                }
            }
            /* code for reoder for current data record */
            self::newReOrderDisplayOrder($parentRecordId);
            if ($parentRecordId !== $oldData->intParentCategoryId) {
                /* code for reoder for exchanbged parent data data record */
                self::newReOrderDisplayOrder($oldData->intParentCategoryId);
            }
        }
    }

    /**
     * This method handels swapping of available order record
     * @param      order
     * @return  order
     * @since   2016-10-21
     * @author  NetQuick
     */
    public static function newReOrderDisplayOrder($parentRecordId = false) {
        $response = false;
        $records = Organizations::getRecordForReorderByParentId($parentRecordId);
        $ids = array();
        $update_syntax = "";
        if (!empty($records)) {
            $i = 0;
            foreach ($records as $rec) {
                $i++;
                $ids[$rec->id] = $rec->id;
                $update_syntax .= " WHEN " . $rec->id . " THEN $i ";
            }
            if (!empty($ids)) {
                $when = $update_syntax;
                Organizations::updateherarachyRecords($when, $ids);
            }
        }
    }

    /**
     * This method handle to get child record.
     * @since   27-Sep-2018
     * @author  NetQuick Team
     */
    public function getChildData(Request $request) {
        $requestArr = Request::all();
        $request = (object) $requestArr;
        $childHtml = "";
        $childData = "";
        $childData = Organizations::getChildGrid($request->id);
        $childHtml .= "<div class=\"producttbl\" style=\"\">";
        $childHtml .= "<table class=\"new_table_desing table table-striped table-bordered table-hover table-checkable dataTable\" id=\"email_log_datatable_ajax\">
																<tr role=\"row\">
																		<th class=\"text-center\"></th>
                                                                                                                                                 <th class=\"text-center\">Title</th>
																		<th class=\"text-center\">Date Submitted</th>
																		<th class=\"text-center\">User</th>																		
																		<th class=\"text-center\">Edit</th>
																		<th class=\"text-center\">Status</th>";
        $childHtml .= "         </tr>";
        if (count($childData) > 0) {
            foreach ($childData as $child_row) {
                $restictMsg = "This is approved record, so can't be deleted.";
                $childHtml .= "<tr role=\"row\">";
                if ($child_row->chrApproved == 'N') {
                    $childHtml .= "<td><span class='mob_show_title'>&nbsp</span><input type=\"checkbox\" name=\"delete\" class=\"chkDelete form-check-input\" value='" . $child_row->id . "'></td>";
                } else {
                    $childHtml .= "<td><span class='mob_show_title'>&nbsp</span><div class=\"checker\"><a href=\"javascript:;\" data-toggle=\"tooltip\" data-placement=\"right\" title=\"$restictMsg\"><i style=\"color:red\" class=\"ri-alert-fill\"></i></a></div></td>";
                }
                $childHtml .= '<td class="text-center"><span class="mob_show_title">Title: </span>' . $child_row->varTitle . '</td>';
                $childHtml .= "<td class=\"text-center\"><span class='mob_show_title'>Date Submitted: </span>" . date('M/d/Y h:i A', strtotime($child_row->created_at)) . "</td>";
                $childHtml .= "<td class=\"text-center\"><span class='mob_show_title'>User: </span>" . CommonModel::getUserName($child_row->UserID) . "</td>";
                if ($child_row->chrApproved == 'N') {
                    $childHtml .= "<td class=\"text-center\"><span class='mob_show_title'>Edit: </span><a class='icon_round' title='" . trans("organizations::template.common.edit") . "' href='" . route('powerpanel.organizations.edit', array('alias' => $child_row->id)) . "'>
							<i class='ri-pencil-line'></i></a></td>";
                } else {
                    $childHtml .= "<td class=\"text-center\"><span class='mob_show_title'>Edit: </span>-</td>";
                }
                if ($child_row->chrApproved == 'N') {
                    $childHtml .= "<td class=\"text-center\"><span class='mob_show_title'>Status: </span><a title='" . trans("organizations::template.common.comments") . "' href=\"javascript:;\" onclick=\"loadModelpopup('" . $child_row->id . "','" . $child_row->UserID . "','" . Config::get('Constant.MODULE.MODEL_NAME') . "','" . $child_row->fkMainRecord . "')\" class=\"approve_icon_btn\"><i class=\"ri-chat-1-line\"></i> <span>Comment</span></a>    <a  onclick=\"update_mainrecord('" . $child_row->id . "','" . $child_row->fkMainRecord . "','" . $child_row->UserID . "','A');\" title='" . trans("organizations::template.common.clickapprove") . "' href=\"javascript:;\" class=\"approve_icon_btn\"><i class=\"ri-checkbox-line\"></i> <span>Approve</span></a></td>";
                } else {
                    $childHtml .= "<td class=\"text-center\"><span class='mob_show_title'>Status: </span><span class='mob_show_overflow'><i class=\"ri-checkbox-circle-line\" style=\"font-size:30px;\"></i><span style=\"display:block\"><strong>Approved On: </strong>" . date('M d Y h:i A', strtotime($child_row->dtApprovedDateTime)) . "</span><span style=\"display:block\"><strong>Approved By: </strong>" . CommonModel::getUserName($child_row->intApprovedBy) . "</span></span></td>";
                }
                $childHtml .= "</tr>";
            }
        } else {
            $childHtml .= "<tr><td colspan='6'>No Records</td></tr>";
        }
        $childHtml .= "</tr></td></tr>";
        $childHtml .= "</tr>
						</table>";
        echo $childHtml;
        exit;
    }

    /**
     * This method handle to get record for Rollback functionality.
     * @since   27-Sep-2018
     * @author  NetQuick Team
     */
    public function getChildData_rollback(Request $request) {
        $requestArr = Request::all();
        $request = (object) $requestArr;
        $child_rollbackHtml = "";
        $Publications_rollbackchildData = "";
        $Publications_rollbackchildData = Organizations::getChildrollbackGrid($request);
        $child_rollbackHtml .= "<div class=\"producttbl producttb2\" style=\"\">";
        $child_rollbackHtml .= "<table class=\"new_table_desing table table-striped table-bordered table-hover table-checkable dataTable\" id=\"email_log_datatable_ajax\">
																<tr role=\"row\">     
                                                                                                                                  <th class=\"text-center\">Title</th>
																		<th class=\"text-center\">Date</th>
																		<th class=\"text-center\">User</th>                                    
																		<th class=\"text-center\">Status</th>";
        $child_rollbackHtml .= "         </tr>";
        if (count($Publications_rollbackchildData) > 0) {
            foreach ($Publications_rollbackchildData as $child_rollbacrow) {
                $child_rollbackHtml .= "<tr role=\"row\">";
                $child_rollbackHtml .= '<td class="text-center"><span class="mob_show_title">Title: </span>' . $child_rollbacrow->varTitle . '</td>';
                $child_rollbackHtml .= "<td class=\"text-center\"><span class='mob_show_title'>Date: </span>" . date('M d Y h:i A', strtotime($child_rollbacrow->created_at)) . "</td>";
                $child_rollbackHtml .= "<td class=\"text-center\"><span class='mob_show_title'>User: </span>" . CommonModel::getUserName($child_rollbacrow->UserID) . "</td>";
                if ($child_rollbacrow->chrApproved == 'Y') {
                    $child_rollbackHtml .= "<td class=\"text-center\"><span class='mob_show_title'>Status: </span><i class=\"ri-checkbox-circle-line\" style=\"color: #1080F2;font-size:30px;\"></i></td>";
                } else {
                    // $child_rollbackHtml .= "<td class=\"text-center\"><span class='mob_show_title'>Status: </span><a onclick=\"update_mainrecord('" . $child_rollbacrow->id . "','" . $child_rollbacrow->fkMainRecord . "','" . $child_rollbacrow->UserID . "','R');\"  class=\"approve_icon_btn\">
					// 						<i class=\"ri-history-line\"></i>  <span>RollBack</span>
                    // 					</a></td>";
                    $child_rollbackHtml .= "<td class=\"text-center\"><span class=\"glyphicon glyphicon-minus\"></span></td>";
                }
                $child_rollbackHtml .= "</tr>";
            }
        } else {
            $child_rollbackHtml .= "<tr><td colspan='6'>No Records</td></tr>";
        }
        echo $child_rollbackHtml;
        exit;
    }

    /**
     * This method handle to retrive comment.
     * @since   27-Sep-2018
     * @author  NetQuick Team
     */
    public function Get_Comments(Request $request) {
        $requestArr = Request::all();
        $request = (object) $requestArr;
        $templateData = Comments::get_comments($request);
        $Comments = "";
        if (count($templateData) > 0) {
            foreach ($templateData as $row_data) {
                if ($row_data->Fk_ParentCommentId == 0) {
                    $Comments .= '<li><p>' . nl2br($row_data->varCmsPageComments) . '</p><span class = "date">' . CommonModel::getUserName($row_data->intCommentBy) . ' ' . date('M d Y h:i A', strtotime($row_data->created_at)) . '</span></li>';
                    $UserComments = Comments::get_usercomments($row_data->id);
                    foreach ($UserComments as $row_comments) {
                        $Comments .= '<li class="user-comments"><p>' . nl2br($row_comments->varCmsPageComments) . '</p><span class = "date">' . CommonModel::getUserName($row_comments->UserID) . ' ' . date('M d Y h:i A', strtotime($row_comments->created_at)) . '</span></li>';
                    }
                }
            }
        } else {
            $Comments .= '<li><p>No Comments yet.</p></li>';
        }
        echo $Comments;
        exit;
    }

    /**
     * This method handle to approve record request from ajax.
     * @since   27-Sep-2018
     * @author  NetQuick Team
     */
    public function ApprovedData_Listing(Request $request) {
        $requestArr = Request::all();
        $request = (object) $requestArr;
        $id = Request::post('id');
        $main_id = Request::post('main_id');
        $approvalid = Request::post('id');
        $approvalData = Organizations::getOrderOfApproval($id);
        $flag = Request::post('flag');
        $main_id = Request::post('main_id');
        $Organization = Organizations::getRecordById($main_id);
        $message = Organizations::approved_data_Listing($request);
        $newCmsPageObj = Organizations::getRecordForLogById($main_id);
        $approval_obj = Organizations::getRecordForLogById($approvalid);
        if ($flag == 'R') {
            $restoredata = Config::get('Constant.ROLLBACK_RECORD');
        } else {
            $restoredata = Config::get('Constant.RECORD_APPROVED');
        }
        /* code for Reorder functionality */
        if (!empty($approvalData)) {
            self::newSwapOrderEdit($approvalData->intDisplayOrder, $Organization);
        }
        if (method_exists($this->MyLibrary, 'userNotificationData')) {
        /* notification for user to record approved */
        $userNotificationArr = MyLibrary::userNotificationData(Config::get('Constant.MODULE.ID'));
        $userNotificationArr['fkRecordId'] = $approvalid;
        $userNotificationArr['txtNotification'] = 'Your request has been approved by ' . ucfirst(auth()->user()->name) . ' (' . ucfirst(Config::get('Constant.MODULE.NAME')) . ')';
        $userNotificationArr['fkIntUserId'] = Auth::user()->id;
        $userNotificationArr['chrNotificationType'] = 'A';
        $userNotificationArr['intOnlyForUserId'] = $approval_obj->UserID;
        UserNotification::addRecord($userNotificationArr);
        /* notification for user to record approved */
        }
        $logArr = MyLibrary::logData($main_id, false, $restoredata);
        $logArr['varTitle'] = stripslashes($newCmsPageObj->varTitle);
        Log::recordLog($logArr);
        $where = [];
        $flowData = [];
        $flowData['dtYes'] = Config::get('Constant.SQLTIMESTAMP');
        $where['fkModuleId'] = Config::get('Constant.MODULE.ID');
        $where['fkRecordId'] = $main_id;
        $where['dtYes'] = 'null';
        WorkflowLog::updateRecord($flowData, $where);
        echo $message;
    }

    public function get_buider_list() {
        $filter = Request::post();
        $rows = '';
        $filterArr = [];
        $records = [];
        $filterArr['orderByFieldName'] = isset($filter['columns']) ? $filter['columns'] : '';
        $filterArr['orderTypeAscOrDesc'] = isset($filter['order']) ? $filter['order'] : '';
        $filterArr['catFilter'] = isset($filter['catValue']) ? $filter['catValue'] : '';
        $filterArr['critaria'] = isset($filter['critaria']) ? $filter['critaria'] : '';
        $filterArr['searchFilter'] = isset($filter['searchValue']) ? trim($filter['searchValue']) : '';
        $filterArr['iDisplayStart'] = isset($filter['start']) ? intval($filter['start']) : 1;
        $filterArr['iDisplayLength'] = isset($filter['length']) ? intval($filter['length']) : 5;
        $filterArr['ignore'] = !empty($filter['ignore']) ? $filter['ignore'] : [];
        $filterArr['selected'] = isset($filter['selected']) && !empty($filter['selected']) ? $filter['selected'] : [];
        $arrResults = Organizations::getBuilderRecordList($filterArr);
        $found = $arrResults->toArray();
        if (!empty($found)) {
            foreach ($arrResults as $key => $value) {
                $rows .= $this->tableDataBuilder($value, false, $filterArr['selected']);
            }
        } else {
            $rows .= '<tr id="not-found"><td colspan="4" align="center">No records found.</td></tr>';
        }
        $iTotalRecords = CommonModel::getTotalRecordCount('Powerpanel\Organizations\Models\Organizations', true, false);
        $records["data"] = $rows;
        $records["found"] = count($found);
        $records["recordsTotal"] = $iTotalRecords;
        return json_encode($records);
    }

    public function tableDataBuilder($value = false, $fcnt = false, $selected = []) {
        $publish_action = '';
        $dtFormat = Config::get('Constant.DEFAULT_DATE_FORMAT');

        $record = '<tr ' . (in_array($value->id, $selected) ? 'class="selected-record"' : '') . '>';
        $record .= '<td width="1%" align="center">';
        $record .= '<label class="mt-checkbox mt-checkbox-outline">';
        $record .= '<input type="checkbox" data-title="' . $value->varTitle . '" name="delete[]" class="chkChoose" ' . (in_array($value->id, $selected) ? 'checked' : '') . ' value="' . $value->id . '">';
        $record .= '<span></span>';
        $record .= '</label>';
        $record .= '</td>';
        $record .= '<td width="32.33%" align="left">';
        $record .= $value->varTitle;
        $record .= '</td>';
        $record .= '<td width="32.33%" align="left">';
        $record .= $value->varDesignation;
        $record .= '</td>';
        $record .= '<td width="32.33%" align="center">';
        $record .= date($dtFormat, strtotime($value->created_at));
        $record .= '</td>';
        $record .= '</tr>';
        return $record;
    }
    
    public function getAllParents() {
        $records = Organizations::getAllParents();
        $opt = '<option value="">Select Parents</option>';
        foreach ($records as $record) {
            $opt .= '<option value="' . $record->id . '">' . $record->varTitle . '</option>';
        }
        return $opt;
    }

    public function rollBackRecord(Request $request) {

        $message = 'Oops! Something went wrong';
        $requestArr = Request::all();
        $request = (object) $requestArr;
        
        $previousRecord = Organizations::getPreviousRecordByMainId($request->id);
        if(!empty($previousRecord)) {

            $main_id = $previousRecord->fkMainRecord;
            $request->id = $previousRecord->id;
            $request->main_id = $main_id;

            $message = Organizations::approved_data_Listing($request);
            
            $newBlogObj = Organizations::getRecordForLogById($main_id);
            $restoredata = Config::get('Constant.ROLLBACK_RECORD');

            /* notification for user to record approved */
            $blogs = Organizations::getRecordForLogById($previousRecord->id);
            if (method_exists($this->MyLibrary, 'userNotificationData')) {
                $userNotificationArr = MyLibrary::userNotificationData(Config::get('Constant.MODULE.ID'));
                $userNotificationArr['fkRecordId'] = $previousRecord->id;
                $userNotificationArr['txtNotification'] = 'Your request has been approved by ' . ucfirst(auth()->user()->name) . ' (' . ucfirst(Config::get('Constant.MODULE.NAME')) . ')';
                $userNotificationArr['fkIntUserId'] = Auth::user()->id;
                $userNotificationArr['chrNotificationType'] = 'A';
                $userNotificationArr['intOnlyForUserId'] = $blogs->UserID;
                UserNotification::addRecord($userNotificationArr);
            }
            /* notification for user to record approved */

            $logArr = MyLibrary::logData($main_id, false, $restoredata);
            $logArr['varTitle'] = stripslashes($newBlogObj->varTitle);
            Log::recordLog($logArr);
            $where = [];
            $flowData = [];
            $flowData['dtYes'] = Config::get('Constant.SQLTIMESTAMP');
            $where['fkModuleId'] = Config::get('Constant.MODULE.ID');
            $where['fkRecordId'] = $main_id;
            $where['dtYes'] = 'null';
            WorkflowLog::updateRecord($flowData, $where);
        }
        echo $message;
    }

}
