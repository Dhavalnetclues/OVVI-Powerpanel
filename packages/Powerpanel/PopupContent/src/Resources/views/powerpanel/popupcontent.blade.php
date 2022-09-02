@section('css')
<link href="{{ url('resources/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ url('resources/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ url('resources/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ url('resources/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ url('resources/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@extends('powerpanel.layouts.app')
@section('title')
{{Config::get('Constant.SITE_NAME')}} - PowerPanel
@endsection
@section('content')
@include('powerpanel.partials.breadcrumbs')
<div class="col-md-12 settings">
    @if(Session::has('message'))
    <div class="alert alert-success">
        <button class="close" data-close="alert"></button>
        {{ Session::get('message') }}
    </div>
    @endif


    {{-- @if (count($errors) > 0)
<div class="alert alert-danger">
<strong>Whoops!</strong> {{ trans('popup-content::template.common.inputProblem') }}<br><br>
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif  --}}


<div class="row">
    <div class="portlet light bdisplay_ordered">
        <div class="portlet-body form_pattern">
            <div class="tabbable tabbable-tabdrop">
                <div class="tab-content row">
                    <div class="col-md-12">
                        {!! Form::open(['method' => 'post','id'=>'frmPopup']) !!}
                        <div class="form-body">
                            <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }} form-md-line-input">
                                {!! Form::text('title',$popup->varTitle,array('class' => 'form-control input-sm','maxlength'=>'150','id' => 'title','placeholder' => trans('popup-content::template.common.title') ,'autocomplete'=>'off')) !!}
                                <label class="form_title" for="title">{{ trans('popup-content::template.common.title') }} <span aria-required="true" class="required"> * </span></label>
                                <span style="color: red;">
                                    {{ $errors->first('title') }}
                                </span>
                            </div>								
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group form-md-line-input">
                                        <div class="input-group date form_meridian_datetime @if($errors->first('start_date_time')) has-error @endif" data-date="{{ date('Y-m-d',strtotime($popup->dtStartDateTime)) }}T{{ date('H:i:s',strtotime($popup->dtStartDateTime)) }}Z">
                                            <span class="input-group-btn date_default">
                                                <button class="btn date-set fromButton" type="button">
                                                    <i class="ri-calendar-line"></i>
                                                </button>
                                            </span>

                                            {!! Form::text('start_date_time', date(Config::get('Constant.DEFAULT_DATE_FORMAT').' g:i A',strtotime($popup->dtStartDateTime)), array('class' => 'form-control','maxlength'=>160,'size'=>'16','id'=>'start_date_time','readonly'=>true)) !!}
                                            <label class="control-label form_title">{{ trans('popup-content::template.managePopup.startDateTime') }}<span aria-required="true" class="required"> * </span></label>

                                        </div>
                                        <span class="help-block">
                                            {{ $errors->first('start_date_time') }}
                                        </span>        
                                    </div>
                                </div>
                                @php $defaultDt = (null !== old('end_date_time'))?old('end_date_time'):null; @endphp
                                @if ((isset($popup->dtEndDateTime)==null))
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
                                        <div class="input-group date  form_meridian_datetime expirydate @if($errors->first('end_date_time')) has-error @endif" data-date="{{ date('Y-m-d',strtotime($popup->dtEndDateTime)) }}T{{ date('H:i:s',strtotime($popup->dtEndDateTime)) }}Z" @if ($expChecked_yes==1) style="display:none;" @endif>
                                             <span class="input-group-btn date_default">
                                                <button class="btn default date-set toButton" type="button">
                                                    <i class="ri-calendar-line"></i>
                                                </button>
                                            </span>
                                            {!! Form::text('end_date_time', isset($popup->dtEndDateTime)?date(Config::get('Constant.DEFAULT_DATE_FORMAT').' g:i A',strtotime($popup->dtEndDateTime)):$defaultDt, array('class' => 'form-control','maxlength'=>160,'size'=>'16','id'=>'end_date_time','readonly'=>true,'data-exp'=> $expChecked_yes,'data-newvalue' )) !!}
                                            <label class="control-label form_title">{{ trans('popup-content::template.managePopup.endDateTime') }} <span aria-required="true" class="required"> * </span></label> 
                                        </div>
                                        <span class="help-block">
                                            {{ $errors->first('end_date_time') }}
                                        </span>
                                        <label class="expdatelabel {{ $expclass }}">
                                            <a id="noexpiry" name="noexpiry" href="javascript:void(0);">
                                                <b class="expiry_lbl"></b></a>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group @if($errors->first('description')) has-error @endif">
                                <label class="form_title" for="description">{{ trans('popup-content::template.common.description') }}</label>
                                <span class="help-block">
                                    {{ $errors->first('description') }}
                                </span>
                                {!! Form::textarea('description',$popup->txtDescription,array('class' => 'form-control','id'=>'txtDescription')) !!}
                            </div>								
                            <div class="row">
                                <div class="col-md-6">
                                    @include('powerpanel.partials.displayInfo',['display' => $popup->chrPublish])
                                </div>
                            </div>
                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="submit" name="saveandedit" class="btn btn-green-drake" value="saveandedit">{{ trans('popup-content::template.common.save') }}</button>
                                        <a class="btn red btn-outline" href="{{ url('/powerpanel') }}">{{ trans('popup-content::template.common.cancel') }}</a>
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
    <div class="new_modal modal fade bs-modal-sm" id="modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-vertical">
                <div class="modal-content"> 
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                        <h4 class="modal-title">{{ trans('popup-content::template.common.alert') }}</h4>
                    </div>
                    <div class="modal-body">
                        <p>{{ trans('popup-content::template.managePopup.daterange') }}</p>
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
@include('powerpanel.partials.ckeditor')
<script src="{{ url('resources/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js') }}" type="text/javascript"></script>
<script src="{{ url('resources/global/plugins/moment.min.js') }}"></script>
<script src="{{ url('resources/global/plugins/moments-timezone.js') }}"></script>
<script type="text/javascript">

