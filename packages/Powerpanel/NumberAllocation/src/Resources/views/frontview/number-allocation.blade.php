@if (!Request::ajax())
    @extends('layouts.app')
    @section('content')
        @include('layouts.inner_banner')
@endif
    @if(isset($PassPropage) && $PassPropage == 'PP' && $isContent)
        <section class="inner-page-gap">
            @include('layouts.share-email-print')
            <div class="container">
                <div class="row">
                    @include('number-allocation::frontview.number-allocation-left-panel')
                    <div class="col-xl-9 n-mt-25 n-mt-xl-0" data-aos="fade-up">
                        @include('partial.passwordProtected', ['Pageid' => $Pageid, 'tablename' => $tablename])
                        <div class="cms" id="pageContent">
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @else
        @if (isset($PageData['response']) && !empty($PageData['response']))
            <section class="inner-page-gap">
                @include('layouts.share-email-print')
                <div class="container">
                    <div class="row">
                        @include('number-allocation::frontview.number-allocation-left-panel')
                        <div class="col-xl-9 n-mt-25 n-mt-xl-0" data-aos="fade-up">
                            <div class="cms" id="pageContent">
                                @php echo $PageData['response']; @endphp
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @else
            @include('coming-soon')
        @endif
    @endif

    <script src="{{ $CDN_PATH . 'assets/js/packages/number-allocations/number-allocations.js' }}" type="text/javascript">
    </script>
    <script type="text/javascript">
        let textDescription = "{{ json_encode($txtDescription) }}"

    </script>
    @if (!Request::ajax())
        @section('footer_scripts')
            <!-- <script src="{{ $CDN_PATH . 'assets/libraries/bootstrap-select-master/1.13.14/js/bootstrap-select.min.js' }}" defer></script> -->
        @endsection
    @endsection
@endif
