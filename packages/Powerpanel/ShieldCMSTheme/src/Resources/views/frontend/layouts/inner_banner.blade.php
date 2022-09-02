@if(isset($inner_banner_data) && count($inner_banner_data) > 0)
<section>
	<div class="inner-banner">
		<div id="inner-banner" class="carousel slide" data-ride="carousel">
			<div class="carousel-inner i-b__radisu">
				@foreach($inner_banner_data as $key=>$inner_banner)
				<div class="item @if($key==0) active @endif">
					<div class="i-b_fill" style="background-image:url('{!! App\Helpers\resize_image::resize($inner_banner->fkIntImgId,1920,312) !!}'); background-size: cover;">
						<div class="i-b_caption">
							<div class="container">
								<div class="row">
									<div class="col-sm-12">
										<div class="i-n_c_title text-center">
											<h2 class="banner_h2_div">{{ isset($detailPageTitle) ?$detailPageTitle:strtoupper($currentPageTitle) }}</h2>
										</div>
										<div class="row">
											<div class="col-md-9 col-sm-12 col-xs-12">
												
												@if(isset($breadcrumb) && count($breadcrumb)>0)
												<ul class="ac-breadcrumb">
													<li><a href="{{url('/')}}" title="Home">Home</a></li>
												</ul>
												@else
												<ul class="ac-breadcrumb">
													<li><a href="{{url('/')}}" title="Home">Home</a></li>
												</ul>
												@endif
											</div>
											<div class="col-md-3 col-sm-12 col-xs-12">
												<ul class="ac-media clearfix">
													@if(Config::get('Constant.DEFAULT_SHARINGOPTION') == "Y")
													<li>
														<div class="share dropdown">
															<a class="media_link dropdown-toggle" data-toggle="dropdown" title="Share" href="#"><i class="fi flaticon-share"></i>Share</a>
															<!-- AddToAny BEGIN -->
															<div class="a2a_kit a2a_kit_size_32 a2a_default_style dropdown-menu">
																<a class="a2a_button_facebook"></a>
																<a class="a2a_button_twitter"></a>
																<a class="a2a_button_linkedin"></a>
																<a class="a2a_button_google_gmail"></a>
															</div>
															<script>
																	var a2a_config = a2a_config || {};
																	a2a_config.onclick = 1;
															</script>
															<script async src="https://static.addtoany.com/menu/page.js"></script>
															<!-- AddToAny END -->
														</div>
													</li>
													@endif
													@if(Config::get('Constant.DEFAULT_EMAILTOFRIENDOPTION') == "Y")
													<li>
														<div class="email">
															<a class="media_link" data-toggle="modal" href="#Modal_emailtofriend" title="Email"><i class="fi flaticon-mail"></i>Email</a>
														</div>
													</li>
													@endif
												</ul>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				@endforeach
			</div>
		</div>
	</div>
</section>
@else
<section>
	<div class="inner-banner">
		<div id="inner-banner" class="carousel slide" data-ride="carousel">
			<!-- Wrapper for slides -->
			<div class="carousel-inner i-b__radisu">
				<div class="item active">
					<div class="i-b_fill" style="background: url('{{ $CDN_PATH.'assets/images/inner-banner.jpg' }}'); background-size: cover;">
						<div class="i-b_caption">
							<div class="container">
								<div class="row">
									<div class="col-sm-12">
										<div class="i-n_c_title text-center">
											<h2 class="banner_h2_div">{{ isset($detailPageTitle) ?$detailPageTitle:strtoupper($currentPageTitle) }}</h2>
										</div>
										<div class="row">
											<div class="col-md-9 col-sm-12 col-xs-12">
												
												@if(isset($breadcrumb) && count($breadcrumb)>0)
												<ul class="ac-breadcrumb">
													<li><a href="{{url('/')}}" title="Home">Home</a></li>
													
												</ul>
												@else
												<ul class="ac-breadcrumb">
													<li><a href="{{url('/')}}" title="Home">Home</a></li>
												</ul>
												@endif
											</div>
											<div class="col-md-3 col-sm-12 col-xs-12">
												<ul class="ac-media clearfix">
													@if(Config::get('Constant.DEFAULT_SHARINGOPTION') == "Y")
													<li>
														<div class="share dropdown">
															<a class="media_link dropdown-toggle" data-toggle="dropdown" title="Share" href="#"><i class="fi flaticon-share"></i>Share</a>
															<!-- AddToAny BEGIN -->
															<div class="a2a_kit a2a_kit_size_32 a2a_default_style dropdown-menu">
																<a class="a2a_button_facebook"></a>
																<a class="a2a_button_twitter"></a>
																<a class="a2a_button_linkedin"></a>
																<a class="a2a_button_google_gmail"></a>
															</div>
															<script>
																	var a2a_config = a2a_config || {};
																	a2a_config.onclick = 1;
															</script>
															<script async src="https://static.addtoany.com/menu/page.js"></script>
															<!-- AddToAny END -->
														</div>
													</li>
													@endif
													@if(Config::get('Constant.DEFAULT_EMAILTOFRIENDOPTION') == "Y")
													<li>
														<div class="email">
															<a class="media_link" data-toggle="modal" href="#Modal_emailtofriend" title="Email"><i class="fi flaticon-mail"></i>Email</a>
														</div>
													</li>
													@endif
												</ul>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
@endif