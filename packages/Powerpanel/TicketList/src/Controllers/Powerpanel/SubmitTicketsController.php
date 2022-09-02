<?php

namespace Powerpanel\TicketList\Controllers\Powerpanel;

use App\Http\Controllers\PowerpanelController;
use Illuminate\Support\Facades\Redirect;
use Request;
use Excel;
use App\User;
use Powerpanel\TicketList\Models\SubmitTickets;
use App\CommonModel;
use Powerpanel\TicketList\Models\SubmitTicketsLeadExport;
use App\Helpers\MyLibrary;
use App\Helpers\resize_image;
use Illuminate\Support\Facades\File;
use Config;
use Illuminate\Routing\UrlGenerator;
use App\UserNotification;
use App\Modules;
use Auth;
use DB;
use App\Helpers\Email_sender;
use Illuminate\Support\Facades\Validator;

class SubmitTicketsController extends PowerpanelController {

    /**
     * Create a new Dashboard controller instance.
     *
     * @return void
     */
    public function __construct(UrlGenerator $url) {
        parent::__construct();
        $this->url = $url;
        if (isset($_COOKIE['locale'])) {
            app()->setLocale($_COOKIE['locale']);
        }
    }

    public function index() {
        $iTotalRecords = CommonModel::getRecordCount(false,false,false,'Powerpanel\TicketList\Models\SubmitTickets');
        $this->breadcrumb['title'] = trans('ticketlist::template.SubmitTicketsModule.manageTicketsLeads');
        return view('ticketlist::powerpanel.list', ['iTotalRecords' => $iTotalRecords, 'breadcrumb' => $this->breadcrumb]);
    }

    public function get_list() {
        $filterArr = [];
        $records = [];
        $records["data"] = [];
        $filterArr['orderColumnNo'] = (!empty(Request::get('order') [0]['column']) ? Request::get('order') [0]['column'] : '');
        $filterArr['orderByFieldName'] = (!empty(Request::get('columns') [$filterArr['orderColumnNo']]['name']) ? Request::get('columns') [$filterArr['orderColumnNo']]['name'] : '');
        $filterArr['orderTypeAscOrDesc'] = (!empty(Request::get('order') [0]['dir']) ? Request::get('order') [0]['dir'] : '');
        $filterArr['searchFilter'] = !empty(Request::get('searchValue')) ? Request::get('searchValue') : '';
        $filterArr['iDisplayLength'] = intval(Request::get('length'));
        $filterArr['iDisplayStart'] = intval(Request::get('start'));
        $sEcho = intval(Request::get('draw'));

        $arrResults = SubmitTickets::getRecordList($filterArr);
        $iTotalRecords = CommonModel::getRecordCount($filterArr, true,false,'Powerpanel\TicketList\Models\SubmitTickets');
        $end = $filterArr['iDisplayStart'] + $filterArr['iDisplayLength'];
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;

        if (!empty($arrResults)) {
            foreach ($arrResults as $key => $value) {
                $records["data"][] = $this->tableData($value);
            }
        }
        
        if (isset($_REQUEST["customActionType"]) && $_REQUEST["customActionType"] == "group_action") {
            $records["customActionStatus"] = "OK"; // pass custom message(useful for getting status of group actions)
        }

        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;
        echo json_encode($records);
        exit;
    }

    /**
     * This method handels delete leads operation
     * @return  xls file
     * @since   2016-10-18
     * @author  NetQuick
     */
    public function DeleteRecord(Request $request) {
        $data = Request::all('ids');
        $update = MyLibrary::deleteMultipleRecords($data,false,false,'Powerpanel\TicketList\Models\SubmitTickets');
        UserNotification::deleteNotificationByRecordID($data['ids'], Config::get('Constant.MODULE.ID'));
        echo json_encode($update);
        exit;
    }

    /**
     * This method handels export process of contact us leads
     * @return  xls file
     * @since   2016-10-18
     * @author  NetQuick
     */
    public function ExportRecord() {

        return Excel::download(new SubmitTicketsLeadExport, Config::get('Constant.SITE_NAME') . '-' . trans("ticketlist::template.SubmitTicketsModule.SubmitTicketsLeads") . '-' . date("dmy-h:i") . '.xlsx');
    }

