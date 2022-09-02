<?php

namespace Powerpanel\Interconnections\Controllers\Powerpanel;

use App\CommonModel;
use App\Helpers\AddCategoryAjax;
use App\Helpers\AddDocumentModelRel;
use App\Helpers\MyLibrary;
use App\Helpers\ParentRecordHierarchy_builder;
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
use File;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Powerpanel\CmsPage\Models\CmsPage;
use Powerpanel\Interconnections\Models\Interconnections;
use Powerpanel\RoleManager\Models\Role_user;
use Powerpanel\Workflow\Models\Comments;
use Powerpanel\Workflow\Models\Workflow;
use Powerpanel\Workflow\Models\WorkflowLog;
use Validator;

class InterconnectionsController extends PowerpanelController
{

    public function __construct()
    {
        parent::__construct();
        if (isset($_COOKIE['locale'])) {
            app()->setLocale($_COOKIE['locale']);
        }
        $this->MyLibrary = new MyLibrary();
        $this->CommonModel = new CommonModel();
    }

    /**
     * This method handels load process of Interconnections
     * @return  View
     * @since   2017-11-10
     * @author  NetQuick
     */
    public function index()
    {
        $userIsAdmin = false;
        if (isset($this->currentUserRoleData) && !empty($this->currentUserRoleData)) {
            if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                $userIsAdmin = true;
            }
        }
        $iTotalRecords = Interconnections::getRecordCount(false, false, $userIsAdmin, $this->currentUserRoleSector);
        $NewRecordsCount = Interconnections::getNewRecordsCount($userIsAdmin, $this->currentUserRoleSector);
        $draftTotalRecords = Interconnections::getRecordCountforListDarft(false, true, $userIsAdmin, array(), $this->currentUserRoleSector);
        $trashTotalRecords = Interconnections::getRecordCountforListTrash(false, false, $userIsAdmin, [], $this->currentUserRoleSector);
        $favoriteTotalRecords = Interconnections::getRecordCountforListFavorite(false, false, $userIsAdmin, [], $this->currentUserRoleSector);
        $this->breadcrumb['title'] = trans('interconnections::template.interconnectionsModule.manageInterconnections');
        $breadcrumb = $this->breadcrumb;
        /* code for getting chart for parent categories */
        $interconnectionsData = Interconnections::getRecordsForChart();
        $intdata = array();
        if (!empty($interconnectionsData) && count($interconnectionsData) > 0) {
            foreach ($interconnectionsData as $interconnections) {
                $inData = array();
                $tempData = array();
                $tempData['v'] = (String) $interconnections->id;
                $tempData['f'] = $interconnections->varTitle;
                $inData[] = $tempData;
                if ($interconnections->intParentCategoryId > 0) {
                    array_push($inData, (String) $interconnections->intParentCategoryId);
                } else {
                    array_push($inData, null);
                }
                array_push($inData, $interconnections->varTitle);
                $intdata[] = $inData;
            }
        }
        $intdata = json_encode($intdata);
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

