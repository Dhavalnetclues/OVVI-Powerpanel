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
            {!! Form::open(['method' => 'post','id'=>'frmBlogCategory']) !!}
            <div class="card">
                <div class="card-body p-30 pb-0">
                    @if(isset($blogCategory))
                    <div class="row pagetitle-heading mb-3">
                        <div class="col-sm-11 col-11">
                            <h4 class="page-title fw-semibold m-0">{{ $breadcrumb['inner_title']}}</h4>
                        </div>
                        <div class="col-sm-1 col-1 lock-link">
                            @if (File::exists(base_path() . '/resources/views/powerpanel/partials/lockedpage.blade.php') != null)
                            @include('powerpanel.partials.lockedpage',['pagedata'=>$blogCategory])
                            @endif
                        </div>
                    </div>
                    @endif

                    <div class="row">
                        <!-- Sector type -->
                        <div class="col-md-12 @if($errors->first('sector')) has-error @endif">
                            <div class="form-md-line-input cm-floating">
                                @if(isset($blogCategory_highLight->varSector) && ($blogCategory_highLight->varSector != $blogCategory->varSector))
                                @php $Class_varSector = " highlitetext"; @endphp
                                @else
                                @php $Class_varSector = ""; @endphp
                                @endif
                                <label class="form-label {{ $Class_varSector }}" for="site_name">Select Sector Type </label>
                                <select class="form-control" name="sector" id="sector" data-choices>
                                    <option value="">Select Sector Type</option>
                                    @foreach($sector as  $keySector => $ValueSector)
                                    @php $permissionName = 'blogCategory-list' @endphp
                                    @php $selected = ''; @endphp
                                    @if(isset($blogCategory->varSector))
                                    @if($keySector == $blogCategory->varSector)
                                    @php $selected = 'selected';  @endphp
                                    @endif
                                    @endif
                                    <option value="{{$keySector}}" {{ $selected }}>{{ ($ValueSector == "blogCategory") ? 'Select Sector Type' : $ValueSector }}</option>
                                    @endforeach
                                </select>
                                <span class="help-block">{{ $errors->first('sector') }}</span>
                            </div>
                        </div>

                        <!-- Title -->
                        <div class="col-md-12">
                            <div class="form-md-line-input cm-floating">
                                @php if(isset($blogCategory_highLight->varTitle) && ($blogCategory_highLight->varTitle != $blogCategory->varTitle)){
                                $Class_title = " highlitetext";
                                }else{
                                $Class_title = "";
                                } @endphp
                                <label class="form-label {!! $Class_title !!}" for="site_name">
                                    {{ trans('blogcategory::template.common.name') }} <span aria-required="true" class="required"> * </span>
                                </label>
                                {!! Form::text('title', isset($blogCategory->varTitle)?$blogCategory->varTitle:old('title'), array('maxlength' => 150,'id'=>'title', 'class' => 'form-control hasAlias seoField maxlength-handler titlespellingcheck','autocomplete'=>'off','data-url' => 'powerpanel/blog-category')) !!}
                                <span class="help-block">{{ $errors->first('title') }}</span>

                                <!-- code for alias -->
                                <div class="link-url mt-2">
                                    {!! Form::hidden(null, null, array('class' => 'hasAlias','data-url' => 'powerpanel/blog-category')) !!}
                                    {!! Form::hidden('alias', isset($blogCategory->alias->varAlias)?$blogCategory->alias->varAlias:old('alias'), array('class' => 'aliasField')) !!}
                                    {!! Form::hidden('oldAlias', isset($blogCategory->alias->varAlias)?$blogCategory->alias->varAlias:old('alias')) !!}
                                    {!! Form::hidden('fkMainRecord', isset($blogCategory->fkMainRecord)?$blogCategory->fkMainRecord:old('fkMainRecord')) !!}
                                    {!! Form::hidden('previewId') !!}
                                    <div class="alias-group {{!isset($blogCategory)?'hide':''}} ">
                                        <label class="form-label m-0" for="{{ trans('template.url') }}">{{ trans('blogcategory::template.common.url') }} :</label>
                                        @if(isset($blogCategory->alias->varAlias) && !$userIsAdmin)
                                            <a class="alias">{!! url("/") !!}</a>
                                        @else
                                            @if(auth()->user()->can('blog-category-create'))    
                                            <a href="javascript:void(0);" class="alias">{!! url("/") !!}</a>
                                            <a href="javascript:void(0);" class="editAlias" title="{{ trans('blogcategory::template.common.edit') }}">
                                                <i class="ri-pencil-line"></i>
                                            </a>
                                            <a href="javascript:void(0);" class="without_bg_icon openLink" title="Open Link" onClick="generatePreview('{{ url('/previewpage?url='.(App\Helpers\MyLibrary::getFrontUri('blog-category')['uri'])) }}');">
                                                <i class="ri-external-link-fill" aria-hidden="true"></i>
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

            {{-- Page Content --}}
            <div class="card">
                <div class="card-body p-30 pb-0">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3 @if($errors->first('description')) has-error @endif">
                                @if (Config::get('Constant.DEFAULT_VISUAL') == 'Y')
                                    <div id="body-roll">
                                        @php
                                            $sections = [];
                                        @endphp
                                        @if(isset($blogCategory))
                                            @php
                                                $sections = json_decode($blogCategory->txtDescription);
                                            @endphp
                                        @endif
                                        <!-- Builder include -->
                                        @php
                                            Powerpanel\VisualComposer\Controllers\VisualComposerController::page_section(['sections'=>$sections])
                                        @endphp
                                    </div>
                                @else
                                    @php if(isset($blogCategory_highLight->txtDescription) && ($blogCategory_highLight->txtDescription != $blogCategory->txtDescription)){
                                    $Class_Description = " highlitetext";
                                    }else{
                                    $Class_Description = "";
                                    } @endphp
                                    <h4 class="form-section mb-3 form-label {!! $Class_Description !!}">{{ trans('blogcategory::template.common.description') }}</h4>
                                    {!! Form::textarea('description', isset($blogCategory->txtDescription)?$blogCategory->txtDescription:old('description'), array('class' => 'form-control','id'=>'txtDescription')) !!}
                                @endif
                                <span class="help-block">{{ $errors->first('description') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div> {{-- .end Page Content --}}

            {{-- Search Ranking --}}
            <div class="card">
                <div class="card-body p-30">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="nopadding">
                                @if(isset($blogCategory->intSearchRank))
                                    @php $srank = $blogCategory->intSearchRank; @endphp
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
                                        <strong>Note: </strong> {{ trans('blogcategory::template.common.SearchEntityTools') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- SEO Information --}}
            <div class="card">
                <div class="card-body p-30">
                    <div class="row">
                        <div class="col-md-12">
                            @if(isset($blogCategory_highLight->intSearchRank) && ($blogCategory_highLight->intSearchRank != $blogCategory->intSearchRank))
                                @php $Class_intSearchRank = " highlitetext"; @endphp
                            @else
                                @php $Class_intSearchRank = ""; @endphp
                            @endif
                            @include('powerpanel.partials.seoInfo',['form'=>'frmBlogCategory','inf'=>isset($metaInfo)?$metaInfo:false,'inf_highLight'=> isset($metaInfo_highLight)?$metaInfo_highLight:false, 'Class_intSearchRank' => $Class_intSearchRank, 'srank' => $srank])
                        </div>
                    </div>
                </div>
            </div> {{-- .end SEO Information --}}

            {{-- Content & Display Info --}}
            <div class="card">
                <div class="card-body p-30">
                    <div class="row">
                        <div class="col-lg-6 col-sm-12">
                            <h4 class="form-section mb-3">{{ trans('blogcategory::template.common.displayinformation') }}</h4>
                            @php
                            $display_order_attributes = array('class' => 'form-control','maxlength'=>5,'autocomplete'=>'off');
                            @endphp
                            @if(isset($blogCategory_highLight->intDisplayOrder) && ($blogCategory_highLight->intDisplayOrder != $blogCategory->intDisplayOrder))
                            @php $Class_intDisplayOrder = " highlitetext"; @endphp
                            @else
                            @php $Class_intDisplayOrder = ""; @endphp
                            @endif
                            <div class="@if($errors->first('order')) has-error @endif form-md-line-input cm-floating">
                                <label class="form-label {{ $Class_intDisplayOrder }}" for="site_name">{{ trans('blogcategory::template.common.displayorder') }} <span aria-required="true" class="required"> * </span></label>
                                {!! Form::text('order', isset($blogCategory->intDisplayOrder)?$blogCategory->intDisplayOrder:'1', $display_order_attributes) !!}
                                <span style="color: red;">{{ $errors->first('order') }}</span>

                                <div class="publish-info mt-3">
                                    @if($hasRecords==0)
                                        @if(isset($blogCategory_highLight->chrPublish) && ($blogCategory_highLight->chrPublish != $blogCategory->chrPublish))
                                        @php $Class_chrPublish = " highlitetext"; @endphp
                                        @else
                                        @php $Class_chrPublish = ""; @endphp
                                        @endif
                                        @if((isset($blogCategory) && $blogCategory->chrDraft == 'D'))
                                        @include('powerpanel.partials.displayInfo',['Class_chrPublish'=>$Class_chrPublish,'display' => (isset($blogCategory->chrDraft)?$blogCategory->chrDraft:'D')])
                                        @else
                                        @include('powerpanel.partials.displayInfo',['Class_chrPublish'=>$Class_chrPublish,'display' => (isset($blogCategory->chrPublish)?$blogCategory->chrPublish:'Y')])
                                        @endif
                                    @else
                                        <label class="control-label form-label"> Publish / Unpublish</label>
                                        @if($hasRecords > 0)
                                        <input type="hidden" id="chrMenuDisplay" name="chrMenuDisplay" value="{{ $blogCategory->chrPublish }}">
                                        <p><b>NOTE:</b> This category is selected in {{ trans("template.sidebar.blogs") }}, so it can&#39;t be published/unpublished.</p>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 col-sm-12">
                            <h4 class="form-section mb-3">{{ trans('blogcategory::template.common.ContentScheduling') }}</h4>
                            @php $defaultDt = (null !== old('start_date_time'))?old('start_date_time'):date(Config::get('Constant.DEFAULT_DATE_FORMAT').' '.Config::get('Constant.DEFAULT_TIME_FORMAT')); @endphp
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-md-line-input cm-floating">
                                        @php if(isset($blogCategory_highLight->dtDateTime) && ($blogCategory_highLight->dtDateTime != $blogCategory->dtDateTime)){
                                        $Class_date = " highlitetext";
                                        }else{
                                        $Class_date = "";
                                        } @endphp
                                        <label class="form-label {!! $Class_date !!}">{{ trans('blogcategory::template.common.startDateAndTime') }}<span aria-required="true" class="required"> * </span></label>
                                        <div class="input-group date form_meridian_datetime @if($errors->first('start_date_time')) has-error @endif" data-date="{{ Carbon\Carbon::today()->format('Y-m-d') }}T15:25:00Z">
                                            {!! Form::text('start_date_time', date(Config::get('Constant.DEFAULT_DATE_FORMAT').' '.Config::get('Constant.DEFAULT_TIME_FORMAT'),strtotime(isset($blogCategory->dtDateTime)?$blogCategory->dtDateTime:$defaultDt)), array('class' => 'form-control', 'data-provider' => 'flatpickr', 'data-date-format' => Config::get('Constant.DEFAULT_DATE_FORMAT'), 'data-enable-time' => '','maxlength'=>160,'size'=>'16','id'=>'start_date_time','autocomplete'=>'off','onkeypress'=>"javascript: return KeycheckOnlyDate(event);",'onpaste'=>'return false')) !!}
                                        </div>
                                        <span class="help-block">{{ $errors->first('start_date_time') }}</span>
                                    </div>
                                </div>
                                @php $defaultDt = (null !== old('end_date_time'))?old('end_date_time'):null; @endphp
                                @if ((isset($blogCategory->dtEndDateTime)==null))
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
                                        @php if(isset($blogCategory_highLight->varTitle) && ($blogCategory_highLight->dtEndDateTime != $blogCategory->dtEndDateTime)){
                                        $Class_end_date = " highlitetext";
                                        }else{
                                        $Class_end_date = "";
                                        } @endphp
                                        <div class="cm-floating form_meridian_datetime expirydate @if($errors->first('end_date_time')) has-error @endif" data-date="{{ Carbon\Carbon::today()->format('Y-m-d') }}T15:25:00Z" @if ($expChecked_yes==1) style="display:none;" @endif>
                                            <label class="form-label {!! $Class_end_date !!}" >{{ trans('blogcategory::template.common.endDateAndTime') }} <span aria-required="true" class="required"> * </span></label>
                                            <div class="input-group date">
                                                {!! Form::text('end_date_time', isset($blogCategory->dtEndDateTime)?date(Config::get('Constant.DEFAULT_DATE_FORMAT').' '.Config::get('Constant.DEFAULT_TIME_FORMAT'),strtotime($blogCategory->dtEndDateTime)):$defaultDt, array('class' => 'form-control', 'data-provider' => 'flatpickr', 'data-date-format' => Config::get('Constant.DEFAULT_DATE_FORMAT'), 'data-enable-time' => '','maxlength'=>160,'size'=>'16','id'=>'end_date_time','data-exp'=> $expChecked_yes,'data-newvalue','autocomplete'=>'off','onkeypress'=>"javascript: return KeycheckOnlyDate(event);",'onpaste'=>'return false')) !!}
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

                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-12">
                                @if(isset($blogCategory->fkMainRecord) && $blogCategory->fkMainRecord != 0)
                                    <button type="submit" name="saveandexit" formmethod="post" class="btn btn-primary bg-gradient waves-effect waves-light btn-label me-1" value="saveandexit">
                                        <div class="flex-shrink-0">
                                            <i class="ri-check-line label-icon align-middle fs-20 me-2"></i>
                                        </div>
                                        {!! trans('blogcategory::template.common.approve') !!}
                                    </button>
                                @else
                                    @if($userIsAdmin)
                                        <button type="submit" name="saveandedit" formmethod="post" class="btn btn-primary bg-gradient waves-effect waves-light btn-label me-1" value="saveandedit">
                                            <div class="flex-shrink-0">
                                                <i class="ri-save-line label-icon align-middle fs-20 me-2"></i>
                                            </div>
                                            {!! trans('blogcategory::template.common.saveandedit') !!}
                                        </button>
                                        <button type="submit" name="saveandexit" formmethod="post" class="btn btn-secondary bg-gradient waves-effect waves-light btn-label me-1" value="saveandexit">
                                            <div class="flex-shrink-0">
                                                <i class="ri-save-3-line label-icon align-middle fs-20 me-2"></i>
                                            </div>
                                            {!! trans('blogcategory::template.common.saveandexit') !!}
                                        </button>
                                    @else
                                        @if((isset($chrNeedAddPermission) && $chrNeedAddPermission == 'N') && (isset($charNeedApproval) && $charNeedApproval == 'N'))
                                            <button type="submit" name="saveandexit" formmethod="post" class="btn btn-secondary bg-gradient waves-effect waves-light btn-label me-1" value="saveandexit">
                                                <div class="flex-shrink-0">
                                                    <i class="ri-save-3-line label-icon align-middle fs-20 me-2"></i>
                                                </div>
                                                {!! trans('blogcategory::template.common.saveandexit') !!}
                                            </button>
                                        @else
                                            <button type="submit" name="saveandexit" formmethod="post" class="btn btn-secondary bg-gradient waves-effect waves-light btn-label me-1" value="approvesaveandexit">
                                                <div class="flex-shrink-0">
                                                    <i class="ri-save-line label-icon align-middle fs-20 me-2"></i>
                                                </div>
                                                {!! trans('blogcategory::template.common.approvesaveandexit') !!}
                                            </button>
                                        @endif
                                    @endif
                                @endif
                                <a class="btn btn-danger bg-gradient waves-effect waves-light btn-label me-1" href="{{ url('powerpanel/blog-category') }}">
                                    <div class="flex-shrink-0">
                                        <i class="ri-close-line label-icon align-middle fs-20 me-2"></i>
                                    </div>
                                    {{ trans('blogcategory::template.common.cancel') }}
                                </a>
                                @if(isset($blogCategory) && $userIsAdmin)
                                    <a class="btn btn-info bg-gradient waves-effect waves-light btn-label ms-1" title="Preview" onClick="generatePreview('{{url('/previewpage?url='.url('/'.$blogCategory->alias->varAlias))}}');">
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
            </div>{{-- Content & Display Info --}}
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
            var seoFormId = 'frmBlogCategory';
            var user_action = "{{ isset($blogCategory)?'edit':'add' }}";
            var moduleAlias = "{{ App\Helpers\MyLibrary::getFrontUri('blog-category')['moduleAlias'] }}";
            var preview_add_route = '{!! route("powerpanel.blog-category.addpreview") !!}';
            var previewForm = $('#frmBlogCategory');
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
            $("#passid").hide();
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
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/blogcategory/blog_category_validations.js?v='.time() }}" type="text/javascript"></script>

<script src="https://themesbrand.com/velzon/html/default/assets/css/app.min.css"></script>
<!-- END PAGE LEVEL SCRIPTS -->
@if (Config::get('Constant.DEFAULT_VISUAL') == 'Y')
    @php Powerpanel\VisualComposer\Controllers\VisualComposerController::get_builder_css_js(); @endphp
@endif
@endsection