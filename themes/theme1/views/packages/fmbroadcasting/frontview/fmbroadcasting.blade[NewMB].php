@if(!Request::ajax())
    @extends('layouts.app')
    @section('content')
    @include('layouts.inner_banner')
@endif


@if(!Request::ajax())
    <section class="inner-page-gap whois-information fm-broadcasting">
        @include('layouts.share-email-print')

        <div class="container">
            <div class="row">
                <div class="col-xl-3 left-panel">
                    <div class="nav-overlay" onclick="closeNav1()"></div>
                    <div class="text-right">
                        <a href="javascript:void(0)" onclick="openNav1()" id="menu__open1" title="Filter & Menu" class="short-menu">Filter & Menu</a>
                    </div>
                    <div class="menu1" id="menu1">
                        <div class="row n-mr-xl-15" data-aos="fade-up">
                            <div class="col-12">
                                <article>
                                    <div class="nqtitle-small lp-title text-uppercase n-mb-25">ICT Information</div>
                                    <div class="s-list">
                                        @include('cmspage::frontview.ict-left-panel')
                                    </div>
                                </article>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-9 n-mt-25 n-mt-xl-0" data-aos="fade-up">
                    <div class="row justify-content-center">
                        <div class="col-sm-8" data-aos="zoom-in">
                            <div class="text-center">
                                <h2 class="nqtitle-ip">The following FM Broadcasting Stations are licensed in the Cayman Islands</h2>
                                <p>Check if an FM Broadcasting Stations is registered to the Cayman Islands.</p>
                            </div>
                            <div class="ac-form-wd n-mt-25">
                                <div class="form-group ac-form-group">
                                    <label class="ac-label" for="firstName">Search by Name</label>
                                    <input type="text" class="form-control ac-input" id="firstName" name="firstName" minlength="1" maxlength="255" spellcheck="true" onpaste="return false;" ondrop="return false;" autocomplete="off">
                                    <button class="-search ac-btn ac-btn-primary" type="button" title="Search">Search</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row n-mt-25 justify-content-center">
                        @php for($x = 1; $x <= 12; $x++) { @endphp
                            <div class="col-lg-3 col-md-4 col-6 n-gapp-lg-5 n-gapm-lg-4 n-gapm-md-3" data-aos="zoom-in" data-aos-delay="@php echo $x; @endphp00">
                                <article class="-items n-bs-1 n-bgc-white-500">
                                    <div class="thumbnail-container ac-webp" data-thumb="100%">
                                        <div class="thumbnail">
                                            <img src="https://www.ofreg.ky/ict/cache/fmbroadcasting/400_200/radio-cayman_1418343380.png">
                                        </div>
                                        <div class="-freq" data-aos="fade-right" data-aos-delay="@php echo $x; @endphp00">Freq: 98.9</div>
                                        <div class="-play"><a href="http://www.radiocayman.gov.ky/portal/page?_pageid=2424,5593248&amp;_dad=portal&amp;_schema=PORTAL" title="Play" rel="nofollow" target="_blank" class="n-ti-05"><i data-feather="play-circle"></i></a></div>
                                    </div>
                                    <div class="n-pa-20">
                                        <div class="-title n-ti-05 n-fs-18 n-fs-22-sm n-fw-500 n-fc-dark-500 n-lh-110">Radio Cayman (Sister Islands)</div>
                                        <div class="n-mt-25 n-fs-16 n-fc-dark-500 n-lh-120">C.I. Conference of Seventh-Day Adventists</div>
                                    </div>
                                </article>
                            </div>
                        @php } @endphp
                    </div>

                    <div class="n-mt-lg-80 n-mt-40" data-aos="fade-up">
                        <ul class="pagination justify-content-center align-content-center">
                            <li class="page-item">
                                <a class="page-link" href="#" title="Previous">
                                    <i class="n-icon" data-icon="s-pagination"></i>
                                </a>
                            </li>
                            <li class="page-item"><a class="page-link" href="#" title="1">1</a></li>
                            <li class="page-item active"><a class="page-link" href="#" title="2">2</a></li>
                            <li class="page-item"><a class="page-link" href="#" title="3">3</a></li>
                            <li class="page-item"><a class="page-link" href="#" title="4">4</a></li>
                            <li class="page-item"><a class="page-link" href="#" title="5">5</a></li>
                            <li class="page-item">
                                <a class="page-link" href="#" title="Next">
                                    <i class="n-icon" data-icon="s-pagination"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif

@if(!Request::ajax())
    @section('footer_scripts')
    @endsection
    @endsection
@endif