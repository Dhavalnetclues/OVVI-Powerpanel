@if(!Request::ajax())
@extends('layouts.app')
@section('content')
@include('layouts.inner_banner')
@endif

@if(!Request::ajax())
@if(isset($boardofdirectors) && !empty($boardofdirectors))
<section class="inner-page-gap directors-detail">
    @include('layouts.share-email-print')

    <div class="container">
        <div class="row">
            <div class="col-12">
                @if(isset($boardofdirectors->fkIntImgId))
                @php
                $itemImg = App\Helpers\resize_image::resize($boardofdirectors->fkIntImgId);
                @endphp
                @else
                @php
                $itemImg = $CDN_PATH.'assets/images/directors.png';
                @endphp
                @endif
                <div class="-img" data-aos="fade-right">
                    <div class="thumbnail-container">
                        <div class="thumbnail">
                            <img src="{{$itemImg}}" alt="{{ $boardofdirectors->varTitle }}">
                        </div>
                    </div>
                </div>
                <div class="-desc">
                    <div data-aos="fade-up">
                        <h2 class="nqtitle-ip">{{ $boardofdirectors->varTitle }}</h2>
                        <div class="n-mt-15 n-fs-18 n-fw-600 text-uppercase n-fc-black-500">{{ $boardofdirectors->varTagLine }}</div>
                    </div>

                    <div class="cms n-mt-25 n-mt-lg-45" data-aos="fade-up">
                        
                        @if(isset($boardofdirectors->varDepartment) && !empty($boardofdirectors->varDepartment))
                        
                        <h3>{{ $boardofdirectors->varDepartment}}</h3>
                        
                        @endif
                        @if(isset($boardofdirectors->txtDescription) && !empty($boardofdirectors->txtDescription))

                        {!! htmlspecialchars_decode($txtDescription) !!}

                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>


</section>
@endif
@endif

@if(!Request::ajax())
@section('footer_scripts')

@endsection
@endsection
@endif