<?php

namespace Powerpanel\DecisionCategory\Controllers\Powerpanel;

use App\Alias;
use App\CommonModel;
use App\Helpers\AddQuickCategoryAjax;
use App\Helpers\MyLibrary;
use App\Helpers\ParentRecordHierarchy_builder;
use App\Http\Controllers\PowerpanelController;
use App\Log;
use App\Modules;
use App\Pagehit;
use App\RecentUpdates;
use App\User;
use App\UserNotification;
use Auth;
use Cache;
use Carbon\Carbon;
use Config;
use DB;
use File;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Powerpanel\DecisionCategory\Models\DecisionCategory;
use Powerpanel\Decision\Models\Decision;
use Powerpanel\RoleManager\Models\Role_user;
use Powerpanel\Workflow\Models\Comments;
use Powerpanel\Workflow\Models\Workflow;
use Powerpanel\Workflow\Models\WorkflowLog;
use Validator;

class DecisionCategoryController extends PowerpanelController
{

    private $userIsAdmin;

    public function __construct()
    {
        parent::__construct();
        if (isset($_COOKIE['locale'])) {
            app()->setLocale($_COOKIE['locale']);
        }
        $this->userIsAdmin = false;
        $this->MyLibrary = new MyLibrary();
        $this->CommonModel = new CommonModel();
    }

