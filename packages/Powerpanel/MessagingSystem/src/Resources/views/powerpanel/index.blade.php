@extends('powerpanel.layouts.app') @section('title') {{Config::get('Constant.SITE_NAME')}} - PowerPanel @stop @section('css')

<link href="{{ $CDN_PATH.'resources/css/packages/messagingsystem/uikit.min.css' }}" rel="stylesheet" type="text/css" />
<link href="{{ $CDN_PATH.'resources/css/packages/messagingsystem/contextMenu.css' }}" rel="stylesheet" type="text/css" />
<link href="{{ $CDN_PATH.'resources/css/packages/messagingsystem/messagingsystem.css' }}" rel="stylesheet" type="text/css" />
@endsection @section('content') {!! csrf_field() !!}
{{-- @include('powerpanel.partials.breadcrumbs') --}}
@php
	$activeMode = 'vertical'; //need to get this from DB once layout config saving to DB is acheived.
@endphp
<div class="chat-wrapper d-lg-flex gap-1 mx-n4 mt-n4 p-1">
    @if($activeMode !='horizontal') @section('sidebar') @endif
    <div class="{{ $activeMode =='horizontal'?'chat-leftsidebar':'' }}">
        <div class="px-4 pt-4 mb-4 chat-title">
            <div class="d-flex align-items-start">
                <div class="flex-grow-1">
                    <h5 class="mb-4">Chats</h5>
                </div>
            </div>
            <div class="search-box">
                <input type="search" class="form-control bg-light border-light search_msg" id="search_msg" placeholder="Search ...">
                <i class="ri-search-2-line search-icon"></i>
            </div>
        </div> <!-- .p-4 -->

        <div class="chat-room-list" data-simplebar>
            <div class="d-flex align-items-center px-4 mb-2 msg-title">
                <div class="flex-grow-1">
                    <h4 class="mb-0 fs-11 text-muted text-uppercase">Messages</h4>
                </div>
                {{-- <div class="flex-shrink-0">
                    <div data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="bottom" title="New Message">
                        <button type="button" class="btn btn-soft-success btn-sm">
                            <i class="ri-add-line align-bottom"></i>
                        </button>
                    </div>
                </div> --}}
            </div>
            
            <div class="chat-message-list" id="navbar-nav">            		
                <ul class="list-unstyled chat-list chat-user-list" id="userList">
                    @php 
                        $usersData = \Powerpanel\MessagingSystem\Models\MessagingSystem::getUserList();
                        $i = 0; 
                        foreach($usersData as $userdata){ 
                        if($userdata->id != '1'){
                        $imagedata = \App\User::GetUserImage($userdata->id);
                        $username = \App\User::GetUserName($userdata->id);
                        if (!empty($imagedata)) {
                        $logo_url = \App\Helpers\resize_image::resize($imagedata);

                        } else { 
                        $logo_url = url($CDN_PATH.'/resources/image/packages/messagingsystem/man.png'); 
                        } 
                        $logindata = \App\LoginLog::getLoginHistryData($userdata->id);

                        $loggedinuser = 'N';
                        if (!empty($logindata)) { 
                        $loggedinuser = 'Y'; 
                        } 
                        $CountUnRedata= \Powerpanel\MessagingSystem\Models\MessagingSystem::GetCountNewMessageidData($userdata->id,auth()->user()->id);
                        $lastData= \Powerpanel\MessagingSystem\Models\MessagingSystem::GetlastDate($userdata->id, auth()->user()->id);
                        if(isset($lastData->created_at) && !empty($lastData->created_at)){
                        $lastseen=date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . '', strtotime($lastData->created_at));
                        $lastseen= \Powerpanel\MessagingSystem\Models\MessagingSystem::relative_date(strtotime($lastData->created_at)); 
                        }else{
                        $lastseen='';
                        } if(isset($lastData->varShortDescription) && !empty($lastData->varShortDescription)){
                        $lastmsg=$lastData->varShortDescription; 
                        }elseif(isset($lastData->fkIntImgId)){
                        $lastmsg= "<i class='fa fa-picture-o' aria-hidden='true'></i>"; 
                        }
                        elseif(isset($lastData->fkIntDocId))
                        { 
                        $lastmsg= "<i class='fa fa-paperclip' aria-hidden='true'></i>"; 
                        }elseif(isset($lastData->varQuote) && $lastData->varQuote=='Y' && $lastData->varShortDescription=='')
                        { 
                        $lastmsg= "<i class='fa fa-quote-left'></i> quoted message"; 
                        }else{ 
                        $lastmsg= ""; 
                        } 
                        if ($userdata->id != auth()->user()->id) { 
                        @endphp
                        <li class="nav-link menu-link" data-userid='{{ $userdata->id }}'>
                            @if($CountUnRedata !=0) 
                                @php $unread="unread-msg-user"; @endphp 
                            @else 
                                @php $unread=""; @endphp 
                            @endif
                            <a href="javascript:void(0);" title="{{ $username }}" class="{{ $unread }} nav-link menu-link">
                            		<div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 chat-user-img {{ ($loggedinuser == 'Y') ? 'online' : '' }} align-self-center me-2 ms-0">
                                        <div class="avatar-xxs">
                                            <img src="{{ $logo_url }}" class="rounded-circle img-fluid userprofile" alt="image">
                                        </div>
                                        <span class="user-status"></span>
                                    </div>
                                    {{-- <div class="flex-grow-1 overflow-hidden">
                                        <p class="text-truncate mb-0">{{ $username }}</p>
                                    </div> --}}
                                    <div class="flex-shrink-0" id="newMSG_{{ $userdata->id }}">
                                        @if($CountUnRedata !=0)
                                        <span id="msg-number" class="badge badge-soft-light msg-number-count rounded p-1">{{$CountUnRedata}}</span>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        </li>
                    @php $i++; } } } @endphp
                </ul>
            </div>            
            <!-- End chat-message-list -->
        </div>

    </div>
    <!-- end chat leftsidebar -->
		@if($activeMode !='horizontal') @endsection @endif

    <!-- Start User chat -->
    <div class="user-chat w-100 overflow-hidden">

        <div class="chat-content d-lg-flex">
            <!-- start chat conversation section -->
            <div class="w-100 overflow-hidden position-relative">
                <!-- conversation user -->
                <div class="position-relative">
                    <div class="position-relative" id="htmldata" >
                        <div class="chat-conversation p-3 p-lg-4 message_signup" id="chat-conversation" data-simplebar>
                            @php 
                            $imagedata = \App\User::GetUserImage(auth()->user()->id); $username = \App\User::GetUserName(auth()->user()->id); 
                            if(!empty($imagedata)) { 
                            $logo_url = \App\Helpers\resize_image::resize($imagedata); 
                            } else { 
                            $logo_url = url($CDN_PATH.'/resources/image/packages/messagingsystem/man.png');
                            } 
                            $logindata = \App\LoginLog::getLoginHistryData($userdata->id); $loggedinuser = 'N'; 
                            if (!empty($logindata)) {
                            $loggedinuser = 'Y'; 
                            } 
                            @endphp
                            <div class="message_signup_div text-center">
                                <h2>Welcome, {{ $username }} </h2>
                                <div class="kt_sign_avtar">
                                    <div class="avtar_holder" style="background-image:url('{{ $logo_url }}')"></div>
                                    @if($loggedinuser == 'Y')
                                    <span class="kt-badge-dot"></span>
                                    @endif
                                </div>
                                <div class="kt-start-conv">
                                    <a href="javascript:void(0);" class="btn btn-primary add-element" onClick="startChat({{auth()->user()->id}})" title="Start a conversation">Start a conversation</a>
                                    <p>You are sign in as <strong>{{\App\Helpers\MyLibrary::getDecryptedString(auth()->user()->email)}}</strong></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end chat user head -->

                    <div class="chat-input-section p-3 p-lg-4" id="replayform" style="display:none"> 
                        <form id="MsgSystem" name="MsgSystem" enctype="multipart/form-data">
                            <div class="row g-0 align-items-center">
                                <div class="col-auto">
                                    <div class="chat-input-links me-2">
                                        <div class="links-list-item">
                                            <button type="button" class="btn btn-link text-decoration-none emoji-btn" id="emoji-btn">
                                                <i class="bx bx-smile align-middle"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="chat-input-feedback">
                                        Please Enter a Message
                                    </div>
                                    <textarea class="form-control chat-input bg-light border-light varShortDescription" id="varShortDescription" onkeypress="FilterInput('event')" name="varShortDescription" placeholder="Write a message." autocomplete="off"></textarea>
                                </div>

                                <div class="col-auto" id="fileuploaddiv">
                                    <div class="chat-input-links ms-2">
                                        <div class="links-list-item image_thumb multi_upload_images multi_file_upload mb-0">
                                            <button type="button" class="btn btn-primary chat-send waves-effect waves-light document_manager multiple-selection" data-multiple="true" onclick="MediaManager.openDocumentManager('publications');">
                                                <i class="ri-attachment-line align-bottom"></i>
                                            </button>
                                            <input class="form-control" type="hidden" id="publications" name="doc_id" value="">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto" id="imageuploaddiv">
                                    <div class="chat-input-links ms-2">
                                        <div class="links-list-item image_thumb multi_upload_images multi_file_upload fileinput fileinput-new mb-0">
                                            <button type="submit" class="btn btn-primary chat-send waves-effect waves-light media_manager multiple-selection" data-multiple="true" onclick="MediaManager.open('publications_image');">
                                                <i class="ri-image-line align-bottom"></i>
                                            </button>
                                            <input class="form-control" type="hidden" id="publications_image" name="img_id" value="" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="chat-input-links ms-2">
                                        <div class="links-list-item kt_chat__actions text-right multi_upload_images">
                                            <button type="button" id="btnSubmit" class="btn btn-primary chat-send waves-effect waves-light msgbutton multiple-selection" title="Send">
                                                <i class="ri-send-plane-2-fill align-bottom"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="" id="publications_documents" class="documents_section"></div>
                            <div class="" id="publications_image_img" class="images_section"></div>
                            <input type="hidden" id="toid" name="toid" value="">
                            <input type="hidden" id="editId" name="editId" value="">
                            <input type="hidden" id="formtype" name="formtype" value="add">
                            <span class="help-block errorclass"></span>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="new_modal modal fade login-user-popup" id="UserListData" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Select User / Admin</h5>
                <button type="button" class="close btn p-0" data-bs-dismiss="modal" aria-hidden="true"><i class="ri-close-line fs-18"></i></button>
            </div>
            <div class="modal-body">
                <ul class="login_user chartpopup-status">
                    @php 
                    $usersData = \Powerpanel\MessagingSystem\Models\MessagingSystem::getUserList();
                    $i = 0; 
                    foreach($usersData as $userdata){ 
                    if($userdata->id != '1'){
                    $imagedata = \App\User::GetUserImage($userdata->id);
                    $username = \App\User::GetUserName($userdata->id);
                    $useremail = \App\User::GetUserEmail($userdata->id);
                    if (!empty($imagedata)) {
                    $logo_url = \App\Helpers\resize_image::resize($imagedata);

                    } else { 
                    $logo_url = url($CDN_PATH.'/resources/image/packages/messagingsystem/man.png'); 
                    } 
                    $logindata = \App\LoginLog::getLoginHistryData($userdata->id);
                    $loggedinuser = 'N';
                    if (!empty($logindata)) {
                    $loggedinuser = 'Y';
                    }
                    if ($userdata->id != auth()->user()->id) {
                    @endphp
                    <li>
                        <a href="javascript:void(0)" class="pop-widget__item" data-toggle="pill" onclick="JumpIntoUser({{$userdata->id}})">
                            <span class="pop-userpic">
                                <span class="avatar-xxs">
                                    <img src="{{ $logo_url }}" alt="{{ $username }}" class="rounded-circle img-fluid"> 
                                </span>
                                @if($loggedinuser == 'Y')
                                <span class="kt-badge-dot"></span>
                                @endif
                            </span>
                            <div class="pop-widget__info">
                                <div class="pop-widget__section">
                                    <span href="javascript:void(0)" class="pop-widget__username">{{ $username }}</span>
                                    <span class="pop-email-data">({{ \App\Helpers\MyLibrary::getDecryptedString($useremail) }})</span>
                                </div>
                            </div>
                        </a>
                    </li>
                    @php } 
                    } 
                    }
                    @endphp
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="new_modal modal fade login-user-popup" id="ForwordUserListData" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <a href="javascript:void(0)" class="fwd-done disabled">Done</a>
                <h3 class="modal-title">Forward Message</h3>
            </div>
            <div class="forward-type-message">
                <span class="message-title">
                    <i class="fa fa-quote-left"></i> 
                    Test
                </span>
                <span class="overlay-input">
                    <input placeholder="Type a message here (optional)" type="search" id="new_forword_search_msg" name="new_forword_search_msg" class="form-control form-control-solid placeholder-no-fix search_msg">
                </span>
            </div>
            <div class="forward-search">
                    <!--<span class="overlay-srch-title">Search:</span>-->
                <span class="overlay-input">
                    <i class="la la-search"></i>
                    <input placeholder="Search people and groups" type="search" id="forword_search_msg" class="form-control form-control-solid placeholder-no-fix forword_search_msg">
                </span>
            </div>
            <div class="modal-body">
                <form id="formforword" name="formforword">
                    <input type="hidden" name="forwordRecId" id="forwordRecId">
                    <input type="hidden" name="varforquatnew" id="varforquatnew">
                </form>

                <ul class="login_user">
                    @php 
                    $usersData = \Powerpanel\MessagingSystem\Models\MessagingSystem::getUserList();
                    $i = 0; 
                    foreach($usersData as $userdata){ 
                    if($userdata->id != '1'){
                    $imagedata = \App\User::GetUserImage($userdata->id);
                    $username = \App\User::GetUserName($userdata->id);
                    $useremail = \App\User::GetUserEmail($userdata->id);
                    if (!empty($imagedata)) {
                    $logo_url = \App\Helpers\resize_image::resize($imagedata);

                    } else { 
                    $logo_url = url($CDN_PATH.'/resources/image/packages/messagingsystem/man.png'); 
                    } 
                    $logindata = \App\LoginLog::getLoginHistryData($userdata->id);
                    $loggedinuser = 'N';
                    if (!empty($logindata)) {
                    $loggedinuser = 'Y';
                    }
                    if ($userdata->id != auth()->user()->id) {
                    @endphp
                    <li>
                        <a href="javascript:void(0)" class="pop-widget__item" id="userid_{{$userdata->id}}" data-toggle="pill">
                            <span class="pop-userpic">
                                <img src="{{ $logo_url }}" alt="{{ $username }}"> 
                                @if($loggedinuser == 'Y')
                                <span class="kt-badge-dot"></span>
                                @endif
                            </span>
                            <div class="pop-widget__info">
                                <div class="pop-widget__section">
                                    <span class="pop-widget__username">{{ $username }}</span>
                                    <span class="pop-email-data">({{ \App\Helpers\MyLibrary::getDecryptedString($useremail) }})</span>
                                </div>
                            </div>
                            <span class="f-send" id="{{$userdata->id}}" title="Send"><i class="fa fa-check" aria-hidden="true"></i>Send</span>
                        </a>
                    </li>
                    @php } 
                    } 
                    }
                    @endphp
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="new_modal modal fade" id="Sing_Remove_Msg" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" >
        <div class="modal-vertical">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h3 class="modal-title">Remove Message</h3>
                </div>
                <div class="modal-body">
                    <form id="singlemsgremove" name="singlemsgremove">
                        <p>Are you sure you want to remove this message?</P>
                        <a id="msg_cancel" class="btn red btn-green-drake">Cancel</a>
                        <a id="msg_remove" class="btn btn-green-drake">Remove</a>
                        <input type="hidden" id="removemsgid" name="removemsgid" value="">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!--<a id="MulRemoveMsg">Remove</a>-->
