@if(!Request::ajax())
@extends('layouts.app')
@section('content')
@include('layouts.inner_banner')
@endif
<section class="blog_sec owl-section">
    <div class="container">  
        <div class="blog_slide">
            <div class="row">
                @php
                if(isset($DataDescription['response'])){
                echo $DataDescription['response'];
                }
                @endphp
                <div class="col-12">
                        @foreach($faqArr as $faq)
                        @php
                        if(isset(App\Helpers\MyLibrary::getFront_Uri('faq')['uri'])){
                        $moduelFrontPageUrl = App\Helpers\MyLibrary::getFront_Uri('faq')['uri'];
                        $moduleFrontWithCatUrl = ($faq->varAlias != false ) ? $moduelFrontPageUrl . '/' . $faq->varAlias : $moduelFrontPageUrl;
                        $categoryRecordAlias = App\Helpers\Mylibrary::getRecordAliasByModuleNameRecordId('events',$faq->intFkCategory);
                        $recordLinkUrl = $moduleFrontWithCatUrl;
                        }else{
                        $recordLinkUrl = '';
                        }
                        @endphp
                        @if(isset($faq->fkIntImgId))
                        @php                          
                        $itemImg = App\Helpers\resize_image::resize($faq->fkIntImgId);
                        @endphp
                        @else 
                        @php
                        $itemImg = $CDN_PATH.'assets/images/blog-img1.jpg';
                        @endphp
                        @endif

                        @if(isset($faq->custom['description']))
                        @php
                        $description = $faq->custom['description'];
                        @endphp
                        @else 
                        @php
                        $description = $faq->varShortDescription;
                        @endphp
                        @endif
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <div class="blog_post listing clearfix">
                                @if(isset($faq->fkIntImgId) && $faq->fkIntImgId != '')
                                <div class="blog_img">
                                    <div class="thumbnail-container">
                                        <div class="thumbnail">
                                            <a title="{{ $faq->varTitle }}" href="{{ $recordLinkUrl }}">
                                                <img src="{{ $itemImg }}" alt="{{ $faq->varTitle }}">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                <div class="info">
                                    @if(isset($faq->dtDateTime) && $faq->dtDateTime != '')
                                    <div class="date">{{ date('l d M, Y',strtotime($faq->dtDateTime)) }}</div>
                                    @endif
                                    <h5 class="sub_title"><a title="{{ $faq->varTitle }}" alt="{{ $faq->varTitle }}">{{ $faq->varTitle }}</a></h5>
                                    @if(isset($faq->txtDescription) && $faq->txtDescription != '')
                                    <p>{!! (strlen($faq->txtDescription) > 150) ? substr($faq->txtDescription, 0, 150).'...' : $faq->txtDescription !!}</p>
                                    @endif
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