<?php
/**
 * The MenuController class handels dynamic menu configuration
 * configuration  process.
 * @package Netquick powerpanel
 * @license http://www.opensource.org/licenses/BSD-3-Clause
 * @version 1.00
 * @since 2016-12-05
 * @author NetQuick
 */
namespace Powerpanel\StaticBlocks\Controllers\Powerpanel;

use App\Alias;
use App\CommonModel;
use App\Helpers\MyLibrary;
use App\Http\Controllers\PowerpanelController;
use App\Log;
use App\RecentUpdates;
use Powerpanel\StaticBlocks\Models\StaticBlocks;
use Auth;
use Cache;
use Carbon\Carbon;
use Config;
use Illuminate\Support\Facades\Redirect;
use Request;
use Validator;
use App\Video;

class StaticBlocksController extends PowerpanelController
{

    public function __construct()
    {
        parent::__construct();
        if (isset($_COOKIE['locale'])) {
            app()->setLocale($_COOKIE['locale']);
        }
    }
    /**
     * This method handels loading process of StaticBlocks
     * @return  View
     * @since   2017-08-03
     * @author  NetQuick
     */
    public function index(Request $request)
    {
        $iTotalRecords = CommonModel::getRecordCount();
        $this->breadcrumb['title'] = trans('static-blocks::template.staticblockModule.manage');
        $netquick_admin  = Auth::user()->hasRole('netquick_admin');
        return view('static-blocks::powerpanel.list', ['iTotalRecords' => $iTotalRecords, 'breadcrumb' => $this->breadcrumb,'netquick_admin' => $netquick_admin]);
    }
    /**
     * This method loads StaticBlocks table data on view
     * @return  View
     * @since   2016-12-05
     * @author  NetQuick
     */
    public function get_list()
    {

        $filterArr = [];
        $records = [];
        $records["data"] = [];
        $filterArr['orderColumnNo'] = (!empty(Request::get('order')[0]['column']) ? Request::get('order')[0]['column'] : '');
        $filterArr['orderByFieldName'] = (!empty(Request::get('columns')[$filterArr['orderColumnNo']]['name']) ? Request::get('columns')[$filterArr['orderColumnNo']]['name'] : '');
        $filterArr['orderTypeAscOrDesc'] = (!empty(Request::get('order')[0]['dir']) ? Request::get('order')[0]['dir'] : '');
        $filterArr['statusFilter'] = !empty(Request::get('statusValue')) ? Request::get('statusValue') : '';
        $filterArr['searchFilter'] = !empty(Request::get('searchValue')) ? Request::get('searchValue') : '';
        $filterArr['iDisplayLength'] = intval(Request::get('length'));
        $filterArr['iDisplayStart'] = intval(Request::get('start'));

        $sEcho = intval(Request::get('draw'));
        $arrResults = StaticBlocks::getRecordList($filterArr);

        $iTotalRecords = StaticBlocks::getRecordCountforList($filterArr, true);

        $end = $filterArr['iDisplayStart'] + $filterArr['iDisplayLength'];
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;

        if (!empty($arrResults)) {
            foreach ($arrResults as $staticBlocks) {

                $records["data"][] = $this->tableData($staticBlocks);
            }
        }

        if (!empty(Request::get("customActionType")) && Request::get("customActionType") == "group_action") {
            $records["customActionStatus"] = "OK";
        }
        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;
        echo json_encode($records);
    }

