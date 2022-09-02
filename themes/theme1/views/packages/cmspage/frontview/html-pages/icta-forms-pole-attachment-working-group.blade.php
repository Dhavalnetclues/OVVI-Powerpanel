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
                    <p>On 20th December 2016, the Information and Communications Technology Authority (‘ICTA’) commenced the Pole Attachment Industry Working Group (the ‘Industry Working Group’) - to address various issues related to the installing and maintaining of attachments of communication cables to the electricity poles owned by Caribbean Utilities Company, Ltd. (‘CUC’).</p>
                </div>

                <div class="cms" data-aos="fade-up">
                    <h2>OfReg Letter to Licensees re: Re-launch of Consultation 2016-2:</h2>
                </div>

                <div class="cms" data-aos="fade-up">
                    <div class="documents">
                        <div class="-doct-img">
                            <i class="n-icon" data-icon="s-pdf"></i>
                            <i class="n-icon" data-icon="s-download"></i>
                        </div>
                        <div>
                            <a class="-link n-ah-a-500" href="#" download="" title="">2017-06-30 OfReg letter to Licensees re: Re-launch of Consultation 2016-2</a>
                        </div>
                    </div>
                </div>

                <div class="cms" data-aos="fade-up">
                    <h2>Licensee Responses on Final Position Papers of other Licensees:</h2>
                </div>

                <div class="row">
                    @php for ($x = 1; $x <= 5; $x++) { @endphp
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
                                    <a class="-link n-ah-a-500" href="#" download="" title="">2017-06-20 C3 Working Group Response</a>
                                </div>
                            </div>
                        </div>
                    @php } @endphp
                </div>

                <div class="cms" data-aos="fade-up">
                    <h2>OfReg Letter to Licensees re: Next Steps</h2>
                </div>

                <div class="cms" data-aos="fade-up">
                    <div class="documents">
                        <div class="-doct-img">
                            <i class="n-icon" data-icon="s-pdf"></i>
                            <i class="n-icon" data-icon="s-download"></i>
                        </div>
                        <div>
                            <a class="-link n-ah-a-500" href="#" download="" title="">2017-06-30 OfReg letter to Licensees re: Re-launch of Consultation 2016-2</a>
                        </div>
                    </div>
                </div>

                <div class="cms" data-aos="fade-up">
                    <h2>Draft of the Master Pole Joint Use Agreement</h2>
                </div>

                <div class="cms" data-aos="fade-up">
                    <div class="documents">
                        <div class="-doct-img">
                            <i class="n-icon" data-icon="s-pdf"></i>
                            <i class="n-icon" data-icon="s-download"></i>
                        </div>
                        <div>
                            <a class="-link n-ah-a-500" href="#" download="" title="">2017-06-30 OfReg letter to Licensees re: Re-launch of Consultation 2016-2</a>
                        </div>
                    </div>
                </div>

                <div class="cms" data-aos="fade-up">
                    <h2>Final Position Papers by Licensees at the conclusion of the Pole Attachment Industry Working Group</h2>
                </div>

                <div class="row">
                    @php for ($x = 1; $x <= 6; $x++) { @endphp
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
                                    <a class="-link n-ah-a-500" href="#" download="" title="">2017-06-20 C3 Working Group Response</a>
                                </div>
                            </div>
                        </div>
                    @php } @endphp
                </div>

                <div class="cms" data-aos="fade-up">
                    <h2>December 2016 Working Group Commencement Notice</h2>
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
                                    <a class="-link n-ah-a-500" href="#" download="" title="">2017-06-20 C3 Working Group Response</a>
                                </div>
                            </div>
                        </div>
                    @php } @endphp
                </div>
            </div>
        </div>
    </div>
</section>


