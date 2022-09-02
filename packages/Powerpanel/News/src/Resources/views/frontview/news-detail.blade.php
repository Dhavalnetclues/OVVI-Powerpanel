@if (!Request::ajax())
    @extends('layouts.app')
    @section('content')
    @include('layouts.inner_banner')
@endif


@if(isset($news->fkIntImgId) && !null == $news->fkIntImgId)
    <section class="inner-page-gap newsdetails-page">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="img-sec">
                            <div class="thumbnail-container object-fit">
                                <div class="thumbnail">
                                    @php $itemImg = App\Helpers\resize_image::resize($news->fkIntImgId) @endphp
                                    <img src="{{ $itemImg }}" alt="{{ $news->varTitle }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="info-box" data-aos="fade-up">
                            <ul class="nqul justify-content-end -tag d-flex align-items-center">
                                <li class="nq-svg ">
                                    <!-- <i class="n-icon" data-icon="s-calendar"></i> -->
                                    {{ date('M d, Y', strtotime($news->dtDateTime)) }}
                                </li>
                            </ul>

                            {{-- <h3>{{ $news->varTitle }}</h3> --}} 

                            @if (isset($txtDescription) && !empty($txtDescription))
                                <div class="cms">
                                    {!! htmlspecialchars_decode($txtDescription) !!}
                                </div>
                            @else
                                <div class="cms n-mt-25">
                                    <p>{!! htmlspecialchars_decode($news->varShortDescription) !!}</p>
                                </div>
                            @endif

                            @if (isset($news->fkIntDocId) && !empty($news->fkIntDocId))
                                @php
                                    $docsAray = explode(',', $news->fkIntDocId);
                                    $docObj = App\Document::getDocDataByIds($docsAray);
                                @endphp
                                @if (count($docObj) > 0)
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
                                            <a href="{{ $docURL }}" data-viewid="{{ $val->id }}" data-viewtype="download" class="-link docHitClick" title="{{ $val->txtDocumentName }}" target="_blank">
                                            <span><i class="fa fa-calendar" aria-hidden="true"></i><span>{{ $val->txtDocumentName }}</a>
                                        @endforeach
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
    </section>
@else
    <section class="inner-page-gap newsdetails-page">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="info-box">
                            <div class="page-img">
                                <div class="thumbnail-container">
                                    <div class="thumbnail">
                                        <img src="{{ $CDN_PATH.'assets/images/news-default.jpg' }}" alt="">
                                    </div>
                                </div>
                            </div>

                            <ul class="nqul justify-content-end d-flex align-items-end mt-3">
                                <li class="nq-svg ">
                                    {{ date('M d, Y', strtotime($news->dtDateTime)) }}
                                </li>
                            </ul>

                            <!-- <h3 class="nqtitle n-mb-lg-30">{{ $news->varTitle }}</h3> -->

                            @if (isset($txtDescription) && !empty($txtDescription))
                                <div class="cms n-mt-25">
                                    {!! htmlspecialchars_decode($txtDescription) !!}
                                </div>
                            @else
                                <div class="cms n-mt-25">
                                    {!! htmlspecialchars_decode($news->varShortDescription) !!}
                                </div>
                            @endif

                            @if (isset($news->fkIntDocId) && !empty($news->fkIntDocId))
                                @php
                                    $docsAray = explode(',', $news->fkIntDocId);
                                    $docObj = App\Document::getDocDataByIds($docsAray);
                                @endphp
                                @if (count($docObj) > 0)
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
                                            <a href="{{ $docURL }}" data-viewid="{{ $val->id }}" data-viewtype="download" class="docHitClick" title="{{ $val->txtDocumentName }}" target="_blank"><i class="fi"></i>{{ $val->txtDocumentName }}</a>
                                        @endforeach
                                    </div>
                                @endif
                            @endif

                        </div>
                    </div>
                </div>
            </div>
    </section>
@endif


@if (!Request::ajax())
    @endsection
@endif
