@if(isset($ictMenu) && !empty($ictMenu))
<div class="col-xl-3 left-panel">
    <div class="nav-overlay" onclick="closeNav1()"></div>
    <div class="text-right">
        <a href="javascript:void(0)" onclick="openNav1()" id="menu__open1" title="Filter & Menu" class="short-menu">Filter & Menu</a>
    </div>
    <div class="menu1" id="menu1">
        <div class="row n-mr-xl-15" data-aos="fade-up">
            <div class="col-12">
                <article>
                    <div class="nqtitle-small lp-title text-uppercase n-mb-25">ICT Information</div>
                    <div class="s-list">
                        {!! $ictMenu !!}
                    </div>
                </article>
            </div>
        </div>
    </div>
</div>
@endif