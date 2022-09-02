<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<html lang="en">
	<head>
		<meta charset="utf-8" />
		@php
		header("Cache-Control: private, must-revalidate, max-age=0, no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
		@endphp
		<title>Login | {{Config::get('Constant.SITE_NAME')}} </title>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta content="width=device-width, initial-scale=1" name="viewport" />
		<meta content="" name="description" />
		<meta content="" name="author" />
		<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
		<link href="{{ Config::get('Constant.CDN_PATH').'resources/global/plugins/font-awesome/css/font-awesome.min.css' }}" rel="stylesheet" type="text/css" />
		<link href="{{ Config::get('Constant.CDN_PATH').'resources/global/plugins/simple-line-icons/simple-line-icons.min.css' }}" rel="stylesheet" type="text/css" />
		<link href="{{ Config::get('Constant.CDN_PATH').'resources/global/plugins/bootstrap/css/bootstrap.min.css' }}" rel="stylesheet" type="text/css" />
		<link href="{{ Config::get('Constant.CDN_PATH').'resources/global/plugins/uniform/css/uniform.default.css' }}" rel="stylesheet" type="text/css" />
		<link href="{{ Config::get('Constant.CDN_PATH').'resources/global/css/components-md.min.css' }}" rel="stylesheet" id="style_components" type="text/css" />
		<link href="{{ Config::get('Constant.CDN_PATH').'resources/global/css/plugins-md.min.css' }}" rel="stylesheet" type="text/css" />
		<link href="{{ Config::get('Constant.CDN_PATH').'resources/pages/css/login-5.min.css' }}" rel="stylesheet" type="text/css" />
		<link rel="shortcut icon" type="image/x-icon" href="{{Config::get('Constant.CDN_PATH').'assets/images/favicon.ico' }}" />
		<link rel="apple-touch-icon" sizes="144x144" href=" {{ Config::get('Constant.CDN_PATH').'assets/images/favicon.ico' }}" />
		<link rel="apple-touch-icon" sizes="114x114" href="{{ Config::get('Constant.CDN_PATH').'assets/images/favicon.ico' }}" />
		<link rel="apple-touch-icon" sizes="72x72" href="{{ Config::get('Constant.CDN_PATH').'assets/images/favicon.ico' }}" />
		<link rel="apple-touch-icon" sizes="57x57" href="{{ Config::get('Constant.CDN_PATH').'assets/images/favicon.ico' }}" />
                <script>
                    var CDN_PATH = "{{ Config::get('Constant.CDN_PATH') }}";
                </script>
	</head>
	<body class=" login">
		<div class="user-login-5">
			<div class="row bs-reset">
				<div class="col-md-6 bs-reset left_loginbg">
					<div class="login-bg" style="background-image:url({{ Config::get('Constant.CDN_PATH').'resources/images/left_loginbg.png' }})">
					</div>
				</div>
				<div class="col-md-6 login-container bs-reset">
					@yield('content')
					<div class="login-footer">
						<div class="row bs-reset">
							<div class="col-sm-8 col-xs-12 bs-reset">
								<div class="login-social">
									<!--<p>{!! Config::get('Constant.FOOTER_COPYRIGHTS') !!} {{ date('Y') }} {{Config::get('Constant.SITE_NAME')}}. {{ trans('template.frontLogin.allrightreserved') }}.</p>-->
									<p>Copyright &COPY; {{ date('Y') }} Netclues. All Rights Reserved.</p>
								</div>
							</div>
							<div class="col-sm-4 col-xs-12 bs-reset text-right">
								<div class="login-copyright" style="margin:0">
									<p>{{ trans('template.frontLogin.developedby') }}: <a href="https://www.netclues.com" target="_blank" rel="nofollow" title="Netclues"><span class="netclues_logo"></span></a></p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			@if(Request::segment(2) == "login")
			<div class="row login_loader">
				<div class="col-xs-12">
					<div class="net_logo">
						<div class="vertical_middle">
							<img src="{{ Config::get('Constant.CDN_PATH').'resources/images/netclues_login_logo.png' }}">
						</div>
					</div>
				</div>
			</div>
			@endif
		</div>
		<!--[if lt IE 9]>
		<script src="../resources/global/plugins/respond.min.js"></script>
		<script src="../resources/global/plugins/excanvas.min.js"></script>
		<![endif]-->
		<script src="{{ Config::get('Constant.CDN_PATH').'resources/global/plugins/jquery.min.js' }}" type="text/javascript"></script>
		<script src="{{ Config::get('Constant.CDN_PATH').'resources/global/plugins/bootstrap/js/bootstrap.min.js' }}" type="text/javascript"></script>
		<script src="{{ Config::get('Constant.CDN_PATH').'resources/global/plugins/js.cookie.min.js' }}" type="text/javascript"></script>
		<script src="{{ Config::get('Constant.CDN_PATH').'resources/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js' }}" type="text/javascript"></script>
		<script src="{{ Config::get('Constant.CDN_PATH').'resources/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js' }}" type="text/javascript"></script>
		<script src="{{ Config::get('Constant.CDN_PATH').'resources/global/plugins/jquery.blockui.min.js' }}" type="text/javascript"></script>
		<script src="{{ Config::get('Constant.CDN_PATH').'resources/global/plugins/uniform/jquery.uniform.min.js' }}" type="text/javascript"></script>
		<script src="{{ Config::get('Constant.CDN_PATH').'resources/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js' }}" type="text/javascript"></script>
		<script src="{{ Config::get('Constant.CDN_PATH').'resources/global/plugins/jquery-validation/js/jquery.validate.min.js' }}" type="text/javascript"></script>
		<script src="{{ Config::get('Constant.CDN_PATH').'resources/global/plugins/jquery-validation/js/additional-methods.min.js' }}" type="text/javascript"></script>
		<script src="{{ Config::get('Constant.CDN_PATH').'resources/global/plugins/select2/js/select2.full.min.js' }}" type="text/javascript"></script>
		<script src="{{ Config::get('Constant.CDN_PATH').'resources/global/plugins/backstretch/jquery.backstretch.min.js' }}" type="text/javascript"></script>
		<script src="{{ Config::get('Constant.CDN_PATH').'resources/global/scripts/app.min.js' }}" type="text/javascript"></script>
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