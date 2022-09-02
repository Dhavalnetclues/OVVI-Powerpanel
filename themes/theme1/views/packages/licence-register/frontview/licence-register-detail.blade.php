@if(!Request::ajax())
@extends('layouts.app')
@section('content')
@include('layouts.inner_banner')
@endif
@if(!Request::ajax())

<section class="inner-page-gap">
    @include('layouts.share-email-print')    

    <div class="container">
        <div class="row">
            <div class="col-xl-6">
                <div class="row">
                    <div class="col-md-6 d-flex n-gapp-md-3 n-gapm-md-2" data-aos="zoom-in">
                        <article class="n-bs-1 w-100 n-pa-30">
                            <div class="nqtitle-small n-fc-a-500">Contact Person</div>
                            <div class="cms n-mt-10"><p>{{$licenceRegister->varContactPerson}}</p></div>
                        </article>
                    </div>
                    <div class="col-md-6 d-flex n-gapp-md-3 n-gapm-md-2" data-aos="zoom-in">
                        <article class="n-bs-1 w-100 n-pa-30">
                            <div class="nqtitle-small n-fc-a-500">Contact Address</div>
                            <div class="cms n-mt-10"><p>{{$licenceRegister->varContactAddress}}</p></div>
                        </article>
                    </div>

                    @if((isset($licenceRegister->varEmail) && !empty($licenceRegister->varEmail)) || ((isset($licenceRegister->varWeblink1) && !empty($licenceRegister->varWeblink1)) || (isset($licenceRegister->varWeblink2) && !empty($licenceRegister->varWeblink2)) || (isset($licenceRegister->varWeblink3) && !empty($licenceRegister->varWeblink3))) )
                    <div class="col-md-6 d-flex n-gapp-md-3 n-gapm-md-2" data-aos="zoom-in">
                        <article class="n-bs-1 w-100 n-pa-30">
                            <div class="nqtitle-small n-fc-a-500">Email & Website</div>
                            <div class="cms n-mt-10">
                                <p>
                                    @if(isset($licenceRegister->varEmail) && !empty($licenceRegister->varEmail))
                                        Email: <a href="mailto:{{$licenceRegister->varEmail}}" title="{{$licenceRegister->varEmail}}">{{$licenceRegister->varEmail}}</a><br>
                                    @endif
                                    @if(isset($licenceRegister->varWeblink1) && !empty($licenceRegister->varWeblink1))
                                        Website: <a href="{{$licenceRegister->varWeblink1}}" title="{{$licenceRegister->varWeblink1}}" target="_blank">{{$licenceRegister->varWeblink1}}</a>
                                    @endif
                                    @if(isset($licenceRegister->varWeblink2) && !empty($licenceRegister->varWeblink2))
                                        Website: <a href="{{$licenceRegister->varWeblink2}}" title="{{$licenceRegister->varWeblink2}}" target="_blank">{{$licenceRegister->varWeblink2}}</a>
                                    @endif
                                    @if(isset($licenceRegister->varWeblink3) && !empty($licenceRegister->varWeblink3))
                                        Website: <a href="{{$licenceRegister->varWeblink3}}" title="{{$licenceRegister->varWeblink3}}" target="_blank">{{$licenceRegister->varWeblink3}}</a>
                                    @endif
                                </p>
                            </div>
                        </article>
                    </div>
                    @endif
                    <div class="col-md-6 d-flex n-gapp-md-3 n-gapm-md-2" data-aos="zoom-in">
                        <article class="n-bs-1 w-100 n-pa-30">
                            <div class="nqtitle-small n-fc-a-500">Date of Issue & Status</div>
                            @if(isset($licenceRegister->dtDateTime) && !empty($licenceRegister->dtDateTime))
                          
                                <div class="cms n-mt-10"><p>{{ date('d',strtotime($licenceRegister->dtDateTime)) }} {{ date('M',strtotime($licenceRegister->dtDateTime)) }}, {{ date('Y',strtotime($licenceRegister->dtDateTime)) }}</p></div>
                            @endif
                            
                            @if(isset($licenceRegister->varStatus) && !empty($licenceRegister->varStatus))
                                <div class="cms n-mt-10"><p>{{$licenceRegister->varStatus}}</p></div>
                            @endif
                        </article>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 n-mt-25 n-mt-xl-0" data-aos="fade-up">
                <ul class="nqul ac-collapse accordion" id="faqaccordion">
                    @if(count($licenceRegister->services) > 0)
                        @php $count = 0; @endphp
                        @foreach($licenceRegister->services as $key => $service)
                        
                            @php
                                if($count == 0) {
                                    $collapse = '';
                                    $display = "show";
                                    $count++;
                                }
                                else {
                                    $collapse = 'collapsed';
                                    $display = "";
                                    $count++;
                                }
                            @endphp
                            <li class="-li">
                                <a class="-tabs {{$collapse}}" data-toggle="collapse" href="#{{$service['categorycode']}}" aria-expanded="true" aria-controls="{{$service['categorycode']}}" title="{{$service['categoryName']}}">{{$service['categoryName']}} <span></span></a>
                                <div id="{{$service['categorycode']}}" class="-info collapse {{$display}}" aria-labelledby="headingOne" data-parent="#faqaccordion">
                                    <div class="cms">
                                        <ul>
                                            @foreach($service['services'] as $value)
                                                <li><b>Code {{$value->serviceCode}}</b> - {{$value->varTitle}}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    @endif
                    @if(isset($licenceRegister->fkIntDocId) && !empty($licenceRegister->fkIntDocId))
                    <li class="-li">
                        <a class="-tabs collapsed" data-toggle="collapse" href="#licencedocuments" aria-expanded="true" aria-controls="licencedocuments" title="Licence Documents">Licence Documents <span></span></a>
                        <div id="licencedocuments" class="-info collapse" aria-labelledby="headingOne" data-parent="#faqaccordion">
                            <div class="row">
                                @php
                                    $docsAray = explode(',',$licenceRegister->fkIntDocId);
                                    $docObj   = App\Document::getDocDataByIds($docsAray);
                                @endphp
                                @if(count($docObj) > 0)
                                    @foreach($docObj as $key => $val)
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

                                            if(isset($val->foldername) && !empty($val->foldername)){
                                                $foldername = $val->foldername.'/';
                                            } else {
                                                $foldername = '';
                                            }
                                            
                                        @endphp
                                        <div class="col-md-6 n-gapp-3 n-gapm-md-2" data-aos="fade-up">
                                            <div class="documents">
                                                <div class="-doct-img">
                                                    <i class="n-icon" data-icon="s-pdf"></i>
                                                    <i class="n-icon" data-icon="s-download"></i>
                                                </div>
                                                <div>
                                                    <a class="-link n-ah-a-500 docHitClick" data-viewid="{{ $val->id }}" data-viewtype="download" href="{{ $CDN_PATH.'documents/'.$foldername.$val->txtSrcDocumentName.'.'.$val->varDocumentExtension }}" download="" title="{{ $val->txtDocumentName }}">{{ $val->txtDocumentName }}</a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
        
    </div>
</section>


@endif
<script src="{{ $CDN_PATH.'assets/js/packages/licence-register/licence-register.js' }}" type="text/javascript"></script>
@if(!Request::ajax())
@section('footer_scripts')
@endsection
@endsection
@endif