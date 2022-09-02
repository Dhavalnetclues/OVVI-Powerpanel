
@section('css')
<link href="{{ $CDN_PATH.'resources/global/plugins/bootstrap-taginput/bootstrap-tagsinput.css' }}" rel="stylesheet" type="text/css" />
@endsection
@extends('powerpanel.layouts.app')
@section('title')
{{Config::get('Constant.SITE_NAME')}} - PowerPanel
@endsection
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
            {!! Form::open(['method' => 'post','id'=>'frmTeam']) !!}
                <div class="card">
                    <div class="card-body p-30">
                        @if(isset($team))
                            <div class="row pagetitle-heading mb-3">
                                <div class="col-sm-11 col-11">
                                    <h4 class="page-title fw-semibold m-0">{{ $breadcrumb['inner_title']}}</h4>
                                </div>
                                <div class="col-sm-1 col-1 lock-link">
                                    @if (File::exists(base_path() . '/resources/views/powerpanel/partials/lockedpage.blade.php') != null)
                                    @include('powerpanel.partials.lockedpage',['pagedata'=>$team])
                                    @endif
                                </div>
                            </div>
                        @endif
                        {!! Form::hidden('fkMainRecord', isset($team->fkMainRecord)?$team->fkMainRecord:old('fkMainRecord')) !!}
                        <div class="row">
                            {{-- Sector type --}}
                            <div class="col-lg-4 col-sm-12">
                                <div class="@if($errors->first('sector')) has-error @endif form-md-line-input cm-floating">
                                    @if(isset($team_highLight->varSector) && ($team_highLight->varSector != $team->varSector))
                                        @php $Class_varSector = " highlitetext"; @endphp
                                    @else
                                        @php $Class_varSector = ""; @endphp
                                    @endif
                                    @include('shiledcmstheme::powerpanel.partials.sector-dropdown', ['selected_sector' => isset($team->varSector)?$team->varSector:'','Class_varSector' => $Class_varSector])
                                    <span class="help-block">
                                        {{ $errors->first('sector') }}
                                    </span>
                                </div>
                            </div>
                            {{-- Title --}}
                            <div class="col-lg-8 col-sm-12">
                                <div class="@if($errors->first('title')) has-error @endif form-md-line-input cm-floating">
                                    @php if(isset($team_highLight->varTitle) && ($team_highLight->varTitle != $team->varTitle)){
                                    $Class_title = " highlitetext";
                                    }else{
                                    $Class_title = "";
                                    } @endphp
                                    <label class="form-label {!! $Class_title !!}" for="site_name">{{ trans('fmbroadcasting::template.common.name') }} <span aria-required="true" class="required"> * </span></label>
                                    {!! Form::text('title', isset($team->varTitle) ? $team->varTitle:old('title'), array('maxlength'=>'150','id'=>'title','class' => 'form-control hasAlias seoField maxlength-handler titlespellingcheck','autocomplete'=>'off')) !!}
                                    <span class="help-block">{{ $errors->first('title') }}</span>
                                    <div class="link-url mt-2">
                                        <!-- code for alias -->
                                        {!! Form::hidden(null, null, array('class' => 'hasAlias','data-url' => 'powerpanel/fmbroadcasting')) !!}
                                        {!! Form::hidden('alias', isset($team->alias->varAlias) ? $team->alias->varAlias : old('alias'), array('class' => 'aliasField')) !!}
                                        {!! Form::hidden('oldAlias', isset($team->alias->varAlias)?$team->alias->varAlias : old('alias')) !!}
                                        {!! Form::hidden('previewId') !!}
                                        <div class="alias-group {{!isset($team->alias)?'hide':''}}">
                                            <label class="form-label" for="Url">{{ trans('fmbroadcasting::template.common.url') }} :</label>
                                            @if(isset($team->alias->varAlias) && !$userIsAdmin)
                                            @php
                                            $aurl = App\Helpers\MyLibrary::getFrontUri('team')['uri'];
                                            @endphp
                                            <a  class="alias">{!! url("/") !!}</a>
                                            @else
                                            @if(auth()->user()->can('team-create'))
                                            <a href="javascript:void(0);" class="alias">{!! url("/") !!}</a>
                                            <a href="javascript:void(0);" class="editAlias" title="Edit">
                                                <i class="ri-pencil-line"></i>
                                            </a>
                                            <a class="without_bg_icon openLink" title="Open Link" onClick="generatePreview('{{url('/previewpage?url='.(App\Helpers\MyLibrary::getFrontUri('team')['uri']))}}');">
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
                            {{-- Department --}}
                            <div class="col-lg-6 col-sm-12">
                                <div class="@if($errors->first('department')) has-error @endif form-md-line-input cm-floating">
                                    @php if(isset($team_highLight->varDepartment) && ($team_highLight->varDepartment != $team->varDepartment)){
                                        $Class_Department = " highlitetext";
                                        }else{
                                        $Class_Department = "";
                                    } @endphp
                                    <label class="form-label {{ $Class_Department }}" for="site_name">{{ trans('team::template.teamModule.department') }}</label>
                                    {!! Form::text('department', isset($team->varDepartment)?$team->varDepartment:old('department'), array('maxlength' => 100,'class' => 'form-control maxlength-handler','autocomplete'=>'off')) !!}
                                    <span class="help-block"> {{ $errors->first('department') }} </span>
                                </div>
                            </div>
                            {{-- Designation --}}
                            <div class="col-lg-6 col-sm-12">
                                <div class="@if($errors->first('tag_line')) has-error @endif form-md-line-input cm-floating">
                                    @php if(isset($team_highLight->varTagLine) && ($team_highLight->varTagLine != $team->varTagLine)){
                                        $Class_TagLine = " highlitetext";
                                        }else{
                                        $Class_TagLine = "";
                                    } @endphp
                                    <label class="form-label {{ $Class_TagLine }}" for="site_name">{{ trans('team::template.teamModule.designation') }}</label>
                                    {!! Form::text('tag_line', isset($team->varTagLine)?$team->varTagLine:old('tag_line'), array('maxlength'=>100, 'class' => 'form-control maxlength-handler','autocomplete'=>'off')) !!}
                                    <span class="help-block">{{ $errors->first('tag_line') }}</span>
                                </div>
                            </div>
                            {{-- Email --}}
                            <div class="col-lg-4 col-sm-12">
                                <div class="{{ $errors->has('email') ? 'has-error' : '' }} form-md-line-input cm-floating">
                                    @php if(isset($team_highLight->varEmail) && ($team_highLight->varEmail != $team->varEmail)){
                                    $Class_varEmail = " highlitetext";
                                    }else{
                                    $Class_varEmail = "";
                                    } @endphp
                                    <label class="form-label {{ $Class_varEmail }}" for="email">{{ trans('team::template.common.email') }}  </label>
                                    {!! Form::email('email',isset($team->varEmail)?$team->varEmail:old('email'), array('class' => 'form-control input-sm', 'maxlength'=>'300','id' => 'email','autocomplete'=>'off')) !!}
                                    <span class="help-block">
                                        {{ $errors->first('email') }}
                                    </span>
                                </div>
                            </div>
                            {{-- Phone No --}}
                            <div class="col-lg-4 col-sm-12">
                                <div class="{{ $errors->has('phone_no') ? 'has-error' : '' }} form-md-line-input cm-floating">
                                    @php if(isset($team_highLight->varPhoneNo) && ($team_highLight->varPhoneNo != $team->varPhoneNo)){
                                    $Class_varPhoneNo = " highlitetext";
                                    }else{
                                    $Class_varPhoneNo = "";
                                    } @endphp
                                    <label class="form-label {{ $Class_varPhoneNo }}" for="phone_no">{{ trans('team::template.common.phoneno') }}</label>
                                    {!! Form::tel('phone_no',isset($team->varPhoneNo)?$team->varPhoneNo:old('phone_no'), array('class' => 'form-control input-sm','id' => 'phone_no','minlength'=>'6','maxlength'=>'20','onpaste'=>'return false;', 'ondrop'=>'return false;','autocomplete'=>'off', 'onkeypress'=>'javascript: return KeycheckOnlyPhonenumber(event);')) !!}
                                    <span class="help-block">{{ $errors->first('phone_no') }}</span>
                                </div>
                            </div>
                            {{-- Fax --}}
                            <div class="col-lg-4 col-sm-12">
                                <div class="{{ $errors->has('fax') ? 'has-error' : '' }} form-md-line-input cm-floating">
                                    @php if(isset($team_highLight->varFax) && ($team_highLight->varFax != $team->varFax)){
                                    $Class_varFax = " highlitetext";
                                    }else{
                                    $Class_varFax = "";
                                    } @endphp
                                    <label class="form-label {{ $Class_varFax }}" for="fax">{{ trans('team::template.common.fax') }}</label>
                                    {!! Form::tel('fax',isset($team->varFax)?$team->varFax:old('fax'), array('class' => 'form-control input-sm','id' => 'fax','minlength'=>'6','maxlength'=>'20','onpaste'=>'return false;', 'ondrop'=>'return false;','autocomplete'=>'off', 'onkeypress'=>'javascript: return KeycheckOnlyPhonenumber(event);')) !!}
                                    <span class="help-block">{{ $errors->first('fax') }}</span>
                                </div>
                            </div>
                            {{-- Short Description --}}
                            <div class="col-md-12">
                                <div class="form-md-line-input cm-floating">
                                    @php if(isset($team_highLight->varShortDescription) && ($team_highLight->varShortDescription != $team->varShortDescription)){
                                    $Class_varShortDescription = " highlitetext";
                                    }else{
                                    $Class_varShortDescription = "";
                                    } @endphp
                                    <label class="form-label {{ $Class_varShortDescription }}" for="short_description">{{ trans('team::template.common.shortdescription') }}</label>
                                    {!! Form::textarea('short_description',isset($team->varShortDescription)?$team->varShortDescription:old('short_description'), array('class' => 'form-control maxlength-handler','maxlength'=>'800','id'=>'shortdescription','rows'=>'3','styel'=>'max-height:80px;')) !!}
                                </div>
                            </div>
                            {{-- Photo --}}
                            <div class="col-lg-6 col-sm-12">
                                @if(isset($team_highLight->fkIntImgId) && ($team_highLight->fkIntImgId != $team->fkIntImgId))
                                @php $Class_fkIntImgId = " highlitetext"; @endphp
                                @else
                                @php $Class_fkIntImgId = ""; @endphp
                                @endif
                                <div class="image_thumb multi_upload_images cm-floating">
                                    @php $height = isset($settings->height)?$settings->height:292; $width = isset($settings->width)?$settings->width:372; @endphp
                                    <label class="form-label {{ $Class_fkIntImgId }}" for="front_logo">
                                        {{ trans('team::template.common.selectimage') }}
                                        <span class="img-note" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ trans('team::template.common.imageSize',['height'=>$height, 'width'=>$width]) }}">
                                            <i class="ri-information-line text-primary fs-16"></i>
                                        </span>
                                    </label>
                                    <div class="fileinput fileinput-new page-media" data-provides="fileinput">
                                        <div class="fileinput-preview thumbnail blog_image_img" data-trigger="fileinput">
                                            @if(old('image_url'))
                                            <img src="{{ old('image_url') }}" />
                                            @elseif(isset($team->fkIntImgId))
                                            <img src="{!! App\Helpers\resize_image::resize($team->fkIntImgId,120,120) !!}" />
                                            @else
                                            <div class="dz-message needsclick w-100 text-center">
                                                <div class="dropzone_icon"><i class="display-5 text-muted ri-upload-cloud-2-fill"></i></div>
                                                <h5 class="sbold dropzone-title">Drop files here or click to upload</h5>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="input-group">
                                            <a class="media_manager" data-multiple="false" onclick="MediaManager.open('blog_image');"><span class="fileinput-new"></span></a>
                                            <input class="form-control" type="hidden" id="blog_image" name="img_id" value="{{ isset($team->fkIntImgId)?$team->fkIntImgId:old('img_id') }}" />
                                            @php
                                            if (method_exists($MyLibrary, 'GetFolderID')) {
                                            if(isset($team->fkIntImgId)){
                                            $folderid = App\Helpers\MyLibrary::GetFolderID($team->fkIntImgId);
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
                            {{-- Upload Bio PDF --}}
                            <div class="col-lg-6 col-sm-12">
                                @if(isset($team_highlight->fkIntDocId) && ($team_highlight->fkIntDocId != $team->fkIntDocId))
                                @php $Class_fkIntDocId = " highlitetext"; @endphp
                                @else
                                @php $Class_fkIntDocId = ""; @endphp
                                @endif
                                <div class="image_thumb multi_upload_images cm-floating">
                                    <label class="form-label {{ $Class_fkIntDocId }}">
                                        Upload Bio PDF
                                        <span class="img-note" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Recommended documents *.txt, *.pdf, *.doc, *.docx, *.ppt, *.xls, *.xlsx, *.xlsm formats are supported. Document should be maximum size of 45 MB.">
                                            <i class="ri-information-line text-primary fs-16"></i>
                                        </span>
                                    </label>
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-preview thumbnail" data-trigger="fileinput">
                                            <div class="dz-message needsclick w-100 text-center">
                                                <div class="dropzone_icon"><i class="display-5 text-muted ri-upload-cloud-2-fill"></i></div>
                                                <h5 class="sbold dropzone-title">Drop files here or click to upload</h5>
                                            </div>
                                        </div>
                                        <div class="input-group">
                                            <a class="document_manager multiple-selection" data-multiple="false" onclick="MediaManager.openDocumentManager('biopdf');"><span class="fileinput-new"></span></a>
                                            <input class="form-control" type="hidden" id="biopdf" name="doc_id" value="{{ isset($team->fkIntDocId)?$team->fkIntDocId:old('doc_id') }}" />
                                            @php
                                            if (method_exists($MyLibrary, 'GetDocumentFolderID')) {
                                            if(isset($team->fkIntDocId)){
                                                $folderid = App\Helpers\MyLibrary::GetDocumentFolderID($team->fkIntDocId);
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
                                    <span class="help-block">{{ $errors->first('doc_id') }}</span>
                                    <div class="mt-2">
                                        @if(!empty($team->fkIntDocId) && isset($team->fkIntDocId))
                                        @php
                                        $docsAray = explode(',', $team->fkIntDocId);
                                        $docObj   = App\Document::getDocDataByIds($docsAray);
                                        @endphp
                                        <div class="col-md-12" id="biopdf_documents">
                                            <div class="multi_image_list" id="multi_document_list">
                                                <ul>
                                                    @if(count($docObj) > 0)
                                                    @foreach($docObj as $value)
                                                    <li id="doc_{{ $value->id }}">
                                                        <span title="{{ $value->txtDocumentName }}.{{ $value->varDocumentExtension }}">
                                                            <img src="{{ $CDN_PATH.'assets/images/document_icon.png' }}" alt="Img" />
                                                            <a href="javascript:;" onclick="MediaManager.removeDocumentFromGallery('{{ $value->id }}');" class="delect_image" data-dismiss="fileinput"><i class="ri-delete-bin-line"></i></a>
                                                        </span>
                                                    </li>
                                                    @endforeach
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>
                                        @else
                                        <div class="col-md-12" id="biopdf_documents"></div>
                                        @endif
                                    </div>
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
                                @php if(isset($team_highLight->txtDescription) && ($team_highLight->txtDescription != $team->txtDescription)){
                                $Class_Description = " highlitetext";
                                }else{
                                $Class_Description = "";
                                } @endphp
                                {{-- <label class="form-label {!! $Class_Description !!}">Description</label> --}}
                                <div class="@if($errors->first('description')) has-error @endif form-md-line-input">
                                    @if (Config::get('Constant.DEFAULT_VISUAL') == 'Y')
                                    <div id="body-roll">
                                        @php
                                        $sections = [];
                                        @endphp
                                        @if(isset($team))
                                        @php
                                        $sections = json_decode($team->txtDescription);
                                        @endphp
                                        @endif
                                        <!-- Builder include -->
                                        @php
                                        Powerpanel\VisualComposer\Controllers\VisualComposerController::page_section(['sections'=>$sections])
                                        @endphp
                                    </div>
                                    @else
                                    {!! Form::textarea('description', isset($team->txtDescription)?$team->txtDescription:old('description'), array('class' => 'form-control','id'=>'txtDescription')) !!}
                                    @endif
                                    <span class="help-block">{{ $errors->first('description') }}</span>
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
                                @if(isset($team->intSearchRank))
                                    @php $srank = $team->intSearchRank; @endphp
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
                                        <strong>Note: </strong> {{ trans('team::template.common.SearchEntityTools') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SEO INFO --}}
                <div class="card">
                    <div class="card-body p-30">
                        <div class="row">
                            <div class="col-md-12">
                                @include('powerpanel.partials.seoInfo',['form'=>'frmTeam','inf'=>isset($metaInfo)?$metaInfo:false,'inf_highLight'=> isset($metaInfo_highLight)?$metaInfo_highLight:false])
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body p-30">
                        {{-- Display Information --}}
                        <div class="row">
                            <div class="col-md-12">
                                <h4 class="form-section mb-3">{{ trans('team::template.common.displayinformation') }}</h4>
                                <div class="@if($errors->first('order')) has-error @endif form-md-line-input cm-floating">
                                    @php
                                    $display_order_attributes = array('class' => 'form-control','maxlength'=>5,'autocomplete'=>'off');
                                    @endphp
                                    @if(isset($team_highLight->intDisplayOrder) && ($team_highLight->intDisplayOrder != $team->intDisplayOrder))
                                    @php $Class_intDisplayOrder = " highlitetext"; @endphp
                                    @else
                                    @php $Class_intDisplayOrder = ""; @endphp
                                    @endif
                                    <label class="form-label {{ $Class_intDisplayOrder }}" for="site_name">{{ trans('team::template.common.displayorder') }} <span aria-required="true" class="required"> * </span></label>
                                    {!! Form::text('order', isset($team->intDisplayOrder)?$team->intDisplayOrder:1, $display_order_attributes) !!}
                                    <span style="color: red;">{{ $errors->first('order') }}</span>
                                    <div class="publish-info mt-3">
                                        @if(isset($team_highLight->chrPublish) && ($team_highLight->chrPublish != $team->chrPublish))
                                            @php $Class_chrPublish = " highlitetext"; @endphp
                                        @else
                                            @php $Class_chrPublish = ""; @endphp
                                        @endif
                                        @if(isset($team) && $team->chrAddStar == 'Y')
                                            <label class="control-label form-label"> Publish/ Unpublish</label>
                                            <input type="hidden" id="chrMenuDisplay" name="chrMenuDisplay" value="{{ isset($team->chrPublish) ? $team->chrPublish : '' }}">
                                            <p><b>NOTE:</b> This record is in Approval Request , so it can&#39;t be published/unpublished.</p>
                                        @elseif(isset($team) && $team->chrDraft == 'D' && $team->chrAddStar != 'Y')
                                            @include('powerpanel.partials.displayInfo',['Class_chrPublish'=>$Class_chrPublish,'display' => (isset($team->chrDraft)?$team->chrDraft:'D')])
                                        @else
                                            @include('powerpanel.partials.displayInfo',['Class_chrPublish'=>$Class_chrPublish,'display' => (isset($team->chrPublish)?$team->chrPublish:'Y')])
                                        @endif
                                    </div>
                                </div>
                            </div>
                            {{-- Form Action --}}
                            <div class="col-md-12">
                                <div class="form-actions">
                                    @if(isset($team->fkMainRecord) && $team->fkMainRecord != 0)
                                    <button type="submit" name="saveandexit" formmethod="post" class="btn btn-primary bg-gradient waves-effect waves-light btn-label me-1" value="saveandexit">
                                        <div class="flex-shrink-0">
                                            <i class="ri-check-line label-icon align-middle fs-20 me-2"></i>
                                        </div>
                                        {!! trans('team::template.common.approve') !!}
                                    </button>
                                    @else
                                    @if($userIsAdmin)
                                    <button type="submit" name="saveandedit" formmethod="post" class="btn btn-primary bg-gradient waves-effect waves-light btn-label me-1" value="saveandedit">
                                        <div class="flex-shrink-0">
                                            <i class="ri-save-line label-icon align-middle fs-20 me-2"></i>
                                        </div>
                                        {!! trans('team::template.common.saveandedit') !!}
                                    </button>
                                    <button type="submit" name="saveandexit" formmethod="post" class="btn btn-secondary bg-gradient waves-effect waves-light btn-label me-1" value="saveandexit">
                                        <div class="flex-shrink-0">
                                            <i class="ri-save-3-line label-icon align-middle fs-20 me-2"></i>
                                        </div>
                                        {!! trans('team::template.common.saveandexit') !!}
                                    </button>
                                    @else
                                    @if((isset($chrNeedAddPermission) && $chrNeedAddPermission == 'N') && (isset($charNeedApproval) && $charNeedApproval == 'N'))
                                    <button type="submit" name="saveandexit" formmethod="post" class="btn btn-secondary bg-gradient waves-effect waves-light btn-label me-1" value="saveandexit">
                                        <div class="flex-shrink-0">
                                            <i class="ri-save-3-line label-icon align-middle fs-20 me-2"></i>
                                        </div>
                                        {!! trans('team::template.common.saveandexit') !!}
                                    </button>
                                    @else
                                    <button type="submit" name="saveandexit" formmethod="post" class="btn btn-primary bg-gradient waves-effect waves-light btn-label me-1" value="approvesaveandexit">
                                        <div class="flex-shrink-0">
                                            <i class="ri-save-line label-icon align-middle fs-20 me-2"></i>
                                        </div>
                                        {!! trans('team::template.common.approvesaveandexit') !!}
                                    </button>
                                    @endif
                                    @endif
                                    @endif
                                    <a class="btn btn-danger bg-gradient waves-effect waves-light btn-label me-1" href="{{ url('powerpanel/team') }}">
                                        <div class="flex-shrink-0">
                                            <i class="ri-close-line label-icon align-middle fs-20 me-2"></i>
                                        </div>
                                        {{ trans('team::template.common.cancel') }}
                                    </a>
                                    @if(isset($team) && !empty($team))
                                        <a class="btn btn-info bg-gradient waves-effect waves-light btn-label me-1" title="Preview" onClick="generatePreview('{{url('/previewpage?url='.(App\Helpers\MyLibrary::getFrontUri('team')['uri']))}}');">
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
    var seoFormId = 'frmTeam';
    var user_action = "{{ isset($team)?'edit':'add' }}";
    var moduleAlias = "{{ App\Helpers\MyLibrary::getFrontUri('team')['moduleAlias'] }}";
    var preview_add_route = '{!! route("powerpanel.team.addpreview") !!}';
    var previewForm = $('#frmTeam');
    var isDetailPage = true;
    function generate_seocontent1(formname) {
    var Meta_Title = document.getElementById('title').value + "";
    var abcd = $('textarea#txtDescription').val();
    var def = abcd.replace(/<a(\s[^>]*)?>.*?<\/a>/ig, "")
    var abc = def.replace(/^(\s*)|(\s*)$/g, '').replace(/\s+/g, ' ');
    var outString1 = abc.replace(/(<([^>]+)>)/ig, "");
    var Meta_Description = "" + document.getElementById('title').value;
    var Meta_Keyword = "";
    $('#varMetaTitle').val(Meta_Title);
    $('#varMetaDescription').val(Meta_Description);
    $('#meta_title').html(Meta_Title);
    $('#meta_description').html(Meta_Description);
    }
</script>
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-taginput/bootstrap-tagsinput.min.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/team/team_validations.js' }}" type="text/javascript"></script>

<script src="{{ $CDN_PATH.'resources/pages/scripts/custom.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/custom-alias/alias-generator.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/seo-generator/seo-info-generator.js' }}" type="text/javascript"></script>
<script src="{{ Config::get('Constant.CDN_PATH').'resources/pages/scripts/pages_password_rules.js' }}" type="text/javascript"></script>
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