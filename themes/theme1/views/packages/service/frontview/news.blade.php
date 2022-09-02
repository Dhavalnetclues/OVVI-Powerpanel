@if(!Request::ajax())
@extends('layouts.app')
@section('content')
@include('layouts.inner_banner')
@endif

@php if(isset($PAGE_CONTENT['response']) && !empty($PAGE_CONTENT['response']) && $PAGE_CONTENT['response'] != '[]'){ @endphp
    <section class="page_section n-pt-lg-80 n-pt-40 n-pb-40 n-pb-lg-80 news-listing">
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
                    @php echo $PAGE_CONTENT['response']; @endphp
                </div>  
            </div>
        </div>  
    </section>
@php } else{ @endphp
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
@php } @endphp


@if(!Request::ajax())
@section('footer_scripts')
    <script src="{{ $CDN_PATH.'assets/libraries/bootstrap-select-master/1.13.14/js/bootstrap-select.min.js' }}" defer></script>
@endsection
@endsection
@endif