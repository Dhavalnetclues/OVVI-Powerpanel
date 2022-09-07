<?php

namespace Powerpanel\ShieldCMSTheme\Controllers\Powerpanel\dashboard;

use App\CommonModel;
use App\Dashboard;
use App\DashboardOrder;
use App\FeedbackLead;
use App\GlobalSearch;
use App\Helpers\MyLibrary;
use App\Http\Controllers\PowerpanelController;
use App\Log;
use App\Modules;
use App\Pagehit;
use App\SubmitTickets;
use App\User;
use App\UserNotification;
use Auth;
use Config;
use File;
use Powerpanel\DocumentReport\Models\DocumentsReport;
use Powerpanel\ContactUsLead\Models\ContactLead;
use Powerpanel\ComplaintLead\Models\ComplaintLead;
use Powerpanel\Events\Models\EventLead;
use Powerpanel\Payonline\Models\Payonline;
use Powerpanel\NewsletterLead\Models\NewsletterLead;
use Powerpanel\FormBuilderLead\Models\FormBuilderLead;
use Powerpanel\OnlinePolling\Models\PollLead;
use Powerpanel\Department\Models\Department;
use Powerpanel\RoleManager\Controllers\Powerpanel\RoleController;
use Powerpanel\RoleManager\Models\Permission_role;
use Powerpanel\RoleManager\Models\Role;
use Powerpanel\Workflow\Models\Comments;
use Powerpanel\Workflow\Models\Workflow;
use Powerpanel\Workflow\Models\WorkflowLog;
use Powerpanel\FormBuilder\Models\FormBuilder;
use Request;
use Powerpanel\FormBuilderLead\Controllers\Powerpanel\FormBuilderLeadController;

class DashboardController extends PowerpanelController
{
    /*
    |--------------------------------------------------------------------------
    | Dashboard Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling dashboard stats.
    |
    |
    |
     */

