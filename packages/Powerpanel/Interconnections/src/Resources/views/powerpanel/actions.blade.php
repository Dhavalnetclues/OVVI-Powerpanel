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
                    {!! Form::open(['method' => 'post','id'=>'frmInterconnections']) !!}
                        {!! Form::hidden('fkMainRecord', isset($interconnections->fkMainRecord)?$interconnections->fkMainRecord:old('fkMainRecord')) !!}
                        <div class="form-body">
                            @if(isset($interconnections))
                            @if (File::exists(base_path() . '/resources/views/powerpanel/partials/lockedpage.blade.php') != null)
                            @include('powerpanel.partials.lockedpage',['pagedata'=>$interconnections])
                            @endif
                            @endif

                            <div class="mb-3 @if($errors->first('sector')) has-error @endif form-md-line-input">
                                @if(isset($consultations_highLight->varSector) && ($consultations_highLight->varSector != $interconnections->varSector))
                                    @php $Class_varSector = " highlitetext"; @endphp
                                @else
                                    @php $Class_varSector = ""; @endphp
                                @endif
                                @if($hasRecords > 0)
                                @php $disable = 'disabled'; @endphp
                                @else
                                @php $disable = ''; @endphp
                                @endif
                                @include('shiledcmstheme::powerpanel.partials.sector-dropdown', ['selected_sector' => isset($interconnections->varSector)?$interconnections->varSector:'','Class_varSector' => $Class_varSector,'disable' => $disable])
                                <span class="help-block">
                                    {{ $errors->first('sector') }}
                                </span>
                            </div>

                            @if(isset($disable) && !empty($disable))
                            <input type="hidden" name="sector" value="{{isset($interconnections->varSector)?$interconnections->varSector:''}}" />
                            @endif

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        @if(isset($interconnectionsHighLight->intParentCategoryId) && ($interconnectionsHighLight->intParentCategoryId != $interconnections->intParentCategoryId))
                                            @php $Class_intParentCategoryId = " highlitetext"; @endphp
                                        @else
                                            @php $Class_intParentCategoryId = ""; @endphp
                                        @endif

                                        <label class="form-label {{ $Class_intParentCategoryId }}" for="parent_category_id">{{ trans('interconnections::template.interconnectionsModule.selectparentcategory') }}</label>
                                        @php echo $categories; @endphp
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3 @if($errors->first('title')) has-error @endif form-md-line-input">
                                        @if(isset($interconnectionsHighLight->varTitle) && ($interconnectionsHighLight->varTitle != $interconnections->varTitle))
                                        @php $Class_varTitle = " highlitetext"; @endphp
                                        @else
                                        @php $Class_varTitle = ""; @endphp
                                        @endif
                                        <label class="form-label {{ $Class_varTitle }}" for="site_name">{{ trans('interconnections::template.common.categoryTitle') }} <span aria-required="true" class="required"> * </span></label>
                                        {!! Form::text('title', isset($interconnections->varTitle) ? $interconnections->varTitle : old('title'), array('maxlength' => 150, 'class' => 'form-control hasAlias seoField maxlength-handler titlespellingcheck','data-url' => 'powerpanel/interconnections','placeholder' => trans('interconnections::template.common.categoryTitle'),'autocomplete'=>'off')) !!}
                                        <span class="help-block">
                                            {{ $errors->first('title') }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            @php $defaultDt = (null !== old('start_date_time'))?old('start_date_time'):date('Y-m-d'); @endphp
                            <div class="row" id="pubdate">
                                <div class="col-md-6">
                                    <div class="mb-3 form-md-line-input">
                                        @php if(isset($interconnectionsHighLight->dtDateTime) && ($interconnectionsHighLight->dtDateTime != $interconnections->dtDateTime)){
                                        $Class_date = " highlitetext";
                                        }else{
                                        $Class_date = "";
                                        } @endphp
                                        <label class="control-label form-label {!! $Class_date !!}">{{ trans('interconnections::template.interconnectionsModule.publishDate') }}<span aria-required="true" class="required"> * </span></label>
                                        <div class="input-group date form_meridian_datetime @if($errors->first('start_date_time')) has-error @endif" data-date="{{ Carbon\Carbon::today()->format('Y-m-d') }}T15:25:00Z">
                                            <span class="input-group-text date_default" id="basic-addon1">
                                                <i class="ri-calendar-fill"></i>
                                            </span>
                                            {!! Form::text('start_date_time', date(Config::get('Constant.DEFAULT_DATE_FORMAT'),strtotime(isset($interconnections->dtDateTime)?$interconnections->dtDateTime:$defaultDt)), array('class' => 'form-control', 'data-provider' => 'flatpickr', 'data-date-format' => Config::get('Constant.DEFAULT_DATE_FORMAT'), 'data-enable-time' => '','maxlength'=>160,'size'=>'16','id'=>'interconnection_date','autocomplete'=>'off','onkeypress'=>"javascript: return KeycheckOnlyDate(event);",'onpaste'=>'return false')) !!}
                                        </div>
                                        <span class="help-block">{{ $errors->first('start_date_time') }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="row" id="docHide">
                                <div class="col-md-12">
                                    @if(isset($interconnectionsHighLight->fkIntDocId) && ($interconnectionsHighLight->fkIntDocId != $interconnections->fkIntDocId))
                                        @php $Class_fkIntDocId = " highlitetext"; @endphp
                                    @else
                                        @php $Class_fkIntDocId = ""; @endphp
                                    @endif
                                    <div class="image_thumb multi_upload_images">
                                        <div class="mb-3">
                                            <label class="form-label {{ $Class_fkIntDocId }}">Select Documents<span aria-required="true" class="required"> * </span></label>
                                            <div class="clearfix"></div>
                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width:100%;float:left; height:120px;position: relative;">
                                                    <img class="img_opacity" src="{{ $CDN_PATH.'resources/images/upload_file.gif' }}" />
                                                </div>
                                                <div class="input-group">
                                                    <a class="document_manager multiple-selection" data-multiple="false" onclick="MediaManager.openDocumentManager('interconnections');"><span class="fileinput-new"></span></a>
                                                    <input class="form-control" type="hidden" id="interconnections" name="doc_id" value="{{ isset($interconnections->fkIntDocId)?$interconnections->fkIntDocId:old('doc_id') }}" />
                                                    @php
                                                        if (method_exists($MyLibrary, 'GetDocumentFolderID')) {
                                                            if(isset($interconnections->fkIntDocId)){
                                                                $folderid = App\Helpers\MyLibrary::GetDocumentFolderID($interconnections->fkIntDocId);
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
                                    <span class="help-block">
                                        {{ $errors->first('doc_id') }}
                                    </span>
                                </div>
                                @if(!empty($interconnections->fkIntDocId) && isset($interconnections->fkIntDocId))
                                    @php
                                        $docsAray = explode(',', $interconnections->fkIntDocId);
                                        $docObj   = App\Document::getDocDataByIds($docsAray);
                                    @endphp
                                    <div class="col-md-12" id="interconnections_documents">
                                        <div class="multi_image_list" id="multi_document_list">
                                            <ul>
                                                @if(count($docObj) > 0)
                                                    @foreach($docObj as $value)
                                                        @php 
                                                            $imageLink = 'document_icon.png';
                                                            if($value->varDocumentExtension === 'txt') {
                                                                $imageLink = 'documents_logo/txt.png';
                                                            }
                                                            if($value->varDocumentExtension === 'pdf') {
                                                                $imageLink = 'documents_logo/pdf.png';
                                                            }
                                                            if($value->varDocumentExtension === 'xls' || $value->varDocumentExtension === 'xlsx') {
                                                                $imageLink = 'documents_logo/xls.png';
                                                            }
                                                            if($value->varDocumentExtension === 'ppt') {
                                                                $imageLink = 'documents_logo/ppt.png';
                                                            }
                                                            if($value->varDocumentExtension === 'doc') {
                                                                $imageLink = 'documents_logo/doc.png';
                                                            }
                                                            $downloadPath = $CDN_PATH.'documents/'.$value->txtSrcDocumentName.'.'.$value->varDocumentExtension;
                                                        @endphp
                                                        <li id="doc_{{ $value->id }}" >
                                                            <a href="{{$downloadPath}}" download>
                                                                <span title="{{ $value->txtDocumentName }}.{{ $value->varDocumentExtension }}">
                                                                    <img  src="{{ $CDN_PATH.'assets/images/'.$imageLink }}" alt="Img" />
                                                                    <a href="javascript:;" onclick="MediaManager.removeDocumentFromGallery('{{ $value->id }}');" class="delect_image" data-dismiss="fileinput"><i class="ri-delete-bin-line"></i></a>
                                                                </span>
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                @else
                                    <div class="col-md-12" id="interconnections_documents"></div>
                                @endif
                            </div>

                            <br/>

                            <div class="mb-3 d-none @if($errors->first('txtShortDescription')) has-error @endif form-md-line-input">
                                @php 
                                    if(isset($interconnectionsHighLight->txtShortDescription) && ($interconnectionsHighLight->txtShortDescription != $interconnections->txtShortDescription)){
                                        $Class_txtShortDescription = " highlitetext";
                                    }else{
                                        $Class_txtShortDescription = "";
                                    }
                                @endphp
                                <label class="form-label {!! $Class_txtShortDescription !!}" for="site_name">Short Description </label>
                                {!! Form::textarea('txtShortDescription', isset($interconnections->txtShortDescription) ? $interconnections->txtShortDescription:old('txtShortDescription'), array('maxlength'=>'300','placeholder' => 'Short Description', 'rows'=>'3','class' => 'form-control seoField maxlength-handler txtShortDescriptionspellingcheck','autocomplete'=>'off')) !!}
                                <span class="help-block">
                                    {{ $errors->first('txtShortDescription') }}
                                </span>
                            </div>

                            <h3 class="form-section">{{ trans('interconnections::template.common.displayinformation') }}</h3>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    @php
                                        $display_order_attributes = array('class' => 'form-control','maxlength'=>5,'placeholder'=>trans('interconnections::template.common.displayorder'),'autocomplete'=>'off');
                                    @endphp
                                    <div class="@if($errors->first('display_order')) has-error @endif form-md-line-input">
                                        @if(isset($interconnectionsHighLight->intDisplayOrder) && ($interconnectionsHighLight->intDisplayOrder != $interconnections->intDisplayOrder))
                                            @php $Class_intDisplayOrder = " highlitetext"; @endphp
                                        @else
                                            @php $Class_intDisplayOrder = ""; @endphp
                                        @endif
                                        <label class="form-label {{ $Class_intDisplayOrder }}" class="site_name">{{ trans('interconnections::template.common.displayorder') }} <span aria-required="true" class="required"> * </span></label>
                                        {!! Form::text('display_order', isset($interconnections->intDisplayOrder)?$interconnections->intDisplayOrder : '1', $display_order_attributes) !!}
                                        <span class="help-block">
                                            <strong>{{ $errors->first('display_order') }}</strong>
                                        </span>
                                    </div>
                                </div>
                                @if($isParent==0 && $hasRecords==0)
                                <div class="col-md-6">
                                    @if(isset($interconnectionsHighLight->chrPublish) && ($interconnectionsHighLight->chrPublish != $interconnections->chrPublish))
                                        @php $Class_chrPublish = " highlitetext"; @endphp
                                    @else
                                        @php $Class_chrPublish = ""; @endphp
                                    @endif

                                    @if(isset($interconnections) && $interconnections->chrAddStar == 'Y')
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="control-label form-label"> Publish/ Unpublish</label>
                                                <input type="hidden" id="chrMenuDisplay" name="chrMenuDisplay" value="{{ isset($interconnections->chrPublish) ? $interconnections->chrPublish : '' }}">
                                                <p><b>NOTE:</b> This record is in Approval Request , so it can&#39;t be published/unpublished.</p>
                                            </div>
                                        </div>
                                    @elseif(isset($interconnections) && $interconnections->chrDraft == 'D' && $interconnections->chrAddStar != 'Y')
                                        @include('powerpanel.partials.displayInfo',['Class_chrPublish'=>$Class_chrPublish,'display' => (isset($interconnections->chrDraft)?$interconnections->chrDraft:'D')])
                                    @else
                                        @include('powerpanel.partials.displayInfo',['Class_chrPublish'=>$Class_chrPublish,'display' => (isset($interconnections->chrPublish)?$interconnections->chrPublish:'Y')])
                                    @endif
                                </div>
                                @else
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="control-label form-label"> Publish/ Unpublish</label>
                                        @if($hasRecords > 0 && $isParent > 0)
                                        <input type="hidden" id="chrMenuDisplay" name="chrMenuDisplay" value="{{ $interconnections->chrPublish }}">
                                        <p><b>NOTE:</b> This interconnections is selected as parent interconnections in other record so it can&#39;t be published/unpublished..</p>
                                        @elseif($isParent > 0)
                                        <input type="hidden" id="chrMenuDisplay" name="chrMenuDisplay" value="{{ $interconnections->chrPublish }}">
                                        <p><b>NOTE:</b> This category is selected as Parent Category, so it can&#39;t be published/unpublished.</p>
                                        @elseif($hasRecords > 0)
                                        <input type="hidden" id="chrMenuDisplay" name="chrMenuDisplay" value="{{ $interconnections->chrPublish }}">
                                            <p><b>NOTE:</b> This interconnections is selected as parent interconnections in other record so it can&#39;t be published/unpublished..</p>
                                        @endif
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-12">
                                    @if(isset($interconnections->fkMainRecord) && $interconnections->fkMainRecord != 0)
                                    <button type="submit" name="saveandexit" class="btn btn-primary" value="saveandexit">{!! trans('interconnections::template.common.approve') !!}</button>
                                    @else
                                    @if($userIsAdmin)
                                    <button type="submit" name="saveandedit" class="btn btn-primary" value="saveandedit">{!! trans('interconnections::template.common.saveandedit') !!}</button>
                                    <button type="submit" name="saveandexit" class="btn btn-primary" value="saveandexit">{!! trans('interconnections::template.common.saveandexit') !!}</button>
                                    @else
                                    @if((isset($chrNeedAddPermission) && $chrNeedAddPermission == 'N') && (isset($charNeedApproval) && $charNeedApproval == 'N'))
                                    <button type="submit" name="saveandexit" class="btn btn-primary" value="saveandexit">{!! trans('interconnections::template.common.saveandexit') !!}</button>
                                    @else
                                    <button type="submit" name="saveandexit" class="btn btn-primary" value="approvesaveandexit">{!! trans('interconnections::template.common.approvesaveandexit') !!}</button>
                                    @endif
                                    @endif
                                    @endif
                                    <a class="btn btn-danger" href="{{ url('powerpanel/interconnections') }}">{{ trans('interconnections::template.common.cancel') }}</a>
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
<!-- <link href="{{ $CDN_PATH.'resources/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css' }}" rel="stylesheet" type="text/css"/>
<link href="{{ $CDN_PATH.'resources/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css' }}" rel="stylesheet" type="text/css" />
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js' }}" type="text/javascript"></script> -->
<script type="text/javascript">
    window.site_url = '{!! url("/") !!}';
    var seoFormId = 'frmInterconnections';
    var user_action = "{{ isset($interconnections)?'edit':'add' }}";
    var moduleAlias = 'interconnections';
</script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/interconnections/interconnections_validations.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js' }}" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/custom.js' }}" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS -->
@endsection