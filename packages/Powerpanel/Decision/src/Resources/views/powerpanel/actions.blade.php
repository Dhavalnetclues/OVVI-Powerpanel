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
        <div class="alert alert-success">
            {{ Session::get('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        @if(Session::has('error'))
        <div class="alert alert-danger display-hide" style="display: block;">
            {{ Session::get('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        
        <div class="card">
            <div class="card-body">
                <div class="live-preview">
                    {!! Form::open(['method' => 'post','id'=>'frmDecision']) !!}
                        <div class="form-body">
                            {!! Form::hidden('fkMainRecord', isset($decision->fkMainRecord)?$decision->fkMainRecord:old('fkMainRecord')) !!}
                            @if(isset($decision))
                            @if (File::exists(base_path() . '/resources/views/powerpanel/partials/lockedpage.blade.php') != null)
                            @include('powerpanel.partials.lockedpage',['pagedata'=>$decision])
                            @endif
                            @endif

                            <!-- Sector type -->
                            <div class="mb-3 @if($errors->first('sector')) has-error @endif form-md-line-input">
                                @if(isset($decisionHighLight->varSector) && ($decisionHighLight->varSector != $decision->varSector))
                                @php $Class_varSector = " highlitetext"; @endphp
                                @else
                                @php $Class_varSector = ""; @endphp
                                @endif
                                @include('shiledcmstheme::powerpanel.partials.sector-dropdown', ['selected_sector' => isset($decision->varSector)?$decision->varSector:'','Class_varSector' => $Class_varSector])
                                <span class="help-block">
                                    {{ $errors->first('sector') }}
                                </span>
                            </div>

                            <div class="mb-3 @if($errors->first('category_id')) has-error @endif form-md-line-input">
                                @if(isset($decisionHighLight->txtCategories) && ($decisionHighLight->txtCategories != $decision->txtCategories))
                                @php $Class_txtCategories = " highlitetext"; @endphp
                                @else
                                @php $Class_txtCategories = ""; @endphp
                                @endif
                                <label class="form_title {{ $Class_txtCategories }}" for="site_name">Select Category <span aria-required="true" class="required"> * </span> </label>
                                <select class="form-control" data-show-subtext="true"  size="10" name="category_id" id="category_id" data-choices>
                                    <option value="">Select Category</option>;
                                </select>
                                <span class="help-block">{{ $errors->first('category_id') }}</span>
                            </div>
                            @if(isset($decisionHighLight->varTitle) && ($decisionHighLight->varTitle != $decision->varTitle))
                            @php $Class_title = " highlitetext"; @endphp
                            @else
                            @php $Class_title = ""; @endphp
                            @endif
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3 @if($errors->first('title')) has-error @endif form-md-line-input">
                                        <label class="form_title {{ $Class_title }}" class="site_name">{{ trans('decision::template.common.title') }} <span aria-required="true" class="required"> * </span></label>
                                        {!! Form::text('title', isset($decision->varTitle)?$decision->varTitle:old('title'), array('maxlength' => 150, 'class' => 'form-control hasAlias seoField maxlength-handler titlespellingcheck','autocomplete'=>'off','data-url' => 'powerpanel/decision','id' =>'title')) !!}
                                        <span class="help-block">
                                            {{ $errors->first('title') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="display: none">
                                <div class="col-md-12">
                                    <!-- code for alias -->
                                    {!! Form::hidden(null, null, array('class' => 'hasAlias','data-url' => 'powerpanel/decision')) !!}
                                    {!! Form::hidden('alias', isset($decision->alias->varAlias) ? $decision->alias->varAlias:old('alias'), array('class' => 'aliasField')) !!}
                                    {!! Form::hidden('oldAlias', isset($decision->alias->varAlias) ? $decision->alias->varAlias:old('alias')) !!}
                                    {!! Form::hidden('previewId') !!}
                                    <div class="mb-3 alias-group {{!isset($decision->alias->varAlias)?'hide':''}}">
                                        <label class="form_title" for="Url">Url :</label>
                                        @if(isset($decision->alias->varAlias) && !$userIsAdmin)
                                        <a class="alias">
                                            {!! url("/") !!}
                                        </a>
                                        @else
                                        @if(auth()->user()->can('decision-create'))
                                        <a href="javascript:void(o);" class="alias">{!! url("/") !!}</a>
                                        <a href="javascript:void(0);" class="editAlias" title="Edit">
                                            <i class="ri-pencil-line"></i>
                                        </a>
                                        <a class="without_bg_icon openLink" title="Open Link" onClick="generatePreview('{{  url('/previewpage?url='.(App\Helpers\MyLibrary::getFrontUri('decision')['uri']))  }}');">
                                            <i class="ri-external-link-line" aria-hidden="true"></i>
                                        </a>
                                        @endif
                                        @endif
                                    </div>
                                    <span class="help-block">
                                        {{ $errors->first('alias') }}
                                    </span>
                                    <!-- code for alias -->
                                </div>
                            </div>
                            @php $defaultDt = (null !== old('decision_date'))?old('decision_date'):date('Y-m-d'); @endphp
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3 form-md-line-input">
                                        @php if(isset($decisionHighLight->DecisionDate) && ($decisionHighLight->DecisionDate != $decision->DecisionDate)){
                                        $Class_date = " highlitetext";
                                        }else{
                                        $Class_date = "";
                                        } @endphp

                                        <label class="control-label form-label {!! $Class_date !!}">{{ trans('cmspage::template.common.startDateAndTime') }}<span aria-required="true" class="required"> * </span></label>
                                        <div class="input-group date form_meridian_datetime @if($errors->first('decision_date')) has-error @endif" data-date="{{ Carbon\Carbon::today()->format('Y-m-d') }}T15:25:00Z">
                                            <span class="input-group-text date_default" id="basic-addon1">
                                                <i class="ri-calendar-fill"></i>
                                            </span>
                                            {!! Form::text('decision_date', date(Config::get('Constant.DEFAULT_DATE_FORMAT'),strtotime(isset($decision->DecisionDate)?$decision->DecisionDate:$defaultDt)), array('class' => 'form-control', 'data-provider' => 'flatpickr', 'data-date-format' => Config::get('Constant.DEFAULT_DATE_FORMAT'), 'data-enable-time' => '','maxlength'=>160,'size'=>'16','id'=>'decision_date','autocomplete'=>'off','onkeypress'=>"javascript: return KeycheckOnlyDate(event);",'onpaste'=>'return false')) !!}
                                        </div>
                                        <span class="help-block">{{ $errors->first('decision_date') }}</span>
                                    </div>
                                </div>
                            </div>
                            @if ((isset($decision->varFieldType) && $decision->varFieldType == 'link') || old('link_type') == 'link')
                            @php $checked_yes = 'checked' @endphp
                            @else
                            @php $checked_yes = '' @endphp
                            @endif
                            @if ((isset($decision->varFieldType) && $decision->varFieldType == 'document') || old('link_type') == 'document' || (!isset($decision->varFieldType) && old('link_type') == null))
                            @php $ichecked_yes = 'checked' @endphp
                            @else
                            @php $ichecked_yes = '' @endphp
                            @endif
                            <div class="mb-3 {{ $errors->has('field_type') ? ' has-error' : '' }}">
                                @php 
                                if(isset($decisionHighLight->varFieldType) && ($decisionHighLight->varFieldType != $decision->varFieldType)){
                                    $Class_varFieldType = " highlitetext";
                                }else{
                                    $Class_varFieldType = "";
                                }
                                @endphp
                                <label class="form_title {{ $Class_varFieldType }}" for="field_type">Select Type <span aria-required="true" class="required"> * </span></label>
                                <div class="md-radio-inline">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" value="link" name="field_type" id="link_type" {{ $checked_yes }}>
                                        <label for="link_type">Link</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" value="document" name="field_type" id="document_type" {{ $ichecked_yes }}>
                                        <label for="document_type">Document</label>
                                    </div>
                                </div>
                                <span class="help-block"><strong>{{ $errors->first('field_type') }}</strong></span>
                            </div>

                            <div class="row" id="linkId">
                                <div class="col-md-12">
                                    <div class="mb-3 @if($errors->first('link')) has-error @endif form-md-line-input">
                                        <label class="form_title {{ $Class_title }}" class="site_name">{{ trans('Link') }}<span aria-required="true" class="required"> * </span> </label>
                                        {!! Form::text('link', isset($decision->varLink)?$decision->varLink:old('link'), array('maxlength' => 150,'placeholder'=>'Link' ,'class' => 'form-control hasAlias seoField maxlength-handler titlespellingcheck','autocomplete'=>'off','data-url' => 'powerpanel/link','id' =>'link')) !!}
                                        <span class="help-block">
                                            {{ $errors->first('link') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="DocId">
                                <div class="col-md-12">
                                    @if(isset($decisionHighLight->fkIntDocId) && ($decisionHighLight->fkIntDocId != $decision->fkIntDocId))
                                    @php $Class_fkIntDocId = " highlitetext"; @endphp
                                    @else
                                    @php $Class_fkIntDocId = ""; @endphp
                                    @endif
                                    <div class="image_thumb multi_upload_images">
                                        <div class="mb-3">
                                            <label class="form_title {{ $Class_fkIntDocId }}">Select Documents <span aria-required="true" class="required"> * </span></label>
                                            <div class="clearfix"></div>
                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width:100%;float:left; height:120px;position: relative;">
                                                    <img class="img_opacity" src="{{ $CDN_PATH.'resources/images/upload_file.gif' }}" />
                                                </div>
                                                <div class="input-group">
                                                    <a class="document_manager multiple-selection" data-multiple="false" onclick="MediaManager.openDocumentManager('decision');"><span class="fileinput-new"></span></a>
                                                    <input class="form-control" type="hidden" id="decision" name="doc_id" value="{{ isset($decision->fkIntDocId)?$decision->fkIntDocId:old('doc_id') }}" />
                                                    @php
                                                    if (method_exists($MyLibrary, 'GetDocumentFolderID')) {
                                                    if(isset($decision->fkIntDocId)){
                                                    $folderid = App\Helpers\MyLibrary::GetDocumentFolderID($decision->fkIntDocId);
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
                                    <span class="help-block">
                                        {{ $errors->first('doc_id') }}
                                    </span>
                                </div>
                                @if(!empty($decision->fkIntDocId) && isset($decision->fkIntDocId))
                                @php
                                $docsAray = explode(',', $decision->fkIntDocId);
                                $docObj   = App\Document::getDocDataByIds($docsAray);
                                @endphp
                                <div class="col-md-12" id="decision_documents">
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
                                <div class="col-md-12" id="decision_documents"></div>
                                @endif
                            </div>
                            <div class="clearfix"></div>

                            @if(isset($decision->intSearchRank))
                            @php $srank = $decision->intSearchRank; @endphp
                            @else
                            @php
                            $srank = null !== old('search_rank') ? old('search_rank') : 2 ;
                            @endphp
                            @endif

                            @if(isset($decisionHighLight->intSearchRank) && ($decisionHighLight->intSearchRank != $decision->intSearchRank))
                            @php $Class_intSearchRank = " highlitetext"; @endphp
                            @else
                            @php $Class_intSearchRank = ""; @endphp
                            @endif

                            <h3 class="form-section">{{ trans('decision::template.common.displayinformation') }}</h3>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    @if(isset($decisionHighLight->chrPublish) && ($decisionHighLight->chrPublish != $decision->chrPublish))
                                        @php $Class_chrPublish = " highlitetext"; @endphp
                                    @else
                                        @php $Class_chrPublish = ""; @endphp
                                    @endif

                                    @if(isset($decision) && $decision->chrAddStar == 'Y')
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="control-label form_title"> Publish/ Unpublish</label>
                                                <input type="hidden" id="chrMenuDisplay" name="chrMenuDisplay" value="{{ isset($decision->chrPublish) ? $decision->chrPublish : '' }}">
                                                <p><b>NOTE:</b> This record is in Approval Request , so it can&#39;t be published/unpublished.</p>
                                            </div>
                                        </div>
                                    @elseif(isset($decision) && $decision->chrDraft == 'D' && $decision->chrAddStar != 'Y')
                                        @include('powerpanel.partials.displayInfo',['Class_chrPublish'=>$Class_chrPublish,'display' => (isset($decision->chrDraft)?$decision->chrDraft:'D')])
                                    @else
                                        @include('powerpanel.partials.displayInfo',['Class_chrPublish'=>$Class_chrPublish,'display' => (isset($decision->chrPublish)?$decision->chrPublish:'Y')])
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-12">
                                    @if(isset($decision->fkMainRecord) && $decision->fkMainRecord != 0)
                                    <button type="submit" name="saveandexit" class="btn btn-primary" value="saveandexit">{!! trans('decision::template.common.approve') !!}</button>
                                    @else
                                    @if($userIsAdmin)
                                    <button type="submit" name="saveandedit" class="btn btn-primary" value="saveandedit">{!! trans('decision::template.common.saveandedit') !!}</button>
                                    <button type="submit" name="saveandexit" class="btn btn-primary" value="saveandexit">{!! trans('decision::template.common.saveandexit') !!}</button>
                                    @else
                                    @if((isset($chrNeedAddPermission) && $chrNeedAddPermission == 'N') && (isset($charNeedApproval) && $charNeedApproval == 'N'))
                                    <button type="submit" name="saveandexit" class="btn btn-primary" value="saveandexit">{!! trans('decision::template.common.saveandexit') !!}</button>
                                    @else
                                    <button type="submit" name="saveandexit" class="btn btn-primary" value="approvesaveandexit">{!! trans('decision::template.common.approvesaveandexit') !!}</button>
                                    @endif
                                    @endif
                                    @endif
                                    <a class="btn btn-danger" href="{{ url('powerpanel/decision') }}">{{ trans('decision::template.common.cancel') }}</a>
                                    @if(isset($decision) && !empty($decision) && $userIsAdmin)
                                    &nbsp;<a style="display: none" class="btn btn-primary" title="Preview" onClick="generatePreview('{{url('/previewpage?url='.(App\Helpers\MyLibrary::getFrontUri('decision')['uri']))}}');">Preview</a>
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
<!-- <link href="{{ $CDN_PATH.'resources/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css' }}" rel="stylesheet" type="text/css"/>
<link href="{{ $CDN_PATH.'resources/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css' }}" rel="stylesheet" type="text/css" />
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js' }}" type="text/javascript"></script> -->
<script type="text/javascript">
    window.site_url = '{!! url("/") !!}';
    // var seoFormId = 'frmDecision';
    var user_action = "{{ isset($decision)?'edit':'add' }}";
    // var moduleAlias = "{{ App\Helpers\MyLibrary::getFrontUri('decision')['moduleAlias'] }}";
    var selectedCategory = '{{ isset($decision->txtCategories)?$decision->txtCategories:' ' }}';
    var selectedId = '{{ isset($decision->id)?$decision->id:' ' }}';
    var categoryAllowed = false;
    var preview_add_route = '{!! route("powerpanel.decision.addpreview") !!}';
    var previewForm = $('#frmDecision');
    var isDetailPage = true;
    function generate_seocontent1(formname) {
    var Meta_Title = document.getElementById('title').value + "";
    var abcd = $('textarea#txtDescription').val();
    var def = abcd.replace(/<a(\s[^>]*)?>.*?<\/a>/ig, "")
        var abc = def.replace(/^(\s*)|(\s*)$/g, '').replace(/\s+/g, ' ');
    var outString1 = abc.replace(/(<([^>]+)>)/ig, "");
    var Meta_Description = outString1.substr(0, 200);
    var Meta_Keyword = document.getElementById('title').value + "" + document.getElementById('title').value + ", " + document.getElementById('title').value;
    $('#varMetaTitle').val(Meta_Title);
    // $('#varMetaKeyword').val(Meta_Keyword);
    $('#varMetaDescription').val(Meta_Description);
    $('#meta_title').html(Meta_Title);
    $('#meta_description').html(Meta_Description);
    }
    @can('decision-category-list')
        categoryAllowed = true;
    @endcan
    function OpenPassword(val) {
    if (val == 'PP') {
    $("#passid").show();
    } else {
    $("#passid").hide();
    }
    }
</script>
<!-- <script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-taginput/bootstrap-tagsinput.min.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js' }}" type="text/javascript"></script> -->
<script src="{{ $CDN_PATH.'resources/pages/scripts/custom.js' }}" type="text/javascript"></script>
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js' }}" type="text/javascript"></script>
<!--<script src="{{ $CDN_PATH.'resources/global/plugins/seo-generator/seo-info-generator.js' }}" type="text/javascript"></script>-->
<!--<script src="{{ $CDN_PATH.'resources/global/plugins/custom-alias/alias-generator.js' }}" type="text/javascript"></script>-->
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/decision/decision-validations.js' }}" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS -->
@if (Config::get('Constant.DEFAULT_VISUAL') == 'Y')
@php Powerpanel\VisualComposer\Controllers\VisualComposerController::get_builder_css_js()@endphp
@endif
@endsection