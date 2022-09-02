<?php

namespace Powerpanel\Service\Controllers\Powerpanel;

use App\Alias;
use App\CommonModel;
use App\Document;
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
use Powerpanel\CmsPage\Models\CmsPage;
use Powerpanel\ServiceCategory\Models\ServiceCategory;
use Powerpanel\Service\Models\Service;
use Powerpanel\RegisterApplication\Models\RegisterApplication;
use Powerpanel\LicenceRegister\Models\LicenceRegister;
use Powerpanel\RoleManager\Models\Role_user;
use Powerpanel\Workflow\Models\Comments;
use Powerpanel\Workflow\Models\Workflow;
use Powerpanel\Workflow\Models\WorkflowLog;
use Request;
use Validator;

class ServiceController extends PowerpanelController {

    public $catModule;

    public function __construct() {
        parent::__construct();
        if (isset($_COOKIE['locale'])) {
            app()->setLocale($_COOKIE['locale']);
        }
        $this->MyLibrary = new MyLibrary();
        $this->CommonModel = new CommonModel();
        $this->Alias = new Alias();
    }

    /**
     * This method handels load process of service
     * @return  View
     * @since   2017-11-10
     * @author  NetQuick
     */
    public function index() {
        $userIsAdmin = false;
        if (isset($this->currentUserRoleData) && !empty($this->currentUserRoleData)) {
            if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                $userIsAdmin = true;
            }
        }

        $admin = $this->currentUserRoleData->varSector;
        $iTotalRecords = Service::getRecordCount(false, false, $userIsAdmin, $this->currentUserRoleSector);
        $approvalTotalRecords = Service::getRecordCountListApprovalTab(false, false, $userIsAdmin, array(), $this->currentUserRoleSector);
        $draftTotalRecords = Service::getRecordCountforListDarft(false, true, $userIsAdmin, array(), $this->currentUserRoleSector);
        $trashTotalRecords = Service::getRecordCountforListTrash(false,false,$userIsAdmin,[],$this->currentUserRoleSector);
        $favoriteTotalRecords = Service::getRecordCountforListFavorite(false, false, $userIsAdmin, [], $this->currentUserRoleSector);

        if (isset($userIsAdmin) && $userIsAdmin == 'true') {
            $categories = ServiceCategory::getCatWithParent(false,false);
        } else {
             $categories = ServiceCategory::getCatWithParent(false,$admin);
        }

        $this->breadcrumb['title'] = trans('service::template.serviceModule.manageService');

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

