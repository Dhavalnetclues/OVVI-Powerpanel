@if(!Request::ajax())
@extends('layouts.app')
@section('content')
@include('layouts.inner_banner')
@endif
<!-- contact_01 S -->
<section>
    <div class="inner-page-container cms">
        <div class="container">
            @if(isset($data['PassPropage']) && $data['PassPropage'] == 'PP')
            <div class="contact_form password_form" id='passpopup'>
                <!-- PassWord Start -->
                <p class="statusMsg"></p>
                {!! Form::open(['method' => 'post','url' => url('PagePass_URL_Listing'), 'id'=>'passwordprotect_form']) !!}
                <input type='hidden' name='id' id='id' value="{{ $data['Pageid'] }}">
                <input type='hidden' name='tablename' id='tablename' value='cms_page'>
                <div class="form-group">
                    <label class="label-title" for="name">Password</label>
                    <input type="password" class="form-control ac-input" maxlength="20" id="passwordprotect" name='passwordprotect' value='' placeholder="Enter your password"/>
                </div>                      
                <div class="text-center"><button class="btn ac-border" title="Submit">Submit</button></div>
                {!! Form::close() !!}
                <!-- PassWord End  -->                        
            </div>
            <div id='passwordcontent'></div>
            @else
            <section class="page_section contact_01">
                <div class="container">
                    <div class="row">
                        <div class="col-md-8">
                            <br><h2 class="nqtitle">Get in Touch</h2>
                            <div class="cms">
                                <p>We are available by e-mail or by phone. You can also use the quick contact form to ask a question about our services and projects we are working on. We would be pleased to answer your questions.</p>
                            </div>
                            {!! Form::open(['method' => 'post','class'=>'nqform mt-xs-30', 'id'=>'contact_page_form']) !!}
                            <div class="row align-items-start">
                                <div class="col-md-12 text-right">
                                    <div class="required">* Denotes Required Inputs</div><br>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="nq-label" for="first_name">First Name<span class="star">*</span></label>
                                        {!! Form::text('first_name', old('first_name'), array('id'=>'first_name', 'class'=>'form-control nq-input', 'id'=>'first_name', 'maxlength'=>'60', 'onpaste'=>'return false;', 'ondrop'=>'return false;')) !!}
                                        @if ($errors->has('first_name'))
                                        <span class="error">{{ $errors->first('first_name') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="nq-label" for="phone_number">Phone<span class="star">*</span></label>
                                        {!! Form::text('phone_number', old('phone_number'), array('id'=>'phone_number', 'class'=>'form-control nq-input', 'id'=>'phone_number', 'maxlength'=>"20", 'onpaste'=>'return false;', 'ondrop'=>'return false;', 'onkeypress'=>'javascript: return KeycheckOnlyPhonenumber(event);')) !!}
                                        @if ($errors->has('phone_number'))
                                        <span class="error">{{ $errors->first('phone_number') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="nq-label" for="contact_email">Email<span class="star">*</span></label>
                                        {!! Form::email('contact_email', old('contact_email'), array('id'=>'contact_email', 'class'=>'form-control nq-input', 'id'=>'contact_email', 'maxlength'=>'60', 'onpaste'=>'return false;', 'ondrop'=>'return false;')) !!}
                                        @if ($errors->has('contact_email'))
                                        <span class="error">{{ $errors->first('contact_email') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="nq-label" for="user_message">Comments</label>
                                        {!! Form::textarea('user_message', old('user_message'), array('class'=>'form-control nq-textarea', 'name'=>'user_message', 'rows'=>'6', 'id'=>'user_message', 'spellcheck'=>'true', 'onpaste'=>'return false;', 'ondrop'=>'return false;' )) !!}
                                        @if ($errors->has('user_message'))
                                        <span class="error">{{ $errors->first('user_message') }}</span>
                                        @endif
                                    </div>
                                </div>
                             
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div id="contact_html_element" class="g-recaptcha"></div>
                                        <div class="capphitcha" data-sitekey="{{Config::get('Constant.GOOGLE_CAPCHA_KEY')}}">
                                            @if ($errors->has('g-recaptcha-response'))
                                            <span class="error">{{ $errors->first('g-recaptcha-response') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8 text-right">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary" title="Submit">Submit</button>
                                    </div>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                        <div class="col-md-4">
                            @if(!empty($contact_info))
                            @foreach($contact_info as $key => $value) 
                            <br><h2 class="nqtitle">{{ $value->varTitle }}</h2><br>
                            @if(!empty($value->txtAddress))
                            <div class="info">
                                <b><div class="title" style="padding-bottom: 5px;">Address :</div></b>
                                <div class="des">{!! nl2br($value->txtAddress) !!}</div>
                            </div><br>
                            @endif
                            @if(!empty($value->varPhoneNo))
                            <div class="info">
                                <b><div class="title" style="padding-bottom: 5px;">Phone :</div></b>
                                <div class="des">
                                    @php $phone = unserialize($value->varPhoneNo); @endphp
                                    @foreach($phone as $p)
                                    <a href="tel:{{ $p }}" target="_blank" title="{{ $p }}">{{ $p }}</a>
                                    @endforeach
                                </div><br>
                            </div>
                            @endif
                            @if(!empty($value->varEmail))
                            <div class="info">
                                <b><div class="title" style="padding-bottom: 5px;">Email :</div></b>
                                <div class="des">
                                    @php  $email = unserialize($value->varEmail); @endphp
                                    @foreach($email as $e)
                                    <a href="mailto:{{ $e }}" target="_blank" title="{{ $e }}">{{ $e }}</a>
                                    @endforeach
                                </div><br>
                            </div>
                            @endif
                            <div class="info">
                                <b><div class="title" style="padding-bottom: 5px;">Opening Hours : </div></b>
                                <div class="des">
                                    <div style="padding-bottom: 2px;">Monday to Friday : 8:30am-5:30pm<br></div>
                                    <div style="padding-bottom: 2px;">Saturday : 10am-2pm<br></div>
                                    <div style="padding-bottom: 2px;">Sunday : Closed</div>
                                </div>
                            </div><br><br>
                            @endforeach
                            @endif
                            @if(
                            !empty(Config::get('Constant.SOCIAL_FB_LINK')) || 
                            !empty(Config::get('Constant.SOCIAL_TWITTER_LINK')) || 
                            !empty(Config::get('Constant.SOCIAL_YOUTUBE_LINK')) || 
                            !empty(Config::get('Constant.SOCIAL_TRIPADVISOR_LINK')) || 
                            !empty(Config::get('Constant.SOCIAL_LINKEDIN_LINK')) || 
                            !empty(Config::get('Constant.SOCIAL_INSTAGRAM_LINK')) || 
                            !empty(Config::get('Constant.SOCIAL_TUMBLR_LINK')) || 
                            !empty(Config::get('Constant.SOCIAL_PINTEREST_LINK')) || 
                            !empty(Config::get('Constant.SOCIAL_FLICKR_LINK')) || 
                            !empty(Config::get('Constant.SOCIAL_DRIBBBLE_LINK')) || 
                            !empty(Config::get('Constant.SOCIAL_RSS_FEED_LINK'))
                            )
                            <div class="info">
                                <b><div class="title">Follow Us</div></b><br>
                                <div class="des">
                                    @if(!empty(Config::get('Constant.SOCIAL_FB_LINK')))
                                    <a style="padding-right: 8px;" href="{{ Config::get('Constant.SOCIAL_FB_LINK') }}" title="Facebook" target="_blank"><i class="fa fa-facebook"></i></a>
                                    @endif
                                    @if(!empty(Config::get('Constant.SOCIAL_TWITTER_LINK')))
                                    <a style="padding: 8px;" href="{{ Config::get('Constant.SOCIAL_TWITTER_LINK') }}" title="Twitter" target="_blank"><i class="fa fa-twitter"></i></a>
                                    @endif
                                    @if(!empty(Config::get('Constant.SOCIAL_YOUTUBE_LINK')))
                                    <a style="padding: 8px;" href="{{ Config::get('Constant.SOCIAL_YOUTUBE_LINK') }}" title="YouTube" target="_blank"><i class="fa fa-youtube"></i></a>
                                    @endif
                                    @if(!empty(Config::get('Constant.SOCIAL_TRIPADVISOR_LINK')))
                                    <a style="padding: 8px;" href="{{ Config::get('Constant.SOCIAL_TRIPADVISOR_LINK') }}" title="Tripadvisor" target="_blank"><i class="fa fa-tripadvisor"></i></a>
                                    @endif
                                    @if(!empty(Config::get('Constant.SOCIAL_LINKEDIN_LINK')))
                                    <a style="padding: 8px;" href="{{ Config::get('Constant.SOCIAL_LINKEDIN_LINK') }}" title="Linkedin" target="_blank"><i class="fa fa-linkedin"></i></a>
                                    @endif
                                    @if(!empty(Config::get('Constant.SOCIAL_INSTAGRAM_LINK')))
                                    <a style="padding: 8px;" href="{{ Config::get('Constant.SOCIAL_INSTAGRAM_LINK') }}" title="Instagram" target="_blank"><i class="fa fa-instagram"></i></a>
                                    @endif
                                    @if(!empty(Config::get('Constant.SOCIAL_TUMBLR_LINK')))
                                    <a style="padding: 8px;" href="{{ Config::get('Constant.SOCIAL_TUMBLR_LINK') }}" title="Tumblr" target="_blank"><i class="fa fa-tumblr"></i></a>
                                    @endif
                                    @if(!empty(Config::get('Constant.SOCIAL_PINTEREST_LINK')))
                                    <a style="padding: 8px;" href="{{ Config::get('Constant.SOCIAL_PINTEREST_LINK') }}" title="Pinterest" target="_blank"><i class="fa fa-pinterest"></i></a>
                                    @endif
                                    @if(!empty(Config::get('Constant.SOCIAL_FLICKR_LINK')))
                                    <a style="padding: 8px;" href="{{ Config::get('Constant.SOCIAL_FLICKR_LINK') }}" title="Flickr" target="_blank"><i class="fa fa-flickr"></i></a>
                                    @endif
                                    @if(!empty(Config::get('Constant.SOCIAL_DRIBBBLE_LINK')))
                                    <a style="padding: 8px;" href="{{ Config::get('Constant.SOCIAL_DRIBBBLE_LINK') }}" title="Dribbble" target="_blank"><i class="fa fa-dribbble"></i></a>
                                    @endif
                                    @if(!empty(Config::get('Constant.SOCIAL_RSS_FEED_LINK')))
                                    <a style="padding: 8px;" href="{{ Config::get('Constant.SOCIAL_RSS_FEED_LINK') }}" title="RSS Feed" target="_blank"><i class="fa fa-rss"></i></a>
                                    @endif
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </section>
            </section>
            @endif
            <!-- @php
                
                        $current_adress = !empty($contact_info->txtAddress)?$contact_info->txtAddress:'';
        
                        $pinaddress = explode("*", trim(preg_replace('/\s\s+/', '*', $current_adress)));
        
                        $pinaddress = implode('<br/>', $pinaddress);
        
                @endphp
            
                var address = "{{ trim(preg_replace('/\s\s+/', ' ',  $current_adress)) }}";
        
                var pinaddress = "{!! $pinaddress !!}";  -->
            <script type="text/javascript">
                var sitekey = '{{Config::get("Constant.GOOGLE_CAPCHA_KEY")}}';
                var onContactloadCallback = function () {
                    grecaptcha.render('contact_html_element', {
                        'sitekey': sitekey
                    });
                };
            </script>
            <script src="https://maps.googleapis.com/maps/api/js?key={{Config::get('Constant.GOOGLE_MAP_KEY')}}&callback=initMap" async defer></script>
            <script src="https://www.google.com/recaptcha/api.js?onload=onContactloadCallback&render=explicit" async defer></script>
            
            <!-- contact_01 E -->
            @if(!Request::ajax())
            @section('footer_scripts')
          
            <script src="https://maps.googleapis.com/maps/api/js?key={{Config::get('Constant.GOOGLE_MAP_KEY')}}&callback=initMap" async defer></script>
            @endsection
            <style>
                .password_form {
                    padding: 40px;
                    background: #fff;
                    box-shadow: 0 0 25px rgba(0,0,0,.5);
                    max-width: 600px;
                    margin: auto;
                }
                .password_form .label-title {    
                    font-weight: 400;
                    margin-bottom: 5px;
                    font-size: 14px;
                    color: gray;
                }
                .ac-border {   
                    max-width: 200px;
                    width: 100%;
                    margin-top:10px;
                }
            </style>
            @endsection
            @endif