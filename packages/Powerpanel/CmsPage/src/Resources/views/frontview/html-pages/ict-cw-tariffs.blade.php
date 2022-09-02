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
                    <p>Tariffs are public documents that describe services, pricing, and terms and conditions of service offering. <a href="http://www.lime.ky/legal#notices" name="FLOW Retail Services" rel="nofollow" target="_blank">FLOW Retail Services</a></p>
                    <p>As a condition of its Licence, LIME must file tariffs for the following categories of retail services:</p>
                    <ul>
                        <li>Category 1 (Price Cap),</li>
                        <li>Category 2 (Special),</li>
                        <li>Category 4 (Bundles of Categories 1 and 2), and</li>
                        <li>Category 5 (Bundles of Categories 2 and 3).</li>
                    </ul>
                    <p>For the above categories, LIME is required to offer its services to all its subscribers at the prices and terms and conditions outlined in its public tariffs.</p>
                    <p>In July 2003, Category 2 consisted of Internet Connectivity, IDD (international direct dial) and Mobile Services. Based on the rules set out in Annex 5 to LIME's Licence, these services have since been reclassified to Category 3 from the date that any other Licensee began to commercially provide the specific ICT service.</p>
                    <p>For Category 3 (Other) services, sub-category A, LIME is required to publish all of its rates, terms and conditions in a manner that is easily accessible and clearly indicates to users what terms and conditions apply to each ICT service. LIME is permitted to offer customer specific pricing for sub-category A services that differ from the published rates, provided that it files those arrangements with the Authority within a reasonable timeframe. The Authority may, on a service by service basis, exempt LIME from this requirement.</p>
                    <p>Refer to Annex 5 of FLOW licence for more information.</p>
                </div>
                <div class="n-mt-15">
                    <div class="documents">
                        <div class="-doct-img">
                            <i class="n-icon" data-icon="s-pdf"></i>
                            <i class="n-icon" data-icon="s-download"></i>
                        </div>
                        <div>
                            <a class="-link n-ah-a-500" href="#" download="" title="">Annex 5</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


