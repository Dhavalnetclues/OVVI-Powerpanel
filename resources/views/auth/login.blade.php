@extends('powerpanel.layouts.app_login')
@section('content')

<div class="auth-page-content">
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<div class="text-left login-logo" title="{{ Config::get('Constant.SITE_NAME') }}">
					<a href="{{ url('/powerpanel') }}" class="d-inline-block auth-logo">
						<img src="{{ Config::get('Constant.CDN_PATH').'resources/images/Powerpanel-logo.png' }}" alt="{{ Config::get('Constant.SITE_NAME') }}">
					</a>
				</div>
			</div>
		</div>
		<!-- end row -->

		<div class="row">
			<div class="col-md-8 col-lg-6 col-xl-6">
				<div class="card mt-0 mt-md-4">
					<div class="card-body">
						<div class="text-left mt-2">
							<h5 class="text-primary">Welcome Back!</h5>
							<p class="text-muted">Using the unparalleled power & flexibility of an enterprise-level Content Management System (CMS), build a blog, a full website, or a shop with ease.<!-- Sign in to continue to Netclues. --></p>
						</div>

						<div class="Login_form">
							<form class="login-form" role="form" method="POST" action="{{ url('/powerpanel/login') }}">
								@if(Session::has('message'))
									<div class="alert alert-info fade in">
										<!-- <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button> -->
										{{ Session::get('message') }}
									</div>
								@endif
								@if(isset($expiredToken))
									<div class="alert alert-danger fade in">
										<!-- <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button> -->
										{{ $expiredToken }}
									</div>
								@endif
								{!! csrf_field() !!}
								@if (session('error'))
									<div class="alert alert-danger fade in">
										{{ session('error') }}
									</div>
								@endif
								<div class="mb-3">
									<!-- <label for="email" class="form-label">Email</label> -->
									@if(Cookie::get('cookie_login_email'))
										<input type="email" class="form-control" name="email" value="{{Cookie::get('cookie_login_email')}}" placeholder="{!! trans('template.frontLogin.email') !!}" autocomplete="off">
									@else
									<div class="input-group">
                                        <div class="input-group-btn">
                                            <i class="ri-mail-open-line"></i>
                                        </div>
										<input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="{!! trans('template.frontLogin.email') !!}" autocomplete="off">
									</div>
									@endif
									@if ($errors->has('email'))
										<span class="help-block">
											{{ $errors->first('email') }}
										</span>
									@endif
								</div>

								<div class="mb-3">
									<div class="float-end d-none">
										<a class="text-muted" href="{{ url('/powerpanel/password/reset') }}">{!! trans('template.frontLogin.forgotpasswordques') !!}</a>
									</div>
									<!-- <label class="form-label" for="password-input">Password</label> -->
									<div class="position-relative auth-pass-inputgroup mb-3">
										@if(Cookie::get('cookie_login_password'))
											<input type="password" class="form-control pe-5" name="password" value="{{Cookie::get('cookie_login_password')}}" placeholder="{!! trans('template.frontLogin.password') !!}"  autocomplete="off">
										@else
										<div class="input-group">
	                                        <div class="input-group-btn">
	                                            <i class="ri-key-line"></i>
	                                        </div>
											<input type="password" class="form-control pe-5" name="password" placeholder="{!! trans('template.frontLogin.password') !!}"  autocomplete="off">
										</div>
										@endif
										@if ($errors->has('password'))
											<span class="help-block">
												{{ $errors->first('password') }}
											</span>
										@endif
									</div>
								</div>

								<div class="form-check d-flex justify-content-between">
									<div class="remember_me">
									@if(Cookie::get('cookie_login_password') && Cookie::get('cookie_login_email'))
										<input type="checkbox" class="form-check-input rem-checkbox" id="remember_me" name="remember" checked/>
									@else
										<input type="checkbox" class="form-check-input rem-checkbox" id="remember_me" name="remember"/>
									@endif
									<label class="form-check-label" for="remember_me">{!! trans('template.frontLogin.rememberme') !!}</label></div>

									<a href="{{ url('/powerpanel/password/reset') }}" class="forgot-password-link" target="_blank">Forgot Password or Email?</a>
								</div>

								<div class="mt-4">
									<button class="btn btn-success w-100" type="submit">{!! trans('template.frontLogin.signin') !!}</button>
								</div>
							</form>
						</div>
					</div>
					<!-- end card body -->
				</div>
				<!-- end card -->

				<div class="mt-0 mt-xl-4 text-center">
					<p class="mb-0 d-none">{{ trans('template.frontLogin.developedby') }}:
						<a href="https://www.netclues.com" target="_blank" rel="nofollow" title="Netclues" class="fw-semibold text-primary text-decoration-underline">
							<img src="{{ Config::get('Constant.CDN_PATH').'resources/images/Powerpanel-logo.gif' }}" alt="{{ Config::get('Constant.SITE_NAME') }}" height="20">
						</a>
					</p>
				</div>

			</div>
		</div>
		<!-- end row -->
	</div>
	<!-- end container -->
</div>

@endsection
@section('scripts')
<script src="{{ Config::get('Constant.CDN_PATH').'resources/pages/scripts/login-5.js' }}" type="text/javascript"></script>
@endsection