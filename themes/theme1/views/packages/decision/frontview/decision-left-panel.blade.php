
<div class="col-xl-3 left-panel">
    <div class="nav-overlay" onclick="closeNav1()"></div>
    <div class="text-right">
        <a href="javascript:void(0)" onclick="openNav1()" id="menu__open1" title="Filter & Menu"
            class="short-menu">Filter & Menu</a>
    </div>
    <div class="menu1" id="menu1">
        <div class="row n-mr-xl-15" data-aos="fade-up">
            @php
            $pubcategory =json_decode($content);
            @endphp
            @if(isset($pubcategory))
         
            @foreach($pubcategory as $pub)
            @if($pub->val->decisioncat ==! ' ')
           
            <div class="col-12 lpgap">
           
                <article>
                    <div class="nqtitle-small lp-title text-uppercase n-mb-25">Sort by Category</div>
                    <div class="form-group ac-form-group n-mb-0">
                          @if(isset($decisionCategories))
                        {!! $decisionCategories !!}
                        @endif
                    </div>
                </article>
            </div>
            @endif
            @endforeach
            @else
             <div class="col-12 lpgap">
           
                <article >
                    <div class="nqtitle-small lp-title text-uppercase n-mb-25">Sort by Category</div>
                    <div class="form-group ac-form-group n-mb-0">
                          @if(isset($decisionCategories))
                        {!! $decisionCategories !!}
                        @endif
                    </div>
                </article>
            </div>
            @endif
          
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
            @php
              $segment1 = Request::segment(1);
           
            if (($segment1 == "ict" || $segment1 == "water" || $segment1 == "fuel" || $segment1 == "energy") && (!empty($segment1))) {
            if($segment1 == "ict"){
            $menu = $ictMenu;
            $title = 'ICT Information';
            }
            elseif($segment1 == "water"){
            $menu = $waterMenu;
            $title = 'Water Information';
            }
            elseif($segment1 == "fuel"){
            $menu = $fuelMenu;
            $title = 'Fuel Information';
            }
            elseif($segment1 == "energy"){
            $menu = $energyMenu;
            $title = 'Energy Information';
            }
           
            }
             else{
            $menu = $aboutUsMenu;
            $title = 'About Us Information';
            }
            @endphp
            @if(isset($menu) && !empty($menu))
                <div class="col-12 lpgap">
                    <article>
                        <div class="nqtitle-small lp-title text-uppercase n-mb-25">{{$title}}</div>
                        <div class="s-list">
                            {!! $menu !!}
                        </div>
                    </article>
                </div>
            @endif    
        </div>
    </div>
</div>
