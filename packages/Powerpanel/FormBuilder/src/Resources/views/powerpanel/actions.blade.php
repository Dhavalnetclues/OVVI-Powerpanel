@section('css')
<link href="{{ $CDN_PATH.'resources/global/css/rank-button.css' }}" rel="stylesheet" type="text/css" />
<link href="{{ $CDN_PATH.'resources/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css' }}" rel="stylesheet" type="text/css" />
<link href="{{ $CDN_PATH.'resources/global/plugins/bootstrap-timepicker/css/timepicki.css' }}" rel="stylesheet" type="text/css" />
<style type="text/css">
    .cus_heading {
        padding-left: 11px;
    }
    .cus_heading span:first-child {
        padding-left: 8%;
    }
    .cus_heading span {
        width: 50%;
        display: block;
        float: left;
        padding-top: 8px;
        padding-bottom: 4px;
        font-weight: 600;
        font-size: 13px;
    }
    @media (min-width:1600px) and (max-width:1600px){
        .cus_heading span:last-child {padding-left:8px;}
    }
    
    /* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Firefox */
input[type=number] {
  -moz-appearance: textfield;
}
</style>
@endsection
@extends('powerpanel.layouts.app')
@section('title')
{{Config::get('Constant.SITE_NAME')}} - PowerPanel
@stop
@section('content')
@php $settings = json_decode(Config::get("Constant.MODULE.SETTINGS")); @endphp

