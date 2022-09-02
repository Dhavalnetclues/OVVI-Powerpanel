@php
$companyurl = '';
@endphp
@if(isset($data['Companies']) && !empty($data['Companies']) && count($data['Companies']) > 0)
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
$cols = 'col-md-3 col-sm-3 col-xs-12';
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
<section class="blog_sec owl-section {{ $class }}"  data-grid="{{ $grid }}">
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
                    @foreach($data['Companies'] as $company)
                        @php
                        if(isset(App\Helpers\MyLibrary::getFront_Uri('Companies')['uri'])){
                        $moduelFrontPageUrl = App\Helpers\MyLibrary::getFront_Uri('Companies')['uri'];
                        $moduleFrontWithCatUrl = ($company->varAlias != false ) ? $moduelFrontPageUrl . '/' . $company->varAlias : $moduelFrontPageUrl;
                        $categoryRecordAlias = App\Helpers\Mylibrary::getRecordAliasByModuleNameRecordId('Companies',$company->intFkCategory);
                        $recordLinkUrl = $moduleFrontWithCatUrl.'/'.$company->alias->varAlias;
                        }else{
                        $recordLinkUrl = '';
                        }
                        @endphp
                        @if(isset($company->fkIntImgId))
                        @php
                        $itemImg = App\Helpers\resize_image::resize($company->fkIntImgId);
                        @endphp
                        @else
                        @php
                        $itemImg = $CDN_PATH.'assets/images/blog-img1.jpg';
                        @endphp
                        @endif

                        @if(isset($company->custom['description']))
                        @php
                        $description = $company->custom['description'];
                        @endphp
                        @else
                        @php
                        $description = $company->varShortDescription;
                        @endphp
                        @endif
                        @if($data['cols'] == 'list')
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="blog_post listing clearfix">
                                @if(isset($company->fkIntImgId) && $company->fkIntImgId != '')
                                <div class="blog_img">
                                    <div class="thumbnail-container">
                                        <div class="thumbnail">
                                            <a title="{{ $company->varTitle }}" href="{{ $recordLinkUrl }}">
                                                <img src="{{ $itemImg }}" alt="{{ $company->varTitle }}">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                <div class="info">
                                    @if(isset($company->dtDateTime) && $company->dtDateTime != '')
                                    <div class="date">{{ date('l d M, Y',strtotime($company->dtDateTime)) }}</div>
                                    @endif
                                    <h5 class="sub_title"><a href="{{ $recordLinkUrl }}" title="{{ $company->varTitle }}" alt="{{ $company->varTitle }}">{{ $company->varTitle }}</a></h5>
                                    @if(isset($description) && $description != '')
                                    <p>{!! (strlen($description) > 150) ? substr($description, 0, 150).'...' : $description !!}</p>
                                    @endif
                                    <a class="btn ac-border " href="{{ $recordLinkUrl }}" title="Read More">Read More</a>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="{{ $pcol }}">
                            <div class="blog_post">
                                @if(isset($company->fkIntImgId) && $company->fkIntImgId != '')
                                <div class="blog_img">
                                    <div class="thumbnail-container">
                                        <div class="thumbnail">
                                            <a title="{{ $company->varTitle }}" href="{{ $recordLinkUrl }}">
                                                <img src="{{ $itemImg }}" alt="{{ $company->varTitle }}">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                <div class="info">
                                    @if(isset($company->dtDateTime) && $company->dtDateTime != '')
                                    <div class="date">{{ date('l d M, Y',strtotime($company->dtDateTime)) }}</div>
                                    @endif
                                    <h5 class="sub_title"><a href="{{ $recordLinkUrl }}" title="{{ $company->varTitle }}" alt="{{ $company->varTitle }}">{{ $company->varTitle }}</a></h5>
                                    @if(isset($description) && $description != '')
                                    <p>{!! (strlen($description) > 80) ? substr($description, 0, 80).'...' : $description !!}</p>
                                    @endif
                                    <a class="btn ac-border " href="{{ $recordLinkUrl }}" title="Read More">Read More</a>
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
                    <a class="btn ac-border btn-more" href="{!! $companyurl !!}" title="View All">View All</a>
                </div>
            </div>
        </div>
    </div>