    /**
     * This method loads StaticBlocks edit view
     * @param      Alias of record
     * @return  View
     * @since   2016-12-05
     * @author  NetQuick
     */
    public function edit($alias = false)
    {
        $imageManager = true;
        $videoManager = true;
        if (!is_numeric($alias)) {
            $total = CommonModel::getRecordCount();
            $total = $total + 1;
            $this->breadcrumb['title'] = trans('static-blocks::template.staticblockModule.add');
            $this->breadcrumb['module'] = trans('static-blocks::template.staticblockModule.manage');
            $this->breadcrumb['url'] = 'powerpanel/static-block';
            $this->breadcrumb['inner_title'] = trans('static-blocks::template.staticblockModule.add');
            $DropDownList = StaticBlocks::getRecordListDropdown();
            $data = ['total' => $total, 'breadcrumb' => $this->breadcrumb, 'imageManager' => 'imageManager', 'videoManager' => 'videoManager', 'DropDownList' => $DropDownList];
        } else {
            $id = $alias;
            $staticBlocks = StaticBlocks::getRecordById($id);
            $DropDownList = StaticBlocks::getRecordListDropdown();

            if (empty($staticBlocks)) {
                return redirect()->route('powerpanel.static-block.add');
            }
            $videoID = $staticBlocks->fkIntVideoId;
            $videoDataForSingle = video::getVideoDataForSingleVidoe($videoID);

            $this->breadcrumb['title'] = trans('static-blocks::template.common.edit') . ' - ' . $staticBlocks->varTitle;
            $this->breadcrumb['module'] = trans('static-blocks::template.staticblockModule.manage');
            $this->breadcrumb['url'] = 'powerpanel/static-block';
            $this->breadcrumb['inner_title'] = trans('static-blocks::template.common.edit') . ' - ' . $staticBlocks->varTitle;
            $data = ['staticBlocks' => $staticBlocks, 'breadcrumb' => $this->breadcrumb, 'imageManager' => 'imageManager', 'videoManager' => 'videoManager', 'DropDownList' => $DropDownList,'videoDataForSingle' => $videoDataForSingle];
        }
        return view('static-blocks::powerpanel.actions', $data);
    }

