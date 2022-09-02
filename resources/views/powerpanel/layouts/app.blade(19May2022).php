
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg">
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
        
        <!-- APP FAVICON -->
        <link rel="shortcut icon" href="{{ $CDN_PATH.'assets/images/favicon.ico' }}">

        <!-- JSVECTORMAP CSS -->
        <link href="{{ $CDN_PATH.'resources/assets/libs/jsvectormap/css/jsvectormap.min.css?v='.time() }}" rel="stylesheet" type="text/css" />

        <!--SWIPER SLIDER CSS-->
        <link href="{{ $CDN_PATH.'resources/assets/libs/swiper/swiper-bundle.min.css?v='.time() }}" rel="stylesheet" type="text/css" />

        <!-- LAYOUT CONFIG JS -->
        <script src="{{ $CDN_PATH.'resources/assets/js/layout.js?v='.time() }}"></script>
        <!-- BOOTSTRAP CSS -->
        <link href="{{ $CDN_PATH.'resources/assets/css/bootstrap.min.css?v='.time() }}" rel="stylesheet" type="text/css" />
        <!-- ICONS CSS -->
        <link href="{{ $CDN_PATH.'resources/assets/css/icons.min.css?v='.time() }}" rel="stylesheet" type="text/css" />
        <!-- APP CSS-->
        <link href="{{ $CDN_PATH.'resources/assets/css/app.min.css?v='.time() }}" rel="stylesheet" type="text/css" />
        <!-- CUSTOM CSS-->
        <link href="{{ $CDN_PATH.'resources/assets/css/custom.min.css?v='.time() }}" rel="stylesheet" type="text/css" />
        
        <!-- Bootstrap Switch -->
        <!-- <link href="{{ $CDN_PATH.'resources/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css?v='.time() }}" rel="stylesheet" type="text/css" /> -->
        
        <link href="{{ $CDN_PATH.'resources/global/plugins/dropzone/dropzone.min.css' }}" rel="stylesheet" type="text/css"/>
				<link href="{{ $CDN_PATH.'resources/global/plugins/dropzone/basic.min.css' }}" rel="stylesheet" type="text/css" />

        <!-- BEGIN PAGE LEVEL CSS -->
        @yield('css')
        <!-- END PAGE LEVEL CSS -->

        <!-- <link href="{{ $CDN_PATH.'resources/global/plugins/select2/css/select2.min.css?v='.time() }}" rel="stylesheet" type="text/css"/>
        <link href="{{ $CDN_PATH.'resources/global/plugins/select2/css/select2-bootstrap.min.css?v='.time() }}" rel="stylesheet" type="text/css"/> -->

        <script>
            let clearcookie = '{{ $clearcookie }}';
        </script>
        <script>
            var CDN_PATH = "{{ $CDN_PATH }}";
            window.site_url = '{!! url("/") !!}';
            var rootUrl = window.site_url + "/";
            var BUCKET_ENABLED = "{{ $BUCKET_ENABLED }}";
            var formid = '<?php echo Request::segment(3); ?>';
            var formurl = '<?php echo Request::segment(4); ?>';
            var formpageurl = '<?php echo Request::segment(2); ?>';
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
    <body onload="display_ct();">
        
         <!-- Loader -->
        <div class="nq_loader nq_loader_up">
            <div class="nq_loader_container">
                <div class="lds-ripple"><div></div><div></div></div>
            </div>
        </div>
        <!-- Begin page -->
        <div id="layout-wrapper">
            <!-- <div class="message_loader" style="display:none;">
                <div class="cell_conter">
                    <img src="{{ $CDN_PATH.'assets/images/message_loader.svg' }}" alt="loader">
                </div>
            </div> -->
            <!-- BEGIN HEADER -->
            @include('powerpanel.partials.header')

            <!-- BEGIN SIDEBAR -->
            @include('powerpanel.partials.sidebar')
            <!-- END SIDEBAR -->
                
            <!-- Vertical Overlay-->
            <div class="vertical-overlay"></div>
            
            <!-- ============================================================== -->
            <!-- Start Content -->
            <!-- ============================================================== -->
            <div class="main-content">
                <div class="page-content">
                    <div class="container-fluid">
                        @yield('content')
                        @include('powerpanel.media_manager.gallery_component')
                        @include('powerpanel.media_manager.audios_component')
                        @if(isset($videoManager))
                        @include('powerpanel.media_manager.video_component')
                        @endif
                        @include('powerpanel.media_manager.documents_component')
                    </div>
                    <!-- container-fluid -->
                </div>
                <!-- End Page-content -->

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
                
                @include('powerpanel.partials.footer')
            </div>
            <!-- end main content-->

        </div>
        <!-- END layout-wrapper -->

        <!--start back-to-top-->
        <button onclick="topFunction()" class="btn btn-danger btn-icon" id="back-to-top">
            <i class="ri-arrow-up-line"></i>
        </button>
        <!--end back-to-top-->

        <!-- FIXED FORM -->
        <!-- <div class="fixed_from" id="Test">
            <a class="title_fixed title_icon_ticket" title="Submit a Ticket" href="javascript:void(0)"><i class="ri-ticket-2-line"></i></a>
            {!! Form::open(['method' => 'post','id'=>'Ticket_Form','name'=>'Ticket_Form','url'=>url('powerpanel/settings/insertticket'),'enctype'=>'multipart/form-data']) !!}

            <div class="bma_form">
                <h4>Submit a Ticket</h4>
                <div class="mb-3">
                    {!! Form::text('Name',$SecurityUser->name, array('id' => 'Name', 'class' => 'form-control', 'placeholder'=>'Enter Your Name' ,'readonly')) !!}
                    @if($errors->has('Name'))
                    <span class="help-block">{{ $errors->first('Name') }}</span>
                    @endif
                </div>
                <div class="mb-3">
                    <select class="form-control bs-select select2" name="varType" id="varType">
                        <option value="">Type</option>
                        <option value="1">Fixes / Issues</option>
                        <option value="2">Changes</option>
                        <option value="3">Suggestion</option>
                        <option value="4">New Features</option>
                    </select>
                    @if($errors->has('varType'))
                    <span class="help-block">{{ $errors->first('varType') }}</span>
                    @endif
                </div>
                <div class="mb-3">
                    {!! Form::textarea('varMessage', old('varMessage') , array( 'class' => 'form-control', 'cols' => '20', 'rows' => '3', 'id' => 'varMessage', 'spellcheck' => 'true','placeholder'=>'Enter Your Message' )) !!}
                    @if($errors->has('varMessage'))
                    <span class="help-block">{{ $errors->first('varMessage') }}</span>
                    @endif
                </div>
                <div class="mb-3">
                    <div class="row row_file">
                        <div class="col-sm-6 fkimg_val">
                            <div class="js-inputbox">
                                <input type="file" name="file-1[]" id="file-1" class="inputfile inputfile-1 fkimg_val" data-multiple-caption="{count} files selected" multiple />
                                <label for="file-1"><svg xmlns="https://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"/></svg> <span>Select a file&hellip;</span></label>
                            </div>
                            @if($errors->has('file-1'))
                            <span class="help-block">{{ $errors->first('file-1') }}</span>
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
                <div class="mb-3">
                    <label>Link:</label>
                    <input type="text" name="Link" id="Link" value="{{ "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']}}" placeholder="Enter Your Link." readonly class="form-control">
                    @if($errors->has('Link'))
                    <span class="help-block">{{ $errors->first('Link') }}</span>
                    @endif
                </div>
                <div class="mb-3 text-center">
                    <button title="Submit" class="btn btn-primary btn_fixed">Submit</button>
                </div>
            </div>
            {!! Form::close() !!}
        </div> -->
        
        <div class="customizer-setting d-none d-md-block">
            <div class="btn-info btn-rounded shadow-lg btn btn-icon btn-lg p-2" data-bs-toggle="offcanvas"
                data-bs-target="#theme-settings-offcanvas" aria-controls="theme-settings-offcanvas">
                <i class='mdi mdi-spin mdi-cog-outline fs-22'></i>
            </div>
        </div>

        <!-- Theme Settings -->
        <div class="offcanvas offcanvas-end border-0" tabindex="-1" id="theme-settings-offcanvas">
            <div class="d-flex align-items-center bg-primary bg-gradient p-3 offcanvas-header">
                <h5 class="m-0 me-2 text-white">Theme Customizer</h5>

                <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="offcanvas"
                    aria-label="Close"></button>
            </div>
            <div class="offcanvas-body p-0">
                <div data-simplebar class="h-100">
                    <div class="p-4">
                        <h6 class="mb-0 fw-semibold text-uppercase">Layout</h6>
                        <p class="text-muted">Choose your layout</p>

                        <div class="row">
                            <div class="col-4">
                                <div class="form-check card-radio">
                                    <input id="customizer-layout01" name="data-layout" type="radio" value="vertical"
                                        class="form-check-input">
                                    <label class="form-check-label p-0 avatar-md w-100" for="customizer-layout01">
                                        <span class="d-flex gap-1 h-100">
                                            <span class="flex-shrink-0">
                                                <span class="bg-light d-flex h-100 flex-column gap-1 p-1">
                                                    <span class="d-block p-1 px-2 bg-soft-primary rounded mb-2"></span>
                                                    <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                    <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                    <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                </span>
                                            </span>
                                            <span class="flex-grow-1">
                                                <span class="d-flex h-100 flex-column">
                                                    <span class="bg-light d-block p-1"></span>
                                                    <span class="bg-light d-block p-1 mt-auto"></span>
                                                </span>
                                            </span>
                                        </span>
                                    </label>
                                </div>
                                <h5 class="fs-13 text-center mt-2">Vertical</h5>
                            </div>
                            <div class="col-4">
                                <div class="form-check card-radio">
                                    <input id="customizer-layout02" name="data-layout" type="radio" value="horizontal"
                                        class="form-check-input">
                                    <label class="form-check-label p-0 avatar-md w-100" for="customizer-layout02">
                                        <span class="d-flex h-100 flex-column gap-1">
                                            <span class="bg-light d-flex p-1 gap-1 align-items-center">
                                                <span class="d-block p-1 bg-soft-primary rounded me-1"></span>
                                                <span class="d-block p-1 pb-0 px-2 bg-soft-primary ms-auto"></span>
                                                <span class="d-block p-1 pb-0 px-2 bg-soft-primary"></span>
                                            </span>
                                            <span class="bg-light d-block p-1"></span>
                                            <span class="bg-light d-block p-1 mt-auto"></span>
                                        </span>
                                    </label>
                                </div>
                                <h5 class="fs-13 text-center mt-2">Horizontal</h5>
                            </div>
                            <div class="col-4">
                                <div class="form-check card-radio">
                                    <input id="customizer-layout03" name="data-layout" type="radio" value="twocolumn"
                                        class="form-check-input">
                                    <label class="form-check-label p-0 avatar-md w-100" for="customizer-layout03">
                                        <span class="d-flex gap-1 h-100">
                                            <span class="flex-shrink-0">
                                                <span class="bg-light d-flex h-100 flex-column gap-1">
                                                    <span class="d-block p-1 bg-soft-primary mb-2"></span>
                                                    <span class="d-block p-1 pb-0 bg-soft-primary"></span>
                                                    <span class="d-block p-1 pb-0 bg-soft-primary"></span>
                                                    <span class="d-block p-1 pb-0 bg-soft-primary"></span>
                                                </span>
                                            </span>
                                            <span class="flex-shrink-0">
                                                <span class="bg-light d-flex h-100 flex-column gap-1 p-1">
                                                    <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                    <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                    <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                    <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                </span>
                                            </span>
                                            <span class="flex-grow-1">
                                                <span class="d-flex h-100 flex-column">
                                                    <span class="bg-light d-block p-1"></span>
                                                    <span class="bg-light d-block p-1 mt-auto"></span>
                                                </span>
                                            </span>
                                        </span>
                                    </label>
                                </div>
                                <h5 class="fs-13 text-center mt-2">Two Column</h5>
                            </div>
                            <!-- end col -->
                        </div>

                        <h6 class="mt-4 mb-0 fw-semibold text-uppercase">Color Scheme</h6>
                        <p class="text-muted">Choose Light or Dark Scheme.</p>

                        <div class="colorscheme-cardradio">
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-check card-radio">
                                        <input class="form-check-input" type="radio" name="data-layout-mode"
                                            id="layout-mode-light" value="light">
                                        <label class="form-check-label p-0 avatar-md w-100" for="layout-mode-light">
                                            <span class="d-flex gap-1 h-100">
                                                <span class="flex-shrink-0">
                                                    <span class="bg-light d-flex h-100 flex-column gap-1 p-1">
                                                        <span class="d-block p-1 px-2 bg-soft-primary rounded mb-2"></span>
                                                        <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                        <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                        <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                    </span>
                                                </span>
                                                <span class="flex-grow-1">
                                                    <span class="d-flex h-100 flex-column">
                                                        <span class="bg-light d-block p-1"></span>
                                                        <span class="bg-light d-block p-1 mt-auto"></span>
                                                    </span>
                                                </span>
                                            </span>
                                        </label>
                                    </div>
                                    <h5 class="fs-13 text-center mt-2">Light</h5>
                                </div>

                                <div class="col-4">
                                    <div class="form-check card-radio dark">
                                        <input class="form-check-input" type="radio" name="data-layout-mode"
                                            id="layout-mode-dark" value="dark">
                                        <label class="form-check-label p-0 avatar-md w-100 bg-dark" for="layout-mode-dark">
                                            <span class="d-flex gap-1 h-100">
                                                <span class="flex-shrink-0">
                                                    <span class="bg-soft-light d-flex h-100 flex-column gap-1 p-1">
                                                        <span class="d-block p-1 px-2 bg-soft-light rounded mb-2"></span>
                                                        <span class="d-block p-1 px-2 pb-0 bg-soft-light"></span>
                                                        <span class="d-block p-1 px-2 pb-0 bg-soft-light"></span>
                                                        <span class="d-block p-1 px-2 pb-0 bg-soft-light"></span>
                                                    </span>
                                                </span>
                                                <span class="flex-grow-1">
                                                    <span class="d-flex h-100 flex-column">
                                                        <span class="bg-soft-light d-block p-1"></span>
                                                        <span class="bg-soft-light d-block p-1 mt-auto"></span>
                                                    </span>
                                                </span>
                                            </span>
                                        </label>
                                    </div>
                                    <h5 class="fs-13 text-center mt-2">Dark</h5>
                                </div>
                            </div>
                        </div>

                        <div id="layout-width">
                            <h6 class="mt-4 mb-0 fw-semibold text-uppercase">Layout Width</h6>
                            <p class="text-muted">Choose Fluid or Boxed layout.</p>

                            <div class="row">
                                <div class="col-4">
                                    <div class="form-check card-radio">
                                        <input class="form-check-input" type="radio" name="data-layout-width"
                                            id="layout-width-fluid" value="fluid">
                                        <label class="form-check-label p-0 avatar-md w-100" for="layout-width-fluid">
                                            <span class="d-flex gap-1 h-100">
                                                <span class="flex-shrink-0">
                                                    <span class="bg-light d-flex h-100 flex-column gap-1 p-1">
                                                        <span class="d-block p-1 px-2 bg-soft-primary rounded mb-2"></span>
                                                        <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                        <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                        <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                    </span>
                                                </span>
                                                <span class="flex-grow-1">
                                                    <span class="d-flex h-100 flex-column">
                                                        <span class="bg-light d-block p-1"></span>
                                                        <span class="bg-light d-block p-1 mt-auto"></span>
                                                    </span>
                                                </span>
                                            </span>
                                        </label>
                                    </div>
                                    <h5 class="fs-13 text-center mt-2">Fluid</h5>
                                </div>
                                <div class="col-4">
                                    <div class="form-check card-radio">
                                        <input class="form-check-input" type="radio" name="data-layout-width"
                                            id="layout-width-boxed" value="boxed">
                                        <label class="form-check-label p-0 avatar-md w-100 px-2" for="layout-width-boxed">
                                            <span class="d-flex gap-1 h-100 border-start border-end">
                                                <span class="flex-shrink-0">
                                                    <span class="bg-light d-flex h-100 flex-column gap-1 p-1">
                                                        <span class="d-block p-1 px-2 bg-soft-primary rounded mb-2"></span>
                                                        <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                        <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                        <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                    </span>
                                                </span>
                                                <span class="flex-grow-1">
                                                    <span class="d-flex h-100 flex-column">
                                                        <span class="bg-light d-block p-1"></span>
                                                        <span class="bg-light d-block p-1 mt-auto"></span>
                                                    </span>
                                                </span>
                                            </span>
                                        </label>
                                    </div>
                                    <h5 class="fs-13 text-center mt-2">Boxed</h5>
                                </div>
                            </div>
                        </div>

                        <div id="layout-position">
                            <h6 class="mt-4 mb-0 fw-semibold text-uppercase">Layout Position</h6>
                            <p class="text-muted">Choose Fixed or Scrollable Layout Position.</p>

                            <div class="btn-group radio" role="group">
                                <input type="radio" class="btn-check" name="data-layout-position" id="layout-position-fixed"
                                    value="fixed">
                                <label class="btn btn-light w-sm" for="layout-position-fixed">Fixed</label>

                                <input type="radio" class="btn-check" name="data-layout-position"
                                    id="layout-position-scrollable" value="scrollable">
                                <label class="btn btn-light w-sm ms-0" for="layout-position-scrollable">Scrollable</label>
                            </div>
                        </div>
                        <h6 class="mt-4 mb-0 fw-semibold text-uppercase">Topbar Color</h6>
                        <p class="text-muted">Choose Light or Dark Topbar Color.</p>

                        <div class="row">
                            <div class="col-4">
                                <div class="form-check card-radio">
                                    <input class="form-check-input" type="radio" name="data-topbar" id="topbar-color-light"
                                        value="light">
                                    <label class="form-check-label p-0 avatar-md w-100" for="topbar-color-light">
                                        <span class="d-flex gap-1 h-100">
                                            <span class="flex-shrink-0">
                                                <span class="bg-light d-flex h-100 flex-column gap-1 p-1">
                                                    <span class="d-block p-1 px-2 bg-soft-primary rounded mb-2"></span>
                                                    <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                    <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                    <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                </span>
                                            </span>
                                            <span class="flex-grow-1">
                                                <span class="d-flex h-100 flex-column">
                                                    <span class="bg-light d-block p-1"></span>
                                                    <span class="bg-light d-block p-1 mt-auto"></span>
                                                </span>
                                            </span>
                                        </span>
                                    </label>
                                </div>
                                <h5 class="fs-13 text-center mt-2">Light</h5>
                            </div>
                            <div class="col-4">
                                <div class="form-check card-radio">
                                    <input class="form-check-input" type="radio" name="data-topbar" id="topbar-color-dark"
                                        value="dark">
                                    <label class="form-check-label p-0 avatar-md w-100" for="topbar-color-dark">
                                        <span class="d-flex gap-1 h-100">
                                            <span class="flex-shrink-0">
                                                <span class="bg-light d-flex h-100 flex-column gap-1 p-1">
                                                    <span class="d-block p-1 px-2 bg-soft-primary rounded mb-2"></span>
                                                    <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                    <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                    <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                </span>
                                            </span>
                                            <span class="flex-grow-1">
                                                <span class="d-flex h-100 flex-column">
                                                    <span class="bg-primary d-block p-1"></span>
                                                    <span class="bg-light d-block p-1 mt-auto"></span>
                                                </span>
                                            </span>
                                        </span>
                                    </label>
                                </div>
                                <h5 class="fs-13 text-center mt-2">Dark</h5>
                            </div>
                        </div>

                        <div id="sidebar-size">
                            <h6 class="mt-4 mb-0 fw-semibold text-uppercase">Sidebar Size</h6>
                            <p class="text-muted">Choose a size of Sidebar.</p>

                            <div class="row">
                                <div class="col-4">
                                    <div class="form-check sidebar-setting card-radio">
                                        <input class="form-check-input" type="radio" name="data-sidebar-size"
                                            id="sidebar-size-default" value="lg">
                                        <label class="form-check-label p-0 avatar-md w-100" for="sidebar-size-default">
                                            <span class="d-flex gap-1 h-100">
                                                <span class="flex-shrink-0">
                                                    <span class="bg-light d-flex h-100 flex-column gap-1 p-1">
                                                        <span class="d-block p-1 px-2 bg-soft-primary rounded mb-2"></span>
                                                        <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                        <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                        <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                    </span>
                                                </span>
                                                <span class="flex-grow-1">
                                                    <span class="d-flex h-100 flex-column">
                                                        <span class="bg-light d-block p-1"></span>
                                                        <span class="bg-light d-block p-1 mt-auto"></span>
                                                    </span>
                                                </span>
                                            </span>
                                        </label>
                                    </div>
                                    <h5 class="fs-13 text-center mt-2">Default</h5>
                                </div>

                                <div class="col-4">
                                    <div class="form-check sidebar-setting card-radio">
                                        <input class="form-check-input" type="radio" name="data-sidebar-size"
                                            id="sidebar-size-compact" value="md">
                                        <label class="form-check-label p-0 avatar-md w-100" for="sidebar-size-compact">
                                            <span class="d-flex gap-1 h-100">
                                                <span class="flex-shrink-0">
                                                    <span class="bg-light d-flex h-100 flex-column gap-1 p-1">
                                                        <span class="d-block p-1 bg-soft-primary rounded mb-2"></span>
                                                        <span class="d-block p-1 pb-0 bg-soft-primary"></span>
                                                        <span class="d-block p-1 pb-0 bg-soft-primary"></span>
                                                        <span class="d-block p-1 pb-0 bg-soft-primary"></span>
                                                    </span>
                                                </span>
                                                <span class="flex-grow-1">
                                                    <span class="d-flex h-100 flex-column">
                                                        <span class="bg-light d-block p-1"></span>
                                                        <span class="bg-light d-block p-1 mt-auto"></span>
                                                    </span>
                                                </span>
                                            </span>
                                        </label>
                                    </div>
                                    <h5 class="fs-13 text-center mt-2">Compact</h5>
                                </div>

                                <div class="col-4">
                                    <div class="form-check sidebar-setting card-radio">
                                        <input class="form-check-input" type="radio" name="data-sidebar-size"
                                            id="sidebar-size-small" value="sm">
                                        <label class="form-check-label p-0 avatar-md w-100" for="sidebar-size-small">
                                            <span class="d-flex gap-1 h-100">
                                                <span class="flex-shrink-0">
                                                    <span class="bg-light d-flex h-100 flex-column gap-1">
                                                        <span class="d-block p-1 bg-soft-primary mb-2"></span>
                                                        <span class="d-block p-1 pb-0 bg-soft-primary"></span>
                                                        <span class="d-block p-1 pb-0 bg-soft-primary"></span>
                                                        <span class="d-block p-1 pb-0 bg-soft-primary"></span>
                                                    </span>
                                                </span>
                                                <span class="flex-grow-1">
                                                    <span class="d-flex h-100 flex-column">
                                                        <span class="bg-light d-block p-1"></span>
                                                        <span class="bg-light d-block p-1 mt-auto"></span>
                                                    </span>
                                                </span>
                                            </span>
                                        </label>
                                    </div>
                                    <h5 class="fs-13 text-center mt-2">Small (Icon View)</h5>
                                </div>

                                <div class="col-4">
                                    <div class="form-check sidebar-setting card-radio">
                                        <input class="form-check-input" type="radio" name="data-sidebar-size"
                                            id="sidebar-size-small-hover" value="sm-hover">
                                        <label class="form-check-label p-0 avatar-md w-100" for="sidebar-size-small-hover">
                                            <span class="d-flex gap-1 h-100">
                                                <span class="flex-shrink-0">
                                                    <span class="bg-light d-flex h-100 flex-column gap-1">
                                                        <span class="d-block p-1 bg-soft-primary mb-2"></span>
                                                        <span class="d-block p-1 pb-0 bg-soft-primary"></span>
                                                        <span class="d-block p-1 pb-0 bg-soft-primary"></span>
                                                        <span class="d-block p-1 pb-0 bg-soft-primary"></span>
                                                    </span>
                                                </span>
                                                <span class="flex-grow-1">
                                                    <span class="d-flex h-100 flex-column">
                                                        <span class="bg-light d-block p-1"></span>
                                                        <span class="bg-light d-block p-1 mt-auto"></span>
                                                    </span>
                                                </span>
                                            </span>
                                        </label>
                                    </div>
                                    <h5 class="fs-13 text-center mt-2">Small Hover View</h5>
                                </div>
                            </div>
                        </div>

                        <div id="sidebar-view">
                            <h6 class="mt-4 mb-0 fw-semibold text-uppercase">Sidebar View</h6>
                            <p class="text-muted">Choose Default or Detached Sidebar view.</p>

                            <div class="row">
                                <div class="col-4">
                                    <div class="form-check sidebar-setting card-radio">
                                        <input class="form-check-input" type="radio" name="data-layout-style"
                                            id="sidebar-view-default" value="default">
                                        <label class="form-check-label p-0 avatar-md w-100" for="sidebar-view-default">
                                            <span class="d-flex gap-1 h-100">
                                                <span class="flex-shrink-0">
                                                    <span class="bg-light d-flex h-100 flex-column gap-1 p-1">
                                                        <span class="d-block p-1 px-2 bg-soft-primary rounded mb-2"></span>
                                                        <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                        <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                        <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                    </span>
                                                </span>
                                                <span class="flex-grow-1">
                                                    <span class="d-flex h-100 flex-column">
                                                        <span class="bg-light d-block p-1"></span>
                                                        <span class="bg-light d-block p-1 mt-auto"></span>
                                                    </span>
                                                </span>
                                            </span>
                                        </label>
                                    </div>
                                    <h5 class="fs-13 text-center mt-2">Default</h5>
                                </div>
                                <div class="col-4">
                                    <div class="form-check sidebar-setting card-radio">
                                        <input class="form-check-input" type="radio" name="data-layout-style"
                                            id="sidebar-view-detached" value="detached">
                                        <label class="form-check-label p-0 avatar-md w-100" for="sidebar-view-detached">
                                            <span class="d-flex h-100 flex-column">
                                                <span class="bg-light d-flex p-1 gap-1 align-items-center px-2">
                                                    <span class="d-block p-1 bg-soft-primary rounded me-1"></span>
                                                    <span class="d-block p-1 pb-0 px-2 bg-soft-primary ms-auto"></span>
                                                    <span class="d-block p-1 pb-0 px-2 bg-soft-primary"></span>
                                                </span>
                                                <span class="d-flex gap-1 h-100 p-1 px-2">
                                                    <span class="flex-shrink-0">
                                                        <span class="bg-light d-flex h-100 flex-column gap-1 p-1">
                                                            <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                            <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                            <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                        </span>
                                                    </span>
                                                </span>
                                                <span class="bg-light d-block p-1 mt-auto px-2"></span>
                                            </span>
                                        </label>
                                    </div>
                                    <h5 class="fs-13 text-center mt-2">Detached</h5>
                                </div>
                            </div>
                        </div>
                        <div id="sidebar-color">
                            <h6 class="mt-4 mb-0 fw-semibold text-uppercase">Sidebar Color</h6>
                            <p class="text-muted">Choose Ligth or Dark Sidebar Color.</p>

                            <div class="row">
                                <div class="col-4">
                                    <div class="form-check sidebar-setting card-radio">
                                        <input class="form-check-input" type="radio" name="data-sidebar"
                                            id="sidebar-color-light" value="light">
                                        <label class="form-check-label p-0 avatar-md w-100" for="sidebar-color-light">
                                            <span class="d-flex gap-1 h-100">
                                                <span class="flex-shrink-0">
                                                    <span class="bg-white border-end d-flex h-100 flex-column gap-1 p-1">
                                                        <span class="d-block p-1 px-2 bg-soft-primary rounded mb-2"></span>
                                                        <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                        <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                        <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                    </span>
                                                </span>
                                                <span class="flex-grow-1">
                                                    <span class="d-flex h-100 flex-column">
                                                        <span class="bg-light d-block p-1"></span>
                                                        <span class="bg-light d-block p-1 mt-auto"></span>
                                                    </span>
                                                </span>
                                            </span>
                                        </label>
                                    </div>
                                    <h5 class="fs-13 text-center mt-2">Light</h5>
                                </div>
                                <div class="col-4">
                                    <div class="form-check sidebar-setting card-radio">
                                        <input class="form-check-input" type="radio" name="data-sidebar" id="sidebar-color-dark"
                                            value="dark">
                                        <label class="form-check-label p-0 avatar-md w-100" for="sidebar-color-dark">
                                            <span class="d-flex gap-1 h-100">
                                                <span class="flex-shrink-0">
                                                    <span class="bg-primary d-flex h-100 flex-column gap-1 p-1">
                                                        <span class="d-block p-1 px-2 bg-soft-light rounded mb-2"></span>
                                                        <span class="d-block p-1 px-2 pb-0 bg-soft-light"></span>
                                                        <span class="d-block p-1 px-2 pb-0 bg-soft-light"></span>
                                                        <span class="d-block p-1 px-2 pb-0 bg-soft-light"></span>
                                                    </span>
                                                </span>
                                                <span class="flex-grow-1">
                                                    <span class="d-flex h-100 flex-column">
                                                        <span class="bg-light d-block p-1"></span>
                                                        <span class="bg-light d-block p-1 mt-auto"></span>
                                                    </span>
                                                </span>
                                            </span>
                                        </label>
                                    </div>
                                    <h5 class="fs-13 text-center mt-2">Dark</h5>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
            <div class="offcanvas-footer border-top p-3 text-center">
                <div class="row">
                    <div class="col-12">
                        <button type="button" class="btn btn-primary w-100" id="reset-layout">Reset</button>
                    </div>
                </div>
            </div>
        </div>
        
        <script type="text/javascript">
            $(document).ready(function () {
                $('.nq_loader').addClass('nq_loader_up');
            });
        </script>

        <script type="text/javascript">
            var CKEDITOR_APP_URL = '{{ env("APP_URL") }}';
            //var settings = JSON.parse('{!! Config::get("Constant.MODULE.SETTINGS") !!}');
            var user_account = false;
            @if (!$userIsAdmin)
                user_account = true;
            @endif
                var super_admin = false;
            @role('netquick_admin')
                super_admin = true;
            @endrole
                var termsAccepted = "{{ $termsAccepted }}";
        </script>

        <!-- JAVASCRIPT -->
        <script src="{{ $CDN_PATH.'resources/assets/libs/bootstrap/js/bootstrap.bundle.min.js?v='.time() }}"></script>
        <script src="{{ $CDN_PATH.'resources/assets/libs/simplebar/simplebar.min.js?v='.time() }}"></script>
        <script src="{{ $CDN_PATH.'resources/assets/libs/node-waves/waves.min.js?v='.time() }}"></script>
        <script src="{{ $CDN_PATH.'resources/assets/libs/feather-icons/feather.min.js?v='.time() }}"></script>
        <script src="{{ $CDN_PATH.'resources/assets/js/pages/plugins/lord-icon-2.1.0.js?v='.time() }}"></script>
        <script src="{{ $CDN_PATH.'resources/assets/js/plugins.js?v='.time() }}"></script>

        <!-- apexcharts -->
        <script src="{{ $CDN_PATH.'resources/assets/libs/apexcharts/apexcharts.min.js?v='.time() }}"></script>
        
        <!-- prismjs plugin -->
        <script src="{{ $CDN_PATH.'resources/assets/libs/prismjs/prism.js?v='.time() }}"></script>

        <!-- Vector map-->
        <script src="{{ $CDN_PATH.'resources/assets/libs/jsvectormap/js/jsvectormap.min.js?v='.time() }}"></script>
        <script src="{{ $CDN_PATH.'resources/assets/libs/jsvectormap/maps/world-merc.js?v='.time() }}"></script>

        <!--Swiper slider js-->
        <script src="{{ $CDN_PATH.'resources/assets/libs/swiper/swiper-bundle.min.js?v='.time() }}"></script>

        <!-- App js -->
        <script src="{{ $CDN_PATH.'resources/assets/js/app.js?v='.time() }}"></script>

        <script type="text/javascript">
            $.ajaxSetup({headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}});
        </script>

        <!-- OLD JAVASCRIPT -->
        <!-- <script src="{{ $CDN_PATH.'resources/global/plugins/mcscroll/jquery.mCustomScrollbar.concat.min.js?v='.time() }}" type="text/javascript"></script>
        <script src="{{ $CDN_PATH.'resources/pages/scripts/footer_ticket_validations.js?v='.time() }}" type="text/javascript"></script>
        <script src="{{ $CDN_PATH.'resources/pages/scripts/custom_js.js?v='.time() }}" type="text/javascript"></script>
        <script src="{{ $CDN_PATH.'resources/global/plugins/loading/loading.js?v='.time() }}" type="text/javascript"></script> -->
        <script src="{{ $CDN_PATH.'resources/global/plugins/menu-loader/jquery-loader.js?v='.time() }}" type="text/javascript"></script>
        <script src="{{ $CDN_PATH.'messages.js?v='.time() }}" type="text/javascript"></script>
        
        <!-- Validate JS -->
        <script src="{{ $CDN_PATH.'resources/global/plugins/jquery-validation/js/jquery.validate.min.js?v='.time() }}" type="text/javascript"></script>
        <script src="{{ $CDN_PATH.'resources/global/plugins/jquery-validation/js/additional-methods.min.js?v='.time() }}" type="text/javascript"></script>

        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="{{ $CDN_PATH.'resources/global/scripts/app.js?v='.time() }}" type="text/javascript"></script>
        
        @if(Request::segment(3) != 'add' && Request::segment(4) != 'edit')
        <script src="{{ $CDN_PATH.'resources/pages/scripts/table-grid-quick-fun-ajax.js?v='.time() }}" type="text/javascript"></script>
        @endif
        <!-- <script src="{{ $CDN_PATH.'resources/global/plugins/moment.min.js?v='.time() }}"></script>
        <script src="{{ $CDN_PATH.'resources/global/plugins/moments-timezone.js?v='.time() }}"></script>
        <script src="{{ $CDN_PATH.'resources/global/plugins/fancybox/source/jquery.fancybox.pack.js?v='.time() }}" type="text/javascript"></script>
        <script src="{{ $CDN_PATH.'resources/global/plugins/fancybox/source/helpers/jquery.fancybox-media.js?v='.time() }}" type="text/javascript"></script>
        <script src="{{ $CDN_PATH.'resources/global/plugins/highslide/highslide-with-html.js?v='.time() }}" type="text/javascript"></script> -->
        
        <script src="{{ $CDN_PATH.'resources/global/scripts/media_manager.js?v='.time() }}" type="text/javascript"></script>

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
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl)
                })
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
                let
                        region = document.querySelector("body"); // whole screen
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
                        let
                                pngUrl = canvas.toDataURL();
                        let
                                img = document.querySelector(".screen");
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

        @yield('cat_select2_config')

        <script type="text/javascript">
            $.ajaxSetup({headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}});
            $(document).ajaxComplete(function(event, jqxhr, settings) {
                if(jqxhr.status == 401) {
                    window.location.href = window.site_url + '/powerpanel/login';
                }
            });
        </script>

        <script src="{{ $CDN_PATH.'resources/global/plugins/select2/js/html2canvas.min.js' }}" type="text/javascript"></script>
       <script src="{{url('cdn/assets/global/plugins/bootstrap-toastr/toastr.js')}}"></script>
       <script src="{{ url('cdn/resources/global/plugins/dropzone/dropzone.min.js') }}" type="text/javascript"></script>
			 <script src="{{ url('cdn/resources/pages/scripts/form-dropzone.js') }}" type="text/javascript"></script>

        @yield('scripts')

        <script type="text/javascript">
            // jQuery.validator.addMethod("noSpace", function (value, element) {
            //     if (value.trim().length <= 0) {
            //         return false;
            //     } else {
            //         return true;
            //     }
            // }, "No space please and don't leave it empty");
            // $(document).on('focusout', 'textarea', function () {
            //     if ($(this).parents('form').hasClass('CommentsForm')) {
            //         var textvalue = $.trim($(this).val());
            //         $(this).val('');
            //         $(this).val(textvalue);
            //     }
            // });
            
            // $("#slim_notification").mCustomScrollbar({
            //     // axis: "y",
            //     theme: "minimal-dark",
            // });
            
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
            // $(".bootstrap-select .dropdown-toggle").on("click", function () {
            //     $(".bootstrap-select").addClass("open");
            // });
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
                        // $("#slim_notification").mCustomScrollbar({
                        //     axis: "y",
                        //     theme: "minimal-dark"
                        // });
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
    </body>
</html>