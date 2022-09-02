<?php

namespace Powerpanel\QuickLinks\Controllers\Powerpanel;

use App\Alias;
use App\CommonModel;
use App\Helpers\MyLibrary;
use App\Helpers\ParentRecordHierarchy_builder;
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
use Powerpanel\CmsPage\Models\CmsPage;
use Powerpanel\QuickLinks\Models\QuickLinks;
use Powerpanel\RoleManager\Models\Role_user;
use Powerpanel\Workflow\Models\Comments;
use Powerpanel\Workflow\Models\Workflow;
use Powerpanel\Workflow\Models\WorkflowLog;
use Request;
use Validator;

class QuickLinksController extends PowerpanelController
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
    }

    /**
     * This method handels load banner grid
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

        $total = QuickLinks::getRecordCount(false, false, $userIsAdmin, $this->currentUserRoleSector);
        $NewRecordsCount = QuickLinks::getNewRecordsCount($userIsAdmin, $this->currentUserRoleSector);
        $draftTotalRecords = QuickLinks::getRecordCountforListDarft(false, true, $userIsAdmin, array(), $this->currentUserRoleSector);
        $trashTotalRecords = QuickLinks::getRecordCountforListTrash(false, false, $userIsAdmin, [], $this->currentUserRoleSector);
        $favoriteTotalRecords = QuickLinks::getRecordCountforListFavorite(false, false, $userIsAdmin, [], $this->currentUserRoleSector);
        $cms_pages = $total > 0 ? CmsPage::getPagesWithModule() : null;
        $this->breadcrumb['title'] = trans('quick-links::template.quickLinkModule.manage');
        if (method_exists($this->CommonModel, 'GridColumnData')) {
            $settingdata = CommonModel::GridColumnData(Config::get('Constant.MODULE.ID'));
            $settingarray = array();
            foreach ($settingdata as $sdata) {
                $settingarray[$sdata->chrtab][] = $sdata->columnid;
            }
        } else {
            $settingarray = '';
        }
        return view('quick-links::powerpanel.list', ['userIsAdmin' => $userIsAdmin, 'NewRecordsCount' => $NewRecordsCount, 'total' => $total, 'cms_pages' => $cms_pages, 'breadcrumb' => $this->breadcrumb, 'draftTotalRecords' => $draftTotalRecords, 'trashTotalRecords' => $trashTotalRecords, 'favoriteTotalRecords' => $favoriteTotalRecords, 'settingarray' => json_encode($settingarray)]);
    }

    /**
     * This method handels list of banner with filters
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
        $filterArr['statusFilter'] = !empty(Request::input('customActionName')) ? Request::input('customActionName') : '';
        $filterArr['sectorFilter'] = !empty(Request::input('sectorValue')) ? Request::input('sectorValue') : '';
        $filterArr['bannerFilter'] = !empty(Request::input('bannerFilter')) ? Request::input('bannerFilter') : '';
        $filterArr['linkFilterType'] = !empty(Request::input('linkFilterType')) ? Request::input('linkFilterType') : '';
        $filterArr['pageFilter'] = !empty(Request::input('pageFilter')) ? Request::input('pageFilter') : '';
        $filterArr['iDisplayLength'] = intval(Request::input('length'));
        $filterArr['iDisplayStart'] = intval(Request::input('start'));
        $sEcho = intval(Request::input('draw'));
        $isAdmin = false;
        if (!empty($this->currentUserRoleData)) {
            if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                $isAdmin = true;
            }
        } else {
            $isAdmin = true;
        }
        $arrResults = QuickLinks::getRecordList($filterArr, $isAdmin, $this->currentUserRoleSector);
        $iTotalRecords = QuickLinks::getRecordCountforList($filterArr, true, $isAdmin, $this->currentUserRoleSector);
        $end = $filterArr['iDisplayStart'] + $filterArr['iDisplayLength'];
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;
        $tableSortedType = (isset($filterArr['orderTypeAscOrDesc']) && $filterArr['orderTypeAscOrDesc'] != "") ? $filterArr['orderTypeAscOrDesc'] : '';
        $totalRecords = QuickLinks::getRecordCount(false, false, $isAdmin, $this->currentUserRoleSector);
        if (count($arrResults) > 0 && !empty($arrResults)) {
            foreach ($arrResults as $key => $value) {
                $records["data"][] = $this->tableData($value, $totalRecords, $tableSortedType);
            }
        }
        $NewRecordsCount = QuickLinks::getNewRecordsCount($isAdmin, $this->currentUserRoleSector);
        $records["newRecordCount"] = $NewRecordsCount;
        $records["customActionStatus"] = "OK";
        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;
        echo json_encode($records);
        exit;
    }

    /**
     * This method handels list of banner with filters
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
        $filterArr['orderByFieldName'] = (!empty(Request::input('columns')[$filterArr['orderColumnNo']]['name']) ? Request::input('columns')[$filterArr['orderColumnNo']]['name'] : '');
        $filterArr['orderTypeAscOrDesc'] = (!empty(Request::input('order')[0]['dir']) ? Request::input('order')[0]['dir'] : '');
        $filterArr['searchFilter'] = !empty(Request::input('searchValue')) ? Request::input('searchValue') : '';
        $filterArr['statusFilter'] = !empty(Request::input('customActionName')) ? Request::input('customActionName') : '';
        $filterArr['sectorFilter'] = !empty(Request::input('sectorValue')) ? Request::input('sectorValue') : '';
        $filterArr['bannerFilter'] = !empty(Request::input('bannerFilter')) ? Request::input('bannerFilter') : '';
        $filterArr['linkFilterType'] = !empty(Request::input('linkFilterType')) ? Request::input('linkFilterType') : '';
        $filterArr['pageFilter'] = !empty(Request::input('pageFilter')) ? Request::input('pageFilter') : '';
        $filterArr['iDisplayLength'] = intval(Request::input('length'));
        $filterArr['iDisplayStart'] = intval(Request::input('start'));
        $sEcho = intval(Request::input('draw'));
        $isAdmin = false;
        if (!empty($this->currentUserRoleData)) {
            if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                $isAdmin = true;
            }
        }
        $arrResults = QuickLinks::getRecordListFavorite($filterArr, $isAdmin, $this->currentUserRoleSector);
        $iTotalRecords = QuickLinks::getRecordCountforListFavorite($filterArr, true, $isAdmin, [], $this->currentUserRoleSector);
        $end = $filterArr['iDisplayStart'] + $filterArr['iDisplayLength'];
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;
        $tableSortedType = (isset($filterArr['orderTypeAscOrDesc']) && $filterArr['orderTypeAscOrDesc'] != "") ? $filterArr['orderTypeAscOrDesc'] : '';
        $totalRecords = QuickLinks::getRecordCount(false, false, $isAdmin, $this->currentUserRoleSector);
        if (count($arrResults) > 0 && !empty($arrResults)) {
            foreach ($arrResults as $key => $value) {
                $records["data"][] = $this->tableDataFavorite($value, $totalRecords, $tableSortedType);
            }
        }
        $NewRecordsCount = QuickLinks::getNewRecordsCount($isAdmin, $this->currentUserRoleSector);
        $records["newRecordCount"] = $NewRecordsCount;
        $records["customActionStatus"] = "OK";
        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;
        echo json_encode($records);
        exit;
    }

    /**
     * This method handels list of banner with filters
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
        $filterArr['orderByFieldName'] = (!empty(Request::input('columns')[$filterArr['orderColumnNo']]['name']) ? Request::input('columns')[$filterArr['orderColumnNo']]['name'] : '');
        $filterArr['orderTypeAscOrDesc'] = (!empty(Request::input('order')[0]['dir']) ? Request::input('order')[0]['dir'] : '');
        $filterArr['searchFilter'] = !empty(Request::input('searchValue')) ? Request::input('searchValue') : '';
        $filterArr['statusFilter'] = !empty(Request::input('customActionName')) ? Request::input('customActionName') : '';
        $filterArr['sectorFilter'] = !empty(Request::input('sectorValue')) ? Request::input('sectorValue') : '';
        $filterArr['bannerFilter'] = !empty(Request::input('bannerFilter')) ? Request::input('bannerFilter') : '';
        $filterArr['linkFilterType'] = !empty(Request::input('linkFilterType')) ? Request::input('linkFilterType') : '';
        $filterArr['pageFilter'] = !empty(Request::input('pageFilter')) ? Request::input('pageFilter') : '';
        $filterArr['iDisplayLength'] = intval(Request::input('length'));
        $filterArr['iDisplayStart'] = intval(Request::input('start'));
        $sEcho = intval(Request::input('draw'));
        $isAdmin = false;
        if (!empty($this->currentUserRoleData)) {
            if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                $isAdmin = true;
            }
        }
        $arrResults = QuickLinks::getRecordListDraft($filterArr, $isAdmin, $this->currentUserRoleSector);
        $iTotalRecords = QuickLinks::getRecordCountforListDarft($filterArr, true, $isAdmin, [], $this->currentUserRoleSector);
        $end = $filterArr['iDisplayStart'] + $filterArr['iDisplayLength'];
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;
        $tableSortedType = (isset($filterArr['orderTypeAscOrDesc']) && $filterArr['orderTypeAscOrDesc'] != "") ? $filterArr['orderTypeAscOrDesc'] : '';
        $totalRecords = QuickLinks::getRecordCount(false, false, $isAdmin, $this->currentUserRoleSector);
        if (count($arrResults) > 0 && !empty($arrResults)) {
            foreach ($arrResults as $key => $value) {
                $records["data"][] = $this->tableDataDraft($value, $totalRecords, $tableSortedType);
            }
        }
        $NewRecordsCount = QuickLinks::getNewRecordsCount($isAdmin, $this->currentUserRoleSector);
        $records["newRecordCount"] = $NewRecordsCount;
        $records["customActionStatus"] = "OK";
        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;
        echo json_encode($records);
        exit;
    }

    /**
     * This method handels list of banner with filters
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
        $filterArr['orderByFieldName'] = (!empty(Request::input('columns')[$filterArr['orderColumnNo']]['name']) ? Request::input('columns')[$filterArr['orderColumnNo']]['name'] : '');
        $filterArr['orderTypeAscOrDesc'] = (!empty(Request::input('order')[0]['dir']) ? Request::input('order')[0]['dir'] : '');
        $filterArr['searchFilter'] = !empty(Request::input('searchValue')) ? Request::input('searchValue') : '';
        $filterArr['statusFilter'] = !empty(Request::input('customActionName')) ? Request::input('customActionName') : '';
        $filterArr['sectorFilter'] = !empty(Request::input('sectorValue')) ? Request::input('sectorValue') : '';
        $filterArr['bannerFilter'] = !empty(Request::input('bannerFilter')) ? Request::input('bannerFilter') : '';
        $filterArr['linkFilterType'] = !empty(Request::input('linkFilterType')) ? Request::input('linkFilterType') : '';
        $filterArr['pageFilter'] = !empty(Request::input('pageFilter')) ? Request::input('pageFilter') : '';
        $filterArr['iDisplayLength'] = intval(Request::input('length'));
        $filterArr['iDisplayStart'] = intval(Request::input('start'));
        $sEcho = intval(Request::input('draw'));
        $isAdmin = false;
        if (!empty($this->currentUserRoleData)) {
            if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                $isAdmin = true;
            }
        }
        $arrResults = QuickLinks::getRecordListTrash($filterArr, $isAdmin);
        $iTotalRecords = QuickLinks::getRecordCountforListTrash($filterArr, true, $isAdmin, [], $this->currentUserRoleSector);
        $end = $filterArr['iDisplayStart'] + $filterArr['iDisplayLength'];
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;
        $tableSortedType = (isset($filterArr['orderTypeAscOrDesc']) && $filterArr['orderTypeAscOrDesc'] != "") ? $filterArr['orderTypeAscOrDesc'] : '';
        $totalRecords = QuickLinks::getRecordCount(false, false, $isAdmin, $this->currentUserRoleSector);
        if (count($arrResults) > 0 && !empty($arrResults)) {
            foreach ($arrResults as $key => $value) {
                $records["data"][] = $this->tableDataTrash($value, $totalRecords, $tableSortedType);
            }
        }
        $NewRecordsCount = QuickLinks::getNewRecordsCount($isAdmin, $this->currentUserRoleSector);
        $records["newRecordCount"] = $NewRecordsCount;
        $records["customActionStatus"] = "OK";
        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;
        echo json_encode($records);
        exit;
    }

    /**
     * This method handels list of Quick Links with Approval tab
     * @return  View
     * @since   2017-07-20
     * @author  NetQuick
     */
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
        $filterArr['orderByFieldName'] = (!empty(Request::input('columns')[$filterArr['orderColumnNo']]['name']) ? Request::input('columns')[$filterArr['orderColumnNo']]['name'] : '');
        $filterArr['orderTypeAscOrDesc'] = (!empty(Request::input('order')[0]['dir']) ? Request::input('order')[0]['dir'] : '');
        $filterArr['searchFilter'] = !empty(Request::input('searchValue')) ? Request::input('searchValue') : '';
        $filterArr['statusFilter'] = !empty(Request::input('customActionName')) ? Request::input('customActionName') : '';
        $filterArr['sectorFilter'] = !empty(Request::input('sectorValue')) ? Request::input('sectorValue') : '';
        $filterArr['bannerFilter'] = !empty(Request::input('bannerFilter')) ? Request::input('bannerFilter') : '';
        $filterArr['linkFilterType'] = !empty(Request::input('linkFilterType')) ? Request::input('linkFilterType') : '';
        $filterArr['pageFilter'] = !empty(Request::input('pageFilter')) ? Request::input('pageFilter') : '';
        $filterArr['iDisplayLength'] = intval(Request::input('length'));
        $filterArr['iDisplayStart'] = intval(Request::input('start'));
        $sEcho = intval(Request::input('draw'));
        $arrResults = QuickLinks::getRecordListApprovalTab($filterArr, $userIsAdmin, $this->currentUserRoleSector);
        $iTotalRecords = QuickLinks::getRecordCountListApprovalTab($filterArr, $userIsAdmin, $this->currentUserRoleSector);
        $end = $filterArr['iDisplayStart'] + $filterArr['iDisplayLength'];
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;
        if (count($arrResults) > 0 && !empty($arrResults)) {
            foreach ($arrResults as $key => $value) {
                $records["data"][] = $this->tableDataWaitingToApprovedData($value);
            }
        }
        $NewRecordsCount = QuickLinks::getNewRecordsCount($userIsAdmin, $this->currentUserRoleSector);
        $records["newRecordCount"] = $NewRecordsCount;
        $records["customActionStatus"] = "OK";
        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;
        echo json_encode($records);
        exit;
    }

    /**
     * This method loads banner edit view
     * @param   Alias of record
     * @return  View
     * @since   2017-11-10
     * @author  NetQuick
     */
    public function edit($id = false)
    {
        if (method_exists($this->MyLibrary, 'getModuleNamesWithoutAlias')) {
            $withoutAliasModulesNames = MyLibrary::getModuleNamesWithoutAlias();
        } else {
            $withoutAliasModulesNames = '';
        }
        if (method_exists($this->MyLibrary, 'getModuleIdsByNames')) {
            $withoutAliasModules = Modules::getModuleIdsByNames($withoutAliasModulesNames);
        } else {
            $withoutAliasModules = '';
        }
        $withoutAliasModuleIds = array();
        if (!empty($withoutAliasModules)) {
            $withoutAliasModuleIds = array_column($withoutAliasModules, 'id');
        }
        $module = Modules::getFrontModuleList($withoutAliasModuleIds);
        $templateData = array();
        $userIsAdmin = false;
        if (isset($this->currentUserRoleData) && !empty($this->currentUserRoleData)) {
            if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                $userIsAdmin = true;
            }
        }

        if (!is_numeric($id)) {
            $total = QuickLinks::getRecordCount(false, false, $userIsAdmin, $this->currentUserRoleSector);
            if (Auth::user()->can('quick-links-create') || $userIsAdmin) {
                $total = $total + 1;
            }
            $this->breadcrumb['title'] = trans('quick-links::template.quickLinkModule.add');
            $this->breadcrumb['module'] = trans('quick-links::template.quickLinkModule.manage');
            $this->breadcrumb['url'] = 'powerpanel/quick-links';
            $this->breadcrumb['inner_title'] = trans('quick-links::template.quickLinkModule.add');
            $breadcrumb = $this->breadcrumb;
            $templateData['modules'] = $module;
            $templateData['total'] = $total;
            $templateData['breadcrumb'] = $this->breadcrumb;
        } else {
            $quickLinks = QuickLinks::getRecordById($id);
            if (empty($quickLinks)) {
                return redirect()->route('powerpanel.quicklinks.add');
            }
            if ((int) $quickLinks->fkMainRecord !== 0) {
                $quickLinksHighLight = QuickLinks::getRecordById($quickLinks->fkMainRecord);
                $templateData['quickLinksHighLight'] = $quickLinksHighLight;
            }
            $this->breadcrumb['title'] = trans('quick-links::template.common.edit') . ' - ' . $quickLinks->varTitle;
            $this->breadcrumb['module'] = trans('quick-links::template.quickLinkModule.manage');
            $this->breadcrumb['url'] = 'powerpanel/quick-links';
            $this->breadcrumb['inner_title'] = trans('quick-links::template.common.edit') . ' - ' . $quickLinks->varTitle;
            $breadcrumb = $this->breadcrumb;
            $templateData['quickLinks'] = $quickLinks;
            $templateData['modules'] = $module;
            $templateData['breadcrumb'] = $this->breadcrumb;
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
            $templateData['chrNeedAddPermission'] = $workFlowByCat->chrNeedAddPermission;
            $templateData['charNeedApproval'] = $workFlowByCat->charNeedApproval;
        } else {
            $templateData['chrNeedAddPermission'] = 'N';
            $templateData['charNeedApproval'] = 'N';
        }
        //End Button Name Change For User Side
        $templateData['userIsAdmin'] = $userIsAdmin;
        return view('quick-links::powerpanel.actions', $templateData);
    }

    /**
     * This method stores banner modifications
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
        $postArr = Request::all();
        $quicklinksFields = [];
        $actionMessage = trans('quick-links::template.common.oppsSomethingWrong');
        $rules = array(
            'title' => 'required|max:160|handle_xss|no_url',
            'sector' => 'required',
            'link_type' => 'required',
            'display_order' => 'required|greater_than_zero|handle_xss|no_url',
            'chrMenuDisplay' => 'required',
        );
        if (isset($postArr['link_type']) && $postArr['link_type'] != 'external') {
            $rules['modules'] = 'required';
            $rules['foritem'] = 'required';
        }
        $messsages = array(
            'display_order.required' => trans('quick-links::template.quickLinkModule.displayOrder'),
            'display_order.greater_than_zero' => trans('quick-links::template.quickLinkModule.displayGreaterThan'),
            'title.required' => 'Title field is required.',
            'sector.required' => 'Sector field is required.',
            'foritem.required' => trans('quick-links::template.quickLinkModule.pageValidationMessage'),
        );
        $validator = Validator::make($postArr, $rules, $messsages);
        if ($validator->passes()) {
            $module = Modules::getModuleById(Config::get('Constant.MODULE.ID'));
            if (isset($this->currentUserRoleData)) {
                $currentUserRoleData = $this->currentUserRoleData;
            }
            $pageId = 0;
            if ($postArr['link_type'] == 'internal') {
                $moduleId = $postArr['modules'];
                $pageId = $postArr['foritem'];
                $extLink = '';
            } else {
                $extLink = $postArr['ext_Link'];
                $moduleId = null;
                $pageId = null;
            }
            $quicklinksFields['varTitle'] = stripslashes(trim($postArr['title']));
            $quicklinksFields['varExtLink'] = $extLink;
            $quicklinksFields['varLinkType'] = $postArr['link_type'];
            $quicklinksFields['fkIntPageId'] = $pageId;
            $quicklinksFields['fkModuleId'] = $moduleId;
            $quicklinksFields['varSector'] = $postArr['sector'];
            $quicklinksFields['chrPublish'] = $postArr['chrMenuDisplay'];
            if (Config::get('Constant.CHRSearchRank') == 'Y') {
                $quicklinksFields['intSearchRank'] = $postArr['search_rank'];
            }
            $quicklinksFields['dtDateTime'] = !empty($postArr['start_date_time']) ? date('Y-m-d H:i:s', strtotime(str_replace('/', '-',$postArr['start_date_time']))) : date('Y-m-d H:i:s');
            $quicklinksFields['dtEndDateTime'] = !empty($postArr['end_date_time']) ? date('Y-m-d H:i:s', strtotime(str_replace('/', '-',$postArr['end_date_time']))) : null;

            // $quicklinksFields['created_at'] = Carbon::now();
            $id = Request::segment(3);
            if (is_numeric($id)) { #Edit post Handler=======
            $quickLinks = QuickLinks::getRecordForLogById($id);
                $quicklinksFields['UserID'] = auth()->user()->id;
                $whereConditions = ['id' => $id];
                if ($quickLinks->chrLock == 'Y' && auth()->user()->id != $quickLinks->LockUserID) {
                    if ($this->currentUserRoleData->chrIsAdmin != 'Y') {
                        $lockedUserData = User::getRecordById($quickLinks->LockUserID, true);
                        $lockedUserName = 'someone';
                        if (!empty($lockedUserData)) {
                            $lockedUserName = $lockedUserData->name;
                        }
                        $actionMessage = "This record has been locked by " . $lockedUserName . ".";
                        return redirect()->route('powerpanel.quick-links.index')->with('message', $actionMessage);
                    }
                }
                if ($postArr['chrMenuDisplay'] == 'D') {
                    $quicklinksFields['chrDraft'] = 'D';
                    $quicklinksFields['chrPublish'] = 'N';
                } else {
                    $quicklinksFields['chrDraft'] = 'N';
                    $quicklinksFields['chrPublish'] = $postArr['chrMenuDisplay'];
                }
                if ($postArr['chrMenuDisplay'] == 'D') {
                    $addlog = Config::get('Constant.UPDATE_DRAFT');
                } else {
                    $addlog = '';
                }
                if (File::exists(app_path() . '/Workflow.php') != null || File::exists(base_path() . '/packages/Powerpanel/Workflow/src/Models/Workflow.php') != null) {
                    if (!$userIsAdmin) {
                        $userRole = $currentUserRoleData->id;
                    } else {
                        $userRoleData = Role_user::getUserRoleByUserId($quickLinks->UserID);
                        if (isset($userRoleData->role_id)) {
                            $userRole = $userRoleData->role_id;
                        } else {
                            $userRole = $this->currentUserRoleData->id;
                        }
                    }
                    $workFlowByCat = Workflow::getRecordByCategoryId($module->intFkGroupCode, $userRole, Config::get('Constant.MODULE.ID'));
                    if (empty($workFlowByCat->varUserId) || $userIsAdmin || $workFlowByCat->charNeedApproval == 'N') {
                        if ((int) $quickLinks->fkMainRecord === 0 || empty($workFlowByCat->varUserId)) {
                            $update = CommonModel::updateRecords($whereConditions, $quicklinksFields, false, 'Powerpanel\QuickLinks\Models\QuickLinks');
                            if ($update) {
                                if (!empty($id)) {
                                    self::swap_order_edit($postArr['display_order'], $id);
                                    $logArr = MyLibrary::logData($id, false, $addlog);
                                    if (Auth::user()->can('log-advanced')) {
                                        $newQuickLinkObj = QuickLinks::getRecordForLogById($id);
                                        $oldRec = $this->recordHistory($quickLinks);
                                        $newRec = $this->newrecordHistory($quickLinks, $newQuickLinkObj);
                                        $logArr['old_val'] = $oldRec;
                                        $logArr['new_val'] = $newRec;
                                    }
                                    $logArr['varTitle'] = stripslashes(trim($postArr['title']));
                                    Log::recordLog($logArr);
                                    if (Auth::user()->can('recent-updates-list')) {
                                        if (!isset($newQuickLinkObj)) {
                                            $newQuickLinkObj = QuickLinks::getRecordForLogById($id);
                                        }
                                        $notificationArr = MyLibrary::notificationData($id, $newQuickLinkObj);
                                        RecentUpdates::setNotification($notificationArr);
                                    }
                                }
                                self::flushCache();
                                if (isset($postArr['saveandexit']) && $postArr['saveandexit'] == 'approvesaveandexit') {
                                    $actionMessage = trans('quick-links::template.common.recordApprovalMessage');
                                } else {
                                    $actionMessage = trans('quick-links::template.quickLinkModule.updateMessage');
                                }
                            }
                        } else {
                            $updateModuleFields = $quicklinksFields;
                            $this->insertApprovedRecord($updateModuleFields, $postArr, $id);
                            if (isset($postArr['saveandexit']) && $postArr['saveandexit'] == 'approvesaveandexit') {
                                $actionMessage = trans('quick-links::template.common.recordApprovalMessage');
                            } else {
                                $actionMessage = trans('quick-links::template.quickLinkModule.updateMessage');
                            }
                        }
                    } else {
                        if ($workFlowByCat->charNeedApproval == 'Y') {
                            $this->insertApprovalRecord($quickLinks, $postArr, $quicklinksFields);
                            if (isset($postArr['saveandexit']) && $postArr['saveandexit'] == 'approvesaveandexit') {
                                $actionMessage = trans('quick-links::template.common.recordApprovalMessage');
                            } else {
                                $actionMessage = trans('quick-links::template.quickLinkModule.updateMessage');
                            }
                        }
                    }
                } else {
                    $update = CommonModel::updateRecords($whereConditions, $quicklinksFields, false, 'Powerpanel\QuickLinks\Models\QuickLinks');
                    $actionMessage = trans('quick-links::template.quickLinkModule.updateMessage');
                }
            } else { #Add post Handler=======
            if (File::exists(app_path() . '/Workflow.php') != null || File::exists(base_path() . '/packages/Powerpanel/Workflow/src/Models/Workflow.php') != null) {
                $workFlowByCat = Workflow::getRecordByCategoryId($module->intFkGroupCode, $currentUserRoleData->id, Config::get('Constant.MODULE.ID'));
            }
                if (!empty($workFlowByCat->varUserId) && $workFlowByCat->chrNeedAddPermission == 'Y' && !$userIsAdmin) {
                    $quicklinksFields['chrPublish'] = 'N';
                    $quicklinksFields['chrDraft'] = 'N';
                    $quicklinksObj = $this->insertNewRecord($postArr, $quicklinksFields);
                    if ($postArr['chrMenuDisplay'] == 'D') {
                        $quicklinksFields['chrDraft'] = 'D';
                    }
                    $quicklinksFields['chrPublish'] = 'Y';
                    $this->insertApprovalRecord($quicklinksObj, $postArr, $quicklinksFields);
                } else {
                    $quicklinksObj = $this->insertNewRecord($postArr, $quicklinksFields);
                }
                if (isset($postArr['saveandexit']) && $postArr['saveandexit'] == 'approvesaveandexit') {
                    $actionMessage = trans('quick-links::template.common.recordApprovalMessage');
                } else {
                    $actionMessage = trans('quick-links::template.quickLinkModule.addedMessage');
                }
                $id = $quicklinksObj->id;
            }
            if ((!empty($postArr['saveandexit']) && $postArr['saveandexit'] == 'saveandexit') || !$userIsAdmin) {
                if ($postArr['chrMenuDisplay'] == 'D') {
                    return redirect()->route('powerpanel.quick-links.index', 'tab=D')->with('message', $actionMessage);
                } else {
                    return redirect()->route('powerpanel.quick-links.index')->with('message', $actionMessage);
                }
            } else {
                return redirect()->route('powerpanel.quick-links.edit', $id)->with('message', $actionMessage);
            }
        } else {
            return Redirect::back()->withErrors($validator)->withInput();
        }
    }

    public function insertApprovedRecord($updateModuleFields, $postArr, $id)
    {
        $whereConditions = ['id' => $postArr['fkMainRecord']];
        $updateModuleFields['chrAddStar'] = 'N';
        $update = CommonModel::updateRecords($whereConditions, $updateModuleFields, false, 'Powerpanel\QuickLinks\Models\QuickLinks');
        if ($update) {
            self::swap_order_edit($postArr['display_order'], $postArr['fkMainRecord']);
        }
        $whereConditions_ApproveN = ['fkMainRecord' => $postArr['fkMainRecord']];
        $updateToApproveN = [
            'chrApproved' => 'N',
            'chrLetest' => 'N',
            'intApprovedBy' => '0',
        ];
        $update = CommonModel::updateRecords($whereConditions_ApproveN, $updateToApproveN, false, 'Powerpanel\QuickLinks\Models\QuickLinks');
        $whereConditionsApprove = ['id' => $id, 'chrMain' => 'N'];
        $updateToApprove = [
            'chrApproved' => 'Y',
            'chrRollBack' => 'Y',
            'intApprovedBy' => auth()->user()->id,
        ];
        $update = CommonModel::updateRecords($whereConditionsApprove, $updateToApprove, false, 'Powerpanel\QuickLinks\Models\QuickLinks');
        if ($postArr['chrMenuDisplay'] == 'D') {
            $addlog = Config::get('Constant.DRAFT_RECORD_APPROVED');
        } else {
            $addlog = Config::get('Constant.RECORD_APPROVED');
        }
        $newBannerObj = QuickLinks::getRecordForLogById($id);
        $logArr = MyLibrary::logData($id, false, $addlog);
        $logArr['varTitle'] = stripslashes($newBannerObj->varTitle);
        Log::recordLog($logArr);
        if (method_exists($this->MyLibrary, 'userNotificationData')) {
            /* notification for user to record approved */
            $userNotificationArr = MyLibrary::userNotificationData(Config::get('Constant.MODULE.ID'));
            $userNotificationArr['fkRecordId'] = $id;
            $userNotificationArr['txtNotification'] = 'Your request has been approved by ' . ucfirst(auth()->user()->name) . ' (' . ucfirst(Config::get('Constant.MODULE.NAME')) . ')';
            $userNotificationArr['fkIntUserId'] = Auth::user()->id;
            $userNotificationArr['chrNotificationType'] = 'A';
            $userNotificationArr['intOnlyForUserId'] = $newBannerObj->UserID;
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
                $actionMessage = trans('quick-links::template.quickLinkModule.updateMessage');
            }
        }
    }

    public function insertApprovalRecord($moduleObj, $postArr, $quicklinksFields)
    {
        #Add post Handler=======
        $quicklinksFields['chrMain'] = 'N';
        $quicklinksFields['chrLetest'] = 'Y';
        $quicklinksFields['UserID'] = auth()->user()->id;
        $quicklinksFields['fkMainRecord'] = $moduleObj->id;
        if ($postArr['chrMenuDisplay'] == 'D') {
            $quicklinksFields['chrDraft'] = 'D';
            $quicklinksFields['chrPublish'] = 'N';
        } else {
            $quicklinksFields['chrDraft'] = 'N';
            $quicklinksFields['chrPublish'] = $postArr['chrMenuDisplay'];
        }
        if (Config::get('Constant.CHRSearchRank') == 'Y') {
            $quicklinksFields['intSearchRank'] = $postArr['search_rank'];
        }

        $quicklinksFields['dtDateTime'] = !empty($postArr['start_date_time']) ? date('Y-m-d H:i:s', strtotime(str_replace('/', '-',$postArr['start_date_time']))) : date('Y-m-d H:i:s');
        $quicklinksFields['dtEndDateTime'] = !empty($postArr['end_date_time']) ? date('Y-m-d H:i:s', strtotime(str_replace('/', '-',$postArr['end_date_time']))) : null;

        $quicklinksFields['intDisplayOrder'] = $postArr['display_order'];
          $quicklinksFields['varSector'] = $postArr['sector'];
        if ($postArr['chrMenuDisplay'] == 'D') {
            $addlog = Config::get('Constant.DRAFT_SENT_FOR_APPROVAL');
        } else {
            $addlog = Config::get('Constant.SENT_FOR_APPROVAL');
        }
        $quicklinkID = CommonModel::addRecord($quicklinksFields, 'Powerpanel\QuickLinks\Models\QuickLinks');
        if (!empty($quicklinkID)) {
            $id = $quicklinkID;
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
            $newQuickLinkObj = QuickLinks::getRecordForLogById($id);
            $logArr = MyLibrary::logData($id, false, $addlog);
            $logArr['varTitle'] = $newQuickLinkObj->varTitle;
            Log::recordLog($logArr);
            if (Auth::user()->can('recent-updates-list')) {
                $notificationArr = MyLibrary::notificationData($id, $newQuickLinkObj);
                RecentUpdates::setNotification($notificationArr);
            }
            self::flushCache();
            $actionMessage = trans('quick-links::template.quickLinkModule.addedMessage');
            $whereConditionsAddstar = ['id' => $moduleObj->id];
            $updateAddStar = [
                'chrAddStar' => 'Y',
            ];
            CommonModel::updateRecords($whereConditionsAddstar, $updateAddStar, false, 'Powerpanel\QuickLinks\Models\QuickLinks');
        }
    }

    public function insertNewRecord($postArr, $quicklinksFields)
    {
        $response = false;
        $quicklinksFields['chrMain'] = 'Y';
        $quicklinksFields['UserID'] = auth()->user()->id;
        if (Config::get('Constant.CHRSearchRank') == 'Y') {
            $quicklinksFields['intSearchRank'] = $postArr['search_rank'];
        }
        $quicklinksFields['varSector'] = $postArr['sector'];
        $quicklinksFields['dtDateTime'] = !empty($postArr['start_date_time']) ? date('Y-m-d H:i:s', strtotime(str_replace('/', '-',$postArr['start_date_time']))) : date('Y-m-d H:i:s');
        $quicklinksFields['dtEndDateTime'] = !empty($postArr['end_date_time']) ? date('Y-m-d H:i:s', strtotime(str_replace('/', '-',$postArr['end_date_time']))) : null;

        if ($postArr['chrMenuDisplay'] == 'D') {
            $quicklinksFields['chrDraft'] = 'D';
            $quicklinksFields['chrPublish'] = 'N';
        } else {
            $quicklinksFields['chrDraft'] = 'N';
        }
        $quicklinksFields['intDisplayOrder'] = self::swap_order_add($postArr['display_order']);
        $quicklinkID = CommonModel::addRecord($quicklinksFields, 'Powerpanel\QuickLinks\Models\QuickLinks');

        if (!empty($quicklinkID)) {
            $id = $quicklinkID;
            $newQuickLinkObj = QuickLinks::getRecordForLogById($id);
            $logArr = MyLibrary::logData($id);
            $logArr['varTitle'] = $newQuickLinkObj->varTitle;
            Log::recordLog($logArr);
            if (Auth::user()->can('recent-updates-list')) {
                $notificationArr = MyLibrary::notificationData($id, $newQuickLinkObj);
                RecentUpdates::setNotification($notificationArr);
            }
            $response = $newQuickLinkObj;
            self::flushCache();
            $actionMessage = trans('quick-links::template.quickLinkModule.addedMessage');
        }
        return $response;
    }

    /**
     * This method destroys Banner in multiples
     * @return  Banner index view
     * @since   2016-10-25
     * @author  NetQuick
     */
    public function DeleteRecord(Request $request)
    {
        $value = Request::input('value');
        $data['ids'] = Request::input('ids');
        $moduleHaveFields = ['chrMain'];
        $update = MyLibrary::deleteMultipleRecords($data, $moduleHaveFields, $value, 'Powerpanel\QuickLinks\Models\QuickLinks');
        if (File::exists(app_path() . '/Comments.php') != null || File::exists(base_path() . '/packages/Powerpanel/Workflow/src/Models/Comments.php') != null) {
            Comments::deleteComments($data['ids'], Config::get('Constant.MODULE.MODEL_NAME'));
        }
        foreach ($update as $ids) {
            $ignoreDeleteScope = true;
            $Deleted_Record = QuickLinks::getRecordById($ids, $ignoreDeleteScope);
            $Cnt_Letest = QuickLinks::getRecordCount_letest($Deleted_Record['fkMainRecord'], $Deleted_Record['id']);
            if ($Cnt_Letest <= 0) {
                $updateLetest = [
                    'chrAddStar' => 'N',
                ];
                $whereConditionsApprove = ['id' => $Deleted_Record['fkMainRecord']];
                CommonModel::updateRecords($whereConditionsApprove, $updateLetest, false, 'Powerpanel\QuickLinks\Models\QuickLinks');
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
        $alias = (int) Request::input('alias');
        $update = MyLibrary::setPublishUnpublish($alias, $val, 'Powerpanel\QuickLinks\Models\QuickLinks');
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
        MyLibrary::swapOrder($order, $exOrder, 'Powerpanel\QuickLinks\Models\QuickLinks');
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
        $moduleHaveFields = ['chrMain'];
        if ($order != null) {
            $response = MyLibrary::swapOrderAdd($order, $isCustomizeModule, $moduleHaveFields, 'Powerpanel\QuickLinks\Models\QuickLinks');
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
       $moduleHaveFields = ['chrMain'];
        MyLibrary::swapOrderEdit($order, $id, $isCustomizeModule, $moduleHaveFields, 'Powerpanel\QuickLinks\Models\QuickLinks');
        self::flushCache();
    }

    /**
     * This method handels getting category and it's records (ajax)
     * @return      JSON object
     * @since       2016-12-23
     * @author      NetQuick
     */
    public static function selectRecords()
    {
        $data = Request::input();
        $module = $data['module'];
        $selected = $data['selected'];
//        $model = '\\App\\' . $data['model'];
        $module = Modules::getModule($module);
        if ($module->varModuleNameSpace != '') {
            $model = $module->varModuleNameSpace . 'Models\\' . $data['model'];
        } else {
            $model = '\\App\\' . $data['model'];
        }
        $recordSelect = '<option value=" ">--' . trans('quick-links::template.quickLinkModule.selectPage') . '--</option>';
//        if ($module->varModuleName == "pages") {
        //            $moduleNamesForAvoidFrontSinglePage = MyLibrary::getModuleNamesNotDisplayInfront();
        //            $ignoreModulesPagedata = Modules::getModuleIdsByNames($moduleNamesForAvoidFrontSinglePage);
        //            $ignoreModulePageIds = array();
        //            if (!empty($ignoreModulesPagedata)) {
        //                $ignoreModulePageIds = array_column($ignoreModulesPagedata, 'id');
        //            }
        //            $moduleRec = $model::getPagesWithModuleForLinks($ignoreModulePageIds);
        //            foreach ($moduleRec as $record) {
        //                if (strtolower($record->varTitle) != 'home') {
        //                    if (Auth::user()->can($record->modules->varModuleName . '-list')) {
        //                        $recordSelect .= '<option data-moduleid="' . $module->id . '" value="' . $record->id . '" ' . ($record->id == $selected ? 'selected' : '') . '>' . ucwords($record->varTitle) . '</option>';
        //                    }
        //                }
        //            }
        //        } else {
        if (\Schema::hasColumn($module->varTableName, 'intDisplayOrder')) {
            $filterArray = [];
            $filterArray['orderByFieldName'] = 'intDisplayOrder';
            $filterArray['orderTypeAscOrDesc'] = 'asc';
            $moduleRec = $model::getRecordList($filterArray);
        } else {
            if (isset($module->id) && $module->id != 3) {

                $moduleRec = $model::getRecordList(false, false, false);
            } else {
                $moduleRec = $model::getRecordListforinternaldropdown(false, false, false);
            }
//                $moduleRec = $model::getRecordList();
        }
        $parentcategorymodles = array();
        if (in_array($data['module'], $parentcategorymodles)) {
            $recordSelect .= ParentRecordHierarchy_builder::Hierarchy_OnlyOptionsForQlinks($moduleRec, $module, $selected);
        } else {
            foreach ($moduleRec as $record) {
                $sector = '';
                if ($record->varSector != 'ofreg' && !empty($record->varSector)) {
                    $sector = $record->varSector;
                }
                if (strtolower($record->varTitle) != 'home' &&  $record->chrPublish == 'Y') {
                    $recordSelect .= '<option data-moduleid="' . $module->id . '" value="' . $record->id . '" ' . ($record->id == $selected ? 'selected' : '') . '>' . ucwords($record->varTitle) . ($module->id == '3' && !empty($sector) ? ' (' . strtoupper($sector) . ')' : '') . '</option>';
                }
            }
        }
//        }
        return $recordSelect;
    }

    public function tableData($value, $totalRecord = false, $tableSortedType = 'asc')
    {
        $actions = '';
        $banner_type = '';
        $checkbox = '';
        $publish_action = '';
        $sector = '';
        if (isset($value->varSector) && !empty($value->varSector)) {
            $sector = strtoupper($value->varSector);
        }
        if (Auth::user()->can('quick-links-edit')) {
            $actions .= '<a class="" title="' . trans("quick-links::template.common.edit") . '" href="' . route('powerpanel.quick-links.edit', array('alias' => $value->id)) . '">
				<span><i class="ri-pencil-line"></i></a>';
        }
        if (Auth::user()->can('quick-links-delete') || (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y')) {
            if (Config::get('Constant.DEFAULT_TRASH') == 'Y') {
                $actions .= '<a title = "' . trans('quick-links::template.common.delete') . '" class="delete-grid" onclick = \'Trashfun("' . $value->id . '")\' data-controller = "quickLinks" data-alias = "' . $value->id . '" data-tab = "P"><i class = "ri-delete-bin-line"></i></a>';
            } else {
                $actions .= '<a class = "delete" title = "' . trans('quick-links::template.common.delete') . '" data-controller = "quickLinks" data-alias = "' . $value->id . '" data-tab = "P"><i class = "ri-delete-bin-line"></i></a>';
            }
        }
        $checkbox = view('powerpanel.partials.checkbox', ['name'=>'delete', 'value'=>$value->id])->render();
        if ($value->chrAddStar != 'Y') {
            if ($value->chrDraft != 'D') {
                if (Auth::user()->can('quick-links-publish')) {
                    if ($value->chrPublish == 'Y') {
                        //Bootstrap Switch
                        $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/quick-links', 'data_alias'=>$value->id, 'title'=>trans("quick-links::template.common.publishedRecord"), 'data_value'=>'Unpublish'])->render();
                    } else {
                        //Bootstrap Switch
                        $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/quick-links', 'data_alias'=>$value->id, 'title'=>trans("quick-links::template.common.unpublishedRecord"), 'data_value'=>'Publish', 'checked'=>'checked'])->render();
                    }
                }
            } else {
                //Bootstrap Switch
                $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/quick-links', 'data_alias'=>$value->id, 'title'=>trans("quick-links::template.common.unpublishedRecord"), 'data_value'=>'Publish', 'checked'=>'checked'])->render();
            }
        } else {
            $publish_action .= '---';
        }
        if ($value->varLinkType == 'internal') {
            $link_type = 'Internal';
        } else {
            $link_type = 'External';
        }
        $otherDetail = self::getInternalLinkHtml($value);
        $startDate = date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($value->dtDateTime));
        $endDate = !empty($value->dtEndDateTime) ? date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($value->dtEndDateTime)) : 'No Expiry';
        $Quickedit_startDate = date('Y-m-d H:i', strtotime($value->dtDateTime));
        $Quickedit_endDate = !empty($value->dtEndDateTime) ? date('Y-m-d H:i', strtotime($value->dtEndDateTime)) : 'No Expiry';
        $title = $value->varTitle;
        if (Auth::user()->can('quick-links-edit')) {
            if ($value->chrLock != 'Y') {
                if (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y') {
                    $title = '<div class="quick_edit"><a href = "' . route('powerpanel.quick-links.edit', array('alias' => $value->id)) . '?tab=P">' . $value->varTitle . '</a> <div class="quick_edit_menu">
														<span><a href="' . route('powerpanel.quick-links.edit', array('alias' => $value->id)) . '?tab=P" title="Edit">Edit</a></span>';
                    if (Config::get('Constant.DEFAULT_QUICK') == 'Y') {
                        $title .= '<span><a title="Quick Edit" href=\'javascript:;\' data-toggle=\'modal\' data-target=\'#modalForm\' aria-label=\'Quick edit\' onclick=\'Quickeditfun("' . $value->id . '","' . $value->varTitle . '","' . $value->intSearchRank . '","' . $Quickedit_startDate . '","' . $Quickedit_endDate . '","P")\'>Quick Edit</a></span>';
                    }
                    if (Config::get('Constant.DEFAULT_TRASH') == 'Y') {
                        $title .= '<span><a title = "Trash" href = \'javascript:;\' onclick=\'Trashfun("' . $value->id . '")\' class="red" data-tab="P">Trash</a></span>';
                    }
                    $title .= '</div>
											 </div>';
                } else {
                    $title = '<div class="quick_edit"><a href = "' . route('powerpanel.quick-links.edit', array('alias' => $value->id)) . '?tab=P">' . $value->varTitle . '</a> <div class="quick_edit_menu">
														<span><a href="' . route('powerpanel.quick-links.edit', array('alias' => $value->id)) . '?tab=P" title="Edit">Edit</a></span>
																</div></div>';
                }
            } else {
                if (auth()->user()->id != $value->LockUserID) {
                    if (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y') {
                        $title = '<div class="quick_edit"><a href = "' . route('powerpanel.quick-links.edit', array('alias' => $value->id)) . '?tab=P">' . $value->varTitle . '</a> <div class="quick_edit_menu">
                            <span><a href="' . route('powerpanel.quick-links.edit', array('alias' => $value->id)) . '?tab=P" title="Edit">Edit</a></span>
                                </div>
                       </div>';
                    } else {
                        $title = '<div class="quick_edit"><a href = "javascript:;">' . $value->varTitle . '</a></div>';
                    }
                } else {
                    $title = '<div class="quick_edit"><a href = "' . route('powerpanel.quick-links.edit', array('alias' => $value->id)) . '?tab=P">' . $value->varTitle . '</a> <div class="quick_edit_menu">
                            <span><a href="' . route('powerpanel.quick-links.edit', array('alias' => $value->id)) . '?tab=P" title="Edit">Edit</a></span>
                                </div>
                       </div>';
                }
            }
        }
        $otherDetailsBox = '<div class="pro-act-btn">
					<a href="javascript:void(0)" class="" onclick="return hs.htmlExpand(this,{width:300,headingText:\'' . 'Links' . '\',wrapperClassName:\'titlebar\',showCredits:false});"><span aria-hidden="true" class="ri-external-link-line"></span></a>
						<div class="highslide-maincontent">' . $otherDetail . '</div>
					</div>';
        if (Auth::user()->can('quick-links-reviewchanges') && (File::exists(app_path() . '/Workflow.php') != null || File::exists(base_path() . '/packages/Powerpanel/Workflow/src/Models/Workflow.php') != null)) {
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
        $First_td = '<div class="star_box star_box_auto">' . $Favorite . '</div>';
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
                if (isset($this->currentUserRoleData->chrIsAdmin) && (auth()->user()->id == $value->LockUserID) || $this->currentUserRoleData->chrIsAdmin == 'Y') {
                    $publish_action = $publish_action;
                } else {
                    $publish_action = "---";
                }
            }
        }

        if(File::exists(base_path() . '/packages/Powerpanel/Workflow/src/Models/Workflow.php')) {
            if($this->currentUserRoleData->chrIsAdmin == 'Y' && count($value->child) > 1) {
                $log .= "<a title='Rollback to previous version'  onclick=\"rollbackToPreviousVersion('" . $value->id . "');\"  class=\"log-grid\"><i class=\"ri-history-line\"></i></a>";
            }
        }

        $records = array(
            $checkbox,
            $First_td,
            '<div class="pages_title_div_row">' . $update . $rollback . $title . ' ' . $status . $statusdata . ' ' . $sector . '</div>',
            $link_type,
            $otherDetailsBox,
            $startDate,
            $endDate,
            $orderArrow,
            $publish_action,
            $log,
            $value->intDisplayOrder,
        );
        return $records;
    }

    public function tableDataFavorite($value, $totalRecord = false, $tableSortedType = 'asc')
    {
        $actions = '';
        $banner_type = '';
        $checkbox = '';
        $publish_action = '';
        $sector = '';
        if (isset($value->varSector) && !empty($value->varSector)) {
            $sector = strtoupper($value->varSector);
        }
        if (Auth::user()->can('quick-links-edit')) {
            $actions .= '<a class="" title="' . trans("quick-links::template.common.edit") . '" href="' . route('powerpanel.quick-links.edit', array('alias' => $value->id)) . '">
				<span><i class="ri-pencil-line"></i></a>';
        }
        if (Auth::user()->can('quick-links-delete') && $this->currentUserRoleData->chrIsAdmin == 'Y') {
            if (Config::get('Constant.DEFAULT_TRASH') == 'Y') {
                $actions .= '<a title = "' . trans('quick-links::template.common.delete') . '" class="delete-grid" onclick = \'Trashfun("' . $value->id . '")\' data-controller = "quickLinks" data-alias = "' . $value->id . '" data-tab = "F"><i class = "ri-delete-bin-line"></i></a>';
            } else {
                $actions .= '<a class = "delete" title = "' . trans('quick-links::template.common.delete') . '" data-controller = "quickLinks" data-alias = "' . $value->id . '" data-tab = "F"><i class = "ri-delete-bin-line"></i></a>';
            }
        }
        $checkbox = view('powerpanel.partials.checkbox', ['name'=>'delete', 'value'=>$value->id])->render();
        if ($value->varLinkType == 'internal') {
            $link_type = 'Internal';
        } else {
            $link_type = 'External';
        }
        $otherDetail = self::getInternalLinkHtml($value);
        $startDate = date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($value->dtDateTime));
        $endDate = !empty($value->dtEndDateTime) ? date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($value->dtEndDateTime)) : 'No Expiry';
        $Quickedit_startDate = date('Y-m-d H:i', strtotime($value->dtDateTime));
        $Quickedit_endDate = !empty($value->dtEndDateTime) ? date('Y-m-d H:i', strtotime($value->dtEndDateTime)) : 'No Expiry';
        $title = $value->varTitle;
        if (Auth::user()->can('quick-links-edit')) {
            if ($value->chrLock != 'Y') {
                if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                    $title = '<div class="quick_edit"><a href = "' . route('powerpanel.quick-links.edit', array('alias' => $value->id)) . '?tab=F">' . $value->varTitle . '</a> <div class="quick_edit_menu">
														<span><a href="' . route('powerpanel.quick-links.edit', array('alias' => $value->id)) . '?tab=F" title="Edit">Edit</a></span>';
                    if (Config::get('Constant.DEFAULT_TRASH') == 'Y') {
                        $title .= '<span><a title = "Trash" href = \'javascript:;\' onclick=\'Trashfun("' . $value->id . '")\' class="red" data-tab="F">Trash</a></span>';
                    }
                    $title .= '</div>
											 </div>';
                } else {
                    $title = '<div class="quick_edit"><a href = "' . route('powerpanel.quick-links.edit', array('alias' => $value->id)) . '?tab=F">' . $value->varTitle . '</a> <div class="quick_edit_menu">
														<span><a href="' . route('powerpanel.quick-links.edit', array('alias' => $value->id)) . '?tab=F" title="Edit">Edit</a></span>
																</div>
											 </div>';
                }
            } else {
                if (auth()->user()->id != $value->LockUserID) {
                    if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                        $title = '<div class="quick_edit"><a href = "' . route('powerpanel.quick-links.edit', array('alias' => $value->id)) . '?tab=F">' . $value->varTitle . '</a> <div class="quick_edit_menu">
	                            <span><a href="' . route('powerpanel.quick-links.edit', array('alias' => $value->id)) . '?tab=F" title="Edit">Edit</a></span>
	                                </div>
	                        </div>';
                    } else {
                        $title = '<div class="quick_edit"><a href = "javascript:;">' . $value->varTitle . '</a></div>';
                    }
                } else {
                    $title = '<div class="quick_edit"><a href = "' . route('powerpanel.quick-links.edit', array('alias' => $value->id)) . '?tab=F">' . $value->varTitle . '</a> <div class="quick_edit_menu">
	                            <span><a href="' . route('powerpanel.quick-links.edit', array('alias' => $value->id)) . '?tab=F" title="Edit">Edit</a></span>
	                                </div>
	                        </div>';
                }
            }
        }
        $otherDetailsBox = '<div class="pro-act-btn">
					<a href="javascript:void(0)" class="" onclick="return hs.htmlExpand(this,{width:300,headingText:\'' . 'Links' . '\',wrapperClassName:\'titlebar\',showCredits:false});"><span aria-hidden="true" class="ri-external-link-line"></span></a>
						<div class="highslide-maincontent">' . $otherDetail . '</div>
					</div>';
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
        $First_td = '<div class="star_box star_box_auto">' . $Favorite . '</div>';
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
        
        $records = array(
            $checkbox,
            $First_td,
            '<div class="pages_title_div_row">' . $title . ' ' . $status . $statusdata . ' ' . $sector . '</div>',
            $link_type,
            $otherDetailsBox,
            $startDate,
            $endDate,
            $log,
            $value->intDisplayOrder,
        );
        return $records;
    }

    public function tableDataDraft($value, $totalRecord = false, $tableSortedType = 'asc')
    {
        $actions = '';
        $banner_type = '';
        $checkbox = '';
        $publish_action = '';
        $sector = '';
        if (isset($value->varSector) && !empty($value->varSector)) {
            $sector = strtoupper($value->varSector);
        }
        if (Auth::user()->can('quick-links-edit')) {
            $actions .= '<a class="" title="' . trans("quick-links::template.common.edit") . '" href="' . route('powerpanel.quick-links.edit', array('alias' => $value->id)) . '">
				<span><i class="ri-pencil-line"></i></a>';
        }
        if (Auth::user()->can('quick-links-delete') && $this->currentUserRoleData->chrIsAdmin == 'Y') {
            if (Config::get('Constant.DEFAULT_TRASH') == 'Y') {
                $actions .= '<a title = "' . trans('quick-links::template.common.delete') . '" class="delete-grid" onclick = \'Trashfun("' . $value->id . '")\' data-controller = "quickLinks" data-alias = "' . $value->id . '" data-tab = "D"><i class = "ri-delete-bin-line"></i></a>';
            } else {
                $actions .= '<a class = "delete" title = "' . trans('quick-links::template.common.delete') . '" data-controller = "quickLinks" data-alias = "' . $value->id . '" data-tab = "D"><i class = "ri-delete-bin-line"></i></a>';
            }
        }
        $checkbox = view('powerpanel.partials.checkbox', ['name'=>'delete', 'value'=>$value->id])->render();
        
        //Bootstrap Switch
        $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/quick-links', 'data_alias'=>$value->id, 'title'=>trans("quick-links::template.common.unpublishedRecord"), 'data_value'=>'Publish', 'checked'=>'checked'])->render();

        if ($value->varLinkType == 'internal') {
            $link_type = 'Internal';
        } else {
            $link_type = 'External';
        }
        $otherDetail = self::getInternalLinkHtml($value);
        $startDate = date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($value->dtDateTime));
        $endDate = !empty($value->dtEndDateTime) ? date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($value->dtEndDateTime)) : 'No Expiry';
        $Quickedit_startDate = date('Y-m-d H:i', strtotime($value->dtDateTime));
        $Quickedit_endDate = !empty($value->dtEndDateTime) ? date('Y-m-d H:i', strtotime($value->dtEndDateTime)) : 'No Expiry';
        $title = $value->varTitle;
        if (Auth::user()->can('quick-links-edit')) {
            if ($value->chrLock != 'Y') {
                if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                    $title = '<div class="quick_edit"><a href = "' . route('powerpanel.quick-links.edit', array('alias' => $value->id)) . '?tab=D">' . $value->varTitle . '</a> <div class="quick_edit_menu">
														<span><a href="' . route('powerpanel.quick-links.edit', array('alias' => $value->id)) . '?tab=D" title="Edit">Edit</a></span>';
                    if (Config::get('Constant.DEFAULT_TRASH') == 'Y') {
                        $title .= '<span><a title = "Trash" href = \'javascript:;\' onclick=\'Trashfun("' . $value->id . '")\' class="red" data-tab="D">Trash</a></span>';
                    }
                    $title .= '</div></div>';
                } else {
                    $title = '<div class="quick_edit"><a href = "' . route('powerpanel.quick-links.edit', array('alias' => $value->id)) . '?tab=D">' . $value->varTitle . '</a> <div class="quick_edit_menu">
														<span><a href="' . route('powerpanel.quick-links.edit', array('alias' => $value->id)) . '?tab=D" title="Edit">Edit</a></span>
																</div>
											 </div>';
                }
            } else {
                if (auth()->user()->id != $value->LockUserID) {
                    if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                        $title = '<div class="quick_edit"><a href = "' . route('powerpanel.quick-links.edit', array('alias' => $value->id)) . '?tab=D">' . $value->varTitle . '</a> <div class="quick_edit_menu">
	                            <span><a href="' . route('powerpanel.quick-links.edit', array('alias' => $value->id)) . '?tab=D" title="Edit">Edit</a></span></div></div>';
                    } else {
                        $title = '<div class="quick_edit"><a href = "javascript:;">' . $value->varTitle . '</a></div>';
                    }
                } else {
                    $title = '<div class="quick_edit"><a href = "' . route('powerpanel.quick-links.edit', array('alias' => $value->id)) . '?tab=D">' . $value->varTitle . '</a> <div class="quick_edit_menu">
	                            <span><a href="' . route('powerpanel.quick-links.edit', array('alias' => $value->id)) . '?tab=D" title="Edit">Edit</a></span>
	                                </div>
	                        </div>';
                }
            }
        }
        $otherDetailsBox = '<div class="pro-act-btn">
					<a href="javascript:void(0)" class="" onclick="return hs.htmlExpand(this,{width:300,headingText:\'' . 'Links' . '\',wrapperClassName:\'titlebar\',showCredits:false});"><span aria-hidden="true" class="ri-external-link-line"></span></a>
						<div class="highslide-maincontent">' . $otherDetail . '</div>
					</div>';
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
        if (Config::get('Constant.DEFAULT_FAVORITE') == 'Y') {
            $Favorite_array = explode(",", $value->FavoriteID);
            if (in_array(auth()->user()->id, $Favorite_array)) {
                $Class = 'ri-star-fill';
                $Favorite = '<a class="star_icon_div" href="javascript:;" onclick="GetFavorite(' . $value->id . ',\'N\',\'D\')"><i class="' . $Class . '"></i></a>';
            } else {
                $Class = 'ri-star-line';
                $Favorite = '<a class="star_icon_div" href="javascript:;" onclick="GetFavorite(' . $value->id . ',\'Y\',\'D\')"><i class="' . $Class . '"></i></a>';
            }
        } else {
            $Favorite = '';
        }
        $First_td = '<div class="star_box star_box_auto">' . $Favorite . '</div>';
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
        $records = array(
            $checkbox,
            '<div class="pages_title_div_row"><input type="hidden" id="draftid" value="' . $value->id . '">' . $title . ' ' . $status . $statusdata . ' ' . $sector . '</div>',
            $link_type,
            $otherDetailsBox,
            $startDate,
            $endDate,
            $publish_action,
            $log,
            $value->intDisplayOrder,
        );
        return $records;
    }

    public function tableDataTrash($value, $totalRecord = false, $tableSortedType = 'asc')
    {
        $actions = '';
        $banner_type = '';
        $checkbox = '';
        $sector = '';
        if (isset($value->varSector) && !empty($value->varSector)) {
            $sector = strtoupper($value->varSector);
        }
        if (Auth::user()->can('quick-links-delete') && $this->currentUserRoleData->chrIsAdmin == 'Y') {
            $actions .= '<a class=" delete" title="Delete" data-controller="quickLinks" data-alias = "' . $value->id . '" data-tab="T"><i class="ri-delete-bin-line"></i></a>';
        }
        $checkbox = view('powerpanel.partials.checkbox', ['name'=>'delete', 'value'=>$value->id])->render();
        if ($value->varLinkType == 'internal') {
            $link_type = 'Internal';
        } else {
            $link_type = 'External';
        }
        $otherDetail = self::getInternalLinkHtml($value);
        $startDate = date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($value->dtDateTime));
        $endDate = !empty($value->dtEndDateTime) ? date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($value->dtEndDateTime)) : 'No Expiry';
        $title = $value->varTitle;
        if (Auth::user()->can('quick-links-edit')) {
            $title = '<div class="quick_edit text-uppercase"><a href = "' . route('powerpanel.quick-links.edit', array('alias' => $value->id)) . '?tab=T">' . $value->varTitle . '</a>
												</div>';
        }
        $otherDetailsBox = '<div class="pro-act-btn">
					<a href="javascript:void(0)" class="" onclick="return hs.htmlExpand(this,{width:300,headingText:\'' . 'Links' . '\',wrapperClassName:\'titlebar\',showCredits:false});"><span aria-hidden="true" class="ri-external-link-line"></span></a>
						<div class="highslide-maincontent">' . $otherDetail . '</div>
					</div>';
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
        if (Config::get('Constant.DEFAULT_FAVORITE') == 'Y') {
            $Favorite_array = explode(",", $value->FavoriteID);
            if (in_array(auth()->user()->id, $Favorite_array)) {
                $Class = 'ri-star-fill';
                $Favorite = '<a class="star_icon_div" href="javascript:;" onclick="GetFavorite(' . $value->id . ',\'N\',\'T\')"><i class="' . $Class . '"></i></a>';
            } else {
                $Class = 'ri-star-line';
                $Favorite = '<a class="star_icon_div" href="javascript:;" onclick="GetFavorite(' . $value->id . ',\'Y\',\'T\')"><i class="' . $Class . '"></i></a>';
            }
        } else {
            $Favorite = '';
        }
        $First_td = '<div class="star_box star_box_auto">' . $Favorite . '</div>';
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
        $records = array(
            $checkbox,
            '<div class="pages_title_div_row"><input type="hidden" id="draftid" value="' . $value->id . '">' . $title . ' '. $sector . '</div>',
            $link_type,
            $otherDetailsBox,
            $startDate,
            $endDate,
            $log,
            $value->intDisplayOrder,
        );
        return $records;
    }

    public function tableDataWaitingToApprovedData($value)
    {
        $actions = '';
        $banner_type = '';
        $checkbox = '';
        $publish_action = '';
        $sector = '';
        if (isset($value->varSector) && !empty($value->varSector)) {
            $sector = strtoupper($value->varSector);
        }
        if (Auth::user()->can('quick-links-edit')) {
            $actions .= '<a class="" title="' . trans("quick-links::template.common.edit") . '" href="' . route('powerpanel.quick-links.edit', array('alias' => $value->id)) . '">
				<span><i class="ri-pencil-line"></i></a>';
        }
        if (Auth::user()->can('quick-links-delete') || (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y')) {
            if (Config::get('Constant.DEFAULT_TRASH') == 'Y') {
                $actions .= '<a title = "' . trans('quick-links::template.common.delete') . '" class="delete-grid" onclick = \'Trashfun("' . $value->id . '")\' data-controller = "quickLinks" data-alias = "' . $value->id . '" data-tab = "A"><i class = "ri-delete-bin-line"></i></a>';
            } else {
                $actions .= '<a class = "delete" title = "' . trans('quick-links::template.common.delete') . '" data-controller = "quickLinks" data-alias = "' . $value->id . '" data-tab = "A"><i class = "ri-delete-bin-line"></i></a>';
            }
        }
        $checkbox = view('powerpanel.partials.checkbox', ['name'=>'delete', 'value'=>$value->id])->render();

        if ($value->varLinkType == 'internal') {
            $link_type = 'Internal';
        } else {
            $link_type = 'External';
        }
        $otherDetail = self::getInternalLinkHtml($value);
        $otherDetailsBox = '<div class="pro-act-btn">
					<a href="javascript:void(0)" class="" onclick="return hs.htmlExpand(this,{width:300,headingText:\'' . 'Details' . '\',wrapperClassName:\'titlebar\',showCredits:false});"><span aria-hidden="true" class="ri-external-link-line"></span></a>
						<div class="highslide-maincontent">' . $otherDetail . '</div>
					</div>';
        if (Auth::user()->can('quick-links-reviewchanges')) {
            $update = "<a title=\"Click here to see all approval records.\" class=\"icon_title1\" style=\"margin-right: 5px;\" onclick=\"javascript:expandcollapsepanel(this ,'tasklisting" . $value->id . "', 'mainsingnimg" . $value->id . "'," . $value->id . ")\"><i id=\"mainsingnimg" . $value->id . "\" class=\"ri-add-box-line\"></i></a>";
            $rollback = "<a title=\"Click here to see all approved records to rollback.\" class=\"icon_title2\" style=\"margin-right: 5px;\" onclick=\"javascript:expandcollapsepanel_rolback(this ,'tasklisting_rollback" . $value->id . "', 'mainsingnimg_rollback" . $value->id . "'," . $value->id . ")\"><i id=\"mainsingnimg_rollback" . $value->id . "\" class=\"ri-history-line\"></i></a>";
        } else {
            $update = '';
            $rollback = '';
        }
        if (Auth::user()->can('quick-links-reviewchanges') && $value->chrAddStar == 'Y') {
            $star = 'addhiglight';
        } else {
            $star = '';
        }
        $startDate = date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($value->dtDateTime));
        $endDate = !empty($value->dtEndDateTime) ? date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($value->dtEndDateTime)) : 'No Expiry';
        $Quickedit_startDate = date('Y-m-d H:i', strtotime($value->dtDateTime));
        $Quickedit_endDate = !empty($value->dtEndDateTime) ? date('Y-m-d H:i', strtotime($value->dtEndDateTime)) : 'No Expiry';
        $title = $value->varTitle;
        if (Auth::user()->can('quick-links-edit')) {
            if ($value->chrLock != 'Y') {
                $title = '<div class="quick_edit"><a href = "' . route('powerpanel.quick-links.edit', array('alias' => $value->id)) . '?tab=A">' . $value->varTitle . '</a> <div class="quick_edit_menu">
														<span><a href="' . route('powerpanel.quick-links.edit', array('alias' => $value->id)) . '?tab=A" title="Edit">Edit</a></span>';
                if (Config::get('Constant.DEFAULT_TRASH') == 'Y') {
                    $title .= '<span><a title = "Trash" href = \'javascript:;\' onclick=\'Trashfun("' . $value->id . '")\' class="red" data-tab="A">Trash</a></span>';
                }
                $title .= '</div></div>';
            } else {
                if (auth()->user()->id != $value->LockUserID) {
                    if (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y') {
                        $title = '<div class="quick_edit"><a href = "' . route('powerpanel.quick-links.edit', array('alias' => $value->id)) . '?tab=A">' . $value->varTitle . '</a> <div class="quick_edit_menu">
	                            <span><a href="' . route('powerpanel.quick-links.edit', array('alias' => $value->id)) . '?tab=A" title="Edit">Edit</a></span></div></div>';
                    } else {
                        $title = '<div class="quick_edit"><a href = "javascript:;">' . $value->varTitle . '</a></div>';
                    }
                } else {
                    $title = '<div class="quick_edit"><a href = "' . route('powerpanel.quick-links.edit', array('alias' => $value->id)) . '?tab=A">' . $value->varTitle . '</a> <div class="quick_edit_menu">
	                            <span><a href="' . route('powerpanel.quick-links.edit', array('alias' => $value->id)) . '?tab=A" title="Edit">Edit</a></span></div></div>';
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

        $First_td = '<div class="star_box star_box_auto">' . $Favorite . '</div>';
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
                if (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y') {
                    $log .= '<a class="star_lock" onclick="GetUnLockData(' . $value->id . ',' . auth()->user()->id . ',' . Config::get('Constant.MODULE.ID') . ',1)" title="This record has been locked by ' . $lockedUserName . ', Click here to unlock."><i class="ri-lock-2-line"></i></a>';
                } else {

                    $log .= '<a class="star_lock" title="This record has been locked by ' . $lockedUserName . '."><i class="ri-lock-2-line"></i></a>';
                }
            } else {
                $log .= '<a class="star_lock" onclick="GetUnLockData(' . $value->id . ',' . auth()->user()->id . ',' . Config::get('Constant.MODULE.ID') . ',1)" title="Click here to unlock."><i class="ri-lock-2-line"></i></a>';
            }
        }

        if(File::exists(base_path() . '/packages/Powerpanel/Workflow/src/Models/Workflow.php')) {
            if($this->currentUserRoleData->chrIsAdmin == 'Y' && count($value->child) > 1) {
                $log .= "<a title='Rollback to previous version'  onclick=\"rollbackToPreviousVersion('" . $value->id . "');\"  class=\"log-grid\"><i class=\"ri-history-line\"></i></a>";
            }
        }

        $records = array(
            $First_td,
            '<div class="pages_title_div_row">' . $update . $rollback . $title . ' ' . $status . $statusdata . ' ' . $sector . '</div>',
            $link_type,
            $otherDetailsBox,
            $startDate,
            $endDate,
            $log,
            $value->intDisplayOrder,
        );
        return $records;
    }

    /**
     * This method handels logs old records
     * @param      $data
     * @return  order
     * @since   2017-07-21
     * @author  NetQuick
     */
    public function recordHistory($data = false)
    {
        $banner_type = null;
        if ($data->varLinkType == 'internal') {
            $link_type = 'Internal';
        } else {
            $link_type = 'External';
        }
        $pageTitle = 'Default';
        if (strlen($data->fkIntPageId) > 0) {
            if ($data->fkModuleId == 4) {
                $pageDetail = CmsPage::getPageTitleById($data->fkIntPageId);
                $pageTitle = (isset($pageDetail->varTitle)) ? $pageDetail->varTitle : '';
            } else {
                $pageTitle = '';
            }
        }
        $moduledata = Modules::getModuleById($data->fkModuleId);
        if (isset($moduledata->varModelName) && !empty($moduledata->varModelName)) {
            $model = $moduledata->varModuleNameSpace . 'Models\\' . $moduledata->varModelName;
            $moduleRec = $model::getRecordById($data->fkIntPageId);
        } else {
            $moduleRec = '';
        }

        if (isset($moduleRec->varTitle) && !empty($moduleRec->varTitle)) {
            $RecordTitle = $moduleRec->varTitle;
        } else {
            $RecordTitle = '-';
        }
        if (isset($moduledata->varTitle) && !empty($moduledata->varTitle)) {
            $moduleName = $moduledata->varTitle;
        } else {
            $moduleName = '-';
        }
        $startDate = date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($data->dtDateTime));
        $endDate = !empty($data->dtEndDateTime) ? date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($data->dtEndDateTime)) : 'No Expiry';
        $returnHtml = '';
        $returnHtml .= '<table class="new_table_desing table table-striped table-bordered table-hover">
						<thead>
							<tr>
								<th align="center">' . trans("quick-links::template.common.title") . '</th>
								<th align="center">' . trans("quick-links::template.quickLinkModule.linkType") . '</th>';
        if ($link_type == "Internal") {
            $returnHtml .= '<th align="center">Module Name</th>';
            $returnHtml .= '<th align="center">Page Name</th>';
        } else {
            $returnHtml .= '<th align="center">External Link</th>';
        }
        $returnHtml .= '<th align="center">Start Date</th>
								<th align="center">End Date</th>
								<th align="center">' . trans("quick-links::template.common.displayorder") . '</th>
								<th align="center">' . trans("quick-links::template.common.publish") . '</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td align="center">' . stripslashes($data->varTitle) . '</td>
								<td align="center">' . $link_type . '</td>';
        if ($link_type == "Internal") {
            $returnHtml .= '<td align="center">' . $moduleName . '</td>';
            $returnHtml .= '<td align="center">' . $RecordTitle . '</td>';
        } else {
            $returnHtml .= '<td align="center">' . $data->varExtLink . '</td>';
        }
        $returnHtml .= '<td align="center">' . $startDate . '</td>
								<td align="center">' . $endDate . '</td>
																																<td align="center">' . ($data->intDisplayOrder) . '</td>
								<td align="center">' . $data->chrPublish . '</td>
							</tr>
						</tbody>
					</table>';
        return $returnHtml;
    }

    /**
     * This method handels logs old records
     * @param      $data
     * @return  order
     * @since   2017-07-21
     * @author  NetQuick
     */
    public function newrecordHistory($data = false, $newdata = false)
    {
        $banner_type = null;
        if ($newdata->varLinkType == 'internal') {
            $link_type = 'Internal';
        } else {
            $link_type = 'External';
        }
        $pageTitle = 'Default';
        if (strlen($newdata->fkIntPageId) > 0) {
            if ($newdata->fkModuleId == 4) {
                $pageDetail = CmsPage::getPageTitleById($newdata->fkIntPageId);
                $pageTitle = (isset($pageDetail->varTitle)) ? $pageDetail->varTitle : '';
            } else {
                $pageTitle = '';
            }
        }
        if ($data->varTitle != $newdata->varTitle) {
            $titlecolor = 'style="background-color:#f5efb7"';
        } else {
            $titlecolor = '';
        }
        if ($data->varLinkType != $newdata->varLinkType) {
            $linkcolor = 'style="background-color:#f5efb7"';
        } else {
            $linkcolor = '';
        }
        if ($data->fkIntPageId != $newdata->fkIntPageId) {
            $pagecolor = 'style="background-color:#f5efb7"';
        } else {
            $pagecolor = '';
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
        $moduledata = Modules::getModuleById($newdata->fkModuleId);
        if (isset($moduledata->varModelName) && !empty($moduledata->varModelName)) {
            $model = $moduledata->varModuleNameSpace . 'Models\\' . $moduledata->varModelName;
            $moduleRec = $model::getRecordById($newdata->fkIntPageId);
        } else {
            $moduleRec = '';
        }

        if (isset($moduleRec->varTitle) && !empty($moduleRec->varTitle)) {
            $RecordTitle = $moduleRec->varTitle;
        } else {
            $RecordTitle = '-';
        }
        if (isset($moduledata->varTitle) && !empty($moduledata->varTitle)) {
            $moduleName = $moduledata->varTitle;
        } else {
            $moduleName = '-';
        }

        if ($data->fkModuleId != $newdata->fkModuleId) {
            $moduleNamecolor = 'style="background-color:#f5efb7"';
        } else {
            $moduleNamecolor = '';
        }
        if ($data->fkIntPageId != $newdata->fkIntPageId) {
            $RecordTitlecolor = 'style="background-color:#f5efb7"';
        } else {
            $RecordTitlecolor = '';
        }
        if ($data->varExtLink != $newdata->varExtLink) {
            $varExtLinkcolor = 'style="background-color:#f5efb7"';
        } else {
            $varExtLinkcolor = '';
        }
        $startDate = date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($newdata->dtDateTime));
        $endDate = !empty($newdata->dtEndDateTime) ? date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($newdata->dtEndDateTime)) : 'No Expiry';
        $returnHtml = '';
        $returnHtml .= '<table class="new_table_desing table table-striped table-bordered table-hover">
						<thead>
							<tr>
								<th align="center">' . trans("quick-links::template.common.title") . '</th>
								<th align="center">' . trans("quick-links::template.quickLinkModule.linkType") . '</th>';
        if ($link_type == "Internal") {
            $returnHtml .= '<th align="center">Module Name</th>';
            $returnHtml .= '<th align="center">Page Name</th>';
        } else {
            $returnHtml .= '<th align="center">External Link</th>';
        }
        $returnHtml .= '<th align="center">Start Date</th>
								<th align="center">End Date</th>
								<th align="center">' . trans("quick-links::template.common.displayorder") . '</th>
								<th align="center">' . trans("quick-links::template.common.publish") . '</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td align="center" ' . $titlecolor . '>' . stripslashes($newdata->varTitle) . '</td>
								<td align="center" ' . $linkcolor . '>' . $link_type . '</td>';
        if ($link_type == "Internal") {
            $returnHtml .= '<td align="center"  ' . $moduleNamecolor . '>' . $moduleName . '</td>';
            $returnHtml .= '<td align="center"  ' . $RecordTitlecolor . '>' . $RecordTitle . '</td>';
        } else {
            $returnHtml .= '<td align="center"  ' . $varExtLinkcolor . '>' . $newdata->varExtLink . '</td>';
        }
        $returnHtml .= '<td align="center" ' . $sdatecolor . '>' . $startDate . '</td>
								<td align="center" ' . $edatecolor . '>' . $endDate . '</td>
																																<td align="center" ' . $ordercolor . '>' . ($newdata->intDisplayOrder) . '</td>
								<td align="center" ' . $Publishcolor . '>' . $newdata->chrPublish . '</td>
							</tr>
						</tbody>
					</table>';
        return $returnHtml;
    }

    /**
     * This method handle to Approved Data record.
     * @since   26-Sep-2018
     * @author  NetQuick Team
     */
    public function ApprovedData_Listing(Request $request)
    {
        $requestArr = Request::all();
        $request = (object) $requestArr;
        $id = Request::post('id');
        $main_id = Request::post('main_id');
        $approvalid = Request::post('id');
        $flag = Request::post('flag');
        $approvalData = QuickLinks::getOrderOfApproval($id);
        $message = QuickLinks::approved_data_Listing($request);
        if (!empty($approvalData)) {
            self::swap_order_edit($approvalData->intDisplayOrder, $main_id);
        }
        $newCmsPageObj = QuickLinks::getRecordForLogById($main_id);
        $approval_obj = QuickLinks::getRecordForLogById($approvalid);
        if ($flag == 'R') {
            $restoredata = Config::get('Constant.ROLLBACK_RECORD');
        } else {
            if ($approval_obj->chrDraft == 'D') {
                $restoredata = Config::get('Constant.DRAFT_RECORD_APPROVED');
            } else {
                $restoredata = Config::get('Constant.RECORD_APPROVED');
            }
        }
        if (method_exists($this->MyLibrary, 'userNotificationData')) {
            /* notification for user to record approved */
            $userNotificationArr = MyLibrary::userNotificationData(Config::get('Constant.MODULE.ID'));
            $userNotificationArr['fkRecordId'] = $approvalid;
            $userNotificationArr['txtNotification'] = 'Your request has been approved by ' . ucfirst(auth()->user()->name) . ' (' . ucfirst(Config::get('Constant.MODULE.NAME')) . ')';
            $userNotificationArr['fkIntUserId'] = Auth::user()->id;
            $userNotificationArr['chrNotificationType'] = 'A';
            $userNotificationArr['intOnlyForUserId'] = $approval_obj->UserID;
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

    public function getChildData_rollback(Request $request)
    {
        $requestArr = Request::all();
        $request = (object) $requestArr;
        $child_rollbackHtml = "";
        $Notices_rollbackchildData = "";
        $Notices_rollbackchildData = QuickLinks::getChildrollbackGrid($request);
        $child_rollbackHtml .= "<div class=\"producttbl producttb2\" style=\"\">";
        $child_rollbackHtml .= "<table class=\"new_table_desing table table-striped table-bordered table-hover table-checkable dataTable\" id=\"email_log_datatable_ajax\">
																<tr role=\"row\">
																																																																								<th class=\"text-center\">Title</th>
																		<th class=\"text-center\">Date</th>
																		<th class=\"text-center\">User</th>
																		<th class=\"text-center\">Status</th>";
        $child_rollbackHtml .= "         </tr>";
        if (count($Notices_rollbackchildData) > 0) {
            foreach ($Notices_rollbackchildData as $child_rollbacrow) {
                $child_rollbackHtml .= "<tr role=\"row\">";
                $child_rollbackHtml .= '<td class="text-center"><span class="mob_show_title">Title: </span>' . $child_rollbacrow->varTitle . '</td>';
                $child_rollbackHtml .= "<td class=\"text-center\"><span class='mob_show_title'>Date: </span>" . date('M d Y h:i A', strtotime($child_rollbacrow->created_at)) . "</td>";
                $child_rollbackHtml .= "<td class=\"text-center\"><span class='mob_show_title'>User: </span>" . CommonModel::getUserName($child_rollbacrow->UserID) . "</td>";
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

    /**
     * This method handle to get child record.
     * @since   26-Sep-2018
     * @author  NetQuick Team
     */
    public function getChildData()
    {
        $data = Request::input();
        $requestedID = $data['id'];
        $childHtml = "";
        $childData = "";
        $childData = QuickLinks::getChildGrid($requestedID);
        $childHtml .= "<div class=\"producttbl\" style=\"\">";
        $childHtml .= "<table class=\"new_table_desing table table-striped table-bordered table-hover table-checkable dataTable\" id=\"email_log_datatable_ajax\">
																<tr role=\"row\">
																		<th class=\"text-center\"></th>
																																																																								<th class=\"text-center\">Title</th>
																		<th class=\"text-center\">Date Submitted</th>
																		<th class=\"text-center\">User</th>
																		 <th class=\"text-center\">Edit</th>
																		<th class=\"text-center\">Status</th>";
        $childHtml .= "         </tr>";
        if (count($childData) > 0) {
            foreach ($childData as $child_row) {
                $restictMsg = "This is approved record, so can't be deleted.";
                $childHtml .= "<tr role=\"row\">";
                if ($child_row->chrApproved == 'N') {
                    $childHtml .= "<td><span class='mob_show_title'>&nbsp</span><input type=\"checkbox\" name=\"delete\" class=\"chkDelete form-check-input\" value='" . $child_row->id . "'></td>";
                } else {
                    $childHtml .= "<td><span class='mob_show_title'>&nbsp</span><div class=\"checker\"><a href=\"javascript:;\" data-toggle=\"tooltip\" data-placement=\"right\" title=\"$restictMsg\"><i style=\"color:red\" class=\"ri-alert-fill\"></i></a></div></td>";
                }
                $childHtml .= '<td class="text-center"><span class="mob_show_title">Title: </span>' . $child_row->varTitle . '</td>';
                $childHtml .= "<td class=\"text-center\"><span class='mob_show_title'>Date Submitted: </span>" . date('M d Y h:i A', strtotime($child_row->created_at)) . "</td>";
                $childHtml .= "<td class=\"text-center\"><span class='mob_show_title'>User: </span>" . CommonModel::getUserName($child_row->UserID) . "</td>";
                if ($child_row->chrApproved == 'N') {
                    $childHtml .= "<td class=\"text-center\"><span class='mob_show_title'>Edit: </span><a class='icon_round' title='" . trans("quick-links::template.common.edit") . "' href='" . route('powerpanel.quick-links.edit', array('alias' => $child_row->id)) . "'>
							<i class='ri-pencil-line'></i></a></td>";
                } else {
                    $childHtml .= "<td class=\"text-center\"><span class='mob_show_title'>Edit: </span>-</td>";
                }
                if ($child_row->chrApproved == 'N') {
                    $childHtml .= "<td class=\"text-center\"><span class='mob_show_title'>Status: </span><a title='" . trans("quick-links::template.common.comments") . "'   href=\"javascript:;\" onclick=\"loadModelpopup('" . $child_row->id . "','" . $child_row->UserID . "','" . Config::get('Constant.MODULE.MODEL_NAME') . "','" . $child_row->fkMainRecord . "')\" class=\"approve_icon_btn\"><i class=\"ri-chat-1-line\"></i> <span>Comment</span></a>    <a  onclick=\"update_mainrecord('" . $child_row->id . "','" . $child_row->fkMainRecord . "','" . $child_row->UserID . "','A');\" title='" . trans("quick-links::template.common.clickapprove") . "'  href=\"javascript:;\" class=\"approve_icon_btn\"><i class=\"ri-checkbox-line\"></i> <span>Approve</span></a></td>";
                } else {
                    $childHtml .= "<td class=\"text-center\"><span class='mob_show_title'>Status: </span><span class='mob_show_overflow'><i class=\"ri-checkbox-circle-line\" style=\"font-size:30px;\"></i><span style=\"display:block\"><strong>Approved On: </strong>" . date('M d Y h:i A', strtotime($child_row->dtApprovedDateTime)) . "</span><span style=\"display:block\"><strong>Approved By: </strong>" . CommonModel::getUserName($child_row->intApprovedBy) . "</span></span></td>";
                }
                $childHtml .= "</tr>";
            }
        } else {
            $childHtml .= "<tr><td colspan='6'>No Records</td></tr>";
        }
        $childHtml .= "</tr></td></tr>";
        $childHtml .= "</tr>
						</table>";
        echo $childHtml;
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

    public static function getInternalLinkHtml($value)
    {
        $otherDetail = '';
        if ($value->varLinkType == 'internal') {
            $moduleCode = $value->modules->id;
            if ($value->modules->varTitle != 'Pages') {
                $tableName = DB::table('module')->select('varTableName', 'varModelName')->where('id', $value->fkModuleId)->first();
                $pageData = CmsPage::select('varTitle', 'intAliasId', 'alias.varAlias as recordalias')
                    ->join('alias', 'alias.id', '=', 'cms_page.intAliasId')
                    ->where('cms_page.intFKModuleCode', $moduleCode)
                    ->first();

                if (!empty($pageData)) {
                    if (\Schema::hasColumn($tableName->varTableName, 'intAliasId')) {
                        $recordData = DB::table($tableName->varTableName)
                            ->select('varTitle', 'intAliasId', 'alias.varAlias as recordalias')
                            ->join('alias', 'alias.id', '=', $tableName->varTableName . '.intAliasId')
                            ->where($tableName->varTableName . '.id', $value->fkIntPageId);
                        if (\Schema::hasColumn($tableName->varTableName, 'chrMain')) {
                            $recordData = $recordData->where($tableName->varTableName . '.chrMain', 'Y');
                        }
                        if (\Schema::hasColumn($tableName->varTableName, 'chrIsPreview')) {
                            $recordData = $recordData->where($tableName->varTableName . '.chrIsPreview', 'N');
                        }
                        $recordData = $recordData->first();
                        if (isset($recordData->recordalias)) {
                            $recordlink = url($pageData->recordalias . '/' . $recordData->recordalias);
                        } else {
                            $recordlink = url($pageData->recordalias);
                        }
                        $otherDetail = '<a href="' . $recordlink . '" target="_blank">' . $recordlink . '</a>';
                    } else {
                        $otherDetail = '-';
                    }
                } else {
                    $otherDetail = 'No Link Available';
                }
            } else {
                if (\Schema::hasColumn('cms_page', 'chrMain') || \Schema::hasColumn('cms_page', 'chrIsPreview')) {
                    $recordData = CmsPage::select('varTitle', 'intAliasId', 'alias.varAlias as recordalias')
                        ->join('alias', 'alias.id', '=', 'cms_page.intAliasId')
                        ->where('cms_page.id', $value->fkIntPageId)
                        ->where('cms_page.chrMain', 'Y')
                        ->where('cms_page.chrIsPreview', 'N')
                        ->first();
                } else {
                    $recordData = CmsPage::select('varTitle', 'intAliasId', 'alias.varAlias as recordalias')
                        ->join('alias', 'alias.id', '=', 'cms_page.intAliasId')
                        ->where('cms_page.id', $value->fkIntPageId)
                        ->first();
                }
                if (!empty($recordData)) {
                    $recordlink = url($recordData->recordalias);
                    $otherDetail = '<a href="' . $recordlink . '" target="_blank">' . $recordlink . '</a>';
                } else {
                    $otherDetail = 'No Link Available';
                }
            }
        } else {
            $otherDetail = '<a href="' . $value->varExtLink . '" target="_blank">' . $value->varExtLink . '</a>';
        }
        return $otherDetail;
    }

    public static function flushCache()
    {
        Cache::tags('QuickLinks')->flush();
    }

    public function rollBackRecord(Request $request)
    {

        $message = 'Previous record is not available';
        $requestArr = Request::all();
        $request = (object) $requestArr;

        $previousRecord = QuickLinks::getPreviousRecordByMainId($request->id);
        if (!empty($previousRecord)) {

            $main_id = $previousRecord->fkMainRecord;
            $request->id = $previousRecord->id;
            $request->main_id = $main_id;

            $message = QuickLinks::approved_data_Listing($request);


            /* notification for user to record approved */
            $blogs = QuickLinks::getRecordForLogById($previousRecord->id);
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
            $newBlogObj = QuickLinks::getRecordForLogById($main_id);
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
