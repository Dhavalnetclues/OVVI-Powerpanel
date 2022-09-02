@if (!Request::ajax())
    @extends('layouts.app')
    @section('content')
        @include('layouts.inner_banner')
    @endif

    @php
    $ignoreLeftPanel = ['about-us', 'who-we-are', 'terms-of-use', 'job-oppertunities', 'foi', 'ict','energy','fuel','water'];
    $sector = '';
    $segment = Request::segment(1);
    if ((Request::segment(1) == 'ict' || Request::segment(1) == 'energy' || Request::segment(1) == 'fuel' || Request::segment(1) == 'water') && !empty(Request::segment(2))) {
        $sector = Request::segment(1);
        $segment = Request::segment(2);
    }
    @endphp

    @if (isset($PageData['response']) && !empty($PageData['response']) && $PageData['response'] != '[]')
        <section class="inner-page-gap">
            @include('layouts.share-email-print')
            <div class="container">
                @if (in_array($segment, $ignoreLeftPanel))
                    {!! $PageData['response'] !!}
                @else
                    <div class="row">
                        @include('cmspage::frontview.left-panel', ['sector' => $sector])
                        <div class="col-xl-9 n-mt-25 n-mt-xl-0" data-aos="fade-up" id="pageContent">
                            {!! $PageData['response'] !!}
                            @if ($segment == 'kydomain-introduction')
                                <div class="row justify-content-center n-mt-50 whois-information">
                                    <div class="col-sm-8" data-aos="zoom-in">
                                        <div class=" text-center">
                                            <img src="https://static.uniregistry.com/static/assets/img/ky-logo.png">
                                            <h2 class="nqtitle-ip n-mt-25">Get your <span class="n-fc-a-500">ky</span>
                                                domain now</h2>
                                            <p>Check if your .KY domain name is available</p>
                                        </div>
                                        <div class="ac-form-wd n-mt-25">
                                            <div class="form-group ac-form-group">
                                                <label class="ac-label" for="domain_name">Search by Domain Name</label>
                                                <input type="text" class="form-control ac-input" id="domain_name" name="domain_name" minlength="1" maxlength="255" spellcheck="true" onpaste="return false;" ondrop="return false;" autocomplete="off">
                                                <button class="-search ac-btn ac-btn-primary" id="search_domain" type="button" title="Search">Search</button>
                                            </div>
                                        </div>
                                        <div class="n-fs-16 n-fc-a-500 n-fw-500 text-center">Powered by GoDaddy Online
                                            Services Cayman Islands Ltd</div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </section>
    @else
        <section class="inner-page-gap">
            @include('layouts.share-email-print')
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

    @if (!Request::ajax())
    @endsection
    @if ($segment == 'kydomain-introduction')
        @section('page_scripts')
            <script>
                $('#search_domain').on('click', function() {
                    var domain_name = $('#domain_name').val(); 
                    $('#domain_name').css('border-color','#616161');
                    if(domain_name == ''){
                        $('#domain_name').css('border-color','#d60f39');
                        return false;                  
                    }
                    var url = 'https://uniregistry.com/ky?q=' + domain_name + '&tlds=ky';
                    window.open(url, '_blank');
                });
            </script>
        @endsection
    @endif
@endif
