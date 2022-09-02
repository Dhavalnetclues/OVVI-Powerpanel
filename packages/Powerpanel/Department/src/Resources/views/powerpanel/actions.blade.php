@section('css')
@endsection
@extends('powerpanel.layouts.app')
@section('title')
{{Config::get('Constant.SITE_NAME')}} - PowerPanel
@stop
@section('content')
@php $settings = json_decode(Config::get("Constant.MODULE.SETTINGS")); @endphp
<!-- @include('powerpanel.partials.breadcrumbs') -->

<div class="row">
    <div class="col-xxl-12">
        @if(Session::has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ Session::get('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        @if(Session::has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ Session::get('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        
        <div class="card">
            <div class="card-body">
                <div class="live-preview">
                    {!! Form::open(['method' => 'post','id'=>'frmdepartment']) !!}
                        {!! Form::hidden('fkMainRecord', isset($department->fkMainRecord)?$department->fkMainRecord:old('fkMainRecord')) !!}
                        @if(isset($department))
                        @if (File::exists(base_path() . '/resources/views/powerpanel/partials/lockedpage.blade.php') != null)
                        @include('powerpanel.partials.lockedpage',['pagedata'=>$department])
                        @endif
                        @endif
                        
                        <!-- Website type -->
                        <div class="mb-3 @if($errors->first('sector')) has-error @endif form-md-line-input">
                                @if(isset($department_highLight->varSector) && ($department_highLight->varSector != $department->varSector))
                                @php $Class_varSector = " highlitetext"; @endphp
                                @else
                                @php $Class_varSector = ""; @endphp
                                @endif
                            <label class="form-label {{ $Class_varSector }}" for="site_name">Select Website Type </label>
                            <select class="form-control" data-choices name="sector" id="sector">
                                <option value="">Select Website Type</option>
                                @foreach($sector as  $keySector => $ValueSector)
                                @php $permissionName = 'department-list' @endphp
                                @php $selected = ''; @endphp
                                @if(isset($department->varSector))
                                @if($keySector == $department->varSector)
                                @php $selected = 'selected';  @endphp
                                @endif
                                @endif
                                <option value="{{$keySector}}" {{ $selected }}>{{ ($ValueSector == "department") ? 'Select Website Type' : $ValueSector }}</option>
                                @endforeach
                            </select>
                            <span class="help-block">
                                {{ $errors->first('sector') }}
                            </span>
                        </div>

                        <div class="mb-3 @if($errors->first('tag_line')) has-error @endif form-md-line-input">
                            @php if(isset($department_highLight->varTitle) && ($department_highLight->varTitle != $department->varTitle)){
                            $Class_title = " highlitetext";
                            }else{
                            $Class_title = "";
                            } @endphp
                            <label class="form-label {!! $Class_title !!}" for="site_name">{{ trans('department::template.common.title') }} <span aria-required="true" class="required"> * </span></label>
                            {!! Form::text('title', isset($department->varTitle) ? $department->varTitle:old('title'), array('maxlength'=>'150','placeholder' => trans('department::template.common.title'),'class' => 'form-control seoField maxlength-handler titlespellingcheck','autocomplete'=>'off')) !!}
                            <span class="help-block">
                                {{ $errors->first('title') }}
                            </span>
                        </div>
                        <div class="mb-3 @if($errors->first('tag_line')) has-error @endif form-md-line-input">
                            @php if(isset($department_highLight->varEmail) && ($department_highLight->varEmail != $department->varEmail)){
                            $Class_email = " highlitetext";
                            }else{
                            $Class_email = "";
                            } @endphp
                            <label class="form-label {!! $Class_email !!}" for="site_name">{{ trans('department::template.common.email') }} <span aria-required="true" class="required"> * </span></label>
                            {!! Form::text('email', isset($department->varEmail) ? $department->varEmail:old('email'), array('maxlength'=>'150','placeholder' => trans('department::template.common.email'),'class' => 'form-control seoField maxlength-handler emailspellingcheck','autocomplete'=>'off')) !!}
                            <span class="help-block">
                                {{ $errors->first('email') }}
                            </span>
                        </div>
                        <div class="phoneField mb-3 {{ $errors->has('phone_no') ? 'has-error' : '' }} form-md-line-input">
                            @php if(isset($department_highLight->varPhoneNo) && ($department_highLight->varPhoneNo != $department->varPhoneNo)){
                            $Class_phone = " highlitetext";
                            }else{
                            $Class_phone = "";
                            } @endphp
                            <label class="form-label {!! $Class_phone !!}" for="phone_no">{{ trans('department::template.common.phoneno') }} </label>
                            {!! Form::text('phone_no', isset($department->varPhoneNo) ? $department->varPhoneNo:old('phone_no'), array('class' => 'form-control input-sm','id' => 'phone_no','placeholder' => 'Phone No','autocomplete'=>'off', 'maxlength'=>"20", 'onkeypress'=>"javascript: return KeycheckOnlyPhonenumber(event);",'onpaste'=>'return false')) !!}
                            <span class="help-block">
                                {{ $errors->first('phone_no') }}
                            </span>
                        </div>
                        <div class="phoneField mb-3 {{ $errors->has('fax') ? 'has-error' : '' }} form-md-line-input">
                            @php if(isset($department_highLight->varfax) && ($department_highLight->varfax != $department->varfax)){
                            $Class_fax = " highlitetext";
                            }else{
                            $Class_fax = "";
                            } @endphp
                            <label class="form-label {!! $Class_fax !!}" for="faxZ">{{ trans('department::template.common.fax') }} </label>
                            {!! Form::text('fax', isset($department->varfax) ? $department->varfax:old('fax'), array('class' => 'form-control input-sm','id' => 'fax','placeholder' => 'Fax','autocomplete'=>'off', 'maxlength'=>"20", 'onkeypress'=>"javascript: return KeycheckOnlyPhonenumber(event);",'onpaste'=>'return false')) !!}
                            <span class="help-block">
                                {{ $errors->first('fax') }}
                            </span>
                        </div>
                            @if(Config::get('Constant.CHRSearchRank') == 'Y')
                        @if(isset($department->intSearchRank))
                        @php $srank = $department->intSearchRank; @endphp
                        @else
                        @php
                        $srank = null !== old('search_rank') ? old('search_rank') : 2 ;
                        @endphp
                        @endif
                        @if(isset($department_highLight->intSearchRank) && ($department_highLight->intSearchRank != $department->intSearchRank))
                        @php $Class_intSearchRank = " highlitetext"; @endphp
                        @else
                        @php $Class_intSearchRank = ""; @endphp
                        @endif
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="{{ $Class_intSearchRank }} form-label">Search Ranking</label>
                                <a href="javascript:;" data-bs-toggle="tooltip" class="config" data-bs-placement="bottom" data-bs-content="{{ trans('department::template.common.SearchEntityTools') }}" title="{{ trans('template.common.SearchEntityTools') }}"><i class="fa fa-question"></i></a>
                                <div class="md-radio-inline">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" value="1" name="search_rank" id="yes_radio" @if ($srank == '1') checked @endif>
                                        <label for="yes_radio">High</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" value="2" name="search_rank" id="maybe_radio" @if ($srank == '2') checked @endif>
                                        <label for="maybe_radio">Medium</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" value="3" name="search_rank" id="no_radio" @if ($srank == '3') checked @endif>
                                        <label for="no_radio">Low</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <h3 class="form-section">{{ trans('department::template.common.ContentScheduling') }}</h3>
                        @php $defaultDt = (null !== old('start_date_time'))?old('start_date_time'):date(Config::get('Constant.DEFAULT_DATE_FORMAT').' '.Config::get('Constant.DEFAULT_TIME_FORMAT')); @endphp
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3 form-md-line-input">
                                    @php if(isset($department_highLight->dtDateTime) && ($department_highLight->dtDateTime != $department->dtDateTime)){
                                    $Class_date = " highlitetext";
                                    }else{
                                    $Class_date = "";
                                    } @endphp
                                    <label class="control-label form-label {!! $Class_date !!}">{{ trans('department::template.common.startDateAndTime') }}<span aria-required="true" class="required"> * </span></label>
                                    <div class="input-group date form_meridian_datetime @if($errors->first('start_date_time')) has-error @endif" data-date="{{ Carbon\Carbon::today()->format('Y-m-d') }}T15:25:00Z">
                                        <span class="input-group-text date_default" id="basic-addon1">
                                            <i class="ri-calendar-fill"></i>
                                        </span>
                                        {!! Form::text('start_date_time', date(Config::get('Constant.DEFAULT_DATE_FORMAT').' '.Config::get('Constant.DEFAULT_TIME_FORMAT'),strtotime(isset($department->dtDateTime)?$department->dtDateTime:$defaultDt)), array('class' => 'form-control', 'data-provider' => 'flatpickr', 'data-date-format' => Config::get('Constant.DEFAULT_DATE_FORMAT'), 'data-enable-time' => '','maxlength'=>160,'size'=>'16','id'=>'start_date_time','autocomplete'=>'off','onkeypress'=>"javascript: return KeycheckOnlyDate(event);",'onpaste'=>'return false')) !!}
                                    </div>
                                    <span class="help-block">{{ $errors->first('start_date_time') }}</span>
                                </div>
                            </div>
                            @php $defaultDt = (null !== old('end_date_time'))?old('end_date_time'):null; @endphp
                            @if ((isset($department->dtEndDateTime)==null))
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
                                    @php if(isset($department_highLight->varTitle) && ($department_highLight->dtEndDateTime != $department->dtEndDateTime)){
                                    $Class_end_date = " highlitetext";
                                    }else{
                                    $Class_end_date = "";
                                    } @endphp
                                    <div class=" form_meridian_datetime expirydate @if($errors->first('end_date_time')) has-error @endif" data-date="{{ Carbon\Carbon::today()->format('Y-m-d') }}T15:25:00Z" @if ($expChecked_yes==1) style="display:none;" @endif>
                                        <label class="form-label {!! $Class_end_date !!}">{{ trans('department::template.common.endDateAndTime') }} <span aria-required="true" class="required"> * </span></label>
                                        
                                        <div class="input-group date">
                                            <span class="input-group-text"><i class="ri-calendar-fill"></i></span>
                                            {!! Form::text('end_date_time', isset($department->dtEndDateTime)?date(Config::get('Constant.DEFAULT_DATE_FORMAT').' '.Config::get('Constant.DEFAULT_TIME_FORMAT'),strtotime($department->dtEndDateTime)):$defaultDt, array('class' => 'form-control', 'data-provider' => 'flatpickr', 'data-date-format' => Config::get('Constant.DEFAULT_DATE_FORMAT'), 'data-enable-time' => '','maxlength'=>160,'size'=>'16','id'=>'end_date_time','data-exp'=> $expChecked_yes,'data-newvalue','autocomplete'=>'off','onkeypress'=>"javascript: return KeycheckOnlyDate(event);",'onpaste'=>'return false')) !!}
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
                        
                        <h3 class="form-section">{{ trans('department::template.common.displayinformation') }}</h3>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="@if($errors->first('display_order')) has-error @endif form-md-line-input">
                                    @php
                                    $display_order_attributes = array('class' => 'form-control','maxlength'=>5,'placeholder'=>trans('department::template.common.displayorder'),'autocomplete'=>'off');
                                    @endphp
                                    @php if(isset($department_highLight->intDisplayOrder) && ($department_highLight->intDisplayOrder != $department->intDisplayOrder)){
                                    $Class_displayorder = " highlitetext";
                                    }else{
                                    $Class_displayorder = "";
                                    } @endphp
                                    <label class="form-label {!! $Class_displayorder !!}" for="site_name">{{ trans('department::template.common.displayorder') }} <span aria-required="true" class="required"> * </span></label>
                                    {!! Form::text('display_order', isset($department->intDisplayOrder)?$department->intDisplayOrder:'1', $display_order_attributes) !!}
                                    <span style="color: red;">
                                        {{ $errors->first('display_order') }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                @if(isset($department_highLight->chrPublish) && ($department_highLight->chrPublish != $department->chrPublish))
                                @php $Class_chrPublish = " highlitetext"; @endphp
                                @else
                                @php $Class_chrPublish = ""; @endphp
                                @endif
                                @include('powerpanel.partials.displayInfo',['Class_chrPublish'=>$Class_chrPublish,'display' => isset($department->chrPublish)?$department->chrPublish:null])
                            </div>
                        </div>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-12">
                                    @if(isset($department->fkMainRecord) && $department->fkMainRecord != 0)
                                    <button type="submit" name="saveandexit" class="btn btn-primary" value="saveandexit">{!! trans('department::template.common.approve') !!}</button>
                                    @else
                                    @if($userIsAdmin)
                                    <button type="submit" name="saveandedit" class="btn btn-primary" value="saveandedit">{!! trans('department::template.common.saveandedit') !!}</button>
                                    <button type="submit" name="saveandexit" class="btn btn-primary" value="saveandexit">{!! trans('department::template.common.saveandexit') !!}</button>
                                    @else
                                    @if((isset($chrNeedAddPermission) && $chrNeedAddPermission == 'N') && (isset($charNeedApproval) && $charNeedApproval == 'N'))
                                    <button type="submit" name="saveandexit" class="btn btn-primary" value="saveandexit">{!! trans('department::template.common.saveandexit') !!}</button>
                                    @else
                                    <button type="submit" name="saveandexit" class="btn btn-primary" value="approvesaveandexit">{!! trans('department::template.common.approvesaveandexit') !!}</button>
                                    @endif
                                    @endif  
                                    @endif
                                    <a class="btn btn-danger" href="{{ url('powerpanel/department') }}">{{ trans('department::template.common.cancel') }}</a>
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
<script type="text/javascript">
    window.site_url = '{!! url("/") !!}';
    var user_action = "{{ isset($department)?'edit':'add' }}";
    var moduleAlias = 'department';
</script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/department/department_validations.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/numbervalidation.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/custom.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/seo-generator/seo-info-generator.js' }}" type="text/javascript"></script>
@endsection