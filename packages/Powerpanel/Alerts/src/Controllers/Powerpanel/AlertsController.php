<?php

namespace Powerpanel\Alerts\Controllers\Powerpanel;

use Request;
use Illuminate\Support\Facades\Redirect;
use Powerpanel\CmsPage\Models\CmsPage;
use Powerpanel\Alerts\Models\Alerts;
use App\User;
use App\Log;
use App\RecentUpdates;
use App\Alias;
use Powerpanel\Workflow\Models\WorkflowLog;
use Powerpanel\Workflow\Models\Workflow;
use Validator;
use App\Modules;
use DB;
use App\Http\Controllers\PowerpanelController;
use Auth;
use App\Helpers\MyLibrary;
use App\CommonModel;
use Carbon\Carbon;
use Cache;
use App\Helpers\AddDocumentModelRel;
use App\Document;
use Config;
use File;
use Powerpanel\Workflow\Models\Comments;
use Powerpanel\RoleManager\Models\Role_user;
use App\Helpers\ParentRecordHierarchy_builder;
use App\UserNotification;

class AlertsController extends PowerpanelController {

    /**
     * Create a new controller instance.
     * @return void
     */
    public function __construct() {
        parent::__construct();
        if (isset($_COOKIE['locale'])) {
            app()->setLocale($_COOKIE['locale']);
        }
        $this->MyLibrary = new MyLibrary();
        $this->CommonModel = new CommonModel();
    }

