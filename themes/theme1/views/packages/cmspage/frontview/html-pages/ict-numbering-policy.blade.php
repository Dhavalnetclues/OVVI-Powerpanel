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
                    <p>The Cayman Islands is part of World Zone 1 for telephone numbering, along with most of the English-speaking Caribbean, Canada and the United States.</p>
                    <p>As such, the numbering scheme used in the Cayman Islands for fixed line telephony and mobile telephony is part of the North American Numbering Plan (NANP).</p>
                    <p>The North American Numbering Plan Administrator (NANPA) follows regulatory directives & industry-developed guidelines as provided by the Industry Numbering Committee (INC) which is part of the Alliance for Telecommunications Industry Solutions Inc. (ATIS), to be used when considering requests for number allocations.</p>
                    <p>The Office has adopted these principles to guide its decisions regarding requests for number allocations in the Cayman Islands.</p>
                    <p>A link to the index of the principles can be found at <a href="https://www.nationalnanpa.com/bdp/index.html" target="_blank" title="NANPA">https://www.nationalnanpa.com/bdp/index.html</a></p>
                </div>
            </div>
        </div>
    </div>
</section>


