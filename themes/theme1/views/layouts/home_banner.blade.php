@if(!empty($bannerData) && count($bannerData)>0)
    <section class="home-banner" data-aos="fade-up">
        <div class="slider slider-for">
            @foreach($bannerData as $key => $banner)
                <div class="item">
                    <div class="thumbnail-container">
                        <div class="thumbnail">
                            <img src="{!! App\Helpers\resize_image::resize($banner->fkIntImgId,1920,853) !!}" alt="{{ $banner->varTitle }}" title="{{ $banner->varTitle }}" />
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="-banner-item d-none d-md-block" data-aos="fade-up">
            <div class="container">
                <div class="container-w">
                    <div class="row">
                        <div class="col-12">
                            <div class="slider slider-nav">
                                @foreach($bannerData as $key => $banner)
                                    <div class="item">
                                        <div class="-items d-flex align-items-center">
                                            <div class="-img n-mr-10">
                                                <img src="{!! App\Helpers\resize_image::resize($banner->fkIntIconId) !!}" alt="{{ $banner->varTitle }}" title="{{ $banner->varTitle }}" />
                                            </div>
                                            <div class="-content">
                                                <div class="nqtitle n-fw-800 n-fc-white-500 text-uppercase">{{ $banner->varTitle }}</div>
                                                @if(isset($banner->varShortDescription) && !empty($banner->varShortDescription))
                                                    <div class="text-uppercase n-fs-14 n-fw-400 n-fc-white-500">{{ $banner->varShortDescription }}</div>
                                                @endif    
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@else
<section class="home-banner" data-aos="fade-up">
    <div class="slider slider-for">
            <div class="item">
                <div class="thumbnail-container">
                    <div class="thumbnail">
                        <img src="{{ $CDN_PATH.'assets/images/banner/ICT-c.jpg' }}" alt="ICT" title="ICT" />
                    </div>
                </div>
            </div>
        </div>
</section>
@endif