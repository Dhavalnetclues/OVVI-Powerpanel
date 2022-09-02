@if(!Request::ajax())
@extends('layouts.app')
@section('content')
@include('layouts.inner_banner')
@endif

@php if(isset($PAGE_CONTENT['response']) && !empty($PAGE_CONTENT['response']) && $PAGE_CONTENT['response'] != '[]'){ @endphp
    <section class="inner-page-gap">
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
                                    <div class="nqtitle-small lp-title text-uppercase n-mb-25">Consumer Information</div>
                                    <div class="s-list">
                                        <ul class="nqul d-flex flex-wrap n-fs-16 n-ff-2 n-fw-600 n-fc-black-500">
                                            <li><a href="#" title="Who we are">Who we are</a></li>
                                            <li><a href="#" title="Job Opportunities">Job Opportunities</a></li>
                                            <li><a class="active" href="#" title="FAQs">FAQs</a></li>
                                            <li><a href="#" title="Legislation">Legislation</a></li>
                                            <li><a href="#" title="Board of Directors">Board of Directors</a></li>
                                            <li><a href="#" title="Board of Directors Meetings">Board of Directors Meetings</a></li>
                                            <li><a href="#" title="Industry Statistics">Industry Statistics</a></li>
                                            <li><a href="#" title="Strategic Plan">Strategic Plan</a></li>
                                            <li><a href="#" title="Annual Plan">Annual Plan</a></li>
                                            <li><a href="#" title="News">News</a></li>
                                            <li><a href="#" title="Consumer Affairs">Consumer Affairs</a></li>
                                            <li><a href="#" title="ClickB4UDig">ClickB4UDig</a></li>
                                            <li><a href="#" title="Archives">Archives</a></li>
                                        </ul>
                                    </div>
                                </article>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-9 n-mt-25 n-mt-xl-0">
                    @php echo $PAGE_CONTENT['response']; @endphp
                </div>  
            </div>
        </div>  
    </section>
@php } else{ @endphp
    <section class="inner-page-gap">
        @include('layouts.share-email-print')

        <div class="container">
            <div class="row">
                <div class="col-12 text-center" data-aos="fade-up">
                    <div class="nqtitle n-fw-800 n-lh-110 text-uppercase">Coming Soon...</div>
                    <div class="desc n-fs-20 n-fw-500 n-lh-130">We are currently working on a new super awesome page.</div>
                </div>  
            </div>
        </div>  
    </section>
@php } @endphp


@if(!Request::ajax())
@section('footer_scripts')

@endsection
@endsection
@endif