    public function tableData($value) {

        // Checkbox
        $checkbox = view('powerpanel.partials.checkbox', ['name'=>'delete[]', 'value'=>$value->id])->render();
        
        $AWSContants = MyLibrary::getAWSconstants();
        $_APP_URL = $AWSContants['CDN_PATH'];
        $countimage = count($value->ticketimage->toArray());

        $details = '';
        if (!empty($value->txtShortDescription)) {
            $details .= '<div class="pro-act-btn">';
            $details .= '<a href="javascript:void(0)" class="" onclick="return hs.htmlExpand(this,{width:300,headingText:\'Message\',wrapperClassName:\'titlebar\',showCredits:false});"><span aria-hidden="true" class="ri-mail-open-line fs-18"></span></a>';
            $details .= '<div class="highslide-maincontent">' . nl2br($value->txtShortDescription) . '</div>';
            $details .= '</div>';
        } else {
            $details .= '-';
        }
        $link = '';
        if (!empty($value->varLink)) {
            $link .= '<div class="pro-act-btn">';
            $link .= '<a href="javascript:void(0)" class="" onclick="return hs.htmlExpand(this,{width:300,headingText:\'Link\',wrapperClassName:\'titlebar\',showCredits:false});"><span aria-hidden="true" class="ri-external-link-line fs-18"></span></a>';
            $link .= '<div class="highslide-maincontent"><a href="' . $value->varLink . '" target="_blank">' . $value->varLink . '</a></div>';
            $link .= '</div>';
        } else {
            $link .= '-';
        }

        if ($value->intType == 1) {
            $type = 'Fixes / Issues';
        } else if ($value->intType == 2) {
            $type = 'Changes';
        } else if ($value->intType == 3) {
            $type = 'Suggestion';
        } else if ($value->intType == 4) {
            $type = 'New Features';
        }

        $pendingDisabled = "";
        $HoldDisabled = "";
        $ongoingDisabled = "";
        $details1 = '';
        if ($value->chrStatus == 'P') {
            $chrStatus = 'Pending';
        } else if ($value->chrStatus == 'H') {
            $chrStatus = 'On Hold';
            $pendingDisabled = "disabled";
             $details1 .= '<div class="pro-act-btn" style="display:inline-block;">';
            $details1 .= '<a href="javascript:void(0)" class="" onclick="return hs.htmlExpand(this,{width:300,headingText:\'On Hold Message\',wrapperClassName:\'titlebar\',showCredits:false});"><span aria-hidden="true" class="ri-mail-open-line fs-18"></span></a>';
            $details1 .= '<div class="highslide-maincontent">' . nl2br($value->HoldMessage) . '</div>';
            $details1 .= '</div>';
        } else if ($value->chrStatus == 'G') {
            $chrStatus = 'On Going';
            $pendingDisabled = "disabled";
            $HoldDisabled = "disabled";
            $details1 .= '<div class="pro-act-btn" style="display:inline-block;">';
            $details1 .= '<a href="javascript:void(0)" class="" onclick="return hs.htmlExpand(this,{width:300,headingText:\'On Going Message\',wrapperClassName:\'titlebar\',showCredits:false});"><span aria-hidden="true" class="ri-mail-open-line fs-18"></span></a>';
            $details1 .= '<div class="highslide-maincontent">' . nl2br($value->OnGoingMessage) . '</div>';
            $details1 .= '</div>';
        } else if ($value->chrStatus == 'C') {
            $chrStatus = 'Completed';
             $details1 .= '<div class="pro-act-btn" style="display:inline-block;">';
            $details1 .= '<a href="javascript:void(0)" class="" onclick="return hs.htmlExpand(this,{width:300,headingText:\'Completed Message\',wrapperClassName:\'titlebar\',showCredits:false});"><span aria-hidden="true" class="ri-mail-open-line fs-18"></span></a>';
            $details1 .= '<div class="highslide-maincontent">' . nl2br($value->CompleteMessage) . '</div>';
            $details1 .= '</div>';
        } else if ($value->chrStatus == 'N') {
            $chrStatus = 'New Implementation';
             $pendingDisabled = "disabled";
            $HoldDisabled = "disabled";
            $ongoingDisabled = "disabled";
             $details1 .= '<div class="pro-act-btn" style="display:inline-block;">';
            $details1 .= '<a href="javascript:void(0)" class="" onclick="return hs.htmlExpand(this,{width:300,headingText:\'New Implementation Message\',wrapperClassName:\'titlebar\',showCredits:false});"><span aria-hidden="true" class="ri-mail-open-line fs-18"></span></a>';
            $details1 .= '<div class="highslide-maincontent">' . nl2br($value->NewImplementationMessage) . '</div>';
            $details1 .= '</div>';
        }

        if (Auth::id() == 1) {
            if ($value->chrStatus == 'C') {
                $chrStatusHtml = '<div class="display_ticket_status mw-200 d-inline-block">Completed</div>';
            } else {
                $chrStatusHtml = '<div class="display_ticket_status mw-200 d-inline-block">
                                    <select id="dd_'.$value->id.'" name="changestatus" class="form-control change_ticket_status">
                                        <option data-uid="' . $value->UserID . '" data-rid="' . $value->id . '" value="P" ' . (($value->chrStatus == 'P') ? "selected" : "") . ' ' . $pendingDisabled . '>Pending</option>
                                        <option data-uid="' . $value->UserID . '" data-rid="' . $value->id . '" value="H" ' . (($value->chrStatus == 'H') ? "selected" : "") . ' ' . $HoldDisabled . '>On Hold</option>
                                        <option data-uid="' . $value->UserID . '" data-rid="' . $value->id . '" value="G" ' . (($value->chrStatus == 'G') ? "selected" : "") . ' '.$ongoingDisabled.'>On Going</option>
                                        <option data-uid="' . $value->UserID . '" data-rid="' . $value->id . '" value="N" ' . (($value->chrStatus == 'N') ? "selected" : "") . '>New Implementation</option>
                                        <option data-uid="' . $value->UserID . '" data-rid="' . $value->id . '" value="C" ' . (($value->chrStatus == 'C') ? "selected" : "") . '>Completed</option>
                                    </select>
                                </div>';
            }
        } else {
            $chrStatusHtml = '<div class="display_ticket_status mw-200 d-inline-block">' . $chrStatus . '</div>';
        }

        // Image
        $capimage = '';
        if ($this->BUCKET_ENABLED) {
            $cimage = $_APP_URL . $AWSContants['S3_MEDIA_BUCKET_PATH'] . '/ticket_images/' . $value->varCaptcher;
        } else {
            $cimage = Config::get('Constant.CDN_PATH').'assets/images/ticket_images/'.$value->varCaptcher;
        }

        if (isset($value->varCaptcher) && !empty($value->varCaptcher)) {
            $capimage .= '<div class="multi_image_thumb">';
            $capimage .= '<a href="' . resize_image::resize($cimage) . '" class="fancybox-thumb" rel="fancybox-thumb-' . $value->id . '" data-rel="fancybox-thumb">';
            $capimage .= '<img style="max-width:20px" data-bs-toggle="tooltip" data-bs-placement="bottom" title="' . preg_replace('/[^A-Za-z0-9\-]/', '-', $value->varTitle) . '" src="' . resize_image::resize($cimage, 50, 50) . '"/>';
            $capimage .= '</a>';
            $capimage .= '</div>';
        } else {
            $capimage .= '-';
        }



        $imgIcon = '';
        $ticketarray = $value->ticketimage->toArray();
        if ($countimage > 0) {
            $imgIcon .= '<div class="multi_image_thumb">';
            foreach ($ticketarray as $row) {
                if ($this->BUCKET_ENABLED) {
                    $imgurl = $_APP_URL . $AWSContants['S3_MEDIA_BUCKET_PATH'] . '/ticket_images/' . $row['txtImageName'];
                } else {
                    $imgurl = Config::get('Constant.CDN_PATH').'assets/images/ticket_images/'. $row['txtImageName'];
                }

                $imgIcon .= '<a href="' . $imgurl . '" class="fancybox-thumb" rel="fancybox-thumb-' . $row['fkticketId'] . '" data-rel="fancybox-thumb">';
                $imgIcon .= '<img height="30" width="30" title="' . preg_replace('/[^A-Za-z0-9\-]/', '-', $value->varTitle) . '" src="' . $imgurl . '"/>';
                $imgIcon .= '</a>';
            }
            $imgIcon .= '</div>';
        } else {
            $imgIcon .= '-';
        }


        $receivedDate = '<span align="left" data-bs-toggle="tooltip" data-bs-placement="bottom" title="'.date(Config::get("Constant.DEFAULT_DATE_FORMAT").' '.Config::get("Constant.DEFAULT_TIME_FORMAT"), strtotime($value->created_at)).'">'.date(Config::get('Constant.DEFAULT_DATE_FORMAT'), strtotime($value->created_at)).'</span>';

        $user = User::getRecordById($value->UserID);
        $userEmail = (isset($user->email) && $user->email!="") ?  MyLibrary::getDecryptedString($user->email) : "";
        $userName = (isset($user->name) && $user->name!="") ?  $user->name : "";
        $records = array(
            $checkbox.'<input type="hidden" name="useremailid" id="useremailid" value="' . $userEmail . '"><input type="hidden" name="username" id="username" value="' . $userName . '">',
            $value->varTitle,
            $type,
            $imgIcon,
            $capimage,
            $details,
            $link,
            $chrStatusHtml.' '.$details1,
            $receivedDate
        );
        return $records;
    }

