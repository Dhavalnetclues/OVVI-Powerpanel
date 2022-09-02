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

            <div class="col-xl-9 n-mt-25 n-mt-xl-0 ac-form-wd" data-aos="fade-up">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group ac-form-group">
                            <label class="ac-label" for="firstName">Your Name <span class="star">*</span></label>
                            <input type="text" class="form-control ac-input" id="firstName" name="firstName" minlength="1" maxlength="255" spellcheck="true" onpaste="return false;" ondrop="return false;" autocomplete="off">
                            <span class="error">Error Massage Here</span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group ac-form-group">
                            <label class="ac-label" for="email">Your Email Address <span class="star">*</span></label>
                            <input type="text" class="form-control ac-input" id="email" name="email" minlength="1" maxlength="255" spellcheck="true" onpaste="return false;" ondrop="return false;" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group ac-form-group">
                            <label class="ac-label" for="friendName">Your Telephone Number <span class="star">*</span></label>
                            <input type="text" class="form-control ac-input" id="friendName" name="friendName" minlength="1" maxlength="255" spellcheck="true" onpaste="return false;" ondrop="return false;" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group ac-form-group">
                            <label class="ac-label" for="friendEmail">Your PO Box # <span class="star">*</span></label>
                            <input type="text" class="form-control ac-input" id="friendEmail" name="friendEmail" minlength="1" maxlength="255" spellcheck="true" onpaste="return false;" ondrop="return false;" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group ac-form-group">
                            <label class="ac-label" for="friendEmail">Company Complained Against <span class="star">*</span></label>
                            <input type="text" class="form-control ac-input" id="friendEmail" name="friendEmail" minlength="1" maxlength="255" spellcheck="true" onpaste="return false;" ondrop="return false;" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group ac-form-group">
                            <label class="ac-label" for="friendEmail">Date complaint filed with Company <span class="star">*</span></label>
                            <input type="text" class="form-control ac-input" id="friendEmail" name="friendEmail" minlength="1" maxlength="255" spellcheck="true" onpaste="return false;" ondrop="return false;" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group ac-form-group">
                            <label class="ac-label" for="message">Your Street Address <span class="star">*</span></label>
                            <textarea class="form-control ac-textarea" id="message" name="message" rows="4" minlength="1" maxlength="600" spellcheck="true" onpaste="return false;" ondrop="return false;" autocomplete="off"></textarea>
                            <span class="ac-note">Please ensure that you provide full details of your complaint including, if appropriate, names of individuals, dates and times.</span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group ac-form-group">
                            <label class="ac-label" for="message">Full details of complaint <span class="star">*</span></label>
                            <textarea class="form-control ac-textarea" id="message" name="message" rows="4" minlength="1" maxlength="600" spellcheck="true" onpaste="return false;" ondrop="return false;" autocomplete="off"></textarea>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group ac-form-group">
                            <label class="ac-label" for="message">Response by Company <span class="star">*</span></label>
                            <textarea class="form-control ac-textarea" id="message" name="message" rows="4" minlength="1" maxlength="600" spellcheck="true" onpaste="return false;" ondrop="return false;" autocomplete="off"></textarea>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group ac-form-group">
                            <label class="ac-label" for="friendEmail">Upload Documents</label>
                            <input type="file" class="form-control ac-input" id="friendEmail" name="friendEmail" minlength="1" maxlength="255" spellcheck="true" onpaste="return false;" ondrop="return false;" autocomplete="off" data-file="">
                            <span class="ac-note">You can upload maximum 5 document(s) having the extension *.pdf, *.doc,*.docx and all files together must not exceed 10 MB.</span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group ac-form-group">
                            <img src="{{ $CDN_PATH.'assets/images/google-captcha.gif' }}" alt="Google Captcha">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group ac-form-group n-tar-sm n-tal">
                            <button type="submit" title="Submit" class="ac-btn ac-btn-primary">Submit</button>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group ac-form-group">
                            <span class="ac-note">Note: Further information can be submitted directly to complaints@ofreg.ky.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


