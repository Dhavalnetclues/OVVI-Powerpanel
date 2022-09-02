@section('css')
    <link href="{{ $CDN_PATH.'resources/global/plugins/bootstrap-taginput/bootstrap-tagsinput.css' }}" rel="stylesheet" type="text/css" />
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
        
        <div class="card">
            <div class="card-body">
                <div class="live-preview">
                    {!! Form::open(['method' => 'post','id'=>'frmCompanies']) !!}
                        {!! Form::hidden('fkMainRecord', isset($companies->fkMainRecord)?$companies->fkMainRecord:old('fkMainRecord')) !!}
                        <div class="mb-3 @if($errors->first('tag_line')) has-error @endif form-md-line-input">
                            @if(isset($companies))
                            @if (File::exists(base_path() . '/resources/views/powerpanel/partials/lockedpage.blade.php') != null)
                            @include('powerpanel.partials.lockedpage',['pagedata'=>$companies])
                            @endif
                            @endif

                            <!-- Sector type -->
                            <div class="mb-3 @if($errors->first('sector')) has-error @endif form-md-line-input">
                                @if(isset($companies_highLight->varSector) && ($companies_highLight->varSector != $companies->varSector))
                                    @php $Class_varSector = " highlitetext"; @endphp
                                @else
                                    @php $Class_varSector = ""; @endphp
                                @endif
                                @include('shiledcmstheme::powerpanel.partials.sector-dropdown', ['selected_sector' => isset($companies->varSector)?$companies->varSector:'','Class_varSector' => $Class_varSector])
                                <span class="help-block">
                                    {{ $errors->first('sector') }}
                                </span>
                            </div>
                        </div>

                        <div class="mb-3 @if($errors->first('title')) has-error @endif form-md-line-input">
                            @php if(isset($companies_highLight->varTitle) && ($companies_highLight->varTitle != $companies->varTitle)){
                            $Class_title = " highlitetext";
                            }else{
                            $Class_title = "";
                            } @endphp
                            <label class="form-label {!! $Class_title !!}" for="site_name">{{ trans('companies::template.common.title') }} <span aria-required="true" class="required"> * </span></label>
                            {!! Form::text('title', isset($companies->varTitle) ? $companies->varTitle:old('title'), array('maxlength'=>'150','id'=>'title','placeholder' => trans('companies::template.common.title'),'class' => 'form-control hasAlias seoField maxlength-handler titlespellingcheck','autocomplete'=>'off')) !!}
                            <span class="help-block">
                                {{ $errors->first('title') }}
                            </span>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <!-- code for alias -->
                                {!! Form::hidden(null, null, array('class' => 'hasAlias','data-url' => 'powerpanel/companies')) !!}
                                {!! Form::hidden('alias', isset($companies->alias->varAlias) ? $companies->alias->varAlias : old('alias'), array('class' => 'aliasField')) !!}
                                {!! Form::hidden('oldAlias', isset($companies->alias->varAlias)?$companies->alias->varAlias : old('alias')) !!}
                                {!! Form::hidden('previewId') !!}
                                <div class="mb-3 alias-group {{!isset($companies->alias)?'hide':''}}">
                                    <label class="form-label" for="Url">{{ trans('companies::template.common.url') }} :</label>
                                    @if(isset($companies->alias->varAlias) && !$userIsAdmin)
                                    <a class="alias">
                                    {!! url("/") !!}
                                    </a>
                                    @else
                                    @if(auth()->user()->can('companies-create'))
                                    <a href="javascript:void(0);" class="alias">{!! url("/") !!}</a>
                                    <a href="javascript:void(0);" class="editAlias" title="Edit">
                                        <i class="ri-pencil-line"></i>
                                    </a>
                                    <a class="without_bg_icon openLink" title="Open Link" onClick="generatePreview('{{ url('/previewpage?url='.(App\Helpers\MyLibrary::getFrontUri('companies')['uri'])) }}');">
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

                        <div class="row hide">
                            <div class="col-md-12">
                                <div class="mb-3 @if($errors->first('short_description')) has-error @endif form-md-line-input">
                                    @php if(isset($companies_highLight->varShortDescription) && ($companies_highLight->varShortDescription != $companies->varShortDescription)){
                                    $Class_ShortDescription = " highlitetext";
                                    }else{
                                    $Class_ShortDescription = "";
                                    } @endphp
                                    <label class="form-label {!! $Class_ShortDescription !!}">Short Description<span aria-required="true" class="required"> * </span></label>
                                    {!! Form::textarea('short_description', isset($companies->varShortDescription)?$companies->varShortDescription:old('short_description'), array('maxlength' => isset($settings->short_desc_length)?$settings->short_desc_length:500,'class' => 'form-control seoField maxlength-handler shortdescspellingcheck','id'=>'varShortDescription','rows'=>'3','placeholder'=>'Short Description')) !!}
                                    <span class="help-block">{{ $errors->first('short_description') }}</span>
                                </div>
                            </div>
                        </div>

                        <h3 class="form-section">{{ trans('companies::template.common.displayinformation') }}</h3>
                        <div class="row">
                            <div class="col-md-6">
                                @php
                                $display_order_attributes = array('class' => 'form-control','maxlength'=>10,'placeholder'=>trans('companies::template.common.displayorder'),'autocomplete'=>'off');
                                @endphp
                                <div class="mb-3 @if($errors->first('display_order')) has-error @endif form-md-line-input">
                                    <label class="form-label" for="site_name">{{ trans('companies::template.common.displayorder') }}<span aria-required="true" class="required"> * </span></label>
                                    {!! Form::text('display_order',  isset($companies->intDisplayOrder)?$companies->intDisplayOrder:$total, $display_order_attributes) !!}
                                    <span class="help-block">
                                        {{ $errors->first('display_order') }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                @if(isset($companies_highLight->chrPublish) && ($companies_highLight->chrPublish != $companies->chrPublish))
                                    @php $Class_chrPublish = " highlitetext"; @endphp
                                @else
                                    @php $Class_chrPublish = ""; @endphp
                                @endif

                                @if(isset($companies) && $companies->chrAddStar == 'Y')
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="control-label form-label"> Publish/ Unpublish</label>
                                            <input type="hidden" id="chrMenuDisplay" name="chrMenuDisplay" value="{{ isset($companies->chrPublish) ? $companies->chrPublish : '' }}">
                                            <p><b>NOTE:</b> This record is in Approval Request , so it can&#39;t be published/unpublished.</p>
                                        </div>
                                    </div>
                                @elseif(isset($companies) && $companies->chrDraft == 'D' && $companies->chrAddStar != 'Y')
                                    @include('powerpanel.partials.displayInfo',['Class_chrPublish'=>$Class_chrPublish,'display' => (isset($companies->chrDraft)?$companies->chrDraft:'D')])
                                @else
                                    @include('powerpanel.partials.displayInfo',['Class_chrPublish'=>$Class_chrPublish,'display' => (isset($companies->chrPublish)?$companies->chrPublish:'Y')])
                                @endif
                            </div>
                        </div>

                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-12">
                                    @if(isset($companies->fkMainRecord) && $companies->fkMainRecord != 0)
                                    <button type="submit" name="saveandexit" class="btn btn-primary" value="saveandexit">{!! trans('companies::template.common.approve') !!}</button>
                                    @else
                                    @if($userIsAdmin)
                                    <button type="submit" name="saveandedit" class="btn btn-primary" value="saveandedit">{!! trans('companies::template.common.saveandedit') !!}</button>
                                    <button type="submit" name="saveandexit" class="btn btn-primary" value="saveandexit">{!! trans('companies::template.common.saveandexit') !!}</button>
                                    @else
                                    @if((isset($chrNeedAddPermission) && $chrNeedAddPermission == 'N') && (isset($charNeedApproval) && $charNeedApproval == 'N'))
                                    <button type="submit" name="saveandexit" class="btn btn-primary" value="saveandexit">{!! trans('companies::template.common.saveandexit') !!}</button>
                                    @else
                                    <button type="submit" name="saveandexit" class="btn btn-primary" value="approvesaveandexit">{!! trans('companies::template.common.approvesaveandexit') !!}</button>
                                    @endif
                                    @endif
                                    @endif
                                    <a class="btn btn-danger" href="{{ url('powerpanel/companies') }}">{{ trans('companies::template.common.cancel') }}</a>
                                    @if(isset($companies) && !empty($companies) && $userIsAdmin)
                                    &nbsp;<a style="display: none" class="btn btn-primary" title="Preview" onClick="generatePreview('{{url('/previewpage?url='.(App\Helpers\MyLibrary::getFrontUri('companies')['uri']))}}');">Preview</a>
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
    @php Powerpanel\VisualComposer\Controllers\VisualComposerController::get_visual_checkEditor()@endphp
@else
    @include('powerpanel.partials.ckeditor',['config'=>'docsConfig'])
@endif
@endsection
@section('scripts')
<script type="text/javascript">
            window.site_url = '{!! url("/") !!}';
            var seoFormId = 'frmCompanies';
            var user_action = "{{ isset($companies)?'edit':'add' }}";
            var moduleAlias = "{{ App\Helpers\MyLibrary::getFrontUri('companies')['moduleAlias'] }}";
            var preview_add_route = '{!! route("powerpanel.companies.addpreview") !!}';
            var previewForm = $('#frmCompanies');
            var isDetailPage = true;
            function generate_seocontent1(formname) {
            var Meta_Title = document.getElementById('title').value + "";
                    var def = abcd.replace(/<a(\s[^>]*)?>.*?<\/a>/ig, "")
                    var abc = def.replace(/^(\s*)|(\s*)$/g, '').replace(/\s+/g, ' ');
                    var outString1 = abc.replace(/(<([^>]+)>)/ig, "");
                    var Meta_Description = outString1.substr(0, 200);
                    var Meta_Keyword = "";
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
    $("#passid").hide();
    }
    }
</script>
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-taginput/bootstrap-tagsinput.min.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/companies/companies_validations.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/custom.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/custom-alias/alias-generator.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/seo-generator/seo-info-generator.js' }}" type="text/javascript"></script>
@if (Config::get('Constant.DEFAULT_VISUAL') == 'Y')
    @php Powerpanel\VisualComposer\Controllers\VisualComposerController::get_builder_css_js()@endphp
@endif
@endsection