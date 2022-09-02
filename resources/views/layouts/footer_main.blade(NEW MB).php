<footer class="footer-main1 n-pv-50 n-pv-lg-100">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-sm-6" data-aos="fade-up">
                <div class="nqtitle-small n-fc-white-500">Locate Us</div>

                <div class="n-fs-16 n-fw-500 n-fc-white-500 n-lh-150 n-mt-15 n-mt-lg-30">Utility Regulation and Competition Office, 3rd Floor, Alissta Towers, 85 North Sound Rd. Grand Cayman, CAYMAN ISLANDS</div>

                <div class="n-fs-18 n-fw-500 n-fc-white-500 n-lh-130 n-mt-20 n-mt-lg-40">Mailing Address:</div>

                <div class="n-fs-16 n-fw-500 n-fc-white-500 n-lh-150 n-mt-10">PO Box 10189, Grand Cayman KY1-1002, CAYMAN ISLANDS</div>
            </div>

            <div class="col-lg-2 col-sm-6  n-mt-25 n-mt-sm-0" data-aos="fade-up">
                <div class="nqtitle-small n-fc-white-500">Contact Us</div>

                <div class="item n-mt-lg-30 n-mt-15">
                    <div class="n-fs-18 n-fw-500 n-fc-white-500 n-lh-130">Email:</div>
                    <div class="n-fs-16 n-fw-500 n-fc-white-500 n-lh-130 n-mt-5"><a href="#" title="info@ofreg.ky" class="n-ah-a-500">info@ofreg.ky</a></div>
                </div>

                <div class="item n-mt-lg-30 n-mt-15">
                    <div class="n-fs-18 n-fw-500 n-fc-white-500 n-lh-130">Phone:</div>
                    <div class="n-fs-16 n-fw-500 n-fc-white-500 n-lh-130 n-mt-5"><a href="#" title="+1(345) 948 4282" class="n-ah-a-500">+1(345) 948 4282</a></div>
                </div>

                <div class="item n-mt-lg-30 n-mt-15">
                    <div class="n-fs-18 n-fw-500 n-fc-white-500 n-lh-130">Fax:</div>
                    <div class="n-fs-16 n-fw-500 n-fc-white-500 n-lh-130 n-mt-5">+1(345) 845 8284</div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-4 col-sm-6 n-mt-25 n-mt-lg-0" data-aos="fade-up">
                <div class="nqtitle-small n-fc-white-500">Subscribe & Stay Updated</div>

                <div class="n-mt-15 n-mt-lg-30 ac-form-md">
                    {!! Form::open(['method' => 'post','class'=>'newslatter subscription_form','id'=>'subscription_form']) !!}
                        <div class="form-group ac-form-group n-mb-0">
                            <label class="ac-label ac-label-md" for="email">Enter your email</label>
                            {!! Form::email('email',  old('email') , array('id' => 'email', 'name' => 'email', 'class' => 'form-control ac-input')) !!}
                            <div class="success"></div>
                            <label class="error"></label>
                        </div>
                        {{-- <div class="alert alert-success n-mv-15 success" role="alert">
                            A simple primary alert—check it out!
                        </div> --}}
                        <button type="submit" class="n-mt-15 n-mt-lg-30 ac-btn ac-btn-primary btn-block text-uppercase" title="Subscribe">Subscribe</button>
                    {!! Form::close() !!}
                </div>
            </div>

            <div class="col-lg-3 offset-xl-1 col-sm-6 n-mt-25 n-mt-lg-0" data-aos="fade-up">
                <div class="nqtitle-small n-fc-white-500">Follow on Social Media</div>

                @php $socialAvailable = false; @endphp
                @if((null!==Config::get('Constant.SOCIAL_FB_LINK') && strlen(Config::get('Constant.SOCIAL_FB_LINK')) > 0) || (null!==Config::get('Constant.SOCIAL_TWITTER_LINK') && strlen(Config::get('Constant.SOCIAL_TWITTER_LINK')) > 0) || (null!==Config::get('Constant.SOCIAL_YOUTUBE_LINK') && strlen(Config::get('Constant.SOCIAL_YOUTUBE_LINK')) > 0))
                @php $socialAvailable = true; @endphp
                    <ul class="ac-share n-fs-16 n-fw-500 n-fc-white-500 n-lh-130 n-mt-lg-30 n-mt-15">
                        @if(null!==(Config::get('Constant.SOCIAL_FB_LINK')) && strlen(Config::get('Constant.SOCIAL_FB_LINK')) > 0)
                            <li data-aos="flip-left" data-aos-delay="500"><a href="{{ Config::get('Constant.SOCIAL_FB_LINK') }}" title="Follow Us On Facebook" target="_blank"><i class="fa fa-facebook"></i></a></li>
                        @endif
                        @if(null!==(Config::get('Constant.SOCIAL_TWITTER_LINK')) && strlen(Config::get('Constant.SOCIAL_TWITTER_LINK')) > 0)
                            <li data-aos="flip-left" data-aos-delay="500"><a href="{{ Config::get('Constant.SOCIAL_TWITTER_LINK') }}" title="Follow Us On Twitter"><i class="fa fa-twitter" target="_blank"></i></a></li>
                        @endif
                        @if(null!==(Config::get('Constant.SOCIAL_YOUTUBE_LINK')) && strlen(Config::get('Constant.SOCIAL_YOUTUBE_LINK')) > 0)
                            <li data-aos="flip-left" data-aos-delay="500"><a href="{{ Config::get('Constant.SOCIAL_YOUTUBE_LINK') }}" title="Follow Us On YouTube"><i class="fa fa-youtube-play" target="_blank"></i></a></li>
                        @endif
                        @if(null!==(Config::get('Constant.SOCIAL_TRIP_ADVISOR_LINK')) && strlen(Config::get('Constant.SOCIAL_TRIP_ADVISOR_LINK')) > 0)
                            <li data-aos="flip-left" data-aos-delay="500"><a href="{{ Config::get('Constant.SOCIAL_TRIP_ADVISOR_LINK') }}" title="Follow Us On Tripadvisor"><i class="fa fa-tripadvisor" target="_blank"></i></a></li>
                        @endif
                        @if(null!==(Config::get('Constant.SOCIAL_INSTAGRAM_LINK')) && strlen(Config::get('Constant.SOCIAL_INSTAGRAM_LINK')) > 0)
                            <li data-aos="flip-left" data-aos-delay="500"><a href="{{ Config::get('Constant.SOCIAL_INSTAGRAM_LINK') }}" title="Follow Us On Instagram"><i class="fa fa-instagram" target="_blank"></i></a></li>
                        @endif
                    </ul>
                @endif

                <div class="n-fs-18 n-fw-500 n-fc-white-500 n-lh-130 n-mt-20 n-mt-lg-40">Quick Links</div>
                <ul class="nqul -nav n-mt-15 n-fs-16 n-fw-500 n-fc-white-500 n-lh-130">
                    <li><a href="#" title="Sitemap" class="n-ah-a-500">Sitemap</a></li>
                    <li><a href="#" title="Terms of Use" class="n-ah-a-500">Terms of Use</a></li>
                    <li><a href="#" title="Contact Us" class="n-ah-a-500 active">Contact Us</a></li>
                </ul>
            </div>
        </div>

        <div class="row n-mt-40 n-mt-lg-80" data-aos="fade-up" data-aos-offset="0">
            <div class="col-lg-6 n-tac n-tal-lg">
                <div class="n-fs-16 n-fw-500 n-fc-white-500 n-lh-130">Copyright &#169; <?php echo date("Y"); ?> {{ Config::get("Constant.SITE_NAME") }}. All Rights Reserved.</div>
            </div>
            <div class="col-lg-6 n-tac n-tar-lg">
                <div class="n-fs-16 n-fw-500 n-fc-white-500 n-lh-130">Website Designed &amp; Developed By: <a href="https://www.netclues.ky/" target="_blank" rel="nofollow" title="Netclues!" class="n-fc-a-500">Netclues!</a></div>
            </div>
        </div>
    </div>
</footer>

<!-- Cookies S -->
<!-- <div class="ac-cookies">
    <div class="ac-c-info">
        This site uses cookies: <a class="ac-c-find" href="#" title="Find out more">Find out more</a>
        <br><a class="ac-c-btn" href="javascript:void(0)" title="Okay, Thanks">Okay, Thanks</a>
    </div>
</div> -->
<!-- Cookies E -->