<!-- BEGIN HEADER -->
<header id="page-topbar">
    <div class="layout-width">
        <div class="navbar-header">
            <div class="d-flex">
                <!-- LOGO -->
                <div class="navbar-brand-box horizontal-logo">
                    <a href="{{ url('/powerpanel') }}" class="logo logo-dark">
                        <span class="logo-sm">
                            <img src="{{ Config::get('Constant.CDN_PATH').'resources/images/Powerpanel-logo.png' }}" alt="{{ Config::get('Constant.SITE_NAME') }}"> <!-- height="22" -->
                        </span>
                        <span class="logo-lg">
                            <img src="{{ Config::get('Constant.CDN_PATH').'resources/images/Powerpanel-logo.png' }}" alt="{{ Config::get('Constant.SITE_NAME') }}"> <!-- height="17" -->
                        </span>
                    </a>
                    <a href="{{ url('/powerpanel') }}" class="logo logo-light">
                        <span class="logo-sm">
                            <img src="{{ Config::get('Constant.CDN_PATH').'resources/images/Powerpanel-logo-light.png' }}" alt="{{ Config::get('Constant.SITE_NAME') }}"> <!-- height="22" -->
                        </span>
                        <span class="logo-lg">
                            <img src="{{ Config::get('Constant.CDN_PATH').'resources/images/Powerpanel-logo-light.png' }}" alt="{{ Config::get('Constant.SITE_NAME') }}"> <!-- height="17" -->
                        </span>
                    </a>
                </div>

                <button type="button" class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn topnav-hamburger" id="topnav-hamburger-icon">
                    <span class="hamburger-icon">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </button>
                <h4 class="mb-sm-0 dashboard_title fw-semibold me-4 text-capitalize">{{ $breadcrumb['title']}}</h4>
                <!-- App Search -->
                <form class="app-search d-none d-md-block" onsubmit="return false;">
                    <div class="position-relative">
                        <input type="text" class="form-control" placeholder="Search..." autocomplete="off" name="inputsearch" id="search-options" value="">
                        <span class="ri-search-2-line search-widget-icon"></span>
                        <span class="mdi mdi-close-circle search-widget-icon search-widget-icon-close d-none" id="search-close-options"></span>
                    </div>
                    <div class="dropdown-menu dropdown-menu-lg" id="search-dropdown">
                    	<div class="search-height" data-simplebar>
                            <div class="resultdata"></div>
                        </div>
                    	<div class="text-center pt-3 pb-1 border-top" id="view_all_result" style="display:none;">
                          <a href="javascript:void(0);" class="text-underline">View All Results <i class="ri-arrow-right-line ms-1"></i></a>
                      </div>
                    </div>
                </form>
            </div>



            <div class="d-flex align-items-center">
                <!-- Not Used Code -->
                <div class="dropdown d-none topbar-head-dropdown header-item">
                    <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle" id="page-header-search-dropdown"  data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="bx bx-search fs-22"></i>
                    </button>
                </div>

                <div class="dropdown topbar-head-dropdown ms-1 header-item d-none">
                    <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle" id="page-header-cart-dropdown" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="false">
                        <i class='bx bx-shopping-bag fs-22'></i>
                        <span class="position-absolute topbar-badge cartitem-badge fs-10 translate-middle badge rounded-pill bg-info">5</span>
                    </button>

                    <div class="dropdown-menu dropdown-menu-xl dropdown-menu-end p-0 dropdown-menu-cart" aria-labelledby="page-header-cart-dropdown">
                        <div data-simplebar style="max-height: 300px;">
                            <div class="p-2">
                                <div class="text-center empty-cart" id="empty-cart">
                                </div>
                            </div>

                        </div>

                        <div class="p-3 border-bottom-0 border-start-0 border-end-0 border-dashed border" id="checkout-elem">
                            <div class="d-flex justify-content-between align-items-center pb-3">
                                <h5 class="m-0 text-muted">Total:</h5>
                                <div class="px-2">
                                    <h5 class="m-0" id="cart-item-total">$1258.58</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- end. Not Used Code -->

                <!-- Date time display -->

                @php

                $dateFormate = DB::table('general_setting')->select('fieldName','fieldValue')->where('fieldName','DEFAULT_TIME_FORMAT')->first();

                @endphp

                <script type="text/javascript">

                    var date_time = "{{ ($dateFormate->fieldValue == 'H:i') ? '24':'12' }}";

                    var x = new Date('<?php print date("F d, Y H:i:s")?>')

                    function display_c(){

                        x.setSeconds(x.getSeconds() + 1);

                        var refresh=1000; // Refresh rate in milli seconds

                        mytime=setTimeout('display_ct()',refresh)

                    }

                    function padzero(num,count) {

                        var num = num + '';

                        while(num.length < count) {

                            num = "0" + num;

                        }

                        return num;

                    }

                    function display_ct() {

                        var weekday=new Array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday")

                        var monthname=new Array("Jan","Feb","March","April","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec")

                        var y;

                        y = monthname[x.getMonth()] + " "

                        y = y + padzero(x.getDate(), 2)

                        y = y + ", "

                        y = y + x.getFullYear()

                        document.getElementById('ct').innerHTML = y

                        if(date_time == '12'){

                            var hours = x.getHours();

                            var minutes = x.getMinutes();

                            var ampm = hours >= 12 ? 'PM' : 'AM';

                            hours = hours % 12;

                            hours = hours ? hours : 12; // the hour '0' should be '12'

                            minutes = minutes < 10 ? '0'+minutes : minutes;

                            var T = hours + ':' + minutes + ' ' + ampm;

                            document.getElementById('ct_2').innerHTML = T

                        }else{

                            var Q;

                            var zeroMin = x.getMinutes() <= 9 ? '0' : '';

                            Q = x.getHours();

                            Q = Q + ":"

                            Q = Q + zeroMin + x.getMinutes();

                            document.getElementById('ct_2').innerHTML = Q

                        }

                        tt=display_c();

                    }

                </script>

                <div class="ms-1 header-item d-none d-xl-flex date_clock">

                    <div class="date_timer_powerpanel">

                        <i class="ri-calendar-2-line fs-22 me-1"></i>

                        @if($dateFormate->fieldValue == 'H:i')

                        <span id="ct"><?php echo date("F j, Y"); ?></span>&nbsp;<span id="ct_2"><?php echo date("H:i"); ?></span>

                        @else

                        <span id="ct"><?php echo date("F j, Y"); ?></span>&nbsp;<span id="ct_2" class="d-none"><?php echo date("g:i a"); ?></span>

                        @endif

                    </div>

                </div>



                <!-- Support Browser -->

                <div class="dropdown ms-1 topbar-head-dropdown header-item">

                    <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="ri-camera-lens-line fs-22"></i>
                    </button>

                    <div class="dropdown-menu dropdown-menu-lg p-0 dropdown-menu-end">

                        @php

                        $version = App\Http\Controllers\PowerpanelController::GetVersion();

                        $browser = App\Http\Controllers\PowerpanelController::GetBrowser();

                        $versiondata = explode(".",$version);

                        if($versiondata[0] <= 75 && $browser=='Chrome'){

                        $cromelink = 'https://chrome.en.softonic.com/download';

                        }else{

                        $cromelink = '';

                        }if($versiondata[0] <= 11 && $browser=='IE'){

                        $internatelink = 'https://internet_explorer.en.downloadastro.com/';

                        }else{

                        $internatelink = '';

                        }if($versiondata[0] <= 11 && $browser=='EDGE'){

                        $edgelink = 'https://www.microsoft.com/en-us/edge';

                        }else{

                        $edgelink = '';

                        }if($versiondata[0] <= 68 && $browser=='Firefox'){

                        $firefoxlink = 'https://mozilla_firefox.en.downloadastro.com/';

                        }else{

                        $firefoxlink = '';

                        }if($versiondata[0] <= 5 && $browser=='Safari'){

                        $safarilink = 'https://safari.en.softonic.com/download';

                        }else{

                        $safarilink = '';

                        }if($versiondata[0] <= 62 && $browser=='Opera'){

                        $operalink = 'https://opera.en.softonic.com/download';

                        }else{

                        $operalink = '';

                        }

                        @endphp

                        <div class="p-2">

                            <div class="row g-0">

                                <div class="col">

                                    <!-- Google Chrome-->

                                    <a href="javascript:void(0);" class="dropdown-icon-item" data-lang="en"

                                        title="English">

                                        <img src="{{ Config::get('Constant.CDN_PATH').'assets/images/chrome.png' }}" alt="Google Chrome">

                                        <span class="align-middle">Google Chrome 75 +</span>

                                        @if($versiondata[0] <= 75 && $browser=='Chrome')

                                        <a class="update_div" href="{{ $cromelink }}" target="_blank" title="Latest Version Update">Latest Version Update</a>

                                        @endif

                                    </a>

                                </div>

                                <div class="col">

                                    <!-- Internet Explorer-->

