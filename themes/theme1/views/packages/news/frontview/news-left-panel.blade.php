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
                    <div class="nqtitle-small lp-title text-uppercase n-mb-25">Sort by Name</div>
                    <div class="form-group ac-form-group n-mb-0">
                        <select class="selectpicker ac-input" data-width="100%" title="Sort by Name"
                            id="sortFilter">
                            <option value="sortByNew">Sort by New</option>
                            <option value="sortByAsc">Sort by A to Z</option>
                            <option value="sortByDesc">Sort by Z to A</option>
                        </select>
                    </div>
                </article>
            </div>
            <div class="col-12 lpgap">
                <article>
                    <div class="nqtitle-small lp-title text-uppercase n-mb-25">Sort by Sector</div>
                    <ul class="nqul s-category d-flex flex-wrap n-fs-14 n-ff-2 n-fw-600 n-fc-black-500"
                        id="categoryFilter">
                        <li><a class="active" href="javascript:void(0)" title="All">All</a></li>
                        <li><a href="javascript:void(0)" title="OfReg">OfReg</a></li>
                        <li><a href="javascript:void(0)" title="ICT">ICT</a></li>
                        <li><a href="javascript:void(0)" title="Energy">Energy</a></li>
                        <li><a href="javascript:void(0)" title="Fuel">Fuel</a></li>
                        <li><a href="javascript:void(0)" title="Water">Water</a></li>
                    </ul>
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
        </div>
    </div>
</div>
