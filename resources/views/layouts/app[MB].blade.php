@php
$CDN_PATH = Config::get('Constant.CDN_PATH');
$requestedFullUrl = Request::Url();
$homePageUrl = url('/');
$versioning = '?'.date('dmy');
@endphp

@if(!Request::ajax())
@if(Request::segment(1) == 'previewpage')  
@include('layouts.preview')
@else
@include('layouts.header')
@include('layouts.header_main')
{{--@include('layouts.popup')--}}
@yield('content')
@include('layouts.footer_main')
{{-- @include('layouts.onlinepolling_main')
		@include('layouts.email_to_friend')
		@include('layouts.notification') --}}
@include('layouts.footer')
@endif
@endif