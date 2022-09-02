<section class="inner-page-gap 121">
    @include('layouts.share-email-print')

    <div class="container">
        <div class="row">
            <div class="col-xl-3 left-panel">
                <div class="nav-overlay" onclick="closeNav1()"></div>
                <div class="text-right">
                    <a href="javascript:void(0)" onclick="openNav1()" id="menu__open1" title="Filter & Menu" class="short-menu">Filter & Menu</a>
                </div>
                <div class="menu1" id="menu1">
                    <div class="row n-mr-xl-15">
                        <div class="col-12">
                            <article>
                                <div class="nqtitle-small lp-title text-uppercase n-mb-25">Consumer Information</div>
                                <div class="s-list">
                                    <ul class="nqul d-flex flex-wrap n-fs-16 n-ff-2 n-fw-600 n-fc-black-500">
                                        <li><a href="#" title="Consumer">Consumer</a></li>
                                        <li><a href="#" title="How to Make a Complaint">How to Make a Complaint</a></li>
                                        <li><a href="#" title="Make a Complaint">Make a Complaint</a></li>
                                    </ul>
                                </div>
                            </article>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-9 n-mt-25 n-mt-xl-0">
                <div class="row justify-content-center">
                    <div class="col-sm-8 p-none" data-aos="zoom-in">
                        <h2 class="nqtitle-ip text-center">How can we help you?</h2>
                        <div class="ac-form-wd n-mt-15">
                            <div class="form-group ac-form-group">
                                <label class="ac-label" for="firstName">Search for answers</label>
                                <input type="text" class="form-control ac-input" id="firstName" name="firstName" minlength="1" maxlength="255" spellcheck="true" onpaste="return false;" ondrop="return false;" autocomplete="off">
                            </div>
                        </div>
                    </div>

                    <div class="col-12" data-aos="zoom-in">
                        <ul class="nqul ac-collapse accordion" id="accordionExample">
                            <li class="-li">
                                <a class="-tabs" data-toggle="collapse" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">How can I file a complaint with or about the Office? <span></span></a>
                                <div id="collapseOne" class="-info collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                                    <div class="cms">
                                        <p>Use the Office's on-line Complaints Form at www.ofreg.ky to send it electronically OR download  Complaint Form C100 or collect from the Office, a printed version of the Complaints Form, complete it, and either:</p>
                                        <ul>
                                            <li>Use the Office's on-line Complaints Form at www.ofreg.ky to send it electronically OR download  Complaint Form C100 or collect from the Office, a printed version of the Complaints Form, complete it, and either:</li>
                                            <li>Email the completed Form to complaints@ofreg.ky; or</li>
                                            <li>Fax the completed Form to (345) 946-8284.</li>
                                        </ul>
                                    </div>
                                </div>
                            </li>

                            <li class="-li">
                                <a class="-tabs collapsed" data-toggle="collapse" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">How to file a KY Domain Name Dispute <span></span></a>
                                <div id="collapseTwo" class="-info collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                                    <div class="cms">
                                        <p>For information on how to file a KY Domain Name Dispute please click here.</p>
                                    </div>
                                </div>
                            </li>

                            <li class="-li">
                                <a class="-tabs collapsed" data-toggle="collapse" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">What kind of information does the Office need to pursue my complaint? <span></span></a>
                                <div id="collapseThree" class="-info collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                                    <div class="cms">
                                        <ul>
                                            <li>Your full name along with a phone number and email or postal address (include physical address if it would assist us in understanding your complaint).</li>
                                            <li>Brief description of your complaint.</li>
                                            <li>Identify the telecom, radio or television station involved by name, or whether it is about OfReg.</li>
                                            <li>Provide the date, time and name of any program, individual, advertisement, technical, or customer services related issue that prompted you to write.</li>
                                            <li>Please provide us with the response, if any, given by the company when you first raised your complaint with them.</li>
                                        </ul>
                                    </div>
                                </div>
                            </li>

                            <li class="-li">
                                <a class="-tabs collapsed" data-toggle="collapse" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour">Anonymous Complaints <span></span></a>
                                <div id="collapseFour" class="-info collapse" aria-labelledby="headingFour" data-parent="#accordionExample">
                                    <div class="cms">
                                        <ul>
                                            <li>The Office does not pursue anonymous complaints. You must provide your full name and an email or postal address where you can be contacted.</li>
                                            <li>Licensees have the right to know the allegations against them and the identity of the complainant.</li>
                                        </ul>
                                    </div>
                                </div>
                            </li>

                            <li class="-li">
                                <a class="-tabs collapsed" data-toggle="collapse" href="#collapseFive" aria-expanded="false" aria-controls="collapseFive">Complaints and Privacy <span></span></a>
                                <div id="collapseFive" class="-info collapse" aria-labelledby="headingFive" data-parent="#accordionExample">
                                    <div class="cms">
                                        <ul>
                                            <li>The practice of the Office is to place all correspondence related to a complaint on a publicly-accessible file.</li>
                                            <li>You may decline to have your correspondence placed on a publicly-accessible file. This should be indicated in your complaint, together with the reason for your request.</li>
                                        </ul>
                                    </div>
                                </div>
                            </li>

                            <li class="-li">
                                <a class="-tabs collapsed" data-toggle="collapse" href="#collapseSix" aria-expanded="false" aria-controls="collapseSix">What happens to a complaint? <span></span></a>
                                <div id="collapseSix" class="-info collapse" aria-labelledby="headingSix" data-parent="#accordionExample">
                                    <div class="cms">
                                        <ul>
                                            <li>You should receive a response from the Office within 10 working days, even if it is just to let you know that your complaint has been received.</li>
                                            <li>The Office will generally ask the company involved to address your concerns before OfReg reaches any conclusions. Companies are normally given twenty days to respond and must respond directly to you, with a copy to the Office. The same would apply where you are complaining about OfReg.</li>
                                            <li>The Office can give the company a shorter period of time in which to respond where the circumstances so require.</li>
                                            <li>The Office will review your concerns and the company's response in light of its policies and regulations to determine if follow-up action is necessary.</li>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <div class="col-lg-10 p-none" data-aos="zoom-in">
                        <div class="n-bs-1 n-pa-40 n-mt-15">
                            <div class="row align-items-center justify-content-between">
                                <div class="col-lg-9">
                                    <div class="nqtitle-small">Need to know more?</div>
                                    <p class="n-fs-16 n-ff-2 n-fw-400 n-lh-150 n-fc-black-500 n-mt-5">Lorem Ipsum is simply dummy text of the printing and typesetting industry. <br>It is a long established fact that a reader will be distracted.</p>
                                </div>
                                <div class="col-lg-3 n-tar-lg n-mt-15 n-mt-lg-15">
                                    <a href="#" class="ac-btn ac-btn-primary">Contact Us</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