    /**
     * Create a new Dashboard controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
        if (isset($_COOKIE['locale'])) {
            app()->setLocale($_COOKIE['locale']);
        }
    }

    public function index()
    {
    	$userId = Auth::id();
        $userIsAdmin = false;
        if (isset($this->currentUserRoleData) && !empty($this->currentUserRoleData)) {
            if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                $userIsAdmin = true;
            }
        } else {
            $userIsAdmin = true;
        }
        $this->breadcrumb['title'] = 'Dashboard';
        $breadcrumb = $this->breadcrumb;
        #Doc Chart======================================
        // $docChartData = DocumentsReport::getDocChartData();
        #/.Doc Chart====================================
        #Hits Chart======================================
        // $hits_web_mobile = $this->getPageHitChart();
        
        // $hitsCount = json_decode($hits_web_mobile);
        // $web_hits = $hitsCount->web_hits;
        // $mobile_hits = $hitsCount->mobile_hits;
				#/.Lead Chart====================================
        // $leadsChart = $this->LeadChart();
        #/.Lead Chart====================================       

        $leads = ContactLead::getRecordList();
        $leadsCount = ContactLead::getRecordCount();
        // if (File::exists(app_path() . '/Workflow.php') != null || File::exists(base_path() . '/packages/Powerpanel/Workflow/src/Models/Workflow.php') != null) {
            $approvals = WorkflowLog::getApprovalListDashbord();
        //     $moduleInformation = array();
        //     $recorduserinfo = array();
        //     $recordRepeatInfo = array();
        //     foreach ($approvals as $key => $approval) {

        //         if (!isset($moduleInformation[$approval->fkModuleId]) && empty($moduleInformation[$approval->fkModuleId])) {
                    // $module = Modules::getModuleById($approval->fkModuleId);
        //             $moduleInformation[$approval->fkModuleId] = $module;
        //         } else {
        //             $module = $moduleInformation[$approval->fkModuleId];
        //         }

        //         if(!empty($module))
        //         {
        //             $modelNameSpace = '---';
        //             if (isset($module->varModuleNameSpace) && $module->varModuleNameSpace != '') {
        //                 $modelNameSpace = $module->varModuleNameSpace . 'Models\\' . $module->varModelName;
        //             } else {
        //                 $modelNameSpace = '\\App\\' . $module->varModelName;
        //             }

        //             $approval->moduleName = $module->varTitle;
        //             $approval->varModuleName = $module->varModuleName;
        //             if (!isset($recordRepeatInfo[$module->varModuleName][$approval->fkRecordId]) && empty($recordRepeatInfo[$module->varModuleName][$approval->fkRecordId])) {
        //                 $record = CommonModel::getCronRecord($modelNameSpace, $approval->fkRecordId, 'approvals');
        //                 $recordRepeatInfo[$module->varModuleName][$approval->fkRecordId] = $record;
        //             } else {
        //                 $record = $recordRepeatInfo[$module->varModuleName][$approval->fkRecordId];
        //             }
        //         }
        //     }
        // }

        $filterArr = $formBuilderLead = [];
        $filterArr['orderByFieldName'] = 'created_at';
        $filterArr['orderTypeAscOrDesc'] = 'DESC';
        $filterArr['iDisplayLength'] = intval(3);
        $filterArr['iDisplayStart'] = intval(0);

        // $formBuilderLeadList = OrderLead::getRecordList($filterArr);
        // $formBuilderLeadCount = OrderLead::getRecordCount();
        
        // if (!empty($formBuilderLeadList)) {
        //     foreach ($formBuilderLeadList as $key => $value) {
        //     	$formExist = FormBuilder::getRecordById($value->fk_formbuilder_id);
        //     	if(!empty($formExist)){
        //     		$formBuilderLead[$key] = FormBuilderLeadController::tableData($value, 'dashboard');
        //         $formBuilderLead[$key][0] = $value->id;
        //     	}
                
        //     }
        // }
        // $feedBackleadsCount = FeedbackLead::getCountForDashboardLeadList();        
        $dashboardWidgetArray = array(
            'widget_download' => array('widget_name' => 'Document Views & Downloads', 'widget_id' => "widget_download", 'widget_display' => 'Y'),
            'widget_leadstatistics' => array('widget_name' => 'Leads Statistics', 'widget_id' => "widget_leadstatistics", 'widget_display' => 'Y'),
            'widget_liveusercountry' => array('widget_name' => 'Live Users By Country', 'widget_id' => "widget_liveusercountry", 'widget_display' => 'Y'),
            'widget_conatctleads' => array('widget_name' => 'Contact Leads', 'widget_id' => "widget_conatctleads", 'widget_display' => 'Y'),
            'widget_inapporval' => array('widget_name' => 'In Approval', 'widget_id' => "widget_inapporval", 'widget_display' => 'Y'),
            'widget_formbuilderleads' => array('widget_name' => 'Form Builder Leads', 'widget_id' => "widget_formbuilderleads", 'widget_display' => 'Y'),
        );
        $dashboardWidgetSettingsData = DashboardOrder::dashboardWidgetSettings($userId);
        $dashboardWidgetSettings = array();
        if (isset($dashboardWidgetSettingsData->txtWidgetSetting) && !empty($dashboardWidgetSettingsData->txtWidgetSetting)) {
            $dashboardWidgetSettings = json_decode($dashboardWidgetSettingsData->txtWidgetSetting);
        } else {
            if (!$userIsAdmin) {
                $nonadminWidgetArray = array(
                    'widget_download' => array('widget_name' => 'Document Views & Downloads', 'widget_id' => "widget_download", 'widget_display' => 'Y'),
                    'widget_leadstatistics' => array('widget_name' => 'Leads Statistics', 'widget_id' => "widget_leadstatistics", 'widget_display' => 'Y'),
                    'widget_liveusercountry' => array('widget_name' => 'Live Users By Country', 'widget_id' => "widget_liveusercountry", 'widget_display' => 'Y'),
                );
                $whereConditions = ['UserID' => $userId];
                $update = [
                    'txtWidgetSetting' => json_encode($nonadminWidgetArray),
                ];
                CommonModel::updateRecords($whereConditions, $update, false, 'App\DashboardOrder');
                $dashboardWidgetSettings = json_encode($nonadminWidgetArray);
                $dashboardWidgetSettings = json_decode($dashboardWidgetSettings);
            } else {
                $whereConditions = ['UserID' => $userId];
                $update = ['txtWidgetSetting' => json_encode($dashboardWidgetArray)];
                CommonModel::updateRecords($whereConditions, $update, false, 'App\DashboardOrder');
            }
        }

        return view('shiledcmstheme::powerpanel.dashboard.dashboard', compact('leads', 'leadsCount', 'approvals', 'breadcrumb', 'formBuilderLead', 'dashboardWidgetSettings'));
    }

    public function ajaxcall()
    {
        $data = Request::all();
        switch ($data['type']) {
            case 'contactuslead':
                $contactusleadID = $data['id'];
                $contactusLeadRecord = ContactLead::getRecordById($contactusleadID);
                if (File::exists(base_path() . '/packages/Powerpanel/Department/src/Models/Department.php')) {
                    if(isset(Department::getRecordforEmailById($contactusLeadRecord->fkIntDepartmentId)['varTitle']) && (!empty(Department::getRecordforEmailById($contactusLeadRecord->fkIntDepartmentId)['varTitle']))){
                        $contactusLeadRecord->DepartmentName = Department::getRecordforEmailById($contactusLeadRecord->fkIntDepartmentId)['varTitle'];
                    }else{
                        $contactusLeadRecord->DepartmentName = '';
                    }
                } else {
                    $contactusLeadRecord->DepartmentName = '';
                }
                $contactusLeadRecord->varEmail = MyLibrary::getDecryptedString($contactusLeadRecord->varEmail);
                $contactusLeadRecord->varPhoneNo = MyLibrary::getDecryptedString($contactusLeadRecord->varPhoneNo);
                $contactusLeadRecord->txtUserMessage = MyLibrary::getDecryptedString($contactusLeadRecord->txtUserMessage);
                echo json_encode($contactusLeadRecord);
                break;
            case 'liveusers':
            	$liveUsers = \App\LiveUsers::getChartUsers();
							$markers = []; $lines = [];
          		if(!empty($liveUsers)){
								$markers[]=["name"=>"Center", "coords"=>[19.292997,-81.366806]];
								foreach ($liveUsers as $lu) {
								$markers[]=["name"=>$lu->varCity, "coords"=>[(float)$lu->varLatitude,(float)$lu->varLongitude]];
								$lines[] = ["from"=>$lu->varCity, "to"=>"Center"];
								}
							}
							echo json_encode(["markers"=>$markers, "lines"=>$lines]);
            break;
            case 'activity':            	
            	$activities = Log::getRecords('activity')->toArray();
            	$rows = [];
            	foreach ($activities as $value) {
            		$value['varAction'] = ucfirst($value['varAction']);
            		$dt = strtotime($value['created_at']);
            		if(date('Y-m-d',$dt) == date('Y-m-d')){
            			$value['created_at'] = 'Today at '.date('H:i A',$dt);
            		}else{
            			$value['created_at'] = date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($value['created_at']));
            		}
            		$rows[] = $value; 
            	}
            	echo json_encode($rows);
            	break;
            default:
                echo "error";
                break;
        }
    }

    public function widgetSettings()
    {
        $userIsAdmin = false;
        if (isset($this->currentUserRoleData) && !empty($this->currentUserRoleData)) {
            if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                $userIsAdmin = true;
            }
        } else {
            $userIsAdmin = true;
        }
        $dashboardWidgetArray = array(
            'widget_download' => array('widget_name' => 'Document Views & Downloads', 'widget_id' => "widget_download", 'widget_display' => 'Y'),
            'widget_leadstatistics' => array('widget_name' => 'Leads Statistics', 'widget_id' => "widget_leadstatistics", 'widget_display' => 'Y'),
            'widget_liveusercountry' => array('widget_name' => 'Live Users By Country', 'widget_id' => "widget_liveusercountry", 'widget_display' => 'Y'),
            'widget_conatctleads' => array('widget_name' => 'Contact Leads', 'widget_id' => "widget_conatctleads", 'widget_display' => 'Y'),
            'widget_inapporval' => array('widget_name' => 'In Approval', 'widget_id' => "widget_inapporval", 'widget_display' => 'Y'),
            'widget_formbuilderleads' => array('widget_name' => 'Form Builder Leads', 'widget_id' => "widget_formbuilderleads", 'widget_display' => 'Y'),
        );
        $dashboardWidgetSettingsData = DashboardOrder::dashboardWidgetSettings(auth()->user()->id);
        if (isset($dashboardWidgetSettingsData->txtWidgetSetting) && !empty($dashboardWidgetSettingsData->txtWidgetSetting)) {
            $dashboardWidgetSettings = $dashboardWidgetSettingsData->txtWidgetSetting;
        } else {
            if (!$userIsAdmin) {
                $nonadminWidgetArray = array(
                    'widget_download' => array('widget_name' => 'Document Views & Downloads', 'widget_id' => "widget_download", 'widget_display' => 'Y'),
                    'widget_leadstatistics' => array('widget_name' => 'Leads Statistics', 'widget_id' => "widget_leadstatistics", 'widget_display' => 'Y'),
                    'widget_liveusercountry' => array('widget_name' => 'Live Users By Country', 'widget_id' => "widget_liveusercountry", 'widget_display' => 'Y'),
                );
                // 'widget_commentuser' => array('widget_name' => 'Comments For user', 'widget_id' => "widget_commentuser", 'widget_display' => 'Y'),
                // 'widget_recentactivity' => array('widget_name' => 'Recent Activity', 'widget_id' => "widget_recentactivity", 'widget_display' => 'Y'),

                $whereConditions = ['UserID' => auth()->user()->id];
                $update = [
                    'txtWidgetSetting' => json_encode($nonadminWidgetArray),
                ];
                CommonModel::updateRecords($whereConditions, $update, false, 'App\DashboardOrder');
                $dashboardWidgetSettings = json_encode($nonadminWidgetArray);
                // $dashboardWidgetSettings = json_decode($dashboardWidgetSettings);
            } else {
                $whereConditions = ['UserID' => auth()->user()->id];
                $update = ['txtWidgetSetting' => json_encode($dashboardWidgetArray)];
                CommonModel::updateRecords($whereConditions, $update, false, 'App\DashboardOrder');
                $dashboardWidgetSettings = json_encode($dashboardWidgetArray);
            }
        }

        echo $dashboardWidgetSettings;
        exit;
    }

    public function Get_Comments_user(Request $request)
    {
        $requestArr = Request::all();
        $request = (object) $requestArr;
        $templateData = Dashboard::get_comments_user($request);
        $Comments = "";
        if (count($templateData) > 0) {
            foreach ($templateData as $row_data) {
                if ($row_data->Fk_ParentCommentId == 0) {
                    $Comments .= '<li><p>' . nl2br($row_data->varCmsPageComments) . '</p><span class = "date">' . CommonModel::getUserName($row_data->intCommentBy) . ' ' . date('M d Y h:i A', strtotime($row_data->created_at)) . '</span></li>';
                    $UserComments = Dashboard::get_usercomments($row_data->id);
                    foreach ($UserComments as $row_comments) {
                        $Comments .= '<li class = "user-comments"><p>' . nl2br($row_comments->varCmsPageComments) . '</p><span class = "date">' . CommonModel::getUserName($row_comments->UserID) . ' ' . date('M d Y h:i A', strtotime($row_comments->created_at)) . '</span></li>';
                    }
                }
            }
        } else {
            $Comments .= '<li><p>No Comments yet.</p></li>';
        }
        echo $Comments;
        exit;
    }

    public function InsertComments_user(Request $request)
    {
        $requestArr = Request::all();
        $request = (object) $requestArr;
        $commentModuleData = Modules::getModuleByModelName(Request::post('varModuleNameSpace'));

        if ($commentModuleData['varModuleNameSpace'] != '') {
            $modelNameSpace = $commentModuleData['varModuleNameSpace'] . 'Models\\' . $commentModuleData['varModelName'];
        } else {
            $modelNameSpace = '\\App\\' . Request::post('varModuleNameSpace');
        }
        $Comments_data['Fk_ParentCommentId'] = Request::post('id');
        $Comments_data['intRecordID'] = Request::post('intRecordID');
        $Comments_data['fkMainRecord'] = Request::post('fkMainRecord');
        $Comments_data['varModuleNameSpace'] = Request::post('varModuleNameSpace');
        $Comments_data['varNameSpace'] = $modelNameSpace;
        $Comments_data['varCmsPageComments'] = Request::post('CmsPageComments_user');
        $Comments_data['UserID'] = auth()->user()->id;
        $Comments_data['intCommentBy'] = auth()->user()->id;
        $Comments_data['varModuleTitle'] = Request::post('varModuleTitle');
        Comments::insertComents($Comments_data);
        /* code for insert comment */
        $parentCommentId = Request::post('id');
        $parentCommentData = Comments::get_commentDetailForNotificationById($parentCommentId);
        $parentCommentedUserId = 0;
        if (!empty($parentCommentData)) {
            $parentCommentedUserId = $parentCommentData->intCommentBy;
        }

