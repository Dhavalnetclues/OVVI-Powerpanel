<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>{{ Config::get('Constant.SITE_NAME') }}</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="" name="description"/>
        <meta content="" name="author" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
        <link href="https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300,700&display=swap" rel="stylesheet">
        <link href="{{ $CDN_PATH.'resources/global/plugins/bootstrap/css/bootstrap.min.css' }}" rel="stylesheet" type="text/css" />
        <link href="{{ $CDN_PATH.'resources/global/plugins/fancybox/source/jquery.fancybox.css' }}" rel="stylesheet" type="text/css"/>
        <link href="{{ $CDN_PATH.'resources/global/css/components-md.min.css' }}" rel="stylesheet" id="style_components" type="text/css" />
        <link href="{{ $CDN_PATH.'resources/layouts/layout4/css/custom.min.css' }}" rel="stylesheet" type="text/css" />
        <link href="{{ $CDN_PATH.'resources/global/css/colors.css' }}" rel="stylesheet" id="style_components" type="text/css" />
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
        </script>
        @php
        header("Cache-Control: private, must-revalidate, max-age=0, no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
        @endphp
    </head>
    <!-- END HEAD -->
    <body class="page-container-bg-solid page-header-fixed page-sidebar-closed-hide-logo page-md" >
        @if (Config::get('Constant.DEFAULT_AUTHENTICATION') == 'Y')
        @if ((Session::get('Authentication_User') == "Y"))
        @if ((Session::get('randomhistory_id') == ""))
        <div class="new_modal modal fade in" id="tncpopup" style="display:inherit">
            <div class="modal-dialog">
                <div class="modal-vertical">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Two Factor Authentication</h4>
                        </div>
                        {!! Form::open(['method' => 'post','class'=>'ac-form random_form']) !!}
                        <div class="modal-body form_pattern">

                            <div class="form-group ac-form-group">
                                {!! Form::text('random_code',  old('random_code') , array('id' => 'random_code', 'class' => 'form-control ac-input', 'maxlength'=>"6", 'minlength'=>"6", 'placeholder'=>'Please Enter Two Factor Authentication Code', 'onkeypress'=>'javascript: return KeycheckOnlyRendom(event);')) !!}
                            </div>
                            <div class="timersetdiv"> Timer:</div> <div id="clockdiv"></div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-green-drake" id="verify" title="Verify">Verify</button>
                            <button type="button" class="btn red btn-outline" id="tnc-rejectpopup"  title="Close">Close</button>
                        </div>
                        {!! Form::close() !!}
                    </div>   
                </div>
            </div>
        </div>
        @endif
        @endif
        @endif
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
        </form>
        <script src="{{ $CDN_PATH.'resources/global/plugins/jquery.min.js' }}" type="text/javascript"></script>
        <script src="{{ $CDN_PATH.'resources/global/plugins/bootstrap/js/bootstrap.min.js' }}" type="text/javascript"></script>
        <script type="text/javascript">
            $.ajaxSetup({headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}});
        </script>
        <script src="{{ $CDN_PATH.'resources/global/plugins/jquery-validation/js/jquery.validate.min.js' }}" type="text/javascript"></script>
        <script src="{{ $CDN_PATH.'resources/global/plugins/jquery-validation/js/additional-methods.min.js' }}" type="text/javascript"></script>
        <script src="{{ $CDN_PATH.'resources/pages/scripts/footer_ticket_validations.js' }}" type="text/javascript"></script>
        <script type="text/javascript">
            var user_account2 = true;
            if (user_account2) {
                var accepted = 'N';
                if (accepted == 'N')
                {
                    $('#tncpopup').modal({
                        backdrop: 'static',
                    });
                    $(document).bind("contextmenu", function (e) {
                        return false;
                    });
                }
            }
        </script>
        <script type="text/javascript">
            var time_in_minutes = {!!  Config::get('Constant.DEFAULT_Authentication_TIME')  !!};
            var current_time = Date.parse(new Date());
            var deadline = new Date(current_time + time_in_minutes * 60 * 1000);
            function time_remaining(endtime) {
                var t = Date.parse(endtime) - Date.parse(new Date());
                var seconds = Math.floor((t / 1000) % 60);
                var minutes = Math.floor((t / 1000 / 60) % 60);
                var hours = Math.floor((t / (1000 * 60 * 60)) % 24);
                var days = Math.floor(t / (1000 * 60 * 60 * 24));
                return {'total': t, 'days': days, 'hours': hours, 'minutes': minutes, 'seconds': seconds};
            }
            function run_clock(id, endtime) {
                var clock = document.getElementById(id);
                function update_clock() {
                    var t = time_remaining(endtime);
                    var timerdata = t.minutes + ':' + t.seconds;
                    clock.innerHTML = '<span class="timerclass">' + t.minutes + '</span>:<span class="timerclass">' + t.seconds + '</span>';
                    if (timerdata == '0:0') {
                        $("#tnc-rejectpopup").trigger("click");
                    }
                    if (t.total <= 0) {
                        clearInterval(timeinterval);
                    }
                }
                update_clock(); // run function once at first to avoid delay
                var timeinterval = setInterval(update_clock, 1000);
            }
            run_clock('clockdiv', deadline);
            $(document).on('click', '#tncpopup #tnc-rejectpopup', function (event) {
                event.preventDefault();
                document.getElementById('logout-form').submit();
            });
        </script>
    </body>
</html>