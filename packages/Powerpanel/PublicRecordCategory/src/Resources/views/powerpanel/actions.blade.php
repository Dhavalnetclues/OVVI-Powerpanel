@section('css')
<link href="{{ $CDN_PATH.'resources/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css' }}" rel="stylesheet" type="text/css" />
<link href="{{ $CDN_PATH.'resources/global/plugins/bootstrap-taginput/bootstrap-tagsinput.css' }}" rel="stylesheet" type="text/css" />
<!-- BEGIN PAGE LEVEL PLUGINS -->
<!-- END PAGE LEVEL PLUGINS -->
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
                    {!! Form::open(['method' => 'post','id'=>'frmPublicRecordCategory']) !!}
                        {!! Form::hidden('fkMainRecord', isset($publicrecordCategory->fkMainRecord)?$publicrecordCategory->fkMainRecord:old('fkMainRecord')) !!}
                        <div class="form-body">
                            @if(isset($publicrecordCategory))
                            @if (File::exists(base_path() . '/resources/views/powerpanel/partials/lockedpage.blade.php') != null)
                            @include('powerpanel.partials.lockedpage',['pagedata'=>$publicrecordCategory])
                            @endif
                            @endif
                            
                            <!-- Sector type -->
                            <div class="mb-3 @if($errors->first('sector')) has-error @endif form-md-line-input">
                                @if(isset($publicrecordCategoryHighLight->varSector) && ($publicrecordCategoryHighLight->varSector != $publicrecordCategory->varSector))
                                    @php $Class_varSector = " highlitetext"; @endphp
                                @else
                                    @php $Class_varSector = ""; @endphp
                                @endif
                                    @if($hasRecords > 0)
                                    @php $disable = 'disabled'; @endphp
                                    @else
                                    @php $disable = ''; @endphp
                                    @endif
                                @include('shiledcmstheme::powerpanel.partials.sector-dropdown', ['selected_sector' => isset($publicrecordCategory->varSector)?$publicrecordCategory->varSector:'','Class_varSector' => $Class_varSector,'disable' => $disable])
                                <span class="help-block">
                                    {{ $errors->first('sector') }}
                                </span>
                            </div>
                            @if(isset($disable) && !empty($disable))
                            <input type="hidden" name="sector" value="{{isset($publicrecordCategory->varSector)?$publicrecordCategory->varSector:''}}" />
                            @endif

                            @if(isset($publicrecordCategoryHighLight->varTitle) && ($publicrecordCategoryHighLight->varTitle != $publicrecordCategory->varTitle))
                            @php $Class_title = " highlitetext"; @endphp
                            @else
                            @php $Class_title = ""; @endphp
                            @endif
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3 @if($errors->first('title')) has-error @endif form-md-line-input">
                                        <label class="form-label {{ $Class_title }}" for="site_name">{{ trans('public-record-category::template.common.title') }} <span aria-required="true" class="required"> * </span></label>
                                        {!! Form::text('title', isset($publicrecordCategory->varTitle) ? $publicrecordCategory->varTitle : old('title'), array('maxlength' => 150,'id'=>'title', 'class' => 'form-control hasAlias seoField maxlength-handler titlespellingcheck','data-url' => 'powerpanel/public-record-category','placeholder' => trans('public-record-category::template.common.title'),'autocomplete'=>'off')) !!}
                                        <span class="help-block">
                                            {{ $errors->first('title') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @php
                            $ignoreIdsForAliasUpdate = [0]; 
                            @endphp
                            @if(!isset($publicrecordCategory) || (isset($publicrecordCategory) && !in_array($publicrecordCategory->id, $ignoreIdsForAliasUpdate) ))
                            <div class="row hide">
                                <div class="col-md-12">
                                    <!-- code for alias -->
                                    {!! Form::hidden(null, null, array('class' => 'hasAlias','data-url' => 'powerpanel/public-record-category')) !!}
                                    {!! Form::hidden('alias', isset($publicrecordCategory->alias->varAlias) ? $publicrecordCategory->alias->varAlias:old('alias'), array('class' => 'aliasField')) !!}
                                    {!! Form::hidden('oldAlias', isset($publicrecordCategory->alias->varAlias) ? $publicrecordCategory->alias->varAlias:old('alias')) !!}
                                    {!! Form::hidden('previewId') !!}
                                    <div class="mb-3 alias-group {{!isset($publicrecordCategory->alias->varAlias)?'hide':''}}">
                                        <label class="form-label" for="Url">Url :</label>
                                        @if(isset($publicrecordCategory->alias->varAlias) && !$userIsAdmin)
                                        <a class="alias">{!! url("/") !!}</a>
                                        @else
                                        @if(auth()->user()->can('public-record-category-create'))
                                        <a href="javascript:void(0);" class="alias">{!! url("/") !!}</a>
                                        <a href="javascript:void(0);" class="editAlias" title="Edit">
                                            <i class="ri-pencil-line"></i>
                                        </a>
                                        <a class="without_bg_icon openLink" title="Open Link" onClick="generatePreview('{{ url('/previewpage?url='.(App\Helpers\MyLibrary::getFrontUri('public-record-category')['uri'])) }}');">
                                            <i class="ri-external-link-line" aria-hidden="true"></i>
                                        </a>
                                        @endif
                                        @endif
                                    </div>
                                    <span class="help-block">{{ $errors->first('alias') }}</span>
                                    <!-- code for alias -->
                                </div>
                            </div>
                            @endif
                            <div class="row" style="display: none">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        @if(isset($publicrecordCategoryHighLight->intParentCategoryId) && ($publicrecordCategoryHighLight->intParentCategoryId != $publicrecordCategory->intParentCategoryId))
                                        @php $Class_intParentCategoryId = " highlitetext"; @endphp
                                        @else
                                        @php $Class_intParentCategoryId = ""; @endphp
                                        @endif
                                        <label class="form-label {{ $Class_intParentCategoryId }}" for="parent_category_id">{{ trans('public-record-category::template.public_record_categoryModule.selectparentPublicRecordCategory') }}<span aria-required="true" class="required"> * </span></label>
                                        @php echo $categories; @endphp
                                    </div>
                                </div>
                            </div>
                            
                            <h3 class="form-section hide">{{ trans('public-record-category::template.common.ContentScheduling') }}</h3>
                            @php $defaultDt = (null !== old('start_date_time'))?old('start_date_time'):date('Y-m-d H:i'); @endphp
                            <div class="row hide">
                                <div class="col-md-6">
                                    <div class="mb-3 form-md-line-input">
                                        @php if(isset($publicrecordCategoryHighLight->dtDateTime) && ($publicrecordCategoryHighLight->dtDateTime != $publicrecordCategory->dtDateTime)){
                                        $Class_date = " highlitetext";
                                        }else{
                                        $Class_date = "";
                                        } @endphp
                                        <label class="control-label form-label {!! $Class_date !!}">{{ trans('public-record-category::template.common.startDateAndTime') }}<span aria-required="true" class="required"> * </span></label>
                                        <div class="input-group date form_meridian_datetime @if($errors->first('start_date_time')) has-error @endif" data-date="{{ Carbon\Carbon::today()->format('Y-m-d') }}T15:25:00Z">
                                            <span class="input-group-text date_default" id="basic-addon1">
                                                <i class="ri-calendar-fill"></i>
                                            </span>
                                            {!! Form::text('start_date_time', date('Y-m-d H:i',strtotime(isset($publicrecordCategory->dtDateTime)?$publicrecordCategory->dtDateTime:$defaultDt)), array('class' => 'form-control', 'data-provider' => 'flatpickr', 'data-date-format' => Config::get('Constant.DEFAULT_DATE_FORMAT'), 'data-enable-time' => '','maxlength'=>160,'size'=>'16','id'=>'start_date_time','autocomplete'=>'off','onkeypress'=>"javascript: return KeycheckOnlyDate(event);",'onpaste'=>'return false')) !!}
                                        </div>
                                        <span class="help-block">{{ $errors->first('start_date_time') }}</span>
                                    </div>
                                </div>
                                @php $defaultDt = (null !== old('end_date_time'))?old('end_date_time'):null; @endphp
                                @if ((isset($publicrecordCategory->dtEndDateTime)==null))
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
                                <div class="col-md-6">
                                    <div class="mb-3 form-md-line-input">
                                        @php if(isset($publicrecordCategoryHighLight->varTitle) && ($publicrecordCategoryHighLight->dtEndDateTime != $publicrecordCategory->dtEndDateTime)){
                                        $Class_end_date = " highlitetext";
                                        }else{
                                        $Class_end_date = "";
                                        } @endphp
                                        <div class=" form_meridian_datetime expirydate @if($errors->first('end_date_time')) has-error @endif" data-date="{{ Carbon\Carbon::today()->format('Y-m-d') }}T15:25:00Z" @if ($expChecked_yes==1) style="display:none;" @endif>
                                            <label class="form-label {!! $Class_end_date !!}">{{ trans('public-record-category::template.common.endDateAndTime') }} <span aria-required="true" class="required"> * </span></label>
                                            
                                            <div class="input-group date">
                                                <span class="input-group-text"><i class="ri-calendar-fill"></i></span>
                                                {!! Form::text('end_date_time', isset($publicrecordCategory->dtEndDateTime)?date('Y-m-d H:i',strtotime($publicrecordCategory->dtEndDateTime)):$defaultDt, array('class' => 'form-control', 'data-provider' => 'flatpickr', 'data-date-format' => Config::get('Constant.DEFAULT_DATE_FORMAT'), 'data-enable-time' => '','maxlength'=>160,'size'=>'16','id'=>'end_date_time','data-exp'=> $expChecked_yes,'data-newvalue','autocomplete'=>'off','onkeypress'=>"javascript: return KeycheckOnlyDate(event);",'onpaste'=>'return false')) !!}
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
                            @if(isset($publicrecordCategory->intSearchRank))
                                @php $srank = $publicrecordCategory->intSearchRank; @endphp
                            @else
                                @php $srank = null !== old('search_rank') ? old('search_rank') : 2 ; @endphp
                            @endif

                            @if(isset($publicrecordCategoryHighLight->intSearchRank) && ($publicrecordCategoryHighLight->intSearchRank != $publicrecordCategory->intSearchRank))
                                @php $Class_intSearchRank = " highlitetext"; @endphp
                            @else
                                @php $Class_intSearchRank = ""; @endphp
                            @endif
                            <div class="row hide">
                                <div class="col-md-12">
                                    <div class=" form-md-line-input nopadding">
                                        @include('powerpanel.partials.seoInfo',['inf_highLight'=> isset($metaInfo_highLight)?$metaInfo_highLight:false,'form'=>'frmPublicRecordCategory','inf'=>isset($metaInfo)?$metaInfo:false,'metaRequired'=>true,'Class_intSearchRank' => $Class_intSearchRank, 'srank' => $srank])
                                    </div>
                                </div>
                            </div>
                            <h3 class="form-section">{{ trans('public-record-category::template.common.displayinformation') }}</h3>
                            <div class="row">
                                <div class="col-md-6">
                                    @php
                                    $display_order_attributes = array('class' => 'form-control','maxlength'=>5,'placeholder'=>trans('public-record-category::template.common.displayorder'),'autocomplete'=>'off');
                                    @endphp
                                    @if(isset($publicrecordCategoryHighLight->intDisplayOrder) && ($publicrecordCategoryHighLight->intDisplayOrder != $publicrecordCategory->intDisplayOrder))
                                    @php $Class_intDisplayOrder = " highlitetext"; @endphp
                                    @else
                                    @php $Class_intDisplayOrder = ""; @endphp
                                    @endif
                                    <div class="mb-3 @if($errors->first('display_order')) has-error @endif form-md-line-input">
                                        <label class="form-label {{ $Class_intDisplayOrder }}" class="site_name">{{ trans('public-record-category::template.common.displayorder') }} <span aria-required="true" class="required"> * </span></label>
                                        {!! Form::text('display_order', isset($publicrecordCategory->intDisplayOrder)?$publicrecordCategory->intDisplayOrder : 1, $display_order_attributes) !!}
                                        <span class="help-block">
                                            <strong>{{ $errors->first('display_order') }}</strong>
                                        </span>
                                    </div>
                                </div>
                                @if($isParent==0 && $hasRecords==0)
                                <div class="col-md-6">
                                    @if(isset($publicrecordCategoryHighLight->chrPublish) && ($publicrecordCategoryHighLight->chrPublish != $publicrecordCategory->chrPublish))
                                        @php $Class_chrPublish = " highlitetext"; @endphp
                                    @else
                                        @php $Class_chrPublish = ""; @endphp
                                    @endif

                                    @if(isset($publicrecordCategory) && $publicrecordCategory->chrAddStar == 'Y')
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="control-label form-label"> Publish/ Unpublish</label>
                                                <input type="hidden" id="chrMenuDisplay" name="chrMenuDisplay" value="{{ isset($publicrecordCategory->chrPublish) ? $publicrecordCategory->chrPublish : '' }}">
                                                <p><b>NOTE:</b> This record is in Approval Request , so it can&#39;t be published/unpublished.</p>
                                            </div>
                                        </div>
                                    @elseif(isset($publicrecordCategory) && $publicrecordCategory->chrDraft == 'D' && $publicrecordCategory->chrAddStar != 'Y')
                                        @include('powerpanel.partials.displayInfo',['Class_chrPublish'=>$Class_chrPublish,'display' => (isset($publicrecordCategory->chrDraft)?$publicrecordCategory->chrDraft:'D')])
                                    @else
                                        @include('powerpanel.partials.displayInfo',['Class_chrPublish'=>$Class_chrPublish,'display' => (isset($publicrecordCategory->chrPublish)?$publicrecordCategory->chrPublish:'Y')])
                                    @endif
                                </div>
                                @else
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="control-label form-label"> Publish/ Unpublish</label>
                                        @if($hasRecords > 0 && $isParent > 0)
                                        <input type="hidden" id="chrMenuDisplay" name="chrMenuDisplay" value="{{ $publicrecordCategory->chrPublish }}">
                                        <p><b>NOTE:</b> This category is selected in Public record and also its a parent category so it can&#39;t be published/unpublished.</p>
                                        @elseif($isParent > 0)
                                        <input type="hidden" id="chrMenuDisplay" name="chrMenuDisplay" value="{{ $publicrecordCategory->chrPublish }}">
                                        <p><b>NOTE:</b> This category is selected as Parent Category, so it can&#39;t be published/unpublished.</p>
                                        @elseif($hasRecords > 0)
                                        <input type="hidden" id="chrMenuDisplay" name="chrMenuDisplay" value="{{ $publicrecordCategory->chrPublish }}">
                                        <p><b>NOTE:</b> This category is selected in Public record, so it can&#39;t be published/unpublished.</p>
                                        @endif
                                    </div>
                                </div>
                                @endif

                            </div>
                        </div>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-12">
                                    @if(isset($publicrecordCategory->fkMainRecord) && $publicrecordCategory->fkMainRecord != 0)
                                    <button type="submit" name="saveandexit" class="btn btn-primary" value="saveandexit">{!! trans('public-record-category::template.common.approve') !!}</button>
                                    @else
                                    @if($userIsAdmin)
                                    <button type="submit" name="saveandedit" class="btn btn-primary" value="saveandedit">{!! trans('public-record-category::template.common.saveandedit') !!}</button>
                                    <button type="submit" name="saveandexit" class="btn btn-primary" value="saveandexit">{!! trans('public-record-category::template.common.saveandexit') !!}</button>
                                    @else
                                    @if((isset($chrNeedAddPermission) && $chrNeedAddPermission == 'N') && (isset($charNeedApproval) && $charNeedApproval == 'N'))
                                    <button type="submit" name="saveandexit" class="btn btn-primary" value="saveandexit">{!! trans('public-record-category::template.common.saveandexit') !!}</button>
                                    @else
                                    <button type="submit" name="saveandexit" class="btn btn-primary" value="approvesaveandexit">{!! trans('public-record-category::template.common.approvesaveandexit') !!}</button>
                                    @endif
                                    @endif
                                    @endif
                                    <a class="btn btn-danger" href="{{ url('powerpanel/public-record-category') }}">{{ trans('public-record-category::template.common.cancel') }}</a>
                                    @if(isset($publicrecordCategory) && $userIsAdmin)
                                    &nbsp;<a style="display: none" class="btn btn-primary" title="Preview" onClick="generatePreview('{{url('/previewpage?url='.(App\Helpers\MyLibrary::getFrontUri('public-record-category')['uri']))}}');">Preview</a>
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
@endsection
@section('scripts')
<script type="text/javascript">
            window.site_url = '{!! url("/") !!}';
            var seoFormId = 'frmPublicRecordCategory';
            var user_action = "{{ isset($publicrecordCategory)?'edit':'add' }}";
            var moduleAlias = "{{ App\Helpers\MyLibrary::getFrontUri('public-record-category')['moduleAlias'] }}";
            var preview_add_route = '{!! route("powerpanel.public-record-category.addpreview") !!}';
            var previewForm = $('#frmPublicRecordCategory');
            var isDetailPage = false;
            function generate_seocontent1(formname) {
            var Meta_Title = document.getElementById('title').value + " ";
                    var Meta_Description = "" + document.getElementById('title').value + "";
                    var Meta_Keyword = document.getElementById('title').value + "";
                    $('#varMetaTitle').val(Meta_Title);
//                    $('#varMetaKeyword').val(Meta_Keyword);
                    $('#varMetaDescription').val(Meta_Description);
                    $('#meta_title').html(Meta_Title);
                    $('#meta_description').html(Meta_Description);
            }
</script>
<script type="text/javascript">
    function OpenPassword(val) {
    if (val == 'PP') {
    $("#passid").show();
    } else {
    $("#passid").hide();
    }
    }
</script>
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-taginput/bootstrap-tagsinput.min.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/publicrecordcategory/publicrecordcategory-validations.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/custom.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/seo-generator/seo-info-generator.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/custom-alias/alias-generator.js' }}" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js' }}" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS -->
@endsection