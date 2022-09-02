@php
$newsurl = '';
@endphp

@if (isset($data['publicRecords']) && !empty($data['publicRecords']) && count($data['publicRecords']) > 0)
    @php
        $cols = 'col-md-4 col-sm-4 col-xs-12';
        $grid = '3';
        if ($data['cols'] == 'grid_2_col') {
            $cols = 'col-xl-6';
            $grid = '2';
        } elseif ($data['cols'] == 'grid_3_col') {
            $cols = 'col-xl-4';
            $grid = '3';
        } elseif ($data['cols'] == 'grid_4_col') {
            $cols = 'col-xl-3';
            $grid = '4';
        }
        
        if (isset($data['class'])) {
            $class = $data['class'];
        }
        if (isset($data['paginatehrml']) && $data['paginatehrml'] == true) {
            $pcol = $cols;
        } else {
            $pcol = 'item';
        }
    @endphp

    @if (isset($data['desc']) && $data['desc'] != '')
        <div class="row">
            <div class="col-12 cms n-mb-30" data-aos="fade-up">
                <p>{!! $data['desc'] !!}</p>
            </div>
        </div>
    @endif

    <div class="row n-mt-25  {{ $class }}" data-grid="{{ $grid }}">
        @foreach ($data['publicRecords'] as $publicRecord)
            @php
                $recordLinkUrl = '';
                
            @endphp

            @if ($data['cols'] == 'list')
                <div class="col-md-12 col-sm-12 col-xs-12 animated fadeInUp">
                @else
                    <div class="{{ $pcol }} d-flex n-gap-2 n-gapp-lg-3 n-gapm-lg-2" data-aos="zoom-in">
            @endif

            @php
                $docsAray = explode(',', $publicRecord->fkIntDocId);
                $docObj = App\Document::getDocDataByIds($docsAray);
            @endphp
            @if (count($docObj) > 0)
                @php
                    if ($docObj[0]->varDocumentExtension == 'pdf' || $docObj[0]->varDocumentExtension == 'PDF') {
                        $blank = 'target="_blank"';
                    } else {
                        $blank = '';
                    }
                    if ($docObj[0]->varDocumentExtension == 'pdf' || $docObj[0]->varDocumentExtension == 'PDF') {
                        $icon = 'pdf-1.svg';
                    } elseif ($docObj[0]->varDocumentExtension == 'doc' || $docObj[0]->varDocumentExtension == 'docx') {
                        $icon = 'doc.svg';
                    } elseif ($docObj[0]->varDocumentExtension == 'xls' || $docObj[0]->varDocumentExtension == 'xlsx') {
                        $icon = 'xls.svg';
                    } else {
                        $icon = 'doc.svg';
                    }
                    
                @endphp
            @endif
            <article class="-items w-100 n-bs-1 n-pa-20 n-p-relative n-bgc-white-500">
                <div class="documents align-items-start">
                    <div class="-doct-img">
                        <i class="n-icon" data-icon="s-pdf"></i>
                        <i class="n-icon" data-icon="s-download"></i>
                    </div>
                    <div>
                        <a class="-link n-ah-a-500"
                            href="{{ $CDN_PATH . 'documents/' . $docObj[0]->txtSrcDocumentName . '.' . $docObj[0]->varDocumentExtension }}"
                            download="" title="">{{ $publicRecord->varTitle }}</a>
                        <span>Author: {{ $publicRecord->varAuthor }}</span>
                        <ul
                            class="nqul d-flex justify-content-between align-items-center n-mt-15 n-fs-15 n-fw-500 n-ff-2 n-fc-a-500">
                            <li class="nq-svg align-items-center d-flex"><img class="svg"
                                    src="{{ $CDN_PATH . 'assets/images/icon/calendar.svg' }}" alt="calendar">
                                {{ date('M', strtotime($publicRecord->dtDateTime)) }}
                                {{ date('d', strtotime($publicRecord->dtDateTime)) }},
                                {{ date('Y', strtotime($publicRecord->dtDateTime)) }}</li>
                            <li><a href="{{ $CDN_PATH . 'documents/' . $docObj[0]->txtSrcDocumentName . '.' . $docObj[0]->varDocumentExtension }}"
                                    title="View" class="ac-btn ac-btn-primary ac-small" target="_blank">View
                                   </a></li>
                        </ul>
                    </div>
                </div>
            </article>
    </div>
@endforeach
</div>
@if (Request::segment(1) != '' && isset($data['paginatehrml']) && $data['paginatehrml'] == true)
    @if ($data['publicRecords']->total() > $data['publicRecords']->perPage())
        <div class="n-mt-lg-80 n-mt-40" data-aos="fade-up" id="paginationSection">
            @include('partial.pagination', ['paginator' => $data['publicRecords']->links()['paginator'], 'elements' =>
            $data['publicRecords']->links()['elements']['0']])
        </div>
    @endif
@endif
@endif
