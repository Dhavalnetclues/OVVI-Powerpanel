<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=Edge" />
				<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
				<meta name="csrf-token" content="{{ csrf_token() }}">
				<title>DPL Privacy Removal</title>
				<link rel="canonical" href="{{ url()->current() }}" />
				<link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">
				<link rel="shortcut icon" href="{!! $CDN_PATH.'assets/images/favicon.ico' !!}" type="image/x-icon"/>
				<link rel="apple-touch-icon" sizes="144x144" href="{!! $CDN_PATH.'assets/images/apple-touch-icon-144.png' !!}"/>
				<link rel="apple-touch-icon" sizes="114x114" href="{!! $CDN_PATH.'assets/images/apple-touch-icon-114.png' !!}"/>
				<link rel="apple-touch-icon" sizes="72x72" href="{!! $CDN_PATH.'assets/images/apple-touch-icon-72.png' !!}"/>
				<link rel="apple-touch-icon" sizes="57x57" href="{!! $CDN_PATH.'assets/images/apple-touch-icon-57.png' !!}"/>
        <style>
            *{	-webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;}
            html,
            body { margin: 0; padding: 0; width: 100%; height: 100%; }
            body { font-family: 'Open Sans', sans-serif; font-size: 16px; vertical-align: baseline; color: #181818; }
            img { vertical-align: middle; }
            p { line-height: 22px; margin: 0 0 10px; }
            .dpl_contianer { width: 1400px; padding-right: 15px; padding-left: 15px; margin-right: auto; margin-left: auto; }
            @media (max-width: 767px) {
                .dpl_contianer { width: 100%; }
            }
            @media (min-width: 768px) {
                .dpl_contianer { width: 750px; }
            }
            @media (min-width: 992px) {
                .dpl_contianer { width: 970px; }
            }
            @media (min-width: 1200px) {
                .dpl_contianer { width: 1170px; }
            }
            @media (min-width: 1367px) {
                .dpl_contianer { width: 1400px; }
            }
            @media (min-width: 1601px) {
                .dpl_contianer { width: 1700px; }
            }
            .dpl-header { display: block;padding: 15px 0; border-bottom: 0.5px solid #e0e0e0;margin-bottom: 50px;}
            .dpl_logo img{ max-width: 200px; }
            .dpl_title { font-size: 28px; color: #21488f; letter-spacing: 0.5px; }
            .dpl_subtitle { font-size: 20px; color: #000; letter-spacing: 0.5px; margin-bottom: 5px; }
            .dpl_form_group{margin-bottom: 20px;}
            .dpl_form_group .dpl_select { padding: 5px; max-width: 600px; width: 100%; font-size: 15px;height: 40px; border-color: #dadada;}
            .dpl_form_group select.dpl_select:required:invalid { color: gray;}
            .dpl_form_group .dpl_label { font-size: 15px; display: block; margin: 0 0 5px; color: #2f2f2f; line-height: 100%;}
            .dpl_form_group .dpl_input { max-width: 600px; width: 100%; padding: 5px 10px;height: 40px;line-height: 40px; font-size: 15px;border:1px solid #dadada;}
            .dpl_form_group .dpl_textarea { max-width: 600px; width: 100%; padding: 10px; border:1px solid #dadada;vertical-align: middle;}
            .dpl_form_group .dpl_required{color: red;display: block; font-size: 14px;margin-top: 5px;}
            .dpl_required_note { color: red; font-size: 13px; line-height: 100%; margin-top: 15px;}
            .google-captcha { margin: 20px 0; }
            .dpl_button { background-color: #000000; color: #ffffff; font-size: 22px; padding: 8px 15px; border: 1px solid #000; border-radius: 0; transition: all 0.5s ease-in-out; margin-bottom: 30px; height: 50px; cursor: pointer; letter-spacing: 0.5px; padding: 10px 60px; }
            .dpl_button:hover { background-color: #7d7d7d; border-color: #7d7d7d}
            .dpl_checkbox_note { padding: 15px; border: 1px solid #ccc; max-width: 600px; width: 100%; margin: 20px 0; }
            .dpl_checkbox{padding-left: 30px; display: inline-block;position: relative;}
            .dpl_checkbox input[type=checkbox]{position: absolute; left: 0;width: 15px;height: 15px;vertical-align: -2px;}
            .dpl_note{}
            @media(max-width:1024px) {
                .dpl-header{margin-bottom: 0;}
            }
            @media(max-width:992px) {
                .dpl_form_group .dpl_select,.dpl_form_group .dpl_input,.dpl_form_group .dpl_textarea,.dpl_checkbox_note {max-width: 100%;}
            }
            @media(max-width:767px) {
                body {font-size: 14px;}
                .dpl_form_group .dpl_input,.dpl_form_group .dpl_textarea{width: 100%;}
                .dpl_checkbox_note {width: 100%;}
                .dpl_form_group .dpl_label{font-size: 14px;}
                .dpl_title{font-size: 22px;}
                .dpl_subtitle{font-size: 18px;}
            }
        </style>
    </head>
    <body>
        <div class="dpl-header">
            <div class="dpl_contianer">
                <a href="{{ url('/') }}" title="{{ Config::get('Constant.SITE_NAME') }}" class="dpl_logo">
                    <img src="{!! App\Helpers\resize_image::resize(Config::get('Constant.FRONT_LOGO_ID')) !!}" alt="{{ Config::get('Constant.SITE_NAME') }}">
                </a>
            </div>
        </div>
        <div class="dpl_contianer">

            <h3 class="dpl_title">Privacy Removal</h3>
            <h4 class="dpl_subtitle">Personal Information Removal Request Form</h4>

            <p>For privacy reasons, you may have the right to ask for certain personal information relating to you to be removed, deleted or erased from our database.</p>

            <h4 class="dpl_subtitle">Your Information</h4>

            {!! Form::open(['method' => 'post','class'=>'privacy-removal-form']) !!}
                <p class="dpl_required_note">*Denotes Required Inputs</p>
                <div class="dpl_form_group">
                    <label class="dpl_label">First Name *</label>
                    {!! Form::text('first_name',  old('first_name') , array('class' => 'form-control dpl_input', 'placeholder'=>'Enter Your First Name')) !!}
                    @if ($errors->has('first_name'))
                        <span class="dpl_required">{{ $errors->first('first_name') }}</span>
                    @endif
                </div>
                <div class="dpl_form_group">
                    <label class="dpl_label">Last Name</label>
                    {!! Form::text('last_name',  old('last_name') , array('class' => 'form-control dpl_input', 'placeholder'=>'Enter Your Last Name')) !!}
                    @if ($errors->has('last_name'))
                        <span class="dpl_required">{{ $errors->first('last_name') }}</span>
                    @endif
                </div>
                <div class="dpl_form_group">
                    <label class="dpl_label">Your Email Address with Us *</label>
                    {!! Form::email('email',  old('email') , array('class' => 'form-control dpl_input', 'placeholder'=>'Enter Your Email Address')) !!}
                    @if ($errors->has('email'))
                        <span class="dpl_required">{{ $errors->first('email') }}</span>
                    @endif
                    <br/>
                    <span class="dpl_note"><b>Note: </b>Please enter the same email address which you have used to communicate with us in past.</span>
                </div>
                <div class="dpl_form_group">
                    <label class="dpl_label">Reason for Removal</label>
                    {!! Form::textarea('reason', old('reason') , array( 'class' => 'form-control dpl_textarea', 'rows' => '5')) !!}
                    @if ($errors->has('reason'))
                        <span class="dpl_required">{{ $errors->first('reason') }}</span>
					@endif
                </div>
                <div class="dpl_form_group">
                    <label class="dpl_checkbox">
                    {!! Form::checkbox('authorized',old('authorized'),null, array('class' => 'form-control')) !!}I represent that the information in this request is accurate and that I am authorized to submit this request. *</label>
                    @if ($errors->has('authorized'))
                        <span class="dpl_required">{{ $errors->first('authorized') }}</span>
					@endif
                </div>
                <div class="dpl_checkbox_note">
                    <p>Your personal information saved (with your consent) will be erased permanently from our database. The process may take at least 2 weeks. This may limit or prevent us from providing the products or services you have asked. It may also make it more difficult for us to advise you or suggest appropriate alternatives.</p>
                    <p>If you need any further assistance, contact us on <a href="mailto:{{ \App\Helpers\MyLibrary::getDecryptedString(Config::get('Constant.DEFAULT_ADMIN_EMAIL')) }}">{{\App\Helpers\MyLibrary::getDecryptedString(Config::get('Constant.DEFAULT_ADMIN_EMAIL'))}}</a>.</p>
                </div>
                <div  class="google-captcha dpl_form_group">
                    <div class="capphitcha">    
                        <div id="html_element" data-sitekey="{{Config::get('Constant.GOOGLE_CAPCHA_KEY')}}"></div>
                    </div>    
                    @if ($errors->has('g-recaptcha-response'))
                    <span class="dpl_required">
                        {{ $errors->first('g-recaptcha-response') }}
                    </span>
                    @endif
                </div>
                <button type="submit" class="dpl_button">Submit</button>
            {!! Form::close() !!}
        </div>

        <script type="text/javascript">
            var sitekey = '{{Config::get("Constant.GOOGLE_CAPCHA_KEY")}}';
            var onloadCallback = function() {
                grecaptcha.render('html_element', {
                    'sitekey' : sitekey
                });
            };
        </script>
        <script type="text/javascript" src="{{ $CDN_PATH.'assets/libraries/jquery/jquery-3.3.1.min.js' }}"></script>
        <script src="{{ $CDN_PATH.'assets/libraries/jquery-validation/js/jquery.validate.min.js' }}"></script>
		<script src="{{ $CDN_PATH.'assets/libraries/jquery-validation/js/additional-methods.min.js' }}"></script>
        <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>
        <script src="{{ $CDN_PATH.'assets/js/dpl-privacy.js' }}"></script>
    </body>
</html>
