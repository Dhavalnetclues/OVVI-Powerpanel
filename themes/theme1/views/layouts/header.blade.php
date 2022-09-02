@php
    $requestedFullUrl = Request::Url();
    $homePageUrl = url('/');
    $seg = request()->segments();
@endphp

<!DOCTYPE html>
<html lang="en-US">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=1" />
    <meta name="theme-color" content="#26a69a" />
    <title>{!! str_replace('&amp;', '&',$META_TITLE) !!}</title>
    <meta name="title" content="{!! str_replace('&amp;', '&',$META_TITLE) !!}">
    <meta name="description" content="{!! str_replace('&amp;', '&',$META_DESCRIPTION) !!}">
    <meta name="keywords" content="{!! str_replace('&amp;', '&',$META_KEYWORD) !!}">
    <meta name="author" content="{{ Config::get("Constant.SITE_NAME") }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Style Sheet S -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ $CDN_PATH.'assets/css/loader.css' }}{{ $versioning }}" media="all" />
    <link rel="preload" href="{{ $CDN_PATH.'assets/css/main.css' }}{{ $versioning }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="{{ $CDN_PATH.'assets/css/main.css' }}{{ $versioning }}"></noscript>
    <!-- Style Sheet E -->
    <!-- OG Meta S -->
    @php
        if(isset($ogImage) && !empty($ogImage)) {
            $og_Image = $ogImage;
        } else {
            $og_Image =  $CDN_PATH.'assets/images/logoshare.png';
        }
    @endphp
    <meta property="og:url" content="{{ Request::Url() }}" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="{!! str_replace('&amp;', '&',$META_TITLE) !!}" />
    <meta property="og:description" content="{!! str_replace('&amp;', '&',$META_DESCRIPTION) !!}" />
    <meta property="og:image" content="{{ $og_Image }}" />
    <!-- OG Meta E -->
    <!-- Twitter Meta S -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="{!! str_replace('&amp;', '&',$META_TITLE) !!}" />
    <meta name="twitter:url" content="{{ Request::Url() }}" />
    <meta name="twitter:description" content="{!! str_replace('&amp;', '&',$META_DESCRIPTION) !!}" />
    <meta name="twitter:image" content="{{ $og_Image }}" />
    <!-- Twitter Meta E -->
    <!-- Favicon Icon S -->
    <link rel="icon" href="{{ $CDN_PATH.'assets/images/favicon.ico' }}" type="image/x-icon" />
    <link rel="apple-touch-icon" sizes="144x144" href="{{ $CDN_PATH.'assets/images/apple-touch-icon-144.png' }}" />
    <link rel="apple-touch-icon" sizes="114x114" href="{{ $CDN_PATH.'assets/images/apple-touch-icon-114.png' }}" />
    <link rel="apple-touch-icon" sizes="72x72" href="{{ $CDN_PATH.'assets/images/apple-touch-icon-72.png' }}" />
    <link rel="apple-touch-icon" sizes="57x57" href="{{ $CDN_PATH.'assets/images/apple-touch-icon-57.png' }}" />
    <!-- Favicon Icon E -->
    <!-- Canonical Link S -->
    <link rel="canonical" href="{{ Request::Url() }}" />
    <!-- Canonical Link E -->
    <!-- Java Script S -->
    <script src="{{ $CDN_PATH.'assets/js/jquery-3.5.1.min.js' }}{{ $versioning }}"></script>
    <script src="{{ $CDN_PATH.'assets/libraries/cookies/js/js.cookie.min.js' }}{{ $versioning }}"></script>
    <!-- Java Script E -->
    <script>
        var site_url = "{{ url('/') }}";
        var deviceType = "{{ Config::get('Constant.DEVICE') }}";
        var segments = {!! json_encode($seg) !!};
        var CDN_PATH = "{{ $CDN_PATH }}";
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        }); 
    </script>
</head>

<body>
    <div class="ac-loader">
        <div class="ac-loader__container">
            <div class="text-center ac-loader-text"></div>
        </div>
    </div>

    <!-- <div class="page-loader">
        <div class="-title">Online Payment is proceeding</div>
        <div class="-stitle">Please Note: Do not close the browser tab or browser.</div>
    </div> -->
    <!-- Browser Upgrade S -->
    <div id="buorg" class="buorg">
        <div class="buorg__text"><i class="ri-alert-fill"></i> For a better view on
            {{ Config::get("Constant.SITE_NAME") }}, <a href="https://support.microsoft.com/en-us/help/17621/internet-explorer-downloads" title="Update Your Browser" target="_blank" rel="nofollow">Update Your Browser.</a></div>
    </div>
    <!-- Browser Upgrade E -->
    <!-- Scroll To Top S -->
    <div id="back-top" title="Scroll To Top" style="display: none;">
        <i class="fa fa-angle-up"></i>
    </div>
    <!-- Scroll To Top E -->
    <div id="wrapper">