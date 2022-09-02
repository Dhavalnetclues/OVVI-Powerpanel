@if(!Request::ajax())
    @extends('layouts.app')
    @section('content')
    @include('layouts.inner_banner')
@endif

<section class="inner-page-gap news-detail">
    <!-- This design also used on the consultation-detail -->
    @include('layouts.share-email-print')   

    <div class="container">
        <div class="row">
            <div class="col-xl-3 order-xl-0 order-2 n-mt-xl-0 n-mt-40 left-panel" data-aos="fade-up">
                <div class="row">
                    <div class="col-xl-12">
                        <article>
                            <div class="nqtitle-small lp-title text-uppercase n-mb-25">Latest News</div>
                            <div class="row">
                                <div class="col-xl-12 col-lg-6 -border">
                                    <ul class="nqul justify-content-between -tag d-flex align-items-center n-fs-16 n-fw-600 n-fc-black-500 n-ff-2">
                                        <li>
                                            <div class="-nimg">
                                                <img src="{{ $CDN_PATH.'assets/images/logo.png' }}" alt="OfReg" title="OfReg">
                                            </div>
                                            <div class="-nlabel n-fc-white-500 n-fs-18 n-fw-800">OfReg</div>
                                        </li>
                                        <li class="nq-svg">
                                            <img class="svg" src="{{ $CDN_PATH.'assets/images/icon/calendar.svg' }}" alt="calendar">
                                            Mar 03, 2021
                                        </li>
                                    </ul>
                                    <a class="d-inline-block n-mt-15 n-fs-18 n-ff-2 n-fw-600 n-lh-130 n-fc-black-500 n-ah-a-500" href="#" title="Fake Instagram account – Hon Minister Tara Rivers">Fake Instagram account – Hon Minister Tara Rivers</a>
                                </div>
                                <div class="col-xl-12 col-lg-6 -border">
                                    <ul class="nqul justify-content-between -tag d-flex align-items-center n-fs-16 n-fw-600 n-fc-black-500 n-ff-2">
                                        <li>
                                            <div class="-nimg">
                                                <img src="{{ $CDN_PATH.'assets/images/logo.png' }}" alt="ICT" title="ICT">
                                            </div>
                                            <div class="-nlabel n-fc-white-500 n-fs-18 n-fw-800">ICT</div>
                                        </li>
                                        <li class="nq-svg">
                                            <img class="svg" src="{{ $CDN_PATH.'assets/images/icon/calendar.svg' }}" alt="calendar">
                                            Mar 03, 2021
                                        </li>
                                    </ul>
                                    <a class="d-inline-block n-mt-15 n-fs-18 n-ff-2 n-fw-600 n-lh-130 n-fc-black-500 n-ah-a-500" href="#" title="Draft Determination of the Proposed Renewable Energy Capacity Reallocation and Tariff Setting">Draft Determination of the Proposed Renewable Energy Capacity Reallocation and Tariff Setting</a>
                                </div>
                                <div class="col-xl-12 col-lg-6 -border">
                                    <ul class="nqul justify-content-between -tag d-flex align-items-center n-fs-16 n-fw-600 n-fc-black-500 n-ff-2">
                                        <li>
                                            <div class="-nimg">
                                                <img src="{{ $CDN_PATH.'assets/images/logo.png' }}" alt="ICT" title="ICT">
                                            </div>
                                            <div class="-nlabel n-fc-white-500 n-fs-18 n-fw-800">ICT</div>
                                        </li>
                                        <li class="nq-svg">
                                            <img class="svg" src="{{ $CDN_PATH.'assets/images/icon/calendar.svg' }}" alt="calendar">
                                            Mar 03, 2021
                                        </li>
                                    </ul>
                                    <a class="d-inline-block n-mt-15 n-fs-18 n-ff-2 n-fw-600 n-lh-130 n-fc-black-500 n-ah-a-500" href="#" title="Press Release - IPX Draft Regulatory Framework Consultation">Press Release - IPX Draft Regulatory Framework Consultation</a>
                                </div>
                                <div class="col-xl-12 col-lg-6 -border">
                                    <ul class="nqul justify-content-between -tag d-flex align-items-center n-fs-16 n-fw-600 n-fc-black-500 n-ff-2">
                                        <li>
                                            <div class="-nimg">
                                                <img src="{{ $CDN_PATH.'assets/images/logo.png' }}" alt="ICT" title="ICT">
                                            </div>
                                            <div class="-nlabel n-fc-white-500 n-fs-18 n-fw-800">ICT</div>
                                        </li>
                                        <li class="nq-svg">
                                            <img class="svg" src="{{ $CDN_PATH.'assets/images/icon/calendar.svg' }}" alt="calendar">
                                            Mar 03, 2021
                                        </li>
                                    </ul>
                                    <a class="d-inline-block n-mt-15 n-fs-18 n-ff-2 n-fw-600 n-lh-130 n-fc-black-500 n-ah-a-500" href="#" title="Press Release - IPX Draft Regulatory Framework Consultation">Press Release - IPX Draft Regulatory Framework Consultation</a>
                                </div>
                            </div>
                        </article>
                    </div>
                    <div class="col-xl-12">
                        <article class="n-mt-xl-50 n-mt-md-0 n-mt-25">
                            <div class="nqtitle-small lp-title text-uppercase n-mb-25">Tags</div>
                            <div class="s-tags">
                                <ul class="nqul d-flex flex-wrap n-fs-14 n-ff-2 n-fw-600 n-fc-white-500">
                                    <li><a href="#" title="Communications">Communications</a></li>
                                    <li><a href="#" title="Electricity">Electricity</a></li>
                                    <li><a href="#" title="Fuel">Fuel</a></li>
                                    <li><a href="#" title="ICTLaw">ICTLaw</a></li>
                                    <li><a href="#" title="Water">Water</a></li>
                                </ul>
                            </div>
                        </article>
                    </div>
                </div>
            </div>

            <div class="col-xl-9" data-aos="fade-up">
                <ul class="nqul justify-content-between -tag d-flex align-items-center n-fs-16 n-fw-600 n-fc-black-500 n-ff-2">
                    <li>
                        <div class="-nimg">
                            <img src="{{ $CDN_PATH.'assets/images/logo.png' }}" alt="ICT" title="ICT">
                        </div>
                        <div class="-nlabel n-fc-white-500 n-fs-18 n-fw-800">ICT</div>
                    </li>
                    <li class="nq-svg">
                        <img class="svg" src="{{ $CDN_PATH.'assets/images/icon/calendar.svg' }}" alt="calendar">
                        {{ date('l F jS, Y',strtotime($news->dtDateTime)) }}
                    </li>
                </ul>

                <h2 class="nqtitle-small n-fc-black-500 n-fw-600 n-lh-140 n-mv-25">{{ $news->varTitle }}</h2>

                <div class="-img">
                    <div class="thumbnail-container">
                        <div class="thumbnail">
                            <img src="" alt="">
                        </div>
                    </div>
                </div>

                @if(isset($news->txtDescription) && !empty($news->txtDescription))
                    <div class="cms n-mt-25">
                        {!! htmlspecialchars_decode($txtDescription) !!}
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

@if(!Request::ajax())
    @section('footer_scripts')

    @endsection
    @endsection
@endif