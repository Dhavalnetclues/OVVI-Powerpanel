<header>
    <div class="header-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-sm-12 d-flex align-items-center n-mt-20 n-mb-20 n-mb-xl-0">
                    <div class="-left" data-aos="fade-down">
                        <div class="-logo">
                            <a href="{{ url('/') }}" title="{{ Config::get('Constant.SITE_NAME') }}">                                
                                <img src="{{ $CDN_PATH.'assets/images/logo.svg' }}" alt="{{ Config::get('Constant.SITE_NAME') }}" title="{{ Config::get('Constant.SITE_NAME') }}" />
                                <!-- <img src="{{ App\Helpers\resize_image::resize(Config::get('Constant.FRONT_LOGO_ID')) }}" alt="{{ Config::get('Constant.SITE_NAME') }}" title="{{ Config::get('Constant.SITE_NAME') }}" /> -->
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
                                <a href="javascript:void(0)" onclick="closeNav()" id="menu__close" title="Menu Close">
                                    <i class="n-icon" data-icon="s-x"></i>
                                </a>
                            </div>
                            @if(isset($HeadreMenuhtml))
                                <div class="mobile-wrap">
                                    {!! $HeadreMenuhtml !!}
                                    <div id="full-menu-clone" class="d-block d-xl-none"></div>
                                </div>
                            @endif
                        </nav>
                    </div>
                    <div class="-right">
                        <div class="jfontsize">
                            <a id="jfontsize-m" href="javascript:void(0)">-</a>
                            <a id="jfontsize-d" href="javascript:void(0)">A</a>
                            <a id="jfontsize-p" href="javascript:void(0)">+</a>
                        </div>
                        <ul class="-sort-menu d-flex align-items-center w-100 justify-content-end">
                            <li class="-spay mr-xl-auto">
                                <a href="{{ url('pay-online') }}" title="Pay Online" class="ac-btn ac-btn-primary text-uppercase">Pay Online</a>
                            </li>
                            @if(isset($objContactInfo) && !empty($objContactInfo))
                                @if(!empty($objContactInfo->varPhoneNo))
                                    <li class="-stel">
                                        <a href="tel:{{ $objContactInfo->varPhoneNo }}" title="{{ $objContactInfo->varPhoneNo }}" class="n-fs-18 n-fw-600 n-lh-100 "><span class="d-inline-block d-lg-none"><i class="n-icon" data-icon="s-phone-call"></i></span> <span class="d-none d-lg-inline-block">{{ $objContactInfo->varPhoneNo }}</span></a>
                                    </li>
                                @endif
                            @endif

                            <li class="-ssearch">
                                <div class="dropdown ac-dropdown ac-noarrow">
                                    <a class="dropdown-toggle " href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="n-icon" data-icon="s-search"></i></a>

                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                                        <div class="dropdown-item">
                                            {!! Form::open(['url' => '/search', 'method' => 'post','class'=>'globalSearch_form','id'=>'globalSearch_form']) !!}
                                            <div class="input-group">
                                                    {!! Form::text('globalSearch', '', array('id'=>'globalSearch', 'class'=>'form-control ac-input w-75', 'maxlength'=>'255')) !!}
                                                    <div class="input-group-append">
                                                        <button type="submit" title="Search" name="searchbtn" id="searchbtn"><i class="n-icon" data-icon="s-search"></i></button>
                                                    </div>
                                            </div>
                                            {!! Form::close() !!}
                                        </div>
                                    </div>
                                </div>
                            </li>

                            <li class="-smenu">
                                <a href="javascript:void(0)" title="Menu" id="menu-toggle" class="text-uppercase n-fs-14 n-fw-600 n-lh-100 d-none d-xl-inline-block">
                                    <i class="n-icon" data-icon="s-menu"></i>
                                    <span class="d-none d-lg-inline-block">Menu</span>
                                </a>
                                <a href="javascript:void(0)" onclick="openNav()" id="menu__open" title="Menu Open" class="d-inline-block d-xl-none">
                                    <i class="n-icon" data-icon="s-menu"></i>
                                </a>                                
                            </li>
                        </ul>
                        <div class="nav-overlay" onclick="closeNav()"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="full-menu d-none d-xl-inline-block">
        <div class="-content">
            <a href="javascript:void(0)" title="Menu Close" id="menu-close" class=" text-uppercase n-fs-14 n-fw-600 n-lh-100">
                <i class="n-icon" data-icon="s-x"></i>
            </a>
            <div class="container">
                <div class="row">
                    <div class="col-sm-8">
                        <h2 class="nqtitle-small text-uppercase n-fc-white-500 n-mb-50">Navigation</h2>

                        <div class="menu-nav mCcontent">
                            @if(isset($navigationMenu))
                                {!! $navigationMenu !!}
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <h2 class="nqtitle-small text-uppercase n-fc-white-500 n-mb-50">Contact Us</h2>
                        @if(isset($objContactInfo->varEmail) && !empty($objContactInfo->varEmail))
                            <div class="item">
                                <div class="n-fs-20 n-fw-600 n-fc-white-500">Email:</div>
                                <div class="n-fs-18 n-fw-500 n-fc-white-500 n-lh-130 n-mt-5"><a href="#" title="{{$objContactInfo->varEmail}}" class="n-ah-a-500">{{$objContactInfo->varEmail}}</a></div>
                            </div>
                        @endif

                        @if(isset($objContactInfo->varPhoneNo) && !empty($objContactInfo->varPhoneNo))
                            <div class="item n-mt-lg-30 n-mt-15">
                                <div class="n-fs-20 n-fw-600 n-fc-white-500">Phone:</div>
                                <div class="n-fs-18 n-fw-500 n-fc-white-500 n-lh-130 n-mt-5"><a href="#" title="{{$objContactInfo->varPhoneNo}}" class="n-ah-a-500">{{$objContactInfo->varPhoneNo}}</a></div>
                            </div>
                        @endif

                        @if(isset($objContactInfo->varFax) && !empty($objContactInfo->varFax))
                            <div class="item n-mt-lg-30 n-mt-15">
                                <div class="n-fs-20 n-fw-600 n-fc-white-500">Fax:</div>
                                <div class="n-fs-18 n-fw-500 n-fc-white-500 n-lh-130 n-mt-5"><a href="#" title="{{$objContactInfo->varFax}}" class="n-ah-a-500">{{$objContactInfo->varFax}}</a></div>
                            </div>
                        @endif

                        @if(isset($objContactInfo->txtAddress) && !empty($objContactInfo->txtAddress))
                            <div class="item n-mt-lg-30 n-mt-15">
                                <div class="n-fs-20 n-fw-600 n-fc-white-500">Address:</div>
                                <div class="n-fs-18 n-fw-500 n-fc-white-500 n-lh-130 n-mt-5">{!! nl2br($objContactInfo->txtAddress) !!}</div>
                            </div>
                        @endif

                        @if(isset($objContactInfo->mailingaddress) && !empty($objContactInfo->mailingaddress))
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