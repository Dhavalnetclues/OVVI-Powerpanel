
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
                    {!! Form::open(['method' => 'post','id'=>'frmRegisterApplication']) !!}
                        {!! Form::hidden('fkMainRecord', isset($registerapplication->fkMainRecord)?$registerapplication->fkMainRecord:old('fkMainRecord')) !!}
                        @if(isset($registerapplication))
                        @if (File::exists(base_path() . '/resources/views/powerpanel/partials/lockedpage.blade.php') != null)
                        @include('powerpanel.partials.lockedpage',['pagedata'=>$registerapplication])
                        @endif
                        @endif

                        <div class="mb-3 @if($errors->first('sector')) has-error @endif form-md-line-input">
                            @if(isset($registerapplication_highLight->varSector) && ($registerapplication_highLight->varSector != $registerapplication->varSector))
                                @php $Class_varSector = " highlitetext"; @endphp
                            @else
                                @php $Class_varSector = ""; @endphp
                            @endif
                                
                            @include('shiledcmstheme::powerpanel.partials.sector-dropdown', ['selected_sector' => isset($registerapplication->varSector)?$registerapplication->varSector:'','Class_varSector' => $Class_varSector])
                            <span class="help-block">
                                {{ $errors->first('sector') }}
                            </span>
                        </div>

                        <div class="mb-3 @if($errors->first('title')) has-error @endif form-md-line-input">
                            @php if(isset($registerapplication_highLight->varTitle) && ($registerapplication_highLight->varTitle != $registerapplication->varTitle)){
                            $Class_title = " highlitetext";
                            }else{
                            $Class_title = "";
                            } @endphp
                            <label class="form-label {!! $Class_title !!}" for="site_name">{{ trans('register-application::template.common.name') }} <span aria-required="true" class="required"> * </span></label>
                            {!! Form::text('title', isset($registerapplication->varTitle) ? $registerapplication->varTitle:old('title'), array('maxlength'=>'150','id'=>'title','placeholder' => trans('register-application::template.common.name'),'class' => 'form-control hasAlias seoField maxlength-handler titlespellingcheck','autocomplete'=>'off')) !!}
                            <span class="help-block">
                                {{ $errors->first('title') }}
                            </span>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <!-- code for alias -->
                                {!! Form::hidden(null, null, array('class' => 'hasAlias','data-url' => 'powerpanel/register-application')) !!}
                                {!! Form::hidden('alias', isset($registerapplication->alias->varAlias) ? $registerapplication->alias->varAlias : old('alias'), array('class' => 'aliasField')) !!}
                                {!! Form::hidden('oldAlias', isset($registerapplication->alias->varAlias)?$registerapplication->alias->varAlias : old('alias')) !!}
                                {!! Form::hidden('previewId') !!}
                                <div class="mb-3 alias-group {{!isset($registerapplication->alias)?'hide':''}}">
                                    <label class="form-label" for="Url">{{ trans('register-application::template.common.url') }} :</label>
                                    @if(isset($registerapplication->alias->varAlias) && !$userIsAdmin)
                                    @php
                                    $aurl = App\Helpers\MyLibrary::getFrontUri('register-application')['uri'];
                                    @endphp
                                    <a  class="alias">{!! url("/") !!}</a>
                                    @else
                                    @if(auth()->user()->can('register-application-create'))
                                    <a href="javascript:void(0);" class="alias">{!! url("/") !!}</a>
                                    <a href="javascript:void(0);" class="editAlias" title="Edit">
                                        <i class="ri-pencil-line"></i>
                                    </a>
                                    <a class="without_bg_icon openLink" title="Open Link" onClick="generatePreview('{{url('/previewpage?url='.(App\Helpers\MyLibrary::getFrontUri('register-application')['uri']))}}');">
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
                        <div class="mb-3 {{ $errors->has('company_id') ? 'has-error' : '' }} form-md-line-input">
                            @php if(isset($registerapplication_highLight->varCompanyId) && ($registerapplication_highLight->varCompanyId != $registerapplication->varCompanyId)){
                                $Class_varCompanyId = " highlitetext";
                                }else{
                                $Class_varCompanyId = "";
                            } @endphp
                            <label class="form-label {{ $Class_varCompanyId }}" for="company_id">{{ trans('register-application::template.registerapplicationModule.id') }}<span aria-required="true" class="required"> * </span></label>
                            {!! Form::text('company_id',isset($registerapplication->varCompanyId)?$registerapplication->varCompanyId:old('company_id'), array('class' => 'form-control input-sm','id' => 'company_id','maxlength'=>'5','placeholder' => trans('register-application::template.registerapplicationModule.id'),'autocomplete'=>'off', 'onkeypress'=>'javascript: return KeycheckOnlyPhonenumber(event);')) !!}
                            <span class="help-block">
                                {{ $errors->first('company_id') }}
                            </span>
                        </div>
                        <div class="mb-3 @if($errors->first('cperson')) has-error @endif form-md-line-input">
                        @php if(isset($registerapplication_highLight->varContactPerson) && ($registerapplication_highLight->varContactPerson != $registerapplication->varContactPerson)){
                                $Class_varContactPerson = " highlitetext";
                                }else{
                                $Class_varContactPerson = "";
                            } @endphp
                            <label class="form-label {{ $Class_varContactPerson }}" for="site_name">{{ trans('register-application::template.registerapplicationModule.cperson') }} <span aria-required="true" class="required"> * </span></label>
                            {!! Form::text('cperson', isset($registerapplication->varContactPerson) ? $registerapplication->varContactPerson:old('cperson'), array('maxlength'=>'150','id'=>'cperson','placeholder' => trans('register-application::template.registerapplicationModule.cperson'),'class' => 'form-control hasAlias seoField maxlength-handler titlespellingcheck','autocomplete'=>'off')) !!}
                            <span class="help-block">
                                {{ $errors->first('cperson') }}
                            </span>
                        </div>

                        <div class="mb-3 {{ $errors->has('email') ? 'has-error' : '' }} form-md-line-input">
                        @php if(isset($registerapplication_highLight->varEmail) && ($registerapplication_highLight->varEmail != $registerapplication->varEmail)){
                                $Class_varEmail = " highlitetext";
                                }else{
                                $Class_varEmail = "";
                            } @endphp
                            <label class="form-label {{ $Class_varEmail }}" for="email">{{ trans('register-application::template.common.email') }}</label>
                            {!! Form::email('email',isset($registerapplication->varEmail)?$registerapplication->varEmail:old('email'), array('class' => 'form-control input-sm', 'maxlength'=>'300','id' => 'email','placeholder' => trans('register-application::template.common.email'),'autocomplete'=>'off')) !!}
                            <span class="help-block">
                                {{ $errors->first('email') }}
                            </span>
                        </div>

                        <div class="mb-3 {{ $errors->has('link1') ? 'has-error' : '' }} form-md-line-input">
                        @php if(isset($registerapplication_highLight->varWeblink1) && ($registerapplication_highLight->varWeblink1 != $registerapplication->varWeblink1)){
                                $Class_varWeblink1 = " highlitetext";
                                }else{
                                $Class_varWeblink1 = "";
                            } @endphp
                            <label class="form-label {{ $Class_varWeblink1 }}" for="link1">{{ trans('register-application::template.registerapplicationModule.url1') }}</label>
                            {!! Form::text('link1',isset($registerapplication->varWeblink1)?$registerapplication->varWeblink1:old('link1'), array('class' => 'form-control input-sm','id' => 'link1','placeholder' => trans('register-application::template.registerapplicationModule.url1'),'autocomplete'=>'off')) !!}
                            <span class="help-block">
                                {{ $errors->first('link1') }}
                            </span>
                        </div>
                        <div class="mb-3 {{ $errors->has('link2') ? 'has-error' : '' }} form-md-line-input">
                        @php if(isset($registerapplication_highLight->varWeblink2) && ($registerapplication_highLight->varWeblink2 != $registerapplication->varWeblink2)){
                                $Class_varWeblink2 = " highlitetext";
                                }else{
                                $Class_varWeblink2 = "";
                            } @endphp
                            <label class="form-label {{ $Class_varWeblink2 }}" for="link2">{{ trans('register-application::template.registerapplicationModule.url2') }}</label>
                            {!! Form::text('link2',isset($registerapplication->varWeblink2)?$registerapplication->varWeblink2:old('link2'), array('class' => 'form-control input-sm','id' => 'link2','placeholder' => trans('register-application::template.registerapplicationModule.url2'),'autocomplete'=>'off')) !!}
                            <span class="help-block">
                                {{ $errors->first('link2') }}
                            </span>
                        </div>
                        <div class="mb-3 {{ $errors->has('link3') ? 'has-error' : '' }} form-md-line-input">
                        @php if(isset($registerapplication_highLight->varWeblink3) && ($registerapplication_highLight->varWeblink3 != $registerapplication->varWeblink3)){
                                $Class_varWeblink3 = " highlitetext";
                                }else{
                                $Class_varWeblink3 = "";
                            } @endphp
                            <label class="form-label {{ $Class_varWeblink3 }}" for="link3">{{ trans('register-application::template.registerapplicationModule.url3') }}</label>
                            {!! Form::text('link3',isset($registerapplication->varWeblink3)?$registerapplication->varWeblink3:old('link3'), array('class' => 'form-control input-sm','id' => 'link3','placeholder' => trans('register-application::template.registerapplicationModule.url3'),'autocomplete'=>'off')) !!}
                            <span class="help-block">
                                {{ $errors->first('link3') }}
                            </span>
                        </div>
                        <div class="mb-3 form-md-line-input">
                        @php if(isset($registerapplication_highLight->varContactAddress) && ($registerapplication_highLight->varContactAddress != $registerapplication->varContactAddress)){
                                $Class_varContactAddress = " highlitetext";
                                }else{
                                $Class_varContactAddress = "";
                            } @endphp
                            <label class="form-label {{ $Class_varContactAddress }}" for="address">{{ trans('register-application::template.registerapplicationModule.address') }}<span aria-required="true" class="required"> * </span></label>
                            {!! Form::textarea('address',isset($registerapplication->varContactAddress)?$registerapplication->varContactAddress:old('address'), array('class' => 'form-control maxlength-handler','maxlength'=>'400','id'=>'address','rows'=>'3','placeholder'=>trans('register-application::template.registerapplicationModule.address'),'styel'=>'max-height:80px;')) !!}
                        </div>

                        <div class="mb-3 @if($errors->first('status')) has-error @endif form-md-line-input">
                            @if(isset($registerapplication_highLight->varStatus) && ($registerapplication_highLight->varStatus != $registerapplication->varStatus))
                            @php $Class_varStatus = " highlitetext"; @endphp
                            @else
                            @php $Class_varStatus = ""; @endphp
                            @endif
                            <label class="form-label {{ $Class_varStatus }}" for="site_name">Status<span aria-required="true" class="required"> * </span></label>
                            <select class="form-control" data-choices name="status" id="status">
                                <option value="">Select Status</option>
                                @foreach($selectstatus as  $status)
                                @php $permissionName = 'register-application-list' @endphp
                                @php $selected = ''; @endphp
                                @if(isset($registerapplication->varStatus))
                                @if($status == $registerapplication->varStatus)
                                @php $selected = 'selected';  @endphp
                                @endif
                                @endif
                                <option value="{{$status}}" {{ $selected }}>{{ $status }}</option>
                                @endforeach
                            </select>
                            <span class="help-block">
                                {{ $errors->first('status') }}
                            </span>
                        </div>
                        <div class="mb-3 @if($errors->first('service')) has-error @endif form-md-line-input">
                        @php if(isset($registerapplication_highLight->varService) && ($registerapplication_highLight->varService != $registerapplication->varService)){
                                $Class_varService = " highlitetext";
                                }else{
                                $Class_varService = "";
                            } @endphp
                            <label class="form-label {{ $Class_varService }}" for="site_name">Select Service/Network<span aria-required="true" class="required"> * </span> </label>
                            <select class="form-control" data-choices multiple name="service[]" id="service">
                                <option value="">Select Service/Network</option>
                                @foreach($selectservice as  $ValueService)
                                @php $permissionName = 'register-application-list' @endphp
                                @php $selected = ''; @endphp
                                @if(isset($registerapplication->varService))
                                @php 
                                $serv= explode(",",$registerapplication->varService)
                                @endphp
                                    @if(in_array($ValueService->id,$serv))
                                @php $selected = 'selected'; @endphp
                                @endif 
                                @endif
                                <option value="{{$ValueService->id}}" {{ $selected }}>{{ $ValueService->varTitle }} (Code: {{$ValueService->serviceCode}})</option>
                                @endforeach
                            </select>
                            <span class="help-block">
                                {{ $errors->first('service') }}
                            </span>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                            @php if(isset($registerapplication_highLight->txtDescription) && ($registerapplication_highLight->txtDescription != $registerapplication->txtDescription)){
                                    $Class_Description = " highlitetext";
                                    }else{
                                    $Class_Description = "";
                                    } @endphp
                                    <label class="form-label {{ $Class_Description }}" for="description">Description</label>
                                <div class="mb-3 @if($errors->first('description')) has-error @endif form-md-line-input">
                                    @if (Config::get('Constant.DEFAULT_VISUAL') == 'Y')
                                    <div id="body-roll">											
                                        @php
                                        $sections = [];
                                        @endphp
                                        @if(isset($registerapplication))
                                        @php
                                        $sections = json_decode($registerapplication->txtDescription);
                                        @endphp
                                        @endif
                                        <!-- Builder include -->
                                        @php
                                        Powerpanel\VisualComposer\Controllers\VisualComposerController::page_section(['sections'=>$sections])
                                        @endphp
                                    </div>
                                    @else
                                    <label class="form-label {!! $Class_Description !!}">{{ trans('frmRegisterApplication::template.common.description') }}</label>
                                    {!! Form::textarea('description', isset($registerapplication->txtDescription)?$registerapplication->txtDescription:old('description'), array('placeholder' => trans('frmRegisterApplication::template.common.description'),'class' => 'form-control','id'=>'txtDescription')) !!}
                                    @endif
                                    <span class="help-block">{{ $errors->first('description') }}</span>
                                </div>
                            </div>
                        </div>
                        
                        @if(isset($registerapplication->intSearchRank))
                            @php $srank = $registerapplication->intSearchRank; @endphp
                        @else
                            @php $srank = null !== old('search_rank') ? old('search_rank') : 2 ; @endphp
                        @endif

                        @if(isset($registerapplication_highLight->intSearchRank) && ($registerapplication_highLight->intSearchRank != $registerapplication->intSearchRank))
                            @php $Class_intSearchRank = " highlitetext"; @endphp
                        @else
                            @php $Class_intSearchRank = ""; @endphp
                        @endif

                        @if(isset($registerapplication_highLight->varTags) && ($registerapplication_highLight->varTags != $registerapplication->varTags))
                            @php $Class_varTags = " highlitetext"; @endphp
                        @else
                            @php $Class_varTags = ""; @endphp
                        @endif
                            
                        <div class="row">
                            <div class="col-md-12">
                                <div class="nopadding">
                                    @include('powerpanel.partials.seoInfo',['form'=>'frmRegisterApplication','inf'=>isset($metaInfo)?$metaInfo:false,'inf_highLight'=> isset($metaInfo_highLight)?$metaInfo_highLight:false,'Class_intSearchRank' => $Class_intSearchRank, 'srank' => $srank , 'Class_varTags' => $Class_varTags])
                                </div>
                            </div>
                        </div>
                        <h3 class="form-section">{{ trans('register-application::template.common.displayinformation') }}</h3>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3 @if($errors->first('order')) has-error @endif form-md-line-input">
                                    @php
                                    $display_order_attributes = array('class' => 'form-control','maxlength'=>5,'placeholder'=>trans('register-application::template.common.displayorder'),'autocomplete'=>'off');
                                    @endphp
                                    @if(isset($registerapplication_highLight->intDisplayOrder) && ($registerapplication_highLight->intDisplayOrder != $registerapplication->intDisplayOrder))
                                    @php $Class_intDisplayOrder = " highlitetext"; @endphp
                                    @else
                                    @php $Class_intDisplayOrder = ""; @endphp
                                    @endif
                                    <label class="form-label {{ $Class_intDisplayOrder }}" for="site_name">{{ trans('register-application::template.common.displayorder') }} <span aria-required="true" class="required"> * </span></label>
                                    {!! Form::text('order', isset($registerapplication->intDisplayOrder)?$registerapplication->intDisplayOrder:1, $display_order_attributes) !!}
                                    <span style="color: red;">
                                        {{ $errors->first('order') }}
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                @if(isset($registerapplication_highLight->chrPublish) && ($registerapplication_highLight->chrPublish != $registerapplication->chrPublish))
                                    @php $Class_chrPublish = " highlitetext"; @endphp
                                @else
                                    @php $Class_chrPublish = ""; @endphp
                                @endif

                                @if(isset($registerapplication) && $registerapplication->chrAddStar == 'Y')
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="control-label form-label"> Publish/ Unpublish</label>
                                            <input type="hidden" id="chrMenuDisplay" name="chrMenuDisplay" value="{{ isset($registerapplication->chrPublish) ? $registerapplication->chrPublish : '' }}">
                                            <p><b>NOTE:</b> This record is in Approval Request , so it can&#39;t be published/unpublished.</p>
                                        </div>
                                    </div>
                                @elseif(isset($registerapplication) && $registerapplication->chrDraft == 'D' && $registerapplication->chrAddStar != 'Y')
                                    @include('powerpanel.partials.displayInfo',['Class_chrPublish'=>$Class_chrPublish,'display' => (isset($registerapplication->chrDraft)?$registerapplication->chrDraft:'D')])
                                @else
                                    @include('powerpanel.partials.displayInfo',['Class_chrPublish'=>$Class_chrPublish,'display' => (isset($registerapplication->chrPublish)?$registerapplication->chrPublish:'Y')])
                                @endif
                            </div>
                        </div>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-12">
                                    @if(isset($registerapplication->fkMainRecord) && $registerapplication->fkMainRecord != 0)
                                    <button type="submit" name="saveandexit" class="btn btn-primary" value="saveandexit">{!! trans('register-application::template.common.approve') !!}</button>
                                    @else
                                    @if($userIsAdmin)
                                    <button type="submit" name="saveandedit" class="btn btn-primary" value="saveandedit">{!! trans('register-application::template.common.saveandedit') !!}</button>
                                    <button type="submit" name="saveandexit" class="btn btn-primary" value="saveandexit">{!! trans('register-application::template.common.saveandexit') !!}</button>
                                    @else
                                    @if((isset($chrNeedAddPermission) && $chrNeedAddPermission == 'N') && (isset($charNeedApproval) && $charNeedApproval == 'N'))
                                    <button type="submit" name="saveandexit" class="btn btn-primary" value="saveandexit">{!! trans('register-application::template.common.saveandexit') !!}</button>
                                    @else
                                    <button type="submit" name="saveandexit" class="btn btn-primary" value="approvesaveandexit">{!! trans('register-application::template.common.approvesaveandexit') !!}</button>
                                    @endif
                                    @endif
                                    @endif
                                    <a class="btn btn-danger" href="{{ url('powerpanel/register-application') }}">{{ trans('register-application::template.common.cancel') }}</a>
                                    @if(isset($registerapplication) && !empty($registerapplication))
                                    &nbsp;<a class="btn btn-primary" title="Preview" onClick="generatePreview('{{url('/previewpage?url='.(App\Helpers\MyLibrary::getFrontUri('register-application')['uri']))}}');">Preview</a>
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
    var seoFormId = 'frmRegisterApplication';
    var user_action = "{{ isset($registerapplication)?'edit':'add' }}";
    var moduleAlias = "{{ App\Helpers\MyLibrary::getFrontUri('register-application')['moduleAlias'] }}";
    var preview_add_route = '{!! route("powerpanel.register-application.addpreview") !!}';
    var previewForm = $('#frmRegisterApplication');
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
    $('#varMetaDescription').val(Meta_Description);
    $('#meta_title').html(Meta_Title);
    $('#meta_description').html(Meta_Description);
    }
</script>
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-taginput/bootstrap-tagsinput.min.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/registerapplication/register-application_validation.js' }}" type="text/javascript"></script>

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
<script>
    // $('#status').select2({
    // placeholder: "Status",
    //         width: '100%'
    // }).on("change", function (e) {
    // $("#status").closest('.has-error').removeClass('has-error');
    // $("#status-error").remove();
    // });
</script>
@if (Config::get('Constant.DEFAULT_VISUAL') == 'Y')
@php Powerpanel\VisualComposer\Controllers\VisualComposerController::get_builder_css_js()@endphp
@endif
@endsection