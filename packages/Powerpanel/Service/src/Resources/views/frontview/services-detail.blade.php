@extends('layouts.app')
@section('content')
@include('layouts.inner_banner')
<section>
   <div class="inner-page-gap cms service-details">
      <div class="container">
         <div class="row servicerow">
            <div class="col-lg-3 col-md-4">
                <div class="service-details-left">
                    <h3 class="title">Our Services</h3>
                    <ul>
                        @foreach ($allServices as $item)
                            @php
                                if(isset(App\Helpers\MyLibrary::getFront_Uri('service')['uri'])){
                                    $moduelFrontPageUrl = App\Helpers\MyLibrary::getFront_Uri('service')['uri'];
                                    $moduleFrontWithCatUrl = ($item->varAlias != false ) ? $moduelFrontPageUrl . '/' . $item->varAlias : $moduelFrontPageUrl;
                                    $recordLinkUrl = $moduleFrontWithCatUrl.'/'.$item->alias->varAlias;
                                } else {
                                    $recordLinkUrl = '';
                                }
                            @endphp
                            <li>
                                <a href="{{ $recordLinkUrl }}"> {{ $item->varTitle }} <span class="arrow-right"></span> </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="service-help">
                    <h3 class="title">Need any help?</h3>
                    <p>We are here to help and answer any question you might have. We look forward to hearing from you.</p>
                    {{--<p>{{substr($primaryContact->mailingaddress, 0, 12)}}</p>
                    <p>{{substr($primaryContact->mailingaddress, 13, 24)}}</p>
                    <p>{{substr($primaryContact->mailingaddress, 35, 50)}}</p>--}}
                    <div class="call"><a class="phone-no" href="tel:{{$primaryContact->varPhoneNo}}" title="Call Us On {{$primaryContact->varPhoneNo}}">{{$primaryContact->varPhoneNo}}</a></div>
                    <a class="ac-btn ac-btn-primary d-block" href="{{ url('/contact-us') }}" title="Request a Callback">Request a Callback</a>
                </div>
            </div>
            <div class="col-lg-9 col-md-8">
                <div class="animated fadeInUp">
                    <div class="detail-img">
                        <div class="thumbnail-container" style="padding-bottom:66.66%;">
                            <div class="thumbnail">
                                @php $itemImg = App\Helpers\resize_image::resize($services->fkIntImgId) @endphp
                                <img src="{{ $itemImg }}" alt="{{ $services->varTitle }}">
                            </div>
                        </div>
                    </div>

                    <div class="detail-desc cms">
                        <h2> {{ $services->varTitle }} </h2>
                        <p> {{ $services->varShortDescription }} </p>
                    </div>

                    <div class="ser-details-img">
                        <div class="row">
                            @if (isset($txtDescription) && !empty($txtDescription))
                                {!! htmlspecialchars_decode($txtDescription) !!}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
         </div>
      </div>
   </div>
</section>
@if(!Request::ajax())
@section('footer_scripts')
@endsection
@endsection
@endif