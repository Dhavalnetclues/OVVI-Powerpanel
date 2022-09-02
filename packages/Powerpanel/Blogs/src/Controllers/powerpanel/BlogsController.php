<?php

namespace Powerpanel\Blogs\Controllers\Powerpanel;

use App\Alias;
use App\CommonModel;
use App\Helpers\AddImageModelRel;
use App\Helpers\FrontPageContent_Shield;
use App\Helpers\MyLibrary;
use App\Helpers\resize_image;
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
use Powerpanel\BlogCategory\Models\BlogCategory;
use Powerpanel\Blogs\Models\Blogs;
use Powerpanel\RoleManager\Models\Role_user;
use Powerpanel\Workflow\Models\Comments;
use Powerpanel\Workflow\Models\Workflow;
use Powerpanel\Workflow\Models\WorkflowLog;
use Request;
use Validator;

class BlogsController extends PowerpanelController {

    /**
     * Create a new controller instance.
     * @return void
     */
    public function __construct() {
        parent::__construct();
        if (isset($_COOKIE['locale'])) {
            app()->setLocale($_COOKIE['locale']);
        }
        $this->MyLibrary = new MyLibrary();
        $this->CommonModel = new CommonModel();
        $this->Alias = new Alias();
    }


    public function index() {

        $userIsAdmin = false;
        if (isset($this->currentUserRoleData) && !empty($this->currentUserRoleData)) {
            if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                $userIsAdmin = true;
            }
        } else {
            $userIsAdmin = true;
        }

        $total = Blogs::getRecordCount();
        $approvalTotalRecords = Blogs::getRecordCountListApprovalTab(false, false, $userIsAdmin, array(), $this->currentUserRoleSector);
        $draftTotalRecords = Blogs::getRecordCountforListDarft(false, true, $userIsAdmin, array(), $this->currentUserRoleSector);
        $trashTotalRecords = Blogs::getRecordCountforListTrash(false, true, $userIsAdmin, array(), $this->currentUserRoleSector);
        $favoriteTotalRecords = Blogs::getRecordCountforListFavorite(false, true, $userIsAdmin, array(), $this->currentUserRoleSector);

        $blogCategory = BlogCategory::getCatWithParent();
        $this->breadcrumb['title'] = trans('blogs::template.blogsModule.manageBlogs');
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

