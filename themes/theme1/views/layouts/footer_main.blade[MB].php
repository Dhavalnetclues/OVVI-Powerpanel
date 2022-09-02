<footer>
    <!-- Subscibe Part S -->
    <div class="subscribe-sec">
        <div class="container">
            <div class="mailling_box">
                <div class="row">
                    <div class="col-md-3 col-sm-4 col-xs-4 col-xss-12">
                        <h4 class="sub_title"><span>Subscribe to Our</span> Newsletter</h4>
                    </div>
                    <div class="col-md-9 col-sm-8 col-xs-8 col-xss-12">
                        {!! Form::open(['method' => 'post','class'=>'newslatter subscription_form','id'=>'subscription_form']) !!}
                        <div class="form-group">
                            {!! Form::email('email',  old('email') , array('id' => 'email', 'class' => 'form-control', 'placeholder'=>'Enter your Email Address *')) !!}
                            <div class="success"></div>
                            <label class="error"></label>
                            <button class="btn" title="Subscribe"><i class="fa fa-paper-plane"></i></button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Subscibe Part E -->
    <div class="footer-main">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-sm-6 col-xs-6 col-xss-12">
                    <div class="footer_box mail_box animated fadeInLeft">
                        <div class="footer_logo animated fadeInLeft">
                            <a href="{{ url('/') }}" title="{{ Config::get("Constant.SITE_NAME") }}">
                                <img src="{{ App\Helpers\resize_image::resize(Config::get('Constant.FRONT_LOGO_ID')) }}" alt="{{ Config::get("Constant.SITE_NAME") }}">
                            </a>
                        </div>
                        <div class="about_foot">
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. </p>
                        </div>
                        @php $socialAvailable = false; @endphp
                        @if((null!==Config::get('Constant.SOCIAL_FB_LINK') && strlen(Config::get('Constant.SOCIAL_FB_LINK')) > 0) || (null!==Config::get('Constant.SOCIAL_TWITTER_LINK') && strlen(Config::get('Constant.SOCIAL_TWITTER_LINK')) > 0) || (null!==Config::get('Constant.SOCIAL_YOUTUBE_LINK') && strlen(Config::get('Constant.SOCIAL_YOUTUBE_LINK')) > 0))
                        @php $socialAvailable = true; @endphp
                        <ul class="social">
                            @if(null!==(Config::get('Constant.SOCIAL_FB_LINK')) && strlen(Config::get('Constant.SOCIAL_FB_LINK')) > 0)
                            <li><a href="{{ Config::get('Constant.SOCIAL_FB_LINK') }}" title="Follow Us On Facebook" target="_blank"><i class="fa fa-facebook"></i></a></li>
                            @endif
                            @if(null!==(Config::get('Constant.SOCIAL_TWITTER_LINK')) && strlen(Config::get('Constant.SOCIAL_TWITTER_LINK')) > 0)
                            <li><a href="{{ Config::get('Constant.SOCIAL_TWITTER_LINK') }}" title="Follow Us On Twitter"><i class="fa fa-twitter" target="_blank"></i></a></li>
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
                </div>
                <div class="col-md-3 col-sm-6 col-xs-6 col-xss-12">
                    <div class="footer_box footer_main_link animated fadeInUp">
                        <h4 class="foot_title">Main Links</h4>
                        <ul class="footer_links clearfix">
                            <li><a class="active" href="{{ url('/') }}" title="Home">Home</a></li>
                            <li><a href="{{ url('/about-us') }}" title="About Us">About Us</a></li>
                            <li><a href="{{ url('/news') }}" title="News">News</a></li>
                            <li><a href="{{ url('/events') }}" title="Events">Events</a></li>
                            <li><a href="{{ url('/blogs') }}" title="Publication">Blogs</a></li>
                            <li><a href="{{ url('/contact-us') }}" title="Contact">Contact</a></li>
                        </ul>
                    </div>
                </div>
                <div class="clearfix visible-sm visible-xs"></div>
                @if(!empty($quickLinks))
                <div class="col-md-3 col-sm-6 col-xs-6 col-xss-12">
                    <div class="footer_box quick_box animated fadeInUp">
                        <h4 class="foot_title">Quick Links</h4>
                        <ul class="footer_links clearfix">
                            @foreach($quickLinks as $key => $ql)
                            @if(isset($ql['link']) && $ql['link']!="")
                            <li><a href="{{ $ql['link'] }}" @if($ql['varLinkType'] == 'external') target="_blank" @endif title="{{ $ql['varTitle'] }}">{{ $ql['varTitle'] }}</a></li>
                            @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif
                @if(isset($objContactInfo) && !empty($objContactInfo))
                <div class="col-md-3 col-sm-6 col-xs-6 col-xss-12">
                    <div class="footer_box newslatter_sign animated fadeInRight">
                        <h4 class="foot_title">Contact Us</h4>
                        <div class="info_address">

                            <p>
                                {{ $objContactInfo->varTitle }}<br>
                                {!! nl2br($objContactInfo->mailingaddress) !!}
                            </p>
                            @if(!empty($objContactInfo->varPhoneNo))
                            @php
                            $c_phone = unserialize($objContactInfo->varPhoneNo);
                            $c_phone =count($c_phone)>0?$c_phone[0]:$c_phone;
                            @endphp
                            <p><a href="tel:{{ $c_phone }}">{{ $c_phone }}</a></p>
                            @endif
                            @if(!empty($objContactInfo->varEmail))
                            @php
                            $c_email = unserialize($objContactInfo->varEmail);
                            $c_email =count($c_email)>0?$c_email[0]:$c_email;
                            @endphp
                            <p><a href="mailto:{{ $c_email }}">{{ $c_email }}</a></p>
                            @endif
                            @php
                            $disp_url = url('/');
                            $disp_url = preg_replace('#^https?://#', '', $disp_url);
                            @endphp
                            <p><a href="{{ url('/') }}">{{ $disp_url }}</a></p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-8 col-sm-12 col-xs-12 animated fadeInLeft">
                    <div class="f-m_copyright">Copyright &copy;
                        <?php echo date("Y"); ?>
                        {{ Config::get("Constant.SITE_NAME") }}. All Rights Reserved.</div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12 animated fadeInLeft">
                    <ul class="f-m_link">
                        <li><a class="active" title="Privacy Policy" href="#">Privacy Policy</a></li>
                        <li><a title="Site Map" href="#">Site Map</a></li>
                    </ul>
                </div>
                 <!--<iframe src="http://www.shieldcms.netcluesdemo.com/livechat/" style="position: absolute;z-index: 99;right: 65px;bottom: -1px;height: 606px;"></iframe>-->
                <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12 animated fadeInLeft">
                    <div class="f-m_designed">Website Designed &amp; Developed By: <a class="f-m_d_logo" href="https://www.netclues.com" target="_blank" rel="nofollow" title="Netclues!"></a></div>
                </div>
            </div>

        </div>
    </div>

