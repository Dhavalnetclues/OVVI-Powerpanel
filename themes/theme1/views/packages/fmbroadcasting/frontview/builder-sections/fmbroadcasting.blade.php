@php
$fmbroadcastingurl = '';
@endphp

@if(isset($data['fmbroadcasting']) && !empty($data['fmbroadcasting']) && count($data['fmbroadcasting']) > 0)
@php 
$cols = 'col-md-4 col-sm-4 col-xs-12';
$grid = '3';
if($data['cols'] == 'grid_2_col'){
$cols = 'col-xl-6';
$grid = '2';
}elseif ($data['cols'] == 'grid_3_col') {
$cols = 'col-xl-4';
$grid = '3';
}elseif ($data['cols'] == 'grid_4_col') {
$cols = 'col-xl-3';
$grid = '4';
}

if(isset($data['class'])){
$class = $data['class'];
}
if(isset($data['paginatehrml']) && $data['paginatehrml'] == true){
$pcol = $cols;
}else{
$pcol = 'item';
}
@endphp

@if(isset($data['desc']) && $data['desc'] != '')
<div class="row">
    <div class="col-12 cms n-mb-30" data-aos="fade-up">
        <p>{!! $data['desc'] !!}</p>
    </div>
</div>
@endif

<!--<div class="row {{ $class }}" data-grid="{{ $grid }}">-->

<div class="row n-mt-25 justify-content-center">
    @foreach($data['fmbroadcasting'] as $key =>  $fmbroadcasting)
    
    @php
    if(isset(App\Helpers\MyLibrary::getFront_Uri('fmbroadcasting')['uri'])){
    $moduelFrontPageUrl = App\Helpers\MyLibrary::getFront_Uri('fmbroadcasting')['uri'];
    $moduleFrontWithCatUrl = ($fmbroadcasting->varAlias != false ) ? $moduelFrontPageUrl . '/' . $fmbroadcasting->varAlias : $moduelFrontPageUrl;
    
    $recordLinkUrl = $moduleFrontWithCatUrl.'/'.$fmbroadcasting->alias->varAlias;
    } else {
    $recordLinkUrl = '';
    }
    @endphp
    

    @if(isset($fmbroadcasting->fkIntImgId))
    @php                          
    $itemImg = App\Helpers\resize_image::resize($fmbroadcasting->fkIntImgId);
    @endphp
    @else 
    @php
    $itemImg =  $itemImg = $CDN_PATH.'assets/images/directors.png';
    @endphp
    @endif

    @if(isset($fmbroadcasting->varShortDescription))
    @php
    $description = $fmbroadcasting->varShortDescription;
    @endphp

    @endif

    <div class="d-flex col-lg-3 col-md-4 col-6 n-gapp-lg-5 n-gapm-lg-4 n-gapm-md-3" data-aos="zoom-in" data-aos-delay="{{$key}}00">
        <article class="-items n-bs-1 n-bgc-white-500 w-100">
            <div class="thumbnail-container ac-webp" data-thumb="100%">
                <div class="thumbnail">
                    <img src="{{$itemImg}}">
                </div>
                <div class="-freq" data-aos="fade-right" data-aos-delay="{{$key}}00">{{$fmbroadcasting->txtFrequency}}</div>
                <div class="-play"><a href="{{$fmbroadcasting->varLink}}" title="Play" rel="nofollow" target="_blank" class="n-ti-05"><i class="n-icon" data-icon="s-play-circle"></i></a></div>
            </div>
            <div class="n-pa-20">
                <div class="-title n-ti-05 n-fs-18 n-fs-22-sm n-fw-500 n-fc-dark-500 n-lh-110">{{$fmbroadcasting->varTitle}}</div>
                <div class="n-mt-25 n-fs-16 n-fc-dark-500 n-lh-120">{!! $description !!}</div>
            </div>
        </article>
    </div>

    @endforeach

</div>

@if(Request::segment(1) != '' && isset($data['paginatehrml']) && $data['paginatehrml'] == true)
@if($data['fmbroadcasting']->total() > $data['fmbroadcasting']->perPage())
<div class="n-mt-lg-80 n-mt-40" data-aos="fade-up" id="paginationSection">
    @include('partial.pagination', ['paginator' => $data['fmbroadcasting']->links()['paginator'], 'elements' => $data['fmbroadcasting']->links()['elements']['0']])
</div>
@endif
@endif
@endif