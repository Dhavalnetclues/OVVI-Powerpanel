@extends('powerpanel.layouts.app')
@section('title')
{{Config::get('Constant.SITE_NAME')}} - PowerPanel
@endsection
@section('css')
<!-- <link href="{{ $CDN_PATH.'resources/global/plugins/datatables/datatables.min.css' }}" rel="stylesheet" type="text/css" /> -->
<link href="{{ $CDN_PATH.'resources/global/plugins/highslide/highslide.css' }}" rel="stylesheet" type="text/css" />
<style type="text/css">
    .dataTables_filter, .dataTables_info { display: none; }
    /*.badge-danger{background: #D33600!important;}*/
</style>
@endsection

@section('content')
<!-- start page title -->
<!-- <div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <div class="page-title-right gridsetting">
                @if(!empty($dashboardWidgetSettings))
                    <div class="dropdown card-header-dropdown">
                        <a class="text-reset dropdown-btn" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="text-muted">
                                <i class="ri-settings-4-line align-bottom me-1"></i>
                            </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end" id="grpChkBox">
                        @foreach($dashboardWidgetSettings as $widget_key => $widget_value)
                            @php
                            $settingChecked ='';
                            if($widget_value->widget_display=="Y"){
                            $settingChecked ='checked="checked"';	
                            }
                            @endphp
                            @if($widget_value->widget_id != 'widget_avl_workflow' && $widget_value->widget_id != 'widget_pending_workflow')
                                <a class="dropdown-item">
                                    <input class="form-check-input dashboard_checkbox" value="{{ $widget_key }}" type="checkbox" name="{{ $widget_value->widget_id }}" id="{{ $widget_value->widget_id }}" {{ $settingChecked }}>&nbsp;
                                    <label class="form-check-label" for="{{ $widget_value->widget_id }}">{{ $widget_value->widget_name }}</label>
                                </a>
                            @endif
                        @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div> -->
<!-- end page title -->

