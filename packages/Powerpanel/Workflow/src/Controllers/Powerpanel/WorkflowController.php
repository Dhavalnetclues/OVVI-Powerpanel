<?php

namespace Powerpanel\Workflow\Controllers\Powerpanel;

//use App\Http\Controllers\Powerpanel\RoleController;
use App\CommonModel;
use App\Notification;
use App\UserNotification;
use App\Helpers\MyLibrary;
use App\Http\Controllers\PowerpanelController;
use App\Log;
use App\ModuleGroup;
use App\Modules;
use App\RecentUpdates;
use App\User;
use Auth;
use Cache;
use Config;
use Illuminate\Support\Facades\Redirect;
use Powerpanel\RoleManager\Controllers\Powerpanel\RoleController;
use Powerpanel\RoleManager\Models\Permission;
use Powerpanel\RoleManager\Models\Permission_role;
use Powerpanel\RoleManager\Models\Role;
use Powerpanel\RoleManager\Models\Role_user;
use Powerpanel\Workflow\Models\Comments;
use Powerpanel\Workflow\Models\Workflow;
use Request;
use Validator;

class WorkflowController extends PowerpanelController
{

    public function __construct()
    {
        parent::__construct();
        if (isset($_COOKIE['locale'])) {
            app()->setLocale($_COOKIE['locale']);
        }
        $this->MyLibrary = new MyLibrary();
    }

