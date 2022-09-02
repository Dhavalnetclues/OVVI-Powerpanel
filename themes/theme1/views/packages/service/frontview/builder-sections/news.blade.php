@php
    $newsurl = '';
@endphp

@if(isset($data['news']) && !empty($data['news']) && count($data['news']) > 0)
    @php 
        $cols = 'col-md-4 col-sm-4 col-xs-12';
        $grid = '3';
        if($data['cols'] == 'grid_2_col'){
            $cols = 'col-xl-6';
            $grid = '2';
        }elseif ($data['cols'] == 'grid_3_col') {
            $cols = 'col-xl-4';
            $grid = '3';
        }elseif ($data['cols'] == 'grid_4_col') {
            $cols = 'col-xl-3';
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

    @if(isset($data['desc']) && $data['desc'] != '')
        <div class="row">
            <div class="col-12 cms n-mb-30" data-aos="fade-up">
                <p>{!! $data['desc'] !!}</p>
            </div>
        </div>
    @endif

    <div class="row {{ $class }}" data-grid="{{ $grid }}">
        @foreach($data['news'] as $news)
            @php
                if(isset(App\Helpers\MyLibrary::getFront_Uri('news')['uri'])){
                    $moduelFrontPageUrl = App\Helpers\MyLibrary::getFront_Uri('news')['uri'];
                    $moduleFrontWithCatUrl = ($news->varAlias != false ) ? $moduelFrontPageUrl . '/' . $news->varAlias : $moduelFrontPageUrl;
                    $categoryRecordAlias = App\Helpers\Mylibrary::getRecordAliasByModuleNameRecordId('news',$news->txtCategories);
                    $recordLinkUrl = $moduleFrontWithCatUrl.'/'.$news->alias->varAlias;
                } else {
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
            @else
                <div class="{{ $pcol }} gap" data-aos="fade-up">
            @endif
                <article class="-items n-bs-1">
                    <ul class="nqul -tag d-flex align-items-center n-fs-16 n-fw-600 n-fc-black-500 n-ff-2">
                        <li>
                            <div class="-nimg">
                                <img src="{{ $CDN_PATH.'assets/images/logo.png' }}" alt="ICT" title="ICT">
                            </div>
                            <div class="-nlabel n-fc-white-500 n-fs-18 n-fw-800">ICT</div>
                        </li>
                        @if(isset($news->dtDateTime) && $news->dtDateTime != '')
                            <li class="nq-svg">
                                <img class="svg" src="{{ $CDN_PATH.'assets/images/icon/calendar.svg' }}" alt="calendar">
                                {{ date('M',strtotime($news->dtDateTime)) }} {{ date('d',strtotime($news->dtDateTime)) }}, {{ date('Y',strtotime($news->dtDateTime)) }}
                            </li>
                        @endif
                    </ul>
                    <h2 class="nqtitle-ip n-mt-15">{{ $news->varTitle }}</h2>
                    @if(isset($description) && $description != '')
                        <div class="cms n-mt-15">
                            <p>{!! $description !!}</p>
                        </div>
                    @endif

                    {{-- <div class="-download">
                        <a data-toggle="collapse" href="#newsDownload@php echo $x; @endphp" role="button" aria-expanded="false" aria-controls="newsDownload@php echo $x; @endphp" title="Download" class="nq-svg n-fs-14 n-fw-600 n-fc-white-500 n-ah-white-500 n-ff-2 d-flex align-items-center">
                            <img src="{{ $CDN_PATH.'assets/images/icon/pdf.svg' }}" alt="Download" class="svg">
                            <span class="n-ml-10">Download</span>
                        </a>
                    </div>
                    <div class="-pdf collapse" id="newsDownload@php echo $x; @endphp">
                        <a class="-close" data-toggle="collapse" href="#newsDownload@php echo $x; @endphp" role="button" aria-expanded="false" aria-controls="newsDownload@php echo $x; @endphp"><i class="fa fa-close"></i></a>
                        <div class="mCcontent">
                            <ul class="nqul -pdflist n-fs-14 n-fw-600 n-fc-white-500 n-ff-2">
                                <li>
                                    <a href="#" title="Download" class="nq-svg d-flex align-items-center" target="_blank" download>
                                        <span class="-pdfimg d-inline-flex"><img src="{{ $CDN_PATH.'assets/images/icon/pdf.svg' }}" alt="" class="svg"></span>
                                        <span class="-pdftitle d-inline-flex">The Utility Regulation and Competition Office (‘OfReg’) has published its draft determination</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div> --}}
                    
                    <div class="-download -view">
                        <a href="{{ $recordLinkUrl }}" title="View Detail" class="nq-svg n-fs-14 n-fw-600 n-fc-white-500 n-ah-white-500 n-ff-2 d-flex align-items-center">
                            <span class="n-ml-10">View Detail</span>
                            <img src="{{ $CDN_PATH.'assets/images/icon/right-arrow.svg' }}" alt="View Detail" class="svg">
                        </a>
                    </div>
                </article>
            </div>
        @endforeach
    </div>
                
    @if(Request::segment(1) != '' && isset($data['paginatehrml']) && $data['paginatehrml'] == true)
        @if($data['news']->total() > $data['news']->perPage())
            <div class="n-mt-lg-80 n-mt-40" data-aos="fade-up">
                <ul class="pagination justify-content-center align-content-center">
                    <li class="page-item">
                        <a class="page-link" href="#" title="Previous">
                            <i class="n-icon" data-icon="s-pagination"></i>
                        </a>
                    </li>
                    <li class="page-item"><a class="page-link" href="#" title="1">1</a></li>
                    <li class="page-item active"><a class="page-link" href="#" title="2">2</a></li>
                    <li class="page-item"><a class="page-link" href="#" title="3">3</a></li>
                    <li class="page-item"><a class="page-link" href="#" title="4">4</a></li>
                    <li class="page-item"><a class="page-link" href="#" title="5">5</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#" title="Next">
                            <i class="n-icon" data-icon="s-pagination"></i>
                        </a>
                    </li>
                </ul>
            </div>
            {{-- {{ $data['news']->links() }} --}}
        @endif
    @endif
    
@endif