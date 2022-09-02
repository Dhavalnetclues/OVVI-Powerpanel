@extends('layouts.app')
@section('content')
@include('layouts.home_banner')

@if(isset($PAGE_CONTENT['response']) && $PAGE_CONTENT['response'] != '[]')
    {!!  $PAGE_CONTENT['response'] !!}
@elseif(isset($PAGE_CONTENT) && $PAGE_CONTENT != '[]')
    {!!  $PAGE_CONTENT !!}
@else
	<section class="n-pv-25 n-pv-lg-50">
		<div class="container">
			<div class="row">
				<div class="col-12 text-center">
					<div class="nqtitle text-uppercase">Coming Soon...</div>
				</div>	
			</div>
		</div>
	</section>	
@endif

<!-- The Modal -->
    <div class="modal fade common-modal" id="myModal">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-body">
            	<button type="button" class="close" data-dismiss="modal"></button>
            	<div class="image">
	            	<div class="thumbnail-container">
	            		<div class="thumbnail">
	            			<img src="{!! url('cdn/assets/images/poupop.png') !!}">
	            		</div>
	            	</div>
	            </div>
            </div>
            
          </div>
        </div>
    </div>
  
<!-- Welcome S -->
	<section class="home-welcome overflow-hidden">
		<div class="container">
			<div class="row">
				<div class="col-12 col-lg-4" data-aos="fade-right">
					<div class="item">
						<h1 class="nqtitle">Lorem ipsum amet, consec adipiscing elit, sed diam nonummy nibh euismod tincidunt aliquam erat </h1>
					</div>
				</div>
				<div class="col-12 col-lg-4 col-sm-6 n-mt-25 n-mt-lg-0" data-aos="fade-left">
					<div class="nqtitle-small text-uppercase n-fc-black-500 n-fw-800 n-pb-xl-30 n-pb-15">Domestic Consumers</div>
					<ul class="nqul n-fs-20 n-fw-500 n-fc-black-500 n-lh-130">
						<li><a href="#" title="Making a complaint" class="n-ah-a-500">Making a complaint</a></li>
						<li><a href="#" title="Switching your energy supplier" class="n-ah-a-500">Switching your energy supplier</a></li>
						<li><a href="#" title="Take charge. Save on bills." class="n-ah-a-500">Take charge. Save on bills.</a></li>
						<li><a href="#" title="Get or alter a gas or electricity connection" class="n-ah-a-500">Get or alter a gas or electricity connection</a></li>
						<li><a href="#" title="Forms & Fees" class="n-ah-a-500">Forms & Fees</a></li>
						<li><a href="#" title="Retail Fuel prices" class="n-ah-a-500">Retail Fuel prices</a></li>
						<li><a href="#" title="Report on a Safety Issue or Accident" class="n-ah-a-500">Report on a Safety Issue or Accident</a></li>
						<li><a href="#" title="FAQs" class="n-ah-a-500">FAQs</a></li>
					</ul>
				</div>
				<div class="col-12 col-lg-4 col-sm-6 n-mt-25 n-mt-lg-0" data-aos="fade-left">
					<div class="nqtitle-small text-uppercase n-fc-black-500 n-fw-800 n-pb-xl-30 n-pb-15">Industry Operators</div>
					<ul class="nqul n-fs-20 n-fw-500 n-fc-black-500 n-lh-130">
						<li><a href="#" title="Licensing, Tariff & Market Rules" class="n-ah-a-500">Licensing, Tariff & Market Rules</a></li>
						<li><a href="#" title="Ofreg regulations" class="n-ah-a-500">Ofreg regulations</a></li>
						<li><a href="#" title="Renewable energy" class="n-ah-a-500">Renewable energy</a></li>
						<li><a href="#" title="Consultations" class="n-ah-a-500">Consultations</a></li>
						<li><a href="#" title="Legislation" class="n-ah-a-500">Legislation</a></li>
						<li><a href="#" title="Ofreg Decisions" class="n-ah-a-500">Ofreg Decisions</a></li>
					</ul>
				</div>
			</div>
		</div>
	</section>
<!-- Welcome E -->

