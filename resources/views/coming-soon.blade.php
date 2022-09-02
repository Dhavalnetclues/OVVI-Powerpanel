@php    
$sector = '';
$aboutUsMenuShow = true;
if ((Request::segment(1) == 'ict' || Request::segment(1) == 'energy' || Request::segment(1) == 'fuel' || Request::segment(1) == 'water') && !empty(Request::segment(2))) {
    $sector = Request::segment(1);
    $aboutUsMenuShow = false;
}
@endphp
<section class="inner-page-gap">
    <div class="container">
        <div class="row">
            {{-- @include('cmspage::frontview.left-panel', ['sector' => $sector,'aboutUsMenuShow' => $aboutUsMenuShow]) --}}
            <div class="col-xl-12 text-center">
                <div class="nqtitle n-fw-800 n-lh-110 text-uppercase">Coming Soon...</div>
                <div class="desc n-fs-20 n-fw-500 n-lh-130">We are currently working on a new super awesome page.
                </div>
            </div>
        </div>
    </div>
</section>
