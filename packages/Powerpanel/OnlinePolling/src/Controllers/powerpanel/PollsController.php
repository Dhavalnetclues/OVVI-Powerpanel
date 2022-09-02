<?php

namespace Powerpanel\OnlinePolling\Controllers\Powerpanel;

use App\Alias;
use App\CommonModel;
use App\Helpers\MyLibrary;
use App\Http\Controllers\PowerpanelController;
use App\Log;
use App\Modules;
use App\RecentUpdates;
use App\User;
use App\UserNotification;
use Auth;
use Cache;
use Carbon\Carbon;
use Config;
use Illuminate\Support\Facades\Redirect;
use Powerpanel\OnlinePolling\Models\Poll;
use Powerpanel\RoleManager\Models\Role_user;
use Powerpanel\Workflow\Models\Comments;
use Powerpanel\Workflow\Models\Workflow;
use Powerpanel\Workflow\Models\WorkflowLog;
use Request;
use Validator;
use DB;
use File;


class PollsController extends PowerpanelController
{

    /**
     * Create a new controller instance.
     * @return void
     */
    public $moduleHaveFields = [];

    public function __construct()
    {
        parent::__construct();
        if (isset($_COOKIE['locale'])) {
            app()->setLocale($_COOKIE['locale']);
        }
        $this->moduleHaveFields = ['chrMain'];
        $this->MyLibrary = new MyLibrary();
        $this->CommonModel = new CommonModel();
        $this->Alias = new Alias();
    }

    /**
     * This method handels load OnlinePolling grid
     * @return  View
     * @since   2017-07-20
     * @author  NetQuick
     */
    public function index()
    {
        $userIsAdmin = false;
        if (isset($this->currentUserRoleData) && !empty($this->currentUserRoleData)) {
            if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                $userIsAdmin = true;
            }
        } else {
            $userIsAdmin = true;
        }

