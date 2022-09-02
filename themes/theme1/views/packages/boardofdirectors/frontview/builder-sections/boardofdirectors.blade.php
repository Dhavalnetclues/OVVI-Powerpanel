@php
$boardofdirectorsurl = '';
@endphp



@if(isset($data['boardofdirectors']) && !empty($data['boardofdirectors']) && count($data['boardofdirectors']) > 0)
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

<div class="row {{ $class }}" data-grid="{{ $grid }}">
    @foreach($data['boardofdirectors'] as $boardofdirectors)
    @php
    if(isset(App\Helpers\MyLibrary::getFront_Uri('boardofdirectors')['uri'])){
    $moduelFrontPageUrl = App\Helpers\MyLibrary::getFront_Uri('boardofdirectors')['uri'];
    $moduleFrontWithCatUrl = ($boardofdirectors->varAlias != false ) ? $moduelFrontPageUrl . '/' . $boardofdirectors->varAlias : $moduelFrontPageUrl;

    $recordLinkUrl = $moduleFrontWithCatUrl.'/'.$boardofdirectors->alias->varAlias;
    } else {
    $recordLinkUrl = '';
    }
    @endphp

    @if(isset($boardofdirectors->fkIntImgId))
    @php                          
    $itemImg = App\Helpers\resize_image::resize($boardofdirectors->fkIntImgId);
    @endphp
    @else 
    @php
    $itemImg = $CDN_PATH.'assets/images/directors.png';
    @endphp
    @endif

    @if(isset($boardofdirectors->varShortDescription))
    @php
    $description = $boardofdirectors->varShortDescription;
    @endphp

    @endif


    <div class="col-xl-12 col-sm-6 -gap -items">
        <div class="row" data-aos="fade-up">  

            <div class="col-xl-5">
                <div class="directors-img">
                    <div class="thumbnail-container">
                        <div class="thumbnail">
                            <a href="{{$recordLinkUrl}}" title="{{ $boardofdirectors->varTitle }}">
                                <img src="{{ $itemImg}}" alt="{{ $boardofdirectors->varTitle }}">
                                <div class="overlay">
                                    <i class="plus"></i>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-7 n-mv-15 d-flex align-items-center -bg">
                <div>
                    <h2 class="-title n-fs-26 n-ff-2 n-fw-800 n-lh-130 n-fc-black-500"><a href="{{$recordLinkUrl}}" title="{{ $boardofdirectors->varTitle }}">{{ $boardofdirectors->varTitle }}</a></h2>
                    <div class="n-mt-15 n-fs-18 n-fw-600 text-uppercase n-fc-black-500">{{ $boardofdirectors->varTagLine}}</div>
                    <div class="cms n-mt-30 d-none d-xl-block n-pr-30 n-pb-30">
                        @if(isset($boardofdirectors->varDepartment) && !empty($boardofdirectors->varDepartment))
                        <h3>{{ $boardofdirectors->varDepartment}}</h3>
                        @endif
                        <p>{!! $description !!}</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
    @endforeach
</div>

@if(Request::segment(1) != '' && isset($data['paginatehrml']) && $data['paginatehrml'] == true)

@if($data['boardofdirectors']->total() > $data['boardofdirectors']->perPage())

<div class="n-mt-lg-80 n-mt-40" data-aos="fade-up" id="paginationSection">
    @include('partial.pagination', ['paginator' => $data['boardofdirectors']->links()['paginator'], 'elements' => $data['boardofdirectors']->links()['elements']['0']])
</div>
@endif
@endif
@endif