@section('css')
    <link href="{{ $CDN_PATH . 'resources/global/plugins/bootstrap-taginput/bootstrap-tagsinput.css' }}" rel="stylesheet" type="text/css" />
@endsection
@extends('powerpanel.layouts.app')
@section('title')
    {{ Config::get('Constant.SITE_NAME') }} - PowerPanel
@endsection
@section('content')
@php $settings = json_decode(Config::get("Constant.MODULE.SETTINGS")); @endphp

<div class="row">
    <div class="col-xxl-12 settings">
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
            {!! Form::open(['method' => 'post', 'id' => 'frmCmsPage']) !!}
            <div class="card">
                <div class="card-body p-30 pb-0">
                    @if(isset($Cmspage))
                    <div class="row pagetitle-heading mb-3">
                        <div class="col-sm-11 col-11">
                            <h4 class="page-title fw-semibold m-0">{{ $breadcrumb['inner_title']}}</h4>
                        </div>
                        <div class="col-sm-1 col-1 lock-link">
                            @if (File::exists(base_path() . '/resources/views/powerpanel/partials/lockedpage.blade.php') != null)
                            @include('powerpanel.partials.lockedpage',['pagedata'=>$Cmspage])
                            @endif
                        </div>
                    </div>
                    @endif

                    <div class="row">
                        <!-- Sector type -->
                        <div class="col-lg-6 col-sm-12 @if($errors->first('sector')) has-error @endif">
                            @include('shiledcmstheme::powerpanel.partials.sector-dropdown', ['selected_sector' => isset($Cmspage->varSector)?$Cmspage->varSector:'','Class_varSector' => ''])
                        </div>

                        {{-- Select Module - START --}}
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-md-line-input cm-floating @if ($errors->first('module')) has-error @endif" @if (isset($Cmspage->alias->varAlias) && $Cmspage->alias->varAlias == 'home') style="display: none;" @endif>
                                @php if (isset($Cmspage_highLight->intFKModuleCode) && $Cmspage_highLight->intFKModuleCode != $Cmspage->intFKModuleCode) {
                                    $Class_module = ' highlitetext';
                                } else {
                                    $Class_module = '';
                                }
                                @endphp
                                @if (isset($Cmspage->alias->varAlias) && $Cmspage->alias->varAlias == 'home')
                                    {!! Form::hidden('module', '1') !!}
                                @else
                                    <label class="form-label {!! $Class_module !!}" for="title">
                                        {{ trans('cmspage::template.pageModule.module') }} <span aria-required="true" class="required"> * </span>
                                        <span class="img-note" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ trans('cmspage::template.common.ModuleTools') }}">
                                            <i class="ri-information-line text-primary fs-16"></i>
                                        </span>
                                    </label>
                                    
                                    <select class="form-control" id="moduleList" name="module" data-choices>
                                        @php $avoidModules = ['sitemap']; @endphp
                                        @if (empty($userIsAdmin) && empty($Cmspage))
                                            <option value="">{{ trans('cmspage::template.common.selectmodule') }}</option>
                                            <option value="3">Default Page (CMS)</option>
                                        @endif
                                        @if (!empty($userIsAdmin))
                                            <option value="">{{ trans('cmspage::template.common.selectmodule') }}</option>
                                        @endif
                                        @foreach ($modules as $module)
                                            @php $selected = ''; @endphp
                                            @if (isset($Cmspage->intFKModuleCode))
                                                @if ($module['id'] == $Cmspage->intFKModuleCode)
                                                    @php $selected = 'selected'; @endphp
                                                @endif
                                            @elseif($module['id'] == 4)
                                                @php $selected = 'selected'; @endphp
                                            @endif

                                            @if (!in_array($module['varModuleName'], $avoidModules) && Auth::user()->can($module['varModuleName'] . '-list'))
                                                @if (!empty($userIsAdmin))
                                                    <option value="{{ $module['id'] }}" {{ $selected }}>
                                                        {{ $module['varModuleName'] == 'pages' ? 'Default Page (CMS)' : $module['varTitle'] }}
                                                    </option>
                                                @else
                                                    @if (empty($userIsAdmin) && isset($selected) && !empty($selected))
                                                        <option readonly value="{{ $module['id'] }}" {{ $selected }}>
                                                            {{ $module['varModuleName'] == 'pages' ? 'Default Page (CMS)' : $module['varTitle'] }}
                                                        </option>
                                                    @endif
                                                @endif
                                            @endif
                                        @endforeach
                                    </select>
                                    <span class="help-block">{{ $errors->first('module') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{--    Title & alias   - Start    --}}
                    <div class="row">
                        <div class="col-lg-6 col-sm-12">
                            <div class="@if($errors->first('title')) has-error @endif form-md-line-input cm-floating">
                                @php
                                if (isset($Cmspage_highLight->varTitle) && $Cmspage_highLight->varTitle != $Cmspage->varTitle) {
                                $Class_title = ' highlitetext';
                                } else {
                                $Class_title = '';
                                } @endphp
                                <label for="title" class="form-label">{{ trans('cmspage::template.common.title') }} <span aria-required="true" class="required"> * </span></label>
                                {!! Form::text('title', isset($Cmspage->varTitle) ? $Cmspage->varTitle : old('title'), ['maxlength' => '150', 'class' => 'form-control hasAlias seoField maxlength-handler titlespellingcheck', 'data-url' => 'powerpanel/pages', 'id' => 'title', 'autocomplete' => 'off']) !!}
                                <span class="help-block">{{ $errors->first('title') }}</span>
                                <div class="link-url mt-2">
                                    <!-- code for alias -->
                                    {!! Form::hidden(null, null, ['class' => 'hasAlias', 'data-url' => 'powerpanel/pages']) !!}
                                    {!! Form::hidden('alias', isset($Cmspage->alias->varAlias) ? $Cmspage->alias->varAlias : old('alias'), ['class' => 'aliasField']) !!}
                                    {!! Form::hidden('oldAlias', isset($Cmspage->alias->varAlias) ? $Cmspage->alias->varAlias : old('alias')) !!}
                                    {!! Form::hidden('fkMainRecord', isset($Cmspage->fkMainRecord) ? $Cmspage->fkMainRecord : old('fkMainRecord')) !!}
                                    {!! Form::hidden('previewId') !!}
                                    <div class="alias-group {{ !isset($Cmspage->alias) ? 'hide' : '' }}">
                                        <label for="Url" class="form-label m-0">{{ trans('cmspage::template.common.url') }}:</label>
                                        @if (isset($Cmspage->alias->varAlias) && !$userIsAdmin)
                                            {{ url('/' . $Cmspage->alias->varAlias) }}
                                        @else
                                            @if (auth()->user()->can('pages-create'))
                                                <a href="javascript:void;" class="alias">{!! url('/') !!}</a>
                                                @if (isset($Cmspage->alias) && $Cmspage->alias->varAlias != 'home')
                                                    <a href="javascript:void(0);" class="editAlias ms-1 me-1 fs-16" title="{{ trans('cmspage::template.common.edit') }}"><i class="ri-pencil-line"></i></a>
                                                    <a href="javascript:void(0);" class="without_bg_icon openLink fs-16" title="{{ trans('cmspage::template.common.openLink') }}" onClick="generatePreview('{{ url('/previewpage?url=' . url('/' . $Cmspage->alias->varAlias)) }}');"><i class="ri-link-m" aria-hidden="true"></i></a>
                                                @elseif(!isset($Cmspage->alias))
                                                    <a href="javascript:void(0);" class="editAlias ms-1 me-1 fs-16" title="{{ trans('cmspage::template.common.edit') }}"><i class="ri-pencil-line"></i></a>&nbsp;
                                                    <a href="javascript:void(0);" class="without_bg_icon openLink fs-16" title="{{ trans('cmspage::template.common.openLink') }}" onClick="generatePreview('{{ url('/previewpage?url=' . url('/')) }}');">
                                                        <i class="ri-link-m" aria-hidden="true"></i>
                                                    </a>
                                                @endif
                                                @if (isset($Cmspage->alias) && $Cmspage->alias->varAlias == 'home')
                                                    <a href="javascript:void(0);" class="without_bg_icon openLink fs-16" title="{{ trans('cmspage::template.common.openLink') }}" onClick="generatePreview('{{ url('/previewpage?url=' . url('/')) }}');">
                                                        <i class="ri-link-m" aria-hidden="true"></i>
                                                    </a>
                                                @endif
                                            @endif
                                        @endif
                                        <span class="help-block">{{ $errors->first('alias') }}</span>
                                        <!-- code for alias -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{--    Title & alias   - End    --}}

                        {{-- Start date - End date --}}
                        @php $defaultDt = (null !== old('start_date_time'))?old('start_date_time'):date(Config::get('Constant.DEFAULT_DATE_FORMAT').' '.Config::get('Constant.DEFAULT_TIME_FORMAT')); @endphp
                        <div class="col-lg-3 col-sm-6">
                            <div class="form-md-line-input cm-floating">
                                @php if(isset($Cmspage_highLight->dtDateTime) && ($Cmspage_highLight->dtDateTime != $Cmspage->dtDateTime)){
                                    $Class_date = " highlitetext";
                                }else{
                                    $Class_date = "";
                                } @endphp
                                <label class="control-label form-label text-capitalize {!! $Class_date !!}">{{ trans('cmspage::template.common.startDateAndTime') }}<span aria-required="true" class="required"> * </span></label>
                                <div class="input-group date form_meridian_datetime @if($errors->first('start_date_time')) has-error @endif" data-date="{{ Carbon\Carbon::today()->format('Y-m-d') }}T15:25:00Z">
                                    <!-- <span class="input-group-text"><i class="ri-calendar-fill"></i></span> -->
                                    {!! Form::text('start_date_time', date(Config::get('Constant.DEFAULT_DATE_FORMAT').' '.Config::get('Constant.DEFAULT_TIME_FORMAT'),strtotime(isset($Cmspage->dtDateTime)?$Cmspage->dtDateTime:$defaultDt)), array('class' => 'form-control', 'data-date-format' => Config::get('Constant.DEFAULT_DATE_FORMAT'), 'data-provider' => 'flatpickr', 'data-enable-time' => '', "allowInput" => true,'maxlength'=>160,'size'=>'16','id'=>'start_date_time','autocomplete'=>'off','onkeypress'=>"javascript: return KeycheckOnlyDate(event);",'onpaste'=>'return false')) !!}
                                </div>
                                <span class="help-block">{{ $errors->first('start_date_time') }}</span>
                            </div>
                        </div>

                        @php $defaultDt = (null !== old('end_date_time'))?old('end_date_time'):null; @endphp
                        @if ((isset($Cmspage->dtEndDateTime)==null))
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
                        <div class="col-lg-3 col-sm-6">
                            <div class="form-md-line-input">
                                @php if(isset($Cmspage_highLight->dtEndDateTime) && ($Cmspage_highLight->dtEndDateTime != $Cmspage->dtEndDateTime)){
                                $Class_end_date = " highlitetext";
                                }else{
                                $Class_end_date = "";
                                } @endphp
                                <div class="cm-floating form_meridian_datetime expirydate @if($errors->first('end_date_time')) has-error @endif" data-date="{{ Carbon\Carbon::today()->format('Y-m-d') }}T15:25:00Z" @if ($expChecked_yes==1) style="display:none;" @endif>
                                    <label class="control-label form-label {!! $Class_end_date !!}" >{{ trans('cmspage::template.common.endDateAndTime') }} <span aria-required="true" class="required"> * </span></label>
                                    <div class="input-group date">
                                        <!-- <span class="input-group-text"><i class="ri-calendar-fill"></i></span> -->
                                        {!! Form::text('end_date_time', isset($Cmspage->dtEndDateTime)?date(Config::get('Constant.DEFAULT_DATE_FORMAT').' '.Config::get('Constant.DEFAULT_TIME_FORMAT'),strtotime($Cmspage->dtEndDateTime)):$defaultDt, array('class' => 'form-control', 'data-provider' => 'flatpickr', 'data-date-format' => Config::get('Constant.DEFAULT_DATE_FORMAT'), 'data-enable-time' => '','maxlength'=>160,'size'=>'16','id'=>'end_date_time','data-exp'=> $expChecked_yes,'data-newvalue','autocomplete'=>'off','onkeypress'=>"javascript: return KeycheckOnlyDate(event);",'onpaste'=>'return false')) !!}
                                    </div>
                                </div>
                                <span class="help-block">{{ $errors->first('end_date_time') }}</span>
                                <label class="expdatelabel {{ $expclass }} form-label m-0">
                                    <a id="noexpiry" name="noexpiry" href="javascript:void(0);">
                                        <b class="expiry_lbl {!! $Class_end_date !!}">Set Expiry</b>
                                    </a>
                                </label>
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
                            <div class="@if($errors->first('description')) has-error @endif form-md-line-input">
                                @if (Config::get('Constant.DEFAULT_VISUAL') == 'Y')
                                    <div id="body-roll">
                                        @php $sections = []; @endphp
                                        @if(isset($Cmspage))
                                            @php $sections = json_decode($Cmspage->txtDescription); @endphp
                                        @endif
                                        <!-- Builder include -->
                                        @php Powerpanel\VisualComposer\Controllers\VisualComposerController::page_section(['sections'=>$sections]) @endphp
                                    </div>
                                @else
                                    @php if(isset($Cmspage_highLight->txtDescription) && ($Cmspage_highLight->txtDescription != $Cmspage->txtDescription)){
                                    $Class_Description = " highlitetext";
                                    }else{
                                    $Class_Description = "";
                                    } @endphp
                                    <h4 class="form-section mb-3 form-label {!! $Class_Description !!}">{{ trans('cmspage::template.common.description') }}</h4>
                                    {!! Form::textarea('description', isset($Cmspage->txtDescription)?$Cmspage->txtDescription:old('description'), array('class' => 'form-control','id'=>'txtDescription')) !!}
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
                            @if (isset($Cmspage->intSearchRank))
                                @php $srank = $Cmspage->intSearchRank; @endphp
                            @else
                                @php $srank = null !== old('search_rank') ? old('search_rank') : 2; @endphp
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
                                    <strong>Note: </strong> {{ trans('cmspage::template.common.SearchEntityTools') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- SEO Information --}}
            <div class="card">
                <div class="card-body p-30">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3 {{ $errors->has('display') ? ' has-error' : '' }} ">
                                @if (isset($Cmspage_highLight->intSearchRank) && $Cmspage_highLight->intSearchRank != $Cmspage->intSearchRank)
                                    @php $Class_intSearchRank = " highlitetext"; @endphp
                                @else
                                    @php $Class_intSearchRank = ""; @endphp
                                @endif
                                @php  $form = 'frmCmsPage';  @endphp
                                @include('powerpanel.partials.seoInfo',['form'=> 'frmCmsPage', 'inf'=>isset($metaInfo)?$metaInfo:false, 'inf_highLight'=>isset($metaInfo_highLight)?$metaInfo_highLight:false])
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-body p-30">
                    <div class="row">
                        @if (isset($Cmspage) && isset($Cmspage->alias->varAlias) && $Cmspage->alias->varAlias == 'home')
                            {!! Form::hidden('chrMenuDisplay', 'Y') !!}
                            {!! Form::hidden('chrPageActive', 'PU') !!}
                        @endif

                        @if (isset($publishActionDisplay))
                            <div class="col-lg-6 col-sm-12">
                                <h4 class="form-section mb-3">{{ trans('cmspage::template.common.displayinformation') }}</h4>
                                @if (isset($Cmspage_highLight->chrPublish) && $Cmspage_highLight->chrPublish != $Cmspage->chrPublish)
                                    @php $Class_chrPublish = " highlitetext"; @endphp
                                @else
                                    @php $Class_chrPublish = ""; @endphp
                                @endif

                                @if(isset($Cmspage) && $Cmspage->chrAddStar == 'Y')
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label class="form-label"> Publish/ Unpublish</label>
                                            <input type="hidden" id="chrMenuDisplay" name="chrMenuDisplay" value="{{ isset($Cmspage->chrPublish) ? $Cmspage->chrPublish : '' }}">
                                            <p><b>NOTE:</b> This record is in Approval Request , so it can&#39;t be published/unpublished.</p>
                                        </div>
                                    </div>
                                @elseif(isset($Cmspage) && $Cmspage->chrDraft == 'D' &&   $Cmspage->chrAddStar != 'Y')
                                    @include('powerpanel.partials.displayInfo',['Class_chrPublish'=>$Class_chrPublish,'display'
                                    => (isset($Cmspage->chrDraft)?$Cmspage->chrDraft:'D')])
                                @elseif(isset($countmenu) && $countmenu > 0)
                                    <label class="form-label"> Publish/ Unpublish</label>
                                    <p><b>NOTE:</b> This page is assigned to menu so can't be published/ unpublished.</p>
                                    <input type="hidden" value="Y" name="chrMenuDisplay">
                                    @elseif(isset($Cmspage->intFKModuleCode) && $Cmspage->intFKModuleCode != '3')
                                    <label class="form-label"> Publish/ Unpublish</label>
                                    <p><b>NOTE:</b> This page is assigned to module so can't be published/ unpublished.</p>
                                    <input type="hidden" value="Y" name="chrMenuDisplay">
                                    @elseif(isset($Cmspage->intFKModuleCode) && $Cmspage->intFKModuleCode != '3' && isset($countmenu) && $countmenu > 0)
                                    <label class="form-label"> Publish/ Unpublish</label>
                                    <p><b>NOTE:</b> This page is assigned to menu and module so can't be published/ unpublished.</p>
                                    <input type="hidden" value="Y" name="chrMenuDisplay">
                                @else
                                    @include('powerpanel.partials.displayInfo',['Class_chrPublish'=>$Class_chrPublish,'display' => (isset($Cmspage->chrPublish)?$Cmspage->chrPublish:'Y')])
                                @endif
                            </div>
                        @endif

                        @if (Config::get('Constant.DEFAULT_VISIBILITY') == 'Y')
                            @if (isset($publishActionDisplay))
                                @if (isset($Cmspage->chrPageActive))
                                    @php $srank1 = $Cmspage->chrPageActive; @endphp
                                @else
                                    @php
                                        $srank1 = null !== old('chrPageActive') ? old('chrPageActive') : 'PU';
                                    @endphp
                                @endif
                                @if (isset($Cmspage_highLight->chrPageActive) && $Cmspage_highLight->chrPageActive != $Cmspage->chrPageActive)
                                    @php $Class_chrPageActive = " highlitetext"; @endphp
                                @else
                                    @php $Class_chrPageActive = ""; @endphp
                                @endif
                                <div class="col-lg-6 col-sm-12">
                                    <h4 class="form-section mb-3 {{ $Class_chrPageActive }}">Visibility</h4>
                                    {{-- <label class="{{ $Class_chrPageActive }} form-label">Visibility</label> --}}
                                    <div class="md-radio-inline mb-3">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" value="PU" name="chrPageActive" id="chrPageActivePU" @if ($srank1 == 'PU') checked @endif>
                                            <label for="chrPageActivePU" onclick="OpenPassword('PU')">Public</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" value="PR" name="chrPageActive" id="chrPageActivePR" @if ($srank1 == 'PR') checked @endif>
                                            <label for="chrPageActivePR" onclick="OpenPassword('PR')">Private</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" value="PP" name="chrPageActive" id="chrPageActivePP" @if ($srank1 == 'PP') checked @endif>
                                            <label for="chrPageActivePP" onclick="OpenPassword('PP')">Password Protected</label>
                                        </div>
                                    </div>
                                    <div class="toggle"></div>

                                    <div class="d-xl-flex flex-wrap align-items-start">
                                        <div class="flex-shrink-0 me-3">
                                            @php
                                            $share = '';
                                            if(isset($Cmspage->chrPageActive) && $Cmspage->chrPageActive == 'PU') {
                                                $share = 'style=display:none';
                                            }
                                            @endphp
                                            <button type="button" name="share" id="sharepage" class="btn btn-primary bg-gradient waves-effect waves-light btn-label mb-30" {{ $share }}>
                                                <div class="flex-shrink-0">
                                                    <i class="ri-share-line label-icon align-middle me-2"></i>
                                                </div>Share
                                            </button>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="alert alert-sm alert-success alert-dismissible fade show" id="shareSuccess" role="alert" style="display: none;"></div>
                                            <div class="alert alert-sm alert-danger alert-dismissible fade show" id="shareError" role="alert" style="display: none;"></div>
                                        </div>
                                        {{-- <div class="flex-grow-1">
                                            @if (!empty($Cmspage->varPassword) && $Cmspage->chrPageActive == 'PP')
                                                @php  $password = ''; @endphp
                                            @else
                                                @php $password = 'style=display:none'; @endphp
                                            @endif
                                            <div id='passid' {{ $password }}>
                                                <div class="{{ $errors->has('varPassword') ? ' has-error' : '' }} form-md-line-input cm-floating">
                                                    @php
                                                    if (isset($Cmspage_highLight->varPassword) && $Cmspage_highLight->varPassword != $Cmspage->varPassword) {
                                                            $Class_varPassword = ' highlitetext';
                                                    } else {
                                                            $Class_varPassword = '';
                                                    } @endphp
                                                    <label for="newpassword" class="form_varPassword form-label {!! $Class_varPassword !!}">Password <span aria-required="true" class="required"> * </span></label>
                                                    {!! Form::text('new_password', isset($Cmspage->varPassword) ? $Cmspage->varPassword : old('new_password'), ['autocomplete' => 'off', 'maxlength' => 20, 'class' => 'form-control', 'id' => 'newpassword']) !!}
                                                    <span style="color: red;">
                                                        {{ $errors->first('new_password') }}
                                                    </span>
                                                    <div class="pswd_info" id="newpassword_info">
                                                        <h4>Password must meet the following requirements:</h4>
                                                        <ul class="tooltiptext">
                                                            <li id="letter" class="letterinfo invalid">At least <strong>one letter</strong></li>
                                                            <li id="capital" class="capitalletterinfo invalid">At least <strong>one capital letter</strong></li>
                                                            <li id="number" class="numberinfo invalid">At least <strong>one number</strong></li>
                                                            <li id="length" class="lengthInfo invalid">Password should be <strong>6 to 20 characters</strong></li>
                                                            <li id="special" class="specialinfo invalid">At least <strong>one special character</strong></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> --}}
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>
                    <span style="color: red;">{{ $errors->first('display') }}</span>
                    
                    {{-- Save Buttons - Start --}}
                    <div class="form-actions btn-bottom">
                        <div class="row">
                            <div class="col-md-12">
                                @if (isset($Cmspage->fkMainRecord) && $Cmspage->fkMainRecord != 0)
                                    <button type="submit" name="saveandexit" formmethod="post" class="btn btn-secondary bg-gradient waves-effect waves-light btn-label me-1" value="saveandexit">
                                        <div class="flex-shrink-0">
                                            <i class="ri-check-line label-icon align-middle fs-20 me-2"></i>
                                        </div>
                                        {!! trans('cmspage::template.common.approve') !!}
                                    </button>
                                @else
                                    @if ($userIsAdmin)
                                        <button type="submit" name="saveandedit" formmethod="post" class="btn btn-primary bg-gradient waves-effect waves-light btn-label me-1" value="saveandedit">
                                            <div class="flex-shrink-0">
                                                <i class="ri-save-line label-icon align-middle fs-20 me-2"></i>
                                            </div>
                                            {!! trans('cmspage::template.common.saveandedit') !!}
                                        </button>
                                        <button type="submit" name="saveandexit" formmethod="post" class="btn btn-secondary bg-gradient waves-effect waves-light btn-label me-1" value="saveandexit">
                                            <div class="flex-shrink-0">
                                                <i class="ri-save-line label-icon align-middle fs-20 me-2"></i>
                                            </div>
                                            {!! trans('cmspage::template.common.saveandexit') !!}
                                        </button>
                                        <button type="submit" name="saveandmenu" formmethod="post" class="btn btn-primary bg-gradient waves-effect waves-light btn-label me-1" value="saveandmenu">
                                            <div class="flex-shrink-0">
                                                <i class="ri-save-line label-icon align-middle fs-20 me-2"></i>
                                            </div>
                                            Save and Assign to Menu
                                        </button>
                                    @else
                                        @if (isset($chrNeedAddPermission) && $chrNeedAddPermission == 'N' && (isset($charNeedApproval) && $charNeedApproval == 'N'))
                                            <button type="submit" name="saveandexit" formmethod="post" class="btn btn-secondary bg-gradient waves-effect waves-light btn-label me-1" value="saveandexit">
                                                <div class="flex-shrink-0">
                                                    <i class="ri-save-3-line label-icon align-middle fs-20 me-2"></i>
                                                </div>
                                                {!! trans('cmspage::template.common.saveandexit') !!}
                                            </button>
                                        @else
                                            <button type="submit" name="saveandexit" formmethod="post" class="btn btn-secondary bg-gradient waves-effect waves-light btn-label me-1" value="approvesaveandexit">
                                                <div class="flex-shrink-0">
                                                    <i class="ri-save-line label-icon align-middle fs-20 me-2"></i>
                                                </div>
                                                {!! trans('cmspage::template.common.approvesaveandexit') !!}
                                            </button>
                                        @endif
                                    @endif
                                @endif
                                @php
                                    if (isset($_REQUEST['tab']) && $_REQUEST['tab'] == 'P') {
                                        $tab = '?tab=P';
                                    } elseif (isset($_REQUEST['tab']) && $_REQUEST['tab'] == 'A') {
                                        $tab = '?tab=A';
                                    } elseif (isset($_REQUEST['tab']) && $_REQUEST['tab'] == 'D') {
                                        $tab = '?tab=D';
                                    } elseif (isset($_REQUEST['tab']) && $_REQUEST['tab'] == 'T') {
                                        $tab = '?tab=T';
                                    } else {
                                        $tab = '';
                                    }
                                @endphp
                                <a class="btn btn-danger bg-gradient waves-effect waves-light btn-label" href="{{ url('powerpanel/pages') }}">
                                    <div class="flex-shrink-0">
                                        <i class="ri-close-line label-icon align-middle fs-20 me-2"></i>
                                    </div>
                                    {{ trans('cmspage::template.common.cancel') }}
                                </a>
    
                            </div>
                        </div>
                    </div>
                    {{-- Save Buttons - End --}}
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

