@extends('powerpanel.layouts.app')
@section('title')
{{Config::get('Constant.SITE_NAME')}} - PowerPanel
@endsection

@section('content')
{{-- @include('powerpanel.partials.breadcrumbs') --}}

<div class="row">
    <div class="col-lg-12">
        @if(Session::has('message'))
        <div class="alert alert-success">
            {{ Session::get('message') }}
            {{-- <button type="button" class="btn-close fs-10" data-bs-dismiss="alert" aria-label="Close"></button> --}}
        </div>
        @endif
        @if(Session::has('error'))
        <div class="alert alert-danger display-hide" style="display: block;">
            {{ Session::get('error') }}
            {{-- <button type="button" class="btn-close fs-10" data-bs-dismiss="alert" aria-label="Close"></button> --}}
        </div>
        @endif
        
        <div class="card">
            <div class="card-body p-30">
                <div class="mb-4">
                    <h5 class="card-title">Create new password</h5>
                    <p class="text-muted">Your new password must be different from previous used password.</p>
                </div>
                {!! Form::open(['method'=>'post', 'id'=>'changePassword']) !!}                     
                    <div class="row mt-1">
                        <div class="col-lg-12 col-sm-12">
                            <div class="form-md-line-input cm-floating">
                                <label for="old_password" class="form-label">
                                    {{ trans('shiledcmstheme::template.forgotPwd.oldpassword') }}
                                    <span aria-required="true" class="required"> * </span>
                                </label>
                                {!! Form::password('old_password', array('autocomplete' => 'off', 'maxlength'=>20,'class' => 'form-control')) !!}
                                <span class="help-block">{{ $errors->first('old_password') }}</span>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-md-line-input cm-floating position-relative">
                                <label for="new_password" class="form-label">
                                    {{ trans('shiledcmstheme::template.forgotPwd.newpassword') }}
                                    <span aria-required="true" class="required"> * </span>
                                </label>
                                {!! Form::password('new_password', array('autocomplete' => 'off', 'maxlength'=>20,'class' => 'form-control','id'=>'newpassword')) !!}
                                <span class="help-block">{{ $errors->first('new_password') }}</span>
                                <div class="pswd_info" id="confirmpasswword_info">
                                    <h4>Password must meet the following requirements:</h4>
                                    <ul>
                                        <li id="letter" class="letterinfo invalid">At least <strong>one letter</strong></li>
                                        <li id="capital" class="capitalletterinfo invalid">At least <strong>one capital letter</strong></li>
                                        <li id="number" class="numberinfo invalid">At least <strong>one number</strong></li>
                                        <li id="length" class="lengthInfo invalid">Password should be <strong>6 to 20 characters</strong></li>
                                        <li id="special" class="specialinfo invalid">At least <strong>one special character</strong></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-md-line-input cm-floating position-relative">
                                <label for="confirm_password" class="form-label">
                                    {{ trans('shiledcmstheme::template.forgotPwd.confirmpassword') }}
                                    <span aria-required="true" class="required"> * </span>
                                </label>
                                {!! Form::password('confirm_password', array('autocomplete' => 'off', 'maxlength'=>20,'class' => 'form-control','id'=>'confirmpasswword')) !!}
                                <span class="help-block">{{ $errors->first('confirm_password') }}</span>
                                <div class="pswd_info" id="confirmpasswword_info">
                                    <h4>Password must meet the following requirements:</h4>
                                    <ul>
                                        <li id="letter" class="letterinfo invalid">At least <strong>one letter</strong></li>
                                        <li id="capital" class="capitalletterinfo invalid">At least <strong>one capital letter</strong></li>
                                        <li id="number" class="numberinfo invalid">At least <strong>one number</strong></li>
                                        <li id="length" class="lengthInfo invalid">Password should be <strong>6 to 20 characters</strong></li>
                                        <li id="special" class="specialinfo invalid">At least <strong>one special character</strong></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-sm-12">
                            <button type="submit" save-settings class="btn btn-primary bg-gradient waves-effect waves-light btn-label" value="Update Password">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="ri-key-2-line label-icon align-middle fs-20 me-2"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                       {{ trans('shiledcmstheme::template.forgotPwd.updatepassword') }}
                                    </div>
                                </div>
                            </button>
                        </div>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div><!--end row-->
@endsection
@section('scripts')
<script src="{{ Config::get('Constant.CDN_PATH').'resources/pages/scripts/packages/profile/change_password.js' }}" type="text/javascript"></script>
<script src="{{ Config::get('Constant.CDN_PATH').'resources/pages/scripts/password_rules.js' }}" type="text/javascript"></script>
@endsection