    /**
     * This method handels load process of DecisionCategory
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
        $categories = DecisionCategory::getParentCategoryFilterList(false);
        $iTotalRecords = DecisionCategory::getRecordCount(false, false, $userIsAdmin, $this->currentUserRoleSector);
        $draftTotalRecords = DecisionCategory::getRecordCountforListDarft(false, true, $userIsAdmin, array(), $this->currentUserRoleSector);
        $trashTotalRecords = DecisionCategory::getRecordCountforListTrash(false, false, $userIsAdmin, array(), $this->currentUserRoleSector);
        $favoriteTotalRecords = DecisionCategory::getRecordCountforListFavorite(false, false, $userIsAdmin, array(), $this->currentUserRoleSector);
        $NewRecordsCount = DecisionCategory::getNewRecordsCount($userIsAdmin, $this->currentUserRoleSector);
        $this->breadcrumb['title'] = trans('decision-category::template.decision_categoryModule.manageDecisionCategory');
        $breadcrumb = $this->breadcrumb;
        /* code for getting chart for parent categories */
        $decisionCategoryData = DecisionCategory::getRecordsForChart();
        $orgdata = array();
        if (!empty($decisionCategoryData) && count($decisionCategoryData) > 0) {
            foreach ($decisionCategoryData as $orgnization) {
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
        $orgdata = addslashes(json_encode($orgdata));
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
        return view('decision-category::powerpanel.index', compact('userIsAdmin','categories' ,'NewRecordsCount', 'iTotalRecords', 'breadcrumb', 'orgdata', 'draftTotalRecords', 'trashTotalRecords', 'favoriteTotalRecords', 'settingarray'));
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
            $decisionCategory = DecisionCategory::getParentCategoryFilterList($sectorname);
        } else {
            $decisionCategory = DecisionCategory::getParentCategoryFilterList(false);
        }
        $recordSelect = '<option value=" ">--Select Parent Category--</option>';

        foreach ($decisionCategory as $cat) {

            $recordSelect .= '<option  value="' . $cat->id . '">' . ucwords($cat->varTitle) . '</option>';
        }
        return $recordSelect;
    }
    /**
     * This method stores DecisionCategory modifications
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
        } else {
            $userIsAdmin = true;
        }
        $data = Request::input();
        $settings = json_decode(Config::get("Constant.MODULE.SETTINGS"));
        $rules = array(
            'title' => 'required|max:160|handle_xss|no_url',
            'sector' => 'required',
            'display_order' => 'required|greater_than_zero|handle_xss|no_url',
            'parent_category_id' => 'required',
            // 'varMetaTitle' => 'required|max:160|handle_xss|no_url',
            // 'varMetaDescription' => 'required|max:200|handle_xss|no_url',
            // 'alias' => 'required',
        );
        $messsages = array(
            'title.required' => 'Name field is required.',
            'sector.required' => 'Sector field is required.',
            'display_order.required' => trans('decision-category::template.decision_categoryModule.displayOrder'),
            'display_order.greater_than_zero' => trans('decision-category::template.decision_categoryModule.displayGreaterThan'),
            // 'varMetaTitle.required' => trans('decision-category::template.decision_categoryModule.metaTitle'),
            // 'varMetaDescription.required' => trans('decision-category::template.decision_categoryModule.metaDescription'),
        );
        $validator = Validator::make($data, $rules, $messsages);
        if ($validator->passes()) {
            $DecisionCategoryArr = [];
            $module = Modules::getModuleById(Config::get('Constant.MODULE.ID'));
            if (isset($this->currentUserRoleData)) {
                $currentUserRoleData = $this->currentUserRoleData;
            }
            $id = Request::segment(3);
            $actionMessage = trans('decision-category::template.common.oppsSomethingWrong');
            if (is_numeric($id)) { #Edit post Handler=======
            $DecisionCategory = DecisionCategory::getRecordForLogById($id);
                if ($data['oldAlias'] != $data['alias']) {
                    Alias::updateAlias($data['oldAlias'], $data['alias']);
                }
                if (Config::get('Constant.CHRSearchRank') == 'Y') {
                    $searchrank = $data['search_rank'];
                }

                $startdate = !empty($data['start_date_time']) ? date('Y-m-d H:i:s', strtotime($data['start_date_time'])) : date('Y-m-d H:i:s');
                $enddate = !empty($data['end_date_time']) ? date('Y-m-d H:i:s', strtotime($data['end_date_time'])) : null;

                $updateDecisionCategoryFields = [
                    'varTitle' => stripslashes(trim($data['title'])),
                    'intParentCategoryId' => $data['parent_category_id'],
                    'varSector' => $data['sector'],
                    'chrPublish' => isset($data['chrMenuDisplay']) ? $data['chrMenuDisplay'] : 'Y',
                    'txtDescription' => $data['description'],
                    'dtDateTime' => $startdate,
                    'dtEndDateTime' => $enddate,
                    'varMetaTitle' => stripslashes(trim($data['varMetaTitle'])),
                    'varMetaDescription' => stripslashes(trim($data['varMetaDescription'])),
                    'varTags' => trim($data['tags']),
                    'intSearchRank' => $data['search_rank'],
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
                $whereConditions = ['id' => $DecisionCategory->id];
                if ($data['chrMenuDisplay'] == 'D') {
                    $updateDecisionCategoryFields['chrDraft'] = 'D';
                    $updateDecisionCategoryFields['chrPublish'] = 'N';
                } else {
                    $updateDecisionCategoryFields['chrDraft'] = 'N';
                    $updateDecisionCategoryFields['chrPublish'] = $data['chrMenuDisplay'];
                }
                if (isset($data['chrPageActive']) && $data['chrPageActive'] != '') {
                    $updateDecisionCategoryFields['chrPageActive'] = $data['chrPageActive'];
                }
                if (isset($data['chrPageActive']) && $data['chrPageActive'] == 'PP') {
                    $updateDecisionCategoryFields['varPassword'] = $data['new_password'];
                } else {
                    $updateDecisionCategoryFields['varPassword'] = '';
                }
                if ($data['chrMenuDisplay'] == 'D') {
                    $addlog = Config::get('Constant.UPDATE_DRAFT');
                } else {
                    $addlog = '';
                }
                if ($DecisionCategory->chrLock == 'Y' && auth()->user()->id != $DecisionCategory->LockUserID) {
                    if ($this->currentUserRoleData->chrIsAdmin != 'Y') {
                        $lockedUserData = User::getRecordById($DecisionCategory->LockUserID, true);
                        $lockedUserName = 'someone';
                        if (!empty($lockedUserData)) {
                            $lockedUserName = $lockedUserData->name;
                        }
                        $actionMessage = "This record has been locked by " . $lockedUserName . ".";
                        return redirect()->route('powerpanel.decision-category.index')->with('message', $actionMessage);
                    }
                }
                if (File::exists(app_path() . '/Workflow.php') != null || File::exists(base_path() . '/packages/Powerpanel/Workflow/src/Models/Workflow.php') != null) {
                    if (!$userIsAdmin) {
                        $userRole = $currentUserRoleData->id;
                    } else {
                        $userRoleData = Role_user::getUserRoleByUserId($DecisionCategory->UserID);
                        if (isset($userRoleData->role_id)) {
                            $userRole = $userRoleData->role_id;
                        } else {
                            $userRole = $this->currentUserRoleData->id;
                        }
                    }
                    if ($data['chrMenuDisplay'] == 'D') {
                        DB::table('menu')->where('intPageId', $id)->where('intfkModuleId', Config::get('Constant.MODULE.ID'))->delete();
                    }
                    $workFlowByCat = Workflow::getRecordByCategoryId($module->intFkGroupCode, $userRole, Config::get('Constant.MODULE.ID'));
                    if (empty($workFlowByCat->varUserId) || $userIsAdmin || $workFlowByCat->charNeedApproval == 'N') {
//                        if ($data['oldAlias'] != $data['alias']) {
                        //                            Alias::updateAlias($data['oldAlias'], $data['alias']);
                        //                            MyLibrary::updateAliasInMenu($data['alias'], $DecisionCategory->id, 'after');
                        ////            echo 'hi';exit;
                        //                        }
                        if ($DecisionCategory->fkMainRecord === 0 || empty($workFlowByCat->varUserId)) {
                            $update = CommonModel::updateRecords($whereConditions, $updateDecisionCategoryFields, false, 'Powerpanel\DecisionCategory\Models\DecisionCategory');
                            if ($update) {
                                if (!empty($id)) {
                                    $newCmsPageObj = DecisionCategory::getRecordForLogById($id);
                                    #Update record in menu
                                    $whereConditions = ['intfkModuleId' => Config::get('Constant.MODULE.ID'), 'intRecordId' => $id];
                                    $updateMenuFields = [
                                        'varTitle' => $data['title'],
                                    ];
                                    CommonModel::updateRecords($whereConditions, $updateMenuFields, false, '\\Powerpanel\\Menu\\Models\\Menu');
                                    #Update record in menu
                                    self::newSwapOrderEdit($data['display_order'], $DecisionCategory);
                                    $logArr = MyLibrary::logData($DecisionCategory->id);
                                    if (Auth::user()->can('log-advanced')) {
                                        $newDecisionCategoryObj = DecisionCategory::getRecordForLogById($DecisionCategory->id);
                                        $oldRec = $this->recordHistory($DecisionCategory);
                                        $newRec = $this->newrecordHistory($DecisionCategory, $newDecisionCategoryObj);
                                        $logArr['old_val'] = $oldRec;
                                        $logArr['new_val'] = $newRec;
                                    }
                                    $logArr['varTitle'] = stripslashes(trim($data['title']));
                                    Log::recordLog($logArr);
                                    if (Auth::user()->can('recent-updates-list')) {
                                        if (!isset($newDecisionCategoryObj)) {
                                            $newDecisionCategoryObj = DecisionCategory::getRecordForLogById($DecisionCategory->id);
                                        }
                                        $notificationArr = MyLibrary::notificationData($DecisionCategory->id, $newDecisionCategoryObj);
                                        RecentUpdates::setNotification($notificationArr);
                                    }
                                    if (isset($data['saveandexit']) && $data['saveandexit'] == 'approvesaveandexit') {
                                        $actionMessage = trans('decision-category::template.common.recordApprovalMessage');
                                    } else {
                                        $actionMessage = trans('decision-category::template.decision_categoryModule.successMessage');
                                    }
                                }
                            }
                        } else {
                            $newCmsPageObj = DecisionCategory::getRecordForLogById($id);
                            #Update record in menu
                            $whereConditions = ['intfkModuleId' => Config::get('Constant.MODULE.ID'), 'intRecordId' => $newCmsPageObj->fkMainRecord];
                            $updateMenuFields = [
                                'varTitle' => $newCmsPageObj->varTitle,
                            ];
                            CommonModel::updateRecords($whereConditions, $updateMenuFields, false, '\\Powerpanel\\Menu\\Models\\Menu');
                            #Update record in menu

                            $updateModuleFields = $updateDecisionCategoryFields;
                            $this->insertApprovedRecord($updateModuleFields, $data, $id);
                            if (isset($data['saveandexit']) && $data['saveandexit'] == 'approvesaveandexit') {
                                $actionMessage = trans('decision-category::template.common.recordApprovalMessage');
                            } else {
                                $actionMessage = trans('decision-category::template.decision_categoryModule.successMessage');
                            }
                        }
                    } else { #Add post Handler=======
                    if ($workFlowByCat->charNeedApproval == 'Y') {
                        $this->insertApprovalRecord($DecisionCategory, $data, $updateDecisionCategoryFields);
                        if (isset($data['saveandexit']) && $data['saveandexit'] == 'approvesaveandexit') {
                            $actionMessage = trans('decision-category::template.common.recordApprovalMessage');
                        } else {
                            $actionMessage = trans('decision-category::template.decision_categoryModule.successMessage');
                        }
                    }
                    }
                } else {
                    $update = CommonModel::updateRecords($whereConditions, $updateDecisionCategoryFields, false, 'Powerpanel\DecisionCategory\Models\DecisionCategory');
                    $actionMessage = trans('decision-category::template.decision_categoryModule.successMessage');
                }
            } else { #Add post Handler=======
            if (File::exists(app_path() . '/Workflow.php') != null || File::exists(base_path() . '/packages/Powerpanel/Workflow/src/Models/Workflow.php') != null) {
                $workFlowByCat = Workflow::getRecordByCategoryId($module->intFkGroupCode, $currentUserRoleData->id, Config::get('Constant.MODULE.ID'));
            }
                if (!empty($workFlowByCat->varUserId) && $workFlowByCat->chrNeedAddPermission == 'Y' && !$userIsAdmin) {

                    $DecisionCategoryArr['chrPublish'] = 'N';
                    $DecisionCategoryArr['chrDraft'] = 'N';
                    $DecisionCategory = $this->insertNewRecord($data, $DecisionCategoryArr);
                    if ($data['chrMenuDisplay'] == 'D') {
                        $DecisionCategoryArr['chrDraft'] = 'D';
                    }
                    $DecisionCategoryArr['chrPublish'] = 'Y';
                    $this->insertApprovalRecord($DecisionCategory, $data, $DecisionCategoryArr);
                } else {
                    $DecisionCategory = $this->insertNewRecord($data, $DecisionCategoryArr);
                }
                if (isset($data['saveandexit']) && $data['saveandexit'] == 'approvesaveandexit') {
                    $actionMessage = trans('decision-category::template.common.recordApprovalMessage');
                } else {
                    $actionMessage = trans('decision-category::template.decision_categoryModule.addedMessage');
                }
                $id = $DecisionCategory->id;
            }
            $this->flushCache();
            if ((!empty($data['saveandexit']) && $data['saveandexit'] == 'saveandexit') || !$userIsAdmin) {
                if ($data['chrMenuDisplay'] == 'D') {
                    return redirect()->route('powerpanel.decision-category.index', 'tab=D')->with('message', $actionMessage);
                } else {
                    return redirect()->route('powerpanel.decision-category.index')->with('message', $actionMessage);
                }
            } else {
                return redirect()->route('powerpanel.decision-category.edit', $id)->with('message', $actionMessage);
            }
        } else {
            return Redirect::back()->withErrors($validator)->withInput();
        }
    }

    public function addPreview()
    {
        $postArr = Request::post();
        $id = $postArr['previewId'];
        if (is_numeric($id)) { #Edit post Handler=======
        $DecisionCategory = DecisionCategory::getRecordForLogById($id);
            if (Config::get('Constant.CHRSearchRank') == 'Y') {
                $searchrank = $postArr['search_rank'];
            }

            $startdate = !empty($postArr['start_date_time']) ? date('Y-m-d H:i:s', strtotime($postArr['start_date_time'])) : date('Y-m-d H:i:s');
            $enddate = !empty($postArr['end_date_time']) ? date('Y-m-d H:i:s', strtotime($postArr['end_date_time'])) : null;

            $updateDecisionCategoryFields = [
                'varTitle' => stripslashes(trim($postArr['title'])),
                'intParentCategoryId' => $postArr['parent_category_id'],
                'chrPublish' => isset($postArr['chrMenuDisplay']) ? $postArr['chrMenuDisplay'] : 'Y',
                'dtDateTime' => $startdate,
                'dtEndDateTime' => $enddate,
                'varMetaTitle' => stripslashes(trim($postArr['varMetaTitle'])),
                'varMetaDescription' => stripslashes(trim($postArr['varMetaDescription'])),
                'varTags' => trim($postArr['tags']),
                'intSearchRank' => $searchrank,
                'updated_at' => date('Y-m-d H:i:s'),
                'chrIsPreview' => 'Y',
            ];
            $whereConditions = ['id' => $id];
            if ($postArr['oldAlias'] != $postArr['alias']) {
                Alias::updateAlias($postArr['oldAlias'], $postArr['alias']);
            }
            $update = CommonModel::updateRecords($whereConditions, $updateDecisionCategoryFields, false, 'Powerpanel\DecisionCategory\Models\DecisionCategory');
        } else {
            $decisionArr['intSearchRank'] = $postArr['search_rank'];
            $DecisionCategoryArr = ['chrIsPreview' => 'Y'];
            $id = $this->insertNewRecord($postArr, $DecisionCategoryArr, 'Y')->id;
        }
        return json_encode(array('status' => $id, 'alias' => $postArr['alias'], 'message' => trans('decision-category::template.pageModule.pageUpdate')));
    }

    public function insertNewRecord($data, $DecisionCategoryArr, $preview = 'N')
    {
        $response = false;
        $DecisionCategoryArr['intAliasId'] = MyLibrary::insertAlias($data['alias'], false, $preview);
        $DecisionCategoryArr['varTitle'] = stripslashes(trim($data['title']));
        $DecisionCategoryArr['varSector'] = $data['sector'];
        $DecisionCategoryArr['dtDateTime'] = !empty($data['start_date_time']) ? date('Y-m-d H:i:s', strtotime($data['start_date_time'])) : date('Y-m-d H:i:s');
        $DecisionCategoryArr['dtEndDateTime'] = !empty($data['end_date_time']) ? date('Y-m-d H:i:s', strtotime($data['end_date_time'])) : null;

        $DecisionCategoryArr['intDisplayOrder'] = ($preview == 'Y') ? '0' : self::newDisplayOrderAdd($data['display_order'], $data['parent_category_id']);
        $DecisionCategoryArr['intParentCategoryId'] = $data['parent_category_id'];
        $DecisionCategoryArr['txtDescription'] = $data['description'];
        $DecisionCategoryArr['varMetaTitle'] = stripslashes(trim($data['varMetaTitle']));
        $DecisionCategoryArr['varMetaDescription'] = stripslashes(trim($data['varMetaDescription']));
        $DecisionCategoryArr['varTags'] = trim($data['tags']);
        $DecisionCategoryArr['created_at'] = Carbon::now();
        $DecisionCategoryArr['UserID'] = auth()->user()->id;
        $DecisionCategoryArr['chrMain'] = 'Y';
        if (Config::get('Constant.CHRSearchRank') == 'Y') {
            $DecisionCategoryArr['intSearchRank'] = $data['search_rank'];
        }
        $DecisionCategoryArr['created_at'] = date('Y-m-d H:i:s');
        $DecisionCategoryArr['updated_at'] = date('Y-m-d H:i:s');
        if ($data['chrMenuDisplay'] == 'D') {
            $DecisionCategoryArr['chrDraft'] = 'D';
            $DecisionCategoryArr['chrPublish'] = 'N';
        } else {
            $DecisionCategoryArr['chrDraft'] = 'N';
        }
        if (isset($data['chrPageActive']) && $data['chrPageActive'] != '') {
            $DecisionCategoryArr['chrPageActive'] = $data['chrPageActive'];
        }
        if (isset($data['chrPageActive']) && $data['chrPageActive'] == 'PP') {
            $DecisionCategoryArr['varPassword'] = $data['new_password'];
        } else {
            $DecisionCategoryArr['varPassword'] = '';
        }
        if ($data['chrMenuDisplay'] == 'D') {
            $addlog = Config::get('Constant.ADDED_DRAFT');
        } else {
            $addlog = '';
        }
        $DecisionCategoryID = CommonModel::addRecord($DecisionCategoryArr, 'Powerpanel\DecisionCategory\Models\DecisionCategory');
        if (!empty($DecisionCategoryID)) {
            self::newReOrderDisplayOrder($data['parent_category_id']);
            $id = $DecisionCategoryID;
            $newDecisionCategoryObj = DecisionCategory::getRecordForLogById($id);
            $logArr = MyLibrary::logData($id);
            $logArr['varTitle'] = stripslashes($newDecisionCategoryObj->varTitle);
            Log::recordLog($logArr);
            if (Auth::user()->can('recent-updates-list')) {
                $notificationArr = MyLibrary::notificationData($id, $newDecisionCategoryObj);
                RecentUpdates::setNotification($notificationArr);
            }
            $response = $newDecisionCategoryObj;
        }
        return $response;
    }

    public function insertApprovalRecord($DecisionCategory, $data, $updateDecisionCategoryFields)
    {
        $DecisionCategoryArr['UserID'] = auth()->user()->id;
        $DecisionCategoryArr['chrMain'] = 'N';
        $DecisionCategoryArr['fkMainRecord'] = $DecisionCategory->id;
        $DecisionCategoryArr['chrLetest'] = 'Y';

        $DecisionCategoryArr['dtDateTime'] = !empty($data['start_date_time']) ? date('Y-m-d H:i:s', strtotime($data['start_date_time'])) : date('Y-m-d H:i:s');
        $DecisionCategoryArr['dtEndDateTime'] = !empty($data['end_date_time']) ? date('Y-m-d H:i:s', strtotime($data['end_date_time'])) : null;
         $DecisionCategoryArr['varSector'] = $data['sector'];
        $DecisionCategoryArr['intAliasId'] = MyLibrary::insertAlias($data['alias'], false, 'N');
        $DecisionCategoryArr['varTitle'] = stripslashes(trim($data['title']));
        $DecisionCategoryArr['intDisplayOrder'] = $data['display_order'];
        $DecisionCategoryArr['intParentCategoryId'] = $data['parent_category_id'];
        $DecisionCategoryArr['txtDescription'] = $data['description'];
        $DecisionCategoryArr['varMetaTitle'] = stripslashes(trim($data['varMetaTitle']));
        $DecisionCategoryArr['varMetaDescription'] = stripslashes(trim($data['varMetaDescription']));
        $DecisionCategoryArr['varTags'] = trim($data['tags']);
        if (Config::get('Constant.CHRSearchRank') == 'Y') {
            $DecisionCategoryArr['intSearchRank'] = $data['search_rank'];
        }
        $DecisionCategoryArr['chrPublish'] = isset($data['chrMenuDisplay']) ? $data['chrMenuDisplay'] : 'Y';
        $DecisionCategoryArr['created_at'] = date('Y-m-d H:i:s');
        $DecisionCategoryArr['updated_at'] = date('Y-m-d H:i:s');
        if ($data['chrMenuDisplay'] == 'D') {
            $DecisionCategoryArr['chrDraft'] = 'D';
            $DecisionCategoryArr['chrPublish'] = 'N';
        } else {
            $DecisionCategoryArr['chrDraft'] = 'N';
            $DecisionCategoryArr['chrPublish'] = $data['chrMenuDisplay'];
        }
        if (isset($data['chrPageActive']) && $data['chrPageActive'] != '') {
            $DecisionCategoryArr['chrPageActive'] = $data['chrPageActive'];
        }
        if (isset($data['chrPageActive']) && $data['chrPageActive'] == 'PP') {
            $DecisionCategoryArr['varPassword'] = $data['new_password'];
        } else {
            $DecisionCategoryArr['varPassword'] = '';
        }
        if ($data['chrMenuDisplay'] == 'D') {
            $addlog = Config::get('Constant.DRAFT_SENT_FOR_APPROVAL');
        } else {
            $addlog = Config::get('Constant.SENT_FOR_APPROVAL');
        }
        $DecisionCategoryID = CommonModel::addRecord($DecisionCategoryArr, 'Powerpanel\DecisionCategory\Models\DecisionCategory');
        if (!empty($DecisionCategoryID)) {
            $id = $DecisionCategoryID;
            WorkflowLog::addRecord([
                'fkModuleId' => Config::get('Constant.MODULE.ID'),
                'fkRecordId' => $DecisionCategory->id,
                'charApproval' => 'Y',
            ]);
            if (method_exists($this->MyLibrary, 'userNotificationData')) {
                $userNotificationArr = MyLibrary::userNotificationData(Config::get('Constant.MODULE.ID'));
                $userNotificationArr['fkRecordId'] = $DecisionCategory->id;
                $userNotificationArr['txtNotification'] = 'New approval request from ' . ucfirst(auth()->user()->name) . ' (' . ucfirst(Config::get('Constant.MODULE.NAME')) . ')';
                $userNotificationArr['fkIntUserId'] = Auth::user()->id;
                $userNotificationArr['chrNotificationType'] = 'A';
                UserNotification::addRecord($userNotificationArr);
            }
            $newDecisionCategoryObj = DecisionCategory::getRecordForLogById($id);
            $logArr = MyLibrary::logData($id, false, $addlog);
            $logArr['varTitle'] = $newDecisionCategoryObj->varTitle;
            Log::recordLog($logArr);
            if (Auth::user()->can('recent-updates-list')) {
                $notificationArr = MyLibrary::notificationData($id, $newDecisionCategoryObj);
                RecentUpdates::setNotification($notificationArr);
            }
            $actionMessage = trans('decision-category::template.decision_categoryModule.successMessage');
        }
        $whereConditionsAddstar = ['id' => $DecisionCategory->id];
        $updateAddStar = [
            'chrAddStar' => 'Y',
        ];
        CommonModel::updateRecords($whereConditionsAddstar, $updateAddStar, false, 'Powerpanel\DecisionCategory\Models\DecisionCategory');
    }

    public function insertApprovedRecord($updateDecisionCategoryFields, $data, $id)
    {
        $whereConditions = ['id' => $data['fkMainRecord']];
        $updateDecisionCategoryFields['chrAddStar'] = 'N';
        $updateDecisionCategoryFields['chrLetest'] = 'N';
        $updateDecisionCategoryFields['updated_at'] = date('Y-m-d H:i:s');
        $update = CommonModel::updateRecords($whereConditions, $updateDecisionCategoryFields, false, 'Powerpanel\DecisionCategory\Models\DecisionCategory');
        $DecisionCategory = DecisionCategory::getRecordForLogById($data['fkMainRecord']);
        self::newSwapOrderEdit($data['display_order'], $DecisionCategory);
        $whereConditions_ApproveN = ['fkMainRecord' => $data['fkMainRecord']];
        $updateToApproveN = [
            'chrApproved' => 'N',
            'intApprovedBy' => '0',
        ];
        $update = CommonModel::updateRecords($whereConditions_ApproveN, $updateToApproveN, false, 'Powerpanel\DecisionCategory\Models\DecisionCategory');
        $whereConditionsApprove = ['id' => $id, 'chrMain' => 'N'];
        $updateToApprove = [
            'chrApproved' => 'Y',
            'chrRollBack' => 'Y',
            'intApprovedBy' => auth()->user()->id,
        ];
        $update = CommonModel::updateRecords($whereConditionsApprove, $updateToApprove, false, 'Powerpanel\DecisionCategory\Models\DecisionCategory');
        if ($data['chrMenuDisplay'] == 'D') {
            $addlog = Config::get('Constant.DRAFT_RECORD_APPROVED');
        } else {
            $addlog = Config::get('Constant.RECORD_APPROVED');
        }
        $newCmsPageObj = DecisionCategory::getRecordForLogById($id);
        $logArr = MyLibrary::logData($id, false, $addlog);
        $logArr['varTitle'] = stripslashes($newCmsPageObj->varTitle);
        Log::recordLog($logArr);
        /* notification for user to record approved */
        $careers = DecisionCategory::getRecordForLogById($id);
        if (method_exists($this->MyLibrary, 'userNotificationData')) {
            $userNotificationArr = MyLibrary::userNotificationData(Config::get('Constant.MODULE.ID'));
            $userNotificationArr['fkRecordId'] = $id;
            $userNotificationArr['txtNotification'] = 'Your request has been approved by ' . ucfirst(auth()->user()->name) . ' (' . ucfirst(Config::get('Constant.MODULE.NAME')) . ')';
            $userNotificationArr['fkIntUserId'] = Auth::user()->id;
            $userNotificationArr['chrNotificationType'] = 'A';
            $userNotificationArr['intOnlyForUserId'] = $careers->UserID;
            UserNotification::addRecord($userNotificationArr);
            /* notification for user to record approved */
        }
        if ($update) {
            if ($id > 0 && !empty($id)) {
                $where = [];
                $flowData = [];
                $flowData['dtYes'] = Config::get('Constant.SQLTIMESTAMP');
                $where['fkModuleId'] = Config::get('Constant.MODULE.ID');
                $where['fkRecordId'] = (isset($data['fkMainRecord']) && (int) $data['fkMainRecord'] != 0) ? $data['fkMainRecord'] : $id;
                $where['dtYes'] = 'null';
                WorkflowLog::updateRecord($flowData, $where);
                self::flushCache();
            }
        }
    }

    /**
     * This method loads DecisionCategory table data on view
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
        $filterArr['customFilterIdentity'] = !empty(Request::get('customFilterIdentity')) ? Request::get('customFilterIdentity') : '';
        $filterArr['statusFilter'] = !empty(Request::input('statusValue')) ? Request::input('statusValue') : '';
        $filterArr['sectorFilter'] = !empty(Request::input('sectorValue')) ? Request::input('sectorValue') : '';
        $filterArr['searchFilter'] = !empty(Request::input('searchValue')) ? Request::input('searchValue') : '';
        $filterArr['ParentCategoryFilter'] = !empty(Request::input('parentcatValue')) ? Request::input('parentcatValue') : '';
        $filterArr['personalityFilter'] = !empty(Request::input('personalityFilter')) ? Request::input('personalityFilter') : '';
        $filterArr['paymentFilter'] = !empty(Request::input('paymentFilter')) ? Request::input('paymentFilter') : '';
        $filterArr['rangeFilter'] = !empty(Request::input('rangeFilter')) ? Request::input('rangeFilter') : '';
        $filterArr['iDisplayLength'] = intval(Request::input('length'));
        $filterArr['iDisplayStart'] = intval(Request::input('start'));
        $sEcho = intval(Request::input('draw'));
        $isAdmin = false;
        if (!empty($this->currentUserRoleData)) {
            if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                $isAdmin = true;
            }
        }
        $arrResults = DecisionCategory::getRecordListforDecisionCategoryGrid($filterArr, 'Y', $isAdmin, $this->currentUserRoleSector);
        $arrResults = $this->restructureData($arrResults, $filterArr);
        $iTotalRecords = DecisionCategory::getRecordCountforList($filterArr, true, $isAdmin, $this->currentUserRoleSector);
        $end = $filterArr['iDisplayStart'] + $filterArr['iDisplayLength'];
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;
        if (!empty($arrResults)) {
            foreach ($arrResults as $key => $value) {
                $records["data"][] = $this->tableData($value);
            }
        }
        $NewRecordsCount = DecisionCategory::getNewRecordsCount($isAdmin, $this->currentUserRoleSector);
        $records["newRecordCount"] = $NewRecordsCount;
        $records["customActionStatus"] = "OK";
        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;
        return json_encode($records);
    }

    /**
     * This method loads DecisionCategory table data on view
     * @return  View
     * @since   2017-11-10
     * @author  NetQuick
     */
    public function get_list_draft()
    {
        $filterArr = [];
        $records = [];
        $records["data"] = [];
        $filterArr['orderColumnNo'] = (!empty(Request::input('order')[0]['column']) ? Request::input('order')[0]['column'] : '');
        $filterArr['orderByFieldName'] = (!empty(Request::input('columns')[$filterArr['orderColumnNo']]['name']) ? Request::input('columns')[$filterArr['orderColumnNo']]['name'] : '');
        $filterArr['orderTypeAscOrDesc'] = (!empty(Request::input('order')[0]['dir']) ? Request::input('order')[0]['dir'] : '');
        $filterArr['customFilterIdentity'] = !empty(Request::get('customFilterIdentity')) ? Request::get('customFilterIdentity') : '';
        $filterArr['statusFilter'] = !empty(Request::input('statusValue')) ? Request::input('statusValue') : '';
        $filterArr['sectorFilter'] = !empty(Request::input('sectorValue')) ? Request::input('sectorValue') : '';
        $filterArr['searchFilter'] = !empty(Request::input('searchValue')) ? Request::input('searchValue') : '';
        $filterArr['DecisionCategoryFilter'] = !empty(Request::input('DecisionCategoryFilter')) ? Request::input('DecisionCategoryFilter') : '';
        $filterArr['personalityFilter'] = !empty(Request::input('personalityFilter')) ? Request::input('personalityFilter') : '';
        $filterArr['paymentFilter'] = !empty(Request::input('paymentFilter')) ? Request::input('paymentFilter') : '';
        $filterArr['rangeFilter'] = !empty(Request::input('rangeFilter')) ? Request::input('rangeFilter') : '';
        $filterArr['iDisplayLength'] = intval(Request::input('length'));
        $filterArr['iDisplayStart'] = intval(Request::input('start'));
        $sEcho = intval(Request::input('draw'));
        $isAdmin = false;
        if (!empty($this->currentUserRoleData)) {
            if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                $isAdmin = true;
            }
        }
        $arrResults = DecisionCategory::getDraftRecordListforDecisionCategoryGrid($filterArr, 'Y', $isAdmin, $this->currentUserRoleSector);
        $iTotalRecords = DecisionCategory::getRecordCountforListDarft($filterArr, true, $isAdmin, array(), $this->currentUserRoleSector);
        $end = $filterArr['iDisplayStart'] + $filterArr['iDisplayLength'];
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;
        if (!empty($arrResults)) {
            foreach ($arrResults as $key => $value) {
                $records["data"][] = $this->tableDataDraft($value);
            }
        }
        $NewRecordsCount = DecisionCategory::getNewRecordsCount($isAdmin, $this->currentUserRoleSector);
        $records["newRecordCount"] = $NewRecordsCount;
        $records["customActionStatus"] = "OK";
        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;
        return json_encode($records);
    }

    /**
     * This method loads DecisionCategory table data on view
     * @return  View
     * @since   2017-11-10
     * @author  NetQuick
     */
    public function get_list_favorite()
    {
        $filterArr = [];
        $records = [];
        $records["data"] = [];
        $filterArr['orderColumnNo'] = (!empty(Request::input('order')[0]['column']) ? Request::input('order')[0]['column'] : '');
        $filterArr['orderByFieldName'] = (!empty(Request::input('columns')[$filterArr['orderColumnNo']]['name']) ? Request::input('columns')[$filterArr['orderColumnNo']]['name'] : '');
        $filterArr['orderTypeAscOrDesc'] = (!empty(Request::input('order')[0]['dir']) ? Request::input('order')[0]['dir'] : '');
        $filterArr['customFilterIdentity'] = !empty(Request::get('customFilterIdentity')) ? Request::get('customFilterIdentity') : '';
        $filterArr['statusFilter'] = !empty(Request::input('statusValue')) ? Request::input('statusValue') : '';
        $filterArr['sectorFilter'] = !empty(Request::input('sectorValue')) ? Request::input('sectorValue') : '';
        $filterArr['searchFilter'] = !empty(Request::input('searchValue')) ? Request::input('searchValue') : '';
        $filterArr['DecisionCategoryFilter'] = !empty(Request::input('DecisionCategoryFilter')) ? Request::input('DecisionCategoryFilter') : '';
        $filterArr['personalityFilter'] = !empty(Request::input('personalityFilter')) ? Request::input('personalityFilter') : '';
        $filterArr['paymentFilter'] = !empty(Request::input('paymentFilter')) ? Request::input('paymentFilter') : '';
        $filterArr['rangeFilter'] = !empty(Request::input('rangeFilter')) ? Request::input('rangeFilter') : '';
        $filterArr['iDisplayLength'] = intval(Request::input('length'));
        $filterArr['iDisplayStart'] = intval(Request::input('start'));
        $sEcho = intval(Request::input('draw'));
        $isAdmin = false;
        if (!empty($this->currentUserRoleData)) {
            if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                $isAdmin = true;
            }
        }
        $arrResults = DecisionCategory::getFavoriteRecordListforDecisionCategoryGrid($filterArr, 'Y', $isAdmin, $this->currentUserRoleSector);
        $iTotalRecords = DecisionCategory::getRecordCountforListFavorite($filterArr, true, $isAdmin, array(), $this->currentUserRoleSector);
        $end = $filterArr['iDisplayStart'] + $filterArr['iDisplayLength'];
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;
        if (!empty($arrResults)) {
            foreach ($arrResults as $key => $value) {
                $records["data"][] = $this->tableDataFavorite($value);
            }
        }
        $NewRecordsCount = DecisionCategory::getNewRecordsCount($isAdmin, $this->currentUserRoleSector);
        $records["newRecordCount"] = $NewRecordsCount;
        $records["customActionStatus"] = "OK";
        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;
        return json_encode($records);
    }

    /**
     * This method loads DecisionCategory table data on view
     * @return  View
     * @since   2017-11-10
     * @author  NetQuick
     */
    public function get_list_trash()
    {
        $filterArr = [];
        $records = [];
        $records["data"] = [];
        $filterArr['orderColumnNo'] = (!empty(Request::input('order')[0]['column']) ? Request::input('order')[0]['column'] : '');
        $filterArr['orderByFieldName'] = (!empty(Request::input('columns')[$filterArr['orderColumnNo']]['name']) ? Request::input('columns')[$filterArr['orderColumnNo']]['name'] : '');
        $filterArr['orderTypeAscOrDesc'] = (!empty(Request::input('order')[0]['dir']) ? Request::input('order')[0]['dir'] : '');
        $filterArr['customFilterIdentity'] = !empty(Request::get('customFilterIdentity')) ? Request::get('customFilterIdentity') : '';
        $filterArr['statusFilter'] = !empty(Request::input('statusValue')) ? Request::input('statusValue') : '';
        $filterArr['sectorFilter'] = !empty(Request::input('sectorValue')) ? Request::input('sectorValue') : '';
        $filterArr['searchFilter'] = !empty(Request::input('searchValue')) ? Request::input('searchValue') : '';
        $filterArr['DecisionCategoryFilter'] = !empty(Request::input('DecisionCategoryFilter')) ? Request::input('DecisionCategoryFilter') : '';
        $filterArr['personalityFilter'] = !empty(Request::input('personalityFilter')) ? Request::input('personalityFilter') : '';
        $filterArr['paymentFilter'] = !empty(Request::input('paymentFilter')) ? Request::input('paymentFilter') : '';
        $filterArr['rangeFilter'] = !empty(Request::input('rangeFilter')) ? Request::input('rangeFilter') : '';
        $filterArr['iDisplayLength'] = intval(Request::input('length'));
        $filterArr['iDisplayStart'] = intval(Request::input('start'));
        $sEcho = intval(Request::input('draw'));
        $isAdmin = false;
        if (!empty($this->currentUserRoleData)) {
            if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                $isAdmin = true;
            }
        }
        $arrResults = DecisionCategory::getTrashRecordListforDecisionCategoryGrid($filterArr, 'Y', $isAdmin, $this->currentUserRoleSector);
        $iTotalRecords = DecisionCategory::getRecordCountforListTrash($filterArr, true, $isAdmin, array(), $this->currentUserRoleSector);
        $end = $filterArr['iDisplayStart'] + $filterArr['iDisplayLength'];
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;
        if (!empty($arrResults)) {
            foreach ($arrResults as $key => $value) {
                $records["data"][] = $this->tableDataTrash($value);
            }
        }
        $NewRecordsCount = DecisionCategory::getNewRecordsCount($isAdmin, $this->currentUserRoleSector);
        $records["newRecordCount"] = $NewRecordsCount;
        $records["customActionStatus"] = "OK";
        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;
        return json_encode($records);
    }

    /**
     * This method loads DecisionCategory table data on view
     * @return  View
     * @since   2017-11-10
     * @author  NetQuick
     */
    public function get_list_New()
    {
        $isAdmin = false;
        if (!empty($this->currentUserRoleData)) {
            if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                $isAdmin = true;
            }
        }
        $filterArr = [];
        $records = [];
        $records["data"] = [];
        $filterArr['orderColumnNo'] = (!empty(Request::input('order')[0]['column']) ? Request::input('order')[0]['column'] : '');
        $filterArr['orderByFieldName'] = (!empty(Request::input('columns')[$filterArr['orderColumnNo']]['name']) ? Request::input('columns')[$filterArr['orderColumnNo']]['name'] : '');
        $filterArr['orderTypeAscOrDesc'] = (!empty(Request::input('order')[0]['dir']) ? Request::input('order')[0]['dir'] : '');
        $filterArr['customFilterIdentity'] = !empty(Request::get('customFilterIdentity')) ? Request::get('customFilterIdentity') : '';
        $filterArr['statusFilter'] = !empty(Request::input('statusValue')) ? Request::input('statusValue') : '';
        $filterArr['sectorFilter'] = !empty(Request::input('sectorValue')) ? Request::input('sectorValue') : '';
        $filterArr['searchFilter'] = !empty(Request::input('searchValue')) ? Request::input('searchValue') : '';
        $filterArr['DecisionCategoryFilter'] = !empty(Request::input('DecisionCategoryFilter')) ? Request::input('DecisionCategoryFilter') : '';
        $filterArr['personalityFilter'] = !empty(Request::input('personalityFilter')) ? Request::input('personalityFilter') : '';
        $filterArr['paymentFilter'] = !empty(Request::input('paymentFilter')) ? Request::input('paymentFilter') : '';
        $filterArr['rangeFilter'] = !empty(Request::input('rangeFilter')) ? Request::input('rangeFilter') : '';
        $filterArr['iDisplayLength'] = intval(Request::input('length'));
        $filterArr['iDisplayStart'] = intval(Request::input('start'));
        $sEcho = intval(Request::input('draw'));
        $arrResults = DecisionCategory::getRecordListApprovalTab($filterArr, $isAdmin, $this->currentUserRoleSector);
        $iTotalRecords = DecisionCategory::getRecordCountListApprovalTab($filterArr, $isAdmin, $this->currentUserRoleSector);
        $end = $filterArr['iDisplayStart'] + $filterArr['iDisplayLength'];
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;
        if (!empty($arrResults)) {
            foreach ($arrResults as $key => $value) {
                $records["data"][] = $this->tableDataTab1($value);
            }
        }
        $NewRecordsCount = DecisionCategory::getNewRecordsCount($isAdmin, $this->currentUserRoleSector);
        $records["newRecordCount"] = $NewRecordsCount;
        $records["customActionStatus"] = "OK";
        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;
        return json_encode($records);
    }

    /**
     * This method loads DecisionCategory table data on view
     * @return  View
     * @since   2017-11-10
     * @author  NetQuick
     */
    public function tableDataTab1($value = false)
    {
        $sector = '';
        if (isset($value->varSector) && !empty($value->varSector)) {
            $sector = strtoupper($value->varSector);
        }
        $Hits = Pagehit::where('fkIntAliasId', $value->intAliasId)->count();
        
        $isParent = DecisionCategory::getCountById($value->id);
        $hasRecords = Decision::getCountById($value->id);
        $publish_action = '';
        $titleData_delete = "";
        $titleData_publish = "";
        $details = '';
        if ($isParent > 0) {
            $titleData_delete .= 'This category is selected as Parent Category, so it can&#39;t be deleted.';
            $titleData_publish .= 'This category is selected as Parent Category, so it can&#39;t be published/unpublished.';
        }
        if ($hasRecords > 0) {
            $titleData_delete .= 'This category is selected in ' . trans("decision-category::template.sidebar.decision") . ', so it can&#39;t be deleted.';
            $titleData_publish .= 'This category is selected in ' . trans("decision-category::template.sidebar.decision") . ', so it can&#39;t be published/unpublished.';
        }
        if ($hasRecords > 0 && $isParent > 0) {
            $titleData_delete = 'This category is selected in ' . trans("decision-category::template.sidebar.decision") . ' and also its a parent category so it can&#39;t be deleted.';
            $titleData_publish = 'This category is selected in ' . trans("decision-category::template.sidebar.decision") . ' and also its a parent category so it can&#39;t be published/unpublished.';
        }
        $minus = '<span class="glyphicon glyphicon-minus"></span>';
        $checkbox = '<a href="javascript:;" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-toggle="tooltip" data-bs-content="' . $titleData_delete . '" title="' . $titleData_delete . '"><i style="color:red" class="ri-alert-fill"></i></a>';
        $checkbox_publish = '<a href="javascript:;" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-toggle="tooltip" data-bs-content="' . $titleData_publish . '" title="' . $titleData_publish . '"><i style="color:red" class="ri-alert-fill"></i></a>';
        if (Auth::user()->can('decision-category-edit')) {
            $details .= '<a class="" title="' . trans("decision-category::template.common.edit") . '" href="' . route('powerpanel.decision-category.edit', array('alias' => $value->id)) . '"><i class="ri-pencil-line"></i></a>';
        }
        if ((Auth::user()->can('decision-category-delete') || (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y')) && $hasRecords == 0 && $isParent == 0) {
            if (Config::get('Constant.DEFAULT_TRASH') == 'Y') {
                $details .= '<a class = "delete-grid" title = "' . trans('decision-category::template.common.delete') . '" href = "javascript:;" onclick = \'Trashfun("' . $value->id . '")\' data-controller = "decision_category" data-alias = "' . $value->id . '" data-tab = "A"><i class = "ri-delete-bin-line"></i></a>';
            } else {
                $details .= '<a class = " delete" title = "' . trans('decision-category::template.common.delete') . '"  data-controller = "decision_category" data-alias = "' . $value->id . '" data-tab = "A"><i class = "ri-delete-bin-line"></i></a>';
            }
        }

        $parentCategoryTitle = $minus;
        if (!empty($value->intParentCategoryId) && $value->intParentCategoryId > 0) {
            $catIDS[] = $value->intParentCategoryId;
            $parentCategoryName = DecisionCategory::getParentCategoryNameBycatId($catIDS);
            $parentCategoryTitle = $parentCategoryName[0]->varTitle;
        }
        if (Auth::user()->can('decision-category-reviewchanges')) {
            $update = "<a title=\"Click here to see all approval records.\" class=\"icon_title1\" style=\"margin-right: 5px;\" onclick=\"javascript:expandcollapsepanel(this ,'waiting-tasklisting" . $value->id . "', 'waiting-mainsingnimg" . $value->id . "'," . $value->id . "," . "'waiting-'" . ")\"><i id=\"waiting-mainsingnimg" . $value->id . "\" class=\"ri-add-box-line\"></i></a>";
            $rollback = "<a title=\"Click here to see all approved records to rollback.\" class=\"icon_title2\" style=\"margin-right: 5px;\" onclick=\"javascript:expandcollapsepanel_rolback(this ,'waiting-tasklisting_rollback" . $value->id . "', 'waiting-mainsingnimg_rollback" . $value->id . "'," . $value->id . "," . "'waiting-'" . ")\"><i id=\"waiting-mainsingnimg_rollback" . $value->id . "\" class=\"ri-history-line\"></i></a>";
        } else {
            $update = '';
            $rollback = '';
        }
        if (Auth::user()->can('decision-category-reviewchanges') && $value->chrAddStar == 'Y') {
            $star = 'addhiglight';
        } else {
            $star = '';
        }
        $startDate = date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($value->dtDateTime));
        $endDate = !empty($value->dtEndDateTime) ? date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($value->dtEndDateTime)) : 'No Expiry';
        $Quickedit_startDate = date('Y-m-d H:i', strtotime($value->dtDateTime));
        $Quickedit_endDate = !empty($value->dtEndDateTime) ? date('Y-m-d H:i', strtotime($value->dtEndDateTime)) : 'No Expiry';
        $title = $value->varTitle;
        if (Auth::user()->can('decision-category-edit')) {
//            $frontViewLink = $frontViewLink = MyLibrary::getFrontUri('decision-category', $value->id)['uri'];
            if ($value->chrDraft == 'D' || $value->chrAddStar == 'Y') {
                $viewlink = url('/previewpage?url=' . MyLibrary::getFrontUri('decision-category')['uri'] . '/' . $value->id . '/preview');
                $linkviewLable = "Preview";
            } else {
                $viewlink = MyLibrary::getFrontUri('decision-category', $value->id)['uri'];
                $linkviewLable = "View";
            }
            if ($value->chrLock != 'Y') {
                $title = '<div class="quick_edit"><a href = "' . route('powerpanel.decision-category.edit', array('alias' => $value->id)) . '?tab=A">' . $value->varTitle . '</a> <div class="quick_edit_menu">
														<span><a href="' . route('powerpanel.decision-category.edit', array('alias' => $value->id)) . '?tab=A" title="Edit">Edit</a></span>';
                if ($hasRecords == 0 && $isParent == 0) {
                    if (Config::get('Constant.DEFAULT_TRASH') == 'Y') {
                        $title .= '<span><a title = "Trash" href = \'javascript:;\' onclick=\'Trashfun("' . $value->id . '")\' class="red" data-tab="A">Trash</a></span>';
                    }
                }
                // $title .= '<span><a href="' . $viewlink . '" target="_blank" title="' . $linkviewLable . '" >' . $linkviewLable . '</a></span></div></div>';
            } else {
                if (auth()->user()->id != $value->LockUserID) {
                    if (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y') {
                        $title = '<div class="quick_edit"><a href = "' . route('powerpanel.decision-category.edit', array('alias' => $value->id)) . '?tab=A">' . $value->varTitle . '</a> <div class="quick_edit_menu">
	                            <span><a href="' . route('powerpanel.decision-category.edit', array('alias' => $value->id)) . '?tab=A" title="Edit">Edit</a></span>';

                        // $title .= '<span><a href="' . $viewlink . '" target="_blank" title="' . $linkviewLable . '" >' . $linkviewLable . '</a></span></div></div>';
                    } else {
                        $title = '<div class="quick_edit"><a href = "javascript:;">' . $value->varTitle . '</a></div>';
                    }
                } else {
                    $title = '<div class="quick_edit"><a href = "' . route('powerpanel.decision-category.edit', array('alias' => $value->id)) . '?tab=A">' . $value->varTitle . '</a> <div class="quick_edit_menu">
	                            <span><a href="' . route('powerpanel.decision-category.edit', array('alias' => $value->id)) . '?tab=A" title="Edit">Edit</a></span>';

                    // $title .= '<span><a href="' . $viewlink . '" target="_blank" title="' . $linkviewLable . '" >' . $linkviewLable . '</a></span></div></div>';
                }
            }
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
        $logurl = url('powerpanel/log?id=' . $value->id . '&mid=' . Config::get('Constant.MODULE.ID'));
        if ($details == "") {
            $details = "---";
        } else {
            $details = $details;
        }
        $log = '';
        if ($value->chrLock != 'Y') {
            $log .= $details;
            if (Auth::user()->can('log-list')) {
                $log .= "<a title=\"Log History\" href=\"$logurl\"><i class=\"ri-time-line\"></i></a>";
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
            '<div class="pages_title_div_row">' . $update . $rollback . $title . ' ' . $status . $statusdata . ' ' . $sector . '</div>',
            $parentCategoryTitle,
            // $startDate,
            // $endDate,
            $log,
        );
        return $records;
    }

    /**
     * This method delete multiples DecisionCategory
     * @return  true/false
     * @since   2017-07-15
     * @author  NetQuick
     */
    public function DeleteRecord(Request $request)
    {
        /* new code for delete and reorder functionality */
        $value = Request::input('value');
        $data['ids'] = Request::input('ids');
        $moduleHaveFields = ['chrMain'];
        $update = Self::deleteMultipleRecords($data);
        if (File::exists(app_path() . '/Comments.php') != null || File::exists(base_path() . '/packages/Powerpanel/Workflow/src/Models/Comments.php') != null) {
            Comments::deleteComments($data['ids'], Config::get('Constant.MODULE.MODEL_NAME'));
        }
        foreach ($update as $ids) {
            $ignoreDeleteScope = true;
            $Deleted_Record = DecisionCategory::getRecordById($ids, $ignoreDeleteScope);
            $Cnt_Letest = DecisionCategory::getRecordCount_letest($Deleted_Record['fkMainRecord'], $Deleted_Record['id']);
            if ($Cnt_Letest <= 0) {
                $updateLetest = [
                    'chrAddStar' => 'N',
                ];
                $whereConditionsApprove = ['id' => $Deleted_Record['fkMainRecord']];
                CommonModel::updateRecords($whereConditionsApprove, $updateLetest, false, 'Powerpanel\DecisionCategory\Models\DecisionCategory');
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
            if ($Deleted_Record['chrMain'] == "Y") {
                $whereConditions = [
                    'intRecordId' => $ids,
                    'intfkModuleId' => Config::get('Constant.MODULE.ID'),
                ];
                $updateMenuFields = [
                    'chrPublish' => 'N',
                    'chrDelete' => 'Y',
                    'chrActive' => 'N',
                ];
                CommonModel::updateRecords($whereConditions, $updateMenuFields, false, '\\Powerpanel\\Menu\\Models\\Menu');
                if ($value != "P" && $value != "F") {
                    #code for delete alias from database
                    Alias::where('id', $Deleted_Record['intAliasId'])
                        ->where('intFkModuleCode', Config::get('Constant.MODULE.ID'))
                        ->delete();
                }
            }
        }
        $this->flushCache();
        echo json_encode($update);
        exit;
    }

    public static function deleteMultipleRecords($data) {
        $response = false;
        $responseAr = [];
        if (!empty($data)) {
            $updateFields = ['chrDelete' => 'Y', 'chrPublish' => 'N'];
            $whereINConditions = $data['ids'];
            $update = CommonModel::updateMultipleRecords($whereINConditions, $updateFields, false, 'Powerpanel\DecisionCategory\Models\DecisionCategory');
            foreach ($data['ids'] as $key => $id) {
                if ($update) {
                    $objModule = DecisionCategory::getRecordsForDeleteById($id);
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
        $recEx = DecisionCategory::getRecordByOrderByParent($exOrder, $parentRecordId);
        if (!empty($recEx)) {
            $recCur = DecisionCategory::getRecordByOrderByParent($order, $parentRecordId);
            if ($recCur->intDisplayOrder != $recEx->intDisplayOrder) {
                $whereConditionsForEx = ['id' => $recEx['id']];
                CommonModel::updateRecords($whereConditionsForEx, ['intDisplayOrder' => $recCur->intDisplayOrder], false, 'Powerpanel\DecisionCategory\Models\DecisionCategory');
                $whereConditionsForCur = ['id' => $recCur['id']];
                CommonModel::updateRecords($whereConditionsForCur, ['intDisplayOrder' => $recEx->intDisplayOrder], false, 'Powerpanel\DecisionCategory\Models\DecisionCategory');
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
//        $request = (object) $requestArr;
        $val = Request::get('val');
        $alias = Request::input('alias');
        $update = MyLibrary::setPublishUnpublish($alias, $val, 'Powerpanel\DecisionCategory\Models\DecisionCategory');
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
        $startDate = date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($data->dtDateTime));
        $endDate = !empty($data->dtEndDateTime) ? date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($data->dtEndDateTime)) : 'No Expiry';
        $returnHtml = '';
        $returnHtml .= '<table class="new_table_desing table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                        <th align="center">' . trans("decision-category::template.common.title") . '</th>
                        <th align="center">' . trans("decision-category::template.common.parentCategory") . '</th>
                        <th align="center">Start date</th>
                        <th align="center">End date</th>
                        <th align="center">Meta Title</th>
                        <th align="center">Meta Description</th>
                        <th align="center">' . trans("decision-category::template.common.displayorder") . '</th>
                        <th align="center">' . trans("decision-category::template.common.publish") . '</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                        <td align="center">' . stripslashes(trim($data->varTitle)) . '</td>';
        if ($data->intParentCategoryId > 0) {
            $catIDS[] = $data->intParentCategoryId;
            $parentCateName = DecisionCategory::getParentCategoryNameBycatId($catIDS);
            $parentCateName = $parentCateName[0]->varTitle;
            $returnHtml .= '<td align="center">' . $parentCateName . '</td>';
        } else {
            $returnHtml .= '<td align="center">-</td>';
        }
        $returnHtml .= '<td align="center">' . $startDate . '</td>
					<td align="center">' . $endDate . '</td>
                    <td align="center">' . stripslashes($data->varMetaTitle) . '</td>
                    <td align="center">' . stripslashes($data->varMetaDescription) . '</td>
                    <td align="center">' . $data->intDisplayOrder . '</td>
                    <td align="center">' . $data->chrPublish . '</td>
                    </tr>
                    </tbody>
                    </table>';
        return $returnHtml;
    }

    /**
     * This method handels logs History records
     * @param   $data
     * @return  HTML
     * @since   2017-07-21
     * @author  NetQuick
     */
    public function newrecordHistory($data = false, $newdata = false)
    {
        if ($data->varTitle != $newdata->varTitle) {
            $titlecolor = 'style="background-color:#f5efb7"';
        } else {
            $titlecolor = '';
        }
        if ($data->intParentCategoryId != $newdata->intParentCategoryId) {
            $catcolor = 'style="background-color:#f5efb7"';
        } else {
            $catcolor = '';
        }
        if ($data->intDisplayOrder != $newdata->intDisplayOrder) {
            $desccolor = 'style="background-color:#f5efb7"';
        } else {
            $desccolor = '';
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
        if ($data->varMetaTitle != $newdata->varMetaTitle) {
            $metatitlecolor = 'style="background-color:#f5efb7"';
        } else {
            $metatitlecolor = '';
        }
        if ($data->varMetaDescription != $newdata->varMetaDescription) {
            $metadesccolor = 'style="background-color:#f5efb7"';
        } else {
            $metadesccolor = '';
        }
        $startDate = date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($newdata->dtDateTime));
        $endDate = !empty($newdata->dtEndDateTime) ? date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($newdata->dtEndDateTime)) : 'No Expiry';
        $returnHtml = '';
        $returnHtml .= '<table class="new_table_desing table table-striped table-bordered table-hover">
                    <thead>
                    <tr>
                    <th align="center">' . trans("decision-category::template.common.title") . '</th>
                    <th align="center">' . trans("decision-category::template.common.parentCategory") . '</th>
                    <th align="center">Start date</th>
                    <th align="center">End date</th>
                    <th align="center">Meta Title</th>
                    <th align="center">Meta Description</th>
                    <th align="center">' . trans("decision-category::template.common.displayorder") . '</th>
                    <th align="center">' . trans("decision-category::template.common.publish") . '</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                    <td align="center" ' . $titlecolor . '>' . stripslashes(trim($newdata->varTitle)) . '</td>';
        if ($newdata->intParentCategoryId > 0) {
            $catIDS[] = $newdata->intParentCategoryId;
            $parentCateName = DecisionCategory::getParentCategoryNameBycatId($catIDS);
            $parentCateName = $parentCateName[0]->varTitle;
            $returnHtml .= '<td align="center" ' . $catcolor . '>' . $parentCateName . '</td>';
        } else {
            $returnHtml .= '<td align="center">-</td>';
        }
        $returnHtml .= '<td align="center" ' . $DateTimecolor . '>' . $startDate . '</td>
                        <td align="center" ' . $EndDateTimecolor . '>' . $endDate . '</td>
                        <td align="center" ' . $metatitlecolor . '>' . stripslashes($newdata->varMetaTitle) . '</td>
                        <td align="center" ' . $metadesccolor . '>' . stripslashes($newdata->varMetaDescription) . '</td>
                        <td align="center" ' . $desccolor . '>' . $newdata->intDisplayOrder . '</td>
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
        $Hits = Pagehit::where('fkIntAliasId', $value->intAliasId)->count();
        
        $isParent = DecisionCategory::getCountById($value->id);
        $hasRecords = Decision::getCountById($value->id);
        $details = '';
        $parent_category_name = ' ';
        $publish_action = '';
        $titleData_delete = "";
        $titleData_publish = "";
        $details = '';
        if ($isParent > 0) {
            $titleData_delete .= 'This category is selected as Parent Category, so it can&#39;t be deleted.';
            $titleData_publish .= 'This category is selected as Parent Category, so it can&#39;t be published/unpublished.';
        }
        if ($hasRecords > 0) {
            $titleData_delete .= 'This category is selected in ' . trans("decision-category::template.sidebar.decision") . ', so it can&#39;t be deleted.';
            $titleData_publish .= 'This category is selected in ' . trans("decision-category::template.sidebar.decision") . ', so it can&#39;t be published/unpublished.';
        }
        if ($hasRecords > 0 && $isParent > 0) {
            $titleData_delete = 'This category is selected in ' . trans("decision-category::template.sidebar.decision") . ' and also its a parent category so it can&#39;t be deleted.';
            $titleData_publish = 'This category is selected in ' . trans("decision-category::template.sidebar.decision") . ' and also its a parent category so it can&#39;t be published/unpublished.';
        }

        $checkbox = '<a href="javascript:;" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-toggle="tooltip" data-bs-content="' . $titleData_delete . '" title="' . $titleData_delete . '"><i style="color:red" class="ri-alert-fill"></i></a>';
        $checkbox_publish = '<a href="javascript:;" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-toggle="tooltip" data-bs-content="' . $titleData_publish . '" title="' . $titleData_publish . '"><i style="color:red" class="ri-alert-fill"></i></a>';
        if (Auth::user()->can('decision-category-edit')) {
            $details .= '<a class="" title="' . trans("decision-category::template.common.edit") . '" href="' . route('powerpanel.decision-category.edit', array('alias' => $value->id)) . '"><i class="ri-pencil-line"></i></a>';
        }

        if ((Auth::user()->can('decision-category-delete') || (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y')) && $hasRecords == 0 && $isParent == 0) {
            if (Config::get('Constant.DEFAULT_TRASH') == 'Y') {
                $details .= '<a class = "delete-grid" title = "' . trans('decision-category::template.common.delete') . '" href = "javascript:;" onclick = \'Trashfun("' . $value->id . '")\' data-controller = "decision_category" data-alias = "' . $value->id . '" data-tab = "P"><i class = "ri-delete-bin-line"></i></a>';
            } else {
                $details .= '<a class = " delete" title = "' . trans('decision-category::template.common.delete') . '"  data-controller = "decision_category" data-alias = "' . $value->id . '" data-tab = "P"><i class = "ri-delete-bin-line"></i></a>';
            }
        }
        if (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y') {
            // $details .= '<a class=" share" title="Share" data-modal="DecisionCategory" data-alias="' . $value->id . '"  data-images="" data-link = "' . url('/') . '" data-toggle="modal" data-target="#confirm_share">
            // <i class="ri-share-line"></i></a>';
        }
        if ($value->chrAddStar != 'Y') {
            if ($value->chrDraft != 'D') {
                if (Auth::user()->can('decision-category-publish')) {
                    if ($hasRecords == 0 && $isParent == 0) {
                        if ($value->chrPublish == 'Y') {
                            //Bootstrap Switch
                            $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/decision-category', 'data_alias'=>$value->id, 'title'=>trans("decision-category::template.common.publishedRecord"), 'data_value'=>'Unpublish'])->render();
                        } else {
                            //Bootstrap Switch
                            $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/decision-category', 'data_alias'=>$value->id, 'title'=>trans("decision-category::template.common.unpublishedRecord"), 'data_value'=>'Publish', 'checked'=>'checked'])->render();
                        }
                    } else {
                        $publish_action = $checkbox_publish;
                    }
                }
            } else {
                //Bootstrap Switch
                $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/decision-category', 'data_alias'=>$value->id, 'title'=>trans("decision-category::template.common.unpublishedRecord"), 'data_value'=>'Publish', 'checked'=>'checked'])->render();
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
            $parentCategoryName = DecisionCategory::getParentCategoryNameBycatId($catIDS);
            $parentCategoryTitle = $parentCategoryName[0]->varTitle;
        }
        if (Auth::user()->can('decision-category-reviewchanges') && (File::exists(app_path() . '/Workflow.php') != null || File::exists(base_path() . '/packages/Powerpanel/Workflow/src/Models/Workflow.php') != null)) {
            $update = "<a title=\"Click here to see all approval records.\" class=\"icon_title1\" style=\"margin-right: 5px;\" onclick=\"javascript:expandcollapsepanel(this ,'primary-tasklisting" . $value->id . "', 'primary-mainsingnimg" . $value->id . "'," . $value->id . "," . "'primary-'" . ")\"><i id=\"primary-mainsingnimg" . $value->id . "\" class=\"ri-add-box-line\"></i></a>";
            $rollback = "<a title=\"Click here to see all approved records to rollback.\" class=\"icon_title2\" style=\"margin-right: 5px;\" onclick=\"javascript:expandcollapsepanel_rolback(this ,'primary-tasklisting_rollback" . $value->id . "', 'primary-mainsingnimg_rollback" . $value->id . "'," . $value->id . "," . "'primary-'" . ")\"><i id=\"primary-mainsingnimg_rollback" . $value->id . "\" class=\"ri-history-line\"></i></a>";
        } else {
            $update = '';
            $rollback = '';
        }
        $startDate = date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($value->dtDateTime));
        $endDate = !empty($value->dtEndDateTime) ? date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($value->dtEndDateTime)) : 'No Expiry';
        $Quickedit_startDate = date('Y-m-d H:i', strtotime($value->dtDateTime));
        $Quickedit_endDate = !empty($value->dtEndDateTime) ? date('Y-m-d H:i', strtotime($value->dtEndDateTime)) : 'No Expiry';
        $title = $value->varTitle;
        if (Auth::user()->can('decision-category-edit')) {
//            $frontViewLink = MyLibrary::getFrontUri('decision-category', $value->id)['uri'];
            if ($value->chrDraft == 'D' || $value->chrAddStar == 'Y') {
                $viewlink = url('/previewpage?url=' . MyLibrary::getFrontUri('decision-category')['uri'] . '/' . $value->id . '/preview');
                $linkviewLable = "Preview";
            } else {
                $viewlink = MyLibrary::getFrontUri('decision-category', $value->id)['uri'];
                $linkviewLable = "View";
            }
            if ($value->chrLock != 'Y') {
                if (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y') {
                    $title = '<div class="quick_edit"><a href = "' . route('powerpanel.decision-category.edit', array('alias' => $value->id)) . '?tab=P">' . $value->treename . '</a> <div class="quick_edit_menu">
                    <span><a href="' . route('powerpanel.decision-category.edit', array('alias' => $value->id)) . '?tab=P" title="Edit">Edit</a></span>';
                    if (Config::get('Constant.DEFAULT_QUICK') == 'Y') {
                        $title .= '<span><a title="Quick Edit" href=\'javascript:;\' data-toggle=\'modal\' data-target=\'#modalForm\' aria-label=\'Quick edit\' onclick=\'Quickeditfun("' . $value->id . '","' . $value->varTitle . '","' . $value->intSearchRank . '","' . $Quickedit_startDate . '","' . $Quickedit_endDate . '","P")\'>Quick Edit</a></span>';
                    }
                    if ($hasRecords == 0 && $isParent == 0) {
                        if (Config::get('Constant.DEFAULT_TRASH') == 'Y') {
                            $title .= '<span><a title = "Trash" href = \'javascript:;\' onclick=\'Trashfun("' . $value->id . '")\' class="red" data-tab="P">Trash</a></span>';
                        }
                    }
                    $title .= '</div></div>';
                } else {
                    $title = '<div class="quick_edit"><a href = "' . route('powerpanel.decision-category.edit', array('alias' => $value->id)) . '?tab=P">' . $value->treename . '</a> <div class="quick_edit_menu">
                            <span><a href="' . route('powerpanel.decision-category.edit', array('alias' => $value->id)) . '?tab=P" title="Edit">Edit</a></span>
                        </div>
                    </div>';
                }
            } else {
                if (auth()->user()->id != $value->LockUserID) {
                    if (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y') {
                        $title = '<div class="quick_edit"><a href = "' . route('powerpanel.decision-category.edit', array('alias' => $value->id)) . '?tab=P">' . $value->treename . '</a> <div class="quick_edit_menu">
                            <span><a href="' . route('powerpanel.decision-category.edit', array('alias' => $value->id)) . '?tab=P" title="Edit">Edit</a></span>
                        </div>
                       </div>';
                    } else {
                        $title = '<div class="quick_edit"><a href = "javascript:;">' . $value->treename . '</a></div>';
                    }
                } else {
                    $title = '<div class="quick_edit"><a href = "' . route('powerpanel.decision-category.edit', array('alias' => $value->id)) . '?tab=P">' . $value->treename . '</a> <div class="quick_edit_menu">
                            <span><a href="' . route('powerpanel.decision-category.edit', array('alias' => $value->id)) . '?tab=P" title="Edit">Edit</a></span>
                        </div>
                       </div>';
                }
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
                if (Config::get('Constant.DEFAULT_DUPLICATE') == 'Y') {
                    $log .= "<a title=\"Duplicate\" class='copy-grid' href=\"javascript:;\" onclick=\"GetCopyPage('" . $value->id . "');\"><i class=\"ri-file-copy-line\"></i></a>";
                }
                $log .= $details;
                if (Auth::user()->can('log-list')) {
                    $log .= "<a title=\"Log History\" class='log-grid' href=\"$logurl\"><i class=\"ri-time-line\"></i></a>";
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
            
            $childCount = DecisionCategory::where('fkMainRecord', $value->id)
                                                ->where('dtApprovedDateTime','!=',NULL)
                                                ->count();

            if($this->currentUserRoleData->chrIsAdmin == 'Y' && $childCount > 1) {
                $log .= "<a title='Rollback to previous version'  onclick=\"rollbackToPreviousVersion('" . $value->id . "');\"  class=\"log-grid\"><i class=\"ri-history-line\"></i></a>";
            }
        }

        $records = array(
            ($isParent == 0 && $hasRecords == 0) ? '<input type="checkbox" name="delete" class="chkDelete form-check-input" value="' . $value->id . '">' : $checkbox,
            $First_td,
            '<div class="pages_title_div_row">' . $update . $rollback . $title . ' ' . $status . $statusdata . ' ' . $sector . '</div>',
            $parentCategoryTitle,
            // $startDate,
            // $endDate,
            '<a href="javascript:;" data-parentRecordId="' . $value->intParentCategoryId . '" data-order="' . $value->intDisplayOrder . '" class="moveUp"><i class="ri-arrow-up-line" aria-hidden="true"></i></a>
				' . $value->DisplayOrder .
            ' <a href="javascript:;" data-parentRecordId="' . $value->intParentCategoryId . '" data-order="' . $value->intDisplayOrder . '" class="moveDwn"><i class="ri-arrow-down-line" aria-hidden="true"></i></a>',
            $publish_action,
            $log,
            $value->intDisplayOrder,
        );
        return $records;
    }

    public function tableDataFavorite($value = false)
    {
        $sector = '';
        if (isset($value->varSector) && !empty($value->varSector)) {
            $sector = strtoupper($value->varSector);
        }
        $isParent = DecisionCategory::getCountById($value->id);
        $hasRecords = Decision::getCountById($value->id);
        $Hits = Pagehit::where('fkIntAliasId', $value->intAliasId)->count();
        
        $details = '';
        $parent_category_name = ' ';
        $publish_action = '';
        $titleData_delete = "";
        $titleData_publish = "";
        $details = '';
        if ($isParent > 0) {
            $titleData_delete .= 'This category is selected as Parent Category, so it can&#39;t be deleted.';
            $titleData_publish .= 'This category is selected as Parent Category, so it can&#39;t be published/unpublished.';
        }
        if ($hasRecords > 0) {
            $titleData_delete .= 'This category is selected in ' . trans("decision-category::template.sidebar.decision") . ', so it can&#39;t be deleted.';
            $titleData_publish .= 'This category is selected in ' . trans("decision-category::template.sidebar.decision") . ', so it can&#39;t be published/unpublished.';
        }
        if ($hasRecords > 0 && $isParent > 0) {
            $titleData_delete = 'This category is selected in ' . trans("decision-category::template.sidebar.decision") . ' and also its a parent category so it can&#39;t be deleted.';
            $titleData_publish = 'This category is selected in ' . trans("decision-category::template.sidebar.decision") . ' and also its a parent category so it can&#39;t be published/unpublished.';
        }

        $checkbox = '<a href="javascript:;" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-toggle="tooltip" data-bs-content="' . $titleData_delete . '" title="' . $titleData_delete . '"><i style="color:red" class="ri-alert-fill"></i></a>';
        $checkbox_publish = '<a href="javascript:;" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-toggle="tooltip" data-bs-content="' . $titleData_publish . '" title="' . $titleData_publish . '"><i style="color:red" class="ri-alert-fill"></i></a>';
        if (Auth::user()->can('decision-category-edit')) {
            $details .= '<a class="" title="' . trans("decision-category::template.common.edit") . '" href="' . route('powerpanel.decision-category.edit', array('alias' => $value->id)) . '"><i class="ri-pencil-line"></i></a>';
        }
        if (Auth::user()->can('decision-category-delete') && $hasRecords == 0 && $isParent == 0 && $this->currentUserRoleData->chrIsAdmin == 'Y') {
            if (Config::get('Constant.DEFAULT_TRASH') == 'Y') {
                $details .= '<a class = "delete-grid" title = "' . trans('decision-category::template.common.delete') . '" href = "javascript:;" onclick = \'Trashfun("' . $value->id . '")\' data-controller = "decision_category" data-alias = "' . $value->id . '" data-tab = "F"><i class = "ri-delete-bin-line"></i></a>';
            } else {
                $details .= '<a class = " delete" title = "' . trans('decision-category::template.common.delete') . '"  data-controller = "decision_category" data-alias = "' . $value->id . '" data-tab = "F"><i class = "ri-delete-bin-line"></i></a>';
            }
        }
        if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
            $details .= '<a class=" share" title="Share" data-modal="DecisionCategory" data-alias="' . $value->id . '"  data-images="" data-link = "' . url('/') . '" data-toggle="modal" data-target="#confirm_share">
					<i class="ri-share-line"></i></a>';
        }
        if (Auth::user()->can('decision-category-publish')) {
            if ($hasRecords == 0 && $isParent == 0) {
                if ($value->chrPublish == 'Y') {
                    //Bootstrap Switch
                    $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/decision-category', 'data_alias'=>$value->id, 'title'=>trans("decision-category::template.common.publishedRecord"), 'data_value'=>'Unpublish'])->render();
                } else {
                    //Bootstrap Switch
                    $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/decision-category', 'data_alias'=>$value->id, 'title'=>trans("decision-category::template.common.unpublishedRecord"), 'data_value'=>'Publish', 'checked'=>'checked'])->render();
                }
            } else {
                $publish_action = $checkbox_publish;
            }
        }
        $parentCategoryTitle = '-';
        if (!empty($value->intParentCategoryId) && $value->intParentCategoryId > 0) {
            $catIDS[] = $value->intParentCategoryId;
            $parentCategoryName = DecisionCategory::getParentCategoryNameBycatId($catIDS);
            $parentCategoryTitle = $parentCategoryName[0]->varTitle;
        }
        if (Auth::user()->can('decision-category-reviewchanges')) {
            $update = "<a title=\"Click here to see all approval records.\" class=\"icon_title1\" style=\"margin-right: 5px;\" onclick=\"javascript:expandcollapsepanel(this ,'primary-tasklisting" . $value->id . "', 'primary-mainsingnimg" . $value->id . "'," . $value->id . "," . "'primary-'" . ")\"><i id=\"primary-mainsingnimg" . $value->id . "\" class=\"ri-add-box-line\"></i></a>";
            $rollback = "<a title=\"Click here to see all approved records to rollback.\" class=\"icon_title2\" style=\"margin-right: 5px;\" onclick=\"javascript:expandcollapsepanel_rolback(this ,'primary-tasklisting_rollback" . $value->id . "', 'primary-mainsingnimg_rollback" . $value->id . "'," . $value->id . "," . "'primary-'" . ")\"><i id=\"primary-mainsingnimg_rollback" . $value->id . "\" class=\"ri-history-line\"></i></a>";
        } else {
            $update = '';
            $rollback = '';
        }
        $startDate = date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($value->dtDateTime));
        $endDate = !empty($value->dtEndDateTime) ? date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($value->dtEndDateTime)) : 'No Expiry';
        $Quickedit_startDate = date('Y-m-d H:i', strtotime($value->dtDateTime));
        $Quickedit_endDate = !empty($value->dtEndDateTime) ? date('Y-m-d H:i', strtotime($value->dtEndDateTime)) : 'No Expiry';
        $title = $value->varTitle;
        if (Auth::user()->can('decision-category-edit')) {
            if ($value->chrDraft == 'D' || $value->chrAddStar == 'Y') {
                $viewlink = url('/previewpage?url=' . MyLibrary::getFrontUri('decision-category')['uri'] . '/' . $value->id . '/preview');
                $linkviewLable = "Preview";
            } else {
                $viewlink = MyLibrary::getFrontUri('decision-category', $value->id)['uri'];
                $linkviewLable = "View";
            }
            if ($value->chrLock != 'Y') {
                if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                    $title = '<div class="quick_edit"><a href = "' . route('powerpanel.decision-category.edit', array('alias' => $value->id)) . '?tab=P">' . $value->varTitle . '</a> <div class="quick_edit_menu">
														<span><a href="' . route('powerpanel.decision-category.edit', array('alias' => $value->id)) . '?tab=P" title="Edit">Edit</a></span>
                                                        </div>
                                                        </div>';

                    if ($hasRecords == 0 && $isParent == 0) {
                        if (Config::get('Constant.DEFAULT_TRASH') == 'Y') {
                            $title .= '<span><a title = "Trash" href = \'javascript:;\' onclick=\'Trashfun("' . $value->id . '")\' class="red" data-tab="F">Trash</a></span>';
                        }
                    }									
                } else {
                    $title = '<div class="quick_edit"><a href = "' . route('powerpanel.decision-category.edit', array('alias' => $value->id)) . '?tab=P">' . $value->varTitle . '</a> <div class="quick_edit_menu">
														<span><a href="' . route('powerpanel.decision-category.edit', array('alias' => $value->id)) . '?tab=P" title="Edit">Edit</a></span>
												 </div>
											 </div>';
                }
            } else {
                if (auth()->user()->id != $value->LockUserID) {
                    if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                        $title = '<div class="quick_edit"><a href = "' . route('powerpanel.decision-category.edit', array('alias' => $value->id)) . '?tab=F">' . $value->varTitle . '</a> <div class="quick_edit_menu">
	                            <span><a href="' . route('powerpanel.decision-category.edit', array('alias' => $value->id)) . '?tab=F" title="Edit">Edit</a></span>
	                                </div>
	                        </div>';
                    } else {
                        $title = '<div class="quick_edit"><a href = "javascript:;">' . $value->varTitle . '</a></div>';
                    }
                } else {
                    $title = '<div class="quick_edit"><a href = "' . route('powerpanel.decision-category.edit', array('alias' => $value->id)) . '?tab=F">' . $value->varTitle . '</a> <div class="quick_edit_menu">
	                            <span><a href="' . route('powerpanel.decision-category.edit', array('alias' => $value->id)) . '?tab=F" title="Edit">Edit</a></span>
	                                </div>
	                        </div>';
                }
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
            ($isParent == 0 && $hasRecords == 0) ? '<input type="checkbox" name="delete" class="chkDelete form-check-input" value="' . $value->id . '">' : $checkbox,
            $First_td,
            '<div class="pages_title_div_row">' . $title . ' ' . $status . $statusdata . ' ' . $sector . '</div>',
            $parentCategoryTitle,
            // $startDate,
            // $endDate,
            $log,
            $value->intDisplayOrder,
        );
        return $records;
    }

    public function tableDataDraft($value = false)
    {
        $sector = '';
        if (isset($value->varSector) && !empty($value->varSector)) {
            $sector = strtoupper($value->varSector);
        }
        $isParent = DecisionCategory::getCountById($value->id);
        $hasRecords = Decision::getCountById($value->id);
        $Hits = Pagehit::where('fkIntAliasId', $value->intAliasId)->count();

        $details = '';
        $parent_category_name = ' ';
        $publish_action = '';
        $titleData_delete = "";
        $titleData_publish = "";
        $details = '';
        if ($isParent > 0) {
            $titleData_delete .= 'This category is selected as Parent Category, so it can&#39;t be deleted.';
            $titleData_publish .= 'This category is selected as Parent Category, so it can&#39;t be published/unpublished.';
        }
        if ($hasRecords > 0) {
            $titleData_delete .= 'This category is selected in ' . trans("decision-category::template.sidebar.decision") . ', so it can&#39;t be deleted.';
            $titleData_publish .= 'This category is selected in ' . trans("decision-category::template.sidebar.decision") . ', so it can&#39;t be published/unpublished.';
        }
        if ($hasRecords > 0 && $isParent > 0) {
            $titleData_delete = 'This category is selected in ' . trans("decision-category::template.sidebar.decision") . ' and also its a parent category so it can&#39;t be deleted.';
            $titleData_publish = 'This category is selected in ' . trans("decision-category::template.sidebar.decision") . ' and also its a parent category so it can&#39;t be published/unpublished.';
        }

        $checkbox = '<a href="javascript:;" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-toggle="tooltip" data-bs-content="' . $titleData_delete . '" title="' . $titleData_delete . '"><i style="color:red" class="ri-alert-fill"></i></a>';
        $checkbox_publish = '<a href="javascript:;" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-toggle="tooltip" data-bs-content="' . $titleData_publish . '" title="' . $titleData_publish . '"><i style="color:red" class="ri-alert-fill"></i></a>';
        if (Auth::user()->can('decision-category-edit')) {
            $details .= '<a class="" title="' . trans("decision-category::template.common.edit") . '" href="' . route('powerpanel.decision-category.edit', array('alias' => $value->id)) . '"><i class="ri-pencil-line"></i></a>';
        }
        if (Auth::user()->can('decision-category-delete') && $hasRecords == 0 && $isParent == 0 && $this->currentUserRoleData->chrIsAdmin == 'Y') {
            if (Config::get('Constant.DEFAULT_TRASH') == 'Y') {
                $details .= '<a class = "delete-grid" title = "' . trans('decision-category::template.common.delete') . '" href = "javascript:;" onclick = \'Trashfun("' . $value->id . '")\' data-controller = "decision_category" data-alias = "' . $value->id . '" data-tab = "D"><i class = "ri-delete-bin-line"></i></a>';
            } else {
                $details .= '<a class = " delete" title = "' . trans('decision-category::template.common.delete') . '"  data-controller = "decision_category" data-alias = "' . $value->id . '" data-tab = "D"><i class = "ri-delete-bin-line"></i></a>';
            }
        }
        //Bootstrap Switch
        $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/decision-category', 'data_alias'=>$value->id, 'title'=>trans("decision-category::template.common.publishedRecord"), 'data_value'=>'Unpublish', 'checked'=>'checked'])->render();

        $parentCategoryTitle = '-';
        if (!empty($value->intParentCategoryId) && $value->intParentCategoryId > 0) {
            $catIDS[] = $value->intParentCategoryId;
            $parentCategoryName = DecisionCategory::getParentCategoryNameBycatId($catIDS);
            $parentCategoryTitle = $parentCategoryName[0]->varTitle;
        }
        $startDate = date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($value->dtDateTime));
        $endDate = !empty($value->dtEndDateTime) ? date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($value->dtEndDateTime)) : 'No Expiry';
        $Quickedit_startDate = date('Y-m-d H:i', strtotime($value->dtDateTime));
        $Quickedit_endDate = !empty($value->dtEndDateTime) ? date('Y-m-d H:i', strtotime($value->dtEndDateTime)) : 'No Expiry';
        $title = $value->varTitle;
        if (Auth::user()->can('decision-category-edit')) {
            // $previewlink = url('/previewpage?url=' . MyLibrary::getFrontUri('decision-category')['uri'] . '/' . $value->id . '/preview');
            if ($value->chrDraft == 'D' || $value->chrAddStar == 'Y') {
                $viewlink = url('/previewpage?url=' . MyLibrary::getFrontUri('decision-category')['uri'] . '/' . $value->id . '/preview');
                $linkviewLable = "Preview";
            } else {
                $viewlink = MyLibrary::getFrontUri('decision-category', $value->id)['uri'];
                $linkviewLable = "View";
            }
            if ($value->chrLock != 'Y') {
                if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                    $title = '<div class="quick_edit"><a href = "' . route('powerpanel.decision-category.edit', array('alias' => $value->id)) . '?tab=D">' . $value->varTitle . '</a> <div class="quick_edit_menu">
														<span><a href="' . route('powerpanel.decision-category.edit', array('alias' => $value->id)) . '?tab=D" title="Edit">Edit</a></span>';
                    if ($hasRecords == 0 && $isParent == 0) {
                        if (Config::get('Constant.DEFAULT_TRASH') == 'Y') {
                            $title .= '<span><a title = "Trash" href = \'javascript:;\' onclick=\'Trashfun("' . $value->id . '")\' class="red" data-tab="D">Trash</a></span>';
                        }
                    }
                    $title .= '</div>
											 </div>';
                } else {
                    $title = '<div class="quick_edit"><a href = "' . route('powerpanel.decision-category.edit', array('alias' => $value->id)) . '?tab=D">' . $value->varTitle . '</a> <div class="quick_edit_menu">
														<span><a href="' . route('powerpanel.decision-category.edit', array('alias' => $value->id)) . '?tab=D" title="Edit">Edit</a></span>
												 </div>
											 </div>';
                }
            } else {
                if (auth()->user()->id != $value->LockUserID) {
                    if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                        $title = '<div class="quick_edit"><a href = "' . route('powerpanel.decision-category.edit', array('alias' => $value->id)) . '?tab=D">' . $value->varTitle . '</a> <div class="quick_edit_menu">
	                            <span><a href="' . route('powerpanel.decision-category.edit', array('alias' => $value->id)) . '?tab=D" title="Edit">Edit</a></span>';

                        $title .= '</div></div>';
                    } else {
                        $title = '<div class="quick_edit"><a href = "javascript:;">' . $value->varTitle . '</a></div>';
                    }
                } else {
                    $title = '<div class="quick_edit"><a href = "' . route('powerpanel.decision-category.edit', array('alias' => $value->id)) . '?tab=D">' . $value->varTitle . '</a> <div class="quick_edit_menu">
	                            <span><a href="' . route('powerpanel.decision-category.edit', array('alias' => $value->id)) . '?tab=D" title="Edit">Edit</a></span>
                                	                                </div>
	                        </div>';
                }
            }
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
            ($isParent == 0 && $hasRecords == 0) ? '<input type="checkbox" name="delete" class="chkDelete form-check-input" value="' . $value->id . '">' : $checkbox,
            '<div class="pages_title_div_row"><input type="hidden" id="draftid" value="' . $value->id . '">' . $title . ' ' . $status . $statusdata . ' ' . $sector . '</div>',
            $parentCategoryTitle,
            // $startDate,
            // $endDate,
            $publish_action,
            $log,
            $value->intDisplayOrder,
        );
        return $records;
    }

    public function tableDataTrash($value = false)
    {
        $sector = '';
        if (isset($value->varSector) && !empty($value->varSector)) {
            $sector = strtoupper($value->varSector);
        }
        $isParent = DecisionCategory::getCountById($value->id);
        $hasRecords = Decision::getCountById($value->id);
        $Hits = Pagehit::where('fkIntAliasId', $value->intAliasId)->count();
        $webHits = '';
       
        $details = '';
        $parent_category_name = ' ';
        $publish_action = '';
        $titleData_delete = "";
        $titleData_publish = "";
        $details = '';
        if ($isParent > 0) {
            $titleData_delete .= 'This category is selected as Parent Category, so it can&#39;t be deleted.';
            $titleData_publish .= 'This category is selected as Parent Category, so it can&#39;t be published/unpublished.';
        }
        if ($hasRecords > 0) {
            $titleData_delete .= 'This category is selected in ' . trans("decision-category::template.sidebar.decision") . ', so it can&#39;t be deleted.';
            $titleData_publish .= 'This category is selected in ' . trans("decision-category::template.sidebar.decision") . ', so it can&#39;t be published/unpublished.';
        }
        if ($hasRecords > 0 && $isParent > 0) {
            $titleData_delete = 'This category is selected in ' . trans("decision-category::template.sidebar.decision") . ' and also its a parent category so it can&#39;t be deleted.';
            $titleData_publish = 'This category is selected in ' . trans("decision-category::template.sidebar.decision") . ' and also its a parent category so it can&#39;t be published/unpublished.';
        }

        $checkbox = '<a href="javascript:;" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-toggle="tooltip" data-bs-content="' . $titleData_delete . '" title="' . $titleData_delete . '"><i style="color:red" class="ri-alert-fill"></i></a>';

        if (Auth::user()->can('decision-category-delete') && $hasRecords == 0 && $isParent == 0 && $this->currentUserRoleData->chrIsAdmin == 'Y') {
            $details .= '<a class=" delete" title="' . trans("decision-category::template.common.delete") . '" data-controller="decision_category" data-alias = "' . $value->id . '" data-tab="T"><i class="ri-delete-bin-line"></i></a>';
        }
        $parentCategoryTitle = '-';
        if (!empty($value->intParentCategoryId) && $value->intParentCategoryId > 0) {
            $catIDS[] = $value->intParentCategoryId;
            $parentCategoryName = DecisionCategory::getParentCategoryNameBycatId($catIDS);
            $parentCategoryTitle = $parentCategoryName[0]->varTitle;
        }
        $startDate = date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($value->dtDateTime));
        $endDate = !empty($value->dtEndDateTime) ? date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($value->dtEndDateTime)) : 'No Expiry';
        $title = $value->varTitle;
        if (Auth::user()->can('decision-category-edit')) {
            $title = '<div class="quick_edit text-uppercase"><a href = "' . route('powerpanel.decision-category.edit', array('alias' => $value->id)) . '?tab=T">' . $value->varTitle . '</a>
												</div>';
        }

        $logurl = url('powerpanel/log?id=' . $value->id . '&mid=' . Config::get('Constant.MODULE.ID'));
        if ($details == "") {
            $details = "---";
        } else {
            $details = $details;
        }
        $log = '';
        if ($value->chrLock != 'Y') {
            if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                if (Config::get('Constant.DEFAULT_TRASH') == 'Y') {
                    $log .= "<a title=\"Restore\" href='javascript:;' onclick='Restorefun(\"$value->id\",\"T\")'><i class=\"ri-repeat-line\"></i></a>";
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
            ($isParent == 0 && $hasRecords == 0) ? '<input type="checkbox" name="delete" class="chkDelete form-check-input" value="' . $value->id . '">' : $checkbox,
            '<div class="pages_title_div_row"><input type="hidden" id="draftid" value="' . $value->id . '">' . $title . ' ' . $sector . '</div>',
            $parentCategoryTitle,
            // $startDate,
            // $endDate,
            $log,
            $value->intDisplayOrder,
        );
        return $records;
    }

    /**
     * This method loads DecisionCategory edit view
     * @param   Alias of record
     * @return  View
     * @since   2017-11-10
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
            $total = DecisionCategory::getRecordCounter();
            if (auth()->user()->can('decision-category-create') || $userIsAdmin) {
                $total = $total + 1;
            }
            $this->breadcrumb['title'] = trans('decision-category::template.decision_categoryModule.addDecisionCategory');
            $this->breadcrumb['module'] = trans('decision-category::template.decision_categoryModule.manageDecisionCategory');
            $this->breadcrumb['url'] = 'powerpanel/decision-category';
            $this->breadcrumb['inner_title'] = trans('decision-category::template.decision_categoryModule.addDecisionCategory');
            $breadcrumb = $this->breadcrumb;
            $data = compact('total', 'breadcrumb', 'categories', 'isParent', 'hasRecords');
        } else {
            $id = $alias;
            $decisionCategory = DecisionCategory::getRecordById($id);
            if (empty($decisionCategory)) {
                return redirect()->route('powerpanel.decision-category.add');
            }
            $categories = ParentRecordHierarchy_builder::Parentrecordhierarchy($decisionCategory->intParentCategoryId, $decisionCategory->id);
            $metaInfo = array('varMetaTitle' => $decisionCategory->varMetaTitle,
                'varMetaDescription' => $decisionCategory->varMetaDescription);
            $this->breadcrumb['title'] = trans('decision-category::template.common.edit') . ' - ' . $decisionCategory->varTitle;
            $this->breadcrumb['module'] = trans('decision-category::template.decision_categoryModule.manageDecisionCategory');
            $this->breadcrumb['url'] = 'powerpanel/decision-category';
            $this->breadcrumb['inner_title'] = trans('decision-category::template.common.edit') . ' - ' . $decisionCategory->varTitle;
            $breadcrumb = $this->breadcrumb;
            if (method_exists($this->MyLibrary, 'getModulePageAliasByModuleName')) {
                $categorypagereocrdlink = MyLibrary::getModulePageAliasByModuleName('decision-category');
            }
            if (!empty($categorypagereocrdlink)) {
                $varURL = $categorypagereocrdlink . '/' . $decisionCategory->alias->varAlias;
            } else {
                $varURL = $decisionCategory->alias->varAlias;
            }
            $metaInfo = array('varMetaTitle' => $decisionCategory->varMetaTitle,
                'varMetaDescription' => $decisionCategory->varMetaDescription,
                'varTags' => $decisionCategory->varTags,
            );
            if ((int) $decisionCategory->fkMainRecord !== 0) {
                $decisionCategoryHighLight = DecisionCategory::getRecordById($decisionCategory->fkMainRecord);
                $metaInfo_highLight['varMetaTitle'] = $decisionCategoryHighLight['varMetaTitle'];
                $metaInfo_highLight['varMetaDescription'] = $decisionCategoryHighLight['varMetaDescription'];
                $metaInfo_highLight['varTags'] = $decisionCategoryHighLight['varTags'];
                $isParent = DecisionCategory::getCountById($decisionCategory->fkMainRecord);
                $hasRecords = Decision::getCountById($decisionCategory->fkMainRecord);
            } else {
                $metaInfo_highLight['varMetaTitle'] = "";
                $metaInfo_highLight['varMetaDescription'] = "";
                $metaInfo_highLight['varTags'] = "";
                $decisionCategoryHighLight = "";
                $isParent = DecisionCategory::getCountById($decisionCategory->id);
                $hasRecords = Decision::getCountById($decisionCategory->id);
            }
            $data = compact('decisionCategoryHighLight', 'metaInfo_highLight', 'categories', 'isParent', 'hasRecords', 'decisionCategory', 'metaInfo', 'breadcrumb');
        }
        //Start Button Name Change For User Side
        if (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin != 'Y') {
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
        } else {
            $data['chrNeedAddPermission'] = 'N';
            $data['charNeedApproval'] = 'N';
        }
        //End Button Name Change For User Side
        $data['userIsAdmin'] = $userIsAdmin;
        return view('decision-category::powerpanel.actions', $data);
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
        $serCats = DecisionCategory::where('intParentCategoryId', $CatId)->get();
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
        return AddQuickCategoryAjax::AddSimple($data, 'DecisionCategory');
    }

    public static function flushCache()
    {
        Cache::tags('DecisionCategory')->flush();
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
//            foreach ($onlyParentIds as $Id) {
            //                $parentNodes = DecisionCategory::getParentNodesIdsByRecordId($Id);
            //                if (!empty($parentNodes)) {
            //                    $stringIds = array_merge($stringIds, $parentNodes);
            //                }
            //            }
        }
        $stringIds = array_unique($stringIds);
        $fetchData = DecisionCategory::getRecordListforGridbyIds($stringIds, $filterArr);
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
        $TotalRec = DecisionCategory::getRecordCounter($parentRecordId);
        if ($parentRecordId > 0) {
            if ($TotalRec >= $order) {
                DecisionCategory::UpdateDisplayOrder($order, $parentRecordId);
                $order = $order;
            } else {
                $order = $TotalRec + 1;
            }
        } else {
            if ($TotalRec >= $order) {
                DecisionCategory::UpdateDisplayOrder($order);
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
        $recCur = DecisionCategory::getRecordById($id);
        if (!empty($recCur)) {
            $parentRecordId = $recCur->intParentCategoryId;
            $TotalRec = DecisionCategory::getRecordCounter($parentRecordId);
            if ($parentRecordId > 0) {
                if ($TotalRec > $order) {
                    DecisionCategory::UpdateDisplayOrder($order, $parentRecordId);
                    $whereConditionsForCur = ['id' => $recCur['id']];
                    CommonModel::updateRecords($whereConditionsForCur, ['intDisplayOrder' => $order], false, 'Powerpanel\DecisionCategory\Models\DecisionCategory');
                } else {
                    $whereConditionsForCur = ['id' => $recCur['id']];
                    CommonModel::updateRecords($whereConditionsForCur, ['intDisplayOrder' => $TotalRec + 1], false, 'Powerpanel\DecisionCategory\Models\DecisionCategory');
                }
            } else {
                if ($TotalRec > $order) {
                    DecisionCategory::UpdateDisplayOrder($order);
                    $whereConditionsForCur = ['id' => $recCur['id']];
                    CommonModel::updateRecords($whereConditionsForCur, ['intDisplayOrder' => $order], false, 'Powerpanel\DecisionCategory\Models\DecisionCategory');
                } else {
                    $whereConditionsForCur = ['id' => $recCur['id']];
                    CommonModel::updateRecords($whereConditionsForCur, ['intDisplayOrder' => $TotalRec + 1], false, 'Powerpanel\DecisionCategory\Models\DecisionCategory');
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
        $records = DecisionCategory::getRecordForReorderByParentId($parentRecordId);
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
                DecisionCategory::updateherarachyRecords($when, $ids);
            }
        }
    }

    public function ApprovedData_Listing(Request $request)
    {
        $requestArr = Request::all();
        $request = (object) $requestArr;
        $id = Request::post('id');
        $approvalid = Request::post('id');
        $flag = Request::post('flag');
        $approvalData = DecisionCategory::getOrderOfApproval($id);
        $main_id = Request::post('main_id');
        $Organization = DecisionCategory::getRecordById($main_id);
        $message = DecisionCategory::approved_data_Listing($request);
        if (!empty($approvalData)) {
            self::newSwapOrderEdit($approvalData->intDisplayOrder, $Organization);
        }
        $newCmsPageObj = DecisionCategory::getRecordForLogById($main_id);
        $approval_obj = DecisionCategory::getRecordForLogById($approvalid);
        if ($flag == 'R') {
            $restoredata = Config::get('Constant.ROLLBACK_RECORD');
        } else {
            if ($approval_obj->chrDraft == 'D') {
                $restoredata = Config::get('Constant.DRAFT_RECORD_APPROVED');
            } else {
                $restoredata = Config::get('Constant.RECORD_APPROVED');
            }
        }
        $newCmsPageObj = DecisionCategory::getRecordForLogById($main_id);
        #Update record in menu
        $whereConditions = ['intRecordId' => $main_id, 'intfkModuleId' => Config::get('Constant.MODULE.ID')];
        $updateMenuFields = [
            'varTitle' => $newCmsPageObj->varTitle,
        ];
        CommonModel::updateRecords($whereConditions, $updateMenuFields, false, '\\Powerpanel\\Menu\\Models\\Menu');
        #Update record in menu
        /* notification for user to record approved */
        $careers = DecisionCategory::getRecordForLogById($id);
        if (method_exists($this->MyLibrary, 'userNotificationData')) {
            $userNotificationArr = MyLibrary::userNotificationData(Config::get('Constant.MODULE.ID'));
            $userNotificationArr['fkRecordId'] = $id;
            $userNotificationArr['txtNotification'] = 'Your request has been approved by ' . ucfirst(auth()->user()->name) . ' (' . ucfirst(Config::get('Constant.MODULE.NAME')) . ')';
            $userNotificationArr['fkIntUserId'] = Auth::user()->id;
            $userNotificationArr['chrNotificationType'] = 'A';
            $userNotificationArr['intOnlyForUserId'] = $careers->UserID;
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

    /**
     * This method handle to get child record.
     * @since   30-Aug-2018
     * @author  Rbhuva
     */
    public function getChildData(Request $request)
    {
        $requestArr = Request::all();
        $request = (object) $requestArr;
        $childHtml = "";
        $Decision_childData = "";
        $Decision_childData = DecisionCategory::getChildGrid($request->id);
        $childHtml .= "<div class=\"producttbl\" style=\"\">";
        $childHtml .= "<table class=\"new_table_desing table table-striped table-bordered table-hover table-checkable dataTable\" id=\"email_log_datatable_ajax\">
																<tr role=\"row\">
																		<th class=\"text-center\"></th>
																		<th class=\"text-center\">Title</th>
																		<th class=\"text-center\">Date Submitted</th>
																		<th class=\"text-center\">User</th>
																		<th class=\"text-center\">Preview</th>
																		<th class=\"text-center\">Edit</th>
																		<th class=\"text-center\">Status</th>";
        $childHtml .= "         </tr>";
        if (count($Decision_childData) > 0) {
            foreach ($Decision_childData as $child_row) {
                $childHtml .= "<tr role=\"row\">";
                if ($child_row->chrApproved == 'N') {
                    $childHtml .= "<td><span class='mob_show_title'>&nbsp</span><input type=\"checkbox\" name=\"delete\" class=\"chkDelete form-check-input\" value='" . $child_row->id . "'></td>";
                } else {
                    $childHtml .= "<td><span class='mob_show_title'>&nbsp</span><div class=\"checker\"><a href=\"javascript:;\" data-toggle=\"tooltip\" data-placement=\"right\" title=\"This is approved record, so can't be deleted.\"><i style=\"color:red\" class=\"ri-alert-fill\"></i></a></div></td>";
                }
                $childHtml .= '<td class="text-center"><span class="mob_show_title">Title: </span>' . $child_row->varTitle . '</td>';
                $childHtml .= "<td class=\"text-center\"><span class='mob_show_title'>Date Submitted: </span>" . date('M/d/Y h:i A', strtotime($child_row->created_at)) . "</td>";
                $childHtml .= "<td class=\"text-center\"><span class='mob_show_title'>User: </span>" . CommonModel::getUserName($child_row->UserID) . "</td>";
                $previewlink = url('/previewpage?url=' . MyLibrary::getFrontUri('decision-category')['uri'] . '/' . $child_row->id . '/preview');
                $childHtml .= "<td class=\"text-center\"><span class='mob_show_title'>Preview: </span><a class='icon_round' href=" . $previewlink . " target='_blank'><i class=\"ri-computer-line\"></i></a></td>";
                if ($child_row->chrApproved == 'N') {
                    $childHtml .= "<td class=\"text-center\"><span class='mob_show_title'>Edit: </span><a class='icon_round' title='" . trans("decision-category::template.common.edit") . "' href='" . route('powerpanel.decision-category.edit', array('alias' => $child_row->id)) . "'>
							<i class='ri-pencil-line'></i></a></td>";
                } else {
                    $childHtml .= "<td class=\"text-center\"><span class='mob_show_title'>Edit: </span>-</td>";
                }
                if ($child_row->chrApproved == 'N') {
                    $childHtml .= "<td class=\"text-center\"><span class='mob_show_title'>Status: </span><a title='" . trans("decision-category::template.common.comments") . "'  href=\"javascript:;\" onclick=\"loadModelpopup('" . $child_row->id . "','" . $child_row->UserID . "','" . Config::get('Constant.MODULE.MODEL_NAME') . "','" . $child_row->fkMainRecord . "')\" class=\"approve_icon_btn\"><i class=\"ri-chat-1-line\"></i> <span>Comment</span></a>    <a  onclick=\"update_mainrecord('" . $child_row->id . "','" . $child_row->fkMainRecord . "','" . $child_row->UserID . "','A');\" title='" . trans("decision-category::template.common.clickapprove") . "'  href=\"javascript:;\" class=\"approve_icon_btn\"><i class=\"ri-checkbox-line\"></i> <span>Approve</span></a></td>";
                } else {
                    $childHtml .= "<td class=\"text-center\"><span class='mob_show_title'>Status: </span><span class='mob_show_overflow'><i class=\"ri-checkbox-circle-line\" style=\"font-size:30px;\"></i><span style=\"display:block\"><strong>Approved On: </strong>" . date('M d Y h:i A', strtotime($child_row->dtApprovedDateTime)) . "</span><span style=\"display:block\"><strong>Approved By: </strong>" . CommonModel::getUserName($child_row->intApprovedBy) . "</span></span></td>";
                }
                $childHtml .= "</tr>";
            }
        } else {
            $childHtml .= "<tr><td colspan='7'>No Records</td></tr>";
        }
        $childHtml .= "</tr></td></tr>";
        $childHtml .= "</tr>
						</table>";
        echo $childHtml;
        exit;
    }

    public function getChildData_rollback(Request $request)
    {
        $requestArr = Request::all();
        $request = (object) $requestArr;
        $child_rollbackHtml = "";
        $Decision_rollbackchildData = "";
        $Decision_rollbackchildData = DecisionCategory::getChildrollbackGrid($request);
        $child_rollbackHtml .= "<div class=\"producttbl producttb2\" style=\"\">";
        $child_rollbackHtml .= "<table class=\"new_table_desing table table-striped table-bordered table-hover table-checkable dataTable\" id=\"email_log_datatable_ajax\">
																<tr role=\"row\">
																																																																<th class=\"text-center\">Title</th>
																		<th class=\"text-center\">Date</th>
																		<th class=\"text-center\">User</th>
																		<th class=\"text-center\">Preview</th>
																		<th class=\"text-center\">Status</th>";
        $child_rollbackHtml .= "         </tr>";
        if (count($Decision_rollbackchildData) > 0) {
            foreach ($Decision_rollbackchildData as $child_rollbacrow) {
                $child_rollbackHtml .= "<tr role=\"row\">";
                $child_rollbackHtml .= '<td class="text-center"><span class="mob_show_title">Title: </span>' . $child_rollbacrow->varTitle . '</td>';
                $child_rollbackHtml .= "<td class=\"text-center\"><span class='mob_show_title'>Date: </span>" . date('M d Y h:i A', strtotime($child_rollbacrow->created_at)) . "</td>";
                $child_rollbackHtml .= "<td class=\"text-center\"><span class='mob_show_title'>User: </span>" . CommonModel::getUserName($child_rollbacrow->UserID) . "</td>";
                $previewlink = url('/previewpage?url=' . MyLibrary::getFrontUri('decision-category')['uri'] . '/' . $child_rollbacrow->id . '/preview');
                $child_rollbackHtml .= "<td class=\"text-center\"><span class='mob_show_title'>Preview: </span><a class='icon_round' href=" . $previewlink . " target='_blank'><i class=\"ri-computer-line\"></i></a></td>";
                if ($child_rollbacrow->chrApproved == 'Y') {
                    $child_rollbackHtml .= "<td class=\"text-center\"><span class='mob_show_title'>Status: </span><i class=\"ri-checkbox-circle-line\" style=\"color: #1080F2;font-size:30px;\"></i></td>";
                } else {
                    // $child_rollbackHtml .= "<td class=\"text-center\"><span class='mob_show_title'>Status: </span><a onclick=\"update_mainrecord('" . $child_rollbacrow->id . "','" . $child_rollbacrow->fkMainRecord . "','" . $child_rollbacrow->UserID . "','R');\"  class=\"approve_icon_btn\">
                    //                         <i class=\"ri-history-line\"></i>  <span>RollBack</span>
                    // </a></td>";
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

    public function insertComents(Request $request)
    {
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

    public function get_builder_list()
    {
        $records = DecisionCategory::getAllCategory();
        $opt = '<option value="">Category</option>';
        foreach ($records as $record) {
            $opt .= '<option value="' . $record->id . '">' . $record->varTitle . '</option>';
        }
        return $opt;
    }

    public function getAllCategory()
    {
        $records = DecisionCategory::getAllCategory();
        $opt = '<option value="">Select Category</option>';
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

        $previousRecord = DecisionCategory::getPreviousRecordByMainId($request->id);
        if (!empty($previousRecord)) {

            $main_id = $previousRecord->fkMainRecord;
            $request->id = $previousRecord->id;
            $request->main_id = $main_id;

            $message = DecisionCategory::approved_data_Listing($request);

            /* notification for user to record approved */
            $blogs = DecisionCategory::getRecordForLogById($previousRecord->id);
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
            $newBlogObj = DecisionCategory::getRecordForLogById($main_id);
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
