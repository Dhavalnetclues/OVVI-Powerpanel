@if(!Request::ajax())
@extends('layouts.app')
@section('content')
@include('layouts.inner_banner')
@endif

<section class="inner-page-gap search">
    @include('layouts.share-email-print')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="nqtitle-small n-fc-black-500">Search Results for <span class="n-fc-a-500">{{$searchTerm}}</span></h2>
                <div class="n-fs-18 n-fw-500 n-fc-black-500 n-lh-110">About {{$searchFoundCounter}} results</div>
            </div>
        </div>
       
        <div class="row n-mt-30 justify-content-center">
            @foreach($searchResults as $key => $result)
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

                    if(isset($result->slug) && !empty($result->slug) && $result->slug != 'na') {
                        $url = env('APP_URL') . $result->pageAlias . '/' . $result->slug;
                    } else {
                        if(isset($result->pageAlias) && !empty($result->pageAlias)) {
                            $url = env('APP_URL'). $result->pageAlias;
                        } else {
                            $url = '';
                        }
                    }
                        
                @endphp
                <div class="col-xl-6 n-gapp-3 n-gapm-xl-2 d-flex" data-aos="fade-up">
                    <article class="-items n-bs-1 n-br-5 n-pa-15 w-100 d-flex flex-column">
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
                                <h2 class="n-fs-20 n-fw-500 n-ff-1 n-fc-black-500 n-lh-120"><a href="{{$url}}" title="{{$result->term}}" class="n-ah-a-500">{{$result->term}}</a></h2>
                                @if(isset($result->varShortDescription) && !empty($result->varShortDescription) && $result->varShortDescription !='na')
                                    <div class="n-fs-17 n-fw-500 n-fc-dark-500 n-lh-130 n-mt-10">
                                        {{$result->varShortDescription}}
                                    </div>
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
        {{--<div class="row">
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