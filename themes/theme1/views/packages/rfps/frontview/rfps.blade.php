@if(!Request::ajax())
@extends('layouts.app')
@section('content')
@include('layouts.inner_banner')
@endif

<section class="page_section n-pt-lg-80 n-pt-40 n-pb-40 n-pb-lg-80 rfps-listing">
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
                        </div>
                        <div class="d-xl-none d-block">
                            <button type="button" class="ac-btn ac-btn-primary btn-block n-mt-25" title="Go">Go</button>
                        </div>
                    </div>
                </div>
        	</div>

            <div class="col-xl-9">
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
                                    <a data-toggle="collapse" href="#rfpsDownload@php echo $x; @endphp" role="button" aria-expanded="false" aria-controls="rfpsDownload@php echo $x; @endphp" title="Download" class="nq-svg n-fs-14 n-fw-600 n-fc-white-500 n-ah-white-500 n-ff-2 d-flex align-items-center">
                                        <img src="{{ $CDN_PATH.'assets/images/icon/pdf.svg' }}" alt="Download" class="svg">
                                        <span class="n-ml-10">Download</span>
                                    </a>
                                </div>
                                <div class="-pdf collapse" id="rfpsDownload@php echo $x; @endphp">
                                    <a class="-close" data-toggle="collapse" href="#rfpsDownload@php echo $x; @endphp" role="button" aria-expanded="false" aria-controls="rfpsDownload@php echo $x; @endphp"><i class="fa fa-close"></i></a>
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
            </div>  
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