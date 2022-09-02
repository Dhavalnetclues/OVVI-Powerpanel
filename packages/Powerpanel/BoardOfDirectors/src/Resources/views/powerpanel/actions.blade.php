
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
                    {!! Form::open(['method' => 'post','id'=>'frmBoardofdirectors']) !!}
                        {!! Form::hidden('fkMainRecord', isset($boardofdirectors->fkMainRecord)?$boardofdirectors->fkMainRecord:old('fkMainRecord')) !!}
                        @if(isset($boardofdirectors))
                        @if (File::exists(base_path() . '/resources/views/powerpanel/partials/lockedpage.blade.php') != null)
                        @include('powerpanel.partials.lockedpage',['pagedata'=>$boardofdirectors])
                        @endif
                        @endif
                        <div class="mb-3 @if($errors->first('sector')) has-error @endif form-md-line-input">
                            @if(isset($boardofdirectors_highLight->varSector) && ($boardofdirectors_highLight->varSector != $boardofdirectors->varSector))
                                @php $Class_varSector = " highlitetext"; @endphp
                            @else
                                @php $Class_varSector = ""; @endphp
                            @endif
                            @include('shiledcmstheme::powerpanel.partials.sector-dropdown', ['selected_sector' => isset($boardofdirectors->varSector)?$boardofdirectors->varSector:'','Class_varSector' => $Class_varSector])
                            <span class="help-block">
                                {{ $errors->first('sector') }}
                            </span>
                        </div>
                        <div class="mb-3 @if($errors->first('title')) has-error @endif form-md-line-input">
                            @php if(isset($boardofdirectors_highLight->varTitle) && ($boardofdirectors_highLight->varTitle != $boardofdirectors->varTitle)){
                            $Class_title = " highlitetext";
                            }else{
                            $Class_title = "";
                            } @endphp
                            <label class="form-label {!! $Class_title !!}" for="site_name">{{ trans('fmbroadcasting::template.common.name') }} <span aria-required="true" class="required"> * </span></label>
                            {!! Form::text('title', isset($boardofdirectors->varTitle) ? $boardofdirectors->varTitle:old('title'), array('maxlength'=>'150','id'=>'title','placeholder' => trans('boardofdirectors::template.common.name'),'class' => 'form-control hasAlias seoField maxlength-handler titlespellingcheck','autocomplete'=>'off')) !!}
                            <span class="help-block">
                                {{ $errors->first('title') }}
                            </span>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <!-- code for alias -->
                                {!! Form::hidden(null, null, array('class' => 'hasAlias','data-url' => 'powerpanel/fmbroadcasting')) !!}
                                {!! Form::hidden('alias', isset($boardofdirectors->alias->varAlias) ? $boardofdirectors->alias->varAlias : old('alias'), array('class' => 'aliasField')) !!}
                                {!! Form::hidden('oldAlias', isset($boardofdirectors->alias->varAlias)?$boardofdirectors->alias->varAlias : old('alias')) !!}
                                {!! Form::hidden('previewId') !!}
                                <div class="mb-3 alias-group {{!isset($boardofdirectors->alias)?'hide':''}}">
                                    <label class="form-label" for="Url">{{ trans('fmbroadcasting::template.common.url') }} :</label>
                                    @if(isset($boardofdirectors->alias->varAlias) && !$userIsAdmin)
                                    @php
                                    $aurl = App\Helpers\MyLibrary::getFrontUri('boardofdirectors')['uri'];
                                    @endphp
                                    <a  class="alias">{!! url("/") !!}</a>
                                    @else
                                    @if(auth()->user()->can('boardofdirectors-create'))
                                    <a href="javascript:void;" class="alias">{!! url("/") !!}</a>
                                    <a href="javascript:void(0);" class="editAlias" title="Edit">
                                        <i class="ri-pencil-line"></i>
                                    </a>
                                    <a class="without_bg_icon openLink" title="Open Link" onClick="generatePreview('{{url('/previewpage?url='.(App\Helpers\MyLibrary::getFrontUri('boardofdirectors')['uri']))}}');">
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
                        <div class="mb-3 {{ $errors->has('email') ? 'has-error' : '' }} form-md-line-input">
                            @php if(isset($boardofdirectors_highLight->varEmail) && ($boardofdirectors_highLight->varEmail != $boardofdirectors->varEmail)){
                            $Class_varEmail = " highlitetext";
                            }else{
                            $Class_varEmail = "";
                            } @endphp
                            <label class="form-label {{ $Class_varEmail }}" for="email">{{ trans('boardofdirectors::template.common.email') }}</label>
                            {!! Form::email('email',isset($boardofdirectors->varEmail)?$boardofdirectors->varEmail:old('email'), array('class' => 'form-control input-sm', 'maxlength'=>'300','id' => 'email','placeholder' => trans('boardofdirectors::template.common.email'),'autocomplete'=>'off')) !!}
                            <span class="help-block">
                                {{ $errors->first('email') }}
                            </span>
                        </div>

                        <div class="mb-3 {{ $errors->has('phone_no') ? 'has-error' : '' }} form-md-line-input">
                            @php if(isset($boardofdirectors_highLight->varPhoneNo) && ($boardofdirectors_highLight->varPhoneNo != $boardofdirectors->varPhoneNo)){
                            $Class_varPhoneNo = " highlitetext";
                            }else{
                            $Class_varPhoneNo = "";
                            } @endphp
                            <label class="form-label {{ $Class_varPhoneNo }}" for="phone_no">{{ trans('boardofdirectors::template.common.phoneno') }}</label>
                            {!! Form::tel('phone_no',isset($boardofdirectors->varPhoneNo)?$boardofdirectors->varPhoneNo:old('phone_no'), array('class' => 'form-control input-sm','id' => 'phone_no','minlength'=>'6','maxlength'=>'20','onpaste'=>'return false;', 'ondrop'=>'return false;','placeholder' => trans('boardofdirectors::template.common.phoneno'),'autocomplete'=>'off', 'onkeypress'=>'javascript: return KeycheckOnlyPhonenumber(event);')) !!}
                            <span class="help-block">
                                {{ $errors->first('phone_no') }}
                            </span>
                        </div>

                        <div class="mb-3 @if($errors->first('department')) has-error @endif form-md-line-input">
                            @php if(isset($boardofdirectors_highLight->varDepartment) && ($boardofdirectors_highLight->varDepartment != $boardofdirectors->varDepartment)){
                            $Class_varDepartment = " highlitetext";
                            }else{
                            $Class_varDepartment = "";
                            } @endphp
                            <label class="form-label {{ $Class_varDepartment }}" for="site_name">{{ trans('boardofdirectors::template.boardofdirectorsModule.department') }}</label>
                            {!! Form::text('department', isset($boardofdirectors->varDepartment)?$boardofdirectors->varDepartment:old('department'), array('maxlength' => 100,'placeholder' => trans("boardofdirectors::template.boardofdirectorsModule.department"),'class' => 'form-control maxlength-handler','autocomplete'=>'off')) !!}
                            <span class="help-block"> {{ $errors->first('department') }} </span>
                        </div>

                        <div class="mb-3 @if($errors->first('tag_line')) has-error @endif form-md-line-input">
                            @php if(isset($boardofdirectors_highLight->varTagLine) && ($boardofdirectors_highLight->varTagLine != $boardofdirectors->varTagLine)){
                            $Class_varTagLine = " highlitetext";
                            }else{
                            $Class_varTagLine = "";
                            } @endphp
                            <label class="form-label {{ $Class_varTagLine }}" for="site_name">{{ trans('boardofdirectors::template.boardofdirectorsModule.designation') }}<span aria-required="true" class="required"> * </span></label>
                            {!! Form::text('tag_line', isset($boardofdirectors->varTagLine)?$boardofdirectors->varTagLine:old('tag_line'), array('maxlength' => 100,'placeholder' => trans("boardofdirectors::template.boardofdirectorsModule.designation"),'class' => 'form-control maxlength-handler','autocomplete'=>'off')) !!}
                            <span class="help-block"> {{ $errors->first('tag_line') }} </span>
                        </div>


                        <div class="row">
                            <div class="col-md-12">
                                @if(isset($boardofdirectors_highLight->fkIntImgId) && ($boardofdirectors_highLight->fkIntImgId != $boardofdirectors->fkIntImgId))
                                @php $Class_fkIntImgId = " highlitetext"; @endphp
                                @else
                                @php $Class_fkIntImgId = ""; @endphp
                                @endif
                                <div class="image_thumb multi_upload_images">
                                    <div class="mb-3">
                                        <label class="form-label {{ $Class_fkIntImgId }}" for="front_logo">{{ trans('boardofdirectors::template.common.selectimage') }} </label>
                                        <div class="clearfix"></div>
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <div class="fileinput-preview thumbnail blog_image_img" data-trigger="fileinput" style="width:100%;float:left; height:120px;position: relative;">
                                                @if(old('image_url'))
                                                <img src="{{ old('image_url') }}" />
                                                @elseif(isset($boardofdirectors->fkIntImgId))
                                                <img src="{!! App\Helpers\resize_image::resize($boardofdirectors->fkIntImgId,120,120) !!}" />
                                                @else
                                                <img class="img_opacity" src="{{ $CDN_PATH.'resources/images/upload_file.gif' }}" />
                                                @endif
                                            </div>

                                            <div class="input-group">
                                                <a class="media_manager" data-multiple="false" onclick="MediaManager.open('blog_image');"><span class="fileinput-new"></span></a>
                                                <input class="form-control" type="hidden" id="blog_image" name="img_id" value="{{ isset($boardofdirectors->fkIntImgId)?$boardofdirectors->fkIntImgId:old('img_id') }}" />
                                                @php
                                                if (method_exists($MyLibrary, 'GetFolderID')) {
                                                if(isset($boardofdirectors->fkIntImgId)){
                                                $folderid = App\Helpers\MyLibrary::GetFolderID($boardofdirectors->fkIntImgId);
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
                                                <a href="javascript:;" class="fileinput-exists remove_img removeimg" data-dismiss="fileinput"><i class="ri-delete-bin-line"></i></a>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        @php $height = isset($settings->height)?$settings->height:292; $width = isset($settings->width)?$settings->width:372; @endphp <span>{{ trans('boardofdirectors::template.common.imageSize',['height'=>$height, 'width'=>$width]) }}</span>
                                    </div>
                                    <span class="help-block">
                                        {{ $errors->first('img_id') }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                @if(isset($boardofdirectors_highLight->fkIntDocId) && ($boardofdirectors_highLight->fkIntDocId != $boardofdirectors->fkIntDocId))
                                @php $Class_fkIntDocId = " highlitetext"; @endphp
                                @else
                                @php $Class_fkIntDocId = ""; @endphp
                                @endif
                                <div class="image_thumb multi_upload_images">
                                    <div class="mb-3">
                                        <label class="form-label {{ $Class_fkIntDocId }}">Upload Bio PDF</label>
                                        <div class="clearfix"></div>
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width:100%;float:left; height:120px;position: relative;">
                                                <img class="img_opacity" src="{{ $CDN_PATH.'resources/images/upload_file.gif' }}" />
                                            </div>
                                            <div class="input-group">
                                                <a class="document_manager multiple-selection" data-multiple="false" onclick="MediaManager.openDocumentManager('biopdf');"><span class="fileinput-new"></span></a>
                                                <input class="form-control" type="hidden" id="biopdf" name="doc_id" value="{{ isset($boardofdirectors->fkIntDocId)?$boardofdirectors->fkIntDocId:old('doc_id') }}" />
                                                @php
                                                if (method_exists($MyLibrary, 'GetDocumentFolderID')) {
                                                if(isset($boardofdirectors->fkIntDocId)){
                                                    $folderid = App\Helpers\MyLibrary::GetDocumentFolderID($boardofdirectors->fkIntDocId);
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
                            @if(!empty($boardofdirectors->fkIntDocId) && isset($boardofdirectors->fkIntDocId))
                            @php
                            $docsAray = explode(',', $boardofdirectors->fkIntDocId);
                            $docObj   = App\Document::getDocDataByIds($docsAray);
                            @endphp
                            <div class="col-md-12" id="biopdf_documents">
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
                            <div class="col-md-12" id="biopdf_documents"></div>
                            @endif
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3 @if($errors->first('short_description')) has-error @endif form-md-line-input">
                                    @php if(isset($boardofdirectors_highLight->varShortDescription) && ($boardofdirectors_highLight->varShortDescription != $boardofdirectors->varShortDescription)){
                                    $Class_ShortDescription = " highlitetext";
                                    }else{
                                    $Class_ShortDescription = "";
                                    } @endphp
                                    <label class="form-label {!! $Class_ShortDescription !!}">Short Description</label>
                                    {!! Form::textarea('varShortDescription', isset($boardofdirectors->varShortDescription)?$boardofdirectors->varShortDescription:old('varShortDescription'), array('maxlength' => 800,'class' => 'form-control seoField maxlength-handler shortdescspellingcheck','id'=>'varShortDescription','rows'=>'3','placeholder'=>'Short Description')) !!}
                                    <span class="help-block">{{ $errors->first('varShortDescription') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                            @php if(isset($boardofdirectors_highLight->txtDescription) && ($boardofdirectors_highLight->txtDescription != $boardofdirectors->txtDescription)){
                                    $Class_Description = " highlitetext";
                                    }else{
                                    $Class_Description = "";
                                    } @endphp
                                    <label class="form-label {!! $Class_Description !!}">Description</label>
                                <div class="mb-3 @if($errors->first('description')) has-error @endif form-md-line-input">
                                    @if (Config::get('Constant.DEFAULT_VISUAL') == 'Y')
                                    <div id="body-roll">
                                        @php
                                        $sections = [];
                                        @endphp
                                        @if(isset($boardofdirectors))
                                        @php
                                        $sections = json_decode($boardofdirectors->txtDescription);
                                        @endphp
                                        @endif
                                        <!-- Builder include -->
                                        @php
                                        Powerpanel\VisualComposer\Controllers\VisualComposerController::page_section(['sections'=>$sections])
                                        @endphp
                                    </div>
                                    @else
                                    {!! Form::textarea('description', isset($boardofdirectors->txtDescription)?$boardofdirectors->txtDescription:old('description'), array('placeholder' => trans('frmBoardofdirectors::template.common.description'),'class' => 'form-control','id'=>'txtDescription')) !!}
                                    @endif
                                    <span class="help-block">{{ $errors->first('description') }}</span>
                                </div>
                            </div>
                        </div>

                        @if(isset($boardofdirectors->intSearchRank))
                            @php $srank = $boardofdirectors->intSearchRank; @endphp
                        @else
                            @php $srank = null !== old('search_rank') ? old('search_rank') : 2 ; @endphp
                        @endif

                        @if(isset($boardofdirectors_highLight->intSearchRank) && ($boardofdirectors_highLight->intSearchRank != $boardofdirectors->intSearchRank))
                            @php $Class_intSearchRank = " highlitetext"; @endphp
                        @else
                            @php $Class_intSearchRank = ""; @endphp
                        @endif

                        @if(isset($boardofdirectors_highLight->varTags) && ($boardofdirectors_highLight->varTags != $boardofdirectors->varTags))
                            @php $Class_varTags = " highlitetext"; @endphp
                        @else
                            @php $Class_varTags = ""; @endphp
                        @endif

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="nopadding">
                                    @include('powerpanel.partials.seoInfo',['form'=>'frmBoardofdirectors','inf'=>isset($metaInfo)?$metaInfo:false,'inf_highLight'=> isset($metaInfo_highLight)?$metaInfo_highLight:false,'Class_intSearchRank' => $Class_intSearchRank, 'srank' => $srank , 'Class_varTags' => $Class_varTags])
                                </div>
                            </div>
                        </div>

                        <h3 class="form-section">{{ trans('boardofdirectors::template.common.displayinformation') }}</h3>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3 @if($errors->first('order')) has-error @endif form-md-line-input">
                                    @php
                                    $display_order_attributes = array('class' => 'form-control','maxlength'=>5,'placeholder'=>trans('boardofdirectors::template.common.displayorder'),'autocomplete'=>'off');
                                    @endphp
                                    @if(isset($boardofdirectors_highLight->intDisplayOrder) && ($boardofdirectors_highLight->intDisplayOrder != $boardofdirectors->intDisplayOrder))
                                    @php $Class_intDisplayOrder = " highlitetext"; @endphp
                                    @else
                                    @php $Class_intDisplayOrder = ""; @endphp
                                    @endif
                                    <label class="form-label {{ $Class_intDisplayOrder }}" for="site_name">{{ trans('boardofdirectors::template.common.displayorder') }} <span aria-required="true" class="required"> * </span></label>
                                    {!! Form::text('order', isset($boardofdirectors->intDisplayOrder)?$boardofdirectors->intDisplayOrder:1, $display_order_attributes) !!}
                                    <span style="color: red;">
                                        {{ $errors->first('order') }}
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                @if(isset($boardofdirectors_highLight->chrPublish) && ($boardofdirectors_highLight->chrPublish != $boardofdirectors->chrPublish))
                                    @php $Class_chrPublish = " highlitetext"; @endphp
                                @else
                                    @php $Class_chrPublish = ""; @endphp
                                @endif

                                @if(isset($boardofdirectors) && $boardofdirectors->chrAddStar == 'Y')
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="control-label form-label"> Publish/ Unpublish</label>
                                            <input type="hidden" id="chrMenuDisplay" name="chrMenuDisplay" value="{{ isset($boardofdirectors->chrPublish) ? $boardofdirectors->chrPublish : '' }}">
                                            <p><b>NOTE:</b> This record is in Approval Request , so it can&#39;t be published/unpublished.</p>
                                        </div>
                                    </div>
                                @elseif(isset($boardofdirectors) && $boardofdirectors->chrDraft == 'D' && $boardofdirectors->chrAddStar != 'Y')
                                    @include('powerpanel.partials.displayInfo',['Class_chrPublish'=>$Class_chrPublish,'display' => (isset($boardofdirectors->chrDraft)?$boardofdirectors->chrDraft:'D')])
                                @else
                                    @include('powerpanel.partials.displayInfo',['Class_chrPublish'=>$Class_chrPublish,'display' => (isset($boardofdirectors->chrPublish)?$boardofdirectors->chrPublish:'Y')])
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-12">
                                    @if(isset($boardofdirectors->fkMainRecord) && $boardofdirectors->fkMainRecord != 0)
                                    <button type="submit" name="saveandexit" class="btn btn-primary" value="saveandexit">{!! trans('boardofdirectors::template.common.approve') !!}</button>
                                    @else
                                    @if($userIsAdmin)
                                    <button type="submit" name="saveandedit" class="btn btn-primary" value="saveandedit">{!! trans('boardofdirectors::template.common.saveandedit') !!}</button>
                                    <button type="submit" name="saveandexit" class="btn btn-primary" value="saveandexit">{!! trans('boardofdirectors::template.common.saveandexit') !!}</button>
                                    @else
                                    @if((isset($chrNeedAddPermission) && $chrNeedAddPermission == 'N') && (isset($charNeedApproval) && $charNeedApproval == 'N'))
                                    <button type="submit" name="saveandexit" class="btn btn-primary" value="saveandexit">{!! trans('boardofdirectors::template.common.saveandexit') !!}</button>
                                    @else
                                    <button type="submit" name="saveandexit" class="btn btn-primary" value="approvesaveandexit">{!! trans('boardofdirectors::template.common.approvesaveandexit') !!}</button>
                                    @endif
                                    @endif
                                    @endif
                                    <a class="btn btn-danger" href="{{ url('powerpanel/boardofdirectors') }}">{{ trans('boardofdirectors::template.common.cancel') }}</a>
                                    @if(isset($boardofdirectors) && !empty($boardofdirectors))
                                    &nbsp;<a class="btn btn-primary" title="Preview" onClick="generatePreview('{{url('/previewpage?url='.(App\Helpers\MyLibrary::getFrontUri('boardofdirectors')['uri']))}}');">Preview</a>
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
    var seoFormId = 'frmBoardofdirectors';
    var user_action = "{{ isset($boardofdirectors)?'edit':'add' }}";
    var moduleAlias = "{{ App\Helpers\MyLibrary::getFrontUri('boardofdirectors')['moduleAlias'] }}";
    var preview_add_route = '{!! route("powerpanel.boardofdirectors.addpreview") !!}';
    var previewForm = $('#frmBoardofdirectors');
    var isDetailPage = true;
    function generate_seocontent(formname) {
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
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/boardofdirectors/boardofdirectors_validations.js' }}" type="text/javascript"></script>

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