    /**
     * This method handels load banner grid
     * @return  View
     * @since   2017-07-20
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

        $total = Alerts::getRecordCount(false, false, $userIsAdmin, $this->currentUserRoleSector);
        $approvalTotalRecords = Alerts::getRecordCountListApprovalTab(false, false, $userIsAdmin, array(), $this->currentUserRoleSector);
        $draftTotalRecords = Alerts::getRecordCountforListDarft(false, true, $userIsAdmin, array(), $this->currentUserRoleSector);
        $trashTotalRecords = Alerts::getRecordCountforListTrash(false, true, $userIsAdmin, array(), $this->currentUserRoleSector);
        $favoriteTotalRecords = Alerts::getRecordCountforListFavorite(false, true, $userIsAdmin, array(), $this->currentUserRoleSector);

        $this->breadcrumb['title'] = trans('alerts::template.alertsModule.manage');

        if (method_exists($this->CommonModel, 'GridColumnData')) {
            $settingdata = CommonModel::GridColumnData(Config::get('Constant.MODULE.ID'));
            $settingarray = array();
            foreach ($settingdata as $sdata) {
                $settingarray[$sdata->chrtab][] = $sdata->columnid;
            }
        } else {
            $settingarray = '';
        }

        return view('alerts::powerpanel.list', ['userIsAdmin' => $userIsAdmin, 'approvalTotalRecords' => $approvalTotalRecords, 'iTotalRecords' => $total, 'breadcrumb' => $this->breadcrumb, 'draftTotalRecords' => $draftTotalRecords, 'trashTotalRecords' => $trashTotalRecords, 'favoriteTotalRecords' => $favoriteTotalRecords, 'settingarray' => json_encode($settingarray)]);
    }



    public function get_list() {
        $filterArr = [];
        $records = array();
        $records["data"] = array();
        $filterArr['orderColumnNo'] = (!empty(Request::input('order') [0]['column']) ? Request::input('order') [0]['column'] : '');
        $filterArr['orderByFieldName'] = (!empty(Request::input('columns') [$filterArr['orderColumnNo']]['name']) ? Request::input('columns') [$filterArr['orderColumnNo']]['name'] : '');
        $filterArr['orderTypeAscOrDesc'] = (!empty(Request::input('order') [0]['dir']) ? Request::input('order') [0]['dir'] : '');
        $filterArr['searchFilter'] = !empty(Request::input('searchValue')) ? Request::input('searchValue') : '';
        $filterArr['statusFilter'] = !empty(Request::input('statusValue')) ? Request::input('statusValue') : '';
        $filterArr['sectorFilter'] = !empty(Request::input('sectorValue')) ? Request::input('sectorValue') : '';
        $filterArr['bannerFilter'] = !empty(Request::input('bannerFilter')) ? Request::input('bannerFilter') : '';
        $filterArr['linkFilterType'] = !empty(Request::input('linkFilterType')) ? Request::input('linkFilterType') : '';
        $filterArr['pageFilter'] = !empty(Request::input('pageFilter')) ? Request::input('pageFilter') : '';
        $filterArr['iDisplayLength'] = intval(Request::input('length'));
        $filterArr['iDisplayStart'] = intval(Request::input('start'));
        $sEcho = intval(Request::input('draw'));

        $isAdmin = false;
        if (isset($this->currentUserRoleData) && !empty($this->currentUserRoleData)) {
            if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                $isAdmin = true;
            }
        }

        $ignoreId = [];
        $arrResults = Alerts::getRecordList($filterArr, $isAdmin, $ignoreId, $this->currentUserRoleSector);
        $iTotalRecords = Alerts::getRecordCountforList($filterArr, true, $isAdmin, $ignoreId, $this->currentUserRoleSector);
        $allRecordsCount = Alerts::getRecordCountForDorder(false, false, $isAdmin, $this->currentUserRoleSector);

        if (!empty($arrResults)) {
            $currentUserID = auth()->user()->id;
            $permit = [
                'canalertsedit' => Auth::user()->can('alerts-edit'),
                'canalertspublish' => Auth::user()->can('alerts-publish'),
                'canalertsdelete' => Auth::user()->can('alerts-delete'),
                'canalertsreviewchanges' => Auth::user()->can('alerts-reviewchanges'),
                'canloglist' => Auth::user()->can('log-list'),
            ];

            foreach ($arrResults as $key => $value) {
                if (!in_array($value->id, $ignoreId)) {
                    $records['data'][] = $this->tableData($value, $permit, $currentUserID, $allRecordsCount);
                }
            }
        }

        $NewRecordsCount = Alerts::getNewRecordsCount($isAdmin, $this->currentUserRoleSector);
        $records["newRecordCount"] = $NewRecordsCount;
        if (!empty(Request::input('customActionType')) && Request::input('customActionType') == 'group_action') {
            $records['customActionStatus'] = 'OK';
        }
        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;
        echo json_encode($records);
        exit;
    }

    public function get_list_New() {
        $filterArr = [];
        $records = array();
        $records["data"] = array();
        $filterArr['orderColumnNo'] = (!empty(Request::input('order') [0]['column']) ? Request::input('order') [0]['column'] : '');
        $filterArr['orderByFieldName'] = (!empty(Request::input('columns') [$filterArr['orderColumnNo']]['name']) ? Request::input('columns') [$filterArr['orderColumnNo']]['name'] : '');
        $filterArr['orderTypeAscOrDesc'] = (!empty(Request::input('order') [0]['dir']) ? Request::input('order') [0]['dir'] : '');
        $filterArr['searchFilter'] = !empty(Request::input('searchValue')) ? Request::input('searchValue') : '';
        $filterArr['statusFilter'] = !empty(Request::input('statusValue')) ? Request::input('statusValue') : '';
        $filterArr['sectorFilter'] = !empty(Request::input('sectorValue')) ? Request::input('sectorValue') : '';
        $filterArr['bannerFilter'] = !empty(Request::input('bannerFilter')) ? Request::input('bannerFilter') : '';
        $filterArr['linkFilterType'] = !empty(Request::input('linkFilterType')) ? Request::input('linkFilterType') : '';
        $filterArr['pageFilter'] = !empty(Request::input('pageFilter')) ? Request::input('pageFilter') : '';
        $filterArr['iDisplayLength'] = intval(Request::input('length'));
        $filterArr['iDisplayStart'] = intval(Request::input('start'));
        $sEcho = intval(Request::input('draw'));

        $isAdmin = false;
        if (isset($this->currentUserRoleData) && !empty($this->currentUserRoleData)) {
            if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                $isAdmin = true;
            }
        }

        $ignoreId = [];
        $arrResults = Alerts::getRecordListApprovalTab($filterArr, $isAdmin, $ignoreId, $this->currentUserRoleSector);
        $iTotalRecords = Alerts::getRecordCountListApprovalTab($filterArr, true, $isAdmin, $ignoreId, $this->currentUserRoleSector);

        if (!empty($arrResults)) {
            $currentUserID = auth()->user()->id;
            $permit = [
                'canalertsedit' => Auth::user()->can('alerts-edit'),
                'canalertspublish' => Auth::user()->can('alerts-publish'),
                'canalertsdelete' => Auth::user()->can('alerts-delete'),
                'canalertsreviewchanges' => Auth::user()->can('alerts-reviewchanges'),
                'canloglist' => Auth::user()->can('log-list'),
            ];

            foreach ($arrResults as $key => $value) {
                if (!in_array($value->id, $ignoreId)) {
                    $records['data'][] = $this->tableDataWaitingToApprovedData($value, $permit, $currentUserID);
                }
            }
        }

        $NewRecordsCount = Alerts::getNewRecordsCount($isAdmin, $this->currentUserRoleSector);
        $records["newRecordCount"] = $NewRecordsCount;
        if (!empty(Request::input('customActionType')) && Request::input('customActionType') == 'group_action') {
            $records['customActionStatus'] = 'OK';
        }
        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;
        echo json_encode($records);
        exit;
    }

    public function get_list_favorite() {
        $filterArr = [];
        $records = array();
        $records["data"] = array();
        $filterArr['orderColumnNo'] = (!empty(Request::input('order') [0]['column']) ? Request::input('order') [0]['column'] : '');
        $filterArr['orderByFieldName'] = (!empty(Request::input('columns') [$filterArr['orderColumnNo']]['name']) ? Request::input('columns') [$filterArr['orderColumnNo']]['name'] : '');
        $filterArr['orderTypeAscOrDesc'] = (!empty(Request::input('order') [0]['dir']) ? Request::input('order') [0]['dir'] : '');
        $filterArr['searchFilter'] = !empty(Request::input('searchValue')) ? Request::input('searchValue') : '';
        $filterArr['statusFilter'] = !empty(Request::input('statusValue')) ? Request::input('statusValue') : '';
        $filterArr['sectorFilter'] = !empty(Request::input('sectorValue')) ? Request::input('sectorValue') : '';
        $filterArr['bannerFilter'] = !empty(Request::input('bannerFilter')) ? Request::input('bannerFilter') : '';
        $filterArr['linkFilterType'] = !empty(Request::input('linkFilterType')) ? Request::input('linkFilterType') : '';
        $filterArr['pageFilter'] = !empty(Request::input('pageFilter')) ? Request::input('pageFilter') : '';
        $filterArr['iDisplayLength'] = intval(Request::input('length'));
        $filterArr['iDisplayStart'] = intval(Request::input('start'));
        $iDisplayLength = intval(Request::input('length'));
        $iDisplayStart = intval(Request::input('start'));
        $sEcho = intval(Request::input('draw'));

        $isAdmin = false;
        if (isset($this->currentUserRoleData) && !empty($this->currentUserRoleData)) {
            if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                $isAdmin = true;
            }
        }

        $ignoreId = [];
        $arrResults = Alerts::getRecordListFavorite($filterArr, $isAdmin, $ignoreId, $this->currentUserRoleSector);
        $iTotalRecords = Alerts::getRecordCountforListFavorite($filterArr, true, $isAdmin, $ignoreId, $this->currentUserRoleSector);

        if (!empty($arrResults)) {
            $currentUserID = auth()->user()->id;
            $permit = [
                'canalertsedit' => Auth::user()->can('alerts-edit'),
                'canalertspublish' => Auth::user()->can('alerts-publish'),
                'canalertsdelete' => Auth::user()->can('alerts-delete'),
                'canalertsreviewchanges' => Auth::user()->can('alerts-reviewchanges'),
                'canloglist' => Auth::user()->can('log-list'),
            ];

            foreach ($arrResults as $key => $value) {
                if (!in_array($value->id, $ignoreId)) {
                    $records['data'][] = $this->tableDataFavorite($value, $permit, $currentUserID);
                }
            }
        }

        $NewRecordsCount = Alerts::getNewRecordsCount($isAdmin, $this->currentUserRoleSector);
        $records["newRecordCount"] = $NewRecordsCount;
        if (!empty(Request::input('customActionType')) && Request::input('customActionType') == 'group_action') {
            $records['customActionStatus'] = 'OK';
        }
        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;
        echo json_encode($records);
        exit;
    }

    public function get_list_draft() {
        $filterArr = [];
        $records = array();
        $records["data"] = array();
        $filterArr['orderColumnNo'] = (!empty(Request::input('order') [0]['column']) ? Request::input('order') [0]['column'] : '');
        $filterArr['orderByFieldName'] = (!empty(Request::input('columns') [$filterArr['orderColumnNo']]['name']) ? Request::input('columns') [$filterArr['orderColumnNo']]['name'] : '');
        $filterArr['orderTypeAscOrDesc'] = (!empty(Request::input('order') [0]['dir']) ? Request::input('order') [0]['dir'] : '');
        $filterArr['searchFilter'] = !empty(Request::input('searchValue')) ? Request::input('searchValue') : '';
        $filterArr['sectorFilter'] = !empty(Request::input('sectorValue')) ? Request::input('sectorValue') : '';
        $filterArr['statusFilter'] = !empty(Request::input('statusValue')) ? Request::input('statusValue') : '';
        $filterArr['bannerFilter'] = !empty(Request::input('bannerFilter')) ? Request::input('bannerFilter') : '';
        $filterArr['linkFilterType'] = !empty(Request::input('linkFilterType')) ? Request::input('linkFilterType') : '';
        $filterArr['pageFilter'] = !empty(Request::input('pageFilter')) ? Request::input('pageFilter') : '';
        $filterArr['iDisplayLength'] = intval(Request::input('length'));
        $filterArr['iDisplayStart'] = intval(Request::input('start'));
        $sEcho = intval(Request::input('draw'));

        $isAdmin = false;
        if (isset($this->currentUserRoleData) && !empty($this->currentUserRoleData)) {
            if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                $isAdmin = true;
            }
        }

        $ignoreId = [];
        $arrResults = Alerts::getRecordListDraft($filterArr, $isAdmin, $ignoreId, $this->currentUserRoleSector);
        $iTotalRecords = Alerts::getRecordCountforListDarft($filterArr, true, $isAdmin, $ignoreId, $this->currentUserRoleSector);

        if (!empty($arrResults)) {
            $currentUserID = auth()->user()->id;
            $permit = [
                'canalertsedit' => Auth::user()->can('alerts-edit'),
                'canalertspublish' => Auth::user()->can('alerts-publish'),
                'canalertsdelete' => Auth::user()->can('alerts-delete'),
                'canalertsreviewchanges' => Auth::user()->can('alerts-reviewchanges'),
                'canloglist' => Auth::user()->can('log-list'),
            ];

            foreach ($arrResults as $key => $value) {
                if (!in_array($value->id, $ignoreId)) {
                    $records['data'][] = $this->tableDataDraft($value, $permit, $currentUserID);
                }
            }
        }

        $NewRecordsCount = Alerts::getNewRecordsCount($isAdmin, $this->currentUserRoleSector);
        $records["newRecordCount"] = $NewRecordsCount;
        if (!empty(Request::input('customActionType')) && Request::input('customActionType') == 'group_action') {
            $records['customActionStatus'] = 'OK';
        }
        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;
        echo json_encode($records);
        exit;
    }

    public function get_list_trash() {
        $filterArr = [];
        $records = array();
        $records["data"] = array();
        $filterArr['orderColumnNo'] = (!empty(Request::input('order') [0]['column']) ? Request::input('order') [0]['column'] : '');
        $filterArr['orderByFieldName'] = (!empty(Request::input('columns') [$filterArr['orderColumnNo']]['name']) ? Request::input('columns') [$filterArr['orderColumnNo']]['name'] : '');
        $filterArr['orderTypeAscOrDesc'] = (!empty(Request::input('order') [0]['dir']) ? Request::input('order') [0]['dir'] : '');
        $filterArr['searchFilter'] = !empty(Request::input('searchValue')) ? Request::input('searchValue') : '';
        $filterArr['sectorFilter'] = !empty(Request::input('sectorValue')) ? Request::input('sectorValue') : '';
        $filterArr['statusFilter'] = !empty(Request::input('statusValue')) ? Request::input('statusValue') : '';
        $filterArr['bannerFilter'] = !empty(Request::input('bannerFilter')) ? Request::input('bannerFilter') : '';
        $filterArr['linkFilterType'] = !empty(Request::input('linkFilterType')) ? Request::input('linkFilterType') : '';
        $filterArr['pageFilter'] = !empty(Request::input('pageFilter')) ? Request::input('pageFilter') : '';
        $filterArr['iDisplayLength'] = intval(Request::input('length'));
        $filterArr['iDisplayStart'] = intval(Request::input('start'));
        $sEcho = intval(Request::input('draw'));

        $isAdmin = false;
        if (isset($this->currentUserRoleData) && !empty($this->currentUserRoleData)) {
            if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                $isAdmin = true;
            }
        }

        $ignoreId = [];
        $arrResults = Alerts::getRecordListTrash($filterArr, $isAdmin, $ignoreId, $this->currentUserRoleSector);
        $iTotalRecords = Alerts::getRecordCountforListTrash($filterArr, true, $isAdmin, $ignoreId, $this->currentUserRoleSector);

        if (!empty($arrResults)) {
            $currentUserID = auth()->user()->id;
            $permit = [
                'canalertsedit' => Auth::user()->can('alerts-edit'),
                'canalertspublish' => Auth::user()->can('alerts-publish'),
                'canalertsdelete' => Auth::user()->can('alerts-delete'),
                'canalertsreviewchanges' => Auth::user()->can('alerts-reviewchanges'),
                'canloglist' => Auth::user()->can('log-list'),
            ];

            foreach ($arrResults as $key => $value) {
                if (!in_array($value->id, $ignoreId)) {
                    $records['data'][] = $this->tableDataTrash($value, $permit, $currentUserID);
                }
            }
        }

        $NewRecordsCount = Alerts::getNewRecordsCount($isAdmin, $this->currentUserRoleSector);
        $records["newRecordCount"] = $NewRecordsCount;
        if (!empty(Request::input('customActionType')) && Request::input('customActionType') == 'group_action') {
            $records['customActionStatus'] = 'OK';
        }
        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;
        echo json_encode($records);
        exit;
    }



    public function edit($id = false) {
        $documentManager = true;

        $sector = array('ofreg' => 'OFREG' , 'water' => 'WATER' , 'ict' => 'ICT' , 'energy' => 'ENERGY' , 'fuel' => 'FUEL');
        if (method_exists($this->MyLibrary, 'getModuleNamesWithoutAlias')) {
            $withoutAliasModulesNames = MyLibrary::getModuleNamesWithoutAlias();
        } else {
            $withoutAliasModulesNames = '';
        }
        if (method_exists($this->MyLibrary, 'getModuleIdsByNames')) {
            $withoutAliasModules = Modules::getModuleIdsByNames($withoutAliasModulesNames);
        } else {
            $withoutAliasModules = '';
        }
        $withoutAliasModuleIds = array();
        if (!empty($withoutAliasModules)) {
            $withoutAliasModuleIds = array_column($withoutAliasModules, 'id');
        }
        array_push($withoutAliasModuleIds, 52);
        $module = Modules::getFrontModulesList($withoutAliasModuleIds);
        $templateData = array();
        $userIsAdmin = false;
        if (isset($this->currentUserRoleData) && !empty($this->currentUserRoleData)) {
            if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                $userIsAdmin = true;
            }
        }else{
            $userIsAdmin = true;
        }
        if (!is_numeric($id)) {
            $total = Alerts::getRecordCount(false, false, $userIsAdmin, $this->currentUserRoleSector);
            if (Auth::user()->can('alerts-create') || $userIsAdmin) {
                $total = $total + 1;
            }
            $this->breadcrumb['title'] = trans('alerts::template.alertsModule.add');
            $this->breadcrumb['module'] = trans('alerts::template.alertsModule.manage');
            $this->breadcrumb['url'] = 'powerpanel/alerts';
            $this->breadcrumb['inner_title'] = '';
            $breadcrumb = $this->breadcrumb;
            $templateData['modules'] = $module;
            $templateData['total'] = $total;
            $templateData['breadcrumb'] = $this->breadcrumb;
        } else {
            $alerts = Alerts::getRecordById($id);
            if (empty($alerts)) {
                return redirect()->route('powerpanel.alerts.add');
            }
            if ((int) $alerts->fkMainRecord !== 0) {
                $alertsHighLight = Alerts::getRecordById($alerts->fkMainRecord);
                $templateData['alertsHighLight'] = $alertsHighLight;
            }
            $this->breadcrumb['title'] = trans('alerts::template.alertsModule.edit');
            $this->breadcrumb['module'] = trans('alerts::template.alertsModule.manage');
            $this->breadcrumb['url'] = 'powerpanel/alerts';
            $this->breadcrumb['inner_title'] = $alerts->varTitle;
            $breadcrumb = $this->breadcrumb;
            $templateData['alerts'] = $alerts;
            $templateData['modules'] = $module;
            $templateData['breadcrumb'] = $this->breadcrumb;
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
                    $templateData['chrNeedAddPermission'] = $workFlowByCat->chrNeedAddPermission;
                    $templateData['charNeedApproval'] = $workFlowByCat->charNeedApproval;
                } else {
                    $templateData['chrNeedAddPermission'] = 'N';
                    $templateData['charNeedApproval'] = 'N';
                }
            }
        } else {
            $templateData['chrNeedAddPermission'] = 'N';
            $templateData['charNeedApproval'] = 'N';
        }
        //End Button Name Change For User Side
        $templateData['sector'] = $sector;
        $templateData['userIsAdmin'] = $userIsAdmin;
        return view('alerts::powerpanel.actions', $templateData);
    }

    /**
     * This method stores banner modifications
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
        $postArr = Request::input();
        $alertsFields = [];
        $actionMessage = trans('alerts::template.common.oppsSomethingWrong');
        $rules = array(
            'title' => 'required|max:160|handle_xss|no_url',
            'display_order' => 'required|greater_than_zero|handle_xss|no_url',
            'chrMenuDisplay' => 'required'
        );
        $rules['modules'] = 'required';
        $rules['foritem'] = 'required';
        $messsages = array(
            'title.required' => "Title field is required.",
            'modules.required' => "Module field is required.",
            'display_order.required' => trans('alerts::template.alertsModule.displayOrder'),
            'display_order.greater_than_zero' => trans('alerts::template.alertsModule.displayGreaterThan'),
            'foritem.required' => trans('alerts::template.alertsModule.pageValidationMessage')
        );
        $validator = Validator::make($postArr, $rules, $messsages);
        if ($validator->passes()) {
            $module = Modules::getModuleById(Config::get('Constant.MODULE.ID'));
            if (isset($this->currentUserRoleData)) {
                $currentUserRoleData = $this->currentUserRoleData;
            }
            $pageId = 0;
            $moduleId = $postArr['modules'];
            $pageId = $postArr['foritem'];
            $alertsFields['varTitle'] = stripslashes(trim($postArr['title']));
            $alertsFields['fkIntPageId'] = $pageId;
            $alertsFields['fkModuleId'] = $moduleId;
            if ($postArr['chrMenuDisplay'] == 'D') {
                $alertsFields['chrDraft'] = 'D';
                $alertsFields['chrPublish'] = 'N';
            } else {
                $alertsFields['chrDraft'] = 'N';
                $alertsFields['chrPublish'] = $postArr['chrMenuDisplay'];
            }
            if(Config::get('Constant.CHRSearchRank') == 'Y'){
            $alertsFields['intSearchRank'] = $postArr['search_rank'];
            }
            $alertsFields['intAlertType'] = $postArr['alert_type'];
            
            $alertsFields['dtDateTime'] = !empty($postArr['start_date_time']) ? date('Y-m-d H:i:s', strtotime($postArr['start_date_time'])) : date('Y-m-d H:i:s');
            $alertsFields['dtEndDateTime'] = !empty($postArr['end_date_time']) ? date('Y-m-d H:i:s', strtotime($postArr['end_date_time'])) : null;
            
            $alertsFields['created_at'] = Carbon::now();
            $id = Request::segment(3);
            if (is_numeric($id)) { #Edit post Handler=======
                $alerts = Alerts::getRecordForLogById($id);
                $alertsFields['fkIntDocId'] = !empty($postArr['doc_id']) ? $postArr['doc_id'] : null;
                $alertsFields['varShortDescription'] = stripslashes(trim($postArr['short_description']));
                $alertsFields['UserID'] = auth()->user()->id;
                $whereConditions = ['id' => $id];
                if ($alerts->chrLock == 'Y' && auth()->user()->id != $alerts->LockUserID) {
                    if ($this->currentUserRoleData->chrIsAdmin != 'Y') {
                        $lockedUserData = User::getRecordById($alerts->LockUserID, true);
                        $lockedUserName = 'someone';
                        if (!empty($lockedUserData)) {
                            $lockedUserName = $lockedUserData->name;
                        }
                        $actionMessage = "This record has been locked by " . $lockedUserName . ".";
                        return redirect()->route('powerpanel.alerts.index')->with('message', $actionMessage);
                    }
                }
                $alertsFields['varSector'] = $postArr['sector'];
                if (File::exists(app_path() . '/Workflow.php') != null || File::exists(base_path() . '/packages/Powerpanel/Workflow/src/Models/Workflow.php') != null) {
                    if (!$userIsAdmin) {
                        $userRole = $currentUserRoleData->id;
                    } else {
                        $userRoleData = Role_user::getUserRoleByUserId($alerts->UserID);
                        if (isset($userRoleData->role_id)) {
                            $userRole = $userRoleData->role_id;
                        } else {
                            $userRole = $this->currentUserRoleData->id;
                        }
                    }
                    $workFlowByCat = Workflow::getRecordByCategoryId($module->intFkGroupCode, $userRole, Config::get('Constant.MODULE.ID'));
                    if (empty($workFlowByCat->varUserId) || $userIsAdmin || $workFlowByCat->charNeedApproval == 'N') {
                        if ((int) $alerts->fkMainRecord === 0 || empty($workFlowByCat->varUserId)) {
                            $update = CommonModel::updateRecords($whereConditions, $alertsFields, false, 'Powerpanel\Alerts\Models\Alerts');
                            if ($update) {
                                if (!empty($id)) {
                                    self::swap_order_edit($postArr['display_order'], $id);
                                    $logArr = MyLibrary::logData($id);
                                    if (Auth::user()->can('log-advanced')) {
                                        $newQuickLinkObj = Alerts::getRecordForLogById($id);
                                        $oldRec = $this->recordHistory($alerts);
                                        $newRec = $this->newrecordHistory($alerts, $newQuickLinkObj);
                                        // $newRec = $this->recordHistory($newQuickLinkObj);
                                        $logArr['old_val'] = $oldRec;
                                        $logArr['new_val'] = $newRec;
                                    }
                                    $logArr['varTitle'] = stripslashes(trim($postArr['title']));
                                    Log::recordLog($logArr);
                                    if (Auth::user()->can('recent-updates-list')) {
                                        if (!isset($newQuickLinkObj)) {
                                            $newQuickLinkObj = Alerts::getRecordForLogById($id);
                                        }
                                        $notificationArr = MyLibrary::notificationData($id, $newQuickLinkObj);
                                        RecentUpdates::setNotification($notificationArr);
                                    }
                                }
                                self::flushCache();
                                if (isset($postArr['saveandexit']) && $postArr['saveandexit'] == 'approvesaveandexit') {
                                    $actionMessage = trans('alerts::template.common.recordApprovalMessage');
                                } else {
                                    $actionMessage = trans('alerts::template.alertsModule.updateMessage');
                                }
                            }
                        } else {
                            $updateModuleFields = $alertsFields;
                            $this->insertApprovedRecord($updateModuleFields, $postArr, $id);
                            if (isset($postArr['saveandexit']) && $postArr['saveandexit'] == 'approvesaveandexit') {
                                $actionMessage = trans('alerts::template.common.recordApprovalMessage');
                            } else {
                                $actionMessage = trans('alerts::.alertsModule.updateMessage');
                            }
                        }
                    } else {
                        if ($workFlowByCat->charNeedApproval == 'Y') {
                            $this->insertApprovalRecord($alerts, $postArr, $alertsFields);
                            if (isset($postArr['saveandexit']) && $postArr['saveandexit'] == 'approvesaveandexit') {
                                $actionMessage = trans('alerts::template.common.recordApprovalMessage');
                            } else {
                                $actionMessage = trans('alerts::template.alertsModule.updateMessage');
                            }
                        }
                    }
                } else {
                    $update = CommonModel::updateRecords($whereConditions, $alertsFields, false, 'Powerpanel\Alerts\Models\Alerts');
                    $actionMessage = trans('alerts::template.alertsModule.updateMessage');
                }
            } else { #Add post Handler=======
                if (File::exists(app_path() . '/Workflow.php') != null || File::exists(base_path() . '/packages/Powerpanel/Workflow/src/Models/Workflow.php') != null) {
                    $workFlowByCat = Workflow::getRecordByCategoryId($module->intFkGroupCode, $currentUserRoleData->id, Config::get('Constant.MODULE.ID'));
                }
                if (!empty($workFlowByCat->varUserId) && $workFlowByCat->chrNeedAddPermission == 'Y' && !$userIsAdmin) {
                    $alertsFields['chrPublish'] = 'N';
                    $alertsFields['chrDraft'] = 'N';
                    $alertsObj = $this->insertNewRecord($postArr, $alertsFields);
                    $alertsFields['chrPublish'] = 'Y';
                    $this->insertApprovalRecord($alertsObj, $postArr, $alertsFields);
                } else {
                    $alertsObj = $this->insertNewRecord($postArr, $alertsFields);
                }
                if (isset($postArr['saveandexit']) && $postArr['saveandexit'] == 'approvesaveandexit') {
                    $actionMessage = trans('alerts::template.common.recordApprovalMessage');
                } else {
                    $actionMessage = trans('alerts::template.alertsModule.addedMessage');
                }
                $id = $alertsObj->id;
            }
            AddDocumentModelRel::sync(explode(',', $postArr['doc_id']), $id);
            if ((!empty($postArr['saveandexit']) && $postArr['saveandexit'] == 'saveandexit') || !$userIsAdmin) {
                return redirect()->route('powerpanel.alerts.index')->with('message', $actionMessage);
            } else {
                return redirect()->route('powerpanel.alerts.edit', $id)->with('message', $actionMessage);
            }
        } else {
            return Redirect::back()->withErrors($validator)->withInput();
        }
    }

    public function insertApprovedRecord($updateModuleFields, $postArr, $id) {
        $whereConditions = ['id' => $postArr['fkMainRecord']];
        $updateModuleFields['chrAddStar'] = 'N';
        $update = CommonModel::updateRecords($whereConditions, $updateModuleFields, false, 'Powerpanel\Alerts\Models\Alerts');
        if ($update) {
            self::swap_order_edit($postArr['display_order'], $postArr['fkMainRecord']);
        }
        $whereConditions_ApproveN = ['fkMainRecord' => $postArr['fkMainRecord']];
        $updateToApproveN = [
            'chrApproved' => 'N',
            'chrLetest' => 'N',
            'intApprovedBy' => '0',
        ];
        $update = CommonModel::updateRecords($whereConditions_ApproveN, $updateToApproveN, false, 'Powerpanel\Alerts\Models\Alerts');
        $whereConditionsApprove = ['id' => $id, 'chrMain' => 'N'];
        $updateToApprove = [
            'chrApproved' => 'Y',
            'chrRollBack' => 'Y',
            'intApprovedBy' => auth()->user()->id
        ];
        $update = CommonModel::updateRecords($whereConditionsApprove, $updateToApprove, false, 'Powerpanel\Alerts\Models\Alerts');

        if ($postArr['chrMenuDisplay'] == 'D') {
            $addlog = Config::get('Constant.DRAFT_RECORD_APPROVED');
        } else {
            $addlog = Config::get('Constant.RECORD_APPROVED');
        }
        $newAlertsObj = Alerts::getRecordForLogById($id);
        $logArr = MyLibrary::logData($id, false, $addlog);
        $logArr['varTitle'] = stripslashes($newAlertsObj->varTitle);
        Log::recordLog($logArr);
        if (method_exists($this->MyLibrary, 'userNotificationData')) {
            /* notification for user to record approved */
            $userNotificationArr = MyLibrary::userNotificationData(Config::get('Constant.MODULE.ID'));
            $userNotificationArr['fkRecordId'] = $id;
            $userNotificationArr['txtNotification'] = 'Your request has been approved by ' . ucfirst(auth()->user()->name) . ' (' . ucfirst(Config::get('Constant.MODULE.NAME')) . ')';
            $userNotificationArr['fkIntUserId'] = Auth::user()->id;
            $userNotificationArr['chrNotificationType'] = 'A';
            $userNotificationArr['intOnlyForUserId'] = $newAlertsObj->UserID;
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
                $actionMessage = trans('alerts::template.alertsModule.updateMessage');
            }
        }
    }

    public function insertApprovalRecord($moduleObj, $postArr, $alertsFields) {
        #Add post Handler=======
        $alertsFields['chrMain'] = 'N';
        $alertsFields['chrLetest'] = 'Y';
        $alertsFields['UserID'] = auth()->user()->id;
        $alertsFields['fkMainRecord'] = $moduleObj->id;
        if(Config::get('Constant.CHRSearchRank') == 'Y'){
        $alertsFields['intSearchRank'] = $postArr['search_rank'];
        }
        $alertsFields['varShortDescription'] = stripslashes(trim($postArr['short_description']));
        $alertsFields['intAlertType'] = $postArr['alert_type'];
      
        $alertsFields['dtDateTime'] = !empty($postArr['start_date_time']) ? date('Y-m-d H:i:s', strtotime($postArr['start_date_time'])) : date('Y-m-d H:i:s');
        $alertsFields['dtEndDateTime'] = !empty($postArr['end_date_time']) ? date('Y-m-d H:i:s', strtotime($postArr['end_date_time'])) : null;
       
        $alertsFields['intDisplayOrder'] = $postArr['display_order'];
        if ($postArr['chrMenuDisplay'] == 'D') {
            $addlog = Config::get('Constant.DRAFT_SENT_FOR_APPROVAL');
        } else {
            $addlog = Config::get('Constant.SENT_FOR_APPROVAL');
        }
        $alertsID = CommonModel::addRecord($alertsFields, 'Powerpanel\Alerts\Models\Alerts');
        if (!empty($alertsID)) {
            $id = $alertsID;
            WorkflowLog::addRecord([
                'fkModuleId' => Config::get('Constant.MODULE.ID'),
                'fkRecordId' => $moduleObj->id,
                'charApproval' => 'Y'
            ]);
            if (method_exists($this->MyLibrary, 'userNotificationData')) {
                $userNotificationArr = MyLibrary::userNotificationData(Config::get('Constant.MODULE.ID'));
                $userNotificationArr['fkRecordId'] = $moduleObj->id;
                $userNotificationArr['txtNotification'] = 'New approval request from ' . ucfirst(auth()->user()->name) . ' (' . ucfirst(Config::get('Constant.MODULE.NAME')) . ')';
                $userNotificationArr['fkIntUserId'] = Auth::user()->id;
                $userNotificationArr['chrNotificationType'] = 'A';
                UserNotification::addRecord($userNotificationArr);
            }
            $alertsObj = Alerts::getRecordForLogById($id);
            $logArr = MyLibrary::logData($id, false, $addlog);
            $logArr['varTitle'] = $alertsObj->varTitle;
            Log::recordLog($logArr);
            if (Auth::user()->can('recent-updates-list')) {
                $notificationArr = MyLibrary::notificationData($id, $alertsObj);
                RecentUpdates::setNotification($notificationArr);
            }
            self::flushCache();
            $actionMessage = trans('alerts::template.alertsModule.addedMessage');
            $whereConditionsAddstar = ['id' => $moduleObj->id];
            $updateAddStar = [
                'chrAddStar' => 'Y',
            ];
            CommonModel::updateRecords($whereConditionsAddstar, $updateAddStar, false, 'Powerpanel\Alerts\Models\Alerts');
        }
    }

    public function insertNewRecord($postArr, $alertsFields) {
        $response = false;
        $alertsFields['chrMain'] = 'Y';
        $alertsFields['UserID'] = auth()->user()->id;
        if(Config::get('Constant.CHRSearchRank') == 'Y'){
        $alertsFields['intSearchRank'] = $postArr['search_rank'];
        }
        $alertsFields['intAlertType'] = $postArr['alert_type'];
        $alertsFields['varSector'] = $postArr['sector'];
        $alertsFields['varShortDescription'] = stripslashes(trim($postArr['short_description']));
        $alertsFields['fkIntDocId'] = !empty($postArr['doc_id']) ? $postArr['doc_id'] : null;
        $alertsFields['dtDateTime'] = !empty($postArr['start_date_time']) ? date('Y-m-d H:i:s', strtotime($postArr['start_date_time'])) : date('Y-m-d H:i:s');
        $alertsFields['dtEndDateTime'] = !empty($postArr['end_date_time']) ? date('Y-m-d H:i:s', strtotime($postArr['end_date_time'])) : null;
        
        $alertsFields['intDisplayOrder'] = self::swap_order_add($postArr['display_order']);
        if ($postArr['chrMenuDisplay'] == 'D') {
            $alertsFields['chrDraft'] = 'D';
            $alertsFields['chrPublish'] = 'N';
        } else {
            $alertsFields['chrDraft'] = 'N';
        }
        $quicklinkID = CommonModel::addRecord($alertsFields, 'Powerpanel\Alerts\Models\Alerts');
        if (!empty($quicklinkID)) {
            $id = $quicklinkID;
            $newQuickLinkObj = Alerts::getRecordForLogById($id);
            $logArr = MyLibrary::logData($id);
            $logArr['varTitle'] = $newQuickLinkObj->varTitle;
            Log::recordLog($logArr);
            if (Auth::user()->can('recent-updates-list')) {
                $notificationArr = MyLibrary::notificationData($id, $newQuickLinkObj);
                RecentUpdates::setNotification($notificationArr);
            }
            $response = $newQuickLinkObj;
            self::flushCache();
            $actionMessage = trans('alerts::template.alertsModule.addedMessage');
        }
        return $response;
    }

    /**
     * This method destroys Banner in multiples
     * @return  Banner index view
     * @since   2016-10-25
     * @author  NetQuick
     */
    public function DeleteRecord(Request $request) {
        $value = Request::input('value');
        $data['ids'] = Request::input('ids');
        $moduleHaveFields = ['chrMain'];
        $update = MyLibrary::deleteMultipleRecords($data, $moduleHaveFields, $value, 'Powerpanel\Alerts\Models\Alerts');
        if (File::exists(app_path() . '/Comments.php') != null || File::exists(base_path() . '/packages/Powerpanel/Workflow/src/Models/Comments.php') != null) {
            Comments::deleteComments($data['ids'], Config::get('Constant.MODULE.MODEL_NAME'));
        }
        foreach ($update as $ids) {
            $ignoreDeleteScope = true;
            $Deleted_Record = Alerts::getRecordById($ids, $ignoreDeleteScope);
            $Cnt_Letest = Alerts::getRecordCount_letest($Deleted_Record['fkMainRecord'], $Deleted_Record['id']);
            if ($Cnt_Letest <= 0) {
                $updateLetest = [
                    'chrAddStar' => 'N',
                ];
                $whereConditionsApprove = ['id' => $Deleted_Record['fkMainRecord']];
                CommonModel::updateRecords($whereConditionsApprove, $updateLetest, false, 'Powerpanel\Alerts\Models\Alerts');
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
        Alerts::ReorderAllrecords();
        self::flushCache();
        echo json_encode($update);
        exit;
    }

    /**
     * This method destroys Banner in multiples
     * @return  Banner index view
     * @since   2016-10-25
     * @author  NetQuick
     */
    public function publish(Request $request) {
        $alias = (int) Request::input('alias');
        $val = Request::get('val');
        $update = MyLibrary::setPublishUnpublish($alias, $val, 'Powerpanel\Alerts\Models\Alerts');
        self::flushCache();
        echo json_encode($update);
        exit;
    }

    /**
     * This method reorders banner position
     * @return  Banner index view data
     * @since   2016-10-26
     * @author  NetQuick
     */
    public function reorder() {
        $order = Request::input('order');
        $exOrder = Request::input('exOrder');
        MyLibrary::swapOrder($order, $exOrder, 'Powerpanel\Alerts\Models\Alerts');
        Alerts::ReorderAllrecords();
        self::flushCache();
    }

    /**
     * This method handels swapping of available order record while adding
     * @param  	order
     * @return  order
     * @since   2016-10-21
     * @author  NetQuick
     */
    public static function swap_order_add($order = null) {
        $response = false;
        $isCustomizeModule = true;
        $moduleHaveFields = ['chrMain'];
        if ($order != null) {
        		Alerts::ReorderAllrecords();
            $response = MyLibrary::swapOrderAdd($order, $isCustomizeModule, $moduleHaveFields,'Powerpanel\Alerts\Models\Alerts');
            self::flushCache();
        }
        return $response;
    }

    /**
     * This method handels swapping of available order record while editing
     * @param  	order
     * @return  order
     * @since   2016-12-23
     * @author  NetQuick
     */
    public static function swap_order_edit($order = null, $id = null) {
        $isCustomizeModule = true;
        $moduleHaveFields = ['chrMain'];
        MyLibrary::swapOrderEdit($order, $id, $isCustomizeModule, $moduleHaveFields, 'Powerpanel\Alerts\Models\Alerts');
        Alerts::ReorderAllrecords();
        self::flushCache();
    }

    /**
     * This method handels getting category and it's records (ajax)  
     * @return  	JSON object
     * @since   	2016-12-23
     * @author  	NetQuick
     */
    public function selectRecords() {
        $data = Request::input();
        $module = (isset($data['id'])) ? $data['id'] : '';
        // $module = $data['module'];
        $selected = $data['selected'];
        $module = Modules::getModuleById($module);
        if ($module->varModuleNameSpace != '') {
            $modelNameSpace = $module->varModuleNameSpace . 'Models\\' . $module->varModelName;
        } else {
            $modelNameSpace = '\\App\\' . $module->varModelName;
        }
        $recordSelect = '<option value="">'.trans('alerts::template.alertsModule.selectPage').'</option>';
        if (\Schema::hasColumn($module->varTableName, 'intDisplayOrder')) {
            $filterArray = [];
            $filterArray['orderByFieldName'] = 'intDisplayOrder';
            $filterArray['orderTypeAscOrDesc'] = 'asc';
            $moduleRec = $modelNameSpace::getRecordList($filterArray);
        } else {
            $moduleRec = $modelNameSpace::getRecordList();
        }
        $parentcategorymodles = array();
        if (in_array($module->varModuleName, $parentcategorymodles)) {
            $recordSelect .= ParentRecordHierarchy_builder::Hierarchy_OnlyOptionsForQlinks($moduleRec, $module, $selected);
        } else {
            foreach ($moduleRec as $record) {
                if (strtolower($record->varTitle) != 'home') {
                    $recordSelect .= '<option data-moduleid="' . $module->id . '" value="' . $record->id . '" ' . ($record->id == $selected ? 'selected' : '') . '>' . ucwords($record->varTitle) . '</option>';
                }
            }
        }
        return $recordSelect;
    }





    public function tableData($value , $permit, $currentUserID, $allRecordsCount) {

        // Checkbox
        $checkbox = view('powerpanel.partials.checkbox', ['name'=>'delete', 'value'=>$value->id])->render();


        // Date
        // $startDate = date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($value->dtDateTime));

        $startDate = '<span align="left" data-bs-toggle="tooltip" data-bs-placement="bottom" title="'.date(Config::get("Constant.DEFAULT_DATE_FORMAT").' '.Config::get("Constant.DEFAULT_TIME_FORMAT"), strtotime($value->dtDateTime)).'">'.date(Config::get('Constant.DEFAULT_DATE_FORMAT'), strtotime($value->dtDateTime)).'</span>';
        // $endDate = !empty($value->dtEndDateTime) ? date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($value->dtEndDateTime)) : 'No Expiry';
        $endDate = !empty($value->dtEndDateTime) ? '<span align="left" data-bs-toggle="tooltip" data-bs-placement="bottom" title="'.date(Config::get("Constant.DEFAULT_DATE_FORMAT").' '.Config::get("Constant.DEFAULT_TIME_FORMAT"), strtotime($value->dtEndDateTime)).'">'.date(Config::get('Constant.DEFAULT_DATE_FORMAT'), strtotime($value->dtEndDateTime)).'</span>' : 'No Expiry';

        // Title
        $title = $value->varTitle;


        // Publish Action
        $publish_action = '';
        if ($value->chrAddStar != 'Y') {
            if ($value->chrDraft != 'D') {
                if ($permit['canalertspublish']) {
                    if ($value->chrPublish == 'Y') {
                        $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/alerts', 'data_alias'=>$value->id, 'title'=>trans("alerts::template.common.publishedRecord"), 'data_value'=>'Unpublish', 'checked'=>'checked'])->render();
                    } else {
                        $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/alerts', 'data_alias'=>$value->id, 'title'=>trans("alerts::template.common.unpublishedRecord"), 'data_value'=>'Publish'])->render();
                    }
                } else {
                    $publish_action = "-";
                }
            } else {
                if ($permit['canalertspublish']) {
                    if ($value->chrPublish == 'Y') {
                        $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/alerts', 'data_alias'=>$value->id, 'title'=>trans("alerts::template.common.publishedRecord"), 'data_value'=>'Unpublish', 'checked'=>'checked'])->render();
                    } else {
                        $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/alerts', 'data_alias'=>$value->id, 'title'=>trans("alerts::template.common.unpublishedRecord"), 'data_value'=>'Publish'])->render();
                    }
                } else {
                    $publish_action = "-";
                }
            }
        } else {
            if ($permit['canalertspublish']) {
                $publish_action .= '<a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-toggle="tooltip" title="This alert is in Approval request so can&#39;t be Publish/Unpublish."><i style="color:red" class="ri-toggle-line icon-publish fs-24"></i></a>';
            } else {
                $publish_action = "-";
            }
        }

        if ($publish_action == "") {
            $publish_action = "";
        } else {
            if ($value->chrLock != 'Y') {
                $publish_action = $publish_action;
            } else {
                if (($currentUserID == $value->LockUserID) || (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y')) {
                    $publish_action = $publish_action;
                } else {
                    $publish_action = "";
                }
            }
        }



        // Title Action
        $title_action = '';
        if ($permit['canalertsedit']) {
            $Quickedit_startDate = date('Y-m-d H:i', strtotime($value->dtDateTime));
            $Quickedit_endDate = !empty($value->dtEndDateTime) ? date('Y-m-d H:i', strtotime($value->dtEndDateTime)) : 'No Expiry';

            if ($value->chrLock != 'Y') {
                if (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y') {
                    if ($permit['canalertsreviewchanges']) {
                        $title_action .= "<a href=\"javascript:void(0);\" class=\"icon_title1 approval_active\" data-bs-toggle=\"tooltip\" data-bs-placement=\"bottom\" title=\"Click here to see all approval records.\" style=\"margin-right: 5px;\" onclick=\"javascript:expandcollapsepanel(this ,'tasklisting" . $value->id . "', 'mainsingnimg" . $value->id . "'," . $value->id . ')" id="mainsingnimg' . $value->id . '"><i class="ri-stack-line fs-16"></i></a>';

                        if (File::exists(base_path() . '/packages/Powerpanel/Workflow/src/Models/Workflow.php')) {
                            if ($this->currentUserRoleData->chrIsAdmin == 'Y' && count($value->child) > 1) {
                                $title_action .= "<a href='javascript:void(0);' data-bs-toggle='tooltip' data-bs-placement='bottom' style='margin-right: 5px;' title='Rollback to previous version' onclick='rollbackToPreviousVersion('" . $value->id . "');'  class='icon_title2 rollback_active'><i class='ri-history-line fs-16'></i></a>";
                            }
                        }                    }
                    if (Config::get('Constant.DEFAULT_QUICK') == 'Y') {
                        $title_action .= '<span class="show-hover"><a data-bs-toggle="tooltip" data-bs-placement="bottom" title="Quick Edit" href=\'javascript:void(0);\' data-toggle=\'modal\' data-target=\'#modalForm\' aria-label=\'Quick edit\' onclick=\'Quickeditfun("' . $value->id . '","' . $value->varTitle . '","' . $value->intSearchRank . '","' . $Quickedit_startDate . '","' . $Quickedit_endDate . '","P")\'><i class="ri-edit-2-line fs-16"></i></a></span>';
                    }
                }
            }
        }


        // Favorite Symbol
        if (Config::get('Constant.DEFAULT_FAVORITE') == 'Y') {
            $Favorite_array = explode(",", $value->FavoriteID);
            if (in_array($currentUserID, $Favorite_array)) {
                $Class = 'ri-bookmark-3-fill fs-20';
                $Favorite = '<a class="star_icon_div" href="javascript:void(0);" onclick="GetFavorite(' . $value->id . ',\'N\',\'P\')"><i class="' . $Class . '"></i></a>';
            } else {
                $Class = 'ri-bookmark-3-line fs-20';
                $Favorite = '<a class="star_icon_div" href="javascript:void(0);" onclick="GetFavorite(' . $value->id . ',\'Y\',\'P\')"><i class="' . $Class . '"></i></a>';
            }
        } else {
            $Favorite = '';
        }

        // Status-Data , Status , Sector
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
                $statusdata = '<span class="badge badge-soft-danger badge-border"> New</span>';
            }
        } else {
            if ($days_modified < Config::get('Constant.DEFAULT_DAYS')) {
                $statusdata = '<span class="badge badge-soft-secondary badge-border"> Update</span>';
            }
            if ($days < Config::get('Constant.DEFAULT_DAYS')) {
                $statusdata = '<span class="badge badge-soft-danger badge-border"> New</span>';
            }
        }

        $status = '';
        if ($value->chrDraft == 'D') {
            $status .= Config::get('Constant.DRAFT_LIST') . ' ';
        }
        if ($value->chrAddStar == 'Y') {
            $status .= Config::get('Constant.APPROVAL_LIST') . ' ';
        }
        if ($value->chrArchive == 'Y') {
            $status .= Config::get('Constant.ARCHIVE_LIST') . ' ';
        }

        $sector = '';
        if (isset($value->varSector) && !empty($value->varSector)) {
            $sector = strtoupper($value->varSector);
        }


        // Image
        $imgIcon = '';
        if (isset($value->fkIntImgId) && !empty($value->fkIntImgId)) {
            $imageArr = explode(',', $value->fkIntImgId);
            if (count($imageArr) > 1) {
                $imgIcon .= '<div class="multi_image_thumb">';
                foreach ($imageArr as $key => $image) {
                    $imgIcon .= '<a href="' . resize_image::resize($image) . '" class="fancybox-thumb" rel="fancybox-thumb-' . $value->id . '" data-rel="fancybox-thumb">';
                    $imgIcon .= '<img style="max-width:20px" title="' . preg_replace('/[^A-Za-z0-9\-]/', '-', $value->varTitle) . '" src="' . resize_image::resize($image, 50, 50) . '"/>';
                    $imgIcon .= '</a>';
                }
                $imgIcon .= '</div>';
            } else {
                $imgIcon .= '<div class="multi_image_thumb">';
                $imgIcon .= '<a href="' . resize_image::resize($value->fkIntImgId) . '" class="fancybox-buttons"  data-rel="fancybox-buttons">';
                $imgIcon .= '<img style="max-width:20px" data-bs-toggle="tooltip" data-bs-placement="bottom" title="' . preg_replace('/[^A-Za-z0-9\-]/', '-', $value->varTitle) . '" src="' . resize_image::resize($value->fkIntImgId, 50, 50) . '"/>';
                $imgIcon .= '</a>';
                $imgIcon .= '</div>';
            }
        } else {
            $imgIcon .= '-';
        }


        // Order Arrow
        $orderArrow = '';
        $dispOrder = $value->intDisplayOrder;
        if (($value->intDisplayOrder == $allRecordsCount || $value->intDisplayOrder < $allRecordsCount) && $value->intDisplayOrder > 1) {
            $orderArrow .= '<a href="javascript:;" data-order="' . $value->intDisplayOrder . '" class="moveUp"><i class="ri-arrow-up-line" aria-hidden="true"></i></a>';
        }
        $orderArrow .= $dispOrder;
        if (($value->intDisplayOrder != $allRecordsCount || $value->intDisplayOrder < $allRecordsCount)) {
            $orderArrow .= ' <a href="javascript:;" data-order="' . $value->intDisplayOrder . '" class="moveDwn"><i class="ri-arrow-down-line" aria-hidden="true"></i></a>';
        }

        // Alerts Type
        if ($value->intAlertType == 1) {
            $alertType = "High";
        } else if ($value->intAlertType == 2) {
            $alertType = "Medium";
        } else {
            $alertType = "Low";
        }


        // All - Actions
        $viewlink = "";
        $linkviewLable = "";
        if(isset($value->alias) && $value->alias != null){
            if ($permit['canalertsedit']) {
                if ($value->chrDraft == 'D' || $value->chrAddStar == 'Y') {
                    $viewlink = url('/previewpage?url=' . url($value->alias->varAlias) . '/' . $value->id . '/preview');
                    $linkviewLable = "Preview";
                } else {
                    if (isset($value->alias->varAlias)) {
                        $slug = '';
                        if (isset($value->varSector) && !empty($value->varSector) && $value->varSector != 'ofreg') {
                            if ($value->varSector != 'ofreg') {
                                $slug = strtolower($value->varSector);
                            } else {
                                $slug = '';
                            }
                        }
                        $viewlink = url($slug . '/' . $value->alias->varAlias);
                    } else {
                        $viewlink = "";
                    }
                    $linkviewLable = "View";
                }
            }
        }

        $logurl = url('powerpanel/log?id=' . $value->id . '&mid=' . Config::get('Constant.MODULE.ID'));
        $allActions = view('powerpanel.partials.all-actions',
                    [
                        'tabName'=>'All',
                        'canedit'=> $permit['canalertsedit'],
                        'candelete'=>$permit['canalertsdelete'],
                        'canloglist'=>$permit['canloglist'],
                        'value'=>$value,
                        'chrIsAdmin' => $this->currentUserRoleData->chrIsAdmin,
                        'module_name'=>'alerts',
                        'module_edit_url' => route('powerpanel.alerts.edit', array('alias' => $value->id)),
                        'module_type'=>'parent',
                        'viewlink' => isset($viewlink) ? $viewlink : "",
                        'linkviewLable' => isset($linkviewLable) ? $linkviewLable : "",
                        'logurl' => $logurl
                    ])->render();

        if($permit['canalertsedit'] || $permit['canalertsdelete']){
            $allActions = $allActions;
        } else {
            $allActions = "-";
        }


        $records = array(
            $checkbox,
            '<div class="pages_title_div_row">' . $Favorite . ' <span class="title-txt"> ' . $title . ' ' . $status . $statusdata . ' - ' . $sector . ' ' .$title_action. '</span></div>',
            $startDate,
            $endDate,
            $orderArrow,
            $alertType,
            $publish_action,
            $allActions
        );
        return $records;
    }

    public function tableDataWaitingToApprovedData($value , $permit, $currentUserID) {

        // Checkbox
        $checkbox = view('powerpanel.partials.checkbox', ['name'=>'delete', 'value'=>$value->id])->render();


        // Date
        // $startDate = date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($value->dtDateTime));
        $startDate = '<span align="left" data-bs-toggle="tooltip" data-bs-placement="bottom" title="'.date(Config::get("Constant.DEFAULT_DATE_FORMAT").' '.Config::get("Constant.DEFAULT_TIME_FORMAT"), strtotime($value->dtDateTime)).'">'.date(Config::get('Constant.DEFAULT_DATE_FORMAT'), strtotime($value->dtDateTime)).'</span>';
        // $endDate = !empty($value->dtEndDateTime) ? date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($value->dtEndDateTime)) : 'No Expiry';
        $endDate = !empty($value->dtEndDateTime) ? '<span align="left" data-bs-toggle="tooltip" data-bs-placement="bottom" title="'.date(Config::get("Constant.DEFAULT_DATE_FORMAT").' '.Config::get("Constant.DEFAULT_TIME_FORMAT"), strtotime($value->dtEndDateTime)).'">'.date(Config::get('Constant.DEFAULT_DATE_FORMAT'), strtotime($value->dtEndDateTime)).'</span>' : 'No Expiry';

        // Title
        $title = $value->varTitle;


        // Publish Action
        $publish_action = '';
        if ($value->chrAddStar != 'Y') {
            if ($value->chrDraft != 'D') {
                if ($permit['canalertspublish']) {
                    if ($value->chrPublish == 'Y') {
                        $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/alerts', 'data_alias'=>$value->id, 'title'=>trans("alerts::template.common.publishedRecord"), 'data_value'=>'Unpublish', 'checked'=>'checked'])->render();
                    } else {
                        $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/alerts', 'data_alias'=>$value->id, 'title'=>trans("alerts::template.common.unpublishedRecord"), 'data_value'=>'Publish'])->render();
                    }
                } else {
                    $publish_action = "-";
                }
            } else {
                if ($permit['canalertspublish']) {
                    if ($value->chrPublish == 'Y') {
                        $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/alerts', 'data_alias'=>$value->id, 'title'=>trans("alerts::template.common.publishedRecord"), 'data_value'=>'Unpublish', 'checked'=>'checked'])->render();
                    } else {
                        $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/alerts', 'data_alias'=>$value->id, 'title'=>trans("alerts::template.common.unpublishedRecord"), 'data_value'=>'Publish'])->render();
                    }
                } else {
                    $publish_action = "-";
                }
            }
        } else {
            if ($permit['canalertspublish']) {
                $publish_action .= '<a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-toggle="tooltip" title="This alert is in Approval request so can&#39;t be Publish/Unpublish."><i style="color:red" class="ri-toggle-line icon-publish fs-24"></i></a>';
            } else {
                $publish_action = "-";
            }
        }

        if ($publish_action == "") {
            $publish_action = "";
        } else {
            if ($value->chrLock != 'Y') {
                $publish_action = $publish_action;
            } else {
                if (($currentUserID == $value->LockUserID) || (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y')) {
                    $publish_action = $publish_action;
                } else {
                    $publish_action = "";
                }
            }
        }



        // Title Action
        $title_action = '';
        if ($permit['canalertsedit']) {
            $Quickedit_startDate = date('Y-m-d H:i', strtotime($value->dtDateTime));
            $Quickedit_endDate = !empty($value->dtEndDateTime) ? date('Y-m-d H:i', strtotime($value->dtEndDateTime)) : 'No Expiry';

            if ($value->chrLock != 'Y') {
                if (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y') {
                    if ($permit['canalertsreviewchanges']) {
                        $title_action .= "<a href=\"javascript:void(0);\" class=\"icon_title1 approval_active\" data-bs-toggle=\"tooltip\" data-bs-placement=\"bottom\" title=\"Click here to see all approval records.\" style=\"margin-right: 5px;\" onclick=\"javascript:expandcollapsepanel(this ,'tasklisting" . $value->id . "', 'mainsingnimg" . $value->id . "'," . $value->id . ')" id="mainsingnimg' . $value->id . '"><i class="ri-stack-line fs-16"></i></a>';

                        if (File::exists(base_path() . '/packages/Powerpanel/Workflow/src/Models/Workflow.php')) {
                            if ($this->currentUserRoleData->chrIsAdmin == 'Y' && count($value->child) > 1) {
                                $title_action .= "<a href='javascript:void(0);' data-bs-toggle='tooltip' data-bs-placement='bottom' style='margin-right: 5px;' title='Rollback to previous version' onclick='rollbackToPreviousVersion('" . $value->id . "');'  class='icon_title2 rollback_active'><i class='ri-history-line fs-16'></i></a>";
                            }
                        }                    }
                    if (Config::get('Constant.DEFAULT_QUICK') == 'Y') {
                        $title_action .= '<span class="show-hover"><a data-bs-toggle="tooltip" data-bs-placement="bottom" title="Quick Edit" href=\'javascript:void(0);\' data-toggle=\'modal\' data-target=\'#modalForm\' aria-label=\'Quick edit\' onclick=\'Quickeditfun("' . $value->id . '","' . $value->varTitle . '","' . $value->intSearchRank . '","' . $Quickedit_startDate . '","' . $Quickedit_endDate . '","P")\'><i class="ri-edit-2-line fs-16"></i></a></span>';
                    }
                }
            }
        }


        // Favorite Symbol
        if (Config::get('Constant.DEFAULT_FAVORITE') == 'Y') {
            $Favorite_array = explode(",", $value->FavoriteID);
            if (in_array($currentUserID, $Favorite_array)) {
                $Class = 'ri-bookmark-3-fill fs-20';
                $Favorite = '<a class="star_icon_div" href="javascript:void(0);" onclick="GetFavorite(' . $value->id . ',\'N\',\'P\')"><i class="' . $Class . '"></i></a>';
            } else {
                $Class = 'ri-bookmark-3-line fs-20';
                $Favorite = '<a class="star_icon_div" href="javascript:void(0);" onclick="GetFavorite(' . $value->id . ',\'Y\',\'P\')"><i class="' . $Class . '"></i></a>';
            }
        } else {
            $Favorite = '';
        }

        // Status-Data , Status , Sector
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
                $statusdata = '<span class="badge badge-soft-danger badge-border"> New</span>';
            }
        } else {
            if ($days_modified < Config::get('Constant.DEFAULT_DAYS')) {
                $statusdata = '<span class="badge badge-soft-secondary badge-border"> Update</span>';
            }
            if ($days < Config::get('Constant.DEFAULT_DAYS')) {
                $statusdata = '<span class="badge badge-soft-danger badge-border"> New</span>';
            }
        }

        $status = '';
        if ($value->chrDraft == 'D') {
            $status .= Config::get('Constant.DRAFT_LIST') . ' ';
        }
        if ($value->chrAddStar == 'Y') {
            $status .= Config::get('Constant.APPROVAL_LIST') . ' ';
        }
        if ($value->chrArchive == 'Y') {
            $status .= Config::get('Constant.ARCHIVE_LIST') . ' ';
        }

        $sector = '';
        if (isset($value->varSector) && !empty($value->varSector)) {
            $sector = strtoupper($value->varSector);
        }



        // Alerts Type
        if ($value->intAlertType == 1) {
            $alertType = "High";
        } else if ($value->intAlertType == 2) {
            $alertType = "Medium";
        } else {
            $alertType = "Low";
        }


        // All - Actions
        $viewlink = "";
        $linkviewLable = "";
        if(isset($value->alias) && $value->alias != null){
            if ($permit['canalertsedit']) {
                if ($value->chrDraft == 'D' || $value->chrAddStar == 'Y') {
                    $viewlink = url('/previewpage?url=' . url($value->alias->varAlias) . '/' . $value->id . '/preview');
                    $linkviewLable = "Preview";
                } else {
                    if (isset($value->alias->varAlias)) {
                        $slug = '';
                        if (isset($value->varSector) && !empty($value->varSector) && $value->varSector != 'ofreg') {
                            if ($value->varSector != 'ofreg') {
                                $slug = strtolower($value->varSector);
                            } else {
                                $slug = '';
                            }
                        }
                        $viewlink = url($slug . '/' . $value->alias->varAlias);
                    } else {
                        $viewlink = "";
                    }
                    $linkviewLable = "View";
                }
            }
        }

        $logurl = url('powerpanel/log?id=' . $value->id . '&mid=' . Config::get('Constant.MODULE.ID'));
        $allActions = view('powerpanel.partials.all-actions',
                    [
                        'tabName'=>'Approval',
                        'canedit'=> $permit['canalertsedit'],
                        'candelete'=>$permit['canalertsdelete'],
                        'canloglist'=>$permit['canloglist'],
                        'value'=>$value,
                        'chrIsAdmin' => $this->currentUserRoleData->chrIsAdmin,
                        'module_name'=>'alerts',
                        'module_edit_url' => route('powerpanel.alerts.edit', array('alias' => $value->id)),
                        'module_type'=>'parent',
                        'viewlink' => isset($viewlink) ? $viewlink : "",
                        'linkviewLable' => isset($linkviewLable) ? $linkviewLable : "",
                        'logurl' => $logurl
                    ])->render();

        if($permit['canalertsedit'] || $permit['canalertsdelete']){
            $allActions = $allActions;
        } else {
            $allActions = "-";
        }


        $records = array(
            $checkbox,
            '<div class="pages_title_div_row">' . $Favorite . ' <span class="title-txt"> ' . $title . ' ' . $status . $statusdata . ' - ' . $sector . ' ' .$title_action. '</span></div>',
            $startDate,
            $endDate,
            $alertType,
            $publish_action,
            $allActions
        );
        return $records;
    }

    public function tableDataFavorite($value , $permit, $currentUserID) {

        // Checkbox
        $checkbox = view('powerpanel.partials.checkbox', ['name'=>'delete', 'value'=>$value->id])->render();


        // Date
        // $startDate = date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($value->dtDateTime));
        $startDate = '<span align="left" data-bs-toggle="tooltip" data-bs-placement="bottom" title="'.date(Config::get("Constant.DEFAULT_DATE_FORMAT").' '.Config::get("Constant.DEFAULT_TIME_FORMAT"), strtotime($value->dtDateTime)).'">'.date(Config::get('Constant.DEFAULT_DATE_FORMAT'), strtotime($value->dtDateTime)).'</span>';
        // $endDate = !empty($value->dtEndDateTime) ? date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($value->dtEndDateTime)) : 'No Expiry';
        $endDate = !empty($value->dtEndDateTime) ? '<span align="left" data-bs-toggle="tooltip" data-bs-placement="bottom" title="'.date(Config::get("Constant.DEFAULT_DATE_FORMAT").' '.Config::get("Constant.DEFAULT_TIME_FORMAT"), strtotime($value->dtEndDateTime)).'">'.date(Config::get('Constant.DEFAULT_DATE_FORMAT'), strtotime($value->dtEndDateTime)).'</span>' : 'No Expiry';


        // Title
        $title = $value->varTitle;


        // Publish Action
        $publish_action = '';
        if ($value->chrAddStar != 'Y') {
            if ($value->chrDraft != 'D') {
                if ($permit['canalertspublish']) {
                    if ($value->chrPublish == 'Y') {
                        $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/alerts', 'data_alias'=>$value->id, 'title'=>trans("alerts::template.common.publishedRecord"), 'data_value'=>'Unpublish', 'checked'=>'checked'])->render();
                    } else {
                        $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/alerts', 'data_alias'=>$value->id, 'title'=>trans("alerts::template.common.unpublishedRecord"), 'data_value'=>'Publish'])->render();
                    }
                } else {
                    $publish_action = "-";
                }
            } else {
                if ($permit['canalertspublish']) {
                    if ($value->chrPublish == 'Y') {
                        $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/alerts', 'data_alias'=>$value->id, 'title'=>trans("alerts::template.common.publishedRecord"), 'data_value'=>'Unpublish', 'checked'=>'checked'])->render();
                    } else {
                        $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/alerts', 'data_alias'=>$value->id, 'title'=>trans("alerts::template.common.unpublishedRecord"), 'data_value'=>'Publish'])->render();
                    }
                } else {
                    $publish_action = "-";
                }
            }
        } else {
            if ($permit['canalertspublish']) {
                $publish_action .= '<a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-toggle="tooltip" title="This alert is in Approval request so can&#39;t be Publish/Unpublish."><i style="color:red" class="ri-toggle-line icon-publish fs-24"></i></a>';
            } else {
                $publish_action = "-";
            }
        }

        if ($publish_action == "") {
            $publish_action = "";
        } else {
            if ($value->chrLock != 'Y') {
                $publish_action = $publish_action;
            } else {
                if (($currentUserID == $value->LockUserID) || (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y')) {
                    $publish_action = $publish_action;
                } else {
                    $publish_action = "";
                }
            }
        }



        // Title Action
        $title_action = '';
        if ($permit['canalertsedit']) {
            $Quickedit_startDate = date('Y-m-d H:i', strtotime($value->dtDateTime));
            $Quickedit_endDate = !empty($value->dtEndDateTime) ? date('Y-m-d H:i', strtotime($value->dtEndDateTime)) : 'No Expiry';

            if ($value->chrLock != 'Y') {
                if (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y') {
                    if ($permit['canalertsreviewchanges']) {
                        $title_action .= "<a href=\"javascript:void(0);\" class=\"icon_title1 approval_active\" data-bs-toggle=\"tooltip\" data-bs-placement=\"bottom\" title=\"Click here to see all approval records.\" style=\"margin-right: 5px;\" onclick=\"javascript:expandcollapsepanel(this ,'tasklisting" . $value->id . "', 'mainsingnimg" . $value->id . "'," . $value->id . ')" id="mainsingnimg' . $value->id . '"><i class="ri-stack-line fs-16"></i></a>';

                        if (File::exists(base_path() . '/packages/Powerpanel/Workflow/src/Models/Workflow.php')) {
                            if ($this->currentUserRoleData->chrIsAdmin == 'Y' && count($value->child) > 1) {
                                $title_action .= "<a href='javascript:void(0);' data-bs-toggle='tooltip' data-bs-placement='bottom' style='margin-right: 5px;' title='Rollback to previous version' onclick='rollbackToPreviousVersion('" . $value->id . "');'  class='icon_title2 rollback_active'><i class='ri-history-line fs-16'></i></a>";
                            }
                        }                    }
                    if (Config::get('Constant.DEFAULT_QUICK') == 'Y') {
                        $title_action .= '<span class="show-hover"><a data-bs-toggle="tooltip" data-bs-placement="bottom" title="Quick Edit" href=\'javascript:void(0);\' data-toggle=\'modal\' data-target=\'#modalForm\' aria-label=\'Quick edit\' onclick=\'Quickeditfun("' . $value->id . '","' . $value->varTitle . '","' . $value->intSearchRank . '","' . $Quickedit_startDate . '","' . $Quickedit_endDate . '","P")\'><i class="ri-edit-2-line fs-16"></i></a></span>';
                    }
                }
            }
        }


        // Favorite Symbol
        if (Config::get('Constant.DEFAULT_FAVORITE') == 'Y') {
            $Favorite_array = explode(",", $value->FavoriteID);
            if (in_array($currentUserID, $Favorite_array)) {
                $Class = 'ri-bookmark-3-fill fs-20';
                $Favorite = '<a class="star_icon_div" href="javascript:void(0);" onclick="GetFavorite(' . $value->id . ',\'N\',\'P\')"><i class="' . $Class . '"></i></a>';
            } else {
                $Class = 'ri-bookmark-3-line fs-20';
                $Favorite = '<a class="star_icon_div" href="javascript:void(0);" onclick="GetFavorite(' . $value->id . ',\'Y\',\'P\')"><i class="' . $Class . '"></i></a>';
            }
        } else {
            $Favorite = '';
        }

        // Status-Data , Status , Sector
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
                $statusdata = '<span class="badge badge-soft-danger badge-border"> New</span>';
            }
        } else {
            if ($days_modified < Config::get('Constant.DEFAULT_DAYS')) {
                $statusdata = '<span class="badge badge-soft-secondary badge-border"> Update</span>';
            }
            if ($days < Config::get('Constant.DEFAULT_DAYS')) {
                $statusdata = '<span class="badge badge-soft-danger badge-border"> New</span>';
            }
        }

        $status = '';
        if ($value->chrDraft == 'D') {
            $status .= Config::get('Constant.DRAFT_LIST') . ' ';
        }
        if ($value->chrAddStar == 'Y') {
            $status .= Config::get('Constant.APPROVAL_LIST') . ' ';
        }
        if ($value->chrArchive == 'Y') {
            $status .= Config::get('Constant.ARCHIVE_LIST') . ' ';
        }

        $sector = '';
        if (isset($value->varSector) && !empty($value->varSector)) {
            $sector = strtoupper($value->varSector);
        }



        // Alerts Type
        if ($value->intAlertType == 1) {
            $alertType = "High";
        } else if ($value->intAlertType == 2) {
            $alertType = "Medium";
        } else {
            $alertType = "Low";
        }


        // All - Actions
        $viewlink = "";
        $linkviewLable = "";
        if(isset($value->alias) && $value->alias != null){
            if ($permit['canalertsedit']) {
                if ($value->chrDraft == 'D' || $value->chrAddStar == 'Y') {
                    $viewlink = url('/previewpage?url=' . url($value->alias->varAlias) . '/' . $value->id . '/preview');
                    $linkviewLable = "Preview";
                } else {
                    if (isset($value->alias->varAlias)) {
                        $slug = '';
                        if (isset($value->varSector) && !empty($value->varSector) && $value->varSector != 'ofreg') {
                            if ($value->varSector != 'ofreg') {
                                $slug = strtolower($value->varSector);
                            } else {
                                $slug = '';
                            }
                        }
                        $viewlink = url($slug . '/' . $value->alias->varAlias);
                    } else {
                        $viewlink = "";
                    }
                    $linkviewLable = "View";
                }
            }
        }

        $logurl = url('powerpanel/log?id=' . $value->id . '&mid=' . Config::get('Constant.MODULE.ID'));
        $allActions = view('powerpanel.partials.all-actions',
                    [
                        'tabName'=>'Favorite',
                        'canedit'=> $permit['canalertsedit'],
                        'candelete'=>$permit['canalertsdelete'],
                        'canloglist'=>$permit['canloglist'],
                        'value'=>$value,
                        'chrIsAdmin' => $this->currentUserRoleData->chrIsAdmin,
                        'module_name'=>'alerts',
                        'module_edit_url' => route('powerpanel.alerts.edit', array('alias' => $value->id)),
                        'module_type'=>'parent',
                        'viewlink' => isset($viewlink) ? $viewlink : "",
                        'linkviewLable' => isset($linkviewLable) ? $linkviewLable : "",
                        'logurl' => $logurl
                    ])->render();

        if($permit['canalertsedit'] || $permit['canalertsdelete']){
            $allActions = $allActions;
        } else {
            $allActions = "-";
        }


        $records = array(
            $checkbox,
            '<div class="pages_title_div_row">' . $Favorite . ' <span class="title-txt"> ' . $title . ' ' . $status . $statusdata . ' - ' . $sector . ' ' .$title_action. '</span></div>',
            $startDate,
            $endDate,
            "-",
            $alertType,
            $publish_action,
            $allActions
        );
        return $records;
    }

    public function tableDataDraft($value , $permit, $currentUserID) {

        // Checkbox
        $checkbox = view('powerpanel.partials.checkbox', ['name'=>'delete', 'value'=>$value->id])->render();


        // Date
        // $startDate = date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($value->dtDateTime));
        $startDate = '<span align="left" data-bs-toggle="tooltip" data-bs-placement="bottom" title="'.date(Config::get("Constant.DEFAULT_DATE_FORMAT").' '.Config::get("Constant.DEFAULT_TIME_FORMAT"), strtotime($value->dtDateTime)).'">'.date(Config::get('Constant.DEFAULT_DATE_FORMAT'), strtotime($value->dtDateTime)).'</span>';
        // $endDate = !empty($value->dtEndDateTime) ? date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($value->dtEndDateTime)) : 'No Expiry';
        $endDate = !empty($value->dtEndDateTime) ? '<span align="left" data-bs-toggle="tooltip" data-bs-placement="bottom" title="'.date(Config::get("Constant.DEFAULT_DATE_FORMAT").' '.Config::get("Constant.DEFAULT_TIME_FORMAT"), strtotime($value->dtEndDateTime)).'">'.date(Config::get('Constant.DEFAULT_DATE_FORMAT'), strtotime($value->dtEndDateTime)).'</span>' : 'No Expiry';

        // Title
        $title = $value->varTitle;


        // Publish Action
        $publish_action = '';
        if ($value->chrAddStar != 'Y') {
            if ($value->chrDraft != 'D') {
                if ($permit['canalertspublish']) {
                    if ($value->chrPublish == 'Y') {
                        $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/alerts', 'data_alias'=>$value->id, 'title'=>trans("alerts::template.common.publishedRecord"), 'data_value'=>'Unpublish', 'checked'=>'checked'])->render();
                    } else {
                        $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/alerts', 'data_alias'=>$value->id, 'title'=>trans("alerts::template.common.unpublishedRecord"), 'data_value'=>'Publish'])->render();
                    }
                }
            } else {
                if ($value->chrPublish == 'Y') {
                    $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/alerts', 'data_alias'=>$value->id, 'title'=>trans("alerts::template.common.publishedRecord"), 'data_value'=>'Unpublish', 'checked'=>'checked'])->render();
                } else {
                    $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/alerts', 'data_alias'=>$value->id, 'title'=>trans("alerts::template.common.unpublishedRecord"), 'data_value'=>'Publish'])->render();
                }
            }
        } else {
            $publish_action .= '<a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-toggle="tooltip" title="This alert is in Approval request so can&#39;t be Publish/Unpublish."><i style="color:red" class="ri-toggle-line icon-publish fs-24"></i></a>';
        }

        if ($publish_action == "") {
            $publish_action = "";
        } else {
            if ($value->chrLock != 'Y') {
                $publish_action = $publish_action;
            } else {
                if (($currentUserID == $value->LockUserID) || (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y')) {
                    $publish_action = $publish_action;
                } else {
                    $publish_action = "";
                }
            }
        }



        // Title Action
        $title_action = '';
        if ($permit['canalertsedit']) {
            $Quickedit_startDate = date('Y-m-d H:i', strtotime($value->dtDateTime));
            $Quickedit_endDate = !empty($value->dtEndDateTime) ? date('Y-m-d H:i', strtotime($value->dtEndDateTime)) : 'No Expiry';

            if ($value->chrLock != 'Y') {
                if (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y') {
                    if (Config::get('Constant.DEFAULT_QUICK') == 'Y') {
                        $title_action .= '<span class="show-hover"><a data-bs-toggle="tooltip" data-bs-placement="bottom" title="Quick Edit" href=\'javascript:void(0);\' data-toggle=\'modal\' data-target=\'#modalForm\' aria-label=\'Quick edit\' onclick=\'Quickeditfun("' . $value->id . '","' . $value->varTitle . '","' . $value->intSearchRank . '","' . $Quickedit_startDate . '","' . $Quickedit_endDate . '","P")\'><i class="ri-edit-2-line fs-16"></i></a></span>';
                    }
                }
            }
        }


        // Favorite Symbol
        if (Config::get('Constant.DEFAULT_FAVORITE') == 'Y') {
            $Favorite_array = explode(",", $value->FavoriteID);
            if (in_array($currentUserID, $Favorite_array)) {
                $Class = 'ri-bookmark-3-fill fs-20';
                $Favorite = '<a class="star_icon_div" href="javascript:void(0);" onclick="GetFavorite(' . $value->id . ',\'N\',\'P\')"><i class="' . $Class . '"></i></a>';
            } else {
                $Class = 'ri-bookmark-3-line fs-20';
                $Favorite = '<a class="star_icon_div" href="javascript:void(0);" onclick="GetFavorite(' . $value->id . ',\'Y\',\'P\')"><i class="' . $Class . '"></i></a>';
            }
        } else {
            $Favorite = '';
        }

        // Status-Data , Status , Sector
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
                $statusdata = '<span class="badge badge-soft-danger badge-border"> New</span>';
            }
        } else {
            if ($days_modified < Config::get('Constant.DEFAULT_DAYS')) {
                $statusdata = '<span class="badge badge-soft-secondary badge-border"> Update</span>';
            }
            if ($days < Config::get('Constant.DEFAULT_DAYS')) {
                $statusdata = '<span class="badge badge-soft-danger badge-border"> New</span>';
            }
        }

        $status = '';
        if ($value->chrDraft == 'D') {
            $status .= Config::get('Constant.DRAFT_LIST') . ' ';
        }
        if ($value->chrAddStar == 'Y') {
            $status .= Config::get('Constant.APPROVAL_LIST') . ' ';
        }
        if ($value->chrArchive == 'Y') {
            $status .= Config::get('Constant.ARCHIVE_LIST') . ' ';
        }

        $sector = '';
        if (isset($value->varSector) && !empty($value->varSector)) {
            $sector = strtoupper($value->varSector);
        }



        // Alerts Type
        if ($value->intAlertType == 1) {
            $alertType = "High";
        } else if ($value->intAlertType == 2) {
            $alertType = "Medium";
        } else {
            $alertType = "Low";
        }


        // All - Actions
        $viewlink = "";
        $linkviewLable = "";
        if(isset($value->alias) && $value->alias != null){
            if ($permit['canalertsedit']) {
                if ($value->chrDraft == 'D' || $value->chrAddStar == 'Y') {
                    $viewlink = url('/previewpage?url=' . url($value->alias->varAlias) . '/' . $value->id . '/preview');
                    $linkviewLable = "Preview";
                } else {
                    if (isset($value->alias->varAlias)) {
                        $slug = '';
                        if (isset($value->varSector) && !empty($value->varSector) && $value->varSector != 'ofreg') {
                            if ($value->varSector != 'ofreg') {
                                $slug = strtolower($value->varSector);
                            } else {
                                $slug = '';
                            }
                        }
                        $viewlink = url($slug . '/' . $value->alias->varAlias);
                    } else {
                        $viewlink = "";
                    }
                    $linkviewLable = "View";
                }
            }
        }

        $logurl = url('powerpanel/log?id=' . $value->id . '&mid=' . Config::get('Constant.MODULE.ID'));
        $allActions = view('powerpanel.partials.all-actions',
                    [
                        'tabName'=>'Draft',
                        'canedit'=> $permit['canalertsedit'],
                        'candelete'=>$permit['canalertsdelete'],
                        'canloglist'=>$permit['canloglist'],
                        'value'=>$value,
                        'chrIsAdmin' => $this->currentUserRoleData->chrIsAdmin,
                        'module_name'=>'alerts',
                        'module_edit_url' => route('powerpanel.alerts.edit', array('alias' => $value->id)),
                        'module_type'=>'parent',
                        'viewlink' => isset($viewlink) ? $viewlink : "",
                        'linkviewLable' => isset($linkviewLable) ? $linkviewLable : "",
                        'logurl' => $logurl
                    ])->render();

        if($permit['canalertsedit'] || $permit['canalertsdelete']){
            $allActions = $allActions;
        } else {
            $allActions = "-";
        }


        $records = array(
            $checkbox,
            '<div class="pages_title_div_row">' . $Favorite . ' <span class="title-txt"> ' . $title . ' ' . $status . $statusdata . ' - ' . $sector . ' ' .$title_action. '</span></div>',
            $startDate,
            $endDate,
            $alertType,
            $publish_action,
            $allActions
        );
        return $records;
    }

    public function tableDataTrash($value , $permit, $currentUserID) {

        // Checkbox
        $checkbox = view('powerpanel.partials.checkbox', ['name'=>'delete', 'value'=>$value->id])->render();


        // Date
        // $startDate = date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($value->dtDateTime));
        $startDate = '<span align="left" data-bs-toggle="tooltip" data-bs-placement="bottom" title="'.date(Config::get("Constant.DEFAULT_DATE_FORMAT").' '.Config::get("Constant.DEFAULT_TIME_FORMAT"), strtotime($value->dtDateTime)).'">'.date(Config::get('Constant.DEFAULT_DATE_FORMAT'), strtotime($value->dtDateTime)).'</span>';
        // $endDate = !empty($value->dtEndDateTime) ? date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($value->dtEndDateTime)) : 'No Expiry';
        $endDate = !empty($value->dtEndDateTime) ? '<span align="left" data-bs-toggle="tooltip" data-bs-placement="bottom" title="'.date(Config::get("Constant.DEFAULT_DATE_FORMAT").' '.Config::get("Constant.DEFAULT_TIME_FORMAT"), strtotime($value->dtEndDateTime)).'">'.date(Config::get('Constant.DEFAULT_DATE_FORMAT'), strtotime($value->dtEndDateTime)).'</span>' : 'No Expiry';

        // Title
        $title = $value->varTitle;



        // Title Action
        $title_action = '';
        if ($permit['canalertsedit']) {
            $Quickedit_startDate = date('Y-m-d H:i', strtotime($value->dtDateTime));
            $Quickedit_endDate = !empty($value->dtEndDateTime) ? date('Y-m-d H:i', strtotime($value->dtEndDateTime)) : 'No Expiry';

            if ($value->chrLock != 'Y') {
                if (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y') {
                    if (Config::get('Constant.DEFAULT_QUICK') == 'Y') {
                        $title_action .= '<span class="show-hover"><a data-bs-toggle="tooltip" data-bs-placement="bottom" title="Quick Edit" href=\'javascript:void(0);\' data-toggle=\'modal\' data-target=\'#modalForm\' aria-label=\'Quick edit\' onclick=\'Quickeditfun("' . $value->id . '","' . $value->varTitle . '","' . $value->intSearchRank . '","' . $Quickedit_startDate . '","' . $Quickedit_endDate . '","P")\'><i class="ri-edit-2-line fs-16"></i></a></span>';
                    }
                }
            }
        }



        // Status-Data , Status , Sector
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
                $statusdata = '<span class="badge badge-soft-danger badge-border"> New</span>';
            }
        } else {
            if ($days_modified < Config::get('Constant.DEFAULT_DAYS')) {
                $statusdata = '<span class="badge badge-soft-secondary badge-border"> Update</span>';
            }
            if ($days < Config::get('Constant.DEFAULT_DAYS')) {
                $statusdata = '<span class="badge badge-soft-danger badge-border"> New</span>';
            }
        }

        $status = '';
        if ($value->chrDraft == 'D') {
            $status .= Config::get('Constant.DRAFT_LIST') . ' ';
        }
        if ($value->chrAddStar == 'Y') {
            $status .= Config::get('Constant.APPROVAL_LIST') . ' ';
        }
        if ($value->chrArchive == 'Y') {
            $status .= Config::get('Constant.ARCHIVE_LIST') . ' ';
        }

        $sector = '';
        if (isset($value->varSector) && !empty($value->varSector)) {
            $sector = strtoupper($value->varSector);
        }



        // Alerts Type
        if ($value->intAlertType == 1) {
            $alertType = "High";
        } else if ($value->intAlertType == 2) {
            $alertType = "Medium";
        } else {
            $alertType = "Low";
        }


        // All - Actions

        $logurl = url('powerpanel/log?id=' . $value->id . '&mid=' . Config::get('Constant.MODULE.ID'));
        $allActions = view('powerpanel.partials.all-actions',
                    [
                        'tabName'=>'Trash',
                        'canedit'=> $permit['canalertsedit'],
                        'candelete'=>$permit['canalertsdelete'],
                        'canloglist'=>$permit['canloglist'],
                        'value'=>$value,
                        'chrIsAdmin' => $this->currentUserRoleData->chrIsAdmin,
                        'module_name'=>'alerts',
                        'module_edit_url' => route('powerpanel.alerts.edit', array('alias' => $value->id)),
                        'module_type'=>'parent',
                        'viewlink' => isset($viewlink) ? $viewlink : "",
                        'linkviewLable' => isset($linkviewLable) ? $linkviewLable : "",
                        'logurl' => $logurl
                    ])->render();

        if($permit['canalertsedit'] || $permit['canalertsdelete']){
            $allActions = $allActions;
        } else {
            $allActions = "-";
        }


        $records = array(
            $checkbox,
            '<div class="pages_title_div_row"> <span class="title-txt"> ' . $title . ' ' . $status . $statusdata . ' - ' . $sector . ' ' .$title_action. '</span></div>',
            $startDate,
            $endDate,
            $alertType,
            $allActions
        );
        return $records;
    }




    /**
     * This method handels logs old records
     * @param  	$data
     * @return  order
     * @since   2017-07-21
     * @author  NetQuick
     */
    public function recordHistory($data = false) {
        if (isset($data->fkIntDocId)) {
            $DocId = Document::getRecordById($data->fkIntDocId);
            $docname = stripslashes($DocId->txtDocumentName);
        } else {
            $DocId = '';
            $docname = '';
        }

        if ($data->intAlertType == 1) {
            $alertType = "High";
        } else if ($data->intAlertType == 2) {
            $alertType = "Medium";
        } else {
            $alertType = "Low";
        }
        $startDate = date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($data->dtDateTime));
        $endDate = !empty($data->dtEndDateTime) ? date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($data->dtEndDateTime)) : 'No Expiry';

        $returnHtml = '';
        $returnHtml .= '<table class="new_table_desing table table-striped table-bordered table-hover">
						<thead>
							<tr>
								<th>' . trans("alerts::template.common.title") . '</th>
								<th>Module Name</th>
								<th>Alert Type</th>
                                <th>Start Date</th>
                                <th align="center">Documents</th>
                                <th align="center">Short Description</th>
								<th>End Date</th>
								<th>' . trans("alerts::template.common.publish") . '</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td align="center">' . stripslashes($data->varTitle) . '</td>
                                <td align="center">' . $data->modules->varModuleName . '</td>
                                <td align="center">' . $docname . '</td>
					            <td align="center">' . stripslashes($data->varShortDescription) . '</td>
                                                                <td align="center">' . $alertType . '</td>';
        $returnHtml .= '<td align="center">' . $startDate . '</td>';
        $returnHtml .= '<td align="center">' . $endDate . '</td>';
        $returnHtml .= '<td align="center">' . $data->chrPublish . '</td>
							</tr>
						</tbody>
					</table>';
        return $returnHtml;
    }

    public function newrecordHistory($data = false, $newdata = false) {
//        echo "<pre>"; print_r($data);exit;
        if ($data->varTitle != $newdata->varTitle) {
            $titlecolor = 'style="background-color:#f5efb7"';
        } else {
            $titlecolor = '';
        }
        if ($data->modules->varModuleName != $newdata->modules->varModuleName) {
            $modulecolor = 'style="background-color:#f5efb7"';
        } else {
            $modulecolor = '';
        }
        if ($data->fkIntDocId != $newdata->fkIntDocId) {
            $DocIdcolor = 'style="background-color:#f5efb7"';
        } else {
            $DocIdcolor = '';
        }
        if ($data->varShortDescription != $newdata->varShortDescription) {
            $ShortDescriptioncolor = 'style="background-color:#f5efb7"';
        } else {
            $ShortDescriptioncolor = '';
        }
        if ($data->fkIntPageId != $newdata->fkIntPageId) {
            $fkIntPageIdcolor = 'style="background-color:#f5efb7"';
        } else {
            $fkIntPageIdcolor = '';
        }
        if ($data->intAlertType != $newdata->intAlertType) {
            $intAlertTypecolor = 'style="background-color:#f5efb7"';
        } else {
            $intAlertTypecolor = '';
        }
        if ($data->chrPublish != $newdata->chrPublish) {
            $Publishcolor = 'style="background-color:#f5efb7"';
        } else {
            $Publishcolor = '';
        }
        if ($data->dtDateTime != $newdata->dtDateTime) {
            $DateTimecolor = 'style="background-color:#f5efb7"';
        } else {
            $DateTimecolor = '';
        }
        if ($data->dtEndDateTime != $newdata->dtEndDateTime) {
            $EndDateTimecolor = 'style="background-color:#f5efb7"';
        } else {
            $EndDateTimecolor = '';
        }
//        =====

        if ($newdata->intAlertType == 1) {
            $alertType = "High";
        } else if ($newdata->intAlertType == 2) {
            $alertType = "Medium";
        } else {
            $alertType = "Low";
        }
        if (isset($newdata->fkIntDocId)) {
            $DocId = Document::getRecordById($newdata->fkIntDocId);
            $docname = stripslashes($DocId->txtDocumentName);
        } else {
            $DocId = '';
            $docname = '';
        }
        $startDate = date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($newdata->dtDateTime));
        $endDate = !empty($newdata->dtEndDateTime) ? date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($newdata->dtEndDateTime)) : 'No Expiry';
        $returnHtml = '';
        $returnHtml .= '<table class = "new_table_desing table table-striped table-bordered table-hover">
				<thead>
				<tr>
				<th align="center">' . trans('alerts::template.common.title') . '</th>
				<th align="center">' . trans('alerts::template.common.modulename') . '</th>
                <th align="center">Documents</th>
                <th align="center">Short Description</th>
				<th align="center">Alert Type</th>
                                <th align="center">Start date</th>
                                <th align="center">End date</th>
				<th align="center">' . trans('alerts::template.common.publish') . '</th>
				</tr>
				</thead>
				<tbody>
				<tr>
				<td align="center" ' . $titlecolor . '>' . stripslashes($newdata->varTitle) . '</td>
                <td align="center" ' . $modulecolor . '>' . $newdata->modules->varModuleName . '</td>
                <td align="center" ' . $DocIdcolor . '>' . $docname . '</td>
                <td align="center" ' . $ShortDescriptioncolor . '>' . stripslashes($newdata->varShortDescription) . '</td>
				<td align="center" ' . $intAlertTypecolor . '>' . $alertType . '</td>
                                <td align="center" ' . $DateTimecolor . '>' . $startDate . '</td>
                                <td align="center" ' . $EndDateTimecolor . '>' . $endDate . '</td>
				<td align="center" ' . $Publishcolor . '>' . $newdata->chrPublish . '</td>
				</tr>
				</tbody>
				</table>';
        return $returnHtml;
    }

    /**
     * This method handle to Approved Data record.
     * @since   26-Sep-2018
     * @author  NetQuick Team
     */
    public function ApprovedData_Listing(Request $request) {
        $requestArr = Request::all();
        $request = (object) $requestArr;
        $id = Request::post('id');
        $main_id = Request::post('main_id');
        $approvalid = Request::post('id');
        $approvalData = Alerts::getOrderOfApproval($id);
        $flag = Request::post('flag');
        $message = Alerts::approved_data_Listing($request);
        if (!empty($approvalData)) {
            self::swap_order_edit($approvalData->intDisplayOrder, $main_id);
        }

        $newCmsPageObj = Alerts::getRecordForLogById($main_id);
        $approval_obj = Alerts::getRecordForLogById($approvalid);
        if ($flag == 'R') {
            $restoredata = Config::get('Constant.ROLLBACK_RECORD');
        } else {
            if ($approval_obj->chrDraft == 'D') {
                $restoredata = Config::get('Constant.DRAFT_RECORD_APPROVED');
            } else {
                $restoredata = Config::get('Constant.RECORD_APPROVED');
            }
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

    public function getChildData_rollback(Request $request) {
        $requestArr = Request::all();
        $request = (object) $requestArr;
        $child_rollbackHtml = "";
        $Notices_rollbackchildData = "";
        $Notices_rollbackchildData = Alerts::getChildrollbackGrid($request);
        $child_rollbackHtml .= "<div class=\"producttbl producttb2\" style=\"\">";
        $child_rollbackHtml .= "<table class=\"new_table_desing table table-striped table-bordered table-hover table-checkable dataTable\" id=\"email_log_datatable_ajax\">
																<tr role=\"row\">       
                                                                                                                                <th class=\"text-center\">Title</th>
																		<th class=\"text-center\">Date</th>
																		<th class=\"text-center\">User</th>                                     
																		<th class=\"text-center\">Status</th>";
        $child_rollbackHtml .= "         </tr>";
        if (count($Notices_rollbackchildData) > 0) {
            foreach ($Notices_rollbackchildData as $child_rollbacrow) {
                $child_rollbackHtml .= "<tr role=\"row\">";
                $child_rollbackHtml .= '<td class="text-center"><span class="mob_show_title">Title: </span>' . $child_rollbacrow->varTitle . '</td>';
                $child_rollbackHtml .= "<td class=\"text-center\"><span class='mob_show_title'>Date: </span>" . date('M d Y h:i A', strtotime($child_rollbacrow->created_at)) . "</td>";
                $child_rollbackHtml .= "<td class=\"text-center\"><span class='mob_show_title'>User: </span>" . CommonModel::getUserName($child_rollbacrow->UserID) . "</td>";
                if ($child_rollbacrow->chrApproved == 'Y') {
                    $child_rollbackHtml .= '<td class="text-center"><span class="mob_show_title">Status: </span><i class="ri-checkbox-circle-line" style="color: #1080F2;font-size:30px;"></i></td>';
                } else {
                    // $child_rollbackHtml .= "<td class=\"text-center\"><span class='mob_show_title'>Status: </span><a onclick=\"update_mainrecord('" . $child_rollbacrow->id . "','" . $child_rollbacrow->fkMainRecord . "','" . $child_rollbacrow->UserID . "','R');\"  class=\"approve_icon_btn\">
					// 						<i class=\"ri-history-line\"></i>  <span>RollBack</span>
                    //                     </a></td>";
                                        
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
     * This method handle to get child record.
     * @since   26-Sep-2018
     * @author  NetQuick Team
     */
    public function getChildData() {
        $data = Request::input();
        $requestedID = $data['id'];
        $childHtml = "";
        $childData = "";
        $childData = Alerts::getChildGrid($requestedID);


        $childHtml .= "<div class=\"producttbl\" style=\"\">";
        $childHtml .= "<table class=\"table table-hover align-middle table-nowrap hide-mobile\" id=\"email_log_datatable_ajax\">
						<tr role=\"row\">
                            <th class=\"text-left\"></th>
                            <th class=\"text-left\">Title</th>
                            <th class=\"text-center\">Date Submitted</th>
                            <th class=\"text-center\">User</th>
                            <th class=\"text-center\">Edit</th>
                            <th class=\"text-center\">Status</th>";
        $childHtml .=   "</tr>";


        if (count($childData) > 0) {
            foreach ($childData as $child_row) {
                $checkbox = view('powerpanel.partials.checkbox', ['name'=>'delete', 'value'=>$child_row->id])->render();

                $childHtml .= "<tr role=\"row\">";
                    if ($child_row->chrApproved == 'N') {
                        $childHtml .= "<td>".$checkbox."</td>";
                    } else {
                        $childHtml .= "<td><span class='mob_show_title'>&nbsp</span><div class=\"checker\"><a href=\"javascript:;\" data-toggle=\"tooltip\" data-placement=\"right\" title=\"This is approved record, so can't be deleted.\"><i style=\"color:red\" class=\"ri-alert-fill\"></i></a></div></td>";
                    }

                    $childHtml .= '<td class="text-left"><span class="mob_show_title">Title: </span>' . $child_row->varTitle . '</td>';

                    $childHtml .= "<td class=\"text-center\"><span class='mob_show_title'>Date Submitted: </span> <span align='left' data-bs-toggle='tooltip' data-bs-placement='bottom' title='".date(Config::get('Constant.DEFAULT_DATE_FORMAT').' '.Config::get('Constant.DEFAULT_TIME_FORMAT'), strtotime($child_row->created_at))."'>" . date(Config::get('Constant.DEFAULT_DATE_FORMAT'), strtotime($child_row->created_at)) . "</span> </td>";

                    $childHtml .= "<td class=\"text-center\"><span class='mob_show_title'>User: </span>" . CommonModel::getUserName($child_row->UserID) . "</td>";

                    if ($child_row->chrApproved == 'N') {
                        $childHtml .= "<td class=\"text-center\"><span class='mob_show_title'>Edit: </span><a class='icon_round me-2'
                        data-bs-toggle='tooltip' data-bs-placement='bottom' title='" . trans('alerts::template.common.edit') . "'
                        href='" . route('powerpanel.alertss.edit', array('alias' => $child_row->id)) . "'>
                                                            <i class='ri-pencil-line'></i></a></td>";
                    } else {
                        $childHtml .= "<td class=\"text-center\"><span class='mob_show_title'>Edit: </span>-</td>";
                    }

                    if ($child_row->chrApproved == 'N') {
                        $childHtml .= "<td class=\"text-center\"><span class='mob_show_title'>Status: </span> <a class=\"approve_icon_btn\" data-bs-toggle='tooltip' data-bs-placement='bottom' title='" . trans("alerts::template.common.comments") . "' href=\"javascript:;\" onclick=\"loadModelpopup('" . $child_row->id . "','" . $child_row->UserID . "','" . Config::get('Constant.MODULE.MODEL_NAME') . "','" . $child_row->fkMainRecord . "')\"><i class=\"ri-chat-1-line\"></i> </a> &nbsp;&nbsp;<a class=\"approve_icon_btn me-2\" onclick=\"update_mainrecord('" . $child_row->id . "','" . $child_row->fkMainRecord . "','" . $child_row->UserID . "','A');\" data-bs-toggle='tooltip' data-bs-placement='bottom' title='" . trans('alerts::template.common.clickapprove') . "'  href=\"javascript:void(0);\"><i class=\"ri-checkbox-line\"></i></a></td>";
                    } else {
                        $childHtml .= "<td class=\"text-center\"><span class='mob_show_title'>Status: </span> <span class='mob_show_overflow'><i class=\"ri-checkbox-circle-line\" style=\"font-size:30px;\"></i><span style=\"display:block\"><strong>Approved On: </strong>" . date('M d Y h:i A', strtotime($child_row->dtApprovedDateTime)) . "</span><span style=\"display:block\"><strong>Approved By: </strong>" . CommonModel::getUserName($child_row->intApprovedBy) . "</span></span></td>";
                    }
                $childHtml .= "</tr>";
            }
        } else {
            $childHtml .= "<tr><td class='text-center' colspan='6'>No Records</td></tr>";
        }
        $childHtml .= "</tr></td></tr>";
        $childHtml .= "</tr></table>";

        echo $childHtml;
        exit;
    }

    public function insertComents(Request $request) {
        $Comments_data['intRecordID'] = Request::post('id');
        $Comments_data['varModuleNameSpace'] = Request::post('namespace');
        $Comments_data['varCmsPageComments'] = stripslashes(Request::post('CmsPageComments'));
        $Comments_data['UserID'] = Request::post('UserID');
        $Comments_data['intCommentBy'] = auth()->user()->id;
        $Comments_data['varModuleTitle'] = Config::get('Constant.MODULE.TITLE');
        $Comments_data['fkMainRecord'] = Request::post('fkMainRecord');
        Comments::insertComents($Comments_data);
        exit;
    }

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

    public static function getInternalLinkHtml($value) {
        $otherDetail = '';
        if ($value->varLinkType == 'internal') {
            $moduleCode = $value->modules->id;
            if ($value->modules->varTitle != 'Pages') {
                $tableName = DB::table('module')->select('varTableName', 'varModelName')->where('id', $value->fkModuleId)->first();
                $pageData = CmsPage::select('varTitle', 'intAliasId', 'alias.varAlias as recordalias')
                        ->join('alias', 'alias.id', '=', 'cms_page.intAliasId')
                        ->where('cms_page.intFKModuleCode', $moduleCode)
                        ->first();
                if (!empty($pageData)) {
                    if (\Schema::hasColumn($tableName->varTableName, 'intAliasId')) {
                        $recordData = DB::table($tableName->varTableName)
                                ->select('varTitle', 'intAliasId', 'alias.varAlias as recordalias')
                                ->join('alias', 'alias.id', '=', $tableName->varTableName . '.intAliasId')
                                ->where($tableName->varTableName . '.id', $value->fkIntPageId);
                        if (\Schema::hasColumn($tableName->varTableName, 'chrMain'))
                            ;
                        {
                            $recordData = $recordData->where($tableName->varTableName . '.chrMain', 'Y');
                        }
                        if (\Schema::hasColumn($tableName->varTableName, 'chrIsPreview'))
                            ;
                        {
                            $recordData = $recordData->where($tableName->varTableName . '.chrIsPreview', 'N');
                        }
                        $recordData = $recordData->first();
                        $recordlink = url($pageData->recordalias . '/' . $recordData->recordalias);
                        $otherDetail = '<a href="' . $recordlink . '" target="_blank">' . $recordlink . '</a>';
                    } else {
                        $otherDetail = '-';
                    }
                } else {
                    $otherDetail = 'No Link Available';
                }
            } else {
                $recordData = CmsPage::select('varTitle', 'intAliasId', 'alias.varAlias as recordalias')
                        ->join('alias', 'alias.id', '=', 'cms_page.intAliasId')
                        ->where('cms_page.id', $value->fkIntPageId)
                        ->where('cms_page.chrMain', 'Y')
                        ->where('cms_page.chrIsPreview', 'N')
                        ->first();
                if (!empty($recordData)) {
                    $recordlink = url($recordData->recordalias);
                    $otherDetail = '<a href="' . $recordlink . '" target="_blank">' . $recordlink . '</a>';
                } else {
                    $otherDetail = 'No Link Available';
                }
            }
        } else {
            $otherDetail = '<a href="' . $value->varExtLink . '" target="_blank">' . $value->varExtLink . '</a>';
        }
        return $otherDetail;
    }

    public static function flushCache() {
        Cache::tags('Alerts')->flush();
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
        $arrResults = Alerts::getBuilderRecordList($filterArr);
        $found = $arrResults->toArray();
        if (!empty($found)) {
            foreach ($arrResults as $key => $value) {
                $rows .= $this->tableDataBuilder($value, false, $filterArr['selected']);
            }
        } else {
            $rows .= '<tr id="not-found"><td colspan="4" align="center">No records found.</td></tr>';
        }
        $iTotalRecords = CommonModel::getTotalRecordCount('Powerpanel\Alerts\Models\Alerts', true, false);
        $records["data"] = $rows;
        $records["found"] = count($found);
        $records["recordsTotal"] = $iTotalRecords;
        return json_encode($records);
    }

    public function tableDataBuilder($value = false, $fcnt = false, $selected = []) {
        $publish_action = '';
        $dtFormat = Config::get('Constant.DEFAULT_DATE_FORMAT');
        if ($value->intAlertType == 1) {
            $alertType = "High";
        } else if ($value->intAlertType == 2) {
            $alertType = "Medium";
        } else {
            $alertType = "Low";
        }
        $startDate = date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($value->dtDateTime));
        $endDate = !empty($value->dtEndDateTime) ? date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($value->dtEndDateTime)) : 'No Expiry';

        $record = '<tr ' . (in_array($value->id, $selected) ? 'class="selected-record"' : '') . '>';
        $record .= '<td width="1%" align="center">';
        $record .= '<label class="mt-checkbox mt-checkbox-outline">';
        $record .= '<input type="checkbox" data-title="' . $value->varTitle . '" name="delete[]" class="chkChoose" ' . (in_array($value->id, $selected) ? 'checked' : '') . ' value="' . $value->id . '">';
        $record .= '<span></span>';
        $record .= '</label>';
        $record .= '</td>';
        $record .= '<td width="20%" align="left">';
        $record .= $value->varTitle;
        $record .= '</td>';
        $record .= '<td width="20%" align="left">';
        $record .= $alertType;
        $record .= '</td>';
        $record .= '<td width="20%" align="center">';
        $record .= $startDate;
        $record .= '</td>';
        $record .= '<td width="20%" align="center">';
        $record .= $endDate;
        $record .= '</td>';
        $record .= '<td width="20%" align="center">';
        $record .= date($dtFormat, strtotime($value->updated_at));
        $record .= '</td>';
        $record .= '</tr>';
        return $record;
    }

    public function rollBackRecord(Request $request) {

        $message = 'Oops! Something went wrong';
        $requestArr = Request::all();
        $request = (object) $requestArr;
        
        $previousRecord = Alerts::getPreviousRecordByMainId($request->id);
        if(!empty($previousRecord)) {

            $main_id = $previousRecord->fkMainRecord;
            $request->id = $previousRecord->id;
            $request->main_id = $main_id;

            $message = Alerts::approved_data_Listing($request);
            
            $newBlogObj = Alerts::getRecordForLogById($main_id);
            $restoredata = Config::get('Constant.ROLLBACK_RECORD');

            /* notification for user to record approved */
            $blogs = Alerts::getRecordForLogById($previousRecord->id);
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
