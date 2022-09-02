@section('css')
<link href="{{ $CDN_PATH.'resources/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css' }}" rel="stylesheet" type="text/css" />
<link href="{{ $CDN_PATH.'resources/global/plugins/bootstrap-taginput/bootstrap-tagsinput.css' }}" rel="stylesheet" type="text/css" />
<!-- BEGIN PAGE LEVEL PLUGINS -->
<!-- END PAGE LEVEL PLUGINS -->
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
            {!! Form::open(['method' => 'post','id'=>'frmPublicationsCategory']) !!}
            <div class="card">
                <div class="card-body p-30 pb-0">
                    @if(isset($publicationsCategory))
                        <div class="row pagetitle-heading mb-3">
                            <div class="col-sm-11 col-11">
                                <h4 class="page-title fw-semibold m-0">{{ $breadcrumb['inner_title']}}</h4>
                            </div>
                            <div class="col-sm-1 col-1 lock-link">
                                @if (File::exists(base_path() . '/resources/views/powerpanel/partials/lockedpage.blade.php') != null)
                                @include('powerpanel.partials.lockedpage',['pagedata'=>$publicationsCategory])
                                @endif
                            </div>
                        </div>
                    @endif

                    <div class="row">
                        {!! Form::hidden('fkMainRecord', isset($publicationsCategory->fkMainRecord)?$publicationsCategory->fkMainRecord:old('fkMainRecord')) !!}
                        {{-- Sector type --}}
                        <div class="col-md-12">
                            <div class="@if($errors->first('sector')) has-error @endif form-md-line-input cm-floating">
                                @if(isset($publicationsCategoryHighLight->varSector) && ($publicationsCategoryHighLight->varSector != $publicationsCategory->varSector))
                                    @php $Class_varSector = " highlitetext"; @endphp
                                @else
                                    @php $Class_varSector = ""; @endphp
                                @endif
        
                                @if($hasRecords > 0)
                                @php $disable = 'disabled'; @endphp
                                @else
                                @php $disable = ''; @endphp
                                @endif
                                @include('shiledcmstheme::powerpanel.partials.sector-dropdown', ['selected_sector' => isset($publicationsCategory->varSector)?$publicationsCategory->varSector:'','Class_varSector' => $Class_varSector,'disable' => $disable])
                                <!-- <span class="help-block">{{ $errors->first('sector') }}</span> -->

                                @if(isset($disable) && !empty($disable))
                                <input type="hidden" name="sector" value="{{isset($publicationsCategory->varSector)?$publicationsCategory->varSector:''}}" />
                                @endif
                            </div>
                        </div>
                        {{-- Title --}}
                        <div class="col-md-12">
                            @if(isset($publicationsCategoryHighLight->varTitle) && ($publicationsCategoryHighLight->varTitle != $publicationsCategory->varTitle))
                            @php $Class_title = " highlitetext"; @endphp
                            @else
                            @php $Class_title = ""; @endphp
                            @endif
                            
                            <div class="@if($errors->first('title')) has-error @endif form-md-line-input cm-floating">
                                <label class="form-label {{ $Class_title }}" for="site_name">{{ trans('publications-category::template.common.name') }} <span aria-required="true" class="required"> * </span></label>
                                {!! Form::text('title', isset($publicationsCategory->varTitle) ? $publicationsCategory->varTitle : old('title'), array('maxlength' => 150,'id'=>'title', 'class' => 'form-control hasAlias seoField maxlength-handler titlespellingcheck','data-url' => 'powerpanel/publications-category','autocomplete'=>'off')) !!}
                                <span class="help-block">{{ $errors->first('title') }}</span>
                                <!-- code for alias -->
                                <div class="link-url mt-2 d-none">
                                    {!! Form::hidden(null, null, array('class' => 'hasAlias','data-url' => 'powerpanel/pubications-category')) !!}
                                    {!! Form::hidden('alias', isset($publicationsCategory->alias->varAlias) ? $publicationsCategory->alias->varAlias:old('alias'), array('class' => 'aliasField')) !!}
                                    {!! Form::hidden('oldAlias', isset($publicationsCategory->alias->varAlias) ? $publicationsCategory->alias->varAlias:old('alias')) !!}
                                    {!! Form::hidden('previewId') !!}
                                    <div class="alias-group {{!isset($publicationsCategory->alias->varAlias)?'hide':''}}">
                                        <label class="form-label" for="Url">Url :</label>
                                        @if(isset($publicationsCategory->alias->varAlias) && !$userIsAdmin)
                                        <a class="alias">{!! url("/") !!}</a>
                                        @else
                                        @if(auth()->user()->can('publications-category-create'))
                                        <a href="javascript:void;" class="alias">{!! url("/") !!}</a>
                                        <a href="javascript:void(0);" class="editAlias" title="Edit">
                                            <i class="ri-pencil-line"></i>
                                        </a>
                                        <a class="without_bg_icon openLink" title="Open Link" onClick="generatePreview('{{  url('/previewpage?url='.(App\Helpers\MyLibrary::getFrontUri('publications-category')['uri']))  }}');">
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
                        {{-- Publications Category --}}
                        <div class="col-md-12">
                            <div class="@if($errors->first('categories')) has-error @endif form-md-line-input cm-floating">
                                @if(isset($publicationsCategoryHighLight->intParentCategoryId) && ($publicationsCategoryHighLight->intParentCategoryId != $publicationsCategory->intParentCategoryId))
                                @php $Class_intParentCategoryId = " highlitetext"; @endphp
                                @else
                                @php $Class_intParentCategoryId = ""; @endphp
                                @endif
                                <label class="form-label {{ $Class_intParentCategoryId }}" for="parent_category_id">{{ trans('publications-category::template.publications_categoryModule.selectparentpublicationsCategory') }}</label>
                                @php echo $categories; @endphp
                            </div>
                        </div>
                    </div>
                    
                    <div class="row d-none">
                        <div class="col-md-12">
                            @if(isset($publicationsCategoryHighLight->txtDescription) && ($publicationsCategoryHighLight->txtDescription != $publicationsCategory->txtDescription))
                            @php $Class_txtDescription = " highlitetext"; @endphp
                            @else
                            @php $Class_txtDescription = ""; @endphp
                            @endif
                            <div class="@if($errors->first('description')) has-error @endif ">
                                <label class="control-label form-label {{ $Class_txtDescription }}">{{ trans('publications-category::template.common.description') }}</label>
                                {!! Form::textarea('description', isset($publicationsCategory->txtDescription) ? $publicationsCategory->txtDescription : old('description'), array('class' => 'form-control','id'=>'txtDescription')) !!}
                                <span class="help-block">{{ $errors->first('description') }}</span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <h4 class="form-section mb-3">{{ trans('publications-category::template.common.ContentScheduling') }}</h4>
                            @php $defaultDt = (null !== old('start_date_time'))?old('start_date_time'):date(Config::get('Constant.DEFAULT_DATE_FORMAT').' '.Config::get('Constant.DEFAULT_TIME_FORMAT')); @endphp
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-md-line-input cm-floating">
                                        @php if(isset($publicationsCategoryHighLight->dtDateTime) && ($publicationsCategoryHighLight->dtDateTime != $publicationsCategory->dtDateTime)){
                                        $Class_date = " highlitetext";
                                        }else{
                                        $Class_date = "";
                                        } @endphp
                                        <label class="control-label form_title {!! $Class_date !!}">{{ trans('publications-category::template.common.startDateAndTime') }}<span aria-required="true" class="required"> * </span></label>
                                        <div class="input-group date form_meridian_datetime @if($errors->first('start_date_time')) has-error @endif" data-date="{{ Carbon\Carbon::today()->format('Y-m-d') }}T15:25:00Z">
                                            {!! Form::text('start_date_time', date(Config::get('Constant.DEFAULT_DATE_FORMAT').' '.Config::get('Constant.DEFAULT_TIME_FORMAT'),strtotime(isset($publicationsCategory->dtDateTime)?$publicationsCategory->dtDateTime:$defaultDt)), array('class' => 'form-control', 'data-provider' => 'flatpickr', 'data-date-format' => Config::get('Constant.DEFAULT_DATE_FORMAT'), 'data-enable-time' => '','maxlength'=>160,'size'=>'16','id'=>'start_date_time','autocomplete'=>'off','onkeypress'=>"javascript: return KeycheckOnlyDate(event);",'onpaste'=>'return false')) !!}
                                        </div>
                                        <span class="help-block">{{ $errors->first('start_date_time') }}</span>
                                    </div>
                                </div>
                                @php $defaultDt = (null !== old('end_date_time'))?old('end_date_time'):null; @endphp
                                @if ((isset($publicationsCategory->dtEndDateTime)==null))
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
                                        @php if(isset($publicationsCategoryHighLight->varTitle) && ($publicationsCategoryHighLight->dtEndDateTime != $publicationsCategory->dtEndDateTime)){
                                        $Class_end_date = " highlitetext";
                                        }else{
                                        $Class_end_date = "";
                                        } @endphp
                                        <div class="cm-floating form_meridian_datetime expirydate @if($errors->first('end_date_time')) has-error @endif" data-date="{{ Carbon\Carbon::today()->format('Y-m-d') }}T15:25:00Z" @if ($expChecked_yes==1) style="display:none;" @endif>
                                            <label class="form-label {!! $Class_end_date !!}">{{ trans('publications-category::template.common.endDateAndTime') }}<span aria-required="true" class="required"> * </span></label>
                                            <div class="input-group date">
                                                {!! Form::text('end_date_time', isset($publicationsCategory->dtEndDateTime)?date(Config::get('Constant.DEFAULT_DATE_FORMAT').' '.Config::get('Constant.DEFAULT_TIME_FORMAT'),strtotime($publicationsCategory->dtEndDateTime)):$defaultDt, array('class' => 'form-control', 'data-provider' => 'flatpickr', 'data-date-format' => Config::get('Constant.DEFAULT_DATE_FORMAT'), 'data-enable-time' => '','maxlength'=>160,'size'=>'16','id'=>'end_date_time','data-exp'=> $expChecked_yes,'data-newvalue','autocomplete'=>'off','onkeypress'=>"javascript: return KeycheckOnlyDate(event);",'onpaste'=>'return false')) !!}
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
                        <div class="col-md-12">
                            @if(isset($publicationsCategory->intSearchRank))
                                @php $srank = $publicationsCategory->intSearchRank; @endphp
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
                                    <strong>Note: </strong> {{ trans('publications-category::template.common.SearchEntityTools') }}
                                </div>
                            @endif
                        </div>
                        <div class="col-md-12">
                            <div class=" form-md-line-input nopadding">
                                @include('powerpanel.partials.seoInfo',['inf_highLight'=> isset($metaInfo_highLight)?$metaInfo_highLight:false,'form'=>'frmPublicationsCategory','inf'=>isset($metaInfo)?$metaInfo:false,'metaRequired'=>true])
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
                            <h4 class="form-section mb-3">{{ trans('publications-category::template.common.displayinformation') }}</h4>
                            @php
                            $display_order_attributes = array('class' => 'form-control','maxlength'=>5,'autocomplete'=>'off');
                            @endphp
                            @if(isset($publicationsCategoryHighLight->intDisplayOrder) && ($publicationsCategoryHighLight->intDisplayOrder != $publicationsCategory->intDisplayOrder))
                            @php $Class_intDisplayOrder = " highlitetext"; @endphp
                            @else
                            @php $Class_intDisplayOrder = ""; @endphp
                            @endif
                            <div class="@if($errors->first('display_order')) has-error @endif form-md-line-input cm-floating">
                                <label class="form-label {{ $Class_intDisplayOrder }}" class="site_name">{{ trans('publications-category::template.common.displayorder') }} <span aria-required="true" class="required"> * </span></label>
                                {!! Form::text('display_order', isset($publicationsCategory->intDisplayOrder)?$publicationsCategory->intDisplayOrder : 1, $display_order_attributes) !!}
                                <span class="help-block"><strong>{{ $errors->first('display_order') }}</strong></span>
                                <div class="publish-info mt-3">
                                    @if($isParent==0 && $hasRecords==0)
                                        @if(isset($publicationsCategoryHighLight->chrPublish) && ($publicationsCategoryHighLight->chrPublish != $publicationsCategory->chrPublish))
                                        @php $Class_chrPublish = " highlitetext"; @endphp
                                        @else
                                        @php $Class_chrPublish = ""; @endphp
                                        @endif
                                        
                                        @if(isset($publicationsCategory) && $publicationsCategory->chrAddStar == 'Y')
                                            <div class="col-md-6">
                                            <div class="mb-3">
                                            <label class="control-label form-label"> Publish/ Unpublish</label>
                                            <input type="hidden" id="chrMenuDisplay" name="chrMenuDisplay" value="{{ isset($publicationsCategory->chrPublish) ? $publicationsCategory->chrPublish : '' }}">
                                            <p><b>NOTE:</b> This record is in Approval Request , so it can&#39;t be published/unpublished.</p>
                                            </div>
                                            </div>
                                        @elseif(isset($publicationsCategory) && $publicationsCategory->chrDraft == 'D' &&   $publicationsCategory->chrAddStar != 'Y')
                                        @include('powerpanel.partials.displayInfo',['Class_chrPublish'=>$Class_chrPublish,'display' => (isset($publicationsCategory->chrDraft)?$publicationsCategory->chrDraft:'D')])
                                        @else
                                            @include('powerpanel.partials.displayInfo',['Class_chrPublish'=>$Class_chrPublish,'display' => (isset($publicationsCategory->chrPublish)?$publicationsCategory->chrPublish:'Y')])
                                        @endif
                                    @else
                                        <label class="control-label form-label"> Publish/ Unpublish</label>
                                        @if($hasRecords > 0 && $isParent > 0)
                                            <input type="hidden" id="chrMenuDisplay" name="chrMenuDisplay" value="{{ $publicationsCategory->chrPublish }}">
                                            <p><b>NOTE:</b> This category is selected in {{ trans("publications-category::template.sidebar.publications") }} and also its a parent category so it can&#39;t be published/unpublished.</p>
                                        @elseif($isParent > 0)
                                            <input type="hidden" id="chrMenuDisplay" name="chrMenuDisplay" value="{{ $publicationsCategory->chrPublish }}">
                                            <p><b>NOTE:</b> This category is selected as Parent Category, so it can&#39;t be published/unpublished.</p>
                                        @elseif($hasRecords > 0)
                                            <input type="hidden" id="chrMenuDisplay" name="chrMenuDisplay" value="{{ $publicationsCategory->chrPublish }}">
                                            <p><b>NOTE:</b> This category is selected in {{ trans("publications-category::template.sidebar.publications") }}, so it can&#39;t be published/unpublished.</p>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                        {{-- Form Action --}}
                        <div class="col-md-12">
                            <div class="form-actions">
                                @if(isset($publicationsCategory->fkMainRecord) && $publicationsCategory->fkMainRecord != 0)
                                    <button type="submit" name="saveandexit" formmethod="post" class="btn btn-primary bg-gradient waves-effect waves-light btn-label me-1" value="saveandexit">
                                        <div class="flex-shrink-0">
                                            <i class="ri-check-line label-icon align-middle fs-20 me-2"></i>
                                        </div>
                                        {!! trans('publications-category::template.common.approve') !!}
                                    </button>
                                @else
                                    @if($userIsAdmin)
                                        <button type="submit" name="saveandedit" formmethod="post" class="btn btn-primary bg-gradient waves-effect waves-light btn-label me-1" value="saveandedit">
                                            <div class="flex-shrink-0">
                                                <i class="ri-save-line label-icon align-middle fs-20 me-2"></i>
                                            </div>
                                            {!! trans('publications-category::template.common.saveandedit') !!}
                                        </button>
                                        <button type="submit" name="saveandexit" formmethod="post" class="btn btn-secondary bg-gradient waves-effect waves-light btn-label me-1" value="saveandexit">
                                            <div class="flex-shrink-0">
                                                <i class="ri-save-3-line label-icon align-middle fs-20 me-2"></i>
                                            </div>
                                            {!! trans('publications-category::template.common.saveandexit') !!}
                                        </button>
                                    @else
                                        @if((isset($chrNeedAddPermission) && $chrNeedAddPermission == 'N') && (isset($charNeedApproval) && $charNeedApproval == 'N'))
                                            <button type="submit" name="saveandexit" formmethod="post" class="btn btn-secondary bg-gradient waves-effect waves-light btn-label me-1" value="saveandexit">
                                                <div class="flex-shrink-0">
                                                    <i class="ri-save-3-line label-icon align-middle fs-20 me-2"></i>
                                                </div>
                                                {!! trans('publications-category::template.common.saveandexit') !!}
                                            </button>
                                        @else
                                            <button type="submit" name="saveandexit" formmethod="post" class="btn btn-primary bg-gradient waves-effect waves-light btn-label me-1" value="approvesaveandexit">
                                                <div class="flex-shrink-0">
                                                    <i class="ri-save-line label-icon align-middle fs-20 me-2"></i>
                                                </div>
                                                {!! trans('publications-category::template.common.approvesaveandexit') !!}
                                            </button>
                                        @endif
                                    @endif
                                @endif
                                <a class="btn btn-danger bg-gradient waves-effect waves-light btn-label me-1" href="{{ url('powerpanel/career-category') }}">
                                    <div class="flex-shrink-0">
                                        <i class="ri-close-line label-icon align-middle fs-20 me-2"></i>
                                    </div>
                                    {{ trans('publications-category::template.common.cancel') }}
                                </a>
                                @if(isset($publicationsCategory) && !empty($publicationsCategory) && $userIsAdmin)
                                    <a style="display: none" class="btn btn-info bg-gradient waves-effect waves-light btn-label me-1" title="Preview" onClick="generatePreview('{{url('/previewpage?url='.(App\Helpers\MyLibrary::getFrontUri('publications-category')['uri']))}}');">
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
@endsection

