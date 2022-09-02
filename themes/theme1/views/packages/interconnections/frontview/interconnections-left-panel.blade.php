<div class="col-xl-3 left-panel">
    <div class="nav-overlay" onclick="closeNav1()"></div>
    <div class="text-right">
        <a href="javascript:void(0)" onclick="openNav1()" id="menu__open1" title="Filter & Menu"
            class="short-menu">Filter & Menu</a>
    </div>
    <div class="menu1" id="menu1">
        <div class="row n-mr-xl-15" data-aos="fade-up">
            <div class="col-12 lpgap">
                <article>
                    <div class="nqtitle-small lp-title text-uppercase n-mb-25">Sort by Category</div>
                    <div class="form-group ac-form-group n-mb-0">
                        <select class="selectpicker ac-input" data-width="100%" title="Sort by Category"
                            id="categoryFilter">
                            <option value="all">All</option>
                            @if (count($interconnectionsCategory) > 0)
                                @foreach ($interconnectionsCategory as $category)
                                    <option value="{{ $category->id }}">{{ $category->varTitle }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </article>
            </div>

            <div class="col-12 lpgap">
                <article>
                    <div class="nqtitle-small lp-title text-uppercase n-mb-25">Sort by Month</div>
                    <div class="form-group ac-form-group n-mb-0">
                        <select class="selectpicker ac-input" data-width="100%" title="Sort by Month"
                            id="monthFilter">
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
                                $lastYear = $year - 20;
                            @endphp
                            @for ($i = $year; $i >= $lastYear; $i--)
                                <li>
                                    <div class="form-group ac-form-group n-mb-0">
                                        <div class="ac-checkbox-list n-pt-0">
                                            <label class="ac-checkbox">
                                                <input type="checkbox" value="{{ $i }}">
                                                {{ $i }}<span></span>
                                            </label>
                                        </div>
                                    </div>
                                </li>
                            @endfor
                        </ul>
                    </div>
                </article>
            </div>

            @if(isset($ictMenu) && !empty($ictMenu))
                <div class="col-12 lpgap">
                    <article>
                        <div class="nqtitle-small lp-title text-uppercase n-mb-25">ICT Information</div>
                        <div class="s-list">
                            {!! $ictMenu !!}
                        </div>
                    </article>
                </div>
            @endif
            
        </div>
    </div>
</div>
