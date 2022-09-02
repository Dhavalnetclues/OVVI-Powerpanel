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
            {!! Form::open(['method' => 'post','id'=>'frmServiceCategory']) !!}
                <div class="card">
                    <div class="card-body p-30 pb-0">
                        @if(isset($serviceCategory))
                        <div class="row pagetitle-heading mb-4">
                            <div class="col-sm-11 col-11">
                                <h4 class="page-title fw-semibold m-0">{{ $breadcrumb['inner_title']}}</h4>
                            </div>
                            <div class="col-sm-1 col-1 lock-link">
                                @if (File::exists(base_path() . '/resources/views/powerpanel/partials/lockedpage.blade.php') != null)
                                @include('powerpanel.partials.lockedpage',['pagedata'=>$serviceCategory])
                                @endif
                            </div>
                        </div>
                        @endif

                        <div class="row">
                            <!-- Sector type -->
                            <div class="col-md-12">
                                <div class="@if($errors->first('sector')) has-error @endif form-md-line-input cm-floating">
                                    @if(isset($serviceCategory_highLight->varSector) && ($serviceCategory_highLight->varSector != $serviceCategory->varSector))
                                    @php $Class_varSector = " highlitetext"; @endphp
                                    @else
                                    @php $Class_varSector = ""; @endphp
                                    @endif
                                        @if($hasRecords > 0)
                                    @php $disable = 'disabled'; @endphp
                                    @else
                                    @php $disable = ''; @endphp
                                    @endif
                                    @include('shiledcmstheme::powerpanel.partials.sector-dropdown', ['selected_sector' => isset($serviceCategory->varSector)?$serviceCategory->varSector:'','Class_varSector' => $Class_varSector,'disable' => $disable])
                                    <span class="help-block">{{ $errors->first('sector') }}</span>
                                    @if(isset($disable) && !empty($disable))
                                    <input type="hidden" name="sector" value="{{isset($serviceCategory->varSector)?$serviceCategory->varSector:''}}" />
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="@if($errors->first('title')) has-error @endif form-md-line-input cm-floating">
                                    @php if(isset($serviceCategory_highLight->varTitle) && ($serviceCategory_highLight->varTitle != $serviceCategory->varTitle)){
                                    $Class_title = " highlitetext";
                                    }else{
                                    $Class_title = "";
                                    } @endphp
                                    <label class="form_title {!! $Class_title !!}" for="site_name">{{ trans('servicecategory::template.common.name') }} <span aria-required="true" class="required"> * </span></label>
                                    {!! Form::text('title', isset($serviceCategory->varTitle)?$serviceCategory->varTitle:old('title'), array('maxlength' => 150,'id'=>'title', 'class' => 'form-control hasAlias seoField maxlength-handler titlespellingcheck','autocomplete'=>'off','data-url' => 'powerpanel/service-category')) !!}
                                    <span class="help-block">{{ $errors->first('title') }}</span>

                                    <!-- code for alias -->
                                    <div class="link-url mt-2">
                                        {!! Form::hidden(null, null, array('class' => 'hasAlias','data-url' => 'powerpanel/service-category')) !!}
                                        {!! Form::hidden('alias', isset($serviceCategory->alias->varAlias)?$serviceCategory->alias->varAlias:old('alias'), array('class' => 'aliasField')) !!}
                                        {!! Form::hidden('oldAlias', isset($serviceCategory->alias->varAlias)?$serviceCategory->alias->varAlias:old('alias')) !!}
                                        {!! Form::hidden('fkMainRecord', isset($serviceCategory->fkMainRecord)?$serviceCategory->fkMainRecord:old('fkMainRecord')) !!}
                                        {!! Form::hidden('previewId') !!}
                                        <div class="d-none">
                                            <div class="alias-group {{!isset($serviceCategory)?'d-none':''}} ">
                                                <label class="form_title" for="{{ trans('template.url') }}">{{ trans('servicecategory::template.common.url') }} :</label>
                                                @if(isset($serviceCategory->alias->varAlias) && !$userIsAdmin)
                                                    <a class="alias">{!! url("/") !!}</a>
                                                @else
                                                    @if(auth()->user()->can('service-category-create'))
                                                    <a href="javascript:void;" class="alias">{!! url("/") !!}</a>
                                                    <a href="javascript:void(0);" class="editAlias" title="{{ trans('servicecategory::template.common.edit') }}">
                                                        <i class="ri-pencil-line"></i>
                                                    </a>
                                                    <a class="without_bg_icon openLink" title="Open Link" onClick="generatePreview('{{ url('/previewpage?url='.(App\Helpers\MyLibrary::getFrontUri('service-category')['uri'])) }}');">
                                                        <i class="ri-external-link-line" aria-hidden="true"></i>
                                                    </a>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                        <span class="help-block">{{ $errors->first('alias') }}</span>
                                    </div>
                                    <!-- code for alias -->
                                </div>
                            </div>

                            <div class="col-md-12 d-none">
                                <div class="@if($errors->first('description')) has-error @endif">
                                    @if (Config::get('Constant.DEFAULT_VISUAL') == 'Y')
                                        <div id="body-roll">
                                            @php
                                                $sections = [];
                                            @endphp
                                            @if(isset($serviceCategory))
                                                @php
                                                    $sections = json_decode($serviceCategory->txtDescription);
                                                @endphp
                                            @endif
                                            <!-- Builder include -->
                                            @php
                                                Powerpanel\VisualComposer\Controllers\VisualComposerController::page_section(['sections'=>$sections])
                                            @endphp
                                        </div>
                                    @else
                                        @php if(isset($serviceCategory_highLight->txtDescription) && ($serviceCategory_highLight->txtDescription != $serviceCategory->txtDescription)){
                                        $Class_Description = " highlitetext";
                                        }else{
                                        $Class_Description = "";
                                        } @endphp
                                        <label class="form_title {!! $Class_Description !!}">{{ trans('servicecategory::template.common.description') }}</label>
                                        {!! Form::textarea('description', isset($serviceCategory->txtDescription)?$serviceCategory->txtDescription:old('description'), array('class' => 'form-control','id'=>'txtDescription')) !!}
                                    @endif
                                    <span class="help-block">{{ $errors->first('description') }}</span>
                                </div>
                            </div>

                            <div class="row d-none">
                                @php $defaultDt = (null !== old('start_date_time'))?old('start_date_time'):date(Config::get('Constant.DEFAULT_DATE_FORMAT').' '.Config::get('Constant.DEFAULT_TIME_FORMAT')); @endphp
                                <div class="col-md-6 d-none">
                                    <h4 class="form-section d-none">{{ trans('servicecategory::template.common.ContentScheduling') }}</h4>
                                    <div class="form-md-line-input">
                                        @php if(isset($serviceCategory_highLight->dtDateTime) && ($serviceCategory_highLight->dtDateTime != $serviceCategory->dtDateTime)){
                                        $Class_date = " highlitetext";
                                        }else{
                                        $Class_date = "";
                                        } @endphp
                                        <label class="control-label form_title {!! $Class_date !!}">{{ trans('servicecategory::template.common.startDateAndTime') }}<span aria-required="true" class="required"> * </span></label>
                                        <div class="input-group date form_meridian_datetime @if($errors->first('start_date_time')) has-error @endif" data-date="{{ Carbon\Carbon::today()->format('Y-m-d') }}T15:25:00Z">
                                            <span class="input-group-btn date_default">
                                                <button class="btn date-set fromButton" type="button">
                                                    <i class="ri-calendar-line"></i>
                                                </button>
                                            </span>
                                            {!! Form::text('start_date_time', date(Config::get('Constant.DEFAULT_DATE_FORMAT').' '.Config::get('Constant.DEFAULT_TIME_FORMAT'),strtotime(isset($serviceCategory->dtDateTime)?$serviceCategory->dtDateTime:$defaultDt)), array('class' => 'form-control','maxlength'=>160,'size'=>'16','id'=>'start_date_time','autocomplete'=>'off','onkeypress'=>"javascript: return KeycheckOnlyDate(event);",'onpaste'=>'return false')) !!}
                                        </div>
                                        <span class="help-block">
                                            {{ $errors->first('start_date_time') }}
                                        </span>
                                    </div>
                                </div>
                                @php $defaultDt = (null !== old('end_date_time'))?old('end_date_time'):null; @endphp
                                @if ((isset($serviceCategory->dtEndDateTime)==null))
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
                                        <div class="input-group date  form_meridian_datetime expirydate @if($errors->first('end_date_time')) has-error @endif" data-date="{{ Carbon\Carbon::today()->format('Y-m-d') }}T15:25:00Z" @if ($expChecked_yes==1) style="display:none;" @endif>
                                                @php if(isset($serviceCategory_highLight->varTitle) && ($serviceCategory_highLight->dtEndDateTime != $serviceCategory->dtEndDateTime)){
                                                $Class_end_date = " highlitetext";
                                                }else{
                                                $Class_end_date = "";
                                                } @endphp
                                                <label class="control-label form_title {!! $Class_end_date !!}" >{{ trans('servicecategory::template.common.endDateAndTime') }} <span aria-required="true" class="required"> * </span></label>
                                            <div class="pos_cal">
                                                <span class="input-group-btn date_default">
                                                    <button class="btn date-set toButton" type="button">
                                                        <i class="ri-calendar-line"></i>
                                                    </button>
                                                </span>
                                                {!! Form::text('end_date_time', isset($serviceCategory->dtEndDateTime)?date(Config::get('Constant.DEFAULT_DATE_FORMAT').' '.Config::get('Constant.DEFAULT_TIME_FORMAT'),strtotime($serviceCategory->dtEndDateTime)):$defaultDt, array('class' => 'form-control','maxlength'=>160,'size'=>'16','id'=>'end_date_time','data-exp'=> $expChecked_yes,'data-newvalue','autocomplete'=>'off','onkeypress'=>"javascript: return KeycheckOnlyDate(event);",'onpaste'=>'return false')) !!}
                                            </div>
                                        </div>
                                        <span class="help-block">
                                            {{ $errors->first('end_date_time') }}
                                        </span>
                                        <label class="expdatelabel {{ $expclass }}">
                                            <a id="noexpiry" name="noexpiry" href="javascript:void(0);">
                                                <b class="expiry_lbl {!! $Class_end_date !!}"></b>
                                            </a>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12 d-none">
                                @if(isset($serviceCategory->intSearchRank))
                                    @php $srank = $serviceCategory->intSearchRank; @endphp
                                @else
                                    @php $srank = null !== old('search_rank') ? old('search_rank') : 2; @endphp
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
                                        <strong>Note: </strong> {{ trans('servicecategory::template.common.SearchEntityTools') }}
                                    </div>
                                @endif
                            </div>

                            <div class="col-md-12 d-none">
                                <div class="nopadding">
                                    @include('powerpanel.partials.seoInfo',['form'=>'frmServiceCategory','inf'=>isset($metaInfo)?$metaInfo:false,'inf_highLight'=> isset($metaInfo_highLight)?$metaInfo_highLight:false])
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body p-30">
                        <div class="row">
                            <div class="col-md-12">
                                <h4 class="form-section mb-3">{{ trans('servicecategory::template.common.displayinformation') }}</h4>
                                @php
                                $display_order_attributes = array('class' => 'form-control','maxlength'=>5,'autocomplete'=>'off');
                                @endphp
                                @if(isset($serviceCategory_highLight->intDisplayOrder) && ($serviceCategory_highLight->intDisplayOrder != $serviceCategory->intDisplayOrder))
                                @php $Class_intDisplayOrder = " highlitetext"; @endphp
                                @else
                                @php $Class_intDisplayOrder = ""; @endphp
                                @endif
                                <div class="@if($errors->first('order')) has-error @endif form-md-line-input cm-floating">
                                    <label class="form_title {{ $Class_intDisplayOrder }}" for="site_name">{{ trans('servicecategory::template.common.displayorder') }} <span aria-required="true" class="required"> * </span></label>
                                    {!! Form::text('order', isset($serviceCategory->intDisplayOrder)?$serviceCategory->intDisplayOrder:'1', $display_order_attributes) !!}
                                    <span style="color: red;">{{ $errors->first('order') }}</span>

                                    <div class="publish-info mt-3">
                                        @if($hasRecords==0)
                                            @if(isset($serviceCategory_highLight->chrPublish) && ($serviceCategory_highLight->chrPublish != $serviceCategory->chrPublish))
                                                @php $Class_chrPublish = " highlitetext"; @endphp
                                            @else
                                                @php $Class_chrPublish = ""; @endphp
                                            @endif
                
                                            @if(isset($serviceCategory) && $serviceCategory->chrAddStar == 'Y')
                                                <label class="control-label form_title"> Publish/ Unpublish</label>
                                                <input type="hidden" id="chrMenuDisplay" name="chrMenuDisplay" value="{{ isset($serviceCategory->chrPublish) ? $serviceCategory->chrPublish : '' }}">
                                                <p><b>NOTE:</b> This record is in Approval Request , so it can&#39;t be published/unpublished.</p>
                                                @elseif(isset($serviceCategory) && $serviceCategory->chrDraft == 'D' && $serviceCategory->chrAddStar != 'Y')
                                                @include('powerpanel.partials.displayInfo',['Class_chrPublish'=>$Class_chrPublish,'display' => (isset($serviceCategory->chrDraft)?$serviceCategory->chrDraft:'D')])
                                            @else
                                                @include('powerpanel.partials.displayInfo',['Class_chrPublish'=>$Class_chrPublish,'display' => (isset($serviceCategory->chrPublish)?$serviceCategory->chrPublish:'Y')])
                                            @endif
                                        @else
                                            <label class="control-label form_title"> Publish/ Unpublish</label>
                                            @if($hasRecords > 0)
                                            <input type="hidden" id="chrMenuDisplay" name="chrMenuDisplay" value="{{ $serviceCategory->chrPublish }}">
                                            <p><b>NOTE:</b> This category is selected in {{ trans("template.sidebar.services") }}, so it can&#39;t be published/unpublished.</p>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions">
                                @if(isset($serviceCategory->fkMainRecord) && $serviceCategory->fkMainRecord != 0)
                                <button type="submit" name="saveandexit" formmethod="post" class="btn btn-primary" value="saveandexit">
                                    <div class="flex-shrink-0">
                                        <i class="ri-check-line label-icon align-middle fs-20 me-2"></i>
                                    </div>
                                    {!! trans('servicecategory::template.common.approve') !!}
                                </button>
                                @else
                                @if($userIsAdmin)
                                <button type="submit" name="saveandedit" formmethod="post" class="btn btn-primary bg-gradient waves-effect waves-light btn-label me-1" value="saveandedit">
                                    <div class="flex-shrink-0">
                                        <i class="ri-save-line label-icon align-middle fs-20 me-2"></i>
                                    </div>
                                    {!! trans('servicecategory::template.common.saveandedit') !!}
                                </button>
                                <button type="submit" name="saveandexit" formmethod="post" class="btn btn-secondary bg-gradient waves-effect waves-light btn-label me-1" value="saveandexit">
                                    <div class="flex-shrink-0">
                                        <i class="ri-save-3-line label-icon align-middle fs-20 me-2"></i>
                                    </div>
                                    {!! trans('servicecategory::template.common.saveandexit') !!}
                                </button>
                                @else
                                @if((isset($chrNeedAddPermission) && $chrNeedAddPermission == 'N') && (isset($charNeedApproval) && $charNeedApproval == 'N'))
                                <button type="submit" name="saveandexit" formmethod="post" class="btn btn-secondary bg-gradient waves-effect waves-light btn-label me-1" value="saveandexit">
                                    <div class="flex-shrink-0">
                                        <i class="ri-save-3-line label-icon align-middle fs-20 me-2"></i>
                                    </div>
                                    {!! trans('servicecategory::template.common.saveandexit') !!}
                                </button>
                                @else
                                <button type="submit" name="saveandexit" formmethod="post" class="btn btn-primary bg-gradient waves-effect waves-light btn-label me-1" value="approvesaveandexit">
                                    <div class="flex-shrink-0">
                                        <i class="ri-save-line label-icon align-middle fs-20 me-2"></i>
                                    </div>
                                    {!! trans('servicecategory::template.common.approvesaveandexit') !!}
                                </button>
                                @endif
                                @endif
                                @endif
                                <a class="btn btn-danger bg-gradient waves-effect waves-light btn-label me-1" href="{{ url('powerpanel/service-category') }}">
                                    <div class="flex-shrink-0">
                                        <i class="ri-close-line label-icon align-middle fs-20 me-2"></i>
                                    </div>
                                    {{ trans('servicecategory::template.common.cancel') }}
                                </a>
                                @if(isset($serviceCategory) && $userIsAdmin)
                                <a style="display: none" class="btn btn-info bg-gradient waves-effect waves-light btn-label me-1" title="Preview" onClick="generatePreview('{{url('/previewpage?url='.(App\Helpers\MyLibrary::getFrontUri('service-category')['uri']))}}');">
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
            {!! Form::close() !!}
        </div>
    </div>
