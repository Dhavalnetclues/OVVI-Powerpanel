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
                <div class="col-md-12 col-sm-12 col-xs-12">
            @else
                <div class="{{ $pcol }} d-flex gap" data-aos="fade-up">
            @endif
                <article class="-items n-bs-1 w-100">
                    <div class="thumbnail-container" data-thumb="66.66%">
                        <div class="thumbnail">
                            <img src="{{ $itemImg }}" alt="{{ $news->varTitle }}" title="{{ $news->varTitle }}">
                        </div>
                    </div>
                    <ul class="nqul -tag d-flex align-items-center n-mt-15">
                        <li>
                            <div class="-nimg">
                                <img src="{{ $CDN_PATH.'assets/images/logo.png' }}" alt="{{$news->varSector}}" title="{{$news->varSector}}">
                            </div>
                            <div class="-nlabel n-fc-white-500 n-fs-16 n-fw-800 text-uppercase">{{$news->varSector}}</div>
                        </li>
                        @if(isset($news->dtDateTime) && $news->dtDateTime != '')
                            <li class="nq-svg n-fs-14 n-fw-500 n-fc-gray-500 n-ff-2">
                                <i class="n-icon" data-icon="s-calendar"></i>
                                {{ date('M',strtotime($news->dtDateTime)) }} {{ date('d',strtotime($news->dtDateTime)) }}, {{ date('Y',strtotime($news->dtDateTime)) }}
                            </li>
                        @endif
                    </ul>
                    <h2 class="-title n-fs-18 n-fw-600 n-fc-black-500 n-lh-140 n-mt-15">{{ $news->varTitle }}</h2>
                    @if(isset($description) && $description != '')
                        <div class="cms n-mt-15">
                            <p>{!! $description !!}</p>
                        </div>
                    @endif

                    @if(isset($news->txtDescription) && !empty($news->txtDescription))
                        <div class="-download -view">
                            <a href="{{ $recordLinkUrl }}" title="View Detail" class="nq-svg n-fs-14 n-fw-600 n-fc-white-500 n-ah-white-500 n-ff-2 d-flex align-items-center">
                                <span class="n-mr-10">View Detail</span>
                                <i class="n-icon" data-icon="s-pagination"></i>
                            </a>
                        </div>
                    @else
                    @if(isset($news->fkIntDocId))
                        <div class="-download">
                            <a data-toggle="collapse" href="#newsDownload{{$news->id}}" role="button" aria-expanded="false" aria-controls="newsDownload{{$news->id}}" title="Download" class="nq-svg n-fs-14 n-fw-600 n-fc-white-500 n-ah-white-500 n-ff-2 d-flex align-items-center">
                                <i class="n-icon" data-icon="s-pdf"></i>
                                <span class="n-ml-10">Download</span>
                            </a>
                        </div>
                    @endif
                        <div class="-pdf collapse" id="newsDownload{{$news->id}}">
                            @php
                                $docsAray = explode(',',$news->fkIntDocId);
                                $docObj   = App\Document::getDocDataByIds($docsAray);
                            @endphp
                            <a class="-close" data-toggle="collapse" href="#newsDownload{{$news->id}}" role="button" aria-expanded="false" aria-controls="newsDownload{{$news->id}}"><i class="fa fa-close"></i></a>
                            <div class="mCcontent">
                                <ul class="nqul -pdflist n-fs-14 n-fw-600 n-fc-white-500 n-ff-2">
                                    @if(count($docObj) > 0)
                                        @foreach($docObj as $key => $val)
                                            @php
                                        if ($val->fk_folder > 0 && !empty($val->foldername)) {

                                        if($val->varDocumentExtension == 'pdf' || $val->varDocumentExtension == 'PDF'){    
                                        //$docURL = 'viewPDF/documents/'.$val->foldername.'/'.$val->txtSrcDocumentName . '.' . $val->varDocumentExtension;
                                        $docURL = route('viewFolderPDF',['dir' => 'documents' ,'foldername' => $val->foldername,'filename' => $val->txtSrcDocumentName . '.' . $val->varDocumentExtension]);
                                        } else {
                                        $docURL = $CDN_PATH . 'documents/' . $val->foldername . '/' . $val->txtSrcDocumentName . '.' . $val->varDocumentExtension;
                                        }

                                        } else {

                                        if($val->varDocumentExtension == 'pdf' || $val->varDocumentExtension == 'PDF') {
                                        //$docURL = 'viewPDF/documents/'. $val->txtSrcDocumentName . '.' . $val->varDocumentExtension;
                                        $docURL = route('viewPDF',['dir' => 'documents','filename' => $val->txtSrcDocumentName.'.'.$val->varDocumentExtension]);
                                        }
                                        else{
                                        $docURL = $CDN_PATH . 'documents/'. $val->txtSrcDocumentName . '.' . $val->varDocumentExtension;
                                        }
                                        }
                                        @endphp
                                            @php
                                                if($val->varDocumentExtension == 'pdf' || $val->varDocumentExtension == 'PDF'){
                                                    $blank = 'target="_blank"';
                                                }else{
                                                    $blank = '';
                                                }
                                                if($val->varDocumentExtension == 'pdf' || $val->varDocumentExtension == 'PDF'){
                                                    $icon = "pdf.svg";
                                                }elseif($val->varDocumentExtension == 'doc' || $val->varDocumentExtension == 'docx'){
                                                    $icon = "doc.svg";
                                                }elseif($val->varDocumentExtension == 'xls' || $val->varDocumentExtension == 'xlsx'){
                                                    $icon = "xls.svg";
                                                }else{
                                                    $icon = "doc.svg";
                                                }
                                                
                                            @endphp
                                            <li>
                                                <a href="{{ $docURL }}" data-viewid="{{ $val->id }}" data-viewtype="download" title="Download" class="nq-svg d-flex align-items-center docHitClick" target="_blank" download>
                                                    <span class="-pdfimg d-inline-flex"><i class="n-icon" data-icon="s-pdf"></i></span>
                                                    <span class="-pdftitle d-inline-flex">{{ $val->txtDocumentName }}</span>
                                                </a>
                                            </li>
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                        </div>
                    @endif
                </article>
            </div>
        @endforeach
    </div>

    @if(Request::segment(1) != '' && isset($data['paginatehrml']) && $data['paginatehrml'] == true)
        @if($data['news']->total() > $data['news']->perPage())
            <div class="n-mt-lg-80 n-mt-40" data-aos="fade-up" id="paginationSection">
                @include('partial.pagination', ['paginator' => $data['news']->links()['paginator'], 'elements' => $data['news']->links()['elements']['0']])
            </div>
        @endif
    @endif
@endif