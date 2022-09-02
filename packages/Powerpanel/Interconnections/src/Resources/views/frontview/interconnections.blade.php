@if(!Request::ajax())
    @extends('layouts.app')
    @section('content')
    @include('layouts.inner_banner')
@endif
@if(isset($PassPropage) && $PassPropage == 'PP' && $isContent)
    <section class="inner-page-gap bod-meetings">
        @include('layouts.share-email-print')   
        <div class="container">
            <div class="row">
                @include('interconnections::frontview.interconnections-left-panel')
                @include('partial.passwordProtected', ['Pageid' => $Pageid, 'tablename' => $tablename])
                <div class="col-xl-9 n-mt-25 n-mt-xl-0" data-aos="fade-up" id="pageContent">
                </div>
            </div>
        </div>
    </section>
@else
    @if(isset($PageData['response']) && !empty($PageData['response']))
        <section class="inner-page-gap bod-meetings">
            @include('layouts.share-email-print')   
            <div class="container">
                <div class="row">
                    @include('interconnections::frontview.interconnections-left-panel')
                    <div class="col-xl-9 n-mt-25 n-mt-xl-0" data-aos="fade-up" id="pageContent">
                        @php echo $PageData['response']; @endphp
                    </div>
                </div>
            </div>
        </section>
    @else
        @include('coming-soon')
    @endif
@endif

<script type="text/javascript">
    let textDescription = "{{json_encode($txtDescription)}}"
</script>
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
<script src="{{ $CDN_PATH.'assets/js/packages/interconnections/interconnections.js' }}" type="text/javascript"></script>
@if(!Request::ajax())
@section('footer_scripts')
    <!-- <script src="{{ $CDN_PATH.'assets/libraries/bootstrap-select-master/1.13.14/js/bootstrap-select.min.js' }}" defer></script> -->
@endsection
@endsection
@endif