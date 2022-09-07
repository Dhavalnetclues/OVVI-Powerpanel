<?php

namespace Powerpanel\FormBuilder\Controllers\Powerpanel;

use Request;
use Illuminate\Support\Facades\Redirect;
use Powerpanel\Workflow\Models\Comments;
use Powerpanel\FormBuilder\Models\FormBuilder;
use Powerpanel\Workflow\Models\WorkflowLog;
use Powerpanel\Workflow\Models\Workflow;
use App\Log;
use App\RecentUpdates;
use App\Alias;
use App\User;
use Validator;
use DB;
use Config;
use App\Http\Controllers\PowerpanelController;
use Crypt;
use Auth;
use App\Helpers\resize_image;
use App\Helpers\MyLibrary;
use App\CommonModel;
use Carbon\Carbon;
use Cache;
use App\Modules;
use Powerpanel\RoleManager\Models\Role_user;
use App\Helpers\AddImageModelRel;
use App\UserNotification;

class FormBuilderController extends PowerpanelController {

    /**
     * Create a new controller instance.
     * @return void
     */
    public function __construct() {
        parent::__construct();
        if (isset($_COOKIE['locale'])) {
            app()->setLocale($_COOKIE['locale']);
        }
        $this->CommonModel = new CommonModel();
        $this->MyLibrary = new MyLibrary();
    }

    /**
     * This method handels load videoGallery grid
     * @return  View
     * @since   2017-07-20
     * @author  NetQuick
     */
    public function index() {
        $userIsAdmin = false;
        if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
            $userIsAdmin = true;
        }
        $total = FormBuilder::getRecordCount();
        $NewRecordsCount = FormBuilder::getNewRecordsCount();
        
        $this->breadcrumb['title'] = trans('formbuilder::template.formbuilderModule.manageformbuilder');

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
        
