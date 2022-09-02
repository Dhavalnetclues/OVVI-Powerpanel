<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->
    <head>
        <meta charset="utf-8" />
        <title>@yield('title')</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="" name="description"/>
        <meta content="" name="author" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
        <link href="https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300,700&display=swap" rel="stylesheet">
        <link href="{{ $CDN_PATH.'resources/global/plugins/font-awesome/css/font-awesome.min.css' }}" rel="stylesheet" type="text/css" />
        <link href="{{ $CDN_PATH.'resources/global/plugins/simple-line-icons/simple-line-icons.min.css' }}" rel="stylesheet" type="text/css" />
        <link href="{{ $CDN_PATH.'resources/global/plugins/line-awesome/line-awesome.css' }}" rel="stylesheet" type="text/css" />
        <link href="{{ $CDN_PATH.'resources/global/plugins/bootstrap/css/bootstrap.min.css' }}" rel="stylesheet" type="text/css" />
        <link href="{{ $CDN_PATH.'resources/global/plugins/uniform/css/uniform.default.css' }}" rel="stylesheet" type="text/css" />
        <link href="{{ $CDN_PATH.'resources/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css' }}" rel="stylesheet" type="text/css" />
        <link href="{{ $CDN_PATH.'resources/global/plugins/bootstrap-toastr/toastr.css' }}" rel="stylesheet" type="text/css"/>
        <link href="{{ $CDN_PATH.'resources/global/plugins/fancybox/source/jquery.fancybox.css' }}" rel="stylesheet" type="text/css"/>
        <link href="{{ $CDN_PATH.'resources/global/plugins/mcscroll/jquery.mCustomScrollbar.css' }}" rel="stylesheet" type="text/css" />
        <link href="{{ $CDN_PATH.'assets/libraries/bootstrap-select-master/css/bootstrap-select.min.css' }}" rel="stylesheet" type="text/css"/>
        <link href="{{ $CDN_PATH.'resources/global/plugins/image-cropper/cropper.min.css' }}" rel="stylesheet" type="text/css" />
        
         <link href="{{ $CDN_PATH.'resources/global/css/rank-button.css' }}" rel="stylesheet" type="text/css" />
        <!-- END GLOBAL MANDATORY STYLES -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        @yield('css')
        <link href="{{ $CDN_PATH.'resources/global/plugins/select2/css/select2.min.css' }}" rel="stylesheet" type="text/css"/>
        <link href="{{ $CDN_PATH.'resources/global/plugins/select2/css/select2-bootstrap.min.css' }}" rel="stylesheet" type="text/css"/>
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="{{ $CDN_PATH.'resources/global/css/components-md.min.css' }}" rel="stylesheet" id="style_components" type="text/css" />
        <link href="{{ $CDN_PATH.'resources/global/css/plugins-md.min.css' }}" rel="stylesheet" type="text/css" />
        <!-- END THEME GLOBAL STYLES -->
        <!-- BEGIN THEME LAYOUT STYLES -->
        <link href="{{ $CDN_PATH.'resources/layouts/layout4/css/layout.min.css' }}" rel="stylesheet" type="text/css"/>
        <link href="{{ $CDN_PATH.'resources/layouts/layout4/css/themes/light.min.css' }}" rel="stylesheet" type="text/css" id="style_color" />
        <link href="{{ $CDN_PATH.'resources/layouts/layout4/css/custom.min.css' }}" rel="stylesheet" type="text/css" />
        <link href="{{ $CDN_PATH.'resources/global/plugins/jquery-nestable/jquery.nestable.css' }}" rel="stylesheet" type="text/css" />
        <link href="{{ $CDN_PATH.'resources/global/plugins/menu-loader/style.css' }}" rel="stylesheet" type="text/css"/>
        <link href="{{ $CDN_PATH.'resources/global/plugins/dropzone/dropzone.min.css' }}" rel="stylesheet" type="text/css" />
        <link href="{{ $CDN_PATH.'resources/global/plugins/dropzone/basic.min.css' }}" rel="stylesheet" type="text/css" />
        <link href="{{ $CDN_PATH.'resources/global/css/colors.css' }}" rel="stylesheet" id="style_components" type="text/css" />
        <link href="{{ $CDN_PATH.'resources/global/plugins/datetimepicker/css/jquery.datetimepicker.css' }}" rel="stylesheet" type="text/css" />
        <!-- END THEME LAYOUT STYLES -->
        <link rel="shortcut icon" href="{{ $CDN_PATH.'assets/images/favicon.ico' }}" />
        <link rel="apple-touch-icon" sizes="144x144" href="{{ $CDN_PATH.'assets/images/apple-touch-icon-144.png' }}" />
        <link rel="apple-touch-icon" sizes="114x114" href="{{ $CDN_PATH.'assets/images/apple-touch-icon-114.png' }}" />
        <link rel="apple-touch-icon" sizes="72x72" href="{{ $CDN_PATH.'assets/images/apple-touch-icon-72.png' }}" />
        <link rel="apple-touch-icon" sizes="57x57" href="{{ $CDN_PATH.'assets/images/apple-touch-icon-57.png' }}" />
        <script>
            var CDN_PATH = "{{ $CDN_PATH }}";
            window.site_url = '{!! url("/") !!}';
            var rootUrl = window.site_url + "/";
            var BUCKET_ENABLED = "{{ $BUCKET_ENABLED }}";
            var formid = '<?php echo Request::segment(3); ?>';
            var formurl = '<?php echo Request::segment(4); ?>';
            var formpageurl = '<?php echo Request::segment(2); ?>';
        </script>
        <script type="text/javascript">
