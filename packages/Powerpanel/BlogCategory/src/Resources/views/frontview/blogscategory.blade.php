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
                        @foreach($blogsArr as $blog)
                        @php
                        if(isset(App\Helpers\MyLibrary::getFront_Uri('blogs')['uri'])){
                        $moduelFrontPageUrl = App\Helpers\MyLibrary::getFront_Uri('blogs')['uri'];
                        $moduleFrontWithCatUrl = ($blog->varAlias != false ) ? $moduelFrontPageUrl . '/' . $blog->varAlias : $moduelFrontPageUrl;
                        $categoryRecordAlias = App\Helpers\Mylibrary::getRecordAliasByModuleNameRecordId('blogs',$blog->intFkCategory);
                        $recordLinkUrl = $moduleFrontWithCatUrl.'/'.$blog->alias->varAlias;
                        }else{
                        $recordLinkUrl = '';
                        }
                        @endphp
                        @if(isset($blog->fkIntImgId))
                        @php                          
                        $itemImg = App\Helpers\resize_image::resize($blog->fkIntImgId);
                        @endphp
                        @else 
                        @php
                        $itemImg = $CDN_PATH.'assets/images/blog-img1.jpg';
                        @endphp
                        @endif

                        @if(isset($blog->custom['description']))
                        @php
                        $description = $blog->custom['description'];
                        @endphp
                        @else 
                        @php
                        $description = $blog->varShortDescription;
                        @endphp
                        @endif
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <div class=" listing clearfix">
                                @if(isset($blog->fkIntImgId) && $blog->fkIntImgId != '')
                                <div class="blog_img">
                                    <div class="thumbnail-container">
                                        <div class="thumbnail">
                                            <a title="{{ $blog->varTitle }}" href="{{ $recordLinkUrl }}">
                                                <img src="{{ $itemImg }}" alt="{{ $blog->varTitle }}">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                <div class="info">
                                    @if(isset($blog->dtDateTime) && $blog->dtDateTime != '')
                                    <div class="date">{{ date('l d M, Y',strtotime($blog->dtDateTime)) }}</div>
                                    @endif
                                    <h5 class="sub_title"><a href="{{ $recordLinkUrl }}" title="{{ $blog->varTitle }}" alt="{{ $blog->varTitle }}">{{ $blog->varTitle }}</a></h5>
                                    @if(isset($description) && $description != '')
                                    <p>{!! (strlen($description) > 150) ? substr($description, 0, 150).'...' : $description !!}</p>
                                    @endif
                                    <a class=" " href="{{ $recordLinkUrl }}" title="Read More">Read More</a>
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