@section('css')
<link href="{{ $CDN_PATH.'resources/global/css/rank-button.css' }}" rel="stylesheet" type="text/css" />
@endsection
@extends('powerpanel.layouts.app')
@section('title')
{{Config::get('Constant.SITE_NAME')}} - PowerPanel
@endsection
@section('content')
@include('powerpanel.partials.builder-css') <!-- Builder include -->
@php $settings = json_decode(Config::get("Constant.MODULE.SETTINGS")); @endphp

<div class="row">
    <div class="col-md-12">
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
            {!! Form::open(['method' => 'post','id'=>'frmPageTemplate']) !!}
                <div class="card">
                    <div class="card-body p-30">
                        @if (isset($Cmspage))
                        <div class="row pagetitle-heading mb-4">
                            <div class="col-sm-11 col-11">
                                <h4 class="page-title fw-semibold m-0">{{ $breadcrumb['inner_title']}}</h4>
                            </div>
                            <div class="col-sm-1 col-1 lock-link">
                                @if (File::exists(base_path() . '/resources/views/powerpanel/partials/lockedpage.blade.php') != null)
                                @include('powerpanel.partials.lockedpage',['pagedata'=>$Cmspage])
                                @endif
                            </div>
                        </div>
                        @endif

                        <div class="row">
                            <div class="col-md-12">
                            <div class="{{ $errors->has('title') ? ' has-error' : '' }} form-md-line-input cm-floating mb-0">
                                <label class="form-label" for="title">Template Name <span aria-required="true" class="required"> * </span></label>
                                {!! Form::text('title', (isset($pageTemplate->varTemplateName)?$pageTemplate->varTemplateName:old('title')), array('maxlength'=>'150','class' => 'form-control input-sm hasAlias seoField maxlength-handler', 'data-url' => 'powerpanel/page_template','id' => 'title','autocomplete'=>'off')) !!}
                                <span style="color: red;">{{ $errors->first('title') }}</span>
                            </div>
                            <!-- code for alias -->
                            {!! Form::hidden(null, null, array('class' => 'hasAlias','data-url' => 'powerpanel/page_template')) !!}
                            {!! Form::hidden('alias', isset($pageTemplate->alias->varAlias)?$pageTemplate->alias->varAlias:old('alias'), array('class' => 'aliasField')) !!}
                            {!! Form::hidden('oldAlias', isset($pageTemplate->alias->varAlias)?$pageTemplate->alias->varAlias:old('alias')) !!}
                            {!! Form::hidden('fkMainRecord', isset($pageTemplate->fkMainRecord)?$pageTemplate->fkMainRecord:old('fkMainRecord')) !!}
                            {!! Form::hidden('previewId') !!}
                            <!-- code for alias -->
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body p-30">
                        @if (Config::get('Constant.DEFAULT_VISUAL') == 'Y')
                        <div id="body-roll">
                            @php $sections = []; @endphp
                            @if(isset($pageTemplate))
                                @php $sections = json_decode($pageTemplate->txtDesc); @endphp
                            @endif
                            <!-- Builder include -->
                            @php Powerpanel\VisualComposer\Controllers\VisualComposerController::page_section(['sections'=>$sections])@endphp
                        </div>
                        @else
                            @php if(isset($pageTemplate_highLight->txtDesc) && ($pageTemplate_highLight->txtDesc != $pageTemplate->txtDesc)){
                            $Class_Description = " highlitetext";
                            }else{
                            $Class_Description = "";
                            } @endphp
                            <div class="{{ $errors->has('contents') ? ' has-error' : '' }}">
                                <h4 class="form-section mb-3 form-label {!! $Class_Description !!}">{{ trans('pagetemplates::template.common.description') }}</h4>
                                {!! Form::textarea('contents',(isset($pageTemplate->txtDesc)?$pageTemplate->txtDesc:old('contents')) , array('class' => 'form-control cms','id'=>'txtDesc')) !!}
                            </div>
                        @endif
                    </div>
                </div>

                <div class="card">
                    <div class="card-body p-30">
                        <div class="{{ $errors->has('display') ? ' has-error' : '' }} ">
                            @if(isset($publishActionDisplay))
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <h4 class="form-section mb-3">Status</h4>
                                    @if(isset($pageTemplate_highLight->chrPublish) && ($pageTemplate_highLight->chrPublish != $pageTemplate->chrPublish))
                                    @php $Class_chrPublish = " highlitetext"; @endphp
                                    @else
                                    @php $Class_chrPublish = ""; @endphp
                                    @endif
                                    @if((isset($pageTemplate) && $pageTemplate->chrDraft == 'D'))
                                    @include('powerpanel.partials.displayInfo',['Class_chrPublish'=>$Class_chrPublish,'display' => (isset($pageTemplate->chrDraft)?$pageTemplate->chrDraft:'D')])
                                    @else
                                    @include('powerpanel.partials.displayInfo',['Class_chrPublish'=>$Class_chrPublish,'display' => (isset($pageTemplate->chrPublish)?$pageTemplate->chrPublish:'Y')])
                                    @endif
                                </div>
                                <div class="col-md-6 mb-3">
                                    <h4 class="form-section mb-3">Accessible</h4>
                                    @if(isset($pageTemplate->chrDisplayStatus) && $pageTemplate->chrDisplayStatus == 'PU')
                                    @php $Class_PU = 'checked="checked"';
                                    $Class_PR = ''; @endphp
                                    @elseif(isset($pageTemplate->chrDisplayStatus) && $pageTemplate->chrDisplayStatus == 'PR')
                                    @php $Class_PR = 'checked="checked"';
                                    $Class_PU = '';@endphp
                                    @else
                                    @php $Class_PU = 'checked="checked"';
                                    $Class_PR = ''; @endphp
                                    @endif
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="chrDisplayStatus" id="chrDisplayStatus0" value="PU" {{ $Class_PU }}>
                                        <label class="form-check-label" for="chrDisplayStatus0">All</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="chrDisplayStatus" id="chrDisplayStatus1" value="PR" {{ $Class_PR }}>
                                        <label class="form-check-label" for="chrDisplayStatus1">Only Me</label>
                                    </div>
                                    <div id="frmmail_membership_error"></div>
                                </div>
                            </div>
                            <span style="color: red;">{{ $errors->first('display') }}</span>
                            @endif

                            <div class="form-actions btn-bottom pt-1">
                                <div class="row">
                                    <div class="col-md-12">
                                        @if(isset($pageTemplate->fkMainRecord) && $pageTemplate->fkMainRecord != 0)
                                        <button type="submit" name="saveandexit" formmethod="post" class="btn btn-primary bg-gradient waves-effect waves-light btn-label me-1" value="saveandexit">
                                            <div class="flex-shrink-0">
                                                <i class="ri-save-line label-icon align-middle fs-20 me-2"></i>
                                            </div>
                                            {!! trans('pagetemplates::template.common.approve') !!}
                                        </button>
                                        @else
                                        @if($userIsAdmin)
                                        <button type="submit" name="saveandedit" formmethod="post" class="btn btn-primary bg-gradient waves-effect waves-light btn-label me-1" value="saveandedit">
                                            <div class="flex-shrink-0">
                                                <i class="ri-save-line label-icon align-middle fs-20 me-2"></i>
                                            </div>
                                            {!! trans('pagetemplates::template.common.saveandedit') !!}
                                        </button>
                                        <button type="submit" name="saveandexit" formmethod="post" class="btn btn-secondary bg-gradient waves-effect waves-light btn-label me-1" value="saveandexit">
                                            <div class="flex-shrink-0">
                                                <i class="ri-save-line label-icon align-middle fs-20 me-2"></i>
                                            </div>
                                            {!! trans('pagetemplates::template.common.saveandexit') !!}
                                        </button>
                                        @else
                                        @if((isset($chrNeedAddPermission) && $chrNeedAddPermission == 'N') && (isset($charNeedApproval) && $charNeedApproval == 'N'))
                                        <button type="submit" name="saveandexit" formmethod="post" class="btn btn-secondary bg-gradient waves-effect waves-light btn-label me-1" value="saveandexit">
                                            <div class="flex-shrink-0">
                                                <i class="ri-save-line label-icon align-middle fs-20 me-2"></i>
                                            </div>
                                            {!! trans('pagetemplates::template.common.saveandexit') !!}
                                        </button>
                                        @else
                                        <button type="submit" name="saveandexit" formmethod="post" class="btn btn-secondary bg-gradient waves-effect waves-light btn-label me-1" value="saveandexit">
                                            <div class="flex-shrink-0">
                                                <i class="ri-save-line label-icon align-middle fs-20 me-2"></i>
                                            </div>
                                            {!! trans('pagetemplates::template.common.saveandexit') !!}
                                        </button>
                                        @endif
                                        @endif
                                        @endif
                                        @php
                                        if(isset($_REQUEST['tab']) && $_REQUEST['tab'] == 'P'){
                                        $tab = '?tab=P';
                                        }else if(isset($_REQUEST['tab']) && $_REQUEST['tab'] == 'A'){
                                        $tab = '?tab=A';
                                        }else if(isset($_REQUEST['tab']) && $_REQUEST['tab'] == 'D'){
                                        $tab = '?tab=D';
                                        }else if(isset($_REQUEST['tab']) && $_REQUEST['tab'] == 'T'){
                                        $tab = '?tab=T';
                                        }else{
                                        $tab = '';
                                        }
                                        @endphp
                                        <a class="btn btn-danger bg-gradient waves-effect waves-light btn-label me-1" href="{{ url('powerpanel/page_template'.$tab) }}">
                                            <div class="flex-shrink-0">
                                                <i class="ri-close-line label-icon align-middle fs-20 me-2"></i>
                                            </div>
                                            {{ trans('pagetemplates::template.common.cancel') }}
                                        </a>
                                        <span id="previewid"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            {!! Form::close() !!}
        </div>
        @if (Config::get('Constant.DEFAULT_VISUAL') == 'Y')
            {{--@include('powerpanel.partials.dialog-maker') --}}
            @php Powerpanel\VisualComposer\Controllers\VisualComposerController::get_dialog_maker()@endphp
        @endif
        @endsection
        @section('scripts')
        @if (Config::get('Constant.DEFAULT_VISUAL') == 'Y')
            @php Powerpanel\VisualComposer\Controllers\VisualComposerController::get_visual_checkEditor()@endphp
        @else
            @include('powerpanel.partials.ckeditor',['config'=>'docsConfig'])
        @endif
    </div>
