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
                    <p>OfReg (or the 'Office') is responsible for the licensing and regulation of all radio transmitters in the Cayman Islands, including:</p>
                    <ul>
                        <li>Radios in aircraft registered with the <a href="http://www.caacayman.com" name="Cayman Islands Civil Aviation Authority" rel="nofollow" target="_blank">Cayman Islands Civil Aviation Authority</a></li>
                        <li>Ground-to-air radios located in the Cayman Islands</li>
                        <li>Radios in ships registered with the <a href="http://www.cishipping.com/" name="Cayman Islands Shipping Registry" rel="nofollow" target="_blank">Cayman Islands Shipping Registry</a></li>
                        <li>Radios in unregistered ships operating in the coastal waters of the Cayman Islands</li>
                        <li>Land-based marine band radios used by coastal boat operators</li>
                        <li>Amateur (HAM) Radios</li>
                        <li>All land-based radio transmitters, irrespective of frequency, located in the Cayman Island</li>
                    </ul>
                    <p>All radio licences are currently annual, and therefore must be renewed on each anniversary of their initial issue.</p>
                </div>
            </div>
        </div>
    </div>
</section>


