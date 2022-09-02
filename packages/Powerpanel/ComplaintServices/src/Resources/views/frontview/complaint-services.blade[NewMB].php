@if(!Request::ajax())
    @extends('layouts.app')
    @section('content')
    @include('layouts.inner_banner')
@endif

@if(!Request::ajax())
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
                        <div class="row n-mr-xl-15">
                            <div class="col-12">
                                <article>
                                    <div class="nqtitle-small lp-title text-uppercase n-mb-25">Consumer Information</div>
                                    <div class="s-list">
                                        <ul class="nqul d-flex flex-wrap n-fs-16 n-ff-2 n-fw-600 n-fc-black-500">
                                            <li><a href="#" title="Consumer">Consumer</a></li>
                                            <li><a href="#" title="How to Make a Complaint">How to Make a Complaint</a></li>
                                            <li><a class="active" href="#" title="Make a Complaint">Make a Complaint</a></li>
                                        </ul>
                                    </div>
                                </article>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-9 n-mt-25 n-mt-xl-0">
                    <div class="row justify-content-center">
                        @php for($x = 1; $x <= 12; $x++) { @endphp
                            <div class="col-lg-3 col-md-4 col-6 n-gapp-lg-5 n-gapm-lg-4 n-gapm-md-3" data-aos="zoom-in" data-aos-delay="@php echo $x; @endphp00">
                                <article class="n-bs-1 n-bgc-white-500">
                                    <div class="thumbnail-container ac-webp" data-thumb="66.66%">
                                        <div class="thumbnail">
                                            
                                        </div>
                                    </div>
                                    <div class="n-pa-20">
                                        <div class="n-fs-22 n-fc-dark-500 n-lh-120">Television</div>
                                        <a href="{{ url('on-line-complaint-form') }}" title="File Complaint" class="ac-btn ac-btn-primary ac-small n-mt-15">File Complaint</a>
                                    </div>
                                </article>
                            </div>
                        @php } @endphp
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