@php
$breadcrumb['module'] = isset($breadcrumb['module'])?$breadcrumb['module']:ucwords(request()->segment(1));
$breadcrumb['inner_title'] = isset($breadcrumb['inner_title'])?$breadcrumb['inner_title']:$breadcrumb['module'];
$CDN_PATH = Config::get('Constant.CDN_PATH');
@endphp
<section class="inner-page-banner">
    <div id="inner-banner" class="carousel slide" data-ride="carousel" data-interval="4500" data-pause="hover" data-wrap="true">
        <div class="carousel-inner">
            <div class="carousel-item active">
                @if(!empty($inner_banner_data[0]) && isset($inner_banner_data[0]))
                    {{-- <img src="{!! App\Helpers\resize_image::resize($inner_banner_data[0]->fkIntInnerImgId,1920,853) !!}" alt="{{ $inner_banner_data[0]->varTitle }}" title="{{ $inner_banner_data[0]->varTitle }}" /> --}}
                    <div class="fill" style="background-image:url('{!! App\Helpers\resize_image::resize($inner_banner_data[0]->fkIntInnerImgId,1920,853) !!}')"></div>
                @else
                    {{-- <img src="{{ $CDN_PATH.'assets/images/Default-Banner-D.jpg' }}" alt="Title" title="Inner Banner" /> --}}
                    <div class="fill" style="background-image:url('{{ $CDN_PATH.'assets/images/Default-Banner-D.jpg' }}')"></div>
                @endif
            </div>
        </div>
    </div>
    <div class="caption">
        <div class="nq-table">
            <div class="nq-center">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12">

                            @if(Request::segment(1) == "sitemap")
                                <h1 class="title"> Sitemap </h1>
                            @elseif(Request::segment(1) == "search")
                                <h1 class="title"> Search </h1>
                            @else
                                <h1 class="title">{{ isset($detailPageTitle) ? $detailPageTitle : '' }}</h1>
                            @endif

                            @if(isset($breadcrumb) && count($breadcrumb) > 0)
                            <ul class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{url('/')}}" title="Home">Home</a></li>
                                @if(!empty($breadcrumb['url']))
                                    <li class="breadcrumb-item"><a href="{{ url($breadcrumb['url']) }}" title="{{ $breadcrumb['module'] }}"> {{ $breadcrumb['module'] }}</a></li>
                                @endif

                                @if(!empty($breadcrumb['inner_title']))
                                    <li class="breadcrumb-item active">{{ $breadcrumb['inner_title'] }}</li>
                                @else
                                    <li class="breadcrumb-item active">{{ $breadcrumb['title'] }}</li>
                                @endif
                            </ul>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>