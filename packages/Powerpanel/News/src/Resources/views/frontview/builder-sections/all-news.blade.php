@php
    $newsurl = '';
@endphp
<div class="row">
    @if(isset($data['news']) && !empty($data['news']) && count($data['news']) > 0)
        @php
            $class = $data['class'];
            if(isset($data['paginatehrml']) && $data['paginatehrml'] == true){
                $pcol = 'col-lg-6 ';
            }else{
                $pcol = 'item';
            }

            $NewsArray = $data['news']->toArray();
            $right_side_records = array_slice($NewsArray,1);
        @endphp

        @if(isset($data['desc']) && $data['desc'] != '')
            <div class="row">
                <div class="col-12 cms n-mb-30">
                    <p>{!! $data['desc'] !!}</p>
                </div>
            </div>
        @endif

        <!-- {{-- right-side-content --}} -->
        <div class="col-lg-12">
            <div class="row {{ $class }}">
                @foreach($NewsArray['data'] as $news)
                    @php
                        $moduelFrontPageUrl = '#';
                        $recordLinkUrl = '#';
                        if(isset(App\Helpers\MyLibrary::getFront_Uri('news')['uri'])) {
                            $moduelFrontPageUrl =  App\Helpers\MyLibrary::getFront_Uri('news')['uri'];
                        }
                        $recordLinkUrl = (isset($news['alias']['varAlias']) && !empty($news['alias']['varAlias'])) ? $moduelFrontPageUrl . '/' . $news['alias']['varAlias'] : $moduelFrontPageUrl;
                        $varTitle = $news['varTitle'];                        
                    @endphp

                    @if(isset($news['fkIntImgId']) && $news['fkIntImgId'] > 0)
	                    @php
	                    	$itemImg = App\Helpers\resize_image::resize($news['fkIntImgId']);
	                    @endphp
                    @else
	                    @php
	                    	$itemImg = $CDN_PATH.'assets/images/news-default.jpg';
	                    @endphp
                    @endif                  	

                    @if(isset($news->custom['description']))
                        @php $description = strtolower($news->custom['description']); @endphp
                    @else
                        @php $description = strtolower($news['varShortDescription']); @endphp
                    @endif

                    @php $colourclass = '';@endphp
                    @if(strtolower($news['varSector']) == 'ofreg')
                    @php
                    $colourclass = 'ofreg-tag';
                    @endphp
                    @elseif(strtolower($news['varSector']) == 'ict')
                    @php $colourclass = 'ict-tag'; @endphp
                    @elseif(strtolower($news['varSector']) == 'water')
                    @php $colourclass = 'water-tag'; @endphp
                    @elseif(strtolower($news['varSector']) == 'fuel')
                    @php $colourclass = 'fuel-tag'; @endphp
                    @elseif(strtolower($news['varSector']) == 'energy')
                    @php $colourclass = 'energy-tag'; @endphp
                    @endif

                    <div class="col-lg-4 col-sm-6 new-gap {{$colourclass}}">
                        <div class="news-item">
                            <div class="thumbnail-container">
                                <div class="thumbnail">
                                    <a href="{{ $recordLinkUrl }}" title="{{$varTitle}}">
                                        <img src="{{ $itemImg }}" alt="{{$varTitle}}"/>
                                    </a>
                                </div>
                            </div>
                            <div class="content">
                                <div class="date-val n-mb-10 n-mb-lg-20">
                                    @if(isset($news['dtDateTime']) && $news['dtDateTime'] != '')
                                        {{ date('M',strtotime($news['dtDateTime'])) }} {{ date('d',strtotime($news['dtDateTime'])) }}, {{ date('Y',strtotime($news['dtDateTime'])) }}
                                    @endif
                                </div>

                                <h3 class="nqtitle">
                                    <a href="{{ $recordLinkUrl }}" title="{{ str_limit($varTitle) }}">
                                        {{ str_limit($varTitle, $limit = 65, $end = '...') }}
                                    </a>
                                </h3>

                                {{-- @if(isset($description) && $description != '')
                                    <div class="desc"> <p>{!! str_limit(ucfirst($description), $limit = 96, $end = '...') !!}</p> </div>
                                @endif
                                <div class="morebtn">
                                    <a href="{{ $recordLinkUrl }}" title="Read More">Read More</a>
                                </div> --}}

                                @if (isset($news['fkIntDocId']) && !empty($news['fkIntDocId']))
                                    @php
                                        $docsAray = explode(',', $news['fkIntDocId']);
                                        $docObj = App\Document::getDocDataByIds($docsAray);
                                    @endphp
                                    @if (count($docObj) > 0)
                                        <div class="cms mt-auto pt-5 d-none">
                                            @foreach($docObj as $key => $val)
                                                @php
                                                    $CDN_PATH = Config::get('Constant.CDN_PATH');
                                                    if ($val->fk_folder > 0 && !empty($val->foldername)) {
                                                        if ($val->varDocumentExtension == 'pdf' || $val->varDocumentExtension == 'PDF') {
                                                            $docURL = route('viewFolderPDF', ['dir' => 'documents', 'foldername' => $val->foldername, 'filename' => $val->txtSrcDocumentName . '.' . $val->varDocumentExtension]);
                                                        } else {
                                                            $docURL = $CDN_PATH . 'documents/' . $val->foldername . '/' . $val->txtSrcDocumentName . '.' . $val->varDocumentExtension;
                                                        }
                                                    } else {
                                                        if ($val->varDocumentExtension == 'pdf' || $val->varDocumentExtension == 'PDF') {
                                                            $docURL = route('viewPDF', ['dir' => 'documents', 'filename' => $val->txtSrcDocumentName . '.' . $val->varDocumentExtension]);
                                                        } else {
                                                            $docURL = $CDN_PATH . 'documents/' . $val->txtSrcDocumentName . '.' . $val->varDocumentExtension;
                                                        }
                                                    }
                                                @endphp
                                                <a href="{{ $docURL }}" data-viewid="{{ $val->id }}" data-viewtype="view" title="{{ $val->txtDocumentName }}" class="ac-btn ac-btn-primary ac-small docHitClick" target="_blank">Download</a>
                                            @endforeach
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>

                @endforeach
            </div>

            @if(Request::segment(1) != '' && isset($data['paginatehrml']) && $data['paginatehrml'] == true)
                @if($data['news']->total() > $data['news']->perPage())
                    <div id="paginationSection">
                        @include('partial.pagination', ['paginator' => $data['news']->links()['paginator'], 'elements' => $data['news']->links()['elements']['0']])
                    </div>
                @endif
            @endif
        </div>
    @else
        <div class="row">
            <div class="col-12 text-center">
                <div class="nqtitle n-fw-800 n-lh-110 text-uppercase">No data found</div>
                <div class="n-fs-20 n-fw-500 n-lh-130">Please reset filter to see the data.</div>
            </div>
        </div>
    @endif
</div>
