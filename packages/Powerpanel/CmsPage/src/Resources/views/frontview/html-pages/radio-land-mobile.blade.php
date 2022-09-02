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
                    <h2>Land Mobile Radio Licences</h2>
                    <p>Sections 9(2)(c), 23(2) and 82 of the Information and Communications Technology Law require that the owner of a land mobile radio operated in the Cayman Islands must at all times have a current licence from the Office.</p>
                    <p>No later than 30 days prior to the licence expiry date each year, you should forward to the Office a completed Land Mobile Radio Application Form and Schedule can be found <strong><a href="application-forms" title="Application Forms"><span>here</span></a></strong> along with the appropriate licence fee. Payment may be made as follows:</p>
                    <ul>
                        <li>By cash, if hand delivered to OfReg’s office;</li>
                        <li>By mail addressed to Licensing, enclosing a cheque drawn on a Cayman Islands’ bank and payable to “<strong>OfReg</strong>”. (Money orders and personal or company cheques drawn on a non-Cayman Islands’ bank are NOT accepted); or</li>
                        <li>By email or fax, attaching a credit card authorisation (Visa/MasterCard only). The appropriate form is attached to the licence application form.</li>
                        <li>The Office's address and other contact information may be found on our <a href="https://www.ofreg.ky/contact-us" name="Contact page">Contact page</a>.</li>
                    </ul>
                    <p>Links to the Application Form and Schedule are provided above, or may be requested by e-mail from <a href="mailto:licensing@ofreg.ky">licensing@ofreg.ky</a>, or by telephone from +1-345-946-4282.</p>
                    <p>Any change to the particulars referenced in the Licence or the Licence Application should be notified immediately to the Office.</p>
                </div>
            </div>
        </div>
    </div>
</section>