    /**
     * This method handels ticket Status
     * @return  xls file
     * @since   2016-10-18
     * @author  NetQuick
     */
    public function changeticketstatus(Request $request) {
        $returnArray = array("success" => "0");
        $data = Request::get();
        $recordId = $data['recordId'];
        $ticketUserId = $data['ticketUserId'];
        $ticketStatus = $data['ticketStatus'];
        $update = SubmitTickets::updateTicketStatus($recordId, $ticketStatus);
        if ($update) {
            $notificationText = "";

            if ($ticketStatus == 'P') {
                $chrStatus = 'Pending';
                $notificationText = "Your ticket status has been updated from Netclues (Support)";
            } else if ($ticketStatus == 'H') {
                $chrStatus = 'On Hold';
                $notificationText = "Your ticket status has been updated from Netclues (Support)";
            } else if ($ticketStatus == 'G') {
                $chrStatus = 'On Going';
                $notificationText = "Your ticket status has been updated from Netclues (Support)";
            } else if ($ticketStatus == 'C') {
                $chrStatus = 'Completed';
                $notificationText = "Your ticket status has been updated from Netclues (Support)";
            } else if ($ticketStatus == 'N') {
                $chrStatus = 'New Implementation';
                $notificationText = "Your ticket status has been updated from Netclues (Support)";
            }
            /* code for send notifications to user */
            $submitTicketModuleData = Modules::getModule('submit-tickets');
            $submitTicketModuleId = $submitTicketModuleData->id;
            $userNotificationArr = MyLibrary::userNotificationData($submitTicketModuleId);
            $userNotificationArr['fkRecordId'] = $recordId;
            $userNotificationArr['txtNotification'] = $notificationText;
            $userNotificationArr['fkIntUserId'] = auth()->user()->id;
            $userNotificationArr['chrNotificationType'] = 'T';
            $userNotificationArr['intOnlyForUserId'] = $ticketUserId;
            UserNotification::addRecord($userNotificationArr);

            $returnArray = array("success" => "1");
        }
        echo json_encode($returnArray);
        exit;
    }

