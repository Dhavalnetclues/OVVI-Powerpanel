<?php

namespace Powerpanel\CmsPage\Controllers\Powerpanel;

use App\Alias;
use App\CommonModel;
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
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Powerpanel\CmsPage\Models\CmsPage;
use Powerpanel\Menu\Models\Menu;
use Powerpanel\RoleManager\Models\Role_user;
use Powerpanel\Workflow\Models\Comments;
use Powerpanel\Workflow\Models\Workflow;
use Powerpanel\Workflow\Models\WorkflowLog;
use Validator;
use App\Helpers\Email_sender;

class CmsPagesController extends PowerpanelController
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
        $this->Alias = new Alias();
    }


    public function index()
    {

        $userIsAdmin = false;
        if (isset($this->currentUserRoleData) && !empty($this->currentUserRoleData)) {
            if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                $userIsAdmin = true;
            }
        }
        $iTotalRecords = CmsPage::getRecordCount(false, false, $userIsAdmin, $this->currentUserRoleSector);
        $draftTotalRecords = CmsPage::getRecordCountforListDarft(false, true, $userIsAdmin, array(), $this->currentUserRoleSector);
        $trashTotalRecords = CmsPage::getRecordCountforListTrash(false, false, $userIsAdmin, [], $this->currentUserRoleSector);
        $favoriteTotalRecords = CmsPage::getRecordCountforListFavorite(false, false, $userIsAdmin, [], $this->currentUserRoleSector);
        $approvalTotalRecords = CmsPage::getRecordCountListApprovalTab(false, false, $userIsAdmin, array(), $this->currentUserRoleSector);
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
        $this->breadcrumb['title'] = trans('cmspage::template.pageModule.manage');
        return view('cmspage::powerpanel.list', ['iTotalRecords' => $iTotalRecords, 'breadcrumb' => $this->breadcrumb, 'approvalTotalRecords' => $approvalTotalRecords, 'userIsAdmin' => $userIsAdmin, 'draftTotalRecords' => $draftTotalRecords, 'trashTotalRecords' => $trashTotalRecords, 'favoriteTotalRecords' => $favoriteTotalRecords, 'settingarray' => $settingarray]);
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
        $filterArr['sectorFilter'] = !empty(Request::input('sectorValue')) ? Request::input('sectorValue') : '';
        $filterArr['searchFilter'] = !empty(Request::input('searchValue')) ? Request::input('searchValue') : '';
        $filterArr['customFilterIdentity'] = !empty(Request::get('customFilterIdentity')) ? Request::get('customFilterIdentity') : '';
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

        $igonresModulesforShare = Modules::getModuleDataByNames(['']);
        $igonresModulesIds = array();
        if (!empty($igonresModulesforShare)) {
            foreach ($igonresModulesforShare as $ignoreModule) {
                $igonresModulesIds[] = $ignoreModule->id;
            }
        }

        $ignoreId = [];
        $arrResults = CmsPage::getRecordList($filterArr, $isAdmin, $ignoreId, $this->currentUserRoleSector);
        $iTotalRecords = CmsPage::getRecordCountforList($filterArr, true, $isAdmin, $ignoreId, $this->currentUserRoleSector);
        $end = $iDisplayStart + $iDisplayLength;
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;

        if (!empty($arrResults)) {
            $permit = [
                'canpagesedit' => Auth::user()->can('pages-edit'),
                'canpagespublish' => Auth::user()->can('pages-publish'),
                'canpagesdelete' => Auth::user()->can('pages-delete'),
                'canpagesreviewchanges' => Auth::user()->can('pages-reviewchanges'),
                'canpagesedit' => Auth::user()->can('pages-edit'),
                'canloglist' => Auth::user()->can('log-list'),
                'canloglist' => Auth::user()->can('log-list')
            ];

			$currentUserID = auth()->user()->id;
            foreach ($arrResults as $key => $value) {
                if (!in_array($value->id, $ignoreId)) {
                    $records['data'][] = $this->tableData($value, $igonresModulesIds,$permit,$currentUserID);
                }
            }
        }

        if (!empty(Request::input('customActionType')) && Request::input('customActionType') == 'group_action') {
            $records['customActionStatus'] = 'OK';
        }

        $NewRecordsCount = Cmspage::getNewRecordsCount($isAdmin, $this->currentUserRoleSector);
        $records['newRecordCount'] = $NewRecordsCount;
        $records['draw'] = $sEcho;
        $records['recordsTotal'] = $iTotalRecords;
        $records['recordsFiltered'] = $iTotalRecords;
        echo json_encode($records);
        exit;
    }

    public function get_list_New()
    {
        $filterArr = [];
        $records = [];
        $records['data'] = [];
        $filterArr['orderColumnNo'] = (!empty(Request::input('order')[0]['column']) ? Request::input('order')[0]['column'] : '');
        $filterArr['orderByFieldName'] = (!empty(Request::input('columns')[$filterArr['orderColumnNo']]['name']) ? Request::input('columns')[$filterArr['orderColumnNo']]['name'] : '');
        $filterArr['orderTypeAscOrDesc'] = (!empty(Request::input('order')[0]['dir']) ? Request::input('order')[0]['dir'] : '');
        $filterArr['statusFilter'] = !empty(Request::input('customActionName')) ? Request::input('customActionName') : '';
        $filterArr['sectorFilter'] = !empty(Request::input('sectorValue')) ? Request::input('sectorValue') : '';
        $filterArr['searchFilter'] = !empty(Request::input('searchValue')) ? Request::input('searchValue') : '';
        $filterArr['customFilterIdentity'] = !empty(Request::get('customFilterIdentity')) ? Request::get('customFilterIdentity') : '';
        $filterArr['iDisplayLength'] = intval(Request::input('length'));
        $filterArr['iDisplayStart'] = intval(Request::input('start'));
        $iDisplayLength = intval(Request::input('length'));
        $iDisplayStart = intval(Request::input('start'));
        $sEcho = intval(Request::input('draw'));

        $userIsAdmin = false;
        if (!empty($this->currentUserRoleData)) {
            if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                $userIsAdmin = true;
            }
        }

        $ignoreId = [];
        $arrResults = CmsPage::getRecordList_tab1($filterArr, $userIsAdmin, $this->currentUserRoleSector);
        $iTotalRecords = CmsPage::getRecordCountListApprovalTab($filterArr, true, $userIsAdmin, $ignoreId, $this->currentUserRoleSector);
        $end = $iDisplayStart + $iDisplayLength;
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;

        $igonresModulesforShare = Modules::getModuleDataByNames(['publications-category']);
        $igonresModulesIds = array();
        if (!empty($igonresModulesforShare)) {
            foreach ($igonresModulesforShare as $ignoreModule) {
                $igonresModulesIds[] = $ignoreModule->id;
            }
        }

        if (!empty($arrResults)) {
            $currentUserID = auth()->user()->id;
            $permit = [
                'canpagesedit' => Auth::user()->can('pages-edit'),
                'canpagespublish' => Auth::user()->can('pages-publish'),
                'canpagesdelete' => Auth::user()->can('pages-delete'),
                'canpagesreviewchanges' => Auth::user()->can('pages-reviewchanges'),
                'canpagesedit' => Auth::user()->can('pages-edit'),
                'canloglist' => Auth::user()->can('log-list'),
                'canloglist' => Auth::user()->can('log-list')
            ];

            foreach ($arrResults as $key => $value) {
                if (!in_array($value->id, $ignoreId)) {
                    $records['data'][] = $this->tableData_tab1($value, $igonresModulesIds,$permit,$currentUserID);
                }
            }
        }

        if (!empty(Request::input('customActionType')) && Request::input('customActionType') == 'group_action') {
            $records['customActionStatus'] = 'OK';
        }
        $NewRecordsCount = Cmspage::getNewRecordsCount($userIsAdmin, $this->currentUserRoleSector);
        $records['newRecordCount'] = $NewRecordsCount;
        $records['draw'] = $sEcho;
        $records['recordsTotal'] = $iTotalRecords;
        $records['recordsFiltered'] = $iTotalRecords;
        echo json_encode($records);
        exit;
    }

    public function get_list_draft()
    {
        $filterArr = [];
        $records = [];
        $records['data'] = [];
        $filterArr['orderColumnNo'] = (!empty(Request::input('order')[0]['column']) ? Request::input('order')[0]['column'] : '');
        $filterArr['orderByFieldName'] = (!empty(Request::input('columns')[$filterArr['orderColumnNo']]['name']) ? Request::input('columns')[$filterArr['orderColumnNo']]['name'] : '');
        $filterArr['orderTypeAscOrDesc'] = (!empty(Request::input('order')[0]['dir']) ? Request::input('order')[0]['dir'] : '');
        $filterArr['statusFilter'] = !empty(Request::input('customActionName')) ? Request::input('customActionName') : '';
        $filterArr['sectorFilter'] = !empty(Request::input('sectorValue')) ? Request::input('sectorValue') : '';
        $filterArr['searchFilter'] = !empty(Request::input('searchValue')) ? Request::input('searchValue') : '';
        $filterArr['customFilterIdentity'] = !empty(Request::get('customFilterIdentity')) ? Request::get('customFilterIdentity') : '';
        $filterArr['iDisplayLength'] = intval(Request::input('length'));
        $filterArr['iDisplayStart'] = intval(Request::input('start'));
        $iDisplayLength = intval(Request::input('length'));
        $iDisplayStart = intval(Request::input('start'));
        $sEcho = intval(Request::input('draw'));

        $isAdmin = false;
        if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
            $isAdmin = true;
        }

        $igonresModulesforShare = Modules::getModuleDataByNames(['publications-category']);
        $igonresModulesIds = array();
        if (!empty($igonresModulesforShare)) {
            foreach ($igonresModulesforShare as $ignoreModule) {
                $igonresModulesIds[] = $ignoreModule->id;
            }
        }

        $ignoreId = [];
        $arrResults = CmsPage::getRecordListDraft($filterArr, $isAdmin, $ignoreId, $this->currentUserRoleSector);
        $iTotalRecords = CmsPage::getRecordCountforListDarft($filterArr, true, $isAdmin, [], $this->currentUserRoleSector);
        $end = $iDisplayStart + $iDisplayLength;
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;

        if (!empty($arrResults)) {
            $currentUserID = auth()->user()->id;
            $permit = [
                'canpagesedit' => Auth::user()->can('pages-edit'),
                'canpagespublish' => Auth::user()->can('pages-publish'),
                'canpagesdelete' => Auth::user()->can('pages-delete'),
                'canpagesreviewchanges' => Auth::user()->can('pages-reviewchanges'),
                'canpagesedit' => Auth::user()->can('pages-edit'),
                'canloglist' => Auth::user()->can('log-list'),
                'canloglist' => Auth::user()->can('log-list')
            ];

            foreach ($arrResults as $key => $value) {
                if (!in_array($value->id, $ignoreId)) {
                    $records['data'][] = $this->tableDataDraft($value, $igonresModulesIds,$permit,$currentUserID);
                }
            }
        }

        if (!empty(Request::input('customActionType')) && Request::input('customActionType') == 'group_action') {
            $records['customActionStatus'] = 'OK';
        }

        $NewRecordsCount = Cmspage::getNewRecordsCount($isAdmin, $this->currentUserRoleSector);
        $records['newRecordCount'] = $NewRecordsCount;
        $records['draw'] = $sEcho;
        $records['recordsTotal'] = $iTotalRecords;
        $records['recordsFiltered'] = $iTotalRecords;
        echo json_encode($records);
        exit;
    }

    public function get_list_trash()
    {
        $filterArr = [];
        $records = [];
        $records['data'] = [];
        $filterArr['orderColumnNo'] = (!empty(Request::input('order')[0]['column']) ? Request::input('order')[0]['column'] : '');
        $filterArr['orderByFieldName'] = (!empty(Request::input('columns')[$filterArr['orderColumnNo']]['name']) ? Request::input('columns')[$filterArr['orderColumnNo']]['name'] : '');
        $filterArr['orderTypeAscOrDesc'] = (!empty(Request::input('order')[0]['dir']) ? Request::input('order')[0]['dir'] : '');
        $filterArr['statusFilter'] = !empty(Request::input('customActionName')) ? Request::input('customActionName') : '';
        $filterArr['sectorFilter'] = !empty(Request::input('sectorValue')) ? Request::input('sectorValue') : '';
        $filterArr['searchFilter'] = !empty(Request::input('searchValue')) ? Request::input('searchValue') : '';
        $filterArr['customFilterIdentity'] = !empty(Request::get('customFilterIdentity')) ? Request::get('customFilterIdentity') : '';
        $filterArr['iDisplayLength'] = intval(Request::input('length'));
        $filterArr['iDisplayStart'] = intval(Request::input('start'));
        $iDisplayLength = intval(Request::input('length'));
        $iDisplayStart = intval(Request::input('start'));
        $sEcho = intval(Request::input('draw'));

        $isAdmin = false;
        if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
            $isAdmin = true;
        }

        $igonresModulesforShare = Modules::getModuleDataByNames(['publications-category']);
        $igonresModulesIds = array();
        if (!empty($igonresModulesforShare)) {
            foreach ($igonresModulesforShare as $ignoreModule) {
                $igonresModulesIds[] = $ignoreModule->id;
            }
        }

        $ignoreId = [];
        $arrResults = CmsPage::getRecordListTrash($filterArr, $isAdmin, $ignoreId);
        $iTotalRecords = CmsPage::getRecordCountforListTrash($filterArr, true, $isAdmin, $ignoreId, $this->currentUserRoleSector);
        $end = $iDisplayStart + $iDisplayLength;
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;

        if (!empty($arrResults)) {
            $currentUserID = auth()->user()->id;
            $permit = [
                'canpagesedit' => Auth::user()->can('pages-edit'),
                'canpagespublish' => Auth::user()->can('pages-publish'),
                'canpagesdelete' => Auth::user()->can('pages-delete'),
                'canpagesreviewchanges' => Auth::user()->can('pages-reviewchanges'),
                'canpagesedit' => Auth::user()->can('pages-edit'),
                'canloglist' => Auth::user()->can('log-list'),
                'canloglist' => Auth::user()->can('log-list')
            ];

            foreach ($arrResults as $key => $value) {
                if (!in_array($value->id, $ignoreId)) {
                    $records['data'][] = $this->tableDataTrash($value, $igonresModulesIds,$permit,$currentUserID);
                }
            }
        }

        if (!empty(Request::input('customActionType')) && Request::input('customActionType') == 'group_action') {
            $records['customActionStatus'] = 'OK';
        }

        $NewRecordsCount = Cmspage::getNewRecordsCount($isAdmin, $this->currentUserRoleSector);
        $records['newRecordCount'] = $NewRecordsCount;
        $records['draw'] = $sEcho;
        $records['recordsTotal'] = $iTotalRecords;
        $records['recordsFiltered'] = $iTotalRecords;
        echo json_encode($records);
        exit;
    }

    public function get_list_favorite()
    {
        $filterArr = [];
        $records = [];
        $records['data'] = [];
        $filterArr['orderColumnNo'] = (!empty(Request::input('order')[0]['column']) ? Request::input('order')[0]['column'] : '');
        $filterArr['orderByFieldName'] = (!empty(Request::input('columns')[$filterArr['orderColumnNo']]['name']) ? Request::input('columns')[$filterArr['orderColumnNo']]['name'] : '');
        $filterArr['orderTypeAscOrDesc'] = (!empty(Request::input('order')[0]['dir']) ? Request::input('order')[0]['dir'] : '');
        $filterArr['statusFilter'] = !empty(Request::input('customActionName')) ? Request::input('customActionName') : '';
        $filterArr['sectorFilter'] = !empty(Request::input('sectorValue')) ? Request::input('sectorValue') : '';
        $filterArr['searchFilter'] = !empty(Request::input('searchValue')) ? Request::input('searchValue') : '';
        $filterArr['customFilterIdentity'] = !empty(Request::get('customFilterIdentity')) ? Request::get('customFilterIdentity') : '';
        $filterArr['iDisplayLength'] = intval(Request::input('length'));
        $filterArr['iDisplayStart'] = intval(Request::input('start'));
        $iDisplayLength = intval(Request::input('length'));
        $iDisplayStart = intval(Request::input('start'));
        $sEcho = intval(Request::input('draw'));

        $isAdmin = false;
        if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
            $isAdmin = true;
        }

        $igonresModulesforShare = Modules::getModuleDataByNames(['publications-category']);
        $igonresModulesIds = array();
        if (!empty($igonresModulesforShare)) {
            foreach ($igonresModulesforShare as $ignoreModule) {
                $igonresModulesIds[] = $ignoreModule->id;
            }
        }

        $ignoreId = [];
        $arrResults = CmsPage::getRecordListFavorite($filterArr, $isAdmin, $ignoreId, $this->currentUserRoleSector);
        $iTotalRecords = CmsPage::getRecordCountforListFavorite($filterArr, true, $isAdmin, [], $this->currentUserRoleSector);
        $end = $iDisplayStart + $iDisplayLength;
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;

        if (!empty($arrResults)) {
            $currentUserID = auth()->user()->id;
            $permit = [
                'canpagesedit' => Auth::user()->can('pages-edit'),
                'canpagespublish' => Auth::user()->can('pages-publish'),
                'canpagesdelete' => Auth::user()->can('pages-delete'),
                'canpagesreviewchanges' => Auth::user()->can('pages-reviewchanges'),
                'canpagesedit' => Auth::user()->can('pages-edit'),
                'canloglist' => Auth::user()->can('log-list'),
                'canloglist' => Auth::user()->can('log-list')
            ];

            foreach ($arrResults as $key => $value) {
                if (!in_array($value->id, $ignoreId)) {
                    $records['data'][] = $this->tableDataFavorite($value, $igonresModulesIds,$permit,$currentUserID);
                }
            }
        }

        if (!empty(Request::input('customActionType')) && Request::input('customActionType') == 'group_action') {
            $records['customActionStatus'] = 'OK';
        }

        $NewRecordsCount = Cmspage::getNewRecordsCount($isAdmin, $this->currentUserRoleSector);
        $records['newRecordCount'] = $NewRecordsCount;
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
        if (isset($this->currentUserRoleData) && !empty($this->currentUserRoleData)) {
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
        $modules = Modules::getFrontCmsModulesList($ignoreModulesIds);
        $templateData = array();
        if (is_numeric($id) && !empty($id)) {
            $countmenu = Menu::getMenuCmsById($id);
            $Cmspage = CmsPage::getRecordById($id);
            if (empty($Cmspage)) {
                return redirect()->route('powerpanel.pages.add');
            }
            if ($Cmspage->fkMainRecord != '0') {
                $Cmspage_highLight = CmsPage::getRecordById($Cmspage->fkMainRecord);
                $templateData['Cmspage_highLight'] = $Cmspage_highLight;
                $metaInfo_highLight['varMetaTitle'] = $Cmspage_highLight['varMetaTitle'];
                $metaInfo_highLight['varMetaDescription'] = $Cmspage_highLight['varMetaDescription'];
                $metaInfo_highLight['varTags'] = $Cmspage_highLight['varTags'];
            } else {
                $templateData['Cmspage_highLight'] = '';
                $metaInfo_highLight['varMetaTitle'] = '';
                $metaInfo_highLight['varMetaDescription'] = '';
                $metaInfo_highLight['varTags'] = '';
            }
            $templateData['Cmspage'] = $Cmspage;
            $templateData['countmenu'] = $countmenu;
            
            $metaInfo['varURL']  = '';
            if(!empty($Cmspage)) {
                if (!empty($Cmspage['varSector']) && $Cmspage['varSector'] != 'ofreg') {
                    $metaInfo['varURL'] = $Cmspage['varSector'] . '/' . $Cmspage['alias']['varAlias'];
                } else {
                    $metaInfo['varURL'] = !empty($Cmspage['alias']['varAlias'])?$Cmspage['alias']['varAlias']:'';
                }

                if (!empty($Cmspage['intAliasId'])) {
                    $metaInfo['privateLink'] = Mylibrary::getEncryptedString($Cmspage['intAliasId']);
                    $metaInfo['chrPageActive'] = $Cmspage['chrPageActive'];
                }
            }
            
            $metaInfo['varMetaTitle'] = $Cmspage['varMetaTitle'];
            $metaInfo['varMetaDescription'] = $Cmspage['varMetaDescription'];
            $metaInfo['varTags'] = $Cmspage['varTags'];
            $this->breadcrumb['title'] = trans('cmspage::template.pageModule.edit');
            $this->breadcrumb['inner_title'] = $Cmspage->varTitle;
            if (isset($Cmspage->alias->varAlias) && $Cmspage->alias->varAlias != 'home') {
                $templateData['publishActionDisplay'] = true;
            }
        } else {
            $this->breadcrumb['title'] = trans('cmspage::template.pageModule.add');
            $this->breadcrumb['inner_title'] = "";
            $templateData['publishActionDisplay'] = true;
        }
        $templateData['userIsAdmin'] = $userIsAdmin;
        $this->breadcrumb['module'] = trans('cmspage::template.pageModule.manage');
        $this->breadcrumb['url'] = 'powerpanel/pages';
        $templateData['modules'] = $modules;
        $templateData['breadcrumb'] = $this->breadcrumb;
        $templateData['metaInfo'] = (!empty($metaInfo) ? $metaInfo : '');
        $templateData['metaInfo_highLight'] = (!empty($metaInfo_highLight) ? $metaInfo_highLight : '');
        $templateData['imageManager'] = $imageManager;

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
        $templateData['MyLibrary'] = $this->MyLibrary;
        $templateData['pageId'] = $id;

        return view('cmspage::powerpanel.actions', $templateData);
    }

    public function handlePost(Request $request, Guard $auth)
    {
        $approval = false;
        $userIsAdmin = false;
        if (isset($this->currentUserRoleData) && !empty($this->currentUserRoleData)) {
            if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                $userIsAdmin = true;
            }
        }
        $data = Request::input();
        $actionMessage = trans('cmspage::template.common.oppsSomethingWrong');
        $rules = array(
            'title' => 'required|max:160|handle_xss|no_url',
            'sector' => 'required',
            'module' => 'required',
            'varMetaTitle' => 'required|max:500|handle_xss|no_url',
            'varMetaDescription' => 'required|max:500|handle_xss|no_url',
            'chrMenuDisplay' => 'required',
            'alias' => 'required',
        );
        $messsages = array(
            'varMetaTitle.required' => trans('cmspage::template.pageModule.metaTitle'),
            'sector.required' => 'Sector field is required',
            'title.required' => trans('cmspage::template.pageModule.title'),
            'varMetaDescription.required' => trans('cmspage::template.pageModule.metaDescription'),
        );
        
        $validator = Validator::make($data, $rules, $messsages);
        if ($validator->passes()) {
            $module = Modules::getModuleById(Config::get('Constant.MODULE.ID'));
            $moduleCode = $data['module'];
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
            $cmsPageArr['varTitle'] = stripslashes(trim($data['title']));
            $cmsPageArr['intFKModuleCode'] = $moduleCode;
            $cmsPageArr['txtDescription'] = $vsection;
            $cmsPageArr['varMetaTitle'] = stripslashes(trim($data['varMetaTitle']));
            $cmsPageArr['varMetaDescription'] = stripslashes(trim($data['varMetaDescription']));
            $cmsPageArr['varTags'] = trim($data['tags']);
            if (Config::get('Constant.CHRContentScheduling') == 'Y') {
                $cmsPageArr['dtDateTime'] = date('Y-m-d H:i:s', strtotime(str_replace('/', '-',$data['start_date_time'])));
                $cmsPageArr['dtEndDateTime'] = (isset($data['end_date_time']) && $data['end_date_time'] != "") ? date('Y-m-d H:i:s', strtotime(str_replace('/', '-',$data['end_date_time']))) : null;
            }
            $cmsPageArr['UserID'] = auth()->user()->id;
            if ($data['chrMenuDisplay'] == 'D') {
                $cmsPageArr['chrDraft'] = 'D';
                $cmsPageArr['chrPublish'] = 'N';
            } else {
                $cmsPageArr['chrDraft'] = 'N';
                $cmsPageArr['chrPublish'] = $data['chrMenuDisplay'];
            }
            if (Config::get('Constant.CHRSearchRank') == 'Y') {
                $cmsPageArr['intSearchRank'] = $data['search_rank'];
            }
            if (isset($data['chrPageActive']) && $data['chrPageActive'] != '') {
                $cmsPageArr['chrPageActive'] = $data['chrPageActive'];
            }
            // if (isset($data['chrPageActive']) && $data['chrPageActive'] == 'PP') {
            //     $cmsPageArr['varPassword'] = $data['new_password'];
            // } else {
            //     $cmsPageArr['varPassword'] = '';
            // }
            if ($data['chrMenuDisplay'] == 'D') {
                $addlog = Config::get('Constant.UPDATE_DRAFT');
            } else {
                $addlog = '';
            }
            $id = Request::segment(3);
            if (is_numeric($id) && !empty($id)) {
                //Edit post Handler=======
                $cmsPage = CmsPage::getRecordForLogById($id);
                $cmsPageArr['varSector'] = $data['sector'];
                if ($cmsPage->chrLock == 'Y' && auth()->user()->id != $cmsPage->LockUserID) {
                    if ($this->currentUserRoleData->chrIsAdmin != 'Y') {
                        $lockedUserData = User::getRecordById($cmsPage->LockUserID, true);
                        $lockedUserName = 'someone';
                        if (!empty($lockedUserData)) {
                            $lockedUserName = $lockedUserData->name;
                        }
                        $actionMessage = "This record has been locked by " . $lockedUserName . ".";
                        return redirect()->route('powerpanel.pages.index')->with('message', $actionMessage);
                    }
                }
                if (File::exists(app_path() . '/Workflow.php') != null || File::exists(base_path() . '/packages/Powerpanel/Workflow/src/Models/Workflow.php') != null) {
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
                    $workFlowByCat = Workflow::getRecordByCategoryId($module->intFkGroupCode, $userRole, Config::get('Constant.MODULE.ID'));
                    Alias::updateCmsPageAlias($cmsPage->intAliasId, $data['alias'], $data['sector']);
                    if ($data['chrMenuDisplay'] == 'D') {
                        // Menu::deletePermenentMenuRecord($id, Config::get('Constant.MODULE.ID'));
                        //DB::table('menu')->where('intPageId', $id)->where('intfkModuleId', Config::get('Constant.MODULE.ID'))->delete();
                    }
                    $whereConditions = ['id' => $cmsPage->id];
                    if (empty($workFlowByCat->varUserId) || $userIsAdmin || $workFlowByCat->charNeedApproval == 'N') {
                        if ($cmsPage->fkMainRecord == '0' || empty($workFlowByCat->varUserId)) {
                            $update = CommonModel::updateRecords($whereConditions, $cmsPageArr, false, 'Powerpanel\CmsPage\Models\CmsPage');
                            if ($update) {
                                $newCmsPageObj = CmsPage::getRecordForLogById($cmsPage->id);
                                //Update record in menu
                                $whereConditions = ['txtPageUrl' => $data['oldAlias']];
                                $updateMenuFields = [
                                    'varTitle' => $newCmsPageObj->varTitle,
                                    'txtPageUrl' => $newCmsPageObj->alias->varAlias,
                                    'chrPublish' => $data['chrMenuDisplay'],
                                    'chrActive' => $data['chrMenuDisplay'],
                                ];
                                //Update record in menu
                                $logArr = MyLibrary::logData($cmsPage->id, false, $addlog);
                                if (Auth::user()->can('log-advanced')) {
                                    $oldRec = $this->recordHistory($cmsPage);
                                    $newRec = $this->newrecordHistory($cmsPage, $newCmsPageObj);
                                    $logArr['old_val'] = $oldRec;
                                    $logArr['new_val'] = $newRec;
                                }
                                $logArr['varTitle'] = $newCmsPageObj->varTitle;
                                Log::recordLog($logArr);
                                if (Auth::user()->can('recent-updates-list')) {
                                    $notificationArr = MyLibrary::notificationData($cmsPage->id, $newCmsPageObj);
                                    RecentUpdates::setNotification($notificationArr);
                                }
                                self::flushCache();
                                if (isset($data['saveandexit']) && $data['saveandexit'] == 'approvesaveandexit') {
                                    $actionMessage = trans('cmspage::template.pageModule.pageApprovalUpdate');
                                } else {
                                    $actionMessage = trans('cmspage::template.pageModule.pageUpdate');
                                }
                            }
                        } else {
                            $newCmsPageObj = CmsPage::getRecordForLogById($cmsPage->id);
                            //Update record in menu
                            $whereConditions = ['txtPageUrl' => $data['oldAlias']];
                            $updateMenuFields = [
                                'varTitle' => $newCmsPageObj->varTitle,
                                'txtPageUrl' => $newCmsPageObj->alias->varAlias,
                                'chrPublish' => $data['chrMenuDisplay'],
                                'chrActive' => $data['chrMenuDisplay'],
                            ];
                            //Update record in menu
                            $updateModuleFields = $cmsPageArr;
                            $this->insertApprovedRecord($updateModuleFields, $data, $id);
                            if (isset($data['saveandexit']) && $data['saveandexit'] == 'approvesaveandexit') {
                                $actionMessage = trans('cmspage::template.pageModule.pageApprovalUpdate');
                            } else {
                                $actionMessage = trans('cmspage::template.pageModule.pageUpdate');
                            }
                            $approval = $id;
                        }
                    } else {
                        if ($workFlowByCat->charNeedApproval == 'Y') {
                            $postArr = $data;
                            $approvalObj = $this->insertApprovalRecord($cmsPage, $postArr, $cmsPageArr);
                            if (isset($data['saveandexit']) && $data['saveandexit'] == 'approvesaveandexit') {
                                $actionMessage = trans('cmspage::template.pageModule.pageApprovalUpdate');
                            } else {
                                $actionMessage = trans('cmspage::template.pageModule.pageUpdate');
                            }
                            $approval = $approvalObj->id;
                        }
                    }
                } else {
                    $whereConditions = ['id' => $cmsPage->id];
                    $update = CommonModel::updateRecords($whereConditions, $cmsPageArr, false, 'Powerpanel\CmsPage\Models\CmsPage');
                    $actionMessage = trans('cmspage::template.pageModule.pageUpdate');
                }

                if (method_exists($this->Alias, 'updatePreviewAlias')) {
                    Alias::updateCmsPreviewAlias($cmsPage->intAliasId, 'N');
                }

            } else {
                $postArr = $data;
                if (File::exists(app_path() . '/Workflow.php') != null || File::exists(base_path() . '/packages/Powerpanel/Workflow/src/Models/Workflow.php') != null) {
                    $workFlowByCat = Workflow::getRecordByCategoryId($module->intFkGroupCode, $this->currentUserRoleData->id, Config::get('Constant.MODULE.ID'));
                }
                if (!empty($workFlowByCat->varUserId) && $workFlowByCat->chrNeedAddPermission == 'Y' && !$userIsAdmin) {
                    if ($data['chrPageActive'] == 'PR') {
                        $cmsPageArr['chrPublish'] = 'Y';
                    } else {
                        $cmsPageArr['chrPublish'] = 'N';
                    }
                    $cmsPageArr['chrDraft'] = 'N';
                    $cmsPage = $this->insertNewRecord($postArr, $cmsPageArr);
                    if ($data['chrMenuDisplay'] == 'D') {
                        $cmsPageArr['chrDraft'] = 'D';
                    }
                    $cmsPageArr['chrPublish'] = 'Y';
                    $approvalObj = $this->insertApprovalRecord($cmsPage, $postArr, $cmsPageArr);
                    $approval = $cmsPage->id;
                } else {
                    $cmsPage = $this->insertNewRecord($postArr, $cmsPageArr);
                    $approval = $cmsPage->id;
                }
                if (isset($data['saveandexit']) && $data['saveandexit'] == 'approvesaveandexit') {
                    $actionMessage = trans('cmspage::template.pageModule.addapprovalMessage');
                } else {
                    $actionMessage = trans('cmspage::template.pageModule.addMessage');
                }
                $id = $cmsPage->id;
            }

            if ((!empty(Request::get('saveandexit')) && Request::get('saveandexit') == 'saveandexit') || !$userIsAdmin) {
                if ($data['chrMenuDisplay'] == 'D') {
                    return redirect()->route('powerpanel.pages.index', 'tab=D')->with('message', $actionMessage);
                } else {
                    return redirect()->route('powerpanel.pages.index')->with('message', $actionMessage);
                }
            } elseif ((!empty(Request::get('saveandmenu')) && Request::get('saveandmenu') == 'saveandmenu')) {
                return redirect('powerpanel/menu?pageId=' . $id);
            } else {
                return redirect()->route('powerpanel.pages.edit', $id)->with('message', $actionMessage);
            }
        } else {
            return Redirect::back()->withErrors($validator)->withInput();
        }
    }

    public function insertApprovedRecord($updateModuleFields, $postArr, $id)
    {
        $whereConditions = ['id' => $postArr['fkMainRecord']];
        $updateModuleFields['chrAddStar'] = 'N';
        $update = CommonModel::updateRecords($whereConditions, $updateModuleFields, false, 'Powerpanel\CmsPage\Models\CmsPage');
        $whereConditions_ApproveN = ['fkMainRecord' => $postArr['fkMainRecord']];
        $updateToApproveN = [
            'chrApproved' => 'N',
            'chrLetest' => 'N',
            'intApprovedBy' => '0',
        ];
        CommonModel::updateRecords($whereConditions_ApproveN, $updateToApproveN, false, 'Powerpanel\CmsPage\Models\CmsPage');
        $whereConditionsApprove = ['id' => $id, 'chrMain' => 'N'];
        $updateToApprove = [
            'chrApproved' => 'Y',
            'chrRollBack' => 'Y',
            'intApprovedBy' => auth()->user()->id,
            'dtApprovedDateTime' => date('Y-m-d H:i:s')
        ];
        CommonModel::updateRecords($whereConditionsApprove, $updateToApprove, false, 'Powerpanel\CmsPage\Models\CmsPage');
        if ($postArr['chrMenuDisplay'] == 'D') {
            $addlog = Config::get('Constant.DRAFT_RECORD_APPROVED');
        } else {
            $addlog = Config::get('Constant.RECORD_APPROVED');
        }
        $newCmsPageObj = CmsPage::getRecordForLogById($id);
        $logArr = MyLibrary::logData($id, false, $addlog);
        $logArr['varTitle'] = stripslashes($newCmsPageObj->varTitle);
        Log::recordLog($logArr);
        if (method_exists($this->MyLibrary, 'userNotificationData')) {
            /* notification for user to record approved */
            $userNotificationArr = MyLibrary::userNotificationData(Config::get('Constant.MODULE.ID'));
            $userNotificationArr['fkRecordId'] = $id;
            $userNotificationArr['txtNotification'] = 'Your request has been approved by ' . ucfirst(auth()->user()->name) . ' (' . ucfirst(Config::get('Constant.MODULE.NAME')) . ')';
            $userNotificationArr['fkIntUserId'] = Auth::user()->id;
            $userNotificationArr['chrNotificationType'] = 'A';
            $userNotificationArr['intOnlyForUserId'] = $newCmsPageObj->UserID;
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
                $actionMessage = trans('cmspage::template.pageModule.pageUpdate');
            }
        }
    }

    public function insertApprovalRecord($moduleObj, $postArr, $cmsPageArr)
    {
        $response = false;
        $cmsPageArr['intAliasId'] = MyLibrary::insertAlias($postArr['alias'], false, 'N', $postArr['sector']);
        $cmsPageArr['chrMain'] = 'N';
        $cmsPageArr['chrLetest'] = 'Y';
         $cmsPageArr['varSector'] = $postArr['sector'];
        $cmsPageArr['varTags'] = trim($postArr['tags']);
        if ($postArr['chrMenuDisplay'] == 'D') {
            $cmsPageArr['chrDraft'] = 'D';
            $cmsPageArr['chrPublish'] = 'N';
        } else {
            $cmsPageArr['chrDraft'] = 'N';
            $cmsPageArr['chrPublish'] = $postArr['chrMenuDisplay'];
        }
        $cmsPageArr['fkMainRecord'] = $moduleObj->id;
        if (Config::get('Constant.CHRSearchRank') == 'Y') {
            $cmsPageArr['intSearchRank'] = $postArr['search_rank'];
        }
        if (isset($postArr['chrPageActive']) && $postArr['chrPageActive'] != '') {
            $cmsPageArr['chrPageActive'] = $postArr['chrPageActive'];
        }
        // if (isset($postArr['chrPageActive']) && $postArr['chrPageActive'] == 'PP') {
        //     $cmsPageArr['varPassword'] = $postArr['new_password'];
        // } else {
        //     $cmsPageArr['varPassword'] = '';
        // }
        if ($postArr['chrMenuDisplay'] == 'D') {
            $addlog = Config::get('Constant.DRAFT_SENT_FOR_APPROVAL');
        } else {
            $addlog = Config::get('Constant.SENT_FOR_APPROVAL');
        }
       
        $id = CommonModel::addRecord($cmsPageArr, 'Powerpanel\CmsPage\Models\CmsPage');
        if (isset($id) && !empty($id)) {
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
            $newCmsPageObj = CmsPage::getRecordForLogById($id);
            $logArr = MyLibrary::logData($id, false, $addlog);
            $logArr['varTitle'] = stripslashes($newCmsPageObj->varTitle);
            Log::recordLog($logArr);
            if (Auth::user()->can('recent-updates-list')) {
                $notificationArr = MyLibrary::notificationData($id, $newCmsPageObj);
                RecentUpdates::setNotification($notificationArr);
            }
            self::flushCache();
            $response = $newCmsPageObj;
        }
        $whereConditionsAddstar = ['id' => $moduleObj->id];
        $updateAddStar = [
            'chrAddStar' => 'Y',
        ];
        CommonModel::updateRecords($whereConditionsAddstar, $updateAddStar, false, 'Powerpanel\CmsPage\Models\CmsPage');
        return $response;
    }

    public function insertNewRecord($postArr, $cmsPageArr)
    {
        $response = false;
        //Add post Handler=======
        $cmsPageArr['chrMain'] = 'Y';
        $cmsPageArr['intAliasId'] = MyLibrary::insertAlias($postArr['alias'], false, 'N', $postArr['sector']);
        $cmsPageArr['created_at'] = Carbon::now();
        $cmsPageArr['varSector'] = $postArr['sector'];
        $cmsPageArr['updated_at'] = Carbon::now();
        $cmsPageArr['varTags'] = trim($postArr['tags']);
        if (Config::get('Constant.CHRSearchRank') == 'Y') {
            $cmsPageArr['intSearchRank'] = $postArr['search_rank'];
        }
        if (isset($postArr['chrPageActive']) && $postArr['chrPageActive'] != '') {
            $cmsPageArr['chrPageActive'] = $postArr['chrPageActive'];
        }
        // if (isset($postArr['chrPageActive']) && $postArr['chrPageActive'] == 'PP') {
        //     $cmsPageArr['varPassword'] = $postArr['new_password'];
        // } else {
        //     $cmsPageArr['varPassword'] = '';
        // }

        if ($postArr['chrMenuDisplay'] == 'D') {
            $cmsPageArr['chrDraft'] = 'D';
            $cmsPageArr['chrPublish'] = 'N';
        } else {
            $cmsPageArr['chrDraft'] = 'N';
        }

        if ($postArr['chrMenuDisplay'] == 'D') {
            $addlog = Config::get('Constant.ADDED_DRAFT');
        } else {
            $addlog = '';
        }
        $id = CommonModel::addRecord($cmsPageArr, 'Powerpanel\CmsPage\Models\CmsPage');
        if (isset($id) && !empty($id)) {
            $newCmsPageObj = CmsPage::getRecordForLogById($id);
            $logArr = MyLibrary::logData($id, false, $addlog);
            $logArr['varTitle'] = $newCmsPageObj->varTitle;
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

    public function DeleteRecord(Request $request)
    {
        $value = Request::get('value');
        $data['ids'] = Request::get('ids');
        $moduleHaveFields = ['chrMain', 'chrIsPreview'];
        $update = MyLibrary::deleteMultipleRecords($data, $moduleHaveFields, $value, 'Powerpanel\CmsPage\Models\CmsPage');

        if (File::exists(app_path() . '/Comments.php') != null || File::exists(base_path() . '/packages/Powerpanel/Workflow/src/Models/Comments.php') != null) {
            Comments::deleteComments($data['ids'], Config::get('Constant.MODULE.MODEL_NAME'));
        }

        foreach ($update as $update) {
            $ignoreDeleteScope = true;
            $Cmspage = CmsPage::getRecordById($update, $ignoreDeleteScope);
            $Cnt_Letest = CmsPage::getRecordCount_letest($Cmspage['fkMainRecord'], $Cmspage['id']);
            if ($Cnt_Letest <= 0) {
                $updateLetest = [
                    'chrAddStar' => 'N',
                ];
                $whereConditionsApprove = ['id' => $Cmspage['fkMainRecord']];
                CommonModel::updateRecords($whereConditionsApprove, $updateLetest, false, 'Powerpanel\CmsPage\Models\CmsPage');
                if (File::exists(app_path() . '/Workflow.php') != null || File::exists(base_path() . '/packages/Powerpanel/Workflow/src/Models/Workflow.php') != null) {
                    $where = [];
                    $flowData = [];
                    $flowData['dtNo'] = Config::get('Constant.SQLTIMESTAMP');
                    $where['fkModuleId'] = Config::get('Constant.MODULE.ID');
                    $where['fkRecordId'] = $Cmspage['fkMainRecord'];
                    $where['dtNo'] = 'null';
                    WorkflowLog::updateRecord($flowData, $where);
                }
            }
            if ($Cmspage['chrMain'] == "Y") {
                $whereConditions = [
                    'intPageId' => $update,
                    'intfkModuleId' => Config::get('Constant.MODULE.ID'),
                ];
                $updateMenuFields = [
                    'chrPublish' => 'N',
                    'chrDelete' => 'Y',
                    'chrActive' => 'N',
                ];
                CommonModel::updateRecords($whereConditions, $updateMenuFields, false, '\\Powerpanel\\Menu\\Models\\Menu');
                //code for delete alias from database
                if ($value != 'P' && $value != 'F' && $value != 'A' && $value != 'D' && $value != 'R') {
                    Alias::where('id', $Cmspage['intAliasId'])
                        ->where('varSector', $Cmspage['varSector'])
                        ->where('intFkModuleCode', Config::get('Constant.MODULE.ID'))
                        ->delete();
                }
            }
        }
        self::flushCache();
        echo json_encode($update);
        exit;
    }

    public function publish(Request $request)
    {
        $alias = Request::input('alias');
        $val = Request::get('val');
        $update = MyLibrary::setPublishUnpublish($alias, $val, 'Powerpanel\CmsPage\Models\CmsPage');
        $pageId = $alias;
        $state = Request::input('val') == 'Unpublish' ? 'N' : 'Y';
        $whereConditions = ['intPageId' => $pageId];
        $updateMenuFields = ['chrPublish' => $state, 'chrActive' => $state];
        //CommonModel::updateRecords($whereConditions, $updateMenuFields, false, '\\Powerpanel\\Menu\\Models\\Menu');
        self::flushCache();
        echo json_encode($update);
        exit;
    }

    public function getChildData()
    {
        $childHtml = '';
        $Cmspage_childData = '';
        $Cmspage_childData = CmsPage::getChildGrid();


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


        if (count($Cmspage_childData) > 0) {
            foreach ($Cmspage_childData as $child_row) {
                $checkbox = view('powerpanel.partials.checkbox', ['name'=>'delete', 'value'=>$child_row->id])->render();
                $parentAlias = $child_row->alias->varAlias;
                $url = url('/previewpage?url=' . MyLibrary::getFrontUri('pages')['uri'] . '/' . $parentAlias . '/' . $child_row->id . '/preview');

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
                        $childHtml .= "<td class=\"text-center\"><span class='mob_show_title'>Edit: </span><a class='icon_round me-2' data-bs-toggle='tooltip' data-bs-placement='bottom' title='" . trans('cmspage::template.common.edit') . "' href='" . route('powerpanel.pages.edit', array('alias' => $child_row->id)) . "?tab=A'><i class='ri-pencil-line'></i></a></td>";
                    } else {
                        $childHtml .= '<td class="text-center"><span class="mob_show_title">Edit: </span>-</td>';
                    }

                    if ($child_row->chrApproved == 'N') {
                        $childHtml .= "<td class=\"text-center\"><span class='mob_show_title'>Status: </span><a class=\"approve_icon_btn me-2\" data-bs-toggle='tooltip' data-bs-placement='bottom' title='" . trans('cmspage::template.common.comments') . "' href=\"javascript:void(0);\" onclick=\"loadModelpopup('" . $child_row->id . "','" . $child_row->UserID . "','" . Config::get('Constant.MODULE.MODEL_NAME') . "','" . $child_row->fkMainRecord . "')\"><i class=\"ri-chat-1-line\"></i> </a><a class=\"approve_icon_btn me-2\" onclick=\"update_mainrecord('" . $child_row->id . "','" . $child_row->fkMainRecord . "','" . $child_row->UserID . "','A');\" data-bs-toggle='tooltip' data-bs-placement='bottom' title='" . trans('cmspage::template.common.clickapprove') . "'  href=\"javascript:void(0);\"><i class=\"ri-checkbox-line
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

    public function ApprovedData_Listing(Request $request)
    {
        $requestArr = Request::all();
        $request = (object) $requestArr;
        $main_id = Request::post('main_id');
        $approvalid = Request::post('id');
        $flag = Request::post('flag');
        $message = CmsPage::approved_data_Listing($request);
        $newCmsPageObj = CmsPage::getRecordForLogById($main_id);
        $approval_obj = CmsPage::getRecordForLogById($approvalid);
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
        //Update record in menu
        $whereConditions = ['intPageId' => $main_id, 'intfkModuleId' => 4];
        $updateMenuFields = [
            'varTitle' => $newCmsPageObj->varTitle,
        ];
        //Update record in menu
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
        $Comments = '';
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

    public function getChildData_rollback()
    {
        $child_rollbackHtml = '';
        $Cmspage_rollbackchildData = '';
        $Cmspage_rollbackchildData = CmsPage::getChildrollbackGrid();
        $child_rollbackHtml .= '<div class="producttbl producttb2" style="">';
        $child_rollbackHtml .= '<table class="table table-hover align-middle table-nowrap hide-mobile" id="email_log_datatable_ajax">
        <thead class="table-light">
        <tr role="row">
        <th class="text-left">Title</th>
        <th class="text-left">Date</th>
        <th class="text-left">User</th>
        <th class="text-left">Preview</th>
        <th class="text-left">Status</th>';
        $child_rollbackHtml .= '</tr></thead><tbody>';
        if (count($Cmspage_rollbackchildData) > 0) {
            foreach ($Cmspage_rollbackchildData as $child_rollbacrow) {
                $parentAlias = $child_rollbacrow->alias->varAlias;
                $url = url('/previewpage?url=' . MyLibrary::getFrontUri('pages')['uri'] . '/' . $parentAlias . '/' . $child_rollbacrow->id . '/preview');
                $child_rollbackHtml .= '<tr role="row">';
                $child_rollbackHtml .= '<td class="text-left"><span class="mob_show_title">Title: </span>' . $child_rollbacrow->varTitle . '</td>';
                $child_rollbackHtml .= '<td class="text-left"><span class="mob_show_title">Date: </span>' . date('M d Y h:i A', strtotime($child_rollbacrow->created_at)) . '</td>';
                $child_rollbackHtml .= '<td class="text-left"><span class="mob_show_title">User: </span>' . CommonModel::getUserName($child_rollbacrow->UserID) . '</td>';
                $child_rollbackHtml .= '<td class="text-left"><span class="mob_show_title">Preview: </span><a class="icon_round me-2" href=' . $url . " target='_blank'><i class=\"ri-computer-line\"></i></a></td>";
                if ($child_rollbacrow->chrApproved == 'Y') {
                    $child_rollbackHtml .= '<td class="text-left"><span class="mob_show_title">Status: </span><i class="ri-checkbox-line " style="color: #1080F2;font-size:30px;"></i></td>';
                } else {
                    $child_rollbackHtml .= "<td class=\"text-left\"><span class=\"glyphicon glyphicon-minus\"></span></td>";
                }
                $child_rollbackHtml .= '</tr>';
            }
        } else {
            $child_rollbackHtml .= "<tr><td colspan='5'>No Records</td></tr>";
        }
        $child_rollbackHtml .= "</tbody>";
        echo $child_rollbackHtml;
        exit;
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
        /* code for insert comment */
        $userNotificationArr = MyLibrary::userNotificationData(Request::post('varModuleId'));
        $userNotificationArr['fkRecordId'] = Request::post('id');
        $userNotificationArr['txtNotification'] = 'New comment from ' . ucfirst(auth()->user()->name) . ' (' . ucfirst(Request::post('varModuleTitle')) . ')';
        $userNotificationArr['fkIntUserId'] = auth()->user()->id;
        $userNotificationArr['chrNotificationType'] = 'C';
        $userNotificationArr['intOnlyForUserId'] = Request::post('UserID');
        UserNotification::addRecord($userNotificationArr);
        exit;
    }

    public function tableData($value, $ignoreModuleIds = false,$permit,$currentUserID)
    {

        // Checkbox
        $checkbox = '';
        if ((isset($value->modules->varModuleName) && $value->modules->varModuleName != 'pages')) {
            $checkbox = '<div class="checker"><a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="bottom" title="This is module page so can&#39;t be deleted."><i style = "color:red" class = "ri-spam-line fs-16"></i></a></div>';
        } else {
            if (isset($value->varTitle) && strtolower($value->varTitle) != 'home') {
                $checkbox = view('powerpanel.partials.checkbox', ['name'=>'delete', 'value'=>$value->id])->render();
            }
        }



        // StartDate
        if ($value->id != 1) {
            $startDate = $value->dtDateTime;
            $startDate = '<span align="left" data-bs-toggle="tooltip" data-bs-placement="bottom" title="'.date(Config::get("Constant.DEFAULT_DATE_FORMAT").' '.Config::get("Constant.DEFAULT_TIME_FORMAT"), strtotime($startDate)).'">'.date(Config::get('Constant.DEFAULT_DATE_FORMAT'), strtotime($startDate)).'</span>';
        } else {
            $startDate = '-';
        }



        // Title
        $title = $value->varTitle;



        // Publish Action
        $publish_action = '';
        if ($value->chrAddStar != 'Y' && isset($value->modules->varModuleName) && $value->modules->varModuleName == 'pages') {
            if ($value->chrDraft != 'D') {
                if ($permit['canpagespublish']) {
                    if (isset($value->varTitle) && strtolower($value->varTitle) != 'home') {
                        if ($value->chrPublish == 'Y') {
                            $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/pages', 'data_alias'=>$value->id, 'title'=>trans("cmspage::template.common.publishedRecord"), 'data_value'=>'Unpublish', 'checked'=>'checked'])->render();
                        } else {
                            $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/pages', 'data_alias'=>$value->id, 'title'=>trans("cmspage::template.common.unpublishedRecord"), 'data_value'=>'Publish'])->render();
                        }
                    }
                } else {
                    $publish_action = "-";
                }
            } else {
                if ($permit['canpagespublish']) {
                    if (strtolower($value->varTitle) != 'home') {
                        if ($value->chrPublish == 'Y') {
                            $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/pages', 'data_alias'=>$value->id, 'title'=>trans("cmspage::template.common.publishedRecord"), 'data_value'=>'Unpublish', 'checked'=>'checked'])->render();
                        } else {
                            $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/pages', 'data_alias'=>$value->id, 'title'=>trans("cmspage::template.common.unpublishedRecord"), 'data_value'=>'Publish'])->render();
                        }
                    }
                } else {
                    $publish_action = "-";
                }
            }
        } else {
            if ($permit['canpagespublish']) {
                if (isset($value->modules->varModuleName) && $value->modules->varModuleName != 'pages') {
                    $publish_action .= '<a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="right" title="This page is assigned to module so can&#39;t be Publish/Unpublish."><i style="color:red" class="ri-toggle-line icon-publish fs-24"></i></a>';
                } else {
                    $publish_action .= '-';
                }
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


        // Public , Private , Password Protected
        $pubbtn = $value->chrPageActive;
        $pbtn = '';
        if (Config::get('Constant.DEFAULT_VISIBILITY') == 'Y') {
            if ($pubbtn == 'PU') {
                $pbtn = '<div class="pub_status publicdiv fs-16" data-bs-toggle="tooltip" title="Public"><span>Public</span></div>';
            } else if ($pubbtn == 'PR') {
                $pbtn = '<div class="pub_status privatediv fs-16" data-bs-toggle="tooltip" title="Private"><span>Private</span></div>';
            } else if ($pubbtn == 'PP') {
                $pbtn = '<div class="pub_status passworddiv fs-16" data-bs-toggle="tooltip" title="Password Protected"><span>Password Protected</span></div>';
            } else {
                $pbtn = '<div class="pub_status publicdiv fs-16" data-bs-toggle="tooltip" title="Public"><span>Public</span></div>';
            }
        }
        $First_td = '<div class="star_box d-inline-block">' . $pbtn . '</div>';



        // Title Action
        $title_action = '';
        if ($permit['canpagesedit']) {
            $Quickedit_startDate = date('Y-m-d H:i', strtotime($value->dtDateTime));
            $Quickedit_endDate = !empty($value->dtEndDateTime) ? date('Y-m-d H:i', strtotime($value->dtEndDateTime)) : 'No Expiry';

            if ($value->chrLock != 'Y') {
                if (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y') {
                    if ($permit['canpagesreviewchanges']) {
                        $title_action .= "<a href=\"javascript:void(0);\" class=\"icon_title1 approval_active\" data-bs-toggle=\"tooltip\" data-bs-placement=\"bottom\" title=\"Click here to see all approval records.\" style=\"margin-right: 5px;\" onclick=\"javascript:expandcollapsepanel(this ,'tasklisting" . $value->id . "', 'mainsingnimg" . $value->id . "'," . $value->id . ')" id="mainsingnimg' . $value->id . '"><i class="ri-stack-line fs-16"></i></a>';

                        // $title_action .= "<span class='show-hover'><a href=\"javascript:void(0);\" class=\"icon_title2 rollback_active\" data-bs-toggle=\"tooltip\" data-bs-placement=\"bottom\" title=\"Click here to see all approved records to rollback.\" style=\"margin-right: 5px;\" onclick=\"javascript:expandcollapsepanel_rolback(this ,'tasklisting_rollback" . $value->id . "', 'mainsingnimg_rollback" . $value->id . "'," . $value->id . ')" id="mainsingnimg_rollback' . $value->id . '"><i class="ri-refresh-line fs-16"></i></a></span>';
                        if (File::exists(base_path() . '/packages/Powerpanel/Workflow/src/Models/Workflow.php')) {
                            if ($this->currentUserRoleData->chrIsAdmin == 'Y' && count($value->child) > 1) {
                                $title_action .= "<a href='javascript:void(0);' data-bs-toggle='tooltip' data-bs-placement='bottom' style='margin-right: 5px;' title='Rollback to previous version' onclick='rollbackToPreviousVersion('" . $value->id . "');'  class='icon_title2 rollback_active'><i class='ri-history-line fs-16'></i></a>";
                            }
                        }
                    }
                    if (isset($value->varTitle) && strtolower($value->varTitle) != "home") {
                        if (Config::get('Constant.DEFAULT_QUICK') == 'Y') {
                            $title_action .= '<span class="show-hover"><a data-bs-toggle="tooltip" data-bs-placement="bottom" title="Quick Edit" href=\'javascript:void(0);\' data-toggle=\'modal\' data-target=\'#modalForm\' aria-label=\'Quick edit\' onclick=\'Quickeditfun("' . $value->id . '","' . $value->varTitle . '","' . $value->intSearchRank . '","' . $Quickedit_startDate . '","' . $Quickedit_endDate . '","P")\'><i class="ri-edit-2-line fs-16"></i></a></span>';
                        }
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



        // All Actions
        $actions = '<div class="dropdown">';
        $actions .= '<a href="javascript:void(0);" role="button" id="dropdownMenuLink'.$value->id.'" data-bs-toggle="dropdown" aria-expanded="false" class=""><i class="ri-more-fill"></i></a>';
        $actions .= '<ul class="dropdown-menu" aria-labelledby="dropdownMenuLink'.$value->id.'">';

            // Edit
            if ($permit['canpagesedit']) {
                $actions .= '<li><a data-bs-toggle="tooltip" data-bs-placement="left" class="dropdown-item" title = "' . trans('cmspage::template.common.edit') . '" href = "' . route('powerpanel.pages.edit', array('alias' => $value->id)) . '?tab=P"><i class = "ri-pencil-line"></i>&nbsp;&nbsp;Edit</a></li>';
            }

            // Trash
            if ($permit['canpagesdelete'] || (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y')) {
                if (isset($value->varTitle) && strtolower($value->varTitle) != 'home') {
                    if (isset($value->modules->varModuleName) && $value->modules->varModuleName == 'pages') {
                        if (Config::get('Constant.DEFAULT_TRASH') == 'Y') {
                            $actions .= '<li><a data-bs-toggle="tooltip" data-bs-placement="bottom" title = "Trash" href = \'javascript:void(0);\' onclick=\'Trashfun("' . $value->id . '")\' class="dropdown-item red" data-tab="P"><i class="ri-delete-bin-line"></i>&nbsp;&nbsp;Trash</a></li>';
                        }
                    }
                }
            }

            // Add to Menu
            if (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y') {
                $actions .= '<li><a data-bs-toggle="tooltip" data-bs-placement="left" class="dropdown-item without_bg_icon" href="' . url('powerpanel/menu?pageId=' . $value->id) . '" title="Add to menu"><i class="ri-file-list-line"></i>&nbsp;&nbsp;Add to Menu</a></li>';
            }

            // Preview & View
            if ($permit['canpagesedit']) {
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

            if ($value->chrLock != 'Y') {
                if (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y') {
                    $actions .= '<li><a data-bs-toggle="tooltip" data-bs-placement="left" class="dropdown-item without_bg_icon" href="' . $viewlink . '" target="_blank" title="' . $linkviewLable . '" ><i class="ri-eye-line"></i>&nbsp;&nbsp;' . $linkviewLable . '</a></li>';
                }
            }

            // Duplicate , Log History , Locked-UnLock
            $logurl = url('powerpanel/log?id=' . $value->id . '&mid=' . Config::get('Constant.MODULE.ID'));
            $log = '';
            $log .= $actions;
            if ($value->chrLock != 'Y') {
                if (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y') {
                    if (Config::get('Constant.DEFAULT_DUPLICATE') == 'Y') {
                        $log .= "<li><a data-bs-toggle=\"tooltip\" data-bs-placement=\"left\" title=\"Duplicate\" class='copy-grid dropdown-item' href=\"javascript:void(0);\" onclick=\"GetCopyPage('" . $value->id . "');\"><i class=\"ri-file-copy-line\"></i>&nbsp;&nbsp;Duplicate</a></li>";
                    }
                    if ($permit['canloglist']) {
                        $log .= "<li><a data-bs-toggle=\"tooltip\" data-bs-placement=\"left\" title=\"Log History\" class='log-grid dropdown-item' href=\"$logurl\"><i class=\"ri-time-line\"></i>&nbsp;&nbsp;Log History</a></li>";
                    }
                } else {
                    if ($actions == "") {
                        $actions = "---";
                    } else {
                        $actions = $actions;
                    }
                    $log .= $actions;
                }
            } else {
                if ($currentUserID != $value->LockUserID) {
                    $lockedUserData = null;//User::getRecordById($value->LockUserID, true);
                    $lockedUserName = 'someone';
                    if (!empty($lockedUserData)) {
                        $lockedUserName = $lockedUserData->name;
                    }
                    if (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y') {
                        $log .= '<li><a href="javascript:void(0);" class="copy-grid dropdown-item" onclick="GetUnLockData(' . $value->id . ',' . $currentUserID . ',' . Config::get('Constant.MODULE.ID') . ',1)" data-bs-toggle="tooltip" data-bs-placement="left" title="This record has been locked by ' . $lockedUserName . ', Click here to unlock."><i class="ri-lock-2-line"></i>&nbsp;&nbsp;UnLock</a></li>';
                    } else {
                        $log .= '<li><a href="javascript:void(0);" class="copy-grid dropdown-item" data-bs-toggle="tooltip" data-bs-placement="left" title="This record has been locked by ' . $lockedUserName . '."><i class="ri-lock-2-line"></i>&nbsp;&nbsp;Locked</a></li>';
                    }
                } else {
                    $log .= '<li><a href="javascript:void(0);" class="copy-grid dropdown-item" onclick="GetUnLockData(' . $value->id . ',' . $currentUserID . ',' . Config::get('Constant.MODULE.ID') . ',1)" data-bs-toggle="tooltip" data-bs-placement="left" title="Click here to unlock."><i class="ri-lock-2-line"></i>&nbsp;&nbsp;UnLock</a></li>';
                }
            }

        $log .= "</ul></div>";

        if($permit['canpagesedit'] || $permit['canpagesdelete']){
            $log = $log;
        } else {
            $log = "-";
        }

        $records = array(
            $checkbox,
            '<div class="pages_title_div_row">' . $Favorite . ' <span class="title-txt"> ' . $First_td . ' ' . $title . ' ' . $status . $statusdata . ' - ' . $sector . ' ' .$title_action. '</span></div>',
            $startDate,
            $publish_action,
            $log
        );
        return $records;
    }

    public function tableData_tab1($value, $ignoreModuleIds,$permit,$currentUserID)
    {

        // Checkbox
        $checkbox = '';
        if ((isset($value->modules->varModuleName) && $value->modules->varModuleName != 'pages')) {
            $checkbox = '<div class="checker"><a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="right" title="This is module page so can&#39;t be deleted."><i style = "color:red" class = "ri-spam-line fs-16"></i></a></div>';
        } else {
            if (isset($value->varTitle) && strtolower($value->varTitle) != 'home') {
                $checkbox = view('powerpanel.partials.checkbox', ['name'=>'delete', 'value'=>$value->id])->render();
            }
        }



        // StartDate
        if ($value->id != 1) {
            $startDate = $value->dtDateTime;
            $startDate = '<span align="left" data-bs-toggle="tooltip" data-bs-placement="bottom" title="'.date(Config::get("Constant.DEFAULT_DATE_FORMAT").' '.Config::get("Constant.DEFAULT_TIME_FORMAT"), strtotime($startDate)).'">'.date(Config::get('Constant.DEFAULT_DATE_FORMAT'), strtotime($startDate)).'</span>';
        } else {
            $startDate = '-';
        }



        // Title
        $title = $value->varTitle;



        // Publish Action
        $publish_action = '';
        if ($value->chrAddStar != 'Y' && isset($value->modules->varModuleName) && $value->modules->varModuleName == 'pages') {
            if ($value->chrDraft != 'D') {
                if ($permit['canpagespublish']) {
                    if (isset($value->varTitle) && strtolower($value->varTitle) != 'home') {
                        if ($value->chrPublish == 'Y') {
                            $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/pages', 'data_alias'=>$value->id, 'title'=>trans("cmspage::template.common.publishedRecord"), 'data_value'=>'Unpublish', 'checked'=>'checked'])->render();
                        } else {
                            $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/pages', 'data_alias'=>$value->id, 'title'=>trans("cmspage::template.common.unpublishedRecord"), 'data_value'=>'Publish'])->render();
                        }
                    }
                } else {
                    $publish_action = "-";
                }
            } else {
                if ($permit['canpagespublish']) {
                    if (strtolower($value->varTitle) != 'home') {
                        if ($value->chrPublish == 'Y') {
                            $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/pages', 'data_alias'=>$value->id, 'title'=>trans("cmspage::template.common.publishedRecord"), 'data_value'=>'Unpublish', 'checked'=>'checked'])->render();
                        } else {
                            $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/pages', 'data_alias'=>$value->id, 'title'=>trans("cmspage::template.common.unpublishedRecord"), 'data_value'=>'Publish'])->render();
                        }
                    }
                } else {
                    $publish_action = "-";
                }
            }
        } else {
            if ($permit['canpagespublish']) {
                if (isset($value->modules->varModuleName) && $value->modules->varModuleName != 'pages') {
                    $publish_action .= '<a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="right" title="This page is assigned to module so can&#39;t be Publish/Unpublish."><i style="color:red" class="ri-toggle-line icon-publish fs-24"></i></a>';
                } else {
                    $publish_action .= '-';
                }
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


        // Public , Private , Password Protected
        $pubbtn = $value->chrPageActive;
        $pbtn = '';
        if (Config::get('Constant.DEFAULT_VISIBILITY') == 'Y') {
            if ($pubbtn == 'PU') {
                $pbtn = '<div class="pub_status publicdiv fs-16" data-bs-toggle="tooltip" title="Public"><span>Public</span></div>';
            } else if ($pubbtn == 'PR') {
                $pbtn = '<div class="pub_status privatediv fs-16" data-bs-toggle="tooltip" title="Private"><span>Private</span></div>';
            } else if ($pubbtn == 'PP') {
                $pbtn = '<div class="pub_status passworddiv fs-16" data-bs-toggle="tooltip" title="Password Protected"><span>Password Protected</span></div>';
            } else {
                $pbtn = '<div class="pub_status publicdiv fs-16" data-bs-toggle="tooltip" title="Public"><span>Public</span></div>';
            }
        }
        $First_td = '<div class="star_box d-inline-block">' . $pbtn . '</div>';



        // Title Action
        $title_action = '';
        if ($permit['canpagesedit']) {
            $Quickedit_startDate = date('Y-m-d H:i', strtotime($value->dtDateTime));
            $Quickedit_endDate = !empty($value->dtEndDateTime) ? date('Y-m-d H:i', strtotime($value->dtEndDateTime)) : 'No Expiry';

            if ($value->chrLock != 'Y') {
                if (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y') {
                    if ($permit['canpagesreviewchanges']) {
                        $title_action .= "<a href=\"javascript:void(0);\" class=\"icon_title1 approval_active\" data-bs-toggle=\"tooltip\" data-bs-placement=\"bottom\" title=\"Click here to see all approval records.\" style=\"margin-right: 5px;\" onclick=\"javascript:expandcollapsepanel(this ,'tasklisting" . $value->id . "', 'mainsingnimg" . $value->id . "'," . $value->id . ')" id="mainsingnimg' . $value->id . '"><i class="ri-stack-line fs-16"></i></a>';

                        // $title_action .= "<span class='show-hover'><a href=\"javascript:void(0);\" class=\"icon_title2 rollback_active\" data-bs-toggle=\"tooltip\" data-bs-placement=\"bottom\" title=\"Click here to see all approved records to rollback.\" style=\"margin-right: 5px;\" onclick=\"javascript:expandcollapsepanel_rolback(this ,'tasklisting_rollback" . $value->id . "', 'mainsingnimg_rollback" . $value->id . "'," . $value->id . ')" id="mainsingnimg_rollback' . $value->id . '"><i class="ri-refresh-line fs-16"></i></a></span>';
                        if (File::exists(base_path() . '/packages/Powerpanel/Workflow/src/Models/Workflow.php')) {
                            if ($this->currentUserRoleData->chrIsAdmin == 'Y' && count($value->child) > 1) {
                                $title_action .= "<a href='javascript:void(0);' data-bs-toggle='tooltip' data-bs-placement='bottom' style='margin-right: 5px;' title='Rollback to previous version' onclick='rollbackToPreviousVersion('" . $value->id . "');'  class='icon_title2 rollback_active'><i class='ri-history-line fs-16'></i></a>";
                            }
                        }
                    }
                    if (isset($value->alias->varAlias) && $value->alias->varAlias != "home") {
                        if (Config::get('Constant.DEFAULT_QUICK') == 'Y') {
                            $title_action .= '<span class="show-hover"><a data-bs-toggle="tooltip" data-bs-placement="bottom" title="Quick Edit" href=\'javascript:void(0);\' data-toggle=\'modal\' data-target=\'#modalForm\' aria-label=\'Quick edit\' onclick=\'Quickeditfun("' . $value->id . '","' . $value->varTitle . '","' . $value->intSearchRank . '","' . $Quickedit_startDate . '","' . $Quickedit_endDate . '","P")\'><i class="ri-edit-2-line fs-16"></i></a></span>';
                        }
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



        // All Actions
        $actions = '<div class="dropdown">';
        $actions .= '<a href="javascript:void(0);" role="button" id="dropdownMenuLink'.$value->id.'" data-bs-toggle="dropdown" aria-expanded="false" class=""><i class="ri-more-fill"></i></a>';
        $actions .= '<ul class="dropdown-menu" aria-labelledby="dropdownMenuLink'.$value->id.'">';

            // Edit
            if ($permit['canpagesedit']) {
                $actions .= '<li><a data-bs-toggle="tooltip" data-bs-placement="left" class="dropdown-item" title="' . trans('cmspage::template.common.edit') . '" href = "' . route('powerpanel.pages.edit', array('alias' => $value->id)) . '?tab=P"><i class = "ri-pencil-line"></i>&nbsp;&nbsp;Edit</a></li>';
            }

            // Trash
            if ($permit['canpagesdelete'] || (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y')) {
                if (isset($value->varTitle) && strtolower($value->varTitle) != 'home') {
                    if (isset($value->modules->varModuleName) && $value->modules->varModuleName == 'pages') {
                        if (Config::get('Constant.DEFAULT_TRASH') == 'Y') {
                            $actions .= '<li><a data-bs-toggle="tooltip" data-bs-placement="left" title="Trash" href = \'javascript:void(0);\' onclick=\'Trashfun("' . $value->id . '")\' class="dropdown-item red" data-tab="P"><i class="ri-delete-bin-line"></i>&nbsp;&nbsp;Trash</a></li>';
                        }
                    }
                }
            }

            // Add to Menu
            if (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y') {
                $actions .= '<li><a data-bs-toggle="tooltip" data-bs-placement="left" class="dropdown-item without_bg_icon" href="' . url('powerpanel/menu?pageId=' . $value->id) . '" title="Add to menu"><i class="ri-file-list-line"></i>&nbsp;&nbsp;Add to Menu</a></li>';
            }

            // Preview & View
            if ($permit['canpagesedit']) {
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

            if ($value->chrLock != 'Y') {
                if (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y') {
                    $actions .= '<li><a data-bs-toggle="tooltip" data-bs-placement="bottom" class="dropdown-item without_bg_icon" href="' . $viewlink . '" target="_blank" title="' . $linkviewLable . '" ><i class="ri-eye-line"></i>&nbsp;&nbsp;' . $linkviewLable . '</a></li>';
                }
            }

            // Duplicate , Log History , Locked-UnLock
            $logurl = url('powerpanel/log?id=' . $value->id . '&mid=' . Config::get('Constant.MODULE.ID'));
            $log = '';
            if ($value->chrLock != 'Y') {
                if (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y') {
                    $log .= $actions;
                    if (Config::get('Constant.DEFAULT_DUPLICATE') == 'Y') {
                        $log .= "<li><a data-bs-toggle=\"tooltip\" data-bs-placement=\"left\" title=\"Duplicate\" class='copy-grid dropdown-item' href=\"javascript:void(0);\" onclick=\"GetCopyPage('" . $value->id . "');\"><i class=\"ri-file-copy-line\"></i>&nbsp;&nbsp;Duplicate</a></li>";
                    }
                    if ($permit['canloglist']) {
                        $log .= "<li><a data-bs-toggle=\"tooltip\" data-bs-placement=\"left\" title=\"Log History\" class='log-grid dropdown-item' href=\"$logurl\"><i class=\"ri-time-line\"></i>&nbsp;&nbsp;Log History</a></li>";
                    }
                } else {
                    if ($actions == "") {
                        $actions = "---";
                    } else {
                        $actions = $actions;
                    }
                    $log .= $actions;
                }
            } else {
                if ($currentUserID != $value->LockUserID) {
                    $lockedUserData = null;//User::getRecordById($value->LockUserID, true);
                    $lockedUserName = 'someone';
                    if (!empty($lockedUserData)) {
                        $lockedUserName = $lockedUserData->name;
                    }
                    if (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y') {
                        $log .= '<li><a href="javascript:void(0);" class="star_lock" onclick="GetUnLockData(' . $value->id . ',' . $currentUserID . ',' . Config::get('Constant.MODULE.ID') . ',1)" data-bs-toggle="tooltip" data-bs-placement="bottom" title="This record has been locked by ' . $lockedUserName . ', Click here to unlock."><i class="ri-lock-2-line"></i>&nbsp;&nbsp;UnLock</a></li>';
                    } else {
                        $log .= '<li><a href="javascript:void(0);" class="star_lock" data-bs-toggle="tooltip" data-bs-placement="bottom" title="This record has been locked by ' . $lockedUserName . '."><i class="ri-lock-2-line"></i>&nbsp;&nbsp;Locked</a></li>';
                    }
                } else {
                    $log .= '<li><a href="javascript:void(0);" class="star_lock" onclick="GetUnLockData(' . $value->id . ',' . $currentUserID . ',' . Config::get('Constant.MODULE.ID') . ',1)" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Click here to unlock."><i class="ri-lock-2-line"></i>&nbsp;&nbsp;UnLock</a></li>';
                }
            }
        $log .= "</ul></div>";

        if($permit['canpagesedit'] || $permit['canpagesdelete']){
            $log = $log;
        } else {
            $log = "-";
        }

        $records = array(
            $checkbox,
            '<div class="pages_title_div_row">' . $Favorite . ' <span class="title-txt"> ' . $First_td . ' ' . $title . ' ' . $status . $statusdata . ' - ' . $sector . ' ' .$title_action. '</span></div>',
            $startDate,
            $publish_action,
            $log
        );
        return $records;
    }

    public function tableDataFavorite($value, $ignoreModuleIds = false,$permit,$currentUserID)
    {
        // Checkbox
        $checkbox = '';
        if ((isset($value->modules->varModuleName) && $value->modules->varModuleName != 'pages')) {
            $checkbox = '<div class="checker"><a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="right" title="This is module page so can&#39;t be deleted."><i style = "color:red" class = "ri-spam-line fs-16"></i></a></div>';
        } else {
            if (isset($value->varTitle) && strtolower($value->varTitle) != 'home') {
                $checkbox = view('powerpanel.partials.checkbox', ['name'=>'delete', 'value'=>$value->id])->render();
            }
        }


        // StartDate
        if ($value->id != 1) {
            $startDate = $value->dtDateTime;
            $startDate = '<span align="left" data-bs-toggle="tooltip" data-bs-placement="bottom" title="'.date(Config::get("Constant.DEFAULT_DATE_FORMAT").' '.Config::get("Constant.DEFAULT_TIME_FORMAT"), strtotime($startDate)).'">'.date(Config::get('Constant.DEFAULT_DATE_FORMAT'), strtotime($startDate)).'</span>';
        } else {
            $startDate = '-';
        }


        // Title
        $title = $value->varTitle;


        // Publish Action
        $publish_action = '';
        if ($value->chrAddStar != 'Y' && isset($value->modules->varModuleName) && $value->modules->varModuleName == 'pages') {
            if ($value->chrDraft != 'D') {
                if ($permit['canpagespublish']) {
                    if (isset($value->varTitle) && strtolower($value->varTitle) != 'home') {
                        if ($value->chrPublish == 'Y') {
                            $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/pages', 'data_alias'=>$value->id, 'title'=>trans("cmspage::template.common.publishedRecord"), 'data_value'=>'Unpublish', 'checked'=>'checked'])->render();
                        } else {
                            $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/pages', 'data_alias'=>$value->id, 'title'=>trans("cmspage::template.common.unpublishedRecord"), 'data_value'=>'Publish'])->render();
                        }
                    }
                } else {
                    $publish_action = "-";
                }
            } else {
                if ($permit['canpagespublish']) {
                    if (strtolower($value->varTitle) != 'home') {
                        if ($value->chrPublish == 'Y') {
                            $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/pages', 'data_alias'=>$value->id, 'title'=>trans("cmspage::template.common.publishedRecord"), 'data_value'=>'Unpublish', 'checked'=>'checked'])->render();
                        } else {
                            $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/pages', 'data_alias'=>$value->id, 'title'=>trans("cmspage::template.common.unpublishedRecord"), 'data_value'=>'Publish'])->render();
                        }
                    }
                } else {
                    $publish_action = "-";
                }
            }
        } else {
            if ($permit['canpagespublish']) {
                if (isset($value->modules->varModuleName) && $value->modules->varModuleName != 'pages') {
                    $publish_action .= '<a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="right" title="This page is assigned to module so can&#39;t be Publish/Unpublish."><i style="color:red" class="ri-toggle-line icon-publish fs-24"></i></a>';
                } else {
                    $publish_action .= '-';
                }
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


        // Public , Private , Password Protected
        $pubbtn = $value->chrPageActive;
        $pbtn = '';
        if (Config::get('Constant.DEFAULT_VISIBILITY') == 'Y') {
            if ($pubbtn == 'PU') {
                $pbtn = '<div class="pub_status publicdiv fs-16" data-bs-toggle="tooltip" title="Public"><span>Public</span></div>';
            } else if ($pubbtn == 'PR') {
                $pbtn = '<div class="pub_status privatediv fs-16" data-bs-toggle="tooltip" title="Private"><span>Private</span></div>';
            } else if ($pubbtn == 'PP') {
                $pbtn = '<div class="pub_status passworddiv fs-16" data-bs-toggle="tooltip" title="Password Protected"><span>Password Protected</span></div>';
            } else {
                $pbtn = '<div class="pub_status publicdiv fs-16" data-bs-toggle="tooltip" title="Public"><span>Public</span></div>';
            }
        }
        $First_td = '<div class="star_box d-inline-block">' . $pbtn . '</div>';



        // Title Action
        $title_action = '';
        if ($permit['canpagesedit']) {
            $Quickedit_startDate = date('Y-m-d H:i', strtotime($value->dtDateTime));
            $Quickedit_endDate = !empty($value->dtEndDateTime) ? date('Y-m-d H:i', strtotime($value->dtEndDateTime)) : 'No Expiry';

            if ($value->chrLock != 'Y') {
                if (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y') {
                    if ($permit['canpagesreviewchanges']) {
                        $title_action .= "<a href=\"javascript:void(0);\" class=\"icon_title1 approval_active\" data-bs-toggle=\"tooltip\" data-bs-placement=\"bottom\" title=\"Click here to see all approval records.\" style=\"margin-right: 5px;\" onclick=\"javascript:expandcollapsepanel(this ,'tasklisting" . $value->id . "', 'mainsingnimg" . $value->id . "'," . $value->id . ')" id="mainsingnimg' . $value->id . '"><i class="ri-stack-line fs-16"></i></a>';

                        // $title_action .= "<span class='show-hover'><a href=\"javascript:void(0);\" class=\"icon_title2 rollback_active\" data-bs-toggle=\"tooltip\" data-bs-placement=\"bottom\" title=\"Click here to see all approved records to rollback.\" style=\"margin-right: 5px;\" onclick=\"javascript:expandcollapsepanel_rolback(this ,'tasklisting_rollback" . $value->id . "', 'mainsingnimg_rollback" . $value->id . "'," . $value->id . ')" id="mainsingnimg_rollback' . $value->id . '"><i class="ri-refresh-line fs-16"></i></a></span>';
                        if (File::exists(base_path() . '/packages/Powerpanel/Workflow/src/Models/Workflow.php')) {
                            if ($this->currentUserRoleData->chrIsAdmin == 'Y' && count($value->child) > 1) {
                                $title_action .= "<a href='javascript:void(0);' data-bs-toggle='tooltip' data-bs-placement='bottom' style='margin-right: 5px;' title='Rollback to previous version' onclick='rollbackToPreviousVersion('" . $value->id . "');'  class='icon_title2 rollback_active'><i class='ri-history-line fs-16'></i></a>";
                            }
                        }
                    }
                    if (isset($value->alias->varAlias) && $value->alias->varAlias != "home") {
                        if (Config::get('Constant.DEFAULT_QUICK') == 'Y') {
                            $title_action .= '<span class="show-hover"><a data-bs-toggle="tooltip" data-bs-placement="bottom" title="Quick Edit" href=\'javascript:void(0);\' data-toggle=\'modal\' data-target=\'#modalForm\' aria-label=\'Quick edit\' onclick=\'Quickeditfun("' . $value->id . '","' . $value->varTitle . '","' . $value->intSearchRank . '","' . $Quickedit_startDate . '","' . $Quickedit_endDate . '","P")\'><i class="ri-edit-2-line fs-16"></i></a></span>';
                        }
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



        // All Actions
        $actions = '<div class="dropdown">';
        $actions .= '<a href="javascript:void(0);" role="button" id="dropdownMenuLink'.$value->id.'" data-bs-toggle="dropdown" aria-expanded="false" class=""><i class="ri-more-fill"></i></a>';
        $actions .= '<ul class="dropdown-menu" aria-labelledby="dropdownMenuLink'.$value->id.'">';

            // Edit
            if ($permit['canpagesedit']) {
                $actions .= '<li><a data-bs-toggle="tooltip" data-bs-placement="left" class="dropdown-item" title = "' . trans('cmspage::template.common.edit') . '" href = "' . route('powerpanel.pages.edit', array('alias' => $value->id)) . '?tab=P"><i class = "ri-pencil-line"></i>&nbsp;&nbsp;Edit</a></li>';
            }

            // Trash
            if ($permit['canpagesdelete'] || (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y')) {
                if (isset($value->varTitle) && strtolower($value->varTitle) != 'home') {
                    if (isset($value->modules->varModuleName) && $value->modules->varModuleName == 'pages') {
                        if (Config::get('Constant.DEFAULT_TRASH') == 'Y') {
                            $actions .= '<li><a data-bs-toggle="tooltip" data-bs-placement="left" title = "Trash" href = \'javascript:void(0);\' onclick=\'Trashfun("' . $value->id . '")\' class="dropdown-item red" data-tab="P"><i class="ri-delete-bin-line"></i>&nbsp;&nbsp;Trash</a></li>';
                        }
                    }
                }
            }

            // Add to Menu
            if (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y') {
                $actions .= '<li><a data-bs-toggle="tooltip" data-bs-placement="left" class="dropdown-item without_bg_icon" href="' . url('powerpanel/menu?pageId=' . $value->id) . '" title="Add to menu"><i class="ri-file-list-line"></i>&nbsp;&nbsp;Add to Menu</a></li>';
            }

            // Preview & View
            if ($permit['canpagesedit']) {
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

            if ($value->chrLock != 'Y') {
                if (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y') {
                    $actions .= '<li><a data-bs-toggle="tooltip" data-bs-placement="left" class="dropdown-item without_bg_icon" href="' . $viewlink . '" target="_blank" title="' . $linkviewLable . '" ><i class="ri-eye-line"></i>&nbsp;&nbsp;' . $linkviewLable . '</a></li>';
                }
            }

            // Duplicate , Log History , Locked-UnLock
            $logurl = url('powerpanel/log?id=' . $value->id . '&mid=' . Config::get('Constant.MODULE.ID'));
            $log = '';
            if ($value->chrLock != 'Y') {
                if (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y') {
                    $log .= $actions;
                    if (Config::get('Constant.DEFAULT_DUPLICATE') == 'Y') {
                        $log .= "<li><a data-bs-toggle=\"tooltip\" data-bs-placement=\"left\" title=\"Duplicate\" class='copy-grid dropdown-item' href=\"javascript:void(0);\" onclick=\"GetCopyPage('" . $value->id . "');\"><i class=\"ri-file-copy-line\"></i>&nbsp;&nbsp;Duplicate</a></li>";
                    }
                    if ($permit['canloglist']) {
                        $log .= "<li><a data-bs-toggle=\"tooltip\" data-bs-placement=\"left\" title=\"Log History\" class='log-grid dropdown-item' href=\"$logurl\"><i class=\"ri-time-line\"></i>&nbsp;&nbsp;Log History</a></li>";
                    }
                } else {
                    if ($actions == "") {
                        $actions = "---";
                    } else {
                        $actions = $actions;
                    }
                    $log .= $actions;
                }
            } else {
                if ($currentUserID != $value->LockUserID) {
                    $lockedUserData = null;//User::getRecordById($value->LockUserID, true);
                    $lockedUserName = 'someone';
                    if (!empty($lockedUserData)) {
                        $lockedUserName = $lockedUserData->name;
                    }
                    if (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y') {
                        $log .= '<li><a href="javascript:void(0);" class="star_lock" onclick="GetUnLockData(' . $value->id . ',' . $currentUserID . ',' . Config::get('Constant.MODULE.ID') . ',1)" data-bs-toggle="tooltip" data-bs-placement="bottom" title="This record has been locked by ' . $lockedUserName . ', Click here to unlock."><i class="ri-lock-2-line"></i>&nbsp;&nbsp;UnLock</a></li>';
                    } else {
                        $log .= '<li><a href="javascript:void(0);" class="star_lock" data-bs-toggle="tooltip" data-bs-placement="bottom" title="This record has been locked by ' . $lockedUserName . '."><i class="ri-lock-2-line"></i>&nbsp;&nbsp;Locked</a></li>';
                    }
                } else {
                    $log .= '<li><a href="javascript:void(0);" class="star_lock" onclick="GetUnLockData(' . $value->id . ',' . $currentUserID . ',' . Config::get('Constant.MODULE.ID') . ',1)" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Click here to unlock."><i class="ri-lock-2-line"></i>&nbsp;&nbsp;UnLock</a></li>';
                }
            }

        $log .= "</ul></div>";

        if($permit['canpagesedit'] || $permit['canpagesdelete']){
            $log = $log;
        } else {
            $log = "-";
        }

        $records = array(
            $checkbox,
            '<div class="pages_title_div_row">' . $Favorite . ' <span class="title-txt"> ' . $First_td . ' ' . $title . ' ' . $status . $statusdata . ' - ' . $sector . ' ' .$title_action. '</span></div>',
            $startDate,
            $publish_action,
            $log
        );
        return $records;
    }

    public function tableDataDraft($value, $ignoreModuleIds,$permit,$currentUserID)
    {
        // Checkbox
        $checkbox = '';
        if ((isset($value->modules->varModuleName) && $value->modules->varModuleName != 'pages')) {
            $checkbox = '<div class="checker"><a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="right" title="This is module page so can&#39;t be deleted."><i style = "color:red" class = "ri-spam-line fs-16"></i></a></div>';
        } else {
            if (isset($value->varTitle) && strtolower($value->varTitle) != 'home') {
                $checkbox = view('powerpanel.partials.checkbox', ['name'=>'delete', 'value'=>$value->id])->render();
            }
        }


        // StartDate
        if ($value->id != 1) {
            $startDate = $value->dtDateTime;
            $startDate = '<span align="left" data-bs-toggle="tooltip" data-bs-placement="bottom" title="'.date(Config::get("Constant.DEFAULT_DATE_FORMAT").' '.Config::get("Constant.DEFAULT_TIME_FORMAT"), strtotime($startDate)).'">'.date(Config::get('Constant.DEFAULT_DATE_FORMAT'), strtotime($startDate)).'</span>';
        } else {
            $startDate = '-';
        }


        // Title
        $title = $value->varTitle;


        // Publish Action
        // Publish Action
        $publish_action = '';
        if ($value->chrAddStar != 'Y' && isset($value->modules->varModuleName) && $value->modules->varModuleName == 'pages') {
            if ($value->chrDraft != 'D') {
                if ($permit['canpagespublish']) {
                    if (isset($value->varTitle) && strtolower($value->varTitle) != 'home') {
                        if ($value->chrPublish == 'Y') {
                            $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/pages', 'data_alias'=>$value->id, 'title'=>trans("cmspage::template.common.publishedRecord"), 'data_value'=>'Unpublish', 'checked'=>'checked'])->render();
                        } else {
                            $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/pages', 'data_alias'=>$value->id, 'title'=>trans("cmspage::template.common.unpublishedRecord"), 'data_value'=>'Publish'])->render();
                        }
                    }
                } else {
                    $publish_action = "-";
                }
            } else {
                if ($permit['canpagespublish']) {
                    if (strtolower($value->varTitle) != 'home') {
                        if ($value->chrPublish == 'Y') {
                            $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/pages', 'data_alias'=>$value->id, 'title'=>trans("cmspage::template.common.publishedRecord"), 'data_value'=>'Unpublish', 'checked'=>'checked'])->render();
                        } else {
                            $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/pages', 'data_alias'=>$value->id, 'title'=>trans("cmspage::template.common.unpublishedRecord"), 'data_value'=>'Publish'])->render();
                        }
                    }
                } else {
                    $publish_action = "-";
                }
            }
        } else {
            if ($permit['canpagespublish']) {
                if (isset($value->modules->varModuleName) && $value->modules->varModuleName != 'pages') {
                    $publish_action .= '<a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="right" title="This page is assigned to module so can&#39;t be Publish/Unpublish."><i style="color:red" class="ri-toggle-line icon-publish fs-24"></i></a>';
                } else {
                    $publish_action .= '-';
                }
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


        // Public , Private , Password Protected
        $pubbtn = $value->chrPageActive;
        $pbtn = '';
        if (Config::get('Constant.DEFAULT_VISIBILITY') == 'Y') {
            if ($pubbtn == 'PU') {
                $pbtn = '<div class="pub_status publicdiv fs-16" data-bs-toggle="tooltip" title="Public"><span>Public</span></div>';
            } else if ($pubbtn == 'PR') {
                $pbtn = '<div class="pub_status privatediv fs-16" data-bs-toggle="tooltip" title="Private"><span>Private</span></div>';
            } else if ($pubbtn == 'PP') {
                $pbtn = '<div class="pub_status passworddiv fs-16" data-bs-toggle="tooltip" title="Password Protected"><span>Password Protected</span></div>';
            } else {
                $pbtn = '<div class="pub_status publicdiv fs-16" data-bs-toggle="tooltip" title="Public"><span>Public</span></div>';
            }
        }
        $First_td = '<div class="star_box d-inline-block">' . $pbtn . '</div>';



        // Title Action
        $title_action = '';
        if ($permit['canpagesedit']) {
            $Quickedit_startDate = date('Y-m-d H:i', strtotime($value->dtDateTime));
            $Quickedit_endDate = !empty($value->dtEndDateTime) ? date('Y-m-d H:i', strtotime($value->dtEndDateTime)) : 'No Expiry';

            if ($value->chrLock != 'Y') {
                if (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y') {
                    if (isset($value->alias->varAlias) && $value->alias->varAlias != "home") {
                        if (Config::get('Constant.DEFAULT_QUICK') == 'Y') {
                            $title_action .= '<span class="show-hover"><a data-bs-toggle="tooltip" data-bs-placement="bottom" title="Quick Edit" href=\'javascript:void(0);\' data-toggle=\'modal\' data-target=\'#modalForm\' aria-label=\'Quick edit\' onclick=\'Quickeditfun("' . $value->id . '","' . $value->varTitle . '","' . $value->intSearchRank . '","' . $Quickedit_startDate . '","' . $Quickedit_endDate . '","P")\'><i class="ri-edit-2-line fs-16"></i></a></span>';
                        }
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



        // All Actions
        $actions = '<div class="dropdown">';
        $actions .= '<a href="javascript:void(0);" role="button" id="dropdownMenuLink'.$value->id.'" data-bs-toggle="dropdown" aria-expanded="false" class=""><i class="ri-more-fill"></i></a>';
        $actions .= '<ul class="dropdown-menu" aria-labelledby="dropdownMenuLink'.$value->id.'">';
        $log = '';
            // Edit
            if ($permit['canpagesedit']) {
                $actions .= '<li><a data-bs-toggle="tooltip" data-bs-placement="left" class="dropdown-item" title = "' . trans('cmspage::template.common.edit') . '" href = "' . route('powerpanel.pages.edit', array('alias' => $value->id)) . '?tab=P"><i class = "ri-pencil-line"></i>&nbsp;&nbsp;Edit</a></li>';
            }

            // Trash
            if ($permit['canpagesdelete'] || (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y')) {
                if (isset($value->varTitle) && strtolower($value->varTitle) != 'home') {
                    if (isset($value->modules->varModuleName) && $value->modules->varModuleName == 'pages') {
                        if (Config::get('Constant.DEFAULT_TRASH') == 'Y') {
                            $actions .= '<li><a data-bs-toggle="tooltip" data-bs-placement="left" title = "Trash" href = \'javascript:void(0);\' onclick=\'Trashfun("' . $value->id . '")\' class="dropdown-item red" data-tab="P"><i class="ri-delete-bin-line"></i>&nbsp;&nbsp;Trash</a></li>';
                        }
                    }
                }
            }

            // Log History , Locked-UnLock
            $logurl = url('powerpanel/log?id=' . $value->id . '&mid=' . Config::get('Constant.MODULE.ID'));
            $log = '';
            if ($value->chrLock != 'Y') {
                if (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y') {
                    $log .= $actions;
                    if ($permit['canloglist']) {
                        $log .= "<li><a data-bs-toggle=\"tooltip\" data-bs-placement=\"left\" title=\"Log History\" class='log-grid dropdown-item' href=\"$logurl\"><i class=\"ri-time-line\"></i>&nbsp;&nbsp;Log History</a></li>";
                    }
                } else {
                    if ($actions == "") {
                        $actions = "---";
                    } else {
                        $actions = $actions;
                    }
                    $log .= $actions;
                }
            }

            // Restore
            if ($value->chrLock != 'Y') {
                if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                    if (Config::get('Constant.DEFAULT_TRASH') == 'Y') {
                        $log .= "<li><a data-bs-toggle=\"tooltip\" data-bs-placement=\"left\" title=\"Restore\" href='javascript:void(0);' onclick='Restorefun(\"$value->id\",\"T\")' class=\"dropdown-item\"><i class=\"ri-repeat-line\"></i>&nbsp;&nbsp;Restore</a></li>";
                    }
                }
            }
        $log .= "</ul></div>";

        if($permit['canpagesedit'] || $permit['canpagesdelete']){
            $log = $log;
        } else {
            $log = "-";
        }

        $records = array(
            $checkbox,
            '<div class="pages_title_div_row">' . $First_td . ' <span class="title-txt"> ' . $title . ' ' . $status . $statusdata . ' - ' . $sector . ' ' .$title_action. '</span></div>',
            $startDate,
            $publish_action,
            $log
        );
        return $records;
    }

    public function tableDataTrash($value, $ignoreModuleIds = false,$permit,$currentUserID)
    {

        // Checkbox
        $checkbox = '';
        if ((isset($value->modules->varModuleName) && $value->modules->varModuleName != 'pages')) {
            $checkbox = '<div class="checker"><a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="right" title="This is module page so can&#39;t be deleted."><i style = "color:red" class = "ri-spam-line fs-16"></i></a></div>';
        } else {
            if (isset($value->varTitle) && strtolower($value->varTitle) != 'home') {
                $checkbox = view('powerpanel.partials.checkbox', ['name'=>'delete', 'value'=>$value->id])->render();
            }
        }


        // StartDate
        if ($value->id != 1) {
            $startDate = $value->dtDateTime;
            $startDate = '<span align="left" data-bs-toggle="tooltip" data-bs-placement="bottom" title="'.date(Config::get("Constant.DEFAULT_DATE_FORMAT").' '.Config::get("Constant.DEFAULT_TIME_FORMAT"), strtotime($startDate)).'">'.date(Config::get('Constant.DEFAULT_DATE_FORMAT'), strtotime($startDate)).'</span>';
        } else {
            $startDate = '-';
        }


        // Title
        $title = $value->varTitle;


        // Public , Private , Password Protected
        $pubbtn = $value->chrPageActive;
        $pbtn = '';
        if (Config::get('Constant.DEFAULT_VISIBILITY') == 'Y') {
            if ($pubbtn == 'PU') {
                $pbtn = '<div class="pub_status publicdiv fs-16" data-bs-toggle="tooltip" title="Public"><span>Public</span></div>';
            } else if ($pubbtn == 'PR') {
                $pbtn = '<div class="pub_status privatediv fs-16" data-bs-toggle="tooltip" title="Private"><span>Private</span></div>';
            } else if ($pubbtn == 'PP') {
                $pbtn = '<div class="pub_status passworddiv fs-16" data-bs-toggle="tooltip" title="Password Protected"><span>Password Protected</span></div>';
            } else {
                $pbtn = '<div class="pub_status publicdiv fs-16" data-bs-toggle="tooltip" title="Public"><span>Public</span></div>';
            }
        }
        $First_td = '<div class="star_box d-inline-block">' . $pbtn . '</div>';



        // Title Action
        $title_action = '';
        if ($permit['canpagesedit']) {
            $Quickedit_startDate = date('Y-m-d H:i', strtotime($value->dtDateTime));
            $Quickedit_endDate = !empty($value->dtEndDateTime) ? date('Y-m-d H:i', strtotime($value->dtEndDateTime)) : 'No Expiry';

            if ($value->chrLock != 'Y') {
                if (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y') {
                    if (isset($value->alias->varAlias) && $value->alias->varAlias != "home") {
                        if (Config::get('Constant.DEFAULT_QUICK') == 'Y') {
                            $title_action .= '<span class="show-hover"><a data-bs-toggle="tooltip" data-bs-placement="bottom" title="Quick Edit" href=\'javascript:void(0);\' data-toggle=\'modal\' data-target=\'#modalForm\' aria-label=\'Quick edit\' onclick=\'Quickeditfun("' . $value->id . '","' . $value->varTitle . '","' . $value->intSearchRank . '","' . $Quickedit_startDate . '","' . $Quickedit_endDate . '","P")\'><i class="ri-edit-2-line fs-16"></i></a></span>';
                        }
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



        // All Actions
        $actions = '<div class="dropdown">';
        $actions .= '<a href="javascript:void(0);" role="button" id="dropdownMenuLink'.$value->id.'" data-bs-toggle="dropdown" aria-expanded="false" class=""><i class="ri-more-fill"></i></a>';
        $actions .= '<ul class="dropdown-menu" aria-labelledby="dropdownMenuLink'.$value->id.'">';
        $log = '';
            // Edit
            if ($permit['canpagesedit']) {
                $actions .= '<li><a data-bs-toggle="tooltip" data-bs-placement="left" class="dropdown-item" title="' . trans('cmspage::template.common.edit') . '" href = "' . route('powerpanel.pages.edit', array('alias' => $value->id)) . '?tab=P"><i class = "ri-pencil-line"></i>&nbsp;&nbsp;Edit</a></li>';
            }

            // Delete
            if ($permit['canpagesdelete'] || (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y')) {
                if (isset($value->varTitle) && strtolower($value->varTitle) != 'home') {
                    if (isset($value->modules->varModuleName) && $value->modules->varModuleName == 'pages') {
                        if (Config::get('Constant.DEFAULT_TRASH') == 'Y') {
                            $actions .= '<li><a data-bs-toggle="tooltip" data-bs-placement="left" title="Delete" data-controller = "pages" data-alias = "' . $value->id . '"  data-tab = "T" class="dropdown-item red delete"><i class="ri-delete-bin-line"></i>&nbsp;&nbsp;Delete</a></li>';
                        }
                    }
                }
            }

            // Restore
            $log = '';
            if ($value->chrLock != 'Y') {
                if (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y') {
                    $log .= $actions;
                } else {
                    if ($actions == "") {
                        $actions = "---";
                    } else {
                        $actions = $actions;
                    }
                    $log .= $actions;
                }
            }

            if ($value->chrLock != 'Y') {
                if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                    if (Config::get('Constant.DEFAULT_TRASH') == 'Y') {
                        $log .= "<li><a data-bs-toggle=\"tooltip\" data-bs-placement=\"left\" title=\"Restore\" href='javascript:void(0);' onclick='Restorefun(\"$value->id\",\"T\")' class=\"dropdown-item\"><i class=\"ri-repeat-line\"></i>&nbsp;&nbsp;Restore</a></li>";
                    }
                }
            }
        $log .= "</ul></div>";

        if($permit['canpagesedit'] || $permit['canpagesdelete']){
            $log = $log;
        } else {
            $log = "-";
        }

        $records = array(
            $checkbox,
            '<div class="pages_title_div_row">' . $First_td . ' <span class="title-txt"> ' . $title . ' ' . $status . $statusdata . ' - ' . $sector . ' ' .$title_action. '</span></div>',
            $startDate,
            $log
        );
        return $records;
    }



    public function recordHistory($data = false)
    {
        $startDate = date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($data->dtDateTime));
        $endDate = !empty($data->dtEndDateTime) ? date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($data->dtEndDateTime)) : 'No Expiry';

        if (isset($data->txtDescription) && $data->txtDescription != '') {
            //$desc = FrontPageContent_Shield::renderBuilder($data->txtDescription);
            //if(isset($desc['response']) && !empty($desc['response'])) {
            //$desc = $desc['response'];
            //}else{
            $desc = '---';
            //}
        } else {
            $desc = '---';
        }

        $returnHtml = '';
        $returnHtml .= '<table class="table table-hover align-middle table-nowrap hide-mobile">
                <thead class="table-light">
				<tr>
				<th align="left">' . trans('cmspage::template.common.title') . '</th>
				<th align="left">' . trans('cmspage::template.common.modulename') . '</th>
				<th align="left">' . trans('cmspage::template.common.content') . '</th>
                <th align="left">Start date</th>
                <th align="left">End date</th>
                <th align="left">Meta Title</th>
                <th align="left">Meta Description</th>
				<th align="left">' . trans('cmspage::template.common.publish') . '</th>
				</tr>
				</thead>
				<tbody>
				<tr>
				<td align="left">' . stripslashes($data->varTitle) . '</td>
				<td align="left">' . isset($data->modules->varModuleName) ? $data->modules->varModuleName : '-' . '</td>
				<td align="left">' . $desc . '</td>
                <td align="left">' . $startDate . '</td>
				<td align="left">' . $endDate . '</td>
				<td align="left">' . $data->varMetaTitle . '</td>
				<td align="left">' . $data->varMetaDescription . '</td>
				<td align="left">' . $data->chrPublish . '</td>
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
        if ($data->modules->varModuleName != $newdata->modules->varModuleName) {
            $modulecolor = 'style="background-color:#f5efb7"';
        } else {
            $modulecolor = '';
        }
        if ($data->txtDescription != $newdata->txtDescription) {
            $desccolor = 'style="background-color:#f5efb7"';
        } else {
            $desccolor = '';
        }
        if ($data->varMetaTitle != $newdata->varMetaTitle) {
            $metatitlecolor = 'style=background-color:#f5efb7"';
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
        if ($data->varMetaDescription != $newdata->varMetaDescription) {
            $varMetaDescriptioncolor = 'style="background-color:#f5efb7"';
        } else {
            $varMetaDescriptioncolor = '';
        }
        if ($data->varMetaTitle != $newdata->varMetaTitle) {
            $varMetaTitlecolor = 'style="background-color:#f5efb7"';
        } else {
            $varMetaTitlecolor = '';
        }

        if (isset($newdata->txtDescription) && $newdata->txtDescription != '') {
            //$desc = FrontPageContent_Shield::renderBuilder($newdata->txtDescription);

            // if(isset($desc['response']) && !empty($desc['response'])) {
            //     $desc = $desc['response'];
            // }else{
            $desc = '---';
            //}
        } else {
            $desc = '---';
        }
        $startDate = date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($newdata->dtDateTime));
        $endDate = !empty($newdata->dtEndDateTime) ? date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($newdata->dtEndDateTime)) : 'No Expiry';
        $returnHtml = '';
        $returnHtml .= '<table class="table table-hover align-middle table-nowrap hide-mobile">
                <thead class="table-light">
				<tr>
				<th align="left">' . trans('cmspage::template.common.title') . '</th>
				<th align="left">' . trans('cmspage::template.common.modulename') . '</th>
				<th align="left">' . trans('cmspage::template.common.content') . '</th>
                <th align="left">Start date</th>
                <th align="left">End date</th>
                <th align="left">Meta Title</th>
                <th align="left">Meta Description</th>
				<th align="left">' . trans('cmspage::template.common.publish') . '</th>
				</tr>
				</thead>
				<tbody>
				<tr>
				<td align="left" ' . $titlecolor . '>' . stripslashes($newdata->varTitle) . '</td>
				<td align="left" ' . $modulecolor . '>' . $newdata->modules->varModuleName . '</td>
				<td align="left" ' . $desccolor . '>' . $desc . '</td>
                <td align="left" ' . $DateTimecolor . '>' . $startDate . '</td>
                <td align="left" ' . $EndDateTimecolor . '>' . $endDate . '</td>
                <td align="left" ' . $varMetaTitlecolor . '>' . $newdata->varMetaTitle . '</td>
                <td align="left" ' . $varMetaDescriptioncolor . '>' . $newdata->varMetaDescription . '</td>
				<td align="left" ' . $Publishcolor . '>' . $newdata->chrPublish . '</td>
				</tr>
				</tbody>
				</table>';
        return $returnHtml;
    }

    public function flushCache()
    {
        Cache::forget('getPageByPageId');
    }

    public function addPreview()
    {

        $data = Request::input();
        $rules = array(
            'title' => 'required|max:160',
            'varMetaTitle' => 'max:500',
            'varMetaDescription' => 'max:500',
            'chrMenuDisplay' => 'required',
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

            if (isset($data['module']) && !empty($data['module'])) {
                $moduleCode = $data['module'];
            } else {
                $moduleCode = 3;
            }

            $cmsPageArr = [];
            $cmsPageArr['varTitle'] = stripslashes(trim($data['title']));
            $cmsPageArr['varSector'] = $data['sector'];
            $cmsPageArr['intFKModuleCode'] = $moduleCode;
            $cmsPageArr['txtDescription'] = $vsection;
            $cmsPageArr['chrPublish'] = $data['chrMenuDisplay'];
            $cmsPageArr['varMetaTitle'] = stripslashes(trim($data['varMetaTitle']));
            $cmsPageArr['varMetaDescription'] = stripslashes(trim($data['varMetaDescription']));
            $cmsPageArr['varTags'] = trim($data['tags']);
            $cmsPageArr['UserID'] = auth()->user()->id;

            $id = $data['previewId'];
            if (is_numeric($id) && !empty($id)) {

                $cmsPage = CmsPage::getRecordById($id);
                Alias::updateCmsPageAlias($cmsPage->intAliasId, $data['alias'], $data['sector']);
                
                $whereConditions = ['id' => $cmsPage->id];

                if ($this->currentUserRoleData->chrIsAdmin == 'Y') {

                    if ($cmsPage->fkMainRecord == '0') {

                        $cmsPageArr['chrIsPreview'] = 'Y';
                        $update = CommonModel::updateRecords($whereConditions, $cmsPageArr, false, 'Powerpanel\CmsPage\Models\CmsPage');
                        if ($update) {
                            $newCmsPageObj = CmsPage::getRecordById($cmsPage->id);
                            $whereConditions = ['txtPageUrl' => $data['oldAlias']];
                            if (Auth::user()->can('recent-updates-list')) {
                                $notificationArr = MyLibrary::notificationData($cmsPage->id, $newCmsPageObj);
                                RecentUpdates::setNotification($notificationArr);
                            }
                            Self::flushCache();
                        }

                    } else {
                        $cmsPage = '';
                        $data_child_record = Request::input();
                        if (Config::get('Constant.DEFAULT_VISUAL') == 'Y') {
                            if ($data_child_record['section'] != '[]') {
                                $vsection = $data_child_record['section'];
                            } else {
                                $vsection = '';
                            }
                        } else {
                            $vsection = $data_child_record['contents'];
                        }
                        $id = $data['previewId'];
                        $cmsPage = CmsPage::getRecordById($id);
                        $whereConditions = ['id' => $data_child_record['fkMainRecord']];
                        $cmsPageArr_child['varTitle'] = stripslashes(trim($data_child_record['title']));
                        $cmsPageArr_child['varSector'] = $data_child_record['sector'];
                        $cmsPageArr_child['intFKModuleCode'] = trim($data_child_record['module']);
                        $cmsPageArr_child['txtDescription'] = trim($vsection);
                        $cmsPageArr_child['varMetaTitle'] = stripslashes(trim($data_child_record['varMetaTitle']));
                        $cmsPageArr_child['varMetaDescription'] = stripslashes(trim($data_child_record['varMetaDescription']));
                        $cmsPageArr_child['chrAddStar'] = 'N';
                        $cmsPageArr_child['chrPublish'] = trim($data_child_record['chrMenuDisplay']);
                        $cmsPageArr['chrIsPreview'] = 'Y';
                        $update = CommonModel::updateRecords($whereConditions, $cmsPageArr_child, false, 'Powerpanel\CmsPage\Models\CmsPage');
                        $whereConditions_ApproveN = ['fkMainRecord' => $data_child_record['fkMainRecord']];
                        $updateToApproveN = [
                            'chrApproved' => 'N',
                            'intApprovedBy' => '0',
                        ];
                        CommonModel::updateRecords($whereConditions_ApproveN, $updateToApproveN, false, 'Powerpanel\CmsPage\Models\CmsPage');
                        $whereConditionsApprove = ['id' => $id, 'chrMain' => 'N'];
                        $updateToApprove = [
                            'chrApproved' => 'Y',
                            'chrRollBack' => 'Y',
                            'intApprovedBy' => auth()->user()->id,
                        ];
                        CommonModel::updateRecords($whereConditionsApprove, $updateToApprove, false, 'Powerpanel\CmsPage\Models\CmsPage');
                    }

                } else {

                    $cmsPageArr['intAliasId'] = MyLibrary::insertAlias($data['alias'], false, 'Y', $data['sector']);
                    $cmsPageArr['chrMain'] = 'N';
                    $cmsPageArr['chrIsPreview'] = 'Y';
                    $cmsPageArr['fkMainRecord'] = $cmsPage->id;
                    $id = CommonModel::addRecord($cmsPageArr, 'Powerpanel\CmsPage\Models\CmsPage');
                    $whereConditionsAddstar = ['id' => $cmsPage->id];
                    $updateAddStar = [
                        'chrAddStar' => 'Y',
                    ];
                    CommonModel::updateRecords($whereConditionsAddstar, $updateAddStar, false, 'Powerpanel\CmsPage\Models\CmsPage');
                }

            } else {
                $cmsPageArr['chrMain'] = 'Y';
                $cmsPageArr['intAliasId'] = MyLibrary::insertAlias($data['alias'], false, 'Y', $data['sector']);
                $cmsPageArr['created_at'] = Carbon::now();
                $cmsPageArr['updated_at'] = Carbon::now();
                $cmsPageArr['chrIsPreview'] = 'Y';
                $id = CommonModel::addRecord($cmsPageArr, 'Powerpanel\CmsPage\Models\CmsPage');

            }

            return json_encode(array('status' => $id, 'alias' => $data['alias'], 'message' => trans('cmspage::template.pageModule.pageUpdate')));

        } else {
            return json_encode(array('status' => 'error', 'message' => $validator->errors()));
        }
    }

    public function sharePage(Request $request) {
        $returnArray = array("success" => "0", "msg" => "something Went Wrong");
        $data = Request::all();
        $messsages = array(
            'email.required' => 'Email is required'
        );
        $rules = array(
            'email' => 'required'
        );

        $validator = Validator::make($data, $rules, $messsages);
        if ($validator->passes()) {
            $pageId = $data['pageId'];
            // $leadData = SubmitTickets::getRecordById($leadId);
            // $ticketUserId = $leadData->UserID;
            $mailReponse = Email_sender::sharePage($data);
            if ($mailReponse == true) {
                $whereConditions = ['id' => $pageId];

                $updateLeadFields['chrPageActive'] = $data['pageActive'];
                $updateLeadFields['varPassword'] = $data['password'];
                $update = CommonModel::updateRecords($whereConditions, $updateLeadFields, false, 'Powerpanel\CmsPage\Models\CmsPage');

                $returnArray = array("success" => "1", "msg" => "Page shared on your mail");
            } else {
                $returnArray = array("success" => "0", "msg" => "Falied to share page, Please try again later.");
            }
        } else {
            $returnArray = array("success" => "0", "msg" => "Please fill required fields");
        }

        echo json_encode($returnArray);
        exit;
    }

    public function Template_Listing()
    {
        $record = Request::input();
        $pagedata = DB::table('visultemplate')
            ->select('*')
            ->where('id', '=', $record['id'])
            ->first();
        if ($record['temp'] == 'Y') {
            $temp = 'Y';
        } else {
            $temp = 'N';
        }
        $response = view('powerpanel.partials.pagetemplatesections', ['sections' => json_decode($pagedata->txtDesc), 'contentavalibale' => $temp])->render();
        return $response;
    }

    public function FormBuilder_Listing()
    {
        $record = Request::input();
        $pagedata = DB::table('form_builder')
            ->select('*')
            ->where('id', '=', $record['id'])
            ->first();
        if ($record['temp'] == 'Y') {
            $temp = 'Y';
        } else {
            $temp = 'N';
        }
        if ($record['temp'] == 'F') {
            $temp = 'F';
            $response = view('powerpanel.partials.pageformbuilderPartitionsections', ['sections' => [$pagedata->id, $pagedata->varName], 'contentavalibale' => $temp])->render();
        } else {
            $response = view('powerpanel.partials.pageformbuildersections', ['sections' => [$pagedata->id, $pagedata->varName], 'contentavalibale' => $temp])->render();
        }
        return $response;
    }

    public function rollBackRecord(Request $request)
    {

        $message = 'Previous record is not available';
        $requestArr = Request::all();
        $request = (object) $requestArr;

        $previousRecord = CmsPage::getPreviousRecordByMainId($request->id);
        if (!empty($previousRecord)) {

            $main_id = $previousRecord->fkMainRecord;
            $request->id = $previousRecord->id;
            $request->main_id = $main_id;

            $message = CmsPage::approved_data_Listing($request);

            /* notification for user to record approved */
            $blogs = CmsPage::getRecordForLogById($previousRecord->id);
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
            $newBlogObj = CmsPage::getRecordForLogById($main_id);
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
