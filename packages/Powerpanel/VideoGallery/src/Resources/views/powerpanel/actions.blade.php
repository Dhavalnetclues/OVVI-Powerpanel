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
        </div>
        @endif
        @if(Session::has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ Session::get('error') }}
        </div>
        @endif
        
        <div class="live-preview">
            {!! Form::open(['method' => 'post','id'=>'frmvideoGallery']) !!}
                <div class="card">
                    <div class="card-body p-30 pb-0">
                        @if(isset($videoGallery))
                            <div class="row pagetitle-heading mb-3">
                                <div class="col-sm-11 col-11">
                                    <h4 class="page-title fw-semibold m-0">{{ $breadcrumb['inner_title']}}</h4>
                                </div>
                                <div class="col-sm-1 col-1 lock-link">
                                    @if (File::exists(base_path() . '/resources/views/powerpanel/partials/lockedpage.blade.php') != null)
                                    @include('powerpanel.partials.lockedpage',['pagedata'=>$videoGallery])
                                    @endif
                                </div>
                            </div>
                        @endif
                        {!! Form::hidden('fkMainRecord', isset($videoGallery->fkMainRecord)?$videoGallery->fkMainRecord:old('fkMainRecord')) !!}
                        <div class="row">
                            {{-- Sector type --}}
                            <div class="col-md-12">
                                <div class="@if($errors->first('sector')) has-error @endif form-md-line-input cm-floating">
                                    @if(isset($videoGallery_highLight->varSector) && ($videoGallery_highLight->varSector != $videoGallery->varSector))
                                    @php $Class_varSector = " highlitetext"; @endphp
                                    @else
                                    @php $Class_varSector = ""; @endphp
                                    @endif
                                    <label class="form_title {{ $Class_varSector }}" for="site_name">Select Sector Type </label>
                                    <select class="form-control" name="sector" id="sector" data-choices>
                                        <option value="">Select Sector Type</option>
                                        @foreach($sector as  $keySector => $ValueSector)
                                        @php $permissionName = 'videoGallery-list' @endphp
                                        @php $selected = ''; @endphp
                                        @if(isset($videoGallery->varSector))
                                        @if($keySector == $videoGallery->varSector)
                                        @php $selected = 'selected';  @endphp
                                        @endif
                                        @endif
                                        <option value="{{$keySector}}" {{ $selected }}>{{ ($ValueSector == "videoGallery") ? 'Select Sector Type' : $ValueSector }}</option>
                                        @endforeach
                                    </select>
                                    <span class="help-block">{{ $errors->first('sector') }}</span>
                                </div>
                            </div>
                            {{-- Title --}}
                            <div class="col-md-12">
                                <div class="@if($errors->first('tag_line')) has-error @endif form-md-line-input cm-floating">
                                    @php if(isset($videoGallery_highLight->varTitle) && ($videoGallery_highLight->varTitle != $videoGallery->varTitle)){
                                    $Class_title = " highlitetext";
                                    }else{
                                    $Class_title = "";
                                    } @endphp
                                    <label class="form_title {!! $Class_title !!}" for="site_name">{{ trans('videogallery::template.common.title') }} <span aria-required="true" class="required"> * </span></label>
                                    {!! Form::text('title', isset($videoGallery->varTitle) ? $videoGallery->varTitle:old('title'), array('maxlength'=>'150','class' => 'form-control seoField maxlength-handler titlespellingcheck','autocomplete'=>'off')) !!}
                                    <span class="help-block">{{ $errors->first('title') }}</span>
                                </div>
                            </div>
                            {{-- Link --}}
                            <div class="col-md-12">
                                <div class="{{ $errors->has('link') ? ' has-error' : '' }} form-md-line-input cm-floating">
                                    @if(isset($videoGallery_highLight->txtLink) && ($videoGallery_highLight->txtLink != $videoGallery->txtLink))
                                    @php $Class_txtLink = " highlitetext"; @endphp
                                    @else
                                    @php $Class_txtLink = ""; @endphp
                                    @endif
                                    <label class="form_title {{ $Class_txtLink }}" for="link">{!! trans('videogallery::template.videoGalleryModule.extLink') !!} <span aria-required="true" class="required"> * </span></label>
                                    {!! Form::text('link', isset($videoGallery->txtLink)?$videoGallery->txtLink:old('link'), array('class' => 'form-control input-sm', 'data-url' => 'powerpanel/video-gallery','id' => 'Link','autocomplete'=>'off')) !!}
                                    <span style="color:#e73d4a">{{ $errors->first('link') }}</span>
                                </div>
                            </div>
                            {{-- Select Video Cover Image --}}
                            <div class="col-md-12">
                                @php if(isset($videoGallery_highLight->varTitle) && ($videoGallery_highLight->fkIntImgId != $videoGallery->fkIntImgId)){
                                $Class_file = " highlitetext";
                                }else{
                                $Class_file = "";
                                } @endphp
                                <div class="image_thumb multi_upload_images cm-floating mb-30">
                                    @php $height = isset($settings->height)?$settings->height:292; $width = isset($settings->width)?$settings->width:372; @endphp
                                    <label class="form_title {{ $Class_file }}" for="front_logo">
                                        Select Video Cover Image<span aria-required="true" class="required"> * </span>
                                        <span class="img-note" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ trans('videogallery::template.common.imageSize',['height'=>$height, 'width'=>$width]) }}">
                                            <i class="ri-information-line text-primary fs-16"></i>
                                        </span>
                                    </label>
                                    <div class="fileinput fileinput-new page-media" data-provides="fileinput">
                                        <div class="fileinput-preview thumbnail video_url_img" data-trigger="fileinput">
                                            @if(old('image_url'))
                                            <img src="{{ old('image_url') }}" />
                                            @elseif(isset($videoGallery->fkIntImgId))
                                            <img src="{!! App\Helpers\resize_image::resize($videoGallery->fkIntImgId,120,120) !!}" />
                                            @else
                                            <div class="dz-message needsclick w-100 text-center">
                                                <div class="dropzone_icon"><i class="display-5 text-muted ri-upload-cloud-2-fill"></i></div>
                                                <h5 class="sbold dropzone-title">Drop files here or click to upload</h5>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="input-group">
                                            <a class="media_manager" data-multiple="false" onclick="MediaManager.open('video_url');"><span class="fileinput-new"></span></a>
                                            <input class="form-control" type="hidden" id="video_url" name="img_id" value="{{ isset($videoGallery->fkIntImgId)?$videoGallery->fkIntImgId:old('img_id') }}" />
                                            @php
                                            if (method_exists($MyLibrary, 'GetFolderID')) {
                                            if(isset($videoGallery->fkIntImgId)){
                                            $folderid = App\Helpers\MyLibrary::GetFolderID($videoGallery->fkIntImgId);
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
                                            <a onclick="MediaManager.open('video_url');" class="media_manager remove_img"><i class="ri-pencil-line"></i></a>
                                            <a href="javascript:;" class="fileinput-exists remove_img removeimg" data-dismiss="fileinput"><i class="ri-delete-bin-line"></i></a>
                                        </div>
                                    </div>
                                    <span class="help-block">{{ $errors->first('img_id') }}</span>
                                </div>
                            </div>
                            {{-- Search Ranking --}}
                            @if(Config::get('Constant.CHRSearchRank') == 'Y')
                                @if(isset($videoGallery->intSearchRank))
                                @php $srank = $videoGallery->intSearchRank; @endphp
                                @else
                                @php
                                $srank = null !== old('search_rank') ? old('search_rank') : 2 ;
                                @endphp
                                @endif
                                @if(isset($videoGallery_highLight->intSearchRank) && ($videoGallery_highLight->intSearchRank != $videoGallery->intSearchRank))
                                @php $Class_intSearchRank = " highlitetext"; @endphp
                                @else
                                @php $Class_intSearchRank = ""; @endphp
                                @endif
                                <div class="col-md-12 mb-30">
                                    <label class="{{ $Class_intSearchRank }} form_title">Search Ranking</label>
                                    <a href="javascript:;" data-bs-toggle="tooltip" class="config" data-bs-placement="bottom" data-bs-content="{{ trans('videogallery::template.common.SearchEntityTools') }}" title="{{ trans('videogallery::template.common.SearchEntityTools') }}"><i class="fa fa-question"></i></a>
                                    <div class="md-radio-inline">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" value="1" name="search_rank" id="yes_radio" @if ($srank == '1') checked @endif>
                                            <label for="yes_radio" id="yes-lbl">High</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" value="2" name="search_rank" id="maybe_radio" @if ($srank == '2') checked @endif>
                                            <label for="maybe_radio" id="maybe-lbl">Medium</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" value="3" name="search_rank" id="no_radio" @if ($srank == '3') checked @endif>
                                            <label for="no_radio" id="no-lbl">Low</label>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body p-30">
                        <div class="row">
                            {{-- Display Information --}}
                            <div class="col-lg-6 col-sm-12">
                                <h4 class="form-section mb-3">{{ trans('videogallery::template.common.displayinformation') }}</h4>
                                <div class="@if($errors->first('order')) has-error @endif form-md-line-input cm-floating">
                                    @php
                                    $display_order_attributes = array('class' => 'form-control','maxlength'=>5,'autocomplete'=>'off');
                                    @endphp
                                    @if(isset($videoGallery_highLight->intDisplayOrder) && ($videoGallery_highLight->intDisplayOrder != $videoGallery->intDisplayOrder))
                                    @php $Class_intDisplayOrder = " highlitetext"; @endphp
                                    @else
                                    @php $Class_intDisplayOrder = ""; @endphp
                                    @endif
                                    <label class="form_title {{ $Class_intDisplayOrder }}" for="site_name">{{ trans('videogallery::template.common.displayorder') }} <span aria-required="true" class="required"> * </span></label>
                                    {!! Form::text('order', isset($videoGallery->intDisplayOrder)?$videoGallery->intDisplayOrder:'1', $display_order_attributes) !!}
                                    <span style="color: red;">{{ $errors->first('order') }}</span>
                                    <div class="publish-info mt-3">
                                        @if(isset($videoGallery_highLight->chrPublish) && ($videoGallery_highLight->chrPublish != $videoGallery->chrPublish))
                                        @php $Class_chrPublish = " highlitetext"; @endphp
                                        @else
                                        @php $Class_chrPublish = ""; @endphp
                                        @endif
                                        @if((isset($videoGallery) && $videoGallery->chrDraft == 'D'))
                                        @include('powerpanel.partials.displayInfo',['Class_chrPublish'=>$Class_chrPublish,'display' => (isset($videoGallery->chrDraft)?$videoGallery->chrDraft:'D')])
                                        @else
                                        @include('powerpanel.partials.displayInfo',['Class_chrPublish'=>$Class_chrPublish,'display' => (isset($videoGallery->chrPublish)?$videoGallery->chrPublish:'Y')])
                                        @endif
                                    </div>
                                </div>
                            </div>
                            {{-- Content Scheduling --}}
                            <div class="col-lg-6 col-sm-12">
                                <h4 class="form-section mb-3">{{ trans('videogallery::template.common.ContentScheduling') }}</h4>
                                @php $defaultDt = (null !== old('start_date_time'))?old('start_date_time'):date(Config::get('Constant.DEFAULT_DATE_FORMAT').' '.Config::get('Constant.DEFAULT_TIME_FORMAT')); @endphp
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-md-line-input cm-floating">
                                            @php if(isset($videoGallery_highLight->dtDateTime) && ($videoGallery_highLight->dtDateTime != $videoGallery->dtDateTime)){
                                            $Class_date = " highlitetext";
                                            }else{
                                            $Class_date = "";
                                            } @endphp
                                            <label class="form-label {!! $Class_date !!}">{{ trans('videogallery::template.common.startDateAndTime') }}<span aria-required="true" class="required"> * </span></label>
                                            <div class="input-group date form_meridian_datetime @if($errors->first('start_date_time')) has-error @endif" data-date="{{ Carbon\Carbon::today()->format('Y-m-d') }}T15:25:00Z">
                                                {!! Form::text('start_date_time', date(Config::get('Constant.DEFAULT_DATE_FORMAT').' '.Config::get('Constant.DEFAULT_TIME_FORMAT'),strtotime(isset($videoGallery->dtDateTime)?$videoGallery->dtDateTime:$defaultDt)), array('class' => 'form-control', 'data-provider' => 'flatpickr', 'data-date-format' => Config::get('Constant.DEFAULT_DATE_FORMAT'), 'data-enable-time' => '','maxlength'=>160,'size'=>'16','id'=>'start_date_time','autocomplete'=>'off','onkeypress'=>"javascript: return KeycheckOnlyDate(event);",'onpaste'=>'return false')) !!}
                                            </div>
                                            <span class="help-block">{{ $errors->first('start_date_time') }}</span>
                                        </div>
                                    </div>
                                    @php $defaultDt = (null !== old('end_date_time'))?old('end_date_time'):null; @endphp
                                    @if ((isset($videoGallery->dtEndDateTime)==null))
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
                                            @php if(isset($videoGallery_highLight->varTitle) && ($videoGallery_highLight->dtEndDateTime != $videoGallery->dtEndDateTime)){
                                            $Class_end_date = " highlitetext";
                                            }else{
                                            $Class_end_date = "";
                                            } @endphp
                                            <div class="cm-floating form_meridian_datetime expirydate @if($errors->first('end_date_time')) has-error @endif" data-date="{{ Carbon\Carbon::today()->format('Y-m-d') }}T15:25:00Z" @if ($expChecked_yes==1) style="display:none;" @endif>
                                                <label class="form-label {!! $Class_end_date !!}">{{ trans('videogallery::template.common.endDateAndTime') }}<span aria-required="true" class="required"> * </span></label>
                                                <div class="input-group date">
                                                    {!! Form::text('end_date_time', isset($videoGallery->dtEndDateTime)?date(Config::get('Constant.DEFAULT_DATE_FORMAT').' '.Config::get('Constant.DEFAULT_TIME_FORMAT'),strtotime($videoGallery->dtEndDateTime)):$defaultDt, array('class' => 'form-control', 'data-provider' => 'flatpickr', 'data-date-format' => Config::get('Constant.DEFAULT_DATE_FORMAT'), 'data-enable-time' => '','maxlength'=>160,'size'=>'16','id'=>'end_date_time','data-exp'=> $expChecked_yes,'data-newvalue','autocomplete'=>'off','onkeypress'=>"javascript: return KeycheckOnlyDate(event);",'onpaste'=>'return false')) !!}
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
                                    @if(isset($videoGallery->fkMainRecord) && $videoGallery->fkMainRecord != 0)
                                    <button type="submit" name="approve" formmethod="post" class="btn btn-primary bg-gradient waves-effect waves-light btn-label me-1" value="approve">
                                        <div class="flex-shrink-0">
                                            <i class="ri-check-line label-icon align-middle fs-20 me-2"></i>
                                        </div>
                                        {!! trans('videogallery::template.common.approve') !!}
                                    </button>
                                    @else
                                    @if($userIsAdmin)
                                    <button type="submit" name="saveandedit" formmethod="post" class="btn btn-primary bg-gradient waves-effect waves-light btn-label me-1" value="saveandedit">
                                        <div class="flex-shrink-0">
                                            <i class="ri-save-line label-icon align-middle fs-20 me-2"></i>
                                        </div>
                                        {!! trans('videogallery::template.common.saveandedit') !!}
                                    </button>
                                    <button type="submit" name="saveandexit" formmethod="post" class="btn btn-secondary bg-gradient waves-effect waves-light btn-label me-1" value="saveandexit">
                                        <div class="flex-shrink-0">
                                            <i class="ri-save-3-line label-icon align-middle fs-20 me-2"></i>
                                        </div>
                                        {!! trans('videogallery::template.common.saveandexit') !!}
                                    </button>
                                    @else
                                    @if((isset($chrNeedAddPermission) && $chrNeedAddPermission == 'N') && (isset($charNeedApproval) && $charNeedApproval == 'N'))
                                    <button type="submit" name="saveandexit" formmethod="post" class="btn btn-secondary bg-gradient waves-effect waves-light btn-label me-1" value="saveandexit">
                                        <div class="flex-shrink-0">
                                            <i class="ri-save-3-line label-icon align-middle fs-20 me-2"></i>
                                        </div>
                                        {!! trans('videogallery::template.common.saveandexit') !!}
                                    </button>
                                    @else
                                    <button type="submit" name="saveandexit" formmethod="post" class="btn btn-primary bg-gradient waves-effect waves-light btn-label me-1" value="approvesaveandexit">
                                        <div class="flex-shrink-0">
                                            <i class="ri-save-line label-icon align-middle fs-20 me-2"></i>
                                        </div>
                                        {!! trans('videogallery::template.common.approvesaveandexit') !!}
                                    </button>
                                    @endif
                                    @endif
                                    @endif
                                    <a class="btn btn-danger bg-gradient waves-effect waves-light btn-label me-1" href="{{ url('powerpanel/video-gallery') }}">
                                        <div class="flex-shrink-0">
                                            <i class="ri-close-line label-icon align-middle fs-20 me-2"></i>
                                        </div>
                                        {{ trans('videogallery::template.common.cancel') }}
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
    var user_action = "{{ isset($videoGallery)?'edit':'add' }}";
    var moduleAlias = 'videoGallery';
</script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/videogallery/video_gallery_validations.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/custom.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/seo-generator/seo-info-generator.js' }}" type="text/javascript"></script>
@endsection