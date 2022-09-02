@if(!Request::ajax())
@extends('layouts.app')
@section('content')
@include('layouts.inner_banner')
@endif
<section class="career_sec owl-section">
    <div class="container">  
        <div class="career_slide">
            <div class="row">
                @php
                if(isset($DataDescription['response'])){
                echo $DataDescription['response'];
                }
                @endphp
                <div class="col-12">
                        @foreach($careersArr as $career)
                        @php
                        if(isset(App\Helpers\MyLibrary::getFront_Uri('careers')['uri'])){
                        $moduelFrontPageUrl = App\Helpers\MyLibrary::getFront_Uri('careers')['uri'];
                        $moduleFrontWithCatUrl = ($career->varAlias != false ) ? $moduelFrontPageUrl . '/' . $career->varAlias : $moduelFrontPageUrl;
                        $categoryRecordAlias = App\Helpers\Mylibrary::getRecordAliasByModuleNameRecordId('careers',$career->intFkCategory);
                        $recordLinkUrl = $moduleFrontWithCatUrl.'/'.$career->alias->varAlias;
                        }else{
                        $recordLinkUrl = '';
                        }
                        @endphp
                        @if(isset($career->fkIntImgId))
                        @php                          
                        $itemImg = App\Helpers\resize_image::resize($career->fkIntImgId);
                        @endphp
                        @else 
                        @php
                        $itemImg = $CDN_PATH.'assets/images/career-img1.jpg';
                        @endphp
                        @endif

                        @if(isset($career->custom['description']))
                        @php
                        $description = $career->custom['description'];
                        @endphp
                        @else 
                        @php
                        $description = $career->varShortDescription;
                        @endphp
                        @endif
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <div class="career_post listing clearfix">
                                @if(isset($career->fkIntImgId) && $career->fkIntImgId != '')
                                <div class="career_img">
                                    <div class="thumbnail-container">
                                        <div class="thumbnail">
                                            <a title="{{ $career->varTitle }}" href="{{ $recordLinkUrl }}">
                                                <img src="{{ $itemImg }}" alt="{{ $career->varTitle }}">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                <div class="info">
                                    @if(isset($career->dtDateTime) && $career->dtDateTime != '')
                                    <div class="date">{{ date('l d M, Y',strtotime($career->dtDateTime)) }}</div>
                                    @endif
                                    <h5 class="sub_title"><a href="{{ $recordLinkUrl }}" title="{{ $career->varTitle }}" alt="{{ $career->varTitle }}">{{ $career->varTitle }}</a></h5>
                                    @if(isset($description) && $description != '')
                                    <p>{!! (strlen($description) > 150) ? substr($description, 0, 150).'...' : $description !!}</p>
                                    @endif
                                    <a class="btn ac-border " href="{{ $recordLinkUrl }}" title="Read More">Read More</a>
                                </div>
                            </div>
                        </div>
                        @endforeach
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