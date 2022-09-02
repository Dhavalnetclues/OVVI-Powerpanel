@php
$rfpsurl = '';
@endphp
@if(isset($data['rfps']) && !empty($data['rfps']) && count($data['rfps']) > 0)
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
<section class="rfps_sec owl-section {{ $class }}" data-grid="{{ $grid }}">
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
                        @foreach($data['rfps'] as $rfps)
                        @php
                        if(isset(App\Helpers\MyLibrary::getFront_Uri('rfps')['uri'])){
                        $moduelFrontPageUrl = App\Helpers\MyLibrary::getFront_Uri('rfps')['uri'];
                        $moduleFrontWithCatUrl = ($rfps->varAlias != false ) ? $moduelFrontPageUrl . '/' . $rfps->varAlias : $moduelFrontPageUrl;
                        $categoryRecordAlias = App\Helpers\Mylibrary::getRecordAliasByModuleNameRecordId('rfps',$rfps->txtCategories);
                        $recordLinkUrl = $moduleFrontWithCatUrl.'/'.$rfps->alias->varAlias;
                        }else{
                        $recordLinkUrl = '';
                        }
                        @endphp
                        @if(isset($rfps->custom['img']))
                        @php                          
                        $itemImg = App\Helpers\resize_image::resize($rfps->custom['img']);
                        @endphp
                        @else 
                        @php
                        $itemImg = App\Helpers\resize_image::resize($rfps->fkIntImgId);
                        @endphp
                        @endif

                        @if(isset($rfps->custom['description']))
                        @php
                        $description = $rfps->custom['description'];
                        @endphp
                        @else 
                        @php
                        $description = $rfps->varShortDescription;
                        @endphp
                        @endif
                        @if($data['cols'] == 'list')
                        <div class="col-md-12 col-sm-12 col-xs-12 animated fadeInUp">
                            <div class="rfps_post rfps_post_center">
                                @if(isset($rfps->dtDateTime) && $rfps->dtDateTime != '')
                                <div class="date">
                                    <span>{{ date('d',strtotime($rfps->dtDateTime)) }}</span><span>{{ date('M',strtotime($rfps->dtDateTime)) }}</span><span>{{ date('Y',strtotime($rfps->dtDateTime)) }}</span>
                                </div>
                                @endif
                                <div class="info">
                                    <h5 class="sub_title"><a href="{{ $recordLinkUrl }}" title="{{ $rfps->varTitle }}" alt="{{ $rfps->varTitle }}">{{ $rfps->varTitle }}</a></h5>
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
                            <div class="rfps_post">
                                @if(isset($rfps->dtDateTime) && $rfps->dtDateTime != '')
                                <div class="date">
                                    <span>{{ date('d',strtotime($rfps->dtDateTime)) }}</span><span>{{ date('M',strtotime($rfps->dtDateTime)) }}</span><span>{{ date('Y',strtotime($rfps->dtDateTime)) }}</span>
                                </div>
                                @endif
                                <div class="info">
                                    <h5 class="sub_title"><a href="{{ $recordLinkUrl }}" title="{{ $rfps->varTitle }}" alt="{{ $rfps->varTitle }}">{{ $rfps->varTitle }}</a></h5>
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
                    <a class="btn ac-border btn-more" href="{!! $rfpsurl !!}" title="View All Tenders">View All Tenders</a>
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
            @foreach($data['rfps'] as $rfps)
            @php
            if(isset(App\Helpers\MyLibrary::getFront_Uri('rfps')['uri'])){
            $moduelFrontPageUrl = App\Helpers\MyLibrary::getFront_Uri('rfps')['uri'];
            $moduleFrontWithCatUrl = ($rfps->varAlias != false ) ? $moduelFrontPageUrl . '/' . $rfps->varAlias : $moduelFrontPageUrl;
            $categoryRecordAlias = App\Helpers\Mylibrary::getRecordAliasByModuleNameRecordId('rfps',$rfps->txtCategories);
            $recordLinkUrl = $moduleFrontWithCatUrl.'/'.$rfps->alias->varAlias;
            }else{
            $recordLinkUrl = '';
            }
            @endphp
            @if(isset($rfps->custom['img']))
            @php                          
            $itemImg = App\Helpers\resize_image::resize($rfps->custom['img']);
            @endphp
            @else 
            @php
            $itemImg = App\Helpers\resize_image::resize($rfps->fkIntImgId);
            @endphp
            @endif

            @if(isset($rfps->custom['description']))
            @php
            $description = $rfps->custom['description'];
            @endphp
            @else 
            @php
            $description = $rfps->varShortDescription;
            @endphp
            @endif
            @if($data['cols'] == 'list')
            <div class="col-md-12 col-sm-12 col-xs-12 animated fadeInUp">
                <div class="rfps_post rfps_post_center">
                    @if(isset($rfps->dtDateTime) && $rfps->dtDateTime != '')
                    <div class="date">
                        <span>{{ date('d',strtotime($rfps->dtDateTime)) }}</span><span>{{ date('M',strtotime($rfps->dtDateTime)) }}</span><span>{{ date('Y',strtotime($rfps->dtDateTime)) }}</span>
                    </div>
                    @endif
                    <div class="info">
                        <h5 class="sub_title"><a href="{{ $recordLinkUrl }}" title="{{ $rfps->varTitle }}" alt="{{ $rfps->varTitle }}">{{ $rfps->varTitle }}</a></h5>
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
                <div class="rfps_post">
                    @if(isset($rfps->dtDateTime) && $rfps->dtDateTime != '')
                    <div class="date">
                        <span>{{ date('d',strtotime($rfps->dtDateTime)) }}</span><span>{{ date('M',strtotime($rfps->dtDateTime)) }}</span><span>{{ date('Y',strtotime($rfps->dtDateTime)) }}</span>
                    </div>
                    @endif
                    <div class="info">
                        <h5 class="sub_title"><a href="{{ $recordLinkUrl }}" title="{{ $rfps->varTitle }}" alt="{{ $rfps->varTitle }}">{{ $rfps->varTitle }}</a></h5>
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
    @if($data['rfps']->total() > $data['rfps']->perPage())
    <div class="row">
        <div class="col-sm-12 n-mt-30" data-aos="fade-up">
            {{ $data['rfps']->links() }}
        </div>
    </div>
    @endif
    @endif
</div>

@endif
@endif