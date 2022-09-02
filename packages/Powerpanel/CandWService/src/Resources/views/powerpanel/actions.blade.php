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
                    {!! Form::open(['method' => 'post','id'=>'frmCandWService']) !!}
                        @if(isset($candwservice))
                            @if (File::exists(base_path() . '/resources/views/powerpanel/partials/lockedpage.blade.php') != null)
                            @include('powerpanel.partials.lockedpage',['pagedata'=>$candwservice])
                            @endif
                        @endif

                        <!-- Sector type -->
                        <div class="mb-3 @if($errors->first('sector')) has-error @endif form-md-line-input">
                            @if(isset($candwservice_highLight->varSector) && ($candwservice_highLight->varSector != $candwservice->varSector))
                            @php $Class_varSector = " highlitetext"; @endphp
                            @else
                            @php $Class_varSector = ""; @endphp
                            @endif
                            @include('shiledcmstheme::powerpanel.partials.sector-dropdown', ['selected_sector' => isset($candwservice->varSector)?$candwservice->varSector:'','Class_varSector' => $Class_varSector])
                            <span class="help-block">{{ $errors->first('sector') }}</span>
                        </div>

                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3 @if($errors->first('title')) has-error @endif form-md-line-input">
                                        @php if(isset($candwservice_highLight->varTitle) && ($candwservice_highLight->varTitle != $candwservice->varTitle)){
                                        $Class_title = " highlitetext";
                                        }else{
                                        $Class_title = "";
                                        } @endphp
                                        <label class="form_title {!! $Class_title !!}" for="site_name">{{ trans('news::template.common.title') }} <span aria-required="true" class="required"> * </span></label>
                                        {!! Form::text('title', isset($candwservice->varTitle)?$candwservice->varTitle:old('title'), array('maxlength' => 200,'id'=>'title', 'class' => 'form-control hasAlias seoField maxlength-handler titlespellingcheck','autocomplete'=>'off','data-url' => 'powerpanel/candwservice')) !!}
                                        <span class="help-block">{{ $errors->first('title') }}</span>
                                    </div>
                                </div>
                            </div>
                            {!! Form::hidden('fkMainRecord', isset($candwservice->fkMainRecord)?$candwservice->fkMainRecord:old('fkMainRecord')) !!}

                            @php $defaultDt = (null !== old('start_date_time'))?old('start_date_time'):date('Y-m-d H:i'); @endphp
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3 form-md-line-input">
                                        @php if(isset($candwservice_highLight->dtDateTime) && ($candwservice_highLight->dtDateTime != $candwservice->dtDateTime)){
                                        $Class_date = " highlitetext";
                                        }else{
                                        $Class_date = "";
                                        } @endphp
                                        
                                        <label class="control-label form-label {!! $Class_date !!}">{{ trans('news::template.common.startDateAndTime') }}<span aria-required="true" class="required"> * </span></label>
                                        <div class="input-group date form_meridian_datetime @if($errors->first('start_date_time')) has-error @endif" data-date="{{ Carbon\Carbon::today()->format('Y-m-d') }}T15:25:00Z">
                                            <span class="input-group-text date_default" id="basic-addon1">
                                                <i class="ri-calendar-fill"></i>
                                            </span>
                                            {!! Form::text('start_date_time', date(Config::get('Constant.DEFAULT_DATE_FORMAT'),strtotime(isset($candwservice->dtDateTime)?$candwservice->dtDateTime:$defaultDt)), array('class' => 'form-control', 'data-provider' => 'flatpickr', 'data-date-format' => Config::get('Constant.DEFAULT_DATE_FORMAT'), 'data-enable-time' => '','maxlength'=>160,'size'=>'16','id'=>'cwServices_date','autocomplete'=>'off','onkeypress'=>"javascript: return KeycheckOnlyDate(event);",'onpaste'=>'return false')) !!}
                                        </div>
                                        <span class="help-block">{{ $errors->first('start_date_time') }}</span>
                                    </div>
                                </div>

                                @php $defaultDt = (null !== old('end_date_time'))?old('end_date_time'):null; @endphp
                                @if ((isset($candwservice->dtEndDateTime)==null))
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
                                <div class="col-md-6" style="display:none;">
                                    <div class="mb-3 form-md-line-input">
                                        @php if(isset($candwservice_highLight->dtEndDateTime) && ($candwservice_highLight->dtEndDateTime != $candwservice->dtEndDateTime)){
                                        $Class_end_date = " highlitetext";
                                        }else{
                                        $Class_end_date = "";
                                        } @endphp
                                        <div class=" form_meridian_datetime expirydate @if($errors->first('end_date_time')) has-error @endif" data-date="{{ Carbon\Carbon::today()->format('Y-m-d') }}T15:25:00Z" @if ($expChecked_yes==1) style="display:none;" @endif>
                                            <label class="form-label {!! $Class_end_date !!}">{{ trans('news::template.common.endDateAndTime') }} <span aria-required="true" class="required"> * </span></label>
                                            
                                            <div class="input-group date">
                                                <span class="input-group-text"><i class="ri-calendar-fill"></i></span>
                                                {!! Form::text('end_date_time', isset($candwservice->dtEndDateTime)?date(Config::get('Constant.DEFAULT_DATE_FORMAT'),strtotime($candwservice->dtEndDateTime)):$defaultDt, array('class' => 'form-control', 'data-provider' => 'flatpickr', 'data-date-format' => Config::get('Constant.DEFAULT_DATE_FORMAT'), 'data-enable-time' => '','maxlength'=>160,'size'=>'16','id'=>'end_date_time','data-exp'=> $expChecked_yes,'data-newvalue','autocomplete'=>'off','onkeypress'=>"javascript: return KeycheckOnlyDate(event);",'onpaste'=>'return false')) !!}
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

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="image_thumb multi_upload_images">
                                        <div class="mb-3">
                                            @php if(isset($candwservice_highLight->fkIntDocId) && ($candwservice_highLight->fkIntDocId != $candwservice->fkIntDocId)){
                                            $Class_file = " highlitetext";
                                            }else{
                                            $Class_file = "";
                                            } @endphp
                                            <label class="form_title {!! $Class_file !!}">Upload File<span aria-required="true" class="required"> * </span></label>
                                            <div class="clearfix"></div>
                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width:100%;float:left; height:120px;position: relative;">
                                                    <img class="img_opacity" src="{{ $CDN_PATH.'resources/images/upload_file.gif' }}" />
                                                </div>
                                                <div class="input-group">
                                                    <a class="document_manager multiple-selection" data-multiple="true" onclick="MediaManager.openDocumentManager('news');"><span class="fileinput-new"></span></a>
                                                    <input class="form-control" type="hidden" id="news" name="doc_id" value="{{ isset($candwservice->fkIntDocId)?$candwservice->fkIntDocId:old('doc_id') }}" />
                                                    @php
                                                    if (method_exists($MyLibrary, 'GetDocumentFolderID')) {
                                                    if(isset($candwservice->fkIntDocId)){
                                                    $folderid = App\Helpers\MyLibrary::GetDocumentFolderID($candwservice->fkIntDocId);
                                                    @endphp
                                                    @if(isset($folderid->fk_folder) && $folderid->fk_folder != '0')
                                                    <input class="form-control" type="hidden" id="folder_id" name="folder_id" value="{{ $folderid->fk_folder }}" />
                                                    @endif
                                                    @php
                                                    }
                                                    }
                                                    @endphp
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <span>(Recommended documents *.txt, *.pdf, *.doc, *.docx, *.ppt, *.xls, *.xlsx, *.xlsm formats are supported. Document should be maximum size of 45 MB.)</span>
                                    </div>
                                </div>
                                @if(!empty($candwservice->fkIntDocId) && isset($candwservice->fkIntDocId))
                                @php
                                $docsAray = explode(',', $candwservice->fkIntDocId);
                                $docObj   = App\Document::getDocDataByIds($docsAray);
                                @endphp
                                <div class="col-md-12" id="news_documents">
                                    <div class="multi_image_list" id="multi_document_list">
                                        <ul>
                                            @if(count($docObj) > 0)
                                            @foreach($docObj as $value)
                                            <li id="doc_{{ $value->id }}">
                                                <span title="{{ $value->txtDocumentName }}.{{ $value->varDocumentExtension }}">
                                                    <img  src="{{ $CDN_PATH.'assets/images/document_icon.png' }}" alt="Img" />
                                                    <a href="javascript:;" onclick="MediaManager.removeDocumentFromGallery('{{ $value->id }}');" class="delect_image" data-dismiss="fileinput"><i class="ri-delete-bin-line"></i></a>
                                                </span>
                                            </li>
                                            @endforeach
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                                @else
                                <div class="col-md-12" id="news_documents"></div>
                                @endif
                            </div>

                            <div class="row hide">
                                <div class="col-md-12">
                                    @if (Config::get('Constant.DEFAULT_VISUAL') == 'Y')
                                    <div id="body-roll">											
                                        @php
                                        $sections = [];
                                        @endphp
                                        @if(isset($candwservice))
                                        @php
                                        $sections = json_decode($candwservice->txtDescription);
                                        @endphp
                                        @endif
                                        <!-- Builder include -->
                                        @php Powerpanel\VisualComposer\Controllers\VisualComposerController::page_section(['sections'=>$sections])@endphp
                                    </div>
                                    @else
                                    <div class="mb-3 @if($errors->first('description')) has-error @endif">
                                        @php if(isset($candwservice_highLight->txtDescription) && ($candwservice_highLight->txtDescription != $candwservice->txtDescription)){
                                        $Class_Description = " highlitetext";
                                        }else{
                                        $Class_Description = "";
                                        } @endphp
                                        <label class="form_title {!! $Class_Description !!}">{{ trans('news::template.common.description') }} <span aria-required="true" class="required"> * </span></label>
                                        {!! Form::textarea('description', isset($candwservice->txtDescription)?$candwservice->txtDescription:old('description'), array('placeholder' => trans('news::template.common.description'),'class' => 'form-control','id'=>'txtDescription')) !!}
                                        <span class="help-block">{{ $errors->first('description') }}</span>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <h3 class="form-section">{{ trans('news::template.common.displayinformation') }}</h3>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3 @if($errors->first('order')) has-error @endif form-md-line-input">
                                        @php
                                        $display_order_attributes = array('class' => 'form-control','maxlength'=>5,'placeholder'=>trans('careers::template.common.displayorder'),'autocomplete'=>'off');
                                        @endphp
                                        @if(isset($complaint_highLight->intDisplayOrder) && ($complaint_highLight->intDisplayOrder != $candwservice->intDisplayOrder))
                                        @php $Class_intDisplayOrder = " highlitetext"; @endphp
                                        @else
                                        @php $Class_intDisplayOrder = ""; @endphp
                                        @endif
                                        <label class="form_title {{ $Class_intDisplayOrder }}" for="site_name">{{ trans('careers::template.common.displayorder') }} <span aria-required="true" class="required"> * </span></label>
                                        {!! Form::text('order', isset($candwservice->intDisplayOrder)?$candwservice->intDisplayOrder:$total, $display_order_attributes) !!}
                                        <span style="color: red;">
                                            {{ $errors->first('order') }}
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    @if(isset($candwservice_highLight->chrPublish) && ($candwservice_highLight->chrPublish != $candwservice->chrPublish))
                                    @php $Class_chrPublish = " highlitetext"; @endphp
                                    @else
                                    @php $Class_chrPublish = ""; @endphp
                                    @endif

                                    @if(isset($candwservice) && $candwservice->chrAddStar == 'Y')
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="control-label form_title"> Publish/ Unpublish</label>
                                                <input type="hidden" id="chrMenuDisplay" name="chrMenuDisplay" value="{{ isset($candwservice->chrPublish) ? $candwservice->chrPublish : '' }}">
                                                <p><b>NOTE:</b> This record is in Approval Request , so it can&#39;t be published/unpublished.</p>
                                            </div>
                                        </div>
                                    @elseif(isset($candwservice) && $candwservice->chrDraft == 'D' && $candwservice->chrAddStar != 'Y')
                                        @include('powerpanel.partials.displayInfo',['Class_chrPublish'=>$Class_chrPublish,'display' => (isset($candwservice->chrDraft)?$candwservice->chrDraft:'D')])
                                    @else
                                        @include('powerpanel.partials.displayInfo',['Class_chrPublish'=>$Class_chrPublish,'display' => (isset($candwservice->chrPublish)?$candwservice->chrPublish:'Y')])
                                    @endif
                                </div>
                            </div>
                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-12">
                                        @if(isset($candwservice->fkMainRecord) && $candwservice->fkMainRecord != 0)
                                        <button type="submit" name="saveandexit" class="btn btn-primary" value="saveandexit">{!! trans('news::template.common.approve') !!}</button>
                                        @else
                                        @if($userIsAdmin)
                                        <button type="submit" name="saveandedit" class="btn btn-primary" value="saveandedit">{!! trans('news::template.common.saveandedit') !!}</button>
                                        <button type="submit" name="saveandexit" class="btn btn-primary" value="saveandexit">{!! trans('news::template.common.saveandexit') !!}</button>
                                        @else
                                        @if((isset($chrNeedAddPermission) && $chrNeedAddPermission == 'N') && (isset($charNeedApproval) && $charNeedApproval == 'N'))
                                        <button type="submit" name="saveandexit" class="btn btn-primary" value="saveandexit">{!! trans('news::template.common.saveandexit') !!}</button>
                                        @else
                                        <button type="submit" name="saveandexit" class="btn btn-primary" value="approvesaveandexit">{!! trans('news::template.common.approvesaveandexit') !!}</button>
                                        @endif
                                        @endif
                                        @endif
                                        <a class="btn btn-danger" href="{{ url('powerpanel/candwservice') }}">{{ trans('news::template.common.cancel') }}</a>
                                        @if(isset($candwservice) && !empty($candwservice) && $userIsAdmin)
                                        &nbsp;<a style="display: none" class="btn btn-primary" title="Preview" onClick="generatePreview('{{url('/previewpage?url='.(App\Helpers\MyLibrary::getFrontUri('news')['uri']))}}');">Preview</a>
                                        @endif
                                    </div>
                                </div>
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
@endif
@endsection
@section('scripts')
@if (Config::get('Constant.DEFAULT_VISUAL') == 'Y')
@php Powerpanel\VisualComposer\Controllers\VisualComposerController::get_visual_checkEditor()@endphp
@else
@include('powerpanel.partials.ckeditor',['config'=>'docsConfig'])
@endif