</footer>
@if(Config::get('Constant.DEFAULT_FEEDBACKFORM') == "Y")
<div class="feedback_form" >
    <div style="top:calc(50% - 62px);"  class="feedback_icon" id='feedback_form_model' title="Feedback" data-toggle="modal" data-target="#exampleModal"><i class="ri-chat-1-line"></i></div>
</div>
@endif
<script src="https://www.google.com/recaptcha/api.js?onload=myCallBack&render=explicit" async defer></script>
<script>
$(document).ready(function () {

    $("#feedback_form_model").click(function () {


        $("#feedbacks_form").validate({
            ignore: [],
            rules: {
                name: {
                    required: true,
                    no_url: true,
                    xssProtection: true,
                    check_special_char: true,
                    badwordcheck: true
                },
                phone: {
                    minlength: 6,
                    maxlength: 10,
                    check_special_char: true,
                    xssProtection: true,
                    phonenumber: true,
                },
                email: {
                    required: true,
                    xssProtection: true,
                    check_special_char: true,
                    emailFormat: true,
                    badwordcheck: true
                },
                reason: {
                    required: true,
                    no_url: true,
                    xssProtection: true,
                    check_special_char: true,
                    badwordcheck: true
                },
                feedback: {
                    no_url: true,
                    xssProtection: true,
                    check_special_char: true,
                    badwordcheck: true
                },
                hiddenRecaptcha: {
                    required: function () {
                        if (grecaptcha.getResponse(recaptcha1) == '') {
                            return true;
                        } else {
                            return false;
                        }
                    }
                }
            },
            messages: {
                name: {
                    required: "Please enter name."
                },
                phone: {
////                    required: "Please enter email.",
                    minlength: "Please enter at least 6 digit.",
                    maxlength: "Please enter at least 10 digit."
                },
                email: {
                    required: "Please enter email.",
                },
                reason: {
                    required: "Please enter reason for visit.",
                },
                feedback: {
////                    required: "Please enter reason for visit.",
                },
                hiddenRecaptcha: {
                    required: "Please select I'm not a robot."
                },
            },
            errorPlacement: function (error, element) {
                if (element.attr("name") == "chrSatisfied") {
                    error.insertAfter('.feedback_list_error');
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function (event, validator) { //display error alert on form submit   
                $('.alert-danger', $('.feedbacks_form')).show();
            },
            highlight: function (element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function (element) {
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function (form) {
                $("#loader").css("display", "block");
                var formSerialize = $("#feedbacks_form").serialize();
                jQuery.ajax({
                    type: "POST",
                    url: site_url + '/feedbacks',
                    data: formSerialize,
                    dataType: 'json',
                    async: false,
                    success: function (response) {
                        $("#loader").css("display", "none");
                        if (response.validatorErrors != null) {
                            $.each(response.validatorErrors, function (key, value) {
                                var errorInput = key;
                                var error = '<span id=' + errorInput + '-error" class="error" style="">' + value + '</span>';
                                $('#' + errorInput + '-error').remove();
                                if (key == "chrSatisfied") {
                                    $(error).insertAfter($('#Satisfied'));
                                } else if (key == "chrCategory") {
                                    $(error).insertAfter($('.feedback_cat'));
                                } else {
                                    $(error).insertAfter($('#' + key + ''));
                                }
                            });
                        } else {
                            alert(response.success);
                            location.reload();
                        }
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                    },
                });
                return false;
            }
        });
        $('.feedbacks_form input').keypress(function (e) {
            if (e.which == 13) {
                if ($('.feedbacks_form').validate().form()) {
                    $('.feedbacks_form').submit(); //form validation success, call ajax form submit
                }
                return false;
            }
        });

    });
    $(document).ready(function () {
        var blacklist = /\b(nude|naked|sex|porn|porno|sperm|fuck|penis|pussy|vagina|boobs|asshole|shit|bitch|motherfucker|dick|orgasm|fucker)\b/;  /* many more banned words... */
        $.validator.addMethod("badwordcheck", function (value) {
            return !blacklist.test(value.toLowerCase());
        }, "Please remove bad word/inappropriate language.");
        jQuery.validator.addMethod("emailFormat", function (value, element) {
            // allow any non-whitespace characters as the host part
            return this.optional(element) || /^[_A-Za-z0-9-]+(\.[_A-Za-z0-9-]+)*@[A-Za-z0-9-]+(\.[A-Za-z0-9-]+)*(\.[A-Za-z]{2,4})$/.test(value);
        }, 'Enter valid email format.');

        //called when key is pressed in textbox
        $(document).ready(function () {
            $('#phone').keypress(function (e) {
                var verified = (e.which == 8 || e.which == undefined || e.which == 0) ? null : String.fromCharCode(e.which).match(/[^0-9]/);
                if (verified) {
                    e.preventDefault();
                }
            });
        });
        $.validator.addMethod("phonenumber", function (value, element) {
            var numberPattern = /\d+/g;
            var newVal = value.replace(/\D/g);
            if (parseInt(newVal) <= 0) {
                return false;
            } else {
                return true;
            }
        }, 'Please enter a valid phone number.');
    });
});
</script>
<script>
    var recaptcha1;
    var recaptcha2;
    var recaptcha3;
    var myCallBack = function () {

        recaptcha1 = grecaptcha.render('recaptcha1', {
            'sitekey': '{{ Config::get('Constant.GOOGLE_CAPCHA_KEY') }}',
            'theme': 'light'
        });
        recaptcha3 = grecaptcha.render('recaptcha3', {
            'sitekey': '{{ Config::get('Constant.GOOGLE_CAPCHA_KEY') }}',
            'theme': 'light'
        });

        if ($('#html_element_email_to_friend').length > 0) {
            grecaptcha.render('html_element_email_to_friend', {
                'sitekey': '{{Config::get("Constant.GOOGLE_CAPCHA_KEY")}}',
                'theme': 'light'
            });
        }

    };
    function hidd() {
        $("span.error").hide();
    }
</script>
<div class="modal email_modal feedback_model fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="true">
    <div class="cw-center">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">FEEDBACK FORM</h4>
                </div>
                <div class="modal-body">

                    <div class="cms">
                        <div class="feedback-form">
                            {!! Form::open(['method' => 'post','class'=>'ac-form row feedbacks_form','id'=>'feedbacks_form','autocomplete' => "off"]) !!}
                            <div class="col-sm-12 col-12">
                                <div class="head_model text-center">
                                    <p style="text-align:center">We would like your feedback to improve our website.</p>
                                </div>
                                <h4 class="overall_title text-center">Your overall satisfaction</h4>
                                <div class="feedback_list">
                                    <ul id="Satisfied">
                                        <li>
                                            {{ Form::radio('chrSatisfied', 'H' , false) }}
                                            <span class="icon">
                                                <i>
                                                    <svg aria-hidden="true" data-prefix="fal" data-icon="angry" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 496 512" class="svg-inline--fa fa-angry fa-w-16 fa-7x">
                                                    <path fill="currentColor" d="M248 8C111 8 0 119 0 256s111 248 248 248 248-111 248-248S385 8 248 8zm0 464c-119.1 0-216-96.9-216-216S128.9 40 248 40s216 96.9 216 216-96.9 216-216 216zm0-136c-31.2 0-60.6 13.8-80.6 37.8-5.7 6.8-4.8 16.9 2 22.5s16.9 4.8 22.5-2c27.9-33.4 84.2-33.4 112.1 0 5.3 6.4 15.4 8 22.5 2 6.8-5.7 7.7-15.8 2-22.5-19.9-24-49.3-37.8-80.5-37.8zm-48-96c0-2.9-.9-5.6-1.7-8.2.6.1 1.1.2 1.7.2 6.9 0 13.2-4.5 15.3-11.4 2.6-8.5-2.2-17.4-10.7-19.9l-80-24c-8.4-2.5-17.4 2.3-19.9 10.7-2.6 8.5 2.2 17.4 10.7 19.9l31 9.3c-6.3 5.8-10.5 14.1-10.5 23.4 0 17.7 14.3 32 32 32s32.1-14.3 32.1-32zm171.4-63.3l-80 24c-8.5 2.5-13.3 11.5-10.7 19.9 2.1 6.9 8.4 11.4 15.3 11.4.6 0 1.1-.2 1.7-.2-.7 2.7-1.7 5.3-1.7 8.2 0 17.7 14.3 32 32 32s32-14.3 32-32c0-9.3-4.1-17.5-10.5-23.4l31-9.3c8.5-2.5 13.3-11.5 10.7-19.9-2.4-8.5-11.4-13.2-19.8-10.7z" class=""></path>
                                                    </svg>
                                                </i>
                                            </span>
                                            <span class="title_text">Horrible</span>
                                        </li>
                                        <li>
                                            {{ Form::radio('chrSatisfied', 'B' , false) }}
                                            <span class="icon">
                                                <i>
                                                    <svg aria-hidden="true" data-prefix="fal" data-icon="sad-tear" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 496 512" class="svg-inline--fa fa-sad-tear fa-w-16 fa-7x">
                                                    <path fill="currentColor" d="M168 240c17.7 0 32-14.3 32-32s-14.3-32-32-32-32 14.3-32 32 14.3 32 32 32zm160 0c17.7 0 32-14.3 32-32s-14.3-32-32-32-32 14.3-32 32 14.3 32 32 32zM248 8C111 8 0 119 0 256s111 248 248 248 248-111 248-248S385 8 248 8zm0 464c-119.1 0-216-96.9-216-216S128.9 40 248 40s216 96.9 216 216-96.9 216-216 216zm0-152c-11.2 0-22 1.7-32.7 4.1-7.2-12.6-16.1-26.5-28.1-42.4-9-12.1-29.4-12-38.4 0-29.7 39.6-44.8 69-44.8 87.3 0 34.7 28.7 63 64 63s64-28.3 64-63c0-4.4-1-9.5-2.7-15.1 6.1-1.2 12.3-1.9 18.7-1.9 34.9 0 67.8 15.4 90.2 42.2 5.3 6.4 15.4 8 22.5 2 6.8-5.7 7.7-15.8 2-22.5C334.2 339.6 292.4 320 248 320zm-80 80c-17.7 0-32-13.9-32-31 0-7.7 10-28.8 32-59.5 22 30.7 32 51.8 32 59.5 0 17.1-14.3 31-32 31z" class=""></path>
                                                    </svg>
                                                </i>
                                            </span>
                                            <span class="title_text">Bad</span>
                                        </li>
                                        <li>
                                            {{ Form::radio('chrSatisfied', 'J' , false) }}
                                            <span class="icon">
                                                <i>
                                                    <svg aria-hidden="true" data-prefix="fal" data-icon="grin-beam" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 496 512" class="svg-inline--fa fa-grin-beam fa-w-16 fa-7x">
                                                    <path fill="currentColor" d="M117.7 247.7c3.4 1.1 7.4-.5 9.3-3.7l9.5-17c7.7-13.7 19.2-21.6 31.5-21.6s23.8 7.9 31.5 21.6l9.5 17c2.1 3.7 6.2 4.7 9.3 3.7 3.6-1.1 6-4.5 5.7-8.3-3.3-42.1-32.2-71.4-56-71.4s-52.7 29.3-56 71.4c-.3 3.7 2.1 7.2 5.7 8.3zm160 0c3.4 1.1 7.4-.5 9.3-3.7l9.5-17c7.7-13.7 19.2-21.6 31.5-21.6s23.8 7.9 31.5 21.6l9.5 17c2.1 3.7 6.2 4.7 9.3 3.7 3.6-1.1 6-4.5 5.7-8.3-3.3-42.1-32.2-71.4-56-71.4s-52.7 29.3-56 71.4c-.3 3.7 2.1 7.2 5.7 8.3zm93.4 73.1C340.9 330.5 296 336 248 336s-92.9-5.5-123.1-15.2c-5.3-1.7-11.1-.5-15.3 3.1-4.2 3.7-6.2 9.2-5.3 14.8 9.2 55 83.2 93.3 143.8 93.3s134.5-38.3 143.8-93.3c.9-5.5-1.1-11.1-5.3-14.8-4.3-3.7-10.2-4.9-15.5-3.1zM248 400c-35 0-77-16.3-98.5-40.3 57.5 10.8 139.6 10.8 197.1 0C325 383.7 283 400 248 400zm0-392C111 8 0 119 0 256s111 248 248 248 248-111 248-248S385 8 248 8zm0 464c-119.1 0-216-96.9-216-216S128.9 40 248 40s216 96.9 216 216-96.9 216-216 216z" class=""></path>
                                                    </svg>
                                                </i>
                                            </span>
                                            <span class="title_text">Just Ok</span>
                                        </li>
                                        <li>
                                            {{ Form::radio('chrSatisfied', 'G' , true) }}
                                            <span class="icon">
                                                <i>
                                                    <svg aria-hidden="true" data-prefix="fal" data-icon="laugh-beam" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 496 512" class="svg-inline--fa fa-laugh-beam fa-w-16 fa-7x">
                                                    <path fill="currentColor" d="M248 8C111 8 0 119 0 256s111 248 248 248 248-111 248-248S385 8 248 8zm152.7 400.7c-19.8 19.8-43 35.4-68.7 46.3-26.6 11.3-54.9 17-84.1 17s-57.5-5.7-84.1-17c-25.7-10.9-48.8-26.5-68.7-46.3-19.8-19.8-35.4-43-46.3-68.7-11.3-26.6-17-54.9-17-84.1s5.7-57.5 17-84.1c10.9-25.7 26.5-48.8 46.3-68.7 19.8-19.8 43-35.4 68.7-46.3 26.6-11.3 54.9-17 84.1-17s57.5 5.7 84.1 17c25.7 10.9 48.8 26.5 68.7 46.3 19.8 19.8 35.4 43 46.3 68.7 11.3 26.6 17 54.9 17 84.1s-5.7 57.5-17 84.1c-10.8 25.8-26.4 48.9-46.3 68.7zM287 227.9l9.5-17c7.7-13.7 19.2-21.6 31.5-21.6s23.8 7.9 31.5 21.6l9.5 17c4.1 7.4 15.6 4 14.9-4.5-3.3-42.1-32.2-71.4-56-71.4s-52.7 29.3-56 71.4c-.6 8.6 11 11.9 15.1 4.5zm-160 0l9.5-17c7.7-13.7 19.2-21.6 31.5-21.6s23.8 7.9 31.5 21.6l9.5 17c4.1 7.4 15.6 4 14.9-4.5-3.3-42.1-32.2-71.4-56-71.4s-52.7 29.3-56 71.4c-.6 8.5 10.9 11.9 15.1 4.5zM383 288H113c-9.6 0-17.1 8.4-15.9 18 8.8 71 69.4 126 142.9 126h16c73.4 0 134-55 142.9-126 1.2-9.6-6.3-18-15.9-18zM256 400h-16c-50.2 0-93.5-33.3-107.4-80h230.8c-13.9 46.7-57.2 80-107.4 80z" class=""></path>
                                                    </svg>
                                                </i>
                                            </span>
                                            <span class="title_text">Good</span>
                                        </li>
                                        <li>
                                            {{ Form::radio('chrSatisfied', 'SP' , false) }}
                                            <span class="icon">
                                                <i>
                                                    <svg aria-hidden="true" data-prefix="fal" data-icon="smile" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 496 512" class="svg-inline--fa fa-smile fa-w-16 fa-7x">
                                                    <path fill="currentColor" d="M248 8C111 8 0 119 0 256s111 248 248 248 248-111 248-248S385 8 248 8zm0 464c-119.1 0-216-96.9-216-216S128.9 40 248 40s216 96.9 216 216-96.9 216-216 216zm90.2-146.2C315.8 352.6 282.9 368 248 368s-67.8-15.4-90.2-42.2c-5.7-6.8-15.8-7.7-22.5-2-6.8 5.7-7.7 15.7-2 22.5C161.7 380.4 203.6 400 248 400s86.3-19.6 114.8-53.8c5.7-6.8 4.8-16.9-2-22.5-6.8-5.6-16.9-4.7-22.6 2.1zM168 240c17.7 0 32-14.3 32-32s-14.3-32-32-32-32 14.3-32 32 14.3 32 32 32zm160 0c17.7 0 32-14.3 32-32s-14.3-32-32-32-32 14.3-32 32 14.3 32 32 32z" class=""></path>
                                                    </svg>
                                                </i>
                                            </span>
                                            <span class="title_text">Super!</span>
                                        </li>
                                        <div class="feedback_list_error"></div>
                                    </ul>
                                </div> 
                            </div>
                            <div class="col-sm-12 col-12">
                                <div class="satisfied clearfix">
                                    <span class="left pull-left">Not satisfied</span>
                                    <span class="right pull-right">Really satisfied</span>
                                </div>
                            </div>
                            <div class="col-sm-6 col-12">
                                <div class="form-group ac-form-group">
                                    <label class="ac-label">Name*</label>
                                    {!! Form::text('name',  old('name') , array('id' => 'name', 'class' => 'form-control ac-input','onpaste'=>"return false;",'onCopy'=>"return false;",'onCut'=>"return false;",'onDrag'=>"return false;",'onDrop'=>"return false;", 'placeholder'=>'Name*')) !!}
                                    @if (isset($errors) && $errors->has('name'))
                                    <span class="error">
                                        {{ $errors->first('name') }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-6 col-12">
                                <div class="form-group ac-form-group">
                                    <label class="ac-label">Phone</label>
                                    {!! Form::tel('phone',  old('phone') , array('id' => 'phone', 'class' => 'form-control ac-input','onpaste'=>"return false;",'onCopy'=>"return false;",'onCut'=>"return false;",'onDrag'=>"return false;",'onDrop'=>"return false;", 'placeholder'=>'Phone','maxlength'=>"12")) !!}
                                    @if (isset($errors) && $errors->has('phone'))
                                    <span class="error">
                                        {{ $errors->first('phone') }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-sm-6 col-12">
                                <div class="form-group ac-form-group">
                                    <label class="ac-label">Email*</label>
                                    {!! Form::email('email',  old('email') , array('id' => 'email', 'class' => 'form-control ac-input','maxlength'=>"50", 'onpaste'=>"return false;",'onCopy'=>"return false;",'onCut'=>"return false;",'onDrag'=>"return false;",'onDrop'=>"return false;",'placeholder'=>'Email*')) !!}
                                    @if (isset($errors) && $errors->has('email'))
                                    <span class="error">
                                        {{ $errors->first('email') }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-6 col-12">
                                <div class="form-group ac-form-group">
                                    <label class="ac-label">Reason for visit?*</label>
                                    {!! Form::text('reason',  old('reason') , array('id' => 'reason', 'class' => 'form-control ac-input','maxlength'=>"100",'onpaste'=>"return false;",'onCopy'=>"return false;",'onCut'=>"return false;",'onDrag'=>"return false;",'onDrop'=>"return false;", 'placeholder'=>'Reason for visit?*')) !!}
                                    @if (isset($errors) && $errors->has('reason'))
                                    <span class="error">
                                        {{ $errors->first('reason') }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-sm-12 col-12">
                                <div class="form-radio ac-mb-xs-15">
                                    <label class="ac-label">Please select your feedback category below.</label>
                                    <ul class="feedback_cat">
                                        <li>
                                            {{ Form::radio('Category', 'SGG' , false) }}
                                            <div class="title_text"><span>Suggestions</span></div>
                                        </li>
                                        <li>
                                            {{ Form::radio('Category', 'ISB' , false) }}
                                            <div class="title_text"><span>Issues/Bugs</span></div>
                                        </li>
                                        <li>
                                            {{ Form::radio('Category', 'OTH' , false) }}
                                            <div class="title_text"><span>Others</span></div>
                                        </li>
                                    </ul>
                                </div>
                                <div class="feedback_category"></div>
                            </div>
                            <div class="col-sm-12 col-12">
                                <div class="form-group ac-form-group">
                                    <label class="ac-label">Please leave your feedback below:</label>
                                    {!! Form::text('feedback', old('feedback') , array( 'class' => 'form-control ac-input', 'id' => 'feedback','maxlength'=>"250", 'spellcheck' => 'true','onpaste'=>"return false;",'onCopy'=>"return false;",'onCut'=>"return false;",'onDrag'=>"return false;",'onDrop'=>"return false;" )) !!}
                                    @if (isset($errors) && $errors->has('feedback'))
                                    <span class="error">
                                        {{ $errors->first('feedback') }}
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="captcha_contact">
                                    <div class="form-group captcha_div">
                                        <div id="recaptcha1"></div>
                                        <input type="hidden" class="hiddenRecaptcha" name="hiddenRecaptcha" id="hiddenRecaptcha">
                                        <!--<div class="g-recaptcha" data-sitekey="{{Config::get('Constant.GOOGLE_CAPCHA_KEY')}}">-->
                                        @if (isset($errors) && $errors->has('g-recaptcha'))
                                        <span class="error">
                                            {{ $errors->first('g-recaptcha') }}
                                        </span>
                                        @endif
                                    </div>
                                    <button class="ac-btn-primary btn btn-more">Submit</button>
                                </div>    


                                                                                                                                <!--<img src="assets/images/google-captcha.gif" alt="Captcha">-->
                            </div>                            
                        </div>

                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
