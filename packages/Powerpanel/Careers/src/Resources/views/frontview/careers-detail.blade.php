@if(!Request::ajax())
    @extends('layouts.app')
    @section('content')
    @include('layouts.inner_banner')
@endif
@if(!Request::ajax())
<section class="inner-page-gap careers-detail">
    @include('layouts.share-email-print')
    <div class="container">
        <div class="row">
            <div class="col-xl-3 col-lg-4" data-aos="fade-down">
                <div class="sticky-top">
                    <div class="-title n-br-5 n-pa-30 n-fc-white-500">
                        <div class="n-mt-60 n-mb-15 -svg"><i class="n-icon" data-icon="s-trophy"></i></div>
                        <h2 class="nqtitle-small n-fc-white-500">{{ $career->varTitle }}</h2>
                        <div class="n-fs-18 n-mt-10">{{ strtoupper($career->varSector) }}</div>
                        @if(!empty($career->dtEndDateTime))    
                            <div class="n-fs-18 n-mt-10">Due date: {{ date('d M, Y',strtotime($career->dtEndDateTime)) }}</div>
                        @endif  
                    </div>
                    <button class="ac-btn-primary n-mt-20 n btn-block" title="Apply Now" data-toggle="modal" data-target="#applyNowModal">Apply Now</button>
                </div>
            </div>
            <div class="col-xl-9 col-lg-8 n-mt-25 n-mt-lg-0" data-aos="fade-down">
                <div class="row">
                    <div class="col-xl-3 col-md-4 col-6 n-gapp-xl-5 n-gapm-xl-4 n-gapm-md-2 n-gapm-sm-2">
                        <div class="n-fs-18 n-fw-600 n-fc-dark-500 n-lh-130 text-uppercase">No of Position</div>
                        <div class="n-fs-18 n-fw-400 n-lh-130">{{ $career->txtPosition }}</div>
                    </div>
                    <div class="col-xl-3 col-md-4 col-6 n-gapp-xl-5 n-gapm-xl-4 n-gapm-md-2 n-gapm-sm-2">
                        <div class="n-fs-18 n-fw-600 n-fc-dark-500 n-lh-130 text-uppercase">Experience</div>
                        <div class="n-fs-18 n-fw-400 n-lh-130">{{ $career->txtExperience }}</div>
                    </div>
                    <div class="col-xl-3 col-md-4 col-6 n-gapp-xl-5 n-gapm-xl-4 n-gapm-md-2 n-gapm-sm-2">
                        <div class="n-fs-18 n-fw-600 n-fc-dark-500 n-lh-130 text-uppercase">Status</div>
                        <div class="n-fs-18 n-fw-400 n-lh-130">{{ ($career->employmentType == "F"?"Full time":'Part time') }}</div>
                    </div>
                    <div class="col-xl-3 col-md-4 col-6 n-gapp-xl-5 n-gapm-xl-4 n-gapm-md-2 n-gapm-sm-2">
                        <div class="n-fs-18 n-fw-600 n-fc-dark-500 n-lh-130 text-uppercase">Salary Range</div>
                        <div class="n-fs-18 n-fw-400 n-lh-130">{{ $career->intSalary }}</div>
                    </div>
                </div>

                <div class="row n-mt-25 n-mt-lg-50">

                    <div class="col-xl-6 n-gapm-xl-2">
                        <h2 class="nqtitle-small">Requirements of Job</h2>
                        <div class="cms">
                            <p>{{ $career->varRequirements }}</p>
                        </div>
                    </div>  

                    @if(!empty($career->varShortDescription))    
                        <div class="col-xl-6 n-gapm-xl-2">
                            <h2 class="nqtitle-small">Description of Job</h2>
                            <div class="cms">
                                <p>{{ $career->varShortDescription }}</p>
                            </div>
                        </div>
                    @endif
                </div>
                @if(isset($txtDescription['response']) && !empty($txtDescription['response']))
                    <div class="cms n-mt-30">
                        {!! $txtDescription['response'] !!}
                    </div>
                @endif
            </div>
        </div>
    </div>  
