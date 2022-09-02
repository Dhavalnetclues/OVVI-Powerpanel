<div class="col-xl-3 left-panel">
    <div class="nav-overlay" onclick="closeNav1()"></div>
    <div class="text-right">
        <a href="javascript:void(0)" onclick="openNav1()" id="menu__open1" title="Filter & Menu" class="short-menu">Filter & Menu</a>
    </div>
    @if(isset($aboutUsMenu) && !empty($aboutUsMenu))  
        <div class="menu1" id="menu1">
            <div class="row n-mr-xl-15" data-aos="fade-up">
                <div class="col-12">
                    <article>
                        <div class="nqtitle-small lp-title text-uppercase n-mb-25">About Us</div>
                        <div class="s-list">
                            {!! $aboutUsMenu !!}

                            {{-- <ul class="nqul d-flex flex-wrap n-fs-16 n-ff-2 n-fw-600 n-fc-black-500">
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
                            </ul> --}}
                            
                        </div>
                    </article>
                </div>
            </div>
        </div>
    @endif
</div>