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
                    <div class="nqtitle-small lp-title text-uppercase n-mb-25">Consultations</div>
                    <div class="s-list">
                        <ul class="nqul d-flex flex-wrap n-fs-16 n-ff-2 n-fw-600 n-fc-black-500"
                            id="consultationTypeFilter">
                            <li><a class="active" href="javascript:void(0)" title="all">All</a></li>
                            <li><a href="javascript:void(0)" title="consultations">Consultations</a></li>
                            <li><a href="javascript:void(0)" title="determinations">Determinations</a></li>
                            <li><a href="javascript:void(0)" title="completed_consultations">Completed Consultations</a>
                            </li>
                        </ul>
                    </div>
                </article>
            </div>
            <div class="col-12 lpgap">
                <article>
                    <div class="nqtitle-small lp-title text-uppercase n-mb-25">Sort by Category</div>
                    <ul class="nqul s-category d-flex flex-wrap n-fs-14 n-ff-2 n-fw-600 n-fc-black-500"
                        id="categoryFilter">
                        @php
                        $data = array(
                        "all" => "All",
                        "ofreg" => "OfReg",
                        "ict" => "ICT",
                        "energy" =>"Energy",
                        "fuel" =>"Fuel",
                        "water" =>"Water"
                        );
                        @endphp
                        @foreach($data as $sectorval)
                        @php
                        $class = '';
                        if (isset($_GET['sector']) && !empty($_GET['sector'])) {
                        $sector_consultation = strip_tags($_GET['sector']);
                        if($sector_consultation ==  strtolower($sectorval)){
                        $class = 'active';
                        }
                        }
                        else{
                        if(strtolower($sectorval) == 'all'){
                        $class = 'active';
                        }
                        }


                        @endphp
                        
                        @php $colourclass = '';@endphp
                        @if(strtolower($sectorval) == 'ofreg')
                        @php
                        $colourclass = 'ofreg-tag';
                        @endphp
                        @elseif(strtolower($sectorval) == 'ict')
                        @php $colourclass = 'ict-tag'; @endphp
                        @elseif(strtolower($sectorval) == 'water')
                        @php $colourclass = 'water-tag'; @endphp
                        @elseif(strtolower($sectorval) == 'fuel')
                        @php $colourclass = 'fuel-tag'; @endphp
                       @elseif(strtolower($sectorval) == 'energy')
                        @php $colourclass = 'energy-tag'; @endphp
                        @endif
                        <li class="{{$colourclass}}"><a class="{{$class}}" href="javascript:void(0)" title="{{$sectorval}}">{{$sectorval}}</a></li>
                        @endforeach
<!--                        <li><a href="javascript:void(0)" title="OfReg">OfReg</a></li>
                        <li><a href="javascript:void(0)" title="ICT">ICT</a></li>
                        <li><a href="javascript:void(0)" title="Energy">Energy</a></li>
                        <li><a href="javascript:void(0)" title="Fuel">Fuel</a></li>
                        <li><a href="javascript:void(0)" title="Water">Water</a></li>-->
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
                <button class="ac-btn ac-btn-primary btn-block d-xl-none d-block n-mt-15 ac-small"
                    title="Apply Filter">Apply Filter</button>
            </div>
        </div>
    </div>
</div>
