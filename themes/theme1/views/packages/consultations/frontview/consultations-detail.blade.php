@if(!Request::ajax())
    @extends('layouts.app')
    @section('content')
    @include('layouts.inner_banner')
@endif
<section class="inner-page-gap news-detail consultations-detail">
    @include('layouts.share-email-print')
    <div class="container">
        <div class="row">
            <div class="col-xl-3 order-xl-0 order-2 n-mt-xl-0 n-mt-40 left-panel" data-aos="fade-up">
                <div class="row n-mr-xl-15">
                    @if($latestConsultations->count() > 0)
                        <div class="col-xl-12">
                            <article>
                                <div class="nqtitle-small lp-title text-uppercase n-mb-25">Latest Consultations</div>
                                <div class="row">
                                    @foreach($latestConsultations as $consultation)
                                        @php
                                            if(isset(App\Helpers\MyLibrary::getFront_Uri('consultations')['uri'])){
                                                $moduelFrontPageUrl = App\Helpers\MyLibrary::getFront_Uri('consultations')['uri'];
                                                $moduleFrontWithCatUrl = ($consultation->varAlias != false ) ? $moduelFrontPageUrl . '/' . $consultation->varAlias : $moduelFrontPageUrl;
                                                $categoryRecordAlias = App\Helpers\Mylibrary::getRecordAliasByModuleNameRecordId('consultations',$consultation->txtCategories);
                                                $recordLinkUrl = $moduleFrontWithCatUrl.'/'.$consultation->alias->varAlias;
                                            } else {
                                                $recordLinkUrl = '';
                                            }
                                        @endphp
                                        <div class="col-xl-12 col-lg-6 -border">
                                            <ul class="nqul justify-content-between -tag d-flex align-items-center">
                                                <li>
                                                    <div class="-nimg">
                                                        <img src="{{ $CDN_PATH.'assets/images/logo.png' }}" alt="{{$consultation->varSector}}" title="{{$consultation->varSector}}">
                                                    </div>
                                                    <div class="-nlabel n-fc-white-500 n-fs-16 n-fw-800 text-uppercase">{{$consultation->varSector}}</div>
                                                </li>
                                            </ul>
                                            <a class="d-inline-block n-mt-15 n-fs-16 n-ff-2 n-fw-600 n-lh-130 n-fc-black-500 n-ah-a-500" href="{{$recordLinkUrl}}" title="{{$consultation->varTitle}}">{{$consultation->varTitle}}</a>
                                            <ul class="nqul -tag d-flex align-items-center n-fs-12 n-fw-500 n-fc-gray-500 n-ff-2">
                                                @if(isset($consultation->dtDateTime) && !empty($consultation->dtDateTime))
                                                    <li class="nq-svg d-flex align-items-center n-lh-120">
                                                        <i class="n-icon" data-icon="s-calendar"></i>
                                                        Launch Date:<br> {{ date('M',strtotime($consultation->dtDateTime)) }} {{ date('d',strtotime($consultation->dtDateTime)) }}, {{ date('Y',strtotime($consultation->dtDateTime)) }}
                                                    </li>
                                                @endif
                                                @if(isset($consultation->dtEndDateTime) && !empty($consultation->dtEndDateTime))
                                                    <li class="nq-svg d-flex align-items-center n-lh-120">
                                                        <i class="n-icon" data-icon="s-calendar"></i>
                                                        Closing Date:<br> {{ date('M',strtotime($consultation->dtEndDateTime)) }} {{ date('d',strtotime($consultation->dtEndDateTime)) }}, {{ date('Y',strtotime($consultation->dtEndDateTime)) }}
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    @endforeach
                                </div>
                            </article>
                        </div>
                    @endif    
                    @if(isset($tags) && !empty($tags))
                        <div class="col-xl-12">        
                            <article class="n-mt-xl-50 n-mt-md-0 n-mt-25">
                                <div class="nqtitle-small lp-title text-uppercase n-mb-25">Consultations Tags</div>
                                <div class="s-tags">
                                    <ul class="nqul d-flex flex-wrap n-fs-14 n-ff-2 n-fw-600 n-fc-white-500">
                                        @foreach($tags as $tag)
                                            <li><a href="javascript:void(0)" class="text-uppercase" title="{{$tag}}">{{$tag}}</a></li>
                                        @endforeach                                        
                                    </ul>
                                </div>
                            </article>
                        </div>
                    @endif
                </div>
            </div>
            <div class="col-xl-9" data-aos="fade-up">
                <div class="row">
                    <div class="col-md-6">
                        <ul class="nqul -tag d-flex align-items-center">
                            <li>
                                <div class="-nimg">
                                    <img src="{{ $CDN_PATH.'assets/images/logo.png' }}" alt="{{$consultations->varSector}}" title="{{$consultations->varSector}}">
                                </div>
                                <div class="-nlabel n-fc-white-500 n-fs-16 n-fw-800 text-uppercase">{{$consultations->varSector}}</div>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6 n-tar-md">
                        <ul class="nqul -tag d-inline-flex align-items-center n-fs-12 n-fw-500 n-fc-gray-500 n-ff-2">
                            @if(isset($consultations->dtDateTime) && !empty($consultations->dtDateTime))
                                <li class="nq-svg d-flex align-items-center text-left n-lh-120">
                                    <i class="n-icon" data-icon="s-calendar"></i>
                                    Launch Date:<br> {{ date('M',strtotime($consultations->dtDateTime)) }} {{ date('d',strtotime($consultations->dtDateTime)) }}, {{ date('Y',strtotime($consultations->dtDateTime)) }}
                                </li>
                            @endif
                            @if(isset($consultations->dtEndDateTime) && !empty($consultations->dtEndDateTime))
                                <li class="nq-svg d-flex align-items-center text-left n-lh-120">
                                    <i class="n-icon" data-icon="s-calendar"></i>
                                    Closing Date:<br> {{ date('M',strtotime($consultations->dtEndDateTime)) }} {{ date('d',strtotime($consultations->dtEndDateTime)) }}, {{ date('Y',strtotime($consultations->dtEndDateTime)) }}
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
                <h2 class="nqtitle-small n-fc-black-500 n-fw-600 n-lh-140 n-mv-md-25 n-mb-25">{{ $consultations->varTitle }}</h2>
               

                @if(isset($consultations->txtDescription) && !empty($consultations->txtDescription))
                    <div class="cms">
                        <div class="cms n-mt-25">
                            {!! htmlspecialchars_decode($txtDescription) !!}
                        </div>
                    </div>
                @endif
                @php
                    $docsAray = explode(',',$consultations->fkIntDocId);
                    $docObj   = App\Document::getDocDataByIds($docsAray);
                @endphp
                @if($docObj->count() > 0)
                    <div class="cms">
                        <h2>Documents</h2>
                        @foreach($docObj as $key => $val)
                            @php
                                if ($val->fk_folder > 0 && !empty($val->foldername)) {
                                    $docURL = $CDN_PATH . 'documents/' . $val->foldername . '/' . $val->txtSrcDocumentName . '.' . $val->varDocumentExtension;
                                } else {
                                    $docURL = $CDN_PATH . 'documents/' . $val->foldername . '/' . $val->txtSrcDocumentName . '.' . $val->varDocumentExtension;
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
                            <div class="documents">
                                <div class="-doct-img">
                                    <i class="n-icon" data-icon="s-pdf"></i>
                                    <i class="n-icon" data-icon="s-download"></i>
                                </div>
                                <div>
                                    <a class="-link n-ah-a-500" href="{{ $docURL }}" download title="{{ $val->txtDocumentName }}">{{ $val->txtDocumentName }}</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@if(!Request::ajax())
    @endsection
@endif