@php
	$CDN_PATH = Config::get('Constant.CDN_PATH');
	$requestedFullUrl = Request::Url();
	$homePageUrl = url('/');
	$versioning = '?'.time();
    $seg = request()->segments();
	$encodedSeg = json_encode($seg);
@endphp
	@include('layouts.header')
	@include('layouts.header_main')
        @include('layouts.popup')
	@yield('content')
	@include('layouts.footer_main')
</div>
<script>           
function scrollpagination() {
	$('body,html').animate({
        scrollTop: 0
    }, 1000); 
}
</script>
<script>
	var site_url = "{{ url('/') }}";
	var deviceType = "{{ Config::get('Constant.DEVICE') }}";
	var segments = "{{ $encodedSeg }}";
	var CDN_PATH = "{{ $CDN_PATH }}";
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	}); 
</script>
<!-- Java Script S -->
<script src="{{ $CDN_PATH.'assets/libraries/svgicon/svgicon.js' }}{{ $versioning }}" defer></script>
<!-- <script src="{{ $CDN_PATH.'assets/libraries/gsap/gsap.min.js' }}{{ $versioning }}" defer></script> -->
<script src="{{ $CDN_PATH.'assets/libraries/aos-master/js/aos.js' }}{{ $versioning }}" defer></script>
<script src="{{ $CDN_PATH.'assets/libraries/browser-upgrade/js/browser-upgrade.js'}}{{ $versioning }}" defer></script>
<script src="{{ $CDN_PATH.'assets/libraries/back-top/js/back-top.js'}}{{ $versioning }}" defer></script>
<script src="{{ $CDN_PATH.'assets/libraries/menu/js/menu_01.js'}}{{ $versioning }}"></script>
<!-- <script src="{{ $CDN_PATH.'assets/libraries/materialize-src/js/materialize-form.js' }}{{ $versioning }}"></script> -->
<script src="{{ $CDN_PATH.'assets/libraries/popper/popper.min.js'}}{{ $versioning }}" defer></script>
<script src="{{ $CDN_PATH.'assets/libraries/bootstrap/4.5.3/js/bootstrap.min.js'}}{{ $versioning }}" defer></script>
<script src="{{ $CDN_PATH.'assets/libraries/jquery-validation/js/jquery.validate.min.js' }}{{ $versioning }}"></script>
<script src="{{ $CDN_PATH.'assets/libraries/jquery-validation/js/additional-methods.min.js' }}{{ $versioning }}"></script>
<script src="{{ $CDN_PATH.'assets/js/newsletter.js' }}{{ $versioning }}" defer></script>
<script src="{{ $CDN_PATH.'assets/libraries/SVGConverter/js/svg-converter.js' }}{{ $versioning }}" defer></script>
<script src="{{ $CDN_PATH.'assets/libraries/mc-scrollbar/js/jquery.mCustomScrollbar.concat.min.js' }}{{ $versioning }}"></script>
<script src="{{ $CDN_PATH.'assets/libraries/fontsize/fontsize.js' }}{{ $versioning }}" defer></script>
<!-- <script src="{{ $CDN_PATH.'assets/libraries/bootstrap-select-master/1.13.14/js/bootstrap-select.min.js' }}{{ $versioning }}" defer></script> -->
<script src="{{ $CDN_PATH.'assets/libraries/OwlCarousel2/2.3.4/js/owl.carousel.min.js' }}{{ $versioning }}" defer></script>
<script src="{{ $CDN_PATH.'assets/libraries/lazy/jquery.lazy.min.js' }}{{ $versioning }}" defer></script>
<script src="{{ $CDN_PATH.'assets/js/custom.js'}}{{ $versioning }}"></script>
<script src="{{ $CDN_PATH.'assets/js/globalSearch.js' }}"></script>
<!-- Java Script E -->
@yield('footer_scripts')
@yield('page_scripts')
<link rel="preload" href="{{ $CDN_PATH.'assets/css/print.css' }}{{ $versioning }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="{{ $CDN_PATH.'assets/css/print.css' }}{{ $versioning }}"></noscript>
</body>
<!-- Body E -->
</html>