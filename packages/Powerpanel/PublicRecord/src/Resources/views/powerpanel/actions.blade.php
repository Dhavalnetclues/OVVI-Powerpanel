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
        
        <div class="card">
            <div class="card-body">
                <div class="live-preview">
                    {!! Form::open(['method' => 'post','id'=>'frmPublicRecord']) !!}
                        @if(isset($publicrecord))
                        @if (File::exists(base_path() . '/resources/views/powerpanel/partials/lockedpage.blade.php') != null)
                        @include('powerpanel.partials.lockedpage',['pagedata'=>$publicrecord])
                        @endif
                        @endif

                        <!-- Sector type -->
                        <div class="mb-3 @if($errors->first('sector')) has-error @endif form-md-line-input">
                            @if(isset($publicrecord_highLight->varSector) && ($publicrecord_highLight->varSector != $publicrecord->varSector))
                            @php $Class_varSector = " highlitetext"; @endphp
                            @else
                            @php $Class_varSector = ""; @endphp
                            @endif
                            @include('shiledcmstheme::powerpanel.partials.sector-dropdown', ['selected_sector' => isset($publicrecord->varSector)?$publicrecord->varSector:'','Class_varSector' => $Class_varSector])
                            <span class="help-block">
                                {{ $errors->first('sector') }}
                            </span>
                        </div>

                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3 @if($errors->first('category_id')) has-error @endif form-md-line-input">
                                        @if(isset($publicrecord_highLight->txtCategories) && ($publicrecord_highLight->txtCategories != $publicrecord->txtCategories))
                                        @php $Class_txtCategories = " highlitetext"; @endphp
                                        @else
                                        @php $Class_txtCategories = ""; @endphp
                                        @endif
                                        <label class="form-label {{ $Class_txtCategories }}" for="site_name">Select Category <span aria-required="true" class="required"> * </span></label>
                                        <select class="form-control" data-show-subtext="true" size="10" name="category_id" id="category_id" data-choices>
                                            <option value="">Select Category </option>;
                                        </select>
                                        <span class="help-block">
                                            {{ $errors->first('category_id') }}
                                        </span>
                                    </div>
                                    <div class="mb-3 @if($errors->first('title')) has-error @endif form-md-line-input">
                                        @php if(isset($publicrecord_highLight->varTitle) && ($publicrecord_highLight->varTitle != $publicrecord->varTitle)){
                                        $Class_title = " highlitetext";
                                        }else{
                                        $Class_title = "";
                                        } @endphp
                                        <label class="form-label {!! $Class_title !!}" for="site_name">{{ trans('public-record::template.common.title') }} <span aria-required="true" class="required"> * </span></label>
                                        {!! Form::text('title', isset($publicrecord->varTitle)?$publicrecord->varTitle:old('title'), array('maxlength' => 200,'id'=>'title','placeholder'=>'Title', 'class' => 'form-control hasAlias seoField maxlength-handler titlespellingcheck','autocomplete'=>'off','data-url' => 'powerpanel/public-record')) !!}
                                        <span class="help-block">
                                            {{ $errors->first('title') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            {!! Form::hidden('fkMainRecord', isset($publicrecord->fkMainRecord)?$publicrecord->fkMainRecord:old('fkMainRecord')) !!}
                            <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3 @if($errors->first('short_description')) has-error @endif form-md-line-input">
                                    @php if(isset($publicrecord_highLight->varAuthor) && ($publicrecord_highLight->varAuthor != $publicrecord->varAuthor)){
                                    $Class_Author = " highlitetext";
                                    }else{
                                    $Class_Author = "";
                                    } @endphp
                                    <label class="form-label {!! $Class_Author !!}">Author<span aria-required="true" class="required"> * </span></label>
                                    {!! Form::text('author', isset($publicrecord->varAuthor)?$publicrecord->varAuthor:old('author'), array('maxlength' => 200,'class' => 'form-control seoField maxlength-handler','id'=>'author','placeholder'=>'Author')) !!}
                                    <span class="help-block">{{ $errors->first('short_description') }}</span>
                                </div>
                            </div>
                        </div>

                        @php $defaultDt = (null !== old('start_date_time'))?old('start_date_time'):date('Y-m-d'); @endphp
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3 form-md-line-input">
                                    @php if(isset($publicrecord_highLight->dtDateTime) && ($publicrecord_highLight->dtDateTime != $publicrecord->dtDateTime)){
                                    $Class_date = " highlitetext";
                                    }else{
                                    $Class_date = "";
                                    } @endphp
                                    <label class="control-label form-label {!! $Class_date !!}">Record Date<span aria-required="true" class="required"> * </span></label>
                                    <div class="input-group date form_meridian_datetime @if($errors->first('start_date_time')) has-error @endif" data-date="{{ Carbon\Carbon::today()->format('Y-m-d') }}T15:25:00Z">
                                        <span class="input-group-text date_default" id="basic-addon1">
                                            <i class="ri-calendar-fill"></i>
                                        </span>
                                        {!! Form::text('start_date_time',date(Config::get('Constant.DEFAULT_DATE_FORMAT'),strtotime((isset($publicrecord->dtDateTime)&& !empty($publicrecord->dtDateTime)) ? $publicrecord->dtDateTime:$defaultDt)) , array('class' => 'form-control', 'data-provider' => 'flatpickr', 'data-date-format' => Config::get('Constant.DEFAULT_DATE_FORMAT'), 'data-enable-time' => '','maxlength'=>160,'size'=>'16','id'=>'publicrecord_start_date','autocomplete'=>'off','onkeypress'=>"javascript: return KeycheckOnlyDate(event);",'onpaste'=>'return false')) !!}
                                    </div>
                                    <span class="help-block">{{ $errors->first('start_date_time') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="image_thumb multi_upload_images">
                                    <div class="mb-3">
                                        @php if(isset($publicrecord_highLight->fkIntDocId) && ($publicrecord_highLight->fkIntDocId != $publicrecord->fkIntDocId)){
                                        $Class_file = " highlitetext";
                                        }else{
                                        $Class_file = "";
                                        } @endphp
                                        <label class="form-label {!! $Class_file !!}">Select Documents<span aria-required="true" class="required"> * </span></label>
                                        <div class="clearfix"></div>
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width:100%;float:left; height:120px;position: relative;">
                                                <img class="img_opacity" src="{{ $CDN_PATH.'resources/images/upload_file.gif' }}" />
                                            </div>
                                            <div class="input-group">
                                                <a class="document_manager multiple-selection" data-multiple="true" onclick="MediaManager.openDocumentManager('public-record');"><span class="fileinput-new"></span></a>
                                                <input class="form-control" type="hidden" id="public-record" name="doc_id" value="{{ isset($publicrecord->fkIntDocId)?$publicrecord->fkIntDocId:old('doc_id') }}" />
                                                @php
                                                if (method_exists($MyLibrary, 'GetDocumentFolderID')) {
                                                    if(isset($publicrecord->fkIntDocId)){
                                                    $folderid = App\Helpers\MyLibrary::GetDocumentFolderID($publicrecord->fkIntDocId);
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
                                    </div>
                                    <div class="clearfix"></div>
                                    <span>(Recommended documents *.txt, *.pdf, *.doc, *.docx, *.ppt, *.xls, *.xlsx, *.xlsm formats are supported. Document should be maximum size of 45 MB.)</span>
                                </div>
                            </div>
                            @if(!empty($publicrecord->fkIntDocId) && isset($publicrecord->fkIntDocId))
                            @php
                            $docsAray = explode(',', $publicrecord->fkIntDocId);
                            $docObj   = App\Document::getDocDataByIds($docsAray);
                            @endphp
                            <div class="col-md-12" id="public-record_documents">
                                <div class="multi_image_list" id="multi_document_list">
                                    <ul>
                                        @if(count($docObj) > 0)
                                        @foreach($docObj as $value)
                                        <li id="doc_{{ $value->id }}">
                                            <span title="{{ $value->txtDocumentName }}.{{ $value->varDocumentExtension }}">
                                                <img  src="{{ $CDN_PATH.'assets/images/document_icon.png' }}" alt="Img" />
                                                <a href="javascript:;" onclick="MediaManager.removeDocumentFromGallery('{{ $value->id }}');" class="delect_image" data-dismiss="fileinput"><i class="ri-delete-bin-line"></i></a>
                                            </span>
                                        </li>
                                        @endforeach
                                        @endif
                                    </ul>
                                </div>
                            </div>
                            @else
                            <div class="col-md-12" id="public-record_documents"></div>
                            @endif
                        </div>

                        <h3 class="form-section">{{ trans('public-record::template.common.displayinformation') }}</h3>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                @if(isset($publicrecord_highLight->chrPublish) && ($publicrecord_highLight->chrPublish != $publicrecord->chrPublish))
                                    @php $Class_chrPublish = " highlitetext"; @endphp
                                @else
                                    @php $Class_chrPublish = ""; @endphp
                                @endif
                                @if(isset($publicrecord) && $publicrecord->chrAddStar == 'Y')
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="control-label form-label"> Publish/ Unpublish</label>
                                            <input type="hidden" id="chrMenuDisplay" name="chrMenuDisplay" value="{{ isset($publicrecord->chrPublish) ? $publicrecord->chrPublish : '' }}">
                                            <p><b>NOTE:</b> This record is in Approval Request , so it can&#39;t be published/unpublished.</p>
                                        </div>
                                    </div>
                                @elseif(isset($publicrecord) && $publicrecord->chrDraft == 'D' && $publicrecord->chrAddStar != 'Y')
                                    @include('powerpanel.partials.displayInfo',['Class_chrPublish'=>$Class_chrPublish,'display' => (isset($publicrecord->chrDraft)?$publicrecord->chrDraft:'D')])
                                @else
                                    @include('powerpanel.partials.displayInfo',['Class_chrPublish'=>$Class_chrPublish,'display' => (isset($publicrecord->chrPublish)?$publicrecord->chrPublish:'Y')])
                                @endif
                            </div>
                        </div>

                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-12">
                                    @if(isset($publicrecord->fkMainRecord) && $publicrecord->fkMainRecord != 0)
                                    <button type="submit" name="saveandexit" class="btn btn-primary" value="saveandexit">{!! trans('public-record::template.common.approve') !!}</button>
                                    @else
                                    @if($userIsAdmin)
                                    <button type="submit" name="saveandedit" class="btn btn-primary" value="saveandedit">{!! trans('public-record::template.common.saveandedit') !!}</button>
                                    <button type="submit" name="saveandexit" class="btn btn-primary" value="saveandexit">{!! trans('public-record::template.common.saveandexit') !!}</button>
                                    @else
                                    @if((isset($chrNeedAddPermission) && $chrNeedAddPermission == 'N') && (isset($charNeedApproval) && $charNeedApproval == 'N'))
                                    <button type="submit" name="saveandexit" class="btn btn-primary" value="saveandexit">{!! trans('public-record::template.common.saveandexit') !!}</button>
                                    @else
                                    <button type="submit" name="saveandexit" class="btn btn-primary" value="approvesaveandexit">{!! trans('public-record::template.common.approvesaveandexit') !!}</button>
                                    @endif
                                    @endif
                                    @endif
                                    <a class="btn btn-danger" href="{{ url('powerpanel/public-record') }}">{{ trans('public-record::template.common.cancel') }}</a>
                                    @if(isset($publicrecord) && !empty($publicrecord) && $userIsAdmin)
                                        &nbsp;<a style="display: none" class="btn btn-primary" title="Preview" onClick="generatePreview('{{url('/previewpage?url='.(App\Helpers\MyLibrary::getFrontUri('public-record')['uri']))}}');">Preview</a>
                                        @endif
                                </div>
                            </div>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div><!--end row-->


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
    var seoFormId = 'frmPublicRecord';
    var user_action = "{{ isset($publicrecord)?'edit':'add' }}";
    var selectedCategory = '{{ isset($publicrecord->txtCategories)?$publicrecord->txtCategories:' ' }}';
                                            var selectedId = '{{ isset($publicrecord->id)?$publicrecord->id:' ' }}';
    var preview_add_route = '{!! route("powerpanel.public-record.addpreview") !!}';
    var previewForm = $('#frmPublicRecord');
    var isDetailPage = true;
</script>
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-taginput/bootstrap-tagsinput.min.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/custom.js' }}" type="text/javascript"></script>

<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js' }}" type="text/javascript"></script>

<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/public-record/publicrecord_validations.js' }}" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS -->
 @if (Config::get('Constant.DEFAULT_VISUAL') == 'Y')
  @php Powerpanel\VisualComposer\Controllers\VisualComposerController::get_builder_css_js()@endphp
  @endif
@endsection