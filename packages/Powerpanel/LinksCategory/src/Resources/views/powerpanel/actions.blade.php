@section('css')
@endsection
@extends('powerpanel.layouts.app')
@section('title')
{{Config::get('Constant.SITE_NAME')}} - PowerPanel
@stop
@section('content')
@php $settings = json_decode(Config::get("Constant.MODULE.SETTINGS")); @endphp
@include('powerpanel.partials.breadcrumbs')

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
                    {!! Form::open(['method' => 'post','id'=>'frmLinksCategorys']) !!}
                        <div class="form-body">
                            {!! Form::hidden('fkMainRecord', isset($linkscategory->fkMainRecord)?$linkscategory->fkMainRecord:old('fkMainRecord')) !!}
                            @if(isset($linkscategory))
                            @if (File::exists(base_path() . '/resources/views/powerpanel/partials/lockedpage.blade.php') != null)
                            @include('powerpanel.partials.lockedpage',['pagedata'=>$linkscategory])
                            @endif
                            @endif

                            <!-- Sector type -->
                            <div class="mb-3 @if($errors->first('sector')) has-error @endif form-md-line-input">
                                @if(isset($linkscategory_highLight->varSector) && ($linkscategory_highLight->varSector != $linkscategory->varSector))
                                    @php $Class_varSector = " highlitetext"; @endphp
                                @else
                                    @php $Class_varSector = ""; @endphp
                                @endif
                                    @if($hasRecords > 0)
                                    @php $disable = 'disabled'; @endphp
                                    @else
                                    @php $disable = ''; @endphp
                                    @endif
                                @include('shiledcmstheme::powerpanel.partials.sector-dropdown', ['selected_sector' => isset($linkscategory->varSector)?$linkscategory->varSector:'','Class_varSector' => $Class_varSector,'disable' => $disable])
                                <span class="help-block">
                                    {{ $errors->first('sector') }}
                                </span>
                            </div>
                            @if(isset($disable) && !empty($disable))
                            <input type="hidden" name="sector" value="{{isset($linkscategory->varSector)?$linkscategory->varSector:''}}" />
                            @endif

                            <div class="mb-3 @if($errors->first('tag_line')) has-error @endif form-md-line-input">
                                @php if(isset($linkscategory_highLight->varTitle) && ($linkscategory_highLight->varTitle != $linkscategory->varTitle)){
                                $Class_title = " highlitetext";
                                }else{
                                $Class_title = "";
                                } @endphp
                                <label class="form-label {!! $Class_title !!}" for="site_name">{{ trans('links-category::template.common.title') }} <span aria-required="true" class="required"> * </span></label>
                                {!! Form::text('title', isset($linkscategory->varTitle) ? $linkscategory->varTitle:old('title'), array('maxlength'=>'150','placeholder' => trans('links-category::template.common.title'),'class' => 'form-control seoField maxlength-handler titlespellingcheck','autocomplete'=>'off')) !!}
                                <span class="help-block">
                                    {{ $errors->first('title') }}
                                </span>
                            </div>
                            
                                <div class="mb-3 @if($errors->first('subtitle')) has-error @endif form-md-line-input">
                                @php if(isset($linkscategory_highLight->varsubtitle) && ($linkscategory_highLight->varsubtitle != $linkscategory->varsubtitle)){
                                $Class_subtitle = " highlitetext";
                                }else{
                                $Class_subtitle = "";
                                } @endphp
                                <label class="form-label {{ $Class_subtitle }}" for="site_name">{{ trans('links-category::template.common.sub_title') }} <span aria-required="true" class="required"> * </span></label>
                                {!! Form::text('subtitle', isset($linkscategory->varsubtitle) ? $linkscategory->varsubtitle:old('subtitle'), array('maxlength'=>'150','placeholder' => trans('links-category::template.common.sub_title'),'class' => 'form-control seoField maxlength-handler titlespellingcheck','autocomplete'=>'off')) !!}
                                <span class="help-block">
                                    {{ $errors->first('subtitle') }}
                                </span>
                            </div>
                            
                            <div class="row hide">
                                <div class="col-md-12">
                                    
                                    <div class="image_thumb multi_upload_images">
                                        <div class="mb-3">
                                            <label class="form-label " for="front_logo">{{ trans('blogs::template.common.selectimage') }} <span aria-required="true" class="required"> * </span></label>
                                            <div class="clearfix"></div>
                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                <div class="fileinput-preview thumbnail blog_image_img" data-trigger="fileinput" style="width:100%;float:left; height:120px;position: relative;">
                                                    @if(old('image_url'))
                                                    <img src="{{ old('image_url') }}" />
                                                    @elseif(isset($linkscategory->fkIntImgId))
                                                    <img src="{!! App\Helpers\resize_image::resize($linkscategory->fkIntImgId,120,120) !!}" />
                                                    @else
                                                    <img class="img_opacity" src="{{ $CDN_PATH.'resources/images/upload_file.gif' }}" />
                                                    @endif
                                                </div>

                                                <div class="input-group">
                                                    <a class="media_manager" data-multiple="false" onclick="MediaManager.open('blog_image');"><span class="fileinput-new"></span></a>
                                                    <input class="form-control" type="hidden" id="blog_image" name="img_id" value="{{ isset($linkscategory->fkIntImgId)?$linkscategory->fkIntImgId:old('img_id') }}" />
                                                        @php
                                                        if (method_exists($MyLibrary, 'GetFolderID')) {
                                                            if(isset($linkscategory->fkIntImgId)){
                                                            $folderid = App\Helpers\MyLibrary::GetFolderID($linkscategory->fkIntImgId);
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
                                                    <!-- <a href="javascript:;" class="fileinput-exists remove_img removeimg" data-dismiss="fileinput"><i class="ri-delete-bin-line"></i></a> -->
                                                </div>

                                            </div>
                                            <div class="clearfix"></div>
                                            @php $height = isset($settings->height)?$settings->height:110; $width = isset($settings->width)?$settings->width:110; @endphp <span>{{ trans('links-category::template.common.imageSize',['height'=>$height, 'width'=>$width]) }}</span>
                                        </div>
                                        <span class="help-block">
                                            {{ $errors->first('img_id') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            @if(Config::get('Constant.CHRSearchRank') == 'Y')
                            @if(isset($linkscategory->intSearchRank))
                            @php $srank = $linkscategory->intSearchRank; @endphp
                            @else
                            @php
                            $srank = null !== old('search_rank') ? old('search_rank') : 2 ;
                            @endphp
                            @endif
                            @if(isset($linkscategory_highLight->intSearchRank) && ($linkscategory_highLight->intSearchRank != $linkscategory->intSearchRank))
                            @php $Class_intSearchRank = " highlitetext"; @endphp
                            @else
                            @php $Class_intSearchRank = ""; @endphp
                            @endif
                            <div class="row d-none mb-3">
                                <div class="col-md-12">
                                    <label class="{{ $Class_intSearchRank }} form-label">Search Ranking</label>
                                    <a href="javascript:void(0);" data-bs-toggle="tooltip" class="config" data-bs-placement="bottom" data-bs-content="{{ trans('links-category::template.common.SearchEntityTools') }}" title="{{ trans('links-category::template.common.SearchEntityTools') }}"><i class="fa fa-question"></i></a>
                                    <div class="md-radio-inline">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" value="1" name="search_rank" @if ($srank == 1) checked @endif id="yes_radio">
                                            <label for="yes_radio" id="yes-lbl">High</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" value="2" name="search_rank" @if ($srank == 2) checked @endif id="maybe_radio">
                                            <label for="maybe_radio" id="maybe-lbl">Medium</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" value="3" name="search_rank" @if ($srank == 3) checked @endif id="no_radio">
                                            <label for="no_radio" id="no-lbl">Low</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            <h3 class="form-section hide">{{ trans('links-category::template.common.ContentScheduling') }}</h3>
                            @php $defaultDt = (null !== old('start_date_time'))?old('start_date_time'):date(Config::get('Constant.DEFAULT_DATE_FORMAT').' '.Config::get('Constant.DEFAULT_TIME_FORMAT')); @endphp
                            <div class="row hide">
                                <div class="col-md-6">
                                    <div class="mb-3 form-md-line-input">
                                        @php if(isset($linkscategory_highLight->dtDateTime) && ($linkscategory_highLight->dtDateTime != $linkscategory->dtDateTime)){
                                        $Class_date = " highlitetext";
                                        }else{
                                        $Class_date = "";
                                        } @endphp
                                        <label class="control-label form_title {!! $Class_date !!}">{{ trans('links-category::template.common.startDateAndTime') }}<span aria-required="true" class="required"> * </span></label>
                                        <div class="input-group date form_meridian_datetime @if($errors->first('start_date_time')) has-error @endif" data-date="{{ Carbon\Carbon::today()->format('Y-m-d') }}T15:25:00Z">
                                            <span class="input-group-text date_default">
                                                <i class="ri-calendar-fill"></i>
                                            </span>
                                            {!! Form::text('start_date_time', date(Config::get('Constant.DEFAULT_DATE_FORMAT').' '.Config::get('Constant.DEFAULT_TIME_FORMAT'),strtotime(isset($linkscategory->dtDateTime)?$linkscategory->dtDateTime:$defaultDt)), array('class' => 'form-control', 'data-provider' => 'flatpickr', 'data-date-format' => Config::get('Constant.DEFAULT_DATE_FORMAT'), 'data-enable-time' => '','maxlength'=>160,'size'=>'16','id'=>'start_date_time','autocomplete'=>'off','onkeypress'=>"javascript: return KeycheckOnlyDate(event);",'onpaste'=>'return false')) !!}
                                        </div>
                                        <span class="help-block">{{ $errors->first('start_date_time') }}</span>
                                    </div>
                                </div>
                                @php $defaultDt = (null !== old('end_date_time'))?old('end_date_time'):null; @endphp
                                @if ((isset($linkscategory->dtEndDateTime)==null))
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
                                        @php if(isset($linkscategory_highLight->dtEndDateTime) && ($linkscategory_highLight->dtEndDateTime != $linkscategory->dtEndDateTime)){
                                        $Class_end_date = " highlitetext";
                                        }else{
                                        $Class_end_date = "";
                                        } @endphp
                                        <div class=" form_meridian_datetime expirydate @if($errors->first('end_date_time')) has-error @endif" data-date="{{ Carbon\Carbon::today()->format('Y-m-d') }}T15:25:00Z" @if ($expChecked_yes==1) style="display:none;" @endif>
                                            <label class="form-label {!! $Class_end_date !!}">{{ trans('links-category::template.common.endDateAndTime') }}<span aria-required="true" class="required"> * </span></label>
                                            
                                            <div class="input-group date">
                                                <span class="input-group-text"><i class="ri-calendar-fill"></i></span>
                                                {!! Form::text('end_date_time', isset($linkscategory->dtEndDateTime)?date(Config::get('Constant.DEFAULT_DATE_FORMAT').' '.Config::get('Constant.DEFAULT_TIME_FORMAT'),strtotime($linkscategory->dtEndDateTime)):$defaultDt, array('class' => 'form-control', 'data-provider' => 'flatpickr', 'data-date-format' => Config::get('Constant.DEFAULT_DATE_FORMAT'), 'data-enable-time' => '','maxlength'=>160,'size'=>'16','id'=>'end_date_time','data-exp'=> $expChecked_yes,'data-newvalue','autocomplete'=>'off','onkeypress'=>"javascript: return KeycheckOnlyDate(event);",'onpaste'=>'return false')) !!}
                                                
                                            </div>
                                        </div>
                                        <span class="help-block">{{ $errors->first('end_date_time') }}</span>
                                        <label class="expdatelabel {{ $expclass }}">
                                            <a id="noexpiry" name="noexpiry" href="javascript:void(0);">
                                                <b class="expiry_lbl {!! $Class_end_date !!}"></b>
                                            </a>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <h3 class="form-section">{{ trans('links-category::template.common.displayinformation') }}</h3>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3 @if($errors->first('display_order')) has-error @endif form-md-line-input">
                                        @php
                                        $display_order_attributes = array('class' => 'form-control','maxlength'=>5,'placeholder'=>trans('links-category::template.common.displayorder'),'autocomplete'=>'off');
                                        @endphp
                                        @php if(isset($linkscategory_highLight->intDisplayOrder) && ($linkscategory_highLight->intDisplayOrder != $linkscategory->intDisplayOrder)){
                                        $Class_displayorder = " highlitetext";
                                        }else{
                                        $Class_displayorder = "";
                                        } @endphp
                                        <label class="form-label {!! $Class_displayorder !!}" for="site_name">{{ trans('links-category::template.common.displayorder') }} <span aria-required="true" class="required"> * </span></label>
                                        {!! Form::text('display_order', isset($linkscategory->intDisplayOrder)?$linkscategory->intDisplayOrder:1, $display_order_attributes) !!}
                                        <span style="color: red;">
                                            {{ $errors->first('display_order') }}
                                        </span>
                                    </div>
                                </div>
                                @if($hasRecords==0)
                                <div class="col-md-6">
                                    @if(isset($linkscategory_highLight->chrPublish) && ($linkscategory_highLight->chrPublish != $linkscategory->chrPublish))
                                        @php $Class_chrPublish = " highlitetext"; @endphp
                                    @else
                                        @php $Class_chrPublish = ""; @endphp
                                    @endif

                                    @if(isset($linkscategory) && $linkscategory->chrAddStar == 'Y')
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="control-label form-label"> Publish/ Unpublish</label>
                                                <input type="hidden" id="chrMenuDisplay" name="chrMenuDisplay" value="{{ isset($linkscategory->chrPublish) ? $linkscategory->chrPublish : '' }}">
                                                <p><b>NOTE:</b> This record is in Approval Request , so it can&#39;t be published/unpublished.</p>
                                            </div>
                                        </div>
                                    @elseif(isset($linkscategory) && $linkscategory->chrDraft == 'D' && $linkscategory->chrAddStar != 'Y')
                                        @include('powerpanel.partials.displayInfo',['Class_chrPublish'=>$Class_chrPublish,'display' => (isset($linkscategory->chrDraft)?$linkscategory->chrDraft:'D')])
                                    @else
                                        @include('powerpanel.partials.displayInfo',['Class_chrPublish'=>$Class_chrPublish,'display' => (isset($linkscategory->chrPublish)?$linkscategory->chrPublish:'Y')])
                                    @endif
                                </div>
                                @else
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="control-label form-label"> Publish/ Unpublish</label>
                                        @if($hasRecords > 0)
                                        <input type="hidden" id="chrMenuDisplay" name="chrMenuDisplay" value="{{ $linkscategory->chrPublish }}">
                                        <p><b>NOTE:</b> This category is selected in {{ trans("links-category::template.sidebar.links") }}, so it can&#39;t be published/unpublished.</p>
                                        @endif
                                    </div>
                                </div>
                                @endif
                            </div>
                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-12">
                                        @if(isset($linkscategory->fkMainRecord) && $linkscategory->fkMainRecord != 0)
                                        <button type="submit" name="saveandexit" class="btn btn-primary" value="saveandexit">{!! trans('links-category::template.common.approve') !!}</button>
                                        @else
                                        @if($userIsAdmin)
                                        <button type="submit" name="saveandedit" class="btn btn-primary" value="saveandedit">{!! trans('links-category::template.common.saveandedit') !!}</button>
                                        <button type="submit" name="saveandexit" class="btn btn-primary" value="saveandexit">{!! trans('links-category::template.common.saveandexit') !!}</button>
                                        @else
                                        @if((isset($chrNeedAddPermission) && $chrNeedAddPermission == 'N') && (isset($charNeedApproval) && $charNeedApproval == 'N'))
                                        <button type="submit" name="saveandexit" class="btn btn-primary" value="saveandexit">{!! trans('links-category::template.common.saveandexit') !!}</button>
                                        @else
                                        <button type="submit" name="saveandexit" class="btn btn-primary" value="approvesaveandexit">{!! trans('links-category::template.common.approvesaveandexit') !!}</button>
                                        @endif
                                        @endif
                                        @endif
                                        <a class="btn btn-danger" href="{{ url('powerpanel/links-category') }}">{{ trans('links-category::template.common.cancel') }}</a>
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
@endsection
@section('scripts')
<script type="text/javascript">
    window.site_url = '{!! url("/") !!}';
    var user_action = "{{ isset($linkscategory)?'edit':'add' }}";
    var moduleAlias = 'linkscategory';
</script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/linkcategory/linkscategory_validations.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/custom.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/seo-generator/seo-info-generator.js' }}" type="text/javascript"></script>
@endsection