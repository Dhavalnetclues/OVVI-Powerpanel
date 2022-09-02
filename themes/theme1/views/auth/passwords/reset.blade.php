@extends('powerpanel.layouts.app_login')
@section('content')
<div class="login-content login_form">
	<h1>
	<div class="login_logo">
		<!-- <img src="{{ App\Helpers\resize_image::resize(Config::get('Constant.FRONT_LOGO_ID')) }}" alt="{{ Config::get('Constant.SITE_NAME') }}"> -->
		<img src="{{ Config::get('Constant.CDN_PATH').'resources/images/Powerpanel-logo.png' }}" alt="{{ Config::get('Constant.SITE_NAME') }}">
	</div>
	</h1>
	<h3 class="font-green">{!! trans('template.forgotPwd.resetpassword') !!}</h3>
	<form class="form-horizontal login-form reset-password-form" role="form" method="POST" action="{{ url('/powerpanel/password/reset') }}">
		<div class="width_set">
			<div>
				{{ csrf_field() }}
				<input type="hidden" name="token" value="{{ $token }}">
				<div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
				</div>
				<input id="email" type="email" class="form-control placeholder-no-fix form-group" name="email" placeholder="{!! trans('template.frontLogin.email') !!}" value="{{ $email ?? old('email') }}" autocomplete="off" readonly="readonly">
				@if ($errors->has('email'))
				<span class="help-block">{{ $errors->first('email') }}</span>
				@endif
			</div>
			<div class="row">
				<div class="col-sm-6 col-xs-12">
					<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
						<input id="password" type="password" class="form-control placeholder-no-fix form-group" placeholder="{!! trans('template.frontLogin.password') !!}" name="password" autocomplete="off">
						@if ($errors->has('password'))
						<span class="help-block">{{ $errors->first('password') }}</span>
						@endif
						<div class="pswd_info" id="password_info">
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
				<div class="col-sm-6 col-xs-12">
					<div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
						<input id="password-confirm" type="password" class="form-control placeholder-no-fix form-group" placeholder="{!! trans('template.forgotPwd.confirmpassword') !!}" name="password_confirmation" autocomplete="off">
						@if ($errors->has('password_confirmation'))
						<span class="help-block">{{ $errors->first('password_confirmation') }}</span>
						@endif
						<div class="pswd_info" id="password-confirm_info">
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
			</div>
			<div class="row">
				<div class="col-sm-12 text-right">
					<div class="forgot-password">
						<button type="submit" class="btn blue">
						{!! trans('template.forgotPwd.resetpassword') !!}
						</button>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>
@endsection
@section('scripts')
<script src="{{ $CDN_PATH.'resources/pages/scripts/resetpassword_validation.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/password_rules.js' }}" type="text/javascript"></script>
@endsection