    /**
     * This method handels load workflow grid
     * @return  View
     * @since   2017-07-20
     * @author  NetQuick
     */
    public function index()
    {
        $userIsAdmin = false;
        if (!empty($this->currentUserRoleData)) {
            if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                $userIsAdmin = true;
            }
        } else {
            $userIsAdmin = true;
        }
        // $module = Role::getList();
        $nonAdminRoles = Role::getNonAdmins();
        $moduleCategory = RoleController::groups();
        $iTotalRecords = CommonModel::getRecordCount(false, true, false, 'Powerpanel\Workflow\Models\Workflow');
        $this->breadcrumb['title'] = trans('workflow::template.workflowModule.manageWorkflow');
        return view('workflow::powerpanel.index', ['userIsAdmin' => $userIsAdmin, 'iTotalRecords' => $iTotalRecords, 'breadcrumb' => $this->breadcrumb, 'modules' => $nonAdminRoles, 'categories' => $moduleCategory]);
    }

    /**
     * This method handels list of workflow with filters
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
        $filterArr['searchFilter'] = !empty(Request::input('searchValue')) ? Request::input('searchValue') : '';
        $filterArr['statusFilter'] = !empty(Request::input('statusValue')) ? Request::input('statusValue') : '';
        $filterArr['roleFilter'] = !empty(Request::input('customRoleName')) ? Request::input('customRoleName') : '';
        $filterArr['categorieFilter'] = !empty(Request::input('customCategorieName')) ? Request::input('customCategorieName') : '';
        $filterArr['dateFilter'] = !empty(Request::input('dateValue')) ? Request::input('dateValue') : '';
        $filterArr['iDisplayLength'] = intval(Request::input('length'));
        $filterArr['iDisplayStart'] = intval(Request::input('start'));
        $sEcho = intval(Request::input('draw'));

        $arrResults = Workflow::getRecordList($filterArr);
        // $iTotalRecords = Workflow::getRecordCount($filterArr, true, false, false, $this->currentUserRoleSector);
        $iTotalRecords = Role::getRecordListCount($filterArr, true, false, false, $this->currentUserRoleSector);

        $end = $filterArr['iDisplayStart'] + $filterArr['iDisplayLength'];
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;
        if (count($arrResults) > 0 && !empty($arrResults)) {
            foreach ($arrResults as $key => $value) {
                $value->role = Role::getRecordById($value->varUserRoles)->display_name;
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

    public function getChildData()
    {
        $childHtml = '';
        $workflow_childData = '';
        $workflow_childData = workflow::getChildGrid();
        $childHtml .= '<div class="producttbl" style="">';
        $childHtml .= '<table class="new_table_desing table table-hover table-checkable dataTable" id="email_log_datatable_ajax">
				<tr role="row" class="table-light">
					<th></th>
                    <th>Role</th>
					<th>Category</th>
					<th>Module Name</th>
					<th>Approval</th>
					<th>Date / Time</th>
					<th> ' . trans('workflow::template.common.actions') . '</th>';
        $childHtml .= '</tr>';
        if (count($workflow_childData) > 0) {
            foreach ($workflow_childData as $child_row) {
                $role = Role::getRecordById($child_row->varUserRoles)->display_name;
                $moduledata = Modules::getModuleById($child_row->intModuleId);
                if (!empty($moduledata)) {
                    $title = $moduledata->varTitle;
                } else {
                    $title = '---';
                }
                if ($child_row->chrNeedAddPermission == 'N' && $child_row->charNeedApproval == 'N') {
                    $approvaldata = 'Direct Approval';
                } else if ($child_row->chrNeedAddPermission == 'Y' && $child_row->charNeedApproval == 'Y') {
                    $approvaldata = 'Approval Needed';
                } else {
                    $approvaldata = '----';
                }
                $actions = '<div class="dropdown"><a href="javascript:void(0)" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="ri-more-fill"></i></a>
                <ul class="dropdown-menu">';
                if (Auth::user()->can('workflow-delete')) {
                    $actions .= '<li><a href="javascript:void(0);" class="dropdown-item delete" title="' . trans("workflow::template.common.delete") . '" data-controller="workflow" data-alias = "' . $child_row->id . '"><i class="ri-delete-bin-line"></i> Delete</a></li>';
                }
                $logurl = url('powerpanel/log?id=' . $child_row->id . '&mid=' . Config::get('Constant.MODULE.ID'));
                if (Auth::user()->can('log-list')) {
                    $actions .= "<li><a title=\"Log History\" class='dropdown-item log-grid' href=\"$logurl\"><i class=\"ri-time-line\"></i> Log History</a></li>";
                }
                $actions .= '</ul></div>';


                $childHtml .= '<tr role="row">';
                $childHtml .= "<td><span class='mob_show_title'>&nbsp</span><input type=\"checkbox\" name=\"delete\" class=\"form-check-input chkDelete\" value=\"$child_row->id\"></td>";
                $childHtml .= '<td><span class="mob_show_title">Role: </span>' . $role . '</td>';
                $childHtml .= '<td><span class="mob_show_title">Category: </span>' . ucfirst(str_replace('-', ' ', $child_row->varActivity)) . '</td>';
                $childHtml .= '<td><span class="mob_show_title">Module Name: </span>' . $title . '</td>';
                $childHtml .= '<td><span class="mob_show_title">Approval: </span>' . $approvaldata . '</td>';
                $childHtml .= '<td><span class="mob_show_title">Date / Time: </span>' . date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($child_row->created_at)) . '</td>';
                $childHtml .= '<td class="last_td_action"><span class="mob_show_title">' . trans('workflow::template.common.actions') . ': </span>' . $actions . '</td>';
                $childHtml .= '</tr>';
            }
        } else {
            $childHtml .= "<tr><td colspan='7'>No Records</td></tr>";
        }
        $childHtml .= '</tr></td></tr>';
        $childHtml .= '</tr></table>';
        echo $childHtml;
        exit;
    }

    public function tableData($value)
    {
        $update = "<a href='javascript:void(0);' class=\"icon_title1\" title=\"Click here to see all records.\" style=\"margin-right: 5px;\" onclick=\"javascript:expandcollapsepanel(this ,'tasklisting" . $value->id . "', 'mainsingnimg" . $value->id . "'," . $value->id . ')"><i id="mainsingnimg' . $value->id . '" class="ri-add-box-line"></i></a>';

        $records = array(
            '-',
            $value->role,
            $update,
        );
        return $records;
    }

    public function edit($id = false)
    {
        $nonAdminRoles = Role::getNonAdmins();
        $moduleCategory = RoleController::groups();
        $checkFromRoles = array_column($nonAdminRoles->toArray(), 'id');
        $approvalWorkFlows = Workflow::getApprovalWorkFlows($checkFromRoles)->toArray();
        $approvalWorkFlows = array_column($approvalWorkFlows, 'intCategoryId');
        $adminUsers = [];
        if (!is_numeric($id)) {
            $this->breadcrumb['title'] = trans('workflow::template.workflowModule.addWorkflow');
            $this->breadcrumb['module'] = trans('workflow::template.workflowModule.manageWorkflow');
            $this->breadcrumb['url'] = 'powerpanel/workflow';
            $this->breadcrumb['inner_title'] = '';
            $breadcrumb = $this->breadcrumb;
            $data = compact('breadcrumb', 'adminUsers', 'moduleCategory', 'nonAdminRoles', 'approvalWorkFlows');

        } else {
            $workflow = Workflow::getRecordById($id);
            if (count($workflow) == 0) {
                return redirect()->route('powerpanel.workflow.add');
            }
            $this->breadcrumb['title'] = trans('workflow::template.workflowModule.editWorkflow');
            $this->breadcrumb['module'] = trans('workflow::template.workflowModule.manageWorkflow');
            $this->breadcrumb['url'] = 'powerpanel/workflow';
            $this->breadcrumb['inner_title'] = $workflow->varTitle;
            $breadcrumb = $this->breadcrumb;
            if ((int) $workflow->fkMainRecord !== 0) {
                $workflowHighLight = Workflow::getRecordById($workflow->fkMainRecord);
            }
            $data = compact('workflow', 'workflowHighLight', 'breadcrumb', 'adminUsers', 'moduleCategory', 'nonAdminRoles', 'approvalWorkFlows');

        }
        return view('workflow::powerpanel.actions', $data);
    }

    public function handlePost(Request $request)
    {
        $actionMessage = trans('workflow::template.common.oppsSomethingWrong');
        $postArr = Request::post();
        $response = false;
        $postArr['workflow_type'] = 'approvals';
        $postArr['chrMenuDisplay'] = "Y";
        if ($postArr['workflow_type'] == 'approvals') {
            $rules = array(
                //'category_id' => 'required',
                'user_roles' => 'required',
            );
            $messsages = array(
                //'category_id.required' => 'Category is required',
                'user_roles.required' => 'Role is required',
            );
            $needApproval = 'N';
            if (isset($postArr['catwise_modules']) && !empty($postArr['catwise_modules'])) {
                $needApproval = 'Y';
                $rules['user'] = 'required';
                $messsages['user.required'] = 'User is required';
            }
            $validator = Validator::make($postArr, $rules, $messsages);
            if ($validator->passes()) {
                $data = array();
                $directapprovalArray = array();
                $data['varType'] = $postArr['workflow_type'];
                $data['varActivity'] = 'CMS'; //ModuleGroup::getGroupById($postArr['category_id'])->varTitle;
                $data['varAction'] = $postArr['action'];
                $data['intCategoryId'] = 0;
                $data['varUserRoles'] = $postArr['user_roles'];
                $data['chrNeedAddPermission'] = 'N';
                $data['charNeedApproval'] = 'N';
                $directapprovalArray = $data;
                if (isset($postArr['catwise_modules']) && !empty($postArr['catwise_modules'])) {
                    $data['chrNeedAddPermission'] = 'Y';
                    $data['charNeedApproval'] = 'Y';
                    $postArr['user'][] = 1;
                    $data['varUserId'] = implode(',', $postArr['user']);
                } else {
                    $data['varUserId'] = null;
                }
                if (isset($postArr['directApproved']) && !empty($postArr['directApproved'])) {
                    $directapprovalArray['chrNeedAddPermission'] = 'N';
                    $directapprovalArray['charNeedApproval'] = 'N';
                    $directapprovalArray['varUserId'] = null;
                }
                $data['chrPublish'] = $postArr['chrMenuDisplay'];
                $data['chrDelete'] = 'N';
                $id = Request::segment(3);
                if (is_numeric($id)) {
                    return redirect(url('powerpanel/workflow/add'));
                } else {
                    #Add post Handler=======
                    if (isset($postArr['catwise_modules']) && !empty($postArr['catwise_modules'])) {
                        foreach ($postArr['catwise_modules'] as $moduleid) {
                            $exists = Workflow::checkExists(0, $postArr['user_roles'], $moduleid);
                            if (empty($exists)) {
                                $data['intModuleId'] = $moduleid;
                                $data['created_at'] = date('Y-m-d H:i:s');
                                $data['updated_at'] = date('Y-m-d H:i:s');
                                $added = CommonModel::addRecord($data, 'Powerpanel\Workflow\Models\Workflow');
                                if (!empty($added)) {
                                    $id = $added;
                                    $newWorkflowObj = Workflow::getRecordById($id);
                                    // $this->addHiddenRole($data, $newWorkflowObj);
                                    $logArr = MyLibrary::logData($id);
                                    $logArr['varTitle'] = 'Workflow';
                                    Log::recordLog($logArr);
                                    if (Auth::user()->can('recent-updates-list')) {
                                        $notificationArr = MyLibrary::notificationData($id, $newWorkflowObj);
                                        RecentUpdates::setNotification($notificationArr);
                                    }
                                    self::flushCache();
                                    $actionMessage = trans('workflow::template.workflowModule.addMessage');
                                }
                            } else {
                                $actionMessage = 'Workflow with same role and category already exists!';
                                return Redirect::back()->with('message', $actionMessage);
                            }
                        }
                    }
                    if (isset($postArr['directApproved']) && !empty($postArr['directApproved'])) {
                        foreach ($postArr['directApproved'] as $moduleid) {
                            $exists = Workflow::checkExists(0, $postArr['user_roles'], $moduleid);
                            if (empty($exists)) {
                                $directapprovalArray['intModuleId'] = $moduleid;
                                $directapprovalArray['created_at'] = date('Y-m-d H:i:s');
                                $directapprovalArray['updated_at'] = date('Y-m-d H:i:s');
                                $added = CommonModel::addRecord($directapprovalArray, 'Powerpanel\Workflow\Models\Workflow');
                                if (!empty($added)) {
                                    $id = $added;
                                    $newWorkflowObj = Workflow::getRecordById($id);
                                    //$this->addHiddenRole($directapprovalArray, $newWorkflowObj);
                                    $logArr = MyLibrary::logData($id);
                                    $logArr['varTitle'] = 'Workflow';
                                    Log::recordLog($logArr);
                                    if (Auth::user()->can('recent-updates-list')) {
                                        $notificationArr = MyLibrary::notificationData($id, $newWorkflowObj);
                                        RecentUpdates::setNotification($notificationArr);
                                    }
                                    self::flushCache();
                                    $actionMessage = trans('workflow::template.workflowModule.addMessage');
                                }
                            } else {
                                $actionMessage = 'Workflow with same role and category already exists!';
                                return Redirect::back()->with('message', $actionMessage);
                            }
                        }
                    }
                }
                if (!empty($postArr['saveandexit']) && $postArr['saveandexit'] == 'saveandexit') {
                    return redirect()->route('powerpanel.workflow.index')->with('message', $actionMessage);
                } else {
                    return redirect()->route('powerpanel.workflow.edit', $id)->with('message', $actionMessage);
                }

            } else {
                return Redirect::back()->withErrors($validator)->withInput();
            }
        } else {
            $rules = array(
                'title' => 'required',
                'frequancy_positive' => 'required',
                'frequancy_neagtive' => 'required',
                'yes_content' => 'required',
                'no_content' => 'required',
            );
            if ($postArr['workflow_type'] == 'leads') {
                $rules['after'] = 'required';
                $rules['after_content'] = 'required';
            }
            $messsages = array(
                'title.required' => 'Title is required',
                'activity.required' => 'Activity is required',
                'activity.unique' => 'Workflow with this activity is already exists',
                'action.required' => 'Action is required',
                'after.required' => 'After is required',
                'frequancy_positive.required' => 'Send e-mail is required',
                'frequancy_neagtive.required' => 'Send reminder e-mail frequancy is required',
                'after_content.required' => 'Email content is required',
                'yes_content.required' => 'Email content is required',
                'no_content.required' => 'Email content is required',
            );
            $validator = Validator::make($postArr, $rules, $messsages);
            if ($validator->passes()) {
                $data = array();
                $data['varType'] = $postArr['workflow_type'];
                $data['varActivity'] = $postArr['activity'];
                $data['varAction'] = $postArr['action'];
                $data['varFrequancyPositive'] = $postArr['frequancy_positive'];
                $data['varFrequancyNegative'] = $postArr['frequancy_neagtive'];
                $data['txtFrequancyPositive'] = $postArr['yes_content'];
                $data['txtFrequancyNegative'] = $postArr['no_content'];
                $data['chrPublish'] = $postArr['chrMenuDisplay'];
                $data['chrDelete'] = 'N';
                $data['updated_at'] = date('Y-m-d H:i:s');
                if ($postArr['workflow_type'] == 'leads') {
                    $data['varAfter'] = $postArr['after'];
                    $data['txtAfter'] = $postArr['after_content'];
                }
                $id = $request->segment(3);

                if (is_numeric($id)) { #Edit post Handler=======
                $workflowObj = Workflow::getRecordById($id);
                    $whereConditions = ['id' => $id];
                    $update = CommonModel::updateRecords($whereConditions, $data);
                    if ($update) {
                        if ($id > 0 && !empty($id)) {
                            $logArr = MyLibrary::logData($id);
                            if (Auth::user()->can('log-advanced')) {
                                $newWorkflowObj = Workflow::getRecordById($id);
                                $oldRec = $this->recordHistory($workflowObj);
                                $newRec = $this->recordHistory($newWorkflowObj);
                                $logArr['old_val'] = $oldRec;
                                $logArr['new_val'] = $newRec;
                            }
//                            $logArr['varTitle'] = trim($postArr['title']);
                            Log::recordLog($logArr);
                            if (Auth::user()->can('recent-updates-list')) {
                                if (!isset($newWorkflowObj)) {
                                    $newWorkflowObj = Workflow::getRecordById($id);
                                }
                                $notificationArr = MyLibrary::notificationData($id, $newWorkflowObj);
                                RecentUpdates::setNotification($notificationArr);
                            }
                            self::flushCache();
                            $actionMessage = trans('workflow::template.workflowModule.updateMessage');
                        }
                    }
                } else { #Add post Handler=======
                #Add post Handler=======

                $data['created_at'] = date('Y-m-d H:i:s');
                    $added = CommonModel::addRecord($data, 'Powerpanel\Workflow\Models\Workflow');
                    if (!empty($added)) {
                        $id = $added;
                        $newWorkflowObj = Workflow::getRecordById($id);
                        $logArr = MyLibrary::logData($id);
                        $logArr['varTitle'] = 'Workflow';
                        Log::recordLog($logArr);
                        if (Auth::user()->can('recent-updates-list')) {
                            $notificationArr = MyLibrary::notificationData($id, $newWorkflowObj);
                            RecentUpdates::setNotification($notificationArr);
                        }
                        self::flushCache();
                        $actionMessage = trans('workflow::template.workflowModule.addMessage');
                    }

                }



                if (!empty($postArr['saveandexit']) && $postArr['saveandexit'] == 'saveandexit') {
                    return redirect()->route('powerpanel.workflow.index')->with('message', $actionMessage);
                } else {
                    return redirect()->route('powerpanel.workflow.edit', $id)->with('message', $actionMessage);
                }
            } else {
                return Redirect::back()->withErrors($validator)->withInput();
            }
        }
    }

    public function addHiddenRole($data, $workflowObj)
    {
        if (isset($workflowObj) && !empty($workflowObj)) {
            $this->deleteCatPermissions($workflowObj);
        }
        $unid = 'category-' . $data['intCategoryId'] . '-' . $workflowObj->varUserRoles;
        $existing = Role::select('id')->where('display_name', $unid)->get()->toArray();
        if (!empty($existing)) {
            $existing = array_column($existing, 'id');
            Permission_role::deletePermissionRoles($existing);
            Role::deleteRoles($existing);
            Role_user::deleteUserRoles($existing);
        }
        $unid = 'category-' . $data['intCategoryId'] . '-' . $workflowObj->varUserRoles;
        $role = new Role();
        $role->display_name = $unid;
        $role->name = $unid;
        $role->name = $unid;
        $role->description = 'Approval Role';
        $role->chrApprovalRole = 'Y';
        $role->save();
        $permissions = Permission::getPermissions();
        $newPermissionIds = [];
        $createPermissionIds = [];
        $createPermissionIdsRevoke = [];
        $updatePermissionIds = [];
        $updatePermissionIdsRevoke = [];
        $users = explode(',', isset($data['varUserId']) ? $data['varUserId'] : '');
        foreach ($permissions as $permission) {
            if ($permission['modules']['intFkGroupCode'] == $data['intCategoryId'] && $permission['display_name'] == 'per_reviewchanges') {
                $modulePermit = explode('-reviewchanges', $permission['name'])[0];
                $modulePermit = $modulePermit . '-list';
                // foreach ($users as $user) {
                // $user = User::getRecordById($user);
                // if($user->can($modulePermit)){
                $newPermissionIds[] = $permission['id'];
                // }
                // }
            }
            #Add permissions==================================
            if ($workflowObj->chrNeedAddPermission == 'Y' && $permission['modules']['intFkGroupCode'] == $data['intCategoryId'] && $permission['display_name'] == 'per_add') {
                $modulePermit = explode('-create', $permission['name'])[0];
                $modulePermit = $modulePermit . '-list';
                $listPermit = Permission::getPermitByName($modulePermit);
                $rolecan = Permission_role::roleCan($workflowObj->varUserRoles, $listPermit->id);
                if (!empty($rolecan) && count($rolecan->toArray()) > 0) {
                    $createPermissionIds[] = $permission['id'];
                }
            } elseif ($workflowObj->chrNeedAddPermission == 'N' && $permission['modules']['intFkGroupCode'] == $data['intCategoryId'] && $permission['display_name'] == 'per_add') {
                $createPermissionIdsRevoke[] = $permission['id'];
            }
            #/.Add permissions==================================
            #Update permissions==================================
            if ($workflowObj->charNeedApproval == 'Y' && $permission['modules']['intFkGroupCode'] == $data['intCategoryId'] && $permission['display_name'] == 'per_edit') {
                $modulePermit = explode('-edit', $permission['name'])[0];
                $modulePermit = $modulePermit . '-list';
                $listPermit = Permission::getPermitByName($modulePermit);
                $rolecan = Permission_role::roleCan($workflowObj->varUserRoles, $listPermit->id);
                if (!empty($rolecan) && count($rolecan->toArray()) > 0) {
                    $updatePermissionIds[] = $permission['id'];
                }
            } elseif ($workflowObj->charNeedApproval == 'N' && $permission['modules']['intFkGroupCode'] == $data['intCategoryId'] && $permission['display_name'] == 'per_edit') {
                $updatePermissionIdsRevoke[] = $permission['id'];
            }
            #/.Update permissions==================================
        }
        #Add permissions==================================
        if ($workflowObj->chrNeedAddPermission == 'Y') {
            $createPermissionIds = array_unique($createPermissionIds);
            $userRole = Role::getRecordById($workflowObj->varUserRoles);
            foreach ($createPermissionIds as $value) {
                if (empty(Permission_role::checkRoleHasPermit($value, $userRole->id))) {
                    $userRole->attachPermission($value);
                }
            }
        }
        /* elseif($workflowObj->chrNeedAddPermission == 'N'){
        $createPermissionIdsRevoke = array_unique($createPermissionIdsRevoke);
        $userRole = Role::getRecordById($workflowObj->varUserRoles);
        foreach ($createPermissionIdsRevoke as $value)
        {
        $userRole->detachPermission($value);
        }
        } */
        #/.Add permissions==================================
        #Update permissions==================================
        if ($workflowObj->charNeedApproval == 'Y') {
            $updatePermissionIds = array_unique($updatePermissionIds);
            $userRole = Role::getRecordById($workflowObj->varUserRoles);
            foreach ($updatePermissionIds as $value) {
                if (empty(Permission_role::checkRoleHasPermit($value, $userRole->id))) {
                    $userRole->attachPermission($value);
                }
            }
        }
        /* elseif($workflowObj->charNeedApproval == 'N'){
        $updatePermissionIdsRevoke = array_unique($updatePermissionIdsRevoke);
        $userRole = Role::getRecordById($workflowObj->varUserRoles);
        foreach ($updatePermissionIdsRevoke as $value)
        {
        $userRole->detachPermission($value);
        }
        } */
        #/.Update permissions==================================
        $newPermissionIds = array_unique($newPermissionIds);
        foreach ($newPermissionIds as $value) {
            if (empty(Permission_role::checkRoleHasPermit($value, $role->id))) {
                $role->attachPermission($value);
            }
        }
        if (!empty($role->id)) {
            foreach ($users as $user) {
                $user = User::getRecordById($user);
                if (!empty($user)) {
                    $user->attachRole($role->id);
                }
            }
        }
    }

    public function deleteCatPermissions($workflowObj)
    {
        $workflow = $workflowObj;
        $unid = 'category-' . $workflow->intCategoryId . '-' . $workflowObj->varUserRoles;
        $existing = Role::select('id')->where('display_name', $unid)->get()->toArray();
        if (!empty($existing)) {
            $existing = array_column($existing, 'id');
            Permission_role::deletePermissionRoles($existing);
            Role::deleteRoles($existing);
            Role_user::deleteUserRoles($existing);
        }
    }

    /**
     * This method destroys Workflow in multiples
     * @return  Workflow index view
     * @since   2016-10-25
     * @author  NetQuick
     */
    public function DeleteRecord(Request $request)
    {
        $data = Request::all('ids');
        foreach ($data as $id) {
            $workflowObj = Workflow::getRecordById($id);
            $this->deleteCatPermissions($workflowObj);
        }
        $update = MyLibrary::deleteMultipleRecords($data, false, false, 'Powerpanel\Workflow\Models\Workflow');
        self::flushCache();
        echo json_encode($update);
        exit;
    }

    /**
     * This method destroys Workflow in multiples
     * @return  Workflow index view
     * @since   2016-10-25
     * @author  NetQuick
     */
    public function publish(Request $request)
    {
        $alias = (int) Request::input('alias');
        $update = MyLibrary::setPublishUnpublish($alias, $request);
        self::flushCache();
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
				<th>Title</th>
				<th>Activity</th>
				<th>Action</th>
				<th>FrequancyNegative</th>
				<th>FrequancyPositive</th>
				<th>After</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>' . $data->varTitle . '</td>
				<td>' . $data->varActivity . '</td>
				<td>' . $data->varAction . '</td>
				<td>' . $data->varFrequancyNegative . '</td>
				<td>' . $data->varFrequancyPositive . '</td>
				<td>' . $data->varAfter . '</td>
			</tr>
		</tbody>
	</table>';
        return $returnHtml;
    }

    public function getCategory()
    {
        $response = '<option value="">Select Categories</option>';
        $postArr = Request::post();
        $categories = RoleController::GetCategorygroups($postArr['role']);
        $options = [];
        if (!empty($categories)) {
            foreach ($categories as $id => $category) {
                $exists = $this->wfExists($postArr['role'], $id);
                if (empty($exists) && !in_array($id, [6, 4, 5, 0])) {
                    $options[$id] = $category;
                }
            }
        }
        foreach ($options as $id => $category) {
            $response .= '<option value=' . $id . '>' . $category . '</option>';
        }
        if ((int) $postArr['selected'] >= 0 && $postArr['selected'] != null) {
            $selected = ModuleGroup::getGroupById($postArr['selected']);
            $response .= '<option value=' . $selected->id . '>' . $selected->varTitle . '</option>';
        }
        return $response;
    }

    public function getCategoryWiseModules()
    {
        $response = '';
        $postArr = Request::all();
        $catgoryIds = $postArr['category_id'];
        $roleIds = $postArr['role_id'];
        $ignoreId = [];
        $modules = Modules::getModulesBycategory($catgoryIds, $ignoreId);
        $roleCan = Permission_role::getPermissionRole($roleIds);
        $roleCan = array_column($roleCan, 'permission_role');
        $userroleassignedmodules = array_column($roleCan, 'intFKModuleCode');
        $useraddeditModules = array();
        foreach ($roleCan as $rolval) {
            if ($rolval['display_name'] == "per_add" || $rolval['display_name'] == "per_edit") {
                array_push($useraddeditModules, $rolval['intFKModuleCode']);
            }
        }
        if (!empty($modules)) {
            foreach ($modules as $module) {
                if (in_array($module->id, $useraddeditModules)) {
                    if (!$this->wfExists($roleIds, $catgoryIds, $module->id)) {

                        $response .= '<option value=' . $module->id . '>' . $module->varTitle . '</option>';
                    }
                }
            }
        }
        return $response;
    }

    public function getModulesByRole()
    {
        $response = '';
        $postArr = Request::all();
        if (isset($postArr['role_id']) && !empty($postArr['role_id'])) {
            $roleIds = $postArr['role_id'];
            $modules = Modules::getAllActiveModules();

            if ($modules->count() > 0) {
                $roleCan = Permission_role::getPermissionRole($roleIds);
                $roleCan = array_column($roleCan, 'permission_role');
                //$userroleassignedmodules = array_column($roleCan, 'intFKModuleCode');

                $useraddeditModules = array();
                foreach ($roleCan as $rolval) {
                    if ($rolval['display_name'] == "per_add" || $rolval['display_name'] == "per_edit") {
                        array_push($useraddeditModules, $rolval['intFKModuleCode']);
                    }
                }

                foreach ($modules as $module) {
                    if (in_array($module->id, $useraddeditModules)) {
                        if (!$this->wfExists($roleIds, 0, $module->id)) {
                            $response .= '<option value=' . $module->id . '>' . $module->varTitle . '</option>';
                        }
                    }
                }
            }

        }

        return $response;
    }

    public function getAdmins()
    {
        $postArr = Request::all();
        $catPermissions = Self::getCategoryPermissions($postArr['groupId']);
        $ignoreRoles = [1];
        $appusers = [];
        $adminUsers = Role::getAdmins($ignoreRoles);
        foreach ($adminUsers as $adminUser) {
            foreach ($adminUser->roleuser as $roleuser) {
                $appusers[] = $roleuser->users;
            }
        }
        $admins = [];
        foreach ($appusers as $user) {
            if (!empty($user)) {
                foreach ($catPermissions as $permission) {
                    if ($user->can($permission)) {
                        $admins[] = $user;
                    }
                }
            }
        }
        $adminUsers = array_map("unserialize", array_unique(array_map("serialize", $admins)));
        $admins = '<option value="">Select Admin</option>';
        foreach ($adminUsers as $user) {
            $admins .= "<option value='" . $user->id . "'>" . $user->name . "</option>";
        }
        return $admins;
    }

    public function getAdminUsers()
    {

        $response = '<option value="">Select Admin</option>';
        $admins = [];
        // $ignoreRoles = [1];
        $ignoreRoles = [];
        $adminUsers = Role::getAdmins($ignoreRoles);
        foreach ($adminUsers as $adminUser) {
            foreach ($adminUser->roleuser as $key => $roleuser) {
                $admins[] = $roleuser->users;
            }
        }
        $admins = array_unique($admins);
        foreach ($admins as $user) {
            $response .= "<option value='" . $user->id . "'>" . $user->name . "</option>";
        }
        return $response;
    }

    public static function getCategoryPermissions($id)
    {
        $response = false;
        $moduleCategory = RoleController::grouppedPermission($id);
        $modules = [];
        $catPermissions = [];
        foreach ($moduleCategory as $groups) {
            foreach ($groups as $key => $module) {
                if (is_array($module)) {
                    $modules[] = array_column($module, 'name');
                }
            }
        }
        foreach ($modules as $module) {
            foreach ($module as $permission) {
                if (strpos($permission, '-list') > 0) {
                    $catPermissions[] = $permission;
                }
            }
        }
        if (!empty($catPermissions)) {
            $response = $catPermissions;
        }
        return $response;
    }

    public static function flushCache()
    {
        Cache::tags('Workflow')->flush();
    }

    public function wfExists($roleId, $catId, $mid = false)
    {
        $response = Workflow::checkExists(0, $roleId, $mid);
        return $response;
    }

    public function insertComents(Request $request)
    {
        $modiledata = Modules::getModuleById(Request::post('varModuleId'));
        if ($modiledata['varModuleNameSpace'] != '') {
            $modelNameSpace = $modiledata['varModuleNameSpace'] . 'Models\\' . $modiledata['varModelName'];
        } else {
            $modelNameSpace = '\\App\\' . Request::post('namespace');
        }
        $Comments_data['intRecordID'] = Request::post('id');
        $Comments_data['varNameSpace'] = $modelNameSpace;
        $Comments_data['varModuleNameSpace'] = Request::post('namespace');
        $Comments_data['varCmsPageComments'] = stripslashes(trim(Request::post('CmsPageComments')));
        $Comments_data['UserID'] = Request::post('UserID');
        $Comments_data['intCommentBy'] = auth()->user()->id;
        $Comments_data['varModuleTitle'] = Request::post('varModuleTitle');
        $Comments_data['fkMainRecord'] = Request::post('fkMainRecord');
        Comments::insertComents($Comments_data);

        $commentdata = Config::get('Constant.COMMENT_ADDED');
        $newCmsPageObj = $modelNameSpace::getRecordForLogById(Request::post('id'));
        $logArr = MyLibrary::logData(Request::post('id'), Request::post('varModuleId'), $commentdata);
        $logArr['varTitle'] = stripslashes($newCmsPageObj->varTitle);
        Log::recordLog($logArr);
        if (method_exists($this->MyLibrary, 'userNotificationData')) {
            /* code for insert comment */
            $userNotificationArr = MyLibrary::userNotificationData(Request::post('varModuleId'));
            $userNotificationArr['fkRecordId'] = Request::post('id');
            $userNotificationArr['txtNotification'] = 'New comment from ' . ucfirst(auth()->user()->name) . ' (' . ucfirst(Request::post('varModuleTitle')) . ')';
            $userNotificationArr['fkIntUserId'] = auth()->user()->id;
            $userNotificationArr['chrNotificationType'] = 'C';
            $userNotificationArr['intOnlyForUserId'] = Request::post('UserID');
            UserNotification::addRecord($userNotificationArr);
        }
        exit;
    }

}
