@if(!Request::ajax())
@extends('layouts.app')
@section('content')
@include('layouts.inner_banner')
@endif
@if(!Request::ajax())
<section>
    <div class="inner-page-container cms album_page gallery_page">
        <div class="container">

            <div class="row">
                <div class="col-md-12 col-md-12 col-xs-12 animated fadeInUp">
                    <div class="right_content">
                       <?php if(isset($PAGE_CONTENT['response']) && !empty($PAGE_CONTENT['response']) && $PAGE_CONTENT['response'] != '[]'){
                            echo $PAGE_CONTENT['response'];
                            } ?>  
                        <!-- News Section S -->
                        <div class="row row_gallery">
                            <div class="section_node">
                                @endif
                                @if(!empty($videoGalleryArr) && count($videoGalleryArr)>0)
                                @foreach($videoGalleryArr as $key=>$videoGalleryArr)
                                <div class="col-md-4 col-sm-4 col-xs-6 col-xss-12 animated fadeInUp">
                                    <div class="album-box">
                                        <div class="image imghvr-effect">
                                            <div class="thumbnail-container">
                                                <div class="thumbnail">
                                                    <a class="imghvr-img" title="Click Here To Zoom" href="{{ $videoGalleryArr->txtLink }}" data-fancybox="">
                                                        <img src="{!! App\Helpers\resize_image::resize($videoGalleryArr->fkIntImgId,600,400) !!}" alt="{{ $videoGalleryArr->varTitle }}" >
                                                        <div class="overlay_link"><i class="fa fa-play"></i></div>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="photo-title">
                                            <h3><a title="{{ $videoGalleryArr->varTitle }}" href="#">{{ $videoGalleryArr->varTitle }}</a></h3>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                @endif
                                @if(!Request::ajax())
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- Main Section E -->
        </div>
    </div>
</section>
@endif

@if(!Request::ajax())
@section('footer_scripts')
@endsection
@endsection
@endif