//            @if (Config::get('Constant.DEFAULT_AUTHENTICATION') == 'Y')
//                    @if ((Session::get('Authentication_User') == "Y"))
//                    @if ((Session::get('randomhistory_id') == ""))
//                    var newUrl = site_url + '/powerpanel/verify';
//            window.location.href = newUrl;
//                    @endif
//                    @endif
//                    @endif
        </script>
        <?php
        if (Config::get('Constant.DEFAULT_AUTHENTICATION') == 'Y') {
            if (Session::get('Authentication_User') == "Y") {
                if (Session::get('randomhistory_id') == "") {
                    echo "<script type='text/javascript'>var newUrl = site_url + '/powerpanel/verify';window.location.href = newUrl;</script>";
                    exit;
                }
            }
        }
        ?>
        @php
        $userid = auth()->user()->id;
        $SecurityUser = \App\User::getRecordById($userid);
        $chrSecurityQuestions = $SecurityUser['chrSecurityQuestions'];
        $intSearchRank = $SecurityUser['intSearchRank'];
        $intAttempts = $SecurityUser['intAttempts'];
        $Security_history = Session::get('Security_history');
        $MAX_LOGIN_ATTEMPTS = Config::get('Constant.MAX_LOGIN_ATTEMPTS');
        @endphp
        <?php
        if ($chrSecurityQuestions == "Y" && $Security_history == '') {
            if ($intSearchRank == '1') {
                $High_LoginLog = \App\LoginLog::getSecurity_NewIp_Device_Bro_Count();
                if ($intAttempts >= $MAX_LOGIN_ATTEMPTS - 3 || $High_LoginLog <= 1) {
                    echo "<script type='text/javascript'>var newUrl = site_url + '/powerpanel/question_verify';window.location.href = newUrl;</script>";
                    exit;
                }
            } elseif ($intSearchRank == '2') {
                $Med_LoginLog = \App\LoginLog::getSecurity_NewIp_Device_Count();
                if ($intAttempts >= $MAX_LOGIN_ATTEMPTS - 2 || $Med_LoginLog <= 1) {
                    echo "<script type='text/javascript'>var newUrl = site_url + '/powerpanel/question_verify';window.location.href = newUrl;</script>";
                    exit;
                }
            } elseif ($intSearchRank == '3') {
                $Low_LoginLog = \App\LoginLog::getSecurity_NewIp_Count();
                if ($intAttempts >= $MAX_LOGIN_ATTEMPTS - 1 || $Low_LoginLog <= 1) {
                    echo "<script type='text/javascript'>var newUrl = site_url + '/powerpanel/question_verify';window.location.href = newUrl;</script>";
                    exit;
                }
            }
        }
        ?>
        @php
        header("Cache-Control: private, must-revalidate, max-age=0, no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
        @endphp
    </head>
    <!-- END HEAD -->
    <body class="page-container-bg-solid page-header-fixed page-sidebar-closed-hide-logo page-md" onload="display_ct();">
        <div class="message_loader" style="display:none;">
            <div class="cell_conter">
                <img src="{{ $CDN_PATH.'assets/images/message_loader.svg' }}" alt="loader">
            </div>
        </div>
        @include('powerpanel.partials.header')
        <!-- BEGIN CONTAINER -->
        <div class="page-container">
            <!-- BEGIN SIDEBAR -->
            @include('powerpanel.partials.sidebar')
            <!-- END SIDEBAR -->
            <!-- BEGIN CONTENT -->
            <div class="page-content-wrapper">
                <!-- BEGIN CONTENT BODY -->
                <div class="page-content">
                    @yield('content')
                    @include('powerpanel.media_manager.gallery_component')
                    @include('powerpanel.media_manager.audios_component')
                    @if(isset($videoManager))
                    @include('powerpanel.media_manager.video_component')
                    @endif
                    @include('powerpanel.media_manager.documents_component')
                </div>
                <!-- END CONTENT BODY -->
            </div>
            <!-- END CONTENT -->
        </div>
        <!-- END CONTAINER -->
        <!--[if lt IE 9]>
        <script src="../resources/global/plugins/respond.min.js"></script>
        <script src="../resources/global/plugins/excanvas.min.js"></script>
        <![endif]-->
        <div class="new_modal new_share_popup modal fade bs-modal-md" id="aliasAlert" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-vertical">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                        </div>
                        <div class="modal-body delMsg text-center">
                            <p>
                                An alias is already exist, so we have suffixed it with a number. You can change it as per your choice by editing it.
                            </p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn red btn-outline" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="new_modal new_share_popup modal new_modal_terms  fade bs-modal-md" id="tnc" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-vertical">
                    <div class="modal-content">
                        <div class="modal-header">
                            We've updated our Terms &amp; Conditions
                        </div>
                        <div class="modal-body delMsg text-center">
                            <div class="p-pw_desc">
                                <p>By accepting you agree to be bound by the new Terms and Conditions. This is a legal contract between you and <strong>Netclues.</strong></p>
                                <p>Please accept the changes so you can log in.</p>
                                <div class="d_view">Read Our <a title="Terms &amp; Conditions" target="_blank" href="https://www.netclues.com/terms-conditions" class="addTermsandCondition">Terms &amp; Conditions</a></div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn red btn-outline" id="tnc-reject">Close</button>
                            <button type="button" class="btn green btn-outline" id="tnc-accept">I Agree</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- BEGIN CORE PLUGINS -->
    <div class="footer_section">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-left">
                    <div class="copyright">
                        Copyright &COPY; {{ date('Y') }} Netclues. All Rights Reserved. | <a class="addTermsandCondition" title="Terms &amp; Conditions" target="_blank" href="https://www.netclues.com/terms-conditions">Terms &amp; Conditions</a>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                    <div class="copyright">
                        {!!  Config::get('Constant.FOOTER_DEVELOPED_BY')  !!}
                        Netclues
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- FIXED FORM -->
    <div class="fixed_from" id="Test">
        <a class="title_fixed title_icon_ticket shadow-lg" title="Submit a Ticket" href="javascript:void(0)"><i class="fa fa-ticket"></i></a>
        <!-- <a class="title_fixed" href="javascript:void(0)"><i class="fa fa-ticket"></i></a> -->
        {!! Form::open(['method' => 'post','id'=>'Ticket_Form','name'=>'Ticket_Form','url'=>url('powerpanel/settings/insertticket'),'enctype'=>'multipart/form-data']) !!}
        <div class="bma_form">
            <h4>Submit a Ticket</h4>
            <div class="form-group">
                {!! Form::text('Name',  old('Name') , array('id' => 'Name', 'class' => 'form-control', 'placeholder'=>'Enter Your Name')) !!}
                @if($errors->has('Name'))
                <span class="help-block">
                    {{ $errors->first('Name') }}
                </span>
                @endif
            </div>
            <div class="form-group">
                <select class="form-control bs-select select2" name="varType" id="varType">
                    <option value="">Type</option>
                    <option value="1">Fixes / Issues</option>
                    <option value="2">Changes</option>
                    <option value="3">Suggestion</option>
                    <option value="4">New Features</option>
                </select>
                @if($errors->has('varType'))
                <span class="help-block">
                    {{ $errors->first('varType') }}
                </span>
                @endif
            </div>
            <div class="form-group">
                {!! Form::textarea('varMessage', old('varMessage') , array( 'class' => 'form-control', 'cols' => '20', 'rows' => '3', 'id' => 'varMessage', 'spellcheck' => 'true','placeholder'=>'Enter Your Message' )) !!}
                @if($errors->has('varMessage'))
                <span class="help-block">
                    {{ $errors->first('varMessage') }}
                </span>
                @endif
            </div>
            <div class="form-group">
                <div class="row row_file">
                    <div class="col-sm-6 fkimg_val">
                        <div class="js-inputbox">
                            <input type="file" name="file-1[]" id="file-1" class="inputfile inputfile-1 fkimg_val" data-multiple-caption="{count} files selected" multiple />
                            <label for="file-1"><svg xmlns="https://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"/></svg> <span>Select a file&hellip;</span></label>
                        </div>
                        @if($errors->has('file-1'))
                        <span class="help-block">
                            {{ $errors->first('file-1') }}
                        </span>
                        @endif
                        <div class="validation" style="display:none;color:#e73d4a"> Upload Max 5 Files allowed. </div>
                        <span id="fkimg_val123"></span>
                    </div>
                    <div class="col-sm-6">
                        <a href="javascript:;" onclick="report()" class="btn capture_btn">Capture</a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-center">
                        <input type="hidden" name="img_val1" id="img_val1" value="" />
                        <img width="50%" style="margin:8px auto 0px;box-shadow:0 2px 2px rgba(0,0,0,0.5);max-height: 100px" class="screen" >
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>Link:</label>
                <input type="text" name="Link" id="Link" value="{{ "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']}}" placeholder="Enter Your Link." readonly class="form-control">
                @if($errors->has('Link'))
                <span class="help-block">
                    {{ $errors->first('Link') }}
                </span>
                @endif
            </div>
            <div class="form-group text-center">
                <button title="Submit" class="btn btn_fixed">Submit</button>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
    <script type="text/javascript">
        var CKEDITOR_APP_URL = '{{ env("APP_URL") }}';
        //var settings = JSON.parse('{!! Config::get("Constant.MODULE.SETTINGS") !!}');
        var user_account = false;
                @if (!$userIsAdmin)
        user_account = true;
        @endif
                var super_admin = false;
                @if (Entrust::hasRole('netquick_admin'))
        super_admin = true;
        @endif
                var termsAccepted = "{{ $termsAccepted }}";
    </script>
    <script src="{{ $CDN_PATH.'resources/global/plugins/jquery.min.js' }}" type="text/javascript"></script>
    <script src="{{ $CDN_PATH.'assets/libraries/bootstrap-select-master/js/bootstrap-select.min.js' }}" type="text/javascript"></script>
    <script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap/js/bootstrap.min.js' }}" type="text/javascript"></script>
    <script src="{{ $CDN_PATH.'resources/global/plugins/js.cookie.min.js' }}" type="text/javascript"></script>
    <script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js' }}" type="text/javascript"></script>
    <script src="{{ $CDN_PATH.'resources/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js' }}" type="text/javascript"></script>
    <script src="{{ $CDN_PATH.'resources/global/plugins/jquery.blockui.min.js' }}" type="text/javascript"></script>
    <script src="{{ $CDN_PATH.'resources/global/plugins/uniform/jquery.uniform.min.js' }}" type="text/javascript"></script>
    <script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js' }}" type="text/javascript"></script>
    <!-- END CORE PLUGINS -->
    <!-- END PAGE LEVEL PLUGINS -->

    <script type="text/javascript">
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}});
    </script>
    <script src="{{ $CDN_PATH.'resources/global/plugins/jquery-validation/js/jquery.validate.min.js' }}" type="text/javascript"></script>
    <script src="{{ $CDN_PATH.'resources/global/plugins/jquery-validation/js/additional-methods.min.js' }}" type="text/javascript"></script>
    <!-- <script src="{{ $CDN_PATH.'resources/global/plugins/jquery-validation/js/jquery.validate.js' }}" type="text/javascript"></script> -->
    <!-- END PAGE LEVEL PLUGINS -->
    <!-- BEGIN THEME GLOBAL SCRIPTS -->
    <script src="{{ $CDN_PATH.'resources/global/scripts/app.js' }}" type="text/javascript"></script>

    <script src="{{ $CDN_PATH.'resources/pages/scripts/ui-blockui.min.js' }}" type="text/javascript"></script>
    <script src="{{ $CDN_PATH.'resources/global/plugins/dropzone/dropzone.min.js' }}" type="text/javascript"></script>
    <script src="{{ $CDN_PATH.'resources/pages/scripts/form-dropzone.js' }}" type="text/javascript"></script>
    <!-- END THEME GLOBAL SCRIPTS -->
    <!-- BEGIN THEME LAYOUT SCRIPTS -->         
    <script src="{{ $CDN_PATH.'resources/layouts/layout4/scripts/layout.min.js' }}" type="text/javascript"></script>
    <script src="{{ $CDN_PATH.'resources/layouts/layout4/scripts/demo.min.js' }}" type="text/javascript"></script>
    <!-- END THEME LAYOUT SCRIPTS -->
    <script src="{{ $CDN_PATH.'resources/pages/scripts/popup.js' }}" type="text/javascript"></script>
    <script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap-toastr/toastr.min.js' }}" type="text/javascript"></script>
    <script src="{{ $CDN_PATH.'resources/global/plugins/fancybox/source/jquery.fancybox.pack.js' }}" type=
    "text/javascript"></script>
    <script src="{{ $CDN_PATH.'resources/global/scripts/media_manager.js' }}" type="text/javascript"></script>
    <script type="text/javascript">
        var DEFAULT_TIME_ZONE = "{{ Config::get('Constant.DEFAULT_TIME_ZONE')  }}";
        var DEFAULT_DATE_FORMAT = "{{ Config::get('Constant.DEFAULT_DATE_FORMAT')  }}";
        var DEFAULT_TIME_FORMAT = "{{ Config::get('Constant.DEFAULT_TIME_FORMAT') }}";
        var DEFAULT_DT_FORMAT = 'M/D/YYYY';
        var DEFAULT_DT_FMT_FOR_DATEPICKER = 'M/d d/yyyy';
        if (DEFAULT_DATE_FORMAT == 'd/m/Y')
        {
            DEFAULT_DT_FORMAT = 'D/M/YYYY';
            DEFAULT_DT_FMT_FOR_DATEPICKER = 'd d/mm/yyyy';
        } else if (DEFAULT_DATE_FORMAT == 'm/d/Y') {
            DEFAULT_DT_FORMAT = 'M/D/YYYY';
            DEFAULT_DT_FMT_FOR_DATEPICKER = 'mm/dd/yyyy';
        } else if (DEFAULT_DATE_FORMAT == 'Y/m/d') {
            DEFAULT_DT_FORMAT = 'YYYY/M/D';
            DEFAULT_DT_FMT_FOR_DATEPICKER = 'yyyy/mm/dd';
        } else if (DEFAULT_DATE_FORMAT == 'Y/d/m') {
            DEFAULT_DT_FORMAT = 'YYYY/D/M';
            DEFAULT_DT_FMT_FOR_DATEPICKER = 'yyyy/dd/mm';
        } else if (DEFAULT_DATE_FORMAT == 'M/d/Y') {
            DEFAULT_DT_FORMAT = 'M/D/YYYY';
            DEFAULT_DT_FMT_FOR_DATEPICKER = 'M/dd/yyyy';
        }
        $(document).ready(function () {
            $('[data-bs-toggle="tooltip"]').tooltip();
            $('.thumbnail > a').click(function () {
                $('.thumbnail > a').removeClass('selected')
                $(this).addClass('selected')
            });
            $('.close-btn').click(function () {
                $(this).closest('.info').addClass('close');
            });
            $('.left-panel ul li a').click(function () {
                $('.info').removeClass('close')
            });
            $('.close-btn').click(function () {
                $('ul li').removeClass('active')
            });
        });
        $(document).ready(function () {
            $(".title_fixed").click(function () {
                $(".fixed_from").toggleClass("open");
                if ($('.fixed_from').hasClass('open')) {
                    $('.fixed_from .title_fixed').attr('title', 'Close');
                } else {
                    $('.fixed_from .title_fixed').attr('title', 'Submit a Ticket');
                }
            });
        });
        $(document).ready(function () {
            if (screen.width < 767) {
                $('#cmspage_id.collapse').removeClass('in');
            }
        });
        $(document).ready(function () {
            (function (document, window, index)
            {
                var inputs = document.querySelectorAll('.inputfile');
                Array.prototype.forEach.call(inputs, function (input)
                {
                    var label = input.nextElementSibling,
                            labelVal = label.innerHTML;
                    input.addEventListener('change', function (e)
                    {
                        var fileName = '';
                        if (this.files && this.files.length > 1)
                            fileName = (this.getAttribute('data-multiple-caption') || '').replace('{count}', this.files.length);
                        else
                            fileName = e.target.value.split('\\').pop();
                        if (fileName)
                            label.querySelector('span').innerHTML = fileName;
                        else
                            label.innerHTML = labelVal;
                    });
                    // Firefox bug fix
                    input.addEventListener('focus', function () {
                        input.classList.add('has-focus');
                    });
                    input.addEventListener('blur', function () {
                        input.classList.remove('has-focus');
                    });
                });
            }(document, window, 0));
        });
        setTimeout(function () {
            $('.alert-info').hide()
        }, 5000)
        setTimeout(function () {
            $('.alert-danger').hide()
        }, 5000)
        setTimeout(function () {
            $('.alert-success').hide()
        }, 5000)
        $(document).ready(function () {
            action_bar();
        });
        $(window).resize(function () {
            action_bar();
        });
        function action_bar() {
            var top_bar = $('.top_browser_note');
            if (top_bar.length && "fixed" == top_bar.css('position')) {
                $('.page-header').css('top', top_bar.height());
                $('.page-container').css('top', top_bar.height());
            }
            if (top_bar.css('display') == "none") {
                $('.page-header').css('top', '0');
                $('.page-container').css('top', '0');
            }
        }
        function report() {
            $(".bma_form").toggle();
            if ($(this).hasClass('active')) {
                $(this).addClass('active');
            } else {
                $(this).removeClass('active');
                $(".time-date-iframe").hide();
                $("#cont_d0e6b9370fe3b3af5a4e2b956e6a0576").hide();
            }
            $(".fixed_from").hide();
            $(".copyright").hide();
            let region = document.querySelector("body"); // whole screen
            html2canvas(region, {
                onrendered: function (canvas) {
                    setTimeout(function () {
                        $(".fixed_from").show()
                    }, 50)
                    setTimeout(function () {
                        $(".copyright").show()
                    }, 50)
                    $(".time-date-iframe").show();
                    $("#cont_d0e6b9370fe3b3af5a4e2b956e6a0576").show();
                    $(".bma_form").toggle();
                    $(this).removeClass('active');
                    let pngUrl = canvas.toDataURL();
                    let img = document.querySelector(".screen");
                    img.src = pngUrl; // pngUrl contains screenshot graphics data in url form
                    document.getElementById("img_val1").value = img.src;
                    //document.getElementById("img_val").value = img.src;
                    // here you can allow user to set bug-region
                    // and send it with 'pngUrl' to server
                },
            });
        }
        $('#file-1').change(function () {
            //get the input and the file list
            var input = document.getElementById('file-1');
            if (input.files.length > 5) {
                $('.validation').css('display', 'block');
                $(".btn.btn_fixed").attr('disabled', 'disabled');
            } else {
                $('.validation').css('display', 'none');
                $(".btn.btn_fixed").prop("disabled", false);
            }
        });
        $(document).keydown(function (e) {
            if (e.keyCode === 27) {
                $(".modal").modal('hide');
                $(".config-class").removeClass("open");
                $(".layout-class").removeClass("open");
            }
        });
    </script>

    <script src="{{ $CDN_PATH.'resources/global/plugins/mcscroll/jquery.mCustomScrollbar.concat.min.js' }}" type="text/javascript"></script>
    <script src="{{ $CDN_PATH.'resources/pages/scripts/footer_ticket_validations.js' }}" type="text/javascript"></script>
    <script src="{{ $CDN_PATH.'resources/pages/scripts/custom_js.js' }}" type="text/javascript"></script>
    <script src="{{ $CDN_PATH.'resources/global/plugins/menu-loader/jquery-loader.js' }}" type="text/javascript"></script>
    <script src="{{ $CDN_PATH.'resources/global/plugins/loading/loading.js' }}" type="text/javascript"></script>
    <script src="{{ $CDN_PATH.'messages.js' }}" type="text/javascript"></script>
    <script src="{{ $CDN_PATH.'resources/global/plugins/select2/js/select2.full.min.js' }}" type="text/javascript"></script>
    <script type="text/javascript">
        var DEFAULT_TIME_ZONE = "{{ Config::get('Constant.DEFAULT_TIME_ZONE')  }}";
        var DEFAULT_DATE_FORMAT = "{{ Config::get('Constant.DEFAULT_DATE_FORMAT')  }}";
        var DEFAULT_TIME_FORMAT = "{{ Config::get('Constant.DEFAULT_TIME_FORMAT') }}";
        var DEFAULT_DT_FORMAT = 'M/D/YYYY';
        var DEFAULT_DT_FMT_FOR_DATEPICKER = 'M/d d/yyyy';
        if (DEFAULT_DATE_FORMAT == 'd/m/Y')
        {
            DEFAULT_DT_FORMAT = 'D/M/YYYY';
            DEFAULT_DT_FMT_FOR_DATEPICKER = 'd d/mm/yyyy';
        } else if (DEFAULT_DATE_FORMAT == 'm/d/Y') {
            DEFAULT_DT_FORMAT = 'M/D/YYYY';
            DEFAULT_DT_FMT_FOR_DATEPICKER = 'mm/dd/yyyy';
        } else if (DEFAULT_DATE_FORMAT == 'Y/m/d') {
            DEFAULT_DT_FORMAT = 'YYYY/M/D';
            DEFAULT_DT_FMT_FOR_DATEPICKER = 'yyyy/mm/dd';
        } else if (DEFAULT_DATE_FORMAT == 'Y/d/m') {
            DEFAULT_DT_FORMAT = 'YYYY/D/M';
            DEFAULT_DT_FMT_FOR_DATEPICKER = 'yyyy/dd/mm';
        } else if (DEFAULT_DATE_FORMAT == 'M/d/Y') {
            DEFAULT_DT_FORMAT = 'M/D/YYYY';
            DEFAULT_DT_FMT_FOR_DATEPICKER = 'M/dd/yyyy';
        }
    </script>
    @yield('scripts')
    <script src="{{ $CDN_PATH.'resources/global/plugins/select2/js/components-select2.min.js' }}" type="text/javascript"></script>
    <script src="{{ $CDN_PATH.'resources/global/plugins/select2/js/html2canvas.min.js' }}" type="text/javascript"></script>
    <?php if (Request::segment(2) != 'formbuilder') { ?>
        <script src="{{ $CDN_PATH.'resources/global/plugins/datetimepicker/js/jquery.datetimepicker.full.js' }}" type="text/javascript"></script>
        <script src="{{ $CDN_PATH.'resources/global/plugins/datetimepicker/js/jquery.datetimepicker.custom.js' }}" type="text/javascript"></script>
    <?php } ?>
    <script src="{{ $CDN_PATH.'resources/global/plugins/image-cropper/cropper.min.js' }}"></script>
    <script type="text/javascript">
        jQuery.validator.addMethod("noSpace", function (value, element) {
            if (value.trim().length <= 0) {
                return false;
            } else {
                return true;
            }
        }, "No space please and don't leave it empty");
        $(document).on('focusout', 'textarea', function () {
            if ($(this).parents('form').hasClass('CommentsForm')) {
                var textvalue = $.trim($(this).val());
                $(this).val('');
                $(this).val(textvalue);
            }
        });
    </script>
    <script type="text/javascript">
        $("#slim_notification").mCustomScrollbar({
            // axis: "y",
            theme: "minimal-dark",
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function () {
            var oldUrl = window.location.href;
            var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
            if (hashes == "notifications") {
                oldUrl = oldUrl.replace("?notifications", '');
                $("#MenuItem2").trigger('click');
                window.history.replaceState({}, '', oldUrl);
            } else if (hashes == "tab=P") {
                $("#MenuItem1").trigger('click');
                oldUrl = oldUrl.replace("?tab=P", '');
                window.history.replaceState({}, '', oldUrl);
            } else if (hashes == "tab=A") {
                $("#MenuItem2").trigger('click');
                oldUrl = oldUrl.replace("?tab=A", '');
                window.history.replaceState({}, '', oldUrl);
            } else if (hashes == "tab=D") {
                $("#MenuItem3").trigger('click');
                oldUrl = oldUrl.replace("?tab=D", '');
                window.history.replaceState({}, '', oldUrl);
            } else if (hashes == "tab=T") {
                $("#MenuItem4").trigger('click');
                oldUrl = oldUrl.replace("?tab=T", '');
                window.history.replaceState({}, '', oldUrl);
            } else if (hashes == "tab=F") {
                $("#MenuItem5").trigger('click');
                oldUrl = oldUrl.replace("?tab=F", '');
                window.history.replaceState({}, '', oldUrl);
            } else if (hashes == "tab=R") {
                $("#MenuItem6").trigger('click');
                oldUrl = oldUrl.replace("?tab=R", '');
                window.history.replaceState({}, '', oldUrl);
            }
        });
//            $(document).ready(function () {
        $(".bootstrap-select .dropdown-toggle").on("click", function () {
            $(".bootstrap-select").addClass("open");
        });
//            });
        $(document).on('click', '#AlertNo', function () {
            var x = location.href;
            var value = document.getElementById("AlertNo").value;
            if (value != '') {
                window.location.href = x + "?tab=" + value;
            } else {
                window.location.href = x;
            }
        });
        $("#notification").on("click", function () {
            var Notification_URL = window.site_url + "/powerpanel/Notification_View";
            var resultId = $('#notification-view');
            $.ajax({
                type: 'POST',
                url: Notification_URL,
                beforeSend: function () {
                    resultId.html('<div class="autocomplete_search_loader_inner text-center"><img src="' + site_url + '/cdn/assets/images/loader-search.svg" ></div>');
                },
                success: function (msg) {
                    resultId.find('.autocomplete_search_loader_inner').remove();
                    $("#notification-view").html(msg);
                    $("#slim_notification").mCustomScrollbar({
                        axis: "y",
                        theme: "minimal-dark"
                    });
                }
            });
        });
<?php
$link = $_SERVER["REQUEST_URI"];
$link_array = explode('/', $link);
$page = end($link_array);
if ($page == 'add') {
    ?>
            $(".page-sidebar-wrapper").find('a').bind("click", function () {
                var name = $('body').find('.titlespellingcheck').length;
                var namevalue = $('.titlespellingcheck').val();
                if (name > 0 && namevalue != '' && typeof namevalue != 'undefined') {
                    if (confirm('Are you sure you want to leave this page? Click "OK" to save this record.')) {
                        var Save_URL = window.site_url + "/powerpanel/Save_Data";
                        var mid = '<?php echo Config::get('Constant.MODULE.ID'); ?>';
                        $.ajax({
                            type: 'POST',
                            url: Save_URL,
                            data: {
                                name: namevalue,
                                mid: mid
                            },
                            success: function () {
                                window.location.href = site_url + '/powerpanel/' + '<?php echo Request::segment(2); ?>';
                            }
                        });
                    }
                }
            });
            $(".page-header.navbar-fixed-top").find('a').bind("click", function () {
                var name = $('body').find('.titlespellingcheck').length;
                var namevalue = $('.titlespellingcheck').val();
                if (name > 0 && namevalue != '' && typeof namevalue != 'undefined') {
                    if (confirm('Are you sure you want to leave this page? Click "OK" to save this record.')) {
                        var Save_URL = window.site_url + "/powerpanel/Save_Data";
                        var mid = '<?php echo Config::get('Constant.MODULE.ID'); ?>';
                        $.ajax({
                            type: 'POST',
                            url: Save_URL,
                            data: {
                                name: namevalue,
                                mid: mid
                            },
                            success: function () {
                                window.location.href = site_url + '/powerpanel/' + '<?php echo Request::segment(2); ?>';
                            }
                        });
                    }
                }
            });
            $(".footer_section").find('a').bind("click", function () {
                var name = $('body').find('.titlespellingcheck').length;
                var namevalue = $('.titlespellingcheck').val();
                if (name > 0 && namevalue != '' && typeof namevalue != 'undefined') {
                    if (confirm('Are you sure you want to leave this page? Click "OK" to save this record.')) {
                        var Save_URL = window.site_url + "/powerpanel/Save_Data";
                        var mid = '<?php echo Config::get('Constant.MODULE.ID'); ?>';
                        $.ajax({
                            type: 'POST',
                            url: Save_URL,
                            data: {
                                name: namevalue,
                                mid: mid
                            },
                            success: function () {
                                window.location.href = site_url + '/powerpanel/' + '<?php echo Request::segment(2); ?>';
                            }
                        });
                    }
                }
            });
<?php } ?>


    </script>
    @php
    if(Request::segment(3) != 'add' && Request::segment(4) != 'edit'){
    @endphp
    <script src="{{ $CDN_PATH.'resources/pages/scripts/table-grid-quick-fun-ajax.js' }}" type="text/javascript"></script>
     @php } @endphp
    <script src="{{ $CDN_PATH.'resources/global/plugins/moment.min.js' }}"></script>
    <script src="{{ $CDN_PATH.'resources/global/plugins/moments-timezone.js' }}"></script>
    <script src="{{ $CDN_PATH.'resources/global/plugins/fancybox/source/jquery.fancybox.pack.js' }}" type="text/javascript"></script>
    <script src="{{ $CDN_PATH.'resources/global/plugins/fancybox/source/helpers/jquery.fancybox-media.js' }}" type="text/javascript"></script>
    <script src="{{ $CDN_PATH.'resources/global/plugins/highslide/highslide-with-html.js' }}" type="text/javascript"></script>
    @yield('cat_select2_config')
</body>
</html>