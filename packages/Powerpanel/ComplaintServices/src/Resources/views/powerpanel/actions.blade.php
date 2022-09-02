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
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        @if(Session::has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ Session::get('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        
        <div class="card">
            <div class="card-body">
                <div class="live-preview">
                    {!! Form::open(['method' => 'post','id'=>'frmcomplaintservices']) !!}
                        {!! Form::hidden('fkMainRecord', isset($complaintservices->fkMainRecord)?$complaintservices->fkMainRecord:old('fkMainRecord')) !!}
                        @if(isset($complaintservices))
                        @if (File::exists(base_path() . '/resources/views/powerpanel/partials/lockedpage.blade.php') != null)
                        @include('powerpanel.partials.lockedpage',['pagedata'=>$complaintservices])
                        @endif
                        @endif
                        <div class="mb-3 @if($errors->first('title')) has-error @endif form-md-line-input">
                            @php if(isset($complaint_highLight->varTitle) && ($complaint_highLight->varTitle != $complaintservices->varTitle)){
                            $Class_title = " highlitetext";
                            }else{
                            $Class_title = "";
                            } @endphp
                            <label class="form-label {!! $Class_title !!}" for="site_name">{{ trans('careers::template.common.title') }} <span aria-required="true" class="required"> * </span></label>
                            {!! Form::text('title', isset($complaintservices->varTitle) ? $complaintservices->varTitle:old('title'), array('maxlength'=>'150','id'=>'title','placeholder' => trans('careers::template.common.title'),'class' => 'form-control hasAlias seoField maxlength-handler titlespellingcheck','autocomplete'=>'off')) !!}
                            <span class="help-block">
                                {{ $errors->first('title') }}
                            </span>
                        </div>
                        
                        <div class="mb-3 hide">
                            <div class="{{ $errors->has('email') ? 'has-error' : '' }} form-md-line-input">
                                <label class="form-label" for="email">{{ trans('team::template.common.email') }}<span aria-required="true" class="required"> * </span> </label>
                                {!! Form::text('email',isset($complaintservices->txtEmail)?$complaintservices->txtEmail:old('email'), array('class' => 'form-control input-sm', 'maxlength'=>'300','id' => 'email','placeholder' => trans('team::template.common.email'),'autocomplete'=>'off')) !!}
                                <span class="help-block">
                                    {{ $errors->first('email') }}
                                </span>
                            </div>
                            <span>{{ trans('Note: You can enter multiple email addresses separated by commas (e.g: john@yahoo.com, andy@gmail.com).') }}</span>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                @if(isset($complaint_highLight->fkIntImgId) && ($complaint_highLight->fkIntImgId != $complaintservices->fkIntImgId))
                                @php $Class_fkIntImgId = " highlitetext"; @endphp
                                @else
                                @php $Class_fkIntImgId = ""; @endphp
                                @endif
                                <div class="image_thumb multi_upload_images">
                                    <div class="mb-3">
                                        <label class="form-label {{ $Class_fkIntImgId }}" for="front_logo">{{ trans('blogs::template.common.selectimage') }} <span aria-required="true" class="required"> * </span></label>
                                        <div class="clearfix"></div>
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <div class="fileinput-preview thumbnail blog_image_img" data-trigger="fileinput" style="width:100%;float:left; height:120px;position: relative;">
                                                @if(old('image_url'))
                                                <img src="{{ old('image_url') }}" />
                                                @elseif(isset($complaintservices->fkIntImgId))
                                                <img src="{!! App\Helpers\resize_image::resize($complaintservices->fkIntImgId,120,120) !!}" />
                                                @else
                                                <img class="img_opacity" src="{{ $CDN_PATH.'resources/images/upload_file.gif' }}" />
                                                @endif
                                            </div>

                                            <div class="input-group">
                                                <a class="media_manager" data-multiple="false" onclick="MediaManager.open('blog_image');"><span class="fileinput-new"></span></a>
                                                <input class="form-control" type="hidden" id="blog_image" name="img_id" value="{{ isset($complaintservices->fkIntImgId)?$complaintservices->fkIntImgId:old('img_id') }}" />
                                                @php
                                                if (method_exists($MyLibrary, 'GetFolderID')) {
                                                if(isset($complaintservices->fkIntImgId)){
                                                $folderid = App\Helpers\MyLibrary::GetFolderID($complaintservices->fkIntImgId);
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
                                        @php $height = isset($settings->height)?$settings->height:292; $width = isset($settings->width)?$settings->width:372; @endphp <span>{{ trans('complaint-services::template.common.imageSize',['height'=>$height, 'width'=>$width]) }}</span>
                                    </div>
                                    <span class="help-block">
                                        {{ $errors->first('img_id') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="row hide">
                            <div class="col-md-12">
                                <div class="mb-3 @if($errors->first('short_description')) has-error @endif form-md-line-input">
                                    @php if(isset($complaint_highLight->varShortDescription) && ($complaint_highLight->varShortDescription != $complaintservices->varShortDescription)){
                                    $Class_ShortDescription = " highlitetext";
                                    }else{
                                    $Class_ShortDescription = "";
                                    } @endphp
                                    <label class="form-label {!! $Class_ShortDescription !!}">Short Description</label>
                                    {!! Form::textarea('short_description', isset($complaintservices->varShortDescription)?$complaintservices->varShortDescription:old('short_description'), array('maxlength' => isset($settings->short_desc_length)?$settings->short_desc_length:400,'class' => 'form-control seoField maxlength-handler shortdescspellingcheck','id'=>'varShortDescription','rows'=>'3','placeholder'=>'Short Description')) !!}
                                    <span class="help-block">{{ $errors->first('short_description') }}</span> 
                                </div>
                            </div>
                        </div>

                        @if(isset($complaintservices->intSearchRank))
                        @php $srank = $complaintservices->intSearchRank; @endphp
                        @else
                        @php
                        $srank = null !== old('search_rank') ? old('search_rank') : 2 ;
                        @endphp
                        @endif
                        @if(isset($complaint_highLight->intSearchRank) && ($complaint_highLight->intSearchRank != $complaintservices->intSearchRank))
                        @php $Class_intSearchRank = " highlitetext"; @endphp
                        @else
                        @php $Class_intSearchRank = ""; @endphp
                        @endif
                        
                        <h3 class="form-section">{{ trans('careers::template.common.displayinformation') }}</h3>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3 @if($errors->first('order')) has-error @endif form-md-line-input">
                                    @php
                                    $display_order_attributes = array('class' => 'form-control','maxlength'=>5,'placeholder'=>trans('careers::template.common.displayorder'),'autocomplete'=>'off');
                                    @endphp
                                    @if(isset($complaint_highLight->intDisplayOrder) && ($complaint_highLight->intDisplayOrder != $complaintservices->intDisplayOrder))
                                    @php $Class_intDisplayOrder = " highlitetext"; @endphp
                                    @else
                                    @php $Class_intDisplayOrder = ""; @endphp
                                    @endif
                                    <label class="form-label {{ $Class_intDisplayOrder }}" for="site_name">{{ trans('careers::template.common.displayorder') }} <span aria-required="true" class="required"> * </span></label>
                                    {!! Form::text('order', isset($complaintservices->intDisplayOrder)?$complaintservices->intDisplayOrder:1, $display_order_attributes) !!}
                                    <span style="color: red;">
                                        {{ $errors->first('order') }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                @if(isset($complaint_highLight->chrPublish) && ($complaint_highLight->chrPublish != $complaintservices->chrPublish))
                                    @php $Class_chrPublish = " highlitetext"; @endphp
                                @else
                                    @php $Class_chrPublish = ""; @endphp
                                @endif

                                @if(isset($complaintservices) && $complaintservices->chrAddStar == 'Y')
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="control-label form-label"> Publish/ Unpublish</label>
                                            <input type="hidden" id="chrMenuDisplay" name="chrMenuDisplay" value="{{ isset($complaintservices->chrPublish) ? $complaintservices->chrPublish : '' }}">
                                            <p><b>NOTE:</b> This record is in Approval Request , so it can&#39;t be published/unpublished.</p>
                                        </div>
                                    </div>
                                @elseif(isset($complaintservices) && $complaintservices->chrDraft == 'D' && $complaintservices->chrAddStar != 'Y')
                                    @include('powerpanel.partials.displayInfo',['Class_chrPublish'=>$Class_chrPublish,'display' => (isset($complaintservices->chrDraft)?$complaintservices->chrDraft:'D')])
                                @else
                                    @include('powerpanel.partials.displayInfo',['Class_chrPublish'=>$Class_chrPublish,'display' => (isset($complaintservices->chrPublish)?$complaintservices->chrPublish:'Y')])
                                @endif
                            </div>

                        </div>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-12">
                                    @if(isset($complaintservices->fkMainRecord) && $complaintservices->fkMainRecord != 0)
                                    <button type="submit" name="saveandexit" class="btn btn-primary" value="saveandexit">{!! trans('careers::template.common.approve') !!}</button>
                                    @else
                                    @if($userIsAdmin)
                                    <button type="submit" name="saveandedit" class="btn btn-primary" value="saveandedit">{!! trans('careers::template.common.saveandedit') !!}</button>
                                    <button type="submit" name="saveandexit" class="btn btn-primary" value="saveandexit">{!! trans('careers::template.common.saveandexit') !!}</button>
                                    @else
                                    @if((isset($chrNeedAddPermission) && $chrNeedAddPermission == 'N') && (isset($charNeedApproval) && $charNeedApproval == 'N'))
                                    <button type="submit" name="saveandexit" class="btn btn-primary" value="saveandexit">{!! trans('careers::template.common.saveandexit') !!}</button>
                                    @else
                                    <button type="submit" name="saveandexit" class="btn btn-primary" value="approvesaveandexit">{!! trans('careers::template.common.approvesaveandexit') !!}</button>
                                    @endif
                                    @endif
                                    @endif
                                    <a class="btn btn-danger" href="{{ url('powerpanel/complaint-services') }}">{{ trans('careers::template.common.cancel') }}</a>
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
   
    var user_action = "{{ isset($complaintservices)?'edit':'add' }}";
  
    var preview_add_route = '{!! route("powerpanel.careers.addpreview") !!}';
    var previewForm = $('#frmcomplaintservices');
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
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/complaintservices/complaint_validations.js' }}" type="text/javascript"></script>

<script src="{{ $CDN_PATH.'resources/pages/scripts/custom.js' }}" type="text/javascript"></script>

<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js' }}" type="text/javascript"></script>

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