<script type="text/javascript">
    function OpenPassword(val) {
    if (val == 'PP') {
    $("#passid").show();
    } else {
    $("#passid").hide();
    }
    }
    window.site_url = '{!! url("/") !!}';
    var seoFormId = 'frmCandWService';
    var user_action = "{{ isset($candwservice)?'edit':'add' }}";
    var moduleAlias = "{{ App\Helpers\MyLibrary::getFrontUri('news')['moduleAlias'] }}";
    var preview_add_route = '{!! route("powerpanel.news.addpreview") !!}';
    var previewForm = $('#frmCandWService');
    var isDetailPage = true;
    function generate_seocontent1(formname) {
    var Meta_Title = document.getElementById('title').value + "";
    var abcd = document.getElementById('varShortDescription').value;
    var def = abcd.replace(/<a(\s[^>]*)?>.*?<\/a>/ig, "")
            var abc = def.replace(/^(\s*)|(\s*)$/g, '').replace(/\s+/g, ' ');
    var outString1 = abc.replace(/(<([^>]+)>)/ig, "");
    var Meta_Description = outString1.substr(0, 200);
    var Meta_Keyword = "";
    $('#varMetaTitle').val(Meta_Title);
    //                            $('#varMetaKeyword').val(Meta_Keyword);
    $('#varMetaDescription').val(Meta_Description);
    $('#meta_title').html(Meta_Title);
    $('#meta_description').html(Meta_Description);
    }
</script>
<!-- <script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-taginput/bootstrap-tagsinput.min.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js' }}" type="text/javascript"></script> -->
<script src="{{ $CDN_PATH.'resources/pages/scripts/custom.js' }}" type="text/javascript"></script>
<!-- <script src="{{ $CDN_PATH.'resources/global/plugins/custom-alias/alias-generator.js' }}" type="text/javascript"></script> -->
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/seo-generator/seo-info-generator.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/candwservice/candwservice_validations.js' }}" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS -->
@if (Config::get('Constant.DEFAULT_VISUAL') == 'Y')
@php Powerpanel\VisualComposer\Controllers\VisualComposerController::get_builder_css_js()@endphp
@endif
@endsection