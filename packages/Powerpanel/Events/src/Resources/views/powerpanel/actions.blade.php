@section('css')
<link href="{{ $CDN_PATH.'resources/global/plugins/bootstrap-taginput/bootstrap-tagsinput.css' }}" rel="stylesheet" type="text/css" />
{{-- <link href="{{ $CDN_PATH.'resources/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css' }}" rel="stylesheet" type="text/css"/>
<link href="{{ $CDN_PATH.'resources/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css' }}" rel="stylesheet" type="text/css" />
<link href="{{ $CDN_PATH.'resources/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css' }}" type="text/css" rel="stylesheet" /> --}}
@endsection
@extends('powerpanel.layouts.app')
@section('title')
{{Config::get('Constant.SITE_NAME')}} - PowerPanel
@endsection
@section('content')
@php $settings = json_decode(Config::get("Constant.MODULE.SETTINGS")); @endphp
{{-- @include('powerpanel.partials.breadcrumbs') --}}

<div class="col-md-12 settings">
    <div class="row">
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
            {!! Form::open(['method' => 'post','id'=>'frmEvents']) !!}
            <div class="card">
                <div class="card-body p-30 pb-0">
                    {!! Form::hidden('fkMainRecord', isset($events->fkMainRecord)?$events->fkMainRecord:old('fkMainRecord')) !!}

                    @if(isset($events))
                    <div class="row pagetitle-heading mb-3">
                        <div class="col-sm-11 col-11">
                            <h4 class="page-title fw-semibold m-0">{{ $breadcrumb['inner_title']}}</h4>
                        </div>
                        <div class="col-sm-1 col-1 lock-link">
                            @if (File::exists(base_path() . '/resources/views/powerpanel/partials/lockedpage.blade.php') != null)
                            @include('powerpanel.partials.lockedpage',['pagedata'=>$events])
                            @endif
                        </div>
                    </div>
                    @endif

                    <div class="row">
                        {{-- Sector type --}}
                        <div class="col-lg-6 col-sm-12">
                            <div class="@if($errors->first('sector')) has-error @endif form-md-line-input cm-floating">
                                @if(isset($events_highLight->varSector) && ($events_highLight->varSector != $events->varSector))
                                    @php $Class_varSector = " highlitetext"; @endphp
                                @else
                                    @php $Class_varSector = ""; @endphp
                                @endif
                                @include('shiledcmstheme::powerpanel.partials.sector-dropdown', ['selected_sector' => isset($events->varSector)?$events->varSector:'','Class_varSector' => $Class_varSector])
                                <span class="help-block">{{ $errors->first('sector') }}</span>
                            </div>
                        </div>
                        {{-- Select Category --}}
                        <div class="col-lg-6 col-sm-12">
                            <div class="@if($errors->first('category')) has-error @endif form-md-line-input cm-floating">
                                @php
                                if(isset($events_highLight->intFKCategory) && ($events_highLight->intFKCategory != $events->intFKCategory)){
                                $Class_title = " highlitetext";
                                }else{
                                $Class_title = "";
                                }
                                $currentCatAlias = '';
                                @endphp
                                <label class="form-label {{ $Class_title }}" for="site_name">Select Category <span aria-required="true" class="required"> * </span></label>
                                <select class="form-control" name="category_id" id="category_id" data-choices>
                                    <option value="">Select Category</option>
                                </select>
                                <span class="help-block">{{ $errors->first('category') }}</span>
                            </div>
                        </div>
                        {{-- Title --}}
                        <div class="col-lg-6 col-sm-12">
                            <div class=" @if($errors->first('title')) has-error @endif form-md-line-input cm-floating">
                                @php if(isset($events_highLight->varTitle) && ($events_highLight->varTitle != $events->varTitle)){
                                $Class_title = " highlitetext";
                                }else{
                                $Class_title = "";
                                } @endphp
                                <label class="form-label {!! $Class_title !!}" for="site_name">{{ trans('events::template.common.title') }} <span aria-required="true" class="required"> * </span></label>
                                {!! Form::text('title', isset($events->varTitle) ? $events->varTitle:old('title'), array('maxlength'=>'150','id'=>'title','class' => 'form-control hasAlias seoField maxlength-handler titlespellingcheck','autocomplete'=>'off')) !!}
                                <span class="help-block">{{ $errors->first('title') }}</span>

                                <!-- code for alias -->
                                <div class="link-url mt-2">
                                    {!! Form::hidden(null, null, array('class' => 'hasAlias','data-url' => 'powerpanel/events')) !!}
                                    {!! Form::hidden('alias', isset($events->alias->varAlias) ? $events->alias->varAlias : old('alias'), array('class' => 'aliasField')) !!}
                                    {!! Form::hidden('oldAlias', isset($events->alias->varAlias)?$events->alias->varAlias : old('alias')) !!}
                                    {!! Form::hidden('previewId') !!}
                                    <div class=" alias-group {{!isset($events->alias)?'hide':''}}">
                                        <label class="form-label" for="Url">{{ trans('events::template.common.url') }} :</label>
                                        @if(isset($events->alias->varAlias) && !$userIsAdmin)
                                        <a  class="alias">{!! url("/") !!}</a>
                                        @else
                                        @if(auth()->user()->can('events-create'))
                                        <a href="javascript:void;" class="alias">{!! url("/") !!}</a>
                                        <a href="javascript:void(0);" class="editAlias" title="Edit">
                                            <i class="ri-pencil-line"></i>
                                        </a>
                                        <a class="without_bg_icon openLink" title="Open Link" onClick="generatePreview('{{ url('/previewpage?url='.(App\Helpers\MyLibrary::getFrontUri('events')['uri'])) }}');">
                                            <i class="ri-external-link-line" aria-hidden="true"></i>
                                        </a>
                                        @endif
                                        @endif
                                    </div>
                                    <span class="help-block">{{ $errors->first('alias') }}</span>
                                </div>
                                <!-- code for alias -->
                            </div>
                        </div>
                        {{-- Short Description --}}
                        <div class="col-lg-6 col-sm-12">
                            <div class="@if($errors->first('short_description')) has-error @endif form-md-line-input cm-floating">
                                @php if(isset($events_highLight->varShortDescription) && ($events_highLight->varShortDescription != $events->varShortDescription)){
                                $Class_ShortDescription = " highlitetext";
                                }else{
                                $Class_ShortDescription = "";
                                } @endphp
                                <label class="form-label {!! $Class_ShortDescription !!}">Short Description<span aria-required="true" class="required"> * </span></label>
                                {!! Form::textarea('short_description', isset($events->varShortDescription)?$events->varShortDescription:old('short_description'), array('maxlength' => 140,'class' => 'form-control seoField maxlength-handler shortdescspellingcheck','id'=>'varShortDescription','rows'=>'3')) !!}
                                <span class="help-block">{{ $errors->first('short_description') }}</span>
                            </div>
                        </div>
                        {{-- Select Documents --}}
                        <div class="col-lg-6 col-sm-12">
                            <div class="image_thumb multi_upload_images mb-0">
                                @php if(isset($events_highLight->fkIntDocId) && ($events_highLight->fkIntDocId != $events->fkIntDocId)){
                                $Class_file = " highlitetext";
                                }else{
                                $Class_file = "";
                                } @endphp
                                <div class="cm-floating">
                                    <label class="form-label {!! $Class_file !!}">Select Documents
                                        <span class="img-note" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Recommended documents *.txt, *.pdf, *.doc, *.docx, *.ppt, *.xls, *.xlsx, *.xlsm formats are supported. Document should be maximum size of 45 MB.">
                                            <i class="ri-information-line text-primary fs-16"></i>
                                        </span>
                                    </label>
                                    <div class="clearfix"></div>
                                    <div class="fileinput fileinput-new page-media" data-provides="fileinput">
                                        <div class="fileinput-preview thumbnail" data-trigger="fileinput">
                                            <div class="dz-message needsclick w-100 text-center">
                                                <div class="dropzone_icon"><i class="display-5 text-muted ri-upload-cloud-2-fill"></i></div>
                                                <h5 class="sbold dropzone-title">Drop files here or click to upload</h5>
                                            </div>
                                        </div>
                                        <div class="input-group">
                                            <a class="document_manager multiple-selection" data-multiple="true" onclick="MediaManager.openDocumentManager('events');"><span class="fileinput-new"></span></a>
                                            <input class="form-control" type="hidden" id="events" name="doc_id" value="{{ isset($events->fkIntDocId)?$events->fkIntDocId:old('doc_id') }}" />
                                            @php
                                            if (method_exists($MyLibrary, 'GetDocumentFolderID')) {
                                            if(isset($events->fkIntDocId)){
                                            $folderid = App\Helpers\MyLibrary::GetDocumentFolderID($events->fkIntDocId);
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
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
                        {{-- Select Image --}}
                        <div class="col-lg-6 col-sm-12">
                            <div class="image_thumb multi_upload_images mb-0">
                                @if(isset($events_highLight->fkIntImgId) && ($events_highLight->fkIntImgId != $events->fkIntImgId))
                                @php $Class_fkIntImgId = " highlitetext"; @endphp
                                @else
                                @php $Class_fkIntImgId = ""; @endphp
                                @endif

                                <div class="cm-floating">
                                    <label class="form-label {{ $Class_fkIntImgId }}" for="front_logo">
                                        {{ trans('events::template.common.selectimage') }}
                                        @php $height = isset($settings->height)?$settings->height:292; $width = isset($settings->width)?$settings->width:372; @endphp
                                        <span class="img-note" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ trans('events::template.common.imageSize',['height'=>$height, 'width'=>$width]) }}">
                                            <i class="ri-information-line text-primary fs-16"></i>
                                        </span>
                                    </label>
                                    <div class="fileinput fileinput-new page-media" data-provides="fileinput">
                                        <div class="fileinput-preview thumbnail event_image_img" data-trigger="fileinput">
                                            @if(old('image_url'))
                                            <img src="{{ old('image_url') }}" />
                                            @elseif(isset($events->fkIntImgId))
                                            <img src="{!! App\Helpers\resize_image::resize($events->fkIntImgId,120,120) !!}" />
                                            @else
                                            <div class="dz-message needsclick w-100 text-center">
                                                <div class="dropzone_icon"><i class="display-5 text-muted ri-upload-cloud-2-fill"></i></div>
                                                <h5 class="sbold dropzone-title">Drop files here or click to upload</h5>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="input-group">
                                            <a class="media_manager" data-multiple="false" onclick="MediaManager.open('event_image');"><span class="fileinput-new"></span></a>
                                            <input class="form-control" type="hidden" id="event_image" name="img_id" value="{{ isset($events->fkIntImgId)?$events->fkIntImgId:old('img_id') }}" />
                                            <input class="form-control" type="hidden" id="image_url" name="image_url" value="{{ old('image_url') }}" />
                                            @php
                                            if (method_exists($MyLibrary, 'GetFolderID')) {
                                                if(isset($events->fkIntImgId)) {
                                                $folderid = App\Helpers\MyLibrary::GetFolderID($events->fkIntImgId);
                                                @endphp
                                                @if(isset($folderid->fk_folder) && $folderid->fk_folder != '0')
                                                <input class="form-control" type="hidden" id="folder_id" name="folder_id" value="{{ $folderid->fk_folder }}" />
                                                @endif
                                                @php
                                                }
                                            }
                                            @endphp
                                        </div>
                                        <div class="overflow_layer">
                                            <a onclick="MediaManager.open('event_image');" class="media_manager remove_img"><i class="ri-pencil-line"></i></a>
                                            <a href="javascript:;" class="fileinput-exists remove_img removeimg" data-dismiss="fileinput"><i class="ri-delete-bin-line"></i></a>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <span class="help-block">{{ $errors->first('img_id') }}</span>
                                </div>
                            </div>
                        </div>
                        {{-- Documents Image List --}}
                        @if(!empty($events->fkIntDocId) && isset($events->fkIntDocId))
                            <div class="col-lg-6 col-sm-12">
                                @php
                                $docsAray = explode(',', $events->fkIntDocId);
                                $docObj   = App\Document::getDocDataByIds($docsAray);
                                @endphp
                                <div class="cm-floating" id="events_documents">
                                    <div class="multi_image_list" id="multi_document_list">
                                        <ul>
                                            @if(count($docObj) > 0)
                                            @foreach($docObj as $value)
                                            <li id="doc_{{ $value->id }}">
                                                <span title="{{ $value->txtDocumentName }}.{{ $value->varDocumentExtension }}">
                                                    <i class="ri-file-text-line text-muted display-5 default-icon"></i>
                                                    {{-- <img  src="{{ $CDN_PATH.'assets/images/document_icon.png' }}" alt="Img" /> --}}
                                                    <a href="javascript:;" onclick="MediaManager.removeDocumentFromGallery('{{ $value->id }}');" class="delect_image" data-dismiss="fileinput"><i class="ri-delete-bin-line"></i></a>
                                                </span>
                                            </li>
                                            @endforeach
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="col-md-6 col-sm-12"><div class="cm-floating d-none" id="events_documents"></div></div>
                        @endif
                    </div>
                </div>
            </div>
            {{-- Schedule Date/Time --}}
            <div class="card">
                <div class="card-body p-30 pb-0">
                    @php 
                        if(isset($events_highLight->dtDateTime	) && ($events_highLight->dtDateTime	 != $events->dtDateTime	)){
                            $Class_dtDateTime	 = " highlitetext";
                            $Class_txtCOLOR = "#ece743";
                        }else{
                            $Class_dtDateTime	 = "";
                            $Class_txtCOLOR = "";
                        }
                        
                        if (isset($events->dtEndDateTime)==null) {
                            $expChecked_yes = 1;
                            $expclass='';
                        } else {
                            $expChecked_yes = 0;
                            $expclass='no_expiry';
                        }
                    @endphp
                    <h4 class="form-section" style="background-color:{{ $Class_txtCOLOR }}">Event Schedule</h4>
                    <div class="event-section">
                        @if(isset($events->start_date_time) && count($events->start_date_time) > 0)
                            <div class="row ">
                                @foreach ($events->start_date_time as $dateKey => $start_date_time)
                                    <div class="schedules" data-parentIndex='{!! $dateKey !!}' id="dateTimeSlot{!! $dateKey !!}">
                                        <div class="d-lg-flex">
                                            <div class="flex-grow-1">
                                                <div class="cm-floating form-md-line-input">
                                                    @php
                                                    if(isset($start_date_time->startDate) && !empty($start_date_time->startDate)){
                                                        $startDate = date(Config::get('Constant.DEFAULT_DATE_FORMAT'),strtotime($start_date_time->startDate));
                                                    }else{
                                                        $startDate = null;
                                                    }
                                                    @endphp
                                                    <label class="control-label form-label">{{ trans('events::template.common.startDateAndTime') }}<span aria-required="true" class="required"> * </span></label>
                                                    <div class="input-group date form_meridian_datetime @if($errors->first('start_date_time')) has-error @endif" data-date="{{ Carbon\Carbon::today()->format('Y-m-d') }}T15:25:00Z">
                                                        {{-- <span class="input-group-text"><i class="ri-calendar-fill"></i></span> --}}
                                                        {!! Form::text('start_date_time['.$dateKey.'][startDate]', $startDate, array('class' => 'form-control', 'data-provider' => 'flatpickr', 'data-date-format' => Config::get('Constant.DEFAULT_DATE_FORMAT'), 'data-enable-time' => false, 'data-dateIndex'=> $dateKey,'readonly'=>'readonly', 'id'=>'start_date_time'.$dateKey,'autocomplete'=>'off')) !!}
                                                    </div>
                                                    <span class="help-block">{{ $errors->first('start_date_time') }}</span>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-lg-4">
                                                <div class="form-md-line-input">
                                                    @php
                                                        if(isset($start_date_time->endDate) && !empty($start_date_time->endDate)){
                                                            $endDate = date(Config::get('Constant.DEFAULT_DATE_FORMAT'),strtotime($start_date_time->endDate));
                                                        }else{
                                                            $endDate = null;
                                                        } 
                                                    @endphp
                                                    <div class="cm-floating form_meridian_datetime expirydate @if($errors->first('end_date_time')) has-error @endif" data-date="{{ Carbon\Carbon::today()->format('Y-m-d') }}T15:25:00Z">
                                                        <label class="control-label form-label">{{ trans('events::template.common.endDateAndTime') }} <span aria-required="true" class="required"> * </span></label>
                                                        
                                                        <div class="input-group date">
                                                            {{-- <span class="input-group-text"><i class="ri-calendar-fill"></i></span> --}}
                                                            {!! Form::text('start_date_time['.$dateKey.'][endDate]', $endDate, array('class' => 'form-control', 'data-provider' => 'flatpickr', 'data-date-format' => Config::get('Constant.DEFAULT_DATE_FORMAT'), 'data-enable-time' => false, 'readonly'=> 'readonly', 'data-dateIndex'=> $dateKey, 'id'=>'end_date_time'.$dateKey,'autocomplete'=>'off', 'data-newvalue')) !!}
                                                        </div>
                                                    </div>
                                                    <span class="help-block">{{ $errors->first('end_date_time') }}</span>
                                                </div>
                                            </div>     
                                            <div class="flex-grow-0">                                   
                                                <div class="mb-30 addDateButton">
                                                    @if($dateKey !== 0)
                                                        {{-- <input type="button" name="Remove" value="Remove" class="btn btn-danger ms-lg-4" onclick="removeDateTimeSlot(this,{!! $dateKey !!})" id="dateTimeSlotRemove{!! $dateKey !!}'"> --}}
                                                        <a href="javascript:;" class="btn btn-primary bg-gradient waves-effect waves-light ms-lg-4 event-iconbtn" onclick="removeDateTimeSlot(this,{!! $dateKey !!})" id="dateTimeSlotRemove{!! $dateKey !!}'"><i class="ri-add-fill fs-20"></i></a>
                                                    @endif
                                                    @if(count($events->start_date_time)-1 === $dateKey)
                                                        {{-- <input type=button name="Add" value="Add" class="btn btn-primary ms-lg-4" onclick="addDateTimeSlot(this,{!! $dateKey !!})" id="dateTimeSlotAdd{!! $dateKey !!}"> --}}
                                                        <a href="javascript:;" class="btn btn-primary bg-gradient waves-effect waves-light ms-lg-4 event-iconbtn" onclick="addDateTimeSlot(this,{!! $dateKey !!})" id="dateTimeSlotAdd{!! $dateKey !!}"><i class="ri-add-fill fs-20"></i></a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="">
                                            <h6 class="form-section mb-3">Time & Attendees:</h6>
                                                @if(isset($start_date_time->timeSlotFrom) && count($start_date_time->timeSlotFrom) > 0)
                                                    @foreach ($start_date_time->timeSlotFrom as $fromTimeKey => $timeSlotFrom)
                                                        <div class="d-lg-flex time-slots-{{ $dateKey }}" id="timeSlot{!! $fromTimeKey !!}" data-index="{!! $fromTimeKey !!}" data-parentName="dateTimeSlot{!! $dateKey !!}">
                                                            @php
                                                                if(!empty($start_date_time->timeSlotTo[$fromTimeKey])){
                                                                    $toTimeSlot = $start_date_time->timeSlotTo[$fromTimeKey];
                                                                }else{
                                                                    $toTimeSlot = '';
                                                                }
                                                            @endphp

                                                            <div class="flex-grow-1 me-lg-4">
                                                                <div class="cm-floating">
                                                                    <label class="form-label">From <span aria-required="true" class="required"> * </span></label>
                                                                    <div class="form-md-line-input bootstrap-timepicker timepicker">
                                                                        {!! Form::text('start_date_time['.$dateKey.'][timeSlotFrom][]', $timeSlotFrom, array('id'=>'timeSlotFrom'.$fromTimeKey, 'class' => 'form-control flatpickr-input', 'autocomplete'=>'off','readonly' => 'readonly')) !!}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="flex-grow-1 me-lg-4">
                                                                <div class="cm-floating">
                                                                    <label class="form-label">To <span aria-required="true" class="required"> * </span></label>
                                                                    <div class="form-md-line-input bootstrap-timepicker timepicker">
                                                                        {!! Form::text('start_date_time['.$dateKey.'][timeSlotTo][]', $start_date_time->timeSlotTo[$fromTimeKey], array('id'=>'timeSlotTo'.$fromTimeKey,'class' => 'form-control flatpickr-input', 'autocomplete'=>'off','readonly' => 'readonly')) !!}
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="flex-grow-1">
                                                                <div class="cm-floating">
                                                                    <label class="form-label">No of attendees <span aria-required="true" class="required"> * </span></label>
                                                                    <div class="form-md-line-input">
                                                                        {!! Form::number('start_date_time['.$dateKey.'][attendees][]', $start_date_time->attendees[$fromTimeKey], array('id'=>'attendees'.$fromTimeKey, 'maxlength' => '3' ,'class' => 'form-control','autocomplete'=>'off')) !!}
                                                                        <span class="help-block"></span>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="flex-grow-0">
                                                                <div class="mb-30">
                                                                    @if($fromTimeKey !== 0)
                                                                        {{-- <input type="button" name="Remove" value="Remove" class="btn btn-danger ms-lg-4" onclick="removeTimeSlot(this,{!! $fromTimeKey !!},{!! $dateKey !!})" id="timeSlotRemove{!! $fromTimeKey !!}"> --}}
                                                                        <a href="javascript:;" class="btn btn-primary bg-gradient waves-effect waves-light ms-lg-4 event-iconbtn" onclick="removeTimeSlot(this,{!! $fromTimeKey !!},{!! $dateKey !!})" id="timeSlotRemove{!! $fromTimeKey !!}"><i class="ri-add-fill fs-20"></i></a>
                                                                    @endif
                                                                    @if(count($start_date_time->timeSlotFrom)-1 === $fromTimeKey)
                                                                        {{-- <input type=button name="Add" value="Add" class="btn btn-primary ms-lg-4" id="timeSlotAdd{!! $fromTimeKey !!}" onclick="addTimeSlot(this,{!! $fromTimeKey !!},{!! $dateKey !!})"> --}}
                                                                        <a href="javascript:;" class="btn btn-primary bg-gradient waves-effect waves-light ms-lg-4 event-iconbtn" id="timeSlotAdd{!! $fromTimeKey !!}" onclick="addTimeSlot(this,{!! $fromTimeKey !!},{!! $dateKey !!})"><i class="ri-add-fill fs-20"></i></a>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <div class="d-lg-flex" id="timeSlot0" data-index="0" data-parentName="dateTimeSlot{!! $dateKey !!}">
                                                        <div class="flex-grow-1 me-lg-4">
                                                            <div class="cm-floating">
                                                                <label class="form-label">From</label>
                                                                <div class="bootstrap-timepicker timepicker">
                                                                    {!! Form::text('start_date_time['.$dateKey.'][timeSlotFrom][]', null, array('id'=>'timeSlotFrom0','class' => 'form-control flatpickr-input','autocomplete'=>'off','readonly' => 'readonly')) !!}
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1 me-lg-4">
                                                            <div class="cm-floating">
                                                                <label class="form-label">To</label>
                                                                <div class="bootstrap-timepicker timepicker">
                                                                    {!! Form::text('start_date_time['.$dateKey.'][timeSlotTo][]', null, array('id'=>'timeSlotTo0','class' => 'form-control flatpickr-input','autocomplete'=>'off','readonly' => 'readonly')) !!}
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <div class="cm-floating">
                                                                <label class="form-label">No of attendees</label>
                                                                <div class="form-md-line-input">
                                                                    {!! Form::number('start_date_time['.$dateKey.'][attendees][]', '', array('id'=>'attendees0','readonly'=>'readonly','maxlength' => '3', 'class' => 'form-control','autocomplete'=>'off')) !!}
                                                                    <span class="help-block"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-0">
                                                            <div class="mb-30">
                                                                {{-- <input type=button name="Add" value="Add" class="btn btn-primary ms-lg-4" id="timeSlotAdd0" onclick="addTimeSlot(this,0,{{!! $dateKey !!}})"> --}}
                                                                <a href="javascript:;" class="btn btn-primary bg-gradient waves-effect waves-light ms-lg-4 event-iconbtn" id="timeSlotAdd0" onclick="addTimeSlot(this,0,{{!! $dateKey !!}})"><i class="ri-add-fill fs-20"></i></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            @php $defaultDt = null; @endphp
                            <div class="schedules" data-parentIndex='0' id="dateTimeSlot0">
                                <div class="d-lg-flex">
                                    <div class="flex-grow-1">
                                        <div class="cm-floating form-md-line-input">
                                            @php if(isset($events_highLight->dtDateTime) && ($events_highLight->dtDateTime != $events->dtDateTime)){
                                            $Class_date = " highlitetext";
                                            }else{
                                            $Class_date = "";
                                            } @endphp

                                            <label class="control-label form-label text-capitalize {!! $Class_date !!}">{{ trans('events::template.common.startDateAndTime') }}<span aria-required="true" class="required"> * </span></label>
                                            <div class="input-group date form_meridian_datetime @if($errors->first('start_date')) has-error @endif">
                                                {!! Form::text('start_date_time[0][startDate]', null, array('class' => 'form-control', 'data-provider' => 'flatpickr', 'data-date-format' => Config::get('Constant.DEFAULT_DATE_FORMAT'), 'data-minDate' => 'today','size'=>'16','readonly'=>'readonly', 'data-dateIndex'=> 0, 'id'=>'start_date_time0','autocomplete'=>'off')) !!}
                                            </div>
                                            <span class="help-block">
                                                {{ $errors->first('start_date_time') }}
                                            </span>
                                        </div>
                                    </div>
                                    @php $defaultDt = (null !== old('end_date_time'))?old('end_date_time'):null; @endphp
                                    <div class="flex-grow-1 ms-lg-4">
                                        <div class="form-md-line-input">
                                            @php if(isset($events_highLight->dtEndDateTime) && ($events_highLight->dtEndDateTime != $events->dtEndDateTime)){
                                                $Class_end_date = " highlitetext";
                                                }else{
                                                $Class_end_date = "";
                                            } @endphp
                                            <div class="cm-floating date  form_meridian_datetime @if($errors->first('end_date_time')) has-error @endif" data-date="{{ Carbon\Carbon::today()->format('Y-m-d') }}T15:25:00Z">
                                                <label class="control-label form-label {!! $Class_end_date !!}" >{{ trans('events::template.common.endDateAndTime') }} <span aria-required="true" class="required"> * </span></label>
                                                <div class="pos_cal">
                                                    {!! Form::text('start_date_time[0][endDate]', null, array('class' => 'form-control', 'data-provider' => 'flatpickr', 'data-date-format' => Config::get('Constant.DEFAULT_DATE_FORMAT'), 'data-minDate' => 'today','size'=>'16', 'readonly'=>'readonly', 'data-dateIndex'=> 0, 'id'=>'end_date_time0','data-exp'=> $expChecked_yes,'data-newvalue','autocomplete'=>'off')) !!}
                                                </div>
                                            </div>
                                            <span class="help-block">{{ $errors->first('end_date_time') }}</span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-0">
                                        <div class="addDateButton mb-30">
                                            {{-- <input type=button name="Add" value="Add" class="btn btn-primary ms-lg-4" onclick="addDateTimeSlot(this,0)" id="dateTimeSlotAdd0"> --}}
                                            <a href="javascript:;" class="btn btn-primary bg-gradient waves-effect waves-light ms-lg-4 event-iconbtn" onclick="addDateTimeSlot(this,0)" id="dateTimeSlotAdd0"><i class="ri-add-fill fs-20"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="timeattendees-info">
                                    <h6 class="form-section mb-3">Time & Attendees:</h6>
                                    <div class="d-lg-flex time-slots-0" id="timeSlot0" data-index="0" data-parentName="dateTimeSlot0">
                                        <div class="flex-grow-1 me-lg-4">
                                            <div class="cm-floating form-md-line-input">
                                                <label class="form-label" >From <span aria-required="true" class="required"> * </span></label>
                                                {!! Form::text('start_date_time[0][timeSlotFrom][]', null, array('id'=>'timeSlotFrom0', 'data-provider' => 'timepickr', 'data-time-basic' => 'true','class' => 'form-control flatpickr-input','autocomplete'=>'off', 'readonly' => 'readonly')) !!}
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 me-lg-4">
                                            <div class="cm-floating form-md-line-input">
                                                <label class="form-label" >To <span aria-required="true" class="required"> * </span></label>
                                                {!! Form::text('start_date_time[0][timeSlotTo][]', '', array('id'=>'timeSlotTo0', 'data-provider' => 'timepickr', 'data-time-basic' => 'true','class' => 'form-control flatpickr-input','autocomplete'=>'off','readonly' => 'readonly')) !!}
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="cm-floating form-md-line-input">
                                                <label class="form-label">No of Attendees <span aria-required="true" class="required"> * </span></label>
                                                {!! Form::number('start_date_time[0][attendees][]', null, array('maxlength' => 3 ,'id'=>'attendees0','class' => 'form-control maxlength-handler','autocomplete'=>'off')) !!}
                                                <span class="help-block"></span>
                                            </div>
                                        </div>
                                        <div class="flex-grow-0">
                                            <div class="mb-30 addTimeButton">
                                                {{-- <input type=button name="Add" value="Add" class="btn btn-primary ms-lg-4" id="timeSlotAdd0" onclick="addTimeSlot(this,0,0)"> --}}
                                                <a href="javascript:;" class="btn btn-primary bg-gradient waves-effect waves-light ms-lg-4 event-iconbtn" id="timeSlotAdd0" onclick="addTimeSlot(this,0,0)"><i class="ri-add-fill fs-20"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="row mt-30">
                        <div class="col-md-12">
                            <div class="@if($errors->first('varAddress')) has-error @endif form-md-line-input cm-floating">
                            @php if(isset($events_highLight->varAddress) && ($events_highLight->varAddress != $events->varAddress)){
                            $Class_varAddress = " highlitetext";
                            }else{
                            $Class_varAddress = "";
                            } @endphp
                            <label class="form-label {!! $Class_varAddress !!}">Event Location</label>
                            {!! Form::textarea('varAddress', isset($events->varAddress)?$events->varAddress:old('varAddress'), array('maxlength' => isset($settings->short_desc_length)?$settings->short_desc_length:500,'class' => 'form-control seoField maxlength-handler shortdescspellingcheck','id'=>'varAddress','rows'=>'3')) !!}
                            <span class="help-block">{{ $errors->first('varAddress') }}</span> </div>
                        </div>
                        <div class="col-lg-6 col-sm-12">
                            <div class="@if($errors->first('varAdminEmail')) has-error @endif form-md-line-input cm-floating">
                            @php if(isset($events_highLight->varAdminEmail) && ($events_highLight->varAdminEmail != $events->varAdminEmail)){
                            $Class_varAdminEmail = " highlitetext";
                            }else{
                            $Class_varAdminEmail = "";
                            } @endphp
                            <label class="form-label {!! $Class_varAdminEmail !!}">Event Admin Email <span aria-required="true" class="required"> * </span></label>
                            {!! Form::text('varAdminEmail', isset($events->varAdminEmail)?$events->varAdminEmail:old('varAdminEmail'), array('maxlength' => 100,'class' => 'form-control maxlength-handler','id'=>'varAdminEmail')) !!}
                            <span class="help-block">{{ $errors->first('varAdminEmail') }}</span> </div>
                        </div>
                        <div class="col-lg-6 col-sm-12">
                            <div class="@if($errors->first('varAdminPhone')) has-error @endif form-md-line-input cm-floating">
                            @php if(isset($events_highLight->varAdminPhone) && ($events_highLight->varAdminPhone != $events->varAdminPhone)){
                            $Class_varAdminPhone = " highlitetext";
                            }else{
                            $Class_varAdminPhone = "";
                            } @endphp
                            <label class="form-label {!! $Class_varAdminPhone !!}">Event Admin Phone</label>
                            {!! Form::text('varAdminPhone', isset($events->varAdminPhone)?$events->varAdminPhone:old('varAdminPhone'), array('maxlength' => 15,'class' => 'form-control maxlength-handler','id'=>'varAdminPhone')) !!}
                            <span class="help-block">{{ $errors->first('varAdminPhone') }}</span> </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Page Content --}}
            <div class="card">
                <div class="card-body p-30">
                    <div class="row">
                        <div class="col-md-12">
                            @php
                                if(isset($events_highLight->txtDescription) && ($events_highLight->txtDescription != $events->txtDescription)){
                                $Class_Description = " highlitetext";
                                }else{
                                $Class_Description = "";
                                } 
                            @endphp
                            <div class="@if($errors->first('description')) has-error @endif form-md-line-input">
                                @if (Config::get('Constant.DEFAULT_VISUAL') == 'Y')
                                    <div id="body-roll">
                                        @php
                                            $sections = [];
                                        @endphp
                                        @if(isset($events))
                                            @php
                                                $sections = json_decode($events->txtDescription);
                                            @endphp
                                        @endif
                                        <!-- Builder include -->
                                        @php
                                            Powerpanel\VisualComposer\Controllers\VisualComposerController::page_section(['sections'=>$sections])
                                        @endphp
                                    </div>
                                @else
                                    <label class="form-label {!! $Class_Description !!}">{{ trans('events::template.common.description') }}</label>
                                    {!! Form::textarea('description', isset($events->txtDescription)?$events->txtDescription:old('description'), array('class' => 'form-control','id'=>'txtDescription')) !!}
                                @endif
                                <span class="help-block">{{ $errors->first('description') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Search Rank --}}
            <div class="card">
                <div class="card-body p-30">
                    <div class="row">
                        <div class="col-md-12">
                            @if(isset($events->intSearchRank))
                                @php $srank = $events->intSearchRank; @endphp
                            @else
                                @php $srank = null !== old('search_rank') ? old('search_rank') : 2 ; @endphp
                            @endif
                            @if(Config::get('Constant.CHRSearchRank') == 'Y')
                                <h4 class="form-section mb-3">Search Ranking</h4>
                                <div class="wrapper search_rank">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="search_rank" @if(isset($srank) && $srank == 1) checked @endif id="yes_radio" value="1">
                                        <label class="form-check-label" for="yes_radio">High</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="search_rank" @if(isset($srank) && $srank == 2) checked @endif id="maybe_radio" value="2">
                                        <label class="form-check-label" for="maybe_radio">Medium</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="search_rank" @if(isset($srank) && $srank == 3) checked @endif id="no_radio" value="3">
                                        <label class="form-check-label" for="no_radio">Low</label>
                                    </div>
                                    <div class="toggle"></div>
                                </div>
                                <div class="alert alert-info alert-border-left mt-3 mb-0 d-block">
                                    <strong>Note: </strong> {{ trans('events::template.common.SearchEntityTools') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            {{-- SEO Info --}}
            <div class="card">
                <div class="card-body p-30">
                    @if(isset($events_highLight->varTags) && ($events_highLight->varTags != $events->varTags))
                        @php $Class_varTags = " highlitetext"; @endphp
                    @else
                        @php $Class_varTags = ""; @endphp
                    @endif
                    <div class="row">
                        <div class="col-md-12">
                            <div class="nopadding">
                                @include('powerpanel.partials.seoInfo',['form'=>'frmEvents','inf'=>isset($metaInfo)?$metaInfo:false,'inf_highLight'=> isset($metaInfo_highLight)?$metaInfo_highLight:false, 'Class_varTags' => $Class_varTags])
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body p-30">
                    <div class="row">
                        {{-- Display Information --}}
                        <div class="col-md-12">
                            <h4 class="form-section mb-3">{{ trans('events::template.common.displayinformation') }}</h4>
                            @if(isset($events_highLight->chrPublish) && ($events_highLight->chrPublish != $events->chrPublish))
                                @php $Class_chrPublish = " highlitetext"; @endphp
                            @else
                                @php $Class_chrPublish = ""; @endphp
                            @endif
                            <div class="form-md-line-input cm-floating">
                                @if(isset($events) && $events->chrAddStar == 'Y')
                                    <label class="control-label form-label"> Publish/ Unpublish</label>
                                    <input type="hidden" id="chrMenuDisplay" name="chrMenuDisplay" value="{{ isset($events->chrPublish) ? $events->chrPublish : '' }}">
                                    <p><b>NOTE:</b> This record is in Approval Request , so it can&#39;t be published/unpublished.</p>
                                @elseif(isset($events) && $events->chrDraft == 'D' && $events->chrAddStar != 'Y')
                                    @include('powerpanel.partials.displayInfo',['Class_chrPublish'=>$Class_chrPublish,'display' => (isset($events->chrDraft)?$events->chrDraft:'D')])
                                @else
                                    @include('powerpanel.partials.displayInfo',['Class_chrPublish'=>$Class_chrPublish,'display' => (isset($events->chrPublish)?$events->chrPublish:'Y')])
                                @endif
                            </div>
                        </div>
                        {{-- Form Actions --}}
                        <div class="form-actions">
                            @if(isset($events->fkMainRecord) && $events->fkMainRecord != 0)
                            <button type="submit" name="saveandexit" formmethod="post" class="btn btn-primary bg-gradient waves-effect waves-light btn-label me-1" value="saveandexit">
                                <div class="flex-shrink-0">
                                    <i class="ri-check-line label-icon align-middle fs-20 me-2"></i>
                                </div>
                                {!! trans('events::template.common.approve') !!}
                            </button>
                            @else
                            @if($userIsAdmin)
                            <button type="submit" name="saveandedit" formmethod="post" class="btn btn-primary bg-gradient waves-effect waves-light btn-label me-1" value="saveandedit">
                                <div class="flex-shrink-0">
                                    <i class="ri-save-line label-icon align-middle fs-20 me-2"></i>
                                </div>
                                {!! trans('events::template.common.saveandedit') !!}
                            </button>
                            <button type="submit" name="saveandexit" formmethod="post" class="btn btn-secondary bg-gradient waves-effect waves-light btn-label me-1" value="saveandexit">
                                <div class="flex-shrink-0">
                                    <i class="ri-save-3-line label-icon align-middle fs-20 me-2"></i>
                                </div>
                                {!! trans('events::template.common.saveandexit') !!}
                            </button>
                            @else
                            @if((isset($chrNeedAddPermission) && $chrNeedAddPermission == 'N') && (isset($charNeedApproval) && $charNeedApproval == 'N'))
                            <button type="submit" name="saveandexit" formmethod="post" class="btn btn-secondary bg-gradient waves-effect waves-light btn-label me-1" value="saveandexit">
                                <div class="flex-shrink-0">
                                    <i class="ri-save-3-line label-icon align-middle fs-20 me-2"></i>
                                </div>
                                {!! trans('events::template.common.saveandexit') !!}
                            </button>
                            @else
                            <button type="submit" name="saveandexit" formmethod="post" class="btn btn-primary bg-gradient waves-effect waves-light btn-label me-1" value="approvesaveandexit">
                                <div class="flex-shrink-0">
                                    <i class="ri-save-line label-icon align-middle fs-20 me-2"></i>
                                </div>
                                {!! trans('events::template.common.approvesaveandexit') !!}
                            </button>
                            @endif
                            @endif
                            @endif
                            <a class="btn btn-danger bg-gradient waves-effect waves-light btn-label me-1" href="{{ url('powerpanel/events') }}">
                                <div class="flex-shrink-0">
                                    <i class="ri-close-line label-icon align-middle fs-20 me-2"></i>
                                </div>
                                {{ trans('events::template.common.cancel') }}
                            </a>
                            @if(isset($events) && !empty($events) && $userIsAdmin)
                            <a class="btn btn-info bg-gradient waves-effect waves-light btn-label me-1" title="Preview" onClick="generatePreview('{{url('/previewpage?url='.(App\Helpers\MyLibrary::getFrontUri('events')['uri']))}}');">
                                <div class="flex-shrink-0">
                                    <i class="ri-eye-line label-icon align-middle fs-20 me-2"></i>
                                </div>
                                Preview
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
<div class="clearfix"></div>
@if (Config::get('Constant.DEFAULT_VISUAL') == 'Y')
    @php Powerpanel\VisualComposer\Controllers\VisualComposerController::get_dialog_maker()@endphp
    @php Powerpanel\VisualComposer\Controllers\VisualComposerController::get_visual_checkEditor()@endphp
@else
    @include('powerpanel.partials.ckeditor',['config'=>'docsConfig'])
@endif
@endsection
@section('scripts')
{{-- <script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js' }}" type="text/javascript"></script> --}}
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-taginput/bootstrap-tagsinput.min.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js' }}" type="text/javascript"></script>
{{-- <script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js' }}" type="text/javascript"></script> --}}
<script type="text/javascript">
    window.site_url = '{!! url("/") !!}';
    var seoFormId = 'frmEvents';
    var user_action = "{{ isset($events)?'edit':'add' }}";
    var moduleAlias = "{{ App\Helpers\MyLibrary::getFrontUri('events')['moduleAlias'] }}";
    var selectedCategory = '{{ isset($events->intFKCategory)?$events->intFKCategory:' ' }}';
    var selectedId = '{{ isset($events->id)?$events->id:' ' }}';
    var preview_add_route = '{!! route("powerpanel.events.addpreview") !!}';
    var previewForm = $('#frmEvents');
    var isDetailPage = true;
    function generate_seocontent1(formname) {
    var Meta_Title = document.getElementById('title').value + "";
        var abcd = $('textarea#txtDescription').val();
        var def = abcd.replace(/<a(\s[^>]*)?>.*?<\/a>/ig, "")
        var abc = def.replace(/^(\s*)|(\s*)$/g, '').replace(/\s+/g, ' ');
        var outString1 = abc.replace(/(<([^>]+)>)/ig, "");
        var Meta_Description = outString1.substr(0, 200);
        var Meta_Keyword = "";
        $('#varMetaTitle').val(Meta_Title);
        $('#varMetaDescription').val(Meta_Description);
        $('#meta_title').html(Meta_Title);
        $('#meta_description').html(Meta_Description);
    }

    function OpenPassword(val) {
        if (val == 'PP') {
            $("#passid").show();
        } else {
            $("#passid").hide();
        }
    }
</script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/events/events_validations.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/custom.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/custom-alias/alias-generator.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/seo-generator/seo-info-generator.js' }}" type="text/javascript"></script>
@if (Config::get('Constant.DEFAULT_VISUAL') == 'Y')
    @php Powerpanel\VisualComposer\Controllers\VisualComposerController::get_builder_css_js()@endphp
@endif
@endsection
