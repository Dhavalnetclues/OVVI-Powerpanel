<?php

namespace Powerpanel\PublicationsCategory\Controllers\Powerpanel;

use App\Alias;
use App\CommonModel;
use App\Helpers\AddQuickCategoryAjax;
use App\Helpers\MyLibrary;
use App\Helpers\ParentRecordHierarchy_builder;
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
use Illuminate\Support\Facades\Request;
use Powerpanel\PublicationsCategory\Models\PublicationsCategory;
use Powerpanel\Publications\Models\Publications;
use Powerpanel\RoleManager\Models\Role_user;
use Powerpanel\Workflow\Models\Comments;
use Powerpanel\Workflow\Models\Workflow;
use Powerpanel\Workflow\Models\WorkflowLog;
use Validator;

class PublicationsCategoryController extends PowerpanelController
{

    public function __construct()
    {
        parent::__construct();
        if (isset($_COOKIE['locale'])) {
            app()->setLocale($_COOKIE['locale']);
        }
        $this->userIsAdmin = false;
        $this->MyLibrary = new MyLibrary();
        $this->CommonModel = new CommonModel();
    }

    public function index()
    {
        $userIsAdmin = false;
        if (isset($this->currentUserRoleData) && !empty($this->currentUserRoleData)) {
            if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                $userIsAdmin = true;
            }
        }

        $iTotalRecords = PublicationsCategory::getRecordCount(false, false, $userIsAdmin, $this->currentUserRoleSector);
        $draftTotalRecords = PublicationsCategory::getRecordCountforListDarft(false, true, $userIsAdmin, array(), $this->currentUserRoleSector);
        $trashTotalRecords = PublicationsCategory::getRecordCountforListTrash(false, false, $userIsAdmin, array(), $this->currentUserRoleSector);
        $favoriteTotalRecords = PublicationsCategory::getRecordCountforListFavorite(false, false, $userIsAdmin, array(), $this->currentUserRoleSector);
        $approvalTotalRecords = PublicationsCategory::getRecordCountListApprovalTab(false, false, $userIsAdmin, array(), $this->currentUserRoleSector);

        $this->breadcrumb['title'] = trans('publications-category::template.publications_categoryModule.managePublicationsCategory');
        $breadcrumb = $this->breadcrumb;

        /* code for getting chart for parent categories */
        $categories = PublicationsCategory::getParentCategoryFilterList(false);
        $publicationsCategoryData = PublicationsCategory::getRecordsForChart();
        $orgdata = array();
        if (!empty($publicationsCategoryData) && count($publicationsCategoryData) > 0) {
            foreach ($publicationsCategoryData as $orgnization) {
                $ogData = array();
                $tempData = array();
                $tempData['v'] = (String) $orgnization->id;
                $tempData['f'] = $orgnization->varTitle;
                $ogData[] = $tempData;
                if ($orgnization->intParentCategoryId > 0) {
                    array_push($ogData, (String) $orgnization->intParentCategoryId);
                } else {
                    array_push($ogData, null);
                }
                array_push($ogData, $orgnization->varTitle);
                $orgdata[] = $ogData;
            }
        }
        $orgdata = addslashes(json_encode($orgdata));

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

