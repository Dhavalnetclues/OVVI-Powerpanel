@php 
    $showYearFilterPagesArr = ['board-of-directors-meetings','industry-statistics','strategic-plan'];
    $showMonthFilterPagesArr = ['board-of-directors-meetings','industry-statistics','strategic-plan'];
    $showCategoryFilterPagesArr = ['board-of-directors-meetings'];

    $segment = Request::segment(1);
    if(Request::segment(1) == 'ict' || Request::segment(1) == 'energy' || Request::segment(1) == 'fuel' || Request::segment(1) == 'water'){
        $segment = Request::segment(2);
    }
@endphp

<div class="col-xl-3 left-panel">
    <div class="nav-overlay" onclick="closeNav1()"></div>
    <div class="text-right">
        <a href="javascript:void(0)" onclick="openNav1()" id="menu__open1" title="Filter & Menu"
            class="short-menu">Filter & Menu</a>
    </div>

    <div class="menu1" id="menu1">
        <div class="row n-mr-xl-15" data-aos="fade-up">

            @if(in_array($segment,$showCategoryFilterPagesArr)) 
                <div class="col-12 lpgap">
                    <article>
                        <div class="nqtitle-small lp-title text-uppercase n-mb-25">Sort by Category</div>
                        <div class="form-group ac-form-group n-mb-0">
                            <select class="selectpicker ac-input" data-width="100%" title="Sort by Month">
                                <option>Annual Report</option>
                                <option>Guidelines</option>
                                <option>Rules</option>
                                <option>Quarter Licence Fee Reports</option>
                            </select>
                        </div>
                    </article>
                </div>
            @endif

            @if(in_array($segment,$showMonthFilterPagesArr))     
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
            @endif

            @if(in_array($segment,$showYearFilterPagesArr))   
                <div class="col-12 lpgap">
                    <article>
                        <div class="nqtitle-small lp-title text-uppercase n-mb-25">Sort by Years</div>
                        <div class="s-years">
                            @php
                                $year = now()->year;
                                $lastYear = $year-20;
                            @endphp
                            <ul class="nqul d-flex flex-wrap n-fs-14 n-ff-2 n-fc-black-500" id="yearFilter">
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
            @endif

            @if (isset($aboutUsMenu) && !empty($aboutUsMenu))
                <div class="col-12 lpgap">
                    <article>
                        <div class="nqtitle-small lp-title text-uppercase n-mb-25">About Us</div>
                        <div class="s-list">
                            {!! $aboutUsMenu !!}
                        </div>
                    </article>
                </div>
            @endif

        </div>
    </div>
</div>
<script type="text/javascript">
    let pageName = "{{ $segment }}"
</script>