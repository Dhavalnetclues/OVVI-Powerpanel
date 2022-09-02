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
                            <h2>Type Approval Process</h2>
                            <p>The Cayman Islands do not have testing facilities, nor does the Utility Regulation and Competition Office ('<strong>OfReg</strong>' or the '<strong>Office</strong>') seek to create Standards in relation to Type Approval's, at this time.</p>
                            <p>As such, Type Approval applicants are to provide supporting documentation that demonstrates the equipment has been certified by European Conformity ('<strong>CE</strong>'), Federal Communications Commission ('<strong>FCC</strong>') or both.</p>
                            <p>A Type Approval Application, along with supporting documentation, is then reviewed by the OfReg Type Approval Team and approved or denied based on any differences with the Cayman Islands Spectrum Usage assignments and that of the FCC and/or the European Union as the case may be,</p>
                            <p>Please note that while <strong>Type Approval is not required</strong> any equipment which intends to utilize Spectrum or connect to a public ICT Network must be "certified equipment" as defined by the <a href="/ict/upimages/commonfiles/1417277001ICTA-EquipmentStandardizationRegs.pdf" name="EquipmentStandardizationRegs" target="_blank">ICTA (Interference and Equipment Standardization) Regulations, 2004</a> prior to importation into the Cayman Islands.</p>
                        </div>
                    </div>
                    <div class="col-md-6 n-mt-15 n-mt-md-0">
                        <div class="cms">
                            <h2>Useful Information</h2>
                            <p>The Office suggests that prospective Type Approval Applicants primary research is focused on the standards adopted, or being adopted, by the FCC as these will most likely be accepted by the Office (in whole or in part) provided they conform with the Cayman Islands Spectrum Map and the ICT Section 23(2) Notice.</p>
                            <p>Further, the Cayman Islands Table of Frequency Allocations is based upon the International Telecommunication Union's Table of Frequency Allocations for Region 2, as modified from time to time by the OfReg Board to meet the spectrum requirements of the Cayman Islands.</p>
                            <p>Below are links to various documents and information relating to spectrum and its use in the Cayman Islands in addition to Frequently Asked Questions (FAQs - coming soon). The Office recommends Applicants review information provided in the links below prior to submitting an application:</p>
                            <ul>
                                <li><a href="/ict/spectrum-map" name="spectrum-map" target="_blank">Cayman Islands Spectrum Map Information</a></li>
                                <li><a href="/ict/upimages/commonfiles/1417277001ICTA-EquipmentStandardizationRegs.pdf" name="Interference and Equipment Standardization Regulations" target="_blank">Interference and Equipment Standardization Regulations</a></li>
                                <li><a href="https://www.ofreg.ky/ict/upimages/commonfiles/1512771016Section23Notice20Nov17.pdf" target="_blank" title="Section 23(2)">Section 23(2)</a></li>
                                <li><a href="https://www.fcc.gov/" name="FCC website" target="_blank">FCC website</a></li>
                                <li>FAQs (coming soon)</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="cms n-mt-15">
                    <h2>Required Documentation</h2>
                    <p>Should an Applicant wish to proceed with a Type Approval application, the following documentation is required:</p>
                    <ul>
                        <li>Completed Application Form (the form can be found <strong><a href="https://ofreg.formstack.com/forms/type_approval" name="Completed Application Form" target="_blank">here</a></strong>)</li>
                        <li>Power of Attorney or approval from the owner giving authorisation to an Acting Agent to request a Type Approval, whichever is applicable</li>
                        <li>All Technical Documentation to support the application</li>
                        <li>Copies of certification from the FCC, CE or both</li>
                    </ul>

                    <h2>Important Information to Note</h2>
                    <ol>
                        <li>Application/Modification Fee (non-refundable) - C.I. $400.00 (or U.S. $500.00) Fee to be paid in full at time of Application/Modification request.</li>
                        <li>T<strong>he application review process takes approximately 30 days after ALL required documentation and payment has been received by the Office.</strong></li>
                        <li>All communications in relation to Type Approval requests, other than Applications (which are made through the <strong><a href="https://ofreg.formstack.com/forms/type_approval" name="Application Form Type Approval" target="_blank">Application Form</a></strong>) are to be emailed to <strong><a href="mailto:typeapproval@ofreg.ky">typeapproval@ofreg.ky</a></strong>.</li>
                        <li>Type Approval Applications will be via submission using the <strong><a href="https://ofreg.formstack.com/forms/type_approval" name="Type Approval Application Form" target="_blank">Application Form</a></strong></li>
                        <li>The Office notes most equipment with transmitter(s)/receiver(s) have an FCC certification mark or CE conformity mark (together '<strong>Certification Marks</strong>'), but not in all cases. As such, Certification Marks on packaging and/or equipment is not required.</li>
                        <li>A sample is not required.</li>
                        <li>OfReg Type Approval Certificates are valid for 10 years.</li>
                        <li>Documentation must be provided in English or where the original documentation is translated into English, the translated document(s) must be certified by an accredited translation entity.</li>
                        <li>Any modifications (including model number, design or function) to previously approved equipment must be re-assessed by the Office in the same manner as a first time application.</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</section>


