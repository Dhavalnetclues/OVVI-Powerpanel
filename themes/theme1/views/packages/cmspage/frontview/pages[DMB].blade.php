@if(!Request::ajax())
@extends('layouts.app')
@section('content')
@include('layouts.inner_banner')
@endif

@if(isset($PageData['response']) && !empty($PageData['response']) && $PageData['response'] != '[]')
    {!! $PageData['response'] !!}
@else 
    <!-- <section class="page_section n-pt-lg-80 n-pt-50 n-pb-50 n-pb-lg-80">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center" data-aos="fade-up">
                    <div class="nqtitle n-fw-800 n-lh-110 text-uppercase">Coming Soon...</div>
                    <div class="desc n-fs-20 n-fw-500 n-lh-130">We are currently working on a new super awesome page.</div>
                    kjlhjk\
                </div>  
            </div>
        </div>  
    </section>    --> 
    <section class="page_section itc-detail">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="detail-img">
                        <div class="thumbnail-container">
                            <div class="thumbnail">
                                <img src="{!! url('cdn/assets/images/itc-img.png') !!}" alt="ICT Sector Regulation" title="ICT Sector Regulation">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 d-flex align-items-center itc-bg">
                    <div class="detail-desc">
                        <h2 class="title">ICT <span>Sector</span><span>Regulation</span></h2>
                        <div class="desc">
                            <p>The Information and Communications Technology (ICT) is an independent statutory Authority which was created by the enactment of the Information & Communications Technology Authority Law on 17th May 2002 and is responsible for the regulation and licensing of Telecommunications, Broadcasting, and all forms of radio which includes ship, aircraft, mobile and amateur radio. The ICT conducts the administration and management of the .ky domain, and also has a number of responsibilities under the Electronic Transactions Law 2000.</p>
                        </div>
                        <a href="#" title="View More" class="view-btn">View More <i class="right-arrow"></i></a>
                    </div>
                </div>  
            </div>
            <div class="row grid">
                <div class="col-xl-3 col-md-6 grid-item">
                    <div class="itc-type">
                        <img src="{!! url('cdn/assets/images/domain.png') !!}" alt="KY Domain" title="KY Domain">
                        <h3 class="sub-tilte">KY Domain</h3>
                        <p>OfReg is responsible for the management and administration of the .KY Internet</p>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 grid-item">
                    <div class="itc-type">
                        <img src="{!! url('cdn/assets/images/forms.png') !!}" alt="Application Forms" title="Application Forms">
                        <h3 class="sub-tilte">Application Forms</h3>
                        <p>Applications for ICT Licences should be made on the appropriate application form.</p>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 grid-item">
                    <div class="itc-type">
                        <img src="{!! url('cdn/assets/images/consultations.png') !!}" alt="Consultations" title="Consultations">
                        <h3 class="sub-tilte">Consultations</h3>
                        <p>Consultations</p>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 grid-item">
                    <div class="itc-type">
                        <img src="{!! url('cdn/assets/images/register.png') !!}" alt="Register of Licensees" title="Register of Licensees">
                        <h3 class="sub-tilte">Register of Licensees</h3>
                        <p>OfReg is responsible for the management and administration of the .KY Internet</p>
                    </div>
                </div>
            </div>
        </div>  
    </section>   
@endif

@endsection

