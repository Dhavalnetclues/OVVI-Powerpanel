@if (!Request::ajax())
    @extends('layouts.app')
    @section('content')
        @include('layouts.inner_banner')
    @endif
    @if (isset($PageData['response']) && !empty($PageData['response']))
        <section class="inner-page-gap bod-meetings">
            @include('layouts.share-email-print')
            <div class="container">
                <div class="row">
                     @if(isset($pageContent->txtDescription))
                    @include('public-record::frontview.public-record-left-panel', ['content' => $pageContent->txtDescription])
                @else
                    @include('public-record::frontview.public-record-left-panel')
                @endif
                  
                    <div class="col-xl-9 n-mt-25 n-mt-xl-0" id="pageContent">
                        {!! $PageData['response'] !!}
                    </div>
                </div>
            </div>
        </section>
    @else
        <section class="page_section n-pt-lg-80 n-pt-50 n-pb-50 n-pb-lg-80">
            <div class="container">
                <div class="row">
                    <div class="col-12 text-center" data-aos="fade-up">
                        <div class="nqtitle n-fw-800 n-lh-110 text-uppercase">Coming Soon...</div>
                        <div class="desc n-fs-20 n-fw-500 n-lh-130">We are currently working on a new super awesome page.
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif


    <script src="{{ $CDN_PATH . 'assets/js/packages/public-record/public-record.js' }}" type="text/javascript"></script>
    <script type="text/javascript">
        let textDescription = "{{ json_encode($txtDescription) }}"
    </script>

    @if (!Request::ajax())
        @section('footer_scripts')
            <script src="{{ $CDN_PATH . 'assets/libraries/bootstrap-select-master/1.13.14/js/bootstrap-select.min.js' }}"
                defer>
            </script>
        @endsection
    @endsection
@endif
