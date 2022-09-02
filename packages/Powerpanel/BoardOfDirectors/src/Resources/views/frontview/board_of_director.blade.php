@if(!Request::ajax())
@extends('layouts.app')
@section('content')
@include('layouts.inner_banner')
@endif

@if(isset($PassPropage) && $PassPropage == 'PP' && $isContent)
<section class="inner-page-gap directors-listing">
    @include('layouts.share-email-print')    
    <div class="container">
        <div class="row">
            @include('boardofdirectors::frontview.board-of-director-')

            @include('partial.passwordProtected', ['Pageid' => $Pageid, 'tablename' => $tablename])

            <div class="col-xl-9 n-mt-25 n-mt-xl-0" data-aos="fade-up" id="pageContent">
            </div>
        </div>
</section>
@else
    @if(isset($PageData['response']) && !empty($PageData['response']) && $PageData['response'] != '[]')
        <section class="inner-page-gap directors-listing">
            @include('layouts.share-email-print')    
            <div class="container">
                <div class="row">
                    @include('boardofdirectors::frontview.board-of-director-')
                    <div class="col-xl-9 n-mt-25 n-mt-xl-0" data-aos="fade-up" id="pageContent">
                        @php echo $PageData['response']; @endphp
                    </div>
                </div>
        </section>
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
<script type="text/javascript">
    let slug = "{{$link}}"
    let pagename = "{{$pagename}}"
</script>

<script src="{{ $CDN_PATH.'assets/js/packages/boardofdirectors/boardofdirectors.js' }}" type="text/javascript"></script>
@if(!Request::ajax())
@section('footer_scripts')

@endsection
@endsection
@endif
