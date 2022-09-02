<?php

namespace Powerpanel\PageTemplates\Controllers\Powerpanel;

use App\Alias;
use App\CommonModel;
use App\Helpers\FrontPageContent_Shield;
use App\Helpers\MyLibrary;
use App\Http\Controllers\PowerpanelController;
use App\Log;
use App\Modules;
use App\RecentUpdates;
use App\User;
use Auth;
use Cache;
use Carbon\Carbon;
use Config;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Powerpanel\PageTemplates\Models\PageTemplate;
use Powerpanel\RoleManager\Models\Role_user;
use Validator;

class PageTemplateController extends PowerpanelController
{

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        parent::__construct();
        if (isset($_COOKIE['locale'])) {
            app()->setLocale($_COOKIE['locale']);
        }
        $this->MyLibrary = new MyLibrary();
        $this->CommonModel = new CommonModel();
    }


    public function index()
    {
        $userIsAdmin = false;
        if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
            $userIsAdmin = true;
        }

        $iTotalRecords = PageTemplate::getRecordCount();
        $this->breadcrumb['title'] = trans('pagetemplates::template.pagetemplate.manage');

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

        return view('pagetemplates::powerpanel.list', ['iTotalRecords' => $iTotalRecords, 'breadcrumb' => $this->breadcrumb, 'userIsAdmin' => $userIsAdmin, 'settingarray' => $settingarray]);
    }

    public function get_list()
    {
        $filterArr = [];
        $records = [];
        $records['data'] = [];
        $filterArr['orderColumnNo'] = (!empty(Request::input('order')[0]['column']) ? Request::input('order')[0]['column'] : '');
        $filterArr['orderByFieldName'] = (!empty(Request::input('columns')[$filterArr['orderColumnNo']]['name']) ? Request::input('columns')[$filterArr['orderColumnNo']]['name'] : '');
        $filterArr['orderTypeAscOrDesc'] = (!empty(Request::input('order')[0]['dir']) ? Request::input('order')[0]['dir'] : '');
        $filterArr['statusFilter'] = !empty(Request::input('customActionName')) ? Request::input('customActionName') : '';
        $filterArr['searchFilter'] = !empty(Request::input('searchValue')) ? Request::input('searchValue') : '';
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
        $arrResults = PageTemplate::getRecordList($filterArr, $isAdmin, $ignoreId, $this->currentUserRoleSector);
        $iTotalRecords = PageTemplate::getRecordCountforList($filterArr, true, $isAdmin, $ignoreId, $this->currentUserRoleSector);

        if (!empty($arrResults)) {
            $currentUserID = auth()->user()->id;
            $permit = [
                'canpagetemplateedit' => Auth::user()->can('page_template-edit'),
                'canpagetemplatepublish' => Auth::user()->can('page_template-publish'),
                'canpagetemplatedelete' => Auth::user()->can('page_template-delete'),
                'canpagetemplatereviewchanges' => Auth::user()->can('page_template-reviewchanges'),
                'canloglist' => Auth::user()->can('log-list'),
            ];

            foreach ($arrResults as $key => $value) {
                if (!in_array($value->id, $ignoreId)) {
                    $records['data'][] = $this->tableData($value, $permit, $currentUserID, $isAdmin);
                }
            }
        }

        if (!empty(Request::input('customActionType')) && Request::input('customActionType') == 'group_action') {
            $records['customActionStatus'] = 'OK';
        }
        $records['draw'] = $sEcho;
        $records['recordsTotal'] = $iTotalRecords;
        $records['recordsFiltered'] = $iTotalRecords;
        echo json_encode($records);
        exit;
    }

    public function edit($id = false)
    {
        $imageManager = true;
        $userIsAdmin = false;
        if (!empty($this->currentUserRoleData)) {
            if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                $userIsAdmin = true;
            }
        }
        $ignoreModulesNames = ['workflow', 'submit-tickets', 'feedback-leads'];
        $ignoreModules = Modules::getModuleIdsByNames($ignoreModulesNames);
        $ignoreModulesIds = array();
        if (!empty($ignoreModules)) {
            $ignoreModulesIds = array_column($ignoreModules, 'id');
        }
        $modules = Modules::getModuleList($ignoreModulesIds);
        $templateData = array();
        if (is_numeric($id) && !empty($id)) {
            $Cmspage = PageTemplate::getRecordById($id);
            if (empty($Cmspage)) {
                return redirect()->route('powerpanel.page_template.add');
            }
            $templateData['pageTemplate'] = $Cmspage;
            $metaInfo['varURL'] = $Cmspage['alias']['varAlias'];
            $metaInfo['varMetaTitle'] = $Cmspage['varMetaTitle'];
            $metaInfo['varMetaDescription'] = $Cmspage['varMetaDescription'];
            $this->breadcrumb['title'] = trans('pagetemplates::template.pagetemplate.edit');
            $this->breadcrumb['inner_title'] = $Cmspage->varTemplateName;
            if ($Cmspage->alias->varAlias != 'home') {
                $templateData['publishActionDisplay'] = true;
            }
        } else {
            $this->breadcrumb['title'] = trans('pagetemplates::template.pagetemplate.add');
            $this->breadcrumb['inner_title'] = "";
            $templateData['publishActionDisplay'] = true;
        }
        $templateData['userIsAdmin'] = $userIsAdmin;
        $this->breadcrumb['module'] = trans('pagetemplates::template.pagetemplate.manage');
        $this->breadcrumb['url'] = 'powerpanel/page_template';
        $templateData['modules'] = $modules;
        $templateData['breadcrumb'] = $this->breadcrumb;
        $templateData['metaInfo'] = (!empty($metaInfo) ? $metaInfo : '');
        $templateData['metaInfo_highLight'] = (!empty($metaInfo_highLight) ? $metaInfo_highLight : '');
        $templateData['imageManager'] = $imageManager;

        //Start Button Name Change For User Side
        if ($this->currentUserRoleData->chrIsAdmin != 'Y') {
            $module = Modules::getModuleById(Config::get('Constant.MODULE.ID'));
            if (!$userIsAdmin) {
                $userRole = $this->currentUserRoleData->id;
            } else {
                $userRoleData = Role_user::getUserRoleByUserId(auth()->user()->id);
                $userRole = $userRoleData->role_id;
            }
            /* $workFlowByCat = Workflow::getRecordByCategoryId($module->intFkGroupCode, $userRole, Config::get('Constant.MODULE.ID'));

        $templateData['chrNeedAddPermission'] = $workFlowByCat->chrNeedAddPermission;
        $templateData['charNeedApproval'] = $workFlowByCat->charNeedApproval; */
        }
        //End Button Name Change For User Side

        return view('pagetemplates::powerpanel.actions', $templateData);
    }

    public function handlePost(Request $request, Guard $auth)
    {
        $userIsAdmin = false;
        if (!empty($this->currentUserRoleData)) {
            if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                $userIsAdmin = true;
            }
        }
        $data = Request::input();
        $actionMessage = trans('pagetemplates::template.common.oppsSomethingWrong');
        $rules = array(
            'title' => 'required|max:160|handle_xss|no_url',
            'alias' => 'required',
        );
        $messsages = array(
            'title.required' => trans('pagetemplates::template.pagetemplate.title'),
        );
        $validator = Validator::make($data, $rules, $messsages);
        if ($validator->passes()) {
            $module = Modules::getModuleById(Config::get('Constant.MODULE.ID'));

            $cmsPageArr = [];
            if (Config::get('Constant.DEFAULT_VISUAL') == 'Y') {
                if ($data['section'] != '[]') {
                    $vsection = $data['section'];
                } else {
                    $vsection = '';
                }
            } else {
                $vsection = $data['contents'];
            }
            $cmsPageArr['varTemplateName'] = stripslashes(trim($data['title']));
            $cmsPageArr['txtDesc'] = $vsection;
            $cmsPageArr['UserID'] = auth()->user()->id;
            $cmsPageArr['chrPublish'] = $data['chrMenuDisplay'];
            $cmsPageArr['chrDisplayStatus'] = $data['chrDisplayStatus'];

            $id = Request::segment(3);
            if (is_numeric($id) && !empty($id)) {
                //Edit post Handler=======
                $cmsPage = PageTemplate::getRecordForLogById($id);

                if (!$userIsAdmin) {
                    $userRole = $this->currentUserRoleData->id;
                } else {
                    $userRoleData = Role_user::getUserRoleByUserId($cmsPage->UserID);
                    if (isset($userRoleData->role_id)) {
                        $userRole = $userRoleData->role_id;
                    } else {
                        $userRole = $this->currentUserRoleData->id;
                    }
                }

                if ($data['oldAlias'] != $data['alias']) {
                    Alias::updateAlias($data['oldAlias'], $data['alias']);
                }
                $addlog = '';
                $whereConditions = ['id' => $cmsPage->id];

                if (empty($workFlowByCat->varUserId) || $userIsAdmin || $workFlowByCat->charNeedApproval == 'N') {
                    if ($cmsPage->fkMainRecord == '0' || empty($workFlowByCat->varUserId)) {
                        $update = CommonModel::updateRecords($whereConditions, $cmsPageArr, false, 'Powerpanel\PageTemplates\Models\PageTemplate');
                        if ($update) {
                            $newPageTemplateObj = PageTemplate::getRecordForLogById($cmsPage->id);
                            //Update record in menu
                            $whereConditions = ['txtPageUrl' => $data['oldAlias']];
                            $updateMenuFields = [
                                'varTemplateName' => $newPageTemplateObj->varTemplateName,
                                'txtPageUrl' => $newPageTemplateObj->alias->varAlias,
                                'chrPublish' => $data['chrMenuDisplay'],
                                'chrDisplayStatus' => $data['chrDisplayStatus'],
                                'chrActive' => $data['chrMenuDisplay'],
                            ];
                            //Update record in menu
                            $logArr = MyLibrary::logData($cmsPage->id, false, $addlog);
                            if (Auth::user()->can('log-advanced')) {
                                $oldRec = $this->recordHistory($cmsPage);
                                $newRec = $this->newrecordHistory($cmsPage, $newPageTemplateObj);
                                $logArr['old_val'] = $oldRec;
                                $logArr['new_val'] = $newRec;
                            }
                            $logArr['varTitle'] = $newPageTemplateObj->varTemplateName;
                            Log::recordLog($logArr);
                            if (Auth::user()->can('recent-updates-list')) {
                                $notificationArr = MyLibrary::notificationData($cmsPage->id, $newPageTemplateObj);
                                RecentUpdates::setNotification($notificationArr);
                            }
                            self::flushCache();
                            if (isset($data['saveandexit']) && $data['saveandexit'] == 'approvesaveandexit') {
                                $actionMessage = trans('pagetemplates::template.pagetemplate.pageApprovalUpdate');
                            } else {
                                $actionMessage = trans('pagetemplates::template.pagetemplate.pageUpdate');
                            }
                        }
                    } else {
                        $newCmsPageObj = PageTemplate::getRecordForLogById($cmsPage->id);
                        //Update record in menu
                        $whereConditions = ['txtPageUrl' => $data['oldAlias']];
                        $updateMenuFields = [
                            'varTemplateName' => $newPageTemplateObj->varTemplateName,
                            'txtPageUrl' => $newCmsPageObj->alias->varAlias,
                            'chrPublish' => $data['chrMenuDisplay'],
                            'chrDisplayStatus' => $data['chrDisplayStatus'],
                            'chrActive' => $data['chrMenuDisplay'],
                        ];
                        //Update record in menu
                        $updateModuleFields = $cmsPageArr;
                        $this->insertApprovedRecord($updateModuleFields, $data, $id);
                        if (isset($data['saveandexit']) && $data['saveandexit'] == 'approvesaveandexit') {
                            $actionMessage = trans('pagetemplates::template.pagetemplate.pageApprovalUpdate');
                        } else {
                            $actionMessage = trans('pagetemplates::template.pagetemplate.pageUpdate');
                        }
                        $approval = $id;
                    }
                } else {
                    if ($workFlowByCat->charNeedApproval == 'Y') {
                        $postArr = $data;
                        $approvalObj = $this->insertApprovalRecord($cmsPage, $postArr, $cmsPageArr);
                        if (isset($data['saveandexit']) && $data['saveandexit'] == 'approvesaveandexit') {
                            $actionMessage = trans('pagetemplates::template.pagetemplate.pageApprovalUpdate');
                        } else {
                            $actionMessage = trans('pagetemplates::template.pagetemplate.pageUpdate');
                        }
                        $approval = $approvalObj->id;
                    }
                }
            } else {
                $postArr = $data;
                $cmsPage = $this->insertNewRecord($postArr, $cmsPageArr);
                $approval = $cmsPage->id;
                if (isset($data['saveandexit']) && $data['saveandexit'] == 'approvesaveandexit') {
                    $actionMessage = trans('pagetemplates::template.pagetemplate.addapprovalMessage');
                } else {
                    $actionMessage = trans('pagetemplates::template.pagetemplate.addMessage');
                }
                $id = $cmsPage->id;
            }
            Alias::updatePreviewAlias($data['alias'], 'N');

            if ((!empty(Request::get('saveandexit')) && Request::get('saveandexit') == 'saveandexit') || !$userIsAdmin) {
                if ($data['chrMenuDisplay'] == 'D') {
                    return redirect()->route('powerpanel.page_template.index', 'tab=D')->with('message', $actionMessage);
                } else {
                    return redirect()->route('powerpanel.page_template.index')->with('message', $actionMessage);
                }
            } else {
                return redirect()->route('powerpanel.page_template.edit', $id)->with('message', $actionMessage);
            }
        } else {
            return Redirect::back()->withErrors($validator)->withInput();
        }
    }

