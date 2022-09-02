<section class="inner-page-gap bod-meetings">
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
                <div class="cms" data-aos="fade-up">
                    <h2>CUC Vs WestTel Ltd. T/A Logic</h2>
                    <p>Ruling of the Grand Court in the matter of CUC Ltd. submitting an application for an injunction against WestTel Ltd. T/A Logic to prevent it from making attachments to poles without permission. To see a copy of the ruling please click on the following document  Grand Court Decision - CUC Ltd. vs WestTel Ltd. T/A Logic.</p>
                </div>

                <div class="cms" data-aos="fade-up">
                    <div class="documents">
                        <div class="-doct-img">
                            <i class="n-icon" data-icon="s-pdf"></i>
                            <i class="n-icon" data-icon="s-download"></i>
                        </div>
                        <div>
                            <a class="-link n-ah-a-500" href="#" download="" title="">Grand Court Decision - CUC Ltd. vs WestTel Ltd. T/A Logic.</a>
                        </div>
                    </div>
                </div>

                <div class="cms" data-aos="fade-up">
                    <h2>DataLink - Telecommunications Safety Guidelines</h2>
                    <p>DataLink, working with an Industry working group, has set out its General Guidelines for Telecommunication Workers when Attaching to Electric Utility Assets, a copy of which can be found. These Guidelines are aimed at ensuring that telecommunications workers working on the electricity poles do so in a safe manner.</p>
                </div>

                <div class="cms" data-aos="fade-up">
                    <div class="documents">
                        <div class="-doct-img">
                            <i class="n-icon" data-icon="s-pdf"></i>
                            <i class="n-icon" data-icon="s-download"></i>
                        </div>
                        <div>
                            <a class="-link n-ah-a-500" href="#" download="" title="">Grand Court Decision - CUC Ltd. vs WestTel Ltd. T/A Logic.</a>
                        </div>
                    </div>
                </div>

                <div class="cms" data-aos="fade-up">
                    <h2>Cable & Wireless Filings With The Authority</h2>
                </div>

                <div class="row">
                    @php for ($x = 1; $x <= 2; $x++) { @endphp
                        @php if ( $x & 1 ) { @endphp
                            <div class="col-sm-6 n-gapp-1" data-aos="fade-up">
                        @php } else { @endphp
                            <div class="col-sm-6 n-gapp-1" data-aos="fade-up">
                        @php } @endphp
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

                <div class="cms" data-aos="fade-up">
                    <h2>Licence Revocations</h2>
                </div>

                <div class="row">
                    @php for ($x = 1; $x <= 2; $x++) { @endphp
                        @php if ( $x & 1 ) { @endphp
                            <div class="col-sm-6 n-gapp-1" data-aos="fade-up">
                        @php } else { @endphp
                            <div class="col-sm-6 n-gapp-1" data-aos="fade-up">
                        @php } @endphp
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

                <div class="cms" data-aos="fade-up">
                    <h2>Termination Of Cable & Wireless Exclusive Licence</h2>
                    <p>On 10 July 2003, an Agreement was signed by the Hon. Linford Pierson OBE, JP, Minister of Planning, Communications, Works & Information Technology on behalf of the Cayman Islands Government and Mr Timothy Adam, General Manager of Cable & Wireless (Cayman Islands) Ltd that results in the voluntary surrender of the Company's exclusive licence for the provision of domestic and international telecommunications services. At the same ceremony, the Chairman of the ICT Authority issued Cable & Wireless with their new, non-exclusive licence. Copies of the various documents are available below in Adobe Acrobat format.</p>
                </div>

                <div class="row">
                    @php for ($x = 1; $x <= 4; $x++) { @endphp
                        @php if ( $x & 1 ) { @endphp
                            <div class="col-sm-6 n-gapp-1" data-aos="fade-up">
                        @php } else { @endphp
                            <div class="col-sm-6 n-gapp-1" data-aos="fade-up">
                        @php } @endphp
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

                <div class="cms" data-aos="fade-up">
                    <ul>
                        <li>Schedule 3. Official Notice of date of termination of the old Cable &amp; Wireless Licence</li>
                        <li>Schedule 4. Tariff Regulation &amp; Other Matters</li>
                        <li>Schedule 5. Policy Direction to the ICT Authority</li>
                        <li>Schedule 6. Licensing Timetable</li>
                    </ul>
                </div>

                <div class="row">
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
                </div>
            </div>
        </div>
    </div>
</section>


