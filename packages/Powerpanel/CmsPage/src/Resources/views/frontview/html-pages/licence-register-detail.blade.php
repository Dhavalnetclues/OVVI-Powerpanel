<section class="inner-page-gap">
    @include('layouts.share-email-print')    

    <div class="container">
        <div class="row">
            <div class="col-xl-6">
                <div class="row">
                    <div class="col-md-6 d-flex n-gapp-md-3 n-gapm-md-2" data-aos="zoom-in">
                        <article class="n-bs-1 w-100 n-pa-30">
                            <div class="nqtitle-small n-fc-a-500">Contact Person</div>
                            <div class="cms n-mt-10"><p>Mr. Daniel Lee</p></div>
                        </article>
                    </div>
                    <div class="col-md-6 d-flex n-gapp-md-3 n-gapm-md-2" data-aos="zoom-in">
                        <article class="n-bs-1 w-100 n-pa-30">
                            <div class="nqtitle-small n-fc-a-500">Contact Address</div>
                            <div class="cms n-mt-10"><p>3rd Floor, Alissta Tower, <br>85 North Sound Road, <br>Grand Cayman Islands</p></div>
                        </article>
                    </div>
                    <div class="col-md-6 d-flex n-gapp-md-3 n-gapm-md-2" data-aos="zoom-in">
                        <article class="n-bs-1 w-100 n-pa-30">
                            <div class="nqtitle-small n-fc-a-500">Email & Website</div>
                            <div class="cms n-mt-10">
                                <p>
                                    Email: <a href="mailto:foi@ofreg.ky" title="foi@ofreg.ky">foi@ofreg.ky</a><br>
                                    Website: <a href="https://www.ofreg.ky/" title="www.ofreg.ky" target="_blank">www.ofreg.ky</a>
                                </p>
                            </div>
                        </article>
                    </div>
                    <div class="col-md-6 d-flex n-gapp-md-3 n-gapm-md-2" data-aos="zoom-in">
                        <article class="n-bs-1 w-100 n-pa-30">
                            <div class="nqtitle-small n-fc-a-500">Date of Issue & Status</div>
                            <div class="cms n-mt-10"><p>21 September, 2006 (Assigned to #24)</p></div>
                            <div class="cms n-mt-10"><p>Licence Surrendered</p></div>
                        </article>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 n-mt-25 n-mt-xl-0" data-aos="fade-up">
                <ul class="nqul ac-collapse accordion" id="faqaccordion">
                    <li class="-li">
                        <a class="-tabs" data-toggle="collapse" href="#licensedictservices" aria-expanded="true" aria-controls="licensedictservices" title="Licensed ICT Services">Licensed ICT Services <span></span></a>
                        <div id="licensedictservices" class="-info collapse show" aria-labelledby="headingOne" data-parent="#faqaccordion">
                            <div class="cms">
                                <ul>
                                    <li><b>Service 1</b> - Fixed Telephony</li>
                                    <li><b>Service 3</b> - Mobile Telephony</li>
                                    <li><b>Service 4</b> - Resale of Telephony</li>
                                    <li><b>Service 5</b> - Internet Telephony</li>
                                </ul>
                            </div>
                        </div>
                    </li>
                    <li class="-li">
                        <a class="-tabs collapsed" data-toggle="collapse" href="#licensedictnetworks" aria-expanded="true" aria-controls="licensedictnetworks" title="Licensed ICT Networks">Licensed ICT Networks <span></span></a>
                        <div id="licensedictnetworks" class="-info collapse" aria-labelledby="headingOne" data-parent="#faqaccordion">
                            <div class="cms">
                                <ul>
                                    <li><b>Network A</b> - Fixed wireline</li>
                                    <li><b>Network B</b> - Fixed wireless</li>
                                    <li><b>Network C2</b> - Mobile (cellular) 2.5G</li>
                                    <li><b>Network D1</b> - Fibre optic cable - Domestic</li>
                                </ul>
                            </div>
                        </div>
                    </li>
                    <li class="-li">
                        <a class="-tabs collapsed" data-toggle="collapse" href="#licencedocuments" aria-expanded="true" aria-controls="licencedocuments" title="Licence Documents">Licence Documents <span></span></a>
                        <div id="licencedocuments" class="-info collapse" aria-labelledby="headingOne" data-parent="#faqaccordion">
                            <div class="row">
                                @php for ($x = 1; $x <= 6; $x++) { @endphp
                                    <div class="col-md-6 n-gapp-3 n-gapm-md-2" data-aos="fade-up">
                                        <div class="documents">
                                            <div class="-doct-img">
                                                <i class="n-icon" data-icon="s-pdf"></i>
                                                <i class="n-icon" data-icon="s-download"></i>
                                            </div>
                                            <div>
                                                <a class="-link n-ah-a-500" href="#" download="" title="">View Amendment #1 (Allocation of 949MHz Spectrum)</a>
                                            </div>
                                        </div>
                                    </div>
                                @php } @endphp
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        
    </div>
</section>


