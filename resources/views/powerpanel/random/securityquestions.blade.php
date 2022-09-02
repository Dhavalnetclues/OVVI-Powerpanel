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
        @php
        $userid = auth()->user()->id;
        $LoginLog = \App\LoginLog::getSecurity_NewIp_Count();
        $SecurityUser = \App\User::getRecordById($userid);
        $effectiveDate = strtotime(date('Y-m-d', strtotime("+" . $SecurityUser['varMonth'] . " months", strtotime($SecurityUser['SecurityQuestions_start_date']))));
        $curentDate= strtotime(date('Y-m-d'));
        @endphp
        <div class="new_modal modal fade" id="securitypopup">
            <div class="modal-dialog">
                <div class="modal-vertical">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Security Questions</h4>
                        </div>
                        {!! Form::open(['method' => 'post','class'=>'ac-form security_questions_form']) !!}
                        <div class="modal-body form_pattern">
                            @php
                            $SecurityQuestion = \App\User::GetSecurityQuestion_Random($SecurityUser['varQuestion1'],$SecurityUser['varQuestion2'],$SecurityUser['varQuestion3']);
                            if($SecurityQuestion->id == $SecurityUser['varQuestion1']){
                            $answer = 'varAnswer1';
                            }else if($SecurityQuestion->id == $SecurityUser['varQuestion2']){
                            $answer = 'varAnswer2';
                            }else if($SecurityQuestion->id == $SecurityUser['varQuestion3']){
                            $answer = 'varAnswer3';
                            }
                            @endphp
                            <label>{{$SecurityQuestion->var_questions}}</label>
                            <div class="form-group ac-form-group">
                                <input type="hidden" name="QuestionId" id="QuestionId" value="{{$answer}}"/>
                                <input maxlength="100" class="form-control maxlength-handler" autocomplete="off" name="SecurityAnswer" id="SecurityAnswer" placeholder="Please Enter Answer" type="text">
                                <div class="help-block" id="not_match"></div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn red btn-outline" id="tnc-rejectpopup-security"  title="Close">Close</button>
                            <button class="btn btn-green-drake" id="verify" title="Verify">Verify</button>
                        </div>
                        {!! Form::close() !!}
                    </div>   
                </div>
            </div>
        </div>
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
            var user_account3 = true;
            if (user_account3) {
                var accepted = 'N';
                if (accepted == 'N')
                {
                    $('#securitypopup').modal({
                        backdrop: 'static',
                    });
                    $(document).bind("contextmenu", function (e) {
                        return false;
                    });
                }
            }
        </script>
        <script type="text/javascript">
            $(document).on('click', '#securitypopup #tnc-rejectpopup-security', function (event) {
                event.preventDefault();
                document.getElementById('logout-form').submit();
            });
        </script>
    </body>
</html>