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
                    <h2>Welcome to the site of OfReg (or the 'Office')!</h2>
                    <p>The Office is an independent statutory entity which was originally created by the enactment of the Information & Communications Technology Authority Law on 17th May 2002 (now superceded by the Information & Communications Technolgy Law which falls under the Utility Regulation and Competition Law) and is responsible for the regulation and licensing of Telecommunications, Broadcasting, and all forms of radio which includes ship, aircraft, mobile and amateur radio. The Office conducts the administration and management of the .ky domain, and also has a number of responsibilities under the Electronic Transactions Law 2000.</p>
                </div>
                <div class="documents n-mt-15">
                    <div class="-doct-img">
                        <i class="n-icon" data-icon="s-pdf"></i>
                        <i class="n-icon" data-icon="s-download"></i>
                    </div>
                    <div>
                        <a class="-link n-ah-a-500" href="#" download="" title="">Electronic Transactions Law 2000.</a>
                    </div>
                </div>
                <div class="cms">
                    <p>With the enactment of the ICTA Law, the Cayman Islands became one of the first countries in the world to officially recognise the convergence of telephony, radio and broadcasting, the Internet and e-business.</p>
                    <p>The Board of the Office reports to the Legislative Assembly (Parliament) through the Minister of Planning, Lands, Agriculture, Housing & Infrastructure.</p>
                    <h2>Quick Contact Email Addresses</h2>
                    <ul>
                        <li>General Enquiries: info@ofreg.ky</li>
                        <li>Consumer Complaints: complaints@ofreg.ky</li>
                        <li>Ship, Aircraft and Misc Licensing Matters: licensing@ofreg.ky</li>
                        <li>Contributions to Public Consultations: consultations@ofreg.ky</li>
                        <li>.ky Domain Enquiries: kyadmin@ofreg.ky</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>


