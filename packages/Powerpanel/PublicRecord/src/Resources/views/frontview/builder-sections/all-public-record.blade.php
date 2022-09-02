@if(isset($data['publicRecords']) && !empty($data['publicRecords']) && count($data['publicRecords']) > 0)
<div class="row">
    @foreach($data['publicRecords'] as $key => $publicRecords) 
        @php
            $docObj   = App\Document::getDocDataById($publicRecords->fkIntDocId);
            $key = $key+1;
        @endphp
        @if(count($docObj) > 0)
            @if($key%2 == 0)
                @php $dataAOS = 'fade-left' @endphp          
            @else
                @php $dataAOS = 'fade-right'  @endphp 
            @endif
            <div class="col-lg-6 d-flex n-gap-2 n-gapp-lg-3 n-gapm-lg-2" data-aos="{{ $dataAOS }}">
                <article class="-items w-100 n-bs-1 n-pa-20 n-p-relative n-bgc-white-500">
                    <div class="documents align-items-start">
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
                            @if ($val->varDocumentExtension == 'pdf' || $val->varDocumentExtension == 'PDF')
                            <div class="-doct-img">
                                <i class="n-icon" data-icon="s-pdf"></i>
                                <i class="n-icon" data-icon="s-download"></i>
                            </div>
                            @elseif($val->varDocumentExtension == 'doc' || $val->varDocumentExtension == 'docx')
                            <div class="-doct-img">
                                <i class="n-icon" data-icon="s-doc"></i>
                                <i class="n-icon" data-icon="s-download"></i>
                            </div>
                            @elseif($val->varDocumentExtension == 'xls' || $val->varDocumentExtension == 'xlsx')
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
                                <a class="-link n-ah-a-500 docHitClick" href="{{$docURL }}" download="" title="{{$publicRecords->varTitle}}">{{$publicRecords->varTitle}}</a>
                                <div class="-tags n-fs-12 n-ff-2 n-fc-white-500 n-mt-5 d-inline-flex n-bgc-a-500">{{$publicRecords->varAuthor}}</div>
                                <ul class="nqul d-flex justify-content-between align-items-center n-mt-15 n-fs-15 n-fw-500 n-ff-2 n-fc-a-500">
                                    <li class="nq-svg align-items-center d-flex"><i class="n-icon" data-icon="s-calendar"></i> {{ date('d',strtotime($publicRecords->dtDateTime)) }} {{ date('M',strtotime($publicRecords->dtDateTime)) }}, {{ date('Y',strtotime($publicRecords->dtDateTime)) }}</li>
                                    @if ($val->varDocumentExtension == 'pdf' || $val->varDocumentExtension == 'PDF')
                                        <li><a href="{{$docURL }}" data-viewid="{{ $val->id }}" data-viewtype="view" title="View" class="ac-btn ac-btn-primary ac-small docHitClick" target="_blank">View</a></li>
                                    @else
                                        <li><a href="{{$docURL }}" data-viewid="{{ $val->id }}" data-viewtype="download" download="" title="Download" class="ac-btn ac-btn-primary ac-small docHitClick" target="_blank">Download</a></li>
                                    @endif
                                </ul>
                            </div>
                        @endforeach
                    </div>
                </article>  
            </div>
        @endif
    @endforeach
</div>
@endif

@if(Request::segment(1) != '' && isset($data['paginatehrml']) && $data['paginatehrml'] == true)
    @if($data['publicRecords']->total() > $data['publicRecords']->perPage())
    <div id="paginationSection">
        @include('partial.pagination', ['paginator' => $data['publicRecords']->links()['paginator'], 'elements' => $data['publicRecords']->links()['elements']['0']])
    </div>
    @endif
@endif