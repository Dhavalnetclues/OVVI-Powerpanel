@if(!Request::ajax())
@extends('layouts.app')
@section('content')
@include('layouts.inner_banner')
@endif

<!-- 404 S -->
    <section class="page_section n-pt-lg-80 n-pt-50 n-pb-50 n-pb-lg-80 notfound_01" data-aos="fade-up">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 text-center">
                    <div class="nqtitle n-fw-800 n-lh-110 text-uppercase"><span>LINK EXPIRED</span> <br/></div>

                    <div class="desc n-fs-20 n-fw-500 n-lh-130 n-mt-15 n-mt-lg-30">Oops!</div>
                    <div class="desc n-fs-20 n-fw-500 n-lh-130 n-mt-15 n-mt-lg-10">The link you are trying to access is no longer exist.</div>
                    <div class="n-fs-16 n-fw-500 n-lh-130 n-mt-15">Click on the links below to do something, Thanks!</div>
                    <a href="{{ url('/') }}" title="Back to Home" class="ac-btn ac-btn-primary text-uppercase n-mt-15 n-mt-lg-30">Back to Home</a></div>
                </div>
            </div>
        </div>
    </section>
<!-- 404 E -->

@if(!Request::ajax())
@section('footer_scripts')
@endsection
@endsection
@endif