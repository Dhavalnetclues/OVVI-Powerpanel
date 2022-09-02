@php
    $cols = 'col-md-4 col-sm-4 col-xs-12';
    $grid = '3';

    if($data['cols'] == 'grid_2_col'){
        $cols = 'col-xl-6 col-lg-6 col-md-6';
        $grid = '2';
    }elseif ($data['cols'] == 'grid_3_col') {
        $cols = 'col-xl-4 col-lg-4 col-md-6';
        $grid = '3';
    }elseif ($data['cols'] == 'grid_4_col') {
        $cols = 'col-xl-3 col-lg-3 col-md-3';
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

@if(isset($data['services']) && !empty($data['services']) && count($data['services']) > 0)

    @if(Request::segment(1) == '')
        <div class="all_services homeservices-sec" data-aos="fade-up">
            <div class="container">

                <div class="row">
                    <div class="col-sm-12 col-xs-12">
                        <div class="same_title text-center n-mb-60">
                            @if(isset($data['title']) && $data['title'] != '')
                                <h2 class="title_div n-mb-15">{{ $data['title']}}</h2>
                            @endif
                            @if(isset($data['desc']) && $data['desc'] != '')
                                <p class="">{{ $data['desc'] }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row {{ $class }}" data-grid="{{ $grid }}">
                    @foreach($data['services'] as $services)
                        @php
                            if(isset(App\Helpers\MyLibrary::getFront_Uri('service')['uri'])){
                                $moduelFrontPageUrl = App\Helpers\MyLibrary::getFront_Uri('service')['uri'];
                                $moduleFrontWithCatUrl = ($services->varAlias != false ) ? $moduelFrontPageUrl . '/' . $services->varAlias : $moduelFrontPageUrl;
                                $recordLinkUrl = isset($services->alias->varAlias) ? $moduleFrontWithCatUrl.'/'.$services->alias->varAlias : '#';
                            } else {
                                $recordLinkUrl = '';
                            }
                        @endphp

                        @if(isset($services->fkIntImgId))
                            @php $itemImg = App\Helpers\resize_image::resize($services->fkIntImgId); @endphp
                        @else
                            @php $itemImg = $CDN_PATH.'assets/images/directors.png'; @endphp
                        @endif



                        @if($data['cols'] == 'list')
                            <div class="col-md-12 col-sm-12 col-xs-12">
                        @else
                            <div class="{{ $pcol }} gap service-gap">
                        @endif
                            <div class="services-box">
                            <div class="service-img">
                            <div class="thumbnail-container">
                                <div class="thumbnail">
                                    <a href="{{ $recordLinkUrl }}" title="{{ $services->varTitle }}">
                                        <img src="{{ $itemImg }}" alt="{{ $services->varTitle }}">
                                    </a>
                                </div>
                            </div>
                                </div>
                                <div class="service-info">
                                    <h5 class="title text-truncate"><a href="{{ $recordLinkUrl }}" title="{{ $services->varTitle }}"> {{ $services->varTitle }} </a></h5>
                                    <p>{{ str_limit($services->varShortDescription, $limit = 105, $end = '...') }}</p>
                                    <div class="more-btn">
                                        <a href="{{ $recordLinkUrl }}" class="link" title="Read More">Read More <i class="fa fa-arrow-right" aria-hidden="true"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @else
        <div class="all_services homeservices-sec">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12 col-xs-12">
                        <div class="same_title text-center n-mb-60">
                            @if(isset($data['title']) && $data['title'] != '')
                                <h2 class="title_div n-mb-15">{{ $data['title'] }}</h2>
                            @endif
                            @if(isset($data['desc']) && $data['desc'] != '')
                                <p class="">{{ $data['desc'] }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row {{ $class }}" data-grid="{{ $grid }}">
                    @foreach($data['services'] as $services)
                        @php
                            if(isset(App\Helpers\MyLibrary::getFront_Uri('service')['uri'])){
                                $moduelFrontPageUrl = App\Helpers\MyLibrary::getFront_Uri('service')['uri'];
                                $moduleFrontWithCatUrl = ($services->varAlias != false ) ? $moduelFrontPageUrl . '/' . $services->varAlias : $moduelFrontPageUrl;
                                $recordLinkUrl = $moduleFrontWithCatUrl.'/'.$services->alias->varAlias;
                            } else {
                                $recordLinkUrl = '';
                            }
                        @endphp

                        @if(isset($services->fkIntImgId))
                            @php $itemImg = App\Helpers\resize_image::resize($services->fkIntImgId); @endphp
                        @else
                            @php $itemImg = $CDN_PATH.'assets/images/directors.png'; @endphp
                        @endif



                        @if($data['cols'] == 'list')
                            <div class="col-md-12 col-sm-12 col-xs-12">
                        @else
                            <div class="{{ $pcol }} gap service-gap">
                        @endif
                            <div class="services-box">
                            <div class="service-img">
                            <div class="thumbnail-container">
                                <div class="thumbnail">
                                    <a href="{{ $recordLinkUrl }}" title="{{ $services->varTitle }}"><img src="{{ $itemImg }}" alt="{{ $services->varTitle }}"></a>
                                </div>
                            </div>
                                </div>
                                <div class="service-info">
                                    <h5 class="title text-truncate"><a href="{{ $recordLinkUrl }}" title="{{ $services->varTitle }}"> {{ $services->varTitle }} </a></h5>
                                    <p>{{ str_limit($services->varShortDescription, $limit = 105, $end = '...') }}</p>
                                    <div class="more-btn">
                                        <a href="{{ $recordLinkUrl }}" class="link" title="Read More">Read More <i class="fa fa-arrow-right" aria-hidden="true"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @if(Request::segment(1) != '' && isset($data['paginatehrml']) && $data['paginatehrml'] == true)
        @endif
    @endif
@endif