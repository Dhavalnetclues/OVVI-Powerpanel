@extends('powerpanel.layouts.app_login')
@php
    $CDN_PATH = Config::get('Constant.CDN_PATH');
@endphp
@section('content')

<div class="auth-page-content">
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<div class="text-left login-logo">
                    <a href="{{ url('/powerpanel') }}" class="d-inline-block auth-logo" title="{{ Config::get('Constant.SITE_NAME') }}">
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
							<h5 class="text-primary">{!! trans('template.forgotPwd.forgotpassword') !!} ?</h5>
							<p class="text-muted">{!! trans('template.forgotPwd.enteremailandpassword') !!}.</p>
                            {{-- <lord-icon
                                src="https://cdn.lordicon.com/rhvddzym.json"
                                trigger="loop"
                                colors="primary:#0ab39c"
                                class="avatar-xl">
                            </lord-icon> --}}
						</div>

						<div class="Login_form">

                            <div class="alert alert-borderless alert-info mb-3" role="alert">
                                <b>{!! trans('template.forgotPwd.note') !!}:</b> {!! trans('template.forgotPwd.forgotmailsent') !!}.
                            </div>

							<form class="login-form" role="form" method="POST" action="{{ route('password.email') }}">
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
								@if(Session::has('status'))
                                  <div class="alert alert-success" role="alert">
                                    {{ Session::get('status') }}
                                    <!-- <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button> -->
                                  </div>
                                @endif

								<div class="mb-3">
                                    <div class="input-group">
                                        <div class="input-group-btn">
                                            <i class="ri-mail-open-line"></i>
                                        </div>
										<input type="email" class="form-control {{ $errors->has('email') ? ' has-error' : '' }}" name="email" placeholder="{!! trans('template.frontLogin.email') !!}" autocomplete="off">
									</div>
                                    @if ($errors->has('email'))
                                        <span class="help-block">{{ $errors->first('email') }}</span>
                                    @endif
								</div>

                                <div class="mb-3">
                                    {{-- <div class="g-recaptcha" data-sitekey="{{Config::get('Constant.GOOGLE_CAPCHA_KEY')}}"></div> --}}
									<?php $GoogleSitekey = env('RECHAPCHA_SITEKEY');?>
                                    <div class="g-recaptcha" data-sitekey="{{ $GoogleSitekey; }}"></div>
                                </div>

                                <div class="click-btn mb-3">
                                    {{-- <a href="{{ url('/powerpanel') }}" class="forgot-password-link"> Login here </a> --}}
                                    <p class="mb-0">Wait, I remember my password...  <a href="{{ url('/powerpanel') }}" class="click-link text-decoration-none"> Click here </a> </p>
								</div>

								<div class="mt-4">
                                    <button class="btn btn-success w-100" type="submit">{!! trans('template.common.submit') !!}</button>
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
<script src="{{ Config::get('Constant.CDN_PATH').'resources/pages/scripts/forgotpwd_validation.js' }}" type="text/javascript"></script>
@endsection