<div class="row position-relative">
    <div class="sidebar-menu">
        <form action="javascript:void(0);">
            <button type="button" class="btn btn-soft-info btn-icon waves-effect waves-light layout-rightside-btn">
                <!-- <i class="ri-pulse-line"></i> -->
                <i class="ri-menu-3-line"></i>
            </button>
        </form>
    </div>
    <div class="col">
        <div class="h-100">

            <!-- Flash Message -->
            @if(Session::has('message'))
                <div class="row">
                    <div class="col-xl-12">
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ Session::get('message') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                </div>
            @endif

            <div class="row d-none">
                <!-- Website Hits -->
                <div class="col-xl-3 col-md-6">
                    <!-- card -->
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 overflow-hidden">
                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0"> Website Hits</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-4">
                                <div>
                                    <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                        <span class="counter-value" data-target="{{ $web_hits }}" id=""></span>
                                    </h4>
                                    <a href="{{ url('powerpanel/hits-report') }}" class="text-decoration-underline text-muted">View hits</a>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-soft-success rounded fs-3">
                                        <i class="bx bx-dollar-circle text-success"></i>
                                    </span>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </div><!-- end col -->

                <!-- Mobile Hits -->
                <div class="col-xl-3 col-md-6">
                    <!-- card -->
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 overflow-hidden">
                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Mobile Hits</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-4">
                                <div>
                                    <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                        <span class="counter-value" data-target="{{ $mobile_hits }}"></span>
                                    </h4>
                                    <a href="{{ url('powerpanel/hits-report') }}" class="text-decoration-underline text-muted">View hits</a>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-soft-info rounded fs-3">
                                        <i class="bx bx-shopping-bag text-info"></i>
                                    </span>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </div><!-- end col -->

                <!-- Contact Leads -->
                <div class="col-xl-3 col-md-6">
                    <!-- card -->
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 overflow-hidden">
                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Contact Leads</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-4">
                                <div>
                                    <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                        <span class="counter-value" data-target="{{ $leadsCount }}"></span>
                                    </h4>
                                    <a href="{{ url('powerpanel/contact-us') }}" class="text-decoration-underline text-muted">View Contact Leads</a>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-soft-warning rounded fs-3">
                                        <i class="bx bx-user-circle text-warning"></i>
                                    </span>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </div><!-- end col -->

                <!-- Feedback Leads -->
                <div class="col-xl-3 col-md-6">
                    <!-- card -->
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 overflow-hidden">
                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Feedback Leads</p>
                                </div>
                                <!-- <div class="flex-shrink-0">
                                    <h5 class="text-muted fs-14 mb-0">+0.00 %</h5>
                                </div> -->
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-4">
                                <div>
                                    <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                        <span class="counter-value" data-target="{{ $feedBackleadsCount }}"></span>
                                    </h4>
                                    <a href="{{ url('powerpanel/feedback-leads') }}" class="text-decoration-underline text-muted">View Feedback Leads</a>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-soft-primary rounded fs-3">
                                        <i class="bx bx-wallet text-primary"></i>
                                    </span>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </div><!-- end col -->
            </div> <!-- end row-->

            <div class="row">
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-white dashboard-box overflow-hidden">
                        <div class="card-body p-0">
                            <div class="row align-items-end">
                                <div class="col-sm-12">
                                    <div class="p-3">
                                        <div class="d-flex align-items-end align-items-center">
                                            <div class="avatar-sm flex-shrink-0 me-3">
                                                <span class="rounded fs-2">
                                                    <!-- <i class="bx bx-dollar-circle text-success"></i> -->
                                                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="512" height="512" x="0" y="0" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g>
                                                    <circle xmlns="http://www.w3.org/2000/svg" style="" cx="267" cy="256" r="245" fill="#69cdff" data-original="#69cdff" class=""></circle>
                                                    <rect xmlns="http://www.w3.org/2000/svg" x="185.72" y="358.53" style="" width="78.71" height="41.23" fill="#445ea0" data-original="#445ea0" class=""></rect>
                                                    <rect xmlns="http://www.w3.org/2000/svg" x="185.72" y="358.53" style="" width="19.842" height="41.23" fill="#2e4c89" data-original="#2e4c89"></rect>
                                                    <path xmlns="http://www.w3.org/2000/svg" style="" d="M316.856,398.763H127.56c-2.806,0-5.101,2.296-5.101,5.102v25.571c0,2.806,2.296,5.102,5.101,5.102  h189.296L316.856,398.763L316.856,398.763z" fill="#293d7c" data-original="#293d7c"></path>
                                                    <path xmlns="http://www.w3.org/2000/svg" style="" d="M142.301,429.435v-25.571c0-2.806,2.296-5.102,5.102-5.102H127.56c-2.806,0-5.101,2.296-5.101,5.102  v25.571c0,2.806,2.296,5.102,5.101,5.102h19.842C144.597,434.537,142.301,432.241,142.301,429.435z" fill="#1a2b63" data-original="#1a2b63"></path>
                                                    <path xmlns="http://www.w3.org/2000/svg" style="" d="M445.054,58.964H5.102C2.296,58.964,0,61.26,0,64.066V354.43c0,2.807,2.296,5.102,5.102,5.102  h311.754V206.768c0-3.859,3.14-7,7-7h126.3V64.066C450.156,61.26,447.86,58.964,445.054,58.964z" fill="#293d7c" data-original="#293d7c"></path>
                                                    <path xmlns="http://www.w3.org/2000/svg" style="" d="M22.396,308.514c0,1.684,1.377,3.06,3.061,3.06h291.399V206.768c0-3.859,3.14-7,7-7H427.76v-90.77  H22.396V308.514z" fill="#ffffff" data-original="#ffffff" class=""></path>
                                                    <path xmlns="http://www.w3.org/2000/svg" style="" d="M42.239,308.514V108.998H22.396v199.516c0,1.684,1.377,3.06,3.061,3.06H45.3  C43.616,311.574,42.239,310.197,42.239,308.514z" fill="#d9eafc" data-original="#d9eafc" class=""></path>
                                                    <circle xmlns="http://www.w3.org/2000/svg" style="" cx="225.08" cy="335.46" r="13.774" fill="#445ea0" data-original="#445ea0" class=""></circle>
                                                    <path xmlns="http://www.w3.org/2000/svg" style="" d="M316.856,223.146h-53.424c-1.65,0-3,1.35-3,3v59.639c0,1.65,1.35,3,3,3h53.424V223.146z" fill="#c3ddf4" data-original="#c3ddf4" class=""></path>
                                                    <path xmlns="http://www.w3.org/2000/svg" style="" d="M280.274,285.785v-59.639c0-1.65,1.35-3,3-3h-19.842c-1.65,0-3,1.35-3,3v59.639c0,1.65,1.35,3,3,3  h19.842C281.624,288.785,280.274,287.435,280.274,285.785z" fill="#aec1ed" data-original="#aec1ed"></path>
                                                    <path xmlns="http://www.w3.org/2000/svg" style="" d="M323.856,199.768h46.618v-65.382c0-2.75-2.25-5-5-5H84.682c-2.75,0-5,2.25-5,5v66.525  c0,2.75,2.25,5,5,5h232.233C317.339,202.455,320.288,199.768,323.856,199.768z" fill="#c3ddf4" data-original="#c3ddf4" class=""></path>
                                                    <path xmlns="http://www.w3.org/2000/svg" style="" d="M99.524,200.91v-66.525c0-2.75,2.25-5,5-5H84.682c-2.75,0-5,2.25-5,5v66.525c0,2.75,2.25,5,5,5  h19.842C101.774,205.91,99.524,203.66,99.524,200.91z" fill="#aec1ed" data-original="#aec1ed"></path>
                                                    <path xmlns="http://www.w3.org/2000/svg" style="" d="M424.699,84.983H25.457c-1.684,0-3.061,1.377-3.061,3.062v21.953H427.76V88.045  C427.76,86.36,426.382,84.983,424.699,84.983z" fill="#ffaf10" data-original="#ffaf10" class=""></path>
                                                    <path xmlns="http://www.w3.org/2000/svg" style="" d="M45.3,84.983H25.457c-1.684,0-3.061,1.377-3.061,3.062v21.953h19.842V88.045  C42.239,86.36,43.616,84.983,45.3,84.983z" fill="#ff9518" data-original="#ff9518"></path>
                                                    <g xmlns="http://www.w3.org/2000/svg">
                                                        <path style="" d="M171.504,238.979h-84.17c-4.142,0-7.5-3.357-7.5-7.5s3.358-7.5,7.5-7.5h84.17   c4.142,0,7.5,3.357,7.5,7.5S175.646,238.979,171.504,238.979z" fill="#5dc1d8" data-original="#5dc1d8"></path>
                                                        <path style="" d="M171.504,263.466h-84.17c-4.142,0-7.5-3.357-7.5-7.5s3.358-7.5,7.5-7.5h84.17   c4.142,0,7.5,3.357,7.5,7.5C179.004,260.108,175.646,263.466,171.504,263.466z" fill="#5dc1d8" data-original="#5dc1d8"></path>
                                                        <path style="" d="M171.504,287.953h-84.17c-4.142,0-7.5-3.357-7.5-7.5s3.358-7.5,7.5-7.5h84.17   c4.142,0,7.5,3.357,7.5,7.5S175.646,287.953,171.504,287.953z" fill="#5dc1d8" data-original="#5dc1d8"></path>
                                                        <path style="" d="M237.56,238.979h-43.326c-4.142,0-7.5-3.357-7.5-7.5s3.358-7.5,7.5-7.5h43.326   c4.142,0,7.5,3.357,7.5,7.5S241.702,238.979,237.56,238.979z" fill="#5dc1d8" data-original="#5dc1d8"></path>
                                                        <path style="" d="M237.56,263.466h-43.326c-4.142,0-7.5-3.357-7.5-7.5s3.358-7.5,7.5-7.5h43.326   c4.142,0,7.5,3.357,7.5,7.5C245.06,260.108,241.702,263.466,237.56,263.466z" fill="#5dc1d8" data-original="#5dc1d8"></path>
                                                        <path style="" d="M237.56,287.953h-43.326c-4.142,0-7.5-3.357-7.5-7.5s3.358-7.5,7.5-7.5h43.326   c4.142,0,7.5,3.357,7.5,7.5S241.702,287.953,237.56,287.953z" fill="#5dc1d8" data-original="#5dc1d8"></path>
                                                    </g>
                                                    <path xmlns="http://www.w3.org/2000/svg" style="" d="M504,198.768c4.4,0,8,3.6,8,8v241.77c0,4.4-3.6,8-8,8H323.856c-4.4,0-8-3.6-8-8v-241.77  c0-4.4,3.6-8,8-8L504,198.768L504,198.768z" fill="#445ea0" data-original="#445ea0" class=""></path>
                                                    <path xmlns="http://www.w3.org/2000/svg" style="" d="M337.567,425.125c-2.75,0-5-2.25-5-5V219.18c0-2.75,2.25-5,5-5H490.29c2.75,0,5,2.25,5,5v200.945  c0,2.75-2.25,5-5,5H337.567z" fill="#ffffff" data-original="#ffffff" class=""></path>
                                                    <path xmlns="http://www.w3.org/2000/svg" style="" d="M352.409,420.125V219.18c0-2.75,2.25-5,5-5h-19.842c-2.75,0-5,2.25-5,5v200.945c0,2.75,2.25,5,5,5  h19.842C354.659,425.125,352.409,422.875,352.409,420.125z" fill="#d9eafc" data-original="#d9eafc" class=""></path>
                                                    <path xmlns="http://www.w3.org/2000/svg" style="" d="M404.567,445.125c-2.75,0-5-2.25-5-5v-1.945c0-2.75,2.25-5,5-5h18.723c2.75,0,5,2.25,5,5v1.945  c0,2.75-2.25,5-5,5H404.567z" fill="#445ea0" data-original="#445ea0" class=""></path>
                                                    <path xmlns="http://www.w3.org/2000/svg" style="" d="M495.29,233.473V219.18c0-2.75-2.25-5-5-5H337.567c-2.75,0-5,2.25-5,5v14.293L495.29,233.473  L495.29,233.473z" fill="#ffaf10" data-original="#ffaf10" class=""></path>
                                                    <path xmlns="http://www.w3.org/2000/svg" style="" d="M357.409,214.18h-19.842c-2.75,0-5,2.25-5,5v14.293h19.842V219.18  C352.409,216.43,354.659,214.18,357.409,214.18z" fill="#ff9518" data-original="#ff9518"></path>
                                                    <g xmlns="http://www.w3.org/2000/svg">
                                                        <path style="" d="M448.013,324.933h-68.169c-4.142,0-7.5-3.357-7.5-7.5s3.358-7.5,7.5-7.5h68.169   c4.142,0,7.5,3.357,7.5,7.5C455.513,321.575,452.155,324.933,448.013,324.933z" fill="#5dc1d8" data-original="#5dc1d8"></path>
                                                        <path style="" d="M448.013,348.06h-68.169c-4.142,0-7.5-3.357-7.5-7.5c0-4.142,3.358-7.5,7.5-7.5h68.169   c4.142,0,7.5,3.358,7.5,7.5C455.513,344.703,452.155,348.06,448.013,348.06z" fill="#5dc1d8" data-original="#5dc1d8"></path>
                                                        <path style="" d="M448.013,371.185h-68.169c-4.142,0-7.5-3.357-7.5-7.5c0-4.142,3.358-7.5,7.5-7.5h68.169   c4.142,0,7.5,3.358,7.5,7.5C455.513,367.828,452.155,371.185,448.013,371.185z" fill="#5dc1d8" data-original="#5dc1d8"></path>
                                                    </g>
                                                    <path xmlns="http://www.w3.org/2000/svg" style="" d="M479.756,292.257c0,2.75-2.25,5-5,5H353.101c-2.75,0-5-2.25-5-5v-41.244c0-2.75,2.25-5,5-5h121.655  c2.75,0,5,2.25,5,5V292.257z" fill="#c3ddf4" data-original="#c3ddf4" class=""></path>
                                                    <path xmlns="http://www.w3.org/2000/svg" style="" d="M367.943,292.257v-41.244c0-2.75,2.25-5,5-5h-19.842c-2.75,0-5,2.25-5,5v41.244c0,2.75,2.25,5,5,5  h19.842C370.193,297.257,367.943,295.007,367.943,292.257z" fill="#aec1ed" data-original="#aec1ed"></path>
                                                    <path xmlns="http://www.w3.org/2000/svg" style="" d="M461.757,409.416c0,2.75-2.25,5-5,5H371.1c-2.75,0-5-2.25-5-5v-16.91c0-2.75,2.25-5,5-5h85.657  c2.75,0,5,2.25,5,5L461.757,409.416L461.757,409.416z" fill="#c3ddf4" data-original="#c3ddf4" class=""></path>
                                                    <path xmlns="http://www.w3.org/2000/svg" style="" d="M385.942,409.416v-16.91c0-2.75,2.25-5,5-5H371.1c-2.75,0-5,2.25-5,5v16.91c0,2.75,2.25,5,5,5h19.842  C388.192,414.416,385.942,412.166,385.942,409.416z" fill="#aec1ed" data-original="#aec1ed"></path></svg>
                                                </span>
                                            </div>
                                            <h4 class="fs-14 fw-normal m-0 text-black">Website Hits</h4>
                                        </div>
                                        <p class="fs-22 lh-base text-black fw-semibold mt-4 mb-0">
                                            <span class="counter-value" data-target="{{ $web_hits }}" id=""></span>
                                        </p>
                                    </div>
                                </div>                                        
                            </div>
                            <div class="alert alert-primary bg-bottom alert-solid m-0 d-flex align-items-center border-0 rounded-0" role="alert">
                                <div class="flex-grow-1 text-truncate">
                                    <i class="mdi mdi-circle align-middle text-secondary me-2"></i>
                                    <a href="{{ url('powerpanel/hits-report') }}" class="text-black fw-semibold" title="View Hits">View Hits</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-white dashboard-box overflow-hidden">
                        <div class="card-body p-0">
                            <div class="row align-items-end">
                                <div class="col-sm-12">
                                    <div class="p-3">
                                        <div class="d-flex align-items-end align-items-center">
                                            <div class="avatar-sm flex-shrink-0 me-3">
                                                <span class="rounded fs-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="512" height="512" x="0" y="0" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><linearGradient xmlns="http://www.w3.org/2000/svg" id="SVGID_1_" gradientUnits="userSpaceOnUse" x1="0" x2="512" y1="252.822" y2="252.822"><stop offset="0" stop-color="#ffdbed" stop-opacity="0"></stop><stop offset="1" stop-color="#ffdbed"></stop></linearGradient><g xmlns="http://www.w3.org/2000/svg"><g><path d="m20.337 130.334c-23.967 39.398-26.493 107.613-8.937 150.256 15.377 37.351 45.966 72.41 86.12 76.801 14.766 1.615 29.858-1.024 44.472 1.638 50.257 9.152 68.952 71.09 108.01 104.014 27.182 22.913 64.898 31.021 100.174 26.607s68.302-20.363 97.376-40.824c22.957-16.157 44.436-36.022 55.764-61.707 23.744-53.839-4.19-118.501-47.587-158.239-13.516-12.377-28.581-23.294-39.875-37.727-11.293-14.434-18.48-33.695-13.099-51.214 4.293-13.977 15.849-24.812 20.227-38.763 5.958-18.984-3.035-40.281-17.802-53.616s-34.308-20.039-53.666-24.64c-81.103-19.281-258.823-11.525-331.177 107.414z" fill="url(#SVGID_1_)" data-original="url(#SVGID_1_)"></path></g><g><g><g><path d="m350.232 497.161h-188.464c-16.569 0-30-13.431-30-30v-380.678c0-16.569 13.431-30 30-30h188.464c16.569 0 30 13.431 30 30v380.678c0 16.568-13.431 30-30 30z" fill="#2626bc" opacity=".1" data-original="#2626bc"></path><path d="m350.232 473.161h-188.464c-16.569 0-30-13.431-30-30v-380.678c0-16.569 13.431-30 30-30h188.464c16.569 0 30 13.431 30 30v380.678c0 16.568-13.431 30-30 30z" fill="#6583fe" data-original="#6583fe" class=""></path><path d="m131.77 75.392h248.46v354.86h-248.46z" fill="#ffffff" data-original="#ffffff" class=""></path><path d="m350.232 478.161h-188.464c-19.299 0-35-15.701-35-35v-380.678c0-19.299 15.701-35 35-35h188.465c19.299 0 35 15.701 35 35v380.678c-.001 19.299-15.702 35-35.001 35zm-188.464-440.678c-13.785 0-25 11.215-25 25v380.678c0 13.785 11.215 25 25 25h188.465c13.785 0 25-11.215 25-25v-380.678c0-13.785-11.215-25-25-25z" fill="#2626bc" data-original="#2626bc"></path><g><path d="m273.483 59.853h-34.966c-2.761 0-5-2.239-5-5s2.239-5 5-5h34.966c2.761 0 5 2.239 5 5s-2.239 5-5 5z" fill="#b7c5ff" data-original="#b7c5ff"></path></g><circle cx="296.813" cy="54.851" fill="#b7c5ff" r="5.002" data-original="#b7c5ff"></circle></g><path d="m351.45 109.252v84.9c0 5.52-4.48 10-10 10h-170.9c-5.52 0-10-4.48-10-10v-84.9c0-5.52 4.48-10 10-10h170.9c5.52 0 10 4.48 10 10z" fill="#9fb0fe" data-original="#9fb0fe" class=""></path><path d="m351.45 128.292v65.86c0 5.52-4.48 10-10 10h-121.88c-.37-3.14-.56-6.34-.56-9.58 0-45.73 37.07-82.8 82.81-82.8 18.61 0 35.8 6.15 49.63 16.52z" fill="#8399fe" data-original="#8399fe"></path><path d="m351.45 390.562v39.69h-190.9v-39.69c0-5.52 4.48-10 10-10h170.9c5.52 0 10 4.48 10 10z" fill="#02ffb3" data-original="#02ffb3"></path><path d="m239.06 405.412c0 8.94-2.14 17.38-5.93 24.84h-72.58v-39.69c0-5.52 4.48-10 10-10h62.58c3.79 7.47 5.93 15.91 5.93 24.85z" fill="#97ffd2" data-original="#97ffd2"></path><g><g><path d="m244.294 277.534h-78.742c-2.761 0-5-2.239-5-5v-39.739c0-2.761 2.239-5 5-5h78.742c2.761 0 5 2.239 5 5v39.739c0 2.762-2.239 5-5 5z" fill="#ff7eb8" data-original="#ff7eb8" class=""></path><path d="m346.449 277.534h-78.742c-2.761 0-5-2.239-5-5v-39.739c0-2.761 2.239-5 5-5h78.742c2.761 0 5 2.239 5 5v39.739c0 2.762-2.239 5-5 5z" fill="#02ffb3" data-original="#02ffb3"></path></g><g><path d="m244.294 338.92h-78.742c-2.761 0-5-2.239-5-5v-39.739c0-2.761 2.239-5 5-5h78.742c2.761 0 5 2.239 5 5v39.739c0 2.761-2.239 5-5 5z" fill="#8399fe" data-original="#8399fe"></path><path d="m346.449 338.92h-78.742c-2.761 0-5-2.239-5-5v-39.739c0-2.761 2.239-5 5-5h78.742c2.761 0 5 2.239 5 5v39.739c0 2.761-2.239 5-5 5z" fill="#ffc4df" data-original="#ffc4df" class=""></path></g></g><g><g><path d="m251.668 157.981h-65.681c-2.761 0-5 2.239-5 5s2.239 5 5 5h65.681c2.761 0 5-2.239 5-5s-2.238-5-5-5z" fill="#ffffff" data-original="#ffffff" class=""></path></g><g><path d="m251.668 138.553h-65.681c-2.761 0-5 2.239-5 5s2.239 5 5 5h65.681c2.761 0 5-2.239 5-5s-2.238-5-5-5z" fill="#ffffff" data-original="#ffffff" class=""></path></g><g><path d="m197.668 115.208h-11.681c-2.761 0-5 2.239-5 5s2.239 5 5 5h11.681c2.761 0 5-2.239 5-5s-2.238-5-5-5z" fill="#ffc4df" data-original="#ffc4df" class=""></path></g></g><g><path d="m207.232 360.128h-41.681c-2.761 0-5 2.239-5 5s2.239 5 5 5h41.681c2.761 0 5-2.239 5-5s-2.239-5-5-5z" fill="#8399fe" data-original="#8399fe"></path></g><path d="m351.45 390.562v24.59c-18.95 0-34.32-15.36-34.32-34.31v-.28h24.32c5.52 0 10 4.48 10 10z" fill="#01eca5" data-original="#01eca5"></path></g><path d="m421.705 167.419c-7.88 0-14.291-6.411-14.291-14.292s6.411-14.292 14.291-14.292c7.881 0 14.292 6.411 14.292 14.292s-6.411 14.292-14.292 14.292zm0-18.583c-2.366 0-4.291 1.925-4.291 4.292s1.925 4.292 4.291 4.292 4.292-1.925 4.292-4.292-1.926-4.292-4.292-4.292z" fill="#ff5ba8" data-original="#ff5ba8"></path><path d="m430.997 210.191c-7.881 0-14.292-6.411-14.292-14.292s6.411-14.292 14.292-14.292c7.88 0 14.291 6.411 14.291 14.292s-6.411 14.292-14.291 14.292zm0-18.583c-2.366 0-4.292 1.925-4.292 4.292s1.926 4.292 4.292 4.292 4.291-1.925 4.291-4.292-1.925-4.292-4.291-4.292z" fill="#6583fe" data-original="#6583fe" class=""></path><path d="m83.007 123.288c-2.762 0-5-2.239-5-5 0-3.309-2.691-6-6-6-2.762 0-5-2.239-5-5s2.238-5 5-5c3.309 0 6-2.691 6-6 0-2.761 2.238-5 5-5s5 2.239 5 5c0 3.309 2.691 6 6 6 2.762 0 5 2.239 5 5s-2.238 5-5 5c-3.309 0-6 2.691-6 6 0 2.761-2.238 5-5 5z" fill="#01eca5" data-original="#01eca5"></path><path d="m87.757 417.521c-2.762 0-5-2.239-5-5 0-3.309-2.691-6-6-6-2.762 0-5-2.239-5-5s2.238-5 5-5c3.309 0 6-2.691 6-6 0-2.761 2.238-5 5-5s5 2.239 5 5c0 3.309 2.691 6 6 6 2.762 0 5 2.239 5 5s-2.238 5-5 5c-3.309 0-6 2.691-6 6 0 2.761-2.238 5-5 5z" fill="#01eca5" data-original="#01eca5"></path><path d="m72.007 369.565c-2.762 0-5-2.239-5-5 0-3.309-2.691-6-6-6-2.762 0-5-2.239-5-5s2.238-5 5-5c3.309 0 6-2.692 6-6 0-2.761 2.238-5 5-5s5 2.239 5 5c0 3.309 2.691 6 6 6 2.762 0 5 2.239 5 5s-2.238 5-5 5c-3.309 0-6 2.691-6 6 0 2.761-2.238 5-5 5z" fill="#ff5ba8" data-original="#ff5ba8"></path></g></g></g></svg>
                                                </span>
                                            </div>
                                            <h4 class="fs-14 fw-normal m-0 text-black">Mobile Hits</h4>
                                        </div>
                                        <p class="fs-22 lh-base text-black fw-semibold mt-4 mb-0">
                                            <span class="counter-value" data-target="{{ $mobile_hits }}"></span>
                                        </p>
                                    </div>
                                </div>                                        
                            </div>
                            <div class="alert alert-primary bg-bottom alert-solid m-0 d-flex align-items-center border-0 rounded-0" role="alert">
                                <div class="flex-grow-1 text-truncate">
                                    <i class="mdi mdi-circle align-middle text-danger me-2"></i>
                                    <a href="{{ url('powerpanel/hits-report') }}" class="text-black fw-semibold" title="View Hits">View Hits</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-white dashboard-box overflow-hidden">
                        <div class="card-body p-0">
                            <div class="row align-items-end">
                                <div class="col-sm-12">
                                    <div class="p-3">
                                        <div class="d-flex align-items-end align-items-center">
                                            <div class="avatar-sm flex-shrink-0 me-3">
                                                <span class="rounded fs-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="512" height="512" x="0" y="0" viewBox="0 0 511 512" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><path xmlns="http://www.w3.org/2000/svg" d="m461.699219 204.398438c-1 0-2 0-2.800781.203124 0 1 .203124 2 .203124 2.796876v29.601562c0 1 0 2-.203124 2.800781 9.800781 1.597657 19-5 20.601562-14.800781s-5-19-14.800781-20.601562c-1.199219 0-2.199219 0-3 0zm0 0" fill="#f9d0b4" data-original="#f9d0b4" class=""></path><path xmlns="http://www.w3.org/2000/svg" d="m372.898438 237.199219v-29.597657c0-1 0-2 .203124-2.800781-9.800781-1.601562-19 5-20.800781 14.597657-1.800781 9.601562 5 19 14.597657 20.800781 2 .402343 4 .402343 6 0 0-1.199219 0-2.199219 0-3zm0 0" fill="#f9d0b4" data-original="#f9d0b4" class=""></path><path xmlns="http://www.w3.org/2000/svg" d="m422.300781 185c-15.601562 19.800781-38.800781 17-46.601562 15.398438-1.199219-.199219-2.398438.601562-2.800781 1.800781v.402343 34.597657c0 23.800781 19.203124 43 43 43 23.800781 0 43-19.199219 43-43v-38.398438c0-.199219 0-.402343 0-.601562 0-1.199219-1-2.199219-2.199219-2.199219-17.800781-.601562-27.199219-7.601562-30.800781-11.199219-1-1-2.597657-1-3.597657.199219.199219-.199219 0 0 0 0zm0 0" fill="#f9d0b4" data-original="#f9d0b4" class=""></path><path xmlns="http://www.w3.org/2000/svg" d="m415.898438 280.199219c-6.597657 0-13.199219-1.597657-19.199219-4.597657v16l6.800781 19.398438c.398438 1.199219 1.398438 1.800781 2.601562 1.800781h20.796876c1.203124 0 2.203124-.800781 2.601562-1.800781l6.800781-19.398438v-16.601562c-6.199219 3.398438-13.199219 5.199219-20.402343 5.199219zm0 0" fill="#f9d0b4" data-original="#f9d0b4" class=""></path><path xmlns="http://www.w3.org/2000/svg" d="m141.699219 204.398438c-1 0-2 0-2.800781.203124 0 1 .203124 2 .203124 2.796876v29.601562c0 1 0 2-.203124 2.800781 9.800781 1.597657 19-5 20.601562-14.800781s-5-19-14.800781-20.601562c-1.199219 0-2.199219 0-3 0zm0 0" fill="#f9d0b4" data-original="#f9d0b4" class=""></path><path xmlns="http://www.w3.org/2000/svg" d="m52.898438 237.199219v-29.597657c0-1 0-2 .203124-2.800781-9.800781-1.601562-19 5-20.800781 14.597657-1.601562 9.800781 5 19 14.597657 20.800781 2 .402343 4 .402343 6 0 0-1.199219 0-2.199219 0-3zm0 0" fill="#f9d0b4" data-original="#f9d0b4" class=""></path><path xmlns="http://www.w3.org/2000/svg" d="m102.300781 185c-15.601562 19.800781-38.800781 17-46.601562 15.398438-1.199219-.199219-2.398438.601562-2.800781 1.800781v.402343 34.597657c0 23.800781 19.203124 43 43 43 23.800781 0 43-19.199219 43-43v-38.398438c0-.199219 0-.402343 0-.601562 0-1.199219-1-2.199219-2.199219-2.199219-17.800781-.601562-27.199219-7.601562-30.800781-11.199219-1-1-2.597657-1-3.597657.199219.199219-.199219 0 0 0 0zm0 0" fill="#f9d0b4" data-original="#f9d0b4" class=""></path><path xmlns="http://www.w3.org/2000/svg" d="m95.898438 280.199219c-6.597657 0-13.199219-1.597657-19.199219-4.597657v16l6.800781 19.398438c.398438 1.199219 1.398438 1.800781 2.601562 1.800781h20.796876c1.203124 0 2.203124-.800781 2.601562-1.800781l6.800781-19.398438v-16.601562c-6.199219 3.398438-13.199219 5.199219-20.402343 5.199219zm0 0" fill="#f9d0b4" data-original="#f9d0b4" class=""></path><path xmlns="http://www.w3.org/2000/svg" d="m318.300781 196.398438c-1.402343 0-2.601562.203124-4 .402343 0 1.398438.199219 2.597657.199219 4v40.597657c0 1.402343 0 2.601562-.199219 4 13.398438 2.203124 26-7 28.199219-20.398438s-7-26-20.398438-28.199219c-1.203124-.199219-2.601562-.402343-3.800781-.402343zm0 0" fill="#f9d0b4" data-original="#f9d0b4" class=""></path><path xmlns="http://www.w3.org/2000/svg" d="m196.898438 241.199219v-40.597657c0-1.402343 0-2.601562.203124-4-13.402343-2.203124-26 6.796876-28.402343 20.199219-2.199219 13.398438 6.800781 26 20.199219 28.398438 2.800781.402343 5.402343.402343 8.203124 0-.203124-1.398438-.203124-2.597657-.203124-4zm0 0" fill="#f9d0b4" data-original="#f9d0b4" class=""></path><path xmlns="http://www.w3.org/2000/svg" d="m264.5 169.800781c-21.398438 27-53.199219 23.199219-63.800781 21-1.597657-.402343-3.398438.800781-3.800781 2.398438v.601562 47.398438c0 32.402343 26.402343 58.800781 58.800781 58.800781 32.402343 0 58.800781-26.398438 58.800781-58.800781v-52.398438c0-.402343 0-.601562 0-1 0-1.601562-1.398438-3-3-3-24.398438-.800781-37.398438-10.402343-42.199219-15.199219-1.199219-1.203124-3.199219-1.203124-4.402343 0-.199219 0-.398438.199219-.398438.199219zm0 0" fill="#f9d0b4" data-original="#f9d0b4" class=""></path><path xmlns="http://www.w3.org/2000/svg" d="m255.699219 300c-9.199219 0-18.199219-2.199219-26.199219-6.199219v21.800781l11.601562 26c1 2 3 3.398438 5.199219 3.398438h19.800781c2.199219 0 4.199219-1.199219 5-3.199219l12.597657-26v-22.601562c-8.597657 4.402343-18.199219 6.800781-28 6.800781zm0 0" fill="#f9d0b4" data-original="#f9d0b4" class=""></path><path xmlns="http://www.w3.org/2000/svg" d="m370.101562 204.398438c1 0 1.796876 0 2.796876.203124v-2c0-1.203124 1-2.203124 2.402343-2.203124h.398438c7.800781 1.601562 31 4.402343 46.601562-15.398438.800781-1 2.199219-1.199219 3.199219-.398438 0 0 .199219.199219.199219.199219 3.601562 3.597657 13 10.597657 30.800781 11.199219 1.199219 0 2.199219 1 2.199219 2.199219v.601562 5.800781c5.601562-.800781 11.199219 1 15.199219 4.796876v-32.199219c0-13.597657-11-24.597657-24.597657-24.597657-4.402343 0-8.800781 1.199219-12.601562 3.597657-4.398438-7.398438-12.398438-12-21-12h-12.597657c-24.402343 0-44.203124 19.800781-44.402343 44.402343v19.796876c3.402343-2.597657 7.402343-4 11.402343-4zm0 0" fill="#6d6c6b" data-original="#6d6c6b"></path><path xmlns="http://www.w3.org/2000/svg" d="m50.101562 204.398438c1 0 1.796876 0 2.796876.203124v-2c0-1.203124 1-2.203124 2.402343-2.203124h.398438c7.800781 1.601562 31 4.402343 46.601562-15.398438.800781-1 2.199219-1.199219 3.199219-.398438 0 0 .199219.199219.199219.199219 3.601562 3.597657 13 10.597657 30.800781 11.199219 1.199219 0 2.199219 1 2.199219 2.199219v.601562 5.800781c5.601562-.800781 11.199219 1 15.199219 4.796876v-32.199219c0-13.597657-11-24.597657-24.597657-24.597657-4.402343 0-8.800781 1.199219-12.601562 3.597657-4.398438-7.398438-12.398438-12-21-12h-12.597657c-24.402343 0-44.203124 19.800781-44.402343 44.402343v19.796876c3.402343-2.597657 7.402343-4 11.402343-4zm0 0" fill="#6d6c6b" data-original="#6d6c6b"></path><path xmlns="http://www.w3.org/2000/svg" d="m193.101562 196.398438c1.199219 0 2.597657.203124 3.796876.402343v-2.800781c0-1.800781 1.402343-3.199219 3.203124-3.199219h.597657c10.601562 2.199219 42.402343 6 63.800781-21 1-1.402343 3-1.601562 4.398438-.601562 0 0 .203124.199219.203124.199219 5 4.800781 17.796876 14.601562 42.199219 15.203124 1.597657 0 3 1.398438 3 3v1 7.796876c7.597657-1.199219 15.199219 1.203124 20.800781 6.601562v-43.800781c0-18.597657-15-33.597657-33.601562-33.597657-6 0-12 1.597657-17.199219 4.796876-6-10.199219-17-16.398438-28.800781-16.398438h-17c-33.398438 0-60.601562 27.199219-60.800781 60.800781v27.199219c4.402343-3.800781 9.800781-5.601562 15.402343-5.601562zm0 0" fill="#6d6c6b" data-original="#6d6c6b"></path><path xmlns="http://www.w3.org/2000/svg" d="m507.5 413.398438-5-88.199219c-.398438-7.398438-5.601562-13.597657-12.800781-15.597657l-33.398438-8.402343-20-9.800781-6.800781 19.402343c-.398438 1.199219-1.398438 1.800781-2.601562 1.800781h-20.796876c-1.203124 0-2.203124-.800781-2.601562-1.800781l-6.800781-19.402343-20 9.800781-33.398438 8.402343c-7.199219 1.796876-12.402343 8-12.800781 15.597657l-.398438 8.601562 26.796876 6.800781c9.800781 2.398438 17 11 17.601562 21.199219l3.800781 69.398438h112.199219c9.398438 0 17-7.597657 17-17 0-.199219 0-.398438 0-.800781zm0 0" fill="#f75c64" data-original="#f75c64"></path><path xmlns="http://www.w3.org/2000/svg" d="m138.699219 361.800781c.601562-10.199219 7.601562-18.800781 17.601562-21.199219l26.800781-6.800781-.402343-8.601562c-.398438-7.398438-5.597657-13.597657-12.800781-15.597657l-33.398438-8.402343-20-9.800781-6.800781 19.402343c-.398438 1.199219-1.398438 1.800781-2.597657 1.800781h-21c-1.203124 0-2.203124-.800781-2.601562-1.800781l-6.800781-19.402343-20 9.800781-33.398438 8.402343c-7.199219 1.796876-12.402343 8-12.800781 15.597657l-4.800781 88.199219c-.597657 9.402343 6.601562 17.402343 16 17.800781h1 112.402343zm0 0" fill="#e9e9ea" data-original="#e9e9ea"></path><path xmlns="http://www.w3.org/2000/svg" d="m381.5 490.800781-7.199219-128.800781c-.601562-10.199219-7.601562-18.800781-17.601562-21.199219l-45.597657-11.601562-27.402343-13.597657-12.597657 26c-1 2-3 3.199219-5 3.199219h-19.800781c-2.199219 0-4.199219-1.199219-5.199219-3.402343l-11.601562-26-27.398438 13.601562-45.601562 11.601562c-9.800781 2.398438-17 11-17.601562 21.199219l-7.199219 128.800781c-.398438 8.398438 6 15.796876 14.601562 16.199219h.800781 219.199219c8.597657 0 15.398438-7 15.398438-15.402343-.199219 0-.199219-.398438-.199219-.597657zm0 0" fill="#60bae2" data-original="#60bae2" class=""></path><path xmlns="http://www.w3.org/2000/svg" d="m311.101562 329.199219-13.601562 33.402343c-1 2.597657-4 3.796876-6.601562 2.796876-.796876-.398438-1.597657-.796876-2-1.597657l-17.796876-22.199219 12.597657-26zm0 0" fill="#e9e9ea" data-original="#e9e9ea"></path><path xmlns="http://www.w3.org/2000/svg" d="m215.5 362.601562c1 2.597657 4 3.796876 6.601562 2.796876.796876-.398438 1.597657-.796876 2-1.597657l17.398438-21.601562c-.199219-.199219-.398438-.597657-.398438-.800781l-11.601562-26-18.398438 9-9.203124 4.601562zm0 0" fill="#e9e9ea" data-original="#e9e9ea"></path><path xmlns="http://www.w3.org/2000/svg" d="m191.699219 76.398438h-17.398438c-2.800781 0-5-2.199219-5-5 0-2.796876 2.199219-5 5-5h17.398438c2.800781 0 5 2.203124 5 5 0 2.800781-2.199219 5-5 5zm0 0" fill="#f75c64" data-original="#f75c64"></path><path xmlns="http://www.w3.org/2000/svg" d="m183.101562 85.199219c-2.800781 0-5-2.199219-5-5v-17.398438c0-2.800781 2.199219-5 5-5 2.796876 0 5 2.199219 5 5v17.398438c0 2.800781-2.203124 5-5 5zm0 0" fill="#f75c64" data-original="#f75c64"></path><path xmlns="http://www.w3.org/2000/svg" d="m444.898438 503.398438h-17.398438c-2.800781 0-5-2.199219-5-5 0-2.796876 2.199219-5 5-5h17.398438c2.800781 0 5 2.203124 5 5 0 2.800781-2.199219 5-5 5zm0 0" fill="#6dcc6d" data-original="#6dcc6d"></path><path xmlns="http://www.w3.org/2000/svg" d="m436.300781 512c-2.800781 0-5-2.199219-5-5v-17.398438c0-2.800781 2.199219-5 5-5s5 2.199219 5 5v17.398438c0 2.800781-2.199219 5-5 5zm0 0" fill="#6dcc6d" data-original="#6dcc6d"></path><path xmlns="http://www.w3.org/2000/svg" d="m283.699219 64.800781c-.398438 0-.597657 0-1 0-.398438 0-.597657-.199219-1-.402343-.398438-.199219-.597657-.199219-.800781-.398438-.199219-.199219-.597657-.398438-.796876-.601562-.203124-.199219-.402343-.398438-.601562-.796876-.199219-.203124-.398438-.601562-.398438-.800781-.203124-.402343-.203124-.601562-.203124-1 0-.402343-.199219-.601562-.199219-1 0-.402343 0-.601562.199219-1 0-.402343.203124-.601562.203124-1 .199219-.402343.199219-.601562.398438-.800781s.398438-.601562.601562-.800781c.199219-.199219.398438-.398438.796876-.597657.203124-.203124.601562-.402343.800781-.402343.402343-.199219.601562-.199219 1-.199219.601562-.199219 1.402343-.199219 2 0 .402343 0 .601562.199219 1 .199219.402343.199219.601562.199219.800781.402343.199219.199219.601562.398438.800781.597657s.398438.402343.597657.800781c.203124.199219.402343.601562.402343.800781.199219.398438.199219.597657.199219 1 .199219.597657.199219 1.398438 0 2 0 .398438-.199219.597657-.199219 1-.199219.398438-.199219.597657-.402343.800781-.199219.199219-.398438.597657-.597657.796876-.199219.203124-.402343.402343-.800781.601562-.199219.199219-.601562.398438-.800781.398438-.398438.203124-.597657.203124-1 .402343-.398438 0-.800781 0-1 0zm0 0" fill="#ffcd29" data-original="#ffcd29"></path><path xmlns="http://www.w3.org/2000/svg" d="m467.5 468c-.398438 0-.601562 0-1-.199219-.398438 0-.601562-.199219-1-.199219-.398438-.203124-.601562-.203124-.800781-.402343s-.597657-.398438-.800781-.597657c-1-1-1.398438-2.203124-1.398438-3.601562 0-.398438 0-.601562.199219-1 0-.398438.199219-.601562.199219-1 .203124-.398438.203124-.601562.402343-.800781s.398438-.597657.597657-.800781c2-1.796876 5-1.796876 7 0 .203124.203124.402343.402343.601562.800781.199219.199219.398438.601562.398438.800781.203124.199219.203124.601562.203124 1s0 .601562.199219 1c.199219 2.601562-2 4.800781-4.800781 5zm0 0" fill="#ffcd29" data-original="#ffcd29"></path><g xmlns="http://www.w3.org/2000/svg" fill="#231f20"><path d="m461.699219 245.199219c-1.199219 0-2.398438-.199219-3.597657-.398438-2.601562-.402343-4.402343-2.601562-4.203124-5.199219 0-.800781 0-1.601562 0-2.601562v-29.601562c0-.796876 0-1.796876 0-2.597657-.199219-2.601562 1.601562-4.800781 4.203124-5.199219 12.597657-2 24.199219 6.597657 26.199219 19 2 12.597657-6.601562 24.199219-19 26.199219-1.199219.398438-2.402343.398438-3.601562.398438zm2.199219-35.597657v25.597657c7-1.199219 11.800781-8 10.601562-15-1-5.398438-5.199219-9.597657-10.601562-10.597657zm0 0" fill="#231f20" data-original="#231f20" class=""></path><path d="m370.101562 245.199219c-12.601562 0-23-10.398438-23-23 0-12.597657 10.398438-23 23-23 1.199219 0 2.398438 0 3.597657.199219 2.601562.402343 4.402343 2.601562 4.199219 5.203124v2.597657 29.601562 2.597657c.203124 2.601562-1.597657 4.800781-4.199219 5.203124-1.199219.597657-2.398438.597657-3.597657.597657zm-2.203124-35.597657c-7 1.199219-11.796876 8-10.597657 15 1 5.398438 5.199219 9.597657 10.597657 10.597657zm0 0" fill="#231f20" data-original="#231f20" class=""></path><path d="m415.898438 285.199219c-26.398438 0-48-21.398438-48-48v-34.597657c0-4 3.203124-7.203124 7.203124-7.203124.597657 0 1 0 1.597657.203124 8 1.597657 28 3.597657 41.800781-13.601562 2.601562-3.199219 7.199219-3.601562 10.199219-1.199219.199219.199219.402343.398438.601562.597657 2.800781 2.800781 11.199219 9.203124 27.597657 9.800781 3.800781.199219 7 3.199219 7 7.199219v.800781 38.199219c0 26.203124-21.597657 47.601562-48 47.800781zm-38-79.398438v31.398438c0 21 17 38 38 38s38-17 38-38v-36.398438c-15-1-24.398438-6.402343-29.398438-10.601562-15.601562 17.601562-36.800781 17-46.601562 15.601562zm48.402343-17.800781" fill="#231f20" data-original="#231f20" class=""></path><path d="m474.101562 214.398438c-1.203124 0-2.601562-.597657-3.402343-1.398438-3-2.800781-7-4.199219-11-3.398438-2.800781.398438-5.199219-1.402343-5.800781-4.203124 0-.199219 0-.597657 0-.796876v-3.800781c-15-1-24.398438-6.402343-29.398438-10.601562-15.601562 17.601562-36.800781 17-46.601562 15.402343-.597657 2.597657-3 4.199219-5.597657 3.796876-3.601562-.597657-7.199219.402343-10 2.601562-2.199219 1.800781-5.402343 1.398438-7-.800781-.800781-.800781-1-2-1-3.199219v-19.800781c0-27.199219 22.199219-49.398438 49.398438-49.398438h12.601562c8.800781 0 17 4 22.597657 10.597657 3.402343-1.398438 7.203124-2.199219 11-2.199219 16.402343 0 29.601562 13.199219 29.601562 29.601562v32.199219c-.398438 3.199219-2.601562 5.398438-5.398438 5.398438zm-10.203124-15c1.800781.203124 3.601562.601562 5.203124 1.203124v-23.402343c0-10.800781-8.601562-19.597657-19.402343-19.597657-3.597657 0-7 1-10.199219 2.796876-2.398438 1.402343-5.398438.601562-6.800781-1.796876-3.597657-6-9.800781-9.601562-16.800781-9.601562h-12.597657c-21.800781 0-39.402343 17.601562-39.402343 39.398438v11.800781c1.601562-.398438 3-.597657 4.601562-.800781 1.398438-3 4.800781-4.597657 8.199219-4 8 1.601562 28 3.601562 41.800781-13.597657 2.601562-3.199219 7.199219-3.601562 10.199219-1.199219.199219.199219.402343.398438.601562.597657 2.800781 2.800781 11.199219 9.199219 27.597657 9.800781 3.800781.199219 7 3.199219 7 7.199219zm0 0" fill="#231f20" data-original="#231f20" class=""></path><path d="m416.5 341.398438c-2.800781 0-5-2.199219-5-5 0-1.398438.601562-2.597657 1.398438-3.597657 1.203124-1.199219 2.800781-1.601562 4.601562-1.402343.398438 0 .601562.203124 1 .203124.398438.199219.601562.199219.800781.398438s.597657.398438.800781.601562c1 1 1.398438 2.199219 1.398438 3.597657 0 .402343 0 .601562-.199219 1 0 .402343-.199219.601562-.199219 1-.203124.199219-.203124.601562-.402343.800781s-.398438.601562-.597657.800781c-.203124.199219-.402343.398438-.800781.597657-.199219.203124-.601562.402343-.800781.402343-.199219.199219-.601562.199219-1 .199219-.398438.398438-.601562.398438-1 .398438zm0 0" fill="#231f20" data-original="#231f20" class=""></path><path d="m416.5 364.398438c-.398438 0-.601562 0-1-.199219-.398438 0-.601562-.199219-1-.199219-.398438-.199219-.601562-.199219-.800781-.398438-.199219-.203124-.597657-.402343-.800781-.601562-2-2-2-5.199219 0-7s5-1.800781 7 0c2 2 2 5.199219 0 7-.199219.199219-.398438.398438-.796876.601562-.203124.199219-.601562.398438-.800781.398438-.199219.199219-.601562.199219-1 .199219-.199219.199219-.402343.199219-.800781.199219zm0 0" fill="#231f20" data-original="#231f20" class=""></path><path d="m416.5 387.398438c-1.398438 0-2.601562-.597657-3.601562-1.398438-2-2-2-5.199219 0-7s5-1.800781 7 0c2 2 2 5.199219 0 7-.796876.800781-2 1.398438-3.398438 1.398438zm0 0" fill="#231f20" data-original="#231f20" class=""></path><path d="m416.5 410.398438c-1.398438 0-2.601562-.597657-3.601562-1.398438-2-2-2-5.199219 0-7 .203124-.199219.402343-.398438.800781-.601562.199219-.199219.601562-.398438.800781-.398438.398438-.199219.601562-.199219 1-.199219.601562-.199219 1.398438-.199219 2 0 .398438 0 .601562.199219 1 .199219.398438.199219.601562.199219.800781.398438.199219.203124.597657.402343.800781.601562 2 2 2 5.199219 0 7-1 .800781-2.203124 1.398438-3.601562 1.398438zm0 0" fill="#231f20" data-original="#231f20" class=""></path><path d="m466.699219 436.199219c-2.800781 0-5-2.199219-5-5v-72c0-2.800781 2.199219-5 5-5s5 2.199219 5 5v72c0 2.800781-2.199219 5-5 5zm0 0" fill="#231f20" data-original="#231f20" class=""></path><path d="m426.898438 317.800781h-20.796876c-3.402343 0-6.402343-2.199219-7.402343-5.199219l-6.800781-19.402343c-.199219-.597657-.199219-1-.199219-1.597657v-16c0-2.800781 2.199219-5 5-5 .800781 0 1.601562.199219 2.199219.597657 5.203124 2.601562 11 4 17 4 6.402343 0 12.601562-1.597657 18-4.597657 2.402343-1.402343 5.402343-.402343 6.800781 2 .402343.796876.601562 1.597657.601562 2.398438v16.601562c0 .597657 0 1.199219-.199219 1.597657l-6.800781 19.402343c-1.199219 3-4.199219 5.199219-7.402343 5.199219zm-19.199219-10h17.601562l6-17.199219v-8c-9.601562 3.199219-20 3.398438-29.601562.398438v7.601562zm0 0" fill="#231f20" data-original="#231f20" class=""></path><path d="m141.699219 245.199219c-1.199219 0-2.398438-.199219-3.597657-.398438-2.601562-.402343-4.402343-2.601562-4.203124-5.199219 0-.800781 0-1.601562 0-2.601562v-29.601562c0-.796876 0-1.796876 0-2.597657-.199219-2.601562 1.601562-4.800781 4.203124-5.199219 12.597657-2 24.199219 6.597657 26.199219 19 2 12.597657-6.601562 24.199219-19 26.199219-1.199219.398438-2.402343.398438-3.601562.398438zm2.199219-35.597657v25.597657c7-1.199219 11.800781-8 10.601562-15-1-5.398438-5.199219-9.597657-10.601562-10.597657zm0 0" fill="#231f20" data-original="#231f20" class=""></path><path d="m50.101562 245.199219c-12.601562 0-23-10.398438-23-23 0-12.597657 10.398438-23 23-23 1.199219 0 2.398438 0 3.597657.199219 2.601562.402343 4.402343 2.601562 4.199219 5.203124v2.597657 29.601562 2.597657c.203124 2.601562-1.597657 4.800781-4.199219 5.203124-1.199219.597657-2.398438.597657-3.597657.597657zm-2.203124-35.597657c-7 1.199219-11.796876 8-10.597657 15 1 5.398438 5.199219 9.597657 10.597657 10.597657zm0 0" fill="#231f20" data-original="#231f20" class=""></path><path d="m95.898438 285.199219c-26.398438 0-48-21.398438-48-48v-34.597657c0-4 3.203124-7.203124 7.203124-7.402343.597657 0 1 0 1.597657.199219 8 1.601562 28 3.601562 41.800781-13.597657 2.601562-3.199219 7.199219-3.601562 10.199219-1.199219.199219.199219.402343.398438.601562.597657 2.800781 2.800781 11.199219 9.199219 27.597657 9.800781 3.800781.199219 7 3.199219 7 7.199219v39c0 26.402343-21.597657 47.800781-48 48zm-38-79.398438v31.398438c0 21 17 38 38 38s38-17 38-38v-36.398438c-15-1-24.398438-6.402343-29.398438-10.601562-15.601562 17.601562-36.800781 17-46.601562 15.601562zm48.402343-17.800781" fill="#231f20" data-original="#231f20" class=""></path><path d="m154.101562 214.398438c-1.203124 0-2.601562-.597657-3.402343-1.398438-2.398438-2.398438-5.597657-3.601562-9-3.601562-.597657 0-1.398438 0-2 .203124-2.800781.398438-5.199219-1.402343-5.800781-4.203124 0-.199219 0-.597657 0-.796876v-3.800781c-15-1-24.398438-6.402343-29.398438-10.601562-15.601562 17.601562-36.800781 17-46.601562 15.402343-.597657 2.597657-3 4.199219-5.597657 3.796876-3.601562-.597657-7.199219.402343-10 2.601562-2.199219 1.800781-5.402343 1.398438-7-.800781-.800781-.800781-1-2-1-3.199219v-19.800781c0-27.199219 22.199219-49.398438 49.398438-49.398438h12.601562c8.800781 0 17 4 22.597657 10.597657 3.402343-1.398438 7.203124-2.199219 11-2.199219 16.402343 0 29.601562 13.199219 29.601562 29.601562v32.199219c-.398438 3.199219-2.601562 5.398438-5.398438 5.398438zm-10.203124-15c1.800781.203124 3.601562.601562 5.203124 1.203124v-23.402343c0-10.800781-8.601562-19.597657-19.402343-19.597657-3.597657 0-7 1-10.199219 2.796876-2.398438 1.402343-5.398438.601562-6.800781-1.796876-3.597657-6-9.800781-9.601562-16.800781-9.601562h-12.597657c-21.800781 0-39.402343 17.601562-39.402343 39.398438v11.800781c1.601562-.398438 3-.597657 4.601562-.800781 1.398438-3 4.800781-4.597657 8.199219-4 8 1.601562 28 3.601562 41.800781-13.597657 2.601562-3.199219 7.199219-3.601562 10.199219-1.199219.199219.199219.402343.398438.601562.597657 2.800781 2.800781 11.199219 9.199219 27.597657 9.800781 3.800781.199219 7 3.199219 7 7.199219zm0 0" fill="#231f20" data-original="#231f20" class=""></path><path d="m96.5 341.398438c-2.800781 0-5-2.199219-5-5 0-1.398438.601562-2.597657 1.398438-3.597657.203124-.199219.402343-.402343.800781-.601562.199219-.199219.601562-.398438.800781-.398438.199219-.199219.601562-.199219 1-.199219.601562-.203124 1.398438-.203124 2 0 .398438 0 .601562.199219 1 .199219.398438.199219.601562.199219.800781.398438s.597657.402343.800781.601562c2 2 2 5.199219 0 7-1 1-2.203124 1.597657-3.601562 1.597657zm0 0" fill="#231f20" data-original="#231f20" class=""></path><path d="m96.5 364.398438c-2.800781 0-5-2.199219-5-5 0-.398438 0-.597657 0-1 0-.398438.199219-.597657.199219-1 .199219-.199219.199219-.597657.402343-.796876.199219-.203124.398438-.601562.597657-.800781s.402343-.402343.800781-.601562c.199219-.199219.601562-.398438.800781-.398438.199219-.199219.597657-.199219 1-.199219.597657-.203124 1.398438-.203124 2 0 .597657.199219 1.199219.398438 1.800781.796876.199219.203124.597657.402343.796876.601562 2 2 2 5.199219 0 7-.796876.800781-2 1.398438-3.398438 1.398438zm0 0" fill="#231f20" data-original="#231f20" class=""></path><path d="m96.5 387.398438c-.398438 0-.601562 0-1-.199219-.398438 0-.601562-.199219-1-.199219-.398438-.199219-.601562-.199219-.800781-.398438-.199219-.203124-.597657-.402343-.800781-.601562-2-2-2-5.199219 0-7s5-1.800781 7 0c2 2 2 5.199219 0 7-.199219.199219-.398438.398438-.796876.601562-.203124.199219-.601562.398438-.800781.398438-.402343.199219-.601562.199219-1 .199219-.199219.199219-.402343.199219-.800781.199219zm0 0" fill="#231f20" data-original="#231f20" class=""></path><path d="m96.5 410.398438c-.398438 0-.601562 0-1-.199219-.398438 0-.601562-.199219-1-.199219-.398438-.199219-.601562-.199219-.800781-.398438-.199219-.203124-.597657-.402343-.800781-.601562-2-2-2-5.199219 0-7s5-1.800781 7 0c2 2 2 5.199219 0 7-.199219.199219-.398438.398438-.796876.601562-.601562.398438-1.203124.597657-1.800781.796876-.199219-.199219-.402343 0-.800781 0zm0 0" fill="#231f20" data-original="#231f20" class=""></path><path d="m44.898438 436.199219c-2.796876 0-5-2.199219-5-5v-72c0-2.800781 2.203124-5 5-5 2.800781 0 5 2.199219 5 5v72c0 2.800781-2.199219 5-5 5zm0 0" fill="#231f20" data-original="#231f20" class=""></path><path d="m106.898438 317.800781h-20.796876c-3.402343 0-6.402343-2.199219-7.402343-5.199219l-6.800781-19.402343c-.199219-.597657-.199219-1-.199219-1.597657v-16c0-2.800781 2.199219-5 5-5 .800781 0 1.601562.199219 2.199219.597657 5.203124 2.601562 11 4 17 4 6.402343 0 12.601562-1.597657 18-4.597657 2.402343-1.402343 5.402343-.402343 6.800781 2 .402343.796876.601562 1.597657.601562 2.398438v16.601562c0 .597657 0 1.199219-.199219 1.597657l-6.800781 19.402343c-1.199219 3-4 5.199219-7.402343 5.199219zm-19.199219-10h17.601562l6-17.199219v-8c-9.601562 3.199219-20 3.398438-29.601562.398438v7.601562zm0 0" fill="#231f20" data-original="#231f20" class=""></path><path d="m490.5 436.199219h-112.398438c-2.601562 0-4.800781-2-5-4.800781l-3.800781-69.398438c-.402343-8-6-14.800781-13.800781-16.601562l-26.800781-6.796876c-2.398438-.601562-4-2.800781-3.800781-5.203124l.402343-8.597657c.597657-9.601562 7.199219-17.800781 16.597657-20l32.800781-8.199219 19.601562-9.601562c2.398438-1.199219 5.398438-.199219 6.597657 2.199219 0 .199219.203124.402343.203124.601562l6.199219 18h17.597657l6.203124-18c1-2.601562 3.796876-4 6.398438-3 .199219 0 .398438.199219.601562.199219l19.597657 9.601562 32.800781 8.199219c9.398438 2.398438 16 10.398438 16.601562 20l5 88.199219c.597657 12.199219-8.601562 22.398438-20.800781 23.199219 0 0-.402343 0-.800781 0zm-107.601562-10h107.601562c6.601562 0 12-5.398438 12-12 0-.199219 0-.398438 0-.597657l-5-88.203124c-.199219-5.199219-4-9.597657-9-11l-33.398438-8.398438c-.402343 0-.601562-.199219-1-.398438l-15-7.402343-5 14.199219c-1 3.203124-4 5.203124-7.402343 5.203124h-20.800781c-3.398438 0-6.398438-2.203124-7.398438-5.203124l-5-14.199219-15 7.402343c-.398438.199219-.601562.199219-1 .398438l-33.398438 8.398438c-5 1.203124-8.800781 5.800781-9 11l-.203124 4.601562 22.800781 5.800781c12 3 20.601562 13.398438 21.199219 25.800781zm0 0" fill="#231f20" data-original="#231f20" class=""></path><path d="m134.898438 436.199219h-112.398438c-12.199219 0-22-9.800781-22-22 0-.398438 0-.800781 0-1.199219l5-88.199219c.601562-9.601562 7.199219-17.800781 16.601562-20l32.796876-8.199219 19.601562-9.601562c2.398438-1.199219 5.398438-.199219 6.601562 2.199219 0 .199219.199219.402343.199219.601562l6.199219 18h17.601562l6.199219-18c1-2.601562 3.800781-4 6.398438-3 .199219 0 .402343.199219.601562.199219l19.597657 9.601562 32.800781 8.199219c9.402343 2.398438 16 10.398438 16.601562 20l.398438 8.597657c.199219 2.402343-1.398438 4.601562-3.800781 5.203124l-26.796876 6.796876c-7.800781 2-13.402343 8.601562-13.800781 16.601562l-3.800781 69.398438c.199219 2.800781-2 4.800781-4.601562 4.800781zm-61-137.800781-15 7.402343c-.398438.199219-.597657.199219-1 .398438l-33.398438 8.402343c-5 1.199219-8.800781 5.796876-9 11l-5 88c-.398438 6.597657 4.601562 12.199219 11.199219 12.597657h.601562 107.597657l3.601562-64.800781c.601562-12.398438 9.199219-22.796876 21.199219-25.796876l22.800781-5.800781-.199219-4.601562c-.199219-5.199219-4-9.597657-9-11l-33.402343-8.398438c-.398438 0-.597657-.199219-1-.402343l-15-7.398438-5 14.199219c-1 3.199219-4 5.199219-7.398438 5.199219h-20.398438c-3.402343 0-6.402343-2.199219-7.402343-5.199219zm64.800781 63.402343" fill="#231f20" data-original="#231f20" class=""></path><path d="m318.300781 250.601562c-1.601562 0-3.199219-.203124-4.800781-.402343-2.601562-.398438-4.398438-2.597657-4.199219-5.199219 0-1.199219.199219-2.398438.199219-3.601562v-40.597657c0-1 0-2.199219-.199219-3.601562-.199219-2.597657 1.597657-4.800781 4.199219-5.199219 16.199219-2.601562 31.199219 8.398438 33.800781 24.398438 2.597657 16-8.402343 31.203124-24.402343 33.800781-1.597657.199219-3 .402343-4.597657.402343zm1.199219-49.203124v39c10.800781-.597657 19-10 18.199219-20.796876-.597657-9.800781-8.398438-17.601562-18.199219-18.203124zm0 0" fill="#231f20" data-original="#231f20" class=""></path><path d="m193.101562 250.601562c-16.402343 0-29.601562-13.203124-29.601562-29.601562s13.199219-29.601562 29.601562-29.601562c1.597657 0 3.199219.203124 4.796876.402343 2.601562.398438 4.402343 2.597657 4.203124 5.199219 0 1.398438-.203124 2.601562-.203124 3.601562v40.597657c0 1.199219 0 2.402343.203124 3.601562.199219 2.597657-1.601562 4.800781-4.203124 5.199219-1.796876.398438-3.199219.601562-4.796876.601562zm-1.203124-49.203124c-10.796876.601562-19 10-18.199219 20.800781.601562 9.800781 8.402343 17.601562 18.199219 18.199219zm0 0" fill="#231f20" data-original="#231f20" class=""></path><path d="m255.699219 305c-35.199219 0-63.800781-28.601562-63.800781-63.800781v-47.199219c0-4.398438 3.601562-8.199219 8.203124-8.199219.597657 0 1.199219 0 1.597657.199219 11.199219 2.398438 39.601562 5.199219 59-19.199219 2.800781-3.601562 8-4.199219 11.402343-1.402343.199219.203124.398438.402343.597657.601562 4 4 15.800781 13.199219 39 13.800781 4.402343.199219 7.800781 3.597657 7.800781 8v53.398438c0 35.199219-28.601562 63.800781-63.800781 63.800781zm-53.800781-108.800781v45c0 29.800781 24.203124 53.800781 53.800781 53.800781 29.800781 0 53.800781-24 53.800781-53.800781v-51.398438c-22.601562-1-35.800781-9.402343-42.398438-15.199219-22 26-53.203124 23.597657-65.203124 21.597657zm62.601562-26.398438" fill="#231f20" data-original="#231f20" class=""></path><path d="m335.300781 208.199219c-1.199219 0-2.601562-.597657-3.402343-1.398438-4.398438-4.199219-10.597657-6.199219-16.597657-5.199219-2.800781.398438-5.199219-1.402343-5.800781-4.203124 0-.199219 0-.597657 0-.796876v-7c-22.601562-1-35.800781-9.402343-42.398438-15.203124-22 26-53.203124 23.800781-65.402343 21.601562v.601562c0 2.796876-2.199219 5-5 5-.199219 0-.597657 0-.800781 0-1-.203124-2-.203124-3-.203124-4.398438 0-8.796876 1.601562-12.199219 4.203124-2.199219 1.796876-5.398438 1.398438-7-.800781-.800781-.800781-1-2-1-3.199219v-27c0-36.203124 29.402343-65.601562 65.800781-65.800781h17.199219c12 0 23.199219 5.597657 30.402343 15 4.796876-2.199219 10.199219-3.402343 15.597657-3.402343 21.402343 0 38.601562 17.203124 38.601562 38.601562v44.199219c0 2.800781-2.199219 5-5 5zm-15.800781-16.800781c3.800781.203124 7.398438 1 10.800781 2.601562v-34.800781c0-15.800781-12.601562-28.597657-28.402343-28.597657-5.199219 0-10.398438 1.398438-14.796876 4-2.402343 1.398438-5.402343.597657-6.800781-1.800781-5.199219-8.601562-14.402343-14-24.601562-14h-17.199219c-30.800781 0-55.601562 25-55.800781 55.800781v18.597657c3-1.199219 6.199219-1.800781 9.402343-1.800781.398438-1.398438 1.398438-2.796876 2.597657-3.796876 2-1.601562 4.402343-2.203124 6.800781-1.601562 11.199219 2.398438 39.601562 5.199219 59-19.199219 2.800781-3.601562 8-4.199219 11.398438-1.402343.203124.203124.402343.402343.601562.601562 4 4 15.800781 13.199219 39 13.800781 4.398438.199219 7.800781 3.597657 7.800781 8v1zm-10-3.398438" fill="#231f20" data-original="#231f20" class=""></path><path d="m256.5 375.601562c-.398438 0-.601562 0-1-.203124-.398438 0-.601562-.199219-1-.199219-.398438-.199219-.601562-.199219-.800781-.398438s-.597657-.402343-.800781-.601562c-.199219-.199219-.398438-.398438-.597657-.800781-.199219-.199219-.402343-.597657-.402343-.796876-.199219-.402343-.199219-.601562-.199219-1-.199219-.601562-.199219-1.402343 0-2 0-.402343.199219-.601562.199219-1 .203124-.402343.203124-.601562.402343-.800781s.398438-.601562.597657-.800781c2-1.800781 5-1.800781 7 0 .203124.199219.402343.398438.601562.800781.199219.199219.398438.597657.398438.800781.203124.398438.203124.597657.203124 1 .199219.597657.199219 1.398438 0 2 0 .398438-.203124.597657-.203124 1-.199219.199219-.199219.597657-.398438.796876-.199219.203124-.398438.601562-.601562.800781-.796876 1-2 1.402343-3.398438 1.402343zm0 0" fill="#231f20" data-original="#231f20" class=""></path><path d="m256.5 402c-1.398438 0-2.601562-.601562-3.601562-1.398438-.199219-.203124-.398438-.402343-.597657-.800781-.199219-.199219-.402343-.601562-.402343-.800781-.199219-.199219-.199219-.601562-.199219-1-.199219-.601562-.199219-1.398438 0-2s.402343-1.199219.800781-1.800781c.199219-.199219.398438-.597657.601562-.800781.199219-.199219.398438-.398438.796876-.597657.203124-.199219.601562-.402343.800781-.402343.402343-.199219.601562-.199219 1-.199219 1.601562-.398438 3.402343.199219 4.601562 1.402343.199219.199219.398438.398438.597657.796876.203124.203124.402343.601562.402343.800781.199219.402343.199219.601562.199219 1 .199219.601562.199219 1.402343 0 2 0 .402343-.199219.601562-.199219 1-.199219.402343-.199219.601562-.402343.800781-.199219.199219-.398438.601562-.597657.800781-1.199219.597657-2.402343 1.199219-3.800781 1.199219zm0 0" fill="#231f20" data-original="#231f20" class=""></path><path d="m256.5 428.199219c-1.398438 0-2.601562-.597657-3.601562-1.398438-.199219-.199219-.398438-.402343-.597657-.800781-.199219-.199219-.402343-.601562-.402343-.800781-.199219-.398438-.199219-.597657-.199219-1-.199219-.597657-.199219-1.398438 0-2 0-.398438.199219-.597657.199219-1 .203124-.398438.203124-.597657.402343-.800781.199219-.199219.398438-.597657.597657-.796876 2-1.800781 5-1.800781 7 0 .203124.199219.402343.398438.601562.796876.199219.203124.398438.601562.398438.800781.203124.402343.203124.601562.203124 1 .199219.601562.199219 1.402343 0 2 0 .402343-.203124.601562-.203124 1-.199219.199219-.199219.601562-.398438.800781s-.398438.601562-.601562.800781c-.796876.800781-2 1.398438-3.398438 1.398438zm0 0" fill="#231f20" data-original="#231f20" class=""></path><path d="m256.5 454.398438c-1.398438 0-2.601562-.597657-3.601562-1.398438-.199219-.199219-.398438-.398438-.597657-.800781-.402343-.597657-.601562-1.199219-.800781-1.800781-.199219-.597657-.199219-1.398438 0-2 .199219-.597657.398438-1.199219.800781-1.796876.199219-.203124.398438-.601562.597657-.800781 2-1.800781 5-1.800781 7 0 .203124.199219.402343.398438.601562.800781.199219.199219.398438.597657.398438.796876.203124.402343.203124.601562.203124 1 .199219.601562.199219 1.402343 0 2 0 .402343-.203124.601562-.203124 1-.199219.203124-.199219.601562-.398438.800781s-.398438.601562-.601562.800781c-.796876.800781-2 1.398438-3.398438 1.398438zm0 0" fill="#231f20" data-original="#231f20" class=""></path><path d="m256.5 480.601562c-1.398438 0-2.601562-.601562-3.601562-1.402343-.199219-.199219-.398438-.398438-.597657-.800781-.199219-.199219-.402343-.597657-.402343-.796876-.199219-.402343-.199219-.601562-.199219-1-.199219-.601562-.199219-1.402343 0-2 0-.402343.199219-.601562.199219-1 .203124-.402343.203124-.601562.402343-.800781s.398438-.601562.597657-.800781c2-1.800781 5-1.800781 7 0 .203124.199219.402343.398438.601562.800781.199219.199219.398438.597657.398438.800781.203124.398438.203124.597657.203124 1 .199219.597657.199219 1.398438 0 2 0 .398438-.203124.597657-.203124 1-.199219.199219-.199219.597657-.398438.796876-.199219.203124-.398438.601562-.601562.800781-.796876.800781-2 1.402343-3.398438 1.402343zm0 0" fill="#231f20" data-original="#231f20" class=""></path><path d="m331.898438 512c-2.796876 0-5-2.199219-5-5v-98.398438c0-2.800781 2.203124-5 5-5 2.800781 0 5 2.199219 5 5v98.398438c0 2.800781-2.199219 5-5 5zm0 0" fill="#231f20" data-original="#231f20" class=""></path><path d="m181.101562 512c-2.800781 0-5-2.199219-5-5v-98.398438c0-2.800781 2.199219-5 5-5 2.796876 0 5 2.199219 5 5v98.398438c0 2.800781-2.402343 5-5 5zm0 0" fill="#231f20" data-original="#231f20" class=""></path><path d="m265.898438 349.800781h-19.796876c-4.203124 0-8-2.402343-9.601562-6.199219l-11.601562-26c-.199219-.601562-.398438-1.402343-.398438-2v-21.800781c0-2.800781 2.199219-5 5-5 .800781 0 1.601562.199219 2.199219.597657 7.402343 3.800781 15.601562 5.800781 24 5.800781 9 0 17.800781-2.199219 25.601562-6.597657 2.398438-1.402343 5.398438-.402343 6.800781 2 .398438.796876.597657 1.597657.597657 2.398438v22.601562c0 .796876-.199219 1.597657-.398438 2.199219l-12.601562 26c-2 3.597657-5.800781 6-9.800781 6zm-31.597657-35.199219 11.199219 25c.199219.199219.398438.398438.601562.398438h19.796876c.203124 0 .402343-.199219.601562-.398438l12.199219-25v-13.601562c-7.398438 2.800781-15.199219 4.199219-23 4.199219-7.199219 0-14.398438-1.199219-21.199219-3.597657zm0 0" fill="#231f20" data-original="#231f20" class=""></path><path d="m366.101562 512h-219.203124c-11.199219 0-20.398438-9.199219-20.398438-20.398438 0-.402343 0-.800781 0-1.203124l7.199219-128.796876c.601562-12.402343 9.199219-22.800781 21.199219-25.800781l45.203124-11.402343 27-13.398438c2.398438-1.199219 5.398438-.199219 6.597657 2.199219v.199219l11.601562 26c.199219.203124.398438.402343.597657.402343h19.800781c.199219 0 .402343-.199219.601562-.402343l12.597657-26c1.203124-2.398438 4.203124-3.597657 6.601562-2.398438l27 13.398438 45.199219 11.402343c12 3 20.601562 13.398438 21.199219 25.800781l7.203124 128.796876c.597657 11.203124-8 20.800781-19.203124 21.402343 0 .199219-.398438.199219-.796876.199219zm-139.203124-189.601562-22.796876 11.203124c-.402343.199219-.601562.199219-1 .398438l-45.601562 11.601562c-7.800781 2-13.398438 8.597657-13.800781 16.597657l-7.199219 128.800781c-.398438 5.800781 4 10.601562 9.800781 11h.597657 219.203124c5.796876 0 10.398438-4.601562 10.398438-10.398438 0-.203124 0-.402343 0-.601562l-7.199219-128.800781c-.402343-8-6-14.800781-13.800781-16.597657l-45.601562-11.402343c-.398438 0-.597657-.199219-1-.398438l-23-11.402343-10.398438 21.601562c-1.800781 3.601562-5.398438 6-9.601562 6h-19.796876c-4.203124 0-8-2.398438-9.601562-6.199219zm0 0" fill="#231f20" data-original="#231f20" class=""></path><path d="m256.5 272.398438c-9.199219 0-17.800781-4.597657-23-12.199219-1.601562-2.199219-1-5.398438 1.398438-7 2.402343-1.597657 5.402343-1 7 1.402343 5.402343 8.199219 16.601562 10.199219 24.601562 4.796876 1.800781-1.199219 3.601562-3 4.800781-4.796876 1.597657-2.203124 4.597657-2.800781 7-1.402343 2.199219 1.601562 2.800781 4.601562 1.398438 7-5.398438 7.601562-14 12.199219-23.199219 12.199219zm0 0" fill="#231f20" data-original="#231f20" class=""></path><path d="m234.101562 226c-.402343 0-.601562 0-1-.199219-.402343 0-.601562-.199219-1-.199219-.402343-.203124-.601562-.203124-.800781-.402343s-.601562-.398438-.800781-.597657c-.199219-.203124-.398438-.402343-.601562-.800781-.199219-.199219-.398438-.601562-.398438-.800781-.199219-.398438-.199219-.601562-.398438-1-.203124-.601562-.203124-1.398438 0-2 0-.398438.199219-.601562.398438-1s.199219-.601562.398438-.800781c.203124-.199219.402343-.597657.601562-.800781.199219-.199219.398438-.398438.800781-.597657.199219-.199219.597657-.402343.800781-.402343.398438-.199219.597657-.199219 1-.199219.597657-.199219 1.199219-.199219 2 0 .398438 0 .597657.199219 1 .199219.199219.203124.597657.203124.796876.402343.203124.199219.601562.398438.800781.597657.199219.203124.402343.402343.601562.800781.199219.199219.398438.601562.398438.800781.199219.199219.199219.601562.402343 1 .199219.601562.199219 1.398438 0 2 0 .398438-.203124.601562-.402343 1s-.199219.601562-.398438.800781-.402343.597657-.601562.800781c-.199219.199219-.398438.398438-.800781.597657-.199219.199219-.597657.402343-.796876.402343-.402343.199219-.601562.199219-1 .199219-.402343.199219-.601562.199219-1 .199219zm0 0" fill="#231f20" data-original="#231f20" class=""></path><path d="m278.898438 226c-.398438 0-.597657 0-1-.199219-.398438 0-.597657-.199219-1-.199219-.398438-.203124-.597657-.203124-.796876-.402343-.203124-.199219-.601562-.398438-.800781-.597657-.199219-.203124-.402343-.402343-.601562-.800781-.199219-.199219-.398438-.601562-.398438-.800781-.199219-.398438-.199219-.601562-.199219-1s0-.601562-.203124-1c0-.398438 0-.601562.203124-1 0-.398438.199219-.601562.199219-1 .199219-.398438.199219-.601562.398438-.800781s.402343-.597657.601562-.800781c1.199219-1.199219 2.800781-1.597657 4.398438-1.398438.402343 0 .601562.199219 1 .199219.199219.199219.601562.199219.800781.402343.199219.199219.601562.398438.800781.597657s.398438.402343.597657.800781c.203124.199219.402343.601562.402343.800781.199219.199219.199219.597657.199219 1v1 1c0 .398438-.199219.597657-.199219 1-.199219.398438-.199219.597657-.402343.800781-.199219.199219-.398438.597657-.597657.796876-.199219.203124-.402343.402343-.800781.601562-.199219.199219-.601562.398438-.800781.398438-.398438.203124-.597657.203124-1 .203124-.199219.398438-.398438.398438-.800781.398438zm0 0" fill="#231f20" data-original="#231f20" class=""></path><path d="m292.699219 370.800781c-3 0-6-1.402343-7.800781-3.800781l-17.796876-22.199219c-1.203124-1.601562-1.402343-3.601562-.601562-5.402343l12.601562-26c1.199219-2.398438 4.199219-3.597657 6.597657-2.398438l27.402343 13.601562c2.398438 1.199219 3.398438 4 2.398438 6.398438l-13.601562 33.398438c-1.398438 3.402343-4.398438 5.601562-7.796876 6.203124-.402343.199219-.800781.199219-1.402343.199219zm-15.800781-29.800781 15.800781 19.800781 12-29.199219-18.800781-9.203124zm0 0" fill="#231f20" data-original="#231f20" class=""></path><path d="m220.300781 370.800781c-.601562 0-1 0-1.601562-.199219-3.597657-.402343-6.597657-2.800781-7.800781-6.203124l-13.597657-33.398438c-1-2.398438 0-5.199219 2.398438-6.398438l27.402343-13.601562c2.398438-1.199219 5.398438-.199219 6.597657 2.199219v.199219l11.601562 26c1.199219 1.800781 1.199219 4.203124-.199219 6l-17.402343 21.601562c-1.597657 2.398438-4.398438 3.800781-7.398438 3.800781zm-.199219-10 15.398438-19.199219-8.601562-19.203124-18.597657 9.203124zm0 0" fill="#231f20" data-original="#231f20" class=""></path><path d="m304.898438 428.199219h-24.796876c-2.800781 0-5-2.199219-5-5s2.199219-5 5-5h24.796876c2.800781 0 5 2.199219 5 5s-2.199219 5-5 5zm0 0" fill="#231f20" data-original="#231f20" class=""></path></g><path xmlns="http://www.w3.org/2000/svg" d="m264.300781 16.398438c-8.199219-1.597657-14.402343-8-16-16 0-.199219-.199219-.398438-.402343-.398438-.199219 0-.199219.199219-.398438.398438-1.601562 8.203124-8 14.402343-16 16-.199219 0-.398438.203124-.398438.402343s.199219.199219.398438.398438c8.199219 1.601562 14.398438 8 16 16 0 .199219.199219.402343.398438.402343.203124 0 .203124-.203124.402343-.402343 1.597657-8.199219 8-14.398438 16-16 .199219 0 .398438-.199219.398438-.398438-.199219-.402343-.398438-.402343-.398438-.402343zm0 0" fill="#55acd5" data-original="#55acd5"></path></g></svg>
                                                </span>
                                            </div>
                                            <h4 class="fs-14 fw-normal m-0 text-black">Contact Leads</h4>
                                        </div>
                                        <p class="fs-22 lh-base text-black fw-semibold mt-4 mb-0">
                                            <span class="counter-value" data-target="{{ $leadsCount }}"></span>
                                        </p>
                                    </div>
                                </div>                                        
                            </div>
                            <div class="alert alert-primary bg-bottom alert-solid m-0 d-flex align-items-center border-0 rounded-0" role="alert">
                                <div class="flex-grow-1 text-truncate">
                                    <i class="mdi mdi-circle align-middle text-success me-2"></i>
                                    <a href="{{ url('powerpanel/contact-us') }}" class="text-black fw-semibold" title="View Contact Leads">View Contact Leads</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-white dashboard-box overflow-hidden">
                        <div class="card-body p-0">
                            <div class="row align-items-end">
                                <div class="col-sm-12">
                                    <div class="p-3">
                                        <div class="d-flex align-items-end align-items-center">
                                            <div class="avatar-sm flex-shrink-0 me-3">
                                                <span class="rounded fs-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="512" height="512" x="0" y="0" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><path xmlns="http://www.w3.org/2000/svg" d="m465.588 7.568h-419.317c-21.414 0-38.774 17.36-38.774 38.774v119.51c0 21.414 17.359 38.773 38.773 38.773h29.708l36.99 49.375 36.99-49.375h315.629c21.414 0 38.774-17.36 38.774-38.774v-119.509c0-21.414-17.359-38.774-38.773-38.774z" fill="#ffd36c" data-original="#ffd36c" class=""></path><path xmlns="http://www.w3.org/2000/svg" d="m465.588 7.568h-40.489c21.414 0 38.774 17.359 38.774 38.774v119.51c0 21.414-17.359 38.774-38.774 38.774h40.489c21.414 0 38.774-17.359 38.774-38.774v-119.51c-.001-21.414-17.36-38.774-38.774-38.774z" fill="#fcc645" data-original="#fcc645"></path><path xmlns="http://www.w3.org/2000/svg" d="m114.939 58.059 13.943 28.252c.398.806 1.167 1.365 2.057 1.494l31.178 4.53c2.241.326 3.135 3.079 1.514 4.66l-22.561 21.991c-.644.628-.938 1.532-.786 2.418l5.326 31.052c.383 2.232-1.96 3.933-3.964 2.88l-27.887-14.661c-.796-.418-1.747-.418-2.542 0l-27.887 14.661c-2.004 1.054-4.346-.648-3.964-2.88l5.326-31.052c.152-.886-.142-1.79-.786-2.418l-22.558-21.99c-1.621-1.58-.727-4.334 1.514-4.66l31.178-4.53c.89-.129 1.659-.688 2.057-1.494l13.943-28.252c1.002-2.031 3.897-2.031 4.899-.001z" fill="#eaaf20" data-original="#eaaf20"></path><path xmlns="http://www.w3.org/2000/svg" d="m258.379 58.059 13.943 28.252c.398.806 1.167 1.365 2.057 1.494l31.178 4.53c2.241.326 3.135 3.079 1.514 4.66l-22.561 21.991c-.644.628-.938 1.532-.786 2.418l5.326 31.052c.383 2.232-1.96 3.933-3.964 2.88l-27.887-14.661c-.796-.418-1.747-.418-2.542 0l-27.887 14.661c-2.004 1.054-4.346-.648-3.964-2.88l5.326-31.052c.152-.886-.142-1.79-.786-2.418l-22.561-21.991c-1.621-1.58-.727-4.334 1.514-4.66l31.178-4.53c.89-.129 1.659-.688 2.057-1.494l13.943-28.252c1.005-2.03 3.9-2.03 4.902 0z" fill="#eaaf20" data-original="#eaaf20"></path><path xmlns="http://www.w3.org/2000/svg" d="m401.819 58.059 13.943 28.252c.398.806 1.167 1.365 2.057 1.494l31.178 4.53c2.241.326 3.135 3.079 1.514 4.66l-22.561 21.991c-.644.628-.938 1.532-.786 2.418l5.326 31.052c.383 2.232-1.96 3.933-3.964 2.88l-27.887-14.661c-.796-.418-1.747-.418-2.542 0l-27.887 14.661c-2.004 1.054-4.346-.648-3.964-2.88l5.326-31.052c.152-.886-.142-1.79-.786-2.418l-22.561-21.991c-1.621-1.58-.727-4.334 1.514-4.66l31.178-4.53c.89-.129 1.659-.688 2.057-1.494l13.943-28.252c1.005-2.03 3.9-2.03 4.902 0z" fill="#eaaf20" data-original="#eaaf20"></path><circle xmlns="http://www.w3.org/2000/svg" cx="112.469" cy="399.461" fill="#f7f3f1" r="104.971" data-original="#f7f3f1"></circle><g xmlns="http://www.w3.org/2000/svg" fill="#e3d3c6"><circle cx="112.368" cy="376.467" r="37.99" fill="#e3d3c6" data-original="#e3d3c6"></circle><path d="m112.368 414.456c-35.392 0-64.559 26.654-68.522 60.983v3.456c18.401 15.911 42.388 25.536 68.623 25.536 26.224 0 50.201-9.617 68.599-25.515l.025-1.476c-3.04-35.289-32.648-62.984-68.725-62.984z" fill="#e3d3c6" data-original="#e3d3c6"></path><path d="m485.867 341.976h-205.944c-23.878 0-23.908-36.99 0-36.99h205.943c23.878.001 23.908 36.99.001 36.99z" fill="#e3d3c6" data-original="#e3d3c6"></path><path d="m486.366 417.956h-205.943c-23.878 0-23.908-36.99 0-36.99h205.943c23.878 0 23.908 36.99 0 36.99z" fill="#e3d3c6" data-original="#e3d3c6"></path><path d="m486.866 493.935h-205.943c-23.878 0-23.908-36.99 0-36.99h205.943c23.878 0 23.908 36.99 0 36.99z" fill="#e3d3c6" data-original="#e3d3c6"></path></g><path xmlns="http://www.w3.org/2000/svg" d="m61.784 84.917c-3.882.564-7.046 3.232-8.258 6.961-1.212 3.73-.221 7.748 2.588 10.487l20.768 20.242-4.903 28.582c-.664 3.866.895 7.699 4.068 10.005 3.172 2.305 7.301 2.604 10.774.78l25.669-13.495 25.67 13.495c3.666 1.808 7.257 1.548 10.773-.78 3.173-2.306 4.732-6.139 4.068-10.004l-4.902-28.583 20.766-20.241c2.809-2.738 3.802-6.755 2.59-10.486-1.213-3.731-4.377-6.4-8.259-6.963l-28.698-4.17-12.834-26.006c-1.736-3.517-5.251-5.702-9.173-5.702s-7.438 2.185-9.173 5.702l-12.836 26.005zm41.036 4.713 9.669-19.592 9.671 19.596c1.491 3.017 4.37 5.107 7.701 5.592l21.621 3.142-15.646 15.251c-2.412 2.351-3.511 5.737-2.941 9.054l3.692 21.534-19.337-10.167c-1.491-.784-3.125-1.175-4.76-1.175s-3.271.391-4.76 1.175l-19.338 10.167 3.694-21.539c.567-3.317-.533-6.7-2.943-9.049l-15.647-15.251 21.621-3.142c3.332-.484 6.21-2.574 7.703-5.596z" fill="#000000" data-original="#000000" class=""></path><path xmlns="http://www.w3.org/2000/svg" d="m314.895 91.88c-1.213-3.731-4.377-6.4-8.259-6.963l-28.698-4.17-12.834-26.006c-1.736-3.517-5.25-5.702-9.173-5.702s-7.438 2.185-9.173 5.702l-12.834 26.006-28.698 4.17c-3.883.564-7.047 3.232-8.259 6.963-1.212 3.73-.219 7.748 2.589 10.485l20.767 20.242-4.902 28.583c-.664 3.866.895 7.699 4.068 10.005 3.172 2.305 7.301 2.604 10.774.78l25.669-13.495 25.67 13.495c3.666 1.808 7.257 1.548 10.773-.78 3.173-2.306 4.732-6.139 4.068-10.004l-4.902-28.583 20.766-20.241c2.807-2.739 3.8-6.757 2.588-10.487zm-35.618 21.739c-2.412 2.351-3.511 5.737-2.941 9.054l3.692 21.534-19.338-10.167c-1.491-.784-3.125-1.175-4.76-1.175s-3.27.391-4.76 1.175l-19.338 10.167 3.692-21.531c.57-3.319-.528-6.705-2.941-9.057l-15.646-15.251 21.621-3.142c3.331-.484 6.209-2.574 7.703-5.596l9.669-19.592 9.671 19.596c1.492 3.018 4.37 5.108 7.701 5.592l21.621 3.142z" fill="#000000" data-original="#000000" class=""></path><path xmlns="http://www.w3.org/2000/svg" d="m458.334 91.878c-1.213-3.73-4.377-6.398-8.258-6.961l-28.698-4.17-12.834-26.006c-1.736-3.517-5.251-5.702-9.173-5.702-3.923 0-7.438 2.185-9.173 5.702l-12.834 26.006-28.698 4.17c-3.883.564-7.047 3.232-8.259 6.963-1.212 3.73-.219 7.748 2.589 10.485l20.767 20.242-4.902 28.583c-.664 3.866.895 7.699 4.068 10.005 3.173 2.305 7.3 2.604 10.774.78l25.669-13.495 25.67 13.495c3.666 1.808 7.257 1.548 10.773-.78 3.173-2.306 4.732-6.139 4.068-10.005l-4.903-28.582 20.768-20.243c2.807-2.739 3.798-6.757 2.586-10.487zm-35.617 21.741c-2.409 2.349-3.51 5.731-2.942 9.054l3.693 21.534-19.338-10.167c-2.982-1.568-6.539-1.567-9.521 0l-19.338 10.167 3.692-21.531c.57-3.319-.528-6.705-2.941-9.057l-15.646-15.251 21.622-3.142c3.33-.484 6.209-2.575 7.702-5.596l9.669-19.592 9.671 19.596c1.492 3.018 4.37 5.108 7.701 5.592l21.621 3.142z" fill="#000000" data-original="#000000" class=""></path><path xmlns="http://www.w3.org/2000/svg" d="m46.271 212.123h25.956l34.741 46.373c2.921 3.9 9.067 3.917 12.001 0l34.741-46.373h311.877c25.514 0 46.271-20.757 46.271-46.271v-119.51c0-25.514-20.757-46.271-46.271-46.271h-335.623c-9.672 0-9.672 14.996 0 14.996h335.624c17.245 0 31.276 14.03 31.276 31.276v119.51c0 17.245-14.03 31.276-31.276 31.276h-315.629c-2.362 0-4.585 1.112-6 3.003l-30.99 41.365-30.99-41.365c-1.416-1.89-3.639-3.003-6-3.003h-29.708c-17.245 0-31.276-14.03-31.276-31.276v-119.511c0-17.245 14.03-31.276 31.276-31.276h45.703c9.672 0 9.672-14.996 0-14.996h-45.703c-25.514 0-46.271 20.758-46.271 46.272v119.51c0 25.514 20.757 46.271 46.271 46.271z" fill="#000000" data-original="#000000" class=""></path><path xmlns="http://www.w3.org/2000/svg" d="m112.469 286.991c-62.016 0-112.469 50.454-112.469 112.47s50.453 112.469 112.469 112.469 112.469-50.453 112.469-112.469-50.453-112.47-112.469-112.47zm-61.048 188.397c3.984-30.525 30.014-53.434 60.947-53.434 15.491 0 30.289 5.779 41.667 16.272 10.681 9.85 17.471 23.016 19.305 37.303-16.692 13.384-37.862 21.404-60.872 21.404-23.09.001-44.328-8.076-61.047-21.545zm30.456-98.921c0-16.813 13.679-30.492 30.492-30.492s30.492 13.678 30.492 30.492-13.679 30.492-30.492 30.492-30.492-13.679-30.492-30.492zm104.26 86.755c-3.75-13.723-11.287-26.2-21.936-36.019-7.028-6.482-15.106-11.513-23.816-14.941 10.624-8.334 17.47-21.276 17.47-35.795 0-25.082-20.406-45.488-45.488-45.488s-45.488 20.406-45.488 45.488c0 14.518 6.845 27.46 17.468 35.794-22.077 8.684-39.259 27.353-45.699 50.783-14.733-17.081-23.654-39.309-23.654-63.583 0-53.747 43.726-97.473 97.473-97.473s97.473 43.726 97.473 97.473c.002 24.357-8.978 46.657-23.803 63.761z" fill="#000000" data-original="#000000" class=""></path><path xmlns="http://www.w3.org/2000/svg" d="m279.923 349.474h205.943c33.462 0 33.561-51.986 0-51.986h-205.943c-33.462.001-33.561 51.986 0 51.986zm0-36.99h205.943c14.149 0 14.172 21.994 0 21.994h-205.943c-14.149 0-14.172-21.994 0-21.994z" fill="#000000" data-original="#000000" class=""></path><path xmlns="http://www.w3.org/2000/svg" d="m280.423 425.453h205.943c33.462 0 33.561-51.986 0-51.986h-12.496c-9.672 0-9.672 14.996 0 14.996h12.497c14.149 0 14.172 21.994 0 21.994h-205.944c-14.149 0-14.172-21.994 0-21.994h155.457c9.672 0 9.672-14.996 0-14.996h-155.457c-33.462.001-33.561 51.986 0 51.986z" fill="#000000" data-original="#000000" class=""></path><path xmlns="http://www.w3.org/2000/svg" d="m486.866 449.447h-205.943c-33.462 0-33.561 51.986 0 51.986h24.993c9.672 0 9.672-14.996 0-14.996h-24.993c-14.149 0-14.172-21.994 0-21.994h205.943c14.149 0 14.172 21.994 0 21.994h-143.96c-9.672 0-9.672 14.996 0 14.996h143.96c33.462 0 33.562-51.986 0-51.986z" fill="#000000" data-original="#000000" class=""></path></g></svg>
                                                </span>
                                            </div>
                                            <h4 class="fs-14 fw-normal m-0 text-black">Feedback Leads</h4>
                                        </div>
                                        <p class="fs-22 lh-base text-black fw-semibold mt-4 mb-0">
                                            <span class="counter-value" data-target="{{ $feedBackleadsCount }}"></span>
                                        </p>
                                    </div>
                                </div>                                        
                            </div>
                            <div class="alert alert-primary bg-bottom alert-solid m-0 d-flex align-items-center border-0 rounded-0" role="alert">
                                <div class="flex-grow-1 text-truncate">
                                    <i class="mdi mdi-circle align-middle text-info me-2"></i>
                                    <a href="{{ url('powerpanel/feedback-leads') }}" class="text-black fw-semibold" title="View Feedback Leads">View Feedback Leads</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                <!-- Document Views & Downloads -->
                <div class="col-xl-8">
                    <div class="card">
                        <div class="card-header border-0 align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Document Views & Downloads</h4>
                            <div>
                                <button type="button" class="btn btn-soft-primary btn-sm docChartFilter" data-value="all">ALL</button>
                                <button type="button" class="btn btn-soft-primary btn-sm docChartFilter" data-value="1">1Y</button>
                                <button type="button" class="btn btn-soft-primary btn-sm docChartFilter" data-value="2">2Y</button>
                                <button type="button" class="btn btn-soft-primary btn-sm docChartFilter" data-value="3">3Y</button>
                                <button type="button" class="btn btn-soft-primary btn-sm docChartFilter" data-value="4">4Y</button>
                                <button type="button" class="btn btn-soft-primary btn-sm docChartFilter" data-value="5">5Y</button>
                            </div>
                        </div><!-- end card header -->

                        <div class="card-body p-0 pb-2">
                            <div class="w-100">
                                <div id="doc-chart" data-colors='["--vz-primary", "--vz-danger", "--vz-warning", "--vz-success"]' class="apex-charts" dir="ltr"></div>
                            </div>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </div><!-- end col -->
                <!-- end Document Views & Downloads -->

                <div class="col-xl-4">
                    <div class="card card-height-100">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1" title="Leads Statistics">Leads Statistics</h4>
                            <div class="flex-shrink-0">
                                <div class="dropdown card-header-dropdown">
                                    <a class="text-reset dropdown-btn" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span class="text-muted" id="currentLeadFilter">Current Year <i class="mdi mdi-chevron-down ms-1"></i></span>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item LeadFilter" href="javascript:void(0);" data-value="all">ALL</a>
                                        <a class="dropdown-item LeadFilter" href="javascript:void(0);" data-value="0">Current Years</a>
                                        <a class="dropdown-item LeadFilter" href="javascript:void(0);" data-value="1">Last One Years</a>
                                        <a class="dropdown-item LeadFilter" href="javascript:void(0);" data-value="2">Last Two Years</a>
                                        <a class="dropdown-item LeadFilter" href="javascript:void(0);" data-value="3">Last Three Years</a>
                                        <a class="dropdown-item LeadFilter" href="javascript:void(0);" data-value="4">Last Four Years</a>
                                        <a class="dropdown-item LeadFilter" href="javascript:void(0);" data-value="5">Last Five Years</a>
                                    </div>
                                </div>
                            </div>
                        </div><!-- end card header -->

                        <div class="card-body">
                            <div id="curve_chart" data-colors='["--vz-danger", "--vz-success", "--vz-warning", "--vz-info", "--vz-primary", "--vz-dark"]' class="apex-charts" dir="ltr"></div>
                        </div>
                    </div> <!-- .card-->
                </div> <!-- .col-->
            </div>

            <div class="row">
                <!-- Contact Leads -->
                <div class="col-xl-4">
                    <div class="card contactuslead">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Contact Leads</h4>
                        </div><!-- end card header -->
                        <div class="card-body" data-simplebar style="height: 300px;">
                            <div class="table-responsive"> <!-- table-card -->
                                <table class="table table-hover table-centered align-middle table-nowrap mb-0">
                                    <thead> <!-- class="text-muted table-light" -->
                                        <tr>
                                            <th scope="col">ID</th>
                                            <th scope="col" align="left" title="{{ trans('template.common.name') }}">{{ trans('template.common.name') }}</th>
                                            <th scope="col" align="left" title="{{ trans('template.common.emailid') }}"> {{ trans('template.common.emailid') }} </th>                                            
                                            <th scope="col" align="left" title="{{ trans('template.powerPanelDashboard.receivedDateTime') }}"> {{ trans('template.powerPanelDashboard.receivedDateTime') }}</th>
                                            <th scope="col" align="left" title="{{ trans('template.common.details') }}"> {{ trans('template.common.details') }} </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($leads->isEmpty())
                                            <tr>
                                                <td align="center" colspan="4">
                                                    {{ trans('template.powerPanelDashboard.noContactLead') }} 
                                                    <a target="_blank" href="https://www.netclues.com/social-media-marketing"> 
                                                        {{ trans('template.powerPanelDashboard.here') }}
                                                    </a> 
                                                    {{ trans('template.powerPanelDashboard.findContactLead') }}
                                                </td>
                                            </tr>
                                        @else
                                            @foreach ($leads as $key=>$lead)
                                                @if($key<=4)
                                                <tr>
                                                    <td><span class="fw-medium link-primary">#{!! $lead->id !!}</span></td>
                                                    <td>{!! $lead->varTitle !!}</td>
                                                    <td align="left">
                                                        {!! App\Helpers\MyLibrary::getDecryptedString($lead->varEmail); !!}
                                                    </td>
                                                    <td align="left">
                                                        {{ date(''.Config::get('Constant.DEFAULT_DATE_FORMAT').'  '.Config::get('Constant.DEFAULT_TIME_FORMAT').'', strtotime($lead->created_at)) }}
                                                    </td>
                                                    <td align="left" class='numeric'>
                                                        <a class="contactUsLead" href="javascript:void(0);" type="button" title="{{ trans('template.powerPanelDashboard.clickDetails') }}" id="{!! $lead->id !!}"><i class="ri-arrow-right-up-line"></i></a>
                                                    </td>
                                                </tr>
                                                @endif
                                            @endforeach
                                        @endif <!-- end tr -->
                                    </tbody><!-- end tbody -->
                                </table><!-- end table -->
                            </div>
                        </div>
                        @if(isset($leads) && !empty($leads) && count($leads) > 0 )
                            <div class="card-footer">
                                <div class="justify-content-end">
                                    <a class="btn btn-soft-primary btn-sm" href="{{ url('powerpanel/contact-us') }}" title="{{ trans('template.powerPanelDashboard.seeAllRecords') }}"><i class="ri-file-list-3-line align-middle"></i> {{ trans('template.powerPanelDashboard.seeAllRecords') }}</a>
                                </div>
                            </div>
                        @endif
                    </div> <!-- .card-->
                </div><!-- end col -->
                <!-- end Contact Leads -->

                <!-- In Approval -->
                <div class="col-xl-4">
                    <div class="card">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1" title="In Approval">In Approval</h4>
                            <div class="flex-shrink-0">
                                <div class="dash-approve-search pull-right">
                                    <input type="search" class="form-control form-control-solid placeholder-no-fix" placeholder="Search" id="searchfilter">
                                </div>
                            </div>
                        </div><!-- end card header -->
                        <div class="card-body" data-simplebar style="height: 350px;">
                            <div class="table-responsive"> <!-- table-card -->
                                <table class="table table-hover table-centered align-middle table-nowrap mb-0" id="approvals">
                                    <thead> <!-- class="text-muted table-light" -->
                                        <tr>
                                            <th scope="col">ID</th>
                                            <th scope="col" align="left" title="Module"> Module </th>
                                            <!-- <th scope="col" align="left" title="View"> View </th> -->
                                            <th scope="col" align="left" title="Date &amp; Time"> Date &amp; Time </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($approvals->isEmpty())
                                            <tr><td align="center" colspan="4">No data available</td></tr>
                                        @else
                                            @foreach ($approvals as $key=>$approval)
                                                @if(auth()->user()->can($approval->varModuleName.'-reviewchanges'))
                                                <tr>
                                                    <td><span class="fw-medium link-primary"><a href="{{ url('powerpanel/'.$approval->varModuleName) }}?tab=A" title="{!! $approval->moduleName !!}">#{!! $approval->id !!}</a></span></td>
                                                    <td><a class="body-color" href="{{ url('powerpanel/'.$approval->varModuleName.'?tab=A') }}" title="{!! $approval->moduleName !!}">{!! $approval->moduleName !!}</a></td>
                                                    <!-- <td align="left"><a href="{{ url('powerpanel/'.$approval->varModuleName) }}?tab=A" title="{!! $approval->moduleName !!}"><span class="badge badge-soft-primary">View</span></a></td> -->
                                                    <td align="left">{{ date(Config::get('Constant.DEFAULT_DATE_FORMAT').'  '.Config::get('Constant.DEFAULT_TIME_FORMAT'), strtotime($approval->created_at)) }}</td>
                                                </tr>
                                                @else
                                                <tr>
                                                    <td><span class="fw-medium link-primary">#{!! $approval->id !!}</span></td>
                                                    <td><a href="{{ url('powerpanel/workflow') }}" title="Create workflow for {!! $approval->moduleName !!}">{!! $approval->moduleName !!} <span class="badge badge-pill badge-danger">No Workflow</span></a></td>
                                                    <td align="left"><a href="{{ url('powerpanel/'.$approval->varModuleName) }}" title="{!! $approval->moduleName !!}"><span class="badge badge-soft-primary">View</span></a></td>
                                                    <td align="left">{{ date(Config::get('Constant.DEFAULT_DATE_FORMAT').'  '.Config::get('Constant.DEFAULT_TIME_FORMAT'), strtotime($approval->created_at)) }}</td>
                                                </tr>
                                                @endif
                                            @endforeach
                                        @endif
                                    </tbody><!-- end tbody -->
                                </table><!-- end table -->
                            </div>
                        </div><!-- end card-body -->
                    </div><!-- end card -->
                </div> <!-- .col-->

                
                <!-- In Approval -->
                <div class="col-xl-4">
                    <div class="card">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1" title="In Approval">Form Builder Leads</h4>
                        </div><!-- end card header -->
                        <div class="card-body" data-simplebar style="height: 300px;">
                            <div class="table-responsive"> <!-- table-card -->
                                <table class="table table-hover table-centered align-middle table-nowrap mb-0 formbuilder-table" id="formbuilder_leeds">
                                    <thead> <!-- class="text-muted table-light" -->
                                        <tr>
                                            <th class="date-r" scope="col" title="Date">Date</th>
                                            <th class="name-r" scope="col" align="left" title="Name"> Name </th>
                                            <th class="content-r" scope="col" align="left" title="View"> Contents </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(empty($formBuilderLead))
                                            <tr><td align="center" colspan="4">No data available</td></tr>
                                        @else
                                            @foreach ($formBuilderLead as $key=>$formBuilder)
                                                @if($key<=4)
                                                <tr>
                                                    <td class="date-r" align="left">
                                                        <div class="mini-stats-wid">
                                                            <div class="flex-shrink-0 avatar-sm">
                                                                <span class="mini-stat-icon avatar-title rounded-circle text-success bg-soft-success fs-4">
                                                                    {{ date('d', strtotime($formBuilder[5])) }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="name-r">
                                                        <h6 class="mb-1">{!! $formBuilder[1] !!}</h6>
                                                        <p class="text-muted mb-0">{!! $formBuilder[2] !!}</p>
                                                    </td>
                                                    <td class="content-r"><i class="ri-feedback-line fs-24"></i> {!! $formBuilder[3] !!}</td>
                                                </tr>
                                                @endif
                                            @endforeach
                                        @endif
                                    </tbody><!-- end tbody -->
                                </table><!-- end table -->
                            </div>
                        </div><!-- end card-body -->
                        @if(isset($formBuilderLead) && !empty($formBuilderLead) && count($formBuilderLead) > 0 )
                            <div class="card-footer">
                                <div class="justify-content-end">
                                    <a class="btn btn-soft-primary btn-sm" href="{{ url('powerpanel/formbuilder-lead') }}" title="{{ trans('template.powerPanelDashboard.seeAllRecords') }}"><i class="ri-file-list-3-line align-middle"></i> {{ trans('template.powerPanelDashboard.seeAllRecords') }}</a>
                                </div>
                            </div>
                        @endif
                    </div><!-- end card -->
                </div> <!-- .col-->

            </div>
        </div> <!-- end .h-100-->
    </div> <!-- end col -->

    @include('powerpanel.partials.rightbar')
</div>
<!-- End Page-content -->

<div id="detailsCmsPage" class="modal fade detailsCmsPage" tabindex="-1" aria-labelledby="detailsCmsPage" aria-hidden="true"></div>
<!-- <div id="detailsContactUsLead" class="modal fade detailsContactUsLead" tabindex="-1" aria-labelledby="detailsContactUsLead" aria-hidden="true"></div> -->
<div class="modal fade BlogDetails" tabindex="-1" aria-labelledby="BlogDetails" aria-hidden="true"></div>
<!-- ContactUsLead offcanvas -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="detailsContactUsLead" aria-labelledby="detailsContactUsLead">
</div>

@include('powerpanel.partials.cmsPageCommentsUser')
<script>
    function loadModelpopup(id, intRecordID, fkMainRecord, varModuleNameSpace, intCommentBy, varModuleTitle) {
        $('#CmsPageComments1User').show();
        $('#CmsPageComments1User').modal({
            backdrop: 'static',
            keyboard: false
        });
        document.getElementById('id').value = id;
        document.getElementById('intRecordID').value = intRecordID;
        document.getElementById('fkMainRecord').value = fkMainRecord;
        document.getElementById('varModuleNameSpace').value = varModuleNameSpace;
        document.getElementById('intCommentBy').value = intCommentBy;
        document.getElementById('varModuleTitle').value = varModuleTitle;
        document.getElementById('CmsPageComments_user').value = '';
        $.ajax({
            type: "POST",
            url: window.site_url + "/powerpanel/dashboard/Get_Comments_user",
            data: {'id': id, 'intRecordID': intRecordID, 'fkMainRecord': fkMainRecord, 'varModuleNameSpace': varModuleNameSpace, 'intCommentBy': intCommentBy, 'varModuleTitle': varModuleTitle},
            async: false,
            success: function (data)
            {
                document.getElementById('test').innerHTML = data;
            }
        });
    }
</script>
@endsection

@section('scripts')
<script type="text/javascript">
    window.site_url = '{!! url("/") !!}';
    $(document).on("click", ".dashboard_checkbox", function() {
        $('body').loader(loaderConfig);
        var widgetkey = $(this).val();
        if ($(this).prop('checked') == true) {
            var widget_disp = 'Y';
        } else {
        var widget_disp = 'N';
        }
        $.ajax({
            type: "POST",
            url: site_url + "/powerpanel/dashboard/updatedashboardsettings",
            data: {'widgetkey':widgetkey, 'widget_disp':widget_disp},
            async: false,
            beforeSend:function() {
                $('body').loader(loaderConfig);
            },
            success: function (data) {
                window.location.reload();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                $.loader.close(true);
                alert("Error:" + thrownError);
                window.location.reload();
            }
        });
    });
</script>


<script src="{{ $CDN_PATH.'resources/global/plugins/highslide/highslide-with-html.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/dashboard-ajax.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/global/plugins/datatables/datatables.min.js' }}" type="text/javascript"></script>
<!-- <script src="{{ $CDN_PATH.'resources/global/scripts/jquery-ui.js' }}" type="text/javascript"></script> -->
<script>
    // $(function () {
    //     $("#sortable1").sortable({
    //         connectWith: ".connectedSortable",
    //         update: function () {
    //             dashBoardUpdate('.ui-sortable-handle');
    //         }
    //     }).disableSelection();
    // });
    function dashBoardUpdate(row) {
    var rows = $(row);
        var order = [];
        $.each(rows, function (index) {
        order.push($(this).data('id'));
        });
        $.ajax({
            type: "POST",
            url: window.site_url + "/powerpanel/dashboard/updateorder",
            data: {'order':JSON.stringify(order)},
            async: false,
            success: function (data)
            {
            }
        });
    }
</script>
<script type="text/javascript">
    @if (Session::has('alert-success'))
        toastr.options = {
        "closeButton": true,
            "debug": false,
            "positionClass": "toast-top-right",
            "onclick": null,
            "showDuration": "1000",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }
        toastr.success("{{Session::get('alert-success')}} Welcome to {{Config::get('Constant.SITE_NAME')}}.");
    @endif
    @if (Session::has('alert-success'))
        $("#topMsg").show().delay(5000).fadeOut();
        $("#topMsg").fadeOut("slow", function () {
            $('.page-header').css('top', '0');
            $('.page-container').css('top', '0');
        });
    @endif
    $(document).on('click', '#close_icn', function (e) {
        $("#topMsg").hide();
        $('.page-header').css('top', '0');
        $('.page-container').css('top', '0');
    });
    // $(".mcscroll").mCustomScrollbar({
    //     axis: "yx",
    //     theme: "minimal-dark"
    // });
    var dataTable = $('#approvals').DataTable({
    "paging": false,
        "ordering": false,
        "info": false,
        "oLanguage": {
            "sEmptyTable": "No Approvals are pending"
        }
    });
    $("#searchfilter").keyup(function () {
        dataTable.search(this.value).draw();
    });
</script>

<!-- <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script> -->
<script src="{{ $CDN_PATH.'resources/pages/scripts/packages/documentreport/document-chart.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'resources/pages/scripts/dashboard-chart.js' }}" type="text/javascript"></script>

<script type="text/javascript">
    docChartData({!! $docChartData !!});
    LeadChart({!! $leadsChart !!});
</script>
@endsection