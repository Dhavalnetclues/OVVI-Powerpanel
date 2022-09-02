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
            {!! Form::open(['method' => 'post','id'=>'frmFaqCategory']) !!}
                <div class="card">
                    <div class="card-body p-30 pb-0">
                        @if(isset($faqCategory))
                        <div class="row pagetitle-heading mb-3">
                            <div class="col-sm-11 col-11">
                                <h4 class="page-title fw-semibold m-0">{{ $breadcrumb['inner_title']}}</h4>
                            </div>
                            <div class="col-sm-1 col-1 lock-link">
                                @if (File::exists(base_path() . '/resources/views/powerpanel/partials/lockedpage.blade.php') != null)
                                @include('powerpanel.partials.lockedpage',['pagedata'=>$faqCategory])
                                @endif
                            </div>
                        </div>
                        @endif

                        <div class="row">
                            <!-- Sector type -->
                            <div class="col-md-12">
                                <div class="@if($errors->first('sector')) has-error @endif form-md-line-input cm-floating">
                                    @if(isset($faqCategory_highLight->varSector) && ($faqCategory_highLight->varSector != $faqCategory->varSector))
                                        @php $Class_varSector = " highlitetext"; @endphp
                                    @else
                                        @php $Class_varSector = ""; @endphp
                                    @endif
                                    @if($hasRecords > 0)
                                    @php $disable = 'disabled'; @endphp
                                    @else
                                    @php $disable = ''; @endphp
                                    @endif
                                    @include('shiledcmstheme::powerpanel.partials.sector-dropdown', ['selected_sector' => isset($faqCategory->varSector)?$faqCategory->varSector:'','Class_varSector' => $Class_varSector,'disable' => $disable])
                                    <span class="help-block">{{ $errors->first('sector') }}</span>
                                </div>
                                @if(isset($disable) && !empty($disable))
                                <input type="hidden" name="sector" value="{{isset($faqCategory->varSector)?$faqCategory->varSector:''}}" />
                                @endif
                            </div>
                            {{-- Title --}}
                            <div class="col-md-12">
                                <div class="@if($errors->first('title')) has-error @endif form-md-line-input cm-floating">
                                    @php if(isset($faqCategory_highLight->varTitle) && ($faqCategory_highLight->varTitle != $faqCategory->varTitle)){
                                    $Class_title = " highlitetext";
                                    }else{
                                    $Class_title = "";
                                    } @endphp
                                    <label class="form-label {!! $Class_title !!}" for="site_name">{{ trans('faq-category::template.common.name') }} <span aria-required="true" class="required"> * </span></label>
                                    {!! Form::text('title', isset($faqCategory->varTitle)?$faqCategory->varTitle:old('title'), array('maxlength' => 150,'id'=>'title', 'class' => 'form-control hasAlias seoField maxlength-handler titlespellingcheck','autocomplete'=>'off','data-url' => 'powerpanel/faq-category')) !!}
                                    <span class="help-block">{{ $errors->first('title') }}</span>
                                    <!-- code for alias -->
                                    <div class="link-url mt-2 d-none">
                                        {!! Form::hidden(null, null, array('class' => 'hasAlias','data-url' => 'powerpanel/faq-category')) !!}
                                        {!! Form::hidden('alias', isset($faqCategory->alias->varAlias)?$faqCategory->alias->varAlias:old('alias'), array('class' => 'aliasField')) !!}
                                        {!! Form::hidden('oldAlias', isset($faqCategory->alias->varAlias)?$faqCategory->alias->varAlias:old('alias')) !!}
                                        {!! Form::hidden('fkMainRecord', isset($faqCategory->fkMainRecord)?$faqCategory->fkMainRecord:old('fkMainRecord')) !!}
                                        {!! Form::hidden('previewId') !!}
                                        <div class="alias-group {{!isset($faqCategory)?'hide':''}} ">
                                            <label class="form-label" for="{{ trans('template.url') }}">{{ trans('faq-category::template.common.url') }} :</label>
                                            @if(isset($faqCategory->alias->varAlias) && !$userIsAdmin)
                                            @if(isset($faqCategory->alias->varAlias))
                                            @php
                                            $aurl = App\Helpers\MyLibrary::getFrontUri('faq-category')['uri'];
                                            @endphp
                                            {{url($aurl.'/'.$faqCategory->alias->varAlias)}}
                                            @endif
                                            @else
                                            @if(auth()->user()->can('faq-category-create'))
                                            <a href="javascript:void(0);" class="alias">{!! url("/") !!}</a>
                                            <a href="javascript:void(0);" class="editAlias" title="{{ trans('faq-category::template.common.edit') }}">
                                                <i class="ri-pencil-line"></i>
                                            </a>
                                            <a class="without_bg_icon openLink" title="Open Link" onClick="generatePreview('{{url('/previewpage?url='.(App\Helpers\MyLibrary::getFrontUri('faq-category')['uri']))}}');">
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
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body p-30">
                        <div class="row">
                            {{-- Display Information --}}
                            <div class="col-md-12">
                                <h4 class="form-section mb-3">{!! trans('faq-category::template.common.displayinformation') !!}</h4>
                                @php
                                $display_order_attributes = array('class' => 'form-control','maxlength'=>5,'placeholder'=>trans('faq-category::template.common.displayorder'),'autocomplete'=>'off');
                                @endphp
                                @if(isset($faqCategory_highLight->intDisplayOrder) && ($faqCategory_highLight->intDisplayOrder != $faqCategory->intDisplayOrder))
                                @php $Class_intDisplayOrder = " highlitetext"; @endphp
                                @else
                                @php $Class_intDisplayOrder = ""; @endphp
                                @endif
                                <div class="@if($errors->first('order')) has-error @endif form-md-line-input cm-floating">
                                    <label class="form-label {{ $Class_intDisplayOrder }}" for="site_name">{{ trans('faq-category::template.common.displayorder') }} <span aria-required="true" class="required"> * </span></label>
                                    {!! Form::text('order', isset($faqCategory->intDisplayOrder)?$faqCategory->intDisplayOrder:'1', $display_order_attributes) !!}
                                    <span style="color: red;">{{ $errors->first('order') }}</span>
                                    <div class="publish-info mt-3">
                                        @if($hasRecords==0)
                                            @if(isset($faqCategory_highLight->chrPublish) && ($faqCategory_highLight->chrPublish != $faqCategory->chrPublish))
                                                @php $Class_chrPublish = " highlitetext"; @endphp
                                            @else
                                                @php $Class_chrPublish = ""; @endphp
                                            @endif
        
                                            @if(isset($faqCategory) && $faqCategory->chrAddStar == 'Y')
                                                <label class="form-label"> Publish/ Unpublish</label>
                                                <input type="hidden" id="chrMenuDisplay" name="chrMenuDisplay" value="{{ isset($faqCategory->chrPublish) ? $faqCategory->chrPublish : '' }}">
                                                <p><b>NOTE:</b> This record is in Approval Request , so it can&#39;t be published/unpublished.</p>
                                            @elseif(isset($faqCategory) && $faqCategory->chrDraft == 'D' && $faqCategory->chrAddStar != 'Y')
                                                @include('powerpanel.partials.displayInfo',['Class_chrPublish'=>$Class_chrPublish,'display' => (isset($faqCategory->chrDraft)?$faqCategory->chrDraft:'D')])
                                            @else
                                                @include('powerpanel.partials.displayInfo',['Class_chrPublish'=>$Class_chrPublish,'display' => (isset($faqCategory->chrPublish)?$faqCategory->chrPublish:'Y')])
                                            @endif
                                        @else
                                            <label class="control-label form-label"> Publish/ Unpublish</label>
                                            @if($hasRecords > 0)
                                            <input type="hidden" id="chrMenuDisplay" name="chrMenuDisplay" value="{{ $faqCategory->chrPublish }}">
                                            <p><b>NOTE:</b> This category is selected in {{ trans("faq-category::template.sidebar.faq") }}, so it can&#39;t be published/unpublished.</p>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                            {{-- Form Action --}}
                            <div class="col-md-12">
                                <div class="form-actions">
                                    @if(isset($faqCategory->fkMainRecord) && $faqCategory->fkMainRecord != 0)
                                        <button type="submit" name="saveandexit" formmethod="post" class="btn btn-primary bg-gradient waves-effect waves-light btn-label me-1" value="saveandexit">
                                            <div class="flex-shrink-0">
                                                <i class="ri-check-line label-icon align-middle fs-20 me-2"></i>
                                            </div>
                                            {!! trans('faq-category::template.common.approve') !!}
                                        </button>
                                    @else
                                        @if($userIsAdmin)
                                            <button type="submit" name="saveandedit" formmethod="post" class="btn btn-primary bg-gradient waves-effect waves-light btn-label me-1" value="saveandedit">
                                                <div class="flex-shrink-0">
                                                    <i class="ri-save-line label-icon align-middle fs-20 me-2"></i>
                                                </div>
                                                {!! trans('faq-category::template.common.saveandedit') !!}
                                            </button>
                                            <button type="submit" name="saveandexit" formmethod="post" class="btn btn-secondary bg-gradient waves-effect waves-light btn-label me-1" value="saveandexit">
                                                <div class="flex-shrink-0">
                                                    <i class="ri-save-3-line label-icon align-middle fs-20 me-2"></i>
                                                </div>
                                                {!! trans('faq-category::template.common.saveandexit') !!}
                                            </button>
                                        @else
                                            @if((isset($chrNeedAddPermission) && $chrNeedAddPermission == 'N') && (isset($charNeedApproval) && $charNeedApproval == 'N'))
                                                <button type="submit" name="saveandexit" formmethod="post" class="btn btn-secondary bg-gradient waves-effect waves-light btn-label me-1" value="saveandexit">
                                                    <div class="flex-shrink-0">
                                                        <i class="ri-save-3-line label-icon align-middle fs-20 me-2"></i>
                                                    </div>
                                                    {!! trans('faq-category::template.common.saveandexit') !!}
                                                </button>
                                            @else
                                                <button type="submit" name="saveandexit" formmethod="post" class="btn btn-primary bg-gradient waves-effect waves-light btn-label me-1" value="approvesaveandexit">
                                                    <div class="flex-shrink-0">
                                                        <i class="ri-save-line label-icon align-middle fs-20 me-2"></i>
                                                    </div>
                                                    {!! trans('faq-category::template.common.approvesaveandexit') !!}
                                                </button>
                                            @endif
                                        @endif
                                    @endif
                                    <a class="btn btn-danger bg-gradient waves-effect waves-light btn-label me-1" href="{{ url('powerpanel/faq-category') }}">
                                        <div class="flex-shrink-0">
                                            <i class="ri-close-line label-icon align-middle fs-20 me-2"></i>
                                        </div>
                                        {{ trans('faq-category::template.common.cancel') }}
                                    </a>
                                    @if(isset($faqCategory) && $userIsAdmin)
                                        <a style="display: none" class="btn btn-info bg-gradient waves-effect waves-light btn-label me-1" title="Preview" onClick="generatePreview('{{url('/previewpage?url='.(App\Helpers\MyLibrary::getFrontUri('faq-category')['uri']))}}');">
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

                {{-- Hide Fields  --}}
                <div class="card d-none">
                    <div class="card-body p-30">
                        <div class="row">
                            {{-- Page Content --}}
                            <div class="col-md-12">
                                @if (Config::get('Constant.DEFAULT_VISUAL') == 'Y')
                                <div id="body-roll">
                                    @php $sections = []; @endphp
                                    @if(isset($faqCategory))
                                        @php $sections = json_decode($faqCategory->txtDescription); @endphp
                                    @endif
                                    <!-- Builder include -->
                                    @php Powerpanel\VisualComposer\Controllers\VisualComposerController::page_section(['sections'=>$sections])@endphp
                                </div>
                                @else
                                <div class="@if($errors->first('description')) has-error @endif">
                                    @php if(isset($faqCategory_highLight->txtDescription) && ($faqCategory_highLight->txtDescription != $faqCategory->txtDescription)){
                                    $Class_Description = " highlitetext";
                                    }else{
                                    $Class_Description = "";
                                    } @endphp
                                    <h4 class="form-section mb-3 form-label {!! $Class_Description !!}">{{ trans('faq-category::template.common.description') }}</h4>
                                    {!! Form::textarea('description', isset($faqCategory->txtDescription)?$faqCategory->txtDescription:old('description'), array('placeholder' => trans('faq-category::template.common.description'),'class' => 'form-control','id'=>'txtDescription')) !!}
                                    <span class="help-block">{{ $errors->first('description') }}</span>
                                </div>
                                @endif
                            </div>
                            <div class="col-md-12">
                                @if(isset($faqCategory->intSearchRank))
                                    @php $srank = $faqCategory->intSearchRank; @endphp
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
                                        <strong>Note: </strong> {{ trans('faq-category::template.common.SearchEntityTools') }}
                                    </div>
                                @endif
                            </div>
                            {{-- SEO Information --}}
                            <div class="col-md-12">
                                <div class="nopadding">
                                    @include('powerpanel.partials.seoInfo',['form'=>'frmFaqCategory','inf'=>isset($metaInfo)?$metaInfo:false,'inf_highLight'=> isset($metaInfo_highLight)?$metaInfo_highLight:false])
                                </div>
                            </div>
                            {{-- Content Scheduling --}}
                            <div class="col-lg-6 col-sm-12 d-none">
                                <div class="row">
                                    <h4 class="form-section mb-3">{{ trans('faq-category::template.common.ContentScheduling') }}</h4>
                                    <div class="col-md-6">
                                        @php $defaultDt = (null !== old('start_date_time'))?old('start_date_time'):date(Config::get('Constant.DEFAULT_DATE_FORMAT').' '.Config::get('Constant.DEFAULT_TIME_FORMAT')); @endphp
                                        <div class="form-md-line-input cm-floating">
                                            @php if(isset($faqCategory_highLight->dtDateTime) && ($faqCategory_highLight->dtDateTime != $faqCategory->dtDateTime)){
                                            $Class_date = " highlitetext";
                                            }else{
                                            $Class_date = "";
                                            } @endphp
                                            <label class="control-label form-label {!! $Class_date !!}">{{ trans('faq-category::template.common.startDateAndTime') }}<span aria-required="true" class="required"> * </span></label>
                                            <div class="input-group date form_meridian_datetime @if($errors->first('start_date_time')) has-error @endif" data-date="{{ Carbon\Carbon::today()->format('Y-m-d') }}T15:25:00Z">
                                                {{-- <span class="input-group-text date_default" id="basic-addon1"> --}}
                                                {!! Form::text('start_date_time', date(Config::get('Constant.DEFAULT_DATE_FORMAT').' '.Config::get('Constant.DEFAULT_TIME_FORMAT'),strtotime(isset($faqCategory->dtDateTime)?$faqCategory->dtDateTime:$defaultDt)), array('class' => 'form-control', 'data-provider' => 'flatpickr', 'data-date-format' => Config::get('Constant.DEFAULT_DATE_FORMAT'), 'data-enable-time' => '','maxlength'=>160,'size'=>'16','id'=>'start_date_time','autocomplete'=>'off','onkeypress'=>"javascript: return KeycheckOnlyDate(event);",'onpaste'=>'return false')) !!}
                                            </div>
                                            <span class="help-block">{{ $errors->first('start_date_time') }}</span>
                                        </div>
                                    </div>
                                    @php $defaultDt = (null !== old('end_date_time'))?old('end_date_time'):null; @endphp
                                    @if ((isset($faqCategory->dtEndDateTime)==null))
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
                                            @php if(isset($faqCategory_highLight->varTitle) && ($faqCategory_highLight->dtEndDateTime != $faqCategory->dtEndDateTime)){
                                            $Class_end_date = " highlitetext";
                                            }else{
                                            $Class_end_date = "";
                                            } @endphp
                                            <div class="cm-floating form_meridian_datetime expirydate @if($errors->first('end_date_time')) has-error @endif" data-date="{{ Carbon\Carbon::today()->format('Y-m-d') }}T15:25:00Z" @if ($expChecked_yes==1) style="display:none;" @endif>
                                                <label class="form-label {!! $Class_end_date !!}">{{ trans('faq-category::template.common.endDateAndTime') }} <span aria-required="true" class="required"> * </span></label>
                                                <div class="input-group date">
                                                    {{-- <span class="input-group-text"><i class="ri-calendar-fill"></i></span> --}}
                                                    {!! Form::text('end_date_time', isset($faqCategory->dtEndDateTime)?date(Config::get('Constant.DEFAULT_DATE_FORMAT').' '.Config::get('Constant.DEFAULT_TIME_FORMAT'),strtotime($faqCategory->dtEndDateTime)):$defaultDt, array('class' => 'form-control', 'data-provider' => 'flatpickr', 'data-date-format' => Config::get('Constant.DEFAULT_DATE_FORMAT'), 'data-enable-time' => '','maxlength'=>160,'size'=>'16','id'=>'end_date_time','data-exp'=> $expChecked_yes,'data-newvalue','autocomplete'=>'off','onkeypress'=>"javascript: return KeycheckOnlyDate(event);",'onpaste'=>'return false')) !!}
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
                    </div>
                </div>
            {!! Form::close() !!}
        </div>
    </div>
