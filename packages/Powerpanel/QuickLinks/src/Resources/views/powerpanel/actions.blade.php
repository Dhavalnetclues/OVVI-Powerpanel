@section('css')
<link href="{{ $CDN_PATH.'resources/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css' }}" rel="stylesheet" type="text/css" />
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
        
        <div class="card">
            <div class="card-body">
                <div class="live-preview">
                    {!! Form::open(['method' => 'post','enctype' => 'multipart/form-data','id'=>'frmQuicklinks']) !!}
                        {!! Form::hidden('fkMainRecord', isset($quickLinks->fkMainRecord)?$quickLinks->fkMainRecord:old('fkMainRecord')) !!}
                        <div class="form-body">
                            @if(isset($quickLinks))
                            @if (File::exists(base_path() . '/resources/views/powerpanel/partials/lockedpage.blade.php') != null)
                            @include('powerpanel.partials.lockedpage',['pagedata'=>$quickLinks])
                            @endif
                            @endif

                            <!-- Sector type -->
                            <div class="mb-3 @if($errors->first('sector')) has-error @endif form-md-line-input">
                                @if(isset($quickLinksHighLight->varSector) && ($quickLinksHighLight->varSector != $quickLinks->varSector))
                                    @php $Class_varSector = " highlitetext"; @endphp
                                @else
                                    @php $Class_varSector = ""; @endphp
                                @endif
                                @include('shiledcmstheme::powerpanel.partials.sector-dropdown', ['selected_sector' => isset($quickLinks->varSector)?$quickLinks->varSector:'','Class_varSector' => $Class_varSector])
                                <span class="help-block">
                                    {{ $errors->first('sector') }}
                                </span>
                            </div>

                            @if(isset($quickLinksHighLight->varTitle) && ($quickLinksHighLight->varTitle != $quickLinks->varTitle))
                                @php $Class_title = " highlitetext"; @endphp
                            @else
                                @php $Class_title = ""; @endphp
                            @endif
                            <div class="mb-3 {{ $errors->has('title') ? ' has-error' : '' }} form-md-line-input">
                                <label class="form-label {{ $Class_title }}" for="title">{!! trans('quick-links::template.common.title') !!} <span aria-required="true" class="required"> * </span></label>
                                {!! Form::text('title', isset($quickLinks->varTitle)?$quickLinks->varTitle:old('title'), array('maxlength' => 150,'class' => 'form-control input-sm maxlength-handler titlespellingcheck', 'data-url' => 'powerpanel/quick-links','id' => 'title','placeholder' => trans('quick-links::template.common.title'),'autocomplete'=>'off')) !!}
                                <span style="color:#e73d4a">
                                    {{ $errors->first('title') }}
                                </span>
                            </div>
                            @if ((isset($quickLinks->varLinkType) && $quickLinks->varLinkType == 'external') || old('link_type') == 'external')
                            @php $checked_yes = 'checked' @endphp
                            @else
                            @php $checked_yes = '' @endphp
                            @endif
                            @if ((isset($quickLinks->varLinkType) && $quickLinks->varLinkType == 'internal') || old('link_type') == 'internal' || (!isset($quickLinks->varLinkType) && old('link_type') == null))
                            @php $ichecked_innerbaner_yes = 'checked' @endphp
                            @else
                            @php $ichecked_innerbaner_yes = '' @endphp
                            @endif
                            <div class="mb-3 {{ $errors->has('link_type') ? ' has-error' : '' }}">
                                @if(isset($quickLinksHighLight->varLinkType) && ($quickLinksHighLight->varLinkType != $quickLinks->varLinkType))
                                @php $Class_varLinkType = " highlitetext"; @endphp
                                @else
                                @php $Class_varLinkType = ""; @endphp
                                @endif
                                <label class="form-label {{ $Class_varLinkType }}" for="link_type">{!! trans('quick-links::template.quickLinkModule.linkType') !!} <span aria-required="true" class="required"> * </span></label>
                                <div class="md-radio-inline">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" value="external" name="link_type" id="external_linktype" {{ $checked_yes }}>
                                        <label for="external_linktype">{!! trans('quick-links::template.quickLinkModule.external') !!}</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" value="internal" name="link_type" id="internal_linktype" {{ $ichecked_innerbaner_yes }}>
                                        <label for="internal_linktype">{!! trans('quick-links::template.quickLinkModule.internal') !!}</label>
                                    </div>
                                </div>
                                <span class="help-block">
                                    <strong>{{ $errors->first('link_type') }}</strong>
                                </span>
                            </div>
                            <div class="mb-3" id="pages" style="display: none;">
                                @if(isset($quickLinksHighLight->fkModuleId) && ($quickLinksHighLight->fkModuleId != $quickLinks->fkModuleId))
                                @php $Class_fkModuleId = " highlitetext"; @endphp
                                @else
                                @php $Class_fkModuleId = ""; @endphp
                                @endif
                                <label class="form-label {{ $Class_fkModuleId }}" for="pages">{!! trans('quick-links::template.common.selectmodule') !!} <span aria-required="true" class="required"> * </span></label>
                                <select class="form-control" name="modules" id="modules" data-choices>
                                    <option value="">{!! trans('quick-links::template.common.selectmodule') !!}</option>
                                    @if(count($modules) > 0)
                                    @foreach ($modules as $pagedata)
                                    @php
                                    $avoidModules = array('faq','contact-us','sitemap','banksupervision','links-category','blogs','blog-category','news-category','career-category');
                                    
                                    @endphp
                                    @if (ucfirst($pagedata->varTitle)!='Home' && !in_array($pagedata->varModuleName,$avoidModules) && Auth::user()->can($pagedata['varModuleName'] . '-list'))
                                    <option data-model="{{ $pagedata->varModelName }}" data-module="{{ $pagedata->varModuleName }}" value="{{ $pagedata->id }}" {{ (isset($quickLinks->fkModuleId) && $pagedata->id == $quickLinks->fkModuleId) || $pagedata->id == old('modules')? 'selected' : '' }} >{{ $pagedata->varTitle }}</option>
                                    @endif
                                    @endforeach
                                    @endif
                                </select>
                                <span style="color:#e73d4a">
                                    {{ $errors->first('modules') }}
                                </span>
                            </div>
                            <div class="mb-3" id="records" style="display: none;">
                                @if(isset($quickLinksHighLight->fkIntPageId) && ($quickLinksHighLight->fkIntPageId != $quickLinks->fkIntPageId))
                                @php $Class_fkIntPageId = " highlitetext"; @endphp
                                @else
                                @php $Class_fkIntPageId = ""; @endphp
                                @endif
                                <label class="form-label {{ $Class_fkIntPageId }}" for="pages">{!! trans('quick-links::template.quickLinkModule.selectPage') !!}<span aria-required="true" class="required"> * </span></label>
                                <select class="form-control" name="foritem" id="foritem" style="width:100%" data-choices>
                                    <option value="">{!! trans('quick-links::template.quickLinkModule.selectPage') !!}</option>
                                </select>
                                <span style="color:#e73d4a">
                                    {{ $errors->first('foritem') }}
                                </span>
                            </div>
                            <div class="mb-3 {{ $errors->has('ext_Link') ? ' has-error' : '' }} form-md-line-input" id="ext_Link_div">
                                @if(isset($quickLinksHighLight->varExtLink) && ($quickLinksHighLight->varExtLink != $quickLinks->varExtLink))
                                @php $Class_varExtLink = " highlitetext"; @endphp
                                @else
                                @php $Class_varExtLink = ""; @endphp
                                @endif
                                <label class="form-label {{ $Class_varExtLink }}" for="ext_Link">{!! trans('quick-links::template.quickLinkModule.extLink') !!}<span aria-required="true" class="required"> * </span></label>
                                {!! Form::text('ext_Link', isset($quickLinks->varExtLink)?$quickLinks->varExtLink:old('ext_Link'), array('class' => 'form-control input-sm', 'data-url' => 'powerpanel/quick-links','id' => 'ext_Link','placeholder' => 'External Link','autocomplete'=>'off')) !!}
                                <span style="color:#e73d4a">
                                    {{ $errors->first('ext_Link') }}
                                </span>
                            </div>
                            @if(Config::get('Constant.CHRSearchRank') == 'Y')
                            @if(isset($quickLinks->intSearchRank))
                            @php $srank = $quickLinks->intSearchRank; @endphp
                            @else
                            @php
                            $srank = null !== old('search_rank') ? old('search_rank') : 2 ;
                            @endphp
                            @endif
                            @if(isset($quickLinksHighLight->intSearchRank) && ($quickLinksHighLight->intSearchRank != $quickLinks->intSearchRank))
                            @php $Class_intSearchRank = " highlitetext"; @endphp
                            @else
                            @php $Class_intSearchRank = ""; @endphp
                            @endif
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label class="{{ $Class_intSearchRank }} form-label">Search Ranking</label>
                                    <a href="javascript:;" data-bs-toggle="tooltip" class="config" data-bs-placement="bottom" data-bs-content="{{ trans('quick-links::template.common.SearchEntityTools') }}" title="{{ trans('template.common.SearchEntityTools') }}"><i class="fa fa-question"></i></a>
                                    <div class="md-radio-inline">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" value="1" name="search_rank" @if ($srank == 1) checked @endif id="yes_radio">
                                            <label for="yes_radio" id="yes-lbl">High</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" value="2" name="search_rank" @if ($srank == 2) checked @endif id="maybe_radio">
                                            <label for="maybe_radio" id="maybe-lbl">Medium</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" value="3" name="search_rank" @if ($srank == 3) checked @endif id="no_radio">
                                            <label for="no_radio" id="no-lbl">Low</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            
                            <h3 class="form-section">{{ trans('quick-links::template.common.ContentScheduling') }}</h3>
                            @php $defaultDt = (null !== old('start_date_time'))?old('start_date_time'):date('Y-m-d H:i'); @endphp
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3 form-md-line-input">
                                        @php if(isset($quickLinksHighLight->dtDateTime) && ($quickLinksHighLight->dtDateTime != $quickLinks->dtDateTime)){
                                        $Class_date = " highlitetext";
                                        }else{
                                        $Class_date = "";
                                        } @endphp
                                        <label class="control-label form-label {!! $Class_date !!}">{{ trans('quick-links::template.common.startDateAndTime') }}<span aria-required="true" class="required"> * </span></label>
                                        <div class="input-group date form_meridian_datetime @if($errors->first('start_date_time')) has-error @endif" data-date="{{ Carbon\Carbon::today()->format('Y-m-d') }}T15:25:00Z">
                                            <span class="input-group-text date_default" id="basic-addon1">
                                                <i class="ri-calendar-fill"></i>
                                            </span>
                                            {!! Form::text('start_date_time', date(Config::get('Constant.DEFAULT_DATE_FORMAT').' '.Config::get('Constant.DEFAULT_TIME_FORMAT'),strtotime(isset($quickLinks->dtDateTime)?$quickLinks->dtDateTime:$defaultDt)), array('class' => 'form-control', 'data-provider' => 'flatpickr', 'data-date-format' => Config::get('Constant.DEFAULT_DATE_FORMAT'), 'data-enable-time' => '','maxlength'=>160,'size'=>'16','id'=>'quickLinks_start_date','autocomplete'=>'off','onkeypress'=>"javascript: return KeycheckOnlyDate(event);",'onpaste'=>'return false')) !!}
                                        </div>
                                        <span class="help-block">{{ $errors->first('start_date_time') }}</span>
                                    </div>
                                </div>
                                @php $defaultDt = (null !== old('end_date_time'))?old('end_date_time'):null; @endphp
                                @if ((isset($quickLinks->dtEndDateTime)==null))
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
                                    <div class="mb-3 form-md-line-input">
                                        @php if(isset($quickLinksHighLight->varTitle) && ($quickLinksHighLight->dtEndDateTime != $quickLinks->dtEndDateTime)){
                                        $Class_end_date = " highlitetext";
                                        }else{
                                        $Class_end_date = "";
                                        } @endphp
                                        <div class=" form_meridian_datetime expirydate @if($errors->first('end_date_time')) has-error @endif" data-date="{{ Carbon\Carbon::today()->format('Y-m-d') }}T15:25:00Z" @if ($expChecked_yes==1) style="display:none;" @endif>
                                            <label class="form-label {!! $Class_end_date !!}">{{ trans('quick-links::template.common.endDateAndTime') }} <span aria-required="true" class="required"> * </span></label>
                                            
                                            <div class="input-group date">
                                                <span class="input-group-text"><i class="ri-calendar-fill"></i></span>
                                                {!! Form::text('end_date_time', isset($quickLinks->dtEndDateTime)?date(Config::get('Constant.DEFAULT_DATE_FORMAT').' '.Config::get('Constant.DEFAULT_TIME_FORMAT'),strtotime($quickLinks->dtEndDateTime)):$defaultDt, array('class' => 'form-control', 'data-provider' => 'flatpickr', 'data-date-format' => Config::get('Constant.DEFAULT_DATE_FORMAT'), 'data-enable-time' => '','maxlength'=>160,'size'=>'16','id'=>'quickLinks_end_date','data-exp'=> $expChecked_yes,'data-newvalue','autocomplete'=>'off','onkeypress'=>"javascript: return KeycheckOnlyDate(event);",'onpaste'=>'return false')) !!}
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
                            
                            <h3 class="form-section">{!! trans('quick-links::template.common.displayinformation') !!}</h3>
                            <div class="row">
                                <div class="col-md-6">
                                    @php
                                    $display_order_attributes = array('class' => 'form-control','autocomplete'=>'off','maxlength'=>'5');
                                    @endphp
                                    <div class="mb-3 @if($errors->first('display_order')) has-error @endif form-md-line-input">
                                        @if(isset($quickLinksHighLight->intDisplayOrder) && ($quickLinksHighLight->intDisplayOrder != $quickLinks->intDisplayOrder))
                                        @php $Class_intDisplayOrder = " highlitetext"; @endphp
                                        @else
                                        @php $Class_intDisplayOrder = ""; @endphp
                                        @endif
                                        <label class="form-label {{ $Class_intDisplayOrder }}" for="display_order">{!! trans('quick-links::template.common.displayorder') !!} <span aria-required="true" class="required"> * </span></label>
                                        {!! Form::text('display_order',isset($quickLinks->intDisplayOrder)?$quickLinks->intDisplayOrder:1, $display_order_attributes) !!}
                                        <span class="help-block">
                                            <strong>{{ $errors->first('display_order') }}</strong>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    @if(isset($quickLinksHighLight->chrPublish) && ($quickLinksHighLight->chrPublish != $quickLinks->chrPublish))
                                        @php $Class_chrPublish = " highlitetext"; @endphp
                                    @else
                                        @php $Class_chrPublish = ""; @endphp
                                    @endif

                                    @if(isset($quickLinks) && $quickLinks->chrAddStar == 'Y')
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="control-label form-label"> Publish/ Unpublish</label>
                                                <input type="hidden" id="chrMenuDisplay" name="chrMenuDisplay" value="{{ isset($quickLinks->chrPublish) ? $quickLinks->chrPublish : '' }}">
                                                <p><b>NOTE:</b> This record is in Approval Request , so it can&#39;t be published/unpublished.</p>
                                            </div>
                                        </div>
                                    @elseif(isset($quickLinks) && $quickLinks->chrDraft == 'D' && $quickLinks->chrAddStar != 'Y')
                                        @include('powerpanel.partials.displayInfo',['Class_chrPublish'=>$Class_chrPublish,'display' => (isset($quickLinks->chrDraft)?$quickLinks->chrDraft:'D')])
                                    @else
                                        @include('powerpanel.partials.displayInfo',['Class_chrPublish'=>$Class_chrPublish,'display' => (isset($quickLinks->chrPublish)?$quickLinks->chrPublish:'Y')])
                                    @endif
                                </div>
                            </div>
                            
                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-12">
                                        @if(isset($quickLinks->fkMainRecord) && $quickLinks->fkMainRecord != 0)
                                        <button type="submit" name="saveandexit" class="btn btn-primary" value="saveandexit">{!! trans('quick-links::template.common.approve') !!}</button>
                                        @else
                                        @if($userIsAdmin)
                                        <button type="submit" name="saveandedit" class="btn btn-primary" value="saveandedit">{!! trans('quick-links::template.common.saveandedit') !!}</button>
                                        <button type="submit" name="saveandexit" class="btn btn-primary" value="saveandexit">{!! trans('quick-links::template.common.saveandexit') !!}</button>
                                        @else
                                        @if((isset($chrNeedAddPermission) && $chrNeedAddPermission == 'N') && (isset($charNeedApproval) && $charNeedApproval == 'N'))
                                        <button type="submit" name="saveandexit" class="btn btn-primary" value="saveandexit">{!! trans('quick-links::template.common.saveandexit') !!}</button>
                                        @else
                                        <button type="submit" name="saveandexit" class="btn btn-primary" value="approvesaveandexit">{!! trans('quick-links::template.common.approvesaveandexit') !!}</button>
                                        @endif
                                        @endif
                                        @endif
                                        <a class="btn btn-danger" href="{{ url('powerpanel/quick-links') }}">{{ trans('quick-links::template.common.cancel') }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div><!--end row-->
@endsection
@section('scripts')
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js' }}" type="text/javascript"></script>
<script type="text/javascript">
window.site_url = '{!! url("/") !!}';
var selectedRecord = '{{ isset($quickLinks->fkIntPageId)?$quickLinks->fkIntPageId:' ' }}';
var user_action = "{{ isset($quickLinks)?'edit':'add' }}";
</script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/custom.js' }}" type="text/javascript"></script>>
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/quicklinks/quicklinks.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/seo-generator/seo-info-generator.js' }}" type="text/javascript"></script>
<script type="text/javascript">
$('#modules').select2({
    placeholder: "Select Module",
    width: '100%'
}).on("change", function (e) {
    $("#modules").closest('.has-error').removeClass('has-error');
    $("#modules-error").remove();
    $('#records').show();
});
$('#foritem').select2({
    placeholder: "Select Module",
    width: '100%'
}).on("change", function (e) {
    $("#foritem").closest('.has-error').removeClass('has-error');
    $("#foritem-error").remove();
});
jQuery(document).ready(function() {
    $('#quickLinks_start_date').datetimepicker({
        format: DEFAULT_DATE_FORMAT + ' ' + DEFAULT_TIME_FORMAT,
        onShow: function() {
            this.setOptions({})
        },
        scrollMonth: false,
        scrollInput: false
    });

    $('#quickLinks_end_date').datetimepicker({
        format: DEFAULT_DATE_FORMAT + ' ' + DEFAULT_TIME_FORMAT,
        onShow: function() {
            this.setOptions({})
        },
        scrollMonth: false,
        scrollInput: false
    });
});
</script>
@endsection