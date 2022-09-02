
@if(isset($data['news']) && !empty($data['news']) && count($data['news']) > 0)

<section class="n-pv-50 n-pv-lg-100 home-news">
    @if(isset($data['title']) && $data['title'] != '')
    <div class="container-fluid n-mb-40 n-mb-lg-80" data-aos="fade-right">
        <div class="row">
            <div class="col-12">
                <h2 class="nqtitle-small text-uppercase">{!! $data['title'] !!}</h2>
            </div>
        </div>
    </div>
    @endif
    @php
    $moduelFrontPageUrl = '#';
    $recordLinkUrl = '#';
    if(isset(App\Helpers\MyLibrary::getFront_Uri('news')['uri'])) {
    $moduelFrontPageUrl = App\Helpers\MyLibrary::getFront_Uri('news')['uri'];
    }
    @endphp
    <div class="container">
        <div class="row gap-m">

            <div class="col-lg-6 d-flex gap-p">
                <div class="row gap-m">
                    @foreach($data['news'] as $nkey => $news)
                    @php
                    $recordLinkUrl = (isset($news->alias->varAlias) && !empty($news->alias->varAlias)) ? $moduelFrontPageUrl . '/' . $news->alias->varAlias : $moduelFrontPageUrl;
                    @endphp
                    @if($nkey == 0 )
                    <div class="col-12 gap" data-aos="flip-left">
                        <article class="-items n-bs-1 n-bgc-white-500">
                            <a href="{{ $recordLinkUrl }}" title="{{ $news->varTitle  }}">
                                <div class="thumbnail-container">
                                    <div class="thumbnail">
                                        <img class="lazy" src="{!! App\Helpers\resize_image::resize($news->fkIntImgId) !!}" data-src="{!! App\Helpers\resize_image::resize($news->fkIntImgId) !!}">
                                    </div>
                                </div>
                            </a>
                            <div class="-textblock">
                                <div class="-ntitle n-fc-dark-500 n-lh-120">
                                    <a href="{{ $recordLinkUrl }}" title="{{ $news->varTitle  }}">{{ $news->varTitle }}</a>
                                </div>
                                @if(isset($news->dtDateTime) && $news->dtDateTime != '')
                                <div class="date"> {{ date('M',strtotime($news->dtDateTime)) }} {{ date('d',strtotime($news->dtDateTime)) }}, {{ date('Y',strtotime($news->dtDateTime)) }} </div>
                                @endif
                            </div>
                        </article>
                    </div>


                    @endif

                    @if($nkey == 1 || $nkey == 2)
                    <div class="col-12 col-sm-6 gap" data-aos="flip-left">
                        <article class="-items n-bs-1 n-bgc-white-500">
                            <a href="{{ $recordLinkUrl }}" title="Utility regulation and competition Office experimental licence (4C LTE & fixed wireless solution experimental testing)">
                                <div class="thumbnail-container">
                                    <div class="thumbnail">
                                        <img class="lazy" src="{!! App\Helpers\resize_image::resize($news->fkIntImgId) !!}" data-src="{!! App\Helpers\resize_image::resize($news->fkIntImgId) !!}">
                                    </div>
                                </div>
                            </a>
                            <div class="-textblock">
                                <div class="-ntitle n-fc-dark-500 n-lh-120">
                                    <a href="{{ $recordLinkUrl }}" title="{{ $news->varTitle  }}">{{ $news->varTitle  }}</a>
                                </div>

                                @if(isset($news->dtDateTime) && $news->dtDateTime != '')
                                <div class="date"> {{ date('M',strtotime($news->dtDateTime)) }} {{ date('d',strtotime($news->dtDateTime)) }}, {{ date('Y',strtotime($news->dtDateTime)) }} </div>
                                @endif
                            </div>
                        </article>
                    </div>
                    @endif

                    @endforeach
                </div>
            </div>

            <div class="col-lg-6 d-flex gap-p">
                <div class="row gap-m">
                    @foreach($data['news'] as $key => $news)
                    @if($key == 3 || $key == 4 )
                    <div class="col-12 col-sm-6 gap-r" data-aos="flip-left">
                        <article class="-items n-bs-1 n-bgc-white-500">
                            <div class="-textblock">
                                <div class="-ntitle n-fc-dark-500 n-lh-120">
                                    <a href="{{ $recordLinkUrl }}" title="{{ $news->varTitle  }}">{{ $news->varTitle  }}</a>
                                </div>
                                @if(isset($news->dtDateTime) && $news->dtDateTime != '')
                                <div class="date"> {{ date('M',strtotime($news->dtDateTime)) }} {{ date('d',strtotime($news->dtDateTime)) }}, {{ date('Y',strtotime($news->dtDateTime)) }} </div>
                                @endif
                            </div>
                        </article>
                    </div>
                    @endif
                    @if($key == 5)
                    <div class="col-12 gap-r" data-aos="flip-left">
                        <article class="-items n-bs-1 n-bgc-white-500">
                            <a href="{{ $recordLinkUrl }}" title="{{ $news->varTitle  }}">
                                <div class="thumbnail-container">
                                    <div class="thumbnail">
                                        <img class="lazy" src="{!! App\Helpers\resize_image::resize($news->fkIntImgId) !!}" data-src="{!! App\Helpers\resize_image::resize($news->fkIntImgId) !!}">
                                    </div>
                                </div>
                            </a>
                            <div class="-textblock">
                                <div class="-ntitle n-fc-dark-500 n-lh-120">
                                    <a href="{{ $recordLinkUrl }}" title="{{ $news->varTitle  }}">{{ $news->varTitle  }}</a>
                                </div>
                                @if(isset($news->dtDateTime) && $news->dtDateTime != '')
                                <div class="date"> {{ date('M',strtotime($news->dtDateTime)) }} {{ date('d',strtotime($news->dtDateTime)) }}, {{ date('Y',strtotime($news->dtDateTime)) }} </div>
                                @endif
                            </div>
                        </article>
                    </div>
                    @endif

                    @endforeach
                </div>
            </div>
        </div>
    </div>


</section>

@endif