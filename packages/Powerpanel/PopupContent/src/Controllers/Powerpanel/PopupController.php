<?php

namespace Powerpanel\PopupContent\Controllers\Powerpanel;

use App\Http\Controllers\PowerpanelController;
use Illuminate\Support\Facades\Redirect;
use App\Helpers\AddImageModelRel;
use Request;
use App\Modules;
use App\Helpers\resize_image;
use Powerpanel\PopupContent\Models\PopUpContent;
use Powerpanel\CmsPage\Models\CmsPage;
use App\Log;
use App\RecentUpdates;
use Validator;
use Auth;
use App\Helpers\MyLibrary;
use App\CommonModel;
use Carbon\Carbon;
use Config;

class PopupController extends PowerpanelController {

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
    }

    public function index() {
        $iTotalRecords = CommonModel::getRecordCount(false, false, false, 'Powerpanel\PopupContent\Models\PopUpContent');

        $this->breadcrumb['title'] = trans('popup-content::template.popupModule.managepopupcontent');
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

        return view('popup-content::powerpanel.index', compact('iTotalRecords', 'breadcrumb','settingarray'));
    }

    public static function selectRecords() {
        $popupcontent = PopUpContent::getRecordList();

        $data = Request::input();

        $module = (isset($data['id'])) ? $data['id'] : '';
        // $module = (isset($data['module'])) ? $data['module'] : '';
        $selected = (isset($data['selected']) && $data['selected'] != "") ? $data['selected'] : '';

        $recordSelect = '<option value="">'.trans('banner::template.bannerModule.selectPage').'</option>';
        if ($module != "") {
            $module = Modules::getModuleById($module);
            if ($module->varModuleNameSpace != '') {
                $model = $module->varModuleNameSpace . 'Models\\' . $module->varModelName;
            } else {
                $model = '\\App\\' . $module->varModelName;
            }

            $popageid = array();
            $popmoduleid = array();
            foreach ($popupcontent as $pop) {
                $popageid[] = $pop->fkIntPageId;
                $popmoduleid[] = $pop->fkModuleId;
            }

            if (isset($module->id)) {
                if (isset($module->id) && $module->id != 3) {
                    $moduleRec = $model::getRecordList(false, false, false);
                } else {
                    $moduleRec = $model::getRecordListforinternaldropdown(false, false, false);
                }

                foreach ($moduleRec as $record) {
                    $sector = '';
                    if ($record->varSector != 'ofreg' && !empty($record->varSector)) {
                        $sector = $record->varSector;
                    }
                    if (isset($record->id)) {
                        if ($record->chrPublish == 'Y') {
                            $exist = false;
                            if (in_array($record->id, $popageid) && in_array($module->id, $popmoduleid)) {
                                $exist = true;
                            }
                            if ($data['useraction'] == 'add') {
                                if (!$exist) {
                                    $recordSelect .= '<option data-moduleid="' . $module->id . '" value="' . $record->id . '" ' . ($record->id == $selected ? 'selected' : '') . '>' . ucwords($record->varTitle) . (!empty($sector) ? ' (' . strtoupper($sector) . ')' : '') . '</option>';
                                }
                            } else {
                                if ($exist) {
                                    $recordSelect .= '<option  data-moduleid="' . $module->id . '" value="' . $record->id . '" ' . ($record->id == $selected ? 'selected' : '') . '>' . ucwords($record->varTitle) . (!empty($sector) ? ' (' . strtoupper($sector) . ')' : '') . '</option>';
                                } else {
                                    $recordSelect .= '<option data-moduleid="' . $module->id . '" value="' . $record->id . '" ' . ($record->id == $selected ? 'selected' : '') . '>' . ucwords($record->varTitle) . (!empty($sector) ? ' (' . strtoupper($sector) . ')' : '') . '</option>';
                                }
                            }
                        }
                    }
                }
            }
        }
        return $recordSelect;
    }

    public function edit($id = false) {

        $imageManager = true;
        $modules = Modules::getFrontModuleList();
        if (!is_numeric($id)) {
            //Add Record

            $total = CommonModel::getRecordCount(false, false, false, 'Powerpanel\PopupContent\Models\PopUpContent');
            $checkdisplaybox = PopUpContent::getRecordCheck();
          $checkdisplay = count($checkdisplaybox);

            $total = $total + 1;
            $this->breadcrumb['title'] = trans('popup-content::template.popupModule.managepopupcontent');
            $this->breadcrumb['module'] = trans('popup-content::template.popupModule.managepopupcontent');
            $this->breadcrumb['url'] = 'powerpanel/popup';
            $this->breadcrumb['inner_title'] = '';
            $breadcrumb = $this->breadcrumb;
            $MyLibrary = $this->MyLibrary;

            $data = compact('total', 'breadcrumb', 'MyLibrary', 'imageManager', 'modules','checkdisplay');
        } else {
            //Edit Record
            $popupcontent = PopUpContent::getRecordById($id);
            if($popupcontent->chrDisplay == 'Y'){
                $checkdisplaybox = 1;
            }
            else{
                if($popupcontent->chrDisplay == 'N'){
                $checkedit = PopUpContent::getRecordCheck();
                 if(count($checkedit) <= 0){
                     $checkdisplaybox = 'show';
                 } 
                 else{
                     $checkdisplaybox = 'no';
                     
                 }
            }

                
            }
            if (empty($popupcontent)) {
                return redirect()->route('powerpanel.popup.add');
            }

            $this->breadcrumb['title'] = trans('popup-content::template.popupModule.editPopup');
            $this->breadcrumb['module'] = trans('popup-content::template.popupModule.managepopupcontent');
            $this->breadcrumb['url'] = 'powerpanel/popup';
            $this->breadcrumb['inner_title'] = $popupcontent->varTitle;
            $breadcrumb = $this->breadcrumb;
            $MyLibrary = $this->MyLibrary;
            $data = compact('popupcontent', 'breadcrumb', 'MyLibrary', 'imageManager', 'modules','checkdisplaybox');
        }

        return view('popup-content::powerpanel.actions', $data);
    }

    public function handlePost(Request $request) {

        $data = Request::all();     
        $actionMessage = trans('popup-content::template.common.oppsSomethingWrong');
        $settings = json_decode(Config::get("Constant.MODULE.SETTINGS"));
        $rules = array(
            'title' => 'required|max:160',
            'start_date_time' => 'required',
            'chrMenuDisplay' => 'required'
        );
        $messsages = array(
            'title.required' => trans('popup-content::template.popupModule.shortDescription'),
        );
        $validator = Validator::make($data, $rules, $messsages);
        if ($validator->passes()) {

            $popupArr = [];
            $popupArr['varTitle'] = trim($data['title']);
            
            $popupArr['fkIntImgId'] = !empty($data['img_id']) ? $data['img_id'] : null;
            $popupArr['dtStartDateTime'] = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $data['start_date_time'])));
            $popupArr['dtEndDateTime'] = (isset($data['end_date_time']) && $data['end_date_time'] != "") ? date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $data['end_date_time']))) : null;

            if(isset($data['chrDisplay']) && $data['chrDisplay'] == 'on') {

                $popupArr['chrDisplay'] = 'Y';
                $popupArr['fkModuleId'] = null;
                $popupArr['fkIntPageId'] = null;

            } else{

                $popupArr['chrDisplay'] = 'N';
                $popupArr['fkModuleId'] = $data['modules'];
                if(isset($data['foritem'] )){
                    $popupArr['fkIntPageId'] = $data['foritem'];
                }
            }

            $popupArr['chrPublish'] = $data['chrMenuDisplay'];
            $popupArr['created_at'] = Carbon::now();

            $id = Request::segment(3);
            if (is_numeric($id)) {
                #Edit post Handler=======

                $popups = PopUpContent::getRecordForLogById($id);
                $whereConditions = ['id' => $popups->id];
                $update = CommonModel::updateRecords($whereConditions, $popupArr, false, 'Powerpanel\PopupContent\Models\PopUpContent');
                if ($update) {
                    if (!empty($id)) {


                        $logArr = MyLibrary::logData($popups->id);
                        if (Auth::user()->can('log-advanced')) {
                            $newPopUpObj = PopUpContent::getRecordForLogById($popups->id);
                            $oldRec = $this->recordHistory($popups);
                            $newRec = $this->recordHistory($newPopUpObj);
                            $logArr['old_val'] = $oldRec;
                            $logArr['new_val'] = $newRec;
                        }
                        $logArr['varTitle'] = trim($data['title']);
                        Log::recordLog($logArr);
                        if (Auth::user()->can('recent-updates-list')) {
                            if (!isset($newPopUpObj)) {
                                $newPopUpObj = PopUpContent::getRecordForLogById($popups->id);
                            }
                            $notificationArr = MyLibrary::notificationData($popups->id, $newPopUpObj);
                            RecentUpdates::setNotification($notificationArr);
                        }
                    }
                    
                    $actionMessage = trans('popup-content::template.popupModule.updateMessage');
                }
            } else {

                #Add post Handler=======
                $popupID = CommonModel::addRecord($popupArr, 'Powerpanel\PopupContent\Models\PopUpContent');

                if (!empty($popupID)) {
                    $id = $popupID;
                    $newPopupObj = PopUpContent::getRecordForLogById($id);

                    $logArr = MyLibrary::logData($id);
                    $logArr['varTitle'] = $newPopupObj->varTitle;
                    Log::recordLog($logArr);
                    if (Auth::user()->can('recent-updates-list')) {
                        $notificationArr = MyLibrary::notificationData($id, $newPopupObj);
                        RecentUpdates::setNotification($notificationArr);
                    }
                    
                    $actionMessage = trans('popup-content::template.popupModule.addedMessage');
                }
            }
            AddImageModelRel::sync(explode(',', $data['img_id']), $id);
            if ((!empty(Request::get('saveandexit')) && Request::get('saveandexit') == 'saveandexit')) {
                return redirect()->route('powerpanel.popup.index')->with('message', $actionMessage);
            } else {
                return redirect()->route('powerpanel.popup.edit', $id)->with('message', $actionMessage);
            }
        } else {
            return Redirect::back()->withErrors($validator)->withInput();
        }
    }

    public function get_list() {
        $filterArr = [];
        $records = [];
        $records["data"] = [];
        $filterArr['orderColumnNo'] = (!empty(Request::get('order')[0]['column']) ? Request::get('order')[0]['column'] : '');
        $filterArr['orderByFieldName'] = (!empty(Request::get('columns')[$filterArr['orderColumnNo']]['name']) ? Request::get('columns')[$filterArr['orderColumnNo']]['name'] : '');
        $filterArr['orderTypeAscOrDesc'] = (!empty(Request::get('order')[0]['dir']) ? Request::get('order')[0]['dir'] : '');
        $filterArr['statusFilter'] = !empty(Request::get('statusValue')) ? Request::get('statusValue') : '';
        $filterArr['catFilter'] = !empty(Request::get('catValue')) ? Request::get('catValue') : '';
        $filterArr['searchFilter'] = !empty(Request::get('searchValue')) ? Request::get('searchValue') : '';
        $filterArr['iDisplayLength'] = intval(Request::get('length'));
        $filterArr['iDisplayStart'] = intval(Request::get('start'));
        $currentrecordcountstart = intval(Request::get('start'));
        $totalRecords_old = CommonModel::getTotalRecordCount('Powerpanel\PopupContent\Models\PopUpContent');
        if ($totalRecords_old > $currentrecordcountstart) {
            $filterArr['iDisplayStart'] = intval(Request::get('start'));
        } else {
            $filterArr['iDisplayStart'] = intval(0);
        }
        $sEcho = intval(Request::get('draw'));

        $isAdmin = false;
        if (isset($this->currentUserRoleData) && !empty($this->currentUserRoleData)) {
            if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                $isAdmin = true;
            }
        }

        $ignoreId = [];
        $arrResults = PopUpContent::getRecordList($filterArr, $isAdmin, $ignoreId, $this->currentUserRoleSector);
        $iTotalRecords = CommonModel::getRecordCount($filterArr, true, false, 'Powerpanel\PopupContent\Models\PopUpContent');

        if (!empty($arrResults)) {
            $currentUserID = auth()->user()->id;
            $permit = [
                'canpopupedit' => Auth::user()->can('popup-edit'),
                'canpopuppublish' => Auth::user()->can('popup-publish'),
                'canpopupdelete' => Auth::user()->can('popup-delete'),
                'canpopupreviewchanges' => Auth::user()->can('popup-reviewchanges'),
                'canloglist' => Auth::user()->can('log-list'),
            ];

            foreach ($arrResults as $key => $value) {
                if (!in_array($value->id, $ignoreId)) {
                    $records['data'][] = $this->tableData($value, $permit, $currentUserID);
                }
            }
        }

        if (!empty(Request::input('customActionType')) && Request::input('customActionType') == 'group_action') {
            $records['customActionStatus'] = 'OK';
        }
        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;
        return json_encode($records);
    }

    public function publish(Request $request) {
        $alias = Request::get('alias');
        $val = Request::get('val');
        $update = MyLibrary::setPublishUnpublish($alias, $val, 'Powerpanel\PopupContent\Models\PopUpContent');
        
        echo json_encode($update);
        exit;
    }

    public function recordHistory($data = false) {
        $returnHtml = '';
        $returnHtml .= '<table class="new_table_desing table table-striped table-bordered table-hover">
				<thead>
					<tr>
						<th>' . trans("popup-content::template.common.question") . '</th>						
						<th>' . trans("popup-content::template.common.answer") . '</th>
						<th>' . trans("popup-content::template.common.displayorder") . '</th>
						<th>' . trans("popup-content::template.common.publish") . '</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>' . $data->varTitle . '</td>						
						<td>' . $data->txtDescription . '</td>
						<td>' . ($data->intDisplayOrder) . '</td>
						<td>' . $data->chrPublish . '</td>
					</tr>
				</tbody>
			</table>';

        return $returnHtml;
    }

    public function tableData($value , $permit, $currentUserID) {

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
                if ($permit['canpopuppublish']) {
                    if ($value->chrPublish == 'Y') {
                        $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/popup', 'data_alias'=>$value->id, 'title'=>trans("template.common.publishedRecord"), 'data_value'=>'Unpublish', 'checked'=>'checked'])->render();
                    } else {
                        $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/popup', 'data_alias'=>$value->id, 'title'=>trans("template.common.unpublishedRecord"), 'data_value'=>'Publish'])->render();
                    }
                } else {
                    $publish_action = "-";
                }
            } else {
                if ($permit['canpopuppublish']) {
                    if ($value->chrPublish == 'Y') {
                        $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/popup', 'data_alias'=>$value->id, 'title'=>trans("template.common.publishedRecord"), 'data_value'=>'Unpublish', 'checked'=>'checked'])->render();
                    } else {
                        $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/popup', 'data_alias'=>$value->id, 'title'=>trans("template.common.unpublishedRecord"), 'data_value'=>'Publish'])->render();
                    }
                } else {
                    $publish_action = "-";
                }
            }
        } else {
            if ($permit['canpopuppublish']) {
                $publish_action .= '<a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-toggle="tooltip" title="This popup content is in Approval request so can&#39;t be Publish/Unpublish."><i style="color:red" class="ri-toggle-line icon-publish fs-24"></i></a>';
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



        if (!empty($value->chrDisplay) && ($value->chrDisplay == 'Y')) {
            $pages = 'All';
        } else {
            if(!empty($value->chrDisplay) && ($value->chrDisplay == 'N')){
                $module = Modules::getModuleById($value->fkModuleId);
                if (isset($module) && $module->varModuleNameSpace != '') {
                    $model = $module->varModuleNameSpace . 'Models\\' . $module->varModelName;
                }
                $record = $model::getRecordById($value->fkIntPageId);
                if(isset($record->varTitle)){
                    $pages = $record->varTitle;
                }
                else{
                    $pages = '-';
                }
            }
        }



        // Title Action
        // $title_action = '';
        // if ($permit['canpopupedit']) {
        //     $Quickedit_startDate = date('Y-m-d H:i', strtotime($value->dtDateTime));
        //     $Quickedit_endDate = !empty($value->dtEndDateTime) ? date('Y-m-d H:i', strtotime($value->dtEndDateTime)) : 'No Expiry';

        //     if ($value->chrLock != 'Y') {
        //         if (isset($this->currentUserRoleData->chrIsAdmin) && $this->currentUserRoleData->chrIsAdmin == 'Y') {
        //             if (Config::get('Constant.DEFAULT_QUICK') == 'Y') {
        //                 $title_action .= '<span class="show-hover"><a title="Quick Edit" href=\'javascript:void(0);\' data-toggle=\'modal\' data-target=\'#modalForm\' aria-label=\'Quick edit\' onclick=\'Quickeditfun("' . $value->id . '","' . $value->varTitle . '","' . $value->intSearchRank . '","' . $Quickedit_startDate . '","' . $Quickedit_endDate . '","P")\'><i class="ri-edit-2-line fs-16"></i></a></span>';
        //             }
        //         }
        //     }
        // }



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



        // Image
        $imgIcon = '';
        if (isset($value->fkIntImgId) && !empty($value->fkIntImgId)) {
            $imageArr = explode(',', $value->fkIntImgId);
            if (count($imageArr) > 1) {
                $imgIcon .= '<div class="multi_image_thumb">';
                foreach ($imageArr as $key => $image) {
                    $imgIcon .= '<a href="' . resize_image::resize($image) . '" class="fancybox-thumb" rel="fancybox-thumb-' . $value->id . '" data-rel="fancybox-thumb">';
                    $imgIcon .= '<img style="max-width:20px" title="' . preg_replace('/[^A-Za-z0-9\-]/', '-', $value->varTitle) . '" src="' . resize_image::resize($image, 50, 50) . '"/>';
                    $imgIcon .= '</a>';
                }
                $imgIcon .= '</div>';
            } else {
                $imgIcon .= '<div class="multi_image_thumb">';
                $imgIcon .= '<a href="' . resize_image::resize($value->fkIntImgId) . '" class="fancybox-buttons"  data-rel="fancybox-buttons">';
                $imgIcon .= '<img style="max-width:20px" data-bs-toggle="tooltip" data-bs-placement="bottom" title="' . preg_replace('/[^A-Za-z0-9\-]/', '-', $value->varTitle) . '" src="' . resize_image::resize($value->fkIntImgId, 50, 50) . '"/>';
                $imgIcon .= '</a>';
                $imgIcon .= '</div>';
            }
        } else {
            $imgIcon .= '-';
        }


        // All - Actions
        $logurl = url('powerpanel/log?id=' . $value->id . '&mid=' . Config::get('Constant.MODULE.ID'));
        $allActions = view('powerpanel.partials.all-actions',
                    [
                        'tabName'=>'All',
                        'canedit'=> $permit['canpopupedit'],
                        'candelete'=>$permit['canpopupdelete'],
                        'canloglist'=>$permit['canloglist'],
                        'value'=>$value,
                        'chrIsAdmin' => $this->currentUserRoleData->chrIsAdmin,
                        'module_name'=>'popup',
                        'module_edit_url' => route('powerpanel.popup.edit', array('alias' => $value->id)),
                        'module_type'=>'parent',
                        'viewlink' => isset($viewlink) ? $viewlink : "",
                        'linkviewLable' => isset($linkviewLable) ? $linkviewLable : "",
                        'logurl' => $logurl
                    ])->render();

                    if($permit['canpopupedit'] || $permit['canpopupdelete']){
                        $allActions = $allActions;
                    } else {
                        $allActions = "-";
                    }


        $records = array(
            $checkbox,
            '<div class="pages_title_div_row"> <span class="title-txt"> ' . $title . ' ' . $status . $statusdata . '</span></div>',
            $pages,
            $imgIcon,
            $publish_action,
            $allActions
        );
        return $records;
    }

    public function flushCache() {
        //Cache::forget('getFrontRecordsByPage');
    }

    public function DeleteRecord(Request $request) {
        $data = Request::all('ids');
        $update = MyLibrary::deleteMultipleRecords($data, false, false, 'Powerpanel\PopupContent\Models\PopUpContent');

        echo json_encode($update);
        exit;
    }

}
