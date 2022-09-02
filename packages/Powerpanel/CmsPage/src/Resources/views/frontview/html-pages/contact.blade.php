<section class="home-banner inner-page-banner" data-aos="fade-up">
    <div class="slider owl-carousel">
        <div class="item">
            <div class="thumbnail-container">
                <div class="thumbnail">
                    <picture>
                        <source media="(max-width:1024px)" srcset="{{ $CDN_PATH.'assets/images/contact-banner.jpg' }}">
                        <img src="{{ $CDN_PATH.'assets/images/contact-banner.jpg' }}" alt="Title" title="Inner Banner" />
                        @if(!empty($inner_banner_data[0]) && isset($inner_banner_data[0]))
                        <img src="{!! App\Helpers\resize_image::resize($inner_banner_data[0]->fkIntInnerImgId,1920,853) !!}" alt="{{ $inner_banner_data[0]->varTitle }}" title="{{ $inner_banner_data[0]->varTitle }}" />
                        @endif
                    </picture>
                </div>
            </div>
        </div>
    </div>
    <div class="-banner-contact-item" data-aos="flip-up">
        <div class="container">
            <div class="contact-wrap text-center">
                <h1 class="nqtitle-large n-mb-20">Contact us</h1>
                <div class="desc">
                    <p><span class="s-title">GOT A QUESTION?</span> We are here to help and answer any question you might have. We look forward to hearing from you.</p>
                </div>
                <form class="nqform">
                    <div class="row">   
                        <div class="col-md-6">
                            <div class="ac-form-group">
                                <input type="text" class="ac-input form-control" placeholder="Name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="ac-form-group">
                                <input type="text" class="ac-input form-control" placeholder="Email">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="ac-form-group">
                                <textarea type="text" class="ac-textarea form-control" placeholder="Message"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group ac-form-group n-mb-md-0">
                                <img class="lazy" src="{{ $CDN_PATH.'assets/images/loading.svg' }}" data-src="{{ $CDN_PATH.'assets/images/google-captcha.gif' }}" alt="Google Captcha" title="Google Captcha" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group ac-form-group n-mb-md-0 n-tar-sm n-tal">
                                <button class="ac-btn ac-btn-primary">Send</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<section class="inner-page-gap contact-page n-pb-0">
    <!-- No CSS write for this page -->
    <div class="contact-info n-pt-lg-60 n-pt-30 n-pb-0 n-pb-lg-0">
        <div class="address-info">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="mail-info text-center" data-aos="fade-up">
                            <a href="mailto:info@ofreg.ky">info@ofreg.ky</a>
                        </div>
                        <div class="address-wrap" data-aos="fade-up">
                            <ul class="list-unstyled">
                                <li>
                                    <div class="add-item">
                                        <div class="icn-sec">
                                            <i class="n-icon" data-icon="s-home"></i>
                                        </div>
                                        <div class="info">
                                            <h3 class="c-title">MAILING ADDRESS</h3>
                                            <p>PO Box 10189<br> Grand Cayman KY1-1002<br> CAYMAN ISLANDS</p>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="add-item">
                                        <div class="icn-sec">
                                            <i class="n-icon" data-icon="s-clock"></i>
                                        </div>
                                        <div class="info">
                                            <h3 class="c-title">BUSINESS HOURS</h3>
                                            <p>8.30am to 5pm<br> Monday to Friday</p>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="add-item">
                                        <div class="icn-sec">
                                            <i class="n-icon" data-icon="s-call"></i>
                                        </div>
                                        <div class="info">
                                            <h3 class="c-title">SUPPORT</h3>
                                            <p>P: <a href="tel:+1 (345) 946 4282" title="Call Us On +1 (345) 946 4282">+1 (345) 946 4282</a></p>
                                            <p>F: <a href="tel:+1 (345) 945 8284" title="Call Us On +1 (345) 945 8284">+1 (345) 945 8284</a></p>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="cont-map" data-aos="zoom-in">
            <div class="container-fluid p-0">
                <div class="thumbnail-container">
                    <div class="thumbnail">
                        <img src="{{ $CDN_PATH.'assets/images/map-img.jpg' }}">
                    </div>
                </div>
                <div class="overlay-text">
                    <img class="map-pin" src="{{ $CDN_PATH.'assets/images/map-pin.png' }}">
                    <div class="add-box">
                        <div class="name">PHYSICAL ADDRESS</div>
                        <p>3rd Floor Monaco Towers II,<br> 11 Dr Roy’s Drive, George Town</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>