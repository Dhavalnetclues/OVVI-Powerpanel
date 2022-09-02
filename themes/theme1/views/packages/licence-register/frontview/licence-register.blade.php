@if(!Request::ajax())
    @extends('layouts.app')
    @section('content')
    @include('layouts.inner_banner')
@endif
@if(!Request::ajax())
    
        @if(isset($PageData['response']) && !empty($PageData['response']))
            <section class="inner-page-gap whois-information register-of-applications">
                @include('layouts.share-email-print')
                <div class="container">
                    <div class="row">
                        @include('licence-register::frontview.licence-register-left-panel')
                        <div class="col-xl-9 n-mt-25 n-mt-xl-0">
                            <div class="row justify-content-center">
                                <div class="col-sm-8" data-aos="fade-up">
                                    <h2 class="nqtitle-ip text-center">Chronological Listing</h2>
                                    <div class="ac-form-wd n-mt-25">
                                        <div class="form-group ac-form-group">
                                            <label class="ac-label" for="search">Search by Keyword</label>
                                            <input type="text" class="form-control ac-input" name="search" minlength="1" maxlength="255" spellcheck="true" id="search">
                                            <button class="-search ac-btn ac-btn-primary" type="button" title="Search" id="searchBtn">Search</button>
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
                            <div class="desc n-fs-20 n-fw-500 n-lh-130">We are currently working on a new super awesome page.</div>
                        </div>  
                    </div>
                </div>  
            </section>
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

<script src="{{ $CDN_PATH.'assets/js/packages/licence-register/licence-register.js' }}" type="text/javascript"></script>
<script type="text/javascript">
    let textDescription = "{{json_encode($txtDescription)}}"
</script>

  @if (!Request::ajax())
        @section('footer_scripts')
            <script src="{{ $CDN_PATH . 'assets/libraries/bootstrap-select-master/1.13.14/js/bootstrap-select.min.js' }}"
                defer>
            </script>
        @endsection
    @endsection
@endif