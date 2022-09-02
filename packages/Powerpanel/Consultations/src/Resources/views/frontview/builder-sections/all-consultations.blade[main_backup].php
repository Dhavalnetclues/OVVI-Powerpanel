@php
    $consultationsurl = '';
@endphp

@if(isset($data['consultations']) && !empty($data['consultations']) && count($data['consultations']) > 0)
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
        @foreach($data['consultations'] as $consultations)
            @php
                if(isset(App\Helpers\MyLibrary::getFront_Uri('consultations')['uri'])){
                    $moduelFrontPageUrl = App\Helpers\MyLibrary::getFront_Uri('consultations')['uri'];
                    $moduleFrontWithCatUrl = ($consultations->varAlias != false ) ? $moduelFrontPageUrl . '/' . $consultations->varAlias : $moduelFrontPageUrl;
                    $categoryRecordAlias = App\Helpers\Mylibrary::getRecordAliasByModuleNameRecordId('consultations',$consultations->txtCategories);
                    $recordLinkUrl = $moduleFrontWithCatUrl.'/'.$consultations->alias->varAlias;
                } else {
                    $recordLinkUrl = '';
                }
            @endphp

            @if(isset($consultations->custom['description']))
                @php $description = $consultations->custom['description']; @endphp
            @else 
                @php $description = $consultations->varShortDescription; @endphp
            @endif

            @if($data['cols'] == 'list')
                <div class="col-md-12 col-sm-12 col-xs-12 animated fadeInUp">
            @else
                <div class="{{ $pcol }} gap d-flex" data-aos="zoom-in">
            @endif

                <article class="-items n-bs-1 d-flex flex-column w-100">
                    <ul class="nqul -tag d-flex align-items-center">
                        @php $colourclass = '';@endphp
                        @if(strtolower($consultations->varSector) == 'ofreg')
                        @php
                        $colourclass = 'ofreg-tag';
                        @endphp
                        @elseif(strtolower($consultations->varSector) == 'ict')
                        @php $colourclass = 'ict-tag'; @endphp
                        @elseif(strtolower($consultations->varSector) == 'water')
                        @php $colourclass = 'water-tag'; @endphp
                        @elseif(strtolower($consultations->varSector) == 'fuel')
                        @php $colourclass = 'fuel-tag'; @endphp
                       @elseif(strtolower($consultations->varSector) == 'energy')
                        @php $colourclass = 'energy-tag'; @endphp
                        @endif
                        <li class="{{$colourclass}}">
                            <div class="-nimg">
                                <img src="{{ $CDN_PATH.'assets/images/logo.png' }}" alt="{{$consultations->varSector}}" title="{{$consultations->varSector}}">
                            </div>
                            
                            <div class="-nlabel n-fc-white-500 n-fs-16 n-fw-800 text-uppercase">{{$consultations->varSector}}</div>
                        </li>
                    </ul>

                    <h2 class="-title n-fs-18 n-fw-600 n-fc-black-500 n-lh-140 n-mt-15">{{ $consultations->varTitle }}</h2>

                    @if(isset($consultations->varShortDescription) && !empty($consultations->varShortDescription))
                        <div class="cms n-mv-15">
                            <p> {{ $consultations->varShortDescription }}</p>
                        </div>
                    @endif

                    <div class="row mt-auto">
                        <div class="col-sm-9 n-mb-15 n-mb-sm-0">
                            <ul class="nqul -bmenu d-flex align-items-center n-fs-12 n-fw-500 n-fc-gray-500 n-ff-2">
                                @if(isset($consultations->dtDateTime) && !empty($consultations->dtDateTime))
                                    <li class="nq-svg d-flex align-items-center n-lh-120">
                                        <i class="n-icon" data-icon="s-calendar"></i>
                                        Launch Date:<br> {{ date('M',strtotime($consultations->dtDateTime)) }} {{ date('d',strtotime($consultations->dtDateTime)) }}, {{ date('Y',strtotime($consultations->dtDateTime)) }}
                                    </li>
                                @endif
                                @if(isset($consultations->dtEndDateTime) && !empty($consultations->dtEndDateTime))
                                    <li class="nq-svg d-flex align-items-center n-lh-120">
                                        <i class="n-icon" data-icon="s-calendar"></i>
                                        Closing Date:<br> {{ date('M',strtotime($consultations->dtEndDateTime)) }} {{ date('d',strtotime($consultations->dtEndDateTime)) }}, {{ date('Y',strtotime($consultations->dtEndDateTime)) }}
                                    </li>
                                @endif
                            </ul>
                        </div>
                        <div class="col-sm-3">
                            @if(isset($consultations->txtDescription) && !empty($consultations->txtDescription))
                                <div class="-download -view">
                                    <a href="{{ $recordLinkUrl }}" title="View Detail" class="nq-svg n-fs-14 n-fw-600 n-fc-white-500 n-ah-white-500 n-ff-2 d-flex align-items-center">
                                        <span class="n-mr-10">View Detail</span>
                                        <i class="n-icon" data-icon="s-pagination"></i>
                                    </a>
                                </div>
                            @else
                                <div class="-download">
                                    <a data-toggle="collapse" href="#consultationsDownload{{$consultations->id}}" role="button" aria-expanded="false" aria-controls="consultationsDownload{{$consultations->id}}" title="Download" class="nq-svg n-fs-14 n-fw-600 n-fc-white-500 n-ah-white-500 n-ff-2 d-flex align-items-center">
                                        <i class="n-icon" data-icon="s-pdf"></i>
                                        <span class="n-ml-10">Download</span>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if(isset($consultations->txtDescription) && empty($consultations->txtDescription))
                        <div class="-pdf collapse" id="consultationsDownload{{$consultations->id}}">
                            @php
                                $docsAray = explode(',',$consultations->fkIntDocId);
                                $docObj   = App\Document::getDocDataByIds($docsAray);
                            @endphp
                            <a class="-close" data-toggle="collapse" href="#consultationsDownload{{$consultations->id}}" role="button" aria-expanded="false" aria-controls="consultationsDownload{{$consultations->id}}"><i class="fa fa-close"></i></a>
                            <div class="mCcontent">
                                <ul class="nqul -pdflist n-fs-14 n-fw-500 n-fc-white-500 n-ff-2">
                                    @if(count($docObj) > 0)
                                        @foreach($docObj as $key => $val)
                                            @php
                                                if ($val->fk_folder > 0 && !empty($val->foldername)) {
                                                    if($val->varDocumentExtension == 'pdf' || $val->varDocumentExtension == 'PDF'){    
                                                        $docURL = route('viewFolderPDF',['dir' => 'documents' ,'foldername' => $val->foldername,'filename' => $val->txtSrcDocumentName . '.' . $val->varDocumentExtension]);
                                                    } else {
                                                        $docURL = $CDN_PATH . 'documents/' . $val->foldername . '/' . $val->txtSrcDocumentName . '.' . $val->varDocumentExtension;
                                                    }
                                                } else {
                                                    if($val->varDocumentExtension == 'pdf' || $val->varDocumentExtension == 'PDF') {
                                                        $docURL = route('viewPDF',['dir' => 'documents','filename' => $val->txtSrcDocumentName.'.'.$val->varDocumentExtension]);
                                                    } else {
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
                                                <a href="{{ $docURL }}" title="Download" class="nq-svg d-flex align-items-center docHitClick" data-viewid="{{ $val->id }}" data-viewtype="download" target="_blank" download>
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
        @if($data['consultations']->total() > $data['consultations']->perPage())
            <div class="n-mt-lg-80 n-mt-40" data-aos="fade-up" id="paginationSection">
                @include('partial.pagination', ['paginator' => $data['consultations']->links()['paginator'], 'elements' => $data['consultations']->links()['elements']['0']])
            </div>
        @endif
    @endif
@endif