        return view('services::powerpanel.index', compact('iTotalRecords', 'breadcrumb','approvalTotalRecords', 'userIsAdmin', 'categories', 'draftTotalRecords', 'trashTotalRecords', 'favoriteTotalRecords', 'settingarray'));
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
            $serviceCategory = ServiceCategory::getCatWithParent(false, $sectorname);
        } else {
            $serviceCategory = ServiceCategory::getCatWithParent(false, false);
        }
        $recordSelect = '<option value="">Select Category</option>';

        foreach ($serviceCategory as $cat) {
            $recordSelect .= '<option value="' . $cat->id . '">' . ucwords($cat->varTitle) . '</option>';
        }
        return $recordSelect;
    }

    public static function getCategory()
    {
        $data = Request::input();
        $admin = $data['sectorname'];
        $selected_id = $data['selectedCategory'];
        $module = Modules::getModule('service-category');
        $categories = ServiceCategory::getCatWithParent($module->id,$admin);
        $recordSelect = '<option value="">Select Category</option>';
         foreach ($categories as $record) {
             $selected = '';
            if (isset($data['selectedCategory']) && !empty($data['selectedCategory'])) {
                if ($record->id == $data['selectedCategory']) {
                    $selected = 'selected';
                }
            }
            $recordSelect .= '<option data-moduleid="' . $module->id . '" value="' . $record->id . '" '.$selected.'>' . ucwords($record->varTitle) . '</option>';
        }
        return $recordSelect;
    }

    public static function getRegisterOfApplication()
    {

        $data = Request::input();
        $sectorname = $data['sectorname'];
        $selectedRegisterRecord = $data['selectedRegisterRecord'];
        if(isset($sectorname) && !empty($sectorname)){
            $categories = RegisterApplication::getRecordForService($sectorname);
        }else{
            $categories = RegisterApplication::getRecordForService(false);
        }
        $recordSelect = '<option value="">Assign To Register of Applications</option>';
        foreach ($categories as $record) {
            $selected = '';
            if(isset($selectedRegisterRecord) && !empty($selectedRegisterRecord)){
                $selectedRecords= explode(",",$selectedRegisterRecord);
                if(in_array($record->id,$selectedRecords)){
                    $selected = 'selected';
                }
            }
            $recordSelect .= '<option  value="' . $record->id . '"'.$selected.' >' . ucwords($record->varTitle) . '</option>';
        }
        return $recordSelect;
    }

    public static function getLicenceRegister()
    {
        $data = Request::input();
        $sectorname = $data['sectorname'];
        $selectedLicenceRegisterRecord = $data['selectedLicenceRegisterRecord'];
        if(isset($sectorname) && !empty($sectorname)){
            $categories = LicenceRegister::getRecordForService($sectorname);
        }else{
            $categories = LicenceRegister::getRecordForService(false);
        }
        $recordSelect = '<option value="">Assign To Register Of Licensees</option>';

        foreach ($categories as $record) {
            $selected = '';
            if(isset($selectedLicenceRegisterRecord) && !empty($selectedLicenceRegisterRecord)){
                $selectedRecords= explode(",",$selectedLicenceRegisterRecord);
                if(in_array($record->id,$selectedRecords)){
                    $selected = 'selected';
                }
            }
            $recordSelect .= '<option  value="' . $record->id . '"'.$selected.' >' . ucwords($record->varTitle) . '</option>';
        }
        return $recordSelect;
    }

    /**
     * This method loads service edit view
     * @param   Alias of record
     * @return  View
     * @since   2017-11-10
     * @author  NetQuick
     */
    public function edit($id = false) {

        $userIsAdmin = false;
        if (isset($this->currentUserRoleData) && !empty($this->currentUserRoleData)) {
            if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                $userIsAdmin = true;
            }
        }

        $module = Modules::getModule('service-category');
        $categories = ServiceCategory::getCatWithParent($module->id);
        $iTotalRecords = Service::getRecordCount(false, false, $userIsAdmin, $this->currentUserRoleSector);

        if (!is_numeric($id)) {

            $this->breadcrumb['title'] = trans('service::template.serviceModule.addService');
            $this->breadcrumb['module'] = trans('service::template.serviceModule.manageService');
            $this->breadcrumb['url'] = 'powerpanel/service';
            $this->breadcrumb['inner_title'] = '';
            $breadcrumb = $this->breadcrumb;
            $total = $iTotalRecords + 1;
            $data = compact('breadcrumb', 'total', 'userIsAdmin', 'categories');

        } else {

            $service = Service::getRecordById($id);
            if (empty($service)) {
                return redirect()->route('powerpanel.service.add');
            }

            if ($service->fkMainRecord != '0') {
                $service_highLight = Service::getRecordById($service->fkMainRecord);
                $templateData['service_highLight'] = $service_highLight;
                $display_publish = $service_highLight['chrPublish'];
            } else {
                $service_highLight = "";
                $templateData['service_highLight'] = "";
                $display_publish = '';
            }

            if (method_exists($this->MyLibrary, 'getModulePageAliasByModuleName')) {
                $categorypagereocrdlink = MyLibrary::getModulePageAliasByModuleName('service');
            }
            if (method_exists($this->MyLibrary, 'getRecordAliasByModuleNameRecordId')) {
                $categoryRecordAlias = MyLibrary::getRecordAliasByModuleNameRecordId("service-category", $service->intFKCategory);
            }
            if (!empty($categorypagereocrdlink)) {
                $varURL = $categorypagereocrdlink . '/' . $service->alias->varAlias;
            } else {
                $varURL = $service->alias->varAlias;
            }

            $metaInfo['varURL'] = $varURL;
            $this->breadcrumb['title'] = trans('service::template.serviceModule.editService');
            $this->breadcrumb['module'] = trans('service::template.serviceModule.manageService');
            $this->breadcrumb['url'] = 'powerpanel/service';
            $templateData['metaInfo'] = $metaInfo;
            $this->breadcrumb['inner_title'] = $service->varTitle;
            $breadcrumb = $this->breadcrumb;
            $data = compact('service', 'breadcrumb', 'service_highLight', 'display_publish', 'userIsAdmin', 'categories');
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
        return view('services::powerpanel.actions', $data);
    }

    /**
     * This method stores service modifications
     * @return  View
     * @since   2017-11-10
     * @author  NetQuick
     */
    public function handlePost(Request $request) {
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
            'service_code' => 'required|handle_xss|no_url',
            'chrMenuDisplay' => 'required',
            'category_id' => 'required',
        );
        $actionMessage = trans('service::template.common.oppsSomethingWrong');
        $messsages = array(
            'title.required' => 'Title field is required.',
            'sector.required' => 'Sector field is required.',
            'service_code.required' => 'Service Code field is required.',
            'category_id.required' => trans('service::template.serviceModule.categoryMessage'),
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
            $serviceArr = [];
            $serviceArr['varTitle'] = stripslashes(trim($data['title']));
            $serviceArr['serviceCode'] = $data['service_code'];
            $serviceArr['applicationFee'] = !empty($data['application_fee']) ? $data['application_fee'] : null;
            $serviceArr['noteTitle'] = !empty($data['note']) ? $data['note'] : null;
            $serviceArr['noteLink'] = !empty($data['notelink']) ? $data['notelink'] : null;
            $serviceArr['varShortDescription'] = stripslashes(trim($data['short_description']));
            $serviceArr['txtDescription'] = $vsection;
            $serviceArr['intFKCategory'] = isset($data['category_id']) ? $data['category_id'] : null;
            $serviceArr['fkIntImgId'] = !empty($data['img_id']) ? $data['img_id'] : null;

            if (isset($data['registerapplication']) && !empty($data['registerapplication'])) {

                $serviceArr['varRegisterID'] = implode(',', $data['registerapplication']);
            } else {
                $serviceArr['varRegisterID'] = null;
            }
            if (isset($data['licenseregister']) && !empty($data['licenseregister'])) {
                $serviceArr['varLicenceRegisterID'] = implode(',', $data['licenseregister']);
            }
            else{
                $serviceArr['varLicenceRegisterID'] = null;
                
            }
            if (isset($data['chrServiceFees']) && $data['chrServiceFees'] == 'on') {

                $serviceArr['chrServiceFees'] = 'Y';
            } else {
                $serviceArr['chrServiceFees'] = 'N';
            }

            $serviceArr['UserID'] = auth()->user()->id;
            if ($data['chrMenuDisplay'] == 'D') {
                $serviceArr['chrDraft'] = 'D';
                $serviceArr['chrPublish'] = 'N';
            } else {
                $serviceArr['chrDraft'] = 'N';
                $serviceArr['chrPublish'] = $data['chrMenuDisplay'];
            }

            if ($data['chrMenuDisplay'] == 'D') {
                $addlog = Config::get('Constant.UPDATE_DRAFT');
            } else {
                $addlog = '';
            }

            $id = Request::segment(3);
            if (is_numeric($id)) { #Edit post Handler=======
                
                $registerecord = RegisterApplication::getRecords();
                if (isset($registerecord) && !empty($registerecord) && isset($data['registerapplication']) && !empty($data['registerapplication'])) {
                    foreach ($registerecord as $regrecord) {
                        $removeid = explode(",", $regrecord->varService);
                        if (in_array($id, $removeid)) {
                            if (!in_array($regrecord->id, $data['registerapplication'])) {
                                if (($key = array_search($id, $removeid)) !== false) {
                                    unset($removeid[$key]);
                                }
                                $updateval = implode(',', $removeid);
                                if (isset($updateval) && !empty($updateval)) {
                                    DB::table('register_application')
                                            ->where('id', $regrecord->id)
                                            ->update(['varService' => $updateval]);
                                }
                            }
                        }
                        if (in_array($regrecord->id, $data['registerapplication'])) {

                            $serv = explode(",", $regrecord->varService);
                            if (!in_array($id, $serv)) {
                                $reg = $regrecord->varService;
                                $reg .= ',';
                                $reg .= $id;
                                DB::table('register_application')
                                        ->where('id', $regrecord->id)
                                        ->update(['varService' => $reg]);
                            }
                        }
                    }
                }
                elseif(!isset($data['registerapplication']) && empty($data['registerapplication'])){
                   foreach ($registerecord as $regrecord) {
                        $removeid = explode(",", $regrecord->varService);
                        if (in_array($id, $removeid)) {
                          
                                if (($key = array_search($id, $removeid)) !== false) {
                                    unset($removeid[$key]);
                                }
                                $updateval = implode(',', $removeid);
                                if (isset($updateval) && !empty($updateval)) {
                                    DB::table('register_application')
                                            ->where('id', $regrecord->id)
                                            ->update(['varService' => $updateval]);
                                }
                            
                        }
                   }
                }

                $licenceregisterecord = LicenceRegister::getRecords();
                if (isset($licenceregisterecord) && !empty($licenceregisterecord) && isset($data['licenseregister']) && !empty($data['licenseregister'])) {
                    foreach ($licenceregisterecord as $regrecord) {
                         $removeid = explode(",", $regrecord->varService);
                        if (in_array($id, $removeid)) {
                            if (!in_array($regrecord->id, $data['licenseregister'])) {
                                if (($key = array_search($id, $removeid)) !== false) {
                                    unset($removeid[$key]);
                                }
                                $updateval = implode(',', $removeid);
                                if (isset($updateval) && !empty($updateval)) {
                                    DB::table('licence_register')
                                            ->where('id', $regrecord->id)
                                            ->update(['varService' => $updateval]);
                                }
                            }
                        }
                        if (in_array($regrecord->id, $data['licenseregister'])) {

                            $serv = explode(",", $regrecord->varService);
                            if (!in_array($id, $serv)) {
                                $reg = $regrecord->varService;
                                $reg .= ',';
                                $reg .= $id;
                                DB::table('licence_register')
                                        ->where('id', $regrecord->id)
                                        ->update(['varService' => $reg]);
                            }
                        }
                    }
                }
                elseif(!isset($data['licenseregister']) && empty($data['licenseregister'])){
                    foreach ($licenceregisterecord as $regrecord) {
                         $removeid = explode(",", $regrecord->varService);
                        if (in_array($id, $removeid)) {
                          
                                if (($key = array_search($id, $removeid)) !== false) {
                                    unset($removeid[$key]);
                                }
                                $updateval = implode(',', $removeid);
                                if (isset($updateval) && !empty($updateval)) {
                                    DB::table('licence_register')
                                            ->where('id', $regrecord->id)
                                            ->update(['varService' => $updateval]);
                                }
                            
                        }
                    }
                }


                $service = Service::getRecordForLogById($id);
                $whereConditions = ['id' => $service->id];
                $serviceArr['varSector'] = $data['sector'];
                if ($service->chrLock == 'Y' && auth()->user()->id != $service->LockUserID) {
                    if ($this->currentUserRoleData->chrIsAdmin != 'Y') {
                        $lockedUserData = User::getRecordById($service->LockUserID, true);
                        $lockedUserName = 'someone';
                        if (!empty($lockedUserData)) {
                            $lockedUserName = $lockedUserData->name;
                        }
                        $actionMessage = "This record has been locked by " . $lockedUserName . ".";
                        return redirect()->route('powerpanel.service.index')->with('message', $actionMessage);
                    }
                }
                if (File::exists(app_path() . '/Workflow.php') != null || File::exists(base_path() . '/packages/Powerpanel/Workflow/src/Models/Workflow.php') != null) {
                    if (!$userIsAdmin) {
                        $userRole = $this->currentUserRoleData->id;
                    } else {
                        $userRoleData = Role_user::getUserRoleByUserId($service->UserID);
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
                        if ($data['oldAlias'] != $data['alias']) {
                            Alias::updateAlias($data['oldAlias'], $data['alias']);
                        }
                        if ($service->fkMainRecord == '0' || empty($workFlowByCat->varUserId)) {
                            $update = CommonModel::updateRecords($whereConditions, $serviceArr, false, 'Powerpanel\Service\Models\Service');
                            if ($update) {
                                if (!empty($id)) {
                                     self::swap_order_edit($data['order'], $id);
                                    $logArr = MyLibrary::logData($service->id, false, $addlog);
                                    if (Auth::user()->can('log-advanced')) {
                                        $newNewsObj = Service::getRecordForLogById($service->id);
                                        $oldRec = $this->recordHistory($service);
                                        $newRec = $this->newrecordHistory($service, $newNewsObj);
                                        $logArr['old_val'] = $oldRec;
                                        $logArr['new_val'] = $newRec;
                                    }
                                    $logArr['varTitle'] = trim($data['title']);
                                    Log::recordLog($logArr);
                                    if (Auth::user()->can('recent-updates-list')) {
                                        if (!isset($newNewsObj)) {
                                            $newNewsObj = Service::getRecordForLogById($service->id);
                                        }
                                        $notificationArr = MyLibrary::notificationData($service->id, $newNewsObj);
                                        RecentUpdates::setNotification($notificationArr);
                                    }
                                    self::flushCache();
                                    if (isset($data['saveandexit']) && $data['saveandexit'] == 'approvesaveandexit') {
                                        $actionMessage = trans('service::template.common.recordApprovalMessage');
                                    } else {
                                        $actionMessage = trans('service::template.serviceModule.updateMessage');
                                    }
                                }
                            }
                        } else {
                            $updateModuleFields = $serviceArr;
                            
                            $this->insertApprovedRecord($updateModuleFields, $data, $id);
                            if (isset($data['saveandexit']) && $data['saveandexit'] == 'approvesaveandexit') {
                                $actionMessage = trans('service::template.common.recordApprovalMessage');
                            } else {
                                $actionMessage = trans('service::template.serviceModule.updateMessage');
                            }
                            $approval = $id;
                        }
                    } else {
                        if ($workFlowByCat->charNeedApproval == 'Y') {
                            $approvalObj = $this->insertApprovalRecord($service, $data, $serviceArr);
                            if (isset($data['saveandexit']) && $data['saveandexit'] == 'approvesaveandexit') {
                                $actionMessage = trans('service::template.common.recordApprovalMessage');
                            } else {
                                $actionMessage = trans('service::template.serviceModule.updateMessage');
                            }
                            $approval = $approvalObj->id;
                        }
                    }
                } else {
                    $update = CommonModel::updateRecords($whereConditions, $serviceArr, false, 'Powerpanel\Service\Models\Service');
                    $actionMessage = trans('service::template.serviceModule.updateMessage');
                }
            } else { #Add post Handler=======
                if (File::exists(app_path() . '/Workflow.php') != null || File::exists(base_path() . '/packages/Powerpanel/Workflow/src/Models/Workflow.php') != null) {
                    $workFlowByCat = Workflow::getRecordByCategoryId($module->intFkGroupCode, $this->currentUserRoleData->id, Config::get('Constant.MODULE.ID'));
                }
                if (!empty($workFlowByCat->varUserId) && $workFlowByCat->chrNeedAddPermission == 'Y' && !$userIsAdmin) {
                    $serviceArr['chrPublish'] = 'N';
                    $serviceArr['chrDraft'] = 'N';
                    $newsObj = $this->insertNewRecord($data, $serviceArr);
                    if ($data['chrMenuDisplay'] == 'D') {
                        $serviceArr['chrDraft'] = 'D';
                    }
                    $serviceArr['chrPublish'] = 'Y';
                    $approvalObj = $this->insertApprovalRecord($newsObj, $data, $serviceArr);
                    $approval = $newsObj->id;
                } else {
                    $newsObj = $this->insertNewRecord($data, $serviceArr);
                    $approval = $newsObj->id;
                }
                if (method_exists($this->Alias, 'updatePreviewAlias')) {
                    Alias::updatePreviewAlias($data['alias'], 'N');
                }
                if (isset($data['saveandexit']) && $data['saveandexit'] == 'approvesaveandexit') {
                    $actionMessage = trans('service::template.common.recordApprovalMessage');
                } else {
                    $actionMessage = trans('service::template.serviceModule.addMessage');
                }
                $id = $newsObj->id;
            }


            if ((!empty($request->saveandexit) && $request->saveandexit == 'saveandexit') || !$userIsAdmin) {
                if ($data['chrMenuDisplay'] == 'D') {
                    return redirect()->route('powerpanel.service.index', 'tab=D')->with('message', $actionMessage);
                } else {
                    return redirect()->route('powerpanel.service.index')->with('message', $actionMessage);
                }
            } else {
                return redirect()->route('powerpanel.service.edit', $id)->with('message', $actionMessage);
            }
        } else {
            return Redirect::back()->withErrors($validator)->withInput();
        }
    }

    public function insertApprovedRecord($updateModuleFields, $postArr, $id) {

        $whereConditions = ['id' => $postArr['fkMainRecord']];
        $updateModuleFields['chrAddStar'] = 'N';
        $update = CommonModel::updateRecords($whereConditions, $updateModuleFields, false, 'Powerpanel\Service\Models\Service');
        $whereConditions_ApproveN = ['fkMainRecord' => $postArr['fkMainRecord']];
        $updateToApproveN = [
            'chrApproved' => 'N',
            'chrLetest' => 'N',
            'intApprovedBy' => '0',
        ];
        CommonModel::updateRecords($whereConditions_ApproveN, $updateToApproveN, false, 'Powerpanel\Service\Models\Service');
        $whereConditionsApprove = ['id' => $id, 'chrMain' => 'N'];
        $updateToApprove = [
            'chrApproved' => 'Y',
            'chrRollBack' => 'Y',
            'intApprovedBy' => auth()->user()->id,
            'dtApprovedDateTime' => date('Y-m-d H:i:s')
        ];
        CommonModel::updateRecords($whereConditionsApprove, $updateToApprove, false, 'Powerpanel\Service\Models\Service');
        if ($postArr['chrMenuDisplay'] == 'D') {
            $addlog = Config::get('Constant.DRAFT_RECORD_APPROVED');
        } else {
            $addlog = Config::get('Constant.RECORD_APPROVED');
        }
        $newCmsPageObj = Service::getRecordForLogById($id);
        $logArr = MyLibrary::logData($id, false, $addlog);
        $logArr['varTitle'] = stripslashes($newCmsPageObj->varTitle);
        Log::recordLog($logArr);
        /* notification for user to record approved */
        $careers = Service::getRecordForLogById($id);
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

    public function insertApprovalRecord($moduleObj, $postArr, $serviceArr) {
        $response = false;

        $serviceArr['chrMain'] = 'N';
        $serviceArr['chrLetest'] = 'Y';
        $serviceArr['fkMainRecord'] = $moduleObj->id;
       $serviceArr['varSector'] = $postArr['sector'];
       $serviceArr['varShortDescription'] = stripslashes(trim($postArr['short_description']));
       $serviceArr['fkIntImgId'] = !empty($postArr['img_id']) ? $postArr['img_id'] : null;
       $serviceArr['intAliasId'] = MyLibrary::insertAlias($postArr['alias'], false, 'N');

       $serviceArr['intDisplayOrder'] = $postArr['order'];
        if ($postArr['chrMenuDisplay'] == 'D') {
            $serviceArr['chrDraft'] = 'D';
            $serviceArr['chrPublish'] = 'N';
        } else {
            $serviceArr['chrDraft'] = 'N';
            $serviceArr['chrPublish'] = $postArr['chrMenuDisplay'];
        }
        if (isset($postArr['chrPageActive']) && $postArr['chrPageActive'] != '') {
            $serviceArr['chrPageActive'] = $postArr['chrPageActive'];
        }
        if (isset($postArr['chrPageActive']) && $postArr['chrPageActive'] == 'PP') {
            $serviceArr['varPassword'] = $postArr['new_password'];
        } else {
            $serviceArr['varPassword'] = '';
        }

        if ($postArr['chrMenuDisplay'] == 'D') {
            $addlog = Config::get('Constant.DRAFT_SENT_FOR_APPROVAL');
        } else {
            $addlog = Config::get('Constant.SENT_FOR_APPROVAL');
        }
        $newsID = CommonModel::addRecord($serviceArr, 'Powerpanel\Service\Models\Service');
        if (!empty($newsID)) {
            $id = $newsID;
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
            $newNewsObj = Service::getRecordForLogById($id);
            $logArr = MyLibrary::logData($id, false, $addlog);
            $logArr['varTitle'] = $newNewsObj->varTitle;
            Log::recordLog($logArr);
            if (Auth::user()->can('recent-updates-list')) {
                $notificationArr = MyLibrary::notificationData($id, $newNewsObj);
                RecentUpdates::setNotification($notificationArr);
            }
            $response = $newNewsObj;
            self::flushCache();
            $actionMessage = trans('service::template.serviceModule.addMessage');
        }
        $whereConditionsAddstar = ['id' => $moduleObj->id];
        $updateAddStar = [
            'chrAddStar' => 'Y',
        ];
        CommonModel::updateRecords($whereConditionsAddstar, $updateAddStar, false, 'Powerpanel\Service\Models\Service');
        return $response;
    }

    public function insertNewRecord($postArr, $serviceArr, $preview = 'N') {
        $response = false;

        $serviceArr['varSector'] = $postArr['sector'];
        $serviceArr['chrMain'] = 'Y';
        $serviceArr['intDisplayOrder'] = $postArr['order'];
        $serviceArr['varShortDescription'] = stripslashes(trim($postArr['short_description']));
        $serviceArr['fkIntImgId'] = !empty($postArr['img_id']) ? $postArr['img_id'] : null;
        $serviceArr['intAliasId'] = MyLibrary::insertAlias($postArr['alias'], false, $preview);

        if ($postArr['chrMenuDisplay'] == 'D') {
            $serviceArr['chrDraft'] = 'D';
            $serviceArr['chrPublish'] = 'N';
        } else {
            $serviceArr['chrDraft'] = 'N';
        }

        if ($postArr['chrMenuDisplay'] == 'D') {
            $addlog = Config::get('Constant.ADDED_DRAFT');
        } else {
            $addlog = '';
        }

        $serviceArr['intDisplayOrder'] = self::swap_order_add($postArr['order']);

        $newsID = CommonModel::addRecord($serviceArr, 'Powerpanel\Service\Models\Service');

        if (!empty($newsID)) {
            if(isset($postArr['registerapplication'])&& !empty($postArr['registerapplication'])){
                $registerecord = RegisterApplication::getRecords();
                    if (isset($registerecord) && !empty($registerecord)) {
                        foreach ($registerecord as $regrecord) {

                            if (in_array($regrecord->id, $postArr['registerapplication'])) {

                                $serv = explode(",", $regrecord->varService);
                                if (!in_array($newsID, $serv)) {
                                    $reg = $regrecord->varService;
                                    $reg .= ',';
                                    $reg .= $newsID;
                                    DB::table('register_application')
                                            ->where('id', $regrecord->id)
                                            ->update(['varService' => $reg]);
                                }
                            }
                        }
                    }
            }

            $licenceregisterecord = LicenceRegister::getRecords();

            if (isset($licenceregisterecord) && !empty($licenceregisterecord) && !empty($postArr['licenseregister'])) {
                foreach ($licenceregisterecord as $regrecord) {

                    if (in_array($regrecord->id, $postArr['licenseregister'])) {

                        $serv = explode(",", $regrecord->varService);
                        if (!in_array($newsID, $serv)) {
                            $reg = $regrecord->varService;
                            $reg .= ',';
                            $reg .= $newsID;
                            DB::table('licence_register')
                                    ->where('id', $regrecord->id)
                                    ->update(['varService' => $reg]);
                        }
                    }
                }
            }

            $id = $newsID;
            $newNewsObj = Service::getRecordForLogById($id);
            $logArr = MyLibrary::logData($id, false, $addlog);
            $logArr['varTitle'] = stripslashes($newNewsObj->varTitle);
            Log::recordLog($logArr);
            if (Auth::user()->can('recent-updates-list')) {
                $notificationArr = MyLibrary::notificationData($id, $newNewsObj);
                RecentUpdates::setNotification($notificationArr);
            }
            $response = $newNewsObj;
            self::flushCache();
            $actionMessage = trans('service::template.serviceModule.addMessage');
        }
        return $response;
    }



    public function get_list() {
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
        if (isset($this->currentUserRoleData) && !empty($this->currentUserRoleData)) {
            if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                $isAdmin = true;
            }
        }

        $ignoreId = [];
        $arrResults = Service::getRecordList($filterArr, $isAdmin, $ignoreId, $this->currentUserRoleSector);
        $iTotalRecords = Service::getRecordCountforList($filterArr, true, $isAdmin, $ignoreId, $this->currentUserRoleSector);

        $allRecordsCount = Service::getRecordCountForDorder(false, false, $isAdmin, $this->currentUserRoleSector);

        if (!empty($arrResults)) {
            $currentUserID = auth()->user()->id;
            $permit = [
                'canserviceedit' => Auth::user()->can('service-edit'),
                'canservicepublish' => Auth::user()->can('service-publish'),
                'canservicedelete' => Auth::user()->can('service-delete'),
                'canservicereviewchanges' => Auth::user()->can('service-reviewchanges'),
                'canloglist' => Auth::user()->can('log-list'),
            ];

            foreach ($arrResults as $key => $value) {
                if (!in_array($value->id, $ignoreId)) {
                    $records['data'][] = $this->tableData($value, $permit, $currentUserID,$allRecordsCount);
                }
            }
        }

        $NewRecordsCount = Service::getNewRecordsCount($isAdmin, $this->currentUserRoleSector);
        $records["newRecordCount"] = $NewRecordsCount;
        if (!empty(Request::input('customActionType')) && Request::input('customActionType') == 'group_action') {
            $records['customActionStatus'] = 'OK';
        }
        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;
        return json_encode($records);
    }

    public function get_list_New() {
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
        if (isset($this->currentUserRoleData) && !empty($this->currentUserRoleData)) {
            if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                $isAdmin = true;
            }
        }

        $ignoreId = [];
        $arrResults = Service::getRecordList_tab1($filterArr, $isAdmin, $ignoreId, $this->currentUserRoleSector);
        $iTotalRecords = Service::getRecordCountListApprovalTab($filterArr, true, $isAdmin, $ignoreId, $this->currentUserRoleSector);

        if (!empty($arrResults)) {
            $currentUserID = auth()->user()->id;
            $permit = [
                'canserviceedit' => Auth::user()->can('service-edit'),
                'canservicepublish' => Auth::user()->can('service-publish'),
                'canservicedelete' => Auth::user()->can('service-delete'),
                'canservicereviewchanges' => Auth::user()->can('service-reviewchanges'),
                'canloglist' => Auth::user()->can('log-list'),
            ];

            foreach ($arrResults as $key => $value) {
                if (!in_array($value->id, $ignoreId)) {
                    $records['data'][] = $this->tableData_tab1($value, $permit, $currentUserID);
                }
            }
        }

        $NewRecordsCount = Service::getNewRecordsCount($isAdmin, $this->currentUserRoleSector);
        $records["newRecordCount"] = $NewRecordsCount;
        if (!empty(Request::input('customActionType')) && Request::input('customActionType') == 'group_action') {
            $records['customActionStatus'] = 'OK';
        }
        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;
        return json_encode($records);
    }

    public function get_list_favorite() {
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
        if (isset($this->currentUserRoleData) && !empty($this->currentUserRoleData)) {
            if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                $isAdmin = true;
            }
        }

        $ignoreId = [];
        $arrResults = Service::getRecordListFavorite($filterArr, $isAdmin, $ignoreId, $this->currentUserRoleSector);
        $iTotalRecords = Service::getRecordCountforListFavorite($filterArr, true, $isAdmin, $ignoreId, $this->currentUserRoleSector);

        if (!empty($arrResults)) {
            $currentUserID = auth()->user()->id;
            $permit = [
                'canserviceedit' => Auth::user()->can('service-edit'),
                'canservicepublish' => Auth::user()->can('service-publish'),
                'canservicedelete' => Auth::user()->can('service-delete'),
                'canservicereviewchanges' => Auth::user()->can('service-reviewchanges'),
                'canloglist' => Auth::user()->can('log-list'),
            ];

            foreach ($arrResults as $key => $value) {
                if (!in_array($value->id, $ignoreId)) {
                    $records['data'][] = $this->tableDataFavorite($value, $permit, $currentUserID);
                }
            }
        }

        $NewRecordsCount = Service::getNewRecordsCount($isAdmin, $this->currentUserRoleSector);
        $records["newRecordCount"] = $NewRecordsCount;
        if (!empty(Request::input('customActionType')) && Request::input('customActionType') == 'group_action') {
            $records['customActionStatus'] = 'OK';
        }
        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;
        return json_encode($records);
    }

    public function get_list_draft() {
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
        if (isset($this->currentUserRoleData) && !empty($this->currentUserRoleData)) {
            if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                $isAdmin = true;
            }
        }

        $ignoreId = [];
        $arrResults = Service::getRecordListDraft($filterArr, $isAdmin, $ignoreId, $this->currentUserRoleSector);
        $iTotalRecords = Service::getRecordCountforListDarft($filterArr, true, $isAdmin, $ignoreId, $this->currentUserRoleSector);

        if (!empty($arrResults)) {
            $currentUserID = auth()->user()->id;
            $permit = [
                'canserviceedit' => Auth::user()->can('service-edit'),
                'canservicepublish' => Auth::user()->can('service-publish'),
                'canservicedelete' => Auth::user()->can('service-delete'),
                'canservicereviewchanges' => Auth::user()->can('service-reviewchanges'),
                'canloglist' => Auth::user()->can('log-list'),
            ];

            foreach ($arrResults as $key => $value) {
                if (!in_array($value->id, $ignoreId)) {
                    $records['data'][] = $this->tableDataDraft($value, $permit, $currentUserID);
                }
            }
        }

        $NewRecordsCount = Service::getNewRecordsCount($isAdmin, $this->currentUserRoleSector);
        $records["newRecordCount"] = $NewRecordsCount;
        if (!empty(Request::input('customActionType')) && Request::input('customActionType') == 'group_action') {
            $records['customActionStatus'] = 'OK';
        }
        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;
        return json_encode($records);
    }

    public function get_list_trash() {
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
        if (isset($this->currentUserRoleData) && !empty($this->currentUserRoleData)) {
            if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                $isAdmin = true;
            }
        }

        $ignoreId = [];
        $arrResults = Service::getRecordListTrash($filterArr, $isAdmin, $ignoreId, $this->currentUserRoleSector);
        $iTotalRecords = Service::getRecordCountforListTrash($filterArr, true, $isAdmin, $ignoreId, $this->currentUserRoleSector);

        if (!empty($arrResults)) {
            $currentUserID = auth()->user()->id;
            $permit = [
                'canserviceedit' => Auth::user()->can('service-edit'),
                'canservicepublish' => Auth::user()->can('service-publish'),
                'canservicedelete' => Auth::user()->can('service-delete'),
                'canservicereviewchanges' => Auth::user()->can('service-reviewchanges'),
                'canloglist' => Auth::user()->can('log-list'),
            ];

            foreach ($arrResults as $key => $value) {
                if (!in_array($value->id, $ignoreId)) {
                    $records['data'][] = $this->tableDataTrash($value, $permit, $currentUserID);
                }
            }
        }

        $NewRecordsCount = Service::getNewRecordsCount($isAdmin, $this->currentUserRoleSector);
        $records["newRecordCount"] = $NewRecordsCount;
        if (!empty(Request::input('customActionType')) && Request::input('customActionType') == 'group_action') {
            $records['customActionStatus'] = 'OK';
        }
        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;
        return json_encode($records);
    }



    public function tableData($value , $permit, $currentUserID, $allRecordsCount) {

        // Checkbox
        $checkbox = view('powerpanel.partials.checkbox', ['name'=>'delete', 'value'=>$value->id])->render();


        // StartDate
        $startDate = $value->dtDateTime;
        $startDate = '<span align="left" data-bs-toggle="tooltip" data-bs-placement="bottom" title="'.date(Config::get("Constant.DEFAULT_DATE_FORMAT").' '.Config::get("Constant.DEFAULT_TIME_FORMAT"), strtotime($startDate)).'">'.date(Config::get('Constant.DEFAULT_DATE_FORMAT'), strtotime($startDate)).'</span>';

        // Title
        $title = $value->varTitle;


        // Publish Action
        $publish_action = '';
        if ($value->chrAddStar != 'Y') {
            if ($value->chrDraft != 'D') {
                if ($permit['canservicepublish']) {
                    if ($value->chrPublish == 'Y') {
                        $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/service', 'data_alias'=>$value->id, 'title'=>trans("service::template.common.publishedRecord"), 'data_value'=>'Unpublish', 'checked'=>'checked'])->render();
                    } else {
                        $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/service', 'data_alias'=>$value->id, 'title'=>trans("service::template.common.unpublishedRecord"), 'data_value'=>'Publish'])->render();
                    }
                } else {
                    $publish_action = "-";
                }
            } else {
                if ($permit['canservicepublish']) {
                    if ($value->chrPublish == 'Y') {
                        $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/service', 'data_alias'=>$value->id, 'title'=>trans("service::template.common.publishedRecord"), 'data_value'=>'Unpublish', 'checked'=>'checked'])->render();
                    } else {
                        $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/service', 'data_alias'=>$value->id, 'title'=>trans("service::template.common.unpublishedRecord"), 'data_value'=>'Publish'])->render();
                    }
                } else {
                    $publish_action = "-";
                }
            }
        } else {
            if ($permit['canservicepublish']) {
                $publish_action .= '<a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-toggle="tooltip" title="This service is in Approval request so can&#39;t be Publish/Unpublish."><i style="color:red" class="ri-toggle-line icon-publish fs-24"></i></a>';
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

        // category
        $category = '';
        if (isset($value->intFKCategory)) {
            $categoryIDs = [$value->intFKCategory];
            $selCategory = ServiceCategory::getParentCategoryNameBycatId($categoryIDs);
            foreach ($selCategory as $selCat) {
                if (strlen(trim($selCat)) > 0) {
                    $category = $selCat->varTitle;
                }
            }
        } else {
            $category = "-";
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


        // Title Action
        $title_action = '';
        if ($permit['canserviceedit']) {
            $Quickedit_startDate = date('Y-m-d H:i', strtotime($value->dtDateTime));
            $Quickedit_endDate = !empty($value->dtEndDateTime) ? date('Y-m-d H:i', strtotime($value->dtEndDateTime)) : 'No Expiry';

            if ($value->chrLock != 'Y') {
                if (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y') {
                    if ($permit['canservicereviewchanges']) {
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
        $viewlink = "";
        $linkviewLable = "";
        if(isset($value->alias) && $value->alias != null){
            if ($permit['canserviceedit']) {
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
                        'canedit'=> $permit['canserviceedit'],
                        'candelete'=>$permit['canservicedelete'],
                        'canloglist'=>$permit['canloglist'],
                        'value'=>$value,
                        'chrIsAdmin' => $this->currentUserRoleData->chrIsAdmin,
                        'module_name'=>'service',
                        'module_edit_url' => route('powerpanel.service.edit', array('alias' => $value->id)),
                        'module_type'=>'parent',
                        'viewlink' => isset($viewlink) ? $viewlink : "",
                        'linkviewLable' => isset($linkviewLable) ? $linkviewLable : "",
                        'logurl' => $logurl
                    ])->render();

                    if($permit['canserviceedit'] || $permit['canservicedelete']){
                        $allActions = $allActions;
                    } else {
                        $allActions = "-";
                    }


        $records = array(
                $checkbox,
                '<div class="pages_title_div_row">' . $Favorite . ' <span class="title-txt"> ' . $title . ' ' . $status . $statusdata . ' - ' . $sector . ' ' .$title_action. '</span></div>',
                $category,
                $value->serviceCode,
                $orderArrow,
                $publish_action,
                $allActions,
            );
        return $records;
    }

    public function tableData_tab1($value , $permit, $currentUserID) {
        // Checkbox
        $checkbox = view('powerpanel.partials.checkbox', ['name'=>'delete', 'value'=>$value->id])->render();


        // StartDate
        $startDate = $value->dtDateTime;
        $startDate = '<span align="left" data-bs-toggle="tooltip" data-bs-placement="bottom" title="'.date(Config::get("Constant.DEFAULT_DATE_FORMAT").' '.Config::get("Constant.DEFAULT_TIME_FORMAT"), strtotime($startDate)).'">'.date(Config::get('Constant.DEFAULT_DATE_FORMAT'), strtotime($startDate)).'</span>';

        // Title
        $title = $value->varTitle;


        // Publish Action
        $publish_action = '';
        if ($value->chrAddStar != 'Y') {
            if ($value->chrDraft != 'D') {
                if ($permit['canservicepublish']) {
                    if ($value->chrPublish == 'Y') {
                        $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/service', 'data_alias'=>$value->id, 'title'=>trans("service::template.common.publishedRecord"), 'data_value'=>'Unpublish', 'checked'=>'checked'])->render();
                    } else {
                        $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/service', 'data_alias'=>$value->id, 'title'=>trans("service::template.common.unpublishedRecord"), 'data_value'=>'Publish'])->render();
                    }
                } else {
                    $publish_action = "-";
                }
            } else {
                if ($permit['canservicepublish']) {
                    if ($value->chrPublish == 'Y') {
                        $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/service', 'data_alias'=>$value->id, 'title'=>trans("service::template.common.publishedRecord"), 'data_value'=>'Unpublish', 'checked'=>'checked'])->render();
                    } else {
                        $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/service', 'data_alias'=>$value->id, 'title'=>trans("service::template.common.unpublishedRecord"), 'data_value'=>'Publish'])->render();
                    }
                } else {
                    $publish_action = "-";
                }
            }
        } else {
            if ($permit['canservicepublish']) {
                $publish_action .= '<a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-toggle="tooltip" title="This service is in Approval request so can&#39;t be Publish/Unpublish."><i style="color:red" class="ri-toggle-line icon-publish fs-24"></i></a>';
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

        // category
        $category = '';
        if (isset($value->intFKCategory)) {
            $categoryIDs = [$value->intFKCategory];
            $selCategory = ServiceCategory::getParentCategoryNameBycatId($categoryIDs);
            foreach ($selCategory as $selCat) {
                if (strlen(trim($selCat)) > 0) {
                    $category = $selCat->varTitle;
                }
            }
        } else {
            $category = "-";
        }


        // Title Action
        $title_action = '';
        if ($permit['canserviceedit']) {
            $Quickedit_startDate = date('Y-m-d H:i', strtotime($value->dtDateTime));
            $Quickedit_endDate = !empty($value->dtEndDateTime) ? date('Y-m-d H:i', strtotime($value->dtEndDateTime)) : 'No Expiry';

            if ($value->chrLock != 'Y') {
                if (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y') {
                    if ($permit['canservicereviewchanges']) {
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
        $viewlink = "";
        $linkviewLable = "";
        if(isset($value->alias) && $value->alias != null){
            if ($permit['canserviceedit']) {
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
                        'canedit'=> $permit['canserviceedit'],
                        'candelete'=>$permit['canservicedelete'],
                        'canloglist'=>$permit['canloglist'],
                        'value'=>$value,
                        'chrIsAdmin' => $this->currentUserRoleData->chrIsAdmin,
                        'module_name'=>'service',
                        'module_edit_url' => route('powerpanel.service.edit', array('alias' => $value->id)),
                        'module_type'=>'parent',
                        'viewlink' => isset($viewlink) ? $viewlink : "",
                        'linkviewLable' => isset($linkviewLable) ? $linkviewLable : "",
                        'logurl' => $logurl
                    ])->render();

                    if($permit['canserviceedit'] || $permit['canservicedelete']){
                        $allActions = $allActions;
                    } else {
                        $allActions = "-";
                    }

        $records = array(
                $checkbox,
                '<div class="pages_title_div_row">' . $Favorite . ' <span class="title-txt"> ' . $title . ' ' . $status . $statusdata . ' - ' . $sector . ' ' .$title_action. '</span></div>',
                $category,
                $value->serviceCode,
                $publish_action,
                $allActions,
            );
        return $records;
    }

    public function tableDataFavorite($value , $permit, $currentUserID) {

        // Checkbox
        $checkbox = view('powerpanel.partials.checkbox', ['name'=>'delete', 'value'=>$value->id])->render();


        // StartDate
        $startDate = $value->dtDateTime;
        $startDate = '<span align="left" data-bs-toggle="tooltip" data-bs-placement="bottom" title="'.date(Config::get("Constant.DEFAULT_DATE_FORMAT").' '.Config::get("Constant.DEFAULT_TIME_FORMAT"), strtotime($startDate)).'">'.date(Config::get('Constant.DEFAULT_DATE_FORMAT'), strtotime($startDate)).'</span>';

        // Title
        $title = $value->varTitle;


        // Publish Action
        $publish_action = '';
        if ($value->chrAddStar != 'Y') {
            if ($value->chrDraft != 'D') {
                if ($permit['canservicepublish']) {
                    if ($value->chrPublish == 'Y') {
                        $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/service', 'data_alias'=>$value->id, 'title'=>trans("service::template.common.publishedRecord"), 'data_value'=>'Unpublish', 'checked'=>'checked'])->render();
                    } else {
                        $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/service', 'data_alias'=>$value->id, 'title'=>trans("service::template.common.unpublishedRecord"), 'data_value'=>'Publish'])->render();
                    }
                } else {
                    $publish_action = "-";
                }
            } else {
                if ($permit['canservicepublish']) {
                    if ($value->chrPublish == 'Y') {
                        $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/service', 'data_alias'=>$value->id, 'title'=>trans("service::template.common.publishedRecord"), 'data_value'=>'Unpublish', 'checked'=>'checked'])->render();
                    } else {
                        $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/service', 'data_alias'=>$value->id, 'title'=>trans("service::template.common.unpublishedRecord"), 'data_value'=>'Publish'])->render();
                    }
                } else {
                    $publish_action = "-";
                }
            }
        } else {
            if ($permit['canservicepublish']) {
                $publish_action .= '<a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-toggle="tooltip" title="This service is in Approval request so can&#39;t be Publish/Unpublish."><i style="color:red" class="ri-toggle-line icon-publish fs-24"></i></a>';
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

        // category
        $category = '';
        if (isset($value->intFKCategory)) {
            $categoryIDs = [$value->intFKCategory];
            $selCategory = ServiceCategory::getParentCategoryNameBycatId($categoryIDs);
            foreach ($selCategory as $selCat) {
                if (strlen(trim($selCat)) > 0) {
                    $category = $selCat->varTitle;
                }
            }
        } else {
            $category = "-";
        }


        // Title Action
        $title_action = '';
        if ($permit['canserviceedit']) {
            $Quickedit_startDate = date('Y-m-d H:i', strtotime($value->dtDateTime));
            $Quickedit_endDate = !empty($value->dtEndDateTime) ? date('Y-m-d H:i', strtotime($value->dtEndDateTime)) : 'No Expiry';

            if ($value->chrLock != 'Y') {
                if (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y') {
                    if ($permit['canservicereviewchanges']) {
                        $title_action .= "<a href=\"javascript:void(0);\" class=\"icon_title1 approval_active\"data-bs-toggle=\"tooltip\" data-bs-placement=\"bottom\" title=\"Click here to see all approval records.\" style=\"margin-right: 5px;\" onclick=\"javascript:expandcollapsepanel(this ,'tasklisting" . $value->id . "', 'mainsingnimg" . $value->id . "'," . $value->id . ')" id="mainsingnimg' . $value->id . '"><i class="ri-stack-line fs-16"></i></a>';

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
        $viewlink = "";
        $linkviewLable = "";
        if(isset($value->alias) && $value->alias != null){
            if ($permit['canserviceedit']) {
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
                        'canedit'=> $permit['canserviceedit'],
                        'candelete'=>$permit['canservicedelete'],
                        'canloglist'=>$permit['canloglist'],
                        'value'=>$value,
                        'chrIsAdmin' => $this->currentUserRoleData->chrIsAdmin,
                        'module_name'=>'service',
                        'module_edit_url' => route('powerpanel.service.edit', array('alias' => $value->id)),
                        'module_type'=>'parent',
                        'viewlink' => isset($viewlink) ? $viewlink : "",
                        'linkviewLable' => isset($linkviewLable) ? $linkviewLable : "",
                        'logurl' => $logurl
                    ])->render();

                    if($permit['canserviceedit'] || $permit['canservicedelete']){
                        $allActions = $allActions;
                    } else {
                        $allActions = "-";
                    }

        $records = array(
                $checkbox,
                '<div class="pages_title_div_row">' . $Favorite . ' <span class="title-txt"> ' . $title . ' ' . $status . $statusdata . ' - ' . $sector . ' ' .$title_action. '</span></div>',
                $category,
                $value->serviceCode,
                $publish_action,
                $allActions,
            );
        return $records;
    }

    public function tableDataDraft($value , $permit, $currentUserID) {

        // Checkbox
        $checkbox = view('powerpanel.partials.checkbox', ['name'=>'delete', 'value'=>$value->id])->render();


        // StartDate
        $startDate = $value->dtDateTime;
        $startDate = '<span align="left" data-bs-toggle="tooltip" data-bs-placement="bottom" title="'.date(Config::get("Constant.DEFAULT_DATE_FORMAT").' '.Config::get("Constant.DEFAULT_TIME_FORMAT"), strtotime($startDate)).'">'.date(Config::get('Constant.DEFAULT_DATE_FORMAT'), strtotime($startDate)).'</span>';

        // Title
        $title = $value->varTitle;


        // Publish Action
        $publish_action = '';
        if ($value->chrAddStar != 'Y') {
            if ($value->chrDraft != 'D') {
                if ($permit['canservicepublish']) {
                    if ($value->chrPublish == 'Y') {
                        $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/service', 'data_alias'=>$value->id, 'title'=>trans("service::template.common.publishedRecord"), 'data_value'=>'Unpublish', 'checked'=>'checked'])->render();
                    } else {
                        $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/service', 'data_alias'=>$value->id, 'title'=>trans("service::template.common.unpublishedRecord"), 'data_value'=>'Publish'])->render();
                    }
                } else {
                    $publish_action = "-";
                }
            } else {
                if ($permit['canservicepublish']) {
                    if ($value->chrPublish == 'Y') {
                        $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/service', 'data_alias'=>$value->id, 'title'=>trans("service::template.common.publishedRecord"), 'data_value'=>'Unpublish', 'checked'=>'checked'])->render();
                    } else {
                        $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/service', 'data_alias'=>$value->id, 'title'=>trans("service::template.common.unpublishedRecord"), 'data_value'=>'Publish'])->render();
                    }
                } else {
                    $publish_action = "-";
                }
            }
        } else {
            if ($permit['canservicepublish']) {
                $publish_action .= '<a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-toggle="tooltip" title="This service is in Approval request so can&#39;t be Publish/Unpublish."><i style="color:red" class="ri-toggle-line icon-publish fs-24"></i></a>';
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

        // category
        $category = '';
        if (isset($value->intFKCategory)) {
            $categoryIDs = [$value->intFKCategory];
            $selCategory = ServiceCategory::getParentCategoryNameBycatId($categoryIDs);
            foreach ($selCategory as $selCat) {
                if (strlen(trim($selCat)) > 0) {
                    $category = $selCat->varTitle;
                }
            }
        } else {
            $category = "-";
        }


        // Title Action
        $title_action = '';
        if ($permit['canserviceedit']) {
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


        // All - Actions
        $logurl = url('powerpanel/log?id=' . $value->id . '&mid=' . Config::get('Constant.MODULE.ID'));
        $allActions = view('powerpanel.partials.all-actions',
                    [
                        'tabName'=>'Draft',
                        'canedit'=> $permit['canserviceedit'],
                        'candelete'=>$permit['canservicedelete'],
                        'canloglist'=>$permit['canloglist'],
                        'value'=>$value,
                        'chrIsAdmin' => $this->currentUserRoleData->chrIsAdmin,
                        'module_name'=>'service',
                        'module_edit_url' => route('powerpanel.service.edit', array('alias' => $value->id)),
                        'module_type'=>'parent',
                        'viewlink' => isset($viewlink) ? $viewlink : "",
                        'linkviewLable' => isset($linkviewLable) ? $linkviewLable : "",
                        'logurl' => $logurl
                    ])->render();

                    if($permit['canserviceedit'] || $permit['canservicedelete']){
                        $allActions = $allActions;
                    } else {
                        $allActions = "-";
                    }

        $records = array(
                $checkbox,
                '<div class="pages_title_div_row"> <span class="title-txt"> ' . $title . ' ' . $status . $statusdata . ' - ' . $sector . ' ' .$title_action. '</span></div>',
                $category,
                $value->serviceCode,
                $publish_action,
                $allActions,
            );
        return $records;
    }

    public function tableDataTrash($value , $permit, $currentUserID) {

        // Checkbox
        $checkbox = view('powerpanel.partials.checkbox', ['name'=>'delete', 'value'=>$value->id])->render();


        // StartDate
        $startDate = $value->dtDateTime;
        $startDate = '<span align="left" data-bs-toggle="tooltip" data-bs-placement="bottom" title="'.date(Config::get("Constant.DEFAULT_DATE_FORMAT").' '.Config::get("Constant.DEFAULT_TIME_FORMAT"), strtotime($startDate)).'">'.date(Config::get('Constant.DEFAULT_DATE_FORMAT'), strtotime($startDate)).'</span>';

        // Title
        $title = $value->varTitle;

        // category
        $category = '';
        if (isset($value->intFKCategory)) {
            $categoryIDs = [$value->intFKCategory];
            $selCategory = ServiceCategory::getParentCategoryNameBycatId($categoryIDs);
            foreach ($selCategory as $selCat) {
                if (strlen(trim($selCat)) > 0) {
                    $category = $selCat->varTitle;
                }
            }
        } else {
            $category = "-";
        }


        // Title Action
        $title_action = '';
        if ($permit['canserviceedit']) {
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


        // All - Actions
        $logurl = url('powerpanel/log?id=' . $value->id . '&mid=' . Config::get('Constant.MODULE.ID'));
        $allActions = view('powerpanel.partials.all-actions',
                    [
                        'tabName'=>'Trash',
                        'canedit'=> $permit['canserviceedit'],
                        'candelete'=>$permit['canservicedelete'],
                        'canloglist'=>$permit['canloglist'],
                        'value'=>$value,
                        'chrIsAdmin' => $this->currentUserRoleData->chrIsAdmin,
                        'module_name'=>'service',
                        'module_edit_url' => route('powerpanel.service.edit', array('alias' => $value->id)),
                        'module_type'=>'parent',
                        'viewlink' => isset($viewlink) ? $viewlink : "",
                        'linkviewLable' => isset($linkviewLable) ? $linkviewLable : "",
                        'logurl' => $logurl
                    ])->render();

                    if($permit['canserviceedit'] || $permit['canservicedelete']){
                        $allActions = $allActions;
                    } else {
                        $allActions = "-";
                    }

        $records = array(
                $checkbox,
                '<div class="pages_title_div_row"> <span class="title-txt"> ' . $title . ' ' . $status . $statusdata . ' - ' . $sector . ' ' .$title_action. '</span></div>',
                $category,
                $value->serviceCode,
                $allActions,
            );
        return $records;
    }

    /**
     * This method delete multiples service
     * @return  true/false
     * @since   2017-07-15
     * @author  NetQuick
     */
    public function DeleteRecord(Request $request) {
        $value = Request::input('value');
        $data['ids'] = Request::input('ids');
        if (File::exists(app_path() . '/Comments.php') != null || File::exists(base_path() . '/packages/Powerpanel/Workflow/src/Models/Comments.php') != null) {
            Comments::deleteComments($data['ids'], Config::get('Constant.MODULE.MODEL_NAME'));
        }
        $moduleHaveFields = ['chrMain', 'chrIsPreview'];
        $update = MyLibrary::deleteMultipleRecords($data, $moduleHaveFields, $value, 'Powerpanel\Service\Models\Service');
        foreach ($update as $ids) {
            $ignoreDeleteScope = true;
            $Deleted_Record = Service::getRecordById($ids, $ignoreDeleteScope);
            $Cnt_Letest = Service::getRecordCount_letest($Deleted_Record['fkMainRecord'], $Deleted_Record['id']);
            if ($Cnt_Letest <= 0) {
                $updateLetest = [
                    'chrAddStar' => 'N',
                ];
                $whereConditionsApprove = ['id' => $Deleted_Record['fkMainRecord']];
                CommonModel::updateRecords($whereConditionsApprove, $updateLetest, false, 'Powerpanel\Service\Models\Service');
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
        Service::ReorderAllrecords();
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
        MyLibrary::swapOrder($order, $exOrder, 'Powerpanel\Service\Models\Service');
        Service::ReorderAllrecords();
        self::flushCache();
    }

    /**
     * This method handels swapping of available order record while adding
     * @param   order
     * @return  order
     * @since   2016-10-21
     * @author  NetQuick
     */
//    public static function swap_order_add($order = null) {
//        $response = false;
//        $isCustomizeModule = true;
//        if ($order != null) {
//            $response = MyLibrary::swapOrderAdd($order, $isCustomizeModule, 'Powerpanel\Service\Models\Service');
//            self::flushCache();
//        }
//        return $response;
//    }
    public static function swap_order_add($order = null)
    {
        $response = false;
        $isCustomizeModule = true;
        $moduleHaveFields = ['chrMain'];
        if ($order != null) {
        		Service::ReorderAllrecords();
            $response = MyLibrary::swapOrderAdd($order, $isCustomizeModule, $moduleHaveFields, 'Powerpanel\Service\Models\Service');
            
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
//    public static function swap_order_edit($order = null, $id = null) {
//        MyLibrary::swapOrderEdit($order, $id, 'Powerpanel\Service\Models\Service');
//        self::flushCache();
//    }
    public static function swap_order_edit($order = null, $id = null)
    {
        $isCustomizeModule = true;
        $moduleHaveFields = ['chrMain'];
        MyLibrary::swapOrderEdit($order, $id, $isCustomizeModule, $moduleHaveFields, 'Powerpanel\Service\Models\Service');
        Service::ReorderAllrecords();
        self::flushCache();
    }

    /**
     * This method destroys Banner in multiples
     * @return  Banner index view
     * @since   2016-10-25
     * @author  NetQuick
     */
//    public function publish(Request $request) {
//        $requestArr = Request::all();
////        $request = (object) $requestArr;
//        $val = Request::get('val');
//
//        $update = MyLibrary::setPublishUnpublish($alias, $val, 'Powerpanel\Service\Models\Service');
//        self::flushCache();
//        echo json_encode($update);
//        exit;
//    }
     public function publish(Request $request)
    {
        $requestArr = Request::all();
//        $request = (object) $requestArr;
        $val = Request::get('val');
        $alias = Request::input('alias');
        $update = MyLibrary::setPublishUnpublish($alias, $val, 'Powerpanel\Service\Models\Service');
        self::flushCache();
        echo json_encode($update);
        exit;
    }

    public function recordHistory($data = false) {

        $ServiceCategory = ServiceCategory::getCatData($data->intFKCategory);
        if (isset($data->fkIntDocId)) {
            $DocId = Document::getRecordById($data->fkIntDocId);
            $docname = stripslashes($DocId->txtDocumentName);
        } else {
            $DocId = '';
            $docname = '';
        }
//        if (isset($data->txtDescription) && $data->txtDescription != '') {
//            $desc = FrontPageContent_Shield::renderBuilder($data->txtDescription);
//            if (isset($desc['response']) && !empty($desc['response'])) {
//                $desc = $desc['response'];
//            } else {
//                $desc = '---';
//            }
//        } else {
//            $desc = '---';
//        }

            $desc = '---';
        $returnHtml = '';
        $returnHtml .= '<table class="new_table_desing table table-striped table-bordered table-hover">
				<thead>
						<tr>
								<th align="center">' . trans("service::template.common.title") . '</th>
								<th align="center">Category</th>
																																<th align="center">Documents</th>
																				<th align="center">Short Description</th>
																				<th align="center">Description</th>
								<th align="center">Start Date</th>
								<th align="center">End Date</th>
																																<th align="center">Meta Title</th>
																																 <th align="center">Meta Description</th>
								<th align="center">' . trans("service::template.common.publish") . '</th>
						</tr>
				</thead>
				<tbody>
						<tr>
								<td align="center">' . stripslashes($data->varTitle) . '</td>
																																<td align="center">' . $ServiceCategory->varTitle . '</td>
																																		<td align="center">' . $docname . '</td>
					<td align="center">' . stripslashes($data->varShortDescription) . '</td>
					<td align="center">' . $desc . '</td>
								<td align="center">' . $data->serviceCode . '</td>
								<td align="center">' . $data->applicationFee . '</td>
																																		<td align="center">' . stripslashes($data->varMetaTitle) . '</td>
																				<td align="center">' . stripslashes($data->varMetaDescription) . '</td>
								<td align="center">' . $data->chrPublish . '</td>
						</tr>
				</tbody>
				</table>';
        return $returnHtml;
    }

    public function newrecordHistory($data = false, $newdata = false) {


        $ServiceCategory = ServiceCategory::getCatData($newdata->intFKCategory);
        if ($data->varTitle != $newdata->varTitle) {
            $titlecolor = 'style="background-color:#f5efb7"';
        } else {
            $titlecolor = '';
        }
        if ($data->intFKCategory != $newdata->intFKCategory) {
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
        if ($data->varShortDescription != $newdata->varShortDescription) {
            $ShortDescriptioncolor = 'style="background-color:#f5efb7"';
        } else {
            $ShortDescriptioncolor = '';
        }
        if ($data->txtDescription != $newdata->txtDescription) {
            $desccolor = 'style="background-color:#f5efb7"';
        } else {
            $desccolor = '';
        }

//        if (isset($newdata->txtDescription) && $newdata->txtDescription != '') {
//            $desc = FrontPageContent_Shield::renderBuilder($newdata->txtDescription);
//            if (isset($desc['response']) && !empty($desc['response'])) {
//                $desc = $desc['response'];
//            } else {
//                $desc = '---';
//            }
//        } else {
//            $desc = '---';
//        }
        
            $desc = '---';

        $returnHtml = '';
        $returnHtml .= '<table class="new_table_desing table table-striped table-bordered table-hover">
				<thead>
						<tr>
								<th align="center">' . trans("service::template.common.title") . '</th>
								<th align="center">Category</th>
								<th align="center">Documents</th>
								<th align="center">Short Description</th>
								<th align="center">Description</th>
								<th align="center">Start Date</th>
								<th align="center">End Date</th>
																																<th align="center">Meta Title</th>
																																<th align="center">Meta Description</th>
								<th align="center">' . trans("service::template.common.publish") . '</th>
						</tr>
				</thead>
				<tbody>
						<tr>
								<td align="center" ' . $titlecolor . '>' . stripslashes($newdata->varTitle) . '</td>
																																<td align="center" ' . $catcolor . '>' . $ServiceCategory->varTitle . '</td>
																																 <td align="center" ' . $DocIdcolor . '>' . $docname . '</td>
																																		 <td align="center" ' . $ShortDescriptioncolor . '>' . stripslashes($newdata->varShortDescription) . '</td>
																																				 <td align="center" ' . $desccolor . '>' . $desc . '</td>

								<td align="center" ' . $sdatecolor . '>' . $newdata->serviceCode . '</td>
								<td align="center" ' . $edatecolor . '>' . $newdata->applicationFee . '</td>
																																		<td align="center" ' . $metatitlecolor . '>' . stripslashes($newdata->varMetaTitle) . '</td>
																			 <td align="center" ' . $metadesccolor . '>' . stripslashes($newdata->varMetaDescription) . '</td>
								<td align="center" ' . $Publishcolor . '>' . $newdata->chrPublish . '</td>
						</tr>
				</tbody>
				</table>';
        return $returnHtml;
    }

    /**
     * This method stores service modifications
     * @return  View
     * @since   2017-11-10
     * @author  NetQuick
     */
    public function addPreview(Guard $auth) {
        $data = Request::input();
        $rules = array(
            'title' => 'required|max:200|handle_xss|no_url',
            'chrMenuDisplay' => 'required',
            'varMetaTitle' => 'required|max:500|handle_xss|no_url',
            'varMetaDescription' => 'required|max:500|handle_xss|no_url',
            'short_description' => 'required|handle_xss|no_url',
            // 'description' => 'required',
            'category_id' => 'required',
        );
        $actionMessage = trans('service::template.common.oppsSomethingWrong');
        $messsages = array();
        $validator = Validator::make($data, $rules, $messsages);
        $serviceArr = [];
        if (Config::get('Constant.DEFAULT_VISUAL') == 'Y') {
            if ($data['section'] != '[]') {
                $vsection = $data['section'];
            } else {
                $vsection = '';
            }
        } else {
            $vsection = $data['description'];
        }
        $serviceArr['varTitle'] = stripslashes(trim($data['title']));

        $serviceArr['txtDescription'] = $vsection;

        $serviceArr['chrPublish'] = $data['chrMenuDisplay'];
        $serviceArr['chrIsPreview'] = 'Y';
        $serviceArr['intFKCategory'] = isset($data['category_id']) ? $data['category_id'] : null;

        $id = $data['previewId'];
        if (is_numeric($id)) { #Edit post Handler=======
            $service = Service::getRecordForLogById($id);
            $whereConditions = ['id' => $service->id];
            if ($serviceArr['oldAlias'] != $serviceArr['alias']) {
                Alias::updateAlias($serviceArr['oldAlias'], $serviceArr['alias']);
            }
            $update = CommonModel::updateRecords($whereConditions, $serviceArr, false, 'Powerpanel\Service\Models\Service');
            if ($update) {
                if (!empty($id)) {
                    $logArr = MyLibrary::logData($service->id);
                    if (Auth::user()->can('log-advanced')) {
                        $newNewsObj = Service::getRecordForLogById($service->id);
                        $oldRec = $this->recordHistory($service);
                        $newRec = $this->recordHistory($newNewsObj);
                        $logArr['old_val'] = $oldRec;
                        $logArr['new_val'] = $newRec;
                    }
                    $logArr['varTitle'] = stripslashes(trim($data['title']));
                    Log::recordLog($logArr);
                    if (Auth::user()->can('recent-updates-list')) {
                        if (!isset($newNewsObj)) {
                            $newNewsObj = Service::getRecordForLogById($service->id);
                        }
                        $notificationArr = MyLibrary::notificationData($service->id, $newNewsObj);
                        RecentUpdates::setNotification($notificationArr);
                    }
                    self::flushCache();
                    $actionMessage = trans('service::template.serviceModule.updateMessage');
                }
            }
        } else { #Add post Handler=======
            $id = CommonModel::addRecord($serviceArr, 'Powerpanel\Service\Models\Service');
        }

        return json_encode(array('status' => $id, 'alias' => $serviceArr['alias'], 'message' => trans('service::template.pageModule.pageUpdate')));
    }

    public static function flushCache() {
        Cache::tags('Service')->flush();
    }

    public function getChildData() {
        $childHtml = "";
        $Service_childData = "";
        $Service_childData = Service::getChildGrid();


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


        if (count($Service_childData) > 0) {
            foreach ($Service_childData as $child_row) {

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
                        data-bs-toggle='tooltip' data-bs-placement='bottom' title='" . trans('service::template.common.edit') . "'
                        href='" . route('powerpanel.service.edit', array('alias' => $child_row->id)) . "'>
                                                            <i class='ri-pencil-line'></i></a></td>";
                    } else {
                        $childHtml .= "<td class=\"text-center\"><span class='mob_show_title'>Edit: </span>-</td>";
                    }

                    if ($child_row->chrApproved == 'N') {
                        $childHtml .= "<td class=\"text-center\"><span class='mob_show_title'>Status: </span> <a class=\"approve_icon_btn\" data-bs-toggle='tooltip' data-bs-placement='bottom' title='" . trans("service::template.common.comments") . "' href=\"javascript:;\" onclick=\"loadModelpopup('" . $child_row->id . "','" . $child_row->UserID . "','" . Config::get('Constant.MODULE.MODEL_NAME') . "','" . $child_row->fkMainRecord . "')\"><i class=\"ri-chat-1-line\"></i> </a> &nbsp;&nbsp;<a class=\"approve_icon_btn me-2\" onclick=\"update_mainrecord('" . $child_row->id . "','" . $child_row->fkMainRecord . "','" . $child_row->UserID . "','A');\" data-bs-toggle='tooltip' data-bs-placement='bottom' title='" . trans('service::template.common.clickapprove') . "'  href=\"javascript:void(0);\"><i class=\"ri-checkbox-line\"></i></a></td>";
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

    public function getChildData_rollback() {
        $child_rollbackHtml = "";
        $Cmspage_rollbackchildData = "";
        $Cmspage_rollbackchildData = Service::getChildrollbackGrid();
        $child_rollbackHtml .= "<div class=\"producttbl producttb2\" style=\"\">";
        $child_rollbackHtml .= "<table class=\"new_table_desing table table-striped table-bordered table-hover table-checkable dataTable\" id=\"email_log_datatable_ajax\">
																<tr role=\"row\">
																																																																								<th class=\"text-center\">Title</th>
																		<th class=\"text-center\">Date</th>
																		<th class=\"text-center\">User</th>
																		<th class=\"text-center\">Preview</th>
																		<th class=\"text-center\">Status</th>";
        $child_rollbackHtml .= "         </tr>";
        if (count($Cmspage_rollbackchildData) > 0) {
            foreach ($Cmspage_rollbackchildData as $child_rollbacrow) {
                $child_rollbackHtml .= "<tr role=\"row\">";
                $child_rollbackHtml .= '<td class="text-center"><span class="mob_show_title">Title: </span>' . $child_rollbacrow->varTitle . '</td>';
                $child_rollbackHtml .= "<td class=\"text-center\"><span class='mob_show_title'>Date: </span>" . date('M d Y h:i A', strtotime($child_rollbacrow->created_at)) . "</td>";
                $child_rollbackHtml .= "<td class=\"text-center\"><span class='mob_show_title'>User: </span>" . CommonModel::getUserName($child_rollbacrow->UserID) . "</td>";
                $categoryRecordAlias = MyLibrary::getRecordAliasByModuleNameRecordId("service-category", $child_rollbacrow->intFKCategory);
                $previewlink = url('/previewpage?url=' . MyLibrary::getFrontUri('service')['uri'] . '/' . $child_rollbacrow->id . '/preview/detail');
                $child_rollbackHtml .= "<td class=\"text-center\"><span class='mob_show_title'>Preview: </span><a class='icon_round' href=" . $previewlink . " target='_blank'><i class=\"ri-computer-line\"></i></a></td>";
                if ($child_rollbacrow->chrApproved == 'Y') {
                    $child_rollbackHtml .= "<td class=\"text-center\"><span class='mob_show_title'>Status: </span><i class=\"ri-checkbox-circle-line\" style=\"color: #1080F2;font-size:30px;\"></i></td>";
                } else {
                    // $child_rollbackHtml .= "<td class=\"text-center\"><span class='mob_show_title'>Status: </span><a onclick=\"update_mainrecord('" . $child_rollbacrow->id . "','" . $child_rollbacrow->fkMainRecord . "','" . $child_rollbacrow->UserID . "','R');\"  class=\"approve_icon_btn\">
                    //                         <i class=\"ri-history-line\"></i>   <span>RollBack</span>
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

    public function ApprovedData_Listing(Request $request) {
        $requestArr = Request::all();
        $request = (object) $requestArr;
        $main_id = Request::post('main_id');
        $approvalid = Request::post('id');
        $id = Request::post('id');
        $flag = Request::post('flag');
        $message = Service::approved_data_Listing($request);
        $newCmsPageObj = Service::getRecordForLogById($main_id);
        $approval_obj = Service::getRecordForLogById($approvalid);
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
        $careers = Service::getRecordForLogById($id);
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
        $arrResults = Service::getBuilderRecordList($filterArr);
        $found = $arrResults->toArray();
        if (!empty($found)) {
            foreach ($arrResults as $key => $value) {
                $rows .= $this->tableDataBuilder($value, false, $filterArr['selected']);
            }
        } else {
            $rows .= '<tr id="not-found"><td colspan="4" align="center">No records found.</td></tr>';
        }
        $iTotalRecords = CommonModel::getTotalRecordCount('Powerpanel\Service\Models\Service', true, true);
        $records["data"] = $rows;
        $records["found"] = count($found);
        $records["recordsTotal"] = $iTotalRecords;
        return json_encode($records);
    }

    public function tableDataBuilder($value = false, $fcnt = false, $selected = []) {

        $publish_action = '';
        $dtFormat = Config::get('Constant.DEFAULT_DATE_FORMAT');
        $categories = ServiceCategory::getRecordByIds(explode(',', $value->intFKCategory))->toArray();
        $categories = array_column($categories, 'varTitle');
        $categories = implode(', ', $categories);



        $record = '<tr ' . (in_array($value->id, $selected) ? 'class="selected-record"' : '') . '>';
        $record .= '<td width="1%" align="center">';
        $record .= '<label class="mt-checkbox mt-checkbox-outline">';
        $record .= '<input type="checkbox" data-title="' . $value->varTitle . '" name="delete[]" class="form-check-input chkChoose" ' . (in_array($value->id, $selected) ? 'checked' : '') . ' value="' . $value->id . '">';
        $record .= '<span></span>';
        $record .= '</label>';
        $record .= '</td>';
        $record .= '<td width="20%" align="left">';
        $record .= $value->varTitle;
        $record .= '</td>';
        $record .= '<td width="20%" align="left">';
        $record .= $categories;
        $record .= '</td>';
        $record .= '<td width="20%" align="center">';
        $record .= $value->serviceCode;
        $record .= '</td>';
        $record .= '<td width="20%" align="center">';
        $record .= $value->applicationFee;
        $record .= '</td>';
        $record .= '<td width="20%" align="center">';
        $record .= date($dtFormat, strtotime($value->updated_at));
        $record .= '</td>';
        $record .= '</tr>';
        return $record;
    }

    public function rollBackRecord(Request $request) {

        $message = 'Previous record is not available';
        $requestArr = Request::all();
        $request = (object) $requestArr;
        $previousRecord = Service::getPreviousRecordByMainId($request->id);
        if (!empty($previousRecord)) {

            $main_id = $previousRecord->fkMainRecord;
            $request->id = $previousRecord->id;
            $request->main_id = $main_id;

            $message = Service::approved_data_Listing($request);
            /* notification for user to record approved */
            $services = Service::getRecordForLogById($previousRecord->id);
            if(!empty($services))
            {
                if (method_exists($this->MyLibrary, 'userNotificationData')) {
                    $userNotificationArr = MyLibrary::userNotificationData(Config::get('Constant.MODULE.ID'));
                    $userNotificationArr['fkRecordId'] = $previousRecord->id;
                    $userNotificationArr['txtNotification'] = 'Your request has been approved by ' . ucfirst(auth()->user()->name) . ' (' . ucfirst(Config::get('Constant.MODULE.NAME')) . ')';
                    $userNotificationArr['fkIntUserId'] = Auth::user()->id;
                    $userNotificationArr['chrNotificationType'] = 'A';
                    $userNotificationArr['intOnlyForUserId'] = $services->UserID;
                    UserNotification::addRecord($userNotificationArr);
                }
            }
            
            /* notification for user to record approved */
            $newServiceObj = Service::getRecordForLogById($main_id);
            $restoredata = Config::get('Constant.ROLLBACK_RECORD');
            if(!empty($newServiceObj)) {
                $logArr = MyLibrary::logData($main_id, false, $restoredata);
                $logArr['varTitle'] = stripslashes($newServiceObj->varTitle);
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
