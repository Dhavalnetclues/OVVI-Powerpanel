@if(!Request::ajax())
@extends('layouts.app')
@section('content')
@include('layouts.inner_banner')
@endif
@if(!Request::ajax())




<section class="inner-page-gap">
    @include('layouts.share-email-print')

    <div class="container">
        <div class="row">
            
            @include('complaint-services::frontview.complaint-services-left-panel')

            <div class="col-xl-9 n-mt-25 n-mt-xl-0 ac-form-wd" data-aos="fade-up">
            
                @if(isset($PAGE_CONTENT['response']) && !empty($PAGE_CONTENT['response']) && $PAGE_CONTENT['response'] != '[]')
                     {!! $PAGE_CONTENT['response'] !!}
                @endif

                {!! Form::open(['method' => 'post','class'=>'complaint_form','id'=>'complaint_form','files' => true]) !!}
                {{ Form::hidden('type', $type, array('id' => 'type_id')) }}
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group ac-form-group">
                            <label class="ac-label" for="first_name">Your Name <span class="star">*</span></label>
                            {!! Form::text('first_name', old('first_name'), array('id'=>'first_name', 'class'=>'form-control ac-input', 'id'=>'first_name', 'maxlength'=>'60', 'onpaste'=>'return false;', 'ondrop'=>'return false;')) !!}
                            @if ($errors->has('first_name'))
                            <span class="error">{{ $errors->first('first_name') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group ac-form-group">
                            <label class="ac-label" for="complaint_email">Your Email Address <span class="star">*</span></label>
                            {!! Form::text('complaint_email', old('complaint_email'), array('id'=>'complaint_email', 'class'=>'form-control ac-input',  'spellcheck'=>'true','autocomplete'=>'off','maxlength'=>'255', 'onpaste'=>'return false;', 'ondrop'=>'return false;')) !!}
                            @if ($errors->has('complaint_email'))
                            <span class="error">{{ $errors->first('complaint_email') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group ac-form-group">
                            <label class="ac-label" for="complaint_phoneno">Your Telephone Number <span class="star">*</span></label>
                            {!! Form::text('complaint_phoneno', old('complaint_phoneno'), array('id'=>'complaint_phoneno', 'class'=>'form-control ac-input',  'spellcheck'=>'true','autocomplete'=>'off','maxlength'=>'255', 'onpaste'=>'return false;', 'ondrop'=>'return false;','onkeypress'=>'javascript: return KeycheckOnlyPhonenumber(event);')) !!}
                            @if ($errors->has('complaint_phoneno'))
                            <span class="error">{{ $errors->first('complaint_phoneno') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group ac-form-group">
                            <label class="ac-label" for="friendEmail">Your PO Box & Physical Address <span class="star">*</span></label>
                            {!! Form::textarea('complaint_pobox', old('complaint_pobox'), array('id'=>'complaint_pobox','rows'=>'4', 'class'=>'form-control ac-textarea',  'spellcheck'=>'true','autocomplete'=>'off','maxlength'=>'600', 'onpaste'=>'return false;', 'ondrop'=>'return false;')) !!}
                            @if ($errors->has('complaint_pobox'))
                            <span class="error">{{ $errors->first('complaint_pobox') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group ac-form-group ac-active-select">
                            <label class="ac-label" for="company_name">Company Complained Against <span class="star">*</span></label>
                            <select class="selectpicker ac-input select2" data-width="100%" data-size="5"  name="company_name" id="company_name" title="Select Company" data-live-search="true" >
                                <option value="" id="company_name" class="selectpicker ac-input select2" disabled="disabled">Select Company</option>
                                @if(!empty($companylist) && count($companylist) > 0)
                                @foreach($companylist as $company)
                                <option value="{{ $company->id }}">{{ $company->varTitle }}</option>
                                @endforeach
                                @endif
                            </select>
                            @if ($errors->has('company_name'))
                            <span class="error">
                                {{ $errors->first('company_name') }}
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group ac-form-group">
                            <label class="ac-label" for="date_complaint">Date complaint filed with Company <span class="star">*</span></label>
                            <input type="text" class="form-control ac-input startDate" id="date_complaint" name="date_complaint" minlength="1" maxlength="255" spellcheck="true" onpaste="return false;" ondrop="return false;" autocomplete="off">
                            @if ($errors->has('date_complaint'))
                            <span class="error">
                                {{ $errors->first('date_complaint') }}
                            </span>
                            @endif
                        </div>
                    </div>                   

                    <div class="col-sm-6">
                        <div class="form-group ac-form-group">
                            <label class="ac-label" for="complaint_details">Full details of complaint <span class="star">*</span></label>
                            {!! Form::textarea('complaint_details', old('complaint_details'), array('id'=>'complaint_details', 'class'=>'form-control ac-textarea', 'rows'=>'4', 'spellcheck'=>'true','autocomplete'=>'off','maxlength'=>'600', 'onpaste'=>'return false;', 'ondrop'=>'return false;')) !!}
                            @if ($errors->has('complaint_details'))
                            <span class="error">{{ $errors->first('complaint_details') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group ac-form-group">
                            <label class="ac-label" for="complaint_cresponse">Response by Company <span class="star">*</span></label>
                            {!! Form::textarea('complaint_cresponse', old('complaint_cresponse'), array('id'=>'complaint_cresponse', 'class'=>'form-control ac-textarea', 'rows'=>'4', 'spellcheck'=>'true','autocomplete'=>'off','maxlength'=>'600', 'onpaste'=>'return false;', 'ondrop'=>'return false;')) !!}
                            @if ($errors->has('complaint_cresponse'))
                            <span class="error">{{ $errors->first('complaint_cresponse') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group ac-form-group">
                            <label class="ac-label" for="file">Upload Documents<span class="star">*</span></label>
                            <input type="file" class="form-control ac-input" id="file" name="file[]" autocomplete="off"  multiple>

                            <span class="ac-note">You can upload maximum 5 document(s) having the extension *.pdf, *.doc,*.docx and all files together must not exceed 10 MB.</span>
                            @if ($errors->has('file'))
                            <span class="error">{{ $errors->first('file') }}</span>
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
                            <button type="submit" id="contact_submit" title="Submit" class="ac-btn ac-btn-primary">Submit</button>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group ac-form-group">
                            <span class="ac-note">Note: Further information can be submitted directly to complaints@ofreg.ky.</span>
                        </div>
                    </div>

                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</section>
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

<script type="text/javascript">

    $('select[name=company_name]').select2({
        placeholder: "Select Company",
        width: '100%'
    }).on("change", function (e) {
        $('select[name=company_name]').closest('.has-error').removeClass('has-error');
        $("#company_name-error").remove();
    });

    $(document).ready(function () {
        $('input[type="file"]').change(function (e) {
            var fileName = e.target.files[0].name;
            $(".file-name").html(fileName);
        });
    });
</script>
<script src="https://maps.googleapis.com/maps/api/js?key={{Config::get('Constant.GOOGLE_MAP_KEY')}}&callback=initMap" async defer></script>
<script src="https://www.google.com/recaptcha/api.js?onload=onContactloadCallback&render=explicit" async defer></script>
<script src="{{ $CDN_PATH.'assets/js/packages/complaint-services/complaint-form.js' }}" type="text/javascript"></script>
<script src="{{ $CDN_PATH.'assets/libraries/bootstrap-datetimepicker-master/js/bootstrap-datetimepicker.min.js' }}" defer></script>
<script>
    /* Datetimepicker S */
    $(function () {
        $(".startDate").datetimepicker({
            format: 'dd-mm-yyyy HH:ii P',
            showMeridian: true,
            todayBtn: true,
            autoclose: true,
        });
    });
    /* Datetimepicker E */
</script>
@endif

@if(!Request::ajax())
@section('footer_scripts')

@endsection
@endsection
@endif