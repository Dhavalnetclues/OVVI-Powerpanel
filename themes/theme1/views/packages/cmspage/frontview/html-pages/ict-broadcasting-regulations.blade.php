<section class="inner-page-gap">
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
                        <div class="col-12">
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

            <div class="col-xl-9 n-mt-25 n-mt-xl-0" data-aos="fade-up">
                <div class="cms">
                    <p>In addition to the&nbsp;<a href="https://www.ofreg.ky/ict/legislation-regulations" title="Information &amp; Communications Technology Authority Law"><span>Information &amp; Communications Technology Law</span></a>, the following Regulations have direct applicability to Radio and Television Network and Service licensees:</p>
                </div>

                <div class="row">
                    @php for ($x = 1; $x <= 5; $x++) { @endphp
                        <div class="col-sm-6 col-lg-4 n-gapp-1" data-aos="fade-up">
                            <div class="documents">
                                <div class="-doct-img">
                                    <i class="n-icon" data-icon="s-pdf"></i>
                                    <i class="n-icon" data-icon="s-download"></i>
                                </div>
                                <div>
                                    <a class="-link n-ah-a-500" href="#" download="" title="">Interference and Equipment Standardization Regulations</a>
                                </div>
                            </div>
                        </div>
                    @php } @endphp
                </div>

                <div class="cms">
                    <p><a href="https://www.ofreg.ky/ict/legislation-regulations" title="Notice under s23(2) ICT Law (Networks and Services requiring a licence)"><span>Notice under s23(2) ICT Law (Networks and Services requiring a licence)</span></a></p>
                    <p>Further Regulations may be published over the coming months.</p>
                </div>
            </div>
        </div>
    </div>
</section>