<!-- Short Service S -->
	<section class="n-pt-50 n-pt-lg-100 home-short-service home-short-service-slider" data-aos="fade-up">
		<div class="container">
			<div class="row">
				<div class="col-sm-12">
					<div class="owl-carousel owl-theme">
						<div class="item item-m d-flex">
							<div class="align-self-end">
								<h2 class="title-m">Consumer Information</h2>
								<a href="#" class="more-info" title="More Info">More Info</a>
							</div>
						</div>
						<div class="item item-m d-flex">
							<div class="align-self-end">
								<h2 class="title-m">Documents Publications</h2>
								<a href="#" class="more-info" title="More Info">More Info</a>
							</div>
						</div>
						<div class="item item-m d-flex">
							<div class="align-self-end">
								<h2 class="title-m">Licences & Standards</h2>
								<a href="#" class="more-info" title="More Info">More Info</a>
							</div>
						</div>
						<div class="item item-m d-flex">
							<div class="align-self-end">
								<h2 class="title-m">Annual Reports</h2>
								<a href="#" class="more-info" title="More Info">More Info</a>
							</div>					
						</div>
						<div class="item item-m d-flex">
							<div class="align-self-end">
								<h2 class="title-m">Industry Statistics</h2>
								<a href="#" class="more-info" title="More Info">More Info</a>
							</div>
						</div>
						<div class="item item-m d-flex">
							<div class="align-self-end">
								<h2 class="title-m">Click B4U Dig</h2>
								<a href="#" class="more-info" title="More Info">More Info</a>
							</div>
						</div>
					</div>
				</div>			
			</div>
		</div>
	</section>
<!-- Short Service E -->

<!-- Welcome S -->
	<section class="n-pv-50 n-pv-lg-100 home-welcome-text" data-aos="fade-up">
		<div class="container">
			<div class="row">
				<div class="col-sm-12 text-center -desc">Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi Lorem ipsum dolor sit amet, tincidunt ut laoreet dolore magna aliquam erat volutpat.
				</div>
			</div>
		</div>
	</section>
<!-- Welcome E -->

<!-- Service S -->
	<section class="n-pt-50 n-pt-lg-100 home-service home-service-slider" data-aos="fade-up">
		<div class="container-fluid">
			<div class="row">
				<div class="col-12">
					<div class="owl-carousel owl-theme">
						@php for ($x = 1; $x <= 4; $x++) { @endphp
							<div class="item">
								<article class="-items n-bs-1 n-mv-30 n-bgc-white-500">
									<div class="-img">
										<div class="thumbnail-container">
											<div class="thumbnail">
												<img src="{{ $CDN_PATH.'assets/images/services/energy.png' }}" alt="Energy" title="Energy" />
											</div>
										</div>
									</div>
									<div class="-stitle">
										<div class="nqtitle n-fw-800 text-uppercase">Energy</div>
										<div class="text-uppercase n-fs-18 n-fw-500 n-fc-black-500">Sector Regulation</div>
									</div>
									<ul class="nqul n-fs-20 n-fw-500 n-fc-black-500 n-lh-130 n-mh-xl-25">
										<li><a class="n-ah-a-500" href="#" title="Licensing">Licensing</a></li>
	                                    <li><a class="n-ah-a-500" href="#" title="Consultations">Consultations</a></li>
	                                    <li><a class="n-ah-a-500" href="#" title="Decisions">Decisions</a></li>
	                                    <li><a class="n-ah-a-500" href="#" title="KY Domain">KY Domain</a></li>
	                                    <li><a class="n-ah-a-500" href="#" title="ICT Publications">ICT Publications</a></li>
	                                    <li><a class="n-ah-a-500" href="#" title="Register of Applications">Register of Applications</a></li>
	                                    <li><a class="n-ah-a-500" href="#" title="Archives">Archives</a></li>
									</ul>
								</article>
							</div>
						@php } @endphp
					</div>
				</div>
			</div>
		</div>
	</section>
<!-- Service E -->

