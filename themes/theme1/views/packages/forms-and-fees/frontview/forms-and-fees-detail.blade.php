@if(!Request::ajax())
@extends('layouts.app')
@section('content')
@include('layouts.inner_banner')
@endif
@if(!Request::ajax())

<section>
    <div class="inner-page-container cms faqs_section">
        <div class="container">
           
            <div class="row">
                <div class="col-md-12 col-md-12 col-xs-12 animated fadeInUp">
                    <div class="right_content">
                        <div class="col-sm-12">

                            @if(isset($txtDescription['response']) && !empty($txtDescription['response']))
                                {!! $txtDescription['response'] !!}
                            @endif    
                            
                            @if(!empty($careersData->fkIntDocId))
                            @php
                            $docsAray = explode(',', $careersData->fkIntDocId);
                            $docObj   = App\Document::getDocDataByIds($docsAray);
                            @endphp
                            @if(count($docObj) > 0)
                            <hr />
                            <div class="download_files clearfix">
                                <h6>Downloads</h6>
                                <ul>
                                    @foreach($docObj as $key => $val)
                                    @php
                                    if($val->varDocumentExtension == 'pdf' || $val->varDocumentExtension == 'PDF'){
                                    $blank = 'target="_blank"';
                                    }else{
                                    $blank = '';
                                    }
                                    if($val->varDocumentExtension == 'pdf' || $val->varDocumentExtension == 'PDF'){
                                    $icon = "flaticon-pdf-file";
                                    }elseif($val->varDocumentExtension == 'doc' || $val->varDocumentExtension == 'docx'){
                                    $icon = "flaticon-doc-file";
                                    }elseif($val->varDocumentExtension == 'xls' || $val->varDocumentExtension == 'xlsx'){
                                    $icon = "flaticon-xls-file";
                                    }else{
                                    $icon = "flaticon-doc-file";
                                    }
                                    @endphp
                                    <li><a {!! $blank !!} href="{{ $CDN_PATH.'documents/'.$val->txtSrcDocumentName.'.'.$val->varDocumentExtension }}" data-viewid="{{ $val->id }}" data-viewtype="download" class="docHitClick" title="{{ $val->txtDocumentName }}"><i class="fi {{ $icon }}"></i>{{ $val->txtDocumentName }}</a></li>
                                    @endforeach
                                </ul>

                            </div>
                            @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif
@if(!Request::ajax())
@section('footer_scripts')
@endsection
@endsection
@endif