</section>
<!-- Apply Now S -->
<div class="modal fade ac-modal" id="applyNowModal" tabindex="-1" aria-labelledby="applyNowModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header d-flex flex-column">
                <div class="ac-title n-fs-24 n-fc-white-500">Apply Now</div>
                <div class="n-fs-18 n-fw-400 n-fc-white-500 n-lh-130">{{ $career->varTitle }}</div>
                <a href="javascript:void(0)" data-dismiss="modal" aria-label="Close" class="ac-close">×</a>
            </div>
            <div class="modal-body n-pa-30">

                {!! Form::open(['method' => 'post','url' => url('submit-job-application'),'id'=>'job_application_form','files' => true]) !!}
                <input class="form-control" type="hidden" id="careerId" name="careerId" value="{{$career->id}}" />
                    <div class="row ac-form-wd card-detail">
                        <div class="col-md-6">
                            <div class="form-group ac-form-group">
                                <label class="ac-label" for="firstName">First Name <span class="star">*</span></label>
                                {!! Form::text('fname', old('fname'), array('id' => 'firstName','class'=>'form-control ac-input', 'id'=>'first_name', 'maxlength'=>'60','autocomplete'=>'off', 'onpaste'=>'return false;', 'ondrop'=>'return false;')) !!}
                                @if ($errors->has('fname'))
                                    <span class="error">{{ $errors->first('fname') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group ac-form-group">
                                <label class="ac-label" for="lastName">Last Name <span class="star">*</span></label>
                                {!! Form::text('lname', old('lname'), array('id' => 'lastName','class'=>'form-control ac-input', 'id'=>'last_name', 'maxlength'=>'60','autocomplete'=>'off', 'onpaste'=>'return false;', 'ondrop'=>'return false;')) !!}
                                @if ($errors->has('lname'))
                                    <span class="error">{{ $errors->first('lname') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group ac-form-group">
                                <label class="ac-label" for="email">Email <span class="star">*</span></label>
                                {!! Form::text('email', old('email'), array('id'=>'email', 'class'=>'form-control ac-input',  'spellcheck'=>'true','autocomplete'=>'off','maxlength'=>'255', 'onpaste'=>'return false;', 'ondrop'=>'return false;')) !!}
                                @if ($errors->has('email'))
                                    <span class="error">{{ $errors->first('email') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group ac-form-group">
                                <label class="ac-label" for="phoneNo">Phone No <span class="star">*</span></label>
                                {!! Form::text('phoneNo', old('phoneNo'), array('id'=>'phoneNo', 'class'=>'form-control ac-input',  'spellcheck'=>'true','autocomplete'=>'off', 'placeholder'=>'Enter phone','maxlength'=>'255', 'onpaste'=>'return false;', 'ondrop'=>'return false;','onkeypress'=>'javascript: return KeycheckOnlyPhonenumber(event);')) !!}
                                @if ($errors->has('phoneNo'))
                                    <span class="error">{{ $errors->first('phoneNo') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group ac-form-group">
                                <label class="ac-label" for="Address1">Address 1 <span class="star">*</span></label>
                                {!! Form::textarea('address1', old('address1'), array('id'=>'Address1', 'class'=>'form-control ac-textarea', 'rows'=>'4', 'spellcheck'=>'true','autocomplete'=>'off','maxlength'=>'400',  'ondrop'=>'return false;')) !!}
                                @if ($errors->has('address1'))
                                    <span class="error">{{ $errors->first('address1') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group ac-form-group">
                                <label class="ac-label" for="Address2">Address 2 </label>
                                {!! Form::textarea('address2', old('address2'), array('id'=>'address2', 'class'=>'form-control ac-textarea', 'rows'=>'4','spellcheck'=>'true','autocomplete'=>'off','maxlength'=>'400',  'ondrop'=>'return false;')) !!}
                                @if ($errors->has('address2'))
                                    <span class="error">{{ $errors->first('address2') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group ac-form-group">
                                <label class="ac-label" for="country">Country <span class="star">*</span></label>
                                {!! Form::text('country', old('country'), array('id'=>'country', 'class'=>'form-control ac-input', 'spellcheck'=>'true','autocomplete'=>'off',  'ondrop'=>'return false;')) !!}
                                @if ($errors->has('country'))
                                    <span class="error">{{ $errors->first('country') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group ac-form-group">
                                <label class="ac-label" for="state">State <span class="star">*</span></label>
                                {!! Form::text('state', old('state'), array('id'=>'state', 'class'=>'form-control ac-input', 'spellcheck'=>'true','autocomplete'=>'off',  'ondrop'=>'return false;')) !!}
                                @if ($errors->has('state'))
                                    <span class="error">{{ $errors->first('state') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group ac-form-group">
                                <label class="ac-label" for="city">City <span class="star">*</span></label>
                                {!! Form::text('city', old('city'), array('id'=>'city', 'class'=>'form-control ac-input', 'autocomplete'=>'off', 'ondrop'=>'return false;')) !!}
                                @if ($errors->has('city'))
                                    <span class="error">{{ $errors->first('city') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group ac-form-group">
                                <label class="ac-label" for="postalCode">Postal Code <span class="star">*</span></label>
                                {!! Form::text('postalCode', old('postalCode'), array('id'=>'postalCode', 'class'=>'form-control ac-input', 'autocomplete'=>'off','onpaste'=>'return false;', 'ondrop'=>'return false;')) !!}
                                @if ($errors->has('postalCode'))
                                    <span class="error">{{ $errors->first('postalCode') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group ac-form-group">
                                <label class="ac-label" for="dob">DOB <span class="star">*</span></label>
                                {!! Form::text('dob', old('dob'), array('id'=>'dob', 'class'=>'form-control ac-input dob','autocomplete'=>'off', 'onpaste'=>'return false;', 'ondrop'=>'return false;')) !!}
                                @if ($errors->has('dob'))
                                    <span class="error">{{ $errors->first('dob') }}</span>
                                @endif
                            </div>
                        </div>  
                        <div class="col-md-6">
                            <div class="form-group ac-form-group ac-active-label">
                                <label class="ac-label" for="gender">Gender</label>
                                <div class="ac-radio-inline">
                                    <label class="ac-radio">
                                        <input type="radio" name="gender" value="M" checked> Male<span></span>
                                    </label>
                                    <label class="ac-radio">
                                        <input type="radio" name="gender" value="F"> Female<span></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group ac-form-group">
                                <label class="ac-label" for="resumeUpload">Resume Upload <span class="star">*</span></label>
                                <input type="file" class="form-control ac-input" id="file" name="resume" autocomplete="off" value="" >
                                @if ($errors->has('resume'))
                                    <span class="error">{{ $errors->first('resume') }}</span>
                                @endif
                                <span class="ac-note">You can upload Only 1 document(s) having the extension *.pdf, *.doc,*.docx and all files together must not exceed 5 MB.</span>
                                <div id="selectedFiles" style="margin-left: 20px; margin-top: 10px;">
                                
                            </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group ac-form-group">
                                <label class="ac-label" for="immigrationStatus">What is your current immigration status? </label>
                                {!! Form::textarea('immigrationStatus', old('immigrationStatus'), array('id'=>'immigrationStatus', 'class'=>'form-control ac-textarea','rows'=>'4','maxlength'=>'400', 'autocomplete'=>'off', 'ondrop'=>'return false;')) !!}
                                @if ($errors->has('immigrationStatus'))
                                    <span class="error">{{ $errors->first('immigrationStatus') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group ac-form-group">
                                <label class="ac-label" for="jobOpening">How did you hear about this job opening? </label>
                                {!! Form::textarea('jobOpening', old('jobOpening'), array('id'=>'jobOpening', 'class'=>'form-control ac-textarea','rows'=>'4','maxlength'=>'400','autocomplete'=>'off', 'ondrop'=>'return false;')) !!}
                                @if ($errors->has('jobOpening'))
                                    <span class="error">{{ $errors->first('jobOpening') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group ac-form-group">
                                <label class="ac-label" for="describeExp">Describe the experience you have relevant to this position </label>
                                {!! Form::textarea('describeExp', old('describeExp'), array('id'=>'describeExp', 'class'=>'form-control ac-textarea', 'rows'=>'4','maxlength'=>'400','autocomplete'=>'off', 'ondrop'=>'return false;')) !!}
                                @if ($errors->has('describeExp'))
                                    <span class="error">{{ $errors->first('describeExp') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group ac-form-group">
                                <label class="ac-label" for="reasonForChange">What is the reason for you looking to move from your existing employer? </label>
                                {!! Form::textarea('reasonForChange', old('reasonForChange'), array('id'=>'reasonForChange', 'class'=>'form-control ac-textarea', 'autocomplete'=>'off','rows'=>'4','maxlength'=>'400', 'ondrop'=>'return false;')) !!}
                                @if ($errors->has('reasonForChange'))
                                    <span class="error">{{ $errors->first('reasonForChange') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group ac-form-group">
                                <label class="ac-label" for="whenToStart">How quickly can you start work with Ofreg. </label>
                                {!! Form::textarea('whenToStart', old('whenToStart'), array('id'=>'whenToStart', 'class'=>'form-control ac-textarea', 'autocomplete'=>'off','rows'=>'4','maxlength'=>'400', 'ondrop'=>'return false;')) !!}
                                @if ($errors->has('whenToStart'))
                                    <span class="error">{{ $errors->first('whenToStart') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                              	<div id="career_html_element" class="g-recaptcha"></div>
                              	<div class="capphitcha" data-sitekey="{{Config::get('Constant.GOOGLE_CAPCHA_KEY')}}">
                                @if ($errors->has('g-recaptcha-response'))
                                	<label class="error help-block">{{ $errors->first('g-recaptcha-response') }}</label>
                                @endif
                              	</div>
                            </div>
                        </div>
                        <div class="col-sm-6"></div>
                        <div class="col-sm-6 n-tar-sm n-tal">
                            <button type="submit" id="career_submit" class="ac-btn-primary" title="Apply">Apply</button>
                        </div>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
<!-- Apply Now E -->

@endif

@section('page_scripts')
<script src="{{ $CDN_PATH.'assets/libraries/bootstrap-datetimepicker-master/js/bootstrap-datetimepicker.min.js' }}"></script>
<script>
    $(document).ready(function () {
        $('input[type="file"]').change(function (e) {
            var fileName = e.target.files[0].name;
            $(".file-name").html(fileName);
        });
    });

    $(document).ready(function () {
        $('#applyNowModal').on('shown.bs.modal', function() {
            $(".dob").datetimepicker({
                endDate : new Date(), 
                format: 'dd-mm-yyyy',
                showMeridian: true,
                todayBtn: true,
                autoclose: true,
                allowInputToggle: true,
            });
        });
    });

    $('#dob').on('click' ,function(){
    $('#applyNowModal').css({'overflow-y': 'hidden'});
    });

    $('#dob').on('change' ,function(){
    $('#applyNowModal').css({'overflow-y': 'auto'});
    });
</script>

<script type="text/javascript">
  	var sitekey = '{{Config::get("Constant.GOOGLE_CAPCHA_KEY")}}';
  	var oncareerloadCallback = function() {
    	grecaptcha.render('career_html_element', {
      		'sitekey' : sitekey
    	});
  	};
</script>
<script src="{{ $CDN_PATH.'assets/libraries/masked-input/jquery.mask.min.js' }}"></script>
<script src="{{ $CDN_PATH.'assets/js/packages/career/careerLead_validation.js' }}" type="text/javascript"></script>
<script src="https://www.google.com/recaptcha/api.js?onload=oncareerloadCallback&render=explicit" async defer></script>
@endsection
