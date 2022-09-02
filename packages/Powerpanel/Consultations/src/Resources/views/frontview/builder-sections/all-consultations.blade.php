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

        @foreach($data['consultations'] as $key => $consultations) 
            @php
                $docObj   = App\Document::getDocDataById($consultations->fkIntDocId);
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
                                    $CDN_PATH = Config::get('Constant.CDN_PATH');
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
                                    <a class="-link n-ah-a-500 docHitClick" href="{{$docURL }}" data-viewid="{{ $val->id }}" data-viewtype="download" download="" title="{{$consultations->varTitle}}">{{$consultations->varTitle}}</a>
                                    <ul class="nqul d-flex justify-content-between align-items-center n-mt-15 n-fs-15 n-fw-500 n-ff-2 n-fc-a-500">
                                        <li class="nq-svg align-items-center d-flex"><i class="n-icon" data-icon="s-calendar"></i> 
                                            <div style="margin-left: 10px;">
                                            {{ date('d',strtotime($consultations->dtDateTime)) }} 
                                            {{ date('M',strtotime($consultations->dtDateTime)) }}, 
                                            {{ date('Y',strtotime($consultations->dtDateTime)) }}
                                            </div>
                                        </li>
                                        @if ($val->varDocumentExtension == 'pdf' || $val->varDocumentExtension == 'PDF')
                                            <li><a href="{{ $docURL }}" data-viewid="{{ $val->id }}" data-viewtype="view" title="View" class="ac-btn ac-btn-primary ac-small docHitClick" target="_blank">View</a></li>
                                        @else
                                            <li><a href="{{ $docURL }}" data-viewid="{{ $val->id }}" data-viewtype="download" download="" title="Download" class="ac-btn ac-btn-primary ac-small docHitClick" target="_blank">Download</a></li>
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

    @if(Request::segment(1) != '' && isset($data['paginatehrml']) && $data['paginatehrml'] == true)
        @if($data['consultations']->total() > $data['consultations']->perPage())
            <div class="n-mt-lg-80 n-mt-40" data-aos="fade-up" id="paginationSection">
                @include('partial.pagination', ['paginator' => $data['consultations']->links()['paginator'], 'elements' => $data['consultations']->links()['elements']['0']])
            </div>
        @endif
    @endif
@endif