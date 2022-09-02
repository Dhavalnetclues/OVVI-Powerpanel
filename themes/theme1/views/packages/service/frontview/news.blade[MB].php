@if(!Request::ajax())
@extends('layouts.app')
@section('content')
@include('layouts.inner_banner')
@endif

<section class="page_section n-pt-lg-80 n-pt-40 n-pb-40 n-pb-lg-80 directors-listing">
    <div class="container">
        <div class="row">
            <div class="col-xl-3 left-panel" data-aos="fade-up">
                <div class="sticky-top">                    
                    <div class="d-xl-none d-block">
                        <a class="ac-btn ac-btn-primary btn-block" data-toggle="collapse" href="#sortMenu" role="button" aria-expanded="false" aria-controls="sortMenu" title="Sorting Menu">Sorting Menu</a>
                    </div>
                    <div class="collapse d-xl-block n-mt-25 n-mt-xl-0" id="sortMenu">
                        <div class="ac-note n-mb-25 d-xl-none d-block">
                            <b>Note:</b> Please select your Sorting and press the GO button below.
                        </div>
                        
                        <div class="row">
                            <div class="col-xl-12 col-lg-4 col-md-6">
                                <article>
                                    <div class="nqtitle-small lp-title text-uppercase n-mb-25">Sort by Name</div>
                                    <div class="form-group ac-form-group n-mb-0">
                                        <select class="selectpicker ac-input" data-width="100%" title="Sort by Name">
                                            <option>Sort by New</option>
                                            <option>Sort by A to Z</option>
                                            <option>Sort by Z to A</option>
                                        </select>
                                    </div>
                                </article>
                            </div>
                            <div class="col-xl-12 col-lg-4 col-md-6">
                                <article class="n-mt-xl-50 n-mt-md-0 n-mt-25">
                                    <div class="nqtitle-small lp-title text-uppercase n-mb-25">Sort by Category</div>                   
                                    <ul class="nqul s-category d-flex flex-wrap n-fs-14 n-ff-2 n-fc-black-500">
                                        <li><a href="#" title="All">All</a></li>
                                        <li><a class="active" href="#" title="OfReg">OfReg</a></li>
                                        <li><a href="#" title="ICT">ICT</a></li>
                                        <li><a href="#" title="Energy">Energy</a></li>
                                        <li><a href="#" title="Fuel">Fuel</a></li>
                                        <li><a href="#" title="Water">Water</a></li>
                                    </ul>
                                </article>
                            </div>
                            <div class="col-xl-12 col-lg-4">
                                <article class="n-mt-xl-50 n-mt-lg-0 n-mt-25">
                                    <div class="nqtitle-small lp-title text-uppercase n-mb-25">Sort by Years</div>                  
                                    <div class="s-years">
                                        <ul class="nqul d-flex flex-wrap n-fs-14 n-ff-2 n-fc-black-500">
                                            <li>
                                                <div class="form-group ac-form-group n-mb-0">
                                                    <div class="ac-checkbox-list n-pt-0">
                                                        <label class="ac-checkbox">
                                                            <input type="checkbox"> 2021<span></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="form-group ac-form-group n-mb-0">
                                                    <div class="ac-checkbox-list n-pt-0">
                                                        <label class="ac-checkbox">
                                                            <input type="checkbox"> 2020<span></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="form-group ac-form-group n-mb-0">
                                                    <div class="ac-checkbox-list n-pt-0">
                                                        <label class="ac-checkbox">
                                                            <input type="checkbox"> 2019<span></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="form-group ac-form-group n-mb-0">
                                                    <div class="ac-checkbox-list n-pt-0">
                                                        <label class="ac-checkbox">
                                                            <input type="checkbox"> 2018<span></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="form-group ac-form-group n-mb-0">
                                                    <div class="ac-checkbox-list n-pt-0">
                                                        <label class="ac-checkbox">
                                                            <input type="checkbox"> 2017<span></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="form-group ac-form-group n-mb-0">
                                                    <div class="ac-checkbox-list n-pt-0">
                                                        <label class="ac-checkbox">
                                                            <input type="checkbox"> 2016<span></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="form-group ac-form-group n-mb-0">
                                                    <div class="ac-checkbox-list n-pt-0">
                                                        <label class="ac-checkbox">
                                                            <input type="checkbox"> 2014<span></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="form-group ac-form-group n-mb-0">
                                                    <div class="ac-checkbox-list n-pt-0">
                                                        <label class="ac-checkbox">
                                                            <input type="checkbox"> 2013<span></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="form-group ac-form-group n-mb-0">
                                                    <div class="ac-checkbox-list n-pt-0">
                                                        <label class="ac-checkbox">
                                                            <input type="checkbox"> 2012<span></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="form-group ac-form-group n-mb-0">
                                                    <div class="ac-checkbox-list n-pt-0">
                                                        <label class="ac-checkbox">
                                                            <input type="checkbox"> 2011<span></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="form-group ac-form-group n-mb-0">
                                                    <div class="ac-checkbox-list n-pt-0">
                                                        <label class="ac-checkbox">
                                                            <input type="checkbox"> 2009<span></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="form-group ac-form-group n-mb-0">
                                                    <div class="ac-checkbox-list n-pt-0">
                                                        <label class="ac-checkbox">
                                                            <input type="checkbox"> 2008<span></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="form-group ac-form-group n-mb-0">
                                                    <div class="ac-checkbox-list n-pt-0">
                                                        <label class="ac-checkbox">
                                                            <input type="checkbox"> 2007<span></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="form-group ac-form-group n-mb-0">
                                                    <div class="ac-checkbox-list n-pt-0">
                                                        <label class="ac-checkbox">
                                                            <input type="checkbox"> 2006<span></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="form-group ac-form-group n-mb-0">
                                                    <div class="ac-checkbox-list n-pt-0">
                                                        <label class="ac-checkbox">
                                                            <input type="checkbox"> 2005<span></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="form-group ac-form-group n-mb-0">
                                                    <div class="ac-checkbox-list n-pt-0">
                                                        <label class="ac-checkbox">
                                                            <input type="checkbox"> 2004<span></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="form-group ac-form-group n-mb-0">
                                                    <div class="ac-checkbox-list n-pt-0">
                                                        <label class="ac-checkbox">
                                                            <input type="checkbox"> 2003<span></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </article>
                            </div>
                            <div class="col-xl-12 n-mt-25">
                                <div class="nqtitle-small lp-title text-uppercase n-mb-25">About Us</div>
                                <ul class="about-list">
                                    <li><a href="#" title="Who we are">Who we are</a></li>
                                    <li><a class="active" href="#" title="Job Opportunities">Job Opportunities</a></li>
                                    <li><a href="#" title="FAQs">FAQs</a></li>
                                    <li><a href="#" title="Legislation">Legislation</a></li>
                                    <li><a href="#" title="Board of Directors">Board of Directors</a></li>
                                    <li><a href="#" title="Board of Directors Meetings">Board of Directors Meetings</a></li>
                                    <li><a href="#" title="Industry Statistics">Industry Statistics</a></li>
                                    <li><a href="#" title="Strategic Plan">Strategic Plan</a></li>
                                    <li><a href="#" title="Annual Plan">Annual Plan</a></li>
                                    <li><a href="#" title="News">News</a></li>
                                    <li><a href="#" title="Consumer Affairs">Consumer Affairs</a></li>
                                    <li><a href="#" title="ClickB4UDig">ClickB4UDig</a></li>
                                    <li><a href="#" title="Archives">Archives</a></li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="d-xl-none d-block">
                            <button type="button" class="ac-btn ac-btn-primary btn-block n-mt-25" title="Go">Go</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-9">
                <div class="row grid">
                    <div class="col-md-6 col-xss-5">
                        <div class="directors-img">
                            <div class="thumbnail-container">
                                <div class="thumbnail">
                                    <a href="#" title="Dr. The Hon. Linford A. Pierson OBE">
                                        <img src="http://localhost/Ofreg/Ofreg/public_html/cdn/assets/images/directors.png" alt="Dr. The Hon. Linford A. Pierson OBE">
                                        <div class="overlay">
                                            <i class="plus"></i>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xss-7 d-flex align-items-center">
                        <div class="directors-desc">
                            <h2 class="title">Dr. The Hon. Linford A. Pierson OBE, JP, PhD, MAPPC, FCCA</h2>
                            <span class="position">Chairman of the Board</span>
                            <h3 class="sub-title">CIVIL SERVICE AND PRIVATE SECTOR CAREERS</h3>
                            <p class="desc">Dr. Pierson worked for 16 years in the Cayman Islands Civil Service (1963-1979), where he filled a number of senior positions, including Principal Secretary (now Chief Officer) for Health, Education, and Social Services, and Deputy Financial Secretary (acting as Financial Secretary on several occasions). During the 1980s he was employed in various private sector positions, including Director of Finance for Cayman Airways, local Partner of KPMG/Thorne Riddell (the then Canadian Chartered Accounting Firm), and he held Directorships in a number of private companies.</p>
                        </div>
                    </div>
                </div>
                <div class="row grid">
                    <div class="col-md-6 col-xss-5 order-md-2">
                        <div class="directors-img ">
                            <div class="thumbnail-container">
                                <div class="thumbnail">
                                    <a href="#" title="Mr. Rudy Ebanks">
                                        <img src="http://localhost/Ofreg/Ofreg/public_html/cdn/assets/images/directors.png" alt="Mr. Rudy Ebanks">
                                        <div class="overlay">
                                            <i class="plus"></i>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xss-7 order-md-1 d-flex align-items-center">
                        <div class="directors-desc">
                            <h2 class="title">Mr. Rudy Ebanks</h2>
                            <span class="position">Non Executive Director</span>
                            <h3 class="sub-title">Summary of</h3>
                            <p class="desc">Total 12 years experience as Vice President Regulatory, Government and Carrier Relations, and later, Chief Officer Regulatory and Carrier Relations of the leading telecommunication company in the Cayman Islands.</p>
                        </div>
                    </div>
                </div>
                <div class="row grid">
                    <div class="col-md-6 col-xss-5">
                        <div class="directors-img">
                            <div class="thumbnail-container">
                                <div class="thumbnail">
                                    <a href="#" title="Mr. Paul Byles">
                                        <img src="http://localhost/Ofreg/Ofreg/public_html/cdn/assets/images/directors.png" alt="Mr. Paul Byles">
                                        <div class="overlay">
                                            <i class="plus"></i>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xss-7 d-flex align-items-center">
                        <div class="directors-desc">
                            <h2 class="title">Mr. Paul Byles</h2>
                            <span class="position">Non Executive Director</span>
                            <h3 class="sub-title">PRIVATE SECTOR CAREERS</h3>
                            <p class="desc">Paul Byles is Director of FTS, which provides regulatory and economic consulting services. He is a former director of a big four consulting firm and former Head of Policy at the Cayman Islands Monetary Authority. His economics experience includes carrying out economic impact studies at both the sectoral and individual project levels and he has also been involved in several major strategic economic initiatives at the national level including economic development planning and development of an inward investment framework.</p>
                        </div>
                    </div>
                </div>
                <div class="row grid">
                    <div class="col-md-6 col-xss-5 order-md-2">
                        <div class="directors-img">
                            <div class="thumbnail-container">
                                <div class="thumbnail">
                                    <a href="#" title="Mr. Gene Banks">
                                        <img src="http://localhost/Ofreg/Ofreg/public_html/cdn/assets/images/directors.png" alt="Mr. Gene Banks">
                                        <div class="overlay">
                                            <i class="plus"></i>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xss-7 order-md-1 d-flex align-items-center">
                        <div class="directors-desc">
                            <h2 class="title">Mr. Gene Banks</h2>
                            <span class="position">Non Executive Director</span>
                            <h3 class="sub-title">background</h3>
                            <p class="desc">Gene Banks has been a non-Executive Director of OfReg from mid-2019. Gene’s background is in the Information Technology (IT) field with over 38 years’ experience in the financial services industry. Gene’s roles have included Project Manager, Director of IT, Chief Technology Officer and Regional IT Service Delivery Manager for the Cayman and regional operations of global firms.</p>
                        </div>
                    </div>
                </div>
                <div class="row grid">
                    <div class="col-md-6 col-xss-5">
                        <div class="directors-img">
                            <div class="thumbnail-container">
                                <div class="thumbnail">
                                    <a href="#" title="Mr. H. Phillip Ebanks">
                                        <img src="http://localhost/Ofreg/Ofreg/public_html/cdn/assets/images/directors.png" alt="Mr. H. Phillip Ebanks">
                                        <div class="overlay">
                                            <i class="plus"></i>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xss-7 d-flex align-items-center">
                        <div class="directors-desc">
                            <h2 class="title">Mr. H. Phillip Ebanks</h2>
                            <span class="position">Non Executive Director</span>
                            <h3 class="sub-title">Executive Profile</h3>
                            <p class="desc">Experienced Attorney at Law and Manager with 17 years Post Qualification Experience; prior roles as a Senior Management Executive within Public sector; Police Officer with 16 years + experience at varying levels of operation and command. Combines strategic planning and legal qualifications, dispute resolution with strong business acumen including effective resources management, budgeting, negotiation and leadership skills gained in both Public and Private sectors.</p>
                        </div>
                    </div>
                </div>   
            </div>
            <!-- <div class="col-xl-9">
                <div class="row">
                    @php for ($x = 1; $x <= 6; $x++) { @endphp
                        <div class="col-xl-6 gap" data-aos="fade-up">
                            <article class="-items n-bs-1">
                                <ul class="nqul -tag d-flex align-items-center n-fs-16 n-fw-600 n-fc-black-500 n-ff-2">
                                    <li>
                                        <div class="-nimg">
                                            <img src="{{ $CDN_PATH.'assets/images/logo.png' }}" alt="ICT" title="ICT">
                                        </div>
                                        <div class="-nlabel n-fc-white-500 n-fs-18 n-fw-800">ICT</div>
                                    </li>
                                    <li class="nq-svg">
                                        <img class="svg" src="{{ $CDN_PATH.'assets/images/icon/calendar.svg' }}" alt="calendar">
                                        Nov 11, 2020
                                    </li>
                                </ul>
                                <h2 class="nqtitle-ip n-mt-15">Press Release - IPX Draft Regulatory Framework Consultation</h2>
                                <div class="cms n-mt-15">
                                    <p>The Utility Regulation and Competition Office (‘OfReg’) has published its draft determination of the Proposed Renewable Energy Capacity Reallocation and Tariff Setting proposal for the 1-megawatt (MW) of capacity to be transferred from the Distributed Energy Resources (‘DER’) programme to the Customer-Owned Renewable Energy (‘CORE’) programme.</p>
                                </div>
                                <div class="-download">
                                    <a data-toggle="collapse" href="#newsDownload@php echo $x; @endphp" role="button" aria-expanded="false" aria-controls="newsDownload@php echo $x; @endphp" title="Download" class="nq-svg n-fs-14 n-fw-600 n-fc-white-500 n-ah-white-500 n-ff-2 d-flex align-items-center">
                                        <img src="{{ $CDN_PATH.'assets/images/icon/pdf.svg' }}" alt="Download" class="svg">
                                        <span class="n-ml-10">Download</span>
                                    </a>
                                </div>
                                <div class="-pdf collapse" id="newsDownload@php echo $x; @endphp">
                                    <a class="-close" data-toggle="collapse" href="#newsDownload@php echo $x; @endphp" role="button" aria-expanded="false" aria-controls="newsDownload@php echo $x; @endphp"><i class="fa fa-close"></i></a>
                                    <div class="mCcontent">
                                        <ul class="nqul -pdflist n-fs-14 n-fw-600 n-fc-white-500 n-ff-2">
                                            <li>
                                                <a href="#" title="Download" class="nq-svg d-flex align-items-center" target="_blank" download>
                                                    <span class="-pdfimg d-inline-flex"><img src="{{ $CDN_PATH.'assets/images/icon/pdf.svg' }}" alt="" class="svg"></span>
                                                    <span class="-pdftitle d-inline-flex">The Utility Regulation and Competition Office (‘OfReg’) has published its draft determination</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </article>
                        </div>

                        <div class="col-xl-6 gap" data-aos="fade-up">
                            <article class="-items n-bs-1">
                                <ul class="nqul -tag d-flex align-items-center n-fs-16 n-fw-600 n-fc-black-500 n-ff-2">
                                    <li>
                                        <div class="-nimg">
                                            <img src="{{ $CDN_PATH.'assets/images/logo.png' }}" alt="ICT" title="ICT">
                                        </div>
                                        <div class="-nlabel n-fc-white-500 n-fs-18 n-fw-800">ICT</div>
                                    </li>
                                    <li class="nq-svg">
                                        <img class="svg" src="{{ $CDN_PATH.'assets/images/icon/calendar.svg' }}" alt="calendar">
                                        Nov 11, 2020
                                    </li>
                                </ul>
                                <h2 class="nqtitle-ip n-mt-15">Press Release - IPX Draft Regulatory Framework Consultation</h2>
                                <div class="cms n-mt-15">
                                    <p>The Utility Regulation and Competition Office (‘OfReg’) has published its draft determination of the Proposed Renewable Energy Capacity Reallocation and Tariff Setting proposal for the 1-megawatt (MW) of capacity to be transferred from the Distributed Energy Resources (‘DER’) programme to the Customer-Owned Renewable Energy (‘CORE’) programme.</p>
                                </div>
                                <div class="-download -view">
                                    <a href="#" title="View Detail" class="nq-svg n-fs-14 n-fw-600 n-fc-white-500 n-ah-white-500 n-ff-2 d-flex align-items-center">
                                        <span class="n-ml-10">View Detail</span>
                                        <img src="{{ $CDN_PATH.'assets/images/icon/right-arrow.svg' }}" alt="View Detail" class="svg">
                                    </a>
                                </div>
                            </article>
                        </div>
                    @php } @endphp
                </div>

                <ul class="n-mt-lg-80 n-mt-40" data-aos="fade-up">
                    <ul class="pagination justify-content-center align-content-center">
                        <li class="page-item">
                            <a class="page-link" href="#" title="Previous">
                                <i class="n-icon" data-icon="s-pagination"></i>
                            </a>
                        </li>
                        <li class="page-item"><a class="page-link" href="#" title="1">1</a></li>
                        <li class="page-item active"><a class="page-link" href="#" title="2">2</a></li>
                        <li class="page-item"><a class="page-link" href="#" title="3">3</a></li>
                        <li class="page-item"><a class="page-link" href="#" title="4">4</a></li>
                        <li class="page-item"><a class="page-link" href="#" title="5">5</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#" title="Next">
                                <i class="n-icon" data-icon="s-pagination"></i>
                            </a>
                        </li>
                    </ul>
                </div>  
            </div>   -->
        </div>
    </div>  
</section>

{{--
<?php if(isset($PAGE_CONTENT['response']) && !empty($PAGE_CONTENT['response']) && $PAGE_CONTENT['response'] != '[]'){
    echo $PAGE_CONTENT['response'];
}else{?>
    <section class="page_section n-pt-lg-80 n-pt-50 n-pb-50 n-pb-lg-80">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center" data-aos="fade-up">
                    <div class="nqtitle n-fw-800 n-lh-110 text-uppercase">Coming Soon...</div>
                    <div class="desc n-fs-20 n-fw-500 n-lh-130">We are currently working on a new super awesome page.</div>
                </div>  
            </div>
        </div>  
    </section>
<?php } ?>
--}}

@if(!Request::ajax())
@section('footer_scripts')
    <script src="{{ $CDN_PATH.'assets/libraries/bootstrap-select-master/1.13.14/js/bootstrap-select.min.js' }}" defer></script>
@endsection
@endsection
@endif