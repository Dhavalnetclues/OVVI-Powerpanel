
<div class="top_browser_note" id="topMsg" style="display: none;">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="browser_note">{{ trans('template.header.siteView') }} <strong>I.E 8+</strong>,
                    <strong>Mozilla 46+</strong>, <strong>Google Chrome 5+</strong>, <strong>Safari 5.0 +</strong>
                </div>
            </div>
        </div>
    </div>
    <div class="close_icn">
        <i class="ri-time-line" id="close_icn" style="cursor:pointer"></i>
    </div>
</div>
<!-- BEGIN HEADER -->
<div class="page-header navbar navbar-fixed-top">
    <!-- BEGIN HEADER INNER -->
    <div class="page-header-inner">
        <!-- BEGIN LOGO -->
        <div class="page-logo">
            <a href="{{ url('/powerpanel') }}">
                <img src="{{ App\Helpers\resize_image::resize(Config::get('Constant.FRONT_LOGO_ID')) }}" alt="{{ Config::get('Constant.SITE_NAME') }}">
            </a>
            <div class="menu-toggler sidebar-toggler">
                <!--<i class="fa fa-bars"></i>-->
                <!-- DOC: Remove the above "hide" to enable the sidebar toggler button on header -->

                <div id="nav-icon1" class="">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>


            </div>
        </div>
         	
        <!-- END LOGO -->
        <!-- BEGIN RESPONSIVE MENU TOGGLER -->
        <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse"> </a>
        <!-- END RESPONSIVE MENU TOGGLER -->
        <!-- BEGIN PAGE TOP -->
        <div class="page-top">
            <!-- time and weather code start -->
            <!--<div class="time-date-iframe"><iframe src="http://free.timeanddate.com/clock/i6pniw1r/n173/fn17/tct/pct/ftb/tt0/ta1/tb4" frameborder="0" width="100%" allowTransparency="true"></iframe></div>-->
            <!-- weather widget start -->
            <!--<img src="https://w.bookcdn.com/weather/picture/13_19267_1_1_0286bf_158_fff5d9_faf5fa_ffffff_3_fff5d9_333333_0_6.png?scode=124&domid=w209&anc_id=79531"  alt="booked.net"/>-->
            <!-- weather widget end -->
            <!-- time and weather code end -->
            <!-- BEGIN HEADER SEARCH BOX -->
            <!-- DOC: Apply "search-form-expanded" right after the "search-form" class to have half expanded search box -->
            <!-- END HEADER SEARCH BOX -->
            <!-- BEGIN TOP NAVIGATION MENU -->
            <div class="top-menu">
                <ul class="nav navbar-nav pull-right">
                    <li class="separator hide"> </li>
                    <!-- BEGIN NOTIFICATION DROPDOWN -->
                    <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                    <li class="dropdown dropdown-extended hide">
                        <form id="setLocale" class="language_select">
                            <select class="select2" name="locale">
                                {{-- @php
								@foreach($allLocale as $locale)
								<option value="{{ $locale }}" @php $cnt=0; foreach($_COOKIE as $key=>$cookie){ if($cookie==$locale){ echo 'selected'; $cnt++; } elseif(strtolower($locale)=='en' && $cnt==0){echo 'selected'; $cnt++;} } @endphp >{{ strtoupper($locale) }}</option>
                                @endforeach
                                @endphp --}}
                                <option value="en" selected>English</option>
                            </select>
                            {{ Form::token() }}
                        </form>
                    </li>
                        <script type="text/javascript">
	    var x = new Date('<?php print date(Config::get('Constant.DEFAULT_DATE_FORMAT').'  '.Config::get('Constant.DEFAULT_TIME_FORMAT'), time())?>')

	    function display_c(){
	    x.setSeconds(x.getSeconds() + 1);
	    var refresh=1000; // Refresh rate in milli seconds
	    mytime=setTimeout('display_ct()',refresh)
	}

	function padzero(num,count) {
	var num = num + '';
	while(num.length < count) {
	num = "0" + num;
	}
	return num;
	}

	function display_ct() {
	var weekday=new Array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday")
	var monthname=new Array("Jan","Feb","March","April","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec")

	var y;

	y = monthname[x.getMonth()] + " "
	y = y + padzero(x.getDate(), 2)
	y = y + ", "
	y = y + x.getFullYear()


	document.getElementById('ct').innerHTML = y

	var T;
	var ampm = x.getHours() >= 12 ? 'PM' : 'AM';
	T = padzero(x.getHours(), 2)
	T = T + ":"
	T = T + padzero(x.getMinutes(), 2)
	T = T + " "
	T = T + ampm

	document.getElementById('ct_2').innerHTML = T
	tt=display_c();
	}
