<?php

namespace Powerpanel\ContactInfo\Controllers\Powerpanel;

use App\CommonModel;
use App\Helpers\FrontPageContent_Shield;
use App\Helpers\MyLibrary;
use App\Helpers\resize_image;
use App\Http\Controllers\PowerpanelController;
use App\Log;
use App\RecentUpdates;
use Auth;
use Cache;
use Config;
use Illuminate\Support\Facades\Redirect;
use Powerpanel\ContactInfo\Models\ContactInfo;
use Request;
use Validator;

class ContactInfoController extends PowerpanelController
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
    }

    /**
     * This method handels view of listing
     * @return  view
     * @since   2017-08-02
     * @author  NetQuick
     */
    public function index()
    {
        $total = CommonModel::getRecordCount(false, false, false,'Powerpanel\ContactInfo\Models\ContactInfo');
        $this->breadcrumb['title'] = trans('contactinfo::template.contactModule.managecontacts');
        return view('contactinfo::powerpanel.list', ['iTotalRecords' => $total, 'breadcrumb' => $this->breadcrumb]);
    }

    /**
     * This method loads contactInfo edit view
     * @param   Alias of record
     * @return  View
     * @since   2017-11-10
     * @author  NetQuick
     */
    public function edit($id = false)
    {
        $imageManager = true;
        if (!is_numeric($id)) {
            $total = CommonModel::getRecordCount(false, false, false,'Powerpanel\ContactInfo\Models\ContactInfo');
            $total = $total + 1;
            $this->breadcrumb['title'] = trans('contactinfo::template.contactModule.addnewcontact');
            $this->breadcrumb['module'] = trans('contactinfo::template.contactModule.managecontacts');
            $this->breadcrumb['url'] = 'powerpanel/contact-info';
            $this->breadcrumb['inner_title'] = '';
            $breadcrumb = $this->breadcrumb;
            $data = ['total' => $total, 'breadcrumb' => $this->breadcrumb, 'imageManager' => $imageManager];
        } else {
            $contactInfo = ContactInfo::getRecordById($id);
            if (empty($contactInfo)) {
                return redirect()->route('powerpanel.contact-info.add');
            }
            $this->breadcrumb['title'] = trans('contactinfo::template.contactModule.editnewcontact');
            $this->breadcrumb['module'] = trans('contactinfo::template.contactModule.managecontacts');
            $this->breadcrumb['url'] = 'powerpanel/contact-info';
            $this->breadcrumb['inner_title'] = $contactInfo->varTitle;
            $breadcrumb = $this->breadcrumb;
            $data = ['contactInfo' => $contactInfo, 'breadcrumb' => $this->breadcrumb, 'imageManager' => $imageManager];
        }
        return view('contactinfo::powerpanel.actions', $data);
    }

    /**
     * This method handels post of edit
     * @return  view
     * @since   2017-08-02
     * @author  NetQuick
     */
    public function handlePost(Request $request)
    {
        $postArr = Request::all();
        $postArr['order'] = 1;
        $rules = $this->serverSideValidationRules();
        $actionMessage = 'Opps... Something went wrong!';
        $validator = Validator::make($postArr, $rules);

        if ($validator->passes()) {
            // if(!empty($postArr['phone_no']) && count($postArr['phone_no'])>0) {
            //     foreach ($postArr['phone_no'] as $key => $value) {
            //         if (is_null($value) || $value == '') {
            //             unset($postArr['phone_no'][$key]);
            //         }

            //     }
            // }
            $contactInfoArr['varTitle'] = trim($postArr['name']);
            $contactInfoArr['varEmail'] = $postArr['email'];
            $contactInfoArr['varPhoneNo'] = $postArr['phone_no'];
            $contactInfoArr['varFax'] = trim($postArr['fax']);
            $contactInfoArr['txtDescription'] = trim($postArr['description']);
            $contactInfoArr['txtAddress'] = trim($postArr['address']);
            $contactInfoArr['mailingaddress'] = trim($postArr['mailingaddress']);
            $contactInfoArr['chrIsPrimary'] = $postArr['primary'];
            $contactInfoArr['chrPublish'] = isset($postArr['chrMenuDisplay']) ? $postArr['chrMenuDisplay'] : 'Y';
            $contactInfoArr['created_at'] = date('Y-m-d H:i:s');
            $contactInfoArr['updated_at'] = date('Y-m-d H:i:s');

            $id = Request::segment(3);
            if (is_numeric($id)) { #Edit post Handler=======
            $contactInfo = ContactInfo::getRecordForLogById($id);
                $whereConditions = ['id' => $id];
                $update = CommonModel::updateRecords($whereConditions, $contactInfoArr, false, 'Powerpanel\ContactInfo\Models\ContactInfo');
                if ($update) {
                    if (!empty($id)) {

                        self::swap_order_edit($postArr['order'], $id);

                        $logArr = MyLibrary::logData($id);
                        if (Auth::user()->can('log-advanced')) {
                            $newContactInfo = ContactInfo::getRecordForLogById($id);
                            $oldRec = $this->recordHistory($contactInfo);
                            $newRec = $this->newrecordHistory($contactInfo, $newContactInfo);
            //                            $newRec = $this->recordHistory($newContactInfo);
                            $logArr['old_val'] = $oldRec;
                            $logArr['new_val'] = $newRec;
                        }
                        $logArr['varTitle'] = trim($postArr['name']);
                        Log::recordLog($logArr);
                        if (Auth::user()->can('recent-updates-list')) {
                            if (!isset($newContactInfo)) {
                                $newContactInfo = ContactInfo::getRecordForLogById($id);
                            }
                            $notificationArr = MyLibrary::notificationData($id, $newContactInfo);
                            RecentUpdates::setNotification($notificationArr);
                        }
                    }
                    self::flushCache();
                    $actionMessage = 'Contact has been successfully updated.';
                }
            } else { #Add post Handler=======
                $contactInfoArr['intDisplayOrder'] = self::swap_order_add($postArr['order']);
                $contactInfoID = CommonModel::addRecord($contactInfoArr, 'Powerpanel\ContactInfo\Models\ContactInfo');
                if (!empty($contactInfoID)) {
                    $id = $contactInfoID;
                    $newContactObj = ContactInfo::getRecordForLogById($id);

                    $logArr = MyLibrary::logData($id);
                    $logArr['varTitle'] = $newContactObj->varTitle;
                    Log::recordLog($logArr);
                    if (Auth::user()->can('recent-updates-list')) {
                        $notificationArr = MyLibrary::notificationData($id, $newContactObj);
                        RecentUpdates::setNotification($notificationArr);
                    }
                    self::flushCache();
                    $actionMessage = 'Contact has been successfully added.';
                }
            }

            if($postArr['primary'] == 'Y') {
                ContactInfo::where('id', '<>', $id)
                    ->update(['chrIsPrimary' => 'N']);
            }

            //                AddImageModelRel::sync(explode(',', $postArr['img_id']), $id);

            if ((!empty(Request::get('saveandexit')) && Request::get('saveandexit') == 'saveandexit')) {
                return redirect()->route('powerpanel.contact-info.list')->with('message', $actionMessage);
            } else {
                return redirect()->route('powerpanel.contact-info.edit', $id)->with('message', $actionMessage);
            }
        } else {
            return Redirect::back()->withErrors($validator)->withInput();
        }
    }

    /**
     * This method handels listing
     * @return  view
     * @since   2017-08-02
     * @author  NetQuick
     */
    public function get_list()
    {
        $filterArr = [];
        $records = [];
        $records["data"] = [];
        $filterArr['orderColumnNo'] = (!empty(Request::input('order')[0]['column']) ? Request::input('order')[0]['column'] : '');
        $filterArr['orderByFieldName'] = (!empty(Request::input('columns')[$filterArr['orderColumnNo']]['name']) ? Request::input('columns')[$filterArr['orderColumnNo']]['name'] : '');
        $filterArr['orderTypeAscOrDesc'] = (!empty(Request::input('order')[0]['dir']) ? Request::input('order')[0]['dir'] : '');
        $filterArr['statusFilter'] = !empty(Request::input('statusValue')) ? Request::input('statusValue') : '';
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
        $arrResults = ContactInfo::getRecordList($filterArr, $isAdmin, $ignoreId, $this->currentUserRoleSector);
        $iTotalRecords = CommonModel::getRecordCount($filterArr, true, false, 'Powerpanel\ContactInfo\Models\ContactInfo');

        if (!empty($arrResults)) {
            $currentUserID = auth()->user()->id;
            $permit = [
                'cancontactinfoedit' => Auth::user()->can('contact-info-edit'),
                'cancontactinfopublish' => Auth::user()->can('contact-info-publish'),
                'cancontactinfodelete' => Auth::user()->can('contact-info-delete'),
                'cancontactinforeviewchanges' => Auth::user()->can('contact-info-reviewchanges'),
                'canloglist' => Auth::user()->can('log-list'),
            ];

            foreach ($arrResults as $key => $value) {
                if (!in_array($value->id, $ignoreId)) {
                    $records['data'][] = $this->tableData($value, $permit, $currentUserID);
                }
            }
        }

        if (!empty(Request::input("customActionType")) && Request::input("customActionType") == "group_action") {
            $records["customActionStatus"] = "OK"; // pass custom message(useful for getting status of group actions)
        }
        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;
        echo json_encode($records);
        exit;
    }

    /**
     * This method handels Publish/Unpublish
     * @return  view
     * @since   2017-08-02
     * @author  NetQuick
     */
    public function publish(Request $request)
    {
        $id = (int) Request::input('alias');
        $val = Request::get('val');
//        echo '<pre>';print_r($val);exit;
        $update = MyLibrary::setPublishUnpublish($id, $val, 'Powerpanel\ContactInfo\Models\ContactInfo');
        self::flushCache();
        echo json_encode($update);
        exit;
    }

    /**
     * This method reorders position
     * @return  Banner index view data
     * @since   2016-10-26
     * @author  NetQuick
     */
    public function reorder()
    {
        $order = Request::input('order');
        $exOrder = Request::input('exOrder');
        MyLibrary::swapOrder($order, $exOrder);
        self::flushCache();
    }

    /**
     * This method destroys multiples records
     * @return  true/false
     * @since   03-08-2017
     * @author  NetQuick
     */
    public function DeleteRecord(Request $request)
    {
        $data = Request::all('ids');
        $update = MyLibrary::deleteMultipleRecords($data, false, false, 'Powerpanel\ContactInfo\Models\ContactInfo');
        self::flushCache();
        echo json_encode($update);
        exit;
    }

    /**
     * This method handels swapping of available order record while adding
     * @param      order
     * @return  order
     * @since   2017-07-22
     * @author  NetQuick
     */
    public static function swap_order_add($order = null)
    {
        $response = false;
        if ($order != null) {
            $response = MyLibrary::swapOrderAdd($order, false, false, 'Powerpanel\ContactInfo\Models\ContactInfo');
            self::flushCache();
        }
        return $response;
    }

    /**
     * This method handels swapping of available order record while editing
     * @param      order
     * @return  order
     * @since   2017-07-22
     * @author  NetQuick
     */
    public static function swap_order_edit($order = null, $id = null)
    {
        MyLibrary::swapOrderEdit($order, $id, false, false, 'Powerpanel\ContactInfo\Models\ContactInfo');
        self::flushCache();
    }

    /**
     * This method datatable grid data
     * @return  array
     * @since   03-08-2017
     * @author  NetQuick
     */
    public function tableData($value , $permit, $currentUserID)
    {
        // Checkbox
        $checkbox = view('powerpanel.partials.checkbox', ['name'=>'delete', 'value'=>$value->id])->render();

        // StartDate
        $date = $value->created_at;
        $date = '<span align="left" data-bs-toggle="tooltip" data-bs-placement="bottom" title="'.date(Config::get("Constant.DEFAULT_DATE_FORMAT").' '.Config::get("Constant.DEFAULT_TIME_FORMAT"), strtotime($date)).'">'.date(Config::get('Constant.DEFAULT_DATE_FORMAT').' '.Config::get("Constant.DEFAULT_TIME_FORMAT"), strtotime($date)).'</span>';


        // Title
        $title = $value->varTitle;

        // Email
        $emails = $value->varEmail;


        $details='';
        if(isset($value->mailingaddress) && !empty($value->mailingaddress)){
        $details .= '<a href="javascript:void(0)" class="highslide-active-anchor" onclick="return hs.htmlExpand(this,{width:300,headingText:\'Mailing Address\',wrapperClassName:\'titlebar\',showCredits:false});"><span aria-hidden="true" class="ri-mail-open-line fs-16"></span></a>';
        $details .= '<div class="highslide-maincontent">' . nl2br($value->mailingaddress) . '</div>';
        }
        else{
        $details = '-';
        }


        // Publish Action
        $publish_action = '';
        if ($value->chrAddStar != 'Y') {
            if ($value->chrDraft != 'D') {
                if ($permit['cancontactinfopublish']) {
                    if ($value->chrPublish == 'Y') {
                        $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/contact-info', 'data_alias'=>$value->id, 'title'=>trans("contactinfo::template.common.publishedRecord"), 'data_value'=>'Unpublish', 'checked'=>'checked'])->render();
                    } else {
                        $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/contact-info', 'data_alias'=>$value->id, 'title'=>trans("contactinfo::template.common.unpublishedRecord"), 'data_value'=>'Publish'])->render();
                    }
                }
            } else {
                if ($value->chrPublish == 'Y') {
                    $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/contact-info', 'data_alias'=>$value->id, 'title'=>trans("contactinfo::template.common.publishedRecord"), 'data_value'=>'Unpublish', 'checked'=>'checked'])->render();
                } else {
                    $publish_action .= view('powerpanel.partials.bootstrap-switch', ['data_controller'=>'powerpanel/contact-info', 'data_alias'=>$value->id, 'title'=>trans("contactinfo::template.common.unpublishedRecord"), 'data_value'=>'Publish'])->render();
                }
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

        // All - Actions
        $logurl = url('powerpanel/log?id=' . $value->id . '&mid=' . Config::get('Constant.MODULE.ID'));
        $allActions = view('powerpanel.partials.all-actions',
                    [
                        'tabName'=>'All',
                        'canedit'=> $permit['cancontactinfoedit'],
                        'candelete'=>$permit['cancontactinfodelete'],
                        'canloglist'=>$permit['canloglist'],
                        'value'=>$value,
                        'chrIsAdmin' => $this->currentUserRoleData->chrIsAdmin,
                        'module_name'=>'contact-info',
                        'module_edit_url' => route('powerpanel.contact-info.edit', array('alias' => $value->id)),
                        'module_type'=>'parent',
                        'viewlink' => isset($viewlink) ? $viewlink : "",
                        'linkviewLable' => isset($linkviewLable) ? $linkviewLable : "",
                        'logurl' => $logurl
                    ])->render();


        $records = array(
            $checkbox,
            '<div class="pages_title_div_row"> <span class="title-txt"> ' . $title . ' ' . $status . $statusdata . '</span></div>',
            count((array)$emails) > 1 ? implode(',', $emails) : $emails,
            $details,
            $date,
            $publish_action,
            $allActions
        );
        return $records;
    }

    /**
     * This method handle severside validation rules
     * @return  array
     * @since   03-08-2017
     * @author  NetQuick
     */
    public function serverSideValidationRules()
    {
        $rules = array(
            'name' => 'required|max:255',
            // 'order' => 'greater_than_zero|handle_xss|no_url',
            // 'address' => 'handle_xss|no_url',
            // 'fax' => 'handle_xss|no_url',
            'email' => 'required|email',
            // 'mailingaddress' => 'handle_xss|no_url',
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
        $emails = $data->varEmail;
        $phones = $data->varPhoneNo;
        if (isset($data->varFax) && !empty($data->varFax)) {
            $varFax = $data->varFax;
        } else {
            $varFax = 'N/A';
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
					<th>' . trans("template.common.title") . '</th>
					<th>' . trans("template.common.email") . '</th>
					<th>' . trans("template.common.phoneno") . '</th>
					<th>Fax</th>
					<th>Working Hours</th>
					<th>' . trans("template.common.address") . '</th>
					<th> Mailing Address </th>
					<th>' . trans("template.common.publish") . '</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>' . $data->varTitle . '</td>
					<td>' . $emails . '</td>
					<td>' . $phones . '</td>
					<td>' . $varFax . '</td>
					<td>' . $desc . '</td>
					<td>' . $data->txtAddress . '</td>
					<td>' . $data->mailingaddress . '</td>
					<td>' . $data->chrPublish . '</td>
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
        $newemails = $newdata->varEmail;
        $emails = $data->varEmail;
        if ($emails != $newemails) {
            $emailcolor = 'style="background-color:#f5efb7"';
        } else {
            $emailcolor = '';
        }
        $newphone = $newdata->varPhoneNo;
        $phone = $data->varPhoneNo;
        if ($phone != $newphone) {
            $phonecolor = 'style="background-color:#f5efb7"';
        } else {
            $phonecolor = '';
        }

        if ($data->varFax != $newdata->varFax) {
            $varFaxcolor = 'style="background-color:#f5efb7"';
        } else {
            $varFaxcolor = '';
        }

        if ($data->txtDescription != $newdata->txtDescription) {
            $txtDescriptioncolor = 'style="background-color:#f5efb7"';
        } else {
            $txtDescriptioncolor = '';
        }

        if ($data->txtAddress != $newdata->txtAddress) {
            $txtAddresscolor = 'style="background-color:#f5efb7"';
        } else {
            $txtAddresscolor = '';
        }
        if ($data->mailingaddress != $newdata->mailingaddress) {
            $mailingaddresscolor = 'style="background-color:#f5efb7"';
        } else {
            $mailingaddresscolor = '';
        }
        if ($data->chrPublish != $newdata->chrPublish) {
            $Publishcolor = 'style="background-color:#f5efb7"';
        } else {
            $Publishcolor = '';
        }
        if (isset($newdata->varFax) && !empty($newdata->varFax)) {
            $fax = $newdata->varFax;
        } else {
            $fax = "N/A";
        }
        if (isset($newdata->txtAddress) && !empty($newdata->txtAddress)) {
            $txtAddress = $newdata->txtAddress;
        } else {
            $txtAddress = "N/A";
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
				<th align="center">' . trans('contactinfo::template.common.title') . '</th>
				<th align="center">Email</th>
				<th align="center">Phone</th>
                                <th align="center">Fax</th>
                                <th align="center">Working Hours</th>
                                <th align="center">Address</th>
                                <th align="center">Mailing Address</th>
				<th align="center">' . trans('contactinfo::template.common.publish') . '</th>
				</tr>
				</thead>
				<tbody>
				<tr>
				<td align="center" ' . $titlecolor . '>' . stripslashes($newdata->varTitle) . '</td>
				<td align="center" ' . $emailcolor . '>' . $newemails . '</td>
				<td align="center" ' . $phonecolor . '>' . $newphone . '</td>
                                <td align="center" ' . $varFaxcolor . '>' . $fax . '</td>
                                <td align="center" ' . $txtDescriptioncolor . '>' . $desc . '</td>
                                <td align="center" ' . $txtAddresscolor . '>' . $txtAddress . '</td>
                                <td align="center" ' . $mailingaddresscolor . '>' . $newdata->mailingaddress . '</td>
				<td align="center" ' . $Publishcolor . '>' . $newdata->chrPublish . '</td>
				</tr>
				</tbody>
				</table>';
        return $returnHtml;
    }

    public static function flushCache()
    {
        Cache::tags('ContactInfo')->flush();
    }

}
