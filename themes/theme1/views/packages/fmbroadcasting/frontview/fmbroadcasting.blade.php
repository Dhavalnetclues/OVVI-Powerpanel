@if(!Request::ajax())
    @extends('layouts.app')
    @section('content')
    @include('layouts.inner_banner')
@endif


@if(!Request::ajax())
    <section class="inner-page-gap whois-information fm-broadcasting">
        @include('layouts.share-email-print')

        <div class="container">
            <div class="row">
                @include('fmbroadcasting::frontview.fmbroadcasting-left-panel')
                <div class="col-xl-9 n-mt-25 n-mt-xl-0" data-aos="fade-up">
                    <div class="row justify-content-center">
                        <div class="col-sm-8" data-aos="zoom-in">
                            <div class="text-center">
                                <h2 class="nqtitle-ip">The following FM Broadcasting Stations are licensed in the Cayman Islands</h2>
                                <p>Check if an FM Broadcasting Stations is registered to the Cayman Islands.</p>
                            </div>
                            <div class="ac-form-wd n-mt-25">
                                <div class="form-group ac-form-group">
                                    <label class="ac-label" for="firstName">Search by Name</label>
                                    <input type="text" class="form-control ac-input" name="search" minlength="1" maxlength="255" spellcheck="true" id="search">
                                    <button class="-search ac-btn ac-btn-primary" type="button" title="Search" id="searchBtn">Search</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="pageContent">
                        @php echo $PageData['response']; @endphp
                    </div>
                   
                </div>
            </div>
        </div>
    </section>
@endif
<script src="{{ $CDN_PATH.'assets/js/packages/fmbroadcasting/fmbroadcasting.js' }}" type="text/javascript"></script>
@if(!Request::ajax())
    @section('footer_scripts')
    @endsection
    @endsection
@endif