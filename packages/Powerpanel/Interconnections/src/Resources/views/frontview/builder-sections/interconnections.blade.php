@if(isset($data['interconnections']) && !empty($data['interconnections']) && count($data['interconnections']) > 0)
    @php
        $class = '';
        if(isset($data['class'])){
            $class = $data['class'];
        }
    @endphp
    <div class="row n-mt-25 {{ $class }}">
        @foreach($data['interconnections'] as $interconnections)
        @php
            $docsAray = explode(',',$interconnections->fkIntDocId);
            $docObj   = App\Document::getDocDataByIds($docsAray);					
        @endphp
        @if(count($docObj) > 0)
            <div class="col-lg-6 d-flex n-gap-2 n-gapp-lg-3 n-gapm-lg-2" data-aos="zoom-in">

                    @php
                    if ($docObj[0]->fk_folder > 0 && !empty($docObj[0]->foldername)) {
                        $docURL = $CDN_PATH . 'documents/' . $docObj[0]->foldername . '/' . $docObj[0]->txtSrcDocumentName . '.' . $docObj[0]->varDocumentExtension;
                        if($docObj[0]->varDocumentExtension == 'pdf' || $docObj[0]->varDocumentExtension == 'PDF'){    
                        //$docURL = 'viewPDF/documents/'.$docObj[0]->foldername.'/'.$docObj[0]->txtSrcDocumentName . '.' . $docObj[0]->varDocumentExtension;
                        $docURL = route('viewFolderPDF',['dir' => 'documents' ,'foldername' => $docObj[0]->foldername,'filename' => $docObj[0]->txtSrcDocumentName . '.' . $docObj[0]->varDocumentExtension]);
                        } else {
                        $docURL = $CDN_PATH . 'documents/' . $docObj[0]->foldername . '/' . $docObj[0]->txtSrcDocumentName . '.' . $docObj[0]->varDocumentExtension;
                        }
                        } else {
                        if($docObj[0]->varDocumentExtension == 'pdf' || $docObj[0]->varDocumentExtension == 'PDF') {
                        //$docURL = 'viewPDF/documents/'. $docObj[0]->txtSrcDocumentName . '.' . $docObj[0]->varDocumentExtension;
                        $docURL = route('viewPDF',['dir' => 'documents','filename' => $docObj[0]->txtSrcDocumentName.'.'.$docObj[0]->varDocumentExtension]);
                        }
                        else{
                        $docURL = $CDN_PATH . 'documents/'. $docObj[0]->txtSrcDocumentName . '.' . $docObj[0]->varDocumentExtension;
                        }
                    }
                @endphp
                    <article class="-items w-100 n-bs-1 n-pa-20 n-p-relative n-bgc-white-500">
                        <div class="documents align-items-start">
                            @if ($docObj[0]->varDocumentExtension == 'pdf' || $docObj[0]->varDocumentExtension == 'PDF')
                                <div class="-doct-img">
                                    <i class="n-icon" data-icon="s-pdf"></i>
                                    <i class="n-icon" data-icon="s-download"></i>
                                </div>
                            @elseif($docObj[0]->varDocumentExtension == 'doc' || $docObj[0]->varDocumentExtension == 'docx')
                                <div class="-doct-img">
                                    <i class="n-icon" data-icon="s-doc"></i>
                                    <i class="n-icon" data-icon="s-download"></i>
                                </div>
                            @elseif($docObj[0]->varDocumentExtension == 'xls' || $docObj[0]->varDocumentExtension == 'xlsx')
                                <div class="-doct-img">
                                    <i class="n-icon" data-icon="s-xls"></i>
                                    <i class="n-icon" data-icon="s-download"></i>
                                </div>
                            @else
                                <div class="-doct-img">
                                    <i class="n-icon" data-icon="s-pdf"></i>
                                    <i class="n-icon" data-icon="s-download"></i>
                                </div>
                            @endif
                            <div class="w-100">
                                <a class="-link n-ah-a-500 docHitClick" href="{{ $docURL }}" data-viewid="{{ $docObj[0]->id }}" data-viewtype="download" download="" title="">{{ $interconnections->varTitle }}</a>
                                <ul class="nqul d-flex justify-content-between align-items-center n-mt-15 n-fs-15 n-fw-500 n-ff-2 n-fc-a-500">
                                    <li class="nq-svg align-items-center d-flex">
                                        <i class="n-icon" data-icon="s-calendar"></i> {{ date('M',strtotime($interconnections->dtDateTime)) }} {{ date('d',strtotime($interconnections->dtDateTime)) }}, {{ date('Y',strtotime($interconnections->dtDateTime)) }}
                                    </li>
                                   @if ($docObj[0]->varDocumentExtension == 'pdf' || $docObj[0]->varDocumentExtension == 'PDF')
                                        <li><a href="{{ $docURL }}" data-viewid="{{ $docObj[0]->id }}" data-viewtype="view" title="View" class="ac-btn ac-btn-primary ac-small docHitClick" target="_blank">View</a></li>
                                    @else
                                        <li><a href="{{ $docURL }}" data-viewid="{{ $docObj[0]->id }}" data-viewtype="download" download="" title="Download" class="ac-btn ac-btn-primary ac-small docHitClick" target="_blank">Download</a></li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </article>
                </div>
            @endif
        @endforeach
    </div>
    @if(Request::segment(1) != '' && isset($data['paginatehrml']) && $data['paginatehrml'] == true)
        @if($data['interconnections']->total() > $data['interconnections']->perPage())
            <div class="n-mt-lg-80 n-mt-40" data-aos="fade-up" id="paginationSection">
                @include('partial.pagination', ['paginator' => $data['interconnections']->links()['paginator'], 'elements' => $data['interconnections']->links()['elements']['0']])
            </div>
        @endif
    @endif
    @else
    <div class="row">
        <div class="col-12 text-center" data-aos="fade-up">
            <div class="nqtitle n-fw-800 n-lh-110 text-uppercase">No data found</div>
            <div class="desc n-fs-20 n-fw-500 n-lh-130">Please reset filter or load page to see the data.</div>
        </div>  
    </div>
@endif