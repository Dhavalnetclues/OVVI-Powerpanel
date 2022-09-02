@if(!Request::ajax())
    @extends('layouts.app')
    @section('content')
    @include('layouts.inner_banner')
@endif

@if(isset($PAGE_CONTENT['response']) && !empty($PAGE_CONTENT['response']) && $PAGE_CONTENT['response'] != '[]')
    <section class="inner-page-gap news-listing">
        @include('layouts.share-email-print')
        <div class="container">
            <div class="row">
                @include('news::frontview.news-left-panel')   
                <div class="col-xl-9" id="pageContent">
                    @php echo $PAGE_CONTENT['response']; @endphp
                </div>  
            </div>
        </div>  
    </section>
@else
    <section class="inner-page-gap">
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
<script src="{{ $CDN_PATH.'assets/js/packages/news/news.js' }}" type="text/javascript"></script>
<script type="text/javascript">
    let textDescription = "{{json_encode($txtDescription)}}"
</script>
@if(!Request::ajax())
    @section('footer_scripts')
        <script src="{{ $CDN_PATH.'assets/libraries/bootstrap-select-master/1.13.14/js/bootstrap-select.min.js' }}" defer></script>
    @endsection
@endsection
@endif