var today = moment.tz("{{Config::get('Constant.DEFAULT_TIME_ZONE')}}").format(DEFAULT_DT_FORMAT + " H:m:s");
var oldVal = "{{isset($popup->dtEndDateTime)?date(Config::get('Constant.DEFAULT_DATE_FORMAT').' g:i A',strtotime($popup->dtEndDateTime)):$defaultDt}}";
$(document).ready(function () {
    $('#start_date_time').datetimepicker({
        autoclose: true,
        startDate: today,
        showMeridian: true,
        minuteStep: 5,
        format: DEFAULT_DT_FMT_FOR_DATEPICKER + ' HH:ii P'
    }).on("changeDate", function (e) {
        $("#start_date_time").closest('.has-error').removeClass('has-error');
        $("#start_date_time-error").remove();
        var startdate = moment($('#start_date_time').val());
        startdate = moment(startdate).add(1, 'hours').format(DEFAULT_DT_FORMAT + " H:m:s");
        $('#end_date_time').datetimepicker('setStartDate', startdate);
    });

    var startdate = moment($('#start_date_time').val());
    startdate = moment(startdate).add(1, 'hours').format(DEFAULT_DT_FORMAT + " H:m:s");
    $('#end_date_time').datetimepicker({
        autoclose: true,
        startDate: startdate,
        showMeridian: true,
        minuteStep: 5,
        format: DEFAULT_DT_FMT_FOR_DATEPICKER + ' HH:ii P'
    }).on("changeDate", function (e) {
        $("#end_date_time").closest('.has-error').removeClass('has-error');
        $("#end_date_time-error").remove();
    });
});

$('.fromButton,#start_date_time').on('click', function () {
    $('#start_date_time').datetimepicker('show');
});
$('.toButton,#end_date_time').on('click', function () {
    $('#end_date_time').datetimepicker('show');
});
</script>
<script src="{{ url('resources/pages/scripts/packages/popup-content/popup_validations.js') }}" type="text/javascript"></script>
@endsection