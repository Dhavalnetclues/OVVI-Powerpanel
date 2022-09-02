
@section('css')
<!-- <link href="{{ $CDN_PATH.'resources/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css' }}" rel="stylesheet" type="text/css" />
<link href="{{ $CDN_PATH.'resources/global/plugins/bootstrap-taginput/bootstrap-tagsinput.css' }}" rel="stylesheet" type="text/css" /> -->
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
                    {!! Form::open(['method' => 'post','id'=>'frmLicenceRegister']) !!}
                        {!! Form::hidden('fkMainRecord', isset($licenseregister->fkMainRecord)?$licenseregister->fkMainRecord:old('fkMainRecord')) !!}
                        @if(isset($licenseregister))
                        @if (File::exists(base_path() . '/resources/views/powerpanel/partials/lockedpage.blade.php') != null)
                        @include('powerpanel.partials.lockedpage',['pagedata'=>$licenseregister])
                        @endif
                        @endif

                        <div class="mb-3 @if($errors->first('sector')) has-error @endif form-md-line-input">
                            @if(isset($licenceregister_highLight->varSector) && ($licenceregister_highLight->varSector != $licenseregister->varSector))
                            @php $Class_varSector = " highlitetext"; @endphp
                            @else
                            @php $Class_varSector = ""; @endphp
                            @endif
                            @include('shiledcmstheme::powerpanel.partials.sector-dropdown', ['selected_sector' => isset($licenseregister->varSector)?$licenseregister->varSector:'','Class_varSector' => $Class_varSector])
                            <span class="help-block">
                                {{ $errors->first('sector') }}
                            </span>
                        </div>

                        <div class="mb-3 @if($errors->first('title')) has-error @endif form-md-line-input">
                            @php if(isset($licenceregister_highLight->varTitle) && ($licenceregister_highLight->varTitle != $licenseregister->varTitle)){
                            $Class_title = " highlitetext";
                            }else{
                            $Class_title = "";
                            } @endphp
                            <label class="form-label {!! $Class_title !!}" for="site_name">{{ trans('licence-register::template.common.name') }} <span aria-required="true" class="required"> * </span></label>
                            {!! Form::text('title', isset($licenseregister->varTitle) ? $licenseregister->varTitle:old('title'), array('maxlength'=>'150','id'=>'title','placeholder' => trans('licence-register::template.common.name'),'class' => 'form-control hasAlias seoField maxlength-handler titlespellingcheck','autocomplete'=>'off')) !!}
                            <span class="help-block">
                                {{ $errors->first('title') }}
                            </span>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <!-- code for alias -->
                                {!! Form::hidden(null, null, array('class' => 'hasAlias','data-url' => 'powerpanel/licence-register')) !!}
                                {!! Form::hidden('alias', isset($licenseregister->alias->varAlias) ? $licenseregister->alias->varAlias : old('alias'), array('class' => 'aliasField')) !!}
                                {!! Form::hidden('oldAlias', isset($licenseregister->alias->varAlias)?$licenseregister->alias->varAlias : old('alias')) !!}
                                {!! Form::hidden('previewId') !!}
                                <div class="mb-3 alias-group {{!isset($licenseregister->alias)?'hide':''}}">
                                    <label class="form-label" for="Url">{{ trans('licence-register::template.common.url') }} :</label>
                                    @if(isset($licenseregister->alias->varAlias) && !$userIsAdmin)
                                    @php
                                    $aurl = App\Helpers\MyLibrary::getFrontUri('licence-register')['uri'];
                                    @endphp
                                    <a  class="alias">{!! url("/") !!}</a>
                                    @else
                                    @if(auth()->user()->can('licence-register-create'))
                                    <a href="javascript:void(0);" class="alias">{!! url("/") !!}</a>
                                    <a href="javascript:void(0);" class="editAlias" title="Edit">
                                        <i class="ri-pencil-line"></i>
                                    </a>
                                    <a class="without_bg_icon openLink" title="Open Link" onClick="generatePreview('{{url('/previewpage?url='.(App\Helpers\MyLibrary::getFrontUri('licence-register')['uri']))}}');">
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
                            @php if(isset($licenceregister_highLight->varCompanyId) && ($licenceregister_highLight->varCompanyId != $licenseregister->varCompanyId)){
                                $Class_varCompanyId = " highlitetext";
                                }else{
                                $Class_varCompanyId = "";
                            } @endphp
                            <label class="form-label {{ $Class_varCompanyId }}" for="company_id">{{ trans('licence-register::template.licenceregisterModule.id') }}<span aria-required="true" class="required"> * </span></label>
                            {!! Form::text('company_id',isset($licenseregister->varCompanyId)?$licenseregister->varCompanyId:old('company_id'), array('class' => 'form-control input-sm','id' => 'company_id','maxlength'=>'5','placeholder' => trans('licence-register::template.licenceregisterModule.id'),'autocomplete'=>'off')) !!}
                            <span class="help-block">
                                {{ $errors->first('company_id') }}
                            </span>
                        </div>

                        <div class="mb-3 @if($errors->first('cperson')) has-error @endif form-md-line-input">
                            @php if(isset($licenceregister_highLight->varContactPerson) && ($licenceregister_highLight->varContactPerson != $licenseregister->varContactPerson)){
                                $Class_varContactPerson = " highlitetext";
                                }else{
                                $Class_varContactPerson = "";
                            } @endphp
                            <label class="form-label {{ $Class_varContactPerson }}" for="site_name">{{ trans('licence-register::template.licenceregisterModule.cperson') }} <span aria-required="true" class="required"> * </span></label>
                            {!! Form::text('cperson', isset($licenseregister->varContactPerson) ? $licenseregister->varContactPerson:old('cperson'), array('maxlength'=>'150','id'=>'cperson','placeholder' => trans('licence-register::template.licenceregisterModule.cperson'),'class' => 'form-control hasAlias seoField maxlength-handler titlespellingcheck','autocomplete'=>'off')) !!}
                            <span class="help-block">
                                {{ $errors->first('cperson') }}
                            </span>
                        </div>

                        <div class="mb-3 {{ $errors->has('email') ? 'has-error' : '' }} form-md-line-input">
                            @php if(isset($licenceregister_highLight->varEmail) && ($licenceregister_highLight->varEmail != $licenseregister->varEmail)){
                                $Class_varEmail = " highlitetext";
                                }else{
                                $Class_varEmail = "";
                            } @endphp
                            <label class="form-label {{ $Class_varEmail }}" for="email">{{ trans('licence-register::template.common.email') }}</label>
                            {!! Form::email('email',isset($licenseregister->varEmail)?$licenseregister->varEmail:old('email'), array('class' => 'form-control input-sm', 'maxlength'=>'300','id' => 'email','placeholder' => trans('licence-register::template.common.email'),'autocomplete'=>'off')) !!}
                            <span class="help-block">
                                {{ $errors->first('email') }}
                            </span>
                        </div>

                        <div class="mb-3 {{ $errors->has('link1') ? 'has-error' : '' }} form-md-line-input">
                            @php if(isset($licenceregister_highLight->varWeblink1) && ($licenceregister_highLight->varWeblink1 != $licenseregister->varWeblink1)){
                                $Class_varWeblink1 = " highlitetext";
                                }else{
                                $Class_varWeblink1 = "";
                            } @endphp
                            <label class="form-label {{ $Class_varWeblink1 }}" for="link1">{{ trans('licence-register::template.licenceregisterModule.url1') }}</label>
                            {!! Form::text('link1',isset($licenseregister->varWeblink1)?$licenseregister->varWeblink1:old('link1'), array('class' => 'form-control input-sm','id' => 'link1','placeholder' => trans('licence-register::template.licenceregisterModule.url1'),'autocomplete'=>'off')) !!}
                            <span class="help-block">
                                {{ $errors->first('link1') }}
                            </span>
                        </div>
                        <div class="mb-3 {{ $errors->has('link2') ? 'has-error' : '' }} form-md-line-input">
                            @php if(isset($licenceregister_highLight->varWeblink2) && ($licenceregister_highLight->varWeblink2 != $licenseregister->varWeblink2)){
                                $Class_varWeblink2 = " highlitetext";
                                }else{
                                $Class_varWeblink2 = "";
                            } @endphp
                            <label class="form-label {{ $Class_varWeblink2 }}" for="link2">{{ trans('licence-register::template.licenceregisterModule.url2') }}</label>
                            {!! Form::text('link2',isset($licenseregister->varWeblink2)?$licenseregister->varWeblink2:old('link2'), array('class' => 'form-control input-sm','id' => 'link2','placeholder' => trans('licence-register::template.licenceregisterModule.url2'),'autocomplete'=>'off')) !!}
                            <span class="help-block">
                                {{ $errors->first('link2') }}
                            </span>
                        </div>

                        <div class="mb-3 {{ $errors->has('link3') ? 'has-error' : '' }} form-md-line-input">
                            @php if(isset($licenceregister_highLight->varWeblink3) && ($licenceregister_highLight->varWeblink3 != $licenseregister->varWeblink3)){
                                $Class_varWeblink3 = " highlitetext";
                                }else{
                                $Class_varWeblink3 = "";
                            } @endphp
                            <label class="form-label {{ $Class_varWeblink3 }}" for="link3">{{ trans('licence-register::template.licenceregisterModule.url3') }}</label>
                            {!! Form::text('link3',isset($licenseregister->varWeblink3)?$licenseregister->varWeblink3:old('link3'), array('class' => 'form-control input-sm','id' => 'link3','placeholder' => trans('licence-register::template.licenceregisterModule.url3'),'autocomplete'=>'off')) !!}
                            <span class="help-block">
                                {{ $errors->first('link3') }}
                            </span>
                        </div>

                        <div class="mb-3 form-md-line-input">
                            @php if(isset($licenceregister_highLight->varContactAddress) && ($licenceregister_highLight->varContactAddress != $licenseregister->varContactAddress)){
                                $Class_varContactAddress = " highlitetext";
                                }else{
                                $Class_varContactAddress = "";
                            } @endphp
                            <label class="form-label {{ $Class_varContactAddress }}" for="address">{{ trans('licence-register::template.licenceregisterModule.address') }}<span aria-required="true" class="required"> * </span></label>
                            {!! Form::textarea('address',isset($licenseregister->varContactAddress)?$licenseregister->varContactAddress:old('address'), array('class' => 'form-control maxlength-handler','maxlength'=>'400','id'=>'address','rows'=>'3','placeholder'=>trans('licence-register::template.licenceregisterModule.address'),'styel'=>'max-height:80px;')) !!}
                        </div>

                        <div class="mb-3 @if($errors->first('status')) has-error @endif form-md-line-input">
                            @if(isset($licenceregister_highLight->varStatus) && ($licenceregister_highLight->varStatus != $licenseregister->varStatus))
                                @php $Class_varStatus = " highlitetext"; @endphp
                            @else
                                @php $Class_varStatus = ""; @endphp
                            @endif
                            <label class="form-label {{ $Class_varStatus }}" for="site_name">Status<span aria-required="true" class="required"> * </span></label>
                            <select class="form-control" data-choices name="status" id="status">
                                <option value="">Select Status</option>
                                @foreach($selectstatus as $ValueSector)
                                @php $permissionName = 'licence-register-list' @endphp
                                @php $selected = ''; @endphp
                                @if(isset($licenseregister->varStatus))
                                @if($ValueSector == $licenseregister->varStatus)
                                @php $selected = 'selected';  @endphp
                                @endif
                                @endif
                                <option value="{{$ValueSector}}" {{ $selected }}>{{ ($ValueSector == "licence-register") ? 'Select Status' : $ValueSector }}</option>
                                @endforeach
                            </select>
                            <span class="help-block">
                                {{ $errors->first('status') }}
                            </span>
                        </div>

                        <div class="mb-3 @if($errors->first('service[]')) has-error @endif form-md-line-input">
                            @php if(isset($licenceregister_highLight->varService) && ($licenceregister_highLight->varService != $licenseregister->varService)){
                                $Class_varService = " highlitetext";
                                }else{
                                $Class_varService = "";
                            } @endphp
                            <label class="form-label {{ $Class_varService }}" for="site_name">Select Service/Network<span aria-required="true" class="required"> * </span></label>
                            <select class="form-control" data-choices multiple name="service[]" id="service[]">
                                <option value="">Select Service/Network</option>
                                @foreach($selectservice as  $service)
                                @php $permissionName = 'licence-register-list' @endphp
                                @php $selected = ''; @endphp
                                @if(isset($licenseregister->varService))
                                @php 
                                $serv= explode(",",$licenseregister->varService)
                                @endphp
                                @if(in_array($service->id,$serv))
                                @php $selected = 'selected'; @endphp
                                @endif 
                                @endif
                                <option value="{{$service->id}}" {{ $selected }}>{{ $service->varTitle }} (Code: {{$service->serviceCode}})</option>
                                @endforeach
                            </select>
                            <span class="help-block">
                                {{ $errors->first('service[]') }}
                            </span>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="image_thumb multi_upload_images">
                                    <div class="mb-3">
                                        @php if(isset($licenceregister_highLight->fkIntDocId) && ($licenceregister_highLight->fkIntDocId != $licenseregister->fkIntDocId)){
                                        $Class_file = " highlitetext";
                                        }else{
                                        $Class_file = "";
                                        } @endphp
                                        <label class="form-label {!! $Class_file !!}">Select Documents</label>
                                        <div class="clearfix"></div>
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width:100%;float:left; height:120px;position: relative;">
                                                <img class="img_opacity" src="{{ $CDN_PATH.'resources/images/upload_file.gif' }}" />
                                            </div>
                                            <div class="input-group">
                                                <a class="document_manager multiple-selection" data-multiple="true" onclick="MediaManager.openDocumentManager('licence-register');"><span class="fileinput-new"></span></a>
                                                <input class="form-control" type="hidden" id="licence-register" name="doc_id" value="{{ isset($licenseregister->fkIntDocId)?$licenseregister->fkIntDocId:old('doc_id') }}" />
                                                @php
                                                if (method_exists($MyLibrary, 'GetDocumentFolderID')) {
                                                if(isset($licenseregister->fkIntDocId)){
                                                $folderid = App\Helpers\MyLibrary::GetDocumentFolderID($licenseregister->fkIntDocId);
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
                            @if(isset($docObj) && count($docObj) > 0)
                                <div class="col-md-12" id="licence-register_documents">
                                    <div class="multi_image_list" id="multi_document_list">
                                        <ul id="document_sortable" style="cursor: move;">
                                            @foreach($docObj as $value)
                                            <li id="doc_{{ $value->id }}">
                                                <span title="{{ $value->txtDocumentName }}.{{ $value->varDocumentExtension }}">
                                                    @if ($value->varDocumentExtension == 'pdf' || $value->varDocumentExtension == 'PDF')
                                                    <img  src="{{ $CDN_PATH.'assets/images/documents_logo/pdf.png' }}" alt="Img" />
                                                    @elseif($value->varDocumentExtension == 'doc' || $value->varDocumentExtension == 'docx')
                                                    <img  src="{{ $CDN_PATH.'assets/images/documents_logo/doc.png' }}" alt="Img" />
                                                    @elseif($value->varDocumentExtension == 'xls' || $value->varDocumentExtension == 'xlsx')
                                                    <img  src="{{ $CDN_PATH.'assets/images/documents_logo/xls.png' }}" alt="Img" />
                                                    @else
                                                    <img  src="{{ $CDN_PATH.'assets/images/documents_logo/txt.png' }}" alt="Img" />
                                                    @endif
                                                    <a href="javascript:;" onclick="MediaManager.removeDocumentFromGallery('{{ $value->id }}');" class="delect_image" data-dismiss="fileinput"><i class="ri-delete-bin-line"></i></a>
                                                </span>
                                            </li>
                                            @endforeach
                                            
                                        </ul>
                                    </div>
                                </div>
                            @else
                            <div class="col-md-12" id="licence-register_documents"></div>
                            @endif
                        </div>
                        <br>
                        @php $defaultDt = (null !== old('start_date_time'))?old('start_date_time'):date('Y-m-d H:i'); @endphp
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3 form-md-line-input">
                                    @php if(isset($licenceregister_highLight->dtDateTime) && ($licenceregister_highLight->dtDateTime != $licenseregister->dtDateTime)){
                                    $Class_date = " highlitetext";
                                    }else{
                                    $Class_date = "";
                                    } @endphp
                                    <label class="control-label form-label {!! $Class_date !!}">{{ trans('licence-register::template.common.startDateAndTime') }}<span aria-required="true" class="required"> * </span></label>
                                    <div class="input-group date form_meridian_datetime @if($errors->first('start_date_time')) has-error @endif" data-date="{{ Carbon\Carbon::today()->format('Y-m-d') }}T15:25:00Z">
                                        <span class="input-group-text date_default" id="basic-addon1">
                                            <i class="ri-calendar-fill"></i>
                                        </span>
                                        {!! Form::text('start_date', date(Config::get('Constant.DEFAULT_DATE_FORMAT'),strtotime(isset($licenseregister->dtDateTime)?$licenseregister->dtDateTime:$defaultDt)), array('class' => 'form-control', 'data-provider' => 'flatpickr', 'data-date-format' => Config::get('Constant.DEFAULT_DATE_FORMAT'), 'data-enable-time' => '','maxlength'=>160,'size'=>'16','id'=>'start_date','autocomplete'=>'off','onkeypress'=>"javascript: return KeycheckOnlyDate(event);",'onpaste'=>'return false')) !!}
                                    </div>
                                    <span class="help-block">{{ $errors->first('start_date') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3 @if($errors->first('issue_note')) has-error @endif form-md-line-input">
                            @php if(isset($licenceregister_highLight->varIssuenote) && ($licenceregister_highLight->varIssuenote != $licenseregister->varIssuenote)){
                                $Class_varIssuenote = " highlitetext";
                                }else{
                                $Class_varIssuenote = "";
                            } @endphp
                            <label class="form-label {{ $Class_varIssuenote }}" for="site_name">{{ trans('licence-register::template.licenceregisterModule.issuenote') }} </label>
                            {!! Form::text('issue_note', isset($licenseregister->varIssuenote) ? $licenseregister->varIssuenote:old('cperson'), array('maxlength'=>'150','id'=>'issue_note','placeholder' => trans('licence-register::template.licenceregisterModule.issuenote'),'class' => 'form-control hasAlias seoField maxlength-handler titlespellingcheck','autocomplete'=>'off')) !!}
                            <span class="help-block">
                                {{ $errors->first('issue_note') }}
                            </span>
                        </div>

                        <div class="row versionradio" id="DisplayLink">
                            <div class="col-md-12">
                                <div class="mb-3 form-md-line-input">
                                    @php if(isset($licenceregister_highLight->chrRenewal) && ($licenceregister_highLight->chrRenewal != $licenseregister->chrRenewal)){
                                        $Class_chrRenewal = " highlitetext";
                                        }else{
                                        $Class_chrRenewal = "";
                                    } @endphp
                                    <label class="form-label {{ $Class_chrRenewal }}">Renewal:</label>
                                    @if (isset($licenseregister->chrRenewal) && $licenseregister->chrRenewal == 'Y')
                                        @php $checked_section_link = true; @endphp
                                    @else
                                        @php $checked_section_link = null; @endphp
                                    @endif
                                    {{ Form::checkbox('chrRenewal',null,$checked_section_link, array('ckass'=>'form-check-input','id'=>'chrRenewal','value'=>'Y')) }}
                                </div>
                            </div>
                        </div>

                        <div class="row imguploader">
                            <div class="col-md-6">
                                <div class="mb-3 form-md-line-input">
                                    @php if(isset($licenceregister_highLight->dtRenewaldate) && ($licenceregister_highLight->dtRenewaldate != $licenseregister->dtRenewaldate)){
                                        $Class_dtRenewaldate = " highlitetext";
                                        }else{
                                        $Class_dtRenewaldate = "";
                                    } @endphp

                                    @php $defaultDt = (null !== old('renewal_date'))?old('renewal_date'):date('Y-m-d H:i'); @endphp

                                    @php 
                                    if(isset($licenseregister->dtRenewaldate) && !empty($licenseregister->dtRenewaldate)){
                                    $startDate = $licenseregister->dtRenewaldate;
                                    }else{
                                    $startDate = null;
                                    } @endphp

                                    <label class="control-label form-label {!! $Class_dtRenewaldate !!}">{{ trans('licence-register::template.common.renewaldate') }}<span aria-required="true" class="required"> * </span></label>
                                    <div class="input-group date form_meridian_datetime @if($errors->first('renewal_date')) has-error @endif" data-date="{{ Carbon\Carbon::today()->format('Y-m-d') }}T15:25:00Z">
                                        <span class="input-group-text date_default" id="basic-addon1">
                                            <i class="ri-calendar-fill"></i>
                                        </span>
                                        {!! Form::text('renewal_date',date(Config::get('Constant.DEFAULT_DATE_FORMAT').' '.Config::get('Constant.DEFAULT_TIME_FORMAT'),strtotime(isset($licenseregister->dtRenewaldate)?$licenseregister->dtRenewaldate:$defaultDt)), array('class' => 'form-control', 'data-provider' => 'flatpickr', 'data-date-format' => Config::get('Constant.DEFAULT_DATE_FORMAT'), 'data-enable-time' => '','id'=>'renewal_date','autocomplete'=>'off')) !!}
                                    </div>
                                    <span class="help-block">{{ $errors->first('renewal_date') }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3 @if($errors->first('renewal_note')) has-error @endif form-md-line-input">
                                    @php if(isset($licenceregister_highLight->varRenewalNote) && ($licenceregister_highLight->varRenewalNote != $licenseregister->varRenewalNote)){
                                        $Class_varRenewalNote = " highlitetext";
                                        }else{
                                        $Class_varRenewalNote = "";
                                    } @endphp
                                    <label class="form-label {{ $Class_varRenewalNote }}" for="site_name">{{ trans('licence-register::template.licenceregisterModule.renewalnote') }} </label>
                                    {!! Form::text('renewal_note', isset($licenseregister->varRenewalNote) ? $licenseregister->varRenewalNote:old('renewal_note'), array('maxlength'=>'150','id'=>'renewal_note','placeholder' => trans('licence-register::template.licenceregisterModule.renewalnote'),'class' => 'form-control hasAlias seoField maxlength-handler titlespellingcheck','autocomplete'=>'off')) !!}
                                    <span class="help-block">
                                        {{ $errors->first('renewal_note') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        @if(isset($licenseregister->intSearchRank))
                            @php $srank = $licenseregister->intSearchRank; @endphp
                        @else
                            @php $srank = null !== old('search_rank') ? old('search_rank') : 2 ; @endphp
                        @endif

                        @if(isset($licenceregister_highLight->intSearchRank) && ($licenceregister_highLight->intSearchRank != $licenseregister->intSearchRank))
                            @php $Class_intSearchRank = " highlitetext"; @endphp
                        @else
                            @php $Class_intSearchRank = ""; @endphp
                        @endif

                        @if(isset($licenceregister_highLight->varTags) && ($licenceregister_highLight->varTags != $licenseregister->varTags))
                            @php $Class_varTags = " highlitetext"; @endphp
                        @else
                            @php $Class_varTags = ""; @endphp
                        @endif
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="nopadding">
                                    @include('powerpanel.partials.seoInfo',['form'=>'frmLicenceRegister','inf'=>isset($metaInfo)?$metaInfo:false,'inf_highLight'=> isset($metaInfo_highLight)?$metaInfo_highLight:false,'Class_intSearchRank' => $Class_intSearchRank, 'srank' => $srank , 'Class_varTags' => $Class_varTags])
                                </div>
                            </div>
                        </div>

                        <h3 class="form-section">{{ trans('licence-register::template.common.displayinformation') }}</h3>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3 @if($errors->first('order')) has-error @endif form-md-line-input">
                                    @php
                                    $display_order_attributes = array('class' => 'form-control','maxlength'=>5,'placeholder'=>trans('licence-register::template.common.displayorder'),'autocomplete'=>'off');
                                    @endphp
                                    @if(isset($licenceregister_highLight->intDisplayOrder) && ($licenceregister_highLight->intDisplayOrder != $licenseregister->intDisplayOrder))
                                    @php $Class_intDisplayOrder = " highlitetext"; @endphp
                                    @else
                                    @php $Class_intDisplayOrder = ""; @endphp
                                    @endif
                                    <label class="form-label {{ $Class_intDisplayOrder }}" for="site_name">{{ trans('licence-register::template.common.displayorder') }} <span aria-required="true" class="required"> * </span></label>
                                    {!! Form::text('order', isset($licenseregister->intDisplayOrder)?$licenseregister->intDisplayOrder:1, $display_order_attributes) !!}
                                    <span style="color: red;">
                                        {{ $errors->first('order') }}
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                @if(isset($licenceregister_highLight->chrPublish) && ($licenceregister_highLight->chrPublish != $licenseregister->chrPublish))
                                    @php $Class_chrPublish = " highlitetext"; @endphp
                                @else
                                    @php $Class_chrPublish = ""; @endphp
                                @endif

                                @if(isset($licenseregister) && $licenseregister->chrAddStar == 'Y')
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="control-label form-label"> Publish/ Unpublish</label>
                                            <input type="hidden" id="chrMenuDisplay" name="chrMenuDisplay" value="{{ isset($licenseregister->chrPublish) ? $licenseregister->chrPublish : '' }}">
                                            <p><b>NOTE:</b> This record is in Approval Request , so it can&#39;t be published/unpublished.</p>
                                        </div>
                                    </div>
                                @elseif(isset($licenseregister) && $licenseregister->chrDraft == 'D' && $licenseregister->chrAddStar != 'Y')
                                    @include('powerpanel.partials.displayInfo',['Class_chrPublish'=>$Class_chrPublish,'display' => (isset($licenseregister->chrDraft)?$licenseregister->chrDraft:'D')])
                                @else
                                    @include('powerpanel.partials.displayInfo',['Class_chrPublish'=>$Class_chrPublish,'display' => (isset($licenseregister->chrPublish)?$licenseregister->chrPublish:'Y')])
                                @endif
                            </div>
                        </div>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-12">
                                    @if(isset($licenseregister->fkMainRecord) && $licenseregister->fkMainRecord != 0)
                                    <button type="submit" name="saveandexit" class="btn btn-primary" value="saveandexit">{!! trans('licence-register::template.common.approve') !!}</button>
                                    @else
                                    @if($userIsAdmin)
                                    <button type="submit" name="saveandedit" class="btn btn-primary" value="saveandedit">{!! trans('licence-register::template.common.saveandedit') !!}</button>
                                    <button type="submit" name="saveandexit" class="btn btn-primary" value="saveandexit">{!! trans('licence-register::template.common.saveandexit') !!}</button>
                                    @else
                                    @if((isset($chrNeedAddPermission) && $chrNeedAddPermission == 'N') && (isset($charNeedApproval) && $charNeedApproval == 'N'))
                                    <button type="submit" name="saveandexit" class="btn btn-primary" value="saveandexit">{!! trans('licence-register::template.common.saveandexit') !!}</button>
                                    @else
                                    <button type="submit" name="saveandexit" class="btn btn-primary" value="approvesaveandexit">{!! trans('licence-register::template.common.approvesaveandexit') !!}</button>
                                    @endif
                                    @endif
                                    @endif
                                    <a class="btn btn-danger" href="{{ url('powerpanel/licence-register') }}">{{ trans('licence-register::template.common.cancel') }}</a>
                                    @if(isset($licenseregister) && !empty($licenseregister))
                                    &nbsp;<a class="btn btn-primary" title="Preview" onClick="generatePreview('{{url('/previewpage?url='.(App\Helpers\MyLibrary::getFrontUri('licence-register')['uri']))}}');">Preview</a>
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

<link href="{{ $CDN_PATH.'resources/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css' }}" rel="stylesheet" type="text/css"/>
<link href="{{ $CDN_PATH.'resources/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css' }}" rel="stylesheet" type="text/css" />
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js' }}" type="text/javascript"></script>




<script type="text/javascript">
window.site_url = '{!! url("/") !!}';
var seoFormId = 'frmLicenceRegister';
var user_action = "{{ isset($licenseregister)?'edit':'add' }}";
var moduleAlias = "{{ App\Helpers\MyLibrary::getFrontUri('licence-register')['moduleAlias'] }}";
var preview_add_route = '{!! route("powerpanel.licence-register.addpreview") !!}';
var previewForm = $('#frmLicenceRegister');
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
<script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/licence-register/licence-register_validation.js' }}" type="text/javascript"></script>
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
<script>
$(document).ready(function() {
    $('#document_sortable').sortable({
        //axis: 'x',
        stop: function(event, ui) {
            var aData = $(this).sortable('toArray');
            $.each(aData, function( index, value ) {
                aData[index] = value.replace('doc_','');
            });
            var documentIdsStrings = aData.join(',');
            var documentIds = documentIdsStrings.replace(/item-/g, '');
            $('.document_manager').next("input[name^='doc_id']").val(documentIds);
        }
    });
});
</script>
@if (Config::get('Constant.DEFAULT_VISUAL') == 'Y')
@php Powerpanel\VisualComposer\Controllers\VisualComposerController::get_builder_css_js()@endphp
@endif
@endsection