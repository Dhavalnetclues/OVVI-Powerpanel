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
                    <h2>IMPORTANT NOTICE TO OWNERS AND MASTERS</h2>
                    <blockquote>
                        <p>Owners and Masters are reminded that with effect from 1 July 2008 only EPIRBs operating at 406 MHz and using the International Cospas-Sarsat Satellite System are to be carried on vessels. The 1.6 GHz system was withdrawn in December 2006 and 121.5/243 MHz beacons will cease to be monitored with effect from 1 February 2009. The Office will accept only 406 MHz EPIRBs on a licence application or renewal. The Application Form and Guidance Notes have been amended accordingly.</p>
                    </blockquote>

                    <h2>Reasons for Licensing</h2>
                    <p>Sections 9(2)(c), 23(2) and 82 of the Information and Communications Technology Law require that the operator of a radio station on any vessel registered in the Cayman Islands must at all times have a current licence from OfReg (or the 'Office').</p>
                    <p>Furthermore, the Radio Regulations issued by the International Telecommunications Union (“ITU”), which apply to the Cayman Islands via United Kingdom legislation, state at Article S18:</p>
                    <p>S18.1 § 1 1) No transmitting station may be established or operated by a private person or by any enterprise without a licence issued in an appropriate form and in conformity with the provisions of these Regulations by or on behalf of the government of the country to which the station in question is subject.</p>
                    <p>Vessel owners should also note that unlicensed vessels are excluded from the ITU’s Maritime mobile Access and Retrieval System (“MARS”) database that is used by search and rescue organisations and that, where applicable, Maritime Mobile Service Identity (“MMSI”) numbers are withdrawn from unlicensed vessels and reallocated to new ones. For the continued safety of all those on board, it therefore is vitally important that radio licence licences are kept current.</p>
                    <p>Accordingly, no later than 30 days prior to the licence expiry date each year, you should forward to the Office a completed Ship Radio Application Form which can be found here along with the appropriate licence fee. The Office has produced a comprehensive Guide to Applicants for a Maritime ICT (Ship Radio Station) Licence. All owners and their agents are strongly encouraged to read the Guide in full before submitting applications. The instructions for the correct coding of EPIRBs are particularly important as the Cayman MMSI number MUST NOT be used.</p>

                    <h2>Licence Fees</h2>
                    <p>Licence fees are based upon gross tonnage as shown in the following table:</p>

                    <table>
                        <tr>
                            <th>Lic #</th>
                            <th>Vessel Characteristics</th>
                            <th>Fee (US$)</th>
                            <th>Fee (CI$)</th>
                        </tr>
                        <tr>
                            <td>L1</td>
                            <td>Vessel less than 300 tons with no MMSI</td>
                            <td>US$30</td>
                            <td>CI$24.50</td>
                        </tr>
                        <tr>
                            <td>L2</td>
                            <td>Vessel less than 300 tons with MMSI</td>
                            <td>US$60</td>
                            <td>CI$49.00</td>
                        </tr>
                        <tr>
                            <td>L3</td>
                            <td>Vessel greater than 300 tons and less than 1,600 tons</td>
                            <td>US$120</td>
                            <td>CI$98.00</td>
                        </tr>
                        <tr>
                            <td>L4</td>
                            <td>Vessel greater than 1,600 tons</td>
                            <td>US$180</td>
                            <td>CI$147.50</td>
                        </tr>
                        <tr>
                            <td>L5</td>
                            <td>Local (Coastal) Vessel with no MMSI</td>
                            <td>-</td>
                            <td>CI$10.00</td>
                        </tr>
                    </table>
                    <p>The above fees came into effect on 1 December 2007. These increases were the first in over 20 years, and were necessary to ensure that the Office’s licensing department is self-funding, as is required by the Office’s agreement with the Cayman Islands Government.</p>

                    <h2>Amendment and Duplicate Licence Fees</h2>
                    <p>Any change to the particulars referenced in the Licence or the Licence Application should be notified immediately to the Office. There is a <strong>US$20 (CI$16.40) Fee</strong> for issuing an amended or duplicate Licence.</p>

                    <h2>Payment Methods</h2>
                    <p>Payment may be made as follows:</p>
                    <ul>
                        <li>By cash, if hand delivered to OfReg’s office;</li>
                        <li>By mail addressed to Licensing, enclosing a cheque drawn on a Cayman Islands’ bank and payable to “<strong>OfReg</strong>”. (Money orders and personal or company cheques drawn on a non-Cayman Islands’ bank are NOT accepted); or</li>
                        <li>By email or fax, attaching a credit card authorisation (Visa/MasterCard only). The appropriate form is attached to the licence application form.</li>
                        <li>The Office's address and other contact information may be found on our<a href="https://www.ofreg.ky/contact-us" name=" Contact page"> Contact page</a>.</li>
                    </ul>
                    <h2>Shipping of Licence Documents</h2>
                    <p>New Licence Docoments are dispatched by normal post. Delivery may take several weeks, depending upon destination. Alternatively the applicant may request courier delivery but must provide his/her own account number against which the cost may be charged. On request, an electronic copy (.pdf) of the document will be emailed to the applicant at no additional charge.</p>

                    <h2>Application Form and Guide to Applicants</h2>
                    <p>The Ship Radio Application Form is available on our Application Forms page which can be found <strong><a href="https://www.ofreg.ky/ict/application-forms" title="Application Forms">here</a></strong>, the Guide to Applicants is available <strong><a href="https://www.ofreg.ky/ict/upimages/commonfiles/1417431297MaritimeGuide.pdf" title="Guide to Applicants.">here</a></strong>, or may be requested by e-mail from <a href="mailto:licensing@ofreg.ky">licensing@ofreg.ky</a>, or by telephone from +1-345-946-4282.:</p>
                </div>
            </div>
        </div>
    </div>
</section>