        return view('blogs::powerpanel.index', ['userIsAdmin' => $userIsAdmin, 'iTotalRecords' => $total, 'breadcrumb' => $this->breadcrumb, 'blogCategory' => $blogCategory, 'approvalTotalRecords' => $approvalTotalRecords, 'draftTotalRecords' => $draftTotalRecords, 'trashTotalRecords' => $trashTotalRecords, 'favoriteTotalRecords' => $favoriteTotalRecords, 'settingarray' => $settingarray]);
    }


    public function get_list() {

        $filterArr = [];
        $records = array();
        $records["data"] = array();
        $filterArr['orderColumnNo'] = (!empty(Request::input('order')[0]['column']) ? Request::input('order')[0]['column'] : '');
        $filterArr['orderByFieldName'] = (!empty(Request::input('columns')[$filterArr['orderColumnNo']]['name']) ? Request::input('columns')[$filterArr['orderColumnNo']]['name'] : '');
        $filterArr['orderTypeAscOrDesc'] = (!empty(Request::input('order')[0]['dir']) ? Request::input('order')[0]['dir'] : '');
        $filterArr['statusFilter'] = !empty(Request::input('statusValue')) ? Request::input('statusValue') : '';
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

        $ignoreId = [];
        $arrResults = Blogs::getRecordList($filterArr, $isAdmin, $ignoreId, $this->currentUserRoleSector);
        $iTotalRecords = Blogs::getRecordCountforList($filterArr, true, $isAdmin, $ignoreId, $this->currentUserRoleSector);

        if (!empty($arrResults)) {
            $currentUserID = auth()->user()->id;
            $permit = [
                'canblogsedit' => Auth::user()->can('blogs-edit'),
                'canblogspublish' => Auth::user()->can('blogs-publish'),
                'canblogsdelete' => Auth::user()->can('blogs-delete'),
                'canblogsreviewchanges' => Auth::user()->can('blogs-reviewchanges'),
                'canloglist' => Auth::user()->can('log-list'),
            ];

            foreach ($arrResults as $key => $value) {
                if (!in_array($value->id, $ignoreId)) {
                    $records['data'][] = $this->tableData($value, $permit, $currentUserID);
                }
            }
        }

        $NewRecordsCount = Blogs::getNewRecordsCount($isAdmin, $this->currentUserRoleSector);
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

    public function get_list_New() {

        $filterArr = [];
        $records = array();
        $records["data"] = array();
        $filterArr['orderColumnNo'] = (!empty(Request::input('order')[0]['column']) ? Request::input('order')[0]['column'] : '');
        $filterArr['orderByFieldName'] = (!empty(Request::input('columns')[$filterArr['orderColumnNo']]['name']) ? Request::input('columns')[$filterArr['orderColumnNo']]['name'] : '');
        $filterArr['orderTypeAscOrDesc'] = (!empty(Request::input('order')[0]['dir']) ? Request::input('order')[0]['dir'] : '');
        $filterArr['customFilterIdentity'] = !empty(Request::get('customFilterIdentity')) ? Request::get('customFilterIdentity') : '';
        $filterArr['statusFilter'] = !empty(Request::input('statusValue')) ? Request::input('statusValue') : '';
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

        $ignoreId = [];
        $arrResults = Blogs::getRecordList_tab1($filterArr, $isAdmin, $ignoreId, $this->currentUserRoleSector);
        $iTotalRecords = Blogs::getRecordCountListApprovalTab($filterArr, true, $isAdmin, $ignoreId, $this->currentUserRoleSector);

        if (!empty($arrResults)) {
            $currentUserID = auth()->user()->id;
            $permit = [
                'canblogsedit' => Auth::user()->can('blogs-edit'),
                'canblogspublish' => Auth::user()->can('blogs-publish'),
                'canblogsdelete' => Auth::user()->can('blogs-delete'),
                'canblogsreviewchanges' => Auth::user()->can('blogs-reviewchanges'),
                'canloglist' => Auth::user()->can('log-list'),
            ];

            foreach ($arrResults as $key => $value) {
                if (!in_array($value->id, $ignoreId)) {
                    $records['data'][] = $this->tableData_tab1($value, $permit, $currentUserID);
                }
            }
        }

        $NewRecordsCount = Blogs::getNewRecordsCount($isAdmin, $this->currentUserRoleSector);
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

    public function get_list_favorite() {

        $filterArr = [];
        $records = array();
        $records["data"] = array();
        $filterArr['orderColumnNo'] = (!empty(Request::input('order')[0]['column']) ? Request::input('order')[0]['column'] : '');
        $filterArr['orderByFieldName'] = (!empty(Request::input('columns')[$filterArr['orderColumnNo']]['name']) ? Request::input('columns')[$filterArr['orderColumnNo']]['name'] : '');
        $filterArr['orderTypeAscOrDesc'] = (!empty(Request::input('order')[0]['dir']) ? Request::input('order')[0]['dir'] : '');
        $filterArr['customFilterIdentity'] = !empty(Request::get('customFilterIdentity')) ? Request::get('customFilterIdentity') : '';
        $filterArr['statusFilter'] = !empty(Request::input('statusValue')) ? Request::input('statusValue') : '';
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

        $ignoreId = [];
        $arrResults = Blogs::getRecordListFavorite($filterArr, $isAdmin, $ignoreId, $this->currentUserRoleSector);
        $iTotalRecords = Blogs::getRecordCountforListFavorite($filterArr, true, $isAdmin, $ignoreId, $this->currentUserRoleSector);

        if (!empty($arrResults)) {
            $currentUserID = auth()->user()->id;
            $permit = [
                'canblogsedit' => Auth::user()->can('blogs-edit'),
                'canblogspublish' => Auth::user()->can('blogs-publish'),
                'canblogsdelete' => Auth::user()->can('blogs-delete'),
                'canblogsreviewchanges' => Auth::user()->can('blogs-reviewchanges'),
                'canloglist' => Auth::user()->can('log-list'),
            ];

            foreach ($arrResults as $key => $value) {
                if (!in_array($value->id, $ignoreId)) {
                    $records['data'][] = $this->tableDataFavorite($value, $permit, $currentUserID);
                }
            }
        }

        $NewRecordsCount = Blogs::getNewRecordsCount($isAdmin, $this->currentUserRoleSector);
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

    public function get_list_draft() {

        $filterArr = [];
        $records = array();
        $records["data"] = array();
        $filterArr['orderColumnNo'] = (!empty(Request::input('order')[0]['column']) ? Request::input('order')[0]['column'] : '');
        $filterArr['orderByFieldName'] = (!empty(Request::input('columns')[$filterArr['orderColumnNo']]['name']) ? Request::input('columns')[$filterArr['orderColumnNo']]['name'] : '');
        $filterArr['orderTypeAscOrDesc'] = (!empty(Request::input('order')[0]['dir']) ? Request::input('order')[0]['dir'] : '');
        $filterArr['customFilterIdentity'] = !empty(Request::get('customFilterIdentity')) ? Request::get('customFilterIdentity') : '';
        $filterArr['statusFilter'] = !empty(Request::input('statusValue')) ? Request::input('statusValue') : '';
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

        $ignoreId = [];
        $arrResults = Blogs::getRecordListDraft($filterArr, $isAdmin, $ignoreId, $this->currentUserRoleSector);
        $iTotalRecords = Blogs::getRecordCountforListDarft($filterArr, true, $isAdmin, $ignoreId, $this->currentUserRoleSector);

        if (!empty($arrResults)) {
            $currentUserID = auth()->user()->id;
            $permit = [
                'canblogsedit' => Auth::user()->can('blogs-edit'),
                'canblogspublish' => Auth::user()->can('blogs-publish'),
                'canblogsdelete' => Auth::user()->can('blogs-delete'),
                'canblogsreviewchanges' => Auth::user()->can('blogs-reviewchanges'),
                'canloglist' => Auth::user()->can('log-list'),
            ];

            foreach ($arrResults as $key => $value) {
                if (!in_array($value->id, $ignoreId)) {
                    $records['data'][] = $this->tableDataDraft($value, $permit, $currentUserID);
                }
            }
        }

        $NewRecordsCount = Blogs::getNewRecordsCount($isAdmin, $this->currentUserRoleSector);
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

    public function get_list_trash() {

        $filterArr = [];
        $records = array();
        $records["data"] = array();
        $filterArr['orderColumnNo'] = (!empty(Request::input('order')[0]['column']) ? Request::input('order')[0]['column'] : '');
        $filterArr['orderByFieldName'] = (!empty(Request::input('columns')[$filterArr['orderColumnNo']]['name']) ? Request::input('columns')[$filterArr['orderColumnNo']]['name'] : '');
        $filterArr['orderTypeAscOrDesc'] = (!empty(Request::input('order')[0]['dir']) ? Request::input('order')[0]['dir'] : '');
        $filterArr['customFilterIdentity'] = !empty(Request::get('customFilterIdentity')) ? Request::get('customFilterIdentity') : '';
        $filterArr['statusFilter'] = !empty(Request::input('statusValue')) ? Request::input('statusValue') : '';
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

        $ignoreId = [];
        $arrResults = Blogs::getRecordListTrash($filterArr, $isAdmin, $ignoreId, $this->currentUserRoleSector);
        $iTotalRecords = Blogs::getRecordCountforListTrash($filterArr, true, $isAdmin, $ignoreId, $this->currentUserRoleSector);

        if (!empty($arrResults)) {
            $currentUserID = auth()->user()->id;
            $permit = [
                'canblogsedit' => Auth::user()->can('blogs-edit'),
                'canblogspublish' => Auth::user()->can('blogs-publish'),
                'canblogsdelete' => Auth::user()->can('blogs-delete'),
                'canblogsreviewchanges' => Auth::user()->can('blogs-reviewchanges'),
                'canloglist' => Auth::user()->can('log-list'),
            ];

            foreach ($arrResults as $key => $value) {
                if (!in_array($value->id, $ignoreId)) {
                    $records['data'][] = $this->tableDataTrash($value, $permit, $currentUserID);
                }
            }
        }

        $NewRecordsCount = Blogs::getNewRecordsCount($isAdmin, $this->currentUserRoleSector);
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

    /**
     * This method loads Blogs edit view
     * @param      Alias of record
     * @return  View
     * @since   2017-07-21
     * @author  NetQuick
     */
    public function edit($alias = false) {
        $module = Modules::getModule('blog-category');
        $category = BlogCategory::getCatWithParent($module->id);
        $templateData = array();
        $imageManager = true;
        $documentManager = true;
        $userIsAdmin = false;
        if (isset($this->currentUserRoleData) && !empty($this->currentUserRoleData)) {
            if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                $userIsAdmin = true;
            }
        } else {
            $userIsAdmin = true;
        }
        $sector = array('ofreg' => 'OFREG' , 'water' => 'WATER' , 'ict' => 'ICT' , 'energy' => 'ENERGY' , 'fuel' => 'FUEL');
        if (!is_numeric($alias)) {
            $total = Blogs::getRecordCount();
            if (auth()->user()->can('blogs-create') || $userIsAdmin) {
                $total = $total + 1;
            }
            $this->breadcrumb['title'] = trans('blogs::template.blogsModule.add');
            $this->breadcrumb['module'] = trans('blogs::template.blogsModule.manage');
            $this->breadcrumb['url'] = 'powerpanel/blogs';
            $this->breadcrumb['inner_title'] = "";
            $templateData['total'] = $total;
            $templateData['breadcrumb'] = $this->breadcrumb;
            $templateData['blogCategory'] = $category;
            $templateData['imageManager'] = $imageManager;
            $templateData['documentManager'] = $documentManager;
        } else {
            $id = $alias;
            $blogs = Blogs::getRecordById($id);
            if (empty($blogs)) {
                return redirect()->route('powerpanel.blogs.add');
            }
            if ($blogs->fkMainRecord != '0') {
                $blogs_highLight = Blogs::getRecordById($blogs->fkMainRecord);
                $templateData['blogs_highLight'] = $blogs_highLight;
                $metaInfo_highLight['varMetaTitle'] = $blogs_highLight['varMetaTitle'];
                $metaInfo_highLight['varMetaDescription'] = $blogs_highLight['varMetaDescription'];
                $metaInfo_highLight['varTags'] = $blogs_highLight['varTags'];
                
            } else {
                $templateData['blogs_highLight'] = "";
                $metaInfo_highLight['varMetaTitle'] = "";
                $metaInfo_highLight['varMetaDescription'] = "";
                $metaInfo_highLight['varTags'] = "";
            }

            $metaInfo = array(
                'varMetaTitle' => $blogs->varMetaTitle,
                'varMetaDescription' => $blogs->varMetaDescription,
                'varTags' => $blogs->varTags
            );

            if (method_exists($this->MyLibrary, 'getModulePageAliasByModuleName')) {
                $categorypagereocrdlink = MyLibrary::getModulePageAliasByModuleName('blogs');
            }
            if (method_exists($this->MyLibrary, 'getRecordAliasByModuleNameRecordId')) {
                $categoryRecordAlias = MyLibrary::getRecordAliasByModuleNameRecordId("blog-category", $blogs->intFKCategory);
            }
            if (!empty($categorypagereocrdlink)) {
                $varURL = $categorypagereocrdlink . '/' . $blogs->alias->varAlias;
            } else {
                $varURL = $blogs->alias->varAlias;
            }
            $metaInfo['varURL'] = $varURL;
            $this->breadcrumb['title'] = trans('blogs::template.blogsModule.edit');
            $this->breadcrumb['module'] = trans('blogs::template.blogsModule.manage');
            $this->breadcrumb['url'] = 'powerpanel/blogs';
            $this->breadcrumb['inner_title'] = $blogs->varTitle;
            $templateData['blogs'] = $blogs;
            $templateData['id'] = $id;
            $templateData['breadcrumb'] = $this->breadcrumb;
            $templateData['blogCategory'] = $category;
            $templateData['metaInfo'] = $metaInfo;
            $templateData['metaInfo_highLight'] = $metaInfo_highLight;
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
        $templateData['sector'] = $sector;
        return view('blogs::powerpanel.actions', $templateData);
    }


    public function handlePost(Request $request) {
        // echo 'hii';die;
        $approval = false;
        $userIsAdmin = false;
        if (isset($this->currentUserRoleData) && !empty($this->currentUserRoleData)) {
            if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                $userIsAdmin = true;
            }
        }
        $postArr = Request::all();
        $messsages = [
            'title.required' => 'Title field is required.',
            'category_id.required' => 'Category field is required.',
            'alias.required' => 'Alias field is required.',
            'varMetaTitle.required' => trans('blogs::template.blogsModule.metaTitle'),
            'varMetaDescription.required' => trans('blogs::template.blogsModule.metaDescription'),
            'short_description.required' => trans('blogs::template.blogsModule.shortDescription'),
            'img_id.required' => 'Image field is required.',
        ];
        $rules = [
            'title' => 'required|max:160|handle_xss|no_url',
            'category_id' => 'required',
            'chrMenuDisplay' => 'required',
            'alias' => 'required',
            'varMetaTitle' => 'required|max:160|handle_xss|no_url',
            'varMetaDescription' => 'required|max:200|handle_xss|no_url',
            'short_description' => 'handle_xss|no_url',
            'img_id' => 'required',
        ];
        $validator = Validator::make($postArr, $rules, $messsages);
        if ($validator->passes()) {
            $blogsArr = [];
            if (Config::get('Constant.DEFAULT_VISUAL') == 'Y') {
                if ($postArr['section'] != '[]') {
                    $vsection = $postArr['section'];
                } else {
                    $vsection = '';
                }
            } else {
                $vsection = $postArr['description'];
            }
            $module = Modules::getModuleById(Config::get('Constant.MODULE.ID'));
            if (isset($this->currentUserRoleData)) {
                $currentUserRoleData = $this->currentUserRoleData;
            }
            $id = Request::segment(3);
            $actionMessage = trans('blogs::template.blogsModule.updateMessage');
            if (is_numeric($id)) { #Edit post Handler=======
                $blogs = Blogs::getRecordForLogById($id);
                $updateBlogsFields = [];
                $updateBlogsFields['varTitle'] = stripslashes(trim($postArr['title']));
                $updateBlogsFields['intFKCategory'] = trim($postArr['category_id']);
                $updateBlogsFields['txtDescription'] = $vsection;
                $updateBlogsFields['varShortDescription'] = stripslashes(trim($postArr['short_description']));
                $updateBlogsFields['varMetaTitle'] = stripslashes(trim($postArr['varMetaTitle']));
                $updateBlogsFields['varMetaDescription'] = stripslashes(trim($postArr['varMetaDescription']));
                $updateBlogsFields['varTags'] = trim($postArr['tags']);

                if (Config::get('Constant.CHRSearchRank') == 'Y') {
                    $updateBlogsFields['intSearchRank'] = $postArr['search_rank'];
                }
                $updateBlogsFields['varSector'] = $postArr['sector'];
                $updateBlogsFields['dtDateTime'] = !empty($postArr['start_date_time']) ? date('Y-m-d H:i:s', strtotime($postArr['start_date_time'])) : date('Y-m-d H:i:s');
                $updateBlogsFields['dtEndDateTime'] = !empty($postArr['end_date_time']) ? date('Y-m-d H:i:s', strtotime($postArr['end_date_time'])) : null;
                $updateBlogsFields['UserID'] = auth()->user()->id;
                if ($postArr['chrMenuDisplay'] == 'D') {
                    $updateBlogsFields['chrDraft'] = 'D';
                    $updateBlogsFields['chrPublish'] = 'N';
                } else {
                    $updateBlogsFields['chrDraft'] = 'N';
                    $updateBlogsFields['chrPublish'] = $postArr['chrMenuDisplay'];
                }
                if (isset($postArr['chrPageActive']) && $postArr['chrPageActive'] != '') {
                    $updateBlogsFields['chrPageActive'] = $postArr['chrPageActive'];
                }
                if (isset($postArr['chrPageActive']) && $postArr['chrPageActive'] == 'PP') {
                    $updateBlogsFields['varPassword'] = $postArr['new_password'];
                } else {
                    $updateBlogsFields['varPassword'] = '';
                }
                if ($postArr['chrMenuDisplay'] == 'D') {
                    $addlog = Config::get('Constant.UPDATE_DRAFT');
                } else {
                    $addlog = '';
                }
                $updateBlogsFields['fkIntImgId'] = !empty($postArr['img_id']) ? $postArr['img_id'] : null;
                $whereConditions = ['id' => $id];
                if ($blogs->chrLock == 'Y' && auth()->user()->id != $blogs->LockUserID) {
                    if ($this->currentUserRoleData->chrIsAdmin != 'Y') {
                        $lockedUserData = User::getRecordById($blogs->LockUserID, true);
                        $lockedUserName = 'someone';
                        if (!empty($lockedUserData)) {
                            $lockedUserName = $lockedUserData->name;
                        }
                        $actionMessage = "This record has been locked by " . $lockedUserName . ".";
                        return redirect()->route('powerpanel.blogs.index')->with('message', $actionMessage);
                    }
                }
                if (File::exists(app_path() . '/Workflow.php') != null || File::exists(base_path() . '/packages/Powerpanel/Workflow/src/Models/Workflow.php') != null) {
                    if (!$userIsAdmin) {
                        $userRole = $currentUserRoleData->id;
                    } else {
                        $userRoleData = Role_user::getUserRoleByUserId($blogs->UserID);
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
                        if ($postArr['oldAlias'] != $postArr['alias']) {
                            Alias::updateAlias($postArr['oldAlias'], $postArr['alias']);
                        }
                        if ((int) $blogs->fkMainRecord === 0 || empty($workFlowByCat->varUserId)) {
                            $update = CommonModel::updateRecords($whereConditions, $updateBlogsFields, false, 'Powerpanel\Blogs\Models\Blogs');
                            if ($update) {
                                if ($id > 0 && !empty($id)) {
                                    $logArr = MyLibrary::logData($id);
                                    if (Auth::user()->can('log-advanced')) {
                                        $newBlogObj = Blogs::getRecordForLogById($id);
                                        $oldRec = $this->recordHistory($blogs);
                                        $newRec = $this->newrecordHistory($blogs, $newBlogObj);
                                        $logArr['old_val'] = $oldRec;
                                        $logArr['new_val'] = $newRec;
                                    }
                                    $logArr['varTitle'] = trim($postArr['title']);
                                    Log::recordLog($logArr);
                                    if (Auth::user()->can('recent-updates-list')) {
                                        if (!isset($newBlogObj)) {
                                            $newBlogObj = Blogs::getRecordForLogById($id);
                                        }
                                        $notificationArr = MyLibrary::notificationData($id, $newBlogObj);
                                        RecentUpdates::setNotification($notificationArr);
                                    }
                                    self::flushCache();
                                    if (isset($postArr['saveandexit']) && $postArr['saveandexit'] == 'approvesaveandexit') {
                                        $actionMessage = trans('blogs::template.common.recordApprovalMessage');
                                    } else {
                                        $actionMessage = trans('blogs::template.blogsModule.updateMessage');
                                    }
                                }
                            }
                        } else {
                            $updateModuleFields = $updateBlogsFields;
                            $this->insertApprovedRecord($updateModuleFields, $postArr, $id);
                            if (isset($postArr['saveandexit']) && $postArr['saveandexit'] == 'approvesaveandexit') {
                                $actionMessage = trans('blogs::template.common.recordApprovalMessage');
                            } else {
                                $actionMessage = trans('blogs::template.blogsModule.updateMessage');
                            }
                            $approval = $id;
                        }
                    } else {
                        if ($workFlowByCat->charNeedApproval == 'Y') {
                            $approvalObj = $this->insertApprovalRecord($blogs, $postArr, $updateBlogsFields);
                            if (isset($postArr['saveandexit']) && $postArr['saveandexit'] == 'approvesaveandexit') {
                                $actionMessage = trans('blogs::template.common.recordApprovalMessage');
                            } else {
                                $actionMessage = trans('blogs::template.blogsModule.updateMessage');
                            }
                            $approval = $approvalObj->id;
                        }
                    }
                } else {
                    $update = CommonModel::updateRecords($whereConditions, $updateBlogsFields, false, 'Powerpanel\Blogs\Models\Blogs');
                    $actionMessage = trans('blogs::template.blogsModule.updateMessage');
                }
            } else { #Add post Handler=======
                $blogsArr['dtDateTime'] = !empty($postArr['start_date_time']) ? date('Y-m-d H:i:s', strtotime($postArr['start_date_time'])) : date('Y-m-d H:i:s');
                $blogsArr['dtEndDateTime'] = !empty($postArr['end_date_time']) ? date('Y-m-d H:i:s', strtotime($postArr['end_date_time'])) : null;
                $postArr['txtdescription'] = $vsection;
                if (File::exists(app_path() . '/Workflow.php') != null || File::exists(base_path() . '/packages/Powerpanel/Workflow/src/Models/Workflow.php') != null) {
                    $workFlowByCat = Workflow::getRecordByCategoryId($module->intFkGroupCode, $currentUserRoleData->id, Config::get('Constant.MODULE.ID'));
                }
                if (!empty($workFlowByCat->varUserId) && $workFlowByCat->chrNeedAddPermission == 'Y' && !$userIsAdmin) {
                    $blogsArr['chrPublish'] = 'N';
                    $blogsArr['chrDraft'] = 'N';
                    $blogsObj = $this->insertNewRecord($postArr, $blogsArr);
                    if ($postArr['chrMenuDisplay'] == 'D') {
                        $blogsArr['chrDraft'] = 'D';
                    }
                    $blogsArr['chrPublish'] = 'Y';
                    $approvalObj = $this->insertApprovalRecord($blogsObj, $postArr, $blogsArr);
                    $approval = $blogsObj->id;
                } else {
                    $blogsObj = $this->insertNewRecord($postArr, $blogsArr);
                    $approval = $blogsObj->id;
                }
                if (isset($postArr['saveandexit']) && $postArr['saveandexit'] == 'approvesaveandexit') {
                    $actionMessage = trans('blogs::template.common.recordApprovalMessage');
                } else {
                    $actionMessage = trans('blogs::template.blogsModule.addMessage');
                }
                $actionMessage = trans('blogs::template.blogsModule.addMessage');
                $id = $blogsObj->id;
            }
            AddImageModelRel::sync(explode(',', $postArr['img_id']), $id, $approval);
            if (method_exists($this->Alias, 'updatePreviewAlias')) {
                Alias::updatePreviewAlias($postArr['alias'], 'N');
            }
            if ((!empty(Request::get('saveandexit')) && Request::get('saveandexit') == 'saveandexit') || !$userIsAdmin) {
                if ($postArr['chrMenuDisplay'] == 'D') {
                    return redirect()->route('powerpanel.blogs.index', 'tab=D')->with('message', $actionMessage);
                } else {
                    return redirect()->route('powerpanel.blogs.index')->with('message', $actionMessage);
                }
            } else {
                return redirect()->route('powerpanel.blogs.edit', $id)->with('message', $actionMessage);
            }
        } else {
            return Redirect::back()->withErrors($validator)->withInput();
        }
    }

    public function addPreview() {
        $postArr = Request::post();
        $id = $postArr['previewId'];
        if (is_numeric($id)) { #Edit post Handler=======
            $blogs = Blogs::getRecordForLogById($id);
            $updateBlogsFields = [];
            if (Config::get('Constant.DEFAULT_VISUAL') == 'Y') {
                if ($postArr['section'] != '[]') {
                    $vsection = $postArr['section'];
                } else {
                    $vsection = '';
                }
            } else {
                $vsection = $postArr['description'];
            }
            $updateBlogsFields['varTitle'] = stripslashes(trim($postArr['title']));
            $updateBlogsFields['intFKCategory'] = trim($postArr['category_id']);
            $updateBlogsFields['varShortDescription'] = stripslashes(trim($postArr['short_description']));
            $updateBlogsFields['txtDescription'] = $vsection;
            $updateBlogsFields['varMetaTitle'] = stripslashes(trim($postArr['varMetaTitle']));
            $updateBlogsFields['varMetaDescription'] = stripslashes(trim($postArr['varMetaDescription']));
            $updateBlogsFields['varTags'] = trim($postArr['tags']);
            if (Config::get('Constant.CHRSearchRank') == 'Y') {
                $updateBlogsFields['intSearchRank'] = $postArr['search_rank'];
            }
            $updateBlogsFields['chrPublish'] = $postArr['chrMenuDisplay'];
            $updateBlogsFields['dtDateTime'] = !empty($postArr['start_date_time']) ? date('Y-m-d H:i:s', strtotime($postArr['start_date_time'])) : date('Y-m-d H:i:s');
            $updateBlogsFields['dtEndDateTime'] = !empty($postArr['end_date_time']) ? date('Y-m-d H:i:s', strtotime($postArr['end_date_time'])) : null;
            $updateBlogsFields['fkIntImgId'] = !empty($postArr['img_id']) ? $postArr['img_id'] : null;
            $updateBlogsFields['UserID'] = auth()->user()->id;
            $updateBlogsFields['chrIsPreview'] = 'Y';
            $whereConditions = ['id' => $id];
            if ($postArr['oldAlias'] != $postArr['alias']) {
                Alias::updateAlias($postArr['oldAlias'], $postArr['alias']);
            }
            $update = CommonModel::updateRecords($whereConditions, $updateBlogsFields, false, 'Powerpanel\Blogs\Models\Blogs');
        } else {
            $blogsArr['dtDateTime'] = !empty($postArr['start_date_time']) ? date('Y-m-d H:i:s', strtotime($postArr['start_date_time'])) : date('Y-m-d H:i:s');
            $blogsArr['dtEndDateTime'] = !empty($postArr['end_date_time']) ? date('Y-m-d H:i:s', strtotime($postArr['end_date_time'])) : null;
            $blogsArr['chrIsPreview'] = 'Y';
            $id = $this->insertNewRecord($postArr, $blogsArr, 'Y')->id;
        }
        return json_encode(array('status' => $id, 'alias' => $postArr['alias'], 'message' => trans('template.pageModule.pageUpdate')));
    }

    public function insertApprovedRecord($updateModuleFields, $postArr, $id) {
        $whereConditions = ['id' => $postArr['fkMainRecord']];
        $updateModuleFields['chrAddStar'] = 'N';
        $updateModuleFields['chrPublish'] = trim($postArr['chrMenuDisplay']);
        $updateModuleFields['UserID'] = auth()->user()->id;
        $update = CommonModel::updateRecords($whereConditions, $updateModuleFields, false, 'Powerpanel\Blogs\Models\Blogs');
        $whereConditions_ApproveN = ['fkMainRecord' => $postArr['fkMainRecord']];
        $updateToApproveN = [
            'chrApproved' => 'N',
            'chrLetest' => 'N',
            'intApprovedBy' => '0',
        ];
        CommonModel::updateRecords($whereConditions_ApproveN, $updateToApproveN, false, 'Powerpanel\Blogs\Models\Blogs');
        $whereConditionsApprove = ['id' => $id, 'chrMain' => 'N'];
        $updateToApprove = [
            'chrApproved' => 'Y',
            'chrRollBack' => 'Y',
            'intApprovedBy' => auth()->user()->id,
        ];
        CommonModel::updateRecords($whereConditionsApprove, $updateToApprove, false, 'Powerpanel\Blogs\Models\Blogs');
        if ($postArr['chrMenuDisplay'] == 'D') {
            $addlog = Config::get('Constant.DRAFT_RECORD_APPROVED');
        } else {
            $addlog = Config::get('Constant.RECORD_APPROVED');
        }
        $newCmsPageObj = Blogs::getRecordForLogById($id);
        $logArr = MyLibrary::logData($id, false, $addlog);
        $logArr['varTitle'] = stripslashes($newCmsPageObj->varTitle);
        Log::recordLog($logArr);
        /* notification for user to record approved */
        $careers = Blogs::getRecordForLogById($id);
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
                $actionMessage = trans('blogs::template.blogsModule.updateMessage');
            }
        }
    }

    public function insertApprovalRecord($moduleObj, $postArr, $blogsArr) {
        $response = false;
        $blogsArr['chrMain'] = 'N';
        $blogsArr['chrLetest'] = 'Y';
        $blogsArr['fkMainRecord'] = $moduleObj->id;
        $blogsArr['varTitle'] = stripslashes(trim($postArr['title']));
        $blogsArr['intFKCategory'] = trim($postArr['category_id']);
        $blogsArr['intAliasId'] = MyLibrary::insertAlias($postArr['alias'], false, 'N');
        $blogsArr['varMetaTitle'] = stripslashes(trim($postArr['varMetaTitle']));
        if (Config::get('Constant.CHRSearchRank') == 'Y') {
            $blogsArr['intSearchRank'] = $postArr['search_rank'];
        }
        $blogsArr['varMetaDescription'] = stripslashes(trim($postArr['varMetaDescription']));
        $blogsArr['varTags'] = trim($postArr['tags']);
        $blogsArr['varShortDescription'] = stripslashes(trim($postArr['short_description']));
        if ($postArr['chrMenuDisplay'] == 'D') {
            $blogsArr['chrDraft'] = 'D';
            $blogsArr['chrPublish'] = 'N';
        } else {
            $blogsArr['chrDraft'] = 'N';
            $blogsArr['chrPublish'] = $postArr['chrMenuDisplay'];
        }
        if (isset($postArr['chrPageActive']) && $postArr['chrPageActive'] != '') {
            $blogsArr['chrPageActive'] = $postArr['chrPageActive'];
        }
        if (isset($postArr['chrPageActive']) && $postArr['chrPageActive'] == 'PP') {
            $blogsArr['varPassword'] = $postArr['new_password'];
        } else {
            $blogsArr['varPassword'] = '';
        }
        if (Config::get('Constant.CHRSearchRank') == 'Y') {
            $blogsArr['intSearchRank'] = $postArr['search_rank'];
        }
        if ($postArr['chrMenuDisplay'] == 'D') {
            $addlog = Config::get('Constant.DRAFT_SENT_FOR_APPROVAL');
        } else {
            $addlog = Config::get('Constant.SENT_FOR_APPROVAL');
        }
        $blogsArr['created_at'] = Carbon::now();
        $blogsArr['UserID'] = auth()->user()->id;
        $blogsArr['fkIntImgId'] = !empty($postArr['img_id']) ? $postArr['img_id'] : null;
        $blogsID = CommonModel::addRecord($blogsArr, 'Powerpanel\Blogs\Models\Blogs');
        if (!empty($blogsID)) {
            $id = $blogsID;
            WorkflowLog::addRecord([
                'fkModuleId' => Config::get('Constant.MODULE.ID'),
                'fkRecordId' => $moduleObj->id,
                'charApproval' => 'Y',
            ]);
            if (method_exists($this->MyLibrary, 'userNotificationData')) {
                $userNotificationArr = MyLibrary::userNotificationData(Config::get('Constant.MODULE.ID'));
                $userNotificationArr['fkRecordId'] = $moduleObj->id;
                $userNotificationArr['txtNotification'] = 'New approval request from ' . ucfirst(auth()->user()->name) . ' (Powerpanel\Blogs\Models\\' . ucfirst(Config::get('Constant.MODULE.NAME')) . ')';
                $userNotificationArr['fkIntUserId'] = Auth::user()->id;
                $userNotificationArr['chrNotificationType'] = 'A';
                UserNotification::addRecord($userNotificationArr);
            }
            $newBlogObj = Blogs::getRecordForLogById($id);
            $logArr = MyLibrary::logData($id, false, $addlog);
            $logArr['varTitle'] = $newBlogObj->varTitle;
            Log::recordLog($logArr);
            if (Auth::user()->can('recent-updates-list')) {
                $notificationArr = MyLibrary::notificationData($id, $newBlogObj);
                RecentUpdates::setNotification($notificationArr);
            }
            $response = $newBlogObj;
            self::flushCache();
            $actionMessage = trans('blogs::template.blogsModule.addMessage');
        }
        $whereConditionsAddstar = ['id' => $moduleObj->id];
        $updateAddStar = [
            'chrAddStar' => 'Y',
        ];
        CommonModel::updateRecords($whereConditionsAddstar, $updateAddStar, false, 'Powerpanel\Blogs\Models\Blogs');
        return $response;
    }

    public function insertNewRecord($postArr, $blogsArr, $preview = 'N') {
        $response = false;

        $blogsArr['chrMain'] = 'Y';
        $blogsArr['varTitle'] = stripslashes(trim($postArr['title']));
        $blogsArr['intFKCategory'] = trim($postArr['category_id']);
        $blogsArr['varSector'] = $postArr['sector'];
        $blogsArr['intAliasId'] = MyLibrary::insertAlias($postArr['alias'], false, $preview);
        $blogsArr['varShortDescription'] = stripslashes(trim($postArr['short_description']));

        if (Config::get('Constant.DEFAULT_VISUAL') == 'Y') {
            if ($postArr['section'] != '[]') {
                $vsection = $postArr['section'];
            } else {
                $vsection = '';
            }
        } else {
            $vsection = $postArr['description'];
        }
        $blogsArr['txtDescription'] = $vsection;
        $blogsArr['varMetaTitle'] = stripslashes(trim($postArr['varMetaTitle']));
        if (Config::get('Constant.CHRSearchRank') == 'Y') {
            $blogsArr['intSearchRank'] = $postArr['search_rank'];
        }
        $blogsArr['varMetaDescription'] = stripslashes(trim($postArr['varMetaDescription']));
        $blogsArr['varTags'] = trim($postArr['tags']);
        if ($postArr['chrMenuDisplay'] == 'D') {
            $blogsArr['chrDraft'] = 'D';
            $blogsArr['chrPublish'] = 'N';
        } else {
            $blogsArr['chrDraft'] = 'N';
        }
        if (isset($postArr['chrPageActive']) && $postArr['chrPageActive'] != '') {
            $blogsArr['chrPageActive'] = $postArr['chrPageActive'];
        }
        if (isset($postArr['chrPageActive']) && $postArr['chrPageActive'] == 'PP') {
            $blogsArr['varPassword'] = $postArr['new_password'];
        } else {
            $blogsArr['varPassword'] = '';
        }
        if ($postArr['chrMenuDisplay'] == 'D') {
            $addlog = Config::get('Constant.ADDED_DRAFT');
        } else {
            $addlog = '';
        }
        $blogsArr['UserID'] = auth()->user()->id;
        $blogsArr['created_at'] = Carbon::now();
        $blogsArr['fkIntImgId'] = !empty($postArr['img_id']) ? $postArr['img_id'] : null;
        $blogsID = CommonModel::addRecord($blogsArr, 'Powerpanel\Blogs\Models\Blogs');
        if (!empty($blogsID)) {
            $id = $blogsID;
            $newBlogObj = Blogs::getRecordForLogById($id);
            $logArr = MyLibrary::logData($id, false, $addlog);
            $logArr['varTitle'] = $newBlogObj->varTitle;
            Log::recordLog($logArr);
            if (Auth::user()->can('recent-updates-list')) {
                $notificationArr = MyLibrary::notificationData($id, $newBlogObj);
                RecentUpdates::setNotification($notificationArr);
            }
            $response = $newBlogObj;
            self::flushCache();
            $actionMessage = trans('blogs::template.blogsModule.addMessage');
        }
        return $response;
    }

    /**
     * This method destroys Faq in multiples
     * @return  Faq index view
     * @since   2016-10-25
     * @author  NetQuick
     */
    public function DeleteRecord(Request $request) {
        $value = Request::input('value');
        $data['ids'] = Request::input('ids');
        $moduleHaveFields = ['chrMain', 'chrIsPreview'];
        $update = MyLibrary::deleteMultipleRecords($data, $moduleHaveFields, $value, 'Powerpanel\Blogs\Models\Blogs');
        if (File::exists(app_path() . '/Comments.php') != null || File::exists(base_path() . '/packages/Powerpanel/Workflow/src/Models/Comments.php') != null) {
            Comments::deleteComments($data['ids'], Config::get('Constant.MODULE.MODEL_NAME'));
        }
        foreach ($update as $ids) {
            $ignoreDeleteScope = true;
            $Deleted_Record = Blogs::getRecordById($ids, $ignoreDeleteScope);
            $Cnt_Letest = Blogs::getRecordCount_letest($Deleted_Record['fkMainRecord'], $Deleted_Record['id']);
            if ($Cnt_Letest <= 0) {
                $updateLetest = [
                    'chrAddStar' => 'N',
                ];
                $whereConditionsApprove = ['id' => $Deleted_Record['fkMainRecord']];
                CommonModel::updateRecords($whereConditionsApprove, $updateLetest, false, 'Powerpanel\Blogs\Models\Blogs');
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
    public function publish(Request $request) {
        $requestArr = Request::all();
        //        $request = (object) $requestArr;
        $val = Request::get('val');
        $alias = Request::input('alias');
        $update = MyLibrary::setPublishUnpublish($alias, $val, 'Powerpanel\Blogs\Models\Blogs');
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
        MyLibrary::swapOrder($order, $exOrder, 'Powerpanel\Blogs\Models\Blogs');
        self::flushCache();
    }

    /**
     * This method handels swapping of available order record while adding
     * @param      order
     * @return  order
     * @since   2016-10-21
     * @author  NetQuick
     */
    public static function swap_order_add($order = null) {
        $response = false;
        $isCustomizeModule = true;
        $moduleHaveFields = ['chrMain', 'chrIsPreview'];
        if ($order != null) {
            $response = MyLibrary::swapOrderAdd($order, $isCustomizeModule, $moduleHaveFields, 'Powerpanel\Blogs\Models\Blogs');
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
    public static function swap_order_edit($order = null, $id = null) {
        $isCustomizeModule = true;
        $moduleHaveFields = ['chrMain', 'chrIsPreview'];
        MyLibrary::swapOrderEdit($order, $id, $isCustomizeModule, $moduleHaveFields, 'Powerpanel\Blogs\Models\Blogs');
        self::flushCache();
    }


    public function tableData($value , $permit, $currentUserID)
    {

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
                if ($permit['canblogspublish']) {
                    if ($value->chrPublish == 'Y') {
                        $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/blogs', 'data_alias'=>$value->id, 'title'=>trans("blogs::template.common.publishedRecord"), 'data_value'=>'Unpublish', 'checked'=>'checked'])->render();
                    } else {
                        $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/blogs', 'data_alias'=>$value->id, 'title'=>trans("blogs::template.common.unpublishedRecord"), 'data_value'=>'Publish'])->render();
                    }
                } else {
                    $publish_action = "-";
                }
            } else {
                if ($permit['canblogspublish']) {
                    if ($value->chrPublish == 'Y') {
                        $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/blogs', 'data_alias'=>$value->id, 'title'=>trans("blogs::template.common.publishedRecord"), 'data_value'=>'Unpublish', 'checked'=>'checked'])->render();
                    } else {
                        $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/blogs', 'data_alias'=>$value->id, 'title'=>trans("blogs::template.common.unpublishedRecord"), 'data_value'=>'Publish'])->render();
                    }
                } else {
                    $publish_action = "-";
                }
            }
        } else {
            if ($permit['canblogspublish']) {
                $publish_action .= '<a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="bottom" title="This blog is in Approval request so can&#39;t be Publish/Unpublish."><i style="color:red" class="ri-toggle-line icon-publish fs-24"></i></a>';
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
        if ($permit['canblogsedit']) {
            $Quickedit_startDate = date('Y-m-d H:i', strtotime($value->dtDateTime));
            $Quickedit_endDate = !empty($value->dtEndDateTime) ? date('Y-m-d H:i', strtotime($value->dtEndDateTime)) : 'No Expiry';

            if ($value->chrLock != 'Y') {
                if (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y') {
                    if ($permit['canblogsreviewchanges']) {
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
        $viewlink = "";
        $linkviewLable = "";
        if(isset($value->alias) && $value->alias != null){
            if ($permit['canblogsedit']) {
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
                        'canedit'=> $permit['canblogsedit'],
                        'candelete'=>$permit['canblogsdelete'],
                        'canloglist'=>$permit['canloglist'],
                        'value'=>$value,
                        'currentUserID' => $currentUserID,
                        'chrIsAdmin' => $this->currentUserRoleData->chrIsAdmin,
                        'module_name'=>'blogs',
                        'module_edit_url' => route('powerpanel.blogs.edit', array('alias' => $value->id)),
                        'module_type'=>'parent',
                        'viewlink' => isset($viewlink) ? $viewlink : "",
                        'linkviewLable' => isset($linkviewLable) ? $linkviewLable : "",
                        'logurl' => $logurl
                    ])->render();

        if($permit['canblogsedit'] || $permit['canblogsdelete']){
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

    public function tableData_tab1($value , $permit, $currentUserID)
    {

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
                if ($permit['canblogspublish']) {
                    if ($value->chrPublish == 'Y') {
                        $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/blogs', 'data_alias'=>$value->id, 'title'=>trans("blogs::template.common.publishedRecord"), 'data_value'=>'Unpublish', 'checked'=>'checked'])->render();
                    } else {
                        $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/blogs', 'data_alias'=>$value->id, 'title'=>trans("blogs::template.common.unpublishedRecord"), 'data_value'=>'Publish'])->render();
                    }
                } else {
                    $publish_action = "-";
                }
            } else {
                if ($permit['canblogspublish']) {
                    if ($value->chrPublish == 'Y') {
                        $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/blogs', 'data_alias'=>$value->id, 'title'=>trans("blogs::template.common.publishedRecord"), 'data_value'=>'Unpublish', 'checked'=>'checked'])->render();
                    } else {
                        $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/blogs', 'data_alias'=>$value->id, 'title'=>trans("blogs::template.common.unpublishedRecord"), 'data_value'=>'Publish'])->render();
                    }
                } else {
                    $publish_action = "-";
                }
            }
        } else {
            if ($permit['canblogspublish']) {
                $publish_action .= '<a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="bottom" title="This blog is in Approval request so can&#39;t be Publish/Unpublish."><i style="color:red" class="ri-toggle-line icon-publish fs-24"></i></a>';
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
        if ($permit['canblogsedit']) {
            $Quickedit_startDate = date('Y-m-d H:i', strtotime($value->dtDateTime));
            $Quickedit_endDate = !empty($value->dtEndDateTime) ? date('Y-m-d H:i', strtotime($value->dtEndDateTime)) : 'No Expiry';

            if ($value->chrLock != 'Y') {
                if (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y') {
                    if ($permit['canblogsreviewchanges']) {
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
            if ($permit['canblogsedit']) {
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
                        'canedit'=> $permit['canblogsedit'],
                        'candelete'=>$permit['canblogsdelete'],
                        'canloglist'=>$permit['canloglist'],
                        'value'=>$value,
                        'currentUserID' => $currentUserID,
                        'chrIsAdmin' => $this->currentUserRoleData->chrIsAdmin,
                        'module_name'=>'blogs',
                        'module_edit_url' => route('powerpanel.blogs.edit', array('alias' => $value->id)),
                        'module_type'=>'parent',
                        'viewlink' => isset($viewlink) ? $viewlink : "",
                        'linkviewLable' => isset($linkviewLable) ? $linkviewLable : "",
                        'logurl' => $logurl
                    ])->render();

        if($permit['canblogsedit'] || $permit['canblogsdelete']){
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

    public function tableDataFavorite($value , $permit, $currentUserID)
    {
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
                if ($permit['canblogspublish']) {
                    if ($value->chrPublish == 'Y') {
                        $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/blogs', 'data_alias'=>$value->id, 'title'=>trans("blogs::template.common.publishedRecord"), 'data_value'=>'Unpublish', 'checked'=>'checked'])->render();
                    } else {
                        $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/blogs', 'data_alias'=>$value->id, 'title'=>trans("blogs::template.common.unpublishedRecord"), 'data_value'=>'Publish'])->render();
                    }
                } else {
                    $publish_action = "-";
                }
            } else {
                if ($permit['canblogspublish']) {
                    if ($value->chrPublish == 'Y') {
                        $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/blogs', 'data_alias'=>$value->id, 'title'=>trans("blogs::template.common.publishedRecord"), 'data_value'=>'Unpublish', 'checked'=>'checked'])->render();
                    } else {
                        $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/blogs', 'data_alias'=>$value->id, 'title'=>trans("blogs::template.common.unpublishedRecord"), 'data_value'=>'Publish'])->render();
                    }
                } else {
                    $publish_action = "-";
                }
            }
        } else {
            if ($permit['canblogspublish']) {
                $publish_action .= '<a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="bottom" title="This blog is in Approval request so can&#39;t be Publish/Unpublish."><i style="color:red" class="ri-toggle-line icon-publish fs-24"></i></a>';
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
        if ($permit['canblogsedit']) {
            $Quickedit_startDate = date('Y-m-d H:i', strtotime($value->dtDateTime));
            $Quickedit_endDate = !empty($value->dtEndDateTime) ? date('Y-m-d H:i', strtotime($value->dtEndDateTime)) : 'No Expiry';

            if ($value->chrLock != 'Y') {
                if (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y') {
                    if ($permit['canblogsreviewchanges']) {
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
            if ($permit['canblogsedit']) {
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
                        'canedit'=> $permit['canblogsedit'],
                        'candelete'=>$permit['canblogsdelete'],
                        'canloglist'=>$permit['canloglist'],
                        'value'=>$value,
                        'currentUserID' => $currentUserID,
                        'chrIsAdmin' => $this->currentUserRoleData->chrIsAdmin,
                        'module_name'=>'blogs',
                        'module_edit_url' => route('powerpanel.blogs.edit', array('alias' => $value->id)),
                        'module_type'=>'parent',
                        'viewlink' => isset($viewlink) ? $viewlink : "",
                        'linkviewLable' => isset($linkviewLable) ? $linkviewLable : "",
                        'logurl' => $logurl
                    ])->render();

        if($permit['canblogsedit'] || $permit['canblogsdelete']){
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
                if ($permit['canblogspublish']) {
                    if ($value->chrPublish == 'Y') {
                        $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/blogs', 'data_alias'=>$value->id, 'title'=>trans("blogs::template.common.publishedRecord"), 'data_value'=>'Unpublish', 'checked'=>'checked'])->render();
                    } else {
                        $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/blogs', 'data_alias'=>$value->id, 'title'=>trans("blogs::template.common.unpublishedRecord"), 'data_value'=>'Publish'])->render();
                    }
                } else {
                    $publish_action = "-";
                }
            } else {
                if ($permit['canblogspublish']) {
                    if ($value->chrPublish == 'Y') {
                        $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/blogs', 'data_alias'=>$value->id, 'title'=>trans("blogs::template.common.publishedRecord"), 'data_value'=>'Unpublish', 'checked'=>'checked'])->render();
                    } else {
                        $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/blogs', 'data_alias'=>$value->id, 'title'=>trans("blogs::template.common.unpublishedRecord"), 'data_value'=>'Publish'])->render();
                    }
                } else {
                    $publish_action = "-";
                }
            }
        } else {
            if ($permit['canblogspublish']) {
                $publish_action .= '<a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="bottom" title="This blog is in Approval request so can&#39;t be Publish/Unpublish."><i style="color:red" class="ri-toggle-line icon-publish fs-24"></i></a>';
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
        if ($permit['canblogsedit']) {
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
            if ($permit['canblogsedit']) {
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
                        'canedit'=> $permit['canblogsedit'],
                        'candelete'=>$permit['canblogsdelete'],
                        'canloglist'=>$permit['canloglist'],
                        'value'=>$value,
                        'chrIsAdmin' => $this->currentUserRoleData->chrIsAdmin,
                        'module_name'=>'blogs',
                        'module_edit_url' => route('powerpanel.blogs.edit', array('alias' => $value->id)),
                        'module_type'=>'parent',
                        'viewlink' => isset($viewlink) ? $viewlink : "",
                        'linkviewLable' => isset($linkviewLable) ? $linkviewLable : "",
                        'logurl' => $logurl
                    ])->render();

        if($permit['canblogsedit'] || $permit['canblogsdelete']){
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

    public function tableDataTrash($value , $permit, $currentUserID)
    {

        // Checkbox
        $checkbox = view('powerpanel.partials.checkbox', ['name'=>'delete', 'value'=>$value->id])->render();


        // StartDate
        $startDate = $value->dtDateTime;
        $startDate = '<span align="left" data-bs-toggle="tooltip" data-bs-placement="bottom" title="'.date(Config::get("Constant.DEFAULT_DATE_FORMAT").' '.Config::get("Constant.DEFAULT_TIME_FORMAT"), strtotime($startDate)).'">'.date(Config::get('Constant.DEFAULT_DATE_FORMAT'), strtotime($startDate)).'</span>';


        // Title
        $title = $value->varTitle;


        // Title Action
        $title_action = '';
        if ($permit['canblogsedit']) {
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
                        'canedit'=> $permit['canblogsedit'],
                        'candelete'=>$permit['canblogsdelete'],
                        'canloglist'=>$permit['canloglist'],
                        'value'=>$value,
                        'currentUserID' => $currentUserID,
                        'chrIsAdmin' => $this->currentUserRoleData->chrIsAdmin,
                        'module_name'=>'blogs',
                        'module_edit_url' => route('powerpanel.blogs.edit', array('alias' => $value->id)),
                        'module_type'=>'parent',
                        'viewlink' => isset($viewlink) ? $viewlink : "",
                        'linkviewLable' => isset($linkviewLable) ? $linkviewLable : "",
                        'logurl' => $logurl
                    ])->render();

        if($permit['canblogsedit'] || $permit['canblogsdelete']){
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
        $BlogCategory = BlogCategory::getCatData($data->intFKCategory);

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
						<th align="center">' . trans('blogs::template.common.title') . '</th>
						<th align="center">Category</th>
																								<th align="center">Short Description</th>
																				<th align="center">Description</th>
																								<th align="center">' . trans("blogs::template.common.image") . '</th>
						<th align="center">Start Date</th>
						<th align="center">End Date</th>
																								<th align="center">Meta Title</th>
																																<th align="center">Meta Description</th>
																								<th align="center">' . trans("blogs::template.common.publish") . '</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td align="center">' . stripslashes($data->varTitle) . '</td>
						<td align="center">' . $BlogCategory->varTitle . '</td>   <td align="center">' . stripslashes($data->varShortDescription) . '</td>
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
    public function newrecordHistory($data = false, $newdata = false) {
        $returnHtml = '';
        $startDate = date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($newdata->dtDateTime));
        $endDate = !empty($newdata->dtEndDateTime) ? date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($newdata->dtEndDateTime)) : 'No Expiry';
        $BlogCategory = BlogCategory::getCatData($newdata->intFKCategory);
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
						<th align="center">' . trans('blogs::template.common.title') . '</th>
						<th align="center">Category</th>
																								<th align="center">Short Description</th>
								<th align="center">Description</th>
																								<th align="center">' . trans("blogs::template.common.image") . '</th>
						<th align="center">Start Date</th>
						<th align="center">End Date</th>
																								<th align="center">Meta Title</th>
																																 <th align="center">Meta Description</th>
																								<th align="center">' . trans("blogs::template.common.publish") . '</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td align="center" ' . $titlecolor . '>' . stripslashes($newdata->varTitle) . '</td>
						<td align="center" ' . $catcolor . '>' . $BlogCategory->varTitle . '</td><td align="center" ' . $ShortDescriptioncolor . '>' . stripslashes($newdata->varShortDescription) . '</td>
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

    public static function flushCache() {
        Cache::tags('Faq')->flush();
    }

    public function getChildData() {
        $childHtml = "";
        $Blogs_childData = "";
        $Blogs_childData = Blogs::getChildGrid();


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

        if (count($Blogs_childData) > 0) {
            foreach ($Blogs_childData as $child_row) {

                $checkbox = view('powerpanel.partials.checkbox', ['name'=>'delete', 'value'=>$child_row->id])->render();
                $previewlink = url('/previewpage?url=' . MyLibrary::getFrontUri('blogs')['uri'] . '/' . $child_row->id . '/preview/detail');

                $childHtml .= '<tr role="row">';
                    if ($child_row->chrApproved == 'N') {
                        $childHtml .= "<td><span class='mob_show_title'>&nbsp</span>".$checkbox."</td>";
                    } else {
                        $childHtml .= "<td><span class='mob_show_title'>&nbsp</span><div class=\"checker\"><a href=\"javascript:void(0);\" data-bs-toggle='tooltip' data-bs-placement='bottom' title=\"This is approved record, so can't be deleted.\"><i style=\"color:red\" class=\"ri-spam-line\"></i></a></div></td>";
                    }

                    $childHtml .= '<td class="text-left"><span class="mob_show_title">Title: </span>' . $child_row->varTitle . '</td>';

                    $childHtml .= '<td class="text-center"><span class="mob_show_title">Date Submitted: </span><span align="left" data-bs-toggle="tooltip" data-bs-placement="bottom" title="'.date(Config::get("Constant.DEFAULT_DATE_FORMAT").' '.Config::get("Constant.DEFAULT_TIME_FORMAT"), strtotime($child_row->created_at)).'">' . date(Config::get("Constant.DEFAULT_DATE_FORMAT"), strtotime($child_row->created_at)) . '</span></td>';

                    $childHtml .= '<td class="text-center"><span class="mob_show_title">User: </span>' . CommonModel::getUserName($child_row->UserID) . '</td>';

                    $childHtml .= '<td class="text-center"><span class="mob_show_title">Preview: </span><a class="icon_round me-2" href=' . $previewlink . " target='_blank'><i class=\"ri-computer-line\"></i></a></td>";

                    if ($child_row->chrApproved == 'N') {
                        $childHtml .= "<td class=\"text-center\"><span class='mob_show_title'>Edit: </span><a class='icon_round me-2' data-bs-toggle='tooltip' data-bs-placement='bottom' title='" . trans('blogs::template.common.edit') . "' href='" . route('powerpanel.blogs.edit', array('alias' => $child_row->id)) . "?tab=A'><i class='ri-pencil-line'></i></a></td>";
                    } else {
                        $childHtml .= '<td class="text-center"><span class="mob_show_title">Edit: </span>-</td>';
                    }

                    if ($child_row->chrApproved == 'N') {
                        $childHtml .= "<td class=\"text-center\"><span class='mob_show_title'>Status: </span><a class=\"approve_icon_btn me-2\" data-bs-toggle='tooltip' data-bs-placement='bottom' title='" . trans('blogs::template.common.comments') . "' href=\"javascript:void(0);\" onclick=\"loadModelpopup('" . $child_row->id . "','" . $child_row->UserID . "','" . Config::get('Constant.MODULE.MODEL_NAME') . "','" . $child_row->fkMainRecord . "')\"><i class=\"ri-chat-1-line\"></i> </a><a class=\"approve_icon_btn me-2\" onclick=\"update_mainrecord('" . $child_row->id . "','" . $child_row->fkMainRecord . "','" . $child_row->UserID . "','A');\" data-bs-toggle='tooltip' data-bs-placement='bottom' title='" . trans('blogs::template.common.clickapprove') . "'  href=\"javascript:void(0);\"><i class=\"ri-checkbox-line
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

    public function getChildData_rollback() {
        $child_rollbackHtml = "";
        $Cmspage_rollbackchildData = "";
        $Cmspage_rollbackchildData = Blogs::getChildrollbackGrid();
        $child_rollbackHtml .= "<div class=\"producttbl producttb2\" style=\"\">";
        $child_rollbackHtml .= "<table class=\"new_table_desing table table-striped table-bordered table-hover table-checkable dataTable\" id=\"email_log_datatable_ajax\">
							    <tr role=\"row\">																																																																<th class=\"text-center\">Title</th>
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
                $categoryRecordAlias = MyLibrary::getRecordAliasByModuleNameRecordId("blog-category", $child_rollbacrow->intFKCategory);
                $previewlink = url('/previewpage?url=' . MyLibrary::getFrontUri('blogs')['uri'] . '/' . $child_rollbacrow->id . '/preview/detail');
                $child_rollbackHtml .= "<td class=\"text-center\"><span class='mob_show_title'>Preview: </span><a class='icon_round' href=" . $previewlink . " target='_blank'><i class=\"ri-computer-line\"></i></a></td>";
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

    public function insertComents(Request $request) {
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

    public function ApprovedData_Listing(Request $request) {
        $requestArr = Request::all();
        $request = (object) $requestArr;
        $id = Request::post('id');
        $main_id = Request::post('main_id');
        $approvalid = Request::post('id');
        $flag = Request::post('flag');
        $approvalData = Blogs::getOrderOfApproval($id);
        $message = Blogs::approved_data_Listing($request);
        $newCmsPageObj = Blogs::getRecordForLogById($main_id);
        $approval_obj = Blogs::getRecordForLogById($approvalid);
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
        $careers = Blogs::getRecordForLogById($id);
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

    public function rollBackRecord(Request $request) {

        $message = 'Oops! Something went wrong';
        $requestArr = Request::all();
        $request = (object) $requestArr;

        $previousRecord = Blogs::getPreviousRecordByMainId($request->id);
        if (!empty($previousRecord)) {

            $main_id = $previousRecord->fkMainRecord;
            $request->id = $previousRecord->id;
            $request->main_id = $main_id;

            $message = Blogs::approved_data_Listing($request);

            $newBlogObj = Blogs::getRecordForLogById($main_id);
            $restoredata = Config::get('Constant.ROLLBACK_RECORD');

            /* notification for user to record approved */
            $blogs = Blogs::getRecordForLogById($previousRecord->id);
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
        $arrResults = Blogs::getBuilderRecordList($filterArr);
        $found = $arrResults->toArray();
        if (!empty($found)) {
            foreach ($arrResults as $key => $value) {
                $rows .= $this->tableDataBuilder($value, false, $filterArr['selected']);
            }
        } else {
            $rows .= '<tr id="not-found"><td colspan="4" align="center">No records found.</td></tr>';
        }
        $iTotalRecords = CommonModel::getTotalRecordCount('Powerpanel\Blogs\Models\Blogs', true, true);
        $records["data"] = $rows;
        $records["found"] = count($found);
        $records["recordsTotal"] = $iTotalRecords;
        return json_encode($records);
    }

    public function tableDataBuilder($value = false, $fcnt = false, $selected = []) {
        $publish_action = '';
        $dtFormat = Config::get('Constant.DEFAULT_DATE_FORMAT');
        $categories = BlogCategory::getRecordByIds(explode(',', $value->intFKCategory))->toArray();
        $categories = array_column($categories, 'varTitle'); //print_r($categories); die;
        $categories = implode(', ', $categories);
        $startDate = date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($value->dtDateTime));
        $endDate = !empty($value->dtEndDateTime) ? date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($value->dtEndDateTime)) : 'No Expiry';
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