        $ParentsRecords = Interconnections::getParentRecordList(false,false);
        return view('interconnections::powerpanel.index', compact('NewRecordsCount', 'trashTotalRecords', 'favoriteTotalRecords', 'draftTotalRecords', 'iTotalRecords', 'breadcrumb', 'userIsAdmin', 'intdata', 'settingarray','ParentsRecords'));
    }

   public static function getSectorwiseCategoryGrid() {
        $data = Request::input();
        if (isset($data['sectorname']) && !empty($data['sectorname'])) {
            $sectorname = $data['sectorname'];
        }
        else{
        $sectorname = '';
        }
        if (isset($sectorname) && !empty($sectorname)) {
            $ParentsRecords = Interconnections::getParentRecordListGrid(false, $sectorname);
        } else {
            $ParentsRecords = Interconnections::getParentRecordListGrid(false, false);
        }
        $recordSelect = '<option value=" ">--Select Category--</option>';

        foreach ($ParentsRecords as $cat) {

            $recordSelect .= '<option  value="' . $cat->id . '">' . ucwords($cat->varTitle) . '</option>';
        }
        return $recordSelect;
    }
    
    /**
     * This method loads Interconnections edit view
     * @param   Alias of record
     * @return  View
     * @since   2021-2-19
     * @author  NetQuick
     */
    public function edit($alias = false)
    {
        $isParent = 0;
         $hasRecords = 0;
        $userIsAdmin = false;
        if (isset($this->currentUserRoleData) && !empty($this->currentUserRoleData)) {
            if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                $userIsAdmin = true;
            }
        }

        if (!is_numeric($alias)) {
            $categories = ParentRecordHierarchy_builder::Parentrecordhierarchy();
            $total = Interconnections::getRecordCounter();
            $total = $total + 1;
            $this->breadcrumb['title'] = trans('interconnections::template.interconnectionsModule.addInterconnections');
            $this->breadcrumb['module'] = trans('interconnections::template.interconnectionsModule.manageInterconnections');
            $this->breadcrumb['url'] = 'powerpanel/interconnections';
            $this->breadcrumb['inner_title'] = trans('interconnections::template.interconnectionsModule.addInterconnections');
            $breadcrumb = $this->breadcrumb;
            $data = compact('total', 'breadcrumb', 'categories', 'isParent', 'hasRecords','userIsAdmin');
        } else {
            $id = $alias;
            $interconnections = Interconnections::getRecordById($id);
            if (empty($interconnections)) {
                return redirect()->route('powerpanel.interconnections.add');
            }
            $isParent = Interconnections::getCountById($interconnections->id);
            $categories = ParentRecordHierarchy_builder::Parentrecordhierarchy($interconnections->intParentCategoryId, $interconnections->id, 'Powerpanel\Interconnections\Models\Interconnections');
            $this->breadcrumb['title'] = trans('interconnections::template.common.edit') . ' - ' . $interconnections->varTitle;
            $this->breadcrumb['module'] = trans('interconnections::template.interconnectionsModule.manageInterconnections');
            $this->breadcrumb['url'] = 'powerpanel/interconnections';
            $this->breadcrumb['inner_title'] = trans('interconnections::template.common.edit') . ' - ' . $interconnections->varTitle;
            $breadcrumb = $this->breadcrumb;
            if ((int) $interconnections->fkMainRecord !== 0) {
                $interconnectionsHighLight = Interconnections::getRecordById($interconnections->fkMainRecord);
            $hasRecords = Interconnections::getCountById($interconnections->fkMainRecord);
            } else {
                $interconnectionsHighLight = "";
            $hasRecords = Interconnections::getCountById($interconnections->id);
            }
            $data = compact('interconnectionsHighLight', 'categories', 'isParent','hasRecords', 'interconnections', 'breadcrumb', 'userIsAdmin');
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
        } else {
            $data['chrNeedAddPermission'] = 'N';
            $data['charNeedApproval'] = 'N';
        }
        $data['MyLibrary'] = $this->MyLibrary;
        //End Button Name Change For User Side
        return view('interconnections::powerpanel.actions', $data);
    }

    /**
     * This method stores Interconnections modifications
     * @return  View
     * @since   2017-11-10
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
        $data = Request::input();
//        echo '<pre>';print_r($data);exit;
        
        $rules = array(
            'title' => 'required|max:160|handle_xss|no_url',
            'sector' => 'required',
            'display_order' => 'required|greater_than_zero|handle_xss|no_url',
//            'chrMenuDisplay' => 'required',
        );
        $messsages = array(
            'title.required' => 'Title field is required.',
            'sector.required' => 'Sector field is required.',
            'display_order.required' => trans('interconnections::template.interconnectionsModule.displayOrder'),
            'display_order.greater_than_zero' => trans('interconnections::template.interconnectionsModule.displayGreaterThan'),
        );

        $validator = Validator::make($data, $rules, $messsages);
        if ($validator->passes()) {
            $InterconnectionsArr = [];
            $module = Modules::getModuleById(Config::get('Constant.MODULE.ID'));
            $id = Request::segment(3);
            $actionMessage = trans('interconnections::template.common.oppsSomethingWrong');
            if ($data['chrMenuDisplay'] == 'D') {
                $InterconnectionsArr['chrDraft'] = 'D';
                $InterconnectionsArr['chrPublish'] = 'N';
            } else {
                $InterconnectionsArr['chrDraft'] = 'N';
                $InterconnectionsArr['chrPublish'] = $data['chrMenuDisplay'];
            }
           
             $InterconnectionsArr['dtDateTime'] = !empty($data['start_date_time']) ? date('Y-m-d', strtotime(str_replace('/', '-',$data['start_date_time']))) : date('Y-m-d');
            if (is_numeric($id)) { #Edit post Handler=======
            $Interconnections = Interconnections::getRecordForLogById($id);
                if ($Interconnections->chrLock == 'Y' && auth()->user()->id != $Interconnections->LockUserID) {
                    if ($this->currentUserRoleData->chrIsAdmin != 'Y') {
                        $lockedUserData = User::getRecordById($Interconnections->LockUserID, true);
                        $lockedUserName = 'someone';
                        if (!empty($lockedUserData)) {
                            $lockedUserName = $lockedUserData->name;
                        }
                        $actionMessage = "This record has been locked by " . $lockedUserName . ".";
                        return redirect()->route('powerpanel.interconnections.index')->with('message', $actionMessage);
                    }
                }
                if (Config::get('Constant.CHRSearchRank') == 'Y') {
                }
                $updateInterconnectionsFields = [
                    'varSector' => $data['sector'],
                    'varTitle' => stripslashes(trim($data['title'])),
                    'intParentCategoryId' => $data['parent_category_id'],
                    'chrPublish' => isset($data['chrMenuDisplay']) ? $data['chrMenuDisplay'] : 'Y',
                    'fkIntDocId' => isset($data['doc_id']) ? $data['doc_id'] : '',
                    'txtShortDescription' => isset($data['txtShortDescription']) ? $data['txtShortDescription'] : '',
                    'dtDateTime' => !empty($data['start_date_time']) ? date('Y-m-d', strtotime(str_replace('/', '-',$data['start_date_time']))) : date('Y-m-d'),
                   
                ];
           
                if ($data['chrMenuDisplay'] == 'D') {
                    $updateInterconnectionsFields['chrDraft'] = 'D';
                    $updateInterconnectionsFields['chrPublish'] = 'N';
                } else {
                    $updateInterconnectionsFields['chrDraft'] = 'N';
                    $updateInterconnectionsFields['chrPublish'] = $data['chrMenuDisplay'];
                }
                $whereConditions = ['id' => $Interconnections->id];
                if (File::exists(app_path() . '/Workflow.php') != null || File::exists(base_path() . '/packages/Powerpanel/Workflow/src/Models/Workflow.php') != null) {
                    if (!$userIsAdmin) {
                        $userRole = $this->currentUserRoleData->id;
                    } else {
                        $userRoleData = Role_user::getUserRoleByUserId($Interconnections->UserID);
                        if (isset($userRoleData->role_id)) {
                            $userRole = $userRoleData->role_id;
                        } else {
                            $userRole = $this->currentUserRoleData->id;
                        }
                    }
                    $workFlowByCat = Workflow::getRecordByCategoryId($module->intFkGroupCode, $userRole, Config::get('Constant.MODULE.ID'));
                    if (empty($workFlowByCat->varUserId) || $userIsAdmin || $workFlowByCat->charNeedApproval == 'N') {

                        if ((int) $Interconnections->fkMainRecord === 0 || empty($workFlowByCat->varUserId)) {
                            $update = CommonModel::updateRecords($whereConditions, $updateInterconnectionsFields, false, 'Powerpanel\Interconnections\Models\Interconnections');
                            if ($update) {
                                if (!empty($id)) {
                                    $addlog = '';
                                    self::newSwapOrderEdit($data['display_order'], $Interconnections);
                                    $logArr = MyLibrary::logData($Interconnections->id, false, $addlog);
                                    if (Auth::user()->can('log-advanced')) {
                                        $newInterconnectionsObj = Interconnections::getRecordForLogById($Interconnections->id);
                                        $oldRec = $this->recordHistory($Interconnections);
                                        $newRec = $this->newrecordHistory($Interconnections, $newInterconnectionsObj);
                                        $logArr['old_val'] = $oldRec;
                                        $logArr['new_val'] = $newRec;
                                    }
                                    $logArr['varTitle'] = trim($data['title']);
                                    Log::recordLog($logArr);
                                    if (Auth::user()->can('recent-updates-list')) {
                                        if (!isset($newInterconnectionsObj)) {
                                            $newInterconnectionsObj = Interconnections::getRecordForLogById($Interconnections->id);
                                        }
                                        $notificationArr = MyLibrary::notificationData($Interconnections->id, $newInterconnectionsObj);
                                        RecentUpdates::setNotification($notificationArr);
                                    }
                                    if (isset($data['saveandexit']) && $data['saveandexit'] == 'approvesaveandexit') {
                                        $actionMessage = trans('interconnections::template.common.recordApprovalMessage');
                                    } else {
                                        $actionMessage = trans('interconnections::template.interconnectionsModule.successMessage');
                                    }
                                }
                            }
                        } else {
                            $updateModuleFields = $updateInterconnectionsFields;
                            $this->insertApprovedRecord($updateModuleFields, $data, $id);
                            if (isset($data['saveandexit']) && $data['saveandexit'] == 'approvesaveandexit') {
                                $actionMessage = trans('interconnections::template.common.recordApprovalMessage');
                            } else {
                                $actionMessage = trans('interconnections::template.interconnectionsModule.successMessage');
                            }
                        }
                    } else {
                        if ($workFlowByCat->charNeedApproval == 'Y') {
                            $this->insertApprovalRecord($Interconnections, $data);
                            if (isset($data['saveandexit']) && $data['saveandexit'] == 'approvesaveandexit') {
                                $actionMessage = trans('interconnections::template.common.recordApprovalMessage');
                            } else {
                                $actionMessage = trans('interconnections::template.interconnectionsModule.successMessage');
                            }
                        }
                    }
                } else {
                    $update = CommonModel::updateRecords($whereConditions, $updateInterconnectionsFields, false, 'Powerpanel\Interconnections\Models\Interconnections');
                    $actionMessage = trans('interconnections::template.interconnectionsModule.successMessage');
                }
            } else {
                #Add post Handler=======
                if (File::exists(app_path() . '/Workflow.php') != null || File::exists(base_path() . '/packages/Powerpanel/Workflow/src/Models/Workflow.php') != null) {
                    $workFlowByCat = Workflow::getRecordByCategoryId($module->intFkGroupCode, $this->currentUserRoleData->id, Config::get('Constant.MODULE.ID'));
                }
                $InterconnectionsArr['intParentCategoryId'] = $data['parent_category_id'];
                $InterconnectionsArr['varSector'] = $data['sector'];
                $InterconnectionsArr['fkIntDocId'] = isset($data['doc_id']) ? $data['doc_id'] : '';
                $InterconnectionsArr['txtShortDescription'] = isset($data['txtShortDescription']) ? $data['txtShortDescription'] : '';

                if (!empty($workFlowByCat->varUserId) && $workFlowByCat->chrNeedAddPermission == 'Y' && !$userIsAdmin) {
                    $InterconnectionsArr['chrPublish'] = 'N';
                    $InterconnectionsArr['chrDraft'] = 'N';
                    $Interconnections = $this->insertNewRecord($data, $InterconnectionsArr);
                    if ($data['chrMenuDisplay'] == 'D') {
                        $InterconnectionsArr['chrDraft'] = 'D';
                    }
                    $InterconnectionsArr['chrPublish'] = 'Y';
                    $this->insertApprovalRecord($Interconnections, $data, $InterconnectionsArr);
                    $approval = $Interconnections->id;
                } else {
                    $Interconnections = $this->insertNewRecord($data, $InterconnectionsArr);
                    $approval = $Interconnections->id;
                }
                $id = $Interconnections->id;
                AddDocumentModelRel::sync(explode(',', $data['doc_id']), $id, $approval);
                if (isset($data['saveandexit']) && $data['saveandexit'] == 'approvesaveandexit') {
                    $actionMessage = trans('interconnections::template.common.recordApprovalMessage');
                } else {
                    $actionMessage = trans('interconnections::template.interconnectionsModule.addedMessage');
                }
            }
            $this->flushCache();
            if ((!empty($data['saveandexit']) && $data['saveandexit'] == 'saveandexit') || !$userIsAdmin) {
                return redirect()->route('powerpanel.interconnections.index')->with('message', $actionMessage);
            } else {
                return redirect()->route('powerpanel.interconnections.edit', $id)->with('message', $actionMessage);
            }
        } else {
            return Redirect::back()->withErrors($validator)->withInput();
        }
    }

    public function insertApprovedRecord($updateModuleFields, $postArr, $id)
    {
        $Interconnections = Interconnections::getRecordById($postArr['fkMainRecord']);
        $whereConditions = ['id' => $postArr['fkMainRecord']];
        $updateModuleFields['chrAddStar'] = 'N';
        $update = CommonModel::updateRecords($whereConditions, $updateModuleFields, false, 'Powerpanel\Interconnections\Models\Interconnections');
        self::newSwapOrderEdit($postArr['display_order'], $Interconnections);
        $whereConditions_ApproveN = ['fkMainRecord' => $postArr['fkMainRecord']];
        $updateToApproveN = [
            'chrApproved' => 'N',
            'chrLetest' => 'N',
            'intApprovedBy' => '0',
        ];
        $update = CommonModel::updateRecords($whereConditions_ApproveN, $updateToApproveN, false, 'Powerpanel\Interconnections\Models\Interconnections');
        $whereConditionsApprove = ['id' => $id, 'chrMain' => 'N'];
        $updateToApprove = [
            'chrApproved' => 'Y',
            'chrRollBack' => 'Y',
            'intApprovedBy' => auth()->user()->id,
        ];
        $update = CommonModel::updateRecords($whereConditionsApprove, $updateToApprove, false, 'Powerpanel\Interconnections\Models\Interconnections');
        if ($postArr['chrMenuDisplay'] == 'D') {
            $addlog = Config::get('Constant.DRAFT_RECORD_APPROVED');
        } else {
            $addlog = Config::get('Constant.RECORD_APPROVED');
        }
        $newBannerObj = Interconnections::getRecordForLogById($id);
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
                $actionMessage = trans('Interconnections::template.InterconnectionsModule.successMessage');
            }
        }
    }

    public function insertApprovalRecord($Interconnections, $postArr)
    {
        $InterconnectionsArr = [];
        $InterconnectionsArr['UserID'] = auth()->user()->id;
        $InterconnectionsArr['chrMain'] = 'N';
        $InterconnectionsArr['chrLetest'] = 'Y';
        $InterconnectionsArr['fkMainRecord'] = $Interconnections->id;
        $InterconnectionsArr['varTitle'] = stripslashes(trim($postArr['title']));
        $InterconnectionsArr['intDisplayOrder'] = $postArr['display_order'];
        $InterconnectionsArr['dtDateTime'] = !empty($postArr['start_date_time']) ? date('Y-m-d', strtotime(str_replace('/', '-',$postArr['start_date_time']))) : date('Y-m-d');
        $InterconnectionsArr['intParentCategoryId'] = $postArr['parent_category_id'];
        $InterconnectionsArr['txtShortDescription'] = $postArr['txtShortDescription'];
        $InterconnectionsArr['varSector'] = $postArr['sector'];
        $InterconnectionsArr['fkIntDocId'] = isset($postArr['doc_id']) ? $postArr['doc_id'] : '';
        $InterconnectionsArr['chrPublish'] = isset($postArr['chrMenuDisplay']) ? $postArr['chrMenuDisplay'] : 'Y';
        $InterconnectionsArr['created_at'] = Carbon::now();
        $addlog = Config::get('Constant.SENT_FOR_APPROVAL');
        $InterconnectionsID = CommonModel::addRecord($InterconnectionsArr, 'Powerpanel\Interconnections\Models\Interconnections');
        if (!empty($InterconnectionsID)) {
            $id = $InterconnectionsID;
            WorkflowLog::addRecord([
                'fkModuleId' => Config::get('Constant.MODULE.ID'),
                'fkRecordId' => $Interconnections->id,
                'charApproval' => 'Y',
            ]);
            if (method_exists($this->MyLibrary, 'userNotificationData')) {
                $userNotificationArr = MyLibrary::userNotificationData(Config::get('Constant.MODULE.ID'));
                $userNotificationArr['fkRecordId'] = $Interconnections->id;
                $userNotificationArr['txtNotification'] = 'New approval request from ' . ucfirst(auth()->user()->name) . ' (' . ucfirst(Config::get('Constant.MODULE.NAME')) . ')';
                $userNotificationArr['fkIntUserId'] = Auth::user()->id;
                $userNotificationArr['chrNotificationType'] = 'A';
                UserNotification::addRecord($userNotificationArr);
            }
            $newInterconnectionsObj = Interconnections::getRecordForLogById($id);
            $logArr = MyLibrary::logData($id, false, $addlog);
            $logArr['varTitle'] = $newInterconnectionsObj->varTitle;
            Log::recordLog($logArr);
            if (Auth::user()->can('recent-updates-list')) {
                $notificationArr = MyLibrary::notificationData($id, $newInterconnectionsObj);
                RecentUpdates::setNotification($notificationArr);
            }
            $actionMessage = trans('Interconnections::template.InterconnectionsModule.addedMessage');
        }
        $whereConditionsAddstar = ['id' => $Interconnections->id];
        $updateAddStar = [
            'chrAddStar' => 'Y',
        ];
        CommonModel::updateRecords($whereConditionsAddstar, $updateAddStar, false, 'Powerpanel\Interconnections\Models\Interconnections');
    }

    public function insertNewRecord($postArr, $InterconnectionsArr)
    {
        $response = false;
        $InterconnectionsArr['varTitle'] = stripslashes(trim($postArr['title']));
        $InterconnectionsArr['intDisplayOrder'] = self::newDisplayOrderAdd($postArr['display_order'], $postArr['parent_category_id']);
        $InterconnectionsArr['intParentCategoryId'] = $postArr['parent_category_id'];
        $InterconnectionsArr['txtShortDescription'] = $postArr['txtShortDescription'];
        $InterconnectionsArr['varSector'] = $postArr['sector'];
        $InterconnectionsArr['fkIntDocId'] = isset($postArr['doc_id']) ? $postArr['doc_id'] : '';
        if ($postArr['chrMenuDisplay'] == 'D') {
            $InterconnectionsArr['chrDraft'] = 'D';
            $InterconnectionsArr['chrPublish'] = 'N';
        } else {
            $InterconnectionsArr['chrDraft'] = 'N';
        }
        $InterconnectionsArr['UserID'] = auth()->user()->id;
        $InterconnectionsArr['chrMain'] = 'Y';
        $InterconnectionsArr['created_at'] = Carbon::now();
        $InterconnectionsID = CommonModel::addRecord($InterconnectionsArr, 'Powerpanel\Interconnections\Models\Interconnections');
        if (!empty($InterconnectionsID)) {
            $id = $InterconnectionsID;
            self::newReOrderDisplayOrder($postArr['parent_category_id']);
            $newInterconnectionsObj = Interconnections::getRecordForLogById($id);
            $logArr = MyLibrary::logData($id);
            $logArr['varTitle'] = $newInterconnectionsObj->varTitle;
            Log::recordLog($logArr);
            if (Auth::user()->can('recent-updates-list')) {
                $notificationArr = MyLibrary::notificationData($id, $newInterconnectionsObj);
                RecentUpdates::setNotification($notificationArr);
            }
            $response = $newInterconnectionsObj;
            $actionMessage = trans('Interconnections::template.InterconnectionsModule.addedMessage');
        }
        return $response;
    }

    public function get_list_trash()
    {
        /* Start code for sorting */
        $filterArr = [];
        $records = array();
        $records["data"] = array();
        $filterArr['orderColumnNo'] = (!empty(Request::input('order')[0]['column']) ? Request::input('order')[0]['column'] : '');
        $filterArr['orderByFieldName'] = (!empty(Request::input('columns')[$filterArr['orderColumnNo']]['name']) ? Request::input('columns')[$filterArr['orderColumnNo']]['name'] : '');
        $filterArr['orderTypeAscOrDesc'] = (!empty(Request::input('order')[0]['dir']) ? Request::input('order')[0]['dir'] : '');
        $filterArr['customFilterIdentity'] = !empty(Request::get('customFilterIdentity')) ? Request::get('customFilterIdentity') : '';
        $filterArr['statusFilter'] = !empty(Request::input('statusValue')) ? Request::input('statusValue') : '';
        $filterArr['categoryfilter'] = !empty(Request::input('category')) ? Request::input('category') : '';
        $filterArr['sectorFilter'] = !empty(Request::input('sectorValue')) ? Request::input('sectorValue') : '';
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
        $arrResults = Interconnections::getRecordListTrash($filterArr, $isAdmin);
        $iTotalRecords = Interconnections::getRecordCountforListTrash($filterArr, true, $isAdmin, [], $this->currentUserRoleSector);
        $end = $filterArr['iDisplayStart'] + $filterArr['iDisplayLength'];
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;
        $tableSortedType = (isset($filterArr['orderTypeAscOrDesc']) && $filterArr['orderTypeAscOrDesc'] != "") ? $filterArr['orderTypeAscOrDesc'] : '';
        $totalRecords = Interconnections::getRecordCount(false, false, $isAdmin, $this->currentUserRoleSector);

        if (count($arrResults) > 0 && !empty($arrResults)) {
            foreach ($arrResults as $key => $value) {
                $records["data"][] = $this->tableDataTrash($value, $totalRecords, $tableSortedType);
            }
        }
        $NewRecordsCount = Interconnections::getNewRecordsCount($isAdmin, $this->currentUserRoleSector);
        $records["newRecordCount"] = $NewRecordsCount;
        $records["customActionStatus"] = "OK";
        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;
        echo json_encode($records);
        exit;
    }

    public function get_list_favorite()
    {
        $userIsAdmin = false;
        if (!empty($this->currentUserRoleData)) {
            if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                $userIsAdmin = true;
            }
        }
        $filterArr = [];
        $records = [];
        $records["data"] = array();
        $filterArr['orderColumnNo'] = (!empty(Request::input('order')[0]['column']) ? Request::input('order')[0]['column'] : '');
        $filterArr['orderByFieldName'] = (!empty(Request::input('columns')[$filterArr['orderColumnNo']]['name']) ? Request::input('columns')[$filterArr['orderColumnNo']]['name'] : '');
        $filterArr['orderTypeAscOrDesc'] = (!empty(Request::input('order')[0]['dir']) ? Request::input('order')[0]['dir'] : '');
        $filterArr['customFilterIdentity'] = !empty(Request::get('customFilterIdentity')) ? Request::get('customFilterIdentity') : '';
        $filterArr['statusFilter'] = !empty(Request::input('statusValue')) ? Request::input('statusValue') : '';
        $filterArr['categoryfilter'] = !empty(Request::input('category')) ? Request::input('category') : '';
        $filterArr['sectorFilter'] = !empty(Request::input('sectorValue')) ? Request::input('sectorValue') : '';
        $filterArr['catFilter'] = !empty(Request::input('catValue')) ? Request::input('catValue') : '';
        $filterArr['searchFilter'] = !empty(Request::input('searchValue')) ? Request::input('searchValue') : '';
        $filterArr['iDisplayLength'] = intval(Request::input('length'));
        $filterArr['iDisplayStart'] = intval(Request::input('start'));
        $sEcho = intval(Request::input('draw'));
        $isAdmin = false;
        if ($userIsAdmin) {
            $isAdmin = true;
        }
        $module = Modules::getModule('pages');
        $cmsPageForModule = CmsPage::getRecordForPowerpanelShareByModuleId(Config::get('Constant.MODULE.ID'), $module->id);
        $arrResults = Interconnections::getRecordListFavorite($filterArr, $isAdmin, $this->currentUserRoleSector);
        $iTotalRecords = Interconnections::getRecordCountforListFavorite($filterArr, true, $isAdmin, [], $this->currentUserRoleSector);
        $end = $filterArr['iDisplayStart'] + $filterArr['iDisplayLength'];
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;
        if (!empty($arrResults)) {
            foreach ($arrResults as $key => $value) {
                $records["data"][] = $this->tableDataFavorite($value, $cmsPageForModule);
            }
        }
        $NewRecordsCount = Interconnections::getNewRecordsCount($isAdmin, $this->currentUserRoleSector);
        $records["newRecordCount"] = $NewRecordsCount;
        $records["customActionStatus"] = "OK";
        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;
        return json_encode($records);
    }

    /**
     * This method loads Interconnections table data on view
     * @return  View
     * @since   2017-11-10
     * @author  NetQuick
     */
    public function get_list()
    {
        $filterArr = [];
        $records = [];
        $records["data"] = [];
        $filterArr['orderColumnNo'] = (!empty(Request::input('order')[0]['column']) ? Request::input('order')[0]['column'] : '');
        $filterArr['orderByFieldName'] = (!empty(Request::input('columns')[$filterArr['orderColumnNo']]['name']) ? Request::input('columns')[$filterArr['orderColumnNo']]['name'] : '');
        $filterArr['orderTypeAscOrDesc'] = (!empty(Request::input('order')[0]['dir']) ? Request::input('order')[0]['dir'] : '');
        $filterArr['statusFilter'] = !empty(Request::input('statusValue')) ? Request::input('statusValue') : '';
        $filterArr['sectorFilter'] = !empty(Request::input('sectorValue')) ? Request::input('sectorValue') : '';
        $filterArr['categoryfilter'] = !empty(Request::input('category')) ? Request::input('category') : '';
        $filterArr['searchFilter'] = !empty(Request::input('searchValue')) ? Request::input('searchValue') : '';
        $filterArr['InterconnectionsFilter'] = !empty(Request::input('InterconnectionsFilter')) ? Request::input('InterconnectionsFilter') : '';
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
        $arrResults = Interconnections::getRecordListforInterconnectionsGrid($filterArr, $isAdmin, $this->currentUserRoleSector);
        $arrResults = $this->restructureData($arrResults, $filterArr);
        $iTotalRecords = Interconnections::getRecordCountforList($filterArr, true, $isAdmin, $this->currentUserRoleSector);
        $end = $filterArr['iDisplayStart'] + $filterArr['iDisplayLength'];
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;
        $tableSortedType = (isset($filterArr['orderTypeAscOrDesc']) && $filterArr['orderTypeAscOrDesc'] != "") ? $filterArr['orderTypeAscOrDesc'] : '';
        if (!empty($arrResults)) {
            foreach ($arrResults as $key => $value) {
                // $records["data"][] = $this->tableData($value);
                $records["data"][] = $this->tableData($value, $iTotalRecords, $tableSortedType);
            }
        }
        $NewRecordsCount = Interconnections::getNewRecordsCount($isAdmin, $this->currentUserRoleSector);
        $records["newRecordCount"] = $NewRecordsCount;
        $records["customActionStatus"] = "OK";
        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;
        return json_encode($records);
    }

    /**
     * This method loads Interconnections table data on view
     * @return  View
     * @since   2017-11-10
     * @author  NetQuick
     */
    public function get_list_New()
    {
        $userIsAdmin = false;
        if (!empty($this->currentUserRoleData)) {
            if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                $userIsAdmin = true;
            }
        }
        $filterArr = [];
        $records = [];
        $records["data"] = [];
        $filterArr['orderColumnNo'] = (!empty(Request::input('order')[0]['column']) ? Request::input('order')[0]['column'] : '');
        $filterArr['orderByFieldName'] = (!empty(Request::input('columns')[$filterArr['orderColumnNo']]['name']) ? Request::input('columns')[$filterArr['orderColumnNo']]['name'] : '');
        $filterArr['orderTypeAscOrDesc'] = (!empty(Request::input('order')[0]['dir']) ? Request::input('order')[0]['dir'] : '');
        $filterArr['statusFilter'] = !empty(Request::input('statusValue')) ? Request::input('statusValue') : '';
        $filterArr['categoryfilter'] = !empty(Request::input('category')) ? Request::input('category') : '';
        $filterArr['sectorFilter'] = !empty(Request::input('sectorValue')) ? Request::input('sectorValue') : '';
        $filterArr['searchFilter'] = !empty(Request::input('searchValue')) ? Request::input('searchValue') : '';
        $filterArr['InterconnectionsFilter'] = !empty(Request::input('InterconnectionsFilter')) ? Request::input('InterconnectionsFilter') : '';
        $filterArr['personalityFilter'] = !empty(Request::input('personalityFilter')) ? Request::input('personalityFilter') : '';
        $filterArr['paymentFilter'] = !empty(Request::input('paymentFilter')) ? Request::input('paymentFilter') : '';
        $filterArr['rangeFilter'] = !empty(Request::input('rangeFilter')) ? Request::input('rangeFilter') : '';
        $filterArr['iDisplayLength'] = intval(Request::input('length'));
        $filterArr['iDisplayStart'] = intval(Request::input('start'));
        $sEcho = intval(Request::input('draw'));
        $arrResults = Interconnections::getRecordListApprovalTab($filterArr, $userIsAdmin, $this->currentUserRoleSector);
        $iTotalRecords = Interconnections::getRecordCountListApprovalTab($filterArr, $userIsAdmin, $this->currentUserRoleSector);
        $end = $filterArr['iDisplayStart'] + $filterArr['iDisplayLength'];
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;
        if (!empty($arrResults)) {
            foreach ($arrResults as $key => $value) {
                $records["data"][] = $this->tableDataWaitingToApprovedData($value);
            }
        }
        $NewRecordsCount = Interconnections::getNewRecordsCount($userIsAdmin, $this->currentUserRoleSector);
        $records["newRecordCount"] = $NewRecordsCount;
        $records["customActionStatus"] = "OK";
        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;
        return json_encode($records);
    }

    public function get_list_draft()
    {
        $userIsAdmin = false;
        if (!empty($this->currentUserRoleData)) {
            if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                $userIsAdmin = true;
            }
        }
        $filterArr = [];
        $records = [];
        $records["data"] = [];
        $filterArr['orderColumnNo'] = (!empty(Request::input('order')[0]['column']) ? Request::input('order')[0]['column'] : '');
        $filterArr['orderByFieldName'] = (!empty(Request::input('columns')[$filterArr['orderColumnNo']]['name']) ? Request::input('columns')[$filterArr['orderColumnNo']]['name'] : '');
        $filterArr['orderTypeAscOrDesc'] = (!empty(Request::input('order')[0]['dir']) ? Request::input('order')[0]['dir'] : '');
        $filterArr['statusFilter'] = !empty(Request::input('statusValue')) ? Request::input('statusValue') : '';
        $filterArr['categoryfilter'] = !empty(Request::input('category')) ? Request::input('category') : '';
        $filterArr['sectorFilter'] = !empty(Request::input('sectorValue')) ? Request::input('sectorValue') : '';
        $filterArr['searchFilter'] = !empty(Request::input('searchValue')) ? Request::input('searchValue') : '';
        $filterArr['InterconnectionsFilter'] = !empty(Request::input('InterconnectionsFilter')) ? Request::input('InterconnectionsFilter') : '';
        $filterArr['personalityFilter'] = !empty(Request::input('personalityFilter')) ? Request::input('personalityFilter') : '';
        $filterArr['paymentFilter'] = !empty(Request::input('paymentFilter')) ? Request::input('paymentFilter') : '';
        $filterArr['rangeFilter'] = !empty(Request::input('rangeFilter')) ? Request::input('rangeFilter') : '';
        $filterArr['iDisplayLength'] = intval(Request::input('length'));
        $filterArr['iDisplayStart'] = intval(Request::input('start'));
        $sEcho = intval(Request::input('draw'));
        $isAdmin = false;
        if ($userIsAdmin) {
            $isAdmin = true;
        }
        $module = Modules::getModule('pages');
        $cmsPageForModule = CmsPage::getRecordForPowerpanelShareByModuleId(Config::get('Constant.MODULE.ID'), $module->id);
        $arrResults = Interconnections::getRecordListDraft($filterArr, $userIsAdmin, $this->currentUserRoleSector);
        $iTotalRecords = Interconnections::getRecordCountforListDarft($filterArr, true, $isAdmin, [], $this->currentUserRoleSector);
        $end = $filterArr['iDisplayStart'] + $filterArr['iDisplayLength'];
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;
        if (!empty($arrResults)) {
            foreach ($arrResults as $key => $value) {
                $records["data"][] = $this->tableDataDraft($value, $cmsPageForModule);
            }
        }
        $NewRecordsCount = Interconnections::getNewRecordsCount($userIsAdmin, $this->currentUserRoleSector);
        $records["newRecordCount"] = $NewRecordsCount;
        $records["customActionStatus"] = "OK";
        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;
        return json_encode($records);
    }

    /**
     * This method delete multiples Interconnections
     * @return  true/false
     * @since   2017-07-15
     * @author  NetQuick
     */
    public function DeleteRecord(Request $request)
    {
        $value = Request::input('value');
        $data['ids'] = Request::input('ids');
        $moduleHaveFields = ['chrMain', 'chrIsPreview'];
        $update = Self::deleteMultipleRecords($data);
        if (File::exists(app_path() . '/Comments.php') != null || File::exists(base_path() . '/packages/Powerpanel/Workflow/src/Models/Comments.php') != null) {
            Comments::deleteComments($data['ids'], Config::get('Constant.MODULE.MODEL_NAME'));
        }
        foreach ($update as $ids) {
            $ignoreDeleteScope = true;
            $Deleted_Record = Interconnections::getRecordById($ids, $ignoreDeleteScope);
            $Cnt_Letest = Interconnections::getRecordCount_letest($Deleted_Record['fkMainRecord'], $Deleted_Record['id']);
            if ($Cnt_Letest <= 0) {
                $updateLetest = [
                    'chrAddStar' => 'N',
                ];
                $whereConditionsApprove = ['id' => $Deleted_Record['fkMainRecord']];
                CommonModel::updateRecords($whereConditionsApprove, $updateLetest, false, 'Powerpanel\Interconnections\Models\Interconnections');
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
        self::flushCache();
        echo json_encode($update);
        exit;
    }

    public static function deleteMultipleRecords($data)
    {
        $response = false;
        $responseAr = [];
        if (!empty($data)) {
            $updateFields = ['chrDelete' => 'Y', 'chrPublish' => 'N'];
            $whereINConditions = $data['ids'];
            $update = CommonModel::updateMultipleRecords($whereINConditions, $updateFields, false, 'Powerpanel\Interconnections\Models\Interconnections');
            foreach ($data['ids'] as $key => $id) {
                if ($update) {
                    $objModule = Interconnections::getRecordsForDeleteById($id);
                    if (isset($objModule->intDisplayOrder)) {
                        self::newReOrderDisplayOrder($objModule->intParentCategoryId);
                    }
                    if (!empty($id)) {
                        $logArr = MyLibrary::logData($id);
                        $title = '-';
                        if (isset($objModule->varTitle)) {
                            $title = $objModule->varTitle;
                        } elseif (isset($objModule->varName)) {
                            $title = $objModule->varName;
                        } elseif (isset($objModule->name)) {
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
    public function reorder()
    {
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
    public static function swapOrder($order = null, $exOrder = null, $parentRecordId = false, $recordID = false)
    {
        $recEx = Interconnections::getRecordByOrderByParent($exOrder, $parentRecordId);
        if (!empty($recEx)) {
            $recCur = Interconnections::getRecordByOrderByParent($order, $parentRecordId);
            if ($recCur->intDisplayOrder != $recEx->intDisplayOrder) {
                $whereConditionsForEx = ['id' => $recEx['id']];
                CommonModel::updateRecords($whereConditionsForEx, ['intDisplayOrder' => $recCur->intDisplayOrder], false, 'Powerpanel\Interconnections\Models\Interconnections');
                $whereConditionsForCur = ['id' => $recCur['id']];
                CommonModel::updateRecords($whereConditionsForCur, ['intDisplayOrder' => $recEx->intDisplayOrder], false, 'Powerpanel\Interconnections\Models\Interconnections');
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
    public function publish(Request $request)
    {
        $requestArr = Request::all();
        $val = Request::get('val');
        $alias = Request::input('alias');
        $update = MyLibrary::setPublishUnpublish($alias, $val, 'Powerpanel\Interconnections\Models\Interconnections');
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
    public function recordHistory($data = false)
    {
        $returnHtml = '';
        $returnHtml .= '<table class="new_table_desing table table-striped table-bordered table-hover">
                            <thead>
                                    <tr>
                                        <th align="center">' . trans("interconnections::template.common.title") . '</th>
                                        <th align="center">' . trans("interconnections::template.common.parentCategory") . '</th>
                                        <th align="center">Designation</th>
                                        <th align="center">' . trans("interconnections::template.common.displayorder") . '</th>
                                        <th align="center">' . trans("interconnections::template.common.publish") . '</th>
                                    </tr>
                            </thead>
                            <tbody>
                                    <tr>
                                        <td align="center">' . stripslashes($data->varTitle) . '</td>';
        if ($data->intParentCategoryId > 0) {
            $catIDS[] = $data->intParentCategoryId;
            $parentCateName = Interconnections::getParentCategoryNameBycatId($catIDS);
            $parentCateName = $parentCateName[0]->varTitle;
            $returnHtml .= '<td align="center">' . $parentCateName . '</td>';
        } else {
            $returnHtml .= '<td align="center">-</td>';
        }
        $returnHtml .= '<td align="center">' . ($data->intDisplayOrder) . '</td>
                                        <td align="center">' . $data->chrPublish . '</td>
                                    </tr>
                            </tbody>
                        </table>';
        return $returnHtml;
    }

    public function newrecordHistory($data = false, $newdata = false)
    {
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
                                        <th align="center">' . trans("interconnections::template.common.title") . '</th>
                                        <th align="center">' . trans("interconnections::template.common.parentCategory") . '</th>
                                        <th align="center">Designation</th>
                                        <th align="center">' . trans("interconnections::template.common.displayorder") . '</th>
                                        <th align="center">' . trans("interconnections::template.common.publish") . '</th>
                                    </tr>
                            </thead>
                            <tbody>
                                    <tr>
                                        <td align="center" ' . $titlecolor . '>' . stripslashes($newdata->varTitle) . '</td>';
        if ($newdata->intParentCategoryId > 0) {
            $catIDS[] = $newdata->intParentCategoryId;
            $parentCateName = Interconnections::getParentCategoryNameBycatId($catIDS);
            $parentCateName = $parentCateName[0]->varTitle;
            $returnHtml .= '<td align="center" ' . $ParentCategoryIdcolor . '>' . $parentCateName . '</td>';
        } else {
            $returnHtml .= '<td align="center">-</td>';
        }
        $returnHtml .= ' <td align="center" ' . $DisplayOrdercolor . '>' . ($newdata->intDisplayOrder) . '</td>
                                        <td align="center" ' . $Publishcolor . '>' . $newdata->chrPublish . '</td>
                                    </tr>
                            </tbody>
                        </table>';
        return $returnHtml;
    }

    public function tableData($value = false)
    {
        $sector = '';
        if (isset($value->varSector) && !empty($value->varSector)) {
            $sector = strtoupper($value->varSector);
        }
        $isParent = Interconnections::getCountById($value->id);
         $hasRecords = Interconnections::getCountById($value->id);
        $details = '';
        $parent_category_name = ' ';
        $publish_action = '';
        $titleData_delete = "";
        $titleData_publish = "";
        $details = '';

        if ($isParent > 0) {
            $titleData_delete = 'This interconnections is selected as parent interconnections in other record so it can&#39;t be deleted.';
            $titleData_publish = 'This interconnections is selected as parent interconnections in other record so it can&#39;t be published/unpublished.';
        }

        $checkbox = '<a href="javascript:;" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-toggle="tooltip" data-bs-content="' . $titleData_delete . '" title="' . $titleData_delete . '"><i style="color:red" class="ri-alert-fill"></i></a>';

        $checkbox_publish = '<a href="javascript:;" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-toggle="tooltip" data-bs-content="' . $titleData_publish . '" title="' . $titleData_publish . '"><i style="color:red" class="ri-alert-fill"></i></a>';

        if (Auth::user()->can('interconnections-edit')) {
            $details .= '<a class="" title="' . trans("interconnections::template.common.edit") . '" href="' . route('powerpanel.interconnections.edit', array('alias' => $value->id)) . '"><i class="ri-pencil-line"></i></a>';
        }

        if ((Auth::user()->can('interconnections-delete') || (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y')) && $isParent == 0) {
            if (Config::get('Constant.DEFAULT_TRASH') == 'Y') {
                $details .= '<a class="delete-grid" title="' . trans("interconnections::template.common.delete") . '" onclick = \'Trashfun("' . $value->id . '")\' data-controller="InterconnectionsController" data-alias = "' . $value->id . '" data-tab="P"><i class="ri-delete-bin-line"></i></a>';
            } else {
                $details .= '<a class=" delete" title="' . trans("interconnections::template.common.delete") . '" data-controller="InterconnectionsController" data-alias = "' . $value->id . '" data-tab="P"><i class="ri-delete-bin-line"></i></a>';
            }
        }
        
        if ($value->chrAddStar != 'Y') {
            if ($value->chrDraft != 'D') {
                if (Auth::user()->can('interconnections-publish')) {
                    if ($hasRecords == 0 && $isParent == 0) {
                        if ($value->chrPublish == 'Y') {
                            //Bootstrap Switch
                            $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/publications-category', 'data_alias'=>$value->id, 'title'=>trans("publications-category::template.common.publishedRecord"), 'data_value'=>'Unpublish'])->render();
                        } else {
                            //Bootstrap Switch
                            $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/publications-category', 'data_alias'=>$value->id, 'title'=>trans("publications-category::template.common.unpublishedRecord"), 'data_value'=>'Publish', 'checked'=>'checked'])->render();
                        }
                    } else {
                        $publish_action = $checkbox_publish;
                    }
                }
            } else {
                //Bootstrap Switch
                $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/publications-category', 'data_alias'=>$value->id, 'title'=>trans("publications-category::template.common.unpublishedRecord"), 'data_value'=>'Publish', 'checked'=>'checked'])->render();
            }
        } else {
            if ($hasRecords == 0 && $isParent == 0) {
                $publish_action .= '---';
            } else {
                $publish_action = $checkbox_publish;
            }
        }

        $parentCategoryTitle = '-';
        if (!empty($value->intParentCategoryId) && $value->intParentCategoryId > 0) {
            $catIDS[] = $value->intParentCategoryId;
            $parentCategoryName = Interconnections::getParentCategoryNameBycatId($catIDS);
            $parentCategoryTitle = $parentCategoryName[0]->varTitle;
        }

        if (Auth::user()->can('interconnections-reviewchanges') && (File::exists(app_path() . '/Workflow.php') != null || File::exists(base_path() . '/packages/Powerpanel/Workflow/src/Models/Workflow.php') != null)) {
            $update = "<a class=\"icon_title1\" style=\"margin-right: 5px;\" onclick=\"javascript:expandcollapsepanel(this ,'primary-tasklisting" . $value->id . "', 'primary-mainsingnimg" . $value->id . "'," . $value->id . "," . "'primary-'" . ")\"><i id=\"primary-mainsingnimg" . $value->id . "\" class=\"ri-add-box-line\"></i></a>";
            $rollback = "<a class=\"icon_title2\" style=\"margin-right: 5px;\" onclick=\"javascript:expandcollapsepanel_rolback(this ,'primary-tasklisting_rollback" . $value->id . "', 'primary-mainsingnimg_rollback" . $value->id . "'," . $value->id . "," . "'primary-'" . ")\"><i id=\"primary-mainsingnimg_rollback" . $value->id . "\" class=\"ri-history-line\"></i></a>";
        } else {
            $update = '';
            $rollback = '';
        }

        if ($value->chrLock != 'Y') {
            $title = '<div class="quick_edit"><a class="" title="Edit" href="' . route('powerpanel.interconnections.edit', array('alias' => $value->id)) . '">' . $value->treename . '</a></div>';
        } else {
            if (auth()->user()->id != $value->LockUserID) {
                if (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y') {
                    $title = '<div class="quick_edit"><a class="" title="Edit" href="' . route('powerpanel.interconnections.edit', array('alias' => $value->id)) . '">' . $value->treename . '</a></div>';
                } else {
                    $title = '<div class="quick_edit">' . $value->treename . '</div>';
                }
            } else {
                $title = '<div class="quick_edit"><a class="" title="Edit" href="' . route('powerpanel.interconnections.edit', array('alias' => $value->id)) . '">' . $value->treename . '</a></div>';
            }
        }

        if (Config::get('Constant.DEFAULT_FAVORITE') == 'Y') {
            $Favorite_array = explode(",", $value->FavoriteID);
            if (in_array(auth()->user()->id, $Favorite_array)) {
                $Class = 'ri-star-fill';
                $Favorite = '<a class="star_icon_div" href="javascript:;" onclick="GetFavorite(' . $value->id . ',\'N\',\'P\')"><i class="' . $Class . '"></i></a>';
            } else {
                $Class = 'ri-star-line';
                $Favorite = '<a class="star_icon_div" href="javascript:;" onclick="GetFavorite(' . $value->id . ',\'Y\',\'P\')"><i class="' . $Class . '"></i></a>';
            }
        } else {
            $Favorite = '';
        }
        $First_td = '<div class="star_box">' . $Favorite . '</div>';

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
        if ($value->chrDraft == 'D') {
            $status .= Config::get('Constant.DRAFT_LIST') . ' ';
        }
        if ($value->chrAddStar == 'Y') {
            $status .= Config::get('Constant.APPROVAL_LIST') . ' ';
        }

        if(File::exists(base_path() . '/packages/Powerpanel/Workflow/src/Models/Workflow.php')) {

            $childCount = Interconnections::where('fkMainRecord', $value->id)
                                                ->where('dtApprovedDateTime','!=',NULL)
                                                ->count();
            if($this->currentUserRoleData->chrIsAdmin == 'Y' && $childCount > 1) {
                $log .= "<a title='Rollback to previous version'  onclick=\"rollbackToPreviousVersion('" . $value->id . "');\"  class=\"log-grid\"><i class=\"ri-history-line\"></i></a>";
            }
        }

        $records = array(
            ($isParent == 0) ? '<input type="checkbox" name="delete" class="chkDelete form-check-input" value="' . $value->id . '">' : $checkbox,
            $First_td,
            '<div class="pages_title_div_row">' . $update . $rollback . $title . ' ' . $status . $statusdata . ' ' . $sector . '</div>',
            $parentCategoryTitle,
            '<a href="javascript:;" data-parentRecordId="' . $value->intParentCategoryId . '" data-order="' . $value->intDisplayOrder . '" class="moveUp"><i class="ri-arrow-up-line" aria-hidden="true"></i></a>
				' . $value->DisplayOrder .
            ' <a href="javascript:;" data-parentRecordId="' . $value->intParentCategoryId . '" data-order="' . $value->intDisplayOrder . '" class="moveDwn"><i class="ri-arrow-down-line" aria-hidden="true"></i></a>',
            $publish_action,
            $log,
            // $value->intDisplayOrder,
        );
        return $records;
    }

    public function tableDataWaitingToApprovedData($value = false)
    {
        $sector = '';
        if (isset($value->varSector) && !empty($value->varSector)) {
            $sector = strtoupper($value->varSector);
        }

        $isParent = Interconnections::getCountById($value->id);
        $details = '';
        $parent_category_name = ' ';
        $publish_action = '';
        $titleData_delete = "";
        $titleData_publish = "";
        $details = '';

        if ($isParent > 0) {
            $titleData_delete = 'This interconnections is selected as parent interconnections in other record so it can&#39;t be deleted.';
            $titleData_publish = 'This interconnections is selected as parent interconnections in other record so it can&#39;t be published/unpublished.';
        }

        $checkbox = '<a href="javascript:;" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-toggle="tooltip" data-bs-content="' . $titleData_delete . '" title="' . $titleData_delete . '"><i style="color:red" class="ri-alert-fill"></i></a>';
        $checkbox_publish = '<a href="javascript:;" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-toggle="tooltip" data-bs-content="' . $titleData_publish . '" title="' . $titleData_publish . '"><i style="color:red" class="ri-alert-fill"></i></a>';

        if (Auth::user()->can('interconnections-edit')) {
            $details .= '<a class="" title="' . trans("interconnections::template.common.edit") . '" href="' . route('powerpanel.interconnections.edit', array('alias' => $value->id)) . '"><i class="ri-pencil-line"></i></a>';
        }

        if ((Auth::user()->can('interconnections-delete') || (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y')) && $isParent == 0) {
            if (Config::get('Constant.DEFAULT_TRASH') == 'Y') {
                $details .= '<a class="delete-grid" title="' . trans("interconnections::template.common.delete") . '" onclick = \'Trashfun("' . $value->id . '")\' data-controller="InterconnectionsController" data-alias = "' . $value->id . '" data-tab="P"><i class="ri-delete-bin-line"></i></a>';
            } else {
                $details .= '<a class=" delete" title="' . trans("interconnections::template.common.delete") . '" data-controller="InterconnectionsController" data-alias = "' . $value->id . '" data-tab="P"><i class="ri-delete-bin-line"></i></a>';
            }
        }

        $parentCategoryTitle = '-';
        if (!empty($value->intParentCategoryId) && $value->intParentCategoryId > 0) {
            $catIDS[] = $value->intParentCategoryId;
            $parentCategoryName = Interconnections::getParentCategoryNameBycatId($catIDS);
            $parentCategoryTitle = $parentCategoryName[0]->varTitle;
        }

        if (Config::get('Constant.DEFAULT_FAVORITE') == 'Y') {
            $Favorite_array = explode(",", $value->FavoriteID);
            if (in_array(auth()->user()->id, $Favorite_array)) {
                $Class = 'ri-star-fill';
                $Favorite = '<a class="star_icon_div" href="javascript:;" onclick="GetFavorite(' . $value->id . ',\'N\',\'A\')"><i class="' . $Class . '"></i></a>';
            } else {
                $Class = 'ri-star-line';
                $Favorite = '<a class="star_icon_div" href="javascript:;" onclick="GetFavorite(' . $value->id . ',\'Y\',\'A\')"><i class="' . $Class . '"></i></a>';
            }
        } else {
            $Favorite = '';
        }
        $First_td = '<div class="star_box">' . $Favorite . '</div>';

        if (Auth::user()->can('interconnections-reviewchanges')) {
            $update = "<a class=\"icon_title1\" style=\"margin-right: 5px;\" onclick=\"javascript:expandcollapsepanel(this ,'waiting-tasklisting" . $value->id . "', 'waiting-mainsingnimg" . $value->id . "'," . $value->id . "," . "'waiting-'" . ")\"><i id=\"waiting-mainsingnimg" . $value->id . "\" class=\"ri-add-box-line\"></i></a>";
            $rollback = "<a class=\"icon_title2\" style=\"margin-right: 5px;\" onclick=\"javascript:expandcollapsepanel_rolback(this ,'waiting-tasklisting_rollback" . $value->id . "', 'waiting-mainsingnimg_rollback" . $value->id . "'," . $value->id . "," . "'waiting-'" . ")\"><i id=\"waiting-mainsingnimg_rollback" . $value->id . "\" class=\"ri-history-line\"></i></a>";
        } else {
            $update = '';
            $rollback = '';
        }

        if (Auth::user()->can('interconnections-reviewchanges') && $value->chrAddStar == 'Y') {
            $star = 'addhiglight';
        } else {
            $star = '';
        }

        if (Auth::user()->can('interconnections-edit')) {
            if ($value->chrLock != 'Y') {
                $title = '<div class="quick_edit"><a class="' . $star . '" title="' . trans("interconnections::template.common.edit") . '" href="' . route('powerpanel.interconnections.edit', array('alias' => $value->id)) . '">' . $value->varTitle . '</a></div>';
            } else {
                if (auth()->user()->id != $value->LockUserID) {
                    if (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y') {
                        $title = '<div class="quick_edit"><a class="' . $star . '" title="' . trans("interconnections::template.common.edit") . '" href="' . route('powerpanel.interconnections.edit', array('alias' => $value->id)) . '">' . $value->varTitle . '</a></div>';
                    } else {
                        $title = '<div class="quick_edit">' . $value->varTitle . '</div>';
                    }
                } else {
                    $title = '<div class="quick_edit"><a class="' . $star . '" title="' . trans("interconnections::template.common.edit") . '" href="' . route('powerpanel.interconnections.edit', array('alias' => $value->id)) . '">' . $value->varTitle . '</a></div>';
                }
            }
        } else {
            $title = stripslashes($value->varTitle);
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

        $status = '';
        if ($value->chrDraft == 'D') {
            $status .= Config::get('Constant.DRAFT_LIST') . ' ';
        }

        if(File::exists(base_path() . '/packages/Powerpanel/Workflow/src/Models/Workflow.php')) {
            if($this->currentUserRoleData->chrIsAdmin == 'Y' && count($value->child) > 1) {
                $log .= "<a title='Rollback to previous version'  onclick=\"rollbackToPreviousVersion('" . $value->id . "');\"  class=\"log-grid\"><i class=\"ri-history-line\"></i></a>";
            }
        }

        $records = array(
            $First_td,
            '<div class="pages_title_div_row">' . $update . $rollback . $title .' ' .$status . $statusdata . ' ' . $sector . '</div>',
            $parentCategoryTitle,
            // '<a href="javascript:;" data-parentRecordId="' . $value->intParentCategoryId . '" data-order="' . $value->intDisplayOrder . '" class="moveUp"><i class="ri-arrow-up-line" aria-hidden="true"></i></a>
            //     ' . $value->DisplayOrder . ' <a href="javascript:;" data-parentRecordId="' . $value->intParentCategoryId . '" data-order="' . $value->intDisplayOrder . '" class="moveDwn"><i class="ri-arrow-down-line" aria-hidden="true"></i></a>',
            $log,
        );
        return $records;
    }

    public function tableDataFavorite($value = false, $moduleCmsPageData = false)
    {
        $sector = '';
        if (isset($value->varSector) && !empty($value->varSector)) {
            $sector = strtoupper($value->varSector);
        }

        $isParent = Interconnections::getCountById($value->id);
        $details = '';
        $parent_category_name = ' ';
        $publish_action = '';
        $titleData_delete = "";
        $titleData_publish = "";
        $details = '';

        if ($isParent > 0) {
            $titleData_delete = 'This interconnection is selected as parent interconnection in other record so it can&#39;t be deleted.';
            $titleData_publish = 'This interconnection is selected as parent interconnection in other record so it can&#39;t be published/unpublished.';
        }

        $checkbox = '<a href="javascript:;" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-toggle="tooltip" data-bs-content="' . $titleData_delete . '" title="' . $titleData_delete . '"><i style="color:red" class="ri-alert-fill"></i></a>';
        $checkbox_publish = '<a href="javascript:;" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-toggle="tooltip" data-bs-content="' . $titleData_publish . '" title="' . $titleData_publish . '"><i style="color:red" class="ri-alert-fill"></i></a>';

        $details = '';
        $publish_action = '';

        if (Auth::user()->can('interconnections-edit')) {
            $details .= '<a class="" title="' . trans("interconnections::template.common.edit") . '" href="' . route('powerpanel.interconnections.edit', array('alias' => $value->id)) . '"><i class="ri-pencil-line"></i></a>';
        }

        if ((Auth::user()->can('interconnections-delete') || (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y')) && $isParent == 0) {
            if (Config::get('Constant.DEFAULT_TRASH') == 'Y') {
                $details .= '<a class="delete-grid" title="' . trans("interconnections::template.common.delete") . '" onclick = \'Trashfun("' . $value->id . '")\' data-controller="InterconnectionsController" data-alias = "' . $value->id . '" data-tab="P"><i class="ri-delete-bin-line"></i></a>';
            } else {
                $details .= '<a class=" delete" title="' . trans("interconnections::template.common.delete") . '" data-controller="InterconnectionsController" data-alias = "' . $value->id . '" data-tab="P"><i class="ri-delete-bin-line"></i></a>';
            }
        }

        $parentCategoryTitle = '-';
        if (!empty($value->intParentCategoryId) && $value->intParentCategoryId > 0) {
            $catIDS[] = $value->intParentCategoryId;
            $parentCategoryName = Interconnections::getParentCategoryNameBycatId($catIDS);
            $parentCategoryTitle = $parentCategoryName[0]->varTitle;
        }

        
        $minus = '<span class="glyphicon glyphicon-minus"></span>';
        $title = $value->varTitle;
        if (Auth::user()->can('interconnections-edit')) {
            $title = '<div class="quick_edit text-uppercase"><a href = "' . route('powerpanel.interconnections.edit', array('alias' => $value->id)) . '?tab=T">' . $value->varTitle . '</a>
												</div>';
        }      
        if (Config::get('Constant.DEFAULT_FAVORITE') == 'Y') {
            $Favorite_array = explode(",", $value->FavoriteID);
            if (in_array(auth()->user()->id, $Favorite_array)) {
                $Class = 'ri-star-fill';
                $Favorite = '<a class="star_icon_div" href="javascript:;" onclick="GetFavorite(' . $value->id . ',\'N\',\'P\')"><i class="' . $Class . '"></i></a>';
            } else {
                $Class = 'ri-star-line';
                $Favorite = '<a class="star_icon_div" href="javascript:;" onclick="GetFavorite(' . $value->id . ',\'Y\',\'P\')"><i class="' . $Class . '"></i></a>';
            }
        } else {
            $Favorite = '';
        }
        $First_td = '<div class="star_box">' . $Favorite . '</div>';

        $logurl = url('powerpanel/log?id=' . $value->id . '&mid=' . Config::get('Constant.MODULE.ID'));
        $log = '';
        if ($value->chrLock != 'Y') {
            if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
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
                if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                    $lockedUserData = User::getRecordById($value->LockUserID, true);
                    $lockedUserName = 'someone';
                    if (!empty($lockedUserData)) {
                        $lockedUserName = $lockedUserData->name;
                    }
                    $log .= '<a class="star_lock" onclick="GetUnLockData(' . $value->id . ',' . auth()->user()->id . ',' . Config::get('Constant.MODULE.ID') . ',1)" title="This record has been locked by ' . $lockedUserName . ', Click here to unlock."><i class="ri-lock-2-line"></i></a>';
                } else {

                    $log .= '<a class="star_lock" title="This record has been locked by ' . $lockedUserName . '."><i class="ri-lock-2-line"></i></a>';
                }
            } else {
                $log .= '<a class="star_lock" onclick="GetUnLockData(' . $value->id . ',' . auth()->user()->id . ',' . Config::get('Constant.MODULE.ID') . ',1)" title="Click here to unlock."><i class="ri-lock-2-line"></i></a>';
            }
        }

        $statusdata = '';
        $days = MyLibrary::count_days($value->created_at);
        $days_modified = MyLibrary::count_days($value->updated_at);
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

        $records = array(
            ($isParent == 0) ? '<input type="checkbox" name="delete" class="chkDelete form-check-input" value="' . $value->id . '">' : $checkbox,
            $First_td,
            '<div class="pages_title_div_row">' . $title . ' ' . $status . $statusdata . ' ' . $sector . '</div>',
            $parentCategoryTitle,
            $log,
        );
        return $records;
    }

    public function tableDataTrash($value, $totalRecord = false, $tableSortedType = 'asc')
    {
        $sector = '';
        if (isset($value->varSector) && !empty($value->varSector)) {
            $sector = strtoupper($value->varSector);
        }

        $isParent = Interconnections::getCountById($value->id);
        $details = '';
        $parent_category_name = ' ';
        $publish_action = '';
        $titleData_delete = "";
        $titleData_publish = "";
        $details = '';

        if ($isParent > 0) {
            $titleData_delete = 'This interconnections is selected as parent interconnections in other record so it can&#39;t be deleted.';
            $titleData_publish = 'This interconnections is selected as parent interconnections in other record so it can&#39;t be published/unpublished.';
        }

        $actions = '';
        if (Auth::user()->can('interconnections-delete') && $this->currentUserRoleData->chrIsAdmin == 'Y') {
            $actions .= '<a class=" delete" title="' . trans("interconnections::template.common.delete") . '"  data-controller="InterconnectionsController" data-alias = "' . $value->id . '" data-tab="T"><i class="ri-delete-bin-line"></i></a>';
        }
        $startDate = date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($value->dtDateTime));
        $endDate = !empty($value->dtEndDateTime) ? date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($value->dtEndDateTime)) : 'No Expiry';
        $title = $value->varTitle;

        if (Auth::user()->can('interconnections-edit')) {
            $title = '<div class="quick_edit text-uppercase"><a href = "' . route('powerpanel.interconnections.edit', array('alias' => $value->id)) . '?tab=T">' . $value->varTitle . '</a>
												</div>';
        }

        $parentCategoryTitle = '-';
        if (!empty($value->intParentCategoryId) && $value->intParentCategoryId > 0) {
            $catIDS[] = $value->intParentCategoryId;
            $parentCategoryName = Interconnections::getParentCategoryNameBycatId($catIDS);
            $parentCategoryTitle = $parentCategoryName[0]->varTitle;
        }

        $logurl = url('powerpanel/log?id=' . $value->id . '&mid=' . Config::get('Constant.MODULE.ID'));
        if ($actions == "") {
            $actions = "---";
        } else {
            $actions = $actions;
        }

        $log = '';
        if ($value->chrLock != 'Y') {
            if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                if (Config::get('Constant.DEFAULT_TRASH') == 'Y') {
                    $log .= "<a title=\"Restore\" href='javascript:;' onclick='Restorefun(\"$value->id\",\"T\")'><i class=\"ri-repeat-line\"></i></a>";
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

        $statusdata = '';
        $days = MyLibrary::count_days($value->created_at);
        $days_modified = MyLibrary::count_days($value->updated_at);
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

        $records = array(
            ($isParent == 0) ? '<input type="checkbox" name="delete" class="chkDelete form-check-input" value="' . $value->id . '">' : $checkbox,
            '<div class="pages_title_div_row">' . $title . ' ' . $sector . '</div>',
            $parentCategoryTitle,
            $log,
        );
        return $records;
    }

    public function tableDataDraft($value = false, $moduleCmsPageData = false)
    {
        $sector = '';
        if (isset($value->varSector) && !empty($value->varSector)) {
            $sector = strtoupper($value->varSector);
        }

        $isParent = Interconnections::getCountById($value->id);
        $details = '';
        $parent_category_name = ' ';
        $publish_action = '';
        $titleData_delete = "";
        $titleData_publish = "";
        $details = '';

        if ($isParent > 0) {
            $titleData_delete = 'This interconnections is selected as parent interconnections in other record so it can&#39;t be deleted.';
            $titleData_publish = 'This interconnections is selected as parent interconnections in other record so it can&#39;t be published/unpublished.';
        }

        $details = '';
        $publish_action = '';
        if (Auth::user()->can('interconnections-edit')) {
            $details .= '<a class="" title="' . trans("interconnections::template.common.edit") . '" href="' . route('powerpanel.interconnections.edit', array('alias' => $value->id)) . '"><i class="ri-pencil-line"></i></a>';
        }

        if (Auth::user()->can('interconnections-delete') && $this->currentUserRoleData->chrIsAdmin == 'Y') {
            if (Config::get('Constant.DEFAULT_TRASH') == 'Y') {
                $details .= '<a class="delete-grid" title="' . trans("interconnections::template.common.delete") . '" onclick = \'Trashfun("' . $value->id . '")\' data-controller="InterconnectionsController" data-alias = "' . $value->id . '" data-tab="D"><i class="ri-delete-bin-line"></i></a>';
            } else {
                $details .= '<a class="delete" title="' . trans("interconnections::template.common.delete") . '" data-controller="InterconnectionsController" data-alias = "' . $value->id . '" data-tab="D"><i class="ri-delete-bin-line"></i></a>';
            }
        }

        //Bootstrap Switch
        $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/interconnections', 'data_alias'=>$value->id, 'title'=>trans("interconnections::template.common.unpublishedRecord"), 'data_value'=>'Publish', 'checked'=>'checked'])->render();
        
        $minus = '<span class="glyphicon glyphicon-minus"></span>';
        $category = '';

        $title = $value->varTitle;
        if (Auth::user()->can('interconnections-edit')) {
            $categoryRecordAlias = MyLibrary::getRecordAliasByModuleNameRecordId("interconnections-category", $value->txtCategories);
            if ($value->chrDraft == 'D' || $value->chrAddStar == 'Y') {
                $viewlink = url('/previewpage?url=' . MyLibrary::getFrontUri('interconnections')['uri'] . '/' . $value->id . '/preview/detail');
                $linkviewLable = "Preview";
            } else {
                $viewlink = MyLibrary::getFrontUri('interconnections')['uri'] . '/' . $value->alias->varAlias;
                $linkviewLable = "View";
            }

            if ($value->chrLock != 'Y') {
                if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                    $title = '<div class="quick_edit"><a href = "' . route('powerpanel.interconnections.edit', array('alias' => $value->id)) . '?tab=D">' . $value->varTitle . '</a> <div class="quick_edit_menu"> <span><a href="' . route('powerpanel.interconnections.edit', array('alias' => $value->id)) . '?tab=D" title="Edit">Edit</a></span>';
                    if (Config::get('Constant.DEFAULT_TRASH') == 'Y') {
                        $title .= '<span><a title = "Trash" href = \'javascript:;\' onclick=\'Trashfun("' . $value->id . '")\' class="red" data-tab="D">Trash</a></span>';
                    }
                    $title .= '<span><a href="' . $viewlink . '" target="_blank" title="' . $linkviewLable . '" >' . $linkviewLable . '</a></span>
																</div>
											 </div>';
                } else {
                    $title = '<div class="quick_edit"><a href = "' . route('powerpanel.interconnections.edit', array('alias' => $value->id)) . '?tab=D">' . $value->varTitle . '</a> <div class="quick_edit_menu">
														<span><a href="' . route('powerpanel.interconnections.edit', array('alias' => $value->id)) . '?tab=D" title="Edit">Edit</a></span>
														<span><a href="' . $viewlink . '" target="_blank" title="' . $linkviewLable . '" >' . $linkviewLable . '</a></span>
																</div>
											 </div>';
                }
            } else {
                if (auth()->user()->id != $value->LockUserID) {
                    if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                        $title = '<div class="quick_edit"><a href = "' . route('powerpanel.interconnections.edit', array('alias' => $value->id)) . '?tab=D">' . $value->varTitle . '</a> <div class="quick_edit_menu">
	                            <span><a href="' . route('powerpanel.interconnections.edit', array('alias' => $value->id)) . '?tab=D" title="Edit">Edit</a></span>';

                        $title .= '<span><a href = "' . $viewlink . '" target = "_blank" title = "' . $linkviewLable . '" >' . $linkviewLable . '</a></span></div></div>';
                    } else {
                        $title = '<div class="quick_edit"><a href = "javascript:;">' . $value->varTitle . '</a></div>';
                    }
                } else {
                    $title = '<div class="quick_edit"><a href = "' . route('powerpanel.interconnections.edit', array('alias' => $value->id)) . '?tab=D">' . $value->varTitle . '</a> <div class="quick_edit_menu">
	                            <span><a href="' . route('powerpanel.interconnections.edit', array('alias' => $value->id)) . '?tab=D" title="Edit">Edit</a></span>
	                            <span><a href="' . $viewlink . '" target="_blank" title="' . $linkviewLable . '" >' . $linkviewLable . '</a></span>
	                                </div>
	                        </div>';
                }
            }
        }
        $parentCategoryTitle = '-';
        if (!empty($value->intParentCategoryId) && $value->intParentCategoryId > 0) {
            $catIDS[] = $value->intParentCategoryId;
            $parentCategoryName = Interconnections::getParentCategoryNameBycatId($catIDS);
            $parentCategoryTitle = $parentCategoryName[0]->varTitle;
        }
        $logurl = url('powerpanel/log?id=' . $value->id . '&mid=' . Config::get('Constant.MODULE.ID'));
        $log = '';
        if ($value->chrLock != 'Y') {
            if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
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
                if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
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
                if ((auth()->user()->id == $value->LockUserID) || $this->currentUserRoleData->chrIsAdmin == 'Y') {
                    $publish_action = $publish_action;
                } else {
                    $publish_action = "---";
                }
            }
        }

        $statusdata = '';
        $days = MyLibrary::count_days($value->created_at);
        $days_modified = MyLibrary::count_days($value->updated_at);
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

        $records = array(
            '<input type="checkbox" name="delete" class="chkDelete form-check-input" value="' . $value->id . '">',
            '<div class="pages_title_div_row"><input type="hidden" id="draftid" value="' . $value->id . '">' . $title . ' ' . $status . $statusdata . ' ' . $sector . '</div>',
            $parentCategoryTitle,
            $publish_action,
            $log,
        );
        return $records;
    }

    /**
     * This method handels loading process of generating html menu from array data
     * @return  Html menu
     * @param  parentId, parentUrl, menu_array
     * @since   04-08-2017
     * @author  NetQuick
     */
    public function getChildren($CatId = false)
    {
        $serCats = Interconnections::where('intParentCategoryId', $CatId)->get();
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

    public function addCatAjax()
    {
        $data = Request::input();
        return AddCategoryAjax::Add($data, 'Interconnections');
    }

    public static function flushCache()
    {
        Cache::tags('Interconnections')->flush();
    }

    public function restructureData($elements, $filterArr)
    {
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
        }
        $stringIds = array_unique($stringIds);
        $fetchData = Interconnections::getRecordListforGridbyIds($stringIds, $filterArr);
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

    public function treerecurse($id, $indent, $list = array(), $children = array(), $maxlevel = '10', $level = 0, $Type = 1, $Order = '')
    {
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
    public static function newDisplayOrderAdd($order = null, $parentRecordId = false)
    {
        $response = false;
        $TotalRec = Interconnections::getRecordCounter($parentRecordId);
        if ($parentRecordId > 0) {
            if ($TotalRec >= $order) {
                Interconnections::UpdateDisplayOrder($order, $parentRecordId);
                $order = $order;
            } else {
                $order = $TotalRec + 1;
            }
        } else {
            if ($TotalRec >= $order) {
                Interconnections::UpdateDisplayOrder($order);
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
    public static function newSwapOrderEdit($order = null, $oldData = null)
    {
        $id = $oldData->id;
        $recCur = Interconnections::getRecordById($id);
        if (!empty($recCur)) {
            $parentRecordId = $recCur->intParentCategoryId;
            $TotalRec = Interconnections::getRecordCounter($parentRecordId);
            if ($parentRecordId > 0) {
                if ($TotalRec > $order) {
                    Interconnections::UpdateDisplayOrder($order, $parentRecordId);
                    $whereConditionsForCur = ['id' => $recCur['id']];
                    CommonModel::updateRecords($whereConditionsForCur, ['intDisplayOrder' => $order], false, 'Powerpanel\Interconnections\Models\Interconnections');
                } else {
                    $whereConditionsForCur = ['id' => $recCur['id']];
                    CommonModel::updateRecords($whereConditionsForCur, ['intDisplayOrder' => $TotalRec + 1], false, 'Powerpanel\Interconnections\Models\Interconnections');
                }
            } else {
                if ($TotalRec > $order) {
                    Interconnections::UpdateDisplayOrder($order);
                    $whereConditionsForCur = ['id' => $recCur['id']];
                    CommonModel::updateRecords($whereConditionsForCur, ['intDisplayOrder' => $order], false, 'Powerpanel\Interconnections\Models\Interconnections');
                } else {
                    $whereConditionsForCur = ['id' => $recCur['id']];
                    CommonModel::updateRecords($whereConditionsForCur, ['intDisplayOrder' => $TotalRec + 1], false, 'Powerpanel\Interconnections\Models\Interconnections');
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
    public static function newReOrderDisplayOrder($parentRecordId = false)
    {
        $response = false;
        $records = Interconnections::getRecordForReorderByParentId($parentRecordId);
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
                Interconnections::updateherarachyRecords($when, $ids);
            }
        }
    }

    /**
     * This method handle to get child record.
     * @since   27-Sep-2018
     * @author  NetQuick Team
     */
    public function getChildData(Request $request)
    {
        $requestArr = Request::all();
        $request = (object) $requestArr;
        $childHtml = "";
        $childData = "";
        $childData = Interconnections::getChildGrid($request->id);
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
                    $childHtml .= "<td class=\"text-center\"><span class='mob_show_title'>Edit: </span><a class='icon_round' title='" . trans("Interconnections::template.common.edit") . "' href='" . route('powerpanel.interconnections.edit', array('alias' => $child_row->id)) . "'>
							<i class='ri-pencil-line'></i></a></td>";
                } else {
                    $childHtml .= "<td class=\"text-center\"><span class='mob_show_title'>Edit: </span>-</td>";
                }
                if ($child_row->chrApproved == 'N') {
                    $childHtml .= "<td class=\"text-center\"><span class='mob_show_title'>Status: </span><a title='" . trans("Interconnections::template.common.comments") . "' href=\"javascript:;\" onclick=\"loadModelpopup('" . $child_row->id . "','" . $child_row->UserID . "','" . Config::get('Constant.MODULE.MODEL_NAME') . "','" . $child_row->fkMainRecord . "')\" class=\"approve_icon_btn\"><i class=\"ri-chat-1-line\"></i> <span>Comment</span></a>    <a  onclick=\"update_mainrecord('" . $child_row->id . "','" . $child_row->fkMainRecord . "','" . $child_row->UserID . "','A');\" title='" . trans("Interconnections::template.common.clickapprove") . "' href=\"javascript:;\" class=\"approve_icon_btn\"><i class=\"ri-checkbox-line\"></i> <span>Approve</span></a></td>";
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
    public function getChildData_rollback(Request $request)
    {
        $requestArr = Request::all();
        $request = (object) $requestArr;
        $child_rollbackHtml = "";
        $Publications_rollbackchildData = "";
        $Publications_rollbackchildData = Interconnections::getChildrollbackGrid($request);
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
                    //                         <i class=\"ri-history-line\"></i>  <span>RollBack</span>
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
     * This method handle to retrive comment.
     * @since   27-Sep-2018
     * @author  NetQuick Team
     */
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

    /**
     * This method handle to approve record request from ajax.
     * @since   27-Sep-2018
     * @author  NetQuick Team
     */
    public function ApprovedData_Listing(Request $request)
    {
        $requestArr = Request::all();
        $request = (object) $requestArr;
        $id = Request::post('id');
        $main_id = Request::post('main_id');
        $approvalid = Request::post('id');
        $approvalData = Interconnections::getOrderOfApproval($id);
        $flag = Request::post('flag');
        $main_id = Request::post('main_id');
        $Interconnections = Interconnections::getRecordById($main_id);
        $message = Interconnections::approved_data_Listing($request);
        $newCmsPageObj = Interconnections::getRecordForLogById($main_id);
        $approval_obj = Interconnections::getRecordForLogById($approvalid);
        if ($flag == 'R') {
            $restoredata = Config::get('Constant.ROLLBACK_RECORD');
        } else {
            $restoredata = Config::get('Constant.RECORD_APPROVED');
        }
        /* code for Reorder functionality */
        if (!empty($approvalData)) {
            self::newSwapOrderEdit($approvalData->intDisplayOrder, $Interconnections);
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

    public function get_buider_list()
    {
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
        $arrResults = Interconnections::getBuilderRecordList($filterArr);
        $found = $arrResults->toArray();
        if (!empty($found)) {
            foreach ($arrResults as $key => $value) {
                $rows .= $this->tableDataBuilder($value, false, $filterArr['selected']);
            }
        } else {
            $rows .= '<tr id="not-found"><td colspan="4" align="center">No records found.</td></tr>';
        }
        $iTotalRecords = CommonModel::getTotalRecordCount('Powerpanel\Interconnections\Models\Interconnections', true, false);
        $records["data"] = $rows;
        $records["found"] = count($found);
        $records["recordsTotal"] = $iTotalRecords;
        return json_encode($records);
    }

    public function tableDataBuilder($value = false, $fcnt = false, $selected = [])
    {
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

    public function getAllParents()
    {
        $records = Interconnections::getAllParents();
        $opt = '<option value="">Select Parents</option>';
        foreach ($records as $record) {
            $opt .= '<option value="' . $record->id . '">' . $record->varTitle . '</option>';
        }
        return $opt;
    }

    public function rollBackRecord(Request $request)
    {

        $message = 'Previous record is not available';
        $requestArr = Request::all();
        $request = (object) $requestArr;

        $previousRecord = Interconnections::getPreviousRecordByMainId($request->id);
        if (!empty($previousRecord)) {

            $main_id = $previousRecord->fkMainRecord;
            $request->id = $previousRecord->id;
            $request->main_id = $main_id;

            $message = Interconnections::approved_data_Listing($request);

            /* notification for user to record approved */
            $blogs = Interconnections::getRecordForLogById($previousRecord->id);
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
            $newBlogObj = Interconnections::getRecordForLogById($main_id);
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
}
