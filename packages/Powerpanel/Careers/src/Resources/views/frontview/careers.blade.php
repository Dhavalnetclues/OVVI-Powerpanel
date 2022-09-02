@if(!Request::ajax())
    @extends('layouts.app')
    @section('content')
    @include('layouts.inner_banner')
@endif

@if(isset($PassPropage) && $PassPropage == 'PP' && $isContent)

    <section class="inner-page-gap careers-listing">
        @include('layouts.share-email-print')    
        <div class="container">
            <div class="row">
                @include('partial.passwordProtected', ['Pageid' => $Pageid, 'tablename' => $tablename])
                <div class="col-xl-9 n-mt-25 n-mt-xl-0" data-aos="fade-up" id="pageContent">
                </div>
            </div>
    </section>
@else
    @if(isset($PageData) && !empty($PageData))
        
        @include('layouts.share-email-print')
        <div id="pageContent">    
            {!! $PageData['response'] !!}
        </div>
        
        {{-- <section class="inner-page-gap careers-listing">
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
        </section> --}}

    @else
        @include('coming-soon')
    @endif
@endif
@php
    $segment1 =  Request::segment(1);
    if (($segment1 == "ict" || $segment1 == "water" || $segment1 == "fuel" || $segment1 == "energy") && (!empty($segment1))) {
        $segment2 =  Request::segment(2);

        $link = $segment1.'/' . $segment2 ;
        $pagename = $segment2;
    } else{
        $link = $segment1;
        $pagename = $segment1;
    }
@endphp
@section('page_scripts')
    <script type="text/javascript">
        let slug = "{{$link}}"
        let pagename = "{{$pagename}}"
    </script>
    <script src="{{ $CDN_PATH.'assets/js/packages/career/career.js' }}" type="text/javascript"></script>
@endsection

@if(!Request::ajax())
    @endsection
@endif