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
            {!! Form::open(['method' => 'post','id'=>'frmService']) !!}
                <div class="card">
                    <div class="card-body p-30">
                        @if(isset($service))
                        <div class="row pagetitle-heading mb-3">
                            <div class="col-sm-11 col-11">
                                <h4 class="page-title fw-semibold m-0">{{ $breadcrumb['inner_title']}}</h4>
                            </div>
                            <div class="col-sm-1 col-1 lock-link">
                                @if (File::exists(base_path() . '/resources/views/powerpanel/partials/lockedpage.blade.php') != null)
                                @include('powerpanel.partials.lockedpage',['pagedata'=>$service])
                                @endif
                            </div>
                        </div>
                        @endif

                        {!! Form::hidden('fkMainRecord', isset($service->fkMainRecord)?$service->fkMainRecord:old('fkMainRecord')) !!}

                        <div class="row">
                            {{-- Sector type --}}
                            <div class="col-md-6">
                                <div class="@if($errors->first('sector')) has-error @endif form-md-line-input cm-floating">
                                    @if(isset($service_highLight->varSector) && ($service_highLight->varSector != $service->varSector))
                                        @php $Class_varSector = " highlitetext"; @endphp
                                    @else
                                        @php $Class_varSector = ""; @endphp
                                    @endif
                                    @include('shiledcmstheme::powerpanel.partials.sector-dropdown', ['selected_sector' => isset($service->varSector)?$service->varSector:'','Class_varSector' => $Class_varSector])
                                    <span class="help-block">{{ $errors->first('sector') }}</span>
                                </div>
                            </div>
                            {{-- Select Category --}}
                            <div class="col-md-6">
                                <div class="@if($errors->first('category_id')) has-error @endif form-md-line-input cm-floating">
                                    @php
                                    if(isset($service_highLight->intFKCategory) && ($service_highLight->intFKCategory != $service->intFKCategory)){
                                    $Class_title = " highlitetext";
                                    }else{
                                    $Class_title = "";
                                    }
                                    $currentCatAlias = '';
                                    @endphp
                                    <label class="form-label {{ $Class_title }}" for="site_name">Select Category <span aria-required="true" class="required"> * </span></label>
                                    <select class="form-control" name="category_id" id="category_id" data-choices>
                                        <option value="">Select Category</option>
                                    </select>
                                    <span class="help-block">{{ $errors->first('category') }}</span>
                                </div>
                            </div>
                            {{-- Service Name --}}
                            <div class="col-md-12">
                                <div class="@if($errors->first('title')) has-error @endif form-md-line-input cm-floating">
                                    @php if(isset($service_highLight->varTitle) && ($service_highLight->varTitle != $service->varTitle)){
                                    $Class_title = " highlitetext";
                                    }else{
                                    $Class_title = "";
                                    } @endphp
                                    <label class="form-label {!! $Class_title !!}" for="site_name">{{ trans('service::template.common.name') }} <span aria-required="true" class="required"> * </span></label>
                                    {!! Form::text('title', isset($service->varTitle)?$service->varTitle:old('title'), array('maxlength' => 200,'id'=>'title',  'class' => 'form-control hasAlias seoField maxlength-handler titlespellingcheck','autocomplete'=>'off','data-url' => 'powerpanel/service')) !!}
                                    <span class="help-block">{{ $errors->first('title') }}</span>
                                    <div class="link-url mt-2">
                                        <!-- code for alias -->
                                        {!! Form::hidden(null, null, array('class' => 'hasAlias','data-url' => 'powerpanel/service')) !!}
                                        {!! Form::hidden('alias', isset($service->alias->varAlias) ? $service->alias->varAlias : old('alias'), array('class' => 'aliasField')) !!}
                                        {!! Form::hidden('oldAlias', isset($service->alias->varAlias)?$service->alias->varAlias : old('alias')) !!}
                                        {!! Form::hidden('previewId') !!}
                                        <div class="alias-group {{!isset($service->alias)?'hide':''}}">
                                            <label class="form-label m-0" for="Url">{{ trans('service::template.common.url') }} :</label>
                                            @if(isset($service->alias->varAlias) && !$userIsAdmin)
                                                <a class="alias">{!! url("/") !!}</a>
                                            @else
                                                @if(auth()->user()->can('service-create'))
                                                <a href="javascript:void;" class="alias">{!! url("/") !!}</a>
                                                <a href="javascript:void(0);" class="editAlias ms-1 me-1 fs-16" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit"><i class="ri-pencil-line"></i></a>
                                                <a class="without_bg_icon openLink fs-16" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Open Link" onClick="generatePreview('{{ url('/previewpage?url='.(App\Helpers\MyLibrary::getFrontUri('service')['uri'])) }}');">
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
                            {{-- Service Code --}}
                            <div class="col-md-12">
                                <div class="form-group @if($errors->first('service_code')) has-error @endif form-md-line-input cm-floating">
                                    @php if(isset($service_highLight->serviceCode) && ($service_highLight->serviceCode != $service->serviceCode)){
                                    $Class_title = " highlitetext";
                                    }else{
                                    $Class_title = "";
                                    } @endphp
                                    <label class="form-label {!! $Class_title !!}" for="site_name">{{ trans('service::template.common.servicecode') }} <span aria-required="true" class="required"> * </span></label>
                                    {!! Form::text('service_code', isset($service->serviceCode)?$service->serviceCode:old('service_code'), array('maxlength' => 200,'id'=>'service_code', 'class' => 'form-control hasAlias seoField maxlength-handler titlespellingcheck','autocomplete'=>'off','data-url' => 'powerpanel/service')) !!}
                                    <span class="help-block">{{ $errors->first('service_code') }}</span>
                                </div>
                            </div>
                            {{-- Short Description --}}
                            <div class="@if($errors->first('short_description')) has-error @endif">
                                <div class="form-md-line-input cm-floating">
                                    @php if(isset($service_highLight->varShortDescription) && ($service_highLight->varShortDescription != $service->varShortDescription)){
                                    $Class_ShortDescription = " highlitetext";
                                    }else{
                                    $Class_ShortDescription = "";
                                    } @endphp
                                    <label class="form-label {!! $Class_ShortDescription !!}">Short Description</label>
                                    {!! Form::textarea('short_description', isset($service->varShortDescription)?$service->varShortDescription:old('short_description'), array('maxlength' => isset($settings->short_desc_length)?$settings->short_desc_length:500,'class' => 'form-control h148 seoField maxlength-handler shortdescspellingcheck','id'=>'varShortDescription','rows'=>'3')) !!}
                                    <span class="help-block">{{ $errors->first('short_description') }}</span>
                                </div>
                            </div>
                            {{-- Application Fee --}}
                            <div class="col-md-12" style="display: none">
                                <div class="form-group @if($errors->first('application_fee')) has-error @endif form-md-line-input cm-floating">
                                    @php if(isset($service_highLight->applicationFee) && ($service_highLight->applicationFee != $service->applicationFee)){
                                    $Class_title = " highlitetext";
                                    }else{
                                    $Class_title = "";
                                    } @endphp
                                    <label class="form-label {!! $Class_title !!}" for="site_name">{{ trans('service::template.common.applicationfee') }} </label>
                                    {!! Form::text('application_fee', isset($service->applicationFee)?$service->applicationFee:old('application_fee'), array('maxlength' => 200,'id'=>'application_fee', 'class' => 'form-control hasAlias seoField maxlength-handler titlespellingcheck','autocomplete'=>'off','data-url' => 'powerpanel/service')) !!}
                                    <span class="help-block">{{ $errors->first('application_fee') }}</span>
                                </div>
                            </div>
                            {{-- Note --}}
                            <div class="col-md-12" style="display: none">
                                <div class="form-group @if($errors->first('note')) has-error @endif form-md-line-input cm-floating">
                                    @php if(isset($service_highLight->noteTitle) && ($service_highLight->noteTitle != $service->noteTitle)){
                                    $Class_title = " highlitetext";
                                    }else{
                                    $Class_title = "";
                                    } @endphp
                                    <label class="form-label {!! $Class_title !!}" for="site_name">{{ trans('service::template.common.note') }}</label>
                                    {!! Form::text('note', isset($service->noteTitle)?$service->noteTitle:old('note'), array('maxlength' => 200,'id'=>'note', 'class' => 'form-control hasAlias seoField maxlength-handler titlespellingcheck','autocomplete'=>'off','data-url' => 'powerpanel/service')) !!}
                                    <span class="help-block">{{ $errors->first('note') }}</span>
                                </div>
                            </div>
                            {{-- Note Link --}}
                            <div class="col-md-12" style="display: none">
                                <div class="form-group @if($errors->first('notelink')) has-error @endif form-md-line-input cm-floating">
                                    @php if(isset($service_highLight->noteLink) && ($service_highLight->noteLink != $service->noteLink)){
                                    $Class_title = " highlitetext";
                                    }else{
                                    $Class_title = "";
                                    } @endphp
                                    <label class="form-label {!! $Class_title !!}" for="site_name">{{ trans('service::template.common.notelink') }} </label>
                                    {!! Form::text('notelink', isset($service->noteLink)?$service->noteLink:old('notelink'), array('maxlength' => 200,'id'=>'notelink','class' => 'form-control hasAlias seoField maxlength-handler titlespellingcheck','autocomplete'=>'off','data-url' => 'powerpanel/service')) !!}
                                    <span class="help-block">{{ $errors->first('notelink') }}</span>
                                </div>
                            </div>
                            {{-- Service Fees --}}
                            <div class="col-md-12" style="display: none">
                                <div class="form-group form-md-line-input cm-floating">
                                    @php 
                                    if(isset($service_highLight->chrServiceFees) && ($service_highLight->chrServiceFees != $service->chrServiceFees)){
                                        $Class_chrServiceFees = " highlitetext";
                                    }else{
                                        $Class_chrServiceFees = "";
                                    } 
                                    @endphp
                                    <label class="form-label {{ $Class_chrServiceFees }}">Display in Service Fees Table?</label>
                                    @if (isset($service->chrServiceFees) && $service->chrServiceFees == 'Y')
                                        @php $checked_section_link = true; @endphp
                                    @else
                                        @php $checked_section_link = null; @endphp
                                    @endif
                                    {{ Form::checkbox('chrServiceFees',null,$checked_section_link, array('id'=>'chrServiceFees','value'=>'Y')) }}
                                </div>
                            </div>
                            {{-- Register Application --}}
                            <div class="col-md-12">
                                <div class="@if($errors->first('registerapplication')) has-error @endif form-md-line-input cm-floating">
                                    <label class="form-label" for="site_name">Assign To Register of Applications </label>
                                    <select class="form-control" multiple="" name="registerapplication[]" id="registerapplication" data-choices data-choices-removeItem multiple>
                                        <option value="">Assign To Register of Applications</option>
                                    </select>
                                    <span class="help-block">{{ $errors->first('registerapplication') }}</span>
                                </div>
                            </div>
                            {{-- Licensees Register --}}
                            <div class="col-md-12">
                                <div class="@if($errors->first('licenseregister')) has-error @endif form-md-line-input cm-floating">
                                    <label class="form-label" for="site_name">Assign To Register Of Licensees </label>
                                    <select class="form-control" multiple="" name="licenseregister[]" id="licenseregister" data-choices data-choices-removeItem multiple>
                                        <option value="">Assign To Register Of Licensees</option>
                                    </select>
                                    <span class="help-block">{{ $errors->first('licenseregister') }}</span>
                                </div>
                            </div>
                            {{-- Photo --}}
                            <div class="col-lg-6 col-sm-12">
                                @if(isset($service_highLight->fkIntImgId) && ($service_highLight->fkIntImgId != $service->fkIntImgId))
                                @php $Class_fkIntImgId = " highlitetext"; @endphp
                                @else
                                @php $Class_fkIntImgId = ""; @endphp
                                @endif
                                <div class="image_thumb multi_upload_images cm-floating">
                                    @php $height = isset($settings->height)?$settings->height:292; $width = isset($settings->width)?$settings->width:372; @endphp
                                    <label class="form-label {{ $Class_fkIntImgId }}" for="front_logo">
                                        {{ trans('service::template.common.selectimage') }}
                                        <span class="img-note" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ trans('service::template.common.imageSize',['height'=>$height, 'width'=>$width]) }}">
                                            <i class="ri-information-line text-primary fs-16"></i>
                                        </span>
                                    </label>
                                    <div class="fileinput fileinput-new page-media" data-provides="fileinput">
                                        <div class="fileinput-preview thumbnail blog_image_img" data-trigger="fileinput">
                                            @if(old('image_url'))
                                            <img src="{{ old('image_url') }}" />
                                            @elseif(isset($service->fkIntImgId))
                                            <img src="{!! App\Helpers\resize_image::resize($service->fkIntImgId,120,120) !!}" />
                                            @else
                                            <div class="dz-message needsclick w-100 text-center">
                                                <div class="dropzone_icon"><i class="display-5 text-muted ri-upload-cloud-2-fill"></i></div>
                                                <h5 class="sbold dropzone-title">Drop files here or click to upload</h5>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="input-group">
                                            <a class="media_manager" data-multiple="false" onclick="MediaManager.open('blog_image');"><span class="fileinput-new"></span></a>
                                            <input class="form-control" type="hidden" id="blog_image" name="img_id" value="{{ isset($service->fkIntImgId)?$service->fkIntImgId:old('img_id') }}" />
                                            @php
                                            if (method_exists($MyLibrary, 'GetFolderID')) {
                                            if(isset($service->fkIntImgId)){
                                            $folderid = App\Helpers\MyLibrary::GetFolderID($service->fkIntImgId);
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
                        </div>
                    </div>
                </div>

                {{-- Page Content --}}
                <div class="card">
                    <div class="card-body p-30">
                        <div class="row">
                            <div class="col-md-12">
                                @if (Config::get('Constant.DEFAULT_VISUAL') == 'Y')
                                    <div id="body-roll">
                                        @php
                                        $sections = [];
                                        @endphp
                                        @if(isset($service))
                                        @php
                                        $sections = json_decode($service->txtDescription);
                                        @endphp
                                        @endif
                                        <!-- Builder include -->
                                        @php Powerpanel\VisualComposer\Controllers\VisualComposerController::page_section(['sections'=>$sections])@endphp
                                    </div>
                                @else
                                    <div class="form-group @if($errors->first('description')) has-error @endif">
                                        @php if(isset($service_highLight->txtDescription) && ($service_highLight->txtDescription != $service->txtDescription)){
                                        $Class_Description = " highlitetext";
                                        }else{
                                        $Class_Description = "";
                                        } @endphp
                                        <label class="form-label {!! $Class_Description !!}">{{ trans('service::template.common.description') }} <span aria-required="true" class="required"> * </span></label>
                                        {!! Form::textarea('description', isset($service->txtDescription)?$service->txtDescription:old('description'), array('class' => 'form-control','id'=>'txtDescription')) !!}
                                        <span class="help-block">{{ $errors->first('description') }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Display Information --}}
                <div class="card">
                    <div class="card-body p-30">
                        <div class="row">
                            <div class="col-md-12 col-lg-6">
                                <h4 class="form-section mb-3">{{ trans('service::template.common.displayinformation') }}</h4>
                                @php
                                $display_order_attributes = array('class' => 'form-control','maxlength'=>5,'autocomplete'=>'off');
                                @endphp
                                @if(isset($complaint_highLight->intDisplayOrder) && ($complaint_highLight->intDisplayOrder != $service->intDisplayOrder))
                                @php $Class_intDisplayOrder = " highlitetext"; @endphp
                                @else
                                @php $Class_intDisplayOrder = ""; @endphp
                                @endif
                                <div class="form-group @if($errors->first('order')) has-error @endif form-md-line-input cm-floating">
                                    <label class="form-label {{ $Class_intDisplayOrder }}" for="site_name">{{ trans('service::template.common.displayorder') }} <span aria-required="true" class="required"> * </span></label>
                                    {!! Form::text('order', isset($service->intDisplayOrder)?$service->intDisplayOrder:1, $display_order_attributes) !!}
                                    <span style="color: red;">{{ $errors->first('order') }}</span>
                                    <div class="publish-info mt-3">
                                        @if(isset($service_highLight->chrPublish) && ($service_highLight->chrPublish != $service->chrPublish))
                                            @php $Class_chrPublish = " highlitetext"; @endphp
                                        @else
                                            @php $Class_chrPublish = ""; @endphp
                                        @endif
            
                                        @if(isset($service) && $service->chrAddStar == 'Y')
                                            <label class="control-label form-label"> Publish/ Unpublish</label>
                                            <input type="hidden" id="chrMenuDisplay" name="chrMenuDisplay" value="{{ isset($service->chrPublish) ? $service->chrPublish : '' }}">
                                            <p><b>NOTE:</b> This record is in Approval Request , so it can&#39;t be published/unpublished.</p>
                                        @elseif(isset($service) && $service->chrDraft == 'D' && $service->chrAddStar != 'Y')
                                            @include('powerpanel.partials.displayInfo',['Class_chrPublish'=>$Class_chrPublish,'display' => (isset($service->chrDraft)?$service->chrDraft:'D')])
                                        @else
                                            @include('powerpanel.partials.displayInfo',['Class_chrPublish'=>$Class_chrPublish,'display' => (isset($service->chrPublish)?$service->chrPublish:'Y')])
                                        @endif
                                    </div>
                                </div>
                            </div>
                            {{-- Form Action --}}
                            <div class="col-md-12">
                                <div class="form-actions">
                                    @if(isset($service->fkMainRecord) && $service->fkMainRecord != 0)
                                    <button type="submit" name="saveandexit" formmethod="post" class="btn btn-primary bg-gradient waves-effect waves-light btn-label me-1" value="saveandexit">
                                        <div class="flex-shrink-0">
                                            <i class="ri-check-line label-icon align-middle fs-20 me-2"></i>
                                        </div>
                                        {!! trans('service::template.common.approve') !!}
                                    </button>
                                    @else
                                    @if($userIsAdmin)
                                    <button type="submit" name="saveandedit" formmethod="post" class="btn btn-primary bg-gradient waves-effect waves-light btn-label me-1" value="saveandedit">
                                        <div class="flex-shrink-0">
                                            <i class="ri-save-line label-icon align-middle fs-20 me-2"></i>
                                        </div>
                                        {!! trans('service::template.common.saveandedit') !!}
                                    </button>
                                    <button type="submit" name="saveandexit" formmethod="post" class="btn btn-secondary bg-gradient waves-effect waves-light btn-label me-1" value="saveandexit">
                                        <div class="flex-shrink-0">
                                            <i class="ri-save-3-line label-icon align-middle fs-20 me-2"></i>
                                        </div>
                                        {!! trans('service::template.common.saveandexit') !!}
                                    </button>
                                    @else
                                    @if((isset($chrNeedAddPermission) && $chrNeedAddPermission == 'N') && (isset($charNeedApproval) && $charNeedApproval == 'N'))
                                    <button type="submit" name="saveandexit" formmethod="post" class="btn btn-secondary bg-gradient waves-effect waves-light btn-label me-1" value="saveandexit">
                                        <div class="flex-shrink-0">
                                            <i class="ri-save-3-line label-icon align-middle fs-20 me-2"></i>
                                        </div>
                                        {!! trans('service::template.common.saveandexit') !!}
                                    </button>
                                    @else
                                    <button type="submit" name="saveandexit" formmethod="post" class="btn btn-primary bg-gradient waves-effect waves-light btn-label me-1" value="approvesaveandexit">
                                        <div class="flex-shrink-0">
                                            <i class="ri-save-line label-icon align-middle fs-20 me-2"></i>
                                        </div>
                                        {!! trans('service::template.common.approvesaveandexit') !!}
                                    </button>
                                    @endif
                                    @endif
                                    @endif
                                    <a class="btn btn-danger bg-gradient waves-effect waves-light btn-label me-1" href="{{ url('powerpanel/service') }}">
                                        <div class="flex-shrink-0">
                                            <i class="ri-close-line label-icon align-middle fs-20 me-2"></i>
                                        </div>
                                        {{ trans('service::template.common.cancel') }}
                                    </a>
                                    @if(isset($service) && !empty($service) && $userIsAdmin)
                                    <a style="display: none" class="btn btn-info bg-gradient waves-effect waves-light btn-label me-1" title="Preview" onClick="generatePreview('{{url('/previewpage?url='.(App\Helpers\MyLibrary::getFrontUri('service')['uri']))}}');">
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
            $("#passid").hide();
        }
    }
    window.site_url = '{!! url("/") !!}';
    var seoFormId = 'frmService';
    var user_action = "{{ isset($service)?'edit':'add' }}";
    var selectedCategory = "{{ isset($service->intFKCategory)? $service->intFKCategory : '' }}";
    var moduleAlias = "{{ App\Helpers\MyLibrary::getFrontUri('service')['moduleAlias'] }}";
    var selectedRegisterRecord = "{{ isset($service->varRegisterID)? $service->varRegisterID : '' }}";
    var selectedLicenceRegisterRecord = "{{ isset($service->varLicenceRegisterID)? $service->varLicenceRegisterID : '' }}";
    var preview_add_route = '{!! route("powerpanel.service.addpreview") !!}';
    var previewForm = $('#frmService');
    var isDetailPage = true;
</script>


<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-taginput/bootstrap-tagsinput.min.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/service/service_validations.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/custom.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/custom-alias/alias-generator.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH . 'resources/global/plugins/seo-generator/seo-info-generator.js' }}" type="text/javascript"></script>

@if (Config::get('Constant.DEFAULT_VISUAL') == 'Y')
@php Powerpanel\VisualComposer\Controllers\VisualComposerController::get_builder_css_js()@endphp
@endif
@endsection