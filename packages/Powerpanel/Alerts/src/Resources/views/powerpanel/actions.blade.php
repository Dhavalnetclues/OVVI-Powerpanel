@section('css')
<link href="{{ $CDN_PATH.'resources/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css' }}" rel="stylesheet" type="text/css" />
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
            {!! Form::open(['method' => 'post','enctype' => 'multipart/form-data','id'=>'frmAlerts']) !!}
                <div class="card">
                    <div class="card-body p-30">
                        {!! Form::hidden('fkMainRecord', isset($alerts->fkMainRecord)?$alerts->fkMainRecord:old('fkMainRecord')) !!}
                        @if(isset($alerts))
                            <div class="row pagetitle-heading mb-3">
                                <div class="col-sm-11 col-11">
                                    <h4 class="page-title fw-semibold m-0">{{ $breadcrumb['inner_title']}}</h4>
                                </div>
                                <div class="col-sm-1 col-1 lock-link">
                                    @if (File::exists(base_path() . '/resources/views/powerpanel/partials/lockedpage.blade.php') != null)
                                    @include('powerpanel.partials.lockedpage',['pagedata'=>$alerts])
                                    @endif
                                </div>
                            </div>
                        @endif
                        <div class="row">
                            {{-- Sector type --}}
                            <div class="col-lg-6 col-sm-12">
                                <div class="@if($errors->first('sector')) has-error @endif form-md-line-input cm-floating">
                                    @if(isset($alertsHighLight->varSector) && ($alertsHighLight->varSector != $alerts->varSector))
                                    @php $Class_varSector = " highlitetext"; @endphp
                                    @else
                                    @php $Class_varSector = ""; @endphp
                                    @endif
                                    <label class="form-label {{ $Class_varSector }}" for="site_name">Select Sector Type </label>
                                    <select class="form-control" name="sector" id="sector" data-choices>
                                        <option value="">Select Sector Type</option>
                                        @foreach($sector as $keySector => $ValueSector)
                                        @php $permissionName = 'alerts-list' @endphp
                                        @php $selected = ''; @endphp
                                        @if(isset($alerts->varSector))
                                        @if($keySector == $alerts->varSector)
                                        @php $selected = 'selected';  @endphp
                                        @endif
                                        @endif
                                        <option value="{{$keySector}}" {{ $selected }}>{{ ($ValueSector == "alerts") ? 'Select Sector Type' : $ValueSector }}</option>
                                        @endforeach
                                    </select>
                                    <span class="help-block">{{ $errors->first('sector') }}</span>
                                </div>
                            </div>
                            {{-- title --}}
                            <div class="col-lg-6 col-sm-12">
                                @if(isset($alertsHighLight->varTitle) && ($alertsHighLight->varTitle != $alerts->varTitle))
                                @php $Class_title = " highlitetext"; @endphp
                                @else
                                @php $Class_title = ""; @endphp
                                @endif
                                <div class="{{ $errors->has('title') ? ' has-error' : '' }} form-md-line-input cm-floating">
                                    <label class="form-label {{ $Class_title }}" for="title">{!! trans('alerts::template.common.title') !!} <span aria-required="true" class="required"> * </span></label>
                                    {!! Form::text('title', isset($alerts->varTitle)?$alerts->varTitle:old('title'), array('maxlength' => 150,'class' => 'form-control input-sm maxlength-handler titlespellingcheck', 'data-url' => 'powerpanel/alerts','id' => 'title','autocomplete'=>'off')) !!}
                                    <span style="color:#e73d4a">{{ $errors->first('title') }}</span>
                                </div>
                            </div>
                            {{-- Modules --}}
                            <div class="col-lg-6 col-sm-12 cm-floating" id="pages">
                                @if(isset($alertsHighLight->fkModuleId) && ($alertsHighLight->fkModuleId != $alerts->fkModuleId))
                                @php $Class_fkModuleId = " highlitetext"; @endphp
                                @else
                                @php $Class_fkModuleId = ""; @endphp
                                @endif
                                <label class="form-label {{ $Class_fkModuleId }}" for="pages">{!! trans('alerts::template.common.selectmodule') !!} <span aria-required="true" class="required"> * </span></label>
                                <select class="form-control" name="modules" id="modules" data-choices>
                                    <option value="">{!! trans('alerts::template.common.selectmodule') !!}</option>
                                    @if(count($modules) > 0)
                                    @foreach ($modules as $pagedata)
                                    @php
                                    $avoidModules = array('faq','contact-us','sitemap','banksupervision','links-category');
                                    @endphp
                                    @if (ucfirst($pagedata->varTitle)!='Home' && !in_array($pagedata->varModuleName,$avoidModules))
                                    <option data-model="{{ $pagedata->varModelName }}" data-module="{{ $pagedata->varModuleName }}" value="{{ $pagedata->id }}" {{ (isset($alerts->fkModuleId) && $pagedata->id == $alerts->fkModuleId) || $pagedata->id == old('modules')? 'selected' : '' }}>{{ $pagedata->varTitle }}</option>
                                    @endif
                                    @endforeach
                                    @endif
                                </select>
                                <span style="color:#e73d4a">{{ $errors->first('modules') }}</span>
                            </div>
                            {{-- Select Page --}}
                            <div class="col-lg-6 col-sm-12 cm-floating" id="records">
                                @if(isset($alertsHighLight->fkIntPageId) && ($alertsHighLight->fkIntPageId != $alerts->fkIntPageId))
                                @php $Class_fkIntPageId = " highlitetext"; @endphp
                                @else
                                @php $Class_fkIntPageId = ""; @endphp
                                @endif
                                <label class="form-label {{ $Class_fkIntPageId }}" for="pages">{!! trans('alerts::template.alertsModule.selectPage') !!}<span aria-required="true" class="required"> * </span></label>
                                <select class="form-control" name="foritem" id="foritem" data-choices>
                                    <option value="">{!! trans('alerts::template.alertsModule.selectPage') !!}</option>
                                </select>
                                <span style="color:#e73d4a">{{ $errors->first('foritem') }}</span>
                            </div>
                            {{-- Short Description --}}
                            <div class="col-md-12">
                                <div class="@if($errors->first('short_description')) has-error @endif form-md-line-input cm-floating">
                                    @php if(isset($alertsHighLight->varShortDescription) && ($alertsHighLight->varShortDescription != $alerts->varShortDescription)){
                                    $Class_ShortDescription = " highlitetext";
                                    }else{
                                    $Class_ShortDescription = "";
                                    } @endphp
                                    <label class="form-label {!! $Class_ShortDescription !!}">Short Description</label>
                                    {!! Form::textarea('short_description', isset($alerts->varShortDescription)?$alerts->varShortDescription:old('short_description'), array('maxlength' => isset($settings->short_desc_length)?$settings->short_desc_length:500,'class' => 'form-control seoField maxlength-handler shortdescspellingcheck','id'=>'varShortDescription','rows'=>'3')) !!}
                                    <span class="help-block">{{ $errors->first('short_description') }}</span>
                                </div>
                            </div>
                            {{-- Select Documents --}}
                            <div class="col-lg-6 col-sm-12">
                                @if(isset($alertsHighLight->fkIntDocId) && ($alertsHighLight->fkIntDocId != $alerts->fkIntDocId))
                                @php $Class_fkIntDocId = " highlitetext"; @endphp
                                @else
                                @php $Class_fkIntDocId = ""; @endphp
                                @endif
                                <div class="image_thumb multi_upload_images cm-floating">
                                    <label class="form-label {{ $Class_fkIntDocId }}">
                                        Select Documents
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
                                            <a class="document_manager multiple-selection" data-multiple="true" onclick="MediaManager.openDocumentManager('alerts');"><span class="fileinput-new"></span></a>
                                            <input class="form-control" type="hidden" id="alerts" name="doc_id" value="{{ isset($alerts->fkIntDocId)?$alerts->fkIntDocId:old('doc_id') }}" />
                                            @php
                                            if(isset($alerts->fkIntDocId)){
                                            $folderid = App\Helpers\MyLibrary::GetDocumentFolderID($alerts->fkIntDocId);
                                            @endphp
                                            @if(isset($folderid->fk_folder) && $folderid->fk_folder != '0')
                                            <input class="form-control" type="hidden" id="folder_id" name="folder_id" value="{{ $folderid->fk_folder }}" />
                                            @endif
                                            @php
                                            }
                                            @endphp
                                        </div>
                                    </div>
                                    <span class="help-block">{{ $errors->first('doc_id') }}</span>
                                    @if(!empty($alerts->fkIntDocId) && isset($alerts->fkIntDocId))
                                    @php
                                    $docsAray = explode(',', $alerts->fkIntDocId);
                                    $docObj = App\Document::getDocDataByIds($docsAray);
                                    @endphp
                                    <div class="col-md-12" id="alerts_documents">
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
                                    <div class="col-md-12" id="alerts_documents"></div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                {{-- Alert Type --}}
                                @if(isset($alerts->intAlertType))
                                @php $srank = $alerts->intAlertType; @endphp
                                @else
                                @php
                                $srank = null !== old('alert_type') ? old('alert_type') : 2 ;
                                @endphp
                                @endif
                                @if(isset($alertsHighLight->intAlertType) && ($alertsHighLight->intAlertType != $alerts->intAlertType))
                                @php $Class_intAlertType = " highlitetext"; @endphp
                                @else
                                @php $Class_intAlertType = ""; @endphp
                                @endif
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <label class="{{ $Class_intAlertType }} form-label">Alert Type</label>
                                        <div class="md-radio-inline">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" value="1" name="alert_type" id="yes_radio_1" @if ($srank == '1') checked @endif>
                                                <label for="yes_radio_1" id="yes-lbl_1">High</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" value="2" name="alert_type" id="maybe_radio_1" @if ($srank == '2') checked @endif>
                                                <label for="maybe_radio_1" id="maybe-lbl_1">Medium</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" value="3" name="alert_type" id="no_radio_1" @if ($srank == '3') checked @endif>
                                                <label for="no_radio_1" id="no-lbl_1">Low</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- Search Ranking --}}
                                @if(Config::get('Constant.CHRSearchRank') == 'Y')
                                @if(isset($alerts->intSearchRank))
                                @php $srank = $alerts->intSearchRank; @endphp
                                @else
                                @php
                                $srank = null !== old('search_rank') ? old('search_rank') : 2;
                                @endphp
                                @endif
                                @if(isset($alertsHighLight->intSearchRank) && ($alertsHighLight->intSearchRank != $alerts->intSearchRank))
                                @php $Class_intSearchRank = " highlitetext"; @endphp
                                @else
                                @php $Class_intSearchRank = ""; @endphp
                                @endif
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <label class="{{ $Class_intSearchRank }} form-label">Search Ranking</label>
                                        <a href="javascript:void(0);" data-bs-toggle="tooltip" class="config" data-bs-placement="bottom" data-bs-content="{{ trans('alerts::template.common.SearchEntityTools') }}" title="{{ trans('alerts::template.common.SearchEntityTools') }}"><i class="fa fa-question"></i></a>
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
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body p-30">
                        <div class="row">
                            {{-- Display Information --}}
                            <div class="col-lg-6 col-sm-12">
                                <h4 class="form-section mb-3">{!! trans('alerts::template.common.displayinformation') !!}</h4>
                                @php
                                $display_order_attributes = array('class' => 'form-control','autocomplete'=>'off','maxlength'=>'5');
                                @endphp
                                <div class="@if($errors->first('display_order')) has-error @endif form-md-line-input cm-floating">
                                    @if(isset($alertsHighLight->intDisplayOrder) && ($alertsHighLight->intDisplayOrder != $alerts->intDisplayOrder))
                                    @php $Class_intDisplayOrder = " highlitetext"; @endphp
                                    @else
                                    @php $Class_intDisplayOrder = ""; @endphp
                                    @endif
                                    <label class="form-label {{ $Class_intDisplayOrder }}" for="display_order">{!! trans('alerts::template.common.displayorder') !!} <span aria-required="true" class="required"> * </span></label>
                                    {!! Form::text('display_order',isset($alerts->intDisplayOrder)?$alerts->intDisplayOrder:1, $display_order_attributes) !!}
                                    <span class="help-block"><strong>{{ $errors->first('display_order') }}</strong></span>
                                    <div class="publish-info mt-3">
                                        @if(isset($alertsHighLight->chrPublish) && ($alertsHighLight->chrPublish != $alerts->chrPublish))
                                        @php $Class_chrPublish = " highlitetext"; @endphp
                                        @else
                                        @php $Class_chrPublish = ""; @endphp
                                        @endif
                                        @if((isset($alerts) && $alerts->chrDraft == 'D'))
                                        @include('powerpanel.partials.displayInfo',['Class_chrPublish'=>$Class_chrPublish,'display' => (isset($alerts->chrDraft)?$alerts->chrDraft:'D')])
                                        @else
                                        @include('powerpanel.partials.displayInfo',['Class_chrPublish'=>$Class_chrPublish,'display' => (isset($alerts->chrPublish)?$alerts->chrPublish:'Y')])
                                        @endif
                                    </div>
                                </div>
                            </div>
                            {{-- Content Scheduling --}}
                            <div class="col-lg-6 col-sm-12">
                                @php $defaultDt = (null !== old('start_date_time'))?old('start_date_time'):date(Config::get('Constant.DEFAULT_DATE_FORMAT').' '.Config::get('Constant.DEFAULT_TIME_FORMAT')); @endphp
                                <h4 class="form-section mb-3">{{ trans('alerts::template.common.ContentScheduling') }}</h4>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-md-line-input cm-floating">
                                            @php if(isset($alertsHighLight->dtDateTime) && ($alertsHighLight->dtDateTime != $alerts->dtDateTime)){
                                            $Class_date = " highlitetext";
                                            }else{
                                            $Class_date = "";
                                            } @endphp
                                            <label class="form-label {!! $Class_date !!}">{{ trans('alerts::template.common.startDateAndTime') }}<span aria-required="true" class="required"> * </span></label>
                                            <div class="input-group date form_meridian_datetime @if($errors->first('start_date_time')) has-error @endif" data-date="{{ Carbon\Carbon::today()->format('Y-m-d') }}T15:25:00Z">
                                                {!! Form::text('start_date_time', date(Config::get('Constant.DEFAULT_DATE_FORMAT').' '.Config::get('Constant.DEFAULT_TIME_FORMAT'),strtotime(isset($alerts->dtDateTime)?$alerts->dtDateTime:$defaultDt)), array('class' => 'form-control', 'data-provider' => 'flatpickr', 'data-date-format' => Config::get('Constant.DEFAULT_DATE_FORMAT'), 'data-enable-time' => '','maxlength'=>160,'size'=>'16','id'=>'start_date_time','autocomplete'=>'off','onkeypress'=>"javascript: return KeycheckOnlyDate(event);",'onpaste'=>'return false')) !!}
                                            </div>
                                            <span class="help-block">{{ $errors->first('start_date_time') }}</span>
                                        </div>
                                    </div>
                                    @php $defaultDt = (null !== old('end_date_time'))?old('end_date_time'):null; @endphp
                                    @if ((isset($alerts->dtEndDateTime)==null))
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
                                            @php if(isset($alertsHighLight->varTitle) && ($alertsHighLight->dtEndDateTime != $alerts->dtEndDateTime)){
                                            $Class_end_date = " highlitetext";
                                            }else{
                                            $Class_end_date = "";
                                            } @endphp
                                            <div class="cm-floating form_meridian_datetime expirydate @if($errors->first('end_date_time')) has-error @endif" data-date="{{ Carbon\Carbon::today()->format('Y-m-d') }}T15:25:00Z" @if ($expChecked_yes==1) style="display:none;" @endif>
                                                <label class="form-label {!! $Class_end_date !!}">{{ trans('alerts::template.common.endDateAndTime') }}<span aria-required="true" class="required"> * </span></label>
                                                <div class="input-group date">
                                                    {!! Form::text('end_date_time', isset($alerts->dtEndDateTime)?date(Config::get('Constant.DEFAULT_DATE_FORMAT').' '.Config::get('Constant.DEFAULT_TIME_FORMAT'), strtotime($alerts->dtEndDateTime)):$defaultDt, array('class' => 'form-control', 'data-provider' => 'flatpickr', 'data-date-format' => Config::get('Constant.DEFAULT_DATE_FORMAT'), 'data-enable-time' => '','maxlength'=>160,'size'=>'16','id'=>'end_date_time','data-exp'=> $expChecked_yes,'data-newvalue','autocomplete'=>'off','onkeypress'=>"javascript: return KeycheckOnlyDate(event);",'onpaste'=>'return false')) !!}
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
                        </div>
                        {{-- Form Action --}}
                        <div class="col-md-12">
                            <div class="form-actions">
                                @if(isset($alerts->fkMainRecord) && $alerts->fkMainRecord != 0)
                                <button type="submit" name="saveandexit" formmethod="post" class="btn btn-primary bg-gradient waves-effect waves-light btn-label me-1" value="saveandexit">
                                    <div class="flex-shrink-0">
                                        <i class="ri-check-line label-icon align-middle fs-20 me-2"></i>
                                    </div>
                                    {!! trans('alerts::template.common.approve') !!}
                                </button>
                                @else
                                @if($userIsAdmin)
                                <button type="submit" name="saveandedit" formmethod="post" class="btn btn-primary bg-gradient waves-effect waves-light btn-label me-1" value="saveandedit">
                                    <div class="flex-shrink-0">
                                        <i class="ri-save-line label-icon align-middle fs-20 me-2"></i>
                                    </div>
                                    {!! trans('alerts::template.common.saveandedit') !!}
                                </button>
                                <button type="submit" name="saveandexit" formmethod="post" class="btn btn-secondary bg-gradient waves-effect waves-light btn-label me-1" value="saveandexit">
                                    <div class="flex-shrink-0">
                                        <i class="ri-save-3-line label-icon align-middle fs-20 me-2"></i>
                                    </div>
                                    {!! trans('alerts::template.common.saveandexit') !!}
                                </button>
                                @else
                                @if((isset($chrNeedAddPermission) && $chrNeedAddPermission == 'N') && (isset($charNeedApproval) && $charNeedApproval == 'N'))
                                <button type="submit" name="saveandexit" formmethod="post" class="btn btn-secondary bg-gradient waves-effect waves-light btn-label me-1" value="saveandexit">
                                    <div class="flex-shrink-0">
                                        <i class="ri-save-3-line label-icon align-middle fs-20 me-2"></i>
                                    </div>
                                    {!! trans('alerts::template.common.saveandexit') !!}
                                </button>
                                @else
                                <button type="submit" name="saveandexit" formmethod="post" class="btn btn-primary bg-gradient waves-effect waves-light btn-label me-1" value="approvesaveandexit">
                                    <div class="flex-shrink-0">
                                        <i class="ri-save-line label-icon align-middle fs-20 me-2"></i>
                                    </div>
                                    {!! trans('alerts::template.common.approvesaveandexit') !!}
                                </button>
                                @endif
                                @endif
                                @endif
                                <a class="btn btn-danger bg-gradient waves-effect waves-light btn-label me-1" href="{{ url('powerpanel/alerts') }}">
                                    <div class="flex-shrink-0">
                                        <i class="ri-close-line label-icon align-middle fs-20 me-2"></i>
                                    </div>
                                    {{ trans('alerts::template.common.cancel') }}
                                </a>
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
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js' }}" type="text/javascript"></script>
<script type="text/javascript">
window.site_url = '{!! url("/") !!}';
var selectedRecord = '{{ isset($alerts->fkIntPageId)?$alerts->fkIntPageId:' ' }}';
var user_action = "{{ isset($alerts)?'edit':'add' }}";
</script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/custom.js' }}" type="text/javascript"></script>>
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/alerts/alerts.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/seo-generator/seo-info-generator.js' }}" type="text/javascript"></script>
<script type="text/javascript">
// $('#modules').select2({
//     placeholder: "Select Module",
//     width: '100%'
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