</script>
                   <li class="dropdown date_clock"><div class="date_timer_powerpanel"><span id="ct"><?php  echo  date(Config::get('Constant.DEFAULT_DATE_FORMAT'));?></span><span id="ct_2"><?php  echo date(Config::get('Constant.DEFAULT_TIME_FORMAT'));?></span></div></li>

                    <li class="dropdown dropdown-user viewsitemobile dro pdown-browser">
                        <a href="JavaScript:Void(0);" class="dropdown-toggle viewsite" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" title="Browser Support">
                            <span class="username"><i class="icon-globe"></i> Browser Support</span>
                        </a>
                        <ul class="dropdown-menu">
                            @php
                            $version = App\Http\Controllers\PowerpanelController::GetVersion();
                            $browser = App\Http\Controllers\PowerpanelController::GetBrowser();
                            $versiondata = explode(".",$version);
                            if($versiondata[0] <= 75 && $browser=='Chrome'){
                            $cromelink = 'https://chrome.en.softonic.com/download';
                            }else{
                            $cromelink = '';
                            }if($versiondata[0] <= 11 && $browser=='IE'){
                            $internatelink = 'https://internet_explorer.en.downloadastro.com/';
                            }else{
                            $internatelink = '';
                            }if($versiondata[0] <= 68 && $browser=='Firefox'){
                            $firefoxlink = 'https://mozilla_firefox.en.downloadastro.com/';
                            }else{
                            $firefoxlink = '';
                            }if($versiondata[0] <= 5 && $browser=='Safari'){
                            $safarilink = 'https://safari.en.softonic.com/download';
                            }else{
                            $safarilink = '';
                            }if($versiondata[0] <= 62 && $browser=='Opera'){
                            $operalink = 'https://opera.en.softonic.com/download';
                            }else{
                            $operalink = '';
                            }
                            @endphp
                            <li>
                                <a href="javascript:void(0)">
                                    <i class="fa fa-chrome"></i> Google Chrome
                                    <span class="brows-version">75 +</span>
                                    @if($versiondata[0] <= 75 && $browser=='Chrome')
                                    <a class="update_div" href="{{ $cromelink }}" target="_blank" title="Latest Version Update">Latest Version Update</a>
                                    @endif
                                </a>
                            </li>    
                            <li>
                                <a href="javascript:void(0)">
                                    <i class="fa fa-internet-explorer"></i> Internet Explorer
                                    <span class="brows-version">11 +</span>
                                    @if($versiondata[0] <= 11 && $browser=='IE')
                                    <a class="update_div" href="{{ $internatelink }}" target="_blank" title="Latest Version Update">Latest Version Update</a>
                                    @endif
                                </a>
                            </li>    
                            <li>
                                <a href="javascript:void(0)"><i class="fa fa-firefox"></i> Firefox
                                    <span class="brows-version">68 +</span>    
                                    @if($versiondata[0] <= 68 && $browser=='Firefox')
                                    <a class="update_div" href="{{ $firefoxlink }}" target="_blank" title="Latest Version Update">Latest Version Update</a>
                                    @endif
                                </a>
                            </li>    
                            <li>
                                <a href="javascript:void(0)"><i class="fa fa-safari"></i> Safari
                                    <span class="brows-version">5 +</span>   
                                    @if($versiondata[0] <= 5 && $browser=='Safari')
                                    <a class="update_div" href="{{ $safarilink }}" target="_blank" title="Latest Version Update">Latest Version Update</a> 
                                    @endif
                                </a>
                            </li>    
                            
                        </ul>    
                    </li>
                    @php 
                    $menuArr = App\Helpers\PowerPanelSidebarConfig::getConfig(); 
                    @endphp
                    @if(isset($menuArr['can-liveuser-list']) && $menuArr['can-liveuser-list'])
                    <li class="dropdown dropdown-user viewsitemobile {{ $menuArr['liveuser_active'] }}">
                        <a href="{{ url('powerpanel/live-user') }}" class="dropdown-toggle viewsite" title="Live User">
                            <span class="username"><i class="fa fa-circle icon-live"></i> Live User</span>
                        </a>                                                
                    </li>
                    @endif
                    <!-- END NOTIFICATION DROPDOWN -->
                    <!-- <li class="separator hide"> </li> -->
                    <!-- BEGIN INBOX DROPDOWN -->
                    <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                    <!-- <li class="dropdown dropdown-extended dropdown-notification">
                            <a href="javascript:;" class="dropdown-toggle" id="message" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                            <i class="icon-envelope-open"></i>
                                            <span class="message-count">
                                            </span>
                            </a>
                            <ul class="dropdown-menu">
                                            <li class="external message-count-bold"></li>
                                            <li id="message_html">
                                            </li>
                            </ul>
                    </li> -->
                    <!--					<li class="dropdown dropdown-help viewsitemobile">
                                                                    <a href="{{url('/powerpanel/help')}}" target="_blank" class="dropdown-toggle viewsite" title="Click here to see all video tutorials">
                                                                            <span class="help-que"><i class="fa fa-question-circle" aria-hidden="true"></i></span>
                                                                    </a>
                                                            </li>-->
                            @php
                                $GTM = DB::table('general_setting')
                                ->where('fieldName','GOOGLE_TAG_MANAGER_FOR_BODY')
                                ->get();
                                $GTMdata = $GTM[0]; 

                            @endphp

                    <li class="dropdown dropdown-user viewsitemobile">
                        <a href="JavaScript:Void(0);"  class="dropdown-toggle viewsite" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" title="{{ trans('template.header.googleAnalytic') }}">
                            <span class="username"> {{ trans('template.header.googleAnalytic') }} </span>
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                            @if(!empty($GTMdata->fieldValue))
                                    <p>Your website is connected to Google Analytics. <a href="https://analytics.google.com/"  style="color:#1D4DA1" target="_blank">Click here</a> to access Google Analytic</p>
                                    @else
                                    <p>Your website is not connected to Google Analytics. <a href="https://analytics.google.com/"  style="color:#1D4DA1" target="_blank">Click here</a> to access Google Analytic</p>
                                @endif
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown dropdown-user viewsitemobile">
                        <a href="{{url('/')}}" target="_blank" class="dropdown-toggle viewsite" title="View Website">
                            <span class="username username-hide-on-mobile"> {{ trans('template.header.viewSite') }} </span>
                        </a>
                    </li><li class="dropdown dropdown-search open">
                        <a href="javascript:;" class="dropdown-toggle"  data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                            <i class="icon-magnifier" style="color:rgba(0, 41, 92,1);"></i>                            
                        </a>
                        <div class="dropdown-menu" >
                            <form class="search-form search-form-expanded" id="searchForm">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="inputsearch" placeholder="Search" autocomplete="off" > 
                                    <span class="input-group-btn">
                                        <a href="javascript:;" class="btn" id="globalsearch" title="Search">
                                          <i class="icon-magnifier"></i>
                                        </a>
                                    </span>
                                </div>
                                <ul class="srchlist_result" id="frontSearchHeaderWebRes"></ul>
                            </form>
                        </div>
                    </li>
                    <li class="dropdown dropdown-notification">
                        <a href="javascript:;" class="dropdown-toggle" id="notification" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                            <i class="icon-bell"></i>
                            <span class="notification-count" style="display: none;"></span>
                        </a>
                        <ul class="dropdown-menu" id="notification-view"></ul>
                    </li>
                    <li class="dropdown dropdown-user dropdown-dark userdrop">
                        <a href="javascript:;" class="dropdown-toggle viewsite" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" title="{{ auth()->user()->name }}">
                            <span class="username username-hide-on-mobile"> {{ auth()->user()->name }}</span>
                            @if (!empty($User_logo_url))
                            <img class="img-circle" src="{{ $User_logo_url }} "/>
                            @endif
                            <i class="fa fa-angle-down"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-default">
                            <li>
                                <a title="{{ trans('template.header.myProfile') }}" href="{{ url('/powerpanel/changeprofile') }}">
                                    <i class="icon-user"></i> {{ trans('template.header.myProfile') }}
                                </a>
                            </li>
                            @can('settings-general-setting-management')
                            <li>
                                <a href="{{ url('/powerpanel/settings') }}" title="{{ trans('template.header.settings') }}">
                                    <i class="icon-settings"></i> {{ trans('template.header.settings') }}
                                </a>
                            </li>
                            @endcan
                            <!-- @can('my-calender')
                            <li>
                                    <a href="{{ url('/powerpanel/calender') }}"></a>
                            </li>
                            @endcan -->
                            @if(isset($menuArr['can-security-list']) && $menuArr['can-security-list'])
                            <li {{ $menuArr['security_active'] }}>
                                <a title="Security Settings" href="{{ url('powerpanel/security-settings') }}">
                                    <i class="icon-lock"></i>Security
                                </a>
                            </li>
                            @endif

                            <li>
                                <a title="{{ trans('template.header.changePassword') }}" href="{{ url('/powerpanel/changepassword') }}">
                                    <i class="icon-lock"></i> {{ trans('template.header.changePassword') }}
                                </a>
                            </li>
                            <li>
                                <a title="{{ trans('template.header.logOut') }}" href="javascript:;" onclick="event.preventDefault();
                                                                    document.getElementById('logout-form').submit();">
                                    <i class="icon-key"></i> {{ trans('template.header.logOut') }} </a>
                            </li>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </ul>
                    </li>
                    <!-- END USER LOGIN DROPDOWN -->
                </ul>
            </div>
            <!-- END TOP NAVIGATION MENU -->
        </div>
        <!-- END PAGE TOP -->
    </div>
    <!-- END HEADER INNER -->
