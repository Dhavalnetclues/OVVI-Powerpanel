@if(!Request::ajax())
    @extends('layouts.app')
    @section('content')
    @include('layouts.inner_banner')
@endif

@if(!Request::ajax())
    <section class="inner-page-gap contact-us">
        @include('layouts.share-email-print')    

        <div class="container">
            <div class="row">
                <div class="col-lg-5" data-aos="fade-right">
                    <form class="ac-form-wd n-bs-1 n-pt-40 n-pb-70 n-ph-lg-50 n-ph-25">
                        <div class="row">
                            <div class="col-sm-12 n-pb-20">
                                <div class="form-group ac-form-group">
                                    <h2 class="nqtitle-small text-uppercase">Quick Contact</h2>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group ac-form-group">
                                    <label class="ac-label" for="firstName">First Name <span class="star">*</span></label>
                                    <input type="text" class="form-control ac-input" id="firstName" name="firstName" minlength="1" maxlength="255" spellcheck="true" onpaste="return false;" ondrop="return false;" autocomplete="off">
                                    <span class="error">Error Massage Here</span>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group ac-form-group">
                                    <label class="ac-label" for="firstName">Last Name <span class="star">*</span></label>
                                    <input type="text" class="form-control ac-input" id="firstName" name="firstName" minlength="1" maxlength="255" spellcheck="true" onpaste="return false;" ondrop="return false;" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group ac-form-group">
                                    <label class="ac-label" for="email">Email <span class="star">*</span></label>
                                    <input type="text" class="form-control ac-input" id="email" name="email" minlength="1" maxlength="255" spellcheck="true" onpaste="return false;" ondrop="return false;" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group ac-form-group">
                                    <label class="ac-label" for="email">Phone </label>
                                    <input type="text" class="form-control ac-input" id="email" name="email" minlength="1" maxlength="255" spellcheck="true" onpaste="return false;" ondrop="return false;" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group ac-form-group">
                                    <label class="ac-label" for="email">Service <span class="star">*</span></label>
                                    <select class="selectpicker ac-input" data-width="100%" title="Select Service" id="sortFilter">
                                        <option>ICT</option>
                                        <option>Energy</option>
                                        <option>Fuel</option>
                                        <option>Water</option>
                                        <option>Others</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group ac-form-group">
                                    <label class="ac-label" for="message">Message</label>
                                    <textarea class="form-control ac-textarea" id="message" name="message" rows="4" minlength="1" maxlength="600" spellcheck="true" onpaste="return false;" ondrop="return false;" autocomplete="off"></textarea>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group ac-form-group n-mb-sm-0">
                                    <img src="{{ $CDN_PATH.'assets/images/google-captcha.gif' }}" alt="Google Captcha">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group ac-form-group n-mb-0 n-tar-sm n-tal">
                                    <button type="submit" title="Submit" class="ac-btn ac-btn-primary">Submit</button>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="-secure n-fs-16 n-fw-400 n-ff-2 n-fc-white-500">100% Secure. Zero Spam.</div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="col-lg-7 n-mt-50 n-mt-lg-0">
                    <div class="row">
                        <div class="col-sm-6 d-flex n-gapp-3 n-gapm-sm-2" data-aos="flip-up">
                            <article class="-items w-100 n-ph-30 n-pv-10">
                                <div class="nqtitle-small n-fw-600 n-fc-black-500">Mailing Address</div>
                                <div class="n-fs-18 n-lh-130 n-mt-10">PO Box 10189 Grand Cayman KY1-1002 <br>CAYMAN ISLANDS</div>
                            </article>
                        </div>
                        <div class="col-sm-6 d-flex n-gapp-3 n-gapm-sm-2" data-aos="flip-up">
                            <article class="-items w-100 n-ph-30 n-pv-10">
                                <div class="nqtitle-small n-fw-600 n-fc-black-500">Physical Address</div>
                                <div class="n-fs-18 n-lh-130 n-mt-10">Utility Regulation and Competition Office 3rd Floor, Alissta Towers, 85 North Sound Rd. Grand Cayman, CAYMAN ISLANDS</div>
                            </article>
                        </div>
                        <div class="col-sm-6 d-flex n-gapp-3 n-gapm-sm-2" data-aos="flip-up">
                            <article class="-items w-100 n-ph-30 n-pv-10">
                                <div class="nqtitle-small n-fw-600 n-fc-black-500">Head Office Email</div>
                                <div class="n-fs-18 n-lh-130 n-mt-10"><a class="n-ah-a-500" href="#" title="info@ofreg.ky">info@ofreg.ky</a></div>
                            </article>
                        </div>
                        <div class="col-sm-6 d-flex n-gapp-3 n-gapm-sm-2" data-aos="flip-up">
                            <article class="-items w-100 n-ph-30 n-pv-10">
                                <div class="nqtitle-small n-fw-600 n-fc-black-500">Head Office Phone</div>
                                <div class="n-fs-18 n-lh-130 n-mt-10"><a class="n-ah-a-500" href="#" title="+1 (345) 946 4282">+1 (345) 946 4282</a></div>
                            </article>
                        </div>
                        <div class="col-sm-6 d-flex n-gapp-3 n-gapm-sm-2" data-aos="flip-up">
                            <article class="-items w-100 n-ph-30 n-pv-10">
                                <div class="nqtitle-small n-fw-600 n-fc-black-500">Head Office Fax</div>
                                <div class="n-fs-18 n-lh-130 n-mt-10">+1 (345) 945 8284</div>
                            </article>
                        </div>
                        <div class="col-sm-6 d-flex n-gapp-3 n-gapm-sm-2" data-aos="flip-up">
                            <article class="-items w-100 n-ph-30 n-pv-10">
                                <div class="nqtitle-small n-fw-600 n-fc-black-500">Business Hours</div>
                                <div class="n-fs-18 n-lh-130 n-mt-10">8.30am to 5pm, Monday to Friday</div>
                            </article>
                        </div>

                        <div class="col-sm-6 d-flex n-gapp-3 n-gapm-sm-2" data-aos="flip-up">
                            <article class="-items w-100 n-ph-30 n-pv-10">
                                <div class="nqtitle-small n-fw-600 n-fc-black-500">Consumer Complaints</div>
                                <div class="n-fs-18 n-lh-130 n-mt-10"><a class="n-ah-a-500" href="#" title="complaints@ofreg.ky">complaints@ofreg.ky</a></div>
                            </article>
                        </div>
                        <div class="col-sm-6 d-flex n-gapp-3 n-gapm-sm-2" data-aos="flip-up">
                            <article class="-items w-100 n-ph-30 n-pv-10">
                                <div class="nqtitle-small n-fw-600 n-fc-black-500">.Ky Domain Enquiries</div>
                                <div class="n-fs-18 n-lh-130 n-mt-10"><a class="n-ah-a-500" href="#" title="kyadmin@ofreg.ky">kyadmin@ofreg.ky</a></div>
                            </article>
                        </div>
                        <div class="col-sm-6 d-flex n-gapp-3 n-gapm-sm-2" data-aos="flip-up">
                            <article class="-items w-100 n-ph-30 n-pv-10">
                                <div class="nqtitle-small n-fw-600 n-fc-black-500">Ship, Aircraft & Misc Licensing Matters</div>
                                <div class="n-fs-18 n-lh-130 n-mt-10"><a class="n-ah-a-500" href="#" title="licensing@ofreg.ky">licensing@ofreg.ky</a></div>
                            </article>
                        </div>
                        <div class="col-sm-6 d-flex n-gapp-3 n-gapm-sm-2" data-aos="flip-up">
                            <article class="-items w-100 n-ph-30 n-pv-10">
                                <div class="nqtitle-small n-fw-600 n-fc-black-500">FOI</div>
                                <div class="n-fs-18 n-lh-130 n-mt-10"><a class="n-ah-a-500" href="#" title="foi@ofreg.ky">foi@ofreg.ky</a></div>
                            </article>
                        </div>
                        <div class="col-sm-6 d-flex n-gapp-3 n-gapm-sm-2" data-aos="flip-up">
                            <article class="-items w-100 n-ph-30 n-pv-10">
                                <div class="nqtitle-small n-fw-600 n-fc-black-500">Contributions To Public Consultations</div>
                                <div class="n-fs-18 n-lh-130 n-mt-10"><a class="n-ah-a-500" href="#" title="consultations@ofreg.ky">consultations@ofreg.ky</a></div>
                            </article>
                        </div>
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