        return view('publications-category::powerpanel.index', compact('userIsAdmin', 'categories', 'approvalTotalRecords', 'iTotalRecords', 'breadcrumb', 'orgdata', 'draftTotalRecords', 'trashTotalRecords', 'favoriteTotalRecords', 'settingarray'));
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
            $pubCategory = PublicationsCategory::getParentCategoryFilterList($sectorname);
        } else {
            $pubCategory = PublicationsCategory::getParentCategoryFilterList(false);
        }
        $recordSelect = '<option value=" ">--Select Parent Category--</option>';

        foreach ($pubCategory as $cat) {

            $recordSelect .= '<option  value="' . $cat->id . '">' . ucwords($cat->varTitle) . '</option>';
        }
        return $recordSelect;
    }
    /**
     * This method stores PublicationsCategory modifications
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
        $data = Request::all();
        
        if(empty($data['parent_category_id'])){
        	$data['parent_category_id']="0";
        }
        
        $settings = json_decode(Config::get("Constant.MODULE.SETTINGS"));
        $rules = array(
            'title' => 'required|max:160|handle_xss|no_url',
            'sector' => 'required',
            'display_order' => 'required|greater_than_zero|handle_xss|no_url',
            'parent_category_id' => 'required',
            // 'varMetaTitle' => 'required|max:160|handle_xss|no_url',
            // 'varMetaDescription' => 'required|max:200|handle_xss|no_url',
            // 'alias' => 'required',
        );
        $messsages = array(
            'title.required' => 'Name field is required.',
            'sector.required' => 'Sector field is required.',
            'display_order.required' => trans('publications-category::template.publications_categoryModule.displayOrder'),
            'display_order.greater_than_zero' => trans('publications-category::template.publications_categoryModule.displayGreaterThan'),
            // 'varMetaTitle.required' => trans('publications-category::template.publications_categoryModule.metaTitle'),
            // 'varMetaDescription.required' => trans('publications-category::template.publications_categoryModule.metaDescription'),
        );
        $validator = Validator::make($data, $rules, $messsages);
        if ($validator->passes()) {
            $PublicationsCategoryArr = [];
            $module = Modules::getModuleById(Config::get('Constant.MODULE.ID'));
            if (isset($this->currentUserRoleData)) {
                $currentUserRoleData = $this->currentUserRoleData;
            }
            $id = Request::segment(3);
            $actionMessage = trans('publications-category::template.common.oppsSomethingWrong');
            if (is_numeric($id)) { #Edit post Handler=======
            $PublicationsCategory = PublicationsCategory::getRecordForLogById($id);
                if ($data['oldAlias'] != $data['alias']) {
                    Alias::updateAlias($data['oldAlias'], $data['alias']);
                }
                // if (Config::get('Constant.CHRSearchRank') == 'Y') {
                //     $searchrank = $data['search_rank'];
                // }

                $startdate = !empty($data['start_date_time']) ? date('Y-m-d H:i:s', strtotime($data['start_date_time'])) : date('Y-m-d H:i:s');
                $enddate = !empty($data['end_date_time']) ? date('Y-m-d H:i:s', strtotime($data['end_date_time'])) : null;

                $updatePublicationsCategoryFields = [
                    'varTitle' => stripslashes(trim($data['title'])),
                    'intParentCategoryId' => $data['parent_category_id'],
                    'varSector' => $data['sector'],
                    'chrPublish' => isset($data['chrMenuDisplay']) ? $data['chrMenuDisplay'] : 'Y',
                    'txtDescription' => $data['description'],
                    'dtDateTime' => $startdate,
                    'dtEndDateTime' => $enddate,
                    'varMetaTitle' => stripslashes(trim($data['varMetaTitle'])),
                    'varMetaDescription' => stripslashes(trim($data['varMetaDescription'])),
                    'varTags' => trim($data['tags']),
                    // 'intSearchRank' => $data['search_rank'],
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
                $whereConditions = ['id' => $PublicationsCategory->id];
                if ($data['chrMenuDisplay'] == 'D') {
                    $updatePublicationsCategoryFields['chrDraft'] = 'D';
                    $updatePublicationsCategoryFields['chrPublish'] = 'N';
                } else {
                    $updatePublicationsCategoryFields['chrDraft'] = 'N';
                    $updatePublicationsCategoryFields['chrPublish'] = $data['chrMenuDisplay'];
                }
                if (isset($data['chrPageActive']) && $data['chrPageActive'] != '') {
                    $updatePublicationsCategoryFields['chrPageActive'] = $data['chrPageActive'];
                }
                if (isset($data['chrPageActive']) && $data['chrPageActive'] == 'PP') {
                    $updatePublicationsCategoryFields['varPassword'] = $data['new_password'];
                } else {
                    $updatePublicationsCategoryFields['varPassword'] = '';
                }
                if ($data['chrMenuDisplay'] == 'D') {
                    $addlog = Config::get('Constant.UPDATE_DRAFT');
                } else {
                    $addlog = '';
                }
                if ($PublicationsCategory->chrLock == 'Y' && auth()->user()->id != $PublicationsCategory->LockUserID) {
                    if ($this->currentUserRoleData->chrIsAdmin != 'Y') {
                        $lockedUserData = User::getRecordById($PublicationsCategory->LockUserID, true);
                        $lockedUserName = 'someone';
                        if (!empty($lockedUserData)) {
                            $lockedUserName = $lockedUserData->name;
                        }
                        $actionMessage = "This record has been locked by " . $lockedUserName . ".";
                        return redirect()->route('powerpanel.publications-category.index')->with('message', $actionMessage);
                    }
                }
                if (File::exists(app_path() . '/Workflow.php') != null || File::exists(base_path() . '/packages/Powerpanel/Workflow/src/Models/Workflow.php') != null) {
                    if (!$userIsAdmin) {
                        $userRole = $currentUserRoleData->id;
                    } else {
                        $userRoleData = Role_user::getUserRoleByUserId($PublicationsCategory->UserID);
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
//                        if ($data['oldAlias'] != $data['alias']) {
                        //                            Alias::updateAlias($data['oldAlias'], $data['alias']);
                        //                            MyLibrary::updateAliasInMenu($data['alias'], $PublicationsCategory->id, 'after');
                        ////            echo 'hi';exit;
                        //                        }
                        if ($PublicationsCategory->fkMainRecord === 0 || empty($workFlowByCat->varUserId)) {
                            $update = CommonModel::updateRecords($whereConditions, $updatePublicationsCategoryFields, false, 'Powerpanel\PublicationsCategory\Models\PublicationsCategory');
                            if ($update) {
                                if (!empty($id)) {
                                    $newCmsPageObj = PublicationsCategory::getRecordForLogById($id);
                                    #Update record in menu
                                    $whereConditions = ['intfkModuleId' => Config::get('Constant.MODULE.ID'), 'intRecordId' => $id];
                                    $updateMenuFields = [
                                        'varTitle' => $data['title'],
                                    ];
                                    CommonModel::updateRecords($whereConditions, $updateMenuFields, false, '\\Powerpanel\\Menu\\Models\\Menu');
                                    #Update record in menu
                                    self::newSwapOrderEdit($data['display_order'], $PublicationsCategory);
                                    $logArr = MyLibrary::logData($PublicationsCategory->id);
                                    if (Auth::user()->can('log-advanced')) {
                                        $newPublicationsCategoryObj = PublicationsCategory::getRecordForLogById($PublicationsCategory->id);
                                        $oldRec = $this->recordHistory($PublicationsCategory);
                                        $newRec = $this->newrecordHistory($PublicationsCategory, $newPublicationsCategoryObj);
                                        $logArr['old_val'] = $oldRec;
                                        $logArr['new_val'] = $newRec;
                                    }
                                    $logArr['varTitle'] = stripslashes(trim($data['title']));
                                    Log::recordLog($logArr);
                                    if (Auth::user()->can('recent-updates-list')) {
                                        if (!isset($newPublicationsCategoryObj)) {
                                            $newPublicationsCategoryObj = PublicationsCategory::getRecordForLogById($PublicationsCategory->id);
                                        }
                                        $notificationArr = MyLibrary::notificationData($PublicationsCategory->id, $newPublicationsCategoryObj);
                                        RecentUpdates::setNotification($notificationArr);
                                    }
                                    if (isset($data['saveandexit']) && $data['saveandexit'] == 'approvesaveandexit') {
                                        $actionMessage = trans('publications-category::template.common.recordApprovalMessage');
                                    } else {
                                        $actionMessage = trans('publications-category::template.publications_categoryModule.successMessage');
                                    }
                                }
                            }
                        } else {
                            $newCmsPageObj = PublicationsCategory::getRecordForLogById($id);
                            #Update record in menu
                            $whereConditions = ['intfkModuleId' => Config::get('Constant.MODULE.ID'), 'intRecordId' => $newCmsPageObj->fkMainRecord];
                            $updateMenuFields = [
                                'varTitle' => $newCmsPageObj->varTitle,
                            ];
                            CommonModel::updateRecords($whereConditions, $updateMenuFields, false, '\\Powerpanel\\Menu\\Models\\Menu');
                            #Update record in menu

                            $updateModuleFields = $updatePublicationsCategoryFields;
                            $this->insertApprovedRecord($updateModuleFields, $data, $id);
                            if (isset($data['saveandexit']) && $data['saveandexit'] == 'approvesaveandexit') {
                                $actionMessage = trans('publications-category::template.common.recordApprovalMessage');
                            } else {
                                $actionMessage = trans('publications-category::template.publications_categoryModule.successMessage');
                            }
                        }
                    } else { #Add post Handler=======
                    if ($workFlowByCat->charNeedApproval == 'Y') {
                        $this->insertApprovalRecord($PublicationsCategory, $data, $updatePublicationsCategoryFields);
                        if (isset($data['saveandexit']) && $data['saveandexit'] == 'approvesaveandexit') {
                            $actionMessage = trans('publications-category::template.common.recordApprovalMessage');
                        } else {
                            $actionMessage = trans('publications-category::template.publications_categoryModule.successMessage');
                        }
                    }
                    }
                } else {
                    $update = CommonModel::updateRecords($whereConditions, $updatePublicationsCategoryFields, false, 'Powerpanel\PublicationsCategory\Models\PublicationsCategory');
                    $actionMessage = trans('publications-category::template.publications_categoryModule.successMessage');
                }
            } else { #Add post Handler=======
            if (File::exists(app_path() . '/Workflow.php') != null || File::exists(base_path() . '/packages/Powerpanel/Workflow/src/Models/Workflow.php') != null) {
                $workFlowByCat = Workflow::getRecordByCategoryId($module->intFkGroupCode, $currentUserRoleData->id, Config::get('Constant.MODULE.ID'));
            }
                if (!empty($workFlowByCat->varUserId) && $workFlowByCat->chrNeedAddPermission == 'Y' && !$userIsAdmin) {

                    $PublicationsCategoryArr['chrPublish'] = 'N';
                    $PublicationsCategoryArr['chrDraft'] = 'N';
                    $PublicationsCategory = $this->insertNewRecord($data, $PublicationsCategoryArr);
                    if ($data['chrMenuDisplay'] == 'D') {
                        $PublicationsCategoryArr['chrDraft'] = 'D';
                    }
                    $PublicationsCategoryArr['chrPublish'] = 'Y';
                    $this->insertApprovalRecord($PublicationsCategory, $data, $PublicationsCategoryArr);
                } else {
                    $PublicationsCategory = $this->insertNewRecord($data, $PublicationsCategoryArr);
                }
                if (isset($data['saveandexit']) && $data['saveandexit'] == 'approvesaveandexit') {
                    $actionMessage = trans('publications-category::template.common.recordApprovalMessage');
                } else {
                    $actionMessage = trans('publications-category::template.publications_categoryModule.addedMessage');
                }
                $id = $PublicationsCategory->id;
            }
            $this->flushCache();
            if ((!empty($data['saveandexit']) && $data['saveandexit'] == 'saveandexit') || !$userIsAdmin) {
                if ($data['chrMenuDisplay'] == 'D') {
                    return redirect()->route('powerpanel.publications-category.index', 'tab=D')->with('message', $actionMessage);
                } else {
                    return redirect()->route('powerpanel.publications-category.index')->with('message', $actionMessage);
                }
            } else {
                return redirect()->route('powerpanel.publications-category.edit', $id)->with('message', $actionMessage);
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
        $PublicationsCategory = PublicationsCategory::getRecordForLogById($id);
            if (Config::get('Constant.CHRSearchRank') == 'Y') {
                $searchrank = $postArr['search_rank'];
            }

            $startdate = !empty($postArr['start_date_time']) ? date('Y-m-d H:i:s', strtotime($postArr['start_date_time'])) : date('Y-m-d H:i:s');
            $enddate = !empty($postArr['end_date_time']) ? date('Y-m-d H:i:s', strtotime($postArr['end_date_time'])) : null;

            $updatePublicationsCategoryFields = [
                'varTitle' => stripslashes(trim($postArr['title'])),
                'intParentCategoryId' => $postArr['parent_category_id'],
                'chrPublish' => isset($postArr['chrMenuDisplay']) ? $postArr['chrMenuDisplay'] : 'Y',
                'dtDateTime' => $startdate,
                'dtEndDateTime' => $enddate,
                'varMetaTitle' => stripslashes(trim($postArr['varMetaTitle'])),
                'varMetaDescription' => stripslashes(trim($postArr['varMetaDescription'])),
                'varTags' => trim($postArr['tags']),
                'intSearchRank' => $searchrank,
                'updated_at' => date('Y-m-d H:i:s'),
                'chrIsPreview' => 'Y',
            ];
            $whereConditions = ['id' => $id];
            if ($postArr['oldAlias'] != $postArr['alias']) {
                Alias::updateAlias($postArr['oldAlias'], $postArr['alias']);
            }
            $update = CommonModel::updateRecords($whereConditions, $updatePublicationsCategoryFields, false, 'Powerpanel\PublicationsCategory\Models\PublicationsCategory');
        } else {
            $publicationsArr['intSearchRank'] = $postArr['search_rank'];
            $PublicationsCategoryArr = ['chrIsPreview' => 'Y'];
            $id = $this->insertNewRecord($postArr, $PublicationsCategoryArr, 'Y')->id;
        }
        return json_encode(array('status' => $id, 'alias' => $postArr['alias'], 'message' => trans('publications-category::template.pageModule.pageUpdate')));
    }

    public function insertNewRecord($data, $PublicationsCategoryArr, $preview = 'N')
    {
        $response = false;
        $PublicationsCategoryArr['intAliasId'] = MyLibrary::insertAlias($data['alias'], false, $preview);
        $PublicationsCategoryArr['varTitle'] = stripslashes(trim($data['title']));
        $PublicationsCategoryArr['varSector'] = $data['sector'];
        $PublicationsCategoryArr['dtDateTime'] = !empty($data['start_date_time']) ? date('Y-m-d H:i:s', strtotime($data['start_date_time'])) : date('Y-m-d H:i:s');
        $PublicationsCategoryArr['dtEndDateTime'] = !empty($data['end_date_time']) ? date('Y-m-d H:i:s', strtotime($data['end_date_time'])) : null;
        $PublicationsCategoryArr['intDisplayOrder'] =  self::newDisplayOrderAdd($data['display_order'], $data['parent_category_id']);
        $PublicationsCategoryArr['intParentCategoryId'] = $data['parent_category_id'];
        $PublicationsCategoryArr['txtDescription'] = $data['description'];
        // $PublicationsCategoryArr['varMetaTitle'] = stripslashes(trim($data['varMetaTitle']));
        // $PublicationsCategoryArr['varMetaDescription'] = stripslashes(trim($data['varMetaDescription']));
        // $PublicationsCategoryArr['varTags'] = trim($data['tags']);
        $PublicationsCategoryArr['created_at'] = Carbon::now();
        $PublicationsCategoryArr['UserID'] = auth()->user()->id;
        $PublicationsCategoryArr['chrMain'] = 'Y';
        // if (Config::get('Constant.CHRSearchRank') == 'Y') {
        //     $PublicationsCategoryArr['intSearchRank'] = $data['search_rank'];
        // }
        $PublicationsCategoryArr['created_at'] = date('Y-m-d H:i:s');
        $PublicationsCategoryArr['updated_at'] = date('Y-m-d H:i:s');
        if ($data['chrMenuDisplay'] == 'D') {
            $PublicationsCategoryArr['chrDraft'] = 'D';
            $PublicationsCategoryArr['chrPublish'] = 'N';
        } else {
            $PublicationsCategoryArr['chrDraft'] = 'N';
        }
        if (isset($data['chrPageActive']) && $data['chrPageActive'] != '') {
            $PublicationsCategoryArr['chrPageActive'] = $data['chrPageActive'];
        }
        if (isset($data['chrPageActive']) && $data['chrPageActive'] == 'PP') {
            $PublicationsCategoryArr['varPassword'] = $data['new_password'];
        } else {
            $PublicationsCategoryArr['varPassword'] = '';
        }
        if ($data['chrMenuDisplay'] == 'D') {
            $addlog = Config::get('Constant.ADDED_DRAFT');
        } else {
            $addlog = '';
        }
        $PublicationsCategoryID = CommonModel::addRecord($PublicationsCategoryArr, 'Powerpanel\PublicationsCategory\Models\PublicationsCategory');
        if (!empty($PublicationsCategoryID)) {
            self::newReOrderDisplayOrder($data['parent_category_id']);
            $id = $PublicationsCategoryID;
            $newPublicationsCategoryObj = PublicationsCategory::getRecordForLogById($id);
            $logArr = MyLibrary::logData($id);
            $logArr['varTitle'] = stripslashes($newPublicationsCategoryObj->varTitle);
            Log::recordLog($logArr);
            if (Auth::user()->can('recent-updates-list')) {
                $notificationArr = MyLibrary::notificationData($id, $newPublicationsCategoryObj);
                RecentUpdates::setNotification($notificationArr);
            }
            $response = $newPublicationsCategoryObj;
        }
        return $response;
    }

    public function insertApprovalRecord($PublicationsCategory, $data, $updatePublicationsCategoryFields)
    {
        $PublicationsCategoryArr['UserID'] = auth()->user()->id;
        $PublicationsCategoryArr['chrMain'] = 'N';
        $PublicationsCategoryArr['fkMainRecord'] = $PublicationsCategory->id;
        $PublicationsCategoryArr['chrLetest'] = 'Y';

        $PublicationsCategoryArr['dtDateTime'] = !empty($data['start_date_time']) ? date('Y-m-d H:i:s', strtotime($data['start_date_time'])) : date('Y-m-d H:i:s');
        $PublicationsCategoryArr['dtEndDateTime'] = !empty($data['end_date_time']) ? date('Y-m-d H:i:s', strtotime($data['end_date_time'])) : null;

        $PublicationsCategoryArr['intAliasId'] = MyLibrary::insertAlias($data['alias'], false, 'N');
        $PublicationsCategoryArr['varTitle'] = stripslashes(trim($data['title']));
        $PublicationsCategoryArr['intDisplayOrder'] = $data['display_order'];
        $PublicationsCategoryArr['intParentCategoryId'] = $data['parent_category_id'];
          $PublicationsCategoryArr['varSector'] = $data['sector'];
        $PublicationsCategoryArr['txtDescription'] = $data['description'];
        // $PublicationsCategoryArr['varMetaTitle'] = stripslashes(trim($data['varMetaTitle']));
        // $PublicationsCategoryArr['varMetaDescription'] = stripslashes(trim($data['varMetaDescription']));
        // $PublicationsCategoryArr['varTags'] = trim($data['tags']);
        // if (Config::get('Constant.CHRSearchRank') == 'Y') {
        //     $PublicationsCategoryArr['intSearchRank'] = $data['search_rank'];
        // }
        $PublicationsCategoryArr['chrPublish'] = isset($data['chrMenuDisplay']) ? $data['chrMenuDisplay'] : 'Y';
        $PublicationsCategoryArr['created_at'] = date('Y-m-d H:i:s');
        $PublicationsCategoryArr['updated_at'] = date('Y-m-d H:i:s');
        if ($data['chrMenuDisplay'] == 'D') {
            $PublicationsCategoryArr['chrDraft'] = 'D';
            $PublicationsCategoryArr['chrPublish'] = 'N';
        } else {
            $PublicationsCategoryArr['chrDraft'] = 'N';
            $PublicationsCategoryArr['chrPublish'] = $data['chrMenuDisplay'];
        }
        if (isset($data['chrPageActive']) && $data['chrPageActive'] != '') {
            $PublicationsCategoryArr['chrPageActive'] = $data['chrPageActive'];
        }
        if (isset($data['chrPageActive']) && $data['chrPageActive'] == 'PP') {
            $PublicationsCategoryArr['varPassword'] = $data['new_password'];
        } else {
            $PublicationsCategoryArr['varPassword'] = '';
        }
        if ($data['chrMenuDisplay'] == 'D') {
            $addlog = Config::get('Constant.DRAFT_SENT_FOR_APPROVAL');
        } else {
            $addlog = Config::get('Constant.SENT_FOR_APPROVAL');
        }
        $PublicationsCategoryID = CommonModel::addRecord($PublicationsCategoryArr, 'Powerpanel\PublicationsCategory\Models\PublicationsCategory');
        if (!empty($PublicationsCategoryID)) {
            $id = $PublicationsCategoryID;
            WorkflowLog::addRecord([
                'fkModuleId' => Config::get('Constant.MODULE.ID'),
                'fkRecordId' => $PublicationsCategory->id,
                'charApproval' => 'Y',
            ]);
            if (method_exists($this->MyLibrary, 'userNotificationData')) {
                $userNotificationArr = MyLibrary::userNotificationData(Config::get('Constant.MODULE.ID'));
                $userNotificationArr['fkRecordId'] = $PublicationsCategory->id;
                $userNotificationArr['txtNotification'] = 'New approval request from ' . ucfirst(auth()->user()->name) . ' (' . ucfirst(Config::get('Constant.MODULE.NAME')) . ')';
                $userNotificationArr['fkIntUserId'] = Auth::user()->id;
                $userNotificationArr['chrNotificationType'] = 'A';
                UserNotification::addRecord($userNotificationArr);
            }
            $newPublicationsCategoryObj = PublicationsCategory::getRecordForLogById($id);
            $logArr = MyLibrary::logData($id, false, $addlog);
            $logArr['varTitle'] = $newPublicationsCategoryObj->varTitle;
            Log::recordLog($logArr);
            if (Auth::user()->can('recent-updates-list')) {
                $notificationArr = MyLibrary::notificationData($id, $newPublicationsCategoryObj);
                RecentUpdates::setNotification($notificationArr);
            }
            $actionMessage = trans('publications-category::template.publications_categoryModule.successMessage');
        }
        $whereConditionsAddstar = ['id' => $PublicationsCategory->id];
        $updateAddStar = [
            'chrAddStar' => 'Y',
        ];
        CommonModel::updateRecords($whereConditionsAddstar, $updateAddStar, false, 'Powerpanel\PublicationsCategory\Models\PublicationsCategory');
    }

    public function insertApprovedRecord($updatePublicationsCategoryFields, $data, $id)
    {
        $whereConditions = ['id' => $data['fkMainRecord']];
        $updatePublicationsCategoryFields['chrAddStar'] = 'N';
        $updatePublicationsCategoryFields['chrLetest'] = 'N';
        $updatePublicationsCategoryFields['updated_at'] = date('Y-m-d H:i:s');
        $update = CommonModel::updateRecords($whereConditions, $updatePublicationsCategoryFields, false, 'Powerpanel\PublicationsCategory\Models\PublicationsCategory');
        $PublicationsCategory = PublicationsCategory::getRecordForLogById($data['fkMainRecord']);
        self::newSwapOrderEdit($data['display_order'], $PublicationsCategory);
        $whereConditions_ApproveN = ['fkMainRecord' => $data['fkMainRecord']];
        $updateToApproveN = [
            'chrApproved' => 'N',
            'intApprovedBy' => '0',
        ];
        $update = CommonModel::updateRecords($whereConditions_ApproveN, $updateToApproveN, false, 'Powerpanel\PublicationsCategory\Models\PublicationsCategory');
        $whereConditionsApprove = ['id' => $id, 'chrMain' => 'N'];
        $updateToApprove = [
            'chrApproved' => 'Y',
            'chrRollBack' => 'Y',
            'intApprovedBy' => auth()->user()->id,
        ];
        $update = CommonModel::updateRecords($whereConditionsApprove, $updateToApprove, false, 'Powerpanel\PublicationsCategory\Models\PublicationsCategory');
        if ($data['chrMenuDisplay'] == 'D') {
            $addlog = Config::get('Constant.DRAFT_RECORD_APPROVED');
        } else {
            $addlog = Config::get('Constant.RECORD_APPROVED');
        }
        $newCmsPageObj = PublicationsCategory::getRecordForLogById($id);
        $logArr = MyLibrary::logData($id, false, $addlog);
        $logArr['varTitle'] = stripslashes($newCmsPageObj->varTitle);
        Log::recordLog($logArr);
        /* notification for user to record approved */
        $careers = PublicationsCategory::getRecordForLogById($id);
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
                $where['fkRecordId'] = (isset($data['fkMainRecord']) && (int) $data['fkMainRecord'] != 0) ? $data['fkMainRecord'] : $id;
                $where['dtYes'] = 'null';
                WorkflowLog::updateRecord($flowData, $where);
                self::flushCache();
            }
        }
    }




    public function get_list()
    {
        $filterArr = [];
        $records = [];
        $records["data"] = [];
        $filterArr['orderColumnNo'] = (!empty(Request::input('order')[0]['column']) ? Request::input('order')[0]['column'] : '');
        $filterArr['orderByFieldName'] = (!empty(Request::input('columns')[$filterArr['orderColumnNo']]['name']) ? Request::input('columns')[$filterArr['orderColumnNo']]['name'] : '');
        $filterArr['orderTypeAscOrDesc'] = (!empty(Request::input('order')[0]['dir']) ? Request::input('order')[0]['dir'] : '');
        $filterArr['customFilterIdentity'] = !empty(Request::get('customFilterIdentity')) ? Request::get('customFilterIdentity') : '';
        $filterArr['statusFilter'] = !empty(Request::input('statusValue')) ? Request::input('statusValue') : '';
        $filterArr['sectorFilter'] = !empty(Request::input('sectorValue')) ? Request::input('sectorValue') : '';
        $filterArr['searchFilter'] = !empty(Request::input('searchValue')) ? Request::input('searchValue') : '';
        $filterArr['ParentCategoryFilter'] = !empty(Request::input('parentcatValue')) ? Request::input('parentcatValue') : '';
        $filterArr['personalityFilter'] = !empty(Request::input('personalityFilter')) ? Request::input('personalityFilter') : '';
        $filterArr['paymentFilter'] = !empty(Request::input('paymentFilter')) ? Request::input('paymentFilter') : '';
        $filterArr['rangeFilter'] = !empty(Request::input('rangeFilter')) ? Request::input('rangeFilter') : '';
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
        $arrResults = PublicationsCategory::getRecordListforPublicationsCategoryGrid($filterArr, $isAdmin, $ignoreId, $this->currentUserRoleSector);
        $arrResults = $this->restructureData($arrResults, $filterArr);
        $iTotalRecords = PublicationsCategory::getRecordCountforList($filterArr, true, $isAdmin, $ignoreId, $this->currentUserRoleSector);
        // $allRecordsCount = PublicationsCategory::getRecordCount(false, false, $isAdmin, $this->currentUserRoleSector);

        if (!empty($arrResults)) {
            $currentUserID = auth()->user()->id;
            $permit = [
                'canpublicationscategoryedit' => Auth::user()->can('publications-category-edit'),
                'canpublicationscategorypublish' => Auth::user()->can('publications-category-publish'),
                'canpublicationscategorydelete' => Auth::user()->can('publications-category-delete'),
                'canpublicationscategoryreviewchanges' => Auth::user()->can('publications-category-reviewchanges'),
                'canloglist' => Auth::user()->can('log-list'),
            ];

            foreach ($arrResults as $key => $value) {
                if (!in_array($value->id, $ignoreId)) {
                    $records['data'][] = $this->tableData($value, $permit, $currentUserID);
                }
            }
        }

        $NewRecordsCount = PublicationsCategory::getNewRecordsCount($isAdmin, $this->currentUserRoleSector);
        $records["newRecordCount"] = $NewRecordsCount;
        if (!empty(Request::input('customActionType')) && Request::input('customActionType') == 'group_action') {
            $records['customActionStatus'] = 'OK';
        }
        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;
        return json_encode($records);
    }

    public function get_list_New()
    {
        $filterArr = [];
        $records = [];
        $records["data"] = [];
        $filterArr['orderColumnNo'] = (!empty(Request::input('order')[0]['column']) ? Request::input('order')[0]['column'] : '');
        $filterArr['orderByFieldName'] = (!empty(Request::input('columns')[$filterArr['orderColumnNo']]['name']) ? Request::input('columns')[$filterArr['orderColumnNo']]['name'] : '');
        $filterArr['orderTypeAscOrDesc'] = (!empty(Request::input('order')[0]['dir']) ? Request::input('order')[0]['dir'] : '');
        $filterArr['customFilterIdentity'] = !empty(Request::get('customFilterIdentity')) ? Request::get('customFilterIdentity') : '';
        $filterArr['statusFilter'] = !empty(Request::input('statusValue')) ? Request::input('statusValue') : '';
        $filterArr['sectorFilter'] = !empty(Request::input('sectorValue')) ? Request::input('sectorValue') : '';
        $filterArr['searchFilter'] = !empty(Request::input('searchValue')) ? Request::input('searchValue') : '';
        $filterArr['PublicationsCategoryFilter'] = !empty(Request::input('PublicationsCategoryFilter')) ? Request::input('PublicationsCategoryFilter') : '';
        $filterArr['personalityFilter'] = !empty(Request::input('personalityFilter')) ? Request::input('personalityFilter') : '';
        $filterArr['paymentFilter'] = !empty(Request::input('paymentFilter')) ? Request::input('paymentFilter') : '';
        $filterArr['rangeFilter'] = !empty(Request::input('rangeFilter')) ? Request::input('rangeFilter') : '';
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
        $arrResults = PublicationsCategory::getRecordListApprovalTab($filterArr, $isAdmin, $ignoreId, $this->currentUserRoleSector);
        $iTotalRecords = PublicationsCategory::getRecordCountListApprovalTab($filterArr, true, $isAdmin, $ignoreId, $this->currentUserRoleSector);

        if (!empty($arrResults)) {
            $currentUserID = auth()->user()->id;
            $permit = [
                'canpublicationscategoryedit' => Auth::user()->can('publications-category-edit'),
                'canpublicationscategorypublish' => Auth::user()->can('publications-category-publish'),
                'canpublicationscategorydelete' => Auth::user()->can('publications-category-delete'),
                'canpublicationscategoryreviewchanges' => Auth::user()->can('publications-category-reviewchanges'),
                'canloglist' => Auth::user()->can('log-list'),
            ];

            foreach ($arrResults as $key => $value) {
                if (!in_array($value->id, $ignoreId)) {
                    $records['data'][] = $this->tableDataTab1($value, $permit, $currentUserID);
                }
            }
        }

        $NewRecordsCount = PublicationsCategory::getNewRecordsCount($isAdmin, $this->currentUserRoleSector);
        $records["newRecordCount"] = $NewRecordsCount;
        if (!empty(Request::input('customActionType')) && Request::input('customActionType') == 'group_action') {
            $records['customActionStatus'] = 'OK';
        }
        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;
        return json_encode($records);
    }

    public function get_list_favorite()
    {
        $filterArr = [];
        $records = [];
        $records["data"] = [];
        $filterArr['orderColumnNo'] = (!empty(Request::input('order')[0]['column']) ? Request::input('order')[0]['column'] : '');
        $filterArr['orderByFieldName'] = (!empty(Request::input('columns')[$filterArr['orderColumnNo']]['name']) ? Request::input('columns')[$filterArr['orderColumnNo']]['name'] : '');
        $filterArr['orderTypeAscOrDesc'] = (!empty(Request::input('order')[0]['dir']) ? Request::input('order')[0]['dir'] : '');
        $filterArr['customFilterIdentity'] = !empty(Request::get('customFilterIdentity')) ? Request::get('customFilterIdentity') : '';
        $filterArr['statusFilter'] = !empty(Request::input('statusValue')) ? Request::input('statusValue') : '';
        $filterArr['sectorFilter'] = !empty(Request::input('sectorValue')) ? Request::input('sectorValue') : '';
        $filterArr['searchFilter'] = !empty(Request::input('searchValue')) ? Request::input('searchValue') : '';
        $filterArr['PublicationsCategoryFilter'] = !empty(Request::input('PublicationsCategoryFilter')) ? Request::input('PublicationsCategoryFilter') : '';
        $filterArr['personalityFilter'] = !empty(Request::input('personalityFilter')) ? Request::input('personalityFilter') : '';
        $filterArr['paymentFilter'] = !empty(Request::input('paymentFilter')) ? Request::input('paymentFilter') : '';
        $filterArr['rangeFilter'] = !empty(Request::input('rangeFilter')) ? Request::input('rangeFilter') : '';
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
        $arrResults = PublicationsCategory::getFavoriteRecordListforPublicationsCategoryGrid($filterArr, $isAdmin, $ignoreId, $this->currentUserRoleSector);
        $iTotalRecords = PublicationsCategory::getRecordCountforListFavorite($filterArr, true, $isAdmin, $ignoreId, $this->currentUserRoleSector);

        if (!empty($arrResults)) {
            $currentUserID = auth()->user()->id;
            $permit = [
                'canpublicationscategoryedit' => Auth::user()->can('publications-category-edit'),
                'canpublicationscategorypublish' => Auth::user()->can('publications-category-publish'),
                'canpublicationscategorydelete' => Auth::user()->can('publications-category-delete'),
                'canpublicationscategoryreviewchanges' => Auth::user()->can('publications-category-reviewchanges'),
                'canloglist' => Auth::user()->can('log-list'),
            ];

            foreach ($arrResults as $key => $value) {
                if (!in_array($value->id, $ignoreId)) {
                    $records['data'][] = $this->tableDataFavorite($value, $permit, $currentUserID);
                }
            }
        }

        $NewRecordsCount = PublicationsCategory::getNewRecordsCount($isAdmin, $this->currentUserRoleSector);
        $records["newRecordCount"] = $NewRecordsCount;
        if (!empty(Request::input('customActionType')) && Request::input('customActionType') == 'group_action') {
            $records['customActionStatus'] = 'OK';
        }
        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;
        return json_encode($records);
    }

    public function get_list_draft()
    {
        $filterArr = [];
        $records = [];
        $records["data"] = [];
        $filterArr['orderColumnNo'] = (!empty(Request::input('order')[0]['column']) ? Request::input('order')[0]['column'] : '');
        $filterArr['orderByFieldName'] = (!empty(Request::input('columns')[$filterArr['orderColumnNo']]['name']) ? Request::input('columns')[$filterArr['orderColumnNo']]['name'] : '');
        $filterArr['orderTypeAscOrDesc'] = (!empty(Request::input('order')[0]['dir']) ? Request::input('order')[0]['dir'] : '');
        $filterArr['customFilterIdentity'] = !empty(Request::get('customFilterIdentity')) ? Request::get('customFilterIdentity') : '';
        $filterArr['statusFilter'] = !empty(Request::input('statusValue')) ? Request::input('statusValue') : '';
        $filterArr['sectorFilter'] = !empty(Request::input('sectorValue')) ? Request::input('sectorValue') : '';
        $filterArr['searchFilter'] = !empty(Request::input('searchValue')) ? Request::input('searchValue') : '';
        $filterArr['PublicationsCategoryFilter'] = !empty(Request::input('PublicationsCategoryFilter')) ? Request::input('PublicationsCategoryFilter') : '';
        $filterArr['personalityFilter'] = !empty(Request::input('personalityFilter')) ? Request::input('personalityFilter') : '';
        $filterArr['paymentFilter'] = !empty(Request::input('paymentFilter')) ? Request::input('paymentFilter') : '';
        $filterArr['rangeFilter'] = !empty(Request::input('rangeFilter')) ? Request::input('rangeFilter') : '';
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
        $arrResults = PublicationsCategory::getDraftRecordListforPublicationsCategoryGrid($filterArr, $isAdmin, $ignoreId, $this->currentUserRoleSector);
        $iTotalRecords = PublicationsCategory::getRecordCountforListDarft($filterArr, true, $isAdmin, $ignoreId, $this->currentUserRoleSector);

        if (!empty($arrResults)) {
            $currentUserID = auth()->user()->id;
            $permit = [
                'canpublicationscategoryedit' => Auth::user()->can('publications-category-edit'),
                'canpublicationscategorypublish' => Auth::user()->can('publications-category-publish'),
                'canpublicationscategorydelete' => Auth::user()->can('publications-category-delete'),
                'canpublicationscategoryreviewchanges' => Auth::user()->can('publications-category-reviewchanges'),
                'canloglist' => Auth::user()->can('log-list'),
            ];

            foreach ($arrResults as $key => $value) {
                if (!in_array($value->id, $ignoreId)) {
                    $records['data'][] = $this->tableDataDraft($value, $permit, $currentUserID);
                }
            }
        }

        $NewRecordsCount = PublicationsCategory::getNewRecordsCount($isAdmin, $this->currentUserRoleSector);
        $records["newRecordCount"] = $NewRecordsCount;
        if (!empty(Request::input('customActionType')) && Request::input('customActionType') == 'group_action') {
            $records['customActionStatus'] = 'OK';
        }
        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;
        return json_encode($records);
    }

    public function get_list_trash()
    {
        $filterArr = [];
        $records = [];
        $records["data"] = [];
        $filterArr['orderColumnNo'] = (!empty(Request::input('order')[0]['column']) ? Request::input('order')[0]['column'] : '');
        $filterArr['orderByFieldName'] = (!empty(Request::input('columns')[$filterArr['orderColumnNo']]['name']) ? Request::input('columns')[$filterArr['orderColumnNo']]['name'] : '');
        $filterArr['orderTypeAscOrDesc'] = (!empty(Request::input('order')[0]['dir']) ? Request::input('order')[0]['dir'] : '');
        $filterArr['customFilterIdentity'] = !empty(Request::get('customFilterIdentity')) ? Request::get('customFilterIdentity') : '';
        $filterArr['statusFilter'] = !empty(Request::input('statusValue')) ? Request::input('statusValue') : '';
        $filterArr['sectorFilter'] = !empty(Request::input('sectorValue')) ? Request::input('sectorValue') : '';
        $filterArr['searchFilter'] = !empty(Request::input('searchValue')) ? Request::input('searchValue') : '';
        $filterArr['PublicationsCategoryFilter'] = !empty(Request::input('PublicationsCategoryFilter')) ? Request::input('PublicationsCategoryFilter') : '';
        $filterArr['personalityFilter'] = !empty(Request::input('personalityFilter')) ? Request::input('personalityFilter') : '';
        $filterArr['paymentFilter'] = !empty(Request::input('paymentFilter')) ? Request::input('paymentFilter') : '';
        $filterArr['rangeFilter'] = !empty(Request::input('rangeFilter')) ? Request::input('rangeFilter') : '';
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
        $arrResults = PublicationsCategory::getTrashRecordListforPublicationsCategoryGrid($filterArr, $isAdmin, $ignoreId, $this->currentUserRoleSector);
        $iTotalRecords = PublicationsCategory::getRecordCountforListTrash($filterArr, true, $isAdmin, $ignoreId, $this->currentUserRoleSector);

        if (!empty($arrResults)) {
            $currentUserID = auth()->user()->id;
            $permit = [
                'canpublicationscategoryedit' => Auth::user()->can('publications-category-edit'),
                'canpublicationscategorypublish' => Auth::user()->can('publications-category-publish'),
                'canpublicationscategorydelete' => Auth::user()->can('publications-category-delete'),
                'canpublicationscategoryreviewchanges' => Auth::user()->can('publications-category-reviewchanges'),
                'canloglist' => Auth::user()->can('log-list'),
            ];

            foreach ($arrResults as $key => $value) {
                if (!in_array($value->id, $ignoreId)) {
                    $records['data'][] = $this->tableDataTrash($value, $permit, $currentUserID);
                }
            }
        }

        $NewRecordsCount = PublicationsCategory::getNewRecordsCount($isAdmin, $this->currentUserRoleSector);
        $records["newRecordCount"] = $NewRecordsCount;
        if (!empty(Request::input('customActionType')) && Request::input('customActionType') == 'group_action') {
            $records['customActionStatus'] = 'OK';
        }
        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;
        return json_encode($records);
    }


    public function tableData($value, $permit, $currentUserID)
    {
        $hasRecords = Publications::getCountById($value->id);

        // Checkbox
        if ($hasRecords > 0) {
            $checkbox = '<div class="checker"><a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="bottom" title = "This category is selected in ' . trans("publications-category::template.sidebar.publications") . ', so it can&#39;t be deleted."><i style = "color:red" class = "ri-spam-line fs-16"></i></a></div>';
        }else{
            $checkbox = view('powerpanel.partials.checkbox', ['name'=>'delete', 'value'=>$value->id])->render();
        }

        // Title
        $title = $value->varTitle;


        // Category
        $parentCategoryTitle = '-';
        if (!empty($value->intParentCategoryId) && $value->intParentCategoryId > 0) {
            $catIDS[] = $value->intParentCategoryId;
            $parentCategoryName = PublicationsCategory::getParentCategoryNameBycatId($catIDS);
            $parentCategoryTitle = $parentCategoryName[0]->varTitle;
        }

        // Publish Action
        $publish_action = '';
        if ($value->chrAddStar != 'Y') {
            if ($value->chrDraft != 'D') {
                if ($permit['canpublicationscategorypublish']) {
                    if ($hasRecords == 0) {
                        if ($value->chrPublish == 'Y') {
                            $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/publications-category', 'data_alias'=>$value->id, 'title'=>trans("publications-category::template.common.publishedRecord"), 'data_value'=>'Unpublish', 'checked'=>'checked'])->render();
                        } else {
                            $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/publications-category', 'data_alias'=>$value->id, 'title'=>trans("publications-category::template.common.unpublishedRecord"), 'data_value'=>'Publish'])->render();
                        }
                    } else {
                        $publish_action .= '<a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-toggle="tooltip" title="This category is selected in ' . trans("publications-category::template.sidebar.publications") . ', so it can&#39;t be Publish/Unpublish."><i style="color:red" class="ri-toggle-line icon-publish fs-24"></i></a>';
                    }
                } else {
                    $publish_action = "-";
                }
            } else {
                if ($permit['canpublicationscategorypublish']) {
                    if ($hasRecords == 0) {
                        if ($value->chrPublish == 'Y') {
                            $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/publications-category', 'data_alias'=>$value->id, 'title'=>trans("publications-category::template.common.publishedRecord"), 'data_value'=>'Unpublish', 'checked'=>'checked'])->render();
                        } else {
                            $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/publications-category', 'data_alias'=>$value->id, 'title'=>trans("publications-category::template.common.unpublishedRecord"), 'data_value'=>'Publish'])->render();
                        }
                    } else {
                        $publish_action .= '<a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-toggle="tooltip" title="This category is selected in ' . trans("publications-category::template.sidebar.publications") . ', so it can&#39;t be Publish/Unpublish."><i style="color:red" class="ri-toggle-line icon-publish fs-24"></i></a>';
                    }
                } else {
                    $publish_action = "-";
                }
            }
        } else {
            if ($permit['canpublicationscategorypublish']) {
                $publish_action .= '<a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-toggle="tooltip" title="This publications is in Approval request so can&#39;t be Publish/Unpublish."><i style="color:red" class="ri-toggle-line icon-publish fs-24"></i></a>';
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


        // Order Arrow
        $orderArrow = "";
        $orderArrow = '<a href="javascript:;" data-parentRecordId="' . $value->intParentCategoryId . '" data-order="' . $value->intDisplayOrder . '" class="moveUp"><i class="ri-arrow-up-line" aria-hidden="true"></i></a>' . $value->DisplayOrder . ' <a href="javascript:;" data-parentRecordId="' . $value->intParentCategoryId . '" data-order="' . $value->intDisplayOrder . '" class="moveDwn"><i class="ri-arrow-down-line" aria-hidden="true"></i></a>';

        // Title Action
        $title_action = '';
        if ($permit['canpublicationscategoryedit']) {
            $Quickedit_startDate = date('Y-m-d H:i', strtotime($value->dtDateTime));
            $Quickedit_endDate = !empty($value->dtEndDateTime) ? date('Y-m-d H:i', strtotime($value->dtEndDateTime)) : 'No Expiry';

            if ($value->chrLock != 'Y') {
                if (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y') {
                    if ($permit['canpublicationscategoryreviewchanges']) {
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


        // if(File::exists(base_path() . '/packages/Powerpanel/Workflow/src/Models/Workflow.php')) {
        //     $childCount = PublicationsCategory::where('fkMainRecord', $value->id)->where('dtApprovedDateTime','!=',NULL)->count();

        //     if($this->currentUserRoleData->chrIsAdmin == 'Y' && $childCount > 1) {
        //         $log .= "<a title='Rollback to previous version'  onclick=\"rollbackToPreviousVersion('" . $value->id . "');\"  class=\"log-grid\"><i class=\"ri-history-line\" ></i></a>";
        //     }
        // }


        // All - Actions
        $logurl = url('powerpanel/log?id=' . $value->id . '&mid=' . Config::get('Constant.MODULE.ID'));
        $allActions = view('powerpanel.partials.all-actions',
                    [
                        'tabName'=>'All',
                        'canedit'=> $permit['canpublicationscategoryedit'],
                        'candelete'=>$permit['canpublicationscategorydelete'],
                        'canloglist'=>$permit['canloglist'],
                        'value'=>$value,
                        'chrIsAdmin' => $this->currentUserRoleData->chrIsAdmin,
                        'module_name'=>'publicationscategory',
                        'module_edit_url' => route('powerpanel.publications-category.edit', array('alias' => $value->id)),
                        'module_type'=>'category',
                        'viewlink' => isset($viewlink) ? $viewlink : "",
                        'linkviewLable' => isset($linkviewLable) ? $linkviewLable : "",
                        'hasRecords' => Publications::getCountById($value->id),
                        'logurl' => $logurl
                    ])->render();

                    if($permit['canpublicationscategoryedit'] || $permit['canpublicationscategorydelete']){
                        $allActions = $allActions;
                    } else {
                        $allActions = "-";
                    }


        $records = array(
            $checkbox,
            '<div class="pages_title_div_row">' . $Favorite . ' <span class="title-txt"> ' . $title . ' ' . $status . $statusdata . ' - ' . $sector . ' ' .$title_action. '</span></div>',
            $parentCategoryTitle,
            $orderArrow,
            $publish_action,
            $allActions
        );
        return $records;
    }

    public function tableDataTab1($value, $permit, $currentUserID)
    {
        $hasRecords = Publications::getCountById($value->id);

        // Checkbox
        if ($hasRecords > 0) {
            $checkbox = '<div class="checker"><a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="bottom" title = "This category is selected in ' . trans("publications-category::template.sidebar.publications") . ', so it can&#39;t be deleted."><i style = "color:red" class = "ri-spam-line fs-16"></i></a></div>';
        }else{
            $checkbox = view('powerpanel.partials.checkbox', ['name'=>'delete', 'value'=>$value->id])->render();
        }

        // Title
        $title = $value->varTitle;


        // Category
        $parentCategoryTitle = '-';
        if (!empty($value->intParentCategoryId) && $value->intParentCategoryId > 0) {
            $catIDS[] = $value->intParentCategoryId;
            $parentCategoryName = PublicationsCategory::getParentCategoryNameBycatId($catIDS);
            $parentCategoryTitle = $parentCategoryName[0]->varTitle;
        }

        // Publish Action
        $publish_action = '';
        if ($value->chrAddStar != 'Y') {
            if ($value->chrDraft != 'D') {
                if ($permit['canpublicationscategorypublish']) {
                    if ($hasRecords == 0) {
                        if ($value->chrPublish == 'Y') {
                            $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/publications-category', 'data_alias'=>$value->id, 'title'=>trans("publications-category::template.common.publishedRecord"), 'data_value'=>'Unpublish', 'checked'=>'checked'])->render();
                        } else {
                            $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/publications-category', 'data_alias'=>$value->id, 'title'=>trans("publications-category::template.common.unpublishedRecord"), 'data_value'=>'Publish'])->render();
                        }
                    } else {
                        $publish_action .= '<a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-toggle="tooltip" title="This category is selected in ' . trans("publications-category::template.sidebar.publications") . ', so it can&#39;t be Publish/Unpublish."><i style="color:red" class="ri-toggle-line icon-publish fs-24"></i></a>';
                    }
                } else {
                    $publish_action = "-";
                }
            } else {
                if ($permit['canpublicationscategorypublish']) {
                    if ($hasRecords == 0) {
                        if ($value->chrPublish == 'Y') {
                            $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/publications-category', 'data_alias'=>$value->id, 'title'=>trans("publications-category::template.common.publishedRecord"), 'data_value'=>'Unpublish', 'checked'=>'checked'])->render();
                        } else {
                            $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/publications-category', 'data_alias'=>$value->id, 'title'=>trans("publications-category::template.common.unpublishedRecord"), 'data_value'=>'Publish'])->render();
                        }
                    } else {
                        $publish_action .= '<a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-toggle="tooltip" title="This category is selected in ' . trans("publications-category::template.sidebar.publications") . ', so it can&#39;t be Publish/Unpublish."><i style="color:red" class="ri-toggle-line icon-publish fs-24"></i></a>';
                    }
                } else {
                    $publish_action = "-";
                }
            }
        } else {
            if ($permit['canpublicationscategorypublish']) {
                $publish_action .= '<a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-toggle="tooltip" title="This publications is in Approval request so can&#39;t be Publish/Unpublish."><i style="color:red" class="ri-toggle-line icon-publish fs-24"></i></a>';
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
        if ($permit['canpublicationscategoryedit']) {
            $Quickedit_startDate = date('Y-m-d H:i', strtotime($value->dtDateTime));
            $Quickedit_endDate = !empty($value->dtEndDateTime) ? date('Y-m-d H:i', strtotime($value->dtEndDateTime)) : 'No Expiry';

            if ($value->chrLock != 'Y') {
                if (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y') {
                    if ($permit['canpublicationscategoryreviewchanges']) {
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
        $logurl = url('powerpanel/log?id=' . $value->id . '&mid=' . Config::get('Constant.MODULE.ID'));
        $allActions = view('powerpanel.partials.all-actions',
                    [
                        'tabName'=>'Approval',
                        'canedit'=> $permit['canpublicationscategoryedit'],
                        'candelete'=>$permit['canpublicationscategorydelete'],
                        'canloglist'=>$permit['canloglist'],
                        'value'=>$value,
                        'chrIsAdmin' => $this->currentUserRoleData->chrIsAdmin,
                        'module_name'=>'publicationscategory',
                        'module_edit_url' => route('powerpanel.publications-category.edit', array('alias' => $value->id)),
                        'module_type'=>'category',
                        'viewlink' => isset($viewlink) ? $viewlink : "",
                        'linkviewLable' => isset($linkviewLable) ? $linkviewLable : "",
                        'hasRecords' => Publications::getCountById($value->id),
                        'logurl' => $logurl
                    ])->render();

                    if($permit['canpublicationscategoryedit'] || $permit['canpublicationscategorydelete']){
                        $allActions = $allActions;
                    } else {
                        $allActions = "-";
                    }


        $records = array(
            $checkbox,
            '<div class="pages_title_div_row">' . $Favorite . ' <span class="title-txt"> ' . $title . ' ' . $status . $statusdata . ' - ' . $sector . ' ' .$title_action. '</span></div>',
            $parentCategoryTitle,
            $publish_action,
            $allActions
        );
        return $records;
    }

    public function tableDataFavorite($value, $permit, $currentUserID)
    {
        $hasRecords = Publications::getCountById($value->id);

        // Checkbox
        if ($hasRecords > 0) {
            $checkbox = '<div class="checker"><a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="bottom" title = "This category is selected in ' . trans("publications-category::template.sidebar.publications") . ', so it can&#39;t be deleted."><i style = "color:red" class = "ri-spam-line fs-16"></i></a></div>';
        }else{
            $checkbox = view('powerpanel.partials.checkbox', ['name'=>'delete', 'value'=>$value->id])->render();
        }

        // Title
        $title = $value->varTitle;


        // Category
        $parentCategoryTitle = '-';
        if (!empty($value->intParentCategoryId) && $value->intParentCategoryId > 0) {
            $catIDS[] = $value->intParentCategoryId;
            $parentCategoryName = PublicationsCategory::getParentCategoryNameBycatId($catIDS);
            $parentCategoryTitle = $parentCategoryName[0]->varTitle;
        }

        // Publish Action
        $publish_action = '';
        if ($value->chrAddStar != 'Y') {
            if ($value->chrDraft != 'D') {
                if ($permit['canpublicationscategorypublish']) {
                    if ($hasRecords == 0) {
                        if ($value->chrPublish == 'Y') {
                            $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/publications-category', 'data_alias'=>$value->id, 'title'=>trans("publications-category::template.common.publishedRecord"), 'data_value'=>'Unpublish', 'checked'=>'checked'])->render();
                        } else {
                            $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/publications-category', 'data_alias'=>$value->id, 'title'=>trans("publications-category::template.common.unpublishedRecord"), 'data_value'=>'Publish'])->render();
                        }
                    } else {
                        $publish_action .= '<a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-toggle="tooltip" title="This category is selected in ' . trans("publications-category::template.sidebar.publications") . ', so it can&#39;t be Publish/Unpublish."><i style="color:red" class="ri-toggle-line icon-publish fs-24"></i></a>';
                    }
                } else {
                    $publish_action = "-";
                }
            } else {
                if ($permit['canpublicationscategorypublish']) {
                    if ($hasRecords == 0) {
                        if ($value->chrPublish == 'Y') {
                            $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/publications-category', 'data_alias'=>$value->id, 'title'=>trans("publications-category::template.common.publishedRecord"), 'data_value'=>'Unpublish', 'checked'=>'checked'])->render();
                        } else {
                            $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/publications-category', 'data_alias'=>$value->id, 'title'=>trans("publications-category::template.common.unpublishedRecord"), 'data_value'=>'Publish'])->render();
                        }
                    } else {
                        $publish_action .= '<a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-toggle="tooltip" title="This category is selected in ' . trans("publications-category::template.sidebar.publications") . ', so it can&#39;t be Publish/Unpublish."><i style="color:red" class="ri-toggle-line icon-publish fs-24"></i></a>';
                    }
                } else {
                    $publish_action = "-";
                }
            }
        } else {
            if ($permit['canpublicationscategorypublish']) {
                $publish_action .= '<a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-toggle="tooltip" title="This publications is in Approval request so can&#39;t be Publish/Unpublish."><i style="color:red" class="ri-toggle-line icon-publish fs-24"></i></a>';
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
        if ($permit['canpublicationscategoryedit']) {
            $Quickedit_startDate = date('Y-m-d H:i', strtotime($value->dtDateTime));
            $Quickedit_endDate = !empty($value->dtEndDateTime) ? date('Y-m-d H:i', strtotime($value->dtEndDateTime)) : 'No Expiry';

            if ($value->chrLock != 'Y') {
                if (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y') {
                    if ($permit['canpublicationscategoryreviewchanges']) {
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
        $logurl = url('powerpanel/log?id=' . $value->id . '&mid=' . Config::get('Constant.MODULE.ID'));
        $allActions = view('powerpanel.partials.all-actions',
                    [
                        'tabName'=>'Favorite',
                        'canedit'=> $permit['canpublicationscategoryedit'],
                        'candelete'=>$permit['canpublicationscategorydelete'],
                        'canloglist'=>$permit['canloglist'],
                        'value'=>$value,
                        'chrIsAdmin' => $this->currentUserRoleData->chrIsAdmin,
                        'module_name'=>'publicationscategory',
                        'module_edit_url' => route('powerpanel.publications-category.edit', array('alias' => $value->id)),
                        'module_type'=>'category',
                        'viewlink' => isset($viewlink) ? $viewlink : "",
                        'linkviewLable' => isset($linkviewLable) ? $linkviewLable : "",
                        'hasRecords' => Publications::getCountById($value->id),
                        'logurl' => $logurl
                    ])->render();

                    if($permit['canpublicationscategoryedit'] || $permit['canpublicationscategorydelete']){
                        $allActions = $allActions;
                    } else {
                        $allActions = "-";
                    }


        $records = array(
            $checkbox,
            '<div class="pages_title_div_row">' . $Favorite . ' <span class="title-txt"> ' . $title . ' ' . $status . $statusdata . ' - ' . $sector . ' ' .$title_action. '</span></div>',
            $parentCategoryTitle,
            "-",
            $publish_action,
            $allActions
        );
        return $records;
    }

    public function tableDataDraft($value, $permit, $currentUserID)
    {
        $hasRecords = Publications::getCountById($value->id);

        // Checkbox
        if ($hasRecords > 0) {
            $checkbox = '<div class="checker"><a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="bottom" title = "This category is selected in ' . trans("publications-category::template.sidebar.publications") . ', so it can&#39;t be deleted."><i style = "color:red" class = "ri-spam-line fs-16"></i></a></div>';
        }else{
            $checkbox = view('powerpanel.partials.checkbox', ['name'=>'delete', 'value'=>$value->id])->render();
        }

        // Title
        $title = $value->varTitle;


        // Category
        $parentCategoryTitle = '-';
        if (!empty($value->intParentCategoryId) && $value->intParentCategoryId > 0) {
            $catIDS[] = $value->intParentCategoryId;
            $parentCategoryName = PublicationsCategory::getParentCategoryNameBycatId($catIDS);
            $parentCategoryTitle = $parentCategoryName[0]->varTitle;
        }

        // Publish Action
        $publish_action = '';
        if ($value->chrAddStar != 'Y') {
            if ($value->chrDraft != 'D') {
                if ($permit['canpublicationscategorypublish']) {
                    if ($hasRecords == 0) {
                        if ($value->chrPublish == 'Y') {
                            $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/publications-category', 'data_alias'=>$value->id, 'title'=>trans("publications-category::template.common.publishedRecord"), 'data_value'=>'Unpublish', 'checked'=>'checked'])->render();
                        } else {
                            $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/publications-category', 'data_alias'=>$value->id, 'title'=>trans("publications-category::template.common.unpublishedRecord"), 'data_value'=>'Publish'])->render();
                        }
                    } else {
                        $publish_action .= '<a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-toggle="tooltip" title="This category is selected in ' . trans("publications-category::template.sidebar.publications") . ', so it can&#39;t be Publish/Unpublish."><i style="color:red" class="ri-toggle-line icon-publish fs-24"></i></a>';
                    }
                } else {
                    $publish_action = "-";
                }
            } else {
                if ($permit['canpublicationscategorypublish']) {
                    if ($hasRecords == 0) {
                        if ($value->chrPublish == 'Y') {
                            $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/publications-category', 'data_alias'=>$value->id, 'title'=>trans("publications-category::template.common.publishedRecord"), 'data_value'=>'Unpublish', 'checked'=>'checked'])->render();
                        } else {
                            $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/publications-category', 'data_alias'=>$value->id, 'title'=>trans("publications-category::template.common.unpublishedRecord"), 'data_value'=>'Publish'])->render();
                        }
                    } else {
                        $publish_action .= '<a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-toggle="tooltip" title="This category is selected in ' . trans("publications-category::template.sidebar.publications") . ', so it can&#39;t be Publish/Unpublish."><i style="color:red" class="ri-toggle-line icon-publish fs-24"></i></a>';
                    }
                } else {
                    $publish_action = "-";
                }
            }
        } else {
            if ($permit['canpublicationscategorypublish']) {
                $publish_action .= '<a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-toggle="tooltip" title="This publications is in Approval request so can&#39;t be Publish/Unpublish."><i style="color:red" class="ri-toggle-line icon-publish fs-24"></i></a>';
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
        if ($permit['canpublicationscategoryedit']) {
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
                        'tabName'=>'Draft',
                        'canedit'=> $permit['canpublicationscategoryedit'],
                        'candelete'=>$permit['canpublicationscategorydelete'],
                        'canloglist'=>$permit['canloglist'],
                        'value'=>$value,
                        'chrIsAdmin' => $this->currentUserRoleData->chrIsAdmin,
                        'module_name'=>'publicationscategory',
                        'module_edit_url' => route('powerpanel.publications-category.edit', array('alias' => $value->id)),
                        'module_type'=>'category',
                        'viewlink' => isset($viewlink) ? $viewlink : "",
                        'linkviewLable' => isset($linkviewLable) ? $linkviewLable : "",
                        'hasRecords' => Publications::getCountById($value->id),
                        'logurl' => $logurl
                    ])->render();

                    if($permit['canpublicationscategoryedit'] || $permit['canpublicationscategorydelete']){
                        $allActions = $allActions;
                    } else {
                        $allActions = "-";
                    }


        $records = array(
            $checkbox,
            '<div class="pages_title_div_row"> <span class="title-txt"> ' . $title . ' ' . $status . $statusdata . ' - ' . $sector . ' ' .$title_action. '</span></div>',
            $parentCategoryTitle,
            $publish_action,
            $allActions
        );
        return $records;
    }

    public function tableDataTrash($value, $permit, $currentUserID)
    {
        $hasRecords = Publications::getCountById($value->id);

        // Checkbox
        if ($hasRecords > 0) {
            $checkbox = '<div class="checker"><a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="bottom" title = "This category is selected in ' . trans("publications-category::template.sidebar.publications") . ', so it can&#39;t be deleted."><i style = "color:red" class = "ri-spam-line fs-16"></i></a></div>';
        }else{
            $checkbox = view('powerpanel.partials.checkbox', ['name'=>'delete', 'value'=>$value->id])->render();
        }

        // Title
        $title = $value->varTitle;


        // Category
        $parentCategoryTitle = '-';
        if (!empty($value->intParentCategoryId) && $value->intParentCategoryId > 0) {
            $catIDS[] = $value->intParentCategoryId;
            $parentCategoryName = PublicationsCategory::getParentCategoryNameBycatId($catIDS);
            $parentCategoryTitle = $parentCategoryName[0]->varTitle;
        }


        // Title Action
        $title_action = '';
        if ($permit['canpublicationscategoryedit']) {
            $Quickedit_startDate = date('Y-m-d H:i', strtotime($value->dtDateTime));
            $Quickedit_endDate = !empty($value->dtEndDateTime) ? date('Y-m-d H:i', strtotime($value->dtEndDateTime)) : 'No Expiry';

            if ($value->chrLock != 'Y') {
                if (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y') {
                    if (Config::get('Constant.DEFAULT_QUICK') == 'Y') {
                        $title_action .= '<span class="show-hover"><a data-bs-toggle="tooltip" data-bs-placement="bottom" title="Quick Edit" href=\'javascript:void(0);\' data-bs-toggle=\'modal\' data-bs-target=\'#modalForm\' aria-label=\'Quick edit\' onclick=\'Quickeditfun("' . $value->id . '","' . $value->varTitle . '","' . $value->intSearchRank . '","' . $Quickedit_startDate . '","' . $Quickedit_endDate . '","P")\'><i class="ri-edit-2-line fs-16"></i></a></span>';
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
                        'canedit'=> $permit['canpublicationscategoryedit'],
                        'candelete'=>$permit['canpublicationscategorydelete'],
                        'canloglist'=>$permit['canloglist'],
                        'value'=>$value,
                        'chrIsAdmin' => $this->currentUserRoleData->chrIsAdmin,
                        'module_name'=>'publicationscategory',
                        'module_edit_url' => route('powerpanel.publications-category.edit', array('alias' => $value->id)),
                        'module_type'=>'category',
                        'viewlink' => isset($viewlink) ? $viewlink : "",
                        'linkviewLable' => isset($linkviewLable) ? $linkviewLable : "",
                        'hasRecords' => Publications::getCountById($value->id),
                        'logurl' => $logurl
                    ])->render();

                    if($permit['canpublicationscategoryedit'] || $permit['canpublicationscategorydelete']){
                        $allActions = $allActions;
                    } else {
                        $allActions = "-";
                    }


        $records = array(
            $checkbox,
            '<div class="pages_title_div_row"> <span class="title-txt"> ' . $title . ' ' . $status . $statusdata . ' - ' . $sector . ' ' .$title_action. '</span></div>',
            $parentCategoryTitle,
            $allActions
        );
        return $records;
    }




    public function DeleteRecord(Request $request)
    {
        /* new code for delete and reorder functionality */
        $value = Request::input('value');
        $data['ids'] = Request::input('ids');
        $moduleHaveFields = ['chrMain'];
        $update = Self::deleteMultipleRecords($data);
        if (File::exists(app_path() . '/Comments.php') != null || File::exists(base_path() . '/packages/Powerpanel/Workflow/src/Models/Comments.php') != null) {
            Comments::deleteComments($data['ids'], Config::get('Constant.MODULE.MODEL_NAME'));
        }
        foreach ($update as $ids) {
            $ignoreDeleteScope = true;
            $Deleted_Record = PublicationsCategory::getRecordById($ids, $ignoreDeleteScope);
            $Cnt_Letest = PublicationsCategory::getRecordCount_letest($Deleted_Record['fkMainRecord'], $Deleted_Record['id']);
            if ($Cnt_Letest <= 0) {
                $updateLetest = [
                    'chrAddStar' => 'N',
                ];
                $whereConditionsApprove = ['id' => $Deleted_Record['fkMainRecord']];
                CommonModel::updateRecords($whereConditionsApprove, $updateLetest, false, 'Powerpanel\PublicationsCategory\Models\PublicationsCategory');
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
                if ($value != "P" && $value != "F") {
                    #code for delete alias from database
                    Alias::where('id', $Deleted_Record['intAliasId'])
                        ->where('intFkModuleCode', Config::get('Constant.MODULE.ID'))
                        ->delete();
                }
            }
        }
        PublicationsCategory::ReorderAllrecords();
        $this->flushCache();
        echo json_encode($update);
        exit;
    }

    public static function deleteMultipleRecords($data)
    {
        $response = false;
        $responseAr = [];
        if (!empty($data)) {
            $updateFields = ['chrDelete' => 'Y', 'chrPublish' => 'N'];
            $whereINConditions = $data['ids'];
            $update = CommonModel::updateMultipleRecords($whereINConditions, $updateFields, false, 'Powerpanel\PublicationsCategory\Models\PublicationsCategory');
            foreach ($data['ids'] as $key => $id) {
                if ($update) {
                    $objModule = PublicationsCategory::getRecordsForDeleteById($id);
                    if (isset($objModule->intDisplayOrder)) {
                        self::newReOrderDisplayOrder($objModule->intParentCategoryId);
                    }
                    if (!empty($id)) {
                        $logArr = MyLibrary::logData($id);
                        $title = '-';
                        if (isset($objModule->varTitle)) {
                            $title = $objModule->varTitle;
                        } else if (isset($objModule->varName)) {
                            $title = $objModule->varName;
                        } else if (isset($objModule->name)) {
                            $title = $objModule->name;
                        }
                        $logArr['varTitle'] = stripslashes($title);
                        Log::recordLog($logArr);
                        array_push($responseAr, $objModule->id);
                        $updateRecentUpdatesFilelds = ['chrRecordDelete' => 'Y'];
                        if (Auth::user()->can('recent-updates-list')) {
                            $notificationUpdate = RecentUpdates::updateRecords($id, $updateRecentUpdatesFilelds);
                            if ($notificationUpdate) {
                                $notificationArr = MyLibrary::notificationData($id, $objModule);
                                RecentUpdates::setNotification($notificationArr);
                            }
                        }
                    }
                }
            }
            $response = $responseAr;
        }
        return $response;
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
        $parentRecordId = Request::input('parentRecordId');
        $recordID = '';
        self::swapOrder($order, $exOrder, $parentRecordId);
        $this->flushCache();
    }

    /**
     * This method handels swapping of available order record while editing
     * @param      order
     * @return  order
     * @since   2016-12-23
     * @author  NetQuick
     */
    public static function swapOrder($order = null, $exOrder = null, $parentRecordId = false, $recordID = false)
    {
        $recEx = PublicationsCategory::getRecordByOrderByParent($exOrder, $parentRecordId);
        if (!empty($recEx)) {
            $recCur = PublicationsCategory::getRecordByOrderByParent($order, $parentRecordId);
            if ($recCur->intDisplayOrder != $recEx->intDisplayOrder) {
                $whereConditionsForEx = ['id' => $recEx['id']];
                CommonModel::updateRecords($whereConditionsForEx, ['intDisplayOrder' => $recCur->intDisplayOrder], false, 'Powerpanel\PublicationsCategory\Models\PublicationsCategory');
                $whereConditionsForCur = ['id' => $recCur['id']];
                CommonModel::updateRecords($whereConditionsForCur, ['intDisplayOrder' => $recEx->intDisplayOrder], false, 'Powerpanel\PublicationsCategory\Models\PublicationsCategory');
            }
        }
        self::newReOrderDisplayOrder($parentRecordId);
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
        $alias = Request::input('alias');
        $update = MyLibrary::setPublishUnpublish($alias, $val, 'Powerpanel\PublicationsCategory\Models\PublicationsCategory');
        $this->flushCache();
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
        $startDate = date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($data->dtDateTime));
        $endDate = !empty($data->dtEndDateTime) ? date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($data->dtEndDateTime)) : 'No Expiry';
        $returnHtml = '';
        $returnHtml .= '<table class="new_table_desing table table-striped table-bordered table-hover">
														<thead>
				<tr>
																		<th align="center">' . trans("publications-category::template.common.title") . '</th>
																		<th align="center">' . trans("publications-category::template.common.parentCategory") . '</th>
																		<th align="center">Start date</th>
																		<th align="center">End date</th>
																		<th align="center">Meta Title</th>
																																 <th align="center">Meta Description</th>
																		<th align="center">' . trans("publications-category::template.common.displayorder") . '</th>
																		<th align="center">' . trans("publications-category::template.common.publish") . '</th>
				</tr>
														</thead>
														<tbody>
				<tr>
																		<td align="center">' . stripslashes(trim($data->varTitle)) . '</td>';
        if ($data->intParentCategoryId > 0) {
            $catIDS[] = $data->intParentCategoryId;
            $parentCateName = PublicationsCategory::getParentCategoryNameBycatId($catIDS);
            $parentCateName = $parentCateName[0]->varTitle;
            $returnHtml .= '<td align="center">' . $parentCateName . '</td>';
        } else {
            $returnHtml .= '<td align="center">-</td>';
        }
        $returnHtml .= '<td align="center">' . $startDate . '</td>
					<td align="center">' . $endDate . '</td>
																						<td align="center">' . stripslashes($data->varMetaTitle) . '</td>
																																				<td align="center">' . stripslashes($data->varMetaDescription) . '</td>
																				<td align="center">' . $data->intDisplayOrder . '</td>
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
        if ($data->varTitle != $newdata->varTitle) {
            $titlecolor = 'style="background-color:#f5efb7"';
        } else {
            $titlecolor = '';
        }
        if ($data->intParentCategoryId != $newdata->intParentCategoryId) {
            $catcolor = 'style="background-color:#f5efb7"';
        } else {
            $catcolor = '';
        }
        if ($data->intDisplayOrder != $newdata->intDisplayOrder) {
            $desccolor = 'style="background-color:#f5efb7"';
        } else {
            $desccolor = '';
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
        $startDate = date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($newdata->dtDateTime));
        $endDate = !empty($newdata->dtEndDateTime) ? date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($newdata->dtEndDateTime)) : 'No Expiry';
        $returnHtml = '';
        $returnHtml .= '<table class="new_table_desing table table-striped table-bordered table-hover">
														<thead>
				<tr>
																		<th align="center">' . trans("publications-category::template.common.title") . '</th>
																		<th align="center">' . trans("publications-category::template.common.parentCategory") . '</th>
																		<th align="center">Start date</th>
																		<th align="center">End date</th>
																		 <th align="center">Meta Title</th>
																																 <th align="center">Meta Description</th>
																		<th align="center">' . trans("publications-category::template.common.displayorder") . '</th>
																		<th align="center">' . trans("publications-category::template.common.publish") . '</th>
				</tr>
														</thead>
														<tbody>
				<tr>
																		<td align="center" ' . $titlecolor . '>' . stripslashes(trim($newdata->varTitle)) . '</td>';
        if ($newdata->intParentCategoryId > 0) {
            $catIDS[] = $newdata->intParentCategoryId;
            $parentCateName = PublicationsCategory::getParentCategoryNameBycatId($catIDS);
            $parentCateName = $parentCateName[0]->varTitle;
            $returnHtml .= '<td align="center" ' . $catcolor . '>' . $parentCateName . '</td>';
        } else {
            $returnHtml .= '<td align="center">-</td>';
        }
        $returnHtml .= '<td align="center" ' . $DateTimecolor . '>' . $startDate . '</td>
					<td align="center" ' . $EndDateTimecolor . '>' . $endDate . '</td>
																						 <td align="center" ' . $metatitlecolor . '>' . stripslashes($newdata->varMetaTitle) . '</td>
																			 <td align="center" ' . $metadesccolor . '>' . stripslashes($newdata->varMetaDescription) . '</td>
																				<td align="center" ' . $desccolor . '>' . $newdata->intDisplayOrder . '</td>
																				<td align="center" ' . $Publishcolor . '>' . $newdata->chrPublish . '</td>
				</tr>
														</tbody>
		</table>';
        return $returnHtml;
    }

    /**
     * This method loads PublicationsCategory edit view
     * @param   Alias of record
     * @return  View
     * @since   2017-11-10
     * @author  NetQuick
     */
    public function edit($alias = false)
    {
        $isParent = 0;
        $hasRecords = 0;
        $userIsAdmin = false;
        if (isset($this->currentUserRoleData) && !empty($this->currentUserRoleData)) {
            if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                $userIsAdmin = true;
            }
        }
        if (!is_numeric($alias)) {
            $categories = ParentRecordHierarchy_builder::Parentrecordhierarchy();
            $total = PublicationsCategory::getRecordCounter();
            if (auth()->user()->can('publications-category-create') || $userIsAdmin) {
                $total = $total + 1;
            }
            $this->breadcrumb['title'] = trans('publications-category::template.publications_categoryModule.addPublicationsCategory');
            $this->breadcrumb['module'] = trans('publications-category::template.publications_categoryModule.managePublicationsCategory');
            $this->breadcrumb['url'] = 'powerpanel/publications-category';
            $this->breadcrumb['inner_title'] = '';
            $breadcrumb = $this->breadcrumb;
            $data = compact('total', 'breadcrumb', 'categories', 'isParent', 'hasRecords');
        } else {
            $id = $alias;
            $publicationsCategory = PublicationsCategory::getRecordById($id);
            if (empty($publicationsCategory)) {
                return redirect()->route('powerpanel.publications-category.add');
            }
            $categories = ParentRecordHierarchy_builder::Parentrecordhierarchy($publicationsCategory->intParentCategoryId, $publicationsCategory->id);
            $metaInfo = array('varMetaTitle' => $publicationsCategory->varMetaTitle,
                'varMetaDescription' => $publicationsCategory->varMetaDescription);
            $this->breadcrumb['title'] = trans('publications-category::template.publications_categoryModule.editPublicationsCategory');
            $this->breadcrumb['module'] = trans('publications-category::template.publications_categoryModule.managePublicationsCategory');
            $this->breadcrumb['url'] = 'powerpanel/publications-category';
            $this->breadcrumb['inner_title'] = $publicationsCategory->varTitle;
            $breadcrumb = $this->breadcrumb;
            if (method_exists($this->MyLibrary, 'getModulePageAliasByModuleName')) {
                $categorypagereocrdlink = MyLibrary::getModulePageAliasByModuleName('publications-category');
            }
            if (!empty($categorypagereocrdlink)) {
                $varURL = $categorypagereocrdlink . '/' . $publicationsCategory->alias->varAlias;
            } else {
                $varURL = $publicationsCategory->alias->varAlias;
            }
            $metaInfo = array('varMetaTitle' => $publicationsCategory->varMetaTitle,
                'varMetaDescription' => $publicationsCategory->varMetaDescription,
                'varTags' => $publicationsCategory->varTags,
            );
            if ((int) $publicationsCategory->fkMainRecord !== 0) {
                $publicationsCategoryHighLight = PublicationsCategory::getRecordById($publicationsCategory->fkMainRecord);
                $metaInfo_highLight['varMetaTitle'] = $publicationsCategoryHighLight['varMetaTitle'];
                $metaInfo_highLight['varMetaDescription'] = $publicationsCategoryHighLight['varMetaDescription'];
                $metaInfo_highLight['varTags'] = $publicationsCategoryHighLight['varTags'];
                $isParent = PublicationsCategory::getCountById($publicationsCategory->fkMainRecord);
                $hasRecords = Publications::getCountById($publicationsCategory->fkMainRecord);
            } else {
                $metaInfo_highLight['varMetaTitle'] = "";
                $metaInfo_highLight['varMetaDescription'] = "";
                $metaInfo_highLight['varTags'] = "";
                $publicationsCategoryHighLight = "";
                $isParent = PublicationsCategory::getCountById($publicationsCategory->id);
                $hasRecords = Publications::getCountById($publicationsCategory->id);
            }
            $data = compact('publicationsCategoryHighLight', 'metaInfo_highLight', 'categories', 'isParent', 'hasRecords', 'publicationsCategory', 'metaInfo', 'breadcrumb');
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
        //End Button Name Change For User Side
        $data['userIsAdmin'] = $userIsAdmin;
        return view('publications-category::powerpanel.actions', $data);
    }

    /**
     * This method handels loading process of generating html menu from array data
     * @return  Html menu
     * @param  parentId, parentUrl, menu_array
     * @since   04-08-2017
     * @author  NetQuick
     */
    public function getChildren($CatId = false)
    {
        $serCats = PublicationsCategory::where('intParentCategoryId', $CatId)->get();
        $response = false;
        $html = '';
        foreach ($serCats as $serCat) {
            if (isset($serCat->intParentCategoryId)) {
                $html = '<ul class="dd-list menu_list_set">';
                $html .= '<li class="dd-item">';
                $html .= $serCat->varTitle;
                $html .= $this->getChildren($serCat->id);
                $html .= '</li>';
                $html .= '</ul>';
            }
        }
        $response = $html;
        return $response;
    }

    public function addCatAjax()
    {
        $data = Request::input();
        return AddQuickCategoryAjax::AddSimple($data, 'PublicationsCategory');
    }

    public static function flushCache()
    {
        Cache::tags('PublicationsCategory')->flush();
    }

    public function restructureData($elements, $filterArr)
    {
        $stringIds = array();
        $children = array();
        $onlyParentIds = array();
        // $minIds = array();
        foreach ($elements as $element) {
            $stringIds[] = $element->id;
            $stringIds[] = $element->intParentCategoryId;
            $onlyParentIds[] = $element->intParentCategoryId;
        }
        if (!empty($filterArr['searchFilter']) || !empty($filterArr['statusFilter']) || !empty($filterArr['sectorFilter'])) {
            array_push($stringIds, '0');
            //  foreach ($onlyParentIds as $Id) {
            //      $parentNodes = PublicationsCategory::getParentNodesIdsByRecordId($Id);
            //      if (!empty($parentNodes)) {
            //          $stringIds = array_merge($stringIds, $parentNodes);
            //      }
            //  }
        }
        $stringIds = array_unique($stringIds);
        $fetchData = PublicationsCategory::getRecordListforGridbyIds($stringIds, $filterArr);

        $children = array();
        if (!empty($fetchData)) {
            foreach ($fetchData as $p) {
                $pt = $p->intParentCategoryId;
                $list = isset($children[$pt]) ? $children[$pt] : array();
                array_push($list, $p);
                $children[$pt] = $list;
            }
        }
        $list = array();
        $list = $this->treerecurse(0, '&nbsp;&nbsp;&nbsp;', array(), $children, 10, 0);
        $list = array_slice($list, $filterArr['iDisplayStart'], $filterArr['iDisplayLength']);

        return $list;
    }

    public function treerecurse($id, $indent, $list = array(), $children = array(), $maxlevel = '10', $level = 0, $Type = 1, $Order = '')
    {
        $c = "";
        if (isset($children[$id]) && !empty($children[$id]) && $level <= $maxlevel) {
            foreach ($children[$id] as $c) {
                $id = $c->id;
                if ($Type) {
                    $pre = '<sup>|_</sup>&nbsp;';
                    $spacer = '.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                    $parent_order = $Order;
                } else {
                    $pre = '|_ ';
                    $spacer = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                }
                if ($c->intParentCategoryId == 0) {
                    $txt = $c->varTitle;
                    $Orderparent = $c->intDisplayOrder;
                } else {
                    $txt = $pre . $c->varTitle;
                    $Orderparent = " . " . $c->intDisplayOrder;
                }
                $pt = $c->intParentCategoryId;
                $list[$id] = $c;
                $list[$id]->treename = "$indent$txt";
                $list[$id]->children = (isset($children[$id])) ? count($children[$id]) : 0;
                $list[$id]->DisplayOrder = $Order . $Orderparent;
                $list = $this->treerecurse($id, $indent . $spacer, $list, $children, $maxlevel, $level + 1, $Type, $parent_order . $Orderparent);
            }
        }
        return $list;
    }

    /**
     * This method handels swapping of available order record while adding new function
     * @param      order
     * @return  order
     * @since   2016-10-21
     * @author  NetQuick
     */
    public static function newDisplayOrderAdd($order = null, $parentRecordId = false)
    {
        $response = false;
        $TotalRec = PublicationsCategory::getRecordCounter($parentRecordId);
        if ($parentRecordId > 0) {
            if ($TotalRec >= $order) {
                PublicationsCategory::UpdateDisplayOrder($order, $parentRecordId);
                $order = $order;
            } else {
                $order = $TotalRec + 1;
            }
        } else {
            if ($TotalRec >= $order) {
                PublicationsCategory::UpdateDisplayOrder($order);
                $order = $order;
            } else {
                $order = $TotalRec + 1;
            }
        }
        $response = (int) $order;
        return $response;
    }

    /**
     * This method handels swapping of available order record while editing
     * @param      order
     * @return  order
     * @since   2016-12-23
     * @author  NetQuick
     */
    public static function newSwapOrderEdit($order = null, $oldData = null)
    {
        $id = $oldData->id;
        $recCur = PublicationsCategory::getRecordById($id);
        if (!empty($recCur)) {
            $parentRecordId = $recCur->intParentCategoryId;
            $TotalRec = PublicationsCategory::getRecordCounter($parentRecordId);
            if ($parentRecordId > 0) {
                if ($TotalRec > $order) {
                    PublicationsCategory::UpdateDisplayOrder($order, $parentRecordId);
                    $whereConditionsForCur = ['id' => $recCur['id']];
                    CommonModel::updateRecords($whereConditionsForCur, ['intDisplayOrder' => $order], false, 'Powerpanel\PublicationsCategory\Models\PublicationsCategory');
                } else {
                    $whereConditionsForCur = ['id' => $recCur['id']];
                    CommonModel::updateRecords($whereConditionsForCur, ['intDisplayOrder' => $TotalRec + 1], false, 'Powerpanel\PublicationsCategory\Models\PublicationsCategory');
                }
            } else {
                if ($TotalRec > $order) {
                    PublicationsCategory::UpdateDisplayOrder($order);
                    $whereConditionsForCur = ['id' => $recCur['id']];
                    CommonModel::updateRecords($whereConditionsForCur, ['intDisplayOrder' => $order], false, 'Powerpanel\PublicationsCategory\Models\PublicationsCategory');
                } else {
                    $whereConditionsForCur = ['id' => $recCur['id']];
                    CommonModel::updateRecords($whereConditionsForCur, ['intDisplayOrder' => $TotalRec + 1], false, 'Powerpanel\PublicationsCategory\Models\PublicationsCategory');
                }
            }
            /* code for reoder for current data record */
            self::newReOrderDisplayOrder($parentRecordId);
            if ($parentRecordId !== $oldData->intParentCategoryId) {
                /* code for reoder for exchanbged parent data data record */
                self::newReOrderDisplayOrder($oldData->intParentCategoryId);
            }
        }
    }

    /**
     * This method handels swapping of available order record
     * @param      order
     * @return  order
     * @since   2016-10-21
     * @author  NetQuick
     */
    public static function newReOrderDisplayOrder($parentRecordId = false)
    {
        $response = false;
        $records = PublicationsCategory::getRecordForReorderByParentId($parentRecordId);
        $ids = array();
        $update_syntax = "";
        if (!empty($records)) {
            $i = 0;
            foreach ($records as $rec) {
                $i++;
                $ids[$rec->id] = $rec->id;
                $update_syntax .= " WHEN " . $rec->id . " THEN $i ";
            }
            if (!empty($ids)) {
                $when = $update_syntax;
                PublicationsCategory::updateherarachyRecords($when, $ids);
            }
        }
    }

    public function ApprovedData_Listing(Request $request)
    {
        $requestArr = Request::all();
        $request = (object) $requestArr;
        $id = Request::post('id');
        $approvalid = Request::post('id');
        $flag = Request::post('flag');
        $approvalData = PublicationsCategory::getOrderOfApproval($id);
        $main_id = Request::post('main_id');
        $Organization = PublicationsCategory::getRecordById($main_id);
        $message = PublicationsCategory::approved_data_Listing($request);
        if (!empty($approvalData)) {
            self::newSwapOrderEdit($approvalData->intDisplayOrder, $Organization);
        }
        $newCmsPageObj = PublicationsCategory::getRecordForLogById($main_id);
        $approval_obj = PublicationsCategory::getRecordForLogById($approvalid);
        if ($flag == 'R') {
            $restoredata = Config::get('Constant.ROLLBACK_RECORD');
        } else {
            if ($approval_obj->chrDraft == 'D') {
                $restoredata = Config::get('Constant.DRAFT_RECORD_APPROVED');
            } else {
                $restoredata = Config::get('Constant.RECORD_APPROVED');
            }
        }
        $newCmsPageObj = PublicationsCategory::getRecordForLogById($main_id);
        #Update record in menu
        $whereConditions = ['intRecordId' => $main_id, 'intfkModuleId' => Config::get('Constant.MODULE.ID')];
        $updateMenuFields = [
            'varTitle' => $newCmsPageObj->varTitle,
        ];
        CommonModel::updateRecords($whereConditions, $updateMenuFields, false, '\\Powerpanel\\Menu\\Models\\Menu');
        #Update record in menu
        /* notification for user to record approved */
        $careers = PublicationsCategory::getRecordForLogById($id);
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

    /**
     * This method handle to get child record.
     * @since   30-Aug-2018
     * @author  Rbhuva
     */
    public function getChildData(Request $request)
    {
        $requestArr = Request::all();
        $request = (object) $requestArr;
        $childHtml = "";
        $Publications_childData = "";
        $Publications_childData = PublicationsCategory::getChildGrid($request->id);


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


        if (count($Publications_childData) > 0) {
            foreach ($Publications_childData as $child_row) {

                $previewlink = url('/previewpage?url=' . MyLibrary::getFrontUri('publications-category')['uri'] . '/' . $child_row->id . '/preview');
                $checkbox = view('powerpanel.partials.checkbox', ['name'=>'delete', 'value'=>$child_row->id])->render();

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
                        $childHtml .= "<td class=\"text-center\"><span class='mob_show_title'>Edit: </span><a class='icon_round me-2' data-bs-toggle='tooltip' data-bs-placement='bottom' title='" . trans('publications-category::template.common.edit') . "' href='" . route('powerpanel.publications-category.edit', array('alias' => $child_row->id)) . "?tab=A'><i class='ri-pencil-line'></i></a></td>";
                    } else {
                        $childHtml .= '<td class="text-center"><span class="mob_show_title">Edit: </span>-</td>';
                    }

                    if ($child_row->chrApproved == 'N') {
                        $childHtml .= "<td class=\"text-center\"><span class='mob_show_title'>Status: </span><a class=\"approve_icon_btn me-2\" data-bs-toggle='tooltip' data-bs-placement='bottom' title='" . trans('publications-category::template.common.comments') . "' href=\"javascript:void(0);\" onclick=\"loadModelpopup('" . $child_row->id . "','" . $child_row->UserID . "','" . Config::get('Constant.MODULE.MODEL_NAME') . "','" . $child_row->fkMainRecord . "')\"><i class=\"ri-chat-1-line\"></i> </a><a class=\"approve_icon_btn me-2\" onclick=\"update_mainrecord('" . $child_row->id . "','" . $child_row->fkMainRecord . "','" . $child_row->UserID . "','A');\" data-bs-toggle='tooltip' data-bs-placement='bottom' title='" . trans('publications-category::template.common.clickapprove') . "'  href=\"javascript:void(0);\"><i class=\"ri-checkbox-line
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

    public function getChildData_rollback(Request $request)
    {
        $requestArr = Request::all();
        $request = (object) $requestArr;
        $child_rollbackHtml = "";
        $Publications_rollbackchildData = "";
        $Publications_rollbackchildData = PublicationsCategory::getChildrollbackGrid($request);
        $child_rollbackHtml .= "<div class=\"producttbl producttb2\" style=\"\">";
        $child_rollbackHtml .= "<table class=\"new_table_desing table table-striped table-bordered table-hover table-checkable dataTable\" id=\"email_log_datatable_ajax\">
																<tr role=\"row\">
																																																																<th class=\"text-center\">Title</th>
																		<th class=\"text-center\">Date</th>
																		<th class=\"text-center\">User</th>
																		<th class=\"text-center\">Preview</th>
																		<th class=\"text-center\">Status</th>";
        $child_rollbackHtml .= "         </tr>";
        if (count($Publications_rollbackchildData) > 0) {
            foreach ($Publications_rollbackchildData as $child_rollbacrow) {
                $child_rollbackHtml .= "<tr role=\"row\">";
                $child_rollbackHtml .= '<td class="text-center"><span class="mob_show_title">Title: </span>' . $child_rollbacrow->varTitle . '</td>';
                $child_rollbackHtml .= "<td class=\"text-center\"><span class='mob_show_title'>Date: </span>" . date('M d Y h:i A', strtotime($child_rollbacrow->created_at)) . "</td>";
                $child_rollbackHtml .= "<td class=\"text-center\"><span class='mob_show_title'>User: </span>" . CommonModel::getUserName($child_rollbacrow->UserID) . "</td>";
                $previewlink = url('/previewpage?url=' . MyLibrary::getFrontUri('publications-category')['uri'] . '/' . $child_rollbacrow->id . '/preview');
                $child_rollbackHtml .= "<td class=\"text-center\"><span class='mob_show_title'>Preview: </span><a class='icon_round' href=" . $previewlink . " target='_blank'><i class=\"ri-computer-line\"></i></a></td>";
                if ($child_rollbacrow->chrApproved == 'Y') {
                    $child_rollbackHtml .= "<td class=\"text-center\"><span class='mob_show_title'>Status: </span><i class=\"ri-checkbox-circle-line\" style=\"color: #1080F2;font-size:30px;\"></i></td>";
                } else {
                    // $child_rollbackHtml .= "<td class=\"text-center\"><span class='mob_show_title'>Status: </span><a onclick=\"update_mainrecord('" . $child_rollbacrow->id . "','" . $child_rollbacrow->fkMainRecord . "','" . $child_rollbacrow->UserID . "','R');\"  class=\"approve_icon_btn\">
                    //                         <i class=\"ri-history-line\"></i>  <span>RollBack</span>
                    // </a></td>";
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

    public function get_builder_list()
    {
        $records = PublicationsCategory::getAllCategory();
        $opt = '<option value="">Category</option>';
        foreach ($records as $record) {
            $opt .= '<option value="' . $record->id . '">' . $record->varTitle . '</option>';
        }
        return $opt;
    }

    public function getAllCategory()
    {
        $records = PublicationsCategory::getAllCategory();
        $opt = '<option value="">Select Category</option>';
        foreach ($records as $record) {
             $sector = '';
                if ($record->varSector != 'ofreg' && !empty($record->varSector)) {
                    $sector = $record->varSector;
                }
            $opt .= '<option value="' . $record->id . '">' . $record->varTitle .(!empty($sector) ? ' (' . strtoupper($sector) . ')' : ''). '</option>';
        }

        return $opt;
    }

    public function rollBackRecord(Request $request)
    {

        $message = 'Previous record is not available';
        $requestArr = Request::all();
        $request = (object) $requestArr;

        $previousRecord = PublicationsCategory::getPreviousRecordByMainId($request->id);
        if (!empty($previousRecord)) {

            $main_id = $previousRecord->fkMainRecord;
            $request->id = $previousRecord->id;
            $request->main_id = $main_id;

            $message = PublicationsCategory::approved_data_Listing($request);

            /* notification for user to record approved */
            $blogs = PublicationsCategory::getRecordForLogById($previousRecord->id);
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
            $newBlogObj = PublicationsCategory::getRecordForLogById($main_id);
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
