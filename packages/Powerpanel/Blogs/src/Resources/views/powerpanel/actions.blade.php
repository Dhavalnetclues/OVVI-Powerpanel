@section('css')
    <link href="{{ $CDN_PATH.'resources/global/plugins/bootstrap-taginput/bootstrap-tagsinput.css' }}" rel="stylesheet" type="text/css" />
@endsection
@extends('powerpanel.layouts.app')
@section('title')
{{Config::get('Constant.SITE_NAME')}} - PowerPanel
@endsection
@section('content')
@php $settings = json_decode(Config::get("Constant.MODULE.SETTINGS")); @endphp
{{-- @include('powerpanel.partials.breadcrumbs') --}}


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
            {!! Form::open(['method' => 'post','id'=>'frmBlogs']) !!}
            {!! Form::hidden('fkMainRecord', isset($blogs->fkMainRecord)?$blogs->fkMainRecord:old('fkMainRecord')) !!}
            <div class="card">
                <div class="card-body p-30 pb-0">
                    @if(isset($blogs))
                    <div class="row pagetitle-heading mb-4">
                        <div class="col-sm-11 col-11">
                            <h4 class="page-title fw-semibold m-0">{{ $breadcrumb['inner_title']}}</h4>
                        </div>
                        <div class="col-sm-1 col-1 lock-link">
                            @if (File::exists(base_path() . '/resources/views/powerpanel/partials/lockedpage.blade.php') != null)
                            @include('powerpanel.partials.lockedpage',['pagedata'=>$blogs])
                            @endif
                        </div>
                    </div>
                    @endif
                    
                    <div class="row @if($errors->first('tag_line')) has-error @endif">
                        <div class="col-lg-6 col-sm-12 @if($errors->first('sector')) has-error @endif">
                            <div class="form-md-line-input cm-floating"> 
                                @if(isset($blogs_highLight->varSector) && ($blogs_highLight->varSector != $blogs->varSector))
                                @php $Class_varSector = " highlitetext"; @endphp
                                @else
                                @php $Class_varSector = ""; @endphp
                                @endif
                                <label class="form-label {{ $Class_varSector }}" for="site_name">Select Sector Type </label>
                                <select class="form-control" name="sector" id="sector" data-choices>
                                    <option value="">Select Sector Type</option>
                                    @foreach($sector as  $keySector => $ValueSector)
                                    @php $permissionName = 'blogs-list' @endphp
                                    @php $selected = ''; @endphp
                                    @if(isset($blogs->varSector))
                                    @if($keySector == $blogs->varSector)
                                    @php $selected = 'selected';  @endphp
                                    @endif
                                    @endif
                                    <option value="{{$keySector}}" {{ $selected }}>{{ ($ValueSector == "blogs") ? 'Select Sector Type' : $ValueSector }}</option>
                                    @endforeach
                                </select>
                                <span class="help-block">{{ $errors->first('sector') }}</span>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-md-line-input cm-floating"> 
                                @php
                                if(isset($blogs_highLight->intFKCategory) && ($blogs_highLight->intFKCategory != $blogs->intFKCategory)){
                                $Class_title = " highlitetext";
                                }else{
                                $Class_title = "";
                                }
                                $currentCatAlias = '';
                                @endphp
                                <label class="form-label {{ $Class_title }}" for="site_name">Select Category <span aria-required="true" class="required"> * </span></label>
                                <select class="form-control" name="category_id" data-choices>
                                    <option value="">Select Category</option>
                                    @foreach ($blogCategory as $cat)
                                    @php $permissionName = 'blogs-list' @endphp
                                    @php $selected = ''; @endphp
                                    @if(isset($blogs->intFKCategory))
                                    @if($cat['id'] == $blogs->intFKCategory)
                                    @php $selected = 'selected'; $currentCatAlias = $cat['alias']['varAlias'];  @endphp
                                    @endif
                                    @endif
                                    <option value="{{ $cat['id'] }}" data-categryalias="{{ $cat['alias']['varAlias'] }}" {{ $selected }} >{{ $cat['varModuleName']== "blogs"?'Select Category':$cat['varTitle'] }}</option>
                                    @endforeach
                                </select>
                                <span class="help-block">{{ $errors->first('category') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-sm-12">
                            <div class="@if($errors->first('title')) has-error @endif form-md-line-input cm-floating">
                                @php if(isset($blogs_highLight->varTitle) && ($blogs_highLight->varTitle != $blogs->varTitle)){
                                $Class_title = " highlitetext";
                                }else{
                                $Class_title = "";
                                } @endphp
                                <label class="form-label {!! $Class_title !!}" for="site_name">{{ trans('blogs::template.common.title') }} <span aria-required="true" class="required"> * </span></label>
                                {!! Form::text('title', isset($blogs->varTitle) ? $blogs->varTitle:old('title'), array('maxlength'=>'150','id'=>'title', 'class' => 'form-control hasAlias seoField maxlength-handler titlespellingcheck','autocomplete'=>'off')) !!}
                                <span class="help-block">{{ $errors->first('title') }}</span>
                                <div class="link-url mt-2">
                                    <!-- code for alias -->
                                    {!! Form::hidden(null, null, array('class' => 'hasAlias','data-url' => 'powerpanel/blogs')) !!}
                                    {!! Form::hidden('alias', isset($blogs->alias->varAlias) ? $blogs->alias->varAlias : old('alias'), array('class' => 'aliasField')) !!}
                                    {!! Form::hidden('oldAlias', isset($blogs->alias->varAlias)?$blogs->alias->varAlias : old('alias')) !!}
                                    {!! Form::hidden('previewId') !!}
                                    <div class="alias-group {{!isset($blogs->alias)?'hide':''}}">
                                        <label class="form-label m-0" for="Url">{{ trans('blogs::template.common.url') }} :</label>
                                        @if(isset($blogs->alias->varAlias) && !$userIsAdmin)
                                            <a class="alias">{!! url("/") !!}</a>
                                        @else
                                            @if(auth()->user()->can('blogs-create'))
                                            <a href="javascript:void;" class="alias">{!! url("/") !!}</a>
                                            <a href="javascript:void(0);" class="editAlias ms-1 me-1 fs-16" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit"><i class="ri-pencil-line"></i></a>
                                            <a class="without_bg_icon openLink fs-16" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Open Link" onClick="generatePreview('{{ url('/previewpage?url='.(App\Helpers\MyLibrary::getFrontUri('blogs')['uri'])) }}');">
                                                <i class="ri-link-m" aria-hidden="true"></i>
                                            </a>
                                            @endif
                                        @endif
                                    </div>
                                    <span class="help-block">{{ $errors->first('alias') }}</span>
                                    <!-- code for alias -->
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12">
                            <div class="@if($errors->first('short_description')) has-error @endif">
                                <div class="form-md-line-input cm-floating"> 
                                    @php if(isset($blogs_highLight->varShortDescription) && ($blogs_highLight->varShortDescription != $blogs->varShortDescription)){
                                    $Class_ShortDescription = " highlitetext";
                                    }else{
                                    $Class_ShortDescription = "";
                                    } @endphp
                                    <label class="form-label {!! $Class_ShortDescription !!}">Short Description<span aria-required="true" class="required"> * </span></label>
                                    {!! Form::textarea('short_description', isset($blogs->varShortDescription)?$blogs->varShortDescription:old('short_description'), array('maxlength' => isset($settings->short_desc_length)?$settings->short_desc_length:500,'class' => 'form-control h148 seoField maxlength-handler shortdescspellingcheck','id'=>'varShortDescription','rows'=>'3')) !!}
                                    <span class="help-block">{{ $errors->first('short_description') }}</span>
                                </div>
                            </div>
                            @php $defaultDt = (null !== old('start_date_time'))?old('start_date_time'):date(Config::get('Constant.DEFAULT_DATE_FORMAT').' '.Config::get('Constant.DEFAULT_TIME_FORMAT')); @endphp
                            <div class="row">
                                <div class="col-lg-6 col-md-12">
                                    <div class="form-md-line-input cm-floating">
                                        @php if(isset($blogs_highLight->dtDateTime) && ($blogs_highLight->dtDateTime != $blogs->dtDateTime)){
                                        $Class_date = " highlitetext";
                                        }else{
                                        $Class_date = "";
                                        } @endphp
                                        <label class="control-label form-label text-capitalize {!! $Class_date !!}">{{ trans('blogs::template.common.startDateAndTime') }}<span aria-required="true" class="required"> * </span></label>
                                        <div class="input-group date form_meridian_datetime @if($errors->first('start_date_time')) has-error @endif" data-date="{{ Carbon\Carbon::today()->format('Y-m-d') }}T15:25:00Z">
                                            <!-- <span class="input-group-text"><i class="ri-calendar-fill"></i></span> -->
                                            {!! Form::text('start_date_time', date(Config::get('Constant.DEFAULT_DATE_FORMAT').' '.Config::get('Constant.DEFAULT_TIME_FORMAT'),strtotime(isset($blogs->dtDateTime)?$blogs->dtDateTime:$defaultDt)), array('class' => 'form-control', 'data-provider' => 'flatpickr', 'data-date-format' => Config::get('Constant.DEFAULT_DATE_FORMAT'), 'data-enable-time' => '','maxlength'=>160,'size'=>'16','id'=>'start_date_time','autocomplete'=>'off','onkeypress'=>"javascript: return KeycheckOnlyDate(event);",'onpaste'=>'return false')) !!}
                                        </div>
                                        <span class="help-block">{{ $errors->first('start_date_time') }}</span>
                                    </div>
                                </div>

                                @php $defaultDt = (null !== old('end_date_time'))?old('end_date_time'):null; @endphp
                                @if ((isset($blogs->dtEndDateTime)==null))
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
                                <div class="col-lg-6 col-md-12">
                                    <div class="form-md-line-input">
                                        @php if(isset($blogs_highLight->dtEndDateTime) && ($blogs_highLight->dtEndDateTime != $blogs->dtEndDateTime)){
                                        $Class_end_date = " highlitetext";
                                        }else{
                                        $Class_end_date = "";
                                        } @endphp
                                        <div class="cm-floating form_meridian_datetime expirydate @if($errors->first('end_date_time')) has-error @endif" data-date="{{ Carbon\Carbon::today()->format('Y-m-d') }}T15:25:00Z" @if ($expChecked_yes==1) style="display:none;" @endif>
                                            <label class="control-label form-label {!! $Class_end_date !!}" >{{ trans('blogs::template.common.endDateAndTime') }} <span aria-required="true" class="required"> * </span></label>
                                            <div class="input-group date">
                                                <!-- <span class="input-group-text"><i class="ri-calendar-fill"></i></span> -->
                                                {!! Form::text('end_date_time', isset($blogs->dtEndDateTime)?date(Config::get('Constant.DEFAULT_DATE_FORMAT').' '.Config::get('Constant.DEFAULT_TIME_FORMAT'),strtotime($blogs->dtEndDateTime)):$defaultDt, array('class' => 'form-control', 'data-provider' => 'flatpickr', 'data-date-format' => Config::get('Constant.DEFAULT_DATE_FORMAT'), 'data-enable-time' => '','maxlength'=>160,'size'=>'16','id'=>'end_date_time','data-exp'=> $expChecked_yes,'data-newvalue','autocomplete'=>'off','onkeypress'=>"javascript: return KeycheckOnlyDate(event);",'onpaste'=>'return false')) !!}
                                            </div>
                                        </div>
                                        <span class="help-block">{{ $errors->first('end_date_time') }}</span>
                                        <label class="expdatelabel {{ $expclass }} form-label m-0">
                                            <a id="noexpiry" name="noexpiry" href="javascript:void(0);">
                                                <b class="expiry_lbl {!! $Class_end_date !!}">Set Expiry</b>
                                            </a>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12">
                            @if(isset($blogs_highLight->fkIntImgId) && ($blogs_highLight->fkIntImgId != $blogs->fkIntImgId))
                            @php $Class_fkIntImgId = " highlitetext"; @endphp
                            @else
                            @php $Class_fkIntImgId = ""; @endphp
                            @endif
                            <div class="image_thumb multi_upload_images mb-0">
                                <div class="cm-floating">
                                    <label class="form-label {{ $Class_fkIntImgId }}" for="front_logo">Featured Image <span aria-required="true" class="required"> * </span>
                                        <span class="img-note" data-bs-toggle="tooltip" data-bs-placement="bottom" title="@php $height = isset($settings->height)?$settings->height:292; $width = isset($settings->width)?$settings->width:372; @endphp {{ trans('blogs::template.common.imageSize',['height'=>$height, 'width'=>$width]) }}">
                                            <i class="ri-information-line text-primary fs-16"></i>
                                        </span>
                                    </label>
                                    <div class="clearfix"></div>
                                    <div class="fileinput fileinput-new page-media" data-provides="fileinput">
                                        <div class="fileinput-preview thumbnail blog_image_img" data-trigger="fileinput">
                                            @if(old('image_url'))
                                            <img src="{{ old('image_url') }}" />
                                            @elseif(isset($blogs->fkIntImgId))
                                            <img src="{!! App\Helpers\resize_image::resize($blogs->fkIntImgId,120,120) !!}" />
                                            @else
                                            {{-- <img class="img_opacity" src="{{ $CDN_PATH.'resources/images/upload_file.gif' }}" /> --}}
                                            <div class="dz-message needsclick w-100 text-center">
                                                <div class="dropzone_icon"><i class="display-5 text-muted ri-upload-cloud-2-fill"></i></div>
                                                <h5 class="sbold dropzone-title">Drop files here or click to upload</h5>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="input-group">
                                            <a class="media_manager" data-multiple="false" onclick="MediaManager.open('blog_image');"><span class="fileinput-new"></span></a>
                                            <input class="form-control" type="hidden" id="blog_image" name="img_id" value="{{ isset($blogs->fkIntImgId)?$blogs->fkIntImgId:old('img_id') }}" />
                                            @php
                                            if (method_exists($MyLibrary, 'GetFolderID')) {
                                                if(isset($blogs->fkIntImgId)){
                                                    $folderid = App\Helpers\MyLibrary::GetFolderID($blogs->fkIntImgId);
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
                                    <div class="clearfix"></div>
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
                        <div class="col-md-12">
                            <div class="@if($errors->first('description')) has-error @endif form-md-line-input">
                                @if (Config::get('Constant.DEFAULT_VISUAL') == 'Y')
                                    <div id="body-roll">
                                        @php
                                            $sections = [];
                                        @endphp
                                        @if(isset($blogs))
                                            @php
                                                $sections = json_decode($blogs->txtDescription);
                                            @endphp
                                        @endif
                                        <!-- Builder include -->
                                        @php
                                            Powerpanel\VisualComposer\Controllers\VisualComposerController::page_section(['sections'=>$sections])
                                        @endphp
                                    </div>
                                @else
                                    @php if(isset($blogs_highLight->txtDescription) && ($blogs_highLight->txtDescription != $blogs->txtDescription)){
                                    $Class_Description = " highlitetext";
                                    }else{
                                    $Class_Description = "";
                                    } @endphp
                                    <h4 class="form-section mb-3 form-label {!! $Class_Description !!}">{{ trans('blogs::template.common.description') }}</h4>
                                    {!! Form::textarea('description', isset($blogs->txtDescription)?$blogs->txtDescription:old('description'), array('class' => 'form-control','id'=>'txtDescription')) !!}
                                @endif
                                <span class="help-block">{{ $errors->first('description') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body p-30">
                    @if(isset($blogs->intSearchRank))
                        @php $srank = $blogs->intSearchRank; @endphp
                    @else
                        @php
                            $srank = null !== old('search_rank') ? old('search_rank') : 2 ;
                        @endphp
                    @endif
                    <div class="row">
                        <div class="col-md-12">
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
                                    <strong>Note: </strong> {{ trans('blogs::template.common.SearchEntityTools') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body p-30">
                    <div class="row">
                        <div class="col-md-12">
                            @if(isset($blogs_highLight->intSearchRank) && ($blogs_highLight->intSearchRank != $blogs->intSearchRank))
                                @php $Class_intSearchRank = " highlitetext"; @endphp
                            @else
                                @php $Class_intSearchRank = ""; @endphp
                            @endif
                            @include('powerpanel.partials.seoInfo',['form'=>'frmBlogs','inf'=>isset($metaInfo)?$metaInfo:false,'inf_highLight'=> isset($metaInfo_highLight)?$metaInfo_highLight:false, 'Class_intSearchRank' => $Class_intSearchRank, 'srank' => $srank])
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-body p-30">
                    <h4 class="form-section mb-3">{{ trans('blogs::template.common.displayinformation') }}</h4>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            @if(isset($blogs_highLight->chrPublish) && ($blogs_highLight->chrPublish != $blogs->chrPublish))
                            @php $Class_chrPublish = " highlitetext"; @endphp
                            @else
                            @php $Class_chrPublish = ""; @endphp
                            @endif
                            @include('powerpanel.partials.displayInfo',['Class_chrPublish'=>$Class_chrPublish,'display' => isset($blogs->chrPublish)?$blogs->chrPublish:null])
                        </div>
                    </div>
                    <div class="form-actions btn-bottom pt-1">
                        <div class="row">
                            <div class="col-md-12">
                                @if(isset($blogs->fkMainRecord) && $blogs->fkMainRecord != 0)
                                    <button type="submit" name="saveandexit" formmethod="post" class="btn btn-primary bg-gradient waves-effect waves-light btn-label me-1" value="saveandexit">
                                        <div class="flex-shrink-0">
                                            <i class="ri-save-line label-icon align-middle fs-20 me-2"></i>
                                        </div>
                                        {!! trans('blogs::template.common.approve') !!}
                                    </button>
                                @else
                                    @if($userIsAdmin)
                                        <button type="submit" name="saveandedit" formmethod="post" class="btn btn-primary bg-gradient waves-effect waves-light btn-label me-1" value="saveandedit">
                                            <div class="flex-shrink-0">
                                                <i class="ri-save-line label-icon align-middle fs-20 me-2"></i>
                                            </div>
                                            {!! trans('blogs::template.common.saveandedit') !!}
                                        </button>

                                        <button type="submit" name="saveandexit" formmethod="post" class="btn btn-secondary bg-gradient waves-effect waves-light btn-label me-1" value="saveandexit">
                                            <div class="flex-shrink-0">
                                                <i class="ri-save-3-line label-icon align-middle fs-20 me-2"></i>
                                            </div>
                                            {!! trans('blogs::template.common.saveandexit') !!}
                                        </button>
                                    @else
                                        @if((isset($chrNeedAddPermission) && $chrNeedAddPermission == 'N') && (isset($charNeedApproval) && $charNeedApproval == 'N'))
                                            <button type="submit" name="saveandexit" formmethod="post" class="btn btn-secondary bg-gradient waves-effect waves-light btn-label me-1" value="saveandexit">
                                                <div class="flex-shrink-0">
                                                    <i class="ri-save-3-line label-icon align-middle fs-20 me-2"></i>
                                                </div>
                                                {!! trans('blogs::template.common.saveandexit') !!}
                                            </button>
                                        @else
                                            <button type="submit" name="saveandexit" formmethod="post" class="btn btn-secondary bg-gradient waves-effect waves-light btn-label me-1" value="approvesaveandexit"> 
                                                <div class="flex-shrink-0">
                                                    <i class="ri-save-line label-icon align-middle fs-20 me-2"></i>
                                                </div>
                                                {!! trans('blogs::template.common.approvesaveandexit') !!}
                                            </button>
                                        @endif
                                    @endif
                                @endif

                                <a class="btn btn-danger bg-gradient waves-effect waves-light btn-label" href="{{ url('powerpanel/blogs') }}">
                                    <div class="flex-shrink-0">
                                        <i class="ri-close-line label-icon align-middle fs-20 me-2"></i>
                                    </div>
                                    {{ trans('blogs::template.common.cancel') }}
                                </a>

                                {{-- @if(isset($blogs) && !empty($blogs) && $userIsAdmin)
                                &nbsp;<a class="btn btn-primary" title="Preview" onClick="generatePreview('{{url('/previewpage?url='.(App\Helpers\MyLibrary::getFrontUri('blogs')['uri']))}}');">Preview</a>
                                @endif --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>

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
            var seoFormId = 'frmBlogs';
            var user_action = "{{ isset($blogs)?'edit':'add' }}";
            var moduleAlias = "{{ App\Helpers\MyLibrary::getFrontUri('blogs')['moduleAlias'] }}";
            var preview_add_route = '{!! route("powerpanel.blogs.addpreview") !!}';
            var previewForm = $('#frmBlogs');
            var isDetailPage = true;
            function generate_seocontent1(formname) {
            var Meta_Title = document.getElementById('title').value + "";
                    var abcd = $('textarea#txtDescription').val();
                    var def = abcd.replace(/<a(\s[^>]*)?>.*?<\/a>/ig, "")
                    var abc = def.replace(/^(\s*)|(\s*)$/g, '').replace(/\s+/g, ' ');
                    var outString1 = abc.replace(/(<([^>]+)>)/ig, "");
                    var Meta_Description = outString1.substr(0, 200);
                    var Meta_Keyword = "";
                    $('#varMetaTitle').val(Meta_Title);
//                                    $('#varMetaKeyword').val(Meta_Keyword);
                    $('#varMetaDescription').val(Meta_Description);
                    $('#meta_title').html(Meta_Title);
                    $('#meta_description').html(Meta_Description);
            }

    function OpenPassword(val) {
    if (val == 'PP') {
    $("#passid").show();
    } else {
    $("#passid").hide();
    }
    }
</script>
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-taginput/bootstrap-tagsinput.min.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/blogs/blogs_validations.js?v='.time() }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/custom.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/custom-alias/alias-generator.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/seo-generator/seo-info-generator.js' }}" type="text/javascript"></script>
@if (Config::get('Constant.DEFAULT_VISUAL') == 'Y')
    @php Powerpanel\VisualComposer\Controllers\VisualComposerController::get_builder_css_js()@endphp
@endif
@endsection