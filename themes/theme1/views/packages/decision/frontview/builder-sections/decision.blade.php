
@if(isset($data['decision']) && !empty($data['decision']) && count($data['decision']) > 0)

<div class="row">
    @foreach($data['decision'] as $key => $decision) 


    @if($key%2==0)
    <div class="col-lg-6 d-flex n-gap-2 n-gapp-lg-3 n-gapm-lg-2" data-aos="fade-left">
        @else
        <div class="col-lg-6 d-flex n-gap-2 n-gapp-lg-3 n-gapm-lg-2" data-aos="fade-right">
            @endif
            @if(isset($decision->fkIntDocId) && !empty($decision->fkIntDocId))
            @php
            $docObj   = App\Document::getDocDataById($decision->fkIntDocId);
            $key = $key+1;
            @endphp
            <article class="-items w-100 n-bs-1 n-pa-20 n-p-relative n-bgc-white-500">
                <div class="documents align-items-start">
                    @if(count($docObj) > 0)
                    @foreach($docObj as $key => $val)
                    @php
                    $CDN_PATH = Config::get('Constant.CDN_PATH');
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
                        <a class="-link n-ah-a-500 docHitClick" href="{{$docURL }}" data-viewid="{{ $val->id }}" data-viewtype="download" download="" title="{{$decision->varTitle}}">{{$decision->varTitle}}</a>
                        <ul class="nqul d-flex justify-content-between align-items-center n-mt-15 n-fs-15 n-fw-500 n-ff-2 n-fc-a-500">
                            <li class="nq-svg align-items-center d-flex"><i class="n-icon" data-icon="s-calendar"></i> {{ date('d',strtotime($decision->DecisionDate)) }} {{ date('M',strtotime($decision->DecisionDate)) }}, {{ date('Y',strtotime($decision->DecisionDate)) }}</li>
                            @if ($val->varDocumentExtension == 'pdf' || $val->varDocumentExtension == 'PDF')
                            <li><a href="{{ $docURL }}" data-viewid="{{ $val->id }}" data-viewtype="view" title="View" class="ac-btn ac-btn-primary ac-small docHitClick" target="_blank">View</a></li>
                            @else
                            <li><a href="{{ $docURL }}" data-viewid="{{ $val->id }}" data-viewtype="download" download="" title="Download" class="ac-btn ac-btn-primary ac-small docHitClick" target="_blank">Download</a></li>
                            @endif
                        </ul>
                    </div>
                    @endforeach
                    @endif
                </div>
            </article>
            @else
             @if(isset($decision->varLink) && !empty($decision->varLink))
             <article class="-items w-100 n-bs-1 n-pa-20 n-p-relative n-bgc-white-500">
                 <div class="documents align-items-start">
                     <div class="-doct-img">
                         <i class="n-icon" data-icon="s-pdf"></i>
                     </div>
                     <div class="w-100">
                         <a class="-link n-ah-a-500" href="{{ $decision->varLink }}"  title="{{$decision->varTitle}}">{{$decision->varTitle}}</a>
                         <ul class="nqul d-flex justify-content-between align-items-center n-mt-15 n-fs-15 n-fw-500 n-ff-2 n-fc-a-500">
                             <li class="nq-svg align-items-center d-flex"><i class="n-icon" data-icon="s-calendar"></i> {{ date('d',strtotime($decision->DecisionDate)) }} {{ date('M',strtotime($decision->DecisionDate)) }}, {{ date('Y',strtotime($decision->DecisionDate)) }}</li>
                             <li><a href="{{ $decision->varLink }}"  title="View" class="ac-btn ac-btn-primary ac-small" target="_blank">View</a></li>

                         </ul>
                     </div>

                 </div>
             </article>
                @endif
            @endif
            @if($key%2==0)
        </div>
        @else
    </div>
    @endif
    @endforeach
</div>
@endif


@if(Request::segment(1) != '' && isset($data['paginatehrml']) && $data['paginatehrml'] == true)

@if($data['decision']->total() > $data['decision']->perPage())

<div class="n-mt-lg-80 n-mt-40" data-aos="fade-up" id="paginationSection">
    @include('partial.pagination', ['paginator' => $data['decision']->links()['paginator'], 'elements' => $data['decision']->links()['elements']['0']])
</div>
@endif
@endif