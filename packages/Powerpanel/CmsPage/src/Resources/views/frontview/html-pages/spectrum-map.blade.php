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
                    <h2>Table of Frequency Allocations and Assignments</h2>
                    <p>Section 9 of the Information and Communications Technology Law provides that <strong>OfReg</strong> (or the '<strong>Office</strong>') shall be responsible for the allocation, assignment and licensing of the electromagnetic spectrum in the Cayman Islands and for use by ships and aircraft registered in the Cayman Islands.</p>
                    <p>The Cayman Islands Table of Frequency Allocations and Assignments is maintained and published by the Office as a service to licensees and the general public. With the exception of Column (c) in the table at Part 3, it has no legal standing, but rather summarises in a single document information from a number of sources, some of which may be legally binding. Whilst the Office will use its best endeavours to keep this document up-to-date, it provides no guarantee and strongly recommends that users refer to the primary sources detailed in the Introduction to the document prior to making any decisions based upon the information contained in this publication.</p>
                    <h2>The document is published as three .pdf documents as follows:</h2>
                </div>
                <div class="row">
                    @php for ($x = 1; $x <= 3; $x++) { @endphp
                        <div class="col-sm-4 n-gapp-1" data-aos="fade-up">
                            <div class="documents">
                                <div class="-doct-img">
                                    <i class="n-icon" data-icon="s-pdf"></i>
                                    <i class="n-icon" data-icon="s-download"></i>
                                </div>
                                <div>
                                    <a class="-link n-ah-a-500" href="#" download="" title="">Part 1 includes the Introduction, Regions and Areas and Terms and Definitions.</a>
                                </div>
                            </div>
                        </div>
                    @php } @endphp
                </div>
                <div class="cms">
                    <h2>Other Items of interest</h2>
                    <div class="documents">
                        <div class="-doct-img">
                            <i class="n-icon" data-icon="s-pdf"></i>
                            <i class="n-icon" data-icon="s-download"></i>
                        </div>
                        <div>
                            <a class="-link n-ah-a-500" href="#" download="" title="">FM Broadcasting Spectrum Map <br>(last updated, February 2021)</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>