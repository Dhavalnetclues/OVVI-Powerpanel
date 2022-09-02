<html lang="en-US">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=big5">
        <meta name="robots" content="nofollow">
        <meta name="googlebot" content="noindex">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=0" />
        <title>{{ Config::get('Constant.SITE_NAME') }}</title>
        <meta name="title" content="{{ Config::get('Constant.SITE_NAME') }}">
        <meta name="keywords" content="{{ Config::get('Constant.SITE_NAME') }}">
        <meta name="description" content="{{ Config::get('Constant.SITE_NAME') }}">
        <!-- <meta name="author" content="" /> -->
        <meta property="og:url" content="{{ Request::Url() }}" />
        <meta property="og:type" content="website" />
        @if(isset($META_TITLE))
        <meta property="og:title" content="{!! str_replace('&amp;', '&',$META_TITLE) !!}" />
        @endif
         @if(isset($META_DESCRIPTION))
        <meta property="og:description" content="{!! str_replace('&amp;', '&',$META_DESCRIPTION) !!}" />
        @endif
        <meta property="og:image" content="{{ $CDN_PATH.'assets/images/sharelogo.png' }}" />
        <meta name="twitter:card" content="summary_large_image" />
         @if(isset($META_TITLE))
        <meta name="twitter:title" content="{!! str_replace('&amp;', '&',$META_TITLE) !!}" />
        @endif
        <meta name="twitter:url" content="{{ Request::Url() }}" />
         @if(isset($META_DESCRIPTION))
        <meta name="twitter:description" content="{!! str_replace('&amp;', '&',$META_DESCRIPTION) !!}" />
        @endif
        <meta name="twitter:image" content="{{ $CDN_PATH.'assets/images/sharelogo.png' }}" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="icon" href="{{ $CDN_PATH.'assets/images/favicon.ico' }}" type="image/x-icon" />
        <link rel="canonical" href="{{ Request::Url() }}" />
        <style>
            * {
                -webkit-box-sizing: border-box;
                -moz-box-sizing: border-box;
                box-sizing: border-box;
            }
            .preview_page { font-family: "Open Sans", sans-serif;font-size:16px;margin:0px;padding:130px 40px 40px 30px;color:#333;}   
            .preview_page a {text-decoration:none;color: #00aef1;}            
            .text-center{text-align:center;}
            .preview_page .preview_class {position:absolute;left:0;right:0;top:0px;text-align:center;margin:0px;padding:10px 0;text-transform:uppercase; }  
            
            .preview_page .preview-group-btns { position:absolute;left:0;right:0;top:40px;text-align: center;}
            .preview_page .preview-group-btns a {margin:0px 0px;padding:15px 10px 10px;display:inline-block;border-bottom:3px solid transparent;}
            .preview_page .preview-group-btns a span {display:block;padding-top: 6px;}
            .preview_page .preview-group-btns a img {vertical-align:middle;height:30px; }
            .preview_page .preview-group-btns a.active {border-color:#201e21;}

            .preview_page .preview-group-btns a:last-child {margin-left:0px;} 
            .preview_page .main-preview {height:calc(100vh - 170px);overflow:hidden;position:relative}
            .preview_page .preview-iframe {position:absolute;left:15px;right:15px;bottom:15px;top:15px;}
            .preview_page #desktopid {border: 10px solid transparent;box-shadow: 0 0 15px rgba(0,0,0,0.25);}
            .preview_page #mobileid {border: 10px solid transparent;box-shadow: 0 0 15px rgba(0,0,0,0.25);max-width:355px;margin:auto;
                                     border: 0 solid #201e21;border-width: 80px 15px 70px;border-radius: 35px;
            } 
            .preview_page #iPadid {border: 10px solid transparent;box-shadow: 0 0 15px rgba(0,0,0,0.25);max-width:798px;margin:auto;
                                   border: 0 solid #201e21;border-width: 80px 15px 70px;border-radius: 35px;
            }
            .preview_page #mobileid:after, .preview_page #iPadid:after {
                content: "";display: block;width: 34px;height: 4px;border-radius: 999em;background: lightgray;
                position: absolute;top: -35px;left: 50%;-webkit-transform: translate(-50%,-50%);transform: translate(-50%,-50%);
            }
            .preview_page #mobileid:before, .preview_page #iPadid:before {
                content: "";display: block;width: 34px;height: 34px;border-radius: 999em;background: lightgray;position: absolute;bottom: -70px;left: 50%;
                -webkit-transform: translate(-50%,-50%);transform: translate(-50%,-50%);
            }

            /* Today */    
            .preview_page .main-preview  {overflow: inherit;height: auto;}
            .preview_page #desktopid {height:768px;margin:15px auto;}
            .preview_page #mobileid {height:635px;margin:15px auto;}
            .preview_page #iPadid {height:1024px;margin:15px auto;}

        </style> 
        <script type="text/javascript" src="{{ $CDN_PATH.'assets/js/jquery.min.js' }}"></script>
        <!-- Favicon Icon S -->
        <link rel="icon" href="assets/images/favicon.ico" type="image/x-icon" />
        <link rel="apple-touch-icon" sizes="144x144" href="{{ $CDN_PATH.'assets/images/apple-touch-icon-144.png' }}" />
        <link rel="apple-touch-icon" sizes="114x114" href="{{ $CDN_PATH.'assets/images/apple-touch-icon-114.png' }}" />
        <link rel="apple-touch-icon" sizes="72x72" href="{{ $CDN_PATH.'assets/images/apple-touch-icon-72.png' }}" />
        <link rel="apple-touch-icon" sizes="57x57" href="{{ $CDN_PATH.'assets/images/apple-touch-icon-57.png' }}" />
        <!-- Favicon Icon E -->
        <!--[if IE 8]>     <html class="ie8"> <![endif]-->
    </head>
    <body class="preview_page">
        <section class="head-preview">
            <h2 class="preview_class">Preview</h2>
            <div class="preview-group-btns text-center">
                <a onclick="GetdESKTOPScreen()" id="desktopid1" href="javascript:;" class="active" title="Desktop"><img src="{{ $CDN_PATH.'assets/images/desktop-monitor.svg'}}"><span>Desktop</span></a>            
                <a onclick="GetiPadScreen()" id="iPadid1" href="javascript:;" class="" id="iPad" title="iPad"><img src="{{ $CDN_PATH.'assets/images/ipad.svg'}}"><span>iPad</span></a>        
                <a onclick="GetMobileScreen()" id="mobileid1" href="javascript:;" class="" id="mobile" title="Mobile"><img src="{{ $CDN_PATH.'assets/images/cellphone.svg'}}"><span>Mobile</span></a>
            </div>    
        </section>    
        <section class="main-preview">
            <div class="preview-iframe" id="desktopid">
                <iframe class="live-preview-modal__frame js-preview-iframe live-preview-modal__frame--visible" src="{{ $_GET['url'] }}" frameborder="0" data-no-lazy="1" style="width: 100%; height:100%;"></iframe>
            </div>
            <div class="preview-iframe" id="mobileid" style="display:none;"></div>
            <div class="preview-iframe" id="iPadid"  style="display:none;"></div>           
        </section>    
        <script>
            function GetdESKTOPScreen() {
                $("#iPadid").html('');
                $("#mobileid").html('');
                $("#desktopid").show();
                $("#iPadid").hide();
                $("#mobileid").hide();
                $("#desktopid1").addClass('active');
                $("#mobileid1").removeClass('active');
                $("#iPadid1").removeClass('active');
                        $("#desktopid").html('<iframe class="live-preview-modal__frame js-preview-iframe live-preview-modal__frame--visible" src="{{ $_GET['url'] }}" frameborder="0" data-no-lazy="1" style="width: 100%; height: 100%;"></iframe>');
            }
            function GetMobileScreen() {
                $("#iPadid").html('');
                $("#desktopid").html('');
                $("#mobileid").show();
                $("#desktopid").hide();
                $("#iPadid").hide();
                $("#mobileid1").addClass('active');
                $("#desktopid1").removeClass('active');
                $("#iPadid1").removeClass('active');
                        $("#mobileid").html('<iframe class="live-preview-modal__frame js-preview-iframe live-preview-modal__frame--visible" src="{{ $_GET['url'] }}" frameborder="0" data-no-lazy="1" style="width: 325px; height: 100%;"></iframe>');
            }
            function GetiPadScreen() {
                $("#mobileid").html('');
                $("#desktopid").html('');
                $("#iPadid").show();
                $("#desktopid").hide();
                $("#mobileid").hide();
                $("#iPadid1").addClass('active');
                $("#desktopid1").removeClass('active');
                $("#mobileid1").removeClass('active');
                        $("#iPadid").html('<iframe class="live-preview-modal__frame js-preview-iframe live-preview-modal__frame--visible" src="{{ $_GET['url'] }}" frameborder="0" data-no-lazy="1" style="width: 768px; height: 100%;"></iframe>');
            }
        </script>
    </body>
</html>