<!-- Share Modal -->
<div class="modal fade" id="sharepageModel" tabindex="-1" aria-labelledby="sharepageModel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sharepageModel">Share Page Access</h5>
                <button type="button" class="btn-close fs-10" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            {!! Form::open(['method' => 'post','class'=>'sharePageForm','id'=>'sharePageForm']) !!}
            {!! Form::hidden('pageId',$pageId,array('id' => 'pageId')) !!}
            {!! Form::hidden('privateLink',isset($metaInfo['privateLink'])?$metaInfo['privateLink']:'',array('id' => 'privateLink')) !!}
            {!! Form::hidden('aliasId','',array('id' => 'aliasId')) !!}
            {!! Form::hidden('sectorType','',array('id' => 'sectorType')) !!}
            {!! Form::hidden('pageActive','',array('id' => 'pageActive')) !!}
            {!! Form::hidden('seoLink','',array('id' => 'seoLink')) !!}
            <div class="modal-body">
                <div class="mb-4">
                    <label for="link" class="form-label m-0">Link:</label>
                    <a href="javascript:void(0);" class="link" id="seo_link"></a>
                </div>
                <div class="cm-floating">
                    <label for="email">Email: <span aria-required="true" class="required"> * </span></label>
                    {!! Form::text('email',  '', array('id' => 'email', 'class' => 'form-control')) !!}
                </div>
                <div id="password_div">
                    <div class="{{ $errors->has('varPassword') ? ' has-error' : '' }} form-md-line-input cm-floating">
                        <label for="newpassword" class="form_varPassword form-label">Password <span aria-required="true" class="required"> * </span></label>
                        {!! Form::text('password', isset($Cmspage->varPassword) ? $Cmspage->varPassword : old('password'), ['autocomplete' => 'off', 'maxlength' => 20, 'class' => 'form-control', 'id' => 'newpassword']) !!}
                        <span style="color: red;">{{ $errors->first('password') }}</span>
                        <div class="pswd_info" id="newpassword_info">
                            <h4>Password must meet the following requirements:</h4>
                            <ul class="tooltiptext">
                                <li id="letter" class="letterinfo invalid">At least <strong>one letter</strong></li>
                                <li id="capital" class="capitalletterinfo invalid">At least <strong>one capital letter</strong></li>
                                <li id="number" class="numberinfo invalid">At least <strong>one number</strong></li>
                                <li id="length" class="lengthInfo invalid">Password should be <strong>6 to 20 characters</strong></li>
                                <li id="special" class="specialinfo invalid">At least <strong>one special character</strong></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="sub-btn">
                    <button type="submit" class="btn btn-primary bg-gradient waves-effect waves-light btn-label" id="share_submit" value="saveandexit">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="ri-share-line label-icon align-middle fs-20 me-2"></i>
                            </div>
                            <div class="flex-grow-1">Share</div>
                        </div>
                    </button>
                </div>
            </div>

            {!! Form::close() !!}
        </div>
    </div>