</div><!--end row-->


@if (Config::get('Constant.DEFAULT_VISUAL') == 'Y')
        {{--@include('powerpanel.partials.dialog-maker') --}}
        @php Powerpanel\VisualComposer\Controllers\VisualComposerController::get_dialog_maker()@endphp
    @endif
    @if (Config::get('Constant.DEFAULT_VISUAL') == 'Y')
        @php Powerpanel\VisualComposer\Controllers\VisualComposerController::get_visual_checkEditor()@endphp
    @else
        @include('powerpanel.partials.ckeditor',['config'=>'docsConfig'])
    @endif
@endsection
@section('scripts')
<script type="text/javascript">
            window.site_url = '{!! url("/") !!}';
            var seoFormId = 'frmFaqCategory';
            var user_action = "{{ isset($faqCategory)?'edit':'add' }}";
            var moduleAlias = "{{ App\Helpers\MyLibrary::getFrontUri('faq-category')['moduleAlias'] }}";
            var preview_add_route = '{!! route("powerpanel.faq-category.addpreview") !!}';
            var previewForm = $('#frmFaqCategory');
            var isDetailPage = true;
            function generate_seocontent1(formname) {
            var Meta_Title = document.getElementById('title').value + "";
            var Meta_Description = document.getElementById('title').value + "";
                   
                    var Meta_Keyword = "";
                    $('#varMetaTitle').val(Meta_Title);
//								$('#varMetaKeyword').val(Meta_Keyword);
                    $('#varMetaDescription').val(Meta_Description);
                    $('#meta_title').html(Meta_Title);
                    $('#meta_description').html(Meta_Description);
            }
</script>
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js' }}" type="text/javascript"></script>
<script type="text/javascript">
            function OpenPassword(val) {
            if (val == 'PP') {
            $("#passid").show();
            } else {
            $("#passid").hide();
            }
            }
</script>
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-taginput/bootstrap-tagsinput.min.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/custom.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/custom-alias/alias-generator.js' }}" type="text/javascript"></script>
<!-- BEGIN CORE PLUGINS -->
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap/js/bootstrap.min.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js' }}" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/seo-generator/seo-info-generator.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/faqcategory/faq_category_validations.js' }}" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS -->
 @if (Config::get('Constant.DEFAULT_VISUAL') == 'Y')
        @php Powerpanel\VisualComposer\Controllers\VisualComposerController::get_builder_css_js()@endphp
    @endif
@endsection