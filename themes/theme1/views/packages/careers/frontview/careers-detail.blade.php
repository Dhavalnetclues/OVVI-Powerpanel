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
            <div class="col-12 text-center" data-aos="fade-up">
                <div class="nqtitle n-fw-800 n-lh-110 text-uppercase">Coming Soon...</div>
                <div class="desc n-fs-20 n-fw-500 n-lh-130">We are currently working on a new super awesome page.</div>
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