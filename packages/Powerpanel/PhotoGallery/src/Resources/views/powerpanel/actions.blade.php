@section('css')
@endsection
@extends('powerpanel.layouts.app')
@section('title')
{{Config::get('Constant.SITE_NAME')}} - PowerPanel
@stop
@section('content')
@php $settings = json_decode(Config::get("Constant.MODULE.SETTINGS")); @endphp

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
            {!! Form::open(['method' => 'post','id'=>'frmphotoGallery']) !!}
                <div class="card">
                    <div class="card-body p-30 pb-0">
                        @if(isset($photoGallery))
                            <div class="row pagetitle-heading mb-3">
                                <div class="col-sm-11 col-11">
                                    <h4 class="page-title fw-semibold m-0">{{ $breadcrumb['inner_title']}}</h4>
                                </div>
                                <div class="col-sm-1 col-1 lock-link">
                                    @if (File::exists(base_path() . '/resources/views/powerpanel/partials/lockedpage.blade.php') != null)
                                    @include('powerpanel.partials.lockedpage',['pagedata'=>$photoGallery])
                                    @endif
                                </div>
                            </div>
                        @endif
                        {!! Form::hidden('fkMainRecord', isset($photoGallery->fkMainRecord)?$photoGallery->fkMainRecord:old('fkMainRecord')) !!}
                        <div class="row">
                            {{-- Title --}}
                            <div class="col-md-12">
                                <div class="@if($errors->first('tag_line')) has-error @endif form-md-line-input cm-floating">
                                    @php if(isset($photoGallery_highLight->varTitle) && ($photoGallery_highLight->varTitle != $photoGallery->varTitle)){
                                    $Class_title = " highlitetext";
                                    }else{
                                    $Class_title = "";
                                    } @endphp
                                    <label class="form-label {!! $Class_title !!}" for="site_name">{{ trans('photogallery::template.common.title') }} <span aria-required="true" class="required"> * </span></label>
                                    {!! Form::text('title', isset($photoGallery->varTitle) ? $photoGallery->varTitle:old('title'), array('maxlength'=>'150','class' => 'form-control seoField maxlength-handler titlespellingcheck','autocomplete'=>'off')) !!}
                                    <span class="help-block">{{ $errors->first('title') }}</span>
                                </div>
                            </div>
                            {{-- Select Photo Album --}}
                            <div class="col-md-12 cm-floating" id="pages">
                                @if(isset($photoGallery_highLight->intPhotoAlbumId) && ($photoGallery_highLight->intPhotoAlbumId != $photoGallery->intPhotoAlbumId))
                                @php $Class_intPhotoAlbumId = " highlitetext"; @endphp
                                @else
                                @php $Class_intPhotoAlbumId = ""; @endphp
                                @endif
                                <label class="form-label {{ $Class_intPhotoAlbumId }}" for="pages">Select Photo Album<span aria-required="true" class="required"> * </span></label>
                                <select class="form-control" name="photoAlbumId" id="photoAlbumsDropDownList" data-choices>
                                    <option value="">Select Photo Album</option>
                                    @if(count($photoAlbumsDropDownList) > 0)
                                    @foreach ($photoAlbumsDropDownList as $albumdata)
                                    <option data-model="{{ $albumdata->varModelName }}" data-module="{{ $albumdata->varModuleName }}" value="{{ $albumdata->id }}" {{ (isset($photoGallery->intPhotoAlbumId) && $albumdata->id == $photoGallery->intPhotoAlbumId) || $albumdata->id == old('photoAlbumId')? 'selected' : '' }} >{{ $albumdata->varTitle }}</option>
                                    @endforeach
                                    @endif
                                </select>
                                <span style="color:#e73d4a">{{ $errors->first('photoAlbumId') }}</span>
                            </div>
                            {{-- Select Image --}}
                            <div class="col-md-12">
                                @php if(isset($photoGallery_highLight->varTitle) && ($photoGallery_highLight->fkIntImgId != $photoGallery->fkIntImgId)){
                                $Class_file = " highlitetext";
                                }else{
                                $Class_file = "";
                                } @endphp
                                <div class="image_thumb multi_upload_images cm-floating mb-30">
                                    @php $height = isset($settings->height)?$settings->height:292; $width = isset($settings->width)?$settings->width:372; @endphp
                                    <label class="form-label {{ $Class_file }}" for="front_logo">
                                        {{ trans('photogallery::template.common.selectimage') }}<span aria-required="true" class="required"> * </span>
                                        <span class="img-note" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ trans('photogallery::template.common.imageSize',['height'=>$height, 'width'=>$width]) }}">
                                            <i class="ri-information-line text-primary fs-16"></i>
                                        </span>
                                    </label>
                                    <div class="fileinput fileinput-new page-media" data-provides="fileinput">
                                        <div class="fileinput-preview thumbnail bankcoin_image_img" data-trigger="fileinput">
                                            @if(old('image_url'))
                                            <img src="{{ old('image_url') }}" />
                                            @elseif(isset($photoGallery->fkIntImgId))
                                            <img src="{!! App\Helpers\resize_image::resize($photoGallery->fkIntImgId,120,120) !!}" />
                                            @else
                                            <div class="dz-message needsclick w-100 text-center">
                                                <div class="dropzone_icon"><i class="display-5 text-muted ri-upload-cloud-2-fill"></i></div>
                                                <h5 class="sbold dropzone-title">Drop files here or click to upload</h5>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="input-group">
                                            <a class="media_manager" data-multiple="false" onclick="MediaManager.open('bankcoin_image');"><span class="fileinput-new"></span></a>
                                            <input class="form-control" type="hidden" id="bankcoin_image" name="img_id" value="{{ isset($photoGallery->fkIntImgId)?$photoGallery->fkIntImgId:old('img_id') }}" />
                                                @php
                                                if (method_exists($MyLibrary, 'GetFolderID')) {
                                                if(isset($photoGallery->fkIntImgId)){
                                                $folderid = App\Helpers\MyLibrary::GetFolderID($photoGallery->fkIntImgId);
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
                                            <a onclick="MediaManager.open('bankcoin_image');" class="media_manager remove_img"><i class="ri-pencil-line"></i></a>
                                            <a href="javascript:;" class="fileinput-exists remove_img removeimg" data-dismiss="fileinput"><i class="ri-delete-bin-line"></i></a>
                                        </div>
                                    </div>
                                    <span class="help-block">{{ $errors->first('img_id') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body p-30">
                        <div class="row">
                            {{-- Display Information --}}
                            <div class="col-lg-6 col-sm-12">
                                <h4 class="form-section mb-3">{{ trans('photogallery::template.common.displayinformation') }}</h4>
                                <div class="@if($errors->first('order')) has-error @endif form-md-line-input cm-floating">
                                    @php
                                    $display_order_attributes = array('class' => 'form-control','maxlength'=>5,'autocomplete'=>'off');
                                    @endphp
                                    @if(isset($photoGallery_highLight->intDisplayOrder) && ($photoGallery_highLight->intDisplayOrder != $photoGallery->intDisplayOrder))
                                    @php $Class_intDisplayOrder = " highlitetext"; @endphp
                                    @else
                                    @php $Class_intDisplayOrder = ""; @endphp
                                    @endif
                                    <label class="form-label {{ $Class_intDisplayOrder }}" for="site_name">{{ trans('photogallery::template.common.displayorder') }} <span aria-required="true" class="required"> * </span></label>
                                    {!! Form::text('order', isset($photoGallery->intDisplayOrder)?$photoGallery->intDisplayOrder:'1', $display_order_attributes) !!}
                                    <span style="color: red;">{{ $errors->first('order') }}</span>
                                    <div class="publish-info mt-3">
                                        @if(isset($photoGallery_highLight->chrPublish) && ($photoGallery_highLight->chrPublish != $photoGallery->chrPublish))
                                        @php $Class_chrPublish = " highlitetext"; @endphp
                                        @else
                                        @php $Class_chrPublish = ""; @endphp
                                        @endif
                                        @if((isset($photoGallery) && $photoGallery->chrDraft == 'D'))
                                        @include('powerpanel.partials.displayInfo',['Class_chrPublish'=>$Class_chrPublish,'display' => (isset($photoGallery->chrDraft)?$photoGallery->chrDraft:'D')])
                                        @else
                                        @include('powerpanel.partials.displayInfo',['Class_chrPublish'=>$Class_chrPublish,'display' => (isset($photoGallery->chrPublish)?$photoGallery->chrPublish:'Y')])
                                        @endif
                                    </div>
                                </div>
                            </div>
                            {{-- Content Scheduling --}}
                            <div class="col-lg-6 col-sm-12">
                                <h4 class="form-section mb-3">{{ trans('photogallery::template.common.ContentScheduling') }}</h4>
                                @php $defaultDt = (null !== old('start_date_time'))?old('start_date_time'):date(Config::get('Constant.DEFAULT_DATE_FORMAT').' '.Config::get('Constant.DEFAULT_TIME_FORMAT')); @endphp
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-md-line-input cm-floating">
                                            @php if(isset($photoGallery_highLight->dtDateTime) && ($photoGallery_highLight->dtDateTime != $photoGallery->dtDateTime)){
                                            $Class_date = " highlitetext";
                                            }else{
                                            $Class_date = "";
                                            } @endphp
                                            <label class="control-label form-label {!! $Class_date !!}">{{ trans('photogallery::template.common.startDateAndTime') }}<span aria-required="true" class="required"> * </span></label>
                                            <div class="input-group date form_meridian_datetime @if($errors->first('start_date_time')) has-error @endif" data-date="{{ Carbon\Carbon::today()->format('Y-m-d') }}T15:25:00Z">
                                                {!! Form::text('start_date_time', date(Config::get('Constant.DEFAULT_DATE_FORMAT').' '.Config::get('Constant.DEFAULT_TIME_FORMAT'),strtotime(isset($photoGallery->dtDateTime)?$photoGallery->dtDateTime:$defaultDt)), array('class' => 'form-control', 'data-provider' => 'flatpickr', 'data-date-format' => Config::get('Constant.DEFAULT_DATE_FORMAT'), 'data-enable-time' => '','maxlength'=>160,'size'=>'16','id'=>'start_date_time','autocomplete'=>'off','onkeypress'=>"javascript: return KeycheckOnlyDate(event);",'onpaste'=>'return false')) !!}
                                            </div>
                                            <span class="help-block">{{ $errors->first('start_date_time') }}</span>
                                        </div>
                                    </div>
                                    @php $defaultDt = (null !== old('end_date_time'))?old('end_date_time'):null; @endphp
                                    @if ((isset($photoGallery->dtEndDateTime)==null))
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
                                            @php if(isset($photoGallery_highLight->varTitle) && ($photoGallery_highLight->dtEndDateTime != $photoGallery->dtEndDateTime)){
                                            $Class_end_date = " highlitetext";
                                            }else{
                                            $Class_end_date = "";
                                            } @endphp
                                            <div class="cm-floating form_meridian_datetime expirydate @if($errors->first('end_date_time')) has-error @endif" data-date="{{ Carbon\Carbon::today()->format('Y-m-d') }}T15:25:00Z" @if ($expChecked_yes==1) style="display:none;" @endif>
                                                <label class="form-label {!! $Class_end_date !!}">{{ trans('photogallery::template.common.endDateAndTime') }} <span aria-required="true" class="required"> * </span></label>
                                                <div class="input-group date">
                                                    {!! Form::text('end_date_time', isset($photoGallery->dtEndDateTime)?date(Config::get('Constant.DEFAULT_DATE_FORMAT').' '.Config::get('Constant.DEFAULT_TIME_FORMAT'),strtotime($photoGallery->dtEndDateTime)):$defaultDt, array('class' => 'form-control', 'data-provider' => 'flatpickr', 'data-date-format' => Config::get('Constant.DEFAULT_DATE_FORMAT'), 'data-enable-time' => '','maxlength'=>160,'size'=>'16','id'=>'end_date_time','data-exp'=> $expChecked_yes,'data-newvalue','autocomplete'=>'off','onkeypress'=>"javascript: return KeycheckOnlyDate(event);",'onpaste'=>'return false')) !!}
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
                                    @if(isset($photoGallery->fkMainRecord) && $photoGallery->fkMainRecord != 0)
                                    <button type="submit" name="saveandexit" formmethod="post" class="btn btn-primary bg-gradient waves-effect waves-light btn-label me-1" value="saveandexit">
                                        <div class="flex-shrink-0">
                                            <i class="ri-check-line label-icon align-middle fs-20 me-2"></i>
                                        </div>
                                        {!! trans('photogallery::template.common.approve') !!}
                                    </button>
                                    @else
                                    @if($userIsAdmin)
                                    <button type="submit" name="saveandedit" formmethod="post" class="btn btn-primary bg-gradient waves-effect waves-light btn-label me-1" value="saveandedit">
                                        <div class="flex-shrink-0">
                                            <i class="ri-save-line label-icon align-middle fs-20 me-2"></i>
                                        </div>
                                        {!! trans('photogallery::template.common.saveandedit') !!}
                                    </button>
                                    <button type="submit" name="saveandexit" formmethod="post" class="btn btn-secondary bg-gradient waves-effect waves-light btn-label me-1" value="saveandexit">
                                        <div class="flex-shrink-0">
                                            <i class="ri-save-3-line label-icon align-middle fs-20 me-2"></i>
                                        </div>
                                        {!! trans('photogallery::template.common.saveandexit') !!}
                                    </button>
                                    @else
                                    @if((isset($chrNeedAddPermission) && $chrNeedAddPermission == 'N') && (isset($charNeedApproval) && $charNeedApproval == 'N'))
                                    <button type="submit" name="saveandexit" formmethod="post" class="btn btn-secondary bg-gradient waves-effect waves-light btn-label me-1" value="saveandexit">
                                        <div class="flex-shrink-0">
                                            <i class="ri-save-3-line label-icon align-middle fs-20 me-2"></i>
                                        </div>
                                        {!! trans('photogallery::template.common.saveandexit') !!}
                                    </button>
                                    @else
                                    <button type="submit" name="saveandexit" formmethod="post" class="btn btn-primary bg-gradient waves-effect waves-light btn-label me-1" value="approvesaveandexit">
                                        <div class="flex-shrink-0">
                                            <i class="ri-save-line label-icon align-middle fs-20 me-2"></i>
                                        </div>
                                        {!! trans('photogallery::template.common.approvesaveandexit') !!}
                                    </button>
                                    @endif
                                    @endif
                                    @endif
                                    <a class="btn btn-danger bg-gradient waves-effect waves-light btn-label me-1" href="{{ url('powerpanel/photo-gallery') }}">
                                        <div class="flex-shrink-0">
                                            <i class="ri-close-line label-icon align-middle fs-20 me-2"></i>
                                        </div>
                                        {{ trans('photogallery::template.common.cancel') }}
                                    </a>
                                </div>
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
    var user_action = "{{ isset($photoGallery)?'edit':'add' }}";
    var moduleAlias = 'photo-gallery';
</script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/photogallery/photo_gallery_validations.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/custom.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/seo-generator/seo-info-generator.js' }}" type="text/javascript"></script>
@endsection