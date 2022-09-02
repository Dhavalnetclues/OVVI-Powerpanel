@php
    $newsurl = '';
@endphp

@if(isset($data['news']) && !empty($data['news']) && count($data['news']) > 0)
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
        <section class="news_sec owl-section {{ $class }}" data-grid="{{ $grid }}">
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
                <div class="blog_slide">
                    <div class="row">
                        @if(isset($data['paginatehrml']) && $data['paginatehrml'] != true)
                        <div class="col-12">
                            <div class="owl-carousel owl-theme owl-nav-absolute">
                                @endif
                                @foreach($data['news'] as $news)
                                @php
                                if(isset(App\Helpers\MyLibrary::getFront_Uri('news')['uri'])){
                                $moduelFrontPageUrl = App\Helpers\MyLibrary::getFront_Uri('news')['uri'];
                                $moduleFrontWithCatUrl = ($news->varAlias != false ) ? $moduelFrontPageUrl . '/' . $news->varAlias : $moduelFrontPageUrl;
                                $categoryRecordAlias = App\Helpers\Mylibrary::getRecordAliasByModuleNameRecordId('news',$news->txtCategories);
                                $recordLinkUrl = $moduleFrontWithCatUrl.'/'.$news->alias->varAlias;
                                }else{
                                $recordLinkUrl = '';
                                }
                                @endphp
                                @if(isset($news->custom['img']))
                                @php                          
                                $itemImg = App\Helpers\resize_image::resize($news->custom['img']);
                                @endphp
                                @else 
                                @php
                                $itemImg = App\Helpers\resize_image::resize($news->fkIntImgId);
                                @endphp
                                @endif

                                @if(isset($news->custom['description']))
                                @php
                                $description = $news->custom['description'];
                                @endphp
                                @else 
                                @php
                                $description = $news->varShortDescription;
                                @endphp
                                @endif
                                @if($data['cols'] == 'list')
                                <div class="col-md-12 col-sm-12 col-xs-12 animated fadeInUp">
                                    <div class="news_post news_post_center">
                                        @if(isset($news->dtDateTime) && $news->dtDateTime != '')
                                        <div class="date">
                                            <span>{{ date('d',strtotime($news->dtDateTime)) }}</span><span>{{ date('M',strtotime($news->dtDateTime)) }}</span><span>{{ date('Y',strtotime($news->dtDateTime)) }}</span>
                                        </div>
                                        @endif
                                        <div class="info">
                                            <h5 class="sub_title"><a href="{{ $recordLinkUrl }}" title="{{ $news->varTitle }}" alt="{{ $news->varTitle }}">{{ $news->varTitle }}</a></h5>
                                            <div class="info_dtl">
                                                @if(isset($description) && $description != '')
                                                <p>{!! (strlen($description) > 150) ? substr($description, 0, 150).'...' : $description !!}</p>
                                                @endif
                                                <a href="{{ $recordLinkUrl }}" class="n-more" title="Read More">[ Read More ]</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @else
                                <div class="{{ $pcol }} animated fadeInUp">
                                    <div class="news_post">
                                        @if(isset($news->dtDateTime) && $news->dtDateTime != '')
                                        <div class="date">
                                            <span>{{ date('d',strtotime($news->dtDateTime)) }}</span><span>{{ date('M',strtotime($news->dtDateTime)) }}</span><span>{{ date('Y',strtotime($news->dtDateTime)) }}</span>
                                        </div>
                                        @endif
                                        <div class="info">
                                            <h5 class="sub_title"><a href="{{ $recordLinkUrl }}" title="{{ $news->varTitle }}" alt="{{ $news->varTitle }}">{{ $news->varTitle }}</a></h5>
                                            <div class="info_dtl">
                                                @if(isset($description) && $description != '')
                                                <p>{!! (strlen($description) > 80) ? substr($description, 0, 80).'...' : $description !!}</p>
                                                @endif
                                                <a href="{{ $recordLinkUrl }}" class="n-more" title="Read More">[ Read More ]</a>
                                            </div>
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
                            <a class="btn ac-border btn-more" href="{!! $newsurl !!}" title="View All Tenders">View All Tenders</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @else
        <div class="row">
            <div class="col-sm-12 col-xs-12 animated fadeInUp">
                <div class="same_title text-center">
                    @if(isset($data['desc']) && $data['desc'] != '')
                    <p>{!! $data['desc'] !!}</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="row owl-section {{ $class }}" data-grid="{{ $grid }}">
            @if(isset($data['paginatehrml']) && $data['paginatehrml'] != true)
            <div class="col-12">
                <div class="owl-carousel owl-theme owl-nav-absolute">
                    @endif
                    @foreach($data['news'] as $news)
                    @php
                    if(isset(App\Helpers\MyLibrary::getFront_Uri('news')['uri'])){
                    $moduelFrontPageUrl = App\Helpers\MyLibrary::getFront_Uri('news')['uri'];
                    $moduleFrontWithCatUrl = ($news->varAlias != false ) ? $moduelFrontPageUrl . '/' . $news->varAlias : $moduelFrontPageUrl;
                    $categoryRecordAlias = App\Helpers\Mylibrary::getRecordAliasByModuleNameRecordId('news',$news->txtCategories);
                    $recordLinkUrl = $moduleFrontWithCatUrl.'/'.$news->alias->varAlias;
                    }else{
                    $recordLinkUrl = '';
                    }
                    @endphp
                    @if(isset($news->custom['img']))
                    @php                          
                    $itemImg = App\Helpers\resize_image::resize($news->custom['img']);
                    @endphp
                    @else 
                    @php
                    $itemImg = App\Helpers\resize_image::resize($news->fkIntImgId);
                    @endphp
                    @endif

                    @if(isset($news->custom['description']))
                    @php
                    $description = $news->custom['description'];
                    @endphp
                    @else 
                    @php
                    $description = $news->varShortDescription;
                    @endphp
                    @endif
                    @if($data['cols'] == 'list')
                    <div class="col-md-12 col-sm-12 col-xs-12 animated fadeInUp">
                        <div class="news_post news_post_center">
                            @if(isset($news->dtDateTime) && $news->dtDateTime != '')
                            <div class="date">
                                <span>{{ date('d',strtotime($news->dtDateTime)) }}</span><span>{{ date('M',strtotime($news->dtDateTime)) }}</span><span>{{ date('Y',strtotime($news->dtDateTime)) }}</span>
                            </div>
                            @endif
                            <div class="info">
                                <h5 class="sub_title"><a href="{{ $recordLinkUrl }}" title="{{ $news->varTitle }}" alt="{{ $news->varTitle }}">{{ $news->varTitle }}</a></h5>
                                <div class="info_dtl">
                                    @if(isset($description) && $description != '')
                                    <p>{!! (strlen($description) > 150) ? substr($description, 0, 150).'...' : $description !!}</p>
                                    @endif
                                    <a href="{{ $recordLinkUrl }}" class="n-more" title="Read More">[ Read More ]</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="{{ $pcol }} animated fadeInUp">
                        <div class="news_post">
                            @if(isset($news->dtDateTime) && $news->dtDateTime != '')
                            <div class="date">
                                <span>{{ date('d',strtotime($news->dtDateTime)) }}</span><span>{{ date('M',strtotime($news->dtDateTime)) }}</span><span>{{ date('Y',strtotime($news->dtDateTime)) }}</span>
                            </div>
                            @endif
                            <div class="info">
                                <h5 class="sub_title"><a href="{{ $recordLinkUrl }}" title="{{ $news->varTitle }}" alt="{{ $news->varTitle }}">{{ $news->varTitle }}</a></h5>
                                <div class="info_dtl">
                                    @if(isset($description) && $description != '')
                                    <p>{!! (strlen($description) > 80) ? substr($description, 0, 80).'...' : $description !!}</p>
                                    @endif
                                    <a href="{{ $recordLinkUrl }}" class="n-more" title="Read More">[ Read More ]</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @endforeach
                    @if(isset($data['paginatehrml']) && $data['paginatehrml'] != true)
                </div>
            </div>
            @endif
            @if(Request::segment(1) != '' && isset($data['paginatehrml']) && $data['paginatehrml'] == true)
            @if($data['news']->total() > $data['news']->perPage())
            <div class="row">
                <div class="col-sm-12 n-mt-30" data-aos="fade-up">
                    {{ $data['news']->links() }}
                </div>
            </div>
            @endif
            @endif
        </div>
    @endif
@endif