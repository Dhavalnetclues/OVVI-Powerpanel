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
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="icon" href="{{ Config::get('Constant.CDN_PATH').'assets/images/favicon.ico'}}" type="image/x-icon" />
        <link rel="apple-touch-icon" sizes="144x144" href="{{ Config::get('Constant.CDN_PATH').'assets/images/apple-touch-icon-144.png'}}" />
        <link rel="apple-touch-icon" sizes="114x114" href="{{ Config::get('Constant.CDN_PATH').'assets/images/apple-touch-icon-114.png'}}" />
        <link rel="apple-touch-icon" sizes="72x72" href="{{ Config::get('Constant.CDN_PATH').'assets/images/apple-touch-icon-72.png'}}" />
        <link rel="apple-touch-icon" sizes="57x57" href="{{ Config::get('Constant.CDN_PATH').'assets/images/apple-touch-icon-57.png'}}" />
        <link rel="stylesheet" href="{{ Config::get('Constant.CDN_PATH').'assets/css/main.css'}}" media="all" />
        <script type="text/javascript" src="{{ Config::get('Constant.CDN_PATH').'assets/js/jquery.min.js'}}"></script>
        <link href="https://fonts.googleapis.com/css?family=Libre+Franklin:300,400,500,600,700,800,900&display=swap" rel="stylesheet">
        <!--[if IE 8]>     <html class="ie8"> <![endif]-->
        <script type="text/javascript">$.ajaxSetup({headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}});</script>
    </head>
    <body>        
        <div id="buorg" class="buorg">
            <div class="buorg__text"><i class="ri-alert-fill"></i> For a better view on
                {{ Config::get('Constant.SITE_NAME') }}, <a href="https://support.microsoft.com/en-us/help/17621/internet-explorer-downloads" title="Update Your Browser" target="_blank">Update Your Browser.</a></div>
        </div>
        <div id="wrapper">
            <section>
                <div class="ip_block new_sign_block">
                    <div class="container">
                        <div class="ip__table">
                            <div class="ip__center">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="ip__logo" title="{{ Config::get('Constant.SITE_NAME') }}" itemscope="" itemtype="http://schema.org/Organization">                                                
                                            <meta itemprop="name" content="{{ Config::get('Constant.SITE_NAME') }}">
                                            <meta itemprop="address" content="{{ Config::get('Constant.DEFAULT_ADDRESS') }}">
                                            <img itemprop="image" src="{{ Config::get('Constant.CDN_PATH').'assets/images/logo.svg' }}" alt="{{ Config::get('Constant.SITE_NAME') }}">                                                
                                        </div>
                                        <div class="ip__box">                                            
                                            <h3 class="new-sign-title">New sign-in on {{$varDevice}}</h3>                                             
                                            <div class="new-sign-mail">
                                                <img src="<?php echo $logo_url; ?>">
                                                {{$email}}
                                            </div>
                                            <div class="your_acc_risk">Your account is at risk if this wasn't you</div>
                                            <div class="acc_risk_detail">
                                                <div class="img">
                                                    <?php if ($varDevice == 'Desktop') { ?>
                                                        <i class="ri-computer-line"></i>
                                                    <?php } else {
                                                        ?>
                                                        <i class="fa fa-mobile"></i>
                                                    <?php }
                                                    ?>
                                                </div>
                                                <div class="info">
                                                    <div class="info_device">{{$varDevice}}</div>
                                                    <div class="info_brows">{{$varBrowser_Name}} (Browser)</div>
                                                    <div class="info_contact "><i class="ri-time-line"></i> {{$dat_time}}</div>
                                                    <div class="info_contact "><i class="fa fa-map-marker"></i> {{$varIpAddress}} (IP address) </div>
                                                </div>
                                            </div>
                                            <hr>
                                            <div>
                                                Do you recognize this activity?
                                            </div>
                                            <div class="acc_risk_btns">
                                                <a href="JavaScript:Void(0);" onclick="nowasme({{$id}})" title="No, Secure Account" onclick=""><i class="ri-time-line"></i> No, Secure Account</a>
                                                <a href="JavaScript:Void(0);" onclick="yeswasme()" title="Yes, It Was Me" onclick=""><i class="fa fa-check"></i> Yes, It Was Me</a>
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
                                                    });</script>
        <script type="text/javascript">
                    function yeswasme() {
                    if (confirm("Are you sure, it was you?")) {
                    close();
                    }
                    }
        </script>
        <script type="text/javascript">
            var check_activity = '{!! url("/check_activity/no_secure") !!}';</script>
        <script type="text/javascript">
                    function nowasme(id) {
                    if (confirm("Are you sure, it was not you?")) {
                    $.ajax({
                    type: 'POST',
                            url: check_activity,
                            data: 'id=' + id,
                            success: function (msg) {
                            close();
                            }
                    });
                    }
                    }
        </script>
    </body>
</html>