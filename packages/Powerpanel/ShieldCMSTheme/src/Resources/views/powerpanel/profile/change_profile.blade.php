@section('css')
<link href="{{ $CDN_PATH.'resources/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css' }}" rel="stylesheet" type="text/css" />
<link href="{{ $CDN_PATH.'resources/global/plugins/select2/css/select2.min.css' }}" rel="stylesheet" type="text/css"/>
<link href="{{ $CDN_PATH.'resources/global/plugins/select2/css/select2-bootstrap.min.css' }}" rel="stylesheet" type="text/css"/>
@endsection
@extends('powerpanel.layouts.app')
@section('title')
{{Config::get('Constant.SITE_NAME')}} - PowerPanel
@endsection

@section('content')
{{-- @include('powerpanel.partials.breadcrumbs') --}}

<div class="position-relative mx-n4 mt-n4">
    <div class="profile-wid-bg profile-setting-img">
        <img src="{{ Config::get('Constant.CDN_PATH').'resources/assets/images/profile-bg.jpg' }}" class="profile-wid-img" alt="">
    </div>
</div>

{!! Form::open(['method' => 'post','id'=>'changeProfile']) !!}
<div class="row">

    <div class="col-xxl-3">
        <div class="card mt-n5">
            <div class="card-body p-4">
                <div class="text-center">


                    <div class="profile-user position-relative d-inline-block mx-auto  mb-4 user_photo_img" data-trigger="fileinput">

                        @if(old('image_url'))
                            <img src="{{ old('image_url') }}" class="rounded-circle avatar-xl img-thumbnail user-profile-image"/>
                        @elseif(isset($user_data->fkIntImgId))
                            <img src="{!! App\Helpers\resize_image::resize($user_data->fkIntImgId,120,120) !!}" class="rounded-circle avatar-xl img-thumbnail user-profile-image"/>
                        @else
                            <img src="{{ $CDN_PATH.'/resources/images/man.png' }}" class="rounded-circle avatar-xl img-thumbnail user-profile-image"/>
                        @endif

                        <div class="avatar-xs p-0 rounded-circle profile-photo-edit">

                            <a class="profile-photo-edit avatar-xs media_manager" data-multiple="false" onclick="MediaManager.open('user_photo');">
                                <span class="avatar-title rounded-circle bg-light text-body">
                                    <i class="ri-camera-fill"></i>
                                </span>
                            </a>

                            <input class="form-control" type="hidden" id="user_photo" name="img_id" value="{{ isset($user_data->fkIntImgId)?$user_data->fkIntImgId:old('img_id') }}" />

                            @if(method_exists($MyLibrary, 'GetFolderID'))
                                @if(isset($user_data->fkIntImgId))
                                    @php $folderid = App\Helpers\MyLibrary::GetFolderID($user_data->fkIntImgId); @endphp
                                    @if(isset($folderid->fk_folder) && $folderid->fk_folder != '0')
                                        <input class="form-control" type="hidden" id="folder_id" name="folder_id" value="{{ $folderid->fk_folder }}" />
                                    @endif
                                @endif
                            @endif

                            <input class="form-control" type="hidden" id="image_url" name="image_url" value="{{ old('image_url') }}" />
                        </div>
                        <span class="help-block"> {{ $errors->first('img_id') }} </span>

                    </div>


                    <h5 class="fs-16 mb-1">{{ $user_data->name }}</h5>
                    <p class="text-muted mb-0">{{ $user_data->email }}</p>
                </div>
            </div>
        </div>
        <!--end card-->
    </div>

    <!--end col-->
    <div class="col-xxl-9">
        <div class="card mt-xxl-n5">
            <div class="card-header">
                <ul class="nav nav-tabs-custom rounded card-header-tabs border-bottom-0" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active fs-15" data-bs-toggle="tab" href="#myProfile" role="tab">Personal Details</a>
                    </li>
                </ul>
            </div>
            <div class="card-body p-4">
                <div class="tab-content">
                    <div class="tab-pane active" id="myProfile" role="tabpanel">
                        @if(Session::has('message'))
                        <div class="row">
                            <div class="alert alert-success">
                                {{ Session::get('message') }}
                                {{-- <button type="button" class="btn-close fs-10" data-bs-dismiss="alert" aria-label="Close"></button> --}}
                            </div>
                        </div>
                        @endif
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-md-line-input cm-floating">
                                        <label for="name" class="form-label">{{ trans('shiledcmstheme::template.common.name') }} <span aria-required="true" class="required"> * </span></label>
                                        {!! Form::text('name',$user_data->name,array('class' => 'form-control', 'maxlength'=>'150','id' => 'name','autocomplete'=>'off','placeholder'=> trans('shiledcmstheme::template.common.name'))) !!}
                                        <span class="help-block">{{ $errors->first('name') }}</span>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-md-line-input cm-floating">
                                        <label for="email" class="form-label">{{ trans('shiledcmstheme::template.common.email') }}<span aria-required="true" class="required"> * </span></label>
                                        {!! Form::text('email',$user_data->email,array('class' => 'form-control', 'maxlength'=>'100','id' => 'email','autocomplete'=>'off','placeholder'=> trans('shiledcmstheme::template.common.email'))) !!}
                                        <span class="help-block">{{ $errors->first('email') }}</span>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-md-line-input cm-floating">
                                        <label class="form-label" for="personalId">Personal Email <span aria-required="true" class="required"> * </span></label>
                                        {!! Form::text('personalId',$user_data->personalId,array('class' => 'form-control', 'maxlength'=>'100','id' => 'personalId','autocomplete'=>'off','placeholder'=>'Personal Email')) !!}
                                        <span class="help-block">{{ $errors->first('personalId') }}</span>
                                    </div>
                                </div>
                                <div class="form-note"><b>Note:</b> Forgot password email will receive on personal email id.</div>

                                <div class="col-lg-12">
                                    <div class="hstack gap-2">
                                        <button type="submit" class="btn btn-primary bg-gradient waves-effect waves-light btn-label">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0">
                                                    <i class="ri-save-line label-icon align-middle fs-20 me-2"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                   {{  trans('shiledcmstheme::template.myProfile.updateprofile') }}
                                                </div>
                                            </div>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        {!! Form::close() !!}
                    </div>
                    <!--end tab-pane-->
                </div>
            </div>
        </div>
    </div><!--end col-->
</div>
<!--end row-->
@endsection
@section('scripts')
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/profile/change_profile.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/select2/js/select2.full.min.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/select2/js/components-select2.min.js' }}" type="text/javascript"></script>
@endsection