        return view('formbuilder::powerpanel.index', ['iTotalRecords' => $total, 'breadcrumb' => $this->breadcrumb, 'NewRecordsCount' => $NewRecordsCount, 'userIsAdmin' => $userIsAdmin, 'settingarray' => $settingarray]);
    }

    /**
     * This method handels list of videoGallery with filters
     * @return  View
     * @since   2017-07-20
     * @author  NetQuick
     */
    public function get_list() {
        
        /* Start code for sorting */
        $filterArr = [];
        $records = array();
        $records["data"] = array();
        $filterArr['orderColumnNo'] = (!empty(Request::input('order') [0]['column']) ? Request::input('order') [0]['column'] : '');
        $filterArr['orderByFieldName'] = (!empty(Request::input('columns') [$filterArr['orderColumnNo']]['name']) ? Request::input('columns') [$filterArr['orderColumnNo']]['name'] : '');
        $filterArr['orderTypeAscOrDesc'] = (!empty(Request::input('order') [0]['dir']) ? Request::input('order') [0]['dir'] : '');
        $filterArr['statusFilter'] = !empty(Request::input('statusValue')) ? Request::input('statusValue') : '';
        $filterArr['catFilter'] = !empty(Request::input('catValue')) ? Request::input('catValue') : '';
        $filterArr['searchFilter'] = !empty(Request::input('searchValue')) ? Request::input('searchValue') : '';
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
        $arrResults = FormBuilder::getRecordList($filterArr, $isAdmin, $ignoreId, $this->currentUserRoleSector);
        $iTotalRecords = FormBuilder::getRecordCountforList($filterArr, true, $isAdmin, $ignoreId, $this->currentUserRoleSector);
        
        if (!empty($arrResults)) {
            $currentUserID = auth()->user()->id;
            $permit = [
                'canformbuilderedit' => Auth::user()->can('formbuilder-edit'),
                'canformbuilderpublish' => Auth::user()->can('formbuilder-publish'),
                'canformbuilderdelete' => Auth::user()->can('formbuilder-delete'),
                'canformbuilderreviewchanges' => Auth::user()->can('formbuilder-reviewchanges'),
                'canloglist' => Auth::user()->can('log-list'),
            ];
            
            foreach ($arrResults as $key => $value) {
                if (!in_array($value->id, $ignoreId)) {
                    
                    $records['data'][] = $this->tableData($value, $permit, $currentUserID, $isAdmin);
                }
            }         
            
            
        }
        // print_r($records);die;
            
        $NewRecordsCount = FormBuilder::getNewRecordsCount($isAdmin, $this->currentUserRoleSector);
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

    public function edit($alias = false) {
        $imageManager = true;
        $userIsAdmin = false;
        if (!empty($this->currentUserRoleData)) {
            if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                $userIsAdmin = true;
            }
        }

        $templateData = array();
        if (!is_numeric($alias)) {
            $total = FormBuilder::getRecordCount();


            $this->breadcrumb['title'] = trans('formbuilder::template.formbuilderModule.addformbuilder');
            $this->breadcrumb['module'] = trans('formbuilder::template.formbuilderModule.manageformbuilder');
            $this->breadcrumb['url'] = 'powerpanel/formbuilder';
            $this->breadcrumb['inner_title'] = '';

            $templateData['total'] = $total;
            $templateData['breadcrumb'] = $this->breadcrumb;
            $templateData['imageManager'] = $imageManager;
        } else {
            $id = $alias;
            $formbuilderdata = FormBuilder::getRecordById($id);
            if (empty($formbuilderdata)) {
                return redirect()->route('powerpanel.formbuilder.add');
            }

            if ($formbuilderdata->fkMainRecord != '0') {
                $formbuilderdata_highLight = FormBuilder::getRecordById($formbuilderdata->fkMainRecord);
                $templateData['frombuilder_highLight'] = $formbuilderdata_highLight;
            } else {
                $templateData['frombuilder_highLight'] = "";
            }
            $this->breadcrumb['title'] = trans('formbuilder::template.formbuilderModule.editformbuilder');
            $this->breadcrumb['module'] = trans('formbuilder::template.formbuilderModule.manageformbuilder');
            $this->breadcrumb['url'] = 'powerpanel/formbuilder';
            $this->breadcrumb['inner_title'] = $formbuilderdata->varName;
            $templateData['frombuilder'] = $formbuilderdata;
            $templateData['id'] = $id;
            $templateData['breadcrumb'] = $this->breadcrumb;
            $templateData['imageManager'] = $imageManager;
        }
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
        $templateData['userIsAdmin'] = $userIsAdmin;
        return view('formbuilder::powerpanel.actions', $templateData);
    }

    /**
     * This method stores videoGallery modifications
     * @return  View
     * @since   2017-07-21
     * @author  NetQuick
     */
    public function handlePost(Request $request) {
        $approval = false;
        $userIsAdmin = false;
        if (!empty($this->currentUserRoleData)) {
            if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                $userIsAdmin = true;
            }
        }
        $postArr = Request::all();
        $messsages = [
            'title.required' => 'Title field is required.',
            'link.required' => 'Link field is required.',
            'order.required' => trans('formbuilder::template.formbuilderModule.displayOrder'),
            'order.greater_than_zero' => trans('formbuilder::template.formbuilderModule.displayGreaterThan'),
            'img_id.required' => 'Image field is required.'
        ];
        $rules = [
            'title' => 'required|max:160|handle_xss|no_url',
            'order' => 'required|greater_than_zero|handle_xss|no_url',
            'chrMenuDisplay' => 'required',
            'link' => 'required|handle_xss',
            'img_id' => 'required',
        ];

        $validator = Validator::make($postArr, $rules, $messsages);
        if ($validator->passes()) {

            $formbuilderdataArr = [];

            $module = Modules::getModuleById(Config::get('Constant.MODULE.ID'));

            $id = Request::segment(3);
            $actionMessage = trans('formbuilder::template.formbuilderModule.updateMessage');
            if (is_numeric($id)) { #Edit post Handler=======
                $formbuilderdata = FormBuilder::getRecordForLogById($id);
                $updatevideoGalleryFields = [];
                $updatevideoGalleryFields['varName'] = stripslashes(trim($postArr['title']));
                $updatevideoGalleryFields['txtLink'] = trim($postArr['link']);
                $updatevideoGalleryFields['fkIntImgId'] = !empty($postArr['img_id']) ? $postArr['img_id'] : null;
                $updatevideoGalleryFields['intSearchRank'] = $postArr['search_rank'];
                $updatevideoGalleryFields['dtDateTime'] = !empty($postArr['start_date_time']) ? date('Y-m-d H:i:s', strtotime($postArr['start_date_time'])) : date('Y-m-d H:i:s');
                $updatevideoGalleryFields['dtEndDateTime'] = !empty($postArr['end_date_time']) ? date('Y-m-d H:i:s', strtotime($postArr['end_date_time'])) : null;
                $updatevideoGalleryFields['UserID'] = auth()->user()->id;
                if ($postArr['chrMenuDisplay'] == 'D') {
                    $updatevideoGalleryFields['chrDraft'] = 'D';
                    $updatevideoGalleryFields['chrPublish'] = 'N';
                } else {
                    $updatevideoGalleryFields['chrDraft'] = 'N';
                    $updatevideoGalleryFields['chrPublish'] = $postArr['chrMenuDisplay'];
                }
                $whereConditions = ['id' => $id];

                if ($postArr['chrMenuDisplay'] == 'D') {
                    $addlog = Config::get('Constant.UPDATE_DRAFT');
                } else {
                    $addlog = '';
                }

                if (!$userIsAdmin) {
                    $userRole = $this->currentUserRoleData->id;
                } else {
                    $userRoleData = Role_user::getUserRoleByUserId($formbuilderdata->UserID);
                    if (isset($userRoleData->role_id)) {
                        $userRole = $userRoleData->role_id;
                    } else {
                        $userRole = $this->currentUserRoleData->id;
                    }
                }

                $workFlowByCat = Workflow::getRecordByCategoryId($module->intFkGroupCode, $userRole, Config::get('Constant.MODULE.ID'));

                if (empty($workFlowByCat->varUserId) || $userIsAdmin || $workFlowByCat->charNeedApproval == 'N') {
                    if ((int) $formbuilderdata->fkMainRecord === 0 || empty($workFlowByCat->varUserId)) {
                        $update = CommonModel::updateRecords($whereConditions, $updatevideoGalleryFields);
                        if ($update) {
                            if ($id > 0 && !empty($id)) {
                                self::swap_order_edit($postArr['order'], $id);

                                $logArr = MyLibrary::logData($id, false, $addlog);
                                if (Auth::user()->can('log-advanced')) {
                                    $newFormBuilderObj = FormBuilder::getRecordForLogById($id);
                                    $oldRec = $this->recordHistory($formbuilderdata);
                                    $newRec = $this->newrecordHistory($formbuilderdata, $newFormBuilderObj);
                                    $logArr['old_val'] = $oldRec;
                                    $logArr['new_val'] = $newRec;
                                }

                                $logArr['varName'] = trim($postArr['title']);
                                Log::recordLog($logArr);

                                if (Auth::user()->can('recent-updates-list')) {
                                    if (!isset($newFormBuilderObj)) {
                                        $newFormBuilderObj = FormBuilder::getRecordForLogById($id);
                                    }
                                    $notificationArr = MyLibrary::notificationData($id, $newFormBuilderObj);
                                    RecentUpdates::setNotification($notificationArr);
                                }
                                self::flushCache();
                                if (isset($postArr['saveandexit']) && $postArr['saveandexit'] == 'approvesaveandexit') {
                                    $actionMessage = trans('formbuilder::template.common.recordApprovalMessage');
                                } else {
                                    $actionMessage = trans('formbuilder::template.formbuilderModule.updateMessage');
                                }
                            }
                        }
                    } else {
                        $updateModuleFields = $updatevideoGalleryFields;
                        $this->insertApprovedRecord($updateModuleFields, $postArr, $id);
                        if (isset($postArr['saveandexit']) && $postArr['saveandexit'] == 'approvesaveandexit') {
                            $actionMessage = trans('formbuilder::template.common.recordApprovalMessage');
                        } else {
                            $actionMessage = trans('formbuilder::template.formbuilderModule.updateMessage');
                        }
                        $approval = $id;
                    }
                } else {
                    if ($workFlowByCat->charNeedApproval == 'Y') {
                        $approvalObj = $this->insertApprovalRecord($formbuilderdata, $postArr, $updatevideoGalleryFields);
                        if (isset($postArr['saveandexit']) && $postArr['saveandexit'] == 'approvesaveandexit') {
                            $actionMessage = trans('formbuilder::template.common.recordApprovalMessage');
                        } else {
                            $actionMessage = trans('formbuilder::template.formbuilderModule.updateMessage');
                        }
                        $approval = $approvalObj->id;
                    }
                }
            } else { #Add post Handler=======
                $workFlowByCat = Workflow::getRecordByCategoryId($module->intFkGroupCode, $this->currentUserRoleData->id, Config::get('Constant.MODULE.ID'));
                if (!empty($workFlowByCat->varUserId) && $workFlowByCat->chrNeedAddPermission == 'Y' && !$userIsAdmin) {
                    $formbuilderdataArr['chrPublish'] = 'N';
                    $formbuilderdataArr['chrDraft'] = 'N';
                    $formbuilderdataObj = $this->insertNewRecord($postArr, $formbuilderdataArr);
                    if ($postArr['chrMenuDisplay'] == 'D') {
                        $formbuilderdataArr['chrDraft'] = 'D';
                    }
                    $formbuilderdataArr['chrPublish'] = 'Y';
                    $approvalObj = $this->insertApprovalRecord($formbuilderdataObj, $postArr, $formbuilderdataArr);
                    $approval = $formbuilderdataObj->id;
                } else {
                    $formbuilderdataObj = $this->insertNewRecord($postArr, $formbuilderdataArr);
                }
                if (isset($postArr['saveandexit']) && $postArr['saveandexit'] == 'approvesaveandexit') {
                    $actionMessage = trans('formbuilder::template.common.recordApprovalMessage');
                } else {
                    $actionMessage = trans('formbuilder::template.formbuilderModule.addMessage');
                }
                $id = $formbuilderdataObj->id;
            }

            AddImageModelRel::sync(explode(',', $postArr['img_id']), $id, $approval);

            if ((!empty(Request::get('saveandexit')) && Request::get('saveandexit') == 'saveandexit') || !$userIsAdmin) {
                if ($postArr['chrMenuDisplay'] == 'D') {
                    return redirect()->route('powerpanel.formbuilder.index', 'tab=D')->with('message', $actionMessage);
                } else {
                    return redirect()->route('powerpanel.formbuilder.index')->with('message', $actionMessage);
                }
            } else {
                return redirect()->route('powerpanel.formbuilder.edit', $id)->with('message', $actionMessage);
            }
        } else {
            return Redirect::back()->withErrors($validator)->withInput();
        }
    }

    /**
     * This method destroys videoGallery in multiples
     * @return  videoGallery index view
     * @since   2016-10-25
     * @author  NetQuick
     */
    public function DeleteRecord(Request $request) {
        $value = Request::input('value');
        $data['ids'] = Request::input('ids');
        $update = MyLibrary::deleteMultipleRecords($data, false, $value,'Powerpanel\FormBuilder\Models\FormBuilder');
        self::flushCache();
        echo json_encode($update);
        exit;
    }

    /**
     * This method destroys videoGallery in multiples
     * @return  videoGallery index view
     * @since   2016-10-25
     * @author  NetQuick
     */
    public function publish(Request $request) {

        $alias = (int) Request::input('alias');
        $update = MyLibrary::setPublishUnpublish($alias, $request,'Powerpanel\FormBuilder\Models\FormBuilder');
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
        MyLibrary::swapOrder($order, $exOrder,'Powerpanel\FormBuilder\Models\FormBuilder');
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
            $response = MyLibrary::swapOrderAdd($order, $isCustomizeModule, $moduleHaveFields,'Powerpanel\FormBuilder\Models\FormBuilder');
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
        MyLibrary::swapOrderEdit($order, $id, $isCustomizeModule, $moduleHaveFields,'Powerpanel\FormBuilder\Models\FormBuilder');
        self::flushCache();
    }

    public function tableData($value , $permit, $currentUserID, $isAdmin) {
        // Checkbox
        $checkbox = view('powerpanel.partials.checkbox', ['name'=>'delete', 'value'=>$value->id])->render();
        
        
        // StartDate
        // $date = date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($value->created_at));
        $date = '<span align="left" data-bs-toggle="tooltip" data-bs-placement="bottom" title="'.date(Config::get("Constant.DEFAULT_DATE_FORMAT").' '.Config::get("Constant.DEFAULT_TIME_FORMAT"), strtotime($value->created_at)).'">'.date(Config::get('Constant.DEFAULT_DATE_FORMAT'), strtotime($value->created_at)).'</span>';

        // Title
        $title = $value->varName;
        if (Auth::user()->can('formbuilder-edit')) {
            $title = '<div class="quick_edit text-uppercase"><a href="' . route('powerpanel.formbuilder.edit', array('alias' => $value->id)) . '?tab=P" title="Edit">' . $value->varName . '</a></div>';
        }
        
        // Publish Action
        $publish_action = '';
        if ($value->chrAddStar != 'Y') {
            if ($value->chrDraft != 'D') {
                if ($permit['canformbuilderpublish']) {
                    if ($value->chrPublish == 'Y') {
                        $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/formbuilder', 'data_alias'=>$value->id, 'title'=>trans("formbuilder::template.common.publishedRecord"), 'data_value'=>'Unpublish', 'checked'=>'checked'])->render();
                    } else {
                        $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/formbuilder', 'data_alias'=>$value->id, 'title'=>trans("formbuilder::template.common.unpublishedRecord"), 'data_value'=>'Publish'])->render();
                    }
                } else {
                    $publish_action = "-";
                }
            } else {
                if ($permit['canformbuilderpublish']) {
                    if ($value->chrPublish == 'Y') {
                        $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/formbuilder', 'data_alias'=>$value->id, 'title'=>trans("formbuilder::template.common.publishedRecord"), 'data_value'=>'Unpublish', 'checked'=>'checked'])->render();
                    } else {
                        $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/formbuilder', 'data_alias'=>$value->id, 'title'=>trans("formbuilder::template.common.unpublishedRecord"), 'data_value'=>'Publish'])->render();
                    }
                } else {
                    $publish_action = "-";
                }
            }
        } else {
            if ($permit['canformbuilderpublish']) {
                $publish_action .= '<a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-toggle="tooltip" title="This blog is in Approval request so can&#39;t be Publish/Unpublish."><i style="color:red" class="ri-toggle-line icon-publish fs-24"></i></a>';
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
        
        // Details
        $details = '';
        $details .= '<div class="pro-act-btn">';
        $details .= '<a href="javascript:void(0)" class="" onclick="return hs.htmlExpand(this,{width:300,headingText:\'Email Information\',wrapperClassName:\'titlebar\',showCredits:false});"><span aria-hidden="true" class="icon-envelope"></span></a>';
        $details .= '<div class="highslide-maincontent">';
        $details .= '<b>Admin Email ID:</b> ' . $value->varEmail . '<br/>';
        $details .= '<b>Admin Email Subject:</b> ' . $value->varAdminSubject . '<br/>';
        $details .= '<b>Admin Email Content:</b> ' . $value->varAdminContent . '<br/>';
        if ($value->varUserSubject != '') {
            $details .= '<b>User Email Subject:</b> ' . $value->varUserSubject . '<br/>';
        }
        if ($value->varUserContent != '') {
            $details .= '<b>User Email Content:</b> ' . $value->varUserContent . '<br/>';
        }
        $details .= '</div>';
        $details .= '</div>';

        
        // User
        // if ($isAdmin) {
        //     $userdata = User::getUserId($value->UserID);
        //     $username = '(<em>Created by @' . $userdata->name . "</em>)";
        // } else {
        // }
        $username = '';

        
        
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
            'canedit'=> $permit['canformbuilderedit'],
            'candelete'=>$permit['canformbuilderdelete'],
            'canloglist'=>$permit['canloglist'],
            'value'=>$value,
            'chrIsAdmin' => $this->currentUserRoleData->chrIsAdmin,
            'module_name'=>'formbuilder',
                        'module_edit_url' => route('powerpanel.formbuilder.edit', array('alias' => $value->id)),
                        'module_type'=>'parent',
                        'viewlink' => isset($viewlink) ? $viewlink : "",
                        'linkviewLable' => isset($linkviewLable) ? $linkviewLable : "",
                        'logurl' => $logurl
                        ])->render();

                        if($permit['canformbuilderedit'] || $permit['canformbuilderdelete']){
                            $allActions = $allActions;
                        } else {
                            $allActions = "-";
                        }
                        
                        
                        $records = array(
            $checkbox,
            '<div class="pages_title_div_row"> <span class="title-txt"> ' . $title . ' ' . $status . $statusdata . ' - ' . $username . '</span></div>',
            // $details,
            isset($value->varEmail) ? $value->varEmail : "-",
            $publish_action,
            $date,
            $allActions
        );
        // echo "<pre>";print_r($records);die;
        return $records;
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
        $startDate = date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($data->dtDateTime));
        $endDate = !empty($data->dtEndDateTime) ? date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($data->dtEndDateTime)) : 'No Expiry';

        $returnHtml .= '<table class="new_table_desing table table-striped table-bordered table-hover">
				<thead>
					<tr>
						<th align="center">' . trans('formbuilder::template.common.title') . '</th>	
						<th align="center">Link</th>	
						<th align="center">Start Date</th>	
						<th align="center">End Date</th>	
						<th align="center">' . trans('formbuilder::template.common.displayorder') . '</th>
						<th align="center">' . trans("formbuilder::template.common.publish") . '</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td align="center" >' . stripslashes($data->varName) . '</td>	
						<td align="center">' . stripslashes($data->txtLink) . '</td>	
						<td align="center">' . $startDate . '</td>	
						<td align="center">' . $endDate . '</td>	
						<td align="center">' . ($data->intDisplayOrder) . '</td>
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
    public function newrecordHistory($data = false, $newdata = false) {
        if ($data->varName != $newdata->varName) {
            $titlecolor = 'style="background-color:#f5efb7"';
        } else {
            $titlecolor = '';
        }
        if ($data->txtLink != $newdata->txtLink) {
            $linkcolor = 'style="background-color:#f5efb7"';
        } else {
            $linkcolor = '';
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
        if ($data->intDisplayOrder != $newdata->intDisplayOrder) {
            $ordercolor = 'style="background-color:#f5efb7"';
        } else {
            $ordercolor = '';
        }
        if ($data->chrPublish != $newdata->chrPublish) {
            $Publishcolor = 'style="background-color:#f5efb7"';
        } else {
            $Publishcolor = '';
        }

        $startDate = date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($newdata->dtDateTime));
        $endDate = !empty($newdata->dtEndDateTime) ? date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($newdata->dtEndDateTime)) : 'No Expiry';

        $returnHtml = '';
        $returnHtml .= '<table class="new_table_desing table table-striped table-bordered table-hover">
				<thead>
					<tr>
						<th align="center">' . trans('formbuilder::template.common.title') . '</th>	
						<th align="center">Link</th>	
						<th align="center">Start Date</th>	
						<th align="center">End Date</th>	
						<th align="center">' . trans('formbuilder::template.common.displayorder') . '</th>
						<th align="center">' . trans("formbuilder::template.common.publish") . '</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td align="center" ' . $titlecolor . '>' . stripslashes($newdata->varName) . '</td>	
						<td align="center" ' . $linkcolor . '>' . stripslashes($newdata->txtLink) . '</td>	
						<td align="center" ' . $sdatecolor . '>' . $startDate . '</td>	
						<td align="center" ' . $edatecolor . '>' . $endDate . '</td>	
						<td align="center" ' . $ordercolor . '>' . ($newdata->intDisplayOrder) . '</td>
						<td align="center" ' . $Publishcolor . '>' . $newdata->chrPublish . '</td>
					</tr>
				</tbody>
			</table>';

        return $returnHtml;
    }

    public static function flushCache() {
        Cache::tags('FormBuilder')->flush();
    }

    public function get_buider_list() {
        $filter = Request::post();
        $rows = '';
        $filterArr = [];
        $records = [];
        $filterArr['orderByFieldName'] = isset($filter['columns']) ? $filter['columns'] : '';
        $filterArr['orderTypeAscOrDesc'] = isset($filter['order']) ? $filter['order'] : '';
        $filterArr['critaria'] = isset($filter['critaria']) ? $filter['critaria'] : '';
        $filterArr['searchFilter'] = isset($filter['searchValue']) ? trim($filter['searchValue']) : '';
        $filterArr['iDisplayStart'] = isset($filter['start']) ? intval($filter['start']) : 1;
        $filterArr['iDisplayLength'] = isset($filter['length']) ? intval($filter['length']) : 5;
        $filterArr['ignore'] = !empty($filter['ignore']) ? $filter['ignore'] : [];
        $filterArr['selected'] = isset($filter['selected']) && !empty($filter['selected']) ? $filter['selected'] : [];
        $arrResults = FormBuilder::getBuilderRecordList($filterArr);

        $found = $arrResults->toArray();
        if (!empty($found)) {
            foreach ($arrResults as $key => $value) {
                $rows .= $this->tableDataBuilder($value, false, $filterArr['selected']);
            }
        } else {
            $rows .= '<tr id="not-found"><td colspan="4" align="center">No records found.</td></tr>';
        }
        $iTotalRecords = CommonModel::getTotalRecordCount(false, true);
        $records["data"] = $rows;
        $records["found"] = count($found);
        $records["recordsTotal"] = $iTotalRecords;
        return json_encode($records);
    }

    public function tableDataBuilder($value = false, $fcnt = false, $selected = []) {


        $publish_action = '';
        $dtFormat = Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT');
        if ($value->dtEndDateTime != '') {
            $dateRange = date($dtFormat, strtotime($value->dtDateTime)) . ' - ' . date($dtFormat, strtotime($value->dtEndDateTime));
        } else {
            $dateRange = date($dtFormat, strtotime($value->dtDateTime));
        }
        $record = '<tr ' . (in_array($value->id, $selected) ? 'class="selected-record"' : '') . '>';
        $record .= '<td width="1%" align="center">';
        $record .= '<label class="mt-checkbox mt-checkbox-outline">';
        $record .= '<input type="checkbox" data-title="' . $value->varName . '" name="delete[]" class="chkChoose" ' . (in_array($value->id, $selected) ? 'checked' : '') . ' value="' . $value->id . '">';
        $record .= '<span></span>';
        $record .= '</label>';
        $record .= '</td>';
        $record .= '<td width="32.33%" align="left">';
        $record .= $value->varName;
        $record .= '</td>';
        $record .= '<td width="32.33%" align="center">';
        $record .= '<img src="' . resize_image::resize($value->fkIntImgId, 50, 50) . '">';
        $record .= '</td>';
        $record .= '<td width="32.33%" align="center">';
        $record .= $dateRange;
        $record .= '</td>';
        $record .= '</tr>';
        return $record;
    }

    public function GetFormBuilderData() {
        $requestArr = Request::all();
        $request = (object) $requestArr;
        $data = Request::all();

        if (isset($_REQUEST)) {
            $builderDataArr = [];
            if ($data['check_user'] == 'Y') {
                $usersubject = $data['user_subject'];
                $usercontent = $data['user_content'];
            } else {
                $usersubject = '';
                $usercontent = '';
            }
            $builderDataArr['varName'] = stripslashes(trim($data['formtitle']));
            $builderDataArr['FormTitle'] = $data['formtitle1'];
            $builderDataArr['Description'] = $data['formdesc'];
            $builderDataArr['fkIntImgId'] = $data['img_id'];
            $builderDataArr['varFormDescription'] = $data['fromdata'];
            $builderDataArr['varEmail'] = $data['email'];
            $builderDataArr['varAdminSubject'] = $data['subject'];
            $builderDataArr['varAdminContent'] = $data['content'];
            $builderDataArr['varThankYouMsg'] = $data['thankyoumsg'];
            $builderDataArr['chrCheckUser'] = $data['check_user'];
            $builderDataArr['varUserSubject'] = $usersubject;
            $builderDataArr['varUserContent'] = $usercontent;
            $builderDataArr['UserID'] = auth()->user()->id;
            $builderDataArr['chrPublish'] = "Y";
            $builderDataArr['chrDelete'] = "N";
            $builderDataArr['created_at'] = date('Y-m-d H:i');
            $builderDataArr['updated_at'] = date('Y-m-d H:i');

            $formBuildID = CommonModel::addRecord($builderDataArr,'Powerpanel\FormBuilder\Models\FormBuilder');
            $formdata = 'Form Added';
            $logArr = MyLibrary::logData($formBuildID, Config::get('Constant.MODULE.ID'), $formdata);
            $logArr['varTitle'] = stripslashes($data['formtitle']);
            Log::recordLog($logArr);
            echo $formBuildID;
        }
    }

    public function GetUpdateFormBuilderData() {
        $requestArr = Request::all();
        $request = (object) $requestArr;
        $data = Request::all();
        if (isset($_REQUEST)) {
            $builderDataArr = [];
            if ($data['check_user'] == 'Y') {
                $usersubject = $data['user_subject'];
                $usercontent = $data['user_content'];
            } else {
                $usersubject = '';
                $usercontent = '';
            }
            $builderDataArr['varName'] = stripslashes(trim($data['formtitle']));
            $builderDataArr['FormTitle'] = $data['formtitle1'];
            $builderDataArr['Description'] = $data['formdesc'];
            $builderDataArr['fkIntImgId'] = $data['img_id'];
            $builderDataArr['varFormDescription'] = $data['fromdata'];
            $builderDataArr['varEmail'] = $data['email'];
            $builderDataArr['varAdminSubject'] = $data['subject'];
            $builderDataArr['varAdminContent'] = $data['content'];
            $builderDataArr['varThankYouMsg'] = $data['thankyoumsg'];
            $builderDataArr['chrCheckUser'] = $data['check_user'];
            $builderDataArr['varUserSubject'] = $usersubject;
            $builderDataArr['varUserContent'] = $usercontent;
            $builderDataArr['UserID'] = auth()->user()->id;
            $builderDataArr['chrPublish'] = "Y";
            $builderDataArr['chrDelete'] = "N";
            $builderDataArr['created_at'] = date('Y-m-d H:i');
            $builderDataArr['updated_at'] = date('Y-m-d H:i');
            $whereConditions = ['id' => $data['eid']];
            CommonModel::updateRecords($whereConditions, $builderDataArr, false,'Powerpanel\FormBuilder\Models\FormBuilder');
            $formdata = 'Form Edit';
            $logArr = MyLibrary::logData($data['eid'], Config::get('Constant.MODULE.ID'), $formdata);
            $logArr['varTitle'] = stripslashes($data['formtitle']);
            Log::recordLog($logArr);
        }
    }

}
