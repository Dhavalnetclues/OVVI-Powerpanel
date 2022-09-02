@php
$videogalleryurl = '';
@endphp
@if(isset($data['videogallery']) && !empty($data['videogallery']) && count($data['videogallery']) > 0)
@php 
$cols = 'col-md-4 col-sm-4 col-xs-12';
$grid = '3';
if($data['cols'] == 'grid_2_col'){
$cols = 'col-md-6 col-sm-6 col-xs-12';
$grid = '2';
}elseif ($data['cols'] == 'grid_3_col') {
$cols = 'col-md-4 col-sm-4 col-xs-12';
$grid = '3';
}elseif ($data['cols'] == 'grid_4_col') {
$cols = 'col-md-3 col-sm-6 col-xs-12';
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
@if(Request::segment(1) == '')
<section class="videogallery_sec owl-section {{ $class }}" data-grid="{{ $grid }}">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-xs-12 animated fadeInUp">
                <div class="same_title text-center">
                    @if(isset($data['title']) && $data['title'] != '')
                    <h2 class="title_div">{{ $data['title'] }}</h2>
                    @endif
                    @if(isset($data['desc']) && $data['desc'] != '')
                    <p>{!! $data['desc'] !!}</p>
                    @endif
                </div>
            </div>
        </div>  
        <div class="videogallery_slide">
            <div class="row">
                @if(isset($data['paginatehrml']) && $data['paginatehrml'] != true)
                <div class="col-12">
                    <div class="owl-carousel owl-theme owl-nav-absolute">
                        @endif
                        @foreach($data['videogallery'] as $videogallery)

                        @if(isset($videogallery->fkIntImgId))
                        @php                          
                        $itemImg = App\Helpers\resize_image::resize($videogallery->fkIntImgId);
                        @endphp
                        @else 
                        @php
                        $itemImg = $CDN_PATH.'assets/images/videogallery-img1.jpg';
                        @endphp
                        @endif

                        @if($data['cols'] == 'list')
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="videogallery_post listing clearfix">
                                @if(isset($videogallery->fkIntImgId) && $videogallery->fkIntImgId != '')
                                <div class="videogallery_img">
                                    <div class="image imghvr-effect">
                                        <div class="thumbnail-container">
                                            <div class="thumbnail">
                                                <a class="imghvr-img" title="Click Here To Zoom" href="{{ $videogallery->txtLink }}" data-fancybox="">
                                                    <img src="{{ $itemImg }}" alt="{{ $videogallery->varTitle }}" >
                                                    <div class="overlay_link"><i class="fa fa-play"></i></div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                <div class="info">
                                    @if(isset($videogallery->dtDateTime) && $videogallery->dtDateTime != '')
                                    <div class="date">{{ date('l d M, Y',strtotime($videogallery->dtDateTime)) }}</div>
                                    @endif
                                    <h5 class="sub_title">{{ $videogallery->varTitle }}</h5>

                                </div>
                            </div>
                        </div>
                        @else
                        <div class="{{ $pcol }}">
                            <div class="videogallery_post">
                                @if(isset($videogallery->fkIntImgId) && $videogallery->fkIntImgId != '')
                                <div class="videogallery_img">
                                    <div class="image imghvr-effect">
                                        <div class="thumbnail-container">
                                            <div class="thumbnail">
                                                <a class="imghvr-img" title="Click Here To Zoom" href="{{ $videogallery->txtLink }}" data-fancybox="">
                                                    <img src="{{ $itemImg }}" alt="{{ $videogallery->varTitle }}" >
                                                    <div class="overlay_link"><i class="fa fa-play"></i></div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                <div class="info">
                                    @if(isset($videogallery->dtDateTime) && $videogallery->dtDateTime != '')
                                    <div class="date">{{ date('l d M, Y',strtotime($videogallery->dtDateTime)) }}</div>
                                    @endif
                                    <h5 class="sub_title">{{ $videogallery->varTitle }}</h5>

                                </div>
                            </div>
                        </div>
                        @endif
                        @endforeach
                        @if(isset($data['paginatehrml']) && $data['paginatehrml'] != true)
                    </div>
                </div>
                @endif
            </div>
            <div class="row">
                <div class="col-sm-12 col-xs-12 animated fadeInUp text-center">               
                    <a class="btn ac-border btn-more" href="{!! $videogalleryurl !!}" title="More All VideoGallery">More All VideoGallery</a>               
                </div>
            </div>
        </div>
    </div>
</section>
@else
<div class="row">
    <div class="col-sm-12 col-xs-12 animated fadeInUp">
        <div class="same_title text-center">
            @if(isset($data['title']) && $data['title'] != '')
            <h2 class="title_div">{{ $data['title'] }}</h2>
            @endif
            @if(isset($data['desc']) && $data['desc'] != '')
            <p>{!! $data['desc'] !!}</p>
            @endif
        </div>
    </div>
</div>  
<div class="videogallery_slide owl-section {{ $class }}" data-grid="{{ $grid }}">
    <div class="row">
        @if(isset($data['paginatehrml']) && $data['paginatehrml'] != true)
        <div class="col-12">
            <div class="owl-carousel owl-theme owl-nav-absolute">
                @endif
                @foreach($data['videogallery'] as $videogallery)

                @if(isset($videogallery->fkIntImgId))
                @php                          
                $itemImg = App\Helpers\resize_image::resize($videogallery->fkIntImgId);
                @endphp
                @else 
                @php
                $itemImg = $CDN_PATH.'assets/images/videogallery-img1.jpg';
                @endphp
                @endif

                @if($data['cols'] == 'list')
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="videogallery_post listing clearfix">
                        @if(isset($videogallery->fkIntImgId) && $videogallery->fkIntImgId != '')
                        <div class="videogallery_img">
                            <div class="image imghvr-effect">
                                <div class="thumbnail-container">
                                    <div class="thumbnail">
                                        <a class="imghvr-img" title="Click Here To Zoom" href="{{ $videogallery->txtLink }}" data-fancybox="">
                                            <img src="{{ $itemImg }}" alt="{{ $videogallery->varTitle }}" >
                                            <div class="overlay_link"><i class="fa fa-play"></i></div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="info">
                            @if(isset($videogallery->dtDateTime) && $videogallery->dtDateTime != '')
                            <div class="date">{{ date('l d M, Y',strtotime($videogallery->dtDateTime)) }}</div>
                            @endif
                            <h5 class="sub_title">{{ $videogallery->varTitle }}</h5>
                        </div>
                    </div>
                </div>
                @else
                <div class="{{ $pcol }}">
                    <div class="videogallery_post">
                        @if(isset($videogallery->fkIntImgId) && $videogallery->fkIntImgId != '')
                        <div class="videogallery_img">
                            <div class="image imghvr-effect">
                                <div class="thumbnail-container">
                                    <div class="thumbnail">
                                        <a class="imghvr-img" title="Click Here To Zoom" href="{{ $videogallery->txtLink }}" data-fancybox="">
                                            <img src="{{ $itemImg }}" alt="{{ $videogallery->varTitle }}" >
                                            <div class="overlay_link"><i class="fa fa-play"></i></div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="info">
                            @if(isset($videogallery->dtDateTime) && $videogallery->dtDateTime != '')
                            <div class="date">{{ date('l d M, Y',strtotime($videogallery->dtDateTime)) }}</div>
                            @endif
                            <h5 class="sub_title">{{ $videogallery->varTitle }}</h5>
                        </div>
                    </div>
                </div>
                @endif
                @endforeach
                @if(isset($data['paginatehrml']) && $data['paginatehrml'] != true)
            </div>
        </div>
        @endif
    </div>
    @if(Request::segment(1) != '' && isset($data['paginatehrml']) && $data['paginatehrml'] == true)
    @if($data['videogallery']->total() > $data['videogallery']->perPage())
    <div class="row">
        <div class="col-sm-12 n-mt-30" data-aos="fade-up">
            {{ $data['videogallery']->links() }}
        </div>
    </div>
    @endif
    @endif
</div>
@endif
@endif