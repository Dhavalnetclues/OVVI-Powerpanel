@if(!Request::ajax())
@extends('layouts.app')
@section('content')
@include('layouts.inner_banner')
@endif
<!-- Sitemap S -->
<section class="inner-page-gap sitemap">
   <div class="container">
      <div class="row">
         <div class="col-12">
            <div class="sitemap-list" >

               <ul>

                  <li>
                     <a href="{{url('/')}}" title="">Pages</a>
                     <ul>
                        @foreach($pages_data as $pages)
                           @php
                              $page = isset($pages['alias']['varAlias']) ? $pages['alias']['varAlias'] : '-';
                           @endphp
                           <li><a href="{{ url('/').'/'.$page }}" title="{{ $pages->varTitle }}">{{ $pages->varTitle }}</a></li>
                        @endforeach
                     </ul>
                  </li>

                  <li>
                     <a href="{{url('/blogs')}}" title="">Blogs</a>
                     <ul>
                        @foreach($blogs_data as $blogs)
                           <li><a href="{{ url('/blogs').'/'.$blogs['alias']['varAlias'] }}" title="{{ $blogs->varTitle }}">{{ $blogs->varTitle }}</a></li>
                        @endforeach
                     </ul>
                  </li>

                  <li>
                     <a href="{{url('/news')}}" title="">News</a>
                     <ul>
                        @foreach($news_data as $news)
                           <li><a href="{{ url('/news').'/'.$news['alias']['varAlias'] }}" title="{{ $news->varTitle }}">{{ $news->varTitle }}</a></li>
                        @endforeach
                     </ul>
                  </li>

                  <li>
                     <a href="{{url('/events')}}" title="">Events</a>
                     <ul>
                        @foreach($events_data as $events)
                           <li><a href="{{ url('/events').'/'.$events['alias']['varAlias'] }}" title="{{ $events->varTitle }}">{{ $events->varTitle }}</a></li>
                        @endforeach
                     </ul>
                  </li>

                  <li>
                     <a href="{{url('/service')}}" title="">Services</a>
                     <ul>
                        @foreach($services_data as $services)
                           @php
                              $page = isset($services['alias']['varAlias']) ? $services['alias']['varAlias'] : '-';
                           @endphp
                           <li><a href="{{ url('/service').'/'.$page }}" title="{{ $services->varTitle }}">{{ $services->varTitle }}</a></li>
                        @endforeach
                     </ul>
                  </li>

                  <li>
                     <a href="{{url('/team')}}" title="">Team</a>
                     <ul>
                        @foreach($team_data as $team)
                           <li><a href="{{ url('/team').'/'.$team['alias']['varAlias'] }}" title="{{ $team->varTitle }}">{{ $team->varTitle }}</a></li>
                        @endforeach
                     </ul>
                  </li>

                  <li>
                     <a href="{{ url('/sitemap.xml') }}" title="XML Site Map">XML Site Map</a>
                  </li>

               </ul>

            </div>
         </div>

         <div class="col-12"><hr></div>
         <div class="col-md-12 mt-3">
            <div class=" sitemap-follow">
               <h2 class="nqtitle-small">Social Media</h2>
               @php $socialAvailable = false; @endphp
               @if((null!==Config::get('Constant.SOCIAL_FB_LINK') && strlen(Config::get('Constant.SOCIAL_FB_LINK')) > 0) || (null!==Config::get('Constant.SOCIAL_TWITTER_LINK') && strlen(Config::get('Constant.SOCIAL_TWITTER_LINK')) > 0) || (null!==Config::get('Constant.SOCIAL_LINKEDIN_LINK') && strlen(Config::get('Constant.SOCIAL_LINKEDIN_LINK')) > 0) || (null!==Config::get('Constant.SOCIAL_YOUTUBE_LINK') && strlen(Config::get('Constant.SOCIAL_YOUTUBE_LINK')) > 0))
               @php $socialAvailable = true; @endphp
               <ul class="ac-share">
                  @if(null!==(Config::get('Constant.SOCIAL_FB_LINK')) && strlen(Config::get('Constant.SOCIAL_FB_LINK')) > 0)
                  <li ><a class="site-fb" href="{{ Config::get('Constant.SOCIAL_FB_LINK') }}" title="Follow Us On Facebook" target="_blank"><i class="fa fa-facebook"></i><span>| &nbsp;Facebook</span></a></li>
                  @endif
                  @if(null!==(Config::get('Constant.SOCIAL_TWITTER_LINK')) && strlen(Config::get('Constant.SOCIAL_TWITTER_LINK')) > 0)
                  <li ><a class="site-twr" href="{{ Config::get('Constant.SOCIAL_TWITTER_LINK') }}" title="Follow Us On Twitter"><i class="fa fa-twitter" target="_blank"></i><span>| &nbsp;Twitter</span></a></li>
                  @endif
                  @if(null!==(Config::get('Constant.SOCIAL_INSTAGRAM_LINK')) && strlen(Config::get('Constant.SOCIAL_INSTAGRAM_LINK')) > 0)
                  <li><a class="site-insta" href="{{ Config::get('Constant.SOCIAL_INSTAGRAM_LINK') }}" title="Follow Us On Instagram"><i class="fa fa-instagram" target="_blank"></i><span>| &nbsp;Instagram</span></a></li>
                  @endif
                  @if(null!==(Config::get('Constant.SOCIAL_LINKEDIN_LINK')) && strlen(Config::get('Constant.SOCIAL_LINKEDIN_LINK')) > 0)
                  <li><a class="site-insta" href="{{ Config::get('Constant.SOCIAL_LINKEDIN_LINK') }}" title="Follow Us On Instagram"><i class="fa fa-linkedin" target="_blank"></i><span>| &nbsp;Linkedin</span></a></li>
                  @endif

                  <li><a class="site-youtube" href="{{ Config::get('Constant.SOCIAL_YOUTUBE_LINK') }}" title="Follow Us On Youtube"><i class="fa fa-youtube" target="_blank"></i><span>| &nbsp;Youtube</span></a></li>

                  {{-- <li><a class="site-pinterest" href="{{ Config::get('Constant.SOCIAL_PINTEREST_LINK') }}" title="Follow Us On pinterest"><i class="fa fa-pinterest" target="_blank"></i><span>| &nbsp;Pinrest</span></a></li> --}}

               </ul>
               @endif
            </div>
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