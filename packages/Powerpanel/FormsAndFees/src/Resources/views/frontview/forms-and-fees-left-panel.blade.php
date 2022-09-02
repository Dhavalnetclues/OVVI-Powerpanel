<div class="col-xl-3 left-panel">
    <div class="nav-overlay" onclick="closeNav1()"></div>
    <div class="text-right">
        <a href="javascript:void(0)" onclick="openNav1()" id="menu__open1" title="Filter & Menu" class="short-menu">Filter & Menu</a>
    </div>
    <div class="menu1" id="menu1">
        <div class="row n-mr-xl-15" data-aos="fade-up">
            @php $segment1 = Request::segment(1); @endphp
            @if ($segment1 != "ict" && $segment1 != "water" && $segment1 != "fuel" && $segment1 != "energy")
            <div class="col-12">
                <article>
                    <div class="nqtitle-small lp-title text-uppercase n-mb-25">Sort by Category</div>
                    <ul class="nqul s-category d-flex flex-wrap n-fs-14 n-ff-2 n-fw-600 n-fc-black-500" id="categoryFilter">
                        <li><a class="active" href="javascript:void(0)" title="All">All</a></li>
                        <li class="ofreg-tag"><a href="javascript:void(0)" title="OfReg">OfReg</a></li>
                        <li class="ict-tag"><a href="javascript:void(0)" title="ICT">ICT</a></li>
                        <li class="energy-tag"><a href="javascript:void(0)" title="Energy">Energy</a></li>
                        <li class="fuel-tag"><a href="javascript:void(0)" title="Fuel">Fuel</a></li>
                        <li class="water-tag"><a href="javascript:void(0)" title="Water">Water</a></li>
                    </ul>
                </article>
            </div>
            @endif
            @php
            if (($segment1 == "ict" || $segment1 == "water" || $segment1 == "fuel" || $segment1 == "energy") && (!empty(Request::segment(2)))) {
            if($segment1 == "ict") {
            $menu = $ictMenu;
            $title = 'ICT Information';
            }elseif($segment1 == "water"){
            $menu = $waterMenu;
            $title = 'Water Information';
            }elseif($segment1 == "fuel"){
            $menu = $fuelMenu;
            $title = 'Fuel Information';
            }elseif($segment1 == "energy"){
            $menu = $energyMenu;
            $title = 'Energy Information';
            }
            }else{
            $menu = $aboutUsMenu;
            $title = 'About Us Information';
            }
            @endphp
            @if(isset($menu) && !empty($menu))
            <div class="col-12 lpgap">
                <article>
                    <div class="nqtitle-small lp-title text-uppercase n-mb-25">{{ $title }}</div>
                    <div class="s-list">
                        {!! $menu !!}
                    </div>
                </article>
            </div>
            @endif
        </div>
    </div>
</div>