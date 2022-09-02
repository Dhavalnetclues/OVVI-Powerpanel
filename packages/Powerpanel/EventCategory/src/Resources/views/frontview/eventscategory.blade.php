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
                        @foreach($eventsArr as $events)
                        @php
                        if(isset(App\Helpers\MyLibrary::getFront_Uri('events')['uri'])){
                        $moduelFrontPageUrl = App\Helpers\MyLibrary::getFront_Uri('events')['uri'];
                        $moduleFrontWithCatUrl = ($events->varAlias != false ) ? $moduelFrontPageUrl . '/' . $events->varAlias : $moduelFrontPageUrl;
                        $categoryRecordAlias = App\Helpers\Mylibrary::getRecordAliasByModuleNameRecordId('events',$events->intFkCategory);
                        $recordLinkUrl = $moduleFrontWithCatUrl.'/'.$events->alias->varAlias;
                        }else{
                        $recordLinkUrl = '';
                        }
                        @endphp
                        @if(isset($events->fkIntImgId))
                        @php                          
                        $itemImg = App\Helpers\resize_image::resize($events->fkIntImgId);
                        @endphp
                        @else 
                        @php
                        $itemImg = $CDN_PATH.'assets/images/blog-img1.jpg';
                        @endphp
                        @endif

                        @if(isset($events->custom['description']))
                        @php
                        $description = $events->custom['description'];
                        @endphp
                        @else 
                        @php
                        $description = $events->varShortDescription;
                        @endphp
                        @endif
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <div class=" listing clearfix">
                                @if(isset($events->fkIntImgId) && $events->fkIntImgId != '')
                                <div class="blog_img">
                                    <div class="thumbnail-container">
                                        <div class="thumbnail">
                                            <a title="{{ $events->varTitle }}" href="{{ $recordLinkUrl }}">
                                                <img src="{{ $itemImg }}" alt="{{ $events->varTitle }}">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                <div class="info">
                                    @if(isset($events->dtDateTime) && $events->dtDateTime != '')
                                    <div class="date">{{ date('l d M, Y',strtotime($events->dtDateTime)) }}</div>
                                    @endif
                                    <h5 class="sub_title"><a href="{{ $recordLinkUrl }}" title="{{ $events->varTitle }}" alt="{{ $events->varTitle }}">{{ $events->varTitle }}</a></h5>
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