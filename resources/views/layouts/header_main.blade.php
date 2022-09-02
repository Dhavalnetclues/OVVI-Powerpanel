<header>
    <div class="header-section">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 d-flex align-items-center justify-content-between">
                    <div class="-left">
                        <div class="-logo">
                            <a href="{{ url('/') }}" title="{{ Config::get('Constant.SITE_NAME') }}">
                                {{-- <img src="{{ $CDN_PATH.'assets/images/logo.svg' }}" alt="{{ Config::get('Constant.SITE_NAME') }}" title="{{ Config::get('Constant.SITE_NAME') }}" /> --}}
                                <img src="{{ App\Helpers\resize_image::resize(Config::get('Constant.FRONT_LOGO_ID')) }}" alt="{{ Config::get('Constant.SITE_NAME') }}" title="{{ Config::get('Constant.SITE_NAME') }}" />
                            </a>
                        </div>
                    </div>
                    <div class="-center d-flex align-items-center">
                        <nav class="menu" id="menu">
                            <div class="menu_mobile_visibility">
                                <a href="{{ url('/') }}" title="{{ Config::get('Constant.SITE_NAME') }}">
                                    <div class="menu_title">
                                        <div class="m_t_text">Menu</div>
                                    </div>
                                </a>
                                <a href="javascript:void(0)" onclick="closeNav()" id="menu__close" title="Menu Close">
                                    <i class="n-icon" data-icon="s-x"></i>
                                </a>
                            </div>
                            @if(isset($HeadreMenuhtml))
                                <div class="mobile-wrap">
                                    {!! $HeadreMenuhtml !!}
                                    <div id="full-menu-clone"></div>  <!-- class="d-block d-xl-none" -->
                                </div>
                            @endif
                        </nav>
                        <ul class="-sort-menu">
                            {{-- <li class="-spay mr-xl-auto">
                                <a href="{{ url('pay-online') }}" title="Pay Online" class="ac-btn ac-btn-primary text-uppercase">Pay Online</a>
                            </li>
                            @if(isset($objContactInfo) && !empty($objContactInfo))
                                @if(!empty($objContactInfo->varPhoneNo))
                                    <li class="-stel">
                                        <a href="tel:{{ $objContactInfo->varPhoneNo }}" title="{{ $objContactInfo->varPhoneNo }}" class="n-fs-18 n-fw-600 n-lh-100 "><i class="n-icon" data-icon="s-phone-call"></i> <span class="n-ml-5 d-none d-lg-inline-block">{{ $objContactInfo->varPhoneNo }}</span></a>
                                    </li>
                                @endif
                            @endif --}}
                            <li class="-ssearch">
                                <div class="dropdown ac-dropdown ac-noarrow">
                                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="n-icon" data-icon="s-search"></i></a>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                                        <div class="dropdown-item">
                                            {!! Form::open(['url' => '/search', 'method' => 'post','class'=>'globalSearch_form','id'=>'globalSearch_form']) !!}
                                            <div class="input-group">
                                                {!! Form::text('globalSearch', '', array('id'=>'globalSearch', 'class'=>'form-control ac-input w-75', 'placeholder'=>'Search', 'maxlength'=>'255')) !!}
                                                <div class="input-group-append">
                                                    <button type="submit" title="Search" name="searchbtn" id="searchbtn"><i class="n-icon" data-icon="s-search"></i></button>
                                                </div>
                                            </div>
                                            {!! Form::close() !!}
                                        </div>
                                    </div>
                                </div>
                            </li>
                            @if(isset($objContactInfo->varPhoneNo) && !empty($objContactInfo->varPhoneNo))
                                <li class="contact-link">
                                    <a href="tel:{{$objContactInfo->varPhoneNo}}" title="Call Us On {{$objContactInfo->varPhoneNo}}" class="link">
                                        <span class="icon"><i class="n-icon" data-icon="s-phone-call"></i></span>
                                        <span class="d-none d-md-inline-block">24 HOUR SERVICE</span>
                                    </a>
                                </li>
                            @endif
                            {{-- <li class="-ssocial">
                                <ul class="ac-share n-fs-26 n-fw-500 n-fc-grey-500">
                                    @if(null!==(Config::get('Constant.SOCIAL_FB_LINK')) && strlen(Config::get('Constant.SOCIAL_FB_LINK')) > 0)
                                        <li><a href="{{ Config::get('Constant.SOCIAL_FB_LINK') }}" title="Follow Us On Facebook" target="_blank"><i class="fa fa-facebook"></i></a></li>
                                    @endif
                                    @if(null!==(Config::get('Constant.SOCIAL_TWITTER_LINK')) && strlen(Config::get('Constant.SOCIAL_TWITTER_LINK')) > 0)
                                        <li><a href="{{ Config::get('Constant.SOCIAL_TWITTER_LINK') }}" title="Follow Us On Twitter"><i class="fa fa-twitter" target="_blank"></i></a></li>
                                    @endif
                                    @if(null!==(Config::get('Constant.SOCIAL_INSTAGRAM_LINK')) && strlen(Config::get('Constant.SOCIAL_INSTAGRAM_LINK')) > 0)
                                        <li><a href="{{ Config::get('Constant.SOCIAL_INSTAGRAM_LINK') }}" title="Follow Us On Instagram"><i class="fa fa-instagram" target="_blank"></i></a></li>
                                    @endif
                                </ul>
                            </li> --}}

                            {{-- @php
                                $agent = new Jenssegers\Agent\Agent;
                                if ($agent->isMobile()) {
                                    $device = $agent->device();
                                } else {
                                    $device = 'Desktop';
                                }
                            @endphp

                            @if($device == 'Desktop')

                            @else
                                <li class="-smenu">
                                    <a href="javascript:void(0)" title="Menu" class="text-uppercase n-fs-14 n-fw-600 n-lh-100 d-none d-xl-inline-block">
                                        <i class="n-icon" data-icon="s-menu"></i>
                                    </a>
                                    <a href="javascript:void(0)" onclick="openNav()" id="menu__open" title="Menu Open" class="d-inline-block d-xl-none">
                                        <i class="n-icon" data-icon="s-menu"></i>
                                    </a>
                                </li>
                            @endif --}}
                            <li class="-smenu">
                                <a href="javascript:void(0)" title="Menu" class="text-uppercase n-fs-14 n-fw-600 n-lh-100 d-none d-xl-inline-block">
                                    <i class="n-icon" data-icon="s-menu"></i>
                                </a>
                                <a href="javascript:void(0)" onclick="openNav()" id="menu__open" title="Menu Open" class="d-inline-block d-xl-none">
                                    <i class="n-icon" data-icon="s-menu"></i>
                                </a>
                            </li>
                        </ul>
                        <div class="nav-overlay" onclick="closeNav()"></div>
                    </div>
                    {{-- <div class="-right d-none">
                         <div class="jfontsize">
                            <a id="jfontsize-m" href="javascript:void(0)">-</a>
                            <a id="jfontsize-d" href="javascript:void(0)">A</a>
                            <a id="jfontsize-p" href="javascript:void(0)">+</a>
                        </div> 
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="full-menu d-none d-xl-inline-block">
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
    </div> --}}
</header>

{{-- <div class="container print-header d-none">
    <div class="row">
        <div class="col-xl-6 -gap">
            <img class="-logo" src="{{ $CDN_PATH.'assets/images/logo.svg' }}" alt="{{ Config::get('Constant.SITE_NAME') }}" title="{{ Config::get('Constant.SITE_NAME') }}" />
        </div>
        <div class="col-xl-6 -gap text-right">
        @if(isset($objContactInfo->varPhoneNo)&&  !empty($objContactInfo->varPhoneNo))
            <div class="n-fs-18 n-fw-500 n-fc-black-500 n-lh-130">Phone: {{ $objContactInfo->varPhoneNo }}</div>
           @endif
           @if(isset($objContactInfo->varEmail)&&  !empty($objContactInfo->varEmail))
            <div class="n-fs-18 n-fw-500 n-fc-black-500 n-lh-130">Email: {{$objContactInfo->varEmail}}</div>
           @endif
            @if(isset($objContactInfo->varFax)&&  !empty($objContactInfo->varFax))
            <div class="n-fs-18 n-fw-500 n-fc-black-500 n-lh-130">Fax: {{$objContactInfo->varFax}}</div>
            @endif
        </div>
    </div>
</div> --}}