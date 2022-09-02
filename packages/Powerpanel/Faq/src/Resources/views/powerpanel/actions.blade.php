@section('css')
@endsection
@extends('powerpanel.layouts.app')
@section('title')
{{Config::get('Constant.SITE_NAME')}} - PowerPanel
@endsection
@section('content')
@php $settings = json_decode(Config::get("Constant.MODULE.SETTINGS")); @endphp

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
            {!! Form::open(['method' => 'post','id'=>'frmFaq']) !!}
                <div class="card">
                    <div class="card-body p-30 pb-0">
                        @if(isset($faq))
                            <div class="row pagetitle-heading mb-4">
                                <div class="col-sm-11 col-11">
                                    <h4 class="page-title fw-semibold m-0">{{ $breadcrumb['inner_title']}}</h4>
                                </div>
                                <div class="col-sm-1 col-1 lock-link">
                                    @if (File::exists(base_path() . '/resources/views/powerpanel/partials/lockedpage.blade.php') != null)
                                    @include('powerpanel.partials.lockedpage',['pagedata'=>$faq])
                                    @endif
                                </div>
                            </div>
                        @endif
                        <div class="row">
                            {!! Form::hidden('fkMainRecord', isset($faq->fkMainRecord)?$faq->fkMainRecord:old('fkMainRecord')) !!}
                            {{-- Sector type --}}
                            <div class="col-lg-6 col-sm-12">
                                <div class="@if($errors->first('sector')) has-error @endif form-md-line-input cm-floating">
                                    @if(isset($faq_highLight->varSector) && ($faq_highLight->varSector != $faq->varSector))
                                        @php $Class_varSector = " highlitetext"; @endphp
                                    @else
                                        @php $Class_varSector = ""; @endphp
                                    @endif
                                    @include('shiledcmstheme::powerpanel.partials.sector-dropdown', ['selected_sector' => isset($faq->varSector)?$faq->varSector:'','Class_varSector' => $Class_varSector])
                                </div>
                            </div>
                            {{-- Select Category --}}
                            <div class="col-lg-6 col-sm-12">
                                <div class="@if($errors->first('tag_line')) has-error @endif form-md-line-input cm-floating">
                                    @php
                                    if(isset($faq_highLight->intFKCategory) && ($faq_highLight->intFKCategory != $faq->intFKCategory)){
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
                            {{-- Title --}}
                            <div class="col-md-12">
                                <div class="@if($errors->first('title')) has-error @endif form-md-line-input cm-floating">
                                    @php if(isset($faq_highLight->varTitle) && ($faq_highLight->varTitle != $faq->varTitle)){
                                    $Class_title = " highlitetext";
                                    }else{
                                    $Class_title = "";
                                    } @endphp
                                    <label class="form-label {!! $Class_title !!}" for="site_name">{{ trans('faq::template.common.title') }} <span aria-required="true" class="required"> * </span></label>
                                    {!! Form::text('title', isset($faq->varTitle) ? $faq->varTitle:old('title'), array('maxlength'=>'250','id'=>'title','class' => 'form-control seoField maxlength-handler titlespellingcheck','autocomplete'=>'off')) !!}
                                    <span class="help-block">{{ $errors->first('title') }}</span>
                                </div>
                            </div>
                            {{-- Description --}}
                            <div class="col-md-12">
                                <div class="@if($errors->first('description')) has-error @endif form-md-line-input cm-floating">
                                    @php if(isset($faq_highLight->txtDescription) && ($faq_highLight->txtDescription != $faq->txtDescription)){
                                    $Class_Description = " highlitetext";
                                    }else{
                                    $Class_Description = "";
                                    } @endphp
                                    <label class="form-label {!! $Class_Description !!}">{{ trans('faq::template.common.description') }} <span aria-required="true" class="required"> * </span></label>
                                    {!! Form::textarea('description', isset($faq->txtDescription)?$faq->txtDescription:old('description'), array('class' => 'form-control','id'=>'txtDescription')) !!}
                                    <span class="help-block">{{ $errors->first('description') }}</span>
                                </div>
                            </div>
                            {{-- Search Rank --}}
                            <div class="col-md-12 d-none">
                                @if(Config::get('Constant.CHRSearchRank') == 'Y')
                                @if(isset($faq->intSearchRank))
                                @php $srank = $faq->intSearchRank; @endphp
                                @else
                                @php
                                $srank = null !== old('search_rank') ? old('search_rank') : 2 ;
                                @endphp
                                @endif
                                @if(isset($faq_highLight->intSearchRank) && ($faq_highLight->intSearchRank != $faq->intSearchRank))
                                @php $Class_intSearchRank = " highlitetext"; @endphp
                                @else
                                @php $Class_intSearchRank = ""; @endphp
                                @endif
                                <h4 class="{{ $Class_intSearchRank }} form-label">Search Ranking</h4>
                                <a href="javascript:;" data-bs-toggle="tooltip" class="config" data-bs-placement="bottom" data-bs-content="{{ trans('faq::template.common.SearchEntityTools') }}" title="{{ trans('template.common.SearchEntityTools') }}"><i class="fa fa-question"></i></a>
                                <div class="md-radio-inline">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" value="1" name="search_rank" @if ($srank == 1) checked @endif id="yes_radio">
                                        <label for="yes_radio" id="yes_radio">High</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" value="2" name="search_rank" @if ($srank == 2) checked @endif id="maybe_radio">
                                        <label for="maybe_radio" id="maybe_radio">Medium</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" value="3" name="search_rank" @if ($srank == 3) checked @endif id="no_radio">
                                        <label for="no_radio" id="no_radio">Low</label>
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
                                <h4 class="form-section mb-3">{{ trans('faq::template.common.displayinformation') }}</h4>
                                @php
                                $display_order_attributes = array('class' => 'form-control','maxlength'=>5,'autocomplete'=>'off');
                                @endphp
                                @if(isset($faq_highLight->intDisplayOrder) && ($faq_highLight->intDisplayOrder != $faq->intDisplayOrder))
                                @php $Class_intDisplayOrder = " highlitetext"; @endphp
                                @else
                                @php $Class_intDisplayOrder = ""; @endphp
                                @endif
                                <div class="@if($errors->first('order')) has-error @endif form-md-line-input cm-floating">
                                    <label class="form-label {{ $Class_intDisplayOrder }}" for="site_name">{{ trans('faq::template.common.displayorder') }} <span aria-required="true" class="required"> * </span></label>
                                    {!! Form::text('order', isset($faq->intDisplayOrder)?$faq->intDisplayOrder:1, $display_order_attributes) !!}
                                    <span style="color: red;">{{ $errors->first('order') }}</span>
                                    <div class="publish-info mt-3">
                                        @if(isset($faq_highLight->chrPublish) && ($faq_highLight->chrPublish != $faq->chrPublish))
                                            @php $Class_chrPublish = " highlitetext"; @endphp
                                        @else
                                            @php $Class_chrPublish = ""; @endphp
                                        @endif

                                        @if(isset($faq) && $faq->chrAddStar == 'Y')
                                            <label class="control-label form-label"> Publish/ Unpublish</label>
                                            <input type="hidden" id="chrMenuDisplay" name="chrMenuDisplay" value="{{ isset($faq->chrPublish) ? $faq->chrPublish : '' }}">
                                            <p><b>NOTE:</b> This record is in Approval Request , so it can&#39;t be published/unpublished.</p>
                                        @elseif(isset($faq) && $faq->chrDraft == 'D' && $faq->chrAddStar != 'Y')
                                            @include('powerpanel.partials.displayInfo',['Class_chrPublish'=>$Class_chrPublish,'display' => (isset($faq->chrDraft)?$faq->chrDraft:'D')])
                                        @else
                                            @include('powerpanel.partials.displayInfo',['Class_chrPublish'=>$Class_chrPublish,'display' => (isset($faq->chrPublish)?$faq->chrPublish:'Y')])
                                        @endif
                                    </div>
                                </div>
                            </div>
                            {{-- Content Scheduling --}}
                            <div class="col-lg-6 col-sm-12">
                                @php $defaultDt = (null !== old('start_date_time'))?old('start_date_time'):date(Config::get('Constant.DEFAULT_DATE_FORMAT').' '.Config::get('Constant.DEFAULT_TIME_FORMAT')); @endphp
                                <h4 class="form-section mb-3">{{ trans('faq::template.common.ContentScheduling') }}</h4>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-md-line-input cm-floating">
                                            @php if(isset($faq_highLight->dtDateTime) && ($faq_highLight->dtDateTime != $faq->dtDateTime)){
                                            $Class_date = " highlitetext";
                                            }else{
                                            $Class_date = "";
                                            } @endphp
                                            <label class="form-label {!! $Class_date !!}">{{ trans('faq::template.common.startDateAndTime') }}<span aria-required="true" class="required"> * </span></label>
                                            <div class="input-group date form_meridian_datetime @if($errors->first('start_date_time')) has-error @endif" data-date="{{ Carbon\Carbon::today()->format('Y-m-d') }}T15:25:00Z">
                                                {!! Form::text('start_date_time', date(Config::get('Constant.DEFAULT_DATE_FORMAT').' '.Config::get('Constant.DEFAULT_TIME_FORMAT'),strtotime(isset($faq->dtDateTime)?$faq->dtDateTime:$defaultDt)), array('class' => 'form-control', 'data-provider' => 'flatpickr', 'data-date-format' => Config::get('Constant.DEFAULT_DATE_FORMAT'), 'data-enable-time' => '','maxlength'=>160,'size'=>'16','id'=>'faq_start_date','autocomplete'=>'off','onkeypress'=>"javascript: return KeycheckOnlyDate(event);",'onpaste'=>'return false')) !!}
                                            </div>
                                            <span class="help-block">{{ $errors->first('start_date_time') }}</span>
                                        </div>
                                    </div>
                                    @php $defaultDt = (null !== old('end_date_time'))?old('end_date_time'):null; @endphp
                                    @if ((isset($faq->dtEndDateTime)==null))
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
                                            @php if(isset($faq_highLight->varTitle) && ($faq_highLight->dtEndDateTime != $faq->dtEndDateTime)){
                                            $Class_end_date = " highlitetext";
                                            }else{
                                            $Class_end_date = "";
                                            } @endphp
                                            <div class="cm-floating form_meridian_datetime expirydate @if($errors->first('end_date_time')) has-error @endif" data-date="{{ Carbon\Carbon::today()->format('Y-m-d') }}T15:25:00Z" @if ($expChecked_yes==1) style="display:none;" @endif>
                                                <label class="form-label {!! $Class_end_date !!}">{{ trans('faq::template.common.endDateAndTime') }} <span aria-required="true" class="required"> * </span></label>
                                                <div class="input-group date">
                                                    {!! Form::text('end_date_time', isset($faq->dtEndDateTime)?date(Config::get('Constant.DEFAULT_DATE_FORMAT').' '.Config::get('Constant.DEFAULT_TIME_FORMAT'),strtotime($faq->dtEndDateTime)):$defaultDt, array('class' => 'form-control', 'data-provider' => 'flatpickr', 'data-date-format' => Config::get('Constant.DEFAULT_DATE_FORMAT'), 'data-enable-time' => '','maxlength'=>160,'size'=>'16','id'=>'faq_end_date','data-exp'=> $expChecked_yes,'data-newvalue','autocomplete'=>'off','onkeypress'=>"javascript: return KeycheckOnlyDate(event);",'onpaste'=>'return false')) !!}
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
                            {{-- Form Actions --}}
                            <div class="col-md-12">
                                <div class="form-actions">
                                    @if(isset($faq->fkMainRecord) && $faq->fkMainRecord != 0)
                                    <button type="submit" name="saveandexit" formmethod="post" class="btn btn-primary bg-gradient waves-effect waves-light btn-label me-1" value="saveandexit">
                                        <div class="flex-shrink-0">
                                            <i class="ri-check-line label-icon align-middle fs-20 me-2"></i>
                                        </div>
                                        {!! trans('faq::template.common.approve') !!}
                                    </button>
                                    @else
                                    @if($userIsAdmin)
                                    <button type="submit" name="saveandedit" formmethod="post" class="btn btn-primary bg-gradient waves-effect waves-light btn-label me-1" value="saveandedit">
                                        <div class="flex-shrink-0">
                                            <i class="ri-save-line label-icon align-middle fs-20 me-2"></i>
                                        </div>
                                        {!! trans('faq::template.common.saveandedit') !!}
                                    </button>
                                    <button type="submit" name="saveandexit" formmethod="post" class="btn btn-secondary bg-gradient waves-effect waves-light btn-label me-1" value="saveandexit">
                                        <div class="flex-shrink-0">
                                            <i class="ri-save-3-line label-icon align-middle fs-20 me-2"></i>
                                        </div>
                                        {!! trans('faq::template.common.saveandexit') !!}
                                    </button>
                                    @else
                                    @if((isset($chrNeedAddPermission) && $chrNeedAddPermission == 'N') && (isset($charNeedApproval) && $charNeedApproval == 'N'))
                                    <button type="submit" name="saveandexit" formmethod="post" class="btn btn-secondary bg-gradient waves-effect waves-light btn-label me-1" value="saveandexit">
                                        <div class="flex-shrink-0">
                                            <i class="ri-save-3-line label-icon align-middle fs-20 me-2"></i>
                                        </div>
                                        {!! trans('faq::template.common.saveandexit') !!}
                                    </button>
                                    @else
                                    <button type="submit" name="saveandexit" formmethod="post" class="btn btn-primary bg-gradient waves-effect waves-light btn-label me-1" value="approvesaveandexit">
                                        <div class="flex-shrink-0">
                                            <i class="ri-save-line label-icon align-middle fs-20 me-2"></i>
                                        </div>
                                        {!! trans('faq::template.common.approvesaveandexit') !!}
                                    </button>
                                    @endif
                                    @endif
                                    @endif
                                    <a class="btn btn-danger bg-gradient waves-effect waves-light btn-label me-1" href="{{ url('powerpanel/faq') }}">
                                        <div class="flex-shrink-0">
                                            <i class="ri-close-line label-icon align-middle fs-20 me-2"></i>
                                        </div>
                                        {{ trans('faq::template.common.cancel') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            {!! Form::close() !!}
        </div>
    </div>
</div><!--end row-->

@endsection
@section('scripts')
<script type="text/javascript">
    window.site_url = '{!! url("/") !!}';
    var seoFormId = 'frmFaq';
    var user_action = "{{ isset($faq)?'edit':'add' }}";
     var selectedCategory = "{{ isset($faq->intFKCategory)? $faq->intFKCategory : '' }}";
    var moduleAlias = "{{ App\Helpers\MyLibrary::getFrontUri('faq-category')['moduleAlias'] }}";
    var preview_add_route = '{!! route("powerpanel.faq.addpreview") !!}';
    var previewForm = $('#frmFaq');
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
        $('#varMetaKeyword').val(Meta_Keyword);
        $('#varMetaDescription').val(Meta_Description);
        $('#meta_title').html(Meta_Title);
        $('#meta_description').html(Meta_Description);
    }
</script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/faq/faq_validations.js' }}" type="text/javascript"></script>
@include('powerpanel.partials.ckeditor',['config'=>'docsConfig'])
<script src="{{ $CDN_PATH.'resources/pages/scripts/custom.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/seo-generator/seo-info-generator.js' }}" type="text/javascript"></script>

@endsection