</div>

<script type="text/javascript">
    window.site_url = '{!! url("/") !!}';
    var seoFormId = 'frmPageTemplate';
    var user_action = "{{ isset($pageTemplate)?'edit':'add' }}";
    var moduleAlias = "{{ App\Helpers\MyLibrary::getFrontUri('page_template')['moduleAlias'] }}";
    var preview_add_route = '{!! route("powerpanel.page_template.addpreview") !!}';
    var previewForm = $('#frmPageTemplate');
    var isDetailPage = false;
    function generate_seocontent1(formname) {
        var Meta_Title = document.getElementById('title').value + "";
        var abcd = $('textarea#txtDesc').val();
        if (abcd != undefined){
            var def = abcd.replace(/<a(\s[^>]*)?>.*?<\/a>/ig, "");
            var abc = def.replace(/^(\s*)|(\s*)$/g, '').replace(/\s+/g, ' ');
            var outString1 = abc.replace(/(<([^>]+)>)/ig, "");
            var Meta_Description = outString1.substr(0, 200);
        } else{
            var Meta_Description = document.getElementById('title').value + "";
        }
        $('#varMetaTitle').val(Meta_Title);
        $('#varMetaDescription').val(Meta_Description);
        $('#meta_title').html(Meta_Title);
        $('#meta_description').html(Meta_Description);
    }
</script>
<script src="{{ $CDN_PATH.'resources/global/plugins/seo-generator/seo-info-generator.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/custom-alias/alias-generator.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'messages.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/pagetemplates/page_template_validations.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/custom.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js' }}" type="text/javascript"></script>

<script src="{{ $CDN_PATH.'resources/pages/scripts/custom.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/custom-alias/alias-generator.js' }}" type="text/javascript"></script>
<!-- BEGIN CORE PLUGINS -->
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap/js/bootstrap.min.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js' }}" type="text/javascript"></script>
<script src="{{ Config::get('Constant.CDN_PATH').'resources/pages/scripts/pages_password_rules.js' }}" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
@if (Config::get('Constant.DEFAULT_VISUAL') == 'Y')
    @php Powerpanel\VisualComposer\Controllers\VisualComposerController::get_builder_css_js()@endphp
@endif
@endsection