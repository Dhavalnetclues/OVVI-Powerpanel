<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=0" />
        <title>
            {{ Config::get('Constant.SITE_NAME') }}
        </title>
        <meta name="keywords" content="" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <meta name="csrf-token" content="">
        <link rel="icon" href="{{ Config::get('Constant.CDN_PATH').'assets/images/favicon.ico'}}" type="image/x-icon" />
        <link rel="apple-touch-icon" sizes="144x144" href="{{ Config::get('Constant.CDN_PATH').'assets/images/apple-touch-icon-144.png'}}" />
        <link rel="apple-touch-icon" sizes="114x114" href="{{ Config::get('Constant.CDN_PATH').'assets/images/apple-touch-icon-114.png'}}" />
        <link rel="apple-touch-icon" sizes="72x72" href="{{ Config::get('Constant.CDN_PATH').'assets/images/apple-touch-icon-72.png'}}" />
        <link rel="apple-touch-icon" sizes="57x57" href="{{ Config::get('Constant.CDN_PATH').'assets/images/apple-touch-icon-57.png'}}" />
        <link rel="stylesheet" href="{{ Config::get('Constant.CDN_PATH').'assets/css/main.css'}}" media="all" />
        <script type="text/javascript" src="{{ Config::get('Constant.CDN_PATH').'assets/js/jquery.min.js'}}"></script>
        <link href="https://fonts.googleapis.com/css?family=Libre+Franklin:300,400,500,600,700,800,900&display=swap" rel="stylesheet">
        <!--[if IE 8]>     <html class="ie8"> <![endif]-->
    </head>
    <body>        
        <div id="buorg" class="buorg">
            <div class="buorg__text"><i class="ri-alert-fill"></i> For a better view on
                {{ Config::get('Constant.SITE_NAME') }}, <a href="https://support.microsoft.com/en-us/help/17621/internet-explorer-downloads" title="Update Your Browser" target="_blank">Update Your Browser.</a></div>
        </div>
        <div id="wrapper">
            <section>
                <div class="ip_block">
                    <div class="container">
                        <div class="ip__table">
                            <div class="ip__center">
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <div class="ip__box">
                                            <div class="ip__logo" title="{{ Config::get('Constant.SITE_NAME') }}" itemscope="" itemtype="http://schema.org/Organization">                                                
                                                <meta itemprop="name" content="{{ Config::get('Constant.SITE_NAME') }}">
                                                <meta itemprop="address" content="{{ Config::get('Constant.DEFAULT_ADDRESS') }}">
                                                <img itemprop="image" src="{{ App\Helpers\resize_image::resize(Config::get('Constant.FRONT_LOGO_ID')) }}" alt="{{ Config::get('Constant.SITE_NAME') }}">                                                
                                            </div>
                                            <div class="ip__title attampts">IP Blocked</div>
                                            <div class="ip__desc">
                                                {!! $message !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <script type="text/javascript" src="{{ Config::get('Constant.CDN_PATH').'assets/libraries/browser-upgrade/js/browser-upgrade.js'}}"></script>
        <script type="text/javascript" src="{{ Config::get('Constant.CDN_PATH').'assets/libraries/bootstrap/3.3.7/js/bootstrap.min.js'}}"></script>
        <script type="text/javascript">
$(window).on('load', function () {
    $('#wrapper').css('opacity', '1');
});
        </script>
    </body>
</html>