// End Insert Approval Records
    public function insertNewRecord($postArr, $cmsPageArr)
    {
        $response = false;
//Add post Handler=======
        $cmsPageArr['intAliasId'] = MyLibrary::insertAlias($postArr['alias'], false, 'N');
        $cmsPageArr['created_at'] = Carbon::now();
        $cmsPageArr['updated_at'] = Carbon::now();
        $cmsPageArr['chrPublish'] = $postArr['chrMenuDisplay'];
        $id = CommonModel::addRecord($cmsPageArr, 'Powerpanel\PageTemplates\Models\PageTemplate');
        if (isset($id) && !empty($id)) {
            $newCmsPageObj = PageTemplate::getRecordForLogById($id);
            $logArr = MyLibrary::logData($id, false, '');
            $logArr['varTitle'] = $newCmsPageObj->varTemplateName;
            Log::recordLog($logArr);
            if (Auth::user()->can('recent-updates-list')) {
                $notificationArr = MyLibrary::notificationData($id, $newCmsPageObj);
                RecentUpdates::setNotification($notificationArr);
            }
            $response = $newCmsPageObj;
            self::flushCache();
        }
        return $response;
    }

    /**
     * This method destroys Banner in multiples.
     *
     * @return Banner index view
     *
     * @since   2016-10-25
     *
     * @author  NetQuick
     */
    public function DeleteRecord(Request $request)
    {
        $value = Request::get('value');
        $data['ids'] = Request::get('ids');
        $moduleHaveFields = [];
        $update = MyLibrary::deleteMultipleRecords($data, $moduleHaveFields, $value, 'Powerpanel\PageTemplates\Models\PageTemplate');
        self::flushCache();
        echo json_encode($update);
        exit;
    }

    /**
     * This method handle publish-unpublish features.
     *
     * @return true/false
     *
     * @since   2017-07-24
     *
     * @author  NetQuick
     */
    public function publish(Request $request)
    {
        $alias = Request::input('alias');
        $update = MyLibrary::setPublishUnpublish($alias, $request, 'Powerpanel\PageTemplates\Models\PageTemplate');
        self::flushCache();
        echo json_encode($update);
        exit;
    }

    /**
     * This method handle to get child record.
     *
     * @author  Snehal
     */
    public function tableData($value , $permit, $currentUserID, $isAdmin)
    {

        // Checkbox
        $checkbox = view('powerpanel.partials.checkbox', ['name'=>'delete', 'value'=>$value->id])->render();


        // Date
        $date = '<span align="left" data-bs-toggle="tooltip" data-bs-placement="bottom" title="'.date(Config::get("Constant.DEFAULT_DATE_FORMAT").' '.Config::get("Constant.DEFAULT_TIME_FORMAT"), strtotime($value->created_at)).'">'.date(Config::get('Constant.DEFAULT_DATE_FORMAT'), strtotime($value->created_at)).'</span>';


        // Title
        $title = $value->varTemplateName;

        if ($isAdmin) {
            $userdata = User::getUserId($value->UserID);
            $username = '(<em>Created by @' . $userdata->name . "</em>)";
        } else {
            $username = '';
        }


        // Publish Action
        $publish_action = '';
        if ($value->chrAddStar != 'Y') {
            if ($value->chrDraft != 'D') {
                if ($permit['canpagetemplatepublish']) {
                    if ($value->chrPublish == 'Y') {
                        $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/page_template', 'data_alias'=>$value->id, 'title'=>trans("pagetemplates::template.common.publishedRecord"), 'data_value'=>'Unpublish', 'checked'=>'checked'])->render();
                    } else {
                        $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/page_template', 'data_alias'=>$value->id, 'title'=>trans("pagetemplates::template.common.unpublishedRecord"), 'data_value'=>'Publish'])->render();
                    }
                } else {
                    $publish_action = "-";
                }
            } else {
                if ($permit['canpagetemplatepublish']) {
                    if ($value->chrPublish == 'Y') {
                        $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/page_template', 'data_alias'=>$value->id, 'title'=>trans("pagetemplates::template.common.publishedRecord"), 'data_value'=>'Unpublish', 'checked'=>'checked'])->render();
                    } else {
                        $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/page_template', 'data_alias'=>$value->id, 'title'=>trans("pagetemplates::template.common.unpublishedRecord"), 'data_value'=>'Publish'])->render();
                    }
                } else {
                    $publish_action = "-";
                }
            }
        } else {
            if ($permit['canpagetemplatepublish']) {
                $publish_action .= '<a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-toggle="tooltip" title="This Record is in Approval request so can&#39;t be Publish/Unpublish."><i style="color:red" class="ri-toggle-line icon-publish fs-24"></i></a>';
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


        // All - Actions
        $logurl = url('powerpanel/log?id=' . $value->id . '&mid=' . Config::get('Constant.MODULE.ID'));
        $allActions = view('powerpanel.partials.all-actions',
                    [
                        'tabName'=>'All',
                        'canedit'=> $permit['canpagetemplateedit'],
                        'candelete'=>$permit['canpagetemplatedelete'],
                        'canloglist'=>$permit['canloglist'],
                        'value'=>$value,
                        'chrIsAdmin' => $this->currentUserRoleData->chrIsAdmin,
                        'module_name'=>'page_template',
                        'module_edit_url' => route('powerpanel.page_template.edit', array('alias' => $value->id)),
                        'module_type'=>'parent',
                        'viewlink' => isset($viewlink) ? $viewlink : "",
                        'linkviewLable' => isset($linkviewLable) ? $linkviewLable : "",
                        'logurl' => $logurl
                    ])->render();

        if($permit['canpagetemplateedit'] || $permit['canpagetemplatedelete']){
            $allActions = $allActions;
        } else {
            $allActions = "-";
        }


        $records = array(
            $checkbox,
            '<div class="pages_title_div_row"> <span class="title-txt"> ' . $title . ' ' . $status . $statusdata . ' - ' . $username . '</span></div>',
            $date,
            $publish_action,
            $allActions
        );
        return $records;
    }

    /**
     * This method handels logs History records.
     *
     * @param $data
     *
     * @return HTML
     *
     * @since   2017-07-27
     *
     * @author  NetQuick
     */
    public function recordHistory($data = false)
    {
        $startDate = date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($data->created_at));
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
        $returnHtml = '';
        $returnHtml .= '<table class = "new_table_desing table table-striped table-bordered table-hover">
				<thead>
				<tr>
				<th align="center">Template Name</th>
				<th align="center">' . trans('pagetemplates::template.common.content') . '</th>
                                <th align="center">Date</th>
				<th align="center">' . trans('pagetemplates::template.common.publish') . '</th>
				</tr>
				</thead>
				<tbody>
				<tr>
				<td align="center">' . stripslashes($data->varTemplateName) . '</td>
				<td align="center">' . $desc . '</td>
                                <td align="center">' . $startDate . '</td>
				<td align="center">' . $data->chrPublish . '</td>
				</tr>
				</tbody>
				</table>';
        return $returnHtml;
    }

    public function newrecordHistory($data = false, $newdata = false)
    {
        if ($data->varTemplateName != $newdata->varTemplateName) {
            $titlecolor = 'style="background-color:#f5efb7"';
        } else {
            $titlecolor = '';
        }

        if ($data->txtDesc != $newdata->txtDesc) {
            $desccolor = 'style="background-color:#f5efb7"';
        } else {
            $desccolor = '';
        }

        if ($data->chrPublish != $newdata->chrPublish) {
            $Publishcolor = 'style="background-color:#f5efb7"';
        } else {
            $Publishcolor = '';
        }
        if ($data->created_at != $newdata->created_at) {
            $DateTimecolor = 'style="background-color:#f5efb7"';
        } else {
            $DateTimecolor = '';
        }

        $startDate = date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($newdata->created_at));
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
        $returnHtml = '';
        $returnHtml .= '<table class = "new_table_desing table table-striped table-bordered table-hover">
				<thead>
				<tr>
				<th align="center">Template Name</th>
				<th align="center">' . trans('pagetemplates::template.common.content') . '</th>
                                <th align="center">Date</th>
				<th align="center">' . trans('pagetemplates::template.common.publish') . '</th>
				</tr>
				</thead>
				<tbody>
				<tr>
				<td align="center" ' . $titlecolor . '>' . stripslashes($newdata->varTemplateName) . '</td>
				<td align="center" ' . $desccolor . '>' . $desc . '</td>
                                <td align="center" ' . $DateTimecolor . '>' . $startDate . '</td>
				<td align="center" ' . $Publishcolor . '>' . $newdata->chrPublish . '</td>
				</tr>
				</tbody>
				</table>';
        return $returnHtml;
    }

    public function flushCache()
    {
        Cache::forget('getPageByPageId');
    }

    public function addPreview(Request $request, Guard $auth)
    {
        $data = Request::input();
        $rules = array(
            'title' => 'required|max:160',
            'alias' => 'required',
        );
        $validator = Validator::make($data, $rules);
        if ($validator->passes()) {
            if (Config::get('Constant.DEFAULT_VISUAL') == 'Y') {
                if ($data['section'] != '[]') {
                    $vsection = $data['section'];
                } else {
                    $vsection = '';
                }
            } else {
                $vsection = $data['contents'];
            }

            $cmsPageArr = [];
            $cmsPageArr['varTemplateName'] = stripslashes(trim($data['title']));

            $cmsPageArr['txtDesc'] = $vsection;
            $cmsPageArr['chrPublish'] = $data['chrMenuDisplay'];

            $cmsPageArr['UserID'] = auth()->user()->id;
            $id = $data['previewId'];
            if (is_numeric($id) && !empty($id)) {
//Edit post Handler=======
                $cmsPage = PageTemplate::getRecordById($id);
                if ($data['oldAlias'] != $data['alias']) {
                    Alias::updateAlias($data['oldAlias'], $data['alias']);
                }
                $whereConditions = ['id' => $cmsPage->id];
                if ($this->currentUserRoleData->chrIsAdmin == 'Y') {

                    $cmsPageArr['chrIsPreview'] = 'Y';
                    $update = CommonModel::updateRecords($whereConditions, $cmsPageArr, false, 'Powerpanel\PageTemplates\Models\PageTemplate');
                    if ($update) {
                        $newCmsPageObj = PageTemplate::getRecordById($cmsPage->id);
//Update record in menu
                        $whereConditions = ['txtPageUrl' => $data['oldAlias']];
                        $updateMenuFields = [
                            'varTemplateName' => stripslashes($newCmsPageObj->varTemplateName),
                            'txtPageUrl' => $newCmsPageObj->alias->varAlias,
                            'chrPublish' => $data['chrMenuDisplay'],
                        ];
//Update record in menu
                        if (Auth::user()->can('recent-updates-list')) {
                            $notificationArr = MyLibrary::notificationData($cmsPage->id, $newCmsPageObj);
                            RecentUpdates::setNotification($notificationArr);
                        }
                        self::flushCache();
                    }
                } else {
                    $cmsPageArr['intAliasId'] = MyLibrary::insertAlias($data['alias'], false, 'Y');
                    $cmsPageArr['chrIsPreview'] = 'Y';
                    $cmsPageArr['fkMainRecord'] = $cmsPage->id;
                    $id = CommonModel::addRecord($cmsPageArr, 'Powerpanel\PageTemplates\Models\PageTemplate');
                    $whereConditionsAddstar = ['id' => $cmsPage->id];
                    $updateAddStar = [
                        'chrAddStar' => 'Y',
                    ];
                    CommonModel::updateRecords($whereConditionsAddstar, $updateAddStar, false, 'Powerpanel\PageTemplates\Models\PageTemplate');
                }
            } else {
//Add post Handler=======
                $cmsPageArr['intAliasId'] = MyLibrary::insertAlias($data['alias'], false, 'Y');
                $cmsPageArr['created_at'] = Carbon::now();
                $cmsPageArr['updated_at'] = Carbon::now();
                $cmsPageArr['chrIsPreview'] = 'Y';
                $id = CommonModel::addRecord($cmsPageArr, 'Powerpanel\PageTemplates\Models\PageTemplate');
            }
            return json_encode(array('status' => $id, 'alias' => $data['alias'], 'message' => trans('pagetemplates::template.pageModule.pageUpdate')));
        } else {
            return json_encode(array('status' => 'error', 'message' => $validator->errors()));
        }
    }

}
