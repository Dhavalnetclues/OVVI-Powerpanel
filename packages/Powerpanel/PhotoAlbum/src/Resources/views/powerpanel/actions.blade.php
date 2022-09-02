@section('css')
<link href="{{ $CDN_PATH.'resources/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css' }}" rel="stylesheet" type="text/css" />
<link href="{{ $CDN_PATH.'resources/global/plugins/bootstrap-taginput/bootstrap-tagsinput.css' }}" rel="stylesheet" type="text/css" />
@endsection
@extends('powerpanel.layouts.app')
@section('title')
{{Config::get('Constant.SITE_NAME')}} - PowerPanel
@endsection
@php $settings = json_decode(Config::get("Constant.MODULE.SETTINGS")); @endphp
@section('content')

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
            {!! Form::open(['method' => 'post','id'=>'frmPhotoAlbum']) !!}
                <div class="card">
                    <div class="card-body p-30">
                        @if(isset($photoAlbum))
                            <div class="row pagetitle-heading mb-3">
                                <div class="col-sm-11 col-11">
                                    <h4 class="page-title fw-semibold m-0">{{ $breadcrumb['inner_title']}}</h4>
                                </div>
                                <div class="col-sm-1 col-1 lock-link">
                                    @if (File::exists(base_path() . '/resources/views/powerpanel/partials/lockedpage.blade.php') != null)
                                    @include('powerpanel.partials.lockedpage',['pagedata'=>$photoAlbum])
                                    @endif
                                </div>
                            </div>
                        @endif

                        {!! Form::hidden('fkMainRecord', isset($photoAlbum->fkMainRecord)?$photoAlbum->fkMainRecord:old('fkMainRecord')) !!}
                        <div class="row">
                            {{-- Sector type --}}
                            <div class="col-lg-6 col-sm-12">
                                <div class="@if($errors->first('sector')) has-error @endif form-md-line-input cm-floating">
                                    @if(isset($photoAlbumHighLight->varSector) && ($photoAlbumHighLight->varSector != $photoAlbum->varSector))
                                    @php $Class_varSector = " highlitetext"; @endphp
                                    @else
                                    @php $Class_varSector = ""; @endphp
                                    @endif
                                    <label class="form-label {{ $Class_varSector }}" for="site_name">Select Sector Type </label>
                                    <select class="form-control" name="sector" id="sector" data-choices>
                                        <option value="">Select Sector Type</option>
                                        @foreach($sector as  $keySector => $ValueSector)
                                        @php $permissionName = 'photoAlbum-list' @endphp
                                        @php $selected = ''; @endphp
                                        @if(isset($photoAlbum->varSector))
                                        @if($keySector == $photoAlbum->varSector)
                                        @php $selected = 'selected';  @endphp
                                        @endif
                                        @endif
                                        <option value="{{$keySector}}" {{ $selected }}>{{ ($ValueSector == "photoAlbum") ? 'Select Sector Type' : $ValueSector }}</option>
                                        @endforeach
                                    </select>
                                    <span class="help-block">{{ $errors->first('sector') }}</span>
                                </div>
                            </div>
                            {{-- Title --}}
                            <div class="col-lg-6 col-sm-12">
                                @if(isset($photoAlbumHighLight->varTitle) && ($photoAlbumHighLight->varTitle != $photoAlbum->varTitle))
                                @php $Class_title = " highlitetext"; @endphp
                                @else
                                @php $Class_title = ""; @endphp
                                @endif
                                <div class="@if($errors->first('title')) has-error @endif form-md-line-input cm-floating">
                                    <label class="form-label {{ $Class_title }}" class="site_name">{{ trans('photoalbum::template.common.title') }} <span aria-required="true" class="required"> * </span></label>
                                    {!! Form::text('title', isset($photoAlbum->varTitle)?$photoAlbum->varTitle:old('title'), array('maxlength' => 150, 'class' => 'form-control hasAlias seoField maxlength-handler titlespellingcheck','autocomplete'=>'off','data-url' => 'powerpanel/photo-album','id'=>'title')) !!}
                                    <span class="help-block">{{ $errors->first('title') }}</span>
                                    <!-- code for alias -->
                                    <div class="link-url mt-2">
                                        {!! Form::hidden(null, null, array('class' => 'hasAlias','data-url' => 'powerpanel/photo-album')) !!}
                                        {!! Form::hidden('alias', isset($photoAlbum->alias->varAlias) ? $photoAlbum->alias->varAlias:old('alias'), array('class' => 'aliasField')) !!}
                                        {!! Form::hidden('oldAlias', isset($photoAlbum->alias->varAlias) ? $photoAlbum->alias->varAlias:old('alias')) !!}
                                        {!! Form::hidden('previewId') !!}
                                        <div class="alias-group {{!isset($photoAlbum->alias->varAlias)?'hide':''}}">
                                            <label class="form-label" for="Url">Url :</label>
                                            @if(isset($photoAlbum->alias->varAlias) && !$userIsAdmin)
                                            <a class="alias">{!! url("/") !!}</a>
                                            @else
                                            @if(auth()->user()->can('photo-album-create'))
                                            <a href="javascript:void(0);" class="alias">{!! url("/") !!}</a>
                                            <a href="javascript:void(0);" class="editAlias" title="Edit">
                                                <i class="ri-pencil-line"></i>
                                            </a>
                                            <a class="without_bg_icon openLink" title="Open Link" onClick="generatePreview('{{url('/previewpage?url='.(App\Helpers\MyLibrary::getFrontUri('photo-album')['uri']))}}');">
                                                <i class="ri-external-link-line" aria-hidden="true"></i>
                                            </a>
                                            @endif
                                            @endif
                                        </div>
                                        <span class="help-block">{{ $errors->first('alias') }}</span>
                                    </div>
                                    <!-- code for alias -->
                                </div>
                            </div>
                            {{-- Select Image --}}
                            <div class="col-lg-6 col-sm-12">
                                @if(isset($photoAlbumHighLight->fkIntImgId) && ($photoAlbumHighLight->fkIntImgId != $photoAlbum->fkIntImgId))
                                @php $Class_fkIntImgId = " highlitetext"; @endphp
                                @else
                                @php $Class_fkIntImgId = ""; @endphp
                                @endif
                                <div class="image_thumb multi_upload_images cm-floating">
                                    @php $height = isset($settings->height)?$settings->height:292; $width = isset($settings->width)?$settings->width:372; @endphp
                                    <label class="form-label {{ $Class_fkIntImgId }}" for="front_logo">
                                        {{ trans('photoalbum::template.common.selectimage') }} <span aria-required="true" class="required"> * </span>
                                        <span class="img-note" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ trans('photoalbum::template.common.imageSize',['height'=>$height, 'width'=>$width]) }}">
                                            <i class="ri-information-line text-primary fs-16"></i>
                                        </span>
                                    </label>
                                    <div class="fileinput fileinput-new page-media" data-provides="fileinput">
                                        <div class="fileinput-preview thumbnail photo_album_image_img" data-trigger="fileinput">
                                            @if(old('image_url'))
                                            <img src="{{ old('image_url') }}" />
                                            @elseif(isset($photoAlbum->fkIntImgId))
                                            <img src="{!! App\Helpers\resize_image::resize($photoAlbum->fkIntImgId,120,120) !!}" />
                                            @else
                                            <div class="dz-message needsclick w-100 text-center">
                                                <div class="dropzone_icon"><i class="display-5 text-muted ri-upload-cloud-2-fill"></i></div>
                                                <h5 class="sbold dropzone-title">Drop files here or click to upload</h5>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="input-group">
                                            <a class="media_manager" data-multiple="false" onclick="MediaManager.open('photo_album_image');"><span class="fileinput-new"></span></a>
                                            <input class="form-control" type="hidden" id="photo_album_image" name="img_id" value="{{ isset($photoAlbum->fkIntImgId)?$photoAlbum->fkIntImgId:old('img_id') }}" />
                                            @php
                                                if (method_exists($MyLibrary, 'GetFolderID')) {
                                            if(isset($photoAlbum->fkIntImgId)){
                                            $folderid = App\Helpers\MyLibrary::GetFolderID($photoAlbum->fkIntImgId);
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
                                            <a onclick="MediaManager.open('photo_album_image');" class="media_manager remove_img"><i class="ri-pencil-line"></i></a>
                                            <a href="javascript:;" class="fileinput-exists remove_img removeimg" data-dismiss="fileinput"><i class="ri-delete-bin-line"></i></a>
                                        </div>
                                    </div>
                                    <span class="help-block">{{ $errors->first('img_id') }}</span>
                                </div>
                            </div>
                            {{-- Short Description --}}
                            <div class="col-lg-6 col-sm-12">
                                <div class="@if($errors->first('short_description')) has-error @endif form-md-line-input cm-floating">
                                    @php if(isset($photoAlbumHighLight->varShortDescription) && ($photoAlbumHighLight->varShortDescription != $photoAlbum->varShortDescription)){
                                    $Class_ShortDescription = " highlitetext";
                                    }else{
                                    $Class_ShortDescription = "";
                                    } @endphp
                                    <label class="form-label {!! $Class_ShortDescription !!}">Short Description<span aria-required="true" class="required"> * </span></label>
                                    {!! Form::textarea('short_description', isset($photoAlbum->varShortDescription)?$photoAlbum->varShortDescription:old('short_description'), array('maxlength' => isset($settings->short_desc_length)?$settings->short_desc_length:500,'class' => 'form-control seoField maxlength-handler shortdescspellingcheck','id'=>'varShortDescription','rows'=>'3')) !!}
                                    <span class="help-block">{{ $errors->first('short_description') }}</span>
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
                                <div class="@if($errors->first('description')) has-error @endif">
                                    @if (Config::get('Constant.DEFAULT_VISUAL') == 'Y')
                                    <div id="body-roll">
                                        @php
                                        $sections = [];
                                        @endphp
                                        @if(isset($photoAlbum))
                                        @php
                                        $sections = json_decode($photoAlbum->txtDescription);
                                        @endphp
                                        @endif
                                        <!-- Builder include -->
                                        @php
                                        Powerpanel\VisualComposer\Controllers\VisualComposerController::page_section(['sections'=>$sections])
                                        @endphp
                                    </div>
                                    @else
                                    @php if(isset($photoAlbumHighLight->txtDescription) && ($photoAlbumHighLight->txtDescription != $photoAlbum->txtDescription)){
                                    $Class_Description = " highlitetext";
                                    }else{
                                    $Class_Description = "";
                                    } @endphp
                                    <label class="form-label {!! $Class_Description !!}">{{ trans('photoalbum::template.common.description') }}</label>
                                    {!! Form::textarea('description', isset($photoAlbum->txtDescription)?$photoAlbum->txtDescription:old('description'), array('class' => 'form-control','id'=>'txtDescription')) !!}
                                    <span class="help-block">{{ $errors->first('description') }}</span>
                                    @endif
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
                                @if(isset($photoAlbum->intSearchRank))
                                    @php $srank = $photoAlbum->intSearchRank; @endphp
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
                                        <strong>Note: </strong> {{ trans('photoalbum::template.common.SearchEntityTools') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SEO Info --}}
                <div class="card">
                    <div class="card-body p-30">
                        <div class=" form-md-line-input">
                            @include('powerpanel.partials.seoInfo',['inf_highLight'=> isset($metaInfo_highLight)?$metaInfo_highLight:false,'form'=>'frmPhotoAlbum','inf'=>isset($metaInfo)?$metaInfo:false,'metaRequired'=>true])
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-body p-30">
                        <div class="row">
                            {{-- Display Information --}}
                            <div class="col-lg-6 col-sm-12">
                                <h4 class="form-section mb-3">{{ trans('photoalbum::template.common.displayinformation') }}</h4>
                                <div class="@if($errors->first('order')) has-error @endif form-md-line-input cm-floating">
                                    @php
                                    $display_order_attributes = array('class' => 'form-control','maxlength'=>5,'placeholder'=>trans('photoalbum::template.common.displayorder'),'autocomplete'=>'off');
                                    @endphp
                                    @if(isset($photoAlbumHighLight->intDisplayOrder) && ($photoAlbumHighLight->intDisplayOrder != $photoAlbum->intDisplayOrder))
                                    @php $Class_intDisplayOrder = " highlitetext"; @endphp
                                    @else
                                    @php $Class_intDisplayOrder = ""; @endphp
                                    @endif
                                    <label class="form-label {{ $Class_intDisplayOrder }}" for="site_name">{{ trans('photoalbum::template.common.displayorder') }} <span aria-required="true" class="required"> * </span></label>
                                    {!! Form::text('order', isset($photoAlbum->intDisplayOrder)?$photoAlbum->intDisplayOrder:'1', $display_order_attributes) !!}
                                    <span style="color: red;">{{ $errors->first('order') }}</span>
                                    <div class="publish-info mt-3">
                                        @if($hasRecords==0)
                                            @if(isset($photoAlbumHighLight->chrPublish) && ($photoAlbumHighLight->chrPublish != $photoAlbum->chrPublish))
                                            @php $Class_chrPublish = " highlitetext"; @endphp
                                            @else
                                            @php $Class_chrPublish = ""; @endphp
                                            @endif
                                            @if((isset($photoAlbum) && $photoAlbum->chrDraft == 'D'))
                                            @include('powerpanel.partials.displayInfo',['Class_chrPublish'=>$Class_chrPublish,'display' => (isset($photoAlbum->chrDraft)?$photoAlbum->chrDraft:'D')])
                                            @else
                                            @include('powerpanel.partials.displayInfo',['Class_chrPublish'=>$Class_chrPublish,'display' => (isset($photoAlbum->chrPublish)?$photoAlbum->chrPublish:'Y')])
                                            @endif
                                        @else
                                            <label class="control-label form-label"> Publish/ Unpublish</label>
                                            @if($hasRecords > 0)
                                            <input type="hidden" id="chrMenuDisplay" name="chrMenuDisplay" value="{{ $photoAlbum->chrPublish }}">
                                            <p><b>NOTE:</b> This album is selected in {{ trans("photoalbum::template.sidebar.photogallery") }}, so it can&#39;t be published/unpublished.</p>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                            {{-- Content Scheduling --}}
                            <div class="col-lg-6 col-sm-12">
                                <div class="row">
                                    <h4 class="form-section mb-3">{{ trans('photoalbum::template.common.ContentScheduling') }}</h4>
                                    @php $defaultDt = (null !== old('start_date_time'))?old('start_date_time'):date(Config::get('Constant.DEFAULT_DATE_FORMAT').' '.Config::get('Constant.DEFAULT_TIME_FORMAT')); @endphp
                                    <div class="col-md-6">
                                        <div class="form-md-line-input cm-floating">
                                            @php if(isset($photoAlbumHighLight->dtDateTime) && ($photoAlbumHighLight->dtDateTime != $photoAlbum->dtDateTime)){
                                            $Class_date = " highlitetext";
                                            }else{
                                            $Class_date = "";
                                            } @endphp
                                            <label class="control-label form-label {!! $Class_date !!}">{{ trans('photoalbum::template.common.startDateAndTime') }}<span aria-required="true" class="required"> * </span></label>
                                            <div class="input-group date form_meridian_datetime @if($errors->first('start_date_time')) has-error @endif" data-date="{{ Carbon\Carbon::today()->format('Y-m-d') }}T15:25:00Z">
                                                {!! Form::text('start_date_time', date(Config::get('Constant.DEFAULT_DATE_FORMAT').' '.Config::get('Constant.DEFAULT_TIME_FORMAT'),strtotime(isset($photoAlbum->dtDateTime)?$photoAlbum->dtDateTime:$defaultDt)), array('class' => 'form-control', 'data-provider' => 'flatpickr', 'data-date-format' => Config::get('Constant.DEFAULT_DATE_FORMAT'), 'data-enable-time' => '','maxlength'=>160,'size'=>'16','id'=>'start_date_time','autocomplete'=>'off','onkeypress'=>"javascript: return KeycheckOnlyDate(event);",'onpaste'=>'return false')) !!}
                                            </div>
                                            <span class="help-block">{{ $errors->first('start_date_time') }}</span>
                                        </div>
                                    </div>
                                    @php $defaultDt = (null !== old('end_date_time'))?old('end_date_time'):null; @endphp
                                    @if ((isset($photoAlbum->dtEndDateTime)==null))
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
                                            @php if(isset($photoAlbumHighLight->varTitle) && ($photoAlbumHighLight->dtEndDateTime != $photoAlbum->dtEndDateTime)){
                                            $Class_end_date = " highlitetext";
                                            }else{
                                            $Class_end_date = "";
                                            } @endphp

                                            <div class="cm-floating form_meridian_datetime expirydate @if($errors->first('end_date_time')) has-error @endif" data-date="{{ Carbon\Carbon::today()->format('Y-m-d') }}T15:25:00Z" @if ($expChecked_yes==1) style="display:none;" @endif>
                                                <label class="form-label {!! $Class_end_date !!}">{{ trans('photoalbum::template.common.endDateAndTime') }} <span aria-required="true" class="required"> * </span></label>
                                                <div class="input-group date">
                                                    {!! Form::text('end_date_time', isset($photoAlbum->dtEndDateTime)?date(Config::get('Constant.DEFAULT_DATE_FORMAT').' '.Config::get('Constant.DEFAULT_TIME_FORMAT'),strtotime($photoAlbum->dtEndDateTime)):$defaultDt, array('class' => 'form-control', 'data-provider' => 'flatpickr', 'data-date-format' => Config::get('Constant.DEFAULT_DATE_FORMAT'), 'data-enable-time' => '','maxlength'=>160,'size'=>'16','id'=>'end_date_time','data-exp'=> $expChecked_yes,'data-newvalue','autocomplete'=>'off','onkeypress'=>"javascript: return KeycheckOnlyDate(event);",'onpaste'=>'return false')) !!}
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
                                    @if(isset($photoAlbum->fkMainRecord) && $photoAlbum->fkMainRecord != 0)
                                    <button type="submit" name="saveandexit" formmethod="post" class="btn btn-primary bg-gradient waves-effect waves-light btn-label me-1" value="saveandexit">
                                        <div class="flex-shrink-0">
                                            <i class="ri-check-line label-icon align-middle fs-20 me-2"></i>
                                        </div>
                                        {!! trans('photoalbum::template.common.approve') !!}
                                    </button>
                                    @else
                                    @if($userIsAdmin)
                                    <button type="submit" name="saveandedit" formmethod="post" class="btn btn-primary bg-gradient waves-effect waves-light btn-label me-1" value="saveandedit">
                                        <div class="flex-shrink-0">
                                            <i class="ri-save-line label-icon align-middle fs-20 me-2"></i>
                                        </div>
                                        {!! trans('photoalbum::template.common.saveandedit') !!}
                                    </button>
                                    <button type="submit" name="saveandexit" formmethod="post" class="btn btn-secondary bg-gradient waves-effect waves-light btn-label me-1" value="saveandexit">
                                        <div class="flex-shrink-0">
                                            <i class="ri-save-3-line label-icon align-middle fs-20 me-2"></i>
                                        </div>
                                        {!! trans('photoalbum::template.common.saveandexit') !!}
                                    </button>
                                    @else
                                    @if((isset($chrNeedAddPermission) && $chrNeedAddPermission == 'N') && (isset($charNeedApproval) && $charNeedApproval == 'N'))
                                    <button type="submit" name="saveandexit" formmethod="post" class="btn btn-secondary bg-gradient waves-effect waves-light btn-label me-1" value="saveandexit">
                                        <div class="flex-shrink-0">
                                            <i class="ri-save-3-line label-icon align-middle fs-20 me-2"></i>
                                        </div>
                                        {!! trans('photoalbum::template.common.saveandexit') !!}
                                    </button>
                                    @else
                                    <button type="submit" name="saveandexit" formmethod="post" class="btn btn-primary bg-gradient waves-effect waves-light btn-label me-1" value="approvesaveandexit">
                                        <div class="flex-shrink-0">
                                            <i class="ri-save-line label-icon align-middle fs-20 me-2"></i>
                                        </div>
                                        {!! trans('photoalbum::template.common.approvesaveandexit') !!}
                                    </button>
                                    @endif
                                    @endif
                                    @endif
                                    <a class="btn btn-danger bg-gradient waves-effect waves-light btn-label me-1" href="{{ url('powerpanel/photo-album') }}">
                                        <div class="flex-shrink-0">
                                            <i class="ri-close-line label-icon align-middle fs-20 me-2"></i>
                                        </div>
                                        {{ trans('photoalbum::template.common.cancel') }}
                                    </a>
                                    @if(isset($photoAlbum) && !empty($photoAlbum) && $userIsAdmin)
                                        <a class="btn btn-info bg-gradient waves-effect waves-light btn-label me-1" title="Preview" onClick="generatePreview('{{url('/previewpage?url='.(App\Helpers\MyLibrary::getFrontUri('photo-album')['uri']))}}');">
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
    var seoFormId = 'frmPhotoAlbum';
    var user_action = "{{ isset($photoAlbum)?'edit':'add' }}";
    var moduleAlias = "{{ App\Helpers\MyLibrary::getFrontUri('photo-album')['moduleAlias'] }}";
    var preview_add_route = '{!! route("powerpanel.photo-album.addpreview") !!}';
    var previewForm = $('#frmPhotoAlbum');
    var isDetailPage = false;
    var categoryAllowed = false;
    function generate_seocontent1(formname) {
    var Meta_Title = document.getElementById('title').value + "";
        var abcd = $('textarea#txtDescription').val();
        var def = abcd.replace(/<a(\s[^>]*)?>.*?<\/a>/ig, "")
        var abc = def.replace(/^(\s*)|(\s*)$/g, '').replace(/\s+/g, ' ');
        var outString1 = abc.replace(/(<([^>]+)>)/ig, "");
        var Meta_Description = outString1.substr(0, 200);
        var Meta_Keyword = document.getElementById('title').value + "" + document.getElementById('title').value + ", " + document.getElementById('title').value;
        $('#varMetaTitle').val(Meta_Title);
        //$('#varMetaKeyword').val(Meta_Keyword);
        $('#varMetaDescription').val(Meta_Description);
        $('#meta_title').html(Meta_Title);
        $('#meta_description').html(Meta_Description);
    }

    @can('photo-album-list')
    categoryAllowed = true;
    @endcan
</script>
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-taginput/bootstrap-tagsinput.min.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/custom.js' }}" type="text/javascript"></script>
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/seo-generator/seo-info-generator.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/custom-alias/alias-generator.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/photoalbum/photo-album-validations.js' }}" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS -->
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