<!--                                    <a href="javascript:void(0);" class="dropdown-icon-item" data-lang="sp"

                                        title="Spanish">



                                        <img src="{{ Config::get('Constant.CDN_PATH').'assets/images/internet-explorer.png' }}" alt="internet explorer">

                                        <span class="align-middle">Internet Explorer 11 +</span>

                                        @if($versiondata[0] <= 11 && $browser=='IE')

                                        <a class="update_div" href="{{ $internatelink }}" target="_blank" title="Latest Version Update">Latest Version Update</a>

                                        @endif

                                    </a>-->
                                    <a href="javascript:void(0);" class="dropdown-icon-item" data-lang="sp"

                                        title="Spanish">



                                        <img src="{{ Config::get('Constant.CDN_PATH').'assets/images/microsoft-edge.png' }}" alt="Microsoft Edge">

                                        <span class="align-middle">Microsoft Edge 100+</span>

                                        @if($versiondata[0] <= 11 && $browser=='IE')

                                        <a class="update_div" href="{{ $edgelink }}" target="_blank" title="Latest Version Update">Latest Version Update</a>

                                        @endif

                                    </a>

                                </div>

                            </div>

                            <div class="row g-0">

                                <div class="col">

                                    <!-- Firefox-->

                                    <a href="javascript:void(0);" class="dropdown-icon-item" data-lang="gr"

                                        title="German">



                                        <img src="{{ Config::get('Constant.CDN_PATH').'assets/images/firefox.png' }}" alt="firefox">

                                        <span class="align-middle">Firefox 68 +</span>

                                        @if($versiondata[0] <= 68 && $browser=='Firefox')

                                        <a class="update_div" href="{{ $firefoxlink }}" target="_blank" title="Latest Version Update">Latest Version Update</a>

                                        @endif

                                    </a>

                                </div>

                                <div class="col">

                                    <!-- Safari-->

                                    <a href="javascript:void(0);" class="dropdown-icon-item" data-lang="it"

                                        title="Italian">



                                        <img src="{{ Config::get('Constant.CDN_PATH').'assets/images/safari.png' }}" alt="safari">

                                        <span class="align-middle">Safari 5 +</sapn>

                                        @if($versiondata[0] <= 5 && $browser=='Safari')

                                        <a class="update_div" href="{{ $safarilink }}" target="_blank" title="Latest Version Update">Latest Version Update</a>

                                        @endif

                                    </a>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                

                <!-- Live User -->

                @php

                $menuArr = App\Helpers\PowerPanelSidebarConfig::getConfig();

                @endphp

                @if(isset($menuArr['can-liveuser-list']) && $menuArr['can-liveuser-list'])

                <div class="ms-1 header-item d-none viewsitemobile {{ $menuArr['liveuser_active'] }}"> <!-- d-sm-flex -->

                    <a href="{{ url('powerpanel/live-user') }}" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle" title="Live User">

                        <i class=" ri-account-circle-line fs-22"></i>

                        <span class="position-absolute topbar-badge fs-10 translate-middle badge rounded-pill bg-success notification-count d-none">

                        </span>

                    </a>

                </div>

                @endif

                

                <!-- Google Analytics -->

                @php

                $GTM = DB::table('general_setting')

                ->where('fieldName','GOOGLE_TAG_MANAGER_FOR_BODY')

                ->get();

                $GTMdata = $GTM[0]; 

                @endphp

                <div class="dropdown topbar-head-dropdown ms-1 header-item d-none">

                    <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="{{ trans('template.header.googleAnalytic') }}">

                        <!-- {{ trans('template.header.googleAnalytic') }} -->

                        <i class="ri-google-line fs-22"></i>

                    </button>

                    <div class="dropdown-menu dropdown-menu-lg p-0 dropdown-menu-end">

                        <div class="p-2">

                            <div class="row g-0">

                                <div class="col">

                                    @if(!empty($GTMdata->fieldValue))

                                        <p>Your website is connected to Google Analytics. <a href="https://analytics.google.com/"  style="color:#1D4DA1" target="_blank">Click here</a> to access Google Analytic</p>

                                        @else

                                        <p>Your website is not connected to Google Analytics. <a href="https://analytics.google.com/"  style="color:#1D4DA1" target="_blank">Click here</a> to access Google Analytic</p>

                                    @endif

                                </div>

                            </div>

                        </div>

                    </div>

                </div>



                <!-- Fullscreen -->

                <div class="ms-1 header-item d-none d-sm-flex">
                    <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle" data-toggle="fullscreen">
                        <i class='bx bx-fullscreen fs-22'></i>
                    </button>
                </div>

                <!-- Website -->
                {{-- <div class="ms-1 header-item website-url d-sm-flex">
                    <a href="{{url('/')}}" title="View Website" target="_blank" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle">
                        <i class='bx bx bx-globe fs-22'></i>
                    </a>
                </div> --}}


                <!-- Night mode -->
                <div class="ms-1 header-item d-none"> <!-- d-sm-flex -->
                    <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle light-dark-mode">
                        <i class='bx bx-moon fs-22'></i>
                    </button>
                </div>

                <!-- Notification -->
                <div class="dropdown topbar-head-dropdown ms-1 header-item d-none">
                    <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle" id="notification" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class='bx bx-bell fs-22'></i>
                        <span class="position-absolute topbar-badge fs-10 translate-middle badge rounded-pill bg-primary notification-count d-none">
                        </span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0" id="notification-view" aria-labelledby="notification">
                    </div>

                </div>



                <div class="develop-by ms-3">
                    <a href="https://www.netclues.ky" target="_blank" rel="nofollow" title="Netclues">
                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:a="http://ns.adobe.com/AdobeSVGViewerExtensions/3.0/" x="0px" y="0px" width="372px" height="63px" viewBox="0 0 372 63" enable-background="new 0 0 372 63" xml:space="preserve">

                            <g>

                                <defs>

                                    <rect id="SVGID_1_" width="371.848" height="63.465"/>

                                </defs>

                                <clipPath id="SVGID_2_">

                                    <use xlink:href="#SVGID_1_"  overflow="visible"/>

                                </clipPath>

                                <path class="fill-clr" clip-path="url(#SVGID_2_)" fill="#414042" d="M360.636,28.749l-0.049-0.022c-0.619-0.266-2.888-1.14-5.081-1.985l-1.866-0.72

                                    c-2.751-1.062-3.543-1.451-3.511-3.316c0.008-0.473,0.289-0.983,0.771-1.398c0.954-0.822,2.471-1.167,4.16-0.946 c3.278,0.43,4.515,2.936,4.563,3.041l0.259,0.55l8.459-4.489l-0.353-0.532c-0.202-0.306-5.069-7.504-14.587-7.504 c-6.792,0-9.621,3.273-11.327,6.124c-1.278,2.136-2.068,6.135-0.783,9.871c1.029,2.988,3.226,5.207,6.353,6.415l7.63,2.947 c2.709,1.048,4.343,2.256,4.855,3.591c0.309,0.805,0.217,1.67-0.278,2.645c-0.643,1.267-2.529,2.045-4.583,1.897 c-2.841-0.207-5.115-2.042-6.081-4.909l-0.17-0.505l-9.09,2.252l0.129,0.556c2.558,10.924,12.664,11.784,15.7,11.787h0.02 c6.459,0,13.539-3.396,14.055-12.929C370.118,35.838,366.853,31.427,360.636,28.749 M221.504,11.427

                                    c-11.77,0-21.347,9.576-21.347,21.346c0,11.771,9.577,21.347,21.347,21.347c2.957,0,5.814-0.591,8.493-1.758l0.349-0.149V40.825 l-0.962,0.852c-2.174,1.928-4.972,2.989-7.88,2.989c-6.556,0-11.891-5.335-11.891-11.893c0-6.558,5.335-11.895,11.891-11.895 c2.908,0,5.706,1.061,7.88,2.988l0.962,0.852V13.336l-0.349-0.152C227.318,12.019,224.461,11.427,221.504,11.427 M246.018,11.428 h-9.452v42.647h23.658v-9.45h-14.206V11.428z M168.615,20.879h9.332v33.196h9.452V20.879h9.33v-9.45h-28.113V20.879z M305.797,54.054h28.114v-9.45h-18.663v-7.137h18.663v-9.45h-18.663v-7.138h18.663v-9.451h-28.114V54.054z M134.282,54.054h28.114 v-9.45h-18.663v-7.137h18.663v-9.45h-18.663v-7.138h18.663v-9.451h-28.114V54.054z M118.611,34.669L89.579,10.781v43.275h9.452 v-23.26l29.031,23.889V11.427h-9.451V34.669z  M290.126,36.905c0,4.255-3.462,7.717-7.717,7.717s-7.716-3.462-7.716-7.717v-25.43

                                    h-9.451v25.43c0,6.113,3.289,11.813,8.583,14.873c2.594,1.501,5.563,2.295,8.584,2.295c3.02,0,5.988-0.796,8.584-2.299 c5.295-3.062,8.584-8.759,8.584-14.869V11.489h-9.451V36.905z"/>

                                <path clip-path="url(#SVGID_2_)" fill="#5981C1" d="M59.914,61.914c-1.034,1.034-2.391,1.551-3.741,1.551

                                    c-1.357,0-2.713-0.517-3.747-1.551L1.551,11.04c-2.067-2.068-2.067-5.42,0-7.489c2.07-2.068,5.42-2.068,7.488,0l6.337,6.337 c0.006,0.005,0.006,0.005,0.01,0.01l23.405,23.411c0.021,0.015,0.036,0.028,0.054,0.05c0.006,0.004,0.006,0.004,0.01,0.009 l21.059,21.064C61.981,56.494,61.981,59.851,59.914,61.914"/>

                                <path clip-path="url(#SVGID_2_)" fill="#19A362" d="M29.792,54.436c-1.035,1.035-2.392,1.552-3.742,1.552

                                    c-1.357,0-2.713-0.517-3.746-1.552L1.55,33.684c-2.067-2.068-2.067-5.419,0-7.488c2.07-2.068,5.42-2.068,7.489,0l20.752,20.753 C31.858,49.017,31.858,52.368,29.792,54.436"/>

                                <path clip-path="url(#SVGID_2_)" fill="#64C6C2" d="M15.391,61.865c-1.035,1.034-2.391,1.552-3.742,1.552

                                    c-1.357,0-2.712-0.518-3.747-1.552l-6.351-6.352c-2.068-2.068-2.068-5.419,0-7.488c2.069-2.068,5.421-2.068,7.489,0l6.351,6.352 C17.458,56.445,17.458,59.797,15.391,61.865"/>

                                <path clip-path="url(#SVGID_2_)" fill="#B21F5F" d="M59.914,39.25c-1.034,1.033-2.391,1.552-3.741,1.552

                                    c-1.357,0-2.713-0.519-3.747-1.552L31.674,18.498c-2.068-2.067-2.068-5.419,0-7.488c2.064-2.068,5.42-2.068,7.484,0l9.312,9.308 l11.444,11.443C61.981,33.83,61.981,37.182,59.914,39.25"/>

                                <path clip-path="url(#SVGID_2_)" fill="#F99F1E" d="M46.074,3.581c1.034-1.034,2.391-1.552,3.742-1.552

                                    c1.356,0,2.712,0.518,3.745,1.552l0.006,0.004l6.346,6.351c2.069,2.065,2.069,5.42,0,7.485c-2.068,2.068-5.419,2.068-7.487,0 l-6.352-6.352C44.007,9.001,44.007,5.649,46.074,3.581"/>

                                <path clip-path="url(#SVGID_2_)" fill="#466BB3" d="M59.914,61.914c-1.034,1.034-2.391,1.551-3.741,1.551

                                    c-1.357,0-2.713-0.517-3.747-1.551l-21.058-21.06c-2.07-2.067-2.07-5.42,0-7.487c2.043-2.039,5.35-2.064,7.423-0.059 c0.021,0.015,0.036,0.028,0.054,0.05c0.006,0.004,0.006,0.004,0.01,0.009l21.059,21.064C61.981,56.494,61.981,59.851,59.914,61.914"/>

                                <path clip-path="url(#SVGID_2_)" fill="#8EC64E" d="M29.792,54.436c-1.035,1.035-2.392,1.552-3.742,1.552

                                    c-1.357,0-2.713-0.517-3.746-1.552l-9.298-9.298c-2.068-2.068-2.068-5.42,0-7.488c2.067-2.067,5.42-2.067,7.487,0l9.299,9.299 C31.858,49.017,31.858,52.368,29.792,54.436"/>

                                <path clip-path="url(#SVGID_2_)" fill="#96D8E7" d="M15.391,61.865c-1.035,1.034-2.391,1.552-3.742,1.552

                                    c-1.357,0-2.712-0.518-3.747-1.552L7.898,61.86c-2.069-2.068-2.069-5.42,0-7.488c2.067-2.067,5.42-2.067,7.487,0l0.006,0.005 C17.458,56.445,17.458,59.797,15.391,61.865"/>

                                <path clip-path="url(#SVGID_2_)" fill="#DF4F43" d="M48.47,27.806c-1.035,1.035-2.39,1.552-3.742,1.552

                                    c-1.357,0-2.713-0.517-3.746-1.552l-9.308-9.308c-2.068-2.068-2.068-5.42,0-7.487c2.064-2.068,5.42-2.068,7.483,0l9.313,9.308 C50.538,22.387,50.538,25.738,48.47,27.806"/>

                                <path clip-path="url(#SVGID_2_)" fill="#FFC81B" d="M46.074,3.581c1.034-1.034,2.391-1.552,3.742-1.552

                                    c1.356,0,2.712,0.518,3.745,1.552l0.006,0.004c2.068,2.069,2.068,5.421,0,7.489c-2.068,2.067-5.42,2.067-7.487,0l-0.006-0.005 C44.007,9.001,44.007,5.649,46.074,3.581"/>

                            </g>

                        </svg>

                    </a>    

                </div>



                <!-- Profile Detail -->

                <div class="dropdown header-item topbar-user d-none"> <!-- ms-sm-3 -->

                    <button type="button" class="btn" id="page-header-user-dropdown" data-bs-toggle="dropdown"

                        aria-haspopup="true" aria-expanded="false">

                        <span class="d-flex align-items-center">

                            <img class="header-profile-user" src="{{ $User_logo_url }}" alt="{{ auth()->user()->name }}">

                            <span class="text-start ms-xl-2">

                                <span class="d-none d-xl-inline-block ms-1 fw-medium user-name-text">{{ auth()->user()->name }}</span>

                            </span>

                        </span>

                    </button>

                    <div class="dropdown-menu dropdown-menu-end">

                        <!-- item-->

                        <h6 class="dropdown-header">Welcome!</h6>

                        

                        <a class="dropdown-item" href="{{ url('/powerpanel/changeprofile') }}" title="{{ trans('template.header.myProfile') }}">

                            <i class="mdi mdi-account-circle text-muted fs-16 align-middle me-1"></i> 

                            <span class="align-middle">{{ trans('template.header.myProfile') }}</span>

                        </a>



                        @can('settings-general-setting-management')

                        <a class="dropdown-item" href="{{ url('/powerpanel/settings') }}" title="{{ trans('template.header.settings') }}">

                            <i class="mdi mdi-cog-outline text-muted fs-16 align-middle me-1"></i> 

                            <span class="align-middle">{{ trans('template.header.settings') }}</span>

                        </a>

                        @endcan

                        

                        @if(isset($menuArr['can-security-list']) && $menuArr['can-security-list'])

                        <a class="dropdown-item" title="Security Settings" href="{{ url('powerpanel/security-settings') }}" {{ $menuArr['security_active'] }}>

                            <i class="mdi mdi-lock text-muted fs-16 align-middle me-1"></i>

                            <span class="align-middle">Security</span>

                        </a>

                        @endif

                        

                        <a class="dropdown-item" title="{{ trans('template.header.changePassword') }}" href="{{ url('/powerpanel/changepassword') }}">

                            <i class="mdi mdi-lock text-muted fs-16 align-middle me-1"></i>

                            <span class="align-middle">{{ trans('template.header.changePassword') }}</span>

                        </a>

                        

                        <a class="dropdown-item" title="{{ trans('template.header.logOut') }}" href="javascript:void(0);" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">

                            <i class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i> 

                            <span class="align-middle" data-key="t-logout">{{ trans('template.header.logOut') }}</span>

                        </a>



                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">

                            {{ csrf_field() }}

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</header>

