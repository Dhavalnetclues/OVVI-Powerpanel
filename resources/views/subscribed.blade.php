@if(!Request::ajax())
@extends('layouts.app')
@section('content')
@include('layouts.inner_banner')
@endif
<section class="page_section n-pt-lg-80 n-pt-50 n-pb-50 n-pb-lg-80 notfound_01" data-aos="fade-up">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 text-center">
                    <div class="nqtitle n-fw-800 n-lh-110 text-uppercase"><span>Success</span></div>
                    <div class="desc n-fs-20 n-fw-500 n-lh-130 n-mt-15 n-mt-lg-30">You are successfully subscribed to {{env('APP_NAME')}}</div>                    
                    <a href="{{ url('/') }}" title="Back to Home" class="ac-btn ac-btn-primary text-uppercase n-mt-15 n-mt-lg-30">Back to Home</a></div>
                </div>
            </div>
        
    </section>
@if(!Request::ajax())
@section('footer_scripts')


@endsection

@endsection
@endif