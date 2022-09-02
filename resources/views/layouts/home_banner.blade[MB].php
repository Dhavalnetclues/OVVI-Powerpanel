@if(!empty($bannerData) && count($bannerData)>0)
<section>
    <div class="home-banner-02">
        <div id="home-banner" class="carousel slide"><!-- -- data-ride="carousel"   data-pause="hover" data-wrap="true"  -- -->
            <!-- Wrapper for slides -->
            <div class="carousel-inner h-b_radisu">
                @foreach($bannerData as $key=>$banner)
                <div class="item @if($key==0) active @endif" data-interval="{{ $banner->varRotateTime }}">
                    <div class="h-b_fill" style="background-image:url('{!! App\Helpers\resize_image::resize($banner->fkIntImgId,1920,545) !!}');"></div>
                    <div class="carousel-caption h-b_caption">
                        <div class="h-b_item">
                            <div class="h-b_center">
                                @if($banner->chrDisplayVideo == 'Y')
                                <div class="h-b_video">
                                    <a data-fancybox class="h-b_video_play" href="{{ $banner->varVideoLink }}?autoplay;"></a>
                                </div>
                                @else
                                <div class="container">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="banner_box animated fadeInLeft">
                                                <div class="h-b_title">{{ $banner->varTitle }}</div>
                                                @if(!empty($banner->varShortDescription))
                                                <div class="h-b_sub-title">{!! nl2br($banner->varShortDescription) !!}</div>
                                                @endif
                                                @if(!empty($banner->varLink))
                                                @if($banner->chrDisplayLink == 'Y')
                                                @php $taeget = 'target="_blank"'; @endphp
                                                @else
                                                @php $taeget = ""; @endphp
                                                @endif
                                                <a class="btn ac-border ac-wht" {{ $taeget }} href="{{ $banner->varLink }}" title="Read More">Read More</a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <!-- Indicators -->
            @if(count($bannerData) > 1)
            <ol class="carousel-indicators h-b_indicators">
                @foreach($bannerData as $key=>$banner)
                <li data-target="#home-banner" data-slide-to="{{ $key }}" class="@if($key==0) active @endif"><span></span></li>
                @endforeach
            </ol>
            <!-- Navigation Banner controls -->
            <a class="left h-b_control carousel-control" href="#home-banner" data-slide="prev"></a>
            <a class="right h-b_control carousel-control" href="#home-banner" data-slide="next"></a>
            @endif
        </div>
    </div>
</section>
@else
<section>
    <div class="home-banner-02">
        <div id="home-banner" class="carousel slide">
            <!-- -- data-ride="carousel"   data-pause="hover" data-wrap="true"  -- -->
            <!--<div id="home-banner" class="carousel slide" data-ride="carousel" data-interval="4500" data-pause="hover" data-wrap="true">-->
            <!-- Wrapper for slides -->
            <div class="carousel-inner h-b_radisu">
                <div class="item active">
                    <div class="h-b_fill" style="background: url({{ $CDN_PATH.'assets/images/banner_img1.jpg'}}); background-size: cover;"></div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif
<script>
    var t;

    var start = $('#home-banner').find('.active').attr('data-interval');
    t = setTimeout("$('#home-banner').carousel({interval: 1000});", start - 1000);

    $('#home-banner').on('slid.bs.carousel', function () {
        clearTimeout(t);
        var duration = $(this).find('.active').attr('data-interval');

        $('#home-banner').carousel('pause');
        t = setTimeout("$('#home-banner').carousel();", duration - 1000);
    })

    $('.carousel-control.right').on('click', function () {
        clearTimeout(t);
    });

    $('.carousel-control.left').on('click', function () {
        clearTimeout(t);
    });
</script>