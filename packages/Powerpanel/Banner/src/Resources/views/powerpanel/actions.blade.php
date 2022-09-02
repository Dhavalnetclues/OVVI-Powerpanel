@section('css')
<link href="{{ $CDN_PATH.'resources/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css' }}" rel="stylesheet" type="text/css" />
@endsection
@extends('powerpanel.layouts.app')
@section('title')
{{Config::get('Constant.SITE_NAME')}} - PowerPanel
@endsection
@section('content')
@php $settings = json_decode(Config::get("Constant.MODULE.SETTINGS")); @endphp

<div class="row">
    <div class="col-md-12 settings">
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
            {!! Form::open(['method' => 'post','enctype' => 'multipart/form-data','id'=>'frmBanner']) !!}
                <div class="card">
                    <div class="card-body p-30 pb-0">
                        @if(isset($banners))
                        <div class="row pagetitle-heading mb-3">
                            <div class="col-sm-11 col-11">
                                <h4 class="page-title fw-semibold m-0">{{ $breadcrumb['inner_title']}}</h4>
                            </div>
                            <div class="col-sm-1 col-1 lock-link">
                                @if (File::exists(base_path() . '/resources/views/powerpanel/partials/lockedpage.blade.php') != null)
                                @include('powerpanel.partials.lockedpage',['pagedata'=>$banners])
                                @endif
                            </div>
                        </div>
                        @endif
                        
                        <div class="row">
                            <div class="col-lg-6 col-sm-12">
                                @php if(isset($banners_highLight->varTitle) && ($banners_highLight->varTitle != $banners->varTitle)){
                                $Class_title = " highlitetext";
                                }else{
                                $Class_title = "";
                                } @endphp
                                <div class="{{ $errors->has('title') ? ' has-error' : '' }} form-md-line-input cm-floating">
                                    <label class="form-label {!! $Class_title !!}" for="title">{!! trans('banner::template.common.title') !!}
                                        <span aria-required="true" class="required"> * </span>
                                        <span class="img-note" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Title text will be shown on the image">
                                            <i class="ri-information-line text-primary fs-16"></i>
                                        </span>
                                    </label>
                                    {!! Form::text('title', isset($banners->varTitle)?$banners->varTitle:old('title'), array('maxlength'=>'150','class' => 'form-control input-sm maxlength-handler titlespellingcheck', 'data-url' => 'powerpanel/banners','id' => 'title','autocomplete'=>'off')) !!}
                                    <span style="color:#e73d4a">{{ $errors->first('title') }}</span>
                                </div>
                            </div>

                            <div class="col-lg-6 col-sm-12 d-flex align-items-center">
                                {!! Form::hidden('fkMainRecord', isset($banners->fkMainRecord)?$banners->fkMainRecord:old('fkMainRecord')) !!}
                                @if ((isset($banners->varBannerType) && $banners->varBannerType == 'home_banner') || old('banner_type') == 'home_banner' || (!isset($banners->varBannerType) && old('banner_type') == null))
                                @php $checked_yes = 'checked' @endphp
                                @else
                                @php $checked_yes = '' @endphp
                                @endif
                                @if ((isset($banners->varBannerType) && $banners->varBannerType == 'inner_banner') || old('banner_type') == 'inner_banner')
                                @php $ichecked_innerbaner_yes = 'checked' @endphp
                                @else
                                @php $ichecked_innerbaner_yes = '' @endphp
                                @endif 
                                @php if(isset($banners_highLight->varBannerType) && ($banners_highLight->varBannerType != $banners->varBannerType)){
                                $Class_banner_type = " highlitetext";
                                }else{
                                $Class_banner_type = "";
                                } @endphp
                                <div class="form-group mb-30 {{ $errors->has('banner_type') ? ' has-error' : '' }}">
                                    <label class="form-label mb-1 mb-md-0 fw-medium fs-14 {!! $Class_banner_type !!}" for="banner_type">{!! trans('banner::template.bannerModule.bannerType') !!} <span aria-required="true" class="required"> * </span></label>
                                    <div class="md-radio-inline d-md-inline-block ms-md-3">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input banner" {{ $checked_yes }} type="radio" name="banner_type" id="home_banner" value="home_banner">
                                            <label class="form-check-label" for="home_banner">
                                                {!! trans('banner::template.bannerModule.homeBanner') !!}
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input banner" {{ $ichecked_innerbaner_yes }} type="radio" name="banner_type" id="inner_banner" value="inner_banner">
                                            <label class="form-check-label" for="inner_banner">
                                                {!! trans('banner::template.bannerModule.innerBanner') !!}
                                            </label>
                                        </div>
                                    </div>
                                    <span class="help-block"><strong>{{ $errors->first('banner_type') }}</strong></span>
                                </div>
                            </div>

                            @if ((isset($banners->varBannerVersion) && $banners->varBannerVersion == 'img_banner') || old('bannerversion')=='img_banner' || (!isset($banners->varBannerVersion) && old('bannerversion') == null))
                            @php $checked_yes = 'checked' @endphp
                            @else
                            @php $checked_yes = '' @endphp
                            @endif
                            @if ((isset($banners->varBannerVersion) && $banners->varBannerVersion == 'vid_banner') || old('bannerversion')=='vid_banner')
                            @php $ichecked_vid_yes = 'checked' @endphp
                            @else
                            @php $ichecked_vid_yes = '' @endphp
                            @endif
                            @php if(isset($banners_highLight->varBannerVersion) && ($banners_highLight->varBannerVersion != $banners->varBannerVersion)){
                                $Class_banner_virsion = " highlitetext";
                            }else{
                                $Class_banner_virsion = "";
                            } @endphp
                            <div class="col-lg-6 col-sm-12 bannerversion {{ $errors->has('bannerversion') ? ' has-error' : '' }}" style="display: none;">
                                <label class="form-label {!! $Class_banner_virsion !!}" for="bannerversion">{!! trans('banner::template.bannerModule.version') !!} <span aria-required="true" class="required"> * </span></label>
                                <div class="md-radio-inline">
                                    <div class="form-check form-check-inline cm-floating">
                                        <input class="form-check-input" {{ $checked_yes }} type="radio" name="bannerversion" id="img_banner" value="img_banner">
                                        <label class="form-check-label" for="img_banner">
                                            {!! trans('banner::template.bannerModule.homeBanner') !!}
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline cm-floating">
                                        <input class="form-check-input" {{ $ichecked_vid_yes }} type="radio" name="bannerversion" id="vid_banner" value="vid_banner">
                                        <label class="form-check-label" for="vid_banner">
                                            {!! trans('banner::template.bannerModule.videoBanner') !!}
                                        </label>
                                    </div>
                                </div>
                                <span class="help-block">
                                    <strong>{{ $errors->first('bannerversion') }}</strong>
                                </span>
                            </div>

                            @php if(isset($banners_highLight->fkModuleId) && ($banners_highLight->fkModuleId != $banners->fkModuleId)){
                            $Class_module = " highlitetext";
                            }else{
                            $Class_module = "";
                            } @endphp
                            <div class="col-lg-6 col-sm-12 cm-floating" id="pages" style="display: none;">
                                <label class="form-label {!! $Class_module !!}" for="pages">{!! trans('banner::template.common.selectmodule') !!} <span aria-required="true" class="required"> * </span></label>
                                <select class="form-control" name="modules" id="modules" data-choices>
                                    <option value="">{!! trans('banner::template.common.selectmodule') !!}</option>
                                    @if(count($modules) > 0)
                                    @foreach ($modules as $pagedata)
                                    @php
                                    $avoidModules = array('faq','contact-us','testimonial','blogs','blog-category','news-category','career-category');
                                    @endphp
                                    @if (ucfirst($pagedata->varTitle)!='Home' && Auth::user()->can($pagedata['varModuleName'] . '-list'))
                                    <option data-model="{{ $pagedata->varModelName }}" data-module="{{ $pagedata->varModuleName }}" value="{{ $pagedata->id }}" {{ (isset($banners->fkModuleId) && $pagedata->id == $banners->fkModuleId) || $pagedata->id == old('modules')? 'selected' : '' }} >{{ $pagedata->varTitle }}</option>
                                    @endif
                                    @endforeach
                                    @endif
                                </select>
                                <span style="color:#e73d4a">{{ $errors->first('modules') }}</span>
                            </div>

                            @php if(isset($banners_highLight->fkIntPageId) && ($banners_highLight->fkIntPageId != $banners->fkIntPageId)){
                            $Class_page = " highlitetext";
                            }else{
                            $Class_page = "";
                            } @endphp
                            <div class="col-lg-6 col-sm-12 cm-floating" id="records" style="display: none;">
                                <label class="form-label {!! $Class_page !!}" for="records">{!! trans('banner::template.bannerModule.selectPage') !!}<span aria-required="true" class="required"> * </span></label>
                                <select class="form-control" name="foritem" id="foritem" data-choices style="width:100%">
                                    <option value="">{!! trans('banner::template.bannerModule.selectPage') !!}</option>
                                </select>
                                <span style="color:#e73d4a">{{ $errors->first('foritem') }}</span>
                            </div>

                            @php if(isset($banners_highLight->fkIntImgId) && ($banners_highLight->fkIntImgId != $banners->fkIntImgId)){
                            $Class_image = " highlitetext";
                            }else{
                            $Class_image = "";
                            } @endphp
                            <div class="col-lg-6 col-sm-12" id="home_banner_img">
                                <div class="imguploader {{ $errors->has('img_id') ? ' has-error' : '' }}">
                                    <div class="image_thumb cm-floating">
                                        <label class="form-label {!! $Class_image !!}" for="front_logo">
                                            {!! trans('banner::template.bannerModule.selectBanner') !!} <span aria-required="true" class="required"> * </span>
                                            @php $height = isset($settings->height)?$settings->height:853; $width = isset($settings->width)?$settings->width:1920; @endphp 
                                            <span class="img-note" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ trans('banner::template.common.imageSize',['height'=>'853', 'width'=>'1920']) }}">
                                                <i class="ri-information-line text-primary fs-16"></i>
                                            </span>
                                        </label>
                                        <div class="fileinput fileinput-new page-media" data-provides="fileinput">
                                            <div class="fileinput-preview thumbnail banner_image_img" data-trigger="fileinput">
                                                @if(old('image_url'))
                                                <img src="{{ old('image_url') }}" />
                                                @elseif(isset($banners->fkIntImgId) && $banners->fkIntImgId > 0)
                                                <img  src="{!! App\Helpers\resize_image::resize($banners->fkIntImgId) !!}" />
                                                @else
                                                <div class="dz-message needsclick w-100 text-center">
                                                    <div class="dropzone_icon"><i class="display-5 text-muted ri-upload-cloud-2-fill"></i></div>
                                                    <h5 class="sbold dropzone-title">Drop files here or click to upload</h5>
                                                </div>
                                                @endif
                                            </div>
                                            <div class="input-group">
                                                <a class="media_manager" onclick="MediaManager.open('banner_image');"><span class="fileinput-new"></span></a>
                                                <input class="form-control" type="hidden" id="banner_image" name="img_id" value="{{ isset($banners->fkIntImgId)?$banners->fkIntImgId:old('img_id') }}" />
                                                <input class="form-control" type="hidden" id="image_url" name="image_url" value="{{ old('image_url') }}" />
                                                @php
                                                if (method_exists($MyLibrary, 'GetFolderID')) {
                                                if(isset($banners->fkIntImgId)){
                                                $folderid = App\Helpers\MyLibrary::GetFolderID($banners->fkIntImgId);
                                                @endphp
                                                @if(isset($folderid->fk_folder) && $folderid->fk_folder != '0')
                                                <input class="form-control" type="hidden" id="folder_id" name="folder_id" value="{{ $folderid->fk_folder }}" />
                                                @endif
                                                @php
                                                }
                                                }
                                                @endphp
                                            </div>
                                            <div class="overflow_layer">
                                                <a onclick="MediaManager.open('banner_image');" class="media_manager remove_img"><i class="ri-pencil-line"></i></a>
                                                <a href="javascript:;" class="fileinput-exists remove_img removeimg" data-dismiss="fileinput"><i class="ri-delete-bin-line"></i></a>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <span class="help-block">{{ $errors->first('img_id') }}</span>
                                </div>
                            </div>

                            <!-- inner banner img start-->
                            <div class="col-lg-6 col-sm-12" id="inner_banner_img" >
                                <div class="imguploader {{ $errors->has('img_id_inner') ? ' has-error' : '' }}">
                                    <div class="image_thumb cm-floating">
                                        @php 
                                            if(isset($banners_highLight->fkIntInnerImgId) && ($banners_highLight->fkIntInnerImgId != $banners->fkIntInnerImgId)){
                                                $Class_fkIntInnerImgId = " highlitetext";
                                            }else{
                                                $Class_fkIntInnerImgId = "";
                                            } 
                                        @endphp
                                        <label class="form-label {{ $Class_fkIntInnerImgId }}" for="front_logo">
                                            {!! trans('Select Inner Banner') !!} <span aria-required="true" class="required"> * </span>
                                            @php $height = isset($settings->height)?$settings->height:853; $width = isset($settings->width)?$settings->width:1920; @endphp
                                            <span class="img-note" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ trans('banner::template.common.imageSizeInner',['height'=>'245', 'width'=>'1583']) }}">
                                                <i class="ri-information-line text-primary fs-16"></i>
                                            </span>
                                        </label>
                                        <div class="fileinput fileinput-new page-media" data-provides="fileinput">
                                            <div class="fileinput-preview thumbnail innerbanner_image_img" data-trigger="fileinput">
                                                @if(old('image_url'))
                                                <img src="{{ old('innerimage_url') }}" />
                                                @elseif(isset($banners->fkIntInnerImgId) && $banners->fkIntInnerImgId > 0)
                                                <img src="{!! App\Helpers\resize_image::resize($banners->fkIntInnerImgId) !!}" />
                                                @else
                                                <div class="dz-message needsclick w-100 text-center">
                                                    <div class="dropzone_icon"><i class="display-5 text-muted ri-upload-cloud-2-fill"></i></div>
                                                    <h5 class="sbold dropzone-title">Drop files here or click to upload</h5>
                                                </div>
                                                @endif
                                            </div>
                                            <div class="input-group">
                                                <a class="media_manager" onclick="MediaManager.open('innerbanner_image');"><span class="fileinput-new"></span></a>
                                                <input class="form-control" type="hidden" id="innerbanner_image" name="img_id_inner" value="{{ isset($banners->fkIntInnerImgId)?$banners->fkIntInnerImgId:old('img_id_inner') }}" />
                                                <input class="form-control" type="hidden" id="innerimage_url" name="innerimage_url" value="{{ old('innerimage_url') }}" />
                                                @php
                                                if (method_exists($MyLibrary, 'GetFolderID')) {
                                                if(isset($banners->fkIntInnerImgId)){
                                                $folderid = App\Helpers\MyLibrary::GetFolderID($banners->fkIntInnerImgId);
                                                @endphp
                                                @if(isset($folderid->fk_folder) && $folderid->fk_folder != '0')
                                                <input class="form-control" type="hidden" id="folder_id" name="folder_id" value="{{ $folderid->fk_folder }}" />
                                                @endif
                                                @php
                                                }
                                                }
                                                @endphp
                                            </div>
                                            <div class="overflow_layer">
                                                <a onclick="MediaManager.open('innerbanner_image');" class="media_manager remove_img"><i class="ri-pencil-line"></i></a>
                                                <a href="javascript:;" class="fileinput-exists remove_img removeimg" data-dismiss="fileinput"><i class="ri-delete-bin-line"></i></a>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <span class="help-block">{{ $errors->first('img_id_inner') }}</span>
                                </div>
                            </div>
                            <!-- inner banner img end-->

                            @php
                            if(isset($banners_highLight->fkIntIconId) && ($banners_highLight->fkIntIconId != $banners->fkIntIconId)){
                                $Class_fkIntIconId = " highlitetext";
                            }else{
                                $Class_fkIntIconId = "";
                            } 
                            @endphp
                            <div class="col-lg-6 col-sm-12 iconuploader {{ $errors->has('img_id_icon') ? ' has-error' : '' }}">
                                <div class="image_thumb cm-floating">
                                    <label class="form-label {{ $Class_fkIntIconId }}" for="icon">
                                        {!! trans('Upload Icon') !!} <span aria-required="true" class="required"> * </span>
                                        @php $height = isset($settings->height)?$settings->height:518; $width = isset($settings->width)?$settings->width:110; @endphp 
                                        <span class="img-note" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ trans('banner::template.common.iconSize',['height'=>'110', 'width'=>'110']) }}">
                                            <i class="ri-information-line text-primary fs-16"></i>
                                        </span>
                                    </label>
                                    <div class="fileinput fileinput-new page-media" data-provides="fileinput">
                                        <div class="fileinput-preview thumbnail banner_icon_img" data-trigger="fileinput">
                                            @if(old('icon_url'))
                                                <img src="{{ old('icon_url') }}" />
                                            @elseif(isset($banners->fkIntIconId) && $banners->fkIntIconId > 0)
                                                <img  src="{!! App\Helpers\resize_image::resize($banners->fkIntIconId) !!}" />
                                            @else
                                            <div class="dz-message needsclick w-100 text-center">
                                                <div class="dropzone_icon"><i class="display-5 text-muted ri-upload-cloud-2-fill"></i></div>
                                                <h5 class="sbold dropzone-title">Drop files here or click to upload</h5>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="input-group">
                                            <a class="media_manager" onclick="MediaManager.open('banner_icon');"><span class="fileinput-new"></span></a>
                                            <input class="form-control" type="hidden" id="banner_icon" name="img_id_icon" value="{{ isset($banners->fkIntIconId)?$banners->fkIntIconId:old('img_id_icon') }}" />
                                            <input class="form-control" type="hidden" id="icon_url" name="icon_url" value="{{ old('icon_url') }}" />
                                            @if (method_exists($MyLibrary, 'GetFolderID'))
                                                @if(isset($banners->fkIntIconId))
                                                    @php $folderid = App\Helpers\MyLibrary::GetFolderID($banners->fkIntIconId); @endphp
                                                    @if(isset($folderid->fk_folder) && $folderid->fk_folder != '0')
                                                        <input class="form-control" type="hidden" id="folder_id" name="folder_id" value="{{ $folderid->fk_folder }}" />
                                                    @endif
                                                @endif
                                            @endif  
                                        </div>
                                        <div class="overflow_layer">
                                            <a onclick="MediaManager.open('banner_icon');" class="media_manager remove_img"><i class="ri-pencil-line"></i></a>
                                            <a href="javascript:void(0);" class="fileinput-exists remove_img removeimg" data-dismiss="fileinput"><i class="ri-delete-bin-line"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <span class="help-block">{{ $errors->first('img_id_icon') }}</span>
                            </div>

                            <div class="hide mt-2" id="DisplayVideo" style="display: none">
                                @if(isset($banners_highLight->chrDisplayVideo) && ($banners_highLight->chrDisplayVideo != $banners->chrDisplayVideo))
                                @php $Class_Applicable = " highlitetext"; @endphp
                                @else
                                @php $Class_Applicable = ""; @endphp
                                @endif
                                <div class="row">
                                    <div class="col-lg-3 col-xl-2 col-sm-12">
                                        <div class="form-md-line-input">
                                            <label class="form-label {{ $Class_Applicable }}">Display Video:</label>
                                            @if (isset($banners->chrDisplayVideo) && $banners->chrDisplayVideo == 'Y')
                                            @php $checked_section = true; @endphp
                                            @php $display_Section = ''; @endphp
                                            @else
                                            @php $checked_section = null; 
                                            @endphp
                                            @php $display_Section = 'none'; @endphp
                                            @endif
                                            {{ Form::checkbox('chrDisplayVideo',null,$checked_section, array('class'=>'form-check-input', 'id'=>'chrDisplayVideo')) }}
                                        </div>
                                    </div>
                                    <div class="col-lg-9 col-xl-10 col-sm-12">
                                        @php if(isset($banners_highLight->varVideoLink) && ($banners_highLight->varVideoLink != $banners->varVideoLink)){
                                        $Class_VideoLink = " highlitetext";
                                        }else{
                                        $Class_VideoLink = "";
                                        } @endphp
                                        <div class="{{ $errors->has('videolink') ? ' has-error' : '' }} form-md-line-input cm-floating" id="VideoLinkTEXT" style="display: none;">
                                            <label class="form-label {!! $Class_VideoLink !!}" for="videolink">Video Link<span aria-required="true" class="required"> * </span>
                                                {{-- <span class="img-note" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Please enter">
                                                    <i class="ri-information-line text-primary fs-16"></i>
                                                </span> --}}
                                            </label>
                                            {!! Form::text('videolink', isset($banners->varVideoLink)?$banners->varVideoLink:old('videolink'), array('maxlength'=>'500','class' => 'form-control input-sm maxlength-handler', 'id' => 'videolink','autocomplete'=>'off')) !!}
                                            <span style="color:#e73d4a">
                                                {{ $errors->first('videolink') }}
                                            </span>
                                        </div>

                                        <div class="row hide" id="txtshortdesc">
                                            <div class="col-md-12">
                                                <div class="@if($errors->first('short_description')) has-error @endif form-md-line-input cm-floating">
                                                    @php if(isset($banners_highLight->varShortDescription) && ($banners_highLight->varShortDescription != $banners->varShortDescription)){
                                                    $Class_ShortDescription = " highlitetext";
                                                    }else{
                                                    $Class_ShortDescription = "";
                                                    } @endphp
                                                    <label class="form-label {!! $Class_ShortDescription !!}">Short Description
                                                        <span class="img-note" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Short description text will be shown on the image">
                                                            <i class="ri-information-line text-primary fs-16"></i>
                                                        </span>
                                                    </label>
                                                    {!! Form::textarea('short_description', isset($banners->varShortDescription)?$banners->varShortDescription:old('short_description'), array('maxlength' => isset($settings->short_desc_length)?$settings->short_desc_length:400,'class' => 'form-control seoField maxlength-handler shortdescspellingcheck','id'=>'varShortDescription','rows'=>'2')) !!}
                                                    <span class="help-block">{{ $errors->first('short_description') }}</span> 
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if(isset($banners_highLight->chrDisplayLink) && ($banners_highLight->chrDisplayLink != $banners->chrDisplayLink))
                            @php $Class_NewTab = " highlitetext"; @endphp
                            @else
                            @php $Class_NewTab = ""; @endphp
                            @endif
                            <div id="Links">
                                <div id="DisplayLink" style="display: none;">
                                    <div class="row">
                                        <div class="col-lg-3 col-xl-2 col-sm-12">
                                            <div class="form-md-line-input mt-lg-2">
                                                <label class="form-label mb-1 mb-lg-0 {{ $Class_NewTab }}">Open in New Tab:</label>
                                                @if (isset($banners->chrDisplayLink) && $banners->chrDisplayLink == 'Y')
                                                @php $checked_section_link = true; @endphp
                                                @else
                                                @php $checked_section_link = null;
                                                @endphp
                                                @endif
                                                {{ Form::checkbox('chrDisplayLink',null,$checked_section_link, array('class'=>'form-check-input', 'id'=>'chrDisplayLink')) }}
                                            </div>
                                        </div>
                                        <div class="col-lg-9 col-xl-10 col-sm-12">
                                            @php if(isset($banners_highLight->varLink) && ($banners_highLight->varLink != $banners->varLink)){
                                            $Class_varLink = " highlitetext";
                                            }else{
                                            $Class_varLink = "";
                                            } @endphp
                                            <div class="{{ $errors->has('link') ? ' has-error' : '' }} form-md-line-input cm-floating" id="linkTEXT" style="display: none;">
                                                <label class="form-label {{ $Class_varLink }}" for="link">Link
                                                    <span class="img-note" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Link button will be shown on the image">
                                                        <i class="ri-information-line text-primary fs-16"></i>
                                                    </span>
                                                </label>
                                                {!! Form::text('link', isset($banners->varLink)?$banners->varLink:old('link'), array('maxlength'=>'255','class' => 'form-control input-sm maxlength-handler', 'id' => 'link','autocomplete'=>'off')) !!}
                                                <span style="color:#e73d4a">
                                                    {{ $errors->first('link') }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
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
                                <h4 class="form-section mb-3">{!! trans('banner::template.common.displayinformation') !!}</h4>
                                @php
                                $display_order_attributes = array('class' => 'form-control','autocomplete'=>'off','maxlength'=>'5');
                                @endphp
                                @php if(isset($banners_highLight->intDisplayOrder) && ($banners_highLight->intDisplayOrder != $banners->intDisplayOrder)){
                                $Class_displayorder = " highlitetext";
                                }else{
                                $Class_displayorder = "";
                                } @endphp
                                <div class="@if($errors->first('display_order')) has-error @endif form-md-line-input cm-floating">
                                    <label class="form-label {!! $Class_displayorder !!}" for="display_order">{!! trans('banner::template.common.displayorder') !!} <span aria-required="true" class="required"> * </span></label>
                                    {!! Form::text('display_order',isset($banners->intDisplayOrder)?$banners->intDisplayOrder:$total_banner, $display_order_attributes) !!}
                                    <span class="help-block"><strong>{{ $errors->first('display_order') }}</strong></span>
                                    <div class="publish-info mt-3">
                                        @if(isset($banners_highLight->chrPublish) && ($banners_highLight->chrPublish != $banners->chrPublish))
                                            @php $Class_chrPublish = " highlitetext"; @endphp
                                        @else
                                            @php $Class_chrPublish = ""; @endphp
                                        @endif
                                        @if(isset($banners) && $banners->chrAddStar == 'Y')
                                            <label class="control-label form-label fw-semibold"> Publish/ Unpublish</label>
                                            <input type="hidden" id="chrMenuDisplay" name="chrMenuDisplay" value="{{ isset($banners->chrPublish) ? $banners->chrPublish : '' }}">
                                            <p class="mb-0"><strong>NOTE: </strong> This record is in Approval Request , so it can&#39;t be published/unpublished.</p>
                                        @elseif(isset($banners) && $banners->chrDraft == 'D' && $banners->chrAddStar != 'Y')
                                            @include('powerpanel.partials.displayInfo',['Class_chrPublish'=>$Class_chrPublish,'display' => (isset($banners->chrDraft)?$banners->chrDraft:'D')])
                                        @else
                                            @include('powerpanel.partials.displayInfo',['Class_chrPublish'=>$Class_chrPublish,'display' => (isset($banners->chrPublish)?$banners->chrPublish:'Y')])
                                        @endif
                                    </div>
                                </div>
                            </div>
                            {{-- Content Scheduling --}}
                            <div class="col-lg-6 col-sm-12">
                                @php if(isset($banners_highLight->fkIntVideoId) && ($banners_highLight->fkIntVideoId != $banners->fkIntVideoId)){
                                $Class_video = " highlitetext";
                                }else{
                                $Class_video = "";
                                } @endphp
                                @if(Config::get('Constant.CHRContentScheduling') == 'Y')
                                <h4 class="form-section mb-3">{{ trans('banner::template.common.ContentScheduling') }}</h4>
                                @php $defaultDt = (null !== old('start_date_time'))?old('start_date_time'):date(Config::get('Constant.DEFAULT_DATE_FORMAT').' '.Config::get('Constant.DEFAULT_TIME_FORMAT')); @endphp
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-md-line-input cm-floating">
                                            @php if(isset($banners_highLight->dtDateTime) && ($banners_highLight->dtDateTime != $banners->dtDateTime)){
                                            $Class_date = " highlitetext";
                                            }else{
                                            $Class_date = "";
                                            } @endphp
                                            <label class="form-label {!! $Class_date !!}">{{ trans('banner::template.common.startDateAndTime') }}<span aria-required="true" class="required"> * </span></label>
                                            <div class="input-group date form_meridian_datetime @if($errors->first('start_date_time')) has-error @endif" data-date="{{ Carbon\Carbon::today()->format('Y-m-d') }}T15:25:00Z">
                                                {!! Form::text('start_date_time', date(Config::get('Constant.DEFAULT_DATE_FORMAT').' '.Config::get('Constant.DEFAULT_TIME_FORMAT'),strtotime(isset($banners->dtDateTime)?$banners->dtDateTime:$defaultDt)), array('class' => 'form-control', 'data-provider' => 'flatpickr', 'data-date-format' => Config::get('Constant.DEFAULT_DATE_FORMAT'), 'data-enable-time' => '','maxlength'=>160,'size'=>'16','id'=>'banner_start_date','autocomplete'=>'off','onkeypress'=>"javascript: return KeycheckOnlyDate(event);",'onpaste'=>'return false')) !!}
                                            </div>
                                            <span class="help-block">{{ $errors->first('start_date_time') }}</span>
                                        </div>
                                    </div>
                                    @php $defaultDt = (null !== old('end_date_time'))?old('end_date_time'):null; @endphp
                                    @if ((isset($banners->dtEndDateTime)==null))
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
                                            @php if(isset($banners_highLight->varTitle) && ($banners_highLight->dtEndDateTime != $banners->dtEndDateTime)){
                                            $Class_end_date = " highlitetext";
                                            }else{
                                            $Class_end_date = "";
                                            } @endphp
                                            <div class="cm-floating form_meridian_datetime expirydate @if($errors->first('end_date_time')) has-error @endif" data-date="{{ Carbon\Carbon::today()->format('Y-m-d') }}T15:25:00Z" @if ($expChecked_yes==1) style="display:none;" @endif>
                                                <label class="form-label {!! $Class_end_date !!}" >{{ trans('banner::template.common.endDateAndTime') }} <span aria-required="true" class="required"> * </span></label>
                                                <div class="input-group date">
                                                    {!! Form::text('end_date_time', isset($banners->dtEndDateTime)?date(Config::get('Constant.DEFAULT_DATE_FORMAT').' '.Config::get('Constant.DEFAULT_TIME_FORMAT'),strtotime($banners->dtEndDateTime)):$defaultDt, array('class' => 'form-control', 'data-provider' => 'flatpickr', 'data-date-format' => Config::get('Constant.DEFAULT_DATE_FORMAT'), 'data-enable-time' => '','maxlength'=>160,'size'=>'16','id'=>'banner_end_date','data-exp'=> $expChecked_yes,'data-newvalue','autocomplete'=>'off','onkeypress'=>"javascript: return KeycheckOnlyDate(event);",'onpaste'=>'return false')) !!}
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
                                @endif
                            </div>
                            {{-- Form Action --}}
                            <div class="col-md-12">
                                <div class="form-actions">
                                    @if(isset($banners->fkMainRecord) && $banners->fkMainRecord != 0)
                                    <button type="submit" name="saveandexit" formmethod="post" class="btn btn-primary bg-gradient waves-effect waves-light btn-label me-1" value="saveandexit">
                                        <div class="flex-shrink-0">
                                            <i class="ri-check-line label-icon align-middle fs-20 me-2"></i>
                                        </div>
                                        {!! trans('banner::template.common.approve') !!}
                                    </button>
                                    @else
                                    @if($userIsAdmin)
                                    <button type="submit" name="saveandedit" formmethod="post" class="btn btn-primary bg-gradient waves-effect waves-light btn-label me-1" value="saveandedit">
                                        <div class="flex-shrink-0">
                                            <i class="ri-save-line label-icon align-middle fs-20 me-2"></i>
                                        </div>
                                        {!! trans('banner::template.common.saveandedit') !!}
                                    </button>
                                    <button type="submit" name="saveandexit" formmethod="post" class="btn btn-secondary bg-gradient waves-effect waves-light btn-label me-1" value="saveandexit">
                                        <div class="flex-shrink-0">
                                            <i class="ri-save-3-line label-icon align-middle fs-20 me-2"></i>
                                        </div>
                                        {!! trans('banner::template.common.saveandexit') !!}
                                    </button>
                                    @else
                                    @if((isset($chrNeedAddPermission) && $chrNeedAddPermission == 'N') && (isset($charNeedApproval) && $charNeedApproval == 'N'))
                                    <button type="submit" name="saveandexit" formmethod="post" class="btn btn-secondary bg-gradient waves-effect waves-light btn-label me-1" value="saveandexit">
                                        <div class="flex-shrink-0">
                                            <i class="ri-save-3-line label-icon align-middle fs-20 me-2"></i>
                                        </div>
                                        {!! trans('banner::template.common.saveandexit') !!}
                                    </button>
                                    @else
                                    <button type="submit" name="saveandexit" formmethod="post" class="btn btn-primary bg-gradient waves-effect waves-light btn-label me-1" value="approvesaveandexit">
                                        <div class="flex-shrink-0">
                                            <i class="ri-save-line label-icon align-middle fs-20 me-2"></i>
                                        </div>
                                        {!! trans('banner::template.common.approvesaveandexit') !!}
                                    </button>
                                    @endif
                                    @endif
                        
                                    @endif
                                    <a class="btn btn-danger bg-gradient waves-effect waves-light btn-label me-1" href="{{ url('powerpanel/banners') }}">
                                        <div class="flex-shrink-0">
                                            <i class="ri-close-line label-icon align-middle fs-20 me-2"></i>
                                        </div>
                                        {{ trans('banner::template.common.cancel') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>

@endsection
@section('scripts')
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js' }}" type="text/javascript"></script>
<script type="text/javascript">
    window.site_url = '{!! url("/") !!}';
    var selectedRecord = '{{ isset($banners->fkIntPageId)?$banners->fkIntPageId:' ' }}';
    var user_action = "{{ isset($banners)?'edit':'add' }}";
</script>
<script type="text/javascript">
    function OpenPassword(val) {
        if (val == 'PP') {
            $("#passid").show();
        } else {
            $("#passid").hide();
        }
    }
</script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/custom.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/banner/banners.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/seo-generator/seo-info-generator.js' }}" type="text/javascript"></script>
<script type="text/javascript">
    function isNumber(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    }
    // $('#modules').select2({
    //     // alert('hi');
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