@section('scripts')
<script type="text/javascript">
    window.site_url = '{!! url("/") !!}';
    var seoFormId = 'frmPublicationsCategory';
    var user_action = "{{ isset($publicationsCategory)?'edit':'add' }}";
    var moduleAlias = "{{ App\Helpers\MyLibrary::getFrontUri('publications-category')['moduleAlias'] }}";
    var preview_add_route = '{!! route("powerpanel.publications-category.addpreview") !!}';
    var previewForm = $('#frmPublicationsCategory');
    var isDetailPage = false;
    function generate_seocontent1(formname) {
        var Meta_Title = document.getElementById('title').value + " ";
                var Meta_Description = "" + document.getElementById('title').value + "";
                var Meta_Keyword = document.getElementById('title').value + "";
                $('#varMetaTitle').val(Meta_Title);
                // $('#varMetaKeyword').val(Meta_Keyword);
                $('#varMetaDescription').val(Meta_Description);
                $('#meta_title').html(Meta_Title);
                $('#meta_description').html(Meta_Description);
        }

    function OpenPassword(val) {
    if (val == 'PP') {
    $("#passid").show();
    } else {
    $("#passid").d-none();
    }
    }
</script>
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-taginput/bootstrap-tagsinput.min.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/publicationscategory/publications-category-validations.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/custom.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/seo-generator/seo-info-generator.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/custom-alias/alias-generator.js' }}" type="text/javascript"></script>
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js' }}" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS -->
@endsection