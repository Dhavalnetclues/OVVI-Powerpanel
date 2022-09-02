@extends('powerpanel.layouts.app_login')
@php
    $CDN_PATH = Config::get('Constant.CDN_PATH');
@endphp
<!-- Main Content -->
@section('content')
<div class="login-content">
	<h1>
    <div class="login_logo">
      <!-- <img src="{{ App\Helpers\resize_image::resize(Config::get('Constant.FRONT_LOGO_ID')) }}" alt="{{ Config::get('Constant.SITE_NAME') }}"> -->
      <img src="{{ Config::get('Constant.CDN_PATH').'resources/images/Powerpanel-logo.png' }}" alt="{{ Config::get('Constant.SITE_NAME') }}">
    </div>
  </h1>
	<h3 class="font-green">{!! trans('template.forgotPwd.forgotpassword') !!} ?</h3>
	<p class="content_center"> {!! trans('template.forgotPwd.enteremailandpassword') !!}. </p>
	<form class="form-horizontal login-form forgotpwd" role="form" method="POST" action="{{ url('/powerpanel/password/email') }}">
  <div class="width_set"> 
		{{ csrf_field() }}
		@if(Session::has('status'))
      <div class="alert alert-success">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
        {{ Session::get('status') }}
      </div>
    @endif
		<div class="form-group">
		  <input class="form-control placeholder-no-fix form-group {{ $errors->has('email') ? ' has-error' : '' }}" type="text" placeholder="{!! trans('template.frontLogin.email') !!}" name="email" autocomplete="off"> 
      @if ($errors->has('email'))
          <span class="help-block">{{ $errors->first('email') }}</span>
      @endif
		</div>
    <div class="login-content">
      <p class="content_center">{!! trans('template.forgotPwd.note') !!}: {!! trans('template.forgotPwd.forgotmailsent') !!}.</p>
    </div>
    <div class="row">
      <div class="col-sm-12 text-right">
        <div class="forgot-password">
          <button type="submit" class="btn blue">
            {!! trans('template.common.submit') !!}
          </button>
        </div>
        <a class="btn blue mobile_full" href="{{ url('/powerpanel') }}">{!! trans('template.forgotPwd.login') !!}</a>
      </div>
    </div>
    </div>
  </form>
</div>
@endsection

@section('scripts')
<script src="{{ $CDN_PATH.'resources/pages/scripts/forgotpwd_validation.js' }}" type="text/javascript"></script>
@endsection