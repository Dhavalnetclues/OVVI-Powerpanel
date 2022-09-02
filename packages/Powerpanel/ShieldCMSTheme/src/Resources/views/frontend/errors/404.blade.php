@if(!Request::ajax())
@extends('layouts.app')
@section('content')
@include('layouts.inner_banner')
@endif



<!-- 404_01 S -->
<div id="notfound-page"></div>
<section class="page_section notfound_01">
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2 text-center">
                <div class="icon"><i class="ri-alert-fill"></i></div>
                <div class="title mt-xs-25">ERROR 404 <br/>NOT FOUND</div>
                <div class="desc mt-xs-15">You may have mis-typed the URL. Or the page has been removed. <br>Actually, there is nothing to see here...</div>
                <div class="great_day mt-xs-15">Click on the links below to do something, Thanks!</div>
                <a href="{{ url('/') }}" title="Back to Home" class="btn btn-primary mt-xs-20">Back to Home</a></div>
            </div>
        </div>
    </div>
</section>
<!-- 404_01 E -->


@if(!Request::ajax())
@section('footer_scripts')
<script src="{{ $CDN_PATH.'assets/js/404.js' }}"></script>


@endsection

@endsection
@endif