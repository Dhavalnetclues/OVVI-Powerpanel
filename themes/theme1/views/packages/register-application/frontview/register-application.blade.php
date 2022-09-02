@if (!Request::ajax())
    @extends('layouts.app')
    @section('content')
        @include('layouts.inner_banner')
    @endif

    @if (isset($PageData['response']) && !empty($PageData['response']))
        <section class="inner-page-gap whois-information register-of-applications">
            @include('layouts.share-email-print')
            <div class="container">
                <div class="row">
                    @include('register-application::frontview.register-application-left-panel')
                    <div class="col-xl-9 n-mt-25 n-mt-xl-0">
                        <div class="row justify-content-center">
                            <div class="col-sm-8" data-aos="fade-up">
                                <h2 class="nqtitle-ip text-center">Listing and Current Status</h2>
                                <div class="ac-form-wd n-mt-25">
                                    <div class="form-group ac-form-group">
                                        <label class="ac-label" for="search">Search by Title</label>
                                        <input type="text" class="form-control ac-input" id="search" name="search"
                                            minlength="1" maxlength="255" autocomplete="off">
                                        <button class="-search ac-btn ac-btn-primary" type="button" title="Search"
                                            id="searchBtn">Search</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row n-mt-25" id="pageContent">
                            @php echo $PageData['response']; @endphp
                        </div>
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

    <script src="{{ $CDN_PATH . 'assets/js/packages/register-application/register-application.js' }}"
        type="text/javascript"></script>
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