        $commentModuleId = 0;
        if (!empty($commentModuleData)) {
            $commentModuleId = $commentModuleData->id;
        }
        if ($commentModuleId > 0) {
            $userNotificationArr = MyLibrary::userNotificationData($commentModuleId);
            $userNotificationArr['fkRecordId'] = Request::post('intRecordID');
            $userNotificationArr['txtNotification'] = ucfirst(auth()->user()->name) . ' has replied on your comment ' . '(' . ucfirst(Request::post('varModuleTitle')) . ')';
            $userNotificationArr['fkIntUserId'] = auth()->user()->id;
            $userNotificationArr['chrNotificationType'] = 'C';
            $userNotificationArr['intOnlyForUserId'] = $parentCommentedUserId;
            UserNotification::addRecord($userNotificationArr);
            $commentdata = Config::get('Constant.REPLIED_COMMENT_ADDED');
            $newCmsPageObj = $modelNameSpace::getRecordForLogById(Request::post('intRecordID'));
            $logArr = MyLibrary::logData(Request::post('intRecordID'), $commentModuleId, $commentdata);
            $logArr['varTitle'] = stripslashes($newCmsPageObj->varTitle);
            Log::recordLog($logArr);
        }
        exit;
    }

    public function updateorder(Request $request)
    {
        $Allorder = Request::post('order');
        DashboardOrder::UpdateDisplayOrder($Allorder, auth()->user()->id);
    }

    public function updatedashboardsettings(Request $request)
    {
        $widget_key = Request::post('widgetkey');
        $widget_disp = Request::post('widget_disp');
        $UserId = auth()->user()->id;
        $dashboardWidgetSettingsData = DashboardOrder::dashboardWidgetSettings($UserId);
        $dashboardWidgetSettings = array();
        if (!empty($dashboardWidgetSettingsData)) {
            $dashboardWidgetSettings = json_decode($dashboardWidgetSettingsData->txtWidgetSetting, true);
            $dashboardWidgetSettings[$widget_key]['widget_display'] = $widget_disp;
            $updatedjson = json_encode($dashboardWidgetSettings);
            $whereConditions = ['UserID' => $UserId];
            $update = [
                'txtWidgetSetting' => $updatedjson,
            ];
            CommonModel::updateRecords($whereConditions, $update, false, 'App\DashboardOrder');
        }
        exit;
    }

    public static function workflowFunctions()
    {
        #Workflow funcions======================================
        $availableWorkFlows = Workflow::getApprovalWorkFlowsDashboard();
        $wf_moduleInformation = array();
        $wf_admusers = array();
        foreach ($availableWorkFlows as $wf) {
            $wf->varUserId = str_replace('1', '0', $wf->varUserId); //ignoring super admin

            if (!isset($wf_admusers[$wf->varUserId]) && empty($wf_admusers[$wf->varUserId])) {
                if (!empty($wf->varUserId)) {
                    $wf->adminusers = User::getRecordByIdIn(explode(', ', $wf->varUserId))->toArray();
                    if (!empty($wf->adminusers)) {
                        $wf_admusers[$wf->varUserId] = $wf->adminusers;
                    }
                } else {
                    $wf->adminusers = array();
                    $wf_admusers[$wf->varUserId] = $wf->adminusers;
                }
            } else {
                $wf->adminusers = $wf_admusers[$wf->varUserId];
            }

            if (!isset($wf_moduleInformation[$wf->intModuleId]) && empty($wf_moduleInformation[$wf->intModuleId])) {
                $moduledata = Modules::getModuleById($wf->intModuleId);
                $wf_moduleInformation[$wf->intModuleId] = $moduledata;
            } else {
                $moduledata = $wf_moduleInformation[$wf->intModuleId];
            }

            if (!empty($moduledata)) {
                $wf->moduleTitle = $moduledata->varTitle;
            } else {
                $wf->moduleTitle = ' ---';
            }
        }
        $nonAndminRoles = Role::getNonAdmins();
        $moduleCategory = RoleController::groups();
        $pendingRoleWF = [];
        foreach ($nonAndminRoles as $key => $nonAndminRole) {
            $roleCan = RoleController::groups($nonAndminRole->id);
            $roleCan1 = Permission_role::getPermissionRole($nonAndminRole->id);
            $roleCan1 = array_column($roleCan1, 'permission_role');
            $userroleassignedmodules = array_column($roleCan1, 'intFKModuleCode');
            foreach ($moduleCategory as $key => $category) {
                if ($category != 'Logs' && $category != 'User Management' && $category != 'Leads' && $category != 'Miscellaneous') {
                    $modules = Modules::getModulesBycategory($key);
                    $useraddeditModules = array();
                    foreach ($roleCan1 as $rolval) {
                        if ($rolval['display_name'] == "per_add" || $rolval['display_name'] == "per_edit") {
                            array_push($useraddeditModules, $rolval['intFKModuleCode']);
                        }
                    }
                    if (!empty($modules)) {
                        foreach ($modules as $module) {
                            if (in_array($module->id, $useraddeditModules)) {
                                if (in_array($category, $roleCan)) {
                                    $whereArray = [
                                        'varUserRoles' => $nonAndminRole->id,
                                        //'intCategoryId' => $key,
                                        'intModuleId' => $module->id,
                                    ];
                                    $add = Workflow::getPendingWorkFlows($whereArray);
                                    $whereArray = [
                                        'varUserRoles' => $nonAndminRole->id,
                                        //'intCategoryId' => $key,
                                        'intModuleId' => $module->id,
                                        'charNeedApproval' => 'N',
                                        'chrNeedAddPermission' => 'N',
                                    ];
                                    $directApproved = Workflow::getPendingWorkFlows($whereArray);
                                    if (empty($add)) {
                                        $addWfArr = [
                                            'category' => $category,
                                            'role' => $nonAndminRole->display_name,
                                            'modulename' => $module->varTitle,
                                            'action' => 'Add/Update',
                                        ];
                                        if (isset($add->id)) {
                                            $addWfArr['id'] = $add->id;
                                        }
                                        if (empty($directApproved)) {
                                            $pendingRoleWF[] = $addWfArr;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        $pendingRefine = [];
        foreach ($pendingRoleWF as $pwf) {
            $actionArr = [];
            $actionArr['action'] = $pwf['action'];
            $actionArr['modulename'] = $pwf['modulename'];
            if (isset($pwf['id'])) {
                $actionArr['id'] = $pwf['id'];
            }
            //$pendingRefine[$pwf['role']]['category'][$pwf['category']][] = $actionArr;
            $pendingRefine[$pwf['role']][] = $actionArr;

        }
        $response = [
            'availableWorkFlows' => $availableWorkFlows,
            'pendingRoleWF' => $pendingRefine,
        ];
        return $response;
        #./Workflow funcions====================================
    }

    public function getPageHitChart()
    {
        // $filter = Request::post();
        $hits_web = Pagehit::getHitsWebMobileHitsyears('Y');
        $mobile_web = Pagehit::getHitsWebMobileHitsyears('N');

        $hitsChartArr['web_hits'] = $hits_web;
        $hitsChartArr['mobile_hits'] = $mobile_web;

        $hits_web_mobile = json_encode($hitsChartArr);
        return $hits_web_mobile;
    }

    public function LeadChart()
    {
        $filter = Request::post();
        $year = isset($filter['year']) ? $filter['year'] : 0;
        $year = date('Y', strtotime('-'.$year.' years'));

        $labels = ['Event', 'Newsletter', 'Form Builder','Contact'];

        $Contactleads = ContactLead::getRecordListDashboard($year);
        $Feedbackleads = FeedbackLead::getRecordListDashboard($year);
        //$complaintLead = ComplaintLead::getDashboardReport($year);
        $eventLead = EventLead::getDashboardReport($year);
        //$payonlineLead = Payonline::getDashboardReport($year);
        $newsletterLead = NewsletterLead::getDashboardReport($year);
        $formBuilderLead = OrderLead::getDashboardReport($year);
        //$pollLead = PollLead::getDashboardReport($year);

        $leadsReport = [$eventLead, $newsletterLead, $formBuilderLead, $Contactleads];

        $dataArr = [$labels, $leadsReport];
        $docChartData = json_encode($dataArr);
        return $docChartData;
    }

    public function SearchChart()
    {
        $final_array = array();
        $filter = Request::post();
        $year = isset($filter['year']) ? $filter['year'] : 4;
        $timeparam = isset($filter['timeparam']) ? $filter['timeparam'] : 'year';
        $dataArr = array();
        $searchDara = GlobalSearch::getRecordListDashboard($year, $timeparam);
        foreach ($searchDara as $key => $value) {
            $final_array[$value['Year']] = (!empty($value['SearchCount'])) ? $value['SearchCount'] : 0;
        }
        $chartArr[] = ['Year', 'Hits'];
        foreach ($final_array as $key => $value) {
            $chartArr[] = [
                (string) $key,
                (int) $value,
            ];
        }
        $searchChartData = $chartArr;
        $searchChartData = json_encode($searchChartData);
        return $searchChartData;
    }

}
