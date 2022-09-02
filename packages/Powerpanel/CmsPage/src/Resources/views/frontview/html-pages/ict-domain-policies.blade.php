<section class="inner-page-gap bod-meetings">
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
                <iframe src="https://www.ofreg.ky/ict/upimages/commonfiles/1425398278KYDomainRightsofUse.pdf" width="100%" height="600" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
            </div>
        </div>
    </div>
</section>


