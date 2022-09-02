@section('css')
@endsection
@extends('powerpanel.layouts.app')
@section('title')
{{Config::get('Constant.SITE_NAME')}} - PowerPanel
@stop
@section('content')
@php $settings = json_decode(Config::get("Constant.MODULE.SETTINGS")); @endphp
@include('powerpanel.partials.breadcrumbs')
<div class="col-md-12 settings">
    <div class="row">
        @if(Session::has('message'))
        <div class="alert alert-success">
            <button class="close" data-close="alert"></button>
            {{ Session::get('message') }}
        </div>
        @endif
        <div class="portlet light bordered">
            <div class="portlet-body">
                <div class="tabbable tabbable-tabdrop">
                    <div class="tab-content">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="portlet-body form_pattern">
                                    {!! Form::open(['method' => 'post','id'=>'frmLinks']) !!}
                                    <div class="form-body">
                                        {!! Form::hidden('fkMainRecord', isset($links->fkMainRecord)?$links->fkMainRecord:old('fkMainRecord')) !!}
                                          @if(isset($links))
                                        @if (File::exists(base_path() . '/resources/views/powerpanel/partials/lockedpage.blade.php') != null)
                                        @include('powerpanel.partials.lockedpage',['pagedata'=>$links])
                                        @endif
                                        @endif

                                        <!-- Sector type -->
                                        <div class="form-group @if($errors->first('sector')) has-error @endif form-md-line-input">
                                            @if(isset($links_highLight->varSector) && ($links_highLight->varSector != $links->varSector))
                                                @php $Class_varSector = " highlitetext"; @endphp
                                            @else
                                                @php $Class_varSector = ""; @endphp
                                            @endif
                                            @include('shiledcmstheme::powerpanel.partials.sector-dropdown', ['selected_sector' => isset($links->varSector)?$links->varSector:'','Class_varSector' => $Class_varSector])
                                            <span class="help-block">
                                                {{ $errors->first('sector') }}
                                            </span>
                                        </div>

                                        <div class="form-group @if($errors->first('tag_line')) has-error @endif form-md-line-input">
                                            @php if(isset($links_highLight->intFKCategory) && ($links_highLight->intFKCategory != $links->intFKCategory)){
                                            $Class_title = " highlitetext";
                                            }else{
                                            $Class_title = "";
                                            } @endphp
                                            <label class="form_title {{ $Class_title }}" for="site_name">Select Category <span aria-required="true" class="required"> * </span></label>
                                            <select class="form-control bs-select select2" name="category">
                                                <option value=" ">-- Select Category --</option>
                                                @foreach ($teamCategory as $cat)
                                                @php $permissionName = 'links-list' @endphp
                                                @php $selected = ''; @endphp
                                                @if(isset($links->intFKCategory))
                                                @if($cat['id'] == $links->intFKCategory)
                                                @php $selected = 'selected'; @endphp
                                                @endif
                                                @endif
                                                <option value="{{ $cat['id'] }}" {{ $selected }} >{{ $cat['varModuleName']== "links"?'Select Category':$cat['varTitle'] }}</option>
                                                @endforeach
                                            </select>
                                            <span class="help-block">
                                                {{ $errors->first('category') }}
                                            </span>
                                        </div>
                                        <div class="form-group @if($errors->first('tag_line')) has-error @endif form-md-line-input">
                                            @php if(isset($links_highLight->varTitle) && ($links_highLight->varTitle != $links->varTitle)){
                                            $Class_title = " highlitetext";
                                            }else{
                                            $Class_title = "";
                                            } @endphp
                                            <label class="form_title {!! $Class_title !!}" for="site_name">{{ trans('links::template.common.title') }} <span aria-required="true" class="required"> * </span></label>
                                            {!! Form::text('title', isset($links->varTitle) ? $links->varTitle:old('title'), array('maxlength'=>'150','placeholder' => trans('links::template.common.title'),'class' => 'form-control seoField maxlength-handler titlespellingcheck','autocomplete'=>'off')) !!}
                                            <span class="help-block">
                                                {{ $errors->first('title') }}
                                            </span>
                                        </div>
                                        @if ((isset($links->varLinkType) && $links->varLinkType == 'external') || old('link_type') == 'external')
                                        @php $checked_yes = 'checked' @endphp
                                        @else
                                        @php $checked_yes = '' @endphp
                                        @endif
                                        @if ((isset($links->varLinkType) && $links->varLinkType == 'internal') || old('link_type') == 'internal' || (!isset($links->varLinkType) && old('link_type') == null))
                                        @php $ichecked_innerbaner_yes = 'checked' @endphp
                                        @else
                                        @php $ichecked_innerbaner_yes = '' @endphp
                                        @endif
                                        <div class="form-group {{ $errors->has('link_type') ? ' has-error' : '' }}">
                                            @if(isset($linksHighLight->varLinkType) && ($linksHighLight->varLinkType != $links->varLinkType))
                                            @php $Class_varLinkType = " highlitetext"; @endphp
                                            @else
                                            @php $Class_varLinkType = ""; @endphp
                                            @endif
                                            <label class="form_title {{ $Class_varLinkType }}" for="link_type">{!! trans('quick-links::template.quickLinkModule.linkType') !!} <span aria-required="true" class="required"> * </span></label>
                                            <div class="md-radio-inline">
                                                <div class="md-radio">
                                                    <input type="radio" {{ $checked_yes }}  value="external" id="external_linktype" name="link_type" class="md-radiobtn banner">
                                                    <label for="external_linktype">
                                                        <span class="inc"></span>
                                                        <span class="check"></span>
                                                        <span class="box"></span> {!! trans('quick-links::template.quickLinkModule.external') !!}
                                                    </label>
                                                </div>
                                                <div class="md-radio">
                                                    <input type="radio" {{ $ichecked_innerbaner_yes }} value="internal" id="internal_linktype" name="link_type" class="md-radiobtn banner">
                                                    <label for="internal_linktype">
                                                        <span class="inc"></span>
                                                        <span class="check"></span>
                                                        <span class="box"></span> {!! trans('quick-links::template.quickLinkModule.internal') !!}
                                                    </label>
                                                </div>
                                            </div>
                                            <span class="help-block">
                                                <strong>{{ $errors->first('link_type') }}</strong>
                                            </span>
                                        </div>
                                        <div class="form-group" id="pages" style="display: none;">
                                            @if(isset($linksHighLight->fkModuleId) && ($linksHighLight->fkModuleId != $links->fkModuleId))
                                            @php $Class_fkModuleId = " highlitetext"; @endphp
                                            @else
                                            @php $Class_fkModuleId = ""; @endphp
                                            @endif
                                            <label class="form_title {{ $Class_fkModuleId }}" for="pages">{!! trans('quick-links::template.common.selectmodule') !!} <span aria-required="true" class="required"> * </span></label>
                                            <select class="form-control bs-select select2" name="modules" id="modules">
                                                <option value=" ">-{!! trans('quick-links::template.common.selectmodule') !!}-</option>
                                                @if(count($modules) > 0)
                                                @foreach ($modules as $pagedata)
                                                @php
                                                $avoidModules = array('faq','contact-us','sitemap','banksupervision','links-category','blogs','blog-category','news-category','career-category');

                                                @endphp
                                                @if (ucfirst($pagedata->varTitle)!='Home' && !in_array($pagedata->varModuleName,$avoidModules))
                                                <option data-model="{{ $pagedata->varModelName }}" data-module="{{ $pagedata->varModuleName }}" value="{{ $pagedata->id }}" {{ (isset($links->fkModuleId) && $pagedata->id == $links->fkModuleId) || $pagedata->id == old('modules')? 'selected' : '' }} >{{ $pagedata->varTitle }}</option>
                                                @endif
                                                @endforeach
                                                @endif
                                            </select>
                                            <span style="color:#e73d4a">
                                                {{ $errors->first('modules') }}
                                            </span>
                                        </div>
                                        <div class="form-group" id="records" style="display: none;">
                                            @if(isset($linksHighLight->fkIntPageId) && ($linksHighLight->fkIntPageId != $links->fkIntPageId))
                                            @php $Class_fkIntPageId = " highlitetext"; @endphp
                                            @else
                                            @php $Class_fkIntPageId = ""; @endphp
                                            @endif
                                            <label class="form_title {{ $Class_fkIntPageId }}" for="pages">{!! trans('quick-links::template.quickLinkModule.selectPage') !!}<span aria-required="true" class="required"> * </span></label>
                                            <select class="form-control bs-select select2" name="foritem" id="foritem" style="width:100%">
                                                <option value=" ">--{!! trans('quick-links::template.quickLinkModule.selectPage') !!}--</option>
                                            </select>
                                            <span style="color:#e73d4a">
                                                {{ $errors->first('foritem') }}
                                            </span>
                                        </div>
                                        <div class="form-group {{ $errors->has('ext_Link') ? ' has-error' : '' }} form-md-line-input" id="ext_Link_div">
                                            @if(isset($linksHighLight->varExtLink) && ($linksHighLight->varExtLink != $links->varExtLink))
                                            @php $Class_varExtLink = " highlitetext"; @endphp
                                            @else
                                            @php $Class_varExtLink = ""; @endphp
                                            @endif
                                            <label class="form_title {{ $Class_varExtLink }}" for="ext_Link">{!! trans('quick-links::template.quickLinkModule.extLink') !!}<span aria-required="true" class="required"> * </span></label>
                                            {!! Form::text('ext_Link', isset($links->varExtLink)?$links->varExtLink:old('ext_Link'), array('class' => 'form-control input-sm', 'data-url' => 'powerpanel/quick-links','id' => 'ext_Link','placeholder' => 'External Link','autocomplete'=>'off')) !!}
                                            <span style="color:#e73d4a">
                                                {{ $errors->first('ext_Link') }}
                                            </span>
                                        </div>
                                        @if(Config::get('Constant.CHRSearchRank') == 'Y')
                                        @if(isset($links->intSearchRank))
                                        @php $srank = $links->intSearchRank; @endphp
                                        @else
                                        @php
                                        $srank = null !== old('search_rank') ? old('search_rank') : 2 ;
                                        @endphp
                                        @endif
                                        @if(isset($links_highLight->intSearchRank) && ($links_highLight->intSearchRank != $links->intSearchRank))
                                        @php $Class_intSearchRank = " highlitetext"; @endphp
                                        @else
                                        @php $Class_intSearchRank = ""; @endphp
                                        @endif
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label class="{{ $Class_intSearchRank }} form_title">Search Ranking</label>
                                                <a href="javascript:;" data-bs-toggle="tooltip" class="config" data-bs-placement="bottom" data-bs-content="{{ trans('links::template.common.SearchEntityTools') }}" title="{{ trans('template.common.SearchEntityTools') }}"><i class="fa fa-question"></i></a>
                                                <div class="wrapper search_rank">
                                                    <label for="yes_radio" id="yes-lbl">High</label><input type="radio" value="1" name="search_rank" @if($srank == 1) checked @endif id="yes_radio">
                                                                                                           <label for="maybe_radio" id="maybe-lbl">Medium</label><input type="radio" value="2" name="search_rank" @if($srank == 2) checked @endif id="maybe_radio">
                                                                                                           <label for="no_radio" id="no-lbl">Low</label><input type="radio" value="3" name="search_rank" @if($srank == 3) checked @endif id="no_radio">
                                                                                                           <div class="toggle"></div>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        <h3 class="form-section">{{ trans('links::template.common.ContentScheduling') }}</h3>
                                        @php $defaultDt = (null !== old('start_date_time'))?old('start_date_time'):date('Y-m-d H:i'); @endphp
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group form-md-line-input">
                                                    @php if(isset($links_highLight->dtDateTime) && ($links_highLight->dtDateTime != $links->dtDateTime)){
                                                    $Class_date = " highlitetext";
                                                    }else{
                                                    $Class_date = "";
                                                    } @endphp
                                                    <label class="control-label form_title {!! $Class_date !!}">{{ trans('links::template.common.startDateAndTime') }}<span aria-required="true" class="required"> * </span></label>
                                                    <div class="input-group date form_meridian_datetime @if($errors->first('start_date_time')) has-error @endif" data-date="{{ Carbon\Carbon::today()->format('Y-m-d') }}T15:25:00Z">
                                                        <span class="input-group-btn date_default">
                                                            <button class="btn date-set fromButton" type="button">
                                                                <i class="fa fa-calendar"></i>
                                                            </button>
                                                        </span>
                                                        {!! Form::text('start_date_time', date('Y-m-d H:i',strtotime(isset($links->dtDateTime)?$links->dtDateTime:$defaultDt)), array('class' => 'form-control','maxlength'=>160,'size'=>'16','id'=>'start_date_time','autocomplete'=>'off','onkeypress'=>"javascript: return KeycheckOnlyDate(event);",'onpaste'=>'return false')) !!}
                                                    </div>
                                                    <span class="help-block">
                                                        {{ $errors->first('start_date_time') }}
                                                    </span>
                                                </div>
                                            </div>
                                            @php $defaultDt = (null !== old('end_date_time'))?old('end_date_time'):null; @endphp
                                            @if ((isset($links->dtEndDateTime)==null))
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
                                                <div class="form-group form-md-line-input">
                                                    <div class="input-group date  form_meridian_datetime expirydate @if($errors->first('end_date_time')) has-error @endif" data-date="{{ Carbon\Carbon::today()->format('Y-m-d') }}T15:25:00Z" @if ($expChecked_yes==1) style="display:none;" @endif>
                                                         @php if(isset($links_highLight->varTitle) && ($links_highLight->dtEndDateTime != $links->dtEndDateTime)){
                                                         $Class_end_date = " highlitetext";
                                                         }else{
                                                         $Class_end_date = "";
                                                         } @endphp
                                                         <label class="control-label form_title {!! $Class_end_date !!}" >{{ trans('links::template.common.endDateAndTime') }} <span aria-required="true" class="required"> * </span></label>
                                                        <div class="pos_cal">
                                                            <span class="input-group-btn date_default">
                                                                <button class="btn date-set toButton" type="button">
                                                                    <i class="fa fa-calendar"></i>
                                                                </button>
                                                            </span>
                                                            {!! Form::text('end_date_time', isset($links->dtEndDateTime)?date('Y-m-d H:i',strtotime($links->dtEndDateTime)):$defaultDt, array('class' => 'form-control','maxlength'=>160,'size'=>'16','id'=>'end_date_time','data-exp'=> $expChecked_yes,'data-newvalue','autocomplete'=>'off','onkeypress'=>"javascript: return KeycheckOnlyDate(event);",'onpaste'=>'return false')) !!}
                                                        </div>
                                                    </div>
                                                    <span class="help-block">
                                                        {{ $errors->first('end_date_time') }}
                                                    </span>
                                                    <label class="expdatelabel {{ $expclass }}">
                                                        <a id="noexpiry" name="noexpiry" href="javascript:void(0);">
                                                            <b class="expiry_lbl {!! $Class_end_date !!}"></b>
                                                        </a>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <h3 class="form-section">{{ trans('links::template.common.displayinformation') }}</h3>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group @if($errors->first('order')) has-error @endif form-md-line-input">
                                                    @php
                                                    $display_order_attributes = array('class' => 'form-control','maxlength'=>5,'placeholder'=>trans('links::template.common.displayorder'),'autocomplete'=>'off');
                                                    @endphp
                                                    @if(isset($links_highLight->intDisplayOrder) && ($links_highLight->intDisplayOrder != $links->intDisplayOrder))
                                                    @php $Class_intDisplayOrder = " highlitetext"; @endphp
                                                    @else
                                                    @php $Class_intDisplayOrder = ""; @endphp
                                                    @endif
                                                    <label class="form_title {{ $Class_intDisplayOrder }}" for="site_name">{{ trans('links::template.common.displayorder') }} <span aria-required="true" class="required"> * </span></label>
                                                    {!! Form::text('order', isset($links->intDisplayOrder)?$links->intDisplayOrder:$total, $display_order_attributes) !!}
                                                    <span style="color: red;">
                                                        {{ $errors->first('order') }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                @if(isset($links_highLight->chrPublish) && ($links_highLight->chrPublish != $links->chrPublish))
                                                @php $Class_chrPublish = " highlitetext"; @endphp
                                                @else
                                                @php $Class_chrPublish = ""; @endphp
                                                @endif
                                                @if((isset($links) && $links->chrDraft == 'D'))
                                                @include('powerpanel.partials.displayInfo',['Class_chrPublish'=>$Class_chrPublish,'display' => (isset($links->chrDraft)?$links->chrDraft:'D')])
                                                @else
                                                @include('powerpanel.partials.displayInfo',['Class_chrPublish'=>$Class_chrPublish,'display' => (isset($links->chrPublish)?$links->chrPublish:'Y')])
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-actions">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    @if(isset($links->fkMainRecord) && $links->fkMainRecord != 0)
                                                    <button type="submit" name="saveandexit" class="btn btn-green-drake" value="saveandexit">{!! trans('links::template.common.approve') !!}</button>
                                                    @else
                                                    @if($userIsAdmin)
                                                    <button type="submit" name="saveandedit" class="btn btn-green-drake" value="saveandedit">{!! trans('links::template.common.saveandedit') !!}</button>
                                                    <button type="submit" name="saveandexit" class="btn btn-green-drake" value="saveandexit">{!! trans('links::template.common.saveandexit') !!}</button>
                                                    @else
                                                    @if((isset($chrNeedAddPermission) && $chrNeedAddPermission == 'N') && (isset($charNeedApproval) && $charNeedApproval == 'N'))
                                                    <button type="submit" name="saveandexit" class="btn btn-green-drake" value="saveandexit">{!! trans('links::template.common.saveandexit') !!}</button>
                                                    @else
                                                    <button type="submit" name="saveandexit" class="btn btn-green-drake" value="approvesaveandexit">{!! trans('links::template.common.approvesaveandexit') !!}</button>
                                                    @endif
                                                    @endif  
                                                    @endif
                                                    <a class="btn red btn-outline" href="{{ url('powerpanel/links') }}">{{ trans('links::template.common.cancel') }}</a>
                                                </div>
                                            </div>
                                        </div>   
                                    </div>
                                    {!! Form::close() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="clearfix"></div>
@endsection
@section('scripts')
<script type="text/javascript">
    window.site_url = '{!! url("/") !!}';
    var user_action = "{{ isset($links)?'edit':'add' }}";
    var selectedRecord = '{{ isset($links->fkIntPageId)?$links->fkIntPageId:' ' }}';
    var moduleAlias = 'links';
</script>

<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/links/links_validations.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/custom.js' }}" type="text/javascript"></script>
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
</script>
@endsection