@if(!Request::ajax())
    @extends('layouts.app')
    @section('content')
    @include('layouts.inner_banner')
@endif

<section class="inner-page-gap">
    @include('layouts.share-email-print')    

    <div class="container">
        <div class="row">
            <div class="col-xl-6">
                <div class="row">
                    <div class="col-md-6 d-flex n-gapp-md-3 n-gapm-md-2" data-aos="zoom-in">
                        <article class="n-bs-1 w-100 n-pa-30">
                            <div class="nqtitle-small n-fc-a-500">Contact Person</div>
                            <div class="cms n-mt-10"><p>{{$registerApplication->varContactPerson}}</p></div>
                        </article>
                    </div>
                    <div class="col-md-6 d-flex n-gapp-md-3 n-gapm-md-2" data-aos="zoom-in">
                        <article class="n-bs-1 w-100 n-pa-30">
                            <div class="nqtitle-small n-fc-a-500">Contact Address</div>
                            <div class="cms n-mt-10"><p>{{$registerApplication->varContactAddress}}</p></div>
                        </article>
                    </div>
                    <div class="col-md-6 d-flex n-gapp-md-3 n-gapm-md-2" data-aos="zoom-in">
                        <article class="n-bs-1 w-100 n-pa-30">
                            <div class="nqtitle-small n-fc-a-500">Email & Website</div>
                            <div class="cms n-mt-10">
                                <p>
                                    @if(isset($registerApplication->varEmail) && !empty($registerApplication->varEmail))
                                        Email: <a href="mailto:foi@ofreg.ky" title="{{$registerApplication->varEmail}}">{{$registerApplication->varEmail}}</a><br>
                                    @endif
                                    @if(isset($registerApplication->varWeblink1) && !empty($registerApplication->varWeblink1))
                                        Website: <a href="{{$registerApplication->varWeblink1}}" title="{{$registerApplication->varWeblink1}}" target="_blank">{{$registerApplication->varWeblink1}}</a>
                                    @endif
                                    @if(isset($registerApplication->varWeblink2) && !empty($registerApplication->varWeblink2))
                                        Website: <a href="{{$registerApplication->varWeblink2}}" title="{{$registerApplication->varWeblink2}}" target="_blank">{{$registerApplication->varWeblink2}}</a>
                                    @endif
                                    @if(isset($registerApplication->varWeblink3) && !empty($registerApplication->varWeblink3))
                                        Website: <a href="{{$registerApplication->varWeblink3}}" title="{{$registerApplication->varWeblink3}}" target="_blank">{{$registerApplication->varWeblink3}}</a>
                                    @endif
                                </p>
                            </div>
                        </article>
                    </div>
                    <div class="col-md-6 d-flex n-gapp-md-3 n-gapm-md-2" data-aos="zoom-in">
                        <article class="n-bs-1 w-100 n-pa-30">
                            <div class="nqtitle-small n-fc-a-500">Current Status</div>
                            <div class="cms n-mt-10"><p>{{$registerApplication->varStatus}}</p></div>
                        </article>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 n-mt-25 n-mt-xl-0" data-aos="fade-up">
                <ul class="nqul ac-collapse accordion" id="faqaccordion">
                    @foreach($registerApplication->services as $service)
                        <li class="-li">
                            <a class="-tabs" data-toggle="collapse" href="#{{$service['categorycode']}}" aria-expanded="true" aria-controls="licensedictservices" title="{{$service['categoryName']}}">{{$service['categoryName']}} <span></span></a>
                            <div id="{{$service['categorycode']}}" class="-info collapse show" aria-labelledby="headingOne" data-parent="#faqaccordion">
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
                </ul>
            </div>
        </div>
        
    </div>
</section>


