<section class="home-banner inner-page-banner" data-aos="fade-up">
    <div class="slider owl-carousel">
        <div class="item">
            <div class="thumbnail-container">
                <div class="thumbnail">
                    <picture>
                        <source media="(max-width:1024px)" srcset="{{ $CDN_PATH.'assets/images/news-banner-m.jpg' }}">
                        <img src="{{ $CDN_PATH.'assets/images/news-banner.jpg' }}" alt="Title" title="Inner Banner" />
                        @if(!empty($inner_banner_data[0]) && isset($inner_banner_data[0]))
                        <img src="{!! App\Helpers\resize_image::resize($inner_banner_data[0]->fkIntInnerImgId,1920,853) !!}" alt="{{ $inner_banner_data[0]->varTitle }}" title="{{ $inner_banner_data[0]->varTitle }}" />
                        @endif
                    </picture>
                </div>
            </div>
        </div>
    </div>
    <div class="-banner-item" data-aos="flip-up">
        <div class="container">
            <div class="container-w">
                <div class="row">
                    <div class="col-12">
                        <div class="banner-tabs">
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">News</a>
                                </li>
                                <li class="nav-item search-tab">
                                    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">
                                        <span class="search-wrap">
                                            <button type="submit"><i class="n-icon" data-icon="s-search"></i></button>
                                            <input type="text" placeholder="Search">
                                        </span>
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                    <div class="link-list">
                                        <ul>
                                            <li><a href="#">All News<i class="n-icon" data-icon="s-arrow-l"></i></a></li>
                                            <li><a href="#">Energy <i class="n-icon" data-icon="s-arrow-l"></i></a></li>
                                            <li><a href="#">Fuel <i class="n-icon" data-icon="s-arrow-l"></i></a></li>
                                            <li><a href="#">ICT <i class="n-icon" data-icon="s-arrow-l"></i></a></li>
                                            <li><a href="#">Water <i class="n-icon" data-icon="s-arrow-l"></i></a></li>
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
</section>
<section class="inner-page-gap news-page">
    <!-- No CSS write for this page -->
    <div class="container">
        <div class="row">
            <div class="col-lg-4" data-aos="fade-up">
                <div class="date-val n-mb-10 n-mb-lg-20">20 august 2019</div>
                <h1 class="nqtitle-large n-mb-20">
                OfReg Regulatory Accelerator
                </h1>
                <div class="cms desc n-mb-30 n-mb-lg-0">
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                    <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                </div>
            </div>
            <div class="col-lg-8 n-pl-lg-0">
                <div class="row">
                    <div class="col-md-6 gap">
                        <div class="news-item" data-aos="flip-left">
                            <div class="date-val n-mb-10 n-mb-lg-20">20 august 2019</div>
                            <div class="nqtitle"><a href="#">Fuel Sector Forum on Bulk Tanks & Pipeline Operations</a></div>
                            <div class="desc">
                                <p>Dicta omnes atomorum voluptatum definitionem petentium sit at, vel at quis corrumpit facilisi contentiones per.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 gap">
                        <div class="news-item" data-aos="flip-left">
                            <div class="date-val n-mb-10 n-mb-lg-20">20 august 2019</div>
                            <div class="nqtitle"><a href="#">Fuel Sector Forum on Bulk Tanks & Pipeline Operations</a></div>
                            <div class="desc">
                                <p>Dicta omnes atomorum voluptatum definitionem petentium sit at, vel at quis corrumpit facilisi contentiones per.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 gap">
                        <div class="news-item" data-aos="flip-left">
                            <div class="date-val n-mb-10 n-mb-lg-20">20 august 2019</div>
                            <div class="nqtitle"><a href="#">Fuel Sector Forum on Bulk Tanks & Pipeline Operations</a></div>
                            <div class="desc">
                                <p>Dicta omnes atomorum voluptatum definitionem petentium sit at, vel at quis corrumpit facilisi contentiones per.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 gap">
                        <div class="news-item" data-aos="flip-left">
                            <div class="date-val n-mb-10 n-mb-lg-20">20 august 2019</div>
                            <div class="nqtitle"><a href="#">Fuel Sector Forum on Bulk Tanks & Pipeline Operations</a></div>
                            <div class="desc">
                                <p>Dicta omnes atomorum voluptatum definitionem petentium sit at, vel at quis corrumpit facilisi contentiones per.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 gap">
                        <div class="news-item" data-aos="flip-left">
                            <div class="date-val n-mb-10 n-mb-lg-20">20 august 2019</div>
                            <div class="nqtitle"><a href="#">Fuel Sector Forum on Bulk Tanks & Pipeline Operations</a></div>
                            <div class="desc">
                                <p>Dicta omnes atomorum voluptatum definitionem petentium sit at, vel at quis corrumpit facilisi contentiones per.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 gap">
                        <div class="news-item" data-aos="flip-left">
                            <div class="date-val n-mb-10 n-mb-lg-20">20 august 2019</div>
                            <div class="nqtitle"><a href="#">Fuel Sector Forum on Bulk Tanks & Pipeline Operations</a></div>
                            <div class="desc">
                                <p>Dicta omnes atomorum voluptatum definitionem petentium sit at, vel at quis corrumpit facilisi contentiones per.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <a href="#" class="ac-btn ac-btn-primary" title="More News">More News</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>