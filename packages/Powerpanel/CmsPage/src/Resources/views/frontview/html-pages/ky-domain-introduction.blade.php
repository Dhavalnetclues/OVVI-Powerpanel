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

            <div class="col-xl-9 n-mt-25 n-mt-xl-0" data-aos="fade-up">
                <h2 class="nqtitle-ip text-center">.KY NOW OPEN TO THE WORLD!</h2>
                <div class="cms text-center n-mt-10">
                    <p>OfReg is responsible for the management and administration of the .KY Internet domain. <br>On 2 September 2015 the .KY domain launched globally allowing anyone in the world to register a dot KY domain on a first-come, first-served basis</p>
                    <p>Please click <a href="https://www.ofreg.ky/ict/press-releases/countdown-to-ky-domain-global-launch">here</a> for further information and To contact the .KY Domain Admin, please email: <a href="mailto:kyadmin@ofreg.ky">kyadmin@ofreg.ky</a></p>
                </div>
                <div class="row justify-content-center n-mt-50">
                    <div class="col-sm-8" data-aos="zoom-in">
                        <div class=" text-center">
                            <img src="https://static.uniregistry.com/static/assets/img/ky-logo.png">
                            <h2 class="nqtitle-ip n-mt-25">Get your <span class="n-fc-a-500">ky</span> domain now</h2>
                            <p>Check if your .KY domain name is available</p>
                        </div>
                        <div class="ac-form-wd n-mt-25">
                            <div class="form-group ac-form-group">
                                <label class="ac-label" for="firstName">Search by Domain Name</label>
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


