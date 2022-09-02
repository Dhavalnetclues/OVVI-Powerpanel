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
                            <h5 class="text-primary">{!! trans('template.forgotPwd.resetpassword') !!}</h5>
                            <p class="text-muted">{!! trans('template.forgotPwd.enteremailandpassword') !!}.</p>

                            <!-- <lord-icon
                                src="https://cdn.lordicon.com/rhvddzym.json"
                                trigger="loop"
                                colors="primary:#0ab39c"
                                class="avatar-xl">
                            </lord-icon> -->
                        </div>

                        <div class="Login_form">

                            <div class="alert alert-borderless alert-info mb-3" role="alert">
                                <b>{!! trans('template.forgotPwd.note') !!}:</b> {!! trans('template.forgotPwd.forgotmailsent') !!}.
                            </div>

                            <form class="login-form" role="form" method="POST" action="{{ route('password.reset.post') }}">
                                {{ csrf_field() }}
                                <input type="hidden" name="token" value="{{ $token }}">
                                @if(Session::has('status'))
                                  <div class="alert alert-success" role="alert">
                                    {{ Session::get('status') }}
                                    <!-- <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button> -->
                                  </div>
                                @endif

                                <div class="mb-4">
                                     <div class="input-group">
                                        <div class="input-group-btn">
                                            <i class="ri-mail-open-line"></i>
                                        </div>
                                        <input type="email" name="email" class="form-control {{ $errors->has('email') ? ' has-error' : '' }}" placeholder="{!! trans('template.frontLogin.email') !!}" value="{{ $email ?? old('email') }}"  autocomplete="off" readonly="readonly">
                                    </div>
                                    @if ($errors->has('email'))
                                        <span class="help-block">{{ $errors->first('email') }}</span>
                                    @endif
                                </div>

								<div class="mb-4 position-relative">
                                    <div class="input-group">
                                        <div class="input-group-btn">
                                            <i class="ri-key-line"></i>
                                        </div>
									   <input id="password" type="password" class="form-control" placeholder="{!! trans('template.frontLogin.password') !!}" name="password" autocomplete="off">
                                    </div>
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

								<div class="mb-4 position-relative">
									<!-- <label class="form-label">{!! trans('template.forgotPwd.confirmpassword') !!}</label> -->
                                    <div class="input-group">
                                        <div class="input-group-btn">
                                            <i class="ri-key-line"></i>
                                        </div>
									   <input id="password-confirm" type="password" class="form-control" placeholder="{!! trans('template.forgotPwd.confirmpassword') !!}" name="password_confirmation" autocomplete="off">
                                    </div>
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

                                <div class="text-center mt-4">
                                    <button class="btn btn-success w-100" type="submit">{!! trans('template.forgotPwd.resetpassword') !!}</button>
                                </div>
                            </form><!-- end form -->
                        </div>
                    </div>
                    <!-- end card body -->
                </div>
                <!-- end card -->

                <div class="mt-4 text-center d-none">
					<p class="mb-0">{{ trans('template.frontLogin.developedby') }}:
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
<script src="{{ Config::get('Constant.CDN_PATH').'resources/pages/scripts/resetpassword_validation.js' }}" type="text/javascript"></script>
<script src="{{ Config::get('Constant.CDN_PATH').'resources/pages/scripts/password_rules.js' }}" type="text/javascript"></script>
@endsection