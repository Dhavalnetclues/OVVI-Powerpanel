@if(!Request::ajax())
    @extends('layouts.app')
    @section('content')
    @include('layouts.inner_banner')
@endif

@if(!Request::ajax())
    <section class="inner-page-gap contact-us">
        @include('layouts.share-email-print')    

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
                                    {!! Form::text('first_name', old('first_name'), array('id'=>'first_name', 'class'=>'form-control ac-input', 'id'=>'first_name', 'maxlength'=>'60', 'onpaste'=>'return false;', 'ondrop'=>'return false;')) !!}
                                    @if ($errors->has('first_name'))
                                        <span class="error">{{ $errors->first('first_name') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group ac-form-group">
                                    <label class="ac-label" for="last_Name">Last Name <span class="star">*</span></label>
                                    {!! Form::text('last_name', old('last_name'), array('id'=>'last_name', 'class'=>'form-control ac-input', 'id'=>'last_name', 'maxlength'=>'60', 'onpaste'=>'return false;', 'ondrop'=>'return false;')) !!}
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
                                    <button type="submit" title="Submit" class="ac-btn ac-btn-primary">Submit</button>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="-secure n-fs-16 n-fw-400 n-ff-2 n-fc-white-500">100% Secure. Zero Spam.</div>
                            </div>
                        </div>
                    {!! Form::close() !!}
                </div>

                <div class="col-lg-7 n-mt-50 n-mt-lg-0">
                    @if(isset($data['PageData']) && !empty($data['PageData']))
                        <div class="row">
                            <div class="col-sm-12 d-flex n-gapp-3 n-gapm-sm-2">
                                @php echo $data['PageData']; @endphp
                            </div>
                        </div>
                    @endif
                    <div class="row">
                        
                        @foreach($contact_info['primary'] as $info)

                            @if(isset($info->mailingaddress) && !empty($info->mailingaddress))
                                <div class="col-sm-6 d-flex n-gapp-3 n-gapm-sm-2" data-aos="flip-up">
                                    <article class="-items w-100 n-ph-30 n-pv-10">
                                        <div class="nqtitle-small n-fw-600 n-fc-black-500">{{$info->varTitle}} Mailing Address</div>
                                        <div class="n-fs-18 n-lh-130 n-mt-10">{{$info->mailingaddress}}</div>
                                    </article>
                                </div>
                            @endif

                            @if(isset($info->txtAddress) && !empty($info->txtAddress))
                                <div class="col-sm-6 d-flex n-gapp-3 n-gapm-sm-2" data-aos="flip-up">
                                    <article class="-items w-100 n-ph-30 n-pv-10">
                                        <div class="nqtitle-small n-fw-600 n-fc-black-500">{{$info->varTitle}} Physical Address</div>
                                        <div class="n-fs-18 n-lh-130 n-mt-10">{{$info->txtAddress}}</div>
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
                                        <div class="n-fs-18 n-lh-130 n-mt-10">Email: {{$info->varFax}}</div>
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
                        {{--<div class="col-sm-6 d-flex n-gapp-3 n-gapm-sm-2" data-aos="flip-up">
                            <article class="-items w-100 n-ph-30 n-pv-10">
                                <div class="nqtitle-small n-fw-600 n-fc-black-500">Mailing Address</div>
                                <div class="n-fs-18 n-lh-130 n-mt-10">PO Box 10189 Grand Cayman KY1-1002 <br>CAYMAN ISLANDS</div>
                            </article>
                        </div>
                        <div class="col-sm-6 d-flex n-gapp-3 n-gapm-sm-2" data-aos="flip-up">
                            <article class="-items w-100 n-ph-30 n-pv-10">
                                <div class="nqtitle-small n-fw-600 n-fc-black-500">Physical Address</div>
                                <div class="n-fs-18 n-lh-130 n-mt-10">Utility Regulation and Competition Office 3rd Floor, Alissta Towers, 85 North Sound Rd. Grand Cayman, CAYMAN ISLANDS</div>
                            </article>
                        </div>
                        <div class="col-sm-6 d-flex n-gapp-3 n-gapm-sm-2" data-aos="flip-up">
                            <article class="-items w-100 n-ph-30 n-pv-10">
                                <div class="nqtitle-small n-fw-600 n-fc-black-500">Head Office Email</div>
                                <div class="n-fs-18 n-lh-130 n-mt-10"><a class="n-ah-a-500" href="#" title="info@ofreg.ky">info@ofreg.ky</a></div>
                            </article>
                        </div>
                        <div class="col-sm-6 d-flex n-gapp-3 n-gapm-sm-2" data-aos="flip-up">
                            <article class="-items w-100 n-ph-30 n-pv-10">
                                <div class="nqtitle-small n-fw-600 n-fc-black-500">Head Office Phone</div>
                                <div class="n-fs-18 n-lh-130 n-mt-10"><a class="n-ah-a-500" href="#" title="+1 (345) 946 4282">+1 (345) 946 4282</a></div>
                            </article>
                        </div>
                        <div class="col-sm-6 d-flex n-gapp-3 n-gapm-sm-2" data-aos="flip-up">
                            <article class="-items w-100 n-ph-30 n-pv-10">
                                <div class="nqtitle-small n-fw-600 n-fc-black-500">Head Office Fax</div>
                                <div class="n-fs-18 n-lh-130 n-mt-10">+1 (345) 945 8284</div>
                            </article>
                        </div>
                        <div class="col-sm-6 d-flex n-gapp-3 n-gapm-sm-2" data-aos="flip-up">
                            <article class="-items w-100 n-ph-30 n-pv-10">
                                <div class="nqtitle-small n-fw-600 n-fc-black-500">Business Hours</div>
                                <div class="n-fs-18 n-lh-130 n-mt-10">8.30am to 5pm, Monday to Friday</div>
                            </article>
                        </div>

                        <div class="col-sm-6 d-flex n-gapp-3 n-gapm-sm-2" data-aos="flip-up">
                            <article class="-items w-100 n-ph-30 n-pv-10">
                                <div class="nqtitle-small n-fw-600 n-fc-black-500">Consumer Complaints</div>
                                <div class="n-fs-18 n-lh-130 n-mt-10"><a class="n-ah-a-500" href="#" title="complaints@ofreg.ky">complaints@ofreg.ky</a></div>
                            </article>
                        </div>
                        <div class="col-sm-6 d-flex n-gapp-3 n-gapm-sm-2" data-aos="flip-up">
                            <article class="-items w-100 n-ph-30 n-pv-10">
                                <div class="nqtitle-small n-fw-600 n-fc-black-500">.Ky Domain Enquiries</div>
                                <div class="n-fs-18 n-lh-130 n-mt-10"><a class="n-ah-a-500" href="#" title="kyadmin@ofreg.ky">kyadmin@ofreg.ky</a></div>
                            </article>
                        </div>
                        <div class="col-sm-6 d-flex n-gapp-3 n-gapm-sm-2" data-aos="flip-up">
                            <article class="-items w-100 n-ph-30 n-pv-10">
                                <div class="nqtitle-small n-fw-600 n-fc-black-500">Ship, Aircraft & Misc Licensing Matters</div>
                                <div class="n-fs-18 n-lh-130 n-mt-10"><a class="n-ah-a-500" href="#" title="licensing@ofreg.ky">licensing@ofreg.ky</a></div>
                            </article>
                        </div>
                        <div class="col-sm-6 d-flex n-gapp-3 n-gapm-sm-2" data-aos="flip-up">
                            <article class="-items w-100 n-ph-30 n-pv-10">
                                <div class="nqtitle-small n-fw-600 n-fc-black-500">FOI</div>
                                <div class="n-fs-18 n-lh-130 n-mt-10"><a class="n-ah-a-500" href="#" title="foi@ofreg.ky">foi@ofreg.ky</a></div>
                            </article>
                        </div>
                        <div class="col-sm-6 d-flex n-gapp-3 n-gapm-sm-2" data-aos="flip-up">
                            <article class="-items w-100 n-ph-30 n-pv-10">
                                <div class="nqtitle-small n-fw-600 n-fc-black-500">Contributions To Public Consultations</div>
                                <div class="n-fs-18 n-lh-130 n-mt-10"><a class="n-ah-a-500" href="#" title="consultations@ofreg.ky">consultations@ofreg.ky</a></div>
                            </article>
                        </div>--}}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif
<script type="text/javascript">

    // $('select[name=category]').select2({
    //     placeholder: "Select Company",
    //     width: '100%'
    // }).on("change", function (e) {
    //     $('select[name=category]').closest('.has-error').removeClass('has-error');
    //     $("#category-error").remove();
    // });
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
@if(!Request::ajax())
    @section('footer_scripts')
    @endsection
    @endsection
@endif