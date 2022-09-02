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
                    {!! Form::open(['method' => 'post','id'=>'frmConsultations']) !!}
                        @if(isset($consultations))
                        @if (File::exists(base_path() . '/resources/views/powerpanel/partials/lockedpage.blade.php') != null)
                        @include('powerpanel.partials.lockedpage',['pagedata'=> $consultations])
                        @endif
                        @endif

                        <!-- Sector type -->
                        <div class="mb-3 @if($errors->first('sector')) has-error @endif form-md-line-input">
                            @if(isset($consultations_highLight->varSector) && ($consultations_highLight->varSector != $consultations->varSector))
                                @php $Class_varSector = " highlitetext"; @endphp
                            @else
                                @php $Class_varSector = ""; @endphp
                            @endif
                            @include('shiledcmstheme::powerpanel.partials.sector-dropdown', ['selected_sector' => isset($consultations->varSector)?$consultations->varSector:'','Class_varSector' => $Class_varSector])
                            <span class="help-block">
                                {{ $errors->first('sector') }}
                            </span>
                        </div>

                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3 @if($errors->first('category_id')) has-error @endif form-md-line-input">
                                        @if(isset($consultations_highLight->txtCategories) && ($consultations_highLight->txtCategories != $consultations->txtCategories))
                                            @php $Class_txtCategories = " highlitetext"; @endphp
                                        @else
                                            @php $Class_txtCategories = ""; @endphp
                                        @endif

                                        <label class="form-label {{ $Class_txtCategories }}" for="site_name">Select Consultations Type<span aria-required="true" class="required"> * </span></label>
                                        
                                        <select class="form-control" data-choices name="category_id" id="category_id">
                                            <option value="">Select Sector Type</option>
                                            @foreach($categories as  $categoryKey => $categoryValue)
                                                @php $permissionName = 'consultations-list' @endphp
                                                @php $selected = ''; @endphp
                                                @if(isset($consultations->txtCategories))
                                                    @if($categoryKey == $consultations->txtCategories)
                                                        @php $selected = 'selected';  @endphp
                                                    @endif
                                                @endif
                                                <option value="{{$categoryKey}}" {{ $selected }}>{{ $categoryValue }}</option>
                                            @endforeach
                                        </select>
                                        <span class="help-block">
                                            {{ $errors->first('category_id') }}
                                        </span>
                                    </div>
                                    <div class="mb-3 @if($errors->first('title')) has-error @endif form-md-line-input">
                                        @php if(isset($consultations_highLight->varTitle) && ($consultations_highLight->varTitle != $consultations->varTitle)){
                                        $Class_title = " highlitetext";
                                        }else{
                                        $Class_title = "";
                                        } @endphp
                                        <label class="form-label {!! $Class_title !!}" for="site_name">{{ trans('consultations::template.common.name') }} <span aria-required="true" class="required"> * </span></label>
                                        {!! Form::text('title', isset($consultations->varTitle)?$consultations->varTitle:old('title'), array('maxlength' => 200,'id'=>'title', 'class' => 'form-control hasAlias seoField maxlength-handler titlespellingcheck','autocomplete'=>'off','data-url' => 'powerpanel/consultations')) !!}
                                        <span class="help-block">
                                            {{ $errors->first('title') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <!-- code for alias -->
                            {!! Form::hidden(null, null, array('class' => 'hasAlias','data-url' => 'powerpanel/consultations')) !!}
                            {!! Form::hidden('alias', isset($consultations->alias->varAlias)?$consultations->alias->varAlias:old('alias'), array('class' => 'aliasField')) !!}
                            {!! Form::hidden('oldAlias', isset($consultations->alias->varAlias)?$consultations->alias->varAlias:old('alias')) !!}
                            {!! Form::hidden('fkMainRecord', isset($consultations->fkMainRecord)?$consultations->fkMainRecord:old('fkMainRecord')) !!}
                            {!! Form::hidden('previewId') !!}
                            <div class="mb-3 alias-group {{!isset($consultations)?'hide':''}} ">
                                <label class="form-label" for="{{ trans('template.url') }}">{{ trans('consultations::template.common.url') }} :</label>
                                @if(isset($consultations->alias->varAlias) && !$userIsAdmin)
                                @if(isset($consultations->alias->varAlias))
                                <a class="alias">
                                {!! url("/") !!}
                                </a>
                                @endif
                                @else
                                @if(auth()->user()->can('consultations-create'))
                                <a href="javascript:void(0);" class="alias">{!! url("/") !!}</a>
                                <a href="javascript:void(0);" class="editAlias" title="{{ trans('consultations::template.common.edit') }}">
                                    <i class="ri-pencil-line"></i>
                                </a>
                                <a class="without_bg_icon openLink" title="Open Link" onClick="generatePreview('{{  url('/previewpage?url='.(App\Helpers\MyLibrary::getFrontUri('consultations')['uri']))  }}');">
                                    <i class="ri-external-link-line" aria-hidden="true"></i>
                                </a>
                                @endif
                                @endif
                            </div>
                            <span class="help-block">
                                {{ $errors->first('alias') }}
                            </span>
                            <!-- code for alias -->
                            @php $defaultDt = (null !== old('start_date_time'))?old('start_date_time'):date('Y-m-d H:i'); @endphp
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3 form-md-line-input">
                                        @php if(isset($consultations_highLight->dtDateTime) && ($consultations_highLight->dtDateTime != $consultations->dtDateTime)){
                                        $Class_date = " highlitetext";
                                        }else{
                                        $Class_date = "";
                                        } @endphp
                                        <label class="control-label form-label {!! $Class_date !!}">{{ trans('consultations::template.common.startDateAndTime') }}<span aria-required="true" class="required"> * </span></label>
                                        <div class="input-group date form_meridian_datetime @if($errors->first('start_date_time')) has-error @endif" data-date="{{ Carbon\Carbon::today()->format('Y-m-d') }}T15:25:00Z">
                                            <span class="input-group-text date_default" id="basic-addon1">
                                                <i class="ri-calendar-fill"></i>
                                            </span>
                                            {!! Form::text('start_date_time', date(Config::get('Constant.DEFAULT_DATE_FORMAT'),strtotime(isset($consultations->dtDateTime)?$consultations->dtDateTime:$defaultDt)), array('class' => 'form-control', 'data-provider' => 'flatpickr', 'data-date-format' => Config::get('Constant.DEFAULT_DATE_FORMAT'), 'data-enable-time' => '','maxlength'=>160,'size'=>'16','id'=>'consulation_start_date','autocomplete'=>'off','onpaste'=>'return false')) !!}
                                        </div>
                                        <span class="help-block">{{ $errors->first('start_date_time') }}</span>
                                    </div>
                                </div>
                                @php $defaultDt = (null !== old('end_date_time'))?old('end_date_time'):null; @endphp
                                @if ((isset($consultations->dtEndDateTime)==null))
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
                                    <div class="mb-3 form-md-line-input">
                                        @php if(isset($consultations_highLight->dtEndDateTime) && ($consultations_highLight->dtEndDateTime != $consultations->dtEndDateTime)){
                                        $Class_end_date = " highlitetext";
                                        }else{
                                        $Class_end_date = "";
                                        } @endphp
                                        <div class=" form_meridian_datetime expirydate @if($errors->first('end_date_time')) has-error @endif" data-date="{{ Carbon\Carbon::today()->format('Y-m-d') }}T15:25:00Z" @if ($expChecked_yes==1) style="display:none;" @endif>
                                            <label class="form-label {!! $Class_end_date !!}">{{ trans('consultations::template.common.endDateAndTime') }} <span aria-required="true" class="required"> * </span></label>
                                            
                                            <div class="input-group date">
                                                <span class="input-group-text"><i class="ri-calendar-fill"></i></span>
                                                {!! Form::text('end_date_time', isset($consultations->dtEndDateTime)?date(Config::get('Constant.DEFAULT_DATE_FORMAT'),strtotime($consultations->dtEndDateTime)):$defaultDt, array('class' => 'form-control', 'data-provider' => 'flatpickr', 'data-date-format' => Config::get('Constant.DEFAULT_DATE_FORMAT'), 'data-enable-time' => '','maxlength'=>160,'size'=>'16','id'=>'consulation_end_date','data-exp'=> $expChecked_yes,'data-newvalue','autocomplete'=>'off')) !!}
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
                        
                            <div class="row">
                            <div class="col-md-12">
                                <div class="image_thumb multi_upload_images">
                                    <div class="mb-3">
                                        @php if(isset($consultations_highLight->fkIntDocId) && ($consultations_highLight->fkIntDocId != $consultations->fkIntDocId)){
                                        $Class_file = " highlitetext";
                                        }else{
                                        $Class_file = "";
                                        } @endphp
                                        <label class="form-label {!! $Class_file !!}">Select Documents</label>
                                        <div class="clearfix"></div>
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width:100%;float:left; height:120px;position: relative;">
                                                <img class="img_opacity" src="{{ $CDN_PATH.'resources/images/upload_file.gif' }}" />
                                            </div>
                                            <div class="input-group">
                                                <a class="document_manager multiple-selection" data-multiple="true" onclick="MediaManager.openDocumentManager('consultations');"><span class="fileinput-new"></span></a>
                                                <input class="form-control" type="hidden" id="consultations" name="doc_id" value="{{ isset($consultations->fkIntDocId)?$consultations->fkIntDocId:old('doc_id') }}" />
                                                @php
                                                if (method_exists($MyLibrary, 'GetDocumentFolderID')) {
                                                    if(isset($consultations->fkIntDocId)){
                                                    $folderid = App\Helpers\MyLibrary::GetDocumentFolderID($consultations->fkIntDocId);
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
                            @if(!empty($consultations->fkIntDocId) && isset($consultations->fkIntDocId))
                            @php
                            $docsAray = explode(',', $consultations->fkIntDocId);
                            $docObj   = App\Document::getDocDataByIds($docsAray);
                            @endphp
                            <div class="col-md-12" id="consultations_documents">
                                <div class="multi_image_list" id="multi_document_list">
                                    <ul>
                                        @if(count($docObj) > 0)
                                        @foreach($docObj as $value)
                                        <li id="doc_{{ $value->id }}">
                                            <span title="{{ $value->txtDocumentName }}.{{ $value->varDocumentExtension }}">
                                                <img  src="{{ $CDN_PATH.'assets/images/document_icon.png' }}" alt="Img" />
                                                <a href="javascript:;" onclick="MediaManager.removeDocumentFromGallery('{{ $value->id }}');" class="delect_image" data-dismiss="fileinput"><i class="ri-time-line"></i></a>
                                            </span>
                                        </li>
                                        @endforeach
                                        @endif
                                    </ul>
                                </div>
                            </div>
                            @else
                            <div class="col-md-12" id="consultations_documents"></div>
                            @endif
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3 @if($errors->first('short_description')) has-error @endif form-md-line-input">
                                    @php if(isset($consultations_highLight->varShortDescription) && ($consultations_highLight->varShortDescription != $consultations->varShortDescription)){
                                    $Class_ShortDescription = " highlitetext";
                                    }else{
                                    $Class_ShortDescription = "";
                                    } @endphp
                                    <label class="form-label {!! $Class_ShortDescription !!}">Short Description<span aria-required="true" class="required"> * </span></label>
                                    {!! Form::textarea('short_description', isset($consultations->varShortDescription)?$consultations->varShortDescription:old('short_description'), array('maxlength' => 300,'class' => 'form-control seoField maxlength-handler shortdescspellingcheck','id'=>'varShortDescription','rows'=>'3','placeholder'=>'Short Description')) !!}
                                    <span class="help-block">{{ $errors->first('short_description') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                @php if(isset($consultations_highLight->txtDescription) && ($consultations_highLight->txtDescription != $consultations->txtDescription)){
                                    $Class_Description = " highlitetext";
                                    }else{
                                    $Class_Description = "";
                                } @endphp
                                <label class="form-label {!! $Class_Description !!}">Description</label>
                                @if (Config::get('Constant.DEFAULT_VISUAL') == 'Y')
                                <div id="body-roll">
                                    @php
                                    $sections = [];
                                    @endphp
                                    @if(isset($consultations))
                                    @php
                                    $sections = json_decode($consultations->txtDescription);
                                    @endphp
                                    @endif
                                    <!-- Builder include -->
                                    @php Powerpanel\VisualComposer\Controllers\VisualComposerController::page_section(['sections'=>$sections])@endphp
                                </div>
                                @else
                                <div class="mb-3 @if($errors->first('description')) has-error @endif">
                                    {!! Form::textarea('description', isset($consultations->txtDescription)?$consultations->txtDescription:old('description'), array('placeholder' => trans('consultations::template.common.description'),'class' => 'form-control','id'=>'txtDescription')) !!}
                                    <span class="help-block">{{ $errors->first('description') }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        @if(isset($consultations->intSearchRank))
                            @php $srank = $consultations->intSearchRank; @endphp
                        @else
                            @php $srank = null !== old('search_rank') ? old('search_rank') : 2 ; @endphp
                        @endif

                        @if(isset($consultations_highLight->intSearchRank) && ($consultations_highLight->intSearchRank != $consultations->intSearchRank))
                            @php $Class_intSearchRank = " highlitetext"; @endphp
                        @else
                            @php $Class_intSearchRank = ""; @endphp
                        @endif
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="nopadding">
                                    @include('powerpanel.partials.seoInfo',['form'=>'frmConsultations','inf'=>isset($metaInfo)?$metaInfo:false,'inf_highLight'=> isset($metaInfo_highLight)?$metaInfo_highLight:false,'Class_intSearchRank' => $Class_intSearchRank, 'srank' => $srank])
                                </div>
                            </div>
                        </div>

                        <h3 class="form-section">{{ trans('consultations::template.common.displayinformation') }}</h3>
                        <div class="row mb-3">
                            <div class="col-md-6"style="display: none">
                                <div class="mb-3 @if($errors->first('display_order')) has-error @endif form-md-line-input">
                                    @php
                                    $display_order_attributes = array('class' => 'form-control','maxlength'=>5,'placeholder'=>trans('consultations::template.common.displayorder'),'autocomplete'=>'off');
                                    @endphp
                                    @php if(isset($consultations->intDisplayOrder) && ($consultations->intDisplayOrder != $consultations->intDisplayOrder)){
                                    $Class_displayorder = " highlitetext";
                                    }else{
                                    $Class_displayorder = "";
                                    } @endphp
                                    <label class="form-label {!! $Class_displayorder !!}" for="site_name">{{ trans('department::template.common.displayorder') }} <span aria-required="true" class="required"> * </span></label>
                                    {!! Form::text('display_order', isset($consultations->intDisplayOrder)?$consultations->intDisplayOrder:$total, $display_order_attributes) !!}
                                    <span style="color: red;">
                                        {{ $errors->first('display_order') }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                @if(isset($consultations_highLight->chrPublish) && ($consultations_highLight->chrPublish != $consultations->chrPublish))
                                    @php $Class_chrPublish = " highlitetext"; @endphp
                                @else
                                    @php $Class_chrPublish = ""; @endphp
                                @endif

                                @if(isset($consultations) && $consultations->chrAddStar == 'Y')
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="control-label form-label"> Publish/ Unpublish</label>
                                            <input type="hidden" id="chrMenuDisplay" name="chrMenuDisplay" value="{{ isset($consultations->chrPublish) ? $consultations->chrPublish : '' }}">
                                            <p><b>NOTE:</b> This record is in Approval Request , so it can&#39;t be published/unpublished.</p>
                                        </div>
                                    </div>
                                @elseif(isset($consultations) && $consultations->chrDraft == 'D' && $consultations->chrAddStar != 'Y')
                                    @include('powerpanel.partials.displayInfo',['Class_chrPublish'=>$Class_chrPublish,'display' => (isset($consultations->chrDraft)?$consultations->chrDraft:'D')])
                                @else
                                    @include('powerpanel.partials.displayInfo',['Class_chrPublish'=>$Class_chrPublish,'display' => (isset($consultations->chrPublish)?$consultations->chrPublish:'Y')])
                                @endif
                            </div>
                        </div>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-12">
                                    @if(isset($consultations->fkMainRecord) && $consultations->fkMainRecord != 0)
                                    <button type="submit" name="saveandexit" class="btn btn-primary" value="saveandexit">{!! trans('consultations::template.common.approve') !!}</button>
                                    @else
                                    @if($userIsAdmin)
                                    <button type="submit" name="saveandedit" class="btn btn-primary" value="saveandedit">{!! trans('consultations::template.common.saveandedit') !!}</button>
                                    <button type="submit" name="saveandexit" class="btn btn-primary" value="saveandexit">{!! trans('consultations::template.common.saveandexit') !!}</button>
                                    @else
                                    @if((isset($chrNeedAddPermission) && $chrNeedAddPermission == 'N') && (isset($charNeedApproval) && $charNeedApproval == 'N'))
                                    <button type="submit" name="saveandexit" class="btn btn-primary" value="saveandexit">{!! trans('consultations::template.common.saveandexit') !!}</button>
                                    @else
                                    <button type="submit" name="saveandexit" class="btn btn-primary" value="approvesaveandexit">{!! trans('consultations::template.common.approvesaveandexit') !!}</button>
                                    @endif
                                    @endif
                                    @endif
                                    <a style="display: none" class="btn btn-danger" href="{{ url('powerpanel/consultations') }}">{{ trans('consultations::template.common.cancel') }}</a>
                                    @if(isset($consultations) && !empty($consultations) && $userIsAdmin)
                                    &nbsp;<a class="btn btn-primary" title="Preview" onClick="generatePreview('{{url('/previewpage?url='.(App\Helpers\MyLibrary::getFrontUri('consultations')['uri']))}}');">Preview</a>
                                    @endif
                                </div>
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
                    var seoFormId = 'frmConsultations';
                    var user_action = "{{ isset($consultations)?'edit':'add' }}";
                    var moduleAlias = "{{ App\Helpers\MyLibrary::getFrontUri('consultations')['moduleAlias'] }}";
                    var preview_add_route = '{!! route("powerpanel.consultations.addpreview") !!}';
                    var previewForm = $('#frmConsultations');
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
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-taginput/bootstrap-tagsinput.min.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/custom.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/custom-alias/alias-generator.js' }}" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/seo-generator/seo-info-generator.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/consultations/consultation_validations.js' }}" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS -->
 @if (Config::get('Constant.DEFAULT_VISUAL') == 'Y')
  @php Powerpanel\VisualComposer\Controllers\VisualComposerController::get_builder_css_js()@endphp
  @endif
@endsection