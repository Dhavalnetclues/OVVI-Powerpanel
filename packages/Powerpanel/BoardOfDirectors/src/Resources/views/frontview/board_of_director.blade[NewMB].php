@if(!Request::ajax())
@extends('layouts.app')
@section('content')
@include('layouts.inner_banner')
@endif
<section class="inner-page-gap directors-listing">
    @include('layouts.share-email-print')    

    <div class="container">
        <div class="row">
            <div class="col-xl-3 left-panel">
                <div class="nav-overlay" onclick=""></div>
                <div class="text-right">
                    <a href="javascript:void(0)" onclick="openNav1()" id="menu__open1" title="Filter & Menu" class="short-menu">Filter & Menu</a>
                </div>
                <div class="menu1" id="menu1">
                    <div class="row n-mr-xl-15" data-aos="fade-up">
                        <div class="col-12">
                            <article>
                                <div class="nqtitle-small lp-title text-uppercase n-mb-25">About Us</div>
                                <div class="s-list">
                                    <ul class="nqul d-flex flex-wrap n-fs-16 n-ff-2 n-fw-600 n-fc-black-500">
                                        <li><a href="#" title="Who we are">Who we are</a></li>
                                        <li><a class="active" href="#" title="Job Opportunities">Job Opportunities</a></li>
                                        <li><a href="#" title="FAQs">FAQs</a></li>
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

            <div class="col-xl-9">
                {!! $PageData['response'] !!}
                <div class="row">
                    <div class="col-xl-12 col-sm-6 -gap -items">
                        <div class="row" data-aos="fade-up">
                            <div class="col-xl-5">
                                <div class="directors-img">
                                    <div class="thumbnail-container">
                                        <div class="thumbnail">
                                            <a href="#" title="Dr. The Hon. Linford A. Pierson OBE">
                                                <img src="http://localhost/Ofreg/Ofreg/public_html/cdn/assets/images/directors.png" alt="Dr. The Hon. Linford A. Pierson OBE">
                                                <div class="overlay">
                                                    <i class="plus"></i>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-7 n-mv-15 d-flex align-items-center -bg">
                                <div>
                                    <h2 class="-title n-fs-26 n-ff-2 n-fw-800 n-lh-130 n-fc-black-500"><a href="#" title="Dr. The Hon. Linford A. Pierson OBE, JP, PhD, MAPPC, FCCA">Dr. The Hon. Linford A. Pierson OBE, JP, PhD, MAPPC, FCCA</a></h2>
                                    <div class="n-mt-15 n-fs-18 n-fw-600 text-uppercase n-fc-black-500">Chairman of the Board</div>
                                    <div class="cms n-mt-30 d-none d-xl-block n-pr-30 n-pb-30">
                                        <h3>CIVIL SERVICE AND PRIVATE SECTOR CAREERS</h3>
                                        <p>Dr. Pierson worked for 16 years in the Cayman Islands Civil Service (1963-1979), where he filled a number of senior positions, including Principal Secretary (now Chief Officer) for Health, Education, and Social Services, and Deputy Financial Secretary (acting as Financial Secretary on several occasions). During the 1980s he was employed in various private sector positions, including Director of Finance for Cayman Airways, local Partner of KPMG/Thorne Riddell (the then Canadian Chartered Accounting Firm), and he held Directorships in a number of private companies.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
@if(!Request::ajax())
@section('footer_scripts')

@endsection
@endsection
@endif
