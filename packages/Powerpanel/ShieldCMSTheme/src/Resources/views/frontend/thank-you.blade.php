@if(!Request::ajax())
@extends('layouts.app')
@section('content')
@include('layouts.inner_banner')
@endif



<div id="thankyou-page"></div>
<section class="page_section thankyou_01">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <div class="icon"><i class="fa fa-envelope-open-o"></i></div>
                <div class="title mt-xs-25"> Thank You </div>
                <div class="desc mt-xs-15">{{ $message }}</div>
                <div class="great_day mt-xs-15">Have a great day!</div>
                <a href="{{url('/')}}" title=" Back To Home " class="btn btn-primary mt-xs-20"> Back To Home </a></div>
            </div>
        </div>
    </div>
</section>
@if(!Request::ajax())
@section('footer_scripts')
<script src="{{ url('') }}"></script>

<script src="{{ $CDN_PATH.'assets/js/thank-you.js' }}"></script>


@endsection

@endsection
@endif