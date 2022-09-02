
<li class="external notification-header clearfix">
    <div class="notification_title pull-left">Notification 
        @if($Total > 0)
        <span>{{$Total}} New</span>
        @endif
    </div>
    @if($Total > 0)   
    <div class="markread_link pull-right"><a href="javascript:;" onclick="Read_All_Notification()">Mark as all read</a></div>   
    @endif
    <div class="markread_link pull-right"><a href="{{ url('/powerpanel/notificationlist') }}" id="notification"><strong><u>View All</u></strong></a></div></li>    
<li id="notification_html">
    @php 
    $notificationIcon = $CDN_PATH.'resources/images/man.png'; 
    @endphp
    <div id="slim_notification"  class="notification_list" style="max-height: 275px;">
        @if(!empty($Today) && count($Today)>0)
        <h5 class="title_sub">Today</h5>  
        <ul>
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
            $class='';
            }else{
            $class='selected_read';
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
            <li>
                <a href="javascript:void(0);" data-redirectionmodule="{{ $redirctionModule }}" class="{{ $class }}" onclick="Read_Notification(this,'{{ $row->id }}','{{ $row->fkIntModuleId }}','{{ $row->fkRecordId }}','{{ $row->ModuleName }}')" title="{{ $row->txtNotification }}">
                    <div class="notify_img">
                        <img src="{{ $notificationIcon }}" height="100%" width="100%">                                                    
                    </div>    
                    <div class="notify_info">
                        <div class="noti_info_sub"><strong>{{ $row->txtNotification }}</strong></div>
                        <div class="noti_info_small small">{{ $dispalyInfo }} <span>{{ $notificationDate }}</span></div>
                    </div>    
                </a>
            </li> 
            @endforeach
        </ul>  
        @endif
        @if(!empty($sevenDays) && count($sevenDays)>0)
        <h5 class="title_sub">Previous Notifications </h5>  
        <ul>
            @foreach($sevenDays as $row)
            @php
            $notificationDate = date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($row->created_at));
            $Readseven = \App\UserNotification::getReadRecordList($row->id);
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
            if($Readseven > 0){
            $class='';
            }else{
            $class='selected_read';
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
            <li>
                <a href="javascript:void(0);" data-redirectionmodule="{{ $redirctionModule }}" class="{{ $class }}" onclick="Read_Notification(this,'{{ $row->id }}','{{ $row->fkIntModuleId }}','{{ $row->fkRecordId }}','{{ $row->ModuleName }}')" title="{{ $row->txtNotification }}">
                    <div class="notify_img"> 
                        <img src="{{ $notificationIcon }}" height="100%" width="100%">
                    </div>    
                    <div class="notify_info">
                        <div class="noti_info_sub"><strong>{{ $row->txtNotification }}</strong></div>
                        <div class="noti_info_small small">{{ $dispalyInfo }} <span>{{ $notificationDate }}</span></div>
                    </div>      
                </a>
            </li>
            @endforeach
        </ul>  
        @endif
        @if(count($Today) <=0 && count($sevenDays) <= 0)
        <h5 class="text-center"><strong>No Data Available</strong></h5>
        @endif
    </div>        
</li>