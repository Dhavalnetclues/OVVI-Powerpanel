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
            {!! Form::open(['method' => 'post','id'=>'frmPublications']) !!}
                <div class="card">
                    <div class="card-body p-30 pb-0">
                        @if(isset($publications))
                        <div class="row pagetitle-heading mb-3">
                            <div class="col-sm-11 col-11">
                                <h4 class="page-title fw-semibold m-0">{{ $breadcrumb['inner_title']}}</h4>
                            </div>
                            <div class="col-sm-1 col-1 lock-link">
                                @if (File::exists(base_path() . '/resources/views/powerpanel/partials/lockedpage.blade.php') != null)
                                @include('powerpanel.partials.lockedpage',['pagedata'=>$publications])
                                @endif
                            </div>
                        </div>
                        @endif
                        <div class="row">
                            {!! Form::hidden('fkMainRecord', isset($publications->fkMainRecord)?$publications->fkMainRecord:old('fkMainRecord')) !!}
                            {{-- Sector type --}}
                            <div class="col-md-12">
                                <div class="@if($errors->first('sector')) has-error @endif form-md-line-input cm-floating">
                                    @if(isset($publicationsHighLight->varSector) && ($publicationsHighLight->varSector != $publications->varSector))
                                        @php $Class_varSector = " highlitetext"; @endphp
                                    @else
                                        @php $Class_varSector = ""; @endphp
                                    @endif
                                    @include('shiledcmstheme::powerpanel.partials.sector-dropdown', ['selected_sector' => isset($publications->varSector)?$publications->varSector:'','Class_varSector' => $Class_varSector])
                                    <span class="help-block">{{ $errors->first('sector') }}</span>
                                </div>
                            </div>
                            {{-- Select Category --}}
                            <div class="col-md-12">
                                <div class="@if($errors->first('category_id')) has-error @endif form-md-line-input cm-floating">
                                    @if(isset($publicationsHighLight->txtCategories) && ($publicationsHighLight->txtCategories != $publications->txtCategories))
                                    @php $Class_txtCategories = " highlitetext"; @endphp
                                    @else
                                    @php $Class_txtCategories = ""; @endphp
                                    @endif
                                    <label class="form-label {{ $Class_txtCategories }}" for="site_name">Select Category</label>
                                    <select class="form-control" data-show-subtext="true" size="10" name="category_id" id="category_id">
                                        <option value="">Select Category</option>
                                    </select>
                                    <span class="help-block">{{ $errors->first('category_id') }}</span>
                                </div>
                            </div>
                            {{-- Title --}}
                            <div class="col-lg-6 col-sm-12">
                                @if(isset($publicationsHighLight->varTitle) && ($publicationsHighLight->varTitle != $publications->varTitle))
                                @php $Class_title = " highlitetext"; @endphp
                                @else
                                @php $Class_title = ""; @endphp
                                @endif
                                <div class="@if($errors->first('title')) has-error @endif form-md-line-input cm-floating">
                                    <label class="form-label {{ $Class_title }}" class="site_name">{{ trans('publications::template.common.title') }} <span aria-required="true" class="required"> * </span></label>
                                    {!! Form::text('title', isset($publications->varTitle)?$publications->varTitle:old('title'), array('maxlength' => 150, 'class' => 'form-control hasAlias seoField maxlength-handler titlespellingcheck','autocomplete'=>'off','data-url' => 'powerpanel/publications','id' =>'title')) !!}
                                    <span class="help-block">{{ $errors->first('title') }}</span>
                                    <!-- code for alias -->
                                    <div class="link-url mt-2 d-none">
                                        {!! Form::hidden(null, null, array('class' => 'hasAlias','data-url' => 'powerpanel/publications')) !!}
                                        {!! Form::hidden('alias', isset($publications->alias->varAlias) ? $publications->alias->varAlias:old('alias'), array('class' => 'aliasField')) !!}
                                        {!! Form::hidden('oldAlias', isset($publications->alias->varAlias) ? $publications->alias->varAlias:old('alias')) !!}
                                        {!! Form::hidden('previewId') !!}
                                        <div class="alias-group {{!isset($publications->alias->varAlias)?'hide':''}}">
                                            <label class="form-label" for="Url">Url :</label>
                                            @if(isset($publications->alias->varAlias) && !$userIsAdmin)
                                            <a class="alias">{!! url("/") !!}</a>
                                            @else
                                            @if(auth()->user()->can('publications-create'))
                                            <a href="javascript:void;" class="alias">{!! url("/") !!}</a>
                                            <a href="javascript:void(0);" class="editAlias" title="Edit">
                                                <i class="ri-pencil-line"></i>
                                            </a>
                                            <a class="without_bg_icon openLink" title="Open Link" onClick="generatePreview('{{  url('/previewpage?url='.(App\Helpers\MyLibrary::getFrontUri('publications')['uri']))  }}');">
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
                            {{-- Publication Date --}}
                            <div class="col-lg-6 col-sm-12">
                                @php $defaultDt = (null !== old('publication_date'))?old('publication_date'):date('Y-m-d'); @endphp
                                <div class="form-md-line-input cm-floating">
                                    @php if(isset($publicationsHighLight->PublicationDate) && ($publicationsHighLight->PublicationDate != $publications->PublicationDate)){
                                    $Class_publications = " highlitetext";
                                    }else{
                                    $Class_publications = "";
                                    } @endphp
                                    <label class="control-label form-label {!! $Class_publications !!}">{{ trans('Publication Date') }}<span aria-required="true" class="required"> * </span></label>
                                    <div class="input-group date form_meridian_datetime @if($errors->first('publication_date')) has-error @endif" data-date="{{ Carbon\Carbon::today()->format('Y-m-d') }}T15:25:00Z">
                                        {!! Form::text('publication_date', date(Config::get('Constant.DEFAULT_DATE_FORMAT').' '.Config::get('Constant.DEFAULT_TIME_FORMAT'),strtotime(isset($publications->PublicationDate)?$publications->PublicationDate:$defaultDt)), array('class' => 'form-control', 'data-provider' => 'flatpickr', 'data-date-format' => Config::get('Constant.DEFAULT_DATE_FORMAT'), 'data-enable-time' => '','maxlength'=>160,'size'=>'16','id'=>'publication_date','autocomplete'=>'off','onkeypress'=>"javascript: return KeycheckOnlyDate(event);",'onpaste'=>'return false')) !!}
                                    </div>
                                    <span class="help-block">{{ $errors->first('publication_date') }}</span>
                                </div>
                            </div>
                            {{-- Recommended documents --}}
                            <div class="col-md-12">
                                <div class="image_thumb multi_upload_images mb-0">
                                    @if(isset($publicationsHighLight->fkIntDocId) && ($publicationsHighLight->fkIntDocId != $publications->fkIntDocId))
                                    @php $Class_fkIntDocId = " highlitetext"; @endphp
                                    @else
                                    @php $Class_fkIntDocId = ""; @endphp
                                    @endif
                                    <div class="cm-floating">
                                        <label class="form-label {{ $Class_fkIntDocId }}">
                                            Select Documents<span aria-required="true" class="required"> * </span>
                                            <span class="img-note" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Recommended documents *.txt, *.pdf, *.doc, *.docx, *.ppt, *.xls, *.xlsx, *.xlsm formats are supported. Document should be maximum size of 45 MB.">
                                                <i class="ri-information-line text-primary fs-16"></i>
                                            </span>
                                        </label>
                                        <div class="fileinput fileinput-new page-media" data-provides="fileinput">
                                            <div class="fileinput-preview thumbnail" data-trigger="fileinput">
                                                <div class="dz-message needsclick w-100 text-center">
                                                    <div class="dropzone_icon"><i class="display-5 text-muted ri-upload-cloud-2-fill"></i></div>
                                                    <h5 class="sbold dropzone-title">Drop files here or click to upload</h5>
                                                </div>
                                            </div>
                                            <div class="input-group">
                                                <a class="document_manager multiple-selection" data-multiple="false" onclick="MediaManager.openDocumentManager('publications');"><span class="fileinput-new"></span></a>
                                                <input class="form-control" type="hidden" id="publications" name="doc_id" value="{{ isset($publications->fkIntDocId)?$publications->fkIntDocId:old('doc_id') }}" />
                                                @php
                                                if (method_exists($MyLibrary, 'GetDocumentFolderID')) {
                                                if(isset($publications->fkIntDocId)){
                                                $folderid = App\Helpers\MyLibrary::GetDocumentFolderID($publications->fkIntDocId);
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
                                        @if(!empty($publications->fkIntDocId) && isset($publications->fkIntDocId))
                                            <div class="col-md-12 mt-3" id="publications_documents">
                                                @php
                                                $docsAray = explode(',', $publications->fkIntDocId);
                                                $docObj = App\Document::getDocDataByIds($docsAray);
                                                @endphp
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
                                            <div class="col-md-12" id="publications_documents"></div>
                                        @endif
                                        <span class="help-block">{{ $errors->first('doc_id') }}</span>
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
                            <div class="col-md-12">
                                <h4 class="form-section mb-3">{{ trans('publications::template.common.displayinformation') }}</h4>
                                @if(isset($publications->intSearchRank))
                                @php $srank = $publications->intSearchRank; @endphp
                                @else
                                @php
                                $srank = null !== old('search_rank') ? old('search_rank') : 2 ;
                                @endphp
                                @endif
                                <div class="@if($errors->first('display')) has-error @endif form-md-line-input cm-floating">
                                    @if(isset($publicationsHighLight->intSearchRank) && ($publicationsHighLight->intSearchRank != $publications->intSearchRank))
                                    @php $Class_intSearchRank = " highlitetext"; @endphp
                                    @else
                                    @php $Class_intSearchRank = ""; @endphp
                                    @endif

                                    @if(isset($publicationsHighLight->chrPublish) && ($publicationsHighLight->chrPublish != $publications->chrPublish))
                                        @php $Class_chrPublish = " highlitetext"; @endphp
                                    @else
                                        @php $Class_chrPublish = ""; @endphp
                                    @endif

                                    @if(isset($publications) && $publications->chrAddStar == 'Y')
                                        <label class="control-label form-label"> Publish/ Unpublish</label>
                                        <input type="hidden" id="chrMenuDisplay" name="chrMenuDisplay" value="{{ isset($publications->chrPublish) ? $publications->chrPublish : '' }}">
                                        <p><b>NOTE:</b> This record is in Approval Request , so it can&#39;t be published/unpublished.</p>
                                    @elseif(isset($publications) && $publications->chrDraft == 'D' && $publications->chrAddStar != 'Y')
                                        @include('powerpanel.partials.displayInfo',['Class_chrPublish'=>$Class_chrPublish,'display' => (isset($publications->chrDraft)?$publications->chrDraft:'D')])
                                    @else
                                        @include('powerpanel.partials.displayInfo',['Class_chrPublish'=>$Class_chrPublish,'display' => (isset($publications->chrPublish)?$publications->chrPublish:'Y')])
                                    @endif
                                </div>
                            </div>
                            {{-- Form Action --}}
                            <div class="col-md-12">
                                <div class="form-actions">
                                    @if(isset($publications->fkMainRecord) && $publications->fkMainRecord != 0)
                                        <button type="submit" name="saveandexit" formmethod="post" class="btn btn-primary bg-gradient waves-effect waves-light btn-label me-1" value="saveandexit">
                                            <div class="flex-shrink-0">
                                                <i class="ri-check-line label-icon align-middle fs-20 me-2"></i>
                                            </div>
                                            {!! trans('publications::template.common.approve') !!}
                                        </button>
                                    @else
                                        @if($userIsAdmin)
                                            <button type="submit" name="saveandedit" formmethod="post" class="btn btn-primary bg-gradient waves-effect waves-light btn-label me-1" value="saveandedit">
                                                <div class="flex-shrink-0">
                                                    <i class="ri-save-line label-icon align-middle fs-20 me-2"></i>
                                                </div>
                                                {!! trans('publications::template.common.saveandedit') !!}
                                            </button>
                                            <button type="submit" name="saveandexit" formmethod="post" class="btn btn-secondary bg-gradient waves-effect waves-light btn-label me-1" value="saveandexit">
                                                <div class="flex-shrink-0">
                                                    <i class="ri-save-3-line label-icon align-middle fs-20 me-2"></i>
                                                </div>
                                                {!! trans('publications::template.common.saveandexit') !!}
                                            </button>
                                        @else
                                            @if((isset($chrNeedAddPermission) && $chrNeedAddPermission == 'N') && (isset($charNeedApproval) && $charNeedApproval == 'N'))
                                                <button type="submit" name="saveandexit" formmethod="post" class="btn btn-secondary bg-gradient waves-effect waves-light btn-label me-1" value="saveandexit">
                                                    <div class="flex-shrink-0">
                                                        <i class="ri-save-3-line label-icon align-middle fs-20 me-2"></i>
                                                    </div>
                                                    {!! trans('publications::template.common.saveandexit') !!}
                                                </button>
                                            @else
                                                <button type="submit" name="saveandexit" formmethod="post" class="btn btn-primary bg-gradient waves-effect waves-light btn-label me-1" value="approvesaveandexit">
                                                    <div class="flex-shrink-0">
                                                        <i class="ri-save-line label-icon align-middle fs-20 me-2"></i>
                                                    </div>
                                                    {!! trans('publications::template.common.approvesaveandexit') !!}
                                                </button>
                                            @endif
                                        @endif
                                    @endif
                                    <a class="btn btn-danger bg-gradient waves-effect waves-light btn-label me-1" href="{{ url('powerpanel/career-category') }}">
                                        <div class="flex-shrink-0">
                                            <i class="ri-close-line label-icon align-middle fs-20 me-2"></i>
                                        </div>
                                        {{ trans('publications::template.common.cancel') }}
                                    </a>
                                    @if(isset($publications) && !empty($publications) && $userIsAdmin)
                                        <a style="display: none" class="btn btn-info bg-gradient waves-effect waves-light btn-label me-1" title="Preview" onClick="generatePreview('{{url('/previewpage?url='.(App\Helpers\MyLibrary::getFrontUri('publications')['uri']))}}');">
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
var seoFormId = 'frmPublications';
var user_action = "{{ isset($publications)?'edit':'add' }}";
var selectedCategory = '{{ isset($publications->txtCategories)?$publications->txtCategories:' ' }}';
var selectedId = '{{ isset($publications->id)?$publications->id:' ' }}';
var moduleAlias = "{{ App\Helpers\MyLibrary::getFrontUri('publications')['moduleAlias'] }}";
var categoryAllowed = false;
var preview_add_route = '{!! route("powerpanel.publications.addpreview") !!}';
var previewForm = $('#frmPublications');
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
@can('publications-category-list')
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
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-taginput/bootstrap-tagsinput.min.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/custom.js' }}" type="text/javascript"></script>
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/seo-generator/seo-info-generator.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/publications/publications-validations.js' }}" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS -->

@endsection