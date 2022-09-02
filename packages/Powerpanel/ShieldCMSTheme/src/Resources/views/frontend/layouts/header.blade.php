@php
$CDN_PATH = Config::get('Constant.CDN_PATH');
$requestedFullUrl = Request::Url();
$homePageUrl = url('/');
$seg = request()->segments();
@endphp
<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=big5">
        <meta name="robots" content="nofollow">
        <meta name="googlebot" content="noindex">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=0" />
<!--        <title>{!! str_replace('&amp;', '&',$META_TITLE) !!}</title>
        <meta name="title" content="{!! str_replace('&amp;', '&',$META_TITLE) !!}">
        <meta name="keywords" content="{!! str_replace('&amp;', '&',$META_KEYWORD) !!}">
        <meta name="description" content="{!! str_replace('&amp;', '&',$META_DESCRIPTION) !!}">-->
        <title>{{ Config::get('Constant.SITE_NAME') }}</title>
        <meta name="title" content="{{ Config::get('Constant.SITE_NAME') }}">
        <meta name="keywords" content="{{ Config::get('Constant.SITE_NAME') }}">
        <meta name="description" content="{{ Config::get('Constant.SITE_NAME') }}">
        <!-- <meta name="author" content="" /> -->
        <meta property="og:url" content="{{ Request::Url() }}" />
        <meta property="og:type" content="website" />
        <meta property="og:title" content="{!! str_replace('&amp;', '&',$META_TITLE) !!}" />
        <meta property="og:description" content="{!! str_replace('&amp;', '&',$META_DESCRIPTION) !!}" />
        <meta property="og:image" content="{{ $CDN_PATH.'assets/images/sharelogo.png' }}" />
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" content="{!! str_replace('&amp;', '&',$META_TITLE) !!}" />
        <meta name="twitter:url" content="{{ Request::Url() }}" />
        <meta name="twitter:description" content="{!! str_replace('&amp;', '&',$META_DESCRIPTION) !!}" />
        <meta name="twitter:image" content="{{ $CDN_PATH.'assets/images/sharelogo.png' }}" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="icon" href="{{ $CDN_PATH.'assets/images/favicon.ico' }}" type="image/x-icon" />
        <link rel="canonical" href="{{ Request::Url() }}" />
        <link rel="stylesheet" href="{{ $CDN_PATH.'assets/css/main.css' }}" media="all" />
        <!-- Favicon Icon S -->
        <link rel="icon" href="assets/images/favicon.ico" type="image/x-icon" />
        <link rel="apple-touch-icon" sizes="144x144" href="{{ $CDN_PATH.'assets/images/apple-touch-icon-144.png' }}" />
        <link rel="apple-touch-icon" sizes="114x114" href="{{ $CDN_PATH.'assets/images/apple-touch-icon-114.png' }}" />
        <link rel="apple-touch-icon" sizes="72x72" href="{{ $CDN_PATH.'assets/images/apple-touch-icon-72.png' }}" />
        <link rel="apple-touch-icon" sizes="57x57" href="{{ $CDN_PATH.'assets/images/apple-touch-icon-57.png' }}" />
        <!-- Favicon Icon E -->
        <!-- Java Script S -->
        <script type="text/javascript" src="{{ $CDN_PATH.'assets/js/jquery.min.js' }}"></script>
        <!-- Java Script E -->
        <!-- Fonts S -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600,700,800&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Libre+Franklin:300,400,500,600,700,800,900&display=swap" rel="stylesheet">
        <script src='https://www.google.com/recaptcha/api.js'></script>
        <!-- Fonts E -->
        <!--[if IE 8]>     <html class="ie8"> <![endif]-->
        <script type="text/javascript">
            var site_url = "{{ url('/') }}";
            var deviceType = "{{ Config::get('Constant.DEVICE') }}";
            var segments = {!! json_encode($seg) !!};
            var CDN_PATH = "{{ $CDN_PATH }}";</script>
            <script type="text/javascript">
                $.ajaxSetup({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
                });
        </script>
        <style>
            .form_nxt_prev ul {
                margin: 0;
                padding: 0px 0 20px 0;
                left: auto;
                text-align: center;
            }
            .form_nxt_prev ul li {
                list-style: none;
                margin:0 5px;
                padding: 7px 0px;
                background: #1c4da0;
                display: inline-block;
                color: #fff;
                border-radius: 20px;
                font-weight: 600;
                letter-spacing: 1px;
                min-width: 125px;
                text-align: center;
            }</style>
    </head>
    <!-- Body S -->
    <body>
        <!-- Loader S -->
        <div class="loader" style="display: none;">
            <div class="loader_inner"></div>
        </div>
        <!-- Loader E -->
        <!-- Browser Upgrade S -->
        <div id="buorg" class="buorg">
            <div class="buorg__text"><i class="ri-alert-fill"></i> For a better view on
                {{ Config::get("Constant.SITE_NAME") }}, <a href="https://support.microsoft.com/en-us/help/17621/internet-explorer-downloads" title="Update Your Browser" target="_blank">Update Your Browser.</a></div>
        </div>
        <!-- Browser Upgrade E -->
        <!-- Scroll To Top S -->
        <div id="back-top" title="Scroll To Top" style="display: none;">
            <i class="fa fa-angle-up"></i>
        </div>
        <!-- Scroll To Top E -->
        <!-- Live Chat S -->
        <!--<div class="live_chat">
            <a href="#" title="Live Chat"><i class="ri-chat-1-line-o"></i><span>Live Chat</span></a>
        </div> -->
        <!-- Live Chat E -->
        <!-- Main Wrapper S -->
        <div id="wrapper">