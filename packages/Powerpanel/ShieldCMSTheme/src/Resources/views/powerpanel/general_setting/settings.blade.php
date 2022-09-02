@extends('powerpanel.layouts.app')
@section('title')
{{Config::get('Constant.SITE_NAME')}} - PowerPanel
@endsection
@section('content')

{{-- @include('powerpanel.partials.breadcrumbs') --}}

<div class="row">
    <div class="col-xxl-12">
        @if(Session::has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ Session::get('message') }}
        </div>
        @endif
        @if(Session::has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ Session::get('error') }}
        </div>
        @endif

        <div class="card">
            <div class="card-body p-30">
                @if(empty($tab_value))
                @php
                $general_blank_tab_active = 'active'
                @endphp
                @else
                @php
                $general_blank_tab_active = ''
                @endphp
                @endif

                @can('settings-general-setting-management')
                @if($tab_value=='general_settings')
                @php
                $general_tab_active = 'active'
                @endphp
                @else
                @php
                $general_tab_active = ''
                @endphp
                @endif
                @endcan

                @can('settings-smtp-mail-setting')
                @if($tab_value=='smtp_settings')
                @php
                $smtp_tab_active = 'active'
                @endphp
                @else
                @php
                $smtp_tab_active = ''
                @endphp
                @endif
                @endcan

                @can('settings-seo-setting')
                @if($tab_value=='seo_settings')
                @php
                $seo_tab_active = 'active'
                @endphp
                @else
                @php
                $seo_tab_active = ''
                @endphp
                @endif
                @endcan

                @can('settings-social-setting')
                @if($tab_value=='social_settings')
                @php
                $social_tab_active = 'active'
                @endphp
                @else
                @php
                $social_tab_active = ''
                @endphp
                @endif
                @endcan

                @can('settings-social-media-share-setting')
                @if($tab_value=='social_share_settings')
                @php
                $social_share_tab_active = 'active'
                @endphp
                @else
                @php
                $social_share_tab_active = ''
                @endphp
                @endif
                @endcan

                @can('settings-other-setting')
                @if($tab_value=='other_settings')
                @php
                $other_tab_active = 'active'
                @endphp
                @else
                @php
                $other_tab_active = ''
                @endphp
                @endif
                @endcan

                @can('settings-security-setting')
                @if($tab_value=='security_settings')
                @php
                $security_tab_active = 'active'
                @endphp
                @else
                @php
                $security_tab_active = ''
                @endphp
                @endif
                @endcan

                @can('settings-cron-setting')
                @if($tab_value=='cron_settings')
                @php
                $cron_tab_active = 'active'
                @endphp
                @else
                @php
                $cron_tab_active = ''
                @endphp
                @endif
                @endcan

                @can('settings-features-setting')
                @if($tab_value=='features_settings')
                @php
                $features_tab_active = 'active'
                @endphp
                @else
                @php
                $features_tab_active = ''
                @endphp
                @endif
                @endcan

                @can('settings-magic-setting')
                @if($tab_value=='magic_settings')
                @php
                $magic_tab_active = 'active'
                @endphp
                @else
                @php
                $magic_tab_active = ''
                @endphp
                @endif
                @endcan

                @can('settings-maintenancenew-setting')
                @if($tab_value=='maintenancenew_settings')
                @php
                $maintenancenew_tab_active = 'active'
                @endphp
                @else
                @php
                $maintenancenew_tab_active = ''
                @endphp
                @endif
                @endcan

                @can('settings-maintenance-setting')
                @if($tab_value=='maintenance')
                @php
                $maintenance_tab_active = 'active'
                @endphp
                @else
                @php
                $maintenance_tab_active = ''
                @endphp
                @endif
                @endcan

                @can('settings-module-setting')
                @if($tab_value=='module')
                @php
                $module_tab_active = 'active';
                $general_tab_active='';
                $general_blank_tab_active='';
                @endphp
                @else
                @php
                $module_tab_active = ''
                @endphp
                @endif
                @endcan


                <div class="notify"></div>
                @section('sidebar')
                <!-- Nav tabs -->
                <ul class="navbar-nav" id="navbar-nav" role="tablist">
                    @can('settings-general-setting-management')
                        <li class="nav-item">
                            <a class="nav-link menu-link {{$general_tab_active}} {{$general_blank_tab_active}}" href="javascript:void(0);" data-id="general" onclick="getAttributes('general')">
                                <i class="ri-settings-3-line"></i><span data-key="t-widgets">{{ trans('shiledcmstheme::template.setting.general') }}</span>  
                            </a>
                        </li>
                    @endcan
                    
                    @can('settings-smtp-mail-setting')
                         @if(auth()->user()->name == 'Super Admin')
                            <li class="nav-item">
                                <a class="nav-link menu-link {{$smtp_tab_active}}" href="javascript:void(0);" data-id="smtp-mail" onclick="getAttributes('smtp-mail')">
                                    <i class="ri-mail-send-line"></i><span data-key="t-widgets">{{ trans('shiledcmstheme::template.setting.SMTPMail') }}</span>
                                </a>
                            </li>
                        @endif
                    @endcan

                    @can('settings-seo-setting')
                        <li class="nav-item">
                            <a class="nav-link menu-link {{$seo_tab_active}}" href="javascript:void(0);" data-id="seo" onclick="getAttributes('seo')">
                                <i class="ri-search-eye-line"></i><span data-key="t-widgets">{{ trans('shiledcmstheme::template.setting.seo') }}</span>
                            </a>
                        </li>
                    @endcan

                    @can('settings-social-setting')
                        <li class="nav-item">
                            <a class="nav-link menu-link {{$social_tab_active}}" href="javascript:void(0);" data-id="social" onclick="getAttributes('social')">
                                <i class="ri-share-circle-line"></i><span data-key="t-widgets">{{ trans('shiledcmstheme::template.setting.social') }}</span>
                            </a>
                        </li>
                    @endcan

                    @can('settings-social-media-share-setting')
                        @if(auth()->user()->name == 'Super Admin')
                            <li class="nav-item">
                                <a class="nav-link menu-link {{$social_share_tab_active}}" href="javascript:void(0);" data-id="socialshare" onclick="getAttributes('socialshare')">
                                    <i class="ri-share-line"></i><span data-key="t-widgets">{{ trans('shiledcmstheme::template.setting.socialMediaShare') }}</span>
                                </a>
                            </li>
                        @endif
                    @endcan
                    
                    @can('settings-magic-setting')
                        <li class="nav-item">
                            <a class="nav-link menu-link {{$magic_tab_active}}" href="javascript:void(0);" data-id="magic" onclick="getAttributes('magic')">
                                <i class="ri-upload-cloud-line"></i><span data-key="t-widgets">Magic Upload</span>
                            </a>
                        </li>
                    @endcan

                    @can('settings-security-setting')
                        <li class="nav-item">
                            <a class="nav-link menu-link {{$security_tab_active}}" href="javascript:void(0);" data-id="security" onclick="getAttributes('security')">
                                <i class="ri-lock-line"></i><span data-key="t-widgets">{{ trans('shiledcmstheme::template.setting.securitySettings') }}</span>
                            </a>
                        </li>
                    @endcan

                    @can('settings-cron-setting')
                        @if(auth()->user()->name == 'Super Admin')
                            <li class="nav-item">
                                <a class="nav-link menu-link {{$cron_tab_active}}" href="javascript:void(0);" data-id="cron" onclick="getAttributes('cron')">
                                    <i class="ri-time-line"></i><span data-key="t-widgets">{{ trans('shiledcmstheme::template.setting.cronSettings') }}</span>
                                </a>
                            </li>
                        @endif
                    @endcan

                    @can('settings-features-setting')
                        @if(auth()->user()->name == 'Super Admin')
                            <li class="nav-item">
                                <a class="nav-link menu-link {{$features_tab_active}}" href="javascript:void(0);" data-id="features" onclick="getAttributes('features')">
                                    <i class="ri-settings-2-line"></i><span data-key="t-widgets">{{ trans('shiledcmstheme::template.setting.featuresSettings') }}</span>
                                </a>
                            </li>
                        @endif
                    @endcan

                    @can('settings-other-setting')
                        <li class="nav-item" id="one_tab">
                            <a class="nav-link menu-link {{$other_tab_active}}" href="javascript:void(0);" data-id="other" onclick="getAttributes('other')">
                                <i class="ri-list-settings-line"></i><span data-key="t-widgets">{{ trans('shiledcmstheme::template.setting.otherSettings') }}</span>
                            </a>
                        </li>
                    @endcan
                    
                    @can('settings-maintenance-setting')
                        <li class="nav-item" id="one_tab">
                            <a class="nav-link menu-link {{$maintenance_tab_active}}" href="javascript:void(0);" data-id="maintenance" onclick="getAttributes('maintenance')">
                               <i class="ri-refresh-line"></i><span data-key="t-widgets">Reset Logs</span>
                            </a>
                        </li>
                    @endcan

                    @can('settings-maintenancenew-setting')
                        @if(auth()->user()->name == 'Super Admin')
                            <li class="nav-item" id="one_tab">
                                <a class="nav-link menu-link {{$maintenancenew_tab_active}}" href="javascript:void(0);" data-id="maintenancenew" onclick="getAttributes('maintenancenew')">
                                    <i class="ri-settings-2-line"></i><span data-key="t-widgets">Maintenance</span>
                                </a>
                            </li>
                        @endif
                    @endcan
                </ul>
                @endsection
                <div class="tab-content">
                    <!-- General -->
                    @can('settings-general-setting-management')
                        <div class="tab-pane {{$general_tab_active}} {{$general_blank_tab_active}}" id="general" role="tabpanel">
                            {!! Form::open(['method' => 'post','id'=>'frmSettings']) !!}
                            {!! Form::hidden('tab', 'general_settings', ['id' => 'general']) !!}
                                <div class="row">
                                    <div class="col-sm-12">
                                        <h4 class="card-title pagetab-title fw-semibold mb-4">General</h4>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="cm-floating">
                                            <label for="site_name" class="form-label">
                                                {{ trans('shiledcmstheme::template.setting.siteName') }} 
                                                <span aria-required="true" class="required"> * </span>
                                            </label>
                                            {!! Form::text('site_name', Config::get('Constant.SITE_NAME') , array('maxlength' => '150', 'class' => 'form-control maxlength-handler', 'id' => 'site_name' , 'placeholder' =>  trans('shiledcmstheme::template.setting.siteName'),'autocomplete'=>'off')) !!}
                                            <span class="help-block">{{ $errors->first('site_name') }}</span>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="image_thumb">
                                            <div class="cm-floating">
                                                <label for="front_logo" class="form-label">
                                                    {{ trans('shiledcmstheme::template.setting.frontLogo') }} 
                                                    <span aria-required="true" class="required"> * </span>
                                                    <span data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{  trans('shiledcmstheme::template.common.imageSize',['height'=>'300','width'=>'600']) }}">
                                                        <i class="ri-information-line text-primary fs-16"></i>
                                                    </span>
                                                </label>
                                                <div class="clearfix"></div>
                                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                                    <div class="fileinput-preview thumbnail front_logo_img" data-trigger="fileinput">
                                                        @if (!empty(Config::get('Constant.FRONT_LOGO_ID')))
                                                            <img src="{{ App\Helpers\resize_image::resize(Config::get('Constant.FRONT_LOGO_ID')) }}"/>
                                                        @else
                                                            <img src="{{ $CDN_PATH.'resources/images/upload_file.gif' }}"/>
                                                        @endif
                                                    </div>
                                                    <div class="input-group">
                                                        <a class="media_manager" onclick="MediaManager.open('front_logo');">
                                                            <!-- <span class="ri-camera-fill"></span> -->
                                                        </a>
                                                    </div>
                                                    {!! Form::hidden('front_logo_id',!empty(Config::get('Constant.FRONT_LOGO_ID'))?Config::get('Constant.FRONT_LOGO_ID'):old('image_upload') , array('class' => 'form-control', 'id' => 'front_logo')) !!}
                                                </div>
                                                <div class="clearfix"></div>
                                                {{-- <div class="mt-1">{{  trans('shiledcmstheme::template.common.imageSize',['height'=>'300','width'=>'600']) }}</div> --}}
                                                <span class="help-block">
                                                    {{ $errors->first('front_logo_id') }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="cm-floating">
                                            @if(!empty($timezone))
                                            <label for="timezone" class="form-label">
                                                {{ trans('shiledcmstheme::template.setting.timezone') }}
                                            </label>
                                            <select class="form-control" id="timezone" name="timezone" data-choices>
                                                @foreach ($timezone as $allzones)
                                                    @if(!empty(Config::get('Constant.DEFAULT_TIME_ZONE')))
                                                        @if($allzones->zone_name == Config::get('Constant.DEFAULT_TIME_ZONE'))
                                                            @php  $selected = 'selected'  @endphp
                                                        @else
                                                            @php  $selected = ''  @endphp
                                                        @endif
                                                    @elseif($allzones->zone_name == 'America/Cayman')
                                                        @php  $selected = 'selected'  @endphp
                                                    @else
                                                        @php  $selected = ''  @endphp
                                                    @endif
                                                    <option {{$selected}} value="{{$allzones->zone_name}}">{{$allzones->zone_name}}</option>
                                                @endforeach
                                            </select>
                                            @endif
                                            <div class="form-check mt-3 mb-0">
                                                @if (Config::get('Constant.DEFAULT_NOTIFCATION_DEPARTMENT_EMAIL') == 'Y')
                                                    @php $checked_section = true; @endphp
                                                @else
                                                    @php $checked_section = null; 
                                                    @endphp
                                                    @php $display_Section = 'none'; @endphp
                                                @endif

                                                {{ Form::checkbox('chrDepartmentEmail',null,$checked_section, array('class' => 'form-check-input', 'id'=>'chrDepartmentEmail')) }}
                                                <label class="form-check-label" for="inlineFormCheck">
                                                    Enable Inquiries Notifcation for Department Email:
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="cm-floating">
                                            <label for="default_contactus_email" class="form-label">
                                            {{  trans('shiledcmstheme::template.setting.contactUsEmail') }}
                                            </label>
                                            {!! Form::text('default_contactus_email',!empty(Config::get('Constant.DEFAULT_CONTACTUS_EMAIL'))?Crypt::decrypt(Config::get('Constant.DEFAULT_CONTACTUS_EMAIL')):'', array('maxlength' => '150','class' => 'form-control', 'id' => 'default_contactus_email' , 'autocomplete'=>'off')) !!}
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="cm-floating">
                                            <label for="default_submit_ticket_email" class="form-label">
                                            {{ trans('Default Submit a Ticket Email') }}
                                            </label>
                                            {!! Form::text('default_submit_ticket_email',!empty(Config::get('Constant.SUBMIT_TICKET'))?Crypt::decrypt(Config::get('Constant.SUBMIT_TICKET')):'' , array('maxlength' => '150','class' => 'form-control', 'id' => 'default_submit_ticket_email' , 'autocomplete'=>'off')) !!}
                                        </div>
                                    </div>

                                    <div class="col-lg-6" style="display:none;">
                                        <div class="cm-floating">
                                            <label for="default_feedback_email" class="form-label">
                                            {{ trans('shiledcmstheme::template.setting.feedbackEmail') }}
                                            </label>
                                            {!! Form::text('default_feedback_email',!empty(Config::get('Constant.DEFAULT_FEEDBACK_EMAIL'))?Crypt::decrypt(Config::get('Constant.DEFAULT_FEEDBACK_EMAIL')):'' , array('maxlength' => '150','class' => 'form-control', 'id' => 'default_feedback_email' , 'autocomplete'=>'off')) !!}
                                        </div>
                                    </div>

                                    <div class="col-lg-6" style="display:none;">
                                        <div class="cm-floating">
                                            <label for="default_newsletter_email" class="form-label">
                                            {{ trans('shiledcmstheme::template.setting.newsletterEmail') }}
                                            </label>
                                            {!! Form::text('default_newsletter_email',!empty(Config::get('Constant.DEFAULT_NEWSLETTER_EMAIL'))?Crypt::decrypt(Config::get('Constant.DEFAULT_NEWSLETTER_EMAIL')):'', array('maxlength' => '500','class' => 'form-control', 'id' => 'default_newsletter_email' , 'autocomplete'=>'off')) !!}
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="cm-floating">
                                            <label for="default_event_email" class="form-label">Default Event Email</label>
                                            {!! Form::text('default_event_email',!empty(Config::get('Constant.DEFAULT_EVENT_EMAIL'))?Crypt::decrypt(Config::get('Constant.DEFAULT_EVENT_EMAIL')):'', array('maxlength' => '500','class' => 'form-control', 'id' => 'default_event_email' , 'autocomplete'=>'off')) !!}
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="cm-floating">
                                            <label for="default_complaint_email" class="form-label">Default Complaint Email</label>
                                            {!! Form::text('default_complaint_email',!empty(Config::get('Constant.COMPLAINT_ADMIN_EMAIL'))?Crypt::decrypt(Config::get('Constant.COMPLAINT_ADMIN_EMAIL')):'', array('maxlength' => '500','class' => 'form-control', 'id' => 'default_complaint_email' , 'autocomplete'=>'off')) !!}
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="cm-floating">
                                            <label for="hr_email" class="form-label">HR Email</label>
                                            {!! Form::text('hr_email',!empty(Config::get('Constant.HR_EMAIL'))?Crypt::decrypt(Config::get('Constant.HR_EMAIL')):'', array('maxlength' => '500','class' => 'form-control', 'id' => 'hr_email' , 'autocomplete'=>'off')) !!}
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="cm-floating">
                                            <label for="online_payment_email" class="form-label">Pay Online Email</label>
                                            {!! Form::text('online_payment_email',!empty(Config::get('Constant.ONLINE_PAYMENT_EMAIL'))?Crypt::decrypt(Config::get('Constant.ONLINE_PAYMENT_EMAIL')):'', array('maxlength' => '500','class' => 'form-control', 'id' => 'online_payment_email' , 'autocomplete'=>'off')) !!}
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="hstack gap-2">
                                            <button type="submit" class="btn btn-primary bg-gradient waves-effect waves-light btn-label">
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0">
                                                        <i class="ri-save-line label-icon align-middle fs-20 me-2"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                       {!! trans('shiledcmstheme::template.common.saveandedit') !!}
                                                    </div>
                                                </div>
                                            </button>
                                        </div>
                                    </div>
                                    <!--end col-->
                                </div>
                                <!--end row-->
                            {!! Form::close() !!}
                        </div>
                    @endcan

                    <!-- SMTP -->
                    @can('settings-smtp-mail-setting')
                        <div class="tab-pane {{$smtp_tab_active}}" id="smtp-mail" role="tabpanel">
                            {!! Form::open(['method' => 'post','id'=>'smtpForm']) !!}
                            <input type="password" style="width: 0;height: 0; visibility: hidden;position:absolute;left:0;top:0;"/>
                            {!! Form::hidden('tab', 'smtp_settings', ['id' => 'smtp']) !!}
                                <div class="row mb-4">
                                    <div class="col-sm-6">
                                        <h4 class="card-title pagetab-title fw-semibold mb-0">SMTP Mail</h4>
                                    </div> 
                                    <div class="col-sm-6">
                                        <div class="form-check pull-right">
                                            @if (Config::get('Constant.USE_SMTP_SETTING') == 'Y')
                                                @php $checked_section = true; @endphp
                                                @php $display_Section = ''; @endphp
                                            @else
                                                @php $checked_section = null; 
                                                @endphp
                                            @endif
                                            {{ Form::checkbox('chrUseSMTP',null,$checked_section, array('id'=>'chrUseSMTP', 'class'=>'form-check-input')) }}
                                            <label class="form-check-label" for="chrUseSMTP">Use SMTP Settings:</label>
                                        </div>
                                    </div>   
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-sm-12">
                                        <div class="cm-floating">
                                            <label for="timezone" class="form-label">
                                                {{  trans('shiledcmstheme::template.setting.mailer') }}
                                            </label>
                                            <select class="form-control" id="mailer" name="mailer" data-choices data-choices-search-false>
                                                @php $smtp_selected = '' @endphp
                                                @php $sent_mail = '' @endphp
                                                @php $mail_trap = '' @endphp
                                                @php $log = '' @endphp
                                                @if (Config::get('Constant.MAILER') == 'smtp')
                                                @php $smtp_selected = 'selected' @endphp
                                                @elseif (Config::get('Constant.MAILER') == 'log')
                                                @php $log = 'selected' @endphp
                                                @else
                                                @php $smtp_selected = '' @endphp
                                                @php $sent_mail = '' @endphp
                                                @php $mail_trap = '' @endphp
                                                @endif
                                                <option {{ $smtp_selected }} value="smtp">{{  trans('shiledcmstheme::template.setting.smtp') }}</option>
                                                <option {{ $log }} value="log">Log</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-6 col-sm-12">
                                        <div class="cm-floating">
                                            <label for="smtp_server" class="form-label">
                                                {{  trans('shiledcmstheme::template.setting.smtpServer') }}
                                                <span aria-required="true" class="required"> * </span>
                                            </label>
                                            {!! Form::text('smtp_server', Config::get('Constant.SMTP_SERVER') , array('maxlength' => '150','class' => 'form-control maxlength-handler', 'id' => 'smtp_server' , 'autocomplete'=>'off')) !!}
                                            <span class="help-block">{{ $errors->first('smtp_server') }}</span>
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-sm-12">
                                        <div class="cm-floating">
                                            <label for="smtp_username" class="form-label">
                                                {{  trans('shiledcmstheme::template.setting.smtpUsername') }}
                                                <span aria-required="true" class="required"> * </span>
                                            </label>
                                            {!! Form::text('smtp_username', Config::get('Constant.SMTP_USERNAME') , array('maxlength' => '150','class' => 'form-control maxlength-handler', 'id' => 'smtp_username' , 'autocomplete'=>'off')) !!}
                                            <span class="help-block">{{ $errors->first('smtp_username') }}</span>
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-sm-12">
                                        <div class="cm-floating">
                                            <label for="smtp_password" class="form-label">
                                                {{  trans('shiledcmstheme::template.setting.smtpPassword') }}
                                                <span aria-required="true" class="required"> * </span>
                                            </label>
                                            <input type="password" maxlength="150" class="form-control maxlength-handler" name="smtp_password" id="smtp_password" value="{{Config::get('Constant.SMTP_PASSWORD') }}" autocomplete="off">
                                            <span class="help-block">{{ $errors->first('smtp_password') }}</span>
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-sm-12">
                                        <div class="cm-floating">
                                            <label for="smtp_encryption" class="form-label">
                                                {{  trans('shiledcmstheme::template.setting.smtpEncryption') }}
                                                <span aria-required="true" class="required"> * </span>
                                            </label>
                                            <select class="form-control" id="smtp_encryption" name="smtp_encryption" data-choices data-choices-search-false>
                                                @php $smtp_encryption_selected = '' @endphp
                                                @php $null_mail = '' @endphp
                                                @php $tls_mail = '' @endphp
                                                @php $ssl_mail = '' @endphp
                                                @if (Config::get('Constant.SMTP_ENCRYPTION') == 'null')
                                                @php $smtp_encryption_selected = 'selected' @endphp
                                                @elseif (Config::get('Constant.SMTP_ENCRYPTION') == 'tls')
                                                @php $tls_mail = 'selected' @endphp
                                                @elseif (Config::get('Constant.SMTP_ENCRYPTION') == 'ssl')
                                                @php $ssl_mail = 'selected' @endphp
                                                @else
                                                @php $smtp_encryption_selected = '' @endphp
                                                @php $tls_mail = '' @endphp
                                                @php $ssl_mail = '' @endphp
                                                @endif
                                                <option {{ $smtp_encryption_selected }} value="null">{{  trans('shiledcmstheme::template.setting.none') }}</option>
                                                <option {{ $tls_mail }} value="tls">{{  trans('shiledcmstheme::template.setting.tls') }}</option>
                                                <option {{ $ssl_mail }} value="ssl">{{  trans('shiledcmstheme::template.setting.ssl') }}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-sm-12 d-flex align-items-center">
                                        <div class="form-group mb-30">
                                            <label for="form_control_1" class="form-label mb-1 mb-md-0 fw-medium fs-14">
                                                {{  trans('shiledcmstheme::template.setting.smtpAuthentication') }}
                                                <span aria-required="true" class="required"> * </span>
                                            </label>
                                            <div class="md-check-inline d-md-inline-block ms-md-3">
                                                <div class="form-check form-check-inline">
                                                    <input type="radio" id="yes" name="smtp_authenticattion" value="Y" class="form-check-input" <?php
                                                    if (Config::get('Constant.SMTP_AUTHENTICATION') == "Y") {
                                                        echo 'checked="checked"';
                                                    }
                                                    ?> >
                                                    <label class="form-check-label" for="yes">
                                                        {{  trans('shiledcmstheme::template.common.yes') }}
                                                    </label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input type="radio" id="no" name="smtp_authenticattion" value="N" class="form-check-input" <?php
                                                    if (Config::get('Constant.SMTP_AUTHENTICATION') == "N") {
                                                        echo 'checked="checked"';
                                                    }
                                                    ?> >
                                                    <label class="form-check-label" for="no">{{  trans('shiledcmstheme::template.common.no') }}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-sm-12">
                                        <div class="cm-floating">
                                            <label for="smtp_port" class="form-label">
                                                {{  trans('shiledcmstheme::template.setting.smtpPort') }}
                                                <span aria-required="true" class="required"> * </span>
                                            </label>
                                            {!! Form::text('smtp_port',Config::get('Constant.SMTP_PORT'), array('maxlength' => '150', 'class' => 'form-control maxlength-handler', 'id' => 'smtp_port_no')) !!}
                                            <span class="help-block">{{ $errors->first('smtp_port') }}</span>
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-sm-12">
                                        <div class="cm-floating">
                                            <label for="smtp_sender_name" class="form-label">
                                                {{  trans('shiledcmstheme::template.setting.senderName') }}
                                                <span aria-required="true" class="required"> * </span>
                                            </label>
                                            {!! Form::text('smtp_sender_name',Config::get('Constant.SMTP_SENDER_NAME'), array('maxlength' => '150', 'class' => 'form-control maxlength-handler', 'id' => 'smtp_sender_name','autocomplete'=>'off')) !!}
                                            <span class="help-block">{{ $errors->first('smtp_sender_name') }}</span>
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-sm-12">
                                        <div class="cm-floating">
                                            <label for="smtp_sender_id" class="form-label">
                                                {{  trans('shiledcmstheme::template.setting.senderEmail') }}
                                                <span aria-required="true" class="required"> * </span>
                                            </label>
                                            {!! Form::text('smtp_sender_id',!empty(Config::get('Constant.DEFAULT_EMAIL'))?Crypt::decrypt(Config::get('Constant.DEFAULT_EMAIL')):'', array('maxlength' => '150', 'class' => 'form-control', 'id' => 'smtp_sender_id','autocomplete'=>'off')) !!}
                                            <span class="help-block">{{ $errors->first('smtp_sender_id') }}</span>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="hstack gap-2">
                                            <button type="submit" class="btn btn-primary bg-gradient waves-effect waves-light btn-label">
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0">
                                                        <i class="ri-save-line label-icon align-middle fs-20 me-2"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                       {!! trans('shiledcmstheme::template.common.saveandedit') !!}
                                                    </div>
                                                </div>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            {!! Form::close() !!}
                        </div>
                    @endcan

                    <!-- SEO -->
                    @can('settings-seo-setting')
                        <div class="tab-pane {{$seo_tab_active}}" id="seo" role="tabpanel">
                            {!! Form::open(['method' => 'post','id' => 'frmSeo','enctype'=>'multipart/form-data']) !!}
                            {!! Form::hidden('tab', 'seo_settings', ['id' => 'seo']) !!}
                                <div class="row">
                                    <div class="col-sm-12">
                                        <h4 class="card-title pagetab-title fw-semibold mb-4">SEO</h4>
                                    </div>    
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-sm-12">
                                        <div class="cm-floating">
                                            <label class="form-label" for="google_analytic_code">{{  trans('shiledcmstheme::template.setting.googleAnalytic') }} </label>
                                            {!! Form::textarea('google_analytic_code' , Config::get('Constant.GOOGLE_ANALYTIC_CODE'), array('class' => 'form-control', 'id' => 'google_analytic_code','rows' => '4')) !!}
                                            <span class="help-block">
                                                {{ $errors->first('google_analytic_code') }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-sm-12">
                                        <div class="cm-floating">
                                            <label class="form-label" for="google_tag_manager_for_body">{{  trans('shiledcmstheme::template.setting.googleTagManager') }}</label>
                                            {!! Form::textarea('google_tag_manager_for_body' , Config::get('Constant.GOOGLE_TAG_MANAGER_FOR_BODY'), array('class' => 'form-control', 'id' => 'google_tag_manager_for_body', 'rows' => '4')) !!}
                                            <span class="help-block">
                                                {{ $errors->first('google_tag_manager_for_body') }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="cm-floating">
                                            <label class="form-label" for="meta_title">{{  trans('shiledcmstheme::template.common.metatitle') }} <span aria-required="true" class="required"> * </span></label>
                                            {!! Form::text('meta_title' , Config::get('Constant.DEFAULT_META_TITLE'), array('maxlength' => '150','class' => 'form-control maxlength-handler', 'id' => 'meta_title', 'autocomplete'=>"off")) !!}
                                            <span class="help-block">
                                                {{ $errors->first('meta_title') }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="cm-floating">
                                            <label class="form-label" for="form_control_1">{{  trans('shiledcmstheme::template.common.metadescription') }} <span aria-required="true" class="required"> * </span></label>
                                            {!! Form::textarea('meta_description' , Config::get('Constant.DEFAULT_META_DESCRIPTION'), array('class' => 'form-control', 'id' => 'meta_description', 'rows' => '4')) !!}
                                            <span class="help-block">
                                                {{ $errors->first('meta_description') }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="mb-30 pb-1">
                                            <label class="form_title" for="BingFile">Upload Bing File
                                                <span data-bs-toggle="tooltip" data-bs-placement="bottom" title="Recommended File type (.xml)">
                                                    <i class="ri-information-line text-primary fs-16"></i>
                                                </span>
                                            </label>
                                            {!! Form::file('xml_file' , array('class' => 'form-control', 'id' => 'bingfile','accept'=>"text/xml")) !!}
                                            @php
                                            $BingfileName = Config::get('Constant.BING_FILE_PATH');
                                            @endphp
                                            <div class="clearfix"></div>
                                            {{-- <span class="mt-1 d-block">Recommended File type (.xml)</span> --}}
                                            @if($BingfileName != "" || $BingfileName != null)
                                                <span>File Name:{{ $BingfileName }}</span>
                                            @endif
                                            <span class="help-block">
                                                {{ $errors->first('xml_file') }}
                                            </span>
                                            <div id="xml_file_error"></div>
                                        </div>
                                    </div>

                                    <div class="col-lg-12 hidden d-none">
                                        <div class="mb-3">
                                            <div class="form-group form-md-line-input hidden">
                                                <label class="form_title" for="generate_sitemap">Sitemap:&nbsp;</label>
                                                <a target="_blank" href="{{url('generateSitemap')}}" class="btn default">Click to generate sitemap</a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="hstack gap-2">
                                            <button type="submit" class="btn btn-primary bg-gradient waves-effect waves-light btn-label">
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0">
                                                        <i class="ri-save-line label-icon align-middle fs-20 me-2"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                       {!!  trans('shiledcmstheme::template.common.saveandedit') !!}
                                                    </div>
                                                </div>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            {!! Form::close() !!}
                        </div>
                    @endcan

                    <!-- Social -->
                    @can('settings-social-setting')
                        <div class="tab-pane {{$social_tab_active}}" id="social" role="tabpanel">
                            {!! Form::open(['method' => 'post','id' => 'frmSocial']) !!}
                            {!! Form::hidden('tab', 'social_settings', ['id' => 'social']) !!}
                                <div class="row">
                                    <div class="col-sm-12">
                                        <h4 class="card-title pagetab-title fw-semibold mb-4">Social</h4>
                                    </div>    
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-sm-12">
                                        <div class="cm-floating">
                                            <label class="form-label" for="fb_link">
                                                {{  trans('shiledcmstheme::template.setting.facebookLink') }}
                                                <a href="javascript:;" class="config" data-bs-placement="bottom" data-bs-toggle="tooltip" title="Please add your Facebook page link (eg: https://www.facebook.com/your_page))">
                                                    <i class="ri-information-line fs-16 text-primary"></i></a>
                                            </label>                        
                                            <div class="input-group">
                                                {{-- <div class="input-group-text"><i class="ri-facebook-fill"></i></div> --}}
                                                {!! Form::text('fb_link' , Config::get('Constant.SOCIAL_FB_LINK'), array('class' => 'form-control', 'id' => 'fb_link', 'autocomplete'=>"off")) !!}
                                            </div>
                                            <span class="help-block">
                                                {{ $errors->first('fb_link') }}
                                            </span>
                                        </div>
                                    </div><!-- end col -->

                                    <div class="col-lg-6 col-sm-12">
                                        <div class="cm-floating">
                                            <label class="form-label" for="twitter_link">
                                                {{  trans('shiledcmstheme::template.setting.twitterLink') }} 
                                                <a href="javascript:;" class="config" data-bs-placement="bottom" data-bs-toggle="tooltip" title="Please add your Twitter page link (eg: https://www.twitter.com/your_page))">
                                                    <i class="ri-information-line fs-16 text-primary"></i>
                                                </a>
                                            </label> 
                                            <div class="input-group">
                                                {{-- <div class="input-group-text"><i class="ri-twitter-fill"></i></div> --}}
                                                {!! Form::text('twitter_link' , Config::get('Constant.SOCIAL_TWITTER_LINK'), array('class' => 'form-control', 'id' => 'twitter_link', 'autocomplete'=>"off")) !!}
                                            </div>
                                            <span class="help-block">
                                                {{ $errors->first('twitter_link') }}
                                            </span>
                                        </div>
                                    </div><!-- end col -->

                                    <div class="col-lg-6 col-sm-12">
                                        <div class="cm-floating">
                                            <label class="form-label" for="instagram_link">
                                                {{  trans('shiledcmstheme::template.setting.instagramLink') }}
                                                 <a href="javascript:;" class="config" data-bs-placement="bottom" data-bs-toggle="tooltip" title="Please add your Instagram page link (eg: https://www.instagram.com/your_page))">
                                                    <i class="ri-information-line fs-16 text-primary"></i>
                                                </a>
                                            </label> 
                                            <div class="input-group">
                                                {{-- <div class="input-group-text"><i class="ri-instagram-fill"></i></div> --}}
                                                {!! Form::text('instagram_link' , Config::get('Constant.SOCIAL_INSTAGRAM_LINK'), array('class' => 'form-control', 'id' => 'instagram_link', 'autocomplete'=>"off")) !!}
                                            </div>
                                            <span class="help-block">
                                                {{ $errors->first('instagram_link') }}
                                            </span>
                                        </div>
                                    </div><!-- end col -->
                                    
                                    <div class="col-lg-6 col-sm-12">
                                        <div class="cm-floating">
                                            <label class="form-label" for="linkedin_link">
                                                {{  trans('shiledcmstheme::template.setting.linkedinlink') }} 
                                                <a href="javascript:;" class="config" data-bs-placement="bottom" data-bs-toggle="tooltip" title="Please add your LinkedIn page link (eg: https://www.linkedin.com/your_page))">
                                                    <i class="ri-information-line fs-16 text-primary"></i>
                                                </a>
                                            </label>
                                            <div class="input-group">
                                                {{-- <div class="input-group-text"><i class="ri-linkedin-fill"></i></div> --}}
                                                {!! Form::text('linkedin_link' , Config::get('Constant.SOCIAL_LINKEDIN_LINK'), array('class' => 'form-control', 'id' => 'linkedin_link', 'autocomplete'=>"off")) !!}
                                            </div>
                                            <span class="help-block">
                                                {{ $errors->first('linkedin_link') }}
                                            </span>
                                        </div>
                                    </div><!-- end col -->

                                    <div class="col-lg-6 col-sm-12">
                                        <div class="cm-floating">
                                            <label class="form-label" for="trip_advisor_link">
                                                {{  trans('shiledcmstheme::template.setting.tripadvisorlink') }} 
                                                <a href="javascript:;" class="config" data-bs-placement="bottom" data-bs-toggle="tooltip" title="Please add your Trip Advisor page link (eg: https://www.tripadvisor.com/your_page))">
                                                    <i class="ri-information-line fs-16 text-primary"></i>
                                                </a>
                                            </label> 
                                            <div class="input-group">
                                                {{-- <div class="input-group-text"><i class="lab la-tripadvisor"></i></div> --}}
                                                {!! Form::text('trip_advisor_link' , Config::get('Constant.SOCIAL_TRIP_ADVISOR_LINK'), array('class' => 'form-control', 'id' => 'trip_advisor_link', 'autocomplete'=>"off")) !!}
                                            </div>
                                            <span class="help-block">
                                                {{ $errors->first('trip_advisor_link') }}
                                            </span>
                                        </div>
                                    </div><!-- end col -->

                                    <div class="col-lg-6 col-sm-12">
                                        <div class="cm-floating">
                                            <label class="form-label" for="youtube_link">
                                                {{  trans('shiledcmstheme::template.setting.youtubeLink') }} 
                                                <a href="javascript:;" class="config" data-bs-placement="bottom" data-bs-toggle="tooltip" title="Please add your Youtube page link (eg: https://www.youtube.com/your_page))">
                                                    <i class="ri-information-line fs-16 text-primary"></i>
                                                </a>
                                            </label>
                                            <div class="input-group">
                                                {{-- <div class="input-group-text"><i class="ri-youtube-fill"></i></div> --}}
                                                {!! Form::text('youtube_link' , Config::get('Constant.'), array('class' => 'form-control', 'id' => 'youtube_link', 'autocomplete'=>"off")) !!}
                                            </div>
                                            <span class="help-block">
                                                {{ $errors->first('youtube_link') }}
                                            </span>
                                        </div>
                                    </div><!-- end col -->

                                    <div class="col-lg-12">
                                        <div class="hstack gap-2">
                                            <button type="submit" class="btn btn-primary bg-gradient waves-effect waves-light btn-label">
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0">
                                                        <i class="ri-save-line label-icon align-middle fs-20 me-2"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                       {!!  trans('shiledcmstheme::template.common.saveandedit') !!}
                                                    </div>
                                                </div>
                                            </button>
                                        </div>
                                    </div>
                                </div> <!--end row-->
                            {!! Form::close() !!}
                        </div>
                    @endcan

                    <!-- Social Share -->
                    @can('settings-social-media-share-setting')
                        <div class="tab-pane {{$social_share_tab_active}}" id="socialshare" role="tabpanel">
                            {!! Form::open(['method' => 'post','id' => 'frmSocialShare']) !!}
                            {!! Form::hidden('tab', 'social_share_settings', ['id' => 'socialshare']) !!}
                                <div class="row">
                                    <div class="col-sm-12">
                                        <h4 class="card-title pagetab-title fw-semibold mb-4">Social Media Share</h4>
                                    </div>
                                </div>
                                <div class="row">
                                    <!-- Facebook -->
                                    <div class="col-lg-12">
                                        <h5 class="fs-15 mb-3">{{  trans('shiledcmstheme::template.setting.facebookShare') }}</h5>
                                    </div>
                                    <div class="col-lg-6 col-sm-12">
                                        <div class="cm-floating">
                                            <label class="form-label" for="fb_id">
                                                {{  trans('shiledcmstheme::template.setting.facebookPageID') }}
                                                <span aria-required="true" class="required"> * </span>
                                            </label>
                                            <div class="input-group">
                                                {{-- <div class="input-group-text"><i class="ri-facebook-fill"></i></div> --}}
                                                {!! Form::text('fb_id' , Config::get('Constant.SOCIAL_SHARE_FB_ID'), array('class' => 'form-control', 'id' => 'fb_id', 'autocomplete'=>"off", 'onkeypress' => "return isNumberKey(event)")) !!}
                                            </div>
                                            <span class="help-block">
                                                {{ $errors->first('fb_id') }}
                                            </span>
                                        </div>
                                    </div><!-- end col -->

                                    <div class="col-lg-6 col-sm-12">
                                        <div class="cm-floating">
                                            <label class="form-label" for="fb_api">
                                                {{  trans('shiledcmstheme::template.setting.facebookApiKey') }}
                                                <span aria-required="true" class="required"> * </span>
                                            </label>
                                            <div class="input-group">
                                                {{-- <div class="input-group-text"><i class="ri-facebook-fill"></i></div> --}}
                                                {!! Form::text('fb_api' , Config::get('Constant.SOCIAL_SHARE_FB_API_KEY'), array('class' => 'form-control', 'id' => 'fb_api', 'autocomplete'=>"off")) !!}
                                            </div>
                                            <span class="help-block">
                                                {{ $errors->first('fb_api') }}
                                            </span>
                                        </div>
                                    </div><!-- end col -->
                                    
                                    <div class="col-lg-6 col-sm-12">
                                        <div class="cm-floating">
                                            <label class="form-label" for="fb_secret_key">
                                                {{  trans('shiledcmstheme::template.setting.facebookSecretKey') }}
                                                <span aria-required="true" class="required"> * </span>
                                            </label>
                                            <div class="input-group">
                                                {{-- <div class="input-group-text"><i class="ri-facebook-fill"></i></div> --}}
                                                {!! Form::text('fb_secret_key' , Config::get('Constant.SOCIAL_SHARE_FB_SECRET_KEY'), array('class' => 'form-control', 'id' => 'fb_secret_key', 'autocomplete'=>"off")) !!}
                                            </div>
                                            <span class="help-block">
                                                {{ $errors->first('fb_secret_key') }}
                                            </span>
                                        </div>
                                    </div><!-- end col -->

                                    <div class="col-lg-6 col-sm-12">
                                        <div class="cm-floating">
                                            <label class="form-label" for="fb_access_token">
                                                {{  trans('shiledcmstheme::template.setting.facebookAccessToken') }}
                                                <span aria-required="true" class="required"> * </span>
                                            </label>
                                            <div class="input-group">
                                                {{-- <div class="input-group-text"><i class="ri-facebook-fill"></i></div> --}}
                                                {!! Form::text('fb_access_token' , Config::get('Constant.SOCIAL_SHARE_FB_ACCESS_TOKEN'), array('class' => 'form-control', 'id' => 'fb_access_token', 'autocomplete'=>"off")) !!}
                                            </div>
                                            <span class="help-block">
                                                {{ $errors->first('fb_access_token') }}
                                            </span>
                                        </div>
                                    </div><!-- end col -->

                                    <!-- Twitter -->
                                    <div class="col-lg-12">
                                        <h5 class="fs-15 mb-3">{{  trans('shiledcmstheme::template.setting.twitterShare') }}</h5>
                                    </div>
                                    <div class="col-lg-6 col-sm-12">
                                        <div class="cm-floating">
                                            <label class="form-label" for="twitter_api">
                                                {{  trans('shiledcmstheme::template.setting.twitterApiKey') }}
                                                <span aria-required="true" class="required"> * </span>
                                            </label>
                                            <div class="input-group">
                                                {{-- <div class="input-group-text"><i class="ri-twitter-fill"></i></div> --}}
                                                {!! Form::text('twitter_api' , Config::get('Constant.SOCIAL_SHARE_TWITTER_API_KEY'), array('class' => 'form-control', 'id' => 'twitter_api', 'autocomplete'=>"off")) !!}
                                            </div>
                                            <span class="help-block">
                                                {{ $errors->first('twitter_api') }}
                                            </span>
                                        </div>
                                    </div><!-- end col -->

                                    <div class="col-lg-6 col-sm-12">
                                        <div class="cm-floating">
                                            <label class="form-label" for="twitter_secret_key">
                                                {{  trans('shiledcmstheme::template.setting.twitterSecretKey') }}
                                                <span aria-required="true" class="required"> * </span>
                                            </label>
                                            <div class="input-group">
                                                {{-- <div class="input-group-text"><i class="ri-twitter-fill"></i></div> --}}
                                                {!! Form::text('twitter_secret_key' , Config::get('Constant.SOCIAL_SHARE_TWITTER_SECRET_KEY'), array('class' => 'form-control', 'id' => 'twitter_secret_key', 'autocomplete'=>"off")) !!}
                                            </div>
                                            <span class="help-block">
                                                {{ $errors->first('twitter_secret_key') }}
                                            </span>
                                        </div>
                                    </div><!-- end col -->
                                    
                                    <div class="col-lg-6 col-sm-12">
                                        <div class="cm-floating">
                                            <label class="form-label" for="twitter_access_token">
                                                {{  trans('shiledcmstheme::template.setting.twitterAccessToken') }}
                                                <span aria-required="true" class="required"> * </span>
                                            </label>
                                            <div class="input-group">
                                                {{-- <div class="input-group-text"><i class="ri-twitter-fill"></i></div> --}}
                                                {!! Form::text('twitter_access_token' , Config::get('Constant.SOCIAL_SHARE_TWITTER_ACCESS_TOKEN'), array('class' => 'form-control', 'id' => 'twitter_access_token', 'autocomplete'=>"off")) !!}
                                            </div>
                                            <span class="help-block">
                                                {{ $errors->first('twitter_access_token') }}
                                            </span>
                                        </div>
                                    </div><!-- end col -->
                                    
                                    <div class="col-lg-6 col-sm-12">
                                        <div class="cm-floating">
                                            <label class="form-label" for="twitter_access_token_key">
                                                {{  trans('shiledcmstheme::template.setting.twitterAccessTokenSceretKey') }}
                                                <span aria-required="true" class="required"> * </span>
                                            </label>
                                            <div class="input-group">
                                                {{-- <div class="input-group-text"><i class="ri-twitter-fill"></i></div> --}}
                                                {!! Form::text('twitter_access_token_key' , Config::get('Constant.SOCIAL_SHARE_TWITTER_ACCESS_SECRET_KEY'), array('class' => 'form-control', 'id' => 'twitter_access_token_key', 'autocomplete'=>"off")) !!}
                                            </div>
                                            <span class="help-block">
                                                {{ $errors->first('twitter_access_token_key') }}
                                            </span>
                                        </div>
                                    </div><!-- end col -->
                                    
                                    <!-- LinkedIn -->
                                    <div class="col-lg-12">
                                        <h5 class="fs-15 mb-3">{{  trans('shiledcmstheme::template.setting.linkedinShare') }}</h5>
                                    </div>
                                    <div class="col-lg-6 col-sm-12">
                                        <div class="cm-floating">
                                            <label class="form-label" for="linkedin_api">
                                                {{  trans('shiledcmstheme::template.setting.linkedinApiKey') }}
                                                <span aria-required="true" class="required"> * </span>
                                            </label>
                                            <div class="input-group">
                                                {{-- <div class="input-group-text"><i class="ri-linkedin-fill"></i></div> --}}
                                                {!! Form::text('linkedin_api' , Config::get('Constant.SOCIAL_SHARE_LINKEDIN_API_KEY'), array('class' => 'form-control', 'id' => 'linkedin_api', 'autocomplete'=>"off")) !!}
                                            </div>
                                            <span class="help-block">
                                                {{ $errors->first('linkedin_api') }}
                                            </span>
                                        </div>
                                    </div><!-- end col -->

                                    <div class="col-lg-6 col-sm-12">
                                        <div class="cm-floating">
                                            <label class="form-label" for="linkedin_secret_key">
                                                {{  trans('shiledcmstheme::template.setting.linkedinSecretKey') }}
                                                <span aria-required="true" class="required"> * </span>
                                            </label>
                                            <div class="input-group">
                                                {{-- <div class="input-group-text"><i class="ri-linkedin-fill"></i></div> --}}
                                                {!! Form::text('linkedin_secret_key' , Config::get('Constant.SOCIAL_SHARE_LINKEDIN_SECRET_KEY'), array('class' => 'form-control', 'id' => 'linkedin_secret_key', 'autocomplete'=>"off")) !!}
                                            </div>
                                            <span class="help-block">
                                                {{ $errors->first('linkedin_secret_key') }}
                                            </span>
                                        </div>
                                    </div><!-- end col -->
                                    
                                    <div class="col-lg-6 col-sm-12">
                                        <div class="cm-floating">
                                            <label class="form-label" for="linkedin_access_token">
                                                {{  trans('shiledcmstheme::template.setting.linkedinAccessToken') }}
                                                <span aria-required="true" class="required"> * </span>
                                            </label>
                                            <div class="input-group">
                                                {{-- <div class="input-group-text"><i class="ri-linkedin-fill"></i></div> --}}
                                                {!! Form::text('linkedin_access_token' , Config::get('Constant.SOCIAL_SHARE_LINKEDIN_ACCESS_TOKEN'), array('class' => 'form-control', 'id' => 'linkedin_access_token', 'autocomplete'=>"off")) !!}
                                            </div>
                                            <span class="help-block">
                                                {{ $errors->first('linkedin_access_token') }}
                                            </span>
                                        </div>
                                    </div><!-- end col -->
                                    
                                    <div class="col-lg-6 col-sm-12">
                                        <div class="cm-floating">
                                            <label class="form-label" for="linkedin_access_token_key">
                                                {{  trans('shiledcmstheme::template.setting.linkedinAccessTokenSceretKey') }}
                                                <span aria-required="true" class="required"> * </span>
                                            </label>
                                            <div class="input-group">
                                                {{-- <div class="input-group-text"><i class="ri-linkedin-fill"></i></div> --}}
                                                {!! Form::text('linkedin_access_token_key' , Config::get('Constant.SOCIAL_SHARE_LINKEDIN_ACCESS_SECRET_KEY'), array('class' => 'form-control', 'id' => 'linkedin_access_token_key', 'autocomplete'=>"off")) !!}
                                            </div>
                                            <span class="help-block">
                                                {{ $errors->first('linkedin_access_token_key') }}
                                            </span>
                                        </div>
                                    </div><!-- end col -->

                                    <div class="col-lg-12">
                                        <div class="hstack gap-2">
                                            <button type="submit" class="btn btn-primary bg-gradient waves-effect waves-light btn-label">
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0">
                                                        <i class="ri-save-line label-icon align-middle fs-20 me-2"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                       {!!  trans('shiledcmstheme::template.common.saveandedit') !!}
                                                    </div>
                                                </div>
                                            </button>
                                        </div>
                                    </div>
                                </div> <!--end row-->
                            {!! Form::close() !!}
                        </div>
                    @endcan

                    <!-- Social -->
                    @can('settings-other-setting')
                        <div class="tab-pane {{$other_tab_active}}" id="other" role="tabpanel">
                            {!! Form::open(['method' => 'post','id' => 'otherSettings']) !!}
                            {!! Form::hidden('tab', 'other_settings', ['id' => 'other']) !!}
                                <div class="row">
                                    <div class="col-sm-12">
                                        <h4 class="card-title pagetab-title fw-semibold mb-4">Other Settings</h4>
                                    </div>    
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-sm-12">
                                        <div class="cm-floating">
                                            <label class="form-label" for="fb_link">
                                                {{  trans('shiledcmstheme::template.common.defaultDateFormat') }} (d/m/Y)
                                            </label> 
                                            <select class="form-select" id="default_date_format" name="default_date_format" data-choices data-choices-search-false>
                                                <option value="d/m/Y" <?php
                                                if (Config::get('Constant.DEFAULT_DATE_FORMAT') == "d/m/Y") {
                                                    echo 'selected="selected"';
                                                }
                                                ?> >d/m/Y (Eg: {{ Carbon\Carbon::today()->format('d/m/Y') }})  </option>
                                                <option value="m/d/Y" <?php
                                                if (Config::get('Constant.DEFAULT_DATE_FORMAT') == "m/d/Y") {
                                                    echo 'selected="selected"';
                                                }
                                                ?> >m/d/Y (Eg: {{ Carbon\Carbon::today()->format('m/d/Y') }})  </option>
                                                <option value="Y/m/d" <?php
                                                if (Config::get('Constant.DEFAULT_DATE_FORMAT') == "Y/m/d") {
                                                    echo 'selected="selected"';
                                                }
                                                ?> >Y/m/d (Eg: {{ Carbon\Carbon::today()->format('Y/m/d') }})  </option>
                                                <option value="Y/d/m" <?php
                                                if (Config::get('Constant.DEFAULT_DATE_FORMAT') == "Y/d/m") {
                                                    echo 'selected="selected"';
                                                }
                                                ?> >Y/d/m (Eg: {{ Carbon\Carbon::today()->format('Y/d/m') }})  </option>
                                                <option value="M/d/Y" <?php
                                                if (Config::get('Constant.DEFAULT_DATE_FORMAT') == "M/d/Y") {
                                                    echo 'selected="selected"';
                                                }
                                                ?> >M/d/Y (Eg: {{ Carbon\Carbon::today()->format('M/d/Y') }})  </option>
                                                <option value="M d Y" <?php
                                                if (Config::get('Constant.DEFAULT_DATE_FORMAT') == "M d Y") {
                                                    echo 'selected="selected"';
                                                }
                                                ?> >M d Y (Eg: {{ Carbon\Carbon::today()->format('M d Y') }})  </option>
                                                <option value="M j, Y" <?php
                                                if (Config::get('Constant.DEFAULT_DATE_FORMAT') == "M j, Y") {
                                                    echo 'selected="selected"';
                                                }
                                                ?> >M j, Y (Eg: {{ Carbon\Carbon::today()->format('M j, Y') }})  </option>
                                            </select>
                                        </div>
                                    </div><!-- end col -->

                                    <div class="col-lg-6 col-sm-12">
                                        <div class="cm-floating">
                                            <label class="form-label" for="twitter_link">
                                                {{  trans('shiledcmstheme::template.common.defaultTimeFormat') }}
                                            </label> 
                                            <select class="form-select" id="time_format" name="time_format" data-choices data-choices-search-false>
                                                <option value="h:i A" <?php
                                                if (Config::get('Constant.DEFAULT_TIME_FORMAT') == "h:i A") {
                                                    echo 'selected="selected"';
                                                }
                                                ?> >12 {{  trans('shiledcmstheme::template.common.hours') }}</option>
                                                <option value="H:i" <?php
                                                if (Config::get('Constant.DEFAULT_TIME_FORMAT') == "H:i") {
                                                    echo 'selected="selected"';
                                                }
                                                ?> >24 {{  trans('shiledcmstheme::template.common.hours') }}</option>
                                            </select>
                                        </div>
                                    </div><!-- end col -->

                                    <div class="col-lg-6 col-sm-12">
                                        <div class="cm-floating">
                                            <label class="form-label" for="google_map_key">
                                                {{  trans('shiledcmstheme::template.setting.googleMapKey') }}
                                            </label> 
                                            {!! Form::text('google_map_key' , Config::get('Constant.GOOGLE_MAP_KEY'), array('class' => 'form-control', 'id' => 'google_map_key', 'autocomplete'=>"off")) !!}
                                            <span class="help-block">
                                                {{ $errors->first('google_map_key') }}
                                            </span>
                                        </div>
                                    </div><!-- end col -->
                                    
                                    <div class="col-lg-6 col-sm-12">
                                        <div class="cm-floating">
                                            <label class="form-label" for="google_capcha_key">
                                                {{  trans('shiledcmstheme::template.setting.googleCapchaKey') }}
                                            </label> 
                                            {!! Form::text('google_capcha_key' ,!empty(Config::get('Constant.GOOGLE_CAPCHA_KEY'))?Config::get('Constant.GOOGLE_CAPCHA_KEY'):'', array('class' => 'form-control', 'id' => 'google_capcha_key', 'autocomplete'=>"off")) !!}
                                            <span class="help-block">
                                                {{ $errors->first('google_capcha_key') }}
                                            </span>
                                        </div>
                                    </div><!-- end col -->
                                    
                                    <div class="col-lg-6 col-sm-12">
                                        <div class="cm-floating">
                                            <label class="form-label" for="google_capcha_secret">
                                                {{  trans('shiledcmstheme::template.setting.googleCapchaSecret') }}
                                            </label> 
                                            {!! Form::text('google_capcha_secret' ,!empty(Config::get('Constant.GOOGLE_CAPCHA_SECRET'))?Config::get('Constant.google_capcha_secret'):'', array('class' => 'form-control', 'id' => 'google_capcha_secret', 'autocomplete'=>"off")) !!}
                                            <span class="help-block">
                                                {{ $errors->first('google_capcha_secret') }}
                                            </span>
                                        </div>
                                    </div><!-- end col -->
                                    
                                    @if (Config::get('Constant.DEFAULT_AUTHENTICATION') == 'Y')
                                    <div class="col-lg-6 col-sm-12">
                                        <div class="cm-floating">
                                            <label class="form-label" for="Authentication_Time">Authentication Time (Minute)</label> 
                                            {!! Form::text('Authentication_Time' ,!empty(Config::get('Constant.DEFAULT_Authentication_TIME'))?Config::get('Constant.DEFAULT_Authentication_TIME'):'', array('class' => 'form-control', 'id' => 'Authentication_Time', 'autocomplete'=>"off")) !!}
                                            <span class="help-block">
                                                {{ $errors->first('Authentication_Time') }}
                                            </span>
                                        </div>
                                    </div><!-- end col -->
                                    @endif

                                    <div class="col-lg-6 col-sm-12 d-flex align-items-center">
                                        <div class="mb-30">
                                            <label class="form-label mb-1 mb-md-0 fw-medium fs-14 " for="linkedin_link">
                                                {{  trans('shiledcmstheme::template.setting.filterBadWords') }}:
                                            </label>
                                            <div class="md-check-inline d-md-inline-block ms-md-3">
                                                <div class="form-check form-check-inline">
                                                    @if ((!empty(Config::get('Constant.BAD_WORDS')) && Config::get('Constant.BAD_WORDS') == 'Y') || (null == old('bad_words') || old('bad_words') == 'Y'))
                                                    @php  $checked_yes = 'checked'  @endphp
                                                    @else
                                                    @php  $checked_yes = ''  @endphp
                                                    @endif
                                                    <input type="radio" {{ $checked_yes }} value="Y" id="badWordsYes" name="bad_words" class="form-check-input">
                                                    <label class="form-check-label" for="yes">
                                                        {{  trans('shiledcmstheme::template.common.yes') }}
                                                    </label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    @if (Config::get('Constant.BAD_WORDS') == 'N' || (!empty(Config::get('Constant.BAD_WORDS')) && Config::get('Constant.BAD_WORDS') == 'N'))
                                                    @php  $checked_yes = 'checked'  @endphp
                                                    @else
                                                    @php  $checked_yes = ''  @endphp
                                                    @endif
                                                    <input type="radio" {{ $checked_yes }} value="N" id="badWordsNo" name="bad_words" class="form-check-input">
                                                    <label class="form-check-label" for="yes">
                                                        {{  trans('shiledcmstheme::template.common.no') }}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- end col -->

                                    <div class="col-lg-12">
                                        <div class="hstack gap-2">
                                            <button type="submit" class="btn btn-primary bg-gradient waves-effect waves-light btn-label">
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0">
                                                        <i class="ri-save-line label-icon align-middle fs-20 me-2"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                       {!!  trans('shiledcmstheme::template.common.saveandedit') !!}
                                                    </div>
                                                </div>
                                            </button>
                                        </div>
                                    </div>
                                </div> <!--end row-->
                            {!! Form::close() !!}
                        </div>
                    @endcan

                    <!-- Security -->
                    @can('settings-security-setting')
                        <div class="tab-pane {{$security_tab_active}}" id="security" role="tabpanel">
                            {!! Form::open(['method' => 'post','id' => 'securitySettings']) !!}
                            {!! Form::hidden('tab', 'security_settings', ['id' => 'security']) !!}
                                <div class="row">
                                    <div class="col-sm-12">
                                        <h4 class="card-title pagetab-title fw-semibold mb-4">Security Settings</h4>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4 col-sm-12">
                                        <div class="cm-floating">
                                            <label class="form-label" for="max_login_attempts">
                                                {{  trans('shiledcmstheme::template.setting.maxloginattempts') }} <span aria-required="true" class="required"> * </span>
                                            </label>
                                            {!! Form::text('max_login_attempts' ,!empty(Config::get('Constant.MAX_LOGIN_ATTEMPTS'))?Config::get('Constant.MAX_LOGIN_ATTEMPTS'):'', array('class' => 'form-control', 'id' => 'max_login_attempts', 'autocomplete'=>"off", 'maxlength'=>"3", 'onkeypress'=>"javascript: return KeycheckOnlyAmount(event);",'onpaste'=>'return false')) !!}
                                            <span class="help-block">
                                                {{ $errors->first('max_login_attempts') }}
                                            </span>
                                        </div>
                                    </div><!-- end col -->

                                    <div class="col-lg-4 col-sm-12">
                                        <div class="cm-floating">
                                            <label class="form-label" for="retry_time_period">
                                                {{  trans('shiledcmstheme::template.setting.retrytimeperiod') }} <span aria-required="true" class="required"> * </span>
                                            </label>
                                            {!! Form::text('retry_time_period' ,!empty(Config::get('Constant.RETRY_TIME_PERIOD'))?Config::get('Constant.RETRY_TIME_PERIOD'):'', array('class' => 'form-control', 'id' => 'retry_time_period', 'autocomplete'=>"off", 'maxlength'=>"3", 'onkeypress'=>"javascript: return KeycheckOnlyAmount(event);",'onpaste'=>'return false')) !!}
                                            <span class="help-block">
                                                {{ $errors->first('retry_time_period') }}
                                            </span>
                                        </div>
                                    </div><!-- end col -->

                                    <div class="col-lg-4 col-sm-12">
                                        <div class="cm-floating">
                                            <label class="form-label" for="lockout_time">
                                                {{  trans('shiledcmstheme::template.setting.lockouttime') }}  <span aria-required="true" class="required"> * </span>
                                            </label>
                                            {!! Form::text('lockout_time' ,!empty(Config::get('Constant.LOCKOUT_TIME'))?Config::get('Constant.LOCKOUT_TIME'):'', array('class' => 'form-control', 'id' => 'lockout_time', 'autocomplete'=>"off", 'maxlength'=>"3", 'onkeypress'=>"javascript: return KeycheckOnlyAmount(event);",'onpaste'=>'return false')) !!}
                                            <span class="help-block">
                                                {{ $errors->first('lockout_time') }}
                                            </span>
                                        </div>
                                    </div><!-- end col -->

                                    <div class="col-lg-12 col-sm-12">
                                        <div class="cm-floating">
                                            <label class="form-label" for="form_control_1">
                                                {{  trans('shiledcmstheme::template.setting.IPsetting') }}
                                            </label>
                                            {!! Form::textarea('ip_setting' , !empty(Config::get('Constant.IP_SETTING'))?Config::get('Constant.IP_SETTING'):'', array('class' => 'form-control', 'id' => 'ip_setting','rows' => '4', 'onkeypress'=>"javascript: return KeycheckOnlyAmount(event);")) !!}
                                            <span class="help-block">
                                                {{ $errors->first('ip_setting') }}
                                            </span> 
                                            <p class="mb-0 mt-2">
                                                <!--Note: You can enter multiple IP addresses separated by commas (e.g: 115.42.150.37,192.168.0.1,110.234.52.124).-->
                                                <strong>Note:</strong> You can enter multiple IP addresses separated by commas (e.g: 115.42.150.37,192.168.0.1,110.234.52.124) who will access the PowerPanel.
                                            </p>
                                        </div>
                                    </div><!-- end col -->

                                    <div class="col-lg-12">
                                        <div class="hstack gap-2">
                                            <button type="submit" class="btn btn-primary bg-gradient waves-effect waves-light btn-label">
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0">
                                                        <i class="ri-save-line label-icon align-middle fs-20 me-2"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                       {!!  trans('shiledcmstheme::template.common.saveandedit') !!}
                                                    </div>
                                                </div>
                                            </button>
                                        </div>
                                    </div>
                                </div> <!--end row-->
                            {!! Form::close() !!}
                        </div>
                    @endcan

                    <!-- Cron -->
                    @can('settings-cron-setting')
                        <div class="tab-pane {{$cron_tab_active}}" id="cron" role="tabpanel">
                            {!! Form::open(['method' => 'post','id' => 'cronSettings']) !!}
                            {!! Form::hidden('tab', 'cron_settings', ['id' => 'cron']) !!}
                                <div class="row">
                                    <div class="col-sm-12">
                                        <h4 class="card-title pagetab-title fw-semibold mb-4 pb-1">Cron Settings</h4>
                                    </div>    
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="cm-floating">
                                            <label class="form-label" for="log_remove_time">
                                                {{ trans('shiledcmstheme::template.setting.logremove') }} <span aria-required="true" class="required"> * </span>
                                            </label>
                                            {!! Form::text('log_remove_time' ,!empty(Config::get('Constant.LOG_REMOVE_TIME'))?Config::get('Constant.LOG_REMOVE_TIME'):'', array('class' => 'form-control', 'id' => 'log_remove_time', 'autocomplete'=>"off", 'maxlength'=>"2", 'onkeypress'=>"javascript: return KeycheckOnlyAmount(event);",'onpaste'=>'return false')) !!}
                                            <span class="help-block">
                                                {{ $errors->first('log_remove_time') }}
                                            </span>
                                        </div>
                                    </div><!-- end col -->

                                    <div class="col-lg-12">
                                        <div class="hstack gap-2">
                                            <button type="submit" class="btn btn-primary bg-gradient waves-effect waves-light btn-label">
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0">
                                                        <i class="ri-save-line label-icon align-middle fs-20 me-2"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                       {!!  trans('shiledcmstheme::template.common.saveandedit') !!}
                                                    </div>
                                                </div>
                                            </button>
                                        </div>
                                    </div>
                                </div> <!--end row-->
                            {!! Form::close() !!}
                        </div>
                    @endcan
                    
                    <!-- Magic -->
                    @can('settings-magic-setting')
                        <div class="tab-pane {{$magic_tab_active}}" id="magic" role="tabpanel">
                            {!! Form::open(['method' => 'post','id' => 'MagicSettings']) !!}
                            {!! Form::hidden('tab', 'magic_settings', ['id' => 'magic']) !!}
                                <div class="row">
                                    <div class="col-sm-12">
                                        <h4 class="card-title pagetab-title fw-semibold mb-4">Magic Upload</h4>
                                    </div>    
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-sm-12">
                                        <div class="cm-floating">
                                            <label class="form-label" for="Magic_Receive_Email">
                                                Your Website Email<span aria-required="true" class="required"> * </span>
                                            </label>
                                            {!! Form::text('Magic_Receive_Email' ,!empty(Config::get('Constant.Magic_Receive_Email'))?Config::get('Constant.Magic_Receive_Email'):'', array('class' => 'form-control maxlength-handler', 'id' => 'Magic_Receive_Email', 'autocomplete'=>"off")) !!}
                                            <span class="help-block">
                                                {{ $errors->first('Magic_Receive_Email') }}
                                            </span>
                                        </div>
                                    </div><!-- end col -->

                                    <div class="col-lg-6 col-sm-12">
                                        <div class="cm-floating">
                                            <label class="form-label" for="Magic_Receive_Password">
                                                Your Website Email Password  <span aria-required="true" class="required"> * </span>
                                            </label>
                                            <input type="password" class="form-control maxlength-handler" id="Magic_Receive_Password" name="Magic_Receive_Password" value="{{ !empty(Config::get('Constant.Magic_Receive_Password'))?Config::get('Constant.Magic_Receive_Password'):''}}"  autocomplete="off">
                                            <span class="help-block">
                                                {{ $errors->first('Magic_Receive_Password') }}
                                            </span>
                                        </div>
                                    </div><!-- end col -->

                                    <div class="col-lg-6 col-sm-12">
                                        <div class="cm-floating">
                                            <label class="form-label" for="Magic_Send_Email">
                                                Assigned Email(s)<span aria-required="true" class="required"> * </span>
                                            </label>
                                            {!! Form::text('Magic_Send_Email' ,!empty(Config::get('Constant.Magic_Send_Email'))?Config::get('Constant.Magic_Send_Email'):'', array('class' => 'form-control maxlength-handler', 'id' => 'Magic_Send_Email', 'autocomplete'=>"off")) !!}
                                            <span class="help-block">
                                                {{ $errors->first('Magic_Send_Email') }}
                                            </span>
                                        </div>
                                    </div><!-- end col -->

                                    <div class="col-lg-6 col-sm-12">
                                        <div class="cm-floating">
                                            <label class="form-label" for="publish_content_module">
                                                Select Module to publish content <span aria-required="true" class="required"> * </span> 
                                                <a href="javascript:;" class="config" data-bs-placement="bottom" data-bs-toggle="tooltip" title="The content will be published as new record in selected module"><i class="ri-information-line fs-16 text-primary"></i></a>
                                            </label>
                                            <select class="form-select" id="publish_content_module" name="publish_content_module" data-choices data-choices-sorting-false>
                                                @foreach ($frontModuleList as $key => $value)
                                                    @php  $selected = ''  @endphp
                                                    @if($value['id'] == Config::get('Constant.PUBLISH_CONTENT_MODULE'))
                                                        @php  $selected = 'selected'  @endphp
                                                    @endif
                                                    <option {{ $selected }} value="{{ $value['id'] }}">{{ ucwords($value['varTitle']) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div><!-- end col -->

                                    <div class="col-lg-12 hidden">
                                        <div class="mb-30">
                                            <div class="note note-info">
                                                <strong>Note: </strong> Email subject will be published as title and email content will published as content on page.
                                            </div>
                                        </div>
                                    </div><!-- end col -->

                                    <div class="col-lg-12">
                                        <div class="hstack gap-2">
                                            <button type="submit" class="btn btn-primary bg-gradient waves-effect waves-light btn-label">
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0">
                                                        <i class="ri-save-line label-icon align-middle fs-20 me-2"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                       {!!  trans('shiledcmstheme::template.common.saveandedit') !!}
                                                    </div>
                                                </div>
                                            </button>
                                        </div>
                                    </div>
                                </div> <!--end row-->
                            {!! Form::close() !!}
                        </div>
                    @endcan

                    <!-- SMTP -->
                    @can('settings-maintenancenew-setting')
                        <div class="tab-pane {{$maintenancenew_tab_active}}" id="maintenancenew" role="tabpanel">
                            {!! Form::open(['method' => 'post','id' => 'MaintenancenewSettings']) !!}
                            {!! Form::hidden('tab', 'maintenancenew_settings', ['id' => 'maintenancenew']) !!}
                                <div class="row gy-2">
                                    <div class="col-lg-6 col-sm-12">
                                        <div class="mb-3">
                                            <label for="Maintenancenew_Send_Email" class="form-label">
                                                Payment Type<span aria-required="true" class="required"> * </span>
                                            </label>
                                            <select class="form-select" name="paymenttype" id="paymenttype" data-choices data-choices-sorting-false>
                                                @php $paymenttype_selected = '' @endphp
                                                @php $M_type = '' @endphp
                                                @php $Y_type = '' @endphp
                                                @if (Config::get('Constant.paymenttype') == 'null')
                                                @php $paymenttype_selected = 'selected' @endphp
                                                @elseif (Config::get('Constant.paymenttype') == 'Y')
                                                @php $Y_type = 'selected' @endphp
                                                @elseif (Config::get('Constant.paymenttype') == 'M')
                                                @php $M_type = 'selected' @endphp
                                                @else
                                                @php $paymenttype_selected = '' @endphp
                                                @php $M_type = '' @endphp
                                                @php $Y_type = '' @endphp
                                                @endif
                                                <option {{$paymenttype_selected}} value="">{{  trans('shiledcmstheme::template.setting.none') }}</option>
                                                <option {{$M_type}} value="M">Monthly</option>
                                                <option {{$Y_type}} value="Y">Yearly</option>
                                            </select>
                                            <span class="help-block">
                                                {{ $errors->first('Maintenancenew_paymenttype') }}
                                            </span>
                                        </div>
                                    </div><!-- end col -->                            
                                    <div class="col-lg-6 col-sm-12">
                                        <div class="mb-3">
                                            <label for="Maintenancenew_Hour" class="form-label">
                                                Hour<span aria-required="true" class="required"> * </span>
                                            </label>
                                            {!! Form::text('Maintenancenew_Hour' ,!empty(Config::get('Constant.Maintenancenew_Hour'))?Config::get('Constant.Maintenancenew_Hour'):'', array('class' => 'form-control maxlength-handler', 'id' => 'Maintenancenew_Hour', 'autocomplete'=>"off",'onkeypress' => 'javascript: return KeycheckOnlyHour(event);','maxlength'=>'5')) !!}
                                            <span class="help-block">{{ $errors->first('Maintenancenew_Hour') }}</span>
                                        </div>
                                    </div><!-- end col -->
                                    <div class="col-lg-6 col-sm-12">
                                        <div class="form-check mb-3">
                                            @if (Config::get('Constant.extebdmonth') == 'Y')
                                            @php $checked_section = true; @endphp
                                            @php $display_Section = ''; @endphp
                                            @else
                                            @php $checked_section = null; 
                                            @endphp
                                            @endif
                                            {{ Form::checkbox('extebdmonth','Y',$checked_section, array('class'=>'form-check-input', 'id'=>'extebdmonth')) }}
                                            <label class="form-check-label">If monthly maintenance hours extends then send email.</label>
                                        </div>
                                    </div> <!--end col-->
                                    <div class="col-lg-6 col-sm-12">
                                        <div class="mb-3">
                                            <label for="Maintenancenew_Rep_Send_Email" class="form-label">
                                                Reporting Email Address <span aria-required="true" class="required"> * </span>
                                            </label>
                                            {!! Form::text('Maintenancenew_Rep_Send_Email' ,!empty(Config::get('Constant.Maintenancenew_Rep_Send_Email'))?Config::get('Constant.Maintenancenew_Rep_Send_Email'):'', array('class' => 'form-control maxlength-handler', 'id' => 'Maintenancenew_Rep_Send_Email', 'autocomplete'=>"off")) !!}
                                            <span class="help-block">{{ $errors->first('Maintenancenew_Rep_Send_Email') }}</span>
                                        </div>
                                    </div><!-- end col -->
                                    <div class="col-lg-12">
                                        <div class="hstack gap-2">
                                            <button type="submit" class="btn btn-primary bg-gradient waves-effect waves-light btn-label">
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0">
                                                        <i class="ri-save-line label-icon align-middle fs-20 me-2"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                       {!! trans('shiledcmstheme::template.common.saveandedit') !!}
                                                    </div>
                                                </div>
                                            </button>
                                        </div>
                                    </div>
                                </div> <!--end row-->
                            {!! Form::close() !!}
                        </div>
                    @endcan

                    <!-- Feature Setting -->
                    @can('settings-features-setting')
                        <div class="tab-pane {{$features_tab_active}}" id="features" role="tabpanel">
                            {!! Form::open(['method' => 'post','id' => 'featuresSettings']) !!}
                            {!! Form::hidden('tab', 'features_settings', ['id' => 'features']) !!}
                                <div class="row">
                                    <div class="col-sm-12">
                                        <h4 class="card-title pagetab-title fw-semibold mb-4">Features Settings</h4>
                                    </div>    
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-xl-4 col-sm-12">
                                        <div class="form-check form-switch form-switch-md mb-3 d-flex" dir="ltr">
                                            @if (Config::get('Constant.DEFAULT_DRAFT') == 'Y')
                                            @php $checked_Draft = 'checked'; @endphp
                                            @else
                                            @php $checked_Draft = null; 
                                            @endphp
                                            @endif

                                            {{ Form::checkbox('chrDraft',null,$checked_Draft, array('class' => 'form-check-input', 'id' => 'chrDraft', 'data-label-icon' => 'fa fa-fullscreen', 'data-on-text' => 'Yes', 'data-off-text' => 'No')) }}
                                            <label class="form-check-label" for="chrDraft">Draft</label>
                                        </div>
                                    </div><!-- end col -->

                                    <div class="col-md-6 col-xl-4 col-sm-12">
                                        <div class="form-check form-switch form-switch-md mb-3" dir="ltr">
                                            @if (Config::get('Constant.DEFAULT_TRASH') == 'Y')
                                            @php $checked_Trash = 'checked'; @endphp
                                            @else
                                            @php $checked_Trash = null; 
                                            @endphp
                                            @endif

                                            {{ Form::checkbox('chrTrash',null,$checked_Trash, array('class' => 'form-check-input', 'id' => 'chrTrash', 'data-label-icon' => 'fa fa-fullscreen', 'data-on-text' => 'Yes', 'data-off-text' => 'No')) }}
                                            <label class="form-check-label" for="chrTrash">Trash / Restore</label>
                                        </div>
                                    </div><!-- end col -->

                                    <div class="col-md-6 col-xl-4 col-sm-12">
                                        <div class="form-check form-switch form-switch-md mb-3" dir="ltr">
                                            @if (Config::get('Constant.DEFAULT_QUICK') == 'Y')
                                            @php $checked_Quick = 'checked'; @endphp
                                            @else
                                            @php $checked_Quick = null; 
                                            @endphp
                                            @endif

                                            {{ Form::checkbox('chrQuick',null,$checked_Quick, array('class' => 'form-check-input', 'id' => 'chrQuick', 'data-label-icon' => 'fa fa-fullscreen', 'data-on-text' => 'Yes', 'data-off-text' => 'No')) }}
                                            <label class="form-check-label" for="chrQuick">Quick Edit</label>
                                        </div>
                                    </div><!-- end col -->

                                    <div class="col-md-6 col-xl-4 col-sm-12">
                                        <div class="form-check form-switch form-switch-md mb-3 d-flex" dir="ltr">
                                            @if (Config::get('Constant.DEFAULT_DUPLICATE') == 'Y')
                                            @php $checked_Duplicate = 'checked'; @endphp
                                            @else
                                            @php $checked_Duplicate = null; 
                                            @endphp
                                            @endif

                                            {{ Form::checkbox('chrDuplicate',null,$checked_Duplicate, array('class' => 'form-check-input', 'id' => 'chrDuplicate', 'data-label-icon' => 'fa fa-fullscreen', 'data-on-text' => 'Yes', 'data-off-text' => 'No')) }}
                                            <label class="form-check-label" for="chrDuplicate">Duplicate</label>
                                        </div>
                                    </div><!-- end col -->

                                    <div class="col-md-6 col-xl-4 col-sm-12">
                                        <div class="form-check form-switch form-switch-md mb-3 d-flex" dir="ltr">
                                            @if (Config::get('Constant.DEFAULT_VISIBILITY') == 'Y')
                                            @php $checked_Visibility = 'checked'; @endphp
                                            @else
                                            @php $checked_Visibility = null; 
                                            @endphp
                                            @endif

                                            {{ Form::checkbox('chrVisibility',null,$checked_Visibility, array('class' => 'form-check-input', 'id' => 'chrVisibility', 'data-label-icon' => 'fa fa-fullscreen', 'data-on-text' => 'Yes', 'data-off-text' => 'No')) }}
                                            <label class="form-check-label" for="chrVisibility">Visibility (Public, Private, Password Protected)</label>
                                        </div>
                                    </div><!-- end col -->

                                    <div class="col-md-6 col-xl-4 col-sm-12">
                                        <div class="form-check form-switch form-switch-md mb-3 d-flex" dir="ltr">
                                            @if (Config::get('Constant.DEFAULT_VISUAL') == 'Y')
                                            @php $checked_Visual = 'checked'; @endphp
                                            @else
                                            @php $checked_Visual = null; 
                                            @endphp
                                            @endif

                                            {{ Form::checkbox('chrVisual',null,$checked_Visual, array('class' => 'form-check-input', 'id' => 'chrVisual', 'data-label-icon' => 'fa fa-fullscreen', 'data-on-text' => 'Yes', 'data-off-text' => 'No')) }}
                                            <label class="form-check-label" for="chrVisual">Visual Composer</label>
                                        </div>
                                    </div><!-- end col -->
                                    
                                    <div class="col-md-6 col-xl-4 col-sm-12">
                                        <div class="form-check form-switch form-switch-md mb-3 d-flex" dir="ltr">
                                            @if (Config::get('Constant.DEFAULT_FAVORITE') == 'Y')
                                            @php $checked_Favorite = 'checked'; @endphp
                                            @else
                                            @php $checked_Favorite = null; 
                                            @endphp
                                            @endif

                                            {{ Form::checkbox('chrFavorite',null,$checked_Favorite, array('class' => 'form-check-input', 'id' => 'chrFavorite', 'data-label-icon' => 'fa fa-fullscreen', 'data-on-text' => 'Yes', 'data-off-text' => 'No')) }}
                                            <label class="form-check-label" for="chrFavorite">Favorite</label>
                                        </div>
                                    </div><!-- end col -->
                                    
                                    <div class="col-md-6 col-xl-4 col-sm-12">
                                        <div class="form-check form-switch form-switch-md mb-3 d-flex" dir="ltr">
                                            @if (Config::get('Constant.DEFAULT_ARCHIVE') == 'Y')
                                            @php $checked_Archive = 'checked'; @endphp
                                            @else
                                            @php $checked_Archive = null; 
                                            @endphp
                                            @endif

                                            {{ Form::checkbox('chrArchive',null,$checked_Archive, array('class' => 'form-check-input', 'id' => 'chrArchive', 'data-label-icon' => 'fa fa-fullscreen', 'data-on-text' => 'Yes', 'data-off-text' => 'No')) }}
                                            <label class="form-check-label" for="chrArchive">Archive</label>
                                        </div>
                                    </div><!-- end col -->
                                    
                                    <div class="col-md-6 col-xl-4 col-sm-12">
                                        <div class="form-check form-switch form-switch-md mb-3 d-flex" dir="ltr">
                                            @if (Config::get('Constant.DEFAULT_FORMBUILDER') == 'Y')
                                            @php $checked_Formbuilder = 'checked'; @endphp
                                            @else
                                            @php $checked_Formbuilder = null; 
                                            @endphp
                                            @endif

                                            {{ Form::checkbox('chrFormbuilder',null,$checked_Formbuilder, array('class' => 'form-check-input', 'id' => 'chrFormbuilder', 'data-label-icon' => 'fa fa-fullscreen', 'data-on-text' => 'Yes', 'data-off-text' => 'No')) }}
                                            <label class="form-check-label" for="chrFormbuilder">Form Builder</label>
                                        </div>
                                    </div><!-- end col -->
                                    
                                    <div class="col-md-6 col-xl-4 col-sm-12">
                                        <div class="form-check form-switch form-switch-md mb-3 d-flex" dir="ltr">
                                            @if (Config::get('Constant.DEFAULT_PAGETEMPLATE') == 'Y')
                                            @php $checked_PageTemplate = 'checked'; @endphp
                                            @else
                                            @php $checked_PageTemplate = null; 
                                            @endphp
                                            @endif

                                            {{ Form::checkbox('chrPageTemplate',null,$checked_PageTemplate, array('class' => 'form-check-input', 'id' => 'chrPageTemplate', 'data-label-icon' => 'fa fa-fullscreen', 'data-on-text' => 'Yes', 'data-off-text' => 'No')) }}
                                            <label class="form-check-label" for="chrPageTemplate">Page Template</label>
                                        </div>
                                    </div><!-- end col -->
                                    
                                    <div class="col-md-6 col-xl-4 col-sm-12">
                                        <div class="form-check form-switch form-switch-md mb-3 d-flex" dir="ltr">
                                            @if (Config::get('Constant.DEFAULT_SPELLCHCEK') == 'Y')
                                            @php $checked_SpellChcek = 'checked'; @endphp
                                            @else
                                            @php $checked_SpellChcek = null; 
                                            @endphp
                                            @endif

                                            {{ Form::checkbox('chrSpellChcek',null,$checked_SpellChcek, array('class' => 'form-check-input', 'id' => 'chrSpellChcek', 'data-label-icon' => 'fa fa-fullscreen', 'data-on-text' => 'Yes', 'data-off-text' => 'No')) }}
                                            <label class="form-check-label" for="chrSpellChcek">Spell Check</label>
                                        </div>
                                    </div><!-- end col -->
                                    
                                    <div class="col-md-6 col-xl-4 col-sm-12">
                                        <div class="form-check form-switch form-switch-md mb-3 d-flex" dir="ltr">
                                            @if (Config::get('Constant.DEFAULT_MESSAGINGSYSTEM') == 'Y')
                                            @php $checked_MessagingSystem = 'checked'; @endphp
                                            @else
                                            @php $checked_MessagingSystem = null; 
                                            @endphp
                                            @endif

                                            {{ Form::checkbox('chrMessagingSystem',null,$checked_MessagingSystem, array('class' => 'form-check-input', 'id' => 'chrMessagingSystem', 'data-label-icon' => 'fa fa-fullscreen', 'data-on-text' => 'Yes', 'data-off-text' => 'No')) }}
                                            <label class="form-check-label" for="chrMessagingSystem">Messaging System</label>
                                        </div>
                                    </div><!-- end col -->
                                    
                                    <div class="col-md-6 col-xl-4 col-sm-12">
                                        <div class="form-check form-switch form-switch-md mb-3 d-flex" dir="ltr">
                                            @if (Config::get('Constant.DEFAULT_CONTENTLOCK') == 'Y')
                                            @php $checked_ContentLock = 'checked'; @endphp
                                            @else
                                            @php $checked_ContentLock = null; 
                                            @endphp
                                            @endif

                                            {{ Form::checkbox('chrContentLock',null,$checked_ContentLock, array('class' => 'form-check-input', 'id' => 'chrContentLock', 'data-label-icon' => 'fa fa-fullscreen', 'data-on-text' => 'Yes', 'data-off-text' => 'No')) }}
                                            <label class="form-check-label" for="chrContentLock">Content Lock</label>
                                        </div>
                                    </div><!-- end col -->
                                    
                                    <div class="col-md-6 col-xl-4 col-sm-12">
                                        <div class="form-check form-switch form-switch-md mb-3 d-flex" dir="ltr">
                                            @if (Config::get('Constant.DEFAULT_AUDIO') == 'Y')
                                            @php $checked_Audio = 'checked'; @endphp
                                            @else
                                            @php $checked_Audio = null; 
                                            @endphp
                                            @endif

                                            {{ Form::checkbox('chrAudio',null,$checked_Audio, array('class' => 'form-check-input', 'id' => 'chrAudio', 'data-label-icon' => 'fa fa-fullscreen', 'data-on-text' => 'Yes', 'data-off-text' => 'No')) }}
                                            <label class="form-check-label" for="chrAudio">Audio</label>
                                        </div>
                                    </div><!-- end col -->
                                    
                                    <div class="col-md-6 col-xl-4 col-sm-12">
                                        <div class="form-check form-switch form-switch-md mb-3 d-flex" dir="ltr">
                                            @if (Config::get('Constant.DEFAULT_AUTHENTICATION') == 'Y')
                                            @php $checked_Authentication = 'checked'; @endphp
                                            @else
                                            @php $checked_Authentication = null; 
                                            @endphp
                                            @endif

                                            {{ Form::checkbox('chrAuthentication',null,$checked_Authentication, array('class' => 'form-check-input', 'id' => 'chrAuthentication', 'data-label-icon' => 'fa fa-fullscreen', 'data-on-text' => 'Yes', 'data-off-text' => 'No')) }}
                                            <label class="form-check-label" for="chrAuthentication">Two Factor Authentication</label>
                                        </div>
                                    </div><!-- end col -->
                                    
                                    <div class="col-md-6 col-xl-4 col-sm-12">
                                        <div class="form-check form-switch form-switch-md mb-3 d-flex" dir="ltr">
                                            @if (Config::get('Constant.DEFAULT_FEEDBACKFORM') == 'Y')
                                            @php $checked_Feedbackform = 'checked'; @endphp
                                            @else
                                            @php $checked_Feedbackform = null; 
                                            @endphp
                                            @endif

                                            {{ Form::checkbox('chrFrontFeedbackForm',null,$checked_Feedbackform, array('class' => 'form-check-input', 'id' => 'chrFrontFeedbackForm', 'data-label-icon' => 'fa fa-fullscreen', 'data-on-text' => 'Yes', 'data-off-text' => 'No')) }}
                                            <label class="form-check-label" for="chrFrontFeedbackForm">Feedback Form</label>
                                        </div>
                                    </div><!-- end col -->
                                    
                                    <div class="col-md-6 col-xl-4 col-sm-12">
                                        <div class="form-check form-switch form-switch-md mb-3 d-flex" dir="ltr">
                                            @if (Config::get('Constant.DEFAULT_ONLINEPOLLINGFORM') == 'Y')
                                            @php $checked_OnlinePollingform = 'checked'; @endphp
                                            @else
                                            @php $checked_OnlinePollingform = null; 
                                            @endphp
                                            @endif

                                            {{ Form::checkbox('chrOnlinePollingForm',null,$checked_OnlinePollingform, array('class' => 'form-check-input', 'id' => 'chrOnlinePollingForm', 'data-label-icon' => 'fa fa-fullscreen', 'data-on-text' => 'Yes', 'data-off-text' => 'No')) }}

                                            <label class="form-check-label" for="chrOnlinePollingForm">Online Polling</label>
                                        </div>
                                    </div><!-- end col -->
                                    
                                    <div class="col-md-6 col-xl-4 col-sm-12">
                                        <div class="form-check form-switch form-switch-md mb-3 d-flex" dir="ltr">
                                            @if (Config::get('Constant.DEFAULT_SHARINGOPTION') == 'Y')
                                            @php $checked_SharingOption = 'checked'; @endphp
                                            @else
                                            @php $checked_SharingOption = null; 
                                            @endphp
                                            @endif

                                            {{ Form::checkbox('chrSharingOption',null,$checked_SharingOption, array('class' => 'form-check-input', 'id' => 'chrSharingOption', 'data-label-icon' => 'fa fa-fullscreen', 'data-on-text' => 'Yes', 'data-off-text' => 'No')) }}
                                            <label class="form-check-label" for="chrSharingOption">Sharing Option</label>
                                        </div>
                                    </div><!-- end col -->
                                    
                                    <div class="col-md-6 col-xl-4 col-sm-12">
                                        <div class="form-check form-switch form-switch-md mb-3 d-flex" dir="ltr">
                                            @if (Config::get('Constant.DEFAULT_EMAILTOFRIENDOPTION') == 'Y')
                                            @php $checked_EmailtofriendOption = 'checked'; @endphp
                                            @else
                                            @php $checked_EmailtofriendOption = null; 
                                            @endphp
                                            @endif

                                            {{ Form::checkbox('chrEmailtofriendOption',null,$checked_EmailtofriendOption, array('class' => 'form-check-input', 'id' => 'chrEmailtofriendOption', 'data-label-icon' => 'fa fa-fullscreen', 'data-on-text' => 'Yes', 'data-off-text' => 'No')) }}
                                            <label class="form-check-label" for="chrEmailtofriendOption">Email To Friend</label>
                                        </div>
                                    </div><!-- end col -->
                                    
                                    <div class="col-lg-12">
                                        <div class="hstack gap-2 mt-2">
                                            <button type="submit" class="btn btn-primary bg-gradient waves-effect waves-light btn-label">
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0">
                                                        <i class="ri-save-line label-icon align-middle fs-20 me-2"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                       {!!  trans('shiledcmstheme::template.common.saveandedit') !!}
                                                    </div>
                                                </div>
                                            </button>
                                        </div>
                                    </div>
                                </div> <!--end row-->
                            {!! Form::close() !!}
                        </div>
                    @endcan

                    <!-- Maintenance -->
                    @can('settings-maintenance-setting')
                        <div class="tab-pane {{$maintenance_tab_active}}" id="maintenance" role="tabpanel">
                            {!! Form::open(['method' => 'post','id' => 'frmMaintenance']) !!}
                            {!! Form::hidden('tab', 'maintenance', ['id' => 'maintenance']) !!}
                                <div class="row">
                                    <div class="col-sm-12">
                                        <h4 class="card-title pagetab-title fw-semibold mb-4">Reset Logs</h4>
                                    </div>    
                                </div>
                                <div class="row">
                                    <div class="form-group hidden d-none">
                                        <label><i class="fa fa-refresh"></i> {{  trans('shiledcmstheme::template.setting.resetCounter') }}</label>
                                        <a href="{{url('powerpanel/settings/getDBbackUp')}}"><i class="fa fa-hdd-o" aria-hidden="true"></i> Database Backup</a>
                                    </div>

                                    <div class="col-md-6 col-xl-4 col-sm-12">
                                        <div class="form-check form-check-primary mb-3">
                                             <label class="form-check-label">
                                                {!! Form::checkbox('reset[]', 'moblihits', null, ['class' => 'form-check-input']) !!}
                                                {{  trans('shiledcmstheme::template.setting.resetMobileHits') }}
                                            </label>
                                        </div>
                                    </div> <!--end col-->

                                    <div class="col-md-6 col-xl-4 col-sm-12">
                                        <div class="form-check form-check-primary mb-3">
                                            <label class="form-check-label">
                                                {!! Form::checkbox('reset[]', 'emaillog', null, ['class' => 'form-check-input']) !!}
                                                {{  trans('shiledcmstheme::template.setting.resetEmailLogs') }}
                                            </label>
                                        </div>
                                    </div> <!--end col-->
                                    
                                    <div class="col-md-6 col-xl-4 col-sm-12">
                                        <div class="form-check form-check-primary mb-3">
                                            <label class="form-check-label">
                                                {!! Form::checkbox('reset[]', 'webhits', null, ['class' => 'form-check-input']) !!}
                                                {{  trans('shiledcmstheme::template.setting.resetWebHits') }}
                                            </label>
                                        </div>
                                    </div> <!--end col-->
                                    
                                    <div class="col-md-6 col-xl-4 col-sm-12">
                                        <div class="form-check form-check-primary mb-3">
                                            <label class="form-check-label">
                                                {!! Form::checkbox('reset[]', 'contactleads', null, ['class' => 'form-check-input']) !!}
                                                {{  trans('shiledcmstheme::template.setting.resetContactLeads') }}
                                            </label>
                                        </div>
                                    </div> <!--end col-->
                                    
                                    <div class="col-md-6 col-xl-4 col-sm-12">
                                        <div class="form-check form-check-primary mb-3">
                                            <label class="form-check-label">
                                                {!! Form::checkbox('reset[]', 'newsletterleads', null, ['class' => 'form-check-input']) !!}
                                                {{  trans('shiledcmstheme::template.setting.resetNewsletterLeads') }}
                                            </label>
                                        </div>
                                    </div> <!--end col-->

                                    <div class="col-md-6 col-xl-4 col-sm-12">
                                        <div class="form-check form-check-primary mb-3">
                                            <label class="form-check-label">
                                                {!! Form::checkbox('reset[]', 'flushAllCache', null, ['class' => 'form-check-input']) !!} 
                                                Flush All Cache
                                            </label>
                                        </div>
                                    </div> <!--end col-->
                                    
                                    <div class="col-lg-6 d-none">
                                        <div class="form-check">
                                            <span class="help-block">
                                                {{ $errors->first('reset') }}
                                            </span>
                                        </div>
                                    </div> <!--end col-->
                                    
                                    <div class="col-lg-12">
                                        <div class="hstack gap-2 mt-2">
                                            <button type="submit" class="btn btn-primary bg-gradient waves-effect waves-light btn-label">
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0">
                                                        <i class="ri-refresh-line label-icon align-middle fs-20 me-2"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                       {{  trans('shiledcmstheme::template.common.reset') }}
                                                    </div>
                                                </div>
                                            </button>
                                        </div>
                                    </div>

                                </div> <!--end row-->
                            {!! Form::close() !!}
                        </div>
                    @endcan

                    <!-- Module Settings -->
                    @can('settings-module-setting')
                        <div class="tab-pane {{$module_tab_active}}" id="modulesettings" role="tabpanel">
                                {!! Form::text('search' , null, array('id' => 'moduleSearch', 'class' => 'form-control', 'placeholder'=>'Module Search', 'autocomplete'=>"off")) !!}
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <a href="javascript:;" class="btn btn-green-drake search-module-settings submit"><i class="ri-search-line"></i></a>
                                            <a href="javascript:;" class="btn btn-green-drake modulewisesettings submit"><i class="fa fa-refresh"></i></a>
                                            <br/><br/><br/>

                                            <div class="clearfix"></div>
                                            <div id='moduleDiv'></div>
                                        </div>
                                    </div><!-- end col -->

                                    <div class="col-lg-12">
                                        <div class="hstack gap-2 justify-content-end">
                                            <button type="submit" class="btn btn-primary">
                                                {!!  trans('shiledcmstheme::template.common.saveandedit') !!}
                                            </button>
                                        </div>
                                    </div>
                                </div> <!--end row-->
                            {!! Form::close() !!}
                        </div>
                    @endcan
                </div>
            </div><!-- end card-body -->
        </div>
    </div><!--end col-->
    <!--end col-->
</div>

@endsection
@section('scripts')

<script type="text/javascript">window.site_url = '{!! url("/") !!}';</script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/setting.js?v='.time() }}" type="text/javascript"></script>
{{-- @include('powerpanel.partials.ckeditor',['config'=>'docsConfig']) --}}
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js' }}" type="text/javascript"></script>
<script type="text/javascript">
    function isNumberKey(evt) {
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;
        return true;
    }
    $(document).ready(function () {
        // $('#timezone').select2({
        //     placeholder: "Select timezone",
        //     width: '100%'
        // }).on("change", function (e) {
        //     $("#timezone").closest('.has-error').removeClass('has-error');
        //     $("#timezone-error").remove();
        // });

        // $('#mailer').select2({
        //     placeholder: "Select mailer",
        //     width: '100%'
        // }).on("change", function (e) {
        //     $("#mailer").closest('.has-error').removeClass('has-error');
        //     $("#mailer-error").remove();
        // });
        // $('#default_page_size').select2({
        //     placeholder: "Select default page size",
        //     width: '100%'
        // }).on("change", function (e) {
        //     $("#default_page_size").closest('.has-error').removeClass('has-error');
        //     $("#default_page_size-error").remove();
        // });
        // $('#default_date_format').select2({
        //     placeholder: "Select default date format",
        //     width: '100%'
        // }).on("change", function (e) {
        //     $("#default_date_format").closest('.has-error').removeClass('has-error');
        //     $("#default_date_format-error").remove();
        // });
        // $('#time_format').select2({
        //     placeholder: "Select default time format",
        //     width: '100%'
        // }).on("change", function (e) {
        //     $("#time_format").closest('.has-error').removeClass('has-error');
        //     $("#time_format-error").remove();
        // });

        // $('#publish_content_module').select2({
        //     placeholder: "Select Module",
        //     width: '100%'
        // }).on("change", function (e) {
        //     $("#publish_content_module").closest('.has-error').removeClass('has-error');
        //     $("#publish_content_module-error").remove();
        // });


    });</script>
<script type="text/javascript">
    function getAttributes(val)
    {
        // if (val == 'other') {
        //     document.getElementById("second_tab").click();
        // }
        if (val == 'other' || val == 'security' || val == 'cron' || val == 'features' || val == 'magic') {
            $('.tab_section_setting').css('display', 'block');
        } else {
            $('.tab_section_setting').css('display', 'none');
        }
    }
</script>
<script type="text/javascript">
    value = "{{$tab_value}}"
    $(document).ready(function () {
        if (value == 'other_settings' || value == 'security_settings' || value == 'cron_settings' || value == 'features_settings' || value == 'magic_settings') {
            $('.tab_section_setting').css('display', 'block');
            //$("#one_tab").addClass("active");
        }
    });

</script>
@endsection