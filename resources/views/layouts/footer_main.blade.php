@if(Request::segment(1) == '')
<section class="home-testimonial" data-aos="fade-up">	
<div class="container">
<div class="same_title text-center n-mb-60">
      <h2>Testimonial</h2>
  </div>
  <div class="row">
    <div class="col-lg-6 vcenter">
			 <div class="quote-text">
				<span class="sub-heading mb15">Trusted by 1000's of entrepreneurs</span>
				<h2>We improve demand for efficiency and quality in entrepreneurship with creative mind.</h2>
			 </div>
		  </div>
		  <div class="col-lg-6 vcenter">
			 <div class="testi-card mt30 trust-review owl-carousel owl-loaded owl-drag">
			    <div class="items">
				   <div class="review-text">
					  <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type.</p>
				   </div>
				   <div class="test-row-set mt30">
					  <div class="media vcenter">
						 <div class="testi-icon-set img-round80"><img src="{{ $CDN_PATH.'assets/images/team1.jpg' }}" alt="" class="img-fluid"></div>
						 <div class="testi-details-set user-info">
							<h5>Lora Myka</h5>
							<p>ABC Business</p>
						 </div>
					  </div>
				   </div>
				</div>
			 </div>
		  </div>
  </div>
</div>
</section>	

<section class="home-client" data-aos="fade-up">	
<div class="container">
<div class="same_title text-center n-mb-60">
    <h2>Our Client</h2>
      </div>
     <div class="ourclient">
	 
  <div class="owl-carousel owl-theme">
    <div class="item">
	<div class="client-img">
       <div class="thumbnail-container"> 
        <div class="thumbnail">
	    <img src="{{ $CDN_PATH.'assets/images/client-6.png' }}" alt="client">
       </div>
      </div>
	</div>
    </div>
     <div class="item">
	<div class="client-img">
    <div class="thumbnail-container"> 
        <div class="thumbnail">
	<img src="{{ $CDN_PATH.'assets/images/client-2.png' }}" alt="client">
</div>
</div>
	</div>
    </div>
	 <div class="item">
	<div class="client-img">
    <div class="thumbnail-container"> 
        <div class="thumbnail">
	<img src="{{ $CDN_PATH.'assets/images/client-3.png' }}" alt="client">
</div>
</div>
	</div>
    </div>
	 <div class="item">
	<div class="client-img">
	<div class="thumbnail-container"> 
        <div class="thumbnail">
	<img src="{{ $CDN_PATH.'assets/images/client-4.png' }}" alt="client">
</div>
</div>
	</div>
    </div>
	 <div class="item">
	<div class="client-img">
    <div class="thumbnail-container"> 
        <div class="thumbnail">
	<img src="{{ $CDN_PATH.'assets/images/client-5.png' }}" alt="client">
</div>
</div>
	</div>
    </div>
	 <div class="item">
	<div class="client-img">
    <div class="thumbnail-container"> 
        <div class="thumbnail">
	<img src="{{ $CDN_PATH.'assets/images/client-7.png' }}" alt="client">
</div>
	</div>
    </div>
	
    </div>
    
  </div>
