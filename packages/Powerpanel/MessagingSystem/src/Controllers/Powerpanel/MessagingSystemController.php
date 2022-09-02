<?php

namespace Powerpanel\MessagingSystem\Controllers\Powerpanel;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;
use Powerpanel\Workflow\Models\Comments;
use Powerpanel\MessagingSystem\Models\MessagingSystem;
use Powerpanel\Workflow\Models\WorkflowLog;
use Powerpanel\Workflow\Models\Workflow;
use App\Log;
use App\User;
use Powerpanel\RoleManager\Models\Role;
use App\RecentUpdates;
use Powerpanel\MessagingSystem\Models\MessagingDeleted;
use App\Alias;
use Validator;
use Config;
use DB;
use App\Http\Controllers\PowerpanelController;
use Crypt;
use Auth;
use App\Helpers\MyLibrary;
use App\CommonModel;
use Carbon\Carbon;
use Cache;
use App\Modules;
use Powerpanel\RoleManager\Models\Role_user;
use App\UserNotification;

class MessagingSystemController extends PowerpanelController {

    /**
     * Create a new controller instance.
     * @return void
     */
    public $moduleHaveFields = [];

    public function __construct() {

        parent::__construct();
        if (isset($_COOKIE['locale'])) {
            app()->setLocale($_COOKIE['locale']);
        }
        $this->moduleHaveFields = ['chrMain'];
    }

