@if(!Request::ajax())
@extends('layouts.app')
@section('content')
<!-- @include('layouts.inner_banner') -->
@endif

<!-- 404 S -->
    <section class="page_section n-pt-lg-100 n-pt-50 n-pb-50 n-pb-lg-100 n-mt-lg-100 notfound_01" data-aos="fade-up">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 text-center" >
                    <div class="nqtitle n-fw-800 n-lh-110 text-uppercase"><span>4<span><img src="{{ $CDN_PATH.'assets/images/404-logo.png' }}"></span>4</span> <br/>Oops! Something's missing...</div>
                    <div class="desc n-mt-15 n-mt-lg-30">You may have mis-typed the URL. Or the page has been removed. <br>Actually, there is nothing to see here...</div>
                    <div class="n-mt-15 notfd-content">Click on the links below to do something, Thanks!</div>
                    <a href="{{ url('/') }}" title="Back to Home" class="ac-btn ac-btn-primary text-uppercase n-mt-15 n-mt-lg-30">Back to Home</a>
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