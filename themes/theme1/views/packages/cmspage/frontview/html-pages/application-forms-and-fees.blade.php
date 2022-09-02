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
                <div class="col-12" data-aos="zoom-in">
                    <ul class="nqul ac-collapse accordion" id="applicationforms">
                        <li class="-li">
                            <a class="-tabs" data-toggle="collapse" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">Telephony/Telecommunications Application Form <span></span></a>
                            <div id="collapseOne" class="-info collapse show" data-parent="#applicationforms">
                                <div class="cms">
                                    <h2>Application for Major ICT Licence(s)</h2>
                                    <p>(Type A to E, and G Networks, and/or Type 1 to 5 and 9 to 16 Services). Please <a href="https://ofreg.formstack.com/workflows/ofreg_major_ict_application" name="https://www.ofreg.ky/ict/application-forms/MajorICT" target="_blank">click here</a> to application</p>
                                </div>
                            </div>
                        </li>

                        <li class="-li">
                            <a class="-tabs collapsed" data-toggle="collapse" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">Broadcasting Application Form <span></span></a>
                            <div id="collapseTwo" class="-info collapse" data-parent="#applicationforms">
                                <div class="cms">
                                    <p>For ICT Networks and Services that primarily concern Broadcasting, i.e those that include one or more of the following:</p>
                                    <ul>
                                        <li>Fixed Wireless Network</li>
                                        <li>Broadcast Network</li>
                                        <li>Public Service Television Broadcasting</li>
                                        <li>Subscription Television Broadcasting</li>
                                        <li>Sound Broadcasting</li>
                                    </ul>
                                    <p>that form is ICT Form 3. It is available below in both Acrobat and MS Word format.</p>
                                </div>
                                <div class="row">
                                    @php for ($x = 1; $x <= 2; $x++) { @endphp
                                        <div class="col-sm-6 n-gapp-1" data-aos="fade-up">
                                            <div class="documents">
                                                <div class="-doct-img">
                                                    <i class="n-icon" data-icon="s-pdf"></i>
                                                    <i class="n-icon" data-icon="s-download"></i>
                                                </div>
                                                <div>
                                                    <a class="-link n-ah-a-500" href="#" download="" title="">Information and Communication Technology Authority [Interception of Telecommunication Messages) 2018</a>
                                                </div>
                                            </div>
                                        </div>
                                    @php } @endphp
                                </div>
                            </div>
                        </li>

                        <li class="-li">
                            <a class="-tabs collapsed" data-toggle="collapse" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">Radio Application Forms <span></span></a>
                            <div id="collapseThree" class="-info collapse" data-parent="#applicationforms">
                                <div class="cms">
                                    <h2>Amateur Radio</h2>
                                </div>
                                <div class="row">
                                    @php for ($x = 1; $x <= 2; $x++) { @endphp
                                        <div class="col-sm-6 n-gapp-1" data-aos="fade-up">
                                            <div class="documents">
                                                <div class="-doct-img">
                                                    <i class="n-icon" data-icon="s-pdf"></i>
                                                    <i class="n-icon" data-icon="s-download"></i>
                                                </div>
                                                <div>
                                                    <a class="-link n-ah-a-500" href="#" download="" title="">Information and Communication Technology Authority [Interception of Telecommunication Messages) 2018</a>
                                                </div>
                                            </div>
                                        </div>
                                    @php } @endphp
                                </div>
                                <div class="cms">
                                    <h2>Aircraft Radio</h2>
                                </div>
                                <div class="row">
                                    @php for ($x = 1; $x <= 1; $x++) { @endphp
                                        <div class="col-sm-6 n-gapp-1" data-aos="fade-up">
                                            <div class="documents">
                                                <div class="-doct-img">
                                                    <i class="n-icon" data-icon="s-pdf"></i>
                                                    <i class="n-icon" data-icon="s-download"></i>
                                                </div>
                                                <div>
                                                    <a class="-link n-ah-a-500" href="#" download="" title="">Information and Communication Technology Authority [Interception of Telecommunication Messages) 2018</a>
                                                </div>
                                            </div>
                                        </div>
                                    @php } @endphp
                                </div>
                                <div class="cms">
                                    <h2>Land Mobile</h2>
                                </div>
                                <div class="row">
                                    @php for ($x = 1; $x <= 2; $x++) { @endphp
                                        <div class="col-sm-6 n-gapp-1" data-aos="fade-up">
                                            <div class="documents">
                                                <div class="-doct-img">
                                                    <i class="n-icon" data-icon="s-pdf"></i>
                                                    <i class="n-icon" data-icon="s-download"></i>
                                                </div>
                                                <div>
                                                    <a class="-link n-ah-a-500" href="#" download="" title="">Information and Communication Technology Authority [Interception of Telecommunication Messages) 2018</a>
                                                </div>
                                            </div>
                                        </div>
                                    @php } @endphp
                                </div>
                                <div class="cms">
                                    <h2>Ship Radio</h2>
                                </div>
                                <div class="row">
                                    @php for ($x = 1; $x <= 2; $x++) { @endphp
                                        <div class="col-sm-6 n-gapp-1" data-aos="fade-up">
                                            <div class="documents">
                                                <div class="-doct-img">
                                                    <i class="n-icon" data-icon="s-pdf"></i>
                                                    <i class="n-icon" data-icon="s-download"></i>
                                                </div>
                                                <div>
                                                    <a class="-link n-ah-a-500" href="#" download="" title="">Information and Communication Technology Authority [Interception of Telecommunication Messages) 2018</a>
                                                </div>
                                            </div>
                                        </div>
                                    @php } @endphp
                                </div>
                            </div>
                        </li>

                        <li class="-li">
                            <a class="-tabs collapsed" data-toggle="collapse" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour">Application Fees <span></span></a>
                            <div id="collapseFour" class="-info collapse" data-parent="#applicationforms">
                                <div class="cms">
                                    <h2>Introduction</h2>
                                    <p>With effect from 1 August 2005, the following application fees must accompany an application for a licence for the ICT Networks or ICT Services detailed below. Where a single application covers more than one network or service, the total application fee is the sum of the fees for the individual networks and services. Cheques should be made out to "OfReg", and should be in Cayman Islands' Dollars drawn on a Cayman Islands' bank. </p>
                                    <p>It should be noted that no application will be processed until all application fees have been received.</p>
                                </div>

                                <div class="row n-mt-15">
                                    <div class="col-sm-6">
                                        <div class="cms">
                                            <h2>Application Fees for Major Public ICT Networks</h2>
                                            <p>Please see this document Application Fees for Major Public ICT Networks and Services</p>
                                        </div>
                                        <div class="documents">
                                            <div class="-doct-img">
                                                <i class="n-icon" data-icon="s-pdf"></i>
                                                <i class="n-icon" data-icon="s-download"></i>
                                            </div>
                                            <div>
                                                <a class="-link n-ah-a-500" href="#" download="" title="">Application Fees for Major Public ICT Networks and Services</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="cms">
                                            <h2>Application Fees for ICT Services</h2>
                                            <p>Please see this document Application Fees for Major Public ICT Networks and Services</p>
                                        </div>
                                        <div class="documents">
                                            <div class="-doct-img">
                                                <i class="n-icon" data-icon="s-pdf"></i>
                                                <i class="n-icon" data-icon="s-download"></i>
                                            </div>
                                            <div>
                                                <a class="-link n-ah-a-500" href="#" download="" title="">Application Fees for Major Public ICT Networks and Services</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="cms">
                                    <h2>Notes</h2>
                                    <ul>
                                        <li>This licence permits a Licensee to offer international voice and data communications to the Licensee's business clients solely for use in emergency situations. An emergency is defined as instances where the client's normal communications service provider(s) is unable to provide its services for a period of such duration that there is a material impact upon the transaction of the client's normal business (e.g. as the result of damage following a hurricane) and the Office has acknowledged in writing the existance of such conditions.</li>
                                        <li>Information Security Services may be licensed by the Office on application from Persons who wish to be so licensed, but such licensing is not mandatory. Where a Person wishing to provide Information Security Services makes application to the Office for an ICT Service Licence, the Office will process that application in the same manner and to the same standards as it would process applications for any other type of ICT Service Licence, and the Office may decline to award such Licence.</li>
                                    </ul>
                                    <h2>Definitions</h2>
                                    <p>The following terms have the same definition as those provided in the Information and Communications Technology Law:</p>
                                    <table>
                                        <tr>
                                            <td>Authority</td>
                                            <td>ICT</td>
                                            <td>ICT Network</td>
                                            <td>ICT Service</td>
                                        </tr>
                                        <tr>
                                            <td>Interconnection</td>
                                            <td>Internet Access</td>
                                            <td>Licence</td>
                                            <td>Licensee</td>
                                        </tr>
                                        <tr>
                                            <td>Message</td>
                                            <td>Person</td>
                                            <td>Subscriber</td>
                                            <td></td>
                                        </tr>
                                    </table>
                                    <h2>In this document</h2>
                                    <ul>
                                        <li><b>"Commercial Operation"</b> means the use of an ICT Network by a Licensee to provide an ICT Service to any Person (with the exception of their own employees) or other Licensee, charging a fee for that service.</li>
                                        <li><b>"Communication System"</b> means facilities, equipment or components which are used for the emission, transmission or reception of Messages by any wire, cable, radio, wireless, microwave, laser, satellite, optical (including all free space optical techniques) or other electromagnetic system, or by any similar technical system, provided the facilities, equipment or components are located in the Cayman Islands, or operated from ships, aircraft or spacecraft registered or operating in the Cayman Islands. For the purposes of this document, the term "Communication System" includes a whole Communication System, equipment or facilities forming part of a Communication System, or an individual component of a Communication System.</li>
                                        <li><b>"Information Security Services"</b> has the meaning given in the Electronic Transactions Law, 2000.</li>
                                        <li><b>"Infrastructure"</b> has the meaning given in the Interconnection and Access to Infrastructure Regulations, 2003.</li>
                                        <li><b>"Internet Service Provider"</b> means a Person who provides Internet Access to Subscribers.</li>
                                        <li><b>"Publication of directories"</b> means the supply of directory listings in any medium, format or sequence information whereby the identity of Subscribers may be made public.</li>
                                        <li><b>"Sound broadcasting"</b> means all forms of sound broadcasting transmitted from a station in the Cayman Islands.</li>
                                        <li>"Telephony" means:
                                        <ul>
                                            <li>All forms of wholesale telephony or any other form of supply of Communication System capacity, whether as interconnection services or as airtime, by one ICT Licensee to one or more other ICT Licensees or by an ICT Licensee to Subscribers;</li>
                                            <li>All forms of retail telephony involving the transmission to and from Subscribers of signals over Communication Systems, including the domestic and international transmission of voice, data, facsimile, moving image or still image messages, regardless of the method of transmission;</li>
                                            <li>All forms of providing (including selling, leasing, renting, gifting etc.) dark or unlit fibre to any Person.</li>
                                        </ul>
                                        </li>
                                        <li><b>Television broadcasting</b> means:
                                        <ul>
                                            <li>All forms of terrestrial television broadcasting transmitted from a station in the Cayman Islands;</li>
                                            <li>All forms of television broadcasting distributed by cable, satellite or other ICT Network (including the Internet) from a distribution point located in the Cayman Islands.</li>
                                        </ul>
                                        </li>
                                        <li><b>Video on Demand</b> means the streaming of video material in the Cayman Islands in response to a request from a subscriber, or for subsequent resale to a subscriber, except where the video stream is delivered over the public Internet or the video material is television programming.</li>
                                    </ul>
                                </div>
                            </div>
                        </li>

                        <li class="-li">
                            <a class="-tabs collapsed" data-toggle="collapse" href="#collapseFive" aria-expanded="false" aria-controls="collapseFive">Licence Fees <span></span></a>
                            <div id="collapseFive" class="-info collapse" data-parent="#applicationforms">
                                <div class="cms">
                                    <h2>Introduction</h2>
                                    <p>The ICT Guideline Document, Licence Fees for Long-Term Licensees (GD1) provides ICT Network operators and ICT Service providers licensed by OfReg ( or the "Office") with guidelines on the procedures to be used for the calculation and payment of licence fees by Licensees of major public ICT Networks and ICT Services as listed in the Office's recent Section 23(2) Notice.</p>
                                </div>

                                <div class="row n-mt-15">
                                    <div class="col-sm-6">
                                        <div class="documents">
                                            <div class="-doct-img">
                                                <i class="n-icon" data-icon="s-pdf"></i>
                                                <i class="n-icon" data-icon="s-download"></i>
                                            </div>
                                            <div>
                                                <a class="-link n-ah-a-500" href="#" download="" title="">Application Fees for Major Public ICT Networks and Services</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="documents">
                                            <div class="-doct-img">
                                                <i class="n-icon" data-icon="s-pdf"></i>
                                                <i class="n-icon" data-icon="s-download"></i>
                                            </div>
                                            <div>
                                                <a class="-link n-ah-a-500" href="#" download="" title="">Application Fees for Major Public ICT Networks and Services</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="cms">
                                    <p>These procedures are subject to amendment and updating and any changes will be notified to ICT Licensees and republished on the Office's web site.</p>
                                    <p>The information in the document and on this web page should not be considered to represent legal or commercial advice and readers should seek appropriate professional advice appropriate to their own circumstances. The document and this web page are without prejudice to the legal position or the rights and duties of the Office to regulate the market generally. Any views expressed are without prejudice to the final form and content of any decisions the Office may issue.</p>
                                    <h2>Who is liable to pay Licence Fees?</h2>
                                    <p>Cable & Wireless' licence refers to the revenues to be used for the calculation of the regulatory fee as ICT Sector One which was defined as “… all those activities identified as Types 1, 2, 3, 4, 5, and 9 (limited to Internet Service Providers) ICT Services, as set out in the first Section 23(2) Notice iissued by the Office, as a minimum, and such others as the Office may prescribe from time to time.�? By this Guidelines document, the Authority redefines ICT Sector One to mean all those activities identified as ICT Networks and ICT Services as set out in the most recent Section 23(2) Notice.</p>
                                </div>

                                <div class="row n-mt-15">
                                    <div class="col-sm-6">
                                        <div class="documents">
                                            <div class="-doct-img">
                                                <i class="n-icon" data-icon="s-pdf"></i>
                                                <i class="n-icon" data-icon="s-download"></i>
                                            </div>
                                            <div>
                                                <a class="-link n-ah-a-500" href="#" download="" title="">Application Fees for Major Public ICT Networks and Services</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="documents">
                                            <div class="-doct-img">
                                                <i class="n-icon" data-icon="s-pdf"></i>
                                                <i class="n-icon" data-icon="s-download"></i>
                                            </div>
                                            <div>
                                                <a class="-link n-ah-a-500" href="#" download="" title="">Application Fees for Major Public ICT Networks and Services</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="cms">
                                    <h2>How will Licence Fees be calculated?</h2>
                                    <p>The licence fees will be calculated and paid in arrears on a quarterly basis with an annual true up mechanism based on the Licensee's annual audited financial statements.</p>
                                    <p>For each Licensee, licence fees will be comprised of a <strong>royalty fee</strong> and a<strong> regulatory fee.</strong></p>
                                    <p>The <strong>royalty fee</strong>, which is set by the Government, will be 6% of each Licensee's revenues. The <strong>regulatory fee</strong>, which is based on the Office's costs for regulating ICT Sector One, will be pro-rated across all Licensees based on each Licensee's quarterly revenues as a percentage of all Licensees' quarterly revenues. Both components of the licence fee will be remitted by Licensees on quarterly to the Office.</p>
                                    <p>The Office will use Licensees' revenues for the quarter immediately preceding the quarter for which licence fees are due for purposes of calculating each Licensee's regulatory fee. In part, the reason for this is because of the time it will take for the Office to calculate the regulatory fee payable by each Licensee which can only be done after all Licensees provide their revenues. Given that Licensees' revenues will only be available some time after the end of the last month of each quarter, the Office is concerned that using revenues for the immediately preceding quarter would result in undue delays in calculating and collecting regulatory fees. Hence, the Office has decided to use revenues for the quarter prior to the immediately preceding quarter to calculate each Licensee's regulatory fee.</p>
                                    <h2>The quarterly licence fee therefore will comprise of</h2>
                                    <ul>
                                        <li>a royalty fee to be calculated as 6% of the Licensee's quarterly revenues; and</li>
                                        <li>a regulatory fee to be calculated based on the Office’s quarterly on going expenditures for the regulation of ICT Networks and ICT Services multiplied by the Licensee’s revenues for the quarter immediately preceding the quarter for which licence fees are due, divided by all Licensees’ revenues for the same quarter.</li>
                                    </ul>
                                    <p>The regulatory fee to be paid by each Licensee during the Office's financial year (i.e., 1 July to 30 June) shall not exceed six hundred thousand dollars.</p>
                                    <p>When the results for a Licensee’s financial year are finalised and it transpires that the audited revenues are higher than the sum of the unaudited quarterly revenues, which were used to calculate licence fees during the year, the Licensee will be liable for an additional royalty fee equal to 6% of the difference between the audited revenues and the sum of its unaudited quarterly revenues. This will be payable forthwith upon submission of the Licensee’s annual audited financial statements, which are due no later than three months after the end of its financial year. If, on the other hand, the Licensee’s audited revenues are lower than the sum of its unaudited revenues, the Office will calculate and apply an equivalent credit to the Licensee’s royalty fee for the current year. In either case, the Office does not anticipate collecting additional regulatory fees.</p>
                                    <h2>What constitutes Revenues for Licence Fee purposes?</h2>
                                    <p>Revenues for purposes of calculating the royalty fee and the regulatory fee are defined as:</p>
                                    <blockquote>
                                        <p>The total amount of receipts in money or money’s worth received by the Licensee from all sources arising out of or in connection with the Licensee’s business in or from the Cayman Islands for a defined period of time (e.g., monthly, quarterly or annually, as the case may be).</p>
                                    </blockquote>
                                    <h2>Less</h2>
                                    <ul>
                                        <li>Payments made to other ICT Licensees for interconnection, infrastructure or wholesale services for that same period; and</li>
                                        <li>Settlement payments made to international carriers for international traffic, including adjustments to payments for such traffic for that same period. If any of the payments are made to a Licensee’s affiliate, the amount deducted for purposes of calculating revenues will be only to the extent that those payments are made at open market value on an arm’s-length basis; and</li>
                                        <li>Non-recurring extra-ordinary items of receipt (including real estate) that are not made in the ordinary course of business.</li>
                                    </ul>
                                    <p>While the definition of revenues in the licence uses the words "money or money's worth received", the Information and Communications Technology Law (Determination of Turnover) Order, 2004, for the purposes of then section 34Q(8) of the ICT Law, defined "turnover" to mean the total amount of receipts in money or money's worth earned by a licensee. The Office uses that definition for licence fee purposes and therefore, for the purposes of calculating the royalty fee and the regulatory fee, Licensees are to report based on money or money's worth earned.</p>
                                    <p>A Licensee must report its quarterly turnover and quarterly revenues in a licence fee report, the format of which is attached as Schedule 1 to the Guidelines document.</p>
                                </div>

                                <div class="documents">
                                    <div class="-doct-img">
                                        <i class="n-icon" data-icon="s-pdf"></i>
                                        <i class="n-icon" data-icon="s-download"></i>
                                    </div>
                                    <div>
                                        <a class="-link n-ah-a-500" href="#" download="" title="">Guidelines document.</a>
                                    </div>
                                </div>

                                <div class="cms">
                                    <h2>Other Matters</h2>
                                    <p>The above is merely a summary of Guidelines Document GD1which should be read by all Licensees and potential Licensees.</p>
                                </div>

                                <div class="documents">
                                    <div class="-doct-img">
                                        <i class="n-icon" data-icon="s-pdf"></i>
                                        <i class="n-icon" data-icon="s-download"></i>
                                    </div>
                                    <div>
                                        <a class="-link n-ah-a-500" href="#" download="" title="">Guidelines Document GD1</a>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>


