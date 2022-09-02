@if(Request::segment(1) != '')

    @if($data['videoalignment'] == 'lft-txt')

        <div class="row">
            <div class="col-sm-5 col-xs-12 cms about-left animated fadeInLeft">
                <div class="about_image">
                    <iframe width="100%" height="315" src="{{ $data['vidId'] }}?rel=0;&autoplay=1" frameborder="0" allow="autoplay;"></iframe>
                </div>
            </div>
            <div class="col-sm-7 col-xs-12 cms about-left animated fadeInRight">
                @if($data['videotitle'] != '')
                <div class="same_title">
                    <h2 class="title_div">{{ $data['videotitle'] }}</h2>
                </div>
                @endif
                <div class="info">
                    {!! $data['content'] !!}
                </div>
            </div>
        </div>

    @elseif($data['videoalignment'] == 'rt-txt')

        <div class="row">
            <div class="col-sm-7 col-xs-12 cms about-left animated fadeInLeft">
                <div class='visible-xs'>
                    <div class="about_image">
                        <iframe width="100%" height="315" src="{{ $data['vidId'] }}?rel=0;&autoplay=1" frameborder="0" allow="autoplay;"></iframe>
                    </div>
                </div>
                @if($data['videotitle'] != '')
                <div class="same_title">
                    <h2 class="title_div">{{ $data['videotitle'] }}</h2>
                </div>
                @endif
                <div class="info">
                    {!! $data['content'] !!}
                </div>
            </div>
            <div class="col-sm-5 col-xs-12 cms about-left animated fadeInRight hidden-xs">
                <div class="about_image">
                    <iframe width="100%" height="315" src="{{ $data['vidId'] }}?rel=0;&autoplay=1" frameborder="0" allow="autoplay;"></iframe>
                </div>
            </div>
        </div>
        

    @elseif($data['videoalignment'] == 'top-txt')

        <div class="col-sm-12 col-xs-12 cms about-left animated fadeInUp">
            <div class='about_full'>
                <div class="about_image">
                    <iframe width="100%" height="315" src="{{ $data['vidId'] }}?rel=0;&autoplay=1" frameborder="0" allow="autoplay;"></iframe>
                </div>
                @if($data['videotitle'] != '')
                <div class="same_title">
                    <h2 class="title_div">{{ $data['videotitle'] }}</h2>
                </div>
                @endif
                <div class="info">
                    {!! $data['content'] !!}
                </div>
            </div>
        </div>
        

    @elseif($data['videoalignment'] == 'bot-txt')

        <div class="col-sm-12 col-xs-12 cms about-left animated fadeInUp">
            <div class='about_full'>
                @if($data['videotitle'] != '')
                <div class="same_title">
                    <h2 class="title_div">{{ $data['videotitle'] }}</h2>
                </div>
                @endif
                <div class="info">
                    {!! $data['content'] !!}
                </div>
                <div class="about_image">
                    <iframe width="100%" height="315" src="{{ $data['vidId'] }}?rel=0;&autoplay=1" frameborder="0" allow="autoplay;"></iframe>
                </div>
            </div>
        </div>
            
    @elseif($data['videoalignment'] == 'center-txt')

        <div class="col-sm-12 col-xs-12 cms about-left animated fadeInUp">
            <div class='about_full'>
                <div class="about_image">
                    <iframe width="100%" height="315" src="{{ $data['vidId'] }}?rel=0;&autoplay=1" frameborder="0" allow="autoplay;"></iframe>
                </div>
                @if($data['videotitle'] != '')
                <div class="same_title">
                    <h2 class="title_div">{{ $data['videotitle'] }}</h2>
                </div>
                @endif
                <div class="info">
                    {!! $data['content'] !!}
                </div>
            </div>
        </div>
    
    @endif

@else   

    @if(isset($data['videotitle']) && $data['videoalignment'] == 'lft-txt')
        
        <section class="about_sec">
            <div class="container">
                <div class="row">
                    <div class="col-sm-5 col-xs-12 cms about-left animated fadeInLeft">
                        <div class="about_image">
                            <iframe width="100%" height="315" src="{{ $data['vidId'] }}?rel=0;&autoplay=1" frameborder="0" allow="autoplay;"></iframe>
                        </div>
                    </div>
                    <div class="col-sm-7 col-xs-12 cms about-left animated fadeInRight">
                        @if($data['videotitle'] != '')
                        <div class="same_title">
                            <h2 class="title_div">{{ $data['videotitle'] }}</h2>
                        </div>
                        @endif
                        <div class="info">
                            {!! $data['content'] !!}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    

    @elseif(isset($data['videotitle']) && $data['videoalignment'] == 'rt-txt')
        
        <section class="about_sec">
            <div class="container">
                <div class="row">
                    <div class="col-sm-7 col-xs-12 cms about-left animated fadeInLeft">
                        <div class='visible-xs'>
                            <div class="about_image">
                                <iframe width="100%" height="315" src="{{ $data['vidId'] }}?rel=0;&autoplay=1" frameborder="0" allow="autoplay;"></iframe>
                            </div>
                        </div>
                        @if($data['videotitle'] != '')
                        <div class="same_title">
                            <h2 class="title_div">{{ $data['videotitle'] }}</h2>
                        </div>
                        @endif
                        <div class="info">
                            {!! $data['content'] !!}
                        </div>
                    </div>
                    <div class="col-sm-5 col-xs-12 cms about-left animated fadeInRight hidden-xs">
                        <div class="about_image">
                            <iframe width="100%" height="315" src="{{ $data['vidId'] }}?rel=0;&autoplay=1" frameborder="0" allow="autoplay;"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        

    @elseif(isset($data['videotitle']) && $data['videoalignment'] == 'top-txt')

        <section class="about_sec">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12 col-xs-12 cms about-left animated fadeInUp">
                        <div class='about_full'>
                            <div class="about_image">
                                <iframe width="100%" height="315" src="{{ $data['vidId'] }}?rel=0;&autoplay=1" frameborder="0" allow="autoplay;"></iframe>
                            </div>
                            @if($data['videotitle'] != '')
                            <div class="same_title">
                                <h2 class="title_div">{{ $data['videotitle'] }}</h2>
                            </div>
                            @endif
                            <div class="info">
                                {!! $data['content'] !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        

    @elseif(isset($data['videotitle']) && $data['videoalignment'] == 'bot-txt')

        <section class="about_sec">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12 col-xs-12 cms about-left animated fadeInUp">
                        <div class='about_full'>
                            @if($data['videotitle'] != '')
                            <div class="same_title">
                                <h2 class="title_div">{{ $data['videotitle'] }}</h2>
                            </div>
                            @endif
                            <div class="info">
                                {!! $data['content'] !!}
                            </div>
                            <div class="about_image">
                                <iframe width="100%" height="315" src="{{ $data['vidId'] }}?rel=0;&autoplay=1" frameborder="0" allow="autoplay;"></iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    @endif

@endif
