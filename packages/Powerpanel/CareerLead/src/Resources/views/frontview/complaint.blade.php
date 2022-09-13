@if(!Request::ajax())
@extends('layouts.app')
@section('content')
@include('layouts.inner_banner')
@endif



<!-- complaint_01 S -->

<?php if(isset($PAGE_CONTENT['response']) && !empty($PAGE_CONTENT['response']) && $PAGE_CONTENT['response'] != '[]'){
echo $PAGE_CONTENT['response'];
}?>

<section class="page_section contact_01">

	<div class="container">

		<div class="row">

			<div class="col-md-8">

				<h2 class="nqtitle">Get in Touch</h2>

				<div class="cms">

                    <p>We are available by e-mail or by phone. You can also use the quick complaint form to ask a question about our services and projects we are working on. We would be pleased to answer your questions.</p>

				</div>

				{!! Form::open(['method' => 'post','class'=>'nqform mt-xs-30', 'id'=>'complaint_page_form']) !!}

					<div class="row align-items-start">

                        <div class="col-md-12 text-right">

                            <div class="required">* Denotes Required Inputs</div>

                        </div>

                        <div class="col-md-6">

                            <div class="form-group">

                            	<label class="nq-label" for="first_name">First Name<span class="star">*</span></label>

                                {!! Form::text('first_name', old('first_name'), array('id'=>'first_name', 'class'=>'form-control nq-input', 'name'=>'first_name', 'maxlength'=>'60', 'onpaste'=>'return false;', 'ondrop'=>'return false;')) !!}

                                @if ($errors->has('first_name'))

                                	<span class="error">{{ $errors->first('first_name') }}</span>

                                @endif

                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="form-group">

                                <label class="nq-label" for="complaint_email">Email<span class="star">*</span></label>

                                {!! Form::email('complaint_email', old('complaint_email'), array('id'=>'complaint_email', 'class'=>'form-control nq-input', 'name'=>'complaint_email', 'maxlength'=>'60', 'onpaste'=>'return false;', 'ondrop'=>'return false;')) !!}

                                @if ($errors->has('complaint_email'))

                                    <span class="error">{{ $errors->first('complaint_email') }}</span>

                                @endif

                            </div>

                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                            	<label class="nq-label" for="service">Service<span class="star">*</span></label>
                                {!! Form::text('service', old('service'), array('id'=>'service', 'class'=>'form-control nq-input', 'name'=>'service', 'maxlength'=>'60', 'onpaste'=>'return false;', 'ondrop'=>'return false;')) !!}
                                @if ($errors->has('service'))
                                	<span class="error">{{ $errors->first('service') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                            	<label class="nq-label" for="Company">Company<span class="star">*</span></label>
                                {!! Form::text('Company', old('Company'), array('id'=>'Company', 'class'=>'form-control nq-input', 'name'=>'Company', 'maxlength'=>'60', 'onpaste'=>'return false;', 'ondrop'=>'return false;')) !!}
                                @if ($errors->has('Company'))
                                	<span class="error">{{ $errors->first('Company') }}</span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="col-md-6">

                            <div class="form-group">

                            	<label class="nq-label" for="phone_number">Contact Details<span class="star">*</span></label>

                            	{!! Form::text('phone_number', old('phone_number'), array('id'=>'phone_number', 'class'=>'form-control nq-input', 'name'=>'phone_number', 'maxlength'=>"20", 'onpaste'=>'return false;', 'ondrop'=>'return false;', 'onkeypress'=>'javascript: return KeycheckOnlyPhonenumber(event);')) !!}

                            	@if ($errors->has('phone_number'))

                            		<span class="error">{{ $errors->first('phone_number') }}</span>

                            	@endif

                            </div>

                        </div>

                        <div class="col-md-12">

                            <div class="form-group">

                            	<label class="nq-label" for="user_message">Full Details of Complaint</label>

                            	{!! Form::textarea('user_message', old('user_message'), array('class'=>'form-control nq-textarea', 'name'=>'user_message', 'rows'=>'6', 'id'=>'user_message', 'spellcheck'=>'true', 'onpaste'=>'return false;', 'ondrop'=>'return false;' )) !!}

                            	@if ($errors->has('user_message'))

                            		<span class="error">{{ $errors->first('user_message') }}</span>

                            	@endif

                            </div>

                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                            	<label class="nq-label" for="company_response">Response by Company</label>
                            	{!! Form::textarea('company_response', old('company_response'), array('class'=>'form-control nq-textarea', 'name'=>'company_response', 'rows'=>'6', 'id'=>'company_response', 'spellcheck'=>'true', 'onpaste'=>'return false;', 'ondrop'=>'return false;' )) !!}
                            	@if ($errors->has('company_response'))
                            		<span class="error">{{ $errors->first('company_response') }}</span>
                            	@endif
                            </div>
                        </div>

                        @if(File::exists(app_path().'/NewsletterLead.php'))

                        <div class="col-md-12">

                            <div class="form-group">

                                <div class="nq-checkbox-list">

                                    <label class="nq-checkbox pt-xs-0">

                                        <input name="subscribe" type="checkbox"> Subscribe me to your newsletter as well<span></span>

                                    </label>

                                </div>

                            </div>

                        </div>

                        @endif

                        <div class="col-md-6">

                            <div class="form-group">

                              	<div id="contact_html_element" class="g-recaptcha"></div>

                              	<div class="capphitcha" data-sitekey="{{Config::get('Constant.GOOGLE_CAPCHA_KEY')}}">

                                @if ($errors->has('g-recaptcha-response'))

                                	<label class="error help-block">{{ $errors->first('g-recaptcha-response') }}</label>

                                @endif

                              	</div>

                            </div>

                        </div>

                        <div class="col-md-6 text-right">

                            <div class="form-group">

                                <button type="submit" class="btn btn-primary" title="Submit">Submit</button>

                            </div>

                        </div>

                    </div>

				{!! Form::close() !!}

			</div>

			<div class="col-md-4">

                @if(!empty($complaint_info))

                    @foreach($complaint_info as $key => $value) 

                        <h2 class="nqtitle">{{ $value->varTitle }}</h2>

                        @if(!empty($value->txtAddress))

                            <div class="info">

                                <div class="title">Address:</div>

                                <div class="des">{!! nl2br($value->txtAddress) !!}</div>

                            </div>

                        @endif

                        @if(!empty($value->varPhoneNo))

                        <div class="info">

                            <div class="title">Phone:</div>

                            <div class="des">

                                @php $phone = unserialize($value->varPhoneNo); @endphp

                                @foreach($phone as $p)

                                <a href="tel:{{ $p }}" target="_blank" title="{{ $p }}">{{ $p }}</a>

                                @endforeach

                            </div>

                        </div>

                        @endif

                        @if(!empty($value->varEmail))

                        <div class="info">

                            <div class="title">Email:</div>

                            <div class="des">

                                @php  $email = unserialize($value->varEmail); @endphp

                                @foreach($email as $e)

                                <a href="mailto:{{ $e }}" target="_blank" title="{{ $e }}">{{ $e }}</a>

                                @endforeach

                            </div>

                        </div>

                        @endif

                        <div class="info">

                            <div class="title">Opening Hours:</div>

                            <div class="des">

                                Monday to Friday: 8:30am-5:30pm<br>

                                Saturday: 10am-2pm<br>

                                Sunday: Closed

                            </div>

                        </div>

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

	                    <div class="title">Follow Us</div>

	                    <div class="des">

	                    	<ul class="nqsocia">

                                @if(!empty(Config::get('Constant.SOCIAL_FB_LINK')))

                                    <li><a href="{{ Config::get('Constant.SOCIAL_FB_LINK') }}" title="Facebook" target="_blank"><i class="fa fa-facebook"></i></a></li>

                                @endif

                                @if(!empty(Config::get('Constant.SOCIAL_TWITTER_LINK')))

                                    <li><a href="{{ Config::get('Constant.SOCIAL_TWITTER_LINK') }}" title="Twitter" target="_blank"><i class="fa fa-twitter"></i></a></li>

                                @endif

                                @if(!empty(Config::get('Constant.SOCIAL_YOUTUBE_LINK')))

                                    <li><a href="{{ Config::get('Constant.SOCIAL_YOUTUBE_LINK') }}" title="YouTube" target="_blank"><i class="fa fa-youtube"></i></a></li>

                                @endif

                                @if(!empty(Config::get('Constant.SOCIAL_TRIPADVISOR_LINK')))

                                    <li><a href="{{ Config::get('Constant.SOCIAL_TRIPADVISOR_LINK') }}" title="Tripadvisor" target="_blank"><i class="fa fa-tripadvisor"></i></a></li>

                                @endif

                                @if(!empty(Config::get('Constant.SOCIAL_LINKEDIN_LINK')))

                                    <li><a href="{{ Config::get('Constant.SOCIAL_LINKEDIN_LINK') }}" title="Linkedin" target="_blank"><i class="fa fa-linkedin"></i></a></li>

                                @endif

                                @if(!empty(Config::get('Constant.SOCIAL_INSTAGRAM_LINK')))

                                    <li><a href="{{ Config::get('Constant.SOCIAL_INSTAGRAM_LINK') }}" title="Instagram" target="_blank"><i class="fa fa-instagram"></i></a></li>

                                @endif

                                @if(!empty(Config::get('Constant.SOCIAL_TUMBLR_LINK')))

                                    <li><a href="{{ Config::get('Constant.SOCIAL_TUMBLR_LINK') }}" title="Tumblr" target="_blank"><i class="fa fa-tumblr"></i></a></li>

                                @endif

                                @if(!empty(Config::get('Constant.SOCIAL_PINTEREST_LINK')))

                                    <li><a href="{{ Config::get('Constant.SOCIAL_PINTEREST_LINK') }}" title="Pinterest" target="_blank"><i class="fa fa-pinterest"></i></a></li>

                                @endif

                                @if(!empty(Config::get('Constant.SOCIAL_FLICKR_LINK')))

                                    <li><a href="{{ Config::get('Constant.SOCIAL_FLICKR_LINK') }}" title="Flickr" target="_blank"><i class="fa fa-flickr"></i></a></li>

                                @endif

                                @if(!empty(Config::get('Constant.SOCIAL_DRIBBBLE_LINK')))

                                    <li><a href="{{ Config::get('Constant.SOCIAL_DRIBBBLE_LINK') }}" title="Dribbble" target="_blank"><i class="fa fa-dribbble"></i></a></li>

                                @endif

                                @if(!empty(Config::get('Constant.SOCIAL_RSS_FEED_LINK')))

                                    <li><a href="{{ Config::get('Constant.SOCIAL_RSS_FEED_LINK') }}" title="RSS Feed" target="_blank"><i class="fa fa-rss"></i></a></li>

                                @endif

                            </ul>

	                    </div>

	                </div>

                @endif

			</div>

		</div>

	</div>

</section>

<script type="text/javascript">

  	var sitekey = '{{Config::get("Constant.GOOGLE_CAPCHA_KEY")}}';

  	var oncomplaintloadCallback = function() {

    	grecaptcha.render('contact_html_element', {

      		'sitekey' : sitekey

    	});

  	};



	/* @php

	  	$current_adress = !empty($contact_info->txtAddress)?$contact_info->txtAddress:'';

	  	$pinaddress = explode("*", trim(preg_replace('/\s\s+/', '*', $current_adress)));

	  	$pinaddress = implode('<br/>', $pinaddress);

	@endphp

	var address = "{{ trim(preg_replace('/\s\s+/', ' ',  $current_adress)) }}";

	var pinaddress = "{!! $pinaddress !!}"; */

</script>

<script src="https://maps.googleapis.com/maps/api/js?key={{Config::get('Constant.GOOGLE_MAP_KEY')}}&callback=initMap" async defer></script>

<script src="https://www.google.com/recaptcha/api.js?onload=onComplaintloadCallback&render=explicit" async defer></script>

<!-- complaint_01 E -->
@if(!Request::ajax())
@section('footer_scripts')
<script src="{{ $CDN_PATH.'assets/js/packages/complaintlead/complaint.js' }}"></script>

<script src="https://maps.googleapis.com/maps/api/js?key={{Config::get("Constant.GOOGLE_MAP_KEY")}}&callback=initMap" async defer></script>

@endsection

@endsection
@endif