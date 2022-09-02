@php
$abouturl = '';
@endphp
@if($data['alignment'] == 'home-lft-txt')
<section class="about_sec">
    <div class="container">
        <div class="row">
            <div class="col-sm-5 col-xs-12 cms about-left animated fadeInLeft">
                <div class="about_image">
                    <img src="{{  App\Helpers\resize_image::resize($data['image']) }}" alt="{{ $data['title'] }}">
                </div>
            </div>
            <div class="col-sm-7 col-xs-12 cms about-left animated fadeInRight">
                <div class="same_title">
                    <h1 class="title_div">{{ $data['title'] }}</h1>
                </div>
                <div class="info">
                    {!! $data['content'] !!}
                </div>
                <a class="btn ac-border " href="{!! $abouturl !!}" title="Read More">Read More</a>
            </div>
        </div>
    </div>
</section>
@elseif($data['alignment'] == 'home-rt-txt')
<section class="about_sec">
    <div class="container">
        <div class="row">
            <div class="col-sm-7 col-xs-12 cms about-left animated fadeInLeft">
                <div class='visible-xs'>
                    <div class="about_image">
                        <img src="{{  App\Helpers\resize_image::resize($data['image']) }}" alt="{{ $data['title'] }}">
                    </div>
                </div>
                <div class="same_title">
                    <h1 class="title_div">{{ $data['title'] }}</h1>
                </div>
                <div class="info">
                    {!! $data['content'] !!}
                </div>
                <a class="btn ac-border " href="{!! $abouturl !!}" title="Read More">Read More</a>
            </div>
            <div class="col-sm-5 col-xs-12 cms about-left animated fadeInRight hidden-xs">
                <div class="about_image">
                    <img src="{{  App\Helpers\resize_image::resize($data['image']) }}" alt="{{ $data['title'] }}">
                </div>
            </div>
        </div>
    </div>
</section>
@elseif($data['alignment'] == 'home-top-txt')
<section class="about_sec">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-xs-12 cms about-left animated fadeInUp">
                <div class='about_full'>
                    <div class="about_image">
                        <img src="{{  App\Helpers\resize_image::resize($data['image']) }}" alt="{{ $data['title'] }}">
                    </div>
                    <div class="same_title">
                        <h1 class="title_div">{{ $data['title'] }}</h1>
                    </div>
                    <div class="info">
                        {!! $data['content'] !!}
                    </div>
                    <a class="btn ac-border " href="{!! $abouturl !!}" title="Read More">Read More</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endif