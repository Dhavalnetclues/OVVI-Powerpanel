@section('css')
<link href="{{ $CDN_PATH.'resources/global/plugins/bootstrap-taginput/bootstrap-tagsinput.css' }}" rel="stylesheet" type="text/css" />
@endsection
@extends('powerpanel.layouts.app')
@section('title')
{{Config::get('Constant.SITE_NAME')}} - PowerPanel
@endsection
@section('content')
@php $settings = json_decode(Config::get("Constant.MODULE.SETTINGS")); @endphp
<!-- @include('powerpanel.partials.breadcrumbs') -->
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
        
        <div class="live-preview">
            {!! Form::open(['method' => 'post','id'=>'frmcareers']) !!}
                <div class="card">
                    <div class="card-body p-30 pb-0">
                        @if(isset($careers))
                        <div class="row pagetitle-heading mb-3">
                            <div class="col-sm-11 col-11">
                                <h4 class="page-title fw-semibold m-0">{{ $breadcrumb['inner_title']}}</h4>
                            </div>
                        </div>
                        @endif

                        {!! Form::hidden('fkMainRecord', isset($careers->fkMainRecord)?$careers->fkMainRecord:old('fkMainRecord')) !!}
                        <div class="row">
                            {{-- Sector type --}}
                            <div class="col-lg-6 col-sm-12">
                                <div class="@if($errors->first('sector')) has-error @endif form-md-line-input cm-floating">
                                    @if(isset($careers_highLight->varSector) && ($careers_highLight->varSector != $careers->varSector))
                                        @php $Class_varSector = " highlitetext"; @endphp
                                    @else
                                        @php $Class_varSector = ""; @endphp
                                    @endif
                                    @include('shiledcmstheme::powerpanel.partials.sector-dropdown', ['selected_sector' => isset($careers->varSector)?$careers->varSector:'','Class_varSector' => $Class_varSector])
                                    <span class="help-block">{{ $errors->first('sector') }}</span>
                                </div>
                            </div>
                            {{-- title --}}
                            <div class="col-lg-6 col-sm-12">
                                <div class="@if($errors->first('title')) has-error @endif form-md-line-input cm-floating">
                                    @php if(isset($careers_highLight->varTitle) && ($careers_highLight->varTitle != $careers->varTitle)){
                                    $Class_title = " highlitetext";
                                    }else{
                                    $Class_title = "";
                                    } @endphp
                                    <label class="form-label {!! $Class_title !!}" for="site_name">{{ trans('careers::template.common.title') }} <span aria-required="true" class="required"> * </span></label>
                                    {!! Form::text('title', isset($careers->varTitle) ? $careers->varTitle:old('title'), array('maxlength'=>'150','id'=>'title','class' => 'form-control hasAlias seoField maxlength-handler titlespellingcheck','autocomplete'=>'off')) !!}
                                    <span class="help-block">{{ $errors->first('title') }}</span>
                                    <!-- code for alias -->
                                    <div class="link-url mt-2">
                                        {!! Form::hidden(null, null, array('class' => 'hasAlias','data-url' => 'powerpanel/careers')) !!}
                                        {!! Form::hidden('alias', isset($careers->alias->varAlias) ? $careers->alias->varAlias : old('alias'), array('class' => 'aliasField')) !!}
                                        {!! Form::hidden('oldAlias', isset($careers->alias->varAlias)?$careers->alias->varAlias : old('alias')) !!}
                                        {!! Form::hidden('previewId') !!}
                                        <div class="alias-group {{!isset($careers->alias)?'hide':''}}">
                                            <label class="form-label" for="Url">{{ trans('careers::template.common.url') }} :</label>
                                            @if(isset($careers->alias->varAlias) && !$userIsAdmin)
                                            @php
                                            $aurl = App\Helpers\MyLibrary::getFrontUri('careers')['uri'];
                                            @endphp
                                                <a class="alias">{!! url("/") !!}</a>
                                            @else
                                            @if(auth()->user()->can('careers-create'))
                                            <a href="javascript:void;" class="alias">{!! url("/") !!}</a>
                                            <a href="javascript:void(0);" class="editAlias" title="Edit">
                                                <i class="ri-pencil-line"></i>
                                            </a>
                                            <a class="without_bg_icon openLink" title="Open Link" onClick="generatePreview('{{ url('/previewpage?url='.(App\Helpers\MyLibrary::getFrontUri('careers')['uri'])) }}');">
                                                <i class="ri-external-link-line" aria-hidden="true"></i>
                                            </a>
                                            @endif
                                            @endif
                                        </div>
                                        <span class="help-block">{{ $errors->first('alias') }}</span>
                                        <!-- code for alias -->
                                    </div>
                                </div>
                            </div>
                            {{-- no. of position --}}
                            <div class="col-lg-6 col-sm-12">
                                <div class="@if($errors->first('position')) has-error @endif form-md-line-input cm-floating">
                                    @if(isset($careers_highLight->txtPosition) && ($careers_highLight->txtPosition != $careers->txtPosition))
                                    @php $Class_Position = " highlitetext"; @endphp
                                    @else
                                    @php $Class_Position = ""; @endphp
                                    @endif
                                    <label class="form-label {{ $Class_Position }}" for="site_name">{{ trans('careers::template.common.position') }} <span aria-required="true" class="required"> * </span></label>
                                    {!! Form::text('position', isset($careers->txtPosition) ? $careers->txtPosition:old('position'), array('maxlength'=>'50','id'=>'position','class' => 'form-control')) !!}
                                    <span style="color: red;">{{ $errors->first('position') }}</span>
                                </div>
                            </div>
                            {{-- experience --}}
                            <div class="col-lg-6 col-sm-12">
                                <div class="@if($errors->first('experience')) has-error @endif form-md-line-input cm-floating">
                                    @if(isset($careers_highLight->txtExperience) && ($careers_highLight->txtExperience != $careers->txtExperience))
                                    @php $Class_Experience = "highlitetext"; @endphp
                                    @else
                                    @php $Class_Experience = ""; 
                                    @endphp
                                    @endif
                                    <label class="form-label {{ $Class_Experience }}" for="site_name">{{ trans('careers::template.common.experience') }} <span aria-required="true" class="required"> * </span></label>
                                    {!! Form::text('experience', isset($careers->txtExperience) ? $careers->txtExperience:old('experience'), array('maxlength'=>'50','id'=>'experience','class' => 'form-control')) !!}
                                    <span style="color: red;">{{ $errors->first('experience') }}</span>
                                </div>
                            </div>
                            {{-- salary range --}}
                            <div class="col-lg-6 col-sm-12">
                                <div class="@if($errors->first('salary')) has-error @endif form-md-line-input cm-floating">
                                    @if(isset($careers_highLight->intSalary) && ($careers_highLight->intSalary != $careers->intSalary))
                                    @php $Class_Salary = " highlitetext"; @endphp
                                    @else
                                    @php $Class_Salary = ""; @endphp
                                    @endif
                                    <label class="form-label {{ $Class_title }}" for="site_name">{{ trans('careers::template.common.salary') }} <span aria-required="true" class="required"> * </span></label>
                                    {!! Form::text('salary', isset($careers->intSalary) ? $careers->intSalary:old('salary'), array('maxlength'=>'50','id'=>'salary','class' => 'form-control')) !!}
                                    <span style="color: red;">{{ $errors->first('salary') }}</span>
                                </div>
                            </div>
                            {{-- Employment type --}}
                            <div class="col-lg-6 col-sm-12 d-flex align-items-center">
                                @if(isset($careers->employmentType))
                                @php $srank = $careers->employmentType; @endphp
                                @else
                                @php
                                $srank = null !== old('employmentType') ? old('employmentType') : 2 ;
                                @endphp
                                @endif
                                @if(isset($careers_highLight->employmentType) && ($careers_highLight->employmentType != $careers->employmentType))
                                @php $Class_employmentType = " highlitetext"; @endphp
                                @else
                                @php $Class_employmentType = ""; @endphp
                                <div class="form-group mb-30 {{ $errors->has('banner_type') ? ' has-error' : '' }}">
                                    <label class="form-label" for="site_name">Status <span aria-required="true" class="required"> * </span></label>
                                    <div class="md-radio-inline d-md-inline-block ms-md-3">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" value="F" name="employmentType" id="employmentType0" checked>
                                            <label class="form-check-label" for="employmentType0">Full Time</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" value="P" name="employmentType" id="employmentType1" checked>
                                            <label class="form-check-label" for="employmentType1">Part Time</label>
                                        </div>
                                    </div>
                                    <span class="help-block"><strong>{{ $errors->first('banner_type') }}</strong></span>
                                </div>
                                @endif
                            </div>
                            {{-- Requirements of job --}}
                            <div class="col-lg-6 col-sm-12">
                                <div class="@if($errors->first('requirements')) has-error @endif form-md-line-input cm-floating">
                                    @php if(isset($careers_highLight->varRequirements) && ($careers_highLight->varRequirements != $careers->varRequirements)){
                                    $Class_Requirements = " highlitetext";
                                    }else{
                                    $Class_Requirements = "";
                                    } @endphp
                                    <label class="form-label {!! $Class_Requirements !!}">Requirements of job<span aria-required="true" class="required"> * </span></label>
                                    {!! Form::textarea('requirements', isset($careers->varRequirements)?$careers->varRequirements:old('requirements'), array('maxlength' => isset($settings->short_desc_length)?$settings->short_desc_length:400,'class' => 'form-control seoField maxlength-handler shortdescspellingcheck','id'=>'varRequirements','rows'=>'3')) !!}
                                    <span class="help-block">{{ $errors->first('requirements') }}</span> 
                                </div>
                            </div>
                            {{-- Short description --}}
                            <div class="col-lg-6 col-sm-12">
                                <div class="@if($errors->first('short_description')) has-error @endif form-md-line-input cm-floating">
                                    @php if(isset($careers_highLight->varShortDescription) && ($careers_highLight->varShortDescription != $careers->varShortDescription)){
                                    $Class_ShortDescription = " highlitetext";
                                    }else{
                                    $Class_ShortDescription = "";
                                    } @endphp
                                    <label class="form-label {!! $Class_ShortDescription !!}">Description Of Job</label>
                                    {!! Form::textarea('short_description', isset($careers->varShortDescription)?$careers->varShortDescription:old('short_description'), array('maxlength' => isset($settings->short_desc_length)?$settings->short_desc_length:400,'class' => 'form-control seoField maxlength-handler shortdescspellingcheck','id'=>'varShortDescription','rows'=>'3')) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Page Content --}}
                <div class="card">
                    <div class="card-body p-30">
                        <div class="row">
                            <div class="col-md-12">
                                @php if(isset($careers_highLight->txtDescription) && ($careers_highLight->txtDescription != $careers->txtDescription)){
                                $Class_Description = " highlitetext";
                                }else{
                                $Class_Description = "";
                                } @endphp
                                <div class="@if($errors->first('description')) has-error @endif form-md-line-input">
                                    @if (Config::get('Constant.DEFAULT_VISUAL') == 'Y')
                                        <div id="body-roll">
                                            @php $sections = []; @endphp
                                            @if(isset($careers))
                                                @php $sections = json_decode($careers->txtDescription); @endphp
                                            @endif
                                            <!-- Builder include -->
                                            @php
                                            Powerpanel\VisualComposer\Controllers\VisualComposerController::page_section(['sections'=>$sections])
                                            @endphp
                                        </div>
                                    @else
                                        {!! Form::textarea('description', isset($careers->txtDescription)?$careers->txtDescription:old('description'), array('class' => 'form-control','id'=>'txtDescription')) !!}
                                    @endif
                                    <span class="help-block">{{ $errors->first('description') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                {{-- Search Rank --}}
                <div class="card">
                    <div class="card-body p-30">
                        <div class="row">
                            <div class="col-md-12">
                                @if(isset($careers->intSearchRank))
                                    @php $srank = $careers->intSearchRank; @endphp
                                @else
                                    @php $srank = null !== old('search_rank') ? old('search_rank') : 2 ; @endphp
                                @endif

                                @if(Config::get('Constant.CHRSearchRank') == 'Y')
                                    <h4 class="form-section mb-3">Search Ranking</h4>
                                    <div class="wrapper search_rank">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="search_rank" @if(isset($srank) && $srank == 1) checked @endif id="yes_radio" value="1">
                                            <label class="form-check-label" for="yes_radio">High</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="search_rank" @if(isset($srank) && $srank == 2) checked @endif id="maybe_radio" value="2">
                                            <label class="form-check-label" for="maybe_radio">Medium</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="search_rank" @if(isset($srank) && $srank == 3) checked @endif id="no_radio" value="3">
                                            <label class="form-check-label" for="no_radio">Low</label>
                                        </div>
                                        <div class="toggle"></div>
                                    </div>
                                    <div class="alert alert-info alert-border-left mt-3 mb-0 d-block">
                                        <strong>Note: </strong> {{ trans('careers::template.common.SearchEntityTools') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                {{-- SEO Info --}}
                <div class="card">
                    <div class="card-body p-30">
                        <div class="row">
                            <div class="col-md-12">
                                @if(isset($careers_highLight->varTags) && ($careers_highLight->varTags != $careers->varTags))
                                    @php $Class_varTags = " highlitetext"; @endphp
                                @else
                                    @php $Class_varTags = ""; @endphp
                                @endif
                                @include('powerpanel.partials.seoInfo',['form'=>'frmcareers','inf'=>isset($metaInfo)?$metaInfo:false,'inf_highLight'=> isset($metaInfo_highLight)?$metaInfo_highLight:false, 'srank' => $srank, 'Class_varTags' => $Class_varTags])
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body p-30">
                        <div class="row">
                            {{-- Display Information --}}
                            <div class="col-lg-6 col-sm-12">
                                <h4 class="form-section mb-3">{{ trans('careers::template.common.displayinformation') }}</h4>
                                @php
                                $display_order_attributes = array('class' => 'form-control','maxlength'=>5,'autocomplete'=>'off');
                                @endphp
                                @if(isset($careers_highLight->intDisplayOrder) && ($careers_highLight->intDisplayOrder != $careers->intDisplayOrder))
                                @php $Class_intDisplayOrder = " highlitetext"; @endphp
                                @else
                                @php $Class_intDisplayOrder = ""; @endphp
                                @endif
                                <div class="@if($errors->first('order')) has-error @endif form-md-line-input cm-floating">
                                    {{-- <label class="form-label {{ $Class_intDisplayOrder }}" for="site_name">{{ trans('careers::template.common.displayorder') }} <span aria-required="true" class="required"> * </span></label>
                                    {!! Form::text('order', isset($careers->intDisplayOrder)?$careers->intDisplayOrder:1, $display_order_attributes) !!}
                                    <span style="color: red;">{{ $errors->first('order') }}</span> --}}
                                    <div class="publish-info mt-3">
                                        @if(isset($careers_highLight->chrPublish) && ($careers_highLight->chrPublish != $careers->chrPublish))
                                            @php $Class_chrPublish = " highlitetext"; @endphp
                                        @else
                                            @php $Class_chrPublish = ""; @endphp
                                        @endif
                                        @if(isset($careers) && $careers->chrAddStar == 'Y')
                                            <label class="control-label form-label"> Publish/ Unpublish</label>
                                            <input type="hidden" id="chrMenuDisplay" name="chrMenuDisplay" value="{{ isset($careers->chrPublish) ? $careers->chrPublish : '' }}">
                                            <p><b>NOTE:</b> This record is in Approval Request , so it can&#39;t be published/unpublished.</p>
                                        @elseif(isset($careers) && $careers->chrDraft == 'D' && $careers->chrAddStar != 'Y')
                                            @include('powerpanel.partials.displayInfo',['Class_chrPublish'=>$Class_chrPublish,'display' => (isset($careers->chrDraft)?$careers->chrDraft:'D')])
                                        @else
                                            @include('powerpanel.partials.displayInfo',['Class_chrPublish'=>$Class_chrPublish,'display' => (isset($careers->chrPublish)?$careers->chrPublish:'Y')])
                                        @endif
                                    </div>
                                </div>
                            </div>
                            {{-- Content Scheduling --}}
                            <div class="col-lg-6 col-sm-12">
                                <h4 class="form-section mb-3">{{ trans('careers::template.common.ContentScheduling') }}</h4>
                                @php $defaultDt = (null !== old('start_date_time'))?old('start_date_time'):date(Config::get('Constant.DEFAULT_DATE_FORMAT').' '.Config::get('Constant.DEFAULT_TIME_FORMAT')); @endphp
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-md-line-input cm-floating">
                                            @php if(isset($careers_highLight->dtDateTime) && ($careers_highLight->dtDateTime != $careers->dtDateTime)){
                                            $Class_date = " highlitetext";
                                            }else{
                                            $Class_date = "";
                                            } @endphp
                                            <label class="control-label form-label {!! $Class_date !!}">{{ trans('careers::template.common.startDateAndTime') }}<span aria-required="true" class="required"> * </span></label>
                                            <div class="input-group date form_meridian_datetime @if($errors->first('start_date_time')) has-error @endif" data-date="{{ Carbon\Carbon::today()->format('Y-m-d') }}T15:25:00Z">
                                                {!! Form::text('start_date_time', date(Config::get('Constant.DEFAULT_DATE_FORMAT').' '.Config::get('Constant.DEFAULT_TIME_FORMAT'),strtotime(isset($careers->dtDateTime)?$careers->dtDateTime:$defaultDt)), array('class' => 'form-control', 'data-provider' => 'flatpickr', 'data-date-format' => Config::get('Constant.DEFAULT_DATE_FORMAT'), 'data-enable-time' => '','maxlength'=>160,'size'=>'16','id'=>'careers_start_date','autocomplete'=>'off','onkeypress'=>"javascript: return KeycheckOnlyDate(event);",'onpaste'=>'return false')) !!}
                                            </div>
                                            <span class="help-block">{{ $errors->first('start_date_time') }}</span>
                                        </div>
                                    </div>
                                    @php $defaultDt = (null !== old('end_date_time'))?old('end_date_time'):null; @endphp
                                    @if ((isset($careers->dtEndDateTime)==null))
                                    @php
                                    $expChecked_yes = 1;
                                    $expclass='';
                                    @endphp
                                    @else
                                    @php
                                    $expChecked_yes = 0;
                                    $expclass='no_expiry';
                                    @endphp
                                    @endif
                                    <div class="col-md-6">
                                        <div class="form-md-line-input">
                                            @php if(isset($careers_highLight->varTitle) && ($careers_highLight->dtEndDateTime != $careers->dtEndDateTime)){
                                            $Class_end_date = " highlitetext";
                                            }else{
                                            $Class_end_date = "";
                                            } @endphp
                                            <div class="cm-floating form_meridian_datetime expirydate @if($errors->first('end_date_time')) has-error @endif" data-date="{{ Carbon\Carbon::today()->format('Y-m-d') }}T15:25:00Z" @if ($expChecked_yes==1) style="display:none;" @endif>
                                                <label class="form-label {!! $Class_end_date !!}">{{ trans('careers::template.common.endDateAndTime') }} <span aria-required="true" class="required"> * </span></label>
                                                <div class="input-group date">
                                                    {!! Form::text('end_date_time', isset($careers->dtEndDateTime)?date(Config::get('Constant.DEFAULT_DATE_FORMAT').' '.Config::get('Constant.DEFAULT_TIME_FORMAT'),strtotime($careers->dtEndDateTime)):$defaultDt, array('class' => 'form-control', 'data-provider' => 'flatpickr', 'data-date-format' => Config::get('Constant.DEFAULT_DATE_FORMAT'), 'data-enable-time' => '','maxlength'=>160,'size'=>'16','id'=>'careers_end_date','data-exp'=> $expChecked_yes,'data-newvalue','autocomplete'=>'off','onkeypress'=>"javascript: return KeycheckOnlyDate(event);",'onpaste'=>'return false')) !!}
                                                </div>
                                            </div>
                                            <span class="help-block">{{ $errors->first('end_date_time') }}</span>
                                            <label class="expdatelabel {{ $expclass }} form-label">
                                                <a id="noexpiry" name="noexpiry" href="javascript:void(0);">
                                                    <b class="expiry_lbl {!! $Class_end_date !!}">Set Expiry</b>
                                                </a>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- Form Action --}}
                            <div class="col-md-12">
                                <div class="form-actions">
                                    @if(isset($careers->fkMainRecord) && $careers->fkMainRecord != 0)
                                    <button type="submit" name="saveandexit" formmethod="post" class="btn btn-primary bg-gradient waves-effect waves-light btn-label me-1" value="saveandexit">
                                        <div class="flex-shrink-0">
                                            <i class="ri-check-line label-icon align-middle fs-20 me-2"></i>
                                        </div>
                                        {!! trans('careers::template.common.approve') !!}
                                    </button>
                                    @else
                                    @if($userIsAdmin)
                                    <button type="submit" name="saveandedit" formmethod="post" class="btn btn-primary bg-gradient waves-effect waves-light btn-label me-1" value="saveandedit">
                                        <div class="flex-shrink-0">
                                            <i class="ri-save-line label-icon align-middle fs-20 me-2"></i>
                                        </div>
                                        {!! trans('careers::template.common.saveandedit') !!}
                                    </button>
                                    <button type="submit" name="saveandexit" formmethod="post" class="btn btn-secondary bg-gradient waves-effect waves-light btn-label me-1" value="saveandexit">
                                        <div class="flex-shrink-0">
                                            <i class="ri-save-3-line label-icon align-middle fs-20 me-2"></i>
                                        </div>
                                        {!! trans('careers::template.common.saveandexit') !!}
                                    </button>
                                    @else
                                    @if((isset($chrNeedAddPermission) && $chrNeedAddPermission == 'N') && (isset($charNeedApproval) && $charNeedApproval == 'N'))
                                    <button type="submit" name="saveandexit" formmethod="post" class="btn btn-secondary bg-gradient waves-effect waves-light btn-label me-1" value="saveandexit">
                                        <div class="flex-shrink-0">
                                            <i class="ri-save-3-line label-icon align-middle fs-20 me-2"></i>
                                        </div>
                                        {!! trans('careers::template.common.saveandexit') !!}
                                    </button>
                                    @else
                                    <button type="submit" name="saveandexit" formmethod="post" class="btn btn-primary bg-gradient waves-effect waves-light btn-label me-1" value="approvesaveandexit">
                                        <div class="flex-shrink-0">
                                            <i class="ri-save-line label-icon align-middle fs-20 me-2"></i>
                                        </div>
                                        {!! trans('careers::template.common.approvesaveandexit') !!}
                                    </button>
                                    @endif
                                    @endif
                                    @endif
                                    <a class="btn btn-danger bg-gradient waves-effect waves-light btn-label me-1" href="{{ url('powerpanel/careers') }}">
                                        <div class="flex-shrink-0">
                                            <i class="ri-close-line label-icon align-middle fs-20 me-2"></i>
                                        </div>
                                        {{ trans('careers::template.common.cancel') }}
                                    </a>
                                    @if(isset($careers) && !empty($careers))
                                    <a class="btn btn-info bg-gradient waves-effect waves-light btn-label me-1" title="Preview" onClick="generatePreview('{{url('/previewpage?url='.(App\Helpers\MyLibrary::getFrontUri('careers')['uri']))}}');">
                                        <div class="flex-shrink-0">
                                            <i class="ri-eye-line label-icon align-middle fs-20 me-2"></i>
                                        </div>
                                        Preview
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            {!! Form::close() !!}
        </div>
    </div>
</div><!--end row-->

@if (Config::get('Constant.DEFAULT_VISUAL') == 'Y')
    @php Powerpanel\VisualComposer\Controllers\VisualComposerController::get_dialog_maker()@endphp
    @php Powerpanel\VisualComposer\Controllers\VisualComposerController::get_visual_checkEditor()@endphp
@else
    @include('powerpanel.partials.ckeditor',['config'=>'docsConfig'])
@endif
@endsection
@section('scripts')
<script type="text/javascript">
    window.site_url = '{!! url("/") !!}';
    var seoFormId = 'frmcareers';
    var user_action = "{{ isset($careers)?'edit':'add' }}";
    var moduleAlias = "{{ App\Helpers\MyLibrary::getFrontUri('careers')['moduleAlias'] }}";
    var preview_add_route = '{!! route("powerpanel.careers.addpreview") !!}';
    var previewForm = $('#frmcareers');
    var isDetailPage = true;
    function generate_seocontent1(formname) {
    var Meta_Title = document.getElementById('title').value + "";
        var abcd = $('textarea#txtDescription').val();
        var def = abcd.replace(/<a(\s[^>]*)?>.*?<\/a>/ig, "")
        var abc = def.replace(/^(\s*)|(\s*)$/g, '').replace(/\s+/g, ' ');
        var outString1 = abc.replace(/(<([^>]+)>)/ig, "");
        var Meta_Description = "" + document.getElementById('title').value;
        var Meta_Keyword = "";
        $('#varMetaTitle').val(Meta_Title);
        $('#varMetaDescription').val(Meta_Description);
        $('#meta_title').html(Meta_Title);
        $('#meta_description').html(Meta_Description);
    }
</script>
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-taginput/bootstrap-tagsinput.min.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/careers/careers_validations.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/custom.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/custom-alias/alias-generator.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/seo-generator/seo-info-generator.js' }}" type="text/javascript"></script>
<script src="{{ Config::get('Constant.CDN_PATH').'resources/pages/scripts/pages_password_rules.js' }}" type="text/javascript"></script>
<script type="text/javascript">
    function OpenPassword(val) {
    if (val == 'PP') {
    $("#passid").show();
    } else {
    $("#passid").hide();
    }
    }
</script>
@if (Config::get('Constant.DEFAULT_VISUAL') == 'Y')
    @php Powerpanel\VisualComposer\Controllers\VisualComposerController::get_builder_css_js()@endphp
@endif
@endsection