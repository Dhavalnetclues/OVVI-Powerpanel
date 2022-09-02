@if(!Request::ajax())
    @extends('layouts.app')
    @section('content')
    @include('layouts.inner_banner')
@endif

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
                        <div class="col-12 lpgap">
                            <article>
                                <div class="nqtitle-small lp-title text-uppercase n-mb-25">Sort by Category</div>
                                <div class="form-group ac-form-group n-mb-0">
                                    <select class="selectpicker ac-input" data-width="100%" title="Sort by Category">
                                        <option >ICT Decision 2016-5</option>
                                        <option>Background Documents</option>
                                        <option>ICT Decision 2016-2</option>
                                        <option>ICT Decision 2016-1</option>
                                        <option>ICT Decision 2014-1</option>
                                    </select>
                                </div>
                            </article>
                        </div>

                        <div class="col-12 lpgap">
                            <article>
                                <div class="nqtitle-small lp-title text-uppercase n-mb-25">Sort by Month</div>
                                <div class="form-group ac-form-group n-mb-0">
                                    <select class="selectpicker ac-input" data-width="100%" title="Sort by Month" id="monthFilter">
                                        <option value="01">January</option>
                                        <option value="02">February</option>
                                        <option value="03">March</option>
                                        <option value="04">April</option>
                                        <option value="05">May</option>
                                        <option value="06">June</option>
                                        <option value="07">July</option>
                                        <option value="08">August</option>
                                        <option value="09">September</option>
                                        <option value="10">October</option>
                                        <option value="11">November</option>
                                        <option value="12">December</option>
                                    </select>
                                </div>
                            </article>
                        </div>

                        <div class="col-12 lpgap">
                            <article>
                                <div class="nqtitle-small lp-title text-uppercase n-mb-25">Sort by Years</div>                  
                                <div class="s-years">
                                    <ul class="nqul d-flex flex-wrap n-fs-14 n-ff-2 n-fc-black-500" id="yearFilter">
                                        @php
                                            $year = now()->year;
                                            $lastYear = $year-20;
                                        @endphp
                                        @for ($i = $year; $i >= $lastYear; $i--)
                                            <li>
                                                <div class="form-group ac-form-group n-mb-0">
                                                    <div class="ac-checkbox-list n-pt-0">
                                                        <label class="ac-checkbox">
                                                            <input type="checkbox" value="{{$i}}"> {{$i}}<span></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </li>
                                        @endfor
                                    </ul>
                                </div>
                            </article>
                        </div>

                        <div class="col-12 lpgap">
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
                    <p>The following Interconnection and Infrastructure Sharing Agreements have been filed with the Authority, and are published here in accordance with section 66 of the ICT Law</p>
                </div>

                <div class="row n-mt-25">
                    @php for ($x = 1; $x <= 12; $x++) { @endphp
                        <div class="col-lg-6 d-flex n-gap-2 n-gapp-lg-3 n-gapm-lg-2" data-aos="zoom-in">
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

                <div class="n-mt-lg-80 n-mt-40" data-aos="fade-up">
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

<script src="{{ $CDN_PATH.'assets/js/packages/interconnections/interconnections.js' }}" type="text/javascript"></script>
<script type="text/javascript">
    let textDescription = "{{json_encode($txtDescription)}}"
</script>

@if(!Request::ajax())
@section('footer_scripts')
    <script src="{{ $CDN_PATH.'assets/libraries/bootstrap-select-master/1.13.14/js/bootstrap-select.min.js' }}" defer></script>
@endsection
@endsection
@endif