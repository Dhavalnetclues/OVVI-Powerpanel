@if(!Request::ajax())
    @extends('layouts.app')
    @section('content')
    @include('layouts.inner_banner')
@endif


@if(!Request::ajax())
    <section class="inner-page-gap careers-listing">
        @include('layouts.share-email-print')
        <div class="container">
            <div class="row align-items-center justify-content-center">
                <div class="col-lg-5" data-aos="fade-right">
                    <h2 class="nqtitle">Become a Part of The Utility Regulation and Competition Office <span class="n-fc-a-500">(OfReg)</span> Family</h2>
                    <div class="cms n-mt-15">
                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. </p>
                        <p class="n-fc-black-500"><strong>Dr. The Hon. Linford A. Pierson</strong><br><span class="n-fc-a-500">- Chairman of the Board</span></p>
                    </div>
                </div>
                <div class="col-lg-5 n-mt-25 n-mt-lg-0" data-aos="fade-left">
                    <div class="thumbnail-container">
                        <div class="thumbnail">
                            <img src="{{ $CDN_PATH.'assets/images/job-oppertunities.png' }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="inner-page-gap careers-jobs n-pt-40 n-pt-lg-80">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-8 text-center" data-aos="fade-up">
                    <h2 class="nqtitle">We offer careers, not jobs</h2>
                    <div class="cms n-mt-15">
                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.</p>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center n-mt-25 n-mt-lg-50">
                @php for ($x = 1; $x <= 12; $x++) { @endphp
                    <div class="col-xl-3 col-lg-4 col-sm-6 d-flex n-gapp-xl-5 n-gapm-xl-4 n-gapm-lg-3 n-gapm-sm-1" data-aos="zoom-in" data-aos-delay="@php echo $x; @endphp00">
                        <article class="-items n-bs-1 w-100 n-pa-30 n-bgc-white-500 d-flex flex-column">
                            <h3 class="-title n-fs-22 n-fw-500 n-ff-1 n-fc-balck-500 n-lh-120 n-ti-05">Senior Accounting Supervisor</h3>
                            <div class="n-fs-18 n-fw-500 n-fc-a-500 n-lh-120 n-mt-5">4 Positions</div>

                            <div class="mt-auto n-pt-45">                                
                                <a href="#" class="ac-btn ac-btn-primary ac-small" title="Apply">Apply</a>
                            </div>
                        </article>
                    </div>
                @php } @endphp
            </div>

            <div class="row">
                <div class="col-12">
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