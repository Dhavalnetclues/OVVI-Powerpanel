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
                        @foreach($newsArr as $news)
                        @php
                        if(isset(App\Helpers\MyLibrary::getFront_Uri('news')['uri'])){
                        $moduelFrontPageUrl = App\Helpers\MyLibrary::getFront_Uri('news')['uri'];
                        $moduleFrontWithCatUrl = ($news->varAlias != false ) ? $moduelFrontPageUrl . '/' . $news->varAlias : $moduelFrontPageUrl;
                        $categoryRecordAlias = App\Helpers\Mylibrary::getRecordAliasByModuleNameRecordId('news',$news->intFkCategory);
                        $recordLinkUrl = $moduleFrontWithCatUrl.'/'.$news->alias->varAlias;
                        }else{
                        $recordLinkUrl = '';
                        }
                        @endphp
                        @if(isset($news->fkIntImgId))
                        @php                          
                        $itemImg = App\Helpers\resize_image::resize($news->fkIntImgId);
                        @endphp
                        @else 
                        @php
                        $itemImg = $CDN_PATH.'assets/images/blog-img1.jpg';
                        @endphp
                        @endif

                        @if(isset($news->custom['description']))
                        @php
                        $description = $news->custom['description'];
                        @endphp
                        @else 
                        @php
                        $description = $news->varShortDescription;
                        @endphp
                        @endif
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <div class="blog_post listing clearfix">
                                @if(isset($news->fkIntImgId) && $news->fkIntImgId != '')
                                <div class="blog_img">
                                    <div class="thumbnail-container">
                                        <div class="thumbnail">
                                            <a title="{{ $news->varTitle }}" href="{{ $recordLinkUrl }}">
                                                <img src="{{ $itemImg }}" alt="{{ $news->varTitle }}">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                <div class="info">
                                    @if(isset($news->dtDateTime) && $news->dtDateTime != '')
                                    <div class="date">{{ date('l d M, Y',strtotime($news->dtDateTime)) }}</div>
                                    @endif
                                    <h5 class="sub_title"><a href="{{ $recordLinkUrl }}" title="{{ $news->varTitle }}" alt="{{ $news->varTitle }}">{{ $news->varTitle }}</a></h5>
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