<div class="col-md-12 settings">
    <div class="row">
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
            {!! Form::open(['method' => 'post','name'=>'frmfrombuilder','id'=>'frmfrombuilder']) !!}
                <div class="card">
                    @can('page_template-create')
                        {{-- <div class="card-header border-0">
                            <div class="d-flex align-items-center">
                                <h5 class="card-title mb-0 flex-grow-1"></h5>
                                <div class="flex-shrink-0">
                                    <a class="btn btn-primary add-btn bg-gradient waves-effect waves-light btn-label me-1 add_category" href="{{ url('powerpanel/formbuilder')}}" title="Go to list">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0">
                                                <i class="ri-arrow-go-back-line label-icon align-middle fs-20 me-2"></i>
                                            </div>
                                            <div class="flex-grow-1">Back</div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div> --}}
                    @endcan
                <div class="card-body p-30">
                    {!! Form::hidden('fkMainRecord', isset($frombuilder->fkMainRecord)?$frombuilder->fkMainRecord:old('fkMainRecord')) !!}
                    @if(isset($frombuilder->varFormDescription))
                        <input type="hidden" id="formdatadesc" name="formdatadesc" value="{{ $frombuilder->varFormDescription }}">
                    @endif

                    <div class="row">
                        {{-- Title --}}
                        <div class="col-lg-12">
                            <div class="@if($errors->first('tag_line')) has-error @endif form-md-line-input cm-floating">
                                @php if(isset($frombuilder->varName)){
                                $name = $frombuilder->varName;
                                }else{
                                $name = '';
                                } @endphp
                                <label class="form-label" for="site_name">Form Name <span aria-required="true" class="required"> * </span></label>
                                <input maxlength="150" class="form-control seoField maxlength-handler" value="{{ $name }}" autocomplete="off" name="title" id="formtitle" type="text">
                                <span class="help-block">{{ $errors->first('title') }}
                                </span>
                            </div>
                        </div>
                        {{-- Background Image --}}
                        <div class="col-lg-12">
                            @if(isset($frombuilder_highLight->fkIntImgId) && ($frombuilder_highLight->fkIntImgId != $frombuilder->fkIntImgId))
                            @php $Class_fkIntImgId = " highlitetext"; @endphp
                            @else
                            @php $Class_fkIntImgId = ""; @endphp
                            @endif
                            <div class="image_thumb multi_upload_images cm-floating mb-30">
                                @php $height = isset($settings->height)?$settings->height:100; $width = isset($settings->width)?$settings->width:200; @endphp
                                <label class="form-label {{ $Class_fkIntImgId }}" for="front_logo">
                                    Background Image
                                    <span class="img-note" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ trans('formbuilder::template.common.imageSize',['height'=>$height, 'width'=>$width]) }}">
                                        <i class="ri-information-line text-primary fs-16"></i>
                                    </span>
                                </label>
                                <div class="fileinput fileinput-new page-media" data-provides="fileinput">
                                    <div class="fileinput-preview thumbnail frombuilder_image_img" data-trigger="fileinput">
                                        @if(old('image_url'))
                                        <img src="{{ old('image_url') }}" />
                                        @elseif(isset($frombuilder->fkIntImgId))
                                        <img src="{!! App\Helpers\resize_image::resize($frombuilder->fkIntImgId,120,120) !!}" />
                                        @else
                                        <div class="dz-message needsclick w-100 text-center">
                                            <div class="dropzone_icon"><i class="display-5 text-muted ri-upload-cloud-2-fill"></i></div>
                                            <h5 class="sbold dropzone-title">Drop files here or click to upload</h5>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="input-group">
                                        <a class="media_manager" data-multiple="false" onclick="MediaManager.open('frombuilder_image');"><span class="fileinput-new"></span></a>
                                        <input class="form-control" type="hidden" id="frombuilder_image" name="img_id" value="{{ isset($frombuilder->fkIntImgId)?$frombuilder->fkIntImgId:old('img_id') }}" />
                                        @php
                                        if(isset($frombuilder->fkIntImgId)){
                                        $folderid = App\Helpers\MyLibrary::GetFolderID($frombuilder->fkIntImgId);
                                        @endphp
                                        @if(isset($folderid->fk_folder) && $folderid->fk_folder != '0')
                                        <input class="form-control" type="hidden" id="folder_id" name="folder_id" value="{{ $folderid->fk_folder }}" />
                                        @endif
                                        @php
                                        }
                                        @endphp
                                        <input class="form-control" type="hidden" id="image_url" name="image_url" value="{{ old('image_url') }}" />
                                    </div>
                                    <div class="overflow_layer">
                                        <a onclick="MediaManager.open('frombuilder_image');" class="media_manager remove_img"><i class="ri-pencil-line"></i></a>
                                        <a href="javascript:;" class="fileinput-exists remove_img removeimg" data-dismiss="fileinput"><i class="ri-delete-bin-line"></i></a>
                                    </div>
                                </div>
                                <span class="help-block">{{ $errors->first('img_id') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="dropdown build_setting_acc">
                        <div class="dropdown-toggle title_build_email mb-3 h5" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Email Settings</div>
                        <div class="dropdown-menu">
                            <div class="build_stng_body p-4 pb-0">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="@if($errors->first('tag_line')) has-error @endif form-md-line-input cm-floating">
                                            @php if(isset($frombuilder->varEmail)){
                                            $email = $frombuilder->varEmail;
                                            }else{
                                            $email = "";
                                            } @endphp
                                            <label class="form-label {!! $email !!}" for="site_name">{{ trans('Admin Email Id') }} <span aria-required="true" class="required"> * </span></label>
                                            {!! Form::text('email', isset($frombuilder->varEmail) ? $frombuilder->varEmail:old('email'), array('maxlength'=>'150','id'=>'email','class' => 'form-control seoField maxlength-handler emailspellingcheck','autocomplete'=>'off')) !!}
                                            <span class="help-block">{{ $errors->first('email') }}</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="@if($errors->first('tag_line')) has-error @endif form-md-line-input cm-floating">
                                            @php if(isset($frombuilder->varAdminSubject)){
                                            $admin_subject = $frombuilder->varAdminSubject;
                                            }else{
                                            $admin_subject = '';
                                            } @endphp
                                            <label class="form-label" for="site_name">Admin Email Subject <span aria-required="true" class="required"> * </span></label>
                                            <input maxlength="150" class="form-control seoField maxlength-handler designationspellingcheck" value="{{ $admin_subject }}" autocomplete="off" name="admin_subject" id="admin_subject" type="text">
                                            <span class="help-block">{{ $errors->first('admin_subject') }}</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="@if($errors->first('tag_line')) has-error @endif form-md-line-input cm-floating">
                                            @php if(isset($frombuilder->varAdminContent)){
                                            $admin_content = $frombuilder->varAdminContent;
                                            }else{
                                            $admin_content = '';
                                            } @endphp
                                            <label class="form-label" for="site_name">Admin Email Content <span aria-required="true" class="required"> * </span></label>
                                            <textarea maxlength="400" class="form-control seoField maxlength-handler metatitlespellingcheck" id="admin_content" rows="1" name="admin_content" cols="50" >{{ $admin_content }}</textarea>
                                            <span class="help-block">
                                                {{ $errors->first('admin_content') }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="@if($errors->first('tag_line')) has-error @endif form-md-line-input cm-floating">
                                            @php if(isset($frombuilder->varThankYouMsg)){
                                            $varThankYouMsg = $frombuilder->varThankYouMsg;
                                            }else{
                                            $varThankYouMsg = '';
                                            } @endphp
                                            <label class="form-label" for="site_name">Thank You Massage <span aria-required="true" class="required"> * </span></label>
                                            <input class="form-control seoField maxlength-handler designationspellingcheck" value="{{ $varThankYouMsg }}" autocomplete="off" name="varThankYouMsg" id="varThankYouMsg" type="text">
                                            <span class="help-block">
                                                {{ $errors->first('varThankYouMsg') }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-check mb-3">
                                            @if (isset($frombuilder->chrCheckUser) && $frombuilder->chrCheckUser == 'Y')
                                            @php $checked_section = true; @endphp
                                            @php $display_Section = ''; @endphp
                                            @else
                                            @php $checked_section = null; 
                                            @endphp
                                            @php $display_Section = 'none'; @endphp
                                            @endif
                                            {{ Form::checkbox('chrDisplayUser',null,$checked_section, array('id'=>'chrDisplayUser', 'class'=>'form-check-input')) }}
                                            <label class="form-label mb-0"> Email to User </label>
                                        </div>
                                    </div>
                                    @if (isset($frombuilder->chrCheckUser) && $frombuilder->chrCheckUser == 'Y')
                                    @php $opensection = ''; @endphp
                                    @else
                                    @php $opensection = "style='display: none;'"; @endphp
                                    @endif
                                    <div id="formhiddenfield" {!! $opensection !!}>
                                        <div class="row mt-3">
                                            <div class="col-lg-6">
                                                <div class="@if($errors->first('tag_line')) has-error @endif form-md-line-input cm-floating">
                                                    @php if(isset($frombuilder->varUserSubject)){
                                                    $user_subject = $frombuilder->varUserSubject;
                                                    }else{
                                                    $user_subject = '';
                                                    } @endphp
                                                    <label class="form-label" for="site_name">User Email Subject <span aria-required="true" class="required"> * </span></label>
                                                    <input maxlength="150" class="form-control seoField maxlength-handler userspellingcheck" value="{{ $user_subject }}" autocomplete="off" name="user_subject" id="user_subject" type="text">
                                                    <span class="help-block">{{ $errors->first('user_subject') }}</span>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="@if($errors->first('tag_line')) has-error @endif form-md-line-input cm-floating">
                                                    @php if(isset($frombuilder->varUserContent)){
                                                    $user_content = $frombuilder->varUserContent;
                                                    }else{
                                                    $user_content = '';
                                                    } @endphp
                                                    <label class="form-label" for="site_name">User Email Content <span aria-required="true" class="required"> * </span></label>
                                                    <textarea maxlength="400" class="form-control seoField maxlength-handler metadescspellingcheck" id="user_content" rows="1" name="user_content" cols="50" >{{ $user_content }}</textarea>
                                                    <span class="help-block">{{ $errors->first('user_content') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="fb-editor"></div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('scripts')

@include('powerpanel.partials.ckeditor',['config'=>'docsConfig'])

<script src="{{ $CDN_PATH.'assets/formBuilder/jquery-ui.min.js' }}"></script>
<script src="{{ $CDN_PATH.'assets/formBuilder/form-builder.min.js' }}"></script>
<script src="{{ $CDN_PATH.'assets/formBuilder/form-render.min.js' }}"></script>

<script>
var user_action = "{{ isset($frombuilder)?'edit':'add' }}";
<?php if(isset($frombuilder)){
    $fdata = addslashes($frombuilder->varFormDescription);
}else{
    $fdata = '';
}
?>
var formEditData = '<?php echo $fdata;?>';
var fbEditor = document.getElementById('fb-editor');

$(function () {
    $("#chrDisplayUser").click(function () {
        if ($(this).is(":checked")) {
            $("#formhiddenfield").show();
        } else {
            $("#formhiddenfield").hide();
        }
    });
});

jQuery(function($) {
    if ('<?php echo Request::segment(4); ?>' == 'edit?tab=P' || '<?php echo Request::segment(4); ?>' == 'edit') {
        var fbTemplate = document.getElementById('fb-editor'),
        options = {
            formData: formEditData,
            fields: [{
                type: 'text',
                className: 'datetimepicker',
                label: 'DateTime Picker'
            }, {
                type: 'text',
                className: 'time_element',
                label: 'Time Picker'
            },{
              type: 'checkbox-group',
              label: 'Predefined Option',
              className: 'predefine',
              values: [
                {
                  label: 'Country',
                  value: 'countries',
                  selected: false
                },
                {
                  label: 'State',
                  value: 'states',
                  selected: false
                },
                {
                  label: 'Gender',
                  value: 'gender',
                  selected: false
                },
                {
                  label: 'Month',
                  value: 'months',
                  selected: false
                }
              ]
            },{
                type: 'text',
                className: 'datepicker',
                label: 'Date Picker'
            },{
                type: 'text',
                className: 'form-control urlclass',
                label: 'URL'
            },{
                type: 'text',
                className: 'form-control uniqueclass',
                label: 'User Name'
            }]
        };
        setTimeout(function () {
            $(".time_element").parents(".form-field").find(".subtype-wrap").hide();
            $(".time_element").parents(".form-field").find(".maxlength-wrap").hide();
            $(".time_element").parents(".form-field").find(".value-wrap").hide();
            $(".datetimepicker").parents(".form-field").find(".subtype-wrap").hide();
            $(".datetimepicker").parents(".form-field").find(".maxlength-wrap").hide();
            $(".datetimepicker").parents(".form-field").find(".value-wrap").hide();
            $(".predefine").parents(".form-field").find(".field-options").hide();
            $(".datepicker").parents(".form-field").find(".subtype-wrap").hide();
            $(".datepicker").parents(".form-field").find(".maxlength-wrap").hide();
            $(".datepicker").parents(".form-field").find(".value-wrap").hide();
            $(".uniqueclass").parents(".form-field").find(".subtype-wrap").hide();
            $(".urlclass").parents(".form-field").find(".subtype-wrap").hide();
        }, 2000);
        $(fbTemplate).formBuilder(options);
    }
});

$(document).ready(function () {
    
    // $(document).on("click", ".build_setting_acc", function () {
    //     $(".build_setting_acc .dropdown-menu").toggle();
    // });
    /*code for key up event of option textboxes*/
    $(document).on("keyup", ".sortable-options-wrap .sortable-options .option-label", function () {
        var gettextval = $(this).val();
        // var gettextNewval = gettextval.replace(/[&\/\\#,+()$~%.'":*?<>{}\s]/g, '-');
        var gettextNewval = gettextval.replace(/[*\s]/g, '-');
        $(this).parent('li').find('.option-value').val(gettextNewval);
    });
    
    $(document).on("focusout", ".fld-max", function (e) {
     var maxval = $(this).val();
        var minval = $(this).parents('.form-elements').find('.min-wrap .fld-min').val();
        if(parseInt(maxval) < parseInt(minval)){
            alert('Min value can not gather then max value.');
            $(this).val('');
        }
    });
    
    $(document).on("focusout", ".fld-min", function (e) {
     var minval = $(this).val();
        var maxval = $(this).parents('.form-elements').find('.max-wrap .fld-max').val();
        if(parseInt(minval) > parseInt(maxval)){
            alert('Min value can not gather then max value.');
            $(this).val('');
        }
    });
    
    /*end of code for key up event of option textboxes*/
     $(document).on("keypress", ".fld-min", function (e) {
         var t = 0;
            t = document.all ? 3 : document.getElementById ? 1 : document.layers ? 2 : 0;
            if (document.all)
                e = window.event;
            var n = "";
            var r = "";
            if (t == 2) {
                if (e.which > 0)
                    n = "(" + String.fromCharCode(e.which) + ")";
                r = e.which
            } else {
                if (t == 3) {
                    r = window.event ? event.keyCode : e.which
                } else {
                    if (e.charCode > 0)
                        n = "(" + String.fromCharCode(e.charCode) + ")";
                    r = e.charCode
                }
            }
            if (r >= 65 && r <= 90 || r >= 97 && r <= 122 || r >= 33 && r <= 39 || r >= 42 && r <= 42 ||  r >= 43 && r <= 43 || r >= 44 && r <= 44 || r >= 45 && r <= 45 || r >= 46 && r <= 47 || r >= 58 && r <= 64 || r >= 91 && r <= 96 || r >= 123 && r <= 126) {
                return false
            }
            return true
    });
    
    $(document).on("keypress", ".fld-max", function (e) {
       
         var t = 0;
            t = document.all ? 3 : document.getElementById ? 1 : document.layers ? 2 : 0;
            if (document.all)
                e = window.event;
            var n = "";
            var r = "";
            if (t == 2) {
                if (e.which > 0)
                    n = "(" + String.fromCharCode(e.which) + ")";
                r = e.which
            } else {
                if (t == 3) {
                    r = window.event ? event.keyCode : e.which
                } else {
                    if (e.charCode > 0)
                        n = "(" + String.fromCharCode(e.charCode) + ")";
                    r = e.charCode
                }
            }
            if (r >= 65 && r <= 90 || r >= 97 && r <= 122 || r >= 33 && r <= 39 || r >= 42 && r <= 42 ||  r >= 43 && r <= 43 || r >= 44 && r <= 44 || r >= 45 && r <= 45 || r >= 46 && r <= 47 || r >= 58 && r <= 64 || r >= 91 && r <= 96 || r >= 123 && r <= 126) {
                return false
            }
            return true
    });
     $(document).on("keypress", ".fld-maxlength", function (e) {
         var t = 0;
            t = document.all ? 3 : document.getElementById ? 1 : document.layers ? 2 : 0;
            if (document.all)
                e = window.event;
            var n = "";
            var r = "";
            if (t == 2) {
                if (e.which > 0)
                    n = "(" + String.fromCharCode(e.which) + ")";
                r = e.which
            } else {
                if (t == 3) {
                    r = window.event ? event.keyCode : e.which
                } else {
                    if (e.charCode > 0)
                        n = "(" + String.fromCharCode(e.charCode) + ")";
                    r = e.charCode
                }
            }
            if (r >= 65 && r <= 90 || r >= 97 && r <= 122 || r >= 33 && r <= 39 || r >= 42 && r <= 42 ||  r >= 43 && r <= 43 || r >= 44 && r <= 44 || r >= 45 && r <= 45 || r >= 46 && r <= 47 || r >= 58 && r <= 64 || r >= 91 && r <= 96 || r >= 123 && r <= 126) {
                return false
            }
            return true
    });
});
if ('<?php echo Request::segment(3); ?>' == 'add') {
    jQuery(function ($) {
        var fields = [{
                type: 'text',
                className: 'datetimepicker',
                label: 'DateTime Picker'
            }, {
                type: 'text',
                className: 'time_element',
                label: 'Time Picker'
            },{
              type: 'checkbox-group',
              label: 'Predefined Option',
              className: 'predefine',
              values: [
                {
                  label: 'Country',
                  value: 'countries',
                  selected: false
                },
                {
                  label: 'State',
                  value: 'states',
                  selected: false
                },
                {
                  label: 'Gender',
                  value: 'gender',
                  selected: false
                },
                {
                  label: 'Month',
                  value: 'months',
                  selected: false
                }
              ]
            }, {
                type: 'text',
                className: 'datepicker',
                label: 'Date Picker'
            },{
                type: 'text',
                className: 'form-control urlclass',
                label: 'URL'
            },{
                type: 'text',
                className: 'form-control uniqueclass',
                label: 'User Name'
            }];
        $(document.getElementById('fb-editor')).formBuilder({fields});
    });
}
</script>
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js' }}" type="text/javascript"></script>
<script type="text/javascript">
    
     $(document).on("click", ".input-control-15", function () {
          $(".time_element").parents(".form-field").find(".subtype-wrap").hide();
          $(".time_element").parents(".form-field").find(".maxlength-wrap").hide();
          $(".time_element").parents(".form-field").find(".value-wrap").hide();
    });
    $(document).on("click", ".input-control-14", function () {
        $(".datetimepicker").parents(".form-field").find(".subtype-wrap").hide();
        $(".datetimepicker").parents(".form-field").find(".maxlength-wrap").hide();
        $(".datetimepicker").parents(".form-field").find(".value-wrap").hide();
    });
    $(document).on("click", ".input-control-17", function () {
        $(".datepicker").parents(".form-field").find(".subtype-wrap").hide();
        $(".datepicker").parents(".form-field").find(".maxlength-wrap").hide();
        $(".datepicker").parents(".form-field").find(".value-wrap").hide();
    });
    $(document).on("click", ".input-control-16", function () {
          $(".predefine").parents(".form-field").find(".field-options").hide();
    });
    $(document).on("click", ".input-control-19", function () {
          $(".uniqueclass").parents(".form-field").find(".subtype-wrap").hide();
    });
    $(document).on("click", ".input-control-18", function () {
          $(".urlclass").parents(".form-field").find(".subtype-wrap").hide();
    });
   
    $(document).on("click", ".datepicker", function () {
        setTimeout(function () {
            GetDate();
        }, 500);
    });
    $(document).on("click", ".time_element", function () {
        setTimeout(function () {
            GetTime();
        }, 500);
    });
    $(document).on("click", ".datetimepicker", function () {
        setTimeout(function () {
            GetDateTime();
        }, 500);
    });
    function GetTime() {
        $(".time_element").timepicki();
    }

    function GetDateTime() { 
        $('.datetimepicker').datetimepicker({
            autoclose: true,
            showMeridian: true,
            minuteStep: 5,
            format: DEFAULT_DT_FMT_FOR_DATEPICKER + ' HH:ii P'
        });
    }
    function GetDate() {
        $('.datepicker').datepicker({
            format: DEFAULT_DT_FMT_FOR_DATEPICKER,
            step: 5
        });
    }
</script>
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-timepicker/js/timepicki.js' }}"></script>
@endsection