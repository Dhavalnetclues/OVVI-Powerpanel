@section('css')
<link href="{{ $CDN_PATH.'resources/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css' }}" rel="stylesheet" type="text/css" />
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
                    {!! Form::open(['method' => 'post','id'=>'frmOrganizations']) !!}
                        {!! Form::hidden('fkMainRecord', isset($organization->fkMainRecord)?$organization->fkMainRecord:old('fkMainRecord')) !!}
                        <div class="form-body">
                            @if(isset($organization))
                            @if (File::exists(base_path() . '/resources/views/powerpanel/partials/lockedpage.blade.php') != null)
                            @include('powerpanel.partials.lockedpage',['pagedata'=>$organization])
                            @endif
                            @endif
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        @if(isset($organizationHighLight->intParentCategoryId) && ($organizationHighLight->intParentCategoryId != $organization->intParentCategoryId))
                                        @php $Class_intParentCategoryId = " highlitetext"; @endphp
                                        @else
                                        @php $Class_intParentCategoryId = ""; @endphp
                                        @endif
                                        <label class="form-label {{ $Class_intParentCategoryId }}" for="parent_category_id">{{ trans('organizations::template.organizationsModule.selectparentorganization') }}</label>
                                        @php echo $categories; @endphp
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3 @if($errors->first('title')) has-error @endif form-md-line-input">
                                        @if(isset($organizationHighLight->varTitle) && ($organizationHighLight->varTitle != $organization->varTitle))
                                        @php $Class_varTitle = " highlitetext"; @endphp
                                        @else
                                        @php $Class_varTitle = ""; @endphp
                                        @endif
                                        <label class="form-label {{ $Class_varTitle }}" for="site_name">{{ trans('organizations::template.common.name') }} <span aria-required="true" class="required"> * </span></label>
                                        {!! Form::text('title', isset($organization->varTitle) ? $organization->varTitle : old('title'), array('maxlength' => 150, 'class' => 'form-control hasAlias seoField maxlength-handler titlespellingcheck','data-url' => 'powerpanel/organizations','placeholder' => trans('organizations::template.common.name'),'autocomplete'=>'off')) !!}
                                        <span class="help-block">
                                            {{ $errors->first('title') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3 @if($errors->first('Designation')) has-error @endif form-md-line-input">
                                @php if(isset($organization_highLight->varDesignation) && ($organization_highLight->varDesignation != $organization->varDesignation)){
                                $Class_Designation = " highlitetext";
                                }else{
                                $Class_Designation = "";
                                } @endphp
                                <label class="form-label {!! $Class_Designation !!}" for="site_name">Designation </label>
                                {!! Form::text('Designation', isset($organization->varDesignation) ? $organization->varDesignation:old('Designation'), array('maxlength'=>'150','placeholder' => 'Designation','class' => 'form-control seoField maxlength-handler designationspellingcheck','autocomplete'=>'off')) !!}
                                <span class="help-block">
                                    {{ $errors->first('Designation') }}
                                </span>
                            </div>
                            @if(Config::get('Constant.CHRSearchRank') == 'Y')
                            @if(isset($organization->intSearchRank))
                            @php $srank = $organization->intSearchRank; @endphp
                            @else
                            @php
                            $srank = null !== old('search_rank') ? old('search_rank') : 2 ;
                            @endphp
                            @endif
                            @if(isset($organizationHighLight->intSearchRank) && ($organizationHighLight->intSearchRank != $organization->intSearchRank))
                            @php $Class_intSearchRank = " highlitetext"; @endphp
                            @else
                            @php $Class_intSearchRank = ""; @endphp
                            @endif
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="{{ $Class_intSearchRank }} form-label">Search Ranking</label>
                                    <a href="javascript:;" data-bs-toggle="tooltip" class="config" data-bs-placement="bottom" data-bs-content="{{ trans('organizations::template.common.SearchEntityTools') }}" title="{{ trans('organizations::template.common.SearchEntityTools') }}"><i class="fa fa-question"></i></a>
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
                            <h3 class="form-section">{{ trans('organizations::template.common.displayinformation') }}</h3>
                            <div class="row">
                                <div class="col-md-6">
                                    @php
                                    $display_order_attributes = array('class' => 'form-control','maxlength'=>5,'placeholder'=>trans('organizations::template.common.displayorder'),'autocomplete'=>'off');
                                    @endphp
                                    <div class="mb-3 @if($errors->first('display_order')) has-error @endif form-md-line-input">
                                        @if(isset($organizationHighLight->intDisplayOrder) && ($organizationHighLight->intDisplayOrder != $organization->intDisplayOrder))
                                        @php $Class_intDisplayOrder = " highlitetext"; @endphp
                                        @else
                                        @php $Class_intDisplayOrder = ""; @endphp
                                        @endif
                                        <label class="form-label {{ $Class_intDisplayOrder }}" class="site_name">{{ trans('organizations::template.common.displayorder') }} <span aria-required="true" class="required"> * </span></label>
                                        {!! Form::text('display_order', isset($organization->intDisplayOrder)?$organization->intDisplayOrder : '1', $display_order_attributes) !!}
                                        <span class="help-block">
                                            <strong>{{ $errors->first('display_order') }}</strong>
                                        </span>
                                    </div>
                                </div>
                                @if(isset($organizationHighLight->chrPublish) && ($organizationHighLight->chrPublish != $organization->chrPublish))
                                @php $Class_chrPublish = " highlitetext"; @endphp
                                @else
                                @php $Class_chrPublish = ""; @endphp
                                @endif
                                @if($isParent==0)
                                <div class="col-md-6">
                                    @include('powerpanel.partials.displayInfo',['Class_chrPublish'=>$Class_chrPublish,'display' => isset($organization->chrPublish)?$organization->chrPublish:null])
                                </div>
                                @else
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="control-label form-label"> Publish/ Unpublish</label>
                                        @if($isParent > 0)
                                        <p><b>NOTE:</b> This organization is selected as parent organization in other record so it can&#39;t be published/unpublished..</p>
                                        @endif
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-12">
                                    @if(isset($organization->fkMainRecord) && $organization->fkMainRecord != 0)
                                    <button type="submit" name="saveandexit" class="btn btn-primary" value="saveandexit">{!! trans('organizations::template.common.approve') !!}</button>
                                    @else
                                    @if($userIsAdmin)
                                    <button type="submit" name="saveandedit" class="btn btn-primary" value="saveandedit">{!! trans('organizations::template.common.saveandedit') !!}</button>
                                    <button type="submit" name="saveandexit" class="btn btn-primary" value="saveandexit">{!! trans('organizations::template.common.saveandexit') !!}</button>
                                    @else
                                    @if((isset($chrNeedAddPermission) && $chrNeedAddPermission == 'N') && (isset($charNeedApproval) && $charNeedApproval == 'N'))
                                    <button type="submit" name="saveandexit" class="btn btn-primary" value="saveandexit">{!! trans('organizations::template.common.saveandexit') !!}</button>
                                    @else
                                    <button type="submit" name="saveandexit" class="btn btn-primary" value="approvesaveandexit">{!! trans('organizations::template.common.approvesaveandexit') !!}</button>
                                    @endif
                                    @endif
                                    @endif
                                    <a class="btn btn-danger" href="{{ url('powerpanel/organizations') }}">{{ trans('organizations::template.common.cancel') }}</a>
                                </div>
                            </div>
                        </div>
                    {!! Form::close() !!}
                </div> 
            </div>
        </div>
    </div>
</div><!--end row-->

@endsection
@section('scripts')
<script type="text/javascript">
    window.site_url = '{!! url("/") !!}';
    var seoFormId = 'frmOrganization';
    var user_action = "{{ isset($organization)?'edit':'add' }}";
    var moduleAlias = 'organizations';
</script>
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/custom.js' }}" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/seo-generator/seo-info-generator.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/organizations/organizations_validations.js' }}" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS -->
@endsection