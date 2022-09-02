<?php

namespace Powerpanel\PublicRecord\Controllers\Powerpanel;

use App\Alias;
use App\CommonModel;
use App\Document;
use App\Helpers\AddDocumentModelRel;
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
use Config;
use DB;
use File;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Redirect;
use Powerpanel\CmsPage\Models\CmsPage;
use Powerpanel\PublicRecordCategory\Models\PublicRecordCategory;
use Powerpanel\PublicRecord\Models\PublicRecord;
use Powerpanel\RoleManager\Models\Role_user;
use Powerpanel\Workflow\Models\Comments;
use Powerpanel\Workflow\Models\Workflow;
use Powerpanel\Workflow\Models\WorkflowLog;
use Request;
use Validator;

class PublicRecordController extends PowerpanelController
{

    public $catModule;

    public function __construct()
    {
        parent::__construct();
        if (isset($_COOKIE['locale'])) {
            app()->setLocale($_COOKIE['locale']);
        }
        $this->MyLibrary = new MyLibrary();
        $this->CommonModel = new CommonModel();
        $this->Alias = new Alias();
    }

    /**
     * This method handels load process of public-record
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

         $admin = $this->currentUserRoleData->varSector;
        $iTotalRecords = PublicRecord::getRecordCount(false, false, $userIsAdmin, $this->currentUserRoleSector);
        $NewRecordsCount = PublicRecord::getNewRecordsCount($userIsAdmin, $this->currentUserRoleSector);
        $draftTotalRecords = PublicRecord::getRecordCountforListDarft(false, true, $userIsAdmin, array(), $this->currentUserRoleSector);
        $trashTotalRecords = PublicRecord::getRecordCountforListTrash(false, false, $userIsAdmin, [], $this->currentUserRoleSector);
        $favoriteTotalRecords = PublicRecord::getRecordCountforListFavorite(false, false, $userIsAdmin, [], $this->currentUserRoleSector);
        $pageData = Modules::getAllModuleData('public-record-category');

        if (isset($pageData->varModuleNameSpace) && $pageData->varModuleNameSpace != '') {
            $MODEL = $pageData->varModuleNameSpace . 'Models\\' . $pageData->varModelName;
        } else {
            $MODEL = '\\App\\' . $pageData->varModelName;
        }
        if (isset($userIsAdmin) && $userIsAdmin == 'true') {
            $categories = ParentRecordHierarchy_builder::Parentrecordhierarchy_singleselectTypeArr($selected_id = false, $post_id = false, $MODEL);
        } else {

            $categories = ParentRecordHierarchy_builder::Parentrecordhierarchy_singleselectTypeArr($selected_id = false, $post_id = false, $MODEL, $admin);
        }
        $this->breadcrumb['title'] = trans('public-record::template.publicrecordModule.managePublicRecord');
        $breadcrumb = $this->breadcrumb;
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

        return view('public-record::powerpanel.index', compact('iTotalRecords', 'breadcrumb', 'NewRecordsCount', 'userIsAdmin', 'categories', 'draftTotalRecords', 'trashTotalRecords', 'favoriteTotalRecords', 'settingarray'));
    }

     public static function getSectorwiseCategoryGrid() {
        $data = Request::input();
        if (isset($data['sectorname']) && !empty($data['sectorname'])) {
            $sectorname = $data['sectorname'];
        }
        else{
        $sectorname = '';
        }
        $pageData = Modules::getAllModuleData('public-record-category');
        if (isset($pageData->varModuleNameSpace) && $pageData->varModuleNameSpace != '') {
            $MODEL = $pageData->varModuleNameSpace . 'Models\\' . $pageData->varModelName;
        } else {
            $MODEL = '\\App\\' . $pageData->varModelName;
        }
        if (isset($sectorname) && !empty($sectorname)) {
            
            $categories = ParentRecordHierarchy_builder::Parentrecordhierarchy_singleselectTypeArr($selected_id = false, $post_id = false, $MODEL, $sectorname);
        } else {
           
             $categories = ParentRecordHierarchy_builder::Parentrecordhierarchy_singleselectTypeArr($selected_id = false, $post_id = false, $MODEL);
        }
        return $categories;
    }
    
     public static function getCategory()
    {
        $data = Request::input();
//        echo '<pre>';print_r($data);exit;
        $admin = $data['sectorname'];
        $selected_id = $data['selectedCategory'];
        $post_id = $data['selectedId'];
        $pageData = Modules::getAllModuleData('public-record-category');
        if (isset($pageData->varModuleNameSpace) && $pageData->varModuleNameSpace != '') {
            $MODEL = $pageData->varModuleNameSpace . 'Models\\' . $pageData->varModelName;
        } else {
            $MODEL = '\\App\\' . $pageData->varModelName;
        }
        $categories = ParentRecordHierarchy_builder::Parentrecordhierarchy_singleselectTypeArr($selected_id, $post_id, $MODEL, $admin);

//        echo '<pre>';print_r($categories);exit;
        //        }
        return $categories;
    }
    /**
     * This method loads public-record edit view
     * @param   Alias of record
     * @return  View
     * @since   2017-11-10
     * @author  NetQuick
     */
    public function edit($id = false)
    {
        $documentManager = true;
        $imageManager = false;
        $videoManager = false;
        $userIsAdmin = false;
        if (isset($this->currentUserRoleData) && !empty($this->currentUserRoleData)) {
            if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                $userIsAdmin = true;
            }
        }

        $pageData = Modules::getAllModuleData('public-record-category');
        if (isset($pageData->varModuleNameSpace) && $pageData->varModuleNameSpace != '') {
            $MODEL = $pageData->varModuleNameSpace . 'Models\\' . $pageData->varModelName;
        } else {
            $MODEL = '\\App\\' . $pageData->varModelName;
        }
        if (!is_numeric($id)) {
            $categories = ParentRecordHierarchy_builder::Parentrecordhierarchy_singleselectTypeArr($selected_id = false, $post_id = false, $MODEL);
            $this->breadcrumb['title'] = trans('public-record::template.publicrecordModule.addPublicRecord');
            $this->breadcrumb['module'] = trans('public-record::template.publicrecordModule.managePublicRecord');
            $this->breadcrumb['url'] = 'powerpanel/public-record';
            $this->breadcrumb['inner_title'] = trans('public-record::template.publicrecordModule.addPublicRecord');
            $breadcrumb = $this->breadcrumb;
            $data = compact('documentManager', 'breadcrumb', 'imageManager', 'videoManager', 'userIsAdmin', 'categories');
        } else {
            $documentManager = true;
            $publicrecord = PublicRecord::getRecordById($id);
            if (empty($publicrecord)) {
                return redirect()->route('powerpanel.public-record.add');
            }
            $categories = ParentRecordHierarchy_builder::Parentrecordhierarchy_singleselectTypeArr($publicrecord->txtCategories, $publicrecord->id, $MODEL);
            if ($publicrecord->fkMainRecord != '0') {
                $publicrecord_highLight = PublicRecord::getRecordById($publicrecord->fkMainRecord);
                $templateData['publicrecord_highLight'] = $publicrecord_highLight;

                $display_publish = $publicrecord_highLight['chrPublish'];
            } else {
                $publicrecord_highLight = "";
                $templateData['publicrecord_highLight'] = "";

                $display_publish = '';
            }

            $this->breadcrumb['title'] = trans('public-record::template.publicrecordModule.editPublicRecord') . ' - ' . $publicrecord->varTitle;
            $this->breadcrumb['module'] = trans('public-record::template.publicrecordModule.managePublicRecord');
            $this->breadcrumb['url'] = 'powerpanel/public-record';
            $this->breadcrumb['inner_title'] = trans('public-record::template.publicrecordModule.editPublicRecord') . ' - ' . $publicrecord->varTitle;
            $breadcrumb = $this->breadcrumb;
            $data = compact('publicrecord', 'documentManager', 'breadcrumb', 'imageManager', 'videoManager', 'publicrecord_highLight', 'display_publish', 'userIsAdmin', 'categories');
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
        $data['userIsAdmin'] = $userIsAdmin;
        $data['MyLibrary'] = $this->MyLibrary;
        //End Button Name Change For User Side
        return view('public-record::powerpanel.actions', $data);
    }

