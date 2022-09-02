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
                <div class="row">
                    <div class="col-md-6">
                        <div class="cms">
                            <h2>Amateur Radio Licences</h2>
                            <p>Sections 9(2)(c), 23(2) and 82 of the Information and Communications Technology Law require that an Operator of an amateur radio in the Cayman Islands must at all times have a current licence from OfReg (or the 'Office').</p>
                            <p>Your attention is also drawn to the ICTA (Amateur Radio Licences) Regulations.</p>
                            <div class="documents">
                                <div class="-doct-img">
                                    <i class="n-icon" data-icon="s-pdf"></i>
                                    <i class="n-icon" data-icon="s-download"></i>
                                </div>
                                <div>
                                    <a class="-link n-ah-a-500" href="#" download="" title="">ICTA (Amateur Radio Licences) Regulations.</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 n-mt-15 n-mt-md-0">
                        <div class="cms">
                            <h2>Residents</h2>
                            <p>For first time applicants or renewals (renewals should be sent no later than 30 days prior to the licence expiry date), forward to the Office a completed Resident Licence Application Form which can be found <strong><a href="https://www.ofreg.ky/ict/application-forms" title="Application Forms">here</a></strong> along with <strong>the licence fee of US$25 (or CI$20)</strong>.</p>
                            <h2>Visitors</h2>
                            <p>Visitors should submit a completed Visitor Licence Application Form which can be found <strong><a href="https://www.ofreg.ky/ict/application-forms" title="Application Forms">here</a></strong> along with <strong>the licence fee of US$25 (or CI$20)</strong>.</p>
                        </div>
                    </div>
                </div>

                <div class="cms n-mt-15">
                    <h2>Payment Methods</h2>
                    <p>Payment may be made as follows:</p>
                    <ul>
                        <li>By cash, if hand delivered to OfReg's office;</li>
                        <li>By mail addressed to Licensing, enclosing a cheque drawn on a Cayman Islands’ bank and payable to “<strong>OfReg</strong>”. (Money orders and personal or company cheques drawn on a non-Cayman Islands’ bank are NOT accepted); or</li>
                        <li>By email or fax, attaching a credit card authorisation (Visa/MasterCard only). The appropriate form is attached to the licence application form.</li>
                        <li>The Office's address and other contact information may be found on our <a href="https://www.ofreg.ky/ict/contact" name="contact page">contact page</a>.</li>
                    </ul>
                    <p>The Application Forms may also be requested by e-mail from <a href="mailto:licensing@ofreg.ky">licensing@ofreg.ky</a>, or by telephone from +1-345-946-4282.</p>
                    <p>Any change to the particulars referenced in the Licence or the Licence Application should be notified immediately to the Office. There is a <strong>US$20 (CI$16.40) Fee</strong> for issuing an amended or duplicate Licence.</p>
                    <p>Please see our <a href="https://www.ofreg.ky/ict/contact" name="contact page">contact page</a> for the Office's address and other contact information.</p>
                </div>
            </div>
        </div>
    </div>
</section>


