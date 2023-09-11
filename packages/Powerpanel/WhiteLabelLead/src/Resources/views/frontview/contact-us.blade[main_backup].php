@extends('layouts.app')
@section('content')
@include('layouts.inner_banner')
<section class="inner-page-gap contact-us">

    <div class="container">
        <div class="row">
            <div class="col-lg-5" data-aos="fade-right">
                {!! Form::open(['method' => 'post','class'=>'ac-form-wd n-bs-1 n-pt-40 n-pb-70 n-ph-lg-50 n-ph-25 contactus_form','id'=>'contactus_form']) !!}
                    <div class="row">
                        <div class="col-sm-12 n-pb-20">
                            <div class="form-group ac-form-group">
                                <h2 class="nqtitle-small text-uppercase">Quick Contact</h2>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group ac-form-group">
                                <label class="ac-label" for="first_name">First Name <span class="star">*</span></label>
                                {!! Form::text('first_name', old('first_name'), array('id'=>'first_name', 'class'=>'form-control ac-input', 'autocomplete'=>'off','id'=>'first_name', 'maxlength'=>'60', 'onpaste'=>'return false;', 'ondrop'=>'return false;')) !!}
                                @if ($errors->has('first_name'))
                                    <span class="error">{{ $errors->first('first_name') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group ac-form-group">
                                <label class="ac-label" for="last_Name">Last Name <span class="star">*</span></label>
                                {!! Form::text('last_name', old('last_name'), array('id'=>'last_name', 'class'=>'form-control ac-input', 'autocomplete'=>'off', 'id'=>'last_name', 'maxlength'=>'60', 'onpaste'=>'return false;', 'ondrop'=>'return false;')) !!}
                                @if ($errors->has('last_name'))
                                    <span class="error">{{ $errors->first('last_name') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group ac-form-group">
                                <label class="ac-label" for="email">Email <span class="star">*</span></label>
                                {!! Form::text('email', old('email'), array('id'=>'email', 'class'=>'form-control ac-input',  'spellcheck'=>'true','autocomplete'=>'off','maxlength'=>'255', 'onpaste'=>'return false;', 'ondrop'=>'return false;')) !!}
                                @if ($errors->has('email'))
                                    <span class="error">{{ $errors->first('email') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group ac-form-group">
                                <label class="ac-label" for="email">Phone </label>
                                {!! Form::text('phoneno', old('phoneno'), array('id'=>'phoneno', 'class'=>'form-control ac-input',  'spellcheck'=>'true','autocomplete'=>'off','maxlength'=>'255', 'onpaste'=>'return false;', 'ondrop'=>'return false;','onkeypress'=>'javascript: return KeycheckOnlyPhonenumber(event);')) !!}
                                @if ($errors->has('phoneno'))
                                    <span class="error">{{ $errors->first('phoneno') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group ac-form-group">
                                <label class="ac-label" for="email">Service <span class="star">*</span></label>
                                <select class="selectpicker ac-input" data-width="100%" title="Select Service" name="category" id="category">
                                    <option value="ict">ICT</option>
                                    <option value="energy">Energy</option>
                                    <option value="fuel">Fuel</option>
                                    <option value="water">Water</option>
                                    <option value="other">Others</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group ac-form-group">
                                <label class="ac-label" for="message">Message</label>
                                {!! Form::textarea('message', old('message'), array('id'=>'message', 'class'=>'form-control ac-textarea', 'rows'=>'4', 'spellcheck'=>'true','autocomplete'=>'off','maxlength'=>'600', 'onpaste'=>'return false;', 'ondrop'=>'return false;')) !!}
                                @if ($errors->has('message'))
                                    <span class="error">{{ $errors->first('message') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group ac-form-group">
                                <div id="contactus_html_element" class="g-recaptcha"></div>
                                <div class="capphitcha" data-sitekey="{{Config::get('Constant.GOOGLE_CAPCHA_KEY')}}">
                                    @if ($errors->has('g-recaptcha-response'))
                                        <span class="error">{{ $errors->first('g-recaptcha-response') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group ac-form-group n-tar-sm n-tal">
                                <button id="contact_submit" type="submit" title="Submit" class="ac-btn ac-btn-primary">Submit</button>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="-secure n-fs-16 n-fw-400 n-ff-2 n-fc-white-500">100% Secure. Zero Spam.</div>
                        </div>
                    </div>
                {!! Form::close() !!}
            </div>

            <div class="col-lg-7 n-mt-50 n-mt-lg-0">
                <div class="row">
                    
                    @foreach($contact_info['primary'] as $info)

                        @if(isset($info->mailingaddress) && !empty($info->mailingaddress))
                            <div class="col-sm-6 d-flex n-gapp-3 n-gapm-sm-2" data-aos="flip-up">
                                <article class="-items w-100 n-ph-30 n-pv-10">
                                    <div class="nqtitle-small n-fw-600 n-fc-black-500">{{$info->varTitle}} Mailing Address</div>
                                    <div class="n-fs-18 n-lh-130 n-mt-10">{!! nl2br($info->mailingaddress) !!}</div>
                                </article>
                            </div>
                        @endif

                        @if(isset($info->txtAddress) && !empty($info->txtAddress))
                            <div class="col-sm-6 d-flex n-gapp-3 n-gapm-sm-2" data-aos="flip-up">
                                <article class="-items w-100 n-ph-30 n-pv-10">
                                    <div class="nqtitle-small n-fw-600 n-fc-black-500">{{$info->varTitle}} Physical Address</div>
                                    <div class="n-fs-18 n-lh-130 n-mt-10">{!! nl2br($info->txtAddress) !!}</div>
                                </article>
                            </div>
                        @endif

                        @if(isset($info->varEmail) && !empty($info->varEmail))
                            <div class="col-sm-6 d-flex n-gapp-3 n-gapm-sm-2" data-aos="flip-up">
                                <article class="-items w-100 n-ph-30 n-pv-10">
                                    <div class="nqtitle-small n-fw-600 n-fc-black-500">{{$info->varTitle}} Email</div>
                                    <div class="n-fs-18 n-lh-130 n-mt-10">{{$info->varEmail}}</div>
                                </article>
                            </div>
                        @endif

                        @if(isset($info->varPhoneNo) && !empty($info->varPhoneNo))
                            <div class="col-sm-6 d-flex n-gapp-3 n-gapm-sm-2" data-aos="flip-up">
                                <article class="-items w-100 n-ph-30 n-pv-10">
                                    <div class="nqtitle-small n-fw-600 n-fc-black-500">{{$info->varTitle}} Phone</div>
                                    <div class="n-fs-18 n-lh-130 n-mt-10">{{$info->varPhoneNo}}</div>
                                </article>
                            </div>
                        @endif

                        @if(isset($info->varFax) && !empty($info->varFax))
                            <div class="col-sm-6 d-flex n-gapp-3 n-gapm-sm-2" data-aos="flip-up">
                                <article class="-items w-100 n-ph-30 n-pv-10">
                                    <div class="nqtitle-small n-fw-600 n-fc-black-500">{{$info->varTitle}} Fax</div>
                                    <div class="n-fs-18 n-lh-130 n-mt-10">Fax: {{$info->varFax}}</div>
                                </article>
                            </div>
                        @endif
                    @endforeach
                    @foreach($contact_info['non-primary'] as $info)
                        <div class="col-sm-6 d-flex n-gapp-3 n-gapm-sm-2" data-aos="flip-up">
                            <article class="-items w-100 n-ph-30 n-pv-10">
                                <div class="nqtitle-small n-fw-600 n-fc-black-500">{{$info->varTitle}}</div>
                                @if(isset($info->varEmail) && !empty($info->varEmail))<div class="n-fs-18 n-lh-130 n-mt-10">Email: {{$info->varEmail}}</div>@endif
                                @if(isset($info->varPhoneNo) && !empty($info->varPhoneNo))<div class="n-fs-18 n-lh-130 n-mt-10">Phone: {{$info->varPhoneNo}}</div>@endif
                                @if(isset($info->mailingaddress) && !empty($info->mailingaddress))<div class="n-fs-18 n-lh-130 n-mt-10">Mailing Address: {{$info->mailingaddress}}</div>@endif
                                @if(isset($info->txtAddress) && !empty($info->txtAddress))<div class="n-fs-18 n-lh-130 n-mt-10">Physical Address: {{$info->txtAddress}}</div>@endif
                            </article>
                        </div>
                    @endforeach
                </div>
            </div>

            @if(isset($PassPropage) && $PassPropage == 'PP' && $isContent)
                @include('partial.passwordProtected', ['Pageid' => $Pageid, 'tablename' => $tablename])
                <div class="col-xl-12 n-mt-25" id="pageContent"></div>
            @else
                @if(isset($data['PageData']) && !empty($data['PageData']))
                    <div class="col-xl-12 n-mt-25">
                        @php echo $data['PageData']; @endphp
                    </div>
                @endif
            @endif
        </div>
    </div>
</section>
@endsection


@section('page_scripts')
<script type="text/javascript">
    var sitekey = '{{Config::get("Constant.GOOGLE_CAPCHA_KEY")}}';
    var onContactloadCallback = function () {
        grecaptcha.render('contactus_html_element', {
            'sitekey': sitekey
        });
    };
</script>
<script src="https://maps.googleapis.com/maps/api/js?key={{Config::get('Constant.GOOGLE_MAP_KEY')}}&callback=initMap" async defer></script>
<script src="https://www.google.com/recaptcha/api.js?onload=onContactloadCallback&render=explicit" async defer></script>
<script src="{{ $CDN_PATH.'assets/js/packages/contact-us/contactus-form.js' }}" type="text/javascript"></script>
@endsection
