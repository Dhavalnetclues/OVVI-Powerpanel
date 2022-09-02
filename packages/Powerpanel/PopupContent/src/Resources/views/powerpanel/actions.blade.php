@extends('powerpanel.layouts.app')
@section('title') {{ Config::get('Constant.SITE_NAME') }} - PowerPanel @endsection
@section('content')
<!-- @include('powerpanel.partials.breadcrumbs') -->
@php $settings = json_decode(Config::get("Constant.MODULE.SETTINGS")); @endphp

<div class="row">
    <div class="col-xxl-12">
        @if (count($errors) > 0)
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        @endif

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
            {!! Form::open(['method' => 'post','id'=>'frmPopup']) !!}
            <div class="card">
                <div class="card-body p-30">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="@if($errors->first('title')) has-error @endif form-md-line-input cm-floating">
                                <label class="form-label" for="site_name">{{ trans('popup-content::template.common.title') }} <span aria-required="true" class="required"> * </span></label>
                                {!! Form::text('title', isset($popupcontent->varTitle)?$popupcontent->varTitle:old('title'), array('maxlength' => 150, 'class' => 'form-control hasAlias seoField maxlength-handler','autocomplete'=>'off','data-url' => 'powerpanel/popup')) !!}
                                <span class="help-block">{{ $errors->first('title') }}</span>
                            </div>
                        </div>
                        
                        @if(isset($checkdisplay) && $checkdisplay <= 0)
                            <div class="col-md-12 versionradio" id="DisplayLink">
                                <div class="form-md-line-input mb-30">
                                    <label class="form-label">Display in all Pages:</label>
                                    @php $checked_section_link = false; @endphp
                                    @if (isset($popupcontent->chrDisplay) && $popupcontent->chrDisplay == 'Y')
                                        @php $checked_section_link = true; @endphp
                                    @endif
                                    {{ Form::checkbox('chrDisplay',null,$checked_section_link, array('class'=>'form-check-input', 'id'=>'chrDisplay','value'=>'Y')) }}
                                </div>
                            </div>
                        @endif
                        @if(isset($checkdisplaybox) && $checkdisplaybox == 1)
                            <div class="col-md-12 versionradio" id="DisplayLink">
                                <div class="form-md-line-input mb-30">
                                    <label class="form-label">Display in all Pages:</label>
                                    {{ Form::checkbox('chrDisplay',null,true, array('class'=>'form-check-input', 'id'=>'chrDisplay','value'=>'Y')) }}
                                </div>
                            </div>
                        @elseif(isset($checkdisplaybox) && $checkdisplaybox == 'show')
                            <div class="col-md-12 versionradio" id="DisplayLink" >
                                <div class="form-md-line-input mb-30">
                                    <label class="form-label">Display in all Pages:</label>
                                    @php $checked_section_link = false; @endphp
                                    @if (isset($popupcontent->chrDisplay) && $popupcontent->chrDisplay == 'Y')
                                        @php $checked_section_link = true; @endphp
                                    @endif
                                    {{ Form::checkbox('chrDisplay',null,$checked_section_link, array('class'=>'form-check-input', 'id'=>'chrDisplay','value'=>'Y')) }}
                                </div>
                            </div>
                        @endif

                        <div class="col-lg-6 col-sm-12 displaydropdown cm-floating" id="pages">
                            <label class="form-label" for="pages">{!! trans('popup-content::template.common.selectmodule') !!} <span aria-required="true" class="required"> * </span></label>
                            <select class="form-control" name="modules" id="modules" data-choices>
                                <option value="">{!! trans('popup-content::template.common.selectmodule') !!}</option>
                                @if(count($modules) > 0)
                                @foreach ($modules as $pagedata)
                                @php
                                    $avoidModules = array('faq','contact-us','testimonial');
                                @endphp
                                @if (ucfirst($pagedata->varTitle)!='Home' && !in_array($pagedata->varModuleName,$avoidModules) && Auth::user()->can($pagedata->varModuleName.'-list'))
                                    <option data-model="{{ $pagedata->varModelName }}" data-module="{{ $pagedata->varModuleName }}" value="{{ $pagedata->id }}" {{ (isset($popupcontent->fkModuleId) && $pagedata->id == $popupcontent->fkModuleId) || $pagedata->id == old('modules')? 'selected' : '' }} >{{ $pagedata->varTitle }}</option>
                                @endif
                                @endforeach
                                @endif
                            </select>
                            <span class="help-block" style="color:#e73d4a">{{ $errors->first('modules') }}</span>
                        </div>
                        
                        <div class="col-lg-6 col-sm-12 cm-floating" id="records">
                            <label class="form-label" for="pages">{!! trans('popup-content::template.common.selectPage') !!}<span aria-required="true" class="required"> * </span></label>
                            <select class="form-control" name="foritem" id="foritem" data-choices>
                                <option value="">{!! trans('popup-content::template.common.selectPage') !!}</option>
                            </select>
                            <span style="color:#e73d4a">{{ $errors->first('foritem') }}</span>
                        </div>

                        <div class="col-md-12">
                            @php $defaultDt = (null !== old('start_date_time'))?old('start_date_time'):date(Config::get('Constant.DEFAULT_DATE_FORMAT').' '.Config::get('Constant.DEFAULT_TIME_FORMAT')); @endphp
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-md-line-input cm-floating">
                                        @php if(isset($popupcontent_highLight->dtStartDateTime) && ($popupcontent_highLight->dtStartDateTime != $popupcontent->dtStartDateTime)){
                                        $Class_date = " highlitetext";
                                        }else{
                                        $Class_date = "";
                                        } @endphp
                                        <label class="control-label form-label {!! $Class_date !!}">{{ trans('cmspage::template.common.startDateAndTime') }}<span aria-required="true" class="required"> * </span></label>
                                        <div class="input-group date form_meridian_datetime @if($errors->first('start_date_time')) has-error @endif" data-date="{{ Carbon\Carbon::today()->format('Y-m-d') }}T15:25:00Z">
                                            {!! Form::text('start_date_time', date(Config::get('Constant.DEFAULT_DATE_FORMAT').' '.Config::get('Constant.DEFAULT_TIME_FORMAT'),strtotime(isset($popupcontent->dtStartDateTime)?$popupcontent->dtStartDateTime:$defaultDt)), array('class' => 'form-control', 'data-provider' => 'flatpickr', 'data-date-format' => Config::get('Constant.DEFAULT_DATE_FORMAT'), 'data-enable-time' => '','maxlength'=>160,'size'=>'16','id'=>'popup_start_date','autocomplete'=>'off','onkeypress'=>"javascript: return KeycheckOnlyDate(event);",'onpaste'=>'return false')) !!}
                                        </div>
                                        <span class="help-block">{{ $errors->first('start_date_time') }}</span>
                                    </div>
                                </div>
                                @php $defaultDt = (null !== old('end_date_time'))?old('end_date_time'):null; @endphp
                                @if ((isset($popupcontent->dtEndDateTime)==null))
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
                                        @php if(isset($popupcontent_highLight->dtEndDateTime) && ($popupcontent_highLight->dtEndDateTime != $popupcontent->dtEndDateTime)){
                                        $Class_end_date = " highlitetext";
                                        }else{
                                        $Class_end_date = "";
                                        } @endphp
                                        <div class="cm-floating form_meridian_datetime expirydate @if($errors->first('end_date_time')) has-error @endif" data-date="{{ Carbon\Carbon::today()->format('Y-m-d') }}T15:25:00Z" @if ($expChecked_yes==1) style="display:none;" @endif>
                                            <label class="form-label {!! $Class_end_date !!}">{{ trans('cmspage::template.common.endDateAndTime') }} <span aria-required="true" class="required"> * </span></label>
                                            <div class="input-group date">
                                                {!! Form::text('end_date_time', isset($popupcontent->dtEndDateTime)?date(Config::get('Constant.DEFAULT_DATE_FORMAT').' '.Config::get('Constant.DEFAULT_TIME_FORMAT'),strtotime($popupcontent->dtEndDateTime)):$defaultDt, array('class' => 'form-control', 'data-provider' => 'flatpickr', 'data-date-format' => Config::get('Constant.DEFAULT_DATE_FORMAT'), 'data-enable-time' => '','maxlength'=>160,'size'=>'16','id'=>'popup_end_date','data-exp'=> $expChecked_yes,'data-newvalue','autocomplete'=>'off','onkeypress'=>"javascript: return KeycheckOnlyDate(event);",'onpaste'=>'return false')) !!}
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
                        {{-- Select Image --}}
                        <div class="col-md-12">
                            @if(isset($complaint_highLight->fkIntImgId) && ($complaint_highLight->fkIntImgId != $popupcontent->fkIntImgId))
                            @php $Class_fkIntImgId = " highlitetext"; @endphp
                            @else
                            @php $Class_fkIntImgId = ""; @endphp
                            @endif
                            <div class="image_thumb multi_upload_images cm-floating mb-30">
                                @php $height = isset($settings->height)?$settings->height:292; $width = isset($settings->width)?$settings->width:372; @endphp
                                <label class="form-label {{ $Class_fkIntImgId }}" for="front_logo">
                                    {{ trans('blogs::template.common.selectimage') }} <span aria-required="true" class="required"> * </span>
                                    <span class="img-note" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ trans('popup-content::template.common.imageSize',['height'=>$height, 'width'=>$width]) }}">
                                        <i class="ri-information-line text-primary fs-16"></i>
                                    </span>
                                </label>
                                <div class="fileinput fileinput-new page-media" data-provides="fileinput">
                                    <div class="fileinput-preview thumbnail blog_image_img" data-trigger="fileinput">
                                        @if(old('image_url'))
                                        <img src="{{ old('image_url') }}" />
                                        @elseif(isset($popupcontent->fkIntImgId))
                                        <img src="{!! App\Helpers\resize_image::resize($popupcontent->fkIntImgId,120,120) !!}" />
                                        @else
                                        <div class="dz-message needsclick w-100 text-center">
                                            <div class="dropzone_icon"><i class="display-5 text-muted ri-upload-cloud-2-fill"></i></div>
                                            <h5 class="sbold dropzone-title">Drop files here or click to upload</h5>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="input-group">
                                        <a class="media_manager" data-multiple="false" onclick="MediaManager.open('blog_image');"><span class="fileinput-new"></span></a>
                                        <input class="form-control" type="hidden" id="blog_image" name="img_id" value="{{ isset($popupcontent->fkIntImgId)?$popupcontent->fkIntImgId:old('img_id') }}" />
                                        @php
                                        if (method_exists($MyLibrary, 'GetFolderID')) {
                                        if(isset($popupcontent->fkIntImgId)){
                                        $folderid = App\Helpers\MyLibrary::GetFolderID($popupcontent->fkIntImgId);
                                        @endphp
                                        @if(isset($folderid->fk_folder) && $folderid->fk_folder != '0')
                                        <input class="form-control" type="hidden" id="folder_id" name="folder_id" value="{{ $folderid->fk_folder }}" />
                                        @endif
                                        @php
                                        }
                                        }
                                        @endphp
                                        <input class="form-control" type="hidden" id="image_url" name="image_url" value="{{ old('image_url') }}" />
                                    </div>
                                    <div class="overflow_layer">
                                        <a onclick="MediaManager.open('blog_image');" class="media_manager remove_img"><i class="ri-pencil-line"></i></a>
                                        <a href="javascript:;" class="fileinput-exists remove_img removeimg" data-dismiss="fileinput"><i class="ri-delete-bin-line"></i></a>
                                    </div>
                                </div>
                                <span class="help-block">{{ $errors->first('img_id') }}</span>
                            </div>
                        </div>
                        {{-- Display Info --}}
                        <div class="col-md-12 mb-30">
                            @include('powerpanel.partials.displayInfo',['display' => isset($popupcontent->chrPublish)?$popupcontent->chrPublish:null ])
                        </div>

                        <div class="col-md-12">
                            <div class="form-actions">
                                <button type="submit" name="saveandedit" formmethod="post" class="btn btn-primary bg-gradient waves-effect waves-light btn-label me-1" value="saveandedit">
                                    <div class="flex-shrink-0">
                                        <i class="ri-save-line label-icon align-middle fs-20 me-2"></i>
                                    </div>
                                    {!! trans('contactinfo::template.common.saveandedit') !!}
                                </button>
                                <button type="submit" name="saveandexit" formmethod="post" class="btn btn-secondary bg-gradient waves-effect waves-light btn-label me-1" value="saveandexit">
	                                  <div class="flex-shrink-0">
	                                      <i class="ri-save-3-line label-icon align-middle fs-20 me-2"></i>
	                                  </div>
                                    {!! trans('popup-content::template.common.saveandexit') !!}
                                </button>
                                <a class="btn btn-danger bg-gradient waves-effect waves-light btn-label me-1" href="{{ url('powerpanel/popup') }}">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <i class="ri-close-line label-icon align-middle fs-20 me-2"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            {{ trans('popup-content::template.common.cancel') }}
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div> 
                </div>
            {!! Form::close() !!}
        </div>
    </div>
</div><!--end row-->

@endsection
@section('scripts')
<script type="text/javascript">
    window.site_url = '{!! url("/") !!}';
    var selectedRecord = '{{ isset($popupcontent->fkIntPageId)?$popupcontent->fkIntPageId:' ' }}';
    var user_action = "{{ isset($popupcontent)?'edit':'add' }}"
</script>
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/popup-content/popup-content_validations.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/seo-generator/seo-info-generator.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/custom.js' }}" type="text/javascript"></script>
<script type="text/javascript">
    // $('#modules').select2({
    //     placeholder: "Select Module",
    //     width: '100%',
    //     minimumResultsForSearch: 5
    // }).on("change", function (e) {
    //     $("#modules").closest('.has-error').removeClass('has-error');
    //     $("#modules-error").remove();
    //     $('#records').show();
    // });
    // $('#foritem').select2({
    //     placeholder: "Select Module",
    //     width: '100%'
    // }).on("change", function (e) {
    //     $("#foritem").closest('.has-error').removeClass('has-error');
    //     $("#foritem-error").remove();
    // });
</script>
@endsection