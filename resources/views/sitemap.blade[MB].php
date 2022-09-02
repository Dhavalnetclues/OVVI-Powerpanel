@if(!Request::ajax())
@extends('layouts.app')
@section('content')
@include('layouts.inner_banner')
@endif

<!-- Sitemap S -->
    @if(isset($PAGE_CONTENT) && $PAGE_CONTENT != '[]')
        {!!  $PAGE_CONTENT !!}
    @endif
    <section class="page_section n-pt-lg-80 n-pt-50 n-pb-50 n-pb-lg-80 sitemap">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="sitemap-list" data-aos="fade-up">
                        <ul>
                            <li><a href="#" title="">Home</a></li>
                            <li><a href="#" title="">Menu Name</a></li>
                            <li><a href="#" title="">Menu Name</a>
                                <ul>
                                    <li><a href="#" title="">Sub Menu Name</a></li>
                                    <li><a href="#" title="">Sub Menu Name</a></li>
                                    <li><a href="#" title="">Sub Menu Name</a>
                                        <ul>
                                            <li><a href="#" title="">Sub Menu Name</a></li>
                                            <li><a href="#" title="">Sub Menu Name</a></li>
                                            <li><a href="#" title="">Sub Menu Name</a></li>
                                            <li><a href="#" title="">Sub Menu Name</a></li>
                                            <li><a href="#" title="">Sub Menu Name</a></li>
                                        </ul>
                                    </li>
                                    <li><a href="#" title="">Sub Menu Name</a></li>
                                    <li><a href="#" title="">Sub Menu Name</a></li>
                                </ul>
                            </li>
                            <li><a href="#" title="">Menu Name</a></li>
                            <li><a href="#" title="">Menu Name</a>
                                <ul>
                                    <li><a href="#" title="">Sub Menu Name</a></li>
                                    <li><a href="#" title="">Sub Menu Name</a></li>
                                    <li><a href="#" title="">Sub Menu Name</a>
                                        <ul>
                                            <li><a href="#" title="">Sub Menu Name</a></li>
                                            <li><a href="#" title="">Sub Menu Name</a></li>
                                            <li><a href="#" title="">Sub Menu Name</a></li>
                                            <li><a href="#" title="">Sub Menu Name</a></li>
                                            <li><a href="#" title="">Sub Menu Name</a></li>
                                        </ul>
                                    </li>
                                    <li><a href="#" title="">Sub Menu Name</a></li>
                                    <li><a href="#" title="">Sub Menu Name</a></li>
                                </ul>
                            </li>
                            <li><a href="#" title="">Menu Name</a></li>
                            <li><a href="#" title="">Menu Name</a>
                                <ul>
                                    <li><a href="#" title="">Sub Menu Name</a></li>
                                    <li><a href="#" title="">Sub Menu Name</a></li>
                                    <li><a href="#" title="">Sub Menu Name</a>
                                        <ul>
                                            <li><a href="#" title="">Sub Menu Name</a></li>
                                            <li><a href="#" title="">Sub Menu Name</a></li>
                                            <li><a href="#" title="">Sub Menu Name</a></li>
                                            <li><a href="#" title="">Sub Menu Name</a></li>
                                            <li><a href="#" title="">Sub Menu Name</a></li>
                                        </ul>
                                    </li>
                                    <li><a href="#" title="">Sub Menu Name</a></li>
                                    <li><a href="#" title="">Sub Menu Name</a></li>
                                </ul>
                            </li>
                            <li><a href="#" title="">Menu Name</a></li>
                            <li><a href="#" title="">Menu Name</a>
                                <ul>
                                    <li><a href="#" title="">Sub Menu Name</a></li>
                                    <li><a href="#" title="">Sub Menu Name</a></li>
                                    <li><a href="#" title="">Sub Menu Name</a>
                                        <ul>
                                            <li><a href="#" title="">Sub Menu Name</a></li>
                                            <li><a href="#" title="">Sub Menu Name</a></li>
                                            <li><a href="#" title="">Sub Menu Name</a></li>
                                            <li><a href="#" title="">Sub Menu Name</a></li>
                                            <li><a href="#" title="">Sub Menu Name</a></li>
                                        </ul>
                                    </li>
                                    <li><a href="#" title="">Sub Menu Name</a></li>
                                    <li><a href="#" title="">Sub Menu Name</a></li>
                                </ul>
                            </li>
                            <li><a href="#" title="">Menu Name</a></li>
                            <li><a href="#" title="">Menu Name</a>
                                <ul>
                                    <li><a href="#" title="">Sub Menu Name</a></li>
                                    <li><a href="#" title="">Sub Menu Name</a></li>
                                    <li><a href="#" title="">Sub Menu Name</a>
                                        <ul>
                                            <li><a href="#" title="">Sub Menu Name</a></li>
                                            <li><a href="#" title="">Sub Menu Name</a></li>
                                            <li><a href="#" title="">Sub Menu Name</a></li>
                                            <li><a href="#" title="">Sub Menu Name</a></li>
                                            <li><a href="#" title="">Sub Menu Name</a></li>
                                        </ul>
                                    </li>
                                    <li><a href="#" title="">Sub Menu Name</a></li>
                                    <li><a href="#" title="">Sub Menu Name</a></li>
                                </ul>
                            </li>
                            <li><a href="#" title="">Menu Name</a></li>
                            <li><a href="#" title="">Menu Name</a>
                                <ul>
                                    <li><a href="#" title="">Sub Menu Name</a></li>
                                    <li><a href="#" title="">Sub Menu Name</a></li>
                                    <li><a href="#" title="">Sub Menu Name</a>
                                        <ul>
                                            <li><a href="#" title="">Sub Menu Name</a></li>
                                            <li><a href="#" title="">Sub Menu Name</a></li>
                                            <li><a href="#" title="">Sub Menu Name</a></li>
                                            <li><a href="#" title="">Sub Menu Name</a></li>
                                            <li><a href="#" title="">Sub Menu Name</a></li>
                                        </ul>
                                    </li>
                                    <li><a href="#" title="">Sub Menu Name</a></li>
                                    <li><a href="#" title="">Sub Menu Name</a></li>
                                </ul>
                            </li>
                            <li><a href="#" title="">XML Site Map</a></li>
                            <li><a href="#" title="">Privacy Data Removal</a></li>
                        </ul>
                    </div>

                    <h2 class="nqtitle-small text-uppercase n-mt-20 n-mt-lg-40 n-mb-15 text-center" data-aos="fade-up">Follow Us</h2>


                    @php $socialAvailable = false; @endphp
                    @if((null!==Config::get('Constant.SOCIAL_FB_LINK') && strlen(Config::get('Constant.SOCIAL_FB_LINK')) > 0) || (null!==Config::get('Constant.SOCIAL_TWITTER_LINK') && strlen(Config::get('Constant.SOCIAL_TWITTER_LINK')) > 0) || (null!==Config::get('Constant.SOCIAL_LINKEDIN_LINK') && strlen(Config::get('Constant.SOCIAL_LINKEDIN_LINK')) > 0) || (null!==Config::get('Constant.SOCIAL_YOUTUBE_LINK') && strlen(Config::get('Constant.SOCIAL_YOUTUBE_LINK')) > 0))
                    @php $socialAvailable = true; @endphp
                    <ul class="ac-share text-center">
                        @if(null!==(Config::get('Constant.SOCIAL_FB_LINK')) && strlen(Config::get('Constant.SOCIAL_FB_LINK')) > 0)
                        <li data-aos="flip-left"><a href="{{ Config::get('Constant.SOCIAL_FB_LINK') }}" title="Follow Us On Facebook" target="_blank"><i class="fa fa-facebook"></i></a></li>
                        @endif
                        @if(null!==(Config::get('Constant.SOCIAL_TWITTER_LINK')) && strlen(Config::get('Constant.SOCIAL_TWITTER_LINK')) > 0)
                        <li data-aos="flip-left"><a href="{{ Config::get('Constant.SOCIAL_TWITTER_LINK') }}" title="Follow Us On Twitter"><i class="fa fa-twitter" target="_blank"></i></a></li>
                        @endif
                        @if(null!==(Config::get('Constant.SOCIAL_LINKEDIN_LINK')) && strlen(Config::get('Constant.SOCIAL_LINKEDIN_LINK')) > 0)
                        <li data-aos="flip-left"><a href="{{ Config::get('Constant.SOCIAL_LINKEDIN_LINK') }}" title="Follow Us On YouTube"><i class="fa fa-linkedin" target="_blank"></i></a></li>
                        @endif
                        @if(null!==(Config::get('Constant.SOCIAL_YOUTUBE_LINK')) && strlen(Config::get('Constant.SOCIAL_YOUTUBE_LINK')) > 0)
                        <li data-aos="flip-left"><a href="{{ Config::get('Constant.SOCIAL_YOUTUBE_LINK') }}" title="Follow Us On YouTube"><i class="fa fa-youtube-play" target="_blank"></i></a></li>
                        @endif
                        @if(null!==(Config::get('Constant.SOCIAL_TRIP_ADVISOR_LINK')) && strlen(Config::get('Constant.SOCIAL_TRIP_ADVISOR_LINK')) > 0)
                        <li data-aos="flip-left"><a href="{{ Config::get('Constant.SOCIAL_TRIP_ADVISOR_LINK') }}" title="Follow Us On Tripadvisor"><i class="fa fa-tripadvisor" target="_blank"></i></a></li>
                        @endif
                        @if(null!==(Config::get('Constant.SOCIAL_INSTAGRAM_LINK')) && strlen(Config::get('Constant.SOCIAL_INSTAGRAM_LINK')) > 0)
                        <li data-aos="flip-left"><a href="{{ Config::get('Constant.SOCIAL_INSTAGRAM_LINK') }}" title="Follow Us On Instagram"><i class="fa fa-instagram" target="_blank"></i></a></li>
                        @endif
                    </ul>
                    @endif
                </div>
            </div>
        </div>
    </section>
<!-- Sitemap E -->

@if(!Request::ajax())
@section('footer_scripts')
@endsection
@endsection
@endif