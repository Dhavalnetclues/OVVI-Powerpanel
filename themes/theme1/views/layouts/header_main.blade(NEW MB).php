<header>
    <div class="header-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-sm-12 d-flex align-items-center n-mt-20 n-mb-20 n-mb-xl-0">
                    <div class="-left" data-aos="fade-down">
                        <div class="-logo">
                            <a href="{{ url('/') }}" title="{{ Config::get('Constant.SITE_NAME') }}">
                                <img src="{{ App\Helpers\resize_image::resize(Config::get('Constant.FRONT_LOGO_ID')) }}" alt="{{ Config::get('Constant.SITE_NAME') }}" title="{{ Config::get('Constant.SITE_NAME') }}" />
                            </a>
                        </div>
                    </div>
                    <div class="-center">
                        <nav class="menu" id="menu">
                            <div class="menu_mobile_visibility">
                                <a href="{{ url('/') }}" title="{{ Config::get('Constant.SITE_NAME') }}">
                                    <div class="menu_title">
                                        <div class="m_t_text">OfReg <span class="m_t_sub-text">The Utility Regulation and Competition Office</span></div>
                                    </div>
                                </a>
                                <a href="javascript:void(0)" onclick="closeNav()" id="menu__close" title="Menu Close" class="nq-svg">
                                    <img class="svg" src="{{ $CDN_PATH.'assets/images/icon/x.svg' }}" alt="Menu Close">
                                </a>
                            </div>
                            @if(isset($HeadreMenuhtml))
                            {!! $HeadreMenuhtml !!}
                            <div id="full-menu-clone" class="d-block d-xl-none"></div>
                            @endif
                        </nav>
                    </div>
                    <div class="-right">
                        <ul class="-sort-menu d-flex align-items-center w-100 justify-content-end">
                            <li class="-spay mr-xl-auto">
                                <a href="#" title="Pay Online" class="ac-btn ac-btn-primary text-uppercase">Pay Online</a>
                            </li>
                            @if(isset($objContactInfo) && !empty($objContactInfo))
                            @if(!empty($objContactInfo->varPhoneNo))
                            @php
                            $c_phone = unserialize($objContactInfo->varPhoneNo);
                            $c_phone =count($c_phone)>0?$c_phone[0]:$c_phone;
                            @endphp
                            <li class="-stel">
                                <a href="tel:{{ $c_phone }}" title="{{ $c_phone }}" class="n-fs-18 n-fw-600 n-lh-100 nq-svg"><span class="d-inline-block d-lg-none"><img class="svg" src="{{ $CDN_PATH.'assets/images/icon/phone-call.svg' }}" alt="Phone Call"></span> <span class="d-none d-lg-inline-block">{{ $c_phone }}</span></a>
                            </li>
                            @endif
                            @endif

                            <li class="-ssearch">
                                <div class="dropdown ac-dropdown ac-noarrow">
                                    <a class="dropdown-toggle nq-svg" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img class="svg" src="{{ $CDN_PATH.'assets/images/icon/search.svg' }}" alt="Search"></a>

                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                                        <div class="dropdown-item">
                                            <div class="input-group">
                                                <input type="text" class="form-control ac-input" placeholder="Search by Name">
                                                <div class="input-group-append">
                                                    <button type="button" title="Search" class="nq-svg">
                                                        <img class="svg" src="{{ $CDN_PATH.'assets/images/icon/search.svg' }}" alt="Search">
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>

                            <li class="-smenu">
                                <a href="javascript:void(0)" title="Menu" id="menu-toggle" class="nq-svg text-uppercase n-fs-14 n-fw-600 n-lh-100 d-none d-xl-inline-block">
                                    <img class="svg" src="{{ url('cdn/assets/images/icon/menu.svg') }}" alt="Menu">
                                    <span class="d-none d-lg-inline-block">Menu</span>
                                </a>
                                <a href="javascript:void(0)" onclick="openNav()" id="menu__open" title="Menu Open" class="nq-svg d-inline-block d-xl-none">
                                    <img class="svg" src="{{ $CDN_PATH.'assets/images/icon/menu.svg' }}" alt="Menu Open">
                                </a>                                
                            </li>
                        </ul>
                        <div class="nav-overlay" onclick="closeNav()"></div>
                        <!-- <div class="menu_open_close text-right">
                            <a href="javascript:void(0)" class="menu__open" id="menu__open" onclick="openNav()" title="Menu Open"><span></span></a>
                            <a href="javascript:void(0)" class="menu__close" id="menu__close" onclick="closeNav()" title="Menu Close"><span></span></a>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="full-menu d-none d-xl-inline-block">
        <div class="-content">
            <a href="javascript:void(0)" title="Menu Close" id="menu-close" class="nq-svg text-uppercase n-fs-14 n-fw-600 n-lh-100">
                <img class="svg" src="{{ $CDN_PATH.'assets/images/icon/x.svg' }}" alt="Menu Close">
            </a>
            <div class="container">
                <div class="row">
                    <div class="col-sm-8">
                        <h2 class="nqtitle-small text-uppercase n-fc-white-500 n-mb-50">Navigation</h2>

                        <div class="menu-nav mCcontent">
                            <ul id="verticalMenu" class="brand-nav brand-navbar">
                                <li class="sub-menu1">
                                    <a href="{{ url('ict-1') }}" title="ICT" class="collapsed" data-toggle="collapse" data-target="#ict" aria-expanded="false" aria-controls="ict">ICT</a>
                                    <ul id="ict" class="sub-menu collapse" data-parent="#verticalMenu">
                                        <li><a href="{{ url('about-ict-introduction') }}" title="Introduction">Introduction</a></li>
                                        <li><a href="{{ url('ict-licensing') }}" title="Licensing">Licensing</a>
                                            <ul class="sub-menu">
                                                <li><a href="{{ url('ict-types-of-licenses') }}" title="Types of Licenses">Types of Licenses</a>
                                                    <ul class="sub-menu">
                                                        <li><a href="{{ url('ict-broadcasting-introduction') }}" title="Broadcasting">Broadcasting</a>
                                                            <ul class="sub-menu">
                                                                <!-- <li><a href="#" title="Introduction">Introduction</a></li> -->
                                                                <li><a href="{{ url('ict-broadcasting-regulations') }}" title="Regulations">Regulations</a></li>
                                                                <li><a href="#" title="FM Broadcasting Stations">FM Broadcasting Stations</a></li>
                                                                <li><a href="#" title="TV Broadcasting Stations">TV Broadcasting Stations</a></li>
                                                            </ul>
                                                        </li>
                                                        <li><a href="#" title="Telecoms">Telecoms</a>
                                                            <ul class="sub-menu">
                                                                <li><a href="#" title="Introduction">Introduction</a></li>
                                                                <li><a href="#" title="Regulations">Regulations</a></li>
                                                                <li><a href="#" title="Public Record of Key Topics">Public Record of Key Topics</a>
                                                                    <ul class="sub-menu">
                                                                        <li><a href="#" title="FLLRIC (Phase III) Follow-Up Proceeding Public Record">FLLRIC (Phase III) Follow-Up Proceeding Public Record</a></li>
                                                                        <li><a href="#" title="FLLRIC (Phase III) Public Record">FLLRIC (Phase III) Public Record</a></li>
                                                                        <li><a href="#" title="FLLRIC (Phase II) Consultation">FLLRIC (Phase II) Consultation</a></li>
                                                                        <li><a href="#" title="FLLRIC FTR and Transit Rate Review Public Record">FLLRIC FTR and Transit Rate Review Public Record</a></li>
                                                                        <li><a href="#" title="Local Number Portability (LNP) Public Record">Local Number Portability (LNP) Public Record</a></li>
                                                                    </ul>
                                                                </li>
                                                                <li><a href="#" title="Compliance">Compliance</a></li>
                                                                <li><a href="#" title="C&W Service Filings">C&W Service Filings</a></li>
                                                                <li><a href="#" title="C&W Tariffs">C&W Tariffs</a></li>
                                                                <li><a href="#" title="Numbering Policy">Numbering Policy</a></li>
                                                            </ul>
                                                        </li>
                                                        <li><a href="#" title="Radio">Radio</a>
                                                            <ul class="sub-menu">
                                                                <li><a href="#" title="Introduction">Introduction</a></li>
                                                                <li><a href="#" title="Regulations">Regulations</a></li>
                                                                <li><a href="#" title="Type Approval">Type Approval</a></li>
                                                                <li><a href="#" title="Amateur Radio">Amateur Radio</a></li>
                                                                <li><a href="#" title="Aircraft Radio">Aircraft Radio</a></li>
                                                                <li><a href="#" title="Land Mobile">Land Mobile</a></li>
                                                                <li><a href="#" title="Radio Dealer">Radio Dealer</a></li>
                                                                <li><a href="#" title="Ship Radio">Ship Radio</a></li>
                                                            </ul>
                                                        </li>
                                                    </ul>
                                                </li>
                                                <li><a href="#" title="Application Forms and Fees">Application Forms and Fees</a></li>
                                                <li><a href="#" title="Register of Licensees">Register of Licensees</a>
                                                    <ul class="sub-menu">
                                                        <li><a href="#" title="Applications">Applications</a></li>
                                                        <li><a href="#" title="Licences">Licences</a>
                                                            <ul class="sub-menu">
                                                                <li><a href="#" title="WestStar TV Limited (T/A Logic)">WestStar TV Limited (T/A Logic)</a></li>
                                                            </ul>
                                                        </li>
                                                        <li><a href="#" title="Number (NXX) Allocations">Number (NXX) Allocations</a></li>
                                                        <li><a href="#" title="Spectrum Map">Spectrum Map</a></li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </li>
                                        <li><a href="#" title="Register of Infrastructure Agreements">Register of Infrastructure Agreements</a></li>
                                        <li><a href="#" title="Consultations">Consultations</a></li>
                                        <li><a href="#" title="Decisions">Decisions</a></li>
                                        <li><a href="#" title="KY Domain">KY Domain</a>
                                            <ul class="sub-menu">
                                                <li><a href="#" title="Introduction">Introduction</a></li>
                                                <li><a href="#" title="Frequently Asked Questions">Frequently Asked Questions</a></li>
                                                <li><a href="#" title="Domain Policies">Domain Policies</a></li>
                                                <li><a href="#" title="Dispute Resolution Policy">Dispute Resolution Policy</a></li>
                                                <li><a href="#" title="Registering a Domain Name">Registering a Domain Name</a></li>
                                                <li><a href="#" title=".KY Domain Dispute Decisions">.KY Domain Dispute Decisions</a></li>
                                                <li><a href="#" title="WHOIS Information">WHOIS Information</a></li>
                                                <li><a href="#" title="Contact">Contact</a></li>
                                            </ul>
                                        </li>
                                        <li><a href="#" title="ICT Publications">ICT Publications</a>
                                            <ul class="sub-menu">
                                                <li><a href="#" title="Legislation & Regulations">Legislation & Regulations</a></li>
                                                <li><a href="#" title="Reports, Guidelines & Rules">Reports, Guidelines & Rules</a></li>
                                                <li><a href="#" title="Terms of Use">Terms of Use</a></li>
                                                <li><a href="#" title="Gazette Notices">Gazette Notices</a></li>
                                                <li><a href="#" title="Determination Requests">Determination Requests</a></li>
                                                <li><a href="#" title="ICT Decisions">ICT Decisions</a></li>
                                                <li><a href="#" title="The Risks of Text Messages for User Authentication - Paper">The Risks of Text Messages for User Authentication - Paper</a></li>
                                                <li><a href="#" title="Others">Others</a></li>
                                                <li><a href="#" title="Dispute Resolution">Dispute Resolution</a></li>
                                            </ul>
                                        </li>
                                        <li><a href="#" title="Register of Applications">Register of Applications</a></li>
                                        <li><a href="#" title="Archives">Archives</a></li>
                                    </ul>
                                </li>
                                <li class="sub-menu1">
                                    <a href="javascript:void(0);" title="Energy" class="collapsed" data-toggle="collapse" data-target="#energy" aria-expanded="false" aria-controls="energy">Energy</a>
                                    <ul id="energy" class="sub-menu collapse" data-parent="#verticalMenu">
                                        <li><a href="#" title="Licensing">Licensing</a></li>
                                        <li><a href="#" title="Consultations">Consultations</a></li>
                                        <li><a href="#" title="Decisions">Decisions</a></li>
                                        <li><a href="#" title="Energy Publications">Energy Publications</a></li>
                                        <li><a href="#" title="Generation Solicitations">Generation Solicitations</a></li>
                                        <li><a href="#" title="Renewable Energy">Renewable Energy</a></li>
                                        <li><a href="#" title="Archives">Archives</a></li>
                                    </ul>
                                </li>
                                <li class="sub-menu1">
                                    <a href="javascript:void(0);" title="Fuel" class="collapsed" data-toggle="collapse" data-target="#fuel" aria-expanded="false" aria-controls="fuel">Fuel</a>
                                    <ul id="fuel" class="sub-menu collapse" data-parent="#verticalMenu">
                                        <li><a href="#" title="Licensing">Licensing</a></li>
                                        <li><a href="#" title="Consultations">Consultations</a></li>
                                        <li><a href="#" title="Decisions">Decisions</a></li>
                                        <li><a href="#" title="Fuel Publications">Fuel Publications</a></li>
                                        <li><a href="#" title="Fuel Suppliers Registry">Fuel Suppliers Registry</a></li>
                                        <li><a href="#" title="Archives">Archives</a></li>
                                    </ul>
                                </li>
                                <li class="sub-menu1">
                                    <a href="javascript:void(0);" title="Water" class="collapsed" data-toggle="collapse" data-target="#water" aria-expanded="false" aria-controls="water">Water</a>
                                    <ul id="water" class="sub-menu collapse" data-parent="#verticalMenu">
                                        <li><a href="#" title="Licensing">Licensing</a></li>
                                        <li><a href="#" title="Consultations">Consultations</a></li>
                                        <li><a href="#" title="Decisions">Decisions</a></li>
                                        <li><a href="#" title="Water Publications">Water Publications</a></li>
                                        <li><a href="#" title="Archives">Archives</a></li>
                                    </ul>
                                </li>
                                <li><a href="#" title="News">News</a></li>
                                <li><a href="#" title="Event">Event</a></li>
                                <li><a href="#" title="Forms and Fees">Forms and Fees</a></li>
                                <li><a href="#" title="Make a Complaint">Make a Complaint</a></li>
                                <li><a href="#" title="Alerts">Alerts</a></li>
                                <li><a href="#" title="Contact Us">Contact Us</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <h2 class="nqtitle-small text-uppercase n-fc-white-500 n-mb-50">Contact Us</h2>
                         @if(!empty($objContactInfo->varEmail))
                        @php
                        $c_email = unserialize($objContactInfo->varEmail);
                        @endphp
                        @endif
                        @if(isset($c_email) && !empty($c_email))
                        <div class="item">
                            <div class="n-fs-20 n-fw-600 n-fc-white-500">Email:</div>
                            <div class="n-fs-18 n-fw-500 n-fc-white-500 n-lh-130 n-mt-5"><a href="#" title="{{$c_email[0]}}" class="n-ah-black-500">{{$c_email[0]}}</a></div>
                        </div>
                        @endif

                        @if(!empty($objContactInfo->varPhoneNo))
                        @php
                        $c_phone = unserialize($objContactInfo->varPhoneNo);
                        @endphp
                        @endif
                        @if(isset($c_phone) && !empty($c_phone))
                        <div class="item n-mt-lg-30 n-mt-15">
                            <div class="n-fs-20 n-fw-600 n-fc-white-500">Phone:</div>
                            <div class="n-fs-18 n-fw-500 n-fc-white-500 n-lh-130 n-mt-5"><a href="#" title="{{$c_phone[0]}}" class="n-ah-black-500">{{$c_phone[0]}}</a></div>
                        </div>
                        @endif
                        @if(isset($objContactInfo->varFax) && !empty($objContactInfo->varFax))
                        <div class="item n-mt-lg-30 n-mt-15">
                            <div class="n-fs-20 n-fw-600 n-fc-white-500">Fax:</div>
                            <div class="n-fs-18 n-fw-500 n-fc-white-500 n-lh-130 n-mt-5"><a href="#" title="{{$objContactInfo->varFax}}" class="n-ah-black-500">{{$objContactInfo->varFax}}</a></div>
                        </div>
                        @endif
                        @if(isset($objContactInfo->txtAddress)&&  !empty($objContactInfo->txtAddress))
                        <div class="item n-mt-lg-30 n-mt-15">
                            <div class="n-fs-20 n-fw-600 n-fc-white-500">Address:</div>
                            <div class="n-fs-18 n-fw-500 n-fc-white-500 n-lh-130 n-mt-5">{!! nl2br($objContactInfo->txtAddress) !!}</div>
                        </div>
                        @endif
                        @if(isset($objContactInfo->mailingaddress)&&  !empty($objContactInfo->mailingaddress))
                        <div class="item n-mt-lg-30 n-mt-15">
                            <div class="n-fs-20 n-fw-600 n-fc-white-500">Mailing Address:</div>
                            <div class="n-fs-18 n-fw-500 n-fc-white-500 n-lh-130 n-mt-5">{!! nl2br($objContactInfo->mailingaddress) !!}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>