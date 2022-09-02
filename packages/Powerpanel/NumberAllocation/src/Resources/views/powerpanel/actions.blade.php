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
                    {!! Form::open(['method' => 'post','id'=>'frmNumberAllocation']) !!}
                        {!! Form::hidden('fkMainRecord', isset($numberAllocation->fkMainRecord)?$numberAllocation->fkMainRecord:old('fkMainRecord')) !!}
                        <div class="mb-3 @if($errors->first('tag_line')) has-error @endif form-md-line-input">
                                @if(isset($numberAllocation))
                            @if (File::exists(base_path() . '/resources/views/powerpanel/partials/lockedpage.blade.php') != null)
                            @include('powerpanel.partials.lockedpage',['pagedata'=>$numberAllocation])
                            @endif
                            @endif

                            <!-- Sector type -->
                            <div class="mb-3 @if($errors->first('sector')) has-error @endif form-md-line-input">
                                    @if(isset($numberAllocation_highLight->varSector) && ($numberAllocation_highLight->varSector != $numberAllocation->varSector))
                                    @php $Class_varSector = " highlitetext"; @endphp
                                    @else
                                    @php $Class_varSector = ""; @endphp
                                    @endif
                                    @include('shiledcmstheme::powerpanel.partials.sector-dropdown', ['selected_sector' => isset($numberAllocation->varSector)?$numberAllocation->varSector:'','Class_varSector' => $Class_varSector])
                                <span class="help-block">
                                    {{ $errors->first('sector') }}
                                </span>
                            </div>
                        </div>
                        <div class="mb-3 @if($errors->first('nxx')) has-error @endif form-md-line-input">
                            @php if(isset($numberAllocation_highLight->nxx) && ($numberAllocation_highLight->nxx != $numberAllocation->nxx)){
                            $Class_title = " highlitetext";
                            }else{
                            $Class_title = "";
                            } @endphp
                            <label class="form-label {!! $Class_title !!}" for="site_name">{{ trans('number-allocation::template.common.nxx') }} <span aria-required="true" class="required"> * </span></label>
                            {!! Form::text('nxx', isset($numberAllocation->nxx) ? $numberAllocation->nxx:old('nxx'), array('maxlength'=>'150','id'=>'title','placeholder' => trans('number-allocation::template.common.nxx'),'class' => 'form-control seoField maxlength-handler titlespellingcheck','autocomplete'=>'off')) !!}
                            <span class="help-block">
                                {{ $errors->first('nxx') }}
                            </span>
                        </div>

                        <div class="mb-3 form-md-line-input">
                            @php
                                if(isset($numberAllocation_highLight->intFKCategory) && ($numberAllocation_highLight->intFKCategory != $numberAllocation->intFKCategory)){
                                    $Class_title = " highlitetext";
                                }else{
                                    $Class_title = "";
                                }
                                $currentCatAlias = '';
                            @endphp
                            <label class="form-label {{ $Class_title }}" for="site_name">Select Company Category <span aria-required="true" class="required"> * </span></label>
                            <select class="form-control" data-choices name="category_id" id="category_id">
                                <option value="">Select Company Category</option>
                                @foreach ($companyCategory as $cat)
                                @php $permissionName = 'number-allocation-list' @endphp
                                @php $selected = ''; @endphp
                                @if(isset($numberAllocation->intFKCategory))
                                @if($cat['id'] == $numberAllocation->intFKCategory)
                                @php $selected = 'selected'; $currentCatAlias = '';  @endphp
                                @endif
                                @endif
                                <option value="{{ $cat['id'] }}" data-categryalias="" {{ $selected }} >{{ $cat['varTitle'] }}</option>
                                @endforeach
                                <option value="other" data-categryalias="">Other (Add New)</option>
                            </select>
                            <span class="help-block">
                                {{ $errors->first('category') }}
                            </span>
                        </div>
                        <div class="mb-3 @if($errors->first('companyCategory')) has-error @endif form-md-line-input" id="companyCategoryBlock" style="display:none;">
                            <label class="form-label {!! $Class_title !!}" for="site_name">Company Category<span aria-required="true" class="required"> * </span></label>
                            {!! Form::text('companyCategory', '', array('maxlength'=>'300','id'=>'companyCategory','placeholder' => 'Company Category','class' => 'form-control seoField maxlength-handler titlespellingcheck','autocomplete'=>'off')) !!}
                            <span class="help-block">
                                {{ $errors->first('companyCategory') }}
                            </span>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3 @if($errors->first('service')) has-error @endif form-md-line-input">
                                    @php if(isset($numberAllocation_highLight->service) && ($numberAllocation_highLight->service != $numberAllocation->service)){
                                    $Class_service = " highlitetext";
                                    }else{
                                    $Class_service = "";
                                    } @endphp
                                    <label class="form-label {!! $Class_service !!}">Service<span aria-required="true" class="required"> * </span></label>
                                    {!! Form::text('service', isset($numberAllocation->service)?$numberAllocation->service:old('service'), array('maxlength' => 150,'class' => 'form-control seoField maxlength-handler shortdescspellingcheck','id'=>'service','placeholder'=>'Service')) !!}
                                    <span class="help-block">{{ $errors->first('service') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3 @if($errors->first('note')) has-error @endif form-md-line-input">
                                    @php if(isset($numberAllocation_highLight->note) && ($numberAllocation_highLight->note != $numberAllocation->note)){
                                    $Class_note = " highlitetext";
                                    }else{
                                    $Class_note = "";
                                    } @endphp
                                    <label class="form-label {!! $Class_note !!}">note</label>
                                    {!! Form::textarea('note', isset($numberAllocation->note)?$numberAllocation->note:old('note'), array('maxlength' => 500,'class' => 'form-control seoField maxlength-handler shortdescspellingcheck', 'row'=>'2', 'id'=>'note','placeholder'=>'Note')) !!}
                                    <span class="help-block">{{ $errors->first('note') }}</span>
                                </div>
                            </div>
                        </div>

                        <h3 class="form-section">{{ trans('number-allocation::template.common.displayinformation') }}</h3>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="@if($errors->first('display_order')) has-error @endif form-md-line-input">
                                    @php
                                    $display_order_attributes = array('class' => 'form-control','maxlength'=>5,'placeholder'=>trans('number-allocation::template.common.displayorder'),'autocomplete'=>'off');
                                    @endphp
                                    @php if(isset($numberAllocation_highLight->intDisplayOrder) && ($numberAllocation_highLight->intDisplayOrder != $numberAllocation->intDisplayOrder)){
                                    $Class_displayorder = " highlitetext";
                                    }else{
                                    $Class_displayorder = "";
                                    } @endphp
                                    <label class="form-label {!! $Class_displayorder !!}" for="site_name">{{ trans('department::template.common.displayorder') }} <span aria-required="true" class="required"> * </span></label>
                                    {!! Form::text('display_order', isset($numberAllocation->intDisplayOrder)?$numberAllocation->intDisplayOrder:'1', $display_order_attributes) !!}
                                    <span style="color: red;">
                                        {{ $errors->first('display_order') }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                @if(isset($numberAllocation_highLight->chrPublish) && ($numberAllocation_highLight->chrPublish != $numberAllocation->chrPublish))
                                @php $Class_chrPublish = " highlitetext"; @endphp
                                @else
                                @php $Class_chrPublish = ""; @endphp
                                @endif

                                @if(isset($numberAllocation) && $numberAllocation->chrAddStar == 'Y')
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="control-label form-label"> Publish/ Unpublish</label>
                                            <input type="hidden" id="chrMenuDisplay" name="chrMenuDisplay" value="{{ isset($numberAllocation->chrPublish) ? $numberAllocation->chrPublish : '' }}">
                                            <p><b>NOTE:</b> This record is in Approval Request , so it can&#39;t be published/unpublished.</p>
                                        </div>
                                    </div>
                                @elseif(isset($numberAllocation) && $numberAllocation->chrDraft == 'D' && $numberAllocation->chrAddStar != 'Y')
                                    @include('powerpanel.partials.displayInfo',['Class_chrPublish'=>$Class_chrPublish,'display' => (isset($numberAllocation->chrDraft)?$numberAllocation->chrDraft:'D')])
                                @else
                                    @include('powerpanel.partials.displayInfo',['Class_chrPublish'=>$Class_chrPublish,'display' => (isset($numberAllocation->chrPublish)?$numberAllocation->chrPublish:'Y')])
                                @endif
                            </div>
                        </div>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-12">
                                    @if(isset($numberAllocation->fkMainRecord) && $numberAllocation->fkMainRecord != 0)
                                    <button type="submit" name="saveandexit" class="btn btn-primary" value="saveandexit">{!! trans('number-allocation::template.common.approve') !!}</button>
                                    @else
                                    @if($userIsAdmin)
                                    <button type="submit" name="saveandedit" class="btn btn-primary" value="saveandedit">{!! trans('number-allocation::template.common.saveandedit') !!}</button>
                                    <button type="submit" name="saveandexit" class="btn btn-primary" value="saveandexit">{!! trans('number-allocation::template.common.saveandexit') !!}</button>
                                    @else
                                    @if((isset($chrNeedAddPermission) && $chrNeedAddPermission == 'N') && (isset($charNeedApproval) && $charNeedApproval == 'N'))
                                    <button type="submit" name="saveandexit" class="btn btn-primary" value="saveandexit">{!! trans('number-allocation::template.common.saveandexit') !!}</button>
                                    @else
                                    <button type="submit" name="saveandexit" class="btn btn-primary" value="approvesaveandexit">{!! trans('number-allocation::template.common.approvesaveandexit') !!}</button>
                                    @endif
                                    @endif
                                    @endif
                                    <a class="btn btn-danger" href="{{ url('powerpanel/number-allocation') }}">{{ trans('number-allocation::template.common.cancel') }}</a>
                                    @if(isset($numberAllocation) && !empty($numberAllocation) && $userIsAdmin)
                                    &nbsp;<a style="display: none" class="btn btn-primary" title="Preview" onClick="generatePreview('{{url('/previewpage?url='.(App\Helpers\MyLibrary::getFrontUri('number-allocation')['uri']))}}');">Preview</a>
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
            var seoFormId = 'frmNumberAllocation';
            var user_action = "{{ isset($numberAllocation)?'edit':'add' }}";
            var moduleAlias = 'number-allocation';
            var preview_add_route = '{!! route("powerpanel.number-allocation.addpreview") !!}';
            var previewForm = $('#frmNumberAllocation');
            var isDetailPage = true;
            function generate_seocontent1(formname) {
            var Meta_Title = document.getElementById('title').value + "";
            var abcd = $('textarea#txtDescription').val();
            var def = abcd.replace(/<a(\s[^>]*)?>.*?<\/a>/ig, "")
            var abc = def.replace(/^(\s*)|(\s*)$/g, '').replace(/\s+/g, ' ');
            var outString1 = abc.replace(/(<([^>]+)>)/ig, "");
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
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/number-allocation/number-allocation-validations.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/custom.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/seo-generator/seo-info-generator.js' }}" type="text/javascript"></script>
@if (Config::get('Constant.DEFAULT_VISUAL') == 'Y')
    @php Powerpanel\VisualComposer\Controllers\VisualComposerController::get_builder_css_js()@endphp
@endif
@endsection