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
                <ul class="nqul ac-collapse accordion" id="determinationrequests">
                    <li class="-li">
                        <a class="-tabs" data-toggle="collapse" href="#dr01" aria-expanded="true" aria-controls="dr01" title="Aug 12 - Digicel Complaint Against LIME Re LNP Rules">Aug 12 - Digicel Complaint Against LIME Re LNP Rules <span></span></a>
                        <div id="dr01" class="-info collapse show" aria-labelledby="headingOne" data-parent="#determinationrequests">
                            <div class="cms">
                                <p>For Background Documents see Local Number Portability (LNP) Public Record</p>
                            </div>
                        </div>
                    </li>

                    <li class="-li">
                        <a class="-tabs collapsed" data-toggle="collapse" href="#dr02" aria-expanded="true" aria-controls="dr02" title="Mar 12 - Digicel Complaint Re LIME's Coverage Advertisement">Mar 12 - Digicel Complaint Re LIME's Coverage Advertisement <span></span></a>
                        <div id="dr02" class="-info collapse" aria-labelledby="headingOne" data-parent="#determinationrequests">
                            <div class="cms">
                                <ul>
                                    <li>7-Mar-12 Digicel letter of complaint against LIME's Coverage Advertisement</li>
                                    <li>23-Mar-12 LIME's response</li>
                                </ul>
                            </div>

                            <div class="cms">
                                <div class="documents">
                                    <div class="-doct-img">
                                        <i class="n-icon" data-icon="s-pdf"></i>
                                        <i class="n-icon" data-icon="s-download"></i>
                                    </div>
                                    <div>
                                        <a class="-link n-ah-a-500" href="#" download="" title="">The Risks of Text Messages for User Authentication</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="-li">
                        <a class="-tabs collapsed" data-toggle="collapse" href="#dr03" aria-expanded="true" aria-controls="dr03" title="Feb 12 - Digicel Complaint Against LIME Re ULL">Feb 12 - Digicel Complaint Against LIME Re ULL <span></span></a>
                        <div id="dr03" class="-info collapse" aria-labelledby="headingOne" data-parent="#determinationrequests">
                            <div class="row">
                                @php for ($x = 1; $x <= 9; $x++) { @endphp
                                    @php if ( $x & 1 ) { @endphp
                                        <div class="col-lg-6 d-flex n-gap-2 n-gapp-lg-3 n-gapm-lg-2" data-aos="fade-right">
                                    @php } else { @endphp
                                        <div class="col-lg-6 d-flex n-gap-2 n-gapp-lg-3 n-gapm-lg-2" data-aos="fade-left">
                                    @php } @endphp
                                        <article class="-items w-100 n-bs-1 n-pa-20 n-p-relative n-bgc-white-500">
                                            <div class="documents align-items-start">
                                                <div class="-doct-img">
                                                    <i class="n-icon" data-icon="s-pdf"></i>
                                                    <i class="n-icon" data-icon="s-download"></i>
                                                </div>
                                                <div>
                                                    <a class="-link n-ah-a-500" href="#" download="" title="">Minutes of Special Meeting #1 of 2021 signed Redacted(07 - January - 2021)</a>
                                                    <ul class="nqul d-flex justify-content-between align-items-center n-mt-15 n-fs-15 n-fw-500 n-ff-2 n-fc-a-500">
                                                        <li class="nq-svg align-items-center d-flex"><i class="n-icon" data-icon="s-calendar"></i> 07 January, 2021</li>
                                                        <li><a href="#" title="View" class="ac-btn ac-btn-primary ac-small" target="_blank">View</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </article>
                                    </div>
                                @php } @endphp
                            </div>
                        </div>
                    </li>

                    <li class="-li">
                        <a class="-tabs collapsed" data-toggle="collapse" href="#dr04" aria-expanded="true" aria-controls="dr04" title="Jan 12 - LIME Use Of Routing Numbers">Jan 12 - LIME Use Of Routing Numbers <span></span></a>
                        <div id="dr04" class="-info collapse" aria-labelledby="headingOne" data-parent="#determinationrequests">
                            <div class="cms">
                                <ul>
                                    <li>For Background Documents see Local Number Portability (LNP) Public Record</li>
                                </ul>
                            </div>
                        </div>
                    </li>

                    <li class="-li">
                        <a class="-tabs collapsed" data-toggle="collapse" href="#dr05" aria-expanded="true" aria-controls="dr05" title="Aug 11 - Digicel Complaint Against LIME Re 4G Advertisement">Aug 11 - Digicel Complaint Against LIME Re 4G Advertisement <span></span></a>
                        <div id="dr05" class="-info collapse" aria-labelledby="headingOne" data-parent="#determinationrequests">
                            <div class="cms">
                                <p>5-Sep-11 LIME's response</p>
                            </div>

                            <div class="row n-mt-25">
                                @php for ($x = 1; $x <= 2; $x++) { @endphp
                                    @php if ( $x & 1 ) { @endphp
                                        <div class="col-lg-6 d-flex n-gap-2 n-gapp-lg-3 n-gapm-lg-2" data-aos="fade-right">
                                    @php } else { @endphp
                                        <div class="col-lg-6 d-flex n-gap-2 n-gapp-lg-3 n-gapm-lg-2" data-aos="fade-left">
                                    @php } @endphp
                                        <article class="-items w-100 n-bs-1 n-pa-20 n-p-relative n-bgc-white-500">
                                            <div class="documents align-items-start">
                                                <div class="-doct-img">
                                                    <i class="n-icon" data-icon="s-pdf"></i>
                                                    <i class="n-icon" data-icon="s-download"></i>
                                                </div>
                                                <div>
                                                    <a class="-link n-ah-a-500" href="#" download="" title="">Minutes of Special Meeting #1 of 2021 signed Redacted(07 - January - 2021)</a>
                                                    <ul class="nqul d-flex justify-content-between align-items-center n-mt-15 n-fs-15 n-fw-500 n-ff-2 n-fc-a-500">
                                                        <li class="nq-svg align-items-center d-flex"><i class="n-icon" data-icon="s-calendar"></i> 07 January, 2021</li>
                                                        <li><a href="#" title="View" class="ac-btn ac-btn-primary ac-small" target="_blank">View</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </article>
                                    </div>
                                @php } @endphp
                            </div>
                        </div>
                    </li>

                    <li class="-li">
                        <a class="-tabs collapsed" data-toggle="collapse" href="#dr06" aria-expanded="true" aria-controls="dr06" title="Jul 10 - LNP Cost Sharing Dispute">Jul 10 - LNP Cost Sharing Dispute <span></span></a>
                        <div id="dr06" class="-info collapse" aria-labelledby="headingOne" data-parent="#determinationrequests">
                            <div class="cms">
                                <p>For Background Documents see Local Number Portability (LNP) Public Record</p>
                            </div>
                        </div>
                    </li>

                    <li class="-li">
                        <a class="-tabs collapsed" data-toggle="collapse" href="#dr07" aria-expanded="true" aria-controls="dr07" title="Feb 10 - Digicel Complaint Against LIME Re Unlimited SMS">Feb 10 - Digicel Complaint Against LIME Re Unlimited SMS <span></span></a>
                        <div id="dr07" class="-info collapse" aria-labelledby="headingOne" data-parent="#determinationrequests">
                            <div class="row">
                                @php for ($x = 1; $x <= 3; $x++) { @endphp
                                    @php if ( $x & 1 ) { @endphp
                                        <div class="col-lg-6 d-flex n-gap-2 n-gapp-lg-3 n-gapm-lg-2" data-aos="fade-right">
                                    @php } else { @endphp
                                        <div class="col-lg-6 d-flex n-gap-2 n-gapp-lg-3 n-gapm-lg-2" data-aos="fade-left">
                                    @php } @endphp
                                        <article class="-items w-100 n-bs-1 n-pa-20 n-p-relative n-bgc-white-500">
                                            <div class="documents align-items-start">
                                                <div class="-doct-img">
                                                    <i class="n-icon" data-icon="s-pdf"></i>
                                                    <i class="n-icon" data-icon="s-download"></i>
                                                </div>
                                                <div>
                                                    <a class="-link n-ah-a-500" href="#" download="" title="">Minutes of Special Meeting #1 of 2021 signed Redacted(07 - January - 2021)</a>
                                                    <ul class="nqul d-flex justify-content-between align-items-center n-mt-15 n-fs-15 n-fw-500 n-ff-2 n-fc-a-500">
                                                        <li class="nq-svg align-items-center d-flex"><i class="n-icon" data-icon="s-calendar"></i> 07 January, 2021</li>
                                                        <li><a href="#" title="View" class="ac-btn ac-btn-primary ac-small" target="_blank">View</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </article>
                                    </div>
                                @php } @endphp
                            </div>
                        </div>
                    </li>

                    <li class="-li">
                        <a class="-tabs collapsed" data-toggle="collapse" href="#dr08" aria-expanded="true" aria-controls="dr08" title="Dec 09 - Digicel/LIME Interconnection Agreement Dispute">Dec 09 - Digicel/LIME Interconnection Agreement Dispute <span></span></a>
                        <div id="dr08" class="-info collapse" aria-labelledby="headingOne" data-parent="#determinationrequests">
                            <div class="row">
                                @php for ($x = 1; $x <= 37; $x++) { @endphp
                                    @php if ( $x & 1 ) { @endphp
                                        <div class="col-lg-6 d-flex n-gap-2 n-gapp-lg-3 n-gapm-lg-2" data-aos="fade-right">
                                    @php } else { @endphp
                                        <div class="col-lg-6 d-flex n-gap-2 n-gapp-lg-3 n-gapm-lg-2" data-aos="fade-left">
                                    @php } @endphp
                                        <article class="-items w-100 n-bs-1 n-pa-20 n-p-relative n-bgc-white-500">
                                            <div class="documents align-items-start">
                                                <div class="-doct-img">
                                                    <i class="n-icon" data-icon="s-pdf"></i>
                                                    <i class="n-icon" data-icon="s-download"></i>
                                                </div>
                                                <div>
                                                    <a class="-link n-ah-a-500" href="#" download="" title="">Minutes of Special Meeting #1 of 2021 signed Redacted(07 - January - 2021)</a>
                                                    <ul class="nqul d-flex justify-content-between align-items-center n-mt-15 n-fs-15 n-fw-500 n-ff-2 n-fc-a-500">
                                                        <li class="nq-svg align-items-center d-flex"><i class="n-icon" data-icon="s-calendar"></i> 07 January, 2021</li>
                                                        <li><a href="#" title="View" class="ac-btn ac-btn-primary ac-small" target="_blank">View</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </article>
                                    </div>
                                @php } @endphp
                            </div>
                        </div>
                    </li>

                    <li class="-li">
                        <a class="-tabs collapsed" data-toggle="collapse" href="#dr09" aria-expanded="true" aria-controls="dr09" title="25 Oct 06 - C&W Request For A Proceeding To Determine The Mobile Termination Rate (MTR)">25 Oct 06 - C&W Request For A Proceeding To Determine The Mobile Termination Rate (MTR) <span></span></a>
                        <div id="dr09" class="-info collapse" aria-labelledby="headingOne" data-parent="#determinationrequests">
                            <div class="row">
                                @php for ($x = 1; $x <= 4; $x++) { @endphp
                                    @php if ( $x & 1 ) { @endphp
                                        <div class="col-lg-6 d-flex n-gap-2 n-gapp-lg-3 n-gapm-lg-2" data-aos="fade-right">
                                    @php } else { @endphp
                                        <div class="col-lg-6 d-flex n-gap-2 n-gapp-lg-3 n-gapm-lg-2" data-aos="fade-left">
                                    @php } @endphp
                                        <article class="-items w-100 n-bs-1 n-pa-20 n-p-relative n-bgc-white-500">
                                            <div class="documents align-items-start">
                                                <div class="-doct-img">
                                                    <i class="n-icon" data-icon="s-pdf"></i>
                                                    <i class="n-icon" data-icon="s-download"></i>
                                                </div>
                                                <div>
                                                    <a class="-link n-ah-a-500" href="#" download="" title="">Minutes of Special Meeting #1 of 2021 signed Redacted(07 - January - 2021)</a>
                                                    <ul class="nqul d-flex justify-content-between align-items-center n-mt-15 n-fs-15 n-fw-500 n-ff-2 n-fc-a-500">
                                                        <li class="nq-svg align-items-center d-flex"><i class="n-icon" data-icon="s-calendar"></i> 07 January, 2021</li>
                                                        <li><a href="#" title="View" class="ac-btn ac-btn-primary ac-small" target="_blank">View</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </article>
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


