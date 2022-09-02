@if(!Request::ajax())
@extends('layouts.app')
@section('content')
@include('layouts.inner_banner')
@endif

<!-- Thank You S -->
    <div id="thankyou-page"></div>
    <section class="page_section thankyou_01 n-pt-md-100 n-pb-md-100 n-pt-50 n-pb-50" data-aos="fade-up">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 text-center">
                    <div class="nqtitle n-fw-800 n-lh-110 text-uppercase"><span>Thank You</span> <br/>Hope you are doing great...</div>
                    <div class="desc n-fs-20 n-fw-500 n-lh-130 n-mt-15 n-mt-lg-30">{{ $message }}</div>
                    <div class="n-fs-16 n-fw-500 n-lh-130 n-mt-15">Have a great day!</div>
                    <a href="{{url('/')}}" title=" Back To Home" class="ac-btn ac-btn-primary text-uppercase n-mt-15 n-mt-lg-30"> Back To Home </a>
                </div>
            </div>
        </div>    
    </section>
<!-- Thank You E -->

@if(!Request::ajax())
@section('footer_scripts')
@endsection
@endsection
@endif