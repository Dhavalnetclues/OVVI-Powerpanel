@php 
$menuArr = App\Helpers\PowerPanelSidebarConfig::getConfig();
$segmentArr = request()->segments();
$versioning = '?'.date('dmy');
@endphp
<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="{{ url('/powerpanel') }}" class="logo logo-dark" title="{{ Config::get('Constant.SITE_NAME') }}">
            <span class="logo-sm">
                <img src="{{ Config::get('Constant.CDN_PATH').'resources/images/powerpanel-icon.png' }}" alt="{{ Config::get('Constant.SITE_NAME') }}"> <!-- height="20" -->
            </span>
            <span class="logo-lg">
                <img src="{{ Config::get('Constant.CDN_PATH').'resources/images/Powerpanel-logo.png' }}" alt="{{ Config::get('Constant.SITE_NAME') }}"> <!-- height="20" -->
            </span>
        </a>
        <!-- Light Logo-->
        <a href="{{ url('/powerpanel') }}" class="logo logo-light" title="{{ Config::get('Constant.SITE_NAME') }}">
            <span class="logo-sm">
                <img src="{{ Config::get('Constant.CDN_PATH').'resources/images/powerpanel-icon.png' }}" alt="{{ Config::get('Constant.SITE_NAME') }}"> <!-- height="20" -->
            </span>
            <span class="logo-lg">
                <img src="{{ Config::get('Constant.CDN_PATH').'resources/images/Powerpanel-logo-light.png' }}" alt="{{ Config::get('Constant.SITE_NAME') }}"> <!-- height="20" -->
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
            id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">
            
            <div id="two-column-menu">
            </div>
           @if(!in_array(end($segmentArr), ['messagingsystem','settings']) )
           	@include('powerpanel.partials.dashboard_sidebar',['menuArr'=>$menuArr, 'segmentArr'=>$segmentArr])
        	 @elseif(in_array(end($segmentArr), ['messagingsystem','settings']))
        	 	@yield('sidebar')
        	 @endif
        </div>
        <!-- Sidebar -->
    </div>

    <div class="navbar-footer">
        {{-- <ul class="quick-menu text-center"> <!-- d-flex justify-content-between -->
            <li>
                <a id="dashboard-short" href="{{ url('powerpanel') }}" title="Dashboard"><i class="ri-dashboard-2-line fs-20"></i></a>
            </li>
            <li>
                <a id="messagingsystem" href="{{ url('powerpanel/messagingsystem') }}" title="Powerchat">
                    <i class="ri-wechat-line fs-20"></i>
                    @php
                    $count= \Powerpanel\MessagingSystem\Models\MessagingSystem::GetCountMessage(auth()->user()->id);
                    if($count > 0) {
                        echo '<span class="pulse-danger success"></span>';
                    }
                    @endphp
                </a>
            </li>
            @can('settings-general-setting-management')
            <li>
                <a id="settings" href="{{ url('powerpanel/settings') }}" title="Settings"><i class="ri-settings-2-line fs-20"></i></a>
            </li>
            @endcan
            <li>
                <div class="dropdown help-dropdown">
				   <a class="help-btn" data-bs-toggle="dropdown" title="Help Center" aria-haspopup="true" aria-expanded="false">
				     <i class="ri-question-line fs-20"></i>
				   </a>                   
				    <div class="dropdown-menu">
				        <h6 class="dropdown-header">Help & Support</h6>
				        <a class="dropdown-item" title="Knowledge Base" href="#">
					       <span class="align-middle">Knowledge Base</span>
				        </a>
				        <a class="dropdown-item" title="Check Ticket Status" href="{{ url('powerpanel/submit-tickets') }}">
					        <span class="align-middle">Check Ticket Status</span>
				        </a>
				        <a class="dropdown-item title_fixed title_icon_ticket" title="Submit a Ticket" href="javascript:void(0)">
					        <span class="align-middle">Submit a Ticket</span>
				        </a>
                        <hr />
				        <h6 class="dropdown-header">Have a question?</h6>
				        <a class="dropdown-item" title="Call Us on: +1 (345)-936-2222" href="tel:+13459362222" target="_blank"><strong>Call: </strong> +1 (345)-936-2222</a>
				    </div>
                </div>
            </li>
        </ul> --}}

        <!-- FIXED FORM -->
        <div class="fixed_from" id="Test">
            {{-- <a class="title_fixed title_icon_ticket" title="Submit a Ticket" href="javascript:void(0)"><i class="ri-question-line"></i></a> --}}
            {!! Form::open(['method' => 'post','id'=>'Ticket_Form','name'=>'Ticket_Form','url'=>url('powerpanel/settings/insertticket'),'enctype'=>'multipart/form-data']) !!}
            <h4 class="form-title">
                Submit a Ticket <a class="title_fixed" title="Submit a Ticket" href="javascript:void(0)"><i class="ri-close-line"></i></a>
            </h4>
            <div class="bma_form">
                <div class="mb-3">
                    {!! Form::text('Name',$SecurityUser->name, array('id' => 'Name', 'class' => 'form-control', 'placeholder'=>'Enter Your Name' ,'readonly')) !!}
                    @if($errors->has('Name'))
                    <span class="help-block">{{ $errors->first('Name') }}</span>
                    @endif
                    <span class="help-block"></span>
                </div>
                <div class="mb-3">
                    <select class="form-control form-select bs-select select2" name="varType" id="varType">
                        <option value="">Type</option>
                        <option value="1">Fixes / Issues</option>
                        <option value="2">Changes</option>
                        <option value="3">Suggestion</option>
                        <option value="4">New Features</option>
                    </select>
                    @if($errors->has('varType'))
                    <span class="help-block">{{ $errors->first('varType') }}</span>
                    @endif
                    <span class="help-block"></span>
                </div>
                <div class="mb-3">
                    {!! Form::textarea('varMessage', old('varMessage') , array( 'class' => 'form-control', 'cols' => '20', 'rows' => '3', 'id' => 'varMessage', 'spellcheck' => 'true','placeholder'=>'Enter Your Message' )) !!}
                    @if($errors->has('varMessage'))
                    <span class="help-block">{{ $errors->first('varMessage') }}</span>
                    @endif
                    <span class="help-block"></span>
                </div>
                <div class="mb-0">
                    <div class="row row_file">
                        <div class="col-sm-6 fkimg_val">
                            <div class="js-inputbox">
                                <input type="file" name="file-1[]" id="file-1" class="inputfile inputfile-1 fkimg_val" data-multiple-caption="{count} files selected" multiple />
                                <label for="file-1"><svg xmlns="https://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"/></svg> <span>Select a file</span></label>
                            </div>
                            @if($errors->has('file-1'))
                            <span class="help-block">{{ $errors->first('file-1') }}</span>
                            @endif
                            <span class="help-block"></span>
                            <div class="validation" style="display:none;color:#e73d4a"> Upload Max 5 Files allowed. </div>
                            <span id="fkimg_val123"></span>
                        </div>
                        <div class="col-sm-6">
                            <a href="javascript:;" onclick="report()" class="btn btn-soft-primary capture_btn">Capture</a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <input type="hidden" name="img_val1" id="img_val1" value="" />
                            <img width="50%" class="screen" >
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label>Link</label>
                    <input type="text" name="Link" id="Link" value="{{ "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']}}" placeholder="Enter Your Link." readonly class="form-control">
                    @if($errors->has('Link'))
                    <span class="help-block">{{ $errors->first('Link') }}</span>
                    @endif
                    <span class="help-block"></span>
                </div>
                <div class="mb-0">
                    <button type="submit" formmethod="post" title="Submit" class="btn btn-primary btn_fixed">Submit</button>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
        <!-- FIXED FORM End -->

        <div class="design-by">
            <div class="dropdown after-userlogin"> <!-- ms-sm-3 -->
                <button type="button" class="btn text-white fs-14 p-0 py-3 userbtn" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="d-flex align-items-center">
                        <i class="ri-user-line fs-20"></i>
                        {{-- <img class="header-profile-user" src="{{ $User_logo_url }}" alt="{{ auth()->user()->name }}"> --}}
                        <span class="text-start ms-xl-2">
                            <span class="d-inline-block ms-1 user-name-text">{{ auth()->user()->name }}</span>
                        </span>
                    </span>
                </button>
                <div class="dropdown-menu">
                    <h6 class="dropdown-header">Welcome!</h6>
                    <a class="dropdown-item" href="{{ url('/powerpanel/changeprofile') }}" title="{{ trans('template.header.myProfile') }}">
                        <i class="ri-user-line text-muted fs-16 align-middle me-1"></i> 
                        <span class="align-middle">{{ trans('template.header.myProfile') }}</span>
                    </a>                    
                    
                    @if(isset($menuArr['can-security-list']) && $menuArr['can-security-list'])
                    <a class="dropdown-item" title="Security Settings" href="{{ url('powerpanel/security-settings') }}" {{ $menuArr['security_active'] }}>
                        <i class="ri-lock-line text-muted fs-16 align-middle me-1"></i>
                        <span class="align-middle">Security</span>
                    </a>
                    @endif
                    
                    <a class="dropdown-item" title="{{ trans('template.header.changePassword') }}" href="{{ url('/powerpanel/changepassword') }}">
                        <i class="ri-key-2-line text-muted fs-16 align-middle me-1"></i>
                        <span class="align-middle">{{ trans('template.header.changePassword') }}</span>
                    </a>
                    
                    <a class="dropdown-item" title="{{ trans('template.header.logOut') }}" href="javascript:void(0);" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="ri-logout-box-r-line text-muted fs-16 align-middle me-1"></i> 
                        <span class="align-middle" data-key="t-logout">{{ trans('template.header.logOut') }}</span>
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </div>
            </div>
            <div class="copyright d-inline-block">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 0C114.6 0 0 114.6 0 256s114.6 256 256 256s256-114.6 256-256S397.4 0 256 0zM256 464c-114.7 0-208-93.31-208-208S141.3 48 256 48s208 93.31 208 208S370.7 464 256 464zM255.1 176C255.1 176 255.1 176 255.1 176c21.06 0 40.92 8.312 55.83 23.38c9.375 9.344 24.53 9.5 33.97 .1562c9.406-9.344 9.469-24.53 .1562-33.97c-24-24.22-55.95-37.56-89.95-37.56c0 0 .0313 0 0 0c-33.97 0-65.95 13.34-89.95 37.56c-49.44 49.88-49.44 131 0 180.9c24 24.22 55.98 37.56 89.95 37.56c.0313 0 0 0 0 0c34 0 65.95-13.34 89.95-37.56c9.312-9.438 9.25-24.62-.1562-33.97c-9.438-9.312-24.59-9.219-33.97 .1562c-14.91 15.06-34.77 23.38-55.83 23.38c0 0 .0313 0 0 0c-21.09 0-40.95-8.312-55.89-23.38c-30.94-31.22-30.94-82.03 0-113.3C214.2 184.3 234 176 255.1 176z"/></svg>
                <span class="hideshow"> 
                    <!-- Copyright -->&COPY; {{ date('Y') }} Netclues. | <a class="addTermsandCondition" title="Terms &amp; Conditions" target="_blank" href="https://www.netclues.com/terms-conditions">Terms &amp; Conditions</a>
                </span>
            </div>
        </div>
    </div>
</div>