<!-- END HEADER -->



{{-- @include('powerpanel.partials.googleAnalyticModal') --}}

<!-- END HEADER & CONTENT DIVIDER -->

<script>

    var Read_Notification_URL = '{!! url("/powerpanel/user_notification/update_read_status") !!}';

    function Read_Notification(object, id, ModuleId, RecordId, ModuleName) {

        var redirectmodule = $(object).attr('data-redirectionmodule');

        if (redirectmodule != ""){

            var url = '{!! url("/powerpanel") !!}' + '/' + redirectmodule + '?notifications';

        } else{

            var url = '{!! url("/powerpanel") !!}' + '/' + ModuleName + '?notifications';

        }



        $.ajax({

        type: 'POST',

            url: Read_Notification_URL,

            data: 'id=' + id + '&ModuleId=' + ModuleId + '&RecordId=' + RecordId,

            success: function (msg) {

                window.location.href = url;

            }

        });

    }

</script>

<script>

    var Read_All_Notification_URL = '{!! url("/powerpanel/user_notification/update_read_all_status") !!}';

    var url = '{!! url("/powerpanel/contact-us") !!}';

    function Read_All_Notification() {

        $.ajax({

        type: 'POST',

            url: Read_All_Notification_URL,

            success: function (msg) {

                location.reload();

            }

        });

    }

</script>