    /**
     * This method stores StaticBlocks modifications
     * @return  View
     * @since   2016-12-05
     * @author  NetQuick
     */
    public function handlePost(Request $request)
    {
        $data = Request::all();
       

        $rules = $this->serverSideValidationRules();
        $validator = Validator::make($data, $rules);

        if ($validator->passes()) {
            $id = Request::segment(3);
            $actionMessage = trans('static-blocks::template.common.oppsSomethingWrong');
            if($data['alias'] ==''){              
              $data['alias'] = $this->reCreateAlias($data['title']);
             }   
            Alias::updateAlias($data['oldAlias'], $data['alias']);

            if (is_numeric($id)) { #Edit post Handler=======
            $staticBlocksObj = StaticBlocks::getRecordForLogById($id);
                $updateStaticBlocksFields = [];
                $updateStaticBlocksFields = [
                    'varTitle' => trim($data['title']),
                    'txtDescription' => $data['description'],
                    'chrPublish' => $data['chrMenuDisplay'],
                    'intChildMenu' => !empty($data['intChildMenu']) ? $data['intChildMenu'] : null,
                    'fkIntImgId' => !empty($data['img_id']) ? $data['img_id'] : null,
                    'fkIntVideoId' => !empty($data['video_id']) ? $data['video_id'] : null,
                    'varExternalLink' => !empty($data['varExternalLink']) ? $data['varExternalLink'] : null,
                ];

                $whereConditions = ['id' => $staticBlocksObj->id];
                $update = CommonModel::updateRecords($whereConditions, $updateStaticBlocksFields, false, 'Powerpanel\StaticBlocks\Models\StaticBlocks');
                if ($update) {
                    if ($id > 0) {
                        $logArr = MyLibrary::logData($staticBlocksObj->id);
                        if (Auth::user()->can('log-advanced')) {
                            $newStaticBlocksObj = StaticBlocks::getRecordForLogById($staticBlocksObj->id);
                            $oldRec = $this->recordHistory($staticBlocksObj);
                            $newRec = $this->recordHistory($newStaticBlocksObj);
                            $logArr['old_val'] = $oldRec;
                            $logArr['new_val'] = $newRec;
                        }
                        $logArr['varTitle'] = trim($data['title']);
                        Log::recordLog($logArr);
                        if (Auth::user()->can('recent-updates-list')) {
                            if (!isset($newStaticBlocksObj)) {
                                $newStaticBlocksObj = StaticBlocks::getRecordForLogById($staticBlocksObj->id);
                            }
                            $notificationArr = MyLibrary::notificationData($staticBlocksObj->id, $newStaticBlocksObj);
                            RecentUpdates::setNotification($notificationArr);
                        }
                        self::flushCache();
                        $actionMessage = trans('static-blocks::template.staticblockModule.staticUpdated');
                    }
                }
            } else { #Add post Handler=======

                $staticBlocksArr = [];
                $staticBlocksArr['varTitle'] = trim($data['title']);
                $staticBlocksArr['intAliasId'] = MyLibrary::insertAlias($data['alias']);
                $staticBlocksArr['fkIntImgId'] = !empty($data['img_id']) ? $data['img_id'] : null;
                $staticBlocksArr['fkIntVideoId'] = !empty($data['video_id']) ? $data['video_id'] : null;
                $staticBlocksArr['varExternalLink'] = !empty($data['varExternalLink']) ? $data['varExternalLink'] : null;
                $staticBlocksArr['txtDescription'] = $data['description'];
                $staticBlocksArr['chrPublish'] = $data['chrMenuDisplay'];
                $staticBlocksArr['intChildMenu'] = $data['intChildMenu'];
                $staticBlocksArr['created_at'] = Carbon::now();

                $staticBlockID = CommonModel::addRecord($staticBlocksArr, 'Powerpanel\StaticBlocks\Models\StaticBlocks');

                if (!empty($staticBlockID) && $staticBlockID > 0) {
                    $id = $staticBlockID;
                    $newstaticBlockObj = StaticBlocks::getRecordForLogById($id);

                    $logArr = MyLibrary::logData($id);
                    $logArr['varTitle'] = $newstaticBlockObj->varTitle;
                    Log::recordLog($logArr);
                    if (Auth::user()->can('recent-updates-list')) {
                        $notificationArr = MyLibrary::notificationData($id, $newstaticBlockObj);
                        RecentUpdates::setNotification($notificationArr);
                    }
                    self::flushCache();
                    $actionMessage = trans('static-blocks::template.staticblockModule.staticAdded');
                }
            }

            if (!empty($data['saveandexit']) && $data['saveandexit'] == 'saveandexit') {
                return redirect()->route('powerpanel.static-block.index')->with('message', $actionMessage);
            } else {
                return redirect()->route('powerpanel.static-block.edit', $id)->with('message', $actionMessage);
            }

        } else {
            return Redirect::back()->withErrors($validator)->withInput();
        }
    }
    /**
     * This method loads StaticBlocks table data on view
     * @return  View
     * @since   2017-08-03
     * @author  NetQuick
     */
   public function publish(Request $request)
    {
        $alias  = Request::get('alias');
        $val  = Request::get('val');
        $update = MyLibrary::setPublishUnpublish($alias, $val, 'Powerpanel\StaticBlocks\Models\StaticBlocks');
        self::flushCache();
        echo json_encode($update);
        exit;
    }

    /**
     * This method destroy single StaticBlocks
     * @return  StaticBlocks index view
     * @since   2016-12-05
     * @author  NetQuick
     */
    public function destroy()
    {
        $data = Request::all();
        $update = MyLibrary::deleteRecord($data);
        self::flushCache();
        echo json_encode($update);
        exit;
    }

    /**
     * This method destroys multiples StaticBlocks
     * @return  StaticBlocks index view
     * @since   2016-10-25
     * @author  NetQuick
     */
    public function DeleteRecord(Request $request)
    {
        $data = Request::all('ids');
        $update = MyLibrary::deleteMultipleRecords($data, false, false, 'Powerpanel\StaticBlocks\Models\StaticBlocks');
        self::flushCache();
        echo json_encode($update);
        exit;
    }

