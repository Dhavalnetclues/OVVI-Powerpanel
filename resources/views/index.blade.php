@extends('layouts.app')
@section('content')
@include('layouts.home_banner')

@if(isset($PAGE_CONTENT['response']) && !empty($PAGE_CONTENT['response']))
	{!!  $PAGE_CONTENT['response'] !!}
@endif

@endsection
@section('footer_scripts')
{{-- <script src="{{ $CDN_PATH.'assets/libraries/OwlCarousel2/2.3.4/js/owl.carousel.min.js' }}" defer></script>
<script src="{{ $CDN_PATH.'assets/libraries/slick/js/slick.min.js' }}" defer></script> --}}
<script src="{{ $CDN_PATH.'assets/js/index.js' }}"></script>
@endsection