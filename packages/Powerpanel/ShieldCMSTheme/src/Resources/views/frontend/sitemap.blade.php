@if(!Request::ajax())
@extends('layouts.app')
@section('content')
@include('layouts.inner_banner')
@endif



<!-- sitemap_01 S -->
@if(isset($PAGE_CONTENT) && $PAGE_CONTENT != '[]')
    {!!  $PAGE_CONTENT !!}
@endif
<section class="page_section sitemap_01">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <ul class="list cols">
                    {!! $siteMap !!}
                    <li><a href="{{ url('sitemap.xml') }}" title="XML Sitemap" target="_blank">XML Sitemap</a></li>
                    <li><a href="{{ url('/data-removal') }}" title="Privacy Data Removal">Privacy Data Removal</a></li>
                </ul>
                @if(
                    null!==(Config::get('Constant.SOCIAL_FB_LINK')) && strlen(Config::get('Constant.SOCIAL_FB_LINK')) > 0 || 
                    null!==(Config::get('Constant.SOCIAL_TWITTER_LINK')) && strlen(Config::get('Constant.SOCIAL_TWITTER_LINK')) > 0 || 
                    null!==(Config::get('Constant.SOCIAL_YOUTUBE_LINK')) && strlen(Config::get('Constant.SOCIAL_YOUTUBE_LINK')) > 0 || 
                    null!==(Config::get('Constant.SOCIAL_TRIPADVISOR_LINK')) && strlen(Config::get('Constant.SOCIAL_TRIPADVISOR_LINK')) > 0 || 
                    null!==(Config::get('Constant.SOCIAL_LINKEDIN_LINK')) && strlen(Config::get('Constant.SOCIAL_LINKEDIN_LINK')) > 0 || 
                    null!==(Config::get('Constant.SOCIAL_INSTAGRAM_LINK')) && strlen(Config::get('Constant.SOCIAL_INSTAGRAM_LINK')) > 0 || 
                    null!==(Config::get('Constant.SOCIAL_TUMBLR_LINK')) && strlen(Config::get('Constant.SOCIAL_TUMBLR_LINK')) > 0 || 
                    null!==(Config::get('Constant.SOCIAL_PINTEREST_LINK')) && strlen(Config::get('Constant.SOCIAL_PINTEREST_LINK')) > 0 || 
                    null!==(Config::get('Constant.SOCIAL_FLICKR_LINK')) && strlen(Config::get('Constant.SOCIAL_FLICKR_LINK')) > 0 || 
                    null!==(Config::get('Constant.SOCIAL_DRIBBBLE_LINK')) && strlen(Config::get('Constant.SOCIAL_DRIBBBLE_LINK')) > 0 || 
                    null!==(Config::get('Constant.SOCIAL_RSS_FEED_LINK')) && strlen(Config::get('Constant.SOCIAL_RSS_FEED_LINK')) > 0
                )
                    <hr>
                    <h2 class="nqtitle mb-xs-30">Socia Media</h2>
                    <ul class="list cols">
                        @if(null!==(Config::get('Constant.SOCIAL_FB_LINK')) && strlen(Config::get('Constant.SOCIAL_FB_LINK')) > 0)
                            <li><a href="{{ Config::get('Constant.SOCIAL_FB_LINK') }}" title="Facebook" target="_blank">Facebook</a></li>
                        @endif
                        @if(null!==(Config::get('Constant.SOCIAL_TWITTER_LINK')) && strlen(Config::get('Constant.SOCIAL_TWITTER_LINK')) > 0)
                            <li><a href="{{ Config::get('Constant.SOCIAL_TWITTER_LINK') }}" title="Twitter" target="_blank">Twitter</a></li>
                        @endif
                        @if(null!==(Config::get('Constant.SOCIAL_YOUTUBE_LINK')) && strlen(Config::get('Constant.SOCIAL_YOUTUBE_LINK')) > 0)
                            <li><a href="{{ Config::get('Constant.SOCIAL_YOUTUBE_LINK') }}" title="YouTube" target="_blank">YouTube</a></li>
                        @endif
                        @if(null!==(Config::get('Constant.SOCIAL_TRIPADVISOR_LINK')) && strlen(Config::get('Constant.SOCIAL_TRIPADVISOR_LINK')) > 0)
                            <li><a href="{{ Config::get('Constant.SOCIAL_TRIPADVISOR_LINK') }}" title="Tripadvisor" target="_blank">Tripadvisor</a></li>
                        @endif
                        @if(null!==(Config::get('Constant.SOCIAL_LINKEDIN_LINK')) && strlen(Config::get('Constant.SOCIAL_LINKEDIN_LINK')) > 0)
                            <li><a href="{{ Config::get('Constant.SOCIAL_LINKEDIN_LINK') }}" title="Linkedin" target="_blank">Linkedin</a></li>
                        @endif
                        @if(null!==(Config::get('Constant.SOCIAL_INSTAGRAM_LINK')) && strlen(Config::get('Constant.SOCIAL_INSTAGRAM_LINK')) > 0)
                            <li><a href="{{ Config::get('Constant.SOCIAL_INSTAGRAM_LINK') }}" title="Instagram" target="_blank">Instagram</a></li>
                        @endif
                        @if(null!==(Config::get('Constant.SOCIAL_TUMBLR_LINK')) && strlen(Config::get('Constant.SOCIAL_TUMBLR_LINK')) > 0)
                            <li><a href="{{ Config::get('Constant.SOCIAL_TUMBLR_LINK') }}" title="Tumblr" target="_blank">Tumblr</a></li>
                        @endif
                        @if(null!==(Config::get('Constant.SOCIAL_PINTEREST_LINK')) && strlen(Config::get('Constant.SOCIAL_PINTEREST_LINK')) > 0)
                            <li><a href="{{ Config::get('Constant.SOCIAL_PINTEREST_LINK') }}" title="Pinterest" target="_blank">Pinterest</a></li>
                        @endif
                        @if(null!==(Config::get('Constant.SOCIAL_FLICKR_LINK')) && strlen(Config::get('Constant.SOCIAL_FLICKR_LINK')) > 0)
                            <li><a href="{{ Config::get('Constant.SOCIAL_FLICKR_LINK') }}" title="Flickr" target="_blank">Flickr</a></li>
                        @endif
                        @if(null!==(Config::get('Constant.SOCIAL_DRIBBBLE_LINK')) && strlen(Config::get('Constant.SOCIAL_DRIBBBLE_LINK')) > 0)
                            <li><a href="{{ Config::get('Constant.SOCIAL_DRIBBBLE_LINK') }}" title="Dribbble" target="_blank">Dribbble</a></li>
                        @endif
                        @if(null!==(Config::get('Constant.SOCIAL_RSS_FEED_LINK')) && strlen(Config::get('Constant.SOCIAL_RSS_FEED_LINK')) > 0)
                            <li><a href="{{ Config::get('Constant.SOCIAL_RSS_FEED_LINK') }}" title="RSS Feed" target="_blank">RSS Feed</a></li>
                        @endif                        
                    </ul>
                @endif
            </div>
        </div>
    </div> 
</section>
<!-- sitemap_01 E -->
@if(!Request::ajax())
@section('footer_scripts')
<script src="{{ $CDN_PATH.'assets/js/sitemap.js' }}"></script>


@endsection

@endsection
@endif