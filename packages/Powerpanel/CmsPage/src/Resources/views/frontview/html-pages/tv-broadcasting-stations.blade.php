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
                    <blockquote>
                        <p>The following is a listing of licensed providers that offer TV Broadcasting services in the Cayman Islands:</p>
                    </blockquote>
                    <h3>Subscription TV Service Providers</h3>
                    <p>Subscription TV Service, sometimes referred to as Cable TV, is typically offered in multiple tiers which generally depend on the TV channels offered (Network Channels, Movie Channels, Special Interest Channels, etc).</p>
                    <p>The following is a listing of the Subscription TV Service providers currently licensed in the Cayman Islands:</p>
                    <ul>
                        <li>WestStar TV Ltd.</li>
                        <li>FLOW</li>
                        <li>Infinity Broadband (T/A C3)</li>
                        <li>Digicel Cayman Ltd. - not available at this time</li>
                        <li>WestTel Ltd. (T/A Logic)</li>
                    </ul>
                    <h2>Public TV Service Providers</h2>
                    <p>Subscription TV Service providers are required to provide a channel free to the public, also known as Public TV Service. This is often referred to as "Over-the-Air" ('OTA'), however, with the ever evolving technology sector, this service can be provided over numerous mediums, e.g. Transmitted over the air for reception typically to Televisions equipped with an antenna or transmitted over a wired network (copper, fiber optic, etc). Public TV Service Licensees are required to provide this service at no charge, for those Licensees that use a wired network some of these networks are still being deployed and so there may be a limitation of availability in your area, from all Licensees, at this time.</p>
                    <blockquote>
                        <p>The following is a listing of the Public TV Service providers currently licensed in the Cayman Islands:</p>
                    </blockquote>
                </div>
                <div class="row n-mt-15">
                    <div class="col-sm-6">
                        <div class="cms">
                            <h2>Over the air</h2>
                            <ul>
                                <li>Cayman Christian TV - provided on channel 21</li>
                                <li>Hurley's TV Ltd. - provided on channels 24 and 27</li>
                                <li>CI Conference of Seventh Day Adventist - provided on channel 30</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="cms">
                            <h2>Over a fixed network</h2>
                            <ul>
                                <li>FLOW</li>
                                <li>Infinity Broadband (T/A C3)</li>
                                <li>Digicel Cayman Ltd. - not available at this time</li>
                                <li>WestTel Ltd. (T/A Logic)</li>
                                <li>CI Government - provided over other Licensee's networks</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="cms">
                    <p><i><strong>Please note: The above information (both for subscription and public service broadcasting services) is for guidance only and people should contact the providers individually for further information about the services offered.</strong></i></p>
                </div>
            </div>
        </div>
    </div>
</section>