</div>
<!-- END HEADER -->
<!-- BEGIN HEADER & CONTENT DIVIDER -->
<div class="clearfix"> </div>
@include('powerpanel.partials.googleAnalyticModal')
<!-- END HEADER & CONTENT DIVIDER -->
<script>
                            var Read_Notification_URL = '{!! url("/powerpanel/user_notification/update_read_status") !!}';
                            function Read_Notification(object, id, ModuleId, RecordId, ModuleName) {
                            var redirectmodule = $(object).attr('data-redirectionmodule');
                                    if (redirectmodule != ""){
                            var url = '{!! url("/powerpanel") !!}' + '/' + redirectmodule + '?notifications';
                            } else{
                            var url = '{!! url("/powerpanel") !!}' + '/' + ModuleName + '?notifications';
                            }

                            $.ajax({
                            type: 'POST',
                                    url: Read_Notification_URL,
                                    data: 'id=' + id + '&ModuleId=' + ModuleId + '&RecordId=' + RecordId,
                                    success: function (msg) {
                                    window.location.href = url;
                                    }
                            });
                            }
</script>
<script>
                    var Read_All_Notification_URL = '{!! url("/powerpanel/user_notification/update_read_all_status") !!}';
                            var url = '{!! url("/powerpanel/contact-us") !!}';
                            function Read_All_Notification() {
                            $.ajax({
                            type: 'POST',
                                    url: Read_All_Notification_URL,
                                    success: function (msg) {
                                    location.reload();
                                    }
                            });
                            }

</script>


