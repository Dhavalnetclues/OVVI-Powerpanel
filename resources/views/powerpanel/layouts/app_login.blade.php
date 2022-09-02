<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg">
	<head>
        <meta charset="utf-8" />
		@php
		header("Cache-Control: private, must-revalidate, max-age=0, no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
		@endphp
		<title>Login | {{Config::get('Constant.SITE_NAME')}} </title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="" name="description" />
        <meta content="" name="author" />

        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ Config::get('Constant.CDN_PATH').'resources/assets/images/favicon.ico' }}">

        <!-- Layout config Js -->
        <script src="{{ Config::get('Constant.CDN_PATH').'resources/assets/js/layout.js' }}"></script>
        <!-- Bootstrap Css -->
        <link href="{{ Config::get('Constant.CDN_PATH').'resources/assets/css/bootstrap.min.css' }}" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="{{ Config::get('Constant.CDN_PATH').'resources/assets/css/icons.min.css' }}" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="{{ Config::get('Constant.CDN_PATH').'resources/assets/css/app.min.css' }}" rel="stylesheet" type="text/css" />
        <!-- custom Css-->
        <link href="{{ Config::get('Constant.CDN_PATH').'resources/assets/css/custom.min.css' }}" rel="stylesheet" type="text/css" />
		<script>
			var CDN_PATH = "{{ Config::get('Constant.CDN_PATH') }}";
		</script>
    </head>


	<body>
        <div class="auth-page-wrapper">
            <!-- auth page bg -->
            <div class="auth-one-bg"  id="auth-particles">
                <div class="bg-overlay"></div>
                
                <div class="shape">
                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 1440 120">
                        <path d="M 0,36 C 144,53.6 432,123.2 720,124 C 1008,124.8 1296,56.8 1440,40L1440 140L0 140z"></path>
                    </svg>
                </div>
            </div>

            <!-- auth page content -->
			@yield('content')
            <!-- end auth page content -->

            <!-- footer -->
            <footer class="footer">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="footer-bottom text-left">
								<p> &COPY; {{ date('Y') }} Netclues. All Rights Reserved. </p>
                                <p>Crafted by<a href="https://netclues.com" class="d-inline-block auth-logo">
                                    <img src="{{ Config::get('Constant.CDN_PATH').'resources/images/Powerpanel-logo.gif' }}" alt="PowerPanel CMS" height="20">
                                    </a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
            <!-- end Footer -->
        </div>
        <!-- end auth-page-wrapper -->

        <!-- JAVASCRIPT -->
		<script src="{{ Config::get('Constant.CDN_PATH').'resources/global/plugins/jquery.min.js' }}" type="text/javascript"></script>
        <script src="{{ Config::get('Constant.CDN_PATH').'resources/assets/libs/bootstrap/js/bootstrap.bundle.min.js' }}"></script>
        <script src="{{ Config::get('Constant.CDN_PATH').'resources/assets/libs/simplebar/simplebar.min.js' }}"></script>
        <script src="{{ Config::get('Constant.CDN_PATH').'resources/assets/libs/node-waves/waves.min.js' }}"></script>
        <script src="{{ Config::get('Constant.CDN_PATH').'resources/assets/libs/feather-icons/feather.min.js' }}"></script>
        <script src="{{ Config::get('Constant.CDN_PATH').'resources/assets/js/pages/plugins/lord-icon-2.1.0.js' }}"></script>
        <script src="{{ Config::get('Constant.CDN_PATH').'resources/assets/js/plugins.js' }}"></script>

		<script src="{{ Config::get('Constant.CDN_PATH').'resources/global/plugins/jquery-validation/js/jquery.validate.min.js' }}" type="text/javascript"></script>
		<script src="{{ Config::get('Constant.CDN_PATH').'resources/global/plugins/jquery-validation/js/additional-methods.min.js' }}" type="text/javascript"></script>

        <!-- particles js -->
        <script src="{{ Config::get('Constant.CDN_PATH').'resources/assets/libs/particles.js/particles.js' }}"></script>
        <!-- particles app js -->
        <script src="{{ Config::get('Constant.CDN_PATH').'resources/assets/js/pages/particles.app.js' }}"></script>
        <!-- password-addon init -->
        <script src="{{ Config::get('Constant.CDN_PATH').'resources/assets/js/pages/password-addon.init.js' }}"></script>

        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
        
		<script type="text/javascript">
		setTimeout(function () {
			$('.alert-info').hide()
		}, 10000)
		setTimeout(function () {
			$('.alert-danger').hide()
		}, 10000)
		setTimeout(function () {
			$('.alert-success').hide()
		}, 10000)
		</script>
		<script type="text/javascript">
		$(window).load(function () {
			$(".login_loader").fadeOut(4000);
		});
		$(document).on('focusout', 'input', function () {
			var arr = ['password'];
			if (!jQuery.inArray($(this).attr('type'), arr) == false) {
				var ip = $.trim($(this).val());
				$(this).val('');
				$(this).val(ip);
			}
		});
		</script>
        
		@yield('scripts')
    </body>
</html>