    /**
     * This method stores public-record modifications
     * @return  View
     * @since   2017-11-10
     * @author  NetQuick
     */
    public function handlePost(Request $request)
    {
        $requestArr = Request::all();
        $request = (object) $requestArr;
        $approval = false;
        $userIsAdmin = false;
        if (!empty($this->currentUserRoleData)) {
            if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                $userIsAdmin = true;
            }
        } else {
            $userIsAdmin = true;
        }
        $data = Request::all();
        $rules = array(
            'title' => 'required|max:200|handle_xss|no_url',
            'sector' => 'required',
            'chrMenuDisplay' => 'required',
            'author' => 'required|handle_xss|no_url',
            'category_id' => 'required',
        );
        $actionMessage = trans('public-record::template.common.oppsSomethingWrong');
        $messsages = array(
            'title.required' => 'Title field is required.',
            'sector.required' => 'Sector field is required.',
            'author.required' => trans('public-record::template.publicrecordModule.author'),
            'category_id.required' => trans('public-record::template.publicrecordModule.categoryMessage'),

        );
        $validator = Validator::make($data, $rules, $messsages);
        if ($validator->passes()) {
            $module = Modules::getModuleById(Config::get('Constant.MODULE.ID'));

            $publicrecordArr = [];
            $publicrecordArr['varTitle'] = stripslashes(trim($data['title']));
            $publicrecordArr['fkIntDocId'] = !empty($data['doc_id']) ? $data['doc_id'] : null;
            $publicrecordArr['dtDateTime'] = !empty($data['start_date_time']) ? date('Y-m-d H:i:s', strtotime(str_replace('/', '-',$data['start_date_time']))) : date('Y-m-d H:i:s');
            $publicrecordArr['dtEndDateTime'] = null;

            $publicrecordArr['varAuthor'] = $data['author'];

            $publicrecordArr['UserID'] = auth()->user()->id;
            if ($data['chrMenuDisplay'] == 'D') {
                $publicrecordArr['chrDraft'] = 'D';
                $publicrecordArr['chrPublish'] = 'N';
            } else {
                $publicrecordArr['chrDraft'] = 'N';
                $publicrecordArr['chrPublish'] = $data['chrMenuDisplay'];
            }
            if (isset($data['chrPageActive']) && $data['chrPageActive'] != '') {
                $publicrecordArr['chrPageActive'] = $data['chrPageActive'];
            }
            if (isset($data['chrPageActive']) && $data['chrPageActive'] == 'PP') {
                $publicrecordArr['varPassword'] = $data['new_password'];
            } else {
                $publicrecordArr['varPassword'] = '';
            }
            if ($data['chrMenuDisplay'] == 'D') {
                $addlog = Config::get('Constant.UPDATE_DRAFT');
            } else {
                $addlog = '';
            }

            $publicrecordArr['txtCategories'] = isset($data['category_id']) ? $data['category_id'] : null;
            $id = Request::segment(3);
            if (is_numeric($id)) { #Edit post Handler=======

                $publicrecord = PublicRecord::getRecordForLogById($id);
                $whereConditions = ['id' => $publicrecord->id];
                $publicrecordArr['varSector'] = $data['sector'];
                if ($publicrecord->chrLock == 'Y' && auth()->user()->id != $publicrecord->LockUserID) {
                    if ($this->currentUserRoleData->chrIsAdmin != 'Y') {
                        $lockedUserData = User::getRecordById($publicrecord->LockUserID, true);
                        $lockedUserName = 'someone';
                        if (!empty($lockedUserData)) {
                            $lockedUserName = $lockedUserData->name;
                        }
                        $actionMessage = "This record has been locked by " . $lockedUserName . ".";
                        return redirect()->route('powerpanel.public-record.index')->with('message', $actionMessage);
                    }
                }
                if (File::exists(app_path() . '/Workflow.php') != null || File::exists(base_path() . '/packages/Powerpanel/Workflow/src/Models/Workflow.php') != null) {
                    if (!$userIsAdmin) {
                        $userRole = $this->currentUserRoleData->id;
                    } else {
                        $userRoleData = Role_user::getUserRoleByUserId($publicrecord->UserID);
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
                        if ($publicrecord->fkMainRecord == '0' || empty($workFlowByCat->varUserId)) {
                            $update = CommonModel::updateRecords($whereConditions, $publicrecordArr, false, 'Powerpanel\PublicRecord\Models\PublicRecord');
                            if ($update) {
                                if (!empty($id)) {
                                    $logArr = MyLibrary::logData($publicrecord->id, false, $addlog);
                                    if (Auth::user()->can('log-advanced')) {
                                        $newPublicRecordObj = PublicRecord::getRecordForLogById($publicrecord->id);
                                        $oldRec = $this->recordHistory($publicrecord);
                                        $newRec = $this->newrecordHistory($publicrecord, $newPublicRecordObj);
                                        $logArr['old_val'] = $oldRec;
                                        $logArr['new_val'] = $newRec;
                                    }
                                    $logArr['varTitle'] = trim($data['title']);
                                    Log::recordLog($logArr);
                                    if (Auth::user()->can('recent-updates-list')) {
                                        if (!isset($newPublicRecordObj)) {
                                            $newPublicRecordObj = PublicRecord::getRecordForLogById($publicrecord->id);
                                        }
                                        $notificationArr = MyLibrary::notificationData($publicrecord->id, $newPublicRecordObj);
                                        RecentUpdates::setNotification($notificationArr);
                                    }
                                    self::flushCache();
                                    if (isset($data['saveandexit']) && $data['saveandexit'] == 'approvesaveandexit') {
                                        $actionMessage = trans('public-record::template.common.recordApprovalMessage');
                                    } else {
                                        $actionMessage = trans('public-record::template.publicrecordModule.updateMessage');
                                    }
                                }
                            }
                        } else {
                            $updateModuleFields = $publicrecordArr;
                            $this->insertApprovedRecord($updateModuleFields, $data, $id);
                            if (isset($data['saveandexit']) && $data['saveandexit'] == 'approvesaveandexit') {
                                $actionMessage = trans('public-record::template.common.recordApprovalMessage');
                            } else {
                                $actionMessage = trans('public-record::template.publicrecordModule.updateMessage');
                            }
                            $approval = $id;
                        }
                    } else {
                        if ($workFlowByCat->charNeedApproval == 'Y') {
                            $approvalObj = $this->insertApprovalRecord($publicrecord, $data, $publicrecordArr);
                            if (isset($data['saveandexit']) && $data['saveandexit'] == 'approvesaveandexit') {
                                $actionMessage = trans('public-record::template.common.recordApprovalMessage');
                            } else {
                                $actionMessage = trans('public-record::template.publicrecordModule.updateMessage');
                            }
                            $approval = $approvalObj->id;
                        }
                    }
                } else {
                    $update = CommonModel::updateRecords($whereConditions, $publicrecordArr, false, 'Powerpanel\PublicRecord\Models\PublicRecord');
                    $actionMessage = trans('public-record::template.publicrecordModule.updateMessage');
                }
            } else { #Add post Handler=======
            if (File::exists(app_path() . '/Workflow.php') != null || File::exists(base_path() . '/packages/Powerpanel/Workflow/src/Models/Workflow.php') != null) {
                $workFlowByCat = Workflow::getRecordByCategoryId($module->intFkGroupCode, $this->currentUserRoleData->id, Config::get('Constant.MODULE.ID'));
            }
                if (!empty($workFlowByCat->varUserId) && $workFlowByCat->chrNeedAddPermission == 'Y' && !$userIsAdmin) {
                    $publicrecordArr['chrPublish'] = 'N';
                    $publicrecordArr['chrDraft'] = 'N';
                    $publicrecordObj = $this->insertNewRecord($data, $publicrecordArr);
                    if ($data['chrMenuDisplay'] == 'D') {
                        $publicrecordArr['chrDraft'] = 'D';
                    }
                    $publicrecordArr['chrPublish'] = 'Y';
                    $approvalObj = $this->insertApprovalRecord($publicrecordObj, $data, $publicrecordArr);
                    $approval = $publicrecordObj->id;
                } else {
                    $publicrecordObj = $this->insertNewRecord($data, $publicrecordArr);
                    $approval = $publicrecordObj->id;
                }
                if (isset($data['saveandexit']) && $data['saveandexit'] == 'approvesaveandexit') {
                    $actionMessage = trans('public-record::template.common.recordApprovalMessage');
                } else {
                    $actionMessage = trans('public-record::template.publicrecordModule.addMessage');
                }
                $id = $publicrecordObj->id;
            }
            AddDocumentModelRel::sync(explode(',', $data['doc_id']), $id, $approval);

            if ((!empty($request->saveandexit) && $request->saveandexit == 'saveandexit') || !$userIsAdmin) {
                if ($data['chrMenuDisplay'] == 'D') {
                    return redirect()->route('powerpanel.public-record.index', 'tab=D')->with('message', $actionMessage);
                } else {
                    return redirect()->route('powerpanel.public-record.index')->with('message', $actionMessage);
                }
            } else {
                return redirect()->route('powerpanel.public-record.edit', $id)->with('message', $actionMessage);
            }
        } else {
            return Redirect::back()->withErrors($validator)->withInput();
        }
    }

    public function insertApprovedRecord($updateModuleFields, $postArr, $id)
    {
        $whereConditions = ['id' => $postArr['fkMainRecord']];
        $updateModuleFields['chrAddStar'] = 'N';
        $update = CommonModel::updateRecords($whereConditions, $updateModuleFields, false, 'Powerpanel\PublicRecord\Models\PublicRecord');
        $whereConditions_ApproveN = ['fkMainRecord' => $postArr['fkMainRecord']];
        $updateToApproveN = [
            'chrApproved' => 'N',
            'chrLetest' => 'N',
            'intApprovedBy' => '0',
        ];
        CommonModel::updateRecords($whereConditions_ApproveN, $updateToApproveN, false, 'Powerpanel\PublicRecord\Models\PublicRecord');
        $whereConditionsApprove = ['id' => $id, 'chrMain' => 'N'];
        $updateToApprove = [
            'chrApproved' => 'Y',
            'chrRollBack' => 'Y',
            'intApprovedBy' => auth()->user()->id,
        ];
        CommonModel::updateRecords($whereConditionsApprove, $updateToApprove, false, 'Powerpanel\PublicRecord\Models\PublicRecord');
        if ($postArr['chrMenuDisplay'] == 'D') {
            $addlog = Config::get('Constant.DRAFT_RECORD_APPROVED');
        } else {
            $addlog = Config::get('Constant.RECORD_APPROVED');
        }
        $newCmsPageObj = PublicRecord::getRecordForLogById($id);
        $logArr = MyLibrary::logData($id, false, $addlog);
        $logArr['varTitle'] = stripslashes($newCmsPageObj->varTitle);
        Log::recordLog($logArr);
        /* notification for user to record approved */
        $careers = PublicRecord::getRecordForLogById($id);
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
                $where['fkRecordId'] = (isset($postArr['fkMainRecord']) && (int) $postArr['fkMainRecord'] != 0) ? $postArr['fkMainRecord'] : $id;
                $where['dtYes'] = 'null';
                WorkflowLog::updateRecord($flowData, $where);
            }
        }
    }

    public function insertApprovalRecord($moduleObj, $postArr, $publicrecordArr)
    {
        $response = false;

        $publicrecordArr['chrMain'] = 'N';
        $publicrecordArr['chrLetest'] = 'Y';
        $publicrecordArr['fkMainRecord'] = $moduleObj->id;

        if ($postArr['chrMenuDisplay'] == 'D') {
            $publicrecordArr['chrDraft'] = 'D';
            $publicrecordArr['chrPublish'] = 'N';
        } else {
            $publicrecordArr['chrDraft'] = 'N';
            $publicrecordArr['chrPublish'] = $postArr['chrMenuDisplay'];
        }
        if (isset($postArr['chrPageActive']) && $postArr['chrPageActive'] != '') {
            $publicrecordArr['chrPageActive'] = $postArr['chrPageActive'];
        }
        if (isset($postArr['chrPageActive']) && $postArr['chrPageActive'] == 'PP') {
            $publicrecordArr['varPassword'] = $postArr['new_password'];
        } else {
            $publicrecordArr['varPassword'] = '';
        }

        if ($postArr['chrMenuDisplay'] == 'D') {
            $addlog = Config::get('Constant.DRAFT_SENT_FOR_APPROVAL');
        } else {
            $addlog = Config::get('Constant.SENT_FOR_APPROVAL');
        }
        $publicrecordID = CommonModel::addRecord($publicrecordArr, 'Powerpanel\PublicRecord\Models\PublicRecord');
        if (!empty($publicrecordID)) {
            $id = $publicrecordID;
            WorkflowLog::addRecord([
                'fkModuleId' => Config::get('Constant.MODULE.ID'),
                'fkRecordId' => $moduleObj->id,
                'charApproval' => 'Y',
            ]);
            if (method_exists($this->MyLibrary, 'userNotificationData')) {
                $userNotificationArr = MyLibrary::userNotificationData(Config::get('Constant.MODULE.ID'));
                $userNotificationArr['fkRecordId'] = $moduleObj->id;
                $userNotificationArr['txtNotification'] = 'New approval request from ' . ucfirst(auth()->user()->name) . ' (' . ucfirst(Config::get('Constant.MODULE.NAME')) . ')';
                $userNotificationArr['fkIntUserId'] = Auth::user()->id;
                $userNotificationArr['chrNotificationType'] = 'A';
                UserNotification::addRecord($userNotificationArr);
            }
            $newPublicRecordObj = PublicRecord::getRecordForLogById($id);
            $logArr = MyLibrary::logData($id, false, $addlog);
            $logArr['varTitle'] = $newPublicRecordObj->varTitle;
            Log::recordLog($logArr);
            if (Auth::user()->can('recent-updates-list')) {
                $notificationArr = MyLibrary::notificationData($id, $newPublicRecordObj);
                RecentUpdates::setNotification($notificationArr);
            }
            $response = $newPublicRecordObj;
            self::flushCache();
            $actionMessage = trans('public-record::template.publicrecordModule.addMessage');
        }
        $whereConditionsAddstar = ['id' => $moduleObj->id];
        $updateAddStar = [
            'chrAddStar' => 'Y',
        ];
        CommonModel::updateRecords($whereConditionsAddstar, $updateAddStar, false, 'Powerpanel\PublicRecord\Models\PublicRecord');
        return $response;
    }

    public function insertNewRecord($postArr, $publicrecordArr)
    {
        $response = false;
        $publicrecordArr['varSector'] = $postArr['sector'];

        $publicrecordArr['chrMain'] = 'Y';

        if ($postArr['chrMenuDisplay'] == 'D') {
            $publicrecordArr['chrDraft'] = 'D';
            $publicrecordArr['chrPublish'] = 'N';
        } else {
            $publicrecordArr['chrDraft'] = 'N';
        }
        if (isset($postArr['chrPageActive']) && $postArr['chrPageActive'] != '') {
            $publicrecordArr['chrPageActive'] = $postArr['chrPageActive'];
        }
        if (isset($postArr['chrPageActive']) && $postArr['chrPageActive'] == 'PP') {
            $publicrecordArr['varPassword'] = $postArr['new_password'];
        } else {
            $publicrecordArr['varPassword'] = '';
        }
        if ($postArr['chrMenuDisplay'] == 'D') {
            $addlog = Config::get('Constant.ADDED_DRAFT');
        } else {
            $addlog = '';
        }
        $publicrecordID = CommonModel::addRecord($publicrecordArr, 'Powerpanel\PublicRecord\Models\PublicRecord');
        if (!empty($publicrecordID)) {
            $id = $publicrecordID;
            $newPublicRecordObj = PublicRecord::getRecordForLogById($id);
            $logArr = MyLibrary::logData($id, false, $addlog);
            $logArr['varTitle'] = stripslashes($newPublicRecordObj->varTitle);
            Log::recordLog($logArr);
            if (Auth::user()->can('recent-updates-list')) {
                $notificationArr = MyLibrary::notificationData($id, $newPublicRecordObj);
                RecentUpdates::setNotification($notificationArr);
            }
            $response = $newPublicRecordObj;
            self::flushCache();
            $actionMessage = trans('public-record::template.publicrecordModule.addMessage');
        }
        return $response;
    }

    /**
     * This method loads public-record table data on view
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
        $filterArr['sectorFilter'] = !empty(Request::input('sectorValue')) ? Request::input('sectorValue') : '';
        $filterArr['catFilter'] = !empty(Request::input('catValue')) ? Request::input('catValue') : '';
        $filterArr['searchFilter'] = !empty(Request::input('searchValue')) ? Request::input('searchValue') : '';
        $filterArr['customFilterIdentity'] = !empty(Request::get('customFilterIdentity')) ? Request::get('customFilterIdentity') : '';
        $filterArr['iDisplayLength'] = intval(Request::input('length'));
        $filterArr['iDisplayStart'] = intval(Request::input('start'));
        $filterArr['rangeFilter'] = !empty(Request::input('rangeFilter')) ? Request::input('rangeFilter') : '';
        $sEcho = intval(Request::input('draw'));
        $arrResults = PublicRecord::getRecordList_tab1($filterArr, $userIsAdmin, $this->currentUserRoleSector);
        $iTotalRecords = PublicRecord::getRecordCountListApprovalTab($filterArr, $userIsAdmin, $this->currentUserRoleSector);
        $end = $filterArr['iDisplayStart'] + $filterArr['iDisplayLength'];
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;
        if (!empty($arrResults)) {
            foreach ($arrResults as $key => $value) {
                $records["data"][] = $this->tableData_tab1($value);
            }
        }
        $NewRecordsCount = PublicRecord::getNewRecordsCount($userIsAdmin, $this->currentUserRoleSector);
        $records["newRecordCount"] = $NewRecordsCount;
        $records["customActionStatus"] = "OK";
        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;
        return json_encode($records);
    }

    public function get_list()
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
        $filterArr['sectorFilter'] = !empty(Request::input('sectorValue')) ? Request::input('sectorValue') : '';
        $filterArr['catFilter'] = !empty(Request::input('catValue')) ? Request::input('catValue') : '';
        $filterArr['searchFilter'] = !empty(Request::input('searchValue')) ? Request::input('searchValue') : '';
        $filterArr['customFilterIdentity'] = !empty(Request::get('customFilterIdentity')) ? Request::get('customFilterIdentity') : '';
        $filterArr['iDisplayLength'] = intval(Request::input('length'));
        $filterArr['iDisplayStart'] = intval(Request::input('start'));
        $filterArr['rangeFilter'] = !empty(Request::input('rangeFilter')) ? Request::input('rangeFilter') : '';
        //        echo '<pre>';print_r($filterArr['rangeFilter']);exit;
        $sEcho = intval(Request::input('draw'));
        $isAdmin = false;
        if ($userIsAdmin) {
            $isAdmin = true;
        }
        $module = Modules::getModule('pages');

        $arrResults = PublicRecord::getRecordList($filterArr, $isAdmin, $this->currentUserRoleSector);
        $iTotalRecords = PublicRecord::getRecordCountforList($filterArr, true, $isAdmin, $this->currentUserRoleSector);
        $end = $filterArr['iDisplayStart'] + $filterArr['iDisplayLength'];
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;
        if (!empty($arrResults)) {
            foreach ($arrResults as $key => $value) {
                $records["data"][] = $this->tableData($value, false);
            }
        }
        $NewRecordsCount = PublicRecord::getNewRecordsCount($isAdmin, $this->currentUserRoleSector);
        $records["newRecordCount"] = $NewRecordsCount;
        $records["customActionStatus"] = "OK";
        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;
        return json_encode($records);
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
        $records["data"] = [];
        $filterArr['orderColumnNo'] = (!empty(Request::input('order')[0]['column']) ? Request::input('order')[0]['column'] : '');
        $filterArr['orderByFieldName'] = (!empty(Request::input('columns')[$filterArr['orderColumnNo']]['name']) ? Request::input('columns')[$filterArr['orderColumnNo']]['name'] : '');
        $filterArr['orderTypeAscOrDesc'] = (!empty(Request::input('order')[0]['dir']) ? Request::input('order')[0]['dir'] : '');
        $filterArr['statusFilter'] = !empty(Request::input('statusValue')) ? Request::input('statusValue') : '';
        $filterArr['sectorFilter'] = !empty(Request::input('sectorValue')) ? Request::input('sectorValue') : '';
        $filterArr['catFilter'] = !empty(Request::input('catValue')) ? Request::input('catValue') : '';
        $filterArr['searchFilter'] = !empty(Request::input('searchValue')) ? Request::input('searchValue') : '';
        $filterArr['customFilterIdentity'] = !empty(Request::get('customFilterIdentity')) ? Request::get('customFilterIdentity') : '';
        $filterArr['iDisplayLength'] = intval(Request::input('length'));
        $filterArr['iDisplayStart'] = intval(Request::input('start'));
        $filterArr['rangeFilter'] = !empty(Request::input('rangeFilter')) ? Request::input('rangeFilter') : '';
        $sEcho = intval(Request::input('draw'));
        $isAdmin = false;
        if ($userIsAdmin) {
            $isAdmin = true;
        }
        $module = Modules::getModule('pages');
        $cmsPageForModule = CmsPage::getRecordForPowerpanelShareByModuleId(Config::get('Constant.MODULE.ID'), $module->id);
        $arrResults = PublicRecord::getRecordListFavorite($filterArr, $isAdmin, $this->currentUserRoleSector);
        $iTotalRecords = PublicRecord::getRecordCountforListFavorite($filterArr, true, $isAdmin, [], $this->currentUserRoleSector);
        $end = $filterArr['iDisplayStart'] + $filterArr['iDisplayLength'];
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;
        if (!empty($arrResults)) {
            foreach ($arrResults as $key => $value) {
                $records["data"][] = $this->tableDataFavorite($value, $cmsPageForModule);
            }
        }
        $NewRecordsCount = PublicRecord::getNewRecordsCount($isAdmin, $this->currentUserRoleSector);
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
        $filterArr['sectorFilter'] = !empty(Request::input('sectorValue')) ? Request::input('sectorValue') : '';
        $filterArr['customFilterIdentity'] = !empty(Request::get('customFilterIdentity')) ? Request::get('customFilterIdentity') : '';
        $filterArr['catFilter'] = !empty(Request::input('catValue')) ? Request::input('catValue') : '';
        $filterArr['searchFilter'] = !empty(Request::input('searchValue')) ? Request::input('searchValue') : '';
        $filterArr['iDisplayLength'] = intval(Request::input('length'));
        $filterArr['iDisplayStart'] = intval(Request::input('start'));
        $filterArr['rangeFilter'] = !empty(Request::input('rangeFilter')) ? Request::input('rangeFilter') : '';
        $sEcho = intval(Request::input('draw'));
        $isAdmin = false;
        if ($userIsAdmin) {
            $isAdmin = true;
        }
        $module = Modules::getModule('pages');
        $cmsPageForModule = CmsPage::getRecordForPowerpanelShareByModuleId(Config::get('Constant.MODULE.ID'), $module->id);
        $arrResults = PublicRecord::getRecordListDraft($filterArr, $userIsAdmin, $this->currentUserRoleSector);
        $iTotalRecords = PublicRecord::getRecordCountforListDarft($filterArr, true, $isAdmin, [], $this->currentUserRoleSector);
        $end = $filterArr['iDisplayStart'] + $filterArr['iDisplayLength'];
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;
        if (!empty($arrResults)) {
            foreach ($arrResults as $key => $value) {
                $records["data"][] = $this->tableDataDraft($value, $cmsPageForModule);
            }
        }
        $NewRecordsCount = PublicRecord::getNewRecordsCount($userIsAdmin, $this->currentUserRoleSector);
        $records["newRecordCount"] = $NewRecordsCount;
        $records["customActionStatus"] = "OK";
        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;
        return json_encode($records);
    }

    public function get_list_trash()
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
        $filterArr['sectorFilter'] = !empty(Request::input('sectorValue')) ? Request::input('sectorValue') : '';
        $filterArr['customFilterIdentity'] = !empty(Request::get('customFilterIdentity')) ? Request::get('customFilterIdentity') : '';
        $filterArr['catFilter'] = !empty(Request::input('catValue')) ? Request::input('catValue') : '';
        $filterArr['searchFilter'] = !empty(Request::input('searchValue')) ? Request::input('searchValue') : '';
        $filterArr['iDisplayLength'] = intval(Request::input('length'));
        $filterArr['iDisplayStart'] = intval(Request::input('start'));
        $filterArr['rangeFilter'] = !empty(Request::input('rangeFilter')) ? Request::input('rangeFilter') : '';
        $sEcho = intval(Request::input('draw'));
        $isAdmin = false;
        if ($userIsAdmin) {
            $isAdmin = true;
        }
        $module = Modules::getModule('pages');
        $cmsPageForModule = CmsPage::getRecordForPowerpanelShareByModuleId(Config::get('Constant.MODULE.ID'), $module->id);
        $arrResults = PublicRecord::getRecordListTrash($filterArr, $isAdmin);
        $iTotalRecords = PublicRecord::getRecordCountforListTrash($filterArr, true, $isAdmin, [], $this->currentUserRoleSector);
        $end = $filterArr['iDisplayStart'] + $filterArr['iDisplayLength'];
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;
        if (!empty($arrResults)) {
            foreach ($arrResults as $key => $value) {
                $records["data"][] = $this->tableDataTrash($value, $cmsPageForModule);
            }
        }
        $NewRecordsCount = PublicRecord::getNewRecordsCount($isAdmin, $this->currentUserRoleSector);
        $records["newRecordCount"] = $NewRecordsCount;
        $records["customActionStatus"] = "OK";
        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;
        return json_encode($records);
    }

    public function tableData($value = false, $moduleCmsPageData = false)
    {
        $sector = '';
        if (isset($value->varSector) && !empty($value->varSector)) {
            $sector = strtoupper($value->varSector);
        }
        $Hits = Pagehit::where('fkIntAliasId', $value->intAliasId)->count();
        $webHits = '';
        if ($Hits > 0) {
            $webHits .= '<a data-toggle="modal" href="#" onclick=\'HitsPopup("' . $value->id . '","' . $value->intAliasId . '","' . $value->varTitle . '","P")\'>' . $Hits . '</a>
										<div class="new_modal modal fade" id="desc_' . $value->id . '_P" tabindex="-1" aria-hidden="true">
												<div class="modal-dialog" style="margin: 0 auto;display:table;width: 100%;height:100%;max-width: 1000px;">
												<div class="modal-vertical">
												<div class="modal-content">
										<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
										<h3 class="modal-title">Hits Report</h3>
										</div>
										<div class="modal-body">
										<div id="webdata_' . $value->id . '_P"></div>
										</div>
										</div>
										</div>
										</div>
										</div>';
        } else {
            $webHits .= '0';
        }
        $details = '';
        $publish_action = '';
        if (Auth::user()->can('public-record-edit')) {
            $details .= '<a class="" title="' . trans("public-record::template.common.edit") . '" href="' . route('powerpanel.public-record.edit', array('alias' => $value->id)) . '"><i class="ri-pencil-line"></i></a>';
        }
        if (Auth::user()->can('public-record-delete') || (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y')) {
            if (Config::get('Constant.DEFAULT_TRASH') == 'Y') {
                $details .= '<a class="delete-grid" title="' . trans("public-record::template.common.delete") . '" onclick = \'Trashfun("' . $value->id . '")\' data-controller="public-record" data-alias = "' . $value->id . '" data-tab="P"><i class="ri-delete-bin-line"></i></a>';
            } else {
                $details .= '<a class="delete" title="' . trans("public-record::template.common.delete") . '" data-controller="public-record" data-alias = "' . $value->id . '" data-tab="P"><i class="ri-delete-bin-line"></i></a>';
            }
        }
        if ($value->chrAddStar != 'Y') {
            if ($value->chrDraft != 'D') {
                if (!empty($value->chrPublish) && ($value->chrPublish == 'Y')) {
                    //Bootstrap Switch
                    $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/public-record', 'data_alias'=>$value->id, 'title'=>trans("public-record::template.common.publishedRecord"), 'data_value'=>'Unpublish'])->render();
                } else {
                    //Bootstrap Switch
                    $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/public-record', 'data_alias'=>$value->id, 'title'=>trans("public-record::template.common.unpublishedRecord"), 'data_value'=>'Publish', 'checked'=>'checked'])->render();
                }
            } else {
                //Bootstrap Switch
                $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/public-record', 'data_alias'=>$value->id, 'title'=>trans("public-record::template.common.unpublishedRecord"), 'data_value'=>'Publish', 'checked'=>'checked'])->render();
            }
        } else {
            $publish_action .= '---';
        }

        
        $category = '';
        if (isset($value->txtCategories)) {
            $categoryIDs = [$value->txtCategories];
            $selCategory = PublicRecordCategory::getParentCategoryNameBycatId($categoryIDs);
          
            foreach ($selCategory as $selCat) {
                if (strlen(trim($selCat)) > 0) {
                   
                    $category = $selCat->varTitle;
                    
                }
            }
          
        } else {
            $category = '-';
        }
        if (Auth::user()->can('public-record-reviewchanges') && (File::exists(app_path() . '/Workflow.php') != null || File::exists(base_path() . '/packages/Powerpanel/Workflow/src/Models/Workflow.php') != null)) {
            $update = "<a title=\"Click here to see all approval records.\" class=\"icon_title1\" style=\"margin-right: 5px;\" onclick=\"javascript:expandcollapsepanel(this ,'tasklisting" . $value->id . "', 'mainsingnimg" . $value->id . "'," . $value->id . ")\"><i id=\"mainsingnimg" . $value->id . "\" class=\"ri-add-box-line\"></i></a>";
            $rollback = "<a title=\"Click here to see all approved records to rollback.\"  class=\"icon_title2\" style=\"margin-right: 5px;\" onclick=\"javascript:expandcollapsepanel_rolback(this ,'tasklisting_rollback" . $value->id . "', 'mainsingnimg_rollback" . $value->id . "'," . $value->id . ")\"><i id=\"mainsingnimg_rollback" . $value->id . "\" class=\"ri-history-line\"></i></a>";
        } else {
            $update = '';
            $rollback = '';
        }
        $startDate = date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ', strtotime($value->dtDateTime));
        $endDate = !empty($value->dtEndDateTime) ? date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($value->dtEndDateTime)) : 'No Expiry';
        $Quickedit_startDate = date('Y-m-d H:i', strtotime($value->dtDateTime));
        $Quickedit_endDate = !empty($value->dtEndDateTime) ? date('Y-m-d H:i', strtotime($value->dtEndDateTime)) : 'No Expiry';
        $title = $value->varTitle;
        if (Auth::user()->can('public-record-edit')) {
            if (method_exists($this->MyLibrary, 'getRecordAliasByModuleNameRecordId')) {
                $categoryRecordAlias = MyLibrary::getRecordAliasByModuleNameRecordId("public-record-category", $value->txtCategories);
            } else {
                $categoryRecordAlias = '';
            }
            if ($value->chrDraft == 'D' || $value->chrAddStar == 'Y') {
                $viewlink = url('/previewpage?url=' . MyLibrary::getFrontUri('public-record')['uri'] . '/' . $value->id . '/preview/detail');
                $linkviewLable = "Preview";
            } else {
                $viewlink = MyLibrary::getFrontUri('public-record')['uri'] . '/';
                $linkviewLable = "View";
            }
            //$frontViewLink = MyLibrary::getFrontUri('public-record')['uri'] . '/' . $value->alias->varAlias;
            if ($value->chrLock != 'Y') {
                if (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y') {
                    $title = '<div class="quick_edit"><a href = "' . route('powerpanel.public-record.edit', array('alias' => $value->id)) . '?tab=P">' . $value->varTitle . '</a> <div class="quick_edit_menu">
														<span><a href="' . route('powerpanel.public-record.edit', array('alias' => $value->id)) . '?tab=P" title="Edit">Edit</a></span>';
                    if (Config::get('Constant.DEFAULT_QUICK') == 'Y') {
                        $title .= '<span><a title="Quick Edit" href=\'javascript:;\' data-toggle=\'modal\' data-target=\'#modalForm\' aria-label=\'Quick edit\' onclick=\'Quickeditfun("' . $value->id . '","' . $value->varTitle . '","' . $value->intSearchRank . '","' . $Quickedit_startDate . '","' . $Quickedit_endDate . '","P")\'>Quick Edit</a></span>';
                    }
                    if (Config::get('Constant.DEFAULT_TRASH') == 'Y') {
                        $title .= '<span><a title = "Trash" href = \'javascript:;\' onclick=\'Trashfun("' . $value->id . '")\' class="red" data-tab="P">Trash</a></span>';
                    }
                    $title .= '<span><a href="' . $viewlink . '" target="_blank" title="' . $linkviewLable . '" >' . $linkviewLable . '</a></span>
																</div>
											 </div>';
                } else {
                    $title = '<div class="quick_edit"><a href = "' . route('powerpanel.public-record.edit', array('alias' => $value->id)) . '?tab=P">' . $value->varTitle . '</a> <div class="quick_edit_menu">
														<span><a href="' . route('powerpanel.public-record.edit', array('alias' => $value->id)) . '?tab=P" title="Edit">Edit</a></span>
														<span><a href="' . $viewlink . '" target="_blank" title="' . $linkviewLable . '" >' . $linkviewLable . '</a></span>
																</div>
											 </div>';
                }
            } else {
                if (auth()->user()->id != $value->LockUserID) {
                    if (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y') {
                        $title = '<div class="quick_edit"><a href = "' . route('powerpanel.public-record.edit', array('alias' => $value->id)) . '?tab=P">' . $value->varTitle . '</a> <div class="quick_edit_menu">
                            <span><a href="' . route('powerpanel.public-record.edit', array('alias' => $value->id)) . '?tab=P" title="Edit">Edit</a></span>
                            <span><a href="' . $viewlink . '" target="_blank" title="' . $linkviewLable . '" >' . $linkviewLable . '</a></span>
                                </div>
                       </div>';
                    } else {
                        $title = '<div class="quick_edit"><a href = "javascript:;">' . $value->varTitle . '</a></div>';
                    }
                } else {
                    $title = '<div class="quick_edit"><a href = "' . route('powerpanel.public-record.edit', array('alias' => $value->id)) . '?tab=P">' . $value->varTitle . '</a> <div class="quick_edit_menu">
                            <span><a href="' . route('powerpanel.public-record.edit', array('alias' => $value->id)) . '?tab=P" title="Edit">Edit</a></span>
                            <span><a href="' . $viewlink . '" target="_blank" title="' . $linkviewLable . '" >' . $linkviewLable . '</a></span>
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
            if($this->currentUserRoleData->chrIsAdmin == 'Y' && count($value->child) > 1) {
                $log .= "<a title='Rollback to previous version'  onclick=\"rollbackToPreviousVersion('" . $value->id . "');\"  class=\"log-grid\"><i class=\"ri-history-line\"></i></a>";
            }
        }

        $records = array(
            '<input type="checkbox" name="delete" class="chkDelete form-check-input" value="' . $value->id . '">',
            $First_td,
            '<div class="pages_title_div_row">' . $update . $rollback . $title . ' ' . $status . $statusdata . ' ' . $sector . '</div>',
            $category,
            $startDate,

            $publish_action,
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
        $Hits = Pagehit::where('fkIntAliasId', $value->intAliasId)->count();
        $webHits = '';
        if ($Hits > 0) {
            $webHits .= '<a data-toggle="modal" href="#" onclick=\'HitsPopup("' . $value->id . '","' . $value->intAliasId . '","' . $value->varTitle . '","F")\'>' . $Hits . '</a>
										<div class="new_modal modal fade" id="desc_' . $value->id . '_F" tabindex="-1" aria-hidden="true">
												<div class="modal-dialog" style="margin: 0 auto;display:table;width: 100%;height:100%;max-width: 1000px;">
												<div class="modal-vertical">
												<div class="modal-content">
										<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
										<h3 class="modal-title">Hits Report</h3>
										</div>
										<div class="modal-body">
										<div id="webdata_' . $value->id . '_F"></div>
										</div>
										</div>
										</div>
										</div>
										</div>';
        } else {
            $webHits .= '0';
        }
        $details = '';
        $publish_action = '';
        if (Auth::user()->can('public-record-edit')) {
            $details .= '<a class="" title="' . trans("public-record::template.common.edit") . '" href="' . route('powerpanel.public-record.edit', array('alias' => $value->id)) . '"><i class="ri-pencil-line"></i></a>';
        }
        if (Auth::user()->can('public-record-delete') && $this->currentUserRoleData->chrIsAdmin == 'Y') {
            if (Config::get('Constant.DEFAULT_TRASH') == 'Y') {
                $details .= '<a class="delete-grid" onclick = \'Trashfun("' . $value->id . '")\' title="' . trans("public-record::template.common.delete") . '" data-controller="public-record" data-alias = "' . $value->id . '" data-tab="F"><i class="ri-delete-bin-line"></i></a>';
            } else {
                $details .= '<a class="delete" title="' . trans("public-record::template.common.delete") . '" data-controller="public-record" data-alias = "' . $value->id . '" data-tab="F"><i class="ri-delete-bin-line"></i></a>';
            }
        }
        $minus = '<span class="glyphicon glyphicon-minus"></span>';
        $category = '';
        if (isset($value->txtCategories)) {
            $categoryIDs = [$value->txtCategories];
            $selCategory = PublicRecordCategory::getParentCategoryNameBycatId($categoryIDs);
          
            foreach ($selCategory as $selCat) {
                if (strlen(trim($selCat)) > 0) {
                   
                    $category = $selCat->varTitle;
                    
                }
            }
          
        } else {
            $category = '-';
        }
        $startDate = date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ', strtotime($value->dtDateTime));
        $endDate = !empty($value->dtEndDateTime) ? date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($value->dtEndDateTime)) : 'No Expiry';
        $Quickedit_startDate = date('Y-m-d H:i', strtotime($value->dtDateTime));
        $Quickedit_endDate = !empty($value->dtEndDateTime) ? date('Y-m-d H:i', strtotime($value->dtEndDateTime)) : 'No Expiry';
        $title = $value->varTitle;
        if (Auth::user()->can('public-record-edit')) {
            $categoryRecordAlias = MyLibrary::getRecordAliasByModuleNameRecordId("public-record-category", $value->txtCategories);
            if ($value->chrDraft == 'D' || $value->chrAddStar == 'Y') {
                $viewlink = url('/previewpage?url=' . MyLibrary::getFrontUri('public-record')['uri'] . '/' . $value->id . '/preview/detail');
                $linkviewLable = "Preview";
            } else {
                $viewlink = MyLibrary::getFrontUri('public-record')['uri'] . '/';
                $linkviewLable = "View";
            }
            //$frontViewLink = MyLibrary::getFrontUri('public-record')['uri'] . '/' . $value->alias->varAlias;
            if ($value->chrLock != 'Y') {
                if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                    $title = '<div class="quick_edit"><a href = "' . route('powerpanel.public-record.edit', array('alias' => $value->id)) . '?tab=F">' . $value->varTitle . '</a> <div class="quick_edit_menu">
														<span><a href="' . route('powerpanel.public-record.edit', array('alias' => $value->id)) . '?tab=F" title="Edit">Edit</a></span>';
                    if (Config::get('Constant.DEFAULT_TRASH') == 'Y') {
                        $title .= '<span><a title = "Trash" href = \'javascript:;\' onclick=\'Trashfun("' . $value->id . '")\' class="red" data-tab="F">Trash</a></span>';
                    }
                    $title .= '<span><a href="' . $viewlink . '" target="_blank" title="' . $linkviewLable . '" >' . $linkviewLable . '</a></span>
																</div>
											 </div>';
                } else {
                    $title = '<div class="quick_edit"><a href = "' . route('powerpanel.public-record.edit', array('alias' => $value->id)) . '?tab=F">' . $value->varTitle . '</a> <div class="quick_edit_menu">
														<span><a href="' . route('powerpanel.public-record.edit', array('alias' => $value->id)) . '?tab=F" title="Edit">Edit</a></span>
														<span><a href="' . $viewlink . '" target="_blank" title="' . $linkviewLable . '" >' . $linkviewLable . '</a></span>
																</div>
											 </div>';
                }
            } else {
                if (auth()->user()->id != $value->LockUserID) {
                    if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                        $title = '<div class="quick_edit"><a href = "' . route('powerpanel.public-record.edit', array('alias' => $value->id)) . '?tab=F">' . $value->varTitle . '</a> <div class="quick_edit_menu">
	                            <span><a href="' . route('powerpanel.public-record.edit', array('alias' => $value->id)) . '?tab=F" title="Edit">Edit</a></span>
	                            <span><a href="' . $viewlink . '" target="_blank" title="' . $linkviewLable . '" >' . $linkviewLable . '</a></span>
	                                </div>
	                        </div>';
                    } else {
                        $title = '<div class="quick_edit"><a href = "javascript:;">' . $value->varTitle . '</a></div>';
                    }
                } else {
                    $title = '<div class="quick_edit"><a href = "' . route('powerpanel.public-record.edit', array('alias' => $value->id)) . '?tab=F">' . $value->varTitle . '</a> <div class="quick_edit_menu">
	                            <span><a href="' . route('powerpanel.public-record.edit', array('alias' => $value->id)) . '?tab=F" title="Edit">Edit</a></span>
	                            <span><a href="' . $viewlink . '" target="_blank" title="' . $linkviewLable . '" >' . $linkviewLable . '</a></span>
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
            '<input type="checkbox" name="delete" class="chkDelete form-check-input" value="' . $value->id . '">',
            $First_td,
            '<div class="pages_title_div_row">' . $title . ' ' . $status . $statusdata . ' ' . $sector . '</div>',
            $category,
            $startDate,
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
        $Hits = Pagehit::where('fkIntAliasId', $value->intAliasId)->count();
        $webHits = '';
        if ($Hits > 0) {
            $webHits .= '<a data-toggle="modal" href="#" onclick=\'HitsPopup("' . $value->id . '","' . $value->intAliasId . '","' . $value->varTitle . '","D")\'>' . $Hits . '</a>
										<div class="new_modal modal fade" id="desc_' . $value->id . '_D" tabindex="-1" aria-hidden="true">
												<div class="modal-dialog" style="margin: 0 auto;display:table;width: 100%;height:100%;max-width: 1000px;">
												<div class="modal-vertical">
												<div class="modal-content">
										<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
										<h3 class="modal-title">Hits Report</h3>
										</div>
										<div class="modal-body">
										<div id="webdata_' . $value->id . '_D"></div>
										</div>
										</div>
										</div>
										</div>
										</div>';
        } else {
            $webHits .= '0';
        }
        $details = '';
        $publish_action = '';
        if (Auth::user()->can('public-record-edit')) {
            $details .= '<a class="" title="' . trans("public-record::template.common.edit") . '" href="' . route('powerpanel.public-record.edit', array('alias' => $value->id)) . '"><i class="ri-pencil-line"></i></a>';
        }
        if (Auth::user()->can('public-record-delete') && $this->currentUserRoleData->chrIsAdmin == 'Y') {
            if (Config::get('Constant.DEFAULT_TRASH') == 'Y') {
                $details .= '<a class="delete-grid" title="' . trans("public-record::template.common.delete") . '" onclick = \'Trashfun("' . $value->id . '")\' data-controller="public-record" data-alias = "' . $value->id . '" data-tab="D"><i class="ri-delete-bin-line"></i></a>';
            } else {
                $details .= '<a class="delete" title="' . trans("public-record::template.common.delete") . '" data-controller="public-record" data-alias = "' . $value->id . '" data-tab="D"><i class="ri-delete-bin-line"></i></a>';
            }
        }
        //Bootstrap Switch
        $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/public-record', 'data_alias'=>$value->id, 'title'=>trans("public-record::template.common.unpublishedRecord"), 'data_value'=>'Publish', 'checked'=>'checked'])->render();

        $minus = '<span class="glyphicon glyphicon-minus"></span>';
        $category = '';
        if (isset($value->txtCategories)) {
            $categoryIDs = [$value->txtCategories];
            $selCategory = PublicRecordCategory::getParentCategoryNameBycatId($categoryIDs);
          
            foreach ($selCategory as $selCat) {
                if (strlen(trim($selCat)) > 0) {
                   
                    $category = $selCat->varTitle;
                    
                }
            }
          
        } else {
            $category = '-';
        }
        $startDate = date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ', strtotime($value->dtDateTime));
        $endDate = !empty($value->dtEndDateTime) ? date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($value->dtEndDateTime)) : 'No Expiry';
        $Quickedit_startDate = date('Y-m-d H:i', strtotime($value->dtDateTime));
        $Quickedit_endDate = !empty($value->dtEndDateTime) ? date('Y-m-d H:i', strtotime($value->dtEndDateTime)) : 'No Expiry';
        $title = $value->varTitle;
        if (Auth::user()->can('public-record-edit')) {
            $categoryRecordAlias = MyLibrary::getRecordAliasByModuleNameRecordId("public-record-category", $value->txtCategories);
            if ($value->chrDraft == 'D' || $value->chrAddStar == 'Y') {
                $viewlink = url('/previewpage?url=' . MyLibrary::getFrontUri('public-record')['uri'] . '/' . $value->id . '/preview/detail');
                $linkviewLable = "Preview";
            } else {
                $viewlink = MyLibrary::getFrontUri('public-record')['uri'] . '/';
                $linkviewLable = "View";
            }
            //$previewlink = url('/previewpage?url=' . MyLibrary::getFrontUri('public-record')['uri'] . '/' . $value->id . '/preview/detail');
            if ($value->chrLock != 'Y') {
                if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                    $title = '<div class="quick_edit"><a href = "' . route('powerpanel.public-record.edit', array('alias' => $value->id)) . '?tab=D">' . $value->varTitle . '</a> <div class="quick_edit_menu">
														<span><a href="' . route('powerpanel.public-record.edit', array('alias' => $value->id)) . '?tab=D" title="Edit">Edit</a></span>';
                    if (Config::get('Constant.DEFAULT_TRASH') == 'Y') {
                        $title .= '<span><a title = "Trash" href = \'javascript:;\' onclick=\'Trashfun("' . $value->id . '")\' class="red" data-tab="D">Trash</a></span>';
                    }
                    $title .= '<span><a href="' . $viewlink . '" target="_blank" title="' . $linkviewLable . '" >' . $linkviewLable . '</a></span>
																</div>
											 </div>';
                } else {
                    $title = '<div class="quick_edit"><a href = "' . route('powerpanel.public-record.edit', array('alias' => $value->id)) . '?tab=D">' . $value->varTitle . '</a> <div class="quick_edit_menu">
														<span><a href="' . route('powerpanel.public-record.edit', array('alias' => $value->id)) . '?tab=D" title="Edit">Edit</a></span>
														<span><a href="' . $viewlink . '" target="_blank" title="' . $linkviewLable . '" >' . $linkviewLable . '</a></span>
																</div>
											 </div>';
                }
            } else {
                if (auth()->user()->id != $value->LockUserID) {
                    if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                        $title = '<div class="quick_edit"><a href = "' . route('powerpanel.public-record.edit', array('alias' => $value->id)) . '?tab=D">' . $value->varTitle . '</a> <div class="quick_edit_menu">
	                            <span><a href="' . route('powerpanel.public-record.edit', array('alias' => $value->id)) . '?tab=D" title="Edit">Edit</a></span>';

                        $title .= '<span><a href = "' . $viewlink . '" target = "_blank" title = "' . $linkviewLable . '" >' . $linkviewLable . '</a></span></div></div>';
                    } else {
                        $title = '<div class="quick_edit"><a href = "javascript:;">' . $value->varTitle . '</a></div>';
                    }
                } else {
                    $title = '<div class="quick_edit"><a href = "' . route('powerpanel.public-record.edit', array('alias' => $value->id)) . '?tab=D">' . $value->varTitle . '</a> <div class="quick_edit_menu">
	                            <span><a href="' . route('powerpanel.public-record.edit', array('alias' => $value->id)) . '?tab=D" title="Edit">Edit</a></span>
	                            <span><a href="' . $viewlink . '" target="_blank" title="' . $linkviewLable . '" >' . $linkviewLable . '</a></span>
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
            '<input type="checkbox" name="delete" class="chkDelete form-check-input" value="' . $value->id . '">',
            '<div class="pages_title_div_row"><input type="hidden" id="draftid" value="' . $value->id . '">' . $title . ' ' . $status . $statusdata . ' ' . $sector . '</div>',
            $category,
            $startDate,

            $publish_action,
            $log,
        );
        return $records;
    }

    public function tableDataTrash($value = false, $moduleCmsPageData = false)
    {
        $sector = '';
        if (isset($value->varSector) && !empty($value->varSector)) {
            $sector = strtoupper($value->varSector);
        }
        $Hits = Pagehit::where('fkIntAliasId', $value->intAliasId)->count();
        $webHits = '';
        if ($Hits > 0) {
            $webHits .= '<a data-toggle="modal" href="#" onclick=\'HitsPopup("' . $value->id . '","' . $value->intAliasId . '","' . $value->varTitle . '","T")\'>' . $Hits . '</a>
										<div class="new_modal modal fade" id="desc_' . $value->id . '_T" tabindex="-1" aria-hidden="true">
												<div class="modal-dialog" style="margin: 0 auto;display:table;width: 100%;height:100%;max-width: 1000px;">
												<div class="modal-vertical">
												<div class="modal-content">
										<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
										<h3 class="modal-title">Hits Report</h3>
										</div>
										<div class="modal-body">
										<div id="webdata_' . $value->id . '_T"></div>
										</div>
										</div>
										</div>
										</div>
										</div>';
        } else {
            $webHits .= '0';
        }
        $details = '';
        $publish_action = '';
        if (Auth::user()->can('public-record-delete') && $this->currentUserRoleData->chrIsAdmin == 'Y') {
            $details .= '<a class=" delete" title="' . trans("public-record::template.common.delete") . '" data-controller="public-record" data-alias = "' . $value->id . '" data-tab="T"><i class="ri-delete-bin-line"></i></a>';
        }
        $minus = '<span class="glyphicon glyphicon-minus"></span>';
        $category = '';
        if (isset($value->txtCategories)) {
            $categoryIDs = [$value->txtCategories];
            $selCategory = PublicRecordCategory::getParentCategoryNameBycatId($categoryIDs);
          
            foreach ($selCategory as $selCat) {
                if (strlen(trim($selCat)) > 0) {
                   
                    $category = $selCat->varTitle;
                    
                }
            }
          
        } else {
            $category = '-';
        }
        $startDate = date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ', strtotime($value->dtDateTime));
        $endDate = !empty($value->dtEndDateTime) ? date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($value->dtEndDateTime)) : 'No Expiry';
        $title = $value->varTitle;
        if (Auth::user()->can('careers-edit')) {
            $title = '<div class="quick_edit text-uppercase"><a href = "' . route('powerpanel.public-record.edit', array('alias' => $value->id)) . '?tab=T">' . $value->varTitle . '</a>
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
            '<input type="checkbox" name="delete" class="chkDelete form-check-input" value="' . $value->id . '">',
            '<div class="pages_title_div_row">' . $title . ' ' . $sector . '</div>',
            $category,
            $startDate,
            $log,
        );
        return $records;
    }

    /**
     * This method delete multiples public-record
     * @return  true/false
     * @since   2017-07-15
     * @author  NetQuick
     */
    public function DeleteRecord(Request $request)
    {
        $value = Request::input('value');
        $data['ids'] = Request::input('ids');
        if (File::exists(app_path() . '/Comments.php') != null || File::exists(base_path() . '/packages/Powerpanel/Workflow/src/Models/Comments.php') != null) {
            Comments::deleteComments($data['ids'], Config::get('Constant.MODULE.MODEL_NAME'));
        }
        $moduleHaveFields = ['chrMain', 'chrIsPreview'];
        $update = MyLibrary::deleteMultipleRecords($data, $moduleHaveFields, $value, 'Powerpanel\PublicRecord\Models\PublicRecord');
        foreach ($update as $ids) {
            $ignoreDeleteScope = true;
            $Deleted_Record = PublicRecord::getRecordById($ids, $ignoreDeleteScope);
            $Cnt_Letest = PublicRecord::getRecordCount_letest($Deleted_Record['fkMainRecord'], $Deleted_Record['id']);
            if ($Cnt_Letest <= 0) {
                $updateLetest = [
                    'chrAddStar' => 'N',
                ];
                $whereConditionsApprove = ['id' => $Deleted_Record['fkMainRecord']];
                CommonModel::updateRecords($whereConditionsApprove, $updateLetest, false, 'Powerpanel\PublicRecord\Models\PublicRecord');
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
        MyLibrary::swapOrder($order, $exOrder, 'Powerpanel\PublicRecord\Models\PublicRecord');
        self::flushCache();
    }

    /**
     * This method handels swapping of available order record while adding
     * @param   order
     * @return  order
     * @since   2016-10-21
     * @author  NetQuick
     */
    public static function swap_order_add($order = null)
    {
        $response = false;
        $isCustomizeModule = true;
        if ($order != null) {
            $response = MyLibrary::swapOrderAdd($order, $isCustomizeModule, 'Powerpanel\PublicRecord\Models\PublicRecord');
            self::flushCache();
        }
        return $response;
    }

    /**
     * This method handels swapping of available order record while editing
     * @param   order
     * @return  order
     * @since   2016-12-23
     * @author  NetQuick
     */
    public static function swap_order_edit($order = null, $id = null)
    {
        MyLibrary::swapOrderEdit($order, $id, 'Powerpanel\PublicRecord\Models\PublicRecord');
        self::flushCache();
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
        $update = MyLibrary::setPublishUnpublish($alias, $val, 'Powerpanel\PublicRecord\Models\PublicRecord');
        self::flushCache();
        echo json_encode($update);
        exit;
    }

    public function recordHistory($data = false)
    {
        $startDate = date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ', strtotime($data->dtDateTime));
        $endDate = !empty($data->dtEndDateTime) ? date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($data->dtEndDateTime)) : 'No Expiry';
        $PublicRecordCategory = PublicRecordCategory::getCatData($data->txtCategories);
        if (isset($data->fkIntDocId)) {
            $DocId = Document::getRecordById($data->fkIntDocId);
            $docname = stripslashes($DocId->txtDocumentName);
        } else {
            $DocId = '';
            $docname = '';
        }

        $returnHtml = '';
        $returnHtml .= '<table class="table table-hover align-middle table-nowrap hide-mobile">
				<thead class="table-light">
                    <tr>
                        <th align="left">' . trans("public-record::template.common.title") . '</th>
                        <th align="left">Category</th>
                        <th align="left">Documents</th>
                        <th align="left">Short Description</th>
                        <th align="left">Description</th>
                        <th align="left">Start Date</th>
                        <th align="left">End Date</th>
                        <th align="left">Meta Title</th>
                        <th align="left">Meta Description</th>
                        <th align="left">' . trans("public-record::template.common.publish") . '</th>
                    </tr>
				</thead>
				<tbody>
                    <tr>
                        <td align="left">' . stripslashes($data->varTitle) . '</td>
                        <td align="left">' . $PublicRecordCategory->varTitle . '</td>
                        <td align="left">' . $docname . '</td>
                        <td align="left">' . stripslashes($data->varAuthor) . '</td>
                        <td align="left">' . $startDate . '</td>
                        <td align="left">' . $endDate . '</td>
                        <td align="left">' . stripslashes($data->varMetaTitle) . '</td>
                        <td align="left">' . stripslashes($data->varMetaDescription) . '</td>
                        <td align="left">' . $data->chrPublish . '</td>
                    </tr>
				</tbody>
				</table>';
        return $returnHtml;
    }

    public function newrecordHistory($data = false, $newdata = false)
    {
        $startDate = date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ', strtotime($newdata->dtDateTime));
        $endDate = !empty($newdata->dtEndDateTime) ? date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($newdata->dtEndDateTime)) : 'No Expiry';
        $PublicRecordCategory = PublicRecordCategory::getCatData($newdata->txtCategories);
        if ($data->varTitle != $newdata->varTitle) {
            $titlecolor = 'style="background-color:#f5efb7"';
        } else {
            $titlecolor = '';
        }
        if ($data->txtCategories != $newdata->txtCategories) {
            $catcolor = 'style="background-color:#f5efb7"';
        } else {
            $catcolor = '';
        }
        if ($data->dtDateTime != $newdata->dtDateTime) {
            $sdatecolor = 'style="background-color:#f5efb7"';
        } else {
            $sdatecolor = '';
        }
        if ($data->dtEndDateTime != $newdata->dtEndDateTime) {
            $edatecolor = 'style="background-color:#f5efb7"';
        } else {
            $edatecolor = '';
        }
        if ($data->chrPublish != $newdata->chrPublish) {
            $Publishcolor = 'style="background-color:#f5efb7"';
        } else {
            $Publishcolor = '';
        }
        if ($data->fkIntDocId != $newdata->fkIntDocId) {
            $DocIdcolor = 'style="background-color:#f5efb7"';
        } else {
            $DocIdcolor = '';
        }
        if (isset($newdata->fkIntDocId)) {
            $DocId = Document::getRecordById($newdata->fkIntDocId);
            $docname = stripslashes($DocId->txtDocumentName);
        } else {
            $DocId = '';
            $docname = '';
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
        if ($data->varAuthor != $newdata->varAuthor) {
            $ShortDescriptioncolor = 'style="background-color:#f5efb7"';
        } else {
            $ShortDescriptioncolor = '';
        }

        $returnHtml = '';
        $returnHtml .= '<table class="table table-hover align-middle table-nowrap hide-mobile">
				<thead class="table-light">
                    <tr>
                        <th align="left">' . trans("public-record::template.common.title") . '</th>
                        <th align="left">Category</th>
                        <th align="left">Documents</th>
                        <th align="left">Short Description</th>
                        <th align="left">Description</th>
                        <th align="left">Start Date</th>
                        <th align="left">End Date</th>
                        <th align="left">Meta Title</th>
                        <th align="left">Meta Description</th>
                        <th align="left">' . trans("public-record::template.common.publish") . '</th>
                    </tr>
				</thead>
				<tbody>
                    <tr>
                        <td align="left" ' . $titlecolor . '>' . stripslashes($newdata->varTitle) . '</td>
                        <td align="left" ' . $catcolor . '>' . $PublicRecordCategory->varTitle . '</td>
                        <td align="left" ' . $DocIdcolor . '>' . $docname . '</td>
                        <td align="left" ' . $ShortDescriptioncolor . '>' . stripslashes($newdata->varAuthor) . '</td>
                        <td align="left" ' . $sdatecolor . '>' . $startDate . '</td>
                        <td align="left" ' . $edatecolor . '>' . $endDate . '</td>
                        <td align="left" ' . $metatitlecolor . '>' . stripslashes($newdata->varMetaTitle) . '</td>
                        <td align="left" ' . $metadesccolor . '>' . stripslashes($newdata->varMetaDescription) . '</td>
                        <td align="left" ' . $Publishcolor . '>' . $newdata->chrPublish . '</td>
                    </tr>
				</tbody>
				</table>';
        return $returnHtml;
    }

    /**
     * This method stores public-record modifications
     * @return  View
     * @since   2017-11-10
     * @author  NetQuick
     */
    public function addPreview(Guard $auth)
    {
        $data = Request::input();
        $rules = array(
            'title' => 'required|max:200|handle_xss|no_url',
            'chrMenuDisplay' => 'required',
            'varMetaTitle' => 'required|max:500|handle_xss|no_url',
            'varMetaDescription' => 'required|max:500|handle_xss|no_url',
            'author' => 'required|handle_xss|no_url',
            'description' => 'required',

            'category_id' => 'required',
        );
        $actionMessage = trans('public-record::template.common.oppsSomethingWrong');
        $messsages = array();
        $validator = Validator::make($data, $rules, $messsages);
        $publicrecordArr = [];

        $publicrecordArr['varTitle'] = stripslashes(trim($data['title']));
        $publicrecordArr['fkIntDocId'] = !empty($data['doc_id']) ? $data['doc_id'] : null;
        $publicrecordArr['dtDateTime'] = !empty($data['start_date_time']) ? date('Y-m-d H:i:s', strtotime(str_replace('/', '-',$data['start_date_time']))) : date('Y-m-d H:i:s');
        $publicrecordArr['dtEndDateTime'] = !empty($data['end_date_time']) ? date('Y-m-d H:i:s', strtotime(str_replace('/', '-',$data['end_date_time']))) : null;

        $publicrecordArr['varMetaTitle'] = stripslashes(trim($data['varMetaTitle']));
        $publicrecordArr['varMetaDescription'] = stripslashes(trim($data['varMetaDescription']));

        $publicrecordArr['chrPublish'] = $data['chrMenuDisplay'];
        $publicrecordArr['chrIsPreview'] = 'Y';
        $publicrecordArr['txtCategories'] = isset($data['category_id']) ? $data['category_id'] : null;

        $id = $data['previewId'];
        if (is_numeric($id)) { #Edit post Handler=======

            $publicrecord = PublicRecord::getRecordForLogById($id);
            $whereConditions = ['id' => $publicrecord->id];
            $update = CommonModel::updateRecords($whereConditions, $publicrecordArr, false, 'Powerpanel\PublicRecord\Models\PublicRecord');
            if ($update) {
                if (!empty($id)) {
                    $logArr = MyLibrary::logData($publicrecord->id);
                    if (Auth::user()->can('log-advanced')) {
                        $newPublicRecordObj = PublicRecord::getRecordForLogById($publicrecord->id);
                        $oldRec = $this->recordHistory($publicrecord);
                        $newRec = $this->recordHistory($newPublicRecordObj);
                        $logArr['old_val'] = $oldRec;
                        $logArr['new_val'] = $newRec;
                    }
                    $logArr['varTitle'] = stripslashes(trim($data['title']));
                    Log::recordLog($logArr);
                    if (Auth::user()->can('recent-updates-list')) {
                        if (!isset($newPublicRecordObj)) {
                            $newPublicRecordObj = PublicRecord::getRecordForLogById($publicrecord->id);
                        }
                        $notificationArr = MyLibrary::notificationData($publicrecord->id, $newPublicRecordObj);
                        RecentUpdates::setNotification($notificationArr);
                    }
                    self::flushCache();
                    $actionMessage = trans('public-record::template.publicrecordModule.updateMessage');
                }
            }
        } else { #Add post Handler=======

            $publicrecordArr['fkIntDocId'] = !empty($data['doc_id']) ? $data['doc_id'] : null;
            $id = CommonModel::addRecord($publicrecordArr, 'Powerpanel\PublicRecord\Models\PublicRecord');
        }
        AddDocumentModelRel::sync(explode(',', $data['doc_id']), $id);
        return json_encode(array('status' => $id, 'message' => trans('public-record::template.pageModule.pageUpdate')));
    }

    public static function flushCache()
    {
        Cache::tags('PublicRecord')->flush();
    }

    public function tableData_tab1($value = false)
    {
        $Hits = Pagehit::where('fkIntAliasId', $value->intAliasId)->count();
        $webHits = '';
        if ($Hits > 0) {
            $webHits .= '<a data-toggle="modal" href="#" onclick=\'HitsPopup("' . $value->id . '","' . $value->intAliasId . '","' . $value->varTitle . '","A")\'>' . $Hits . '</a>
										<div class="new_modal modal fade" id="desc_' . $value->id . '_A" tabindex="-1" aria-hidden="true">
												<div class="modal-dialog" style="margin: 0 auto;display:table;width: 100%;height:100%;max-width: 1000px;">
												<div class="modal-vertical">
												<div class="modal-content">
										<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
										<h3 class="modal-title">Hits Report</h3>
										</div>
										<div class="modal-body">
										<div id="webdata_' . $value->id . '_A"></div>
										</div>
										</div>
										</div>
										</div>
										</div>';
        } else {
            $webHits .= '0';
        }
        $details = '';
        $publish_action = '';
        if (Auth::user()->can('public-record-edit')) {
            $details .= '<a class="" title="' . trans("public-record::template.common.edit") . '" href="' . route('powerpanel.public-record.edit', array('alias' => $value->id)) . '"><i class="ri-pencil-line"></i></a>';
        }
        if (Auth::user()->can('public-record-delete') || (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y')) {
            if (Config::get('Constant.DEFAULT_TRASH') == 'Y') {
                $details .= '<a class="delete-grid" title="' . trans("public-record::template.common.delete") . '" onclick = \'Trashfun("' . $value->id . '")\' data-controller="public-record" data-alias = "' . $value->id . '" data-tab="A"><i class="ri-delete-bin-line"></i></a>';
            } else {
                $details .= '<a class="delete" title="' . trans("public-record::template.common.delete") . '" data-controller="public-record" data-alias = "' . $value->id . '" data-tab="A"><i class="ri-delete-bin-line"></i></a>';
            }
        }

        $minus = '<span class="glyphicon glyphicon-minus"></span>';
        $startDate = date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($value->dtDateTime));
        $endDate = !empty($value->dtEndDateTime) ? date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($value->dtEndDateTime)) : 'No Expiry';
        $category = '';
        if (isset($value->txtCategories)) {
            $categoryIDs = [$value->txtCategories];
            $selCategory = PublicRecordCategory::getParentCategoryNameBycatId($categoryIDs);
          
            foreach ($selCategory as $selCat) {
                if (strlen(trim($selCat)) > 0) {
                   
                    $category = $selCat->varTitle;
                    
                }
            }
          
        } else {
            $category = '-';
        }
        if (Auth::user()->can('public-record-reviewchanges')) {
            $update = "<a title=\"Click here to see all approval records.\" class=\"icon_title1\" style=\"margin-right: 5px;\" onclick=\"javascript:expandcollapsepanel(this ,'tasklisting" . $value->id . "', 'mainsingnimg" . $value->id . "'," . $value->id . ")\"><i id=\"mainsingnimg" . $value->id . "\" class=\"ri-add-box-line\"></i></a>";
            $rollback = "<a title=\"Click here to see all approved records to rollback.\"  class=\"icon_title2\" style=\"margin-right: 5px;\" onclick=\"javascript:expandcollapsepanel_rolback(this ,'tasklisting_rollback" . $value->id . "', 'mainsingnimg_rollback" . $value->id . "'," . $value->id . ")\"><i id=\"mainsingnimg_rollback" . $value->id . "\" class=\"ri-history-line\"></i></a>";
        } else {
            $update = '';
            $rollback = '';
        }
        if (Auth::user()->can('public-record-reviewchanges') && $value->chrAddStar == 'Y') {
            $star = 'addhiglight';
        } else {
            $star = '';
        }
        $Quickedit_startDate = date('Y-m-d H:i', strtotime($value->dtDateTime));
        $Quickedit_endDate = !empty($value->dtEndDateTime) ? date('Y-m-d H:i', strtotime($value->dtEndDateTime)) : 'No Expiry';
        $title = $value->varTitle;
        if (Auth::user()->can('public-record-edit')) {
            $categoryRecordAlias = MyLibrary::getRecordAliasByModuleNameRecordId("public-record-category", $value->txtCategories);
            if ($value->chrDraft == 'D' || $value->chrAddStar == 'Y') {
                $viewlink = url('/previewpage?url=' . MyLibrary::getFrontUri('public-record')['uri'] . '/' . $value->id . '/preview/detail');
                $linkviewLable = "Preview";
            } else {
                $viewlink = MyLibrary::getFrontUri('public-record')['uri'] . '/';
                $linkviewLable = "View";
            }
            //$frontViewLink = MyLibrary::getFrontUri('public-record')['uri'] . '/' . $value->alias->varAlias;
            if ($value->chrLock != 'Y') {
                $title = '<div class="quick_edit"><a href = "' . route('powerpanel.public-record.edit', array('alias' => $value->id)) . '?tab=A">' . $value->varTitle . '</a> <div class="quick_edit_menu">
														<span><a href="' . route('powerpanel.public-record.edit', array('alias' => $value->id)) . '?tab=A" title="Edit">Edit</a></span>';
                if (Config::get('Constant.DEFAULT_TRASH') == 'Y') {
                    $title .= '<span><a title = "Trash" href = \'javascript:;\' onclick=\'Trashfun("' . $value->id . '")\' class="red" data-tab="A">Trash</a></span>';
                }
                $title .= '<span><a href="' . $viewlink . '" target="_blank" title="' . $linkviewLable . '" >' . $linkviewLable . '</a></span></div></div>';
            } else {
                if (auth()->user()->id != $value->LockUserID) {
                    if (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y') {
                        $title = '<div class="quick_edit"><a href = "' . route('powerpanel.pages.edit', array('alias' => $value->id)) . '?tab=A">' . $value->varTitle . '</a> <div class="quick_edit_menu">
	                            <span><a href="' . route('powerpanel.pages.edit', array('alias' => $value->id)) . '?tab=A" title="Edit">Edit</a></span>';

                        $title .= '<span><a href="' . $viewlink . '" target="_blank" title="' . $linkviewLable . '" >' . $linkviewLable . '</a></span></div></div>';
                    } else {
                        $title = '<div class="quick_edit"><a href = "javascript:;">' . $value->varTitle . '</a></div>';
                    }
                } else {
                    $title = '<div class="quick_edit"><a href = "' . route('powerpanel.pages.edit', array('alias' => $value->id)) . '?tab=A">' . $value->varTitle . '</a> <div class="quick_edit_menu">
	                            <span><a href="' . route('powerpanel.pages.edit', array('alias' => $value->id)) . '?tab=A" title="Edit">Edit</a></span>';

                    $title .= '<span><a href="' . $viewlink . '" target="_blank" title="' . $linkviewLable . '" >' . $linkviewLable . '</a></span></div></div>';
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
                $log .= "<a title='Rollback to previous version' onclick=\"rollbackToPreviousVersion('" . $value->id . "');\" class=\"log-grid\"><i class=\"ri-history-line\"></i></a>";
            }
        }

        $records = array(
            $First_td,
            '<div class="pages_title_div_row">' . $update . $rollback . $title . $status . $statusdata . '</div>',
            $category,
            $startDate,

            $log,
        );
        return $records;
    }

    public function getChildData()
    {
        $childHtml = "";
        $Cmspage_childData = "";
        $Cmspage_childData = PublicRecord::getChildGrid();
        $childHtml .= "<div class=\"producttbl\" style=\"\">";
        $childHtml .= "<table class=\"table table-hover align-middle table-nowrap hide-mobile\" id=\"email_log_datatable_ajax\">
            c class=\"table-light\">
			<tr role=\"row\">
			<th class=\"text-center\"></th>
            <th class=\"text-center\">Title</th>
			<th class=\"text-center\">Date Submitted</th>
			<th class=\"text-center\">User</th>
			<th class=\"text-center\">Edit</th>
			<th class=\"text-center\">Status</th>";
        $childHtml .= "</tr></thead><tbody>";
        if (count($Cmspage_childData) > 0) {
            foreach ($Cmspage_childData as $child_row) {

                $childHtml .= "<tr role=\"row\">";
                if ($child_row->chrApproved == 'N') {
                    $childHtml .= "<td><span class='mob_show_title'>&nbsp</span><input type=\"checkbox\" name=\"delete\" class=\"chkDelete form-check-input\" value='" . $child_row->id . "'></td>";
                } else {
                    $childHtml .= "<td><span class='mob_show_title'>&nbsp</span><div class=\"checker\"><a href=\"javascript:;\" data-toggle=\"tooltip\" data-placement=\"right\" title=\"This is approved record, so can't be deleted.\"><i style=\"color:red\" class=\"ri-alert-fill\"></i></a></div></td>";
                }
                $childHtml .= '<td class="text-center"><span class="mob_show_title">Title: </span>' . $child_row->varTitle . '</td>';
                $childHtml .= "<td class=\"text-center\"><span class='mob_show_title'>Date Submitted: </span>" . date('M d Y h:i A', strtotime($child_row->created_at)) . "</td>";
                $childHtml .= "<td class=\"text-center\"><span class='mob_show_title'>User: </span>" . CommonModel::getUserName($child_row->UserID) . "</td>";
                $categoryRecordAlias = MyLibrary::getRecordAliasByModuleNameRecordId("public-record-category", $child_row->txtCategories);
                               if ($child_row->chrApproved == 'N') {
                    $childHtml .= "<td class=\"text-center\"><span class='mob_show_title'>Edit: </span><a class='icon_round' title='" . trans("public-record::template.common.edit") . "' href='" . route('powerpanel.public-record.edit', array('alias' => $child_row->id)) . "'>
							<i class='ri-pencil-line'></i></a></td>";
                } else {
                    $childHtml .= "<td class=\"text-center\"><span class='mob_show_title'>Edit: </span>-</td>";
                }
                if ($child_row->chrApproved == 'N') {
                    $childHtml .= "<td class=\"text-center\"><span class='mob_show_title'>Status: </span><a class=\"approve_icon_btn\" title='" . trans("public-record::template.common.comments") . "' href=\"javascript:;\" onclick=\"loadModelpopup('" . $child_row->id . "','" . $child_row->UserID . "','" . Config::get('Constant.MODULE.MODEL_NAME') . "','" . $child_row->fkMainRecord . "')\"><i class=\"ri-chat-1-line\"></i> <span>Comment</span></a>    <a  class=\"approve_icon_btn\" onclick=\"update_mainrecord('" . $child_row->id . "','" . $child_row->fkMainRecord . "','" . $child_row->UserID . "','A');\" title='" . trans("public-record::template.common.clickapprove") . "' href=\"javascript:;\"><i class=\"ri-checkbox-line\"></i> <span>Approve</span></a></td>";
                } else {
                    $childHtml .= "<td class=\"text-center\"><span class='mob_show_title'>Status: </span><span class='mob_show_overflow'><i class=\"ri-checkbox-circle-line\" style=\"font-size:30px;\"></i><span style=\"display:block\"><strong>Approved On: </strong>" . date('M d Y h:i A', strtotime($child_row->dtApprovedDateTime)) . "</span><span style=\"display:block\"><strong>Approved By: </strong>" . CommonModel::getUserName($child_row->intApprovedBy) . "</span></span></td>";
                }
                $childHtml .= "</tr>";
            }
        } else {
            $childHtml .= "<tr><td colspan='6'>No Records</td></tr>";
        }
        $childHtml .= "</tr></td></tr>";
        $childHtml .= "</tr></tbody></table>";
        echo $childHtml;
        exit;
    }

    public function getChildData_rollback()
    {
        $child_rollbackHtml = "";
        $Cmspage_rollbackchildData = "";
        $Cmspage_rollbackchildData = PublicRecord::getChildrollbackGrid();
        $child_rollbackHtml .= "<div class=\"producttbl producttb2\" style=\"\">";
        $child_rollbackHtml .= "<table class=\"table table-hover align-middle table-nowrap hide-mobile\" id=\"email_log_datatable_ajax\">
            <thead class=\"table-light\">
            <tr role=\"row\">
            <th class=\"text-left\">Title</th>
            <th class=\"text-left\">Date</th>
            <th class=\"text-left\">User</th>
            <th class=\"text-left\">Preview</th>
            <th class=\"text-left\">Status</th>";
        $child_rollbackHtml .= "</tr></thead><tbody>";
        if (count($Cmspage_rollbackchildData) > 0) {
            foreach ($Cmspage_rollbackchildData as $child_rollbacrow) {
                $child_rollbackHtml .= "<tr role=\"row\">";
                $child_rollbackHtml .= '<td class="text-left"><span class="mob_show_title">Title: </span>' . $child_rollbacrow->varTitle . '</td>';
                $child_rollbackHtml .= "<td class=\"text-left\"><span class='mob_show_title'>Date: </span>" . date('M d Y h:i A', strtotime($child_rollbacrow->created_at)) . "</td>";
                $child_rollbackHtml .= "<td class=\"text-left\"><span class='mob_show_title'>User: </span>" . CommonModel::getUserName($child_rollbacrow->UserID) . "</td>";
                $categoryRecordAlias = MyLibrary::getRecordAliasByModuleNameRecordId("public-record-category", $child_rollbacrow->txtCategories);
                $previewlink = url('/previewpage?url=' . MyLibrary::getFrontUri('public-record')['uri'] . '/' . $child_rollbacrow->id . '/preview/detail');
                $child_rollbackHtml .= "<td class=\"text-left\"><span class='mob_show_title'>Preview: </span><a class='icon_round' href=" . $previewlink . " target='_blank'><i class=\"ri-computer-line\"></i></a></td>";
                if ($child_rollbacrow->chrApproved == 'Y') {
                    $child_rollbackHtml .= "<td class=\"text-left\"><span class='mob_show_title'>Status: </span><i class=\"ri-checkbox-circle-line\" style=\"color: #1080F2;font-size:30px;\"></i></td>";
                } else {
                    $child_rollbackHtml .= "<td class=\"text-left\"><span class=\"glyphicon glyphicon-minus\"></span></td>";
                }
                $child_rollbackHtml .= "</tr>";
            }
        } else {
            $child_rollbackHtml .= "<tr><td colspan='6'>No Records</td></tr>";
        }
        $child_rollbackHtml .= "</tbody>";
        echo $child_rollbackHtml;
        exit;
    }

    public function ApprovedData_Listing(Request $request)
    {
        $requestArr = Request::all();
        $request = (object) $requestArr;
        $main_id = Request::post('main_id');
        $approvalid = Request::post('id');
        $id = Request::post('id');
        $flag = Request::post('flag');
        $message = PublicRecord::approved_data_Listing($request);
        $newCmsPageObj = PublicRecord::getRecordForLogById($main_id);
        $approval_obj = PublicRecord::getRecordForLogById($approvalid);
        if ($flag == 'R') {
            $restoredata = Config::get('Constant.ROLLBACK_RECORD');
        } else {
            if ($approval_obj->chrDraft == 'D') {
                $restoredata = Config::get('Constant.DRAFT_RECORD_APPROVED');
            } else {
                $restoredata = Config::get('Constant.RECORD_APPROVED');
            }
        }
        /* notification for user to record approved */
        $careers = PublicRecord::getRecordForLogById($id);
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
        $arrResults = PublicRecord::getBuilderRecordList($filterArr);
        $found = $arrResults->toArray();
        if (!empty($found)) {
            foreach ($arrResults as $key => $value) {
                $rows .= $this->tableDataBuilder($value, false, $filterArr['selected']);
            }
        } else {
            $rows .= '<tr id="not-found"><td colspan="4" align="left">No records found.</td></tr>';
        }
        $iTotalRecords = CommonModel::getTotalRecordCount('Powerpanel\PublicRecord\Models\PublicRecord', true, true);
        $records["data"] = $rows;
        $records["found"] = count($found);
        $records["recordsTotal"] = $iTotalRecords;
        return json_encode($records);
    }

    public function tableDataBuilder($value = false, $fcnt = false, $selected = [])
    {

        $publish_action = '';
        $dtFormat = Config::get('Constant.DEFAULT_DATE_FORMAT');
        $categories = PublicRecordCategory::getRecordByIds(explode(',', $value->txtCategories))->toArray();
        $categories = array_column($categories, 'varTitle');
        $categories = implode(', ', $categories);
        $startDate = date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ', strtotime($value->dtDateTime));
        $endDate = !empty($value->dtEndDateTime) ? date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($value->dtEndDateTime)) : 'No Expiry';

        $record = '<tr ' . (in_array($value->id, $selected) ? 'class="selected-record"' : '') . '>';
        $record .= '<td width="1%" align="left">';
        $record .= '<label class="mt-checkbox mt-checkbox-outline">';
        $record .= '<input type="checkbox" data-title="' . $value->varTitle . '" name="delete[]" class="chkChoose" ' . (in_array($value->id, $selected) ? 'checked' : '') . ' value="' . $value->id . '">';
        $record .= '<span></span>';
        $record .= '</label>';
        $record .= '</td>';
        $record .= '<td width="20%" align="left">';
        $record .= $value->varTitle;
        $record .= '</td>';
        $record .= '<td width="20%" align="left">';
        $record .= $categories;
        $record .= '</td>';
        $record .= '<td width="20%" align="left">';
        $record .= $startDate;
        $record .= '</td>';
        $record .= '<td width="20%" align="left">';
        $record .= $endDate;
        $record .= '</td>';
        $record .= '<td width="20%" align="left">';
        $record .= date($dtFormat, strtotime($value->updated_at));
        $record .= '</td>';
        $record .= '</tr>';
        return $record;
    }

    public function rollBackRecord(Request $request)
    {

        $message = 'Previous record is not available';
        $requestArr = Request::all();
        $request = (object) $requestArr;

        $previousRecord = PublicRecord::getPreviousRecordByMainId($request->id);
        if (!empty($previousRecord)) {

            $main_id = $previousRecord->fkMainRecord;
            $request->id = $previousRecord->id;
            $request->main_id = $main_id;

            $message = PublicRecord::approved_data_Listing($request);

            /* notification for user to record approved */
            $blogs = PublicRecord::getRecordForLogById($previousRecord->id);
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
            $newBlogObj = PublicRecord::getRecordForLogById($main_id);
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