</div>

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
            var seoFormId = 'frmServiceCategory';
            var user_action = "{{ isset($serviceCategory)?'edit':'add' }}";
            var moduleAlias = "{{ App\Helpers\MyLibrary::getFrontUri('service-category')['moduleAlias'] }}";
            var preview_add_route = '{!! route("powerpanel.service-category.addpreview") !!}';
            var previewForm = $('#frmServiceCategory');
            var isDetailPage = false;
            function generate_seocontent1(formname) {
            var Meta_Title = document.getElementById('title').value + "";
                    var abcd = $('textarea#txtDescription').val();
                    var def = abcd.replace(/<a(\s[^>]*)?>.*?<\/a>/ig, "")
                    var abc = def.replace(/^(\s*)|(\s*)$/g, '').replace(/\s+/g, ' ');
                    var outString1 = abc.replace(/(<([^>]+)>)/ig, "");
                    var Meta_Description = outString1.substr(0, 200);
                    var Meta_Keyword = "";
                    $('#varMetaTitle').val(Meta_Title);
//                    $('#varMetaKeyword').val(Meta_Keyword);
                    $('#varMetaDescription').val(Meta_Description);
                    $('#meta_title').html(Meta_Title);
                    $('#meta_description').html(Meta_Description);
            }
</script>
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-taginput/bootstrap-tagsinput.min.js' }}" type="text/javascript"></script>
<script type="text/javascript">
            function OpenPassword(val) {
            if (val == 'PP') {
            $("#passid").show();
            } else {
            $("#passid").d-none();
            }
            }
</script>
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
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/servicecategory/service_category_validations.js' }}" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS -->
@if (Config::get('Constant.DEFAULT_VISUAL') == 'Y')
    @php Powerpanel\VisualComposer\Controllers\VisualComposerController::get_builder_css_js(); @endphp
@endif
@endsection