    /**
     * This method handels load MessagingSystem grid
     * @return  View
     * @since   2017-07-20
     * @author  NetQuick
     */
    public function index() {
       $userIsAdmin = false;
        if (isset($this->currentUserRoleData) && !empty($this->currentUserRoleData)) {
            if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                $userIsAdmin = true;
            }
        }else{
            $userIsAdmin = true;
        }
        $total = MessagingSystem::getRecordCount();
        $roles = Role::getRecordListing('display_name', 'id');
        $NewRecordsCount = MessagingSystem::getNewRecordsCount();
        $this->breadcrumb['title'] = trans('messagingsystem::template.messagingsystemModule.managemessagingsystem');
        return view('messagingsystem::powerpanel.index', ['roles' => $roles, 'iTotalRecords' => $total, 'breadcrumb' => $this->breadcrumb, 'NewRecordsCount' => $NewRecordsCount, 'userIsAdmin' => $userIsAdmin]);
    }

    public function publish(Request $request) {

        $alias = (int) Input::get('alias');
        $update = MyLibrary::setPublishUnpublish($alias, $request);
        self::flushCache();
        echo json_encode($update);
        exit;
    }

    public function RemoveSingMsg(Request $request) {
        $RemoveId = Request::post('removemsgidvalue');
        $fromid = Request::post('fromid');
        $toid = Request::post('toid');
        $IdArray = explode(",", $RemoveId);
        MessagingSystem::destroy($IdArray);
        MessagingDeleted::DeletedRecordMsg($toid, $fromid);
        return $RemoveId;
    }

    public function ClearChat(Request $request) {
        $toid = Request::post('toid');
        $fromid = Request::post('fromid');
        MessagingSystem::where("FromID", '=', $fromid)
                ->where('ToID', '=', $toid)
                ->orWhere('FromID', '=', $toid)->where("ToID", '=', $fromid)->delete();
        MessagingDeleted::DeletedRecordMsg($toid, $fromid);
        return $toid;
    }

    public static function flushCache() {
        Cache::tags('messagingsystem')->flush();
    }

    public function InserMessageData(Request $request) {
        $postArr = Request::all();
      
        $messagingsystemArr = [];
        $updatemessagingsystemArr = [];
        $listuser = self::GetuserData();
        if ($postArr['formtype'] == 'edit') {
            $whereConditions = ['id' => $postArr['editId']];
            $updatemessagingsystemArr['varShortDescription'] = $postArr['varShortDescription'];
            $updatemessagingsystemArr['varEdit'] = 'Y';
            $updatemessagingsystemArr['varread'] = 'N';
            $updatemessagingsystemArr['fkIntImgId'] = !empty($postArr['img_id']) ? $postArr['img_id'] : null;
            $updatemessagingsystemArr['fkIntDocId'] = !empty($postArr['doc_id']) ? $postArr['doc_id'] : null;
            $update = CommonModel::updateRecords($whereConditions, $updatemessagingsystemArr,false, 'Powerpanel\MessagingSystem\Models\MessagingSystem');
            return $postArr['editId'] . '@@' . $postArr['varShortDescription'];
        } else {
            $messagingsystemArr['chrMain'] = 'Y';
            $messagingsystemArr['varTitle'] = '';
            $messagingsystemArr['varShortDescription'] = $postArr['varShortDescription'];
            $messagingsystemArr['fkIntDocId'] = !empty($postArr['doc_id']) ? $postArr['doc_id'] : null;
            $messagingsystemArr['fkIntImgId'] = !empty($postArr['img_id']) ? $postArr['img_id'] : null;

            $messagingsystemArr['chrPublish'] = isset($postArr['chrMenuDisplay']) ? $postArr['chrMenuDisplay'] : 'Y';
            $messagingsystemArr['FromID'] = auth()->user()->id;
            $fromusername = User::getRecordById(auth()->user()->id);
            $tousername = User::getRecordById($postArr['toid']);
            $messagingsystemArr['FromName'] = $fromusername->name;
            $messagingsystemArr['ToName'] = $tousername->name;
            $messagingsystemArr['FromEmail'] = MyLibrary::getDecryptedString(auth()->user()->email);
            $messagingsystemArr['ToID'] = $postArr['toid'];
            $user = User::getRecordById($postArr['toid']);
            $messagingsystemArr['ToEmail'] = MyLibrary::getDecryptedString($user->email);
            $messagingsystemArr['UserID'] = auth()->user()->id;
            $messagingsystemArr['created_at'] = Carbon::now();
            if ($postArr['formtype'] == 'quote') {
                $messagingsystemArr['varQuote'] = 'Y';
                $messagingsystemArr['varQuoteId'] = $postArr['editId'];
            }
            $messagingsystemID = CommonModel::addRecord($messagingsystemArr,'Powerpanel\MessagingSystem\Models\MessagingSystem');
            self::flushCache();

            return $postArr['toid'] . '@@' . $listuser;
        }
    }

    public function ForwordMsg(Request $request) {
        $toid = Request::post('toid');
        $fromid = Request::post('fromid');
        $recordid = Request::post('recordid');
        $newmsg = Request::post('newmsg');
        $varforquatnew = Request::post('varforquatnew');
        $forworddata = MessagingSystem::getRecordById($recordid, '');
//        echo $forworddata['varShortDescription'];
//        exit;
        if ($varforquatnew == 'Y' && $newmsg != '') {
            $messagingsystemArrNew = [];
            $messagingsystemArrNew['chrMain'] = 'Y';
            $messagingsystemArrNew['varTitle'] = '';
            $messagingsystemArrNew['varShortDescription'] = isset($newmsg) ? $newmsg : null;
            $messagingsystemArrNew['fkIntDocId'] = isset($forworddata['fkIntDocId']) ? $forworddata['fkIntDocId'] : null;
            $messagingsystemArrNew['fkIntImgId'] = !empty($forworddata['fkIntImgId']) ? $forworddata['fkIntImgId'] : null;

            $messagingsystemArrNew['chrPublish'] = isset($forworddata['chrPublish']) ? $forworddata['chrPublish'] : 'Y';
            $messagingsystemArrNew['FromID'] = auth()->user()->id;
            $fromusername = User::getRecordById(auth()->user()->id);
            $tousername = User::getRecordById($toid);
            $messagingsystemArrNew['FromName'] = $fromusername->name;
//        echo $tousername->name;exit;
            $messagingsystemArrNew['ToName'] = $tousername->name;
            $messagingsystemArrNew['FromEmail'] = MyLibrary::getDecryptedString(auth()->user()->email);
            $messagingsystemArrNew['ToID'] = $toid;
            $user = User::getRecordById($toid);
            $messagingsystemArrNew['ToEmail'] = MyLibrary::getDecryptedString($user->email);
            $messagingsystemArrNew['UserID'] = auth()->user()->id;
            $messagingsystemArrNew['created_at'] = Carbon::now();
            $messagingsystemArrNew['varQuoteId'] = $recordid;

            $messagingsystemID = CommonModel::addRecord($messagingsystemArrNew,'Powerpanel\MessagingSystem\Models\MessagingSystem');
        }
//         *******Insert Forword**********
        $messagingsystemArr = [];
        $messagingsystemArr['chrMain'] = 'Y';
        $messagingsystemArr['varTitle'] = '';
        if ($varforquatnew == 'Y' && $newmsg != '') {
            $messagingsystemArr['varShortDescription'] = null;
        } else {
            $messagingsystemArr['varShortDescription'] = isset($newmsg) ? $newmsg : null;
        }
        $messagingsystemArr['fkIntDocId'] = isset($forworddata['fkIntDocId']) ? $forworddata['fkIntDocId'] : null;
        $messagingsystemArr['fkIntImgId'] = !empty($forworddata['fkIntImgId']) ? $forworddata['fkIntImgId'] : null;

        $messagingsystemArr['chrPublish'] = isset($forworddata['chrPublish']) ? $forworddata['chrPublish'] : 'Y';
        $messagingsystemArr['FromID'] = auth()->user()->id;
        $fromusername = User::getRecordById(auth()->user()->id);
        $tousername = User::getRecordById($toid);
        $messagingsystemArr['FromName'] = $fromusername->name;
//        echo $tousername->name;exit;
        $messagingsystemArr['ToName'] = $tousername->name;
        $messagingsystemArr['FromEmail'] = MyLibrary::getDecryptedString(auth()->user()->email);
        $messagingsystemArr['ToID'] = $toid;
        $user = User::getRecordById($toid);
        $messagingsystemArr['ToEmail'] = MyLibrary::getDecryptedString($user->email);
        $messagingsystemArr['UserID'] = auth()->user()->id;
        $messagingsystemArr['created_at'] = Carbon::now();

        $messagingsystemArr['varQuote'] = 'Y';
        $messagingsystemArr['varQuoteId'] = $recordid;

        $messagingsystemID = CommonModel::addRecord($messagingsystemArr,'Powerpanel\MessagingSystem\Models\MessagingSystem');

        echo "<pre/>";
        print_r('sucess');
        exit;
    }

    public function GetNewMessage(Request $request) {
        $toid = Request::post('toid');
        $fromid = Request::post('fromid');
        $CountUnRedata = MessagingSystem::GetCountNewMessageidData($toid, $fromid);
        $GetUnReadData = MessagingSystem::GetNewMessageidData($toid, $fromid);

        if ($CountUnRedata > 0) {
            foreach ($GetUnReadData as $UnRedata) {
                $updatemessagingsystemFields['varread'] = 'Y';
                $whereConditions = ['id' => $UnRedata->id];
                $update = CommonModel::updateRecords($whereConditions, $updatemessagingsystemFields,false,'Powerpanel\MessagingSystem\Models\MessagingSystem');
                self::flushCache();
            }
            return $CountUnRedata;
        } else {
            return $CountUnRedata;
        }
    }

    public function GetNewMessageCounter(Request $request) {

        $toidArray = Request::post('toid');
        $activeuserid = Request::post('activeuserid');
        $CountUnRedata = '';
        $toidplus = '';
        $lastmsg = '';
        $fromid = Request::post('fromid');
        if(!empty($toidArray)) {
            foreach ($toidArray as $toid) {
                $CountUnRedataorg = MessagingSystem::GetCountNewMessageidData($toid, $fromid);
                if ($CountUnRedataorg > 0) {
                    $lastData = MessagingSystem::GetlastDate($toid, auth()->user()->id);
                    if (isset($lastData->varShortDescription) && !empty($lastData->varShortDescription)) {
                        $lastmsg = $lastData->varShortDescription;
                    } elseif (isset($lastData->fkIntImgId)) {
                        $lastmsg = "<i class='fa fa-picture-o' aria-hidden='true'></i>";
                    } elseif (isset($lastData->fkIntDocId)) {
                        $lastmsg = "<i class='fa fa-paperclip' aria-hidden='true'></i>";
                    } else {
                        $lastmsg = "";
                    }
                }
                $CountUnRedata .= $CountUnRedataorg . '@@';
                $toidplus .= $toid . '-';
            }
        }
        $DeletedMsg = '';
        if ($activeuserid != '') {
            $DeletedMsg = MessagingDeleted::GetCountDeteled($activeuserid, $fromid);
        }
        $userlist = self::GetuserData();
        return $CountUnRedata . '!!' . $lastmsg . '!!' . $userlist . '!!' . $DeletedMsg;
    }

    public function GetuserData() {
        $usersData = MessagingSystem::getUserList();
        $i = 0;
        $listhtml = '';
        foreach ($usersData as $userdata) {
            if($userdata->id != 1){
                $imagedata = User::GetUserImage($userdata->id);
                $username = User::GetUserName($userdata->id);
                if (!empty($imagedata)) {
                    $logo_url = \App\Helpers\resize_image::resize($imagedata);
                } else {
                    $logo_url = Config::get('Constant.CDN_PATH').'/resources/image/packages/messagingsystem/man.png';
                }
                $logindata = \App\LoginLog::getLoginHistryData($userdata->id);
                $loggedinuser = 'N';
                if (!empty($logindata)) {
                    $loggedinuser = 'Y';
                }
                $CountUnRedata = MessagingSystem::GetCountNewMessageidData($userdata->id, auth()->user()->id);
                $lastData = MessagingSystem::GetlastDate($userdata->id, auth()->user()->id);
                if (isset($lastData->created_at) && !empty($lastData->created_at)) {
                    $lastseen = date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . '', strtotime($lastData->created_at));
                    $lastseen = MessagingSystem::relative_date(strtotime($lastData->created_at));
                } else {
                    $lastseen = '';
                }
                if (isset($lastData->varShortDescription) && !empty($lastData->varShortDescription)) {
                    $lastmsg = $lastData->varShortDescription;
                } elseif (isset($lastData->fkIntImgId)) {
                    $lastmsg = "<i class='fa fa-picture-o' aria-hidden='true'></i>";
                } elseif (isset($lastData->fkIntDocId)) {
                    $lastmsg = "<i class='fa fa-paperclip' aria-hidden='true'></i>";
                } elseif (isset($lastData->varQuote) && $lastData->varQuote == 'Y' && $lastData->varShortDescription == '') {
                    $lastmsg = "<i class='fa fa-quote-left'></i> quoted message";
                } else {
                    $lastmsg = "";
                }
                if ($userdata->id != auth()->user()->id) {
                    $listhtml .= '<li data-userid="'.$userdata->id.'">';
                    if($CountUnRedata !=0) {
                        $unread="unread-msg-user";
                    } else {
                        $unread="";
                    }
                    $online = ($loggedinuser == 'Y') ? 'online' : '';

                    $listhtml .= '<a href="javascript:void(0);" class="' . $unread . '">';
                    $listhtml .= '<div class="d-flex align-items-center">';
                    $listhtml .= '<div class="flex-shrink-0 chat-user-img '.$online.' align-self-center me-2 ms-0">';
                    $listhtml .= '<div class="avatar-xxs">';
                    $listhtml .= '<img src="' . $logo_url . '" class="rounded-circle img-fluid userprofile" alt="image">';
                    $listhtml .= '</div>';
                    $listhtml .= '<span class="user-status"></span>';
                    $listhtml .= '</div>';
                    $listhtml .= '<div class="flex-grow-1 overflow-hidden">';
                    $listhtml .= '<p class="text-truncate mb-0">' . $username . '</p>';
                    $listhtml .= '</div>';
                    $listhtml .= '<div class="flex-shrink-0" id="newMSG_' . $userdata->id . '">';
                    if($CountUnRedata !=0) {
                        $listhtml .= '<span id="msg-number" class="badge badge-soft-light msg-number-count rounded p-1">' . $CountUnRedata . '</span>';
                    }
                    $listhtml .= '</div></div></a></li>';
                    $i++;
                }
            }
        }
        return $listhtml;
    }

    public function GetRecentid(Request $request) {
        $fromid = Request::post('fromid');
        $RecentData = MessagingSystem::GetRecentid($fromid);
        if (isset($RecentData->ToID) && !empty($RecentData->ToID)) {
            $RecentData = $RecentData->ToID;
        } else {
            $RecentData = '0';
        }
        return $RecentData;
    }

    public function GetMessageidData(Request $request) {
        $toid = Request::post('toid');
        $fromid = Request::post('fromid');
        $data = MessagingSystem::GetMessageidData($toid, $fromid);

        $username = "Unknown";
        if (!empty($data)) {
            $username = User::GetUserName($toid);
            $useremail = User::GetUserEmail($toid);
            $username1 = $username;
        }
        $logindata = \App\LoginLog::getLoginHistryData($toid);
        $loggedinuser = 'N';
        if (!empty($logindata)) {
            $loggedinuser = 'Y';
        }
        $html = '';
        $imagedata = User::GetUserImage($toid);
        if (!empty($imagedata)) {
            $logo_url = \App\Helpers\resize_image::resize($imagedata);
        } else {
            $logo_url = Config::get('Constant.CDN_PATH').'/resources/image/packages/messagingsystem/man.png';
        }

        $html .= '<div class="p-3 user-chat-topbar">';
        $html .= '<div class="row align-items-center">';
        $html .= '<div class="col-sm-12 col-12">';
        $html .= '<div class="d-flex align-items-center">';
        $html .= '<div class="flex-shrink-0 d-block d-lg-none me-3">';
        $html .= '<a href="javascript:void(0);" class="user-chat-remove fs-18 p-1"><i class="ri-arrow-left-s-line align-bottom"></i></a>';
        $html .= '</div>';
        $html .= '<div class="flex-grow-1 overflow-hidden">';
        $html .= '<div class="d-flex align-items-center">';
        if ($loggedinuser == 'Y') {
            $loginuser_status = 'Online'; 
            $loginuser_statusicon = 'online'; 
        }
        if ($loggedinuser == 'N') {
            $loginuser_status = 'Offline'; 
            $loginuser_statusicon = ''; 
        }        
        
        $html .= '<div class="flex-shrink-0 chat-user-img '.$loginuser_statusicon.' user-own-img align-self-center me-3 ms-0">';
        $html .= '<img src="' . $logo_url . '" class="rounded-circle avatar-xs" alt="">';
        $html .= '<span class="user-status"></span>';
        $html .= '</div>';
        $html .= '<div class="flex-grow-1 overflow-hidden">';
        $html .= '<h5 class="text-truncate mb-0 fs-16">' . $username1 . '</h5>';
        $html .= '<p class="text-truncate text-muted fs-14 mb-0 userStatus"><small>'.$loginuser_status.'</small></p>';
       
        $html .= '</div></div></div></div></div></div></div>';

        if (count($data) > 0) {
            $j = 0;
            $html .= '<div class="position-relative" id="users-chat">';
            $html .= '<div class="chat-conversation p-3 p-lg-4 " id="chat-conversation" data-simplebar>';
            $html .= '<ul class="list-unstyled chat-conversation-list" id="users-conversation">';

            foreach ($data as $userdata) {
                if($userdata->id != 1){
                    if ($userdata->FromID == auth()->user()->id) {
                        $position = "right";
                    } else {
                        $position = "left";
                    }
                    $imagedata = User::GetUserImage($userdata->FromID);
                    $username = User::GetUserName($userdata->FromID);
                    if (!empty($imagedata)) {
                        $logo_url = \App\Helpers\resize_image::resize($imagedata);
                    } else {
                        $logo_url = Config::get('Constant.CDN_PATH').'/resources/image/packages/messagingsystem/man.png';
                    }
                    $docsAray = explode(',', $userdata->fkIntDocId);
                    $docObj = \App\Helpers\DocumentHelper::getDocsByIds($docsAray);

                    $imageAray = explode(',', $userdata->fkIntImgId);
                    $imagObj = array();
                    foreach ($imageAray as $imagesId) {
                        if (isset($imagesId) && !empty($imagesId)) {
                            $imagObj[] = \App\Helpers\resize_image::resize($imagesId);
                        }
                    }

                    $html .= '<li class="chat-list '.$position.'">';
                    $html .= '<div class="conversation-list">';

                    if($position == 'left') {
                        $html .= '<div class="chat-avatar">';
                        $html .= '<img src="'.$logo_url.'" alt="" >';
                        $html .= '</div>';
                    }

                    $html .= '<div class="user-chat-content">';
                    $html .= '<div class="ctext-wrap">';
                    
                    if ($userdata->varQuote == 'Y') {
                        $quoteText = MessagingSystem::getRecordById($userdata->varQuoteId);
                        if (isset($quoteText->FromID)) {
                            $userdatabyid = User::getRecordById($quoteText->FromID);
                            $lastseen = MessagingSystem::relative_date(strtotime($quoteText->created_at));
                            $htmlmage = '';
                            $filemage = '';
                            if ($quoteText->fkIntDocId != '') {
                                $htmlmage .= '<br/>';
                                $docsArayQ = explode(',', $quoteText->fkIntDocId);
                                $docObjQ = \App\Helpers\DocumentHelper::getDocsByIds($docsArayQ);
                                $htmlmage .= self::getimagefile($docObjQ);
                            }
                            if ($quoteText->fkIntImgId != '') {
                                $imageArayQ = explode(',', $quoteText->fkIntImgId);
                                $imagObjQ = array();
                                foreach ($imageArayQ as $imagesIdQ) {
                                    if (isset($imagesIdQ) && !empty($imagesIdQ)) {
                                        $imagObjQ[] = \App\Helpers\resize_image::resize($imagesIdQ);
                                    }
                                }
                                $htmlmage .= '<br/>';
                                foreach ($imagObjQ as $imagdataQ) {
                                    if (isset($imagdataQ) && !empty($imagdataQ)) {
                                        $html .= '<div class="message-img mb-0">';
                                        $html .= '<div class="message-img-list"><div>';
                                        $html .= '<a class="popup-img d-inline-block" href="' . $imagdataQ . '">';
                                        $html .= '<img src="' . $imagdataQ . '" alt="" class="rounded border">';
                                        $html .= '</a></div></div></div>';
                                    }
                                }
                            }

                            // $html .= '<span class="quote-top-msg">' . nl2br($quoteText->varShortDescription) . $htmlmage . '</span><div class="my-quote">' . $userdatabyid['name'] . ', ' . $lastseen . '</div>';
                            // if (isset($userdata->varShortDescription) && !empty($userdata->varShortDescription)) {
                            //     $html .= '<span class="quote-bottom-msg">' . nl2br($userdata->varShortDescription) . '</span>';
                            // }

                        } else {
                            $html .= '<div class="ctext-wrap-content">';
                            $html .= '<p class="mb-0 ctext-content">'.nl2br($userdata->varShortDescription).'</p>';
                            $html .= '</div>';
                        }
                    } else {

                        if(count($imagObj) > 0) {
                            foreach ($imagObj as $imagdata) {
                                if (isset($imagdata) && !empty($imagdata)) {
                                    $html .= '<div class="message-img mb-0">';
                                    $html .= '<div class="message-img-list"><div>';
                                    $html .= '<a class="popup-img d-inline-block" href="' . $imagdata . '">';
                                    $html .= '<img src="' . $imagdata . '" alt="" class="rounded border">';
                                    $html .= '</a></div>';
                                    
                                    $html .= '<div class="message-img-link">';
                                    $html .= '<ul class="list-inline mb-0">';
                                    $html .= '<li class="list-inline-item dropdown">';
                                    $html .= '<a class="dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                                    $html .= '<i class="ri-more-fill"></i>';
                                    $html .= '</a>';
                                    $html .= '<div class="dropdown-menu">';
                                    $html .= '<a class="dropdown-item" href="' . $imagdata . '" download=""><i class="ri-download-2-line me-2 text-muted align-bottom"></i>Download</a>';
                                    $html .= '<a class="dropdown-item" href="#"><i class="ri-reply-line me-2 text-muted align-bottom"></i>Reply</a>';
                                    $html .= '<a class="dropdown-item" href="#"><i class="ri-share-line me-2 text-muted align-bottom"></i>Forward</a>';
                                    $html .= '<a class="dropdown-item" href="#"><i class="ri-bookmark-line me-2 text-muted align-bottom"></i>Bookmark</a>';
                                    $html .= '<a class="dropdown-item delete-image" href="#"><i class="ri-delete-bin-5-line me-2 text-muted align-bottom"></i>Delete</a>';
                                    $html .= '</div></li></ul></div></div></div>';
                                }
                            }
                        }
                        
                        if(count($docObj) > 0) {
                            foreach ($docObj as $docObj) {
                                if(!empty($docObj) && isset($docObj->varDocumentExtension)) {
                                    $PDF_Path = Config::get('Constant.CDN_PATH').'documents/' . $docObj->txtSrcDocumentName . '.' . $docObj->varDocumentExtension;
                                    $doclink = Config::get('Constant.CDN_PATH').'documents/' . $docObj->txtSrcDocumentName . '.' . $docObj->varDocumentExtension;
                                    if ($docObj->varDocumentExtension == 'pdf' || $docObj->varDocumentExtension == 'PDF') {
                                        $blank = 'target="_blank"';
                                        $title = $docObj->txtSrcDocumentName;
                                        $anchorLinkHitType = "view";
                                        $icon = "pdf.png";
                                    } elseif ($docObj->varDocumentExtension == 'txt' || $docObj->varDocumentExtension == 'TXT') {
                                        $blank = '';
                                        $title = 'Download (' . $docObj->txtSrcDocumentName . ')';
                                        $anchorLinkHitType = "download";
                                        $icon = "txt.png";
                                    } elseif ($docObj->varDocumentExtension == 'doc' || $docObj->varDocumentExtension == 'DOC') {
                                        $blank = '';
                                        $title = 'Download (' . $docObj->txtSrcDocumentName . ')';
                                        $anchorLinkHitType = "download";
                                        $icon = "doc.png";
                                    } elseif ($docObj->varDocumentExtension == 'ppt' || $docObj->varDocumentExtension == 'PPT') {
                                        $blank = '';
                                        $title = 'Download (' . $docObj->txtSrcDocumentName . ')';
                                        $anchorLinkHitType = "download";
                                        $icon = "ppt.png";
                                    } elseif ($docObj->varDocumentExtension == 'xls' || $docObj->varDocumentExtension == 'XLS' || $docObj->varDocumentExtension == 'xlsx' || $docObj->varDocumentExtension == 'XLSX' || $docObj->varDocumentExtension == 'xlsm' || $docObj->varDocumentExtension == 'XLSM') {
                                        $blank = '';
                                        $title = 'Download (' . $docObj->txtSrcDocumentName . ')';
                                        $anchorLinkHitType = "download";
                                        $icon = "xls.png";
                                    } else {
                                        $blank = '';
                                        $anchorLinkHitType = "download";
                                        $anchorLinkIsdownload = "download";
                                        $title = 'Download (' . $docObj->txtSrcDocumentName . ')';
                                        $icon = "document.png";
                                    }
                                }
                                $html .= '<div class="message-img mb-0">';
                                $html .= '<div class="message-img-list"><div>';
                                $html .= '<a class="popup-img d-inline-block" '.$blank.' href="'.url($PDF_Path).'"  data-viewid="'.$docObj->id.'" data-viewtype="'.$anchorLinkHitType .'"  title="' . $title . '">';
                                $html .= '<img  src="' . Config::get('Constant.CDN_PATH').'assets/images/documents_logo/' . $icon . '' . '" class="rounded border">';
                                $html .= '</a></div>';
                                $html .= '</div></div>';
                            }
                        }

                        if(!empty($userdata->varShortDescription)) {
                            $html .= '<div class="ctext-wrap-content">';
                            $html .= '<p class="mb-0 ctext-content">'.nl2br($userdata->varShortDescription).'</p>';
                            $html .= '</div>';
    
                            $html .= '<div class="dropdown align-self-start message-box-drop">';
                            $html .= '<a class="dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                            $html .= '<i class="ri-more-2-fill"></i>';
                            $html .= '</a>';
                            $html .= '<div class="dropdown-menu">';
                            $html .= '<a class="dropdown-item reply-message" href="#"><i class="ri-reply-line me-2 text-muted align-bottom"></i>Reply</a>';
                            $html .= '<a class="dropdown-item" href="#"><i class="ri-share-line me-2 text-muted align-bottom"></i>Forward</a>';
                            $html .= '<a class="dropdown-item copy-message" href="#"><i class="ri-file-copy-line me-2 text-muted align-bottom"></i>Copy</a>';
                            $html .= '<a class="dropdown-item" href="#"><i class="ri-bookmark-line me-2 text-muted align-bottom"></i>Bookmark</a>';
                            $html .= '<a class="dropdown-item delete-item" href="#"><i class="ri-delete-bin-5-line me-2 text-muted align-bottom"></i>Delete</a>';
                            $html .= '</div>';
                            $html .= '</div>';
                        }
                    }

                    $html .= '</div>';
                    $html .= '<div class="conversation-name"><small class="text-muted time">' . date('' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($userdata->created_at)) . '</small> <span class="text-success check-message-icon"><i class="ri-check-double-line align-bottom"></i></span></div>';
                    $html .= '</div></div></li>';
                    $j++;
                }
            }
            $html .= '</ul></div></div>';
        } else {
            $html .= '<div class="position-relative" id="users-chat">';
            $html .= '<div class="chat-conversation p-3 p-lg-4 " id="chat-conversation" data-simplebar>';
            $html .= '</ul></div></div>';
        }
        $CountUnRedata = MessagingSystem::GetCountNewMessageidData($toid, $fromid);
        $GetUnReadData = MessagingSystem::GetNewMessageidData($toid, $fromid);
        if ($CountUnRedata > 0) {
            foreach ($GetUnReadData as $UnRedata) {
                $updatemessagingsystemFields['varread'] = 'Y';
                $whereConditions = ['id' => $UnRedata->id];
                $update = CommonModel::updateRecords($whereConditions, $updatemessagingsystemFields,false,'Powerpanel\MessagingSystem\Models\MessagingSystem');
                self::flushCache();
            }
        }
        MessagingDeleted::where("FromID", '=', $toid)->where('ToID', '=', $fromid)->delete();
        echo $html;
        exit;
    }

    public function getimagefile($docObj) {
        $html = '';
        foreach ($docObj as $docObj) {
            if (!empty($docObj) && isset($docObj->varDocumentExtension)) {
                $PDF_Path = Config::get('Constant.CDN_PATH').'documents/' . $docObj->txtSrcDocumentName . '.' . $docObj->varDocumentExtension;
                $doclink = Config::get('Constant.CDN_PATH').'documents/' . $docObj->txtSrcDocumentName . '.' . $docObj->varDocumentExtension;
                if ($docObj->varDocumentExtension == 'pdf' || $docObj->varDocumentExtension == 'PDF') {
                    $blank = 'target="_blank"';
                    $title = $docObj->txtSrcDocumentName;
                    $anchorLinkHitType = "view";
                    $icon = "pdf.png";
                } elseif ($docObj->varDocumentExtension == 'txt' || $docObj->varDocumentExtension == 'TXT') {
                    $blank = '';
                    $title = 'Download (' . $docObj->txtSrcDocumentName . ')';
                    $anchorLinkHitType = "download";
                    $icon = "txt.png";
                } elseif ($docObj->varDocumentExtension == 'doc' || $docObj->varDocumentExtension == 'DOC') {
                    $blank = '';
                    $title = 'Download (' . $docObj->txtSrcDocumentName . ')';
                    $anchorLinkHitType = "download";
                    $icon = "doc.png";
                } elseif ($docObj->varDocumentExtension == 'ppt' || $docObj->varDocumentExtension == 'PPT') {
                    $blank = '';
                    $title = 'Download (' . $docObj->txtSrcDocumentName . ')';
                    $anchorLinkHitType = "download";
                    $icon = "ppt.png";
                } elseif ($docObj->varDocumentExtension == 'xls' || $docObj->varDocumentExtension == 'XLS' || $docObj->varDocumentExtension == 'xlsx' || $docObj->varDocumentExtension == 'XLSX' || $docObj->varDocumentExtension == 'xlsm' || $docObj->varDocumentExtension == 'XLSM') {
                    $blank = '';
                    $title = 'Download (' . $docObj->txtSrcDocumentName . ')';
                    $anchorLinkHitType = "download";
                    $icon = "xls.png";
                } else {
                    $blank = '';
                    $anchorLinkHitType = "download";
                    $anchorLinkIsdownload = "download";
                    $title = 'Download (' . $docObj->txtSrcDocumentName . ')';
                    $icon = "document.png";
                }
            }
            $html .= '<a ' . $blank . ' href="' . url($PDF_Path) . '" data-viewid="' . $docObj->id . '" data-viewtype="' . $anchorLinkHitType . '" title="' . $title . '" class="lnk_view docHitClick" style="width: 32px;margin: 5px 3px;display: inline-block;vertical-align: middle;" ' . $anchorLinkHitType . '><img  src="' . Config::get('Constant.CDN_PATH').'assets/images/documents_logo/' . $icon . '' . '"></a>';
        }
        return $html;
    }

}
