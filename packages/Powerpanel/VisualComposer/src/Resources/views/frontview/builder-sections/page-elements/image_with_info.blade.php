@if(Request::segment(1) != '')

    @if($data['alignment'] == 'lft-txt')

        <div class="left_img_cms">
            <img src="{{  App\Helpers\resize_image::resize($data['image']) }}" alt="{{ $data['title'] }}">
        </div>
        @if($data['title'] != '')
            <div class="same_title">
                <h2 class="title_div">{{ $data['title'] }}</h2>
            </div>
        @endif
        {!! $data['content'] !!}

    @elseif($data['alignment'] == 'rt-txt')
        
        <div class="right_img_cms">
            <img src="{{  App\Helpers\resize_image::resize($data['image']) }}" alt="{{ $data['title'] }}">
        </div>
        @if($data['title'] != '')
            <div class="same_title">
                <h2 class="title_div">{{ $data['title'] }}</h2>
            </div>
        @endif
        {!! $data['content'] !!}

    @elseif($data['alignment'] == 'top-txt')

        <img src="{{  App\Helpers\resize_image::resize($data['image']) }}" alt="{{ $data['title'] }}">
        @if($data['title'] != '')
            <div class="same_title">
                <h2 class="title_div">{{ $data['title'] }}</h2>
            </div>
        @endif
        {!! $data['content'] !!}

    @elseif($data['alignment'] == 'bot-txt')

        
        @if($data['title'] != '')
            <div class="same_title">
                <h2 class="title_div">{{ $data['title'] }}</h2>
            </div>
        @endif
        {!! $data['content'] !!}
        <div class="ac-mb-xs-15"></div>
        <img src="{{  App\Helpers\resize_image::resize($data['image']) }}" alt="{{ $data['title'] }}">

    @elseif($data['alignment'] == 'center-txt')

        @if($data['title'] != '')
            <div class="same_title">
                <h2 class="title_div">{{ $data['title'] }}</h2>
            </div>
        @endif
        {!! $data['content'] !!}
        <div class="ac-mb-xs-15"></div>
        <img src="{{  App\Helpers\resize_image::resize($data['image']) }}" alt="{{ $data['title'] }}">


    @endif

@else

    @if(isset($data['alignment']) && $data['alignment'] == 'lft-txt')
        
            <section class="section">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12 col-xs-12 cms">
                            <div class="left_img_cms">
                                <img src="{{  App\Helpers\resize_image::resize($data['image']) }}" alt="{{ $data['title'] }}">
                            </div>
                            @if($data['title'] != '')
                            <div class="same_title">
                                <h2 class="title_div">{{ $data['title'] }}</h2>
                            </div>
                            @endif
                            {!! $data['content'] !!}
                        </div>
                    </div>
                </div>
            </section>
        

    @elseif(isset($data['alignment']) && $data['alignment'] == 'rt-txt')
        
            <section class="section">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12 col-xs-12 cms">
                            <div class="right_img_cms">
                                <img src="{{  App\Helpers\resize_image::resize($data['image']) }}" alt="{{ $data['title'] }}">
                            </div>
                            @if($data['title'] != '')
                            <div class="same_title">
                                <h2 class="title_div">{{ $data['title'] }}</h2>
                            </div>
                            @endif
                            {!! $data['content'] !!}
                        </div>
                    </div>
                </div>
            </section>
       

    @elseif(isset($data['alignment']) && $data['alignment'] == 'top-txt')
        
            <section class="section">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12 col-xs-12 cms">
                            <img src="{{  App\Helpers\resize_image::resize($data['image']) }}" alt="{{ $data['title'] }}">
                            @if($data['title'] != '')
                            <div class="same_title">
                                <h2 class="title_div">{{ $data['title'] }}</h2>
                            </div>
                            @endif
                            {!! $data['content'] !!}
                        </div>
                    </div>
                </div>
            </section>
        

    @elseif(isset($data['alignment']) && $data['alignment'] == 'bot-txt')
       
            <div class="same_title">
                <h2 class="title_div">{{ $data['title'] }}</h2>
            </div>
            {!! $data['content'] !!}
            <section class="section">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12 col-xs-12 cms">
                            <img src="{{  App\Helpers\resize_image::resize($data['image']) }}" alt="{{ $data['title'] }}">
                        </div>
                    </div>
                </div>
            </section>
       

    @elseif(isset($data['alignment']) && $data['alignment'] == 'center-txt')

        <div class="same_title">
            <h2 class="title_div">{{ $data['title'] }}</h2>
        </div>
        {!! $data['content'] !!}
        <section class="section">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12 col-xs-12 cms">
                        <img src="{{  App\Helpers\resize_image::resize($data['image']) }}" alt="{{ $data['title'] }}">
                    </div>
                </div>
            </div>
        </section>
        
    @endif    
    
@endif
