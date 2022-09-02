<?php

namespace Powerpanel\Careers\Controllers\Powerpanel;

use App\Alias;
use App\CommonModel;
use App\Document;
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
use Carbon\Carbon;
use Config;
use DB;
use File;
use Illuminate\Support\Facades\Redirect;
use Powerpanel\CareerCategory\Models\CareerCategory;
use Powerpanel\Careers\Models\Careers;
use Powerpanel\RoleManager\Models\Role_user;
use Powerpanel\Workflow\Models\Comments;
use Powerpanel\Workflow\Models\Workflow;
use Powerpanel\Workflow\Models\WorkflowLog;
use Request;
use Validator;

class CareersController extends PowerpanelController
{

    /**
     * Create a new controller instance.
     * @return void
     */
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
     * This method handels load careers grid
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
        }

        $total = Careers::getRecordCount(false, false, $userIsAdmin, $this->currentUserRoleSector);
        $draftTotalRecords = Careers::getRecordCountforListDarft(false, true, $userIsAdmin, array(), $this->currentUserRoleSector);
        $trashTotalRecords = Careers::getRecordCountforListTrash(false, false, $userIsAdmin, [], $this->currentUserRoleSector);
        $favoriteTotalRecords = Careers::getRecordCountforListFavorite(false, false, $userIsAdmin, [], $this->currentUserRoleSector);
        $approvalTotalRecords = Careers::getRecordCountListApprovalTab(false, false, $userIsAdmin, array(), $this->currentUserRoleSector);
        $this->breadcrumb['title'] = trans('careers::template.careersModule.managecareers');


        if (method_exists($this->CommonModel, 'GridColumnData')) {
            $settingdata = CommonModel::GridColumnData(Config::get('Constant.MODULE.ID'));
            $settingarray = array();
            foreach ($settingdata as $sdata) {
                $settingarray[$sdata->chrtab][] = $sdata->columnid;
            }
        } else {
            $settingarray = '';
        }

        $careerCategory = CareerCategory::getCatWithParent();
        $settingarray = json_encode($settingarray);

        return view('careers::powerpanel.index', ['userIsAdmin' => $userIsAdmin, 'iTotalRecords' => $total, 'breadcrumb' => $this->breadcrumb, 'approvalTotalRecords' => $approvalTotalRecords, 'draftTotalRecords' => $draftTotalRecords, 'trashTotalRecords' => $trashTotalRecords, 'favoriteTotalRecords' => $favoriteTotalRecords,'careerCategory' => $careerCategory, 'settingarray' => $settingarray]);
    }



    public function get_list()
    {
        $filterArr = [];
        $records = array();
        $records["data"] = array();
        $filterArr['orderColumnNo'] = (!empty(Request::input('order')[0]['column']) ? Request::input('order')[0]['column'] : '');
        $filterArr['orderByFieldName'] = (!empty(Request::input('columns')[$filterArr['orderColumnNo']]['name']) ? Request::input('columns')[$filterArr['orderColumnNo']]['name'] : '');
        $filterArr['orderTypeAscOrDesc'] = (!empty(Request::input('order')[0]['dir']) ? Request::input('order')[0]['dir'] : '');
        $filterArr['customFilterIdentity'] = !empty(Request::get('customFilterIdentity')) ? Request::get('customFilterIdentity') : '';
        $filterArr['statusFilter'] = !empty(Request::input('statusValue')) ? Request::input('statusValue') : '';
        $filterArr['sectorFilter'] = !empty(Request::input('sectorValue')) ? Request::input('sectorValue') : '';
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
        $arrResults = Careers::getRecordList($filterArr, $isAdmin, $ignoreId, $this->currentUserRoleSector);
        $iTotalRecords = Careers::getRecordCountforList($filterArr, true, $isAdmin, $ignoreId, $this->currentUserRoleSector);
        $allRecordsCount = Careers::getRecordCount(false, false, $isAdmin, $this->currentUserRoleSector);

        if (!empty($arrResults)) {
            $currentUserID = auth()->user()->id;
            $permit = [
                'cancareersedit' => Auth::user()->can('careers-edit'),
                'cancareerspublish' => Auth::user()->can('careers-publish'),
                'cancareersdelete' => Auth::user()->can('careers-delete'),
                'cancareersreviewchanges' => Auth::user()->can('careers-reviewchanges'),
                'canloglist' => Auth::user()->can('log-list'),
            ];

            foreach ($arrResults as $key => $value) {
                if (!in_array($value->id, $ignoreId)) {
                    $records['data'][] = $this->tableData($value, $permit, $currentUserID, $allRecordsCount);
                }
            }
        }

        $NewRecordsCount = Careers::getNewRecordsCount($isAdmin, $this->currentUserRoleSector);
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

    public function get_list_New()
    {
        $filterArr = [];
        $records = array();
        $records["data"] = array();
        $filterArr['orderColumnNo'] = (!empty(Request::input('order')[0]['column']) ? Request::input('order')[0]['column'] : '');
        $filterArr['orderByFieldName'] = (!empty(Request::input('columns')[$filterArr['orderColumnNo']]['name']) ? Request::input('columns')[$filterArr['orderColumnNo']]['name'] : '');
        $filterArr['orderTypeAscOrDesc'] = (!empty(Request::input('order')[0]['dir']) ? Request::input('order')[0]['dir'] : '');
        $filterArr['customFilterIdentity'] = !empty(Request::get('customFilterIdentity')) ? Request::get('customFilterIdentity') : '';
        $filterArr['statusFilter'] = !empty(Request::input('statusValue')) ? Request::input('statusValue') : '';
        $filterArr['sectorFilter'] = !empty(Request::input('sectorValue')) ? Request::input('sectorValue') : '';
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
        $arrResults = Careers::getRecordList_tab1($filterArr, $isAdmin, $ignoreId, $this->currentUserRoleSector);
        $iTotalRecords = Careers::getRecordCountListApprovalTab($filterArr, true, $isAdmin, $ignoreId, $this->currentUserRoleSector);

        if (!empty($arrResults)) {
            $currentUserID = auth()->user()->id;
            $permit = [
                'cancareersedit' => Auth::user()->can('careers-edit'),
                'cancareerspublish' => Auth::user()->can('careers-publish'),
                'cancareersdelete' => Auth::user()->can('careers-delete'),
                'cancareersreviewchanges' => Auth::user()->can('careers-reviewchanges'),
                'canloglist' => Auth::user()->can('log-list'),
            ];

            foreach ($arrResults as $key => $value) {
                if (!in_array($value->id, $ignoreId)) {
                    $records['data'][] = $this->tableData_tab1($value, $permit, $currentUserID);
                }
            }
        }

        $NewRecordsCount = Careers::getNewRecordsCount($isAdmin, $this->currentUserRoleSector);
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

    public function get_list_favorite()
    {
        $filterArr = [];
        $records = array();
        $records["data"] = array();
        $filterArr['orderColumnNo'] = (!empty(Request::input('order')[0]['column']) ? Request::input('order')[0]['column'] : '');
        $filterArr['orderByFieldName'] = (!empty(Request::input('columns')[$filterArr['orderColumnNo']]['name']) ? Request::input('columns')[$filterArr['orderColumnNo']]['name'] : '');
        $filterArr['orderTypeAscOrDesc'] = (!empty(Request::input('order')[0]['dir']) ? Request::input('order')[0]['dir'] : '');
        $filterArr['customFilterIdentity'] = !empty(Request::get('customFilterIdentity')) ? Request::get('customFilterIdentity') : '';
        $filterArr['statusFilter'] = !empty(Request::input('statusValue')) ? Request::input('statusValue') : '';
        $filterArr['sectorFilter'] = !empty(Request::input('sectorValue')) ? Request::input('sectorValue') : '';
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
        $arrResults = Careers::getRecordListFavorite($filterArr, $isAdmin, $ignoreId, $this->currentUserRoleSector);
        $iTotalRecords = Careers::getRecordCountforListFavorite($filterArr, true, $isAdmin, $ignoreId, $this->currentUserRoleSector);

        if (!empty($arrResults)) {
            $currentUserID = auth()->user()->id;
            $permit = [
                'cancareersedit' => Auth::user()->can('careers-edit'),
                'cancareerspublish' => Auth::user()->can('careers-publish'),
                'cancareersdelete' => Auth::user()->can('careers-delete'),
                'cancareersreviewchanges' => Auth::user()->can('careers-reviewchanges'),
                'canloglist' => Auth::user()->can('log-list'),
            ];

            foreach ($arrResults as $key => $value) {
                if (!in_array($value->id, $ignoreId)) {
                    $records['data'][] = $this->tableDataFavorite($value, $permit, $currentUserID);
                }
            }
        }

        $NewRecordsCount = Careers::getNewRecordsCount($isAdmin, $this->currentUserRoleSector);
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

    public function get_list_draft()
    {
        $filterArr = [];
        $records = array();
        $records["data"] = array();
        $filterArr['orderColumnNo'] = (!empty(Request::input('order')[0]['column']) ? Request::input('order')[0]['column'] : '');
        $filterArr['orderByFieldName'] = (!empty(Request::input('columns')[$filterArr['orderColumnNo']]['name']) ? Request::input('columns')[$filterArr['orderColumnNo']]['name'] : '');
        $filterArr['orderTypeAscOrDesc'] = (!empty(Request::input('order')[0]['dir']) ? Request::input('order')[0]['dir'] : '');
        $filterArr['customFilterIdentity'] = !empty(Request::get('customFilterIdentity')) ? Request::get('customFilterIdentity') : '';
        $filterArr['statusFilter'] = !empty(Request::input('statusValue')) ? Request::input('statusValue') : '';
        $filterArr['sectorFilter'] = !empty(Request::input('sectorValue')) ? Request::input('sectorValue') : '';
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
        $arrResults = Careers::getRecordListDraft($filterArr, $isAdmin, $ignoreId, $this->currentUserRoleSector);
        $iTotalRecords = Careers::getRecordCountforListDarft($filterArr, true, $isAdmin, $ignoreId, $this->currentUserRoleSector);

        if (!empty($arrResults)) {
            $currentUserID = auth()->user()->id;
            $permit = [
                'cancareersedit' => Auth::user()->can('careers-edit'),
                'cancareerspublish' => Auth::user()->can('careers-publish'),
                'cancareersdelete' => Auth::user()->can('careers-delete'),
                'cancareersreviewchanges' => Auth::user()->can('careers-reviewchanges'),
                'canloglist' => Auth::user()->can('log-list'),
            ];

            foreach ($arrResults as $key => $value) {
                if (!in_array($value->id, $ignoreId)) {
                    $records['data'][] = $this->tableDataDraft($value, $permit, $currentUserID);
                }
            }
        }

        $NewRecordsCount = Careers::getNewRecordsCount($isAdmin, $this->currentUserRoleSector);
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

    public function get_list_trash()
    {
        $filterArr = [];
        $records = array();
        $records["data"] = array();
        $filterArr['orderColumnNo'] = (!empty(Request::input('order')[0]['column']) ? Request::input('order')[0]['column'] : '');
        $filterArr['orderByFieldName'] = (!empty(Request::input('columns')[$filterArr['orderColumnNo']]['name']) ? Request::input('columns')[$filterArr['orderColumnNo']]['name'] : '');
        $filterArr['orderTypeAscOrDesc'] = (!empty(Request::input('order')[0]['dir']) ? Request::input('order')[0]['dir'] : '');
        $filterArr['customFilterIdentity'] = !empty(Request::get('customFilterIdentity')) ? Request::get('customFilterIdentity') : '';
        $filterArr['statusFilter'] = !empty(Request::input('statusValue')) ? Request::input('statusValue') : '';
        $filterArr['sectorFilter'] = !empty(Request::input('sectorValue')) ? Request::input('sectorValue') : '';
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
        $arrResults = Careers::getRecordListTrash($filterArr, $isAdmin, $ignoreId, $this->currentUserRoleSector);
        $iTotalRecords = Careers::getRecordCountforListTrash($filterArr, true, $isAdmin, $ignoreId, $this->currentUserRoleSector);

        if (!empty($arrResults)) {
            $currentUserID = auth()->user()->id;
            $permit = [
                'cancareersedit' => Auth::user()->can('careers-edit'),
                'cancareerspublish' => Auth::user()->can('careers-publish'),
                'cancareersdelete' => Auth::user()->can('careers-delete'),
                'cancareersreviewchanges' => Auth::user()->can('careers-reviewchanges'),
                'canloglist' => Auth::user()->can('log-list'),
            ];

            foreach ($arrResults as $key => $value) {
                if (!in_array($value->id, $ignoreId)) {
                    $records['data'][] = $this->tableDataTrash($value, $permit, $currentUserID);
                }
            }
        }

        $NewRecordsCount = Careers::getNewRecordsCount($isAdmin, $this->currentUserRoleSector);
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




    public function edit($alias = false)
    {
        $module = Modules::getModule('career-category');
        $category = CareerCategory::getCatWithParent($module->id);
        $templateData = array();
        $imageManager = true;
        $userIsAdmin = false;
        if (isset($this->currentUserRoleData) && !empty($this->currentUserRoleData)) {
            if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                $userIsAdmin = true;
            }
        }

        $rollType = array('Roll 1', 'Roll 2', 'Roll 3');
        if (!is_numeric($alias)) {
            $total = Careers::getRecordCount(false, false, $userIsAdmin, $this->currentUserRoleSector);
            if (auth()->user()->can('careers-create') || $userIsAdmin) {
                $total = $total + 1;
            }
            $this->breadcrumb['title'] = trans('careers::template.careersModule.addcareers');
            $this->breadcrumb['module'] = trans('careers::template.careersModule.managecareers');
            $this->breadcrumb['url'] = 'powerpanel/careers';
            $this->breadcrumb['inner_title'] = '';
            $templateData['total'] = $total;
            $templateData['careerCategory'] = $category;
            $templateData['breadcrumb'] = $this->breadcrumb;
            $templateData['imageManager'] = $imageManager;
        } else {
            $id = $alias;
            $careers = Careers::getRecordById($id);
            if (empty($careers)) {
                return redirect()->route('powerpanel.careers.add');
            }
            if ($careers->fkMainRecord != '0') {
                $careers_highLight = Careers::getRecordById($careers->fkMainRecord);
                $templateData['careers_highLight'] = $careers_highLight;
                $metaInfo_highLight['varMetaTitle'] = $careers_highLight['varMetaTitle'];
                $metaInfo_highLight['varMetaDescription'] = $careers_highLight['varMetaDescription'];
                $metaInfo_highLight['varTags'] = $careers_highLight['varTags'];
            } else {
                $templateData['careers_highLight'] = "";
                $metaInfo_highLight['varMetaTitle'] = "";
                $metaInfo_highLight['varTags'] = "";
                $metaInfo_highLight['varMetaDescription'] = "";
            }
            $metaInfo = array(
                'varMetaTitle' => $careers->varMetaTitle,
                'varMetaDescription' => $careers->varMetaDescription,
            );
            if (method_exists($this->MyLibrary, 'getModulePageAliasByModuleName')) {
                $categorypagereocrdlink = MyLibrary::getModulePageAliasByModuleName('careers');
            }
            if (!empty($categorypagereocrdlink)) {
                $varURL = $categorypagereocrdlink . '/' . $careers->alias->varAlias;
            } else {
                $varURL = $careers->alias->varAlias;
            }
            $metaInfo = array('varMetaTitle' => $careers->varMetaTitle,
                'varMetaDescription' => $careers->varMetaDescription,
                'varTags' => $careers->varTags,
            );
            $this->breadcrumb['title'] = trans('careers::template.careersModule.editcareers');
            $this->breadcrumb['module'] = trans('careers::template.careersModule.managecareers');
            $this->breadcrumb['url'] = 'powerpanel/careers';
            $this->breadcrumb['inner_title'] = $careers->varTitle;
            $templateData['careers'] = $careers;
            $templateData['id'] = $id;
            $templateData['careerCategory'] = $category;
            $templateData['breadcrumb'] = $this->breadcrumb;
            $templateData['metaInfo'] = $metaInfo;
            $templateData['metaInfo_highLight'] = $metaInfo_highLight;
            $templateData['imageManager'] = $imageManager;
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
                $templateData['chrNeedAddPermission'] = $workFlowByCat->chrNeedAddPermission;
                $templateData['charNeedApproval'] = $workFlowByCat->charNeedApproval;
            } else {
                $templateData['chrNeedAddPermission'] = 'N';
                $templateData['charNeedApproval'] = 'N';
            }
        } else {
            $templateData['chrNeedAddPermission'] = 'N';
            $templateData['charNeedApproval'] = 'N';
        }
        //End Button Name Change For User Side
        $templateData['careerCategory'] = $category;
        $templateData['rollType'] = $rollType;
        $templateData['userIsAdmin'] = $userIsAdmin;
        $templateData['MyLibrary'] = $this->MyLibrary;
        return view('careers::powerpanel.actions', $templateData);

    }

    /**
     * This method stores careers modifications
     * @return  View
     * @since   2017-07-21
     * @author  NetQuick
     */
    public function handlePost(Request $request)
    {
        $userIsAdmin = false;
        if (isset($this->currentUserRoleData) && !empty($this->currentUserRoleData)) {
            if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                $userIsAdmin = true;
            }
        }
        $postArr = Request::all();
        $messsages = [
            'title.required' => 'Title field is required.',
            'sector.required' => 'Please Select Sector Type.',
            'salary.required' => 'Salary field is required.',
            'employmentType.required' => 'Please Select EmploymentType.',
            'position.required' => 'Position field is required.',
            'experience.required' => 'Experience field is required.',
            'requirements.required' => 'Requirement field is required.',
            'alias.required' => 'Alias field is required.',
            //'order.required' => trans('careers::template.careersModule.displayGreaterThan'),
            //'order.greater_than_zero' => trans('careers::template.careersModule.displayGreaterThan'),
            'varMetaTitle.required' => trans('careers::template.careersModule.metaTitle'),
            'varMetaDescription.required' => trans('careers::template.careersModule.metaDescription'),
        ];
        $rules = [
            'title' => 'required|max:160|handle_xss|no_url',
            'sector' => 'required',
            'salary' => 'required|handle_xss|no_url',
            'employmentType' => 'required',
            'position' => 'required|handle_xss|numeric|no_url',
            'experience' => 'required|max:160|handle_xss|no_url',
            'requirements' => 'required|handle_xss|no_url',
            //'order' => 'required|greater_than_zero|handle_xss|no_url',
            'chrMenuDisplay' => 'required',
            'alias' => 'required',
            'varMetaTitle' => 'required|max:160|handle_xss|no_url',
            'varMetaDescription' => 'required|max:200|handle_xss|no_url',
        ];

        $validator = Validator::make($postArr, $rules, $messsages);
        if ($validator->passes()) {
            if (Config::get('Constant.DEFAULT_VISUAL') == 'Y') {
                if ($postArr['section'] != '[]') {
                    $vsection = $postArr['section'];
                } else {
                    $vsection = '';
                }
            } else {
                $vsection = $postArr['description'];
            }
            $careersArr = [];
            $module = Modules::getModuleById(Config::get('Constant.MODULE.ID'));
            if (isset($this->currentUserRoleData)) {
                $currentUserRoleData = $this->currentUserRoleData;
            }
            $id = Request::segment(3);
            $actionMessage = trans('careers::template.careersModule.updateMessage');
            if (is_numeric($id)) { #Edit post Handler=======
            $careers = Careers::getRecordForLogById($id);
                $updatecareersFields = [];
                $updatecareersFields['varTitle'] = stripslashes(trim($postArr['title']));
                $updatecareersFields['varSector'] = $postArr['sector'];
                $updatecareersFields['intJobCategory'] = NULL;
                $updatecareersFields['intSalary'] = $postArr['salary'];
                $updatecareersFields['txtPosition'] = $postArr['position'];
                $updatecareersFields['txtExperience'] = $postArr['experience'];
                if ($postArr['employmentType'] == 'F') {
                    $updatecareersFields['employmentType'] = 'F';
                } else {
                    $updatecareersFields['employmentType'] = $postArr['employmentType'];
                }
                $updatecareersFields['varRequirements'] = $postArr['requirements'];

                $updatecareersFields['txtDescription'] = $vsection;
                $updatecareersFields['varMetaTitle'] = stripslashes(trim($postArr['varMetaTitle']));
                $updatecareersFields['varMetaDescription'] = stripslashes(trim($postArr['varMetaDescription']));
                $updatecareersFields['varTags'] = trim($postArr['tags']);

                // if (Config::get('Constant.CHRSearchRank') == 'Y') {
                //     $updatecareersFields['intSearchRank'] = $postArr['search_rank'];
                // }
                $updatecareersFields['chrPublish'] = $postArr['chrMenuDisplay'];

                $updatecareersFields['dtDateTime'] = !empty($postArr['start_date_time']) ? date('Y-m-d H:i:s', strtotime(str_replace('/', '-',$postArr['start_date_time']))) : date('Y-m-d H:i:s');
                $updatecareersFields['dtEndDateTime'] = !empty($postArr['end_date_time']) ? date('Y-m-d H:i:s', strtotime(str_replace('/', '-',$postArr['end_date_time']))) : null;

                $updatecareersFields['UserID'] = auth()->user()->id;
                if ($postArr['chrMenuDisplay'] == 'D') {
                    $updatecareersFields['chrDraft'] = 'D';
                    $updatecareersFields['chrPublish'] = 'N';
                } else {
                    $updatecareersFields['chrDraft'] = 'N';
                    $updatecareersFields['chrPublish'] = $postArr['chrMenuDisplay'];
                }
                // if (Config::get('Constant.CHRSearchRank') == 'Y') {
                //     $updatecareersFields['intSearchRank'] = $postArr['search_rank'];
                // }
                if (isset($postArr['chrPageActive']) && $postArr['chrPageActive'] != '') {
                    $updatecareersFields['chrPageActive'] = $postArr['chrPageActive'];
                }
                if (isset($postArr['chrPageActive']) && $postArr['chrPageActive'] == 'PP') {
                    $updatecareersFields['varPassword'] = $postArr['new_password'];
                } else {
                    $updatecareersFields['varPassword'] = '';
                }
                if ($postArr['chrMenuDisplay'] == 'D') {
                    $addlog = Config::get('Constant.UPDATE_DRAFT');
                } else {
                    $addlog = '';
                }
                if ($postArr['chrMenuDisplay'] == 'D') {
                    DB::table('menu')->where('intPageId', $id)->where('intfkModuleId', Config::get('Constant.MODULE.ID'))->delete();
                }
                $updatecareersFields['varShortDescription'] = $postArr['short_description'];

                $whereConditions = ['id' => $id];
                if ($careers->chrLock == 'Y' && auth()->user()->id != $careers->LockUserID) {
                    if ($this->currentUserRoleData->chrIsAdmin != 'Y') {
                        $lockedUserData = User::getRecordById($careers->LockUserID, true);
                        $lockedUserName = 'someone';
                        if (!empty($lockedUserData)) {
                            $lockedUserName = $lockedUserData->name;
                        }
                        $actionMessage = "This record has been locked by " . $lockedUserName . ".";
                        return redirect()->route('powerpanel.careers.index')->with('message', $actionMessage);
                    }
                }
                if (File::exists(app_path() . '/Workflow.php') != null || File::exists(base_path() . '/packages/Powerpanel/Workflow/src/Models/Workflow.php') != null) {
                    if (!$userIsAdmin) {
                        $userRole = $currentUserRoleData->id;
                    } else {
                        $userRoleData = Role_user::getUserRoleByUserId($careers->UserID);
                        if (isset($userRoleData->role_id)) {
                            $userRole = $userRoleData->role_id;
                        } else {
                            $userRole = $this->currentUserRoleData->id;
                        }
                    }
                    $workFlowByCat = Workflow::getRecordByCategoryId($module->intFkGroupCode, $userRole, Config::get('Constant.MODULE.ID'));
                    if (empty($workFlowByCat->varUserId) || $userIsAdmin || $workFlowByCat->charNeedApproval == 'N') {
                        if ($postArr['oldAlias'] != $postArr['alias']) {
                            Alias::updateAlias($postArr['oldAlias'], $postArr['alias']);
                        }
                        if ((int) $careers->fkMainRecord === 0 || empty($workFlowByCat->varUserId)) {
                            $update = CommonModel::updateRecords($whereConditions, $updatecareersFields, false, 'Powerpanel\Careers\Models\Careers');
                            if ($update) {
                                if ($id > 0 && !empty($id)) {
                                    
                                    //self::swap_order_edit($postArr['order'], $id);
                                    $logArr = MyLibrary::logData($id, false, $addlog);
                                    if (Auth::user()->can('log-advanced')) {
                                        $newcareersObj = Careers::getRecordForLogById($id);
                                        $oldRec = $this->recordHistory($careers);
                                        $newRec = $this->newrecordHistory($careers, $newcareersObj);
                                        $logArr['old_val'] = $oldRec;
                                        $logArr['new_val'] = $newRec;
                                    }
                                    $logArr['varTitle'] = trim($postArr['title']);
                                    Log::recordLog($logArr);
                                    if (Auth::user()->can('recent-updates-list')) {
                                        if (!isset($newcareersObj)) {
                                            $newcareersObj = Careers::getRecordForLogById($id);
                                        }
                                        $notificationArr = MyLibrary::notificationData($id, $newcareersObj);
                                        RecentUpdates::setNotification($notificationArr);
                                    }
                                    self::flushCache();
                                    if (isset($postArr['saveandexit']) && $postArr['saveandexit'] == 'approvesaveandexit') {
                                        $actionMessage = trans('careers::template.common.recordApprovalMessage');
                                    } else {
                                        $actionMessage = trans('careers::template.careersModule.updateMessage');
                                    }
                                }
                            }
                        } else {
                            $updateModuleFields = $updatecareersFields;
                            $this->insertApprovedRecord($updateModuleFields, $postArr, $id);
                            if (isset($postArr['saveandexit']) && $postArr['saveandexit'] == 'approvesaveandexit') {
                                $actionMessage = trans('careers::template.common.recordApprovalMessage');
                            } else {
                                $actionMessage = trans('careers::template.careersModule.updateMessage');
                            }
                        }
                    } else {
                        if ($workFlowByCat->charNeedApproval == 'Y') {
                            $this->insertApprovalRecord($careers, $postArr, $updatecareersFields);
                            if (isset($postArr['saveandexit']) && $postArr['saveandexit'] == 'approvesaveandexit') {
                                $actionMessage = trans('careers::template.common.recordApprovalMessage');
                            } else {
                                $actionMessage = trans('careers::template.careersModule.updateMessage');
                            }
                        }
                    }
                } else {
                    $update = CommonModel::updateRecords($whereConditions, $updatecareersFields, false, 'Powerpanel\Careers\Models\Careers');
                    $actionMessage = trans('careers::template.careersModule.updateMessage');
                }
            } else { #Add post Handler=======
            $careersArr['dtDateTime'] = !empty($postArr['start_date_time']) ? date('Y-m-d H:i:s', strtotime(str_replace('/', '-',$postArr['start_date_time']))) : date('Y-m-d H:i:s');
                $careersArr['dtEndDateTime'] = !empty($postArr['end_date_time']) ? date('Y-m-d H:i:s', strtotime(str_replace('/', '-',$postArr['end_date_time']))) : null;
                $postArr['description'] = $vsection;
                if (File::exists(app_path() . '/Workflow.php') != null || File::exists(base_path() . '/packages/Powerpanel/Workflow/src/Models/Workflow.php') != null) {
                    $workFlowByCat = Workflow::getRecordByCategoryId($module->intFkGroupCode, $currentUserRoleData->id, Config::get('Constant.MODULE.ID'));
                }
                if (!empty($workFlowByCat->varUserId) && $workFlowByCat->chrNeedAddPermission == 'Y' && !$userIsAdmin) {

                    $careersArr['chrPublish'] = 'N';
                    $careersArr['chrDraft'] = 'N';
                    $careersObj = $this->insertNewRecord($postArr, $careersArr);
                    if ($postArr['chrMenuDisplay'] == 'D') {
                        $careersArr['chrDraft'] = 'D';
                    }
                    $careersArr['chrPublish'] = 'Y';
                    $this->insertApprovalRecord($careersObj, $postArr, $careersArr);
                } else {
                    $careersObj = $this->insertNewRecord($postArr, $careersArr);
                }
                if (isset($postArr['saveandexit']) && $postArr['saveandexit'] == 'approvesaveandexit') {
                    $actionMessage = trans('careers::template.common.recordApprovalMessage');
                } else {
                    $actionMessage = trans('careers::template.careersModule.addMessage');
                }
                $id = $careersObj->id;
            }
            if (method_exists($this->Alias, 'updatePreviewAlias')) {
                Alias::updatePreviewAlias($postArr['alias'], 'N');
            }
            if ((!empty(Request::get('saveandexit')) && Request::get('saveandexit') == 'saveandexit') || !$userIsAdmin) {
                if ($postArr['chrMenuDisplay'] == 'D') {
                    return redirect()->route('powerpanel.careers.index', 'tab=D')->with('message', $actionMessage);
                } else {
                    return redirect()->route('powerpanel.careers.index')->with('message', $actionMessage);
                }
            } else {
                return redirect()->route('powerpanel.careers.edit', $id)->with('message', $actionMessage);
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
        $careers = Careers::getRecordForLogById($id);
            $updatecareersFields = [];
            if (Config::get('Constant.DEFAULT_VISUAL') == 'Y') {
                if ($postArr['section'] != '[]') {
                    $vsection = $postArr['section'];
                } else {
                    $vsection = '';
                }
            } else {
                $vsection = $postArr['description'];
            }
            $updatecareersFields['varTitle'] = stripslashes(trim($postArr['title']));
            $updatecareersFields['txtDescription'] = $vsection;
            $updatecareersFields['varMetaTitle'] = stripslashes(trim($postArr['varMetaTitle']));
            $updatecareersFields['varMetaDescription'] = stripslashes(trim($postArr['varMetaDescription']));
            $updatecareersFields['varTags'] = stripslashes(trim($postArr['tags']));
            if (Config::get('Constant.CHRSearchRank') == 'Y') {
                $updatecareersFields['intSearchRank'] = $postArr['search_rank'];
            }
            $updatecareersFields['chrPublish'] = $postArr['chrMenuDisplay'];

            $updatecareersFields['dtDateTime'] = !empty($postArr['start_date_time']) ? date('Y-m-d H:i:s', strtotime(str_replace('/', '-',$postArr['start_date_time']))) : date('Y-m-d H:i:s');
            $updatecareersFields['dtEndDateTime'] = !empty($postArr['end_date_time']) ? date('Y-m-d H:i:s', strtotime(str_replace('/', '-',$postArr['end_date_time']))) : null;
            $updatecareersFields['intJobCategory'] = NULL;

            $updatecareersFields['UserID'] = auth()->user()->id;
            $updatecareersFields['chrIsPreview'] = 'Y';
            $whereConditions = ['id' => $id];
            if ($postArr['oldAlias'] != $postArr['alias']) {
                Alias::updateAlias($postArr['oldAlias'], $postArr['alias']);
            }
            $update = CommonModel::updateRecords($whereConditions, $updatecareersFields, false, 'Powerpanel\Careers\Models\Careers');
        } else {
            $careersArr['dtDateTime'] = !empty($postArr['start_date_time']) ? date('Y-m-d H:i:s', strtotime(str_replace('/', '-',$postArr['start_date_time']))) : date('Y-m-d H:i:s');
            $careersArr['dtEndDateTime'] = !empty($postArr['end_date_time']) ? date('Y-m-d H:i:s', strtotime(str_replace('/', '-',$postArr['end_date_time']))) : null;
            $careersArr['chrIsPreview'] = 'Y';
            $id = $this->insertNewRecord($postArr, $careersArr, 'Y')->id;
        }
        return json_encode(array('status' => $id, 'alias' => $postArr['alias'], 'message' => trans('careers::template.pageModule.pageUpdate')));
    }

    public function insertApprovedRecord($updateModuleFields, $postArr, $id)
    {
        $whereConditions = ['id' => $postArr['fkMainRecord']];
        $updateModuleFields['chrAddStar'] = 'N';
        $updateModuleFields['chrPublish'] = trim($postArr['chrMenuDisplay']);
        $updateModuleFields['UserID'] = auth()->user()->id;
        $update = CommonModel::updateRecords($whereConditions, $updateModuleFields, false, 'Powerpanel\Careers\Models\Careers');
        if ($update) {
            //self::swap_order_edit($postArr['order'], $postArr['fkMainRecord']);
        }
        $whereConditions_ApproveN = ['fkMainRecord' => $postArr['fkMainRecord']];
        $updateToApproveN = [
            'chrApproved' => 'N',
            'chrLetest' => 'N',
            'intApprovedBy' => '0',
        ];
        CommonModel::updateRecords($whereConditions_ApproveN, $updateToApproveN, false, 'Powerpanel\Careers\Models\Careers');
        $whereConditionsApprove = ['id' => $id, 'chrMain' => 'N'];
        $updateToApprove = [
            'chrApproved' => 'Y',
            'chrRollBack' => 'Y',
            'intApprovedBy' => auth()->user()->id,
        ];
        CommonModel::updateRecords($whereConditionsApprove, $updateToApprove, false, 'Powerpanel\Careers\Models\Careers');
        if ($postArr['chrMenuDisplay'] == 'D') {
            $addlog = Config::get('Constant.DRAFT_RECORD_APPROVED');
        } else {
            $addlog = Config::get('Constant.RECORD_APPROVED');
        }
        $newCmsPageObj = Careers::getRecordForLogById($id);
        $logArr = MyLibrary::logData($id, false, $addlog);
        $logArr['varTitle'] = stripslashes($newCmsPageObj->varTitle);
        Log::recordLog($logArr);
        /* notification for user to record approved */
        $careers = Careers::getRecordForLogById($id);
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
                $actionMessage = trans('careers::template.careersModule.updateMessage');
            }
        }
    }

    public function insertApprovalRecord($moduleObj, $postArr, $careersArr)
    {
        $careersArr['chrMain'] = 'N';
        $careersArr['chrLetest'] = 'Y';
        $careersArr['fkMainRecord'] = $moduleObj->id;
        $careersArr['varTitle'] = stripslashes(trim($postArr['title']));
         $careersArr['varSector'] = $postArr['sector'];
        $careersArr['intAliasId'] = MyLibrary::insertAlias($postArr['alias'], false, 'N');
        if (Config::get('Constant.DEFAULT_VISUAL') == 'Y') {
            if ($postArr['section'] != '[]') {
                $vsection = $postArr['section'];
            } else {
                $vsection = '';
            }
        } else {
            $vsection = $postArr['description'];
        }
        $careersArr['txtDescription'] = $vsection;
        if ($postArr['chrMenuDisplay'] == 'D') {
            $careersArr['chrDraft'] = 'D';
            $careersArr['chrPublish'] = 'N';
        } else {
            $careersArr['chrDraft'] = 'N';
            $careersArr['chrPublish'] = $postArr['chrMenuDisplay'];
        }
        if (isset($postArr['chrPageActive']) && $postArr['chrPageActive'] != '') {
            $careersArr['chrPageActive'] = $postArr['chrPageActive'];
        }
        if (isset($postArr['chrPageActive']) && $postArr['chrPageActive'] == 'PP') {
            $careersArr['varPassword'] = $postArr['new_password'];
        } else {
            $careersArr['varPassword'] = '';
        }
        $careersArr['varMetaTitle'] = stripslashes(trim($postArr['varMetaTitle']));
        if (Config::get('Constant.CHRSearchRank') == 'Y') {
            $careersArr['intSearchRank'] = $postArr['search_rank'];
        }
        $careersArr['varTags'] = trim($postArr['tags']);
        $careersArr['varMetaDescription'] = stripslashes(trim($postArr['varMetaDescription']));
        $careersArr['varShortDescription'] = trim($postArr['short_description']);
        $careersArr['chrPublish'] = $postArr['chrMenuDisplay'];
        //$careersArr['intDisplayOrder'] = $postArr['order'];
        $careersArr['created_at'] = Carbon::now();
        $careersArr['UserID'] = auth()->user()->id;
        if ($postArr['chrMenuDisplay'] == 'D') {
            $addlog = Config::get('Constant.DRAFT_SENT_FOR_APPROVAL');
        } else {
            $addlog = Config::get('Constant.SENT_FOR_APPROVAL');
        }
        $careersID = CommonModel::addRecord($careersArr, 'Powerpanel\Careers\Models\Careers');
        if (!empty($careersID)) {
            $id = $careersID;
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
            $newcareersObj = Careers::getRecordForLogById($id);
            $logArr = MyLibrary::logData($id, false, $addlog);
            $logArr['varTitle'] = stripslashes($newcareersObj->varTitle);
            Log::recordLog($logArr);
            if (Auth::user()->can('recent-updates-list')) {
                $notificationArr = MyLibrary::notificationData($id, $newcareersObj);
                RecentUpdates::setNotification($notificationArr);
            }
            self::flushCache();
            $actionMessage = trans('careers::template.careersModule.addMessage');
        }
        $whereConditionsAddstar = ['id' => $moduleObj->id];
        $updateAddStar = [
            'chrAddStar' => 'Y',
        ];
        CommonModel::updateRecords($whereConditionsAddstar, $updateAddStar, false, 'Powerpanel\Careers\Models\Careers');
    }

    public function insertNewRecord($postArr, $careersArr, $preview = 'N')
    {
        $response = false;
        $careersArr['chrMain'] = 'Y';
        $careersArr['varTitle'] = stripslashes(trim($postArr['title']));
        $careersArr['varSector'] = stripslashes(trim($postArr['sector']));
        $careersArr['intJobCategory'] = NULL;
        $careersArr['intSalary'] = stripslashes(trim($postArr['salary']));
        $careersArr['txtPosition'] = stripslashes(trim($postArr['position']));
        $careersArr['txtExperience'] = stripslashes(trim($postArr['experience']));
        $careersArr['varTags'] = trim($postArr['tags']);
        // $careersArr['employmentType'] = $postArr['employmentType'];
        if (stripslashes(trim($postArr['employmentType'] == 'F'))) {
            $careersArr['employmentType'] = 'F';
        } else {
            $careersArr['employmentType'] = 'P';
        }
        $careersArr['varRequirements'] = stripslashes(trim($postArr['requirements']));
        $careersArr['intAliasId'] = MyLibrary::insertAlias($postArr['alias'], false, $preview);
        if (Config::get('Constant.DEFAULT_VISUAL') == 'Y') {
            if ($postArr['section'] != '[]') {
                $vsection = $postArr['section'];
            } else {
                $vsection = '';
            }
        } else {
            $vsection = $postArr['description'];
        }
        $careersArr['txtDescription'] = $vsection;
        $careersArr['varMetaTitle'] = stripslashes(trim($postArr['varMetaTitle']));
        if (Config::get('Constant.CHRSearchRank') == 'Y') {
            $careersArr['intSearchRank'] = $postArr['search_rank'];
        }
        if ($postArr['chrMenuDisplay'] == 'D') {
            $careersArr['chrDraft'] = 'D';
            $careersArr['chrPublish'] = 'N';
        } else {
            $careersArr['chrDraft'] = 'N';
        }
        if (isset($postArr['chrPageActive']) && $postArr['chrPageActive'] != '') {
            $careersArr['chrPageActive'] = $postArr['chrPageActive'];
        }
        if (isset($postArr['chrPageActive']) && $postArr['chrPageActive'] == 'PP') {
            $careersArr['varPassword'] = $postArr['new_password'];
        } else {
            $careersArr['varPassword'] = '';
        }
        if ($postArr['chrMenuDisplay'] == 'D') {
            $addlog = Config::get('Constant.ADDED_DRAFT');
        } else {
            $addlog = '';
        }
        $careersArr['varMetaDescription'] = stripslashes(trim($postArr['varMetaDescription']));
        $careersArr['varShortDescription'] = trim($postArr['short_description']);
       
        //$careersArr['intDisplayOrder'] = ($preview == "Y") ? '0' : self::swap_order_add($postArr['order']);
        $careersArr['UserID'] = auth()->user()->id;
        $careersArr['created_at'] = Carbon::now();
        $careersID = CommonModel::addRecord($careersArr, 'Powerpanel\Careers\Models\Careers');
        if (!empty($careersID)) {
            $id = $careersID;
            $newcareersObj = Careers::getRecordForLogById($id);
            $logArr = MyLibrary::logData($id, false, $addlog);
            $logArr['varTitle'] = $newcareersObj->varTitle;
            Log::recordLog($logArr);
            if (Auth::user()->can('recent-updates-list')) {
                $notificationArr = MyLibrary::notificationData($id, $newcareersObj);
                RecentUpdates::setNotification($notificationArr);
            }
            $response = $newcareersObj;
            self::flushCache();
            $actionMessage = trans('careers::template.careersModule.addMessage');
        }
        return $response;
    }

    /**
     * This method destroys careers in multiples
     * @return  careers index view
     * @since   2016-10-25
     * @author  NetQuick
     */
    public function DeleteRecord(Request $request)
    {
        $value = Request::input('value');
        $data['ids'] = Request::input('ids');
        $moduleHaveFields = ['chrMain', 'chrIsPreview'];
        $update = MyLibrary::deleteMultipleRecords($data, $moduleHaveFields, $value, 'Powerpanel\Careers\Models\Careers');
        if (File::exists(app_path() . '/Comments.php') != null || File::exists(base_path() . '/packages/Powerpanel/Workflow/src/Models/Comments.php') != null) {
            Comments::deleteComments($data['ids'], Config::get('Constant.MODULE.MODEL_NAME'));
        }
        foreach ($update as $ids) {
            $ignoreDeleteScope = true;
            $Deleted_Record = Careers::getRecordById($ids, $ignoreDeleteScope);
            $Cnt_Letest = Careers::getRecordCount_letest($Deleted_Record['fkMainRecord'], $Deleted_Record['id']);
            if ($Cnt_Letest <= 0) {
                $updateLetest = [
                    'chrAddStar' => 'N',
                ];
                $whereConditionsApprove = ['id' => $Deleted_Record['fkMainRecord']];
                CommonModel::updateRecords($whereConditionsApprove, $updateLetest, false, 'Powerpanel\Careers\Models\Careers');
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
                if ($value != 'P' && $value != 'F') {
                    Alias::where('id', $Deleted_Record['intAliasId'])
                        ->where('intFkModuleCode', Config::get('Constant.MODULE.ID'))
                        ->delete();
                }
            }
        }
        //Careers::ReorderAllrecords();
        self::flushCache();
        echo json_encode($update);
        exit;
    }

    /**
     * This method destroys careers in multiples
     * @return  careers index view
     * @since   2016-10-25
     * @author  NetQuick
     */
    public function publish(Request $request)
    {
        $requestArr = Request::all();
        //        $request = (object) $requestArr;
        $val = Request::get('val');
        $alias = Request::input('alias');
        $update = MyLibrary::setPublishUnpublish($alias, $val, 'Powerpanel\Careers\Models\Careers');
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
        MyLibrary::swapOrder($order, $exOrder, 'Powerpanel\Careers\Models\Careers');
        Careers::ReorderAllrecords();
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
        $moduleHaveFields = ['chrMain', 'chrIsPreview'];
        if ($order != null) {
        		Careers::ReorderAllrecords();
            $response = MyLibrary::swapOrderAdd($order, $isCustomizeModule, $moduleHaveFields, 'Powerpanel\Careers\Models\Careers');
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
        $moduleHaveFields = ['chrMain', 'chrIsPreview'];
        MyLibrary::swapOrderEdit($order, $id, $isCustomizeModule, $moduleHaveFields, 'Powerpanel\Careers\Models\Careers');
        Careers::ReorderAllrecords();
        self::flushCache();
    }




    public function tableData($value, $permit, $currentUserID, $allRecordsCount)
    {
        // Checkbox
        $checkbox = view('powerpanel.partials.checkbox', ['name'=>'delete', 'value'=>$value->id])->render();

        // Title
        $title = $value->varTitle;


        // Date
        // $startDate = date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($value->dtDateTime));
        $startDate = '<span align="left" data-bs-toggle="tooltip" data-bs-placement="bottom" title="'.date(Config::get("Constant.DEFAULT_DATE_FORMAT").' '.Config::get("Constant.DEFAULT_TIME_FORMAT"), strtotime($value->dtDateTime)).'">'.date(Config::get('Constant.DEFAULT_DATE_FORMAT'), strtotime($value->dtDateTime)).'</span>';
        //$endDate = !empty($value->dtEndDateTime) ? date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($value->dtEndDateTime)) : 'No Expiry';
        $endDate = !empty($value->dtEndDateTime) ? '<span align="left" data-bs-toggle="tooltip" data-bs-placement="bottom" title="'.date(Config::get("Constant.DEFAULT_DATE_FORMAT").' '.Config::get("Constant.DEFAULT_TIME_FORMAT"), strtotime($value->dtEndDateTime)).'">'.date(Config::get('Constant.DEFAULT_DATE_FORMAT'), strtotime($value->dtEndDateTime)).'</span>' : 'No Expiry';


        // Order Arrow
        /*$orderArrow = '';
        $dispOrder = $value->intDisplayOrder;
        if (($value->intDisplayOrder == $allRecordsCount || $value->intDisplayOrder < $allRecordsCount) && $value->intDisplayOrder > 1) {
            $orderArrow .= '<a href="javascript:;" data-order="' . $value->intDisplayOrder . '" class="moveUp"><i class="ri-arrow-up-line" aria-hidden="true"></i></a>';
        }
        $orderArrow .= $dispOrder;
        if (($value->intDisplayOrder != $allRecordsCount || $value->intDisplayOrder < $allRecordsCount)) {
            $orderArrow .= ' <a href="javascript:;" data-order="' . $value->intDisplayOrder . '" class="moveDwn"><i class="ri-arrow-down-line" aria-hidden="true"></i></a>';
        }*/


        // Publish Action
        $publish_action = '';
        if ($value->chrAddStar != 'Y') {
            if ($value->chrDraft != 'D') {
                if ($permit['cancareerspublish']) {
                    if ($value->chrPublish == 'Y') {
                        $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/careers', 'data_alias'=>$value->id, 'title'=>trans("careers::template.common.publishedRecord"), 'data_value'=>'Unpublish', 'checked'=>'checked'])->render();
                    } else {
                        $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/careers', 'data_alias'=>$value->id, 'title'=>trans("careers::template.common.unpublishedRecord"), 'data_value'=>'Publish'])->render();
                    }
                } else {
                    $publish_action = "-";
                }
            } else {
                if ($permit['cancareerspublish']) {
                    if ($value->chrPublish == 'Y') {
                        $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/careers', 'data_alias'=>$value->id, 'title'=>trans("careers::template.common.publishedRecord"), 'data_value'=>'Unpublish', 'checked'=>'checked'])->render();
                    } else {
                        $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/careers', 'data_alias'=>$value->id, 'title'=>trans("careers::template.common.unpublishedRecord"), 'data_value'=>'Publish'])->render();
                    }
                } else {
                    $publish_action = "-";
                }
            }
        } else {
            if ($permit['cancareerspublish']) {
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



        // Title Action
        $title_action = '';
        if ($permit['cancareersedit']) {
            $Quickedit_startDate = date('Y-m-d H:i', strtotime($value->dtDateTime));
            $Quickedit_endDate = !empty($value->dtEndDateTime) ? date('Y-m-d H:i', strtotime($value->dtEndDateTime)) : 'No Expiry';

            if ($value->chrLock != 'Y') {
                if (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y') {
                    if ($permit['cancareersreviewchanges']) {
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
            if ($permit['cancareersedit']) {
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
                        'canedit'=> $permit['cancareersedit'],
                        'candelete'=>$permit['cancareersdelete'],
                        'canloglist'=>$permit['canloglist'],
                        'value'=>$value,
                        'chrIsAdmin' => $this->currentUserRoleData->chrIsAdmin,
                        'module_name'=>'careers',
                        'module_edit_url' => route('powerpanel.careers.edit', array('alias' => $value->id)),
                        'module_type'=>'parent',
                        'viewlink' => isset($viewlink) ? $viewlink : "",
                        'linkviewLable' => isset($linkviewLable) ? $linkviewLable : "",
                        'logurl' => $logurl
                    ])->render();

                    if($permit['cancareersedit'] || $permit['cancareersdelete']){
                        $allActions = $allActions;
                    } else {
                        $allActions = "-";
                    }



        $records = array(
            $checkbox,
            '<div class="pages_title_div_row">' . $Favorite . ' <span class="title-txt"> ' . $title . ' ' . $status . $statusdata . ' - ' . $sector . ' ' .$title_action. '</span></div>',
            $startDate,
            $endDate,
            /*$orderArrow,*/
            $publish_action,
            $allActions
        );
        return $records;
    }

    public function tableData_tab1($value, $permit, $currentUserID)
    {
        // Checkbox
        $checkbox = view('powerpanel.partials.checkbox', ['name'=>'delete', 'value'=>$value->id])->render();

        // Title
        $title = $value->varTitle;


        // Date
        // $startDate = date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($value->dtDateTime));
        $startDate = '<span align="left" data-bs-toggle="tooltip" data-bs-placement="bottom" title="'.date(Config::get("Constant.DEFAULT_DATE_FORMAT").' '.Config::get("Constant.DEFAULT_TIME_FORMAT"), strtotime($value->dtDateTime)).'">'.date(Config::get('Constant.DEFAULT_DATE_FORMAT'), strtotime($value->dtDateTime)).'</span>';
        // $endDate = !empty($value->dtEndDateTime) ? date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($value->dtEndDateTime)) : 'No Expiry';
        $endDate = !empty($value->dtEndDateTime) ? '<span align="left" data-bs-toggle="tooltip" data-bs-placement="bottom" title="'.date(Config::get("Constant.DEFAULT_DATE_FORMAT").' '.Config::get("Constant.DEFAULT_TIME_FORMAT"), strtotime($value->dtEndDateTime)).'">'.date(Config::get('Constant.DEFAULT_DATE_FORMAT'), strtotime($value->dtEndDateTime)).'</span>' : 'No Expiry';


        // Publish Action
        $publish_action = '';
        if ($value->chrAddStar != 'Y') {
            if ($value->chrDraft != 'D') {
                if ($permit['cancareerspublish']) {
                    if ($value->chrPublish == 'Y') {
                        $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/careers', 'data_alias'=>$value->id, 'title'=>trans("careers::template.common.publishedRecord"), 'data_value'=>'Unpublish', 'checked'=>'checked'])->render();
                    } else {
                        $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/careers', 'data_alias'=>$value->id, 'title'=>trans("careers::template.common.unpublishedRecord"), 'data_value'=>'Publish'])->render();
                    }
                } else {
                    $publish_action = "-";
                }
            } else {
                if ($permit['cancareerspublish']) {
                    if ($value->chrPublish == 'Y') {
                        $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/careers', 'data_alias'=>$value->id, 'title'=>trans("careers::template.common.publishedRecord"), 'data_value'=>'Unpublish', 'checked'=>'checked'])->render();
                    } else {
                        $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/careers', 'data_alias'=>$value->id, 'title'=>trans("careers::template.common.unpublishedRecord"), 'data_value'=>'Publish'])->render();
                    }
                } else {
                    $publish_action = "-";
                }
            }
        } else {
            if ($permit['cancareerspublish']) {
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



        // Title Action
        $title_action = '';
        if ($permit['cancareersedit']) {
            $Quickedit_startDate = date('Y-m-d H:i', strtotime($value->dtDateTime));
            $Quickedit_endDate = !empty($value->dtEndDateTime) ? date('Y-m-d H:i', strtotime($value->dtEndDateTime)) : 'No Expiry';

            if ($value->chrLock != 'Y') {
                if (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y') {
                    if ($permit['cancareersreviewchanges']) {
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
            if ($permit['cancareersedit']) {
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
                        'canedit'=> $permit['cancareersedit'],
                        'candelete'=>$permit['cancareersdelete'],
                        'canloglist'=>$permit['canloglist'],
                        'value'=>$value,
                        'chrIsAdmin' => $this->currentUserRoleData->chrIsAdmin,
                        'module_name'=>'careers',
                        'module_edit_url' => route('powerpanel.careers.edit', array('alias' => $value->id)),
                        'module_type'=>'parent',
                        'viewlink' => isset($viewlink) ? $viewlink : "",
                        'linkviewLable' => isset($linkviewLable) ? $linkviewLable : "",
                        'logurl' => $logurl
                    ])->render();

                    if($permit['cancareersedit'] || $permit['cancareersdelete']){
                        $allActions = $allActions;
                    } else {
                        $allActions = "-";
                    }


        $records = array(
            $checkbox,
            '<div class="pages_title_div_row">' . $Favorite . ' <span class="title-txt"> ' . $title . ' ' . $status . $statusdata . ' - ' . $sector . ' ' .$title_action. '</span></div>',
            $startDate,
            $endDate,
            $publish_action,
            $allActions
        );
        return $records;
    }

    public function tableDataFavorite($value, $permit, $currentUserID)
    {
        // Checkbox
        $checkbox = view('powerpanel.partials.checkbox', ['name'=>'delete', 'value'=>$value->id])->render();

        // Title
        $title = $value->varTitle;


        // Date
        // $startDate = date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($value->dtDateTime));
        $startDate = '<span align="left" data-bs-toggle="tooltip" data-bs-placement="bottom" title="'.date(Config::get("Constant.DEFAULT_DATE_FORMAT").' '.Config::get("Constant.DEFAULT_TIME_FORMAT"), strtotime($value->dtDateTime)).'">'.date(Config::get('Constant.DEFAULT_DATE_FORMAT'), strtotime($value->dtDateTime)).'</span>';
        // $endDate = !empty($value->dtEndDateTime) ? date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($value->dtEndDateTime)) : 'No Expiry';
        $endDate = !empty($value->dtEndDateTime) ? '<span align="left" data-bs-toggle="tooltip" data-bs-placement="bottom" title="'.date(Config::get("Constant.DEFAULT_DATE_FORMAT").' '.Config::get("Constant.DEFAULT_TIME_FORMAT"), strtotime($value->dtEndDateTime)).'">'.date(Config::get('Constant.DEFAULT_DATE_FORMAT'), strtotime($value->dtEndDateTime)).'</span>' : 'No Expiry';


        // Publish Action
        $publish_action = '';
        if ($value->chrAddStar != 'Y') {
            if ($value->chrDraft != 'D') {
                if ($permit['cancareerspublish']) {
                    if ($value->chrPublish == 'Y') {
                        $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/careers', 'data_alias'=>$value->id, 'title'=>trans("careers::template.common.publishedRecord"), 'data_value'=>'Unpublish', 'checked'=>'checked'])->render();
                    } else {
                        $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/careers', 'data_alias'=>$value->id, 'title'=>trans("careers::template.common.unpublishedRecord"), 'data_value'=>'Publish'])->render();
                    }
                } else {
                    $publish_action = "-";
                }
            } else {
                if ($permit['cancareerspublish']) {
                    if ($value->chrPublish == 'Y') {
                        $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/careers', 'data_alias'=>$value->id, 'title'=>trans("careers::template.common.publishedRecord"), 'data_value'=>'Unpublish', 'checked'=>'checked'])->render();
                    } else {
                        $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/careers', 'data_alias'=>$value->id, 'title'=>trans("careers::template.common.unpublishedRecord"), 'data_value'=>'Publish'])->render();
                    }
                } else {
                    $publish_action = "-";
                }
            }
        } else {
            if ($permit['cancareerspublish']) {
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



        // Title Action
        $title_action = '';
        if ($permit['cancareersedit']) {
            $Quickedit_startDate = date('Y-m-d H:i', strtotime($value->dtDateTime));
            $Quickedit_endDate = !empty($value->dtEndDateTime) ? date('Y-m-d H:i', strtotime($value->dtEndDateTime)) : 'No Expiry';

            if ($value->chrLock != 'Y') {
                if (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y') {
                    if ($permit['cancareersreviewchanges']) {
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
            if ($permit['cancareersedit']) {
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
                        'canedit'=> $permit['cancareersedit'],
                        'candelete'=>$permit['cancareersdelete'],
                        'canloglist'=>$permit['canloglist'],
                        'value'=>$value,
                        'chrIsAdmin' => $this->currentUserRoleData->chrIsAdmin,
                        'module_name'=>'careers',
                        'module_edit_url' => route('powerpanel.careers.edit', array('alias' => $value->id)),
                        'module_type'=>'parent',
                        'viewlink' => isset($viewlink) ? $viewlink : "",
                        'linkviewLable' => isset($linkviewLable) ? $linkviewLable : "",
                        'logurl' => $logurl
                    ])->render();

                    if($permit['cancareersedit'] || $permit['cancareersdelete']){
                        $allActions = $allActions;
                    } else {
                        $allActions = "-";
                    }



        $records = array(
            $checkbox,
            '<div class="pages_title_div_row">' . $Favorite . ' <span class="title-txt"> ' . $title . ' ' . $status . $statusdata . ' - ' . $sector . ' ' .$title_action. '</span></div>',
            $startDate,
            $endDate,
            "-",
            $publish_action,
            $allActions
        );
        return $records;
    }

    public function tableDataDraft($value, $permit, $currentUserID)
    {
        // Checkbox
        $checkbox = view('powerpanel.partials.checkbox', ['name'=>'delete', 'value'=>$value->id])->render();

        // Title
        $title = $value->varTitle;


        // Date
        // $startDate = date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($value->dtDateTime));
        $startDate = '<span align="left" data-bs-toggle="tooltip" data-bs-placement="bottom" title="'.date(Config::get("Constant.DEFAULT_DATE_FORMAT").' '.Config::get("Constant.DEFAULT_TIME_FORMAT"), strtotime($value->dtDateTime)).'">'.date(Config::get('Constant.DEFAULT_DATE_FORMAT'), strtotime($value->dtDateTime)).'</span>';
        // $endDate = !empty($value->dtEndDateTime) ? date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($value->dtEndDateTime)) : 'No Expiry';
        $endDate = !empty($value->dtEndDateTime) ? '<span align="left" data-bs-toggle="tooltip" data-bs-placement="bottom" title="'.date(Config::get("Constant.DEFAULT_DATE_FORMAT").' '.Config::get("Constant.DEFAULT_TIME_FORMAT"), strtotime($value->dtEndDateTime)).'">'.date(Config::get('Constant.DEFAULT_DATE_FORMAT'), strtotime($value->dtEndDateTime)).'</span>' : 'No Expiry';


        // Publish Action
        $publish_action = '';
        if ($value->chrAddStar != 'Y') {
            if ($value->chrDraft != 'D') {
                if ($permit['cancareerspublish']) {
                    if ($value->chrPublish == 'Y') {
                        $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/careers', 'data_alias'=>$value->id, 'title'=>trans("careers::template.common.publishedRecord"), 'data_value'=>'Unpublish', 'checked'=>'checked'])->render();
                    } else {
                        $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/careers', 'data_alias'=>$value->id, 'title'=>trans("careers::template.common.unpublishedRecord"), 'data_value'=>'Publish'])->render();
                    }
                } else {
                    $publish_action = "-";
                }
            } else {
                if ($permit['cancareerspublish']) {
                    if ($value->chrPublish == 'Y') {
                        $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/careers', 'data_alias'=>$value->id, 'title'=>trans("careers::template.common.publishedRecord"), 'data_value'=>'Unpublish', 'checked'=>'checked'])->render();
                    } else {
                        $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/careers', 'data_alias'=>$value->id, 'title'=>trans("careers::template.common.unpublishedRecord"), 'data_value'=>'Publish'])->render();
                    }
                } else {
                    $publish_action = "-";
                }
            }
        } else {
            if ($permit['cancareerspublish']) {
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



        // Title Action
        $title_action = '';
        if ($permit['cancareersedit']) {
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
        $viewlink = "";
        $linkviewLable = "";
        if(isset($value->alias) && $value->alias != null){
            if ($permit['cancareersedit']) {
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
                        'tabName'=>'Draft',
                        'canedit'=> $permit['cancareersedit'],
                        'candelete'=>$permit['cancareersdelete'],
                        'canloglist'=>$permit['canloglist'],
                        'value'=>$value,
                        'chrIsAdmin' => $this->currentUserRoleData->chrIsAdmin,
                        'module_name'=>'careers',
                        'module_edit_url' => route('powerpanel.careers.edit', array('alias' => $value->id)),
                        'module_type'=>'parent',
                        'viewlink' => isset($viewlink) ? $viewlink : "",
                        'linkviewLable' => isset($linkviewLable) ? $linkviewLable : "",
                        'logurl' => $logurl
                    ])->render();

                    if($permit['cancareersedit'] || $permit['cancareersdelete']){
                        $allActions = $allActions;
                    } else {
                        $allActions = "-";
                    }



        $records = array(
            $checkbox,
            '<div class="pages_title_div_row"> <span class="title-txt"> ' . $title . ' ' . $status . $statusdata . ' - ' . $sector . ' ' .$title_action. '</span></div>',
            $startDate,
            $endDate,
            $publish_action,
            $allActions
        );
        return $records;
    }

    public function tableDataTrash($value, $permit, $currentUserID)
    {
        // Checkbox
        $checkbox = view('powerpanel.partials.checkbox', ['name'=>'delete', 'value'=>$value->id])->render();

        // Title
        $title = $value->varTitle;


        // Date
        // $startDate = date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($value->dtDateTime));
        $startDate = '<span align="left" data-bs-toggle="tooltip" data-bs-placement="bottom" title="'.date(Config::get("Constant.DEFAULT_DATE_FORMAT").' '.Config::get("Constant.DEFAULT_TIME_FORMAT"), strtotime($value->dtDateTime)).'">'.date(Config::get('Constant.DEFAULT_DATE_FORMAT'), strtotime($value->dtDateTime)).'</span>';
        // $endDate = !empty($value->dtEndDateTime) ? date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($value->dtEndDateTime)) : 'No Expiry';
        $endDate = !empty($value->dtEndDateTime) ? '<span align="left" data-bs-toggle="tooltip" data-bs-placement="bottom" title="'.date(Config::get("Constant.DEFAULT_DATE_FORMAT").' '.Config::get("Constant.DEFAULT_TIME_FORMAT"), strtotime($value->dtEndDateTime)).'">'.date(Config::get('Constant.DEFAULT_DATE_FORMAT'), strtotime($value->dtEndDateTime)).'</span>' : 'No Expiry';



        // Title Action
        $title_action = '';
        if ($permit['cancareersedit']) {
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
                        'canedit'=> $permit['cancareersedit'],
                        'candelete'=>$permit['cancareersdelete'],
                        'canloglist'=>$permit['canloglist'],
                        'value'=>$value,
                        'chrIsAdmin' => $this->currentUserRoleData->chrIsAdmin,
                        'module_name'=>'careers',
                        'module_edit_url' => route('powerpanel.careers.edit', array('alias' => $value->id)),
                        'module_type'=>'parent',
                        'viewlink' => isset($viewlink) ? $viewlink : "",
                        'linkviewLable' => isset($linkviewLable) ? $linkviewLable : "",
                        'logurl' => $logurl
                    ])->render();

                    if($permit['cancareersedit'] || $permit['cancareersdelete']){
                        $allActions = $allActions;
                    } else {
                        $allActions = "-";
                    }


        $records = array(
            $checkbox,
            '<div class="pages_title_div_row"> <span class="title-txt">' . $title . ' ' . $status . $statusdata . ' - ' . $sector . ' ' .$title_action. '</span></div>',
            $startDate,
            $endDate,
            $allActions
        );
        return $records;
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
        if (isset($data->fkIntDocId)) {
            $DocId = Document::getRecordById($data->fkIntDocId);
            $docname = stripslashes($DocId->txtDocumentName);
        } else {
            $DocId = '';
            $docname = '';
        }

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
        $returnHtml .= '<table class="new_table_desing table table-striped table-bordered table-hover">
				<thead>
					<tr>
					<th align="center">' . trans('careers::template.common.title') . '</th>
                                        <th align="center">Start date</th>
                                        <th align="center">End date</th>
                                        <th align="center">Documents</th>
                                        <th align="center">Short Description</th>
                                        <th align="center">' . trans('careers::template.common.content') . '</th>
                                        <th align="center">' . trans('careers::template.common.metatitle') . '</th>
                                        <th align="center">' . trans('careers::template.common.metadescription') . '</th>
                                        <th align="center">' . trans('careers::template.common.publish') . '</th>
					</tr>
				</thead>
				<tbody>
					<tr>
					<td align="center">' . stripslashes($data->varTitle) . '</td>
					<td align="center">' . $startDate . '</td>
					<td align="center">' . $endDate . '</td>
					<td align="center">' . $docname . '</td>
					<td align="center">' . stripslashes($data->varShortDescription) . '</td>
					<td align="center">' . $desc . '</td>
					<td align="center">' . stripslashes($data->varMetaTitle) . '</td>
                                        <td align="center">' . stripslashes($data->varMetaDescription) . '</td>
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
        if ($data->fkIntDocId != $newdata->fkIntDocId) {
            $DocIdcolor = 'style="background-color:#f5efb7"';
        } else {
            $DocIdcolor = '';
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
        $startDate = date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($newdata->dtDateTime));
        $endDate = !empty($newdata->dtEndDateTime) ? date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($newdata->dtEndDateTime)) : 'No Expiry';
        if (isset($newdata->fkIntDocId)) {
            $DocId = Document::getRecordById($newdata->fkIntDocId);
            $docname = stripslashes($DocId->txtDocumentName);
        } else {
            $DocId = '';
            $docname = '';
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

        $returnHtml = '';
        $returnHtml .= '<table class = "new_table_desing table table-striped table-bordered table-hover">
				<thead>
				<tr>
				<th align="center">' . trans('careers::template.common.title') . '</th>
                                <th align="center">Start date</th>
                                <th align="center">End date</th>
                                <th align="center">Documents</th>
                                <th align="center">Short Description</th>
                                <th align="center">' . trans('careers::template.common.content') . '</th>
                                <th align="center">' . trans('careers::template.common.metatitle') . '</th>
                                <th align="center">' . trans('careers::template.common.metadescription') . '</th>
                                <th align="center">' . trans('careers::template.common.publish') . '</th>
				</tr>
				</thead>
				<tbody>
				<tr>
                                <td align="center" ' . $titlecolor . '>' . stripslashes($newdata->varTitle) . '</td>
                                <td align="center" ' . $DateTimecolor . '>' . $startDate . '</td>
                                <td align="center" ' . $EndDateTimecolor . '>' . $endDate . '</td>
                                <td align="center" ' . $DocIdcolor . '>' . $docname . '</td>
                                <td align="center" ' . $ShortDescriptioncolor . '>' . stripslashes($newdata->varShortDescription) . '</td>
				<td align="center" ' . $desccolor . '>' . $desc . '</td>
				<td align="center" ' . $metatitlecolor . '>' . stripslashes($newdata->varMetaTitle) . '</td>
				<td align="center" ' . $metadesccolor . '>' . stripslashes($newdata->varMetaDescription) . '</td>
				<td align="center" ' . $Publishcolor . '>' . $newdata->chrPublish . '</td>
				</tr>
				</tbody>
				</table>';
        return $returnHtml;
    }

    public static function flushCache()
    {
        Cache::tags('Careers')->flush();
    }

    public function getChildData()
    {
        $childHtml = "";
        $Careers_childData = "";
        $Careers_childData = Careers::getChildGrid();


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


        if (count($Careers_childData) > 0) {
            foreach ($Careers_childData as $child_row) {
                $checkbox = view('powerpanel.partials.checkbox', ['name'=>'delete', 'value'=>$child_row->id])->render();
                $parentAlias = $child_row->alias->varAlias;
                $url = url('/previewpage?url=' . MyLibrary::getFrontUri('careers')['uri'] . '/' . $parentAlias . '/' . $child_row->id . '/preview');

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
                        $childHtml .= "<td class=\"text-center\"><span class='mob_show_title'>Edit: </span><a class='icon_round me-2' data-bs-toggle='tooltip' data-bs-placement='bottom' title='" . trans('careers::template.common.edit') . "' href='" . route('powerpanel.careers.edit', array('alias' => $child_row->id)) . "?tab=A'><i class='ri-pencil-line'></i></a></td>";
                    } else {
                        $childHtml .= '<td class="text-center"><span class="mob_show_title">Edit: </span>-</td>';
                    }

                    if ($child_row->chrApproved == 'N') {
                        $childHtml .= "<td class=\"text-center\"><span class='mob_show_title'>Status: </span><a class=\"approve_icon_btn me-2\" data-bs-toggle='tooltip' data-bs-placement='bottom' title='" . trans('careers::template.common.comments') . "' href=\"javascript:void(0);\" onclick=\"loadModelpopup('" . $child_row->id . "','" . $child_row->UserID . "','" . Config::get('Constant.MODULE.MODEL_NAME') . "','" . $child_row->fkMainRecord . "')\"><i class=\"ri-chat-1-line\"></i> </a><a class=\"approve_icon_btn me-2\" onclick=\"update_mainrecord('" . $child_row->id . "','" . $child_row->fkMainRecord . "','" . $child_row->UserID . "','A');\" data-bs-toggle='tooltip' data-bs-placement='bottom' title='" . trans('careers::template.common.clickapprove') . "'  href=\"javascript:void(0);\"><i class=\"ri-checkbox-line
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
        $Cmspage_rollbackchildData = Careers::getChildrollbackGrid();
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
                $previewlink = url('/previewpage?url=' . MyLibrary::getFrontUri('careers')['uri'] . '/' . $child_rollbacrow->id . '/preview/detail');
                $child_rollbackHtml .= "<tr role=\"row\">";
                $child_rollbackHtml .= '<td class="text-center"><span class="mob_show_title">Title: </span>' . $child_rollbacrow->varTitle . '</td>';
                $child_rollbackHtml .= "<td class=\"text-center\"><span class='mob_show_title'>Date: </span>" . date('M d Y h:i A', strtotime($child_rollbacrow->created_at)) . "</td>";
                $child_rollbackHtml .= "<td class=\"text-center\"><span class='mob_show_title'>User: </span>" . CommonModel::getUserName($child_rollbacrow->UserID) . "</td>";
                $child_rollbackHtml .= "<td class=\"text-center\"><span class='mob_show_title'>Preview: </span><a class='icon_round' href=" . $previewlink . " target='_blank'><i class=\"ri-computer-line\"></i></a></td>";
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

    public function ApprovedData_Listing(Request $request)
    {
        $requestArr = Request::all();
        $request = (object) $requestArr;
        $id = Request::post('id');
        $approvalid = Request::post('id');
        $main_id = Request::post('main_id');
        $flag = Request::post('flag');
        $approvalData = Careers::getOrderOfApproval($id);
        $message = Careers::approved_data_Listing($request);
        if (!empty($approvalData)) {
            //self::swap_order_edit($approvalData->intDisplayOrder, $main_id);
        }
        $newCmsPageObj = Careers::getRecordForLogById($main_id);
        $approval_obj = Careers::getRecordForLogById($approvalid);
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
        $careers = Careers::getRecordForLogById($id);
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

    public function rollBackRecord(Request $request)
    {

        $message = 'Oops! Something went wrong';
        $requestArr = Request::all();
        $request = (object) $requestArr;

        $previousRecord = Careers::getPreviousRecordByMainId($request->id);
        if (!empty($previousRecord)) {

            $main_id = $previousRecord->fkMainRecord;
            $request->id = $previousRecord->id;
            $request->main_id = $main_id;

            $message = Careers::approved_data_Listing($request);

            $newBlogObj = Careers::getRecordForLogById($main_id);
            $restoredata = Config::get('Constant.ROLLBACK_RECORD');

            /* notification for user to record approved */
            $blogs = Careers::getRecordForLogById($previousRecord->id);
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