</div>

@endsection


@section('scripts')
    <script type="text/javascript">
        window.site_url = '{!! url('/') !!}';
        var seoFormId = 'frmCmsPage';
        var user_action = "{{ isset($Cmspage) ? 'edit' : 'add' }}";
        var moduleAlias = '';
        var preview_add_route = '{!! route('powerpanel.pages.addpreview') !!}';
        var previewForm = $('#frmCmsPage');
        var isDetailPage = false;
        var sharePageURL = '{!! route('powerpanel.pages.sharepage') !!}';

        function generate_seocontent1(formname) {
            var Meta_Title = document.getElementById('title').value + "";
            var abcd = $('textarea#txtDescription').val();
            if (abcd != undefined) {
                var def = abcd.replace(/<a(\s[^>]*)?>.*?<\/a>/ig, "");
                var abc = def.replace(/^(\s*)|(\s*)$/g, '').replace(/\s+/g, ' ');
                var outString1 = abc.replace(/(<([^>]+)>)/ig, "");
                var Meta_Description = outString1.substr(0, 200);
            } else {
                var Meta_Description = document.getElementById('title').value + "";
            }

            var Meta_Keyword = document.getElementById('title').value + "" + document.getElementById('title').value;
            $('#varMetaTitle').val(Meta_Title);
            $('#varMetaDescription').val(Meta_Description);
            $('#meta_title').html(Meta_Title);
            $('#meta_description').html(Meta_Description);
        }
    </script>

    <script src="{{ $CDN_PATH . 'resources/global/plugins/bootstrap-taginput/bootstrap-tagsinput.min.js' }}" type="text/javascript"></script>
    <script src="{{ $CDN_PATH . 'resources/pages/scripts/visual_composer-ajax.js' }}" type="text/javascript"></script>
    <script src="{{ $CDN_PATH . 'messages.js' }}" type="text/javascript"></script>
    <script src="{{ $CDN_PATH . 'resources/pages/scripts/packages/cmspage/cmspages_validations.js' }}" type="text/javascript"></script>
    <script src="{{ $CDN_PATH . 'resources/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js' }}" type="text/javascript"></script>
    <script type="text/javascript">
        $('select[name=module]').on("change", function(e) {
            $("select[name=module]").closest('.has-error').removeClass('has-error');
            $("#module-error").remove();
        });
    </script>
    <script type="text/javascript">
        function OpenPassword(val) {
            // if (val == 'PP') {
            //     $("#passid").show();
            // } else {
            //     $("#passid").hide();
            // }

            if (val == 'PR' || val == 'PP') {
                $("#sharepage").show();
            } else {
                $("#sharepage").hide();
            }
        }
    </script>
    <script src="{{ $CDN_PATH . 'resources/pages/scripts/custom.js' }}" type="text/javascript"></script>
    <script src="{{ $CDN_PATH . 'resources/global/plugins/custom-alias/cms-alias-generator.js' }}" type="text/javascript"></script>
    <script src="{{ $CDN_PATH . 'resources/global/plugins/seo-generator/seo-info-generator.js' }}" type="text/javascript"></script>
    <script src="{{ Config::get('Constant.CDN_PATH') . 'resources/pages/scripts/pages_password_rules.js' }}" type="text/javascript"></script>
    @if (Config::get('Constant.DEFAULT_VISUAL') == 'Y')
        @php Powerpanel\VisualComposer\Controllers\VisualComposerController::get_builder_css_js(); @endphp
    @endif
@endsection