</section>
@else
<section class="blog_sec">
    <div class="container">
        @if(isset($data['desc']) && $data['desc'] != '')
        <div class="row">
            <div class="col-sm-12 col-xs-12">
                <p>{!! $data['desc'] !!}</p>
            </div>
        </div>
        @endif
        <div class="{{ $class }}" data-grid="{{ $grid }}">
            <div class="row">
                @if(isset($data['paginatehrml']) && $data['paginatehrml'] != true)
                <div class="col-12">
                    <div class="owl-carousel owl-theme owl-nav-absolute">
                        @endif
                        @foreach($data['Companies'] as $company)
                        @php
                        if(isset(App\Helpers\MyLibrary::getFront_Uri('Companies')['uri'])){
                        $moduelFrontPageUrl = App\Helpers\MyLibrary::getFront_Uri('Companies')['uri'];
                        $moduleFrontWithCatUrl = ($company->varAlias != false ) ? $moduelFrontPageUrl . '/' . $company->varAlias : $moduelFrontPageUrl;
                        $categoryRecordAlias = App\Helpers\Mylibrary::getRecordAliasByModuleNameRecordId('Companies',$company->intFkCategory);
                        $recordLinkUrl = $moduleFrontWithCatUrl.'/'.$company->alias->varAlias;
                        }else{
                        $recordLinkUrl = '';
                        }
                        @endphp
                        @if(isset($company->fkIntImgId))
                        @php
                        $itemImg = App\Helpers\resize_image::resize($company->fkIntImgId);
                        @endphp
                        @else
                        @php
                        $itemImg = $CDN_PATH.'assets/images/blog-img1.jpg';
                        @endphp
                        @endif

                        @if(isset($company->custom['description']))
                        @php
                        $description = $company->custom['description'];
                        @endphp
                        @else
                        @php
                        $description = $company->varShortDescription;
                        @endphp
                        @endif
                        @if($data['cols'] == 'list')
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="blog_post listing clearfix">
                                @if(isset($company->fkIntImgId) && $company->fkIntImgId != '')
                                <div class="blog_img">
                                    <div class="thumbnail-container">
                                        <div class="thumbnail">
                                            <a title="{{ $company->varTitle }}" href="{{ $recordLinkUrl }}">
                                                <img src="{{ $itemImg }}" alt="{{ $company->varTitle }}">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                <div class="info">
                                    @if(isset($company->dtDateTime) && $company->dtDateTime != '')
                                    <div class="date">{{ date('l d M, Y',strtotime($company->dtDateTime)) }}</div>
                                    @endif
                                    <h5 class="sub_title"><a href="{{ $recordLinkUrl }}" title="{{ $company->varTitle }}" alt="{{ $company->varTitle }}">{{ $company->varTitle }}</a></h5>
                                    @if(isset($description) && $description != '')
                                    <p>{!! (strlen($description) > 150) ? substr($description, 0, 150).'...' : $description !!}</p>
                                    @endif
                                    <a class="btn ac-border " href="{{ $recordLinkUrl }}" title="Read More">Read More</a>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="{{ $pcol }}">
                            <div class="blog_post">
                                @if(isset($company->fkIntImgId) && $company->fkIntImgId != '')
                                <div class="blog_img">
                                    <div class="thumbnail-container">
                                        <div class="thumbnail">
                                            <a title="{{ $company->varTitle }}" href="{{ $recordLinkUrl }}">
                                                <img src="{{ $itemImg }}" alt="{{ $company->varTitle }}">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                <div class="info">
                                    @if(isset($company->dtDateTime) && $company->dtDateTime != '')
                                    <div class="date">{{ date('l d M, Y',strtotime($company->dtDateTime)) }}</div>
                                    @endif
                                    <h5 class="sub_title"><a href="{{ $recordLinkUrl }}" title="{{ $company->varTitle }}" alt="{{ $company->varTitle }}">{{ $company->varTitle }}</a></h5>
                                    @if(isset($description) && $description != '')
                                    <p>{!! (strlen($description) > 80) ? substr($description, 0, 80).'...' : $description !!}</p>
                                    @endif
                                    <a class="btn ac-border " href="{{ $recordLinkUrl }}" title="Read More">Read More</a>
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
            @if($data['Companies']->total() > $data['Companies']->perPage())
            <div class="row">
                <div class="col-sm-12 n-mt-30 text-center" data-aos="fade-up">
                    {{ $data['Companies']->links() }}
                </div>
            </div>
            @endif
            @endif
        </div>
    </div>
</section>
@endif
@endif