<!-- News & Publications S -->
	<section class="n-pv-50 n-pv-lg-100 home-news">
		<div class="container-fluid n-mb-40 n-mb-lg-80" data-aos="fade-right">
			<div class="row">
				<div class="col-12">
					<h2 class="nqtitle-small text-uppercase">News & Publications</h2>
				</div>
			</div>
		</div>
		<div class="container">
			<div class="row gap-m">
				<div class="col-lg-6 d-flex gap-p">
					<div class="row gap-m">
						<div class="col-12 gap" data-aos="flip-left">
							<article class="-items n-bs-1 n-bgc-white-500">
								<a href="#" title="Utility regulation and competition Office experimental licence (4C LTE & fixed wireless solution experimental testing)">
									<div class="thumbnail-container">
										<div class="thumbnail">
											<img class="lazy" src="{{ $CDN_PATH.'assets/images/utility.png' }}" data-src="{{ $CDN_PATH.'assets/images/utility.png' }}">
										</div>
									</div>
								</a>
								<div class="-textblock">
									<div class="-ntitle n-fc-dark-500 n-lh-120">
										<a href="#" title="Utility regulation and competition Office experimental licence (4C LTE & fixed wireless solution experimental testing)">Utility regulation and competition Office experimental licence (4C LTE & fixed wireless solution experimental testing)</a>
									</div>
									<div class="date">14 August 2020</div>
								</div>
							</article>
						</div>
						<div class="col-12 col-sm-6 gap" data-aos="flip-left">
							<article class="-items n-bs-1 n-bgc-white-500">
								<a href="#" title="Utility regulation and competition Office experimental licence (4C LTE & fixed wireless solution experimental testing)">
									<div class="thumbnail-container">
										<div class="thumbnail">
											<img class="lazy" src="{{ $CDN_PATH.'assets/images/utility.png' }}" data-src="{{ $CDN_PATH.'assets/images/utility.png' }}">
										</div>
									</div>
								</a>
								<div class="-textblock">
									<div class="-ntitle n-fc-dark-500 n-lh-120">
										<a href="#" title="Utility regulation and competition Office experimental licence (4C LTE & fixed wireless solution experimental testing)">Utility regulation and competition Office experimental licence (4C LTE & fixed wireless solution experimental testing)</a>
									</div>
									<div class="date">14 August 2020</div>
								</div>
							</article>
						</div>
						<div class="col-12 col-sm-6 gap" data-aos="flip-left">
							<article class="-items n-bs-1 n-bgc-white-500">
								<a href="#" title="Utility regulation and competition Office experimental licence (4C LTE & fixed wireless solution experimental testing)">
									<div class="thumbnail-container">
										<div class="thumbnail">
											<img class="lazy" src="{{ $CDN_PATH.'assets/images/utility.png' }}" data-src="{{ $CDN_PATH.'assets/images/utility.png' }}">
										</div>
									</div>
								</a>
								<div class="-textblock">
									<div class="-ntitle n-fc-dark-500 n-lh-120">
										<a href="#" title="Utility regulation and competition Office experimental licence (4C LTE & fixed wireless solution experimental testing)">Utility regulation and competition Office experimental licence (4C LTE & fixed wireless solution experimental testing)</a>
									</div>
									<div class="date">14 August 2020</div>
								</div>
							</article>
						</div>				
					</div>
				</div>
				<div class="col-lg-6 d-flex gap-p">
					<div class="row gap-m">
						<div class="col-12 col-sm-6 gap-r" data-aos="flip-left">
							<article class="-items n-bs-1 n-bgc-white-500">
								<div class="-textblock">
									<div class="-ntitle n-fc-dark-500 n-lh-120">
										<a href="#" title="Utility regulation and competition Office experimental licence (4C LTE & fixed wireless solution experimental testing)">Utility regulation and competition Office experimental licence (4C LTE & fixed wireless solution experimental testing)</a>
									</div>
									<div class="date">14 August 2020</div>
								</div>
							</article>
						</div>
						<div class="col-12 col-sm-6 gap-r" data-aos="flip-left">
							<article class="-items n-bs-1 n-bgc-white-500">
								<div class="-textblock">
									<div class="-ntitle n-fc-dark-500 n-lh-120">
										<a href="#" title="Utility regulation and competition Office experimental licence (4C LTE & fixed wireless solution experimental testing)">Utility regulation and competition Office experimental licence (4C LTE & fixed wireless solution experimental testing)</a>
									</div>
									<div class="date">14 August 2020</div>
								</div>
							</article>
						</div>		
						<div class="col-12 gap-r" data-aos="flip-left">
							<article class="-items n-bs-1 n-bgc-white-500">
								<a href="#" title="Utility regulation and competition Office experimental licence (4C LTE & fixed wireless solution experimental testing)">
									<div class="thumbnail-container">
										<div class="thumbnail">
											<img class="lazy" src="{{ $CDN_PATH.'assets/images/utility.png' }}" data-src="{{ $CDN_PATH.'assets/images/utility.png' }}">
										</div>
									</div>
								</a>
								<div class="-textblock">
									<div class="-ntitle n-fc-dark-500 n-lh-120">
										<a href="#" title="Utility regulation and competition Office experimental licence (4C LTE & fixed wireless solution experimental testing)">Utility regulation and competition Office experimental licence (4C LTE & fixed wireless solution experimental testing)</a>
									</div>
									<div class="date">14 August 2020</div>
								</div>
							</article>
						</div>
					</div>
				</div>			
			</div>
		</div>
	</section>
<!-- News & Publications E -->

@endsection
@section('footer_scripts')
<script src="{{ $CDN_PATH.'assets/libraries/OwlCarousel2/2.3.4/js/owl.carousel.min.js' }}" defer></script>
<script src="{{ $CDN_PATH.'assets/libraries/slick/js/slick.min.js' }}" defer></script>
<script src="{{ $CDN_PATH.'assets/js/index.js' }}"></script>
@endsection