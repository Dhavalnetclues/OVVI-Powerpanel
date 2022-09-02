@if(!Request::ajax())
@extends('layouts.app')
@section('content')
@include('layouts.inner_banner')
@endif

<section class="inner-page-gap search ">
    {{-- @include('layouts.share-email-print') --}}
    <div class="container">
        {{--<div class="row">
            <div class="col-12">
                <h2 class="nqtitle-small n-fc-black-500">Search Results for <span class="n-fc-a-500">{{$searchTerm}}</span></h2>
                <div class="n-fs-18 n-fw-500 n-fc-black-500 n-lh-110">About {{$searchFoundCounter}} results</div>
            </div>
        </div>--}}


        <div class="row justify-content-center mb-4">
            {{-- <div class="col-lg-6">
                <div class="row g-2">
                    <div class="col">
                        <div class="serachbar">
                            <input type="text" class="form-control form-control-lg bg-light border-light" placeholder="Search here.." value="Search here">
                           
                        </div>
                    </div>
                    <div class="col-auto p-0">
                        <button type="submit" class="btn btn-primary btn-lg waves-effect waves-light"><i class="fa fa-search" aria-hidden="true"></i> Search</button>
                    </div>
                </div>
            </div> --}}
            <div class="col-lg-12">
                <h6 class="search-text">About {{$searchFoundCounter}} results "<span>Search value</span> "</h5>
            </div>
        </div>

        <div class="row n-mt-35 justify-content-center">
            @foreach($searchResults as $key => $result)

                @if($result->fkIntDocId != 'na')
                    @php
                    $docsAray = explode(',',$result->fkIntDocId);
                    $docObj   = App\Document::getDocDataByIds($docsAray);
                    @endphp
                    @if($docObj->count() > 0)
                    <div class="cms">
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
                            <!-- <div class="documents">
                                @if ($val->varDocumentExtension == 'pdf' || $val->varDocumentExtension == 'PDF')
                                <div class="-doct-img">
                                    <i class="n-icon" data-icon="s-pdf"></i>
                                    <i class="n-icon" data-icon="s-download"></i>
                                </div>
                                @elseif($val->varDocumentExtension == 'doc' ||
                                $val->varDocumentExtension == 'docx')
                                <div class="-doct-img">
                                    <i class="n-icon" data-icon="s-doc"></i>
                                    <i class="n-icon" data-icon="s-download"></i>
                                </div>
                                @elseif($val->varDocumentExtension == 'xls' ||
                                $val->varDocumentExtension == 'xlsx')
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
                                    <a class="-link n-ah-a-500 docHitClick" href="{{ $docURL }}" download title="{{ $val->txtDocumentName }}">{{ $val->txtDocumentName }}</a>
                                </div>
                            </div> -->
                        @endforeach
                    </div>
                    @endif
                @endif

                @php
                    if(isset($result->fkIntImgId) && !empty($result->fkIntImgId) && $result->fkIntImgId != 'na') {
                        $image = App\Helpers\resize_image::resize($result->fkIntImgId);
                        $isImage = true;
                        $colClass = "col-sm-10 col-9 n-pl-15";
                    } else {
                        $image = '';
                        $isImage = false;
                        $colClass = "col-12";
                    }

                    if(isset($result->slug) && !empty($result->slug) && $result->slug != 'na' && (isset($result->pageAlias) && !empty($result->pageAlias)) ) {
                        if($result->varModuleName == 'forms-and-fees'){
                            $url = env('APP_URL') . $result->pageAlias;
                        }else{
                            $url = env('APP_URL') . $result->pageAlias . '/' . $result->slug;
                        }
                    } else {
                        if(isset($result->pageAlias) && !empty($result->pageAlias)) {


                            if(isset($result->fkIntDocId) && !empty($result->fkIntDocId) && $result->fkIntDocId != 'na'){
                                $url = $docURL;
                            }else{
                                if($result->varSector != 'ofreg'){
                                    if($result->moduleId != '3'){
                                        if($result->varModuleName == 'decision'){
                                            $url = env('APP_URL') . $result->varSector . '/' .$result->varModuleName.'s';
                                        }elseif($result->varModuleName == 'publications'){
                                            $url = env('APP_URL') . $result->varSector . '/'. $result->varModuleName;
                                        }else{
                                            $url = env('APP_URL') . $result->pageAlias;
                                        }
                                    }else{
                                        $url = env('APP_URL'). $result->varSector . '/' . $result->pageAlias;
                                    }
                                }else{
                                    if($result->varModuleName == 'decision'){
                                        $url = env('APP_URL') . $result->varModuleName.'s';
                                    }elseif($result->varModuleName == 'publications'){
                                        $url = env('APP_URL') . $result->varModuleName;
                                    }else{
                                        $url = env('APP_URL') . $result->pageAlias;
                                    }
                                }
                            }

                        } else {
                            $url = '';
                        }
                    }

                @endphp
                <div class="col-lg-6 n-gapp-3 n-gapm-lg-2 d-flex" data-aos="fade-up">
                    <article class="-items n-bs-1 n-pa-20 w-100 d-flex flex-column">
                        <div class="row no-gutters">
                            @if($isImage)
                                <div class="col-sm-2 col-3">
                                    <div class="thumbnail-container" data-thumb="100%">
                                        <div class="thumbnail">
                                            <img src="{{ $image }}" alt="{{ $result->term }}" title="{{ $result->term }}">
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="{{$colClass}}">
                                <div class="n-fs-14 n-fw-500 n-fc-dark-500 n-lh-130 n-mb-10 text-uppercase">{{$result->varSector}}</div>
                                <h2 class="search-title"><a href="{{$url}}" title="{{$result->term}}" target="_blank">{{$result->term}}</a></h2>
                                @if(isset($result->varShortDescription) && !empty($result->varShortDescription) && $result->varShortDescription !='na')
                                    <p class="mt-2 n-fs-14">
                                        {{$result->varShortDescription}}
                                    </p>
                                @endif
                                @if(isset($result->startDate) && !empty($result->startDate))
                                    <div class="n-fs-14 n-fw-500 n-fc-dark-500 n-lh-130 mt-auto n-pt-15">
                                        {{ date('M',strtotime($result->startDate)) }} {{ date('d',strtotime($result->startDate)) }}, {{ date('Y',strtotime($result->startDate)) }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </article>
                </div>
            @endforeach
        </div>
            {{--pagination--}}
        {{-- <div class="row">
            <div class="col-12 n-mt-lg-80 n-mt-40">
                <ul class="pagination justify-content-center align-content-center">
                    <li class="page-item">
                        <a class="page-link" href="#" title="Previous">
                            <i class="n-icon" data-icon="s-pagination"></i>
                        </a>
                    </li>
                    <li class="page-item"><a class="page-link" href="#" title="1">1</a></li>
                    <li class="page-item active"><a class="page-link" href="#" title="2">2</a></li>
                    <li class="page-item"><a class="page-link" href="#" title="3">3</a></li>
                    <li class="page-item"><a class="page-link" href="#" title="4">4</a></li>
                    <li class="page-item"><a class="page-link" href="#" title="5">5</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#" title="Next">
                            <i class="n-icon" data-icon="s-pagination"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div> --}}
    </div>
</section>

@if(!Request::ajax())
@section('footer_scripts')


@endsection
@endsection
@endif