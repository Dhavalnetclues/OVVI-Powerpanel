<?php

namespace Powerpanel\NumberAllocation\Controllers\Powerpanel;

use App\Alias;
use App\CommonModel;
use App\Helpers\FrontPageContent_Shield;
use App\Helpers\MyLibrary;
use App\Helpers\resize_image;
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
use DB;
use File;
use Illuminate\Support\Facades\Redirect;
use Powerpanel\NumberAllocation\Models\CompanyCategory;
use Powerpanel\NumberAllocation\Models\NumberAllocation;
use Powerpanel\RoleManager\Models\Role_user;
use Powerpanel\Workflow\Models\Comments;
use Powerpanel\Workflow\Models\Workflow;
use Powerpanel\Workflow\Models\WorkflowLog;
use Request;
use Validator;

class NumberAllocationController extends PowerpanelController
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
     * This method handels load Blogs grid
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
        $total = NumberAllocation::getRecordCount(false, false, $userIsAdmin, $this->currentUserRoleSector);
        $NewRecordsCount = NumberAllocation::getNewRecordsCount($userIsAdmin, $this->currentUserRoleSector);
        $draftTotalRecords = NumberAllocation::getRecordCountforListDarft(false, true, $userIsAdmin, array(), $this->currentUserRoleSector);
        $trashTotalRecords = NumberAllocation::getRecordCountforListTrash(false, false, $userIsAdmin, [], $this->currentUserRoleSector);
        $favoriteTotalRecords = NumberAllocation::getRecordCountforListFavorite(false, false, $userIsAdmin, [], $this->currentUserRoleSector);
        $numberAllocationCategory = CompanyCategory::getCatWithParent();
        $this->breadcrumb['title'] = trans('number-allocation::template.numberAllocationModule.manage');
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
        // echo'<pre>'; print_r($settingarray);die;
        return view('number-allocation::powerpanel.index', ['userIsAdmin' => $userIsAdmin, 'iTotalRecords' => $total, 'breadcrumb' => $this->breadcrumb, 'numberAllocationCategory' => $numberAllocationCategory, 'NewRecordsCount' => $NewRecordsCount, 'draftTotalRecords' => $draftTotalRecords, 'trashTotalRecords' => $trashTotalRecords, 'favoriteTotalRecords' => $favoriteTotalRecords, 'settingarray' => $settingarray]);
    }

    /**
     * This method handels list of Number Allocation with filters
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
        $filterArr['statusFilter'] = !empty(Request::input('statusValue')) ? Request::input('statusValue') : '';
        $filterArr['sectorFilter'] = !empty(Request::input('sectorValue')) ? Request::input('sectorValue') : '';
        $filterArr['customFilterIdentity'] = !empty(Request::get('customFilterIdentity')) ? Request::get('customFilterIdentity') : '';
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
        $arrResults = NumberAllocation::getRecordList($filterArr, $isAdmin, $this->currentUserRoleSector);
        $iTotalRecords = NumberAllocation::getRecordCountforList($filterArr, true, $isAdmin, $this->currentUserRoleSector);
        $end = $filterArr['iDisplayStart'] + $filterArr['iDisplayLength'];
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;
        $tableSortedType = (isset($filterArr['orderTypeAscOrDesc']) && $filterArr['orderTypeAscOrDesc'] != "") ? $filterArr['orderTypeAscOrDesc'] : '';
        $totalRecords = NumberAllocation::getRecordCount(false, false, $isAdmin, $this->currentUserRoleSector);
        if (count($arrResults) > 0 && !empty($arrResults)) {
            foreach ($arrResults as $key => $value) {
                $records["data"][] = $this->tableData($value, $totalRecords, $tableSortedType);
            }
        }
        $NewRecordsCount = NumberAllocation::getNewRecordsCount($isAdmin, $this->currentUserRoleSector);
        $records["newRecordCount"] = $NewRecordsCount;
        $records["customActionStatus"] = "OK";
        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;
        echo json_encode($records);
        exit;
    }

    public function get_list_New()
    {
        /* Start code for sorting */
        $userIsAdmin = false;
        if (!empty($this->currentUserRoleData)) {
            if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                $userIsAdmin = true;
            }
        }

        $filterArr = [];
        $records = array();
        $records["data"] = array();
        $filterArr['orderColumnNo'] = (!empty(Request::input('order')[0]['column']) ? Request::input('order')[0]['column'] : '');
        // $filterArr['orderByFieldName'] = (!empty(Request::input('columns')[$filterArr['orderColumnNo']]['name']) ? Request::input('columns')[$filterArr['orderColumnNo']]['name'] : '');
        $filterArr['orderTypeAscOrDesc'] = (!empty(Request::input('order')[0]['dir']) ? Request::input('order')[0]['dir'] : '');
        $filterArr['customFilterIdentity'] = !empty(Request::get('customFilterIdentity')) ? Request::get('customFilterIdentity') : '';
        $filterArr['statusFilter'] = !empty(Request::input('statusValue')) ? Request::input('statusValue') : '';
        $filterArr['sectorFilter'] = !empty(Request::input('sectorValue')) ? Request::input('sectorValue') : '';
        $filterArr['catFilter'] = !empty(Request::input('catValue')) ? Request::input('catValue') : '';
        $filterArr['searchFilter'] = !empty(Request::input('searchValue')) ? Request::input('searchValue') : '';
        $filterArr['iDisplayLength'] = intval(Request::input('length'));
        $filterArr['iDisplayStart'] = intval(Request::input('start'));
        $sEcho = intval(Request::input('draw'));
        $arrResults = NumberAllocation::getRecordList_tab1($filterArr, $userIsAdmin, $this->currentUserRoleSector);
        $iTotalRecords = NumberAllocation::getRecordCountListApprovalTab($filterArr, false, $userIsAdmin, $this->currentUserRoleSector);
        $end = $filterArr['iDisplayStart'] + $filterArr['iDisplayLength'];
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;
        $totalRecords = NumberAllocation::getRecordCount(false, false, $userIsAdmin, $this->currentUserRoleSector);
        if (count($arrResults) > 0 && !empty($arrResults)) {
            foreach ($arrResults as $key => $value) {
                $records["data"][] = $this->tableData_tab1($value, $totalRecords);
            }
        }
        $NewRecordsCount = NumberAllocation::getNewRecordsCount($userIsAdmin, $this->currentUserRoleSector);
        $records["newRecordCount"] = $NewRecordsCount;
        $records["customActionStatus"] = "OK";
        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;
        echo json_encode($records);
        exit;
    }

    /**
     * This method handels list of Number Allocation with filters
     * @return  View
     * @since   2017-07-20
     * @author  NetQuick
     */
    public function get_list_favorite()
    {
        /* Start code for sorting */
        $filterArr = [];
        $records = array();
        $records["data"] = array();
        $filterArr['orderColumnNo'] = (!empty(Request::input('order')[0]['column']) ? Request::input('order')[0]['column'] : '');
        // $filterArr['orderByFieldName'] = (!empty(Request::input('columns')[$filterArr['orderColumnNo']]['name']) ? Request::input('columns')[$filterArr['orderColumnNo']]['name'] : '');
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
        if (!empty($this->currentUserRoleData)) {
            if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                $isAdmin = true;
            }
        }
        $arrResults = NumberAllocation::getRecordListFavorite($filterArr, $isAdmin, $this->currentUserRoleSector);
        $iTotalRecords = NumberAllocation::getRecordCountforListFavorite($filterArr, true, $isAdmin, [], $this->currentUserRoleSector);
        $end = $filterArr['iDisplayStart'] + $filterArr['iDisplayLength'];
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;
        $tableSortedType = (isset($filterArr['orderTypeAscOrDesc']) && $filterArr['orderTypeAscOrDesc'] != "") ? $filterArr['orderTypeAscOrDesc'] : '';
        $totalRecords = NumberAllocation::getRecordCount(false, false, $isAdmin, $this->currentUserRoleSector);
        if (count($arrResults) > 0 && !empty($arrResults)) {
            foreach ($arrResults as $key => $value) {
                $records["data"][] = $this->tableDataFavorite($value, $totalRecords, $tableSortedType);
            }
        }
        $NewRecordsCount = NumberAllocation::getNewRecordsCount($isAdmin, $this->currentUserRoleSector);
        $records["newRecordCount"] = $NewRecordsCount;
        $records["customActionStatus"] = "OK";
        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;
        echo json_encode($records);
        exit;
    }

    /**
     * This method handels list of Number Allocation with filters
     * @return  View
     * @since   2017-07-20
     * @author  NetQuick
     */
    public function get_list_draft()
    {
        /* Start code for sorting */
        $filterArr = [];
        $records = array();
        $records["data"] = array();
        $filterArr['orderColumnNo'] = (!empty(Request::input('order')[0]['column']) ? Request::input('order')[0]['column'] : '');
        // $filterArr['orderByFieldName'] = (!empty(Request::input('columns')[$filterArr['orderColumnNo']]['name']) ? Request::input('columns')[$filterArr['orderColumnNo']]['name'] : '');
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
        if (!empty($this->currentUserRoleData)) {
            if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                $isAdmin = true;
            }
        }
        $arrResults = NumberAllocation::getRecordListDraft($filterArr, $isAdmin, $this->currentUserRoleSector);
        $iTotalRecords = NumberAllocation::getRecordCountforListDarft($filterArr, true, $isAdmin, [], $this->currentUserRoleSector);
        $end = $filterArr['iDisplayStart'] + $filterArr['iDisplayLength'];
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;
        $tableSortedType = (isset($filterArr['orderTypeAscOrDesc']) && $filterArr['orderTypeAscOrDesc'] != "") ? $filterArr['orderTypeAscOrDesc'] : '';
        $totalRecords = NumberAllocation::getRecordCount(false, false, $isAdmin, $this->currentUserRoleSector);
        if (count($arrResults) > 0 && !empty($arrResults)) {
            foreach ($arrResults as $key => $value) {
                $records["data"][] = $this->tableDataDraft($value, $totalRecords, $tableSortedType);
            }
        }
        $NewRecordsCount = NumberAllocation::getNewRecordsCount($isAdmin, $this->currentUserRoleSector);
        $records["newRecordCount"] = $NewRecordsCount;
        $records["customActionStatus"] = "OK";
        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;
        echo json_encode($records);
        exit;
    }

    /**
     * This method handels list of Number Allocation with filters
     * @return  View
     * @since   2017-07-20
     * @author  NetQuick
     */
    public function get_list_trash()
    {
        /* Start code for sorting */
        $filterArr = [];
        $records = array();
        $records["data"] = array();
        $filterArr['orderColumnNo'] = (!empty(Request::input('order')[0]['column']) ? Request::input('order')[0]['column'] : '');
        // $filterArr['orderByFieldName'] = (!empty(Request::input('columns')[$filterArr['orderColumnNo']]['name']) ? Request::input('columns')[$filterArr['orderColumnNo']]['name'] : '');
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
        if (!empty($this->currentUserRoleData)) {
            if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                $isAdmin = true;
            }
        }
        $arrResults = NumberAllocation::getRecordListTrash($filterArr, $isAdmin);
        $iTotalRecords = NumberAllocation::getRecordCountforListTrash($filterArr, true, $isAdmin, [], $this->currentUserRoleSector);
        $end = $filterArr['iDisplayStart'] + $filterArr['iDisplayLength'];
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;
        $tableSortedType = (isset($filterArr['orderTypeAscOrDesc']) && $filterArr['orderTypeAscOrDesc'] != "") ? $filterArr['orderTypeAscOrDesc'] : '';
        $totalRecords = NumberAllocation::getRecordCount(false, false, $isAdmin, $this->currentUserRoleSector);
        if (count($arrResults) > 0 && !empty($arrResults)) {
            foreach ($arrResults as $key => $value) {
                $records["data"][] = $this->tableDataTrash($value, $totalRecords, $tableSortedType);
            }
        }
        $NewRecordsCount = NumberAllocation::getNewRecordsCount($isAdmin, $this->currentUserRoleSector);
        $records["newRecordCount"] = $NewRecordsCount;
        $records["customActionStatus"] = "OK";
        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;
        echo json_encode($records);
        exit;
    }

    /**
     * This method loads Number Allocation edit view
     * @param      Alias of record
     * @return  View
     * @since   2017-07-21
     * @author  NetQuick
     */
    public function edit($alias = false)
    {
        // $module = Modules::getModule('blog-category');
        $category = CompanyCategory::getCatWithParent();
        $templateData = array();
        $imageManager = true;
        $documentManager = true;
        $userIsAdmin = false;

        if (isset($this->currentUserRoleData) && !empty($this->currentUserRoleData)) {
            if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                $userIsAdmin = true;
            }
        }

        if (!is_numeric($alias)) {
            $total = NumberAllocation::getRecordCount(false, false, $userIsAdmin, $this->currentUserRoleSector);
            if (auth()->user()->can('number-allocation-create') || $userIsAdmin) {
                $total = $total + 1;
            }
            $this->breadcrumb['title'] = trans('number-allocation::template.numberAllocationModule.add');
            $this->breadcrumb['module'] = trans('number-allocation::template.numberAllocationModule.manage');
            $this->breadcrumb['url'] = 'powerpanel/number-allocation';
            $this->breadcrumb['inner_title'] = trans('number-allocation::template.numberAllocationModule.add');
            $templateData['total'] = $total;
            $templateData['breadcrumb'] = $this->breadcrumb;
            $templateData['companyCategory'] = $category;
            $templateData['imageManager'] = $imageManager;
            $templateData['documentManager'] = $documentManager;
        } else {
            $id = $alias;
            $numberAllocation = NumberAllocation::getRecordById($id);

            if (empty($numberAllocation)) {
                return redirect()->route('powerpanel.number-allocation.add');
            }
            if ($numberAllocation->fkMainRecord != '0') {
                $numberAllocation_highLight = NumberAllocation::getRecordById($numberAllocation->fkMainRecord);

                $templateData['numberAllocation_highLight'] = $numberAllocation_highLight;

            } else {
                $templateData['numberAllocation_highLight'] = "";
            }

            if (method_exists($this->MyLibrary, 'getModulePageAliasByModuleName')) {
                $categorypagereocrdlink = MyLibrary::getModulePageAliasByModuleName('number-allocation');
            }
            // if (method_exists($this->MyLibrary, 'getRecordAliasByModuleNameRecordId')) {
            //     $categoryRecordAlias = MyLibrary::getRecordAliasByModuleNameRecordId("blog-category", $numberAllocation->intFKCategory);
            // }
            if (!empty($categorypagereocrdlink)) {
                $varURL = $categorypagereocrdlink . '/' . $numberAllocation->id;
            } else {
                $varURL = $numberAllocation->id;
            }
            $metaInfo['varURL'] = $varURL;
            $this->breadcrumb['title'] = trans('number-allocation::template.numberAllocationModule.edit') . ' - ' . $numberAllocation->nxx;
            $this->breadcrumb['module'] = trans('number-allocation::template.numberAllocationModule.manage');
            $this->breadcrumb['url'] = 'powerpanel/number-allocation';
            $this->breadcrumb['inner_title'] = trans('number-allocation::template.numberAllocationModule.edit') . ' - ' . $numberAllocation->nxx;
            $templateData['numberAllocation'] = $numberAllocation;
            $templateData['id'] = $id;
            $templateData['breadcrumb'] = $this->breadcrumb;
            $templateData['companyCategory'] = $category;
            $templateData['metaInfo'] = $metaInfo;
            $templateData['imageManager'] = $imageManager;
            $templateData['documentManager'] = $documentManager;
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
        $templateData['userIsAdmin'] = $userIsAdmin;
        $templateData['MyLibrary'] = $this->MyLibrary;
        return view('number-allocation::powerpanel.actions', $templateData);
    }

    /**
     * This method stores NumberAllocation modifications
     * @return  View
     * @since   2017-07-21
     * @author  NetQuick
     */
    public function handlePost(Request $request)
    {
        $approval = false;
        $userIsAdmin = false;
        if (isset($this->currentUserRoleData) && !empty($this->currentUserRoleData)) {
            if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                $userIsAdmin = true;
            }
        }

        $postArr = Request::all();
        $messsages = [
            'nxx.required' => 'Title field is required.',
            'category_id.required' => 'Category field is required.',
            'service.required' => 'Alias field is required.',
            'sector.required' => 'Sector type is',
            'display_order.required' => trans('number-allocation::template.numberAllocationModule.displayOrder'),
            'display_order.greater_than_zero' => trans('number-allocation::template.numberAllocationModule.displayGreaterThan'),
        ];
        $rules = [
            'sector' => 'required',
            'nxx' => 'required|handle_xss|no_url',
            'category_id' => 'required',
            'service' => 'required',
            'display_order' => 'required|greater_than_zero|handle_xss|no_url',
        ];
        $validator = Validator::make($postArr, $rules, $messsages);
        if ($validator->passes()) {
            $numberAllocationArr = [];
            $module = Modules::getModuleById(Config::get('Constant.MODULE.ID'));
            if (isset($this->currentUserRoleData)) {
                $currentUserRoleData = $this->currentUserRoleData;
            }
            $id = Request::segment(3);
            if ($postArr['category_id'] == 'other') {
                $comapnyCategory = CompanyCategory::create([
                    'varTitle' => $postArr['companyCategory'],
                ]);
                $postArr['categoryId'] = $comapnyCategory->id;
            } else {
                $postArr['categoryId'] = $postArr['category_id'];
            }
            $actionMessage = trans('number-allocation::template.numberAllocationModule.updateMessage');
            if (is_numeric($id)) { #Edit post Handler=======
            $numberAllocation = NumberAllocation::getRecordForLogById($id);
                $updateNumberAllocationFields = [];
                $updateNumberAllocationFields['nxx'] = stripslashes(trim($postArr['nxx']));
                $updateNumberAllocationFields['intFKCategory'] = trim($postArr['categoryId']);
                $updateNumberAllocationFields['service'] = trim($postArr['service']);
                $updateNumberAllocationFields['note'] = trim($postArr['note']);
                $updateNumberAllocationFields['varSector'] = trim($postArr['sector']);
                $updateNumberAllocationFields['intDisplayOrder'] = trim($postArr['display_order']);

                if (Config::get('Constant.CHRSearchRank') == 'Y') {
                    if (isset($postArr['search_rank']) && !empty($postArr['search_rank'])) {
                        $updateNumberAllocationFields['intSearchRank'] = $postArr['search_rank'];
                    }
                }
                $updateNumberAllocationFields['UserID'] = auth()->user()->id;
                if ($postArr['chrMenuDisplay'] == 'D') {
                    $updateNumberAllocationFields['chrDraft'] = 'D';
                    $updateNumberAllocationFields['chrPublish'] = 'N';
                } else {
                    $updateNumberAllocationFields['chrDraft'] = 'N';
                    $updateNumberAllocationFields['chrPublish'] = $postArr['chrMenuDisplay'];
                }
                if (isset($postArr['chrPageActive']) && $postArr['chrPageActive'] != '') {
                    $updateNumberAllocationFields['chrPageActive'] = $postArr['chrPageActive'];
                }
                if (isset($postArr['chrPageActive']) && $postArr['chrPageActive'] == 'PP') {
                    $updateNumberAllocationFields['varPassword'] = $postArr['new_password'];
                } else {
                    $updateNumberAllocationFields['varPassword'] = '';
                }
                if ($postArr['chrMenuDisplay'] == 'D') {
                    $addlog = Config::get('Constant.UPDATE_DRAFT');
                } else {
                    $addlog = '';
                }

                $whereConditions = ['id' => $id];
                if ($numberAllocation->chrLock == 'Y' && auth()->user()->id != $numberAllocation->LockUserID) {
                    if ($this->currentUserRoleData->chrIsAdmin != 'Y') {
                        $lockedUserData = User::getRecordById($numberAllocation->LockUserID, true);
                        $lockedUserName = 'someone';
                        if (!empty($lockedUserData)) {
                            $lockedUserName = $lockedUserData->name;
                        }
                        $actionMessage = "This record has been locked by " . $lockedUserName . ".";
                        return redirect()->route('powerpanel.nuber-allocation.index')->with('message', $actionMessage);
                    }
                }
                if (File::exists(app_path() . '/Workflow.php') != null || File::exists(base_path() . '/packages/Powerpanel/Workflow/src/Models/Workflow.php') != null) {
                    if (!$userIsAdmin) {
                        $userRole = $currentUserRoleData->id;
                    } else {
                        $userRoleData = Role_user::getUserRoleByUserId($numberAllocation->UserID);
                        if (isset($userRoleData->role_id)) {
                            $userRole = $userRoleData->role_id;
                        } else {
                            $userRole = $this->currentUserRoleData->id;
                        }
                    }
                    if ($postArr['chrMenuDisplay'] == 'D') {
                        DB::table('menu')->where('intPageId', $id)->where('intfkModuleId', Config::get('Constant.MODULE.ID'))->delete();
                    }
                    $workFlowByCat = Workflow::getRecordByCategoryId($module->intFkGroupCode, $userRole, Config::get('Constant.MODULE.ID'));
                    if (empty($workFlowByCat->varUserId) || $userIsAdmin || $workFlowByCat->charNeedApproval == 'N') {
                        if ((int) $numberAllocation->fkMainRecord === 0 || empty($workFlowByCat->varUserId)) {
                            $update = CommonModel::updateRecords($whereConditions, $updateNumberAllocationFields, false, 'Powerpanel\NumberAllocation\Models\NumberAllocation');
                            if ($update) {
                                if ($id > 0 && !empty($id)) {
                                    self::swap_order_edit($postArr['display_order'], $id);
                                    $logArr = MyLibrary::logData($id);
                                    if (Auth::user()->can('log-advanced')) {
                                        $newBlogObj = NumberAllocation::getRecordForLogById($id);
                                        $oldRec = $this->recordHistory($numberAllocation);
                                        $newRec = $this->newrecordHistory($numberAllocation, $newBlogObj);
                                        $logArr['old_val'] = $oldRec;
                                        $logArr['new_val'] = $newRec;
                                    }
                                    $logArr['varTitle'] = trim($postArr['nxx']);
                                    Log::recordLog($logArr);
                                    if (Auth::user()->can('recent-updates-list')) {
                                        if (!isset($newBlogObj)) {
                                            $newBlogObj = NumberAllocation::getRecordForLogById($id);
                                        }
                                        $notificationArr = MyLibrary::notificationData($id, $newBlogObj);
                                        RecentUpdates::setNotification($notificationArr);
                                    }
                                    self::flushCache();
                                    if (isset($postArr['saveandexit']) && $postArr['saveandexit'] == 'approvesaveandexit') {
                                        $actionMessage = trans('number-allocation::template.common.recordApprovalMessage');
                                    } else {
                                        $actionMessage = trans('number-allocation::template.numberAllocationModule.updateMessage');
                                    }
                                }
                            }
                        } else {
                            $updateModuleFields = $updateNumberAllocationFields;
                            $this->insertApprovedRecord($updateModuleFields, $postArr, $id);
                            if (isset($postArr['saveandexit']) && $postArr['saveandexit'] == 'approvesaveandexit') {
                                $actionMessage = trans('number-allocation::template.common.recordApprovalMessage');
                            } else {
                                $actionMessage = trans('number-allocation::template.numberAllocationModule.updateMessage');
                            }
                            $approval = $id;
                        }
                    } else {
                        if ($workFlowByCat->charNeedApproval == 'Y') {
                            $approvalObj = $this->insertApprovalRecord($numberAllocation, $postArr, $updateNumberAllocationFields);
                            if (isset($postArr['saveandexit']) && $postArr['saveandexit'] == 'approvesaveandexit') {
                                $actionMessage = trans('number-allocation::template.common.recordApprovalMessage');
                            } else {
                                $actionMessage = trans('number-allocation::template.numberAllocationModule.updateMessage');
                            }
                            $approval = $approvalObj->id;
                        }
                    }
                } else {
                    $update = CommonModel::updateRecords($whereConditions, $updateNumberAllocationFields, false, 'Powerpanel\NumberAllocation\Models\NumberAllocation');
                    $actionMessage = trans('number-allocation::template.numberAllocationModule.updateMessage');
                }
            } else { #Add post Handler=======
            if (File::exists(app_path() . '/Workflow.php') != null || File::exists(base_path() . '/packages/Powerpanel/Workflow/src/Models/Workflow.php') != null) {
                $workFlowByCat = Workflow::getRecordByCategoryId($module->intFkGroupCode, $currentUserRoleData->id, Config::get('Constant.MODULE.ID'));
            }
                if (!empty($workFlowByCat->varUserId) && $workFlowByCat->chrNeedAddPermission == 'Y' && !$userIsAdmin) {
                    $numberAllocationArr['chrPublish'] = 'N';
                    $numberAllocationArr['chrDraft'] = 'N';
                    $numberAllocationObj = $this->insertNewRecord($postArr, $numberAllocationArr);
                    if ($postArr['chrMenuDisplay'] == 'D') {
                        $numberAllocationArr['chrDraft'] = 'D';
                    }
                    $numberAllocationArr['chrPublish'] = 'Y';
                    $approvalObj = $this->insertApprovalRecord($numberAllocationObj, $postArr, $numberAllocationArr);
                    $approval = $numberAllocationObj->id;
                } else {
                    $numberAllocationObj = $this->insertNewRecord($postArr, $numberAllocationArr);
                    $approval = $numberAllocationObj->id;
                }
                if (isset($postArr['saveandexit']) && $postArr['saveandexit'] == 'approvesaveandexit') {
                    $actionMessage = trans('number-allocation::template.common.recordApprovalMessage');
                } else {
                    $actionMessage = trans('number-allocation::template.numberAllocationModule.addMessage');
                }
                $actionMessage = trans('number-allocation::template.numberAllocationModule.addMessage');
                $id = $numberAllocationObj->id;
            }
            if ((!empty(Request::get('saveandexit')) && Request::get('saveandexit') == 'saveandexit') || !$userIsAdmin) {
                if ($postArr['chrMenuDisplay'] == 'D') {
                    return redirect()->route('powerpanel.number-allocation.index', 'tab=D')->with('message', $actionMessage);
                } else {
                    return redirect()->route('powerpanel.number-allocation.index')->with('message', $actionMessage);
                }
            } else {
                return redirect()->route('powerpanel.number-allocation.edit', $id)->with('message', $actionMessage);
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
        $numberAllocation = NumberAllocation::getRecordForLogById($id);
            $updateNumberAllocationFields = [];
            if (Config::get('Constant.DEFAULT_VISUAL') == 'Y') {
                if ($postArr['section'] != '[]') {
                    $vsection = $postArr['section'];
                } else {
                    $vsection = '';
                }
            } else {
                $vsection = $postArr['description'];
            }
            $updateNumberAllocationFields['varTitle'] = stripslashes(trim($postArr['title']));
            $updateNumberAllocationFields['intFKCategory'] = trim($postArr['category_id']);
            $updateNumberAllocationFields['varShortDescription'] = stripslashes(trim($postArr['short_description']));
            $updateNumberAllocationFields['txtDescription'] = $vsection;
            $updateNumberAllocationFields['varMetaTitle'] = stripslashes(trim($postArr['varMetaTitle']));
            $updateNumberAllocationFields['varMetaDescription'] = stripslashes(trim($postArr['varMetaDescription']));
            $updateNumberAllocationFields['varTags'] = trim($postArr['tags']);
            if (Config::get('Constant.CHRSearchRank') == 'Y') {
                $updateNumberAllocationFields['intSearchRank'] = $postArr['search_rank'];
            }
            $updateNumberAllocationFields['chrPublish'] = $postArr['chrMenuDisplay'];
            $updateNumberAllocationFields['dtDateTime'] = !empty($postArr['start_date_time']) ? date('Y-m-d H:i:s', strtotime($postArr['start_date_time'])) : date('Y-m-d H:i:s');
            $updateNumberAllocationFields['dtEndDateTime'] = !empty($postArr['end_date_time']) ? date('Y-m-d H:i:s', strtotime($postArr['end_date_time'])) : null;
            $updateNumberAllocationFields['fkIntImgId'] = !empty($postArr['img_id']) ? $postArr['img_id'] : null;
            $updateNumberAllocationFields['UserID'] = auth()->user()->id;
            $updateNumberAllocationFields['chrIsPreview'] = 'Y';
            $whereConditions = ['id' => $id];
            if ($postArr['oldAlias'] != $postArr['alias']) {
                Alias::updateAlias($postArr['oldAlias'], $postArr['alias']);
            }
            $update = CommonModel::updateRecords($whereConditions, $updateNumberAllocationFields, false, 'Powerpanel\NumberAllocation\Models\NumberAllocation');
        } else {
            $numberAllocationArr['dtDateTime'] = !empty($postArr['start_date_time']) ? date('Y-m-d H:i:s', strtotime($postArr['start_date_time'])) : date('Y-m-d H:i:s');
            $numberAllocationArr['dtEndDateTime'] = !empty($postArr['end_date_time']) ? date('Y-m-d H:i:s', strtotime($postArr['end_date_time'])) : null;
            $numberAllocationArr['chrIsPreview'] = 'Y';
            $id = $this->insertNewRecord($postArr, $numberAllocationArr, 'Y')->id;
        }
        return json_encode(array('status' => $id, 'alias' => $postArr['alias'], 'message' => trans('template.pageModule.pageUpdate')));
    }

    public function insertApprovedRecord($updateModuleFields, $postArr, $id)
    {
        $whereConditions = ['id' => $postArr['fkMainRecord']];
        $updateModuleFields['chrAddStar'] = 'N';
        $updateModuleFields['chrPublish'] = trim($postArr['chrMenuDisplay']);
        $updateModuleFields['UserID'] = auth()->user()->id;
        $update = CommonModel::updateRecords($whereConditions, $updateModuleFields, false, 'Powerpanel\NumberAllocation\Models\NumberAllocation');
        if ($update) {
            self::swap_order_edit($postArr['display_order'], $postArr['fkMainRecord']);
        }
        $whereConditions_ApproveN = ['fkMainRecord' => $postArr['fkMainRecord']];
        $updateToApproveN = [
            'chrApproved' => 'N',
            'chrLetest' => 'N',
            'intApprovedBy' => '0',
        ];
        CommonModel::updateRecords($whereConditions_ApproveN, $updateToApproveN, false, 'Powerpanel\NumberAllocation\Models\NumberAllocation');
        $whereConditionsApprove = ['id' => $id, 'chrMain' => 'N'];
        $updateToApprove = [
            'chrApproved' => 'Y',
            'chrRollBack' => 'Y',
            'intApprovedBy' => auth()->user()->id,
        ];
        CommonModel::updateRecords($whereConditionsApprove, $updateToApprove, false, 'Powerpanel\NumberAllocation\Models\NumberAllocation');
        if ($postArr['chrMenuDisplay'] == 'D') {
            $addlog = Config::get('Constant.DRAFT_RECORD_APPROVED');
        } else {
            $addlog = Config::get('Constant.RECORD_APPROVED');
        }
        $newCmsPageObj = NumberAllocation::getRecordForLogById($id);
        $logArr = MyLibrary::logData($id, false, $addlog);
        $logArr['varTitle'] = stripslashes($newCmsPageObj->nxx);
        Log::recordLog($logArr);
        /* notification for user to record approved */
        $careers = NumberAllocation::getRecordForLogById($id);
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
                self::flushCache();
                $actionMessage = trans('number-allocation::template.numberAllocationModule.updateMessage');
            }
        }
    }

    public function insertApprovalRecord($moduleObj, $postArr, $numberAllocationArr)
    {
        $response = false;
        $numberAllocationArr['chrMain'] = 'N';
        $numberAllocationArr['chrLetest'] = 'Y';
        $numberAllocationArr['fkMainRecord'] = $moduleObj->id;
        $numberAllocationArr['varSector'] = $postArr['sector'];
        $numberAllocationArr['nxx'] = stripslashes(trim($postArr['nxx']));
        $numberAllocationArr['intFKCategory'] = trim($postArr['categoryId']);
        $numberAllocationArr['intDisplayOrder'] = $postArr['display_order'];

        if (Config::get('Constant.CHRSearchRank') == 'Y') {
            if (isset($postArr['search_rank']) && !empty($postArr['search_rank'])) {
                $numberAllocationArr['intSearchRank'] = $postArr['search_rank'];
            }
        }

        if ($postArr['chrMenuDisplay'] == 'D') {
            $numberAllocationArr['chrDraft'] = 'D';
            $numberAllocationArr['chrPublish'] = 'N';
        } else {
            $numberAllocationArr['chrDraft'] = 'N';
            $numberAllocationArr['chrPublish'] = $postArr['chrMenuDisplay'];
        }

        if (isset($postArr['chrPageActive']) && $postArr['chrPageActive'] != '') {
            $numberAllocationArr['chrPageActive'] = $postArr['chrPageActive'];
        }

        if (isset($postArr['chrPageActive']) && $postArr['chrPageActive'] == 'PP') {
            $numberAllocationArr['varPassword'] = $postArr['new_password'];
        } else {
            $numberAllocationArr['varPassword'] = '';
        }

        if ($postArr['chrMenuDisplay'] == 'D') {
            $addlog = Config::get('Constant.DRAFT_SENT_FOR_APPROVAL');
        } else {
            $addlog = Config::get('Constant.SENT_FOR_APPROVAL');
        }

        $numberAllocationArr['created_at'] = Carbon::now();
        $numberAllocationArr['UserID'] = auth()->user()->id;

        $numberAllocationID = CommonModel::addRecord($numberAllocationArr, 'Powerpanel\NumberAllocation\Models\NumberAllocation');
        if (!empty($numberAllocationID)) {
            $id = $numberAllocationID;
            WorkflowLog::addRecord([
                'fkModuleId' => Config::get('Constant.MODULE.ID'),
                'fkRecordId' => $moduleObj->id,
                'charApproval' => 'Y',
            ]);
            if (method_exists($this->MyLibrary, 'userNotificationData')) {
                $userNotificationArr = MyLibrary::userNotificationData(Config::get('Constant.MODULE.ID'));
                $userNotificationArr['fkRecordId'] = $moduleObj->id;
                $userNotificationArr['txtNotification'] = 'New approval request from ' . ucfirst(auth()->user()->name) . ' (Powerpanel\NumberAllocation\Models\\' . ucfirst(Config::get('Constant.MODULE.NAME')) . ')';
                $userNotificationArr['fkIntUserId'] = Auth::user()->id;
                $userNotificationArr['chrNotificationType'] = 'A';
                UserNotification::addRecord($userNotificationArr);
            }
            $newBlogObj = NumberAllocation::getRecordForLogById($id);
            $logArr = MyLibrary::logData($id, false, $addlog);
            $logArr['varTitle'] = $newBlogObj->varTitle;
            Log::recordLog($logArr);
            if (Auth::user()->can('recent-updates-list')) {
                $notificationArr = MyLibrary::notificationData($id, $newBlogObj);
                RecentUpdates::setNotification($notificationArr);
            }
            $response = $newBlogObj;
            self::flushCache();
            $actionMessage = trans('number-allocation::template.numberAllocationModule.addMessage');
        }
        $whereConditionsAddstar = ['id' => $moduleObj->id];
        $updateAddStar = [
            'chrAddStar' => 'Y',
        ];
        CommonModel::updateRecords($whereConditionsAddstar, $updateAddStar, false, 'Powerpanel\NumberAllocation\Models\NumberAllocation');
        return $response;
    }

    public function insertNewRecord($postArr, $numberAllocationArr, $preview = 'N')
    {
        $response = false;
        $numberAllocationArr['chrMain'] = 'Y';
        $numberAllocationArr['nxx'] = stripslashes(trim($postArr['nxx']));
        $numberAllocationArr['intFKCategory'] = trim($postArr['categoryId']);
        $numberAllocationArr['varSector'] = $postArr['sector'];
        $numberAllocationArr['service'] = $postArr['service'];
        $numberAllocationArr['note'] = $postArr['note'];
        $numberAllocationArr['intDisplayOrder'] = self::swap_order_add($postArr['display_order']);

        if (Config::get('Constant.CHRSearchRank') == 'Y') {
            if (isset($postArr['search_rank']) && !empty($postArr['search_rank'])) {
                $numberAllocationArr['intSearchRank'] = $postArr['search_rank'];
            }

        }

        if ($postArr['chrMenuDisplay'] == 'D') {
            $numberAllocationArr['chrDraft'] = 'D';
            $numberAllocationArr['chrPublish'] = 'N';
        } else {
            $numberAllocationArr['chrDraft'] = 'N';
        }

        if (isset($postArr['chrPageActive']) && $postArr['chrPageActive'] != '') {
            $numberAllocationArr['chrPageActive'] = $postArr['chrPageActive'];
        }

        if (isset($postArr['chrPageActive']) && $postArr['chrPageActive'] == 'PP') {
            $numberAllocationArr['varPassword'] = $postArr['new_password'];
        } else {
            $numberAllocationArr['varPassword'] = '';
        }

        if ($postArr['chrMenuDisplay'] == 'D') {
            $addlog = Config::get('Constant.ADDED_DRAFT');
        } else {
            $addlog = '';
        }

        $numberAllocationArr['UserID'] = auth()->user()->id;
        $numberAllocationArr['created_at'] = Carbon::now();
        $numberAllocationID = CommonModel::addRecord($numberAllocationArr, 'Powerpanel\NumberAllocation\Models\NumberAllocation');
        if (!empty($numberAllocationID)) {
            $id = $numberAllocationID;
            $newNumberAllocationObj = NumberAllocation::getRecordForLogById($id);
            $logArr = MyLibrary::logData($id, false, $addlog);
            $logArr['varTitle'] = $newNumberAllocationObj->nxx;
            Log::recordLog($logArr);
            if (Auth::user()->can('recent-updates-list')) {
                $notificationArr = MyLibrary::notificationData($id, $newNumberAllocationObj);
                RecentUpdates::setNotification($notificationArr);
            }
            $response = $newNumberAllocationObj;
            self::flushCache();
            $actionMessage = trans('number-allocation::template.numberAllocationModule.addMessage');
        }
        return $response;
    }

    /**
     * This method destroys Faq in multiples
     * @return  Faq index view
     * @since   2016-10-25
     * @author  NetQuick
     */
    public function DeleteRecord(Request $request)
    {
        $value = Request::input('value');
        $data['ids'] = Request::input('ids');
        $moduleHaveFields = ['chrMain', 'chrIsPreview'];
        $update = MyLibrary::deleteMultipleRecords($data, $moduleHaveFields, $value, 'Powerpanel\NumberAllocation\Models\NumberAllocation');
        if (File::exists(app_path() . '/Comments.php') != null || File::exists(base_path() . '/packages/Powerpanel/Workflow/src/Models/Comments.php') != null) {
            Comments::deleteComments($data['ids'], Config::get('Constant.MODULE.MODEL_NAME'));
        }
        foreach ($update as $ids) {
            $ignoreDeleteScope = true;
            $Deleted_Record = NumberAllocation::getRecordById($ids, $ignoreDeleteScope);
            $Cnt_Letest = NumberAllocation::getRecordCount_letest($Deleted_Record['fkMainRecord'], $Deleted_Record['id']);
            if ($Cnt_Letest <= 0) {
                $updateLetest = [
                    'chrAddStar' => 'N',
                ];
                $whereConditionsApprove = ['id' => $Deleted_Record['fkMainRecord']];
                CommonModel::updateRecords($whereConditionsApprove, $updateLetest, false, 'Powerpanel\NumberAllocation\Models\NumberAllocation');
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
     * This method destroys Faq in multiples
     * @return  Faq index view
     * @since   2016-10-25
     * @author  NetQuick
     */
    public function publish(Request $request)
    {
        $requestArr = Request::all();
        $val = Request::get('val');
        $alias = Request::input('alias');
        $update = MyLibrary::setPublishUnpublish($alias, $val, 'Powerpanel\NumberAllocation\Models\NumberAllocation');
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
        MyLibrary::swapOrder($order, $exOrder, 'Powerpanel\NumberAllocation\Models\NumberAllocation');
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
            $response = MyLibrary::swapOrderAdd($order, $isCustomizeModule, $moduleHaveFields, 'Powerpanel\NumberAllocation\Models\NumberAllocation');
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
        MyLibrary::swapOrderEdit($order, $id, $isCustomizeModule, $moduleHaveFields, 'Powerpanel\NumberAllocation\Models\NumberAllocation');
        self::flushCache();
    }

    public function tableData($value, $totalRecord = false, $tableSortedType = 'asc')
    {
        $sector = '';
        if (isset($value->varSector) && !empty($value->varSector)) {
            $sector = strtoupper($value->varSector);
        }

        $actions = '';
        $publish_action = '';

        if (Auth::user()->can('number-allocation-edit')) {
            $actions .= '<a class="" title="' . trans("number-allocation::template.common.edit") . '" href="' . route('powerpanel.number-allocation.edit', array('alias' => $value->id)) . '">
				<i class="ri-pencil-line"></i></a>';
        }
        if (Auth::user()->can('number-allocation-delete') || (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y')) {
            if (Config::get('Constant.DEFAULT_TRASH') == 'Y') {
                $actions .= '<a class="delete-grid" title="' . trans("number-allocation::template.common.delete") . '" onclick = \'Trashfun("' . $value->id . '")\' data-controller="NumberAllocationController" data-alias = "' . $value->id . '" data-tab="P"><i class="ri-delete-bin-line"></i></a>';
            } else {
                $actions .= '<a class=" delete" title="' . trans("number-allocation::template.common.delete") . '" data-controller="NumberAllocationController" data-alias = "' . $value->id . '" data-tab="P"><i class="ri-delete-bin-line"></i></a>';
            }
        }
        if ($value->chrAddStar != 'Y') {
            if ($value->chrDraft != 'D') {
                if (Auth::user()->can('number-allocation-publish')) {
                    if ($value->chrPublish == 'Y') {
                        //Bootstrap Switch
                        $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/number-allocation', 'data_alias'=>$value->id, 'title'=>trans("number-allocation::template.common.publishedRecord"), 'data_value'=>'Unpublish'])->render();
                    } else {
                        //Bootstrap Switch
                        $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/number-allocation', 'data_alias'=>$value->id, 'title'=>trans("number-allocation::template.common.unpublishedRecord"), 'data_value'=>'Publish', 'checked'=>'checked'])->render();
                    }
                }
            } else {
                //Bootstrap Switch
                $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/number-allocation', 'data_alias'=>$value->id, 'title'=>trans("number-allocation::template.common.unpublishedRecord"), 'data_value'=>'Publish', 'checked'=>'checked'])->render();
            }
        } else {
            $publish_action .= '---';
        }

        $nxx = $value->nxx;
        $service = $value->service;
        if (Auth::user()->can('number-allocation-edit')) {
            $categoryRecordAlias = MyLibrary::getRecordAliasByModuleNameRecordId("blog-category", $value->intFKCategory);

            if ($value->chrDraft == 'D' || $value->chrAddStar == 'Y') {
                $viewlink = url('/previewpage?url=' . MyLibrary::getFrontUri('number-allocation')['uri'] . '/' . $value->id . '/preview/detail');
                $linkviewLable = "Preview";
            } else {
                $viewlink = MyLibrary::getFrontUri('number-allocation')['uri'] . '/' . $value->id;
                $linkviewLable = "View";
            }

            if ($value->chrLock != 'Y') {
                if (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y') {
                    $nxx = '<div class="quick_edit"><a href = "' . route('powerpanel.number-allocation.edit', array('alias' => $value->id)) . '?tab=P">' . $value->nxx . '</a> <div class="quick_edit_menu">
														<span><a href="' . route('powerpanel.number-allocation.edit', array('alias' => $value->id)) . '?tab=P" title="Edit">Edit</a></span>';
                    if (Config::get('Constant.DEFAULT_QUICK') == 'Y') {
                        $nxx .= '<span><a title="Quick Edit" href=\'javascript:;\' data-toggle=\'modal\' data-target=\'#modalForm\' aria-label=\'Quick edit\' onclick=\'Quickeditfun("' . $value->id . '","' . $value->nxx . '","' . $value->intSearchRank . '","P")\'>Quick Edit</a></span>';
                    }
                    if (Config::get('Constant.DEFAULT_TRASH') == 'Y') {
                        $nxx .= '<span><a title = "Trash" href = \'javascript:;\' onclick=\'Trashfun("' . $value->id . '")\' class="red" data-tab="P">Trash</a></span>';
                    }
                    $nxx .= '
																</div>
											 </div>';
                } else {
                    $nxx = '<div class="quick_edit"><a href = "' . route('powerpanel.number-allocation.edit', array('alias' => $value->id)) . '?tab=P">' . $value->nxx . '</a> <div class="quick_edit_menu">
														<span><a href="' . route('powerpanel.number-allocation.edit', array('alias' => $value->id)) . '?tab=P" title="Edit">Edit</a></span>
																</div>
											 </div>';
                }
            } else {
                if (auth()->user()->id != $value->LockUserID) {
                    if (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y') {
                        $nxx = '<div class="quick_edit"><a href = "' . route('powerpanel.number-allocation.edit', array('alias' => $value->id)) . '?tab=P">' . $value->nxx . '</a> <div class="quick_edit_menu">
                            <span><a href="' . route('powerpanel.number-allocation.edit', array('alias' => $value->id)) . '?tab=P" title="Edit">Edit</a></span>
                                </div>
                       </div>';
                    } else {
                        $nxx = '<div class="quick_edit"><a href = "javascript:;">' . $value->nxx . '</a></div>';
                    }
                } else {
                    $nxx = '<div class="quick_edit"><a href = "' . route('powerpanel.number-allocation.edit', array('alias' => $value->id)) . '?tab=P">' . $value->nxx . '</a> <div class="quick_edit_menu">
                            <span><a href="' . route('powerpanel.number-allocation.edit', array('alias' => $value->id)) . '?tab=P" title="Edit">Edit</a></span>
                                </div>
                       </div>';
                }
            }
        }
        if (Auth::user()->can('number-allocation-reviewchanges') && (File::exists(app_path() . '/Workflow.php') != null || File::exists(base_path() . '/packages/Powerpanel/Workflow/src/Models/Workflow.php') != null)) {
            $update = "<a title=\"Click here to see all approval records.\" class=\"icon_title1\" style=\"margin-right: 5px;\" onclick=\"javascript:expandcollapsepanel(this ,'tasklisting" . $value->id . "', 'mainsingnimg" . $value->id . "'," . $value->id . ")\"><i id=\"mainsingnimg" . $value->id . "\" class=\"ri-add-box-line\"></i></a>";
            $rollback = "<a title=\"Click here to see all approved records to rollback.\" class=\"icon_title2\" style=\"margin-right: 5px;\" onclick=\"javascript:expandcollapsepanel_rolback(this ,'tasklisting_rollback" . $value->id . "', 'mainsingnimg_rollback" . $value->id . "'," . $value->id . ")\"><i id=\"mainsingnimg_rollback" . $value->id . "\" class=\"ri-history-line\"></i></a>";
        } else {
            $update = '';
            $rollback = '';
        }

        $orderArrow = '';
        $dispOrder = $value->intDisplayOrder;
        if (($value->intDisplayOrder == $totalRecord || $value->intDisplayOrder < $totalRecord) && $value->intDisplayOrder > 1) {
            $orderArrow .= '<a href="javascript:;" data-order="' . $value->intDisplayOrder . '" class="moveUp"><i class="ri-arrow-up-line" aria-hidden="true"></i></a>
								';
        }
        $orderArrow .= $dispOrder;
        if (($value->intDisplayOrder != $totalRecord || $value->intDisplayOrder < $totalRecord)) {
            $orderArrow .= ' <a href="javascript:;" data-order="' . $value->intDisplayOrder . '" class="moveDwn"><i class="ri-arrow-down-line" aria-hidden="true"></i></a>';
        }

        $blogscatarray = $value->blogscat->toArray();
        $blogscatdata = $blogscatarray['varTitle'];
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
                $log .= $actions;
                if (Auth::user()->can('log-list')) {
                    $log .= "<a title=\"Log History\" class='log-grid' href=\"$logurl\"><i class=\"ri-time-line\"></i></a>";
                }
            } else {
                if ($actions == "") {
                    $actions = "---";
                } else {
                    $actions = $actions;
                }
                $log .= $actions;
                if (Auth::user()->can('log-list')) {
                    $log .= "<a title=\"Log History\" class='log-grid' href=\"$logurl\"><i class=\"ri-time-line\"></i></a>";
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
                if ((auth()->user()->id == $value->LockUserID) || (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y')) {
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
            '<div class="pages_title_div_row">' . $update . $rollback . $nxx . ' ' . $status . $statusdata . ' ' . $sector . '</div>',
            $blogscatdata,
            $service,
            $orderArrow,
            $publish_action,
            $log,
        );
        return $records;
    }

    public function tableData_tab1($value, $totalRecord = false)
    {
        $sector = '';
        if (isset($value->varSector) && !empty($value->varSector)) {
            $sector = strtoupper($value->varSector);
        }

        $actions = '';
        $publish_action = '';

        if (Auth::user()->can('number-allocation-edit')) {
            $actions .= '<a class="" title="' . trans("number-allocation::template.common.edit") . '" href="' . route('powerpanel.number-allocation.edit', array('alias' => $value->id)) . '">
				<i class="ri-pencil-line"></i></a>';
        }
        if (Auth::user()->can('number-allocation-delete') || (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y')) {
            if (Config::get('Constant.DEFAULT_TRASH') == 'Y') {
                $actions .= '<a class="delete-grid" title="' . trans("number-allocation::template.common.delete") . '" onclick = \'Trashfun("' . $value->id . '")\' data-controller="NumberAllocationController" data-alias = "' . $value->id . '" data-tab="P"><i class="ri-delete-bin-line"></i></a>';
            } else {
                $actions .= '<a class=" delete" title="' . trans("number-allocation::template.common.delete") . '" data-controller="NumberAllocationController" data-alias = "' . $value->id . '" data-tab="P"><i class="ri-delete-bin-line"></i></a>';
            }
        }

        $nxx = $value->nxx;
        $service = $value->service;
        if (Auth::user()->can('number-allocation-edit')) {
            $categoryRecordAlias = MyLibrary::getRecordAliasByModuleNameRecordId("blog-category", $value->intFKCategory);

            if ($value->chrDraft == 'D' || $value->chrAddStar == 'Y') {
                $viewlink = url('/previewpage?url=' . MyLibrary::getFrontUri('number-allocation')['uri'] . '/' . $value->id . '/preview/detail');
                $linkviewLable = "Preview";
            } else {
                $viewlink = MyLibrary::getFrontUri('number-allocation')['uri'] . '/' . $value->id;
                $linkviewLable = "View";
            }

            if ($value->chrLock != 'Y') {
                if (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y') {
                    $nxx = '<div class="quick_edit"><a href = "' . route('powerpanel.number-allocation.edit', array('alias' => $value->id)) . '?tab=P">' . $value->nxx . '</a> <div class="quick_edit_menu">
														<span><a href="' . route('powerpanel.number-allocation.edit', array('alias' => $value->id)) . '?tab=P" title="Edit">Edit</a></span>';
                    if (Config::get('Constant.DEFAULT_QUICK') == 'Y') {
                        $nxx .= '<span><a title="Quick Edit" href=\'javascript:;\' data-toggle=\'modal\' data-target=\'#modalForm\' aria-label=\'Quick edit\' onclick=\'Quickeditfun("' . $value->id . '","' . $value->nxx . '","' . $value->intSearchRank . '","P")\'>Quick Edit</a></span>';
                    }
                    if (Config::get('Constant.DEFAULT_TRASH') == 'Y') {
                        $nxx .= '<span><a title = "Trash" href = \'javascript:;\' onclick=\'Trashfun("' . $value->id . '")\' class="red" data-tab="P">Trash</a></span>';
                    }
                    $nxx .= '
																</div>
											 </div>';
                } else {
                    $nxx = '<div class="quick_edit"><a href = "' . route('powerpanel.number-allocation.edit', array('alias' => $value->id)) . '?tab=P">' . $value->nxx . '</a> <div class="quick_edit_menu">
														<span><a href="' . route('powerpanel.number-allocation.edit', array('alias' => $value->id)) . '?tab=P" title="Edit">Edit</a></span>
																</div>
											 </div>';
                }
            } else {
                if (auth()->user()->id != $value->LockUserID) {
                    if (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y') {
                        $nxx = '<div class="quick_edit"><a href = "' . route('powerpanel.number-allocation.edit', array('alias' => $value->id)) . '?tab=P">' . $value->nxx . '</a> <div class="quick_edit_menu">
                            <span><a href="' . route('powerpanel.number-allocation.edit', array('alias' => $value->id)) . '?tab=P" title="Edit">Edit</a></span>
                                </div>
                       </div>';
                    } else {
                        $nxx = '<div class="quick_edit"><a href = "javascript:;">' . $value->nxx . '</a></div>';
                    }
                } else {
                    $nxx = '<div class="quick_edit"><a href = "' . route('powerpanel.number-allocation.edit', array('alias' => $value->id)) . '?tab=P">' . $value->nxx . '</a> <div class="quick_edit_menu">
                            <span><a href="' . route('powerpanel.number-allocation.edit', array('alias' => $value->id)) . '?tab=P" title="Edit">Edit</a></span>
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
                $log .= $actions;
                if (Auth::user()->can('log-list')) {
                    $log .= "<a title=\"Log History\" class='log-grid' href=\"$logurl\"><i class=\"ri-time-line\"></i></a>";
                }
            } else {
                if ($actions == "") {
                    $actions = "---";
                } else {
                    $actions = $actions;
                }
                $log .= $actions;
                if (Auth::user()->can('log-list')) {
                    $log .= "<a title=\"Log History\" class='log-grid' href=\"$logurl\"><i class=\"ri-time-line\"></i></a>";
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

        $blogscatarray = $value->blogscat->toArray();
        $blogscatdata = $blogscatarray['varTitle'];

        if (Auth::user()->can('number-allocation-reviewchanges')) {
            $update = "<a title=\"Click here to see all approval records.\" class=\"icon_title1\" style=\"margin-right: 5px;\" onclick=\"javascript:expandcollapsepanel(this ,'tasklisting" . $value->id . "', 'mainsingnimg" . $value->id . "'," . $value->id . ")\"><i id=\"mainsingnimg" . $value->id . "\" class=\"ri-add-box-line\"></i></a>";
            $rollback = "<a title=\"Click here to see all approved records to rollback.\" class=\"icon_title2\" style=\"margin-right: 5px;\" onclick=\"javascript:expandcollapsepanel_rolback(this ,'tasklisting_rollback" . $value->id . "', 'mainsingnimg_rollback" . $value->id . "'," . $value->id . ")\"><i id=\"mainsingnimg_rollback" . $value->id . "\" class=\"ri-history-line\"></i></a>";
        } else {
            $update = '';
            $rollback = '';
        }

        if (Auth::user()->can('blogs-reviewchanges') && $value->chrAddStar == 'Y') {
            $star = 'addhiglight';
        } else {
            $star = '';
        }

        if ($actions == "") {
            $actions = "---";
        } else {
            $actions = $actions;
        }

        $orderArrow = '';
        $dispOrder = $value->intDisplayOrder;
        if (($value->intDisplayOrder == $totalRecord || $value->intDisplayOrder < $totalRecord) && $value->intDisplayOrder > 1) {
            $orderArrow .= '<a href="javascript:;" data-order="' . $value->intDisplayOrder . '" class="moveUp"><i class="ri-arrow-up-line" aria-hidden="true"></i></a>
								';
        }
        $orderArrow .= $dispOrder;
        if (($value->intDisplayOrder != $totalRecord || $value->intDisplayOrder < $totalRecord)) {
            $orderArrow .= ' <a href="javascript:;" data-order="' . $value->intDisplayOrder . '" class="moveDwn"><i class="ri-arrow-down-line" aria-hidden="true"></i></a>';
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
            '<div class="pages_title_div_row">' . $update . $rollback . $nxx . ' ' . $status . $statusdata . ' ' . $sector . '</div>',
            $blogscatdata,
            $service,
            $orderArrow,
            $log,
        );
        return $records;
    }

    public function tableDataFavorite($value, $totalRecord = false, $tableSortedType = 'asc')
    {
        $sector = '';
        if (isset($value->varSector) && !empty($value->varSector)) {
            $sector = strtoupper($value->varSector);
        }

        $actions = '';
        $publish_action = '';

        if (Auth::user()->can('number-allocation-edit')) {
            $actions .= '<a class="" title="' . trans("number-allocation::template.common.edit") . '" href="' . route('powerpanel.number-allocation.edit', array('alias' => $value->id)) . '">
				<i class="ri-pencil-line"></i></a>';
        }
        if (Auth::user()->can('number-allocation-delete') && $this->currentUserRoleData->chrIsAdmin == 'Y') {
            if (Config::get('Constant.DEFAULT_TRASH') == 'Y') {
                $actions .= '<a class="delete-grid" title="' . trans("number-allocation::template.common.delete") . '" onclick = \'Trashfun("' . $value->id . '")\' data-controller="NumberAllocationController" data-alias = "' . $value->id . '" data-tab="P"><i class="ri-delete-bin-line"></i></a>';
            } else {
                $actions .= '<a class=" delete" title="' . trans("number-allocation::template.common.delete") . '" data-controller="NumberAllocationController" data-alias = "' . $value->id . '" data-tab="P"><i class="ri-delete-bin-line"></i></a>';
            }
        }

        $nxx = $value->nxx;
        $service = $value->service;

        if (Auth::user()->can('number-allocation-publish')) {
            if ($value->chrPublish == 'Y') {
                //Bootstrap Switch
                $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/number-allocation', 'data_alias'=>$value->id, 'title'=>trans("number-allocation::template.common.publishedRecord"), 'data_value'=>'Unpublish'])->render();
            } else {
                //Bootstrap Switch
                $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/number-allocation', 'data_alias'=>$value->id, 'title'=>trans("number-allocation::template.common.unpublishedRecord"), 'data_value'=>'Publish', 'checked'=>'checked'])->render();
            }
        }

        if (Auth::user()->can('number-allocation-edit')) {
            $categoryRecordAlias = MyLibrary::getRecordAliasByModuleNameRecordId("blog-category", $value->intFKCategory);

            if ($value->chrDraft == 'D' || $value->chrAddStar == 'Y') {
                $viewlink = url('/previewpage?url=' . MyLibrary::getFrontUri('number-allocation')['uri'] . '/' . $value->id . '/preview/detail');
                $linkviewLable = "Preview";
            } else {
                $viewlink = MyLibrary::getFrontUri('number-allocation')['uri'] . '/' . $value->id;
                $linkviewLable = "View";
            }

            if ($value->chrLock != 'Y') {
                if (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y') {
                    $nxx = '<div class="quick_edit"><a href = "' . route('powerpanel.number-allocation.edit', array('alias' => $value->id)) . '?tab=P">' . $value->nxx . '</a> <div class="quick_edit_menu">
														<span><a href="' . route('powerpanel.number-allocation.edit', array('alias' => $value->id)) . '?tab=P" title="Edit">Edit</a></span>';
                    if (Config::get('Constant.DEFAULT_QUICK') == 'Y') {
                        $nxx .= '<span><a title="Quick Edit" href=\'javascript:;\' data-toggle=\'modal\' data-target=\'#modalForm\' aria-label=\'Quick edit\' onclick=\'Quickeditfun("' . $value->id . '","' . $value->nxx . '","' . $value->intSearchRank . '","P")\'>Quick Edit</a></span>';
                    }
                    if (Config::get('Constant.DEFAULT_TRASH') == 'Y') {
                        $nxx .= '<span><a title = "Trash" href = \'javascript:;\' onclick=\'Trashfun("' . $value->id . '")\' class="red" data-tab="P">Trash</a></span>';
                    }
                    $nxx .= '
																</div>
											 </div>';
                } else {
                    $nxx = '<div class="quick_edit"><a href = "' . route('powerpanel.number-allocation.edit', array('alias' => $value->id)) . '?tab=P">' . $value->nxx . '</a> <div class="quick_edit_menu">
														<span><a href="' . route('powerpanel.number-allocation.edit', array('alias' => $value->id)) . '?tab=P" title="Edit">Edit</a></span>
																</div>
											 </div>';
                }
            } else {
                if (auth()->user()->id != $value->LockUserID) {
                    if (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y') {
                        $nxx = '<div class="quick_edit"><a href = "' . route('powerpanel.number-allocation.edit', array('alias' => $value->id)) . '?tab=P">' . $value->nxx . '</a> <div class="quick_edit_menu">
                            <span><a href="' . route('powerpanel.number-allocation.edit', array('alias' => $value->id)) . '?tab=P" title="Edit">Edit</a></span>
                                </div>
                       </div>';
                    } else {
                        $nxx = '<div class="quick_edit"><a href = "javascript:;">' . $value->nxx . '</a></div>';
                    }
                } else {
                    $nxx = '<div class="quick_edit"><a href = "' . route('powerpanel.number-allocation.edit', array('alias' => $value->id)) . '?tab=P">' . $value->nxx . '</a> <div class="quick_edit_menu">
                            <span><a href="' . route('powerpanel.number-allocation.edit', array('alias' => $value->id)) . '?tab=P" title="Edit">Edit</a></span>
                                </div>
                       </div>';
                }
            }
        }

        if (Auth::user()->can('number-allocation-reviewchanges') && (File::exists(app_path() . '/Workflow.php') != null || File::exists(base_path() . '/packages/Powerpanel/Workflow/src/Models/Workflow.php') != null)) {
            $update = "<a title=\"Click here to see all approval records.\" class=\"icon_title1\" style=\"margin-right: 5px;\" onclick=\"javascript:expandcollapsepanel(this ,'tasklisting" . $value->id . "', 'mainsingnimg" . $value->id . "'," . $value->id . ")\"><i id=\"mainsingnimg" . $value->id . "\" class=\"ri-add-box-line\"></i></a>";
            $rollback = "<a title=\"Click here to see all approved records to rollback.\" class=\"icon_title2\" style=\"margin-right: 5px;\" onclick=\"javascript:expandcollapsepanel_rolback(this ,'tasklisting_rollback" . $value->id . "', 'mainsingnimg_rollback" . $value->id . "'," . $value->id . ")\"><i id=\"mainsingnimg_rollback" . $value->id . "\" class=\"ri-history-line\"></i></a>";
        } else {
            $update = '';
            $rollback = '';
        }

        $blogscatarray = $value->blogscat->toArray();
        $blogscatdata = $blogscatarray['varTitle'];

        if (Config::get('Constant.DEFAULT_FAVORITE') == 'Y') {
            $Favorite_array = explode(",", $value->FavoriteID);
            if (in_array(auth()->user()->id, $Favorite_array)) {
                $Class = 'ri-star-fill';
                $Favorite = '<a class="star_icon_div" href="javascript:;" onclick="GetFavorite(' . $value->id . ',\'N\',\'F\')"><i class="' . $Class . '"></i></a>';
            } else {
                $Class = 'ri-star-line';
                $Favorite = '<a class="star_icon_div" href="javascript:;" onclick="GetFavorite(' . $value->id . ',\'Y\',\'F\')"><i class="' . $Class . '"></i></a>';
            }
        } else {
            $Favorite = '';
        }
        $First_td = '<div class="star_box">' . $Favorite . '</div>';

        $logurl = url('powerpanel/log?id=' . $value->id . '&mid=' . Config::get('Constant.MODULE.ID'));
        if ($actions == "") {
            $actions = "---";
        } else {
            $actions = $actions;
        }

        $log = '';
        if ($value->chrLock != 'Y') {
            $log .= $actions;
            if (Auth::user()->can('log-list')) {
                $log .= "<a title=\"Log History\" href=\"$logurl\"><i class=\"ri-time-line\"></i></a>";
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

        $orderArrow = '';
        $dispOrder = $value->intDisplayOrder;
        if (($value->intDisplayOrder == $totalRecord || $value->intDisplayOrder < $totalRecord) && $value->intDisplayOrder > 1) {
            $orderArrow .= '<a href="javascript:;" data-order="' . $value->intDisplayOrder . '" class="moveUp"><i class="ri-arrow-up-line" aria-hidden="true"></i></a>
								';
        }
        $orderArrow .= $dispOrder;
        if (($value->intDisplayOrder != $totalRecord || $value->intDisplayOrder < $totalRecord)) {
            $orderArrow .= ' <a href="javascript:;" data-order="' . $value->intDisplayOrder . '" class="moveDwn"><i class="ri-arrow-down-line" aria-hidden="true"></i></a>';
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
            '<div class="pages_title_div_row">' . $nxx . ' ' . $status . $statusdata . ' ' . $sector . '</div>',
            $blogscatdata,
            $service,
            $orderArrow,
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
        $actions = '';

        if (Auth::user()->can('number-allocation-delete') && $this->currentUserRoleData->chrIsAdmin == 'Y') {
            $actions .= '<a class=" delete" title="' . trans("number-allocation::template.common.delete") . '"  data-controller="NumberAllocationController" data-alias = "' . $value->id . '" data-tab="T"><i class="ri-delete-bin-line"></i></a>';
        }

        $nxx = $value->nxx;
        $service = $value->service;
        if (Auth::user()->can('number-allocation-edit')) {
            $nxx = '<div class="quick_edit text-uppercase"><a href = "' . route('powerpanel.number-allocation.edit', array('alias' => $value->id)) . '?tab=T">' . $value->nxx . '</a>
												</div>';
        }
        $blogscatarray = $value->blogscat->toArray();
        $blogscatdata = $blogscatarray['varTitle'];

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

        $orderArrow = '';
        $dispOrder = $value->intDisplayOrder;
        if (($value->intDisplayOrder == $totalRecord || $value->intDisplayOrder < $totalRecord) && $value->intDisplayOrder > 1) {
            $orderArrow .= '<a href="javascript:;" data-order="' . $value->intDisplayOrder . '" class="moveUp"><i class="ri-arrow-up-line" aria-hidden="true"></i></a>
								';
        }
        $orderArrow .= $dispOrder;
        if (($value->intDisplayOrder != $totalRecord || $value->intDisplayOrder < $totalRecord)) {
            $orderArrow .= ' <a href="javascript:;" data-order="' . $value->intDisplayOrder . '" class="moveDwn"><i class="ri-arrow-down-line" aria-hidden="true"></i></a>';
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
            '<div class="pages_title_div_row">' . $nxx  . ' ' . $sector . '</div>',
            $blogscatdata,
            $service,
            $orderArrow,
            $log,
        );
        return $records;
    }

    public function tableDataDraft($value, $totalRecord = false, $tableSortedType = 'asc')
    {
        $sector = '';
        if (isset($value->varSector) && !empty($value->varSector)) {
            $sector = strtoupper($value->varSector);
        }

        $actions = '';
        $publish_action = '';
        if (Auth::user()->can('number-allocation-edit')) {
            $actions .= '<a class="" title="' . trans("number-allocation::template.common.edit") . '" href="' . route('powerpanel.number-allocation.edit', array('alias' => $value->id)) . '">
				<i class="ri-pencil-line"></i></a>';
        }
        if (Auth::user()->can('number-allocation-delete') || (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y')) {
            if (Config::get('Constant.DEFAULT_TRASH') == 'Y') {
                $actions .= '<a class="delete-grid" title="' . trans("number-allocation::template.common.delete") . '" onclick = \'Trashfun("' . $value->id . '")\' data-controller="NumberAllocationController" data-alias = "' . $value->id . '" data-tab="P"><i class="ri-delete-bin-line"></i></a>';
            } else {
                $actions .= '<a class=" delete" title="' . trans("number-allocation::template.common.delete") . '" data-controller="NumberAllocationController" data-alias = "' . $value->id . '" data-tab="P"><i class="ri-delete-bin-line"></i></a>';
            }
        }
        
        if ($value->chrPublish == 'Y') {
            //Bootstrap Switch
            $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/number-allocation', 'data_alias'=>$value->id, 'title'=>trans("number-allocation::template.common.publishedRecord"), 'data_value'=>'Unpublish'])->render();
        } else {
            //Bootstrap Switch
            $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/number-allocation', 'data_alias'=>$value->id, 'title'=>trans("number-allocation::template.common.unpublishedRecord"), 'data_value'=>'Publish', 'checked'=>'checked'])->render();
        }

        $nxx = $value->nxx;
        $service = $value->service;
        if (Auth::user()->can('number-allocation-edit')) {
            $categoryRecordAlias = MyLibrary::getRecordAliasByModuleNameRecordId("blog-category", $value->intFKCategory);

            if ($value->chrDraft == 'D' || $value->chrAddStar == 'Y') {
                $viewlink = url('/previewpage?url=' . MyLibrary::getFrontUri('number-allocation')['uri'] . '/' . $value->id . '/preview/detail');
                $linkviewLable = "Preview";
            } else {
                $viewlink = MyLibrary::getFrontUri('number-allocation')['uri'] . '/' . $value->id;
                $linkviewLable = "View";
            }

            if ($value->chrLock != 'Y') {
                if (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y') {
                    $nxx = '<div class="quick_edit"><a href = "' . route('powerpanel.number-allocation.edit', array('alias' => $value->id)) . '?tab=P">' . $value->nxx . '</a> <div class="quick_edit_menu">
														<span><a href="' . route('powerpanel.number-allocation.edit', array('alias' => $value->id)) . '?tab=P" title="Edit">Edit</a></span>';
                    if (Config::get('Constant.DEFAULT_QUICK') == 'Y') {
                        $nxx .= '<span><a title="Quick Edit" href=\'javascript:;\' data-toggle=\'modal\' data-target=\'#modalForm\' aria-label=\'Quick edit\' onclick=\'Quickeditfun("' . $value->id . '","' . $value->nxx . '","' . $value->intSearchRank . '","P")\'>Quick Edit</a></span>';
                    }
                    if (Config::get('Constant.DEFAULT_TRASH') == 'Y') {
                        $nxx .= '<span><a title = "Trash" href = \'javascript:;\' onclick=\'Trashfun("' . $value->id . '")\' class="red" data-tab="P">Trash</a></span>';
                    }
                    $nxx .= '	</div>
											 </div>';
                } else {
                    $nxx = '<div class="quick_edit"><a href = "' . route('powerpanel.number-allocation.edit', array('alias' => $value->id)) . '?tab=P">' . $value->nxx . '</a> <div class="quick_edit_menu">
														<span><a href="' . route('powerpanel.number-allocation.edit', array('alias' => $value->id)) . '?tab=P" title="Edit">Edit</a></span>
																</div>
											 </div>';
                }
            } else {
                if (auth()->user()->id != $value->LockUserID) {
                    if (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y') {
                        $nxx = '<div class="quick_edit"><a href = "' . route('powerpanel.number-allocation.edit', array('alias' => $value->id)) . '?tab=P">' . $value->nxx . '</a> <div class="quick_edit_menu">
                            <span><a href="' . route('powerpanel.number-allocation.edit', array('alias' => $value->id)) . '?tab=P" title="Edit">Edit</a></span>
                                </div>
                       </div>';
                    } else {
                        $nxx = '<div class="quick_edit"><a href = "javascript:;">' . $value->nxx . '</a></div>';
                    }
                } else {
                    $nxx = '<div class="quick_edit"><a href = "' . route('powerpanel.number-allocation.edit', array('alias' => $value->id)) . '?tab=P">' . $value->nxx . '</a> <div class="quick_edit_menu">
                            <span><a href="' . route('powerpanel.number-allocation.edit', array('alias' => $value->id)) . '?tab=P" title="Edit">Edit</a></span>
                                </div>
                       </div>';
                }
            }
        }

        $blogscatarray = $value->blogscat->toArray();
        $blogscatdata = $blogscatarray['varTitle'];

        $orderArrow = '';
        $dispOrder = $value->intDisplayOrder;
        if (($value->intDisplayOrder == $totalRecord || $value->intDisplayOrder < $totalRecord) && $value->intDisplayOrder > 1) {
            $orderArrow .= '<a href="javascript:;" data-order="' . $value->intDisplayOrder . '" class="moveUp"><i class="ri-arrow-up-line" aria-hidden="true"></i></a>
								';
        }
        $orderArrow .= $dispOrder;
        if (($value->intDisplayOrder != $totalRecord || $value->intDisplayOrder < $totalRecord)) {
            $orderArrow .= ' <a href="javascript:;" data-order="' . $value->intDisplayOrder . '" class="moveDwn"><i class="ri-arrow-down-line" aria-hidden="true"></i></a>';
        }

        $logurl = url('powerpanel/log?id=' . $value->id . '&mid=' . Config::get('Constant.MODULE.ID'));
        if ($actions == "") {
            $actions = "---";
        } else {
            $actions = $actions;
        }

        $log = '';
        if ($value->chrLock != 'Y') {
            $log .= $actions;
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
            '<div class="pages_title_div_row"><input type="hidden" id="draftid" value="' . $value->id . '">' . $nxx . ' ' . $status . $statusdata . ' ' . $sector . '</div>',
            $blogscatdata,
            $service,
            $orderArrow,
            $publish_action,
            $log,
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

        $returnHtml = '';
        $startDate = date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($data->dtDateTime));
        $endDate = !empty($data->dtEndDateTime) ? date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($data->dtEndDateTime)) : 'No Expiry';
        $CompanyCategory = CompanyCategory::getCatData($data->intFKCategory);

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
						<th align="center">' . trans('number-allocation::template.common.title') . '</th>
						<th align="center">Category</th>
																								<th align="center">Short Description</th>
																				<th align="center">Description</th>
																								<th align="center">' . trans("number-allocation::template.common.image") . '</th>
						<th align="center">Start Date</th>
						<th align="center">End Date</th>
																								<th align="center">Meta Title</th>
																																<th align="center">Meta Description</th>
																								<th align="center">' . trans("number-allocation::template.common.publish") . '</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td align="center">' . stripslashes($data->varTitle) . '</td>
						<td align="center">' . $CompanyCategory->varTitle . '</td>   <td align="center">' . stripslashes($data->varShortDescription) . '</td>
					<td align="center">' . $desc . '</td>';

        if ($data->fkIntImgId > 0) {
            $returnHtml .= '<td align="center">' . '<img height="50" width="50" src="' . resize_image::resize($data->fkIntImgId) . '" />' . '</td>';
        } else {
            $returnHtml .= '<td align="center">-</td>';
        }
        $returnHtml .= '<td align="center">' . $startDate . '</td>
						<td align="center">' . $endDate . '</td>
																										<td align="center">' . stripslashes($data->varMetaTitle) . '</td>
																																				<td align="center">' . stripslashes($data->varMetaDescription) . '</td>
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
        $returnHtml = '';
        $startDate = date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($newdata->dtDateTime));
        $endDate = !empty($newdata->dtEndDateTime) ? date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($newdata->dtEndDateTime)) : 'No Expiry';
        $CompanyCategory = CompanyCategory::getCatData($newdata->intFKCategory);
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
        if ($data->fkIntImgId != $newdata->fkIntImgId) {
            $imgcolor = 'style="background-color:#f5efb7"';
        } else {
            $imgcolor = '';
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

        $returnHtml .= '<table class="new_table_desing table table-striped table-bordered table-hover">
				<thead>
					<tr>
						<th align="center">' . trans('number-allocation::template.common.title') . '</th>
						<th align="center">Category</th>
																								<th align="center">Short Description</th>
								<th align="center">Description</th>
																								<th align="center">' . trans("number-allocation::template.common.image") . '</th>
						<th align="center">Start Date</th>
						<th align="center">End Date</th>
																								<th align="center">Meta Title</th>
																																 <th align="center">Meta Description</th>
																								<th align="center">' . trans("number-allocation::template.common.publish") . '</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td align="center" ' . $titlecolor . '>' . stripslashes($newdata->varTitle) . '</td>
						<td align="center" ' . $catcolor . '>' . $CompanyCategory->varTitle . '</td><td align="center" ' . $ShortDescriptioncolor . '>' . stripslashes($newdata->varShortDescription) . '</td>
				        <td align="center" ' . $desccolor . '>' . $desc . '</td>';
        if ($data->fkIntImgId > 0) {
            $returnHtml .= '<td align="center" ' . $imgcolor . '>' . '<img height="50" width="50" src="' . resize_image::resize($newdata->fkIntImgId) . '" />' . '</td>';
        } else {
            $returnHtml .= '<td align="center">-</td>';
        }
        $returnHtml .= '<td align="center" ' . $sdatecolor . '>' . $startDate . '</td>
						<td align="center" ' . $edatecolor . '>' . $endDate . '</td>
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
        Cache::tags('Faq')->flush();
    }

    public function getChildData()
    {
        $childHtml = "";
        $Cmspage_childData = "";
        $Cmspage_childData = NumberAllocation::getChildGrid();
        // echo '<pre>';print_r($Cmspage_childData);die;
        $childHtml .= "<div class=\"producttbl\" style=\"\">";
        $childHtml .= "<table class=\"new_table_desing table table-striped table-bordered table-hover table-checkable dataTable\" id=\"email_log_datatable_ajax\">
                            <tr role=\"row\">
                                    <th class=\"text-center\"></th>
                                    <th class=\"text-center\">NXX#</th>
                                    <th class=\"text-center\">Date Submitted</th>
                                    <th class=\"text-center\">User</th>
                                    <th class=\"text-center\">Edit</th>
                                    <th class=\"text-center\">Status</th>";
        $childHtml .= "</tr>";
        if (count($Cmspage_childData) > 0) {
            foreach ($Cmspage_childData as $child_row) {
                $childHtml .= "<tr role=\"row\">";

                if ($child_row->chrApproved == 'N') {
                    $childHtml .= "<td><span class='mob_show_title'>&nbsp</span><input type=\"checkbox\" name=\"delete\" class=\"chkDelete form-check-input\" value='" . $child_row->id . "'></td>";
                } else {
                    $childHtml .= "<td><span class='mob_show_title'>&nbsp</span><div class=\"checker\"><a href=\"javascript:;\" data-toggle=\"tooltip\" data-placement=\"right\" title=\"This is approved record, so can't be deleted.\"><i style=\"color:red\" class=\"ri-alert-fill\"></i></a></div></td>";
                }

                $childHtml .= '<td class="text-center"><span class="mob_show_title">Title: </span>' . $child_row->nxx . '</td>';
                $childHtml .= "<td class=\"text-center\"><span class='mob_show_title'>Date Submitted: </span>" . date('M d Y h:i A', strtotime($child_row->created_at)) . "</td>";
                $childHtml .= "<td class=\"text-center\"><span class='mob_show_title'>User: </span>" . CommonModel::getUserName($child_row->UserID) . "</td>";
                // $categoryRecordAlias = MyLibrary::getRecordAliasByModuleNameRecordId("blog-category", $child_row->intFKCategory);
                // $previewlink = url('/previewpage?url=' . MyLibrary::getFrontUri('blogs')['uri'] . '/' . $child_row->id . '/preview/detail');
                // $childHtml .= "<td class=\"text-center\"><span class='mob_show_title'>Preview: </span><a class='icon_round' href=" . $previewlink . " target='_blank'><i class=\"ri-computer-line\"></i></a></td>";
                if ($child_row->chrApproved == 'N') {
                    $childHtml .= "<td class=\"text-center\"><span class='mob_show_title'>Edit: </span><a class='icon_round' title='" . trans("number-allocation::template.common.edit") . "' href='" . route('powerpanel.number-allocation.edit', array('alias' => $child_row->id)) . "'>
							<i class='ri-pencil-line'></i></a></td>";
                } else {
                    $childHtml .= "<td class=\"text-center\"><span class='mob_show_title'>Edit: </span>-</td>";
                }
                if ($child_row->chrApproved == 'N') {
                    $childHtml .= "<td class=\"text-center\"><span class='mob_show_title'>Status: </span><a class=\"approve_icon_btn\" title='" . trans("number-allocation::template.common.comments") . "'   href=\"javascript:;\" onclick=\"loadModelpopup('" . $child_row->id . "','" . $child_row->UserID . "','" . Config::get('Constant.MODULE.MODEL_NAME') . "','" . $child_row->fkMainRecord . "')\"><i class=\"ri-chat-1-line\"></i> <span>Comment</span></a>    <a  onclick=\"update_mainrecord('" . $child_row->id . "','" . $child_row->fkMainRecord . "','" . $child_row->UserID . "','A');\" title='" . trans("number-allocation::template.common.clickapprove") . "' class=\"approve_icon_btn\" href=\"javascript:;\"><i class=\"ri-checkbox-line\"></i> <span>Approve</span></a></td>";
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

    public function getChildData_rollback()
    {
        $child_rollbackHtml = "";
        $Cmspage_rollbackchildData = "";
        $Cmspage_rollbackchildData = NumberAllocation::getChildrollbackGrid();
        $child_rollbackHtml .= "<div class=\"producttbl producttb2\" style=\"\">";
        $child_rollbackHtml .= "<table class=\"new_table_desing table table-striped table-bordered table-hover table-checkable dataTable\" id=\"email_log_datatable_ajax\">
							    <tr role=\"row\">																								<th class=\"text-center\">NXX#</th>
                                    <th class=\"text-center\">Date</th>
                                    <th class=\"text-center\">User</th>
                                    <th class=\"text-center\">Status</th>";
        $child_rollbackHtml .= "         </tr>";
        if (count($Cmspage_rollbackchildData) > 0) {
            foreach ($Cmspage_rollbackchildData as $child_rollbacrow) {
                $child_rollbackHtml .= "<tr role=\"row\">";
                $child_rollbackHtml .= '<td class="text-center"><span class="mob_show_title">Title: </span>' . $child_rollbacrow->nxx . '</td>';
                $child_rollbackHtml .= "<td class=\"text-center\"><span class='mob_show_title'>Date: </span>" . date('M d Y h:i A', strtotime($child_rollbacrow->created_at)) . "</td>";
                $child_rollbackHtml .= "<td class=\"text-center\"><span class='mob_show_title'>User: </span>" . CommonModel::getUserName($child_rollbacrow->UserID) . "</td>";
                // $categoryRecordAlias = MyLibrary::getRecordAliasByModuleNameRecordId("blog-category", $child_rollbacrow->intFKCategory);
                // $previewlink = url('/previewpage?url=' . MyLibrary::getFrontUri('blogs')['uri'] . '/' . $child_rollbacrow->id . '/preview/detail');
                // $child_rollbackHtml .= "<td class=\"text-center\"><span class='mob_show_title'>Preview: </span><a class='icon_round' href=" . $previewlink . " target='_blank'><i class=\"ri-computer-line\"></i></a></td>";
                if ($child_rollbacrow->chrApproved == 'Y') {
                    $child_rollbackHtml .= "<td class=\"text-center\"><span class='mob_show_title'>Status: </span><i class=\"ri-checkbox-circle-line\" style=\"color: #1080F2;font-size:30px;\"></i></td>";
                } else {

                    // $child_rollbackHtml .= "<td class=\"text-center\">
                    //<span class='mob_show_title'>Status: </span><a onclick=\"update_mainrecord('" . $child_rollbacrow->id . "','" . $child_rollbacrow->fkMainRecord . "','" . $child_rollbacrow->UserID . "','R');\"  class=\"approve_icon_btn\">
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

    public function ApprovedData_Listing(Request $request)
    {
        $requestArr = Request::all();
        $request = (object) $requestArr;
        $id = Request::post('id');
        $main_id = Request::post('main_id');
        $approvalid = Request::post('id');
        $flag = Request::post('flag');
        $approvalData = NumberAllocation::getOrderOfApproval($id);
        $message = NumberAllocation::approved_data_Listing($request);
        $newCmsPageObj = NumberAllocation::getRecordForLogById($main_id);
        $approval_obj = NumberAllocation::getRecordForLogById($approvalid);
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
        $careers = NumberAllocation::getRecordForLogById($id);
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
        $logArr['varTitle'] = stripslashes($newCmsPageObj->nxx);
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

    public function rollBackRecord(Request $request)
    {

        $message = 'Previous record is not available';
        $requestArr = Request::all();
        $request = (object) $requestArr;

        $previousRecord = NumberAllocation::getPreviousRecordByMainId($request->id);
        if (!empty($previousRecord)) {

            $main_id = $previousRecord->fkMainRecord;
            $request->id = $previousRecord->id;
            $request->main_id = $main_id;

            $message = NumberAllocation::approved_data_Listing($request);

            /* notification for user to record approved */
            $numberAllocation = NumberAllocation::getRecordForLogById($previousRecord->id);
            if(!empty($numberAllocation))
            {
                if (method_exists($this->MyLibrary, 'userNotificationData')) {
                    $userNotificationArr = MyLibrary::userNotificationData(Config::get('Constant.MODULE.ID'));
                    $userNotificationArr['fkRecordId'] = $previousRecord->id;
                    $userNotificationArr['txtNotification'] = 'Your request has been approved by ' . ucfirst(auth()->user()->name) . ' (' . ucfirst(Config::get('Constant.MODULE.NAME')) . ')';
                    $userNotificationArr['fkIntUserId'] = Auth::user()->id;
                    $userNotificationArr['chrNotificationType'] = 'A';
                    $userNotificationArr['intOnlyForUserId'] = $numberAllocation->UserID;
                    UserNotification::addRecord($userNotificationArr);
                }
            }
            /* notification for user to record approved */

            $newBlogObj = NumberAllocation::getRecordForLogById($main_id);
            $restoredata = Config::get('Constant.ROLLBACK_RECORD');
            if(!empty($newBlogObj)) {
                $logArr = MyLibrary::logData($main_id, false, $restoredata);
                $logArr['varTitle'] = stripslashes($newBlogObj->nxx);
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
        $arrResults = NumberAllocation::getBuilderRecordList($filterArr);
        $found = $arrResults->toArray();
        if (!empty($found)) {
            foreach ($arrResults as $key => $value) {
                $rows .= $this->tableDataBuilder($value, false, $filterArr['selected']);
            }
        } else {
            $rows .= '<tr id="not-found"><td colspan="4" align="center">No records found.</td></tr>';
        }
        $iTotalRecords = CommonModel::getTotalRecordCount('Powerpanel\NumberAllocation\Models\NumberAllocation', true, true);
        $records["data"] = $rows;
        $records["found"] = count($found);
        $records["recordsTotal"] = $iTotalRecords;
        return json_encode($records);
    }

    public function tableDataBuilder($value = false, $fcnt = false, $selected = [])
    {
        $publish_action = '';
        $dtFormat = Config::get('Constant.DEFAULT_DATE_FORMAT');
        $categories = CompanyCategory::getRecordByIds($value->intFKCategory)->toArray();
        $categories = array_column($categories, 'varTitle'); //print_r($categories); die;
        $categories = implode(', ', $categories);
        $startDate = date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($value->dtDateTime));
        $endDate = !empty($value->dtEndDateTime) ? date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($value->dtEndDateTime)) : 'No Expiry';
        $record = '<tr ' . (in_array($value->id, $selected) ? 'class="selected-record"' : '') . '>';
        $record .= '<td width="1%" align="center">';
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
        $record .= '<td width="20%" align="center">';
        $record .= $startDate;
        $record .= '</td>';
        $record .= '<td width="20%" align="center">';
        $record .= $endDate;
        $record .= '</td>';
        $record .= '<td width="20%" align="center">';
        $record .= date($dtFormat, strtotime($value->updated_at));
        $record .= '</td>';
        $record .= '</tr>';
        return $record;
    }

}
