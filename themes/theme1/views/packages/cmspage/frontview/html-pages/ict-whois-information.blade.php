<section class="inner-page-gap whois-information">
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

            <div class="col-xl-9 n-mt-25 n-mt-xl-0">
                <div class="row justify-content-center">
                    <div class="col-sm-8" data-aos="zoom-in">
                        <div class=" text-center">
                            <img src="https://static.uniregistry.com/static/assets/img/uni-gd-logo-dark.png?version=1615094439440">
                            <h2 class="nqtitle-ip n-mt-25">WHOIS record lookup</h2>
                            <p>Check if a domain name is registered and to whom.</p>
                        </div>
                        <div class="ac-form-wd n-mt-25">
                            <div class="form-group ac-form-group">
                                <label class="ac-label" for="firstName">Search for Domain</label>
                                <input type="text" class="form-control ac-input" id="firstName" name="firstName" minlength="1" maxlength="255" spellcheck="true" onpaste="return false;" ondrop="return false;" autocomplete="off">
                                <button class="-search ac-btn ac-btn-primary" type="button" title="Search">Search</button>
                            </div>
                        </div>

                        <div class="n-fs-16 n-fc-a-500 n-fw-500 text-center">Powered by GoDaddy Online Services Cayman Islands Ltd</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


