@if (!empty($data['document']))
    @php
        $docsAray = explode(',', $data['document']);
        $docObj = App\Document::getDocDataByIds($docsAray);
    @endphp
    @if (count($docObj) > 0)
        @foreach ($docObj as $key => $val)
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
            @if (isset($data['doc_date_time']) && !empty($data['doc_date_time']))
                <article class="-items doc-icon-top w-100 n-bs-1 n-pa-20 n-p-relative n-bgc-white-500">
                    @if ($val->varDocumentExtension == 'pdf' || $val->varDocumentExtension == 'PDF')
                        @php $blank = 'target="_blank"'; @endphp
                    @else
                        @php $blank = ''; @endphp
                    @endif
                    @php
                        if ($val->varDocumentExtension == 'pdf' || $val->varDocumentExtension == 'PDF') {
                            $blank = 'target="_blank"';
                        } else {
                            $blank = '';
                        }
                    @endphp
                    <div class="documents align-items-start">
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

                            <a class="-link n-ah-a-500 docHitClick" href="{{ $docURL }}" download="" data-viewid="{{ $val->id }}" data-viewtype="download"
                                title="{{ (isset($data['caption']) && !empty($data['caption'])?$data['caption']:$val->txtDocumentName) }}">{{ (isset($data['caption']) && !empty($data['caption'])?$data['caption']:$val->txtDocumentName) }}</a>
                            <ul
                                class="nqul d-flex justify-content-between align-items-center n-mt-15 n-fs-15 n-fw-500 n-ff-2 n-fc-a-500">
                                @if (isset($data['doc_date_time']) && !empty($data['doc_date_time']))
                                    <li class="nq-svg align-items-center d-flex">
                                        <i class="n-icon" data-icon="s-calendar"></i>
                                        {{ date('M', strtotime($data['doc_date_time'])) }}
                                        {{ date('d', strtotime($data['doc_date_time'])) }},
                                        {{ date('Y', strtotime($data['doc_date_time'])) }}
                                    </li>
                                @endif
                                 @if ($val->varDocumentExtension == 'pdf' || $val->varDocumentExtension == 'PDF')
                                    <li><a href="{{ $docURL }}" data-viewid="{{ $val->id }}" data-viewtype="view" title="View" class="ac-btn ac-btn-primary ac-small docHitClick" target="_blank">View</a></li>
                                @else
                                    <li><a href="{{ $docURL }}" data-viewid="{{ $val->id }}" data-viewtype="download" download="" title="Download" class="ac-btn ac-btn-primary ac-small docHitClick" target="_blank">Download</a></li>
                                @endif
                          
                            </ul>
                        </div>
                    </div>
                </article>
            @else
                <div class="documents">
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
                    <div>
                        <a class="-link n-ah-a-500 docHitClick" href="{{ $docURL }}" data-viewid="{{ $val->id }}" data-viewtype="view" title="{{ (isset($data['caption']) && !empty($data['caption'])?$data['caption']:$val->txtDocumentName) }}" download>
                            {{ (isset($data['caption']) && !empty($data['caption'])?$data['caption']:$val->txtDocumentName) }}
                        </a>
                    </div>
                </div>
            @endif
        @endforeach
    @endif
@endif
<script>
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
function setDocumentHitCounter(docId, counterType) {
    if (docId != null) {
        
            $.ajax({
                type: 'POST',
                url: site_url + "/setDocumentHitcounter",
                data: {
                    "docId": docId,
                    "counterType": counterType
                },
                success: function (data) {

                },
                complete: function () {

                },
                error: function (data) {
                },
            });
        
    }
}

$(document).on("click", ".docHitClick", function () {
    var docViewId = $(this).data('viewid');
    var docViewType = $(this).data('viewtype');
    
    if (docViewId != "" && docViewType != "") {
        setDocumentHitCounter(docViewId, docViewType);
    }
}); 

</script>
@if(!Request::ajax())
    @section('footer_scripts')
        <script src="{{ $CDN_PATH.'assets/libraries/bootstrap-select-master/1.13.14/js/bootstrap-select.min.js' }}" defer></script>
    @endsection

@endif