@if(!empty($Today) && count($Today)>0)
<div data-simplebar class="p-3 pt-0 sidebar-notification">
    <div class="acitivity-timeline acitivity-main">
        @php $notificationIcon = $CDN_PATH.'resources/images/man.png'; @endphp
        @php
            $N_recordRepeatInfo = array();
            $N_recorduserinfo = array();
        @endphp

        @foreach($Today as $row)
            @php
                $notificationDate = date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($row->created_at));
                $ReadToday = \App\UserNotification::getReadRecordList($row->id);
                if (!isset($N_recordRepeatInfo[$row->ModelName][$row->fkRecordId]) && empty($N_recordRepeatInfo[$row->ModelName][$row->fkRecordId])) {
                    $recordData = \App\Helpers\Mylibrary::getModuleWiseRecordData($row->ModelName,$row->varTableName,$row->fkRecordId);
                    $N_recordRepeatInfo[$row->ModelName][$row->fkRecordId] = $recordData;
                }else{
                    $recordData = $N_recordRepeatInfo[$row->ModelName][$row->fkRecordId];
                }

                if(isset($recordData->varEmail)){
                    $userEmail = \App\Helpers\Mylibrary::getLaravelDecryptedString($recordData->varEmail);
                    $dispalyInfo = $recordData->Title." - ".$userEmail;
                    $notificationIcon = $CDN_PATH.'resources/images/phone_icon.svg';
                }else{
                    $notificationIcon = $CDN_PATH.'resources/images/man.png'; 
                    if(isset($recordData->Title)){
                        $dispalyInfo = $recordData->Title;		
                    }else{
                        $dispalyInfo = "Record not Available";
                    }
                    if (!isset($N_recorduserinfo[$row->fkIntUserId]) && empty($N_recorduserinfo[$row->fkIntUserId])) {
                        $userlogoData = \App\Helpers\Mylibrary::getUserLogoByUserID($row->fkIntUserId);
                        $N_recorduserinfo[$row->fkIntUserId] = $userlogoData;
                    }else{
                        $userlogoData = $N_recorduserinfo[$row->fkIntUserId];
                    }

                    if(!empty($userlogoData)){
                        $notificationIcon =	$userlogoData;
                    }
                }
                if($ReadToday > 0){
                    $class='stretched-link';
                }else{
                    $class='text-muted';
                }
                $redirctionModule ="";
                if(!$userIsAdmin){
                    if($row->chrNotificationType=="C"){
                        $redirctionModule ="dashboard";	
                    }else if($row->chrNotificationType=="T"){
                        $redirctionModule ="dashboard";
                    }else{
                        $redirctionModule = $row->ModuleName;
                    }
                }else{
                    if(!in_array($row->fkIntModuleId,$currentUserAccessibleModulesIDs))
                    {
                        $redirctionModule ="dashboard";	
                    }else{
                        $redirctionModule = $row->ModuleName;
                    }
                }
            @endphp
            <div class="acitivity-item d-flex pb-3">
                <div class="flex-shrink-0 avatar-xs acitivity-avatar">
                    <div class="avatar-title bg-soft-success text-success rounded-circle">
                        <img src="{{ $notificationIcon }}" alt="user-pic">
                    </div>
                </div>
                <div class="flex-grow-1 ms-3">
                    <a href="javascript:void(0);" data-redirectionmodule="{{ $redirctionModule }}" class="{{ $class }}" onclick="Read_Notification(this,'{{ $row->id }}','{{ $row->fkIntModuleId }}','{{ $row->fkRecordId }}','{{ $row->ModuleName }}')" title="{{ $row->txtNotification }}">
                        <h6 class="mb-1 lh-base">{{ $row->txtNotification }}</h6>
                    </a>
                    <p class="text-muted mb-1">{{ $dispalyInfo }}</p>
                    <small class="mb-0 text-muted">{{ $notificationDate }}</small>
                </div>
            </div>
        @endforeach
    </div>
</div>
<div class="card sidebar-alert border-0 shadow-none text-center mb-0 mt-2">
    <div class="card-body">
        {{-- <button type="button" class="btn btn-soft-primary waves-effect waves-light" onclick="Read_All_Notification()">Mark as all read</button> --}}
        <a class="text-muted" href="{{ url('/powerpanel/notificationlist') }}" id="notification" title="View All">View All <i class="ri-arrow-right-line"></i></a>
    </div>
</div>

@else
<div data-simplebar style="max-height: 410px;" class="p-3 pt-0">
    <div class="acitivity-timeline acitivity-main">
        <div class="w-25 w-sm-50 pt-3 mx-auto">
            <img src="{{ Config::get('Constant.CDN_PATH').'resources/assets/images/svg/bell.svg' }}" class="img-fluid" alt="Notification">
        </div>
        <div class="text-center pb-5 mt-2">
            <h6 class="fs-18 fw-semibold lh-base">Hey! You have no any notifications </h6>
        </div>
    </div>
</div>
@endif