    public function emailreply(Request $request) {
        $returnArray = array("success" => "0", "msg" => "something Went Wrong");
        $data = Request::all();
        $messsages = array(
            'reply_to_email.required' => 'Email is required',
            'reply_to_subject.required' => 'Subject field is required',
            'reply_to_message.handle_xss' => 'Please enter valid input',
            'reply_to_message.no_url' => 'URL is not allowed',
        );
        $rules = array(
            'reply_to_email' => 'required',
            'reply_to_subject' => 'required',
            'reply_to_message' => 'required|handle_xss|no_url',
        );

        $validator = Validator::make($data, $rules, $messsages);
        if ($validator->passes()) {
            $leadId = $data['reply_lead_Id'];
            $leadData = SubmitTickets::getRecordById($leadId);
						$ticketUserId = $leadData->UserID;
            $mailReponse = Email_sender::contactUsLeadReply($data);
            if ($mailReponse == true) {
                if ($data['ticketStatus'] == 'H') {
                    $whereConditions = ['id' => $leadId];
                    $updateLeadFields['chrStatus'] = 'H';
                    $updateLeadFields['EmailID'] = $data['reply_to_email'];
                    $updateLeadFields['HoldMessage'] = $data['reply_to_message'];
                } else if ($data['ticketStatus'] == 'G') {
                    $whereConditions = ['id' => $leadId];
                    $updateLeadFields['chrStatus'] = 'G';
                    $updateLeadFields['EmailID'] = $data['reply_to_email'];
                    $updateLeadFields['OnGoingMessage'] = $data['reply_to_message'];
                } else if ($data['ticketStatus'] == 'C') {
                    $whereConditions = ['id' => $leadId];
                    $updateLeadFields['chrStatus'] = 'C';
                    $updateLeadFields['EmailID'] = $data['reply_to_email'];
                    $updateLeadFields['NewImplementationMessage'] = $data['reply_to_message'];
                } else if ($data['ticketStatus'] == 'N') {
                    $whereConditions = ['id' => $leadId];
                    $updateLeadFields['chrStatus'] = 'N';
                    $updateLeadFields['EmailID'] = $data['reply_to_email'];
                    $updateLeadFields['CompleteMessage'] = $data['reply_to_message'];
                }
                $update = CommonModel::updateRecords($whereConditions, $updateLeadFields, false, 'Powerpanel\TicketList\Models\SubmitTickets');

                $notificationText = "";
								$ticketStatus = $data['ticketStatus'];
		            if ($ticketStatus == 'P') {
		                $chrStatus = 'Pending';
		                $notificationText = "Your ticket status has been updated from Netclues (Support)";
		            } else if ($ticketStatus == 'H') {
		                $chrStatus = 'On Hold';
		                $notificationText = "Your ticket status has been updated from Netclues (Support)";
		            } else if ($ticketStatus == 'G') {
		                $chrStatus = 'On Going';
		                $notificationText = "Your ticket status has been updated from Netclues (Support)";
		            } else if ($ticketStatus == 'C') {
		                $chrStatus = 'Completed';
		                $notificationText = "Your ticket status has been updated from Netclues (Support)";
		            } else if ($ticketStatus == 'N') {
		                $chrStatus = 'New Implementation';
		                $notificationText = "Your ticket status has been updated from Netclues (Support)";
		            }
		            /* code for send notifications to user */
		            $submitTicketModuleData = Modules::getModule('submit-tickets');
		            $submitTicketModuleId = $submitTicketModuleData->id;
		            $userNotificationArr = MyLibrary::userNotificationData($submitTicketModuleId);
		            $userNotificationArr['fkRecordId'] = $leadId;
		            $userNotificationArr['txtNotification'] = $notificationText;
		            $userNotificationArr['fkIntUserId'] = auth()->user()->id;
		            $userNotificationArr['chrNotificationType'] = 'T';
		            $userNotificationArr['intOnlyForUserId'] = $ticketUserId;
		            UserNotification::addRecord($userNotificationArr);

                $returnArray = array("success" => "1", "msg" => "Mail Sent");
            } else {
                $returnArray = array("success" => "0", "msg" => "Mail Not Sent,Please Try again later");
            }
        } else {
            $returnArray = array("success" => "0", "msg" => "Please fill required fields");
        }

        echo json_encode($returnArray);
        exit;
    }

}