        $total = Poll::getRecordCount();
        $NewRecordsCount = Poll::getNewRecordsCount();
        $draftTotalRecords = Poll::getRecordCountforListDarft(false, true, $userIsAdmin, array());
        $trashTotalRecords = Poll::getRecordCountforListTrash();
        $favoriteTotalRecords = Poll::getRecordCountforListFavorite();
        $this->breadcrumb['title'] = trans('polls::polls::template.onlinepollingModule.manageonlinepolling');

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
        return view('polls::powerpanel.index', ['iTotalRecords' => $total, 'breadcrumb' => $this->breadcrumb, 'NewRecordsCount' => $NewRecordsCount, 'userIsAdmin' => $userIsAdmin, 'draftTotalRecords' => $draftTotalRecords, 'trashTotalRecords' => $trashTotalRecords, 'favoriteTotalRecords' => $favoriteTotalRecords, 'settingarray' => $settingarray]);
    }

    /**
     * This method handels list of OnlinePolling with filters
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
        $filterArr['orderColumnNo'] = (!empty(Request::input('order')[0]['column']) ? Request::input('order')[0]['column'] : '');
        $filterArr['orderByFieldName'] = (!empty(Request::input('columns')[$filterArr['orderColumnNo']]['name']) ? Request::input('columns')[$filterArr['orderColumnNo']]['name'] : '');
        $filterArr['orderTypeAscOrDesc'] = (!empty(Request::input('order')[0]['dir']) ? Request::input('order')[0]['dir'] : '');
        $filterArr['statusFilter'] = !empty(Request::input('statusValue')) ? Request::input('statusValue') : '';
        $filterArr['searchFilter'] = !empty(Request::input('searchValue')) ? Request::input('searchValue') : '';
        $filterArr['catFilter'] = !empty(Request::input('catValue')) ? Request::input('catValue') : '';
        $filterArr['iDisplayLength'] = intval(Request::input('length'));
        $filterArr['iDisplayStart'] = intval(Request::input('start'));
        $sEcho = intval(Request::input('draw'));
        $isAdmin = false;
        if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
            $isAdmin = true;
        }
        $arrResults = Poll::getRecordList($filterArr, $isAdmin);
        $iTotalRecords = Poll::getRecordCountforList($filterArr, true, $isAdmin);
        $end = $filterArr['iDisplayStart'] + $filterArr['iDisplayLength'];
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;
        $tableSortedType = (isset($filterArr['orderTypeAscOrDesc']) && $filterArr['orderTypeAscOrDesc'] != "") ? $filterArr['orderTypeAscOrDesc'] : '';
        $totalRecords = Poll::getRecordCount();
        if (count($arrResults) > 0 && !empty($arrResults)) {
            foreach ($arrResults as $key => $value) {
                $records["data"][] = $this->tableData($value, $totalRecords, $tableSortedType);
            }
        }
        $NewRecordsCount = Poll::getNewRecordsCount();
        $records["newRecordCount"] = $NewRecordsCount;
        $records["customActionStatus"] = "OK";
        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;
        echo json_encode($records);
        exit;
    }

    public function get_list_New()
    {
        /* Start code for sorting */
        $filterArr = [];
        $records = array();
        $records["data"] = array();
        $filterArr['orderColumnNo'] = (!empty(Request::input('order')[0]['column']) ? Request::input('order')[0]['column'] : '');
        $filterArr['orderByFieldName'] = (!empty(Request::input('columns')[$filterArr['orderColumnNo']]['name']) ? Request::input('columns')[$filterArr['orderColumnNo']]['name'] : '');
        $filterArr['orderTypeAscOrDesc'] = (!empty(Request::input('order')[0]['dir']) ? Request::input('order')[0]['dir'] : '');
        $filterArr['statusFilter'] = !empty(Request::input('statusValue')) ? Request::input('statusValue') : '';
        $filterArr['searchFilter'] = !empty(Request::input('searchValue')) ? Request::input('searchValue') : '';
        $filterArr['iDisplayLength'] = intval(Request::input('length'));
        $filterArr['iDisplayStart'] = intval(Request::input('start'));
        $sEcho = intval(Request::input('draw'));
        $arrResults = Poll::getRecordList_tab1($filterArr);
        $iTotalRecords = Poll::getRecordCountListApprovalTab($filterArr, true);
        $end = $filterArr['iDisplayStart'] + $filterArr['iDisplayLength'];
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;
        $totalRecords = Poll::getRecordCount();

        if (count($arrResults) > 0 && !empty($arrResults)) {
            foreach ($arrResults as $key => $value) {
                $records["data"][] = $this->tableData_tab1($value, $totalRecords);
            }
        }

        $NewRecordsCount = Poll::getNewRecordsCount();
        $records["newRecordCount"] = $NewRecordsCount;
        $records["customActionStatus"] = "OK";
        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;
        echo json_encode($records);
        exit;
    }

    /**
     * This method handels list of Blogs with filters
     * @return  View
     * @since   2017-07-20
     * @author  NetQuick
     */
    public function get_list_favorite() {
        /* Start code for sorting */
        $filterArr = [];
        $records = array();
        $records["data"] = array();
        $filterArr['orderColumnNo'] = (!empty(Request::input('order')[0]['column']) ? Request::input('order')[0]['column'] : '');
        $filterArr['orderByFieldName'] = (!empty(Request::input('columns')[$filterArr['orderColumnNo']]['name']) ? Request::input('columns')[$filterArr['orderColumnNo']]['name'] : '');
        $filterArr['orderTypeAscOrDesc'] = (!empty(Request::input('order')[0]['dir']) ? Request::input('order')[0]['dir'] : '');
        $filterArr['customFilterIdentity'] = !empty(Request::get('customFilterIdentity')) ? Request::get('customFilterIdentity') : '';
        $filterArr['statusFilter'] = !empty(Request::input('statusValue')) ? Request::input('statusValue') : '';
        $filterArr['catFilter'] = !empty(Request::input('catValue')) ? Request::input('catValue') : '';
        $filterArr['searchFilter'] = !empty(Request::input('searchValue')) ? Request::input('searchValue') : '';
        $filterArr['iDisplayLength'] = intval(Request::input('length'));
        $filterArr['iDisplayStart'] = intval(Request::input('start'));
        $sEcho = intval(Request::input('draw'));
        $isAdmin = false;
        if (!empty($this->currentUserRoleData)) {
            if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                $isAdmin = true;
            }
        }
        $arrResults = Poll::getRecordListFavorite($filterArr, $isAdmin);
        $iTotalRecords = Poll::getRecordCountforListFavorite($filterArr, true, $isAdmin);
        $end = $filterArr['iDisplayStart'] + $filterArr['iDisplayLength'];
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;
        $tableSortedType = (isset($filterArr['orderTypeAscOrDesc']) && $filterArr['orderTypeAscOrDesc'] != "") ? $filterArr['orderTypeAscOrDesc'] : '';
        $totalRecords = Poll::getRecordCount();
        if (count($arrResults) > 0 && !empty($arrResults)) {
            foreach ($arrResults as $key => $value) {
                $records["data"][] = $this->tableDataFavorite($value, $totalRecords, $tableSortedType);
            }
        }
        $NewRecordsCount = Poll::getNewRecordsCount();
        $records["newRecordCount"] = $NewRecordsCount;
        $records["customActionStatus"] = "OK";
        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;
        echo json_encode($records);
        exit;
    }

    /**
     * This method handels list of Blogs with filters
     * @return  View
     * @since   2017-07-20
     * @author  NetQuick
     */
    public function get_list_draft() {
        /* Start code for sorting */
        $filterArr = [];
        $records = array();
        $records["data"] = array();
        $filterArr['orderColumnNo'] = (!empty(Request::input('order')[0]['column']) ? Request::input('order')[0]['column'] : '');
        $filterArr['orderByFieldName'] = (!empty(Request::input('columns')[$filterArr['orderColumnNo']]['name']) ? Request::input('columns')[$filterArr['orderColumnNo']]['name'] : '');
        $filterArr['orderTypeAscOrDesc'] = (!empty(Request::input('order')[0]['dir']) ? Request::input('order')[0]['dir'] : '');
        $filterArr['customFilterIdentity'] = !empty(Request::get('customFilterIdentity')) ? Request::get('customFilterIdentity') : '';
        $filterArr['statusFilter'] = !empty(Request::input('statusValue')) ? Request::input('statusValue') : '';
        $filterArr['catFilter'] = !empty(Request::input('catValue')) ? Request::input('catValue') : '';
        $filterArr['searchFilter'] = !empty(Request::input('searchValue')) ? Request::input('searchValue') : '';
        $filterArr['iDisplayLength'] = intval(Request::input('length'));
        $filterArr['iDisplayStart'] = intval(Request::input('start'));
        $sEcho = intval(Request::input('draw'));
        $isAdmin = false;
        if (!empty($this->currentUserRoleData)) {
            if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                $isAdmin = true;
            }
        }
        $arrResults = Poll::getRecordListDraft($filterArr, $isAdmin);
        $iTotalRecords = Poll::getRecordCountforListDarft($filterArr, true, $isAdmin);
        $end = $filterArr['iDisplayStart'] + $filterArr['iDisplayLength'];
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;
        $tableSortedType = (isset($filterArr['orderTypeAscOrDesc']) && $filterArr['orderTypeAscOrDesc'] != "") ? $filterArr['orderTypeAscOrDesc'] : '';
        $totalRecords = Poll::getRecordCount();
        if (count($arrResults) > 0 && !empty($arrResults)) {
            foreach ($arrResults as $key => $value) {
                $records["data"][] = $this->tableDataDraft($value, $totalRecords, $tableSortedType);
            }
        }
        $NewRecordsCount = Poll::getNewRecordsCount();
        $records["newRecordCount"] = $NewRecordsCount;
        $records["customActionStatus"] = "OK";
        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;
        echo json_encode($records);
        exit;
    }

    /**
     * This method handels list of Blogs with filters
     * @return  View
     * @since   2017-07-20
     * @author  NetQuick
     */
    public function get_list_trash() {
        /* Start code for sorting */
        $filterArr = [];
        $records = array();
        $records["data"] = array();
        $filterArr['orderColumnNo'] = (!empty(Request::input('order')[0]['column']) ? Request::input('order')[0]['column'] : '');
        $filterArr['orderByFieldName'] = (!empty(Request::input('columns')[$filterArr['orderColumnNo']]['name']) ? Request::input('columns')[$filterArr['orderColumnNo']]['name'] : '');
        $filterArr['orderTypeAscOrDesc'] = (!empty(Request::input('order')[0]['dir']) ? Request::input('order')[0]['dir'] : '');
        $filterArr['customFilterIdentity'] = !empty(Request::get('customFilterIdentity')) ? Request::get('customFilterIdentity') : '';
        $filterArr['statusFilter'] = !empty(Request::input('statusValue')) ? Request::input('statusValue') : '';
        $filterArr['catFilter'] = !empty(Request::input('catValue')) ? Request::input('catValue') : '';
        $filterArr['searchFilter'] = !empty(Request::input('searchValue')) ? Request::input('searchValue') : '';
        $filterArr['iDisplayLength'] = intval(Request::input('length'));
        $filterArr['iDisplayStart'] = intval(Request::input('start'));
        $sEcho = intval(Request::input('draw'));
        $isAdmin = false;
        if (!empty($this->currentUserRoleData)) {
            if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                $isAdmin = true;
            }
        }
        $arrResults = Poll::getRecordListTrash($filterArr, $isAdmin);
        $iTotalRecords = Poll::getRecordCountforListTrash($filterArr, true, $isAdmin);
        $end = $filterArr['iDisplayStart'] + $filterArr['iDisplayLength'];
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;
        $tableSortedType = (isset($filterArr['orderTypeAscOrDesc']) && $filterArr['orderTypeAscOrDesc'] != "") ? $filterArr['orderTypeAscOrDesc'] : '';
        $totalRecords = Poll::getRecordCount();
        if (count($arrResults) > 0 && !empty($arrResults)) {
            foreach ($arrResults as $key => $value) {
                $records["data"][] = $this->tableDataTrash($value, $totalRecords, $tableSortedType);
            }
        }
        $NewRecordsCount = Poll::getNewRecordsCount();
        $records["newRecordCount"] = $NewRecordsCount;
        $records["customActionStatus"] = "OK";
        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;
        echo json_encode($records);
        exit;
    }


    /**
     * This method loads OnlinePolling edit view
     * @param      Alias of record
     * @return  View
     * @since   2017-07-21
     * @author  NetQuick
     */
    public function edit($alias = false)
    {

        $userIsAdmin = false;
        if (!empty($this->currentUserRoleData)) {
            if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                $userIsAdmin = true;
            }
        }
        $sector = array('ofreg' => 'OFREG', 'water' => 'WATER', 'ict' => 'ICT', 'energy' => 'ENERGY', 'fuel' => 'FUEL');

        $templateData = array();
        if (!is_numeric($alias)) {

            $total = Poll::getRecordCount();
            if ($userIsAdmin) {
                $total = $total + 1;
            }
            $this->breadcrumb['title'] = trans('polls::template.onlinepollingModule.addPoll');
            $this->breadcrumb['module'] = trans('polls::template.onlinepollingModule.manageonlinepolling');
            $this->breadcrumb['url'] = 'powerpanel/online-polling';

            $this->breadcrumb['inner_title'] = trans('polls::template.onlinepollingModule.addPoll');
            $templateData['total'] = $total;
            $templateData['sector'] = $sector;
            $templateData['breadcrumb'] = $this->breadcrumb;

        } else {

            $id = $alias;
            $onlinepolling = Poll::getRecordById($id);
            if (empty($onlinepolling)) {
                return redirect()->route('powerpanel.polls.add');
            }
            if ($onlinepolling->fkMainRecord != '0') {
                $onlinepolling_highLight = Poll::getRecordById($onlinepolling->fkMainRecord);
                $templateData['onlinepolling_highLight'] = $onlinepolling_highLight;
            } else {
                $templateData['onlinepolling_highLight'] = "";
            }
            $this->breadcrumb['title'] = trans('polls::template.onlinepollingModule.editonlinepolling') . ' - ' . $onlinepolling->varTitle;
            $this->breadcrumb['module'] = trans('polls::template.onlinepollingModule.manageonlinepolling');
            $this->breadcrumb['url'] = 'powerpanel/online-polling';
            $this->breadcrumb['inner_title'] = trans('polls::template.onlinepollingModule.editonlinepolling') . ' - ' . $onlinepolling->varTitle;
            $templateData['onlinepolling'] = $onlinepolling;
            $templateData['id'] = $id;
            $templateData['breadcrumb'] = $this->breadcrumb;
            $templateData['sector'] = $sector;

        }

        $templateData['userIsAdmin'] = $userIsAdmin;

        return view('polls::powerpanel.actions', $templateData);
    }

    /**
     * This method stores OnlinePolling modifications
     * @return  View
     * @since   2017-07-21
     * @author  NetQuick
     */
    public function handlePost(Request $request)
    {

        $userIsAdmin = false;
        if (!empty($this->currentUserRoleData)) {
            if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                $userIsAdmin = true;
            }
        }
        $postArr = Request::all();        

        $messsages = [
            'title.required' => 'Title field is required.',
            'start_date_time.required' => 'Statrt Date field is required.',
            'display_order.required' => trans('polls::template.onlinepollingModule.displayOrder'),
            'display_order.greater_than_zero' => trans('polls::template.onlinepollingModule.displayGreaterThan')];
        $rules = [
            'title' => 'required|max:160|handle_xss|no_url',
            'start_date_time' => 'required|max:160',
            'display_order' => 'required|greater_than_zero|handle_xss|no_url',
        ];
        $validator = Validator::make($postArr, $rules, $messsages);
        if ($validator->passes()) {
            $onlinepollingArr = [];
            $module = Modules::getModuleById(Config::get('Constant.MODULE.ID'));
            $id = Request::segment(3);
            $actionMessage = trans('polls::template.onlinepollingModule.updateMessage');
            if (is_numeric($id)) { 
                #Edit post Handler=======
                $onlinepolling = Poll::getRecordForLogById($id);

                $updateonlinepollingFields = [];
                $updateonlinepollingFields['varTitle'] = stripslashes(trim($postArr['title']));

                $txtQuestionData = array();
                if(isset($postArr['question']) && !empty($postArr['question'])) {

                    foreach($postArr['question'] as $key => $value) {

                        if(isset($value['question']) && !empty($value['question'])) {
                            $txtQuestionData[$key]['question'] = $value['question'];
                            $txtQuestionData[$key]['question_choice'] = $value['question_choice'];
                            if(isset($value['options']) && !empty($value['options'])) {
                                foreach($value['options'] as $okey => $ovalue) {
                                    if(isset($ovalue[$okey]) && !empty($ovalue[$okey])){
                                        $txtQuestionData[$key]['options'][$okey] = $ovalue;
                                    }
                                }
                            }
                        }
                    }
                }

                $updateonlinepollingFields['txtQuestionData'] = (!empty($txtQuestionData)?json_encode($txtQuestionData):NULL);
                $updateonlinepollingFields['intAudienceLimit'] = $postArr['audience_limit'];

                $updateonlinepollingFields['intFKCategory'] = 0;
                $updateonlinepollingFields['dtDateTime'] = !empty($postArr['start_date_time']) ? date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $postArr['start_date_time']))) : date('Y-m-d H:i:s');
                $updateonlinepollingFields['dtEndDateTime'] = !empty($postArr['end_date_time']) ? date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $postArr['end_date_time']))) : null;
                $updateonlinepollingFields['UserID'] = auth()->user()->id;

                if ($postArr['chrMenuDisplay'] == 'D') {
                    $updateonlinepollingFields['chrDraft'] = 'D';
                    $updateonlinepollingFields['chrPublish'] = 'N';
                } else {
                    $updateonlinepollingFields['chrDraft'] = 'N';
                    $updateonlinepollingFields['chrPublish'] = $postArr['chrMenuDisplay'];
                }

                $whereConditions = ['id' => $id];
                if (!$userIsAdmin) {
                    $userRole = $this->currentUserRoleData->id;
                } else {
                    $userRoleData = Role_user::getUserRoleByUserId($onlinepolling->UserID);
                    if (isset($userRoleData->role_id)) {
                        $userRole = $userRoleData->role_id;
                    } else {
                        $userRole = $this->currentUserRoleData->id;
                    }
                }

                if ($postArr['chrMenuDisplay'] == 'D') {
                    DB::table('menu')->where('intPageId', $id)->where('intfkModuleId', Config::get('Constant.MODULE.ID'))->delete();
                }
                
                $workFlowByCat = Workflow::getRecordByCategoryId($module->intFkGroupCode, $userRole, Config::get('Constant.MODULE.ID'));
                if (empty($workFlowByCat->varUserId) || $userIsAdmin || $workFlowByCat->charNeedApproval == 'N') {
                    if ((int) $onlinepolling->fkMainRecord === 0 || empty($workFlowByCat->varUserId)) {
                        $update = CommonModel::updateRecords($whereConditions, $updateonlinepollingFields, false, '\\Powerpanel\\OnlinePolling\\Models\\Poll');
                        if ($update) {
                            if ($id > 0 && !empty($id)) {
                                self::swap_order_edit($postArr['display_order'], $id);
                                $logArr = MyLibrary::logData($id);
                                if (Auth::user()->can('log-advanced')) {
                                    $newonlinepollingObj = Poll::getRecordForLogById($id);
                                    $oldRec = $this->recordHistory($onlinepolling);
                                    $newRec = $this->newrecordHistory($onlinepolling, $newonlinepollingObj);
                                    $logArr['old_val'] = $oldRec;
                                    $logArr['new_val'] = $newRec;
                                }
                                $logArr['varTitle'] = stripslashes(trim($postArr['title']));
                                Log::recordLog($logArr);
                                if (Auth::user()->can('recent-updates-list')) {
                                    if (!isset($newonlinepollingObj)) {
                                        $newonlinepollingObj = Poll::getRecordForLogById($id);
                                    }
                                    $notificationArr = MyLibrary::notificationData($id, $newonlinepollingObj);
                                    RecentUpdates::setNotification($notificationArr);
                                }
                                self::flushCache();
                                if (isset($postArr['saveandexit']) && $postArr['saveandexit'] == 'approvesaveandexit') {
                                    $actionMessage = trans('polls::template.common.recordApprovalMessage');
                                } else {
                                    $actionMessage = trans('polls::template.onlinepollingModule.updateMessage');
                                }
                            }
                        }
                    } else {
                        $updateModuleFields = $updateonlinepollingFields;
                        $this->insertApprovedRecord($updateModuleFields, $postArr, $id);
                        if (isset($postArr['saveandexit']) && $postArr['saveandexit'] == 'approvesaveandexit') {
                            $actionMessage = trans('polls::template.common.recordApprovalMessage');
                        } else {
                            $actionMessage = trans('polls::template.onlinepollingModule.updateMessage');
                        }
                    }
                } else {
                    if ($workFlowByCat->charNeedApproval == 'Y') {
                        $this->insertApprovalRecord($onlinepolling, $postArr, $onlinepollingArr);
                        if (isset($postArr['saveandexit']) && $postArr['saveandexit'] == 'approvesaveandexit') {
                            $actionMessage = trans('polls::template.common.recordApprovalMessage');
                        } else {
                            $actionMessage = trans('polls::template.onlinepollingModule.updateMessage');
                        }
                    }
                }

            } else { 

                #Add post Handler=======

                $workFlowByCat = Workflow::getRecordByCategoryId($module->intFkGroupCode, $this->currentUserRoleData->id, Config::get('Constant.MODULE.ID'));
                if (!empty($workFlowByCat->varUserId) && $workFlowByCat->chrNeedAddPermission == 'Y' && !$userIsAdmin) {
                    $postArr['chrPublish'] = 'N';
                    $postArr['chrDraft'] = 'N';
                    $onlinepollingObj = $this->insertNewRecord($postArr, $onlinepollingArr);
                    if ($postArr['chrMenuDisplay'] == 'D') {
                        $postArr['chrDraft'] = 'D';
                    }
                    $postArr['chrPublish'] = 'Y';
                    $this->insertApprovalRecord($onlinepollingObj, $postArr, $onlinepollingArr);
                } else {
                    $onlinepollingObj = $this->insertNewRecord($postArr, $onlinepollingArr);
                }
                if (isset($postArr['saveandexit']) && $postArr['saveandexit'] == 'approvesaveandexit') {
                    $actionMessage = trans('polls::template.common.recordApprovalMessage');
                } else {
                    $actionMessage = trans('polls::template.onlinepollingModule.addMessage');
                }
                $id = $onlinepollingObj->id;
            }
            if ((!empty(Request::get('saveandexit')) && Request::get('saveandexit') == 'saveandexit') || !$userIsAdmin) {
                if ($postArr['chrMenuDisplay'] == 'D') {
                    return redirect()->route('powerpanel.polls.index', 'tab=D')->with('message', $actionMessage);
                } else {
                    return redirect()->route('powerpanel.polls.index')->with('message', $actionMessage);
                }
            } else {
                return redirect()->route('powerpanel.polls.edit', $id)->with('message', $actionMessage);
            }
        } else {
            return Redirect::back()->withErrors($validator)->withInput();
        }
    }

    public function insertApprovedRecord($updateModuleFields, $postArr, $id)
    {
        $whereConditions = ['id' => $postArr['fkMainRecord']];
        $updateModuleFields['chrAddStar'] = 'N';
        $update = CommonModel::updateRecords($whereConditions, $updateModuleFields, false, '\\Powerpanel\\OnlinePolling\\Models\\Poll');
        if ($update) {
            self::swap_order_edit($postArr['display_order'], $postArr['fkMainRecord']);
        }
        $whereConditions_ApproveN = ['fkMainRecord' => $postArr['fkMainRecord']];
        $updateToApproveN = [
            'chrApproved' => 'N',
            'chrLetest' => 'N',
            'intApprovedBy' => '0',
        ];
        CommonModel::updateRecords($whereConditions_ApproveN, $updateToApproveN, false, '\\Powerpanel\\OnlinePolling\\Models\\Poll');
        $whereConditionsApprove = ['id' => $id, 'chrMain' => 'N'];
        $updateToApprove = [
            'chrApproved' => 'Y',
            'chrRollBack' => 'Y',
            'intApprovedBy' => auth()->user()->id,
        ];
        CommonModel::updateRecords($whereConditionsApprove, $updateToApprove, false, '\\Powerpanel\\OnlinePolling\\Models\\Poll');
        $addlog = Config::get('Constant.RECORD_APPROVED');
        $newBannerObj = Poll::getRecordForLogById($id);
        $logArr = MyLibrary::logData($id, false, $addlog);
        $logArr['varTitle'] = stripslashes($newBannerObj->varTitle);
        Log::recordLog($logArr);
        /* notification for user to record approved */
        $userNotificationArr = MyLibrary::userNotificationData(Config::get('Constant.MODULE.ID'));
        $userNotificationArr['fkRecordId'] = $id;
        $userNotificationArr['txtNotification'] = 'Your request has been approved by ' . ucfirst(auth()->user()->name) . ' (' . ucfirst(Config::get('Constant.MODULE.NAME')) . ')';
        $userNotificationArr['fkIntUserId'] = Auth::user()->id;
        $userNotificationArr['chrNotificationType'] = 'A';
        $userNotificationArr['intOnlyForUserId'] = $newBannerObj->UserID;
        UserNotification::addRecord($userNotificationArr);
        /* notification for user to record approved */
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
                $actionMessage = trans('polls::template.onlinepollingModule.updateMessage');
            }
        }
    }

    public function insertApprovalRecord($moduleObj, $postArr, $onlinepollingArr)
    {
        $onlinepollingArr['chrMain'] = 'N';
        $onlinepollingArr['chrLetest'] = 'Y';
        $onlinepollingArr['fkMainRecord'] = $moduleObj->id;
        $onlinepollingArr['varTitle'] = stripslashes(trim($postArr['title']));

        $txtQuestionData = array();
        if(isset($postArr['question']) && !empty($postArr['question'])) {

            foreach($postArr['question'] as $key => $value) {

                if(isset($value['question']) && !empty($value['question'])) {
                    $txtQuestionData[$key]['question'] = $value['question'];
                    $txtQuestionData[$key]['question_choice'] = $value['question_choice'];
                    if(isset($value['options']) && !empty($value['options'])) {
                        foreach($value['options'] as $okey => $ovalue) {
                            if(isset($ovalue[$okey]) && !empty($ovalue[$okey])){
                                $txtQuestionData[$key]['options'][$okey] = $ovalue;
                            }
                        }
                    }
                }
            }
        }
        $onlinepollingArr['txtQuestionData'] = (!empty($txtQuestionData)?json_encode($txtQuestionData):NULL);
        $onlinepollingArr['intAudienceLimit'] = $postArr['audience_limit'];

        $onlinepollingArr['intDisplayOrder'] = $postArr['display_order'];
        $onlinepollingArr['chrPublish'] = isset($postArr['chrMenuDisplay']) ? $postArr['chrMenuDisplay'] : 'Y';
        $onlinepollingArr['created_at'] = Carbon::now();
        $onlinepollingArr['dtDateTime'] = !empty($postArr['start_date_time']) ? date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $postArr['start_date_time']))) : date('Y-m-d H:i:s');
        $onlinepollingArr['dtEndDateTime'] = !empty($postArr['end_date_time']) ? date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $postArr['end_date_time']))) : null;
        $onlinepollingArr['UserID'] = auth()->user()->id;
        $addlog = Config::get('Constant.SENT_FOR_APPROVAL');
        $managementteamID = CommonModel::addRecord($onlinepollingArr, '\\Powerpanel\\OnlinePolling\\Models\\Poll');
        if (!empty($managementteamID)) {
            $id = $managementteamID;
            WorkflowLog::addRecord([
                'fkModuleId' => Config::get('Constant.MODULE.ID'),
                'fkRecordId' => $moduleObj->id,
                'charApproval' => 'Y',
            ]);
            $userNotificationArr = MyLibrary::userNotificationData(Config::get('Constant.MODULE.ID'));
            $userNotificationArr['fkRecordId'] = $moduleObj->id;
            $userNotificationArr['txtNotification'] = 'New approval request from ' . ucfirst(auth()->user()->name) . ' (' . ucfirst(Config::get('Constant.MODULE.NAME')) . ')';
            $userNotificationArr['fkIntUserId'] = Auth::user()->id;
            $userNotificationArr['chrNotificationType'] = 'A';
            UserNotification::addRecord($userNotificationArr);
            $newonlinepollingObj = Poll::getRecordForLogById($id);
            $logArr = MyLibrary::logData($id, false, $addlog);
            $logArr['varTitle'] = $newonlinepollingObj->varTitle;
            Log::recordLog($logArr);
            if (Auth::user()->can('recent-updates-list')) {
                $notificationArr = MyLibrary::notificationData($id, $newonlinepollingObj);
                RecentUpdates::setNotification($notificationArr);
            }
            self::flushCache();
            $actionMessage = trans('polls::template.onlinepollingModule.addMessage');
        }
        $whereConditionsAddstar = ['id' => $moduleObj->id];
        $updateAddStar = [
            'chrAddStar' => 'Y',
        ];
        CommonModel::updateRecords($whereConditionsAddstar, $updateAddStar, false, '\\Powerpanel\\OnlinePolling\\Models\\Poll');
    }

    public function insertNewRecord($postArr, $onlinepollingArr)
    {
        $response = false;
        $onlinepollingArr['chrMain'] = 'Y';
        $onlinepollingArr['varTitle'] = stripslashes(trim($postArr['title']));

        $txtQuestionData = array();
        if(isset($postArr['question']) && !empty($postArr['question'])) {

            foreach($postArr['question'] as $key => $value) {

                if(isset($value['question']) && !empty($value['question'])) {
                    $txtQuestionData[$key]['question'] = $value['question'];
                    $txtQuestionData[$key]['question_choice'] = $value['question_choice'];
                    if(isset($value['options']) && !empty($value['options'])) {
                        foreach($value['options'] as $okey => $ovalue) {
                            if(isset($ovalue[$okey]) && !empty($ovalue[$okey])){
                                $txtQuestionData[$key]['options'][$okey] = $ovalue;
                            }
                        }
                    }
                }
            }
        }
        $onlinepollingArr['txtQuestionData'] = (!empty($txtQuestionData)?json_encode($txtQuestionData):NULL);
        $onlinepollingArr['intAudienceLimit'] = $postArr['audience_limit'];

        $onlinepollingArr['intDisplayOrder'] = self::swap_order_add($postArr['display_order']);
        $onlinepollingArr['chrPublish'] = isset($postArr['chrMenuDisplay']) ? $postArr['chrMenuDisplay'] : 'Y';
        $onlinepollingArr['intFKCategory'] = 0;
        $onlinepollingArr['dtDateTime'] = !empty($postArr['start_date_time']) ? date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $postArr['start_date_time']))) : date('Y-m-d H:i:s');
        $onlinepollingArr['dtEndDateTime'] = !empty($postArr['end_date_time']) ? date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $postArr['end_date_time']))) : null;
        $onlinepollingArr['UserID'] = auth()->user()->id;
        $onlinepollingArr['created_at'] = Carbon::now();
        $onlinepollingID = CommonModel::addRecord($onlinepollingArr, '\\Powerpanel\\OnlinePolling\\Models\\Poll');
        if (!empty($onlinepollingID)) {
            $id = $onlinepollingID;
            $newonlinepollingObj = Poll::getRecordForLogById($id);
            $logArr = MyLibrary::logData($id);
            $logArr['varTitle'] = $newonlinepollingObj->varTitle;
            Log::recordLog($logArr);
            if (Auth::user()->can('recent-updates-list')) {
                $notificationArr = MyLibrary::notificationData($id, $newonlinepollingObj);
                RecentUpdates::setNotification($notificationArr);
            }
            $response = $newonlinepollingObj;
            self::flushCache();
            $actionMessage = trans('polls::template.onlinepollingModule.addMessage');
        }
        return $response;
    }

    /**
     * This method destroys onlinepolling in multiples
     * @return  onlinepolling index view
     * @since   2016-10-25
     * @author  NetQuick
     */
    public function DeleteRecord(Request $request)
    {
        $data['ids'] = Request::get('ids');
        $moduleHaveFields = ['chrMain'];
        $update = MyLibrary::deleteMultipleRecords($data, $moduleHaveFields, false, '\\Powerpanel\\OnlinePolling\\Models\\Poll');
        Comments::deleteComments($data['ids'], Config::get('Constant.MODULE.MODEL_NAME'));
        foreach ($update as $ids) {
            $ignoreDeleteScope = true;
            $Deleted_Record = Poll::getRecordById($ids, $ignoreDeleteScope);
            $Cnt_Letest = Poll::getRecordCount_letest($Deleted_Record['fkMainRecord'], $Deleted_Record['id']);
            if ($Cnt_Letest <= 0) {
                $updateLetest = [
                    'chrAddStar' => 'N',
                ];
                $whereConditionsApprove = ['id' => $Deleted_Record['fkMainRecord']];
                CommonModel::updateRecords($whereConditionsApprove, $updateLetest, false, '\\Powerpanel\\OnlinePolling\\Models\\Poll');
                $where = [];
                $flowData = [];
                $flowData['dtNo'] = Config::get('Constant.SQLTIMESTAMP');
                $where['fkModuleId'] = Config::get('Constant.MODULE.ID');
                $where['fkRecordId'] = $Deleted_Record['fkMainRecord'];
                $where['dtNo'] = 'null';
                WorkflowLog::updateRecord($flowData, $where);
            }
        }
        self::flushCache();
        echo json_encode($update);
        exit;
    }

    /**
     * This method destroys onlinepolling in multiples
     * @return  onlinepolling index view
     * @since   2016-10-25
     * @author  NetQuick
     */
    public function publish(Request $request)
    {
        $alias = (int) Request::input('alias');
        $update = MyLibrary::setPublishUnpublish($alias, $request, '\\Powerpanel\\OnlinePolling\\Models\\Poll');
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
    public function reorder()
    {
        $order = Request::input('order');
        $exOrder = Request::input('exOrder');
        MyLibrary::swapOrder($order, $exOrder, '\\Powerpanel\\OnlinePolling\\Models\\Poll');
        self::flushCache();
    }

    /**
     * This method handels swapping of available order record while adding
     * @param      order
     * @return  order
     * @since   2016-10-21
     * @author  NetQuick
     */
    public static function swap_order_add($order = null)
    {
        $response = false;
        $isCustomizeModule = true;
        $moduleHaveFields = ['chrMain'];
        if ($order != null) {
            $response = MyLibrary::swapOrderAdd($order, $isCustomizeModule, $moduleHaveFields, '\\Powerpanel\\OnlinePolling\\Models\\Poll');
            self::flushCache();
        }
        return $response;
    }

    /**
     * This method handels swapping of available order record while editing
     * @param      order
     * @return  order
     * @since   2016-12-23
     * @author  NetQuick
     */
    public static function swap_order_edit($order = null, $id = null)
    {
        $isCustomizeModule = true;
        $moduleHaveFields = ['chrMain'];
        MyLibrary::swapOrderEdit($order, $id, $isCustomizeModule, $moduleHaveFields, '\\Powerpanel\\OnlinePolling\\Models\\Poll');
        self::flushCache();
    }

    

    public function tableData($value, $totalRecord = false, $tableSortedType = 'asc')
    {
        $actions = '';
        $titleData = "";
        $publish_action = '';
        $checkbox = '<a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-toggle="tooltip" data-bs-content="' . $titleData . '" title="' . $titleData . '"><i style="color:red" class="ri-alert-fill"></i></a>';

        if ($value->chrAddStar != 'Y') {
            if (Auth::user()->can('online-polling-publish')) {
                if ($value->chrPublish == 'Y') {
                    //Bootstrap Switch
                    $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/online-polling', 'data_alias'=>$value->id, 'title'=>trans("polls::template.common.publishedRecord"), 'data_value'=>'Unpublish'])->render();
                } else {
                    //Bootstrap Switch
                    $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/online-polling', 'data_alias'=>$value->id, 'title'=>trans("polls::template.common.unpublishedRecord"), 'data_value'=>'Publish', 'checked'=>'checked'])->render();
                }
            }
        } else {
            $publish_action .= '---';
        }

        if (Auth::user()->can('online-polling-reviewchanges')) {
            $update = "<a title=\"Click here to see all approval records.\" class=\"icon_title1\" style=\"margin-right: 5px;\" onclick=\"javascript:expandcollapsepanel(this ,'tasklisting" . $value->id . "', 'mainsingnimg" . $value->id . "'," . $value->id . ")\"><i id=\"mainsingnimg" . $value->id . "\" class=\"ri-add-box-line\"></i></a>";
            $rollback = "<a title=\"Click here to see all approved records to rollback.\" class=\"icon_title2\" style=\"margin-right: 5px;\" onclick=\"javascript:expandcollapsepanel_rolback(this ,'tasklisting_rollback" . $value->id . "', 'mainsingnimg_rollback" . $value->id . "'," . $value->id . ")\"><i id=\"mainsingnimg_rollback" . $value->id . "\" class=\"ri-history-line\"></i></a>";
        } else {
            $update = '';
            $rollback = '';
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
        if ($value->chrDraft == 'D') {
            $status .= Config::get('Constant.DRAFT_LIST') . ' ';
        }
        if ($value->chrAddStar == 'Y') {
            $status .= Config::get('Constant.APPROVAL_LIST') . ' ';
        }

        if (Auth::user()->can('online-polling-edit')) {
            $title = '<div class="quick_edit"><a class="text-uppercase" title="' . trans("polls::template.common.edit") . '" href="' . route('powerpanel.polls.edit', array('alias' => $value->id)) . '">' . $value->varTitle . '</a></div>';
        } else {
            $title = stripslashes($value->varTitle);
        }
        
        $orderArrow = '';

        $dispOrder = $value->intDisplayOrder;
        if (($value->intDisplayOrder == $totalRecord || $value->intDisplayOrder < $totalRecord) && $value->intDisplayOrder > 1) {
            $orderArrow .= ' <a href="javascript:void(0);" data-order="' . $value->intDisplayOrder . '" class="moveUp"> <i class="ri-arrow-up-line" aria-hidden="true"></i> </a>';
        }
        $orderArrow .= $dispOrder;
        if (($value->intDisplayOrder != $totalRecord || $value->intDisplayOrder < $totalRecord)) {
            $orderArrow .= ' <a href="javascript:void(0);" data-order="' . $value->intDisplayOrder . '" class="moveDwn"> <i class="ri-arrow-down-line" aria-hidden="true"></i> </a>';
        }

        $startDate = date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($value->dtDateTime));
        $endDate = !empty($value->dtEndDateTime) ? date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($value->dtEndDateTime)) : 'No Expiry';

        if ($publish_action == "") {
            $publish_action = "---";
        } else {
            $publish_action = $publish_action;
        }

        if (Config::get('Constant.DEFAULT_FAVORITE') == 'Y') {
            $Favorite_array = explode(",", $value->FavoriteID);
            if (in_array(auth()->user()->id, $Favorite_array)) {
                $Class = 'ri-star-fill';
                $Favorite = '<a class="star_icon_div" href="javascript:void(0);" onclick="GetFavorite(' . $value->id . ',\'N\',\'P\')"><i class="' . $Class . '"></i></a>';
            } else {
                $Class = 'ri-star-line';
                $Favorite = '<a class="star_icon_div" href="javascript:void(0);" onclick="GetFavorite(' . $value->id . ',\'Y\',\'P\')"><i class="' . $Class . '"></i></a>';
            }
        } else {
            $Favorite = '';
        }
        $First_td = '<div class="star_box">' . $Favorite . '</div>';


        if (Auth::user()->can('online-polling-edit')) {
            $actions .= '<a class="" title="' . trans("polls::template.common.edit") . '" href="' . route('powerpanel.polls.edit', array('alias' => $value->id)) . '">
				<i class="ri-pencil-line"></i></a>';
        }

        if (Auth::user()->can('online-polling-delete') && $this->currentUserRoleData->chrIsAdmin == 'Y') {

            if (Config::get('Constant.DEFAULT_TRASH') == 'Y') {
                $actions .= '<a class="delete-grid" title="' . trans("polls::template.common.delete") . '" onclick = \'Trashfun("' . $value->id . '")\' data-controller="PollsController" data-alias = "' . $value->id . '" data-tab="P"><i class="ri-delete-bin-line"></i></a>';
            } else {
                $actions .= '<a class=" delete" title="' . trans("polls::template.common.delete") . '" data-controller="PollsController" data-alias = "' . $value->id . '" data-tab="P"><i class="ri-delete-bin-line"></i></a>';
            }
        } 

        if(File::exists(base_path() . '/packages/Powerpanel/Workflow/src/Models/Workflow.php')) {
            if($this->currentUserRoleData->chrIsAdmin == 'Y' && count($value->child) > 1) {
                $actions .= "<a title='Rollback to previous version'  onclick=\"rollbackToPreviousVersion('" . $value->id . "');\"  class=\"log-grid\"><i class=\"ri-history-line\"></i></a>";
            }
        }

        $logurl = url('powerpanel/log?id=' . $value->id . '&mid=' . Config::get('Constant.MODULE.ID'));
        $log = '';
        if ($value->chrLock != 'Y') {
            if (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y') {
                if (Config::get('Constant.DEFAULT_DUPLICATE') == 'Y') {
                    $log .= "<a title=\"Duplicate\" class='copy-grid' href=\"javascript:void(0);\" onclick=\"GetCopyPage('" . $value->id . "');\"><i class=\"ri-file-copy-line\"></i></a>";
                }
                $log .= $actions;
                if (Auth::user()->can('log-list')) {
                    $log .= "<a title=\"Log History\" class='log-grid' href=\"$logurl\"><i class=\"ri-time-line\"></i></a>";
                }
            } else {
                if ($actions == "") {
                    $actions = "---";
                } else {
                    $actions = $actions;
                }
                $log .= $actions;
                if (Auth::user()->can('log-list')) {
                    $log .= "<a title=\"Log History\" class='log-grid' href=\"$logurl\"><i class=\"ri-time-line\"></i></a>";
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

        $records = array(
            '<input type="checkbox" name="delete" class="chkDelete form-check-input" value="' . $value->id . '">',
            $First_td,
            '<div class="pages_title_div_row">' . $update . $rollback . $title . ' ' . $statusdata . $status . '</div>',
            $value->intAudienceLimit,
            $startDate,
            $endDate,
            $orderArrow,
            $publish_action,
            $log
        );
        return $records;
    }

    public function tableDataFavorite($value, $totalRecord = false, $tableSortedType = 'asc') {

        $actions = '';
        $titleData = "";
        $publish_action = '';
        $checkbox = '<a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-toggle="tooltip" data-bs-content="' . $titleData . '" title="' . $titleData . '"><i style="color:red" class="ri-alert-fill"></i></a>';
        if (Auth::user()->can('online-polling-edit')) {
            $actions .= '<a class="" title="' . trans("polls::template.common.edit") . '" href="' . route('powerpanel.polls.edit', array('alias' => $value->id)) . '">
				<i class="ri-pencil-line"></i></a>';
        }
        if (Auth::user()->can('online-polling-delete') && $this->currentUserRoleData->chrIsAdmin == 'Y') {

            // $actions .= '<a class=" delete" title="' . trans("polls::template.common.delete") . '" data-controller="onlinepolling" data-alias = "' . $value->id . '"><i class="ri-time-line"></i></a>';
            if (Config::get('Constant.DEFAULT_TRASH') == 'Y') {
                $actions .= '<a class="delete-grid" title="' . trans("polls::template.common.delete") . '" onclick = \'Trashfun("' . $value->id . '")\' data-controller="PollsController" data-alias = "' . $value->id . '" data-tab="P"><i class="ri-delete-bin-line"></i></a>';
            } else {
                $actions .= '<a class=" delete" title="' . trans("polls::template.common.delete") . '" data-controller="PollsController" data-alias = "' . $value->id . '" data-tab="P"><i class="ri-delete-bin-line"></i></a>';
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
        if ($value->chrDraft == 'D') {
            $status .= Config::get('Constant.DRAFT_LIST') . ' ';
        }
        if ($value->chrAddStar == 'Y') {
            $status .= Config::get('Constant.APPROVAL_LIST') . ' ';
        }

        if (Auth::user()->can('log-list')) {
            $logurl = url('powerpanel/log?id=' . $value->id . '&mid=' . Config::get('Constant.MODULE.ID'));
            $actions .= "<a title=\"Log History\" class='log-grid' href=\"$logurl\"><i class=\"ri-time-line\"></i></a>";
        }

        if ($value->chrAddStar != 'Y') {
            if (Auth::user()->can('online-polling-publish')) {
                if ($value->chrPublish == 'Y') {
                    //Bootstrap Switch
                    $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/online-polling', 'data_alias'=>$value->id, 'title'=>trans("polls::template.common.publishedRecord"), 'data_value'=>'Unpublish'])->render();
                } else {
                    //Bootstrap Switch
                    $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/online-polling', 'data_alias'=>$value->id, 'title'=>trans("polls::template.common.unpublishedRecord"), 'data_value'=>'Publish', 'checked'=>'checked'])->render();
                }
            }
        } else {
            $publish_action .= '---';
        }
        if (Auth::user()->can('online-polling-reviewchanges')) {
            $update = "<a title=\"Click here to see all approval records.\" class=\"icon_title1\" style=\"margin-right: 5px;\" onclick=\"javascript:expandcollapsepanel(this ,'tasklisting" . $value->id . "', 'mainsingnimg" . $value->id . "'," . $value->id . ")\"><i id=\"mainsingnimg" . $value->id . "\" class=\"ri-add-box-line\"></i></a>";
            $rollback = "<a title=\"Click here to see all approved records to rollback.\" class=\"icon_title2\" style=\"margin-right: 5px;\" onclick=\"javascript:expandcollapsepanel_rolback(this ,'tasklisting_rollback" . $value->id . "', 'mainsingnimg_rollback" . $value->id . "'," . $value->id . ")\"><i id=\"mainsingnimg_rollback" . $value->id . "\" class=\"ri-history-line\"></i></a>";
        } else {
            $update = '';
            $rollback = '';
        }
        if (Auth::user()->can('online-polling-edit')) {
            $title = '<div class="quick_edit"><a class="text-uppercase" title="' . trans("polls::template.common.edit") . '" href="' . route('powerpanel.polls.edit', array('alias' => $value->id)) . '">' . $value->varTitle . '</a></div>';
        } else {
            $title = stripslashes($value->varTitle);
        }
        $orderArrow = '';

        $dispOrder = $value->intDisplayOrder;
        if (($value->intDisplayOrder == $totalRecord || $value->intDisplayOrder < $totalRecord) && $value->intDisplayOrder > 1) {
            $orderArrow .= ' <a href="javascript:void(0);" data-order="' . $value->intDisplayOrder . '" class="moveUp"> <i class="ri-arrow-up-line" aria-hidden="true"></i> </a>';
        }
        $orderArrow .= $dispOrder;
        if (($value->intDisplayOrder != $totalRecord || $value->intDisplayOrder < $totalRecord)) {
            $orderArrow .= ' <a href="javascript:void(0);" data-order="' . $value->intDisplayOrder . '" class="moveDwn"> <i class="ri-arrow-down-line" aria-hidden="true"></i> </a>';
        }

        $startDate = date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($value->dtDateTime));
        $endDate = !empty($value->dtEndDateTime) ? date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($value->dtEndDateTime)) : 'No Expiry';
        if ($actions == "") {
            $actions = "---";
        } else {
            $actions = $actions;
        }
        if ($publish_action == "") {
            $publish_action = "---";
        } else {
            $publish_action = $publish_action;
        }

        if (Config::get('Constant.DEFAULT_FAVORITE') == 'Y') {
            $Favorite_array = explode(",", $value->FavoriteID);
            if (in_array(auth()->user()->id, $Favorite_array)) {
                $Class = 'ri-star-fill';
                $Favorite = '<a class="star_icon_div" href="javascript:void(0);" onclick="GetFavorite(' . $value->id . ',\'N\',\'P\')"><i class="' . $Class . '"></i></a>';
            } else {
                $Class = 'ri-star-line';
                $Favorite = '<a class="star_icon_div" href="javascript:void(0);" onclick="GetFavorite(' . $value->id . ',\'Y\',\'P\')"><i class="' . $Class . '"></i></a>';
            }
        } else {
            $Favorite = '';
        }
        $First_td = '<div class="star_box">' . $Favorite . '</div>';

        $records = array(
            '<input type="checkbox" name="delete" class="chkDelete form-check-input" value="' . $value->id . '">',
            $First_td,
            '<div class="pages_title_div_row">' . $update . $rollback . $title . ' ' . $status . $statusdata . '</div>',
            $value->intAudienceLimit,
            $startDate,
            $endDate,
            $actions
        );
        return $records;

    }

    public function tableData_tab1($value, $totalRecord) {

        $actions = '';
        $titleData = "";
        $publish_action = '';
        $checkbox = '<a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-toggle="tooltip" data-bs-content="' . $titleData . '" title="' . $titleData . '"><i style="color:red" class="ri-alert-fill"></i></a>';

        if (Auth::user()->can('online-polling-edit')) {
            $actions .= '<a class="" title="' . trans("polls::template.common.edit") . '" href="' . route('powerpanel.polls.edit', array('alias' => $value->id)) . '">
				<i class="ri-pencil-line"></i></a>';
        }

        if (Auth::user()->can('online-polling-delete') && $this->currentUserRoleData->chrIsAdmin == 'Y') {
            if (Config::get('Constant.DEFAULT_TRASH') == 'Y') {
                $actions .= '<a class="delete-grid" title="' . trans("polls::template.common.delete") . '" onclick = \'Trashfun("' . $value->id . '")\' data-controller="PollsController" data-alias = "' . $value->id . '" data-tab="P"><i class="ri-delete-bin-line"></i></a>';
            } else {
                $actions .= '<a class=" delete" title="' . trans("polls::template.common.delete") . '" data-controller="PollsController" data-alias = "' . $value->id . '" data-tab="P"><i class="ri-delete-bin-line"></i></a>';
            }
        }

        if (Auth::user()->can('log-list')) {
            $logurl = url('powerpanel/log?id=' . $value->id . '&mid=' . Config::get('Constant.MODULE.ID'));
            $actions .= "<a title=\"Log History\" class='log-grid' href=\"$logurl\"><i class=\"ri-time-line\"></i></a>";
        }

        if(File::exists(base_path() . '/packages/Powerpanel/Workflow/src/Models/Workflow.php')) {
            if($this->currentUserRoleData->chrIsAdmin == 'Y' && count($value->child) > 1) {
                $actions .= "<a title='Rollback to previous version'  onclick=\"rollbackToPreviousVersion('" . $value->id . "');\"  class=\"log-grid\"><i class=\"ri-history-line\"></i></a>";
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
        if ($value->chrDraft == 'D') {
            $status .= Config::get('Constant.DRAFT_LIST') . ' ';
        }
       
        if (Auth::user()->can('online-polling-reviewchanges')) {
            $update = "<a title=\"Click here to see all approval records.\" class=\"icon_title1\" style=\"margin-right: 5px;\" onclick=\"javascript:expandcollapsepanel(this ,'tasklisting" . $value->id . "', 'mainsingnimg" . $value->id . "'," . $value->id . ")\"><i id=\"mainsingnimg" . $value->id . "\" class=\"ri-add-box-line\"></i></a>";
            $rollback = "<a title=\"Click here to see all approved records to rollback.\" class=\"icon_title2\" style=\"margin-right: 5px;\" onclick=\"javascript:expandcollapsepanel_rolback(this ,'tasklisting_rollback" . $value->id . "', 'mainsingnimg_rollback" . $value->id . "'," . $value->id . ")\"><i id=\"mainsingnimg_rollback" . $value->id . "\" class=\"ri-history-line\"></i></a>";
        } else {
            $update = '';
            $rollback = '';
        }

        if (Auth::user()->can('online-polling-edit')) {
            $title = '<div class="quick_edit"><a class="text-uppercase" title="' . trans("polls::template.common.edit") . '" href="' . route('powerpanel.polls.edit', array('alias' => $value->id)) . '">' . $value->varTitle . '</a></div>';
        } else {
            $title = stripslashes($value->varTitle);
        }

      
        $startDate = date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($value->dtDateTime));
        $endDate = !empty($value->dtEndDateTime) ? date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($value->dtEndDateTime)) : 'No Expiry';
        if ($actions == "") {
            $actions = "---";
        } else {
            $actions = $actions;
        }

        if (Config::get('Constant.DEFAULT_FAVORITE') == 'Y') {
            $Favorite_array = explode(",", $value->FavoriteID);
            if (in_array(auth()->user()->id, $Favorite_array)) {
                $Class = 'ri-star-fill';
                $Favorite = '<a class="star_icon_div" href="javascript:void(0);" onclick="GetFavorite(' . $value->id . ',\'N\',\'P\')"><i class="' . $Class . '"></i></a>';
            } else {
                $Class = 'ri-star-line';
                $Favorite = '<a class="star_icon_div" href="javascript:void(0);" onclick="GetFavorite(' . $value->id . ',\'Y\',\'P\')"><i class="' . $Class . '"></i></a>';
            }
        } else {
            $Favorite = '';
        }
        $First_td = '<div class="star_box">' . $Favorite . '</div>';

        $records = array(
            $First_td,
            '<div class="pages_title_div_row">' . $update . $rollback . $title . ' ' . $status . $statusdata . '</div>',
            $value->intAudienceLimit,
            $startDate,
            $endDate,
            $actions
        );

        return $records;
        
    }

    public function tableDataDraft($value, $totalRecord = false, $tableSortedType = 'asc') {

        $actions = '';
        $titleData = "";
        $publish_action = '';
        $checkbox = '<a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-toggle="tooltip" data-bs-content="' . $titleData . '" title="' . $titleData . '"><i style="color:red" class="ri-alert-fill"></i></a>';
        if (Auth::user()->can('online-polling-edit')) {
            $actions .= '<a class="" title="' . trans("polls::template.common.edit") . '" href="' . route('powerpanel.polls.edit', array('alias' => $value->id)) . '">
				<i class="ri-pencil-line"></i></a>';
        }
        if (Auth::user()->can('online-polling-delete') && $this->currentUserRoleData->chrIsAdmin == 'Y') {

            // $actions .= '<a class=" delete" title="' . trans("polls::template.common.delete") . '" data-controller="onlinepolling" data-alias = "' . $value->id . '"><i class="ri-time-line"></i></a>';
            if (Config::get('Constant.DEFAULT_TRASH') == 'Y') {
                $actions .= '<a class="delete-grid" title="' . trans("polls::template.common.delete") . '" onclick = \'Trashfun("' . $value->id . '")\' data-controller="PollsController" data-alias = "' . $value->id . '" data-tab="P"><i class="ri-delete-bin-line"></i></a>';
            } else {
                $actions .= '<a class=" delete" title="' . trans("polls::template.common.delete") . '" data-controller="PollsController" data-alias = "' . $value->id . '" data-tab="P"><i class="ri-delete-bin-line"></i></a>';
            }

        }

        if (Auth::user()->can('log-list')) {
            $logurl = url('powerpanel/log?id=' . $value->id . '&mid=' . Config::get('Constant.MODULE.ID'));
            $actions .= "<a title=\"Log History\" class='log-grid' href=\"$logurl\"><i class=\"ri-time-line\"></i></a>";
        }

        if ($value->chrAddStar != 'Y') {
            if (Auth::user()->can('online-polling-publish')) {
                if ($value->chrPublish == 'Y') {
                    //Bootstrap Switch
                    $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/online-polling', 'data_alias'=>$value->id, 'title'=>trans("polls::template.common.publishedRecord"), 'data_value'=>'Unpublish'])->render();
                } else {
                    //Bootstrap Switch
                    $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/online-polling', 'data_alias'=>$value->id, 'title'=>trans("polls::template.common.unpublishedRecord"), 'data_value'=>'Publish', 'checked'=>'checked'])->render();
                }
            }
        } else {
            $publish_action .= '---';
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

        if (Auth::user()->can('online-polling-reviewchanges')) {
            $update = "<a title=\"Click here to see all approval records.\" class=\"icon_title1\" style=\"margin-right: 5px;\" onclick=\"javascript:expandcollapsepanel(this ,'tasklisting" . $value->id . "', 'mainsingnimg" . $value->id . "'," . $value->id . ")\"><i id=\"mainsingnimg" . $value->id . "\" class=\"ri-add-box-line\"></i></a>";
            $rollback = "<a title=\"Click here to see all approved records to rollback.\" class=\"icon_title2\" style=\"margin-right: 5px;\" onclick=\"javascript:expandcollapsepanel_rolback(this ,'tasklisting_rollback" . $value->id . "', 'mainsingnimg_rollback" . $value->id . "'," . $value->id . ")\"><i id=\"mainsingnimg_rollback" . $value->id . "\" class=\"ri-history-line\"></i></a>";
        } else {
            $update = '';
            $rollback = '';
        }
        if (Auth::user()->can('online-polling-edit')) {
            $title = '<div class="quick_edit"><a class="text-uppercase" title="' . trans("polls::template.common.edit") . '" href="' . route('powerpanel.polls.edit', array('alias' => $value->id)) . '">' . $value->varTitle . '</a></div>';
        } else {
            $title = stripslashes($value->varTitle);
        }
        $orderArrow = '';

        $dispOrder = $value->intDisplayOrder;
        if (($value->intDisplayOrder == $totalRecord || $value->intDisplayOrder < $totalRecord) && $value->intDisplayOrder > 1) {
            $orderArrow .= ' <a href="javascript:void(0);" data-order="' . $value->intDisplayOrder . '" class="moveUp"> <i class="ri-arrow-up-line" aria-hidden="true"></i> </a>';
        }
        $orderArrow .= $dispOrder;
        if (($value->intDisplayOrder != $totalRecord || $value->intDisplayOrder < $totalRecord)) {
            $orderArrow .= ' <a href="javascript:void(0);" data-order="' . $value->intDisplayOrder . '" class="moveDwn"> <i class="ri-arrow-down-line" aria-hidden="true"></i> </a>';
        }

        $startDate = date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($value->dtDateTime));
        $endDate = !empty($value->dtEndDateTime) ? date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($value->dtEndDateTime)) : 'No Expiry';
        if ($actions == "") {
            $actions = "---";
        } else {
            $actions = $actions;
        }
        if ($publish_action == "") {
            $publish_action = "---";
        } else {
            $publish_action = $publish_action;
        }
        
        $records = array(
            '<input type="checkbox" name="delete" class="chkDelete form-check-input" value="' . $value->id . '">',
            '<div class="pages_title_div_row">' . $update . $rollback . $title . ' ' . $status . $statusdata . '</div>',
            $value->intAudienceLimit,
            $startDate,
            $endDate,
            $publish_action,
            $actions
        );
        return $records;
    }

    public function tableDataTrash($value, $totalRecord = false, $tableSortedType = 'asc') {

        $actions = '';
        $titleData = "";
        
        $checkbox = '<a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-toggle="tooltip" data-bs-content="' . $titleData . '" title="' . $titleData . '"><i style="color:red" class="ri-alert-fill"></i></a>';

        if (Auth::user()->can('online-polling-reviewchanges')) {
            $update = "<a title=\"Click here to see all approval records.\" class=\"icon_title1\" style=\"margin-right: 5px;\" onclick=\"javascript:expandcollapsepanel(this ,'tasklisting" . $value->id . "', 'mainsingnimg" . $value->id . "'," . $value->id . ")\"><i id=\"mainsingnimg" . $value->id . "\" class=\"ri-add-box-line\"></i></a>";
            $rollback = "<a title=\"Click here to see all approved records to rollback.\" class=\"icon_title2\" style=\"margin-right: 5px;\" onclick=\"javascript:expandcollapsepanel_rolback(this ,'tasklisting_rollback" . $value->id . "', 'mainsingnimg_rollback" . $value->id . "'," . $value->id . ")\"><i id=\"mainsingnimg_rollback" . $value->id . "\" class=\"ri-history-line\"></i></a>";
        } else {
            $update = '';
            $rollback = '';
        }

        if (Auth::user()->can('online-polling-edit')) {
            $title = '<div class="quick_edit text-uppercase"><a href = "' . route('powerpanel.polls.edit', array('alias' => $value->id)) . '?tab=T">' . $value->varTitle . '</a>
            </div>';
        }

        $startDate = date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($value->dtDateTime));
        $endDate = !empty($value->dtEndDateTime) ? date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($value->dtEndDateTime)) : 'No Expiry';
        
        if (Auth::user()->can('online-polling-delete') && $this->currentUserRoleData->chrIsAdmin == 'Y') {
            $actions .= '<a class=" delete" title="' . trans("polls::template.common.delete") . '" data-controller="PollsController" data-alias = "' . $value->id . '" data-tab="T"><i class="ri-delete-bin-line"></i></a>';
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
        if ($value->chrDraft == 'D') {
            $status .= Config::get('Constant.DRAFT_LIST') . ' ';
        }
        if ($value->chrAddStar == 'Y') {
            $status .= Config::get('Constant.APPROVAL_LIST') . ' ';
        }

        if ($actions == "") {
            $actions = "---";
        } else {
            $actions = $actions;
        }

        $logurl = url('powerpanel/log?id=' . $value->id . '&mid=' . Config::get('Constant.MODULE.ID'));
        $log = '';
        if ($value->chrLock != 'Y') {
            if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                if (Config::get('Constant.DEFAULT_TRASH') == 'Y') {
                    $log .= "<a title=\"Restore\" href='javascript:void(0);' onclick='Restorefun(\"$value->id\",\"T\")'><i class=\"ri-repeat-line\"></i></a>";
                }
                $log .= $actions;
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
                if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                    $log .= '<a class="star_lock" onclick="GetUnLockData(' . $value->id . ',' . auth()->user()->id . ',' . Config::get('Constant.MODULE.ID') . ',1)" title="This record has been locked by ' . $lockedUserName . ', Click here to unlock."><i class="ri-lock-2-line"></i></a>';
                } else {

                    $log .= '<a class="star_lock" title="This record has been locked by ' . $lockedUserName . '."><i class="ri-lock-2-line"></i></a>';
                }
            } else {
                $log .= '<a class="star_lock" onclick="GetUnLockData(' . $value->id . ',' . auth()->user()->id . ',' . Config::get('Constant.MODULE.ID') . ',1)" title="Click here to unlock."><i class="ri-lock-2-line"></i></a>';
            }
        }
    
        $records = array(
            '<input type="checkbox" name="delete" class="chkDelete form-check-input" value="' . $value->id . '">',
            '<div class="pages_title_div_row">' . $update . $rollback . $title . ' ' . $status . $statusdata . '</div>',
            $value->intAudienceLimit,
            $startDate,
            $endDate,
            $log
        );
        return $records;

    }

    public function rollBackRecord(Request $request) {
        

        $message = 'Previous record is not available';
        $requestArr = Request::all();
        $request = (object) $requestArr;

        $previousRecord = Poll::getPreviousRecordByMainId($request->id);
        if (!empty($previousRecord)) {

            $main_id = $previousRecord->fkMainRecord;
            $request->id = $previousRecord->id;
            $request->main_id = $main_id;

            $message = Poll::approved_data_Listing($request);

            /* notification for user to record approved */
            $blogs = Poll::getRecordForLogById($previousRecord->id);
            if(!empty($blogs))
            {
                if (method_exists($this->MyLibrary, 'userNotificationData')) {
                    $userNotificationArr = MyLibrary::userNotificationData(Config::get('Constant.MODULE.ID'));
                    $userNotificationArr['fkRecordId'] = $previousRecord->id;
                    $userNotificationArr['txtNotification'] = 'Your request has been approved by ' . ucfirst(auth()->user()->name) . ' (' . ucfirst(Config::get('Constant.MODULE.NAME')) . ')';
                    $userNotificationArr['fkIntUserId'] = Auth::user()->id;
                    $userNotificationArr['chrNotificationType'] = 'A';
                    $userNotificationArr['intOnlyForUserId'] = $blogs->UserID;
                    UserNotification::addRecord($userNotificationArr);
                }
            }
            /* notification for user to record approved */
            $newBlogObj = Poll::getRecordForLogById($main_id);
            $restoredata = Config::get('Constant.ROLLBACK_RECORD');
            if(!empty($newBlogObj)) {
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
        }
        echo $message;
    }

    /**
     * This method handels logs History records
     * @param   $data
     * @return  HTML
     * @since   2017-07-21
     * @author  NetQuick
     */
    public function recordHistory($data = false)
    {
        $startDate = date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($data->dtDateTime));
        $endDate = !empty($data->dtEndDateTime) ? date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($data->dtEndDateTime)) : 'No Expiry';
        $returnHtml = '';

        $returnHtml .= '<table class="new_table_desing table table-striped table-bordered table-hover">
				<thead>
					<tr>
					<th align="center">' . trans('polls::template.common.title') . '</th>
                    <th align="center">Start date</th>
                    <th align="center">End date</th>
					<th align="center">' . trans('polls::template.common.displayorder') . '</th>
					<th align="center">' . trans("polls::template.common.publish") . '</th>
					</tr>
				</thead>
				<tbody>
					<tr>
					<td align="center">' . stripslashes($data->varTitle) . '</td>
					<td align="center">' . $startDate . '</td>
					<td align="center">' . $endDate . '</td>
					<td align="center">' . stripslashes($data->intDisplayOrder) . '</td>
					<td align="center">' . $data->chrPublish . '</td>
					</tr>
				</tbody>
			</table>';
        return $returnHtml;
    }

    public function newrecordHistory($data = false, $newdata = false)
    {
        $startDate = date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($newdata->dtDateTime));
        $endDate = !empty($newdata->dtEndDateTime) ? date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($newdata->dtEndDateTime)) : 'No Expiry';
        if ($data->varTitle != $newdata->varTitle) {
            $titlecolor = 'style="background-color:#f5efb7"';
        } else {
            $titlecolor = '';
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

        $returnHtml = '';
        $returnHtml .= '<table class="new_table_desing table table-striped table-bordered table-hover">
				<thead>
					<tr>
					<th align="center">' . trans('polls::template.common.title') . '</th>
                    <th align="center">Start date</th>
                    <th align="center">End date</th>
					<th align="center">' . trans('polls::template.common.displayorder') . '</th>
					<th align="center">' . trans("polls::template.common.publish") . '</th>
					</tr>
				</thead>
				<tbody>
					<tr>
					<td align="center" ' . $titlecolor . '>' . stripslashes($newdata->varTitle) . '</td>
					<td align="center" ' . $DateTimecolor . '>' . $startDate . '</td>
					<td align="center" ' . $EndDateTimecolor . '>' . $endDate . '</td>
					<td align="center" ' . $DisplayOrdercolor . '>' . stripslashes($newdata->intDisplayOrder) . '</td>
					<td align="center" ' . $Publishcolor . '>' . $newdata->chrPublish . '</td>
					</tr>
				</tbody>
			</table>';
        return $returnHtml;
    }

    public static function flushCache()
    {
        Cache::tags('onlinepolling')->flush();
    }

    public function getChildData()
    {
        $childHtml = "";
        $Cmspage_childData = "";
        $Cmspage_childData = Poll::getChildGrid();
        $childHtml .= "<div class=\"producttbl\" style=\"\">";
        $childHtml .= "<table class=\"new_table_desing table table-striped table-bordered table-hover table-checkable dataTable\" id=\"email_log_datatable_ajax\">
						    <tr role=\"row\">
								<th class=\"text-center\"></th>
								<th class=\"text-center\">Title</th>
								<th class=\"text-center\">Date Submitted</th>
								<th class=\"text-center\">User</th>
								<th class=\"text-center\">Edit</th>
							    <th class=\"text-center\">Status</th>";
        $childHtml .= "     </tr>";
        if (count($Cmspage_childData) > 0) {
            foreach ($Cmspage_childData as $child_row) {
                $parentAlias = '';
                $childHtml .= "<tr role=\"row\">";
                if ($child_row->chrApproved == 'N') {
                    $childHtml .= "<td><span class='mob_show_title'>&nbsp</span><input type=\"checkbox\" name=\"delete\" class=\"chkDelete form-check-input\" value='" . $child_row->id . "'></td>";
                } else {
                    $childHtml .= "<td><span class='mob_show_title'>&nbsp</span><div class=\"checker\"><a href=\"javascript:void(0);\" data-toggle=\"tooltip\" data-placement=\"right\" title=\"This is approved record, so can't be deleted.\"><i style=\"color:red\" class=\"ri-alert-fill\"></i></a></div></td>";
                }
                $childHtml .= '<td class="text-center"><span class="mob_show_title">Title: </span>' . $child_row->varTitle . '</td>';
                $childHtml .= "<td class=\"text-center\"><span class='mob_show_title'>Date Submitted: </span>" . date('M d Y h:i A', strtotime($child_row->created_at)) . "</td>";
                $childHtml .= "<td class=\"text-center\"><span class='mob_show_title'>User: </span>" . CommonModel::getUserName($child_row->UserID) . "</td>";
                if ($child_row->chrApproved == 'N') {
                    $childHtml .= "<td class=\"text-center\"><span class='mob_show_title'>Edit: </span><a class='icon_round' title='" . trans("polls::template.common.edit") . "' href='" . route('powerpanel.polls.edit', array('alias' => $child_row->id)) . "'>
							<i class='ri-pencil-line'></i></a></td>";
                } else {
                    $childHtml .= "<td class=\"text-center\"><span class='mob_show_title'>Edit: </span>-</td>";
                }
                if ($child_row->chrApproved == 'N') {
                    $childHtml .= "<td class=\"text-center\"><span class='mob_show_title'>Status: </span><a class=\"approve_icon_btn\" title='" . trans("polls::template.common.comments") . "' href=\"javascript:void(0);\" onclick=\"loadModelpopup('" . $child_row->id . "','" . $child_row->UserID . "','" . Config::get('Constant.MODULE.MODEL_NAME') . "','" . $child_row->fkMainRecord . "')\"><i class=\"ri-chat-1-line\"></i> <span>Comment</span></a>    <a  onclick=\"update_mainrecord('" . $child_row->id . "','" . $child_row->fkMainRecord . "','" . $child_row->UserID . "','A');\" title='" . trans("polls::template.common.clickapprove") . "' class=\"approve_icon_btn\" href=\"javascript:void(0);\"><i class=\"ri-checkbox-line\"></i>  <span>Approve</span></a></td>";
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

    public function getChildData_rollback()
    {
        $child_rollbackHtml = "";
        $Cmspage_rollbackchildData = "";
        $Cmspage_rollbackchildData = Poll::getChildrollbackGrid();
        $child_rollbackHtml .= "<div class=\"producttbl producttb2\" style=\"\">";
        $child_rollbackHtml .= "<table class=\"new_table_desing table table-striped table-bordered table-hover table-checkable dataTable\" id=\"email_log_datatable_ajax\">
                                    <tr role=\"row\">
                                        <th class=\"text-center\">Title</th>
                                        <th class=\"text-center\">Date</th>
                                        <th class=\"text-center\">User</th>
                                        <th class=\"text-center\">Status</th>";
        $child_rollbackHtml .= "    </tr>";
        if (count($Cmspage_rollbackchildData) > 0) {
            foreach ($Cmspage_rollbackchildData as $child_rollbacrow) {
                $child_rollbackHtml .= "<tr role=\"row\">";
                $child_rollbackHtml .= '<td class="text-center">' . $child_rollbacrow->varTitle . '</td>';
                $child_rollbackHtml .= "<td class=\"text-center\">" . date('M d Y h:i A', strtotime($child_rollbacrow->created_at)) . "</td>";
                $child_rollbackHtml .= "<td class=\"text-center\">" . CommonModel::getUserName($child_rollbacrow->UserID) . "</td>";
                if ($child_rollbacrow->chrApproved == 'Y') {
                    $child_rollbackHtml .= "<td class=\"text-center\"><i class=\"ri-checkbox-circle-line\" style=\"color: #1080F2;font-size:30px;\"></i></td>";
                } else {
                    $child_rollbackHtml .= "<td class=\"text-center\"><a onclick=\"update_mainrecord('" . $child_rollbacrow->id . "','" . $child_rollbacrow->fkMainRecord . "','" . $child_rollbacrow->UserID . "','R');\"  class=\"approve_icon_btn\">
											<i class=\"ri-history-line\"></i>  <span>RollBack</span>
										</a></td>";
                }
                $child_rollbackHtml .= "</tr>";
            }
        } else {
            $child_rollbackHtml .= "<tr><td colspan='6'>No Records</td></tr>";
        }
        echo $child_rollbackHtml;
        exit;
    }

    public function insertComents(Request $request)
    {
        $Comments_data['intRecordID'] = $request->post('id');
        $Comments_data['varModuleNameSpace'] = $request->post('namespace');
        $Comments_data['varCmsPageComments'] = stripslashes($request->post('CmsPageComments'));
        $Comments_data['UserID'] = $request->post('UserID');
        $Comments_data['intCommentBy'] = auth()->user()->id;
        $Comments_data['varModuleTitle'] = Config::get('Constant.MODULE.TITLE');
        $Comments_data['fkMainRecord'] = $request->post('fkMainRecord');
        Comments::insertComents($Comments_data);
        exit;
    }

    public function ApprovedData_Listing(Request $request)
    {
        $requestArr = Request::all();
        $request = (object) $requestArr;
        $id = Request::post('id');
        $main_id = Request::post('main_id');
        $approvalid = Request::post('id');
        $flag = Request::post('flag');
        $approvalData = Poll::getOrderOfApproval($id);
        $message = Poll::approved_data_Listing($request);
        $newCmsPageObj = Poll::getRecordForLogById($main_id);
        $approval_obj = Poll::getRecordForLogById($approvalid);
        if ($flag == 'R') {
            $restoredata = Config::get('Constant.ROLLBACK_RECORD');
        } else {
            if ($approval_obj->chrDraft == 'D') {
                $restoredata = Config::get('Constant.DRAFT_RECORD_APPROVED');
            } else {
                $restoredata = Config::get('Constant.RECORD_APPROVED');
            }
        }
        if (!empty($approvalData)) {
            self::swap_order_edit($approvalData->intDisplayOrder, $main_id);
        }
        /* notification for user to record approved */
        $userNotificationArr = MyLibrary::userNotificationData(Config::get('Constant.MODULE.ID'));
        $userNotificationArr['fkRecordId'] = $approvalid;
        $userNotificationArr['txtNotification'] = 'Your request has been approved by ' . ucfirst(auth()->user()->name) . ' (' . ucfirst(Config::get('Constant.MODULE.NAME')) . ')';
        $userNotificationArr['fkIntUserId'] = Auth::user()->id;
        $userNotificationArr['chrNotificationType'] = 'A';
        $userNotificationArr['intOnlyForUserId'] = $approval_obj->UserID;
        UserNotification::addRecord($userNotificationArr);
        /* notification for user to record approved */
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

    public function Get_Comments(Request $request)
    {
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
}
