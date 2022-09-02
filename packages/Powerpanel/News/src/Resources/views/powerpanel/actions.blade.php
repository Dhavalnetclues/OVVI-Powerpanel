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
    <div class="col-sm-12">
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
            {!! Form::open(['method' => 'post','id'=>'frmNews']) !!}
                <div class="card">
                    <div class="card-body p-30 pb-0">
                        @if(isset($news))
                        <div class="row pagetitle-heading mb-4">
                            <div class="col-sm-11 col-11">
                                <h4 class="page-title fw-semibold m-0">{{ $breadcrumb['inner_title']}}</h4>
                            </div>
                            <div class="col-sm-1 col-1 lock-link">
                                @if (File::exists(base_path() . '/resources/views/powerpanel/partials/lockedpage.blade.php') != null)
                                @include('powerpanel.partials.lockedpage',['pagedata'=>$news])
                                @endif
                            </div>
                        </div>
                        @endif
                        <div class="row">
                            <div class="col-lg-6 col-sm-12">
                                <div class="@if($errors->first('sector')) has-error @endif form-md-line-input cm-floating">
                                    @if(isset($news_highLight->varSector) && ($news_highLight->varSector != $news->varSector))
                                        @php $Class_varSector = " highlitetext"; @endphp
                                    @else
                                        @php $Class_varSector = ""; @endphp
                                    @endif
                                    @include('shiledcmstheme::powerpanel.partials.sector-dropdown', ['selected_sector' => isset($news->varSector)?$news->varSector:'','Class_varSector' => $Class_varSector])
                                    <span class="help-block">{{ $errors->first('sector') }}</span>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <div class="@if($errors->first('category_id')) has-error @endif form-md-line-input cm-floating">
                                    @if(isset($news_highLight->txtCategories) && ($news_highLight->txtCategories != $news->txtCategories))
                                    @php $Class_txtCategories = " highlitetext"; @endphp
                                    @else
                                    @php $Class_txtCategories = ""; @endphp
                                    @endif
                                    <label class="form-label {{ $Class_txtCategories }}" for="site_name">Select Category<span aria-required="true" class="required"> * </span></label>
                                    @php echo $categories; @endphp
                                    <span class="help-block">
                                        {{ $errors->first('category_id') }}
                                    </span>
                                </div>                                
                            </div>
                            <div class="col-lg-12 col-sm-12">
                                <div class="@if($errors->first('title')) has-error @endif form-md-line-input cm-floating">
                                    @php if(isset($news_highLight->varTitle) && ($news_highLight->varTitle != $news->varTitle)){
                                    $Class_title = " highlitetext";
                                    }else{
                                    $Class_title = "";
                                    } @endphp
                                    <label class="form-label {!! $Class_title !!}" for="site_name">{{ trans('news::template.common.title') }} <span aria-required="true" class="required"> * </span></label>
                                    {!! Form::text('title', isset($news->varTitle)?$news->varTitle:old('title'), array('maxlength' => 200,'id'=>'title', 'class' => 'form-control hasAlias seoField maxlength-handler titlespellingcheck','autocomplete'=>'off','data-url' => 'powerpanel/news')) !!}
                                    <span class="help-block">
                                        {{ $errors->first('title') }}
                                    </span>
                                    <div class="link-url mt-2">
                                        <!-- code for alias -->
                                        {!! Form::hidden(null, null, array('class' => 'hasAlias','data-url' => 'powerpanel/news')) !!}
                                        {!! Form::hidden('alias', isset($news->alias->varAlias)?$news->alias->varAlias:old('alias'), array('class' => 'aliasField')) !!}
                                        {!! Form::hidden('oldAlias', isset($news->alias->varAlias)?$news->alias->varAlias:old('alias')) !!}
                                        {!! Form::hidden('fkMainRecord', isset($news->fkMainRecord)?$news->fkMainRecord:old('fkMainRecord')) !!}
                                        {!! Form::hidden('previewId') !!}
                                        <div class=" alias-group {{!isset($news)?'d-none':''}} ">
                                            <label class="form-label" for="{{ trans('template.url') }}">{{ trans('news::template.common.url') }} :</label>
                                            @if(isset($news->alias->varAlias) && !$userIsAdmin)
                                            @if(isset($news->alias->varAlias))
                                            <a class="alias">
                                            {!! url("/") !!}
                                            </a>
                                            @endif
                                            @else
                                            @if(auth()->user()->can('news-create'))
                                            <a href="javascript:void;" class="alias">{!! url("/") !!}</a>
                                            <a href="javascript:void(0);" class="editAlias" title="{{ trans('news::template.common.edit') }}">
                                                <i class="ri-pencil-line"></i>
                                            </a>
                                            <a class="without_bg_icon openLink" title="Open Link" onClick="generatePreview('{{ url('/previewpage?url='.(App\Helpers\MyLibrary::getFrontUri('news')['uri'])) }}');">
                                                <i class="ri-external-link-line" aria-hidden="true"></i>
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
                                <div class="cm-floating @if($errors->first('short_description')) has-error @endif form-md-line-input">
                                    @php if(isset($news_highLight->varShortDescription) && ($news_highLight->varShortDescription != $news->varShortDescription)){
                                    $Class_ShortDescription = " highlitetext";
                                    }else{
                                    $Class_ShortDescription = "";
                                    } @endphp
                                    <label class="form-label {!! $Class_ShortDescription !!}">Short Description<span aria-required="true" class="required"> * </span></label>
                                    {!! Form::textarea('short_description', isset($news->varShortDescription)?$news->varShortDescription:old('short_description'), array('maxlength' => isset($settings->short_desc_length)?$settings->short_desc_length:400,'class' => 'form-control h148 seoField maxlength-handler shortdescspellingcheck','id'=>'varShortDescription','rows'=>'3')) !!}
                                    <span class="help-block">{{ $errors->first('short_description') }}</span>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-sm-12">
                                        <div class="cm-floating form-md-line-input">
                                            @php if(isset($news_highLight->dtDateTime) && ($news_highLight->dtDateTime != $news->dtDateTime)){
                                            $Class_date = " highlitetext";
                                            }else{
                                            $Class_date = "";
                                            } @endphp
                                            <label class="control-label form-label {!! $Class_date !!}">{{ trans('news::template.common.startDateAndTime') }}<span aria-required="true" class="required"> * </span></label>
                                            <div class="input-group date form_meridian_datetime @if($errors->first('start_date_time')) has-error @endif" data-date="{{ Carbon\Carbon::today()->format('Y-m-d') }}T15:25:00Z">
                                                {!! Form::text('start_date_time', date(Config::get('Constant.DEFAULT_DATE_FORMAT').' '.Config::get('Constant.DEFAULT_TIME_FORMAT'),strtotime(isset($news->dtDateTime)?$news->dtDateTime:Carbon\Carbon::today()->format('Y-m-d'))), array('class' => 'form-control', 'data-provider' => 'flatpickr', 'data-date-format' => Config::get('Constant.DEFAULT_DATE_FORMAT'), 'data-enable-time' => '','maxlength'=>160,'size'=>'16','id'=>'news_start_date','onkeypress'=>"javascript: return KeycheckOnlyDate(event);",'autocomplete'=>'off','onpaste'=>'return false')) !!}
                                            </div>
                                            <span class="help-block">{{ $errors->first('start_date_time') }}</span>
                                        </div>
                                    </div>
                                    @php $defaultDt = (null !== old('end_date_time'))?old('end_date_time'):null; @endphp
                                    @if ((isset($news->dtEndDateTime)==null))
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
                                    <div class="col-lg-6 col-sm-12">
                                        <div class="form-md-line-input">
                                            @php if(isset($news_highLight->dtEndDateTime) && ($news_highLight->dtEndDateTime != $news->dtEndDateTime)){
                                            $Class_end_date = " highlitetext";
                                            }else{
                                            $Class_end_date = "";
                                            } @endphp
                                            <div class="cm-floating form_meridian_datetime expirydate @if($errors->first('end_date_time')) has-error @endif" data-date="{{ Carbon\Carbon::today()->format('Y-m-d') }}T15:25:00Z" @if ($expChecked_yes==1) style="display:none;" @endif>
                                                <label class="control-label form-label {!! $Class_end_date !!}" >{{ trans('news::template.common.endDateAndTime') }} <span aria-required="true" class="required"> * </span></label>
                                                
                                                <div class="input-group date">
                                                    {!! Form::text('end_date_time', isset($news->dtEndDateTime)?date(Config::get('Constant.DEFAULT_DATE_FORMAT').' '.Config::get('Constant.DEFAULT_TIME_FORMAT'),strtotime($news->dtEndDateTime)):$defaultDt, array('class' => 'form-control', 'data-provider' => 'flatpickr', 'data-date-format' => Config::get('Constant.DEFAULT_DATE_FORMAT'), 'data-enable-time' => '','maxlength'=>160,'size'=>'16','id'=>'news_end_date','data-exp'=> $expChecked_yes,'data-newvalue','autocomplete'=>'off','onkeypress'=>"javascript: return KeycheckOnlyDate(event);",'onpaste'=>'return false')) !!}
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
                                @if(isset($news_highLight->fkIntImgId) && ($news_highLight->fkIntImgId != $news->fkIntImgId))
                                @php $Class_fkIntImgId = " highlitetext"; @endphp
                                @else
                                @php $Class_fkIntImgId = ""; @endphp
                                @endif
                                <div class="image_thumb multi_upload_images">
                                    <div class="cm-floating">
                                        <label class="form-label {{ $Class_fkIntImgId }}" for="front_logo">
                                            {{ trans('blogs::template.common.selectimage') }} 
                                            <span class="img-note" data-bs-toggle="tooltip" data-bs-placement="bottom" title="@php $height = isset($settings->height)?$settings->height:292; $width = isset($settings->width)?$settings->width:372; @endphp {{ trans('news::template.common.imageSize',['height'=>$height, 'width'=>$width]) }}">
                                                <i class="ri-information-line text-primary fs-16"></i>
                                            </span>
                                        </label>
                                        <div class="clearfix"></div>
                                        <div class="fileinput fileinput-new page-media" data-provides="fileinput">
                                            <div class="fileinput-preview thumbnail blog_image_img" data-trigger="fileinput">
                                                @if(old('image_url'))
                                                <img src="{{ old('image_url') }}" />
                                                @elseif(isset($news->fkIntImgId))
                                                <img src="{!! App\Helpers\resize_image::resize($news->fkIntImgId,120,120) !!}" />
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
                                                <input class="form-control" type="hidden" id="blog_image" name="img_id" value="{{ isset($news->fkIntImgId)?$news->fkIntImgId:old('img_id') }}" />
                                                    @php
                                                    if (method_exists($MyLibrary, 'GetFolderID')) {
                                                        if(isset($news->fkIntImgId)){
                                                        $folderid = App\Helpers\MyLibrary::GetFolderID($news->fkIntImgId);
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
                                        {{-- @php $height = isset($settings->height)?$settings->height:292; $width = isset($settings->width)?$settings->width:372; @endphp <span>{{ trans('news::template.common.imageSize',['height'=>$height, 'width'=>$width]) }}</span> --}}
                                        <span class="help-block">
                                            {{ $errors->first('img_id') }}
                                        </span>
                                    </div>
                                </div>
                            </div>   
                        </div>

                        <div class="row">
                            <div class="col-lg-6 col-sm-12">
                                <div class="image_thumb multi_upload_images mb-0">
                                    @php if(isset($news_highLight->fkIntDocId) && ($news_highLight->fkIntDocId != $news->fkIntDocId)){
                                    $Class_file = " highlitetext";
                                    }else{
                                    $Class_file = "";
                                    } @endphp
                                    <div class="cm-floating">
                                        <label class="form-label {!! $Class_file !!}">Select Documents
                                            <span class="img-note" data-bs-toggle="tooltip" data-bs-placement="bottom" title="(Recommended documents *.txt, *.pdf, *.doc, *.docx, *.ppt, *.xls, *.xlsx, *.xlsm formats are supported. Document should be maximum size of 45 MB.)">
                                                <i class="ri-information-line text-primary fs-16"></i>
                                            </span>
                                        </label>
                                        <div class="clearfix"></div>
                                        <div class="fileinput fileinput-new page-media" data-provides="fileinput">
                                            <div class="fileinput-preview thumbnail" data-trigger="fileinput">
                                                <div class="dz-message needsclick w-100 text-center">
                                                    <div class="dropzone_icon"><i class="display-5 text-muted ri-upload-cloud-2-fill"></i></div>
                                                    <h5 class="sbold dropzone-title">Drop files here or click to upload</h5>
                                                </div>
                                            </div>
                                            <div class="input-group">
                                                <a class="document_manager multiple-selection" data-multiple="true" onclick="MediaManager.openDocumentManager('news_doc_id');"><span class="fileinput-new"></span></a>
                                                <input class="form-control" type="hidden" id="news_doc_id" name="doc_id" value="{{ isset($news->fkIntDocId)?$news->fkIntDocId:old('doc_id') }}" />
                                                @php
                                                if (method_exists($MyLibrary, 'GetDocumentFolderID')) {
                                                if(isset($news->fkIntDocId)){
                                                $folderid = App\Helpers\MyLibrary::GetDocumentFolderID($news->fkIntDocId);
                                                @endphp
                                                @if(isset($folderid->fk_folder) && $folderid->fk_folder != '0')
                                                <input class="form-control" type="hidden" id="folder_id" name="folder_id" value="{{ $folderid->fk_folder }}" />
                                                @endif
                                                @php
                                                }
                                                }
                                                @endphp
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                @if(!empty($news->fkIntDocId) && isset($news->fkIntDocId))
                                @php
                                $docsAray = explode(',', $news->fkIntDocId);
                                $docObj   = App\Document::getDocDataByIds($docsAray);
                                @endphp
                                <div class="col-md-12" id="news_doc_id_documents">
                                    <div class="multi_image_list" id="multi_document_list">
                                        <ul>
                                            @if(count($docObj) > 0)
                                            @foreach($docObj as $value)
                                            <li id="doc_{{ $value->id }}">
                                                <span class="documents-item" title="{{ $value->txtDocumentName }}.{{ $value->varDocumentExtension }}">
                                                    <i class="ri-file-text-line text-muted display-5 default-icon"></i>
                                                    <a href="javascript:;" onclick="MediaManager.removeDocumentFromGallery('{{ $value->id }}');" class="delect_image" data-dismiss="fileinput"><i class="ri-delete-bin-line"></i></a>
                                                </span>
                                            </li>
                                            @endforeach
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                                @else
                                <div class="col-md-12" id="news_doc_id_documents"></div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body p-30">
                        <div class="row">
                            <div class="col-md-12">
                                @php 
                                if(isset($news_highLight->txtDescription) && ($news_highLight->txtDescription != $news->txtDescription)){
                                    $Class_Description = " highlitetext";
                                }else{
                                    $Class_Description = "";
                                } 
                                @endphp
                                {{-- <h4 class="form-section mb-3 form-label {!! $Class_Description !!}"> Description</h4> --}}
                                @if (Config::get('Constant.DEFAULT_VISUAL') == 'Y')
                                    <div id="body-roll">
                                        @php
                                        $sections = [];
                                        @endphp
                                        @if(isset($news))
                                        @php
                                        $sections = json_decode($news->txtDescription);
                                        @endphp
                                        @endif
                                        <!-- Builder include -->
                                        @php Powerpanel\VisualComposer\Controllers\VisualComposerController::page_section(['sections'=>$sections])@endphp
                                    </div>
                                @else
                                    <div class="@if($errors->first('description')) has-error @endif">
                                        <h4 class="form-section form-label cm-floating {!! $Class_Description !!}"> Description</h4>
                                        {!! Form::textarea('description', isset($news->txtDescription)?$news->txtDescription:old('description'), array('class' => 'form-control','id'=>'txtDescription')) !!}
                                        <span class="help-block">{{ $errors->first('description') }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Search Ranking --}}
                <div class="card">
                    <div class="card-body p-30">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="nopadding">
                                    @if(isset($news->intSearchRank))
                                        @php $srank = $news->intSearchRank; @endphp
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
                                            <strong>Note: </strong> {{ trans('news::template.common.SearchEntityTools') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body p-30">
                        @if(isset($news_highLight->varTags) && ($news_highLight->varTags != $news->varTags))
                            @php $Class_varTags = " highlitetext"; @endphp
                        @else
                            @php $Class_varTags = ""; @endphp
                        @endif
                        <div class="row">
                            <div class="col-md-12">
                                <div class="nopadding">
                                    @include('powerpanel.partials.seoInfo',['form'=>'frmNews','inf'=>isset($metaInfo)?$metaInfo:false,'inf_highLight'=> isset($metaInfo_highLight)?$metaInfo_highLight:false])
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body p-30">
                        <h4 class="form-section mb-3">{{ trans('news::template.common.displayinformation') }}</h4>
                        <div class="row">
                            <div class="col-md-12">
                                @if(isset($news_highLight->chrPublish) && ($news_highLight->chrPublish != $news->chrPublish))
                                    @php $Class_chrPublish = " highlitetext"; @endphp
                                @else
                                    @php $Class_chrPublish = ""; @endphp
                                @endif

                                <div class="form-md-line-input">
                                    @if(isset($news) && $news->chrAddStar == 'Y')
                                        <label class="control-label form-label"> Publish/ Unpublish</label>
                                        <input type="hidden" id="chrMenuDisplay" name="chrMenuDisplay" value="{{ isset($news->chrPublish) ? $news->chrPublish : '' }}">
                                        <p><b>NOTE:</b> This record is in Approval Request , so it can&#39;t be published/unpublished.</p>
                                    @elseif(isset($news) && $news->chrDraft == 'D' && $news->chrAddStar != 'Y')
                                        @include('powerpanel.partials.displayInfo',['Class_chrPublish'=>$Class_chrPublish,'display' => (isset($news->chrDraft)?$news->chrDraft:'D')])
                                    @else
                                        @include('powerpanel.partials.displayInfo',['Class_chrPublish'=>$Class_chrPublish,'display' => (isset($news->chrPublish)?$news->chrPublish:'Y')])
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-12">
                                    @if(isset($news->fkMainRecord) && $news->fkMainRecord != 0)
                                    <button type="submit" name="saveandexit" formmethod="post" class="btn btn-primary bg-gradient waves-effect waves-light btn-label me-1" value="saveandexit">
                                        <div class="flex-shrink-0">
                                            <i class="ri-check-line label-icon align-middle fs-20 me-2"></i>
                                        </div>
                                        {!! trans('news::template.common.approve') !!}
                                    </button>
                                    @else
                                    @if($userIsAdmin)
                                    <button type="submit" name="saveandedit" formmethod="post" class="btn btn-primary bg-gradient waves-effect waves-light btn-label me-1" value="saveandedit">
                                        <div class="flex-shrink-0">
                                            <i class="ri-save-line label-icon align-middle fs-20 me-2"></i>
                                        </div>
                                        {!! trans('news::template.common.saveandedit') !!}
                                    </button>
                                    <button type="submit" name="saveandexit" formmethod="post" class="btn btn-secondary bg-gradient waves-effect waves-light btn-label me-1" value="saveandexit">
                                        <div class="flex-shrink-0">
                                            <i class="ri-save-3-line label-icon align-middle fs-20 me-2"></i>
                                        </div>
                                        {!! trans('news::template.common.saveandexit') !!}
                                    </button>
                                    @else
                                    @if((isset($chrNeedAddPermission) && $chrNeedAddPermission == 'N') && (isset($charNeedApproval) && $charNeedApproval == 'N'))
                                    <button type="submit" name="saveandexit" formmethod="post" class="btn btn-secondary bg-gradient waves-effect waves-light btn-label me-1" value="saveandexit">
                                        <div class="flex-shrink-0">
                                            <i class="ri-save-3-line label-icon align-middle fs-20 me-2"></i>
                                        </div>
                                        {!! trans('news::template.common.saveandexit') !!}
                                    </button>
                                    @else
                                    <button type="submit" name="saveandexit" formmethod="post" class="btn btn-primary bg-gradient waves-effect waves-light btn-label me-1" value="approvesaveandexit">
                                        <div class="flex-shrink-0">
                                            <i class="ri-save-line label-icon align-middle fs-20 me-2"></i>
                                        </div>
                                        {!! trans('news::template.common.approvesaveandexit') !!}
                                    </button>
                                    @endif
                                    @endif
                                    @endif
                                    <a class="btn btn-danger bg-gradient waves-effect waves-light btn-label me-1" href="{{ url('powerpanel/news') }}">
                                        <div class="flex-shrink-0">
                                            <i class="ri-close-line label-icon align-middle fs-20 me-2"></i>
                                        </div>
                                        {{ trans('news::template.common.cancel') }}
                                    </a>
                                    @if(isset($news) && !empty($news) && $userIsAdmin)
                                        <a class="btn btn-info bg-gradient waves-effect waves-light btn-label me-1" title="Preview" onClick="generatePreview('{{url('/previewpage?url='.(App\Helpers\MyLibrary::getFrontUri('news')['uri']))}}');">
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
</div>

@if (Config::get('Constant.DEFAULT_VISUAL') == 'Y')
@php Powerpanel\VisualComposer\Controllers\VisualComposerController::get_dialog_maker()@endphp
@endif
@endsection
@section('scripts')
@if (Config::get('Constant.DEFAULT_VISUAL') == 'Y')
@php Powerpanel\VisualComposer\Controllers\VisualComposerController::get_visual_checkEditor()@endphp
@else
@include('powerpanel.partials.ckeditor',['config'=>'docsConfig'])
@endif

<script type="text/javascript">
                    function OpenPassword(val) {
                        if (val == 'PP') {
                            $("#passid").show();
                        } else {
                            $("#passid").d-none();
                        }
                    }
            window.site_url = '{!! url("/") !!}';
                    var seoFormId = 'frmNews';
                    var user_action = "{{ isset($news)?'edit':'add' }}";
                    var moduleAlias = "{{ App\Helpers\MyLibrary::getFrontUri('news')['moduleAlias'] }}";
                    var preview_add_route = '{!! route("powerpanel.news.addpreview") !!}';
                    var previewForm = $('#frmNews');
                    var isDetailPage = true;
                    function generate_seocontent1(formname) {
                    var Meta_Title = document.getElementById('title').value + "";
                            var abcd = document.getElementById('varShortDescription').value;
                            var def = abcd.replace(/<a(\s[^>]*)?>.*?<\/a>/ig, "")
                            var abc = def.replace(/^(\s*)|(\s*)$/g, '').replace(/\s+/g, ' ');
                            var outString1 = abc.replace(/(<([^>]+)>)/ig, "");
                            var Meta_Description = outString1.substr(0, 200);
                            var Meta_Keyword = "";
                            $('#varMetaTitle').val(Meta_Title);
                            //                            $('#varMetaKeyword').val(Meta_Keyword);
                            $('#varMetaDescription').val(Meta_Description);
                            $('#meta_title').html(Meta_Title);
                            $('#meta_description').html(Meta_Description);
                    }
</script>
<!-- <script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-taginput/bootstrap-tagsinput.min.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js' }}" type="text/javascript"></script> -->
<script src="{{ $CDN_PATH.'resources/pages/scripts/custom.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/custom-alias/alias-generator.js' }}" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/seo-generator/seo-info-generator.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/news/news_validations.js' }}" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS -->
 @if (Config::get('Constant.DEFAULT_VISUAL') == 'Y')
  @php Powerpanel\VisualComposer\Controllers\VisualComposerController::get_builder_css_js()@endphp
  @endif
@endsection