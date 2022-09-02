<?php

namespace Powerpanel\BlogCategory\Controllers\Powerpanel;

use App\Alias;
use App\CommonModel;
use App\Helpers\AddCategoryAjax;
use App\Helpers\FrontPageContent_Shield;
use App\Helpers\MyLibrary;
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
use Illuminate\Support\Facades\Request;
use Powerpanel\BlogCategory\Models\BlogCategory;
use Powerpanel\Blogs\Models\Blogs;
use Powerpanel\CmsPage\Models\CmsPage;
use Powerpanel\RoleManager\Models\Role_user;
use Powerpanel\Workflow\Models\Comments;
use Powerpanel\Workflow\Models\Workflow;
use Powerpanel\Workflow\Models\WorkflowLog;
use Validator;

class BlogCategoryController extends PowerpanelController
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

        $iTotalRecords = BlogCategory::getRecordCount();
        $draftTotalRecords = BlogCategory::getRecordCountforListDarft(false, true, $userIsAdmin, array(), $this->currentUserRoleSector);
        $trashTotalRecords = BlogCategory::getRecordCountforListTrash(false, true, $userIsAdmin, array(), $this->currentUserRoleSector);
        $favoriteTotalRecords = BlogCategory::getRecordCountforListFavorite(false, true, $userIsAdmin, array(), $this->currentUserRoleSector);
        $approvalTotalRecords = BlogCategory::getRecordCountListApprovalTab(false, false, $userIsAdmin, array(), $this->currentUserRoleSector);

        $this->breadcrumb['title'] = trans('blogcategory::template.blogCategoryModule.manageBlogCategory');
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

        return view('blogcategory::powerpanel.index', compact('iTotalRecords', 'breadcrumb', 'approvalTotalRecords', 'userIsAdmin', 'draftTotalRecords', 'trashTotalRecords', 'favoriteTotalRecords', 'settingarray'));
    }


    public function edit($id = false)
    {
        $hasRecords = 0;
        $documentManager = true;
        $userIsAdmin = false;
        $sector = array('ofreg' => 'OFREG' , 'water' => 'WATER' , 'ict' => 'ICT' , 'energy' => 'ENERGY' , 'fuel' => 'FUEL');
        if (isset($this->currentUserRoleData) && !empty($this->currentUserRoleData)) {
            if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                $userIsAdmin = true;
            }
        } else {
            $userIsAdmin = true;
        }
        if (!is_numeric($id)) {
            $this->breadcrumb['title'] = trans('blogcategory::template.blogCategoryModule.addBlogCategory');
            $this->breadcrumb['module'] = trans('blogcategory::template.blogCategoryModule.manageBlogCategory');
            $this->breadcrumb['url'] = 'powerpanel/blog-category';
            $this->breadcrumb['inner_title'] = '';
            $breadcrumb = $this->breadcrumb;
            $data = compact('documentManager', 'breadcrumb', 'userIsAdmin', 'hasRecords');
        } else {
            $documentManager = true;
            $blogCategory = BlogCategory::getRecordById($id);
            if (empty($blogCategory)) {
                return redirect()->route('powerpanel.blog-category.add');
            }
            if ($blogCategory->fkMainRecord != '0') {
                $blogCategory_highLight = BlogCategory::getRecordById($blogCategory->fkMainRecord);
                $templateData['blogCategory_highLight'] = $blogCategory_highLight;
                $metaInfo_highLight['varMetaTitle'] = $blogCategory_highLight['varMetaTitle'];
                $metaInfo_highLight['varMetaDescription'] = $blogCategory_highLight['varMetaDescription'];
                $metaInfo_highLight['varTags'] = $blogCategory_highLight['varTags'];
                $display_publish = $blogCategory_highLight['chrPublish'];
                $hasRecords = Blogs::getCountById($blogCategory->fkMainRecord);
            } else {
                $templateData['blogCategory_highLight'] = "";
                $metaInfo_highLight['varMetaTitle'] = "";
                $metaInfo_highLight['varMetaDescription'] = "";
                $metaInfo_highLight['varTags'] = "";
                $display_publish = '';
                $blogCategory_highLight = '';
                $hasRecords = Blogs::getCountById($blogCategory->id);
            }
            $metaInfo = array('varMetaTitle' => $blogCategory->varMetaTitle,
                'varMetaDescription' => $blogCategory->varMetaDescription,
                'varTags' => $blogCategory->varTags
            );
            $this->breadcrumb['title'] = trans('blogcategory::template.blogCategoryModule.editBlogCategory');
            $this->breadcrumb['module'] = trans('blogcategory::template.blogCategoryModule.manageBlogCategory');
            $this->breadcrumb['url'] = 'powerpanel/blog-category';
            $this->breadcrumb['inner_title'] = $blogCategory->varTitle;
            $breadcrumb = $this->breadcrumb;
            if (method_exists($this->MyLibrary, 'getModulePageAliasByModuleName')) {
                $categorypagereocrdlink = MyLibrary::getModulePageAliasByModuleName('blog-category');
            }
            if (!empty($categorypagereocrdlink)) {
                $varURL = $categorypagereocrdlink . '/' . $blogCategory->alias->varAlias;
            } else {
                $varURL = $blogCategory->alias->varAlias;
            }
            $metaInfo['varURL'] = $varURL;
            $data = compact('blogCategory', 'documentManager', 'metaInfo', 'breadcrumb', 'blogCategory_highLight', 'metaInfo_highLight', 'display_publish', 'userIsAdmin', 'hasRecords');
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
        $data['sector'] = $sector;
        return view('blogcategory::powerpanel.actions', $data);
    }


    public function handlePost(Request $request)
    {
        $approval = false;
        $userIsAdmin = false;
        if (isset($this->currentUserRoleData) && !empty($this->currentUserRoleData)) {
            if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                $userIsAdmin = true;
            }
        }
        $data = Request::input();
        $rules = array(
            'title' => 'required|max:160|handle_xss|no_url',
            'varMetaTitle' => 'required|max:500|handle_xss|no_url',
            'varMetaDescription' => 'required|max:500|handle_xss|no_url',
            'alias' => 'required',
            'order' => 'required|greater_than_zero|handle_xss|no_url',
        );
        $actionMessage = trans('blogcategory::template.common.oppsSomethingWrong');
        $messsages = array(
            'title.required' => 'Name field is required.',
            'order.required' => trans('blogcategory::template.blogCategoryModule.displayOrder'),
            'order.greater_than_zero' => trans('blogcategory::template.blogCategoryModule.displayGreaterThan'),
            'varMetaTitle.required' => trans('blogcategory::template.blogCategoryModule.metaTitle'),
            'varMetaDescription.required' => trans('blogcategory::template.blogCategoryModule.metaDescription'),
        );
        $validator = Validator::make($data, $rules, $messsages);
        if ($validator->passes()) {
            $module = Modules::getModuleById(Config::get('Constant.MODULE.ID'));
            if (Config::get('Constant.DEFAULT_VISUAL') == 'Y') {
                if ($data['section'] != '[]') {
                    $vsection = $data['section'];
                } else {
                    $vsection = '';
                }
            } else {
                $vsection = $data['description'];
            }
            $blogCategoryArr = [];
            $blogCategoryArr['varTitle'] = stripslashes(trim($data['title']));

            $blogCategoryArr['dtDateTime'] = !empty($data['start_date_time']) ? date('Y-m-d H:i:s', strtotime($data['start_date_time'])) : date('Y-m-d H:i:s');
            $blogCategoryArr['dtEndDateTime'] = !empty($data['end_date_time']) ? date('Y-m-d H:i:s', strtotime($data['end_date_time'])) : null;

            $blogCategoryArr['txtDescription'] = $vsection;
            $blogCategoryArr['varMetaTitle'] = stripslashes(trim($data['varMetaTitle']));
            $blogCategoryArr['varMetaDescription'] = stripslashes(trim($data['varMetaDescription']));
            $blogCategoryArr['varTags'] = trim($data['tags']);

            $blogCategoryArr['chrPublish'] = isset($data['chrMenuDisplay']) ? $data['chrMenuDisplay'] : 'Y';
            $blogCategoryArr['UserID'] = auth()->user()->id;
            if ($data['chrMenuDisplay'] == 'D') {
                $blogCategoryArr['chrDraft'] = 'D';
                $blogCategoryArr['chrPublish'] = 'N';
            } else {
                $blogCategoryArr['chrDraft'] = 'N';
                $blogCategoryArr['chrPublish'] = $data['chrMenuDisplay'];
            }
            if (isset($data['chrPageActive']) && $data['chrPageActive'] != '') {
                $blogCategoryArr['chrPageActive'] = $data['chrPageActive'];
            }
            if (isset($data['chrPageActive']) && $data['chrPageActive'] == 'PP') {
                $blogCategoryArr['varPassword'] = $data['new_password'];
            } else {
                $blogCategoryArr['varPassword'] = '';
            }
            if ($data['chrMenuDisplay'] == 'D') {
                $addlog = Config::get('Constant.UPDATE_DRAFT');
            } else {
                $addlog = '';
            }
            if (Config::get('Constant.CHRSearchRank') == 'Y') {
                $blogCategoryArr['intSearchRank'] = $data['search_rank'];
            }
            $id = Request::segment(3);
            if (is_numeric($id)) { #Edit post Handler=======
            if ($data['oldAlias'] != $data['alias']) {
                Alias::updateAlias($data['oldAlias'], $data['alias']);
            }
                $blogCategory = BlogCategory::getRecordForLogById($id);
                $whereConditions = ['id' => $blogCategory->id];
                if ($blogCategory->chrLock == 'Y' && auth()->user()->id != $blogCategory->LockUserID) {
                    if ($this->currentUserRoleData->chrIsAdmin != 'Y') {
                        $lockedUserData = User::getRecordById($blogCategory->LockUserID, true);
                        $lockedUserName = 'someone';
                        if (!empty($lockedUserData)) {
                            $lockedUserName = $lockedUserData->name;
                        }
                        $actionMessage = "This record has been locked by " . $lockedUserName . ".";
                        return redirect()->route('powerpanel.blog-category.index')->with('message', $actionMessage);
                    }
                }
                $blogCategoryArr['varSector'] = $data['sector'];
                if (File::exists(app_path() . '/Workflow.php') != null || File::exists(base_path() . '/packages/Powerpanel/Workflow/src/Models/Workflow.php') != null) {
                    if (!$userIsAdmin) {
                        $userRole = $this->currentUserRoleData->id;
                    } else {
                        $userRoleData = Role_user::getUserRoleByUserId($blogCategory->UserID);
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
                        if ($blogCategory->fkMainRecord == '0' || empty($workFlowByCat->varUserId)) {
                            $update = CommonModel::updateRecords($whereConditions, $blogCategoryArr, false, 'Powerpanel\BlogCategory\Models\BlogCategory');

                            if ($update) {
                                if (!empty($id)) {
                                    self::swap_order_edit($data['order'], $id);

                                    $logArr = MyLibrary::logData($blogCategory->id, false, $addlog);
                                    if (Auth::user()->can('log-advanced')) {
                                        $newBlogCategoryObj = BlogCategory::getRecordForLogById($blogCategory->id);
                                        $oldRec = $this->recordHistory($blogCategory);
                                        $newRec = $this->newrecordHistory($blogCategory, $newBlogCategoryObj);
                                        $logArr['old_val'] = $oldRec;
                                        $logArr['new_val'] = $newRec;
                                    }
                                    $logArr['varTitle'] = trim($data['title']);
                                    Log::recordLog($logArr);
                                    if (Auth::user()->can('recent-updates-list')) {
                                        if (!isset($newBlogCategoryObj)) {
                                            $newBlogCategoryObj = BlogCategory::getRecordForLogById($blogCategory->id);
                                        }
                                        $notificationArr = MyLibrary::notificationData($blogCategory->id, $newBlogCategoryObj);
                                        RecentUpdates::setNotification($notificationArr);
                                    }
                                    self::flushCache();
                                    if (isset($data['saveandexit']) && $data['saveandexit'] == 'approvesaveandexit') {
                                        $actionMessage = trans('blogcategory::template.common.recordApprovalMessage');
                                    } else {
                                        $actionMessage = trans('blogcategory::template.blogCategoryModule.updateMessage');
                                    }
                                }
                            }
                        } else {
                            $updateModuleFields = $blogCategoryArr;
                            $this->insertApprovedRecord($updateModuleFields, $data, $id);
                            if (isset($data['saveandexit']) && $data['saveandexit'] == 'approvesaveandexit') {
                                $actionMessage = trans('blogcategory::template.common.recordApprovalMessage');
                            } else {
                                $actionMessage = trans('blogcategory::template.blogCategoryModule.updateMessage');
                            }
                            $approval = $id;
                        }
                    } else {
                        if ($workFlowByCat->charNeedApproval == 'Y') {
                            $approvalObj = $this->insertApprovalRecord($blogCategory, $data, $blogCategoryArr);
                            if (isset($data['saveandexit']) && $data['saveandexit'] == 'approvesaveandexit') {
                                $actionMessage = trans('blogcategory::template.common.recordApprovalMessage');
                            } else {
                                $actionMessage = trans('blogcategory::template.blogCategoryModule.updateMessage');
                            }
                            $approval = $approvalObj->id;
                        }
                    }
                } else {
                    $update = CommonModel::updateRecords($whereConditions, $blogCategoryArr, false, 'Powerpanel\BlogCategory\Models\BlogCategory');
                    $actionMessage = trans('blogcategory::template.blogCategoryModule.updateMessage');
                }
            } else { #Add post Handler=======
            if (File::exists(app_path() . '/Workflow.php') != null || File::exists(base_path() . '/packages/Powerpanel/Workflow/src/Models/Workflow.php') != null) {
                $workFlowByCat = Workflow::getRecordByCategoryId($module->intFkGroupCode, $this->currentUserRoleData->id, Config::get('Constant.MODULE.ID'));
            }
                if (!empty($workFlowByCat->varUserId) && $workFlowByCat->chrNeedAddPermission == 'Y' && !$userIsAdmin) {

                    $blogCategoryArr['chrPublish'] = 'N';
                    $blogCategoryArr['chrDraft'] = 'N';
                    $blogCategoryObj = $this->insertNewRecord($data, $blogCategoryArr);
                    if ($data['chrMenuDisplay'] == 'D') {
                        $blogCategoryArr['chrDraft'] = 'D';
                    }
                    $blogCategoryArr['chrPublish'] = 'Y';
                    $approvalObj = $this->insertApprovalRecord($blogCategoryObj, $data, $blogCategoryArr);
                    $approval = $blogCategoryObj->id;
                } else {
                    $blogCategoryObj = $this->insertNewRecord($data, $blogCategoryArr);
                    $approval = $blogCategoryObj->id;
                }
                if (isset($data['saveandexit']) && $data['saveandexit'] == 'approvesaveandexit') {
                    $actionMessage = trans('blogcategory::template.common.recordApprovalMessage');
                } else {
                    $actionMessage = trans('blogcategory::template.blogCategoryModule.addedMessage');
                }
                $id = $blogCategoryObj->id;
            }
            if (method_exists($this->Alias, 'updatePreviewAlias')) {
                Alias::updatePreviewAlias($data['alias'], 'N');
            }
            if ((!empty(Request::get('saveandexit')) && Request::get('saveandexit') == 'saveandexit') || !$userIsAdmin) {
                if ($data['chrMenuDisplay'] == 'D') {
                    return redirect()->route('powerpanel.blog-category.index', 'tab=D')->with('message', $actionMessage);
                } else {
                    return redirect()->route('powerpanel.blog-category.index')->with('message', $actionMessage);
                }
            } else {
                return redirect()->route('powerpanel.blog-category.edit', $id)->with('message', $actionMessage);
            }
        } else {
            return Redirect::back()->withErrors($validator)->withInput();
        }
    }


    public function insertApprovedRecord($updateModuleFields, $postArr, $id)
    {
        $whereConditions = ['id' => $postArr['fkMainRecord']];
        $updateModuleFields['chrAddStar'] = 'N';
        $update = CommonModel::updateRecords($whereConditions, $updateModuleFields, false, 'Powerpanel\BlogCategory\Models\BlogCategory');
        if ($update) {
            self::swap_order_edit($postArr['order'], $postArr['fkMainRecord']);
        }
        $whereConditions_ApproveN = ['fkMainRecord' => $postArr['fkMainRecord']];
        $updateToApproveN = [
            'chrApproved' => 'N',
            'chrLetest' => 'N',
            'intApprovedBy' => '0',
        ];
        CommonModel::updateRecords($whereConditions_ApproveN, $updateToApproveN, false, 'Powerpanel\BlogCategory\Models\BlogCategory');
        $whereConditionsApprove = ['id' => $id, 'chrMain' => 'N'];
        $updateToApprove = [
            'chrApproved' => 'Y',
            'chrRollBack' => 'Y',
            'intApprovedBy' => auth()->user()->id,
        ];
        CommonModel::updateRecords($whereConditionsApprove, $updateToApprove, false, 'Powerpanel\BlogCategory\Models\BlogCategory');
        if ($postArr['chrMenuDisplay'] == 'D') {
            $addlog = Config::get('Constant.DRAFT_RECORD_APPROVED');
        } else {
            $addlog = Config::get('Constant.RECORD_APPROVED');
        }
        $newCmsPageObj = BlogCategory::getRecordForLogById($id);
        $logArr = MyLibrary::logData($id, false, $addlog);
        $logArr['varTitle'] = stripslashes($newCmsPageObj->varTitle);
        Log::recordLog($logArr);
        /* notification for user to record approved */
        $careers = BlogCategory::getRecordForLogById($id);
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

    public function insertApprovalRecord($moduleObj, $postArr, $blogCategoryArr)
    {
        $response = false;
        $blogCategoryArr['intAliasId'] = MyLibrary::insertAlias($postArr['alias']);
        $blogCategoryArr['intDisplayOrder'] = $postArr['order'];
        $blogCategoryArr['chrMain'] = 'N';
        $blogCategoryArr['chrLetest'] = 'Y';
        $blogCategoryArr['fkMainRecord'] = $moduleObj->id;
        if (Config::get('Constant.CHRSearchRank') == 'Y') {
            $blogCategoryArr['intSearchRank'] = $postArr['search_rank'];
        }
        if ($postArr['chrMenuDisplay'] == 'D') {
            $blogCategoryArr['chrDraft'] = 'D';
            $blogCategoryArr['chrPublish'] = 'N';
        } else {
            $blogCategoryArr['chrDraft'] = 'N';
            $blogCategoryArr['chrPublish'] = $postArr['chrMenuDisplay'];
        }
        if (isset($postArr['chrPageActive']) && $postArr['chrPageActive'] != '') {
            $blogCategoryArr['chrPageActive'] = $postArr['chrPageActive'];
        }
        if (isset($postArr['chrPageActive']) && $postArr['chrPageActive'] == 'PP') {
            $blogCategoryArr['varPassword'] = $postArr['new_password'];
        } else {
            $blogCategoryArr['varPassword'] = '';
        }
        if ($postArr['chrMenuDisplay'] == 'D') {
            $addlog = Config::get('Constant.DRAFT_SENT_FOR_APPROVAL');
        } else {
            $addlog = Config::get('Constant.SENT_FOR_APPROVAL');
        }
        $blogCategoryID = CommonModel::addRecord($blogCategoryArr, 'Powerpanel\BlogCategory\Models\BlogCategory');
        if (!empty($blogCategoryID)) {
            $id = $blogCategoryID;
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
            $newblogCategoryObj = BlogCategory::getRecordForLogById($id);
            $logArr = MyLibrary::logData($id, false, $addlog);
            $logArr['varTitle'] = $newblogCategoryObj->varTitle;
            Log::recordLog($logArr);
            if (Auth::user()->can('recent-updates-list')) {
                $notificationArr = MyLibrary::notificationData($id, $newblogCategoryObj);
                RecentUpdates::setNotification($notificationArr);
            }
            $response = $newblogCategoryObj;
            self::flushCache();
            $actionMessage = trans('blogcategory::template.blogCategoryModule.addedMessage');
        }
        $whereConditionsAddstar = ['id' => $moduleObj->id];
        $updateAddStar = [
            'chrAddStar' => 'Y',
        ];
        CommonModel::updateRecords($whereConditionsAddstar, $updateAddStar, false, 'Powerpanel\BlogCategory\Models\BlogCategory');
        return $response;
    }

    public function insertNewRecord($postArr, $blogCategoryArr)
    {
        $response = false;
        $blogCategoryArr['intAliasId'] = MyLibrary::insertAlias($postArr['alias']);
        $blogCategoryArr['intDisplayOrder'] = self::swap_order_add($postArr['order']);
        $blogCategoryArr['chrMain'] = 'Y';
        $blogCategoryArr['varSector'] = $postArr['sector'];
        if (Config::get('Constant.CHRSearchRank') == 'Y') {
            $blogCategoryArr['intSearchRank'] = $postArr['search_rank'];
        }
        if ($postArr['chrMenuDisplay'] == 'D') {
            $blogCategoryArr['chrDraft'] = 'D';
            $blogCategoryArr['chrPublish'] = 'N';
        } else {
            $blogCategoryArr['chrDraft'] = 'N';
        }
        if (isset($postArr['chrPageActive']) && $postArr['chrPageActive'] != '') {
            $blogCategoryArr['chrPageActive'] = $postArr['chrPageActive'];
        }
        if (isset($postArr['chrPageActive']) && $postArr['chrPageActive'] == 'PP') {
            $blogCategoryArr['varPassword'] = $postArr['new_password'];
        } else {
            $blogCategoryArr['varPassword'] = '';
        }
        if ($postArr['chrMenuDisplay'] == 'D') {
            $addlog = Config::get('Constant.ADDED_DRAFT');
        } else {
            $addlog = '';
        }
        $blogCategoryID = CommonModel::addRecord($blogCategoryArr, 'Powerpanel\BlogCategory\Models\BlogCategory');
        if (!empty($blogCategoryID)) {
            $id = $blogCategoryID;
            $newBlogCategoryObj = BlogCategory::getRecordForLogById($id);
            $logArr = MyLibrary::logData($id, false, $addlog);
            $logArr['varTitle'] = stripslashes($newBlogCategoryObj->varTitle);
            Log::recordLog($logArr);
            if (Auth::user()->can('recent-updates-list')) {
                $notificationArr = MyLibrary::notificationData($id, $newBlogCategoryObj);
                RecentUpdates::setNotification($notificationArr);
            }
            $response = $newBlogCategoryObj;
            self::flushCache();
            $actionMessage = trans('blogcategory::template.blogCategoryModule.addedMessage');
        }
        return $response;
    }


    public function get_list()
    {
        $filterArr = [];
        $records = [];
        $records["data"] = [];
        $filterArr['orderColumnNo'] = (!empty(Request::input('order')[0]['column']) ? Request::input('order')[0]['column'] : '');
        $filterArr['orderByFieldName'] = (!empty(Request::input('columns')[$filterArr['orderColumnNo']]['name']) ? Request::input('columns')[$filterArr['orderColumnNo']]['name'] : '');
        $filterArr['orderTypeAscOrDesc'] = (!empty(Request::input('order')[0]['dir']) ? Request::input('order')[0]['dir'] : '');
        $filterArr['statusFilter'] = !empty(Request::input('statusValue')) ? Request::input('statusValue') : '';
        $filterArr['customFilterIdentity'] = !empty(Request::get('customFilterIdentity')) ? Request::get('customFilterIdentity') : '';
        $filterArr['catFilter'] = !empty(Request::input('catValue')) ? Request::input('catValue') : '';
        $filterArr['searchFilter'] = !empty(Request::input('searchValue')) ? Request::input('searchValue') : '';
        $filterArr['iDisplayLength'] = intval(Request::input('length'));
        $filterArr['iDisplayStart'] = intval(Request::input('start'));
        $filterArr['rangeFilter'] = !empty(Request::input('rangeFilter')) ? Request::input('rangeFilter') : '';
        $sEcho = intval(Request::input('draw'));

        $isAdmin = false;
        if (isset($this->currentUserRoleData) && !empty($this->currentUserRoleData)) {
            if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                $isAdmin = true;
            }
        }

        $ignoreId = [];
        $arrResults = BlogCategory::getRecordList($filterArr, $isAdmin, $ignoreId, $this->currentUserRoleSector);
        $iTotalRecords = BlogCategory::getRecordCountforList($filterArr, true, $isAdmin, $ignoreId, $this->currentUserRoleSector);

        if (!empty($arrResults)) {
            $currentUserID = auth()->user()->id;
            $permit = [
                'canblogcategoryedit' => Auth::user()->can('blog-category-edit'),
                'canblogcategorypublish' => Auth::user()->can('blog-category-publish'),
                'canblogcategorydelete' => Auth::user()->can('blog-category-delete'),
                'canblogcategoryreviewchanges' => Auth::user()->can('blog-category-reviewchanges'),
                'canloglist' => Auth::user()->can('log-list'),
            ];

            foreach ($arrResults as $key => $value) {
                if (!in_array($value->id, $ignoreId)) {
                    $records['data'][] = $this->tableData($value, $permit, $currentUserID);
                }
            }
        }

        $NewRecordsCount = BlogCategory::getNewRecordsCount();
        $records["newRecordCount"] = $NewRecordsCount;
        if (!empty(Request::input('customActionType')) && Request::input('customActionType') == 'group_action') {
            $records['customActionStatus'] = 'OK';
        }
        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;
        return json_encode($records);
    }

    public function get_list_New()
    {
        $filterArr = [];
        $records = [];
        $records["data"] = [];
        $filterArr['orderColumnNo'] = (!empty(Request::input('order')[0]['column']) ? Request::input('order')[0]['column'] : '');
        $filterArr['orderByFieldName'] = (!empty(Request::input('columns')[$filterArr['orderColumnNo']]['name']) ? Request::input('columns')[$filterArr['orderColumnNo']]['name'] : '');
        $filterArr['orderTypeAscOrDesc'] = (!empty(Request::input('order')[0]['dir']) ? Request::input('order')[0]['dir'] : '');
        $filterArr['statusFilter'] = !empty(Request::input('statusValue')) ? Request::input('statusValue') : '';
        $filterArr['catFilter'] = !empty(Request::input('catValue')) ? Request::input('catValue') : '';
        $filterArr['searchFilter'] = !empty(Request::input('searchValue')) ? Request::input('searchValue') : '';
        $filterArr['customFilterIdentity'] = !empty(Request::get('customFilterIdentity')) ? Request::get('customFilterIdentity') : '';
        $filterArr['iDisplayLength'] = intval(Request::input('length'));
        $filterArr['iDisplayStart'] = intval(Request::input('start'));
        $filterArr['rangeFilter'] = !empty(Request::input('rangeFilter')) ? Request::input('rangeFilter') : '';
        $sEcho = intval(Request::input('draw'));

        $isAdmin = false;
        if (isset($this->currentUserRoleData) && !empty($this->currentUserRoleData)) {
            if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                $isAdmin = true;
            }
        }

        $ignoreId = [];
        $arrResults = BlogCategory::getRecordList_tab1($filterArr, $isAdmin, $ignoreId, $this->currentUserRoleSector);
        $iTotalRecords = BlogCategory::getRecordCountListApprovalTab($filterArr, true, $isAdmin, $ignoreId, $this->currentUserRoleSector);

        if (!empty($arrResults)) {
            $currentUserID = auth()->user()->id;
            $permit = [
                'canblogcategoryedit' => Auth::user()->can('blog-category-edit'),
                'canblogcategorypublish' => Auth::user()->can('blog-category-publish'),
                'canblogcategorydelete' => Auth::user()->can('blog-category-delete'),
                'canblogcategoryreviewchanges' => Auth::user()->can('blog-category-reviewchanges'),
                'canloglist' => Auth::user()->can('log-list'),
            ];

            foreach ($arrResults as $key => $value) {
                if (!in_array($value->id, $ignoreId)) {
                    $records['data'][] = $this->tableData_tab1($value, $permit, $currentUserID);
                }
            }
        }

        $NewRecordsCount = BlogCategory::getNewRecordsCount();
        $records["newRecordCount"] = $NewRecordsCount;
        if (!empty(Request::input('customActionType')) && Request::input('customActionType') == 'group_action') {
            $records['customActionStatus'] = 'OK';
        }
        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;
        return json_encode($records);
    }

    public function get_list_favorite()
    {
        $filterArr = [];
        $records = [];
        $records["data"] = [];
        $filterArr['orderColumnNo'] = (!empty(Request::input('order')[0]['column']) ? Request::input('order')[0]['column'] : '');
        $filterArr['orderByFieldName'] = (!empty(Request::input('columns')[$filterArr['orderColumnNo']]['name']) ? Request::input('columns')[$filterArr['orderColumnNo']]['name'] : '');
        $filterArr['orderTypeAscOrDesc'] = (!empty(Request::input('order')[0]['dir']) ? Request::input('order')[0]['dir'] : '');
        $filterArr['statusFilter'] = !empty(Request::input('statusValue')) ? Request::input('statusValue') : '';
        $filterArr['customFilterIdentity'] = !empty(Request::get('customFilterIdentity')) ? Request::get('customFilterIdentity') : '';
        $filterArr['catFilter'] = !empty(Request::input('catValue')) ? Request::input('catValue') : '';
        $filterArr['searchFilter'] = !empty(Request::input('searchValue')) ? Request::input('searchValue') : '';
        $filterArr['iDisplayLength'] = intval(Request::input('length'));
        $filterArr['iDisplayStart'] = intval(Request::input('start'));
        $filterArr['rangeFilter'] = !empty(Request::input('rangeFilter')) ? Request::input('rangeFilter') : '';
        $sEcho = intval(Request::input('draw'));

        $isAdmin = false;
        if (isset($this->currentUserRoleData) && !empty($this->currentUserRoleData)) {
            if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                $isAdmin = true;
            }
        }

        $ignoreId = [];
        $arrResults = BlogCategory::getRecordListFavorite($filterArr, $isAdmin, $ignoreId, $this->currentUserRoleSector);
        $iTotalRecords = BlogCategory::getRecordCountforListFavorite($filterArr, true, $isAdmin, $ignoreId, $this->currentUserRoleSector);

        if (!empty($arrResults)) {
            $currentUserID = auth()->user()->id;
            $permit = [
                'canblogcategoryedit' => Auth::user()->can('blog-category-edit'),
                'canblogcategorypublish' => Auth::user()->can('blog-category-publish'),
                'canblogcategorydelete' => Auth::user()->can('blog-category-delete'),
                'canblogcategoryreviewchanges' => Auth::user()->can('blog-category-reviewchanges'),
                'canloglist' => Auth::user()->can('log-list'),
            ];

            foreach ($arrResults as $key => $value) {
                if (!in_array($value->id, $ignoreId)) {
                    $records['data'][] = $this->tableDataFavorite($value, $permit, $currentUserID);
                }
            }
        }

        $NewRecordsCount = BlogCategory::getNewRecordsCount();
        $records["newRecordCount"] = $NewRecordsCount;
        if (!empty(Request::input('customActionType')) && Request::input('customActionType') == 'group_action') {
            $records['customActionStatus'] = 'OK';
        }
        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;
        return json_encode($records);
    }

    public function get_list_draft()
    {
        $filterArr = [];
        $records = [];
        $records["data"] = [];
        $filterArr['orderColumnNo'] = (!empty(Request::input('order')[0]['column']) ? Request::input('order')[0]['column'] : '');
        $filterArr['orderByFieldName'] = (!empty(Request::input('columns')[$filterArr['orderColumnNo']]['name']) ? Request::input('columns')[$filterArr['orderColumnNo']]['name'] : '');
        $filterArr['orderTypeAscOrDesc'] = (!empty(Request::input('order')[0]['dir']) ? Request::input('order')[0]['dir'] : '');
        $filterArr['statusFilter'] = !empty(Request::input('statusValue')) ? Request::input('statusValue') : '';
        $filterArr['customFilterIdentity'] = !empty(Request::get('customFilterIdentity')) ? Request::get('customFilterIdentity') : '';
        $filterArr['catFilter'] = !empty(Request::input('catValue')) ? Request::input('catValue') : '';
        $filterArr['searchFilter'] = !empty(Request::input('searchValue')) ? Request::input('searchValue') : '';
        $filterArr['iDisplayLength'] = intval(Request::input('length'));
        $filterArr['iDisplayStart'] = intval(Request::input('start'));
        $filterArr['rangeFilter'] = !empty(Request::input('rangeFilter')) ? Request::input('rangeFilter') : '';
        $sEcho = intval(Request::input('draw'));

        $isAdmin = false;
        if (isset($this->currentUserRoleData) && !empty($this->currentUserRoleData)) {
            if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                $isAdmin = true;
            }
        }

        $ignoreId = [];
        $arrResults = BlogCategory::getRecordListDraft($filterArr, $isAdmin, $ignoreId, $this->currentUserRoleSector);
        $iTotalRecords = BlogCategory::getRecordCountforListDarft($filterArr, true, $isAdmin, $ignoreId, $this->currentUserRoleSector);

        if (!empty($arrResults)) {
            $currentUserID = auth()->user()->id;
            $permit = [
                'canblogcategoryedit' => Auth::user()->can('blog-category-edit'),
                'canblogcategorypublish' => Auth::user()->can('blog-category-publish'),
                'canblogcategorydelete' => Auth::user()->can('blog-category-delete'),
                'canblogcategoryreviewchanges' => Auth::user()->can('blog-category-reviewchanges'),
                'canloglist' => Auth::user()->can('log-list'),
            ];

            foreach ($arrResults as $key => $value) {
                if (!in_array($value->id, $ignoreId)) {
                    $records['data'][] = $this->tableDataDraft($value, $permit, $currentUserID);
                }
            }
        }

        $NewRecordsCount = BlogCategory::getNewRecordsCount();
        $records["newRecordCount"] = $NewRecordsCount;
        if (!empty(Request::input('customActionType')) && Request::input('customActionType') == 'group_action') {
            $records['customActionStatus'] = 'OK';
        }
        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;
        return json_encode($records);
    }

    public function get_list_trash()
    {
        $filterArr = [];
        $records = [];
        $records["data"] = [];
        $filterArr['orderColumnNo'] = (!empty(Request::input('order')[0]['column']) ? Request::input('order')[0]['column'] : '');
        $filterArr['orderByFieldName'] = (!empty(Request::input('columns')[$filterArr['orderColumnNo']]['name']) ? Request::input('columns')[$filterArr['orderColumnNo']]['name'] : '');
        $filterArr['orderTypeAscOrDesc'] = (!empty(Request::input('order')[0]['dir']) ? Request::input('order')[0]['dir'] : '');
        $filterArr['statusFilter'] = !empty(Request::input('statusValue')) ? Request::input('statusValue') : '';
        $filterArr['customFilterIdentity'] = !empty(Request::get('customFilterIdentity')) ? Request::get('customFilterIdentity') : '';
        $filterArr['catFilter'] = !empty(Request::input('catValue')) ? Request::input('catValue') : '';
        $filterArr['searchFilter'] = !empty(Request::input('searchValue')) ? Request::input('searchValue') : '';
        $filterArr['iDisplayLength'] = intval(Request::input('length'));
        $filterArr['iDisplayStart'] = intval(Request::input('start'));
        $filterArr['rangeFilter'] = !empty(Request::input('rangeFilter')) ? Request::input('rangeFilter') : '';
        $sEcho = intval(Request::input('draw'));

        $isAdmin = false;
        if (isset($this->currentUserRoleData) && !empty($this->currentUserRoleData)) {
            if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                $isAdmin = true;
            }
        }

        $ignoreId = [];
        $arrResults = BlogCategory::getRecordListTrash($filterArr, $isAdmin, $ignoreId, $this->currentUserRoleSector);
        $iTotalRecords = BlogCategory::getRecordCountforListTrash($filterArr, true, $isAdmin, $ignoreId, $this->currentUserRoleSector);

        if (!empty($arrResults)) {
            $currentUserID = auth()->user()->id;
            $permit = [
                'canblogcategoryedit' => Auth::user()->can('blog-category-edit'),
                'canblogcategorypublish' => Auth::user()->can('blog-category-publish'),
                'canblogcategorydelete' => Auth::user()->can('blog-category-delete'),
                'canblogcategoryreviewchanges' => Auth::user()->can('blog-category-reviewchanges'),
                'canloglist' => Auth::user()->can('log-list'),
            ];

            foreach ($arrResults as $key => $value) {
                if (!in_array($value->id, $ignoreId)) {
                    $records['data'][] = $this->tableDataTrash($value, $permit, $currentUserID);
                }
            }
        }

        $NewRecordsCount = BlogCategory::getNewRecordsCount();
        $records["newRecordCount"] = $NewRecordsCount;
        if (!empty(Request::input('customActionType')) && Request::input('customActionType') == 'group_action') {
            $records['customActionStatus'] = 'OK';
        }
        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;
        return json_encode($records);
    }


    public function tableData($value, $permit, $currentUserID)
    {
        $hasRecords = Blogs::getCountById($value->id);

        // Checkbox
        if ($hasRecords > 0) {
            $checkbox = '<div class="checker"><a href="javascript:void(0);" data-toggle = "tooltip" data-placement = "right" data-toggle = "tooltip" title = "This category is selected in ' . trans("blogcategory::template.sidebar.blogs") . ', so it can&#39;t be deleted."><i style = "color:red" class = "ri-spam-line fs-16"></i></a></div>';
        }else{
            $checkbox = view('powerpanel.partials.checkbox', ['name'=>'delete', 'value'=>$value->id])->render();
        }

        // StartDate
        $startDate = $value->dtDateTime;
        $startDate = '<span align="left" data-bs-toggle="tooltip" data-bs-placement="bottom" title="'.date(Config::get("Constant.DEFAULT_DATE_FORMAT").' '.Config::get("Constant.DEFAULT_TIME_FORMAT"), strtotime($startDate)).'">'.date(Config::get('Constant.DEFAULT_DATE_FORMAT'), strtotime($startDate)).'</span>';

        // Title
        $title = $value->varTitle;


        // Publish Action
        $publish_action = '';
        if ($value->chrAddStar != 'Y') {
            if ($value->chrDraft != 'D') {
                if ($permit['canblogcategorypublish']) {
                    if ($hasRecords == 0) {
                        if ($value->chrPublish == 'Y') {
                            $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/blog-category', 'data_alias'=>$value->id, 'title'=>trans("blogcategory::template.common.publishedRecord"), 'data_value'=>'Unpublish', 'checked'=>'checked'])->render();
                        } else {
                            $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/blog-category', 'data_alias'=>$value->id, 'title'=>trans("blogcategory::template.common.unpublishedRecord"), 'data_value'=>'Publish'])->render();
                        }
                    } else {
                        $publish_action .= '<a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-toggle="tooltip" title="This category is selected in ' . trans("blogcategory::template.sidebar.blogs") . ', so it can&#39;t be Publish/Unpublish."><i style="color:red" class="ri-toggle-line icon-publish fs-24"></i></a>';
                    }
                } else {
                    $publish_action = "-";
                }
            } else {
                if ($permit['canblogcategorypublish']) {
                    if ($hasRecords == 0) {
                        if ($value->chrPublish == 'Y') {
                            $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/blog-category', 'data_alias'=>$value->id, 'title'=>trans("blogcategory::template.common.publishedRecord"), 'data_value'=>'Unpublish', 'checked'=>'checked'])->render();
                        } else {
                            $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/blog-category', 'data_alias'=>$value->id, 'title'=>trans("blogcategory::template.common.unpublishedRecord"), 'data_value'=>'Publish'])->render();
                        }
                    } else {
                        $publish_action .= '<a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-toggle="tooltip" title="This category is selected in ' . trans("blogcategory::template.sidebar.blogs") . ', so it can&#39;t be Publish/Unpublish."><i style="color:red" class="ri-toggle-line icon-publish fs-24"></i></a>';
                    }
                } else {
                    $publish_action = "-";
                }
            }
        } else {
            if ($permit['canblogcategorypublish']) {
                $publish_action .= '<a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-toggle="tooltip" title="This blog category is in Approval request so can&#39;t be Publish/Unpublish."><i style="color:red" class="ri-toggle-line icon-publish fs-24"></i></a>';
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
        if ($permit['canblogcategoryedit']) {
            $Quickedit_startDate = date('Y-m-d H:i', strtotime($value->dtDateTime));
            $Quickedit_endDate = !empty($value->dtEndDateTime) ? date('Y-m-d H:i', strtotime($value->dtEndDateTime)) : 'No Expiry';

            if ($value->chrLock != 'Y') {
                if (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y') {
                    if ($permit['canblogcategoryreviewchanges']) {
                        $title_action .= "<a href=\"javascript:void(0);\" class=\"icon_title1 approval_active\" data-bs-toggle=\"tooltip\" data-bs-placement=\"bottom\" title=\"Click here to see all approval records.\" style=\"margin-right: 5px;\" onclick=\"javascript:expandcollapsepanel(this ,'tasklisting" . $value->id . "', 'mainsingnimg" . $value->id . "'," . $value->id . ')" id="mainsingnimg' . $value->id . '"><i class="ri-stack-line fs-16"></i></a>';

                        if (File::exists(base_path() . '/packages/Powerpanel/Workflow/src/Models/Workflow.php')) {
                            if ($this->currentUserRoleData->chrIsAdmin == 'Y' && count($value->child) > 1) {
                                $title_action .= "<a href='javascript:void(0);' data-bs-toggle='tooltip' data-bs-placement='bottom' style='margin-right: 5px;' title='Rollback to previous version' onclick='rollbackToPreviousVersion('" . $value->id . "');'  class='icon_title2 rollback_active'><i class='ri-history-line fs-16'></i></a>";
                            }
                        }
                    }
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



        // All - Actions
        $logurl = url('powerpanel/log?id=' . $value->id . '&mid=' . Config::get('Constant.MODULE.ID'));
        $allActions = view('powerpanel.partials.all-actions',
                    [
                        'tabName'=>'All',
                        'canedit'=> $permit['canblogcategoryedit'],
                        'candelete'=>$permit['canblogcategorydelete'],
                        'canloglist'=>$permit['canloglist'],
                        'value'=>$value,
                        'currentUserID' => $currentUserID,
                        'chrIsAdmin' => $this->currentUserRoleData->chrIsAdmin,
                        'module_name'=>'blogcategory',
                        'module_edit_url' => route('powerpanel.blog-category.edit', array('alias' => $value->id)),
                        'module_type'=>'category',
                        'viewlink' => isset($viewlink) ? $viewlink : "",
                        'linkviewLable' => isset($linkviewLable) ? $linkviewLable : "",
                        'hasRecords' => Blogs::getCountById($value->id),
                        'logurl' => $logurl
                    ])->render();

        if($permit['canblogcategoryedit'] || $permit['canblogcategorydelete']){
            $allActions = $allActions;
        } else {
            $allActions = "-";
        }


        $records = array(
            $checkbox,
            '<div class="pages_title_div_row">' . $Favorite . ' <span class="title-txt"> ' . $title . ' ' . $status . $statusdata . ' - ' . $sector . ' ' .$title_action. '</span></div>',
            $startDate,
            $publish_action,
            $allActions
        );
        return $records;
    }

    public function tableData_tab1($value, $permit, $currentUserID)
    {
        $hasRecords = Blogs::getCountById($value->id);

        // Checkbox
        if ($hasRecords > 0) {
            $checkbox = '<div class="checker"><a href="javascript:void(0);" data-toggle = "tooltip" data-placement = "right" data-toggle = "tooltip" title = "This category is selected in ' . trans("blogcategory::template.sidebar.blogs") . ', so it can&#39;t be deleted."><i style = "color:red" class = "ri-spam-line fs-16"></i></a></div>';
        }else{
            $checkbox = view('powerpanel.partials.checkbox', ['name'=>'delete', 'value'=>$value->id])->render();
        }

        // StartDate
        $startDate = $value->dtDateTime;
        $startDate = '<span align="left" data-bs-toggle="tooltip" data-bs-placement="bottom" title="'.date(Config::get("Constant.DEFAULT_DATE_FORMAT").' '.Config::get("Constant.DEFAULT_TIME_FORMAT"), strtotime($startDate)).'">'.date(Config::get('Constant.DEFAULT_DATE_FORMAT'), strtotime($startDate)).'</span>';

        // Title
        $title = $value->varTitle;


        // Publish Action
        $publish_action = '';
        if ($value->chrAddStar != 'Y') {
            if ($value->chrDraft != 'D') {
                if ($permit['canblogcategorypublish']) {
                    if ($hasRecords == 0) {
                        if ($value->chrPublish == 'Y') {
                            $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/blog-category', 'data_alias'=>$value->id, 'title'=>trans("blogcategory::template.common.publishedRecord"), 'data_value'=>'Unpublish', 'checked'=>'checked'])->render();
                        } else {
                            $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/blog-category', 'data_alias'=>$value->id, 'title'=>trans("blogcategory::template.common.unpublishedRecord"), 'data_value'=>'Publish'])->render();
                        }
                    } else {
                        $publish_action .= '<a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-toggle="tooltip" title="This category is selected in ' . trans("blogcategory::template.sidebar.blogs") . ', so it can&#39;t be Publish/Unpublish."><i style="color:red" class="ri-toggle-line icon-publish fs-24"></i></a>';
                    }
                } else {
                    $publish_action = "-";
                }
            } else {
                if ($permit['canblogcategorypublish']) {
                    if ($hasRecords == 0) {
                        if ($value->chrPublish == 'Y') {
                            $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/blog-category', 'data_alias'=>$value->id, 'title'=>trans("blogcategory::template.common.publishedRecord"), 'data_value'=>'Unpublish', 'checked'=>'checked'])->render();
                        } else {
                            $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/blog-category', 'data_alias'=>$value->id, 'title'=>trans("blogcategory::template.common.unpublishedRecord"), 'data_value'=>'Publish'])->render();
                        }
                    } else {
                        $publish_action .= '<a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-toggle="tooltip" title="This category is selected in ' . trans("blogcategory::template.sidebar.blogs") . ', so it can&#39;t be Publish/Unpublish."><i style="color:red" class="ri-toggle-line icon-publish fs-24"></i></a>';
                    }
                } else {
                    $publish_action = "-";
                }
            }
        } else {
            if ($permit['canblogcategorypublish']) {
                $publish_action .= '<a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-toggle="tooltip" title="This blog category is in Approval request so can&#39;t be Publish/Unpublish."><i style="color:red" class="ri-toggle-line icon-publish fs-24"></i></a>';
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
        if ($permit['canblogcategoryedit']) {
            $Quickedit_startDate = date('Y-m-d H:i', strtotime($value->dtDateTime));
            $Quickedit_endDate = !empty($value->dtEndDateTime) ? date('Y-m-d H:i', strtotime($value->dtEndDateTime)) : 'No Expiry';

            if ($value->chrLock != 'Y') {
                if (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y') {
                    if ($permit['canblogcategoryreviewchanges']) {
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



        // All - Actions
        $logurl = url('powerpanel/log?id=' . $value->id . '&mid=' . Config::get('Constant.MODULE.ID'));
        $allActions = view('powerpanel.partials.all-actions',
                    [
                        'tabName'=>'Approval',
                        'canedit'=> $permit['canblogcategoryedit'],
                        'candelete'=>$permit['canblogcategorydelete'],
                        'canloglist'=>$permit['canloglist'],
                        'value'=>$value,
                        'currentUserID' => $currentUserID,
                        'chrIsAdmin' => $this->currentUserRoleData->chrIsAdmin,
                        'module_name'=>'blogcategory',
                        'module_edit_url' => route('powerpanel.blog-category.edit', array('alias' => $value->id)),
                        'module_type'=>'category',
                        'viewlink' => isset($viewlink) ? $viewlink : "",
                        'linkviewLable' => isset($linkviewLable) ? $linkviewLable : "",
                        'hasRecords' => Blogs::getCountById($value->id),
                        'logurl' => $logurl
                    ])->render();

        if($permit['canblogcategoryedit'] || $permit['canblogcategorydelete']){
            $allActions = $allActions;
        } else {
            $allActions = "-";
        }


        $records = array(
            $checkbox,
            '<div class="pages_title_div_row">' . $Favorite . ' <span class="title-txt"> ' . $title . ' ' . $status . $statusdata . ' - ' . $sector . ' ' .$title_action. '</span></div>',
            $startDate,
            $publish_action,
            $allActions
        );
        return $records;
    }

    public function tableDataFavorite($value, $permit, $currentUserID)
    {
        $hasRecords = Blogs::getCountById($value->id);

        // Checkbox
        if ($hasRecords > 0) {
            $checkbox = '<div class="checker"><a href="javascript:void(0);" data-toggle = "tooltip" data-placement = "right" data-toggle = "tooltip" title = "This category is selected in ' . trans("blogcategory::template.sidebar.blogs") . ', so it can&#39;t be deleted."><i style = "color:red" class = "ri-spam-line fs-16"></i></a></div>';
        }else{
            $checkbox = view('powerpanel.partials.checkbox', ['name'=>'delete', 'value'=>$value->id])->render();
        }

        // StartDate
        $startDate = $value->dtDateTime;
        $startDate = '<span align="left" data-bs-toggle="tooltip" data-bs-placement="bottom" title="'.date(Config::get("Constant.DEFAULT_DATE_FORMAT").' '.Config::get("Constant.DEFAULT_TIME_FORMAT"), strtotime($startDate)).'">'.date(Config::get('Constant.DEFAULT_DATE_FORMAT'), strtotime($startDate)).'</span>';

        // Title
        $title = $value->varTitle;


        // Publish Action
        $publish_action = '';
        if ($value->chrAddStar != 'Y') {
            if ($value->chrDraft != 'D') {
                if ($permit['canblogcategorypublish']) {
                    if ($hasRecords == 0) {
                        if ($value->chrPublish == 'Y') {
                            $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/blog-category', 'data_alias'=>$value->id, 'title'=>trans("blogcategory::template.common.publishedRecord"), 'data_value'=>'Unpublish', 'checked'=>'checked'])->render();
                        } else {
                            $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/blog-category', 'data_alias'=>$value->id, 'title'=>trans("blogcategory::template.common.unpublishedRecord"), 'data_value'=>'Publish'])->render();
                        }
                    } else {
                        $publish_action .= '<a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-toggle="tooltip" title="This category is selected in ' . trans("blogcategory::template.sidebar.blogs") . ', so it can&#39;t be Publish/Unpublish."><i style="color:red" class="ri-toggle-line icon-publish fs-24"></i></a>';
                    }
                } else {
                    $publish_action = "-";
                }
            } else {
                if ($permit['canblogcategorypublish']) {
                    if ($hasRecords == 0) {
                        if ($value->chrPublish == 'Y') {
                            $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/blog-category', 'data_alias'=>$value->id, 'title'=>trans("blogcategory::template.common.publishedRecord"), 'data_value'=>'Unpublish', 'checked'=>'checked'])->render();
                        } else {
                            $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/blog-category', 'data_alias'=>$value->id, 'title'=>trans("blogcategory::template.common.unpublishedRecord"), 'data_value'=>'Publish'])->render();
                        }
                    } else {
                        $publish_action .= '<a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-toggle="tooltip" title="This category is selected in ' . trans("blogcategory::template.sidebar.blogs") . ', so it can&#39;t be Publish/Unpublish."><i style="color:red" class="ri-toggle-line icon-publish fs-24"></i></a>';
                    }
                } else {
                    $publish_action = "-";
                }
            }
        } else {
            if ($permit['canblogcategorypublish']) {
                $publish_action .= '<a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-toggle="tooltip" title="This blog category is in Approval request so can&#39;t be Publish/Unpublish."><i style="color:red" class="ri-toggle-line icon-publish fs-24"></i></a>';
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
        if ($permit['canblogcategoryedit']) {
            $Quickedit_startDate = date('Y-m-d H:i', strtotime($value->dtDateTime));
            $Quickedit_endDate = !empty($value->dtEndDateTime) ? date('Y-m-d H:i', strtotime($value->dtEndDateTime)) : 'No Expiry';

            if ($value->chrLock != 'Y') {
                if (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y') {
                    if ($permit['canblogcategoryreviewchanges']) {
                        $title_action .= "<a href=\"javascript:void(0);\" class=\"icon_title1 approval_active\" data-bs-toggle=\"tooltip\" data-bs-placement='bottom' title=\"Click here to see all approval records.\" style=\"margin-right: 5px;\" onclick=\"javascript:expandcollapsepanel(this ,'tasklisting" . $value->id . "', 'mainsingnimg" . $value->id . "'," . $value->id . ')" id="mainsingnimg' . $value->id . '"><i class="ri-stack-line fs-16"></i></a>';

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



        // All - Actions
        $logurl = url('powerpanel/log?id=' . $value->id . '&mid=' . Config::get('Constant.MODULE.ID'));
        $allActions = view('powerpanel.partials.all-actions',
                    [
                        'tabName'=>'Favorite',
                        'canedit'=> $permit['canblogcategoryedit'],
                        'candelete'=>$permit['canblogcategorydelete'],
                        'canloglist'=>$permit['canloglist'],
                        'value'=>$value,
                        'currentUserID' => $currentUserID,
                        'chrIsAdmin' => $this->currentUserRoleData->chrIsAdmin,
                        'module_name'=>'blogcategory',
                        'module_edit_url' => route('powerpanel.blog-category.edit', array('alias' => $value->id)),
                        'module_type'=>'category',
                        'viewlink' => isset($viewlink) ? $viewlink : "",
                        'linkviewLable' => isset($linkviewLable) ? $linkviewLable : "",
                        'hasRecords' => Blogs::getCountById($value->id),
                        'logurl' => $logurl
                    ])->render();

        if($permit['canblogcategoryedit'] || $permit['canblogcategorydelete']){
            $allActions = $allActions;
        } else {
            $allActions = "-";
        }

        $records = array(
            $checkbox,
            '<div class="pages_title_div_row">' . $Favorite . ' <span class="title-txt"> ' . $title . ' ' . $status . $statusdata . ' - ' . $sector . ' ' .$title_action. '</span></div>',
            $startDate,
            $publish_action,
            $allActions
        );
        return $records;
    }

    public function tableDataDraft($value, $permit, $currentUserID)
    {
        $hasRecords = Blogs::getCountById($value->id);

        // Checkbox
        if ($hasRecords > 0) {
            $checkbox = '<div class="checker"><a href="javascript:void(0);" data-toggle = "tooltip" data-placement = "right" data-toggle = "tooltip" title = "This category is selected in ' . trans("blogcategory::template.sidebar.blogs") . ', so it can&#39;t be deleted."><i style = "color:red" class = "ri-spam-line fs-16"></i></a></div>';
        }else{
            $checkbox = view('powerpanel.partials.checkbox', ['name'=>'delete', 'value'=>$value->id])->render();
        }

        // StartDate
        $startDate = $value->dtDateTime;
        $startDate = '<span align="left" data-bs-toggle="tooltip" data-bs-placement="bottom" title="'.date(Config::get("Constant.DEFAULT_DATE_FORMAT").' '.Config::get("Constant.DEFAULT_TIME_FORMAT"), strtotime($startDate)).'">'.date(Config::get('Constant.DEFAULT_DATE_FORMAT'), strtotime($startDate)).'</span>';


        // Title
        $title = $value->varTitle;


        // Publish Action
        $publish_action = '';
        if ($value->chrAddStar != 'Y') {
            if ($value->chrDraft != 'D') {
                if ($permit['canblogcategorypublish']) {
                    if ($hasRecords == 0) {
                        if ($value->chrPublish == 'Y') {
                            $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/blog-category', 'data_alias'=>$value->id, 'title'=>trans("blogcategory::template.common.publishedRecord"), 'data_value'=>'Unpublish', 'checked'=>'checked'])->render();
                        } else {
                            $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/blog-category', 'data_alias'=>$value->id, 'title'=>trans("blogcategory::template.common.unpublishedRecord"), 'data_value'=>'Publish'])->render();
                        }
                    } else {
                        $publish_action .= '<a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-toggle="tooltip" title="This category is selected in ' . trans("blogcategory::template.sidebar.blogs") . ', so it can&#39;t be Publish/Unpublish."><i style="color:red" class="ri-toggle-line icon-publish fs-24"></i></a>';
                    }
                } else {
                    $publish_action = "-";
                }
            } else {
                if ($permit['canblogcategorypublish']) {
                    if ($hasRecords == 0) {
                        if ($value->chrPublish == 'Y') {
                            $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/blog-category', 'data_alias'=>$value->id, 'title'=>trans("blogcategory::template.common.publishedRecord"), 'data_value'=>'Unpublish', 'checked'=>'checked'])->render();
                        } else {
                            $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/blog-category', 'data_alias'=>$value->id, 'title'=>trans("blogcategory::template.common.unpublishedRecord"), 'data_value'=>'Publish'])->render();
                        }
                    } else {
                        $publish_action .= '<a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-toggle="tooltip" title="This category is selected in ' . trans("blogcategory::template.sidebar.blogs") . ', so it can&#39;t be Publish/Unpublish."><i style="color:red" class="ri-toggle-line icon-publish fs-24"></i></a>';
                    }
                } else {
                    $publish_action = "-";
                }
            }
        } else {
            if ($permit['canblogcategorypublish']) {
                $publish_action .= '<a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-toggle="tooltip" title="This blog category is in Approval request so can&#39;t be Publish/Unpublish."><i style="color:red" class="ri-toggle-line icon-publish fs-24"></i></a>';
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
        if ($permit['canblogcategoryedit']) {
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



        // All - Actions
        $logurl = url('powerpanel/log?id=' . $value->id . '&mid=' . Config::get('Constant.MODULE.ID'));
        $allActions = view('powerpanel.partials.all-actions',
                    [
                        'tabName'=>'Draft',
                        'canedit'=> $permit['canblogcategoryedit'],
                        'candelete'=>$permit['canblogcategorydelete'],
                        'canloglist'=>$permit['canloglist'],
                        'value'=>$value,
                        'currentUserID' => $currentUserID,
                        'chrIsAdmin' => $this->currentUserRoleData->chrIsAdmin,
                        'module_name'=>'blogcategory',
                        'module_edit_url' => route('powerpanel.blog-category.edit', array('alias' => $value->id)),
                        'module_type'=>'category',
                        'viewlink' => isset($viewlink) ? $viewlink : "",
                        'linkviewLable' => isset($linkviewLable) ? $linkviewLable : "",
                        'hasRecords' => Blogs::getCountById($value->id),
                        'logurl' => $logurl
                    ])->render();

        if($permit['canblogcategoryedit'] || $permit['canblogcategorydelete']){
            $allActions = $allActions;
        } else {
            $allActions = "-";
        }


        $records = array(
            $checkbox,
            '<div class="pages_title_div_row"> <span class="title-txt">' . $title . ' ' . $status . $statusdata . ' - ' . $sector . ' ' .$title_action. '</span></div>',
            $startDate,
            $publish_action,
            $allActions
        );
        return $records;
    }

    public function tableDataTrash($value, $permit, $currentUserID)
    {
        $hasRecords = Blogs::getCountById($value->id);

        // Checkbox
        if ($hasRecords > 0) {
            $checkbox = '<div class="checker"><a href="javascript:void(0);" data-toggle = "tooltip" data-placement = "right" data-toggle = "tooltip" title = "This category is selected in ' . trans("blogcategory::template.sidebar.blogs") . ', so it can&#39;t be deleted."><i style = "color:red" class = "ri-spam-line fs-16"></i></a></div>';
        }else{
            $checkbox = view('powerpanel.partials.checkbox', ['name'=>'delete', 'value'=>$value->id])->render();
        }

        // StartDate
        $startDate = $value->dtDateTime;
        $startDate = '<span align="left" data-bs-toggle="tooltip" data-bs-placement="bottom" title="'.date(Config::get("Constant.DEFAULT_DATE_FORMAT").' '.Config::get("Constant.DEFAULT_TIME_FORMAT"), strtotime($startDate)).'">'.date(Config::get('Constant.DEFAULT_DATE_FORMAT'), strtotime($startDate)).'</span>';


        // Title
        $title = $value->varTitle;


        // Title Action
        $title_action = '';
        if ($permit['canblogcategoryedit']) {
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



        // All - Actions
        $logurl = url('powerpanel/log?id=' . $value->id . '&mid=' . Config::get('Constant.MODULE.ID'));
        $allActions = view('powerpanel.partials.all-actions',
                    [
                        'tabName'=>'Trash',
                        'canedit'=> $permit['canblogcategoryedit'],
                        'candelete'=>$permit['canblogcategorydelete'],
                        'canloglist'=>$permit['canloglist'],
                        'value'=>$value,
                        'currentUserID' => $currentUserID,
                        'chrIsAdmin' => $this->currentUserRoleData->chrIsAdmin,
                        'module_name'=>'blogcategory',
                        'module_edit_url' => route('powerpanel.blog-category.edit', array('alias' => $value->id)),
                        'module_type'=>'category',
                        'viewlink' => isset($viewlink) ? $viewlink : "",
                        'linkviewLable' => isset($linkviewLable) ? $linkviewLable : "",
                        'hasRecords' => Blogs::getCountById($value->id),
                        'logurl' => $logurl
                    ])->render();

        if($permit['canblogcategoryedit'] || $permit['canblogcategorydelete']){
            $allActions = $allActions;
        } else {
            $allActions = "-";
        }

        $records = array(
            $checkbox,
            '<div class="pages_title_div_row"> <span class="title-txt">'  . $title . ' ' . $status . $statusdata . ' - ' . $sector . ' ' .$title_action. '</span></div>',
            $startDate,
            $allActions
        );
        return $records;
    }


    public function DeleteRecord(Request $request)
    {
        $value = Request::input('value');
        $data['ids'] = Request::input('ids');
        if (File::exists(app_path() . '/Comments.php') != null || File::exists(base_path() . '/packages/Powerpanel/Workflow/src/Models/Comments.php') != null) {
            Comments::deleteComments($data['ids'], Config::get('Constant.MODULE.MODEL_NAME'));
        }
        $moduleHaveFields = ['chrMain', 'chrIsPreview'];
        $update = MyLibrary::deleteMultipleRecords($data, $moduleHaveFields, $value, 'Powerpanel\BlogCategory\Models\BlogCategory');
        foreach ($update as $ids) {
            $ignoreDeleteScope = true;
            $Deleted_Record = BlogCategory::getRecordById($ids, $ignoreDeleteScope);
            $Cnt_Letest = BlogCategory::getRecordCount_letest($Deleted_Record['fkMainRecord'], $Deleted_Record['id']);
            if ($Cnt_Letest <= 0) {
                $updateLetest = [
                    'chrAddStar' => 'N',
                ];
                $whereConditionsApprove = ['id' => $Deleted_Record['fkMainRecord']];
                CommonModel::updateRecords($whereConditionsApprove, $updateLetest, false, 'Powerpanel\BlogCategory\Models\BlogCategory');
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
                if ($value != "P" && $value != "F" && $value != "A" && $value != "D") {
                    Alias::where('id', $Deleted_Record['intAliasId'])
                        ->where('intFkModuleCode', Config::get('Constant.MODULE.ID'))
                        ->delete();
                }
            }
        }
        BlogCategory::ReorderAllrecords();
        self::flushCache();
        echo json_encode($update);
        exit;
    }


    public function reorder()
    {
        $order = Request::input('order');
        $exOrder = Request::input('exOrder');
        MyLibrary::swapOrder($order, $exOrder, 'Powerpanel\BlogCategory\Models\BlogCategory');
        BlogCategory::ReorderAllrecords();
        self::flushCache();
    }


    public static function swap_order_add($order = null)
    {
        $response = false;
        $isCustomizeModule = true;
        $moduleHaveFields = ['chrMain', 'chrIsPreview'];
        if ($order != null) {
        		BlogCategory::ReorderAllrecords();
            $response = MyLibrary::swapOrderAdd($order, $isCustomizeModule, $moduleHaveFields, 'Powerpanel\BlogCategory\Models\BlogCategory');
            self::flushCache();
        }
        return $response;
    }


    public static function swap_order_edit($order = null, $id = null)
    {
        $isCustomizeModule = true;
        $moduleHaveFields = ['chrMain', 'chrIsPreview'];
        MyLibrary::swapOrderEdit($order, $id, $isCustomizeModule, $moduleHaveFields, 'Powerpanel\BlogCategory\Models\BlogCategory');
        BlogCategory::ReorderAllrecords();
        self::flushCache();
    }


    public function publish(Request $request)
    {
        $requestArr = Request::all();
        // $request = (object) $requestArr;
        $val = Request::get('val');
        $alias = Request::input('alias');
        $update = MyLibrary::setPublishUnpublish($alias, $val, 'Powerpanel\BlogCategory\Models\BlogCategory');
        self::flushCache();
        echo json_encode($update);
        exit;
    }

    public function recordHistory($data = false)
    {
        $returnHtml = '';
        $startDate = date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($data->dtDateTime));
        $endDate = !empty($data->dtEndDateTime) ? date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($data->dtEndDateTime)) : 'No Expiry';

        if (isset($data->txtDescription) && $data->txtDescription != '') {

            $desc = FrontPageContent_Shield::renderBuilder($data->txtDescription);
            if (isset($desc['response']) && !empty($desc['response'])) {
                $desc = $desc['response'];
            } else {
                $desc = '---';
            }

        } else {
            $desc = '---';
        }

        $returnHtml .= '<table class="new_table_desing table table-striped table-bordered table-hover">
				<thead>
                    <tr>
                        <th align="center">' . trans("blogcategory::template.common.title") . '</th>
                        <th align="center">Description</th>
                        <th align="center">Start Date</th>
                        <th align="center">End Date</th>
                        <th align="center">Meta Title</th>
                        <th align="center">Meta Description</th>
                        <th align="center">Display Order</th>
                        <th align="center">' . trans("blogcategory::template.common.publish") . '</th>
                    </tr>
				</thead>
				<tbody>
                    <tr>
                        <td align="center">' . stripslashes($data->varTitle) . '</td>
                        <td align="center">' . $desc . '</td>
                        <td align="center">' . $startDate . '</td>
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

    public function newrecordHistory($data = false, $newdata = false)
    {
        $returnHtml = '';
        if ($data->varTitle != $newdata->varTitle) {
            $titlecolor = 'style="background-color:#f5efb7"';
        } else {
            $titlecolor = '';
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
        if ($data->intDisplayOrder != $newdata->intDisplayOrder) {
            $ordercolor = 'style="background-color:#f5efb7"';
        } else {
            $ordercolor = '';
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
        if ($data->txtDescription != $newdata->txtDescription) {
            $desccolor = 'style="background-color:#f5efb7"';
        } else {
            $desccolor = '';
        }

        if (isset($newdata->txtDescription) && $newdata->txtDescription != '') {

            $desc = FrontPageContent_Shield::renderBuilder($newdata->txtDescription);
            if (isset($desc['response']) && !empty($desc['response'])) {
                $desc = $desc['response'];
            } else {
                $desc = '---';
            }

        } else {
            $desc = '---';
        }

        $startDate = date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($newdata->dtDateTime));
        $endDate = !empty($newdata->dtEndDateTime) ? date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($newdata->dtEndDateTime)) : 'No Expiry';
        $returnHtml .= '<table class="new_table_desing table table-striped table-bordered table-hover">
				<thead>
                    <tr>
                        <th align="center">' . trans("blogcategory::template.common.title") . '</th>
                        <th align="center">Description</th>
                        <th align="center">Start Date</th>
                        <th align="center">End Date</th>
                        <th align="center">Meta Title</th>
                        <th align="center">Meta Description</th>
                        <th align="center">Display Order</th>
                        <th align="center">' . trans("blogcategory::template.common.publish") . '</th>
                    </tr>
				</thead>
				<tbody>
                    <tr>
                        <td align="center" ' . $titlecolor . '>' . stripslashes($newdata->varTitle) . '</td>
                        <td align="center" ' . $desccolor . '>' . $desc . '</td>
                        <td align="center" ' . $DateTimecolor . '>' . $startDate . '</td>
                        <td align="center" ' . $EndDateTimecolor . '>' . $endDate . '</td>
                        <td align="center" ' . $metatitlecolor . '>' . stripslashes($newdata->varMetaTitle) . '</td>
                        <td align="center" ' . $metadesccolor . '>' . stripslashes($newdata->varMetaDescription) . '</td>
                        <td align="center" ' . $ordercolor . '>' . $newdata->intDisplayOrder . '</td>
                        <td align="center" ' . $Publishcolor . '>' . $newdata->chrPublish . '</td>
                    </tr>
				</tbody>
				</table>';
        return $returnHtml;
    }


    public function addPreview(Guard $auth)
    {
        $data = Request::input();
        $rules = array(
            'title' => 'required|max:160|handle_xss|no_url',
            'varMetaTitle' => 'required|max:500|handle_xss|no_url',
            'varMetaDescription' => 'required|max:500|handle_xss|no_url',
            'alias' => 'required',
            'order' => 'required|greater_than_zero|handle_xss|no_url',
        );
        $actionMessage = trans('blogcategory::template.common.oppsSomethingWrong');
        $messsages = array();
        $validator = Validator::make($data, $rules, $messsages);
        if (Config::get('Constant.DEFAULT_VISUAL') == 'Y') {
            if ($data['section'] != '[]') {
                $vsection = $data['section'];
            } else {
                $vsection = '';
            }
        } else {
            $vsection = $data['description'];
        }
        $blogCategoryArr = [];
        $blogCategoryArr['varTitle'] = stripslashes(trim($data['title']));
        $blogCategoryArr['dtDateTime'] = !empty($data['start_date_time']) ? date('Y-m-d H:i:s', strtotime($data['start_date_time'])) : date('Y-m-d H:i:s');
        $blogCategoryArr['dtEndDateTime'] = !empty($data['end_date_time']) ? date('Y-m-d H:i:s', strtotime($data['end_date_time'])) : null;
        $blogCategoryArr['txtDescription'] = $vsection;
        $blogCategoryArr['varMetaTitle'] = stripslashes(trim($data['varMetaTitle']));
        $blogCategoryArr['varMetaDescription'] = stripslashes(trim($data['varMetaDescription']));
        $blogCategoryArr['varTags'] = trim($data['tags']);
        $blogCategoryArr['chrPublish'] = isset($data['chrMenuDisplay']) ? $data['chrMenuDisplay'] : 'Y';
        $blogCategoryArr['chrIsPreview'] = 'Y';
        $id = $data['previewId'];
        if (is_numeric($id)) { #Edit post Handler=======
        if ($data['oldAlias'] != $data['alias']) {
            Alias::updateAlias($data['oldAlias'], $data['alias']);
        }
            $blogCategory = BlogCategory::getRecordForLogById($id);
            $whereConditions = ['id' => $blogCategory->id];
            $update = CommonModel::updateRecords($whereConditions, $blogCategoryArr, false, 'Powerpanel\BlogCategory\Models\BlogCategory');
            if ($update) {
                if (!empty($id)) {
                    $logArr = MyLibrary::logData($blogCategory->id);
                    if (Auth::user()->can('log-advanced')) {
                        $newBlogCategoryObj = BlogCategory::getRecordForLogById($blogCategory->id);
                        $oldRec = $this->recordHistory($blogCategory);
                        $newRec = $this->recordHistory($newBlogCategoryObj);
                        $logArr['old_val'] = $oldRec;
                        $logArr['new_val'] = $newRec;
                    }
                    $logArr['varTitle'] = stripslashes(trim($data['title']));
                    Log::recordLog($logArr);
                    if (Auth::user()->can('recent-updates-list')) {
                        if (!isset($newBlogCategoryObj)) {
                            $newBlogCategoryObj = BlogCategory::getRecordForLogById($blogCategory->id);
                        }
                        $notificationArr = MyLibrary::notificationData($blogCategory->id, $newBlogCategoryObj);
                        RecentUpdates::setNotification($notificationArr);
                    }
                    self::flushCache();
                    $actionMessage = trans('blogcategory::template.blogCategoryModule.updateMessage');
                }
            }
        } else { #Add post Handler=======
        $blogCategoryArr['intAliasId'] = MyLibrary::insertAlias($data['alias'], false, 'Y');
            $id = CommonModel::addRecord($blogCategoryArr, 'Powerpanel\BlogCategory\Models\BlogCategory');
        }
        return json_encode(array('status' => $id, 'alias' => $data['alias'], 'message' => trans('template.pageModule.pageUpdate')));
    }

    public static function flushCache()
    {
        Cache::tags('BlogCategory')->flush();
    }

    public function getChildData()
    {
        $childHtml = "";
        $BlogCategory_childData = "";
        $BlogCategory_childData = BlogCategory::getChildGrid();


        $childHtml .= '<div class="producttbl" style="">';
        $childHtml .= '<table class="table table-hover align-middle table-nowrap hide-mobile" id="email_log_datatable_ajax"><thead class="table-light">
                <tr role="row">
                <th class="text-left"></th>
                <th class="text-left">Title</th>
                <th class="text-center">Date Submitted</th>
                <th class="text-center">User</th>
                <th class="text-center">Preview</th>
                <th class="text-center">Edit</th>
                <th class="text-center">Status</th>';
        $childHtml .= '</tr></thead><tbody>';

        if (count($BlogCategory_childData) > 0) {
            foreach ($BlogCategory_childData as $child_row) {
                $checkbox = view('powerpanel.partials.checkbox', ['name'=>'delete', 'value'=>$child_row->id])->render();
                $parentAlias = $child_row->alias->varAlias;
                $url = url('/previewpage?url=' . MyLibrary::getFrontUri('blog-category')['uri'] . '/' . $parentAlias . '/' . $child_row->id . '/preview');

                $childHtml .= '<tr role="row">';
                    if ($child_row->chrApproved == 'N') {
                        $childHtml .= "<td><span class='mob_show_title'>&nbsp</span>".$checkbox."</td>";
                    } else {
                        $childHtml .= "<td><span class='mob_show_title'>&nbsp</span><div class=\"checker\"><a href=\"javascript:void(0);\" data-bs-toggle='tooltip' data-bs-placement='bottom' title=\"This is approved record, so can't be deleted.\"><i style=\"color:red\" class=\"ri-spam-line\"></i></a></div></td>";
                    }

                    $childHtml .= '<td class="text-left"><span class="mob_show_title">Title: </span>' . $child_row->varTitle . '</td>';

                    $childHtml .= '<td class="text-center"><span class="mob_show_title">Date Submitted: </span><span align="left" data-bs-toggle="tooltip" data-bs-placement="bottom" title="'.date(Config::get("Constant.DEFAULT_DATE_FORMAT").' '.Config::get("Constant.DEFAULT_TIME_FORMAT"), strtotime($child_row->created_at)).'">' . date(Config::get("Constant.DEFAULT_DATE_FORMAT"), strtotime($child_row->created_at)) . '</span></td>';

                    $childHtml .= '<td class="text-center"><span class="mob_show_title">User: </span>' . CommonModel::getUserName($child_row->UserID) . '</td>';

                    $childHtml .= '<td class="text-center"><span class="mob_show_title">Preview: </span><a class="icon_round me-2" href=' . $url . " target='_blank'><i class=\"ri-computer-line\"></i></a></td>";

                    if ($child_row->chrApproved == 'N') {
                        $childHtml .= "<td class=\"text-center\"><span class='mob_show_title'>Edit: </span><a class='icon_round me-2' data-bs-toggle='tooltip' data-bs-placement='bottom' title='" . trans('blogcategory::template.common.edit') . "' href='" . route('powerpanel.blog-category.edit', array('alias' => $child_row->id)) . "?tab=A'><i class='ri-pencil-line'></i></a></td>";
                    } else {
                        $childHtml .= '<td class="text-center"><span class="mob_show_title">Edit: </span>-</td>';
                    }

                    if ($child_row->chrApproved == 'N') {
                        $childHtml .= "<td class=\"text-center\"><span class='mob_show_title'>Status: </span><a class=\"approve_icon_btn me-2\" data-bs-toggle='tooltip' data-bs-placement='bottom' title='" . trans('blogcategory::template.common.comments') . "' href=\"javascript:void(0);\" onclick=\"loadModelpopup('" . $child_row->id . "','" . $child_row->UserID . "','" . Config::get('Constant.MODULE.MODEL_NAME') . "','" . $child_row->fkMainRecord . "')\"><i class=\"ri-chat-1-line\"></i> </a><a class=\"approve_icon_btn me-2\" onclick=\"update_mainrecord('" . $child_row->id . "','" . $child_row->fkMainRecord . "','" . $child_row->UserID . "','A');\" data-bs-toggle='tooltip' data-bs-placement='bottom' title='" . trans('blogcategory::template.common.clickapprove') . "'  href=\"javascript:void(0);\"><i class=\"ri-checkbox-line
                        \"></i> </a></td>";
                    } else {
                        $childHtml .= '<td class="text-center"><span class="mob_show_title">Status: </span><span class="mob_show_overflow"><i class="ri-checkbox-line " style="font-size:30px;"></i><span style="display:block"><strong>Approved On: </strong><span align="left" data-bs-toggle="tooltip" data-bs-placement="bottom" title="'.date(Config::get("Constant.DEFAULT_DATE_FORMAT").' '.Config::get("Constant.DEFAULT_TIME_FORMAT"), strtotime($child_row->dtApprovedDateTime)).'">' . date(Config::get("Constant.DEFAULT_DATE_FORMAT"), strtotime($child_row->dtApprovedDateTime)) . '</span></span><span style="display:block"><strong>Approved By: </strong>' . CommonModel::getUserName($child_row->intApprovedBy) . '</span></span></td>';
                    }
                $childHtml .= '</tr>';
            }
        } else {
            $childHtml .= "<tr><td class='text-center' colspan='7'>No Records</td></tr>";
        }
        $childHtml .= '</tr></td></tr>';
        $childHtml .= '</tr></tbody></table>';
        echo $childHtml;
        exit;
    }

    public function getChildData_rollback()
    {
        $child_rollbackHtml = "";
        $Cmspage_rollbackchildData = "";
        $Cmspage_rollbackchildData = BlogCategory::getChildrollbackGrid();
        $child_rollbackHtml .= "<div class=\"producttbl producttb2\" style=\"\">";
        $child_rollbackHtml .= "<table class=\"new_table_desing table table-striped table-bordered table-hover table-checkable dataTable\" id=\"email_log_datatable_ajax\">
                                <tr role=\"row\">
                                    <th class=\"text-center\">Title</th>
                                    <th class=\"text-center\">Date</th>
                                    <th class=\"text-center\">User</th>
                                    <th class=\"text-center\">Preview</th>
                                    <th class=\"text-center\">Status</th>";
        $child_rollbackHtml .= "</tr>";
        if (count($Cmspage_rollbackchildData) > 0) {
            foreach ($Cmspage_rollbackchildData as $child_rollbacrow) {
                $child_rollbackHtml .= "<tr role=\"row\">";
                $child_rollbackHtml .= '<td class="text-center"><span class="mob_show_title">Title: </span>' . $child_rollbacrow->varTitle . '</td>';
                $child_rollbackHtml .= "<td class=\"text-center\"><span class='mob_show_title'>Date: </span>" . date('M d Y h:i A', strtotime($child_rollbacrow->created_at)) . "</td>";
                $child_rollbackHtml .= "<td class=\"text-center\"><span class='mob_show_title'>User: </span>" . CommonModel::getUserName($child_rollbacrow->UserID) . "</td>";
                $previewlink = url('/previewpage?url=' . MyLibrary::getFrontUri('blog-category')['uri'] . '/' . $child_rollbacrow->id . '/preview');
                $child_rollbackHtml .= "<td class=\"text-center\"><span class='mob_show_title'>Preview: </span><a class='icon_round' href=" . $previewlink . " target='_blank'><i class=\"ri-computer-line\"></i></a></td>";
                if ($child_rollbacrow->chrApproved == 'Y') {
                    $child_rollbackHtml .= "<td class=\"text-center\"><span class='mob_show_title'>Status: </span><i class=\"ri-checkbox-circle-line\" style=\"color: #1080F2;font-size:30px;\"></i></td>";
                } else {
                    // $child_rollbackHtml .= "<td class=\"text-center\"><span class='mob_show_title'>Status: </span><a onclick=\"update_mainrecord('" . $child_rollbacrow->id . "','" . $child_rollbacrow->fkMainRecord . "','" . $child_rollbacrow->UserID . "','R');\"  class=\"approve_icon_btn\">
                    // <i class=\"ri-history-fill\"></i>  <span>RollBack</span>
                    // </a></td>";
                    $child_rollbackHtml .= "<td class=\"text-center\"><span class=\"glyphicon glyphicon-minus\"></span></td>";
                }
                $child_rollbackHtml .= "</tr>";
            }
        } else {
            $child_rollbackHtml .= "<tr><td colspan='7'>No Records</td></tr>";
        }
        echo $child_rollbackHtml;
        exit;
    }

    public function ApprovedData_Listing(Request $request)
    {
        $requestArr = Request::all();
        $request = (object) $requestArr;
        $id = Request::post('id');
        $approvalid = Request::post('id');
        $main_id = Request::post('main_id');
        $flag = Request::post('flag');
        $approvalData = BlogCategory::getOrderOfApproval($id);
        $message = BlogCategory::approved_data_Listing($request);
        if (!empty($approvalData)) {
            self::swap_order_edit($approvalData->intDisplayOrder, $main_id);
        }
        $newCmsPageObj = BlogCategory::getRecordForLogById($main_id);
        $approval_obj = BlogCategory::getRecordForLogById($approvalid);
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
        $careers = BlogCategory::getRecordForLogById($id);
        if (method_exists($this->MyLibrary, 'userNotificationData')) {
            $userNotificationArr = MyLibrary::userNotificationData(Config::get('Constant.MODULE.ID'));
            $userNotificationArr['fkRecordId'] = $id;
            $userNotificationArr['txtNotification'] = 'Your request has been approved by ' . ucfirst(auth()->user()->name) . ' (' . ucfirst(Config::get('Constant.MODULE.NAME')) . ')';
            $userNotificationArr['fkIntUserId'] = Auth::user()->id;
            $userNotificationArr['chrNotificationType'] = 'A';
            $userNotificationArr['intOnlyForUserId'] = $careers->UserID;
            UserNotification::addRecord($userNotificationArr);
        }
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
                    $Comments .= '<div class="d-flex border border-dashed p-2 rounded position-relative"><div class="flex-grow-1 ms-2"><h6>' . nl2br($row_data->varCmsPageComments) . '</h6><small class = "date">' . CommonModel::getUserName($row_data->intCommentBy) . ' ' . date('M d Y h:i A', strtotime($row_data->created_at)) . '</small></div></div>';
                    $UserComments = Comments::get_usercomments($row_data->id);
                    foreach ($UserComments as $row_comments) {
                        $Comments .= '<div class="d-flex border border-dashed p-2 rounded position-relative user-comments"><div class="flex-grow-1 ms-2"><h6>' . nl2br($row_comments->varCmsPageComments) . '</h6><small class = "date">' . CommonModel::getUserName($row_comments->UserID) . ' ' . date('M d Y h:i A', strtotime($row_comments->created_at)) . '</small></div></div>';
                    }
                }
            }
        } else {
            $Comments .= '<div class="d-flex border border-dashed p-2 rounded position-relative"><div class="flex-grow-1 ms-2"><h6>No Comments yet.</small></h6></div>';
        }
        echo $Comments;
        exit;
    }

    public function get_builder_list()
    {
        $records = BlogCategory::getAllCategory();
        $opt = '<option value="">Category</option>';
        foreach ($records as $record) {
            $opt .= '<option value="' . $record->id . '">' . $record->varTitle . '</option>';
        }
        return $opt;
    }

    public function getAllCategory()
    {
        $records = BlogCategory::getAllCategory();
        $opt = '<option value="">Select Category</option>';
        foreach ($records as $record) {
            $opt .= '<option value="' . $record->id . '">' . $record->varTitle . '</option>';
        }
        return $opt;
    }

    public function addCatAjax()
    {
        $data = Request::input();
        return AddCategoryAjax::Add($data, 'BlogCategory');
    }

    public function rollBackRecord(Request $request)
    {

        $message = 'Oops! Something went wrong';
        $requestArr = Request::all();
        $request = (object) $requestArr;

        $previousRecord = BlogCategory::getPreviousRecordByMainId($request->id);
        if (!empty($previousRecord)) {

            $main_id = $previousRecord->fkMainRecord;
            $request->id = $previousRecord->id;
            $request->main_id = $main_id;

            $message = BlogCategory::approved_data_Listing($request);

            $newBlogObj = BlogCategory::getRecordForLogById($main_id);
            $restoredata = Config::get('Constant.ROLLBACK_RECORD');

            /* notification for user to record approved */
            $blogs = BlogCategory::getRecordForLogById($previousRecord->id);
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
