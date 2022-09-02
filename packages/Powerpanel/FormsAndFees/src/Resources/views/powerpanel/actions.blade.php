
@section('css')
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
                    {!! Form::open(['method' => 'post','id'=>'frmformsandfees']) !!}
                        {!! Form::hidden('fkMainRecord', isset($forms->fkMainRecord)?$forms->fkMainRecord:old('fkMainRecord')) !!}
                        @if(isset($forms))
                        @if (File::exists(base_path() . '/resources/views/powerpanel/partials/lockedpage.blade.php') != null)
                        @include('powerpanel.partials.lockedpage',['pagedata'=>$forms])
                        @endif
                        @endif
                        <div class="mb-3 @if($errors->first('sector')) has-error @endif form-md-line-input">
                            @if(isset($forms_highLight->varSector) && ($forms_highLight->varSector != $forms->varSector))
                                @php $Class_varSector = " highlitetext"; @endphp
                            @else
                                @php $Class_varSector = ""; @endphp
                            @endif
                            @include('shiledcmstheme::powerpanel.partials.sector-dropdown', ['selected_sector' => isset($forms->varSector)?$forms->varSector:'','Class_varSector' => $Class_varSector])
                            <span class="help-block">
                                {{ $errors->first('sector') }}
                            </span>
                        </div>

                        <div class="mb-3 @if($errors->first('title')) has-error @endif form-md-line-input">
                            @php if(isset($forms_highLight->varTitle) && ($forms_highLight->varTitle != $forms->varTitle)){
                            $Class_title = " highlitetext";
                            }else{
                            $Class_title = "";
                            } @endphp
                            <label class="form-label {!! $Class_title !!}" for="site_name">{{ trans('forms-and-fees::template.common.name') }} <span aria-required="true" class="required"> * </span></label>
                            {!! Form::text('title', isset($forms->varTitle) ? $forms->varTitle:old('title'), array('maxlength'=>'150','id'=>'title','placeholder' => trans('Title'),'class' => 'form-control hasAlias seoField maxlength-handler titlespellingcheck','autocomplete'=>'off')) !!}
                            <span class="help-block">
                                {{ $errors->first('title') }}
                            </span>
                        </div>
                        <div class="row" style="display: none">
                            <div class="col-md-12">
                                <!-- code for alias -->
                                {!! Form::hidden(null, null, array('class' => 'hasAlias','data-url' => 'powerpanel/forms-and-fees')) !!}
                                {!! Form::hidden('alias', isset($forms->alias->varAlias) ? $forms->alias->varAlias : old('alias'), array('class' => 'aliasField')) !!}
                                {!! Form::hidden('oldAlias', isset($forms->alias->varAlias)?$forms->alias->varAlias : old('alias')) !!}
                                {!! Form::hidden('previewId') !!}
                                <div class="mb-3 alias-group {{!isset($forms->alias)?'hide':''}}">
                                    <label class="form-label" for="Url">{{ trans('forms-and-fees::template.common.url') }} :</label>
                                    @if(isset($forms->alias->varAlias) && !$userIsAdmin)
                                    @php
                                    $aurl = App\Helpers\MyLibrary::getFrontUri('forms-and-fees')['uri'];
                                    @endphp
                                    <a  class="alias">{!! url("/") !!}</a>
                                    @else
                                    @if(auth()->user()->can('forms-and-fees-create'))
                                    <a href="javascript:void(0);" class="alias">{!! url("/") !!}</a>
                                    <a href="javascript:void(0);" class="editAlias" title="Edit">
                                        <i class="ri-pencil-line"></i>
                                    </a>
                                    <a class="without_bg_icon openLink" title="Open Link" onClick="generatePreview('{{url('/previewpage?url='.(App\Helpers\MyLibrary::getFrontUri('forms-and-fees')['uri']))}}');">
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
                        

                        <div class="row">
                            <div class="col-md-12">
                                @php if(isset($forms_highLight->txtDescription) && ($forms_highLight->txtDescription != $forms->txtDescription)){
                                    $Class_Description = " highlitetext";
                                    }else{
                                    $Class_Description = "";
                                } @endphp
                                <label class="form-label {!! $Class_Description !!}">Description</label>
                                <div class="mb-3 @if($errors->first('description')) has-error @endif form-md-line-input">
                                    @if (Config::get('Constant.DEFAULT_VISUAL') == 'Y')
                                    <div id="body-roll">
                                        @php
                                        $sections = [];
                                        @endphp
                                        @if(isset($forms))
                                        @php
                                        $sections = json_decode($forms->txtDescription);
                                        @endphp
                                        @endif
                                        <!-- Builder include -->
                                        @php
                                        Powerpanel\VisualComposer\Controllers\VisualComposerController::page_section(['sections'=>$sections])
                                        @endphp
                                    </div>
                                    @else
                                    {!! Form::textarea('description', isset($forms->txtDescription)?$forms->txtDescription:old('description'), array('placeholder' => trans('frmformsandfees::template.common.description'),'class' => 'form-control','id'=>'txtDescription')) !!}
                                    @endif
                                    <span class="help-block">{{ $errors->first('description') }}</span>
                                </div>
                            </div>
                        </div>
                        @if(Config::get('Constant.CHRSearchRank') == 'Y')
                        @if(isset($forms->intSearchRank))
                        @php $srank = $forms->intSearchRank; @endphp
                        @else
                        @php
                        $srank = null !== old('search_rank') ? old('search_rank') : 2 ;
                        @endphp
                        @endif
                        @if(isset($forms_highLight->intSearchRank) && ($forms_highLight->intSearchRank != $forms->intSearchRank))
                        @php $Class_intSearchRank = " highlitetext"; @endphp
                        @else
                        @php $Class_intSearchRank = ""; @endphp
                        @endif
                        <div class="row mb-3 d-none">
                            <div class="col-md-12">
                                <label class="{{ $Class_intSearchRank }} form-label">Search Ranking</label>
                                <a href="javascript:;" data-bs-toggle="tooltip" class="config" data-bs-placement="bottom" data-bs-content="{{ trans('frmformsandfees::template.common.SearchEntityTools') }}" title="{{ trans('frmformsandfees::template.common.SearchEntityTools') }}"><i class="fa fa-question"></i></a>
                                <div class="md-radio-inline search_rank">
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
                        <div class="row mb-3" style="display: none">
                            <div class="col-md-12">
                                <div class="nopadding">
                                    @include('powerpanel.partials.seoInfo',['form'=>'frmformsandfees','inf'=>isset($metaInfo)?$metaInfo:false,'inf_highLight'=> isset($metaInfo_highLight)?$metaInfo_highLight:false])
                                </div>
                            </div>
                        </div>
                        <h3 class="form-section">{{ trans('forms-and-fees::template.common.displayinformation') }}</h3>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3 @if($errors->first('order')) has-error @endif form-md-line-input">
                                    @php
                                    $display_order_attributes = array('class' => 'form-control','maxlength'=>5,'placeholder'=>trans('forms-and-fees::template.common.displayorder'),'autocomplete'=>'off');
                                    @endphp
                                    @if(isset($forms_highLight->intDisplayOrder) && ($forms_highLight->intDisplayOrder != $forms->intDisplayOrder))
                                    @php $Class_intDisplayOrder = " highlitetext"; @endphp
                                    @else
                                    @php $Class_intDisplayOrder = ""; @endphp
                                    @endif
                                    <label class="form-label {{ $Class_intDisplayOrder }}" for="site_name">{{ trans('forms-and-fees::template.common.displayorder') }} <span aria-required="true" class="required"> * </span></label>
                                    {!! Form::text('order', isset($forms->intDisplayOrder)?$forms->intDisplayOrder:1, $display_order_attributes) !!}
                                    <span style="color: red;">
                                        {{ $errors->first('order') }}
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                @if(isset($forms_highLight->chrPublish) && ($forms_highLight->chrPublish != $forms->chrPublish))
                                    @php $Class_chrPublish = " highlitetext"; @endphp
                                @else
                                    @php $Class_chrPublish = ""; @endphp
                                @endif

                                @if(isset($forms) && $forms->chrAddStar == 'Y')
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="control-label form-label"> Publish/ Unpublish</label>
                                            <input type="hidden" id="chrMenuDisplay" name="chrMenuDisplay" value="{{ isset($forms->chrPublish) ? $forms->chrPublish : '' }}">
                                            <p><b>NOTE:</b> This record is in Approval Request , so it can&#39;t be published/unpublished.</p>
                                        </div>
                                    </div>
                                @elseif(isset($forms) && $forms->chrDraft == 'D' && $forms->chrAddStar != 'Y')
                                    @include('powerpanel.partials.displayInfo',['Class_chrPublish'=>$Class_chrPublish,'display' => (isset($forms->chrDraft)?$forms->chrDraft:'D')])
                                @else
                                    @include('powerpanel.partials.displayInfo',['Class_chrPublish'=>$Class_chrPublish,'display' => (isset($forms->chrPublish)?$forms->chrPublish:'Y')])
                                @endif
                            </div>

                        </div>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-12">
                                    @if(isset($forms->fkMainRecord) && $forms->fkMainRecord != 0)
                                    <button type="submit" name="saveandexit" class="btn btn-primary" value="saveandexit">{!! trans('forms-and-fees::template.common.approve') !!}</button>
                                    @else
                                    @if($userIsAdmin)
                                    <button type="submit" name="saveandedit" class="btn btn-primary" value="saveandedit">{!! trans('forms-and-fees::template.common.saveandedit') !!}</button>
                                    <button type="submit" name="saveandexit" class="btn btn-primary" value="saveandexit">{!! trans('forms-and-fees::template.common.saveandexit') !!}</button>
                                    @else
                                    @if((isset($chrNeedAddPermission) && $chrNeedAddPermission == 'N') && (isset($charNeedApproval) && $charNeedApproval == 'N'))
                                    <button type="submit" name="saveandexit" class="btn btn-primary" value="saveandexit">{!! trans('forms-and-fees::template.common.saveandexit') !!}</button>
                                    @else
                                    <button type="submit" name="saveandexit" class="btn btn-primary" value="approvesaveandexit">{!! trans('forms-and-fees::template.common.approvesaveandexit') !!}</button>
                                    @endif
                                    @endif
                                    @endif
                                    <a class="btn btn-danger" href="{{ url('powerpanel/forms-and-fees') }}">{{ trans('forms-and-fees::template.common.cancel') }}</a>
                                    @if(isset($forms) && !empty($forms))
                                    &nbsp;<a style="display: none" class="btn btn-primary" title="Preview" onClick="generatePreview('{{url('/previewpage?url='.(App\Helpers\MyLibrary::getFrontUri('forms-and-fees')['uri']))}}');">Preview</a>
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
    var seoFormId = 'frmformsandfees';
    var user_action = "{{ isset($forms)?'edit':'add' }}";
    var moduleAlias = "{{ App\Helpers\MyLibrary::getFrontUri('forms-and-fees')['moduleAlias'] }}";
    var preview_add_route = '{!! route("powerpanel.forms-and-fees.addpreview") !!}';
    var previewForm = $('#frmformsandfees');
    var isDetailPage = true;
    var formname = 'frmformsandfees';
    function generate_seocontent1(formname) {
        var Meta_Title = document.getElementById('title').value + "";
        var abcd = document.getElementById('title').value + "";
        var def = abcd.replace(/<a(\s[^>]*)?>.*?<\/a>/ig, "")
        var abc = def.replace(/^(\s*)|(\s*)$/g, '').replace(/\s+/g, ' ');
        var outString1 = abc.replace(/(<([^>]+)>)/ig, "");
        var Meta_Description = "" + document.getElementById('title').value;
        var Meta_Keyword = "";
        $('#varMetaTitle').val(Meta_Title);
        $('#varMetaDescription').val(Meta_Description);
        $('#meta_title').html(Meta_Title);
        $('#meta_description').html(Meta_Description);
    }
</script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/forms-and-fees/forms-and-fees_validations.js' }}" type="text/javascript"></script>

<script src="{{ $CDN_PATH.'resources/pages/scripts/custom.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/custom-alias/alias-generator.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/seo-generator/seo-info-generator.js' }}" type="text/javascript"></script>
<script src="{{ Config::get('Constant.CDN_PATH').'resources/pages/scripts/pages_password_rules.js' }}" type="text/javascript"></script>
<script type="text/javascript">
    function OpenPassword(val) {
    if (val == 'PP') {
    $("#passid").show();
    } else {
    $("#passid").hide();
    }
    }
</script>
@if (Config::get('Constant.DEFAULT_VISUAL') == 'Y')
@php Powerpanel\VisualComposer\Controllers\VisualComposerController::get_builder_css_js()@endphp
@endif
@endsection