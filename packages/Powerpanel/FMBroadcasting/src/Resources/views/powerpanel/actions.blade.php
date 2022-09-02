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
                    {!! Form::open(['method' => 'post','id'=>'frmfmbroadcasting']) !!}
                        {!! Form::hidden('fkMainRecord', isset($fmbroadcasting->fkMainRecord)?$fmbroadcasting->fkMainRecord:old('fkMainRecord')) !!}
                        @if(isset($fmbroadcasting))
                        @if (File::exists(base_path() . '/resources/views/powerpanel/partials/lockedpage.blade.php') != null)
                        @include('powerpanel.partials.lockedpage',['pagedata'=>$fmbroadcasting])
                        @endif
                        @endif
                            
                        <!-- Sector type -->
                        <div class="mb-3 @if($errors->first('sector')) has-error @endif form-md-line-input">
                            @if(isset($FmBroadcasting_highLight->varSector) && ($FmBroadcasting_highLight->varSector != $fmbroadcasting->varSector))
                                @php $Class_varSector = " highlitetext"; @endphp
                            @else
                                @php $Class_varSector = ""; @endphp
                            @endif
                            @include('shiledcmstheme::powerpanel.partials.sector-dropdown', ['selected_sector' => isset($fmbroadcasting->varSector)?$fmbroadcasting->varSector:'','Class_varSector' => $Class_varSector])
                            <span class="help-block">
                                {{ $errors->first('sector') }}
                            </span>
                        </div>

                        <div class="mb-3 @if($errors->first('title')) has-error @endif form-md-line-input">
                            @php if(isset($FmBroadcasting_highLight->varTitle) && ($FmBroadcasting_highLight->varTitle != $fmbroadcasting->varTitle)){
                            $Class_title = " highlitetext";
                            }else{
                            $Class_title = "";
                            } @endphp
                            <label class="form-label {!! $Class_title !!}" for="site_name">{{ trans('fmbroadcasting::template.common.staionname') }} <span aria-required="true" class="required"> * </span></label>
                            {!! Form::text('title', isset($fmbroadcasting->varTitle) ? $fmbroadcasting->varTitle:old('title'), array('maxlength'=>'150','id'=>'title','placeholder' => trans('fmbroadcasting::template.common.staionname'),'class' => 'form-control hasAlias seoField maxlength-handler titlespellingcheck','autocomplete'=>'off')) !!}
                            <span class="help-block">
                                {{ $errors->first('title') }}
                            </span>
                        </div>
                        <div class="row" style="display: none">
                            <div class="col-md-12">
                                <!-- code for alias -->
                                {!! Form::hidden(null, null, array('class' => 'hasAlias','data-url' => 'powerpanel/fmbroadcasting')) !!}
                                {!! Form::hidden('alias', isset($fmbroadcasting->alias->varAlias) ? $fmbroadcasting->alias->varAlias : old('alias'), array('class' => 'aliasField')) !!}
                                {!! Form::hidden('oldAlias', isset($fmbroadcasting->alias->varAlias)?$fmbroadcasting->alias->varAlias : old('alias')) !!}
                                {!! Form::hidden('previewId') !!}
                                <div class="mb-3 alias-group {{!isset($fmbroadcasting->alias)?'hide':''}}">
                                    <label class="form-label" for="Url">{{ trans('fmbroadcasting::template.common.url') }} :</label>
                                    @if(isset($fmbroadcasting->alias->varAlias) && !$userIsAdmin)
                                    @php
                                    $aurl = App\Helpers\MyLibrary::getFrontUri('fmbroadcasting')['uri'];
                                    @endphp
                                    <a  class="alias">{!! url("/") !!}</a>
                                    @else
                                    @if(auth()->user()->can('fmbroadcasting-create'))
                                    <a href="javascript:void;" class="alias">{!! url("/") !!}</a>
                                    <a href="javascript:void(0);" class="editAlias" title="Edit">
                                        <i class="ri-pencil-line"></i>
                                    </a>
                                    <a class="without_bg_icon openLink" title="Open Link" onClick="generatePreview('{{url('/previewpage?url='.(App\Helpers\MyLibrary::getFrontUri('fmbroadcasting')['uri']))}}');">
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
                        <div class="mb-3 @if($errors->first('frequency')) has-error @endif form-md-line-input">
                            @php if(isset($FmBroadcasting_highLight->txtFrequency) && ($FmBroadcasting_highLight->txtFrequency != $fmbroadcasting->txtFrequency)){
                            $Class_title = " highlitetext";
                            }else{
                            $Class_title = "";
                            } @endphp
                            <label class="form-label {!! $Class_title !!}" for="site_name">{{ trans('fmbroadcasting::template.common.frequency') }} <span aria-required="true" class="required"> * </span></label>
                            {!! Form::text('frequency', isset($fmbroadcasting->txtFrequency) ? $fmbroadcasting->txtFrequency:old('frequency'), array('maxlength'=>'50','id'=>'frequency','placeholder' => trans('fmbroadcasting::template.common.frequency'),'class' => 'form-control hasAlias seoField maxlength-handler titlespellingcheck','autocomplete'=>'off','onkeypress'=>'return isNumberKey(event)')) !!}
                            <span class="help-block">
                                {{ $errors->first('frequency') }}
                            </span>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                @if(isset($FmBroadcasting_highLight->fkIntImgId) && ($FmBroadcasting_highLight->fkIntImgId != $fmbroadcasting->fkIntImgId))
                                @php $Class_fkIntImgId = " highlitetext"; @endphp
                                @else
                                @php $Class_fkIntImgId = ""; @endphp
                                @endif
                                <div class="image_thumb multi_upload_images">
                                    <div class="mb-3">
                                        <label class="form-label {{ $Class_fkIntImgId }}" for="front_logo">{{ trans('fmbroadcasting::template.common.selectimage') }} <span aria-required="true" class="required"> * </span></label>
                                        <div class="clearfix"></div>
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <div class="fileinput-preview thumbnail blog_image_img" data-trigger="fileinput" style="width:100%;float:left; height:120px;position: relative;">
                                                @if(old('image_url'))
                                                <img src="{{ old('image_url') }}" />
                                                @elseif(isset($fmbroadcasting->fkIntImgId))
                                                <img src="{!! App\Helpers\resize_image::resize($fmbroadcasting->fkIntImgId,120,120) !!}" />
                                                @else
                                                <img class="img_opacity" src="{{ $CDN_PATH.'resources/images/upload_file.gif' }}" />
                                                @endif
                                            </div>

                                            <div class="input-group">
                                                <a class="media_manager" data-multiple="false" onclick="MediaManager.open('blog_image');"><span class="fileinput-new"></span></a>
                                                <input class="form-control" type="hidden" id="blog_image" name="img_id" value="{{ isset($fmbroadcasting->fkIntImgId)?$fmbroadcasting->fkIntImgId:old('img_id') }}" />
                                                @php
                                                if (method_exists($MyLibrary, 'GetFolderID')) {
                                                if(isset($fmbroadcasting->fkIntImgId)){
                                                $folderid = App\Helpers\MyLibrary::GetFolderID($fmbroadcasting->fkIntImgId);
                                                @endphp
                                                @if(isset($folderid->fk_folder) && $folderid->fk_folder != '0')
                                                <input class="form-control" type="hidden" id="folder_id" name="folder_id" value="{{ $folderid->fk_folder }}" />
                                                @endif
                                                @php
                                                }
                                                }
                                                @endphp
                                                <input class="form-control" type="hidden" id="image_url" name="image_url" value="{{ old('image_url') }}" />
                                            </div>
                                            <div class="overflow_layer">
                                                <a onclick="MediaManager.open('blog_image');" class="media_manager remove_img"><i class="ri-pencil-line"></i></a>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        @php $height = isset($settings->height)?$settings->height:292; $width = isset($settings->width)?$settings->width:372; @endphp <span>{{ trans('fmbroadcasting::template.common.imageSize',['height'=>$height, 'width'=>$width]) }}</span>
                                    </div>
                                    <span class="help-block">
                                        {{ $errors->first('img_id') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 @if($errors->first('link')) has-error @endif form-md-line-input">
                            @php if(isset($FmBroadcasting_highLight->varLink) && ($FmBroadcasting_highLight->varLink != $fmbroadcasting->varLink)){
                            $Class_title = " highlitetext";
                            }else{
                            $Class_title = "";
                            } @endphp
                            <label class="form-label {!! $Class_title !!}" for="site_name">{{ trans('Listen Live Link') }} <span aria-required="true" class="required"> * </span></label>
                            {!! Form::text('link', isset($fmbroadcasting->varLink) ? $fmbroadcasting->varLink:old('link'), array('maxlength'=>'150','id'=>'link','placeholder' => trans('Listen Live Link'),'class' => 'form-control hasAlias seoField maxlength-handler titlespellingcheck','autocomplete'=>'off')) !!}
                            <span class="help-block">
                                {{ $errors->first('link') }}
                            </span>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3 @if($errors->first('short_description')) has-error @endif form-md-line-input">
                                    @php if(isset($FmBroadcasting_highLight->varShortDescription) && ($FmBroadcasting_highLight->varShortDescription != $fmbroadcasting->varShortDescription)){
                                    $Class_ShortDescription = " highlitetext";
                                    }else{
                                    $Class_ShortDescription = "";
                                    } @endphp
                                    <label class="form-label {!! $Class_ShortDescription !!}">Licensee</label>
                                    {!! Form::textarea('short_description', isset($fmbroadcasting->varShortDescription)?$fmbroadcasting->varShortDescription:old('short_description'), array('maxlength' => isset($settings->short_desc_length)?$settings->short_desc_length:400,'class' => 'form-control seoField maxlength-handler shortdescspellingcheck','id'=>'varShortDescription','rows'=>'3','placeholder'=>'Licensee')) !!}
                                    <span class="help-block">{{ $errors->first('short_description') }}</span> 
                                </div>
                            </div>
                        </div>
                        <h3 class="form-section">{{ trans('fmbroadcasting::template.common.displayinformation') }}</h3>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3 @if($errors->first('order')) has-error @endif form-md-line-input">
                                    @php
                                    $display_order_attributes = array('class' => 'form-control','maxlength'=>5,'placeholder'=>trans('fmbroadcasting::template.common.displayorder'),'autocomplete'=>'off');
                                    @endphp
                                    @if(isset($FmBroadcasting_highLight->intDisplayOrder) && ($FmBroadcasting_highLight->intDisplayOrder != $fmbroadcasting->intDisplayOrder))
                                    @php $Class_intDisplayOrder = " highlitetext"; @endphp
                                    @else
                                    @php $Class_intDisplayOrder = ""; @endphp
                                    @endif
                                    <label class="form-label {{ $Class_intDisplayOrder }}" for="site_name">{{ trans('fmbroadcasting::template.common.displayorder') }} <span aria-required="true" class="required"> * </span></label>
                                    {!! Form::text('order', isset($fmbroadcasting->intDisplayOrder)?$fmbroadcasting->intDisplayOrder:1, $display_order_attributes) !!}
                                    <span style="color: red;">
                                        {{ $errors->first('order') }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                @if(isset($FmBroadcasting_highLight->chrPublish) && ($FmBroadcasting_highLight->chrPublish != $fmbroadcasting->chrPublish))
                                    @php $Class_chrPublish = " highlitetext"; @endphp
                                @else
                                    @php $Class_chrPublish = ""; @endphp
                                @endif

                                @if(isset($fmbroadcasting) && $fmbroadcasting->chrAddStar == 'Y')
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="control-label form-label"> Publish/ Unpublish</label>
                                            <input type="hidden" id="chrMenuDisplay" name="chrMenuDisplay" value="{{ isset($fmbroadcasting->chrPublish) ? $fmbroadcasting->chrPublish : '' }}">
                                            <p><b>NOTE:</b> This record is in Approval Request , so it can&#39;t be published/unpublished.</p>
                                        </div>
                                    </div>
                                @elseif(isset($fmbroadcasting) && $fmbroadcasting->chrDraft == 'D' && $fmbroadcasting->chrAddStar != 'Y')
                                    @include('powerpanel.partials.displayInfo',['Class_chrPublish'=>$Class_chrPublish,'display' => (isset($fmbroadcasting->chrDraft)?$fmbroadcasting->chrDraft:'D')])
                                @else
                                    @include('powerpanel.partials.displayInfo',['Class_chrPublish'=>$Class_chrPublish,'display' => (isset($fmbroadcasting->chrPublish)?$fmbroadcasting->chrPublish:'Y')])
                                @endif
                            </div>
                        </div>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-12">
                                    @if(isset($fmbroadcasting->fkMainRecord) && $fmbroadcasting->fkMainRecord != 0)
                                    <button type="submit" name="saveandexit" class="btn btn-primary" value="saveandexit">{!! trans('fmbroadcasting::template.common.approve') !!}</button>
                                    @else
                                    @if($userIsAdmin)
                                    <button type="submit" name="saveandedit" class="btn btn-primary" value="saveandedit">{!! trans('fmbroadcasting::template.common.saveandedit') !!}</button>
                                    <button type="submit" name="saveandexit" class="btn btn-primary" value="saveandexit">{!! trans('fmbroadcasting::template.common.saveandexit') !!}</button>
                                    @else
                                    @if((isset($chrNeedAddPermission) && $chrNeedAddPermission == 'N') && (isset($charNeedApproval) && $charNeedApproval == 'N'))
                                    <button type="submit" name="saveandexit" class="btn btn-primary" value="saveandexit">{!! trans('fmbroadcasting::template.common.saveandexit') !!}</button>
                                    @else
                                    <button type="submit" name="saveandexit" class="btn btn-primary" value="approvesaveandexit">{!! trans('fmbroadcasting::template.common.approvesaveandexit') !!}</button>
                                    @endif
                                    @endif
                                    @endif
                                    <a class="btn btn-danger" href="{{ url('powerpanel/fmbroadcasting') }}">{{ trans('fmbroadcasting::template.common.cancel') }}</a>
                                    @if(isset($fmbroadcasting) && !empty($fmbroadcasting))
                                    &nbsp;<a style="display: none" class="btn btn-primary" title="Preview" onClick="generatePreview('{{url('/previewpage?url='.(App\Helpers\MyLibrary::getFrontUri('fmbroadcasting')['uri']))}}');">Preview</a>
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
    var seoFormId = 'frmfmbroadcasting';
    var user_action = "{{ isset($fmbroadcasting)?'edit':'add' }}";
    var moduleAlias = "{{ App\Helpers\MyLibrary::getFrontUri('fmbroadcasting')['moduleAlias'] }}";
    var preview_add_route = '{!! route("powerpanel.fmbroadcasting.addpreview") !!}';
    var previewForm = $('#frmfmbroadcasting');
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
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/fmbroadcasting/fmbroadcasting_validations.js' }}" type="text/javascript"></script>

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