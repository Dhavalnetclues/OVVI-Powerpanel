@extends('layouts.app')
@section('content')
@include('layouts.inner_banner')
    <div id="thankyou-page"></div>
    <section class="page_section thankyou_01 inner-page-gap" data-aos="fade-up">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 text-center">
                    <div class="text-uppercase"><h2>Thank You</h2></div>
                    <div class="desc n-fw-500 n-lh-130">{!! $message !!}</div>
                    <div class="n-fw-500 n-lh-130 n-mt-15">Have a great day!</div>
                    <a href="{{ url('/') }}" title=" Back to Home" class="ac-btn ac-btn-primary text-uppercase n-mt-15 n-mt-lg-30"> Back to Home </a>
                </div>
            </div>
        </div>    
    </section>
@endsection