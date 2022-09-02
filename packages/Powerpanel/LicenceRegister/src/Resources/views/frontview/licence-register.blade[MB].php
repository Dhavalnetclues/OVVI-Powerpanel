@if(!Request::ajax())
    @extends('layouts.app')
    @section('content')
    @include('layouts.inner_banner')
@endif


@if(!Request::ajax())
    <section class="inner-page-gap whois-information register-of-applications">
        @include('layouts.share-email-print')

        <div class="container">
            <div class="row">
                <div class="col-xl-3 left-panel">
                    <div class="nav-overlay" onclick="closeNav1()"></div>
                    <div class="text-right">
                        <a href="javascript:void(0)" onclick="openNav1()" id="menu__open1" title="Filter & Menu" class="short-menu">Filter & Menu</a>
                    </div>
                    <div class="menu1" id="menu1">
                        <div class="row n-mr-xl-15" data-aos="fade-up">
                            <div class="col-12 lpgap">
                                <article>
                                    <div class="nqtitle-small lp-title text-uppercase n-mb-25">Sort by Status</div>
                                    <div class="form-group ac-form-group n-mb-0">
                                        <select class="selectpicker ac-input" data-width="100%" title="Sort by Status" data-size="5" data-live-search="true">
                                            <option>Issued</option>
                                            <option>Reissued</option>
                                            <option>Renewed</option>
                                            <option>Pending</option>
                                            <option>Revoked</option>
                                            <option>Surrendered</option>
                                            <option>Expired</option>
                                            <option>Consolidated</option>
                                            <option>Suspended</option>
                                        </select>
                                    </div>
                                </article>
                            </div>
                            <div class="col-12 lpgap">
                                <article>
                                    <div class="nqtitle-small lp-title text-uppercase n-mb-25">Sort by Service / Network</div>
                                    <div class="form-group ac-form-group n-mb-0">
                                        <select class="selectpicker ac-input" data-width="100%" title="Sort by Service / Network" data-size="5" data-live-search="true">
                                            <optgroup label="Service">
                                                <option>Service  1  (Fixed Telephony)</option>
                                                <option>Service  2  (Fall-back International Voice and Data Communications)</option>
                                                <option>Service  3  (Mobile Telephony)</option>
                                                <option>Service  4  (Resale of Telephony)</option>
                                                <option>Service  5  (Internet Telephony)</option>
                                                <option>Service  6  (Public Service TV Broadcasting)</option>
                                                <option>Service  7  (Subcription TV Broadcasting)</option>
                                                <option>Service  8  (Sound Broadcasting)</option>
                                                <option>Service  9  (Internet Service Provider (ISP))</option>
                                                <option>Service  10  (Information Security Service provision)</option>
                                                <option>Service  11  (Provision of ICT Infrastructure to 3rd Parties)</option>
                                                <option>Service  12  (Retail sale of ICT equipment)</option>
                                                <option>Service  13  (Publication of directories)</option>
                                                <option>Service  14  (Application Service Provider (ASP))</option>
                                                <option>Service  15  (Video on Demand)</option>
                                                <option>Service  11A  (Provision of Infrastructure - dark fibre)</option>
                                                <option>Service  16  (Experimental Licence for wearable devices and sensors to individuals on Cayman who have a verified case of, are suspected of having, or are at risk for contracting COVID-19)</option>
                                                <option>Service  A2  (Occasional or Experimental Service)</option>
                                                <option>Service  17  (4G LTE &amp; Fixed Wireless Solution Experimental Testing)</option>
                                                <option>Service  18  (Mobile Data Capture Devices &amp; Central Data Repository)</option>
                                                <option>Service  16A  (Internet Peering Service Provider)</option>
                                            </optgroup>
                                            <optgroup label="Network">
                                                <option>Network  A  (Fixed wireline)</option>
                                                <option>Network  B  (Fixed wireless)</option>
                                                <option>Network  C1  (Mobile (cellular) 2G)</option>
                                                <option>Network  C2  (Mobile (cellular) 2.5G)</option>
                                                <option>Network  C3  (Mobile (cellular) 3G)</option>
                                                <option>Network  D1  (Fibre optic cable - Domestic)</option>
                                                <option>Network  D2  (Fibre optic cable - International)</option>
                                                <option>Network  E1  (Satellite (incl VSAT) - Domestic)</option>
                                                <option>Network  E2  (Satellite (incl VSAT) - International)</option>
                                                <option>Network  F  (Broadcast Network)</option>
                                                <option>Network  C  (Mobile (Cellular))</option>
                                                <option>Network  S  (Spectrum)</option>
                                                <option>Network  FD  (Future Digital SEZC)</option>
                                                <option>Network  A1  (Occasional or Experimental Network)</option>
                                                <option>Network  G  (Internet Exchange Point)</option>
                                            </optgroup>
                                        </select>
                                    </div>
                                </article>
                            </div>
                            <div class="col-12 lpgap">
                                <article>
                                    <div class="nqtitle-small lp-title text-uppercase n-mb-25">ICT Information</div>
                                    <div class="s-list">
                                        @include('cmspage::frontview.ict-left-panel')
                                    </div>
                                </article>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-9 n-mt-25 n-mt-xl-0">
                    <div class="row justify-content-center">
                        <div class="col-sm-8" data-aos="fade-up">
                            <h2 class="nqtitle-ip text-center">Chronological Listing</h2>
                            <div class="ac-form-wd n-mt-25">
                                <div class="form-group ac-form-group">
                                    <label class="ac-label" for="firstName">Search by Keyword</label>
                                    <input type="text" class="form-control ac-input" id="firstName" name="firstName" minlength="1" maxlength="255" spellcheck="true" onpaste="return false;" ondrop="return false;" autocomplete="off">
                                    <button class="-search ac-btn ac-btn-primary" type="button" title="Search">Search</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row n-mt-25">
                        @php for ($x = 1; $x <= 12; $x++) { @endphp
                            <div class="col-lg-4 col-md-6 d-flex n-gap-2 n-gapp-lg-4 n-gapm-lg-3 n-gapm-md-2" data-aos="zoom-in">
                                <article class="-items w-100 n-bs-1 n-pa-20 n-p-relative n-bgc-white-500" data-id="R12">
                                    <div class="n-fs-16 n-fw-500 n-ff-2 n-fc-black-500 n-lh-130"><a href="{{ url('licence-register-detail') }}" title="Cable and Wireless (C.I.) Ltd (T/A Flow)" target="_blank">Cable and Wireless (C.I.) Ltd (T/A Flow)</a></div>
                                    <div class="-status n-mt-40 n-fs-16 n-lh-120">
                                        <div class="-status n-fc-a-500 n-fw-600">Date of Issue</div>
                                        10 Oct, 2003 (Surrendered 1 March 2007)
                                    </div>

                                    <div class="-status n-mt-15 n-fs-16 n-lh-120">
                                        <div class="-status n-fc-a-500 n-fw-600">Current Status</div>
                                        Surrendered
                                    </div>
                                </article>
                            </div>
                        @php } @endphp
                    </div>

                    <div class="n-mt-lg-80 n-mt-40" data-aos="fade-up">
                        <ul class="pagination justify-content-center align-content-center">
                            <li class="page-item">
                                <a class="page-link" href="#" title="Previous">
                                    <i class="n-icon" data-icon="s-pagination"></i>
                                </a>
                            </li>
                            <li class="page-item"><a class="page-link" href="#" title="1">1</a></li>
                            <li class="page-item active"><a class="page-link" href="#" title="2">2</a></li>
                            <li class="page-item"><a class="page-link" href="#" title="3">3</a></li>
                            <li class="page-item"><a class="page-link" href="#" title="4">4</a></li>
                            <li class="page-item"><a class="page-link" href="#" title="5">5</a></li>
                            <li class="page-item">
                                <a class="page-link" href="#" title="Next">
                                    <i class="n-icon" data-icon="s-pagination"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div> 
            </div>
        </div>
    </section>
@endif

@if(!Request::ajax())
    @section('footer_scripts')
    @endsection
    @endsection
@endif