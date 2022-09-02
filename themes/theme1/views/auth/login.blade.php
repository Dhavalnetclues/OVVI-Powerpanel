@extends('powerpanel.layouts.app_login')
@section('content')
<div class="login-content login_form"> 
	<h1>
		<div class="login_logo">
			<!-- <img src="{{ App\Helpers\resize_image::resize(Config::get('Constant.FRONT_LOGO_ID')) }}" alt="{{ Config::get('Constant.SITE_NAME') }}"> -->
			<img src="{{ Config::get('Constant.CDN_PATH').'resources/images/Powerpanel-logo.png' }}" alt="{{ Config::get('Constant.SITE_NAME') }}">
		</div>
	</h1>
	<p class="content_center">
		 {!! trans('template.frontLogin.frontcmspp') !!}
	</p>
	<form class="form-horizontal login-form" role="form" method="POST" action="{{ url('/powerpanel/login') }}">
		<input type="password" style="width: 0;height: 0; visibility: hidden;position:absolute;left:0;top:0;"/>
		<div class="width_set">
			@if(Session::has('message'))
				<div class="alert alert-info fade in">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
					{{ Session::get('message') }}
				</div>
			@endif
			@if(isset($expiredToken))
				<div class="alert alert-danger fade in">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
					{{ $expiredToken }}
				</div>
			@endif
			{!! csrf_field() !!}
			@if (session('error'))
				<div class="alert alert-danger fade in">
					{{ session('error') }}
				</div>
			@endif
			<div class="row">
				<div class="col-sm-6 col-xs-12 form-group{{ $errors->has('email') ? ' has-error' : '' }}" >
					@if(Cookie::get('cookie_login_email'))
						<input type="email" class="form-control form-control-solid placeholder-no-fix form-group" name="email" value="{{Cookie::get('cookie_login_email')}}" placeholder="{!! trans('template.frontLogin.email') !!}" autocomplete="off">
					@else
						<input type="email" class="form-control form-control-solid placeholder-no-fix form-group" name="email" value="{{ old('email') }}" placeholder="{!! trans('template.frontLogin.email') !!}" autocomplete="off">
					@endif
					@if ($errors->has('email'))
						<span class="help-block">
							{{ $errors->first('email') }}
						</span>
					@endif
				</div>
				<div class="col-sm-6 col-xs-12 form-group {{ $errors->has('password') ? ' has-error' : '' }}">
					@if(Cookie::get('cookie_login_password'))
						<input type="password" class="form-control form-control-solid placeholder-no-fix form-group" name="password" value="{{Cookie::get('cookie_login_password')}}" placeholder="{!! trans('template.frontLogin.password') !!}"  autocomplete="off">
					@else
						<input type="password" class="form-control form-control-solid placeholder-no-fix form-group" name="password" placeholder="{!! trans('template.frontLogin.password') !!}"  autocomplete="off">
					@endif
					@if ($errors->has('password'))
						<span class="help-block">
							{{ $errors->first('password') }}
						</span>
					@endif
				</div>
			</div>
			<div class="row">
				<div class="col-sm-5 col-xs-6">
					<div class="rem-password">
						<p>
							<label for="remember_me">
							@if(Cookie::get('cookie_login_password') && Cookie::get('cookie_login_email'))
								<input type="checkbox" class="rem-checkbox" id="remember_me" name="remember" checked/>
							@else
								<input type="checkbox" class="rem-checkbox" id="remember_me" name="remember"/>
							@endif
							{!! trans('template.frontLogin.rememberme') !!}</label>
						</p>
					</div>
				</div>
				<div class="col-sm-7 col-xs-6 text-right">
					<div class="forgot-password">
						<a class="forget_link btn-link" href="{{ url('/powerpanel/password/reset') }}">{!! trans('template.frontLogin.forgotpasswordques') !!}</a>
					</div>
					<button class="btn blue browser_show" type="submit">{!! trans('template.frontLogin.signin') !!} </button>
				</div>
				<div class="col-xs-12 mobile_show">
					<button class="btn blue" type="submit">{!! trans('template.frontLogin.signin') !!} </button>
				</div>
			</div>
		</div>
	</form>
</div>

@endsection
@section('scripts')
<script src="{{ Config::get('Constant.CDN_PATH').'/resources/pages/scripts/login-5.js' }}" type="text/javascript"></script>
@endsection