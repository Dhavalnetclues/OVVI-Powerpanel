<!-- Header Section -->
<header>
    <div class="header-top alert_slider_sec alert_red">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    @if(isset($alertsArr) && !empty($alertsArr))
                        <div class="alert_slider_div">
                            <div class="alert_label"><i class="fi flaticon-danger"></i> Alerts</div>
                            <div class="alert_slide owl-carousel owl-theme">
                                <?php
                                foreach ($alertsArr as $key => $value) {
                                    $colour = 'color:red';
                                    if($value->intAlertType == 3) {
                                        $colour = 'color:#008a00';
                                    } elseif($value->intAlertType == 2) {
                                        $colour = 'color:#0a34ba';
                                    } 
                                    ?>
                                    <div class="alert_title">
                                        <a href="javascript:void(0)" style="<?php $colour;?>"><?php echo $value->varTitle; ?></a>
                                    </div>
                                <?php }
                                ?>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="col-lg-4">
                    <div class="quick_head pull-right">
                        @php $socialAvailable = false; @endphp
                        @if((null!==Config::get('Constant.SOCIAL_FB_LINK') && strlen(Config::get('Constant.SOCIAL_FB_LINK')) > 0) || (null!==Config::get('Constant.SOCIAL_TWITTER_LINK') && strlen(Config::get('Constant.SOCIAL_TWITTER_LINK')) > 0) || (null!==Config::get('Constant.SOCIAL_YOUTUBE_LINK') && strlen(Config::get('Constant.SOCIAL_YOUTUBE_LINK')) > 0))
                        @php $socialAvailable = true; @endphp
                        <div class="social_head">
                            <ul class="social">
                                @if(null!==(Config::get('Constant.SOCIAL_FB_LINK')) && strlen(Config::get('Constant.SOCIAL_FB_LINK')) > 0)
                                <li><a href="{{ Config::get('Constant.SOCIAL_FB_LINK') }}" title="Follow Us On Facebook" target="_blank"><i class="fa fa-facebook"></i></a></li>
                                @endif
                                @if(null!==(Config::get('Constant.SOCIAL_TWITTER_LINK')) && strlen(Config::get('Constant.SOCIAL_TWITTER_LINK')) > 0)
                                <li><a href="{{ Config::get('Constant.SOCIAL_TWITTER_LINK') }}" title="Follow Us On Twitter" target="_blank"><i class="fa fa-twitter"></i></a></li>
                                @endif
                                @if(null!==(Config::get('Constant.SOCIAL_YOUTUBE_LINK')) && strlen(Config::get('Constant.SOCIAL_YOUTUBE_LINK')) > 0)
                                <li><a href="{{ Config::get('Constant.SOCIAL_YOUTUBE_LINK') }}" title="Follow Us On YouTube" target="_blank"><i class="fa fa-youtube-play" ></i></a></li>
                                @endif
                                @if(null!==(Config::get('Constant.SOCIAL_TRIP_ADVISOR_LINK')) && strlen(Config::get('Constant.SOCIAL_TRIP_ADVISOR_LINK')) > 0)
                                <li><a href="{{ Config::get('Constant.SOCIAL_TRIP_ADVISOR_LINK') }}" title="Follow Us On Tripadvisor" target="_blank"><i class="fa fa-tripadvisor" ></i></a></li>
                                @endif
                                @if(null!==(Config::get('Constant.SOCIAL_INSTAGRAM_LINK')) && strlen(Config::get('Constant.SOCIAL_INSTAGRAM_LINK')) > 0)
                                <li><a href="{{ Config::get('Constant.SOCIAL_INSTAGRAM_LINK') }}" title="Follow Us On Instagram" target="_blank"><i class="fa fa-instagram"></i></a></li>
                                @endif
                            </ul>
                        </div>
                        @endif
                        @if(!empty($quickLinks))
                        <div class="quick_links" @if($socialAvailable==false) style="border-left: none;" @endif>
                             <div class="dropdown">
                                <a href="javascript:void(0)" class="btn" title="Quick Links" data-toggle="dropdown">Quick Links<span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    @foreach($quickLinks as $key => $ql)
                                    @if(isset($ql['link']) && $ql['link']!="")
                                    <li><a href="{{ $ql['link'] }}" @if($ql['varLinkType'] == 'external') target="_blank" @endif title="{{ $ql['varTitle'] }}">{{ $ql['varTitle'] }}</a></li>
                                    @endif
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="clearfix"></div>

                </div>
            </div>
        </div>
    </div>

    <div class="header-section">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="h-s__row">
                        <div class="h-s__logo pull-left" itemscope="" itemtype="http://schema.org/Organization">
                            <a href="{{ url('/') }}" title="{{ Config::get("Constant.SITE_NAME") }}">
                                <meta itemprop="name" content="{{ Config::get("Constant.SITE_NAME") }}">
                                <meta itemprop="address" content="{{(isset($objContactInfo->txtAddress) && !empty($objContactInfo->txtAddress))?$objContactInfo->txtAddress:''}}">
                                <!--<img itemprop="image" src="{!! App\Helpers\resize_image::resize(Config::get('Constant.FRONT_LOGO_ID')) !!}" alt="{{ Config::get("Constant.SITE_NAME") }}">-->
                                <img itemprop="image" src="{{ App\Helpers\resize_image::resize(Config::get('Constant.FRONT_LOGO_ID')) }}" alt="{{ Config::get("Constant.SITE_NAME") }}">
                            </a>
                        </div>
                        <div class="h-s__search pull-right">
                            <div class="nav-overlay" onclick="closeNav()"></div>
                            <div class="menu_open_close text-right">
                                <a href="javascript:void(0)" class="menu__open" id="menu__open" onclick="openNav()"><span></span></a>
                                <a href="javascript:void(0)" class="menu__close" id="menu__close" onclick="closeNav()"><span></span></a>
                            </div>
                            <a href="javascript:void(0)" title="Search" data-toggle="modal" data-target="#search_box" data-backdrop="static" class="top_search"><i class="ri-search-line"></i></a>
                        </div>
                        <div class="h-s__menu">
                            <nav class="menu" id="menu">
                                <div class="menu_mobile_visibility">
                                    <a href="{{ Config::get("Constant.SITE_PATH") }}" title="{{ Config::get("Constant.SITE_NAME") }}">
                                        <div class="menu_title">
                                            <img src="{{ App\Helpers\resize_image::resize(Config::get('Constant.FRONT_LOGO_ID')) }}" alt="{{ Config::get("Constant.SITE_NAME") }}">
                                        </div>
                                    </a>
                                </div>
                                @if(isset($HeadreMenuhtml))
                                    {!! $HeadreMenuhtml !!}
                                @endif
                            </nav>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</header>