@if (File::exists(base_path() . '/resources/views/powerpanel/partials/deletePopup.blade.php') != null)
@include('powerpanel.partials.deletePopup')
@endif
@if (File::exists(base_path() . '/resources/views/powerpanel/partials/approveRecord.blade.php') != null)
@include('powerpanel.partials.approveRecord')
@endif
@if (File::exists(base_path() . '/resources/views/powerpanel/partials/cmsPageComments.blade.php') != null)
@include('powerpanel.partials.cmsPageComments',['module'=>Config::get('Constant.MODULE.TITLE')])
@endif
@endsection @section('scripts')

{{-- <script src="{{ $CDN_PATH.'resources/pages/scripts/packages/messagingsystem/inputEmoji.js' }}" type="text/javascript"></script> --}}
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/messagingsystem/vanillaEmojiPicker.js' }}" type="text/javascript"></script>
<script>
new EmojiPicker({
    trigger: [
        {
            selector: '.emoji-btn',
            insertInto: '.varShortDescription'
        }
    ],
    closeButton: true,
    //specialButtons: green
});
</script>

<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/messagingsystem/uikit.min.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/messagingsystem/uikit-icons.min.js' }}" type="text/javascript"></script>

{{-- <script src="{{ $CDN_PATH.'resources/assets/js/pages/chat.init.js' }}"></script> --}}
<!-- fgEmojiPicker js -->
{{-- <script src="{{ $CDN_PATH.'resources/assets/libs/fg-emoji-picker/fgEmojiPicker.js' }}"></script> --}}
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/messagingsystem/contextMenu.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/messagingsystem/jquery.ui.position.min.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/messagingsystem/messagingsystem.js' }}" type="text/javascript"></script>
<script>
var dataid = '@php echo auth()->user()->id; @endphp';
</script>
@endsection