    /**
     * This method handle table data
     * @return  array
     * @since   2016-10-25
     * @author  NetQuick
     */
    public function tableData($staticBlocks)
    {
        $actions = '';
        $details = '';
        $publish_action = '';
        $shortCode = '';
        $netquick_admin  = Auth::user()->hasRole('netquick_admin');
        if (Auth::user()->can('static-block-edit')) {
            $actions .= '<a class="without_bg_icon" title="Edit" href="' . route('powerpanel.static-block.edit', array('alias' => $staticBlocks->id)) . '">
					<i class="ri-pencil-line"></i></a>';
        }
        if (Auth::user()->can('static-block-create')) {
           if($netquick_admin){
            if (!empty($staticBlocks)) {
                $actions .= '<a href="javascript:void(0)" class="without_bg_icon highslide-active-anchor" onclick="return hs.htmlExpand(this,{width:300,headingText:\'' . "Shortcode" . '\',wrapperClassName:\'titlebar\',showCredits:false});"><span aria-hidden="true" class="fa fa-file-text-o"></span></a>';
                $actions .= '<div class="highslide-maincontent">' . $staticBlocks->alias->varAlias . '</div>';
            }
           }
        }

        if (Auth::user()->can('static-block-delete')) {
         if($netquick_admin){
            $childCount = StaticBlocks::getRecordChildListGrid()->pluck('intChildMenu')->toArray();
            if (!in_array($staticBlocks->id, $childCount)) {
                $actions .= '&nbsp;<a class="without_bg_icon delete" title="Delete" data-controller="static-block" data-alias = "' . $staticBlocks->id . '"><i class="ri-delete-bin-line"></i></a>';
            }
           }
        }
        if (Auth::user()->can('static-block-publish')) {
            if ($staticBlocks->chrPublish == 'Y') {
                //Bootstrap Switch
                $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/static-block', 'data_alias'=>$staticBlocks->id, 'title'=>trans("static-blocks::template.common.publishedRecord"), 'data_value'=>'Unpublish'])->render();
            } else {
                //Bootstrap Switch
                $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/static-block', 'data_alias'=>$staticBlocks->id, 'title'=>trans("static-blocks::template.common.unpublishedRecord"), 'data_value'=>'Publish', 'checked'=>'checked'])->render();
            }
        }
        $minus = '<span class="glyphicon glyphicon-minus"></span>';
        if (strlen($staticBlocks->txtDescription) > 0) {
            $details .= '<a href="javascript:void(0)" class="without_bg_icon highslide-active-anchor" onclick="return hs.htmlExpand(this,{width:300,headingText:\'' . trans("static-blocks::template.common.description") . '\',wrapperClassName:\'titlebar\',showCredits:false});"><span aria-hidden="true" class="fa fa-file-text-o"></span></a>';
            $details .= '<div class="highslide-maincontent">' . html_entity_decode($staticBlocks->txtDescription) . '</div>';
        } else {
            $details .= $minus;
        }

        if (Auth::user()->can('static-block-edit')) {
            $title = '<a title="Edit" href="' . route('powerpanel.static-block.edit', array('alias' => $staticBlocks->id)) . '">' . $staticBlocks->varTitle . '</a>';
        } else {
            $title = $staticBlocks->varTitle;
        }
        $update = '';
        $childCount = StaticBlocks::getRecordChildListGrid()->pluck('intChildMenu')->toArray();
        if (in_array($staticBlocks->id, $childCount)) {
            $update .= "<a id='onload_" . $staticBlocks->id . "' style=\"margin-right: 5px;\" onclick=\"javascript:expandcollapsepanel(this ,'tasklisting" . $staticBlocks->id . "', 'mainsingnimg" . $staticBlocks->id . "'," . $staticBlocks->id . ")\"><i id=\"mainsingnimg" . $staticBlocks->id . "\" class=\"fa fa-plus\"></i></a>";
        } else {
            $update .= '<input type="checkbox" name="delete" class="chkDelete" value="' . $staticBlocks->id . '">';
        }
        $records = array(
            $update,
            $title,
            $details,
            date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($staticBlocks->created_at)),
            $publish_action,
            $actions,
        );
        return $records;
    }

    /**
     * This method handle serveside validation rules
     * @return  array
     * @since   2016-10-25
     * @author  NetQuick
     */
    public function serverSideValidationRules()
    {

        $rules = array(
            'title' => 'required|max:160',
            /*'alias'=>'required'*/
        );
        return $rules;
    }

    /**
     * This method handle notification old record
     * @return  array
     * @since   2016-10-25
     * @author  NetQuick
     */
    public function recordHistory($data = false)
    {
        $returnHtml = '';
        $returnHtml .= '<table class="new_table_desing table table-striped table-bordered table-hover">
					<thead>
						<tr>
							<th>' . trans("static-blocks::template.common.title") . '</th>
							<th>' . trans("static-blocks::template.common.description") . '</th>
							<th>' . trans("static-blocks::template.common.publish") . '</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>' . $data->varTitle . '</td>
							<td>' . $data->txtDescription . '</td>
							<td>' . $data->chrPublish . '</td>
						</tr>
					</tbody>
				</table>';
        return $returnHtml;
    }

    public static function flushCache()
    {
        Cache::tags('StaticBlocks')->flush();
    }

    public function getChildData()
    {
        $netquick_admin  = Auth::user()->hasRole('netquick_admin');
        $id = Request::get('id');
        $childHtml = "";
        $staticChildData = "";
        $staticChildData = StaticBlocks::getRecordChildList($id);

				if (!empty($staticChildData)) 
				{
            $childHtml .= '<div class="producttbl">';
            $childHtml .= '<table class="new_table_desing table table-striped table-bordered table-hover table-checkable" id="static_blocks_datatable_ajax">
																		<tr role="row">
																				<th class="text-center">Title</th>
																				<th class="text-center">Description</th>
																				<th class="text-center">Date & Time</th>
																				<th class="text-center">Publish</th>
																				<th class="text-center">Actions</th>';
            $childHtml .= "         </tr>";

            foreach ($staticChildData as $child_row) {

                $parentAlias = $child_row->id;
                $publish_action = '';
                $actions = '';
                $details = '';
                if ($child_row->chrPublish == 'Y') {
                    //child_row Switch
                    $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/static-block', 'data_alias'=>$parentAlias, 'title'=>trans("static-blocks::template.common.publishedRecord"), 'data_value'=>'Unpublish'])->render();
                } else {
                    //Bootstrap Switch
                    $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/static-block', 'data_alias'=>$parentAlias, 'title'=>trans("static-blocks::template.common.unpublishedRecord"), 'data_value'=>'Publish', 'checked'=>'checked'])->render();
                }
                
                if (Auth::user()->can('static-block-edit')) {
                    $actions .= '<td><a class="without_bg_icon" title="Edit" href="' . route('powerpanel.static-block.edit', array('alias' => $parentAlias)) . '">
										<i class="ri-pencil-line"></i></a>';
                }
                if (Auth::user()->can('static-block-delete')) {
                   if($netquick_admin){
                    $actions .= '<a class="without_bg_icon delete" title="Delete" data-controller="static-block" data-alias = "' . $parentAlias . '"><i class="ri-delete-bin-line"></i></a></td>';
                   }
                }
                $minus = '<td><span class="glyphicon glyphicon-minus"></span></td>';
                if (strlen($child_row->txtDescription) > 0) {
                    $details .= '<td class="text-center"><a href="javascript:void(0)" class="without_bg_icon highslide-active-anchor" onclick="return hs.htmlExpand(this,{width:300,headingText:\'' . trans("static-blocks::template.common.description") . '\',wrapperClassName:\'titlebar\',showCredits:false});"><span aria-hidden="true" class="fa fa-file-text-o"></span></a>';
                    $details .= '<div class="highslide-maincontent">' . html_entity_decode($child_row->txtDescription) . '</div></td>';
                } else {
                    $details .= $minus;
                }

                $childHtml .= '<tr role="row">';
                //    $childHtml .= "<td><input type=\"checkbox\" name=\"delete\" class=\"chkDelete\" value=\"" . $child_row->id . "\"></td>";
                $childHtml .= '<td class="text-center">' . $child_row->varTitle . '</td>';
                $childHtml .= $details;
                $childHtml .= '<td class="text-center">' . date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($child_row->created_at)) . '</td>';
                $childHtml .= $publish_action;
                $childHtml .= $actions;
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

}