</div>
</section>
  
	  
@endif
<footer class="footer-main">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-12">
                <h3 class="f-title">Contact info</h3>
                <div class="contact-info">
                    <ul>
                        @if(isset($objContactInfo->txtAddress)&&  !empty($objContactInfo->txtAddress))
                            <li>{!! ($objContactInfo->txtAddress) !!}</li>
                        @endif

                        @if(isset($objContactInfo->varEmail) && !empty($objContactInfo->varEmail))
                            <li><a href="mailto:{{$objContactInfo->varEmail}}" title="Email Us On {{$objContactInfo->varEmail}}">{!! nl2br($objContactInfo->varEmail) !!}</a></li>
                        @endif

                        @if(isset($objContactInfo->varPhoneNo) && !empty($objContactInfo->varPhoneNo))
                            <li><a href="tel:{{$objContactInfo->varPhoneNo}}" title="Call Us On {{$objContactInfo->varPhoneNo}}">{{$objContactInfo->varPhoneNo}}</a></li>
                        @endif
                    </ul>
                </div> 

                @php $socialAvailable = false; @endphp
                @if((null!==Config::get('Constant.SOCIAL_FB_LINK') && strlen(Config::get('Constant.SOCIAL_FB_LINK')) > 0) || (null!==Config::get('Constant.SOCIAL_TWITTER_LINK') && strlen(Config::get('Constant.SOCIAL_TWITTER_LINK')) > 0) || (null!==Config::get('Constant.SOCIAL_LINKEDIN_LINK') && strlen(Config::get('Constant.SOCIAL_LINKEDIN_LINK')) > 0) || (null!==Config::get('Constant.SOCIAL_YOUTUBE_LINK') && strlen(Config::get('Constant.SOCIAL_YOUTUBE_LINK')) > 0))
                @php $socialAvailable = true; @endphp
                <ul class="ac-share">
                    @if(null!==(Config::get('Constant.SOCIAL_FB_LINK')) && strlen(Config::get('Constant.SOCIAL_FB_LINK')) > 0)
                    <li><a href="{{ Config::get('Constant.SOCIAL_FB_LINK') }}" title="Follow Us On Facebook" target="_blank"><i class="fa fa-facebook"></i></a></li>
                    @endif
                    @if(null!==(Config::get('Constant.SOCIAL_TWITTER_LINK')) && strlen(Config::get('Constant.SOCIAL_TWITTER_LINK')) > 0)
                    <li><a href="{{ Config::get('Constant.SOCIAL_TWITTER_LINK') }}" title="Follow Us On Twitter"><i class="fa fa-twitter" target="_blank"></i></a></li>
                    @endif
                    @if(null!==(Config::get('Constant.SOCIAL_LINKEDIN_LINK')) && strlen(Config::get('Constant.SOCIAL_LINKEDIN_LINK')) > 0)
                    <li><a href="{{ Config::get('Constant.SOCIAL_LINKEDIN_LINK') }}" title="Follow Us On YouTube"><i class="fa fa-linkedin" target="_blank"></i></a></li>
                    @endif
                    @if(null!==(Config::get('Constant.SOCIAL_YOUTUBE_LINK')) && strlen(Config::get('Constant.SOCIAL_YOUTUBE_LINK')) > 0)
                    <li><a href="{{ Config::get('Constant.SOCIAL_YOUTUBE_LINK') }}" title="Follow Us On YouTube"><i class="fa fa-youtube-play" target="_blank"></i></a></li>
                    @endif
                    @if(null!==(Config::get('Constant.SOCIAL_TRIP_ADVISOR_LINK')) && strlen(Config::get('Constant.SOCIAL_TRIP_ADVISOR_LINK')) > 0)
                    <li><a href="{{ Config::get('Constant.SOCIAL_TRIP_ADVISOR_LINK') }}" title="Follow Us On Tripadvisor"><i class="fa fa-tripadvisor" target="_blank"></i></a></li>
                    @endif
                    @if(null!==(Config::get('Constant.SOCIAL_INSTAGRAM_LINK')) && strlen(Config::get('Constant.SOCIAL_INSTAGRAM_LINK')) > 0)
                    <li><a href="{{ Config::get('Constant.SOCIAL_INSTAGRAM_LINK') }}" title="Follow Us On Instagram"><i class="fa fa-instagram" target="_blank"></i></a></li>
                    @endif
                </ul>
                @endif
            </div>
            <div class="col-lg-2 col-md-6 col-sm-12">
                <div class="links-info">
                    <h3 class="f-title">Quick Links</h3>
                    @if(isset($QuickLinksMenu))
                        {!! $QuickLinksMenu !!}
                    @endif
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <h3 class="f-title">Follow us on Instagram</h3>
                <a href="javascript:void(0)" title="Instagram">
                    <img src="{{ $CDN_PATH.'assets/images/instagram.jpg' }}" alt="Instagram">
                </a>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="newsletter-info">
                    <h3 class="f-title">Subscribe to Our Newsletter</h3>
                    <p>Join our subscribes list to get the latest news, updates and special offers delivered directly in your inbox.</p>
					
					<form id="subscription_form" method="POST" action="{{url('news-letter')}}">
                    		 @csrf
                    <form>
                        <div class="input-group form-info">
						  <input type="email" class="form-control" name="email" placeholder="Enter your email" />
                  
                           <div class="input-group-append">
						     <button class="btn btn-primary" type="submit" title="GO">GO</button>
                            <button class="btn btn-primary" type="button" title="GO">GO</button>
                          </div>
                        </div>
						<span class="help-block">{{ $errors->first('email') }}</span>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="defalut-footer">    
        <div class="container">
            <hr>
            <div class="row">
                <div class="col-lg-5 col-sm-12">
                    <div class="n-fw-400 n-lh-130">Copyright &#169; {{ date("Y") }} {{ Config::get("Constant.SITE_NAME") }}. All rights reserved.</div>
                </div>
                <div class="col-lg-4 col-sm-12 text-center">
                    @if(isset($footerMenu))
                        {!! $footerMenu !!}
                    @endif
                </div>
                <div class="col-lg-3 col-sm-12">
                    <div class="-crafted text-right">Crafted by: <a href="https://www.netclues.ky/" target="_blank" rel="nofollow" title="Netclues!">
                        <img src="{{ $CDN_PATH.'assets/images/netclues.gif' }}" alt="Netclues!"></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

{{-- <div class="content print-footer">
    <div class="row text-center">
        <div class="col-12">
             @if(isset($objContactInfo->txtAddress)&&  !empty($objContactInfo->txtAddress))
            <div class="n-fs-18 n-fw-500 n-fc-black-500 n-lh-130">{!! ($objContactInfo->txtAddress) !!}</div>
            @endif
               @if(isset($objContactInfo->mailingaddress)&&  !empty($objContactInfo->mailingaddress))
            <div class="n-fs-18 n-fw-500 n-fc-black-500 n-lh-130">Mailing Address: <br>{!! ($objContactInfo->mailingaddress) !!}</div>
            @endif
            <div class="n-fs-16 n-fw-500 n-fc-black-500 n-lh-130 n-mt-15">Copyright &#169; {{ date("Y") }} {{ Config::get("Constant.SITE_NAME") }}. All Rights Reserved.</div>
            <div class="n-fs-16 n-fw-500 n-fc-black-500 n-lh-130 -crafted">Crafted by: <a href="https://www.netclues.ky/" target="_blank" rel="nofollow" title="Netclues!" class="n-fc-a-500"><i class="n-icon" data-icon="s-netclues"></i></a></div>
        </div>
    </div>
</div> --}}

<!-- Cookies S -->
@if(Cookie::get('cookiesPopupStore') !="cookiesPopupStore")
    <div class="ac-cookies">
        <div class="ac-c-info">
            This site uses cookies: <a class="ac-c-find" href="{{ url('privacy-policy') }}" title="Find out more">Find out more</a>
            <br><a class="ac-c-btn" id="cookie_policy" href="javascript:void(0)" title="Okay, Thanks">Okay, Thanks</a>
        </div>
    </div>
@endif
<!-- Cookies E -->




